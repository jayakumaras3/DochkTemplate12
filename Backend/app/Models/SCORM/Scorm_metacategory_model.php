<?php

namespace App\Models\SCORM;

use CodeIgniter\Model;

class Scorm_metacategory_model extends Model
{
    function addmetadetails($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_meta_category');
        $builder->insert($newdata);
        $data = $builder->get()->getResultArray();
        // if($data){
        //    $insertID = $db->insertID();
        //    print_r($insertID);
        //    exit();
        // }
        return  $data;
    }
    function getMetadata($type)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_meta_category as mc');
        // $builder->select('mc.*');
        $builder->select('mc.sc_mcid, ANY_VALUE(mc.description) as description, ANY_VALUE(mc.typeofval) as typeofval, ANY_VALUE(mc.status) as status');

       // $builder->join('assign_meta_category as c', 'c.fk_sc_mcid = mc.sc_mcid', 'left');
        $builder->groupBy('mc.sc_mcid');
        $builder->where('mc.typeofval', $type);
        $builder->where('mc.status =', '1');
      //  $builder->where('c.status !=', '0');
        $data = $builder->get()->getResultArray();
        // print_r($data);
        return $data;
    }
    function getmetaDetails($sc_mcid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_meta_category as mc');
        $builder->select('mc.*');
        $builder->where('mc.sc_mcid', $sc_mcid);
        $builder->where('mc.status =', '1');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getCategorydata()
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_meta_category as mc');
         $builder->select('ANY_VALUE(mc.sc_mcid) as sc_mcid, ANY_VALUE(mc.description) as description, ANY_VALUE(mc.typeofval) as typeofval, ANY_VALUE(mc.status) as status,count(c.fk_sc_mcid) as catcount');
        $builder->join('assign_meta_category as c', 'c.fk_sc_mcid = mc.sc_mcid AND c.status=1', 'left');
        $builder->where('mc.typeofval', 2);
        $builder->where('mc.status =', '1');
        $builder->groupBy('c.fk_sc_mcid');
        $builder->orderBy('description');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getAllCoursesByCategory($sc_mcid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('assign_meta_category as mc');
        $builder->select('c.course_name as course_name, c.scourse_id as scourse_id, c.language as language, c.duration as duration');
        $builder->join('scorm_courses as c', 'c.scourse_id = mc.fk_scourse_id', 'left');
        $builder->where('mc.fk_sc_mcid =', $sc_mcid);
        $builder->where('c.status !=', 0);
        $builder->where('mc.status !=', 0);
        $builder->orderBy('c.language');
        //  $builder->groupBy('c.fk_sc_mcid');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function editmetacatdetails($newdata, $sc_mcid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_meta_category');
        $builder->where('sc_mcid', $sc_mcid);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray($newdata);
        return  $data;
    }
    public function addclienttocategory($newdata)
    {
        $builder = $this->db->table('assign_client_to_category');
        $builder->insert($newdata);
        $data = $builder->get()->getResultArray($newdata);
        return  $data;
    }
    public function getClientsofcategory($category_id)
    {
        $builder = $this->db->table('assign_client_to_category as ac');
        $builder->select('ac.*,c.client_name');
        $builder->join('client as c', 'c.id_c = ac.client', 'left');
        $builder->where('category_id', $category_id);
        $builder->where('ac.status =1');
        $data = $builder->get()->getResultArray();
        return  $data;
    }
}
