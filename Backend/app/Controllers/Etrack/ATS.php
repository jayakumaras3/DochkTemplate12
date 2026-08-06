<?php

namespace App\Controllers\Etrack;

use App\Controllers\BaseController;
use App\Models\Etrack\ATS_model;

#[\AllowDynamicProperties]
class ATS extends BaseController
{
    public function __construct()
    {
        $this->is_session_available();
        $this->ATS_model = new ATS_model();
    }

    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url('my_training'));
            exit();
        }
        $arrayuserlevel = explode(',', $userlevel);

        if (!in_array('2015', $arrayuserlevel)) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }

    public function index() //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        $client = session()->get('client');
        $user = session()->get('id_user');
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url('my_training'));
            exit();
        }
        $arrayuserlevel = explode(',', $userlevel);
        $type_access = 1;
        if (in_array('2010', $arrayuserlevel)) {
            $type_access = 2;
        }
        $data['type_access'] = $type_access;
        $data['active_ats'] = $this->ATS_model->get_active_ats($client, $type_access, $user);
        echo view('templates/header_view', $data);
        echo view('etrack/ats/ats_dashboard', $data);
        echo view('templates/footer_view');
    }

    public function new_ats_request()
    {
        $data = [];
        helper(['form']);
        $data['roles'] = $this->ATS_model->get_roles();
        echo view('templates/header_view', $data);
        echo view('etrack/ats/ats_create_new_request', $data);
        echo view('templates/footer_view');
    }

    public function edit_ats()
    {
        $data = [];
        helper(['form']);
        $ats_id = $_POST['ats_id'];
        $data['roles'] = $this->ATS_model->get_roles();
        $data['ats_details'] = $this->ATS_model->get_ats_details($ats_id);
        echo view('templates/header_view', $data);
        echo view('etrack/ats/ats_edit_ats_request', $data);
        echo view('templates/footer_view');
    }

    public function view_details()
    {
        $data = [];
        helper(['form']);

        if (isset($_POST['ats_id'])) {
            $data['ats_id'] = $_POST['ats_id'];
            $_SESSION['ats_id'] =  $data['ats_id'];
        } else if (isset($_SESSION['ats_id'])) {
            $data['ats_id'] = $_SESSION['ats_id'];
        } else {
            return redirect()->to(base_url() . 'etrack/ATS');
        }

        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url('my_training'));
            exit();
        }
        $arrayuserlevel = explode(',', $userlevel);
        $type_access = 1;
        if (in_array('2010', $arrayuserlevel)) {
            $type_access = 2;
        }
        $data['type_access'] = $type_access;
        $data['ats_details'] = $this->ATS_model->get_ats_details($data['ats_id']);
        $data['ats_history'] = $this->ATS_model->get_ats_history($data['ats_id']);
        echo view('templates/header_view', $data);
        echo view('etrack/ats/ats_details', $data);
        echo view('templates/footer_view');
    }

    public function add_new_ats_request()
    {
        $newdata = [
            'resource_type' => $_POST['resource_type'],
            'requirement_details' => $_POST['requirement_details'],
            'job_description' => $_POST['job_description'],
            'min_experience' => $_POST['min_experience'],
            'max_experience' => $_POST['max_experience'],
            'type_of_position' => $_POST['type_of_position'],
            'requested_by' => session()->get('id_user'),
            'requested_on' => time(),
            'client_id' => session()->get('client'),
            'status' => 7
        ];
        $insertid = $this->ATS_model->add_new_ats_request($newdata);
        session()->setFlashdata('success', lang('Messages.Success_0026'));
        return redirect()->to(base_url() . 'etrack/ATS');
    }

    public function update_ats_details()
    {
        $ats_id = $_POST['ats_id'];
        $newdata = [
            'resource_type' => $_POST['resource_type'],
            'requirement_details' => $_POST['requirement_details'],
            'job_description' => $_POST['job_description'],
            'min_experience' => $_POST['min_experience'],
            'max_experience' => $_POST['max_experience'],
            'type_of_position' => $_POST['type_of_position'],
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $this->ATS_model->update_ats($newdata, $ats_id);
        $newdata = [
            'ats_id' => $_POST['ats_id'],
            'remarks' => "Requested edited the request.",
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => 1
        ];
        $this->ATS_model->add_new_ats_history($newdata);
        session()->setFlashdata('success', lang('Messages.Success_0008'));
        return redirect()->to(base_url() . 'etrack/ATS/view_details');
    }

    public function add_ats_history()
    {
        if (strlen($_POST['remarks']) > 2) {
            $newdata = [
                'ats_id' => $_POST['ats_id'],
                'remarks' => $_POST['remarks'],
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
                'status' => 1
            ];
            $this->ATS_model->add_new_ats_history($newdata);
        }

        $various_status = array('', 'Active', 'Sourcing', 'Interviewing', 'Offered', 'Accepted', 'Joined', 'Edit', 'Hold', '', 'Closed', 'Rejected');

        $current_status = $_POST['current_status'];
        $new_status = $_POST['new_status'];

        if ($current_status != $new_status) {
            $currentstatvalue =  $various_status[$current_status];
            $newstatvalue = $various_status[$new_status];
            $newdata = [
                'ats_id' => $_POST['ats_id'],
                'remarks' => 'Status changed from ' . $currentstatvalue . ' to ' . $newstatvalue,
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
                'status' => 1
            ];
            $this->ATS_model->add_new_ats_history($newdata);

            $newdata = [
                'status' => $_POST['new_status'],
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time()
            ];
            $this->ATS_model->update_ats($newdata, $_POST['ats_id']);
        }

        session()->setFlashdata('success', lang('Messages.Success_0008'));
        return redirect()->to(base_url() . 'etrack/ATS/view_details');
    }
    public function assign_hr()
    {
        $newdata = [
            'assigned_hr' => $_POST['assigned_hr'],
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];
        $this->ATS_model->update_ats($newdata, $_POST['ats_id']);
        $newdata = [
            'ats_id' => $_POST['ats_id'],
            'remarks' => "HR Assigned.",
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => 1
        ];
        $this->ATS_model->add_new_ats_history($newdata);
        session()->setFlashdata('success', lang('Messages.Success_0008'));
        return redirect()->to(base_url() . 'etrack/ATS/view_details');
    }
    public function add_fin_app()
    {
        $newdata = [
            'fin_approver' => $_POST['finance_approver'],
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];
        $this->ATS_model->update_ats($newdata, $_POST['ats_id']);
        $newdata = [
            'ats_id' => $_POST['ats_id'],
            'remarks' => "Finance approver added.",
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => 1
        ];
        $this->ATS_model->add_new_ats_history($newdata);
        session()->setFlashdata('success', lang('Messages.Success_0008'));
        return redirect()->to(base_url() . 'etrack/ATS/view_details');
    }

    public function add_level2_app()
    {
        $newdata = [
            'level2_approver' => $_POST['level2_approver'],
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];
        $this->ATS_model->update_ats($newdata, $_POST['ats_id']);
        $newdata = [
            'ats_id' => $_POST['ats_id'],
            'remarks' => "Level 2 approver added.",
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => 1
        ];
        $this->ATS_model->add_new_ats_history($newdata);
        session()->setFlashdata('success', lang('Messages.Success_0008'));
        return redirect()->to(base_url() . 'etrack/ATS/view_details');
    }

    public function finance_approval()
    {
        $stat = 'Rejected';
        $newdata = [
            'fin_remark' => $_POST['fin_remark'],
            'fin_approve' => $_POST['fin_approve'],
            'fin_approve_on' => time(),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];
        $this->ATS_model->update_ats($newdata, $_POST['ats_id']);
        if ($_POST['fin_approve'] == 1) {
            $stat = 'Approved';
        }
        $newdata = [
            'ats_id' => $_POST['ats_id'],
            'remarks' => 'Finance approver changed status. - ' . $stat,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => 1
        ];
        $this->ATS_model->add_new_ats_history($newdata);
        session()->setFlashdata('success', lang('Messages.Success_0008'));
        return redirect()->to(base_url() . 'etrack/ATS');
    }

    public function level2_approval()
    {
        $stat = 'Rejected';
        $newdata = [
            'remark_level2' => $_POST['remark_level2'],
            'level2_approve' => $_POST['level2_approve'],
            'level2_approve_on' => time(),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];
        $this->ATS_model->update_ats($newdata, $_POST['ats_id']);
        if ($_POST['level2_approve'] == 1) {
            $stat = 'Approved';
        }
        $newdata = [
            'ats_id' => $_POST['ats_id'],
            'remarks' => 'Level 2 approver changed status. - ' . $stat,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => 1
        ];
        $this->ATS_model->add_new_ats_history($newdata);
        session()->setFlashdata('success', lang('Messages.Success_0008'));
        return redirect()->to(base_url() . 'etrack/ATS');
    }

    public function submit_for_processing()
    {

        $newdata = [
            'status' => 1,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];
        $this->ATS_model->update_ats($newdata, $_POST['ats_id']);

        $newdata = [
            'ats_id' => $_POST['ats_id'],
            'remarks' => 'ATS Requested submitted.',
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => 1
        ];
        $this->ATS_model->add_new_ats_history($newdata);
        session()->setFlashdata('success', lang('Messages.Success_0008'));
        return redirect()->to(base_url() . 'etrack/ATS');
    }
}
