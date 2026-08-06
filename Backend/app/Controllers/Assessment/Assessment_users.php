<?php

namespace App\Controllers\Assessment;
use App\Controllers\BaseController;

use App\Models\User_login\Login_model;
use App\Models\Settings\Dropdown_model;
use App\Models\User_login\Users_model;
use App\Models\SCORM\Scorm_client_model;
#[\AllowDynamicProperties]
class Assessment_users extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->is_session_available();
        $this->login_model = new Login_model();
        $this->dropdown_model = new Dropdown_model();
        $this->users_model = new Users_model();
        $this->scorm_client_model = new Scorm_client_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url('my_training'));
            exit();
        }

        $arrayuserlevel = explode(',', $userlevel);
        if (!in_array('6', $arrayuserlevel) && !in_array('98', $arrayuserlevel) && !in_array('99', $arrayuserlevel) && !in_array('44', $arrayuserlevel) && !in_array('67', $arrayuserlevel)) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }
    public function index() //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'Assessment Clients';
        $data['header_link'] = 'Assessment/scorm_client';
        $data['sub_header_1'] = 'Users List';

        $data['form_link1'] = 'Assessment/Assessment_users/users_courses_assign';

        $data['clientid'] = $this->request->getVar('id_c');
        $data['usertable'] = $this->scorm_client_model->user_assigned_courses($data['clientid'], 2);
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_users/users_view', $data);
        echo view('templates/footer_view');
    }
    public function users_courses_assign()
    {
        $data = [];
        $data['header'] = 'Assessment Clients';
        $data['header_link'] = 'Assessment/assessment_client';
        $data['sub_header_1'] = 'Users List';
        $data['sub_header_1_link'] = 'Assessment/Assessment_users';
        $data['sub_header_2'] = 'User Report';
        $data['form_link1'] = 'Assessment/Assessment_users/deleteuserscoursedetails';
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
        $data['getAllCoursesForClient'] = $this->scorm_client_model->getAllCoursesForClient($data['client_id'], 2);
        $data['getAllCoursesForUsers'] = $this->scorm_client_model->getAllCoursesForUserbyType($data['userid'], 2);
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_users/users_courses_assign_view', $data);
        echo view('templates/footer_view');
    }
    public function add_user_to_course()
    {
        $newData = [
            'course_id' => $_POST['course_id'],
            'scenario_id' => $_POST['scenario'],
            'id_user' => $_POST['userid'],
            'createdby' => session()->get('id_user'),
            'createdon' => time(),
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_client_model->addusertocourses($newData);
        echo json_encode($result);
    }
    public function add_course_to_user()
    {
        $newData = [
            'course_id' => $_POST['course_id'],
            'id_user' => $_POST['userid'],
            'createdby' => session()->get('id_user'),
            'createdon' => time(),
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_client_model->addcoursetousers($newData);
        echo json_encode($result);
    }
    public function deleteuserscoursedetails(){
        $user_assign_id = $_POST['user_assign_id'];
        $newdata = [
             
            
            'status' => '0',
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_client_model->deleteuserscoursedetails($newdata, $user_assign_id);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
            return redirect()->to(base_url() . '/Assessment/Assessment_users/users_courses_assign');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . '/Assessment/Assessment_users/users_courses_assign');
        }
    }
   
}
