<?php

namespace App\Models\SCORM;

use CodeIgniter\Model;

class Scorm_learn_group_model extends Model
{
    function addcoursegroupdetails($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_course_group');
        $builder->insert($newdata);
        //$data = $builder->get()->getResultArray();
        $data = $db->insertID();
        return $data;
    }
    function add_course_to_gr($newdata)
    {
        foreach ($newdata['course_id'] as $course_id) {
            $scorm_course_group_assigned = [
                'course_id' => $course_id,
                'group_id' => $newdata['group_id'],
                'status' => $newdata['status'],
                'createdby' => $newdata['createdby'],
                'createdon' => time(),
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time()
            ];
            $builder = $this->db->table('scorm_course_group_assigned');
            $builder->insert($scorm_course_group_assigned);
            $data = $builder->get()->getResultArray();
            if (!empty($data)) {
                $builder = $this->db->table('scorm_course_group_users');
                $builder->select('*');
                $builder->where('sc_cgid', $newdata['group_id']);
                $builder->where('status', 1);
                $scgdata = $builder->get()->getResultArray();
                if (!empty($scgdata)) {
                    foreach ($scgdata as $eachassigneddata) {
                        $builder = $this->db->table('scorm_users_courses_assigned as sca');
                        $builder->select('*');
                        $builder->where('id_user', $eachassigneddata['user_id']);
                        $builder->where('course_id', $course_id);
                        $builder->where('status', 1);
                        $data = $builder->get()->getResultArray();
                        if (empty($data)) {
                            $newdata = [
                                'id_user' => $eachassigneddata['user_id'],
                                'course_id' => $course_id,
                                'due_date' => '0000-00-00',
                                'expiry_date' => '0000-00-00',
                                'status' => 1,
                                'createdby' => session()->get('id_user'),
                                'createdon' => time()
                            ];
                            $db = \Config\Database::connect();
                            $builder = $this->db->table('scorm_users_courses_assigned');
                            $builder->insert($newdata);
                        }
                    }
                }
                $data['status'] = "OK";
            } else {
                $data['status'] = "Error";
            }
        }
        return $data;
    }
    function add_course_to_grauto($projectid, $group_id, $language)
    {
        $builder = $this->db->table('scorm_courses as mc');
        $builder->select('mc.*');
        $builder->where('mc.status =', '1');
        $builder->where('mc.project_id =', $projectid);
        $builder->where('mc.language =', $language);
        $newdata = $builder->get()->getResultArray();
        if (!empty($newdata)) {
            foreach ($newdata as $data) {
                $builder = $this->db->table('scorm_course_group_assigned as mc');
                $builder->select('mc.*');
                $builder->where('mc.status =', '1');
                $builder->where('mc.course_id =', $data['scourse_id']);
                $builder->where('mc.group_id =', $group_id);
                $groupdata = $builder->get()->getResultArray();
                if (empty($groupdata)) {
                    $scorm_course_group_assigned = [
                        'course_id' => $data['scourse_id'],
                        'group_id' => $group_id,
                        'status' => 1,
                        'createdby' => session()->get('id_user'),
                        'createdon' => time()

                    ];
                    $builder = $this->db->table('scorm_course_group_assigned');
                    $builder->insert($scorm_course_group_assigned);
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
        } else {
            $data['status'] = "Error";
        }
        return $data;
    }

    function add_client_to_gr($newData)
    {

        $builder = $this->db->table('scorm_course_group_clients');
        $builder->insert($newData);
        return true;
    }

    function check_if_course_exists_in_group($course_id, $sc_cgid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_course_group_assigned as mc');
        $builder->select('mc.*');
        $builder->where('mc.status =', '1');
        $builder->where('mc.course_id =', $course_id);
        $builder->where('mc.group_id =', $sc_cgid);
        $data = $builder->countAllResults();
        return $data;
    }

    function clientuserlist()
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('client as c');
        $builder->select('c.*');
        $builder->where('c.status =', '1');
        $builder->orderBy('c.client_name');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function clientassignedlist($sc_cgid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_course_group_clients as scg');
        $builder->select('scg.*, c.client_name as client');
        $builder->join('client as c', 'c.id_c = scg.client_id and c.status!=0', 'left');
        $builder->where('scg.status !=', '0');
        $builder->where('scg.group_id', $sc_cgid);
        $builder->orderBy('c.client_name');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getCoursegroupdate($type, $client)
    {
        $userlevel = session()->get('userlevel');
        $arrayuserlevel  = explode(',', $userlevel);
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_course_group as mc');
        $builder->select('mc.*, count(distinct sc.scourse_id) as assign_id_count');
        $builder->where('mc.status =', '1');
        $builder->where('mc.type =', $type);
        $builder->join('scorm_course_group_clients as scgc', 'scgc.group_id = mc.sc_cgid', 'left');
        $builder->join('scorm_course_group_assigned as ac', 'ac.group_id = mc.sc_cgid and ac.status!=0', 'left');
        $builder->join('scorm_courses as sc', 'sc.scourse_id = ac.course_id and sc.status != 0', 'left');
        $builder->join('scorm_course_group_users as ca', 'ca.sc_cgid = scgc.group_id', 'left');

        $builder->groupby('mc.sc_cgid');
        if (!in_array('6', $arrayuserlevel) || !in_array('44', $arrayuserlevel)) {
            $builder->where('ca.user_id', session()->get('id_user'));
            $builder->where('ca.status!=', 0);
        }

        // }
        // $builder->where('sc.status !=', 0);
        $builder->where('scgc.status =', '1');
        $builder->where('scgc.client_id =', $client);
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    function getCoursesAssignedto($sc_cgid)
    {
        // $db = \Config\Database::connect();
        // $builder = $this->db->table('scorm_courses as c');
        // $builder->select('c.*,GROUP_CONCAT(DISTINCT(sm.description) ORDER BY sm.description ASC SEPARATOR ", ")  as category,MAX(su.lesson_status) as lesson_status');
        // $builder->join('assign_meta_category as am', 'am.fk_scourse_id = c.scourse_id', 'left');
        // $builder->join('scorm_meta_category as sm', 'sm.sc_mcid = am.fk_sc_mcid and am.typeofval =2 and sm.status =1', 'left');
        // $builder->join('scorm_user_details as su', 'su.course_id = c.scourse_id and su.student_id =' . session()->get("id_user"), 'left');
        // $builder->where('c.type ', $type);
        // $builder->where('c.status ', '1');
        // $builder->groupBy('c.scourse_id');
        // $data = $builder->get()->getResultArray();
        // return $data;

        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_course_group_assigned as mc');
        $builder->select('ANY_VALUE(mc.assign_id) as assign_id,sc_co.*,sc_co.course_name as coursename,sc_co.scourse_id as scourse_id');
        $builder->join('scorm_courses as sc_co', 'sc_co.scourse_id = mc.course_id', 'left');
        //  $builder->join('assign_meta_category as am', 'am.fk_scourse_id = sc_co.scourse_id', 'left');
        //   $builder->join('scorm_meta_category as sm', 'sm.sc_mcid = am.fk_sc_mcid and am.typeofval =2 and sm.status =1', 'left');
        //     $builder->join('scorm_user_details as su', 'su.course_id = sc_co.scourse_id and su.student_id =' . session()->get("id_user") . ' and su.status =1', 'left');
        $builder->where('mc.group_id', $sc_cgid);
        $builder->where('mc.status =', '1');
        $builder->where('sc_co.status !=', 0);
        $builder->groupBy('sc_co.scourse_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function get_client_group($client)
    {
        $builder = $this->db->table('scorm_course_group_clients as scgc');
        $builder->select('lcg.*');
        $builder->join('scorm_course_group as lcg', 'lcg.sc_cgid = scgc.group_id and scgc.status =1', 'left');
        $builder->where('scgc.client_id', $client);
        $builder->where('lcg.type =', '3');
        $builder->where('lcg.status =', '1');
        $builder->where('scgc.status =', '1');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_client_group_all($client)
    {
        $builder = $this->db->table('scorm_course_group_clients as scgc');
        $builder->select('lcg.*');
        $builder->join('scorm_course_group as lcg', 'lcg.sc_cgid = scgc.group_id and scgc.status =1', 'left');
        $builder->where('scgc.client_id', $client);
        $builder->where('lcg.type =', '3');
        $builder->where('lcg.visible =', '0');
        $builder->where('lcg.status =', '1');
        $builder->where('scgc.status =', '1');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_client_group_to_me($client, $id_user)
    {
        $builder = $this->db->table('scorm_course_group_clients as scgc');
        $builder->select('lcg.*');
        $builder->join('scorm_course_group as lcg', 'lcg.sc_cgid = scgc.group_id and scgc.status =1', 'left');
        $builder->join('scorm_course_group_users as scgu', 'scgu.sc_cgid = lcg.sc_cgid', 'left');
        $builder->where('scgc.client_id', $client);
        $builder->where('scgu.user_id  ', $id_user);
        $builder->where('scgu.status =', '1');
        $builder->where('lcg.type =', '3');
        $builder->where('lcg.visible =', '1');
        $builder->where('lcg.status =', '1');
        $builder->where('scgc.status =', '1');
        $builder->groupBy('lcg.sc_cgid');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getcoursegroupDetails($sc_cgid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_course_group as mc');
        $builder->select('mc.*');
        $builder->where('mc.sc_cgid', $sc_cgid);
        $builder->where('mc.status =', '1');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getGroupUsers($sc_cgid, $client)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_course_group_users as scg');
        $builder->select('scg.scgu_id, us.name, us.last_name,us.id_user');
        $builder->join('users as us', 'us.id_user = scg.user_id', 'left');
        $builder->where('us.client_id', $client);
        $builder->where('us.valid', 1);
        $builder->where('scg.sc_cgid', $sc_cgid);
        $builder->where('scg.status =', '1');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getCategorydata()
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_meta_category as mc');
        $builder->select('mc.*');
        $builder->where('mc.typeofval', 2);
        $builder->where('mc.status =', '1');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function editcoursegpdetails($newdata, $sc_cgid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_course_group');
        $builder->where('sc_cgid', $sc_cgid);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray($newdata);
        return $data;
    }

    public function del_client_to_gr($newData, $scgcid)
    {
        $builder = $this->db->table('scorm_course_group_clients');
        $builder->where('scgcid', $scgcid);
        $builder->update($newData);
        return true;
    }
    public function delete_course_assigned_mod($newdata, $assign_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_course_group_assigned');
        $builder->where('assign_id', $assign_id);
        $builder->update($newdata);
        return true;
    }
    public function checklearnassigned($id_user, $group_id)
    {
        $builder = $this->db->table('learning_group as c');
        $builder->select('c.id_lg');
        $builder->where('c.id_user', $id_user);
        $builder->where('c.group_id', $group_id);
        $builder->where('c.status', '1');
        $data = $builder->countAllResults();
        return $data;
    }
    public function addcoursegroupassignusers($newdata)
    {
        $builder = $this->db->table('scorm_course_group_users');
        $builder->insert($newdata);
        return true;
    }
    public function scorm_course_group_assigned($newdata)
    {
        $builder = $this->db->table('scorm_course_group_assigned');
        $builder->insert($newdata);
        return true;
    }
    public function addLearncoursetoUser($newdata)
    {
        $builder = $this->db->table('learning_group');
        $builder->insert($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getlearngroupdata($id_user)
    {
        $builder = $this->db->table('learning_group as lg');
        $builder->select('lg.*,lcg.description as learn_group_name,lcg.logo');
        $builder->join('scorm_course_group as lcg', 'lcg.sc_cgid = lg.group_id and lcg.status =1', 'left');
        $builder->where('lg.id_user =', $id_user);
        $builder->where('lg.status =', '1');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getlearngroupCourses($id_user, $group_id)
    {
        $builder = $this->db->table('scorm_course_group_assigned as lg');
        $builder->select('lg.*,sc.*,su.*,scg.description as group_name,l.due_date,l.complete_percent');
        $builder->join('scorm_courses as sc', 'sc.scourse_id= lg.course_id and sc.status =1', 'left');
        $builder->join('scorm_users_courses_assigned as sca', 'sca.course_id= lg.course_id and sca.id_user= ' . $id_user . ' and sca.status =1', 'left');
        $builder->join('scorm_user_details as su', 'su.user_assign_id= sca.user_assign_id and su.status =1', 'left');
        $builder->join('scorm_course_group as scg', 'scg.sc_cgid = lg.group_id', 'left');
        $builder->join('learning_group as l', 'l.group_id = lg.group_id and l.status=1', 'left');
        $builder->where('lg.group_id =', $group_id);
        $builder->where('lg.status =', '1');
        $builder->groupBy('lg.course_id');
        $builder->orderBy('lg.course_id');
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    function updateGroupUserData($newdata, $scgu_id)
    {
        $builder = $this->db->table('scorm_course_group_users as s');
        $builder->where('s.scgu_id ', $scgu_id);
        $builder->update($newdata);
        return true;
    }
    function updategrouplogo($newdata, $sc_cgid)
    {
        $builder = $this->db->table('scorm_course_group as s');
        $builder->where('s.sc_cgid', $sc_cgid);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function updateCompletionPerncent($group_id, $userid, $complete_percent)
    {
        $builder = $this->db->table('learning_group as lg');
        $builder->set('complete_percent', $complete_percent);
        $builder->where('group_id', $group_id);
        $builder->where('id_user', $userid);
        $builder->update();
        $data = $builder->get()->getResultArray();
        return $data;
    }
}
