<?php

namespace App\Controllers\Etrack;

use App\Controllers\BaseController;
use App\Models\Etrack\Finance_model;
use App\Models\Etrack\Leave_model;
use App\Models\Etrack\Attendance_model;
use App\Models\Project_Manage\PM_ucn_model;
use Dompdf\Dompdf;
use setasign\Fpdi\Tcpdf\Fpdi;
use App\Models\Etrack\Employee_data_model;

#[\AllowDynamicProperties]
class Fin_admin extends BaseController
{
    public function __construct()
    {
        $this->is_session_available();
        $this->Leave_model = new Leave_model();
        $this->PM_ucn_model = new PM_ucn_model();
        $this->Finance_model = new Finance_model();
        $this->Attendance_model = new Attendance_model();
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

        if (in_array('2010', $arrayuserlevel) || in_array('3048', $arrayuserlevel) || in_array('3014', $arrayuserlevel) || in_array('69', $arrayuserlevel)) {

            //  session()->setFlashdata('error', lang('Messages.Error_0004'));
            //     header('Location:' . base_url('my_training'));
            //  exit();
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }

    public function index() //fetch data from users table to display
    {
        if ($response =  $this->requireRole(['3048'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $client = session()->get('client');
        $data['all_users'] = $this->Leave_model->getUsersDetails($client);
        echo view('templates/header_view', $data);
        echo view('etrack/finance/payroll_dashboard', $data);
        echo view('templates/footer_view');
    }

    public function dashboard() //fetch data from users table to display
    {
        if ($response =  $this->requireRole(['3048', '2010', '3014', '69'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if(isset($_POST['year'])){
            $data['year'] = $_POST['year'];
        }else{
            $data['year'] = date('Y');
        }
        if(isset($_POST['month'])){
            $data['month'] = $_POST['month'];
        }else{
            $data['month'] = date('m');
        } 
        $client = session()->get('client');
        $data['total_users'] = $this->Finance_model->get_active_users_dashboard($client);
        $data['total_male'] = $this->Finance_model->get_active_male_users_dashboard($client);
        $data['India_users'] = $this->Finance_model->get_active_India_users_dashboard($client);
        $data['permanent_users'] = $this->Finance_model->get_active_permanent_users_dashboard($client);
        $data['tasks_users'] = $this->Finance_model->get_active_tasks_users_dashboard($client);
        $data['list_users'] = $this->Finance_model->list_task_users($client);
        $data['bespoke_revenue'] = $this->Finance_model->get_bespoke_revenue($data['year']);
        $data['active_users'] = $this->Finance_model->get_active_users();
        $data['salary_users'] = $this->Finance_model->get_active_salary_users_dashboard($data['year']);
        $data['sales_data'] = $this->Finance_model->get_sales_data_dashboard();
        $data['operation_cost'] = $this->Finance_model->get_operation_cost($data['year']);
        echo view('templates/header_view', $data);
        echo view('etrack/finance/finance_dashboard', $data);
        echo view('templates/footer_view');
    }

    private function imageToBase64($path)
    {
        $path = $path;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }
    public function download_payslip()
    {
        if ($response =  $this->requireRole(['3048', '2010'])) {
            return $response;
        }
        helper(['form']);
        $user = $_POST['temp_user'];
        if (!isset($_POST['month'], $_POST['year'])) {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            return redirect()->to(base_url('etrack/Fin_admin'));
        }

        $pannumber = $this->Employee_data_model->get_pan_by_userID($user);

        if (strlen($pannumber[0]['PAN']) > 2) {
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            return redirect()->to(base_url('etrack/Fin_admin'));
        }
        $data = [
            'pannumber' => $pannumber[0]['PAN'],
            'payslip_month' => $_POST['month'],
            'payslip_yr' => $_POST['year'],
            'return_page' => 1,
            'header_img' => '',
            'rupee_sym' => '',
        ];

        $data['getpayroll_details'] = $this->Employee_data_model->getpayroll_details($data['payslip_month'], $data['payslip_yr'], $user);

        if (count($data['getpayroll_details']) > 0) {
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
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            return redirect()->to(base_url() . 'etrack/Fin_admin');
        }
    }



    public function claim_admin()
    {
        $data = [];
        helper(['form']);
        $data['active_claims'] = $this->Finance_model->get_active_claims();
        echo view('templates/header_view', $data);
        echo view('etrack/finance/claim_admin_view', $data);
        echo view('templates/footer_view');
    }

    public function view_claim()
    {
        $data = [];
        helper(['form']);
        $vd_id = $_POST['vd_id'];
        $data['claim_details'] = $this->Finance_model->get_claim_details_byID($vd_id);
        $data['active_ucn'] = $this->Finance_model->get_active_ucn();
        $data['linked_ucn'] = $this->Finance_model->get_linked_ucn($vd_id);
        echo view('templates/header_view', $data);
        echo view('etrack/finance/claim_admin_approve', $data);
        echo view('templates/footer_view');
    }

    public function delete_payslip()
    {
        $month = $_POST['selected_month'];
        $year = $_POST['selected_year'];
        $newdata = [
            'status' => 0,


        ];
        $this->Finance_model->delete_payslips($newdata, $year, $month);
        session()->setFlashdata('success', lang('Messages.Success_0005'));
        return redirect()->to(base_url() . 'etrack/Fin_admin');
    }


    //////
    function payslip_import()
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
                        $result = $this->Finance_model->import_payslip_excel($sheetData, $month, $year);

                        $session = session();
                        $session->setFlashdata('success', $result . ' Rows imported');
                        return redirect()->to(base_url() . 'etrack/Fin_admin');
                    }
                }
            }
        }
        return redirect()->to(base_url() . 'etrack/Fin_admin');
    }
    public function WIP_summary()
    {
        /*         $data = [];
        helper(['form']);
        if (!empty($_POST['year'])) {
            $data['year'] = $this->request->getPost('year');
            $data['wip_list'] = $this->PM_ucn_model->get_wip_yearlist($data['year']);
            if (empty($data['wip_list'])) {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
                return redirect()->to(base_url() . 'Project_Manage/PM_wip');
            }
        } else {
            $data['year'] = date("Y");
            $data['wip_list'] = $this->PM_ucn_model->get_wip_yearlist($data['year']);
        }
        echo view('templates/header_view', $data);
        echo view('etrack/finance/wip_summary_view', $data);
        echo view('templates/footer_view'); */

        $data = [];
        helper(['form']);
        $data = [
            'link1' => 'my_training',
            'link1_name' => 'Dashboard',
            'link2' => '',
            'link2_name' => '',
            'link3_name' => 'WIP'
        ];
        $user = session()->get('id_user');
        if (!empty($_POST['year'])) {
            $data['year'] = $this->request->getPost('year');
            $data['wip_list'] = $this->PM_ucn_model->get_wip_yearlist($data['year']);
            // print_r($data['wip_list']);
            // exit();
            if (empty($data['wip_list'])) {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
                return redirect()->to(base_url() . 'Project_Manage/PM_wip');
            }
        } else {
            $data['year'] = date("Y");
            $data['wip_list'] = $this->PM_ucn_model->get_wip_yearlist($data['year']);
            // $data['wip_list'] = $this->PM_ucn_model->get_wip_list();
        }
        echo view('templates/header_view', $data);
        echo view('project_management/wip_dashboard_view', $data);
        echo view('templates/footer_view');
    }

    public function purchase_orders()
    {
        $data = [];
        helper(['form']);

        $data['year'] = date("Y");
        $data['purchase_orders'] = $this->Finance_model->get_purchase_order_admin();

        echo view('templates/header_view', $data);
        echo view('etrack/finance/purchase_order_view', $data);
        echo view('templates/footer_view');
    }


    public function purchase_orders_issued()
    {
        $data = [];
        helper(['form']);

        $data['year'] = date("Y");
        // $data['purchase_orders_issued'] = $this->Finance_model->get_purchase_order_issued($data['year']);

        echo view('templates/header_view', $data);
        echo view('etrack/finance/purchase_order_issued_view', $data);
        echo view('templates/footer_view');
    }

    public function vendors()
    {
        $data = [];
        helper(['form']);
        $data['all_vendors'] = $this->Finance_model->get_all_vendors();

        echo view('templates/header_view', $data);
        echo view('etrack/finance/vendors', $data);
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

        $data['data_value'] = $this->Attendance_model->teamAttendance(1, $data['start_date'], $data['end_date']);
        $data['return_page'] = 4;
        $_SESSION['return_page'] = 4;
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

    public function approve_grace()
    {
        $data = [];
        helper(['form']);
        $data['all_grace'] = $this->Leave_model->getallgrace();
        echo view('templates/header_view', $data);
        echo view('etrack/finance/biz_grace', $data);
        echo view('templates/footer_view');
    }

    public function view_vendor_details()
    {
        $data = [];
        helper(['form']);
        $data['vendor_id'] = $_POST['vendor_id'];
        if (isset($_POST['year'])) {
            $data['year'] = $_POST['year'];
        } else {
            $data['year'] = date('Y');
        }
        $data['vendor_details'] = $this->Finance_model->get_vendor_details_by_ID($data['vendor_id']);
        $data['vendor_payments'] = $this->Finance_model->get_vendor_payments_by_ID($data['vendor_id'], $data['year']);
        echo view('templates/header_view', $data);
        echo view('etrack/finance/vendors_details', $data);
        echo view('templates/footer_view');
    }

    public function reject_grace()
    {
        $grace_id = $_POST['grace_id'];
        $approve_type = $_POST['approve_type'];
        $newdata = [
            'biz_updated_by' => session()->get('id_user'),
            'biz_updated_on' => time(),
            'remarks_biz' => $_POST['biz_remarks'],
            'bz_status' => $approve_type
        ];
        $this->Leave_model->update_grace_data($newdata, $grace_id);
        session()->setFlashdata('success', lang('Messages.Success_0008'));
        return redirect()->to(base_url() . 'etrack/Fin_admin/approve_grace');
    }

    public function approve_claim()
    {
        if ($_POST['vd_id']) {
            $vd_id = $_POST['vd_id'];
            $newdata = [
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
                'status' => $_POST['status']
            ];
            $this->Finance_model->approve_claim_stat($newdata, $vd_id);
            session()->setFlashdata('success', lang('Messages.Success_0029'));
            return redirect()->to(base_url() . 'etrack/Fin_admin/claim_admin');
        }

        session()->setFlashdata('error', lang('Messages.Error_0003'));
        return redirect()->to(base_url() . 'etrack/Fin_admin/claim_admin');
    }
    public function add_new_vendor()
    {
        if ($_POST['vendor_short_name']) {
            $newdata = [
                'vendor_short_name' => $_POST['vendor_short_name'],
                'full_comp_name' => $_POST['full_comp_name'],
                'address_01' => $_POST['address_01'],
                'address_02' => $_POST['address_02'],
                'address_03' => $_POST['address_03'],
                'contact_name_01' => $_POST['contact_name_01'],
                'email_01' => $_POST['email_01'],
                'phone_01' => $_POST['phone_01'],
                'GST' => $_POST['GST'],
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
                'status' => 1
            ];
            $this->Finance_model->add_data_to_vendor($newdata);
            session()->setFlashdata('success', 'New Vendor Added.');
            return redirect()->to(base_url() . 'etrack/Fin_admin/vendors');
        }

        session()->setFlashdata('error', lang('Messages.Error_0003'));
        return redirect()->to(base_url() . 'etrack/Fin_admin/vendors');
    }

    public function update_vendor_details()
    {
        if ($_POST['vendor_id']) {
            $vendor_id = $_POST['vendor_id'];
            $newdata = [
                'vendor_short_name' => $_POST['vendor_short_name'],
                'full_comp_name' => $_POST['full_comp_name'],
                'address_01' => $_POST['address_01'],
                'address_02' => $_POST['address_02'],
                'address_03' => $_POST['address_03'],
                'contact_name_01' => $_POST['contact_name_01'],
                'email_01' => $_POST['email_01'],
                'phone_01' => $_POST['phone_01'],
                'contact_name_02' => $_POST['contact_name_02'],
                'email_02' => $_POST['email_02'],
                'phone_02' => $_POST['phone_02'],
                'GST' => $_POST['GST'],
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user')
            ];
            $this->Finance_model->update_vendor_data($newdata, $vendor_id);
            session()->setFlashdata('success', 'Vendor Data Updated.');
            return redirect()->to(base_url() . 'etrack/Fin_admin/vendors');
        }

        session()->setFlashdata('error', lang('Messages.Error_0003'));
        return redirect()->to(base_url() . 'etrack/Fin_admin/vendors');
    }
}
