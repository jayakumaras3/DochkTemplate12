<?php

namespace App\Models\Task;

use DateTime;

use CodeIgniter\Model;

class Task_model extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = db_connect();
    }
    function getTaskbyid2($id2, $type)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('tasks as t');
        $builder->select('t.*, u1.name as taskcreatedby, u2.name as taskassigneto');
        $builder->join('users as u1', 'u1.id_user = t.created_by', 'left');
        $builder->join('users as u2', 'u2.id_user = t.assigned_to', 'left');
        $builder->where('t.type', $type);
        $builder->where('t.id2', $id2);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getTaskByUser($user, $status)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('tasks as t');
        $builder->select('t.*, u1.name as taskcreatedby, sc.course_name as course_name');
        $builder->join('users as u1', 'u1.id_user = t.created_by', 'left');
        $builder->join('scorm_courses as sc', 'sc.scourse_id = t.course_id', 'left');
        $builder->where('t.assigned_to', $user);
        $builder->where('t.status', $status);
        $builder->limit(50);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function get_name($user)
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.name as fname, u.report_to_you as report_to_you');
        $builder->where('u.id_user', $user);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_active_task($user)
    {
        $builder = $this->db->table('ucn_tl_effort as u');
        $builder->select('u.*, p.projectname as projectname');
        $builder->join('projects as p', 'p.projectid = u.project_id', 'left');
        $builder->where('u.user', $user);
        $builder->where('u.status', 1);
        $builder->orderBy('u.project_id', 'ASC');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_usereffort($user, $start_date, $end_date)
    {
        $builder = $this->db->table('ucn_emp_effort as u');
        $builder->select('u.*, p.projectname as projectname');
        $builder->join('projects as p', 'p.projectid = u.project_id', 'left');
        $builder->where('u.employee', $user);
        $builder->where('u.date_value >=', $start_date);
        $builder->where('u.date_value <=', $end_date);
        $builder->where('u.status', 1);
        $builder->orderBy('u.date_value', 'ASC');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getdatabydt($user, $date)
    {
        $builder = $this->db->table('ucn_emp_effort as u');
        $builder->select('u.effort, u.ucn_emp_id, u.remarks, p.projectname as projectname,u.date_value');
        $builder->join('projects as p', 'p.projectid = u.project_id', 'left');
        $builder->where('u.employee', $user);
        $builder->where('u.date_value', $date);
        $builder->where('u.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_active_master_task($user)
    {

        $tlSubQuery = $this->db->table('ucn_tl_effort')
            ->select('ucn_mst_id, SUM(effort) as effort')
            ->where('status !=', 0)
            ->groupBy('ucn_mst_id');

        $efSubQuery = $this->db->table('ucn_emp_effort')
            ->select('ucn_mst_id, SUM(effort) as effort')
            ->where('status !=', 0)
            ->groupBy('ucn_mst_id');

        $tlCompiled = $tlSubQuery->getCompiledSelect(false);
        $efCompiled = $efSubQuery->getCompiledSelect(false);

        $builder = $this->db->table('ucn_master_task as u');
        $builder->select('u.*, p.projectname as projectname, tl.effort as tleffort, ef.effort as empeff');
        $builder->join('projects as p', 'p.projectid = u.project_id', 'left');
        $builder->join("($tlCompiled) as tl", 'tl.ucn_mst_id = u.ucn_mst_id', 'left');
        $builder->join("($efCompiled) as ef", 'ef.ucn_mst_id = u.ucn_mst_id', 'left');
        $builder->where('u.manager', $user);
        $builder->where('u.status', 1);
        $builder->orderBy('u.project_id', 'ASC');
        $builder->groupBy('u.ucn_mst_id');

        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getTeam($user)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('users as u');
        $builder->select('u.*');
        $builder->where('u.manager', $user);
        $builder->where('u.valid', 1);
        $directReports = $builder->get()->getResultArray();
        $allUsers = [];
        foreach ($directReports as $user) {
            $subManagers = $this->getSubManagers($user['id_user']);
            $user['sub_managers'] = $subManagers;

            foreach ($subManagers as $subManager) {
                $subManager['sub_managers'] = $this->getSubManagers($subManager['id_user']);
            }

            $allUsers[] = $user;
        }
        return $allUsers;
    }
    function getAllTQusers()
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.*');
        $builder->where('u.valid', 1);
        $builder->where('u.client_id', 1);
        $builder->where('u.name !=', 'Demo User');
        $allUsers = $builder->get()->getResultArray();
        return $allUsers;
    }
    function getSubManagers($managerId)
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.*');
        $builder->where('u.manager', $managerId);  // Get users managed by $managerId
        $builder->where('u.valid', 1);
        return $builder->get()->getResultArray();
    }

    function get_employee_breakdown_tasks($ucn_tl_id)
    {
        $builder = $this->db->table('ucn_emp_effort as u');
        $builder->select('u.*');
        $builder->where('u.ucn_tl_id', $ucn_tl_id);  // Get users managed by $managerId
        $builder->where('u.status !=', 0);
        return $builder->get()->getResultArray();
    }


    function get_employee_assigned_tasks($ucn_mst_id)
    {
        $builder = $this->db->table('ucn_tl_effort as tl');
        $builder->select('tl.*, u.name, u.last_name, sum(ef.effort) as toteff');
        $builder->join('users as u', 'u.id_user = tl.user', 'left');
        $builder->join('ucn_emp_effort as ef', 'ef.ucn_tl_id = tl.ucn_tl_id AND ef.status=1', 'left');
        $builder->where('tl.ucn_mst_id', $ucn_mst_id);
        $builder->where('tl.status !=', 0);
        $builder->groupBy('tl.ucn_tl_id');
        return $builder->get()->getResultArray();
        // echo "<pre>";
        // echo $this->db->getLastQuery();
        // exit();
    }


    function addNewTask($newdata)
    {
        // $db = \Config\Database::connect();

        $builder = $this->db->table('tasks');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        // print_r($builder);
        // exit();
        return true;
    }

    function add_effort_by_employee($newData)
    {
        $builder = $this->db->table('ucn_emp_effort');
        $builder->insert($newData);
        return true;
    }
    function add_effort_employee($newData)
    {
        $builder = $this->db->table('ucn_tl_effort');
        $builder->insert($newData);
        return true;
    }

    function delete_effort_by_emp($newData, $ucn_emp_id)
    {
        $builder = $this->db->table('ucn_emp_effort as tl');
        $builder->where('tl.ucn_emp_id', $ucn_emp_id);
        $builder->update($newData);
        return true;
    }
    function close_tl_task($newData, $ucn_tl_id)
    {
        $builder = $this->db->table('ucn_tl_effort as tl');
        $builder->where('tl.ucn_tl_id', $ucn_tl_id);
        $builder->update($newData);
        return true;
    }
    function deleteTask($newdata, $task_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('tasks as t');
        $builder->where('t.task_id', $task_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function addMasterTask($newdata)
    {
        $builder = $this->db->table('master_task');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        // print_r($builder);
        // exit();
        return true;
    }
    function getAssignedMasterTask($user)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('master_task as mt');
        $builder->select('mt.*, sc.course_name as course_name, p.projectname as projectname,GROUP_CONCAT(DISTINCT(t.status) ORDER BY t.status ASC SEPARATOR ", ") as task_status');
        $builder->join('scorm_courses as sc', 'sc.scourse_id = mt.course_id', 'left');
        $builder->join('projects as p', 'p.projectid = mt.project_id', 'left');
        $builder->join('tasks as t', 't.master_task_id = mt.mt_id and t.status !=0', 'left');
        $builder->where('mt.assigned_to', $user);
        $builder->where('mt.status', 1);
        $builder->groupBy('mt.mt_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getSingleMasterData($master_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('master_task as mt');
        $builder->select('mt.*, sc.course_name as course_name, p.projectname as projectname,c.client_name');
        $builder->join('scorm_courses as sc', 'sc.scourse_id = mt.course_id', 'left');
        $builder->join('projects as p', 'p.projectid = mt.project_id', 'left');
        $builder->join('client as c', 'c.id_c = p.client', 'left');
        $builder->where('mt.mt_id', $master_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function gettaskByMasterId($master_id)
    {
        $builder = $this->db->table('tasks as t');
        $builder->select('t.*, u.name as name');
        $builder->join('users as u', 'u.id_user = t.assigned_to', 'left');
        $builder->where('t.master_task_id', $master_id);
        $builder->where('t.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getNextRecord($projectid, $dt_id)
    {
        $builder = $this->db->table('deal_timeline as q');
        $builder->select('q.*');
        $builder->where('q.fk_course_id', $projectid);
        $builder->where('q.status !=', 0);
        $builder->where('q.dt_id >', $dt_id)
            ->orderBy('q.dt_id', 'ASC');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function getPreviousRecord($projectid, $dt_id)
    {
        $builder = $this->db->table('deal_timeline as q');
        $builder->select('q.*');
        $builder->where('q.fk_course_id', $projectid);
        $builder->where('q.status !=', 0);
        $builder->where('q.dt_id <', $dt_id)
            ->orderBy('q.dt_id', 'DESC');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function get_taskmasterDetails($projectid, $dt_id)
    {
        $builder = $this->db->table('master_task as mt');
        $builder->select('mt.*,sc.course_name,u.name as assigned_to');
        $builder->join('scorm_courses as sc', 'sc.scourse_id = mt.course_id', 'left');
        $builder->join('users as u', 'u.id_user = mt.assigned_to', 'left');
        $builder->where('mt.project_id', $projectid);
        $builder->where('mt.dt_id', $dt_id);
        $builder->where('mt.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function get_course_task_master_list($course_id)
    {
        $builder = $this->db->table('master_task as mt');
        $builder->select('mt.*,u.name as assigned_to');
        $builder->join('users as u', 'u.id_user = mt.assigned_to', 'left');
        $builder->where('mt.course_id', $course_id);
        $builder->where('mt.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function deleteMasterTask($newdata, $mt_id)
    {
        $builder = $this->db->table('master_task as mt');
        $builder->where('mt_id', $mt_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function complete_master_task_data($newdata, $mt_id)
    {
        $builder = $this->db->table('master_task as mt');
        $builder->where('mt_id', $mt_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getAllTask($course_id)
    {
        $builder = $this->db->table('tasks as t');
        $builder->select('t.master_task_id');
        $builder->where('course_id', $course_id);
        $builder->where('status!=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getAllTaskfromProjectplan($dt_id)
    {
        $builder = $this->db->table('master_task as mt');
        $builder->select('t.master_task_id');
        $builder->join('tasks as t', 't.course_id = mt.course_id and t.status !=0', 'left');
        $builder->where('mt.dt_id', $dt_id);
        $builder->where('mt.status!=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getEffortMonthlyReport($month, $year, $user)
    {

        $builder = $this->db->table('users as u');
        $builder->select('CONCAT(u.name, " ", u.last_name) AS fullname, empeff.effort AS emp_total_effort, empeff.date_value');
        $builder->join('ucn_emp_effort as empeff', "empeff.employee = u.id_user AND empeff.status != 0 AND MONTH(empeff.date_value) = {$month} AND YEAR(empeff.date_value) = {$year}", 'left');
        $builder->join('projects as p', 'p.projectid = empeff.project_id', 'left');
        $builder->join('dropdown_users as du', 'du.fk_id_user = u.id_user and fk_id_dc=2', 'left');
        if ($user != '1' && $user != '831') {
            $builder->where('u.manager', $user);
        }
        $builder->where('du.status', 1);
        $builder->where('du.fk_id_d', 7);
        $builder->where('u.valid', 1);
        $builder->where('u.valid', 1);
        $builder->where('u.client_id', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getdomainleveldata($skill_val, $start_date, $end_date)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('skill_map as skills');
        $builder->select('u.name as fname, u.last_name as lname, skills.*,p.projectid,p.projectname, 
                      assigned_sum.assigned_total,d.name as domain,
                      utilized_sum.utilized_total,utilized_sum.stage,utilized_sum.date_value');

        // Subquery for assigned sum
        $builder->join(
            '(SELECT skill_id, user, SUM(effort) as assigned_total,project_id,stage 
          FROM ucn_tl_effort 
          WHERE status = 1 
          GROUP BY skill_id,user,project_id,stage) as assigned_sum',
            'assigned_sum.skill_id = skills.skill_id AND assigned_sum.user = skills.user_id',
            'left'
        );

        // Subquery for utilized sum
        $builder->join(
            '(SELECT skill_id, employee, project_id, SUM(effort) as utilized_total, stage,Max(date_value) as  date_value
      FROM ucn_emp_effort 
      WHERE status = 1 
      GROUP BY skill_id, employee, project_id, stage) as utilized_sum',
            'utilized_sum.skill_id = skills.skill_id 
     AND utilized_sum.employee = skills.user_id
      AND utilized_sum.project_id = assigned_sum.project_id
     AND utilized_sum.stage = assigned_sum.stage',
            'left'
        );
        $builder->join('dropdown as d', 'd.value  = skills.skill_id and d.fk_id_dc=8 and d.status=1', 'left');
        $builder->join('projects as p', 'p.projectid  = assigned_sum.project_id', 'left');
        $builder->join('users as u', 'u.id_user = skills.user_id AND u.valid=1', 'left');
        if ($skill_val != 0) {
            $builder->where('skills.skill_id', $skill_val);
        }
        // $builder->where('skills.skill_id', $skill_val);
        $builder->where('utilized_sum.date_value >= ', $start_date);
        $builder->where('utilized_sum.date_value <= ', $end_date);
        $builder->where('skills.status', 1);

        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
}
