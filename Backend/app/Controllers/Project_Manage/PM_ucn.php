<?php

namespace App\Controllers\Project_Manage;

use App\Controllers\BaseController;
use App\Models\Project_Manage\PM_ucn_model;
use App\Models\Project_Manage\PM_pricing_sheet_model;
use App\Models\User_login\Users_model;
use App\Models\SCORM\Scorm_course_model;
use App\Models\Project_Manage\PM_Proposals_model;
use App\Models\Project_Manage\PM_PO_model;
use App\Models\SCORM\Scorm_client_model;
use App\Models\SCORM\Scorm_user_group_model;
use App\Models\Settings\Settings_model;
use App\Models\User_login\Login_model;

#[\AllowDynamicProperties]
class PM_ucn extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->is_session_available();
        $this->PM_ucn_model = new PM_ucn_model();
        $this->PM_pricing_sheet_model = new PM_pricing_sheet_model();
        $this->users_model = new Users_model();
        $this->scorm_course_model = new Scorm_course_model();
        $this->scorm_client_model = new Scorm_client_model();
        $this->PM_Proposals_model = new PM_Proposals_model();
        $this->PM_PO_model = new PM_PO_model();
        $this->settings_model = new Settings_model();
        $this->login_model = new Login_model();
        $this->scorm_user_group_model = new Scorm_user_group_model();
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
            'link3_name' => 'My UCN'
        ];
        $user = session()->get('id_user');
        $data['ucn_list'] = $this->PM_ucn_model->get_ucn_list($user);
        echo view('templates/header_view', $data);
        echo view('project_management/ucn_dashboard_view', $data);
        echo view('templates/footer_view');
    }

    public function closed_ucn()
    {
        $data = [];
        helper(['form']);
        $data = [
            'link1' => 'my_training',
            'link1_name' => 'Dashboard',
            'link2' => '',
            'link2_name' => '',
            'link3_name' => 'Closed UCN'
        ];
        $user = session()->get('id_user');
        $data['ucn_list'] = $this->PM_ucn_model->get_closed_ucn_list($user);
        echo view('templates/header_view', $data);
        echo view('project_management/closed_ucn_view', $data);
        echo view('templates/footer_view');
    }

    public function create_new_ucn()
    {
        $data = [];
        helper(['form']);
        $data['clientlist'] = $this->settings_model->get_my_client_list(session()->get('id_user'));
        $data['salesuser'] = $this->PM_pricing_sheet_model->getSalesuseraccess(68);
        echo view('templates/header_view', $data);
        echo view('project_management/ucn_create_new_view', $data);
        echo view('templates/footer_view');
    }

    public function addeffort($ucn_id, $effort, $type, $type_of_resource, $remarks)
    {
        if ($effort > 0) {
            $newdata = [
                'ucn_id' => $ucn_id,
                'effort' => $effort,
                'type' => $type,
                'type_resource' => $type_of_resource,
                'remarks' => $remarks,
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
                'status' => '1'
            ];
            $this->PM_pricing_sheet_model->add_new_cost($newdata);
        }
        return;
    }

    public function add_new_ucn()
    {
        $newdata = [
            'description' => $_POST['ucn_name'],
            'client_id' => $_POST['client'],
            'account_manager' => $_POST['account_manager'],
            'po_value' => $_POST['PO_value'],
            'po_number' => $_POST['PO_number'],
            'project_value' => $_POST['Proj_value'],
            'po_status' => $_POST['po_status'],
            'last_updated_on' => time(),
            'created_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'last_updated_by' => session()->get('id_user'),
            'status' => '1'
        ];

        $PO_ID = $this->PM_PO_model->add_new_purchase_order($newdata);

        $ucn_data = [
            'name' => $_POST['ucn_name'],
            'client' => $_POST['client'],
            'account_manager' => $_POST['account_manager'],
            'created_on' => time(),
            'created_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'last_updated_by' => session()->get('id_user'),
            'status' => 1
        ];
        $ucn_id = $this->PM_PO_model->add_ucn($ucn_data, $PO_ID);


        if ($_POST['ID_effort'] > 0) {
            $this->addeffort($ucn_id, $_POST['ID_effort'], 1, 52, '');
        }
        if ($_POST['CE_effort'] > 0) {
            $this->addeffort($ucn_id, $_POST['CE_effort'], 1, 2, '');
        }
        if ($_POST['Graphic_effort'] > 0) {
            $this->addeffort($ucn_id, $_POST['Graphic_effort'], 1, 3, '');
        }
        if ($_POST['Media_effort'] > 0) {
            $this->addeffort($ucn_id, $_POST['Media_effort'], 1, 4, '');
        }
        if ($_POST['Viz_effort'] > 0) {
            $this->addeffort($ucn_id, $_POST['Viz_effort'], 1, 5, '');
        }
        if ($_POST['PP_effort'] > 0) {
            $this->addeffort($ucn_id, $_POST['PP_effort'], 1, 6, '');
        }
        if ($_POST['AR_effort'] > 0) {
            $this->addeffort($ucn_id, $_POST['AR_effort'], 1, 7, '');
        }
        if ($_POST['3D_effort'] > 0) {
            $this->addeffort($ucn_id, $_POST['3D_effort'], 1, 8, '');
        }
        if ($_POST['GP_effort'] > 0) {
            $this->addeffort($ucn_id, $_POST['GP_effort'], 1, 9, '');
        }
        if ($_POST['QA_effort'] > 0) {
            $this->addeffort($ucn_id, $_POST['QA_effort'], 1, 10, '');
        }
        if ($_POST['Unity_effort'] > 0) {
            $this->addeffort($ucn_id, $_POST['Unity_effort'], 1, 51, '');
        }
        if ($_POST['PM_effort'] > 0) {
            $this->addeffort($ucn_id, $_POST['PM_effort'], 1, 53, '');
        }
        if ($_POST['SME_effort'] > 0) {
            $this->addeffort($ucn_id, $_POST['SME_effort'], 1, 54, '');
        }
        if ($_POST['value_1'] > 0) {
            $this->addeffort($ucn_id, $_POST['value_1'], 2, 55, $_POST['desc_1']);
        }
        if ($_POST['value_2'] > 0) {
            $this->addeffort($ucn_id, $_POST['value_2'], 2, 56, $_POST['desc_2']);
        }

        session()->setFlashdata('success', lang('Messages.Success_0011'));
        return redirect()->to(base_url() . 'Project_Manage/PM_ucn');
    }
    public function updateeffort($ucn_id, $effort, $type, $type_of_resource, $remarks)
    {
        $is_data_available = $this->PM_pricing_sheet_model->get_ucn_effort_data($ucn_id, $type_of_resource);

        if ($is_data_available) {
            $bidc = $is_data_available[0]['bidc'];
            $newdata = [
                'effort' => $effort,
                'remarks' => $remarks,
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user')
            ];
            $this->PM_pricing_sheet_model->update_cost($newdata, $bidc);
        } else {
            if ($effort > 0) {
                $newdata = [
                    'ucn_id' => $ucn_id,
                    'effort' => $effort,
                    'type' => $type,
                    'type_resource' => $type_of_resource,
                    'remarks' => $remarks,
                    'last_updated_on' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'status' => '1'
                ];
                $this->PM_pricing_sheet_model->add_new_cost($newdata);
            }
        }
        return;
    }
    public function update_effort_data()
    {
        $ucn_id = $_POST['ucn_id'];
        if ($_POST['ID_effort'] != '') {
            $this->updateeffort($ucn_id, $_POST['ID_effort'], 1, 52, '');
        }
        if ($_POST['CE_effort'] != '') {
            $this->updateeffort($ucn_id, $_POST['CE_effort'], 1, 2, '');
        }
        if ($_POST['Graphic_effort'] != '') {
            $this->updateeffort($ucn_id, $_POST['Graphic_effort'], 1, 3, '');
        }
        if ($_POST['Media_effort'] != '') {
            $this->updateeffort($ucn_id, $_POST['Media_effort'], 1, 4, '');
        }
        if ($_POST['Viz_effort'] != '') {
            $this->updateeffort($ucn_id, $_POST['Viz_effort'], 1, 5, '');
        }
        if ($_POST['PP_effort'] != '') {
            $this->updateeffort($ucn_id, $_POST['PP_effort'], 1, 6, '');
        }
        if ($_POST['AR_effort'] != '') {
            $this->updateeffort($ucn_id, $_POST['AR_effort'], 1, 7, '');
        }
        if ($_POST['3D_effort'] != '') {
            $this->updateeffort($ucn_id, $_POST['3D_effort'], 1, 8, '');
        }
        if ($_POST['GP_effort'] != '') {
            $this->updateeffort($ucn_id, $_POST['GP_effort'], 1, 9, '');
        }
        if ($_POST['QA_effort'] != '') {
            $this->updateeffort($ucn_id, $_POST['QA_effort'], 1, 10, '');
        }
        if ($_POST['Unity_effort'] != '') {
            $this->updateeffort($ucn_id, $_POST['Unity_effort'], 1, 51, '');
        }
        if ($_POST['PM_effort'] != '') {
            $this->updateeffort($ucn_id, $_POST['PM_effort'], 1, 53, '');
        }
        if ($_POST['SME_effort'] != '') {
            $this->updateeffort($ucn_id, $_POST['SME_effort'], 1, 54, '');
        }
        if ($_POST['value_1'] != '') {
            $this->updateeffort($ucn_id, $_POST['value_1'], 2, 55, $_POST['desc_1']);
        }
        if ($_POST['value_2'] != '') {
            $this->updateeffort($ucn_id, $_POST['value_2'], 2, 56, $_POST['desc_2']);
        }
        session()->setFlashdata('success', lang('Messages.Success_0008'));
        return redirect()->to(base_url() . 'Project_Manage/PM_ucn/edit_ucn');
    }
    public function edit_ucn()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['ucn_id'])) {
            $data['ucn_id'] = $_POST['ucn_id'];
            $data['projectclient'] = $_POST['client'];
            $_SESSION['ucn_id'] = $data['ucn_id'];
            $_SESSION['projectclient'] = $data['projectclient'];
        } else if (isset($_SESSION['ucn_id'])) {
            $data['ucn_id'] = $_SESSION['ucn_id'];
            $data['projectclient'] = $_SESSION['projectclient'];
        } else {
            session()->setFlashdata('message', 'Error loading page.');
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'Project_Manage/PM_ucn');
        }

        $UCNdetails = $this->PM_ucn_model->getUCNdetails($data['ucn_id']);

        $user = session()->get('id_user');
        $data['projects'] = $this->PM_ucn_model->get_projects_by_ucn($data['ucn_id'], $user);
        $data['ucn'] = $UCNdetails[0];

        $data['project_manager'] = $this->PM_pricing_sheet_model->get_project_manager();
        $data['access'] = $this->PM_pricing_sheet_model->get_access_info($data['ucn_id'], 5);
 

        $data['Year'] = date('Y');
        $data['Month'] = date('m');
        $previousMonthDate = new \DateTime();
        $previousMonthDate->modify('first day of last month');
        $previousYear = $previousMonthDate->format('Y');  // Previous Year
        $previousMonth = $previousMonthDate->format('m');

        $data['ucn_percent_exist'] = $this->PM_ucn_model->get_ucn_percent_exist($data['ucn_id'], $previousYear, $previousMonth);
        $data['ucn_percent'] = $this->PM_ucn_model->get_ucn_percent($data['ucn_id'], $data['Year']);
        $data['po_details_for_ucn'] = $this->PM_ucn_model->po_details_for_ucn($data['ucn_id']);

        $data['get_effort'] = $this->PM_ucn_model->get_effort($data['ucn_id']);
        $data['get_actual'] = $this->PM_ucn_model->get_actual_effort($data['ucn_id']);
 

        $data['external_cost'] = $this->PM_ucn_model->get_cost($data['ucn_id']);
        $data['external_actual_cost'] = $this->PM_ucn_model->get_external_actual_cost($data['ucn_id']);


        echo view('templates/header_view', $data);
        echo view('project_management/ucn_edit', $data);
        echo view('templates/footer_view');
    }

    public function view_claims()
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
        $data['claims'] = $this->PM_ucn_model->get_claims($data['ucn_id']);
        echo view('templates/header_view');
        echo view('project_management/ucn_claims_view', $data);
        echo view('templates/footer_view');
    }

    public function claim_action()
    {
        $claim_id = $_POST['claim_id'];
        $action = $_POST['action'];
        $notes = $_POST['notes'] ?? '';

        if ($action == 3) {
            $this->PM_ucn_model->claim_status_change($claim_id, 3, $notes);
            session()->setFlashdata('success', 'Claim approved successfully.');
        } elseif ($action == 10) {
            $this->PM_ucn_model->claim_status_change($claim_id, 10, $notes);
            session()->setFlashdata('success', 'Claim rejected.');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
        }

        return redirect()->to(base_url() . 'Project_Manage/PM_ucn/view_claims');
    }
    public function edit_effort_ucn()
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
        $data['effort_data'] = $this->PM_ucn_model->get_ucn_effort($data['ucn_id']);
        echo view('templates/header_view');
        echo view('project_management/ucn_effort_edit', $data);
        echo view('templates/footer_view');
    }

    public function edit_ucn_details()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['id_ucn'])) {
            $data['id_ucn'] = $_POST['id_ucn'];
            $_SESSION['id_ucn'] = $data['id_ucn'];
        } else if (isset($_SESSION['id_ucn'])) {
            $data['id_ucn'] = $_SESSION['id_ucn'];
        } else {
            session()->setFlashdata('message', 'Error loading page.');
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'Project_Manage/PM_ucn');
        }
        if (isset($_POST['return_page']) && $_POST['return_page'] == 2) {
            $data['return_page'] = 2;
        } elseif (isset($_POST['return_page']) && $_POST['return_page'] == 3) {
            $data['return_page'] = 3;
        } else {
            $data['return_page'] = 1;
        }
        $data['clientlist'] = $this->settings_model->get_my_client_list(session()->get('id_user'));
        $data['salesuser'] = $this->PM_pricing_sheet_model->getSalesuseraccess(68);
        $data['ucn_list'] = $this->PM_ucn_model->get_ucn_details($data['id_ucn']);
        echo view('templates/header_view', $data);
        echo view('project_management/ucn_edit_view', $data);
        echo view('templates/footer_view');
    }

    public function allocate_effort_to_manager()
    {

        $newdata = [
            'ucn_id' => $_POST['ucn'],
            'project_id' => $_POST['projectid'],
            'skill_id' => $_POST['skillId'],
            'effort' => $_POST['effort'],
            'stage' => $_POST['stage'],
            'manager' => $_POST['manager'],
            'status' => 1,
            'last_updated_on' => time(),
            'last_updated_by' => session()->get('id_user')
        ];
        $this->PM_ucn_model->add_master_effort_to_ucn($newdata);
        session()->setFlashdata('success', lang('Messages.Success_0011'));
        return redirect()->to(base_url() . 'Project_Manage/PM_ucn/project_breakdown');
    }
    public function allocate_effort_to_manager_bulk()
    {
        // $data = $this->request->getPost();
        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
        // exit;
        $ucns = $this->request->getPost('ucns');
        $projectids = $this->request->getPost('projectids');
        $skillIds = $this->request->getPost('skillIds');
        $efforts = $this->request->getPost('effort');
        $stages = $this->request->getPost('stage');
        $remarks = $this->request->getPost('remarks');

        $managers = $this->request->getPost('manager');
        if (!empty($managers)) {
            foreach ($managers as $i => $manager) {
                if (
                    isset($ucns[$i], $projectids[$i], $skillIds[$i], $efforts[$i], $stages[$i]) &&
                    !empty($manager) && !empty($efforts[$i])
                ) {
                    $newData = [
                        'ucn_id' => $ucns[$i],
                        'project_id' => $projectids[$i],
                        'skill_id' => $skillIds[$i],
                        'effort' => $efforts[$i],
                        'stage' => $stages[$i],
                        'manager' => $manager,
                        'remarks' => $remarks[$i],
                        'status' => 1,
                        'last_updated_on' => time(),
                        'last_updated_by' => session()->get('id_user')
                    ];

                    $this->PM_ucn_model->add_master_effort_to_ucn($newData);
                }
            }

            session()->setFlashdata('success', lang('Messages.Success_0011'));
            return redirect()->to(base_url() . 'Project_Manage/PM_ucn/project_breakdown');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            return redirect()->to(base_url() . 'Project_Manage/PM_ucn/project_breakdown');
        }
    }
    public function update_ucn_data()
    {
        $ucn_id = $this->request->getVar('ucn_id');
        $return_page = $this->request->getVar('return_page');
        $newdata = [
            'name' => $_POST['ucn_name'],
            'start_dt' => $_POST['start_date'],
            'end_dt' => $_POST['end_date'],
            'client' => $_POST['client'],
            'scope' => $_POST['scope'],
            'account_manager' => $_POST['account_manager'],
            'remarks' => $_POST['remarks'],
            'status' => $_POST['status'],
            'last_updated_on' => time(),
            'last_updated_by' => session()->get('id_user')
        ];
        $this->PM_PO_model->updateUcndata($newdata, $ucn_id);
        session()->setFlashdata('success', lang('Messages.Success_0008'));
        if ($return_page == 2) {
            return redirect()->to(base_url() . 'Etrack/Claims/Status');
        }elseif ($return_page == 3) {
            return redirect()->to(base_url() . 'User_login/client_list/client_status');
        } else {
            return redirect()->to(base_url() . 'Project_Manage/PM_ucn');
        }
    }
    public function updateUCNData()
    {
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();
            $ucn_id = $this->request->getVar('ucn_id');
            $ucn_name = $this->request->getVar('ucn_name');
            $newdata = [
                'name' => $ucn_name,
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user'),
                'status' => $this->request->getVar('status')
            ];

            $result = $this->PM_PO_model->updateUcndata($newdata, $ucn_id);

            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
                return redirect()->to(base_url() . 'Project_Manage/PM_ucn/edit_ucn');
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'Project_Manage/PM_ucn/edit_ucn');
            }
        }
    }


    public function add_percentage()
    {
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();
            $ucn = $this->request->getVar('ucn');
            $_SESSION['ucn_id'] = $ucn;
            $Year = date('Y');
            $Month = date('m');
            $previousMonthDate = new \DateTime();
            $previousMonthDate->modify('first day of last month');
            $previousYear = $previousMonthDate->format('Y');  // Previous Year
            $previousMonth = $previousMonthDate->format('m');  // Previous Month (01-12)

            $newdata = [
                'percent' => $this->request->getVar('percent'),
                'remarks' => $this->request->getVar('remarks'),
                'ucn_id' => $ucn,
                'year' => $previousYear,
                'month' => $previousMonth,
                'created_on' => $timestamp,
                'created_by' => session()->get('id_user'),
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user'),
                'status' => 1
            ];

            $result = $this->PM_ucn_model->add_percentage($newdata);

            $ucndata = [
                'wip' => $this->request->getVar('percent'),
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user')
            ];

            $result = $this->PM_ucn_model->update_ucn_percentage($ucndata, $ucn);

            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
                return redirect()->to(base_url() . 'Project_Manage/PM_ucn/edit_ucn');
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'Project_Manage/PM_ucn/edit_ucn');
            }
        }
    }

    public function update_ucn_percentage()
    {
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();
            $ucn_percent_id = $this->request->getVar('ucn_percent_id');
            $ucn = $this->request->getVar('ucn');
            $newdata = [
                'percent' => $this->request->getVar('percent'),
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user')
            ];

            $result = $this->PM_ucn_model->update_percentage($newdata, $ucn_percent_id);
            $ucndata = [
                'wip' => $this->request->getVar('percent'),
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user')
            ];

            $result = $this->PM_ucn_model->update_ucn_percentage($ucndata, $ucn);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0008'));
                return redirect()->to(base_url() . 'Project_Manage/PM_ucn/edit_ucn');
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'Project_Manage/PM_ucn/edit_ucn');
            }
        }
    }

    public function projects()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['ucn_id'])) {
            $data['ucn_id'] = $_POST['ucn_id'];
            $_SESSION['ucn_id'] = $data['ucn_id'];
            $data['projectclient'] = $_POST['projectclient'];
            $_SESSION['projectclient'] = $data['projectclient'];
            //  $_SESSION['client'] = $data['client'];
        } else if (isset($_SESSION['ucn_id'])) {
            $data['ucn_id'] = $_SESSION['ucn_id'];
            $data['projectclient'] = $_SESSION['projectclient'];
        } else {
            session()->setFlashdata('message', 'Error loading page.');
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'Project_Manage/PM_ucn');
        }
        $user = session()->get('id_user');
        //$data['projects'] = $this->PM_ucn_model->get_projects($data['ucn_id'],  $user);

        echo view('templates/header_view', $data);
        echo view('project_management/ucn_projects', $data);
        echo view('templates/footer_view');
    }
    public function edit_project_details()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['projectid'])) {
            $data['projectid'] = $_POST['projectid'];
            $_SESSION['projectid'] = $data['projectid'];
        }
        if (isset($_SESSION['projectid'])) {
            $data['projectid'] = $_SESSION['projectid'];
        } else {
            session()->setFlashdata('message', 'Error loading page.');
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'Project_Manage/PM_ucn/projects');
        }
        $data['returnid'] = $this->request->getVar('returnid');
        $data['project_details'] = $this->PM_ucn_model->get_project_details($data['projectid']);
        $data['project_manager'] = $this->PM_pricing_sheet_model->get_project_manager();
        $data['access'] = $this->PM_pricing_sheet_model->get_access_info($data['projectid'], 1);
        echo view('templates/header_view', $data);
        echo view('project_management/ucn_edit_details', $data); 
        echo view('templates/footer_view');
    }

    public function project_breakdown()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['projectid'])) {
            $data['projectid'] = $_POST['projectid'];
            $_SESSION['projectid'] = $data['projectid'];
        }
        if (isset($_SESSION['projectid'])) {
            $data['projectid'] = $_SESSION['projectid'];
        } else {
            session()->setFlashdata('message', 'Error loading page.');
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'Project_Manage/PM_ucn/projects');
        }
        $user = session()->get('id_user');
        $data['self_user'] = $user;
        $data['self_user_name'] = session()->get('name');
        $data['department_list'] = $this->PM_pricing_sheet_model->get_department(1);
        $data['returnid'] = $this->request->getVar('returnid');
        $data['project_details'] = $this->PM_ucn_model->get_project_details($data['projectid']);
        $data['manager_allocated_effort'] = $this->PM_ucn_model->get_manager_allocated_effort($data['projectid']);
        $data['ucn'] = $data['project_details'][0]['ucn'];
        $data['managerlist'] = $this->login_model->getManagers();
        $data['effort_data'] = $this->PM_ucn_model->get_ucn_effort($data['ucn']);
        echo view('templates/header_view', $data);
        echo view('project_management/ucn_project_breakup', $data);
        echo view('templates/footer_view');
    }

    public function view_effort_details()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['projectid'])) {
            $_SESSION['projectid'] = $_POST['projectid'];
            $_SESSION['ucn_id'] = $_POST['ucn'];
            $_SESSION['skillId'] = $_POST['skillId'];
            $data['projectid'] = $_POST['projectid'];
            $data['ucn_id'] = $_POST['ucn'];
            $data['skillId'] = $_POST['skillId'];
        } elseif (isset($_SESSION['projectid'])) {
            $data['projectid'] = $_SESSION['projectid'];
            $data['ucn_id'] = $_SESSION['ucn_id'];
            $data['skillId'] = $_SESSION['skillId'];
        }
        $data['get_project_data'] = $this->PM_ucn_model->get_project_data($data['projectid'], $data['ucn_id'], $data['skillId']);
        echo view('templates/header_view', $data);
        echo view('project_management/ucn_project_breakupeffort', $data);
        echo view('templates/footer_view');
    }

    public function view_mst_detail()
    {
        $data = [];
        $data['ucn_mst_id'] = $_POST['ucn_mst_id'];
        $data['get_emp_data'] = $this->PM_ucn_model->get_emp_data($data['ucn_mst_id']);
        echo view('templates/header_view', $data);
        echo view('project_management/ucn_employee_breakupeffort', $data);
        echo view('templates/footer_view');
    }

    public function close_mst_task()
    {
        $ucn_mst_id = $_POST['ucn_mst_id'];
        $newData = [
            'status' => $_POST['status'],
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $this->PM_ucn_model->close_master($newData, $ucn_mst_id);

        $this->PM_ucn_model->close_tl_effort($newData, $ucn_mst_id);

        if ($_POST['status'] == 0) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
        } else {
            session()->setFlashdata('success', lang('Messages.Success_0040'));
        }
        return redirect()->to(base_url() . 'Project_Manage/PM_ucn/view_effort_details');
    }
    public function team()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
            $data['project_id'] = $_POST['project_id'];
            $_SESSION['project_id'] = $data['project_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
            $data['project_id'] = $_SESSION['project_id'];
        } else {
            session()->setFlashdata('message', 'Error loading page.');
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'Project_Manage/PM_projects');
        }
        $data['project_manager'] = $this->PM_pricing_sheet_model->get_project_manager();
        // $data['access']  = $this->PM_pricing_sheet_model->get_access_info($data['projectid'], 1);
        $data['stage'] = $this->scorm_course_model->getCourseDetails($data['scourse_id']);
        // print_r($data['stage']);
        // exit();  
        $data['department_list'] = $this->PM_pricing_sheet_model->get_department(1);
        $data['getUserclientlist'] = $this->PM_ucn_model->getallusersclient($data['project_id']);
        $data['getclientIDforproject'] = $this->PM_ucn_model->getclientIDforproject($data['project_id']);
        $client = isset($data['getclientIDforproject'][0]['client']) ? $data['getclientIDforproject'][0]['client'] : '';

        $data['coursegroupdata'] = $this->scorm_user_group_model->getCoursegroupdate(4, $client);

        $data['client_access'] = $this->PM_ucn_model->getAssignedUsercourse($data['scourse_id'], $client);
        echo view('templates/header_view', $data);
        echo view('project_management/projects_team', $data);
        echo view('templates/footer_view');
    }

    function assign_user_group_reviewers()
    {
        $group_id = $_POST['group_id'];
        $usergroup = $this->scorm_client_model->getUsergroup($group_id);
        $newData = [
            'course_id' => $_POST['course_id'],
            'stage' => $_POST['stage'],
            'due_date' => isset($_POST['due_date']) ? $_POST['due_date'] : '0000-00-00',
            'expiry_date' => '0000-00-00',
            'scenario_id' => 0,
            'role' => 5,
            'id_user' => $usergroup,
            'createdby' => session()->get('id_user'),
            'createdon' => time(),
            // 'last_updated_by' =>  session()->get('id_user'),
            // 'last_updated_on' => time(),
        ];
        $this->scorm_client_model->addusergrouptocourses($newData);
        session()->setFlashdata('success', lang('Messages.Success_0011'));
        return redirect()->to(base_url() . 'Project_Manage/PM_ucn/team');
    }
    function assign_task_to_reviewer()
    {
        $user_assign_id = $_POST['user_assign_id'];
        $newData = [
            'role' => 5
        ];
        session()->setFlashdata('success', lang('Messages.Success_0018'));
        $this->PM_ucn_model->delete_assigneduser($newData, $user_assign_id);
        return redirect()->to(base_url() . 'Project_Manage/PM_ucn/team');
    }
    function delete_assigneduser()
    {
        $user_assign_id = $_POST['user_assign_id'];
        $newData = [
            'status' => 0,
            'last_updated_on' => time(),
            'last_updated_by' => session()->get('username'),

        ];
        $result = $this->PM_ucn_model->delete_assigneduser($newData, $user_assign_id);
        if ($result) {
            return redirect()->to(base_url() . 'Project_Manage/PM_ucn/team');
        }
    }
    public function assignreviewer()
    {
        $newData = [
            'id_user' => $_POST['assignuser'],
            'due_date' => $_POST['due_date'],
            'role' => '5',
            'stage' => $_POST['stage'],
            'course_status' => $_POST['coursestatus'],
            'course_id' => $_POST['course_id'],
            'status' => 1,
            'createdby' => session()->get('id_user'),
            'createdon' => time(),
        ];
        $result = $this->PM_ucn_model->assignreviewerdata($newData);
        echo json_encode($result);
    }
    public function delete_reviewid()
    {
        $newData = [
            'username' => $_POST['assignuser'],
            'duedate' => $_POST['due_date'],
            // 'description' => $_POST['description'],
            'role' => $_POST['role'],
            'coursestatus' => $_POST['coursestatus'],
            'courseid' => $_POST['course_id'],
            'status' => 1,
            'createdby' => session()->get('id_user'),
            'createdon' => time(),
        ];
    }
    public function update_project_details()
    {
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();
            $ucnprojectid = $this->request->getVar('projectid');

            $newdata = [
                'projectname' => $this->request->getVar('name'),
                'project_type' => $this->request->getVar('type_of_project'),
                'wip' => $this->request->getVar('wip'),
                'start_date' => $this->request->getVar('start_date'),
                'end_date' => $this->request->getVar('end_date'),
                'percent' => $this->request->getVar('percent'),
                'project_type' => $this->request->getVar('project_type'),
                'description' => $this->request->getVar('remarks'),
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user'),
                'status' => $this->request->getVar('status')
            ];

            $this->PM_ucn_model->update_projects($newdata, $ucnprojectid);

            if ($this->request->getVar('status') == 4) {
                $newData = [
                    'status' => 2,
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                $this->PM_ucn_model->close_tl_task_by_pid($newData, $ucnprojectid);
                $this->PM_ucn_model->close_pm_task_by_pid($newData, $ucnprojectid);
            }

            session()->setFlashdata('success', lang('Messages.Success_0008'));
            return redirect()->to(base_url() . 'Project_Manage/PM_ucn/edit_ucn');
        }
    }

    public function add_projects()
    {
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();
            $ucn = $this->request->getVar('ucn');
            $_SESSION['ucn_id'] = $ucn;
            $client = $this->request->getVar('client');
         
            if (isset($client)) {
                $newdata = [
                    'ucn' => $this->request->getVar('ucn'),
                    'projectname' => $this->request->getVar('name'),
                    'project_type' => $this->request->getVar('type_of_project'),
                    'percent' => $this->request->getVar('percentage_po'),
                    'client' => $client,
                    'start_date' => $this->request->getVar('start_date'),
                    'end_date' => $this->request->getVar('end_date'),
                    'description' => $this->request->getVar('remarks'),
                    'createdon' => $timestamp,
                    'createdby' => session()->get('id_user'),
                    'last_updated_on' => $timestamp,
                    'last_updated_by' => session()->get('id_user'),
                    'status' => 1
                ];
                $course_name = $this->request->getVar('name');
                $result = $this->PM_ucn_model->add_projects($newdata, $ucn, $course_name);

                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0011'));
                    return redirect()->to(base_url() . 'Project_Manage/PM_ucn/edit_ucn');
                } else {
                    session()->setFlashdata('message', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . 'Project_Manage/PM_ucn/edit_ucn');
                }
            } else {
                session()->setFlashdata('message', 'Client is Not Assigned to UCN');
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'Project_Manage/PM_ucn/edit_ucn');
            }
        }
    }
}
