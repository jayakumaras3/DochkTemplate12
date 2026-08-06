<?php

namespace App\Controllers\Project_Manage;

use App\Controllers\BaseController;
use App\Models\Settings\Settings_model;
use App\Models\Project_Manage\PM_pricing_sheet_model;
use Dompdf\Dompdf;

#[\AllowDynamicProperties]
class PM_pricing_sheet extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->is_session_available();
        $this->PM_pricing_sheet_model = new PM_pricing_sheet_model();
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
            'link3_name' => 'Effort Sheet'
        ];
        $data['pricing_sheet_list'] = $this->PM_pricing_sheet_model->get_pricing_sheet_list();
        echo view('templates/header_view', $data);
        // echo view('dashboard/projects_left_menu', $data);
        echo view('project_management/pricing_sheet_dashboard_view', $data);
        echo view('templates/footer_view');
    }

    public function add_pricing_sheet()
    {
        $data = [];
        helper(['form']);
        $data['post'] = '';
        $data['getclients'] = $this->PM_pricing_sheet_model->getclients_project_assignment();
        $data['salesuser'] =  $this->PM_pricing_sheet_model->getSalesuseraccess(68);
        echo view('templates/header_view', $data);
        echo view('project_management/pricing_sheet_add', $data);
        echo view('templates/footer_view');
    }
    public function add_pricing_sheet_submit()
    {
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();

            $newdata = [
                'margin' => $this->request->getVar('margin'),
                'pricing_model' => $this->request->getVar('pricing_model'),

                'proposal_name' => $this->request->getVar('pricing_name'),
                'requested_on' => date('Y-m-d', $timestamp),
                'client' =>  $this->request->getVar('client'),
                'currency' => 1,
                'requested_by' =>  $this->request->getVar('account_manager'),
                'description' => $this->request->getVar('description'),
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user'),
                'status' => '1'
            ];

            $result = $this->PM_pricing_sheet_model->add_new_pricing_sheet($newdata);
            if ($result) {
                session()->setFlashdata('ppid', $result);
                session()->setFlashdata('success', lang('Messages.Success_0011'));
                return redirect()->to(base_url() . 'Project_Manage/PM_pricing_sheet/edit_pricing_sheet');
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'Project_Manage/PM_pricing_sheet');
            }
        }
    }
    public function update_pricing_sheet_submit()
    {
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();
            $ppid = $this->request->getVar('ppid');
            $newdata = [
                'margin' => $this->request->getVar('margin'),
                'pricing_model' => $this->request->getVar('pricing_model'),
                'currency' => $this->request->getVar('currency'),
                'proposal_name' => $this->request->getVar('pricing_name'),
                'requested_on' => date('Y-m-d', $timestamp),
                'client' =>  $this->request->getVar('client'),
                'requested_by' =>  $this->request->getVar('account_manager'),
                'description' => $this->request->getVar('description'),
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user'),
                'status' => '1'
            ];

            $result = $this->PM_pricing_sheet_model->update_pricing_sheet($newdata, $ppid);

            if ($result) {
                session()->setFlashdata('ppid', $ppid);
                session()->setFlashdata('success', lang('Messages.Success_0011'));
                return redirect()->to(base_url() . 'Project_Manage/PM_pricing_sheet/edit_pricing_sheet');
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'Project_Manage/PM_pricing_sheet/edit_pricing_sheet');
            }
        }
    }
    public function edit_pricing_sheet()
    {
        $data = [];
        helper(['form']);
        // print_r($_POST['ppid']);
        // exit();
        if (isset($_POST['ppid'])) {
            $data['ppid'] = $this->request->getVar('ppid');;
            $_SESSION['ppid'] =  $data['ppid'];
        } else if (isset($_SESSION['ppid'])) {
            $data['ppid'] = $_SESSION['ppid'];
        } else {
            $data['ppid'] = session()->get('ppid');
        }

        $data['project_manager']  = $this->PM_pricing_sheet_model->get_project_manager();
        $data['access']  = $this->PM_pricing_sheet_model->get_access_info($data['ppid'], 2);
        $data['getclients'] = $this->PM_pricing_sheet_model->getclients_project_assignment();
        $data['get_pricing_sheet_data'] = $this->PM_pricing_sheet_model->get_pricing_sheet_data($data['ppid']);
        // print_r($data['ppid']);
        // exit();
        $data['get_pricing_sheet_details'] = $this->PM_pricing_sheet_model->get_pricing_sheet_details($data['ppid']);
        $data['salesuser'] =  $this->PM_pricing_sheet_model->getSalesuseraccess(68);
        $data['check_purchase_orders'] =  $this->PM_pricing_sheet_model->check_purchase_orders($data['ppid']);
        echo view('templates/header_view', $data);
        echo view('project_management/pricing_sheet_edit', $data);
        echo view('templates/footer_view');
    }

    public function  add_user_to_pricing_sheet()
    {
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();
            if (isset($_POST['ppid'])) {
                $data['ppid'] = $this->request->getVar('ppid');
                $_SESSION['ppid'] =  $data['ppid'];
            } else if (isset($_SESSION['ppid'])) {
                $data['ppid'] = $_SESSION['ppid'];
            }
            $newdata = [
                'type_of_assignment'  =>  $this->request->getVar('type_of_assignment'),
                'db_id' =>  $data['ppid'],
                'type_of_role' =>  $this->request->getVar('role'),
                'user_id' =>  $this->request->getVar('assignuser'),

                'created_on' => $timestamp,
                'created_by' => session()->get('id_user'),
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user'),
                'status' => '1'
            ];

            $result = $this->PM_pricing_sheet_model->add_user_to_pricing($newdata);
            $returnid = $this->request->getVar('returnid');
            if ($returnid == 1) {
                $returnurl = 'Project_Manage/PM_ucn/team';
            }
            if ($returnid == 2) {
                $returnurl = 'Project_Manage/PM_pricing_sheet/edit_pricing_sheet';
            }
            if ($returnid == 3) {
                $returnurl = 'Project_Manage/PM_Proposals/proposal_edit';
            }
            if ($returnid == 4) {
                $returnurl = 'Project_Manage/PM_purchase_order/edit_purchase_order';
            }
            if ($returnid == 5) {
                $returnurl = 'Project_Manage/PM_ucn/edit_ucn';
            }
            if ($returnid == 6) {
                $returnurl = 'Project_Manage/PM_ucn/edit_project_details';
            }
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
                return redirect()->to(base_url() . $returnurl);
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . $returnurl);
            }
        }
    }

    public function delete_userassignment()
    {
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();
            if (isset($_POST['ppid'])) {
                $_SESSION['ppid'] =  $_POST['ppid'];
            }
            $project_assign_id = $this->request->getVar('project_assign_id');
            $newdata = [
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user'),
                'status' => 0
            ];

            $result = $this->PM_pricing_sheet_model->delete_userassigned($newdata, $project_assign_id);
            $returnid = $this->request->getVar('returnid');
            if ($returnid == 1) {
                $returnurl = 'Project_Manage/PM_ucn/team';
            }
            if ($returnid == 2) {
                $returnurl = 'Project_Manage/PM_pricing_sheet/edit_pricing_sheet';
            }
            if ($returnid == 3) {
                $returnurl = 'Project_Manage/PM_Proposals/proposal_edit';
            }
            if ($returnid == 4) {
                $returnurl = 'Project_Manage/PM_purchase_order/edit_purchase_order';
            }
            if ($returnid == 5) {
                $returnurl = 'Project_Manage/PM_ucn/edit_ucn';
            }
            if ($returnid == 6) {
                $returnurl = 'Project_Manage/PM_ucn/edit_project_details';
            }
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0003'));
                return redirect()->to(base_url() . $returnurl);
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . $returnurl);
            }
        }
    }

    public function add_cost()
    {
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();
            if (isset($_POST['ppid'])) {
                $data['ppid'] = $this->request->getVar('ppid');
                $_SESSION['ppid'] =  $data['ppid'];
            } else if (isset($_SESSION['ppid'])) {
                $data['ppid'] = $_SESSION['ppid'];
            }
            $newdata = [
                'ppid' => $data['ppid'],
                'currency' => $this->request->getVar('currency'),
                'effort' =>  $this->request->getVar('cost'),
                'remarks' =>  $this->request->getVar('remarks'),
                'type' =>  $this->request->getVar('type'),
                'type_resource' =>  $this->request->getVar('type_resource'),
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user'),
                'status' => '1'
            ];

            $result = $this->PM_pricing_sheet_model->add_new_cost($newdata);

            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
                return redirect()->to(base_url() . 'Project_Manage/PM_pricing_sheet/edit_pricing_sheet');
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'Project_Manage/PM_pricing_sheet/edit_pricing_sheet');
            }
        }
    }
    public function status_update_pricing_sheet()
    {
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();
            if (isset($_POST['ppid'])) {
                $data['ppid'] = $this->request->getVar('ppid');;
                $_SESSION['ppid'] =  $data['ppid'];
            } else if (isset($_SESSION['ppid'])) {
                $data['ppid'] = $_SESSION['ppid'];
            } else {
                $data['ppid'] = session()->get('ppid');
            }
            $newdata = [
                'pricing_value' => $this->request->getVar('pricing_value'),
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user'),
                'status' => $this->request->getVar('status')
            ];

            $result = $this->PM_pricing_sheet_model->update_pricing_sheet($newdata, $data['ppid']);

            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0008'));
                return redirect()->to(base_url() . 'Project_Manage/PM_pricing_sheet/edit_pricing_sheet');
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'Project_Manage/PM_pricing_sheet/edit_pricing_sheet');
            }
        }
    }
    public function delete_pricing_data()
    {
        $data = [];
        if (isset($_POST['ppid'])) {
            $data['ppid'] = $this->request->getVar('ppid');;
            $_SESSION['ppid'] =  $data['ppid'];
        } else if (isset($_SESSION['ppid'])) {
            $data['ppid'] = $_SESSION['ppid'];
        } else {
            $data['ppid'] = session()->get('ppid');
        }
        if ($this->request->getPost()) {
            $timestamp = time();
            $bidc = $this->request->getVar('bidc');
            $newdata = [
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user'),
                'status' => '0'
            ];

            $result = $this->PM_pricing_sheet_model->delete_cost($newdata, $bidc);

            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0005'));
                return redirect()->to(base_url() . 'Project_Manage/PM_pricing_sheet/edit_pricing_sheet');
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'Project_Manage/PM_pricing_sheet/edit_pricing_sheet');
            }
        }
    }
    function export_pricing_sheet()
    {
        $dompdf = new Dompdf();
        $data = [];
        if ($this->request->getPost('ppid')) {
            $data['ppid'] = $this->request->getPost('ppid');
            $data['get_pricing_sheet_data'] = $this->PM_pricing_sheet_model->get_pricing_sheet_data($data['ppid']);
            // print_r( $data['get_pricing_sheet_data']);
            // exit();
            $data['department_list'] = $this->PM_pricing_sheet_model->get_department(1);
            $data['get_pricing_sheet_details'] = $this->PM_pricing_sheet_model->get_pricing_sheet_details($data['ppid']);

            $html = view('project_management/export_pricing_sheet_view', $data);
            $dompdf->loadHtml($html);

            // Set paper size and orientation
            $dompdf->setPaper('A4', 'landscape');

            $date_today = date('Y-m-d');
            $dompdf->render();
            $dompdf->stream($data['ppid'] . '_' . $date_today . '_' . $data['get_pricing_sheet_data'][0]['client'] . '_pricing_sheet.pdf', ['Attachment' => true]);
        } else {
            return redirect()->to(base_url() . 'Project_Manage/PM_pricing_sheet');
        }
    }
    function updatelockstatus()
    {
        if (isset($_POST)) {
            $ppid = $_POST['ppid'];
            $newdata = [
                'status' => $_POST['status'],
                'ppid' => $_POST['ppid'],
                'last_updated_by' =>  session()->get('id_user'),
                'last_updated_on' => time(),
            ];
        }
        $result = $this->PM_pricing_sheet_model->updatelockstatus($newdata, $ppid);
        if ($result) {
            echo json_encode($result);
        }
    }
}
