<?php

namespace App\Controllers\Assessment;

use App\Controllers\BaseController;

use App\Models\Assessment\Assessment_training_model;
use App\Models\SCORM\Scorm_course_model;
use App\Models\Settings\Dropdown_model;
use App\Models\SCORM\Scorm_page_model;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Protection;

use Config\AssessmentSets\Assessment_english;
use Config\AssessmentSets\Assessment_french;
use Config\AssessmentSets\Assessment_spanish;
use Config\AssessmentSets\Assessment_russian;
use Config\AssessmentSets\Assessment_portuguese;
use Config\AssessmentSets\Assessment_bahasa;
use Config\AssessmentSets\Assessment_arabic;


#[\AllowDynamicProperties]
class Trainings extends BaseController
{
    private $db;

    public function __construct()
    {
        //$this->is_session_available();
        $this->assessment_training_model = new Assessment_training_model();
        $this->scorm_course_model = new Scorm_course_model();
        $this->dropdown_model = new Dropdown_model();
        $this->scorm_page_model = new Scorm_page_model();
    }
    private function is_session_available()
    {
        // $userlevel = session()->get('userlevel');
        // if (empty($userlevel)) {
        //     header('Location:' . base_url('my_training'));
        //     exit();
        // }

        // $arrayuserlevel = explode(',', $userlevel);
        // if (!in_array('6', $arrayuserlevel) && !in_array('67', $arrayuserlevel)) {
        //     session()->setFlashdata('error', lang('Messages.Error_0004'));
        //     header('Location:' . base_url('my_training'));
        //     exit();
        // }
    }
    public function index()
    {

        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $data['header'] = 'Assessment';
        $data['create_new_course_link'] = 'Assessment/trainings/course_add_view';
        $data['settings_link'] = 'Assessment/trainings/course_settings_view';
        $data['edit_link'] = 'Assessment/trainings/course_edit_view';
        $data['question_list_view'] = 'Assessment/trainings/question_list_view';
        $data['delete_link'] = '';
        $data['typeval'] = 8;
        $data['coursesDetails'] = $this->scorm_course_model->getCoursesDetails($data['typeval']);
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/courses_list_view', $data);
        echo view('templates/footer_view');
    }
    public function course_add_view()
    {
        $data = [];
        helper(['form']);
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        $data['header'] = 'Course Builder';
        $data['header_link'] = 'SCORM/course_builder/Editor';
        $data['sub_header_1'] = 'Create New Assessment';
        $data['form_link'] = 'Assessment/trainings/addcourse';

        $data['typeval'] = 8;

        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/courses_add_view', $data);
        echo view('templates/footer_view');
    }
    public function addcourse()
    {

        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $user = session()->get('username');

        if ($this->request->getPost()) {
            //print_r("sss");
            $rules = [
                'course_name' => 'required',
            ];

            if (!$this->validate($rules)) {
                $data['coursevalidation'] = $this->validator;
            } else {

                $newdata = [
                    'client_id' => session()->get('client_id'),
                    'course_name' => $this->request->getVar('course_name'),
                    'description' => $this->request->getVar('description'),
                    'objectives' => $this->request->getVar('objectives'),
                    'duration' => $this->request->getVar('duration'),
                    'language' => $this->request->getVar('language'),
                    'course_code' => $this->request->getVar('course_code'),
                    'type' => '8',
                    'mode' => 1,
                    'status' => '1',
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),

                ];
                $enabled_default = array(1, 2, 3);
                $result = $this->scorm_course_model->addcoursedetails($newdata);
                // print_r($result);
                // exit();
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0008'));
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
            return redirect()->to(base_url() . 'Assessment/trainings');
        }
    }
    public function course_edit_view()
    {
        $data = [];
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);

        $data['header'] = 'Course Builder';
        $data['header_link'] = 'SCORM/course_builder/Editor';
        $data['sub_header_1'] = 'Edit Assessment';
        $data['form_link'] = 'Assessment/trainings/editcourse';

