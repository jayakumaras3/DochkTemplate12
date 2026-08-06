<?php

namespace App\Models\Category;

use CodeIgniter\Model;

class Category_Dashboard_model extends Model
{

    function get_meta()
    {
        $builder = $this->db->table('scorm_meta_category as meta');
        $builder->select('meta.description, meta.sc_mcid,
        (SELECT COUNT(cat.sc_mcid) FROM scorm_meta_category as cat WHERE cat.meta_category = meta.sc_mcid AND cat.status != 0) as total_categories');
        $builder->where('meta.status !=', 0);
        $builder->where('meta.typeofval', 5);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function add_new_meta_category_m($data)
    {
        $builder = $this->db->table('scorm_meta_category');
        $builder->insert($data);
        return $this->db->insertID();
    }

    function delete_meta_category_m($id, $data)
    {
        $builder = $this->db->table('scorm_meta_category');
        $builder->where('sc_mcid', $id);
        $builder->update($data);
        return true;
    }

    function get_category_m($mc_id)
    {
        $builder = $this->db->table('scorm_meta_category as cat');
        $builder->select('cat.description, cat.sc_mcid,
        (SELECT COUNT(asn.mc_id) FROM assign_meta_category as asn WHERE asn.fk_sc_mcid = cat.sc_mcid AND asn.status != 0) as total_courses');
        $builder->where('cat.status !=', 0);
        $builder->where('cat.typeofval', 2);
        $builder->where('cat.meta_category', $mc_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function add_new_category_m($data)
    {
        $builder = $this->db->table('scorm_meta_category');
        $builder->insert($data);
        return $this->db->insertID();
    }

    function delete_category_m($id, $data)
    {
        $builder = $this->db->table('scorm_meta_category');
        $builder->where('sc_mcid', $id);
        $builder->update($data);
        return true;
    }

    function get_cat_courses_m($cat_id)
    {
        $builder = $this->db->table('assign_meta_category as asn');
        $builder->select('asn.mc_id, crs.course_name, crs.language, crs.duration, crs.scourse_id, crs.course_code');
        $builder->join('scorm_courses as crs', 'crs.scourse_id = asn.fk_scourse_id AND crs.status != 0', 'left');
        $builder->where('asn.status !=', 0);
        $builder->where('asn.fk_sc_mcid', $cat_id);
        //$builder->orderBy('crs.course_name', 'DESC');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_all_courses()
    {
        $builder = $this->db->table('scorm_courses');
        $builder->select('scourse_id, course_name, course_code');
        $builder->where('status !=', 0);
        $builder->orderBy('course_name', 'DESC');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function add_course_to_category_m($newdata)
    {
        // $builder = $this->db->table('assign_meta_category');
        // $builder->insert($data);
        // return $this->db->insertID();
        foreach ($newdata['fk_scourse_id'] as $course_id) {
            $assign_meta_category = [
                'fk_sc_mcid' => $newdata['fk_sc_mcid'],
                'fk_scourse_id' => $course_id,
                'status' => 1,
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
            ];
            $builder = $this->db->table('assign_meta_category');
            $builder->insert($assign_meta_category);
            $data = $builder->get()->getResultArray();
            if (!empty($data)) {
                $data['status'] = "OK";
            } else {
                $data['status'] = "Error";
            }
        }
        return $data;
    }

    function unlink_course_m($cat_id, $data)
    {
        $builder = $this->db->table('assign_meta_category');
        $builder->where('mc_id', $cat_id);
        $builder->update($data);
        return true;
    }
}
