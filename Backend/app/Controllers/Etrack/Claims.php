<?php

namespace App\Controllers\Etrack;

use App\Controllers\BaseController;
use App\Models\Etrack\Claim_model;
use App\Models\Project_Manage\PM_projects_model;


#[\AllowDynamicProperties]
class Claims extends BaseController
{
    public function __construct()
    {
        $this->is_session_available();
        $this->Claim_model = new Claim_model();
        $this->PM_projects_model = new PM_projects_model();
    }
    private function is_session_available()
    {
        $client = session()->get('client');
        if ($client != 1) {
            header('Location:' . base_url('my_training'));
            exit();
        }
    }

    public function index() //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        $user = session()->get('id_user');
        $data['expense_list'] = array(
            10 => 'General',
            11 => 'Common Subscriptions',
            12 => 'Travel',
            13 => 'Meals & Entertainment',
            14 => 'IT Expenses',
            15 => 'Other'
        );
        if (isset($_POST['month']) && $_POST['month'] != '') {
            $data['month'] = $_POST['month'];
            $data['year'] = $_POST['year'];
        } else {
            $data['month'] = date('m');
            $data['year'] = date('Y');
        }
        $user = session()->get('id_user');
        $userlevel = session()->get('userlevel');
        $arrayuserlevel  = array_map('intval', explode(',', $userlevel) ?? '');

