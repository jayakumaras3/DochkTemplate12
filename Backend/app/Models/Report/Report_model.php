<?php

namespace App\Models\Report;

use CodeIgniter\Model;

class Report_model extends Model
{

    function getStatusCountCourseUsers($id_user)
    {

        $builder = $this->db->table('scorm_users_courses_assigned as sca')
            ->select('MAX(sca.course_status) as course_status,count(sca.user_assign_id ) as count_course_status')
            ->where('sca.id_user', $id_user)
            ->where('sca.status', 1)
            ->groupBy('course_status');
        $data = $builder->get()->getResultArray();
        // echo $this->db->getlastQuery();
        // exit();
        return $data;
    }

    function update_client_id($clientid)
    {
        // $builder = $this->db->table('scorm_users_courses_assigned as table1');
        // $builder->select('table1.user_assign_id');
        // $builder->join('users as table2', 'table2.id_user = table1.id_user AND table2.client_id = ' . $clientid, 'left');
        // $builder->where('table1.client_id', 0);
        // $builder->update(['table1.client_id' => $clientid]);
        // return true;
        $builder = $this->db->table('scorm_users_courses_assigned');

        $builder->set('client_id', $clientid);
        $builder->where('client_id', 0);
        $builder->where("id_user IN (SELECT id_user FROM users WHERE client_id = {$clientid})", null, false);
        // echo $this->db->getLastQuery();
        // exit();
        $builder->update();
    }

