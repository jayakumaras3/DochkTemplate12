<?php

namespace App\Controllers\XAPI;

use App\Controllers\BaseController;

use App\Models\XAPI\XAPI_scenarios_model;
use App\Models\SCORM\Scorm_course_model;
use App\Models\User_login\Users_model;
use App\Models\SCORM\Scorm_client_model;
#[\AllowDynamicProperties]
class XAPI_scenarios extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->xapi_scenarios_model = new XAPI_scenarios_model();
        $this->scorm_course_model = new Scorm_course_model();
        $this->scorm_client_model = new Scorm_client_model();
        $this->users_model = new Users_model();
    }
    public function index()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['scourse_id']) && isset($_POST['course_name'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] =  $data['scourse_id'];
            $data['course_name'] = $_POST['course_name'];
            $_SESSION['course_name'] =  $data['course_name'];
        } else if (isset($_SESSION['scourse_id']) && isset($_SESSION['course_name'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
            $data['course_name'] = $_SESSION['course_name'];
        } else {
            return redirect()->to(base_url() . '/XAPI/XAPI_courses');
        }
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);
        if (in_array('84', $arrayuserlevel)) {
            $data['course_header'] = 'Courses';

            $data['course_header_link'] = 'User_login/client_users/manageTrainings';

            $data['form_link'] = 'XAPI/XAPI_scenarios/view_assigned_users';
            $data['form_seetinglink'] = 'XAPI/XAPI_scenarios/viewScenarioSettings';
            $data['form_editlink'] = 'XAPI/XAPI_scenarios/viewScenarioDetails';
            $data['header'] = 'Scenarios - ' . $data['course_name'];
            $data['scenarios'] = $this->xapi_scenarios_model->getAllScenarios($_SESSION['scourse_id']);
            // print_r($data['scenarios']);
            // exit();
            echo view('templates/header_view', $data);
            echo view('XAPI/scenarios', $data);
            echo view('templates/footer_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            return redirect()->to(base_url('my_training'));
        }
    }
    public function XAPIMangeCourses()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['scourse_id']) && isset($_POST['course_name'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] =  $data['scourse_id'];
            $data['course_name'] = $_POST['course_name'];
            $_SESSION['course_name'] =  $data['course_name'];
        } else if (isset($_SESSION['scourse_id']) && isset($_SESSION['course_name'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
            $data['course_name'] = $_SESSION['course_name'];
        } else {
            return redirect()->to(base_url() . '/XAPI/XAPI_courses');
        }
        if (isset($_POST['type'])) {
            $data['type'] = $_POST['type'];
        }
        $data['course_header'] = 'Courses';
        $data['course_header_link'] = 'User_login/client_users/manageTrainings';
        $data['form_link'] = 'XAPI/XAPI_scenarios/view_Manageassigned_users';
        $data['form_seetinglink'] = 'XAPI/XAPI_scenarios/viewScenarioSettings';
        $data['form_editlink'] = 'XAPI/XAPI_scenarios/viewScenarioDetails';
        $data['header'] = 'Scenarios - ' . $data['course_name'];
        $data['scenarios'] = $this->xapi_scenarios_model->getAllScenarios($_SESSION['scourse_id']);
        echo view('templates/header_view', $data);
        echo view('XAPI/scenarios', $data);
        echo view('templates/footer_view');
    }
    public function createNewScenario()
    {
        $data = [];
        helper(['form']);
        if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        } else {
            return redirect()->to(base_url() . '/XAPI/XAPI_courses');
        }
        if ($this->request->getPost()) {
            $newdata = [
                'scenario_name' => $this->request->getVar('scenario'),
                'scourse_id' => $data['scourse_id'],
                'status' => '1',
                'client' => session()->get('client'),
                'createdby' => session()->get('id_user'),
                'createdon' => time(),
                'last_updated_by' =>  session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $result = $this->xapi_scenarios_model->createNewScenario($newdata);
            if ($result) {
                $inputVariables = $this->scorm_course_model->getInputVariables($data['scourse_id']);
                $scenario_id = $result;
                foreach ($inputVariables as $iv) {
                    if ($iv['variable_type'] == 1) {
                        $default_value = $iv['default_text'];
                    } else {
                        $default_value = 0;
                    }
                    $newdata_settings = [
                        'scenario_id' =>  $scenario_id,
                        'input_variable' =>  $iv['xiv'],
                        'input_variable_type' =>  $iv['variable_type'],
                        'value' =>  $default_value,
                        'status' => '1',
                        'createdby' => session()->get('id_user'),
                        'createdon' => time(),
                        'last_updated_by' =>  session()->get('id_user'),
                        'last_updated_on' => time(),
                    ];
                    $this->xapi_scenarios_model->setScenarioSettings($newdata_settings);
                }
                session()->setFlashdata('success', lang('Messages.Success_0011'));
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }
            return redirect()->to(base_url() . '/XAPI/XAPI_scenarios');
        }
    }

    public function viewScenarioDetails()
    {
        $data = [];
        helper(['form']);
        $scourse_id = session()->get('scourse_id');
        $course_name = session()->get('course_name');
        $xs = session()->get('xs');
        if (isset($scourse_id) && isset($course_name) && isset($xs)) {
            $_POST['scourse_id'] = $scourse_id;
            $_POST['course_name'] = $course_name;
            $_POST['xs'] = $xs;
            session()->remove('scourse_id');
            session()->remove('course_name');
            session()->remove('xs');
        }
        if (isset($_POST['scourse_id']) && isset($_POST['course_name'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] =  $data['scourse_id'];
            $data['course_name'] = $_POST['course_name'];
            $_SESSION['course_name'] =  $data['course_name'];
        } else if (isset($_SESSION['scourse_id']) && isset($_SESSION['course_name'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
            $data['course_name'] = $_SESSION['course_name'];
        } else {
            return redirect()->to(base_url() . 'XAPI/XAPI_courses');
        }
        if (isset($_POST['xs'])) {
            $xs = $_POST['xs'];
            $data['xs'] = $xs;
        } else {
            return redirect()->to(base_url() . 'XAPI/XAPI_courses');
        }

        $data['course_header'] = 'Courses';
        $data['course_header_link'] = 'User_login/client_users/manageTrainings';
        $data['header'] = 'Scenarios - ' . $data['course_name'];
        $data['header_link'] = 'XAPI/XAPI_scenarios';
        $data['header2'] = 'Edit Scenarios';
        $data['updateScenario'] = 'XAPI/XAPI_scenarios/updateScenario';
        $data['assignUserScenario'] = 'XAPI/XAPI_scenarios/editAssignedScenariouser';
        $data['deleteAssignedUser'] = 'XAPI/XAPI_scenarios/deleteAssignedScenariouser';
        $data['scenario_details'] = $this->xapi_scenarios_model->getScenarioDetailss($xs);
        $data['getAssignedCourseUsers'] = $this->xapi_scenarios_model->getAssignedCourseUsers($data['scourse_id']);
        $data['getAssignedScenarioUsers'] = $this->xapi_scenarios_model->getAssignedScenarioUsers($xs);
        echo view('templates/header_view', $data);
        echo view('XAPI/scenario_details', $data);
        echo view('templates/footer_view');
    }

    public function viewScenarioSettings()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['xs'])) {
            $data['xs'] = $_POST['xs'];
            $_SESSION['xs'] =  $data['xs'];
            $data['scenario_name'] = $_POST['scenario_name'];
            $_SESSION['scenario_name'] = $data['scenario_name'];
        } else if (isset($_SESSION['xs'])) {
            $data['xs'] = $_SESSION['xs'];
            $data['scenario_name'] = $_SESSION['scenario_name'];
        } else {
            return redirect()->to(base_url() . '/XAPI/XAPI_courses');
        }
        $data['course_header'] = 'Courses';
        $data['course_header_link'] = 'User_login/client_users/manageTrainings';
        $data['header'] = 'Scenarios - ' . $_SESSION['course_name'];
        $data['header_link'] = 'XAPI/XAPI_scenarios';
        $data['header2'] = 'Scenarios Settings {' . $_SESSION['scenario_name'] . '}';
        $data['settings_form'] = 'XAPI/XAPI_scenarios/changeSenarioSettingValues';
        $data['scenario_settings'] = $this->xapi_scenarios_model->getScenarioSettings($data['xs']);
        echo view('templates/header_view', $data);
        echo view('XAPI/scenarios_settings', $data);
        echo view('templates/footer_view');
    }
    public function updateScenario()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['xs'])) {
            $data['xs'] = $_POST['xs'];
            $data['scourse_id'] = $_POST['scourse_id'];
        } else {
            return redirect()->to(base_url() . '/XAPI/XAPI_courses');
        }
        if ($this->request->getPost()) {
            $newdata = [
                'scenario_name' => $this->request->getVar('scenario'),
                'status' => $this->request->getVar('status'),
                'last_updated_by' =>  session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $result = $this->xapi_scenarios_model->updateScenarioDetails($newdata, $data['xs'], $data['scourse_id']);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0008'));
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }
            return redirect()->to(base_url() . '/XAPI/XAPI_scenarios');
        }
    }

    public function updateScenarioSettingsDetails()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['xsis'])) {
            $data['xsis'] = $_POST['xsis'];
        } else {
            return redirect()->to(base_url() . '/XAPI/XAPI_courses');
        }
        if ($this->request->getPost()) {
            $newdata = [
                'value' => $this->request->getVar('value'),
            ];
            $result = $this->xapi_scenarios_model->updateScenarioSettings($newdata, $data['xsis']);

            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0008'));
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }
            return redirect()->to(base_url() . '/XAPI/XAPI_scenarios/viewScenarioSettings');
        }
    }

    public function changeSenarioSettingValues()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['xsis'])) {
            $data['xsis'] = $_POST['xsis'];
        } else {
            return redirect()->to(base_url() . '/XAPI/XAPI_courses');
        }
        $data['course_header'] = 'Courses';
        $data['course_header_link'] = 'User_login/client_users/manageTrainings';
        $data['header'] = 'Scenarios - ' . $_SESSION['course_name'];
        $data['header_link'] = 'XAPI/XAPI_scenarios';
        $data['header2'] = 'Scenarios - ' . $_SESSION['scenario_name'];
        $data['header2_link'] = 'XAPI/XAPI_scenarios/viewScenarioSettings';
        $data['header3'] = 'Edit Scenarios Settings Values';
        $data['edit_link'] = 'XAPI/XAPI_scenarios/updateScenarioSettingsDetails';

        $data['scenario_details'] = $this->xapi_scenarios_model->getScenarioSettingValue($data['xsis']);
        $xiv = $data['scenario_details'][0]['xiv'];
        $data['getDropDownValues'] = $this->scorm_course_model->getDropDownValues($xiv);
        echo view('templates/header_view', $data);
        echo view('XAPI/edit_scenario_details', $data);
        echo view('templates/footer_view');
    }

    public function view_assigned_users()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['xs'])) {
            $data['xs'] = $_POST['xs'];
            $_SESSION['xs'] =  $data['xs'];
            $data['scenario_name'] = $_POST['scenario_name'];
            $_SESSION['scenario_name'] = $data['scenario_name'];
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_SESSION['xs'])) {
            $data['xs'] = $_SESSION['xs'];
            $data['scenario_name'] = $_SESSION['scenario_name'];
            $data['scourse_id'] = $_SESSION['scourse_id'];
        } else {
            return redirect()->to(base_url() . '/XAPI/XAPI_courses');
        }
        $client_id =  session()->get('client');
        $data['course_header'] = 'AR/VR/Sim Courses';
        $data['course_header_link'] = 'XAPI/XAPI_courses';
        $data['header'] = 'Scenarios {' . $_SESSION['course_name'] . '}';
        $data['header_link'] = 'XAPI/XAPI_scenarios';
        $data['header2'] = 'Scenarios Users {' . $_SESSION['scenario_name'] . '}';
        $data['form_link'] = 'XAPI/XAPI_users/userallcoursedetails';
        $data['form_link1'] = 'XAPI/XAPI_scenarios/deleteEnrollment';
        $data['getUserlatestCourse'] = $this->scorm_client_model->getUserlatestclientCourse($data['scourse_id'], $client_id);
        $data['getUserlatestclientCourseByScenario'] = $this->scorm_client_model->getUserlatestclientCourseByScenario($data['scourse_id'], $client_id);
        // echo "<pre>";
        // print_r($data['getUserlatestclientCourseByScenario']);
        // exit();
        $data['getUserclientlist'] = $this->users_model->getUserclientlist($client_id);
        echo view('templates/header_view', $data);
        echo view('XAPI/scenarios_users', $data);
        echo view('templates/footer_view');
    }
    public function view_Manageassigned_users()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['xs'])) {
            $data['xs'] = $_POST['xs'];
            $_SESSION['xs'] =  $data['xs'];
            $data['scenario_name'] = $_POST['scenario_name'];
            $_SESSION['scenario_name'] = $data['scenario_name'];
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_SESSION['xs'])) {
            $data['xs'] = $_SESSION['xs'];
            $data['scenario_name'] = $_SESSION['scenario_name'];
            $data['scourse_id'] = $_SESSION['scourse_id'];
        } else {
            return redirect()->to(base_url() . '/XAPI/XAPI_courses');
        }
        $client_id =  session()->get('client');
        $data['course_header'] = 'Courses';
        $data['course_header_link'] = 'User_login/client_users/manageTrainings';
        $data['header'] = 'Scenarios {' . $_SESSION['course_name'] . '}';
        $data['header_link'] = 'XAPI/XAPI_scenarios/XAPIMangeCourses';
        $data['header2'] = 'Scenarios Users {' . $_SESSION['scenario_name'] . '}';
        $data['form_link'] = 'XAPI/XAPI_users/manageuserallcoursedetails';
        $data['form_link1'] = 'XAPI/XAPI_scenarios/deleteEnrollment';
        $data['getUserlatestCourse'] = $this->scorm_client_model->getUserlatestclientCourse($data['scourse_id'], $client_id);
        $data['getUserlatestclientCourseByScenario'] = $this->scorm_client_model->getUserlatestclientCourseByScenario($data['scourse_id'], $data['xs'], $client_id);
        // echo "<pre>";
        // print_r($data['getUserlatestclientCourseByScenario']);
        // exit();
        $data['getUserclientlist'] = $this->users_model->getUserclientlist($client_id);
        echo view('templates/header_view', $data);
        echo view('XAPI/scenarios_users', $data);
        echo view('templates/footer_view');
    }
    public function deleteEnrollment()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['user_assign_id'])) {
            $data['user_assign_id'] = $_POST['user_assign_id'];
        } else {
            return redirect()->to(base_url() . 'User_login/client_users/usersassigned_report');
        }
        if ($this->request->getPost()) {
            $newdata = [
                'status' => 2,
                
                 
            ];
            $result = $this->xapi_scenarios_model->delEnrollment($newdata, $data['user_assign_id']);

            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0020'));
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }
            return redirect()->to(base_url() . 'User_login/client_users/usersassigned_report');
        }
    }

    public function add_user_to_course_scenario()
    {
        $newData = [
            'course_id' => $_POST['course_id'],
            'scenario_id' => $_POST['scenario_id'],
            'id_user' => $_POST['userid'],
            'createdby' => session()->get('id_user'),
            'createdon' => time(),
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->xapi_scenarios_model->addusertocourses($newData);
        echo json_encode($result);
    }
    function assignUserstoScenario()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['user_id']) && isset($_POST['role']) && isset($_POST['xs'])) {
            $data['user_id'] = $_POST['user_id'];
            $data['role'] = $_POST['role'];
            $data['scenario_id'] = $_POST['xs'];
        } else {
            return redirect()->to(base_url() . 'XAPI/XAPI_scenarios/viewScenarioDetails');
        }
        $postresult = $this->xapi_scenarios_model->getassignUserRoletoScenario( $_POST['scourse_id'],$data['user_id']);
        session()->set('scourse_id',  $_POST['scourse_id']);
        session()->set('course_name',  $_POST['course_name']);
        session()->set('xs',  $data['scenario_id']);
        if ($postresult) {
            echo '<script>
            if (confirm("User already added to other Scenario. Do you want add to new Scenario?")) {
                // Proceed with deletion  
                window.location.href = "' . base_url() . 'XAPI/XAPI_scenarios/reassignusertoscenario?scourse_id=' . $_POST['scourse_id'] . '&course_name=' . $_POST['course_name'] . '&user_id=' . $data['user_id'] . '&role=' . $data['role'] . '&scenario_id=' . $data['scenario_id'] . '&user_assign_id=' . $postresult[0]['user_assign_id'] . '";
            } else {
                window.location.href = "' . base_url() . 'XAPI/XAPI_scenarios/viewScenarioDetails";
            }
             </script>';
        } else {
            $newdata = [
                'course_id' => $_POST['scourse_id'],
                'scenario_id' =>  $data['scenario_id'],
                'role' =>  $data['role'],
                'user_id' =>  $data['user_id'],
                'status' => 1,
                'createdby' => session()->get('id_user'),
                'createdon' => time(),
            ];
            $result = $this->xapi_scenarios_model->assignUserRoletoScenario($newdata);
            // echo json_encode($result);
            // session()->set('scourse_id',  $_POST['scourse_id']);
            // session()->set('course_name',  $_POST['course_name']);
            // session()->set('xs',  $data['scenario_id']);
            if ($result) {

                session()->setFlashdata('success', lang('Messages.Success_0018'));
            } else {

                session()->setFlashdata('error', lang('Messages.Error_0001'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }
            return redirect()->to(base_url() . 'XAPI/XAPI_scenarios/viewScenarioDetails');
        }
    }
    function reassignusertoscenario()
    {

        $data['user_id'] = $_GET['user_id'];
        $data['role'] = $_GET['role'];
        $data['scenario_id'] = $_GET['scenario_id'];
        $user_assign_id = $_GET['user_assign_id'];
        $deletedata = [
            'status' => 0,
            
             
        ];
        $scenarioresult = $this->xapi_scenarios_model->deleteAssigdOlderScenariouser($deletedata,$data['user_id'],$_GET['scourse_id']);
        if ($scenarioresult) {
            $newdata = [
                'course_id' => $_GET['scourse_id'],
                'scenario_id' =>  $data['scenario_id'],
                'role' =>  $data['role'],
                'user_id' =>  $data['user_id'],
                'status' => 1,
                'createdby' => session()->get('id_user'),
                'createdon' => time(),
            ];
            $result = $this->xapi_scenarios_model->assignUserRoletoScenario($newdata);
            session()->set('scourse_id',  $_GET['scourse_id']);
            session()->set('course_name',  $_GET['course_name']);
            session()->set('xs',  $data['scenario_id']);
            if ($result) {

                session()->setFlashdata('success', lang('Messages.Success_0018'));
            } else {

                session()->setFlashdata('error', lang('Messages.Error_0001'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }
            return redirect()->to(base_url() . 'XAPI/XAPI_scenarios/viewScenarioDetails');
        }
    }
    function editAssignedScenariouser()
    {
        $data = [];
        helper(['form']);
        // print_r($_POST);
        // exit();
        if (isset($_POST['scourse_id']) && isset($_POST['course_name'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] =  $data['scourse_id'];
            $data['course_name'] = $_POST['course_name'];
            $_SESSION['course_name'] =  $data['course_name'];
        } else if (isset($_SESSION['scourse_id']) && isset($_SESSION['course_name'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
            $data['course_name'] = $_SESSION['course_name'];
        } else {
            return redirect()->to(base_url() . 'XAPI/XAPI_courses');
        }
        if (isset($_POST['user_assign_id'])) {
            $data['user_assign_id'] = $_POST['user_assign_id'];
            $data['xs'] = $_POST['xs'];
        }
        $data['course_header'] = 'Courses';
        $data['course_header_link'] = 'User_login/client_users/manageTrainings';
        $data['header'] = 'Scenarios {' . $_SESSION['course_name'] . '}';
        $data['header_link'] = 'XAPI/XAPI_scenarios/viewScenarioDetails';
        $data['header2'] = 'Edit User Role';
        $data['edit_role'] = 'XAPI/XAPI_scenarios/updateUserRole';
        $data['getScenarioUsers'] = $this->xapi_scenarios_model->getScenarioUsers($data['user_assign_id']);
        echo view('templates/header_view', $data);
        echo view('XAPI/XAPI_edit_users_role', $data);
        echo view('templates/footer_view');
    }
    function updateUserRole()
    {
        $data = [];
        // print_r($_POST);
        // exit();
        helper(['form']);
        if (isset($_POST['scourse_id']) && isset($_POST['course_name'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] =  $data['scourse_id'];
            $data['course_name'] = $_POST['course_name'];
            $_SESSION['course_name'] =  $data['course_name'];
        } else if (isset($_SESSION['scourse_id']) && isset($_SESSION['course_name'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
            $data['course_name'] = $_SESSION['course_name'];
        } else {
            return redirect()->to(base_url() . 'XAPI/XAPI_courses');
        }
        if (isset($_POST['user_assign_id'])) {
            $data['user_assign_id'] = $_POST['user_assign_id'];
            $data['xs'] = $_POST['xs'];
        } else {
            return redirect()->to(base_url() . 'XAPI/XAPI_scenarios');
        }
        if ($this->request->getPost()) {
            $newdata = [
                'role' => $_POST['role'],
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $result = $this->xapi_scenarios_model->editAssignedScenariouser($newdata, $data['user_assign_id'], $data['xs']);
            if ($result) {
                session()->set('scourse_id',  $data['scourse_id']);
                session()->set('course_name',  $data['course_name']);
                session()->set('xs',  $data['xs']);
                session()->setFlashdata('success', lang('Messages.Success_0008'));
                return redirect()->to(base_url() . 'XAPI/XAPI_scenarios/viewScenarioDetails');
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }
            return redirect()->to(base_url() . 'XAPI/XAPI_scenarios/viewScenarioDetails');
        }
    }
    function deleteAssignedScenariouser()
    {
        $data = [];

        helper(['form']);
        if (isset($_POST['user_assign_id'])) {
            $data['user_assign_id'] = $_POST['user_assign_id'];
        } elseif (isset($_GET['user_assign_id'])) {
            $data['user_assign_id'] = $_GET['user_assign_id'];
        }else{
            return redirect()->to(base_url() . 'XAPI/XAPI_scenarios');
        }
        session()->set('scourse_id',  $_POST['scourse_id']);
        session()->set('course_name',  $_POST['course_name']);
        session()->set('xs',  $_POST['xs']);
        if ($this->request->getPost()) {
            $newdata = [
                'status' => 0,
                
                 
            ];
            $result = $this->xapi_scenarios_model->deleteAssignedScenariouser($newdata, $data['user_assign_id']);
            // echo json_encode($result);
            // print_r($data['user_assign_id']);
            // exit();
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0005'));
                return redirect()->to(base_url() . 'XAPI/XAPI_scenarios/viewScenarioDetails');
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }
            return redirect()->to(base_url() . 'XAPI/XAPI_scenarios/viewScenarioDetails');
        }
    }
}
