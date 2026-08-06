<?php

namespace App\Controllers\Etrack;

use App\Controllers\BaseController;
use App\Models\Etrack\Leave_model;
use App\Models\Etrack\Attendance_model;
use App\Models\Etrack\HR_model;
use App\Models\User_login\Users_model;
use App\Models\Etrack\Employee_data_model;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;

#[\AllowDynamicProperties]
class HR_admin extends BaseController
{
    public function __construct()
    {
        $this->is_session_available();
        $this->Leave_model = new Leave_model();
        $this->Attendance_model = new Attendance_model();
        $this->HR_model = new HR_model();
        $this->users_model = new Users_model();
        $this->Employee_data_model = new Employee_data_model();
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
        echo view('etrack/hr/admin_hr_view', $data);
        echo view('templates/footer_view');
    }

    public function payroll_report()
    {
        $data = [];
        helper(['form']);
        $id_user = session()->get('id_user');
        if (isset($_POST['month'])) {
            $data['month'] = $_POST['month'];
            $data['year'] = $_POST['year'];
        } else {
            $data['month'] = date('m');
            $data['year'] = date('Y');
        }
        $year = date('Y');
        if ($data['month'] == 1) {
            $pre_month = 12;
            $pre_year = $year - 1;
        } else {
            $pre_month = $data['month'] - 1;
            $pre_year = $year;
        }

        if (strlen($pre_month) < 2) {
            $pre_month = '0' . $pre_month;
        }
        if (strlen($data['month']) < 2) {
            $this_month = '0' . $data['month'];
        } else {
            $this_month =  $data['month'];
        }

        $data['start_date'] = $pre_year . '-' . $pre_month . '-26';
        $data['end_date'] = $year . '-' . $this_month . '-25';


        $data['holidays'] = $this->Attendance_model->getholidays($data['start_date'],  $data['end_date'], 1);

        $today = date('Y-m-d');
        if ($data['end_date'] > $today) {
            $newdat = date('Y-m-d', (strtotime('-1 day', strtotime($today))));
            $data['working_days'] = $this->getWorkingDays($data['start_date'], $newdat, $data['holidays']);
        } else {
            $data['working_days'] = $this->getWorkingDays($data['start_date'], $data['end_date'], $data['holidays']);
        }

        $data['data_value'] = $this->Attendance_model->teamAttendance(1, $data['start_date'], $data['end_date']);

        $data['return_page'] = 3;
        $_SESSION['return_page'] = 3;

        echo view('templates/header_view');
        echo view('etrack/Attendance/team_attendance_view', $data);
        echo view('templates/footer_view');
    }
    public function getWorkingDays($start_date, $end_date, $holidays)
    {
        $business_days = 0;
        $current_date = strtotime($start_date);
        $end_date = strtotime($end_date);
        while ($current_date <= $end_date) {
            if (date('N', $current_date) < 6 && !in_array(date('Y-m-d', $current_date), array_column($holidays, 'holiday_dt'))) {
                $business_days++;
            }
            if ($current_date <= $end_date) {
                $current_date = strtotime('+1 day', $current_date);
            }
        }
        return $business_days;
    }



    public function apply_grase()
    {
        $data = [];
        helper(['form']);
        $data['temp_user'] = $_POST['temp_user'];
        $_SESSION['returnpage'] = 2;
        $data['get_grace'] = $this->Leave_model->getgrace($data['temp_user']);
        echo view('templates/header_view', $data);
        echo view('etrack/hr/add_grace', $data);
        echo view('templates/footer_view');
    }

