<?php

namespace App\Controllers\Project_Manage;

use App\Controllers\BaseController;
use App\Models\Project_Manage\PM_MileStones_model;
use App\Models\Project_Manage\PM_ucn_model;
use Dompdf\Dompdf;

#[\AllowDynamicProperties]
class MileStones extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->is_session_available();
        $this->PM_MileStones_model = new PM_MileStones_model();
        $this->PM_ucn_model = new PM_ucn_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url());
            exit();
        }

        $arrayuserlevel = explode(',', $userlevel);

        if (!in_array('4', $arrayuserlevel) && !in_array('69', $arrayuserlevel)) {

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
            'link3_name' => 'MileStones'
        ];
        $data['milestone_list'] = $this->PM_MileStones_model->get_milestone_list();
        echo view('templates/header_view', $data);
        //echo view('dashboard/projects_left_menu', $data);
        echo view('project_management/milestone_dashboard_view', $data);
        echo view('templates/footer_view');
    }
    public function milestones_summary()
    {
        //   print_r($_POST['year']);
        // exit();

        $data = [];
        helper(['form']);
        $data = [
            'link1' => 'my_training',
            'link1_name' => 'Dashboard',
            'link2' => '',
            'link2_name' => '',
            'link3_name' => 'MileStones'
        ];
        if (!empty($_POST['year'])) {
            $data['year'] = $this->request->getPost('year');
            $data['milestoneSummary_list'] = $this->PM_ucn_model->get_wip_yearlist($data['year']);
            $data['milestone_values'] = $this->PM_ucn_model->get_milestone_values($data['year']);
            $data['milestone_list'] = $this->PM_MileStones_model->get_milestone_list();
            // print_r($data['wip_list']);
            // exit();
            if (empty($data['milestone_list'])) {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
                return redirect()->to(base_url() . 'Project_Manage/PM_wip');
            }
        } else {
            $data['year'] = date("Y");
            $data['milestoneSummary_list'] = $this->PM_ucn_model->get_wip_yearlist($data['year']);

            $data['milestone_values'] = $this->PM_ucn_model->get_milestone_values($data['year']);
            $data['milestone_list'] = $this->PM_MileStones_model->get_milestone_list();
        }

        echo view('templates/header_view', $data);
        //echo view('dashboard/projects_left_menu', $data);
        echo view('project_management/milestone_summary_view', $data);
        echo view('templates/footer_view');
    }


    public function action()
    {
        $data = [];
        helper(['form']);

        if (isset($_POST['milestone_id'])) {
            $data['milestone_id'] = $_POST['milestone_id'];
            $_SESSION['milestone_id'] = $data['milestone_id'];
        } else if (isset($_SESSION['milestone_id'])) {
            $data['milestone_id'] = $_SESSION['milestone_id'];
        } else {
            session()->setFlashdata('message', 'Error loading page.');
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'Project_Manage/MileStones');
        }

        $data['milestone_details'] = $this->PM_MileStones_model->get_milestone_details($data['milestone_id']);

        echo view('templates/header_view', $data);
        echo view('project_management/milestone_edit_view', $data);
        echo view('templates/footer_view');
    }

    public function add_invoice()
    {
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();
            $milestone_id = $this->request->getVar('milestone_id');
            $poclient =  $this->PM_MileStones_model->getpoclient($milestone_id);
            if ($poclient) {
                $client_id = $poclient[0]['client_id'];
            } else {
                $client_id = 0;
            }
            // print_r($client_id);
            // exit();
            $newdata = [
                'milestone_id' => $this->request->getVar('milestone_id'),
                'percentage' => $this->request->getVar('percentage'),
                'client_id' => $client_id,
                'description' => $this->request->getVar('description'),
                'currency' => $this->request->getVar('currency'),
                'value' => $this->request->getVar('value'),
                'inv_dt' =>  $this->request->getVar('inv_dt'),
                'due_dt' =>  $this->request->getVar('due_dt'),

                'created_by' =>  session()->get('id_user'),
                'created_on' => $timestamp,
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user'),
                'status' => '1'
            ];
            $result = $this->PM_MileStones_model->add_invoice($newdata);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
                return redirect()->to(base_url() . 'Project_Manage/MileStones/action');
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'Project_Manage/MileStones/action');
            }
        }
    }

    public function edit_invoice()
    {
        $data = [];
        helper(['form']);

        if (isset($_POST['invoice_id'])) {
            $data['invoice_id'] = $_POST['invoice_id'];
            $_SESSION['invoice_id'] = $data['invoice_id'];
        } else if (isset($_SESSION['invoice_id'])) {
            $data['invoice_id'] = $_SESSION['invoice_id'];
        } else {
            session()->setFlashdata('message', 'Error loading page.');
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'Project_Manage/MileStones/action');
        }

        $data['invoice_details'] = $this->PM_MileStones_model->get_invoice_details($data['invoice_id']);

        echo view('templates/header_view', $data);
        echo view('project_management/invoice_edit_view', $data);
        echo view('templates/footer_view');
    }

    public function update_invoice()
    {
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();
            $invoice_id = $this->request->getVar('invoice_id');
            $newdata = [
                'percentage' => $this->request->getVar('percentage'),
                'description' => $this->request->getVar('description'),
                'currency' => $this->request->getVar('currency'),
                'value' => $this->request->getVar('value'),
                'inv_dt' =>  $this->request->getVar('inv_dt'),
                'due_dt' =>  $this->request->getVar('due_dt'),
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user')
            ];
            $result = $this->PM_MileStones_model->update_invoice($newdata, $invoice_id);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0008'));
                return redirect()->to(base_url() . 'Project_Manage/MileStones/action');
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'Project_Manage/MileStones/action');
            }
        }
    }

    public function submit_invoice()
    {
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();
            $invoice_id = $this->request->getVar('invoice_id');
            $newdata = [
                'status' =>  $this->request->getVar('status'),
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user')
            ];
            $result = $this->PM_MileStones_model->update_invoice($newdata, $invoice_id);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0008'));
                return redirect()->to(base_url() . 'Project_Manage/MileStones/action');
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'Project_Manage/MileStones/action');
            }
        }
    }

    public function invoices()
    {
        $data = [];
        helper(['form']);
        $data = [
            'link1' => 'my_training',
            'link1_name' => 'Dashboard',
            'link2' => '',
            'link2_name' => '',
            'link3_name' => 'Invoices'
        ];

        $data['invoice_list'] = $this->PM_MileStones_model->get_invoice_list();
        echo view('templates/header_view', $data);
        echo view('dashboard/projects_left_menu', $data);
        echo view('project_management/invoices_dashboard_view', $data);
        echo view('templates/footer_view');
    }

    public function invoice_change_status()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['invoice_id'])) {
            $data['invoice_id'] = $_POST['invoice_id'];
            $_SESSION['invoice_id'] = $data['invoice_id'];
        } else if (isset($_SESSION['invoice_id'])) {
            $data['invoice_id'] = $_SESSION['invoice_id'];
        } else {
            session()->setFlashdata('message', 'Error loading page.');
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'Project_Manage/MileStones/invoices');
        }

        echo view('templates/header_view', $data);
        echo view('project_management/invoices_change_status', $data);
        echo view('templates/footer_view');
    }


    public function update_invoice_status()
    {
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();
            $invoice_id = $this->request->getVar('invoice_id');
            $newdata = [
                'status' => $this->request->getVar('status'),
                'notes' => $this->request->getVar('notes'),
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user')
            ];
            $result = $this->PM_MileStones_model->update_invoice($newdata, $invoice_id);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0008'));
                return redirect()->to(base_url() . 'Project_Manage/MileStones/invoices');
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'Project_Manage/MileStones/invoices');
            }
        }
    }
    function export_invoice_pdf()
    {
        $dompdf = new Dompdf();
        $data = [];
        if (isset($_POST['invoice_id'])) {
            $data['invoice_id'] = $_POST['invoice_id'];
            $_SESSION['invoice_id'] = $data['invoice_id'];
        } else if (isset($_SESSION['invoice_id'])) {
            $data['invoice_id'] = $_SESSION['invoice_id'];
        } else {
            session()->setFlashdata('message', 'Error loading page.');
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'Project_Manage/MileStones/invoices');
        }

        $data['invoice_details'] = $this->PM_MileStones_model->get_invoice_details($data['invoice_id']);
        $html = view('project_management/export_invoice_view', $data);
        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

        $date_today = date('Y-m-d');
        $dompdf->render();
        $dompdf->stream($data['invoice_id'] . '_' . $date_today . '_' . $data['invoice_details'][0]['client_id'] . 'invoice.pdf', ['Attachment' => true]);
    }
}
