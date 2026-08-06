<?php

namespace App\Controllers\SCORM;

use App\Controllers\BaseController;

use App\Models\SCORM\Scorm_dashboard_model;
use App\Models\SCORM\Scorm_lanuch_model;
use App\Models\XAPI\API_model;

#[\AllowDynamicProperties]
class Scorm_launch extends BaseController
{
    private $db;
    protected $mRequest;

    public function __construct()
    {

        $this->scorm_dashboard_model = new Scorm_dashboard_model();
        $this->scorm_lanuch_model = new Scorm_lanuch_model();
        $this->API_model = new API_model();
        // $this->mRequest = service("request"); 
    }

    // public function index()
    // {
    //     $data = [];
    //     // print_r($_POST);
    //     // exit();

    //     $data['course_id']  = isset($_POST['course_id']) ? $_POST['course_id'] : session()->get('course_id');
    //     $data['foldername'] = isset($_POST['foldername']) ? $_POST['foldername'] : session()->get('foldername');
    //     $data['type'] = isset($_POST['type']) ? $_POST['type'] : session()->get('type');
    //     $data['group_id'] = isset($_POST['group_id']) ? $_POST['group_id'] : '0';
    //     $data['student_id'] = session()->get('id_user');
    //     $data['student_name'] = session()->get('name');
    //     $data['SCORMpath'] = base_url('assets/assets/uploads/SCORM_course_document/' . $data['course_id'] . '/' . $data['foldername']);
    //     $data['addscormuserdetails'] = $this->scorm_lanuch_model->addscormuserdetails($data['course_id'], $data['group_id']);
    //     $data['CMIdata'] = $this->scorm_lanuch_model->getscormuserdetails($data['student_id'], $data['course_id']);
    //     // print_r($data);
    //     // exit();
    //     echo view('SCORM/scorm_launch/scorm_lanuch_view', $data);
    // }
    // public function index()
    // {
    //     $session = session();

    //     // Read POST or Session values safely
    //     $course_id = $this->request->getPost('course_id') ?? $session->get('course_id');
    //     $foldername = $this->request->getPost('foldername') ?? $session->get('foldername');
    //     $type = $this->request->getPost('type') ?? $session->get('type');
    //     $group_id = $this->request->getPost('group_id') ?? '0';
    //     $student_id = $session->get('id_user');
    //     $student_name = $session->get('name');

    //     // Prepare data for view
    //     $data = [
    //         'course_id' => $course_id,
    //         'foldername' => $foldername,
    //         'type' => $type,
    //         'group_id' => $group_id,
    //         'student_id' => $student_id,
    //         'student_name' => $student_name,
    //         'SCORMpath' => base_url("assets/assets/uploads/SCORM_course_document/{$course_id}/{$foldername}"),
    //         'addscormuserdetails' => $this->scorm_lanuch_model->addscormuserdetails($course_id, $group_id),
    //         'CMIdata' => $this->scorm_lanuch_model->getscormuserdetails($student_id, $course_id),
    //     ];

