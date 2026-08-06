<?php

namespace App\Models\eTrack;

use CodeIgniter\Model;

class Leave_model extends Model
{

    function add_leave_data($newdata)
    {
        $builder = $this->db->table('et_leave');
        $builder->insert($newdata);
        return true;
    }

    function add_grace_data($newdata)
    {
        $builder = $this->db->table('et_grace');
        $builder->insert($newdata);
        return true;
    }
    function update_grace_data($newdata, $grace_id)
    {
        $builder = $this->db->table('et_grace as etg');
        $builder->where('etg.grace_id', $grace_id);
        $builder->update($newdata);
        return true;
    }

    function getUserGender($user)
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.gender');
        $builder->where('u.id_user', $user);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getMensuralLeaves($user, $startofMonth, $endofMonth)
    {
        $builder = $this->db->table('et_leave as lev');
        $builder->select('SUM(lev.number_leave) AS Mensural_Leaves');
        $builder->where('lev.emp_id', $user);
        $builder->where('lev.type', 7);
        $builder->where('lev.start_dt >=', $startofMonth);
        $builder->where('lev.start_dt <=', $endofMonth);
        $builder->where('lev.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getUsersDetails($client)
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.name as name, u.id_user as id_user, u.last_name as last_name,u.email,u.manager');
        $builder->where('u.client_id', $client);
        $builder->orderBy('u.name', 'ASC');
        $builder->where('u.valid', 1);
        $builder->where('u.name !=', 'Demo User');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function in_office_today_details($today)
    {
        $builder = $this->db->table('et_present_today as ep');
        $builder->select('guser.id_user, guser.name, guser.last_name, SUM(ep.num_days) as numday');
        $builder->join('users as guser', 'guser.id_user  = ep.id_user', 'left');
        $builder->where('ep.todaydate', $today);
        $builder->groupBy('guser.id_user');
        $builder->where('ep.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function import_bulk_leave_excel($sheetData, $month, $year)
    {
        $rowid = 0;
        if (strlen($month) == 1) {
            $month = '0' . $month;
        }
        $start_date = $year . '-' . $month . '-01';
        foreach ($sheetData as $Row) {
            if (isset($Row[0]) && isset($Row[1])) {
                if ($rowid == 0) {
                    $rowid = 1;
                    continue;
                }

                $emp_code = $Row[1];
                $builder = $this->db->table('users as u');
                $builder->select('u.id_user');
                $builder->where('u.emp_id', $emp_code);
                $userdata = $builder->get()->getResultArray();
                if ($userdata) {
                    $num_leaves = $Row[2];
                    if ($num_leaves == 0) {
                        continue;
                    }
                    $type_code = $Row[3];
                    $remarks = $Row[4];
                    $userid = $userdata[0]['id_user'];
                    $insertdata = [
                        'emp_id' => $userid,
                        'number_leave' => $num_leaves,
                        'start_dt' => $start_date,
                        'remarks' => $remarks,
                        'type' => $type_code,
                        'status' => 1,
                        'last_updated_by' => session()->get('id_user'),
                        'last_updated_on' => time()
                    ];
                    $builder = $this->db->table('et_leave');
                    $builder->insert($insertdata);
                    $rowid++;
                }
            }
        }
        return $rowid;
    }

    function leave_details($today)
    {
        $builder = $this->db->table('et_leave as el');
        $builder->select('SUM(el.number_leave) as leavex, guser.id_user, guser.name, guser.last_name');
        $builder->join('users as guser', 'guser.id_user  = el.emp_id', 'left');
        $builder->where('el.start_dt', $today);
        $builder->where('el.number_leave <', 0);
        $builder->groupBy('guser.id_user');
        $builder->where('el.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function workfromhome_details($today)
    {
        $builder = $this->db->table('et_workfromhome as wfh');
        $builder->select('guser.name, guser.id_user, guser.last_name, ANY_VALUE(wfh.number_wfh) as number_wfh');
        $builder->join('users as guser', 'guser.id_user  = wfh.emp_id', 'left');
        $builder->where('wfh.start_date', $today);
        $builder->groupBy('guser.id_user');
        $builder->where('wfh.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function in_office_today($today)
    {
        $builder = $this->db->table('et_present_today as ep');
        $builder->select('ANY_VALUE(ep.num_days) as num_days');
        $builder->where('ep.todaydate', $today);
        $builder->groupBy('ep.id_user');
        $builder->where('ep.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;

        //  return $builder->countAllResults();
    }

    function leave_today($today)
    {
        $builder = $this->db->table('et_leave as el');
        $builder->select('ANY_VALUE(el.et_le_id) as et_le_id');
        $builder->where('el.start_dt', $today);
        $builder->groupBy('el.emp_id');
        $builder->where('el.number_leave <', 0);
        $builder->where('el.status', 1);
        return $builder->countAllResults();
    }

    function wfh_today($today)
    {
        $builder = $this->db->table('et_workfromhome as wfh');
        $builder->select('ANY_VALUE(wfh.et_wfh_id) as et_wfh_id');
        $builder->where('wfh.start_date', $today);
        $builder->groupBy('wfh.emp_id');
        $builder->where('wfh.status', 1);
        return $builder->countAllResults();
    }

    function my_in_office_today($today, $user)
    {
        $builder = $this->db->table('et_present_today as ep');
        $builder->select('SUM(ep.num_days) as num_days');
        $builder->where('ep.todaydate', $today);
        $builder->where('ep.id_user', $user);
        $builder->where('ep.status', 1);
        return $builder->get()->getResultArray();
    }

    function my_leave_today($today, $user)
    {
        $builder = $this->db->table('et_leave as el');
        $builder->select('SUM(el.number_leave) as number_leave');
        $builder->where('el.start_dt', $today);
        $builder->where('el.status', 1);
        $builder->where('el.emp_id', $user);

        return $builder->get()->getResultArray();
    }

    function my_wfh_today($today, $user)
    {
        $builder = $this->db->table('et_workfromhome as wfh');
        $builder->select('SUM(wfh.number_wfh) as number_wfh');
        $builder->where('wfh.start_date', $today);
        $builder->where('wfh.emp_id', $user);
        $builder->where('wfh.status', 1);

        return $builder->get()->getResultArray();
    }

    function getallgrace()
    {
        $startyear = date('Y') . '-01-01';
        $endyear = date('Y') . '-12-31';
        $builder = $this->db->table('et_grace as g');
        $builder->select('g.*,guser.name as gname, guser.last_name as glast, u.name as hr_name');
        $builder->join('users as guser', 'guser.id_user  = g.user_id', 'left');
        $builder->join('users as u', 'u.id_user  = g.hr_updated_by', 'left');
        $builder->where('g.bz_status', 0);
        $builder->where('g.date >=', $startyear);
        $builder->where('g.date <=', $endyear);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getgrace($temp_user)
    {
        $startyear = date('Y') . '-01-01';
        $endyear = date('Y') . '-12-31';
        $builder = $this->db->table('et_grace as g');
        $builder->select('g.*, u.name as hr_name, biz.name as biz_name');
        $builder->join('users as u', 'u.id_user  = g.hr_updated_by', 'left');
        $builder->join('users as biz', 'biz.id_user  = g.biz_updated_by', 'left');
        $builder->where('g.user_id', $temp_user);
        $builder->where('g.date >=', $startyear);
        $builder->where('g.date <=', $endyear);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getLeaveDataByUser($type, $user, $start_year, $end_year)
    {
        $builder = $this->db->table('et_leave as lev');
        $builder->select('SUM(lev.number_leave) AS cumulative_leaves');
        $builder->where('lev.emp_id', $user);
        $builder->where('lev.type', $type);
        //   $builder->where('lev.expire_on >=', date('Y-m-d'));
        $builder->where('lev.start_dt >=', $start_year);
        $builder->where('lev.start_dt <=', $end_year);
        $builder->where('lev.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getUserName($user)
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.name, u.last_name, u.id_user');
        $builder->where('u.id_user', $user);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function compoffApproval($user)
    {
        $builder = $this->db->table('et_leave as lev');
        $builder->select('lev.*, u.name as emp_name');
        $builder->join('users as u', 'u.id_user  = lev.emp_id', 'left');
        $builder->where('lev.requested_to', $user);
        $builder->where('lev.type', 6);
        $builder->where('lev.status', 3);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function leave_statement($user, $start_year, $end_year)
    {
        $builder = $this->db->table('et_leave as lev');
        $builder->select('lev.*, u.name as updatedby');
        $builder->join('users as u', 'u.id_user  = lev.last_updated_by', 'left');
        $builder->where('lev.emp_id', $user);
        $builder->where('lev.type !=', 1);
        $builder->where('lev.start_dt >=', $start_year);
        $builder->where('lev.start_dt <=', $end_year);
        // $builder->where('lev.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function update_leave_data($newdata, $leaveid)
    {
        $builder = $this->db->table('et_leave as lev');
        $builder->where('lev.et_le_id', $leaveid);
        $builder->update($newdata);
        return true;
    }
    function getetLeavesData($start_date, $end_date)
    {
        $builder = $this->db->table('et_leave as lev');
        $builder->select('u.id_user, u.gender, ANY_VALUE(lev.et_le_id) as et_le_id, u.emp_id, u.name,u.last_name,
            SUM(CASE WHEN (lev.type = 2 AND lev.number_leave > 0) THEN lev.number_leave ELSE 0  END) AS Earned,
            SUM(CASE WHEN (lev.type = 2 AND lev.number_leave < 0) THEN lev.number_leave ELSE 0 END) AS Earned_N,
            SUM(CASE WHEN (lev.type = 3 AND lev.number_leave > 0) THEN lev.number_leave ELSE 0 END) AS Medical,
            SUM(CASE WHEN (lev.type = 3 AND lev.number_leave < 0) THEN lev.number_leave ELSE 0 END) AS Medical_N,
            SUM(CASE WHEN (lev.type = 4 AND lev.number_leave > 0) THEN lev.number_leave ELSE 0 END) AS Restricted,
            SUM(CASE WHEN (lev.type = 4 AND lev.number_leave < 0) THEN lev.number_leave ELSE 0 END) AS Restricted_N,
            SUM(CASE WHEN (lev.type = 5 AND lev.number_leave > 0) THEN lev.number_leave ELSE 0 END) AS Paternity,
            SUM(CASE WHEN (lev.type = 5 AND lev.number_leave < 0) THEN lev.number_leave ELSE 0 END) AS Paternity_N, 
            SUM(CASE WHEN (lev.type = 6 AND lev.number_leave > 0) THEN lev.number_leave ELSE 0 END) AS Compoff,
            SUM(CASE WHEN (lev.type = 6 AND lev.number_leave < 0) THEN lev.number_leave ELSE 0 END) AS Compoff_N,
            SUM(CASE WHEN (lev.type = 7 AND lev.number_leave < 0) THEN lev.number_leave ELSE 0 END) AS Casual
        ');
        $builder->join('users as u', 'u.id_user = lev.emp_id AND u.valid = 1', 'left');
        $builder->where('lev.status', '1');
        $builder->groupBy('lev.emp_id, u.name');
        $builder->where('lev.start_dt >=', $start_date);
        $builder->where('lev.start_dt <=', $end_date);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getetLeavesDatabyTeam($user, $start_of_year, $end_of_year)
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.emp_id, u.name,u.last_name,u.id_user,
            SUM(CASE WHEN (lev.type = 2 AND lev.number_leave > 0) THEN lev.number_leave ELSE 0  END) AS Earned,
            SUM(CASE WHEN (lev.type = 2 AND lev.number_leave < 0) THEN lev.number_leave ELSE 0 END) AS Earned_N,
            SUM(CASE WHEN (lev.type = 3 AND lev.number_leave > 0) THEN lev.number_leave ELSE 0 END) AS Medical,
            SUM(CASE WHEN (lev.type = 3 AND lev.number_leave < 0) THEN lev.number_leave ELSE 0 END) AS Medical_N,
            SUM(CASE WHEN (lev.type = 4 AND lev.number_leave > 0) THEN lev.number_leave ELSE 0 END) AS Restricted,
            SUM(CASE WHEN (lev.type = 4 AND lev.number_leave < 0) THEN lev.number_leave ELSE 0 END) AS Restricted_N,
            SUM(CASE WHEN (lev.type = 5 AND lev.number_leave > 0) THEN lev.number_leave ELSE 0 END) AS Paternity,
            SUM(CASE WHEN (lev.type = 5 AND lev.number_leave < 0) THEN lev.number_leave ELSE 0 END) AS Paternity_N, 
            SUM(CASE WHEN (lev.type = 6 AND lev.number_leave > 0) THEN lev.number_leave ELSE 0 END) AS Compoff,
            SUM(CASE WHEN (lev.type = 6 AND lev.number_leave < 0) THEN lev.number_leave ELSE 0 END) AS Compoff_N,
            SUM(CASE WHEN (lev.type = 7 AND lev.number_leave < 0) THEN lev.number_leave ELSE 0 END) AS Casual
        ');
        $builder->join('et_leave as lev', 'lev.emp_id = u.id_user AND lev.status = 1', 'left');
        $builder->join('users as man', 'man.id_user = lev.emp_id AND u.valid = 1', 'left');
        $builder->where('lev.status', '1');
        $builder->where('man.valid', '1');
        $builder->where('man.manager', $user);
        $builder->groupBy('lev.emp_id, u.name');
        $builder->where('lev.start_dt >=', $start_of_year);
        $builder->where('lev.start_dt <=', $end_of_year);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function importaccesscarddetails($sheetData, $start_date, $columnCount)
    {
        // print_r($sheetData[4][0]);
        // exit();
        if (trim($sheetData[4][0]) == 'Sr.No') {
            $j = 0;
            $date = \DateTime::createFromFormat('d/m/Y', $sheetData[5][3]);
            $excelfriststartdate = $date->format('Y-m-d');

            $builder = $this->db->table('et_access_card_data as ac');
            $builder->select('ac.start_date');
            $builder->where('ac.start_date', $excelfriststartdate);
            $dateformat = $builder->get()->getResultArray();
            if (!empty($dateformat)) {
                $data['error'] = 'Details are aleardy exists , Please delete existing details and Re-import';
                return $data;
            }
            if ($excelfriststartdate != $start_date) {
                $data['error'] = 'Transaction Date not matching with selected Start date!, Try again';
                return $data;
            }
            foreach ($sheetData as $Row) {
                if ($j < 5) {
                    $j++;
                    continue;
                }

                if (trim($Row[0]) == 'Sr.No') {
                    continue;
                }
                // print_r($Row[0]);
                // exit();
                // $j = $j + 1;
                if (isset($Row[1]) && isset($Row[2]) && isset($Row[3]) && isset($Row[4]) && isset($Row[5])) {
                    // print_r($start_date.'<br/>');
                    $emp_id = "";
                    if (isset($Row[1])) {
                        $emp_id = trim($Row[1]);
                    }
                    $builder = $this->db->table('users as u');
                    $builder->select('u.id_user');
                    $builder->where('u.emp_id', $emp_id);
                    $userdata = $builder->get()->getResultArray();
                    if (!empty($userdata)) {
                        $user_id = $userdata[0]['id_user'];
                    } else {
                        continue;
                    }


                    $start_date = "";
                    if (isset($Row[3])) {
                        $date = \DateTime::createFromFormat('d/m/Y', $Row[3]);
                        $start_date = $date->format('Y-m-d');
                        $start_date = $start_date;
                    }
                    $timein = "00:00";
                    $timeout = "00:00";

                    // Set time in and time out if values are available
                    if (isset($Row[4]) && !empty($Row[4])) {
                        $inputTime = $Row[4];
                        $dateTime = new \DateTime($inputTime);
                        $timein = $dateTime->format('H:i');
                    }

                    if (isset($Row[5]) && !empty($Row[5])) {
                        $inputTime = $Row[5];
                        $dateTime = new \DateTime($inputTime);
                        $timeout = $dateTime->format('H:i');
                    }

                    // Check if timein and timeout are set
                    if (!empty($timein) && !empty($timeout)) {
                        // Create DateTime objects for timein and timeout
                        $timeinObject = \DateTime::createFromFormat('H:i', $timein);
                        $timeoutObject = \DateTime::createFromFormat('H:i', $timeout);
                        $interval = $timeoutObject->diff($timeinObject);
                        $totalhrs = $interval->format('%H:%I'); // %H = hours, %I = minutes
                    }

                    $breakhr = "00:00";
                    if (isset($Row[7])) {
                        $inputTime = $Row[7];
                        // Step 1: Remove the negative sign (if present)
                        $normalizedTime = ltrim($inputTime, '-');  // Removes the leading negative sign

                        // Step 2: Remove leading zeros from the hour part
                        $normalizedTime = preg_replace('/^0+/', '', $normalizedTime);  // Removes leading zeros from hours

                        // Step 3: Ensure the hour part has at least two digits (e.g., 1:47:53 becomes 01:47:53)
                        if (strpos($normalizedTime, ':') !== false) {
                            $timeParts = explode(":", $normalizedTime);
                            if (count($timeParts) == 2) {
                                // If it's a minute-second format, we add leading zero to hours
                                $normalizedTime = str_pad($timeParts[0], 2, "0", STR_PAD_LEFT) . ":" . $timeParts[1];
                            } elseif (count($timeParts) == 3) {
                                // If it's hour:minute:second format, just pad the hour if needed
                                $normalizedTime = str_pad($timeParts[0], 2, "0", STR_PAD_LEFT) . ":" . $timeParts[1] . ":" . $timeParts[2];
                            }
                        }

                        $dateTime = new \DateTime($normalizedTime);
                        $breakhr = $dateTime->format('H:i');
                        // exit($breakhr);
                    }
                    $totalTimeObj = \DateTime::createFromFormat('H:i', $totalhrs);
                    $breakTimeObj = \DateTime::createFromFormat('H:i', $breakhr);
                    $totalMinutes = ($totalTimeObj->format('H') * 60) + $totalTimeObj->format('i');  // Total time in minutes
                    $breakMinutes = ($breakTimeObj->format('H') * 60) + $breakTimeObj->format('i'); // Break time in minutes
                    $actualMinutes = $totalMinutes - $breakMinutes;
                    $actualHours = floor($actualMinutes / 60);
                    $actualRemainingMinutes = $actualMinutes % 60;
                    $actualhr = sprintf('%02d:%02d', $actualHours, $actualRemainingMinutes);
                    echo "Actual time worked: " . $actualhr;

                    if ($actualMinutes > 450) {
                        $attendancetype = 1;
                    } elseif ($actualMinutes > 210) {
                        $attendancetype = .5;
                    } else {
                        $attendancetype = 0;
                    }

                    $insertaccesscarddata = [
                        'user_id' => $user_id,
                        'emp_id' => $emp_id,
                        'start_date' => $start_date,
                        'start_date' => $start_date,
                        'timein' => $timein,
                        'timeout' => $timeout,
                        'totalhrs' => $totalhrs,
                        'breakhr' => $breakhr,
                        'actualhr' => $actualhr,
                        'actual_time_in_min' => $actualMinutes,
                        'attendance_type' => $attendancetype,
                        'status' => 1,
                        'last_updated_by' => session()->get('id_user'),
                        'last_updated_on' => time()
                    ];
                    // print_r($insertquestiondata);
                    // exit();
                    $db = \Config\Database::connect();
                    $builder = $this->db->table('et_access_card_data');
                    $builder->insert($insertaccesscarddata);
                    $insert_id = $db->insertID();
                } else {
                    $insertrecordCount = $j - 1;
                    $data['error'] = "Row " . $j . " : don't have properly value, further row excution has been stopped <br/>
                    only" . $insertrecordCount . " : Record imported successfully";
                    return $data;
                }
            }
            $data['success'] = 'Record imported successfully';
            return $data;
        } else {
            $data['error'] = 'Data not found!, Your trying to import wrong Excelsheet';
            return $data;
        }
    }
    function getAccessCardDetails($start_date)
    {
        $builder = $this->db->table('et_access_card_data as ac');
        $builder->select('ac.*,CONCAT(u.name," ",u.last_name) as fullname');
        $builder->join('users as u', 'u.id_user = ac.user_id', 'left');
        $builder->where('ac.start_date ', $start_date);
        $builder->orderBy('ac.start_date', 'ASC');
        $builder->where('ac.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function deleteaccessCarddeatils($start_date)
    {
        $builder = $this->db->table('et_access_card_data');
        $builder->where('start_date', $start_date);
        $builder->delete();
        $affectedRows = $this->db->affectedRows();
        if ($affectedRows > 0) {
            $data['success'] = 'Record Deleted successfully';
        } else {
            $data['error'] = lang('Messages.Error_0001');
        }
        return $data;
    }
}
