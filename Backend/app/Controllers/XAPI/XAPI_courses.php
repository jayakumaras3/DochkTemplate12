<?php

namespace App\Controllers\XAPI;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

use App\Controllers\BaseController;

use ZipArchive;
use App\Models\Settings\Dropdown_model;
use App\Models\SCORM\Scorm_course_model;
use App\Models\Settings\Verbs_model;
use App\Models\Assessment\Assessment_training_model;
use App\Models\User_login\Users_model;
use App\Models\SCORM\Scorm_client_model;
use App\Models\XAPI\XAPI_scenarios_model;
use App\Models\SCORM\Scorm_user_group_model;
use App\Models\SCORM\Scorm_page_model;

#[\AllowDynamicProperties]
class XAPI_courses extends BaseController
{
    private $db;

    public function __construct()
    {
        //$this->login_model = new Login_model();

        $this->dropdown_model = new Dropdown_model();
        $this->verbs_model = new Verbs_model();
        $this->scorm_course_model = new Scorm_course_model();
        $this->assessment_training_model = new Assessment_training_model();
        $this->xapi_scenarios_model = new XAPI_scenarios_model();
        $this->scorm_client_model = new Scorm_client_model();
        $this->users_model = new Users_model();
        $this->scorm_user_group_model = new Scorm_user_group_model();
        $this->scorm_page_model = new Scorm_page_model();
    }
    public function index() //fetch data from projects and project_details table to display
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4', '3'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['header'] = 'AR/VR/Sim Courses';
        $data['create_new_course_link'] = 'XAPI/XAPI_courses/course_add_view';
        $data['settings_link'] = 'XAPI/XAPI_courses/course_settings_view';
        $data['edit_link'] = 'XAPI/XAPI_courses/course_edit_view';
        $data['delete_link'] = 'XAPI/XAPI_courses/deleteCoursedetails';
        $data['typeval'] = 5;
        $data['coursesDetails'] = $this->scorm_course_model->getCoursesDetails(5);
        $data['assignedCourselist'] = $this->scorm_course_model->getassignedCourselist(5);
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/courses_search_view', $data);
        echo view('templates/footer_view');
    }
    public function course_add_view()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['header'] = 'AR/VR/Sim Courses';
        $data['header_link'] = 'XAPI/XAPI_courses';
        $data['sub_header_1'] = 'Create New Course';
        $data['form_link'] = 'XAPI/XAPI_courses/addcourse';

        $data['typeval'] = 5;

        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/courses_add_view', $data);
        echo view('templates/footer_view');
    }
    public function addcourse()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $user = session()->get('username');

        if ($this->request->getPost()) {
            //print_r("sss");
            $rules = [
                'course_name' => 'required',
            ];

            if (!$this->validate($rules)) {
                $data['coursevalidation'] = $this->validator;
            } else {
                $timestamp = time();
                $newdata = [
                    'client_id' => session()->get('client_id'),
                    'course_name' => $this->request->getVar('course_name'),
                    'description' => $this->request->getVar('description'),
                    'objectives' => $this->request->getVar('objectives'),
                    'duration' => $this->request->getVar('duration'),
                    'language' => $this->request->getVar('language'),
                    'course_code' => $this->request->getVar('course_code'),
                    'upload_type' => $this->request->getVar('upload_type'),
                    'type' => '5',
                    'status' => '1',
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),

                ];
                $result = $this->scorm_course_model->addcoursedetails($newdata);

                $courseid = $result['course_id'];
                $logindata = [
                    'scourse_id' => $courseid,
                    'variable_name' => 'login',
                    'variable_description' => 'User logged into the simulation.',
                    'verb' => 'login',
                    'status' => '1',
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                $result = $this->scorm_course_model->addNewOutputVariable($logindata);

                $logindata = [
                    'scourse_id' => $courseid,
                    'variable_name' => 'exit',
                    'variable_description' => 'User exited the simulation.',
                    'verb' => 'exit',
                    'status' => '1',
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                $result = $this->scorm_course_model->addNewOutputVariable($logindata);

                $logindata = [
                    'scourse_id' => $courseid,
                    'variable_name' => 'total_time',
                    'variable_description' => 'Total time spent by user.',
                    'verb' => 'time',
                    'status' => '1',
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                $result = $this->scorm_course_model->addNewOutputVariable($logindata);

                $logindata = [
                    'scourse_id' => $courseid,
                    'variable_name' => 'score',
                    'variable_description' => 'User score.',
                    'verb' => 'score',
                    'status' => '1',
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                $result = $this->scorm_course_model->addNewOutputVariable($logindata);

                $logindata = [
                    'scourse_id' => $courseid,
                    'variable_name' => 'simulation_status',
                    'variable_description' => 'User status on simulation.',
                    'verb' => 'status',
                    'status' => '1',
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                $result = $this->scorm_course_model->addNewOutputVariable($logindata);



                $enabled_default = array(1, 2, 3);
                // print_r($result);
                // exit();
                if ($result) {
                    for ($i = 0; $i < count($enabled_default); $i++) {
                        $tempdata = array(
                            'scourse_id' => $courseid,
                            'type' => $enabled_default[$i],
                            'value' => 'Enabled',
                            'status' => 1,
                            'last_updated_by' => session()->get('id_user'),
                            'last_updated_on' => time(),
                        );
                        $this->assessment_training_model->add_settings($tempdata);
                    }

                    $value_default = array(21, 22, 23, 24);
                    $value_default_input = array('', '', 80, 2);
                    for ($i = 0; $i < count($value_default); $i++) {
                        $tempdata = array(
                            'scourse_id' => $courseid,
                            'type' => $value_default[$i],
                            'value' => $value_default_input[$i],
                            'status' => 1,
                            'last_updated_by' => session()->get('id_user'),
                            'last_updated_on' => time(),
                        );
                        $this->assessment_training_model->add_settings($tempdata);
                    }
                    session()->setFlashdata('success', lang('Messages.Success_0008'));
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
            return redirect()->to(base_url() . 'SCORM/scorm_courses');
        }
    }
    public function course_edit_view()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4','46'])) {
            return $response;
        }
        $data = [];
        helper(['form']);


        $data['header'] = 'AR/VR/Sim Courses';
        $data['header_link'] = 'XAPI/XAPI_courses';
        $data['sub_header_1'] = 'Edit Course';
        $data['form_link'] = 'XAPI/XAPI_courses/editcourse';

        $data['typeval'] = 5;

        $user = session()->get('username');
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        } else {
            return redirect()->to(base_url() . 'SCORM/scorm_courses');
        }
        $getCourseData = $this->scorm_course_model->getCourseDetails($data['scourse_id']);
        $data['row'] = $getCourseData[0];
        $data['allmetadata'] = $this->scorm_course_model->getAllMetadata(9);
        $data['allcategories'] = $this->scorm_course_model->getAllMetadata(10);
        $data['getAssignmetadata'] = $this->scorm_course_model->getAssignmetadata($data['scourse_id']);
        $data['getAssigncategorydata'] = $this->scorm_course_model->getAssigncategorydata($data['scourse_id']);
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/courses_edit_view', $data);
        echo view('templates/footer_view');
    }


    public function editcourse()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $user = session()->get('username');
        $scourse_id = $this->request->getVar('scourse_id');
        $data['scourse_id'] = $scourse_id;
        $getCourseData = $this->scorm_course_model->getCourseDetails($scourse_id);
        // $data['allmetadata'] = $this->scorm_course_model->getAllMetadata(9);
        // $data['allcategories'] = $this->scorm_course_model->getAllMetadata(10);
        // $data['getAssignmetadata'] = $this->scorm_course_model->getAssignmetadata($data['scourse_id']);
        // $data['getAssigncategorydata'] = $this->scorm_course_model->getAssigncategorydata($data['scourse_id']);
        $data['row'] = $getCourseData[0];
        if ($this->request->getPost()) {
            $newdata = [
                'language' => $this->request->getVar('language'),
                'course_code' => $this->request->getVar('course_code'),
                'scourse_id' => $this->request->getVar('scourse_id'),
                'course_name' => $this->request->getVar('course_name'),
                'description' => $this->request->getVar('description'),
                'objectives' => $this->request->getVar('objectives'),
                'duration' => $this->request->getVar('duration'),
                'launch_link' => $this->request->getVar('launch_link'),
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $result = $this->scorm_course_model->editcoursedetails($newdata, $scourse_id);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0008'));
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }
            return redirect()->to(base_url() . 'SCORM/scorm_courses/course_edit_view');
        }
    }

    public function activate()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4','46'])) {
            return $response;
        }
        $scourse_id = $this->request->getVar('scourse_id');
        $newdata = [
            'upload' => $this->request->getVar('filename'),
        ];
        $result = $this->scorm_course_model->editcoursedetails($newdata, $scourse_id);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0008'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . 'SCORM/scorm_courses/course_settings_view');
    }

    public function del_folder()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4','46'])) {
            return $response;
        }
        if (isset($_POST['folderloc'])) {
            $dirPath = $_POST['folderloc'];
            $couser_id = $_POST['scourse_id'];
            $folder_name = $_POST['folder_name'];

            $dir = $dirPath . DIRECTORY_SEPARATOR;
            $this->emptyDir($dir);
            rmdir($dir);
            $newdata = [
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
                'status' => '0',
            ];
            $this->scorm_course_model->delscormfiles($newdata, $couser_id, $folder_name);
            session()->setFlashdata('success', lang('Messages.Success_0005'));
        }
        return redirect()->to(base_url() . 'SCORM/scorm_courses/course_edit_view');
    }
    public function del_file()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        if (isset($_POST['tab'])) {
            $_SESSION['tab'] = $_POST['tab'];
        }
        if (isset($_POST['fileloc'])) {
            $dirPath = $_POST['fileloc'];
            $deleted = false;
            if (is_file($dirPath)) {
                try {
                    $deleted = unlink($dirPath);
                } catch (\ErrorException $e) {
                    log_message('error', 'del_file: unable to delete {file}: {msg}', [
                        'file' => $dirPath,
                        'msg'  => $e->getMessage(),
                    ]);
                }
            }
            if ($deleted) {
                session()->setFlashdata('success', lang('Messages.Success_0005'));
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
            }
        }
        return redirect()->to(base_url() . 'SCORM/scorm_courses/course_settings_view');
    }
    public function emptyDir($dir)
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4','46'])) {
            return $response;
        }
        if (is_dir($dir)) {
            $scn = scandir($dir);
            foreach ($scn as $files) {
                if ($files !== '.') {
                    if ($files !== '..') {
                        if (!is_dir($dir . '/' . $files)) {
                            unlink($dir . '/' . $files);
                        } else {
                            $this->emptyDir($dir . '/' . $files);
                            rmdir($dir . '/' . $files);
                        }
                    }
                }
            }
        }
    }

    public function scenarios()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);

        if (isset($_POST['scourse_id']) && isset($_POST['course_name'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
            $data['course_name'] = $_POST['course_name'];
            $_SESSION['course_name'] = $data['course_name'];
        } else if (isset($_GET['scourse_id']) && isset($_GET['course_name'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
            $data['course_name'] = $_GET['course_name'];
        } else if (isset($_SESSION['scourse_id']) && isset($_SESSION['course_name'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
            $data['course_name'] = $_SESSION['course_name'];
        } else {
            return redirect()->to(base_url() . 'SCORM/scorm_courses');
        }

        $data['header'] = 'AR/VR/Sim Courses';
        $data['header_link'] = 'XAPI/XAPI_courses';
        $data['sub_header_1'] = 'Scenarios';

        $data['form_link'] = 'XAPI/XAPI_courses/course_settings_view';

        $data['typeval'] = 5;

        $data['scenario_list'] = $this->scorm_course_model->getAllScenarios($data['scourse_id']);
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/courses_scenarios', $data);
        echo view('templates/footer_view');
    }
    public function input_variables()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);

        if (isset($_POST['scourse_id']) && isset($_POST['course_name'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
            $data['course_name'] = $_POST['course_name'];
            $_SESSION['course_name'] = $data['course_name'];
        } else if (isset($_GET['scourse_id']) && isset($_GET['course_name'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
            $data['course_name'] = $_GET['course_name'];
        } else if (isset($_SESSION['scourse_id']) && isset($_SESSION['course_name'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
            $data['course_name'] = $_SESSION['course_name'];
        } else {
            return redirect()->to(base_url() . 'SCORM/scorm_courses');
        }

        $data['header'] = 'Courses';
        $data['header_link'] = 'SCORM/SCORM_courses';
        $data['sub_header_1'] = 'Course Settings';
        $data['sub_header_1_link'] = 'XAPI/XAPI_courses/course_settings_view';
        $data['sub_header_2'] = $data['course_name'] . ' - Input Variables';
        $data['form_link'] = 'XAPI/XAPI_courses/course_settings_view';

        $data['typeval'] = 5;

        $data['inputVariables'] = $this->scorm_course_model->getInputVariables($data['scourse_id']);
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/courses_input_variables', $data);
        echo view('templates/footer_view');
    }
    public function output_variables()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);

        if (isset($_POST['scourse_id']) && isset($_POST['course_name'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
            $data['course_name'] = $_POST['course_name'];
            $_SESSION['course_name'] = $data['course_name'];
        } else if (isset($_GET['scourse_id']) && isset($_GET['course_name'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
            $data['course_name'] = $_GET['course_name'];
        } else if (isset($_SESSION['scourse_id']) && isset($_SESSION['course_name'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
            $data['course_name'] = $_SESSION['course_name'];
        } else {
            return redirect()->to(base_url() . 'SCORM/scorm_courses');
        }

        $data['header'] = 'Courses';
        $data['header_link'] = 'SCORM/scorm_courses';
        $data['sub_header_1'] = 'Course Settings';
        $data['sub_header_1_link'] = 'XAPI/XAPI_courses/course_settings_view';
        $data['sub_header_2'] = $data['course_name'] . ' - Output Variables';
        $data['form_link'] = 'XAPI/XAPI_courses/course_settings_view';

        $data['typeval'] = 5;
        $data['verbs'] = $this->verbs_model->getAllVerbs();
        $data['outputVariables'] = $this->scorm_course_model->getOutputVariables($data['scourse_id']);
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/courses_output_variables', $data);
        echo view('templates/footer_view');
    }
    public function view_input_variable()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_SESSION['scourse_id']) && isset($_SESSION['course_name'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
            $data['course_name'] = $_SESSION['course_name'];
        } else {
            return redirect()->to(base_url() . 'SCORM/scorm_courses');
        }

        if (isset($_POST['xiv'])) {
            $data['xiv'] = $_POST['xiv'];
            $_SESSION['xiv'] = $data['xiv'];
        } else if (isset($_SESSION['xiv'])) {
            $data['xiv'] = $_SESSION['xiv'];
        } else {
            return redirect()->to(base_url() . 'SCORM/scorm_courses');
        }

        $data['header'] = 'Courses';
        $data['header_link'] = 'SCORM/SCORM_courses';
        $data['sub_header_1'] = 'Course Settings';
        $data['sub_header_1_link'] = 'XAPI/XAPI_courses/course_settings_view';
        $data['sub_header_2'] = $data['course_name'] . ' - Input Variables';
        $data['sub_header_2_link'] = 'XAPI/XAPI_courses/input_variables';
        $data['sub_header_3'] = 'Edit Input Variables';

        $data['typeval'] = 5;

        $data['inputVariables_details'] = $this->scorm_course_model->getInputVariablesDetails($data['xiv']);
        $data['getDropDownValues'] = $this->scorm_course_model->getDropDownValues($data['xiv']);
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/view_input_variables', $data);
        echo view('templates/footer_view');
    }
    public function view_output_variable()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_SESSION['scourse_id']) && isset($_SESSION['course_name'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
            $data['course_name'] = $_SESSION['course_name'];
        } else {
            return redirect()->to(base_url() . 'SCORM/scorm_courses');
        }

        if (isset($_POST['xov'])) {
            $data['xov'] = $_POST['xov'];
            $_SESSION['xov'] = $data['xov'];
        } else if (isset($_SESSION['xov'])) {
            $data['xov'] = $_SESSION['xov'];
        } else {
            return redirect()->to(base_url() . 'SCORM/scorm_courses');
        }

        $data['header'] = 'Courses';
        $data['header_link'] = 'SCORM/scorm_courses';
        $data['sub_header_1'] = 'Course Settings';
        $data['sub_header_1_link'] = 'XAPI/XAPI_courses/course_settings_view';
        $data['sub_header_2'] = $data['course_name'] . ' - Output Variables';
        $data['sub_header_2_link'] = 'XAPI/XAPI_courses/output_variables';
        $data['sub_header_3'] = 'Edit Output Variables';

        $data['typeval'] = 5;

        $data['outputVariables_details'] = $this->scorm_course_model->getOutputVariablesDetails($data['xov']);
        $data['verbs'] = $this->verbs_model->getAllVerbs();
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/view_output_variables', $data);
        echo view('templates/footer_view');
    }


    public function add_input_variable()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $newdata = [
            'variable_name' => $this->request->getVar('var_name'),
            'variable_type' => $this->request->getVar('var_type'),
            'variable_description' => $this->request->getVar('description'),
            'instructions' => $this->request->getVar('instructions'),
            'scourse_id' => $this->request->getVar('scourse_id'),
            'createdon' => time(),
            'createdby' => session()->get('id_user'),
            'status' => '1',
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_course_model->addNewInputVariable($newdata);
        if ($result) {
            $inputVariables = $this->scorm_course_model->getInputVariables($newdata['scourse_id']);
            $getscenarioids = $this->xapi_scenarios_model->getScenarioIDs($newdata['scourse_id']);

            if (!empty($getscenarioids))
                foreach ($getscenarioids as $scenarioid) {
                    $scenario_id = $scenarioid['xs'];

                    foreach ($inputVariables as $iv) {
                        if ($iv['variable_type'] == 1) {
                            $default_value = $iv['default_text'];
                        } else {
                            $default_value = 0;
                        }
                        $Scenariosettingsvarible = $this->xapi_scenarios_model->getScenariosettingsvarible($scenario_id, $iv['xiv']);
                        if (!empty($Scenariosettingsvarible)) {
                            continue;
                        }
                        $newdata_settings = [
                            'scenario_id' => $scenario_id,
                            'input_variable' => $iv['xiv'],
                            'input_variable_type' => $iv['variable_type'],
                            'value' => $default_value,
                            'status' => '1',
                            'createdby' => session()->get('id_user'),
                            'createdon' => time(),
                            'last_updated_by' => session()->get('id_user'),
                            'last_updated_on' => time(),
                        ];
                        $this->xapi_scenarios_model->setScenarioSettings($newdata_settings);
                    }
                }
            session()->setFlashdata('success', lang('Messages.Success_0011'));
        } else {
            session()->setFlashdata('error', 'Error in submitting the file.');
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . 'XAPI/XAPI_courses/input_variables');
    }

    public function add_output_variable()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $newdata = [
            'variable_name' => $this->request->getVar('var_name'),
            'verb' => $this->request->getVar('verbs'),
            'variable_description' => $this->request->getVar('description'),
            'feedback' => $this->request->getVar('feedback'),
            'scourse_id' => $this->request->getVar('scourse_id'),
            'createdon' => time(),
            'createdby' => session()->get('id_user'),
            'status' => '1',
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_course_model->addNewOutputVariable($newdata);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0011'));
        } else {
            session()->setFlashdata('error', 'Error in submitting the file.');
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . 'XAPI/XAPI_courses/output_variables');
    }
    public function del_input_variable()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $xiv = $_POST['xiv'];
        $newdata = [
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => '0',
        ];
        $result = $this->scorm_course_model->delVariable($newdata, $xiv);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
        } else {
            session()->setFlashdata('error', 'Error in submitting the file.');
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . 'XAPI/XAPI_courses/input_variables');
    }
    public function del_output_variable()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $xov = $_POST['xov'];
        $newdata = [


            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => '0',
        ];
        $result = $this->scorm_course_model->updateOuptutVariable($newdata, $xov);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
        } else {
            session()->setFlashdata('error', 'Error in submitting the file.');
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . 'XAPI/XAPI_courses/output_variables');
    }

    public function update_input_variable()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $xiv = $_POST['xiv'];
        $newdata = [
            'variable_name' => $this->request->getVar('var_name'),
            'variable_type' => $this->request->getVar('var_type'),
            'instructions' => $this->request->getVar('instructions'),
            'variable_description' => $this->request->getVar('description'),
            'createdon' => time(),
            'createdby' => session()->get('id_user'),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_course_model->delVariable($newdata, $xiv);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0008'));
        } else {
            session()->setFlashdata('error', 'Error in submitting the file.');
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . 'XAPI/XAPI_courses/input_variables');
    }
    public function update_output_variable()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $xov = $_POST['xov'];
        $newdata = [
            'variable_name' => $this->request->getVar('var_name'),
            'variable_description' => $this->request->getVar('variable_description'),
            'feedback' => $this->request->getVar('feedback'),
            'verb' => $this->request->getVar('verbs'),
            'createdon' => time(),
            'createdby' => session()->get('id_user'),
        ];
        $result = $this->scorm_course_model->updateOuptutVariable($newdata, $xov);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0008'));
        } else {
            session()->setFlashdata('error', 'Error in submitting the file.');
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . 'XAPI/XAPI_courses/output_variables');
    }

    public function add_default_text_input_variable()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $xiv = $_POST['xiv'];
        $newdata = [
            'default_text' => $this->request->getVar('default_text'),
        ];
        $result = $this->scorm_course_model->delVariable($newdata, $xiv);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0008'));
        } else {
            session()->setFlashdata('error', 'Error in submitting the file.');
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . 'XAPI/XAPI_courses/input_variables');
    }

    public function add_dropdown_values()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $dropdownval = $_POST['dropdown_val'];
        $xiv = $_POST['xiv'];
        $totalrows = $this->scorm_course_model->checkdroddownvalexist($dropdownval, $xiv);
        if ($totalrows == 0) {
            $newdata = [
                'value' => $this->request->getVar('dropdown_val'),
                'text' => $this->request->getVar('drop_down_text'),
                'xiv' => $this->request->getVar('xiv'),
                'createdon' => time(),
                'createdby' => session()->get('id_user'),
                'status' => '1',
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $result = $this->scorm_course_model->addDropDown($newdata);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
            } else {
                session()->setFlashdata('error', 'Error in submitting the file.');
                session()->setFlashdata('alert-class', 'alert-danger');
            }
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . 'SCORM/scorm_courses/view_input_variable');
    }
    public function del_dropdown_variable()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $xidv = $_POST['xidv'];
        $newdata = [


            'status' => '0',
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_course_model->deleteDropdown($newdata, $xidv);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . 'SCORM/scorm_courses/view_input_variable');
    }
    public function deleteCoursedetails()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $scourse_id = $_POST['scourse_id'];
        $newdata = [


            'status' => '0',
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_course_model->deltecourse($newdata, $scourse_id);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . 'SCORM/scorm_courses');
    }

    public function deleteasignmetadetails()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $mc_id = $_POST['mc_id'];
        $newdata = [


            'status' => '0',
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_course_model->deleteassignmeta($newdata, $mc_id);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
            return redirect()->to(base_url() . 'SCORM/scorm_courses/course_edit_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'SCORM/scorm_courses/course_edit_view');
        }
    }

    public function upload_view()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['scourse_id'] = $_POST['scourse_id'];
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/upload_view', $data);
        echo view('templates/footer_view');
    }
    public function thumbnail_upload()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $data = [];
        helper(['filesystem']);
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        }

        if ($this->request->getPost()) {
            $rules = [
                'file' => 'uploaded[file]|max_size[file,150]|ext_in[file,jpg]'
            ];
            if (!$this->validate($rules)) {
                $data['thumbnailvalidation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();
                        if (!is_dir(FCPATH . 'assets/assets/uploads/SCORM_course_thumbnail/' . $data['scourse_id'])) {
                            mkdir('assets/assets/uploads/SCORM_course_thumbnail/' . $data['scourse_id'], 0777, true);
                        }
                        if (file_exists(FCPATH . 'assets/assets/uploads/SCORM_course_thumbnail/' . $data['scourse_id'] . "/" . $filename)) {
                            session()->setFlashdata('error', $filename . ' ' . lang('Messages.Success_0051'));
                        } else {
                            if ($file->move(FCPATH . 'assets/assets/uploads/SCORM_course_thumbnail/' . $data['scourse_id'], $filename)) {
                                $filepath = FCPATH . 'assets/assets/uploads/SCORM_course_thumbnail/' . $data['scourse_id'] . '/' . $filename;
                                $newdata = [
                                    'thumbnail' => $filename,
                                ];
                                $result = $this->scorm_course_model->editcoursedetails($newdata, $data['scourse_id']);
                                if ($result) {
                                    session()->setFlashdata('success', lang('Messages.Success_0009'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                } else {
                                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                }
                            }
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                    }
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
        }
        return redirect()->to(base_url() . 'SCORM/scorm_courses/course_settings_view');
    }
    function scorm_upload()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $data = [];
        helper(['filesystem']);
        $data = [];
        helper(['filesystem']);
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        }
        if ($this->request->getPost()) {
            if ($file = $this->request->getFile('zip_file')) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $filename = $file->getName();
                    $timestamp = time();
                    $extension = pathinfo($filename, PATHINFO_EXTENSION);
                    if ($extension == 'zip') {
                        if (!is_dir(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp)) {
                            mkdir('assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp, 0777, true);
                        }
                        if (file_exists(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp)) {
                            $getfiles = glob(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp . '/*');
                            // print_r($getfiles);
                            //exit();

                            $targetzip = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp . '/' . $filename;
                            $filenoext = basename($filename, '.zip');  // absolute path to the directory where zipper.php is in (lowercase)
                            $filenoext = basename($filenoext, '.ZIP');  // absolute path to the directory where zipper.php is in (when uppercase)
                            //$targetdir = $path . $filenoext; // target directory

                            if ($file->move(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp, $filename)) {
                                $zip = new ZipArchive();
                                $x = $zip->open($targetzip);
                                if ($x === true) {
                                    $zip->extractTo(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp); // place in the directory with same name  
                                    $zip->close();
                                    unlink($targetzip);
                                }
                            }

                            $newdata = [
                                'upload' => $timestamp,
                            ];
                            $result = $this->scorm_course_model->editcoursedetails($newdata, $data['scourse_id']);

                            $fileupload = [
                                'description' => $_POST['description'],
                                'course_id' => $data['scourse_id'],
                                'folder' => $timestamp,
                                'status' => 1,
                                'createdby' => session()->get('id_user'),
                                'createdon' => time(),
                                'last_updated_by' => session()->get('id_user'),
                                'last_updated_on' => time(),
                            ];
                            $this->scorm_course_model->insertFileuploaddata($fileupload);

                            return json_encode($result);
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                    }
                }
            }
        }
        return redirect()->to(base_url() . 'SCORM/scorm_courses/course_edit_view');
    }
    function overwriteupload_zip()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        helper(['filesystem']);

        // Get scourse_id from POST/GET/SESSION
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } elseif (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } elseif (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        }

        $timestamp = $_POST['timestamp'];

        if ($this->request->getPost()) {
            if ($file = $this->request->getFile('zip_file')) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $filename = $file->getName();
                    $extension = pathinfo($filename, PATHINFO_EXTENSION);

                    if ($extension == 'zip') {
                        $targetDir = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp;

                        // Create folder if not exists
                        if (!is_dir($targetDir)) {
                            mkdir($targetDir, 0777, true);
                        }

                        if (file_exists($targetDir)) {
                            $targetzip = $targetDir . '/' . $filename;

                            // ✅ Move uploaded file and force overwrite
                            if ($file->move($targetDir, $filename, true)) {
                                $zip = new ZipArchive();
                                $x = $zip->open($targetzip);

                                if ($x === true) {
                                    // ✅ Extract and overwrite existing files
                                    $zip->extractTo($targetDir);
                                    $zip->close();

                                    // Delete the zip after extraction
                                    unlink($targetzip);
                                }
                            }

                            // Update course details
                            $newdata = [
                                'upload' => $timestamp,
                            ];
                            $result = $this->scorm_course_model->editcoursedetails($newdata, $data['scourse_id']);

                            // Insert file upload data
                            $fileupload = [
                                // 'description' => $_POST['description'],
                                'course_id' => $data['scourse_id'],
                                'folder' => $timestamp,
                                'status' => 1,
                                'createdby' => session()->get('id_user'),
                                'createdon' => time(),
                                'last_updated_by' => session()->get('id_user'),
                                'last_updated_on' => time(),
                            ];
                            $this->scorm_course_model->insertFileuploaddata($fileupload);
                            session()->setFlashdata('success', lang('Messages.Success_0008'));
                            return redirect()->to(base_url() . 'SCORM/scorm_courses/course_edit_view');
                        }
                    } else {
                        session()->setFlashdata('error', 'Only ZIP files are allowed!');
                        session()->setFlashdata('alert-class', 'alert-danger');
                    }
                }
            }
        }

        return redirect()->to(base_url() . 'SCORM/scorm_courses/course_edit_view');
    }

    public function view_client_course_assigned()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['header'] = 'AR/VR/Sim Clients';
        $data['header_link'] = 'XAPI/xapi_client';
        $data['sub_header_1'] = 'Add Courses to Client';
        $data['form_link1'] = 'XAPI/xapi_client/delete_assigned_client_course';
        $data['form_link2'] = 'XAPI/xapi_client/add_course_to_client';
        $data['form_link3'] = 'XAPI/xapi_client/add_group_to_client';
        $data['form_link4'] = 'XAPI/xapi_client/usersassigned_report';
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);
        if (in_array('4', $arrayuserlevel) || in_array('6', $arrayuserlevel)) {
            if (isset($_POST['client_id'])) {
                $data['client_id'] = $_POST['client_id'];
                $_SESSION['client_id'] = $data['client_id'];
            } else if (isset($_GET['client_id'])) {
                $data['client_id'] = $_GET['client_id'];
            } else if (isset($_SESSION['client_id'])) {
                $data['client_id'] = $_SESSION['client_id'];
            }
            $data['all_courses'] = $this->scorm_course_model->getCoursesDetails(1);
            $data['coursegroupdata'] = $this->scorm_course_group_model->getCoursegroupdate(1);
            $data['getAllCoursesForClient'] = $this->scorm_client_model->getAllCoursesForClient($data['client_id'], 1);
            echo view('templates/header_view');
            echo view('SCORM/scorm_client/sc_client_courses_assigned', $data);
            echo view('templates/footer_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            return redirect()->to(base_url('dashboard'));
        }
    }

    public function usersassigned_report()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);
        if (in_array('4', $arrayuserlevel) || in_array('6', $arrayuserlevel) || in_array('44', $arrayuserlevel)) {
            $client_id = session()->get('client');
            $data['client_id'] = $client_id;
            if (isset($_POST['scourse_id'])) {
                $data['scourse_id'] = $_POST['scourse_id'];
                $_SESSION['scourse_id'] = $data['scourse_id'];
            } else if (isset($_GET['scourse_id'])) {
                $data['scourse_id'] = $_GET['scourse_id'];
            } elseif ($_SESSION['scourse_id'] && $_SESSION['scourse_id']) {
                $data['scourse_id'] = $_SESSION['scourse_id'];
            }
            $data['coursename'] = $this->scorm_client_model->getCoursename($data['scourse_id']);
            $data['header_link'] = 'XAPI/xapi_client/view_client_course_assigned';
            $data['header_sub_link'] = 'XAPI/xapi_client/usersassigned_report';
            $data['form_link'] = 'XAPI/xapi_client/userallcoursedetails';
            $data['header_link_name'] = 'Add Courses to Client';
            $data['getUserlatestCourse'] = $this->scorm_client_model->getUserlatestCourse($data['scourse_id']);
            $data['getUserclientlist'] = $this->users_model->getUserclientlist($client_id);
            // $data['getAllUsersForCourses'] = $this->scorm_client_model->getAllUsersForCourses($data['scourse_id']);
            echo view('templates/header_view');
            echo view('users/sc_userslatestassigned_view', $data);
            echo view('templates/footer_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            return redirect()->to(base_url('dashboard'));
        }
    }
    public function assignmetacategory()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $newData = [
            'fk_scourse_id' => $_POST['scourse_id'],
            'fk_sc_mcid' => $_POST['metaCategory'],
            'typeofval' => $_POST['typeofval'],
            'status' => 1,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_course_model->assignmetacategorydata($newData);
        echo json_encode($result);
    }
    public function uploadpromovideo()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $data = [];
        helper(['filesystem']);
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        }
        if ($this->request->getPost()) {
            $rules = [
                'file' => 'uploaded[file]|max_size[file,20480]|ext_in[file,mp4]'
            ];
            if (!$this->validate($rules)) {
                $data['promovalidation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();
                        if (!is_dir(FCPATH . 'assets/assets/uploads/SCORM_course_promovideo/' . $data['scourse_id'])) {
                            mkdir('assets/assets/uploads/SCORM_course_promovideo/' . $data['scourse_id'], 0777, true);
                        }
                        if (file_exists(FCPATH . 'assets/assets/uploads/SCORM_course_promovideo/' . $data['scourse_id'] . "/" . $filename)) {
                            session()->setFlashdata('error', $filename . ' ' . lang('Messages.Success_0051'));
                            return redirect()->to(base_url() . '/XAPI_courses/course_settings_view');
                        } else {

                            if ($file->move(FCPATH . 'assets/assets/uploads/SCORM_course_promovideo/' . $data['scourse_id'], $filename)) {
                                $filepath = FCPATH . 'assets/assets/uploads/SCORM_course_promovideo/' . $data['scourse_id'] . '/' . $filename;
                                $newdata = [
                                    'promo_video' => $filename,
                                ];
                                $result = $this->scorm_course_model->editcoursedetails($newdata, $data['scourse_id']);
                                if ($result) {
                                    session()->setFlashdata('success', lang('Messages.Success_0009'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                } else {
                                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                }
                            }
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                    }
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
        }
        return redirect()->to(base_url() . 'SCORM/scorm_courses/course_settings_view');
    }
    public function uploadpdf()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $data = [];
        helper(['filesystem']);
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        }
        if ($this->request->getPost()) {
            $rules = [
                'file' => 'uploaded[file]|max_size[file,20480]|ext_in[file,pdf]'
            ];
            if (!$this->validate($rules)) {
                $data['pdfvalidation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();
                        if (!is_dir(FCPATH . 'assets/assets/uploads/SCORM_course_pdf/' . $data['scourse_id'])) {
                            mkdir('assets/assets/uploads/SCORM_course_pdf/' . $data['scourse_id'], 0777, true);
                        }
                        if (file_exists(FCPATH . 'assets/assets/uploads/SCORM_course_pdf/' . $data['scourse_id'] . "/" . $filename)) {
                            session()->setFlashdata('error', $filename . ' ' . lang('Messages.Success_0051'));
                            return redirect()->to(base_url() . 'SCORM/scorm_courses/course_settings_view');
                        } else {

                            if ($file->move(FCPATH . 'assets/assets/uploads/SCORM_course_pdf/' . $data['scourse_id'], $filename)) {
                                $filepath = FCPATH . 'assets/assets/uploads/SCORM_course_pdf/' . $data['scourse_id'] . '/' . $filename;
                                $newdata = [
                                    'pdf_filename' => $filename,
                                ];
                                $result = $this->scorm_course_model->editcoursedetails($newdata, $data['scourse_id']);
                                if ($result) {
                                    session()->setFlashdata('success', lang('Messages.Success_0009'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                } else {
                                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                }
                            }
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                    }
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
        }
        return redirect()->to(base_url() . 'SCORM/scorm_courses/course_settings_view');
    }
    public function courseusersassigned_report()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);
        $client_id = session()->get('client');
        if (isset($_POST['return_page'])) {
            $data['return_page'] = $_POST['return_page'];
        } else {
            $data['return_page'] = 'details';
        }

        $data['client_id'] = $client_id;
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } elseif (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        } else {
            session()->setFlashdata('error', 'Please select a course to view the report.');
            return redirect()->to(base_url() . 'XAPI/XAPI_courses');
        }
        $data['coursename'] = $this->scorm_client_model->getCoursename($data['scourse_id']);
        // print_r($data['coursename']);
        // exit();
        $data['scenarios'] = $this->xapi_scenarios_model->getAllScenarios($_SESSION['scourse_id']);
        $data['header_link'] = 'SCORM/scorm_courses';
        $data['header_sub_link'] = 'SCORM/scorm_courses/courseusersassigned_report';
        $data['form_link'] = 'XAPI/XAPI_courses/userallcoursedetails';
        $data['form_link1'] = 'XAPI/XAPI_courses/deleteEnrollment';
        $data['delete_enrollment'] = 'XAPI/XAPI_courses/userdelete_enrollment';
        $data['header_link_name'] = 'Courses';

        // "Courses" breadcrumb link: points back to wherever the user actually came from
        // (My Courses, Marketplace, Learning Plan, Demos, ...) - see BaseController::coursesBreadcrumbLink().
        // The Reports/client_reports "Course Report" form already posts return_page=report,
        // which takes priority here since that's a more specific "came from" signal than
        // whatever detail_type happens to be sitting in the session.
        if ($data['return_page'] === 'report') {
            $data['courses_link'] = 'Reports/client_reports';
            $data['courses_link_label'] = lang('Buttons.Report');
            $data['page_title_prefix'] = lang('UI_Text.Course_Report');
            $data['show_course_details_link'] = false;
        } else {
            $coursesBreadcrumb = $this->coursesBreadcrumbLink();
            $data['courses_link'] = $coursesBreadcrumb['link'];
            $data['courses_link_label'] = $coursesBreadcrumb['label'];
            $data['page_title_prefix'] = lang('UI_Text.Assign_Users');
            $data['show_course_details_link'] = true;
        }

        $data['getUserlatestCourse'] = $this->scorm_client_model->getUserlatestclientCourse($data['scourse_id'], $client_id);
        $data['getUserclientlist'] = $this->users_model->getUserclientlist($client_id);
        // print_r($data['getUserclientlist']);
        $data['getUserlatestclientCourseByScenario'] = $this->scorm_client_model->getUserlatestclientCourseByScenario($data['scourse_id'], $client_id);
        $data['usergroupdata'] = $this->scorm_user_group_model->getUsergroupdata(4, $data['client_id']);
        // $data['getAllUsersForCourses'] = $this->scorm_client_model->getAllUsersForCourses($data['scourse_id']);
        echo view('templates/header_view');
        echo view('users/sc_userslatestassigned_view', $data);
        echo view('templates/footer_view');
    }
    public function deleteEnrollment()
    {

        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['user_assign_id'])) {
            $data['user_assign_id'] = $_POST['user_assign_id'];
        } else {
            return redirect()->to(base_url() . 'XAPI/XAPI_courses/courseusersassigned_report');
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
            return redirect()->to(base_url() . 'XAPI/XAPI_courses/courseusersassigned_report');
        }
    }
    public function userdelete_enrollment()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        if (isset($_POST['user_assign_id'])) {
            $data['user_assign_id'] = $_POST['user_assign_id'];
            $_SESSION['user_assign_id'] = $data['user_assign_id'];
        } else if (isset($_GET['user_assign_id'])) {
            $data['user_assign_id'] = $_GET['user_assign_id'];
        } elseif (isset($_SESSION['user_assign_id'])) {
            $data['user_assign_id'] = $_SESSION['user_assign_id'];
        }
        $newdata = [


            'status' => '0',
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_client_model->deleteEnrollment($newdata, $data['user_assign_id']);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
            return redirect()->to(base_url() . 'XAPI/XAPI_courses/courseusersassigned_report');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'XAPI/XAPI_courses/courseusersassigned_report');
        }
    }

    public function course_report_view()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);
        $client_id = session()->get('client');
        $data['client_id'] = $client_id;
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } elseif (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        }
        $data['coursename'] = $this->scorm_client_model->getCoursename($data['scourse_id']);
        // print_r($data['coursename']);
        $data['scenarios'] = $this->xapi_scenarios_model->getAllScenarios($_SESSION['scourse_id']);
        $data['header_link'] = 'SCORM/scorm_courses';
        $data['header_sub_link'] = 'SCORM/scorm_courses/courseusersassigned_report';
        $data['form_link'] = 'XAPI/XAPI_courses/userallcoursedetails';
        $data['form_link1'] = 'XAPI/XAPI_scenarios/deleteEnrollment';
        $data['delete_enrollment'] = 'User_login/client_users/delete_enrollment';
        $data['header_link_name'] = 'Courses';
        $data['getUserlatestCourse'] = $this->scorm_client_model->getUserlatestclientCourse($data['scourse_id'], $client_id);
        // $data['getUserclientlist'] = $this->users_model->getUserclientlist($client_id);
        $data['getUserclientlist'] = $this->users_model->getUsersassignedcourse($client_id, $data['scourse_id']);
        $data['getUserlatestclientCourseByScenario'] = $this->scorm_client_model->getUserlatestclientCourseByScenario($data['scourse_id'], $client_id);

        // $data['getAllUsersForCourses'] = $this->scorm_client_model->getAllUsersForCourses($data['scourse_id']);
        echo view('templates/header_view');
        echo view('users/sc_userslatestassigned_report_view', $data);
        echo view('templates/footer_view');
    }


    function userallcoursedetails()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['user_assign_id'])) {
            $data['user_assign_id'] = $_POST['user_assign_id'];
            $_SESSION['user_assign_id'] = $data['user_assign_id'];
            $data['tempusername'] = $_POST['tempusername'];
            $_SESSION['tempusername'] = $data['tempusername'];
            $data['course_name'] = $_POST['course_name'];
            $_SESSION['course_name'] = $data['course_name'];
            $data['scenario_name'] = $_POST['scenario_name'];
            $_SESSION['scenario_name'] = $data['scenario_name'];
            $data['return_page'] = $_POST['return_page'] ?? 'details';
            $_SESSION['ua_return_page'] = $data['return_page'];
        } else if (isset($_SESSION['user_assign_id'])) {
            $data['user_assign_id'] = $_SESSION['user_assign_id'];
            $data['return_page'] = $_SESSION['ua_return_page'] ?? 'details';
        } else {
            return redirect()->to(base_url() . 'SCORM/scorm_courses');
        }
        $data['header_link'] = 'XAPI/XAPI_courses/courseusersassigned_report';
        $data['course_name'] = $_SESSION['course_name'];

        // When arrived from the Course Report flow, collapse the breadcrumb down to a
        // single "Course Report" item pointing back to courseusersassigned_report -
        // the "My Courses" / "Assign Users" trail only applies to the normal course flow.
        if ($data['return_page'] === 'report') {
            $data['show_course_header_link'] = false;
            $data['course_header'] = '';
            $data['course_header_link'] = '';
            $data['header'] = lang('UI_Text.Course_Report');
        } else {
            $data['show_course_header_link'] = true;
            $data['course_header'] = 'Courses';
            $data['course_header_link'] = 'SCORM/scorm_courses';
            $data['header'] = 'Assign Users';
        }

        if (strlen($_SESSION['scenario_name']) > 2) {
            $data['header2'] = 'Scenario - ' . $_SESSION['scenario_name'];
        } else {
            $data['header2'] = 'dd';
        }
        $data['header2_link'] = 'XAPI/XAPI_scenarios/view_assigned_users';
        $data['header3'] = 'Attempts';
        $data['userreport'] = $_SESSION['tempusername'];

        $data['form_link'] = 'XAPI/XAPI_courses/userallcoursedetails';
        $data['view_details'] = 'XAPI/XAPI_courses/viewDetailedReport';
        $data['form_link1'] = 'XAPI/XAPI_scenarios/deleteEnrollment';
        $data['delete_enrollment'] = 'User_login/client_users/delete_enrollment';

        $data['delete_enrollment'] = 'XAPI/XAPI_courses/delete_enrollment';

        $data['userRecords'] = $this->xapi_scenarios_model->getuserDetails($data['user_assign_id']);
        // echo "<pre>";
        // print_r($data['userRecords']);
        // exit();
        echo view('templates/header_view');
        echo view('XAPI/scenario_user_report', $data);
        echo view('templates/footer_view');
    }
    function addnewattempt()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $attempt = $_POST['attempt'] + 1;
        $scorm_user_details = [
            'user_assign_id' => $_POST['user_assign_id'],
            'course_id' => $_POST['course_id'],
            'student_id' => $_POST['student_id'],
            'attempt' => $attempt,
            'lesson_status' => 'not started',
            'status' => 1,
            'createdby' => session()->get('id_user'),
            'createdon' => time(),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];
        $result = $this->xapi_scenarios_model->addnewattempt($scorm_user_details);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0011'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
        }
        return redirect()->to(base_url() . 'XAPI/XAPI_courses/userallcoursedetails');
    }
    function searchuserallcoursedetails()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['userid'])) {
            $data['userid'] = $_POST['userid'];
            $_SESSION['userid'] = $data['userid'];
            $data['course_id'] = $_POST['course_id'];
            $_SESSION['course_id'] = $data['course_id'];
        } else if (isset($_SESSION['userid']) && $_SESSION['course_id']) {
            $data['userid'] = $_SESSION['userid'];
            $data['course_id'] = $_SESSION['course_id'];
        } else {
            return redirect()->to(base_url() . 'SCORM/scorm_courses');
        }
        $user_assign = $this->xapi_scenarios_model->getuser_assign_id($data['course_id'], $data['userid']);
        // print_r($user_assign);
        // exit();

        if (empty($user_assign)) {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url() . 'XAPI/XAPI_courses/course_report_view');
        } else {
            $userassigndata = $user_assign['0'];
            $_SESSION['course_name'] = $userassigndata['course_name'];
            $_SESSION['tempusername'] = $userassigndata['username'];
            $_SESSION['scenario_name'] = $userassigndata['scenario_name'];
            $data['course_header'] = 'My Courses';
            $data['course_header_link'] = 'SCORM/scorm_courses';
            $data['header'] = 'Assign Users - ' . $userassigndata['course_name'];
            $data['header_link'] = 'XAPI/XAPI_courses/course_report_view';
            $data['form_link1'] = 'XAPI/XAPI_scenarios/deleteEnrollment';
            $data['delete_enrollment'] = 'User_login/client_users/delete_enrollment';

            if (strlen($userassigndata['scenario_name']) > 2) {
                $data['header2'] = 'Scenario - ' . $userassigndata['scenario_name'];
            } else {
                $data['header2'] = 'dd';
            }

            $data['header2_link'] = 'XAPI/XAPI_scenarios/view_assigned_users';
            $data['header3'] = 'Attempts';
            $data['userreport'] = $_SESSION['tempusername'];

            $data['form_link'] = 'XAPI/XAPI_courses/userallcoursedetails';
            $data['view_details'] = 'XAPI/XAPI_courses/viewDetailedReport';

            $data['delete_enrollment'] = 'XAPI/XAPI_courses/delete_enrollment';


            $result = $this->xapi_scenarios_model->getuserDetails($userassigndata['user_assign_id']);
            // print_r($result );
            // exit();
            if (!empty($result)) {
                $data['userRecords'] = $result;
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0003'));
                return redirect()->to(base_url() . 'XAPI/XAPI_courses/course_report_view');
            }
        }
        // echo "<pre>";
        // print_r($data['userRecords']);
        // exit();
        echo view('templates/header_view');
        echo view('XAPI/scenario_user_report', $data);
        echo view('templates/footer_view');
    }
    function manageuserallcoursedetails()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['user_assign_id'])) {
            $data['user_assign_id'] = $_POST['user_assign_id'];
            $_SESSION['user_assign_id'] = $data['user_assign_id'];
            $data['tempusername'] = $_POST['tempusername'];
            $_SESSION['tempusername'] = $data['tempusername'];
        } else if (isset($_SESSION['user_assign_id'])) {
            $data['user_assign_id'] = $_SESSION['user_assign_id'];
        } else {
            return redirect()->to(base_url() . 'SCORM/scorm_courses');
        }


        $data['course_header'] = 'Courses';
        $data['course_header_link'] = 'XAPI/XAPI_courses';
        $data['header'] = 'Scenarios {' . $_SESSION['course_name'] . '}';
        $data['header_link'] = 'XAPI/XAPI_scenarios/XAPIMangeCourses';
        if (strlen($_SESSION['scenario_name']) > 2) {
            $data['header2'] = 'Scenarios Users {' . $_SESSION['scenario_name'] . '}';
        } else {
            $data['header2'] = 'dd';
        }

        $data['header2_link'] = 'XAPI/XAPI_scenarios/view_Manageassigned_users';
        $data['header3'] = 'Attempts';

        $data['form_link'] = 'XAPI/XAPI_courses/userallcoursedetails';
        $data['view_details'] = 'XAPI/XAPI_courses/viewManageDetailedReport';

        $data['delete_enrollment'] = 'XAPI/XAPI_courses/delete_enrollment';

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
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['sc_uid'])) {
            $data['sc_uid'] = $_POST['sc_uid'];
            $_SESSION['sc_uid'] = $data['sc_uid'];
            $data['attempt'] = $_POST['attempt'];
            $_SESSION['attempt'] = $data['attempt'];
        } else if (isset($_SESSION['user_assign_id'])) {
            $data['sc_uid'] = $_SESSION['sc_uid'];
            $data['attempt'] = $_SESSION['attempt'];
        } else {
            return redirect()->to(base_url() . 'SCORM/scorm_courses');
        }
        $data['course_header'] = 'Courses';
        $data['course_header_link'] = 'XAPI/XAPI_courses';
        $data['header'] = 'Assign Users - ' . $_SESSION['course_name'];
        $data['header_link'] = 'XAPI/XAPI_courses/courseusersassigned_report';
        $data['header2'] = 'Scenario - ' . $_SESSION['scenario_name'];
        //$data['header2_link'] = 'XAPI/XAPI_courses/userallcoursedetails';
        $data['header3'] = $_SESSION['tempusername'] . '  Attempts';
        $data['header3_link'] = 'XAPI/XAPI_courses/searchuserallcoursedetails';
        $data['header4'] = 'Attempt ' . $_SESSION['attempt'] . '  Details';

        $data['deleteAction'] = 'XAPI/XAPI_courses/delete_activity';

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
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['sc_uid'])) {
            $data['sc_uid'] = $_POST['sc_uid'];
            $_SESSION['sc_uid'] = $data['sc_uid'];
            $data['attempt'] = $_POST['attempt'];
            $_SESSION['attempt'] = $data['attempt'];
        } else if (isset($_SESSION['user_assign_id'])) {
            $data['sc_uid'] = $_SESSION['sc_uid'];
            $data['attempt'] = $_SESSION['attempt'];
        } else {
            return redirect()->to(base_url() . 'SCORM/scorm_courses');
        }
        $data['course_header'] = 'Courses';
        $data['course_header_link'] = 'XAPI/XAPI_courses';
        $data['header'] = 'Scenarios {' . $_SESSION['course_name'] . '}';
        $data['header_link'] = 'XAPI/XAPI_scenarios/XAPIMangeCourses';
        $data['header2'] = 'Scenarios Users {' . $_SESSION['scenario_name'] . '}';
        $data['header2_link'] = 'XAPI/XAPI_scenarios/view_assigned_users';
        $data['header3'] = $_SESSION['tempusername'] . '  Attempts';
        $data['header3_link'] = 'XAPI/XAPI_courses/userallcoursedetails';
        $data['header4'] = 'Attempt ' . $_SESSION['attempt'] . '  Details';

        $data['deleteAction'] = 'XAPI/XAPI_courses/delete_activity';

        $data['userActivity'] = $this->xapi_scenarios_model->activityDetails($data['sc_uid']);
        $data['OutputVariables'] = $this->xapi_scenarios_model->getOutputVariable($data['sc_uid']);
        echo view('templates/header_view');
        echo view('XAPI/scenario_user_report_details', $data);
        echo view('templates/footer_view');
    }
    function viewManageuserDetailedReport()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['sc_uid'])) {
            $data['sc_uid'] = $_POST['sc_uid'];
            $_SESSION['sc_uid'] = $data['sc_uid'];
            $data['attempt'] = $_POST['attempt'];
            $_SESSION['attempt'] = $data['attempt'];
        } else if (isset($_SESSION['user_assign_id'])) {
            $data['sc_uid'] = $_SESSION['sc_uid'];
            $data['attempt'] = $_SESSION['attempt'];
        } else {
            return redirect()->to(base_url() . 'SCORM/scorm_courses');
        }
        $data['course_header'] = 'Users';
        $data['course_header_link'] = 'User_login/client_users';
        $data['header'] = 'User Report';
        $data['header_link'] = 'User_login/client_users/course_report/' . base64_encode($_POST['userid']);
        $data['header2'] = '';
        $data['header2_link'] = '';
        $data['header3'] = 'All Attempt Course View -' . $_POST['username'];
        $data['header3_link'] = 'User_login/client_users/getscormuserdetails';
        $data['header4'] = 'Attempt ' . $_SESSION['attempt'] . '  Details';

        $data['deleteAction'] = 'XAPI/XAPI_courses/delete_activity';

        $data['userActivity'] = $this->xapi_scenarios_model->activityDetails($data['sc_uid']);
        $data['OutputVariables'] = $this->xapi_scenarios_model->getOutputVariable($data['sc_uid']);

        echo view('templates/header_view');
        echo view('XAPI/scenario_user_report_details', $data);
        echo view('templates/footer_view');
    }

    public function delete_enrollment()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $sc_uid = $_POST['sc_uid'];
        $newdata = [


            'last_updated_by' => session()->get('id_user'),
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
        return redirect()->to(base_url() . 'XAPI/XAPI_courses/userallcoursedetails');
    }
    public function users_delete_enrollment()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $sc_uid = $_POST['sc_uid'];
        $newdata = [


            'last_updated_by' => session()->get('id_user'),
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
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $xapi_act_id = $_POST['xapi_act_id'];
        $newdata = [


            'last_updated_by' => session()->get('id_user'),
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
        return redirect()->to(base_url() . 'SCORM/scorm_courses/viewDetailedReport');
    }
    public function downloadSCORMCoursePackage()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        helper('filesystem');

        $data = [];

        // Get scourse_id from POST, GET, or SESSION
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } elseif (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } elseif (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url() . 'SCORM/scorm_courses/course_edit_view');
        }

        $getAllpdfFileOwner = $this->scorm_page_model->getAllpdfFileOwner($data['scourse_id']);
        $timestamp = $getAllpdfFileOwner[0]['upload'];
        // print_r($timestamp);
        // exit();
        $course_name = $getAllpdfFileOwner[0]['course_name'];
        $zipfilenameformat = $course_name;
        if (!empty($timestamp)) {
            $basePath = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/';
            $timestampFolderPath = $basePath . $timestamp . '/';
            $zipFilePath = $basePath . $zipfilenameformat . '.zip';

            // Ensure base path exists
            if (!is_dir($basePath)) {
                mkdir($basePath, 0777, true);
            }

            // Ensure timestamp folder exists
            if (!is_dir($timestampFolderPath)) {
                session()->setFlashdata('error', lang('Messages.Error_0003'));
                return redirect()->to(base_url() . 'SCORM/scorm_courses/course_edit_view');
            }

            // Create ZIP
            $zip = new ZipArchive();
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                $files = [];

                $getFilesRecursively = function ($folder) use (&$files) {
                    $dir = new RecursiveDirectoryIterator($folder);
                    foreach (new RecursiveIteratorIterator($dir) as $file) {
                        if ($file->isFile()) {
                            $files[] = $file->getPathname();
                        }
                    }
                };
                $getFilesRecursively($timestampFolderPath);

                foreach ($files as $file) {
                    $relativePath = substr($file, strlen($timestampFolderPath));
                    $zip->addFile($file, $relativePath);
                }

                $zip->close();

                // Serve and delete
                if (file_exists($zipFilePath)) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/zip');
                    header('Content-Disposition: attachment; filename="' . basename($zipFilePath) . '"');
                    header('Content-Length: ' . filesize($zipFilePath));
                    header('Pragma: public');
                    header('Cache-Control: must-revalidate');
                    header('Expires: 0');
                    flush();
                    readfile($zipFilePath);
                    flush();

                    unlink($zipFilePath); // Delete ZIP after download
                    exit;
                } else {
                    session()->setFlashdata('error', 'Zip file not found.');
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . 'SCORM/scorm_courses/course_edit_view');
                }
            } else {
                session()->setFlashdata('error', 'Zip file not found.');
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'SCORM/scorm_courses/course_edit_view');
            }
        } else {
            session()->setFlashdata('error', 'Failed to create zip file.');
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'SCORM/scorm_courses/course_edit_view');
        }
    }
}