        if (in_array(6, $arrayuserlevel)) {
            $data['my_claims'] = $this->Claim_model->get_all_claims_by_month($data['month'], $data['year']);
        } else {
            $data['my_claims'] = $this->Claim_model->get_my_claims_by_month($user, $data['month'], $data['year']);
        }
        // $data['my_claims'] = $this->Claim_model->get_my_claims_by_month($user, $data['month'], $data['year']);
        $data['active_ucn'] = $this->Claim_model->get_active_ucn();
        echo view('templates/header_view');
        echo view('etrack/finance/claims_view', $data);
        echo view('templates/footer_view');
    }

    public function Status()
    {
        if ($response =  $this->requireRole(['7'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $user = session()->get('id_user');
        if (!empty($_POST['year'])) {
            $data['year'] = $this->request->getPost('year');
            $data['wip_list'] = $this->PM_projects_model->get_ucn_status($data['year']);
            if (empty($data['wip_list'])) {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
                return redirect()->to(base_url() . 'Project_Manage/PM_wip');
            }
        } else {
            $data['year'] = date("Y");
            $data['wip_list'] = $this->PM_projects_model->get_ucn_status($data['year']);
        }

        echo view('templates/header_view', $data);
        echo view('project_management/projects_status_view', $data);
        echo view('templates/footer_view');
    }

    public function edit_claim()
    {
        $data = [];
        helper(['form']);

        if (isset($_POST['vd_id']) && $_POST['vd_id'] != '') {
            $vd_id = $_POST['vd_id'];
            $_SESSION['vd_id'] = $vd_id;
        } elseif (isset($_SESSION['vd_id']) && $_SESSION['vd_id'] != '') {
            $vd_id = $_SESSION['vd_id'];
        } else {
            session()->setFlashdata('success', 'Claim not found.');
            return redirect()->to(base_url() . 'Etrack/claims');
        }
        //$vd_id = $_POST['vd_id'];
        /// $data['all_vendors'] = $this->Claim_model->get_vendors();
        $data['expense_list'] = array(
            10 => 'General',
            11 => 'Common Subscriptions',
            12 => 'Travel',
            13 => 'Meals & Entertainment',
            14 => 'IT Expenses',
            15 => 'Other'
        );
        $data['active_ucn'] = $this->Claim_model->get_active_ucn();
        $data['vendor_payment_details'] = $this->Claim_model->get_claim_by_ID($vd_id);
        $data['claim_history'] = $this->Claim_model->get_claim_history_by_ID($vd_id);

        echo view('templates/header_view');
        echo view('etrack/finance/claims_edit', $data);
        echo view('templates/footer_view');
    }

    public function add_new_claim()
    {

        $exchangerate = 94;
        if ($_POST['currency'] == 1) {
            $usd = $_POST['amount'];
            $inr = $_POST['amount'] * $exchangerate;
        } else {
            $inr = $_POST['amount'];
            $usd = round($_POST['amount'] / $exchangerate);
        }
        $newdata = [
            'vendor_name' => $_POST['vendor_name'],
            'description' => $_POST['description'],
            'claim_amount_inr' => $inr,
            'claim_amount_usd' =>  $usd,
            'mode' => $_POST['payment_mode'],
            'expense_head' => $_POST['expense_head'],
            'primary_currency' => $_POST['currency'],
            'exchange_rate' =>  $exchangerate,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'requested_by' => session()->get('id_user'),
            'requested_on' => $_POST['claim_date'],
            'status' => 1
        ];
        $claim_id = $this->Claim_model->add_claim($newdata);

        $history_data = [
            'claim_id' => $claim_id,
            'description' => 'New claim added.',
            'notes' => '',
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];
        $this->Claim_model->add_claim_history($history_data);
        $_SESSION['vd_id'] = $claim_id;
        session()->setFlashdata('success', 'New claim added.');
        return redirect()->to(base_url() . 'Etrack/claims/edit_claim');
    }

    public function update_claim()
    {
        $vd_id = $_POST['vd_id'];
        $exchangerate = $_POST['exchange_rate'];
        if ($_POST['currency'] == 1) {
            $usd = $_POST['amount'];
            $inr = $_POST['amount'] * $exchangerate;
        } else {
            $inr = $_POST['amount'];
            $usd = round($_POST['amount'] / $exchangerate);
        }
        $newdata = [
            'vendor_name' => $_POST['vendor_name'],
            'description' => $_POST['description'],
            'claim_amount_inr' => $inr,
            'claim_amount_usd' =>  $usd,
            'mode' => $_POST['payment_mode'],
            'primary_currency' => $_POST['currency'],
            'exchange_rate' =>  $exchangerate,
            'expense_head' => $_POST['expense_head'],
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'requested_by' => session()->get('id_user'),
            'requested_on' => $_POST['claim_date'],
            'status' => 1
        ];
        $this->Claim_model->update_claim($newdata, $vd_id);
        session()->setFlashdata('success', 'Claim Updated.');
        return redirect()->to(base_url() . 'Etrack/claims');
    }

    public function claim_status_change()
    {
        if (isset($_POST['vd_id']) && $_POST['vd_id'] != '') {
            $vd_id = $_POST['vd_id'];
        } else {
            session()->setFlashdata('error', 'Claim not found.');
            return redirect()->to(base_url() . 'Etrack/claims');
        }

        $status = $_POST['status'];
        $action_value = '';
        if ($status == 2) {
            $action_value = 'Claim submitted to Project Manager for Approval.';
        } elseif ($status == 4) {
            $action_value = 'Claim submitted to Pramod Chandran for Approval.';
        } elseif ($status == 6) {
            $action_value = 'Claim submitted to Shrikant for Approval.';
        } elseif ($status == 10) {
            $action_value = 'Claim Rejected.';
        } elseif ($status == 3) {
            $action_value = 'Claim Approved by Project Manager.';
        } elseif ($status == 5) {
            $action_value = 'Claim Approved by Pramod Chandran.';
        } elseif ($status == 7) {
            $action_value = 'Claim Approved by Shrikant.';
        } elseif ($status == 9) {
            $action_value = 'Claim Approved by Finance.';
        }

        $newdata = [
            'status' =>  $status,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];
        $this->Claim_model->update_claim($newdata, $vd_id);

        $history_data = [
            'claim_id' => $vd_id,
            'notes' => $_POST['notes'],
            'description' => $action_value,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];
        $this->Claim_model->add_claim_history($history_data);

        session()->setFlashdata('success', 'Claim status updated.');
        return redirect()->to(base_url() . 'Etrack/claims');
    }
    public function upload_documents()
    {
        $files = $this->request->getFiles();

        if (!isset($files['documents'])) {
            return redirect()->back()->with('error', 'No file selected.');
        }

        $uploadPath = FCPATH . 'assets/assets/uploads/cliams/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $uploadedFiles = session()->get('claim_documents') ?? [];

        foreach ($files['documents'] as $file) {

            if (!$file->isValid()) {
                continue;
            }

            if (strtolower($file->getExtension()) !== 'pdf') {
                return redirect()->back()->with('error', 'Only PDF files are allowed.');
            }

            if ($file->getSize() > (5 * 1024 * 1024)) {
                return redirect()->back()->with('error', 'File size should not exceed 5 MB.');
            }

            $originalName = $file->getClientName();

            $file->move($uploadPath, $originalName);

            $uploadedFiles[] = $originalName;
        }

        session()->set('claim_documents', $uploadedFiles);

        return redirect()->back()->with('success', 'Documents uploaded successfully.');
    }
    public function delete_document($index)
    {
        $uploadedFiles = session()->get('claim_documents') ?? [];

        if (isset($uploadedFiles[$index])) {

            $filePath = FCPATH . 'assets/assets/uploads/cliams/' . $uploadedFiles[$index];

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            unset($uploadedFiles[$index]);

            session()->set(
                'claim_documents',
                array_values($uploadedFiles)
            );
        }

        return redirect()->back()->with('success', 'Document deleted successfully.');
    }
}