    public function add_new_grace()
    {
        $numdays = $_POST['numdays'];
        if ($numdays < 0.5) {
            session()->setFlashdata('error', lang('Messages.Error_0013'));
            return redirect()->to(base_url() . 'etrack/HR_admin/payroll_report');
        }
        $temp_user = $_POST['temp_user'];
        $start_dt = $_POST['year'] . '-' . $_POST['month'] . '-01';
        $remarks = $_POST['remarks'];
        $newdata = [
            'user_id' => $temp_user,
            'numgrace' => $numdays,
            'date' => $start_dt,
            'remarks_hr' => $remarks,
            'hr_updated_by' => session()->get('id_user'),
            'hr_updated_on' => time(),
            'hr_status' => 1
        ];
        $this->Leave_model->add_grace_data($newdata);
        session()->setFlashdata('success', lang('Messages.Success_0030'));
        return redirect()->to(base_url() . 'etrack/HR_admin/payroll_report');
    }
    public function delete_grace()
    {
        $grace_id = $_POST['grace_id'];
        $newdata = [
            'hr_updated_by' => session()->get('id_user'),
            'hr_updated_on' => time(),
            'hr_status' => 0
        ];
        $this->Leave_model->update_grace_data($newdata, $grace_id);
        session()->setFlashdata('success', lang('Messages.Success_0005'));
        return redirect()->to(base_url() . 'etrack/HR_admin/payroll_report');
    }


    public function hr_dashboard()
    {
        $data = [];
        helper(['form']);
        $client = session()->get('client');
        $data['all_users'] = $this->Leave_model->getUsersDetails($client);
        echo view('templates/header_view', $data);
        echo view('etrack/hr/admin_hr_dashboard', $data);
        echo view('templates/footer_view');
    }
    public function personal()
    {
        $data = [];
        helper(['form']);
        $client = session()->get('client');
        $data['all_users'] = $this->Leave_model->getUsersDetails($client);
        echo view('templates/header_view', $data);
        echo view('etrack/hr/admin_hr_personal', $data);
        echo view('templates/footer_view');
    }

