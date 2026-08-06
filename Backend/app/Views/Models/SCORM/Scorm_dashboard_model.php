<?php

namespace App\Models\SCORM;

use CodeIgniter\Model;

class Scorm_dashboard_model extends Model
{
    public function getClientCourseddata()
    {
        $client = session()->get('client');
        $builder = $this->db->table('scorm_courses_assigned as sc');
        $builder->select('sc.*,c.*,GROUP_CONCAT(DISTINCT(sm.description) ORDER BY sm.description ASC SEPARATOR ", ")  as category,MAX(su.lesson_status) as lesson_status');
        $builder->join('scorm_courses as c', 'c.scourse_id = sc.course_id', 'left');
        $builder->join('assign_meta_category as am', 'am.fk_scourse_id = c.scourse_id', 'left');
        $builder->join('scorm_meta_category as sm', 'sm.sc_mcid = am.fk_sc_mcid and am.typeofval =2 and sm.status =1', 'left');
        $builder->join('scorm_user_details as su', 'su.course_id = c.scourse_id and su.student_id =' . session()->get("id_user"), 'left');
        $builder->where('sc.client_id', $client);
        $builder->where('sc.status ', '1');
        $builder->where('c.status ', '1');
        $builder->groupBy('c.scourse_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getFavoriteCourses($id_user)
    {
        $builder = $this->db->table('scorm_favorite_courses as scf');
        $builder->select('c.*,GROUP_CONCAT(DISTINCT sm.description ORDER BY sm.description ASC SEPARATOR ", ") as category,
    MAX(su.lesson_status) as lesson_status,
    MAX(su.last_updated_on) as lastupdate,
    MAX(sca.course_status) as course_status,
    MAX(sca.due_date) as due_date');
        $builder->where('scf.user_id', $id_user);
        $builder->join('scorm_courses as c', 'c.scourse_id = scf.scourse_id', 'left');
        $builder->join('scorm_users_courses_assigned as sca', 'sca.course_id = scf.scourse_id', 'left');
        $builder->join('scorm_user_details as su', 'su.course_id = c.scourse_id and su.student_id =' . $id_user, 'left');
        $builder->join('assign_meta_category as am', 'am.fk_scourse_id = c.scourse_id', 'left');
        $builder->join('scorm_meta_category as sm', 'sm.sc_mcid = am.fk_sc_mcid and am.typeofval =2 and sm.status =1', 'left');

        $builder->where('c.status', '1');
        $builder->where('sca.type', 0);
        $builder->groupBy('c.scourse_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function assignDemoUserstoAllCourses($user_id)
    {
        $builder = $this->db->table('scorm_courses as c');
        $builder->select('c.scourse_id');
        $builder->where('c.demo ', '1');
        $builder->where('c.status', '1');
        $coursedata = $builder->get()->getResultArray();
        if ($coursedata) {
            foreach ($coursedata as $data) {
                $builder = $this->db->table('scorm_users_courses_assigned');
                $builder->select('*');
                $builder->where('course_id ', $data['scourse_id']);
                $builder->where('id_user', $user_id);
                $builder->where('status !=', '0');
                $assigndata = $builder->get()->getResultArray();
                if (empty($assigndata)) {
                    $scorm_users_courses_assigned = [
                        'id_user' => $user_id,
                        'course_id' => $data['scourse_id'],
                        'status' => 1,
                        'createdby' => session()->get('id_user'),
                        'createdon' => time(),
                    ];
                    $builder = $this->db->table('scorm_users_courses_assigned');
                    $builder->insert($scorm_users_courses_assigned);
                    $data = $builder->get()->getResultArray();
                }
            }
            if ($data) {
                echo 'Assigned User to All Demo Courses';
            } else {
                echo 'No Data found';
            }
        }
    }
    public function getsearchAllCourseddata($type, $search)
    {
        $where = "(c.course_name LIKE '%$search%' OR ";
        $where .= "sm.description LIKE '%$search%' )";
        $builder = $this->db->table('scorm_courses as c');
        $builder->select('c.*,GROUP_CONCAT(DISTINCT(sm.description) ORDER BY sm.description ASC SEPARATOR ", ")  as category,MAX(su.lesson_status) as lesson_status');
        $builder->join('assign_meta_category as am', 'am.fk_scourse_id = c.scourse_id', 'left');
        $builder->join('scorm_meta_category as sm', 'sm.sc_mcid = am.fk_sc_mcid and am.typeofval =2 and sm.status =1', 'left');
        $builder->join('scorm_user_details as su', 'su.course_id = c.scourse_id and su.student_id =' . session()->get("id_user"), 'left');
        $builder->where('c.type ', $type);
        $builder->where('c.status', '1');
        $builder->where($where);
        $builder->groupBy('c.scourse_id');
        $data = $builder->get()->getResultArray();
        // print_r($builder);
        // exit();
        return $data;
    }
    public function getsearchAllDemoCourseddata($search)
    {
        $where = "(c.course_name LIKE '%$search%' OR ";
        $where .= "sm.description LIKE '%$search%' )";
        $builder = $this->db->table('scorm_courses as c');
        $builder->select('c.*,GROUP_CONCAT(DISTINCT(sm.description) ORDER BY sm.description ASC SEPARATOR ", ")  as category,MAX(su.lesson_status) as lesson_status');
        $builder->join('assign_meta_category as am', 'am.fk_scourse_id = c.scourse_id', 'left');
        $builder->join('scorm_meta_category as sm', 'sm.sc_mcid = am.fk_sc_mcid and am.typeofval =2 and sm.status =1', 'left');
        $builder->join('scorm_user_details as su', 'su.course_id = c.scourse_id and su.student_id =' . session()->get("id_user"), 'left');
        $builder->where('c.demo ', 1);
        $builder->where('c.status', '1');
        $builder->where($where);
        $builder->groupBy('c.scourse_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getsearchCoursesAssignedToMe($id_user, $search)
    {
        $searchTerm = str_replace("'", "\\'", $search);
        $where = "(c.course_name LIKE '%$searchTerm%' OR ";
        $where .= "sm.description LIKE '%$searchTerm%' )";
        $builder = $this->db->table('scorm_courses as c');
        $builder = $this->db->table('scorm_users_courses_assigned as sca');
        $builder->select('c.*,GROUP_CONCAT(DISTINCT sm.description ORDER BY sm.description ASC SEPARATOR ", ") as category,
    MAX(su.lesson_status) as lesson_status,
    MAX(su.last_updated_on) as lastupdate,
    MAX(sca.course_status) as course_status,
    MAX(sca.due_date) as due_date');
        $builder->where('sca.id_user', $id_user);
        $builder->join('scorm_courses as c', 'c.scourse_id = sca.course_id', 'left');
        $builder->join('scorm_user_details as su', 'su.course_id = c.scourse_id and su.student_id =' . $id_user, 'left');
        $builder->join('assign_meta_category as am', 'am.fk_scourse_id = c.scourse_id', 'left');
        $builder->join('scorm_meta_category as sm', 'sm.sc_mcid = am.fk_sc_mcid and am.typeofval =2 and sm.status =1', 'left');
        $builder->where('sca.status', 1);
        $builder->where('c.status', '1');
        $builder->where('sca.type', 0);
        $builder->groupBy('sca.id_user');
        $builder->groupBy('sca.course_id');
        // $builder->groupBy('sca.user_assign_id');
        $builder->where($where);
        $builder->groupBy('c.scourse_id');
        $data = $builder->get()->getResultArray();
        $builder->limit(8);
        return $data;
    }
    public function getC4UCourses()
    {
        $builder = $this->db->table('scorm_courses as c');
        $builder->where('c.project_id', 29);
        $builder->where('c.mode', '2');
        $builder->where('c.status', '1');
        $builder->orderBy('scourse_id', 'RANDOM');
        $builder->limit(4);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function getAllCourseddata($type)
    {
        $builder = $this->db->table('scorm_courses as c');
        $builder->select('c.*,GROUP_CONCAT(DISTINCT(sm.description) ORDER BY sm.description ASC SEPARATOR ", ")  as category,MAX(su.lesson_status) as lesson_status');
        $builder->join('assign_meta_category as am', 'am.fk_scourse_id = c.scourse_id', 'left');
        $builder->join('scorm_meta_category as sm', 'sm.sc_mcid = am.fk_sc_mcid and am.typeofval =2 and sm.status =1', 'left');
        $builder->join('scorm_user_details as su', 'su.course_id = c.scourse_id and su.student_id =' . session()->get("id_user"), 'left');
        $builder->where('c.type ', $type);
        $builder->where('c.status ', '1');
        $builder->groupBy('c.scourse_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getAllDemoCourseddata()
    {
        $builder = $this->db->table('scorm_courses as c');
        $builder->select('c.*,GROUP_CONCAT(DISTINCT(sm.description) ORDER BY sm.description ASC SEPARATOR ", ")  as category,MAX(su.lesson_status) as lesson_status');
        $builder->join('assign_meta_category as am', 'am.fk_scourse_id = c.scourse_id', 'left');
        $builder->join('scorm_meta_category as sm', 'sm.sc_mcid = am.fk_sc_mcid and am.typeofval =2 and sm.status =1', 'left');
        $builder->join('scorm_user_details as su', 'su.course_id = c.scourse_id and su.student_id =' . session()->get("id_user"), 'left');
        $builder->where('c.demo ', 1);
        $builder->where('c.status ', '1');
        $builder->groupBy('c.scourse_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    // public function democoursenamessearch_view($course_name)
    // {
    //     $db = \Config\Database::connect();
    //     $builder = $this->db->table('scorm_courses as c');
    //     $builder->select('c.*,GROUP_CONCAT(DISTINCT(sm.description) ORDER BY sm.description ASC SEPARATOR ", ")  as category,MAX(su.lesson_status) as lesson_status');
    //     $builder->join('assign_meta_category as am', 'am.fk_scourse_id = c.scourse_id', 'left');
    //     $builder->join('scorm_meta_category as sm', 'sm.sc_mcid = am.fk_sc_mcid and am.typeofval =2 and sm.status =1', 'left');
    //     $builder->join('scorm_user_details as su', 'su.course_id = c.scourse_id and su.student_id =' . session()->get("id_user"), 'left');
    //     $builder->like('c.course_name', '%' . $course_name . '%');
    //     $builder->where('c.demo ', 1);
    //     $builder->where('c.status ', '1');
    //     $builder->groupBy('c.scourse_id');
    //     $data = $builder->get()->getResultArray();
    //     return $data;
    // }
    public function democoursenamessearch_view()
    {
        $builder = $this->db->table('scorm_courses as c');
        $builder->select('c.*,GROUP_CONCAT(DISTINCT(sm.description) ORDER BY sm.description ASC SEPARATOR ", ")  as category,MAX(su.lesson_status) as lesson_status');
        $builder->join('assign_meta_category as am', 'am.fk_scourse_id = c.scourse_id', 'left');
        $builder->join('scorm_meta_category as sm', 'sm.sc_mcid = am.fk_sc_mcid and sm.typeofval =2 and sm.status =1', 'left');
        $builder->join('scorm_user_details as su', 'su.course_id = c.scourse_id and su.student_id =' . session()->get("id_user"), 'left');
        // $builder->like('c.course_name', '%' . $course_name . '%');
        $builder->where('c.demo ', 1);
        $builder->where('c.status ', '1');
        $builder->groupBy('c.scourse_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    // public function democourseCategoryssearch_view($categories)
    // {
    //     // print_r(!empty($categories));
    //     //  exit();
    //     $builder = $this->db->table('scorm_courses as c');
    //     $builder->select('c.*, 
    //     GROUP_CONCAT(DISTINCT(sm.description) ORDER BY sm.description ASC SEPARATOR "| ") as category 
    //    ');

    //     $builder->join('assign_meta_category as am', 'am.fk_scourse_id = c.scourse_id and am.status !=0 ', 'left');
    //     $builder->join('scorm_meta_category as sm', 'sm.sc_mcid = am.fk_sc_mcid and sm.typeofval =2 and sm.status =1', 'left');
    //     // $builder->join('scorm_user_details as su', 'su.course_id = c.scourse_id and su.student_id =' . session()->get("id_user"), 'left');

    //     if (!empty($categories) && is_array($categories)) {
    //         $builder->whereIn('am.fk_sc_mcid', $categories);
    //     }

    //     $builder->where('c.demo', '1');
    //     $builder->where('c.status', '1');
    //     $builder->groupBy('c.scourse_id');

    //     $data = $builder->get()->getResultArray();
    //     //  echo $this->db->getLastQuery();
    //     // // print_r($query);
    //     // exit();
    //     return $data;
    // }
    public function democourseCategoryssearch_view($categories)
    {
        $builder = $this->db->table('scorm_courses as c');
        $builder->select('c.*');
        if (!empty($categories)) {
            if (is_array($categories)) {
                $terms = $categories;
            } else {
                $terms = explode(',', $categories);
            }
            $terms = array_map('trim', $terms);
            $builder->groupStart();
            foreach ($terms as $i => $term) {
                if ($i === 0) {
                    $builder->like('c.demo_category', $term);
                } else {
                    $builder->orLike('c.demo_category', $term);
                }
            }
            $builder->groupEnd();
        }
        $builder->where('c.demo', '1');
        $builder->where('c.status', '1');
        $builder->groupBy('c.scourse_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }



    public function getAlleachCourseddata($course_id)
    {
        $builder = $this->db->table('scorm_courses as c');
        $builder->where('c.scourse_id ', $course_id);
        $builder->where('c.status ', '1');
        $data = $builder->get()->getResultArray();
        //  echo $this->db->getLastQuery();
        // // print_r($query);
        // exit();
        return $data;
    }
    public function getCoursesAssignedToMe($id_user)
    {
        $builder = $this->db->table('scorm_users_courses_assigned as sca');

        $builder->select('c.*,GROUP_CONCAT(DISTINCT sm.description ORDER BY sm.description ASC SEPARATOR ", ") as category,
    MAX(su.lesson_status) as lesson_status,
    MAX(su.last_updated_on) as lastupdate,
    MAX(sca.course_status) as course_status,
    MAX(sca.due_date) as due_date');  // 'false' to prevent escaping

        $builder->where('sca.id_user', $id_user);

        $builder->join('scorm_courses as c', 'c.scourse_id = sca.course_id', 'left');
        $builder->join('scorm_user_details as su', 'su.user_assign_id = sca.user_assign_id', 'left');
        $builder->join('assign_meta_category as am', 'am.fk_scourse_id = c.scourse_id', 'left');
        $builder->join('scorm_meta_category as sm', 'sm.sc_mcid = am.fk_sc_mcid AND am.typeofval = 2 AND sm.status = 1', 'left');

        $builder->where('sca.status', 1);
        $builder->where('sca.group_id', 0);
        $builder->where('c.status', '1');
        $builder->where('sca.type', 0);

        $builder->groupBy('sca.id_user');
        $builder->groupBy('sca.course_id');

        // ✅ Use raw expressions for ordering with aggregates
        $builder->orderBy('MAX(su.last_updated_on)', 'DESC', false);
        $builder->orderBy('FIELD(MAX(sca.course_status), 1)', 'DESC', false);
        $builder->orderBy('FIELD(MAX(sca.course_status), 0)', 'DESC', false);
        $builder->orderBy('FIELD(MAX(sca.course_status), 2)', 'ASC', false);


        $builder->limit(8);

        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        //     // print_r($data);
        //      exit();  
        return $data;
    }
    function assignCourseToUser($user, $courseId, $mpId, $type)
    {
        $builder = $this->db->table('scorm_users_courses_assigned');

        $existing = $builder
            ->where('id_user', $user)
            ->where('course_id', $courseId)
            ->where('status !=', 0)
            ->get()
            ->getResultArray();

        // print_r($existing);
        // exit();

        if (empty($existing)) {

            $newData = [
                'client_id' => session()->get('client'),
                'id_user' => $user,
                'course_id' => $courseId,
                'mp_id' => $mpId,
                'due_date' => '0000-00-00',
                'expiry_date' => '0000-00-00',
                'status' => 1,
                'type' => $type,
                'createdby' => $user,
                'createdon' => time()
            ];

            $this->db
                ->table('scorm_users_courses_assigned')
                ->insert($newData);
        } else {
            if (!empty($mpId)) {
                $mpexisting = $builder
                    ->where('id_user', $user)
                    ->where('course_id', $courseId)
                    ->where('mp_id', $mpId)
                    ->where('status !=', 0)
                    ->get()
                    ->getResultArray();
                if (empty($mpexisting)) {

                    $this->db
                        ->table('scorm_users_courses_assigned')
                        ->set('mp_id', $mpId)
                        ->where('id_user', $user)
                        ->where('course_id', $courseId)
                        ->where('status !=', 0)
                        ->update();
                }
            }
        }
    }


    public function assignPreAttempttoQiz($c_gid)
    {
        $builder = $this->db->table('scorm_course_group_assigned');
        $builder->select('*');
        $builder->where('group_id', $c_gid);
        $builder->where('status', 1);
        $data = $builder->get()->getResultArray();
        if (!empty($data)) {
            foreach ($data as $eachassigneddata) {
                $builder = $this->db->table('page');
                $builder->select('*');
                $builder->where('fk_course_id', $eachassigneddata['course_id']);
                $builder->where('type', 4);
                $builder->where('status', 1);
                $pagedata = $builder->get()->getResultArray();
                if (!empty($pagedata)) {
                    $newdata = [
                        'page_id' => $pagedata[0]['page_id'],
                        'scourse_id' => $eachassigneddata['course_id'],
                        'type' => '4',
                        'value' => 'Disabled',
                        'status' => 1,
                        'createdby' => session()->get('id_user'),
                        'createdon' => time()
                    ];
                    $builder = $this->db->table('assessment_settings');
                    $builder->insert($newdata);
                }
            }
            echo 'Assigned successfully';
        } else {
            echo lang('Messages.Error_0001');
        }
    }

    public function assignUserstoUsergroup($u_gid)
    {
        $builder = $this->db->table('users');
        $builder->select('*');
        $builder->where('valid', 1);
        $builder->where('name !=', 'Demo User');
        $builder->where('client_id', '1');
        $builder->notLike('username ', 'user');
        $userdata = $builder->get()->getResultArray();
        // echo "<pre>";
        // print_r($data);
        // exit();
        if (!empty($userdata)) {
            foreach ($userdata as $user) {
                $scorm_course_group_assigned = [
                    'user_id' => $user['id_user'],
                    'group_id' => $u_gid,
                    'status' => 1,
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time()
                ];
                $builder = $this->db->table('scorm_user_group_assigned');
                $builder->insert($scorm_course_group_assigned);
            }
            echo 'success';
        } else {
            echo 'Not found';
        }
    }
    public function getAllCoursedexportdata()
    {
        $builder = $this->db->table('scorm_courses as c');
        $builder->select('c.*,GROUP_CONCAT(DISTINCT(sm.description) ORDER BY sm.description ASC SEPARATOR ", ")  as category,GROUP_CONCAT(DISTINCT(sm1.description) ORDER BY sm1.description ASC SEPARATOR ", ")  as metadata');
        $builder->join('assign_meta_category as am', 'am.fk_scourse_id = c.scourse_id', 'left');
        $builder->join('scorm_meta_category as sm', 'sm.sc_mcid = am.fk_sc_mcid and am.typeofval =2 and sm.status =1', 'left');
        $builder->join('scorm_meta_category as sm1', 'sm1.sc_mcid = am.fk_sc_mcid and am.typeofval =1 and sm1.status =1', 'left');
        $builder->where('c.status ', '1');
        $builder->groupBy('c.scourse_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getSelectedCoursesdetails($course_id)
    {
        $builder = $this->db->table('scorm_courses as c');
        $builder->select('c.*,GROUP_CONCAT(DISTINCT(sm.description) ORDER BY sm.description ASC SEPARATOR ", ")  as category,GROUP_CONCAT(DISTINCT(sm1.description) ORDER BY sm1.description ASC SEPARATOR ", ")  as metadata');
        $builder->join('assign_meta_category as am', 'am.fk_scourse_id = c.scourse_id', 'left');
        $builder->join('scorm_meta_category as sm', 'sm.sc_mcid = am.fk_sc_mcid and am.typeofval =2 and sm.status =1', 'left');
        $builder->join('scorm_meta_category as sm1', 'sm1.sc_mcid = am.fk_sc_mcid and am.typeofval =1 and sm1.status =1', 'left');
        $builder->whereIn('c.scourse_id', $course_id);
        $builder->where('c.status', '1');
        $builder->groupBy('c.scourse_id');
        $data = $builder->get()->getResultArray();
        // print_r($builder);
        // exit();
        return $data;
    }
    public function getUserTimestamp($id_user)
    {
        $builder = $this->db->table("users as u");
        $builder->select('u.timestamp,u.id_user, u.name,u.app_username');
        $builder->where('id_user', $id_user);
        $builder->where('valid', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function getUserAthenticate($app_username)
    {
        $builder = $this->db->table("users as u");
        $builder->select('u.timestamp,u.id_user,u.username,u.name,u.password,u.app_username,u.app_password');
        $builder->where('valid', 1);
        $builder->where('app_username', $app_username);
        $builder->orwhere('username', $app_username);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function checkUserassignedtocourse($userid, $course_id)
    {
        $builder = $this->db->table('scorm_users_courses_assigned as sca');
        $builder->select('sca.*');
        $builder->where('sca.id_user', $userid);
        $builder->where('sca.course_id', $course_id);
        $builder->where('sca.status ', '1');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getCoursesAssigned($id_user, $course_id)
    {
        // $db = \Config\Database::connect();
        // $builder = $this->db->table('scorm_user_details as s');
        // $builder->select('c.*,GROUP_CONCAT(DISTINCT(sm.description) ORDER BY sm.description ASC SEPARATOR ", ")  as category,s.lesson_status,s.*');
        // $builder->join('scorm_users_courses_assigned as sca', 'sca.user_assign_id = s.user_assign_id and sca.status=1', 'left');
        // $builder->join('scorm_courses as c', 'c.scourse_id = s.course_id and c.status =1', 'left');
        // $builder->join('assign_meta_category as am', 'am.fk_scourse_id = c.scourse_id', 'left');
        // $builder->join('scorm_meta_category as sm', 'sm.sc_mcid = am.fk_sc_mcid and am.typeofval =2 and sm.status =1', 'left');
        // $builder->where('sca.id_user', $id_user);
        // $builder->where('sca.course_id', $course_id);
        // $builder->where('s.status', '1');
        // // $builder->groupby('sca.user_assign_id');
        // // $builder->orderBy('s.attempt','desc');
        // $data = $builder->get()->getResultArray();
        // // print_r($data);
        // // exit();
        // echo $this->db->getLastQuery();
        // exit();
        // return $data;
        $builder = $this->db->table('scorm_users_courses_assigned as sca');
        $builder->select('c.*,GROUP_CONCAT(DISTINCT(sm.description) ORDER BY sm.description ASC SEPARATOR ", ")  as category,MAX(su.lesson_status) as lesson_status,max(su.attempt) ,sca.user_assign_id,sca.course_status,sca.due_date,sca.last_updated_by,sca.role');
        $builder->join('scorm_courses as c', 'c.scourse_id = sca.course_id', 'left');
        $builder->join('scorm_user_details as su', 'su.user_assign_id = sca.user_assign_id and su.status !=0', 'left');
        $builder->join('assign_meta_category as am', 'am.fk_scourse_id = c.scourse_id', 'left');
        $builder->join('scorm_meta_category as sm', 'sm.sc_mcid = am.fk_sc_mcid and am.typeofval =2 and sm.status =1', 'left');
        $builder->where('sca.id_user', $id_user);
        $builder->where('sca.course_id', $course_id);
        $builder->where('sca.status', 1);
        $builder->where('c.status', '1');
        $builder->orderby('sca.user_assign_id', 'desc');
        $builder->groupBy('sca.user_assign_id');
        $builder->limit(1);
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // print_r($data);
        // exit();
        return $data;
    }
    function editableCoursedata($client_id, $course_id)
    {
        $builder = $this->db->table('scorm_courses_assigned as sca');
        $builder->select('sca.editable');
        $builder->where('sca.client_id', $client_id);
        $builder->where('sca.course_id', $course_id);
        $builder->where('sca.status', '1');
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // print_r($data);
        // exit();
        return $data;
    }
    public function getCoursesdetails($course_id)
    {
        // $db = \Config\Database::connect();
        // $builder = $this->db->table('scorm_user_details as s');
        // $builder->select('c.*,GROUP_CONCAT(DISTINCT(sm.description) ORDER BY sm.description ASC SEPARATOR ", ")  as category,s.lesson_status,s.*');
        // $builder->join('scorm_users_courses_assigned as sca', 'sca.user_assign_id = s.user_assign_id and sca.status=1', 'left');
        // $builder->join('scorm_courses as c', 'c.scourse_id = s.course_id and c.status =1', 'left');
        // $builder->join('assign_meta_category as am', 'am.fk_scourse_id = c.scourse_id', 'left');
        // $builder->join('scorm_meta_category as sm', 'sm.sc_mcid = am.fk_sc_mcid and am.typeofval =2 and sm.status =1', 'left');
        // $builder->where('sca.id_user', $id_user);
        // $builder->where('sca.course_id', $course_id);
        // $builder->where('s.status', '1');
        // // $builder->groupby('sca.user_assign_id');
        // // $builder->orderBy('s.attempt','desc');
        // $data = $builder->get()->getResultArray();
        // // print_r($data);
        // // exit();
        // echo $this->db->getLastQuery();
        // exit();
        // return $data;
        $id_user = session()->get('id_user');
        $builder = $this->db->table('scorm_users_courses_assigned as sca');
        $builder->select('c.*,MAX(su.lesson_status) as lesson_status,Max(su.attempt) as attempt,MAX(sca.course_status) as course_status,MAX(sca.due_date) as due_date,MAX(sca.last_updated_by) as last_updated_by');
        $builder->join('scorm_courses as c', 'c.scourse_id = sca.course_id', 'left');
        $builder->join('scorm_user_details as su', 'su.user_assign_id = sca.user_assign_id and su.status=1', 'left');
        $builder->join('assign_meta_category as am', 'am.fk_scourse_id = c.scourse_id', 'left');
        $builder->join('scorm_meta_category as sm', 'sm.sc_mcid = am.fk_sc_mcid and am.typeofval =2 and sm.status =1', 'left');
        $builder->where('sca.id_user', $id_user);
        $builder->where('sca.course_id', $course_id);
        $builder->where('sca.status !=', 0);
        $builder->where('c.status', '1');
        $builder->groupBy('sca.course_id');
        // $builder->orderby('sca.user_assign_id');
        // $builder->orderBy('su.attempt', 'desc');


        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // // print_r($data);
        //  exit();
        return $data;
    }
    public function getCoursesdetailslibrary($course_id)
    {
        $id_user = session()->get('id_user');
        $builder = $this->db->table('scorm_courses as c');
        $builder->select('c.*,GROUP_CONCAT(DISTINCT(sm.description) ORDER BY sm.description ASC SEPARATOR ", ")  as category');
        $builder->join('assign_meta_category as am', 'am.fk_scourse_id = c.scourse_id', 'left');
        $builder->join('scorm_meta_category as sm', 'sm.sc_mcid = am.fk_sc_mcid and am.typeofval =2 and sm.status =1', 'left');
        $builder->where('c.scourse_id', $course_id);
        $builder->where('c.status !=', 0);
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // // print_r($data);
        //  exit();
        return $data;
    }

    function getClientUserCount()
    {
        $client = session()->get('client');
        $builder = $this->db->table('users as u');
        $builder->select('count(u.id_user) as user_count');
        $builder->where('u.client_id', $client);
        $builder->where('u.valid', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function coursesAssignedCount($id_user)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('scorm_users_courses_assigned as sca');
        $builder->select('count(c.scourse_id) as course_count');
        $builder->where('sca.id_user', $id_user);
        $builder->join('scorm_courses as c', 'c.scourse_id = sca.course_id', 'left');
        $builder->where('sca.status', 1);
        $builder->where('c.status', '1');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function democourseCount()
    {
        $builder = $this->db->table('scorm_courses as c');
        $builder->select('count(c.scourse_id) as demo_count');
        $builder->where('c.demo', 1);
        $builder->where('c.status', '1');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getUserCartCount($user)
    {
        $builder = $this->db->table('cart as c');
        $builder->select('count(c.course_id) as cart_count');
        $builder->join('scorm_courses as sc', 'sc.scourse_id  = c.course_id', 'left');
        $builder->where('c.createdby', $user);
        $builder->where('c.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getSalesReportCount($user)
    {
        $builder = $this->db->table('cart_assigned as ca');
        $builder->select('count(ca.id) as salesReportCount');
        $builder->where('ca.createdby', $user);
        $builder->where('ca.expiry_date >=', date('Y-m-d'));
        $builder->where('ca.status', 1);
        $builder->orderBy("id", "desc");
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getCategoriesofclient($client)
    {
        $builder = $this->db->table('assign_client_to_category as ac');
        $builder->select('ac.*,smc.description,count(amc.fk_scourse_id) as course_count');

        $builder->join('scorm_meta_category as smc', 'smc.sc_mcid = ac.category_id and smc.typeofval =2');
        $builder->join('assign_meta_category as amc', 'amc.fk_sc_mcid =smc.sc_mcid and amc.status =1', 'left');
        $builder->join('scorm_courses as sc', 'sc.scourse_id = amc.fk_scourse_id', 'left');
        $builder->where('ac.client', $client);
        $builder->where('sc.status =1');
        $builder->where('ac.status', 1);
        $builder->groupBy('smc.sc_mcid');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getpagedetails($fk_course_id)
    {
        $builder = $this->db->table('page as p');
        $builder->select('p.*');
        $builder->where('p.fk_course_id', $fk_course_id);
        $builder->where('p.page_number', 1);
        $builder->where('p.status !=', 0);
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
}
