<?php

namespace App\Controllers\Reports;

use App\Controllers\BaseController;

use App\Models\Report\Report_model;
use App\Models\SCORM\Scorm_client_model;
use CodeIgniter\I18n\Time;

#[\AllowDynamicProperties]
class Client_reports extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->is_session_available();
        $this->report_model = new Report_model();
        $this->scorm_client_model = new Scorm_client_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url());
            exit();
        }

        $arrayuserlevel = explode(',', $userlevel);

        if (!in_array('5', $arrayuserlevel) && !in_array('44', $arrayuserlevel)) {

            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }
    public function index() //fetch data from users table to display
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $clientid = session()->get('client');

        $currentYear = (int) date('Y');
        $selectedYear = (int) ($this->request->getGet('year') ?: $currentYear);
        if ($selectedYear < 2000 || $selectedYear > $currentYear) {
            $selectedYear = $currentYear;
        }
        $selectedMonth = (int) ($this->request->getGet('month') ?: date('n'));

        $data['total_users'] = $this->report_model->total_users($clientid);
        $data['total_courses'] =  $this->report_model->client_courses($clientid);
        $data['total_completed'] = $this->report_model->course_status_values($clientid, 2, 3);
        $data['total_inprogress'] = $this->report_model->course_status_values($clientid, 1, 0);
        $data['total_not_started'] = $this->report_model->course_status_values($clientid, 0, 0);
        // $data['total_assgned_courses'] = $this->report_model->get_assigned_courses($clientid);


        $data['completed_data'] = $this->report_model->checkrowexists($clientid, 2, $selectedYear);

        //Graph Report
        $data['Year'] = $selectedYear;
        $data['current_year'] = $currentYear;
        $data['selected_month'] = $selectedMonth;
        echo view('templates/header_view', $data);
        echo view('reports/client_report_view', $data);
        echo view('templates/footer_view');
    }

    public function detail_report($type = null, $reviewed = null)
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $clientid = session()->get('client');


        $data['type'] = $type;
        $data['data_values'] = $this->report_model->course_assigned_report($clientid, $type, $reviewed);


        echo view('templates/header_view', $data);
        echo view('reports/client_assigned_report', $data);
        echo view('templates/footer_view');
    }

    public function assigned_report()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $clientid = session()->get('client');
        $data['type']  = 10;

        $data['data_values'] = $this->report_model->course_assigned_report($clientid, 10);

        echo view('templates/header_view', $data);
        echo view('reports/client_assigned_report', $data);
        echo view('templates/footer_view');
    }
    public function lp() //fetch data from users table to display
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $clientid = session()->get('client');

        $data['total_users'] = $this->report_model->total_users($clientid);
        $data['total_courses'] =  0;
        $data['total_completed'] = 0;
        $data['total_inprogress'] = 0;

        echo view('templates/header_view', $data);
        echo view('reports/client_lp_report_view', $data);
        echo view('templates/footer_view');
    }

    public function cert() //fetch data from users table to display
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $clientid = session()->get('client');

        $data['total_users'] = $this->report_model->total_users($clientid);
        $data['total_courses'] =  0;
        $data['total_completed'] = 0;
        $data['total_inprogress'] = 0;

        echo view('templates/header_view', $data);
        echo view('reports/client_cer_report_view', $data);
        echo view('templates/footer_view');
    }

    public function update_graph()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $clientid = session()->get('client');

        if (!isset($_POST['month'])) {
            session()->setFlashdata('error', 'Error');
            return redirect()->to(base_url('Reports/client_reports'));
        }

        $month =  $_POST['month'];
        $monthupdate = 'M' . $month;
        $year = $_POST['Year'];

        if (strlen($month) == 1) {
            $month = '0' . $month;
        }
        $startdate = $year . '-' . $month . '-01';
        $enddate =  date('Y-m-d', mktime(0, 0, 0, $month + 1, 0, $year));

        $this->report_model->update_client_id($clientid);

        $data['completed'] = $this->report_model->course_status_values_graph($clientid, 2, $startdate, $enddate);
        $data['inprogress'] = $this->report_model->course_status_values_graph($clientid, 1, $startdate, $enddate);
        $data['not_started'] = $this->report_model->course_status_values_graph($clientid, 0, $startdate, $enddate);


        //Insert completed data
        $rowexists = $this->report_model->checkrowexists($clientid, 2, $year);
        if ($rowexists) {
            $update_data = [
                $monthupdate =>  $data['completed'],
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
            ];
            $this->report_model->update_graph_data_value($rowexists[0]['rg_id'], $update_data);
        } else {
            $insert_data = [
                'status' => 1,
                'client_id' => $clientid,
                'year' => $year,
                'type' => 2,
                $monthupdate =>  $data['completed'],
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
            ];
            $this->report_model->insert_graph_data_value($insert_data);
        }

        //Insert In Progress data
        $rowexists1 = $this->report_model->checkrowexists($clientid, 1, $year);
        if ($rowexists1) {
            $update_data = [
                $monthupdate =>  $data['inprogress'],
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
            ];
            $this->report_model->update_graph_data_value($rowexists1[0]['rg_id'], $update_data);
        } else {
            $insert_data = [
                'status' => 1,
                'client_id' => $clientid,
                'year' => $year,
                'type' => 1,
                $monthupdate =>  $data['inprogress'],
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
            ];
            $this->report_model->insert_graph_data_value($insert_data);
        }
        //Insert Not Started data
        $rowexists0 = $this->report_model->checkrowexists($clientid, 0, $year);
        if ($rowexists0) {
            $update_data = [
                $monthupdate =>  $data['not_started'],
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
            ];
            $this->report_model->update_graph_data_value($rowexists0[0]['rg_id'], $update_data);
        } else {
            $insert_data = [
                'status' => 1,
                'client_id' => $clientid,
                'year' => $year,
                'type' => 0,
                $monthupdate =>  $data['not_started'],
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
            ];
            $this->report_model->insert_graph_data_value($insert_data);
        }


        session()->setFlashdata('success', lang('Messages.Success_0008'));
        return redirect()->to(base_url('Reports/client_reports') . '?year=' . $year . '&month=' . $_POST['month']);
    }

    public function report_course_report()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['users'])) {
            $data['users'] =  $_POST['users'];
            $_SESSION['users'] = $data['users'];
        } elseif (isset($_SESSION['users'])) {
            $data['users'] =  $_SESSION['users'];
        } else {
            session()->setFlashdata('error', 'Error');
            return redirect()->to(base_url('Reports/client_reports'));
        }
        $data['id_user'] =   $data['users'];
        $data['userid'] = $data['id_user'];
        $data['encode'] = base64_encode($data['id_user']);
        $client_id = session()->get('client');
        $data['client_id'] = $client_id;
        $data['username'] = $this->scorm_client_model->getUserName($data['id_user']);
        $data['getlatestCoursesForUsers'] = $this->scorm_client_model->getUserlatestCourseUsers($data['id_user']);
        $data['getAllCoursesForClient'] = $this->scorm_client_model->getClientCourses($data['client_id']);
        echo view('templates/header_view');
        echo view('users/users_courselatest_report_view', $data);
        echo view('templates/footer_view');
    }

    public function monthly_report()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (!isset($_POST['month'])) {
            session()->setFlashdata('error', 'Error');
            return redirect()->to(base_url('Reports/client_reports'));
        }
        $month =  $_POST['month'];
        $year = $_POST['Year'];

        if (strlen($month) == 1) {
            $month = '0' . $month;
        }
        $startdate = $year . '-' . $month . '-01';
        // print_r($startdate);
        // exit();
        $enddate =  date('Y-m-d', mktime(0, 0, 0, $month + 1, 0, $year));

        $client_id = session()->get('client');
        $data['client_id'] = $client_id;
        $data['date_range'] = $startdate . ' - ' . $enddate;
        $data['full_report'] = $this->report_model->get_monthly_reports($client_id, $startdate, $enddate);

        echo view('templates/header_view');
        echo view('reports/client_montly_report_view', $data);
        echo view('templates/footer_view');
    }
}
