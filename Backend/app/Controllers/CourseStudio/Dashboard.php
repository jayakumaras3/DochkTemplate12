<?php

namespace App\Controllers\SCORM\Course_builder_v2;

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
use App\Models\Settings\Dropdown_model;

use Config\AssessmentSets\Assessment_english;
use Config\AssessmentSets\Assessment_french;
use Config\AssessmentSets\Assessment_spanish;
use Config\AssessmentSets\Assessment_russian;
use Config\AssessmentSets\Assessment_portuguese;
use Config\AssessmentSets\Assessment_bahasa;
use Config\AssessmentSets\Assessment_arabic;

#[\AllowDynamicProperties]
class Page_editor extends BaseController
{

    public function __construct()
    {
        $this->is_session_available();
        $this->scorm_course_model = new Scorm_course_model();
        $this->scorm_page_model = new Scorm_page_model();
        $this->scorm_lanuch_model = new Scorm_lanuch_model();
        $this->assessment_training_model = new Assessment_training_model();
        $this->scorm_client_model = new Scorm_client_model();
        $this->dropdown_model = new Dropdown_model();
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
            session()->setFlashdata('message', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }

    function index()
    {
        if ($response =  $this->requireRole(['6', '5', '46', '67', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if (isset($_SESSION['crid'])) {
            $data['scourse_id'] = $_SESSION['crid'];
        } else {
            return redirect()->to(base_url() . 'my_training/read_more');
        }
        $data['courseDetails'] = $this->scorm_course_model->getCourseDetails($data['scourse_id']);
        $data['pagesDetails'] = $this->scorm_page_model->getPageDetails($data['scourse_id']);
        // $data['questiondata'] = $this->scorm_page_model->getAssessmentquestion($data['scourse_id']);

        $page_data = [];
        helper(['form']);
        if (isset($_POST['course_id'])) {
            $page_data['course_id'] = $_POST['course_id'];
        } elseif (isset($_SESSION['crid'])) {
            $page_data['course_id'] = $_SESSION['crid'];
        } elseif (isset($_SESSION['course_id'])) {
            $page_data['course_id'] = $_SESSION['course_id'];
        } elseif (isset($_SESSION['scourse_id'])) {
            $page_data['course_id'] = $_SESSION['scourse_id'];
        } else {
            return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
        }

        if (isset($_POST['page_number'])) {
            $page_data['page_number'] = $_POST['page_number'];
            $_SESSION['page_number'] = $page_data['page_number'];
        } elseif (isset($_SESSION['page_number'])) {
            $page_data['page_number'] = $_SESSION['page_number'];
        } else {
            $page_data['page_number'] = isset($data['pagesDetails'][0]['page_number']) ? $data['pagesDetails'][0]['page_number'] : '';
        }

        $page_data['getAssessmentSettings'] = $this->assessment_training_model->get_assessmentCourselevel_settings($page_data['course_id']);

        $course_lang = $_SESSION['course_lang'];
        $page_data['course_lang'] = $course_lang;
        if ($course_lang == 'French') {
            $page_data['assessment_export_sets'] = Assessment_french::$assessment_export_sets;
            $page_data['assessment_scqmcq_sets'] = Assessment_french::$assessment_scqmcq_sets;
        } elseif ($course_lang == 'Spanish') {
            $page_data['assessment_export_sets'] = Assessment_spanish::$assessment_export_sets;
            $page_data['assessment_scqmcq_sets'] = Assessment_spanish::$assessment_scqmcq_sets;
        } elseif ($course_lang == 'Russian') {
            $page_data['assessment_export_sets'] = Assessment_russian::$assessment_export_sets;
            $page_data['assessment_scqmcq_sets'] = Assessment_russian::$assessment_scqmcq_sets;
        } elseif ($course_lang == 'Portuguese') {
            $page_data['assessment_export_sets'] = Assessment_portuguese::$assessment_export_sets;
            $page_data['assessment_scqmcq_sets'] = Assessment_portuguese::$assessment_scqmcq_sets;
        } elseif ($course_lang == 'Bahasa') {
            $page_data['assessment_export_sets'] = Assessment_bahasa::$assessment_export_sets;
            $page_data['assessment_scqmcq_sets'] = Assessment_bahasa::$assessment_scqmcq_sets;
        } elseif ($course_lang == 'Arabic') {
            $page_data['assessment_export_sets'] = Assessment_arabic::$assessment_export_sets;
            $page_data['assessment_scqmcq_sets'] = Assessment_arabic::$assessment_scqmcq_sets;
        } else {
            $page_data['assessment_export_sets'] = Assessment_english::$assessment_export_sets;
            $page_data['assessment_scqmcq_sets'] = Assessment_english::$assessment_scqmcq_sets;
        }


        $page_data['header_link'] = 'SCORM/course_builder/Editor';
        $page_data['transcript_link'] = 'SCORM/course_builder/Scorm_course_pages/page_transcript_view';
        $page_data['video_link'] = 'SCORM/course_builder/Scorm_course_pages/addpage';
        $page_data['vtt_link'] = 'SCORM/course_builder/scorm_course_pages/uploadvtt';

        $page_data['coursedetails'] = $this->scorm_lanuch_model->coursedetails($page_data['course_id']);
        $page_data['pagedetails'] = $this->scorm_lanuch_model->getpagedetails($page_data['course_id']);

        $pagedata = $this->scorm_page_model->getpagedata_number($page_data['page_number'], $page_data['course_id']);
        if (!empty($pagedata)) {

            $page_data['row'] = $pagedata[0];
            $page_data['page_id'] = $page_data['row']['page_id'];
            $data['current_page_id'] = $page_data['page_id'];
            $page_data['page_name'] = $page_data['row']['page_name'];

            $page_data['page_content'] = $this->scorm_page_model->getpagecontent($page_data['page_number'], $page_data['course_id']);

            $currentpagenum = $page_data['row']['page_number'];
            $fk_course_id = $page_data['row']['fk_course_id'];
            // $prepage = $currentpagenum - 1;
            // $nextpage = $currentpagenum + 1;
            $page_data['sub_page_content'] = $this->scorm_page_model->getSubpagecontent($currentpagenum, $fk_course_id);
            //    $page_data['prev_page'] = $this->scorm_page_model->get_nxt_page($prepage, $fk_course_id);
            //  $page_data['next_page'] = $this->scorm_page_model->get_nxt_page($nextpage, $fk_course_id);


            $page_data['pagetraanscript'] = $this->scorm_page_model->getpagetraanscript($page_data['page_id']);
            $page_data['pageArticulate'] = $this->scorm_page_model->getpageArticulate($page_data['page_id']);
            $page_data['pageVideo'] = $this->scorm_page_model->getpageVideo($page_data['page_id'], 1);
            $page_data['pageVtt'] = $this->scorm_page_model->getpageVideo($page_data['page_id'], 2);

            $page_data['getAllFileOwner'] = $this->scorm_page_model->getAllFileOwner($page_data['page_id']);
            $page_data['getAllvideoFileOwner'] = $this->scorm_page_model->getAllvideoFileOwner($page_data['page_id']);

            if ($page_data['row']['type'] == 5 || $page_data['row']['type'] == 6) {
                $getQuestiondata = $this->assessment_training_model->getQuestionDetails($page_data['page_id']);
                $page_data['question'] = $getQuestiondata[0];
                $page_data['question_options'] = $this->assessment_training_model->getoptiondaata($page_data['question']['q_id']);
            }

            $page_data['feedback'] = $this->scorm_lanuch_model->getAllFeedbackByPageID($page_data['page_id']);
            if (isset($page_data['feedback'])) {
                $review_replies = [];
                foreach ($page_data['feedback'] as $feedback) {
                    // Fetch all replies for each feedback
                    $replies[$feedback['feedbackid']] = $this->scorm_lanuch_model->getAllFeedback_replies($feedback['feedbackid']);
                }

                // Pass the feedbacks and replies to the view
                $page_data['review_replies'] = $review_replies;
            }
            $page_data['getUserlatestclientCourseByScenario'] = $this->scorm_client_model->getUserlatestclientCourseByScenario($fk_course_id, 1);


            $feedbacks = $this->scorm_lanuch_model->getAllQAFeedback($page_data['page_id']);
            $replies = [];
            foreach ($feedbacks as $feedback) {
                // Fetch all replies for each feedback
                $replies[$feedback['feedbackid']] = $this->scorm_lanuch_model->getAllFeedback_replies($feedback['feedbackid']);
            }
            $page_data['feedbacks'] = $feedbacks;
            $page_data['replies'] = $replies;


            if (isset($_POST['tab'])) {
                $data['tab'] = $_POST['tab'];
                $_SESSION['tab'] = $data['tab'];
            } else if (isset($_SESSION['tab'])) {
                $data['tab'] = $_SESSION['tab'];
            } else {
                $data['tab'] = 1;
            }
            $page_data['main_header'] = 'Course Detail';
            $page_data['main_header_link'] = 'my_training/read_more';
            $page_data['header'] = 'Pages';
            $page_data['header_link'] = 'SCORM/course_builder/scorm_course_pages/page_edit_view';
            $page_data['sub_header_1'] = 'Edit Questions';
            $page_data['header_1'] = 'Questions';
            $page_data['header_link_1'] = 'Assessment/trainings/question_list_view';
            $page_data['editquestion'] = 'Assessment/trainings/editquestion';
            $page_data['typeval'] = 11;
            $page_data['form_link'] = 'Assessment/trainings/addoption';
            $page_data['edit_link'] = 'Assessment/trainings/option_edit_view';
            $page_data['delete_link'] = 'Assessment/trainings/deleteOption';
            $page_data['form_url_1'] = 'Assessment/trainings/question_image_upload';
            $page_data['form_url_2'] = 'Assessment/trainings/pdf_upload';
            $page_data['form_url_3'] = 'Assessment/trainings/video_upload';
            $page_data['editpage'] = 'Assessment/trainings/editpage';
            $page_data['editsubpage'] = 'Assessment/trainings/editcyupage';

            $page_data['sub_header_1'] = $page_data['page_name'] . ' - Questions';
            $page_data['quizedit_link'] = 'Assessment/trainings/edit_quetion_view';
            $page_data['quizdelete_link'] = 'Assessment/trainings/deleteQuestion';
            $page_data['setting_link'] = 'Assessment/trainings/add_option_view';
            $page_data['copyQuestion_link'] = 'Assessment/trainings/copyQuestiondetails';
            $page_data['typeval'] = 8;


            // print_r($_SESSION['page_number']);
            // exit();
            $page_data['getCourseData'] = $this->scorm_course_model->getCourseDetails($page_data['course_id']);
            $page_data['pagerow'] = $pagedata[0];
            // print_r($data['pagerow']);
            // exit();
            $getQuestiondatax = $this->assessment_training_model->getQuestionDetails_byQID($page_data['pagerow']['page_id'], $page_data['course_id']);
            // print_r($getQuestiondatax);
            // exit();
            $data['pagetype'] = $this->assessment_training_model->getpagetype($page_data['pagerow']['page_id']);
            if (!empty($getQuestiondatax)) {
                $page_data['question_id'] = $getQuestiondatax[0]['q_id'];

                $getQuestiondata = $this->assessment_training_model->geteditquestiondetails($page_data['question_id']);
                $page_data['qrow'] = $getQuestiondata[0];
                // print_r($data['qrow']);
                // exit();
                $page_data['question_attachment_image'] = $this->assessment_training_model->check_image_file_exists($page_data['question_id'], 1);
                $page_data['question_attachment_video'] = $this->assessment_training_model->check_image_file_exists($page_data['question_id'], 3);
                $page_data['AssessmentQpage_datauestionType'] = $this->dropdown_model->getCountrylist(21);
                $page_data['allcategories'] = $this->scorm_course_model->getAllMetadata(12);
                $page_data['getoptiondata'] = $this->assessment_training_model->getoptiondaata($page_data['question_id']);
                $page_data['CategoryData'] = $this->dropdown_model->getCountrylist(20);

                $page_data['page_content'] = $this->scorm_page_model->getpagecontent($page_data['page_number'], $page_data['course_id']);

                $page_data['page_id'] = $getQuestiondata[0]['page_id'];

                $page_data['getQuestiondata'] = $this->assessment_training_model->getQuestiondata($page_data['course_id'], $page_data['page_id']);
            }

            $currentpagenum = $page_data['pagerow']['page_number'];
            $fk_course_id = $page_data['pagerow']['fk_course_id'];
            $page_data['scourse_id'] = $fk_course_id;
            $prepage = $currentpagenum - 1;
            $nextpage = $currentpagenum + 1;

            $page_data['prev_page'] = $this->scorm_page_model->get_nxt_page($prepage, $fk_course_id);
            $page_data['next_page'] = $this->scorm_page_model->get_nxt_page($nextpage, $fk_course_id);

            $page_data['type'] = 5;
            $page_data['getAssessmentSettings'] = $this->assessment_training_model->get_question_settings($page_data['course_id'], $page_data['pagerow']['page_id']);
            $page_data['AssessmentSettings']['59'] = $this->scorm_course_model->getpageAssignmetadatabyID($page_data['course_id'], $page_data['pagerow']['page_id'], 59);
            $page_data['AssessmentSettings']['60'] = $this->scorm_course_model->getpageAssignmetadatabyID($page_data['course_id'], $page_data['pagerow']['page_id'], 60);
            $page_data['AssessmentSettings']['61'] = $this->scorm_course_model->getpageAssignmetadatabyID($page_data['course_id'], $page_data['pagerow']['page_id'], 61);
            $page_data['AssessmentSettings']['68'] = $this->scorm_course_model->getpageAssignmetadatabyID($page_data['course_id'], $page_data['pagerow']['page_id'], 68);
        }



        echo view('templates/header_view', $data);
        echo view('page/course_builder/left_menu', $page_data);
        echo view('page/course_builder/main', $page_data);
        echo view('templates/footer_view');
    }
}