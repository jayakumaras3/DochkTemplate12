<?php

namespace App\Controllers\Project_Manage;

use App\Controllers\BaseController;
use App\Models\Project_Manage\PM_projects_model;
use App\Models\Etrack\Leave_model;
use App\Models\Project_Manage\PM_pricing_sheet_model;

#[\AllowDynamicProperties]

class PM_projects extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->is_session_available();
        $this->Leave_model = new Leave_model();
        $this->PM_pricing_sheet_model = new PM_pricing_sheet_model();
        $this->PM_projects_model = new PM_projects_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url());
            exit();
        }

        $arrayuserlevel = explode(',', $userlevel);

        if (!in_array('4', $arrayuserlevel)) {

            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }
    public function index()
    {
        $data = [];
        helper(['form']);

        $data = [
            'link1' => 'my_training',
            'link1_name' => 'Dashboard',
            'link2' => '',
            'link2_name' => '',
            'link3_name' => 'Projects'
        ];
        $user = session()->get('id_user');
        $data['project_list'] = $this->PM_projects_model->get_projects_list($user);
        echo view('templates/header_view', $data);
        // echo view('dashboard/projects_left_menu', $data);
        echo view('project_management/projects_dashboard_view', $data);
        echo view('templates/footer_view');
    }


    public function projects()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['ucn_id'])) {
            $data['ucn_id'] = $_POST['ucn_id'];
            $_SESSION['ucn_id'] = $data['ucn_id'];
        } else if (isset($_SESSION['ucn_id'])) {
            $data['ucn_id'] = $_SESSION['ucn_id'];
        } else {
            session()->setFlashdata('message', 'Error loading page.');
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'Project_Manage/PM_ucn');
        }
        $user = session()->get('id_user');
        $data['projects'] = $this->PM_ucn_model->get_projects($data['ucn_id'],  $user);

        echo view('templates/header_view', $data);
        echo view('project_management/ucn_projects', $data);
        echo view('templates/footer_view');
    }

    public function skill_mapping()
    {
        $data = [];
        helper(['form']);
        $client = session()->get('client');
        $data['department_list'] = $this->PM_pricing_sheet_model->get_department($client);
        $data['all_users'] = $this->Leave_model->getUsersDetails($client);
        $data['skill_assigned'] = $this->PM_pricing_sheet_model->get_skill_assigned_list($client);
        echo view('templates/header_view', $data);
        echo view('project_management/resource_allocation/skill_mapping_view', $data);
        echo view('templates/footer_view');
    }

    public function assign_skill_employee()
    {
        $data = [];
        if ($this->request->getPost()) {
            $user = $this->request->getVar('user_select');
            $skill_val = $this->request->getVar('skill_val');
            $allocation = $this->request->getVar('allocation');
            $proficiency = $this->request->getVar('proficiency');
            $client = session()->get('client');
            $timestamp = time();
            $newdata = [
                'client_id' =>  $client,
                'user_id' =>  $user,
                'skill_id' =>  $skill_val,
                'allocation' =>  $allocation,
                'proficiency' =>  $proficiency,
                'last_updated_on' =>  $timestamp,
                'last_updated_by' => session()->get('id_user'),
                'status' => 1
            ];
            $result = $this->PM_pricing_sheet_model->assign_skill_employee($newdata);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0003'));
            }
        }
        return redirect()->to(base_url() . 'Project_Manage/PM_projects/skill_mapping');
    }

    public function delete_skill()
    {
        $data = [];
        if ($this->request->getPost()) {
            $skill_map_id = $this->request->getVar('skill_map_id');
            $result = $this->PM_pricing_sheet_model->delete_skill($skill_map_id);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0005'));
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0003'));
            }
        }
        return redirect()->to(base_url() . 'Project_Manage/PM_projects/skill_mapping');
    }
    public function resource_allocation()
    {
        $data = [];
        helper(['form']);
        $user = session()->get('id_user');
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
        $data['project_list'] = $this->PM_projects_model->get_projects_resource_allocation($user, $data['week'], $data['skill_val']);
        echo view('templates/header_view', $data);
        echo view('project_management/resource_allocation/res_projects', $data);
        echo view('templates/footer_view');
    }



    public function add_assignments()
    {
        $data = [];
        if (isset($_POST['week'])) {
            $week = $_POST['week'];
            $skill_val = $_POST['skill_val'];
            $user = session()->get('id_user');
            $project_list = $this->PM_projects_model->get_projects_resource_allocation($user, $week, $skill_val);
            foreach ($project_list as $data) {
                $proid = $data['projectid'];
                $check_data = $this->PM_projects_model->get_resource_alloc_id($proid, $week, $skill_val);
                if (count($check_data) > 0) {
                    $res_loc_id = $check_data[0]['res_loc_id'];
                    $newdata = [
                        'skill' =>  $skill_val,
                        'week_day' =>  $week,
                        'proj_id' =>  $proid,
                        'mon' => $_POST['mon_' . $proid],
                        'tue' => $_POST['tue_' . $proid],
                        'wed' => $_POST['wed_' . $proid],
                        'thu' => $_POST['thu_' . $proid],
                        'fri' => $_POST['fri_' . $proid],
                        'last_updated_on' => time(),
                        'last_updated_by' => session()->get('id_user'),
                        'status' =>  1
                    ];
                    $this->PM_projects_model->update_resource_allocation($newdata, $res_loc_id);
                } else {
                    $newdata = [
                        'skill' =>  $skill_val,
                        'week_day' =>  $week,
                        'proj_id' =>  $proid,
                        'mon' => $_POST['mon_' . $proid],
                        'tue' => $_POST['tue_' . $proid],
                        'wed' => $_POST['wed_' . $proid],
                        'thu' => $_POST['thu_' . $proid],
                        'fri' => $_POST['fri_' . $proid],
                        'last_updated_on' => time(),
                        'last_updated_by' => session()->get('id_user'),
                        'status' =>  1
                    ];
                    $this->PM_projects_model->add_resource_allocation($newdata);
                }
            }
            session()->setFlashdata('success', lang('Messages.Success_0008'));
            return redirect()->to(base_url() . 'Project_Manage/PM_projects/resource_allocation');
        }
        session()->setFlashdata('error', lang('Messages.Error_0003'));
        return redirect()->to(base_url() . 'Project_Manage/PM_projects/resource_allocation');
    }

    public function add_projects()
    {
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();
            $ucn = $this->request->getVar('ucn');
            $_SESSION['ucn_id'] =  $ucn;
            $newdata = [
                'ucn' =>  $this->request->getVar('ucn'),
                'projectname' =>  $this->request->getVar('name'),
                'project_type' =>  $this->request->getVar('type_of_project'),
                'percent' => $this->request->getVar('percentage_po'),
                'client' => session()->get('client'),
                'start_date' => $this->request->getVar('start_date'),
                'end_date' => $this->request->getVar('end_date'),
                'description' => $this->request->getVar('remarks'),
                'createdon' => $timestamp,
                'createdby' => session()->get('id_user'),
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user'),
                'status' =>  1
            ];

            $result = $this->PM_ucn_model->add_projects($newdata);

            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
                return redirect()->to(base_url() . 'Project_Manage/PM_ucn/projects');
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'Project_Manage/PM_ucn/projects');
            }
        }
    }
}
