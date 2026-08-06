<?php

namespace App\Controllers\SCORM\Course_builder;

use App\Controllers\BaseController;

use App\Models\SCORM\Scorm_dashboard_model;
use App\Models\SCORM\Scorm_lanuch_model;
use App\Models\XAPI\API_model;
use App\Models\SCORM\Scorm_page_model;
use App\Models\SCORM\Scorm_course_model;
use App\Models\Assessment\Assessment_training_model;
use App\Models\SCORM\Scorm_client_model;
use App\Models\Task\Task_model;
use Config\AssessmentSets\Assessment_english;
use Config\AssessmentSets\Assessment_french;
use Config\AssessmentSets\Assessment_spanish;
use Config\AssessmentSets\Assessment_russian;
use Config\AssessmentSets\Assessment_portuguese;
use Config\AssessmentSets\Assessment_bahasa;
use Config\AssessmentSets\Assessment_arabic;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;

#[\AllowDynamicProperties]
class Review_course extends BaseController
{
    private $db;

    public function __construct()
    {

        $this->scorm_dashboard_model = new Scorm_dashboard_model();
        $this->scorm_lanuch_model = new Scorm_lanuch_model();
        $this->API_model = new API_model();
        $this->scorm_client_model = new Scorm_client_model();
        $this->scorm_page_model = new Scorm_page_model();
        $this->assessment_training_model = new Assessment_training_model();
        $this->task_model = new Task_model();
        $this->scorm_course_model = new Scorm_course_model();
    }

