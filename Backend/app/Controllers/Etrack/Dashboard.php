<?php

namespace App\Controllers\Etrack;

use App\Controllers\BaseController;
use App\Models\Etrack\Dashboard_model;
use App\Models\Etrack\Leave_model;
use App\Models\Task\Task_model;
use App\Models\User_login\Users_model;
use App\Models\Social\Post_model;
use App\Models\Etrack\Attendance_model;
use App\Models\Holiday\Holidays_model;
use App\Models\Project_Manage\PM_Effort_Tracker_Model;

#[\AllowDynamicProperties]
class Dashboard extends BaseController
{
    public function __construct()
    {
        $this->is_session_available();
        $this->Dashboard_model = new Dashboard_model();
        $this->Leave_model = new Leave_model();
        $this->task_model = new Task_model();
        $this->Users_model = new Users_model();
        $this->Attendance_model = new Attendance_model();
        $this->holidays_model = new Holidays_model();
        $this->post_model = new Post_model();
        $this->PM_Effort_Tracker_model = new PM_Effort_Tracker_Model();
    }
    private function is_session_available()
    {
        $client = session()->get('client');
        if ($client != 1) {
            header('Location:' . base_url('my_training'));
            exit();
        }
    }
    public function index() //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        $_SESSION['return_page'] = 1;
        if (isset($_POST['month'])) {
            $data['report_month'] = $_POST['month'];
            $data['report_year'] = $_POST['year'];
        } else {
            if (date('d') > 25) {
                if (date('m') == 12) {
                    $data['report_month'] = date('m');
                    $data['report_year'] = date('Y');
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
        $data['todayx'] = $today;
        $user = session()->get('id_user');

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

        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url('my_training'));
            exit();
        }
        $arrayuserlevel = explode(',', $userlevel);

        $start_date = $year . '-' . $month . '-26';
        $end_date = $report_year . '-' . $report_month . '-25';
        $data['start_dt'] = $start_date;
        $data['end_dt'] = $end_date;

        $data['birthday_buddies'] = $this->Dashboard_model->getbirthdaybuddies($today);
        $data['anniv_buddies'] = $this->Dashboard_model->get_anniversary_buddies($today);

        $holiday_country = ($user == '1124') ? 2 : 1;
        $data['holidays'] = $this->Attendance_model->getholidays($start_date, $end_date, $holiday_country);
        $data['workfromhome_data'] = $this->Attendance_model->getworkfromhomemonth($user, $start_date, $end_date);
        $data['leave_data'] = $this->Attendance_model->getleaveformonth($user, $start_date, $end_date);
        $data['access_card'] = $this->Attendance_model->getAccessCardDataLite($user, $start_date, $end_date);

        $this_week = date('Y-\WW');
        $last_week = date('Y-\WW', strtotime('-1 week'));
        $data['effort_week'] = $this->Dashboard_model->getWeeklyEffort_dash($user, $this_week, $last_week);

        if (session()->get('report_to_you') == 2) {
            $data['pending_approval'] = $this->Dashboard_model->getPendingApprovalCount_dash($user);
        }

        if (in_array('4', $arrayuserlevel)) {
            $access_requests = $this->PM_Effort_Tracker_model->get_pending_access_requests();
            $my_active_projects = $this->PM_Effort_Tracker_model->get_my_active_projects($user);
            $myProjectIds = array_column($my_active_projects, 'projectid');

            $pending_access_count = 0;
            foreach ($access_requests as $access) {
                if (!empty($myProjectIds) && !in_array($access['project_id'], $myProjectIds)) {
                    continue; // Skip requests for projects this PM doesn't manage
                }
                $pending_access_count++;
            }
            $data['pending_access_count'] = $pending_access_count;
        }

        $data['upcoming_holidays'] = $this->holidays_model->upcoming_holidays(1, 4);

        $thisyear = date('Y');
        $start_year = $thisyear . '-01-01';
        $end_year = $thisyear . '-12-31';

        $leave_type_labels = [
            2 => 'Earned Leave',
            3 => 'Casual Leave',
            4 => 'Restricted Leave',
            6 => 'Comp Off',
        ];
        $leave_balance_rows = $this->Leave_model->getLeaveBalanceByUser($user, $start_year, $end_year);
        $leave_balance_by_type = [];
        foreach ($leave_balance_rows as $row) {
            $leave_balance_by_type[$row['type']] = $row;
        }
        $data['leave_balance'] = [];
        foreach ($leave_type_labels as $type => $label) {
            $data['leave_balance'][] = [
                'label' => $label,
                'balance' => $leave_balance_by_type[$type]['balance'] ?? 0,
                'total' => $leave_balance_by_type[$type]['allotted'] ?? 0,
            ];
        }

        $data['total_employees'] = $this->Users_model->getUserclientlist_onlyname_etrack_dashbaord(1);

        $data['inoffice_count'] = $this->Leave_model->in_office_today($today);
        $data['leave_today'] = $this->Leave_model->leave_today($today);
        $data['wfh_today'] = $this->Leave_model->wfh_today($today);

        $data['my_inofficetoday'] = $this->Leave_model->my_in_office_today($today, $user);
        $data['my_leave_today'] = $this->Leave_model->my_leave_today($today, $user);
        $data['my_wfh_today'] = $this->Leave_model->my_wfh_today($today, $user);

        if ($data['my_inofficetoday']) {
            $inoffice = $data['my_inofficetoday'][0]['num_days'];
        } else {
            $inoffice = 0;
        }
        if ($data['my_leave_today']) {
            $my_leave_today = $data['my_leave_today'][0]['number_leave'];
        } else {
            $my_leave_today = 0;
        }
        if ($data['my_wfh_today']) {
            $my_wfh_today = $data['my_wfh_today'][0]['number_wfh'];
        } else {
            $my_wfh_today = 0;
        }

        $data['myattendance'] = $inoffice + $my_leave_today + $my_wfh_today;

        //$data['to_do'] = $this->Dashboard_model->getTaskByUser_dash($user, 1);
        //$data['in_progress'] = $this->Dashboard_model->getTaskByUser_dash($user, 2);
        if (in_array('4', $arrayuserlevel)) {
            //  $data['active_projects'] = $this->Dashboard_model->get_active_projects(1);
            // $data['closed_projects'] = $this->Dashboard_model->get_active_projects(10);

            // $data['revenue'] = $this->Dashboard_model->get_purchase_order_list(4);
        }
        echo view('templates/header_view', $data);
        echo view('etrack/dashboard_view', $data);
        echo view('templates/footer_view');
    }