    function total_users($clientid)
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.id_user, u.name, u.last_name');
        $builder->where('u.client_id', $clientid);
        $builder->where('u.valid !=', 0);
        $builder->orderBy('u.name', 'ASC');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function client_courses($client)
    {
        $id_user = session()->get('id_user');
        $builder = $this->db->table('scorm_courses_assigned as sc');
        $builder->select('c.course_name as course_name,c.scourse_id as scourse_id');
        $builder->join('scorm_users_courses_assigned as sca', 'sca.course_id = sc.course_id and  sca.id_user =' . $id_user . ' and sca.status =1 and sca.type = 0', 'left');
        $builder->join('scorm_courses as c', 'c.scourse_id = sc.course_id', 'left');
        $builder->join('scorm_user_details as su', 'su.user_assign_id = sca.user_assign_id and su.status =1', 'left');
        $builder->join('users as u', 'u.id_user = c.createdby', 'left');
        $builder->where('sc.client_id', $client);
        $builder->where('sc.status', 1);
        $builder->where('c.status', '1');
        $builder->orderby('c.course_name', 'ASC');
        $builder->groupBy('sc.course_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function get_assigned_courses($clientid)
    {
        $builder = $this->db->table('scorm_users_courses_assigned as sca');
        $builder->select('sca.user_assign_id');
        $builder->where('sca.client_id', $clientid);

        $builder->where('sca.status', 1);
        $counter = $builder->countAllResults();
        return $counter;
    }
    // public function course_assigned_report($clientid, $type, $reviewd = null)
    // {
    //     $builder = $this->db->table('scorm_users_courses_assigned as sca');
    //     $builder->select('c.course_name as course_name, u.name, u.last_name, sca.course_status, sca.createdby, sca.createdon, sca.last_updated_on, uc.name as createdbyadmin,sca.client_id ');
    //     $builder->join('scorm_courses as c', 'c.scourse_id = sca.course_id', 'left');
    //     $builder->join('users as u', 'u.id_user = sca.id_user', 'left');
    //     $builder->join('users as uc', 'uc.id_user = sca.createdby', 'left');


    //     if ($reviewd == 3) {
    //         $builder->groupStart()
    //             ->where('sca.course_status', $type)
    //             ->orwhere('sca.course_status', $reviewd)
    //             ->groupEnd();
    //     } else {
    //         $builder->where('sca.course_status', $type);
    //     }


    //     $builder->where('sca.client_id', $clientid);
    //     $builder->where('sca.status !=', 0);

    //     $builder->groupBy('sca.id_user');
    //     $builder->groupBy('sca.course_id');

    //     $builder->orderby('sca.createdon', 'DESC');
    //     $data = $builder->get()->getResultArray();
    //     echo $this->db->getLastQuery();
    //     print_r($data);
    //     exit();
    //     return $data;
    // }
    public function course_assigned_report($clientid, $type, $reviewd = null)
    {
        $builder = $this->db->table('scorm_users_courses_assigned as sca');

        $builder->select('
        c.course_name,
        u.name,
        u.last_name,
        sca.course_status,
        sca.createdby,
        sca.createdon,
        sca.last_updated_on,
        uc.name as createdbyadmin,
        sca.client_id
    ');

        $builder->join('scorm_courses as c', 'c.scourse_id = sca.course_id', 'left');
        $builder->join('users as u', 'u.id_user = sca.id_user', 'left');
        $builder->join('users as uc', 'uc.id_user = sca.createdby', 'left');

        if ($type === null) {
            // No status filter: show every assigned course regardless of status.
        } elseif ($reviewd == 3) {
            $builder->groupStart()
                ->where('sca.course_status', $type)
                ->orWhere('sca.course_status', $reviewd)
                ->groupEnd();
        } else {
            $builder->where('sca.course_status', $type);
        }

        $builder->where('sca.client_id', $clientid);

        $builder->groupStart()
            ->where('sca.status !=', 0)
            ->orWhere('sca.status IS NULL')
            ->groupEnd();

        // $builder->groupBy('sca.id_user');
        // $builder->groupBy('sca.course_id');

        $builder->orderBy('sca.createdon', 'DESC');

        $data = $builder->get()->getResultArray();

        // echo $this->db->getLastQuery();
        // print_r($data);
        // exit();
        return $data;
    }

    public function data_by_status_values($clientid,  $status)
    {
        $builder = $this->db->table('scorm_user_details as sud');
        $builder->select('sud.raw, sud.attempt, sud.total_time, sud.last_updated_on, u.name, u.last_name, c.course_name');
        $builder->join('users as u', 'u.id_user = sud.student_id', 'left');
        $builder->join('scorm_courses as c', 'c.scourse_id = sud.course_id', 'left');
        $builder->where('sud.client_id', $clientid);
        $builder->where('sud.status !=', 0);
        if ($status == 'Completed') {
            $builder->groupStart()
                ->where('sud.lesson_status', 'passed')
                ->orWhere('sud.lesson_status', 'completed')
                ->orWhere('sud.lesson_status', 'Completed')
                ->groupEnd();
        } else {
            $builder->where('sud.lesson_status', $status);
        }
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function course_status_values($clientid, $status, $reviewd)
    {
        $builder = $this->db->table('scorm_users_courses_assigned as sud');
        $builder->select('MAX(sud.user_assign_id)  as sc_uid');

        if ($reviewd == 3) {
            $builder->groupStart()
                ->where('sud.course_status', $status)
                ->orwhere('sud.course_status', $reviewd)
                ->groupEnd();
        } else {
            $builder->where('sud.course_status', $status);
        }
        $builder->where('sud.client_id', $clientid);
        $builder->where('sud.status !=', 0);

        $builder->groupBy('sud.id_user');
        $builder->groupBy('sud.course_id');


        $counter = $builder->countAllResults();
        return $counter;
    }

    public function course_status_values_graph($clientid, $status, $startdate, $enddate)
    {
        $builder = $this->db->table('scorm_users_courses_assigned as sud');
        $builder->select('MAX(sud.user_assign_id)  as sc_uid');

        if ($status != 0) {
            $builder->where("FROM_UNIXTIME(sud.last_updated_on, '%Y-%m-%d') >=", $startdate);
            $builder->where("FROM_UNIXTIME(sud.last_updated_on, '%Y-%m-%d') <=", $enddate);
        } else {
            $builder->where("FROM_UNIXTIME(sud.createdon, '%Y-%m-%d') >=", $startdate);
            $builder->where("FROM_UNIXTIME(sud.createdon, '%Y-%m-%d') <=", $enddate);
        }

        $builder->where('sud.course_status', $status);
        $builder->where('sud.client_id', $clientid);
        $builder->where('sud.status !=', 0);
        $builder->groupBy('sud.id_user');
        $builder->groupBy('sud.course_id');
        $counter = $builder->countAllResults();
        return $counter;
    }

    function checkrowexists($clientid, $type, $year)
    {
        $builder = $this->db->table('report_graph as row');
        $builder->select('row.*');
        $builder->where('row.client_id', $clientid);
        $builder->where('row.year', $year);
        $builder->where('row.type', $type);
        $builder->where('row.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function get_monthly_reports($client_id, $startdate, $enddate)
    {
        $builder = $this->db->table('scorm_user_details as suc');
        $builder->select('c.course_name as course_name,c.scourse_id as scourse_id, u.name, u.last_name, suc.lesson_status, suc.last_updated_on, suc.attempt, suc.raw as score,suc.total_time');
        $builder->join('scorm_users_courses_assigned as su', 'su.user_assign_id  = suc.user_assign_id ', 'left');
        $builder->join('scorm_courses as c', 'c.scourse_id = su.course_id', 'left');
        $builder->join('users as u', 'u.id_user = su.id_user', 'left');
        $builder->where("FROM_UNIXTIME(suc.last_updated_on, '%Y-%m-%d') >=", $startdate);
        $builder->where("FROM_UNIXTIME(suc.last_updated_on, '%Y-%m-%d') <", $enddate);
        $builder->where('suc.client_id', $client_id);
        $builder->where('suc.status', 1);
        $data = $builder->get()->getResultArray();
        //  echo $this->db->getLastQuery();
        // print_r($data);
        // exit();
        //
        return $data;
    }

    public function update_graph_data_value($rg_id, $update_data)
    {
        $builder = $this->db->table('report_graph as rg');
        $builder->where('rg.rg_id', $rg_id);
        $builder->update($update_data);
        return true;
    }

    public function insert_graph_data_value($insert_data)
    {
        $builder = $this->db->table('report_graph');
        $builder->insert($insert_data);
        return true;
    }


    public function reportUsergraphCompeted($id_user)
    {
        // $id_user = session()->get('id_user');
        $date = date('Y');
        $builder = $this->db->table('report_user_graph_completed as r');
        $builder->select('r.*');
        $builder->where('r.id_user', $id_user);
        $builder->where('r.year', $date);
        $builder->where('r.status', 1);
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    function getReportUsergraphCompeted($id_user)
    {
        $completed = ['completed', 'passed'];
        $builder = $this->db->table('scorm_users_courses_assigned as sud');
        $builder->select('sud.id_user as id_user,DATE_FORMAT(FROM_UNIXTIME(ANY_VALUE(sud.last_updated_on)), "%m") as month,DATE_FORMAT(FROM_UNIXTIME(ANY_VALUE(sud.last_updated_on)), "%Y") as year,COUNT(sud.id_user) as complete_course_count', false);
        // $builder->where('sca.id_user', $id_user);

        $builder->join('scorm_courses as sc', 'sc.scourse_id = sud.course_id and sc.status = 1', 'left');
        $builder->where('sud.course_status', 2);
        $builder->groupBy('sud.id_user');
        $builder->groupBy('DATE_FORMAT(FROM_UNIXTIME(sud.last_updated_on), "%Y-%m")'); // Group by year-month
        $builder->orderBy('DATE_FORMAT(FROM_UNIXTIME(sud.last_updated_on), "%Y-%m")'); // Order by year-month
        $data = $builder->get()->getResultArray();

        //Uncomment the following lines for debugging
        // echo $this->db->getLastQuery();
        // exit();

        if ($data) {
            $builder = $this->db->table('report_user_graph_completed');
            $query = $builder->truncate();

            if (!$query) {
                $this->db->query('TRUNCATE TABLE report_user_graph_completed');
            }
            foreach ($data as $eachdata) {
                $newdata = [
                    'id_user' => $eachdata['id_user'],
                    'month' => isset($eachdata['month']) ? $eachdata['month'] : '0',
                    'year' => isset($eachdata['year']) ? $eachdata['year'] : '0',
                    'complete_course_count' => $eachdata['complete_course_count'],
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    'status' => 1
                ];
                $builder = $this->db->table('report_user_graph_completed');
                $builder->insert($newdata);
            }
            return $data;
        }
    }
}
