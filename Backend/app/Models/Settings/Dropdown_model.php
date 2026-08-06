<?php

namespace App\Models\Settings;

use CodeIgniter\Model;

class Dropdown_model extends Model
{
    protected $primaryKey = 'id_ua';
    protected $allowedFields = ['type', 'name', 'value', 'status', 'createdon', 'createdby', 'last_updated_by', 'last_updated_on', 'fk_id_dc'];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    public function getCategoryData()
    { // get category details
        $builder = $this->db->table('dropdown_category');
        $data = $builder->get()->getResultArray();
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
    public function categoryDetails()
    { // get category item details
        $builder = $this->db->table('dropdown_category as dc');
        $builder->select('d.id_ua,dc.name  as category_name,d.name as  Category_item,d.createdon');
        $builder->join('user_access as d', 'd.fk_id_dc = dc.id_dc ', "left");
        $builder->where('d.status', '1');
        $data = $builder->get()->getResultArray();
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
    public function savecategoryItem($newdata)
    { // save category item data
        $db = \Config\Database::connect();

        if ($newdata['fk_id_dc'] == 1) {
            $clientdata['status'] = 1;
            $clientdata['type'] = 1;
            $clientdata['createdon'] = $newdata['createdon'];
            $clientdata['createdby'] = $newdata['createdby'];
            $clientdata['client_name'] = $newdata['name'];
            $clientdata['partner_code'] = $newdata['partner_code'];
            $clientdata['last_updated_by'] = session()->get('id_user');
            $clientdata['last_updated_on'] = time();
            $builder = $this->db->table('client');
            $builder->insert($clientdata);
            $data['insertID'] = $db->insertID();
            if (isset($data['insertID'])) {
                $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                $password = array();
                $alpha_length = strlen($alphabet) - 1;
                for ($i = 0; $i < 6; $i++) {
                    $n = rand(0, $alpha_length);
                    $password[] = $alphabet[$n];
                }
                $hash = implode($password);
                // $hash = hash('sha256',$data['insertID'].''.$newdata['createdon'],false);
                $db = \Config\Database::connect();
                $builder = $this->db->table('client as c');
                $builder->set('c.hash', $hash);
                $builder->where('c.id_c', $data['insertID']);
                $builder->update();
            }
            // $data = $builder->get()->getResultArray();
        } else {
            $dropdowndata = [
                'fk_id_dc' => $newdata['category'],
                'name' => $newdata['name'],
                'createdon' => time(),
                'createdby' => session()->get('id_user'),
                'status' => '1',
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $builder = $this->db->table($dropdowndata);
            $builder->insert($newdata);
            // $data = $builder->get()->getResultArray();
            $data = true;
        }
        return $data;
    }
    public function saveCategory($newdata)
    { // save category data
        $db = \Config\Database::connect();
        $builder = $this->db->table('dropdown_category');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        $data = true;
        return $data;
    }
    public function deleteCategoryItem($categoryitemID)
    { // remove category item from table
        $db = \Config\Database::connect();
        $builder = $this->db->table('user_access');
        $builder->set('status', '0');
        $builder->where('id_d', $categoryitemID);
        $builder->update();
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getdropdownData($type)
    {
        $builder = $this->db->table('user_access as d');
        $builder->select('d.*');
        $builder->where('d.fk_id_dc', $type);
        $builder->where('d.status', '1');
        $data = $builder->get()->getResultArray();
        if (!empty($data)) {
            if (count($data) > 0) {
                return $data;
            } else {
                //echo "Error displaying info";
                return false;
            }
        } else {
            // echo "Database table empty";
            return false;
        }
    }


    public function access_users($access_id, $client)
    {
        $builder = $this->db->table('dropdown_users as du');
        $builder->select('du.id_du as access_id, u.name as name, u.last_name as last_name, c.client_name as client');
        $builder->join('users as u', 'u.id_user = du.fk_id_user', 'left');
        $builder->join('client as c', 'c.id_c = u.client_id', 'left');
        $builder->where('u.client_id', $client);
        $builder->where('du.fk_id_d', $access_id);
        $builder->where('du.fk_id_dc', 2);
        $builder->where('du.status', '1');
        $builder->where('u.valid', '1');
        $builder->orderBy('u.name', 'ASC');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function getCountrylist($type)
    {
        $builder = $this->db->table('dropdown as d');
        $builder->select('d.*');
        $builder->where('d.fk_id_dc', $type);
        $builder->where('d.status', '1');
        $data = $builder->get()->getResultArray();
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
    public function clientuserlist()
    {
        $builder = $this->db->table('client as c');
        $builder->select('c.*,count(du.fk_id_user) as user_count');
        $builder->join('dropdown_users as du', 'du.fk_id_d = c.id_c and du.fk_id_dc =1 and du.status =1 ', 'left');
        $builder->where('c.status', '1');
        $builder->groupBy('c.id_c');
        $builder->orderBy('c.id_c', 'ASC');
        $data = $builder->get()->getResultArray();
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
    public function updateCategory($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('dropdown_users as du');
        $builder->select('du.*');
        $builder->where('du.fk_id_user', $newdata['fk_id_user']);
        $builder->where('du.fk_id_dc', $newdata['fk_id_dc']);
        $builder->where('du.fk_id_d', $newdata['fk_id_d']);
        //$builder->where('du.status','1');
        $categoryData = $builder->get()->getResultArray();
        // print_r( $categoryData);

        if (count($categoryData) > 0 && $categoryData[0]['status'] == '1') {
            $data = "Data already exist!!!";
        } elseif (count($categoryData) > 0 && $categoryData[0]['status'] == '0') {
            $builder = $this->db->table('dropdown_users as du');
            $builder->set('status', '1');
            $builder->where('du.fk_id_user', $newdata['fk_id_user']);
            $builder->where('du.fk_id_dc', $newdata['fk_id_dc']);
            $builder->where('du.fk_id_d', $newdata['fk_id_d']);
            $builder->where('du.status', '0');
            $builder->update();
            $data = $builder->get()->getResultArray();
            if (count($data) > 0) {
                $data = "Data updated successfully";
            }
        } else {
            $builder = $this->db->table('dropdown_users');
            $builder->insert($newdata);
            $data = $builder->get()->getResultArray();
            if (count($data) > 0) {
                $data = "Data added successfully";
            }
        }
        return $data;
    }

    function add_new_access($newdata)
    {
        $builder = $this->db->table('user_access');
        $builder->insert($newdata);
        return true;
    }
    function updateCilenttoUsertable($client_id, $id_user)
    {
        $builder = $this->db->table('users as u');
        $builder->set('u.client_id', $client_id);
        $builder->where('u.id_user', $id_user);
        $builder->update();
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function updateCategoryItem($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('dropdown_users as du');
        $builder->select('du.*');
        $builder->where('du.fk_id_user', $newdata['fk_id_user']);
        $builder->where('du.fk_id_dc', $newdata['fk_id_dc']);
        $builder->where('du.fk_id_d', $newdata['fk_id_d']);
        //$builder->where('du.status','1');
        $categoryData = $builder->get()->getResultArray();
        // print_r( $categoryData);
        if (count($categoryData) > 0 && $categoryData[0]['status'] == '1') {
            $data = "Data already exist!!!";
        } elseif (count($categoryData) > 0 && $categoryData[0]['status'] == '0') {
            $builder = $this->db->table('dropdown_users as du');
            $builder->set('status', '1');
            $builder->where('du.fk_id_user', $newdata['fk_id_user']);
            $builder->where('du.fk_id_dc', $newdata['fk_id_dc']);
            $builder->where('du.fk_id_d', $newdata['fk_id_d']);
            $builder->where('du.status', '0');
            $builder->update();
            $data = $builder->get()->getResultArray();
            if (count($data) > 0) {
                $data = "Data updated successfully";
            }
        } else {
            $builder = $this->db->table('dropdown_users');
            $builder->insert($newdata);
            $data = $builder->get()->getResultArray();
            if (count($data) > 0) {
                $data = "Data added successfully";
            }
        }
        return $data;
    }
    public function getcolorstatusData()
    {
        $builder = $this->db->table(' color_statusname as cs');
        $builder->select('cs.*');
        $builder->where('cs.status', '1');
        $data = $builder->get()->getResultArray();
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
    function saveTemplate($newdata)
    {
        $builder = $this->db->table('user_access');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        $data = true;
        return $data;
    }
    /*End client admin  */
    function geteachtemplate($id_d)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('user_access as d');
        $builder->select('d.*');
        $builder->where('d.id_ua', $id_d);
        $builder->where('d.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function updateTemplate($newdata, $id_d)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('user_access as d');
        $builder->where('d.id_ua', $id_d);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function update_user_access_level($newdata, $id_ua)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('user_access as d');
        $builder->where('d.id_ua', $id_ua);
        $builder->update($newdata);
        return true;
    }

    function deleteTemplate($id_d, $newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('user_access as d');
        $builder->where('d.id_ua', $id_d);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function delete_user_access($newdata, $id_du)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('dropdown_users as d');
        $builder->where('d.id_du', $id_du);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getclientData()
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('client as c');
        $builder->select('c.*');
        $builder->where('c.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    // function updateuserclient($newdata)
    // {
    //     $db = \Config\Database::connect();
    //     $builder = $this->db->table('dropdown as d');
    //     $builder->where('d.fk_id_user', $newdata['fk_id_user']);
    //     $builder->where('du.fk_id_dc', $newdata['fk_id_dc']);
    //     $builder->where('du.fk_id_d', $newdata['fk_id_d']);
    //     $builder->update($newdata);
    //     $data = $builder->get()->getResultArray();
    //     return $data;
    // }
    function updateHashtoClient($data, $id_c)
    {
        $builder = $this->db->table('client as c');
        $builder->where('c.id_c', $id_c);
        $builder->update($data);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getusersdata()
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.*');
        // $builder->where('c.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function updateHashtouser($data, $id_user)
    {
        $builder = $this->db->table('users as u');
        $builder->where('u.id_user', $id_user);
        $builder->update($data);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getAllowedRoles($userId)
    {
        $db = \Config\Database::connect();

        $builder = $db->table('dropdown_users');
        $builder->select('fk_id_d');
        $builder->where('fk_id_user', $userId);
        $builder->where('fk_id_dc', 2); // role category
        $builder->where('status', '1');

        $userRoles = $builder->get()->getResultArray();

        if (!$userRoles) {
            return [];
        }

        $allowedRoles = [];

        foreach ($userRoles as $role) {
            switch ($role['fk_id_d']) {
                case 44: // Admin
                    $allowedRoles = array_merge($allowedRoles, [5, 3, 44]);
                    break;

                case 6: // Super Admin
                    $allowedRoles = array_merge($allowedRoles, [6, 44, 5, 3,46,67]);
                    break;
            }
        }

        // Remove duplicates
        return array_values(array_unique($allowedRoles));
    }
}
