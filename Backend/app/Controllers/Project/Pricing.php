<?php

namespace App\Controllers\Project;

use App\Controllers\BaseController;
use App\Models\Project\Pricing_model;
use App\Models\Project\Baseline_model;
#[\AllowDynamicProperties]
class Pricing extends BaseController
{
    public function __construct()
    {
        $this->pricing_model = new Pricing_model();
        $this->baseline_model = new Baseline_model();
    }
    public function index() //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'Pricing';
        $data['form_link1'] = 'Project/pricing/addpricing';
        $data['edit_link'] = 'Project/pricing/editpricing';
        $data['edit_effort'] = 'Project/pricing/edit_effort';
        $data['pricing'] = $this->pricing_model->getAllPricing();
        echo view('templates/header_view', $data);
        echo view('projects/pricing/index', $data);
        echo view('templates/footer_view');
    }
    public function addpricing() //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'Pricing';
        $data['form_link1'] = 'Project/pricing/addpricing';
        $data['edit_link'] = 'Project/pricing/editpricing';
        $data['sales'] = $this->pricing_model->getAllSales();
        $data['baseline'] = $this->baseline_model->getAllBaseline();
        $data['client'] = $this->pricing_model->getAllclient();
        $data['baselinePricing'] = $this->pricing_model->baselinePricing();
        echo view('templates/header_view', $data);
        echo view('projects/pricing/pricing_add_view', $data);
        echo view('templates/footer_view');
    }
    public function editpricing() //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'Pricing';
        if (isset($_POST['ppid'])) {
            $data['ppid'] = $_POST['ppid'];
            $_SESSION['ppid'] = $data['ppid'];
        } else if (isset($_SESSION['ppid'])) {
            $data['ppid'] = $_SESSION['ppid'];
        } else {
            return redirect()->to(base_url('Project/pricing'));
        }
        $data['form_link1'] = 'Project/pricing/addpricing';
        $data['edit_link'] = 'Project/pricing/editpricing';
        $data['sales'] = $this->pricing_model->getAllSales();
        $data['baseline'] = $this->baseline_model->getAllBaseline();
        $data['client'] = $this->pricing_model->getAllclient();
        $data['pricing_value'] = $this->pricing_model->getcurrentPricingValue($data['ppid']);
        $data['baselinePricing'] = $this->pricing_model->baselinePricing();
        echo view('templates/header_view', $data);
        echo view('projects/pricing/pricing_edit_view', $data);
        echo view('templates/footer_view');
    }

    public function edit_effort() //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'Pricing';
        if (isset($_POST['ppid'])) {
            $data['ppid'] = $_POST['ppid'];
            $_SESSION['ppid'] = $data['ppid'];
        } else if (isset($_SESSION['ppid'])) {
            $data['ppid'] = $_SESSION['ppid'];
        } else {
            return redirect()->to(base_url('Project/pricing'));
        }

        $data['form_link1'] = 'Project/pricing/addpricing';
        $data['edit_link'] = 'Project/pricing/editpricing';
        $data['sales'] = $this->pricing_model->getAllSales();
        $data['baseline'] = $this->baseline_model->getAllBaseline();
        $data['client'] = $this->pricing_model->getAllclient();
        $data['pricing_value'] = $this->pricing_model->getcurrentPricingValue($data['ppid']);
        $data['baselinePricing'] = $this->pricing_model->baselinePricing();

        $data['baselineVal'] = $this->baseline_model->getBaselineValues($data['pricing_value'][0]['type']);
        $data['project_pricing_details'] = $this->pricing_model->project_pricing_details($data['pricing_value'][0]['pricing_model']);

        $data['pricing_sheet_details'] = $this->pricing_model->pricing_sheet_details($data['ppid']);

        if (!isset( $data['pricing_sheet_details'][0]['ID'])) {
            $newdata = [
                'ppid' => $data['ppid'],
                'status' => 1,
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
            ];
            $this->pricing_model->addPricingDetails($newdata);
            $data['pricing_sheet_details'] = $this->pricing_model->pricing_sheet_details($data['ppid']);
        }

        echo view('templates/header_view', $data);
        echo view('projects/pricing/pricing_edit_effort', $data);
        echo view('templates/footer_view');
    }

    public function addpricing_description()
    {
        $data = [];
        helper(['form']);
       if ($this->request->getPost()) {
            $newdata = [
                'proposal_name' => $this->request->getVar('proposal_name'),
                'client' => $this->request->getVar('client'),
                'requested_by' => $this->request->getVar('requested_by'),
                'type' => $this->request->getVar('type'),
                'pricing_model' => $this->request->getVar('pricing_model'),
                'duration' => $this->request->getVar('duration'),
                'description' => $this->request->getVar('description'),
                'requested_on' => time(),
                'status' => 1,
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
            ];
            $result = $this->pricing_model->addpricingValue($newdata);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }
        }
        return redirect()->to(base_url('Project/pricing'));
    }
    public function updatepricing_description()
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'Pricing';
        if (isset($_POST['ppid'])) {
            $data['ppid'] = $_POST['ppid'];
            $_SESSION['ppid'] = $data['ppid'];
        } else if (isset($_SESSION['ppid'])) {
            $data['ppid'] = $_SESSION['ppid'];
        } else {
            return redirect()->to(base_url('Project/pricing'));
        }
       if ($this->request->getPost()) {
            $newdata = [
                'proposal_name' => $this->request->getVar('proposal_name'),
                'client' => $this->request->getVar('client'),
                'requested_by' => $this->request->getVar('requested_by'),
                'type' => $this->request->getVar('type'),
                'pricing_model' => $this->request->getVar('pricing_model'),
                'duration' => $this->request->getVar('duration'),
                'description' => $this->request->getVar('description'),
                'status' => $this->request->getVar('status'),
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
            ];
            $result = $this->pricing_model->updatepricingValue($newdata, $data['ppid']);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0008'));
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }
        }
        return redirect()->to(base_url('Project/pricing/edit_effort'));
    }
}
