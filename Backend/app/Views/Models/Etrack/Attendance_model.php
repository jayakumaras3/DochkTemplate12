<?php

namespace App\Models\eTrack;

use CodeIgniter\Model;

class Attendance_model extends Model
{

    function add_wfh_data($newdata)
    {
        $builder = $this->db->table('et_workfromhome');
        $builder->insert($newdata);
        return true;
    }

    function getattendancebydate($user, $today)
    {
        $builder = $this->db->table('et_workfromhome as wfh');
        $builder->select('SUM(wfh.number_wfh) AS number_wfh');
        $builder->where('wfh.emp_id', $user);
        $builder->where('wfh.start_date', $today);
        $builder->where('wfh.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getworkfromhomemonth($user, $start_date, $end_date)
    {

        $builder = $this->db->table('et_workfromhome as wfh');
        $builder->select('wfh.start_date,  SUM(wfh.number_wfh) as number_wfh');
        $builder->groupBy('wfh.start_date');
        $builder->where('wfh.emp_id', $user);
        $builder->where('wfh.start_date >=', $start_date);
        $builder->where('wfh.start_date <=', $end_date);
        $builder->where('wfh.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getleaveformonth($user, $start_date, $end_date)
    {
        $builder = $this->db->table('et_leave as etl');
        $builder->select('etl.start_dt, SUM(etl.number_leave) as number_leave');
        $builder->groupBy('etl.start_dt');
        $builder->where('etl.emp_id', $user);
        $builder->where('etl.number_leave <', 0);
        $builder->where('etl.start_dt >=', $start_date);
        $builder->where('etl.start_dt <=', $end_date);
        $builder->where('etl.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getAccessCardData($id_user, $start_date, $end_date)
    {
        $builder = $this->db->table('et_access_card_data as ac');
        $builder->select('ac.*');
        $builder->where('ac.user_id', $id_user);
        $builder->where('ac.start_date >=', $start_date);
        $builder->where('ac.start_date <=', $end_date);
        $builder->orderBy('ac.start_date', 'ASC');
        $builder->where('ac.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getAccessCardDataLite($id_user, $start_date, $end_date)
    {
        $builder = $this->db->table('et_access_card_data as ac');
        $builder->select('ac.start_date, ac.actualhr, ac.attendance_type');
        $builder->where('ac.user_id', $id_user);
        $builder->where('ac.start_date >=', $start_date);
        $builder->where('ac.start_date <=', $end_date);
        $builder->where('ac.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getholidays($start_date, $end_date, $country)
    {

        $builder = $this->db->table('holiday_list as holidays');
        $builder->select('holidays.holiday_dt');
        $builder->where('holidays.status', 1);
        $builder->where('holidays.country', $country);
        $builder->where('holidays.type', 1);
        $builder->where('holidays.holiday_dt >=', $start_date);
        $builder->where('holidays.holiday_dt <=', $end_date);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getwfhstatement($user, $start_date, $end_date)
    {
        $builder = $this->db->table('et_workfromhome as wfh');
        $builder->select('wfh.*');
        $builder->where('wfh.emp_id', $user);
        $builder->where('wfh.start_date >=', $start_date);
        $builder->where('wfh.start_date <=', $end_date);
        $builder->orderBy('wfh.start_date', 'ASC');
        $builder->where('wfh.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function delete_remarks_from_accesscard($newdata, $access_id)
    {
        $builder = $this->db->table('et_access_card_data as ac');
        $builder->where('ac.access_id', $access_id);
        $builder->update($newdata);
        return true;
    }

    function delete_remarks_from_wfh($newdata, $et_wfh_id)
    {
        $builder = $this->db->table('et_workfromhome as wfg');
        $builder->where('wfg.et_wfh_id', $et_wfh_id);
        $builder->update($newdata);
        return true;
    }

    function teamAttendance($id_user, $start_date, $end_date)
    {

        $builder = $this->db->table('users as u');
        $builder->select('u.emp_id,u.id_user, u.name,u.last_name,SUM(CASE WHEN (wfh.number_wfh > 0) THEN wfh.number_wfh ELSE 0  END) AS wfh, GROUP_CONCAT(if (wfh.remarks ="" || wfh.remarks ="-WFH-" || wfh.remarks ="WFH", null, wfh.remarks) SEPARATOR ",") as rema, manager.name as manager_name');

        $builder->join('users as man', 'man.id_user = u.id_user AND u.valid = 1', 'left');
        $builder->join('users as manager', 'manager.id_user = man.manager', 'left');
        $builder->join('et_workfromhome as wfh', 'wfh.emp_id = u.id_user AND wfh.status = 1 AND wfh.start_date >="' . $start_date . '" AND wfh.start_date <="' . $end_date . '"', 'left');
        if ($id_user == 1) {
        } elseif ($id_user == 831) {
        } else {
            $builder->where('man.manager', $id_user);
        }
        $builder->where('u.client_id', 1);
        $builder->where('u.valid', 1);
        $builder->orderBy('u.emp_id', 'ASC');
        $builder->groupBy('u.id_user');
        $wfhData = $builder->get()->getResultArray();

        $builder = $this->db->table('users as u');
        $builder->select('u.emp_id,u.id_user, u.name,u.last_name,sum(ac.attendance_type) AS ac_data, sum(ac.actual_time_in_min) AS ac_minx, GROUP_CONCAT(if (ac.remarks ="", null, ac.remarks) SEPARATOR ",") as remax');
        $builder->join('users as man', 'man.id_user = u.id_user AND u.valid = 1', 'left');
        $builder->join('et_access_card_data as ac', 'ac.user_id = u.id_user AND ac.status = 1  AND ac.start_date >="' . $start_date . '" AND ac.start_date <="' . $end_date . '"', 'left');
        if ($id_user == 1) {
        } elseif ($id_user == 831) {
        } else {
            $builder->where('man.manager', $id_user);
        }
        $builder->where('u.client_id', 1);
        $builder->where('u.valid', 1);
        $builder->orderBy('u.emp_id', 'ASC');
        $builder->groupBy('u.id_user');
        $accessData = $builder->get()->getResultArray();


        $builder = $this->db->table('users as u');
        $builder->select('u.emp_id,u.id_user, u.name,u.last_name,SUM(CASE WHEN (lev.number_leave < 0) THEN lev.number_leave ELSE 0  END) AS leaves');
        $builder->join('users as man', 'man.id_user = u.id_user AND u.valid = 1', 'left');
        $builder->join('et_leave as lev', 'lev.emp_id = u.id_user AND lev.status = 1 AND lev.start_dt >="' . $start_date . '" AND lev.start_dt <="' . $end_date . '" AND lev.type !="8"', 'left');
        if ($id_user == 1) {
        } elseif ($id_user == 831) {

        } else {
            $builder->where('man.manager', $id_user);
        }
        $builder->where('u.client_id', 1);
        $builder->where('u.valid', 1);
        $builder->orderBy('u.emp_id', 'ASC');
        $builder->groupBy('u.id_user');
        $leaveData = $builder->get()->getResultArray();


        $builder = $this->db->table('users as u');
        $builder->select('u.emp_id,u.id_user, u.name,u.last_name,SUM(CASE WHEN (lev.number_leave < 0) THEN lev.number_leave ELSE 0  END) AS leaves');
        $builder->join('users as man', 'man.id_user = u.id_user AND u.valid = 1', 'left');
        $builder->join('et_leave as lev', 'lev.emp_id = u.id_user AND lev.status = 1 AND lev.start_dt >="' . $start_date . '" AND lev.start_dt <="' . $end_date . '" AND lev.type ="8"', 'left');
        if ($id_user == 1) {
        } elseif ($id_user == 831) {

        } else {
            $builder->where('man.manager', $id_user);
        }
        $builder->where('u.client_id', 1);
        $builder->where('u.valid', 1);
        $builder->orderBy('u.emp_id', 'ASC');
        $builder->groupBy('u.id_user');
        $leaveData_lwp = $builder->get()->getResultArray();


        // echo $this->db->getLastQuery();
        // exit();

        //grace data
        $builder = $this->db->table('users as u');
        $builder->select('u.emp_id,u.id_user, u.name,u.last_name,SUM(etg.numgrace) AS numgrace');
        $builder->join('users as man', 'man.id_user = u.id_user AND u.valid = 1', 'left');
        $builder->join('et_grace as etg', 'etg.user_id = u.id_user AND etg.hr_status = 1 AND etg.bz_status = 1 AND etg.date >="' . $start_date . '" AND etg.date <="' . $end_date . '"', 'left');
        if ($id_user == 1) {
        } elseif ($id_user == 831) {

        } else {
            $builder->where('man.manager', $id_user);
        }
        $builder->where('u.client_id', 1);
        $builder->where('u.valid', 1);
        $builder->orderBy('u.emp_id', 'ASC');
        $builder->groupBy('u.id_user');
        $gracedata = $builder->get()->getResultArray();



        $combinedData = ['wfhData' => $wfhData, 'accessData' => $accessData, 'leaveData' => $leaveData, 'gracedata' => $gracedata, 'leaveData_lwp' => $leaveData_lwp];
        // print_r($combinedData);
        // exit();

        return $combinedData;



        // $builder = $this->db->table('users as u');
        // $builder->select('u.emp_id,u.id_user, u.name,u.last_name,u.id_user,
        //         SUM(CASE WHEN (lev.number_leave > 0) THEN lev.number_leave ELSE 0  END) AS leaves,
        //          SUM(CASE WHEN (wfh.number_wfh > 0) THEN wfh.number_wfh ELSE 0  END) AS wfh,
        //          count(CASE WHEN (ac.access_id > 0) THEN ac.access_id ELSE 0  END) AS ac_data
        //     ');
        // $builder->join('et_workfromhome as wfh', 'wfh.emp_id = u.id_user AND wfh.status = 1', 'left');
        // $builder->join('et_access_card_data as ac', 'ac.user_id = u.id_user AND ac.status = 1', 'left');
        // $builder->join('et_leave as lev', 'lev.emp_id = u.id_user AND lev.status = 1', 'left');
        // $builder->join('users as man', 'man.id_user = lev.emp_id AND u.valid = 1', 'left');
        // $builder->where('lev.status', '1');
        // $builder->where('man.valid', '1');
        // $builder->where('man.manager', $id_user);
        // $builder->groupBy('wfh.emp_id, u.name');
        // $builder->where('lev.start_dt >=', $start_date);
        // $builder->where('lev.start_dt <=', $end_date);
        // $data = $builder->get()->getResultArray();
        // return $data;

        // $builder = $this->db->table('users as u');
        // $builder->select('u.emp_id,u.id_user, u.name,u.last_name,
        //   SUM(CASE WHEN (lev.number_leave > 0) THEN lev.number_leave ELSE 0  END) AS leaves,
        //   SUM(CASE WHEN (wfh.number_wfh > 0) THEN wfh.number_wfh ELSE 0  END) AS wfh,
        //   count(CASE WHEN (ac.access_id > 0) THEN ac.access_id ELSE 0  END) AS ac_data');
        // $builder->join('users as man', 'man.id_user = u.id_user AND u.valid = 1', 'left');
        // $builder->join('et_leave as lev', 'lev.emp_id = u.id_user AND lev.status = 1 AND lev.start_dt >="' . $start_date . '" AND lev.start_dt <="' . $end_date . '"', 'left');
        // $builder->join('et_workfromhome as wfh', 'wfh.emp_id = u.id_user AND wfh.status = 1 AND wfh.start_date >="' . $start_date . '" AND wfh.start_date <="' . $end_date . '"', 'left');
        // $builder->join('et_access_card_data as ac', 'ac.user_id = u.id_user AND ac.status = 1  AND ac.start_date >="' . $start_date . '" AND ac.start_date <="' . $end_date . '"', 'left');
        // $builder->where('man.manager', $id_user);
        // $builder->groupBy('u.id_user');
        // $data = $builder->get()->getResultArray();
    }
    // public function getattendanceMonthlyReport($month, $year, $user)
    // {

    //     $builder = $this->db->table('users as u');
    //     $builder->select('u.emp_id,CONCAT(u.name, " ", u.last_name) AS fullname');
    //     // $builder->join('et_leave as lev', 'lev.emp_id = u.id_user AND lev.status = 1 AND MONTH(lev.start_dt)="' . $month . '" AND YEAR(lev.start_dt) ="' . $year . '"', 'left');
    //     // $builder->join('et_workfromhome as wfh', 'wfh.emp_id = u.id_user AND wfh.status = 1 AND MONTH(wfh.start_date) ="' . $month . '" AND YEAR(wfh.start_date) ="' . $year . '"', 'left');
    //     // $builder->join('et_access_card_data as ac', 'ac.user_id = u.id_user AND ac.status = 1  AND MONTH(ac.start_date)="' . $month . '" AND YEAR(ac.start_date) ="' . $year . '"', 'left');


    //     if ($user != '1' && $user != '834') {
    //         $builder->where('u.manager', $user);
    //     }
    //     $builder->where('u.valid', 1);
    //     $builder->where('u.client_id', 1);
    //     $data = $builder->get()->getResultArray();
    //     return $data;
    // }
    public function getattendanceMonthlyReport($startDate, $endDate, $userId = null)
    {
        $db = \Config\Database::connect();

        $leaveQuery = $db->table('et_leave')
            ->select("emp_id, start_dt as date, (-1 * number_leave) as number_leave,type,'Leaves' as attendance_type")
            ->where('status', 1)
            ->where('number_leave <', 0)
           // ->where('type !=', 8)
            ->where('start_dt >=', $startDate)
            ->where('start_dt <=', $endDate);
            // ->groupBy('emp_id, start_dt');

        //  WFH
        $wfhQuery = $db->table('et_workfromhome')
            ->select("emp_id, start_date as date,number_wfh,'','WFH' as attendance_type")
            ->where('status', 1)
            ->where('start_date >=', $startDate)
            ->where('start_date <=', $endDate);

        //  In Office
        $officeQuery = $db->table('et_access_card_data')
            ->select("user_id as emp_id, start_date as date,actualhr,'','In Office' as attendance_type")
            ->where('status', 1)
            ->where('start_date >=', $startDate)
            ->where('start_date <=', $endDate);

        if ($userId !== '1' && $userId !== '871' && $userId !== '834') {
            // First get managed users
            $builder = $db->table('users')
                ->select('id_user, emp_id as emp_no, CONCAT(name, " ", last_name) as fullname,engage_type')
                ->where('manager', $userId)
                ->where('emp_id !=', 10111)
                ->where('emp_id !=', 10077)
                ->where('valid', 1)
                ->where('client_id', 1)
                ->orderBy('name', 'Asc');

            $userList = $builder->get()->getResultArray();
            $userIds = array_column($userList, 'id_user');

            if (empty($userIds)) {
                return []; // no data to return
            }

            $leaveQuery->whereIn('emp_id', $userIds);
            $wfhQuery->whereIn('emp_id', $userIds);
            $officeQuery->whereIn('user_id', $userIds);

            $employeeInfo = [];
            foreach ($userList as $u) {
                $employeeInfo[$u['id_user']] = [
                    'fullname' => $u['fullname'],
                    'emp_no' => $u['emp_no'],
                    'engage_type' => $u['engage_type']
                ];
            }
        } else {
            // Get all active users
            $userList = $db->table('users')
                ->select('id_user, emp_id as emp_no, CONCAT(name, " ", last_name) as fullname,engage_type')
                ->where('valid', 1)
                ->where('emp_id !=', 10111)
                ->where('emp_id !=', 10077)
                ->where('client_id', 1)
                ->orderBy('name', 'Asc')
                ->get()->getResultArray();

            $employeeInfo = [];
            foreach ($userList as $u) {
                $employeeInfo[$u['id_user']] = [
                    'fullname' => $u['fullname'],
                    'emp_no' => $u['emp_no'],
                    'engage_type' => $u['engage_type']
                ];
            }
        }

        $sql = "({$leaveQuery->getCompiledSelect()})
            UNION
            ({$wfhQuery->getCompiledSelect()})
            UNION
            ({$officeQuery->getCompiledSelect()})";

        $results = $db->query($sql)->getResultArray();

        foreach ($results as &$r) {
            $r['fullname'] = $employeeInfo[$r['emp_id']]['fullname'] ?? 'Unknown';
            $r['emp_no'] = $employeeInfo[$r['emp_id']]['emp_no'] ?? 'NA';
            $r['engage_type'] = $employeeInfo[$r['emp_id']]['engage_type'] ?? '0';
        }

        return $results;
    }
}
