<?php

namespace App\Controllers\Project_Manage;

use App\Controllers\BaseController;
use App\Models\Project_Manage\PM_ucn_model;
use App\Models\Project_Manage\PM_pricing_sheet_model;
use App\Models\User_login\Users_model;
use App\Models\SCORM\Scorm_course_model;

#[\AllowDynamicProperties]
class PM_wip extends BaseController
{
    protected $PM_ucn_model;
    protected $PM_pricing_sheet_model;
    protected $users_model;
    protected $scorm_course_model;
    public function __construct()
    {
        $this->is_session_available();
        $this->PM_ucn_model = new PM_ucn_model();
        $this->PM_pricing_sheet_model = new PM_pricing_sheet_model();
        $this->users_model = new Users_model();
        $this->scorm_course_model = new Scorm_course_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url());
            exit();
        }
        $arrayuserlevel = explode(',', $userlevel);
        if (!in_array('69', $arrayuserlevel) && !in_array('3014', $arrayuserlevel)) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }
    public function index()
    {
        if ($response =  $this->requireRole(['69', '3014'])) {
            return $response;
        }
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
            $data['percent_data'] = $this->PM_ucn_model->get_percent_wip($data['year']);
            // print_r($data['wip_list']);
            // exit();
            if (empty($data['wip_list'])) {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
                return redirect()->to(base_url() . 'Project_Manage/PM_wip');
            }
        } else {
            $data['year'] = date("Y");
            $data['wip_list'] = $this->PM_ucn_model->get_wip_yearlist($data['year']);
            $data['percent_data'] = $this->PM_ucn_model->get_percent_wip($data['year']);
            // $data['wip_list'] = $this->PM_ucn_model->get_wip_list();
        }
        echo view('templates/header_view', $data);
        echo view('project_management/wip_dashboard_view', $data);
        echo view('templates/footer_view');
    }

    public function Download_WIP_Report()
    {
        helper(['form']);
        if (!empty($_POST['year'])) {
            $year = $this->request->getPost('year');
            if ($response =  $this->requireRole(['69', '3014'])) {
                return $response;
            }

            $wip_list = $this->PM_ucn_model->get_wip_yearlist($year);
            $percent_data = $this->PM_ucn_model->get_percent_wip($year);
            // print_r($percent_data);
            // exit;
            // Create lookup array using ucn_id
            $percent_lookup = [];

            foreach ($percent_data as $item) {
                $percent_lookup[$item['ucn_id']] = $item;
            }
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('WIP-' . $year);

            // Header row
            $sheet->setCellValue('A1', 'UCN')
                ->setCellValue('B1', 'Name')
                ->setCellValue('C1', 'Client')
                ->setCellValue('D1', 'PM')
                ->setCellValue('E1', 'PO')
                ->setCellValue('F1', 'START')
                ->setCellValue('G1', 'DEC')
                ->setCellValue('H1', 'JAN')
                ->setCellValue('I1', 'FEB')
                ->setCellValue('J1', 'MAR')
                ->setCellValue('K1', 'APR')
                ->setCellValue('L1', 'MAY')
                ->setCellValue('M1', 'JUN')
                ->setCellValue('N1', 'JUL')
                ->setCellValue('O1', 'AUG')
                ->setCellValue('P1', 'SEP')
                ->setCellValue('Q1', 'OCT')
                ->setCellValue('R1', 'NOV')
                ->setCellValue('S1', 'DEC')
                ->setCellValue('T1', 'Status');


            // Header style
            $headerStyle = $sheet->getStyle('A1:F1');
            $headerStyle->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
            $headerStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF000000');

            $headerStyle = $sheet->getStyle('G1:G1');
            //$headerStyle->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
            $headerStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFCCCC');

            $headerStyle = $sheet->getStyle('H1:T1');
            //$headerStyle->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
            $headerStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('ADD8E6');
            // $headerStyle->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            // $sheet->freezePane('A2');

            // Write data
            $row = 2;
            $count = 1;
            $po_value_array = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

            foreach ($wip_list as $data) {
                $percent = $percent_lookup[$data['ucn_id']] ?? [];
                $prev_percent = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
                if (isset($data['month_0_percent']) && $data['month_0_percent'] > 99) {
                    continue;
                }
                $ucn =  $data['ucn_id'];
                if ($ucn == 896 || $ucn == 897) {
                    continue;
                }
                $po_value_array[0] = $po_value_array[0] + $data['po_value'];
                if ($data['status'] == 10) {
                    $status = 'Closed';
                } else {
                    $status = 'Open';
                }

                $sheet->setCellValue('A' . $row, $data['ucn_id'])
                    ->setCellValue('B' . $row, $data['name'])
                    ->setCellValue('C' . $row, $data['client_name'])
                    ->setCellValue('D' . $row, $data['project_manager'])
                    ->setCellValue('E' . $row, $data['po_value'])
                    ->setCellValue('F' . $row, $data['start_dt'])
                    ->setCellValue('G' . $row, $percent['month_0_percent'] ?? '')
                    ->setCellValue('H' . $row, $percent['month_1_percent'] ?? '')
                    ->setCellValue('I' . $row, $percent['month_2_percent'] ?? '')
                    ->setCellValue('J' . $row, $percent['month_3_percent'] ?? '')
                    ->setCellValue('K' . $row, $percent['month_4_percent'] ?? '')
                    ->setCellValue('L' . $row, $percent['month_5_percent'] ?? '')
                    ->setCellValue('M' . $row, $percent['month_6_percent'] ?? '')
                    ->setCellValue('N' . $row, $percent['month_7_percent'] ?? '')
                    ->setCellValue('O' . $row, $percent['month_8_percent'] ?? '')
                    ->setCellValue('P' . $row, $percent['month_9_percent'] ?? '')
                    ->setCellValue('Q' . $row, $percent['month_10_percent'] ?? '')
                    ->setCellValue('R' . $row, $percent['month_11_percent'] ?? '')
                    ->setCellValue('S' . $row, $percent['month_12_percent'] ?? '')
                    ->setCellValue('T' . $row, $status);

                // Wrap text for description & objectives
                // $sheet->getStyle('G' . $row . ':H' . $row)->getAlignment()->setWrapText(true);

                $row++;
            }

            // Auto-size columns
            foreach (range('A', 'T') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Download
            $filename = 'WIP_Export_year' . $year . '_' . date('Y-m-d') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header('Cache-Control: max-age=0');

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            exit();
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            return redirect()->to(base_url() . 'Project_Manage/PM_wip');
        }
    }
}
