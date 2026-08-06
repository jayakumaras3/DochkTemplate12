<?php

namespace App\Models\User_login;

use CodeIgniter\Model;

class Login_model extends Model
{

    protected $db;
    public function __construct()
    {
        $this->db = db_connect(); // Loading database
        // OR $this->db = \Config\Database::connect();
    }
    protected $table = 'users';
    protected $primaryKey = 'id_user';
    protected $allowedFields = ['username', 'name', 'last_name', 'email', 'password', 'app_username', 'app_password', 'client', 'designation', 'userlevel', 'timestamp', 'valid', 'timezone', 'userid', 'update_at', 'otp', 'createdby', 'createdon', 'client_id', 'partner_id', 'department', 'manager', 'level', 'gender', 'report_to_you', 'DOJ', 'engage_type', 'status', 'LWD', 'emp_id', 'last_updated_on', 'last_updated_by'];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    protected function beforeInsert(array $data)
    {
        $data = $this->passwordHash($data);
        return $data;
    }
    protected function beforeUpdate(array $data)
    {
        $data = $this->passwordHash($data);
        return $data;
    }
    protected function passwordHash(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = trim(password_hash($data['data']['password'], PASSWORD_DEFAULT));
            return $data;
        }
    }
    public function login_view($username)
    {
        $builder = $this->db->table("users as u");
        $builder->select('u.*,GROUP_CONCAT(DISTINCT(d1.id_ua) ORDER BY d1.name ASC SEPARATOR ", ") as userlevel,max(dc.id_dc),max(dc.name) as categoryName,u.client_id as client,GROUP_CONCAT(DISTINCT(c.validity) ORDER BY c.id_c  ASC SEPARATOR ", ") as validity,GROUP_CONCAT(DISTINCT(c.logo) ORDER BY c.id_c  ASC SEPARATOR ", ") as logo,GROUP_CONCAT(DISTINCT(c.logo_dark) ORDER BY c.id_c  ASC SEPARATOR ", ") as logo_dark,t.*,max(ua.dateandtime) as dateandtime,GROUP_CONCAT(DISTINCT(c.default_dashboard) ORDER BY c.id_c  ASC SEPARATOR ", ") as default_dashboard,u.default_dashboard as users_default_dashboard,profile_image,profile_foldername,GROUP_CONCAT(DISTINCT(c.partner_code) ORDER BY c.id_c  ASC SEPARATOR ", ") as partner_code');
        $builder->join('dropdown_users as du', 'du.fk_id_user = u.id_user and du.status =1', "left");
        $builder->join('dropdown_category as dc', 'dc.id_dc = du.fk_id_dc and dc.status =1', "left");
        $builder->join('user_access as d1', 'd1.id_ua = du.fk_id_d  and du.fk_id_dc=2 and d1.status =1', "left");
        $builder->join('client as c', 'c.id_c = du.fk_id_d  and du.fk_id_dc=1 and c.status =1', "left");
        $builder->join('timezone as t', 't.id_t = u.timezone  and t.status=1', "left");
        $builder->join('useractivity as ua', 'ua.username = u.username', 'left');
        // $builder->join('profile as p', 'p.username = u.username', 'left');
        $builder->where('u.username', $username);
        $builder->Orwhere('u.email', $username);
        $builder->where('u.valid', '1');
        $builder->where('du.status', '1');
        $builder->groupBY('u.id_user', 'asc');
        $user = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        if (!empty($user)) {
            if (count($user) > 0) {
                return $user;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function demo_quick_access($username)
    {
        $builder = $this->db->table("users as u");
        $builder->select('u.*,GROUP_CONCAT(DISTINCT(d1.id_ua) ORDER BY d1.name ASC SEPARATOR ", ") as userlevel,max(dc.id_dc),max(dc.name) as categoryName,GROUP_CONCAT(DISTINCT(c.id_c) ORDER BY c.id_c  ASC SEPARATOR ", ") as client,GROUP_CONCAT(DISTINCT(c.validity) ORDER BY c.id_c  ASC SEPARATOR ", ") as validity,GROUP_CONCAT(DISTINCT(c.logo) ORDER BY c.id_c  ASC SEPARATOR ", ") as logo,GROUP_CONCAT(DISTINCT(c.logo_dark) ORDER BY c.id_c  ASC SEPARATOR ", ") as logo_dark,t.*,max(ua.dateandtime) as dateandtime,GROUP_CONCAT(DISTINCT(c.default_dashboard) ORDER BY c.id_c  ASC SEPARATOR ", ") as default_dashboard,u.default_dashboard as users_default_dashboard,u.profile_image,u.profile_foldername,GROUP_CONCAT(DISTINCT(c.partner_code) ORDER BY c.id_c  ASC SEPARATOR ", ") as partner_code');
        $builder->join('dropdown_users as du', 'du.fk_id_user = u.id_user and du.status =1', "left");
        $builder->join('dropdown_category as dc', 'dc.id_dc = du.fk_id_dc and dc.status =1', "left");
        $builder->join('user_access as d1', 'd1.id_ua = du.fk_id_d  and du.fk_id_dc=2 and d1.status =1', "left");
        $builder->join('client as c', 'c.id_c = du.fk_id_d  and du.fk_id_dc=1 and c.status =1', "left");
        $builder->join('timezone as t', 't.id_t = u.timezone  and t.status=1', "left");
        $builder->join('useractivity as ua', 'ua.username = u.username', 'left');
        // $builder->join('profile as p', 'p.username = u.username', 'left');
        $builder->where('u.username', $username);
        $builder->Orwhere('u.email', $username);
        $builder->where('u.valid', '1');
        $builder->where('du.status', '1');
        $builder->groupBY('u.id_user', 'asc');
        $user = $builder->get()->getResultArray();
        if (!empty($user)) {
            if (count($user) > 0) {
                return $user;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getManagers()
    {
        $builder = $this->db->table("users as u");
        $builder->select('u.*');
        $builder->where('u.valid', '1');
        $builder->where('u.client_id', '1');
        $builder->where('u.report_to_you', '2');
        $builder->orderBy('u.name', 'asc');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function users_list($clientid)
    {
        $builder = $this->db->table("users as u");
        $builder->select('u.*');
        $builder->where('u.valid', '1');
        $builder->where('u.client_id', $clientid);
        $builder->orderBy('u.name', 'asc');
        $data = $builder->get()->getResultArray();
        // print_r($data);
        // exit();
        if (!empty($data)) {
            if (count($data) > 0) {
                return $data;
            } else {
                echo "Error displaying info";
                return false;
            }
        } else {
            echo "Database table empty";
            return false;
        }
    }
    public function report_to_list($clientid)
    {
        $builder = $this->db->table("users as u");
        $builder->select('u.*');
        $builder->where('u.valid', '1');
        $builder->where('u.report_to_you', 2);
        $builder->where('u.client_id', $clientid);
        $builder->orderBy('u.name', 'asc');
        $data = $builder->get()->getResultArray();
        // print_r($data);
        // exit();
        if (!empty($data)) {
            if (count($data) > 0) {
                return $data;
            } else {
                echo "Error displaying info";
                return false;
            }
        } else {
            echo "Database table empty";
            return false;
        }
    }
    public function users_view($clientid)
    {
        $builder = $this->db->table("users as u");
        //   $builder->select('u.*,u.client_id as clientid,c.client_name as client,ua.dateandtime');
        $builder->select('u.*,u.client_id as clientid,u.last_updated_on as dateandtime,GROUP_CONCAT(distinct(d1.name) ORDER BY d1.name ASC SEPARATOR ", ") as userlevel');
        //    $builder->join('client as c', 'c.id_c = u.client_id  and c.status =1', "left");
        $builder->join('useractivity as ua', 'ua.username = u.email and ua.dateandtime != "" ', 'left');
        $builder->join('dropdown_users as du', 'du.fk_id_user = u.id_user and du.status !=0 ', "left");
        $builder->join('user_access as d1', 'd1.id_ua = du.fk_id_d  and du.fk_id_dc=2', "left");

        //    $builder->join('scorm_users_courses_assigned as su', 'su.id_user = u.id_user and su.status=1', 'left');
        $builder->where('u.valid', '1');
        $builder->where('u.client_id', $clientid);
        $builder->groupBy('u.id_user', 'asc');
        $builder->orderBy('u.name', 'asc');
        $data = $builder->get()->getResultArray();
        // print_r($data);
        // exit();
        if (!empty($data)) {
            if (count($data) > 0) {
                return $data;
            } else {
                // echo "Error displaying info";
                return false;
            }
        } else {
            // echo "Database table empty";
            return false;
        }
    }
    function usersdata_view()
    {
        $builder = $this->db->table("users as u");
        $builder->select('u.*,GROUP_CONCAT(c.id_c ORDER BY c.id_c  ASC SEPARATOR ", ") as clientid,GROUP_CONCAT(c.client_name ORDER BY c.client_name ASC SEPARATOR ", ") as client,GROUP_CONCAT(d1.name ORDER BY d1.name ASC SEPARATOR ", ") as userlevel,max(dc.id_dc),max(dc.name) as categoryName,t.*');
        $builder->join('dropdown_users as du', 'du.fk_id_user = u.id_user ', "left");
        $builder->join('dropdown_category as dc', 'dc.id_dc = du.fk_id_dc ', "left");
        $builder->join('user_access as d', 'd.id_ua = du.fk_id_d  and du.fk_id_dc=1', "left");
        $builder->join('user_access as d1', 'd1.id_ua = du.fk_id_d  and du.fk_id_dc=2', "left");
        $builder->join('client as c', 'c.id_c = du.fk_id_d  and du.fk_id_dc=1 and du.status =1', "left");
        $builder->join('timezone as t', 't.id_t = u.timezone  and t.status=1', "left");
        $builder->where('u.valid', '1');
        $builder->groupBy('u.id_user', 'asc');
        $builder->orderBy('u.id_user', 'asc');
        $data = $builder->get()->getResultArray();
        //  print_r($data);
        // exit();
        if (!empty($data)) {
            if (count($data) > 0) {
                return $data;
            } else {
                echo "Error displaying info";
                return false;
            }
        } else {
            echo "Database table empty";
            return false;
        }
    }

    public function updateUserActive($newdata)
    {
        /*$builder = $this->db->table('useractivity as ua');
       /*$builder->select('ua.*');
       $builder->where('username',$newdata['username']);
       $data = $builder->get()->getResultArray();
       /*if(isset($data['0']['username'])){
           $builder->where('username',$newdata['username']);
           $builder->update($newdata);
           $data = $builder->get()->getResultArray();
           return  $data;
       }else{*/
        // print_r(session()->get('id_user'));
        // exit();
        $id_user = session()->get('id_user');
        $builder = $this->db->table('users');
        $builder->set('last_updated_on', time());
        $builder->where('id_user', $id_user);
        $builder->where('valid', 1);
        $builder->update();


        $builder = $this->db->table('useractivity');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        $data = true;
        return $data;
        //}
    }
    function gettimezonedata()
    {
        $builder = $this->db->table('timezone as t');
        $builder->select("t.*");
        $builder->where('t.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function verifyEmail($email)
    {
        $builder = $this->db->table('users as u');
        $builder->select("u.*");
        $builder->where('u.email', $email);
        $builder->where('u.valid', 1);
        $data = $builder->get();
        if (count($data->getResultArray()) == 1) {
            $userid = uniqid();
            $builder = $this->db->table('users');
            $builder->where('email', $email);
            $builder->update(['userid' => $userid]);
            if ($this->db->affectedRows() == 1) {
                $builder = $this->db->table('users as u');
                $builder->select("u.*");
                $builder->where('u.email', $email);
                $builder->where('u.valid', 1);
                $postdata = $builder->get();
                return $postdata->getRowArray();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    function updateAt($id)
    {
        $builder = $this->db->table('users');
        $builder->where('userid', $id);
        $builder->update(['updated_at' => date('Y-m-d h:i:s')]);
        if ($this->db->affectedRows() == 1) {
            return true;
        } else {
            return false;
        }
    }
    function verifyToken($token)
    {
        $builder = $this->db->table('users as u');
        $builder->select("u.*");
        $builder->where('u.userid', $token);
        $builder->where('u.valid', 1);
        $data = $builder->get();
        if (count($data->getResultArray()) == 1) {
            return $data->getRowArray();
        } else {
            return false;
        }
    }
    function updatePassword($id, $pwd)
    {
        $builder = $this->db->table('users');
        $builder->where('userid', $id);
        $builder->update(['password' => $pwd]);
        if ($this->db->affectedRows() == 1) {
            return true;
        } else {
            return false;
        }
    }
    function verifyOTP($otp)
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.*');
        $builder->where('u.otp', $otp);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function namessearch_view($name)
    {
        $this->db->query('SET SQL_BIG_SELECTS=1');
        $where = "(u.name LIKE '%$name%')";
        $builder = $this->db->table("users as u");
        $builder->select('u.*,u.client_id clientid,GROUP_CONCAT(DISTINCT(c.client_name) ORDER BY c.client_name ASC SEPARATOR ", ") as client,max(ua.dateandtime) as dateandtime,c.license');
        $builder->join('client as c', 'c.id_c = u.client_id  and c.status=1', "left");
        $builder->join('useractivity as ua', 'ua.username = u.username and ua.dateandtime != "" ', 'left');
        $builder->where($where);
        $builder->where('u.valid', 1);
        $builder->groupBy('u.id_user', 'asc');
        $builder->orderBy('u.id_user', 'asc');
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        if (!empty($data)) {
            if (count($data) > 0) {
                return $data;
            } else {
                echo "Error displaying info";
                return false;
            }
        } else {
            echo "Database table empty";
            return false;
        }
    }
    public function lastnamessearch_view($name)
    {
        $this->db->query('SET SQL_BIG_SELECTS=1');
        $where = "(u.last_name LIKE '%$name%')";
        $builder = $this->db->table("users as u");
        $builder->select('u.*,u.client_id clientid,GROUP_CONCAT(DISTINCT(c.client_name) ORDER BY c.client_name ASC SEPARATOR ", ") as client,max(ua.dateandtime) as dateandtime,c.license');
        $builder->join('client as c', 'c.id_c = u.client_id  and c.status=1', "left");
        $builder->join('useractivity as ua', 'ua.username = u.username and ua.dateandtime != "" ', 'left');
        $builder->where($where);
        $builder->where('u.valid', 1);
        $builder->groupBy('u.id_user', 'asc');
        $builder->orderBy('u.id_user', 'asc');
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        if (!empty($data)) {
            if (count($data) > 0) {
                return $data;
            } else {
                echo "Error displaying info";
                return false;
            }
        } else {
            echo "Database table empty";
            return false;
        }
    }
    public function usernamessearch_view($username)
    {
        $this->db->query('SET SQL_BIG_SELECTS=1');
        $where = "(u.username LIKE '%$username%')";
        $orwhere = "(u.email LIKE '%$username%')";
        $builder = $this->db->table("users as u");
        $builder->select('u.*,u.client_id as clientid,c.client_name as client,max(ua.dateandtime) as dateandtime,c.license');
        $builder->join('client as c', 'c.id_c = u.client_id and c.status =1', "left");
        $builder->join('useractivity as ua', 'ua.username = u.username and ua.dateandtime != "" ', 'left');
        $builder->groupStart();
        $builder->where($where);
        $builder->orwhere($orwhere);
        $builder->groupEnd();
        $builder->where('u.valid', 1);
        $builder->groupBy('u.id_user', 'asc');
        $builder->orderBy('u.id_user', 'asc');
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        if (!empty($data)) {
            if (count($data) > 0) {
                return $data;
            } else {
                echo "Error displaying info";
                return false;
            }
        } else {
            echo "Database table empty";
            return false;
        }
    }
    function updateUserlevel($id_user, $userlevel)
    {
        $builder = $this->db->table('dropdown_users');
        $builder->insert(['fk_id_user' => $id_user, 'fk_id_d' => $userlevel, 'fk_id_dc' => 2, 'status' => 1]);
        if ($this->db->affectedRows() == 1) {
            return true;
        } else {
            return false;
        }
    }
}