        $data['typeval'] = 8;
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings');
        }
        $getCourseData = $this->scorm_course_model->getCourseDetails($data['scourse_id']);
        $data['row'] = $getCourseData[0];
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/courses_edit_view', $data);
        echo view('templates/footer_view');
    }
    public function editcourse()
    {
        $data = [];
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $user = session()->get('username');
        $scourse_id = $this->request->getVar('scourse_id');
        $data['scourse_id'] = $scourse_id;
        if ($this->request->getPost()) {
            $rules = [
                'course_name' => 'required',
                'description' => 'required',

            ];
            if (!$this->validate($rules)) {

                $data['courseeditvalidation'] = $this->validator;
            } else {
                $newdata = [
                    'language' => $this->request->getVar('language'),
                    'course_code' => $this->request->getVar('course_code'),
                    'scourse_id' => $this->request->getVar('scourse_id'),
                    'course_name' => $this->request->getVar('course_name'),
                    'description' => $this->request->getVar('description'),
                    'objectives' => $this->request->getVar('objectives'),
                    'duration' => $this->request->getVar('duration'),
                    'upload_type' => $this->request->getVar('upload_type'),
                    'mode' => $this->request->getVar('mode'),
                    'launch_link' => $this->request->getVar('launch_link'),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];

                $result = $this->scorm_course_model->editcoursedetails($newdata, $scourse_id);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0008'));
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
            return redirect()->to(base_url() . 'Assessment/trainings/course_edit_view');
        }
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/courses_edit_view', $data);
        echo view('templates/footer_view');
    }
    public function question_list_view()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];

        // print_r($_POST);
        // exit();
        $user = session()->get('username');
        if (isset($_POST['scourse_id']) && isset($_POST['page_name'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
            $data['page_name'] = $_POST['page_name'];
            $_SESSION['page_name'] = $data['page_name'];
        } else if (isset($_GET['scourse_id']) && isset($_GET['page_name'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
            $data['page_name'] = $_GET['page_name'];
        } else if (isset($_SESSION['scourse_id']) && isset($_SESSION['page_name'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
            $data['page_name'] = $_SESSION['page_name'];
        } else {
            return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
        }
        if (isset($_POST['page_id'])) {
            $data['page_id'] = $_POST['page_id'];
            $data['page_number'] = $_POST['page_number'] ?? null;
            $_SESSION['page_id'] = $data['page_id'];
            $_SESSION['page_number'] = $data['page_number'];
        } elseif (isset($_SESSION['page_id']) || isset($_SESSION['page_number'])) {
            $data['page_id'] = $_SESSION['page_id'] ?? null;
            $data['page_number'] = $_SESSION['page_number'] ?? null;
            // $data['question_id'] = $_SESSION['question_id'];
        }
        $data['editpage'] = 'Assessment/trainings/editpage';
        $data['editsubpage'] = 'Assessment/trainings/editcyupage';
        $data['main_header'] = 'Course Detail';
        $data['main_header_link'] = 'my_training/read_more';
        $data['header'] = 'Pages';
        $data['header_link'] = 'SCORM/course_builder/Editor';
        $data['sub_header_1'] = $data['page_name'] . ' - Questions';
        $data['edit_link'] = 'Assessment/trainings/edit_quetion_view';
        $data['delete_link'] = 'Assessment/trainings/deleteQuestion';
        $data['setting_link'] = 'Assessment/trainings/add_option_view';
        $data['copyQuestion_link'] = 'Assessment/trainings/copyQuestiondetails';
        $data['typeval'] = 8;
        $data['getQuestiondata'] = $this->assessment_training_model->getQuestiondata($data['scourse_id'], $data['page_id']);
        $data['pagetype'] = $this->assessment_training_model->getpagetype($data['page_id']);

        $pagedata = $this->scorm_page_model->getpagedata_number($data['page_number'], $data['scourse_id']);
        $data['pagerow'] = $pagedata[0];

        $getQuestiondatax = $this->assessment_training_model->getQuestionDetails_byQID($data['pagerow']['page_id'], $data['scourse_id']);
        // print_r($getQuestiondatax);
        // exit();
        $data['question_id'] = isset($getQuestiondatax[0]['q_id']) ? $getQuestiondatax[0]['q_id'] : '';
        if ($data['question_id'] != '') {
            $getQuestiondata = $this->assessment_training_model->geteditquestiondetails($data['question_id']);
            $data['row'] = $getQuestiondata[0];
            $data['page_content'] = $this->scorm_page_model->getpagecontent($data['page_number'], $data['scourse_id']);

            $data['page_id'] = $getQuestiondata[0]['page_id'];
        }
        $data['AssessmentQuestionType'] = $this->dropdown_model->getCountrylist(21);
        $data['allcategories'] = $this->scorm_course_model->getAllMetadata(12);
        $data['getoptiondata'] = $this->assessment_training_model->getoptiondaata($data['question_id']);
        $data['CategoryData'] = $this->dropdown_model->getCountrylist(20);


        $currentpagenum = $data['pagerow']['page_number'];
        $fk_course_id = $data['pagerow']['fk_course_id'];
        $data['scourse_id'] = $fk_course_id;
        $prepage = $currentpagenum - 1;
        $nextpage = $currentpagenum + 1;

        $data['prev_page'] = $this->scorm_page_model->get_nxt_page($prepage, $fk_course_id);
        $data['next_page'] = $this->scorm_page_model->get_nxt_page($nextpage, $fk_course_id);

        echo view('templates/header_view', $data);
        echo view('assessment/question_list_view', $data);
        echo view('templates/footer_view');
    }
    public function assignmetacategory()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $newData = [
            'fk_scourse_id' => $_POST['scourse_id'],
            'fk_sc_mcid' => $_POST['metaCategory'],
            'typeofval' => $_POST['typeofval'],
            'status' => 1,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_course_model->assignmetacategorydata($newData);
        echo json_encode($result);
    }
    public function deleteasignmetadetails()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $mc_id = $_POST['mc_id'];
        $newdata = [


            'status' => '0',
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_course_model->deleteassignmeta($newdata, $mc_id);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
            return redirect()->to(base_url() . 'Assessment/trainings/course_edit_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'Assessment/trainings/course_edit_view');
        }
    }
    public function thumbnail_upload()
    {
        $data = [];
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['filesystem']);
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        }

        if ($this->request->getPost()) {
            $rules = [
                'file' => 'uploaded[file]|max_size[file,150]|ext_in[file,jpg]'
            ];
            if (!$this->validate($rules)) {
                $data['thumbnailvalidation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();
                        if (!is_dir(FCPATH . 'assets/assets/uploads/SCORM_course_thumbnail/' . $data['scourse_id'])) {
                            mkdir('assets/assets/uploads/SCORM_course_thumbnail/' . $data['scourse_id'], 0777, true);
                        }
                        if (file_exists(FCPATH . 'assets/assets/uploads/SCORM_course_thumbnail/' . $data['scourse_id'] . "/" . $filename)) {
                            session()->setFlashdata('error', $filename . ' ' . lang('Messages.Success_0051'));
                        } else {
                            if ($file->move(FCPATH . 'assets/assets/uploads/SCORM_course_thumbnail/' . $data['scourse_id'], $filename)) {
                                $filepath = FCPATH . 'assets/assets/uploads/SCORM_course_thumbnail/' . $data['scourse_id'] . '/' . $filename;
                                $newdata = [
                                    'scourse_id' => $data['scourse_id'],
                                    'thumbnail' => $filename,
                                ];
                                $result = $this->scorm_course_model->editcoursedetails($newdata, $data['scourse_id']);
                                if ($result) {
                                    session()->setFlashdata('success', lang('Messages.Success_0009'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                } else {
                                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                }
                            }
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                    }
                }
            }
            // session()->setFlashdata('error', lang('Messages.Error_0003'));
            // session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . 'Assessment/trainings/course_settings_view');
    }
    public function uploadpromovideo()
    {
        $data = [];
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        helper(['filesystem']);
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        }
        if ($this->request->getPost()) {
            $rules = [
                'file' => 'uploaded[file]|max_size[file,20480]|ext_in[file,mp4]'
            ];
            if (!$this->validate($rules)) {
                $data['promovalidation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();
                        if (!is_dir(FCPATH . 'assets/assets/uploads/SCORM_course_promovideo/' . $data['scourse_id'])) {
                            mkdir('assets/assets/uploads/SCORM_course_promovideo/' . $data['scourse_id'], 0777, true);
                        }
                        if (file_exists(FCPATH . 'assets/assets/uploads/SCORM_course_promovideo/' . $data['scourse_id'] . "/" . $filename)) {
                            session()->setFlashdata('error', $filename . ' ' . lang('Messages.Success_0051'));
                            return redirect()->to(base_url() . 'Assessment/trainings/course_settings_view');
                        } else {

                            if ($file->move(FCPATH . 'assets/assets/uploads/SCORM_course_promovideo/' . $data['scourse_id'], $filename)) {
                                $filepath = FCPATH . 'assets/assets/uploads/SCORM_course_promovideo/' . $data['scourse_id'] . '/' . $filename;
                                $newdata = [
                                    'promo_video' => $filename,
                                ];
                                $result = $this->scorm_course_model->editcoursedetails($newdata, $data['scourse_id']);
                                if ($result) {
                                    session()->setFlashdata('success', lang('Messages.Success_0009'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                } else {
                                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                }
                            }
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                    }
                }
            }
            // session()->setFlashdata('error', lang('Messages.Error_0003'));
            // session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . 'Assessment/trainings/course_settings_view');
    }
    public function uploadpdf()
    {
        // print_r('rrr');
        // exit();
        $data = [];
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['filesystem']);
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        }
        if ($this->request->getPost()) {
            $rules = [
                'file' => 'uploaded[file]|max_size[file,20480]|ext_in[file,pdf]'
            ];
            if (!$this->validate($rules)) {
                $data['pdfvalidation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();
                        if (!is_dir(FCPATH . 'assets/assets/uploads/SCORM_course_pdf/' . $data['scourse_id'])) {
                            mkdir('assets/assets/uploads/SCORM_course_pdf/' . $data['scourse_id'], 0777, true);
                        }
                        if (file_exists(FCPATH . 'assets/assets/uploads/SCORM_course_pdf/' . $data['scourse_id'] . "/" . $filename)) {
                            session()->setFlashdata('error', $filename . ' ' . lang('Messages.Success_0051'));
                            return redirect()->to(base_url() . '/Assessment/trainings/course_settings_view');
                        } else {

                            if ($file->move(FCPATH . 'assets/assets/uploads/SCORM_course_pdf/' . $data['scourse_id'], $filename)) {
                                $filepath = FCPATH . 'assets/assets/uploads/SCORM_course_pdf/' . $data['scourse_id'] . '/' . $filename;
                                $newdata = [
                                    'pdf_filename' => $filename,
                                ];
                                $fileupload = [
                                    'description' => $_POST['description'],
                                    'course_id' => $data['scourse_id'],
                                    'folder' => $filename,
                                    'status' => 1,
                                    'createdby' => session()->get('id_user'),
                                    'createdon' => time(),
                                    'last_updated_by' => session()->get('id_user'),
                                    'last_updated_on' => time(),
                                ];
                                $this->scorm_course_model->insertFileuploaddata($fileupload);

                                $result = $this->scorm_course_model->editcoursedetails($newdata, $data['scourse_id']);
                                if ($result) {

                                    session()->setFlashdata('success', lang('Messages.Success_0009'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                } else {
                                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                }
                            }
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                    }
                }
                // session()->setFlashdata('error', lang('Messages.Error_0003'));
                // session()->setFlashdata('alert-class', 'alert-danger');
            }
        }
        return redirect()->to(base_url() . 'Assessment/trainings/course_settings_view');
    }



    public function del_file()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        if (isset($_POST['fileloc'])) {
            $dirPath = $_POST['fileloc'];
            unlink($dirPath);
            session()->setFlashdata('success', lang('Messages.Success_0010'));
        }
        return redirect()->to(base_url() . 'Assessment/trainings/course_settings_view');
    }
    // public function delquestion_file()
    // {
    //     if (isset($_POST['fileloc'])) {
    //         $dirPath = $_POST['fileloc'];
    //         if (file_exists($dirPath)) {
    //             if (!unlink($dirPath)) {
    //                 session()->setFlashdata('error', lang('Messages.Error_0006') . $dirPath);
    //             }
    //         } else {
    //             // session()->setFlashdata('error', 'File does not exist: ' . $fileloc);
    //             // unlink($dirPath);
    //         }
    //         // unlink($dirPath);
    //         $qa_id = $this->request->getVar('qa_id');
    //         $newdata = [
    //             
    //              
    //             'status' => 0,
    //             'last_updated_by' => session()->get('id_user'),
    //             'last_updated_on' => time(),
    //         ];
    //         $result = $this->assessment_training_model->delete_question_attachments($newdata, $qa_id);
    //         return json_encode($result);
    //     }

    //     // return redirect()->to(base_url() . 'Assessment/trainings/edit_quetion_view');
    // }
    // public function delquestion_file()
    // {
    //     $qa_id = $this->request->getVar('qa_id');
    //     // print_r($q_id);
    //     // exit();
    //     if (!$qa_id) {
    //         session()->setFlashdata('error', 'Invalid request');
    //         return redirect()->to(base_url() . '/Assessment/trainings/add_quiz_option_view');
    //     }

    //     // Get question data
    //     $getCoursedetailsforq_id = $this->assessment_training_model->getCoursedetailsforq_id($qa_id);

    //     if (empty($getCoursedetailsforq_id)) {
    //         session()->setFlashdata('error', 'Data not found');
    //         return redirect()->to(base_url() . '/Assessment/trainings/add_quiz_option_view');
    //     }

    //     $fileData = $getCoursedetailsforq_id[0];

    //     // Base directory (secure root)
    //     $baseDir = FCPATH . 'assets/assets/uploads/SCORM_course_document/';


    //     $folderPath = $baseDir
    //         . $fileData['scourse_id'] . '/'
    //         . $fileData['createdon'] . '/assets/Quiz/'
    //         . $fileData['page_id'] . '/assessment_image/';

    //     // Resolve real paths
    //     $realBase   = realpath($baseDir);
    //     $realFolder = realpath($folderPath);
    //     // print_r($folderPath);
    //     // exit();
    //     //  Check folder exists
    //     if ($realFolder === false || !is_dir($realFolder)) {
    //         session()->setFlashdata('error', 'Folder not found');
    //         return redirect()->to(base_url() . '/Assessment/trainings/add_quiz_option_view');
    //     }

    //     //  Security check (prevent path traversal)
    //     if (strpos($realFolder, $realBase) !== 0) {
    //         session()->setFlashdata('error', 'Unauthorized path');
    //         return redirect()->to(base_url() . '/Assessment/trainings/add_quiz_option_view');
    //     }

    //     //  Ownership check
    //     if ($fileData['createdby'] != session()->get('id_user')) {
    //         session()->setFlashdata('error', 'Access denied');
    //         return redirect()->to(base_url() . '/Assessment/trainings/add_quiz_option_view');
    //     }

    //     // Delete all files in folder
    //     $files = scandir($realFolder);

    //     foreach ($files as $file) {
    //         if ($file !== '.' && $file !== '..') {
    //             $fullPath = $realFolder . '/' . $file;

    //             if (is_file($fullPath)) {
    //                 unlink($fullPath);
    //             }
    //         }
    //     }


    //     $newdata = [
    //         
    //          
    //         'status' => 0,
    //         'last_updated_by' => session()->get('id_user'),
    //         'last_updated_on' => time(),
    //     ];

    //     $result = $this->assessment_training_model->delete_question_attachments($newdata, $qa_id);
    //     if ($result) {
    //         session()->setFlashdata('success', 'All files deleted successfully');
    //         return redirect()->to(base_url() . '/Assessment/trainings/add_quiz_option_view');
    //     } else {
    //         session()->setFlashdata('error', 'Failed to update database');
    //         return redirect()->to(base_url() . '/Assessment/trainings/add_quiz_option_view');
    //     }
    // }
    public function delquestion_file()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $qa_id = $this->request->getVar('qa_id');

        // Validate request
        if (!$qa_id) {
            session()->setFlashdata('error', 'Invalid request');
            return redirect()->to(base_url() . '/Assessment/trainings/add_quiz_option_view');
        }

        // Get question data
        $getCoursedetailsforq_id = $this->assessment_training_model->getCoursedetailsforq_id($qa_id);

        if (empty($getCoursedetailsforq_id)) {
            session()->setFlashdata('error', 'Data not found');
            return redirect()->to(base_url() . '/Assessment/trainings/add_quiz_option_view');
        }

        $fileData = $getCoursedetailsforq_id[0];

        // Base directory (secure root)
        $baseDir = FCPATH . 'assets/assets/uploads/SCORM_course_document/';

        // Build folder path
        $folderPath = $baseDir
            . $fileData['scourse_id'] . '/'
            . $fileData['createdon'] . '/assets/Quiz/'
            . $fileData['page_id'] . '/assessment_image/';

        // Resolve real paths
        $realBase   = realpath($baseDir);
        $realFolder = realpath($folderPath);

        // Check base path
        if ($realBase === false) {
            session()->setFlashdata('error', 'Base path error');
            return redirect()->to(base_url() . '/Assessment/trainings/add_quiz_option_view');
        }

        // Check folder exists
        if ($realFolder === false || !is_dir($realFolder)) {
            session()->setFlashdata('error', 'Folder not found');
            return redirect()->to(base_url() . '/Assessment/trainings/add_quiz_option_view');
        }

        // Security check (prevent path traversal)
        if (strpos($realFolder, $realBase) !== 0) {
            session()->setFlashdata('error', 'Unauthorized path');
            return redirect()->to(base_url() . '/Assessment/trainings/add_quiz_option_view');
        }

        // Ownership check
        if (empty($fileData['createdby']) || $fileData['createdby'] != session()->get('id_user')) {
            session()->setFlashdata('error', 'Access denied');
            return redirect()->to(base_url() . '/Assessment/trainings/add_quiz_option_view');
        }

        // Delete all files in folder (non-recursive)
        $files = array_diff(scandir($realFolder), ['.', '..']);

        foreach ($files as $file) {
            $fullPath = $realFolder . '/' . $file;

            if (is_file($fullPath) && file_exists($fullPath)) {
                unlink($fullPath);
            }
        }

        // Optional: delete folder itself
        // rmdir($realFolder);

        // Log action (recommended)
        log_message('info', 'User ' . session()->get('id_user') . ' deleted files in ' . $realFolder);

        // Soft delete DB record
        $newdata = [
            'status' => 0,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];

        $result = $this->assessment_training_model->delete_question_attachments($newdata, $qa_id);

        // Final response
        if ($result) {
            session()->setFlashdata('success', 'All files deleted successfully');
        } else {
            session()->setFlashdata('error', 'Failed to update database');
        }

        return redirect()->to(base_url() . '/Assessment/trainings/add_quiz_option_view');
    }
    public function add_new_question()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        // print_r($_SESSION);
        // exit();
        if (isset($_POST['scourse_id']) && isset($_POST['page_name'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $data['page_id'];
            $data['page_name'] = $_POST['page_name'];
            $_SESSION['page_name'] = $data['page_name'];
        } else if (isset($_GET['scourse_id']) && isset($_GET['page_name'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
            $data['page_name'] = $_GET['page_name'];
            $data['page_id'] = $_GET['page_id'];
        } else if (isset($_SESSION['scourse_id']) && isset($_SESSION['page_name'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
            $data['page_name'] = $_SESSION['page_name'];
            $data['page_id'] = $_SESSION['page_id'];
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings/question_list_view');
        }
        if (isset($_POST['type'])) {
            $data['type'] = $_POST['type'];
            $_SESSION['type'] = $data['type'];
        } else if (isset($_GET['type'])) {
            $data['type'] = $_GET['type'];
        } else if (isset($_SESSION['type'])) {
            $data['type'] = $_SESSION['type'];
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings/question_list_view');
        }
        $data['main_header'] = 'Course Detail';
        $data['main_header_link'] = 'my_training/read_more';
        $data['header'] = 'Pages';
        $data['header_link'] = 'SCORM/course_builder/Editor';
        $data['header_1'] = 'Questions';
        $data['header_link_1'] = 'Assessment/trainings/question_list_view';
        $data['sub_header_1'] = 'Create New Question';
        $data['form_link'] = 'Assessment/trainings/addQuestions';

        $data['typeval'] = 8;
        $data['allcategories'] = $this->scorm_course_model->getAllMetadata(12);
        $data['CategoryData'] = $this->dropdown_model->getCountrylist(20);
        $data['AssessmentQuestionType'] = $this->dropdown_model->getCountrylist(21);
        echo view('templates/header_view', $data);
        echo view('assessment/add_new_question', $data);
        echo view('templates/footer_view');
    }

    public function addQuestions()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if (isset($_POST['type'])) {
            $data['type'] = $_POST['type'];
            $_SESSION['type'] = $data['type'];
        } else if (isset($_GET['type'])) {
            $data['type'] = $_GET['type'];
        } else if (isset($_SESSION['type'])) {
            $data['type'] = $_SESSION['type'];
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings/question_list_view');
        }
        if ($this->request->getPost()) {
            $rules = [
                'question' => 'required',
            ];

            if (!$this->validate($rules)) {
                $data['coursevalidation'] = $this->validator;
            } else {
                $category = $this->request->getVar('category');
                $category = isset($category) ? $category : '';
                $correct = $this->request->getVar('correct');
                $correct = isset($correct) ? $correct : '';
                $incorrect = $this->request->getVar('incorrect');
                $incorrect = isset($incorrect) ? $incorrect : '';
                $noAttempts = $this->request->getVar('noAttempts');
                $noAttempts = isset($noAttempts) ? $noAttempts : '';
                $quiz_type = $this->request->getVar('quiz_type');
                $quiz_type = isset($quiz_type) ? $quiz_type : '';

                $newdata = [
                    'scourse_id' => $this->request->getVar('scourse_id'),
                    'page_id' => $this->request->getVar('page_id'),
                    'question' => $this->request->getVar('question'),
                    'category' => $category,
                    // 'score' => $this->request->getVar('score'),
                    'quiz_type' => $quiz_type,
                    'correct' => $correct,
                    'incorrect' => $incorrect,
                    'noAttempts' => $noAttempts,
                    'status' => '1',
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),

                ];
                $result = $this->assessment_training_model->addquestiondetails($newdata);
                if ($result) {
                    // Every quiz question needs at least one option, and at least one correct
                    // option, to be answerable - seed one with placeholder text marked correct
                    // rather than leaving the question unanswerable until the user adds one
                    // themselves. Mirrors the same seeding done for new SCQ/MCQ questions and
                    // the same "at least one correct" rule enforced in
                    // Assessment_training_model::updateoptioneditableformat() when the user
                    // later edits/deletes options.
                    $this->assessment_training_model->addoptiondata([
                        'scourse_id' => $this->request->getVar('scourse_id'),
                        'question_id' => $result['question_id'],
                        'values' => 'Option 1',
                        'truefalse' => 1,
                        'status' => '1',
                        'last_updated_by' => session()->get('id_user'),
                        'last_updated_on' => time(),
                    ]);

                    $postData['question_id'] = $result['question_id'];
                    $postData['type'] = $data['type'];
                    session()->setFlashdata('post_data', $postData);
                    session()->setFlashdata('success', lang('Messages.Success_0008'));
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
            if ($_POST['returnUrl'] == 1) {
                return redirect()->to(base_url() . 'Assessment/trainings/add_quiz_option_view/' . $result['question_id']);
                // return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
            } else {
                return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
                //  return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
            }
        }
    }
    public function add_question_bank()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if (isset($_POST['scourse_id']) && isset($_POST['course_name'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
            $data['course_name'] = $_POST['course_name'];
            $_SESSION['course_name'] = $data['course_name'];
        } else if (isset($_GET['scourse_id']) && isset($_GET['course_name'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
            $data['course_name'] = $_GET['course_name'];
        } else if (isset($_SESSION['scourse_id']) && isset($_SESSION['course_name'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
            $data['course_name'] = $_SESSION['course_name'];
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings');
        }
        $data['main_header'] = 'Course Detail';
        $data['main_header_link'] = 'my_training/read_more';
        $data['header'] = 'Course Builder';
        $data['header_link'] = 'SCORM/course_builder/Editor';
        $data['header_1'] = 'Questions';
        $data['header_link_1'] = 'Assessment/trainings/question_list_view';
        $data['sub_header_1'] = 'Add Question from Bank';
        $data['form_link'] = 'Assessment/trainings/addQuestionsBank';

        $data['typeval'] = 9;
        $data['allcategories'] = $this->scorm_course_model->getAllMetadata(12);
        $data['CategoryData'] = $this->dropdown_model->getCountrylist(20);
        $data['AssessmentQuestionType'] = $this->dropdown_model->getCountrylist(21);
        $data['getQuestiondata'] = $this->assessment_training_model->getQuestionbankdata($data['scourse_id'], $data['typeval']);
        $getQuestiondata = $this->assessment_training_model->geteditquestiondetails($data['question_id']);
        $data['row'] = $getQuestiondata[0];
        $data['getoptiondata'] = $this->assessment_training_model->getoptiondaata($data['question_id']);

        echo view('templates/header_view', $data);
        echo view('assessment/add_question_bank', $data);
        echo view('templates/footer_view');
    }

    public function addQuestionsBank()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $data['scourse_id'] = $this->request->getVar('scourse_id');
        $data['question_id'] = $this->request->getVar('q_id');
        $result = $this->assessment_training_model->copyQuestionBank($data['question_id'], $data['scourse_id']);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0011'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . 'Assessment/trainings/question_list_view');
    }
    public function edit_quetion_view()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];

        if (isset($_SESSION['crid'])) {
            $data['scourse_id'] = $_SESSION['crid'];
        } else {
            return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
        }

        if (isset($_POST['page_id'])) {
            $data['page_id'] = $_POST['page_id'];
            $data['page_number'] = $_POST['page_number'] ?? null;
            $_SESSION['page_id'] = $data['page_id'];
            $_SESSION['page_number'] = $data['page_number'];
        } elseif (isset($_SESSION['page_id']) || isset($_SESSION['page_number'])) {
            $data['page_id'] = $_SESSION['page_id'] ?? null;
            $data['page_number'] = $_SESSION['page_number'] ?? null;
            // $data['question_id'] = $_SESSION['question_id'];
        }
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else if (isset($_SESSION['tab'])) {
            $data['tab'] = $_SESSION['tab'];
        } else {
            $data['tab'] = 1;
        }

        $course_lang = $_SESSION['course_lang'];
        if ($course_lang == 'French') {
            $data['assessment_scqmcq_sets'] = Assessment_french::$assessment_scqmcq_sets;
        } elseif ($course_lang == 'Spanish') {
            $data['assessment_scqmcq_sets'] = Assessment_spanish::$assessment_scqmcq_sets;
        } elseif ($course_lang == 'Russian') {
            $data['assessment_scqmcq_sets'] = Assessment_russian::$assessment_scqmcq_sets;
        } elseif ($course_lang == 'Portuguese') {
            $data['assessment_scqmcq_sets'] = Assessment_portuguese::$assessment_scqmcq_sets;
        } elseif ($course_lang == 'Bahasa') {
            $data['assessment_scqmcq_sets'] = Assessment_bahasa::$assessment_scqmcq_sets;
        } elseif ($course_lang == 'Arabic') {
            $data['assessment_scqmcq_sets'] = Assessment_arabic::$assessment_scqmcq_sets;
        } else {
            $data['assessment_scqmcq_sets'] = Assessment_english::$assessment_scqmcq_sets;
        }
        $data['main_header'] = 'Course Detail';
        $data['main_header_link'] = 'my_training/read_more';
        $data['header'] = 'Pages';
        $data['header_link'] = 'SCORM/course_builder/scorm_course_pages/page_edit_view';
        $data['sub_header_1'] = 'Edit Questions';
        $data['header_1'] = 'Questions';
        $data['header_link_1'] = 'Assessment/trainings/question_list_view';
        $data['editquestion'] = 'Assessment/trainings/editquestion';
        $data['typeval'] = 11;
        $data['form_link'] = 'Assessment/trainings/addoption';
        $data['edit_link'] = 'Assessment/trainings/option_edit_view';
        $data['delete_link'] = 'Assessment/trainings/deleteOption';
        $data['form_url_1'] = 'Assessment/trainings/question_image_upload';
        $data['form_url_2'] = 'Assessment/trainings/pdf_upload';
        $data['form_url_3'] = 'Assessment/trainings/video_upload';
        $data['editpage'] = 'Assessment/trainings/editpage';
        $data['editsubpage'] = 'Assessment/trainings/editcyupage';


        // print_r($_SESSION['page_number']);
        // exit();
        $data['getCourseData'] = $this->scorm_course_model->getCourseDetails($data['scourse_id']);
        $pagedata = $this->scorm_page_model->getpagedata_number($data['page_number'], $data['scourse_id']);
        $data['pagerow'] = $pagedata[0];
        // print_r($data['pagerow']);
        // exit();
        $getQuestiondatax = $this->assessment_training_model->getQuestionDetails_byQID($data['pagerow']['page_id'], $data['scourse_id']);
        // print_r($getQuestiondatax);
        // exit();
        if (!empty($getQuestiondatax)) {
            $data['question_id'] = $getQuestiondatax[0]['q_id'];

            $getQuestiondata = $this->assessment_training_model->geteditquestiondetails($data['question_id']);
            $data['row'] = $getQuestiondata[0];
            $data['question_attachment_image'] = $this->assessment_training_model->check_image_file_exists($data['question_id'], 1);
            $data['question_attachment_video'] = $this->assessment_training_model->check_image_file_exists($data['question_id'], 3);
            $data['AssessmentQuestionType'] = $this->dropdown_model->getCountrylist(21);
            $data['allcategories'] = $this->scorm_course_model->getAllMetadata(12);
            $data['getoptiondata'] = $this->assessment_training_model->getoptiondaata($data['question_id']);
            $data['CategoryData'] = $this->dropdown_model->getCountrylist(20);

            $data['page_content'] = $this->scorm_page_model->getpagecontent($data['page_number'], $data['scourse_id']);

            $data['page_id'] = $getQuestiondata[0]['page_id'];
        }
        $currentpagenum = $data['pagerow']['page_number'];
        $fk_course_id = $data['pagerow']['fk_course_id'];
        $data['scourse_id'] = $fk_course_id;
        $prepage = $currentpagenum - 1;
        $nextpage = $currentpagenum + 1;

        $data['prev_page'] = $this->scorm_page_model->get_nxt_page($prepage, $fk_course_id);
        $data['next_page'] = $this->scorm_page_model->get_nxt_page($nextpage, $fk_course_id);

        $data['type'] = 5;
        $data['getAssessmentSettings'] = $this->assessment_training_model->get_question_settings($data['scourse_id'], $data['pagerow']['page_id']);
        $data['AssessmentSettings']['59'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['pagerow']['page_id'], 59);
        $data['AssessmentSettings']['60'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['pagerow']['page_id'], 60);
        $data['AssessmentSettings']['61'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['pagerow']['page_id'], 61);
        $data['AssessmentSettings']['68'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['pagerow']['page_id'], 68);
        echo view('templates/header_view', $data);
        echo view('assessment/question_edit_view', $data);
        echo view('templates/footer_view');
    }
    function editcyupage()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if (isset($_POST['page_id'])) {
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $data['page_id'];
        } else if (isset($_GET['page_id'])) {
            $data['page_id'] = $_GET['page_id'];
        } else if (isset($_SESSION['page_id'])) {
            $data['page_id'] = $_SESSION['page_id'];
        } else {
            return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
        }
        $user = session()->get('username');

        if ($this->request->getPost()) {
            //print_r("sss");
            $rules = [
                'page_name' => 'required',
            ];

            if (!$this->validate($rules)) {
                $data['coursevalidation'] = $this->validator;
            } else {
                $newdata = [
                    'page_name' => $this->request->getVar('page_name'),
                    'sub_page_main' => $this->request->getVar('sub_page_main'),
                    'type' => $this->request->getVar('type'),
                    'status' => $this->request->getVar('status'),
                    'page_number' => $this->request->getVar('page_number'),
                    'last_update_by' => session()->get('id_user'),
                    'last_update_on' => time(),

                ];
                $result = $this->scorm_page_model->editpagedetails($newdata, $data['page_id']);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0008'));
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
        }
        return redirect()->to(base_url('SCORM/course_builder/Editor'));
    }
    public function edit_quiz_quetion_view()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $data['main_header'] = 'Courses';
        $data['main_header_link'] = 'SCORM/scorm_courses';
        $data['header'] = 'Pages';
        $data['header_link'] = 'SCORM/course_builder/Editor';
        $data['sub_header_1'] = 'Edit Questions';
        $data['header_1'] = 'Questions';
        $data['header_link_1'] = 'Assessment/trainings/question_list_view';
        $data['form_link'] = 'Assessment/trainings/editquestion';
        $data['typeval'] = 8;
        if (isset($_POST['question_id'])) {
            $data['question_id'] = $_POST['question_id'];
            $data['type'] = $_POST['type'];
            $_SESSION['question_id'] = $data['question_id'];
            $_SESSION['type'] = $data['type'];
        } else if (isset($_GET['question_id'])) {
            $data['question_id'] = $_GET['question_id'];
        } else if (isset($_SESSION['question_id'])) {
            $data['question_id'] = $_SESSION['question_id'];
        } else {
            return redirect()->to(base_url() . '/Assessment/trainings');
        }
        $getQuestiondata = $this->assessment_training_model->geteditquestiondetails($data['question_id']);
        $data['row'] = $getQuestiondata[0];
        $data['AssessmentQuestionType'] = $this->dropdown_model->getdropdownData(21);
        $data['allcategories'] = $this->scorm_course_model->getAllMetadata(12);
        echo view('templates/header_view', $data);
        echo view('assessment/quiz_question_edit_view', $data);
        echo view('templates/footer_view');
    }
    function editpage()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if (isset($_POST['page_id'])) {
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $data['page_id'];
        } else if (isset($_GET['page_id'])) {
            $data['page_id'] = $_GET['page_id'];
        } else if (isset($_SESSION['page_id'])) {
            $data['page_id'] = $_SESSION['page_id'];
        } else {
            return redirect()->to(base_url() . '/SCORM/course_builder/Editor');
        }
        $user = session()->get('username');

        if ($this->request->getPost()) {
            //print_r("sss");
            $rules = [
                'page_name' => 'required',
            ];

            if (!$this->validate($rules)) {
                $data['coursevalidation'] = $this->validator;
            } else {
                $newdata = [
                    'page_name' => $this->request->getVar('page_name'),
                    'type' => $this->request->getVar('type'),
                    'status' => $this->request->getVar('status'),
                    'page_number' => $this->request->getVar('page_number'),
                    'last_update_by' => session()->get('id_user'),
                    'last_update_on' => time(),

                ];
                $result = $this->scorm_page_model->editpagedetails($newdata, $data['page_id']);

                if ($result) {

                    // print_r($_SESSION);
                    // exit();
                    session()->setFlashdata('success', lang('Messages.Success_0008'));
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
        }
        return redirect()->to(base_url('SCORM/course_builder/scorm_course_pages/page_edit_view'));
    }
    function editquestion()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if (isset($_POST['q_id'])) {
            $data['question_id'] = $_POST['q_id'];
            $data['type'] = $_POST['typeval'];
            $_SESSION['question_id'] = $data['question_id'];
            $_SESSION['type'] = $data['type'];
        } else if (isset($_SESSION['question_id'])) {
            $data['question_id'] = $_SESSION['question_id'];
            $data['type'] = $_SESSION['type'];
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings/edit_quetion_view');
        }
        if ($this->request->getPost()) {
            $rules = [
                'question' => 'required',
            ];

            if (!$this->validate($rules)) {
                $data['coursevalidation'] = $this->validator;
            } else {
                $question = $this->request->getVar('question');
                $question_1 = isset($question) ? $question : '';
                $category = $this->request->getVar('category');
                $category_1 = isset($category) ? $category : '';
                $correct = $this->request->getVar('correct');
                $correct_1 = isset($correct) ? $correct : '';
                $incorrect = $this->request->getVar('incorrect');
                $incorrect_1 = isset($incorrect) ? $incorrect : '';
                $noAttempts = $this->request->getVar('noAttempts');
                $noAttempts_1 = isset($noAttempts) ? $noAttempts : '';
                $quiz_type = $this->request->getVar('quiz_type');
                $quiz_type_1 = isset($quiz_type) ? $quiz_type : '';
                $score = $this->request->getVar('score');
                $score_1 = isset($score) ? $score : '';

                $newdata = [
                    'question' => $question_1,

                ];
                // print_r($newdata);
                // exit();
                $result = $this->assessment_training_model->updatequestiondetails($newdata, $data['question_id']);
                if ($result) {
                    //$_SESSION['page_number'] =  $this->request->getVar('page_number');
                    // $_SESSION['page_id'] = $result['page_id'];
                    session()->setFlashdata('success', lang('Messages.Success_0008'));
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
            if (isset($_POST['returnUrl']) == 1) {
                // return redirect()->to(base_url() . 'Assessment/trainings/question_list_view');
                return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
            } else {
                // return redirect()->to(base_url() . 'Assessment/trainings/edit_quetion_view');
                return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
            }
        }
    }
    function edit_attempts_question()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if (isset($_POST['q_id'])) {
            $data['question_id'] = $_POST['q_id'];
            $_SESSION['question_id'] = $data['question_id'];
        } else if (isset($_GET['question_id'])) {
            $data['question_id'] = $_GET['question_id'];
        } else if (isset($_SESSION['question_id'])) {
            $data['question_id'] = $_SESSION['question_id'];
        } else {
            //   return redirect()->to(base_url() . 'Assessment/trainings/edit_quetion_view');
        }
        if (isset($_POST['type'])) {
            $data['type'] = $_POST['type'];
            $_SESSION['type'] = $data['type'];
        } else if (isset($_GET['type'])) {
            $data['type'] = $_GET['type'];
        } else if (isset($_SESSION['type'])) {
            $data['type'] = $_SESSION['type'];
        } else {
            // return redirect()->to(base_url() . 'Assessment/trainings/edit_quetion_view');
        }
        if (isset($_POST['page_number'])) {
            $data['page_number'] = $_POST['page_number'];
            $_SESSION['page_number'] = $data['page_number'];
        } else if (isset($_SESSION['page_number'])) {
            $data['page_number'] = $_SESSION['page_number'];
        } else {
            //   return redirect()->to(base_url() . 'Assessment/trainings/edit_quetion_view');
        }
        // print_r($_POST['page_id']);
        // exit();
        if (isset($_POST['page_id'])) {
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $data['page_id'];
        } else if (isset($_SESSION['page_id'])) {
            $data['page_id'] = $_SESSION['page_id'];
        } else {
            //   return redirect()->to(base_url() . 'Assessment/trainings/edit_quetion_view');
        }
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else if (isset($_SESSION['tab'])) {
            $data['tab'] = $_SESSION['tab'];
        } else {
            $data['tab'] = 1;
        }
        // Only touch a column when its form actually posted it - the Quiz Type selector
        // (Quiz page type) and the Correct/Incorrect feedback boxes (SCQ/MCQ page type) now
        // save independently of each other, so an unconditional $newdata here would silently
        // blank out whichever group wasn't part of the request that just submitted.
        $newdata = [
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        if ($this->request->getVar('category') !== null) {
            $newdata['category'] = $this->request->getVar('category');
        }
        if ($this->request->getVar('correct') !== null) {
            $newdata['correct'] = $this->request->getVar('correct');
        }
        if ($this->request->getVar('incorrect') !== null) {
            $newdata['incorrect'] = $this->request->getVar('incorrect');
        }
        if ($this->request->getVar('incorrect2') !== null) {
            $newdata['incorrect2'] = $this->request->getVar('incorrect2');
        }
        if ($this->request->getVar('noAttempts') !== null) {
            $newdata['noAttempts'] = $this->request->getVar('noAttempts');
        }
        if ($this->request->getVar('quiz_type') !== null) {
            $newdata['quiz_type'] = $this->request->getVar('quiz_type');
        }
        if ($this->request->getVar('score') !== null) {
            $newdata['score'] = $this->request->getVar('score');
        }
        $result = $this->assessment_training_model->updatequestiondetails($newdata, $data['question_id']);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0008'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        if ($_POST['returnUrl'] == '2') {
            // return redirect()->to(base_url() . 'Assessment/trainings/add_quiz_option_view');
            return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
        } else {
            //return redirect()->to(base_url() . 'Assessment/trainings/edit_quetion_view');
            return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
        }
    }
    function deleteQuestion()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $q_id = $this->request->getVar('question_id');
        $newdata = [
            'status' => 0,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->assessment_training_model->updatequestiondetails($newdata, $q_id);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
            return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
            // return redirect()->to(base_url() . 'Assessment/trainings/question_list_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
            // return redirect()->to(base_url() . 'Assessment/trainings/question_list_view');
        }
    }
    public function add_option_view()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $data['main_header'] = 'Course Detail';
        $data['main_header_link'] = 'my_training/read_more';
        $data['header'] = 'Course Builder';
        $data['header_link'] = 'SCORM/course_builder/Editor';
        $data['sub_header_1'] = 'Add Option';
        $data['header_1'] = 'Questions';
        $data['header_link_1'] = 'Assessment/trainings/question_list_view';
        $data['question_form_link'] = 'Assessment/trainings/addQuestions';

        $data['form_link'] = 'Assessment/trainings/addoption';
        $data['edit_link'] = 'Assessment/trainings/option_edit_view';
        $data['delete_link'] = 'Assessment/trainings/deleteOption';
        $data['form_url_1'] = 'Assessment/trainings/question_image_upload';
        $data['form_url_2'] = 'Assessment/trainings/pdf_upload';
        $data['form_url_3'] = 'Assessment/trainings/video_upload';
        $data['typeval'] = 8;
        if (isset($_POST['scourse_id']) && isset($_POST['course_name'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $data['page_id'];
            $data['course_name'] = $_POST['course_name'];
            $_SESSION['course_name'] = $data['course_name'];
        } else if (isset($_GET['scourse_id']) && isset($_GET['course_name'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
            $data['course_name'] = $_GET['course_name'];
            $data['page_id'] = $_GET['page_id'];
        } else if (isset($_SESSION['scourse_id']) && isset($_SESSION['course_name'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
            $data['course_name'] = $_SESSION['course_name'];
            $data['page_id'] = $_SESSION['page_id'];
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings/edit_quetion_view');
        }
        if (isset($_POST['question_id'])) {
            $data['question_id'] = $_POST['question_id'];
            $data['type'] = $_POST['type'];
            $_SESSION['question_id'] = $data['question_id'];
            $_SESSION['type'] = $data['type'];
        } else if (isset($_GET['question_id'])) {
            $data['question_id'] = $_GET['question_id'];
            $data['type'] = $_GET['type'];
        } else if (isset($_SESSION['question_id'])) {
            $data['question_id'] = $_SESSION['question_id'];
            $data['type'] = $_SESSION['type'];
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings/edit_quetion_view');
        }
        $data['getCourseData'] = $this->scorm_course_model->getCourseDetails($data['scourse_id']);
        $getQuestiondata = $this->assessment_training_model->geteditquestiondetails($data['question_id']);
        $data['row'] = $getQuestiondata[0];
        $data['getoptiondata'] = $this->assessment_training_model->getoptiondaata($data['question_id']);
        $data['AssessmentQuestionType'] = $this->dropdown_model->getCountrylist(21);
        $data['allcategories'] = $this->scorm_course_model->getAllMetadata(12);
        $data['CategoryData'] = $this->dropdown_model->getCountrylist(20);
        echo view('templates/header_view', $data);
        echo view('assessment/option_add_view', $data);
        echo view('templates/footer_view');
    }
    public function add_quiz_option_view($question_id = null)
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $data['main_header'] = 'Courses';
        $data['main_header_link'] = 'SCORM/scorm_courses';
        $data['header'] = 'Course Builder';
        $data['header_link'] = 'SCORM/course_builder/Editor';
        $data['header_1'] = 'Questions';

        if (isset($_POST['question_id'])) {
            $data['question_id'] = $_POST['question_id'];
            $_SESSION['question_id'] = $data['question_id'];
        } else if (isset($question_id)) {
            $data['question_id'] = $question_id;
            $_SESSION['question_id'] = $data['question_id'];
        } else if (isset($_SESSION['question_id'])) {
            $data['question_id'] = $_SESSION['question_id'];
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings/edit_quetion_view');
        }
        if (isset($_POST['type'])) {
            $data['type'] = $_POST['type'];
            $_SESSION['type'] = $data['type'];
        } else if (isset($_GET['type'])) {
            $data['type'] = $_GET['type'];
        } else if (isset($_SESSION['type'])) {
            $data['type'] = $_SESSION['type'];
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings/edit_quetion_view');
        }
        if (isset($_POST['page_id'])) {
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $data['page_id'];
        } else if (isset($_SESSION['page_id'])) {
            $data['page_id'] = $_SESSION['page_id'];
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings/edit_quetion_view');
        }
        // print_r($data['page_id']);
        // exit();
        $data['editquestion'] = 'Assessment/trainings/editquestion';
        $data['header_link_1'] = 'Assessment/trainings/question_list_view';
        $data['form_link'] = 'Assessment/trainings/addoption';
        $data['edit_link'] = 'Assessment/trainings/option_edit_view';
        $data['delete_link'] = 'Assessment/trainings/deleteOption';
        $data['form_url_1'] = 'Assessment/trainings/question_image_upload';
        $data['form_url_2'] = 'Assessment/trainings/pdf_upload';
        $data['form_url_3'] = 'Assessment/trainings/video_upload';
        $data['typeval'] = 8;
        $getQuestiondata = $this->assessment_training_model->geteditquestiondetails($data['question_id']);
        $data['row'] = $getQuestiondata[0];
        $data['getCourseData'] = $this->scorm_course_model->getCourseDetails($data['row']['scourse_id']);

        // "Edit Quiz (2/10)" - this question's position among all questions on the page, and
        // the page's total question count.
        $pageQuestions = $this->assessment_training_model->getpagequestion($data['page_id']);
        $questionPosition = 1;
        foreach ($pageQuestions as $index => $pageQuestion) {
            if ((string) $pageQuestion['q_id'] === (string) $data['question_id']) {
                $questionPosition = $index + 1;
                break;
            }
        }
        $data['sub_header_1'] = 'Edit Quiz (' . $questionPosition . '/' . count($pageQuestions) . ')';

        $data['getoptiondata'] = $this->assessment_training_model->getoptiondaata($data['question_id']);
        $data['AssessmentQuestionType'] = $this->dropdown_model->getCountrylist(21);
        $data['allcategories'] = $this->scorm_course_model->getAllMetadata(12);
        $data['question_attachment_image'] = $this->assessment_training_model->check_image_file_exists($data['question_id'], 1);
        $data['question_attachment_video'] = $this->assessment_training_model->check_image_file_exists($data['question_id'], 3);
        $data['prev_page'] = $this->scorm_page_model->getPreviousRecord($data['question_id'], $data['page_id']);
        // print_r($data['prev_page']);
        // exit();
        $data['next_page'] = $this->scorm_page_model->getNextRecord($data['question_id'], $data['page_id']);

        echo view('templates/header_view', $data);
        echo view('assessment/option_add_view', $data);
        echo view('templates/footer_view');
    }
    public function addoption()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if ($this->request->getPost()) {
            $rules = [
                'option' => 'required',
            ];
            if (!$this->validate($rules)) {
                $data['coursevalidation'] = $this->validator;
            } else {
                $newdata = [
                    'values' => $this->request->getVar('option'),
                    'truefalse' => $this->request->getVar('truefalse'),
                    'scourse_id' => $this->request->getVar('scourse_id'),
                    'question_id' => $this->request->getVar('q_id'),
                    'status' => '1',
                    'score' => $this->request->getVar('score'),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                $result = $this->assessment_training_model->addoptiondata($newdata);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0011'));
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
            if ($_POST['returnUrl'] == 2) {
                return redirect()->to(base_url() . 'Assessment/trainings/add_quiz_option_view');
            } elseif ($_POST['returnUrl'] == 1) {
                return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
            } else {
                return redirect()->to(base_url() . 'Assessment/trainings/edit_quetion_view');
            }
        }
    }
    function option_edit_view()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $data['main_header'] = 'Course Detail';
        $data['main_header_link'] = 'my_training/read_more';
        $data['header'] = 'Course Builder';
        $data['header_link'] = 'SCORM/course_builder/Editor';
        $data['sub_header_1'] = 'Edit Option';
        $data['header_1'] = 'Questions';
        $data['header_link_1'] = 'Assessment/trainings/question_list_view';
        $data['header_2'] = 'Add Option';
        $data['header_link_2'] = 'Assessment/trainings/add_option_view';
        $data['form_link'] = 'Assessment/trainings/editoption';
        $data['edit_link'] = 'Assessment/trainings/option_edit_view';

        $data['typeval'] = 8;
        if (isset($_POST['question_id'])) {
            $data['question_id'] = $_POST['question_id'];
            //$data['type'] = $_POST['type'];
            $_SESSION['question_id'] = $data['question_id'];
            $data['o_id'] = $_POST['o_id'];
            $_SESSION['o_id'] = $data['o_id'];
        } else if (isset($_GET['question_id'])) {
            $data['question_id'] = $_GET['question_id'];
        } else if (isset($_SESSION['question_id'])) {
            $data['question_id'] = $_SESSION['question_id'];
            $data['o_id'] = $_SESSION['o_id'];
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings');
        }

        $getoptiondetails = $this->assessment_training_model->getoptiondetails($data['o_id']);
        $data['row'] = $getoptiondetails[0];
        echo view('templates/header_view', $data);
        echo view('assessment/option_edit_view', $data);
        echo view('templates/footer_view');
    }
    function updatedateformat()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $value = $_POST['value'];
        $column = $_POST['column'];
        $id = $_POST['id'];
        if (isset($_POST['question_id'])) {
            $data['question_id'] = $_POST['question_id'];
            //$data['type'] = $_POST['type'];
            $_SESSION['question_id'] = $data['question_id'];
        } else if (isset($_GET['question_id'])) {
            $data['question_id'] = $_GET['question_id'];
        } else if (isset($_SESSION['question_id'])) {
            $data['question_id'] = $_SESSION['question_id'];
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings');
        }
        $getQuestiondata = $this->assessment_training_model->geteditquestiondetails($data['question_id']);
        $data['row'] = $getQuestiondata[0];

        $result = $this->assessment_training_model->updateoptioneditableformat($value, $column, $id, $data['question_id'], $data['row']['quiz_type']);
        echo json_encode($result);
    }
    function adddateformat()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $value = $_POST['value'];
        $column = $_POST['column'];
        $id = $_POST['id'];
        $scourse_id = $_POST['scourse_id'];
        $question_id = $_POST['question_id'];
        // Optional - only sent when adding an option from the SCQ/MCQ editor, so this new
        // option's default correctness can depend on the question type (see model method).
        $pageType = isset($_POST['page_type']) ? $_POST['page_type'] : null;
        $result = $this->assessment_training_model->addoptioneditableformat($value, $column, $id, $scourse_id, $question_id, $pageType);
        echo json_encode($result);
    }
    function editoption()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if (isset($_POST['o_id'])) {
            $data['o_id'] = $_POST['o_id'];
            $_SESSION['o_id'] = $data['o_id'];
            // $data['type'] = $_POST['type'];
        } else if (isset($_GET['o_id'])) {
            $data['o_id'] = $_GET['o_id'];
        } else if (isset($_SESSION['o_id'])) {
            $data['o_id'] = $_SESSION['o_id'];
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings');
        }
        if ($this->request->getPost()) {
            $rules = [
                'option' => 'required',
            ];
            if (!$this->validate($rules)) {
                $data['coursevalidation'] = $this->validator;
            } else {
                $newdata = [
                    'values' => $this->request->getVar('option'),
                    'score' => $this->request->getVar('score'),
                    'truefalse' => $this->request->getVar('truefalse'),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),

                ];
                $result = $this->assessment_training_model->updateoptiondata($newdata, $data['o_id']);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0011'));
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
            if ($_POST['returnUrl'] == 2) {
                return redirect()->to(base_url() . 'Assessment/trainings/add_quiz_option_view');
            } else {
                return redirect()->to(base_url() . 'Assessment/trainings/edit_quetion_view');
            }
        }
    }
    public function deleteOption()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if (isset($_POST['question_id'])) {
            $data['question_id'] = $_POST['question_id'];
            $_SESSION['question_id'] = $data['question_id'];
        } else if (isset($_GET['question_id'])) {
            $data['question_id'] = $_GET['question_id'];
        } else if (isset($_SESSION['question_id'])) {
            $data['question_id'] = $_SESSION['question_id'];
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings');
        }
        if (isset($_POST['o_id'])) {
            $data['o_id'] = $_POST['o_id'];
            $_SESSION['o_id'] = $data['o_id'];
        } else if (isset($_GET['o_id'])) {
            $data['o_id'] = $_GET['o_id'];
        } else if (isset($_SESSION['o_id'])) {
            $data['o_id'] = $_SESSION['o_id'];
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings');
        }
        $newdata = [


            'status' => 0,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->assessment_training_model->updateoptiondata($newdata, $data['o_id']);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
            //return redirect()->to(base_url() . 'Assessment/trainings/edit_quetion_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            //return redirect()->to(base_url() . 'Assessment/trainings/edit_quetion_view');
        }
        if ($_POST['returnUrl'] == 2) {
            return redirect()->to(base_url() . 'Assessment/trainings/add_quiz_option_view');
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings/edit_quetion_view');
        }
    }
    public function copyQuestiondetails()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if (isset($_POST['question_id'])) {
            $data['question_id'] = $_POST['question_id'];
            $_SESSION['question_id'] = $data['question_id'];
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_GET['question_id'])) {
            $data['question_id'] = $_GET['question_id'];
        } else if (isset($_SESSION['question_id'])) {
            $data['question_id'] = $_SESSION['question_id'];
            $data['scourse_id'] = $_SESSION['scourse_id'];
        } else {
            // return redirect()->to(base_url() . '/Assessment/trainings');
            return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
        }
        $result = $this->assessment_training_model->copyQuestion($data['question_id']);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0012'));
            // return redirect()->to(base_url() . 'Assessment/trainings/question_list_view');
            return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
            // return redirect()->to(base_url() . 'Assessment/trainings/question_list_view');
        }
    }
    function assessment_settings()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $course_lang = $_SESSION['course_lang'];
        if ($course_lang == 'French') {
            $data['assessment_sets'] = Assessment_french::$assessment_sets;
        } elseif ($course_lang == 'Spanish') {
            $data['assessment_sets'] = Assessment_spanish::$assessment_sets;
        } elseif ($course_lang == 'Russian') {
            $data['assessment_sets'] = Assessment_russian::$assessment_sets;
        } elseif ($course_lang == 'Portuguese') {
            $data['assessment_sets'] = Assessment_portuguese::$assessment_sets;
        } elseif ($course_lang == 'Bahasa') {
            $data['assessment_sets'] = Assessment_bahasa::$assessment_sets;
        } elseif ($course_lang == 'Arabic') {
            $data['assessment_sets'] = Assessment_arabic::$assessment_sets;
        } else {
            $data['assessment_sets'] = Assessment_english::$assessment_sets;
        }

        if (isset($_POST['scourse_id']) && isset($_POST['page_name'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $data['page_id'];
            $data['page_name'] = $_POST['page_name'];
            $_SESSION['page_name'] = $data['page_name'];
        } else if (isset($_GET['scourse_id']) && isset($_GET['page_name'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
            $data['page_name'] = $_GET['page_name'];
            $data['page_id'] = $_GET['page_id'];
        } else if (isset($_SESSION['scourse_id']) && isset($_SESSION['page_name'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
            $data['page_name'] = $_SESSION['page_name'];
            $data['page_id'] = $_SESSION['page_id'];
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings');
        }
        // print_r($_POST['tab']);
        // exit();
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else if (isset($_SESSION['tab'])) {
            $data['tab'] = $_SESSION['tab'];
        } else {
            $data['tab'] = 1;
        }
        $data['main_header'] = 'Course Detail';
        $data['main_header_link'] = 'my_training/read_more';
        $data['header'] = 'Course Builder';
        $data['header_link'] = 'SCORM/course_builder/Editor';
        $data['header_1'] = 'Questions';
        $data['header_link_1'] = 'Assessment/trainings/question_list_view';
        $data['sub_header_1'] = lang('UI_Text.CB_Quiz_Settings');
        $data['form_link'] = 'Assessment/trainings/addQuestionsBank';

        $data['typeval'] = 8;
        $data['getAssessmentSettings'] = $this->assessment_training_model->get_question_settings($data['scourse_id'], $data['page_id']);
        $data['getAssessmentSettings31'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['page_id'], 31);
        $data['getAssessmentSettings32'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['page_id'], 32);

        $data['AssessmentSettings']['33'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['page_id'], 33);
        $data['AssessmentSettings']['34'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['page_id'], 34);
        $data['AssessmentSettings']['35'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['page_id'], 35);
        $data['AssessmentSettings']['36'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['page_id'], 36);
        $data['AssessmentSettings']['37'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['page_id'], 37);
        $data['AssessmentSettings']['38'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['page_id'], 38);
        $data['AssessmentSettings']['39'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['page_id'], 39);
        $data['AssessmentSettings']['40'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['page_id'], 40);
        $data['AssessmentSettings']['41'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['page_id'], 41);
        $data['AssessmentSettings']['42'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['page_id'], 42);
        $data['AssessmentSettings']['43'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['page_id'], 43);
        $data['AssessmentSettings']['44'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['page_id'], 44);
        $data['AssessmentSettings']['45'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['page_id'], 45);
        $data['AssessmentSettings']['46'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['page_id'], 46);
        $data['AssessmentSettings']['47'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['page_id'], 47);
        $data['AssessmentSettings']['48'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['page_id'], 48);
        $data['AssessmentSettings']['49'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['page_id'], 49);
        // $data['AssessmentSettings']['66'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['page_id'], 66);
        $data['AssessmentSettings']['67'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['page_id'], 67);
        $data['AssessmentSettings']['69'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['page_id'], 69);
        $data['AssessmentSettings']['70'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['page_id'], 70);
        $data['AssessmentSettings']['71'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['page_id'], 71);
        $data['AssessmentSettings']['73'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['page_id'], 73);

        $data['AssessmentSettings']['69'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['page_id'], 69);
        $data['AssessmentSettings']['70'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['page_id'], 70);
        $data['AssessmentSettings']['71'] = $this->scorm_course_model->getpageAssignmetadatabyID($data['scourse_id'], $data['page_id'], 71);


        echo view('templates/header_view', $data);
        echo view('assessment/assessment_settings_view', $data);
        echo view('templates/footer_view');
    }
    public function setting_data_update()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $data['scourse_id'] = $_POST['scourse_id'];
        $data['quiz_settings_type'] = $_POST['quiz_settings_type'];
        $data['add_or_update'] = $_POST['add_or_update'];
        $data['value'] = $_POST['value'];
        $data['s_id'] = $_POST['s_id'];
        $data['page_id'] = isset($_POST['page_id']) ? $_POST['page_id'] : 0;
        $data['returnUrl'] = isset($_POST['returnUrl']) ? $_POST['returnUrl'] : '';
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else if (isset($_SESSION['tab'])) {
            $data['tab'] = $_SESSION['tab'];
        } else {
            $data['tab'] = 1;
        }
        if ($data['add_or_update'] == 1) {
            $newdata = [
                'scourse_id' => $data['scourse_id'],
                'page_id' => $data['page_id'],
                'type' => $data['quiz_settings_type'],
                'value' => $data['value'],
                'status' => 1,
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $this->assessment_training_model->add_settings($newdata);
            session()->setFlashdata('success', lang('Messages.Success_0011'));
            session()->setFlashdata('alert-class', 'alert-danger');
        } else {
            $newdata = [
                'value' => $data['value'],
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $this->assessment_training_model->delete_old_settings($data['s_id'], $newdata);
            session()->setFlashdata('success', lang('Messages.Success_0008'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        if ($data['returnUrl'] == 1) {
            return redirect()->to(base_url() . 'SCORM/course_builder/Scorm_course_pages/page_pdf_view');
        }
        if ($data['returnUrl'] == 2) {
            // return redirect()->to(base_url() . 'Assessment/trainings/edit_quetion_view');
            return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings/assessment_settings');
            // return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
        }
    }

    // public function question_image_upload()
    // {
    //     if ($response =  $this->requireRole(['46'])) {
    //         return $response;
    //     }
    //     helper(['filesystem', 'form']);
    //     $data = [];
    //     if (isset($_POST['scourse_id'])) {
    //         $data['scourse_id'] = $_POST['scourse_id'];
    //         $_SESSION['scourse_id'] = $data['scourse_id'];
    //     } else if (isset($_GET['scourse_id'])) {
    //         $data['scourse_id'] = $_GET['scourse_id'];
    //     } else if (isset($_SESSION['scourse_id'])) {
    //         $data['scourse_id'] = $_SESSION['scourse_id'];
    //     }
    //     if (isset($_POST['createdon'])) {
    //         $data['createdon'] = $_POST['createdon'];
    //         $_SESSION['createdon'] = $data['createdon'];
    //     } else if (isset($_GET['createdon'])) {
    //         $data['createdon'] = $_GET['createdon'];
    //     } else if (isset($_SESSION['createdon'])) {
    //         $data['createdon'] = $_SESSION['createdon'];
    //     }
    //     if (isset($_POST['page_id'])) {
    //         $data['page_id'] = $_POST['page_id'];
    //         $_SESSION['page_id'] = $data['page_id'];
    //     } else if (isset($_GET['page_id'])) {
    //         $data['page_id'] = $_GET['page_id'];
    //     } else if (isset($_SESSION['page_id'])) {
    //         $data['page_id'] = $_SESSION['page_id'];
    //     }
    //     if (isset($_POST['q_id'])) {
    //         $data['q_id'] = $_POST['q_id'];
    //         $_SESSION['q_id'] = $data['q_id'];
    //     } else if (isset($_GET['q_id'])) {
    //         $data['q_id'] = $_GET['q_id'];
    //     } else if (isset($_SESSION['q_id'])) {
    //         $data['q_id'] = $_SESSION['q_id'];
    //     }


    //     if ($this->request->getPost()) {
    //         $rules = [
    //             'file' => [
    //                 'rules' => 'uploaded[file]|ext_in[file,jpg,png,mp4]|max_size[file,102400]',
    //                 'errors' => [
    //                     'uploaded' => 'Please choose a file.',
    //                     'ext_in' => 'Only .jpg and .png files are allowed.',
    //                     'max_size' => 'The file size exceeds the allowed limit of 2MB.',
    //                 ]
    //             ]
    //         ];
    //         if (!$this->validate($rules)) {
    //             $data['imagevalidation'] = $this->validator;
    //             session()->setFlashdata('error', lang('Messages.Error_0007'));
    //         } else {
    //             if ($file = $this->request->getFile('file')) {
    //                 if ($file->isValid() && !$file->hasMoved()) {
    //                     $filename = $file->getName();
    //                     // print_r($filename);
    //                     $fname = explode('.', $filename);
    //                     if ($fname[1] == 'mp4') {
    //                         $type = 3;
    //                         $filepath = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $data['createdon'] . '/assets/Quiz/' . $data['page_id'] . '/assessment_video';
    //                         $mkdir = 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $data['createdon'] . '/assets/Quiz/' . $data['page_id'] . '/assessment_video';
    //                     } else {
    //                         $type = 1;
    //                         $filepath = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $data['createdon'] . '/assets/Quiz/' . $data['page_id'] . '/assessment_image';
    //                         $mkdir = 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $data['createdon'] . '/assets/Quiz/' . $data['page_id'] . '/assessment_image';
    //                     }
    //                     // exit();
    //                     if (!is_dir($filepath)) {
    //                         mkdir($mkdir, 0777, true);
    //                     }
    //                     if (file_exists($filepath . '/' . $filename)) {
    //                         session()->setFlashdata('error', $filename . ' ' . lang('Messages.Success_0051'));
    //                         return redirect()->to(base_url() . 'Assessment/trainings/add_quiz_option_view');
    //                     } else {
    //                         if ($file->move($filepath, $filename)) {
    //                             $filepath = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $data['createdon'] . '/assets/Quiz/' . $data['page_id'] . '/assessment_image/' . $filename;
    //                             $newdata = [
    //                                 'scourse_id' => $data['scourse_id'],
    //                                 'q_id' => $data['q_id'],
    //                                 'doc_name' => $filename,
    //                                 'type' => $type,
    //                                 'status' => 1,
    //                                 'createdby' => session()->get('id_user'),
    //                                 'createdon' => time(),
    //                                 'last_updated_by' => session()->get('id_user'),
    //                                 'last_updated_on' => time(),
    //                             ];
    //                             $result = $this->assessment_training_model->addAssessmentimg($newdata);
    //                             if ($result) {
    //                                 session()->setFlashdata('success', lang('Messages.Success_0009'));
    //                                 session()->setFlashdata('alert-class', 'alert-danger');
    //                             } else {
    //                                 session()->setFlashdata('error', lang('Messages.Error_0001'));
    //                                 session()->setFlashdata('alert-class', 'alert-danger');
    //                             }
    //                         }
    //                     }
    //                 } else {
    //                     session()->setFlashdata('error', lang('Messages.Error_0001'));
    //                     session()->setFlashdata('alert-class', 'alert-danger');
    //                 }
    //             }
    //         }
    //         // session()->setFlashdata('error', lang('Messages.Error_0003'));
    //         // session()->setFlashdata('alert-class', 'alert-danger');
    //     }
    //     return redirect()->to(base_url() . 'Assessment/trainings/add_quiz_option_view');
    //     // echo view('templates/header_view', $data);
    //     // echo view('assessment/option_add_view', $data);
    //     // echo view('templates/footer_view');
    // }
    public function question_image_upload()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['filesystem', 'form']);
        $data = [];

        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        }

        if (isset($_POST['createdon'])) {
            $data['createdon'] = $_POST['createdon'];
            $_SESSION['createdon'] = $data['createdon'];
        } else if (isset($_GET['createdon'])) {
            $data['createdon'] = $_GET['createdon'];
        } else if (isset($_SESSION['createdon'])) {
            $data['createdon'] = $_SESSION['createdon'];
        }

        if (isset($_POST['page_id'])) {
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $data['page_id'];
        } else if (isset($_GET['page_id'])) {
            $data['page_id'] = $_GET['page_id'];
        } else if (isset($_SESSION['page_id'])) {
            $data['page_id'] = $_SESSION['page_id'];
        }

        if (isset($_POST['q_id'])) {
            $data['q_id'] = $_POST['q_id'];
            $_SESSION['q_id'] = $data['q_id'];
        } else if (isset($_GET['q_id'])) {
            $data['q_id'] = $_GET['q_id'];
        } else if (isset($_SESSION['q_id'])) {
            $data['q_id'] = $_SESSION['q_id'];
        }

        if ($this->request->getPost()) {

            $rules = [
                'file' => [
                    'rules' => 'uploaded[file]|ext_in[file,jpg,jpeg,png,mp4]|max_size[file,102400]',
                    'errors' => [
                        'uploaded' => 'Please choose a file.',
                        'ext_in' => 'Only .jpg, .jpeg, .png and .mp4 files are allowed.',
                        'max_size' => 'The file size exceeds the allowed limit.',
                    ]
                ]
            ];

            if (!$this->validate($rules)) {
                $data['imagevalidation'] = $this->validator;
                session()->setFlashdata('error', lang('Messages.Error_0007'));
            } else {

                if ($file = $this->request->getFile('file')) {

                    if ($file->isValid() && !$file->hasMoved()) {

                        $extension = strtolower($file->getExtension());
                        $filename  = $file->getRandomName(); // safer

                        if ($extension == 'mp4') {
                            $type = 3;
                            $folder = 'assessment_video';
                        } else {
                            $type = 1;
                            $folder = 'assessment_image';
                        }

                        $filepath = FCPATH . 'assets/assets/uploads/SCORM_course_document/' .
                            $data['scourse_id'] . '/' . $data['createdon'] .
                            '/assets/Quiz/' . $data['page_id'] . '/' . $folder;

                        if (!is_dir($filepath)) {
                            mkdir($filepath, 0755, true);
                        }

                        $fullPath = $filepath . '/' . $filename;

                        // 🔒 FIX: Process image to strip EXIF
                        if ($extension == 'jpg' || $extension == 'jpeg') {

                            $image = @imagecreatefromjpeg($file->getTempName());
                            if ($image) {
                                imagejpeg($image, $fullPath, 90);
                                imagedestroy($image);
                            }
                        } elseif ($extension == 'png') {

                            $image = @imagecreatefrompng($file->getTempName());
                            if ($image) {
                                imagepng($image, $fullPath);
                                imagedestroy($image);
                            }
                        } else {
                            // mp4 → keep original logic
                            $file->move($filepath, $filename);
                        }

                        // DB insert (UNCHANGED LOGIC)
                        $newdata = [
                            'scourse_id' => $data['scourse_id'],
                            'q_id' => $data['q_id'],
                            'doc_name' => $filename,
                            'type' => $type,
                            'status' => 1,
                            'createdby' => session()->get('id_user'),
                            'createdon' => time(),
                            'last_updated_by' => session()->get('id_user'),
                            'last_updated_on' => time(),
                        ];

                        $result = $this->assessment_training_model->addAssessmentimg($newdata);

                        if ($result) {
                            session()->setFlashdata('success', lang('Messages.Success_0009'));
                            session()->setFlashdata('alert-class', 'alert-danger');
                        } else {
                            session()->setFlashdata('error', lang('Messages.Error_0001'));
                            session()->setFlashdata('alert-class', 'alert-danger');
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                    }
                }
            }
        }

        return redirect()->to(base_url() . 'Assessment/trainings/add_quiz_option_view');
    }

    function video_upload()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['filesystem', 'form']);
        $data = [];
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        }
        if (isset($_POST['createdon'])) {
            $data['createdon'] = $_POST['createdon'];
            $_SESSION['createdon'] = $data['createdon'];
        } else if (isset($_GET['createdon'])) {
            $data['createdon'] = $_GET['createdon'];
        } else if (isset($_SESSION['createdon'])) {
            $data['createdon'] = $_SESSION['createdon'];
        }
        if (isset($_POST['page_id'])) {
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $data['page_id'];
        } else if (isset($_GET['page_id'])) {
            $data['page_id'] = $_GET['page_id'];
        } else if (isset($_SESSION['page_id'])) {
            $data['page_id'] = $_SESSION['page_id'];
        }
        if (isset($_POST['q_id'])) {
            $data['q_id'] = $_POST['q_id'];
            $_SESSION['q_id'] = $data['q_id'];
        }
        if ($this->request->getPost()) {
            $rules = [
                'file' => [
                    'rules' => 'uploaded[file]|ext_in[file,mp4]|max_size[file,102400]', // Only allow .mp4 and max size of 100MB
                    'errors' => [
                        'uploaded' => 'Please choose a file.',
                        'ext_in' => 'Only .mp4 files are allowed.',
                        'max_size' => 'The file size exceeds the allowed limit of 100MB.',
                    ]
                ]
            ];
            if (!$this->validate($rules)) {
                $data['videovalidation'] = $this->validator;
                session()->setFlashdata('error', lang('Messages.Error_0012'));
            } else {
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();
                        if (!is_dir(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $data['createdon'] . '/assets/Quiz/' . $data['page_id'] . '/assessment_video')) {
                            mkdir('assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $data['createdon'] . '/assets/Quiz/' . $data['page_id'] . '/assessment_video', 0777, true);
                        }
                        if (file_exists(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $data['createdon'] . '/assets/Quiz/' . $data['page_id'] . '/assessment_video/' . $filename)) {
                            session()->setFlashdata('error', $filename . ' ' . lang('Messages.Success_0051'));
                            return redirect()->to(base_url() . 'Assessment/trainings/add_quiz_option_view');
                        } else {

                            if ($file->move(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $data['createdon'] . '/assets/Quiz/' . $data['page_id'] . '/assessment_video/', $filename)) {
                                $filepath = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $data['createdon'] . '/assets/Quiz/' . $data['page_id'] . '/assessment_video/' . '/' . $filename;
                                $newdata = [
                                    'scourse_id' => $data['scourse_id'],
                                    'q_id' => $data['q_id'],
                                    'doc_name' => $filename,
                                    'type' => $this->request->getVar('type'),
                                    'status' => 1,
                                    'last_updated_by' => session()->get('id_user'),
                                    'last_updated_on' => time(),
                                ];
                                $result = $this->assessment_training_model->addAssessmentimg($newdata);
                                if ($result) {
                                    session()->setFlashdata('success', lang('Messages.Success_0009'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                } else {
                                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                }
                            }
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                    }
                }
                // session()->setFlashdata('error', lang('Messages.Error_0003'));
                // session()->setFlashdata('alert-class', 'alert-danger');
            }
        }
        return redirect()->to(base_url() . 'Assessment/trainings/add_quiz_option_view');
    }

    public function change_settings()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['filesystem', 'form']);
        $data = [];
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings/add_quiz_option_view');
        }
        if (isset($_POST['page_id'])) {
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $data['page_id'];
        } else if (isset($_GET['page_id'])) {
            $data['page_id'] = $_GET['page_id'];
        } else if (isset($_SESSION['page_id'])) {
            $data['page_id'] = $_SESSION['page_id'];
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings/add_quiz_option_view');
        }
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else if (isset($_SESSION['tab'])) {
            $data['tab'] = $_SESSION['tab'];
        } else {
            $data['tab'] = 1;
        }
        $settings_id = $this->request->getVar('quiz_settings_id');
        if ($this->request->getPost()) {

            $change_quiz_settings_inactive = array(
                'status' => 0,
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
            );
            $this->assessment_training_model->delete_old_settings($settings_id, $change_quiz_settings_inactive);
            $data = array(
                'type' => $this->request->getVar('quiz_settings_type'),
                'scourse_id' => $data['scourse_id'],
                'page_id' => $data['page_id'],
                'value' => $this->request->getVar('value'),
                'status' => 1,
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
            );

            $result = $this->assessment_training_model->add_settings($data);
            // This endpoint is only ever called via fetch() from assessment_settings_view.php's
            // auto-save fields - it needs the newly inserted row's s_id back so the next edit to
            // the same field deactivates the row that's actually current, not the one that was
            // current when the page first loaded.
            if ($result) {
                return $this->response->setJSON(['status' => 'OK', 's_id' => $result]);
            }
            return $this->response->setJSON(['status' => 'Error']);
        }
    }
    function export_questions_excel()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if (isset($_POST['scourse_id']) && isset($_POST['course_name'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $data['page_id'];
            $data['course_name'] = $_POST['course_name'];
            $_SESSION['course_name'] = $data['course_name'];
        } else if (isset($_GET['scourse_id']) && isset($_GET['course_name'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
            $data['course_name'] = $_GET['course_name'];
            $data['page_id'] = $_GET['page_id'];
        } else if (isset($_SESSION['scourse_id']) && isset($_SESSION['course_name'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
            $data['course_name'] = $_SESSION['course_name'];
            $data['page_id'] = $_SESSION['page_id'];
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings');
        }
        $questiondata = $this->assessment_training_model->getexportQuestionAnswerdata($data['page_id']);

        $groupedData = [];
        foreach ($questiondata as $entry) {
            // Group by q_id and o_id to eliminate duplicate options for the same question
            $groupedData[$entry['q_id']][$entry['o_id']][] = $entry;
        }

        // Create a new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Dynamically determine the maximum number of options per question
        $maxOptions = 0;
        foreach ($groupedData as $q_id => $options) {
            $maxOptions = max($maxOptions, count($options));
        }
        $sheet->setCellValue('A1', 'Question ID');
        $sheet->setCellValue('B1', 'Question');
        $sheet->setCellValue('C1', 'Type');
        $sheet->setCellValue('D1', 'Feedback');

        // $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getStyle('A1:Z1')->getFont()->setBold(true);
        // $sheet->getProtection()->setPassword('yourpassword');  // Set password if needed
        // $sheet->getProtection()->setSheet(true);  // Protect the sheet

        // $sheet->getStyle('A1:Z5000')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
        // $sheet->getStyle('A:A')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);


        // Generate dynamic headers for the options
        $col = 'E';
        for ($i = 1; $i <= $maxOptions; $i++) {
            $sheet->setCellValue($col . '1', "Option $i ID");
            $sheet->getStyle($col . ':' . $col)->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);
            $sheet->getStyle($col . ':' . $col)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $sheet->getStyle($col . ':' . $col)->getFill()->getStartColor()->setRGB('D3D3D3'); // Light gray

            $sheet->setCellValue(++$col . '1', "Option $i");
            $sheet->setCellValue(++$col . '1', "Correct");


            $col++; // Move to the next set of columns

        }

        $sheet->getStyle('A:A')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A:A')->getFill()->getStartColor()->setRGB('D3D3D3');
        $rowNumber = 2;

        // Loop through the grouped data and populate the spreadsheet
        foreach ($groupedData as $q_id => $options) {
            $question = $options[array_key_first($options)][0]['question'];  // Get the question from the first option group
            $quiz_type = $options[array_key_first($options)][0]['quiz_type'];
            if ($quiz_type == '112') {
                $type = 'SCQ';
            } elseif ($quiz_type == '115') {
                $type = 'MCQ';
            } else {
                $type = '';
            }
            $feedback = $options[array_key_first($options)][0]['correct'];  // Get the feedback from the first option group

            $sheet->setCellValue("A$rowNumber", $q_id);
            $sheet->setCellValue("B$rowNumber", $question);
            $sheet->setCellValue("C$rowNumber", $type);
            $sheet->setCellValue("D$rowNumber", $feedback);

            // Loop through the distinct options and fill data
            $colIndex = 0;
            foreach ($options as $o_id => $optionGroup) {
                $option = $optionGroup[0];  // Get the first entry for this option, as the others are just feedback variations

                // Adjust the column index for the options to account for the feedback column
                $col = $this->numToExcelColumn(4 + $colIndex * 3);  // Adjusted to start from column D

                // Option ID, Option value, and Correct/Incorrect
                $sheet->setCellValue($col . $rowNumber, $option['o_id']);
                $sheet->setCellValue($this->numToExcelColumn(5 + $colIndex * 3) . $rowNumber, $option['values']); // Column E
                $sheet->setCellValue($this->numToExcelColumn(6 + $colIndex * 3) . $rowNumber, $option['truefalse'] == 1 ? 'TRUE' : ''); // Column F

                $colIndex++;
            }

            // Move to the next row
            $rowNumber++;
        }


        $today_dt = date("Y-m-d_H-i-sa");
        $filename = $data['course_name'] . '_' . $today_dt . '.xlsx';
        $writer = new Xlsx($spreadsheet);


        $tempFilePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $filename;
        $writer->save($tempFilePath);


        if (file_exists($tempFilePath)) {
            return $this->response->download($tempFilePath, null)->setFileName($filename);
        } else {
            echo 'Error: File not created.';
        }
    }
    function numToExcelColumn($num)
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        $column = '';
        while ($num >= 0) {
            $column = chr($num % 26 + 65) . $column;
            $num = floor($num / 26) - 1;
        }
        return $column;
    }
    public function importQuestionsOptions_view()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if (isset($_POST['course_name'])) {
            $data['course_name'] = $_POST['course_name'];
            $_SESSION['course_name'] = $data['course_name'];
        } else if (isset($_GET['course_name'])) {
            $data['course_name'] = $_GET['course_name'];
        } else if (isset($_SESSION['course_name'])) {
            $data['course_name'] = $_SESSION['course_name'];
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings');
        }
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings');
        }
        if (isset($_POST['page_id'])) {
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $data['page_id'];
        } else if (isset($_GET['page_id'])) {
            $data['page_id'] = $_GET['page_id'];
        } else if (isset($_SESSION['page_id'])) {
            $data['page_id'] = $_SESSION['page_id'];
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings');
        }
        $data['header_1'] = 'Course Builder';
        $data['header_link_1'] = 'SCORM/course_builder/Editor';
        echo view('templates/header_view', $data);
        echo view('assessment/import_questions_excel', $data);
        echo view('templates/footer_view');
    }
    public function importNewquestionsOption()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];

        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings');
        }
        if (isset($_POST['page_id'])) {
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $data['page_id'];
        } else if (isset($_GET['page_id'])) {
            $data['page_id'] = $_GET['page_id'];
        } else if (isset($_SESSION['page_id'])) {
            $data['page_id'] = $_SESSION['page_id'];
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings');
        }
        $data['header_1'] = 'Questions';
        $data['header_link_1'] = 'Assessment/trainings/question_list_view';
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
                        // print_r($columnCount);
                        // exit();
                        $result = $this->assessment_training_model->importQuestionsdetails($sheetData, $data['scourse_id'], $data['page_id'], $columnCount);
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
                    return redirect()->to(base_url() . 'Assessment/trainings/question_list_view');
                }
            }
            echo view('templates/header_view', $data);
            echo view('assessment/import_questions_excel', $data);
            echo view('templates/footer_view');
        }
    }

    public function importquestionsOption()
    {
        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings');
        }
        if (isset($_POST['page_id'])) {
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $data['page_id'];
        } else if (isset($_GET['page_id'])) {
            $data['page_id'] = $_GET['page_id'];
        } else if (isset($_SESSION['page_id'])) {
            $data['page_id'] = $_SESSION['page_id'];
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings');
        }
        $data['header_1'] = 'Questions';
        $data['header_link_1'] = 'Assessment/trainings/question_list_view';
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

                        $result = $this->assessment_training_model->UpdateQuestionsdetails($sheetData, $data['scourse_id'], $data['page_id'], $columnCount);
                        if ($result) {
                            session()->setFlashdata('error', $result['error']);
                            session()->setFlashdata('alert-class', 'alert-success');
                        }
                        // else
                        //     session()->setFlashdata('error', lang('Messages.Error_0008'));
                        // session()->setFlashdata('alert-class', 'alert-danger');
                    }
                    return redirect()->to(base_url() . 'Assessment/trainings/question_list_view');
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    return redirect()->to(base_url() . 'Assessment/trainings/question_list_view');
                }
            }
            echo view('templates/header_view', $data);
            echo view('assessment/import_questions_excel', $data);
            echo view('templates/footer_view');
        }
    }
    function exportQuestion()
    {

        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if (isset($_POST['scourse_id']) && isset($_POST['course_name'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $data['page_id'];
            $data['course_name'] = $_POST['course_name'];
            $_SESSION['course_name'] = $data['course_name'];
        } else if (isset($_GET['scourse_id']) && isset($_GET['course_name'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
            $data['course_name'] = $_GET['course_name'];
            $data['page_id'] = $_GET['page_id'];
        } else if (isset($_SESSION['scourse_id']) && isset($_SESSION['course_name'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
            $data['course_name'] = $_SESSION['course_name'];
            $data['page_id'] = $_SESSION['page_id'];
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings');
        }
        $questionjsonfile = $this->assessment_training_model->getQuestionAnswerdata($data['page_id']);
        $coursetimestamp = $this->scorm_page_model->getAllpagedetails($data['scourse_id']);
        $AttemptsofCourse = $this->assessment_training_model->getAttemptsofCourse($data['scourse_id']);
        // print_r($AttemptsofCourse);
        //  $tocjsonfile = array($tocjsonfile);
        // echo "<pre>";
        // print_r($questionjsonfile);

        //  exit();
        $boolean = '';
        if ($questionjsonfile) {

            foreach ($questionjsonfile as &$eachquestion) {
                //  print_r($eachquestion);
                if ($eachquestion['truefalse'] == '1') {

                    $boolean = true;
                } elseif ($eachquestion['truefalse'] == '2') {
                    $boolean = false;
                }
                $option[] = [
                    "text" => $eachquestion['values'],
                    "value" => $eachquestion['values'],
                    "correct" => $boolean
                ];
            }
            //  print_r($option);
            //     exit();
            if (isset($AttemptsofCourse[0]['attempts'])) {
                $attempts = $AttemptsofCourse[0]['attempts'];
            } else {
                $attempts = 2;
            }
            if ($questionjsonfile[0]['quiz_type'] == '115') {
                $iframeSrc = "MCQ.html";
            } else {
                $iframeSrc = "SCQ.html";
            }
            $postdata = [
                "question" => [

                    "question" => $questionjsonfile[0]['question'],
                    "options" => $option,
                    "image" => "images/france.jpg",
                    "feedback" => [

                        "correct" => $questionjsonfile[0]['correct'],
                        "incorrect" => $questionjsonfile[0]['incorrect'],
                        "noAttempts" => $questionjsonfile[0]['noAttempts']

                    ],
                    "attempts" => $attempts,
                    "iframeSrc" => $iframeSrc,
                ]
            ];

            // print_r($postdata);
            // exit();

            $pagejson = json_encode($postdata, JSON_UNESCAPED_SLASHES);
            $timestamp = $coursetimestamp[0]['createdon'];
            if (!is_dir(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp . '/shared/assets/content/english/pages/' . $data['page_id'])) {
                mkdir('assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp . '/shared/assets/content/english/pages/' . $data['page_id'], 0777, true);
            }
            $path = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp . '/shared/assets/content/english/pages/' . $data['page_id'];
            $fp = fopen($path . "/question.json", 'w');
            fwrite($fp, $pagejson);
            fclose($fp);
            session()->setFlashdata('success', lang('Messages.Success_0013'));
            return redirect()->to(base_url() . 'Assessment/trainings/question_list_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            return redirect()->to(base_url() . 'Assessment/trainings/question_list_view');
        }
    }
    function exportQuizQuestion()
    {

        if ($response =  $this->requireRole(['46', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if (isset($_POST['scourse_id']) && isset($_POST['course_name'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $data['page_id'];
            $data['course_name'] = $_POST['course_name'];
            $_SESSION['course_name'] = $data['course_name'];
        } else if (isset($_GET['scourse_id']) && isset($_GET['course_name'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
            $data['course_name'] = $_GET['course_name'];
            $data['page_id'] = $_GET['page_id'];
        } else if (isset($_SESSION['scourse_id']) && isset($_SESSION['course_name'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
            $data['course_name'] = $_SESSION['course_name'];
            $data['page_id'] = $_SESSION['page_id'];
        } else {
            return redirect()->to(base_url() . 'Assessment/trainings');
        }
        // $questionjsonfile = $this->assessment_training_model->getQuestionAnswerdata($data['page_id']);
        $coursetimestamp = $this->scorm_page_model->getAllpagedetails($data['scourse_id']);

        $questionjsonfile = $this->assessment_training_model->getpagequestion($data['page_id']);
        // print_r($AttemptsofCourse);
        //  $tocjsonfile = array($tocjsonfile);
        // echo "<pre>";
        // print_r($questionjsonfile);

        // exit();
        $boolean = '';
        if ($questionjsonfile) {
            $postdata = [
                'passingScore' => 70, // Example passing score
                "iframeSrc" => "Quiz.html",
                'questions' => []
            ];
            $j = 0;
            // Iterate through each question in $data
            foreach ($questionjsonfile as $item) {
                $j = $j + 1;
                // Determine question type
                $type = ($item['quiz_type'] == 115) ? 'multiple' : 'single';
                $image = ($item['quiz_type'] == 115) ? 'images/primes.jpg' : 'images/paris.jpg';

                // Reset arrays for options
                $questionoption = [];

                // Get question options
                $getquestionoptions = $this->assessment_training_model->getquestionoptions($item['q_id']);

                foreach ($getquestionoptions as $option) {
                    if ($option['truefalse'] == '1') {
                        $boolean = true;
                    } elseif ($option['truefalse'] == '2') {
                        $boolean = false;
                    }
                    // Add each option to $questionoption array
                    $questionoption[] = [
                        "text" => $option['values'],
                        "value" => $option['values'], // Assuming you want value to be the same as text
                        "correct" => $boolean
                    ];
                }

                // Build question object
                $questionObject = [

                    'type' => $type,
                    'question' => $item['question'],
                    'options' => $questionoption, // Use 'options' instead of 'option' to match JSON structure
                    "image" => "$image",

                ];

                // Add question object to questions array
                $postdata['questions'][] = $questionObject;
            }


            $pagejson = json_encode($postdata, JSON_UNESCAPED_SLASHES);
            $timestamp = $coursetimestamp[0]['createdon'];
            if (!is_dir(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp . '/shared/assets/content/english/pages/' . $data['page_id'])) {
                mkdir('assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp . '/shared/assets/content/english/pages/' . $data['page_id'], 0777, true);
            }
            $path = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp . '/shared/assets/content/english/pages/' . $data['page_id'];
            $fp = fopen($path . "/questions.json", 'w');
            fwrite($fp, $pagejson);
            fclose($fp);
            session()->setFlashdata('success', lang('Messages.Success_0013'));
            return redirect()->to(base_url() . 'Assessment/trainings/question_list_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            return redirect()->to(base_url() . 'Assessment/trainings/question_list_view');
        }
    }
    function review_quiz()
    {
        if ($response =  $this->requireRole(['46', '67', '5', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        } else {
            return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
        }
        if (isset($_POST['page_id'])) {
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $data['page_id'];
        } else if (isset($_GET['page_id'])) {
            $data['page_id'] = $_GET['page_id'];
        } else if (isset($_SESSION['page_id'])) {
            $data['page_id'] = $_SESSION['page_id'];
        } else {
            return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
        }
        if (isset($_POST['type'])) {
            $data['type'] = $_POST['type'];
            $_SESSION['type'] = $data['type'];
        } else if (isset($_GET['type'])) {
            $data['type'] = $_GET['type'];
        } else if (isset($_SESSION['type'])) {
            $data['type'] = $_SESSION['type'];
        } else {
            return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
        }
        $data['header_1'] = 'Course Builder';
        $data['header_link_1'] = 'SCORM/course_builder/Editor';
        $questiondata = $this->assessment_training_model->getexportQuestionAnswerdata($data['page_id']);

        $groupedData = [];
        foreach ($questiondata as $entry) {
            // Group by q_id and o_id to eliminate duplicate options for the same question
            $groupedData[$entry['q_id']][$entry['o_id']][] = $entry;
        }
        $data['groupedData'] = $groupedData;
        $maxOptions = 0;
        foreach ($groupedData as $q_id => $options) {
            $data['maxOptions'] = max($maxOptions, count($options));
        }
        echo view('templates/header_view', $data);
        echo view('assessment/review_quiz_view', $data);
        echo view('templates/footer_view');
    }
}