    public function launcher($returnpage, $page_number)
    {
        if ($response =  $this->requireRole(['6', '5', '46', '67', '44', '3'])) {
            return $response;
        }
        helper(['form']);
        $data = [];

        // $data['assessment_export_sets'] = Assessment_english::$assessment_export_sets;
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
        $lesson_location = 1;
        if ($_SESSION['crid']) {
            $data['course_id'] = $_SESSION['crid'];
        } else {
            return redirect()->to(base_url() . 'my_training/read_more');
        }

        if (isset($page_number)) {
            $data['page_number'] = $page_number;
        } else {
            return redirect()->to(base_url() . 'my_training/read_more');
        }
        /*
        |--------------------------------------------------------------------------
        | Certification Purchase Validation
        |--------------------------------------------------------------------------
        | Only validate when launched from Certification.
        */
        if (isset($_SESSION['detail_type']) && $_SESSION['detail_type'] == 13 && !empty($_SESSION['certificate_id'])) {

            $isPurchased = $this->CertificationPaymentModel->isCertificatePurchased(session()->get('id_user'), $_SESSION['certificate_id']);
            if (!$isPurchased) {

                session()->setFlashdata('error', 'Please purchase this certification before launching the course.');

                return redirect()->to(
                    base_url('Certification/Certification_Portal/courseDetails')
                );
            }

            // Assign course if not already assigned
            if (
                !$this->scorm_dashboard_model->isassignCourseToUser(
                    session()->get('id_user'),
                    $_SESSION['crid']
                )
            ) {

                $this->scorm_dashboard_model->assigncoursetouser(
                    session()->get('id_user'),
                    $_SESSION['crid'],
                    0,
                    1
                );
            }
        }


        if ($_SESSION['course_detail_launch']) {
            $data['course_return_window'] = $_SESSION['course_detail_launch'];
        } else {
            $data['course_return_window'] = 0;
            $_SESSION['course_detail_launch'] = 0;
        }
        // $client = session()->get('client');
        $_SESSION['tab'] = 1;
        $data['clientdata'] = $this->scorm_lanuch_model->getClientDetails($data['course_id']);
        $user_id = session()->get('id_user');
        $get_course_assigned_id = $this->scorm_lanuch_model->get_course_assigned_id($user_id, $data['course_id']);
        if (count($get_course_assigned_id) === 0) {
            return redirect()->to(base_url() . 'my_training/read_more');
        } else {
            $get_scorm_track_id = $this->scorm_lanuch_model->get_scorm_track_id($get_course_assigned_id[0]['user_assign_id']);

            if (count($get_scorm_track_id) === 0) {
                $scorm_record = [
                    'user_assign_id' => $get_course_assigned_id[0]['user_assign_id'],
                    'course_id' => $data['course_id'],
                    'scenario_id' => 0,
                    'student_id' => $user_id,
                    'lesson_location' => 1,
                    'lesson_status' => 'incomplete',
                    'suspend_data' => 1,
                    'attempt' => 1,
                    'last_active' => time(),
                    'status' => 1,
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time()
                ];
                $this->scorm_lanuch_model->insert_scorm_record($scorm_record);
                $get_scorm_track_id = $this->scorm_lanuch_model->get_scorm_track_id($get_course_assigned_id[0]['user_assign_id']);
                // print_r($get_scorm_track_id[0]['lesson_status']);
                if ($get_course_assigned_id[0]['course_status'] != 2) {
                    $course_status = 1;
                    $this->scorm_lanuch_model->update_course_status($get_course_assigned_id[0]['user_assign_id'], $course_status);
                } elseif (in_array($get_scorm_track_id[0]['lesson_status'], ['failed'])) {
                    $course_status = 1;
                    $this->scorm_lanuch_model->update_course_status($get_course_assigned_id[0]['user_assign_id'], $course_status);
                }
            } elseif (count($get_scorm_track_id) > 0 && in_array($get_scorm_track_id[0]['lesson_status'], ['completed', 'passed', 'failed'])) {
                // print_r($get_scorm_track_id[0]['lesson_status']);
                // exit();
                $attempt = $get_scorm_track_id[0]['attempt'] + 1;
                $newData = [
                    'user_assign_id' => $get_course_assigned_id[0]['user_assign_id'],
                    'student_id' => $user_id,
                    'course_id' => $data['course_id'],
                    'lesson_status' => 'incomplete',
                    'attempt' => $attempt,
                    'status' => 1,
                    'createdby' => $user_id,
                    'createdon' => time(),
                    'last_updated_by' => $user_id,
                    'last_updated_on' => time()
                ];
                $this->scorm_lanuch_model->insert_scorm_record($newData);
                if (in_array($get_scorm_track_id[0]['lesson_status'], ['completed', 'passed'])) {
                    $course_status = 2;
                    // fetch total time

                    $this->scorm_lanuch_model->update_course_status($get_course_assigned_id[0]['user_assign_id'], $course_status);
                }
                if (in_array($get_scorm_track_id[0]['lesson_status'], ['failed'])) {
                    $course_status = 1;
                    $this->scorm_lanuch_model->update_course_status($get_course_assigned_id[0]['user_assign_id'], $course_status);
                }

                $get_scorm_track_id = $this->scorm_lanuch_model->get_scorm_track_id($get_course_assigned_id[0]['user_assign_id']);
            }

            $scorm_track_id = $get_scorm_track_id[0]['sc_uid'];
            $scorm_suspend_data = $get_scorm_track_id[0]['suspend_data'];
            $lesson_location = $get_scorm_track_id[0]['lesson_location'];

            if (strlen($scorm_suspend_data) > 0) {
                $scorm_suspend_data_array = explode(',', $scorm_suspend_data);
                $is_page_num_exists = array_search(abs($data['page_number']), $scorm_suspend_data_array);
                if (strlen($is_page_num_exists) == 0) {
                    array_push($scorm_suspend_data_array, abs($data['page_number']));
                }
            } else {
                $scorm_suspend_data_array = array(1);
            }

            $scorm_suspend_data_string = implode(',', $scorm_suspend_data_array);

            $update_scorm_record_data = [
                'lesson_location' => $data['page_number'],
                'suspend_data' => $scorm_suspend_data_string,
                'lesson_status' => 'incomplete',
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time()
            ];

            $data['inputdata'] = $this->scorm_lanuch_model->update_scorm_record($update_scorm_record_data, $scorm_track_id);
        }

        $data['lesson_location'] = $lesson_location;
        $data['scorm_suspend_data_arr'] = $scorm_suspend_data_array;
        $data['return_page'] = $returnpage;
        $data['coursedetails'] = $this->scorm_lanuch_model->coursedetails($data['course_id']);
        $data['pagedetails'] = $this->scorm_lanuch_model->getpagedetails($data['course_id']);
        //  print_r($data['pagedetails']);
        //     exit();
        $lastPage = end($data['pagedetails']);


        if ($lastPage['type'] != 4 && $page_number == $lastPage['page_number']) {

            if (in_array(abs($page_number), $data['scorm_suspend_data_arr'])) {

                $update_scorm_record_data = [
                    'lesson_location' => $data['page_number'],
                    'suspend_data'    => $scorm_suspend_data_string,
                    'lesson_status'   => 'completed',
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time()
                ];

                $scormstatuschange = $this->scorm_lanuch_model
                    ->update_scorm_record($update_scorm_record_data, $scorm_track_id);

                if ($scormstatuschange) {
                    $course_status = 2; // Course completed
                    $this->scorm_lanuch_model->update_course_status(
                        $get_course_assigned_id[0]['user_assign_id'],
                        $course_status
                    );
                }
            }
        }

        $pagedata = $this->scorm_page_model->getpagedata_number($data['page_number'], $data['course_id']);
        if (!empty($pagedata)) {
            $data['row'] = $pagedata[0];
        } else {
            echo "Data is doesn't exist for this page.Page numbers are not build in sequence order.";
            exit();
        }

        $data['page_content'] = $this->scorm_page_model->getpagecontent($data['page_number'], $data['course_id']);
        $data['transcript'] = $this->scorm_lanuch_model->getAudioscript($data['row']['page_id']);
        $data['pageVideo'] = $this->scorm_page_model->getpageVideo($data['row']['page_id'], 1);
        $data['pageVtt'] = $this->scorm_page_model->getpageVideo($data['row']['page_id'], 2);
        $course_status = $get_course_assigned_id[0]['course_status'];
        if ($data['coursedetails'][0]['mode'] == 2) {
            $data['typeOfLaunch'] = $data['coursedetails'][0]['mode'];
        } else if ($course_status == 3) {
            $data['typeOfLaunch'] = 2;
        } else {
            $data['typeOfLaunch'] = 1;
        }
        $data['page_id'] = $data['row']['page_id'];


        $data['page_type'] = $data['row']['type'];
        if ($data['page_type'] == 8) {
            $data['sub_page_content'] = $this->scorm_page_model->getSubpagecontent($data['row']['page_number'], $data['course_id']);
        }
        $getQuestiondata = $this->assessment_training_model->getQuestionDetails($data['row']['page_id']);
        if (!empty($getQuestiondata)) {
            if ($data['row']['type'] == 5 || $data['row']['type'] == 6 || $data['row']['type'] == 4) {

                $data['question'] = $getQuestiondata[0];
                $data['question_options'] = $this->assessment_training_model->getoptiondaata($data['question']['q_id']);
            }

            if ($data['row']['type'] == 4) {
                $data['question_attachment'] = $this->scorm_page_model->question_attachment($data['course_id'], $data['question']['q_id']);
                // print_r($data['question_attachment']);
                // exit();
                $data['getAssessmentSettings'] = $this->assessment_training_model->get_question_settings($data['course_id'], $data['page_id']);
            }
        }
        $data['getAssessmentSettings'] = $this->assessment_training_model->get_question_settings($data['course_id'], $data['page_id']);
        $data['getcourseAssessmentSettings'] = $this->assessment_training_model->get_assessmentCourselevel_settings($data['course_id']);

        // print_r($data['getcourseAssessmentSettings']);
        // exit();

        echo view('SCORM/course_builder/header', $data);
        echo view('SCORM/course_builder/page_video_view', $data);
        echo view('SCORM/course_builder/footer', $data);
        $_SESSION['course_detail_launch'] = 0;
    }
    public function save_time_on_exit()
    {
        if ($response =  $this->requireRole(['6', '5', '46', '67', '44', '3'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        // nothing needed because we already save every 5 sec
        return $this->response->setJSON(['status' => 'saved']);
    }
    public function update_time()
    {
        if ($response =  $this->requireRole(['6', '5', '46', '67', '44', '3'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $request = service('request');
        $session = session();

        $user_id = $session->get('id_user');
        $course_id = $_SESSION['crid'];

        $json = $request->getJSON(true);

        if ($json && isset($json['time'])) {

            $timeKey = 'course_time_' . $course_id . '_' . $user_id;

            $existing = $session->get($timeKey);

            // ✅ add only delta
            if ($existing) {
                $total = $existing['total_time'] + $json['time'];
            } else {
                $total = $json['time'];
            }

            // store session
            $session->set($timeKey, [
                'total_time' => $total
            ]);

            $get_course_assigned_id = $this->scorm_lanuch_model->get_course_assigned_id($user_id, $course_id);
            if (count($get_course_assigned_id) === 0) {
                // return redirect()->to(base_url() . 'my_training/read_more');
            } else {
                $scorm_track_id = $this->scorm_lanuch_model
                    ->get_scorm_track_id($get_course_assigned_id[0]['user_assign_id']);
            }
            if ($scorm_track_id) {

                if ($json && isset($json['time'])) {

                    $seconds = (int)$json['time'];
                    // print_r($scorm_track_id[0]['sc_uid']).'<br/>';
                    // print_r($seconds);
                    // exit();
                    $result = $this->scorm_lanuch_model->update_total_time(
                        $scorm_track_id[0]['sc_uid'],
                        $seconds
                    );
                }
            }
        }

        return $this->response->setJSON(['status' => $result]);
    }
    public function quizStartPage($course_id, $page_id)
    {
        if ($response =  $this->requireRole(['6', '5', '46', '67', '44', '3'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $course_lang = $_SESSION['course_lang'];
        $data['course_lang'] = $course_lang;
        if ($course_lang == 'French') {
            $data['assessment_export_sets'] = Assessment_french::$assessment_export_sets;
            $data['assessment_sets'] = Assessment_french::$assessment_sets;
        } elseif ($course_lang == 'Spanish') {
            $data['assessment_export_sets'] = Assessment_spanish::$assessment_export_sets;
            $data['assessment_sets'] = Assessment_spanish::$assessment_sets;
        } elseif ($course_lang == 'Russian') {
            $data['assessment_export_sets'] = Assessment_russian::$assessment_export_sets;
            $data['assessment_sets'] = Assessment_russian::$assessment_sets;
        } elseif ($course_lang == 'Portuguese') {
            $data['assessment_export_sets'] = Assessment_portuguese::$assessment_export_sets;
            $data['assessment_sets'] = Assessment_portuguese::$assessment_sets;
        } elseif ($course_lang == 'Bahasa') {
            $data['assessment_export_sets'] = Assessment_bahasa::$assessment_export_sets;
            $data['assessment_sets'] = Assessment_bahasa::$assessment_sets;
        } elseif ($course_lang == 'Arabic') {
            $data['assessment_export_sets'] = Assessment_arabic::$assessment_export_sets;
            $data['assessment_sets'] = Assessment_arabic::$assessment_sets;
        } else {
            $data['assessment_export_sets'] = Assessment_english::$assessment_export_sets;
            $data['assessment_sets'] = Assessment_english::$assessment_sets;
        }

        $data['getAssessmentSettings'] = $this->assessment_training_model->get_question_settings($course_id, $page_id);
        $data['getcourseAssessmentSettings'] = $this->assessment_training_model->get_assessmentCourselevel_settings($course_id);
        $data['course_id'] = $course_id;
        $data['page_id'] = $page_id;
        echo view('SCORM/course_builder/header', $data);
        echo view('SCORM/course_builder/quiz_start_page', $data);
    }
    public function quizQuestions($course_id, $page_id, $question_id_sequense)
    {
        if ($response =  $this->requireRole(['6', '5', '46', '67', '44', '3'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $newarray = [];
        $quizSequence = [];
        $attempt = 0;
        $user_id = session()->get('id_user');
        $course_lang = $_SESSION['course_lang'];
        $data['course_lang'] = $course_lang;
        if ($course_lang == 'French') {
            $data['assessment_export_sets'] = Assessment_french::$assessment_export_sets;
            $data['assessment_sets'] = Assessment_french::$assessment_sets;
        } elseif ($course_lang == 'Spanish') {
            $data['assessment_export_sets'] = Assessment_spanish::$assessment_export_sets;
            $data['assessment_sets'] = Assessment_spanish::$assessment_sets;
        } elseif ($course_lang == 'Russian') {
            $data['assessment_export_sets'] = Assessment_russian::$assessment_export_sets;
            $data['assessment_sets'] = Assessment_russian::$assessment_sets;
        } elseif ($course_lang == 'Portuguese') {
            $data['assessment_export_sets'] = Assessment_portuguese::$assessment_export_sets;
            $data['assessment_sets'] = Assessment_portuguese::$assessment_sets;
        } elseif ($course_lang == 'Bahasa') {
            $data['assessment_export_sets'] = Assessment_bahasa::$assessment_export_sets;
            $data['assessment_sets'] = Assessment_bahasa::$assessment_sets;
        } elseif ($course_lang == 'Arabic') {
            $data['assessment_export_sets'] = Assessment_arabic::$assessment_export_sets;
            $data['assessment_sets'] = Assessment_arabic::$assessment_sets;
        } else {
            $data['assessment_export_sets'] = Assessment_english::$assessment_export_sets;
            $data['assessment_sets'] = Assessment_english::$assessment_sets;
        }
        $data['suspsendData'] = $this->assessment_training_model->getSuspendData($course_id, $user_id);
        $data['sc_uid'] = $data['suspsendData']['0']['sc_uid'];
        $data['attempt'] = $this->assessment_training_model->getAttempt($data['sc_uid']);
        if ($data['attempt']) {
            $attempt = $data['attempt'][0]['attempts_id'];
        }
        if (str_contains($data['suspsendData'][0]['suspend_data'], "|||")) {
            $quizSequence = explode('|||', $data['suspsendData'][0]['suspend_data']);
            $data['getQuestionList'] = explode(",", $quizSequence[1]);;
        } else {
            $quizSequence[0] = $data['suspsendData'][0]['suspend_data'];
        }
        $TotalQuestions = $this->assessment_training_model->getassessment_settings($course_id, $page_id, '22');
        $TotalQuestions = isset($TotalQuestions[0]['value']) ? $TotalQuestions[0]['value'] : "10";

        $RandomizeQuestions = $this->assessment_training_model->getassessment_settings($course_id, $page_id, '1');
        $data['RandomizeQuestions'] = isset($RandomizeQuestions[0]['value']) ? $RandomizeQuestions[0]['value'] : "";

        $RandomizeOptions = $this->assessment_training_model->getassessment_settings($course_id, $page_id, '2');
        $data['RandomizeOptions'] = isset($RandomizeOptions[0]['value']) ? $RandomizeOptions[0]['value'] : "";
        if (isset($data['RandomizeOptions'])) {
            $_SESSION['RandomizeOptions'] = $data['RandomizeOptions'];
        }
        if (isset($_SESSION['RandomizeOptions'])) {
            $data['RandomizeOptions'] = $_SESSION['RandomizeOptions'];
        }
        if ($question_id_sequense == 0) {
            $attempt = $attempt + 1;

            // print_r( $data['RandomizeOptions']."tt");
            // exit();

            $data['questions'] = $this->assessment_training_model->getQuestionList($course_id, $page_id, $TotalQuestions);
            if (empty($data['questions'])) {
                echo 'Under Development';
                exit();
            }
            foreach ($data['questions'] as $quizQuestions) {
                array_push($newarray, $quizQuestions['q_id']);
            }
            if ($data['RandomizeQuestions'] == 'Enabled') {
                shuffle($newarray);
            }

            $data['getQuestionList'] = $newarray;
            $newarray = implode(",", $newarray);
            $newSuspendData = $quizSequence[0] . '|||' . $newarray;
            $newdata = [
                'suspend_data' => $newSuspendData,
            ];
            $this->assessment_training_model->udpateSuspendData($newdata, $course_id, $user_id);
        }
        $data['totalQuestions'] = count($data['getQuestionList']);

        $q_id = $data['getQuestionList'][$question_id_sequense];
        $totalQuestion = count($data['getQuestionList']) - 1;
        $data['course_id'] = $course_id;
        $data['page_id'] = $page_id;
        if ($totalQuestion > $question_id_sequense) {
            $data['next_sequence'] = $question_id_sequense + 1;
        } else {
            $data['next_sequence'] = 'NoQuestions';
        }
        $data['attempt'] = $attempt;
        $data['QuizQuestions'] = $this->assessment_training_model->getQuestionForQuiz($q_id);
        $data['optionsval'] = $this->assessment_training_model->getOptionsforQuestion($q_id);
        $data['question_attachment'] = $this->scorm_page_model->question_attachment($course_id, $q_id);
        $data['getCourseData'] = $this->scorm_course_model->getCourseDetails($course_id);
        // print_r($data['question_attachment']);
        // exit();
        $data['getAssessmentSettings'] = $this->assessment_training_model->get_question_settings($course_id, $page_id);
        $data['getcourseAssessmentSettings'] = $this->assessment_training_model->get_assessmentCourselevel_settings($data['course_id']);
        echo view('SCORM/course_builder/header', $data);
        echo view('SCORM/course_builder/quiz_questions', $data);
    }
    public function questionSubmitted($course_id, $page_id, $question_id_sequense)
    {
        if ($response =  $this->requireRole(['6', '5', '46', '67', '44', '3'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $course_lang = $_SESSION['course_lang'];
        $data['course_lang'] = $course_lang;
        if ($course_lang == 'French') {
            $data['assessment_export_sets'] = Assessment_french::$assessment_export_sets;
            $data['assessment_sets'] = Assessment_french::$assessment_sets;
        } elseif ($course_lang == 'Spanish') {
            $data['assessment_export_sets'] = Assessment_spanish::$assessment_export_sets;
            $data['assessment_sets'] = Assessment_spanish::$assessment_sets;
        } elseif ($course_lang == 'Russian') {
            $data['assessment_export_sets'] = Assessment_russian::$assessment_export_sets;
            $data['assessment_sets'] = Assessment_russian::$assessment_sets;
        } elseif ($course_lang == 'Portuguese') {
            $data['assessment_export_sets'] = Assessment_portuguese::$assessment_export_sets;
            $data['assessment_sets'] = Assessment_portuguese::$assessment_sets;
        } elseif ($course_lang == 'Bahasa') {
            $data['assessment_export_sets'] = Assessment_bahasa::$assessment_export_sets;
            $data['assessment_sets'] = Assessment_bahasa::$assessment_sets;
        } elseif ($course_lang == 'Arabic') {
            $data['assessment_export_sets'] = Assessment_arabic::$assessment_export_sets;
            $data['assessment_sets'] = Assessment_arabic::$assessment_sets;
        } else {
            $data['assessment_export_sets'] = Assessment_english::$assessment_export_sets;
            $data['assessment_sets'] = Assessment_english::$assessment_sets;
        }

        $data['course_id'] = isset($course_id) ? $course_id : $_POST['sc_uid'];
        $data['page_id'] = isset($page_id) ? $page_id : $_POST['sc_uid'];
        $data['scid'] = isset($sc_uid) ? $sc_uid : $_POST['sc_uid'];
        $data['attempts_id'] = isset($attempt) ? $attempt : $_POST['attempt'];
        $data['assessemnt_q_id'] = isset($questionId) ? $questionId : $_POST['questionId'];
        $data['student_option_id'] = isset($optionID) ? $optionID : $_POST['optionID'];
        $totalOptionsSelected = count($data['student_option_id']);
        $totalscore = 0;
        foreach ($data['student_option_id'] as $selected) {
            $iscorrect = $this->assessment_training_model->is_Correct_Singe_Choice($selected);
            $iscorrect_val = $iscorrect[0]['truefalse'];
            $totalscore = $totalscore + $iscorrect_val;
        }

        if ($totalOptionsSelected == $totalscore) {
            $correct = 1;
        } else {
            $correct = 0;
        }

        $data['student_option_id'] = implode(',', $data['student_option_id']);

        $newdata = [
            'scid' => $data['scid'],
            'attempts_id' => $data['attempts_id'],
            'assessemnt_q_id' => $data['assessemnt_q_id'],
            'student_option_id' => $data['student_option_id'],
            'score' => $correct,
            'status' => 1,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];
        $this->assessment_training_model->add_record_options($newdata);

        if ($question_id_sequense == 'NoQuestions') {
            return redirect()->to(base_url() . 'SCORM/Course_builder/Review_course/showResults/' . $data['attempts_id'] . '/' . $course_id . '/' . $page_id . '/' . $data['scid']);
        } else {
            return redirect()->to(base_url() . 'SCORM/Course_builder/Review_course/quizQuestions/' . $course_id . '/' . $page_id . '/' . $question_id_sequense);
        }
    }
    public function showResults($attempts_id, $course_id, $page_id, $scid)
    {
        if ($response =  $this->requireRole(['6', '5', '46', '67', '44', '3'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $course_lang = $_SESSION['course_lang'];
        $data['course_lang'] = $course_lang;
        if ($course_lang == 'French') {
            $data['assessment_export_sets'] = Assessment_french::$assessment_export_sets;
            $data['assessment_sets'] = Assessment_french::$assessment_sets;
        } elseif ($course_lang == 'Spanish') {
            $data['assessment_export_sets'] = Assessment_spanish::$assessment_export_sets;
            $data['assessment_sets'] = Assessment_spanish::$assessment_sets;
        } elseif ($course_lang == 'Russian') {
            $data['assessment_export_sets'] = Assessment_russian::$assessment_export_sets;
            $data['assessment_sets'] = Assessment_russian::$assessment_sets;
        } elseif ($course_lang == 'Portuguese') {
            $data['assessment_export_sets'] = Assessment_portuguese::$assessment_export_sets;
            $data['assessment_sets'] = Assessment_portuguese::$assessment_sets;
        } elseif ($course_lang == 'Bahasa') {
            $data['assessment_export_sets'] = Assessment_bahasa::$assessment_export_sets;
            $data['assessment_sets'] = Assessment_bahasa::$assessment_sets;
        } elseif ($course_lang == 'Arabic') {
            $data['assessment_export_sets'] = Assessment_arabic::$assessment_export_sets;
            $data['assessment_sets'] = Assessment_arabic::$assessment_sets;
        } else {
            $data['assessment_export_sets'] = Assessment_english::$assessment_export_sets;
            $data['assessment_sets'] = Assessment_english::$assessment_sets;
        }
        $data['score_data'] = $this->assessment_training_model->getResultValue($attempts_id, $scid);
        $data['totalQuestion'] = count($data['score_data']);
        $correctAnswer = 0;
        foreach ($data['score_data'] as $finalScore) {
            $correctAnswer = $correctAnswer + $finalScore['score'];
        }
        $data['correctAnswer'] = $correctAnswer;
        $data['percentage'] = round($correctAnswer / $data['totalQuestion'] * 100);
        $data['course_id'] = $course_id;
        $data['page_id'] = $page_id;
        $passingScore = $this->assessment_training_model->getassessment_settings($data['course_id'], $data['page_id'], '23');
        $passingScore = isset($passingScore[0]['value']) ? $passingScore[0]['value'] : '80';
        $data['getAssessmentSettings'] = $this->assessment_training_model->get_question_settings($course_id, $page_id);
        $data['getcourseAssessmentSettings'] = $this->assessment_training_model->get_assessmentCourselevel_settings($data['course_id']);
        // print_r($passingScore);
        // exit();
        if ($data['percentage'] >= $passingScore) {
            $data['Result'] = 'Passed';
            $newdata = [
                'raw' => $data['percentage'],
                'min' => 0,
                'max' => 100,
                'lesson_status' => 'passed',
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time()
            ];
        } else {
            $data['Result'] = 'Failed';
            $newdata = [
                'raw' => $data['percentage'],
                'min' => 0,
                'max' => 100,
                'lesson_status' => 'failed',
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time()
            ];
        }
        $this->assessment_training_model->updateLMSData($newdata, $scid);
        echo view('SCORM/course_builder/header', $data);
        echo view('SCORM/course_builder/quiz_result', $data);
    }
    public function update_reviewstatus($course_id, $url)
    {
        if ($response =  $this->requireRole(['6', '5', '46', '67', '44', '3'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $newdata = [
            'course_status' => '3',
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];
        $result = $this->scorm_lanuch_model->update_reviewstatus($newdata, $course_id);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0043'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
        }
        if ($url == 7) {
            return redirect()->to(base_url() . 'SCORM/Scorm_client/reviews');
        } elseif ($url == 10) {
            return redirect()->to(base_url() . 'my_training/read_more');
        } else {
            return redirect()->to(base_url() . 'my_training');
        }
    }
    public function menu($page_number)
    {
        if ($response =  $this->requireRole(['6', '5', '46', '67', '44', '3'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if ($_SESSION['crid']) {
            $data['course_id'] = $_SESSION['crid'];
        } else {
            return redirect()->to(base_url() . 'my_training/read_more');
        }
        if (isset($page_number)) {
            $data['page_number'] = $page_number;
        } else {
            return redirect()->to(base_url() . 'my_training/read_more');
        }
        $data['clientdata'] = $this->scorm_lanuch_model->getClientDetails($data['course_id']);
        $data['pagedetails'] = $this->scorm_lanuch_model->getpagedetails($data['course_id']);
        $data['getAssessmentSettings'] = $this->assessment_training_model->get_assessmentCourselevel_settings($data['course_id']);
        // print_r($data['getAssessmentSettings']);
        // exit();
        echo view('SCORM/course_builder/header', $data);
        echo view('SCORM/Course_builder/menu', $data);
        echo view('SCORM/course_builder/footer', $data);
    }

    public function feedback_launcher($course_id, $page_id, $typeOfLaunch)
    {
        if ($response =  $this->requireRole(['6', '5', '46', '67', '44', '3'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else if (isset($_SESSION['tab'])) {
            $data['tab'] = $_SESSION['tab'];
        } else {
            $data['tab'] = 1;
        }
        $data['course_id'] = isset($course_id) ? $course_id : $_POST['course_id'];
        $data['page_id'] = isset($page_id) ? $page_id : $_POST['page_id'];


        $pagedata = $this->scorm_page_model->getpagedata($data['page_id']);
        $data['row'] = $pagedata[0];
        // print_r($data['row']);


        $feedbacks = $this->scorm_lanuch_model->getAllFeedback($data['page_id']);
        $replies = [];

        echo view('SCORM/course_builder/header', $data);
        foreach ($feedbacks as $feedback) {
            // Fetch all replies for each feedback
            $replies[$feedback['feedbackid']] = $this->scorm_lanuch_model->getAllFeedback_replies($feedback['feedbackid']);
        }

        // Pass the feedbacks and replies to the view
        $data = [
            'feedbacks' => $feedbacks,
            'replies' => $replies
        ];
        echo view('SCORM/Course_builder/page_feedback_view', $data);
        echo view('SCORM/course_builder/footer', $data);
    }

    public function scorm_feedback_launcher($course_id, $page_id, $typeOfLaunch)
    {
        // if($page_id);
        // exit(); 
        if ($response =  $this->requireRole(['6', '5', '46', '67', '44', '3'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else if (isset($_SESSION['tab'])) {
            $data['tab'] = $_SESSION['tab'];
        } else {
            $data['tab'] = 1;
        }
        $data['course_id'] = isset($course_id) ? $course_id : $_POST['course_id'];
        $data['page_id'] = isset($page_id) ? $page_id : $_POST['page_id'];
        $data['typeOfLaunch'] = isset($typeOfLaunch) ? $typeOfLaunch : $_POST['typeOfLaunch'];

        $pagedata = $this->scorm_page_model->getpagedata($data['page_id']);
        if (!empty($pagedata)) {
            $data['row'] = $pagedata[0];
        } else {
            $data['pageIDValidation'] = "The page setup has not been done correctly. Kindly Contact Developer's";
            // echo '<br/>';
            // exit();
        }



        $feedbacks = $this->scorm_lanuch_model->getAllFeedback($data['page_id']);
        $replies = [];

        echo view('SCORM/course_builder/header', $data);
        foreach ($feedbacks as $feedback) {
            // Fetch all replies for each feedback
            $replies[$feedback['feedbackid']] = $this->scorm_lanuch_model->getAllFeedback_replies($feedback['feedbackid']);
        }

        // Pass the feedbacks and replies to the view
        $data = [
            'feedbacks' => $feedbacks,
            'replies' => $replies
        ];
        echo view('SCORM/course_builder/page_scorm_feedback_view', $data);
        //echo view('SCORM/course_builder/footer', $data);
    }

    public function launcher3($course_id, $page_id)
    {
        if ($response =  $this->requireRole(['6', '5', '46', '67', '44', '3'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $data['course_id'] = isset($course_id) ? $course_id : $_POST['course_id'];
        $data['page_id'] = isset($page_id) ? $page_id : $_POST['page_id'];

        $data['coursedetails'] = $this->scorm_lanuch_model->coursedetails($data['course_id']);
        $data['pagedetails'] = $this->scorm_lanuch_model->getpagedetails($data['course_id']);

        $pagedata = $this->scorm_page_model->getpagedata($data['page_id']);

        $data['page_content'] = $this->scorm_page_model->getpagecontent($data['page_number'], $data['course_id']);
        $data['transcript'] = $this->scorm_lanuch_model->getAudioscript($data['page_id']);
        $data['row'] = $pagedata[0];


        $currentpagenum = $data['row']['page_number'];
        $fk_course_id = $data['row']['fk_course_id'];
        $prepage = $currentpagenum - 1;
        $nextpage = $currentpagenum + 1;
        $data['prev_page'] = $this->scorm_page_model->get_prev_page($prepage, $fk_course_id);
        $data['next_page'] = $this->scorm_page_model->get_nxt_page($nextpage, $fk_course_id);
        if ($data['row']['type'] == 5 || $data['row']['type'] == 5) {
            $getQuestiondata = $this->assessment_training_model->getQuestionDetails($data['page_id']);
            $data['question'] = $getQuestiondata[0];
            $data['question_options'] = $this->assessment_training_model->getoptiondaata($data['question']['q_id']);
        }

        echo view('SCORM/course_builder/header', $data);
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
        echo view('page/qa_feedback_view', $data);
        echo view('SCORM/course_builder/footer', $data);
    }

    public function showPageFeedback()
    {
        if ($response =  $this->requireRole(['6', '5', '46', '67', '44', '3'])) {
            return $response;
        }
        helper(['form']);
        $data = [];

        if (isset($_POST['page_id'])) {
            $data['page_id'] = $_POST['page_id'];
            $_SESSION['page_id'] = $data['page_id'];
        } else if (isset($_SESSION['page_id'])) {
            $data['page_id'] = $_SESSION['page_id'];
        } else {
            return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
        }

        if (isset($_POST['page_name'])) {
            $data['page_name'] = $_POST['page_name'];
            $_SESSION['page_name'] = $data['page_name'];
        } else if (isset($_SESSION['page_name'])) {
            $data['page_name'] = $_SESSION['page_name'];
        } else {
            return redirect()->to(base_url() . 'SCORM/course_builder/Editor');
        }

        $data['feedback'] = $this->scorm_lanuch_model->getAllFeedbackByPageID($data['page_id']);
        foreach ($data['feedback'] as $feedback) {
            // Fetch all replies for each feedback
            $replies[$feedback['feedbackid']] = $this->scorm_lanuch_model->getAllFeedback_replies($feedback['feedbackid']);
        }

        // Pass the feedbacks and replies to the view
        $data['replies'] = $replies;


        echo view('templates/header_view');
        echo view('SCORM/scorm_courses/pages_feedback_view', $data);
        echo view('templates/footer_view');
    }
    public function showfeedback()
    {
        if ($response =  $this->requireRole(['6', '46', '67', '45'])) {
            return $response;
        }
        helper(['form']);
        $data = [];

        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] = $data['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        } else {
            return redirect()->to(base_url() . '/SCORM/scorm_courses');
        }
        if (isset($_POST['stage'])) {
            $data['stage'] = $_POST['stage'];
            $_SESSION['stage'] = $data['stage'];
        } else if (isset($_SESSION['stage'])) {
            $data['stage'] = $_SESSION['stage'];
        } else {
            return redirect()->to(base_url() . '/SCORM/scorm_courses');
        }

        if (isset($_POST['course_name'])) {
            $data['course_name'] = $_POST['course_name'];
            $_SESSION['course_name'] = $data['course_name'];
        } else if (isset($_SESSION['course_name'])) {
            $data['course_name'] = $_SESSION['course_name'];
        } else {
            return redirect()->to(base_url() . '/SCORM/scorm_courses');
        }

        $data['feedback'] = $this->scorm_lanuch_model->getAllFeedbackByCourseId($data['scourse_id'], $data['stage']);
        $data['feedback_count'] = $this->scorm_lanuch_model->getcountFeedbackByCourseId($data['scourse_id']);
        // print_r($data['feedback_count']);
        // exit();

        // "Courses" breadcrumb link: points back to wherever the user actually came from
        // (My Courses, Marketplace, Learning Plan, Demos, ...) - see BaseController::coursesBreadcrumbLink().
        $coursesBreadcrumb = $this->coursesBreadcrumbLink();
        $data['courses_link'] = $coursesBreadcrumb['link'];
        $data['courses_link_label'] = $coursesBreadcrumb['label'];

        echo view('templates/header_view');
        echo view('SCORM/scorm_courses/courses_feedback_view', $data);
        echo view('templates/footer_view');
    }
    public function exportfeedbackreplies()
    {
        if ($response =  $this->requireRole(['6', '5', '46', '67', '44', '3'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if ($this->request->getPost()) {

            $scourse_id = $this->request->getPost('scourse_id');
            $course_name = $this->request->getPost('course_name');
            $rowCount = 2;
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set column headers
            $sheet->setCellValue('A1', 'Feedback ID');
            $sheet->setCellValue('B1', 'Page Name');
            $sheet->setCellValue('C1', 'Video Time');
            $sheet->setCellValue('D1', 'Reply');
            $sheet->setCellValue('E1', 'Feedback/Replies');
            $sheet->setCellValue('F1', 'Stage');
            $sheet->setCellValue('G1', 'Status');
            $sheet->setCellValue('H1', 'Type');
            $sheet->setCellValue('I1', 'Category');
            $sheet->setCellValue('J1', 'Severity');
            $sheet->setCellValue('K1', 'Created By');
            $sheet->setCellValue('L1', 'Created On');

            $feedbacks = $this->scorm_page_model->getExportFeedback($scourse_id);

            foreach ($feedbacks as $feedback) {
                if (isset($feedback['feedbackid'])) {
                    // Set feedback status
                    switch ($feedback['stage']) {
                        case 1:
                            $stage = 'Development';
                            break;
                        case 2:
                            $stage = 'Live';
                            break;
                        case 3:
                            $stage = 'Alpha Review';
                            break;
                        case 4:
                            $stage = 'Alpha 2 Review';
                            break;
                        case 5:
                            $stage = 'Beta Review';
                            break;
                        case 6:
                            $stage = 'Beta 2 Review';
                            break;
                        case 7:
                            $stage = 'Gamma';
                            break;
                        case 8:
                            $stage = 'Gamma 2';
                            break;
                    }

                    $status = '';
                    switch ($feedback['status']) {
                        case 1:
                            $status = 'New';
                            break;
                        case 2:
                            $status = 'Replied';
                            break;
                        case 3:
                            $status = 'Fixed';
                            break;
                        case 4:
                            $status = 'QA Ver';
                            break;
                        case 5:
                            $status = 'ReOpen';
                            break;
                        case 6:
                            $status = 'Closed';
                            break;
                    }

                    // Prepare the feedback content (with newline between feedback and feedback reply)
                    $feedbackText = strip_tags(html_entity_decode($feedback['feedback']));
                    // Set video time
                    $init = isset($feedback['videotime']) ? intval($feedback['videotime']) : 0;
                    $videotime = '';
                    if ($init > 0) {
                        $hours = floor($init / 3600);
                        $minutes = floor(($init / 60) % 60);
                        $seconds = $init % 60;
                        $videotime = "$minutes:$seconds";
                    }

                    $comment_type = $feedback['comment_type'];
                    switch ($comment_type) {
                        case 1:
                            $comment_type = "Change";
                            break;
                        case 2:
                            $comment_type = "Defect";
                            break;
                        case 3:
                            $comment_type = "Question";
                            break;
                        case 4:
                            $comment_type = "Suggestion";
                            break;
                        default:
                            $comment_type = "";
                            break;
                    }
                    $serverity = $feedback['serverity'];
                    switch ($serverity) {
                        case 1:
                            $serverity = "Low";
                            break;
                        case 2:
                            $serverity = "Medium";
                            break;
                        case 3:
                            $serverity = "High";
                            break;
                        default:
                            $serverity = "";
                            break;
                    }
                    $comment_category = $feedback['comment_category'];
                    switch ($comment_category) {
                        case 1:
                            $comment_category = "Visual";
                            break;
                        case 2:
                            $comment_category = "Audio";
                            break;
                        case 3:
                            $comment_category = "Content";
                            break;
                        case 4:
                            $comment_category = "Functionaliy";
                            break;
                        case 5:
                            $comment_category = "Global";
                            break;
                        default:
                            $comment_category = "";
                            break;
                    }
                    // Set feedback details
                    $createdon = date('Y-m-d', $feedback['createdon']);
                    $sheet->setCellValue('A' . $rowCount, $feedback['feedbackid']);
                    $sheet->setCellValue('B' . $rowCount, $feedback['page_name']);
                    $sheet->setCellValue('C' . $rowCount, $videotime);
                    $sheet->setCellValue('D' . $rowCount, 'F'); // Feedback indicator
                    $sheet->setCellValue('E' . $rowCount, $feedbackText);
                    $sheet->setCellValue('F' . $rowCount, $stage);
                    $sheet->setCellValue('G' . $rowCount, $status);
                    $sheet->setCellValue('H' . $rowCount, $comment_type);
                    $sheet->setCellValue('I' . $rowCount, $comment_category);
                    $sheet->setCellValue('J' . $rowCount, $serverity);
                    $sheet->setCellValue('K' . $rowCount, $feedback['name']);
                    $sheet->setCellValue('L' . $rowCount, $createdon);

                    // Increment rowCount for the next feedback or reply
                    $rowCount++;

                    // Get replies for the feedback
                    $replyfeedbacks = $this->scorm_page_model->getExportFeedback_replies($feedback['feedbackid']);
                    foreach ($replyfeedbacks as $reply) {
                        switch ($reply['stage']) {
                            case 1:
                                $replystage = 'Development';
                                break;
                            case 2:
                                $replystage = 'Live';
                                break;
                            case 3:
                                $replystage = 'Alpha Review';
                                break;
                            case 4:
                                $replystage = 'Alpha 2 Review';
                                break;
                            case 5:
                                $replystage = 'Beta Review';
                                break;
                            case 6:
                                $replystage = 'Beta 2 Review';
                                break;
                            case 7:
                                $replystage = 'Gamma Review';
                                break;
                            case 8:
                                $replystage = 'Gamma 2 Review';
                                break;
                            default:
                                $replystage = '';
                                break;
                        }
                        $sheet->setCellValue('A' . $rowCount, $feedback['feedbackid']); // Same feedback ID for replies
                        $sheet->setCellValue('A' . $rowCount, $feedback['feedbackid']);
                        $sheet->setCellValue('B' . $rowCount, $feedback['page_name']);
                        // $sheet->setCellValue('C' . $rowCount, $videotime);
                        $sheet->setCellValue('D' . $rowCount, 'R'); // Reply indicator
                        $sheet->setCellValue('F' . $rowCount, $replystage);
                        $sheet->setCellValue('E' . $rowCount, strip_tags(html_entity_decode($reply['feedback_reply']))); // Reply content
                        $sheet->setCellValue('K' . $rowCount, $reply['name']);
                        $sheet->setCellValue('L' . $rowCount, date('Y-m-d', $reply['createdon']));
                        $rowCount++;
                    }
                }
            }

            // Set filename for download
            $datetoday = 'Report_' . $course_name . '_feedback';
            $datetoday .= '_' . date("d-m-Y");
            $datetoday = str_replace(',', '_', $datetoday);
            $datetoday = preg_replace('/\s+/', '_', $datetoday);
            $filename = trim($datetoday) . '.xlsx';

            // Output Excel file
            $writer = new Xlsx($spreadsheet);

            // Clean output buffer
            if (ob_get_length()) {
                ob_end_clean();
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            // Send to browser
            $writer->save('php://output');
            exit;
        }
    }

    public function exportfeedbackreplies_backup()
    {
        if ($response =  $this->requireRole(['6', '5', '46', '67', '44', '3'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        if ($this->request->getPost()) {

            $scourse_id = $this->request->getPost('scourse_id');
            $course_name = $this->request->getPost('course_name');

            // $pagedetails =  $this->scorm_lanuch_model->getpagedetails($scourse_id);
            // print_r($pagedetails);
            // exit();
            $csvData = "Feedback Id,Page Name,Time,Feedback and Replies,Status,Created By,Created On\n";
            $feedbacks = $this->scorm_page_model->getExportFeedback_replies($scourse_id);
            foreach ($feedbacks as $feedback) {
                if (isset($feedback['feedbackid'])) {
                    if ($feedback['status'] == 1) {
                        $status = 'New';
                    } elseif ($feedback['status'] == 2) {
                        $status = 'Replied';
                    } elseif ($feedback['status'] == 3) {
                        $status = 'Fixed';
                    } elseif ($feedback['status'] == 4) {
                        $status = 'QA Ver';
                    } elseif ($feedback['status'] == 5) {
                        $status = 'ReOpen';
                    } elseif ($feedback['status'] == 6) {
                        $status = 'Closed';
                    }

                    // Prepare the feedback content (with newline between feedback and feedback reply)
                    $feedbackText = 'F : ' . strip_tags(html_entity_decode($feedback['feedback']));
                    $feedbackReplyText = isset($feedback['feedback_reply']) ? 'R : ' . strip_tags(html_entity_decode($feedback['feedback_reply'])) : '';

                    // Combine feedback and feedback reply into one cell, with a newline
                    $combinedFeedback = $feedbackText . "\n" . $feedbackReplyText;
                    $init = isset($feedback['videotime']) ? intval($feedback['videotime']) : 0;
                    $videotime = '';
                    if ($init > 0) {
                        $hours = floor($init / 3600);
                        $minutes = floor(($init / 60) % 60);
                        $seconds = $init % 60;
                        $videotime = "$minutes:$seconds";
                        $videotime = isset($videotime) ? $videotime : '';
                    }
                    // Concatenate feedback fields into a single CSV row
                    $csvData .= $feedback['feedbackid'] . ','
                        . $this->encodeForCSV($feedback['page_name']) . ','
                        . $this->encodeForCSV($videotime) . ','
                        . $this->encodeForCSV($combinedFeedback) . ',' // Wrap the combined feedback and reply in quotes
                        . $this->encodeForCSV($status) . ','
                        . $this->encodeForCSV($feedback['name']) . ','
                        . $this->encodeForCSV(date('m-d-Y', $feedback['createdon'])) . "\r\n"; // End the current row with a newline

                }
            }

            // Output the CSV headers and content
            $this->response->setHeader('Content-Type', 'text/csv; charset=UTF-8')
                ->setHeader('Content-Disposition', 'attachment; filename="Report_' . $course_name . '_feedback.csv"')
                ->setBody("\xEF\xBB\xBF" . $csvData);
            return $this->response;

            // Print the CSV data
            echo $csvData;
            exit();
        }
    }


    private function encodeForCSV($value)
    {
        if ($response =  $this->requireRole(['6', '5', '46', '67', '44', '3'])) {
            return $response;
        }
        $value = str_replace(["\r\n", "\r", "\n"], ' ', $value);

        $value = str_replace('"', '""', $value);
        if (strpos($value, ',') !== false || strpos($value, "\n") !== false || strpos($value, "\r") !== false || strpos($value, '"') !== false) {
            $value = '"' . $value . '"';
        }

        return $value;
    }


    public function showfeedbackReplies()
    {
        if ($response =  $this->requireRole(['6', '5', '46', '67', '44', '3'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $data['current_user'] = session()->get('id_user');
        if (isset($_POST['feedbackid'])) {
            $data['feedbackid'] = $_POST['feedbackid'];
            $data['typeofpage'] = $_POST['typeofpage'];
            $_SESSION['feedbackid'] = $data['feedbackid'];
            $_SESSION['typeofpage'] = $data['typeofpage'];
        } else if (isset($_SESSION['feedbackid'])) {
            $data['feedbackid'] = $_SESSION['feedbackid'];
            $data['typeofpage'] = $_SESSION['typeofpage'];
        } else {
            return redirect()->to(base_url() . 'SCORM/Course_builder/review_course/showfeedback');
        }

        $data['feedback_details'] = $this->scorm_lanuch_model->getFeedbackDetails($data['feedbackid']);
        $data['feedback_replies'] = $this->scorm_lanuch_model->getFeedbackReplies($data['feedbackid']);
        $data['scourse_id'] = $data['feedback_details'][0]['course_id'];
        $data['pageid'] = $data['feedback_details'][0]['pageid'];

        $data['getPageNameByPageID'] = $this->scorm_client_model->getPageNameByPageID($data['pageid']);

        $data['getNextPage'] = $this->scorm_client_model->getNextPageID($data['feedbackid'], $data['scourse_id']);
        $data['getPrevPage'] = $this->scorm_client_model->getPrevPageID($data['feedbackid'], $data['scourse_id']);

        $data['getUserlatestclientCourseByScenario'] = $this->scorm_client_model->getUserlatestclientCourseByScenario($data['scourse_id'], 1);
        $data['getTaskbyFeedbackID'] = $this->task_model->getTaskbyid2($data['feedbackid'], 1);

        echo view('templates/header_view');
        echo view('SCORM/scorm_courses/courses_feedback_replies_view', $data);
        echo view('templates/footer_view');
    }
    public function deleteFeedback()
    {

        if ($response =  $this->requireRole(['6', '5', '46', '67', '44', '3'])) {
            return $response;
        }
        helper(['form']);
        $data_Value = [];
        $data_Value['feedbackid'] = $this->request->getVar('feedbackid');
        $delfeeddata = [
            'status' => 0,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];

        $this->scorm_page_model->deleteFeedbackData($delfeeddata, $data_Value['feedbackid']);
        session()->setFlashdata('success', lang('Messages.Success_0005'));
        return redirect()->to(base_url() . 'SCORM/Course_builder/review_course/showfeedback');
    }
    public function closefeedback()
    {
        if ($response =  $this->requireRole(['6', '5', '46', '67', '44', '3'])) {
            return $response;
        }
        $data_Value = [];
        helper(['form']);
        $data_Value['feedbackid'] = $this->request->getVar('feedbackid');
        // $data_Value['typeofpage'] = $_POST['typeofpage'];
        $feedbackupadate = [
            'status' => $this->request->getVar('status'),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];

        $this->scorm_page_model->deleteFeedbackData($feedbackupadate, $data_Value['feedbackid']);
        return redirect()->to(base_url() . 'SCORM/Course_builder/review_course/showfeedback');
    }
    public function delReply()
    {
        if ($response =  $this->requireRole(['6', '5', '46', '67', '44', '3'])) {
            return $response;
        }
        helper(['form']);
        $data_Value = [];
        $data_Value['feedbackreplyid'] = $this->request->getVar('feedbackreplyid');
        // $data_Value['typeofpage'] = $_POST['typeofpage'];
        $feedbackupadate = [
            'status' => 0,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];

        $this->scorm_page_model->deleteFeedbackReply($feedbackupadate, $data_Value['feedbackreplyid']);
        return redirect()->to(base_url() . 'SCORM/Course_builder/review_course/showfeedbackReplies');
    }

    public function qaVerified()
    {
        if ($response =  $this->requireRole(['6', '5', '46', '67', '44', '3'])) {
            return $response;
        }
        helper(['form']);
        $data_Value = [];
        $data_Value['feedbackid'] = $this->request->getVar('feedbackid');
        $data_Value['typeofpage'] = $_POST['typeofpage'];
        $feedbackupadate = [
            'status' => 4,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];

        $this->scorm_page_model->updateFeedbackStatus($feedbackupadate, $data_Value['feedbackid']);

        $newfeedback = [
            'feedback' => 'QA Verified',
            'feedbackid' => $this->request->getVar('feedbackid'),
            'status' => 2,
            'createdby' => session()->get('id_user'),
            'createdon' => time(),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];
        $this->scorm_page_model->addNewFeedbackReply($newfeedback);
        if ($data_Value['typeofpage'] == 2) {
            return redirect()->to(base_url() . 'SCORM/Course_builder/review_course/showfeedback');
        } else {
            return redirect()->to(base_url() . 'SCORM/Course_builder/review_course/showfeedbackReplies');
        }
    }

    public function addfeedbackReplies()
    {

        if ($response =  $this->requireRole(['6', '5', '46', '67', '44', '3'])) {
            return $response;
        }
        helper(['form']);
        $data_Value = [];

        $data_Value['feedbackid'] = $this->request->getVar('feedbackid');
        $data_Value['typeofpage'] = $_POST['typeofpage'];
        $data_Value['course_id'] = $_POST['course_id'];
        $courseStagedata = $this->scorm_page_model->getcoursestage($data_Value['course_id']);


        $feedbackupadate = [
            'status' => isset($_POST['status']) ? $_POST['status'] : '2',
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];

        $this->scorm_page_model->updateFeedbackStatus($feedbackupadate, $data_Value['feedbackid']);

        $newfeedback = [
            'feedback' => $this->request->getVar('feedbackReply'),
            'feedbackid' => $this->request->getVar('feedbackid'),
            'stage' => $courseStagedata[0]['mode'],
            'status' => 1,
            'createdby' => session()->get('id_user'),
            'createdon' => time(),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];
        $this->scorm_page_model->addNewFeedbackReply($newfeedback);
        if ($data_Value['typeofpage'] == 2) {
            return redirect()->to(base_url() . 'SCORM/Course_builder/review_course/showfeedback');
        } else {
            return redirect()->to(base_url() . 'SCORM/Course_builder/review_course/showfeedbackReplies');
        }
    }

    public function addNewfeedback()
    {
        if ($response =  $this->requireRole(['6', '5', '46', '67', '44', '3'])) {
            return $response;
        }
        helper(['form']);
        $data_Value = [];
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else if (isset($_SESSION['tab'])) {
            $data['tab'] = $_SESSION['tab'];
        } else {
            $data['tab'] = 1;
        }

        $data_Value['course_id'] = $_POST['course_id'];
        $data_Value['page_id'] = $_POST['page_id'];
        $courseStagedata = $this->scorm_page_model->getcoursestage($data_Value['course_id']);
        $comment_type = isset($_POST['comment_type']) ? $_POST['comment_type'] : '0';
        $serverity = isset($_POST['serverity']) ? $_POST['serverity'] : '0';
        $comment_category = isset($_POST['serverity']) ? $_POST['serverity'] : '0';
        $newfeedback = [
            'course_id' => $_POST['course_id'],
            'pageid' => $_POST['page_id'],
            'feedback' => $this->request->getVar('feedback_value'),
            'stage' => $courseStagedata[0]['mode'],
            'videotime' => $_POST['videotime'],
            'type' => $this->request->getVar('type'),
            'comment_type' => $comment_type,
            'serverity' => $serverity,
            'comment_category' => $comment_category,
            'status' => 1,
            'createdby' => session()->get('id_user'),
            'createdon' => time(),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];

        $result = $this->scorm_page_model->addNewFeedback($newfeedback);
        if (isset($result['feedbackid'])) {
            if (!is_dir(FCPATH . 'assets/assets/uploads/feedback_attachment/' . $data_Value['course_id'])) {
                mkdir('assets/assets/uploads/feedback_attachment/' . $data_Value['course_id'], 0777, true);
            }
            // print_r($_POST['img_val']);
            // // exit();
            if (!empty($_POST['img_val'])) {
                $filteredData = substr($_POST['img_val'], strpos($_POST['img_val'], ",") + 1);
                $filename = base64_decode($filteredData);
                $filepath = 'assets/assets/uploads/feedback_attachment/' . $data_Value['course_id'] . '/' . $result['feedbackid'] . '.jpg';
                file_put_contents($filepath, $filename);
                $newdata = [
                    'attchment' => $result['feedbackid'] . '.jpg',
                ];
                $this->scorm_page_model->updatefeedbackAttachment($newdata, $result['feedbackid']);
            }
            if ($file = $this->request->getFile('file')) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $filename_x = $file->getName();
                    if (file_exists(FCPATH . 'assets/assets/uploads/feedback_attachment/' . $data_Value['course_id'] . "/" . $filename_x)) {
                        session()->setFlashdata('error', $filename_x . ' already exists.!');
                    } else {
                        if ($file->move(FCPATH . 'assets/assets/uploads/feedback_attachment/' . $data_Value['course_id'], $filename_x)) {
                            $filepath = FCPATH . 'assets/assets/uploads/feedback_attachment/' . $data_Value['course_id'] . '/' . $filename_x;
                            $newdata = [
                                'uploaded_file' => $filename_x,
                            ];
                            $this->scorm_page_model->updatefeedbackAttachment($newdata, $result['feedbackid']);
                        }
                    }
                }
            }
        }


        return redirect()->to(base_url('SCORM/Course_builder/review_course/scorm_feedback_launcher/' . $data_Value['course_id'] . '/' . $data_Value['page_id'] . '/1'));

        return json_encode($result);
    }
    public function addreplyfeedback()
    {
        if ($response =  $this->requireRole(['6', '5', '46', '67', '44', '3'])) {
            return $response;
        }
        helper(['form']);
        $data_Value = [];
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else if (isset($_SESSION['tab'])) {
            $data['tab'] = $_SESSION['tab'];
        } else {
            $data['tab'] = 1;
        }
        $data_Value['course_id'] = $_POST['course_id'];
        $data_Value['page_id'] = $_POST['page_id'];
        $courseStagedata = $this->scorm_page_model->getcoursestage($data_Value['course_id']);

        $newfeedback = [
            'feedbackid' => $_POST['feedbackid'],
            'feedback' => $_POST['feedback'],
            'stage' => $courseStagedata[0]['mode'],
            'status' => 1,
            'createdby' => session()->get('id_user'),
            'createdon' => time(),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];
        $result = $this->scorm_page_model->addreplyfeedback($newfeedback);
        if (isset($_POST['status'])) {
            if ($_POST['status'] == 6 || $_POST['status'] == 2) {
                $feedbackupadate = [
                    'status' => isset($_POST['status']) ? $_POST['status'] : 1,
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time()
                ];
                $this->scorm_page_model->updateFeedbackStatus($feedbackupadate, $_POST['feedbackid']);
            }
        }
        return json_encode($result);
    }
    public function delete_feedback()
    {
        if ($response =  $this->requireRole(['6', '5', '46', '67', '44', '3'])) {
            return $response;
        }
        helper(['form']);
        $data_Value = [];
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else if (isset($_SESSION['tab'])) {
            $data['tab'] = $_SESSION['tab'];
        } else {
            $data['tab'] = 1;
        }
        $data_Value['feedbackid'] = $this->request->getVar('feedbackid');
        $feedbackupadate = [
            'status' => 0,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];

        $result = $this->scorm_page_model->delete_feedback($feedbackupadate, $data_Value['feedbackid']);
        return json_encode($result);
    }
    public function delete_reply()
    {
        if ($response =  $this->requireRole(['6', '5', '46', '67', '44', '3'])) {
            return $response;
        }
        helper(['form']);
        $data_Value = [];
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else if (isset($_SESSION['tab'])) {
            $data['tab'] = $_SESSION['tab'];
        } else {
            $data['tab'] = 1;
        }
        $data_Value['feedbackreplyid'] = $this->request->getVar('feedbackreplyid');
        $feedbackupadate = [
            'status' => 0,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];

        $result = $this->scorm_page_model->delete_reply($feedbackupadate, $data_Value['feedbackreplyid']);
        return json_encode($result);
    }
    public function closed_status()
    {
        if ($response =  $this->requireRole(['6', '5', '46', '67', '44', '3'])) {
            return $response;
        }
        helper(['form']);
        $data_Value = [];
        if (isset($_POST['tab'])) {
            $data['tab'] = $_POST['tab'];
            $_SESSION['tab'] = $data['tab'];
        } else if (isset($_SESSION['tab'])) {
            $data['tab'] = $_SESSION['tab'];
        } else {
            $data['tab'] = 1;
        }
        $data_Value['feedbackid'] = $this->request->getVar('feedbackid');
        $pageupadate = [
            'status' => $this->request->getVar('status'),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];

        $result = $this->scorm_page_model->delete_feedback($pageupadate, $data_Value['feedbackid']);
        return json_encode($result);
    }
    public function feedback_attachment()
    {
        if ($response =  $this->requireRole(['6', '5', '46', '67', '44', '3'])) {
            return $response;
        }
        helper(['form']);
        $data = [];
        $data['course_id'] = $_POST['course_id'];
        $data['page_id'] = $_POST['page_id'];
        $data['feedbackid'] = $_POST['feedbackid'];
        if ($this->request->getPost()) {
            $rules = [
                'file' => 'uploaded[file]|ext_in[file,jpg,png]'
            ];
            if (!$this->validate($rules)) {
                $data['logovalidation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();
                        if (!is_dir(FCPATH . 'assets/assets/uploads/feedback_attachment/' . $data['course_id'])) {
                            mkdir('assets/assets/uploads/feedback_attachment/' . $data['course_id'], 0777, true);
                        }
                        // if (file_exists(FCPATH . 'assets/assets/uploads/feedback_attachment/' . $data['feedbackid'] . "/" . $filename)) {
                        //     session()->setFlashdata('error', $filename . ' '.lang('Messages.Success_0051'));
                        // } else {
                        if ($file->move(FCPATH . 'assets/assets/uploads/feedback_attachment/' . $data['course_id'], $filename)) {
                            $filepath = FCPATH . 'assets/assets/uploads/feedback_attachment/' . $data['course_id'] . '/' . $filename;
                            $newdata = [
                                'uploaded_file' => $filename,
                            ];
                            $result = $this->scorm_page_model->updatefeedbackAttachment($newdata, $data['feedbackid']);
                            if ($result) {
                                return json_encode($result);
                                // return redirect()->to(base_url() . 'User_login/User_login/client_list/client_edit_view');
                            } else {
                                return json_encode($result);
                                // return redirect()->to(base_url() . 'User_login/User_login/client_list/client_edit_view');
                            }
                        }
                        // }
                    }
                }
            }
        }
    }
}
