<?php

namespace App\Controllers\Etrack;

use App\Controllers\BaseController;
use App\Models\Etrack\Leave_model;
use App\Models\Etrack\HR_model;
use Dompdf\Dompdf;
use Dompdf\Options;


#[\AllowDynamicProperties]
class Exit_clearance extends BaseController
{
    public function __construct()
    {
        $this->Leave_model = new Leave_model();
        $this->HR_model = new HR_model();
    }
    public function index() //fetch data from users table to display
    {
        $data = []; 
        helper(['form']);
        $client = session()->get('client');
        $data['all_users'] = $this->Leave_model->getUsersDetails($client);
        $data['exitUsers'] = $this->HR_model->exitUsers();
     
        echo view('templates/header_view', $data);
        echo view('etrack/Exit_clearance/exit_clearance_view', $data);
        echo view('templates/footer_view');
    }
    public function updateLwd()
    {
        $data = [];
        helper(['form']);
        $userdata = explode('|', $_POST['user_id']);
        $data['user_id'] = $userdata[0];
        $data['username'] = $userdata[1];
        $data['email'] = $userdata[2];
        $data['manager'] = $userdata[3];
        $newdata = [
            'LWD' => $_POST['LWD'],
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),

        ];
        $result = $this->HR_model->updateLwd($newdata, $data['user_id']);
        $managerdata = $this->HR_model->getMangerEmailid($data['manager']);
        $managerid = isset($managerdata[0]['email']) ? $managerdata[0]['email'] : '';
        if ($result) {
            $to = 'shwetha.k@touchstonelc.com,lakshmi.n@touchstonelc.com,arun.p@touchstonelc.com,vijay.r@touchstonelc.com,pramod.c@touchstonelc.com,' . $managerid . ',' . $data['email'];
            // $to = 'keerthana.mk@TouchstoneLC.com';
            $subject = 'Exit Clearance';
            $message = 'Hi,<br><br>A request for Exit Clearance for ' . $data['username'] . ' for last day ' . $_POST['LWD'] . ' has been raised. Request you to '
                . 'please visit <a href="https://dochek.com/" target="blank">Dochek</a> and fill the form.<br><br><br>'
                . 'Regards,<br>'
                . 'Admin';
            $email = \Config\Services::email();
            $email->setFrom('do-not-reply@touchstonelc.com', 'Dochek');
            $email->setTo($to);
            // $email->setCC('another@another-example.com');
            //$email->setBCC('them@their-example.com');

            $email->setSubject($subject);
            $email->setMessage($message);

            if ($email->send()) {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
            } else {
                $data = $email->printDebugger(['headers']);
                print_r($data);
                exit();
            }
            session()->setFlashdata('success', lang('Messages.Success_0028'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
        }
        return redirect()->to(base_url() . 'etrack/exit_clearance');
    }
    function exit_clearance_form()
    {
        $data = [];
        helper(['form']);
        $client = session()->get('client');
        if (isset($_POST['user_id'])) {
            $data['user_id'] = $_POST['user_id'];
            $_SESSION['user_id'] = $data['user_id'];
        }
        if (isset($_SESSION['user_id'])) {
            $data['user_id'] = $_SESSION['user_id'];
        }
        if (isset($_POST['manager'])) {
            $data['manager'] = $_POST['manager'];
            $_SESSION['manager'] = $data['manager'];
        }
        if (isset($_SESSION['manager'])) {
            $data['manager'] = $_SESSION['manager'];
        }
        $data['all_users'] = $this->Leave_model->getUsersDetails($client);
        $data['exitUsers'] = $this->HR_model->exitUsers();
        $data['getExitclearanceheader'] = $this->HR_model->getExitclearanceheader(1);
        $data['getExitclearanceSubheader'] = $this->HR_model->getExitclearanceheader(2);
        $data['getUserexitformstatus'] = $this->HR_model->getUserexitformstatus($data['user_id']);
        echo view('templates/header_view', $data);
        echo view('etrack/Exit_clearance/exit_clearance_from_view', $data);
        echo view('templates/footer_view');
    }
    public function updateHeaderStatus()
    {
        $status = $this->request->getPost('status');
        $user_id = $this->request->getPost('user_id');
        $fk_header_id = $this->request->getPost('fk_header_id');
        // print_r($status);
        // print_r($user_id);
        // print_r($fk_header_id);
        // exit();

        if (!$user_id || !$fk_header_id) {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->back();
        }

        $newdata = [
            'fk_header_id' => $fk_header_id,
            'id_user' => $user_id,
            'status' => $status,
            'createdon' => time(),
            'createdby' => session()->get('id_user')
        ];

        $this->HR_model->updateExitstatus($newdata);

        session()->setFlashdata('success', lang('Messages.Success_0008'));
        return redirect()->to(base_url('etrack/exit_clearance/exit_clearance_form'));
    }

    public function updateSubheaders()
    {
        $user_id = $this->request->getPost('user_id');
        $fk_header_id = $this->request->getPost('fk_header_id');

        $statusUpdates = $this->request->getPost('status');   // array of fk_header_id => status
        // print_r($statusUpdates);
        // exit();
        $commentUpdates = $this->request->getPost('comment'); // array of fk_header_id => comment

        $createdby = session()->get('id_user');
        $timestamp = time();

        if (!empty($statusUpdates)) {
            foreach ($statusUpdates as $subheader_id => $status) {
                if ($status == '') {
                    continue;
                }


                $data = [
                    'fk_header_id' => $subheader_id,
                    'id_user' => $user_id,
                    'status' => $status,
                    'createdon' => $timestamp,
                    'createdby' => $createdby
                ];

                $this->HR_model->updateExitstatus($data);
            }
        }

        if (!empty($commentUpdates)) {
            foreach ($commentUpdates as $subheader_id => $comment) {
                $data = [
                    'fk_header_id' => $subheader_id,
                    'id_user' => $user_id,
                    'comment' => $comment,
                    'createdon' => $timestamp,
                    'createdby' => $createdby
                ];
                $this->HR_model->updateExitstatus($data);
            }
        }

        session()->setFlashdata('success', lang('Messages.Success_0008'));
        return redirect()->to(base_url('etrack/exit_clearance/exit_clearance_form'));
    }

    function exit_interview_view()
    {
        $data = [];
        if (isset($_POST['user_id'])) {
            $data['user_id'] = $_POST['user_id'];
            $_SESSION['user_id'] = $data['user_id'];
        }
        if (isset($_SESSION['user_id'])) {
            $data['user_id'] = $_SESSION['user_id'];
        }
        helper(['form']);
        $data['userexitInterdata'] = $this->HR_model->getUserexitInterviewtatus($data['user_id']);
        echo view('templates/header_view', $data);
        echo view('etrack/Exit_clearance/exit_interview_view', $data);
        echo view('templates/footer_view');
    }
    function addUpdateExitInterview()
    {
        $data = [];
        helper(['form']);
        // print_r($_POST);
        // exit();
        $newdata = [
            'id_user' => $_POST['user_id'],
            'mobile_number' => $_POST['mobile_number'],
            'personal_email_id' => $_POST['personal_email_id'],
            'why_new_job' => $_POST['why_new_job'],
            'culture_of_our_comany' => $_POST['culture_of_our_comany'],
            'remain_employed_here' => $_POST['remain_employed_here'],
            'job_company_change' => $_POST['job_company_change'],
            'satisfied_manged' => $_POST['satisfied_manged'],
            'objectives' => $_POST['objectives'],
            'performance' => $_POST['performance'],
            'work_here_future' => $_POST['work_here_future'],
            'recommend_employment' => $_POST['recommend_employment'],
            'feedback' => $_POST['feedback']
        ];
        $result = $this->HR_model->addUpdateExitInterview($_POST['user_id'], $newdata);
        session()->setFlashdata('success', lang('Messages.Success_0008'));
        return redirect()->to(base_url() . 'etrack/exit_clearance/exit_interview_view');
    }
    function exit_clearance_pdf()
    {
        $dompdf = new Dompdf();
        $client = session()->get('client');
        if (isset($_POST['user_id'])) {
            $data['user_id'] = $_POST['user_id'];
            $_SESSION['user_id'] = $data['user_id'];
        }
        if (isset($_SESSION['user_id'])) {
            $data['user_id'] = $_SESSION['user_id'];
        }
        $data['emp_id'] = $_POST['emp_id'];
        $data['username'] = $_POST['username'];
        $data['all_users'] = $this->Leave_model->getUsersDetails($client);
        $data['exitUsers'] = $this->HR_model->exitUsers();

        $data['getExitclearanceheader'] = $this->HR_model->getExitclearanceheader(1);
        $data['getExitclearanceSubheader'] = $this->HR_model->getExitclearanceheader(2);
        $data['getUserexitformstatus'] = $this->HR_model->getUserexitformstatus($data['user_id']);
        //  print_r($data['getUserexitformstatus'] );
        // exit();
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        // if (!empty($data['getUserexitformstatus'])) {

        $data['logo'] = $this->imageToBase64(ROOTPATH . 'assets/assets/img/TS_Logo.svg');
        $html = view('etrack/Exit_clearance/pdf_exit_clearance_view', $data);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $canvas = $dompdf->getCanvas();
        $font = $dompdf->getFontMetrics()->getFont('tahoma', 'normal');
        $fontSize = 8;
        $y = 820;
        $canvas->page_text(558, $y, "{PAGE_NUM}", $font, $fontSize, [0, 0, 0]);


        // Enable HTML5 and font settings
        $dompdf->getOptions()->set('isHtml5ParserEnabled', true);
        $dompdf->getOptions()->set('isPhpEnabled', true);
        $dompdf->loadHtml($html);

        $dompdf->stream($data['emp_id'] . "_" . $data['username'] . '_exit_clearance.pdf', ['Attachment' => true]);
        // } else {
        //     session()->setFlashdata('error', lang('Messages.Error_0001'));
        //     session()->setFlashdata('alert-class', 'alert-danger');
        //     return redirect()->to(base_url() . 'etrack/exit_clearance');
        // }

    }
    function exit_interview_pdf()
    {
        $dompdf = new Dompdf();
        $client = session()->get('client');
        if (isset($_POST['user_id'])) {
            $data['user_id'] = $_POST['user_id'];
            $_SESSION['user_id'] = $data['user_id'];
        }
        if (isset($_SESSION['user_id'])) {
            $data['user_id'] = $_SESSION['user_id'];
        }
        $data['emp_id'] = $_POST['emp_id'];
        $data['username'] = $_POST['username'];
        $data['userexitInterdata'] = $this->HR_model->getUserexitInterviewtatus($data['user_id']);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        // if (!empty($data['userexitInterdata'])) {
        $data['logo'] = $this->imageToBase64(ROOTPATH . 'assets/assets/img/TS_Logo.svg');

        $html = view('etrack/Exit_clearance/pdf_exit_interview_view', $data);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $canvas = $dompdf->getCanvas();
        $font = $dompdf->getFontMetrics()->getFont('tahoma', 'normal');
        $fontSize = 8;
        $y = 820;
        $canvas->page_text(558, $y, "{PAGE_NUM}", $font, $fontSize, [0, 0, 0]);


        // Enable HTML5 and font settings
        $dompdf->getOptions()->set('isHtml5ParserEnabled', true);
        $dompdf->getOptions()->set('isPhpEnabled', true);
        $dompdf->loadHtml($html);

        $dompdf->stream($data['emp_id'] . "_" . $data['username'] . '_exit_interview.pdf', ['Attachment' => true]);
        // } else {
        //     session()->setFlashdata('error', lang('Messages.Error_0001'));
        //     session()->setFlashdata('alert-class', 'alert-danger');
        //     return redirect()->to(base_url() . 'etrack/exit_clearance');
        // }

    }
    private function imageToBase64($path)
    {
        $path = $path;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }
}
