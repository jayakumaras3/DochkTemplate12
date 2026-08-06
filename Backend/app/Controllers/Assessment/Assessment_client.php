<?php

namespace App\Controllers\Assessment;

use App\Controllers\BaseController;
use App\Models\Dashboard\Dashboard_model;
use App\Models\Project\Projects_model;
use App\Models\Settings\Settings_model;
use App\Models\Settings\Dropdown_model;
use App\Models\SCORM\Scorm_client_model;
use App\Models\SCORM\Scorm_course_model;
use App\Models\User_login\Users_model;
use App\Models\SCORM\Scorm_course_group_model;

#[\AllowDynamicProperties]
class Assessment_client extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->is_session_available();
        $this->dashboard_model = new Dashboard_model();
        $this->projects_model = new Projects_model();
        $this->settings_model = new Settings_model();
        $this->dropdown_model = new Dropdown_model();
        $this->scorm_client_model = new Scorm_client_model();
        $this->scorm_course_model = new Scorm_course_model();
        $this->scorm_client_model = new Scorm_client_model();
        $this->users_model = new Users_model();
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
        $data['header'] = 'Assessment Clients';
        //$data['header_link'] = 'scorm_courses';
        //$data['sub_header_1'] = 'Create New Course';
        $data['form_link1'] = 'Assessment/assessment_client/view_client_course_assigned';
        $data['form_link2'] = 'Assessment/Scorm_users';

        $data['clientlist'] = $this->scorm_client_model->clientAssessmentuserlist();
        echo view('templates/header_view');
        echo view('SCORM/scorm_client/sc_client_view', $data);
        echo view('templates/footer_view');
    }

    public function view_client_course_assigned()
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'Assessment Clients';
        $data['header_link'] = 'Assessment/assessment_client';
        $data['sub_header_1'] = 'Add Courses to Client';
        $data['form_link1'] = 'Assessment/assessment_client/delete_assigned_client_course';
        $data['form_link2'] = 'Assessment/assessment_client/add_course_to_client';
        $data['form_link3'] = 'Assessment/assessment_client/add_group_to_client';
        $data['form_link4'] = 'Assessment/assessment_client/usersassigned_report';
        if (isset($_POST['client_id'])) {
            $data['client_id'] = $_POST['client_id'];
            $_SESSION['client_id'] =  $data['client_id'];
        } else if (isset($_GET['client_id'])) {
            $data['client_id'] = $_GET['client_id'];
        } else if (isset($_SESSION['client_id'])) {
            $data['client_id'] = $_SESSION['client_id'];
        }
        $data['all_courses'] = $this->scorm_course_model->getCoursesDetails(8);
        $data['coursegroupdata'] = $this->scorm_course_group_model->getCoursegroupdate(8);
        $data['getAllCoursesForClient'] = $this->scorm_client_model->getAllCoursesForClient($data['client_id'], 8);

        echo view('templates/header_view');
        echo view('SCORM/scorm_client/sc_client_courses_assigned', $data);
        echo view('templates/footer_view');
    }
    public function usersassigned_report()
    {
        $data = [];
        helper(['form']);
        $userlevel = session()->get('userlevel');
        $arrayuserlevel  = explode(',', $userlevel);
        $client_id =  session()->get('client');
        $data['client_id'] = $client_id;
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] =  $data['scourse_id'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } elseif ($_SESSION['scourse_id'] && $_SESSION['scourse_id']) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        }
        $data['coursename'] = $this->scorm_client_model->getCoursename($data['scourse_id']);
        $data['header_link'] = 'Assessment/assessment_client/view_client_course_assigned';
        $data['header_sub_link'] = 'Assessment/assessment_client/usersassigned_report';
        $data['form_link'] = 'Assessment/assessment_client/userallcoursedetails';
        $data['header_link_name'] = 'Add Courses to Client';
        $data['form_link1'] = 'Assessment/assessment_users/add_user_to_course';
        $data['getUserlatestCourse'] = $this->scorm_client_model->getUserlatestCourse($data['scourse_id']);
        $data['getUserclientlist'] = $this->users_model->getUserclientlist($client_id);
        // $data['getAllUsersForCourses'] = $this->scorm_client_model->getAllUsersForCourses($data['scourse_id']);
        echo view('templates/header_view');
        echo view('users/sc_userslatestassigned_view', $data);
        echo view('templates/footer_view');
    }
    function userallcoursedetails()
    {
        $data = [];
        helper(['form']);
        $userlevel = session()->get('userlevel');
        $arrayuserlevel  = explode(',', $userlevel);
        $client_id =  session()->get('client');
        $data['client_id'] = $client_id;
        if (isset($_POST['course_id'])) {
            $data['course_id'] = $_POST['course_id'];
            $_SESSION['course_id'] =  $data['course_id'];
        } else if (isset($_GET['course_id'])) {
            $data['course_id'] = $_GET['course_id'];
        } elseif ($_SESSION['course_id']) {
            $data['course_id'] = $_SESSION['course_id'];
        }
        if (isset($_POST['student_id'])) {
            $data['student_id'] = $_POST['student_id'];
            $_SESSION['student_id'] =  $data['student_id'];
        } else if (isset($_GET['student_id'])) {
            $data['student_id'] = $_GET['student_id'];
        } elseif ($_SESSION['student_id']) {
            $data['student_id'] = $_SESSION['student_id'];
        }
        $data['header_link'] = 'Assessment/assessment_client/view_client_course_assigned';
        $data['header_sub_link'] = 'Assessment/assessment_client/usersassigned_report';
        $data['form_link'] = 'Assessment/assessment_client/userallcoursedetails';
        $data['coursename'] = $this->assessment_client_model->getCoursename($data['course_id']);
        $data['header_link_name'] = 'Add Courses to Client';
        $data['form_link1'] = 'Assessment/assessment_client/deleteScormdetails';
        $data['delete_enrollment'] = 'Assessment/assessment_client/delete_enrollment';
        $data['form_link2'] =  'Assessment/assessment_users/add_course_to_user';
        $data['getAllUsersForCourses'] = $this->scorm_client_model->getcourseusersdetails($data['student_id'], $data['course_id']);
        echo view('templates/header_view');
        echo view('users/sc_usersassigned_report_view', $data);
        echo view('templates/footer_view');
    }
    public function delete_enrollment()
    {
        $course_id = $_GET['course_id'];
        $id_user = $_GET['id_user'];
        // print_r($course_id.''. $id_user);
        // exit();
        $newdata = [
             
            
            'status' => '0',
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_client_model->deleteEnrollment($newdata, $id_user, $course_id);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
            return redirect()->to(base_url() . '/Assessment/assessment_client/usersassigned_report');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . '/Assessment/assessment_client/usersassigned_report');
        }
    }
    public function deleteScormdetails()
    {
        $sc_uid = $_POST['sc_uid'];
        $newdata = [
             
            
            'status' => '0',
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_client_model->deleteScormUserdetails($newdata, $sc_uid);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
            return redirect()->to(base_url() . '/Assessment/assessment_client/usersassigned_report');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . '/Assessment/assessment_client/usersassigned_report');
        }
    }
    public function add_course_to_client()
    {
        $data = [];
        helper(['form']);
        $userlevel = session()->get('userlevel');
        $arrayuserlevel  = explode(',', $userlevel);
        $newdata = [
            'client_id' => $_POST['client_id'],
            'course_id' => $_POST['course_id'],
            'group_id' => 0,
            'editable' => $_POST['editable'],
            'status' => 1,
            'createdby' => session()->get('id_user'),
            'createdon' => time(),
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_client_model->addmulticoursetoclient($newdata);
        echo json_encode($result);
    }
    // public function add_course_to_client()
    // {
    //     $data = [];
    //     helper(['form']);
    //     $userlevel = session()->get('userlevel');
    //     $arrayuserlevel  = explode(',', $userlevel);
    //     if (in_array('4', $arrayuserlevel) || in_array('6', $arrayuserlevel)) {
    //        if ($this->request->getPost()) {
    //             $client_id = $this->request->getVar('client_id');
    //             $course_id = $this->request->getVar('course_id');
    //             $checkifassigned = $this->scorm_client_model->checkcourseassigned($client_id, $course_id);
    //             if ($checkifassigned == 0) {
    //                 $newdata = [
    //                     'client_id' => $client_id,
    //                     'course_id' => $course_id,
    //                     'group_id' => 0,
    //                     'status' => '1',
    //                     'createdby' => session()->get('id_user'),
    //                     'createdon' => time(),
    //                 ];
    //                 $result = $this->scorm_client_model->addcoursetoclient($newdata);
    //                 if ($result) {
    //                     session()->setFlashdata('success', 'New Course Added.');
    //                     return redirect()->to(base_url() . '/scorm_client/view_client_course_assigned?client_id=' . $client_id);
    //                 } else {
    //                     session()->setFlashdata('error', 'Error adding course.');
    //                     session()->setFlashdata('alert-class', 'alert-danger');
    //                     return redirect()->to(base_url() . '/scorm_client/view_client_course_assigned?client_id=' . $client_id);
    //                 }
    //             } else {
    //                 session()->setFlashdata('error', 'Course already assigned.');
    //                 session()->setFlashdata('alert-class', 'alert-danger');
    //                 return redirect()->to(base_url() . '/scorm_client/view_client_course_assigned?client_id=' . $client_id);
    //             }
    //         }
    //     } else {
    //         session()->setFlashdata('error', lang('Messages.Error_0004'));
    //         return redirect()->to(base_url('dashboard'));
    //     }
    // }

    public function add_group_to_client()
    {
        $data = [];
        helper(['form']);
        $userlevel = session()->get('userlevel');
        $arrayuserlevel  = explode(',', $userlevel);
        $numcourses = 0;
        if (in_array('4', $arrayuserlevel) || in_array('6', $arrayuserlevel)) {
            if ($this->request->getPost()) {
                $client_id = $this->request->getVar('client_id');
                $group_id = $this->request->getVar('group_id');
                $coursesInGroup = $this->scorm_course_group_model->getCoursesAssignedto($group_id);
                foreach ($coursesInGroup  as $assigned) {
                    $course_id = $assigned['course_id'];
                    $checkifassigned = $this->scorm_client_model->checkcourseassigned($client_id, $course_id);
                    if ($checkifassigned == 0) {
                        $numcourses++;
                        $newdata = [
                            'client_id' => $client_id,
                            'course_id' => $course_id,
                            'group_id' => $group_id,
                            'status' => '1',
                            'createdby' => session()->get('id_user'),
                            'createdon' => time(),
                            'last_updated_by' =>  session()->get('id_user'),
                            'last_updated_on' => time(),
                        ];
                        $this->scorm_client_model->addcoursetoclient($newdata);
                    }
                }
                if ($numcourses > 0) {
                    session()->setFlashdata('success', $numcourses . lang('Messages.Success_0006'));
                    return redirect()->to(base_url() . '/Assessment/assessment_client/view_client_course_assigned?client_id=' . $client_id);
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0002'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . '/Assessment/assessment_client/view_client_course_assigned?client_id=' . $client_id);
                }
            }
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            return redirect()->to(base_url('dashboard'));
        }
    }

    public function delete_assigned_client_course()
    {
        $data = [];
        helper(['form']);
        $userlevel = session()->get('userlevel');
        $arrayuserlevel  = explode(',', $userlevel);
        if (in_array('4', $arrayuserlevel) || in_array('6', $arrayuserlevel)) {
            $sc_cr_as_id = $this->request->getVar('sc_cr_as_id');
            $client_id = $this->request->getVar('client_id');
            $newdata = [
                'status' => '0',
                
                 
                'last_updated_by' =>  session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $result = $this->scorm_client_model->delAssignedClientCourse($newdata, $sc_cr_as_id);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0005'));
                return redirect()->to(base_url() . '/Assessment/assessment_client/view_client_course_assigned?client_id=' . $client_id);
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . '/Assessment/assessment_client/view_client_course_assigned?client_id=' . $client_id);
            }
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            return redirect()->to(base_url('dashboard'));
        }
    }
}