    //     // Render the view
    //     return view('SCORM/scorm_launch/scorm_lanuch_view', $data);
    // }
    public function index()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '3'])) {
            return $response;
        }
        helper(['url', 'form']); // If not already loaded

        // Get request values (POST > Session fallback)
        $course_id = $this->request->getPost('course_id') ?? session()->get('course_id');
        $foldername = $this->request->getPost('foldername') ?? session()->get('foldername');
        $type = $this->request->getPost('type') ?? session()->get('type');
        $group_id = $this->request->getPost('group_id') ?? '0';

        $student_id = session()->get('id_user');
        $student_name = session()->get('name');

        // Basic validation
        if (empty($course_id) || empty($foldername)) {
            return redirect()->back()->with('error', 'Course information missing.');
        }

        // Prepare data
        $data = [
            'course_id' => $course_id,
            'foldername' => $foldername,
            'type' => $type,
            'group_id' => $group_id,
            'student_id' => $student_id,
            'student_name' => $student_name,
            'SCORMpath' => base_url('assets/assets/uploads/SCORM_course_document/' . $course_id . '/' . $foldername)
        ];

        // Call model functions (model handles concurrency safety now)
        $data['addscormuserdetails'] = $this->scorm_lanuch_model->addscormuserdetails($course_id, $group_id);
        $data['CMIdata'] = $this->scorm_lanuch_model->getscormuserdetails($student_id, $course_id);

        return view('SCORM/scorm_launch/scorm_lanuch_view', $data);
    }
    public function review($page_id)
    {
        if ($response =  $this->requireRole(['6', '44', '5', '3'])) {
            return $response;
        }
        $data = [];
        $data['course_id'] = isset($_POST['course_id']) ? $_POST['course_id'] : session()->get('course_id');
        $data['foldername'] = isset($_POST['foldername']) ? $_POST['foldername'] : session()->get('foldername');
        $data['type'] = isset($_POST['type']) ? $_POST['type'] : session()->get('type');

        $data['page_id'] = $page_id;
        $data['typeOfLaunch'] = 1;
        $_SESSION['tab'] = 1;

        echo view('SCORM/course_builder/page_scorm_review', $data);
    }

    public function tinCanlanch()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '3'])) {
            return $response;
        }
        $data = [];
        $data['course_id'] = session()->get('course_id');
        $data['foldername'] = session()->get('foldername');
        $data['type'] = $this->request->getGet('type');
        $data['student_id'] = session()->get('id_user');
        $data['student_name'] = session()->get('name');

        $data['cid'] = $data['course_id'];


        $data['result'] = '';
        $user_assigned_id = $this->API_model->checkUserAssignment($data['student_id'], $data['course_id']);
        //   echo  $user_assigned_id[0]['user_assign_id'];
        if ($user_assigned_id) {
            $user_assigned_id_val = $user_assigned_id[0]['user_assign_id'];
            $scenario_id = $user_assigned_id[0]['scenario_id'];
            $current_attempt = 0;
            $attempt = $this->API_model->checkattempts($user_assigned_id_val);
            if ($attempt) {
                $current_attempt = $attempt[0]['attempt'];
            }
            $current_attempt++;
            $newdata = [
                'user_assign_id' => $user_assigned_id_val,
                'lesson_status' => 'incomplete',
                'attempt' => $current_attempt,
                'createdon' => time(),
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
                'status' => '1',
            ];
            $updatecoursestatus = [
                'course_status' => 1,
                'last_updated_on' => time()
            ];
            $record_id = $this->API_model->createNewActivity($newdata);
            $record_id = $this->API_model->updatecoursestatus($user_assigned_id_val, $updatecoursestatus);
            if ($record_id) {
                $newdata2 = [
                    'sc_uid' => $record_id,
                    'variable' => 'login',
                    'createdon' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                    'status' => '1',
                ];
                $this->API_model->enterUserData($newdata2);
                $data['result'] = 'Valid||recordID|' . $record_id;
                $data['result'] = $data['result'] . '||User Name|' . $data['student_name'];
                $scenario_settings = $this->API_model->getScenarioSettings($scenario_id);
                if ($scenario_settings) {
                    foreach ($scenario_settings as $sce_set) {
                        $data['result'] = $data['result'] . '||' . $sce_set['variable_name'] . '|' . $sce_set['value'];
                    }
                }
            } else {
                $data['result'] = 'Invaid Data Error!0445';
            }
        } else {

            $data['result'] = 'Course not assigned to User.';
        }


        $data['SCORMpath'] = base_url('assets/assets/uploads/SCORM_course_document/' . $data['course_id'] . '/' . $data['foldername']);
        // $data['addscormuserdetails'] = $this->scorm_lanuch_model->addscormuserdetails($data['course_id']);
        //   $data['CMIdata'] = $this->scorm_lanuch_model->getscormuserdetails($data['student_id'], $data['course_id']);

        $data['result'] = str_replace(' ', '---', $data['result']);
        // print_r($data['result']);
        echo view('SCORM/scorm_launch/tincan_lanch_view', $data);
    }
    public function LMSSetValue()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '3'])) {
            return $response;
        }
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if (!is_array($data)) {
            $data = $_POST; // fallback
        }

        $requiredFields = ['student_id', 'student_name', 'course_id', 'column_name', 'value'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return $this->response->setJSON([
                    'status' => 'false',
                    'message' => "Missing field: $field"
                ]);
            }
        }

        $student_id = $data['student_id'];
        $student_name = $data['student_name'];
        $course_id = $data['course_id'];
        $column_name = $data['column_name'];
        $value = $data['value'];
        // echo "<script>alert('Column Name: " . addslashes($column_name) . "');</script>";


        $result = $this->scorm_lanuch_model->updatescormuserdetails(
            $student_id,
            $student_name,
            $course_id,
            $column_name,
            $value
        );


        return $this->response->setJSON($result);
    }
}
