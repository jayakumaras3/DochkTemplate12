<?php

namespace App\Controllers\Project;

use App\Controllers\BaseController;

use App\Models\Settings\Dropdown_model;
use App\Models\User_login\Login_model;
use App\Models\Settings\Event_model;
use App\Models\Project\Projects_model;
use DateTime;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xls;

#[\AllowDynamicProperties]
class Project_plan extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->dropdown_model = new Dropdown_model();
        $this->login_model = new Login_model();
        $this->event_model = new Event_model();
        $this->projects_model = new Projects_model();
    }

    public function index() //fetch data from users table to display
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url() . '/');
        } else {
            $data = [];
            helper(['form']);
            if (isset($_POST['projectid'])) {
                $data['projectid'] = $_POST['projectid'];
                $_SESSION['projectid'] = $data['projectid'];
            } else if (isset($_GET['projectid'])) {
                $data['projectid'] = $_GET['projectid'];
            } else if (isset($_SESSION['projectid'])) {
                $data['projectid'] = $_SESSION['projectid'];
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0003'));
                return redirect()->to(base_url() . '/Project/projects');
            }
            $data['projectDetails'] = $this->projects_model->getProjectDetails($data['projectid']);
            $data['clientdata'] = $this->event_model->getprojectclient($data['projectid']);
            $data['headerdata'] = $this->event_model->getheaderdata($data['projectid']);
            $data['dealtimelineData'] = $this->event_model->dealtimeline_view($data['projectid']);
            $data['leveldata'] = $this->dropdown_model->getCountrylist(10);
            $data['templatedata'] = $this->dropdown_model->getCountrylist(9);
            $data['templatedetailsdata'] = $this->event_model->projectPlanData($data['projectid']);
            echo view('templates/header_view', $data);
            echo view('project_plan/project_plan_view', $data);
            echo view('templates/footer_view');
        }
    }
    function addheader()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url() . '/');
        } else {
            $data = [];
            helper(['form']);
            if (isset($_POST['projectid'])) {
                $data['projectid'] = $_POST['projectid'];
                $_SESSION['projectid'] = $data['projectid'];
            } else if (isset($_GET['projectid'])) {
                $data['projectid'] = $_GET['projectid'];
            } else if (isset($_SESSION['projectid'])) {
                $data['projectid'] = $_SESSION['projectid'];
            }
            //$data['clientdata'] = $this->dropdown_model->getclientData();
            $data['projectDetails'] = $this->projects_model->getProjectDetails($data['projectid']);
            $data['clientdata'] = $this->event_model->getprojectclient($data['projectid']);
            $data['leveldata'] = $this->dropdown_model->getCountrylist(10);
            $data['dealtimelineData'] = $this->event_model->dealtimeline_view($data['projectid']);
            $data['templatedata'] = $this->dropdown_model->getCountrylist(9);
            $data['headerdata'] = $this->event_model->getheaderdata($data['projectid']);
            $data['templatedetailsdata'] = $this->event_model->projectPlanData($data['projectid']);
            if ($this->request->getPost()) {
                $rulesData = [
                    'header_name' => 'required',

                ];

                if (!$this->validate($rulesData)) {
                    $data['validationData'] = $this->validator;
                } else {

                    $timestamp = time();
                    $duration = $this->request->getVar('duration');
                    $start_date = $this->request->getVar('start_date');
                    $newdata = [
                        'fk_course_id' => $data['projectid'],
                        'header_name' => $this->request->getVar('header_name'),
                        'createdon' => $timestamp,
                        'createdby' => session()->get('id_user'),
                        'status' => '1',
                    ];
                    //print_r($newdata);

                    $result = $this->event_model->addheaderdata($newdata);
                    if ($result) {
                        session()->setFlashdata('success', lang('Messages.Success_0011'));
                        return redirect()->to(base_url() . '/Project/project_plan');
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0003'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                        return redirect()->to(base_url() . '/Project/project_plan');
                    }
                }
            }
            echo view('templates/header_view', $data);
            echo view('project_plan/project_plan_view', $data);
            echo view('templates/footer_view');
        }
    }
    function editplanheader_view()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url() . '/');
        } else {
            if (isset($_POST['projectid']) && isset($_POST['course_id'])) {
                $data['projectid'] = $_POST['projectid'];
                $data['course_id'] = $_POST['course_id'];
                $_SESSION['projectid'] = $data['projectid'];
                $_SESSION['course_id'] = $data['course_id'];
            } else if (isset($_GET['projectid']) && isset($_GET['course_id'])) {
                $data['projectid'] = $_GET['projectid'];
                $data['course_id'] = $_GET['course_id'];
            } else if (isset($_SESSION['projectid']) && isset($_SESSION['course_id'])) {
                $data['projectid'] = $_SESSION['projectid'];
                $data['course_id'] = $_SESSION['course_id'];
            }
            if (isset($_POST['id_ph'])) {
                $data['id_ph'] = $_POST['id_ph'];
                $_SESSION['id_ph'] = $data['id_ph'];
            } else if (isset($_GET['id_ph'])) {
                $data['id_ph'] = $_GET['id_ph'];
            } else if (isset($_SESSION['id_ph'])) {
                $data['id_ph'] = $_SESSION['id_ph'];
            }
            // $data['course_id'] = $_GET['course_id'];
            // $data['projectid'] = $_GET['projectid'];
            // $data['id_ph'] = $_GET['id_ph'];
            $data['projectDetails'] = $this->projects_model->getProjectDetails($data['projectid']);
            $geteachheaderdata = $this->event_model->geteachheaderdata($data['id_ph']);
            $data['row'] = $geteachheaderdata[0];
            echo view('templates/header_view', $data);
            echo view('project_plan/headerplan_edit_view', $data);
            echo view('templates/footer_view');
        }
    }
    function editplanheader()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url() . '/');
        } else {
            $data = [];
            helper(['form']);
            $course_id = $_POST['course_id'];
            $projectid = $_POST['projectid'];
            $data['course_id'] = $course_id;
            $data['projectid'] = $projectid;
            $data['id_ph'] = $_POST['id_ph'];
            //$data['clientdata'] = $this->dropdown_model->getclientData();
            $data['projectDetails'] = $this->projects_model->getProjectDetails($data['projectid']);
            $data['clientdata'] = $this->event_model->getprojectclient($projectid);
            $data['leveldata'] = $this->dropdown_model->getCountrylist(10);
            $data['dealtimelineData'] = $this->event_model->dealtimeline_view($data['projectid']);
            $data['templatedata'] = $this->dropdown_model->getCountrylist(9);
            $data['headerdata'] = $this->event_model->getheaderdata($data['projectid']);
            $data['templatedetailsdata'] = $this->event_model->projectPlanData($data['projectid']);
            if ($this->request->getPost()) {
                $rulesData = [
                    'header_name' => 'required',

                ];

                if (!$this->validate($rulesData)) {
                    $data['validationData'] = $this->validator;
                } else {

                    $timestamp = time();
                    $duration = $this->request->getVar('duration');
                    $start_date = $this->request->getVar('start_date');
                    $newdata = [
                        'fk_course_id' => $course_id,
                        'header_name' => $this->request->getVar('header_name'),
                        'createdon' => $timestamp,
                        'createdby' => session()->get('id_user'),
                        'status' => '1',
                    ];
                    //print_r($newdata);

                    $result = $this->event_model->editheaderdata($newdata, $data['id_ph']);
                    if ($result) {
                        //session()->setFlashdata('success', lang('Messages.Success_0008'));
                        return redirect()->to(base_url() . '/Project/project_plan');
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0003'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                        return redirect()->to(base_url() . '/Project/project_plan');
                    }
                }
            }
            echo view('templates/header_view', $data);
            echo view('project_plan/project_plan_view', $data);
            echo view('templates/footer_view');
        }
    }
    function deleteplanheader_view()
    {
        $course_id = $_POST['course_id'];
        $projectid = $_POST['projectid'];
        $id_ph = $_POST['id_ph'];
        $newdata = ['id_ph' => $id_ph, 'status' => '0'  ];
        // $data['templatedetailsdata'] = $this->event_model->projectPlanData($course_id);
        $result = $this->event_model->editheaderdata($newdata, $id_ph);
        if ($result) {
            $sessionData = session();
            $sessionData->setFlashdata('success', 'Timeline item : Deleted Successful');
            return redirect()->to(base_url() . '/Project/project_plan');
        }
        session()->setFlashdata('error', lang('Messages.Error_0001'));
        session()->setFlashdata('alert-class', 'alert-danger');
        return redirect()->to(base_url() . '/Project/project_plan');
    }
    function addtimeline()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url() . '/');
        } else {
            $data = [];
            helper(['form']);
            $course_id = $_POST['course_id'];
            $projectid = $_POST['projectid'];
            $header = $_POST['header'];
            $data['course_id'] = $course_id;
            $data['projectid'] = $projectid;
            $data['projectDetails'] = $this->projects_model->getProjectDetails($data['projectid']);
            //$data['clientdata'] = $this->dropdown_model->getclientData();
            $data['clientdata'] = $this->event_model->getprojectclient($projectid);
            $data['leveldata'] = $this->dropdown_model->getCountrylist(10);
            $data['dealtimelineData'] = $this->event_model->dealtimeline_view($course_id);
            $data['templatedata'] = $this->dropdown_model->getCountrylist(9);
            $data['headerdata'] = $this->event_model->getheaderdata($course_id);
            $data['templatedetailsdata'] = $this->event_model->projectPlanData($data['course_id']);
            if ($this->request->getPost()) {
                $rulesData = [
                    'item_type' => 'required|max_length[32]',
                    'item_description' => 'required|max_length[100]',
                    //'completion' => 'required|max_length[10]',
                    'duration' => 'required|max_length[10]',
                    'start_date' => 'required',
                    //'end_date'=>'required|startdt_check[start_date,end_date]',

                ];
                $errors = [
                    'end_date' => [
                        'startdt_check' => 'End date should be greater than Start date'
                    ]
                ];
                if (!$this->validate($rulesData, $errors)) {
                    $data['planvalidation'] = $this->validator;
                } else {

                    $timestamp = time();
                    $duration = $this->request->getVar('duration');
                    $start_date = $this->request->getVar('start_date');
                    $d = new DateTime($start_date);
                    $t = $d->getTimestamp();
                    // loop for X days
                    for ($i = 0; $i < $duration; $i++) {

                        // add 1 day to timestamp
                        $addDay = 86400;

                        // get what day it is next day
                        $nextDay = date('w', ($t + $addDay));

                        // if it's Saturday or Sunday get $i-1
                        if ($nextDay == 0 || $nextDay == 6) {
                            $i--;
                        }

                        // modify timestamp, add 1 day
                        $t = $t + $addDay;
                    }

                    $d->setTimestamp($t);

                    $end_date = $d->format('Y-m-d');
                    // exit();

                    $newdata = [
                        'fk_course_id' => $course_id,
                        'header' => $header,
                        'item_type' => $this->request->getVar('item_type'),
                        'item_description' => $this->request->getVar('item_description'),
                        'completion' => $this->request->getVar('completion'),
                        'duration' => $this->request->getVar('duration'),
                        'start_date' => $this->request->getVar('start_date'),
                        'end_date' => $end_date,
                        'level' => 39,
                        'createdon' => $timestamp,
                        'createdby' => session()->get('id_user'),
                        'status' => '1',
                    ];
                    //print_r($newdata);

                    $result = $this->event_model->addtimeline($newdata);
                    if ($result) {
                        session()->setFlashdata('success', lang('Messages.Success_0011'));
                        return redirect()->to(base_url() . 'Project/project_plan');
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0003'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                        return redirect()->to(base_url() . 'Project/project_plan');
                    }
                }
            }
            echo view('templates/header_view', $data);
            echo view('project_plan/project_plan_view', $data);
            echo view('templates/footer_view');
        }
    }
    function updatedateformat()
    {
        $value = $_POST['value'];
        $column = $_POST['column'];
        $id = $_POST['id'];
        //print_r($value);
        //print_r($column);
        //print_r($id);
        // exit();
        $result = $this->event_model->updatedateformat($value, $column, $id);
        echo json_encode($result);
    }
    function updatesortplan()
    {
        $allData = $_POST['allData'];
        $result = $this->event_model->updatesortplan($allData);
        echo json_encode($result);
    }
    function deletedealtimeline()
    {
        $dt_id = $_POST['dt_id'];
        $course_id = $_POST['course_id'];
        $projectid = $_POST['projectid'];
        $newdata = ['dt_id' => $dt_id, 'status' => '0'  ];
        // $data['templatedetailsdata'] = $this->event_model->projectPlanData($course_id);
        $result = $this->event_model->deleteDealtimeline($newdata, $dt_id);
        if ($result) {
            $sessionData = session();
            $sessionData->setFlashdata('success', 'Timeline item : Deleted Successful');
            return redirect()->to(base_url() . '/Project/project_plan');
        }
        session()->setFlashdata('error', lang('Messages.Error_0001'));
        session()->setFlashdata('alert-class', 'alert-danger');
        return redirect()->to(base_url() . '/Project/project_plan');
    }
    function dealhistorytimeline()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url() . '/');
        } else {
            // print_r($_POST);
            // exit();
            if (isset($_POST['projectid']) && isset($_POST['course_id'])) {
                $data['projectid'] = $_POST['projectid'];
                $data['course_id'] = $_POST['course_id'];
                $_SESSION['projectid'] = $data['projectid'];
                $_SESSION['course_id'] = $data['course_id'];
            } else if (isset($_GET['projectid']) && isset($_GET['course_id'])) {
                $data['projectid'] = $_GET['projectid'];
                $data['course_id'] = $_GET['course_id'];
            } else if (isset($_SESSION['projectid']) && isset($_SESSION['course_id'])) {
                $data['projectid'] = $_SESSION['projectid'];
                $data['course_id'] = $_SESSION['course_id'];
            }
            if (isset($_POST['dt_id'])) {
                $data['dt_id'] = $_POST['dt_id'];
                $_SESSION['dt_id'] = $data['dt_id'];
            } else if (isset($_GET['dt_id'])) {
                $data['dt_id'] = $_GET['dt_id'];
            } else if (isset($_SESSION['dt_id'])) {
                $data['dt_id'] = $_SESSION['dt_id'];
            }
            //  print_r($data['course_id']);
            //  exit();
            //  $data['projectid'] = $projectid;
            $data['dealhistory'] = $this->event_model->getdealhistory($data['dt_id']);
            $eachdealtimeline = $this->event_model->geteachdealtimeline($data['dt_id']);
            $data['row'] = $eachdealtimeline['0'];
            echo view('templates/header_view', $data);
            echo view('project_plan/projectplan_history_view', $data);
            echo view('templates/footer_view');
        }
    }
    function graph($course_id)
    {
        $data = [];
        helper(['form']);
        $data['dealtimelineData'] = $this->event_model->dealtimeline_view($course_id);
        $data['headerdata'] = $this->event_model->getheaderdata($course_id);
        // print_r($data['dealtimelineData']);
        echo view('project_plan/graph_view', $data);
        echo view('templates/footer_view');
    }
    // function userprojectplan($course_id)
    // {
    //     $data = [];
    //     helper(['form']);
    //     $data['course_id'] = $_GET['course_id'];
    //     $data['projectid'] = $_GET['projectid'];
    //     //$data['clientdata'] = $this->dropdown_model->getclientData();
    //     $data['clientdata'] = $this->dropdown_model->getCountrylist(12);
    //     $data['headerdata'] = $this->event_model->getheaderdata($data['course_id']);
    //     $data['dealtimelineData'] = $this->event_model->dealtimeline_view($data['course_id']);
    //     $data['leveldata'] = $this->dropdown_model->getCountrylist(10);
    //     $data['templatedata'] = $this->dropdown_model->getCountrylist(9);
    //     $data['templatedetailsdata'] = $this->event_model->projectPlanData($data['course_id']);
    //     echo view('templates/header_view', $data);
    //     echo view('project_plan/project_planuser_view', $data);
    //     echo view('templates/footer_view');
    // }
    function addtemplate()
    {
        $courseid = $_GET['course_id'];
        $projectid = $_GET['projectid'];
        $data['course_id'] = $courseid;
        $data['projectid'] = $projectid;
        $template_type = $this->request->getVar('template_type');
        //$data['clientdata'] = $this->dropdown_model->getclientData();
        $data['clientdata'] = $this->event_model->getprojectclient($projectid);
        $data['templatedata'] = $this->dropdown_model->getCountrylist(9);
        $data['dealtimelineData'] = $this->event_model->dealtimeline_view($data['course_id']);
        $data['templatedetailsdata'] = $this->event_model->projectPlanData($data['course_id']);
        $result = $this->event_model->insetTemplate($courseid, $template_type);
        if ($result) {
            $session = session();
            $session->setFlashdata('success', 'Updated successfully');
            return redirect()->to(base_url() . '/project_plan?projectid=' . $projectid . '&course_id=' . $courseid);
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . '/project_plan?projectid=' . $projectid . '&course_id=' . $courseid);
        }
        echo view('templates/header_view', $data);
        echo view('project_plan/project_plan_view', $data);
        echo view('templates/footer_view');
    }
    function link()
    {
        $data = [];
        if (isset($_POST['projectid'])) {
            $data['projectid'] = $_POST['projectid'];
            $_SESSION['projectid'] = $data['projectid'];
        } else if (isset($_GET['projectid'])) {
            $data['projectid'] = $_GET['projectid'];
        } else if (isset($_SESSION['projectid'])) {
            $data['projectid'] = $_SESSION['projectid'];
        }
        if (isset($_POST['dt_id'])) {
            $data['dt_id'] = $_POST['dt_id'];
            $_SESSION['dt_id'] = $data['dt_id'];
        } else if (isset($_GET['dt_id'])) {
            $data['dt_id'] = $_GET['dt_id'];
        } else if (isset($_SESSION['dt_id'])) {
            $data['dt_id'] = $_SESSION['dt_id'];
        }
        $data['itemdescription'] = $this->event_model->getitemdescription($data['projectid'], $data['dt_id']);
        //print_r($data['itemdescription'] );
        //exit();
        echo view('templates/header_view', $data);
        echo view('project_plan/course_link_view', $data);
        echo view('templates/footer_view');
    }
    function bulknewpage()
    {
        if ($this->request->getPost()) {
            $projectid = $this->request->getVar('projectid');
            $courseid = $this->request->getVar('course_id');
            $rules = [
                'file' => 'uploaded[file]|max_size[file,1024]|ext_in[file,csv,xls,xlsx]',
            ];
            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        // Get random file name
                        $newfilename = $file->getRandomName();
                        $a = FCPATH . '/assets/assets/uploads';
                        $file->move(FCPATH . '/assets/assets/uploads/project_plan/' . $projectid . '/' . $courseid, $newfilename);
                        $filepath = FCPATH . 'assets/assets/uploads/project_plan/' . $projectid . '/' . $courseid . '/' . $newfilename;
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
                        //$count = count($sheetData);
                        // print_r($count);
                        //echo "<pre>";
                        // print_r($sheetData);
                        $clientdata = $this->event_model->getprojectclient($projectid);
                        $result = $this->event_model->importnewpage($courseid, $sheetData, $clientdata);
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
                        return redirect()->to(base_url() . '/Project/project_plan');
                    }
                }
            }
        }
    }
    function updateitemlink($projectid, $dt_id)
    {
        if (!session()->get('isLoggedIn')) {

            return redirect()->to(base_url() . '/');
        } else {
            $data = [];
            helper(['form']);
            if ($this->request->getPost()) {
                $rulesData = [
                    'link' => 'required',
                ];

                if (!$this->validate($rulesData)) {
                    $data['validationData'] = $this->validator;
                } else {
                    $newdata = [
                        'link' => $this->request->getVar('link'),
                    ];
                    //print_r($newdata);
                    $result = $this->event_model->updatelinkId($newdata, $projectid, $dt_id);
                    if ($result) {

                        session()->setFlashdata('success',lang('Messages.Success_0011'));
                        return redirect()->to(base_url() . '/Project/project_plan/link');
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0003'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                        return redirect()->to(base_url() . '/Project/project_plan/link');
                    }
                }
            }
        }
    }
    function newexportcomments()
    {
        if (isset($_POST['course_id'])) {
            $course_id = $_POST['course_id'];
            $rowCount = 2;
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'Id');
            $sheet->setCellValue('B1', 'Item Type');
            $sheet->setCellValue('C1', 'Header');
            $sheet->setCellValue('D1', 'Description');
            $sheet->setCellValue('E1', 'completion');
            $sheet->setCellValue('F1', 'Start Date');
            $sheet->setCellValue('G1', 'Duration');
            $sheet->setCellValue('H1', 'Start Day');
            $sheet->setCellValue('I1', 'End Date');
            $sheet->setCellValue('J1', 'status');
            $sheet->setCellValue('K1', 'level');
            $sheet->setCellValue('L1', 'link');

            $projectplandata = $this->event_model->getProjectPlanData($course_id);
            // print_r($projectplandata);
            // exit();
            if (!empty($projectplandata)) {
                if (count($projectplandata) > 0) {
                    foreach ($projectplandata as $projectplaneachdata) {
                        $dt_id = $projectplaneachdata['dt_id'];
                        $fk_course_id = $projectplaneachdata['projectname'];
                        $itemtypename = $projectplaneachdata['itemtypename'];
                        //print_r($itemtypename);
                        $header_name = $projectplaneachdata['header_name'];
                        $item_description = $projectplaneachdata['item_description'];
                        $completion = $projectplaneachdata['completion'];
                        $duration = $projectplaneachdata['duration'];
                        $start_day = $projectplaneachdata['start_day'];
                        $note = $projectplaneachdata['note'];
                        $status = $projectplaneachdata['status'];
                        $start_date = $projectplaneachdata['start_date'];
                        $end_date = $projectplaneachdata['end_date'];
                        $level = $projectplaneachdata['level'];
                        if ($projectplaneachdata['link'] != 0) {
                            $link = $projectplaneachdata['link'];
                        } else {
                            $link = '';
                        }
                        $createdby = $projectplaneachdata['username'];
                        $createdon = date('Y-m-d', $projectplaneachdata['createdon']);


                        $sheet->setCellValue('A' . $rowCount, $dt_id);
                        $sheet->SetCellValue('B' . $rowCount, $itemtypename);
                        $sheet->setCellValue('C' . $rowCount, $header_name);
                        $sheet->setCellValue('D' . $rowCount, $item_description);
                        $sheet->SetCellValue('E' . $rowCount, $completion);
                        $sheet->SetCellValue('F' . $rowCount, $start_date);
                        $sheet->SetCellValue('G' . $rowCount, $duration);
                        $sheet->SetCellValue('H' . $rowCount, $start_day);
                        $sheet->SetCellValue('I' . $rowCount, $end_date);
                        $sheet->SetCellValue('J' . $rowCount, $status);
                        $sheet->SetCellValue('K' . $rowCount, $level);
                        $sheet->SetCellValue('L' . $rowCount, $link);
                        $rowCount = $rowCount + 1;
                    }
                }

                $datetoday = $projectplaneachdata['projectname'];
                $datetoday = $datetoday . '_' . date("d-m-Y");
                $datetoday = str_replace(',', '_', $datetoday);
                $datetoday = preg_replace('/\s+/', '_', $datetoday);
                $filename = trim($datetoday) . '.xls';
                $writer = new xls($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="Project_Plan"' . $filename);
                header('Cache-Control: max-age=0');
                $writer->save('php://output');
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0003'));
                return redirect()->to(base_url() . '/Project/project_plan');
            }
        }
    }
    function ajaxprojectplansubmit()
    {
        if (!empty($_POST['item'])) {
            $allPageArray = $_POST['item'];
            $result = $this->event_model->addpagesequence($allPageArray);
            echo json_encode($result);
        }
    }
}
