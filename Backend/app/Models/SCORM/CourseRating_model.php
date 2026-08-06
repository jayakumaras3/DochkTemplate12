<?php

namespace App\Models\SCORM;

use CodeIgniter\Model;


class CourseRating_model extends Model
{
    protected $table = 'course_ratings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['course_id', 'user_id', 'rating', 'comment', 'status'];

    function getratingCourseofuser($id_user, $courseid)
    {
        $builder = $this->db->table('course_ratings as cr');
        $builder->select('cr.*');
        $builder->where('cr.user_id', $id_user);
        $builder->where('cr.course_id', $courseid);
        // $builder->where('c.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function avrratingofCourse($courseid)
    {
        $builder = $this->db->table('scorm_courses as cr');
        $builder->select('cr.scourse_id as courseid, cr.avg_rating as average_rating,cr.user_count_rating as count_user');
        $builder->where('cr.scourse_id', $courseid);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getratingCourse($courseid)
    {
        $builder = $this->db->table('course_ratings as cr');
        $builder->select('cr.course_id, ROUND(AVG(cr.rating), 2) as average_rating,count(cr.user_id) as count_user');
        $builder->where('cr.course_id', $courseid);
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        if ($data) {
            $avg_rating = $data['0']['average_rating'];
            $user_count_rating = $data['0']['count_user'];
            $builder = $this->db->table('scorm_courses as sc');
            $builder->where('scourse_id',$courseid);
            $builder->set('avg_rating', $avg_rating);
            $builder->set('user_count_rating', $user_count_rating);
            $builder->update();
        }
        return $data;
    }
    function getCourseRateData(){
        $builder = $this->db->table('course_ratings as cr');
        $builder->select('sc.course_name,CONCAT(u.name," ",u.last_name) as username,cr.rating,cr.comment,cr.createdon');
        $builder->join('scorm_courses as sc','scourse_id = cr.course_id','left');
        $builder->join('users as u','u.id_user = cr.user_id','left');
        $data = $builder->get()->getResultArray();
        return $data;
    }
}