    public function view_salary_slip()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['temp_user'])) {
            $temp_user = $_POST['temp_user'];
            $month = date("m", strtotime($_POST['salary_month']));
            $year = date("Y", strtotime($_POST['salary_month']));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'etrack/HR_admin/personal');
        }

        $data['getpayroll_details'] = $this->Employee_data_model->getpayroll_details($month, $year, $temp_user);
        $data['return_page'] = 2;
        if (count($data['getpayroll_details']) > 0) {
            echo view('templates/header_view');
            echo view('etrack/Employee_data/payroll_detail_show', $data);
            echo view('templates/footer_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'etrack/HR_admin/personal');
        }
    }
    function upload_salary()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['salary_month'])) {
            $month = date("m", strtotime($_POST['salary_month']));
            $year = date("Y", strtotime($_POST['salary_month']));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'etrack/HR_admin/personal');
        }

        if (isset($_FILES)) {

            $rules = [
                'file' => 'uploaded[file]|ext_in[file,csv,xls,xlsx]', // 10 MB
            ];
            if (!$this->validate($rules)) {
                $data['excelvalidation'] = $this->validator;
                // print_r( $data['validation']);
                // exit;
            } else {
                // print_r($this->request->getFile('file'));
                // exit;
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {

                        // Get random file name
                        $newfilename = $file->getRandomName();
                        //  print_r($newfilename);
                        //  exit();
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
                        $result = $this->HR_model->import_salary($sheetData, $month, $year);
                        if (isset($result['success'])) {
                            $session = session();
                            $session->setFlashdata('success', $result['success']);
                        } else if (isset($result['error'])) {
                            session()->setFlashdata('error', lang('Messages.Error_0003').' ' . $result['error']);
                            session()->setFlashdata('alert-class', 'alert-danger');
                        }
                    }
                }
            }
        } else if (isset($result['error'])) {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . 'etrack/HR_admin/personal');
    }
    public function view_appraisal_data()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['temp_user'])) {
            $data['temp_user'] = $_POST['temp_user'];
            $_SESSION['temp_user'] =  $data['temp_user'];
        } else if (isset($_SESSION['temp_user'])) {
            $data['temp_user'] = $_SESSION['temp_user'];
        } else {
            return redirect()->to(base_url() . 'etrack/attendance/team_attendance');
        }
        $temp_user = $data['temp_user'];
        $data['appraisals'] = $this->Employee_data_model->getappraisals($temp_user);
        $data['user_data'] = $this->HR_model->getusername($temp_user);
        echo view('templates/header_view', $data);
        echo view('etrack/hr/admin_hr_appraisal_view', $data);
        echo view('templates/footer_view');
    }



    public function edit_appraisal()
    {
        $data = [];
        helper(['form']);
        $salid = $_POST['salid'];
        $data['return_page'] = $_POST['return_page'];
        $data['appraisals_data'] = $this->Employee_data_model->getappraisalsID($salid);
        echo view('templates/header_view', $data);
        echo view('etrack/hr/admin_hr_appraisal_edit', $data);
        echo view('templates/footer_view');
    }
    public function udpate_breakup()
    {
        $salid = $_POST['salid'];
        $newdata = [
            'basic' => $_POST['basic'],
            'hra' => $_POST['hra'],
            'edu_allowance' => $_POST['edu_allowance'],
            'lta' => $_POST['lta'],
            'meal_allow' => $_POST['meal_allow'],
            'internet_allow' => $_POST['internet_allow'],
            'flex_allow' => $_POST['flex_allow'],
            'emp_pf' => $_POST['emp_pf'],
            'empl_pf' => $_POST['empl_pf'],
            'prof_tax' => $_POST['prof_tax'],
            'esi' => $_POST['esi'],
            'empl_esi' => $_POST['empl_esi'],
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $this->HR_model->update_breakup($newdata, $salid);
        $session = session();
        $session->setFlashdata('success', 'Updated successfully!');
        return redirect()->to(base_url() . 'etrack/HR_admin/view_appraisal_data');
    }

    public function download_data()
    {
        $rowCount = 2;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', '#');
        $sheet->setCellValue('B1', 'DB ID');
        $sheet->setCellValue('C1', 'Employee ID');
        $sheet->setCellValue('D1', 'First Name');
        $sheet->setCellValue('E1', 'Last Name');
        $sheet->setCellValue('F1', 'Designation');
        $sheet->setCellValue('G1', 'Level');
        $sheet->setCellValue('H1', 'Department');
        $sheet->setCellValue('I1', 'Office Email');
        $sheet->setCellValue('J1', 'Gender');
        $sheet->setCellValue('K1', 'Report To You');
        $sheet->setCellValue('L1', 'Date of Join');
        $sheet->setCellValue('M1', 'Engagement');
        $sheet->setCellValue('N1', 'Date of Resign');
        $sheet->setCellValue('O1', 'Region');

        $sheet->setCellValue('P1', 'Date of Birth');
        $sheet->setCellValue('Q1', 'Personal Email');
        $sheet->setCellValue('R1', 'Mobile Num');
        $sheet->setCellValue('S1', 'Mobile Num 2');
        $sheet->setCellValue('T1', 'Emergency Num');
        $sheet->setCellValue('U1', 'Emergency Name');
        $sheet->setCellValue('V1', 'Emergency Relation');
        $sheet->setCellValue('W1', 'PAN');
        $sheet->setCellValue('X1', 'Bank');
        $sheet->setCellValue('Y1', 'Bank Acct No.');
        $sheet->setCellValue('Z1', 'Martial');
        $sheet->setCellValue('AA1', 'Blood Group');
        $sheet->setCellValue('AB1', 'Salary');

        $headerStyle = $sheet->getStyle('A1:AZ1');
        $headerStyle->getFont()->setBold(true);

        $client = session()->get('client');

        $data['all_users'] = $this->HR_model->all_users($client);
        $department = $this->HR_model->getDropdown(8);
        $level = $this->HR_model->getDropdown(23);

        $sno = 0;
        foreach ($data['all_users'] as $user_data) {
            if ($sno > 3) {
                //  continue;
            }
            $temp_user =  $user_data['id_user'];
            $user_main_data = $this->HR_model->getpersonaldata($temp_user);
            $sno++;
            $sheet->setCellValue('A' . $rowCount, $sno);
            $sheet->SetCellValue('B' . $rowCount, $user_data['id_user']);
            $sheet->SetCellValue('C' . $rowCount, $user_data['emp_id']);
            $sheet->SetCellValue('D' . $rowCount, $user_data['name']);
            $sheet->SetCellValue('E' . $rowCount, $user_data['last_name']);
            $sheet->SetCellValue('F' . $rowCount, $user_data['designation']);

            if ($user_data['level'] > 0) {
                $level = $this->HR_model->get_dropdown($user_data['level'], 23);

                if (count($level) > 0) {
                    $sheet->SetCellValue('G' . $rowCount, $level[0]['name']);
                }
            }

            if ($user_data['department'] > 0) {
                $department = $this->HR_model->get_dropdown($user_data['department'], 8);
                if (count($department) > 0) {
                    $sheet->SetCellValue('H' . $rowCount, $department[0]['name']);
                }
            }
            $sheet->SetCellValue('I' . $rowCount, $user_data['email']);
            switch ($user_data['gender']) {
                case 1:
                    $gender = 'Female';
                    break;
                case 2:
                    $gender = 'Male';
                    break;
                case 3:
                    $gender = 'No Disclose';
                    break;
                default:
                    $gender = ' - ';
                    break;
            }
            $sheet->SetCellValue('J' . $rowCount, $gender);
            switch ($user_data['report_to_you']) {
                case 2:
                    $report_to_you = 'Yes';
                    break;
                default:
                    $report_to_you = 'No';
                    break;
            }
            $sheet->SetCellValue('K' . $rowCount, $report_to_you);
            if ($user_data['DOJ'] != '000-00-00') {
                $sheet->SetCellValue('L' . $rowCount, $user_data['DOJ']);
            }

            switch ($user_data['engage_type']) {
                case 1:
                    $engage_type = 'Salaried';
                    break;
                case 2:
                    $engage_type = 'Contract';
                    break;
                case 3:
                    $engage_type = 'Trainee';
                    break;
                default:
                    $engage_type = ' - ';
                    break;
            }
            $sheet->SetCellValue('M' . $rowCount, $engage_type);
            if ($user_data['LWD'] != '000-00-00') {
                $sheet->SetCellValue('N' . $rowCount, $user_data['LWD']);
            }

            switch ($user_data['region']) {
                case 1:
                    $region = 'India';
                    break;
                case 2:
                    $region = 'US';
                    break;
                default:
                    $region = ' - ';
                    break;
            }
            $sheet->SetCellValue('O' . $rowCount, $region);


            if (count($user_main_data) > 0) {
                if ($user_main_data[0]['DOB'] != '000-00-00') {
                    $sheet->SetCellValue('P' . $rowCount, $user_main_data[0]['DOB']);
                }

                $sheet->SetCellValue('Q' . $rowCount, $user_main_data[0]['personal_mail']);
                $sheet->SetCellValue('R' . $rowCount, $user_main_data[0]['personal_phone']);
                $sheet->SetCellValue('S' . $rowCount, $user_main_data[0]['home_phone']);
                $sheet->SetCellValue('T' . $rowCount, $user_main_data[0]['emergency_phone']);
                $sheet->SetCellValue('U' . $rowCount, $user_main_data[0]['emergency_contact']);
                $sheet->SetCellValue('V' . $rowCount, $user_main_data[0]['emergency_relation']);
                $sheet->SetCellValue('W' . $rowCount, $user_main_data[0]['PAN']);
                $sheet->SetCellValue('X' . $rowCount, $user_main_data[0]['bank']);
                $sheet->SetCellValue('Y' . $rowCount, $user_main_data[0]['bank_account_num']);
                switch ($user_main_data[0]['martial']) {
                    case 1:
                        $martial = 'Single';
                        break;
                    case 2:
                        $martial = 'Married';
                        break;
                    default:
                        $martial = ' - ';
                        break;
                }
                $sheet->SetCellValue('Z' . $rowCount, $martial);
                $sheet->SetCellValue('AA' . $rowCount, $user_main_data[0]['blood_group']);
            }


            $salary = $this->HR_model->getlatestSalary($temp_user);

            if (count($salary) > 0) {
                $sheet->SetCellValue('AB' . $rowCount, $salary[0]['yearly']);
            }
            $rowCount++;
        }

        $today_dt = date("Y-m-d H-i-s-a");
        $filename = 'ALL_EMPLOYEE-' . $today_dt . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        // Save the file to a temporary location
        $tempFilePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $filename;
        if (!is_dir(sys_get_temp_dir())) {
            mkdir(sys_get_temp_dir(), 0777, true);
        }
        $writer->save($tempFilePath);

        // Set the appropriate headers and offer the file as a download
        return $this->response->download($tempFilePath, null)->setFileName($filename);
    }
    public function download_attendance_report()
    {
        $rowCount = 2;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', '#');
        $sheet->setCellValue('B1', 'EID');
        $sheet->setCellValue('C1', 'Manager');
        $sheet->setCellValue('D1', 'Employee Name');
        $sheet->setCellValue('E1', 'WFH');
        $sheet->setCellValue('F1', 'Leaves');
        $sheet->setCellValue('G1', 'IO');
        $sheet->setCellValue('H1', 'Hrs');
        $sheet->setCellValue('I1', 'Calc IO');
        $sheet->setCellValue('J1', 'Grace');
        $sheet->setCellValue('K1', 'Work. Days');
        $sheet->setCellValue('L1', 'Attendance');
        $sheet->setCellValue('M1', 'Calc. Total');
        $sheet->setCellValue('N1', 'Delta');
        $sheet->setCellValue('O1', 'LOP');

        $headerStyle = $sheet->getStyle('A1:O1');
        $headerStyle->getFont()->setBold(true);

        $id_user = session()->get('id_user');

        if (isset($_POST['month'])) {
            $data['month'] = $_POST['month'];
            $data['year'] = $_POST['year'];
        } else {
            if (date('d') > 25) {
                if (date('m') == 12) {
                    $data['month'] = date('m');
                    $data['year'] = date('Y');
                } else {
                    $data['month'] = date('m') + 1;
                    $data['year'] = date('Y');
                }
            } else {
                $data['month'] = date('m');
                $data['year'] = date('Y');
            }
        }
        $year = date('Y');
        if ($data['month'] == 1) {
            $pre_month = 12;
            $pre_year = $year - 1;
        } else {
            $pre_month = $data['month'] - 1;
            $pre_year = $year;
        }

        if (strlen($pre_month) < 2) {
            $pre_month = '0' . $pre_month;
        }
        if (strlen($data['month']) < 2) {
            $this_month = '0' . $data['month'];
        } else {
            $this_month =  $data['month'];
        }

        $data['start_date'] = $pre_year . '-' . $pre_month . '-26';
        $data['end_date'] = $year . '-' . $this_month . '-25';


        $data['holidays'] = $this->Attendance_model->getholidays($data['start_date'],  $data['end_date'], 1);

        $today = date('Y-m-d');
        if ($data['end_date'] > $today) {
            $newdat = date('Y-m-d', (strtotime('-1 day', strtotime($today))));
            $data['working_days'] = $this->getWorkingDays($data['start_date'], $newdat, $data['holidays']);
        } else {
            $data['working_days'] = $this->getWorkingDays($data['start_date'], $data['end_date'], $data['holidays']);
        }

        $data_value = $this->Attendance_model->teamAttendance(1, $data['start_date'], $data['end_date']);
        $j = 0;
        $sno = 0;
        $counter = 0;

        foreach ($data_value['leaveData'] as $leaveData) {
            $totalattendance = 0;
            $hrs = 0;
            $min = 0;
            $leave = 0;
            $val = 0;
            $wfh = 0;
            $j++;
            if ($data_value['wfhData'][$j - 1]['emp_id'] == 0) {
                continue;
            }
            if ($data_value['wfhData'][$j - 1]['emp_id'] == '10077') {
                continue;
            }
            if ($data_value['wfhData'][$j - 1]['emp_id'] == '10111') {
                continue;
            }
            if ($data_value['wfhData'][$j - 1]['emp_id'] == '10335') {
                continue;
            }
            if ($data_value['wfhData'][$j - 1]['emp_id'] == '40032') {
                continue;
            }
            if ($data_value['wfhData'][$j - 1]['emp_id'] == '40033') {
                continue;
            }
            if ($data_value['wfhData'][$j - 1]['emp_id'] == '40034') {
                continue;
            }
            $sno++;
            //    $counter++;

            // if ($counter > 10) {
            //  $counter = 1;


            //    $username = strip_tags($eachuserclientddata['username']);


            $wfh = isset($data_value['wfhData'][$j - 1]['wfh']) ? $data_value['wfhData'][$j - 1]['wfh'] : '0';
            $leave = isset($leaveData['leaves']) ? -1 * $leaveData['leaves'] : '0';
            $inoffice = isset($data_value['accessData'][$j - 1]['ac_data']) ? $data_value['accessData'][$j - 1]['ac_data'] : '0';
            $grase = 0;
            if (isset($data_value['accessData'][$j - 1]['ac_minx'])) {
                $hrs = floor($data_value['accessData'][$j - 1]['ac_minx'] / 60);
                $min =  ':' . ($data_value['accessData'][$j - 1]['ac_minx'] -   floor($data_value['accessData'][$j - 1]['ac_minx'] / 60) * 60);
                $totalhr = $hrs . $min;
            } else {
                $totalhr = 0;
            }

            if (isset($data_value['accessData'][$j - 1]['ac_minx'])) {
                $hrs = floor($data_value['accessData'][$j - 1]['ac_minx'] / 60);

                $calhr = round(round(($hrs / 8), 1) * 2) / 2;
            } else {
                $calhr = 0;
            }

            if (isset($data_value['gracedata'][$j - 1]['numgrace'])) {
                $grase = $data_value['gracedata'][$j - 1]['numgrace'];
            } else {
                $grase = 0;
            }

            $totalattendance = $calhr + $leave + $wfh + $grase;
            $atten =  $inoffice + $leave + $wfh;

            $sheet->setCellValue('A' . $rowCount, $sno);
            $sheet->SetCellValue('B' . $rowCount, $data_value['wfhData'][$j - 1]['emp_id']);
            $sheet->SetCellValue('C' . $rowCount, $data_value['wfhData'][$j - 1]['manager_name']);
            $sheet->SetCellValue('D' . $rowCount, $leaveData['name'] . ' ' . $leaveData['last_name']);
            $sheet->SetCellValue('E' . $rowCount, $wfh);

            if ($wfh > 12) {
                $sheet->getStyle('E' . $rowCount)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FAA0A0');
            }

            $sheet->SetCellValue('F' . $rowCount, $leave);
            $sheet->SetCellValue('G' . $rowCount, $inoffice);

            $sheet->SetCellValue('L' . $rowCount, $atten);
            $lop_one = $data['working_days'] - $atten;
            if ($lop_one > 0) {
                $sheet->getStyle('L' . $rowCount)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FAA0A0');
            }

            $sheet->SetCellValue('H' . $rowCount, $totalhr);
            $sheet->setCellValue('I' . $rowCount, $calhr);
            $sheet->setCellValue('J' . $rowCount, $grase);
            $sheet->setCellValue('M' . $rowCount, $totalattendance);
            $delta = $calhr - $inoffice;
            $sheet->setCellValue('N' . $rowCount, $delta);

            $lop = $data['working_days'] - $totalattendance;
            if ($lop < 0) {
                $lop = 0;
            }
            if ($lop > 0) {
                $sheet->getStyle('O' . $rowCount)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FAA0A0');
            }

            $sheet->setCellValue('K' . $rowCount, $data['working_days']);
            $sheet->setCellValue('O' . $rowCount, $lop);
            $rowCount = $rowCount + 1;
        }

        $today_dt = date("Y-m-d H-i-s-a");
        $filename = 'Attendance_DOWNLOAD-' . $data['start_date'] . '-' . $today_dt . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        // Save the file to a temporary location
        $tempFilePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $filename;
        if (!is_dir(sys_get_temp_dir())) {
            mkdir(sys_get_temp_dir(), 0777, true);
        }
        $writer->save($tempFilePath);

        // Set the appropriate headers and offer the file as a download
        return $this->response->download($tempFilePath, null)->setFileName($filename);
    }


    public function update_appraisal()
    {
        $salid = $_POST['salid'];
        $newdata = [
            'yearly' => $_POST['yearly'],
            'description' => $_POST['description'],
            'effectivedate' => $_POST['effectivedate'],
            'designation' => $_POST['designation'],
            'type_of_engagement' => $_POST['type_of_engagement'],
            'type_of_app' => $_POST['type_of_appraisal'],
            'template' => $_POST['template'],
            'status' => $_POST['status'],
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $this->HR_model->update_appr($newdata, $salid);
        $session = session();
        $session->setFlashdata('success', 'Updated successfully!');
        return redirect()->to(base_url() . 'etrack/HR_admin/view_appraisal_data');
    }

    public function add_new_appraisal()
    {
        $newdata = [
            'id_user' => $_POST['temp_user'],
            'yearly' => $_POST['yearly'],
            'description' => $_POST['description'],
            'effectivedate' => $_POST['effectivedate'],
            'designation' => $_POST['designation'],
            'type_of_engagement' => $_POST['type_of_engagement'],
            'template' => $_POST['template'],
            'type_of_app' => $_POST['type_of_appraisal'],
            'status' => 1,
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
            'createdby' =>  session()->get('id_user'),
            'createdon' => time(),
        ];
        $salid = $this->HR_model->add_new_appr($newdata);
        if ($_POST['type_of_appraisal'] != 3) {
            $breakup = [
                'salid' => $salid,
                'id_user' => $_POST['temp_user'],
                'status' => 1,
                'last_updated_by' =>  session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $this->HR_model->add_new_breakup($breakup);
        }
        $session = session();
        $session->setFlashdata('success', 'Added successfully!');
        return redirect()->to(base_url() . 'etrack/HR_admin/view_appraisal_data');
    }


    public function view_all_appraisals()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['start_dt'])) {
            $data['start_dt'] = $_POST['start_dt'];
            $data['end_date'] = $_POST['end_date'];
            $_SESSION['start_dt'] = $data['start_dt'];
            $_SESSION['end_date'] = $data['end_date'];
        } elseif (isset($_SESSION['start_dt'])) {
            $data['start_dt'] = $_SESSION['start_dt'];
            $data['end_date'] = $_SESSION['end_date'];
        } else {
            return redirect()->to(base_url() . 'etrack/HR_admin/personal');
        }
        $data['all_appraisal'] = $this->HR_model->getAllappraisalbydate($data['start_dt'], $data['end_date']);
        echo view('templates/header_view', $data);
        echo view('etrack/hr/admin_hr_appraisal_by_date', $data);
        echo view('templates/footer_view');
    }

    public function doc_for_approval()
    {
        $data = [];
        helper(['form']);

        $data['user_docs'] = $this->HR_model->getpersonalalldocs();
        echo view('templates/header_view', $data);
        echo view('etrack/hr/admin_hr_personal_dataall_view', $data);
        echo view('templates/footer_view');
    }
    public function view_personal_data()
    {
        $data = [];
        helper(['form']);

        if (isset($_POST['temp_user'])) {
            $data['temp_user'] = $_POST['temp_user'];
            $_SESSION['temp_user'] =  $data['temp_user'];
        } else if (isset($_SESSION['temp_user'])) {
            $data['temp_user'] = $_SESSION['temp_user'];
        } else {
            return redirect()->to(base_url() . 'etrack/attendance/team_attendance');
        }
        $temp_user = $data['temp_user'];
   
        $data['user_data'] = $this->HR_model->getpersonaldata($temp_user);
        $data['username'] = $this->HR_model->getusername($temp_user);
        $data['department'] = $this->HR_model->getDropdown(8);
        $data['level'] = $this->HR_model->getDropdown(23);

        if (count($data['user_data']) == 0) {
           
            $newdata = [
                'userid' =>  $temp_user,
                'status' => 1,
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time()
            ];
            $this->HR_model->add_personal_data($newdata);
            $data['user_data'] = $this->HR_model->getpersonaldata($temp_user);
        }
   
        echo view('templates/header_view', $data);
        echo view('etrack/hr/admin_hr_personal_data_view', $data);
        echo view('templates/footer_view');
    }

    function personal_documents()
    {

        $data = [];
        helper(['form']);

        if (isset($_POST['temp_user'])) {
            $data['temp_user'] = $_POST['temp_user'];
            $_SESSION['temp_user'] =  $data['temp_user'];
        } else if (isset($_SESSION['temp_user'])) {
            $data['temp_user'] = $_SESSION['temp_user'];
        } else {
            return redirect()->to(base_url() . 'etrack/attendance/team_attendance');
        }
        $temp_user = $data['temp_user'];

        $data['username'] = $this->HR_model->getusername($temp_user);
        $data['user_docs'] = $this->HR_model->getpersonaldocs($temp_user);

        echo view('templates/header_view', $data);
        echo view('etrack/hr/admin_hr_user_docs', $data);
        echo view('templates/footer_view');
    }

    function approve_doc()
    {
        $et_doc_id = $_POST['et_doc_id'];
        $newdata = [
            'status' => $_POST['status'],
            'uploaded_by' =>  session()->get('id_user'),
            'uploaded_on' => time(),
        ];

        $this->users_model->deleteDoc($et_doc_id, $newdata);

        $session = session();
        $session->setFlashdata('success', 'Updated successfully!');
        if ($_POST['returnid'] == 1) {
            return redirect()->to(base_url() . 'etrack/HR_admin/view_personal_data');
        } else {
            return redirect()->to(base_url() . 'etrack/HR_admin/doc_for_approval');
        }
    }


    public function update_profile_data()
    {
        $upd_id = $_POST['upd_id'];
        $newdata = [
            'DOB' => $_POST['DOB'],
            'personal_mail' => $_POST['personal_mail'],
            'current_addresss' => $_POST['current_addresss'],
            'permanent_address' => $_POST['permanent_address'],
            'personal_phone' => $_POST['personal_phone'],
            'home_phone' => $_POST['home_phone'],
            'emergency_phone' => $_POST['emergency_phone'],
            'emergency_contact' => $_POST['emergency_contact'],
            'emergency_relation' => $_POST['emergency_relation'],
            'blood_group' => $_POST['blood_group'],
            'PAN' => $_POST['PAN'],
            'bank' => $_POST['bank'],
            'bank_account_num' => $_POST['bank_account_num'],
            'martial' => $_POST['martial'],
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];

        $this->HR_model->update_personal_data($newdata, $upd_id);

        $id_user = $_POST['id_user'];
        $main_data = [
            'DOJ' => $_POST['DOJ'],
            'LWD' => $_POST['LWD'],
            'gender' => $_POST['gender'],
            'engage_type' => $_POST['engage_type'],
            'designation' => $_POST['designation'],
            'level' => $_POST['level'],
            'region' => $_POST['region'],
            'department' => $_POST['department'],
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];

        $this->HR_model->update_main_data($main_data, $id_user);


        session()->setFlashdata('success', lang('Messages.Success_0008'));
        return redirect()->to(base_url() . 'etrack/HR_admin/view_personal_data');
    }

    public function user_ac_statement()
    {
        $data = [];
        helper(['form']);
        $id_user = $_POST['user_select'];
        $data['start_date'] = $_POST['start_date'];
        $data['end_date'] = $_POST['end_date'];

        $data['access_card'] = $this->Attendance_model->getAccessCardData($id_user, $data['start_date'], $data['end_date']);

        echo view('templates/header_view');
        echo view('etrack/Attendance/access_card_admin_view', $data);
        echo view('templates/footer_view');
    }

    public function apply_admin_wfh()
    {

        $value = $_POST['value'];
        $temp_user = $_POST['temp_user'];
        $start_dt = $_POST['start_date'];
        $remarks = $_POST['remarks'];
        $newdata = [
            'emp_id' => $temp_user,
            'number_wfh' => $value,
            'start_date' => $start_dt,
            'remarks' => $remarks,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => 1
        ];
        $this->Attendance_model->add_wfh_data($newdata);
        session()->setFlashdata('success', lang('Messages.Success_0025'));
        return redirect()->to(base_url() . 'etrack/HR_admin');
    }
}
