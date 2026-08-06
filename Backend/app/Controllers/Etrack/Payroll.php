<?php

namespace App\Controllers\Etrack;

use App\Controllers\BaseController;
use App\Models\Etrack\Employee_data_model;
use Dompdf\Dompdf;
use setasign\Fpdi\Tcpdf\Fpdi;

#[\AllowDynamicProperties]

class Payroll extends BaseController
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

    public function index()
    {
        $user = session()->get('id_user');
        if (isset($_SESSION['pannumber'])) {
            $data['pannumber'] = $_SESSION['pannumber'];
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            return redirect()->to(base_url() . 'etrack/employee_details');
        }
        $data['payroll'] = $this->Employee_data_model->getpayrolls($user);
        echo view('templates/header_view');
        echo view('etrack/Employee_data/payroll_view', $data);
        echo view('templates/footer_view');
    }

    public function show_payslip()
    {
        $data = [];
        helper(['form']);
        $user = session()->get('id_user');

        if (isset($_SESSION['pannumber'])) {
            $data['pannumber'] = $_SESSION['pannumber'];
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            return redirect()->to(base_url() . 'etrack/employee_details');
        }

        if (isset($_POST['payslip_month'])) {
            $data['payslip_month'] = $_POST['payslip_month'];
            $data['payslip_yr'] = $_POST['payslip_yr'];
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            return redirect()->to(base_url() . 'etrack/employee_details');
        }

        $user = session()->get('id_user');
        $data['getpayroll_details'] = $this->Employee_data_model->getpayroll_details($data['payslip_month'], $data['payslip_yr'], $user);
        $data['return_page'] = 1;

        echo view('templates/header_view');
        echo view('etrack/Employee_data/payroll_detail_show', $data);
        echo view('templates/footer_view');
    }
    public function updateEID()
    {
        $this->Employee_data_model->updateEmpID_For_MissingData();
    }
    public function download_payslip()
    {
        helper(['form']);
        $user = session()->get('id_user');

        if (!isset($_SESSION['pannumber'])) {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            return redirect()->to(base_url('etrack/employee_details'));
        }

        if (!isset($_POST['payslip_month'], $_POST['payslip_yr'])) {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            return redirect()->to(base_url('etrack/employee_details'));
        }

        $data = [
            'pannumber' => $_SESSION['pannumber'],
            'payslip_month' => $_POST['payslip_month'],
            'payslip_yr' => $_POST['payslip_yr'],
            'return_page' => 1,
            'header_img' => '',
            'rupee_sym' => '',
        ];



        $data['getpayroll_details'] = $this->Employee_data_model->getpayroll_details($data['payslip_month'], $data['payslip_yr'], $user);

        $filename = 'Payslip_' . $data['payslip_month'] . '_' . $data['payslip_yr'];

        $userPassword = $data['getpayroll_details'][0]['pan'];

        $dateofpay =  $data['payslip_yr'] . '-' . $data['payslip_month'] . '-01';
        $data['dateofpay'] = date("Y-m-d", strtotime($dateofpay));

        if ($data['dateofpay'] < '2025-04-01') {
            $data['header_img'] = $this->imageToBase64(ROOTPATH . '/assets/assets/uploads/client_logo/talentquest_logo.png');
        } else {
            $data['header_img'] = $this->imageToBase64(ROOTPATH . '/assets/assets/uploads/client_logo/touchstone_logo.png');
        }

        $data['rupee_sym'] = $this->imageToBase64(ROOTPATH . '/assets/assets/img/Indian_Rupee_symbol.svg');

        $dompdf = new Dompdf(['defaultPaperSize' => 'A4']);
        $html = view('etrack/Employee_data/payroll_pdf_export', $data);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        $dompdf->loadHtml($html);
        $dompdf->render();

        $pdfOutput = $dompdf->output();

        $tempFile = WRITEPATH . 'temp_' . time() . '.pdf';
        file_put_contents($tempFile, $pdfOutput);

        // Step 2: Load PDF in TCPDF + FPDI and add password protection
        $pdf = new Fpdi();
        $masterPass = $data['getpayroll_details'][0]['uan'];

        // Set protection (permissions, user password, owner password)
        $pdf->SetProtection([
            'print' => true,
            'copy' => false,
            'modify' => false,
            'annot-forms' => true
        ], $userPassword, $masterPass);



        // Get total pages from source PDF
        $pageCount = $pdf->setSourceFile($tempFile);
        if ($pageCount === 0) {
            unlink($tempFile);
            throw new \Exception('Source PDF file contains no pages.');
        }

        // Import all pages, add them to TCPDF
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $tplId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($tplId);
            $orientation = ($size['height'] > $size['width']) ? 'P' : 'L';
            $pdf->AddPage($orientation, [$size['width'], $size['height']]);
            $pdf->useTemplate($tplId);
        }

        unlink($tempFile);  // Clean up temp file

        // Output encrypted PDF to browser for download
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '.pdf"')
            ->setBody($pdf->Output($filename . '.pdf', 'S'));
    }






    /* public function download_payslip()
    {
        $data = [];
        helper(['form']);
        $user = session()->get('id_user');

        if (isset($_SESSION['pannumber'])) {
            $data['pannumber'] = $_SESSION['pannumber'];
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            return redirect()->to(base_url() . 'etrack/employee_details');
        }

        if (isset($_POST['payslip_month'])) {
            $data['payslip_month'] = $_POST['payslip_month'];
            $data['payslip_yr'] = $_POST['payslip_yr'];
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            return redirect()->to(base_url() . 'etrack/employee_details');
        }
        $data['return_page'] = 1;
        $data['getpayroll_details'] = $this->Employee_data_model->getpayroll_details($data['payslip_month'], $data['payslip_yr'], $user);
        $data['header_img'] = $this->imageToBase64(ROOTPATH . '/assets/assets/uploads/client_logo/talentquest_logo.png');
        $data['rupee_sym'] = $this->imageToBase64(ROOTPATH . '/assets/assets/img/Indian_Rupee_symbol.svg');
        $dompdf = new Dompdf(['defaultPaperSize' => 'A4']);
        $filename = 'Payslip_' . $data['payslip_month'] . '_' . $data['payslip_yr'];
        $data['filename'] = $filename;
        $html = view('etrack/Employee_data/payroll_pdf_export', $data);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        $dompdf->getOptions()->set('isHtml5ParserEnabled', true);
        $dompdf->getOptions()->set('isPhpEnabled', true);
        $dompdf->loadHtml($html);
        $dompdf->render();

        $canvas = $dompdf->getCanvas();
        $canvas->page_text(553, 766, "{PAGE_NUM}", null, 8, array(0, 0, 0));
        $dompdf->stream($filename . '.pdf', ['Attachment' => true]);
    } */

    private function imageToBase64($path)
    {
        $path = $path;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }
}
