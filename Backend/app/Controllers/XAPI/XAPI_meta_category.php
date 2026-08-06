<?php

namespace App\Controllers\XAPI;

use App\Controllers\BaseController;

use App\Models\Settings\Dropdown_model;
use App\Models\SCORM\Scorm_course_model;
use App\Models\SCORM\Scorm_metacategory_model;

#[\AllowDynamicProperties]
class XAPI_meta_category extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->dropdown_model = new Dropdown_model();
        $this->scorm_course_model = new Scorm_course_model();
        $this->scorm_metacategory_model = new Scorm_metacategory_model();
    }
    public function index()
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'AR/VR/Sim Courses';
        $data['header_link'] = 'XAPI/XAPI_courses';
        $data['sub_header_1'] = 'AR/VR/Sim Meta Data';
        $data['form_link'] = 'XAPI/XAPI_meta_category/addmetaval';
        $data['form_link_1'] = 'XAPI/XAPI_meta_category/meta_courses';
        $data['form_link_2'] = 'XAPI/XAPI_meta_category/meta_edit_view';
        $data['form_link_3'] = 'XAPI/XAPI_meta_category/deletemetadetails';
        $data['type'] = 1;

        $data['form_title'] = 'Meta Data';

        $data['metadata'] = $this->scorm_metacategory_model->getMetadata(9);

        echo view('templates/header_view', $data);
        echo view('XAPI_meta_category/metadata_view', $data);
        echo view('templates/footer_view');
    }
    public function addmetaval()
    {
        $data = [];
        helper(['form']);
        $user = session()->get('username');
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
            return redirect()->to(base_url() . '/XAPI/XAPI_meta_category');
        }
    }
    public function meta_edit_view()
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'AR/VR/Sim Courses';
        $data['header_link'] = 'XAPI/XAPI_courses';
        $data['sub_header_1'] = 'AR/VR/Sim Meta Data';
        $data['sub_header_1_link'] = 'XAPI/XAPI_meta_category';
        $data['sub_header_2'] = 'AR/VR/Sim Meta Edit';

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
        $data['form_link'] = 'XAPI/XAPI_meta_category/editmetaval?sc_mcid=' . $getmetaData[0]['sc_mcid'];
        echo view('templates/header_view', $data);
        echo view('XAPI_meta_category/meta_edit_view', $data);
        echo view('templates/footer_view');
    }
    public function editmetaval()
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
            return redirect()->to(base_url() . '/XAPI/XAPI_meta_category');
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
        return redirect()->to(base_url() . '/XAPI/XAPI_meta_category');
    }
    public function category()
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'AR/VR/Sim Courses';
        $data['header_link'] = 'XAPI/XAPI_courses';
        $data['sub_header_1'] = 'AR/VR/Sim Category';

        $data['form_title'] = 'Category';

        $data['form_link'] = 'XAPI/XAPI_meta_category/addcategoryval';
        $data['form_link_1'] = 'XAPI/XAPI_meta_category/category_courses';
        $data['form_link_2'] = 'XAPI/XAPI_meta_category/category_edit_view';
        $data['form_link_3'] = 'XAPI/XAPI_meta_category/deleteCategorydetails';
        $data['type'] = 2;

        $data['metadata'] = $this->scorm_metacategory_model->getMetadata(10);

        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_meta_category/metadata_view', $data);
        echo view('templates/footer_view');
    }
    public function addcategoryval()
    {
        $data = [];
        helper(['form']);
        $user = session()->get('username');
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
                    'typeofval' => 10, //category data
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
            return redirect()->to(base_url() . '/XAPI/XAPI_meta_category/category');
        }
    }
    public function category_edit_view()
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'AR/VR/Sim Courses';
        $data['header_link'] = 'XAPI/XAPI_courses';
        $data['sub_header_1'] = 'AR/VR/Sim Category';
        $data['sub_header_1_link'] = 'XAPI/XAPI_meta_category/category';
        $data['sub_header_2'] = 'AR/VR/Sim Category Edit';

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
        $data['form_link'] = 'XAPI/XAPI_meta_category/editcategoryval?sc_mcid=' . $getmetaData[0]['sc_mcid'];

        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_meta_category/category_edit_view', $data);
        echo view('templates/footer_view');
    }
    public function category_courses()
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'AR/VR/Sim Courses';
        $data['header_link'] = 'XAPI/XAPI_courses';
        $data['sub_header_1'] = 'AR/VR/Sim Category';
        $data['sub_header_1_link'] = 'XAPI/XAPI_meta_category/category';
        $data['sub_header_2'] = 'AR/VR/Sim Category Courses';

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
        $data['form_link'] = 'XAPI/XAPI_courses/course_edit_view';

        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_meta_category/category_course_view', $data);
        echo view('templates/footer_view');
    }
    public function meta_courses()
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'AR/VR/Sim Courses';
        $data['header_link'] = 'XAPI/XAPI_courses';
        $data['sub_header_1'] = 'C4U Meta Data';
        $data['sub_header_1_link'] = 'XAPI/XAPI_meta_category';
        $data['sub_header_2'] = 'C4U Meta Courses';
        $data['form_link'] = 'XAPI/XAPI_courses/course_edit_view';

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
        echo view('XAPI_meta_category/meta_course_view', $data);
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
            return redirect()->to(base_url() . '/XAPI/XAPI_meta_category/category');
        }
    }
    public function deleteCategorydetails()
    {
        $sc_mcid = $_POST['sc_mcid'];
        $newdata = [
            'last_updated_on' => time(),
            'last_updated_by' =>  session()->get('id_user'),
            'status' => '0',
        ];
        $result = $this->scorm_metacategory_model->editmetacatdetails($newdata, $sc_mcid);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . '/XAPI/XAPI_meta_category/category');
    }
}
