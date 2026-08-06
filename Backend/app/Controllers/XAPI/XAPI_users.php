<?php

namespace App\Controllers\XAPI;

use App\Controllers\BaseController;
use App\Models\XAPI\XAPI_scenarios_model;
use App\Models\User_login\Login_model;
use App\Models\Settings\Dropdown_model;
use App\Models\User_login\Users_model;
use App\Models\SCORM\Scorm_client_model;

#[\AllowDynamicProperties]
class XAPI_users extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->xapi_scenarios_model = new XAPI_scenarios_model();
        $this->login_model = new Login_model();
        $this->dropdown_model = new Dropdown_model();
        $this->users_model = new Users_model();
        $this->scorm_client_model = new Scorm_client_model();
    }
    public function index() //fetch data from users table to display
    {
        $data = [];
        helper(['form']);

        $data['header'] = 'AR/VR/Sim Clients';
        $data['header_link'] = 'XAPI/XAPI_client';
        $data['sub_header_1'] = 'Users List';

        $data['form_link1'] = 'XAPI/XAPI_users/users_courses_assign';

        $data['clientid'] = $this->request->getVar('id_c');
        $data['usertable'] = $this->scorm_client_model->user_assigned_courses($data['clientid'], 5);
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_users/users_view', $data);
        echo view('templates/footer_view');
    }
    public function users_courses_assign()
    {
        $data = [];
        $data['header'] = 'AR/VR/Sim Clients';
        $data['header_link'] = 'XAPI/XAPI_client';
        $data['sub_header_1'] = 'Users List';
        $data['sub_header_1_link'] = 'XAPI/xapi_users';
        $data['sub_header_2'] = 'User Report';

        $data['form_link1'] = 'XAPI/xapi_users/deleteuserscoursedetails';

        helper(['form']);
        if (isset($_POST['userid'])) {
            $data['userid'] = $_POST['userid'];
            $_SESSION['userid'] =  $data['userid'];
        } else if (isset($_GET['userid'])) {
            $data['userid'] = $_GET['userid'];
        } else if (isset($_SESSION['userid'])) {
            $data['userid'] = $_SESSION['userid'];
        }
        if (isset($_POST['client_id'])) {
            $data['client_id'] = $_POST['client_id'];
            $_SESSION['client_id'] =  $data['client_id'];
        } else if (isset($_GET['client_id'])) {
            $data['client_id'] = $_GET['client_id'];
        } else if (isset($_SESSION['client_id'])) {
            $data['client_id'] = $_SESSION['client_id'];
        }
        $data['username'] = $this->scorm_client_model->getUserName($data['userid']);
        $data['getAllCoursesForClient'] = $this->scorm_client_model->getAllCoursesForClient($data['client_id'], 5);
        $data['getAllCoursesForUsers'] = $this->scorm_client_model->getAllCoursesForUserbyType($data['userid'], 5);
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_users/users_courses_assign_view', $data);
        echo view('templates/footer_view');
    }
    public function add_course_to_user()
    {
        $newData = [
            'course_id' => $_POST['course_id'],
            'id_user' => $_POST['id_user'],
            'createdby' => session()->get('id_user'),
            'createdon' => time(),
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_client_model->addcoursetousers($newData);
        echo json_encode($result);
    }
    public function deleteuserscoursedetails()
    {
        $user_assign_id = $_POST['user_assign_id'];
        $newdata = [
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => '0',
        ];
        $result = $this->scorm_client_model->deleteuserscoursedetails($newdata, $user_assign_id);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
            return redirect()->to(base_url() . '/XAPI/xapi_users/users_courses_assign');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . '/XAPI/xapi_users/users_courses_assign');
        }
    }
    function userallcoursedetails()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['user_assign_id'])) {
            $data['user_assign_id'] = $_POST['user_assign_id'];
            $_SESSION['user_assign_id'] =  $data['user_assign_id'];
            $data['tempusername'] = $_POST['tempusername'];
            $_SESSION['tempusername'] =  $data['tempusername'];
            $data['course_name'] = $_POST['course_name'];
            $_SESSION['course_name'] =  $data['course_name'];
            $data['scenario_name'] = $_POST['scenario_name'];
            $_SESSION['scenario_name'] =  $data['scenario_name'];
        } else if (isset($_SESSION['user_assign_id'])) {
            $data['user_assign_id'] = $_SESSION['user_assign_id'];
        } else {
            return redirect()->to(base_url() . 'User_login/client_users/manageTrainings');
        }
        $data['course_header'] = 'Courses';
        $data['course_header_link'] = 'User_login/client_users/manageTrainings';
        $data['header'] = 'Course Report - ' . $_SESSION['course_name'];
        $data['header_link'] = 'User_login/client_users/usersassigned_report';
        $data['header2'] = 'Scenario - ' . $_SESSION['scenario_name'];
        $data['header2_link'] = 'XAPI/XAPI_scenarios/view_assigned_users';
        $data['header3'] =  $_SESSION['tempusername'] . '  Attempts';

        $data['form_link'] = 'XAPI/XAPI_users/userallcoursedetails';
        $data['view_details'] = 'XAPI/XAPI_users/viewDetailedReport';

        $data['delete_enrollment'] = 'XAPI/XAPI_users/delete_enrollment';

        $data['userRecords'] = $this->xapi_scenarios_model->getuserDetails($data['user_assign_id']);
        // echo "<pre>";
        // print_r($data['userRecords']);
        // exit();
        echo view('templates/header_view');
        echo view('XAPI/scenario_user_report', $data);
        echo view('templates/footer_view');
    }
    function manageuserallcoursedetails()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['user_assign_id'])) {
            $data['user_assign_id'] = $_POST['user_assign_id'];
            $_SESSION['user_assign_id'] =  $data['user_assign_id'];
            $data['tempusername'] = $_POST['tempusername'];
            $_SESSION['tempusername'] =  $data['tempusername'];
        } else if (isset($_SESSION['user_assign_id'])) {
            $data['user_assign_id'] = $_SESSION['user_assign_id'];
        } else {
            return redirect()->to(base_url() . 'User_login/client_users/manageTrainings');
        }


        $data['course_header'] = 'Courses';
        $data['course_header_link'] = 'User_login/client_users/manageTrainings';
        $data['header'] = 'Scenarios {' . $_SESSION['course_name'] . '}';
        $data['header_link'] = 'XAPI/XAPI_scenarios/XAPIMangeCourses';
        $data['header2'] = 'Scenarios Users {' . $_SESSION['scenario_name'] . '}';
        $data['header2_link'] = 'XAPI/XAPI_scenarios/view_Manageassigned_users';
        $data['header3'] =  $_SESSION['tempusername'] . '  Attempts';

        $data['form_link'] = 'XAPI/XAPI_users/userallcoursedetails';
        $data['view_details'] = 'XAPI/XAPI_users/viewManageDetailedReport';

        $data['delete_enrollment'] = 'XAPI/XAPI_users/delete_enrollment';

        $data['userRecords'] = $this->xapi_scenarios_model->getuserDetails($data['user_assign_id']);
        // echo "<pre>";
        // print_r($data['userRecords']);
        // exit();
        echo view('templates/header_view');
        echo view('XAPI/scenario_user_report', $data);
        echo view('templates/footer_view');
    }
    function viewDetailedReport()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['sc_uid'])) {
            $data['sc_uid'] = $_POST['sc_uid'];
            $_SESSION['sc_uid'] =  $data['sc_uid'];
            $data['attempt'] = $_POST['attempt'];
            $_SESSION['attempt'] =  $data['attempt'];
        } else if (isset($_SESSION['user_assign_id'])) {
            $data['sc_uid'] = $_SESSION['sc_uid'];
            $data['attempt'] = $_SESSION['attempt'];
        } else {
            return redirect()->to(base_url() . 'User_login/client_users/manageTrainings');
        }
        $data['course_header'] = 'Courses';
        $data['course_header_link'] = 'User_login/client_users/manageTrainings';
        $data['header'] = 'Course Report - ' . $_SESSION['course_name'];
        $data['header_link'] = 'User_login/client_users/usersassigned_report';
        $data['header2'] = 'Scenario - ' . $_SESSION['scenario_name'];
        //$data['header2_link'] = 'XAPI/XAPI_users/userallcoursedetails';
        $data['header3'] =  $_SESSION['tempusername'] . '  Attempts';
        $data['header3_link'] = 'XAPI/XAPI_users/userallcoursedetails';
        $data['header4'] =  'Attempt ' . $_SESSION['attempt'] . '  Details';

        $data['deleteAction'] = 'XAPI/XAPI_users/delete_activity';

        $data['userActivity'] = $this->xapi_scenarios_model->activityDetails($data['sc_uid']);
        $data['OutputVariables'] = $this->xapi_scenarios_model->getOutputVariable($data['sc_uid']);
        // print_r( $data['OutputVariables']);
        //exit();
        echo view('templates/header_view');
        echo view('XAPI/scenario_user_report_details', $data);
        echo view('templates/footer_view');
    }
    function viewManageDetailedReport()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['sc_uid'])) {
            $data['sc_uid'] = $_POST['sc_uid'];
            $_SESSION['sc_uid'] =  $data['sc_uid'];
            $data['attempt'] = $_POST['attempt'];
            $_SESSION['attempt'] =  $data['attempt'];
        } else if (isset($_SESSION['user_assign_id'])) {
            $data['sc_uid'] = $_SESSION['sc_uid'];
            $data['attempt'] = $_SESSION['attempt'];
        } else {
            return redirect()->to(base_url() . 'User_login/client_users/manageTrainings');
        }
        $data['course_header'] = 'Courses';
        $data['course_header_link'] = 'User_login/client_users/manageTrainings';
        $data['header'] = 'Scenarios {' . $_SESSION['course_name'] . '}';
        $data['header_link'] = 'XAPI/XAPI_scenarios/XAPIMangeCourses';
        $data['header2'] = 'Scenarios Users {' . $_SESSION['scenario_name'] . '}';
        $data['header2_link'] = 'XAPI/XAPI_scenarios/view_assigned_users';
        $data['header3'] =  $_SESSION['tempusername'] . '  Attempts';
        $data['header3_link'] = 'XAPI/XAPI_users/userallcoursedetails';
        $data['header4'] =  'Attempt ' . $_SESSION['attempt'] . '  Details';

        $data['deleteAction'] = 'XAPI/XAPI_users/delete_activity';

        $data['userActivity'] = $this->xapi_scenarios_model->activityDetails($data['sc_uid']);
        $data['OutputVariables'] = $this->xapi_scenarios_model->getOutputVariable($data['sc_uid']);
        echo view('templates/header_view');
        echo view('XAPI/scenario_user_report_details', $data);
        echo view('templates/footer_view');
    }
    function viewManageuserDetailedReport()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['sc_uid'])) {
            $data['sc_uid'] = $_POST['sc_uid'];
            $_SESSION['sc_uid'] =  $data['sc_uid'];
            $data['attempt'] = $_POST['attempt'];
            $_SESSION['attempt'] =  $data['attempt'];
        } else if (isset($_SESSION['user_assign_id'])) {
            $data['sc_uid'] = $_SESSION['sc_uid'];
            $data['attempt'] = $_SESSION['attempt'];
        } else {
            return redirect()->to(base_url() . 'User_login/client_users/manageTrainings');
        }
        $data['course_header'] = 'Users';
        $data['course_header_link'] = 'User_login/client_users';
        $data['header'] = 'User Report';
        $data['header_link'] = 'User_login/client_users/course_report/' . base64_encode($_POST['userid']);
        $data['header2'] = '';
        $data['header2_link'] = '';
        $data['header3'] =  'All Attempt Course View -' . $_POST['username'];
        $data['header3_link'] = 'User_login/client_users/getscormuserdetails';
        $data['header4'] =  'Attempt ' . $_SESSION['attempt'] . '  Details';

        $data['deleteAction'] = 'XAPI/XAPI_users/delete_activity';

        $data['userActivity'] = $this->xapi_scenarios_model->activityDetails($data['sc_uid']);
        $data['OutputVariables'] = $this->xapi_scenarios_model->getOutputVariable($data['sc_uid']);

        echo view('templates/header_view');
        echo view('XAPI/scenario_user_report_details', $data);
        echo view('templates/footer_view');
    }

    public function delete_enrollment()
    {
        $sc_uid = $_POST['sc_uid'];
        $newdata = [
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => '0',
        ];
        $result = $this->xapi_scenarios_model->deleteEnrollment($newdata, $sc_uid);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . '/XAPI/XAPI_users/userallcoursedetails');
    }
    public function users_delete_enrollment()
    {
        $sc_uid = $_POST['sc_uid'];
        $newdata = [
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => '0',
        ];
        $result = $this->xapi_scenarios_model->deleteEnrollment($newdata, $sc_uid);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . 'User_login/client_users');
    }
    public function delete_activity()
    {
        $xapi_act_id = $_POST['xapi_act_id'];
        $newdata = [
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => '0',
        ];
        $result = $this->xapi_scenarios_model->deleteAction($newdata, $xapi_act_id);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . '/XAPI/XAPI_users/viewDetailedReport');
    }
}
