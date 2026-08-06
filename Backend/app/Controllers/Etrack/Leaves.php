<?php

namespace App\Controllers\Etrack;

use App\Controllers\BaseController;
use App\Models\Etrack\Leave_model;

#[\AllowDynamicProperties]
class Leaves extends BaseController
{
    public function __construct()
    {
        $this->is_session_available();
        $this->Leave_model = new Leave_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url('my_training'));
            exit();
        }
    }
    public function index() //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        $user = session()->get('id_user');

        $year = date('Y');
        $start_year = $year . '-01-01';
        $end_year = $year . '-12-31';
        //Work From Home
        // $data['workfromhome'] = $this->Leave_model->getLeaveDataByUser(1, $user, $start_year, $end_year);
        //Earned Leaves
        $data['Earned_Leaves'] = $this->Leave_model->getLeaveDataByUser(2, $user, $start_year, $end_year);
        //Medical Leaves
        $data['Medical_Leaves'] = $this->Leave_model->getLeaveDataByUser(3, $user, $start_year, $end_year);
        //Restriced Leaves
        $data['Restriced_Leaves'] = $this->Leave_model->getLeaveDataByUser(4, $user, $start_year, $end_year);
        //Paternity Leaves
        $data['Paternity_Leaves'] = $this->Leave_model->getLeaveDataByUser(5, $user, $start_year, $end_year);
        //Compoff Leaves
        $data['Compoff_Leaves'] = $this->Leave_model->getLeaveDataByUser(6, $user, $start_year, $end_year);

        //Start of Materinity Leaves calculations

        $data['gender_value'] = $this->Leave_model->getUserGender($user);
        $data['gender'] = $data['gender_value'][0]['gender'];

        if ($data['gender'] == 1) {
            $date = new \DateTime('now');
            $date->modify('last day of this month');
            $endofMonth = $date->format('Y-m-d');
            $startofMonth = date('Y-m-01');
            $data['getMensuralLeaves'] = $this->Leave_model->getMensuralLeaves($user, $startofMonth, $endofMonth);
            $data['Menasural_Leaves_Taken'] = $data['getMensuralLeaves'][0]['Mensural_Leaves'];
        }

        //End of Materinity Leaves calculations

        echo view('templates/header_view');
        echo view('etrack/Leaves/leaves_view', $data);
        echo view('templates/footer_view');
    }

    public function statement()
    {
        $data = [];
        helper(['form']);
        $user = session()->get('id_user');
        //  $year = date('Y');// 

        if (isset($_POST['year']) && $_POST['year'] != '') {
            $data['year'] = $_POST['year'];
            $year = $_POST['year'];
        } else {
            $data['year'] = date('Y');
            $year = date('Y');
        }


        $start_year = $year . '-01-01';
        $end_year = $year . '-12-31';
        $data['leave_statement'] = $this->Leave_model->leave_statement($user, $start_year, $end_year);
        echo view('templates/header_view');
        echo view('etrack/Leaves/leave_statement_view', $data);
        echo view('templates/footer_view');
    }

    public function team_single_user_statement()
    {
        $data = [];
        helper(['form']);
        //   $user = $_POST['user_id'];
        if (isset($_POST['user_id']) && $_POST['user_id'] != '') {
            $_SESSION['tempuser'] = $_POST['user_id'];
            $user = $_POST['user_id'];
        }
        if (isset($_SESSION['tempuser']) && $_SESSION['tempuser'] != '') {
            $user = $_SESSION['tempuser'];
        } else {
            return redirect()->to(base_url() . 'etrack/leaveadmin');
        }

        if (isset($_POST['year']) && $_POST['year'] != '') {
            $data['year'] = $_POST['year'];
            $year = $_POST['year'];
        } else {
            $data['year'] = date('Y');
            $year = date('Y');
        }
        $start_year = $year . '-01-01';
        $end_year = $year . '-12-31';
        $data['user_details'] = $this->Leave_model->getUserName($user);
        $data['leave_statement'] = $this->Leave_model->leave_statement($user, $start_year, $end_year);
        echo view('templates/header_view');
        echo view('etrack/Leaves/leave_team_statement_view', $data);
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
    public function team_lead_apply_leaves()
    {

        if ($_POST['numofLeaves'] == '') {
            session()->setFlashdata('error', lang('Messages.Error_0014'));
            return redirect()->to(base_url() . 'etrack/leaves/team_single_user_leaves');
        }
        if ($_POST['numofLeaves'] < .5) {
            session()->setFlashdata('error', lang('Messages.Error_0014'));
            return redirect()->to(base_url() . 'etrack/leaves/team_single_user_leaves');
        }




        if ($_POST['typeofLeave'] == 1) {
            $newdata = [
                'emp_id' => $_POST['user_select'],
                'number_wfh' => $_POST['numofLeaves'],
                'start_date' => $_POST['start_date'],
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
                'status' => 1
            ];
            $this->Attendance_model->add_wfh_data($newdata);
            session()->setFlashdata('success', lang('Messages.Success_0025'));
        } else {
            $expiry = date('Y') . '-12-31';
            $total_leaves = $_POST['numofLeaves'];
            $numleaves = $_POST['numofLeaves'] * -1;
            if ($_POST['numofLeaves'] > 1) {
                for ($i = 0; $i < $total_leaves; $i++) {
                    $date = $this->add_work_days($_POST['start_date'], $i);
                    $new_date = $date->format('Y-m-d');
                    $leave = -1;
                    $newdata = [
                        'emp_id' => $_POST['user_select'],
                        'number_leave' => $leave,
                        'start_dt' => $new_date,
                        'expire_on' => $expiry,
                        'remarks' => $_POST['remarks'],
                        'type' => $_POST['typeofLeave'],
                        'last_updated_by' => session()->get('id_user'),
                        'last_updated_on' => time(),
                        'status' => 1
                    ];
                    $this->Leave_model->add_leave_data($newdata);
                }
            } else {
                $newdata = [
                    'emp_id' => $_POST['user_select'],
                    'number_leave' => $numleaves,
                    'start_dt' => $_POST['start_date'],
                    'remarks' => $_POST['remarks'],
                    'expire_on' => $expiry,
                    'type' => $_POST['typeofLeave'],
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                    'status' => 1
                ];
                $this->Leave_model->add_leave_data($newdata);
            }
            session()->setFlashdata('success', lang('Messages.Success_0008'));
        }
        return redirect()->to(base_url() . 'etrack/leaves/team_single_user_leaves');
    }

    public function apply_leaves()
    {
        if ($_POST['numofLeaves'] < .5) {
            session()->setFlashdata('error', lang('Messages.Error_0015'));
            return redirect()->to(base_url() . 'etrack/leaves');
        }
        if ($_POST['typeofLeave'] == 2) {
            if ($_POST['earned_balance'] < $_POST['numofLeaves']) {
                session()->setFlashdata('error', lang('Messages.Error_0016'));
                return redirect()->to(base_url() . 'etrack/leaves');
            }
        } elseif ($_POST['typeofLeave'] == 3) {
            if ($_POST['medical_balance'] < $_POST['numofLeaves']) {
                session()->setFlashdata('error', lang('Messages.Error_0016'));
                return redirect()->to(base_url() . 'etrack/leaves');
            }
        } elseif ($_POST['typeofLeave'] == 4) {
            if ($_POST['restriced_balance'] < $_POST['numofLeaves']) {
                session()->setFlashdata('error', lang('Messages.Error_0016'));
                return redirect()->to(base_url() . 'etrack/leaves');
            }
        } elseif ($_POST['typeofLeave'] == 5) {
            if ($_POST['paternity_balance'] < $_POST['numofLeaves']) {
                session()->setFlashdata('error', lang('Messages.Error_0016'));
                return redirect()->to(base_url() . 'etrack/leaves');
            }
        } elseif ($_POST['typeofLeave'] == 6) {
            if ($_POST['compoff_balance'] < $_POST['numofLeaves']) {
                session()->setFlashdata('error', lang('Messages.Error_0016'));
                return redirect()->to(base_url() . 'etrack/leaves');
            }
        }

        //Mensural leave validataion

        if ($_POST['typeofLeave'] == 7) {
            if ($_POST['numofLeaves'] < 1) {
                session()->setFlashdata('error', lang('Messages.Error_0017'));
                return redirect()->to(base_url() . 'etrack/leaves');
            }
            $startofMonth = date('Y-m-01');
            if ($_POST['start_date'] < $startofMonth) {
                session()->setFlashdata('error', lang('Messages.Error_0018'));
                return redirect()->to(base_url() . 'etrack/leaves');
            }
            $date = new \DateTime('now');
            $date->modify('last day of this month');
            $endofMonth = $date->format('Y-m-d');
            if ($_POST['start_date'] > $endofMonth) {
                session()->setFlashdata('error', lang('Messages.Error_0018'));
                return redirect()->to(base_url() . 'etrack/leaves');
            }

            if ($_POST['numofLeaves'] > 1) {
                session()->setFlashdata('error', lang('Messages.Error_0017'));
                return redirect()->to(base_url() . 'etrack/leaves');
            }
        }
        //end of Mensural leave validation


        $dxt = $_POST['start_date'];
        if (strlen($dxt) < 6) {
            session()->setFlashdata('error', 'Date is mandatory.');
            return redirect()->to(base_url() . 'etrack/leaves');
        }

        $date = date("Y/m/d");
        $exceed_date = date('Y-m-d', strtotime($date . ' + 40 days'));
        if ($_POST['start_date'] > $exceed_date) {
            session()->setFlashdata('error', lang('Messages.Error_0019'));
            return redirect()->to(base_url() . 'etrack/leaves');
        }

        $nextyear = date('Y', strtotime('+1 year'));
        $nextyear_dt = $nextyear . '-01-01';
        if ($_POST['start_date'] > $nextyear_dt) {
            session()->setFlashdata('error', lang('Messages.Error_0020'));
            return redirect()->to(base_url() . 'etrack/leaves');
        }

        $nextyear = date('Y');
        $nextyear_dt = $nextyear . '-01-01';
        if ($_POST['start_date'] < $nextyear_dt) {
            session()->setFlashdata('error', lang('Messages.Error_0021'));
            return redirect()->to(base_url() . 'etrack/leaves');
        }

        $total_leaves = $_POST['numofLeaves'];
        $numleaves = $_POST['numofLeaves'] * -1;

        if ($_POST['numofLeaves'] > 1) {
            for ($i = 0; $i < $total_leaves; $i++) {
                $date = $this->add_work_days($_POST['start_date'], $i);
                $new_date = $date->format('Y-m-d');
                $leave = -1;
                $newdata = [
                    'emp_id' => session()->get('id_user'),
                    'number_leave' => $leave,
                    'start_dt' => $new_date,
                    'remarks' => $_POST['remarks'],
                    'type' => $_POST['typeofLeave'],
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                    'status' => 1
                ];
                $this->Leave_model->add_leave_data($newdata);
            }
        } else {
            $newdata = [
                'emp_id' => session()->get('id_user'),
                'number_leave' => $numleaves,
                'start_dt' => $_POST['start_date'],
                'remarks' => $_POST['remarks'],
                'type' => $_POST['typeofLeave'],
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
                'status' => 1
            ];
            $this->Leave_model->add_leave_data($newdata);
        }



        session()->setFlashdata('success', lang('Messages.Success_0031'));
        return redirect()->to(base_url() . 'etrack/leaves');
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
        return redirect()->to(base_url() . 'etrack/leaves/statement');
    }

    public function approve_compoff()
    {
        $leaveid = $_POST['leaveid'];

        $effectiveDate = strtotime("+3 months", time()); // returns timestamp
        $expire_on = date('Y-m-d', $effectiveDate);

        $newdata = [
            'approved_by' => session()->get('id_user'),
            'approved_on' => time(),
            'start_dt' => date('Y-m-d'),
            'expire_on' => $expire_on,
            'status' => 1
        ];

        $this->Leave_model->update_leave_data($newdata, $leaveid);
        session()->setFlashdata('success', lang('Messages.Success_0032'));
        return redirect()->to(base_url() . 'etrack/leaves/team_leaves');
    }
    public function reject_compoff()
    {
        $leaveid = $_POST['leaveid'];
        $newdata = [
            'approved_by' => session()->get('id_user'),
            'approved_on' => time(),
            'status' => 4
        ];
        $this->Leave_model->update_leave_data($newdata, $leaveid);
        session()->setFlashdata('success', lang('Messages.Success_0033'));
        return redirect()->to(base_url() . 'etrack/leaves/team_leaves');
    }
    public function team_leaves()
    {
        $data = [];
        if (session()->get('report_to_you') == 2) {
            $start_of_year = date('Y-01-01');
            $end_of_year = date('Y-12-31');
            $user = session()->get('id_user');
            $data['user_id'] = $user;
            $data['getcompoffApproval'] = $this->Leave_model->compoffApproval($user);
            $data['getetLeavesData'] = $this->Leave_model->getetLeavesDatabyTeam($user, $start_of_year, $end_of_year);
            echo view('templates/header_view');
            echo view('etrack/Leaves/leave_team_lead_view', $data);
            echo view('templates/footer_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            return redirect()->to(base_url() . 'etrack/leaves');
        }
    }


    public function team_single_user_leaves()
    {
        $data = [];

        if (isset($_POST['user_id'])) {
            $data['temp_user'] = $_POST['user_id'];
            $_SESSION['temp_user'] = $data['temp_user'];
        } else if (isset($_SESSION['temp_user'])) {
            $data['temp_user'] = $_SESSION['temp_user'];
        } else {
            return redirect()->to(base_url() . 'etrack/leaves/team_leaves');
        }
        $user = $data['temp_user'];
        $year = date('Y');
        $start_year = $year . '-01-01';
        $end_year = $year . '-12-31';

        $data['user_name'] = $this->Leave_model->getUserName($user);

        $data['Earned_Leaves'] = $this->Leave_model->getLeaveDataByUser(2, $user, $start_year, $end_year);
        //Medical Leaves
        $data['Medical_Leaves'] = $this->Leave_model->getLeaveDataByUser(3, $user, $start_year, $end_year);
        //Restriced Leaves
        $data['Restriced_Leaves'] = $this->Leave_model->getLeaveDataByUser(4, $user, $start_year, $end_year);
        //Paternity Leaves
        $data['Paternity_Leaves'] = $this->Leave_model->getLeaveDataByUser(5, $user, $start_year, $end_year);
        //Compoff Leaves
        $data['Compoff_Leaves'] = $this->Leave_model->getLeaveDataByUser(6, $user, $start_year, $end_year);

        echo view('templates/header_view');
        echo view('etrack/Leaves/leave_team_user_view', $data);
        echo view('templates/footer_view');
    }

    public function apply_compoff()
    {
        $start_dt = date('Y-m-d');
        $newdata = [
            'emp_id' => session()->get('id_user'),
            'number_leave' => $_POST['numofLeaves'],
            'remarks' => $_POST['remarks'],
            'start_dt' => $start_dt,
            'type' => 6,
            'requested_to' => $_POST['requested_to'],
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => 3
        ];
        $this->Leave_model->add_leave_data($newdata);
        session()->setFlashdata('success', lang('Messages.Success_0034'));
        return redirect()->to(base_url() . 'etrack/leaves');
    }
}
