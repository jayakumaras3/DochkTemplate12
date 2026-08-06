<?php

namespace App\Controllers\Assessment;

use App\Controllers\BaseController;

use App\Models\Assessment\Assessment_training_model;
use App\Models\SCORM\Scorm_course_model;
use App\Models\SCORM\Scorm_metacategory_model;
use App\Models\Settings\Dropdown_model;

#[\AllowDynamicProperties]
class Bank extends BaseController
{
    private $db;

    public function __construct()
    {
        //$this->is_session_available();
        $this->assessment_training_model = new Assessment_training_model();
        $this->scorm_metacategory_model = new Scorm_metacategory_model();
        $this->scorm_course_model = new Scorm_course_model();
        $this->dropdown_model = new Dropdown_model();
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
        $data = [];
        helper(['form']);
        $data['header'] = 'Assessment';
        $data['create_new_course_link'] = 'Assessment/bank/course_add_view';
        $data['settings_link'] = 'SCORM/scorm_courses/course_settings_view';
        $data['edit_link'] = 'Assessment/bank/course_edit_view';
        $data['question_list_view'] = 'Assessment/bank/question_list_view';
        $data['typeval'] = 9;
        $data['coursesDetails'] = $this->scorm_course_model->getCoursesDetails($data['typeval']);
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/courses_list_view', $data);
        echo view('templates/footer_view');
    }
    public function course_add_view()
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'Assessment';
        $data['header_link'] = 'Assessment/bank';
        $data['sub_header_1'] = 'Create New Assessment';
        $data['form_link'] = 'Assessment/bank/addcourse';

