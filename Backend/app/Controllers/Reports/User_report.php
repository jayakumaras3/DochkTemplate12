<?php

namespace App\Controllers\Reports;

use App\Controllers\BaseController;

use App\Models\Report\Report_model;
use CodeIgniter\I18n\Time;

#[\AllowDynamicProperties]
class User_report extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->report_model = new Report_model();
    }
    // private function is_session_available()
    // {
    //     $userlevel = session()->get('userlevel');
    //     if (empty($userlevel)) {
    //         header('Location:' . base_url('my_training'));
    //         exit();
    //     }

    //     $arrayuserlevel = explode(',', $userlevel);
    //     if (!in_array('6', $arrayuserlevel) && !in_array('73', $arrayuserlevel)) {
    //         session()->setFlashdata('error', lang('Messages.Error_0004'));
    //         header('Location:' . base_url('my_training'));
    //         exit();
    //     }
    // }
    public function index() //fetch data from users table to display
    {
        if ($response =  $this->requireRole(['5', '44', '3'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $id_user = session()->get('id_user');
        $data['status'] = $this->report_model->getStatusCountCourseUsers($id_user);
        // print_r($data['status']);
        // exit();
        $this->report_model->getReportUsergraphCompeted($id_user);
        $data['report_user_graph_completed'] = $this->report_model->reportUsergraphCompeted($id_user);

        echo view('templates/header_view', $data);
        echo view('reports/user_report_view', $data);
        echo view('templates/footer_view');
    }
    public function course_reports() //fetch data from users table to display
    {
        if ($response =  $this->requireRole(['5', '44', '3'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $id_user = session()->get('id_user');
        $data['status'] = $this->report_model->getStatusCountCourseUsers($id_user);
        // print_r($data['status']);
        // exit();
        // $this->report_model->getReportUsergraphCompeted($id_user);
        // $data['report_user_graph_completed'] = $this->report_model->reportUsergraphCompeted($id_user);

        echo view('templates/header_view', $data);
        echo view('reports/course_report_view', $data);
        echo view('templates/footer_view');
    }

    function report_user_graph_completed()
    {
        if ($response =  $this->requireRole(['5', '44', '3'])) {
            return $response;
        }
        $id_user = session()->get('id_user');
        $result = $this->report_model->getReportUsergraphCompeted($id_user);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0041'));
            return redirect()->to(base_url('my_training/report'));
        } else {
            session()->setFlashdata('error', 'Error');
            return redirect()->to(base_url('my_training/report'));
        }
    }
    function report_user_graph_time()
    {
        if ($response =  $this->requireRole(['5', '44', '3'])) {
            return $response;
        }

        $id_user = session()->get('id_user');
        $result = $this->scorm_client_model->reportUserGraphTime($id_user);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0041'));
            return redirect()->to(base_url('my_training/report'));
        } else {
            // echo 'error';
            session()->setFlashdata('error', 'Error');
            return redirect()->to(base_url('my_training/report'));
        }
    }
}
