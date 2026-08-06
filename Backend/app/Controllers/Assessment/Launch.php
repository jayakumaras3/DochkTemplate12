<?php

namespace App\Controllers\Assessment;

use App\Controllers\BaseController;

use App\Models\Assessment\Assessment_training_model;
use App\Models\SCORM\Scorm_lanuch_model;
use App\Models\XAPI\API_model;

#[\AllowDynamicProperties]
class Launch extends BaseController
{
    private $db;

    public function __construct()
    {

        $this->assessment_training_model = new Assessment_training_model();
        $this->scorm_lanuch_model = new Scorm_lanuch_model();
        $this->API_model = new API_model();
    }

    public function index()
    {
        $data = [];
        $type = 8;
        $data['scourse_id'] = $_GET['course_id'];
        $_SESSION['globalval']['qarray'] = 1;
        $user = session()->get('id_user');
        $data['quiz_attempts'] = $this->assessment_training_model->get_quiz_settings($data['scourse_id'], 24);
        $data['current_attempts'] = $this->assessment_training_model->getcurrentattempt($data['scourse_id'], $user);
        if ($data['current_attempts'][0]['attempt'] < $data['quiz_attempts'][0]['value']) {
            $data['addassessmentuserdetails'] = $this->scorm_lanuch_model->addscormuserdetails($data['scourse_id']);
        }
        $data['quiz_description'] = $this->assessment_training_model->get_description($data['scourse_id']);
        $data['quiz_pass'] = $this->assessment_training_model->get_quiz_settings($data['scourse_id'], 23);
        // $data['quiz_attempts'] = $this->assessment_training_model->get_quiz_settings($data['scourse_id'], 24);
        $data['quiz_lock'] = $this->assessment_training_model->get_quiz_settings($data['scourse_id'], 3);


        // print_r($data['current_attempts']);
        // exit();
        echo view('assessment/launch_view', $data);
    }
    public function start_assessment()
    {
        $data = [];
        $type = 8;
        // print_r($_POST);
        // exit();
        $client_id = session()->get('client');
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] =  $data['scourse_id'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        }
        if (isset($_POST['q_num'])) {
            $data['q_num'] = $_POST['q_num'];
            $_SESSION['q_num'] =  $data['q_num'];
        } else if (isset($_GET['q_num'])) {
            $data['q_num'] = $_GET['q_num'];
        } else if (isset($_SESSION['q_num'])) {
            $data['q_num'] = $_SESSION['q_num'];
        }
        $q_num = $data['q_num'];
        $user = session()->get('id_user');
        $current_attempt = $this->assessment_training_model->getcurrentattempt($data['scourse_id'], $user);
        $current_attemptdata = $this->assessment_training_model->getcurrentattemptdata($data['scourse_id'], $user);
        // print_r($current_attempt);
        $attempt = $current_attempt[0]['attempt'];
        $future_attempt = $attempt + 1;
        $user_assign_id = $current_attemptdata[0]['user_assign_id'];
        $sc_uid = $current_attemptdata[0]['sc_uid'];
        if ($q_num == 0) {
            $remove_grace = array(
                'grace' => 0
            );
            $this->assessment_training_model->increase_attempt($remove_grace, $user_assign_id);

            $checkQuestionRandomRequired = $this->assessment_training_model->get_quiz_settings($data['scourse_id'], 1);

            if ($checkQuestionRandomRequired[0]['value'] == 'Enabled') {

                $data['question_array'] = $this->assessment_training_model->get_question_array($data['scourse_id'], 1);
            } else {
                $data['question_array'] = $this->assessment_training_model->get_question_array($data['scourse_id'], 2);
            }

            $data['total_questions'] = count($data['question_array']);
            $countval = count($data['question_array']);

            $testarray = array();
            for ($i = 0; $i < $countval; $i++) {
                array_push($testarray, $data['question_array'][$i]['q_id']);
            }
            $_SESSION['globalval']['qarray'] = $testarray;
        } else {
            //All user selections are saved to DB.
            // $quiz_option = $this->request->getVar('quiz_option');
            // $q_id =  $this->request->getVar('q_id');
            if (isset($_POST['scourse_id'])) {
                $data['scourse_id'] = $_POST['scourse_id'];
                $_SESSION['scourse_id'] =  $data['scourse_id'];
                $data['quiz_option'] = $_POST['quiz_option'];
                $_SESSION['quiz_option'] =  $data['quiz_option'];
                $data['q_id'] = $_POST['q_id'];
                $_SESSION['q_id'] =  $data['q_id'];
            } else if (isset($_GET['scourse_id'])) {
                $data['scourse_id'] = $_GET['scourse_id'];
                $data['quiz_option'] = $_GET['quiz_option'];
                $data['q_id'] = $_GET['q_id'];
            } else if (isset($_SESSION['scourse_id'])) {
                $data['scourse_id'] = $_SESSION['scourse_id'];
                $data['quiz_option'] = $_SESSION['quiz_option'];
                $data['q_id'] = $_SESSION['q_id'];
            }
            // print_r("tt");
            // exit();
            $question_value = $this->assessment_training_model->get_current_question($data['q_id']);

            $option_values = $this->assessment_training_model->get_option_values($data['quiz_option']);
            $istrue = $option_values[0]['truefalse'];
            if ($istrue == 1) {
                $user_scored = $option_values[0]['score'];
            } else {
                $user_scored = 0;
            }
            //Check if record already exists
            $checkifrecordexists = $this->assessment_training_model->checkifexists($data['scourse_id'], $data['q_id'], $future_attempt, $user);
            $data = array(
                'scourse_id' => $data['scourse_id'],
                'question_id' => $data['q_id'],
                'sc_uid' =>  $sc_uid,
                'user_id' => $user,
                'attempt' => $future_attempt,
                'option_selected' => $data['quiz_option'],
                'scored' => $user_scored,
                'client_id' => $client_id,
                'status' => 1,
                'last_updated_by' => $user,
                'last_updated_on' => time()
            );
            if ($checkifrecordexists == FALSE) {
                $this->assessment_training_model->add_question_record($data);
            } else {
                $updateid = $checkifrecordexists[0]['qr_id'];
                $this->assessment_training_model->update_question_record($data, $updateid);
            }
        }
        $data['total_questions'] = count($_SESSION['globalval']['qarray']);
        $data['current_question'] = $q_num + 1;

