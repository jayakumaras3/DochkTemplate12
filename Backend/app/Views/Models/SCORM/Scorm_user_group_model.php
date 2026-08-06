<?php

namespace App\Models\SCORM;

use CodeIgniter\Model;
use PHPUnit\Framework\MockObject\Stub\ReturnReference;

class Scorm_user_group_model extends Model
{
    function addcoursegroupdetails($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_course_group');
        $builder->insert($newdata);
        $data = $db->insertID();
        return  $data;
    }
    function add_user_to_gr($newdata)
    {
        foreach ($newdata['user_id'] as $user_id) {
            $scorm_course_group_assigned = [
                'user_id' => $user_id,
                'group_id' => $newdata['group_id'],
                'status' => $newdata['status'],
                'createdby' => $newdata['createdby'],
                'createdon' => time(),
                'last_updated_by' =>  session()->get('id_user'),
                'last_updated_on' => time()
            ];
            $builder = $this->db->table('scorm_user_group_assigned');
            $builder->insert($scorm_course_group_assigned);
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
    // function add_course_to_gr($newdata)
    // {
    //     $db = \Config\Database::connect();
    //     $builder = $this->db->table('scorm_course_group_assigned');
    //     $builder->insert($newdata);
    //     $data = $builder->get()->getResultArray();
    //     return  $data;
    // }

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


    function getCoursegroupdate($type, $client)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_course_group as mc');
        $builder->select('mc.*, count(distinct ac.assign_id) as assign_id_count');
        $builder->where('mc.status =', '1');
        $builder->where('mc.type =', $type);
        $builder->join('scorm_course_group_clients as scgc', 'scgc.group_id = mc.sc_cgid', 'left');

        $builder->join('scorm_course_group_assigned as ac', 'ac.group_id = mc.sc_cgid and ac.status!=0', 'left');
        $builder->groupby('mc.sc_cgid');
        $builder->where('scgc.status =', '1');
        $builder->where('scgc.client_id =', $client);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getUsersAssignedto($sc_cgid)
    {

        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_user_group_assigned as mc');
        $builder->select('mc.*,sc_co.*,CONCAT(sc_co.name," ",sc_co.last_name) as username');
        $builder->join('users as sc_co', 'sc_co.id_user = mc.user_id', 'left');
        $builder->where('mc.group_id', $sc_cgid);
        $builder->where('mc.status =', '1');
        // $builder->groupBy('sc_co.scourse_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getusersgroupDetails($sc_cgid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_course_group as mc');
        $builder->select('mc.*');
        $builder->where('mc.sc_cgid', $sc_cgid);
        $builder->where('mc.status =', '1');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getUsersDetails()
    {
        $client = session()->get('client');
        // if ($client != 1) {
        //     $builder = $this->db->table('users as u');
        //     $builder->select('u.*');
        //     $builder->join('dropdown_users as du', 'du.fk_id_user = u.id_user and  du.fk_id_dc=2 and du.status =1', 'left');
        //     $builder->where('du.fk_id_d', 45);
        //     $builder->where('u.client_id', $client);
        //     $builder->orderBy('u.name', 'ASC');
        //     $builder->where('u.valid', 1);
        // } else {
        $builder = $this->db->table('users as u');
        $builder->select('u.*');
        $builder->where('u.client_id', $client);
        $builder->orderBy('u.name', 'ASC');
        $builder->where('u.valid', 1);
        // }
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
        return  $data;
    }

    public function delete_user_assigned_mod($newdata, $assign_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_user_group_assigned');
        $builder->where('assign_id', $assign_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray($newdata);
        return  $data;
    }
    function getCourseUserGroupdata($type)
    {
        $client = session()->get('client');
        $builder = $this->db->table('scorm_course_group as sg');
        $builder->select('sg.*,sum(c.group_id)');
        $builder->join('scorm_course_group_clients as c', 'c.group_id = sg.sc_cgid', 'left');
        $builder->where('type', $type);
        $builder->where('sg.status', 1);
        $builder->where('c.client_id', $client);
        $builder->groupBy('c.group_id');
        return $builder->get()->getResultArray();
    }
    function assigncoursegrouptousergroup($c_gid, $u_gid)
    {
        $builder = $this->db->table('scorm_course_group_assigned');
        $builder->select('*');
        $builder->where('group_id', $c_gid);
        $builder->where('status', 1);
        $data = $builder->get()->getResultArray();
        // print_r($data);
        // exit();
        if (!empty($data)) {
            foreach ($data  as $eachassigneddata) {
                $builder = $this->db->table('scorm_user_group_assigned');
                $builder->select('*');
                $builder->where('group_id', $u_gid);
                $builder->where('status', 1);
                $userdata = $builder->get()->getResultArray();
                // print_r($userdata);
                // exit();
                if (!empty($userdata)) {
                    foreach ($userdata  as $user) {
                        $builder = $this->db->table('scorm_users_courses_assigned as sca');
                        $builder->select('*');
                        $builder->where('id_user', $user['user_id']);
                        $builder->where('course_id', $eachassigneddata['course_id']);
                        $builder->where('status', 1);
                        $data = $builder->get()->getResultArray();
                        // print_r($data);
                        // exit();
                        if (empty($data)) {
                            $newdata = [
                                'id_user' => $user['user_id'],
                                'course_id' => $eachassigneddata['course_id'],
                                'due_date' => '0000-00-00',
                                'expiry_date' => '0000-00-00',
                                'status' => 1,
                                'createdby' => session()->get('id_user'),
                                'createdon' => time()
                            ];
                            $db = \Config\Database::connect();
                            $builder = $this->db->table('scorm_users_courses_assigned');
                            $builder->insert($newdata);
                            $assignID = $db->insertID();
                            //  print_r($assignID);
                            //     exit();
                        }
                        if (!empty($userdata)) {
                            $builder = $this->db->table('scorm_course_group_users');
                            $builder->select('*');
                            $builder->where('sc_cgid', $c_gid);
                            $builder->where('user_id', $user['user_id']);
                            $builder->where('status', 1);
                            $coursegroupuseracess = $builder->get()->getResultArray();

                            if (empty($coursegroupuseracess)) {
                                $groupdata = [
                                    'sc_cgid' => $c_gid,
                                    'user_id' => $user['user_id'],
                                    'status' => '1',
                                    'last_updated_by' => session()->get('id_user'),
                                    'last_updated_on' => time()

                                ];
                                $builder = $this->db->table('scorm_course_group_users');
                                $builder->insert($groupdata);
                            }
                        }
                    }
                }
            }
            $data['success'] = 'Assigned successfully!';
        } else {
            $data['error'] = 'Course not found';
        }
        return $data;
    }
}
