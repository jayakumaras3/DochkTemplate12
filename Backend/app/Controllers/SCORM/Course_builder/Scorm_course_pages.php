<?php

namespace App\Controllers\SCORM\Course_builder;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Dompdf\Dompdf;
use Dompdf\Options;

use App\Controllers\BaseController;

use ZipArchive;
use App\Models\SCORM\Scorm_course_model;
use App\Models\SCORM\Scorm_page_model;
use App\Models\Assessment\Assessment_training_model;
use App\Models\SCORM\Scorm_lanuch_model;
use App\Models\SCORM\Scorm_client_model;

use Config\AssessmentSets\Assessment_english;
use Config\AssessmentSets\Assessment_french;
use Config\AssessmentSets\Assessment_spanish;
use Config\AssessmentSets\Assessment_russian;
use Config\AssessmentSets\Assessment_portuguese;
use Config\AssessmentSets\Assessment_bahasa;
use Config\AssessmentSets\Assessment_arabic;

#[\AllowDynamicProperties]
class Scorm_course_pages extends BaseController
{

    public function __construct()
    {
        $this->is_session_available();
        $this->scorm_course_model = new Scorm_course_model();
        $this->scorm_page_model = new Scorm_page_model();
        $this->scorm_lanuch_model = new Scorm_lanuch_model();
        $this->assessment_training_model = new Assessment_training_model();
        $this->scorm_client_model = new Scorm_client_model();
    }


    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url('my_training'));
            exit();
        }

        $arrayuserlevel = explode(',', $userlevel);
        if (!in_array('5', $arrayuserlevel) && !in_array('46', $arrayuserlevel) && !in_array('67', $arrayuserlevel) && !in_array('44', $arrayuserlevel)) {
            session()->setFlashdata('message', 'You do not have access to view client list');
            header('Location:' . base_url('my_training'));
            exit();
        }
    }

    function index()
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $data = [];

        helper(['form']);
        if (isset($_SESSION['crid'])) {
            $data['scourse_id'] = $_SESSION['crid'];
        } else {
            return redirect()->to(base_url() . 'my_training/read_more');
        }
        $data['header'] = 'Pages';
        $data['courseDetails'] = $this->scorm_course_model->getCourseDetails($data['scourse_id']);
        $data['pagesDetails'] = $this->scorm_page_model->getPageDetails($data['scourse_id']);
        // print_r( $data['pagesDetails']);
        // exit();
        $data['questiondata'] = $this->scorm_page_model->getAssessmentquestion($data['scourse_id']);
        echo view('templates/header_view', $data);
        echo view('page/page_list_view', $data);
        echo view('templates/footer_view');
    }
    function storyboarding()
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
            // $data['course_name'] = $_POST['course_name'];
            // $_SESSION['course_name'] =  $data['course_name'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
            // $data['course_name'] = $_GET['course_name'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
            // $data['course_name'] = $_SESSION['course_name'];
        } else {
            return redirect()->to(base_url() . '/SCORM/course_builder/Editor');
        }
        $data['header'] = 'Storyboard';
        $data['create_new_page_link'] = 'SCORM/course_builder/Scorm_course_pages/page_add_view';

        $data['settings_link'] = 'SCORM/course_builder/Editor';
        $data['edit_link'] = 'SCORM/course_builder/scorm_course_pages/page_edit_view';
        $data['delete_link'] = 'SCORM/course_builder/Scorm_course_pages/deletepage';
        $data['pdf_link'] = 'SCORM/course_builder/Scorm_course_pages/page_pdf_view';
        $data['add_assessment_link'] = 'Assessment/trainings/add_new_question';
        $data['edit_assessment_link'] = 'Assessment/trainings/edit_quetion_view';
        $data['assessment_link'] = 'Assessment/trainings/question_list_view';
        $data['pagesDetails'] = $this->scorm_page_model->getPageDetails($data['scourse_id']);
        $data['questiondata'] = $this->scorm_page_model->getAssessmentquestion($data['scourse_id']);
        $data['Coursedata'] = $this->scorm_page_model->getCoursedata($data['scourse_id']);
        echo view('templates/header_view', $data);
        echo view('page/storyboarding', $data);
        echo view('templates/footer_view');
    }

    public function view_full_sb()
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
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
        $data['header'] = 'Storyboard';

        $data['full_sb'] = $this->scorm_page_model->get_full_sb($data['scourse_id']);

        echo view('templates/header_view', $data);
        echo view('page/storyboarding_view', $data);
        echo view('templates/footer_view');
    }
    function generate_transcript_pdf()
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $dompdf = new Dompdf();
        $data = [];
        helper(['form']);
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


        $data['full_sb'] = $this->scorm_page_model->get_full_sb($data['scourse_id']);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('defaultFont', 'Arial');
        // if (!empty($data['userexitInterdata'])) {
        $data['logo'] = $this->imageToBase64(ROOTPATH . 'assets/assets/img/TS_Logo.svg');

        $html = view('page/pdf_transcript_view', $data);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $canvas = $dompdf->getCanvas();
        $font = $dompdf->getFontMetrics()->getFont('Segoe UI', 'normal');
        $fontSize = 8;
        $y = 820;
        $canvas->page_text(558, $y, "{PAGE_NUM}", $font, $fontSize, [0, 0, 0]);


        $dompdf->getOptions()->set('isHtml5ParserEnabled', true);
        $dompdf->getOptions()->set('isPhpEnabled', true);
        $dompdf->loadHtml($html);

        $dompdf->stream($data['full_sb'][0]['course_name'] . '.pdf', ['Attachment' => true]);
    }
    private function imageToBase64($path)
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $path = $path;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }

    public function page_add_view()
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
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

        $data['page_name'] = $this->request->getVar('nxt_pageid');
        echo view('templates/header_view', $data);
        echo view('page/pages_add_view', $data);
        echo view('templates/footer_view');
    }

    public function page_add_sub_page()
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        } else {
            return redirect()->to(base_url() . '/SCORM/course_builder/Editor');
        }


        $data['page_number'] = $_POST['page_number'];


        if (isset($_POST['page_id'])) {
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $data['page_id'];
        } else if (isset($_SESSION['page_id'])) {
            $data['page_id'] = $_SESSION['page_id'];
        } else {
            return redirect()->to(base_url() . '/SCORM/course_builder/Editor');
        }


        echo view('templates/header_view', $data);
        echo view('page/pages_add_sub_page', $data);
        echo view('templates/footer_view');
    }
    public function addpage()
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        } else {
            return redirect()->to(base_url() . '/SCORM/course_builder/Editor');
        }

        $data['header'] = 'Pages';
        $data['header_link'] = 'SCORM/course_builder/Editor';
        $data['sub_header_1'] = 'Create New Page';
        $data['form_link'] = 'SCORM/course_builder/Scorm_course_pages/addpage';
        if ($this->request->getPost()) {
            $rules = [
                'page_name' => 'required',
            ];

            if (!$this->validate($rules)) {
                $data['pagevalidation'] = $this->validator;
            } else {
                $type = $this->request->getVar('type');
                $page_number = $this->request->getVar('page_number');
                $page_name = $this->request->getVar('page_name');
                $newdata = [
                    'fk_course_id' => $data['scourse_id'],
                    'page_name' => $this->request->getVar('page_name'),
                    'sub_page_main' => 0,
                    'page_number' => $this->request->getVar('page_number'),
                    'type' => $type,
                    'status' => '1',
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    'last_update_by' => session()->get('id_user'),
                    'last_update_on' => time()
                ];
                $result = $this->scorm_page_model->addpagedetails($newdata);
                // print_r($result);
                // exit();

                if ($result) {
                    // print_r($result);
                    // exit();
                    $_SESSION['page_name'] = $page_name;
                    $_SESSION['scourse_id'] = $data['scourse_id'];
                    $_SESSION['page_id'] = $result['page_id'];
                    $_SESSION['page_number'] = $page_number;
                    if ($type == 4) {
                        $enabled_default = array(1, 2, 3, 4);

                        for ($i = 0; $i < count($enabled_default); $i++) {
                            if ($enabled_default[$i] == 1) {
                                $value = 'Disabled';
                            }
                            if ($enabled_default[$i] == 2) {
                                $value = 'Disabled';
                            }
                            if ($enabled_default[$i] == 3) {
                                $value = 'Enabled';
                            }
                            if ($enabled_default[$i] == 4) {
                                $value = 'Enabled';
                            }
                            $tempdata = array(
                                'scourse_id' => $data['scourse_id'],
                                'page_id' => $result['page_id'],
                                'type' => $enabled_default[$i],
                                'value' => $value,
                                'status' => 1,
                                'last_updated_by' => session()->get('id_user'),
                                'last_updated_on' => time(),
                            );
                            $this->assessment_training_model->add_settings($tempdata);
                        }

                        $value_default = array(21, 22, 23, 24);
                        $value_default_input = array('', '', 80, 2);
                        for ($i = 0; $i < count($value_default); $i++) {
                            $tempdata = array(
                                'scourse_id' => $data['scourse_id'],
                                'page_id' => $result['page_id'],
                                'type' => $value_default[$i],
                                'value' => $value_default_input[$i],
                                'status' => 1,
                                'last_updated_by' => session()->get('id_user'),
                                'last_updated_on' => time(),
                            );
                            $this->assessment_training_model->add_settings($tempdata);
                        }
                    }

                    session()->setFlashdata('success', lang('Messages.Success_0011'));
                    // return redirect()->to(base_url('SCORM/course_builder/scorm_course_pages/page_edit_view'));
                    return redirect()->to(base_url('SCORM/course_builder/Editor'));
                    // }
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    // return redirect()->to(base_url('SCORM/course_builder/Scorm_course_pages/storyboarding'));
                    return redirect()->to(base_url('SCORM/course_builder/Editor'));
                }
            }
        }

        echo view('templates/header_view', $data);
        echo view('page/pages_add_view', $data);
        echo view('templates/footer_view');
    }


    function addsubpage()
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        } else {
            return redirect()->to(base_url() . '/SCORM/course_builder/Editor');
        }
        if (isset($_POST['page_id'])) {
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $data['page_id'];
        } else if (isset($_SESSION['page_id'])) {
            $data['page_id'] = $_SESSION['page_id'];
        } else {
            return redirect()->to(base_url() . '/SCORM/course_builder/Editor');
        }
        $page_number = $this->request->getVar('page_number');
        $current_sub_pages = count($this->scorm_page_model->getSubpagecontent($page_number, $data['scourse_id']));
        $newsubpage_id = $page_number + ($current_sub_pages + 1) / 100;

        if ($this->request->getPost()) {
            $newdata = [
                'fk_course_id' => $data['scourse_id'],
                'page_name' => $this->request->getVar('page_name'),
                'sub_page_main' => $this->request->getVar('page_number'),
                'type' => $this->request->getVar('type'),
                'page_number' => $newsubpage_id,
                'type' => $this->request->getVar('type'),
                'status' => '1',
                'createdby' => session()->get('id_user'),
                'createdon' => time(),
                'last_update_by' => session()->get('id_user'),
                'last_update_on' => time()
            ];
            $result = $this->scorm_page_model->addpagedetails($newdata);


            if ($result) {
                $_SESSION['scourse_id'] = $data['scourse_id'];
                $_SESSION['page_id'] = $result['page_id'];
                session()->setFlashdata('success', lang('Messages.Success_0011'));
                return redirect()->to(base_url('SCORM/course_builder/scorm_course_pages/page_edit_view'));
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url('SCORM/course_builder/Scorm_course_pages/storyboarding'));
            }
        }

        echo view('templates/header_view', $data);
        echo view('page/pages_add_view', $data);
        echo view('templates/footer_view');
    }
    function page_edit_view()
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $data = [];
        helper(['form']);

        if (isset($_POST['crid'])) {
            $data['crid'] = $_POST['crid'];
            $_SESSION['crid'] = $data['crid'];
            $_SESSION['scourse_id'] = $data['crid'];
        } elseif (isset($_SESSION['crid'])) {
            $data['course_id'] = $_SESSION['crid'];
        } elseif (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        } else {
            return redirect()->to(base_url() . '/SCORM/course_builder/Editor');
        }
        if (isset($_POST['page_id'])) {
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $data['page_id'];
        } elseif (isset($_SESSION['page_id'])) {
            $data['page_id'] = $_SESSION['page_id'];
        } else {
            return redirect()->to(base_url() . '/SCORM/course_builder/Editor');
        }

        if (isset($_POST['page_number'])) {
            $data['page_number'] = $_POST['page_number'];
            $_SESSION['page_number'] = $data['page_number'];
        } elseif (isset($_SESSION['page_number'])) {
            $data['page_number'] = $_SESSION['page_number'];
        } else {
            return redirect()->to(base_url() . '/SCORM/course_builder/Editor');
        }
        if (isset($_POST['page_name'])) {
            $data['page_name'] = $_POST['page_name'];
            $_SESSION['page_name'] = $data['page_name'];
        } elseif (isset($_SESSION['page_name'])) {
            $data['page_name'] = $_SESSION['page_name'];
        } else {
            return redirect()->to(base_url() . '/SCORM/course_builder/Editor');
        }

        $data['header'] = 'Pages';
        $data['header_link'] = 'SCORM/course_builder/Editor';
        $data['sub_header_1'] = 'Edit Page';
        $data['form_link_add'] = 'Assessment/trainings/addQuestions';
        $data['form_link'] = 'SCORM/course_builder/scorm_course_pages/editpage';

        $pagedata = $this->scorm_page_model->getpagedata_number($data['page_number'], $data['course_id']);
        // print_r($pagedata);
        // exit();
        if (!empty($pagedata)) {
            $data['row'] = $pagedata[0];
        } else {
            return redirect()->to(base_url() . '/SCORM/course_builder/Editor');
        }
        $data['page_content'] = $this->scorm_page_model->getpagecontent($data['page_number'], $data['course_id']);

        $data['sub_page_content'] = $this->scorm_page_model->getSubpagecontent($data['page_number'], $data['course_id']);

        $data['page_id'] = $data['row']['page_id'];

        $currentpagenum = $data['row']['page_number'];
        $fk_course_id = $data['row']['fk_course_id'];
        $prepage = $currentpagenum - 1;
        $nextpage = $currentpagenum + 1;

        $data['prev_page'] = $this->scorm_page_model->get_nxt_page($prepage, $fk_course_id);
        $data['next_page'] = $this->scorm_page_model->get_nxt_page($nextpage, $fk_course_id);


        if ($data['row']['type'] == 5 || $data['row']['type'] == 6) {
            return redirect()->to(base_url() . 'Assessment/trainings/edit_quetion_view');
        }
        if ($data['row']['type'] == 4) {
            return redirect()->to(base_url() . 'Assessment/trainings/question_list_view');
        } else {
            echo view('templates/header_view', $data);
            echo view('page/pages_edit_view', $data);
            echo view('templates/footer_view');
        }
    }
    function editpage()
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
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
        return redirect()->to(base_url('SCORM/course_builder/scorm_course_pages/page_edit_view'));
    }
    function page_del_content()
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $data = [];
        if (isset($_POST['scourse_id'])) {
            $_SESSION['crid'] = $_POST['scourse_id'];
        }

        helper(['form']);
        if (isset($_POST['page_content_id'])) {
            $data['page_content_id'] = $_POST['page_content_id'];
            $newdata = [
                'status' => 0,
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),

            ];
            $this->scorm_page_model->delPageContent($newdata, $data['page_content_id']);
            session()->setFlashdata('success', lang('Messages.Success_0005'));
        }
        return redirect()->to(base_url('SCORM/course_builder/scorm_course_pages/page_edit_view'));
    }

    function deletepage()
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['page_id'])) {
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $data['page_id'];
        } else if (isset($_GET['page_id'])) {
            $data['page_id'] = $_GET['page_id'];
        } else if (isset($_SESSION['page_id'])) {
            $data['page_id'] = $_SESSION['page_id'];
        } else {
            return redirect()->to(base_url() . 'SCORM/course_builder/Scorm_course_pages/storyboarding');
        }
        if (isset($_POST['scourse_id']) && isset($_POST['type'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
            $data['type'] = $_POST['type'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        } else {
            return redirect()->to(base_url() . 'SCORM/course_builder/Scorm_course_pages/storyboarding');
        }
        if ($data['type'] == 4 || $data['type'] == 5 || $data['type'] == 6) {
            $coursedata = $this->scorm_page_model->getCoursedata($data['scourse_id']);
            $dir = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $coursedata[0]['createdon'] . '/shared/assets/content/english/pages/' . $data['page_id'];
            $this->emptyDir($dir);
            rmdir($dir);
        }
        $newdata = [
            'status' => $this->request->getVar('status'),
            'last_update_by' => session()->get('id_user'),
            'last_update_on' => time(),

        ];
        $result = $this->scorm_page_model->editpagedetails($newdata, $data['page_id']);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url('SCORM/course_builder/Scorm_course_pages/storyboarding'));
    }
    function add_content()
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['page_id'])) {
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $data['page_id'];
        } else if (isset($_GET['page_id'])) {
            $data['page_id'] = $_GET['page_id'];
        } else if (isset($_SESSION['page_id'])) {
            $data['page_id'] = $_SESSION['page_id'];
        } else {
            return redirect()->to(base_url() . 'SCORM/course_builder/Scorm_course_pages/storyboarding');
        }
        $newdata = [
            'page_id' => $data['page_id'],
            'page_sequense' => $this->request->getVar('sequence'),
            'audio' => $this->request->getVar('audio'),
            'on_screen_text' => $this->request->getVar('on_screen_text'),
            'production_notes' => $this->request->getVar('production_notes'),
            'status' => 1,
            'created_by' => session()->get('id_user'),
            'created_on' => time(),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_page_model->add_content_to_page($newdata);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0011'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url('SCORM/course_builder/scorm_course_pages/page_edit_view'));
    }
    function update_content()
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['page_id'])) {
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $data['page_id'];
            $data['page_content_id'] = $_POST['page_content_id'];
            $_SESSION['page_content_id'] = $data['page_content_id'];
        } else if (isset($_GET['page_id'])) {
            $data['page_id'] = $_GET['page_id'];
            $data['page_content_id'] = $_GET['page_content_id'];
        } else if (isset($_SESSION['page_id'])) {
            $data['page_id'] = $_SESSION['page_id'];
            $data['page_content_id'] = $_SESSION['page_content_id'];
        } else {
            return redirect()->to(base_url() . 'SCORM/course_builder/Scorm_course_pages/storyboarding');
        }
        $newdata = [
            'page_sequense' => $this->request->getVar('sequence'),
            'audio' => $this->request->getVar('audio'),
            'on_screen_text' => $this->request->getVar('on_screen_text'),
            'production_notes' => $this->request->getVar('production_notes'),
            'status' => 1,
            'created_by' => session()->get('id_user'),
            'created_on' => time(),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_page_model->update_content_to_page($newdata, $data['page_content_id']);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0011'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url('SCORM/course_builder/scorm_course_pages/page_edit_view'));
    }
    function page_settings_view()
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['course_id'])) {
            $data['course_id'] = $_POST['course_id'];
        } elseif (isset($_SESSION['crid'])) {
            $data['course_id'] = $_SESSION['crid'];
        } elseif (isset($_SESSION['course_id'])) {
            $data['course_id'] = $_SESSION['course_id'];
        } elseif (isset($_SESSION['scourse_id'])) {
            $data['course_id'] = $_SESSION['scourse_id'];
        } else {
            return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
        }
        if (isset($_POST['page_number'])) {
            $data['page_number'] = $_POST['page_number'];
            $_SESSION['page_number'] = $data['page_number'];
        } elseif (isset($_SESSION['page_number'])) {
            $data['page_number'] = $_SESSION['page_number'];
        } else {
            return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
        }
        $data['getAssessmentSettings'] = $this->assessment_training_model->get_assessmentCourselevel_settings($data['course_id']);

        $course_lang = $_SESSION['course_lang'];
        $data['course_lang'] = $course_lang;
        if ($course_lang == 'French') {
            $data['assessment_export_sets'] = Assessment_french::$assessment_export_sets;
            $data['assessment_scqmcq_sets'] = Assessment_french::$assessment_scqmcq_sets;
        } elseif ($course_lang == 'Spanish') {
            $data['assessment_export_sets'] = Assessment_spanish::$assessment_export_sets;
            $data['assessment_scqmcq_sets'] = Assessment_spanish::$assessment_scqmcq_sets;
        } elseif ($course_lang == 'Russian') {
            $data['assessment_export_sets'] = Assessment_russian::$assessment_export_sets;
            $data['assessment_scqmcq_sets'] = Assessment_russian::$assessment_scqmcq_sets;
        } elseif ($course_lang == 'Portuguese') {
            $data['assessment_export_sets'] = Assessment_portuguese::$assessment_export_sets;
            $data['assessment_scqmcq_sets'] = Assessment_portuguese::$assessment_scqmcq_sets;
        } elseif ($course_lang == 'Bahasa') {
            $data['assessment_export_sets'] = Assessment_bahasa::$assessment_export_sets;
            $data['assessment_scqmcq_sets'] = Assessment_bahasa::$assessment_scqmcq_sets;
        } elseif ($course_lang == 'Arabic') {
            $data['assessment_export_sets'] = Assessment_arabic::$assessment_export_sets;
            $data['assessment_scqmcq_sets'] = Assessment_arabic::$assessment_scqmcq_sets;
        } else {
            $data['assessment_export_sets'] = Assessment_english::$assessment_export_sets;
            $data['assessment_scqmcq_sets'] = Assessment_english::$assessment_scqmcq_sets;
        }

        $data['header'] = 'Pages';
        $data['header_link'] = 'SCORM/course_builder/Editor';
        $data['transcript_link'] = 'SCORM/course_builder/Scorm_course_pages/page_transcript_view';
        $data['video_link'] = 'SCORM/course_builder/Scorm_course_pages/addpage';
        $data['vtt_link'] = 'SCORM/course_builder/scorm_course_pages/uploadvtt';

        $data['coursedetails'] = $this->scorm_lanuch_model->coursedetails($data['course_id']);
        $data['pagedetails'] = $this->scorm_lanuch_model->getpagedetails($data['course_id']);
        $pagedata = $this->scorm_page_model->getpagedata_number($data['page_number'], $data['course_id']);
        $data['row'] = $pagedata[0];
        $data['page_id'] = $data['row']['page_id'];
        $data['page_name'] = $data['row']['page_name'];

        $data['page_content'] = $this->scorm_page_model->getpagecontent($data['page_number'], $data['course_id']);

        $currentpagenum = $data['row']['page_number'];
        $fk_course_id = $data['row']['fk_course_id'];
        $prepage = $currentpagenum - 1;
        $nextpage = $currentpagenum + 1;
        $data['sub_page_content'] = $this->scorm_page_model->getSubpagecontent($currentpagenum, $fk_course_id);
        $data['prev_page'] = $this->scorm_page_model->get_nxt_page($prepage, $fk_course_id);
        $data['next_page'] = $this->scorm_page_model->get_nxt_page($nextpage, $fk_course_id);


        $data['pagetraanscript'] = $this->scorm_page_model->getpagetraanscript($data['page_id']);
        $data['pageArticulate'] = $this->scorm_page_model->getpageArticulate($data['page_id']);
        $data['pageVideo'] = $this->scorm_page_model->getpageVideo($data['page_id'], 1);
        $data['pageVtt'] = $this->scorm_page_model->getpageVideo($data['page_id'], 2);

        $data['getAllFileOwner'] = $this->scorm_page_model->getAllFileOwner($data['page_id']);
        $data['getAllvideoFileOwner'] = $this->scorm_page_model->getAllvideoFileOwner($data['page_id']);

        if ($data['row']['type'] == 5 || $data['row']['type'] == 6) {
            $getQuestiondata = $this->assessment_training_model->getQuestionDetails($data['page_id']);
            $data['question'] = $getQuestiondata[0];
            $data['question_options'] = $this->assessment_training_model->getoptiondaata($data['question']['q_id']);
        }
        $data['feedback'] = $this->scorm_lanuch_model->getAllFeedbackByPageID($data['page_id']);
        if (isset($data['feedback'])) {
            $review_replies = [];
            foreach ($data['feedback'] as $feedback) {
                // Fetch all replies for each feedback
                $replies[$feedback['feedbackid']] = $this->scorm_lanuch_model->getAllFeedback_replies($feedback['feedbackid']);
            }

            // Pass the feedbacks and replies to the view
            $data['review_replies'] = $review_replies;
        }
        $data['getUserlatestclientCourseByScenario'] = $this->scorm_client_model->getUserlatestclientCourseByScenario($fk_course_id, 1);
        echo view('templates/header_view', $data);

        $feedbacks = $this->scorm_lanuch_model->getAllQAFeedback($data['page_id']);
        $replies = [];
        foreach ($feedbacks as $feedback) {
            // Fetch all replies for each feedback
            $replies[$feedback['feedbackid']] = $this->scorm_lanuch_model->getAllFeedback_replies($feedback['feedbackid']);
        }

        // Pass the feedbacks and replies to the view
        $data = [
            'feedbacks' => $feedbacks,
            'replies' => $replies
        ];

        echo view('page/page_settings_view', $data);
        echo view('templates/footer_view');
    }
    function page_transcript_view()
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
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
        $data['header'] = 'Pages';
        $data['header_link'] = 'SCORM/course_builder/Editor';
        $data['header_1'] = 'Page settings';
        $data['header_link_1'] = 'SCORM/course_builder/Editor';
        $data['sub_header_1'] = 'Add Transcript Page';
        $data['form_link'] = 'SCORM/course_builder/Scorm_course_pages/addtranscript';
        $data['pagedata'] = $this->scorm_page_model->gettranscriptpagedata($data['page_id']);
        if (!empty($data['pagedata'])) {
            $data['row'] = $data['pagedata'][0];
        } else {
            $data['row'] = 0;
        }

        echo view('templates/header_view', $data);
        // echo view('SCORM/course_builder/header', $data);
        echo view('page/pages_transcript_view', $data);
        echo view('templates/footer_view');
    }
    function addtranscript()
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
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
                'language' => 'required',
                'transcript' => 'required',
            ];

            if (!$this->validate($rules)) {
                $data['coursevalidation'] = $this->validator;
            } else {
                $newdata = [
                    'page_id' => $data['page_id'],
                    'language' => $this->request->getVar('language'),
                    'transcript' => $this->request->getVar('transcript'),
                    'status' => '1',
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                ];
                // print_r($newdata);
                // exit();
                $result = $this->scorm_page_model->addtrancript($newdata);
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
    function page_edittranscript_view()
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['t_id'])) {
            $data['t_id'] = $_POST['t_id'];
            $_SESSION['t_id'] = $data['t_id'];
        } else if (isset($_GET['t_id'])) {
            $data['t_id'] = $_GET['t_id'];
        } else if (isset($_SESSION['t_id'])) {
            $data['t_id'] = $_SESSION['t_id'];
        } else {
            return redirect()->to(base_url() . '/SCORM/course_builder/Editor');
        }
        $data['header'] = 'Pages';
        $data['header_link'] = 'SCORM/course_builder/Editor';
        $data['header_1'] = 'Page settings';
        $data['header_link_1'] = 'SCORM/course_builder/Editor';
        $data['sub_header_1'] = 'Edit Transcript Page';
        $data['form_link'] = 'SCORM/course_builder/Scorm_course_pages/edittranscript';
        $pagedata = $this->scorm_page_model->transcriptdata($data['t_id']);
        $data['row'] = $pagedata[0];
        echo view('templates/header_view', $data);
        echo view('page/pages_edittranscript_view', $data);
        echo view('templates/footer_view');
    }
    function edittranscript()
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['t_id'])) {
            $data['t_id'] = $_POST['t_id'];
            $_SESSION['t_id'] = $data['t_id'];
        } else if (isset($_GET['t_id'])) {
            $data['t_id'] = $_GET['t_id'];
        } else if (isset($_SESSION['t_id'])) {
            $data['t_id'] = $_SESSION['t_id'];
        } else {
            return redirect()->to(base_url() . '/SCORM/course_builder/Editor');
        }
        $user = session()->get('username');

        if ($this->request->getPost()) {
            //print_r("sss");
            $rules = [
                'transcript' => 'required',
            ];

            if (!$this->validate($rules)) {
                $data['coursevalidation'] = $this->validator;
            } else {
                $newdata = [
                    'language' => $this->request->getVar('language'),
                    'transcript' => $this->request->getVar('transcript'),
                    'status' => '1',
                    'last_update_by' => session()->get('id_user'),
                    'last_update_on' => time(),

                ];
                // print_r($newdata);
                // exit();
                $result = $this->scorm_page_model->edittrascriptpage($newdata, $data['t_id']);

                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0008'));
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
        }
        return redirect()->to(base_url('SCORM/course_builder/Scorm_course_pages/page_edittranscript_view'));
    }
    function del_transcript()
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['t_id'])) {
            $data['t_id'] = $_POST['t_id'];
            $_SESSION['t_id'] = $data['t_id'];
        } else if (isset($_GET['t_id'])) {
            $data['t_id'] = $_GET['t_id'];
        } else if (isset($_SESSION['t_id'])) {
            $data['t_id'] = $_SESSION['t_id'];
        } else {
            return redirect()->to(base_url() . '/SCORM/course_builder/Editor');
        }
        $newdata = [
            'status' => $this->request->getVar('status'),


            'last_update_by' => session()->get('id_user'),
            'last_update_on' => time(),

        ];
        $result = $this->scorm_page_model->edittrascriptpage($newdata, $data['t_id']);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url() . '/SCORM/course_builder/Editor');
    }
    function create_json_file_new($scourse_id, $lmsStatus)
    {

        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $data = [];
        helper(['form']);

        if (isset($scourse_id)) {
            $data['scourse_id'] = $scourse_id;
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        } else {
            return redirect()->to(base_url() . '/SCORM/course_builder/Editor');
        }
        $tocjsonfile = $this->scorm_page_model->getPagedetailsofcourse($data['scourse_id']);

        if ($tocjsonfile) {
            if ($tocjsonfile[0]['language'] == 'French') {
                $assessment_export_sets = Assessment_french::$assessment_export_sets;
            } elseif ($tocjsonfile[0]['language'] == 'Spanish') {
                $assessment_export_sets = Assessment_spanish::$assessment_export_sets;
            } elseif ($tocjsonfile[0]['language'] == 'Russian') {
                $assessment_export_sets = Assessment_russian::$assessment_export_sets;
            } elseif ($tocjsonfile[0]['language'] == 'Portuguese') {
                $assessment_export_sets = Assessment_portuguese::$assessment_export_sets;
            } elseif ($tocjsonfile[0]['language'] == 'Bahasa') {
                $assessment_export_sets = Assessment_bahasa::$assessment_export_sets;
            } elseif ($tocjsonfile[0]['language'] == 'Arabic') {
                $assessment_export_sets = Assessment_arabic::$assessment_export_sets;
            } else {
                $assessment_export_sets = Assessment_english::$assessment_export_sets;
            }
            // print_r($tocjsonfile);
            // exit();
            $pagesData = [];  // Array to hold all pages data
            foreach ($tocjsonfile as $eachpage) {
                $transcript = (isset($eachpage['transcript']) && ($eachpage['transcript'] != '')) ? $eachpage['transcript'] : '" "';
                $type = '';
                $path = '';
                $pathfilename = '';
                $functionName = '';
                $onendnextscrn = '';
                if ($eachpage['type'] == '5' || $eachpage['type'] == '6') {
                    $this->exportQuestion($eachpage['page_id'], $data['scourse_id'], $eachpage['type'], $eachpage['language']);
                } elseif ($eachpage['type'] == '4') {
                    $this->exportQuizQuestion($eachpage['page_id'], $data['scourse_id'], $eachpage['type'], $eachpage['language']);
                } elseif ($eachpage['type'] == '10' || $eachpage['type'] == '11' || $eachpage['type'] == '12') {
                    $this->exportTextPage($eachpage, $data['scourse_id']);
                }
                if ($eachpage['type'] == '1') {
                    $type = 'captivate';
                    $path = "assets/Articulate/" . $eachpage['page_id'] . "/story.html";
                    $pathfilename = "story.html";
                    $functionName = "functionName";
                    $onendnextscrn = 'story_html5';
                } elseif ($eachpage['type'] == '2' || $eachpage['type'] == '9') {
                    $type = 'video';
                    $path = "assets/video/" . $eachpage['filename'];
                    $pathfilename = $eachpage['filename'];
                    $functionName = "onendnextscrn";
                    $onendnextscrn = 'false';
                } elseif ($eachpage['type'] == '3' || $eachpage['type'] == '4' || $eachpage['type'] == '5' || $eachpage['type'] == '6') {
                    $type = ($eachpage['type'] == '5' || $eachpage['type'] == '4' || $eachpage['type'] == '6') ? 'captivate' : 'captivate';
                    if ($eachpage['type'] == '4' || $eachpage['type'] == '5' || $eachpage['type'] == '6') {
                        $path = "assets/Quiz/" . $eachpage['page_id'] . "/index.html";
                    }
                    if ($eachpage['type'] == '3') {
                        $path = "assets/html/" . $eachpage['page_id'] . "/Screen_01.html";
                    }
                    $pathfilename = "Screen_01.html";
                    $functionName = "functionName";
                    $onendnextscrn = ($eachpage['type'] == '5' || $eachpage['type'] == '6' || $eachpage['type'] == '4') ? 'captivate' : 'captivate';
                } elseif ($eachpage['type'] == '10' || $eachpage['type'] == '11' || $eachpage['type'] == '12') {
                    $type = 'captivate';
                    $path = "assets/html/" . $eachpage['page_id'] . "/Screen_01.html";
                    $pathfilename = "Screen_01.html";
                    $functionName = "functionName";
                    $onendnextscrn = 'captivate';
                }
                // $sidebar = ($eachpage['page_number'] == '1');
                // $header = ($eachpage['page_number'] == '1');
                // $footer = ($eachpage['page_number'] == '1');
                // $fullScreen = ($eachpage['page_number'] != '1');

                $postdata =
                    [
                        "name" => $eachpage['page_id'],
                        "title" => $eachpage['title'],
                        "header" => $eachpage['header'],
                        "transcript" => $transcript,
                        "settings" => [
                            "sidebar" => true,
                            "header" => true,
                            "footer" => true,
                            "fullScreen" => false,
                            "pageNumber" => "Page 1/11",
                            "module" => 0,
                            "preloader" => [
                                [
                                    "src" => $path,
                                    "id" => $pathfilename
                                ],
                                [
                                    "src" => "theme/scripts/Screen_01.js",
                                    "id" => "Screen_01"
                                ]
                            ],
                            "content" => [
                                [
                                    "type" => $type,
                                    "path" => $path,
                                    $functionName => $onendnextscrn
                                ]
                            ]
                        ]

                    ];


                // Collect page data into the array
                $pagesData[] = $postdata;
            }
            $timestamp = $tocjsonfile[0]['createdon'];


            $pagejson = json_encode($pagesData, JSON_UNESCAPED_SLASHES);
            $tocjson = '{"0": ' . $pagejson . '}';
            if (!is_dir(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp . '/assets/json')) {
                mkdir('assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp . '/assets/json', 0777, true);
            }
            $path = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp . '/assets/json';
            $fp = fopen($path . "/toc.json", 'w');
            fwrite($fp, $tocjson);
            fclose($fp);

            $page_type = array_column($tocjsonfile, 'type');
            // print_r($page_type);
            // exit();
            if (in_array('9', $page_type)) {
                $AudioVersionEnable = 'true';
            } else {
                $AudioVersionEnable = 'false';
            }

            // print_r($data['getAllFileOwner']);
            // exit();
            // Course level settings
            $Menutitle = $this->scorm_course_model->getAssignmetadatabyID($scourse_id, 50);
            $Menutitle = (isset($Menutitle[0]['value']) && ($Menutitle[0]['value'] != '')) ? $Menutitle[0]['value'] : $assessment_export_sets['50'];

            $NextTitle = $this->scorm_course_model->getAssignmetadatabyID($scourse_id, 51);
            $NextTitle = (isset($NextTitle[0]['value']) && ($NextTitle[0]['value'] != '')) ? $NextTitle[0]['value'] : $assessment_export_sets['51'];

            $Prevtitle = $this->scorm_course_model->getAssignmetadatabyID($scourse_id, 52);
            $Prevtitle = (isset($Prevtitle[0]['value']) && ($Prevtitle[0]['value'] != '')) ? $Prevtitle[0]['value'] : $assessment_export_sets['52'];

            $MenuName = $this->scorm_course_model->getAssignmetadatabyID($scourse_id, 53);
            $MenuName = (isset($MenuName[0]['value']) && ($MenuName[0]['value'] != '')) ? $MenuName[0]['value'] : $assessment_export_sets['53'];

            $TranscriptName = $this->scorm_course_model->getAssignmetadatabyID($scourse_id, 54);
            $TranscriptName = (isset($TranscriptName[0]['value']) && ($TranscriptName[0]['value'] != '')) ? $TranscriptName[0]['value'] : $assessment_export_sets['54'];

            $ResumeTitle = $this->scorm_course_model->getAssignmetadatabyID($scourse_id, 55);
            $ResumeTitle = (isset($ResumeTitle[0]['value']) && ($ResumeTitle[0]['value'] != '')) ? $ResumeTitle[0]['value'] : $assessment_export_sets['55'];

            $ResumeHeader = $this->scorm_course_model->getAssignmetadatabyID($scourse_id, 56);
            $ResumeHeader = (isset($ResumeHeader[0]['value']) && ($ResumeHeader[0]['value'] != '')) ? $ResumeHeader[0]['value'] : $assessment_export_sets['56'];

            $ResumeYES = $this->scorm_course_model->getAssignmetadatabyID($scourse_id, 57);
            $ResumeYES = (isset($ResumeYES[0]['value']) && ($ResumeYES[0]['value'] != '')) ? $ResumeYES[0]['value'] : $assessment_export_sets['57'];

            $ResumeNO = $this->scorm_course_model->getAssignmetadatabyID($scourse_id, 58);
            $ResumeNO = (isset($ResumeNO[0]['value']) && ($ResumeNO[0]['value'] != '')) ? $ResumeNO[0]['value'] : $assessment_export_sets['58'];

            $master = $this->scorm_course_model->getAssignmetadatabyID($scourse_id, 62);
            $master = (isset($master[0]['value']) && ($master[0]['value'] != '') && ($master[0]['value'] == '1')) ? 'true' : 'false';

            $PageLevelCourseComplete = $this->scorm_course_model->getAssignmetadatabyID($scourse_id, 63);
            $PageLevelCourseComplete = (isset($PageLevelCourseComplete[0]['value']) && ($PageLevelCourseComplete[0]['value'] != '') && ($PageLevelCourseComplete[0]['value'] == '1')) ? 'true' : 'false';

            $LearningAidsTitle = $this->scorm_course_model->getAssignmetadatabyID($scourse_id, 75);
            $LearningAidsTitle = (isset($LearningAidsTitle[0]['value']) && ($LearningAidsTitle[0]['value'] != '')) ? $LearningAidsTitle[0]['value'] : $assessment_export_sets['75'];

            $ExitCourseTitle = $this->scorm_course_model->getAssignmetadatabyID($scourse_id, 76);
            $ExitCourseTitle = (isset($ExitCourseTitle[0]['value']) && ($ExitCourseTitle[0]['value'] != '')) ? $ExitCourseTitle[0]['value'] : $assessment_export_sets['76'];

            $CertificateEnabled = $this->scorm_course_model->getAssignmetadatabyID($scourse_id, 74);
            if (isset($CertificateEnabled[0]['value']) && ($CertificateEnabled[0]['value'] != '')) {
                if ($CertificateEnabled[0]['value'] == '1') {
                    $CertificateEnabled = 'true';
                } elseif ($CertificateEnabled[0]['value'] == '0') {
                    $CertificateEnabled = 'false';
                } else {
                    $CertificateEnabled = 'true';
                }
            } else {
                $CertificateEnabled = 'true';
            }
            $VttLanguage = $this->scorm_course_model->getAssignmetadatabyID($scourse_id, 64);
            $VttLanguage = (isset($VttLanguage[0]['value']) && ($VttLanguage[0]['value'] != '')) ? $VttLanguage[0]['value'] : $assessment_export_sets['64'];

            $VttLabel = $this->scorm_course_model->getAssignmetadatabyID($scourse_id, 65);
            $VttLabel = (isset($VttLabel[0]['value']) && ($VttLabel[0]['value'] != '')) ? $VttLabel[0]['value'] : $assessment_export_sets['65'];

            $spanCliContinue = $this->scorm_course_model->getAssignmetadatabyID($scourse_id, 72);
            $spanCliContinue = (isset($spanCliContinue[0]['value']) && ($spanCliContinue[0]['value'] != '')) ? $spanCliContinue[0]['value'] : $assessment_export_sets['72'];
            $getAllFileOwner = $this->scorm_course_model->getAllFileOwner($data['scourse_id']);
            $resource = (!empty($getAllFileOwner)) ? 'true' : 'false';

            $templatejson = '{"master": ' . $master . ',"lmsStatus":"' . $lmsStatus . '","QuizAttempt": "2","AudioVersionEnable": ' . $AudioVersionEnable . ',"CertificateEnabled": ' . $CertificateEnabled . ',"PageLevelCourseComplete": ' . $PageLevelCourseComplete . ',"LearningAidsTitle": "' . $LearningAidsTitle . '","Menutitle":"' . $Menutitle . '","ExitCourseTitle":"' . $ExitCourseTitle . '","NextTitle":" ' . $NextTitle . '","Prevtitle":"' . $Prevtitle . '","MenuName":"' . $MenuName . '","TranscriptName": "' . $TranscriptName . '","ResumeTitle":"' . $ResumeTitle . '","ResumeHeader":"' . $ResumeHeader . '","ResumeYES": "' . $ResumeYES . '","ResumeNO":"' . $ResumeNO . '","VttLanguage": "' . $VttLanguage . '","CourseName":"' . $tocjsonfile[0]['course_name'] . '","VttLabel":"' . $VttLabel . '","spanCliContinue":"' . $spanCliContinue . '","Resource": ' . $resource . ',';
            if ($resource == true) {
                $totalFiles = count($getAllFileOwner);
                $templatejson .= '"ResourceArea": {"LearningAids": {"Title": "Troubleshooting","Resources": [';
                foreach ($getAllFileOwner as $index => $pdf) {
                    $Title = $pdf['description'];
                    if ($index === $totalFiles - 1) {
                        $templatejson .= '{"Title": "' . $Title . '","URL": "assets/PDF/' . $pdf['folder'] . '"}';
                    } else {
                        $templatejson .= '{"Title": "' . $Title . '","URL": "assets/PDF/' . $pdf['folder'] . '"},';
                    }
                }
                $templatejson .= ']}}';
            }
            $templatejson .= '}';



            if (!is_dir(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp . '/assets/json')) {
                mkdir('assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp . '/assets/json', 0777, true);
            }
            $path = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp . '/assets/json';
            $fp = fopen($path . "/Template.json", 'w');
            fwrite($fp, $templatejson);
            fclose($fp);

            session()->setFlashdata('success', lang('Messages.Success_0044'));
            return redirect()->to(base_url() . '/SCORM/course_builder/Scorm_course_pages/page_pdf_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            return redirect()->to(base_url() . '/SCORM/course_builder/Scorm_course_pages/page_pdf_view');
        }
    }
    private function exportTextPage($eachpage, $scourse_id)
    {
        $timestamp = $eachpage['createdon'];
        $htmlFolder = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $scourse_id . '/' . $timestamp . '/assets/html/' . $eachpage['page_id'];
        if (!is_dir($htmlFolder)) {
            mkdir($htmlFolder, 0777, true);
        }

        $imageTag = '';
        if (!empty($eachpage['page_image'])) {
            $sourceImage = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $scourse_id . '/' . $timestamp . '/assets/page_images/' . $eachpage['page_image'];
            if (file_exists($sourceImage)) {
                copy($sourceImage, $htmlFolder . '/' . $eachpage['page_image']);
                $alt = htmlspecialchars($eachpage['image_alt'] ?? '', ENT_QUOTES);
                $imageTag = '<img src="' . $eachpage['page_image'] . '" alt="' . $alt . '" style="max-width:100%;height:auto;">';
            }
        }

        $content = $eachpage['content'] ?? '';
        $type = (int) $eachpage['type'];

        if ($type == 11 && $imageTag !== '') {
            $body = '<div class="text-page-row"><div class="text-page-image">' . $imageTag . '</div><div class="text-page-content">' . $content . '</div></div>';
        } elseif ($type == 12 && $imageTag !== '') {
            $body = '<div class="text-page-row"><div class="text-page-content">' . $content . '</div><div class="text-page-image">' . $imageTag . '</div></div>';
        } else {
            $body = '<div class="text-page-content">' . $content . '</div>';
        }

        $html = '<!DOCTYPE html><html><head><meta charset="UTF-8">'
            . '<style>body{font-family:Arial,sans-serif;padding:20px;}.text-page-row{display:flex;gap:20px;align-items:flex-start;}.text-page-row>div{flex:1;min-width:0;}.text-page-image img{max-width:100%;height:auto;}</style>'
            . '</head><body>' . $body . '</body></html>';

        file_put_contents($htmlFolder . '/Screen_01.html', $html);
    }
    function exportQuestion($page_id, $scourse_id, $type, $langauge)
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        if ($langauge == 'French') {
            $assessment_scqmcq_sets = Assessment_french::$assessment_scqmcq_sets;
        } elseif ($langauge == 'Spanish') {
            $assessment_scqmcq_sets = Assessment_spanish::$assessment_scqmcq_sets;
        } elseif ($langauge == 'Russian') {
            $assessment_scqmcq_sets = Assessment_russian::$assessment_scqmcq_sets;
        } elseif ($langauge == 'Portuguese') {
            $assessment_scqmcq_sets = Assessment_portuguese::$assessment_scqmcq_sets;
        } elseif ($langauge == 'Bahasa') {
            $assessment_scqmcq_sets = Assessment_bahasa::$assessment_scqmcq_sets;
        } elseif ($langauge == 'Arabic') {
            $assessment_scqmcq_sets = Assessment_arabic::$assessment_scqmcq_sets;
        } else {
            $assessment_scqmcq_sets = Assessment_english::$assessment_scqmcq_sets;
        }
        $questionjsonfile = $this->assessment_training_model->getQuestionAnswerdata($page_id);
        $coursetimestamp = $this->scorm_page_model->getAllpagedetails($scourse_id);
        $AttemptsofCourse = $this->assessment_training_model->getAttemptsofCourse($scourse_id);

        if ($type == '5') {
            $Questiontext = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '59');
            $Questiontext = isset($Questiontext[0]['value']) ? $Questiontext[0]['value'] : $assessment_scqmcq_sets['59'];
        }
        if ($type == '6') {
            $Questiontext = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '60');
            $Questiontext = isset($Questiontext[0]['value']) ? $Questiontext[0]['value'] : $assessment_scqmcq_sets['60'];
        }

        $quizButton = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '61');
        $quizButton = isset($quizButton[0]['value']) ? $quizButton[0]['value'] : $assessment_scqmcq_sets['61'];

        $AlertText = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '68');
        $AlertText = isset($AlertText[0]['value']) ? $AlertText[0]['value'] : $assessment_scqmcq_sets['68'];

        $boolean = '';
        if ($questionjsonfile) {
            // print_r($questionjsonfile);
            // exit();
            foreach ($questionjsonfile as &$eachquestion) {
                //  print_r($eachquestion);
                if ($eachquestion['truefalse'] == '1') {

                    $boolean = true;
                } elseif ($eachquestion['truefalse'] == '2') {
                    $boolean = false;
                } elseif ($eachquestion['truefalse'] == '0') {
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
            if (isset($eachquestion['noAttempts']) && $eachquestion['noAttempts'] != '') {
                $attempts = $eachquestion['noAttempts'];
            } else {
                $attempts = 2;
            }
            if ($type == '5') {
                $iframeSrc = "../../../theme/scripts/QuizTemplate/SCQ/SCQ.html";
            }
            if ($type == '6') {
                $iframeSrc = "../../../theme/scripts/QuizTemplate/MCQ/MCQ.html";
            }

            $postdata = [
                "quizButton" => $quizButton,
                "AlertText" => $AlertText,
                "Questiontext" => $Questiontext,
                "question" => [
                    "question" => $questionjsonfile[0]['question'],
                    "options" => $option,
                    "image" => "images/france.jpg",
                    "feedback" => [

                        "correct" => $questionjsonfile[0]['correct'],
                        "incorrect" => !empty($questionjsonfile[0]['incorrect2']) ? $questionjsonfile[0]['incorrect2'] : 'Sorry! That is not the correct answer. Click Try Again.',
                        "noAttempts" => $questionjsonfile[0]['incorrect']

                    ],
                    "attempts" => $attempts,
                    "iframeSrc" => $iframeSrc,
                ]
            ];

            // print_r($postdata);
            // exit();

            $pagejson = json_encode($postdata, JSON_UNESCAPED_SLASHES);
            $timestamp = $coursetimestamp[0]['createdon'];
            if (!is_dir(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $scourse_id . '/' . $timestamp . '/assets/Quiz/' . $page_id)) {
                mkdir('assets/assets/uploads/SCORM_course_document/' . $scourse_id . '/' . $timestamp . '/assets/Quiz/' . $page_id, 0777, true);
            }
            $path = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $scourse_id . '/' . $timestamp . '/assets/Quiz/' . $page_id;
            $fp = fopen($path . "/question.json", 'w');
            fwrite($fp, $pagejson);
            fclose($fp);
            if ($type == '5') {
                $sourceFilePath = FCPATH . 'assets/assets/uploads/SCORM_course_document/scorm_libraries/html/SCQ';
                $destinationFilePath = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $scourse_id . '/' . $timestamp . '/assets/Quiz/' . $page_id;
                $this->copyFolder($sourceFilePath, $destinationFilePath);
            }
            if ($type == '6') {
                $sourceFilePath = FCPATH . 'assets/assets/uploads/SCORM_course_document/scorm_libraries/html/MCQ';
                $destinationFilePath = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $scourse_id . '/' . $timestamp . '/assets/Quiz/' . $page_id;
                $this->copyFolder($sourceFilePath, $destinationFilePath);
            }
            session()->setFlashdata('success', lang('Messages.Success_0044'));
            return redirect()->to(base_url() . 'Assessment/trainings/question_list_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url() . 'Assessment/trainings/question_list_view');
        }
    }
    function exportQuizQuestion($page_id, $scourse_id, $type, $langauge)
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        if ($langauge == 'French') {
            $assessmentSets = Assessment_french::$assessment_sets;
        } elseif ($langauge == 'Spanish') {
            $assessmentSets = Assessment_spanish::$assessment_sets;
        } elseif ($langauge == 'Russian') {
            $assessmentSets = Assessment_russian::$assessment_sets;
        } elseif ($langauge == 'Portuguese') {
            $assessmentSets = Assessment_portuguese::$assessment_sets;
        } elseif ($langauge == 'Bahasa') {
            $assessmentSets = Assessment_bahasa::$assessment_sets;
        } elseif ($langauge == 'Arabic') {
            $assessmentSets = Assessment_arabic::$assessment_sets;
        } else {
            $assessmentSets = Assessment_english::$assessment_sets;
        }
        // print_r($assessmentSets);
        // exit();

        $coursetimestamp = $this->scorm_page_model->getAllpagedetails($scourse_id);
        $questionjsonfile = "";
        $questionjsonfile = $this->assessment_training_model->getpagequizquestion($scourse_id, $page_id);


        $QuestionRandom = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '1');
        if (!empty($QuestionRandom)) {
            if ($QuestionRandom[0]['value'] == 'Enabled') {
                $QuestionRandom = "1";
            } else {
                $QuestionRandom = "0";
            }
        } else {
            $QuestionRandom = "0";
        }
        $TotalQuestions = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '22');
        $TotalQuestions = isset($TotalQuestions[0]['value']) ? $TotalQuestions[0]['value'] : "10";

        $OptionRandom = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '2');

        if (!empty($QuestionRandom)) {
            if ($OptionRandom[0]['value'] == 'Enabled') {
                // print_r($OptionRandom);
                // exit();
                $OptionRandom = "1";
            } else {
                $OptionRandom = "0";
            }
        } else {
            $OptionRandom = "0";
        }
        $QuizMode = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '3');

        if (!empty($QuizMode)) {
            if ($QuizMode[0]['value'] == 'Enabled') {
                // print_r($QuizMode);
                // exit();
                $QuizMode = "PostTest";
            } else {
                $QuizMode = "PreTest";
            }
        } else {
            $QuizMode = "PostTest";
        }
        $PostAttemptType = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '4');

        if (!empty($PostAttemptType)) {
            if ($PostAttemptType[0]['value'] == 'Enabled') {
                // print_r($PostAttemptType);
                // exit();
                $PostAttemptType = true;
            } else {
                $PostAttemptType = false;
            }
        } else {
            $PostAttemptType = true;
        }

        $passingScore = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '23');
        $passingScore = isset($passingScore[0]['value']) ? $passingScore[0]['value'] : '70';


        $QuizAttempt = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '24');
        $QuizAttempt = isset($QuizAttempt[0]['value']) ? $QuizAttempt[0]['value'] : '1';

        $duration = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '21');
        $duration = isset($duration[0]['value']) ? $duration[0]['value'] : '0';
        // print_r($duration);
        // exit();
        $startpagedescrip = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '31');
        $startpagedescrip = isset($startpagedescrip[0]['value']) ? $startpagedescrip[0]['value'] : '';

        $resultpagedescrip = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '32');
        $resultpagedescrip = isset($resultpagedescrip[0]['value']) ? $resultpagedescrip[0]['value'] : '';

        $TotalQuestionste = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '33');
        $TotalQuestionste = (isset($TotalQuestionste[0]['value']) && ($TotalQuestionste[0]['value'] != '')) ? $TotalQuestionste[0]['value'] : $assessmentSets['33'];

        $passingScorete = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '34');
        $passingScorete = (isset($passingScorete[0]['value']) && $passingScorete[0]['value'] != '') ? $passingScorete[0]['value'] : $assessmentSets['34'];

        $QuizAttemptte = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '35');
        $QuizAttemptte = (isset($QuizAttemptte[0]['value']) && ($QuizAttemptte[0]['value'] != '')) ? $QuizAttemptte[0]['value'] : $assessmentSets['35'];

        $Resulttitle = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '36');
        $Resulttitle = (isset($Resulttitle[0]['value']) && ($Resulttitle[0]['value'] != '')) ? $Resulttitle[0]['value'] : $assessmentSets['36'];

        $Resutscorecontent = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '37');
        $Resutscorecontent = (isset($Resutscorecontent[0]['value']) && ($Resutscorecontent[0]['value'] != '')) ? $Resutscorecontent[0]['value'] : $assessmentSets['37'];

        $Resutpassed = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '38');
        $Resutpassed = (isset($Resutpassed[0]['value']) && ($Resutpassed[0]['value'] != '')) ? $Resutpassed[0]['value'] : $assessmentSets['38'];

        $Resutfailed = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '39');
        $Resutfailed = (isset($Resutfailed[0]['value']) && ($Resutfailed[0]['value'] != '')) ? $Resutfailed[0]['value'] : $assessmentSets['39'];

        $FinalResutfailed = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '67');
        $FinalResutfailed = (isset($FinalResutfailed[0]['value']) && ($FinalResutfailed[0]['value'] != '')) ? $FinalResutfailed[0]['value'] : $assessmentSets['67'];

        $Questiontext = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '40');
        $Questiontext = (isset($Questiontext[0]['value']) && ($Questiontext[0]['value'] != '')) ? $Questiontext[0]['value'] : $assessmentSets['40'];

        // $QuestionMcQtext = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '66');
        // $QuestionMcQtext  = (isset($QuestionMcQtext[0]['value']) && ($QuestionMcQtext[0]['value'] != '')) ? $QuestionMcQtext[0]['value'] : "<i>Select the best answers, then click Submit.</i>";

        $durationte = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '41');
        $durationte = (isset($durationte[0]['value']) && ($durationte[0]['value'] != '')) ? $durationte[0]['value'] : $assessmentSets['41'];

        $startpageheader = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '42');
        $startpageheader = (isset($startpageheader[0]['value']) && ($startpageheader[0]['value'] != '')) ? $startpageheader[0]['value'] : $assessmentSets['42'];

        $startpagedescrip1 = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '43');
        $startpagedescrip1 = (isset($startpagedescrip1[0]['value']) && ($startpagedescrip1[0]['value'] != '')) ? $startpagedescrip1[0]['value'] : $assessmentSets['43'];

        $startButton = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '44');
        $startButton = (isset($startButton[0]['value']) && ($startButton[0]['value'] != '')) ? $startButton[0]['value'] : $assessmentSets['44'];

        $quizButton = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '45');
        $quizButton = (isset($quizButton[0]['value']) && ($quizButton[0]['value'] != '')) ? $quizButton[0]['value'] : $assessmentSets['45'];

        $retryButton = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '46');
        $retryButton = (isset($retryButton[0]['value']) && ($retryButton[0]['value'] != '')) ? $retryButton[0]['value'] : $assessmentSets['46'];

        $viewResult = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '47');
        $viewResult = (isset($viewResult[0]['value']) && ($viewResult[0]['value'] != '')) ? $viewResult[0]['value'] : $assessmentSets['47'];

        $clicknote = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '48');
        $clicknote = (isset($clicknote[0]['value']) && ($clicknote[0]['value'] != '')) ? $clicknote[0]['value'] : $assessmentSets['48'];

        $TimeUpte = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '49');
        $TimeUpte = (isset($TimeUpte[0]['value']) && ($TimeUpte[0]['value'] != '')) ? $TimeUpte[0]['value'] : $assessmentSets['49'];

        $QuestionCountText = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '69');
        $QuestionCountText = (isset($QuestionCountText[0]['value']) && ($QuestionCountText[0]['value'] != '')) ? $QuestionCountText[0]['value'] : $assessmentSets['69'];

        $QuestionOFText = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '70');
        $QuestionOFText = (isset($QuestionOFText[0]['value']) && ($QuestionOFText[0]['value'] != '')) ? $QuestionOFText[0]['value'] : $assessmentSets['70'];

        $MinutesText = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '71');
        $MinutesText = (isset($MinutesText[0]['value']) && ($MinutesText[0]['value'] != '')) ? $MinutesText[0]['value'] : $assessmentSets['71'];

        $ImageZoomText = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '73');
        $ImageZoomText = (isset($ImageZoomText[0]['value']) && ($ImageZoomText[0]['value'] != '')) ? $ImageZoomText[0]['value'] : $assessmentSets['73'];

        $AlertText = $this->assessment_training_model->getassessment_settings($scourse_id, $page_id, '74');
        $AlertText = (isset($AlertText[0]['value']) && ($AlertText[0]['value'] != '')) ? $AlertText[0]['value'] : $assessmentSets['74'];




        $boolean = '';

        if ($questionjsonfile) {
            // echo "<pre>";
            // print_r($questionjsonfile);
            // exit();
            $postdata = [
                "QuestionRandom" => $QuestionRandom,
                "QuizMode" => $QuizMode,
                "PostAttemptType" => $PostAttemptType,
                "TotalQuestions" => $TotalQuestions,
                "OptionRandom" => $OptionRandom,
                "passingScore" => $passingScore,
                "AlertText" => $AlertText,
                "QuizAttempt" => $QuizAttempt,
                "iframeSrc" => "../../../theme/scripts/QuizTemplate/Quiz/Quiz.html",
                "duration" => $duration,
                "startpagedescrip" => $startpagedescrip,
                "resultpagedescrip" => $resultpagedescrip,

                "TotalQuestionste" => $TotalQuestionste,
                "passingScorete" => $passingScorete,
                "QuizAttemptte" => $QuizAttemptte,
                "Resulttitle" => $Resulttitle,
                "Resutscorecontent" => $Resutscorecontent,
                "Resutpassed" => $Resutpassed,
                "Resutfailed" => $Resutfailed,
                "FinalResutfailed" => $FinalResutfailed,
                "Questiontext" => $Questiontext,
                "QuestionMcQtext" => $Questiontext,
                "ImageZoomText" => $ImageZoomText,
                "durationte" => $durationte,
                "startpageheader" => $startpageheader,
                "startpagedescrip1" => $startpagedescrip1,
                "startButton" => $startButton,
                "quizButton" => $quizButton,
                "MinutesText" => $MinutesText,
                "retryButton" => $retryButton,
                "QuestionCountText" => $QuestionCountText,
                "QuestionOFText" => $QuestionOFText,
                "viewResult" => $viewResult,
                "clicknote" => $clicknote,
                "TimeUpte" => $TimeUpte,

                'questions' => $questionjsonfile
            ];
            // $j = 0;
            // // Iterate through each question in $data
            // foreach ($questionjsonfile as $item) {
            //     $j = $j + 1;
            //     // Determine question type
            //     $type = ($item['quiz_type'] == 115) ? 'multiple' : 'single';
            //     $image = ($item['quiz_type'] == 115) ? 'images/primes.jpg' : 'images/paris.jpg';

            //     // Reset arrays for options
            //     $questionoption = [];

            //     // Get question options
            //     $getquestionoptions = $this->assessment_training_model->getquestionoptions($item['q_id']);

            //     foreach ($getquestionoptions as $option) {
            //         if ($option['truefalse'] == '1') {
            //             $boolean = true;
            //         } elseif ($option['truefalse'] == '2') {
            //             $boolean = false;
            //         } else {
            //             $boolean = false;
            //         }
            //         // Add each option to $questionoption array
            //         $questionoption[] = [
            //             "text" =>  $option['values'],
            //             // "value" => $option['values'], // Assuming you want value to be the same as text
            //             // "correct" => $boolean
            //         ];
            //     }

            //     // Build question object
            //     $questionObject = [

            //         'type' => $type,
            //         'question' => $item['question'],
            //         'options' => $questionoption, // Use 'options' instead of 'option' to match JSON structure
            //         // "image" => "$image",

            //     ];

            //     // Add question object to questions array
            //     $postdata['questions'][] = $questionObject;
            // }


            $pagejson = json_encode($postdata, JSON_UNESCAPED_SLASHES);
            $timestamp = $coursetimestamp[0]['createdon'];
            if (!is_dir(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $scourse_id . '/' . $timestamp . '/assets/Quiz/' . $page_id)) {
                mkdir('assets/assets/uploads/SCORM_course_document/' . $scourse_id . '/' . $timestamp . '/assets/Quiz/' . $page_id, 0777, true);
            }
            $path = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $scourse_id . '/' . $timestamp . '/assets/Quiz/' . $page_id;
            $fp = fopen($path . "/questions.json", 'w');
            fwrite($fp, $pagejson);
            fclose($fp);
            // foreach ($questionjsonfile as $item) {
            //     $j = $j + 1;
            //     // Determine question type
            //     $type = ($item['quiz_type'] == 115) ? 'multiple' : 'single';

            //     $answeroption = [];
            //     $getansweroptions = $this->assessment_training_model->getquestionoptions($item['q_id']);
            //     foreach ($getansweroptions as $option) {
            //         if ($option['truefalse'] == '1') {
            //             $boolean = true;
            //         } elseif ($option['truefalse'] == '2') {
            //             $boolean = false;
            //         } else {
            //             $boolean = false;
            //         }
            //         $answeroption[] = [
            //             "correct" => $boolean
            //         ];
            //     }
            //     $answerObject = [

            //         'type' => $type,
            //         'options' => $answeroption,

            //     ];
            //     $answerpostdata['Answers'][] = $answerObject;
            // }

            // $pagejson = json_encode($answerpostdata, JSON_UNESCAPED_SLASHES);
            // $fp = fopen($path . "/answers.json", 'w');
            // fwrite($fp, $pagejson);
            // fclose($fp);
            $sourceFilePath = FCPATH . 'assets/assets/uploads/SCORM_course_document/scorm_libraries/html/Quiz';
            $destinationFilePath = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $scourse_id . '/' . $timestamp . '/assets/Quiz/' . $page_id;
            $this->copyFolder($sourceFilePath, $destinationFilePath);

            session()->setFlashdata('success', lang('Messages.Success_0044'));
            return redirect()->to(base_url() . 'Assessment/trainings/question_list_view');
        } else {
            session()->setFlashdata('error', 'Quiz Data Not Found');
            return redirect()->to(base_url() . 'Assessment/trainings/question_list_view');
        }
    }
    function update_pagenumber()
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $position = $_POST['position'];
        $pagenumberresult = $this->scorm_page_model->updatePagenumber($position);
        if ($pagenumberresult) {
            return json_encode(['success' => true]);
        }
    }
    function uploadZipfile()
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $data = [];
        helper(['filesystem']);
        if (isset($_POST['course_id'])) {
            $data['course_id'] = $_POST['course_id'];
            $_SESSION['course_id'] = $data['course_id'];
        } else if (isset($_GET['course_id'])) {
            $data['course_id'] = $_GET['course_id'];
        } else if (isset($_SESSION['course_id'])) {
            $data['course_id'] = $_SESSION['course_id'];
        } else {
            return redirect()->to(base_url() . '/SCORM/course_builder/Editor');
        }

        if (isset($_POST['page_id'])) {
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $data['page_id'];
        } else if (isset($_GET['page_id'])) {
            $data['page_id'] = $_GET['page_id'];
        } else if (isset($_SESSION['page_id'])) {
            $data['page_id'] = $_SESSION['page_id'];
        }

        sleep(2);
        if ($this->request->getPost()) {
            //    echo $this->request->getFile('zip_file');
            //    exit();
            $pagejsonfile = $this->scorm_page_model->getpagedata($data['page_id']);
            // print_r($pagejsonfile);
            $timestamp = $pagejsonfile[0]['createdon'];
            if ($file = $this->request->getFile('zip_file')) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $filename = $file->getName();
                    $extension = pathinfo($filename, PATHINFO_EXTENSION);
                    if ($extension == 'zip') {
                        if (!is_dir(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['course_id'] . '/' . $timestamp . '/assets/Articulate/' . $data['page_id'])) {
                            mkdir('assets/assets/uploads/SCORM_course_document/' . $data['course_id'] . '/' . $timestamp . '/assets/Articulate/' . $data['page_id'], 0777, true);
                        }
                        if (file_exists(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['course_id'] . '/' . $timestamp . '/assets/Articulate/' . $data['page_id'])) {
                            $dirPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['course_id'] . '/' . $timestamp . '/assets/Articulate/' . $data['page_id'];
                            $dir = $dirPath . DIRECTORY_SEPARATOR;
                            $this->emptyDir($dir);
                            rmdir($dir);
                            $targetzip = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['course_id'] . '/' . $timestamp . '/assets/Articulate/' . $data['page_id'] . '/' . $filename;
                            $filenoext = basename($filename, '.zip');  // absolute path to the directory where zipper.php is in (lowercase)
                            $filenoext = basename($filenoext, '.ZIP');  // absolute path to the directory where zipper.php is in (when uppercase)
                            //$targetdir = $path . $filenoext; // target directory

                            if ($file->move(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['course_id'] . '/' . $timestamp . '/assets/Articulate/' . $data['page_id'], $filename)) {
                                $zip = new ZipArchive();
                                $x = $zip->open($targetzip);
                                if ($x === true) {
                                    $zip->extractTo(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['course_id'] . '/' . $timestamp . '/assets/Articulate/' . $data['page_id']); // place in the directory with same name  
                                    $zip->close();
                                    unlink($targetzip);
                                }
                            }
                            $newdata = [
                                'video_upload' => $filename,
                            ];
                            $result = $this->scorm_page_model->edituploadpagedetails($newdata, $data['page_id']);
                            $fileupload = [
                                'language' => $_POST['language'],
                                'page_id' => $data['page_id'],
                                'folder' => $data['page_id'],
                                'status' => 1,
                                'createdby' => session()->get('id_user'),
                                'createdon' => time(),
                            ];
                            $this->scorm_page_model->insertFileuploaddata($fileupload);
                            return json_encode($result);
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                    }
                }
            }
        }
        return redirect()->to(base_url('SCORM/course_builder/Editor'));
    }
    public function del_folder()
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        if (isset($_POST['folderloc'])) {
            // Get data from POST
            $dirPath = $_POST['folderloc'];
            // print_r($dirPath);
            //  exit();  
            $page_id = $_POST['page_id'];
            $folder_name = $_POST['folder_name'];
            $dir = $dirPath . DIRECTORY_SEPARATOR;
            try {
                if (is_dir($dir)) {
                    $this->emptyDir($dir);  // Custom function to empty directory (optional)
                    if (!rmdir($dir)) {
                        session()->setFlashdata('error', 'Failed to remove directory: ' . $dir);
                    }
                }
                $newdata = [


                    'status' => '0',
                ];
                $deleteResult = $this->scorm_page_model->delsfiles($newdata, $page_id, $folder_name);

                if (!$deleteResult) {
                    throw new Exception('Failed to delete folder data in database.');
                }
                session()->setFlashdata('success', lang('Messages.Success_0005'));
            } catch (Exception $e) {
                log_message('error', 'Folder deletion failed: ' . $e->getMessage());

                session()->setFlashdata('error', 'Failed to delete folder: ' . $e->getMessage());
            }
        }

        return redirect()->to(base_url('SCORM/course_builder/Editor'));
    }
    public function emptyDir($dir)
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        if (is_dir($dir)) {
            $scn = scandir($dir);
            foreach ($scn as $files) {
                if ($files !== '.') {
                    if ($files !== '..') {
                        if (!is_dir($dir . '/' . $files)) {
                            unlink($dir . '/' . $files);
                        } else {
                            $this->emptyDir($dir . '/' . $files);
                            rmdir($dir . '/' . $files);
                        }
                    }
                }
            }
        }
    }
    public function uploadvideo()
    {
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        $data = [];
        helper(['filesystem']);

        if (isset($_POST['course_id'])) {
            $data['course_id'] = $_POST['course_id'];
            $_SESSION['course_id'] = $data['course_id'];
        } else if (isset($_SESSION['course_id'])) {
            $data['course_id'] = $_SESSION['course_id'];
        }
        if (isset($_POST['page_id'])) {
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $data['page_id'];
        } else if (isset($_SESSION['page_id'])) {
            $data['page_id'] = $_SESSION['page_id'];
        }

        if ($this->request->getPost()) {
            // print_r($_POST);
            // exit();
            $rules = [
                'file' => 'uploaded[file]|ext_in[file,mp4]'
            ];
            if (!$this->validate($rules)) {
                $data['promovalidation'] = $this->validator;
            } else {
                $pagejsonfile = $this->scorm_page_model->getpagedata($data['page_id']);
                $timestamp = $pagejsonfile[0]['createdon'];
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();
                        $englishFolderPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['course_id'] . '/' . $timestamp . '/assets/video/';

                        // Create the English folder if it doesn't exist
                        if (!is_dir($englishFolderPath)) {
                            mkdir($englishFolderPath, 0777, true);
                        }

                        // Define the full path of the new video file
                        $newVideoFilePath = $englishFolderPath . $filename;

                        // Check if the file already exists
                        if (file_exists($newVideoFilePath)) {
                            // If the file already exists, delete it
                            unlink($newVideoFilePath);
                        }

                        // Move the new file to the English folder
                        if ($file->move($englishFolderPath, $filename)) {
                            // Update database record or any other necessary operations
                            $newdata = [
                                'video_upload' => $filename,
                            ];
                            $result = $this->scorm_page_model->edituploadpagedetails($newdata, $data['page_id']);
                            $fileupload = [
                                'language' => $_POST['language'],
                                'page_id' => $data['page_id'],
                                'type' => 1,
                                'filename' => $filename,
                                'status' => 1,
                                'createdby' => session()->get('id_user'),
                                'createdon' => time(),
                            ];
                            $this->scorm_page_model->insertvideoFileuploaddata($fileupload);

                            if ($result) {
                                session()->setFlashdata('success', $filename . lang('Messages.Success_0009'));
                                session()->setFlashdata('alert-class', 'alert-danger');
                            } else {
                                session()->setFlashdata('error', lang('Messages.Error_0001'));
                                session()->setFlashdata('alert-class', 'alert-danger');
                            }
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                    }
                }
            }
        }
        return redirect()->to(base_url('SCORM/course_builder/Editor'));
    }
    public function saveTextPage()
    {
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        $data = [];
        helper(['filesystem']);

        if (isset($_POST['course_id'])) {
            $data['course_id'] = $_POST['course_id'];
            $_SESSION['course_id'] = $data['course_id'];
        } else if (isset($_SESSION['course_id'])) {
            $data['course_id'] = $_SESSION['course_id'];
        }
        if (isset($_POST['page_id'])) {
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $data['page_id'];
        } else if (isset($_SESSION['page_id'])) {
            $data['page_id'] = $_SESSION['page_id'];
        }

        if ($this->request->getPost()) {
            $newdata = [
                'content' => $this->request->getPost('content'),
            ];
            if ($this->request->getPost('image_alt') !== null) {
                $newdata['image_alt'] = $this->request->getPost('image_alt');
            }

            $file = $this->request->getFile('image');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $rules = [
                    // JPG/JPEG/PNG only, max 1 MB
                    'image' => 'is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]|max_size[image,1024]',
                ];
                if (!$this->validate($rules)) {
                    $data['promovalidation'] = $this->validator;
                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url('SCORM/course_builder/Editor'));
                }

                $pagejsonfile = $this->scorm_page_model->getpagedata($data['page_id']);
                $timestamp = $pagejsonfile[0]['createdon'];
                $extension = $file->getExtension();
                // Unique per upload (not just per page) so replacing an image never collides with a cached copy of the old one.
                $filename = 'page_' . $data['page_id'] . '_' . time() . '.' . $extension;
                $imageFolderPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['course_id'] . '/' . $timestamp . '/assets/page_images/';

                if (!is_dir($imageFolderPath)) {
                    mkdir($imageFolderPath, 0777, true);
                }

                if ($file->move($imageFolderPath, $filename)) {
                    // Remove the previous image file so uploads don't accumulate on disk.
                    $previousFilename = $pagejsonfile[0]['page_image'] ?? '';
                    if ($previousFilename !== '' && file_exists($imageFolderPath . $previousFilename)) {
                        unlink($imageFolderPath . $previousFilename);
                    }
                    $newdata['page_image'] = $filename;
                }
            }

            $result = $this->scorm_page_model->edituploadpagedetails($newdata, $data['page_id']);

            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0009'));
                session()->setFlashdata('alert-class', 'alert-danger');
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }
        }
        return redirect()->to(base_url('SCORM/course_builder/Editor'));
    }
    public function deleteTextImage()
    {
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }

        $page_id = $_POST['page_id'] ?? null;
        if ($page_id) {
            $pagejsonfile = $this->scorm_page_model->getpagedata($page_id);
            if (!empty($pagejsonfile) && !empty($pagejsonfile[0]['page_image'])) {
                $imageFolderPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $pagejsonfile[0]['fk_course_id'] . '/' . $pagejsonfile[0]['createdon'] . '/assets/page_images/';
                $filePath = $imageFolderPath . $pagejsonfile[0]['page_image'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                $this->scorm_page_model->edituploadpagedetails(['page_image' => null, 'image_alt' => null], $page_id);
                session()->setFlashdata('success', lang('Messages.Success_0005'));
            }
        }

        return redirect()->to(base_url('SCORM/course_builder/Editor'));
    }
    function uploadHTML()
    {
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }

        $data = [];
        helper(['filesystem']);
        if (isset($_POST['course_id'])) {
            $data['course_id'] = $_POST['course_id'];
            $_SESSION['course_id'] = $data['course_id'];
        } else if (isset($_GET['course_id'])) {
            $data['course_id'] = $_GET['course_id'];
        } else if (isset($_SESSION['course_id'])) {
            $data['course_id'] = $_SESSION['course_id'];
        } else {
            return redirect()->to(base_url() . '/SCORM/course_builder/Editor');
        }

        if (isset($_POST['page_id'])) {
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $data['page_id'];
        } else if (isset($_GET['page_id'])) {
            $data['page_id'] = $_GET['page_id'];
        } else if (isset($_SESSION['page_id'])) {
            $data['page_id'] = $_SESSION['page_id'];
        }

        sleep(2);
        if ($this->request->getPost()) {
            //    echo $this->request->getFile('zip_file');
            //    exit();
            $pagejsonfile = $this->scorm_page_model->getpagedata($data['page_id']);
            // print_r($pagejsonfile);
            $timestamp = $pagejsonfile[0]['createdon'];
            $page_number = $pagejsonfile[0]['page_number'];
            if ($file = $this->request->getFile('zip_file')) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $filename = $file->getName();
                    $extension = pathinfo($filename, PATHINFO_EXTENSION);
                    if ($page_number == 1) {
                        if ($extension == 'zip') {
                            if (!is_dir(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['course_id'] . '/' . $timestamp . '/assets/html/' . $data['page_id'])) {
                                mkdir('assets/assets/uploads/SCORM_course_document/' . $data['course_id'] . '/' . $timestamp . '/assets/html/' . $data['page_id'], 0777, true);
                            }
                            if (file_exists(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['course_id'] . '/' . $timestamp . '/assets/html/' . $data['page_id'])) {
                                $dirPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['course_id'] . '/' . $timestamp . '/assets/html/' . $data['page_id'];
                                $dir = $dirPath . DIRECTORY_SEPARATOR;
                                $this->emptyDir($dir);
                                rmdir($dir);
                                $targetzip = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['course_id'] . '/' . $timestamp . '/assets/html/' . $data['page_id'] . '/' . $filename;
                                $filenoext = basename($filename, '.zip');  // absolute path to the directory where zipper.php is in (lowercase)
                                $filenoext = basename($filenoext, '.ZIP');  // absolute path to the directory where zipper.php is in (when uppercase)
                                //$targetdir = $path . $filenoext; // target directory

                                if ($file->move(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['course_id'] . '/' . $timestamp . '/assets/html/' . $data['page_id'], $filename)) {
                                    $zip = new ZipArchive();
                                    $x = $zip->open($targetzip);
                                    if ($x === true) {
                                        $zip->extractTo(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['course_id'] . '/' . $timestamp . '/assets/html/' . $data['page_id']); // place in the directory with same name  
                                        $zip->close();
                                        unlink($targetzip);
                                    }
                                }
                                $newdata = [
                                    'video_upload' => $filename,
                                ];
                                $result = $this->scorm_page_model->edituploadpagedetails($newdata, $data['page_id']);
                                $fileupload = [
                                    'language' => $_POST['language'],
                                    'page_id' => $data['page_id'],
                                    'folder' => 'page1',
                                    'status' => 1,
                                    'createdby' => session()->get('id_user'),
                                    'createdon' => time(),
                                ];
                                $this->scorm_page_model->insertFileuploaddata($fileupload);
                                return json_encode($result);
                            }
                        } else {
                            session()->setFlashdata('error', lang('Messages.Error_0001'));
                            session()->setFlashdata('alert-class', 'alert-danger');
                        }
                    } else {
                        if ($extension == 'zip') {
                            if (!is_dir(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['course_id'] . '/' . $timestamp . '/assets/html/' . $data['page_id'])) {
                                mkdir('assets/assets/uploads/SCORM_course_document/' . $data['course_id'] . '/' . $timestamp . '/assets/html/' . $data['page_id'], 0777, true);
                            }
                            if (file_exists(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['course_id'] . '/' . $timestamp . '/assets/html/' . $data['page_id'])) {
                                $dirPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['course_id'] . '/' . $timestamp . '/assets/html/' . $data['page_id'];
                                $dir = $dirPath . DIRECTORY_SEPARATOR;
                                $this->emptyDir($dir);
                                rmdir($dir);
                                $targetzip = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['course_id'] . '/' . $timestamp . '/assets/html/' . $data['page_id'] . '/' . $filename;
                                $filenoext = basename($filename, '.zip');  // absolute path to the directory where zipper.php is in (lowercase)
                                $filenoext = basename($filenoext, '.ZIP');  // absolute path to the directory where zipper.php is in (when uppercase)
                                //$targetdir = $path . $filenoext; // target directory

                                if ($file->move(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['course_id'] . '/' . $timestamp . '/assets/html/' . $data['page_id'], $filename)) {
                                    $zip = new ZipArchive();
                                    $x = $zip->open($targetzip);
                                    if ($x === true) {
                                        $zip->extractTo(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['course_id'] . '/' . $timestamp . '/assets/html/' . $data['page_id']); // place in the directory with same name  
                                        $zip->close();
                                        unlink($targetzip);
                                    }
                                }
                                $newdata = [
                                    'video_upload' => $filename,
                                ];
                                $result = $this->scorm_page_model->edituploadpagedetails($newdata, $data['page_id']);
                                $fileupload = [
                                    'language' => $_POST['language'],
                                    'page_id' => $data['page_id'],
                                    'folder' => $data['page_id'],
                                    'status' => 1,
                                    'createdby' => session()->get('id_user'),
                                    'createdon' => time(),
                                ];
                                $this->scorm_page_model->insertFileuploaddata($fileupload);
                                return json_encode($result);
                            }
                        } else {
                            session()->setFlashdata('error', lang('Messages.Error_0001'));
                            session()->setFlashdata('alert-class', 'alert-danger');
                        }
                    }
                }
            }
        }
        return redirect()->to(base_url('SCORM/course_builder/Editor'));
    }
    function uploadvtt()
    {
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        $data = [];
        helper(['filesystem']);
        if (isset($_POST['course_id'])) {
            $data['course_id'] = $_POST['course_id'];
            $_SESSION['course_id'] = $data['course_id'];
        } else if (isset($_GET['course_id'])) {
            $data['course_id'] = $_GET['course_id'];
        } else if (isset($_SESSION['course_id'])) {
            $data['course_id'] = $_SESSION['course_id'];
        }
        if (isset($_POST['page_id'])) {
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $data['page_id'];
        } else if (isset($_GET['page_id'])) {
            $data['page_id'] = $_GET['page_id'];
        } else if (isset($_SESSION['page_id'])) {
            $data['page_id'] = $_SESSION['page_id'];
        }

        if ($this->request->getPost()) {
            $rules = [
                'file' => 'uploaded[file]|ext_in[file,vtt,html,mp4]'
            ];
            if (!$this->validate($rules)) {
                $data['promovalidation'] = $this->validator;
            } else {
                $pagejsonfile = $this->scorm_page_model->getpagedata($data['page_id']);
                $timestamp = $pagejsonfile[0]['createdon'];
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();

                        $englishFolderPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['course_id'] . '/' . $timestamp . '/assets/vtt/';

                        // Create the English folder if it doesn't exist
                        if (!is_dir($englishFolderPath)) {
                            mkdir($englishFolderPath, 0777, true);
                        }

                        // Define the full path of the new video file
                        $newVideoFilePath = $englishFolderPath . $filename;

                        // Check if the file already exists
                        if (file_exists($newVideoFilePath)) {
                            // If the file already exists, delete it
                            unlink($newVideoFilePath);
                        }

                        // Move the new file to the English folder
                        if ($file->move($englishFolderPath, $filename)) {
                            // Update database record or any other necessary operations
                            $newdata = [
                                'vtt_upload' => $filename,
                            ];
                            $result = $this->scorm_page_model->edituploadpagedetails($newdata, $data['page_id']);
                            $fileupload = [
                                'language' => $_POST['language'],
                                'page_id' => $data['page_id'],
                                'type' => 2,
                                'filename' => $filename,
                                'status' => 1,
                                'createdby' => session()->get('id_user'),
                                'createdon' => time(),
                            ];
                            $this->scorm_page_model->insertvideoFileuploaddata($fileupload);

                            if ($result) {
                                session()->setFlashdata('success', $filename . lang('Messages.Success_0009'));
                                session()->setFlashdata('alert-class', 'alert-danger');
                            } else {
                                session()->setFlashdata('error', lang('Messages.Error_0001'));
                                session()->setFlashdata('alert-class', 'alert-danger');
                            }
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                    }
                }
            }
        }
        return redirect()->to(base_url('SCORM/course_builder/Editor'));
    }
    public function del_file()
    {
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        if (isset($_POST['fileloc'])) {
            // Get the file location and other POST data
            $fileloc = $_POST['fileloc'];
            $page_id = $_POST['page_id'];
            $file_name = $_POST['file_name'];

            try {
                if (file_exists($fileloc)) {
                    if (!unlink($fileloc)) {
                        session()->setFlashdata('error', 'Failed to delete file: ' . $fileloc);
                    }
                } else {
                    // session()->setFlashdata('error', 'File does not exist: ' . $fileloc);
                }
                $newdata = [


                    'status' => '0',
                ];
                $deleteResult = $this->scorm_page_model->delsvideofiles($newdata, $page_id, $file_name);

                if (!$deleteResult) {
                    session()->setFlashdata('error', 'Failed to delete file record in database.');
                }
                session()->setFlashdata('success', lang('Messages.Success_0005'));
            } catch (Exception $e) {
                // In case of an error, log the error and provide a failure message
                log_message('error', 'File deletion failed: ' . $e->getMessage());
                session()->setFlashdata('error', 'Failed to delete file: ' . $e->getMessage());
            }
        }

        // return redirect()->to(base_url('SCORM/course_builder/Editor'));
        return redirect()->to(base_url('SCORM/course_builder/Editor'));
    }
    function page_pdf_view()
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $data = [];
        $course_lang = $_SESSION['course_lang'];
        // print_r($data['assessment_export_sets']);
        if ($course_lang == 'French') {
            $data['assessment_export_sets'] = Assessment_french::$assessment_export_sets;
        } elseif ($course_lang == 'Spanish') {
            $data['assessment_export_sets'] = Assessment_spanish::$assessment_export_sets;
        } elseif ($course_lang == 'Russian') {
            $data['assessment_export_sets'] = Assessment_russian::$assessment_export_sets;
        } elseif ($course_lang == 'Portuguese') {
            $data['assessment_export_sets'] = Assessment_portuguese::$assessment_export_sets;
        } elseif ($course_lang == 'Bahasa') {
            $data['assessment_export_sets'] = Assessment_bahasa::$assessment_export_sets;
        } elseif ($course_lang == 'Arabic') {
            $data['assessment_export_sets'] = Assessment_arabic::$assessment_export_sets;
        } else {
            $data['assessment_export_sets'] = Assessment_english::$assessment_export_sets;
        }
        helper(['form']);
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        } else {
            return redirect()->to(base_url() . '/SCORM/course_builder/Editor');
        }
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else if (isset($_SESSION['tab'])) {
            $data['tab'] = $_SESSION['tab'];
        } else {
            $data['tab'] = 1;
        }
        $data['header'] = 'Pages';
        $data['header_link'] = 'SCORM/course_builder/Editor';
        $data['sub_header_1'] = ' Settings';
        $data['create_json_file'] = 'SCORM/course_builder/Scorm_course_pages/create_json_file';
        $data['getAllpdfFileOwner'] = $this->scorm_page_model->getAllpdfFileOwner($data['scourse_id']);
        $getCourseData = $this->scorm_course_model->getCourseDetails($data['scourse_id']);

        $data['row'] = $getCourseData[0];
        $data['getAssessmentSettings'] = $this->assessment_training_model->get_assessmentCourselevel_settings($data['scourse_id']);
        $data['AssessmentSettings']['50'] = $this->scorm_course_model->getAssignmetadatabyID($data['scourse_id'], 50);
        $data['AssessmentSettings']['51'] = $this->scorm_course_model->getAssignmetadatabyID($data['scourse_id'], 51);
        $data['AssessmentSettings']['52'] = $this->scorm_course_model->getAssignmetadatabyID($data['scourse_id'], 52);
        $data['AssessmentSettings']['53'] = $this->scorm_course_model->getAssignmetadatabyID($data['scourse_id'], 53);
        $data['AssessmentSettings']['54'] = $this->scorm_course_model->getAssignmetadatabyID($data['scourse_id'], 54);
        $data['AssessmentSettings']['55'] = $this->scorm_course_model->getAssignmetadatabyID($data['scourse_id'], 55);
        $data['AssessmentSettings']['56'] = $this->scorm_course_model->getAssignmetadatabyID($data['scourse_id'], 56);
        $data['AssessmentSettings']['57'] = $this->scorm_course_model->getAssignmetadatabyID($data['scourse_id'], 57);
        $data['AssessmentSettings']['58'] = $this->scorm_course_model->getAssignmetadatabyID($data['scourse_id'], 58);
        $data['AssessmentSettings']['62'] = $this->scorm_course_model->getAssignmetadatabyID($data['scourse_id'], 62);
        $data['AssessmentSettings']['63'] = $this->scorm_course_model->getAssignmetadatabyID($data['scourse_id'], 63);
        $data['AssessmentSettings']['64'] = $this->scorm_course_model->getAssignmetadatabyID($data['scourse_id'], 64);
        $data['AssessmentSettings']['65'] = $this->scorm_course_model->getAssignmetadatabyID($data['scourse_id'], 65);
        $data['AssessmentSettings']['72'] = $this->scorm_course_model->getAssignmetadatabyID($data['scourse_id'], 72);
        $data['AssessmentSettings']['74'] = $this->scorm_course_model->getAssignmetadatabyID($data['scourse_id'], 74);
        $data['AssessmentSettings']['75'] = $this->scorm_course_model->getAssignmetadatabyID($data['scourse_id'], 75);
        $data['AssessmentSettings']['76'] = $this->scorm_course_model->getAssignmetadatabyID($data['scourse_id'], 76);

        echo view('templates/header_view', $data);
        echo view('page/page_pdf_view', $data);
        echo view('templates/footer_view');
    }
    function uploadpdf()
    {
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        $data = [];
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
                'file' => 'uploaded[file]|ext_in[file,pdf,PDF]'
            ];
            if (!$this->validate($rules)) {
                $data['coursevalidation'] = $this->validator;
            } else {
                $getAllpdfFileOwner = $this->scorm_page_model->getAllpdfFileOwner($data['scourse_id']);
                $timestamp = $getAllpdfFileOwner[0]['createdon'];
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();
                        if (!is_dir(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp . '/shared/assets/content/PDF')) {
                            mkdir('assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp . '/shared/assets/content/PDF', 0777, true);
                        }
                        if (file_exists(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp . '/shared/assets/content/PDF/' . $filename)) {
                            $pdf = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp . '/shared/assets/content/PDF/' . $filename;
                            unlink($pdf);
                        } else {

                            if ($file->move(FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp . '/shared/assets/content/PDF', $filename)) {
                                $newdata = [
                                    'pdf_filename' => $filename,

                                ];
                                $result = $this->scorm_page_model->edituploadpdfdetails($newdata, $data['scourse_id']);

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
        }
        return redirect()->to(base_url('SCORM/course_builder/Scorm_course_pages/page_pdf_view'));
    }
    public function manifestfile($scourse_id, $Identifier, $theme)
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        } elseif ($scourse_id) {
            $data['scourse_id'] = $scourse_id;
        } else {
            return redirect()->to(base_url() . '/SCORM/course_builder/Editor');
        }
        $getAllpdfFileOwner = $this->scorm_page_model->getAllpdfFileOwner($data['scourse_id']);
        $timestamp = $getAllpdfFileOwner[0]['createdon'];
        $encoded_course_name = rawurlencode($getAllpdfFileOwner[0]['course_name']);
        $course_name = htmlspecialchars($getAllpdfFileOwner[0]['course_name'], ENT_QUOTES, 'UTF-8');
        $xmlDoc = new \DOMDocument('1.0', 'UTF-8');

        $manifest = $xmlDoc->createElementNS('http://www.imsproject.org/xsd/imscp_rootv1p1p2', 'manifest');
        $manifest->setAttribute('xmlns:imsmd', 'http://www.imsglobal.org/xsd/imsmd_rootv1p2p1');
        $manifest->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $manifest->setAttribute('xmlns:adlcp', 'http://www.adlnet.org/xsd/adlcp_rootv1p2');
        $manifest->setAttribute('identifier', 'pipwerksWrapperSCORM12');
        $manifest->setAttribute('xsi:schemaLocation', 'http://www.imsproject.org/xsd/imscp_rootv1p1p2 imscp_rootv1p1p2.xsd http://www.imsglobal.org/xsd/imsmd_rootv1p2p1 imsmd_rootv1p2p1.xsd http://www.adlnet.org/xsd/adlcp_rootv1p2 adlcp_rootv1p2.xsd');
        $xmlDoc->appendChild($manifest);

        $metadata = $xmlDoc->createElement('metadata');
        $schema = $xmlDoc->createElement('schema', 'ADL SCORM');
        $schemaversion = $xmlDoc->createElement('schemaversion', '1.2');
        $metadata->appendChild($schema);
        $metadata->appendChild($schemaversion);
        $manifest->appendChild($metadata);

        $organizations = $xmlDoc->createElement('organizations');
        $organizations->setAttribute('default', 'pipwerks');
        $manifest->appendChild($organizations);




        $organization = $xmlDoc->createElement('organization');
        $organization->setAttribute('identifier', 'pipwerks');
        $organization->setAttribute('structure', 'hierarchical');
        $organizations->appendChild($organization);

        $orgTitle = $xmlDoc->createElement('title', $course_name);
        $organization->appendChild($orgTitle);

        $item = $xmlDoc->createElement('item');
        $item->setAttribute('identifier', 'SCORM12_wrapper_test');
        $item->setAttribute('isvisible', 'true');
        $item->setAttribute('identifierref', $Identifier);
        $organization->appendChild($item);

        $itemTitle = $xmlDoc->createElement('title', $course_name);
        $item->appendChild($itemTitle);

        $resources = $xmlDoc->createElement('resources');
        $manifest->appendChild($resources);

        $resource = $xmlDoc->createElement('resource');
        $resource->setAttribute('identifier', $Identifier);
        $resource->setAttribute('type', 'webcontent');
        $resource->setAttribute('adlcp:scormtype', 'sco');
        $resource->setAttribute('href', 'index.html');
        $resources->appendChild($resource);

        $xmlString = $xmlDoc->saveXML();
        if ($theme == 7) {
            $folderPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/scorm_libraries/Vertical_index_files/';
        } elseif ($theme == 8) {
            $folderPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/scorm_libraries/Modern_index_files/';
        } else {
            $folderPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/scorm_libraries/index_files/';
        }
        $fileName = 'imsmanifest.xml';
        $filePath = $folderPath . $fileName;

        // This is only caching a copy of the manifest on disk - the response below
        // already carries the generated XML regardless, so a permission problem
        // writing the cache shouldn't fail the whole request.
        if (is_dir($folderPath) || @mkdir($folderPath, 0777, true) || is_dir($folderPath)) {
            if (@file_put_contents($filePath, $xmlString) === false) {
                log_message('error', 'manifestfile: unable to write cache copy to {file}', ['file' => $filePath]);
            }
        } else {
            log_message('error', 'manifestfile: unable to create cache folder {folder}', ['folder' => $folderPath]);
        }

        $this->response->setContentType('application/xml');
        $this->response->setBody($xmlString);
        return $this->response;
    }
    public function exportCoursePackage()
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $data = [];
        // helper('filesystem');
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        }
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else if (isset($_SESSION['tab'])) {
            $data['tab'] = $_SESSION['tab'];
        } else {
            $data['tab'] = 1;
        }
        // print_r($data['scourse_id']);
        // exit();
        // $theme = $_POST['theme'];
        $lmsStatus = isset($_POST['lmsStatus']) ? $_POST['lmsStatus'] : 'Completed/Failed';
        $jsongeneration = $this->create_json_file_new($data['scourse_id'], $lmsStatus);
        if ($jsongeneration) {
            $getAllpdfFileOwner = $this->scorm_page_model->getAllpdfFileOwner($data['scourse_id']);
            $timestamp = $getAllpdfFileOwner[0]['createdon'];
            $theme = $getAllpdfFileOwner[0]['theme'];
            $Identifier = isset($_POST['Identifier']) ? $_POST['Identifier'] : 'DCK' . $timestamp;


            $course_name = htmlspecialchars($getAllpdfFileOwner[0]['course_name'], ENT_QUOTES, 'UTF-8');
            $decoded_course_name = html_entity_decode($course_name, ENT_QUOTES, 'UTF-8');
            $decoded_course_name = preg_replace('/[^A-Za-z0-9 _.\-]/u', ' ', $decoded_course_name);

            $decoded_course_name = trim($decoded_course_name, " _-");

            $decoded_course_name = preg_replace('/[\s\-]+/', ' ', $decoded_course_name);

            $zipfilenameformat = $decoded_course_name;
            // print_r($zipfilenameformat);
            // exit();
            if ($theme == 1) {
                $themesourceFolderPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/Default';
                $indexsourceFolderPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/scorm_libraries/index_files';
            } elseif ($theme == 2) {
                $themesourceFolderPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/ContentforU';
                $indexsourceFolderPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/scorm_libraries/index_files';
            } elseif ($theme == 3) {
                $themesourceFolderPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/Wabtec';
                $indexsourceFolderPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/scorm_libraries/index_files';
            } elseif ($theme == 4) {
                $themesourceFolderPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/Knowledge_Works';
                $indexsourceFolderPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/scorm_libraries/index_files';
            } elseif ($theme == 5) {
                $themesourceFolderPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/WabtecArabic';
                $indexsourceFolderPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/scorm_libraries/index_files';
            } elseif ($theme == 6) {
                $themesourceFolderPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/WabtecTheme';
                $indexsourceFolderPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/scorm_libraries/index_files';
            } elseif ($theme == 7) {
                $themesourceFolderPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/Vertical_ContentforU';
                $indexsourceFolderPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/scorm_libraries/Vertical_index_files';
            } elseif ($theme == 8) {
                $themesourceFolderPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/ModernTheme';
                $indexsourceFolderPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/scorm_libraries/Modern_index_files';
            } else {
                $themesourceFolderPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/Default';
                $indexsourceFolderPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/scorm_libraries/index_files';
            }
            $this->manifestfile($data['scourse_id'], $Identifier, $theme);

            $destinationfolderPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $zipfilenameformat;
            // print_r($destinationfolderPath);
            // exit();
            if (!is_dir($destinationfolderPath)) {
                mkdir($destinationfolderPath, 0777, true);
            }
            if (is_dir($destinationfolderPath)) {
                chmod($destinationfolderPath, 0755); // Set directory permissions to 755
            }
            $themedestinationfolderPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $zipfilenameformat . '/theme';
            if (!is_dir($destinationfolderPath)) {
                mkdir($destinationfolderPath, 0777, true);
            }
            $themepath = $this->copyFolder($themesourceFolderPath, $themedestinationfolderPath);
            $indexpath = $this->copyFolder($indexsourceFolderPath, $destinationfolderPath);
            if ($themepath == '1' && $indexpath == '1') {
                $folderPath = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $timestamp . '/assets/';
                if (is_dir($destinationfolderPath)) {
                    $destination = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $zipfilenameformat . '/assets/';
                    $this->copyFolder($folderPath, $destination);
                    // Check if the folder exists
                    $zipfolder = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/' . $zipfilenameformat . '/';
                    // Check if the folder exists
                    if (!is_dir($zipfolder)) {
                        return "Folder does not exist.";
                    }

                    $zipFileName = $zipfilenameformat . '.zip';

                    $zippath = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $data['scourse_id'] . '/';
                    $zipFilePath = $zippath . $zipFileName;
                    $zip = new ZipArchive();

                    if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                        $files = [];

                        $getFilesRecursively = function ($zipfolder) use (&$files, &$getFilesRecursively) {
                            $dir = new RecursiveDirectoryIterator($zipfolder);
                            foreach (new RecursiveIteratorIterator($dir) as $file) {
                                if ($file->isFile()) {
                                    $files[] = $file->getPathname();
                                }
                            }
                        };
                        $getFilesRecursively($zipfolder);
                        foreach ($files as $file) {
                            $relativePath = substr($file, strlen($zipfolder));
                            $zip->addFile($file, $relativePath);
                        }

                        // Close the zip file
                        $zip->close();
                        $dir = $destinationfolderPath . DIRECTORY_SEPARATOR;
                        $this->emptyDir($dir);
                        rmdir($dir);
                        // Debugging: Check if the zip file was created
                        if (file_exists($zipFilePath)) {
                            // Set headers to force download
                            // header('Content-Type: application/octet-stream');
                            // header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
                            // header('Content-Length: ' . filesize($zipFileName));
                            // header('Pragma: no-cache');
                            // header('Expires: 0');

                            // Output the zip file content
                            // readfile($zipFileName);

                            session()->setFlashdata('success', lang('Messages.Success_0045'));
                            session()->setFlashdata('alert-class', 'alert-danger');
                            if ($_POST['returnUrl'] == 2) {
                                return redirect()->to(base_url('my_training/course_group_list'));
                            } else {
                                return redirect()->to(base_url('SCORM/course_builder/Scorm_course_pages/page_pdf_view'));
                            }
                        } else {
                            session()->setFlashdata('error', 'Zip file not found.');
                            session()->setFlashdata('alert-class', 'alert-danger');
                            if ($_POST['returnUrl'] == 2) {
                                return redirect()->to(base_url('my_training/course_group_list'));
                            } else {
                                return redirect()->to(base_url('SCORM/course_builder/Scorm_course_pages/page_pdf_view'));
                            }
                        }
                    } else {

                        session()->setFlashdata('error', 'Failed to create zip file');
                        session()->setFlashdata('alert-class', 'alert-danger');
                        if ($_POST['returnUrl'] == 2) {
                            return redirect()->to(base_url('my_training/course_group_list'));
                        } else {
                            return redirect()->to(base_url('SCORM/course_builder/Scorm_course_pages/page_pdf_view'));
                        }
                    }
                }
            }
        } else {
            session()->setFlashdata('error', 'toc.json not generated');
            session()->setFlashdata('alert-class', 'alert-danger');
            if ($_POST['returnUrl'] == 2) {
                return redirect()->to(base_url('my_training/course_group_list'));
            } else {
                return redirect()->to(base_url('SCORM/course_builder/Scorm_course_pages/page_pdf_view'));
            }
        }
    }
    function copyFolder($sourceFolderPath, $destinationFolderPath)
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        // Create destination folder if it doesn't exist
        if (!is_dir($destinationFolderPath)) {
            mkdir($destinationFolderPath, 0777, true);
        }

        // Open the source directory
        $dir = opendir($sourceFolderPath);

        // Loop through each file in the source directory
        while (($file = readdir($dir)) !== false) {
            if ($file == '.' || $file == '..') {
                continue; // Skip parent and current directory entries
            }

            $sourceFilePath = $sourceFolderPath . '/' . $file;
            $destinationFilePath = $destinationFolderPath . '/' . $file;

            if (is_dir($sourceFilePath)) {
                // If it's a directory, recursively copy it
                $this->copyFolder($sourceFilePath, $destinationFilePath);
            } else {
                // If it's a file, copy it
                copy($sourceFilePath, $destinationFilePath);
            }
        }

        // Close the directory handle
        closedir($dir);
        return 1;
    }
    function delete_zip($courseId, $zipFileName)
    {
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else if (isset($_SESSION['tab'])) {
            $data['tab'] = $_SESSION['tab'];
        } else {
            $data['tab'] = 1;
        }
        // $courseId = $_GET['course_id'];
        // $zipFileName = $_GET['zip_file_name'];

        // Define the path to the zip file
        $zipFilePath = FCPATH . 'assets/assets/uploads/SCORM_course_document/' . $courseId . '/' . $zipFileName . '.zip';

        // Check if the file exists
        if (file_exists($zipFilePath)) {
            // Delete the file
            unlink($zipFilePath);
            echo "File deleted successfully!";
        } else {
            echo "File not found!";
        }
    }
    function update_status()
    {
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        if (isset($_POST)) {
            $data['course_id'] = $_POST['course_id'];
            $data['page_id'] = $_POST['page_id'];
            $data['status'] = $_POST['status'];

            $newdata = [
                'status' => $data['status'],
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
            return redirect()->to(base_url('SCORM/course_builder/Editor'));
        }
    }
    function add_test_feedback()
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $data = [];
        // helper('filesystem');
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        }
        $pagesDetails = $this->scorm_page_model->getpagealldetails($data['scourse_id']);

        $result = $this->scorm_page_model->addtestFeedback($pagesDetails, $data['scourse_id']);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0011'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url('SCORM/course_builder/Editor'));
    }
    function delete_test_feedback()
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $data = [];
        // helper('filesystem');
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        }
        $result = $this->scorm_page_model->deletetestFeedback($data['scourse_id']);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url('SCORM/course_builder/Editor'));
    }
    function delete_course_pages()
    {
        if ($response =  $this->requireRole(['5', '44', '67', '46'])) {
            return $response;
        }
        $data = [];
        // helper('filesystem');
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        }
        $result = $this->scorm_page_model->deleteCoursePages($data['scourse_id']);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }
        return redirect()->to(base_url('SCORM/course_builder/Editor'));
    }
    function importPageStructure()
    {
        if ($response =  $this->requireRole(['5', '44', '46'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        // print_r($_FILES);
        // exit();

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
                        $sheetData = $spreadsheet->getActiveSheet()->toArray();
                        $filteredData = array_filter($sheetData[0]);
                        $columnCount = count($filteredData);
                        // print_r($columnCount);
                        // exit();
                        $result = $this->scorm_page_model->importpagedetails($sheetData, $data['scourse_id'], $columnCount);
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
                    return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
                }
            }
            return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
        }
    }
}