        if ($data['current_question'] <= $data['total_questions']) {
            $question_id = $_SESSION['globalval']['qarray'][$q_num];
            $data['question_id'] = $question_id;

            $data['checkfileexists'] = $this->assessment_training_model->check_image_file_exists($question_id, 1);

            $data['checkpdf'] = $this->assessment_training_model->check_image_file_exists($question_id, 2);
            $data['question'] = $this->assessment_training_model->geteditquestiondetails($question_id);

            $checkOptionRandomRequired = $this->assessment_training_model->get_quiz_settings($data['scourse_id'], 2);

            if ($checkOptionRandomRequired[0]['value'] == 'Enabled') {
                // print_r($checkOptionRandomRequired[0]['value']);
                // exit();
                $data['option_array'] = $this->assessment_training_model->get_option_array($question_id, 1);
            } else {
                $data['option_array'] = $this->assessment_training_model->get_option_array($question_id, 2);
            }
            echo view('assessment/assessment_processor_view', $data);
        } else {

            $data['scourse_id'] = $data['scourse_id'];
            $data['attempt'] = $future_attempt;
            $data['quiz_pass'] = $this->assessment_training_model->get_quiz_settings($data['scourse_id'], 23);
            $data['quiz_max_scored'] = $this->assessment_training_model->get_quiz_settings($data['scourse_id'], 21);
            $data['resultval'] = $this->assessment_training_model->calculate_result($data['scourse_id'], $future_attempt, $user);
            $data['maxscoreoption'] = $this->assessment_training_model->calculate_maxscoreoption($data['scourse_id'], $future_attempt, $user);

            $data['cat_details'] = $this->assessment_training_model->calculate_category($data['scourse_id'], $future_attempt, $user);
            $percent_scored = round($data['resultval'][0]['scoreval'] / $data['maxscoreoption'][0]['maxs'] * 100, 2);
            // $percent_scored = '0';
            $required_score =  $data['quiz_pass'][0]['value'];
            if ($percent_scored >= $required_score) {
                $pass = 'passed';
            } else {
                $pass = 'failed';
            }
            $message = $pass . ' with ' . $percent_scored . '%. Required ' . $required_score . '%';
            $update_status = array(
                // 'attempt' => $future_attempt,
                'lesson_status' => $pass,
                'raw' => $data['resultval'][0]['scoreval'],
                'max' => $data['maxscoreoption'][0]['maxs'],
                'min' => $required_score
            );
            $this->assessment_training_model->increase_attempt_sc($update_status, $sc_uid);
            echo view('assessment/assessment_result_view', $data);
        }
    }
    public function review_questions()
    {
        if (isset($_POST['scourse_id'])) {
            $data['scourse_id'] = $_POST['scourse_id'];
            $_SESSION['scourse_id'] =  $data['scourse_id'];
        } else if (isset($_GET['scourse_id'])) {
            $data['scourse_id'] = $_GET['scourse_id'];
        } else if (isset($_SESSION['scourse_id'])) {
            $data['scourse_id'] = $_SESSION['scourse_id'];
        }
        if (isset($_POST['attempt'])) {
            $data['attempt'] = $_POST['attempt'];
            $_SESSION['attempt'] =  $data['attempt'];
        } else if (isset($_GET['attempt'])) {
            $data['attempt'] = $_GET['attempt'];
        } else if (isset($_SESSION['attempt'])) {
            $data['attempt'] = $_SESSION['attempt'];
        }
        $user = session()->get('id_user');
        $currentquestions = $this->assessment_training_model->getquestionsattempted($data['scourse_id'], $user, $data['attempt']);
        $fulldata = array();
        $i = 0;
        if (!empty($currentquestions)) {
            // echo 'Came here';
            foreach ($currentquestions as $row) {
                $correctansser = $this->assessment_training_model->correctanswer($row['question_id']);
                $correctoptionarray = '';
                if (!empty($correctansser)) {
                    foreach ($correctansser as $row2) {
                        if ($correctoptionarray == '') {
                            $correctoptionarray = $row2['values'];
                        } else {
                            $correctoptionarray = $correctoptionarray . ', ' . $row2['values'];
                        }
                    }
                }

                $fulldata[$i] = array(
                    $row['question_id'],
                    $row['question'],
                    $row['score'],
                    $row['option'],
                    $row['truefalse'],
                    $correctoptionarray
                );
                $i++;
            }
        }
        $data['fulldata'] = $fulldata;
        echo view('assessment/result_detail_view', $data);
    }
}
