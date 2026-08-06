<?php

namespace App\Controllers\Meeting;

use App\Controllers\BaseController;
use App\Models\Settings\Dropdown_model;
use App\Models\Dashboard\dashboard_v1_model;
use App\Models\User_login\Login_model;
use App\Models\Settings\Event_model;
use App\Models\Meeting\Meeting_model;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xls;
#[\AllowDynamicProperties]
class Meeting_agenda extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->dropdown_model = new Dropdown_model();
        $this->dashboard_v1_model = new Dashboard_v1_model();
        $this->login_model = new Login_model();
        $this->event_model = new Event_model();
        $this->meeting_model = new Meeting_model();
    }
    public function index() //fetch data from users table to display
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url() . '/');
        } else {
            $data = [];
            helper(['form']);
            $data['projectid'] = $_GET['projectid'];
            $getmyassignment = $this->dashboard_v1_model->getprojectmyassignment($data['projectid']);
            $data['getmyassignment'] = isset($getmyassignment['data']) ? $getmyassignment['data'] : '';
            $user = session()->get('username');
            if ($data['getmyassignment'][0]['users'] !=  $user) {
                session()->setFlashdata('error', 'You donot have access for this Project');
                return redirect()->to(base_url('dashboard'));
            } else {
                $data['meetingagendaheader'] = $this->meeting_model->meetingagendaheader_view($data['projectid']);
                echo view('templates/header_view', $data);
                echo view('meeting/add_agenda_view', $data);
                echo view('templates/footer_view');
            }
        }
    }

    public function addagenda_header()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url() . '/');
        } else {
            $data = [];
            helper(['form']);
            $projectid = $_GET['projectid'];
            $data['projectid'] = $projectid;
            $data['meetingagendaheader'] = $this->meeting_model->meetingagendaheader_view($data['projectid']);
           if ($this->request->getPost()) {
                $rulesData = [
                    'start_date' => 'required',
                    'time' => 'required',
                    'description' => 'required',
                    // 'attendees' => 'required',
                    //'end_date'=>'required|startdt_check[start_date,end_date]',

                ];
                $errors = [
                    'end_date' => [
                        'startdt_check' => 'End date should be greater than Start date'
                    ]
                ];
                if (!$this->validate($rulesData, $errors)) {
                    $data['validationData'] = $this->validator;
                } else {

                    $timestamp = time();
                    $duration = $this->request->getVar('duration');
                    $start_date = $this->request->getVar('start_date');
                    //print_r($start_date);
                    //exit();
                    $newdata = [
                        'fk_project_id' => $projectid,
                        'start_date' => date('Y-m-d h:i:s', strtotime($start_date)),
                        'time' =>  $this->request->getVar('time'),
                        'description' => $this->request->getVar('description'),
                        // 'attendees' => $this->request->getVar('attendees'),
                        // 'meeting_link' => $this->request->getVar('meeting_link'),
                        'createdon' => $timestamp,
                        'createdby' => session()->get('id_user'),
                        'status' => '1',
                        'type' => '1',
                        'last_updated_by' =>  session()->get('id_user'),
                        'last_updated_on' => time(),
                    ];
                    //print_r($newdata);

                    $result = $this->meeting_model->addmeetingagenda($newdata);
                    if ($result) {
                        session()->setFlashdata('success', lang('Messages.Success_0011'));
                        return redirect()->to(base_url() . '/Meeting/meeting_agenda?projectid=' . $projectid);
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0003'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                        return redirect()->to(base_url() . '/meeting_agenda?projectid=' . $projectid);
                    }
                }
            }
            echo view('templates/header_view', $data);
            echo view('meeting/add_agenda_view', $data);
            echo view('templates/footer_view');
        }
    }
    public function editagenda_header()
    {
        if (isset($_POST['projectid'])) {
            $data['projectid'] = $_POST['projectid'];
            $_SESSION['projectid'] =  $data['projectid'];
        } else if (isset($_GET['projectid'])) {
            $data['projectid'] = $_GET['projectid'];
        } else if (isset($_SESSION['projectid'])) {
            $data['projectid'] = $_SESSION['projectid'];
        }
        if (isset($_POST['id_m'])) {
            $data['id_m'] = $_POST['id_m'];
            $_SESSION['id_m'] =  $data['id_m'];
        } else if (isset($_GET['id_m'])) {
            $data['id_m'] = $_GET['id_m'];
        } else if (isset($_SESSION['id_m'])) {
            $data['id_m'] = $_SESSION['id_m'];
        }
        $getmyassignment = $this->dashboard_v1_model->getprojectmyassignment($data['projectid']);
        $data['getmyassignment'] = isset($getmyassignment['data']) ? $getmyassignment['data'] : '';
        $user = session()->get('username');
        if ($data['getmyassignment'][0]['users'] !=  $user) {
            session()->setFlashdata('error', 'You donot have access for this Project');
            return redirect()->to(base_url('dashboard'));
        } else {
            $data['projectusers'] = $this->meeting_model->getprojectusers($data['projectid']);
            $meetingagendaheader = $this->meeting_model->editagenda_header($data['id_m']);
            $data['row'] = $meetingagendaheader[0];
            $data['meetingagendadata'] = $this->meeting_model->meeting_agenda_view($data['id_m']);
            echo view('templates/header_view', $data);
            echo view('meeting/edit_agenda_view', $data);
            echo view('templates/footer_view');
        }
    }
    public function add_attendees()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url() . '/');
        } else {
            $data = [];
            helper(['form']);
            $projectid = $_GET['projectid'];
            $data['id_m'] = $_GET['id_m'];
            $data['projectid'] = $projectid;
            $data['meetingagendaheader'] = $this->meeting_model->meetingagendaheader_view($data['projectid']);
           if ($this->request->getPost()) {
                $rulesData = [
                    'attendees' => 'required',
                    //'end_date'=>'required|startdt_check[start_date,end_date]',

                ];

                if (!$this->validate($rulesData)) {
                    $data['validationattendees'] = $this->validator;
                } else {
                    $attendees =  $this->request->getVar('attendees');
                    $attendeesstring = implode(', ', $attendees);
                    $newdata = [
                        'attendees' =>  $attendeesstring,
                        'last_updated_by' =>  session()->get('id_user'),
                        'last_updated_on' => time(),
                    ];
                    $result = $this->meeting_model->updateagenda_header($newdata, $data['id_m']);
                    if ($result) {
                        session()->setFlashdata('success', lang('Messages.Success_0011'));
                        return redirect()->to(base_url() . '/meeting_agenda/editagenda_header');
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0003'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                        return redirect()->to(base_url() . '/meeting_agenda/editagenda_header');
                    }
                }
            }
            echo view('templates/header_view', $data);
            echo view('meeting/add_agenda_view', $data);
            echo view('templates/footer_view');
        }
    }
    public function updateagenda_header()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url() . '/');
        } else {
            $data = [];
            helper(['form']);
            $data['id_m'] = $_GET['id_m'];
            $data['projectid'] = $_GET['projectid'];
            $meetingagendaheader = $this->meeting_model->editagenda_header($data['id_m']);
            $data['projectusers'] = $this->meeting_model->getprojectusers($data['projectid']);
            $data['meetingagendaheader'] = $this->meeting_model->meetingagendaheader_view($data['projectid']);
            $data['meetingagendadata'] = $this->meeting_model->meeting_agenda_view($data['id_m']);
            $data['row'] = $meetingagendaheader[0];
           if ($this->request->getPost()) {
                $rulesData = [
                    'start_date' => 'required',
                    'time' => 'required',
                    'description' => 'required',
                    'last_updated_by' =>  session()->get('id_user'),
                    'last_updated_on' => time(),
                    //'end_date'=>'required|startdt_check[start_date,end_date]',
                ];

                if (!$this->validate($rulesData)) {
                    $data['validationData'] = $this->validator;
                } else {

                    $timestamp = time();
                    $duration = $this->request->getVar('duration');
                    $start_date = $this->request->getVar('start_date');
                    //print_r($start_date);
                    //exit();
                    $newdata = [
                        'start_date' => date('Y-m-d h:i:s', strtotime($start_date)),
                        'description' => $this->request->getVar('description'),
                        'time' => $this->request->getVar('time'),
                        'meeting_link' => $this->request->getVar('meeting_link'),
                        'createdon' => $timestamp,
                        'createdby' => session()->get('id_user'),
                        'status' => '1',
                        'last_updated_by' =>  session()->get('id_user'),
                        'last_updated_on' => time(),
                    ];
                    //print_r($newdata);

                    $result = $this->meeting_model->updateagenda_header($newdata, $data['id_m']);
                    if ($result) {
                        session()->setFlashdata('success', lang('Messages.Success_0008'));
                        return redirect()->to(base_url() . '/meeting_agenda/editagenda_header');
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0003'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                        return redirect()->to(base_url() . '/meeting_agenda/editagenda_header');
                    }
                }
            }
            echo view('templates/header_view', $data);
            echo view('meeting/edit_agenda_view', $data);
            echo view('templates/footer_view');
        }
    }
    public function delagenda_header()
    {
        $sessionData =  session();
        $timestamp = time();
        $projectid = $this->request->getVar('projectid');
        $id_m = $this->request->getVar('id_m');
        $newdata = [
             
             
            'status' => '0',
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->meeting_model->delagendaheader($newdata, $id_m);
        if ($result) {
            $sessionData->setFlashdata('success', 'Deleted Successfully');
            return redirect()->to(base_url() . '/meeting_agenda?projectid=' . $projectid);
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . '/meeting_agenda?projectid=' . $projectid);
        }
    }
    public function addprojectstatus()
    {
        $data = [];
        helper(['form']);
        //$data['id_m'] = $_GET['id_m'];
        $data['id_m'] = $_GET['id_m'];
        $data['projectid'] = $_GET['projectid'];
        $data['meetingagendadata'] = $this->meeting_model->meeting_agenda_view($data['id_m']);
        $meetingagendaheader = $this->meeting_model->editagenda_header($data['id_m']);
        $data['row'] = $meetingagendaheader[0];
       if ($this->request->getPost()) {
            $rulesData = [
                'discussion' => 'required',
            ];

            if (!$this->validate($rulesData)) {
                $data['validationagenda'] = $this->validator;
            } else {

                $timestamp = time();
                $completion_dt = $this->request->getVar('completion_dt');
                $remarksdata = $this->request->getVar('remarks');
                $remarks = isset($remarksdata) ? $remarksdata : '';
                // print_r($completion_dt);
                // exit();
                $newdata = [
                    'fk_id_m' => $data['id_m'],
                    'completion_dt' => ($completion_dt != '') ? date('Y-m-d', strtotime($completion_dt)) : '',
                    'project_status' => $this->request->getVar('discussion'),
                    'remarks' => $remarks,
                    'createdon' => $timestamp,
                    'createdby' => session()->get('id_user'),
                    'status' => '1',
                    'last_updated_by' =>  session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                $result = $this->meeting_model->addprojectstatus($newdata);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0008'));
                    return redirect()->to(base_url() . '/meeting_agenda/editagenda_header');
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . '/meeting_agenda/editagenda_header');
                }
            }
        }
        echo view('templates/header_view', $data);
        echo view('meeting/edit_agenda_view', $data);
        echo view('templates/footer_view');
    }
    function meeting_agenda_edit()
    {
        $data = [];
        if (isset($_POST['id_ma'])) {
            $data['id_ma'] = $_POST['id_ma'];
            $_SESSION['id_ma'] =  $data['id_ma'];
        } else if (isset($_GET['id_ma'])) {
            $data['id_ma'] = $_GET['id_ma'];
        } else if (isset($_SESSION['id_ma'])) {
            $data['id_ma'] = $_SESSION['id_ma'];
        }
        $meeting_agenda = $this->meeting_model->meeting_agenda_edit($data['id_ma']);
        $data['row'] = $meeting_agenda[0];
        echo view('templates/header_view', $data);
        echo view('meeting/edit_agenda_disccussion', $data);
        echo view('templates/footer_view');
    }
    public function updatemeetingagenda()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url() . '/');
        } else {
            $data = [];
            helper(['form']);
            $data['id_ma'] = $_GET['id_ma'];
            $meeting_agenda = $this->meeting_model->meeting_agenda_edit($data['id_ma']);
            $data['row'] = $meeting_agenda[0];

           if ($this->request->getPost()) {
                $rulesData = [
                    'discussion' => 'required',
                ];

                if (!$this->validate($rulesData)) {
                    $data['validationData'] = $this->validator;
                } else {

                    $timestamp = time();
                    $completion_dt = $this->request->getVar('completion_dt');
                    $remarksdata = $this->request->getVar('remarks');
                    $remarks = isset($remarksdata) ? $remarksdata : '';
                    // print_r($completion_dt);
                    // exit();
                    $newdata = [
                        'completion_dt' => ($completion_dt != '') ? date('Y-m-d', strtotime($completion_dt)) : '',
                        'project_status' => $this->request->getVar('discussion'),
                        'remarks' => $remarks,
                        'last_updated_by' =>  session()->get('id_user'),
                        'last_updated_on' => time(),
                    ];
                    $result = $this->meeting_model->updatemeetingagenda($newdata, $data['id_ma']);
                    if ($result) {
                        session()->setFlashdata('success', lang('Messages.Success_0008'));
                        return redirect()->to(base_url() . '/meeting_agenda/meeting_agenda_edit');
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0003'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                        return redirect()->to(base_url() . '/meeting_agenda/meeting_agenda_edit');
                    }
                }
            }
            echo view('templates/header_view', $data);
            echo view('meeting/edit_agenda_disccussion', $data);
            echo view('templates/footer_view');
        }
    }
    public function delmeeting_agenda()
    {
        $sessionData =  session();
        $timestamp = time();
        $projectid = $this->request->getVar('projectid');
        $id_m = $this->request->getVar('id_m');
        $id_ma = $this->request->getVar('id_ma');
        $newdata = [
             
             
            'status' => '0',
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        //print_r($newdata);
        $result = $this->meeting_model->delmeetingagenda($newdata, $id_ma);
        if ($result) {
            $sessionData->setFlashdata('success', 'Deleted Successfully');
            return redirect()->to(base_url() . '/meeting_agenda/editagenda_header');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . '/meeting_agenda/editagenda_header');
        }
    }
    public function copyagenda_header()
    {
        $projectid = $this->request->getVar('projectid');
        $id_m = $this->request->getVar('id_m');
        $result = $this->meeting_model->copyagenda_header($id_m);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0035'));
            return redirect()->to(base_url() . '/meeting_agenda?projectid=' . $projectid);
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . '/meeting_agenda?projectid=' . $projectid);
        }
    }
    public function export_meeting_agenda()
    {
        $data['id_m'] = $_GET['id_m'];
        $data['projectid'] = $_GET['projectid'];
        $meetingagenda = $this->meeting_model->meetingagendaclient_view($data['id_m']);
        //print_r($data['meetingagenda']);
        $rowCount = 4;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(10);
        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(30);
        $sheet->setCellValue('A1', 'Date:');
        $sheet->setCellValue('A2', 'Attendees:');
        $sheet->setCellValue('C1', 'Time:');
        $sheet->setCellValue('A3', '#');
        $sheet->setCellValue('B3', 'Project Status and Topics for discussion');
        $sheet->setCellValue('C3', 'Completion Date');
        $sheet->setCellValue('D3', 'Remarks');

        $date = date('m-d-Y', strtotime($meetingagenda[0]['start_date']));
        $time = 'IST ' . $meetingagenda[0]['time'];
        $attendees = $meetingagenda[0]['attendees'];
        $meeting_link = $meetingagenda[0]['meeting_link'];

        $sheet->setCellValue('B1', $date);
        $sheet->setCellValue('B2', $attendees);
        $sheet->setCellValue('D1', $time);
        $k = 0;
        foreach ($meetingagenda as $eachmeetingagenda) {
            if (!empty($eachmeetingagenda['project_status'])) {
                $k = $k + 1;
                $project_status = strip_tags($eachmeetingagenda['project_status']);
                if ($eachmeetingagenda['completion_dt'] != '0000-00-00') {
                    $completion_dt = date('m-d-Y', strtotime($eachmeetingagenda['completion_dt']));
                } else {
                    $completion_dt = " ";
                }
                $sheet->setCellValue('A' . $rowCount, $k);
                $sheet->setCellValue('B' . $rowCount, $project_status);
                $sheet->setCellValue('C' . $rowCount, $completion_dt);
                $sheet->setCellValue('D' . $rowCount, $eachmeetingagenda['remarks']);
                $rowCount = $rowCount + 1;
            }
        }

        $styleboldArray = [
            'font' => [
                'bold' => true,
            ],
        ];

        $filename = $meetingagenda[0]['projectname'] . '_Meeting_agenda.xls';
        $writer = new xls($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $filename);
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    public function exportall_meeting_agenda()
    {
        $data['projectid'] = $_GET['projectid'];
        $meetingagenda = $this->meeting_model->projectmeetingagenda($data['projectid']);
        $meetingagendastatus = $this->meeting_model->meetingagendastatus($data['projectid']);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $row = 1; // 1-based index
        $col = 0;
        foreach ($meetingagenda as $eachmeetingagenda) {
            $date = date('m-d-Y', strtotime($eachmeetingagenda['start_date']));
            $time = 'IST ' . $eachmeetingagenda['time'];
            $attendees = $eachmeetingagenda['attendees'];
            $sheet->setCellValue('A' . $row, 'Date:' . $date);
            $col++;
            $sheet->setCellValue('B' . $row, 'Time:' . $time);
            $col++;
            $row++;
            $sheet->setCellValue('A' . $row, 'Attendees:' . $attendees);
            $col++;
            $row++;
            $sheet->setCellValue('A' . $row, '#');
            $col++;
            $sheet->setCellValue('B' . $row, 'Project Status and Topics for discussion');
            $col++;
            $sheet->setCellValue('C' . $row, 'Completion Date');
            $col++;
            $sheet->setCellValue('D' . $row, 'Remarks');
            $col++;
            $row++;
            $k = 0;
            if (count($meetingagendastatus) > 0) {
                foreach ($meetingagendastatus  as $eachmeetingagendastatus) {
                    if (!empty($eachmeetingagendastatus['project_status'])) {
                        if ($eachmeetingagenda['id_m'] == $eachmeetingagendastatus['id_m']) {
                            $k = $k + 1;
                            $project_status = strip_tags($eachmeetingagendastatus['project_status']);
                            $sheet->setCellValue('A' . $row, $k);
                            $sheet->setCellValue('B' . $row, $project_status);
                            $sheet->setCellValue('C' . $row, $eachmeetingagendastatus['completion_dt']);
                            $sheet->setCellValue('D' . $row, $eachmeetingagendastatus['remarks']);
                            $row++;
                        }
                        $col++;
                        $col = 0;
                    }
                }
            }
        }
        $filename = $meetingagenda[0]['projectname'] . '_Meeting_agenda.xls';
        $writer = new xls($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $filename);
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    public function generalagenda()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url() . '/');
        } else {
            $data = [];
            helper(['form']);
            $data['meetingagendaheader'] = $this->meeting_model->meetingallagendaheader_view();
            echo view('templates/header_view', $data);
            echo view('general_meeting/add_agenda_view', $data);
            echo view('templates/footer_view');
        }
    }
    public function addgeneralagenda_header()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url() . '/');
        } else {
            $data = [];
            helper(['form']);
            // $client = session()->get('client');
            $data['meetingagendaheader'] = $this->meeting_model->meetingallagendaheader_view();
           if ($this->request->getPost()) {
                $rulesData = [
                    'start_date' => 'required',
                    'time' => 'required',
                    'description' => 'required',
                    // 'attendees' => 'required',
                    //'end_date'=>'required|startdt_check[start_date,end_date]',
                    'last_updated_by' =>  session()->get('id_user'),
                    'last_updated_on' => time(),

                ];
                $errors = [
                    'end_date' => [
                        'startdt_check' => 'End date should be greater than Start date'
                    ]
                ];
                if (!$this->validate($rulesData, $errors)) {
                    $data['validationData'] = $this->validator;
                } else {

                    $timestamp = time();
                    $duration = $this->request->getVar('duration');
                    $start_date = $this->request->getVar('start_date');
                    //print_r($start_date);
                    //exit();
                    $newdata = [
                        'fk_project_id' => session()->get('client'),
                        'start_date' => date('Y-m-d h:i:s', strtotime($start_date)),
                        'time' =>  $this->request->getVar('time'),
                        'description' => $this->request->getVar('description'),
                        // 'attendees' => $this->request->getVar('attendees'),
                        // 'meeting_link' => $this->request->getVar('meeting_link'),
                        'createdon' => $timestamp,
                        'createdby' => session()->get('id_user'),
                        'status' => '1',
                        'type' => '2',
                        'last_updated_by' =>  session()->get('id_user'),
                        'last_updated_on' => time(),
                    ];
                    //print_r($newdata);

                    $result = $this->meeting_model->addmeetingagenda($newdata);
                    if ($result) {
                        session()->setFlashdata('success', lang('Messages.Success_0011'));
                        return redirect()->to(base_url() . '/meeting_agenda/generalagenda');
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0003'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                        return redirect()->to(base_url() . 'meeting_agenda/generalagenda');
                    }
                }
            }
            echo view('templates/header_view', $data);
            echo view('general_meeting/add_agenda_view', $data);
            echo view('templates/footer_view');
        }
    }
}
