<?php

namespace App\Controllers\SCORM\Course_builder;

use App\Controllers\BaseController;

use App\Models\Settings\Dropdown_model;
use App\Models\SCORM\Scorm_course_model;
use App\Models\SCORM\Scorm_metacategory_model;
use App\Models\SCORM\Scorm_course_group_model;

#[\AllowDynamicProperties]
class Scorm_course_group extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->is_session_available();
        $this->dropdown_model = new Dropdown_model();
        $this->scorm_course_model = new Scorm_course_model();
        $this->scorm_metacategory_model = new Scorm_metacategory_model();
        $this->scorm_course_group_model = new Scorm_course_group_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url('my_training'));
            exit();
        }

        $arrayuserlevel = explode(',', $userlevel);
        if (!in_array('44', $arrayuserlevel) && !in_array('5', $arrayuserlevel)) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }
    public function index()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $data['header'] = 'Courses';
        $data['header_link'] = 'SCORM/Course_builder/scorm_courses';
        $data['sub_header_1'] = 'Course Group';
        $data['form_link'] = 'SCORM/Course_builder/scorm_course_group/addcoursegroupval';
        $data['form_link_1'] = 'SCORM/Course_builder/scorm_course_group/add_courses';
        $data['form_link_2'] = 'SCORM/Course_builder/scorm_course_group/coursegroup_edit_view';

        $data['coursegroupdata'] = $this->scorm_course_group_model->getCoursegroupdate(11);
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_course_group/course_group_view', $data);
        echo view('templates/footer_view');
    }
    public function addcoursegroupval()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if ($this->request->getPost()) {
            $newdata = [
                'client_id' => session()->get('client'),
                'description' => $this->request->getVar('description'),
                'type' => '11',
                'status' => '1',
                'createdby' => session()->get('id_user'),
                'createdon' => time(),
                'last_updated_by' =>  session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $result = $this->scorm_course_group_model->addcoursegroupdetails($newdata);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
            } else {
                session()->setFlashdata('error', 'Error adding course group.');
                session()->setFlashdata('alert-class', 'alert-danger');
            }
            return redirect()->to(base_url() . '/SCORM/Course_builder/scorm_course_group');
        }
    }
    public function deleteCoursedetails()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }

        $scourse_id = $_POST['scourse_id'];
        $assign_id = $_POST['assign_id'];

        $newdata = [
             
            
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => '0',
        ];
        $result = $this->scorm_course_model->deltecoursegroup($newdata, $assign_id);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
            return redirect()->to(base_url() . 'my_training/course_group_list');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'my_training/course_group_list');
        }
    }
    public function coursegroup_edit_view()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }

        $data = [];
        helper(['form']);
        $data['header'] = 'Courses';
        $data['header_link'] = 'SCORM/Course_builder/scorm_courses';
        $data['sub_header_1'] = 'Course Group';
        $data['sub_header_1_link'] = 'SCORM/Course_builder/scorm_course_group';
        $data['sub_header_2'] = 'Edit Course Group';


        $user = session()->get('username');
        if (isset($_POST['sc_cgid'])) {
            $data['sc_cgid'] = $_POST['sc_cgid'];
            $_SESSION['sc_cgid'] =  $data['sc_cgid'];
        } else if (isset($_GET['sc_cgid'])) {
            $data['sc_cgid'] = $_GET['sc_cgid'];
        } else if (isset($_SESSION['sc_cgid'])) {
            $data['sc_cgid'] = $_SESSION['sc_cgid'];
        }
        $getcoursegroup = $this->scorm_course_group_model->getcoursegroupDetails($data['sc_cgid']);
        $data['row'] = $getcoursegroup;
        $data['form_link'] = 'SCORM/Course_builder/scorm_course_group/editcoursegpval?sc_cgid=' . $getcoursegroup[0]['sc_cgid'];

        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_course_group/coursegroup_edit_view', $data);
        echo view('templates/footer_view');
    }
    public function editcoursegpval()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }

        $data = [];
        helper(['form']);
        $user = session()->get('username');
        $sc_cgid = $this->request->getVar('sc_cgid');
        $data['sc_cgid'] = $sc_cgid;
        $getcoursegroup = $this->scorm_course_group_model->getcoursegroupDetails($data['sc_cgid']);
        $data['row'] = $getcoursegroup;
        if ($this->request->getPost()) {
            $newdata = [
                'description' => $this->request->getVar('description'),
            ];
            $result = $this->scorm_course_group_model->editcoursegpdetails($newdata, $sc_cgid);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0008'));
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }
            return redirect()->to(base_url() . '/SCORM/Course_builder/scorm_course_group');
        }
    }
    public function deletecoursegpdetails()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }

        $sc_cgid = $_POST['sc_cgid'];
        $newdata = [
             
            
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => '0',
        ];
        $result = $this->scorm_course_group_model->editcoursegpdetails($newdata, $sc_cgid);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
            return redirect()->to(base_url() . '/SCORM/Course_builder/scorm_course_group');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . '/SCORM/Course_builder/scorm_course_group');
        }
    }
    public function add_courses()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }

        $data = [];
        helper(['form']);
        $data['header'] = 'Courses';
        $data['header_link'] = 'SCORM/Course_builder/scorm_courses';
        $data['sub_header_1'] = 'Course Group';
        $data['sub_header_1_link'] = 'SCORM/Course_builder/scorm_course_group';
        $data['sub_header_2'] = 'Group Courses';

        $data['form_link'] = 'SCORM/Course_builder/scorm_course_group/add_course_to_group';
        $data['form_link_1'] = 'SCORM/Course_builder/scorm_course_group/delete_assigned_course';

        if (isset($_POST['sc_cgid'])) {
            $data['sc_cgid'] = $_POST['sc_cgid'];
            $_SESSION['sc_cgid'] =  $data['sc_cgid'];
        } else if (isset($_GET['sc_cgid'])) {
            $data['sc_cgid'] = $_GET['sc_cgid'];
        } else if (isset($_SESSION['sc_cgid'])) {
            $data['sc_cgid'] = $_SESSION['sc_cgid'];
        }
        $getcoursegroup = $this->scorm_course_group_model->getcoursegroupDetails($data['sc_cgid']);
        $data['row'] = $getcoursegroup;
        $data['all_courses'] = $this->scorm_course_model->getCoursesDetails(11);
        $data['assigned_courses'] = $this->scorm_course_group_model->getCoursesAssignedto($data['sc_cgid']);
        $data['type'] = 11;
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_course_group/coursegroup_add_courses', $data);
        echo view('templates/footer_view');
    }
    public function add_course_to_group()
    {
         if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        
        $data = [];
        helper(['form']);
        $newData = [
            'course_id' => $_POST['course_id'],
            'group_id' => $_POST['sc_cgid'],
            'status' => 1,
            'createdby' => session()->get('id_user'),
            'createdon' => time(),
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_course_group_model->add_course_to_gr($newData);
        echo json_encode($result);
    }
    // public function add_course_to_group1()
    // {
    //     $data = [];
    //     helper(['form']);
    //     $sc_cgid = $this->request->getVar('sc_cgid');
    //     $data['sc_cgid'] = $sc_cgid;
    //    if ($this->request->getPost()) {
    //         $course_id = $this->request->getVar('course_id');
    //         $checkifexists = $this->scorm_course_group_model->check_if_course_exists_in_group($course_id, $sc_cgid);
    //         if ($checkifexists == 0) {
    //             $timestamp = time();
    //             $newdata = [
    //                 'course_id' => $course_id,
    //                 'group_id' => $sc_cgid,
    //                 'status' => 1,
    //                 'createdby' => session()->get('id_user'),
    //                 'createdon' => $timestamp,
    //             ];
    //             $result = $this->scorm_course_group_model->add_course_to_gr($newdata);

    //             if ($result) {
    //                 session()->setFlashdata('success', 'Course added to the Group.');
    //                 return redirect()->to(base_url() . '/scorm_course_group/add_courses');
    //             } else {
    //                 session()->setFlashdata('error', 'Error adding course.');
    //                 session()->setFlashdata('alert-class', 'alert-danger');
    //                 return redirect()->to(base_url() . '/scorm_course_group/add_courses');
    //             }
    //         } else {
    //             session()->setFlashdata('error', 'Course already assigned.');
    //             session()->setFlashdata('alert-class', 'alert-danger');
    //             return redirect()->to(base_url() . '/scorm_course_group/add_courses');
    //         }
    //     }
    // }

    public function delete_assigned_course()
    {
         if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        
        $data = [];
        helper(['form']);
        $user = session()->get('username');
        $sc_cgid = $this->request->getVar('sc_cgid');
        $data['sc_cgid'] = $sc_cgid;
        if ($this->request->getPost()) {
            $rules = [
                'sc_cgid' => 'required',
                'assign_id' => 'required',
            ];
            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
            } else {
                $timestamp = time();
                $newdata = [
                    'status' => 0,
                    
                     
                    'last_updated_by' =>  session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                $assign_id = $this->request->getVar('assign_id');
                $result = $this->scorm_course_group_model->delete_course_assigned_mod($newdata, $assign_id);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0005'));
                } else {
                    session()->setFlashdata('error', 'Error deleting course.');
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
        }
        return redirect()->to(base_url() . '/SCORM/Course_builder/scorm_course_group/add_courses');
    }
}
