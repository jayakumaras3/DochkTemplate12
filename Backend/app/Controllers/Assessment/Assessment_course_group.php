<?php

namespace App\Controllers\Assessment;

use App\Controllers\BaseController;

use App\Models\Settings\Dropdown_model;
use App\Models\SCORM\Scorm_course_model;
use App\Models\SCORM\Scorm_metacategory_model;
use App\Models\SCORM\Scorm_course_group_model;

#[\AllowDynamicProperties]
class Assessment_course_group extends BaseController
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
        $data['header'] = 'Demo Courses';
        $data['header_link'] = 'Demo/demo_courses';
        $data['sub_header_1'] = 'Demo Course Group';
        $data['form_link'] = 'Demo/demo_course_group/addcoursegroupval';
        $data['form_link_1'] = 'Demo/demo_course_group/add_courses';
        $data['form_link_2'] = 'Demo/demo_course_group/coursegroup_edit_view';

        $data['coursegroupdata'] = $this->scorm_course_group_model->getCoursegroupdate(1);
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_course_group/course_group_view', $data);
        echo view('templates/footer_view');
    }
    public function addcoursegroupval()
    {
        $data = [];
        helper(['form']);
        if ($this->request->getPost()) {
            $newdata = [
                'client_id' => session()->get('client'),
                'description' => $this->request->getVar('description'),
                'type' => '1',
                'status' => '1',
                'createdby' => session()->get('id_user'),
                'createdon' => time(),
                'last_updated_by' =>  session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $result = $this->scorm_course_group_model->addcoursegroupdetails($newdata);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0007'));
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }
            return redirect()->to(base_url() . '/Demo/demo_course_group');
        }
    }
    public function coursegroup_edit_view()
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'Demo Courses';
        $data['header_link'] = 'demo_courses';
        $data['sub_header_1'] = 'Demo Course Group';
        $data['sub_header_1_link'] = 'Demo/demo_course_group';
        $data['sub_header_2'] = 'Demo Edit Course Group';

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
        $data['form_link'] = 'Demo/demo_course_group/editcoursegpval?sc_cgid=' . $getcoursegroup[0]['sc_cgid'];

        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_course_group/coursegroup_edit_view', $data);
        echo view('templates/footer_view');
    }
    public function editcoursegpval()
    {
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
        }
        return redirect()->to(base_url() . '/Demo/demo_course_group');
    }
    public function deletecoursegpdetails()
    {
        $sc_cgid = $_POST['sc_cgid'];
        $newdata = [


            'status' => '0',
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_course_group_model->editcoursegpdetails($newdata, $sc_cgid);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . '/Demo/demo_course_group');
    }
    public function add_courses()
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'Demo Courses';
        $data['header_link'] = 'demo_courses';
        $data['sub_header_1'] = 'Demo Course Group';
        $data['sub_header_1_link'] = 'Demo/demo_course_group';
        $data['sub_header_2'] = 'Demo Group Courses';

        $data['form_link'] = 'Demo/demo_course_group/add_course_to_group';
        $data['form_link_1'] = 'Demo/demo_course_group/delete_assigned_course';

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
        $data['all_courses'] = $this->scorm_course_model->getCoursesDetails(1);
        $data['assigned_courses'] = $this->scorm_course_group_model->getCoursesAssignedto($data['sc_cgid']);
        $data['type'] = 1;
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_course_group/coursegroup_add_courses', $data);
        echo view('templates/footer_view');
    }
    public function add_course_to_group()
    {
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
        return redirect()->to(base_url() . '/Demo/demo_course_group/add_courses');
    }
}
