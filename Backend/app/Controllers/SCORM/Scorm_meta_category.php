<?php

namespace App\Controllers\SCORM;

use App\Controllers\BaseController;

use App\Models\Settings\Dropdown_model;
use App\Models\SCORM\Scorm_course_model;
use App\Models\SCORM\Scorm_metacategory_model;

#[\AllowDynamicProperties]
class Scorm_meta_category extends BaseController
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
        if (!in_array('6', $arrayuserlevel) && !in_array('73', $arrayuserlevel)) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }
    public function index()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
        $data = [];
        helper(['form']);


        $data['header'] = 'Dashboard';
        $data['header_link'] = 'SCORM/my_training';
        $data['sub_header_1'] = 'Meta Data';
        $data['form_link'] = 'SCORM/scorm_meta_category/addmetaval';
        $data['form_link_1'] = 'SCORM/scorm_meta_category/meta_courses';
        $data['form_link_2'] = 'SCORM/scorm_meta_category/meta_edit_view';
        $data['form_link_3'] = 'SCORM/scorm_meta_category/deletemetadetails';
        $data['type'] = 1;

        $data['form_title'] = 'Meta Data';

        $data['metadata'] = $this->scorm_metacategory_model->getMetadata(1);

        echo view('templates/header_view', $data);

        echo view('SCORM/scorm_meta_category/metadata_view', $data);
        echo view('templates/footer_view');
    }
    public function addmetaval()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
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
            return redirect()->to(base_url() . '/SCORM/scorm_meta_category');
        }
    }
    public function meta_edit_view()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['header'] = 'Courses';
        $data['header_link'] = 'SCORM/scorm_courses';
        $data['sub_header_1'] = 'Meta Data';
        $data['sub_header_1_link'] = 'SCORM/scorm_meta_category';
        $data['sub_header_2'] = 'Meta Edit';

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
        $data['form_link'] = 'SCORM/scorm_meta_category/editmetaval?sc_mcid=' . $getmetaData[0]['sc_mcid'];
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_meta_category/meta_edit_view', $data);
        echo view('templates/footer_view');
    }
    public function editmetaval()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
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
            return redirect()->to(base_url() . '/SCORM/scorm_meta_category');
        }
    }
    public function deletemetadetails()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
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
        return redirect()->to(base_url() . '/SCORM/scorm_meta_category');
    }
    public function category()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data = [
            'link1' => '',
            'link1_name' => '',
            'link2' => '',
            'link2_name' => '',
            'link3_name' => 'Category'
        ];
        $data['header'] = 'Courses';
        $data['header_link'] = 'SCORM/scorm_courses';
        $data['sub_header_1'] = 'Category';

        $data['form_title'] = 'Category';

        $data['form_link'] = 'SCORM/scorm_meta_category/addcategoryval';
        $data['form_link_1'] = 'SCORM/scorm_meta_category/category_courses';
        $data['form_link_2'] = 'SCORM/scorm_meta_category/category_edit_view';
        $data['form_link_3'] = 'SCORM/scorm_meta_category/deleteCategorydetails';
        $data['form_link_4'] = 'SCORM/scorm_meta_category/addclientstocategoryview';

        $data['type'] = 2;

        $data['metadata'] = $this->scorm_metacategory_model->getMetadata(2);
        echo view('templates/header_view', $data);
        // echo view('settings/settings_left_menu', $data);
        echo view('SCORM/scorm_meta_category/metadata_view', $data);
        echo view('templates/footer_view');
    }
    public function addcategoryval()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
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
                    'details' => $this->request->getVar('details'),
                    'typeofval' => 2, //category data
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
            return redirect()->to(base_url() . '/SCORM/scorm_meta_category/category');
        }
    }
    public function category_edit_view()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data = [
            'link1' => 'my_training',
            'link1_name' => 'Dashboard',
            'link2' => 'SCORM/scorm_meta_category/category',
            'link2_name' => 'Category',
            'link3_name' => 'Edit Category'
        ];
        $data['header'] = 'Courses';
        $data['header_link'] = 'SCORM/scorm_courses';
        $data['sub_header_1'] = 'Category';
        $data['sub_header_1_link'] = 'SCORM/scorm_meta_category/category';
        $data['sub_header_2'] = 'Category Edit';

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
        $data['form_link'] = 'SCORM/scorm_meta_category/editcategoryval?sc_mcid=' . $getmetaData[0]['sc_mcid'];

        echo view('templates/header_view', $data);
        //echo view('settings/settings_left_menu', $data);
        echo view('SCORM/scorm_meta_category/category_edit_view', $data);
        echo view('templates/footer_view');
    }
    public function category_courses()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data = [
            'link1' => 'my_training',
            'link1_name' => 'Dashboard',
            'link2' => 'SCORM/scorm_meta_category/category',
            'link2_name' => 'Category',
            'link3_name' => 'Category Courses'
        ];
        $data['header'] = 'Courses';
        $data['header_link'] = 'SCORM/scorm_courses';
        $data['sub_header_1'] = 'Category';
        $data['sub_header_1_link'] = 'SCORM/scorm_meta_category/category';
        $data['sub_header_2'] = 'Category Courses';

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
        $data['form_link'] = 'SCORM/scorm_courses/course_edit_view';

        echo view('templates/header_view', $data);
        //echo view('settings/settings_left_menu', $data);
        echo view('SCORM/scorm_meta_category/category_course_view', $data);
        echo view('templates/footer_view');
    }
    public function meta_courses()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data = [
            'link1' => 'my_training',
            'link1_name' => 'Dashboard',
            'link2' => '',
            'link2_name' => '',
            'link3_name' => 'Category'
        ];
        $data['header'] = 'Courses';
        $data['header_link'] = 'SCORM/scorm_courses';
        $data['sub_header_1'] = 'Meta Data';
        $data['sub_header_1_link'] = 'SCORM/scorm_meta_category';
        $data['sub_header_2'] = 'Meta Courses';
        $data['form_link'] = 'SCORM/scorm_courses/course_edit_view';

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
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
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
                    'details' => $this->request->getVar('details'),
                ];
                $result = $this->scorm_metacategory_model->editmetacatdetails($newdata, $sc_mcid);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0008'));
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
            return redirect()->to(base_url() . 'SCORM/scorm_meta_category/category');
        }
    }
    public function deleteCategorydetails()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
        $sc_mcid = $_POST['sc_mcid'];
        $newdata = [
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => '0',
        ];
        $result = $this->scorm_metacategory_model->editmetacatdetails($newdata, $sc_mcid);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . 'SCORM/scorm_meta_category/category');
    }
    public function addclientstocategoryview()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data = [
            'link1' => 'my_training',
            'link1_name' => 'Dashboard',
            'link2' => 'SCORM/scorm_meta_category/category',
            'link2_name' => 'Category',
            'link3_name' => 'Add Client to Category'
        ];
        if (isset($_POST['sc_mcid'])) {
            $data['sc_mcid'] = $_POST['sc_mcid'];
            $_SESSION['sc_mcid'] =  $data['sc_mcid'];
        } else if (isset($_GET['sc_mcid'])) {
            $data['sc_mcid'] = $_GET['sc_mcid'];
        } else if (isset($_SESSION['sc_mcid'])) {
            $data['sc_mcid'] = $_SESSION['sc_mcid'];
        }
        $data['clientData'] = $this->dropdown_model->getclientData();
        $data['Clientsofcategory'] = $this->scorm_metacategory_model->getClientsofcategory($data['sc_mcid']);
        echo view('templates/header_view', $data);
        //  echo view('settings/settings_left_menu', $data);
        echo view('SCORM/scorm_meta_category/addclients_to_category_view', $data);
        echo view('templates/footer_view');
    }
    public function addclienttocategory()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if ($this->request->getPost()) {
            //print_r("sss");
            $rules = [
                'client' => 'required',
            ];

            if (!$this->validate($rules)) {
                //print_r("rrr");
                $data['validation'] = $this->validator;
            } else {
                //print_r("ttt");
                $timestamp = time();
                $newdata = [
                    'client' => $this->request->getPost('client'),
                    'category_id' =>  $this->request->getPost('category_id'), //category data
                    'status' => '1',
                    'last_updated_by' =>  session()->get('id_user'),
                    'last_updated_on' => time(),

                ];
                $result = $this->scorm_metacategory_model->addclienttocategory($newdata);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0008'));
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
            return redirect()->to(base_url() . 'SCORM/scorm_meta_category/addclientstocategoryview');
        }
    }
    public function uploadCategorylogo()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
        $data = [];
        helper(['filesystem']);
        if (isset($_POST['sc_mcid'])) {
            $data['sc_mcid'] = $_POST['sc_mcid'];
            $_SESSION['sc_mcid'] =  $data['sc_mcid'];
        } else if (isset($_GET['sc_mcid'])) {
            $data['sc_mcid'] = $_GET['sc_mcid'];
        } else if (isset($_SESSION['sc_mcid'])) {
            $data['sc_mcid'] = $_SESSION['sc_mcid'];
        }

        if ($this->request->getPost()) {
            $rules = [
                'file' => 'uploaded[file]|ext_in[file,jpg,png]'
            ];
            if (!$this->validate($rules)) {
                $data['logovalidation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();
                        if (!is_dir(FCPATH . 'assets/assets/uploads/category/' . $data['sc_mcid'])) {
                            mkdir('assets/assets/uploads/category/' . $data['sc_mcid'], 0777, true);
                        }
                        if (file_exists(FCPATH . 'assets/assets/uploads/category/' . $data['sc_mcid'] . "/" . $filename)) {
                            session()->setFlashdata('error', $filename . ' ' . lang('Messages.Success_0051'));
                        } else {
                            if ($file->move(FCPATH . 'assets/assets/uploads/category/' . $data['sc_mcid'], $filename)) {
                                $filepath = FCPATH . 'assets/assets/uploads/category/' . $data['sc_mcid'] . '/' . $filename;
                                $newdata = [
                                    'image' => $filename,
                                ];
                                $result = $this->scorm_metacategory_model->editmetacatdetails($newdata, $data['sc_mcid']);
                                if ($result) {
                                    session()->setFlashdata('sc_mcid', $data['sc_mcid']);
                                    session()->setFlashdata('success', lang('Messages.Success_0009'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                    return redirect()->to(base_url() . 'SCORM/scorm_meta_category/category');
                                } else {
                                    session()->setFlashdata('sc_mcid',  $data['sc_mcid']);
                                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                    return redirect()->to(base_url() . 'SCORM/scorm_meta_category/category');
                                }
                            }
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                        return redirect()->to(base_url() . 'SCORM/scorm_meta_category/category');
                    }
                }
            }
        }
    }
}
