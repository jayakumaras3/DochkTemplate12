<?php

namespace App\Models\Project;

use CodeIgniter\Model;

class Projects_model extends Model
{
    protected $table = 'projects';
    protected $primaryKey = 'projectid';
    protected $allowedFields = ['projectname', 'theme', 'reviewpopup', 'status', 'createdby', 'createdon', 'deletedby', 'deletedon'];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    public function addProjects($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('projects');
        $builder->insert($newdata);
        $insertid  = $db->insertID();
        if (isset($insertid)) {
            $newdata = [
                'users' => session()->get('username'),
                'accesslevel' => 16,
                'projectid' => $insertid,
                'createdon' => time(),
                'status' => '1',
                'createdby' => session()->get('username'),
                'last_updated_by' =>  session()->get('id_user'),
                'last_updated_on' => time()
            ];
            $builder = $this->db->table('projects_details');
            $builder->insert($newdata);
            // $data = $builder->get()->getResultArray();
            return  true;
        } else {
            return False;
        }
    }
    public function editProjects($newdata, $projectid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('projects');
        $builder->where('projectid', $projectid);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        if ($data) {
            $this->insertProjectHistory($newdata);
        }
        return  $data;
    }
    public function insertProjectHistory($newhisdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_history');
        $builder->insert($newhisdata);
        // $data = $builder->get()->getResultArray();
        return  true;
    }
    public function projects_view()
    {
        $userlevel = session()->get('userlevel');
        $arrayuserlevel  = explode(',', $userlevel);
        //print_r($arrayuserlevel);
        $db = \Config\Database::connect();
        $user = session()->get('username');
        $builder = $this->db->table('projects as p');
        $builder->select('p.*,p.projectid as projectsid,c.course_id,count(distinct c.course_id) as course_count,d.name as theme,pd.*,max(pd.accesslevel),p.createdby as add_by,p.createdon as add_dt,p.completion,cl.client_name,u.name as fullname');
        $builder->join('dropdown as d', 'd.id_d = p.project_type', 'left');
        $builder->join('users as u', 'u.username = p.createdby', 'left');
        $builder->join('projects_details as pd', 'pd.projectid = p.projectid ', 'left');
        $builder->join('courses as c', 'c.project_id = p.projectid and c.status!=0', 'left');
        $builder->join('client as cl', 'cl.id_c = p.client and cl.status=1', 'left');
        $builder->where('p.status', '1');
        if (!in_array('6', $arrayuserlevel)) {
            $builder->where('pd.users=', session()->get('username'));
            $builder->where('pd.status', '1');
        }
        $builder->groupby('p.projectid');
        $builder->orderby('pd.accesslevel');
        $data = $builder->get()->getResultArray();
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
    public function updateProjects($newdata, $projectid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('projects as p');
        $builder->where('p.projectid', $projectid);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    public function getProjectDetails($projectid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('projects as p');
        $builder->select('p.*');
        $builder->where('p.projectid', $projectid);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getCourseDetails($course_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('courses as c');
        $builder->select('c.*,p.projectname');
        $builder->join('projects as p', 'p.projectid= c.project_id and p.status=1', 'left');
        $builder->where('c.course_id', $course_id);
        $builder->where('c.status !=', '0');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function addprojectusers($userdata)
    {
        foreach ($userdata['userlistArray'] as $UserName) {
            $newdata = [
                'users' => $UserName,
                'accesslevel' => $userdata['accesslevel'],
                'projectid' => $userdata['projectid'],
                'createdon' => time(),
                'status' => '1',
                'createdby' => $userdata['users'],
            ];
            $db = \Config\Database::connect();
            $builder = $this->db->table('projects_details as pd');
            $builder->select('pd.*');
            $builder->where('pd.users', $UserName);
            $builder->where('pd.accesslevel', $userdata['accesslevel']);
            $builder->where('pd.projectid', $userdata['projectid']);
            $builder->where('pd.status =', '1');
            $data = $builder->get();
            $postdata = $data->getRowArray();
            if (!empty($postdata)) {
                $db = \Config\Database::connect();
                $builder = $this->db->table('projects_details');
                $builder->set('accesslevel', $newdata['accesslevel']);
                $builder->where('projectid', $userdata['projectid']);
                $builder->where('users', $UserName);
                $builder->where('status =', '1');
                $builder->update();
            } else {
                $db = \Config\Database::connect();
                $builder = $this->db->table('projects_details');
                $builder->insert($newdata);
            }
        }
        $data = $builder->get()->getResultArray();
        if (isset($data)) {
            $data['status'] = "OK";
        } else {
            $data['status'] = "Error: ";
        }
        return $data;
    }
    public function getuseraccess($projectid, $user)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('projects_details as pd');
        $builder->select('pd.*,d.name as accesslevel,p.projectname,u.designation,u.email,u.name as fullname');
        $builder->join('dropdown as d', 'd.id_d = pd.accesslevel', 'left');
        $builder->join('projects as p', 'p.projectid = pd.projectid and p.status =1', 'left');
        $builder->join('users as u', 'u.username = pd.users and u.valid =1', 'left');
        $builder->where('pd.status', '1');
        $builder->where('d.fk_id_dc', '4');
        $builder->where('pd.projectid', $projectid);
        $builder->orderby('pd.accesslevel');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function clientTQusers($projectid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('projects as p');
        $builder->select('u.username,u.name');
        $builder->join('dropdown_users as du', 'du.fk_id_d = p.client or  du.fk_id_d = 1 and du.status =1', 'left');
        $builder->join('users as u', 'u.id_user = du.fk_id_user and du.status =1', 'left');
        $builder->where('p.projectid', $projectid);
        $builder->where('u.valid', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getaccesslevelusers($user, $projectid = "")
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('projects_details as pd');
        $builder->select('pd.*,max(pd.accesslevel) as projectaccesslevel');
        $builder->where('pd.projectid', $projectid);
        $builder->where('pd.users', $user);
        $builder->where('pd.status', '1');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function updateProjectsdetails($newdata, $project_details_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('projects_details as pd');
        $builder->where('pd.project_details_id', $project_details_id);
        $builder->where('pd.status', '1');
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    public function getprojecthistory($projectid)
    {
        $db = \Config\Database::connect();
        $user = session()->get('username');
        $builder = $this->db->table('project_history as ph');
        $builder->select('ph.*,d.name as theme,ph.createdby as add_by,ph.createdon as add_dt,cl.client_name');
        $builder->join('dropdown as d', 'd.id_d = ph.theme', 'left');
        $builder->join('client as cl', 'cl.id_c = ph.client ', 'left');
        $builder->join('users as u', 'u.id_user = ph.createdby ', 'left');
        $builder->where('ph.status', '1');
        $builder->where('ph.projectid', $projectid);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    /* course details */
    public function addcoursedetails($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('courses');
        $builder->insert($newdata);
        $data = $builder->get()->getResultArray();
        // if($data){
        //    $insertID = $db->insertID();
        //    print_r($insertID);
        //    exit();
        // }
        return  $data;
    }
    public function editcoursedetails($newdata, $course_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('courses');
        $builder->where('course_id', $course_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray($newdata);
        if ($data) {
            $this->insertCourseHistory($newdata, $course_id);
        }
        return  $data;
    }
    public function insertCourseHistory($newdata, $course_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('course_history');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray($newdata);
        return true;
    }
    public function getcoursefilehistory($course_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('course_history as ch');
        $builder->select("ch.*,d.name as type,cs.name as colorstatusname");
        $builder->join('dropdown as d', 'd.id_d = ch.type ', 'left');
        $builder->join('color_statusname as cs', 'cs.id_cs = ch.status', 'left');
        $builder->where('ch.course_id', $course_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getCoursesDetails($projectid)
    {
        $db = \Config\Database::connect();
        $query = $db->query("SELECT c.*,cs.name as coursestatusname,d.name as typename FROM courses as c 
        left join color_statusname as cs on cs.id_cs = c.status
        left join dropdown as d on d.id_d = c.type
        WHERE c.status >0 AND c.project_id='$projectid' order by c.orderby");
        $data = $query->getResultArray();

        /*$builder = $this->db->table('courses as c');
        $builder->select('c.*,cs.name as coursestatusname,d.name as typename');
        $builder->join('color_statusname as cs','cs.id_cs = c.status','left');
        $builder->join('dropdown as d','d.id_d = c.type','left');
        $builder->where('c.status >','0');
        $builder->where('c.project_id',$projectid);
       // print_r($builder);
        $data = $builder->get()->getResultArray();*/
        // print_r($data);
        return  $data;
    }
    public function addlockcoursedetails($courseid, $courselock, $newdata)
    { // do lock and unlock project
        $builder = $this->db->table('courses as c');
        $builder->where('course_id', $courseid);
        $builder->set('courselock', $courselock);
        $builder->update();
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    function getcoursename($course_id)
    { // get course name
        $db = \Config\Database::connect();
        $builder = $this->db->table('courses as c');
        $builder->select('c.*,p.projectname');
        $builder->join('projects as p', 'p.projectid = c.project_id ', 'left');
        $builder->where('c.status >', '0');
        $builder->where('c.course_id', $course_id);
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    function getfeedback($user, $course_id)
    { // get feedback details based on courseid
        $db = \Config\Database::connect();
        $builder = $this->db->table('feedback as f');
        $builder->select('f.*,f.type as feedbacktype,f.status as feedbackstatus,f.createdby as feedbackcreatedby,f.createdon as feedbackcreatedon, p.*,cs.name as colorstatusname,c.project_id,GROUP_CONCAT(fr.feedback separator "^") as frfeedbackreplay, GROUP_CONCAT(fr.createdby separator "^") as frcreatedby, GROUP_CONCAT(fr.createdon separator "^")  as frcreatedon,fr.type as frtype');
        $builder->join('courses as c', 'c.course_id = f.course_id', 'left');
        $builder->join('pages as p', 'p.pageid =f.pageid', 'left');
        $builder->join('color_statusname as cs', 'cs.id_cs =f.status', 'left');
        $builder->join('feedback_replies as fr', 'fr.feedbackid = f.feedbackid and fr.status =1', 'left');
        $builder->where('f.status >', '0');
        $builder->where('c.status !=', '0');
        $builder->where('f.course_id', $course_id);
        //$builder->where('p.courseid',$course_id);
        $builder->groupby('f.feedbackid');
        $data = $builder->get()->getResultArray();
        // print_r($builder);
        // exit();
        return  $data;
    }
    function getonefeedback($feedbackid)
    { // get feedback data based on feedback id
        $db = \Config\Database::connect();
        $builder = $this->db->table('feedback as f');
        $builder->select('f.*,f.type as feedbacktype,f.status as feedbackstatus,f.createdby as feedbackcreatedby,f.createdon as feedbackcreatedon, p.*,cs.name as colorstatusname,c.project_id,GROUP_CONCAT(fr.feedback separator "^") as frfeedbackreplay, GROUP_CONCAT(fr.createdby separator "^") as frcreatedby, GROUP_CONCAT(fr.createdon separator "^")  as frcreatedon,fr.type as frtype');
        $builder->join('pages as p', 'p.pageid =f.pageid', 'left');
        $builder->join('color_statusname as cs', 'cs.id_cs =f.status', 'left');
        $builder->join('courses as c', 'c.course_id = f.course_id', 'left');
        $builder->join('feedback_replies as fr', 'fr.feedbackid = f.feedbackid and fr.status =1', 'left');
        $builder->where('f.status >', '0');
        $builder->where('c.status >', '1');
        $builder->where('f.feedbackid', $feedbackid);
        $data = $builder->get()->getResultArray();
        //print_r($builder);
        return  $data;
    }
    function addfeedbackreply($feedbackid, $newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('feedback_replies');
        $builder->insert($newdata);
        $data = $builder->get()->getResultArray();
        if (!empty($data)) {
            $builder = $this->db->table('feedback as f');
            $builder->where('f.feedbackid', $feedbackid);
            $builder->set('f.status', '2');
            $builder->update();
            $data = $builder->get()->getResultArray();
        }
        if (isset($data)) {
            $data['status'] = "OK";
        } else {
            $data['status'] = "Error ";
        }
        return $data;
    }
    public function removefeedback($newdata, $feedbackid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('feedback as f');
        $builder->where('f.feedbackid', $feedbackid);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        if (isset($data)) {
            $data['status'] = "OK";
        } else {
            $data['status'] = "Error ";
        }
        return $data;
    }
    public function deltecourse($newdata, $course_id)
    {
        $builder = $this->db->table('courses as c');
        $builder->where('c.course_id', $course_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    /*project management*/
    public function getreviewer($projectid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('projects_details as pd');
        $builder->select('pd.*,u.name');
        $builder->join('users as u', 'u.username =pd.users', 'left join');
        $builder->where('pd.status !=', '0');
        $builder->where('accesslevel=', '13');
        $builder->where('pd.projectid', $projectid);
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    public function getcoursestatus($course_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('courses as c');
        $builder->select('c.*');
        $builder->where('c.course_id', $course_id);
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    public function assignreviewerdata($newdata)
    {
        $db = \Config\Database::connect();

        foreach ($newdata['username'] as $username) {
            $assigntask = ['username' => $username, 'duedate' => $newdata['duedate'], 'description' => $newdata['description'], 'coursestatus' => $newdata['coursestatus'], 'courseid' => $newdata['courseid'], 'status' => '1', 'createdby' => session()->get('username'), 'createdon' => time()];
            $builder = $this->db->table('course_assign_review');
            $builder->insert($assigntask);
            // $data = $builder->get()->getResultArray();
            $data['status'] = "OK";
            // if (!empty($data)) {
            //     $data['status'] = "OK";
            // } else {
            //     $data['status'] = "Error";
            // }
        }
        return  $data;
    }
    public function getreviewersData($course_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('course_assign_review as cr');
        $builder->select('cr.*,cr.createdon as completedon,cs.name as coursestatusname,u.name as users');
        $builder->join('users as u', 'u.username = cr.username', 'left');
        $builder->join('color_statusname as cs', 'cs.id_cs = cr.coursestatus', 'left');
        $builder->where('cr.courseid', $course_id);
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    public function deletereviewData($newdata, $reviewid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('course_assign_review as cr');
        $builder->where('cr.reviewid', $reviewid);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        if (isset($data)) {
            $data['status'] = "OK";
        } else {
            $data['status'] = "Error ";
        }
        return $data;
    }
    // function gethistoryOfCourseData($course_id){
    //     $db = \Config\Database::connect();
    //     $builder = $this->db->table('course_history as ph');
    //     $builder->select('ph.*,d.name as contentname,cs.name as coursestatusname');
    //     $builder->join('color_statusname as cs','cs.id_cs = ph.content','left');
    //     $builder->join('dropdown as d','d.id_d= ph.content','left');
    //     $builder->where('ph.courseid',$course_id);
    //     $builder->orderBy('prohistoryid','DESC');
    //     $builder->limit(10);
    //     $data = $builder->get()->getResultArray();
    //     return  $data;
    // }
    function updatefiledata($historydata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('course_history');
        $builder->insert($historydata);
        $db = \Config\Database::connect();
        $builder = $this->db->table('courses');
        $builder->set('assetlink', $historydata['assetlink']);
        $builder->where('course_id', $historydata['course_id']);
        $builder->update();
        // $data = $builder->get()->getResultArray();
        $data['status'] = "OK";
        // if (isset($data)) {
        //     $data['status'] = "OK";
        // } else {
        //     $data['status'] = "Error";
        // }
        return $data;
    }
    function getassetdetails($course_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('course_history as ch');
        $builder->select('ch.*,d.name as typename, cs.name as colorstatusname');
        $builder->join('dropdown as d', 'd.id_d = ch.type ', 'left');
        $builder->join('color_statusname as cs', 'cs.id_cs = ch.status', 'left');
        $builder->where('course_id', $course_id);
        // $builder->where('ch.status',1);
        $builder->orderBy('courhistoryid', 'DESC');
        $builder->limit('1');
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    function removeassetfolder($assethistoryid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_history');
        $builder->set('status', 0);
        $builder->set('deletedby', session()->get('username'));
        $builder->set('deletedon', time());
        $builder->where('prohistoryid', $assethistoryid);
        $builder->update();
        $data = $builder->get()->getResultArray();
        if (isset($data)) {
            $data['status'] = "OK";
        } else {
            $data['status'] = "Error: ";
        }
        return $data;
    }
    function getprojrctfilehistory($courseid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_history as ph');
        $builder->select('ph.*,ph.status as phstatus, d.name as contentname,cs.name as statusname');
        $builder->join('color_statusname as cs', 'cs.id_cs = ph.stage', 'left');
        $builder->join('dropdown as d', 'd.id_d= ph.content', 'left');
        $builder->where('ph.status>', 0);
        $builder->where('ph.typeofvalue =', 1);
        $builder->where('ph.course_id', $courseid);
        $builder->orderBy('prohistoryid', 'DESC');
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    function coursetype($courseid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('courses as c');
        $builder->select('c.*');
        $builder->where('course_id', $courseid);
        $builder->where('status>', 0);
        $coursedata = $builder->get()->getResultArray();
        if (count($coursedata)) {
            $courseType =  $coursedata['0']['type'];
        } else {
            $courseType = 1;
        }
        return $courseType;
    }
    function getpagegraph($projectid)
    {
        $builder = $this->db->table('courses as c');
        $builder->select('c.*');
        $builder->where('c.project_id', $projectid);
        $builder->where('c.status!=', 0);
        $data['courseid'] = $builder->get()->getResultArray();
        $getgraphsubval[] = '';
        foreach ($data['courseid'] as $eachdata) {
            $oneval = array(1);
            $twoval = array(2, 3);
            $threeval = array(4);
            $fourval = array(5);
            $fiveval = array(6);
            $sixval = array(7);
            $sevenval = array(8);
            $eightval = array(9);

            $val1 = $this->getdbvaluepage($eachdata['course_id'], $oneval);
            $val2 = $this->getdbvaluepage($eachdata['course_id'], $twoval);
            $val3 = $this->getdbvaluepage($eachdata['course_id'], $threeval);
            $val4 = $this->getdbvaluepage($eachdata['course_id'], $fourval);
            $val5 = $this->getdbvaluepage($eachdata['course_id'], $fiveval);
            $val6 = $this->getdbvaluepage($eachdata['course_id'], $sixval);
            $val7 = $this->getdbvaluepage($eachdata['course_id'], $sevenval);
            $val8 = $this->getdbvaluepage($eachdata['course_id'], $eightval);
            $getgraphsubval[] = $val1 . ',' . $val2 . ',' . $val3 . ',' . $val4 . ',' . $val5 . ',' . $val6 . ',' . $val7 . ',' . $val8;
        }
        $getgraphsubval = implode("^", $getgraphsubval);
        $data['graph'] = explode('^', $getgraphsubval);
        return  $data;
    }


    function getdbvaluepage($courseid, $arrayval)
    {
        $totalval = sizeof($arrayval);
        $totalval1 = 0;

        for ($i = 0; $i < $totalval; $i++) {
            $status = $arrayval[$i];
            $builder = $this->db->table("pages as p");
            $builder->select('p.*');
            $builder->where('p.courseid', $courseid);
            $builder->where('p.status', $status);
            $data = $builder->get()->getResultArray();
            $totalval1 = $totalval1 + count($data);
        }
        //print_r($totalval1);
        return $totalval1;
    }
    function getExportComments($project_id)
    {
        $db = \Config\Database::connect();
        $q1 = "SELECT * FROM courses WHERE status > 0 AND project_id='$project_id' ORDER BY course_id ASC";
        $result1 = $db->query($q1);
        $result = $result1->getResultArray();
        return $result;
    }
    function getpageExportComments($course_id)
    {
        $db = \Config\Database::connect();
        $q1 = "SELECT f.*,cs.name as colorstatusname,p.pagename FROM feedback as f 
        LEFT JOIN color_statusname as cs on cs.id_cs = f.stage
        left join pages as p on p.pageid = f.pageid 
        WHERE f.status > 0 AND f.course_id='$course_id'
        ORDER BY f.pageid ASC";
        $result1 = $db->query($q1);
        $result = $result1->getResultArray();
        return $result;
    }
    function getFeedbackData($course_id)
    {
        $db = \Config\Database::connect();
        $q2 = "SELECT f.*,p.pagename,cs.name as colorstatusname FROM feedback as f
                LEFT JOIN color_statusname as cs on cs.id_cs = f.stage
             left join pages as p on p.pageid = f.pageid  WHERE f.status > 0 AND f.course_id='$course_id' ORDER BY f.pageid ASC";
        $result2 = $db->query($q2);
        $result = $result2->getResultArray();
        return $result;
    }
    function getpagename2($pageid)
    {
        $db = \Config\Database::connect();
        $q = "SELECT * FROM pages WHERE pageid='$pageid' LIMIT 1";
        $result = $db->query($q);
        $resultx = $result->getResultArray();
        $num_rows = count($resultx);
        $name = '';
        if ($num_rows > 0) {
            // $name = mysql_result($result, 0, "pagename");
            $subpage_id = $resultx[0]["subpage_id"];

            $qs = "SELECT * FROM pages WHERE pageid='$subpage_id' LIMIT 1";
            $results = $db->query($qs);
            $resultxs = $results->getResultArray();
            $num_rowss = count($resultxs);
            if ($num_rowss > 0) {
                $name = $resultxs[0]["pagename"];
            }
        }

        return $name;
    }
    function getpagename($pageid)
    {
        $db = \Config\Database::connect();
        $q = "SELECT * FROM pages WHERE pageid='$pageid' LIMIT 1";
        $result = $db->query($q);
        $resultx = $result->getResultArray();
        $num_rows = count($resultx);
        if ($num_rows > 0) {
            $name = $resultx[0]['pagename'];
        } else {
            $name = $pageid;
        }

        return $name;
    }
    function getallrepliesArray($feedbackid, $typereply)
    {
        $db = \Config\Database::connect();
        $q = "SELECT * FROM feedback_replies WHERE status!=0 AND feedbackid='$feedbackid' ORDER BY feedbackreplyid ASC";
        $resultx = $db->query($q);
        $result = $resultx->getResultArray();
        $num_rows = count($result);
        $allReply = '';
        $allReplyx = array();
        for ($i = 0; $i < $num_rows; $i++) {
            $feedback = $result[$i]["feedback"];

            $newcomment = strip_tags($feedback);
            $newval = htmlspecialchars_decode($newcomment);
            $replacespecial = str_replace('&#39;', '`', $newval);
            $replacespecialx = str_replace('&nbsp;', ' ', $replacespecial);
            $feedReplystatus = $result[$i]["status"];
            $feedReplyBy = $result[$i]["createdby"];
            $createdon = $result[$i]["createdon"];
            $dateval = date('m/d/Y', $createdon);

            if ($feedback != '') {
                $allReplyx[] = array(
                    'feedbackid' => $feedbackid,
                    'status' => $feedReplystatus,
                    'feedback' => $replacespecialx,
                    'feedReplyBy' => $feedReplyBy,
                    'feedcreatedon' => $dateval,
                );
            }
        }

        return $allReplyx;
    }

    function getfirstname($createdby)
    {
        $db = \Config\Database::connect();
        $q = "SELECT * FROM users WHERE username = '$createdby'";
        $resultx = $db->query($q);
        $result = $resultx->getResultArray();
        $num_rows = count($result);
        if ($num_rows > 0) {
            $name = $result[0]["name"];
        } else {
            $name = $createdby;
        }
        return $name;
    }
    function getallpagerepliesArray($feedbackid, $typereply)
    {
        $db = \Config\Database::connect();
        $q = "SELECT fr.*,cs.name as colorstatusname FROM feedback_replies as fr
              left join feedback_stage_details as fs on fs.fk_id = fr.feedbackreplyid and fk_id_type ='2'
              LEFT JOIN color_statusname as cs on cs.id_cs = fs.fb_stage
              WHERE fr.status!=0 AND fr.feedbackid='$feedbackid' ORDER BY fr.feedbackreplyid ASC";
        $resultx = $db->query($q);
        $result = $resultx->getResultArray();
        $num_rows = count($result);
        $allReply = '';
        $allReplyx = array();
        for ($i = 0; $i < $num_rows; $i++) {
            $feedback = $result[$i]["feedback"];
            $newcomment = strip_tags($feedback);
            $newval = htmlspecialchars_decode($newcomment);
            $replacespecial = str_replace('&#39;', '`', $newval);
            $replacespecialx = str_replace('&nbsp;', ' ', $replacespecial);
            $feedReplystatus = $result[$i]["status"];
            $feedReplyBy = $result[$i]["createdby"];
            $createdon = $result[$i]["createdon"];
            $replystage = $result[$i]["colorstatusname"];
            $dateval = date('m/d/Y', $createdon);

            if ($feedback != '') {
                $allReplyx[] = array(
                    'feedbackid' => $feedbackid,
                    'status' => $feedReplystatus,
                    'feedback' => $replacespecialx,
                    'feedReplyBy' => $feedReplyBy,
                    'feedcreatedon' => $dateval,
                    'replystage' => $replystage
                );
            }
        }

        return $allReplyx;
    }
    function changefeedbackname($newPageID, $feedbackid, $course_id)
    {
        $db = \Config\Database::connect();
        $q = "UPDATE feedback SET pageid = '$newPageID' WHERE feedbackid = '$feedbackid'";
        $resultx = $db->query($q);
        $result = $resultx->getResultArray();
        return $result;
    }
    function displayclosedproject()
    {
        $db = \Config\Database::connect();
        $user = session()->get('username');
        $builder = $this->db->table('projects as p');
        $builder->select('p.*,p.projectid as projectsid,c.course_id,count(distinct c.course_id) as course_count,d.name as theme,pd.*,max(pd.accesslevel),p.createdby as add_by,p.createdon as add_dt,p.deletedon as del_dt,p.status as stat,p.completion,cl.client_name,u.name as fullname');
        $builder->join('dropdown as d', 'd.id_d = p.project_type', 'left');
        $builder->join('users as u', 'u.username = p.createdby', 'left');
        $builder->join('projects_details as pd', 'pd.projectid = p.projectid ', 'left');
        $builder->join('courses as c', 'c.project_id = p.projectid and c.status!=0', 'left');
        $builder->join('client as cl', 'cl.id_c = p.client and cl.status=1', 'left');
        $builder->where('p.status !=', '1');
        $builder->groupby('p.projectid');
        //$builder->orderBy('','');
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

    function displayoldtask()
    {
        $user = session()->get("username");
        $q = $this->db->query("SELECT ca.*,p.projectname,c.course_name,c.type as coursetype FROM course_assign_review  as ca
                        left join courses as c on c.course_id = ca.courseid
                        left join projects as p on p.projectid = c.project_id
                        WHERE ca.status = 2 AND ca.username='$user' ORDER BY ca.duedate ASC");
        $result = $q->getResultArray();
        $num_rows = count($result);
        return  $result;
    }
    function displaypageoldtask()
    {
        $user = session()->get("username");
        $q = $this->db->query("SELECT pa.*,pg.pagename,cs.name as pagestatusname FROM page_assign_review  as pa
                            left join pages as pg on pg.pageid = pa.pageid
                            left join color_statusname as cs on cs.id_cs = pa.pagestatus
                            WHERE pa.status = 2 AND pa.username='$user' ORDER BY pa.duedate ASC");
        $result = $q->getResultArray();
        $num_rows = count($result);
        return  $result;
    }
    function importnewpage($courseid, $sheetData)
    {
        for ($i = 0; $i < count($sheetData); $i++) {
            $db = \Config\Database::connect();
            if (isset($sheetData[$i][0])) {
                if (trim($sheetData[$i][0]) == 'PageID') {
                    continue;
                }
            }
            $pageid = "";
            if (isset($sheetData[$i][0])) {
                $pageid = trim($sheetData[$i][0]);
            } elseif (isset($sheetData[$i][1])) {
                $pageid = trim($sheetData[$i][1]);
            }
            $feedback = "";
            if (isset($sheetData[$i][2])) {
                $feedback = trim($sheetData[$i][2]);
            }
            $users = "";
            if (isset($sheetData[$i][3])) {
                $users = trim($sheetData[$i][3]);
            }
            $stage = 1;
            $privatex = 1;
            $currentvidtime = time();
            $username = session()->get('username');
            $time = time();
            $coursedata = $this->getcoursename($courseid);
            $stage = isset($coursedata[0]['status']) ? $coursedata[0]['status'] : '';
            $q = " INSERT INTO feedback (`feedbackid`, `course_id`, `pageid`, `feedback`, `type`, `stage`, `status`, `private`, `comment_type`, `comment_category`, `serverity`, `createdby`, `createdon`, `videotime`, `deletedby`, `deletedon`) 
                     VALUES (NULL,'$courseid', '$pageid', '$feedback', '1', '$stage', '1',  '$privatex', '', '', '', '$username', '$time', '$currentvidtime', '','');";
            $result = $db->query($q);
            if ($result) {

                $insert_id = $db->InsertID();
                //echo $insert_id;
                $qx = " INSERT INTO feedback_stage_details VALUES(null,$insert_id,'1','1',$time)"; // fk_id_type = feedback =1, reply =2
                $resultx = $db->query($qx);
                //return $resultx;
            }
        }
    }
}
