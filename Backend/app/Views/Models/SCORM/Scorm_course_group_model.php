<?php

namespace App\Models\SCORM;

use CodeIgniter\Model;

class Scorm_course_group_model extends Model
{
    function addcoursegroupdetails($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_course_group');
        $builder->insert($newdata);
        $data = $builder->get()->getResultArray();
        // if($data){
        //    $insertID = $db->insertID();
        //    print_r($insertID);
        //    exit();
        // }
        return $data;
    }
    function add_trainnercourse_to_gr($newdata)
    {
        $builder = $this->db->table('scorm_course_group_assigned');
        $builder->insert($newdata);
        return true;
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
                $data['status'] = "OK";
            } else {
                $data['status'] = "Error";
            }
        }
        return $data;
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


    function getCoursegroupdate($type)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_course_group as mc');
        $builder->select('mc.*, count(distinct ac.assign_id) as assign_id_count');
        $builder->where('mc.status =', '1');
        $builder->where('mc.type !=', 3);
        $builder->join('scorm_course_group_assigned as ac', 'ac.group_id = mc.sc_cgid and ac.status!=0', 'left');
        $builder->join('scorm_courses as sc', 'sc.scourse_id = ac.course_id', 'left');
        $builder->where(' sc.status !=', 0);
        $builder->groupby('mc.sc_cgid');
        $data = $builder->get()->getResultArray();
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
        $builder->select('ANY_VALUE(mc.assign_id) as assign_id,ANY_VALUE(mc.course_id) as course_id,sc_co.scourse_id,ANY_VALUE(sc_co.course_name) as coursename, GROUP_CONCAT(DISTINCT(sm.description) ORDER BY sm.description ASC SEPARATOR ", ")  as category,MAX(su.lesson_status) as lesson_status');
        $builder->join('scorm_courses as sc_co', 'sc_co.scourse_id = mc.course_id', 'left');
        $builder->join('assign_meta_category as am', 'am.fk_scourse_id = sc_co.scourse_id', 'left');
        $builder->join('scorm_meta_category as sm', 'sm.sc_mcid = am.fk_sc_mcid and am.typeofval =2 and sm.status =1', 'left');
        $builder->join('scorm_user_details as su', 'su.course_id = sc_co.scourse_id and su.student_id =' . session()->get("id_user"), 'left');
        $builder->where('mc.group_id', $sc_cgid);
        $builder->where('mc.status =', '1');
        $builder->groupBy('sc_co.scourse_id');
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

    public function delete_course_assigned_mod($newdata, $assign_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_course_group_assigned');
        $builder->where('assign_id', $assign_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray($newdata);
        return $data;
    }
}
