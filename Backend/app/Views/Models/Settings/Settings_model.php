<?php

namespace App\Models\Settings;

use CodeIgniter\Model;

class Settings_model extends Model
{

    function giveclientrevieweraccess()
    {
        // $user_ids = '';
        $builder = $this->db->table('dropdown_users as dus');
        $builder->select('dus.fk_id_user');
        $builder->where('dus.fk_id_d', '45');
        $builder->where('dus.status', '1');
        $clientreviewrs = $builder->get()->getResultArray();
        $user_ids = array_column($clientreviewrs, 'fk_id_user');
        $builder = $this->db->table('users as u');
        $builder->select('u.*');
        if (!empty($user_ids)) {
            $builder->whereNotIn('u.id_user ', $user_ids);
        }
        $builder->where('u.client_id !=', '1');
        $builder->where('u.name !=', 'Demo User');
        // $builder->groupBy('id_user');
        // $builder->where('u.valid',1);
        $userdata = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        if (!empty($userdata)) {
            foreach ($userdata as $user) {
                $dropdowndata = [
                    'fk_id_user' => $user['id_user'],
                    'fk_id_dc' => 2,
                    'fk_id_d' => 45,
                    'status' => 1,
                    'createdon' => time(),
                    'createdby' => session()->get('id_user'),
                ];
                $builder = $this->db->table('dropdown_users');
                $builder->insert($dropdowndata);
            }
            echo 'Added Successfully';
        } else {
            echo lang('Messages.Error_0001');
        }
    }

