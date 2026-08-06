<?php

namespace App\Models\Project_Manage;

use CodeIgniter\Model;

class PM_projects_model extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = db_connect(); // Loading database
    }
    public function get_projects_list($user)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('projects as pro');
        $builder->select('pro.*, c.client_name as client_name, c.logo as logo');
        $builder->join('projects_assignment as pa', 'pa.db_id = pro.projectid', 'left');
        $builder->where('pro.status >', 0);
        $builder->where('pro.status !=', 10);
        $builder->where('pro.status !=', 4);
        $builder->join('client as c', 'c.id_c = pro.client', "left");
        $builder->orderBy('pro.projectid', 'DESC');
        $builder->where('pa.type_of_assignment', 1);
        $builder->where('pa.user_id', session()->get('id_user'));
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function get_ucn_status($year)
    {

        $poSubQuery = $this->db->table('project_ucn_link as pl')
            ->select('pl.ucn, SUM(po.po_value) as total_po_value')
            ->join('purchase_order as po', 'po.po_id = pl.table_id and po.status !=0', 'left')
            ->where('pl.type_of_link', 2)
            ->where('pl.status', 1)
            ->groupBy('pl.ucn');


        $prevyear = $year - 1;
        $builder = $this->db->table('project_ucn as ucn');
        $builder->select('ucn.*, c.client_name, c.id_c as client, ANY_VALUE(p.projectname) as projectname');
        $builder->select("GROUP_CONCAT(DISTINCT(u.name) SEPARATOR ', ') as project_manager");
        $builder->select('po_sum.total_po_value as po_value');

        $builder->join('projects as p', 'p.ucn = ucn.ucn_id', 'left');
        $builder->join('project_ucn_link as pul', 'pul.ucn = ucn.ucn_id', 'left');
        $builder->join('projects_assignment as pa', 'pa.db_id = ucn.ucn_id AND pa.type_of_assignment = 5 AND pa.status = 1', 'left');
        $builder->join('project_ucn_percent as per', 'per.ucn_id = ucn.ucn_id', 'left');
        $builder->join("({$poSubQuery->getCompiledSelect()}) as po_sum", 'po_sum.ucn = ucn.ucn_id', 'left');
        $builder->join('client as c', 'c.id_c = ucn.client', 'left');
        $builder->join('users as u', 'u.id_user = pa.user_id', 'left');
        $builder->where('ucn.status !=', 0);
        $builder->where('ucn.status !=', 10);
        $builder->groupStart()
            ->orWhere('YEAR(FROM_UNIXTIME(ucn.last_updated_on))', $year)
            ->groupEnd();
        $builder->groupBy('ucn.ucn_id');
        $builder->orderBy('ucn.client', 'ASC');

        $data = $builder->get()->getResultArray();

        return $data;
    }

    public function get_resource_alloc_id($proid, $week, $skill_val)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('et_resource_allocation  as pro');
        $builder->select('pro.res_loc_id');
        $builder->where('pro.proj_id', $proid);
        $builder->where('pro.week_day', $week);
        $builder->where('pro.skill', $skill_val);
        $builder->where('pro.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function get_projects_resource_avail($week, $skill_val)
    {
        $builder = $this->db->table('et_resource_available  as pro');
        $builder->select('pro.*');
        $builder->where('pro.week_day', $week);
        $builder->where('pro.skill', $skill_val);
        $builder->where('pro.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }




    public function get_projects_resource_planning($week, $skill_val)
    {
        $builder = $this->db->table('et_resource_allocation as rs');
        $builder->select('rs.*, pro.projectname, ANY_VALUE(u.name) as name, pro.ucn as ucn_id, c.client_name as client_name');
        $builder->join('projects as pro', 'pro.projectid = rs.proj_id', 'left');
        $builder->join('projects_assignment as pa', 'pa.db_id = rs.proj_id AND pa.status=1', 'left');
        $builder->join('client as c', 'c.id_c = pro.client', "left");
        $builder->join('users as u', 'u.id_user = pa.user_id', 'left');
        $builder->where('rs.skill', $skill_val);
        $builder->where('rs.week_day', $week);
        $builder->where('rs.status', 1);
        $builder->groupBy('rs.res_loc_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function get_projects_resource_allocation($user, $week_day, $skill)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('projects as pro');
        $builder->select('pro.*, c.client_name as client_name, c.logo as logo, ANY_VALUE(ra.res_loc_id) as res_loc_id, ANY_VALUE(ra.skill) as skill, ANY_VALUE(ra.week_day) as week_day ,ANY_VALUE(ra.proj_id) as proj_id,ANY_VALUE(ra.mon) as mon,ANY_VALUE(ra.tue) as tue,ANY_VALUE(ra.wed) as wed,ANY_VALUE(ra.thu) as thu,ANY_VALUE(ra.fri) as fri,ANY_VALUE(ra.status) as status');
        $builder->join('projects_assignment as pa', 'pa.db_id = pro.projectid', 'left');
        $builder->join('et_resource_allocation as ra', "ra.proj_id = pro.projectid AND ra.skill = {$skill} AND ra.week_day = '{$week_day}'", 'left');
        // $builder->where('ra.skill', $skill);
        // $builder->where('ra.week_day', $week_day);
        $builder->where('pro.status >', 0);
        $builder->where('pro.status !=', 10);
        $builder->where('pro.status !=', 4);
        $builder->join('client as c', 'c.id_c = pro.client', "left");
        $builder->orderBy('pro.projectid', 'DESC');
        $builder->where('pa.type_of_assignment', 1);
        $builder->where('pa.user_id', $user);
        $builder->groupBy('pro.projectid');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function get_projects($ucn_id, $user)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('projects as pro');
        $builder->select('pro.*');
        $builder->where('pro.status !=', 0);
        $builder->where('pro.ucn', $ucn_id);
        $builder->where('pro.createdby', $user);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function update_resource_allocation($newdata, $res_loc_id)
    {
        $builder = $this->db->table('et_resource_allocation  as pd');
        $builder->where('pd.res_loc_id', $res_loc_id);
        $builder->update($newdata);
        return true;
    }

    public function update_resource_awail($newdata, $res_avl_id)
    {
        $builder = $this->db->table('et_resource_available  as pd');
        $builder->where('pd.res_avl_id ', $res_avl_id);
        $builder->update($newdata);
        return true;
    }

    public function add_resource_allocation($newdata)
    {
        $builder = $this->db->table('et_resource_allocation');
        $builder->insert($newdata);
        return true;
    }

    public function add_resource_awail($newdata)
    {
        $builder = $this->db->table('et_resource_available');
        $builder->insert($newdata);
        return true;
    }
}
