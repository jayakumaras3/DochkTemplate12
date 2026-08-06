<?php

namespace App\Controllers\Demo;

use App\Controllers\BaseController;

use App\Models\SCORM\Scorm_dashboard_model;
use App\Models\SCORM\Scorm_course_model;
use App\Models\SCORM\Scorm_metacategory_model;

#[\AllowDynamicProperties]

class Demo_dashboard extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->is_session_available();
        $this->scorm_dashboard_model = new Scorm_dashboard_model();
        $this->scorm_course_model = new Scorm_course_model();
        $this->scorm_metacategory_model = new Scorm_metacategory_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url('my_training'));
            exit();
        }

        $arrayuserlevel = explode(',', $userlevel);
        if (!in_array('8', $arrayuserlevel)) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }
    public function index()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $data = [];
        $data['header'] = 'Demo Dashboard';
        $data['demo'] = 1;
        $user = session()->get('id_user');
        $data['cart_count'] = $this->scorm_dashboard_model->getUserCartCount($user);
        $data['getSalesReportCount'] = $this->scorm_dashboard_model->getSalesReportCount($user);
        $data['categoryData'] = $this->scorm_metacategory_model->getCategoryData();
        $data['coursesDetails'] = $this->scorm_dashboard_model->democoursenamessearch_view();
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_dashboard/demo_search_view', $data);
        echo view('templates/footer_view');
    }
    function assignDemouserstoAllcourses($user_id)
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $data = [];
        $this->scorm_dashboard_model->assignDemoUserstoAllCourses($user_id);
    }
    public function searchBycourseName()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['header'] = 'Demo Dashboard';
        $user = session()->get('id_user');

        $course_name = trim($this->request->getPost('course_name')); // remove spaces before/after

        if (empty($course_name)) {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url('Demo/demo_dashboard'));
        } else {
            $data['cart_count'] = $this->scorm_dashboard_model->getUserCartCount($user);
            $data['getSalesReportCount'] = $this->scorm_dashboard_model->getSalesReportCount($user);
            $data['coursesDetails'] = $this->scorm_dashboard_model->democoursenamessearch_view($course_name);
            $data['categoryData'] = $this->scorm_metacategory_model->getCategoryData();
        }
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_dashboard/demo_search_view', $data);
        echo view('templates/footer_view');
    }
    public function searchBycourseCategory()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['header'] = 'Demo Dashboard';
        $user = session()->get('id_user');
        $category = $this->request->getPost('category');
        // print_r($category);
        // exit();
        $data['cart_count'] = $this->scorm_dashboard_model->getUserCartCount($user);
        $data['getSalesReportCount'] = $this->scorm_dashboard_model->getSalesReportCount($user);
        $data['categoryData'] = $this->scorm_metacategory_model->getCategoryData();
        $data['coursesDetails'] = $this->scorm_dashboard_model->democourseCategoryssearch_view($category);
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_dashboard/demo_search_view', $data);
        echo view('templates/footer_view');
    }
}
