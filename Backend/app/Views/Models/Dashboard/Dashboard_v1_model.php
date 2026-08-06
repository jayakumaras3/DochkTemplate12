<?php

namespace App\Models\Dashboard;

use CodeIgniter\Model;

class dashboard_v1_model extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = db_connect(); // Loading database
        // OR $this->db = \Config\Database::connect();
    }
    protected $table = 'course_assign_review';
    protected $primaryKey = 'id_user';
    protected $allowedFields = ['reviewid', 'username', 'duedate', 'courseid', 'coursestatus', 'createdby', 'timestamp', 'createdon'];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    public function getprojectmyassignment($projectid)
    {
        $builder = $this->db->table('projects_details as pd');
        $builder->select('pd.*,d.name as accesslevel,p.projectid,p.projectname,p.completion as comp,p.description,min(pp.start_date) as pstart_dt,max(pp.end_date) as pend_dt,u.designation,u.email,u.name as fullname');
        $builder->join('dropdown as d', 'd.id_d = pd.accesslevel', 'left');
        $builder->join('projects as p', 'p.projectid = pd.projectid and p.status =1', 'left');
        //$builder->join('color_statusname as cs','cs.id_cs = s.status','left');
        $builder->join('courses as c', 'c.project_id = p.projectid', 'left');
        $builder->join('deal_timeline as pp', 'pp.fk_course_id = c.course_id and pp.status=1', 'left');
        $builder->join('users as u', 'u.username = pd.users and u.valid =1', 'left');
        $builder->where('pd.status', '1');
        $builder->where('p.status', '1');
        $builder->where('d.fk_id_dc', '4');
        $builder->where('pd.users=', session()->get('username'));
        $builder->where('p.projectid =', $projectid);
        $builder->orderby('pd.accesslevel');
        $data = $builder->get()->getResultArray();
        // print_r($builder);
        // exit();
        if (count($data) > 0) {
            foreach ($data as $eachdata) {
                $projectid = $eachdata['projectid'];
                $builder = $this->db->table('courses as c');
                $builder->select('round(sum(c.completion)/count(c.course_id)) as projectcomp');
                $builder->where('c.project_id', $projectid);
                $totalcompletion = $builder->get()->getResultArray();
                $users = session()->get('username');
                $graph[] = $this->getProjectid($users, $projectid);
            }
            return $data =
                [
                    'data' => $data,
                    'graph' => $graph,
                    'totalcompletion' => $totalcompletion
                ];
        }
    }
    public function getprojectcoursemyassignment($projectid)
    {
        $builder = $this->db->table('projects_details as pd');
        $builder->select('pd.*,d.name as accesslevel,p.projectid,p.projectname,min(pp.start_date) as pstart_dt,max(pp.end_date) as pend_dt,u.designation,u.email,u.name as fullname,c.*,c.completion as comp,cs.name as stage,c.type as coursetype,d1.name as projectTheme,cr.*,cr.username as courseusername ,cr.status as reviewstatus,pg.pageid');
        $builder->join('dropdown as d', 'd.id_d = pd.accesslevel', 'left');
        $builder->join('projects as p', 'p.projectid = pd.projectid and p.status =1', 'left');
        $builder->join('dropdown as d1', 'd1.id_d =p.theme and d1.fk_id_dc = 3 ', 'left');
        $builder->join('courses as c', 'c.project_id = p.projectid', 'left');
        $builder->join('pages as pg', 'pg.courseid = c.course_id', 'left');
        $builder->join('deal_timeline as pp', 'pp.fk_course_id = c.course_id and pp.status=1', 'left');
        $builder->join('color_statusname as cs', 'cs.id_cs = c.status', 'left');
        $builder->join('users as u', 'u.username = pd.users and u.valid =1', 'left');
        $builder->join('course_assign_review as cr', ' cr.courseid = c.course_id and cr.username = u.username', 'left');
        $builder->where('pd.status', '1');
        $builder->where('p.status', '1');
        $builder->where('c.status !=', '0');
        $builder->where('d.fk_id_dc', '4');
        $builder->where('pd.users=', session()->get('username'));
        $builder->where('c.project_id', $projectid);
        $builder->groupBy('c.course_id');
        $builder->orderby('pd.accesslevel');
        $data = $builder->get()->getResultArray();
        // print_r($data);
        // exit();
        if (count($data) > 0) {
            foreach ($data as $eachdata) {
                $projectid = $eachdata['projectid'];
                $users = session()->get('username');
                $graph[] = $this->getProjectid($users, $projectid);
            }
            return $data =
                [
                    'data' => $data,
                    'graph' => $graph
                ];
        }
    }
    function getmyassignment()
    {
        $timestamp = time();
        $builder = $this->db->table("course_assign_review as cr");
        $builder->select('cr.*,p.projectname,p.projectid,p.createdon as start_date,c.course_name,c.course_id,cs.name as stage,c.type as coursetype,d.name as projectTheme,e.*,u.username,d1.name as level,p.start_date as pstart_dt,p.end_date as pend_dt,pg.pageid');
        $builder->join('courses as c', 'c.course_id = cr.courseid ', 'left');
        $builder->join('projects as p', 'p.projectid = c.project_id ', 'left');
        $builder->join('pages as pg', 'pg.courseid = c.course_id', 'left');
        $builder->join('color_statusname as cs', 'cs.id_cs = c.status', 'left');
        $builder->join('dropdown as d', 'd.id_d =p.theme and d.fk_id_dc = 3 ', 'left');
        $builder->join('(select event_description,fk_course_id,createdon as max_date,responsible,level from event ORDER BY e_id DESC LIMIT 1) as e', 'e.fk_course_id =c.course_id', 'left');
        $builder->join('users as u', 'u.id_user =e.responsible', 'left');
        $builder->join('dropdown as d1 ', 'd1.id_d = e.level and d1.fk_id_dc =10', 'left');
        $builder->where('p.status =', '1');
        $builder->where('cr.status =', '1');
        $builder->where('cr.username=', session()->get('username'));
        $builder->groupBy('p.projectid');
        $builder->orderBy('cr.duedate', 'ASC');
        $data = $builder->get()->getResultArray();
        //echo $data;
        if (count($data) > 0) {
            foreach ($data as $eachdata) {
                $projectid = $eachdata['projectid'];
                $builder = $this->db->table('courses as c');
                $builder->select('round(sum(c.completion)/count(c.course_id)) as projectcomp');
                $builder->where('c.project_id', $projectid);
                $totalcompletion = $builder->get()->getResultArray();
                $users = session()->get('username');
                $graph[] = $this->getProjectid($users, $projectid);
            }
            return $data =
                [
                    'data' => $data,
                    'graph' => $graph,
                    'totalcompletion' => $totalcompletion
                ];
        }
        // return $data;
    }
    function getmyassignmentcourse()
    {
        $timestamp = time();
        $builder = $this->db->table("course_assign_review as cr");
        $builder->select('cr.*,p.projectname,p.projectid,p.createdon as start_date,c.course_name,c.course_id,cs.name as stage,c.type as coursetype,d.name as projectTheme,e.*,u.username,d1.name as level,c.start_date as pstart_dt,c.end_date as pend_dt');
        $builder->join('courses as c', 'c.course_id = cr.courseid ', 'left');
        $builder->join('projects as p', 'p.projectid = c.project_id ', 'left');
        $builder->join('color_statusname as cs', 'cs.id_cs = c.status', 'left');
        $builder->join('dropdown as d', 'd.id_d =p.theme and d.fk_id_dc = 3 ', 'left');
        $builder->join('(select event_description,fk_course_id,createdon as max_date,responsible,level from event ORDER BY e_id DESC LIMIT 1) as e', 'e.fk_course_id =c.course_id', 'left');
        $builder->join('users as u', 'u.id_user =e.responsible', 'left');
        $builder->join('dropdown as d1 ', 'd1.id_d = e.level and d1.fk_id_dc =10', 'left');
        $builder->where('p.status =', '1');
        $builder->where('cr.status =', '1');
        $builder->where('cr.username=', session()->get('username'));
        //$builder->groupBy('p.projectid');
        $builder->orderBy('cr.duedate', 'ASC');
        $data = $builder->get()->getResultArray();
        //echo $data;
        if (count($data) > 0) {
            foreach ($data as $eachdata) {
                $projectid = $eachdata['projectid'];
                $users = session()->get('username');
                $graph[] = $this->getProjectid($users, $projectid);
            }
            return $data =
                [
                    'data' => $data,
                    'graph' => $graph
                ];
        }
        // return $data;
    }
    function getmyassignmentpage()
    {
        $builder = $this->db->table("page_assign_review as pr");
        $builder->select('pr.*,p.projectname,,p.projectid,c.course_name,c.course_id,cs.name as stage,c.type as coursetype,pg.pagename,pg.sequence,e.*,u.username,d1.name as level');
        $builder->join('pages as pg', 'pg.pageid = pr.pageid', 'left');
        $builder->join('courses as c', 'c.course_id = pg.courseid ', 'left');
        $builder->join('projects as p', 'p.projectid = c.project_id ', 'left');
        $builder->join('color_statusname as cs', 'cs.id_cs = pr.pagestatus', 'left');
        $builder->join('(select event_description,fk_course_id,createdon as max_date,responsible,level from event ORDER BY e_id DESC LIMIT 1) as e', 'e.fk_course_id =c.course_id', 'left');
        $builder->join('users as u', 'u.id_user =e.responsible', 'left');
        $builder->join('dropdown as d1 ', 'd1.id_d = e.level and d1.fk_id_dc =10', 'left');
        $builder->where('pr.status =', '1');
        $builder->where('pr.username =', session()->get('username'));
        $builder->orderBy('pr.duedate', 'ASC');
        $data = $builder->get()->getResultArray();

        return $data;
    }
    function insertdochekfeedback($newdata)
    {
        $builder = $this->db->table("dochek_feedback");
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        $data['status'] = "OK";
        // if (!empty($data)) {
        //     $data['status'] = "OK";
        // } else {
        //     $data['status'] = "Error";
        // }
        return $data;
    }
    function getProjectid($users, $projectid = '')
    {
        $builder = $this->db->table("projects_details as pd");
        $builder->select('DISTINCT(pd.projectid),p.*');
        $builder->join('projects as p', 'p.projectid = pd.projectid', 'left');
        $builder->where('pd.status', '1');
        $builder->where('p.status', '1');
        $builder->where('pd.users', $users);
        $data['projectid'] = $builder->get()->getResultArray();
        //print_r($data['projectid']);
        // foreach($data['projectid'] as $eachdata){
        $oneval = array(1);
        $twoval = array(2, 3);
        $threeval = array(4);
        $fourval = array(5);
        $fiveval = array(6);
        $sixval = array(7);
        $sevenval = array(8);
        $eightval = array(9);

        $val1 = $this->getdbvalue($projectid, $oneval);
        $val2 = $this->getdbvalue($projectid, $twoval);
        $val3 = $this->getdbvalue($projectid, $threeval);
        $val4 = $this->getdbvalue($projectid, $fourval);
        $val5 = $this->getdbvalue($projectid, $fiveval);
        $val6 = $this->getdbvalue($projectid, $sixval);
        $val7 = $this->getdbvalue($projectid, $sevenval);
        $val8 = $this->getdbvalue($projectid, $eightval);
        $getgraphsubval[] = $val1 . ',' . $val2 . ',' . $val3 . ',' . $val4 . ',' . $val5 . ',' . $val6 . ',' . $val7 . ',' . $val8;

        // }
        $getgraphsubval = isset($getgraphsubval) ? implode("^", $getgraphsubval) : '';
        $graph = explode('^', $getgraphsubval);
        //print_r($data['graph'][1]);
        return $graph;
    }
    function getdbvalue($projectid, $arrayval)
    {
        $totalval = sizeof($arrayval);
        $totalval1 = 0;

        for ($i = 0; $i < $totalval; $i++) {
            $status = $arrayval[$i];
            $builder = $this->db->table("courses as c");
            $builder->select('c.*');
            $builder->where('c.project_id', $projectid);
            $builder->where('c.status', $status);
            $data = $builder->get()->getResultArray();
            $totalval1 = $totalval1 + count($data);
        }
        //print_r($totalval1);
        return $totalval1;
    }
    function dashboard_view()
    {
        $timestamp = time();
        $builder = $this->db->table("course_assign_review as cr");
        $builder->select('cr.*,p.projectname,p.projectid,p.createdon as start_date,c.course_name,c.course_id,cs.name as stage,c.type as coursetype,d.name as projectTheme,e.*,u.username,d1.name as level');
        $builder->join('courses as c', 'c.course_id = cr.courseid ', 'left');
        $builder->join('projects as p', 'p.projectid = c.project_id ', 'left');
        $builder->join('color_statusname as cs', 'cs.id_cs = c.status', 'left');
        $builder->join('dropdown as d', 'd.id_d =p.theme and d.fk_id_dc = 3 ', 'left');
        $builder->join('(select event_description,fk_course_id,createdon as max_date,responsible,level from event ORDER BY e_id DESC LIMIT 1) as e', 'e.fk_course_id =c.course_id', 'left');
        $builder->join('users as u', 'u.id_user =e.responsible', 'left');
        $builder->join('dropdown as d1 ', 'd1.id_d = e.level and d1.fk_id_dc =10', 'left');
        $builder->where('cr.status =', '1');
        //$builder->where('cr.username=',session()->get('username'));
        $builder->groupBy('p.projectid');
        $builder->orderBy('cr.duedate', 'ASC');
        $data = $builder->get()->getResultArray();
        // print_r($data);
        return $data;
    }
    function updatefiledata($newdata)
    {
        $builder = $this->db->table("project_document");
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        $data['status'] = "OK";
        // if (!empty($data)) {
        //     $data['status'] = "OK";
        // } else {
        //     $data['status'] = "Error";
        // }
        return $data;
    }
    function getprojectdocumentdata($projectid)
    {
        $builder = $this->db->table("project_document as doc");
        $builder->select('doc.*');
        $builder->where('doc.fk_project_id', $projectid);
        $builder->where('doc.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
}
