<?php

namespace App\Controllers\Etrack;

use App\Controllers\BaseController;
use App\Models\Etrack\Attendance_model;
use App\Models\Etrack\Leave_model;
use CodeIgniter\I18n\Time;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

#[\AllowDynamicProperties]
class Attendance extends BaseController
{
    public function __construct()
    {
        $this->is_session_available();
        $this->Attendance_model = new Attendance_model();
        $this->Leave_model = new Leave_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url('my_training'));
            exit();
        }
    }

    public function index()
    {
        return redirect()->to(base_url() . 'etrack/attendance/view');
    }
    public function view($url_value = null) //fetch data from users table to display
    {
        $data = [];
        helper(['form']);

        if (isset($_POST['return_page'])) {
            $data['return_page'] = $_POST['return_page'];
            $_SESSION['return_page'] = $_POST['return_page'];
        } elseif (isset($_SESSION['return_page'])) {
            $data['return_page'] = $_SESSION['return_page'];
        } else {
            $data['return_page'] = 1;
            $_SESSION['return_page'] = 1;
        }

        $return_page = $data['return_page'];

        if (isset($_POST['month'])) {
            $data['report_month'] = $_POST['month'];
            $data['report_year'] = $_POST['year'];
        } else {
            if (date('d') > 25) {
                if (date('m') == 12) {
                    $data['report_month'] = 1;
                    $data['report_year'] = date('Y') + 1;
                } else {
                    $data['report_month'] = date('m') + 1;
                    $data['report_year'] = date('Y');
                }
            } else {
                $data['report_month'] = date('m');
                $data['report_year'] = date('Y');
            }
        }
        date_default_timezone_set("Asia/Kolkata");
        $today = date('Y-m-d');
        $data['todaydt'] = $today;

        if ($return_page == 1 || $url_value == 1) {
            $data['temp_user'] = session()->get('id_user');
        } else {
            if (isset($_POST['temp_user'])) {
                $data['temp_user'] = $_POST['temp_user'];
                $_SESSION['temp_user'] = $data['temp_user'];
            } else if (isset($_SESSION['temp_user'])) {
                $data['temp_user'] = $_SESSION['temp_user'];
            } else {
                $data['temp_user'] = session()->get('id_user');
            }
        }
        $user = $data['temp_user'];
        $data['user_details'] = $this->Leave_model->getUserName($user);

        $report_month = $data['report_month'];
        $report_year = $data['report_year'];

        if ($report_month > 1) {
            $month = $report_month - 1;
            if (strlen($month) < 2) {
                $month = '0' . $month;
            }
            $year = $report_year;
        } else {
            $month = 12;
            $year = $report_year - 1;
        }

        if (strlen($report_month) < 2) {
            $report_month = '0' . $report_month;
        }

        $start_date = $year . '-' . $month . '-26';
        $end_date = $report_year . '-' . $report_month . '-25';

        $data['start_dt'] = $start_date;
        $data['end_dt'] = $end_date;

        if ($user == '1124') {
            $data['holidays'] = $this->Attendance_model->getholidays($start_date, $end_date, 2);
        } else {
            $data['holidays'] = $this->Attendance_model->getholidays($start_date, $end_date, 1);
        }

        $data['workfromhome_data'] = $this->Attendance_model->getworkfromhomemonth($user, $start_date, $end_date);
        $data['leave_data'] = $this->Attendance_model->getleaveformonth($user, $start_date, $end_date);
        $data['access_card'] = $this->Attendance_model->getAccessCardDataLite($user, $start_date, $end_date);

        // $data['today_wfh'] = $this->Attendance_model->getattendancebydate($user, $today);

        $start_dt = new Time('now', 'Asia/Kolkata', 'en_US');
        $start_dt = date("Y-m-d", strtotime("-1 day", strtotime($start_dt)));


        if ($user == '1124') {
            $holidays = $this->Attendance_model->getholidays($start_dt, $start_dt, 2);
        } else {
            $holidays = $this->Attendance_model->getholidays($start_dt, $start_dt, 1);
        }

        if (in_array($start_dt, array_column($holidays, 'holiday_dt'))) {
            $start_dt = date("Y-m-d", strtotime("-1 day", strtotime($start_dt)));
        }

        $currentWeekDay = date("w", strtotime($start_dt));

        switch ($currentWeekDay) {
            case "6": {  // saturday
                    $start_dt = date("Y-m-d", strtotime("-1 day", strtotime($start_dt)));
                    break;
                }
            case "0": {  // sunday
                    $start_dt = date("Y-m-d", strtotime("-2 day", strtotime($start_dt)));
                    break;
                }
            default: {  //all other days
                    $start_dt = $start_dt;
                    break;
                }
        }
        if ($user == '1124') {
            $holidays = $this->Attendance_model->getholidays($start_dt, $start_dt, 2);
        } else {
            $holidays = $this->Attendance_model->getholidays($start_dt, $start_dt, 1);
        }


        if (in_array($start_dt, array_column($holidays, 'holiday_dt'))) {
            $start_dt = date("Y-m-d", strtotime("-1 day", strtotime($start_dt)));
        }


        //  $data['last_wfh'] = $this->Attendance_model->getattendancebydate($user, $start_dt);

        $data['prev_india_time'] = $start_dt;

        echo view('templates/header_view', $data);
        echo view('etrack/Attendance/attendance_view', $data);
        echo view('templates/footer_view');
    }

    public function getWorkingDays($start_date, $end_date, $holidays)
    {
        $business_days = 0;
        $current_date = strtotime($start_date);
        $end_date = strtotime($end_date);
        $holidaySet = array_column($holidays, 'holiday_dt');
        while ($current_date <= $end_date) {
            if (date('N', $current_date) < 6 && !in_array(date('Y-m-d', $current_date), $holidaySet, true)) {
                $business_days++;
            }
            if ($current_date <= $end_date) {
                $current_date = strtotime('+1 day', $current_date);
            }
        }
        return $business_days;
    }

    public function add_wfh()
    {
        $type = $_POST['type'];
        $value = $_POST['value'];
        date_default_timezone_set("Asia/Kolkata");



        //  $data['today_india_time'] = new Time('now', 'Asia/Kolkata', 'en_US');
        // $data['prev_india_time'] = $start_dt;
        if ($type == 1) {
            $start_dt = new Time('now', 'Asia/Kolkata', 'en_US');
            $start_dt = date("Y-m-d", strtotime($start_dt));
        }
        if ($type == 2) {
            $start_dt = new Time('now', 'Asia/Kolkata', 'en_US');
            $start_dt = date("Y-m-d", strtotime("-1 day", strtotime($start_dt)));
            $user = session()->get('id_user');
            if ($user == '1124') {
                $holidays = $this->Attendance_model->getholidays($start_dt, $start_dt, 2);
            } else {
                $holidays = $this->Attendance_model->getholidays($start_dt, $start_dt, 1);
            }


            if (in_array($start_dt, array_column($holidays, 'holiday_dt'))) {
                $start_dt = date("Y-m-d", strtotime("-1 day", strtotime($start_dt)));
            }

            $currentWeekDay = date("w", strtotime($start_dt));

            switch ($currentWeekDay) {
                case "6": {  // saturday
                        $start_dt = date("Y-m-d", strtotime("-1 day", strtotime($start_dt)));
                        break;
                    }
                case "0": {  // sunday
                        $start_dt = date("Y-m-d", strtotime("-2 day", strtotime($start_dt)));
                        break;
                    }
                default: {  //all other days
                        $start_dt = $start_dt;
                        break;
                    }
            }
            if ($user == '1124') {
                $holidays = $this->Attendance_model->getholidays($start_dt, $start_dt, 2);
            } else {
                $holidays = $this->Attendance_model->getholidays($start_dt, $start_dt, 1);
            }
            if (in_array($start_dt, array_column($holidays, 'holiday_dt'))) {
                $start_dt = date("Y-m-d", strtotime("-1 day", strtotime($start_dt)));
            }
        }
        $id_user = session()->get('id_user');
        $check_attendance = $this->Attendance_model->getattendancebydate($id_user, $start_dt);
        $appliedwfh = 0;
        if ($check_attendance) {
            $appliedwfh = $check_attendance[0]['number_wfh'];
        }

        $canapply = 0;
        if ($appliedwfh > 0) {
            if ($value == 1 && $appliedwfh == 1) {
                $canapply = 0;
            } else if ($value == .5 && $appliedwfh == .5) {
                $canapply = .5;
            }
        } else {
            $canapply = $value;
        }

        if ($canapply > 0) {
            $newdata = [
                'emp_id' => session()->get('id_user'),
                'number_wfh' => $canapply,
                'start_date' => $start_dt,
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
                'status' => 1
            ];
            $this->Attendance_model->add_wfh_data($newdata);
            session()->setFlashdata('success', lang('Messages.Success_0025'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
        }

        return redirect()->to(base_url() . 'etrack/attendance');
    }

    public function access_card_data()
    {
        $data = [];
        helper(['form']);
        $id_user = session()->get('id_user');

        if (isset($_POST['month'])) {
            $data['month'] = $_POST['month'];
            $data['year'] = $_POST['year'];
        } else {
            if (date('d') > 25) {
                if (date('m') == 12) {
                    $data['month'] = 1;
                    $data['year'] = date('Y') + 1;
                } else {
                    $data['month'] = date('m') + 1;
                    $data['year'] = date('Y');
                }
            } else {
                $data['month'] = date('m');
                $data['year'] = date('Y');
            }
        }
        $year = date('Y');
        if ($data['month'] == 1) {
            $pre_month = 12;
            $pre_year = $year - 1;
        } else {
            $pre_month = $data['month'] - 1;
            $pre_year = $year;
        }

        if (strlen($pre_month) < 2) {
            $pre_month = '0' . $pre_month;
        }

        if (strlen($data['month']) < 2) {
            $this_month = '0' . $data['month'];
        } else {
            $this_month = $data['month'];
        }

        $data['start_date'] = $pre_year . '-' . $pre_month . '-26';
        $data['end_date'] = $year . '-' . $this_month . '-25';

        $data['access_card'] = $this->Attendance_model->getAccessCardData($id_user, $data['start_date'], $data['end_date']);

        echo view('templates/header_view');
        echo view('etrack/Attendance/access_card_view', $data);
        echo view('templates/footer_view');
    }

    function delete_remarks()
    {
        $access_id = $_POST['access_id'];
        $newdata = [
            'remarks' => ''
        ];
        $this->Attendance_model->delete_remarks_from_accesscard($newdata, $access_id);
        session()->setFlashdata('success', lang('Messages.Success_0005'));
        return redirect()->to(base_url() . 'etrack/attendance/access_card_data');
    }

    function add_remarks()
    {
        $access_id = $_POST['access_id'];
        $newdata = [
            'remarks' => $_POST['remarks']
        ];
        $this->Attendance_model->delete_remarks_from_accesscard($newdata, $access_id);
        session()->setFlashdata('success', lang('Messages.Success_0011'));
        return redirect()->to(base_url() . 'etrack/attendance/access_card_data');
    }

    function delete_wfh()
    {
        $et_wfh_id = $_POST['et_wfh_id'];
        $newdata = [
            'status' => 0,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];
        $this->Attendance_model->delete_remarks_from_wfh($newdata, $et_wfh_id);
        session()->setFlashdata('success', lang('Messages.Success_0005'));
        return redirect()->to(base_url() . 'etrack/attendance/wfh_statement');
    }

    function delete_wfh_remarks()
    {
        $et_wfh_id = $_POST['et_wfh_id'];
        $newdata = [
            'remarks' => ''
        ];
        $this->Attendance_model->delete_remarks_from_wfh($newdata, $et_wfh_id);
        session()->setFlashdata('success', lang('Messages.Success_0005'));
        return redirect()->to(base_url() . 'etrack/attendance/wfh_statement');
    }

    function add_wfh_remarks()
    {
        $et_wfh_id = $_POST['et_wfh_id'];
        $newdata = [
            'remarks' => $_POST['remarks']
        ];
        $this->Attendance_model->delete_remarks_from_wfh($newdata, $et_wfh_id);
        session()->setFlashdata('success', lang('Messages.Success_0011'));
        return redirect()->to(base_url() . 'etrack/attendance/wfh_statement');
    }
    public function wfh_statement()
    {
        $_SESSION['return_page'] = 1;
        return redirect()->to(base_url() . 'etrack/attendance/wfh_statement_view');
    }

    public function wfh_statement_view()
    {
        $data = [];
        if (isset($_POST['return_page'])) {
            $data['return_page'] = $_POST['return_page'];
            $_SESSION['return_page'] = $_POST['return_page'];
        } elseif (isset($_SESSION['return_page'])) {
            $data['return_page'] = $_SESSION['return_page'];
        } else {
            $data['return_page'] = 1;
        }

        $return_page = $data['return_page'];

        if ($return_page == 1) {
            $data['temp_user'] = session()->get('id_user');
        } else {
            if (isset($_POST['temp_user'])) {
                $data['temp_user'] = $_POST['temp_user'];
                $_SESSION['temp_user'] = $data['temp_user'];
            } else if (isset($_SESSION['temp_user'])) {
                $data['temp_user'] = $_SESSION['temp_user'];
            } else {
                $data['temp_user'] = session()->get('id_user');
            }
        }
        $user = $data['temp_user'];
        $data['user_details'] = $this->Leave_model->getUserName($user);


        if (isset($_POST['month'])) {
            $data['month'] = $_POST['month'];
            $data['year'] = $_POST['year'];
        } else {
            if (date('d') > 25) {
                if (date('m') == 12) {
                    $data['month'] = 1;
                    $data['year'] = date('Y') - 1;
                } else {
                    $data['month'] = date('m') + 1;
                    $data['year'] = date('Y');
                }
            } else {
                $data['month'] = date('m');
                $data['year'] = date('Y');
            }
        }
        $year = $data['year'];
        if ($data['month'] == 1) {
            $pre_month = 12;
            $pre_year = $year - 1;
        } else {
            $pre_month = $data['month'] - 1;
            $pre_year = $year;
        }

        if (strlen($pre_month) < 2) {
            $pre_month = '0' . $pre_month;
        }

        if (strlen($data['month']) < 2) {
            $data['month'] = '0' . $data['month'];
        }

        $data['start_date'] = $pre_year . '-' . $pre_month . '-26';
        $data['end_date'] = $year . '-' . $data['month'] . '-25';

        $data['wfh_statement'] = $this->Attendance_model->getwfhstatement($data['temp_user'], $data['start_date'], $data['end_date']);

        echo view('templates/header_view');
        echo view('etrack/Attendance/wfh_statement_view', $data);
        echo view('templates/footer_view');
    }


    public function team_attendance()
    {
        $data = [];
        if (session()->get('report_to_you') == 2) {
            helper(['form']);
            $id_user = session()->get('id_user');

            if (isset($_POST['month'])) {
                $data['month'] = $_POST['month'];
                $data['year'] = $_POST['year'];
            } else {
                if (date('d') > 25) {
                    if (date('m') == 12) {
                        $data['month'] = date('m');
                        $data['year'] = date('Y');
                    } else {
                        $data['month'] = date('m') + 1;
                        $data['year'] = date('Y');
                    }
                } else {
                    $data['month'] = date('m');
                    $data['year'] = date('Y');
                }
            }

            $year =  $data['year'];

            if ($data['month'] == 1) {
                $pre_month = 12;
                $pre_year = $year - 1;
            } else {
                $pre_month = $data['month'] - 1;
                $pre_year = $year;
            }

            if (strlen($pre_month) < 2) {
                $pre_month = '0' . $pre_month;
            }
            if (strlen($data['month']) < 2) {
                $this_month = '0' . $data['month'];
            } else {
                $this_month = $data['month'];
            }

            $data['start_date'] = $pre_year . '-' . $pre_month . '-26';
            $data['end_date'] = $year . '-' . $this_month . '-25';



            $data['data_value'] = $this->Attendance_model->teamAttendance($id_user, $data['start_date'], $data['end_date']);
            // echo "<pre>";
            // print_r($data['data_value']);
            // exit();
            $data['return_page'] = 2;
            $_SESSION['return_page'] = 2;

            $data['holidays'] = $this->Attendance_model->getholidays($data['start_date'], $data['end_date'], 1);


            //   $today = date('Y-m-d');
            //  if ($data['end_date'] > $today) {
            //    $newdat = date('Y-m-d', (strtotime('-1 day', strtotime($today))));
            //        $data['working_days'] = $this->getWorkingDays($data['start_date'], $newdat, $data['holidays']);
            //   } else {
            $data['working_days'] = $this->getWorkingDays($data['start_date'], $data['end_date'], $data['holidays']);
            //   }

            echo view('templates/header_view');
            echo view('etrack/Attendance/team_attendance_view', $data);
            echo view('templates/footer_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            return redirect()->to(base_url() . 'etrack/attendance');
        }
    }

    public function team_attenance_statement()
    {
        $data = [];

        if (isset($_POST['temp_user'])) {
            $data['temp_user'] = $_POST['temp_user'];
            $_SESSION['temp_user'] = $data['temp_user'];
        } else if (isset($_SESSION['temp_user'])) {
            $data['temp_user'] = $_SESSION['temp_user'];
        } else {
            return redirect()->to(base_url() . 'etrack/attendance/team_attendance');
        }

        if (isset($_POST['return_page'])) {
            $data['return_page'] = $_POST['return_page'];
        } else {
            $data['return_page'] = 1;
        }

        helper(['form']);
        if (isset($_POST['month'])) {
            $data['report_month'] = $_POST['month'];
            $data['report_year'] = $_POST['year'];
        } else {
            $data['report_month'] = date('m');
            $data['report_year'] = date('Y');
        }
        $today = date('Y-m-d');
        $user = $data['temp_user'];

        $report_month = $data['report_month'];
        $report_year = $data['report_year'];

        if ($report_month > 1) {
            $month = $report_month - 1;
            if (strlen($month) < 2) {
                $month = '0' . $month;
            }
            $year = $report_year;
        } else {
            $month = 12;
            $year = $report_year - 1;
        }

        if (strlen($report_month) < 2) {
            $report_month = '0' . $report_month;
        }

        $start_date = $year . '-' . $month . '-26';
        $end_date = $report_year . '-' . $report_month . '-25';

        $data['user_details'] = $this->Leave_model->getUserName($user);

        if ($user == '1124') {
            $data['holidays'] = $this->Attendance_model->getholidays($start_date, $end_date, 2);
        } else {
            $data['holidays'] = $this->Attendance_model->getholidays($start_date, $end_date, 1);
        }

        if ($end_date > $today) {
            $newdat = date('Y-m-d', (strtotime('-1 day', strtotime($today))));
            $data['working_days'] = $this->getWorkingDays($start_date, $newdat, $data['holidays']);
        } else {
            $data['working_days'] = $this->getWorkingDays($start_date, $end_date, $data['holidays']);
        }


        $data['workfromhome_data'] = $this->Attendance_model->getworkfromhomemonth($user, $start_date, $end_date);
        $data['leave_data'] = $this->Attendance_model->getleaveformonth($user, $start_date, $end_date);
        $data['access_card'] = $this->Attendance_model->getAccessCardDataLite($user, $start_date, $end_date);

        $data['today_wfh'] = $this->Attendance_model->getattendancebydate($user, $today);
        $currentWeekDay = date("w");
        switch ($currentWeekDay) {
            case "1": {  // monday
                    $start_dt = date("Y-m-d", strtotime("-3 day"));
                    break;
                }
            case "0": {  // sunday
                    $start_dt = date("Y-m-d", strtotime("-2 day"));
                    break;
                }
            default: {  //all other days
                    $start_dt = date("Y-m-d", strtotime("-1 day"));
                    break;
                }
        }

        $data['last_wfh'] = $this->Attendance_model->getattendancebydate($user, $start_dt);

        echo view('templates/header_view', $data);
        echo view('etrack/Attendance/attendance_team_view', $data);
        echo view('templates/footer_view');
    }

    public function team_single_user_ac()
    {
        $data = [];
        if (isset($_POST['return_page'])) {
            $data['return_page'] = $_POST['return_page'];
            $_SESSION['return_page'] = $data['return_page'];
        } else if (isset($_SESSION['return_page'])) {
            $data['return_page'] = $_SESSION['return_page'];
        } else {
            $data['return_page'] = 1;
        }


        if (isset($_POST['temp_user'])) {
            $data['temp_user'] = $_POST['temp_user'];
            $_SESSION['temp_user'] = $data['temp_user'];
        } else if (isset($_SESSION['temp_user'])) {
            $data['temp_user'] = $_SESSION['temp_user'];
        } else {
            return redirect()->to(base_url() . 'etrack/attendance/team_attendance');
        }

        $id_user = $data['temp_user'];

        if (isset($_POST['month'])) {
            $data['month'] = $_POST['month'];
            $data['year'] = $_POST['year'];
        } else {
            $data['month'] = date('m');
            $data['year'] = date('Y');
        }
        $year = date('Y');
        if ($data['month'] == 1) {
            $pre_month = 12;
            $pre_year = $year - 1;
        } else {
            $pre_month = $data['month'] - 1;
            $pre_year = $year;
        }

        if (strlen($pre_month) < 2) {
            $pre_month = '0' . $pre_month;
        }

        $data['user_details'] = $this->Leave_model->getUserName($id_user);

        $data['start_date'] = $pre_year . '-' . $pre_month . '-26';
        $data['end_date'] = $year . '-' . $data['month'] . '-25';

        $data['access_card'] = $this->Attendance_model->getAccessCardData($id_user, $data['start_date'], $data['end_date']);

        echo view('templates/header_view');
        echo view('etrack/Attendance/access_card_team_view', $data);
        echo view('templates/footer_view');
    }

    public function apply_wfh_team()
    {
        $data = [];

        if (isset($_POST['temp_user'])) {
            $data['temp_user'] = $_POST['temp_user'];
            $_SESSION['temp_user'] = $data['temp_user'];
        } else if (isset($_SESSION['temp_user'])) {
            $data['temp_user'] = $_SESSION['temp_user'];
        } else {
            return redirect()->to(base_url() . 'etrack/attendance/team_attendance');
        }

        $data['user_details'] = $this->Leave_model->getUserName($data['temp_user']);

        echo view('templates/header_view');
        echo view('etrack/Attendance/team_wfh_apply_view', $data);
        echo view('templates/footer_view');
    }

    public function apply_team_wfh()
    {

        $value = $_POST['value'];
        $temp_user = $_POST['temp_user'];
        $start_dt = $_POST['start_date'];
        $remarks = $_POST['remarks'];
        $newdata = [
            'emp_id' => $temp_user,
            'number_wfh' => $value,
            'start_date' => $start_dt,
            'remarks' => $remarks,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => 1
        ];
        $this->Attendance_model->add_wfh_data($newdata);
        session()->setFlashdata('success', lang('Messages.Success_0025'));
        return redirect()->to(base_url() . 'etrack/attendance/team_attendance');
    }
    public function Attendance_monthly_report()
    {
        $data = [];
        $user = session()->get('id_user');
        // print_r();
        // exit();
        // $data['team_task'] = $this->task_model->getTeam($user);
        echo view('templates/header_view', $data);
        echo view('etrack/Attendance/attendance_monthly_report_view', $data);
        echo view('templates/footer_view', $data);
    }
    public function attendance_montly_report()
    {
        $data = [];
        if ($this->request->getPost()) {
            $month = $_POST['month'];
            $year = $_POST['year'];
            $user = session()->get('id_user');
            $startDate = new \DateTime("$year-$month-26");
            $startDate->modify('-1 month');
            $endDate = new \DateTime("$year-$month-25");

            $interval = $startDate->diff($endDate);
            $daysInCycle = $interval->days + 1;

            $expectedWorkingDays = 0;
            $loopDate = clone $startDate;
            for ($i = 0; $i < $daysInCycle; $i++) {
                if (!in_array($loopDate->format('w'), ['0', '6'])) { // 0 = Sunday, 6 = Saturday
                    $expectedWorkingDays++;
                }
                $loopDate->modify('+1 day');
            }


            $attendanceMonthlyReport = $this->Attendance_model->getattendanceMonthlyReport($startDate->format('Y-m-d'), $endDate->format('Y-m-d'), $user);
            // echo "<pre>";
            // print_r($attendanceMonthlyReport);
            // exit();
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $cycleRange = $startDate->format('d M Y') . " - " . $endDate->format('d M Y');
            $sheet->setCellValue('A1', "Attendance Report ($cycleRange)");
            $sheet->mergeCells("A1:Z1");
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);

            $sheet->setCellValue('A3', '#');
            $sheet->setCellValue('B3', 'Emp ID');
            $sheet->setCellValue('C3', 'Employee Name');
            $sheet->setCellValue('D3', 'Engage Type');
            $sheet->setCellValue('E3', 'Type');
            $sheet->getStyle('A3:E3')->getFont()->setBold(true);

            for ($day = 0; $day < $daysInCycle; $day++) {
                $date = clone $startDate;
                $date->modify("+$day day");
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($day + 6);
                $sheet->setCellValue($columnLetter . '3', $date->format('m-d'));
                $sheet->getStyle($columnLetter . '3')->getFont()->setBold(true);

                if (in_array($date->format('w'), ['0', '6'])) {
                    $sheet->getStyle($columnLetter . '3')->getFill()->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FFD3D3D3');
                }
            }

            $finalColIndex = $daysInCycle + 6;
            $finalColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($finalColIndex);
            $sheet->setCellValue($finalColLetter . '3', 'Total');
            $sheet->getStyle($finalColLetter . '3')->getFont()->setBold(true);

            $effortColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($finalColIndex + 1);
            $sheet->setCellValue($effortColLetter . '3', 'Effort Days');
            $sheet->getStyle($effortColLetter . '3')->getFont()->setBold(true);

            $lwpeffortColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($finalColIndex + 2);
            $sheet->setCellValue($lwpeffortColLetter . '3', 'Total LWP Days');
            $sheet->getStyle($lwpeffortColLetter . '3')->getFont()->setBold(true);


            $groupedUsers = [];
            foreach ($attendanceMonthlyReport as $entry) {
                if ($entry['emp_no'] != 'NA') {
                    $emp_id = $entry['emp_id'];
                    $groupedUsers[$emp_id] = [
                        'name' => $entry['fullname'] ?? '',
                        'emp_no' => $entry['emp_no'] ?? '',
                        'engage_type' => $entry['engage_type'] ?? '',
                    ];
                }
            }

            $j = 1;
            $row = 4;
            $types = ['WFH', 'Leaves', 'In Office', 'Total'];

            // Initialize column totals
            $columnTotals = [];
            for ($i = 0; $i < $daysInCycle + 1; $i++) {
                $columnTotals[$i] = ['WFH' => 0, 'Leaves' => 0, 'In Office' => 0];
            }

            foreach ($groupedUsers as $emp_id => $name) {
                // $rowTypeData = ['WFH' => 0, 'Leaves' => 0, 'In Office' => 0];
                $rowTypeDataEffort = ['WFH' => 0, 'Leaves' => 0, 'In Office' => 0, 'LWP' => 0];
                // For TOTAL (effort count)
                $rowTypeDataMinutes = ['In Office' => 0];  // For HOURS total only for "In Office"

                foreach ($types as $typeIndex => $type) {
                    if ($name['engage_type'] == 1) {
                        $engage_type = 'Permanent';
                    } elseif ($name['engage_type'] == 2) {
                        $engage_type = 'Contract';
                    } else {
                        $engage_type = '';
                    }
                    $sheet->setCellValue("A$row", $j);
                    $sheet->setCellValue("B$row", $name['emp_no']);
                    $sheet->setCellValue("C$row", $name['name']);
                    $sheet->setCellValue("D$row", $engage_type);
                    $sheet->setCellValue("E$row", $type);

                    $rowTotal = 0;

                    for ($day = 0; $day < $daysInCycle; $day++) {
                        $date = clone $startDate;
                        $date->modify("+$day day");
                        $formattedDate = $date->format('Y-m-d');
                        $colIndex = $day + 6;
                        $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                        $value = '';

                        if (in_array($type, ['Leaves', 'WFH', 'In Office'])) {
                            $match = array_filter($attendanceMonthlyReport, function ($entry) use ($emp_id, $formattedDate, $type) {
                                return $entry['emp_id'] == $emp_id
                                    && $entry['date'] == $formattedDate
                                    && $entry['attendance_type'] == $type;
                            });

                            if (!empty($match)) {

                                $valueParts = [];
                                $totalEffort = 0;

                                foreach ($match as $entry) {
                                    if ($type === 'WFH') {
                                        $leavetype = 'WFH';
                                    } else {
                                        switch ($entry['type']) {
                                            case '2':
                                                $leavetype = 'EL';
                                                break;
                                            case '3':
                                                $leavetype = 'ML';
                                                break;
                                            case '4':
                                                $leavetype = 'RL';
                                                break;
                                            case '5':
                                                $leavetype = 'PL';
                                                break;
                                            case '6':
                                                $leavetype = 'CL';
                                                break;
                                            case '7':
                                                $leavetype = 'CL+';
                                                break;
                                            case '8':
                                                $leavetype = 'LWP';
                                                break;

                                            case '9':
                                                $leavetype = 'OD';
                                                break;
                                            default:
                                                $leavetype = '';
                                        }
                                    }



                                    $rawValue = $entry['number_leave'] ?? '';
                                    $effort = 0;

                                    if ($type === 'In Office' && preg_match('/^\d{2}:\d{2}$/', $rawValue)) {
                                        $valueParts[] = $rawValue;
                                        list($h, $m) = explode(':', $rawValue);
                                        $minutes = $h * 60 + $m;
                                        if (!in_array($date->format('w'), ['0', '6'])) {
                                            $rowTypeDataMinutes['In Office'] += $minutes;
                                        }

                                        if ($minutes >= 438) {
                                            $effort = 1;
                                        } elseif ($minutes >= 240) {
                                            $effort = 0.5;
                                        }
                                    } elseif (is_numeric($rawValue)) {
                                        $effort = abs(floatval($rawValue));
                                    }
                                    if ($type === 'Leaves' && $leavetype === 'LWP') {
                                        $rowTypeDataEffort['LWP'] += $effort;
                                    }


                                    $totalEffort += $effort;

                                    if ($type === 'WFH' && $effort > 0) {
                                        $valueParts[] = "{$effort}";
                                    } elseif ($type !== 'In Office' && $leavetype && $effort > 0) {
                                        $valueParts[] = "{$effort} {$leavetype}";
                                    }
                                }



                                $value = implode(' + ', $valueParts);

                                // Update totals
                                $rowTotal += $totalEffort;
                                $columnTotals[$day][$type] += $totalEffort;
                                if (!in_array($date->format('w'), ['0', '6'])) {
                                    $rowTypeDataEffort[$type] += $totalEffort;
                                }

                                $sheet->setCellValue($colLetter . $row, $value);
                            }
                        } elseif ($type === 'Total') {
                            // Total effort per person per day
                            $dayEffort = 0;
                            foreach (['WFH', 'Leaves', 'In Office'] as $effType) {
                                $match = array_filter($attendanceMonthlyReport, function ($entry) use ($emp_id, $formattedDate, $effType) {
                                    return $entry['emp_id'] == $emp_id
                                        && $entry['date'] == $formattedDate
                                        && $entry['attendance_type'] == $effType;
                                });

                                if (!empty($match)) {
                                    foreach ($match as $entry) {
                                        $val = $entry['number_leave'] ?? '';
                                        $effort = 0;

                                        if ($effType === 'In Office' && preg_match('/^\d{2}:\d{2}$/', $val)) {
                                            list($h, $m) = explode(':', $val);
                                            $minutes = $h * 60 + $m;

                                            if ($minutes >= 438) {
                                                $effort = 1;
                                            } elseif ($minutes >= 240) {
                                                $effort = 0.5;
                                            }
                                        } elseif (is_numeric($val)) {
                                            $effort = floatval($val);
                                        }
                                        if (!in_array($date->format('w'), ['0', '6'])) {
                                            $dayEffort += $effort;
                                        }
                                    }
                                }
                            }

                            $value = $dayEffort > 0 ? $dayEffort : '';
                            $rowTotal += $dayEffort;
                        }

                        $sheet->setCellValue($colLetter . $row, $value);
                        if ($type === 'Total') {
                            if ($value === 0.5 || $value === '0.5') {
                                $sheet->getStyle($colLetter . $row)->getFill()
                                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                    ->getStartColor()->setARGB('FFFF00'); // Yellow background
                            }
                        }
                        if ($type === 'Leaves' && $value != '' && $leavetype === 'LWP') {
                            $sheet->getStyle($colLetter . $row) // <-- exact cell
                                ->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FFFF0000'); // Solid Red
                        }
                    }



                    if ($type === 'In Office') {
                        // Show total hours worked
                        $totalMinutes = $rowTypeDataMinutes['In Office'];
                        $totalHours = floor($totalMinutes / 60); // decimal hours
                        $inOfficeEffortDays = round(round(($totalHours / 8), 1) * 2) / 2;
                        // $inOfficeEffortDays = $this->customRound((($totalHours / 8) * 10) / 10); // this truncates (21.56 → 21.5)

                        // round(round(($hrs / 8), 1) * 2) / 2;
                        // $sheet->setCellValue($effortColLetter . $row, $inOfficeEffortDays);
                        $sheet->setCellValue(
                            $effortColLetter . $row,
                            $inOfficeEffortDays == 0 ? '' : $inOfficeEffortDays
                        );
                    } elseif ($type === 'Total') {

                        // Sum of daily effort values (1s, 0.5s) for WFH + Leaves + In Office
                        $grandTotal = $rowTypeDataEffort['WFH'] + $rowTypeDataEffort['Leaves'] + $rowTypeDataEffort['In Office'];
                        $roundedTotal = round($grandTotal, 2);
                        // $sheet->setCellValue($finalColLetter . $row, round($grandTotal, 2));
                        $sheet->setCellValue(
                            $finalColLetter . $row,
                            $grandTotal == 0 ? '' : round($grandTotal, 2)
                        );


                        // Highlight if less than expected working days
                        if ($roundedTotal < $expectedWorkingDays) {
                            $sheet->getStyle($finalColLetter . $row)->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FFEB9C'); // Light yellow
                            $sheet->getStyle($finalColLetter . $row)->getFont()->getColor()->setRGB('9C6500'); // Brown font
                        }

                        $wfhDays = $rowTypeDataEffort['WFH'];
                        $leaveDays = $rowTypeDataEffort['Leaves'];
                        $totalMinutes = $rowTypeDataMinutes['In Office'];
                        $totalHours = floor($totalMinutes / 60); // decimal hours
                        $inOfficeEffortDays = round(round(($totalHours / 8), 1) * 2) / 2;

                        $totalEffortDays = $wfhDays + $leaveDays + $inOfficeEffortDays;


                        // $sheet->setCellValue($effortColLetter . $row, $totalEffortDays);
                        $sheet->setCellValue(
                            $effortColLetter . $row,
                            $totalEffortDays == 0 ? '' : $totalEffortDays
                        );

                        if ($totalEffortDays < $expectedWorkingDays) {
                            $sheet->getStyle($effortColLetter . $row)->getFill()
                                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                ->getStartColor()->setARGB('FEEB9F'); // Light yellow
                            $sheet->getStyle($effortColLetter . $row)->getFont()->getColor()->setRGB('9C6500'); // Brown font
                        }
                    } else {
                        // For WFH and Leaves rows
                        // $sheet->setCellValue($effortColLetter . $row, $rowTypeDataEffort[$type]);
                        $sheet->setCellValue(
                            $effortColLetter . $row,
                            $rowTypeDataEffort[$type] == 0 ? '' : $rowTypeDataEffort[$type]
                        );
                    }
                    if ($type === 'Leaves') {
                        // $sheet->setCellValue($lwpeffortColLetter . $row, $rowTypeDataEffort['LWP']);
                        $sheet->setCellValue(
                            $lwpeffortColLetter . $row,
                            $rowTypeDataEffort['LWP'] == 0 ? '' : $rowTypeDataEffort['LWP']
                        );
                        // Apply background fill color (RED)
                        $sheet->getStyle($lwpeffortColLetter . $row) // <-- exact cell
                            ->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('FFFF0000'); // Solid Red
                        // Apply font color (WHITE so it is visible on red)
                        // $sheet->getStyle($lwpeffortColLetter)->getFont()->getColor()->setRGB('FFFFFF');

                    }
                    $row++;
                }

                $j++;
            }

            $filename = "Attendance_Report_{$month}_{$year}.xlsx";
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=\"$filename\"");
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        }
    }
}
