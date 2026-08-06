<?php

namespace App\Models\User_login;

use CodeIgniter\Model;

class Users_model extends Model
{
    protected $table = 'users_dropdown';
    protected $primaryKey = 'ud_id';
    // protected $allowedFields = ['ud_type', 'ud_name', 'ud_code', 'status', 'createdon', 'createdby', 'deletedon', 'deletedby'];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    public function updateUsersData($id_user, $newdata)
    { // admin/user update profile
        $builder = $this->db->table('users');
        $builder->where('id_user', $id_user);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return true;
    }

    public function deleteDoc($et_doc_id, $newdata)
    {
        $builder = $this->db->table('et_documents');
        $builder->where('et_doc_id', $et_doc_id);
        $builder->update($newdata);
        return true;
    }
    function get_personal_data($username)
    {
        $builder = $this->db->table('users_personal_data as upd');
        $builder->select('upd.*');
        $builder->where('upd.userid', $username);
        $builder->where('upd.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function get_doc_user($id_user)
    {
        $builder = $this->db->table('et_documents as etd');
        $builder->select('etd.*');
        $builder->where('etd.id_user', $id_user);
        $builder->where('etd.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function adddocument($newdata)
    {
        $builder = $this->db->table('et_documents');
        $builder->insert($newdata);
        return true;
    }
    function add_personal_data_user($newdata)
    {
        $builder = $this->db->table('users_personal_data');
        $builder->insert($newdata);
        return true;
    }

    public function update_personal_data_user($newdata, $upd_id)
    {
        $builder = $this->db->table('users_personal_data as up');
        $builder->where('up.upd_id', $upd_id);
        $builder->update($newdata);
        return true;
    }

    function getapp_username($id_user)
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.app_username');
        $builder->where('id_user', $id_user);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function usersedit_view($id_user = NULL)
    { // user edit page
        $builder = $this->db->table("users as u");
        $builder->select('u.*,c.*,t.*,u.username as user,u.status as ustatus');
        $builder->join('dropdown_users as du', 'du.fk_id_user = u.id_user ', "left");
        $builder->join('client as c', 'c.id_c = u.client_id', "left");
        $builder->join('timezone as t', 't.id_t = u.timezone and t.status =1', "left");
        // $builder->join('profile as p', 'p.username = u.username', "left");
        $builder->where('id_user', $id_user);
        $builder->where('valid', 1);
        $data = $builder->get()->getResultArray();
        // echo "<pre>";
        // print_r($data);

        // // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }

    public function deleteUser($id_user)
    { // user delete 
        $db = \Config\Database::connect();
        $builder = $this->db->table('users');
        $builder->set('valid', '0');
        $builder->set('updatedby', session()->get('id_user'));
        $builder->set('updatedon', time());
        $builder->where('id_user', $id_user);
        $builder->update();
        $data = $builder->get()->getResultArray();
        if ($data) {
            $builder = $this->db->table('dropdown_users');
            $builder->set('status', '0');
            $builder->set('deletedby', session()->get('id_user'));
            $builder->set('deletedon', time());
            $builder->where('fk_id_user', $id_user);
            $builder->update();
        }
        return $data;
    }
    public function userActiveData($username)
    { // get useractive details
        $builder = $this->db->table('useractivity as ua');
        $builder->select('ua.*');
        $builder->where('ua.username', $username);
        $builder->where('ua.dateandtime != ', ' ');
        $builder->orderBy('ua.id', 'DESC');
        $builder->limit(2);
        $data = $builder->get()->getResultArray();
        if (!empty($data)) {
            if (count($data) < 2) {
                return;
            } else {
                return $data;
            }
        }
    }
    public function clientUserlevel_view($id_user)
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.*,du.*,dc.name  as category_name,d.name as  Category_item,c.client_name,d.createdon');
        $builder->join('dropdown_users as du', 'du.fk_id_user = u.id_user ', "left");
        $builder->join('dropdown_category as dc', 'dc.id_dc = du.fk_id_dc ', "left");
        $builder->join('user_access as d', 'd.id_ua = du.fk_id_d and du.fk_id_dc =2', "left");
        $builder->join('client as c', 'c.id_c = du.fk_id_d and du.fk_id_dc =1', "left");
        $builder->where('u.valid', '1');
        $builder->where('du.status', '1');
        $builder->where('id_user', $id_user);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getUserCategoryById($id_du)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('dropdown_users as du');

        $builder->select('du.id_du, du.fk_id_user, du.fk_id_d, du.fk_id_dc, u.client_id');
        $builder->join('users as u', 'u.id_user = du.fk_id_user', 'left');

        $builder->where('du.id_du', $id_du);
        $builder->where('du.status', 1); // only active records

        return $builder->get()->getRow(); // single row
    }
    public function getUserById($id_user)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users');

        $builder->select('id_user, client_id');
        $builder->where('id_user', $id_user);

        return $builder->get()->getRow();
    }
    public function deleteUserCategory($newdata, $id_du)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('dropdown_users as du');
        $builder->where('du.id_du', $id_du);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function deleteUserlist($id_c)
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.id_user, u.name, u.last_name, u.LWD, u.designation, u.last_updated_on, u.last_updated_by ,CONCAT(u1.name," ", u1.last_name) as deleted_name');
        //     $builder->join('dropdown_users as du', 'du.fk_id_user = u.id_user ', "left");
        //   $builder->join('client as c', 'c.id_c = du.fk_id_d and du.fk_id_dc =1', "left");
        $builder->join('users as u1', 'u1.id_user = u.last_updated_by', "left");
        $builder->where('u.client_id', $id_c);
        $builder->where('u.valid', '0');
        $data = $builder->get()->getResultArray();
        // print_r( $id_c);
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }

    public function getUserclientlist_onlyname($client_id)
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.id_user, u.name, u.last_name');
        $builder->where('u.client_id', $client_id);
        $builder->where('u.valid', '1');
        $builder->orderBy('u.name', 'ASC');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getUserclientlist($client_id)
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.*,u.last_updated_on as dateandtime, m.name as managername');
        $builder->join('users as m', 'm.id_user = u.manager', "left");
        $builder->where('u.client_id', $client_id);
        $builder->where('u.valid', '1');
        $builder->orderBy('u.name', 'ASC');
        $data = $builder->get()->getResultArray();
        //  echo $this->db->getLastQuery();
        // print_r($data);
        //exit();
        return $data;
    }
    public function getUserclient($client_id)
    {
        $builder = $this->db->table(' projects as p');
        $builder->select('p.*,concat(u.name," ",u.last_name) as fullname,u.id_user');
        $builder->join('projects_assignment as pa', 'pa.db_id = p.projectid', 'left');
        $builder->join('users as u', 'u.id_user = pa.user_id', 'left');
        $builder->where('u.client_id', $client_id);
        $builder->where('p.status ', 1);
        $builder->where('pa.type_of_assignment', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getUsersassignedcourse($client_id, $course_id)
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.name,u.last_name,u.id_user');
        $builder->join('scorm_users_courses_assigned as suc', 'suc.id_user =u.id_user and suc.status=1', 'left');
        $builder->where('suc.course_id', $course_id);
        $builder->where('u.client_id', $client_id);
        $builder->where('u.valid', '1');
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    function importnewusers($sheetData)
    {
        if (trim($sheetData[0][0]) == 'email') {
            foreach ($sheetData as $Row) {

                if (isset($Row[0]) && isset($Row[1])) {
                    if (trim($Row[0]) == 'email') {
                        continue;
                    }
                    $pagename = "";
                    if (isset($Row[0])) {
                        $username = utf8_encode(trim($Row[0]));
                    }
                    if (isset($Row[0])) {
                        $email = utf8_encode(trim($Row[0]));
                    }

                    $first_name = "";
                    if (isset($Row[1])) {
                        $first_name = trim($Row[1]);
                    }
                    $last_name = "";
                    if (isset($Row[2])) {
                        $last_name = trim($Row[2]);
                    }
                    $app_username = "";
                    if (isset($Row[3])) {
                        $app_username = trim($Row[3]);
                    }

                    $app_password = "";
                    if (isset($Row[4])) {
                        $app_password = trim($Row[4]);
                    }
                    $password = "";
                    if (isset($Row[5])) {
                        $password = trim($Row[5]);
                    } else {
                        $password = "Welcomedochek@123";
                    }
                    $clientid = session()->get('client');
                    $insertdata = [
                        'username' => $username,
                        'name' => $first_name,
                        'last_name' => $last_name,
                        'password' => trim(password_hash($password, PASSWORD_DEFAULT)),
                        'app_username' => $app_username,
                        'app_password' => $app_password,
                        'client_id' => $clientid,
                        'email' => $email,
                        'valid' => '1',
                        'createdon' => time(),
                        'last_updated_on' => time()
                    ];
                    // print_r($insertdata);
                    // exit();
                    $builder = $this->db->table('users as u');
                    $builder->select('u.*');
                    $builder->where('u.username', $username);
                    $userdata = $builder->get()->getResultArray();
                    if (empty($userdata)) {
                        $db = \Config\Database::connect();
                        $builder = $this->db->table('users');
                        $builder->insert($insertdata);
                        $insertID = $db->insertID();
                        $userinsertdata = $builder->get()->getResultArray();
                        if (!empty($userinsertdata)) {
                            $userlevel = [
                                'fk_id_user' => $insertID,
                                'fk_id_dc' => 2,
                                'fk_id_d' => 3,
                                'status' => 1,
                                'createdby' => session()->get('id_user'),
                                'createdon' => time(),
                                'last_updated_on' => time(),
                                'last_updated_by' => session()->get('id_user')
                            ];

                            $builder = $this->db->table('dropdown_users');
                            $builder->insert($userlevel);
                            // $data = $builder->get()->getResultArray();

                            $clientdata = [
                                'fk_id_user' => $insertID,
                                'fk_id_dc' => 1,
                                'fk_id_d' => $clientid,
                                'status' => 1,
                                'createdby' => session()->get('id_user'),
                                'createdon' => time(),
                                'last_updated_on' => time(),
                                'last_updated_by' => session()->get('id_user')
                            ];
                            $builder = $this->db->table('dropdown_users');
                            $builder->insert($clientdata);
                            // $data = $builder->get()->getResultArray();
                        }
                    } else {
                        $data['error'] = 'Email ID' . $userdata[0]['username'] . ' already Exists    ';
                        return $data;
                    }
                }
            }
        } else {
            $data['error'] = 'Data not found!. Importing Wrong format ';
            return $data;
        }
        $data['success'] = 'Data imported Successfully ';
        return $data;
    }
    public function activateuser($id_user, $cid)
    {
        $builder = $this->db->table('users');
        $builder->set('valid', '1');
        $builder->set('client_id', $cid);
        $builder->where('id_user', $id_user);
        $builder->update();
        $data = $builder->get()->getResultArray();
        if ($data) {
            $builder = $this->db->table('dropdown_users');
            $builder->set('status', '1');
            $builder->where('fk_id_user', $id_user);
            $builder->where('fk_id_d', $cid);
            $builder->orwhere('fk_id_dc', 2);
            $builder->update();
            $data = $builder->get()->getResultArray();

            $builder = $this->db->table('dropdown_users');
            $builder->set('status', '1');
            $builder->where('fk_id_user', $id_user);
            $builder->where('fk_id_d', $cid);
            $builder->orwhere('fk_id_dc', 1);
            $builder->update();

            $data = $builder->get()->getResultArray();

            return $data;
        } else {
            return false;
        }
    }
    function addprofileImgData($newdata)
    {
        $builder = $this->db->table('users');
        $builder->set('profile_image', $newdata['profile_image']);
        $builder->set('profile_foldername', $newdata['profile_foldername']);
        $builder->where('id_user', session()->get('id_user'));
        $builder->update();
        $data = $builder->get()->getResultArray();
        if (!empty($data)) {
            // $builder = $this->db->table('profile');
            // $builder->where('username', session()->get('username'));
            // $builder->update($newdata);

            $_SESSION['profile_image'] = $newdata['profile_image'];
            $_SESSION['profile_foldername'] = $newdata['profile_foldername'];
        } else {
            $builder = $this->db->table('users');
            $builder->set('profile_image', $newdata['profile_image']);
            $builder->set('profile_foldername', $newdata['profile_foldername']);
            $builder->where('id_user', session()->get('id_user'));
            $builder->update();

            // $builder = $this->db->table('profile');
            // $builder->insert($newdata);
            // $data = $builder->get()->getResultArray();
            if (!empty($data)) {
                $_SESSION['profile_image'] = $newdata['profile_image'];
                $_SESSION['profile_foldername'] = $newdata['profile_foldername'];
            }
        }
        return $data;
    }
    function getprofiledata()
    {
        $builder = $this->db->table('profile as pf');
        $builder->select('pf.*');
        $builder->where('pf.username', session()->get('username'));
        $builder->where('typeofvalue', '1');
        $builder->orderBy('profileid', 'DESC');
        $builder->limit('1');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getuserlevel($id_user)
    {
        $builder = $this->db->table('dropdown_users as d');
        $builder->select('d.fk_id_d');
        $builder->where('fk_id_user', $id_user);
        $builder->where('fk_id_dc', '2');
        $builder->where('status', '1');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getClientName()
    {
        $builder = $this->db->table('client as c');
        $builder->select('c.*');
        $builder->where('c.status', '1');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getpartnerdata($client_id)
    {
        $builder = $this->db->table('client as c');
        $builder->select('c.*');
        $builder->where('id_c', $client_id);
        $builder->where('c.status', '1');
        $data = $builder->get()->getResultArray();
        return $data;
    }
}
