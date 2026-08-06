<?php

namespace App\Controllers\Project;

use App\Controllers\BaseController;

use App\Models\Project\Baseline_model;
#[\AllowDynamicProperties]

class Baseline extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->baseline_model = new Baseline_model();
    }
    public function index() //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        $data = [
            'link1' => 'my_training',
            'link1_name' => 'Dashboard',
            'link2' => '',
            'link2_name' => '',
            'link3_name' => 'Baseline'
        ];
        $data['header'] = 'Baseline';
        $data['form_link1'] = 'Project/baseline/addbaseline';
        $data['edit_link'] = 'Project/baseline/editbaseline';
        $data['baseline'] = $this->baseline_model->getAllBaseline();
        echo view('templates/header_view', $data);
        echo view('settings/settings_left_menu', $data);
        echo view('projects/baseline/index', $data);
        echo view('templates/footer_view');
    }
    function addbaseline()
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'Baseline';
        echo view('templates/header_view', $data);
        echo view('projects/baseline/baseline_add_view', $data);
        echo view('templates/footer_view');
    }

    function addbaselinevalues()
    {
        $data = [];
        helper(['form']);
        if ($this->request->getPost()) {
            $total = $this->request->getVar('ID');
            $total =  $this->request->getVar('CE') + $total;
            $total =  $this->request->getVar('Viz') + $total;
            $total =  $this->request->getVar('SME') + $total;
            $total =  $this->request->getVar('VD') + $total;
            $total =  $this->request->getVar('flash') + $total;
            $total =  $this->request->getVar('3D') + $total;
            $total =  $this->request->getVar('PP') + $total;
            $total =  $this->request->getVar('QA') + $total;
            $total =  $this->request->getVar('Prog') + $total;
            $total =  $this->request->getVar('Unity') + $total;
            $total =   $this->request->getVar('Articulate') + $total;
            $total =  $this->request->getVar('PMO') + $total;
            $newdata = [
                'description' => $this->request->getVar('description'),
                'duration' => $this->request->getVar('duration'),
                'ID' => $this->request->getVar('ID'),
                'CE' => $this->request->getVar('CE'),
                'Viz' => $this->request->getVar('Viz'),
                'SME' => $this->request->getVar('SME'),
                'VD' => $this->request->getVar('VD'),
                'flash' => $this->request->getVar('flash'),
                '3D' => $this->request->getVar('3D'),
                'PP' => $this->request->getVar('PP'),
                'QA' => $this->request->getVar('QA'),
                'Prog' => $this->request->getVar('Prog'),
                'Unity' => $this->request->getVar('Unity'),
                'Articulate' => $this->request->getVar('Articulate'),
                'PMO' => $this->request->getVar('PMO'),
                'status' => 1,
                'Total' => $total,
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
            ];
            $result = $this->baseline_model->addbaselineValue($newdata);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }
        }
        return redirect()->to(base_url('Project/baseline'));
    }
    function editbaseline()
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'Baseline';
        if ($this->request->getPost()) {
            $bid = $this->request->getVar('bid');
            $data['baselineVal'] = $this->baseline_model->getBaselineValues($bid);
            echo view('templates/header_view', $data);
            echo view('projects/baseline/baseline_edit_view', $data);
            echo view('templates/footer_view');
        } else {
            return redirect()->to(base_url('Project/baseline'));
        }
    }

    function updatebaselinevalues()
    {
        $data = [];
        helper(['form']);
        if ($this->request->getPost()) {
            $bid = $this->request->getVar('bid');
            $total = $this->request->getVar('ID');
            $total =  $this->request->getVar('CE') + $total;
            $total =  $this->request->getVar('Viz') + $total;
            $total =  $this->request->getVar('SME') + $total;
            $total =  $this->request->getVar('VD') + $total;
            $total =  $this->request->getVar('flash') + $total;
            $total =  $this->request->getVar('3D') + $total;
            $total =  $this->request->getVar('PP') + $total;
            $total =  $this->request->getVar('QA') + $total;
            $total =  $this->request->getVar('Prog') + $total;
            $total =  $this->request->getVar('Unity') + $total;
            $total =   $this->request->getVar('Articulate') + $total;
            $total =  $this->request->getVar('PMO') + $total;
            $newdata = [
                'description' => $this->request->getVar('description'),
                'duration' => $this->request->getVar('duration'),
                'ID' => $this->request->getVar('ID'),
                'CE' => $this->request->getVar('CE'),
                'Viz' => $this->request->getVar('Viz'),
                'SME' => $this->request->getVar('SME'),
                'VD' => $this->request->getVar('VD'),
                'flash' => $this->request->getVar('flash'),
                '3D' => $this->request->getVar('3D'),
                'PP' => $this->request->getVar('PP'),
                'QA' => $this->request->getVar('QA'),
                'Prog' => $this->request->getVar('Prog'),
                'Unity' => $this->request->getVar('Unity'),
                'Articulate' => $this->request->getVar('Articulate'),
                'PMO' => $this->request->getVar('PMO'),
                'status' => 1,
                'Total' => $total,
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
            ];
            $result = $this->baseline_model->updatebaselineValue($newdata, $bid);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0008'));
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }
        }
        return redirect()->to(base_url('Project/baseline'));
    }
}