    public function menu_view($cid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('menu_clientaccess as mc');
        $builder->select('GROUP_CONCAT(DISTINCT(mc.menu_id) ORDER BY mc.permission_id  ASC SEPARATOR ", ") as accessmenu');
        $builder->where('mc.client_id', $cid);
        $builder->where('mc.client_permission', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function marketplaceaccess($cid)
    {
        $builder = $this->db->table('marketplace as m');
        $builder->select('mc.mp_id');
        $builder->join('marketplace_clients as mc', 'mc.mp_id = m.mp_id', 'left');
        $builder->where('m.type', 1);
        $builder->where('mc.client_id', $cid);
        $builder->where('m.status !=', 0);
        $builder->where('mc.status !=', 0);
        $data = $builder->get()->getResultArray();
        // print_r($data);
        // exit();
        return $data;
    }
    public function insertClientPermission($newdata)
    {
        $q = $this->db->query("select * from menu_clientaccess where menu_id ='" . $newdata['menu_id'] . "' and client_id='" . $newdata['client_id'] . "'");
        $clientaccess = $q->getResultArray();
        //print_r(count($clientaccess));
        if (count($clientaccess) > 0) {
            $builder = $this->db->table('menu_clientaccess');
            $builder->where('permission_id', $clientaccess[0]['permission_id']);
            $builder->where('client_id', $clientaccess[0]['client_id']);
            $builder->update($newdata);
            $data = $builder->get()->getResultArray();
            if ($newdata['client_permission'] == 0) {
                $builder = $this->db->table('client as c');
                $builder->set('c.default_dashboard', 0);
                $builder->where('c.id_c', $clientaccess[0]['client_id']);
                $builder->update();
                $builder = $this->db->table('dropdown_users as du');
                $builder->select('du.*');
                $builder->where('du.fk_id_d', $clientaccess[0]['client_id']);
                $userdata = $builder->get()->getResultArray();
                foreach ($userdata as $eachuserdata) {
                    $builder = $this->db->table('users as u');
                    $builder->set('u.default_dashboard', 0);
                    $builder->where('u.id_user', $eachuserdata['fk_id_user']);
                    $builder->update();
                }
            }
        } else {
            $builder = $this->db->table('menu_clientaccess');
            $builder->insert($newdata);
            $data = $builder->get()->getResultArray();
        }
        if (isset($data)) {

            $data['status'] = "OK";
        } else {
            $data['status'] = "Error: ";
        }
        return $data;
    }

    public function get_my_client_list($user)
    {
        $builder = $this->db->table('projects_assignment as p');
        $builder->select('c.client_name,c.id_c,GROUP_CONCAT(DISTINCT(p.user_id)ORDER BY p.user_id ASC SEPARATOR ", ") as assigned_users');
        $builder->join('client as c', 'c.id_c = p.db_id', 'left');
        // $builder->where('p.user_id', $user);
        $builder->where('p.type_of_assignment', 8);
        $builder->where('p.status', '1');
        $builder->where('c.status', '1');
        $builder->groupBy('p.db_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    // public function clientuserlist()
    // {
    //     $type = ['70', '72']; // projects , projects and scorm list
    //     $builder = $this->db->table('client as c');
    //     $builder->select('c.*,count(du.fk_id_user) as user_count,GROUP_CONCAT(du.fk_id_d ORDER BY du.fk_id_d ASC SEPARATOR ",") as clientid,u.username');
    //     $builder->join('dropdown_users as du', 'du.fk_id_d = c.id_c and du.fk_id_dc = 1 and  du.status=1', 'left');
    //     $builder->join('users as u', 'u.id_user = c.createdby', 'left');
    //     $builder->whereIn('c.type', $type);
    //     $builder->where('c.status', '1');
    //     $builder->groupBy('c.id_c');
    //     $builder->orderBy('c.id_c', 'ASC');
    //     $data = $builder->get()->getResultArray();
    //     return $data;
    // }
    public function clientuserlist($partner_id)
    {
        $builder = $this->db->table('client as c');
        // $builder->select('c.*,count(u1.id_user) as user_count,GROUP_CONCAT(du.fk_id_d ORDER BY du.fk_id_d ASC SEPARATOR ",") as clientid,u.username,count(distinct(sc.scourse_id)) as sc_cr_as_id_count, ANY_VALUE(sca.sc_cr_as_id),count(distinct(du.fk_id_user)) as user_count');
        $builder->select('c.*');
        //  $builder->join('dropdown_users as du', 'du.fk_id_d = c.id_c and du.fk_id_dc = 1 and  du.status=1', 'left');
        //   $builder->join('users as u1', 'u1.id_user = du.fk_id_user and u1.valid =1', 'left');
        //  $builder->join('users as u', 'u.id_user = c.createdby', 'left');
        //   $builder->join('scorm_courses_assigned as sca', 'sca.client_id = c.id_c and sca.status=1', 'left');
        //    $builder->join('scorm_courses as sc', 'sc.scourse_id = sca.course_id', 'left');
        $builder->where('c.partner_code', $partner_id);
        $builder->where('c.status', '1');
        $builder->groupBy('c.id_c');
        $builder->orderBy('c.id_c', 'ASC');
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        // print_r($data);
        return $data;
    }
    public function clientlicensedata($cid)
    {
        $builder = $this->db->table('client as c');
        $builder->select('c.*,count(u.id_user) as user_count');
        // $builder->join('dropdown_users as du', 'du.fk_id_d = c.id_c and du.fk_id_dc = 1 and  du.status=1', 'left');
        $builder->join('users as u', 'u.id_user = c.createdby', 'left');
        $builder->where('c.id_c', $cid);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getclientuserlist($cid)
    {
        $builder = $this->db->table('client as c');
        $builder->select('c.*');
        $builder->where('c.id_c', $cid);
        $builder->where('c.status', '1');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function addClient($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('client as c');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        $insertID = $db->insertID();
        if ($insertID) {
            $newdata = [
                'type_of_assignment' => 8,
                'db_id' => $insertID,
                'user_id' => session()->get('id_user'),
                'created_on' => time(),
                'created_by' => session()->get('id_user'),
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
                'status' => '1'
            ];
            $builder = $this->db->table('projects_assignment');
            $builder->insert($newdata);
        }
        return $insertID;
    }
    public function editClientlist($newdata, $cid)
    {
        $builder = $this->db->table('client as c');
        $builder->where('c.id_c', $cid);
        $builder->where('c.status', '1');
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        // print_r($data);
        // exit();
        return $data;
    }
    public function deleteClientlist($id_c, $newdata)
    {
        $builder = $this->db->table(' client as c');
        $builder->where('c.id_c', $id_c);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function givedefaultdashboardaccess($icheck, $client_id)
    {
        $builder = $this->db->table(' client as c');
        $builder->set('c.default_dashboard', $icheck);
        $builder->where('c.id_c', $client_id);
        $builder->update();
        $data = $builder->get()->getResultArray();
        if (!empty($data)) {
            $builder = $this->db->table('client as cc');
            $builder->select('cc.default_dashboard');
            $builder->where('cc.id_c', $client_id);
            $builder->where('cc.status', '1');
            $data = $builder->get()->getResultArray();
            $_SESSION['users_default_dashboard'] = $data[0]['default_dashboard'];
            // exit();
            return $data;
        }
    }
    public function userdefaultdashvalue($id_user)
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.default_dashboard');
        $builder->where('u.id_user', $id_user);
        $builder->where('u.valid', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function giveusersdefaultdashboard($icheck, $id_user)
    {
        $builder = $this->db->table(' users as u');
        $builder->set('u.default_dashboard', $icheck);
        $builder->where('u.id_user', $id_user);
        $builder->update();
        $data = $builder->get()->getResultArray();
        if (!empty($data)) {
            $builder = $this->db->table('users as ud');
            $builder->select('ud.default_dashboard');
            $builder->where('ud.id_user', $id_user);
            $data = $builder->get()->getResultArray();
            $_SESSION['users_default_dashboard'] = $data[0]['default_dashboard'];
            // exit();
            return $data;
        }
    }
    function updateclientlogo($newdata, $cid)
    {
        $builder = $this->db->table(' client as c');
        $builder->where('c.id_c', $cid);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function partneruserlist($client)
    {
        // $type = ['70', '72']; // projects , projects and scorm list
        $builder = $this->db->table('client as c');
        $builder->select('c.*,count(u1.id_user) as user_count,GROUP_CONCAT(du.fk_id_d ORDER BY du.fk_id_d ASC SEPARATOR ",") as clientid,u.username');
        $builder->join('dropdown_users as du', 'du.fk_id_d = c.id_c and du.fk_id_dc = 1 and  du.status=1', 'left');
        $builder->join('users as u1', 'u1.id_user = du.fk_id_user and u1.valid =1', 'left');
        $builder->join('users as u', 'u.id_user = c.createdby', 'left');
        $builder->where('c.partner_code', $client);
        $builder->where('c.status', '1');
        $builder->groupBy('c.id_c');
        $builder->orderBy('c.id_c', 'ASC');
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        // print_r($data);
        return $data;
    }
    public function getCourseCounttoClient($cid)
    {
        $builder = $this->db->table('scorm_courses_assigned as s');
        $builder->select('count(s.course_id) as course_count');
        $builder->join('scorm_courses as sc', 'sc.scourse_id = s.course_id', 'left');
        $builder->where('s.client_id ', $cid);
        $builder->where('s.status !=', 0);
        // $builder->where('sc.status !=', 0);
        $data = $builder->get()->getRowArray();
        // print_r($data);
        // exit();
        return $data;
    }
    public function getmarketlearningtoClient($cid, $type)
    {
        //     print_r($type);
        // exit();
        $builder = $this->db->table('marketplace_clients as mc');
        $builder->select('count(mc.mp_id) as ml_count');
        $builder->join('marketplace as m', 'm.mp_id = mc.mp_id', 'left');
        $builder->where('mc.client_id ', $cid);
        $builder->where('mc.status !=', 0);
        $builder->where('m.type ', $type);
        $builder->where('m.status !=', 0);
        // $builder->where('sc.status !=', 0);
        $data = $builder->get()->getRowArray();
        // print_r($data);
        // exit();
        return $data;
    }
}
