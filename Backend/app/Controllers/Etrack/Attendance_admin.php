<?php

namespace App\Controllers\Etrack;

use App\Controllers\BaseController;
use App\Models\Etrack\Leave_model;
use App\Models\Etrack\Attendance_model;

#[\AllowDynamicProperties]
class Attendance_admin extends BaseController
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
        if (!in_array('2030', $arrayuserlevel)) {
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
        echo view('etrack/Attendance/admin_view', $data);
        echo view('templates/footer_view');
    }

    public function hr_view()
    {
        $data = [];
        helper(['form']);
        $client = session()->get('client');
        $data['all_users'] = $this->Leave_model->getUsersDetails($client);
        echo view('templates/header_view', $data);
        echo view('etrack/Attendance/admin_hr_view', $data);
        echo view('templates/footer_view');
    }

    public function uploadAccessCard()
    {
        if (isset($_POST['start_date'])) {
            $data['start_date'] = $_POST['start_date'];
            $_SESSION['start_date'] =  $data['start_date'];
        } else if (isset($_GET['start_date'])) {
            $data['start_date'] = $_GET['start_date'];
        } else if (isset($_SESSION['start_date'])) {
            $data['start_date'] = $_SESSION['start_date'];
        } else {
            // return redirect()->to(base_url() . 'Assessment/trainings');
        }
        $client = session()->get('client');
        $data['all_users'] = $this->Leave_model->getUsersDetails($client);
        if (isset($_FILES)) {

            $rules = [
                'file' => 'uploaded[file]|ext_in[file,csv,xls,xlsx]', // 10 MB
            ];
            if (!$this->validate($rules)) {
                $data['excelvalidation'] = $this->validator;
                // print_r( $data['validation']);
                // exit;
            } else {

                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {

                        // Get random file name
                        $newfilename = $file->getRandomName();
                        // print_r($newfilename);
                        // exit();
                        $a = FCPATH . '/assets/assets/uploads';
                        $file->move(FCPATH . '/assets/assets/uploads/Quiz_import', $newfilename);
                        $filepath = FCPATH . 'assets/assets/uploads/Quiz_import/' . $newfilename;
                        $extension = pathinfo($newfilename, PATHINFO_EXTENSION);
                        if ($extension == 'csv') {
                            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                        } elseif ($extension == 'xls') {
                            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                        } else {
                            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                        }
                        $spreadsheet = $reader->load($filepath);
                        // Unprotect the sheet if it's protected
                        $spreadsheet->getActiveSheet()->getProtection()->setSheet(null);

                        // Get data from the sheet as an array
                        $sheetData = $spreadsheet->getActiveSheet()->toArray();

                        // Get the highest row and column with data
                        // $highestColumn = $spreadsheet->getActiveSheet()->getHighestColumn(); // Get the last column with data
                        // $columnCount = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

                        $filteredData = array_filter($sheetData[0]);
                        $columnCount = count($filteredData);
                        // print_r($sheetData[4][0]);
                        // exit();
                        $result = $this->Leave_model->importaccesscarddetails($sheetData, $data['start_date'], $columnCount);
                        // print_r($result);
                        // exit();
                        if (isset($result['error'])) {
                            session()->setFlashdata('error', $result['error']);
                            session()->setFlashdata('alert-class', 'alert-danger');
                        } elseif (isset($result['success'])) {
                            session()->setFlashdata('success', $result['success']);
                            session()->setFlashdata('alert-class', 'alert-success');
                        } else {
                            session()->setFlashdata('error', lang('Messages.Error_0008'));
                            session()->setFlashdata('alert-class', 'alert-danger');
                        }
                    }
                    return redirect()->to(base_url() . '/etrack/Attendance_admin');
                }
            }
            echo view('templates/header_view', $data);
            echo view('etrack/Attendance/admin_view', $data);
            echo view('templates/footer_view');
        }
    }
    public function viewAccessCardDetails()
    {
        $data = [];
        helper(['form']);
        $data['start_date'] = $_POST['start_date'];
        $data['access_card'] = $this->Leave_model->getAccessCardDetails($data['start_date']);
        echo view('templates/header_view', $data);
        echo view('etrack/Attendance/accesscard_selecteddate_view', $data);
        echo view('templates/footer_view');
    }
    public function deleteAccessCard()
    {
        if (isset($_POST['start_date'])) {
            $data['start_date'] = $_POST['start_date'];
            $_SESSION['start_date'] =  $data['start_date'];
        } else if (isset($_GET['start_date'])) {
            $data['start_date'] = $_GET['start_date'];
        } else if (isset($_SESSION['start_date'])) {
            $data['start_date'] = $_SESSION['start_date'];
        } else {
            // return redirect()->to(base_url() . 'Assessment/trainings');
        }

        $result = $this->Leave_model->deleteaccessCarddeatils($data['start_date']);
        if (isset($result['error'])) {
            session()->setFlashdata('error', $result['error']);
            session()->setFlashdata('alert-class', 'alert-danger');
        } elseif (isset($result['success'])) {
            session()->setFlashdata('success', $result['success']);
            session()->setFlashdata('alert-class', 'alert-success');
        }
        return redirect()->to(base_url() . '/etrack/Attendance_admin');
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
        return redirect()->to(base_url() . 'etrack/Attendance_admin');
    }
}