    public function inoffice()
    {
        date_default_timezone_set("Asia/Kolkata");
        $today = date('Y-m-d');
        $newdata = [
            'id_user' => session()->get('id_user'),
            'num_days' => $_POST['in_office'],
            'todaydate' => $today,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => 1
        ];
        $this->Dashboard_model->add_inoffice($newdata);
        session()->setFlashdata('success', lang('Messages.Success_0008'));
        return redirect()->to(base_url() . 'etrack/dashboard');
    }

    public function inoffice_half()
    {
        date_default_timezone_set("Asia/Kolkata");
        $today = date('Y-m-d');
        $newdata = [
            'id_user' => session()->get('id_user'),
            'num_days' => $_POST['in_office'],
            'todaydate' => $today,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => 1
        ];
        $this->Dashboard_model->add_inoffice($newdata);
        session()->setFlashdata('success', lang('Messages.Success_0008'));
        return redirect()->to(base_url() . 'etrack/dashboard');
    }

    public function show_in_office()
    {
        $data = [];
        helper(['form']);
        date_default_timezone_set("Asia/Kolkata");
        $today = date('Y-m-d');
        $data['inofficetoday'] = $this->Leave_model->in_office_today_details($today);
        $data['workfromhome'] = $this->Leave_model->workfromhome_details($today);
        $data['leave'] = $this->Leave_model->leave_details($today);
        $data['all_employees'] = $this->Users_model->getUserclientlist_onlyname(1);
        echo view('templates/header_view', $data);
        echo view('etrack/dashboard_employee_view', $data);
        echo view('templates/footer_view');
    }

