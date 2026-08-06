<?php

namespace App\Controllers\Etrack;

use App\Controllers\BaseController;
use App\Models\Etrack\Employee_data_model;

#[\AllowDynamicProperties]
class Employee_details extends BaseController
{
    public function __construct()
    {
        $this->is_session_available();
        $this->Employee_data_model = new Employee_data_model();
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
        echo view('templates/header_view', $data);
        echo view('etrack/Employee_data/twofactorauth', $data);
        echo view('templates/footer_view');
    }

    public function check_access()
    {
        $user = session()->get('id_user');
        if (isset($_POST['pannumber'])) {
            $data['pannumber'] = $_POST['pannumber'];
        } else if (isset($_SESSION['pannumber'])) {
            $data['pannumber'] = $_SESSION['pannumber'];
        } else {
            session()->setFlashdata('error', 'Update 2 factor authentication.');
            return redirect()->to(base_url() . 'my_training');
        }
        $data['access_granted'] = $this->Employee_data_model->checkuseraccess($user, $data['pannumber']);
        if ($data['access_granted']) {
            if ($data['access_granted'][0]['upd_id'] > 0) {
                $_SESSION['pannumber'] =  $data['pannumber'];
                return redirect()->to(base_url() . 'etrack/employee_details/access_approved');
            }
        } else {
            session()->setFlashdata('error', 'Did not get it correct.');
            return redirect()->to(base_url() . 'etrack/employee_details');
        }
    }
    public function access_approved()
    {
        if (!isset($_SESSION['pannumber'])) {
            session()->setFlashdata('error', 'Update 2 factor authentication.');
            return redirect()->to(base_url() . 'etrack/employee_details');
        }
        echo view('templates/header_view');
        echo view('etrack/Employee_data/twofactorauth_approved');
        echo view('templates/footer_view');
    }

    public function dependents()
    {
        $user = session()->get('id_user');
        if (isset($_SESSION['pannumber'])) {
            $data['pannumber'] = $_SESSION['pannumber'];
        } else {
            session()->setFlashdata('error', 'Update 2 factor authentication.');
            return redirect()->to(base_url() . 'etrack/employee_details');
        }
        $data['dependents'] = $this->Employee_data_model->getdependents($user);
        echo view('templates/header_view');
        echo view('etrack/Employee_data/employee_dependent_view', $data);
        echo view('templates/footer_view');
    }

    public function  income_tax()
    {

        if (isset($_SESSION['pannumber'])) {
            $data['pannumber'] = $_SESSION['pannumber'];
        } else {
            session()->setFlashdata('error', 'Update 2 factor authentication.');
            return redirect()->to(base_url() . 'etrack/employee_details');
        }

        echo view('templates/header_view');
        echo view('etrack/Employee_data/income_tax_view', $data);
        echo view('templates/footer_view');
    }

