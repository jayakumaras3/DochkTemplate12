<?php

namespace App\Controllers\Assessment;

use App\Controllers\BaseController;

use App\Models\Settings\Dropdown_model;
use App\Models\SCORM\Scorm_course_model;
use App\Models\SCORM\Scorm_metacategory_model;

#[\AllowDynamicProperties]
class Assessment_meta_category extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->is_session_available();
        $this->dropdown_model = new Dropdown_model();
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
        if (!in_array('6', $arrayuserlevel) && !in_array('98', $arrayuserlevel)) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }
    public function index()
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'Assessment';
        $data['header_link'] = 'Assessment/trainings';
        $data['sub_header_1'] = 'Assessment Meta Data';
        $data['form_link'] = 'Assessment/assessment_meta_category/addmetaval';
        $data['form_link_1'] = 'Assessment/assessment_meta_category/meta_courses';
        $data['form_link_2'] = 'Assessment/assessment_meta_category/meta_edit_view';
        $data['form_link_3'] = 'Assessment/assessment_meta_category/deletemetadetails';
        $data['type'] = 11;

        $data['form_title'] = 'Meta Data';

        $data['metadata'] = $this->scorm_metacategory_model->getMetadata(11);

        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_meta_category/metadata_view', $data);
        echo view('templates/footer_view');
    }
    public function addmetaval()
    {
        $data = [];
        helper(['form']);
        if ($this->request->getPost()) {
            $rules = [
                'description' => 'required',
            ];
            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
            } else {
                $timestamp = time();
                $newdata = [
                    'description' => $this->request->getVar('description'),
                    'typeofval' => $this->request->getVar('type'),
                    'status' => '1',
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    'last_updated_by' =>  session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                $result = $this->scorm_metacategory_model->addmetadetails($newdata);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0008'));
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
            return redirect()->to(base_url() . '/Assessment/assessment_meta_category');
        }
    }
    public function meta_edit_view()
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'Assessment';
        $data['header_link'] = 'Assessment/scorm_courses';
        $data['sub_header_1'] = 'Assessment Meta Data';
        $data['sub_header_1_link'] = 'Assessment/assessment_meta_category';
        $data['sub_header_2'] = 'Assessment Meta Edit';

        $user = session()->get('username');
        if (isset($_POST['sc_mcid'])) {
            $data['sc_mcid'] = $_POST['sc_mcid'];
            $_SESSION['sc_mcid'] =  $data['sc_mcid'];
        } else if (isset($_GET['sc_mcid'])) {
            $data['sc_mcid'] = $_GET['sc_mcid'];
        } else if (isset($_SESSION['sc_mcid'])) {
            $data['sc_mcid'] = $_SESSION['sc_mcid'];
        }
        $getmetaData = $this->scorm_metacategory_model->getmetaDetails($data['sc_mcid']);
        $data['row'] = $getmetaData;
        $data['form_link'] = 'Assessment/assessment_meta_category/editmetaval?sc_mcid=' . $getmetaData[0]['sc_mcid'];
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_meta_category/meta_edit_view', $data);
        echo view('templates/footer_view');
    }
    public function editmetaval()
    {
        $data = [];
        helper(['form']);
        $sc_mcid = $this->request->getVar('sc_mcid');
        $data['sc_mcid'] = $sc_mcid;
        $getmetaData = $this->scorm_metacategory_model->getmetaDetails($data['sc_mcid']);
        $data['row'] = $getmetaData;
        if ($this->request->getPost()) {
            $rules = [
                'description' => 'required',
            ];
            if (!$this->validate($rules)) {
                $data['courseeditvalidation'] = $this->validator;
            } else {
                $timestamp = time();
                $newdata = [
                    'description' => $this->request->getVar('description'),
                ];
                $result = $this->scorm_metacategory_model->editmetacatdetails($newdata, $sc_mcid);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0008'));
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
            return redirect()->to(base_url() . '/Assessment/assessment_meta_category');
        }
    }
    public function deletemetadetails()
    {
        $sc_mcid = $_POST['sc_mcid'];
        $newdata = [
            'status' => '0',
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_metacategory_model->editmetacatdetails($newdata, $sc_mcid);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . '/Assessment/assessment_meta_category');
    }
    public function category()
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'Assessment';
        $data['header_link'] = 'Assessment/trainings';
        $data['sub_header_1'] = 'Assessment Category';

        $data['form_title'] = 'Category';

        $data['form_link'] = 'Assessment/assessment_meta_category/addcategoryval';
        $data['form_link_1'] = 'Assessment/assessment_meta_category/category_courses';
        $data['form_link_2'] = 'Assessment/assessment_meta_category/category_edit_view';
        $data['form_link_3'] = 'Assessment/assessment_meta_category/deleteCategorydetails';
        $data['type'] = 12;

        $data['metadata'] = $this->scorm_metacategory_model->getMetadata(12);

        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_meta_category/metadata_view', $data);
        echo view('templates/footer_view');
    }
    public function addcategoryval()
    {
        $data = [];
        helper(['form']);
        if ($this->request->getPost()) {
            //print_r("sss");
            $rules = [
                'description' => 'required',
            ];

            if (!$this->validate($rules)) {
                //print_r("rrr");
                $data['validation'] = $this->validator;
            } else {
                //print_r("ttt");
                $timestamp = time();
                $newdata = [
                    'description' => $this->request->getVar('description'),
                    'typeofval' => 12, //category data
                    'status' => '1',
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    'last_updated_by' =>  session()->get('id_user'),
                    'last_updated_on' => time(),

                ];
                $result = $this->scorm_metacategory_model->addmetadetails($newdata);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0008'));
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
            return redirect()->to(base_url() . '/Assessment/assessment_meta_category/category');
        }
    }
    public function category_edit_view()
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'Assessment';
        $data['header_link'] = 'Assessment/trainings';
        $data['sub_header_1'] = 'Assessment Category';
        $data['sub_header_1_link'] = 'Assessment/assessment_meta_category/category';
        $data['sub_header_2'] = 'Assessment Category Edit';

        if (isset($_POST['sc_mcid'])) {
            $data['sc_mcid'] = $_POST['sc_mcid'];
            $_SESSION['sc_mcid'] =  $data['sc_mcid'];
        } else if (isset($_GET['sc_mcid'])) {
            $data['sc_mcid'] = $_GET['sc_mcid'];
        } else if (isset($_SESSION['sc_mcid'])) {
            $data['sc_mcid'] = $_SESSION['sc_mcid'];
        }
        $getmetaData = $this->scorm_metacategory_model->getmetaDetails($data['sc_mcid']);
        $data['row'] = $getmetaData;
        $data['form_link'] = 'Assessment/assessment_meta_category/editcategoryval?sc_mcid=' . $getmetaData[0]['sc_mcid'];

        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_meta_category/category_edit_view', $data);
        echo view('templates/footer_view');
    }
    public function category_courses()
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'Assessment Courses';
        $data['header_link'] = 'Assessment/scorm_courses';
        $data['sub_header_1'] = 'Assessment Category';
        $data['sub_header_1_link'] = 'Assessment/assessment_meta_category/category';
        $data['sub_header_2'] = 'Assessment Category';

        if (isset($_POST['sc_mcid'])) {
            $data['sc_mcid'] = $_POST['sc_mcid'];
            $_SESSION['sc_mcid'] =  $data['sc_mcid'];
        } else if (isset($_GET['sc_mcid'])) {
            $data['sc_mcid'] = $_GET['sc_mcid'];
        } else if (isset($_SESSION['sc_mcid'])) {
            $data['sc_mcid'] = $_SESSION['sc_mcid'];
        }
        $getmetaData = $this->scorm_metacategory_model->getAllCoursesByCategory($data['sc_mcid']);
        $data['row'] = $getmetaData;
        $data['form_link'] = 'Assessment/scorm_courses/course_edit_view';

        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_meta_category/category_course_view', $data);
        echo view('templates/footer_view');
    }
    public function meta_courses()
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'Assessment Courses';
        $data['header_link'] = 'Assessment/scorm_courses';
        $data['sub_header_1'] = 'Assessment Meta Data';
        $data['sub_header_1_link'] = 'Assessment/assessment_meta_category';
        $data['sub_header_2'] = 'Assessment Meta';
        $data['form_link'] = 'Assessment/scorm_courses/course_edit_view';

        if (isset($_POST['sc_mcid'])) {
            $data['sc_mcid'] = $_POST['sc_mcid'];
            $_SESSION['sc_mcid'] =  $data['sc_mcid'];
        } else if (isset($_GET['sc_mcid'])) {
            $data['sc_mcid'] = $_GET['sc_mcid'];
        } else if (isset($_SESSION['sc_mcid'])) {
            $data['sc_mcid'] = $_SESSION['sc_mcid'];
        }
        $getmetaData = $this->scorm_metacategory_model->getAllCoursesByCategory($data['sc_mcid']);
        $data['row'] = $getmetaData;
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_meta_category/meta_course_view', $data);
        echo view('templates/footer_view');
    }
    public function editcategoryval()
    {
        $data = [];
        helper(['form']);
        $user = session()->get('username');
        $sc_mcid = $this->request->getVar('sc_mcid');
        $data['sc_mcid'] = $sc_mcid;
        $getmetaData = $this->scorm_metacategory_model->getmetaDetails($data['sc_mcid']);
        $data['row'] = $getmetaData;
        if ($this->request->getPost()) {
            $rules = [
                'description' => 'required',
            ];
            if (!$this->validate($rules)) {
                $data['courseeditvalidation'] = $this->validator;
            } else {
                $timestamp = time();
                $newdata = [
                    'description' => $this->request->getVar('description'),
                ];
                $result = $this->scorm_metacategory_model->editmetacatdetails($newdata, $sc_mcid);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0008'));
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
            return redirect()->to(base_url() . '/Assessment/assessment_meta_category/category');
        }
    }
    public function deleteCategorydetails()
    {
        $sc_mcid = $_POST['sc_mcid'];
        $newdata = [
            'status' => '0',
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_metacategory_model->editmetacatdetails($newdata, $sc_mcid);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . '/Assessment/assessment_meta_category/category');
    }
}
