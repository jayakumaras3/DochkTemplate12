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
use App\Models\Settings\Dropdown_model;

use Config\AssessmentSets\Assessment_english;
use Config\AssessmentSets\Assessment_french;
use Config\AssessmentSets\Assessment_spanish;
use Config\AssessmentSets\Assessment_russian;
use Config\AssessmentSets\Assessment_portuguese;
use Config\AssessmentSets\Assessment_bahasa;
use Config\AssessmentSets\Assessment_arabic;

#[\AllowDynamicProperties]
class Editor extends BaseController
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
        // Lite version: the left menu only ever reads page_id/number/name/type, so it doesn't
        // need getPageDetails()'s 10-table join + GROUP BY (see page_content() below, which
        // still uses the full page row for the currently viewed page).
        $data['pagesDetails'] = $this->scorm_page_model->getpageDetailsLite($data['scourse_id']);

        // Same course_id/page_number resolution page_content() uses below, done here too so the
        // left menu can highlight the active page and so a page-switch POST (from left_menu's
        // per-page forms) persists the chosen page_number to the session before the browser's
        // follow-up AJAX call to page_content() reads it back out.
        if (isset($_POST['course_id'])) {
            $course_id = $_POST['course_id'];
        } elseif (isset($_SESSION['crid'])) {
            $course_id = $_SESSION['crid'];
        } elseif (isset($_SESSION['course_id'])) {
            $course_id = $_SESSION['course_id'];
        } elseif (isset($_SESSION['scourse_id'])) {
            $course_id = $_SESSION['scourse_id'];
        } else {
            return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
        }

        if (isset($_POST['page_number'])) {
            $page_number = $_POST['page_number'];
            $_SESSION['page_number'] = $page_number;
        } elseif (isset($_SESSION['page_number'])) {
            $page_number = $_SESSION['page_number'];
        } else {
            $page_number = isset($data['pagesDetails'][0]['page_number']) ? $data['pagesDetails'][0]['page_number'] : '';
        }

        // page_number isn't guaranteed unique within a course (e.g. right after a manual edit
        // collides with an existing page), so page_id - which left_menu's per-page forms already
        // send - is the authoritative key for "which page is this". page_content() below
        // resolves the same way, from the same session values.
        if (isset($_POST['page_id'])) {
            $page_id = $_POST['page_id'];
            $_SESSION['page_id'] = $page_id;
        } elseif (isset($_SESSION['page_id'])) {
            $page_id = $_SESSION['page_id'];
        } else {
            $page_id = isset($data['pagesDetails'][0]['page_id']) ? $data['pagesDetails'][0]['page_id'] : '';
            if ($page_id !== '') {
                $_SESSION['page_id'] = $page_id;
            }
        }

        // The page previously viewed/selected (via session) may since have been deleted (its
        // status set to 0, e.g. from the Edit Page modal) - getpageDetailsLite() already
        // excludes those, so if it's no longer in this list, fall back to the first page that
        // still exists instead of leaving the left menu with nothing highlighted and
        // page_content() below resolving to no page at all.
        $pageStillExists = false;
        foreach ($data['pagesDetails'] as $eachPage) {
            if ((string) $eachPage['page_id'] === (string) $page_id) {
                $pageStillExists = true;
                break;
            }
        }
        if (!$pageStillExists) {
            $page_id = isset($data['pagesDetails'][0]['page_id']) ? $data['pagesDetails'][0]['page_id'] : '';
            $page_number = isset($data['pagesDetails'][0]['page_number']) ? $data['pagesDetails'][0]['page_number'] : '';
            $_SESSION['page_id'] = $page_id;
            $_SESSION['page_number'] = $page_number;
        }

        if (isset($_POST['tab'])) {
            $_SESSION['tab'] = $_POST['tab'];
        }

        $data['current_page_id'] = $page_id;

        // "Courses" breadcrumb link: points back to wherever the user actually came from
        // (My Courses, Marketplace, Learning Plan, Demos, ...) - see BaseController::coursesBreadcrumbLink().
        $coursesBreadcrumb = $this->coursesBreadcrumbLink();
        $data['courses_link'] = $coursesBreadcrumb['link'];
        $data['courses_link_label'] = $coursesBreadcrumb['label'];
        $data['scourse_id'] = $course_id;

        // The rest of the page (page content, question/assessment data, ~15 more queries) is
        // fetched by the browser right after this renders, via the page_content() endpoint
        // below - so the header/left menu paint immediately instead of waiting on all of it.
        echo view('templates/header_view', $data);
        echo view('page/course_builder/left_menu', $data);
        echo view('page/course_builder/main_loading', $data);
        echo view('templates/footer_view');
    }

    // AJAX-only: renders the main content pane (current page's content, question/assessment
    // data) for the page_number/course_id already resolved and stashed in session by index()
    // above. Split out so index() can paint the header/left menu without waiting on this -
    // see main_loading.php, which fetches this right after the shell loads.
    public function page_content()
    {
        if ($response =  $this->requireRole(['6', '5', '46', '67', '44'])) {
            return $response;
        }
        helper(['form']);

        $page_data = [];
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
            $page_data['page_number'] = '';
        }

        // page_id - sent by left_menu's per-page forms - is the authoritative key for which
        // page to show; page_number alone is ambiguous whenever two pages share a number (see
        // getpagedata_by_id() in Scorm_page_model.php). Only fall back to a page_number lookup
        // when no page_id is known at all (e.g. this endpoint reached without index() having
        // run first in this session).
        if (isset($_POST['page_id'])) {
            $page_data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $page_data['page_id'];
        } elseif (isset($_SESSION['page_id'])) {
            $page_data['page_id'] = $_SESSION['page_id'];
        } else {
            $page_data['page_id'] = '';
        }

        if ($page_data['page_id'] === '' || $page_data['page_number'] === '') {
            $pagesDetails = $this->scorm_page_model->getpageDetailsLite($page_data['course_id']);
            if ($page_data['page_number'] === '') {
                $page_data['page_number'] = isset($pagesDetails[0]['page_number']) ? $pagesDetails[0]['page_number'] : '';
            }
            if ($page_data['page_id'] === '') {
                $page_data['page_id'] = isset($pagesDetails[0]['page_id']) ? $pagesDetails[0]['page_id'] : '';
            }
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

        // Resolve by page_id whenever we have one - page_number can collide between pages
        // within a course, which would otherwise show whichever matching page the query
        // happens to return first instead of the one actually clicked.
        if ($page_data['page_id'] !== '') {
            $pagedata = $this->scorm_page_model->getpagedata_by_id($page_data['page_id'], $page_data['course_id']);
        } else {
            $pagedata = $this->scorm_page_model->getpagedata_number($page_data['page_number'], $page_data['course_id']);
        }

        // The page previously selected (via session) may since have been deleted (e.g. from the
        // Edit Page modal's Status field) - fall back to the first page that still exists rather
        // than rendering an empty "no page" pane, which otherwise left this stuck looking like it
        // never finished loading with no way to tell why.
        if (empty($pagedata)) {
            $fallbackPages = $this->scorm_page_model->getpageDetailsLite($page_data['course_id']);
            if (!empty($fallbackPages)) {
                $page_data['page_id'] = $fallbackPages[0]['page_id'];
                $page_data['page_number'] = $fallbackPages[0]['page_number'];
                $_SESSION['page_id'] = $page_data['page_id'];
                $_SESSION['page_number'] = $page_data['page_number'];
                $pagedata = $this->scorm_page_model->getpagedata_by_id($page_data['page_id'], $page_data['course_id']);
            }
        }

        if (!empty($pagedata)) {

            $page_data['row'] = $pagedata[0];
            $page_data['page_id'] = $page_data['row']['page_id'];
            $page_data['page_name'] = $page_data['row']['page_name'];
            // Keep this authoritative once the row is known, rather than whatever value (POST/
            // session/guess) was used to look it up.
            $page_data['page_number'] = $page_data['row']['page_number'];

            $page_data['page_content'] = $this->scorm_page_model->getpagecontentbyid($page_data['page_id']);

            $currentpagenum = $page_data['row']['page_number'];
            $fk_course_id = $page_data['row']['fk_course_id'];
            $page_data['sub_page_content'] = $this->scorm_page_model->getSubpagecontent($currentpagenum, $fk_course_id);

            $page_data['pageArticulate'] = $this->scorm_page_model->getpageArticulate($page_data['page_id']);
            $page_data['pageVideo'] = $this->scorm_page_model->getpageVideo($page_data['page_id'], 1);
            $page_data['pageVtt'] = $this->scorm_page_model->getpageVideo($page_data['page_id'], 2);

            if ($page_data['row']['type'] == 5 || $page_data['row']['type'] == 6) {
                $getQuestiondata = $this->assessment_training_model->getQuestionDetails($page_data['page_id']);
                $page_data['question'] = $getQuestiondata[0];
                $page_data['question_options'] = $this->assessment_training_model->getoptiondaata($page_data['question']['q_id']);
            }

            $page_data['getUserlatestclientCourseByScenario'] = $this->scorm_client_model->getUserlatestclientCourseByScenario($fk_course_id, 1);


            if (isset($_POST['tab'])) {
                $page_data['tab'] = $_POST['tab'];
                $_SESSION['tab'] = $page_data['tab'];
            } else if (isset($_SESSION['tab'])) {
                $page_data['tab'] = $_SESSION['tab'];
            } else {
                $page_data['tab'] = 1;
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


            $page_data['pagerow'] = $pagedata[0];
            $getQuestiondatax = $this->assessment_training_model->getQuestionDetails_byQID($page_data['pagerow']['page_id'], $page_data['course_id']);
            $page_data['pagetype'] = $this->assessment_training_model->getpagetype($page_data['pagerow']['page_id']);
            if (!empty($getQuestiondatax)) {
                $page_data['question_id'] = $getQuestiondatax[0]['q_id'];

                $getQuestiondata = $this->assessment_training_model->geteditquestiondetails($page_data['question_id']);
                $page_data['qrow'] = $getQuestiondata[0];
                $page_data['allcategories'] = $this->scorm_course_model->getAllMetadata(12);
                $page_data['getoptiondata'] = $this->assessment_training_model->getoptiondaata($page_data['question_id']);
                $page_data['CategoryData'] = $this->dropdown_model->getCountrylist(20);

                $page_data['page_id'] = $getQuestiondata[0]['page_id'];

                $page_data['getQuestiondata'] = $this->assessment_training_model->getQuestiondata($page_data['course_id'], $page_data['page_id']);
            }

            $fk_course_id = $page_data['pagerow']['fk_course_id'];
            $page_data['scourse_id'] = $fk_course_id;

            $page_data['type'] = 5;
            $page_data['getAssessmentSettings'] = $this->assessment_training_model->get_question_settings($page_data['course_id'], $page_data['pagerow']['page_id']);
            $page_data['AssessmentSettings']['59'] = $this->scorm_course_model->getpageAssignmetadatabyID($page_data['course_id'], $page_data['pagerow']['page_id'], 59);
            $page_data['AssessmentSettings']['60'] = $this->scorm_course_model->getpageAssignmetadatabyID($page_data['course_id'], $page_data['pagerow']['page_id'], 60);
            $page_data['AssessmentSettings']['61'] = $this->scorm_course_model->getpageAssignmetadatabyID($page_data['course_id'], $page_data['pagerow']['page_id'], 61);
            $page_data['AssessmentSettings']['68'] = $this->scorm_course_model->getpageAssignmetadatabyID($page_data['course_id'], $page_data['pagerow']['page_id'], 68);
        }

        echo view('page/course_builder/main', $page_data);
    }

    public function settings()
    {
        if ($response =  $this->requireRole(['6', '5', '46', '67', '44'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if (isset($_POST['scourse_id'])) {
            $data['course_name'] = $_POST['course_name'];
            $data['scourse_id'] = $_POST['scourse_id'];
        } else {
            return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
        }
        // course_settings.php only reads page_id/number/name/type/status/sub_page_main - see
        // getpageDetailsLite() in Scorm_page_model.php.
        $data['pagesDetails'] = $this->scorm_page_model->getpageDetailsLite($data['scourse_id']);
        echo view('templates/header_view', $data);
        echo view('page/course_builder/course_settings', $data);
        echo view('templates/footer_view');
    }
}