        $data['typeval'] = 9;

        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/courses_add_view', $data);
        echo view('templates/footer_view');
    }
    public function addcourse()
    {
        $data = [];
        helper(['form']);
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
                    'type' => '9',
                    'mode' => 1,
                    'status' => '1',
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    'last_updated_by' =>  session()->get('id_user'),
                    'last_updated_on' => time(),

                ];
                $result = $this->scorm_course_model->addcoursedetails($newdata);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0008'));
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
            return redirect()->to(base_url() . '/Assessment/bank');
        }
    }
    public function course_edit_view()
    {
        $data = [];
        helper(['form']);

        $data['header'] = 'Assessment';
        $data['header_link'] = 'Assessment/bank';
        $data['sub_header_1'] = 'Edit Assessment';
        $data['form_link'] = 'Assessment/bank/editcourse';

        $data['typeval'] = 9;
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] =  $data['scourse_id'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        } else {
            return redirect()->to(base_url() . '/Assessment/bank');
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
                    'language' =>  $this->request->getVar('language'),
                    'course_code' =>  $this->request->getVar('course_code'),
                    'scourse_id' => $this->request->getVar('scourse_id'),
                    'course_name' => $this->request->getVar('course_name'),
                    'description' => $this->request->getVar('description'),
                    'objectives' => $this->request->getVar('objectives'),
                    'duration' => $this->request->getVar('duration'),
                    'mode' => $this->request->getVar('mode'),
                    'launch_link' => $this->request->getVar('launch_link'),
                    'last_updated_by' =>  session()->get('id_user'),
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
            return redirect()->to(base_url() . '/Assessment/bank/course_edit_view');
        }
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_courses/courses_edit_view', $data);
        echo view('templates/footer_view');
    }
    public function question_list_view()
    {
        $data = [];
        helper(['form']);
        $user = session()->get('username');
        if (isset($_POST['scourse_id']) && isset($_POST['course_name'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] =  $data['scourse_id'];
            $data['course_name'] = $_POST['course_name'];
            $_SESSION['course_name'] =  $data['course_name'];
        } else if (isset($_GET['scourse_id']) && isset($_GET['course_name'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
            $data['course_name'] = $_GET['course_name'];
        } else if (isset($_SESSION['scourse_id']) && isset($_SESSION['course_name'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
            $data['course_name'] = $_SESSION['course_name'];
        } else {
            return redirect()->to(base_url() . '/Assessment/bank');
        }
        $data['main_header'] = 'Courses';
        $data['main_header_link'] = 'SCORM/scorm_courses';
        $data['header'] = 'Assessment';
        $data['header_link'] = 'Assessment/bank';
        $data['sub_header_1'] = $data['course_name'] . ' - Assessment Questions';
        $data['edit_link'] = 'Assessment/bank/edit_quetion_view';
        $data['delete_link'] = 'Assessment/bank/deleteQuestion';
        $data['setting_link'] = 'Assessment/bank/add_option_view';
        $data['copyQuestion_link'] = 'Assessment/bank/copyQuestiondetails';
        $data['typeval'] = 9;
        $data['getQuestiondata'] = $this->assessment_training_model->getQuestiondata($data['scourse_id']);
        echo view('templates/header_view', $data);
        echo view('assessment/question_list_view', $data);
        echo view('templates/footer_view');
    }
    public function assignmetacategory()
    {
        $newData = [
            'fk_scourse_id' => $_POST['scourse_id'],
            'fk_sc_mcid' => $_POST['metaCategory'],
            'typeofval' => $_POST['typeofval'],
            'status' => 1,
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_course_model->assignmetacategorydata($newData);
        echo json_encode($result);
    }
    public function deleteasignmetadetails()
    {
        $mc_id = $_POST['mc_id'];
        $newdata = [


            'status' => '0',
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_course_model->deleteassignmeta($newdata, $mc_id);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
            return redirect()->to(base_url() . '/Assessment/bank/course_edit_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . '/Assessment/bank/course_edit_view');
        }
    }
    public function thumbnail_upload()
    {
        $data = [];
        helper(['filesystem']);
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] =  $data['scourse_id'];
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
                                    session()->setFlashdata('success', lang('Messages.Success_0008'));
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
        return redirect()->to(base_url() . '/SCORM/scorm_courses/course_settings_view');
    }
    public function uploadpromovideo()
    {
        $data = [];
        helper(['filesystem']);
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] =  $data['scourse_id'];
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
                            return redirect()->to(base_url() . '/SCORM/scorm_courses/course_settings_view');
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
        return redirect()->to(base_url() . '/SCORM/scorm_courses/course_settings_view');
    }
    public function uploadpdf()
    {
        $data = [];
        helper(['filesystem']);
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] =  $data['scourse_id'];
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
                            return redirect()->to(base_url() . '/SCORM/scorm_courses/course_settings_view');
                        } else {

                            if ($file->move(FCPATH . 'assets/assets/uploads/SCORM_course_pdf/' . $data['scourse_id'], $filename)) {
                                $filepath = FCPATH . 'assets/assets/uploads/SCORM_course_pdf/' . $data['scourse_id'] . '/' . $filename;
                                $newdata = [
                                    'pdf_filename' => $filename,
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
                // session()->setFlashdata('error', lang('Messages.Error_0003'));
                // session()->setFlashdata('alert-class', 'alert-danger');
            }
        }
        return redirect()->to(base_url() . '/SCORM/scorm_courses/course_settings_view');
    }



    public function del_file()
    {
        if (isset($_POST['fileloc'])) {
            $dirPath = $_POST['fileloc'];
            unlink($dirPath);
            session()->setFlashdata('success', lang('Messages.Success_0010'));
        }
        return redirect()->to(base_url() . '/SCORM/scorm_courses/course_settings_view');
    }
    public function add_new_question()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['scourse_id']) && isset($_POST['course_name'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] =  $data['scourse_id'];
            $data['course_name'] = $_POST['course_name'];
            $_SESSION['course_name'] =  $data['course_name'];
        } else if (isset($_GET['scourse_id']) && isset($_GET['course_name'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
            $data['course_name'] = $_GET['course_name'];
        } else if (isset($_SESSION['scourse_id']) && isset($_SESSION['course_name'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
            $data['course_name'] = $_SESSION['course_name'];
        } else {
            return redirect()->to(base_url() . '/Assessment/bank');
        }
        $data['main_header'] = 'Courses';
        $data['main_header_link'] = 'SCORM/scorm_courses';
        $data['header'] = 'Assessment';
        $data['header_link'] = 'Assessment/bank';
        $data['header_1'] = 'Assessment Questions';
        $data['header_link_1'] = 'Assessment/bank/question_list_view';
        $data['sub_header_1'] = 'Create New Question';
        $data['form_link'] = 'Assessment/bank/addQuestions';

        $data['typeval'] = 9;
        $data['allcategories'] = $this->scorm_course_model->getAllMetadata(12);
        $data['CategoryData'] = $this->dropdown_model->getdropdownData(20);
        $data['AssessmentQuestionType'] = $this->dropdown_model->getdropdownData(21);
        echo view('templates/header_view', $data);
        echo view('assessment/add_new_question', $data);
        echo view('templates/footer_view');
    }
    public function addQuestions()
    {
        $data = [];
        helper(['form']);
        if ($this->request->getPost()) {
            $rules = [
                'question' => 'required',
            ];

            if (!$this->validate($rules)) {
                $data['coursevalidation'] = $this->validator;
            } else {

                $newdata = [
                    'scourse_id' => $this->request->getVar('scourse_id'),
                    'question' => $this->request->getVar('question'),
                    'category' => $this->request->getVar('category'),
                    // 'score' => $this->request->getVar('score'),
                    'quiz_type' => $this->request->getVar('quiz_type'),
                    'status' => '1',
                    'last_updated_by' =>  session()->get('id_user'),
                    'last_updated_on' => time(),

                ];
                $result = $this->assessment_training_model->addquestiondetails($newdata);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0008'));
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
            return redirect()->to(base_url() . '/Assessment/bank/question_list_view');
        }
    }
    public function edit_quetion_view()
    {
        $data = [];
        helper(['form']);

        $data['header'] = 'Assessment';
        $data['header_link'] = 'Assessment/bank';
        $data['sub_header_1'] = 'Edit Questions';
        $data['header_1'] = 'Assessment Questions';
        $data['header_link_1'] = 'Assessment/bank/question_list_view';
        $data['form_link'] = 'Assessment/bank/editquestion';
        $data['typeval'] = 9;
        if (isset($_POST['question_id'])) {
            $data['question_id'] = $_POST['question_id'];
            $_SESSION['question_id'] =  $data['question_id'];
        } else if (isset($_GET['question_id'])) {
            $data['question_id'] = $_GET['question_id'];
        } else if (isset($_SESSION['question_id'])) {
            $data['question_id'] = $_SESSION['question_id'];
        } else {
            return redirect()->to(base_url() . '/Assessment/bank');
        }
        $getQuestiondata = $this->assessment_training_model->geteditquestiondetails($data['question_id']);
        $data['row'] = $getQuestiondata[0];
        $data['AssessmentQuestionType'] = $this->dropdown_model->getdropdownData(21);
        $data['allcategories'] = $this->scorm_course_model->getAllMetadata(12);
        echo view('templates/header_view', $data);
        echo view('assessment/question_edit_view', $data);
        echo view('templates/footer_view');
    }
    function editquestion()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['question_id'])) {
            $data['question_id'] = $_POST['question_id'];
            $_SESSION['question_id'] =  $data['question_id'];
        } else if (isset($_GET['question_id'])) {
            $data['question_id'] = $_GET['question_id'];
        } else if (isset($_SESSION['question_id'])) {
            $data['question_id'] = $_SESSION['question_id'];
        } else {
            return redirect()->to(base_url() . '/Assessment/bank');
        }
        if ($this->request->getPost()) {
            $rules = [
                'question' => 'required',
            ];

            if (!$this->validate($rules)) {
                $data['coursevalidation'] = $this->validator;
            } else {

                $newdata = [
                    'question' => $this->request->getVar('question'),
                    'category' => $this->request->getVar('category'),
                    'score' => $this->request->getVar('score'),
                    'quiz_type' => $this->request->getVar('quiz_type'),
                    'last_updated_by' =>  session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                $result = $this->assessment_training_model->updatequestiondetails($newdata, $data['question_id']);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0008'));
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
            return redirect()->to(base_url() . '/Assessment/bank/question_list_view');
        }
    }
    function deleteQuestion()
    {
        $data = [];
        helper(['form']);
        $q_id = $this->request->getVar('question_id');
        $newdata = [
            'status' => 0,
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->assessment_training_model->updatequestiondetails($newdata, $q_id);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
            return redirect()->to(base_url() . '/Assessment/bank/question_list_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . '/Assessment/bank/question_list_view');
        }
    }
    public function add_option_view()
    {
        $data = [];
        helper(['form']);

        $data['header'] = 'Assessment';
        $data['header_link'] = 'Assessment/bank';
        $data['sub_header_1'] = 'Add Option';
        $data['header_1'] = 'Assessment Questions';
        $data['header_link_1'] = 'Assessment/bank/question_list_view';;
        $data['form_link'] = 'Assessment/bank/addoption';
        $data['edit_link'] = 'Assessment/bank/option_edit_view';
        $data['delete_link'] = 'Assessment/bank/deleteOption';
        $data['typeval'] = 9;
        if (isset($_POST['question_id'])) {
            $data['question_id'] = $_POST['question_id'];
            $_SESSION['question_id'] =  $data['question_id'];
        } else if (isset($_GET['question_id'])) {
            $data['question_id'] = $_GET['question_id'];
        } else if (isset($_SESSION['question_id'])) {
            $data['question_id'] = $_SESSION['question_id'];
        } else {
            return redirect()->to(base_url() . '/Assessment/bank');
        }
        $getQuestiondata = $this->assessment_training_model->geteditquestiondetails($data['question_id']);
        $data['row'] = $getQuestiondata[0];
        $data['getoptiondata'] = $this->assessment_training_model->getoptiondaata($data['question_id']);
        $data['AssessmentQuestionType'] = $this->dropdown_model->getdropdownData(21);
        $data['allcategories'] = $this->scorm_course_model->getAllMetadata(12);
        echo view('templates/header_view', $data);
        echo view('assessment/option_add_view', $data);
        echo view('templates/footer_view');
    }
    public function addoption()
    {
        $data = [];

        helper(['form']);
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
                    'last_updated_by' =>  session()->get('id_user'),
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
            return redirect()->to(base_url() . '/Assessment/bank/add_option_view');
        }
    }
    function option_edit_view()
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'Assessment';
        $data['header_link'] = 'Assessment/bank';
        $data['sub_header_1'] = 'Edit Option';
        $data['header_1'] = 'Assessment Questions';
        $data['header_link_1'] = 'Assessment/bank/question_list_view';
        $data['header_2'] = 'Add Option';
        $data['header_link_2'] = 'Assessment/bank/add_option_view';
        $data['form_link'] = 'Assessment/bank/editoption';
        $data['edit_link'] = 'Assessment/bank/option_edit_view';

        $data['typeval'] = 9;
        if (isset($_POST['question_id'])) {
            $data['question_id'] = $_POST['question_id'];
            $_SESSION['question_id'] =  $data['question_id'];
            $data['o_id'] = $_POST['o_id'];
            $_SESSION['o_id'] =  $data['o_id'];
        } else if (isset($_GET['question_id'])) {
            $data['question_id'] = $_GET['question_id'];
        } else if (isset($_SESSION['question_id'])) {
            $data['question_id'] = $_SESSION['question_id'];
            $data['o_id'] = $_SESSION['o_id'];
        } else {
            return redirect()->to(base_url() . '/Assessment/bank');
        }
        $getoptiondetails = $this->assessment_training_model->getoptiondetails($data['o_id']);
        $data['row'] = $getoptiondetails[0];
        echo view('templates/header_view', $data);
        echo view('assessment/option_edit_view', $data);
        echo view('templates/footer_view');
    }
    function editoption()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['o_id'])) {
            $data['o_id'] = $_POST['o_id'];
            $_SESSION['o_id'] =  $data['o_id'];
        } else if (isset($_GET['o_id'])) {
            $data['o_id'] = $_GET['o_id'];
        } else if (isset($_SESSION['o_id'])) {
            $data['o_id'] = $_SESSION['o_id'];
        } else {
            return redirect()->to(base_url() . '/Assessment/bank');
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
                    'last_updated_by' =>  session()->get('id_user'),
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
            return redirect()->to(base_url() . '/Assessment/bank/add_option_view');
        }
    }
    public function deleteOption()
    {
        helper(['form']);
        if (isset($_POST['question_id'])) {
            $data['question_id'] = $_POST['question_id'];
            $_SESSION['question_id'] =  $data['question_id'];
        } else if (isset($_GET['question_id'])) {
            $data['question_id'] = $_GET['question_id'];
        } else if (isset($_SESSION['question_id'])) {
            $data['question_id'] = $_SESSION['question_id'];
        } else {
            return redirect()->to(base_url() . '/Assessment/bank');
        }
        if (isset($_POST['o_id'])) {
            $data['o_id'] = $_POST['o_id'];
            $_SESSION['o_id'] =  $data['o_id'];
        } else if (isset($_GET['o_id'])) {
            $data['o_id'] = $_GET['o_id'];
        } else if (isset($_SESSION['o_id'])) {
            $data['o_id'] = $_SESSION['o_id'];
        } else {
            return redirect()->to(base_url() . '/Assessment/bank');
        }
        $newdata = [
            'status' => 0,
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->assessment_training_model->updateoptiondata($newdata, $data['o_id']);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
            return redirect()->to(base_url() . '/Assessment/bank/add_option_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . '/Assessment/bank/add_option_view');
        }
    }
    public function copyQuestiondetails()
    {
        if (isset($_POST['question_id'])) {
            $data['question_id'] = $_POST['question_id'];
            $_SESSION['question_id'] =  $data['question_id'];
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] =  $data['scourse_id'];
        } else if (isset($_GET['question_id'])) {
            $data['question_id'] = $_GET['question_id'];
        } else if (isset($_SESSION['question_id'])) {
            $data['question_id'] = $_SESSION['question_id'];
            $data['scourse_id'] = $_SESSION['scourse_id'];
        } else {
            return redirect()->to(base_url() . '/Assessment/bank');
        }
        $result = $this->assessment_training_model->copyQuestion($data['question_id']);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0012'));
            return redirect()->to(base_url() . '/Assessment/bank/question_list_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . '/Assessment/bank/question_list_view');
        }
    }
}
