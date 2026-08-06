<?php

namespace App\Controllers\Etrack;

use App\Controllers\BaseController;
use App\Models\Etrack\Leave_model;
use App\Models\Etrack\Attendance_model;

#[\AllowDynamicProperties]
class Leaveadmin extends BaseController
{
    public function __construct()
    {
        $this->is_session_available();
        $this->Leave_model = new Leave_model();
        $this->Attendance_model = new Attendance_model();
    }
    private function is_session_available()
    {
        $client = session()->get('client');
        if ($client != 1) {
            header('Location:' . base_url('my_training'));
            exit();
        }
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url('my_training'));
            exit();
        }
        $arrayuserlevel = explode(',', $userlevel);
        if (!in_array('2010', $arrayuserlevel)) {
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
        echo view('templates/header_view', $data);
        echo view('etrack/Leaves/admin_view', $data);
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

        if ($_POST['numofLeaves'] == '') {
            session()->setFlashdata('error', lang('Messages.Error_0014'));
            return redirect()->to(base_url() . 'etrack/leaveadmin');
        }
        if ($_POST['numofLeaves'] < .5) {
            session()->setFlashdata('error', lang('Messages.Error_0014'));
            return redirect()->to(base_url() . 'etrack/leaveadmin');
        }
        if (strlen($_POST['start_date']) < 5) {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url() . 'etrack/leaveadmin');
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
            $newdata = [
                'emp_id' => $_POST['user_select'],
                'number_leave' => $_POST['numofLeaves'],
                'start_dt' => $_POST['start_date'],
                'expire_on' => $expiry,
                'remarks' => $_POST['remarks'],
                'type' => $_POST['typeofLeave'],
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
                'status' => 1
            ];
            $this->Leave_model->add_leave_data($newdata);
            session()->setFlashdata('success', lang('Messages.Success_0008'));
        }
        return redirect()->to(base_url() . 'etrack/leaveadmin');
    }

    public function user_leave_statement()
    {
        $data = [];
        helper(['form']);
        //$user = $_POST['user_select'];
      //  $year = date('Y');
        if(isset($_POST['user_select']) && $_POST['user_select'] != ''){
            $_SESSION['tempuser'] = $_POST['user_select'];
            $user = $_POST['user_select'];
        }if(isset($_SESSION['tempuser']) && $_SESSION['tempuser'] != ''){
            $data['user'] = $_SESSION['tempuser'];
            $user = $_SESSION['tempuser'];
        }else{ 
            return redirect()->to(base_url() . 'etrack/leaveadmin');
        }   

        if(isset($_POST['year']) && $_POST['year'] != ''){
            $data['year'] = $_POST['year'];
            $year = $_POST['year'];
        }else{ 
             $data['year'] = date('Y');
            $year = date('Y');
        }

        $start_year = $year . '-01-01';
        $end_year = $year . '-12-31';
        $data['leave_statement'] = $this->Leave_model->leave_statement($user, $start_year, $end_year);
        echo view('templates/header_view');
        echo view('etrack/Leaves/admin_leave_statement_view', $data);
        echo view('templates/footer_view');
    }

    public function import_bulk_etLeaves()
    {
        $data = [];
        helper(['form']);
        if (isset($_FILES)) {
            $rules = [
                'file' => 'uploaded[file]|ext_in[file,csv,xls,xlsx]', // 10 MB
            ];
            if (!$this->validate($rules)) {
                $data['excelvalidation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $newfilename = $file->getRandomName();
                        $a = FCPATH . '/assets/assets/uploads';
                        $file->move(FCPATH . '/assets/assets/uploads', $newfilename);
                        $filepath = FCPATH . 'assets/assets/uploads/' . $newfilename;
                        $extension = pathinfo($newfilename, PATHINFO_EXTENSION);
                        if ($extension == 'csv') {
                            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                        } elseif ($extension == 'xls') {
                            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                        } else {
                            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                        }
                        $spreadsheet = $reader->load($filepath);
                        $sheetData = $spreadsheet->getActiveSheet()->toArray();
                        $month = $_POST['month'];
                        $year = $_POST['year'];
                        // $result = $this->Finance_model->import_payslip_excel($sheetData, $month, $year);
                        $result = $this->Leave_model->import_bulk_leave_excel($sheetData, $month, $year);

                        $session = session();
                        $session->setFlashdata('success', $result . ' Rows imported');
                        return redirect()->to(base_url() . 'etrack/leaveadmin');
                    }
                }
            }
        }
        return redirect()->to(base_url() . 'etrack/leaveadmin');
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
        return redirect()->to(base_url() . 'etrack/leaveadmin');
    }
    public function export_etLeaves()
    {
        $data = [];
        // $start_date = $_POST['start_date'];
        // $end_date = $_POST['end_date'];
        if (isset($_POST['start_date'])) {
            $data['start_date'] = $_POST['start_date'];
            $_SESSION['start_date'] = $data['start_date'];
        } else if (isset($_GET['start_date'])) {
            $data['start_date'] = $_GET['start_date'];
        } else if (isset($_SESSION['start_date'])) {
            $data['start_date'] = $_SESSION['start_date'];
        }
        if (isset($_POST['end_date'])) {
            $data['end_date'] = $_POST['end_date'];
            $_SESSION['end_date'] = $data['end_date'];
        } else if (isset($_GET['end_date'])) {
            $data['end_date'] = $_GET['end_date'];
        } else if (isset($_SESSION['end_date'])) {
            $data['end_date'] = $_SESSION['end_date'];
        }
        // print_r($data['start_date']);
        // exit();
        // $data['end_date'] = $end_date;

        //   $thisyear_start = date("Y-01-01");
        //    $thisyear_enddate = date("Y-12-01");

        $data['getetLeavesData'] = $this->Leave_model->getetLeavesData($data['start_date'], $data['end_date']);
        //   $data['getetLeavesData_balance'] = $this->Leave_model->getetLeavesData($thisyear_start, $thisyear_enddate);
        echo view('templates/header_view');
        echo view('etrack/Leaves/admin_leave_report01_view', $data);
        echo view('templates/footer_view');
    }
    public function leave_balance_report()
    {
        $data = [];
        //  $start_date = $_POST['start_date'];
        // $end_date = $_POST['end_date'];
        if($_POST['year']==''){
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url() . 'etrack/leaveadmin');
        }
        $data['year'] = $_POST['year'];
        $thisyear_start = date($data['year']."-01-01");
        $thisyear_enddate = date($data['year']."-12-31");
        $data['start_date'] = $thisyear_start;
        $data['end_date'] = $thisyear_enddate;


        $data['getetLeavesData'] = $this->Leave_model->getetLeavesData($thisyear_start, $thisyear_enddate);
        // $data['getetLeavesData_balance'] = $this->Leave_model->getetLeavesData($thisyear_start, $thisyear_enddate);
        echo view('templates/header_view');
        echo view('etrack/Leaves/admin_leave_report03_view', $data);
        echo view('templates/footer_view');
    }
    public function attentance_report()
    {
        $data = [];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        $data['getetLeavesData'] = $this->Leave_model->getetLeavesData($start_date, $end_date);
        echo view('templates/header_view');
        echo view('etrack/Leaves/admin_leave_report02_view', $data);
        echo view('templates/footer_view');
    }
}
