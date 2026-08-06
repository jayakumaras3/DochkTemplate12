<?php

namespace App\Controllers\Etrack;

use App\Controllers\BaseController;
use App\Models\Etrack\Leave_model;

#[\AllowDynamicProperties]
class Admin extends BaseController
{
    public function __construct()
    {
        $this->is_session_available();
        $this->Leave_model = new Leave_model();
    }
    private function is_session_available()
    {
        $client = session()->get('client');
        $userlevel = session('userlevel');
        $arrayuserlevel = array_map('intval', explode(',', $userlevel) ?? '');
        if (!in_array('4154', $arrayuserlevel)) {
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
        $data['all_users'] = $this->Leave_model->getUsersDetails($client);
        echo view('templates/header_view', data: $data);
        echo view('etrack/admin_view', $data);
        echo view('templates/footer_view');
    }

    function add_work_days($date, $day)
    {
        if (!($date instanceof \DateTime) || is_string($date)) {
            $date = new \DateTime($date);
        }

        if ($date instanceof \DateTime) {
            $newDate = clone $date;
        }

        if ($day == 0) {
            return $newDate;
        }

        $i = 1;

        while ($i <= abs($day)) {

            $newDate->modify(($day > 0 ? ' +' : ' -') . '1 day');

            $next_day_number = $newDate->format('N');

            if (!in_array($next_day_number, [6, 7])) {
                $i++;
            }
        }

        return $newDate;
    }


    public function add_leaves()
    {
        $date = $this->add_work_days($_POST['start_date'], $_POST['numofLeaves'] - 1);
        $end_date = $date->format('Y-m-d');
        $newdata = [
            'emp_id' => $_POST['user_select'],
            'number_leave' => $_POST['numofLeaves'],
            'start_dt' => $_POST['start_date'],
            'end_dt' => $end_date,
            'remarks' => $_POST['remarks'],
            'type' => $_POST['typeofLeave'],
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => 1
        ];
        $this->Leave_model->add_leave_data($newdata);
        session()->setFlashdata('success', lang('Messages.Success_0024'));
        if ($_POST['typeofLeave'] == 1) {
            $newdata = [
                'emp_id' => $_POST['user_select'],
                'number_wfh' => $_POST['numofLeaves'],
                'start_date' => $_POST['start_date'],
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
                'status' => 1
            ];
            $this->Leave_model->add_wfh_data($newdata);
            session()->setFlashdata('success', lang('Messages.Success_0025'));
        } else {
            $date = $this->add_work_days($_POST['start_date'], $_POST['numofLeaves'] - 1);
            $end_date = $date->format('Y-m-d');
            $newdata = [
                'emp_id' => $_POST['user_select'],
                'number_leave' => $_POST['numofLeaves'],
                'start_dt' => $_POST['start_date'],
                'end_dt' => $end_date,
                'remarks' => $_POST['remarks'],
                'type' => $_POST['typeofLeave'],
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
                'status' => 1
            ];
            $this->Leave_model->add_leave_data($newdata);
            session()->setFlashdata('success', lang('Messages.Success_0008'));
        }
        return redirect()->to(base_url() . 'etrack/admin');
    }

    public function user_leave_statement()
    {
        $data = [];
        helper(['form']);
        $user = $_POST['user_select'];
        //    $year = '2025';
        $year = date('Y');
        $start_year = $year . '-01-01';
        $end_year = $year . '-12-31';
        $data['leave_statement'] = $this->Leave_model->leave_statement($user, $start_year, $end_year);
        echo view('templates/header_view');
        echo view('etrack/admin_leave_statement_view', $data);
        echo view('templates/footer_view');
    }

    public function delete_leaves()
    {
        $leaveid = $_POST['leaveid'];
        $newdata = [
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => 0
        ];
        $this->Leave_model->update_leave_data($newdata, $leaveid);
        session()->setFlashdata('success', lang('Messages.Success_0005'));
        return redirect()->to(base_url() . 'etrack/admin');
    }
    public function export_etLeaves()
    {
        $data = [];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        $data['getetLeavesData'] = $this->Leave_model->getetLeavesData($start_date, $end_date);
        echo view('templates/header_view');
        echo view('etrack/admin_leave_report01_view', $data);
        echo view('templates/footer_view');
    }
}
