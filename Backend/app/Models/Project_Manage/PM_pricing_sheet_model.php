<?php

namespace App\Models\Project_Manage;

use CodeIgniter\Model;

class PM_pricing_sheet_model extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = db_connect(); // Loading database
    }

    public function add_new_pricing_sheet($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_pricing');
        $builder->insert($newdata);
        $insertID = $db->insertID();
        if ($insertID) {
            $newdata = [
                'type_of_assignment' => 2,
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

    // public function get_pricing_sheet_list() // PC code
    // {
    //     $db = \Config\Database::connect();
    //     $builder = $this->db->table('project_pricing as du');
    //     $builder->select('du.*');
    //     $builder->where('du.status !=', 0);
    //     $builder->orderBy('du.ppid', 'DESC');
    //     $builder->limit(20);
    //     $data = $builder->get()->getResultArray();
    //     return  $data;
    // }
    public function get_pricing_sheet_list()
    {
        $client = session()->get('client');
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_pricing as du');
        $builder->select('du.*,c.client_name, concat(u.name," ",u.last_name) as fullname');
        $builder->join('projects_assignment as pa', 'pa.db_id = du.ppid', 'left');
        $builder->join('client as c', 'c.id_c = du.client', 'left');
        $builder->join('users as u', 'u.id_user = du.requested_by', 'left');
        $builder->where('pa.type_of_assignment', 2);
        $builder->where('pa.user_id', session()->get('id_user'));
        $builder->where('du.status !=', 0);
        $builder->groupby('du.ppid');
        $builder->orderBy('du.ppid', 'DESC');
        $builder->limit(20);

        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }

    public function check_purchase_orders($ppid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('purchase_order as po');
        $builder->select('po.*, u.name as name');
        $builder->join('users as u', 'u.id_user = po.created_by', 'left');
        $builder->where('po.project_id', $ppid);
        $builder->where('po.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function get_skill_assigned_list($client)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('skill_map as skills');
        $builder->select('u.name as fname, u.last_name as lname, drop.name as skill_name, skills.*');
        $builder->join('dropdown as drop', 'drop.value = skills.skill_id AND drop.fk_id_dc=8', 'left');
        $builder->join('users as u', 'u.id_user = skills.user_id AND u.valid=1', 'left');

        $builder->where('skills.status', 1);
        $builder->where('skills.client_id', $client);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function get_employee_skill($skill_val)
    {
        $db = \Config\Database::connect();

        $builder = $db->table('skill_map as skills');
        $builder->select('u.name as fname, u.last_name as lname, skills.*, 
                      assigned_sum.assigned_total, 
                      utilized_sum.utilized_total');

        // Subquery for assigned sum
        $builder->join(
            '(SELECT skill_id, user, SUM(effort) as assigned_total 
          FROM ucn_tl_effort 
          WHERE status = 1 
          GROUP BY skill_id, user) as assigned_sum',
            'assigned_sum.skill_id = skills.skill_id AND assigned_sum.user = skills.user_id',
            'left'
        );

        // Subquery for utilized sum
        $builder->join(
            '(SELECT skill_id, employee, SUM(effort) as utilized_total 
          FROM ucn_emp_effort 
          WHERE status =1
          GROUP BY skill_id, employee) as utilized_sum',
            'utilized_sum.skill_id = skills.skill_id AND utilized_sum.employee = skills.user_id',
            'left'
        );

        $builder->join('users as u', 'u.id_user = skills.user_id AND u.valid=1', 'left');
        $builder->where('skills.skill_id', $skill_val);
        $builder->where('skills.status', 1);

        $data = $builder->get()->getResultArray();

        return $data;
    }

    public function delete_skill($skill_map_id)
    {
        $db = \Config\Database::connect();
        $data = [
            'status' => 0,
            'last_updated_on' => time(),
            'last_updated_by' => session()->get('id_user')
        ];
        $builder = $this->db->table('skill_map as skills');
        $builder->where('skills.skill_map_id', $skill_map_id);
        $builder->update($data);
        if ($this->db->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function assign_skill_employee($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('skill_map');
        $builder->insert($newdata);
        $insertID = $db->insertID();
        if ($insertID) {
            return true;
        } else {
            return false;
        }
    }
    public function get_pricing_sheet_data($ppid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_pricing as du');
        $builder->select('du.*,c.client_name,concat(u.name," ",u.last_name) as account_manager');
        $builder->join('client as c', 'c.id_c = du.client', 'left');
        $builder->join('users as u', 'u.id_user = du.requested_by', 'left');
        $builder->where('du.ppid', $ppid);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function get_department($client_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('dropdown as drop');
        $builder->select('drop.*');
        $builder->where('drop.status', 1);
        $builder->where('drop.fk_id_dc', 8);
        $builder->where('drop.client_id	', $client_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function get_pricing_sheet_details($ppid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_pricing_details as du');
        $builder->select('du.*');
        $builder->where('du.ppid', $ppid);
        $builder->orderBy('du.type', 'ASC');
        $builder->where('du.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function get_ucn_effort_data($ucn_id, $type_of_resource)
    {
        $builder = $this->db->table('project_pricing_details as du');
        $builder->select('du.bidc');
        $builder->where('du.ucn_id', $ucn_id);
        $builder->where('du.status !=', 0);
        $builder->where('du.type_resource', $type_of_resource);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function add_new_cost($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_pricing_details');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        $data = true;
        return $data;
    }

    public function delete_cost($newdata, $bidc)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_pricing_details as pd');
        $builder->where('pd.bidc', $bidc);
        $builder->update($newdata);
        // $data = $builder->get()->getResultArray();
        $data = true;
        return $data;
    }
    public function update_pricing_sheet($newdata, $ppid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_pricing as pd');
        $builder->where('pd.ppid', $ppid);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function update_cost($newdata, $bidc)
    {
        $builder = $this->db->table('project_pricing_details as pd');
        $builder->where('pd.bidc', $bidc);
        $builder->update($newdata);
        return;
    }

    public function get_access_info($id, $type)
    {
        $builder = $this->db->table('projects_assignment as pa');
        $builder->select('pa.*, u.name as fname, u.last_name as lname');
        $builder->join('users as u', 'u.id_user = pa.user_id', "left");
        $builder->where('pa.type_of_assignment', $type);
        $builder->where('pa.status !=', 0);
        $builder->where('pa.db_id', $id);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function get_project_manager()
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.name as fname, u.last_name as lname, u.id_user as id_user');
        $builder->join('dropdown_users as du', 'du.fk_id_user = u.id_user and du.fk_id_dc = 2 and du.status = 1', 'left');
        $builder->where('du.fk_id_d', 4);
        // $builder->where('du.status', 1);
        // $builder->where('u.client_id', session()->get('client'));
        $builder->where('u.valid', 1);
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();

        return $data;
    }

    public function add_user_to_pricing($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('projects_assignment');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        $data = 'true';
        return $data;
    }

    public function delete_userassigned($newdata, $project_assign_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('projects_assignment as pa');
        $builder->where('pa.project_assign_id', $project_assign_id);
        $builder->update($newdata);
        // $data = $builder->get()->getResultArray();
        $data = true;
        return $data;
    }
    public function getclients_project_assignment()
    {
        $id_user = session()->get('id_user');
        $builder = $this->db->table('projects_assignment as pa');
        $builder->select('c.client_name,c.id_c,pa.db_id');
        $builder->join('client as c', 'c.id_c = pa.db_id', 'left');
        $builder->where('pa.user_id', $id_user);
        $builder->where('pa.type_of_assignment', 8);
        $builder->where('pa.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getSalesuseraccess($id_d)
    {
        $builder = $this->db->table('dropdown_users as du');
        $builder->select('concat(u.name," ",u.last_name) as fullname,u.id_user');
        $builder->join('users as u', 'u.id_user = du.fk_id_user and valid=1', 'left');
        $builder->where('du.fk_id_d', $id_d);
        $builder->where('du.status', 1);
        $builder->where('u.valid', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function updatelockstatus($newdata, $ppid)
    {
        $builder = $this->db->table('project_pricing as pp');
        $builder->where('pp.ppid', $ppid);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        if (!empty($data)) {
            $data['status'] = 'OK';
        } else {
            $data['status'] = 'error';
        }
        return $data;
    }
}
