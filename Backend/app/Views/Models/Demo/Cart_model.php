<?php

namespace App\Models\Demo;

use CodeIgniter\Model;

class Cart_model extends Model
{
    function getAddedCourses($user)
    {
        $builder = $this->db->table('cart as c');
        $builder->select('c.id as cartid,sc.*');
        $builder->join('scorm_courses as sc', 'sc.scourse_id  = c.course_id', 'left');
        $builder->where('c.createdby', $user);
        $builder->where('c.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getReportData($user)
    {
        $todaydate = date('Y-m-d');
        $builder = $this->db->table('cart_assigned as ca');
        $builder->select('ca.*');
        $builder->where('ca.createdby', $user);
        $builder->where('expiry_date >=', $todaydate);
        $builder->where('ca.status', 1);
        $builder->orderBy("id", "desc");
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function  getAssignedCourses($cartAssignid)
    {
        $builder = $this->db->table('cart as c');
        $builder->select('c.id as cartid,sc.*');
        $builder->join('scorm_courses as sc', 'sc.scourse_id  = c.course_id', 'left');
        $builder->where('c.assign_id', $cartAssignid);
        $builder->where('c.status', 2);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function checkValid($cart_access_id)
    {
        $builder = $this->db->table('cart_assigned as c');
        $builder->select('c.*');
        $builder->where('c.id', $cart_access_id);
        $builder->where('c.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getUserDetails($cartAssignid)
    {
        $builder = $this->db->table('cart_assigned as ca');
        $builder->select('ca.*');
        $builder->where('ca.id', $cartAssignid);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function doesCourseAlreadyAssigned($scourse_id, $user)
    {
        $builder = $this->db->table('cart as c');
        $builder->select('c.*');
        $builder->where('c.course_id', $scourse_id);
        $builder->where('c.createdby', $user);
        $builder->where('c.status', 1);
        $data = $builder->get()->getResultArray();
        return count($data);
    }

    function insertRecordsCourses($courseAssignment)
    {
        $builder = $this->db->table('scorm_users_courses_assigned');
        $builder->insert($courseAssignment);
        $insertID = $this->db->insertID();
        return  $insertID;
    }
    function assignCartItems($addRecord)
    {
        $builder = $this->db->table('cart_assigned');
        $builder->insert($addRecord);
        $insertID = $this->db->insertID();
        //   $insertID = $this->getInsertID();
        return  $insertID;
    }


    function addCourseToCart($newdata)
    {
        $builder = $this->db->table('cart');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        return  true;
    }
    function addmultiCourseToCart($newdata, $username)
    {
        foreach ($newdata['course_id'] as $course_id) {
            $addtocart = [
                'course_id' => $course_id,
                'status' => $newdata['status'],
                'assign_id' => $newdata['assign_id'],
                'createdby' => $newdata['createdby'],
                'createdon' => time(),
                'last_updated_by' =>  session()->get('id_user'),
                'last_updated_on' => time()
            ];
            $builder = $this->db->table('cart');
            $builder->insert($addtocart);
            $builder = $this->db->table('users as u');
            $builder->select('u.id_user');
            $builder->where('u.username', $username);
            $builder->where('u.valid', 1);
            $data = $builder->get()->getResultArray();

            $assigndata = [
                'id_user' => $data[0]['id_user'],
                'course_id' => $course_id,
                'status' => 1,
                'createdby' => $newdata['createdby'],
                'createdon' => time(),
                'last_updated_by' =>  session()->get('id_user'),
                'last_updated_on' => time()
            ];
            $data = $builder->get()->getResultArray();
            $builder = $this->db->table('scorm_users_courses_assigned as s');
            $builder->insert($assigndata);
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
    function delCartItem($delData, $cartid)
    {
        $builder = $this->db->table('cart as dlt');
        $builder->where('dlt.id', $cartid);
        $builder->update($delData);
        $data = $builder->get()->getResultArray();
        return true;
    }

    function updateCartDetails($updateRecord, $assignID)
    {
        $builder = $this->db->table('cart_assigned as dlt');
        $builder->where('dlt.id', $assignID);
        $builder->update($updateRecord);
        $data = $builder->get()->getResultArray();
        return true;
    }

    function updateCartAssignment($cartRecord, $user)
    {
        $builder = $this->db->table('cart as dlt');
        $builder->where('dlt.createdby', $user);
        $builder->where('dlt.status', 1);
        $builder->where('dlt.assign_id', 0);
        $builder->update($cartRecord);
        $data = $builder->get()->getResultArray();
        return true;
    }
    function unassignUserfromCourse($username, $course_id)
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.id_user');
        $builder->where('u.username', $username);
        $builder->where('u.valid', 1);
        $data = $builder->get()->getResultArray();
        if ($data) {
            $id_user =  $data[0]['id_user'];
            $builder = $this->db->table('scorm_users_courses_assigned as s');
            $builder->set('s.status', 0);
            $builder->where('s.id_user', $id_user);
            $builder->where('s.course_id', $course_id);
            $builder->update();
            $data = $builder->get()->getResultArray();
            return $data;
        } else {
            return false;
        }
    }
}
