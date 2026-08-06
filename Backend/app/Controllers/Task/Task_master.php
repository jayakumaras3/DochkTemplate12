<?php

namespace App\Controllers\Task;

use App\Controllers\BaseController;
use App\Models\Task\Task_model;
use App\Models\User_login\Users_model;
use App\Models\User_login\Login_model;
use App\Models\SCORM\Scorm_course_model;

#[\AllowDynamicProperties]
class Task_master extends BaseController
{
    protected $task_model;
    protected $users_model;
    protected $login_model;

    public function __construct()
    {
        $this->task_model = new Task_model();
        $this->users_model = new Users_model();
        $this->login_model = new Login_model();
        $this->scorm_course_model = new Scorm_course_model();
    }
    
    public function index()
    {
        $data = [];
        if (isset($_POST['projectid'])) {
            $data['projectid'] = $_POST['projectid'];
            $_SESSION['projectid'] = $data['projectid'];
        } elseif (isset($_SESSION['projectid'])) {
            $data['projectid'] = $_SESSION['projectid'];
        }
        if (isset($_POST['dt_id'])) {
            $data['dt_id'] = $_POST['dt_id'];
            $_SESSION['dt_id'] = $data['dt_id'];
            $data['item_description'] = $_POST['item_description'];
            $_SESSION['item_description'] = $data['item_description'];
        } elseif (isset($_SESSION['dt_id'])) {
            $data['dt_id'] = $_SESSION['dt_id'];
            $data['item_description'] = $_SESSION['item_description'];
        }
        $data['prev_page'] = $this->task_model->getPreviousRecord($data['projectid'], $data['dt_id']);
        $data['next_page'] =  $this->task_model->getNextRecord($data['projectid'], $data['dt_id']);
        $data['usertable'] = $this->login_model->getManagers();
        $data['getCourses'] = $this->scorm_course_model->get_courses_assigned_to_project($data['projectid']);
        $data['task_masters'] = $this->task_model->get_taskmasterDetails($data['projectid'], $data['dt_id']);
        $data['allTaskofCourse'] = $this->task_model->getAllTaskfromProjectplan($data['dt_id']);
        echo view('templates/header_view', $data);
        echo view('tasks/task_master_view', $data);
        echo view('templates/footer_view', $data);
    }

    public function task_master_pm()
    {
        $data = [];
        if (isset($_POST['project_id'])) {
            $data['projectid'] = $_POST['project_id'];
            $_SESSION['projectid'] = $data['projectid'];
        } elseif (isset($_SESSION['projectid'])) {
            $data['projectid'] = $_SESSION['projectid'];
        }
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } elseif (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        }
        if (isset($_POST['course_name'])) {
            $data['course_name'] = $_POST['course_name'];
            $_SESSION['course_name'] = $data['course_name'];
        } elseif (isset($_SESSION['course_name'])) {
            $data['course_name'] = $_SESSION['course_name'];
        }
        // print_r($data['course_name']);
        $data['self_user_name'] = session()->get('name');
        $data['self_id_user'] = session()->get('id_user');
        $data['managerlist'] = $this->login_model->getManagers();
        $data['course_tasks'] = $this->task_model->get_course_task_master_list($data['scourse_id']);
        $data['allTaskofCourse'] = $this->task_model->getAllTask($data['scourse_id']);
        // print_r($data['allTaskofCourse']);
        // exit();
        echo view('templates/header_view', $data);
        echo view('tasks/task_master_pm_view', $data);
        echo view('templates/footer_view', $data);
    }

    public function view_taskMaster_details()
    {
        $data = [];
        if (isset($_POST['mt_id'])) {
            $data['master_id'] = $_POST['mt_id'];
            $_SESSION['master_id'] = $data['master_id'];
        } elseif (isset($_SESSION['master_id'])) {
            $data['master_id'] = $_SESSION['master_id'];
        }
        $master_id = $data['master_id'];
        $data['masterData'] = $this->task_model->getSingleMasterData($master_id);
        $data['taskByMasterId'] = $this->task_model->gettaskByMasterId($master_id);
        echo view('templates/header_view', $data);
        echo view('tasks/task_master_task_view', $data);
        echo view('templates/footer_view', $data);
    }

    public function add_new_basic_task()
    {
        if (isset($_POST['type_of_task'])) {
            $effort = $_POST['effort'] . '' . $_POST['effort_min'];
            // print_r($effort);
            // exit();
            $newdata = [
                'master_task_id' =>  $_POST['mt_id'],
                'type' =>  1,
                'unit' => 1,
                'course_id' =>  $_POST['course_id'],
                'id2' =>  $_POST['project_id'],
                'description' =>  $_POST['description'],
                'effort' =>  $effort,
                'priority' =>  $_POST['priority'],
                'due_date' =>  $_POST['end_date'],
                'assigned_to' => $_POST['assigned_to'],
                'status' => '1',
                'created_by' => session()->get('id_user'),
                'created_on' => time(),
                'last_updated_by' =>  session()->get('id_user'),
                'last_updated_on' => time(),
            ];

            $this->task_model->addNewTask($newdata);
            session()->setFlashdata('success', lang('Messages.Success_0018'));
        }
        return redirect()->to(base_url('Task/Task_manage/assign_master'));
    }


    public function add_new_task()
    {
        if (isset($_POST['type_of_task'])) {
            $data['project_id'] = $_POST['project_id'];
            $data['dt_id'] = $_POST['dt_id'];
            $effort = $_POST['effort'] . '' . $_POST['effort_min'];
            $newdata = [
                'description' =>  $_POST['description'],
                'dt_id' =>  $_POST['dt_id'],
                'project_id' => $_POST['project_id'],
                'course_id' =>  $_POST['course_id'],
                'assigned_to' =>  $_POST['assigned_to'],
                'start_date' =>  $_POST['start_date'],
                'end_date' =>  $_POST['end_date'],
                'effort' =>  $effort,
                'priority' => isset($_POST['priority'])?$_POST['priority']:'',
                'status' => '1',
                'createdby' => session()->get('id_user'),
                'createdon' => time(),
                'last_updated_by' =>  session()->get('id_user'),
                'last_updated_on' => time(),
            ];

            $this->task_model->addMasterTask($newdata);
            session()->setFlashdata('success', lang('Messages.Success_0018'));
            $type_of_task = $_POST['type_of_task'];
            if ($type_of_task == 1) {
                return redirect()->to(base_url() . 'SCORM/Course_builder/review_course/showfeedbackReplies');
            }
            if ($type_of_task == 2) {
                return redirect()->to(base_url() . 'Task/Task_manage');
            }
            if ($type_of_task == 3) {
                return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
            }
            if ($type_of_task == 4) {
                return redirect()->to(base_url() . 'Task/Task_master');
            }
            if ($type_of_task == 5) {
                return redirect()->to(base_url() . 'Task/Task_master/task_master_pm');
            }
        } else {
            return redirect()->to(base_url('dashboard'));
        }
    }
    function deleteMasterTask()
    {
        $data['mt_id'] = $_POST['mt_id'];
        $newdata = [
            'status' => 0,
            
             
        ];
        $this->task_model->deleteMasterTask($newdata, $data['mt_id']);
        session()->setFlashdata('success', lang('Messages.Success_0005'));
        if ($_POST['return_url'] == '1') {
            return redirect()->to(base_url() . 'Task/Task_master/task_master_pm');
        } elseif ($_POST['return_url'] == '2') {
            return redirect()->to(base_url() . 'Task/Task_master');
        }
    }
}