    public function downloadf16()
    {
        $user = session()->get('id_user');
        if (isset($_SESSION['pannumber'])) {
            $data['pannumber'] = $_SESSION['pannumber'];
        } else {
            session()->setFlashdata('error', 'Update 2 factor authentication.');
            return redirect()->to(base_url() . 'etrack/employee_details');
        }
        $data['access_granted'] = $this->Employee_data_model->checkuseraccess($user, $data['pannumber']);
        if ($data['access_granted']) {
            if ($data['access_granted'][0]['upd_id'] > 0) {
                $data['pannumber'];

                $url = '/var/www/html/assets/assets/uploads/F16/FORM_16_FY_2025-26/' . $data['pannumber'] . '.pdf';
                //$url = 'C:/wamp64/www/DOCHEK/assets/assets/uploads/F16/FORM_16_FY_2024-25/' . $data['pannumber'] . '_2025-26.pdf';
                // print_r($url);
                // exit();
                if (file_exists($url)) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="F16-2025-26.pdf"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($url));
                    flush(); // Flush system output buffer
                    readfile($url);
                    die();
                }
                session()->setFlashdata('error', 'File does not exists.');
            }
        } else {
            session()->setFlashdata('error', 'Did not get it correct.');
            return redirect()->to(base_url() . 'etrack/employee_details/income_tax');
        }
        return redirect()->to(base_url() . 'etrack/employee_details/income_tax');
    }

    public function appraisals()
    {
        $user = session()->get('id_user');
        if (isset($_SESSION['pannumber'])) {
            $data['pannumber'] = $_SESSION['pannumber'];
        } else {
            session()->setFlashdata('error', 'Update 2 factor authentication.');
            return redirect()->to(base_url() . 'etrack/employee_details');
        }
        $data['appraisals'] = $this->Employee_data_model->getappraisals($user);
        echo view('templates/header_view');
        echo view('etrack/Employee_data/employee_appraisal_view', $data);
        echo view('templates/footer_view');
    }

    public function view_letter()
    {
        if (isset($_SESSION['pannumber'])) {
            $data['pannumber'] = $_SESSION['pannumber'];
        } else {
            session()->setFlashdata('error', 'Update 2 factor authentication.');
            return redirect()->to(base_url() . 'etrack/employee_details');
        }
        if (isset($_POST['temp_user'])) {
            $user = $_POST['temp_user'];
        } else {
            $user = session()->get('id_user');
        }

        $data['return_page'] = $_POST['return_page'];

        $salid = $_POST['salid'];
        $data['appraisal_letter'] = $this->Employee_data_model->getletter($user, $salid);
        echo view('templates/header_view');
        echo view('etrack/Employee_data/employee_appraisal_letter', $data);
        echo view('templates/footer_view');
    }

    public function view_des_letter()
    {
        if (isset($_SESSION['pannumber'])) {
            $data['pannumber'] = $_SESSION['pannumber'];
        } else {
            session()->setFlashdata('error', 'Update 2 factor authentication.');
            return redirect()->to(base_url() . 'etrack/employee_details');
        }
        if (isset($_POST['temp_user'])) {
            $user = $_POST['temp_user'];
        } else {
            $user = session()->get('id_user');
        }

        $data['return_page'] = $_POST['return_page'];

        $salid = $_POST['salid'];
        $data['appraisal_letter'] = $this->Employee_data_model->getletter($user, $salid);
        echo view('templates/header_view');
        echo view('etrack/Employee_data/employee_appraisal_letter_2', $data);
        echo view('templates/footer_view');
    }
    public function view_mixed_letter()
    {
        if (isset($_SESSION['pannumber'])) {
            $data['pannumber'] = $_SESSION['pannumber'];
        } else {
            session()->setFlashdata('error', 'Update 2 factor authentication.');
            return redirect()->to(base_url() . 'etrack/employee_details');
        }
        if (isset($_POST['temp_user'])) {
            $user = $_POST['temp_user'];
        } else {
            $user = session()->get('id_user');
        }

        $data['return_page'] = $_POST['return_page'];

        $salid = $_POST['salid'];
        $data['appraisal_letter'] = $this->Employee_data_model->getletter($user, $salid);
        echo view('templates/header_view');
        echo view('etrack/Employee_data/employee_appraisal_letter_3', $data);
        echo view('templates/footer_view');
    }
    public function view_breakup()
    {
        if (isset($_SESSION['pannumber'])) {
            $data['pannumber'] = $_SESSION['pannumber'];
        } else {
            session()->setFlashdata('error', 'Update 2 factor authentication.');
            return redirect()->to(base_url() . 'etrack/employee_details');
        }
        if (isset($_POST['temp_user'])) {
            $user = $_POST['temp_user'];
        } else {
            $user = session()->get('id_user');
        }

        $data['return_page'] = $_POST['return_page'];

       $salid = $_POST['salid'];

        $data['appraisal_breakup'] = $this->Employee_data_model->getbreakup($user, $salid);

        echo view('templates/header_view');
        echo view('etrack/Employee_data/employee_appraisal_breakup', $data);
        echo view('templates/footer_view');
    }


    public function approve_appraisal()
    {
        $salid  = $_POST['salid'];
        $newdata = [
            'iagree' => $_POST['acceptval'],
            'adgreed_on' => time(),
            'adgreed_by' => session()->get('id_user')
        ];
        $this->Employee_data_model->update_appraisal($newdata, $salid);

        if ($_POST['acceptval'] == 2) {
            session()->setFlashdata('success', 'Please contact BU Head.');
        } else {
            session()->setFlashdata('success', 'Thanks for accepting. The data is shared with HR/Finance.');
        }

        return redirect()->to(base_url() . 'etrack/employee_details/appraisals');
    }

    public function add_dependents()
    {
        if (strlen($_POST['dob']) < 5) {
            session()->setFlashdata('error', 'Date of birth required.');
            return redirect()->to(base_url() . 'etrack/employee_details/check_access');
        }
        $newdata = [
            'saturation' => $_POST['saturation'],
            'dep_name' => $_POST['fname'],
            'dep_mname' => $_POST['mname'],
            'dep_lname' => $_POST['lname'],
            'policy' => $_POST['policyno'],
            'relation' => $_POST['relation'],
            'dep_dob' => $_POST['dob'],
            'user_id' => session()->get('id_user'),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => 1
        ];
        $this->Employee_data_model->add_dependent($newdata);
        session()->setFlashdata('success', 'Data added.');
        return redirect()->to(base_url() . 'etrack/employee_details/check_access');
    }

    public function del_dependent()
    {
        $dependent_id = $_POST['dependent_id'];
        $newdata = [
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => 0
        ];
        $this->Employee_data_model->update_dependent($newdata, $dependent_id);
        session()->setFlashdata('success', 'Data deleted.');
        return redirect()->to(base_url() . 'etrack/employee_details/check_access');
    }
}
