<?php

namespace App\Controllers\Task;

use App\Controllers\BaseController;
use App\Models\Task\Task_model;
use App\Models\User_login\Users_model;
use App\Models\User_login\Login_model;
use App\Models\Project_Manage\PM_ucn_model;
use App\Models\Project_Manage\PM_pricing_sheet_model;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use App\Models\Project_Manage\PM_projects_model;
use App\Models\Etrack\HR_model;

#[\AllowDynamicProperties]
class Task_manage extends BaseController
{
    protected $task_model;
    protected $users_model;
    protected $login_model;

    public function __construct()
    {
        $this->is_session_available();
        $this->task_model = new Task_model();
        $this->PM_pricing_sheet_model = new PM_pricing_sheet_model();
        $this->PM_projects_model = new PM_projects_model();
        $this->users_model = new Users_model();
        $this->login_model = new Login_model();
        $this->PM_ucn_model = new PM_ucn_model();
        $this->HR_model = new HR_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url());
            exit();
        }

        $arrayuserlevel = explode(',', $userlevel);

        if (in_array('4', $arrayuserlevel) && !in_array('7', $arrayuserlevel) && session()->get('report_to_you') != 2) {

            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }
    public function index()
    {
        $data = [];
        $menu = [];
        $user = session()->get('id_user');
        $data['to_do'] = $this->task_model->getTaskByUser($user, 1);
        $data['in_progress'] = $this->task_model->getTaskByUser($user, 2);
        $menu = [
            'ActiveMenu' => 'Task Dashboard'
        ];

        echo view('templates/header_view', $data);
        // echo view('tasks/menu', $menu);
        echo view('tasks/task_dashboard_view', $data);
        echo view('templates/footer_view', $data);
    }

    public function completed_task()
    {
        $data = [];
        $user = session()->get('id_user');
        $data['completed_task'] = $this->task_model->getTaskByUser($user, 3);
        $menu = [
            'ActiveMenu' => 'Completed Task'
        ];

        echo view('templates/header_view', $data);
        // echo view('tasks/menu', $menu);
        echo view('tasks/task_completed_view', $data);
        echo view('templates/footer_view', $data);
    }

    public function getPreviousWorkingDay($date = null)
    {
        $dayOfWeek = date('N', strtotime($date)); // 1 (Monday) to 7 (Sunday)
        if ($dayOfWeek == 1) { // If it's Monday, go back 3 days to Friday
            $newdate = date('Y-m-d', strtotime('-3 days', strtotime($date)));
        } elseif ($dayOfWeek == 7) { // If it's Sunday, go back 2 days to Friday
            $newdate = date('Y-m-d', strtotime('-2 days', strtotime($date)));
        } else {
            $newdate = date('Y-m-d', strtotime('-1 days', strtotime($date)));
        }
        return $newdate;
    }

    public function my_task()
    {
        $data = [];
        $user = session()->get('id_user');
        $data['active_tasks'] = $this->task_model->get_active_task($user);
        date_default_timezone_set("Asia/Kolkata");
        $data['todaydt'] = date('Y-m-d');
        $data['previous_dt'] = $this->getPreviousWorkingDay(date('Y-m-d'));
        $data['previous_dt_min'] = $this->getPreviousWorkingDay($data['previous_dt']);

        $data['getdata_1'] = $this->task_model->getdatabydt($user, $data['todaydt']);
        $data['getdata_2'] = $this->task_model->getdatabydt($user, $data['previous_dt']);
        $data['getdata_3'] = $this->task_model->getdatabydt($user, $data['previous_dt_min']);
        $data['department_list'] = $this->PM_pricing_sheet_model->get_department(1);
        echo view('templates/header_view', $data);
        echo view('tasks/team_taskallocated_view', $data);
        echo view('templates/footer_view', $data);
    }

    public function search_effort_by_date()
    {
        $data = [];
        $user = session()->get('id_user');
        $data['previous_dt'] = $_POST['start_date'];
        $data['getdata_2'] = $this->task_model->getdatabydt($user, $data['previous_dt']);

        echo view('templates/header_view', $data);
        echo view('tasks/team_taskallocated_bydate_view', $data);
        echo view('templates/footer_view', $data);
    }
    public function team_tasks_allocate()
    {
        $data = [];
        if (session()->get('report_to_you') != 2) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            return redirect()->to(base_url('my_training'));
        } else {
            $user = session()->get('id_user');
            $data['self_user'] = $user;
            $data['self_user_name'] = session()->get('name');
            $data['department_list'] = $this->PM_pricing_sheet_model->get_department(1);
            $data['active_tasks'] = $this->task_model->get_active_master_task($user);
            $clientid = session()->get('client');
            $data['usertable'] = $this->login_model->users_view($clientid);
            echo view('templates/header_view', $data);
            echo view('tasks/team_task_allocation_view', $data);
            echo view('templates/footer_view', $data);
        }
    }

    public function view_task_details()
    {
        $data = [];

        if (isset($_POST['ucn_mst_id'])) {
            $data['ucn_mst_id'] = $_POST['ucn_mst_id'];
            $_SESSION['ucn_mst_id'] = $_POST['ucn_mst_id'];
        } elseif (isset($_SESSION['ucn_mst_id'])) {

            $data['ucn_mst_id'] = $_SESSION['ucn_mst_id'];
        }
        $data['allocated_tasks'] = $this->task_model->get_employee_assigned_tasks($data['ucn_mst_id']);
        echo view('templates/header_view', $data);
        echo view('tasks/team_task_allocation_detail_view', $data);
        echo view('templates/footer_view', $data);
    }

    public function resource_planning()
    {
        $data = [];
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url());
            exit();
        }
        $arrayuserlevel = explode(',', $userlevel);
        if (!in_array('4', $arrayuserlevel) && session()->get('report_to_you') != 2) {

            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        } else {


            helper(['form']);
            //  $user = session()->get('id_user');
            if (isset($_POST['week'])) {
                $data['week'] = $_POST['week'];
                $data['skill_val'] = $_POST['skill_val'];
                $_SESSION['week'] = $data['week'];
                $_SESSION['skill_val'] = $data['skill_val'];
            } elseif (isset($_SESSION['week'])) {
                $data['week'] = $_SESSION['week'];
                $data['skill_val'] = $_SESSION['skill_val'];
            } else {
                $data['week'] = date('Y-m-d', strtotime('monday this week'));
                $data['skill_val'] = 52;
            }
            $data['employee_skill'] = $this->PM_pricing_sheet_model->get_employee_skill($data['skill_val']);
            $data['department_list'] = $this->PM_pricing_sheet_model->get_department(1);
            $data['project_list'] = $this->PM_projects_model->get_projects_resource_planning($data['week'], $data['skill_val']);
            $data['awail_all'] = $this->PM_projects_model->get_projects_resource_avail($data['week'], $data['skill_val']);
            echo view('templates/header_view', $data);
            echo view('project_management/resource_allocation/res_planning', $data);
            echo view('templates/footer_view');
        }
    }
    public function add_resource_available()
    {
        $data = [];
        if (isset($_POST['week'])) {
            $week = $_POST['week'];
            $skill_val = $_POST['skill_val'];
            $check_data = $this->PM_projects_model->get_projects_resource_avail($week, $skill_val);
            if (count(value: $check_data) > 0) {
                $res_avl_id = isset($check_data[0]['res_avl_id'])?$check_data[0]['res_avl_id']:0;
                $newdata = [
                    'skill' => $skill_val,
                    'week_day' => $week,
                    'mon' => $_POST['mon'],
                    'tue' => $_POST['tue'],
                    'wed' => $_POST['wed'],
                    'thu' => $_POST['thu'],
                    'fri' => $_POST['fri'],
                    'last_updated_on' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'status' => 1
                ];
                $this->PM_projects_model->update_resource_awail($newdata, $res_avl_id);
            } else {
                $newdata = [
                    'skill' => $skill_val,
                    'week_day' => $week,
                    'mon' => $_POST['mon'],
                    'tue' => $_POST['tue'],
                    'wed' => $_POST['wed'],
                    'thu' => $_POST['thu'],
                    'fri' => $_POST['fri'],
                    'last_updated_on' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'status' => 1
                ];
                $this->PM_projects_model->add_resource_awail($newdata);
            }

            session()->setFlashdata('success', lang('Messages.Success_0008'));
            return redirect()->to(base_url() . 'task/task_manage/resource_planning');
        }
        session()->setFlashdata('error', lang('Messages.Error_0003'));
        return redirect()->to(base_url() . 'task/task_manage/resource_planning');
    }
    public function employee_brkdown_effort()
    {
        $data = [];

        $data['ucn_tl_id'] = $_POST['ucn_tl_id'];
        $data['returnid'] = $_POST['returnid'];

        $data['allocated_breakdown'] = $this->task_model->get_employee_breakdown_tasks($data['ucn_tl_id']);

        echo view('templates/header_view', $data);
        echo view('tasks/team_task_allocation_breakdown_view', $data);
        echo view('templates/footer_view', $data);
    }
    public function delete_effort()
    {
        $ucn_emp_id = $_POST['ucn_emp_id'];
        $return_url = isset($_POST['return_url']) ? $_POST['return_url'] : '';
        $newData = [
            'status' => 0,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $this->task_model->delete_effort_by_emp($newData, $ucn_emp_id);
        session()->setFlashdata('success', lang('Messages.Success_0005'));
        if ($return_url == 1) {
            return redirect()->to(base_url() . 'Task/Task_manage/team_active_tasks_new');
        } else {
            return redirect()->to(base_url() . 'Task/Task_manage/my_task');
        }
    }
    public function close_my_assigned_task()
    {
        $ucn_tl_id = $_POST['ucn_tl_id'];
        $newData = [
            'status' => 2,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $this->task_model->close_tl_task($newData, $ucn_tl_id);
        session()->setFlashdata('success', lang('Messages.Success_0040'));
        return redirect()->to(base_url() . 'Task/Task_manage/my_task');
    }
    public function close_lt_assigned_task()
    {
        $ucn_tl_id = $_POST['ucn_tl_id'];

        $newData = [
            'status' => $_POST['status'],
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $this->task_model->close_tl_task($newData, $ucn_tl_id);
        if ($_POST['status'] == 0) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
        } else {
            session()->setFlashdata('success', lang('Messages.Success_0040'));
        }
        return redirect()->to(base_url() . 'Task/Task_manage/view_task_details');
    }
    public function close_mng_task()
    {
        $ucn_mst_id = $_POST['ucn_mst_id'];
        $newData = [
            'status' => 2,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $this->PM_ucn_model->close_master($newData, $ucn_mst_id);
        $this->PM_ucn_model->close_tl_effort($newData, $ucn_mst_id);
        session()->setFlashdata('success', lang('Messages.Success_0040'));
        return redirect()->to(base_url() . 'Task/Task_manage/team_tasks_allocate');
    }
    public function employee_add_effort()
    {
        $return_url = isset($_POST['return_url']) ? $_POST['return_url'] : '';
        if ($_POST['hrs_val'] == 0 && $_POST['min_val'] == 0) {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            if ($return_url == 1) {
                return redirect()->to(base_url() . '/Task/Task_manage/team_active_tasks_new');
            } else {
                return redirect()->to(base_url() . 'Task/Task_manage/my_task');
            }
        }
        $effort = $_POST['hrs_val'] . $_POST['min_val'];

        $id_user = isset($_POST['user_id']) ? $_POST['user_id'] : session()->get('id_user');

        $newData = [
            'ucn_tl_id' => $_POST['ucn_tl_id'],
            'ucn_mst_id' => $_POST['ucn_mst_id'],
            'ucn_id' => $_POST['ucn_id'],
            'project_id' => $_POST['project_id'],
            'skill_id' => $_POST['skill_Id'],
            'effort' => $effort,
            'stage' => $_POST['stage'],
            'employee' => $id_user,
            'remarks' => $_POST['remarks'],
            'date_value' => $_POST['start_date'],
            'status' => 1,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $this->task_model->add_effort_by_employee($newData);
        session()->setFlashdata('success', lang('Messages.Success_0011'));
        if ($return_url == 1) {
            return redirect()->to(base_url() . '/Task/Task_manage/team_active_tasks_new');
        } else {
            return redirect()->to(base_url() . 'Task/Task_manage/my_task');
        }
    }

    public function allocate_effort_to_employee()
    {
        $newData = [
            'ucn_mst_id' => $_POST['ucn_mst_id'],
            'ucn_id' => $_POST['ucn_id'],
            'project_id' => $_POST['project_id'],
            'skill_id' => $_POST['skill_Id'],
            'effort' => $_POST['effort'],
            'stage' => $_POST['stage'],
            'user' => $_POST['user'],
            'status' => 1,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $this->task_model->add_effort_employee($newData);
        session()->setFlashdata('success', lang('Messages.Success_0018'));
        return redirect()->to(base_url() . 'Task/Task_manage/team_tasks_allocate');
    }
    public function allocate_effort_bulk()
    {
        // $data = $this->request->getPost();
        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
        // exit;
        $users = $this->request->getPost('user');
        $efforts = $this->request->getPost('effort');
        $stages = $this->request->getPost('stage');
        $project_ids = $this->request->getPost('project_id');
        $ucn_ids = $this->request->getPost('ucn_id');

        $skill_ids = $this->request->getPost('skill_id');
        $ucn_mst_ids = $this->request->getPost('ucn_mst_ids');
        if (!empty($users)) {
            foreach ($users as $i => $user_id) {
                if (
                    isset($efforts[$i], $ucn_ids[$i], $project_ids[$i], $skill_ids[$i], $ucn_mst_ids[$i], $stages[$i]) &&
                    !empty($user_id) && !empty($efforts[$i])
                ) {
                    $newData = [
                        'ucn_mst_id' => $ucn_mst_ids[$i],
                        'ucn_id' => $ucn_ids[$i],
                        'project_id' => $project_ids[$i],
                        'skill_id' => $skill_ids[$i],
                        'effort' => $efforts[$i],
                        'stage' => $stages[$i],
                        'user' => $user_id,
                        'status' => 1,
                        'last_updated_by' => session()->get('id_user'),
                        'last_updated_on' => time(),
                    ];

                    $this->task_model->add_effort_employee($newData);
                }
            }
            session()->setFlashdata('success', lang('Messages.Success_0018'));
            return redirect()->to(base_url() . 'Task/Task_manage/team_tasks_allocate');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            return redirect()->to(base_url() . 'Task/Task_manage/team_tasks_allocate');
        }
    }
    public function team_active_tasks()
    {
        $data = [];
        if (isset($_POST['userid'])) {
            $user = $_POST['userid'];
            $data['name'] = $this->task_model->get_name($user);
            $data['is_manager'] = $data['name'][0]['report_to_you'];
            if ($data['is_manager'] == 2) {
                $data['assignedMasterTask'] = $this->task_model->getAssignedMasterTask($user);
            }
            $data['to_do'] = $this->task_model->getTaskByUser($user, 1);
            $data['in_progress'] = $this->task_model->getTaskByUser($user, 2);
        }
        $data['emplname'] = $data['name'][0]['fname'];

        echo view('templates/header_view', $data);
        echo view('tasks/team_active_task_view', $data);
        echo view('templates/footer_view', $data);
    }


    public function team_completed_tasks()
    {
        $data = [];
        if (isset($_POST['userid'])) {
            $user = $_POST['userid'];
            $data['name'] = $this->task_model->get_name($user);
            $data['completed'] = $this->task_model->getTaskByUser($user, 3);
        }
        $data['emplname'] = isset($data['name'][0]['name']) ? $data['name'][0]['name'] : '';

        echo view('templates/header_view', $data);
        echo view('tasks/team_completed_task_view', $data);
        echo view('templates/footer_view', $data);
    }


    public function team_tasks()
    {
        $data = [];
        $user = session()->get('id_user');
        if (session()->get('report_to_you') != 2) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            return redirect()->to(base_url('my_training'));
        } else {
            $data['team_task'] = $this->task_model->getTeam($user);
            echo view('templates/header_view', $data);
            echo view('tasks/team_task_view', $data);
            echo view('templates/footer_view', $data);
        }
    }

    public function team_active_tasks_new()
    {
        $data = [];
        if (isset($_POST['temp_user'])) {
            $data['temp_user'] = $_POST['temp_user'];
            $_SESSION['temp_user'] = $data['temp_user'];
        } elseif (isset($_SESSION['temp_user'])) {
            $data['temp_user'] = $_SESSION['temp_user'];
        }
        $user = $data['temp_user'];
        if (isset($_POST['start_date'])) {
            $data['start_date'] = $_POST['start_date'];
            $_SESSION['start_date'] = $data['start_date'];
        } elseif (isset($_SESSION['start_date'])) {
            $data['start_date'] = $_SESSION['start_date'];
        }
        if (isset($_POST['end_date'])) {
            $data['end_date'] = $_POST['end_date'];
            $_SESSION['end_date'] = $data['end_date'];
        } elseif (isset($_SESSION['end_date'])) {
            $data['end_date'] = $_SESSION['end_date'];
        } else {
            $data['start_date'] = date('Y-m-d');
            $data['end_date'] = date('Y-m-d');
        }
        $data['department_list'] = $this->PM_pricing_sheet_model->get_department(1);
        $data['active_tasks'] = $this->task_model->get_active_task($user);
        $data['user_effort'] = $this->task_model->get_usereffort($user, $data['start_date'], $data['end_date']);
        echo view('templates/header_view', $data);
        echo view('tasks/team_employee_breakdown_view', $data);
        echo view('templates/footer_view', $data);
    }

    public function project_tasks()
    {
        $data = [];
        $user = session()->get('id_user');
        $data['assignedMasterTask'] = $this->task_model->getAssignedMasterTask($user);
        $menu = [
            'ActiveMenu' => 'Project Tasks'
        ];

        echo view('templates/header_view', $data);
        // echo view('tasks/menu', $menu);
        echo view('tasks/project_task_view', $data);
        echo view('templates/footer_view', $data);
    }
    public function assign_master()
    {
        $data = [];
        if (isset($_POST['master_id'])) {
            $data['master_id'] = $_POST['master_id'];
            $_SESSION['master_id'] = $data['master_id'];
        } elseif (isset($_SESSION['master_id'])) {
            $data['master_id'] = $_SESSION['master_id'];
        }
        $master_id = $data['master_id'];
        $user = session()->get('id_user');
        $data['self_user'] = $user;
        $data['self_user_name'] = session()->get('name');
        $data['masterData'] = $this->task_model->getSingleMasterData($master_id);
        $data['taskByMasterId'] = $this->task_model->gettaskByMasterId($master_id);
        // $data['team'] = $this->task_model->getTeam($user);
        $data['team'] = $this->task_model->getAllTQusers();
        // $data['managerlist'] = $this->login_model->getManagers();
        echo view('templates/header_view', $data);
        echo view('tasks/assign_task_to_user', $data);
        echo view('templates/footer_view', $data);
    }
    public function assign_master_tl()
    {
        $data = [];
        if (isset($_POST['master_id'])) {
            $data['master_id'] = $_POST['master_id'];
            $_SESSION['master_id'] = $data['master_id'];
        } elseif (isset($_SESSION['master_id'])) {
            $data['master_id'] = $_SESSION['master_id'];
        }
        $master_id = $data['master_id'];
        $user = session()->get('id_user');
        $data['self_user'] = $user;
        $data['self_user_name'] = session()->get('name');
        $data['masterData'] = $this->task_model->getSingleMasterData($master_id);
        $data['taskByMasterId'] = $this->task_model->gettaskByMasterId($master_id);
        $data['team'] = $this->task_model->getTeam($user);
        // $data['managerlist'] = $this->login_model->getManagers();
        echo view('templates/header_view', $data);
        echo view('tasks/assign_task_to_user_tl_view', $data);
        echo view('templates/footer_view', $data);
    }
    public function my_task_report()
    {
        $data = [];
        $user = session()->get('id_user');
        // $data['team_task'] = $this->task_model->getTeamTask($user);
        $menu = [
            'ActiveMenu' => 'My Task Report'
        ];

        echo view('templates/header_view', $data);
        // echo view('tasks/menu', $menu);
        echo view('tasks/my_task_report', $data);
        echo view('templates/footer_view', $data);
    }

    public function add_new_task() //fetch data from users table to display
    {
        if (isset($_POST['type_of_task'])) {
            $selectedValue = $this->request->getPost('master_task_id'); // Get the selected value

            if ($selectedValue) {
                $value = explode('|', $selectedValue);
                $master_task_id = $value[0];
                $course_id = $value[1];
                //   print_r($course_id);
                //     exit();
            }
            $effort = $_POST['effort'] . '.' . $_POST['effort_min'];
            $newdata = [
                'type' => $_POST['type_of_task'],
                'master_task_id' => $master_task_id,
                'course_id' => $course_id,
                'id2' => $_POST['feedbackid'],
                'description' => $_POST['description'],
                'unit' => $_POST['unit'],
                'assigned_to' => $_POST['assigned_to'],
                'effort' => $effort,
                'due_date' => $_POST['due_date'],
                'priority' => isset($_POST['priority']) ? $_POST['priority'] : '',
                'status' => '1',
                'created_by' => session()->get('id_user'),
                'created_on' => time(),
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $this->task_model->addNewTask($newdata);
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
        } else {
            return redirect()->to(base_url('my_training'));
        }
    }

    public function del_task()
    {
        if (isset($_POST['task_id'])) {
            $task_id = $_POST['task_id'];
            $type_of_task = $_POST['type_of_task'];
            $newdata = [
                'status' => '0',
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $this->task_model->deleteTask($newdata, $task_id);
            session()->setFlashdata('success', lang('Messages.Success_0005'));
            $type_of_task = $_POST['type_of_task'];
            if ($type_of_task == 1) {
                return redirect()->to(base_url() . 'SCORM/Course_builder/review_course/showfeedbackReplies');
            }
        } else {
            return redirect()->to(base_url('my_training'));
        }
    }
    public function complete_master_task()
    {
        if (isset($_POST['mt_id'])) {
            $mt_id = $_POST['mt_id'];
            $newdata = [
                'status' => '2',
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $this->task_model->complete_master_task_data($newdata, $mt_id);
            session()->setFlashdata('success', lang('Messages.Success_0041'));
            return redirect()->to(base_url() . 'Task/Task_manage/project_tasks');
        } else {
            return redirect()->to(base_url('my_training'));
        }
    }
    public function change_task_status()
    {
        if (isset($_POST['task_id'])) {
            $task_id = $_POST['task_id'];
            $status = $_POST['status'];
            if ($status == 2) {
                $newdata = [
                    'status' => $status,
                    'started_on' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
            } elseif ($status == 3) {
                $effort = $_POST['actual_effort'] . '' . $_POST['effort_min'];
                $remark = isset($_POST['remark']) ? $_POST['remark'] : '';
                $newdata = [
                    'actual_effort' => $effort,
                    'remark' => $remark,
                    'status' => $status,
                    'completed_on' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
            } else {
                $newdata = [
                    'status' => $status,
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
            }
            $this->task_model->deleteTask($newdata, $task_id);

            if ($status == 0) {
                session()->setFlashdata('success', lang('Messages.Success_0005'));
                return redirect()->to(base_url() . 'Task/Task_manage/assign_master');
            } else {
                session()->setFlashdata('success', lang('Messages.Success_0008'));
                return redirect()->to(base_url() . 'Task/Task_manage');
            }
        } else {
            return redirect()->to(base_url('my_training'));
        }
    }
    public function tasks_monthly_report()
    {
        $data = [];
        if (session()->get('report_to_you') != 2) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            return redirect()->to(base_url('my_training'));
        } else {
            $user = session()->get('id_user');
            $data['domain'] = $this->HR_model->getDropdown(8);
            $data['team_task'] = $this->task_model->getTeam($user);
            echo view('templates/header_view', $data);
            echo view('tasks/tasks_monthly_report_view', $data);
            echo view('templates/footer_view', $data);
        }
    }

    public function effort_montly_report()
    {
        $data = [];
        if ($this->request->getPost()) {
            $month = $_POST['month'];
            $year = $_POST['year'];
            $user = session()->get('id_user');

            $EffortMonthlyReport = $this->task_model->getEffortMonthlyReport($month, $year, $user);
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $monthName = date("F", mktime(0, 0, 0, $month, 1)); // Converts numeric month to name
            $sheet->setCellValue('A1', "Effort Report for $monthName $year");
            $sheet->mergeCells("A1:Z1"); // Optional: Merge cells across the width
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);

            // Header: Employee Name + Days 1 to N
            $sheet->setCellValue('A3', 'Employee Name');
            $sheet->getStyle('A3')->getFont()->setBold(true);
            $sheet->getColumnDimension('A')->setAutoSize(true);

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($day + 1); // A = 1
                $sheet->setCellValue($columnLetter . '3', $day);
                $sheet->getStyle($columnLetter . '3')->getFont()->setBold(true);
                $sheet->getColumnDimension($columnLetter)->setAutoSize(true);

                // Highlight Saturday and Sunday with grey background
                $date = "$year-$month-$day";
                $dayOfWeek = date('w', strtotime($date)); // 0=Sun, 6=Sat
                if ($dayOfWeek == 0 || $dayOfWeek == 6) {
                    $sheet->getStyle($columnLetter . '3')->getFill()->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FFD3D3D3'); // Light grey
                }
            }

            // Organize data
            $organized = [];
            foreach ($EffortMonthlyReport as $entry) {
                $name = $entry['fullname'];
                $effort = $entry['emp_total_effort'];
                $dateValue = $entry['date_value'];

                if (!isset($organized[$name])) {
                    $organized[$name] = array_fill(1, $daysInMonth, null);
                }

                if ($dateValue) {
                    $day = (int) date('j', strtotime($dateValue));
                    if ($day >= 1 && $day <= $daysInMonth) {
                        $organized[$name][$day] += $effort;
                    }
                }
            }

            // Fill employee rows
            $row = 4;
            foreach ($organized as $name => $efforts) {
                $sheet->setCellValue('A' . $row, $name);
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($day + 1);
                    if ($efforts[$day] !== null) {
                        $sheet->setCellValue($col . $row, $efforts[$day]);
                    } else {
                        $sheet->setCellValue($col . $row, ''); // Set blank cell
                    }
                }
                $row++;
            }

            // // Add SUM row
            // $sumRow = $row;
            // $sheet->setCellValue('A' . $sumRow, 'Total');
            // $sheet->getStyle('A' . $sumRow)->getFont()->setBold(true);

            // for ($day = 1; $day <= $daysInMonth; $day++) {
            //     $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($day + 1);
            //     $cellRange = $col . '2:' . $col . ($sumRow - 1);
            //     $sheet->setCellValue($col . $sumRow, "=SUM($cellRange)");

            //     // Highlight the SUM row background
            //     $sheet->getStyle($col . $sumRow)->getFill()->setFillType(Fill::FILL_SOLID)
            //         ->getStartColor()->setARGB('FFFFE599'); // Light Yellow
            //     $sheet->getStyle($col . $sumRow)->getFont()->setBold(true);
            // }


            $filename = "Effort_Report_{$month}_{$year}.xlsx";
            // if (ob_get_length()) ob_end_clean();

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        }
    }
    public function effort_domain_report()
    {
        if (isset($_POST['domain'])) {
            $parts = explode('|', $_POST['domain']);
            $skill = $parts[0] ?? null;   // safe access
            $Domain = $parts[1] ?? null;   // safe access
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $rowCount = 2;
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'Sl No');
            $sheet->setCellValue('B1', 'Domain');
            $sheet->setCellValue('C1', 'Resource');
            $sheet->setCellValue('D1', 'Project Name');
            // $sheet->setCellValue('E1', 'Planned Effort');
            $sheet->setCellValue('E1', 'Actual Effort');
            $sheet->setCellValue('F1', 'Stage');
            $sheet->setCellValue('G1', 'Completed Date');


            $rows = 2;
            $domaindata = $this->task_model->getdomainleveldata($skill, $start_date, $end_date);
            $p = 0;
            if (count($domaindata) > 0) {
                foreach ($domaindata as $data) {
                    $p = $p + 1;
                    $projectname = $data['projectname'];
                    $domain = $data['domain'];
                    $Resource = $data['fname'] . '' . $data['lname'];
                    $assigned_total = $data['assigned_total'];
                    $utilized_total = $data['utilized_total'];
                    $stage = $data['stage'];
                    $date_value = $data['date_value'];
                    switch ($stage) {
                        case 1:
                            $replystage = 'Alpha';
                            break;
                        case 2:
                            $replystage = 'Beta';
                            break;
                        case 5:
                            $replystage = 'Gamma';
                            break;
                        case 0:
                            $replystage = 'Gen';
                            break;
                    }

                    $sheet->setCellValue('A' . $rowCount, $p);
                    $sheet->SetCellValue('B' . $rowCount, $domain);
                    $sheet->SetCellValue('C' . $rowCount, $Resource);
                    $sheet->SetCellValue('D' . $rowCount, $projectname);
                    // $sheet->setCellValue('E' . $rowCount, $assigned_total);
                    $sheet->SetCellValue('E' . $rowCount, $utilized_total);
                    $sheet->SetCellValue('F' . $rowCount, $replystage);
                    $sheet->SetCellValue('G' . $rowCount, $date_value); //$modname
                    $rowCount = $rowCount + 1;

                }
                $sheet->getColumnDimension('H')->setWidth(200);
            }

            $datetoday = $start_date . '_' . $end_date;
            $datetoday = str_replace(',', '_', $datetoday);
            $datetoday = preg_replace('/\s+/', '_', $datetoday);
            $filename = 'domain_level_' . trim($datetoday) . '.xlsx';

            $writer = new Xlsx($spreadsheet);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;

        }
    }
}
