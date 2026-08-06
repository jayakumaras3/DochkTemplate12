<?php

namespace App\Models\SCORM;

use CodeIgniter\Model;

class Scorm_client_model extends Model
{
    protected $primaryKey = 'id_c';
    protected $allowedFields = ['name', 'url', 'start_date', 'end_date', 'courses_count', 'status', 'createdon', 'createdby', 'deletedon', 'deletedby'];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    public function saveScormClient($newdata)
    { // save category item data
        $db = \Config\Database::connect();
        if ($newdata['fk_id_dc'] == 15) {
            $clientdata['client_name'] = $newdata['name'];
            $clientdata['status'] = 1;
            $clientdata['type'] = 2;
            $clientdata['createdon'] = $newdata['createdon'];
            $clientdata['createdby'] = $newdata['createdby'];
            $clientdata['last_updated_by'] = $newdata['last_updated_by'];
            $clientdata['last_updated_on'] = $newdata['last_updated_on'];
            //$clientdata['client_name'] = $newdata['name'];
            $builder = $this->db->table('client');
            $builder->insert($clientdata);
            // $data = $builder->get()->getResultArray();
            $data = true;
        } else {
            $builder = $this->db->table('dropdown');
            $builder->insert($newdata);
            $data = true;
        }
        return $data;
    }
    public function clientC4Uuserlist()
    {
        // $type = ['71', '72'];
        $builder = $this->db->table('client as c');
        $builder->select('c.*, count(distinct(sc.scourse_id)) as sc_cr_as_id_count, ANY_VALUE(sca.sc_cr_as_id) as sc_cr_as_id,count(distinct(du.fk_id_user)) as user_count');
        $builder->join('scorm_courses_assigned as sca', 'sca.client_id = c.id_c and sca.status=1', 'left');
        $builder->join('dropdown_users as du', 'du.fk_id_d = c.id_c and du.fk_id_dc = 1 and  du.status=1', 'left');
        $builder->join('scorm_courses as sc', 'sc.scourse_id = sca.course_id and sc.type=2', 'left');
        $builder->where('c.status', '1');
        $builder->groupBy('c.id_c');
        $builder->orderBy('c.id_c', 'ASC');
        $data = $builder->get()->getResultArray();
        // print_r($builder);
        return $data;
    }
    public function getmyreviews($user)
    {
        $builder = $this->db->table('scorm_users_courses_assigned as suc');
        $builder->select('suc.*, sc.course_name as course_name');

        $builder->where('suc.id_user', $user);
        $builder->join('scorm_courses as sc', 'sc.scourse_id = suc.course_id', 'left');
        $builder->where('suc.role', 5);
        $builder->where('sc.status', 1);
        $builder->where('suc.status', 1);
        $builder->where('suc.course_status !=', 3);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getPageNameByPageID($pageid)
    {
        $builder = $this->db->table('page as p');
        $builder->where('p.page_id', $pageid);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function getNextPageID($feedbackid, $scourse_id)
    {
        $builder = $this->db->table('feedback as f');
        $builder->where('f.feedbackid > ', $feedbackid);
        $builder->where('f.course_id', $scourse_id);
        $builder->where('f.status !=', 0);
        $builder->orderBy('f.feedbackid ASC');
        $builder->limit(1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getPrevPageID($feedbackid, $scourse_id)
    {
        $builder = $this->db->table('feedback as f');
        $builder->where('f.feedbackid <', $feedbackid);
        $builder->where('f.course_id', $scourse_id);
        $builder->where('f.status !=', 0);
        $builder->orderBy('f.feedbackid DESC');
        $builder->limit(1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function clientAristouserlist()
    {
        $builder = $this->db->table('client as c');
        $builder->select('c.*, count(distinct(sc.scourse_id)) as sc_cr_as_id_count, ANY_VALUE(sca.sc_cr_as_id) as sc_cr_as_id,count(distinct(du.fk_id_user)) as user_count');
        $builder->join('scorm_courses_assigned as sca', 'sca.client_id = c.id_c and sca.status=1', 'left');
        $builder->join('dropdown_users as du', 'du.fk_id_d = c.id_c and du.fk_id_dc = 1 and  du.status=1', 'left');
        $builder->join('scorm_courses as sc', 'sc.scourse_id = sca.course_id and sc.type=3', 'left');
        $builder->where('c.status', '1');
        $builder->groupBy('c.id_c');
        $builder->orderBy('c.id_c', 'ASC');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function clientDemouserlist()
    {
        $builder = $this->db->table('client as c');
        $builder->select('c.*, count(distinct(sc.scourse_id)) as sc_cr_as_id_count, ANY_VALUE(sca.sc_cr_as_id) as sc_cr_as_id,count(distinct(du.fk_id_user)) as user_count');
        $builder->join('scorm_courses_assigned as sca', 'sca.client_id = c.id_c and sca.status=1', 'left');
        $builder->join('dropdown_users as du', 'du.fk_id_d = c.id_c and du.fk_id_dc = 1 and  du.status=1', 'left');
        $builder->join('scorm_courses as sc', 'sc.scourse_id = sca.course_id and sc.type=1', 'left');
        $builder->where('c.status', '1');
        $builder->groupBy('c.id_c');
        $builder->orderBy('c.id_c', 'ASC');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function clientXAPIuserlist()
    {
        $builder = $this->db->table('client as c');
        $builder->select('c.*, count(distinct(sc.scourse_id)) as sc_cr_as_id_count, ANY_VALUE(sca.sc_cr_as_id) as sc_cr_as_id,count(distinct(du.fk_id_user)) as user_count');
        $builder->join('scorm_courses_assigned as sca', 'sca.client_id = c.id_c and sca.status=1', 'left');
        $builder->join('dropdown_users as du', 'du.fk_id_d = c.id_c and du.fk_id_dc = 1 and  du.status=1', 'left');
        $builder->join('scorm_courses as sc', 'sc.scourse_id = sca.course_id and sc.type=5', 'left');
        $builder->where('c.status', '1');
        $builder->groupBy('c.id_c');
        $builder->orderBy('c.id_c', 'ASC');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function clientAssessmentuserlist()
    {
        $builder = $this->db->table('client as c');
        $builder->select('c.*, count(distinct(sc.scourse_id)) as sc_cr_as_id_count, ANY_VALUE(sca.sc_cr_as_id) as sc_cr_as_id,count(distinct(du.fk_id_user)) as user_count');
        $builder->join('scorm_courses_assigned as sca', 'sca.client_id = c.id_c and sca.status=1', 'left');
        $builder->join('dropdown_users as du', 'du.fk_id_d = c.id_c and du.fk_id_dc = 1 and  du.status=1', 'left');
        $builder->join('scorm_courses as sc', 'sc.scourse_id = sca.course_id and sc.type=8', 'left');
        $builder->where('c.status', '1');
        $builder->groupBy('c.id_c');
        $builder->orderBy('c.id_c', 'ASC');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getclientlist($id_c)
    {
        // $type = ['71', '72'];
        $builder = $this->db->table('client as c');
        $builder->select('c.*');
        $builder->where('c.id_c', $id_c);
        // $builder->whereIn('c.type', $type);
        $builder->where('c.status', '1');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function getUserName($id_user)
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.name');
        $builder->where('u.id_user', $id_user);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getUserasignedName($user_assign_id)
    {
        $builder = $this->db->table('scorm_users_courses_assigned as su');
        $builder->select('u.name');
        $builder->join('users as u', 'u.id_user = su.id_user', 'left');
        $builder->where('su.user_assign_id', $user_assign_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function editClientlist($newdata, $cid)
    {
        // $type = ['71', '72'];
        $builder = $this->db->table('client as c');
        $builder->where('c.id_c', $cid);
        // $builder->whereIn('c.type', $type);
        $builder->where('c.status', '1');
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function deleteClientlist($id_c, $newdata)
    {
        $builder = $this->db->table('client as c');
        $builder->where('c.id_c', $id_c);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function checkcourseassigned($client_id, $course_id)
    {
        $builder = $this->db->table('scorm_courses_assigned as c');
        $builder->select('c.sc_cr_as_id');
        $builder->where('c.client_id', $client_id);
        $builder->where('c.course_id', $course_id);
        $builder->where('c.status', '1');
        $data = $builder->countAllResults();
        return $data;
    }

    public function addmulticoursetoclient($newdata)
    {
        // print_r($newdata);
        // exit();
        foreach ($newdata['course_id'] as $course_id) {
            $scorm_course_group_assigned = [
                'client_id' => $newdata['client_id'],
                'course_id' => $course_id,
                'group_id' => $newdata['group_id'],
                'editable' => $newdata['editable'],
                'status' => $newdata['status'],
                'createdby' => $newdata['createdby'],
                'createdon' => time(),
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time()
            ];
            $builder = $this->db->table('scorm_courses_assigned');
            $builder->insert($scorm_course_group_assigned);
            $data = $builder->get()->getResultArray();
            if (!empty($data)) {
                $data['status'] = "OK";
            } else {
                $data['status'] = "Error";
            }
        }
        return $data;
    }
    public function addcoursetoclient($newdata)
    {
        $builder = $this->db->table('scorm_courses_assigned');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return true;
    }
    public function getClientCourses($clientid)
    {
        $db = \Config\Database::connect();
        // print_r($clientid);
        $builder = $this->db->table('scorm_courses_assigned as sca');
        $builder->select('ANY_VALUE(sca.sc_cr_as_id) as sc_cr_as_id,sca.course_id as course_id,sc.course_name as course_name,sc.duration as duration, scg.description as group_name,sc.type,count(suca.course_id) as user_count');
        $builder->join('scorm_users_courses_assigned as suca', 'suca.course_id = sca.course_id and suca.status=1', 'left');
        $builder->join('scorm_courses as sc', 'sc.scourse_id = sca.course_id and sc.status =1', 'left');
        $builder->join('scorm_course_group as scg', 'scg.sc_cgid = sca.group_id and scg.status=1', 'left');
        $builder->where('sca.status', '1');
        $builder->where('sca.client_id', $clientid);
        $builder->groupBy('sca.sc_cr_as_id');
        $builder->orderBy('suca.course_id');
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }


    public function getUserCourses($clientid)
    {
        $builder = $this->db->table('scorm_courses_assigned as sca');

        $builder->select([
            'sca.sc_cr_as_id',
            'sca.course_id',
            'sc.course_name',
            'sc.duration',
            'scg.description AS group_name',
            'sc.type',
            'COUNT(du.fk_id_user) AS user_count',
            'sca.editable',
            'sc.scourse_id',
            'sc.course_code'
        ]);

        $builder->join(
            'scorm_users_courses_assigned as suca',
            'suca.course_id = sca.course_id AND suca.status = 1',
            'left'
        );

        $builder->join(
            'scorm_courses as sc',
            'sc.scourse_id = sca.course_id',
            'left'
        );

        $builder->join(
            'dropdown_users as du',
            'du.fk_id_user = suca.id_user AND du.fk_id_dc = 1 AND du.status = 1',
            'left'
        );

        $builder->join(
            'scorm_course_group as scg',
            'scg.sc_cgid = sca.group_id AND scg.status = 1',
            'left'
        );

        // WHERE conditions
        $builder->where('sca.status', 1);
        $builder->where('sca.client_id', $clientid);
        $builder->where('du.fk_id_d', $clientid);   // moved from join condition

        // Grouping
        $builder->groupBy('sca.sc_cr_as_id');

        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getTypeCourses($clientid)
    {
        $builder = $this->db->table('scorm_courses_assigned as sca');
        $builder->select('sc.type');
        $builder->join('scorm_courses as sc', 'sc.scourse_id = sca.course_id', 'left');
        $builder->where('sca.status', '1');
        $builder->where('sca.client_id', $clientid);
        $builder->groupBy('sc.type');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function getAllCoursesForClient($clientid, $type)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_courses_assigned as sca');
        $builder->select('MAX(sca.sc_cr_as_id) as sc_cr_as_id,sca.course_id as course_id,sc.course_name as course_name,sc.duration as duration, ANY_VALUE(scg.description) as group_name,sc.type,count(suca.course_id) as user_count');
        $builder->join('scorm_users_courses_assigned as suca', 'suca.course_id = sca.course_id and suca.status=1', 'left');
        $builder->join('scorm_courses as sc', 'sc.scourse_id = sca.course_id', 'left');
        $builder->join('scorm_course_group as scg', 'scg.sc_cgid = sca.group_id and scg.status=1', 'left');
        // $builder->where('sc.type', $type);
        $builder->where('sca.status', '1');
        $builder->where('sca.client_id', $clientid);
        $builder->groupBy('sca.sc_cr_as_id');
        $data = $builder->get()->getResultArray();
        // print_r($builder);
        // exit();
        return $data;
    }

    public function getAllCoursesEachClient($clientid)
    {
        $type = $clientid + 50;
        $builder = $this->db->table('scorm_courses_assigned as sca');
        $builder->select('ANY_VALUE(sca.sc_cr_as_id) as sc_cr_as_id,sca.course_id as course_id,sc.course_name as course_name,sc.duration as duration, scg.description as group_name,sc.type,count(suca.course_id) as user_count');
        $builder->join('scorm_users_courses_assigned as suca', 'suca.course_id = sca.course_id and suca.status=1', 'left');
        $builder->join('scorm_courses as sc', 'sc.scourse_id = sca.course_id', 'left');
        $builder->join('scorm_course_group as scg', 'scg.sc_cgid = sca.group_id and scg.status=1', 'left');
        $builder->where('sc.type', $type);
        $builder->where('sca.status', '1');
        $builder->where('sca.client_id', $clientid);
        $builder->groupBy('sca.sc_cr_as_id');
        $data = $builder->get()->getResultArray();
        // print_r($builder);
        return $data;
    }
    public function getAllUsersForCourses($course_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_users_courses_assigned as sca');
        $builder->select('sca.course_id as course_id,sc.course_name as course_name,sud.*,u.name as username');
        $builder->join('scorm_courses as sc', 'sc.scourse_id = sca.course_id', 'left');
        $builder->where('sca.status', 1);
        $builder->join('scorm_user_details as sud', 'sud.course_id = sca.course_id and sud.student_id = sca.id_user and sud.status=1', 'left');
        $builder->join('users as u', 'u.id_user = sca.id_user', 'left');
        // $builder->where('sud.status',1);
        $builder->where('sca.course_id', $course_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getAllCoursesForUser($id_user)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_users_courses_assigned as sca');
        $builder->select('sca.course_id as course_id,sca.user_assign_id as user_assign_idx,sc.course_name as course_name,sud.*');
        $builder->where('sca.id_user', $id_user);
        $builder->where('sca.status', 1);
        $builder->join('scorm_courses as sc', 'sc.scourse_id = sca.course_id and sc.status =1', 'left');
        $builder->join('scorm_user_details as sud', 'sud.user_assign_id = sca.user_assign_id and sud.status =1', 'left');
        $builder->orderBy('sud.course_id');
        $data = $builder->get()->getResultArray();
        // print_r($data);
        // exit();
        return $data;
    }
    //getAllCoursesForUserDemo - changed to this
    public function getAllCoursesForUserbyType($id_user, $type)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_users_courses_assigned as sca');
        $builder->select('sca.course_id as course_id,sca.user_assign_id as user_assign_id,sc.course_name as course_name,sud.*');
        $builder->where('sca.id_user', $id_user);
        $builder->join('scorm_courses as sc', 'sc.scourse_id = sca.course_id', 'left');
        $builder->where('sca.status', 1);
        $builder->join('scorm_user_details as sud', 'sud.course_id = sca.course_id and sud.student_id = sca.id_user and sud.status =1', 'left');
        $builder->where('sc.type', $type);
        $builder->orderBy('sud.course_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getEnrollmentById($user_assign_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('scorm_users_courses_assigned as x');

        $builder->select('x.user_assign_id, x.fk_user_id, u.client_id');
        $builder->join('users as u', 'u.id_user = x.fk_user_id', 'left');

        $builder->where('x.user_assign_id', $user_assign_id);

        return $builder->get()->getRow();
    }
    public function getScormUserDetailsById($sc_uid)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('scorm_user_details as s');

        $builder->select('s.sc_uid, s.fk_user_id, u.client_id');
        $builder->join('users as u', 'u.id_user = s.fk_user_id', 'left');

        $builder->where('s.sc_uid', $sc_uid);
        $builder->where('s.status', 1);

        return $builder->get()->getRow();
    }
    public function getcourseusersdetails($user_assign_id)
    {
        $builder = $this->db->table('scorm_user_details as sud');
        $builder->select('sud.*,sc.course_name,sc.type,sca.id_user,count(xa.xapi_act_id) as xapiscenariocount');
        $builder->join('scorm_users_courses_assigned as sca', 'sca.user_assign_id = sud.user_assign_id', 'left');
        $builder->join('scorm_courses as sc', 'sc.scourse_id = sca.course_id', 'left');
        $builder->join('xapi_activities as xa', 'xa.sc_uid = sud.sc_uid and xa.status =1', 'left');
        $builder->where('sud.user_assign_id', $user_assign_id);
        $builder->where('sud.status', 1);
        $builder->groupBy('sud.sc_uid');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function delAssignedClientCourse($newdata, $sc_cr_as_id)
    {
        $builder = $this->db->table('scorm_courses_assigned as c');
        $builder->where('c.sc_cr_as_id', $sc_cr_as_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    // public function addcoursetousers($newdata)
    // {
    //     $db = \Config\Database::connect();
    //     // print_r($newdata);
    //     // exit();

    //     foreach ($newdata['course_id'] as $course_id) {
    //         $builder = $this->db->table('scorm_users_courses_assigned');
    //         $builder->where('id_user', $newdata['id_user']);
    //         $builder->where('course_id', $newdata['course_id']);
    //         $builder->where('status', 1);
    //         $scadata = $builder->get()->getResultArray();
    //         if (empty($scadata)) {
    //             $scorm_users_courses_assigned = [
    //                 'client_id' => session()->get('client'),
    //                 'id_user' => $newdata['id_user'],
    //                 'course_id' => $course_id,
    //                 'due_date' => $newdata['due_date'],
    //                 'status' => 1,
    //                 'createdby' => $newdata['createdby'],
    //                 'createdon' => time(),
    //                 // 'last_updated_by' =>  session()->get('id_user'),
    //                 // 'last_updated_on' => time()
    //             ];
    //             $builder = $this->db->table('scorm_users_courses_assigned');
    //             $builder->insert($scorm_users_courses_assigned);
    //             $insert_id = $db->insertID();

    //             if (!empty($insert_id)) {

    //                 $scorm_user_details = [
    //                     'user_assign_id' => $insert_id,
    //                     'course_id' => $course_id,
    //                     'student_id' => $newdata['id_user'],
    //                     'lesson_status' => 'not started',
    //                     'status' => 1,
    //                     'createdby' => $newdata['createdby'],
    //                     'createdon' => time(),
    //                     'last_updated_by' => session()->get('id_user'),
    //                     'last_updated_on' => time()
    //                 ];
    //                 $builder = $this->db->table('scorm_user_details');
    //                 $builder->insert($scorm_user_details);
    //                 $data = $builder->get()->getResultArray();
    //                 if (!empty($data)) {
    //                     $data['status'] = "OK";
    //                 } else {
    //                     $data['status'] = "Error";
    //                 }
    //             } else {
    //                 $data['status'] = "Error";
    //             }
    //         }
    //     }
    //     return $data;
    // }
    public function addcoursetousers($newdata)
{
    $db = \Config\Database::connect();
    $response = [];

    foreach ($newdata['course_id'] as $course_id) {

        // ✅ Correct duplicate check
        $builder = $db->table('scorm_users_courses_assigned');
        $builder->where('id_user', $newdata['id_user']);
        $builder->where('course_id', $course_id);
        $builder->where('status', 1);

        $exists = $builder->get()->getRow();

        if (!$exists) {

            $assignData = [
                'client_id' => $newdata['client_id'],
                'id_user' => $newdata['id_user'],
                'course_id' => $course_id,
                'due_date' => $newdata['due_date'],
                'status' => 1,
                'createdby' => $newdata['createdby'],
                'createdon' => time()
            ];

            $db->table('scorm_users_courses_assigned')->insert($assignData);
            $insert_id = $db->insertID();

            if ($insert_id) {

                $detailsData = [
                    'user_assign_id' => $insert_id,
                    'course_id' => $course_id,
                    'student_id' => $newdata['id_user'],
                    'lesson_status' => 'not started',
                    'status' => 1,
                    'createdby' => $newdata['createdby'],
                    'createdon' => time(),
                    'last_updated_by' => $newdata['createdby'],
                    'last_updated_on' => time()
                ];

                $db->table('scorm_user_details')->insert($detailsData);

                $response[] = [
                    'course_id' => $course_id,
                    'status' => 'assigned'
                ];
            } else {
                $response[] = [
                    'course_id' => $course_id,
                    'status' => 'error'
                ];
            }
        } else {
            $response[] = [
                'course_id' => $course_id,
                'status' => 'already_exists'
            ];
        }
    }

    return $response;
}
    public function addcoursetoScormUsersdetils($coursenewData)
    {
        foreach ($coursenewData['course_id'] as $course_id) {
            $scorm_users_courses_assigned = [
                'student_id' => $coursenewData['student_id'],
                'course_id' => $course_id,
                'lesson_status' => $coursenewData['lesson_status'],
                'status' => 1,
                'createdby' => $coursenewData['createdby'],
                'createdon' => time(),
                // 'last_updated_by' =>  session()->get('id_user'),
                // 'last_updated_on' => time()
            ];
            $builder = $this->db->table('scorm_user_details');
            $builder->insert($scorm_users_courses_assigned);
            $data = $builder->get()->getResultArray();
            if (!empty($data)) {
                $data['status'] = "OK";
            } else {
                $data['status'] = "Error";
            }
        }
        return $data;
    }
    public function addusertocourses($newdata)
    {
        $db = \Config\Database::connect();

        foreach ($newdata['id_user'] as $id_user) {
            $scorm_users_courses_assigned = [
                'client_id' => session()->get('client'),
                'id_user' => $id_user,
                'course_id' => $newdata['course_id'],
                'scenario_id' => $newdata['scenario_id'],
                'due_date' => $newdata['due_date'],
                'expiry_date' => $newdata['expiry_date'],
                'status' => 1,
                'createdby' => $newdata['createdby'],
                'createdon' => time(),
                // 'last_updated_by' =>  session()->get('id_user'),
                // 'last_updated_on' => time()
            ];
            $builder = $this->db->table('scorm_users_courses_assigned');
            $builder->insert($scorm_users_courses_assigned);
            $insert_id = $db->insertID();
            // $data = $builder->get()->getResultArray();

            if (!empty($insert_id)) {
                if (($newdata['scenario_id']) != 0) {
                    $xapi_scenarios_users_assign = [
                        // 'course_id' => $newdata['course_id'],
                        // 'scenario_id' => $newdata['scenario_id'],
                        // 'id_user' => $id_user,
                        'client_id' => session()->get('client'),
                        'role' => 1,
                        // 'status' => 1,
                        // 'createdby' => $newdata['createdby'],
                        // 'createdon' => time(),
                        'last_updated_by' => session()->get('id_user'),
                        'last_updated_on' => time()
                    ];
                    // $builder = $this->db->table('xapi_scenarios_users_assign');
                    $builder = $this->db->table('scorm_users_courses_assigned');
                    $builder->where('course_id', $newdata['course_id']);
                    $builder->where('scenario_id', $newdata['scenario_id']);
                    $builder->where('id_user', $id_user);
                    $builder->where('status !=', '0');
                    $builder->update($xapi_scenarios_users_assign);
                    $data = $builder->get()->getResultArray();
                }

                $scorm_user_details = [
                    'user_assign_id' => $insert_id,
                    'course_id' => $newdata['course_id'],
                    'student_id' => $id_user,
                    'scenario_id' => $newdata['scenario_id'],
                    'lesson_status' => 'not started',
                    'status' => 1,
                    'createdby' => $newdata['createdby'],
                    'createdon' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time()
                ];
                $builder = $this->db->table('scorm_user_details');
                $builder->insert($scorm_user_details);
                $data = $builder->get()->getResultArray();
                if (!empty($data)) {
                    $data['status'] = "OK";
                } else {
                    $data['status'] = "Error";
                }
            } else {
                $data['status'] = "Error";
            }
        }
        // print_r($data);
        // exit();
        return $data;
    }
    function getUsergroup($group_id)
    {
        $builder = $this->db->table("scorm_user_group_assigned as ug");
        $builder->select('ug.user_id');
        $builder->where('ug.group_id', $group_id);
        $builder->where('ug.status', '1');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getClientProjects($client)
    {
        $builder = $this->db->table('projects as p');
        $builder->select('p.*');
        $builder->where('p.client', $client);
        $builder->where('p.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function addusergrouptocourses($newdata)
    {
        $db = \Config\Database::connect();

        foreach ($newdata['id_user'] as $id_user) {
            $builder = $this->db->table('scorm_users_courses_assigned');
            $builder->where([
                'course_id' => $newdata['course_id'],
                'id_user'   => $id_user,
                'status'    => 1
            ]);
            $exists = $builder->countAllResults();
            if ($exists == 0) {
                $scorm_users_courses_assigned = [
                    'client_id' => session()->get('client'),
                    'id_user' => $id_user,
                    'course_id' => $newdata['course_id'],
                    'scenario_id' => $newdata['scenario_id'],
                    'due_date' => $newdata['due_date'],
                    'expiry_date' => $newdata['expiry_date'],
                    'stage' => $newdata['stage'],
                    'role' => $newdata['role'],
                    'status' => 1,
                    'createdby' => $newdata['createdby'],
                    'createdon' => time(),
                    // 'last_updated_by' =>  session()->get('id_user'),
                    // 'last_updated_on' => time()
                ];
                $builder = $this->db->table('scorm_users_courses_assigned');
                $builder->insert($scorm_users_courses_assigned);
                $insert_id = $db->insertID();
                // $data = $builder->get()->getResultArray();

                if (!empty($insert_id)) {
                    if (($newdata['scenario_id']) != 0) {
                        $xapi_scenarios_users_assign = [
                            // 'course_id' => $newdata['course_id'],
                            // 'scenario_id' => $newdata['scenario_id'],
                            // 'id_user' => $id_user,
                            'role' => 1,
                            // 'status' => 1,
                            // 'createdby' => $newdata['createdby'],
                            // 'createdon' => time(),
                            'last_updated_by' => session()->get('id_user'),
                            'last_updated_on' => time()
                        ];
                        // $builder = $this->db->table('xapi_scenarios_users_assign');
                        $builder = $this->db->table('scorm_users_courses_assigned');
                        $builder->where('course_id', $newdata['course_id']);
                        $builder->where('scenario_id', $newdata['scenario_id']);
                        $builder->where('id_user', $id_user);
                        $builder->where('status', '1');
                        $builder->update($xapi_scenarios_users_assign);
                        $data = $builder->get()->getResultArray();
                    }

                    $scorm_user_details = [
                        'user_assign_id' => $insert_id,
                        'course_id' => $newdata['course_id'],
                        'student_id' => $id_user,
                        'scenario_id' => $newdata['scenario_id'],
                        'lesson_status' => 'not started',
                        'status' => 1,
                        'createdby' => $newdata['createdby'],
                        'createdon' => time(),
                        'last_updated_by' => session()->get('id_user'),
                        'last_updated_on' => time()
                    ];
                    $builder = $this->db->table('scorm_user_details');
                    $builder->insert($scorm_user_details);
                    $data = $builder->get()->getResultArray();
                    if (!empty($data)) {
                        $data['status'] = "OK";
                    } else {
                        $data['status'] = "Error";
                    }
                } else {
                    $data['status'] = "Error";
                }
            } else {
                $data['status'] = "Error";
            }
        }
        return $data;
    }
    public function demo_users_view($clientid)
    {
        $builder = $this->db->table("users as u");
        $builder->select('u.*,count(s.scourse_id) as courseassignedcount');
        $builder->join('dropdown_users as du', 'du.fk_id_user = u.id_user and and du.fk_id_dc = 1 and du.status=1', "left");
        $builder->join('dropdown_category as dc', 'dc.id_dc = du.fk_id_dc ', "left");
        $builder->join('scorm_users_courses_assigned as su', 'su.id_user = u.id_user and su.status=1', 'left');
        $builder->join('scorm_courses as s', 's.scourse_id = su.course_id and s.type=1', 'left');
        $builder->where('u.valid', '1');
        $builder->where('du.status', '1');
        $builder->where('du.fk_id_d', $clientid);
        $builder->groupBy('u.id_user', 'asc');
        $builder->orderBy('u.id_user', 'asc');
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
    public function user_assigned_courses($clientid, $type)
    {
        $builder = $this->db->table("users as u");
        $builder->select('u.*,count(s.scourse_id) as courseassignedcount');
        $builder->join('dropdown_users as du', 'du.fk_id_user = u.id_user and and du.fk_id_dc = 1 and du.status=1', "left");
        $builder->join('dropdown_category as dc', 'dc.id_dc = du.fk_id_dc ', "left");
        $builder->join('scorm_users_courses_assigned as su', 'su.id_user = u.id_user and su.status=1', 'left');
        $builder->join('scorm_courses as s', 's.scourse_id = su.course_id and s.type=' . $type, 'left');
        $builder->where('u.valid', '1');
        $builder->where('du.status', '1');
        $builder->where('du.fk_id_d', $clientid);
        $builder->groupBy('u.id_user', 'asc');
        $builder->orderBy('u.id_user', 'asc');
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
    function deleteuserscoursedetails($newdata, $user_assign_id)
    {
        $builder = $this->db->table('scorm_users_courses_assigned as cu');
        $builder->where('cu.user_assign_id', $user_assign_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getScormuserdetails($sc_uid)
    {
        $builder = $this->db->table('scorm_user_details as sud');
        $builder->select('sud.*');
        $builder->where('sud.sc_uid', $sc_uid);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getUserlatestCourse($course_id)
    {
        $builder = $this->db->table('scorm_users_courses_assigned as sca');
        $builder->select('sca.course_id as course_id, sc.course_name as course_name, sud.*, u.name as username,u.id_user');
        $builder->join('scorm_courses as sc', 'sc.scourse_id = sca.course_id', 'left');
        $builder->where('sca.status', 1);
        $builder->join('users as u', 'u.id_user = sca.id_user', 'left');
        $builder->where('sca.course_id', $course_id);

        $subquery = $this->db->table('scorm_user_details')
            ->select('student_id, MAX(sc_uid) AS max_sc_uid')
            ->where('course_id', $course_id)
            ->where('status', 1)
            ->groupBy('student_id')
            ->getCompiledSelect();
        $builder->join('(' . $subquery . ') latest_sud', 'latest_sud.student_id = sca.id_user', 'left');

        $builder->join('scorm_user_details as sud', 'sud.course_id = sca.course_id AND sud.student_id = sca.id_user AND sud.sc_uid = latest_sud.max_sc_uid AND sud.status = 1', 'left');

        $data = $builder->get()->getResultArray();
        // print_r($builder);
        // exit();
        return $data;
    }
    function getUserlatestclientCourse($course_id, $client)
    {
        $builder = $this->db->table('scorm_users_courses_assigned as sca');
        $builder->select('sca.course_id as course_id, ANY_VALUE(sca.scenario_id) as scenario_id, sc.course_name as course_name, ANY_VALUE(sud.sc_uid) as sc_uid,count(sud.attempt) as attempt, ANY_VALUE(u.name) as username,ANY_VALUE(u.id_user) as id_user');
        $builder->join('scorm_courses as sc', 'sc.scourse_id = sca.course_id', 'left');

        $builder->where('sca.status', 1);
        $builder->join('users as u', 'u.id_user = sca.id_user', 'left');
        $builder->join('dropdown_users as du', 'du.fk_id_user = u.id_user and and du.fk_id_dc = 1 and du.status=1', "left");
        $builder->where('sca.course_id', $course_id);
        $builder->where('du.fk_id_d', $client);

        $subquery = $this->db->table('scorm_user_details')
            ->select('student_id, MAX(sc_uid) AS max_sc_uid')
            ->where('course_id', $course_id)
            ->where('status', 1)
            ->groupBy('student_id')
            ->getCompiledSelect();
        $builder->join('(' . $subquery . ') latest_sud', 'latest_sud.student_id = sca.id_user', 'left');

        $builder->join('scorm_user_details as sud', 'sud.course_id = sca.course_id AND sud.student_id = sca.id_user AND sud.sc_uid = latest_sud.max_sc_uid AND sud.status = 1', 'left');

        $data = $builder->get()->getResultArray();
        // print_r($builder);
        // exit();
        return $data;
    }
    function getUserlatestclientCourseByScenariocopy($course_id, $scenario_id, $client)
    {
        $builder = $this->db->table('scorm_users_courses_assigned as sca');
        $builder->select('sca.course_id as course_id, sca.user_assign_id as suser_assign_id, sc.course_name as course_name, sud.*,sud.user_assign_id as scorm_userassign_id, u.name as username,u.id_user,su.grace');
        $builder->join('scorm_courses as sc', 'sc.scourse_id = sca.course_id', 'left');

        $builder->where('sca.status', 1);
        $builder->join('users as u', 'u.id_user = sca.id_user', 'left');
        $builder->join('dropdown_users as du', 'du.fk_id_user = u.id_user and and du.fk_id_dc = 1 and du.status=1', "left");
        $builder->where('sca.course_id', $course_id);
        $builder->where('sca.scenario_id', $scenario_id);
        $builder->where('du.fk_id_d', $client);

        $subquery = $this->db->table('scorm_user_details as sc')
            ->select('sc.user_assign_id,sc.sc_uid AS max_sc_uid')
            ->join('scorm_users_courses_assigned as su', 'su.user_assign_id = sc.user_assign_id', 'left')
            // ->where('course_id', $course_id)
            // ->where('scenario_id', $scenario_id)
            ->where('sc.status', 1)
            ->orderBy('sc.sc_uid', 'desc')
            ->limit('1')
            ->getCompiledSelect();
        $builder->join('(' . $subquery . ') latest_sud', 'latest_sud.user_assign_id = sca.user_assign_id', 'left');

        $builder->join('scorm_user_details as sud', 'sud.sc_uid = latest_sud.max_sc_uid AND sud.status = 1', 'left');

        $data = $builder->get()->getResultArray();
        // print_r($builder);
        // exit();
        return $data;
    }
    function getUserlatestclientCourseByScenario($course_id, $client)
    {
        $builder = $this->db->table('scorm_users_courses_assigned as sca');
        $builder->select('sca.course_id AS course_id,sca.status as enrollstatus,sca.course_status,sca.scenario_id AS scenario_id_imp,sca.user_assign_id AS suser_assign_id,sc.course_name AS course_name,sud.*,sud.user_assign_id AS scorm_userassign_id,u.name AS username,u.id_user,sca.grace');
        $builder->join('scorm_courses as sc', 'sc.scourse_id = sca.course_id', 'left');
        $builder->join('users as u', 'u.id_user = sca.id_user', 'left');
        // $builder->join('dropdown_users as du', 'du.fk_id_user = u.id_user and du.fk_id_dc = 1 and du.status = 1', 'left');
        $builder->join('scorm_user_details as sud', 'sud.user_assign_id = sca.user_assign_id AND sud.status = 1', 'left');
        $builder->where('sca.course_id', $course_id);
        //  $builder->where('sca.scenario_id', $scenario_id);
        $builder->where('u.client_id', $client);
        $builder->where('sca.status !=', 0);

        $builder->groupStart();
        $builder->where('sud.sc_uid IS NULL');
        $builder->orWhere("sud.sc_uid = (SELECT max(sud2.sc_uid) FROM scorm_user_details as sud2 WHERE sud2.user_assign_id = sud.user_assign_id and sud2.status =1)");
        $builder->groupEnd();
        $data = $builder->get()->getResultArray();
        //  echo $this->db->getLastQuery();
        // print_r($data);
        // exit();
        return $data;
    }

    function getUserlatestCourseUsers($id_user)
    {
        $builder = $this->db->table('scorm_users_courses_assigned as sca')
            ->select('sca.course_id AS course_id, sca.user_assign_id AS suser_assign_id, ANY_VALUE(sca.scenario_id) as scenario_id, sc.course_name AS course_name, ANY_VALUE(sud.session_time) as session_time ,ANY_VALUE(sud.total_time) as total_time,ANY_VALUE(sud.attempt) as attempt ,ANY_VALUE(sud.lesson_status) as lesson_status,ANY_VALUE(sud.raw) as raw,ANY_VALUE(sud.user_assign_id) as user_assign_id,ANY_VALUE(sud.user_assign_id) AS scorm_userassign_id, u.name AS username, u.id_user, sc.type')
            ->join('scorm_courses as sc', 'sc.scourse_id = sca.course_id', 'left')
            ->join('users as u', 'u.id_user = sca.id_user and u.valid =1', 'left')
            ->join('scorm_user_details as sud', 'sud.user_assign_id = sca.user_assign_id AND sud.status = 1', 'left')
            ->where('sca.id_user', $id_user)
            ->where('sca.status', 1)
            ->groupStart()
            ->where('sud.sc_uid IS NULL')
            ->orWhere('sud.sc_uid = (SELECT MAX(sud2.sc_uid) FROM scorm_user_details as sud2 WHERE sud2.user_assign_id = sud.user_assign_id AND sud2.status = 1)')
            ->groupEnd();

        $data = $builder->get()->getResultArray();
        // echo $this->db->getlastQuery();
        // exit();
        return $data;

        // print_r($id_user);
        // $builder = $this->db->table('scorm_users_courses_assigned as sca');
        // $builder->select('sca.course_id AS course_id,sca.user_assign_id AS suser_assign_id,sca.scenario_id,sc.course_name AS course_name,sud.*,sud.user_assign_id AS scorm_userassign_id,u.name AS username,u.id_user,sc.type');
        // $builder->join('scorm_courses as sc', 'sc.scourse_id = sca.course_id', 'left');
        // $builder->join('users as u', 'u.id_user = sca.id_user', 'left');
        // $builder->join('dropdown_users as du', 'du.fk_id_user = u.id_user and du.fk_id_dc = 1 and du.status = 1', 'left');
        // $builder->join('scorm_user_details as sud', 'sud.user_assign_id = sca.user_assign_id AND sud.status = 1', 'left');
        // $builder->where('sca.id_user', $id_user);

        // $builder->where('sca.status', 1);
        // $builder->groupStart();
        // $builder->where('sud.sc_uid IS NULL');
        // $builder->orWhere("sud.sc_uid = (SELECT MAX(sud2.sc_uid) FROM scorm_user_details as sud2 WHERE sud2.user_assign_id = sud.user_assign_id and sud2.status =1)");
        // $builder->groupEnd();
        // $data = $builder->get()->getResultArray();
        // //  print_r($builder);
        // return $data;

    }
    function getUserlatestCourseUsersbysearch($id_user, $course_name)
    {
        $where = "(sc.course_name LIKE '%$course_name%')";
        $builder = $this->db->table('scorm_users_courses_assigned as sca')
            ->select('sca.course_id AS course_id, sca.user_assign_id AS suser_assign_id, sca.scenario_id, sc.course_name AS course_name, sud.session_time,sud.total_time,sud.attempt,sud.lesson_status,sud.raw,sud.user_assign_id,sud.user_assign_id AS scorm_userassign_id, u.name AS username, u.id_user, sc.type')
            ->join('scorm_courses as sc', 'sc.scourse_id = sca.course_id', 'left')
            ->join('users as u', 'u.id_user = sca.id_user and u.valid =1', 'left')
            ->join('scorm_user_details as sud', 'sud.user_assign_id = sca.user_assign_id AND sud.status = 1', 'left')
            ->where('sca.id_user', $id_user)
            ->where($where)
            ->where('sca.status', 1)
            ->groupStart()
            ->where('sud.sc_uid IS NULL')
            ->orWhere('sud.sc_uid = (SELECT MAX(sud2.sc_uid) FROM scorm_user_details as sud2 WHERE sud2.user_assign_id = sud.user_assign_id AND sud2.status = 1)')
            ->groupEnd();

        $data = $builder->get()->getResultArray();
        // echo $this->db->getlastQuery();
        // exit();
        return $data;
    }
    function searchcoursereportByStatus($id_user, $status)
    {
        if ($status == 0) {
            $lesson_status = ['Not Started', 'not started'];
        } elseif ($status == 1) {
            $lesson_status = ['Incomplete', 'incomplete'];
        } elseif ($status == 2) {
            $lesson_status = ['completed', 'Completed', 'passed'];
        }
        $builder = $this->db->table('scorm_users_courses_assigned as sca')
            ->select('sca.course_id AS course_id, sca.user_assign_id AS suser_assign_id, sca.scenario_id, sc.course_name AS course_name, sud.session_time,sud.total_time,sud.attempt,sud.lesson_status,sud.raw,sud.user_assign_id,sud.user_assign_id AS scorm_userassign_id, u.name AS username, u.id_user, sc.type')
            ->join('scorm_courses as sc', 'sc.scourse_id = sca.course_id', 'left')
            ->join('users as u', 'u.id_user = sca.id_user and u.valid =1', 'left')
            ->join('scorm_user_details as sud', 'sud.user_assign_id = sca.user_assign_id AND sud.status = 1', 'left')
            ->where('sca.id_user', $id_user)
            ->whereIn('sud.lesson_status', $lesson_status)
            ->where('sca.status', 1)
            ->groupStart()
            ->where('sud.sc_uid IS NULL')
            ->orWhere('sud.sc_uid = (SELECT MAX(sud2.sc_uid) FROM scorm_user_details as sud2 WHERE sud2.user_assign_id = sud.user_assign_id AND sud2.status = 1)')
            ->groupEnd();

        $data = $builder->get()->getResultArray();
        // echo $this->db->getlastQuery();
        // exit();
        return $data;
    }
    function deleteScormUserdetails($newdata, $sc_uid)
    {
        $builder = $this->db->table('scorm_user_details as cu');
        $builder->where('cu.sc_uid', $sc_uid);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function deleteEnrollment($newdata, $user_assign_id)
    {
        $builder = $this->db->table('scorm_users_courses_assigned as cu');
        $builder->where('cu.user_assign_id', $user_assign_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getCoursename($course_id)
    {
        $builder = $this->db->table('scorm_courses as sc');
        $builder->select('sc.course_name, sc.type');
        $builder->where('sc.scourse_id ', $course_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }


    function getAllAttempts($scourse_id, $client_id)
    {
        $builder = $this->db->table('scorm_user_details as sc');
        $builder->select('sc.sc_uid, sc.raw, sc.createdon, sc.total_time,sc.session_time,sc.lesson_status, u.name,sud.id_user, sc.user_assign_id');
        $builder->join('scorm_users_courses_assigned as sud', 'sud.user_assign_id = sc.user_assign_id', 'left');
        $builder->join('users as u', 'u.id_user = sud.id_user', 'left');
        $builder->join('dropdown_users as du', 'du.fk_id_user = sud.id_user and du.fk_id_dc = 1', 'left');
        $builder->where('du.fk_id_d', $client_id);
        $builder->where('sud.course_id ', $scourse_id);
        $builder->where('sc.status', 1);
        $builder->orderBy('sc.raw', 'DESC');
        $builder->groupBy('sc.sc_uid');
        $data = $builder->get()->getResultArray();
        // print_r($builder);
        // exit();
        return $data;
    }

    function allmissed($scourse_id, $client_id)
    {
        $builder = $this->db->table('scorm_user_details as sc');
        $builder->select('ANY_VALUE(xa.value) as value, ANY_VALUE(xa.xapi_act_id) as xapi_act_id ,ANY_VALUE(sud.user_assign_id) as user_assign_id,ANY_VALUE(u.id_user) as id_user,ANY_VALUE(xa.sc_uid) as sc_uid');
        $builder->join('scorm_users_courses_assigned as sud', 'sud.user_assign_id = sc.user_assign_id', 'left');
        $builder->join('users as u', 'u.id_user = sud.id_user', 'left');
        $builder->join('dropdown_users as du', 'du.fk_id_user = sud.id_user and du.fk_id_dc = 1', 'left');
        $builder->join('xapi_activities as xa', 'xa.sc_uid = sc.sc_uid', 'left');
        $builder->where('du.fk_id_d', $client_id);
        $builder->where('sud.course_id ', $scourse_id);
        $builder->where('xa.variable', 'missed');
        $builder->where('xa.status ', 1);
        $builder->where('sc.status', 1);
        $builder->orderBy('sc.raw', 'DESC');
        $builder->groupBy('sc.sc_uid');
        $data = $builder->get()->getResultArray();
        // print_r($builder);
        // exit();
        return $data;
    }

    function addCounter($newdata, $xov)
    {
        $builder = $this->db->table('xapi_output_variables as cu');
        $builder->where('cu.xov', $xov);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function updateMissed($newdata1, $xapi_act_id)
    {
        $builder = $this->db->table('xapi_activities as cu');
        $builder->where('cu.xapi_act_id', $xapi_act_id);
        $builder->update($newdata1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function addXapiOutData($newdata)
    {
        $builder = $this->db->table('xapi_output_data');
        $builder->insert($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function insertTotaltime($data, $sc_uid)
    {
        $builder = $this->db->table('scorm_user_details as sc');
        $builder->where('sc.sc_uid', $sc_uid);
        $builder->update($data);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getScuserdetails()
    {
        $builder = $this->db->table('scorm_user_details as sud');
        $builder->select('sud.*');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function add_bulk_course()
    {
        $builder = $this->db->table('scorm_courses as sca');
        $builder->select('sca.*');
        $builder->where('sca.scourse_id >', '629');
        $builder->where('sca.status ', '1');
        $data = $builder->get()->getResultArray();
        if (!empty($data)) {
            foreach ($data as $eachdata) {
                $scorm_course_group_assigned = [
                    'client_id' => 1,
                    'course_id' => $eachdata['scourse_id'],
                    'group_id' => 0,
                    'editable' => 1,
                    'status' => 1,
                    'createdby' => 1,
                    'createdon' => time(),
                ];
                $builder = $this->db->table('scorm_courses_assigned');
                $builder->insert($scorm_course_group_assigned);
                $data = $builder->get()->getResultArray();
            }
            echo 'Data add successfully';
        } else {
            echo lang('Messages.Error_0001');
        }
    }
}