    public function holiday_cal()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['show_year'])) {
            $data['show_year'] = $_POST['show_year'];
            $_SESSION['show_year'] = $_POST['show_year'];
        } elseif (isset($_SESSION['show_year'])) {
            $data['show_year'] = $_SESSION['show_year'];
        } else {
            $data['show_year'] = date('Y');
        }
        $data['indiaholidaydata'] = $this->holidays_model->holiday_list(1,  $data['show_year']);
        $data['usholidaydata'] = $this->holidays_model->holiday_list(2,  $data['show_year']);
        echo view('templates/header_view', $data);
        echo view('etrack/dashboard_allholidays', $data);
        echo view('templates/footer_view');
    }

    public function policies()
    {
        $data = [];
        helper(['form']);
        $data['policies'] = $this->Dashboard_model->get_tlc_policies();
        echo view('templates/header_view', $data);
        echo view('etrack/dashboard_policies', $data);
        echo view('templates/footer_view');
    }

    public function view_policy()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['emd_id'])) {

            $emd_id = $_POST['emd_id'];
        } else {

            session()->setFlashdata('error', 'No policy selected.');
            return redirect()->to(base_url() . 'etrack/dashboard/policies');
        }

        $data['emd_id'] = $emd_id;

        $data['policy_list'] = $this->Dashboard_model->get_tlc_policy_details($emd_id);
        $data['policy_name'] = $this->Dashboard_model->get_tlc_policy_name($emd_id);
        $data['policy_accepted'] = $this->Dashboard_model->check_policy_accepted(session()->get('id_user'), $emd_id);
        $data['policy_pages'] = $this->Dashboard_model->get_tlc_policy_pages($data['policy_list'][0]['empg_id']);
        $data['empg_id'] = $data['policy_list'][0]['empg_id'];

        echo view('templates/header_view', $data);
        echo view('etrack/dashboard_policy_details', $data);
        echo view('templates/footer_view');
    }

    public function accept_policy()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['emd_id'])) {
            $emd_id = $_POST['emd_id'];
        } else {
            session()->setFlashdata('error', 'No policy selected.');
            return redirect()->to(base_url() . 'etrack/dashboard/policies');
            // exit();
        }
        $user_id = session()->get('id_user');
        $newdata = [
            'user_id' => $user_id,
            'emd_id' => $emd_id,
            'accepted_on' => time()
        ];
        $this->Dashboard_model->accept_policy($newdata);
        session()->setFlashdata('success', lang('Messages.Success_0027'));
        return redirect()->to(base_url() . 'etrack/dashboard/policies');
    }
    public function view_policy_by_id()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['emd_id'])) {
            $emd_id = $_POST['emd_id'];
            $_SESSION['emd_id'] = $emd_id;
            $empg_id = $_POST['empg_id'];
            $_SESSION['empg_id'] = $empg_id;
        } elseif (isset($_SESSION['emd_id'])) {
            $emd_id = $_SESSION['emd_id'];
            $empg_id = $_SESSION['empg_id'];
        } else {
            return redirect()->to(base_url() . 'etrack/dashboard/policies');
            exit();
        }
        //$emd_id = $_POST['emd_id'];
        $data['emd_id'] = $emd_id;
        $data['empg_id'] = $empg_id;

        $data['policy_name'] = $this->Dashboard_model->get_tlc_policy_name($emd_id);
        $data['policy_list'] = $this->Dashboard_model->get_tlc_policy_details($emd_id);
        $data['policy_pages'] = $this->Dashboard_model->get_tlc_policy_pages($empg_id);
        $data['policy_accepted'] = $this->Dashboard_model->check_policy_accepted(session()->get('id_user'), $emd_id);



        echo view('templates/header_view', $data);
        echo view('etrack/dashboard_policy_details', $data);
        echo view('templates/footer_view');
    }

    public function org_chart()
    {
        $data = [];
        helper(['form']);

        if (isset($_POST['temp_user'])) {
            $temp_user = $_POST['temp_user'];
        } else {
            $temp_user = session()->get('id_user');
        }

        $data['my_manager'] = $this->Dashboard_model->getmymymanager($temp_user);
        $data['about_me'] = $this->Dashboard_model->aboutme($temp_user);
        $data['my_reportees'] = $this->Dashboard_model->getmyReportees($temp_user);

        echo view('templates/header_view');
        echo view('etrack/dashboard_org', $data);
        echo view('templates/footer_view');
    }
}
