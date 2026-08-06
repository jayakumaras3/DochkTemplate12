<?php

namespace App\Models\Project_Manage;

use CodeIgniter\Model;

class PM_ucn_model extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = db_connect(); // Loading database
    }
    public function get_ucn_list($user)
    {
        $year = date("Y");
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_ucn as ucn');
        $builder->select('ucn.*, c.client_name as client_name, u.name as manager');
        $builder->join('users as u', 'u.id_user = ucn.account_manager', 'left');
        $builder->join('projects_assignment as pa', 'pa.db_id = ucn.ucn_id', 'left');
        // $builder->groupStart();
        //$builder->where("YEAR(FROM_UNIXTIME(ucn.created_on)) =", $year);
        // $builder->orWhere("YEAR(FROM_UNIXTIME(ucn.last_updated_on)) =", $year);
        // $builder->groupEnd();

        $builder->join('client as c', 'c.id_c = ucn.client', "left");
        $builder->where('pa.type_of_assignment', 5);
        $builder->where('pa.user_id', session()->get('id_user'));
        $builder->where('pa.status !=', 0);
        $builder->where('ucn.status !=', 0);
        $builder->groupBy('ucn.ucn_id');
        $builder->orderBy('ucn.ucn_id', 'DESC');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function get_ucn_details($id_ucn)
    {
        $builder = $this->db->table('project_ucn as ucn');
        $builder->select('ucn.*');
        $builder->where('ucn.ucn_id', $id_ucn);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function get_wip_list()
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_ucn as ucn');
        $builder->select('ucn.*, c.client_name as client_name,p.projectname,u.name as project_manager,per.month,per.percent');
        $builder->join('projects as p', 'p.ucn = ucn.ucn_id', 'left');
        $builder->join('projects_assignment as pa', 'pa.db_id = ucn.ucn_id', 'left');
        $builder->join('project_ucn_percent as per', 'per.ucn_id =ucn.ucn_id', 'left');
        $builder->join('client as c', 'c.id_c = ucn.client', "left");
        $builder->join('users as u', 'u.id_user = pa.user_id', 'left');
        $builder->where('pa.type_of_assignment', 5);
        $builder->where('pa.type_of_role', 1);
        $builder->groupBy('ucn.ucn_id');
        $builder->orderBy('ucn.ucn_id', 'DESC');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function get_wip_yearlist($year)
    {
        $poSubQuery = $this->db->table('project_ucn_link as pl')
            ->select('pl.ucn, SUM(po.po_value) as total_po_value')
            ->join('purchase_order as po', 'po.po_id = pl.table_id and po.status !=0', 'left')
            ->where('pl.type_of_link', 2)
            ->where('pl.status', 1)
            ->groupBy('pl.ucn');
        // echo $this->db->getLastQuery();
        //          exit();

        $prevyear = $year - 1;
        $builder = $this->db->table('project_ucn as ucn');
        $builder->select('ucn.*, c.client_name, c.id_c as client, ANY_VALUE(p.projectname) as projectname');
        $builder->select("GROUP_CONCAT(DISTINCT(u.name) SEPARATOR ', ') as project_manager");
        $builder->select('po_sum.total_po_value as po_value');
        $builder->select('MAX(CASE WHEN per.month = 12 AND per.year = ' . $prevyear . ' THEN per.percent END) AS month_0_percent');
        $builder->select('MAX(CASE WHEN per.month = 1 AND per.year = ' . $year . ' THEN per.percent END) AS month_1_percent');
        $builder->select('MAX(CASE WHEN per.month = 2 AND per.year = ' . $year . ' THEN per.percent END) AS month_2_percent');
        $builder->select('MAX(CASE WHEN per.month = 3 AND per.year = ' . $year . ' THEN per.percent END) AS month_3_percent');
        $builder->select('MAX(CASE WHEN per.month = 4 AND per.year = ' . $year . ' THEN per.percent END) AS month_4_percent');
        $builder->select('MAX(CASE WHEN per.month = 5 AND per.year = ' . $year . ' THEN per.percent END) AS month_5_percent');
        $builder->select('MAX(CASE WHEN per.month = 6 AND per.year = ' . $year . ' THEN per.percent END) AS month_6_percent');
        $builder->select('MAX(CASE WHEN per.month = 7 AND per.year = ' . $year . ' THEN per.percent END) AS month_7_percent');
        $builder->select('MAX(CASE WHEN per.month = 8 AND per.year = ' . $year . ' THEN per.percent END) AS month_8_percent');
        $builder->select('MAX(CASE WHEN per.month = 9 AND per.year = ' . $year . ' THEN per.percent END) AS month_9_percent');
        $builder->select('MAX(CASE WHEN per.month = 10 AND per.year = ' . $year . ' THEN per.percent END) AS month_10_percent');
        $builder->select('MAX(CASE WHEN per.month = 11 AND per.year = ' . $year . ' THEN per.percent END) AS month_11_percent');
        $builder->select('MAX(CASE WHEN per.month = 12 AND per.year = ' . $year . ' THEN per.percent END) AS month_12_percent');
        $builder->join('projects as p', 'p.ucn = ucn.ucn_id', 'left');
        $builder->join('project_ucn_link as pul', 'pul.ucn = ucn.ucn_id', 'left');
        $builder->join('projects_assignment as pa', 'pa.db_id = ucn.ucn_id', 'left');
        $builder->join('project_ucn_percent as per', 'per.ucn_id = ucn.ucn_id', 'left');
        $builder->join("({$poSubQuery->getCompiledSelect()}) as po_sum", 'po_sum.ucn = ucn.ucn_id', 'left');
        $builder->join('client as c', 'c.id_c = ucn.client', 'left');
        $builder->join('users as u', 'u.id_user = pa.user_id', 'left');
        $builder->where('pa.type_of_assignment', 5);
        $builder->where('ucn.status !=', 0);
        $builder->where('pa.status', 1);
        $builder->groupStart()
            ->where('YEAR(FROM_UNIXTIME(ucn.created_on))', $year)
            ->orWhere('YEAR(FROM_UNIXTIME(ucn.last_updated_on))', $year)
            ->groupEnd();
        $builder->groupBy('ucn.ucn_id');
        $builder->orderBy('ucn.status', 'ASC');

        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        //          exit();
        return $data;
    }


    public function get_ucn_percent($ucn_id, $year)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_ucn_percent as ucn_per');
        $builder->select('ucn_per.*');
        $builder->where('ucn_per.status !=', 0);
        // $builder->where('ucn_per.year', $year);
        $builder->where('ucn_per.ucn_id', $ucn_id);
        $builder->orderBy('ucn_per.month', 'DESC');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function get_ucn_percent_exist($ucn_id, $year, $month)
    {
        $builder = $this->db->table('project_ucn_percent as ucn_per');
        $builder->select('ucn_per.*');
        $builder->where('ucn_per.status !=', 0);
        $builder->where('ucn_per.year', $year);
        $builder->where('ucn_per.month', $month);
        $builder->where('ucn_per.ucn_id', $ucn_id);
        $builder->orderBy('ucn_per.month', 'DESC');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function get_projects_by_ucn($ucn_id, $user)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('projects as pro');
        $builder->select('pro.*');
        $builder->where('pro.status !=', 0);
        $builder->where('pro.ucn', $ucn_id);
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }


    public function get_projects($ucn_id, $user)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('projects as pro');
        $builder->select('pro.*, scourse_id as course_id, course_name as courseName');
        $builder->join('scorm_courses as sc', 'sc.project_id = pro.projectid and sc.status=1', 'left');
        $builder->where('pro.status !=', 0);
        $builder->where('pro.ucn', $ucn_id);
        $builder->where('pro.createdby', $user);
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    public function getUCNdetails($ucn_id)
    {
        $builder = $this->db->table('project_ucn as ucn');
        $builder->select('ucn.*, u.name as acm, c.client_name as clientname');
        $builder->join('client as c', 'c.id_c = ucn.client', 'left');
        $builder->join('users as u', 'u.id_user = ucn.account_manager', 'left');
        $builder->where('ucn.status !=', 0);
        $builder->where('ucn.ucn_id', $ucn_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function po_details_for_ucn($ucn_id)
    {
        $builder = $this->db->table('project_ucn_link as pul');
        $builder->select('po.*');
        $builder->join('purchase_order as po', 'po.po_id = pul.table_id', 'left');
        $builder->where('pul.status !=', 0);
        $builder->where('pul.type_of_link', 2);
        $builder->where('pul.ucn', $ucn_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function get_ucn_effort($ucn_id)
    {
        $builder = $this->db->table('project_pricing_details as effort');
        $builder->select('effort.*');
        $builder->where('effort.status !=', 0);
        $builder->where('effort.ucn_id', $ucn_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function get_effort($ucn_id)
    {
        $builder = $this->db->table('project_pricing_details as effortt');
        $builder->select('SUM(effortt.effort) as total');
        $builder->where('effortt.status !=', 0);
        $builder->where('effortt.ucn_id', $ucn_id);
        $builder->where('effortt.type_resource <', 55);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function get_emp_data($ucn_mst_id)
    {
        $builder = $this->db->table('ucn_emp_effort as eff');
        $builder->select('eff.*, u.name, u.last_name');
        $builder->join('users as u', 'u.id_user = eff.employee', 'left');
        $builder->where('eff.status !=', 0);
        $builder->where('eff.ucn_mst_id', $ucn_mst_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function get_actual($ucn_id)
    {
        $builder = $this->db->table('ucn_emp_effort as effortt');
        $builder->select('SUM(effortt.effort) as total');
        $builder->where('effortt.status !=', 0);
        $builder->where('effortt.ucn_id', $ucn_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function get_employee_breakdown_tasks($ucn_tl_id)
    {
        $builder = $this->db->table('ucn_emp_effort as data');
        $builder->select('data.*');
        $builder->where('data.status !=', 0);
        $builder->where('data.ucn_tl_id', $ucn_tl_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function get_project_data($projectid, $ucn, $skillId)
    {
        $tlSubQuery = $this->db->table('ucn_tl_effort')
            ->select('ucn_mst_id, SUM(effort) as tleffort')
            ->where('status !=', 0)
            ->groupBy('ucn_mst_id');

        $efSubQuery = $this->db->table('ucn_emp_effort')
            ->select('ucn_mst_id, SUM(effort) as efffort')
            ->where('status !=', 0)
            ->groupBy('ucn_mst_id');

        $tlCompiled = $tlSubQuery->getCompiledSelect(false);
        $efCompiled = $efSubQuery->getCompiledSelect(false);

        $builder = $this->db->table('ucn_master_task as mst');
        $builder->select('mst.*, u.name, sum(te.tleffort) as teeff, sum(ee.efffort) as eeeff,mst.remarks');
        $builder->join('users as u', 'u.id_user = mst.manager', 'left');
        $builder->join("($tlCompiled) as te", 'te.ucn_mst_id = mst.ucn_mst_id', 'left');
        $builder->join("($efCompiled) as ee", 'ee.ucn_mst_id = mst.ucn_mst_id', 'left');
        $builder->where('mst.ucn_id', $ucn);
        $builder->where('mst.project_id', $projectid);
        $builder->where('mst.skill_id', $skillId);
        $builder->where('mst.status !=', '0');
        $builder->groupBy('mst.ucn_mst_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function get_cost($ucn_id)
    {
        $builder = $this->db->table('project_pricing_details as effort');
        $builder->select('SUM(effort.effort) as total');
        // $builder->join('ucn_emp_effort as ef', 'ef.ucn_id = effort.ucn_id AND ef.status=1', 'left');
        $builder->where('effort.status !=', 0);
        $builder->where('effort.ucn_id', $ucn_id);
        //$builder->where('effort.type', $type);
        $builder->where('effort.type_resource >', 54);
        //$builder->groupBy('effort.type');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function get_manager_allocated_effort($projectid)
    {
        // $builder = $this->db->table('ucn_master_task as master');
        // $builder->select('SUM(master.effort) as total, master.skill_id, sum(tl.effort) as tleffort, sum(ef.effort) as efffort');
        // $builder->join('ucn_tl_effort as tl', 'tl.ucn_mst_id = master.ucn_mst_id', 'left');
        // $builder->join('ucn_emp_effort as ef', 'ef.ucn_mst_id = master.ucn_mst_id', 'left');
        // $builder->where('master.status !=', 0);
        // $builder->where('master.project_id', $projectid);
        // $builder->groupBy('master.skill_id');
        // $data = $builder->get()->getResultArray();
        // return  $data;

        $tlSubQuery = $this->db->table('ucn_tl_effort')
            ->select('ucn_mst_id, SUM(effort) as tleffort')
            ->where('status !=', 0)
            ->groupBy('ucn_mst_id');

        $efSubQuery = $this->db->table('ucn_emp_effort')
            ->select('ucn_mst_id, SUM(effort) as efffort')
            ->where('status !=', 0)
            ->groupBy('ucn_mst_id');

        $tlCompiled = $tlSubQuery->getCompiledSelect(false);
        $efCompiled = $efSubQuery->getCompiledSelect(false);

        $builder = $this->db->table('ucn_master_task as master');
        $builder->select('
        master.skill_id, 
        SUM(master.effort) as total, 
        SUM(tl.tleffort) as tleffort, 
        SUM(ef.efffort) as efffort
    ');
        $builder->join("($tlCompiled) as tl", 'tl.ucn_mst_id = master.ucn_mst_id', 'left');
        $builder->join("($efCompiled) as ef", 'ef.ucn_mst_id = master.ucn_mst_id', 'left');
        $builder->where('master.status !=', 0);
        $builder->where('master.project_id', $projectid);
        $builder->groupBy('master.skill_id');

        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function add_projects($newdata, $ucn, $course_name)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('projects');
        $builder->insert($newdata);
        $insertID = $db->insertID();
        if ($insertID) {
            $newdata = [
                'type_of_assignment' => 1,
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

            $newdata2 = [
                'ucn' => $ucn,
                'type_of_link' => 3,
                'table_id' => $insertID,
                'created_on' => time(),
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
                'status' => '1'
            ];
            $builder = $this->db->table('project_ucn_link');
            $builder->insert($newdata2);

            $newdata3 = [
                'project_id' => $insertID,
                'type' => 1,
                'course_name' => $course_name,
                'duration' => 10,
                'createdby' => session()->get('id_user'),
                'createdon' => time(),
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
                'status' => '1'
            ];
            $builder = $this->db->table('scorm_courses');
            $builder->insert($newdata3);

            $data['course_id'] = $db->insertID();
            if (isset($data['course_id'])) {
                $scourse_id = $data['course_id'];
                $scorm_courses_assigned = [
                    'client_id' => session()->get('client'),
                    'course_id' => $scourse_id,
                    'editable' => 1,
                    'status' => 1,
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                ];
                $builder = $this->db->table('scorm_courses_assigned');
                $builder->insert($scorm_courses_assigned);
                $userlevel = session()->get('userlevel');
                $arrayuserlevel = array_map('intval', explode(',', $userlevel));
                if (in_array('4', $arrayuserlevel)) {
                    $builder = $this->db->table('projects as p');
                    $builder->select('p.client');
                    $builder->where('p.projectid', $insertID);
                    $clientasssigndata = $builder->get()->getResultArray();
                    if (!empty($clientasssigndata)) {
                        $client = $clientasssigndata[0]['client'];
                        $scorm_courses_assigned = [
                            'client_id' => $client,
                            'course_id' => $scourse_id,
                            'editable' => 1,
                            'status' => 1,
                            'createdby' => session()->get('id_user'),
                            'createdon' => time(),
                        ];
                        // print_r($clientasssigndata[0]['client']);
                        // exit();

                        $builder = $this->db->table('scorm_courses_assigned');
                        $builder->insert($scorm_courses_assigned);
                    }
                }


                $scorm_users_courses_assigned = [
                    'id_user' => session()->get('id_user'),
                    'course_id' => $scourse_id,
                    'status' => 1,
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                ];
                $builder = $this->db->table('scorm_users_courses_assigned');
                $builder->insert($scorm_users_courses_assigned);

                $timestamp = time();
                $hash = hash('sha256', $scourse_id . '' . $timestamp, false);
                $db = \Config\Database::connect();
                $builder = $this->db->table('scorm_courses as sc');
                $builder->set('sc.hash', $hash);
                $builder->where('sc.scourse_id', $scourse_id);
                $builder->update();
            }
        }
        return $insertID;
    }

    public function add_master_effort_to_ucn($newdata)
    {
        $user = session()->get('id_user');
        $db = \Config\Database::connect();
        $builder = $this->db->table('ucn_master_task');
        $builder->insert($newdata);
        $ucn_mst_id = $db->insertID();
        if ($user == $newdata['manager']) {
            $newData = [
                'ucn_mst_id' => $ucn_mst_id,
                'ucn_id' => $newdata['ucn_id'],
                'project_id' => $newdata['project_id'],
                'skill_id' => $newdata['skill_id'],
                'effort' => $newdata['effort'],
                'stage' => $newdata['stage'],
                'user' => $user,
                'status' => 1,
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $builder = $this->db->table('ucn_tl_effort');
            $builder->insert($newData);
        }
        return true;
    }
    public function add_new_ucn_data($ucn_data)
    {
        $builder = $this->db->table('project_ucn');
        $builder->insert($ucn_data);
        return true;
    }

    public function add_percentage($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_ucn_percent');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        $data = true;
        return $data;
    }

    function close_tl_task_by_pid($newData, $ucnprojectid)
    {
        $builder = $this->db->table('ucn_master_task');
        $builder->where('project_id', $ucnprojectid);
        $builder->update($newData);
        return true;
    }
    function close_pm_task_by_pid($newData, $ucnprojectid)
    {
        $builder = $this->db->table('ucn_tl_effort');
        $builder->where('project_id', $ucnprojectid);
        $builder->update($newData);
        return true;
    }

    function close_master($newData, $ucn_mst_id)
    {
        $builder = $this->db->table('ucn_master_task');
        $builder->where('ucn_mst_id', $ucn_mst_id);
        $builder->update($newData);
        return true;
    }

    function close_tl_effort($newData, $ucn_mst_id)
    {
        $builder = $this->db->table('ucn_tl_effort');
        $builder->where('ucn_mst_id', $ucn_mst_id);
        $builder->update($newData);
        return true;
    }

    function update_percentage($newdata, $ucn_percent_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_ucn_percent');
        $builder->where('ucn_percent_id', $ucn_percent_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function get_project_details($projectid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('projects as pro');
        $builder->select('pro.*');
        $builder->where('pro.projectid', $projectid);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function update_ucn_percentage($newdata, $ucn)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_ucn');
        $builder->where('ucn_id', $ucn);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function update_projects($newdata, $ucnprojectid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('projects');
        $builder->where('projectid', $ucnprojectid);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getclientIDforproject($project_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('projects as p');
        $builder->select('p.client');
        $builder->where('p.projectid ', $project_id);
        $builder->where('p.status  !=', 0);
        $builder->where('p.status  !=', 3);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getallusersclient($project_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('projects as p');
        $builder->select('concat(u.name," ",u.last_name) as fullname,u.id_user,p.client');
        $builder->join('users as u', 'u.client_id = p.client and u.valid =1', 'left');
        $builder->join('dropdown_users as du', 'du.fk_id_user = u.id_user and  du.fk_id_dc=2 and du.status =1', 'left');
        $builder->where('du.fk_id_d', 45);
        $builder->where('p.projectid ', $project_id);
        $builder->where('p.status != ', 0);
        $data = $builder->get()->getResultArray();
        //  echo $this->db->getLastQuery();
        //  exit();
        return $data;
    }
    public function assignreviewerdata($newdata)
    {
        $db = \Config\Database::connect();

        foreach ($newdata['id_user'] as $id_user) {
            $dnewData = [
                'id_user' => $id_user,
                'due_date' => $newdata['due_date'],
                'role' => '5',
                'stage' => $newdata['stage'],
                'course_status' => $newdata['course_status'],
                'course_id' => $newdata['course_id'],
                'status' => 1,
                'createdby' => session()->get('id_user'),
                'createdon' => time(),
            ];
            $builder = $this->db->table('scorm_users_courses_assigned');
            $builder->insert($dnewData);
            $data = $builder->get()->getResultArray();
            if (!empty($data)) {
                $data['status'] = "OK";
            } else {
                $data['status'] = "Error";
            }
        }
        return $data;
    }
    public function getAssignedUsercourse($scourse_id, $client)
    {
        $builder = $this->db->table('scorm_users_courses_assigned as r');
        $builder->select("r.*,concat(u.name,' ',u.last_name) as fullname");
        $builder->join('users as u', 'u.id_user = r.id_user', 'left');
        $builder->where('u.client_id', $client);
        $builder->where('r.course_id', $scourse_id);
        $builder->where('r.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function delete_assigneduser($newdata, $user_assign_id)
    {
        $builder = $this->db->table('scorm_users_courses_assigned');
        $builder->where('user_assign_id', $user_assign_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
}
