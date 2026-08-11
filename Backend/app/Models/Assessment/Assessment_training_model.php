<?php

namespace App\Models\Assessment;

use CodeIgniter\Database\MySQLi\Builder;
use CodeIgniter\Model;

class Assessment_training_model extends Model
{

    public function addquestiondetails($newdata)
    {
        $builder = $this->db->table('assessment_questions');
        $builder->insert($newdata);
        $data['question_id'] = $this->db->insertID();
        // $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getQuestiondata($scourse_id, $page_id)
    {
        $builder = $this->db->table('assessment_questions as q');
        $builder->select('q.*, d.description as categoryname, (SELECT COUNT(*) FROM assessment_options o WHERE o.question_id = q.q_id AND o.status != 0) as option_count');
        $builder->join('scorm_meta_category as d', 'd.sc_mcid = q.category', 'left');
        // $builder->join('assessment_options as o', 'o.question_id = q.q_id', 'left');
        // $builder->join('assessment_options as o1', 'o1.question_id = q.q_id and o1.truefalse = 1', 'left');
        $builder->where('q.page_id', $page_id);
        $builder->where('q.scourse_id', $scourse_id);
        $builder->where('q.status', 1);
        $data = $builder->get()->getResultArray();
        // print_r($builder);
        // exit();
        return $data;
    }
    function getpagetype($page_id)
    {
        $builder = $this->db->table('page as p');
        $builder->select('p.*');
        $builder->where('p.page_id', $page_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getQuestionDetails_byQID($page_id, $scourse_id)
    {
        $builder = $this->db->table('assessment_questions as q');
        $builder->select('q.*');
        $builder->where('q.page_id', $page_id);
        $builder->where('q.scourse_id', $scourse_id);
        $builder->where('q.status', 1);
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    public function getQuestionDetails($page_id)
    {
        $builder = $this->db->table('assessment_questions as q');
        $builder->select('q.*');
        $builder->where('q.page_id', $page_id);
        $builder->where('q.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getCoursedetailsforq_id($qa_id)
    {
        $builder = $this->db->table('question_attachments as q');
        $builder->select('q.scourse_id, q.q_id, s.createdon,aq.page_id,q.createdby');
        $builder->join('scorm_courses as s', 's.scourse_id = q.scourse_id', 'left');
        $builder->join('assessment_questions as aq', 'aq.q_id = q.q_id', 'left');
        $builder->where('q.qa_id', $qa_id);
        $builder->where('q.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function geteditquestiondetails($q_id)
    {
        $builder = $this->db->table('assessment_questions as q');
        $builder->select('q.*, d.description as categoryname');
        $builder->join('scorm_meta_category as d', 'd.sc_mcid = q.category', 'left');
        $builder->where('q.q_id', $q_id);
        $builder->where('q.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function updatequestiondetails($newdata, $q_id)
    {
        $builder = $this->db->table('assessment_questions as q');
        $builder->where('q.q_id', $q_id);
        $builder->update($newdata);
        return true;
    }
    public function addoptiondata($newdata)
    {
        $builder = $this->db->table('assessment_options');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        return true;
    }
    public function getoptiondaata($q_id)
    {
        $builder = $this->db->table('assessment_options as o');
        $builder->select('o.*');
        $builder->where('o.question_id', $q_id);
        $builder->where('o.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getoptiondetails($o_id)
    {
        $builder = $this->db->table('assessment_options as o');
        $builder->select('o.*');
        $builder->where('o.o_id', $o_id);
        $builder->where('o.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function updateoptiondata($newdata, $o_id)
    {
        $builder = $this->db->table('assessment_options as o');
        $builder->where('o.o_id', $o_id);
        $builder->update($newdata);
        return true;
    }
    public function deleteoptiondata($newdata, $o_id)
    {
        $builder = $this->db->table('assessment_options as o');
        $builder->where('o.o_id', $o_id);
        $builder->update($newdata);
        // $data = $builder->get()->getResultArray();
        $data['status'] = 'OK';
        // if ($data) {
        //     $data['status'] = 'OK';
        // } else {
        //     $data['status'] = 'error';
        // }
        return $data;
    }
    function copyQuestion($q_id)
    {
        $builder = $this->db->table('assessment_questions as q');
        $builder->select('q.*,o.*');
        $builder->join('assessment_options as o', 'o.question_id = q.q_id and o.status=1', 'inner');
        $builder->where('q.q_id', $q_id);
        $builder->where('q.status', 1);
        $data = $builder->get()->getResultArray();

        if ($data) {
            $scourse_id = $data[0]['scourse_id'];
            $page_id = $data[0]['page_id'];
            $quiz_type = $data[0]['quiz_type'];
            $category = $data[0]['category'];
            $question = $data[0]['question'] . '_copy';
            $score = $data[0]['score'];
            $createdby = session()->get('id_user');
            $createdon = time();
            // echo "<pre>";
            //     print_r($data);
            //     exit();
            $db = \Config\Database::connect();
            $q = "INSERT INTO assessment_questions VALUES ('','$scourse_id','$page_id','$quiz_type','$category','$question','$score','','','','','1', '$createdby','$createdon','0','0','0')";
            if ($db->query($q)) {
                $insert_id = $db->InsertID();
                //$insertdata = [];


                foreach ($data as $eachoptiondata) {
                    //print_r($eachagendadata);
                    // exit();
                    $insertstatus = [
                        'question_id' => $insert_id,
                        'scourse_id' => $eachoptiondata['scourse_id'],
                        'values' => isset($eachoptiondata['values']) ? $eachoptiondata['values'] : '',
                        'score' => isset($eachoptiondata['score']) ? $eachoptiondata['score'] : '',
                        'truefalse' => isset($eachoptiondata['truefalse']) ? $eachoptiondata['truefalse'] : '',
                        'status' => 1,
                       
                        'last_updated_by' => session()->get('id_user'),
                        'last_updated_on' => time()
                    ];
                    $builder = $this->db->table('assessment_options');
                    $builder->insert($insertstatus);
                    $headerdata = true;
                }
            }
            return $headerdata;
        } else {

            $builder = $this->db->table('assessment_questions as q');
            $builder->select('q.*');
            //$builder->join('meeting_agenda as ma', 'ma.fk_id_m = m.id_m and ma.status=1', 'inner');
            $builder->where('q.q_id', $q_id);
            $builder->where('q.status', 1);
            $data = $builder->get()->getResultArray();
            if ($data) {
                // print_r($data);
                // exit();
                $scourse_id = $data[0]['scourse_id'];
                $page_id = $data[0]['page_id'];
                $quiz_type = $data[0]['quiz_type'];
                $category = $data[0]['category'];
                $question = $data[0]['question'] . '_copy';
                $score = $data[0]['score'];
                $createdby = session()->get('id_user');
                $createdon = time();
                $db = \Config\Database::connect();
                $q = "INSERT INTO assessment_questions VALUES ('','$scourse_id','$page_id','$quiz_type','$category','$question','$score','','','','','1', '$createdby','$createdon','0','0','0')";
                if ($db->query($q)) {
                    return $q;
                }
            }
        }
    }
    function copyQuestionBank($q_id, $scourse_id)
    {
        $builder = $this->db->table('assessment_questions as q');
        $builder->select('q.*,o.*');
        $builder->join('assessment_options as o', 'o.question_id = q.q_id and o.status=1', 'inner');
        $builder->where('q.q_id', $q_id);
        $builder->where('q.status', 1);
        $data = $builder->get()->getResultArray();
        // echo "<pre>";
        // print_r($data);
        // exit();
        if ($data) {
            $scourse_id = $scourse_id;
            $quiz_type = $data[0]['quiz_type'];
            $page_id = $data[0]['page_id'];
            $category = $data[0]['category'];
            $question = $data[0]['question'];
            $score = $data[0]['score'];
            $createdby = session()->get('id_user');
            $createdon = time();
            $db = \Config\Database::connect();
            $q = "INSERT INTO assessment_questions VALUES ('','$scourse_id','$page_id','$quiz_type','$category','$question','$score','','','','1', '$createdby','$createdon','','','','','')";
            if ($db->query($q)) {
                $insert_id = $db->InsertID();
                //$insertdata = [];

                foreach ($data as $eachoptiondata) {
                    //print_r($eachagendadata);
                    // exit();
                    $insertstatus = [
                        'question_id' => $insert_id,
                        'scourse_id' => $scourse_id,
                        'values' => isset($eachoptiondata['values']) ? $eachoptiondata['values'] : '',
                        'score' => isset($eachoptiondata['score']) ? $eachoptiondata['score'] : '',
                        'truefalse' => isset($eachoptiondata['truefalse']) ? $eachoptiondata['truefalse'] : '',
                        'status' => 1,
                       
                        'last_updated_by' => session()->get('id_user'),
                        'last_updated_on' => time()
                    ];
                    $db = \Config\Database::connect();
                    $builder = $this->db->table('assessment_options');
                    $builder->insert($insertstatus);
                    $headerdata = true;
                }
            }
            return $headerdata;
        } else {
            $builder = $this->db->table('assessment_questions as q');
            $builder->select('q.*');
            //$builder->join('meeting_agenda as ma', 'ma.fk_id_m = m.id_m and ma.status=1', 'inner');
            $builder->where('q.q_id', $q_id);
            $builder->where('q.status', 1);
            $data = $builder->get()->getResultArray();
            if ($data) {
                $scourse_id = $scourse_id;
                $quiz_type = $data[0]['quiz_type'];
                $category = $data[0]['category'];
                $question = $data[0]['question'];
                $score = $data[0]['score'];
                $createdby = session()->get('id_user');
                $createdon = time();
                $db = \Config\Database::connect();
                $q = "INSERT INTO assessment_questions VALUES ('','$scourse_id','$quiz_type','$category','$question','$score','1', '$createdby','$createdon','0','0','0')";
                if ($db->query($q)) {
                    return $q;
                }
            }
        }
    }
    function getQuestionbankdata($scourse_id, $typeval)
    {
        $builder = $this->db->table('scorm_courses as s');
        $builder->select('q.*,s.*');
        $builder->join('assign_meta_category as a', 'a.fk_sc_mcid =s.scourse_id and a.status=1', 'left');
        $builder->join('assessment_questions as q', 'q.scourse_id = a.fk_sc_mcid and q.status=1', 'inner');
        $builder->where('s.type', $typeval);
        $builder->where('a.fk_scourse_id', $scourse_id);
        $builder->where('s.status', 1);
        $data = $builder->get()->getResultArray();
        // print_r($builder);
        // exit();
        return $data;
    }
    function getSuspendData($course_id, $user_id)
    {
        $builder = $this->db->table('scorm_user_details as sud');
        $builder->select('sud.suspend_data, sud.sc_uid');
        $builder->where('sud.course_id', $course_id);
        $builder->where('sud.student_id', $user_id);
        $builder->orderBy("sud.sc_uid", "desc");
        $builder->orderBy("sud.status", 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getQuestionList($course_id, $page_id, $TotalQuestions)
    {
        $builder = $this->db->table('assessment_questions as aq');
        $builder->select('aq.q_id');
        $builder->where('aq.scourse_id', $course_id);
        $builder->where('aq.page_id', $page_id);
        $builder->where('aq.status', 1);
        if (isset($TotalQuestions) && $TotalQuestions != '') {
            $builder->limit($TotalQuestions);
        }
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function is_Correct_Singe_Choice($option_id)
    {
        $builder = $this->db->table('assessment_options as ao');
        $builder->select('ao.truefalse');
        $builder->where('ao.o_id', $option_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getAttempt($sc_uid)
    {
        $builder = $this->db->table('scorm_assessment_details as sad');
        $builder->select('sad.attempts_id');
        $builder->where('sad.scid', $sc_uid);
        $builder->where('sad.status', 1);
        $builder->orderBy("sad.sad", "desc");
        $builder->limit(1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getQuestionForQuiz($q_id)
    {
        $builder = $this->db->table('assessment_questions as aq');
        $builder->select('aq.*');
        $builder->where('aq.q_id', $q_id);
        $builder->where('aq.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getResultValue($attempts_id, $scid)
    {
        $builder = $this->db->table('scorm_assessment_details as sad');
        $builder->select('sad.*');
        $builder->where('sad.scid', $scid);
        $builder->where('sad.attempts_id', $attempts_id);
        $builder->where('sad.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getOptionsforQuestion($q_id)
    {
        $builder = $this->db->table('assessment_options as ao');
        $builder->select('ao.*');
        $builder->where('ao.question_id', $q_id);
        $builder->where('ao.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function add_record_options($newdata)
    {
        $builder = $this->db->table('scorm_assessment_details');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        return true;
    }
    function addAssessmentimg($newdata)
    {
        $builder = $this->db->table('question_attachments');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        return true;
    }
    function get_question_settings($scourse_id, $page_id)
    {
        $builder = $this->db->table('assessment_settings as a');
        $builder->select('a.*');
        $builder->join('page as p', 'p.page_id = a.page_id', 'left');
        $builder->where('a.scourse_id', $scourse_id);
        $builder->where('a.page_id', $page_id);
        $builder->where('a.status', 1);
        $builder->where('p.status !=', 0);
        $builder->orderBy("a.type", "desc");
        $data = $builder->get()->getResultArray();
        // print_r($data);
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    function get_assessmentCourselevel_settings($scourse_id)
    {
        $builder = $this->db->table('assessment_settings as a');
        $builder->where('a.scourse_id', $scourse_id);
        $builder->where('a.status', 1);
        $builder->orderBy("a.type", "desc");
        $data = $builder->get()->getResultArray();
        // print_r($scourse_id);
        // exit();
        return $data;
    }
    function add_settings($tempdata)
    {
        $builder = $this->db->table('assessment_settings');
        $builder->insert($tempdata);
        return $this->db->insertID();
    }
    function delete_old_settings($settings_id, $change_quiz_settings_inactive)
    {
        $builder = $this->db->table('assessment_settings');
        $builder->where('s_id', $settings_id);
        $builder->update($change_quiz_settings_inactive);
        return true;
    }
    function updateLMSData($newdata, $scid)
    {
        $builder = $this->db->table('scorm_user_details');
        $builder->where('sc_uid', $scid);
        $builder->update($newdata);

        $builder = $this->db->table('scorm_user_details as s');
        $builder->select('s.user_assign_id');
        $builder->where('sc_uid', $scid);
        $builder->where('status', 1);
        $user_assign_iddata = $builder->get()->getResultArray();
        if ($newdata['lesson_status'] == 'passed') {
            $builder = $this->db->table('scorm_users_courses_assigned');
            $builder->set('course_status', 2);
            $builder->where('user_assign_id', $user_assign_iddata[0]['user_assign_id']);
            $builder->where('status', 1);
            $builder->update();
        }
        return true;
    }
    function udpateSuspendData($newdata, $course_id, $user_id)
    {
        $builder = $this->db->table('scorm_user_details');
        $builder->where('course_id', $course_id);
        $builder->where('student_id', $user_id);
        $builder->where('status', 1);
        $builder->update($newdata);
        return true;
    }
    function get_description($scourse_id)
    {
        $builder = $this->db->table('scorm_courses as s');
        $builder->select('s.description,s.course_name,s.objectives');
        $builder->where('s.scourse_id', $scourse_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function get_quiz_settings($scourse_id, $type)
    {
        $builder = $this->db->table('assessment_settings as s');
        $builder->select('s.value');
        $builder->where('s.type', $type);
        $builder->where('s.status', 1);
        $builder->where('s.scourse_id', $scourse_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getcurrentattempt($scourse_id, $id_user)
    {
        $builder = $this->db->table('scorm_users_courses_assigned as su');
        $builder->select('count(sud.user_assign_id) as attempt,su.grace,su.user_assign_id,sud.sc_uid');
        $builder->join('scorm_user_details as sud', 'sud.user_assign_id = su.user_assign_id', 'left');
        $builder->where('su.course_id', $scourse_id);
        $builder->where('su.id_user', $id_user);
        $builder->where('su.status', 1);
        $builder->orderBy('sud.attempt', 'Asc');
        $data = $builder->get()->getResultArray();
        // print_r($data);
        // exit();
        return $data;
    }
    function getcurrentattemptdata($scourse_id, $id_user)
    {
        $builder = $this->db->table('scorm_users_courses_assigned as su');
        $builder->select('su.grace,su.user_assign_id,sud.sc_uid');
        $builder->join('scorm_user_details as sud', 'sud.user_assign_id = su.user_assign_id', 'left');
        $builder->where('su.course_id', $scourse_id);
        $builder->where('su.id_user', $id_user);
        $builder->where('su.status', 1);
        $builder->orderBy('sud.sc_uid', 'DESC');
        $data = $builder->get()->getResultArray();
        // print_r($data);
        // exit();
        return $data;
    }
    public function get_question_array($scourse_id, $randomize)
    {
        $builder = $this->db->table('assessment_questions as q');
        $builder->select('q.q_id');
        $builder->where('q.status', 1);
        if ($randomize == 1) {
            $builder->orderBy('q.q_id', 'RANDOM');
        }
        $builder->where('q.scourse_id', $scourse_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function check_image_file_exists($quiz_id, $type)
    {
        $builder = $this->db->table('question_attachments');
        $builder->select('qa_id, doc_name');
        $builder->where('q_id', $quiz_id);
        $builder->where('type', $type);
        $builder->where('status', 1);
        $builder->orderBy('qa_id', 'desc');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function get_option_array($quiz_id, $randomize)
    {
        $builder = $this->db->table('assessment_options as s');
        $builder->select('s.o_id, s.values');
        $builder->where('s.status', 1);
        if ($randomize == 1) {
            $builder->orderBy('s.o_id', 'RANDOM');
        }
        $builder->where('s.question_id', $quiz_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function get_current_question($q_id)
    {
        $builder = $this->db->table('assessment_questions as s');
        $builder->select('s.*');
        $builder->where('s.q_id', $q_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function get_option_values($option_id)
    {
        $builder = $this->db->table('assessment_options as s');
        $builder->select('s.truefalse,score');
        $builder->where('s.o_id', $option_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function increase_attempt($data_attempt, $user_assign_id)
    {
        $builder = $this->db->table('scorm_users_courses_assigned');
        $builder->where('user_assign_id', $user_assign_id);
        $builder->update($data_attempt);
        return true;
    }
    public function increase_attempt_sc($data_attempt, $sc_uid)
    {
        $builder = $this->db->table('scorm_user_details');
        $builder->where('sc_uid', $sc_uid);
        $builder->update($data_attempt);
        return true;
    }
    public function checkifexists($scourse_id, $question_id, $attempt, $user)
    {
        $builder = $this->db->table('assessment_question_report as qr');
        $builder->select('qr.*');
        $builder->where('scourse_id', $scourse_id);
        $builder->where('question_id', $question_id);
        $builder->where('attempt', $attempt);
        $builder->where('user_id', $user);
        $builder->where('status', 1);
        $data = $builder->get()->getResultArray();
        if (!empty($data)) {
            return $data;
        } else {
            return FALSE;
        }
    }
    public function add_question_record($data)
    {
        $builder = $this->db->table('assessment_question_report');
        $builder->insert($data);
        // $data = $builder->get()->getResultArray();
        return true;
    }
    public function update_question_record($data, $updateid)
    {
        $builder = $this->db->table('assessment_question_report');
        $builder->where('qr_id', $updateid);
        $builder->update($data);
        return true;
    }
    public function calculate_result($scourse_id, $attempt, $user)
    {
        $builder = $this->db->table('assessment_question_report as q');
        $builder->select('SUM(q.scored) as scoreval, count(q.qr_id) as numrecords');
        $builder->where('q.scourse_id', $scourse_id);
        $builder->where('q.attempt', $attempt);
        $builder->where('q.user_id', $user);
        $builder->where('q.status', 1);
        $data = $builder->get()->getResultArray();
        // print_r($builder);
        return $data;
    }
    public function calculate_maxscoreoption($scourse_id, $attempt, $user)
    {
        $builder = $this->db->table('assessment_options as ao');
        $builder->select('SUM(ao.score) as maxs');
        $builder->where('ao.scourse_id', $scourse_id);
        $builder->where('ao.status', 1);
        $data = $builder->get()->getResultArray();

        return $data;
    }


    public function calculate_category($scourse_id, $attempt, $user)
    {
        $builder = $this->db->table('assessment_question_report as qr');
        $builder->select("GROUP_CONCAT(DISTINCT(sm.description) ORDER BY sm.description ASC SEPARATOR ', ')  as cat");
        $builder->join('assessment_questions as qq', 'qq.q_id = qr.question_id', 'left');
        $builder->join('scorm_meta_category as sm', 'sm.sc_mcid = qq.category', 'left');
        $builder->where('qr.scourse_id', $scourse_id);
        $builder->where('qr.attempt', $attempt);
        $builder->where('qr.user_id', $user);
        $builder->where('qr.status', 1);
        $builder->where('qr.scored', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getquestionsattempted($scourse_id, $user, $attempt)
    {
        $builder = $this->db->table('assessment_question_report as qr');
        $builder->select('qq.q_id as question_id, qq.question as question, qq.score as score, 
        qo.values as option, qo.truefalse as truefalse');
        $builder->join('assessment_questions as qq', 'qq.q_id = qr.question_id', 'LEFT');
        $builder->join('assessment_options as qo', 'qo.o_id = qr.option_selected', 'LEFT');
        $builder->where('qr.scourse_id', $scourse_id);
        $builder->where('qr.attempt', $attempt);
        $builder->where('qr.user_id', $user);
        $builder->where('qr.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function correctanswer($quizid)
    {
        $builder = $this->db->table('assessment_options');
        $builder->select('values');
        $builder->where('question_id', $quizid);
        $builder->where('status', 1);
        $builder->where('truefalse', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function del_sim_user($user_assign_id, $data)
    {
        $builder = $this->db->table('scorm_users_courses_assigned');
        $builder->where('user_assign_id', $user_assign_id);
        $builder->update($data);
        return true;
    }
    public function getQuestionAnswerdata($page_id)
    {
        $builder = $this->db->table('assessment_questions as s');
        $builder->select('s.question,ao.values,ao.truefalse,s.correct,s.incorrect,s.incorrect2,s.noAttempts,s.quiz_type');
        $builder->join('assessment_options as ao', 'ao.question_id = s.q_id and ao.status != 0', 'left');
        // $builder->groupby('ao.question_id');
        $builder->where('s.page_id', $page_id);
        $builder->where('s.status', 1);
        $builder->orderBy('ao.o_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getexportQuestionAnswerdata($page_id)
    {
        $builder = $this->db->table('assessment_questions as s');
        $builder->select('s.q_id,s.question,ao.o_id,ao.values,ao.truefalse,s.correct,s.incorrect,s.noAttempts,s.quiz_type,f.feedback');
        $builder->join('assessment_options as ao', 'ao.question_id = s.q_id and ao.status = 1', 'left');
        $builder->join('feedback as f', 'f.course_id = s.scourse_id and f.pageid = s.page_id', 'left');
        // $builder->groupby('ao.question_id');
        $builder->where('s.page_id', $page_id);
        $builder->where('s.status', 1);
        $builder->orderBy('s.q_id');
        $builder->orderBy('ao.o_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function getAttemptsofCourse($scourse_id)
    {
        $builder = $this->db->table('assessment_settings');
        $builder->select('value as attempts');
        $builder->where('scourse_id', $scourse_id);
        $builder->where('type', 24);
        $builder->where('status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getassessment_settings($scourse_id, $page_id, $type)
    {
        $builder = $this->db->table('assessment_settings as s');
        $builder->select('s.value');
        $builder->where('scourse_id', $scourse_id);
        $builder->where('page_id', $page_id);
        $builder->where('type', $type);
        $builder->where('s.status', 1);
        $builder->orderby('s.s_id', 'Desc');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getpagequizquestion($scourse_id, $page_id)
    {
        // $builder = $this->db->table('assessment_questions as q');
        // $builder->select('q.q_id,q.question,q.quiz_type,o.values as option,o.truefalse as correct');
        // $builder->join('assessment_options as o', 'o.question_id = q.q_id and o.status =1', 'left');
        // $builder->where('q.page_id', $page_id);
        // $builder->where('q.status', 1);
        // $data = $builder->get()->getResultArray();
        // return  $data;
        $builder = $this->db->table('assessment_questions as q');
        $builder->select('
             q.question,
            CASE 
                WHEN (q.quiz_type = 112) THEN "single" 
                ELSE "multiple" 
            END AS type, 
            GROUP_CONCAT(o.values ORDER BY o.o_id SEPARATOR "|") AS options,  
            GROUP_CONCAT(o.truefalse ORDER BY o.o_id SEPARATOR "|") AS correct,
            (SELECT concat("../../../../assets/Quiz/' . $page_id . '/assessment_image/",qa.doc_name) 
            FROM question_attachments as qa 
            WHERE qa.q_id = q.q_id AND qa.type = 1 AND qa.status != 0 group by qa_id order by qa_id Desc
            LIMIT 1) AS images, 
            (SELECT concat("../../../../assets/Quiz/' . $page_id . '/assessment_video/",qa1.doc_name)
            FROM question_attachments as qa1 
            WHERE qa1.q_id = q.q_id AND qa1.type = 3 AND qa1.status != 0  group by qa_id order by qa_id Desc
            LIMIT 1) AS video');
        $builder->join('assessment_options as o', 'o.question_id = q.q_id AND o.status = 1', 'left');
        $builder->where('q.page_id', $page_id);
        $builder->where('q.scourse_id', $scourse_id);
        $builder->where('q.status !=', 0);
        $builder->groupBy('q.q_id');  // Group by question to ensure all options are concatenated for one question

        $data = $builder->get()->getResultArray();
        // echo "<pre>";
        // print_r($data);
        // exit();
        return $data;
    }
    public function getpagequestion($page_id)
    {
        $builder = $this->db->table('assessment_questions as s');
        $builder->where('s.page_id', $page_id);
        $builder->where('s.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getquestionoptions($q_id)
    {
        $builder = $this->db->table('assessment_options as s');
        $builder->where('s.question_id', $q_id);
        $builder->where('s.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function updateoptioneditableformat($value, $column, $id, $question_id, $quiz_type)
    {
        // A question needs at least one option to be answerable, and at least one of its
        // options must stay marked correct - refuse changes that would leave either at zero.
        // Checked here (rather than only client-side) since this is the authoritative save path.
        if ($column === 'status' && (int) $value === 0) {
            $activeCount = $this->db->table('assessment_options')
                ->where('question_id', $question_id)
                ->where('status !=', 0)
                ->countAllResults();
            if ($activeCount <= 1) {
                $data['status'] = 'A question must have at least one option - add another before deleting this one.';
                return $data;
            }

            $otherCorrectCount = $this->db->table('assessment_options')
                ->where('question_id', $question_id)
                ->where('o_id !=', $id)
                ->where('status !=', 0)
                ->where('truefalse', 1)
                ->countAllResults();
            if ($otherCorrectCount === 0) {
                $data['status'] = 'A question must have at least one correct option - mark another option correct before deleting this one.';
                return $data;
            }
        }

        if ($column === 'truefalse' && (int) $value === 2) {
            $otherCorrectCount = $this->db->table('assessment_options')
                ->where('question_id', $question_id)
                ->where('o_id !=', $id)
                ->where('status !=', 0)
                ->where('truefalse', 1)
                ->countAllResults();
            if ($otherCorrectCount === 0) {
                $data['status'] = 'A question must have at least one correct option - mark another option correct before changing this one to wrong.';
                return $data;
            }
        }

        $builder = $this->db->table('assessment_options as dtd');
        $builder->set($column, $value);
        $builder->where('dtd.o_id', $id);
        $builder->update();

        if ($column == 'truefalse' && (int) $value === 1 && $quiz_type == '112') {
            $builder = $this->db->table('assessment_options as dtd');
            $builder->set('truefalse', 2);
            $builder->where('dtd.o_id !=', $id);
            $builder->where('dtd.question_id ', $question_id);
            $builder->update();
        }

        // $data = $builder->get()->getResultArray();
        $data['status'] = "OK";
        // if (!empty($data)) {
        //     $data['status'] = "OK";
        // } else {
        //     $data['status'] = "Error";
        // }
        return $data;
    }
    public function addoptioneditableformat($value, $column, $id, $scourse_id, $question_id, $pageType = null)
    {
        $newdata = [
            'scourse_id' => $scourse_id,
            'question_id' => $question_id,
            $column => $value,
            'status' => 1,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];

        // For a brand-new SCQ question, default its first option to "Correct" so the
        // question isn't left with zero correct answers until the user manually picks one -
        // every option after that (and edits to this one) still default/stay "Wrong" as
        // before, matching the existing toggle behaviour.
        if ($column === 'values' && (string) $pageType === '5') {
            $existingCount = $this->db->table('assessment_options')
                ->where('question_id', $question_id)
                ->where('status !=', 0)
                ->countAllResults();
            if ($existingCount === 0) {
                $newdata['truefalse'] = 1;
            }
        }

        $builder = $this->db->table('assessment_options');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        $data['status'] = "OK";
        // if (!empty($data)) {
        //     $data['status'] = "OK";
        // } else {
        //     $data['status'] = "Error";
        // }
        return $data;
    }
    function importQuestionsdetails($sheetData, $scourse_id, $page_id, $columnCount)
    {
        // print_r($sheetData[0][0]);
        // exit();
        if (trim($sheetData[0][0]) == 'Question') {
            $j = 0;
            foreach ($sheetData as $Row) {
                // print_r($scourse_id.'-'.$page_id);
                // exit();

                if (trim($Row[0]) == 'Question') {
                    continue;
                }
                $j = $j + 1;
                if (isset($Row[0]) && isset($Row[1]) && isset($Row[3]) && isset($Row[5])) {

                    $question = "";
                    if (isset($Row[0])) {
                        $question = trim($Row[0]);
                    }
                    $type = "";
                    if (isset($Row[1])) {
                        $type = trim($Row[1]);
                        if (strtoupper($type) == 'SCQ') {
                            $type = '112';
                        } elseif (strtoupper($type) == 'MCQ') {
                            $type = '115';
                        } else {
                            $type = '';
                            // $type = '112';
                        }
                    }
                    $feedback = "";
                    if (isset($Row[2])) {
                        $feedback = trim($Row[2]);
                        if ($feedback == 'N/A') {
                            $feedback = '';
                        } else {
                            $feedback = trim($Row[2]);
                        }
                    }

                    $insertquestiondata = [
                        'scourse_id' => $scourse_id,
                        'page_id' => $page_id,
                        'question' => $question,
                        'quiz_type' => $type,
                        'correct' => $feedback,
                        'status' => 1,
                        'last_updated_by' => session()->get('id_user'),
                        'last_updated_on' => time()
                    ];
                    // print_r($insertquestiondata);
                    // exit();
                    $db = \Config\Database::connect();
                    $builder = $this->db->table('assessment_questions');
                    $builder->insert($insertquestiondata);
                    $insert_id = $db->insertID();
                    if (isset($insert_id)) {
                        $q_id = $insert_id;
                    }

                    $i = 3;

                    for ($i; $i < $columnCount; $i += 2) {
                        $values = "";
                        $correct = "";
                        // print_r($i . "<br/>");
                        if (isset($Row[$i])) {

                            $values = trim($Row[$i]);
                            if ($values == 'N/A') {
                                continue;
                            }      // Column for Option_id
                            $correct = trim($Row[$i + 1]);       // Column for values
                            // Column for correct value

                            $truefalse = (strtolower($correct) == 'true') ? '1' : '0';
                            if (isset($q_id) && (!empty($values))) {
                                $insertoptiondata = [
                                    'scourse_id' => $scourse_id,
                                    'question_id' => $q_id,
                                    'values' => $values,
                                    'truefalse' => $truefalse,
                                    'status' => '1',
                                    'last_updated_by' => session()->get('id_user'),
                                    'last_updated_on' => time()
                                ];
                                // print_r($insertoptiondata);
                                // exit();
                                $db = \Config\Database::connect();
                                $builder = $this->db->table('assessment_options');
                                $builder->insert($insertoptiondata);
                            }
                        }
                    }
                } else {
                    $insertrecordCount = $j - 1;
                    $data['error'] = "Row " . $j . " : don't have properly value, further row excution has been stopped <br/>
                    only" . $insertrecordCount . " : Record imported successfully";
                    return $data;
                }
            }
            $data['success'] = 'Record imported successfully';
            return $data;
        } else {
            $data['error'] = 'Data not found!, Your trying to import wrong Excelsheet';
            return $data;
        }
    }

    function UpdateQuestionsdetails($sheetData, $scourse_id, $page_id, $columnCount)
    {
        if (trim($sheetData[0][0]) == 'Question ID') {
            $j = 0;
            foreach ($sheetData as $Row) {
                $j = $j + 1;
                // print_r()
                if (trim($Row[0]) == 'Question ID') {
                    continue;
                }
                $q_id = "";
                if (isset($Row[0])) {
                    if (isset($Row[0])) {
                        $q_id = trim($Row[0]);

                        $question = "";
                        if (isset($Row[1])) {
                            $question = trim($Row[1]);
                            $type = trim($Row[2]);
                            if (strtoupper($type) == 'SCQ') {
                                $type = '112';
                            } elseif (strtoupper($type) == 'MCQ') {
                                $type = '115';
                            } else {
                                $type = '0';
                            }
                            $feedback = "";
                            if (isset($Row[3])) {
                                $feedback = trim($Row[3]);
                            }

                            // question update
                            $updatequestiondata = [
                                'question' => $question,
                                'quiz_type' => $type,
                                'correct' => $feedback,
                                'last_updated_by' => session()->get('id_user'),
                                'last_updated_on' => time()
                            ];
                            // print_r($insertdata);
                            // exit();
                            $builder = $this->db->table('assessment_questions as q');
                            $builder->where('q.q_id', $q_id);
                            $builder->update($updatequestiondata);
                        }
                    } else {
                        if (isset($Row[1])) {
                            $question = trim($Row[1]);
                            $type = trim($Row[2]);
                            $feedback = trim($Row[3]);

                            if (strtoupper($type) == 'SCQ') {
                                $type = '112';
                            } elseif (strtoupper($type) == 'MCQ') {
                                $type = '115';
                            } else {
                                $type = '0';
                            }

                            $insertquestiondata = [
                                'scourse_id' => $scourse_id,
                                'page_id' => $page_id,
                                'question' => $question,
                                'quiz_type' => $type,
                                'correct' => $feedback,
                                'status' => 1,
                                'last_updated_by' => session()->get('id_user'),
                                'last_updated_on' => time()
                            ];
                            $db = \Config\Database::connect();
                            $builder = $this->db->table('assessment_questions');
                            $builder->insert($insertquestiondata);
                            $insert_id = $db->insertID();
                        }
                    }
                    if (isset($insert_id)) {
                        $q_id = $insert_id;
                    }
                    $i = 4;

                    for ($i; $i < $columnCount; $i += 3) {
                        $Option_id = "";
                        $values = "";
                        $correct = "";
                        // print_r($i . "<br/>");
                        if (isset($Row[$i])) {

                            $Option_id = trim($Row[$i]);        // Column for Option_id
                            $values = trim($Row[$i + 1]);       // Column for values
                            $correct = trim($Row[$i + 2]);      // Column for correct value

                            $truefalse = (strtolower($correct) == 'true') ? '1' : '0';

                            $updateoptiondata = [
                                'values' => $values,
                                'truefalse' => $truefalse,
                                'last_updated_by' => session()->get('id_user'),
                                'last_updated_on' => time()
                            ];

                            // Perform the update query
                            $builder = $this->db->table('assessment_options as o');
                            $builder->where('o.o_id', $Option_id);
                            $builder->update($updateoptiondata);
                        } else {
                            $Option_id = trim($Row[$i]);
                            $values = trim($Row[$i + 1]);       // Column for values
                            $correct = trim($Row[$i + 2]);      // Column for correct value
                            if (isset($q_id) && (!empty($values))) {
                                $truefalse = (strtolower($correct) == 'true') ? '1' : '0';
                                $insertoptiondata = [
                                    'scourse_id' => $scourse_id,
                                    'question_id' => $q_id,
                                    'values' => $values,
                                    'truefalse' => $truefalse,
                                    'status' => 1,
                                    'last_updated_by' => session()->get('id_user'),
                                    'last_updated_on' => time()
                                ];


                                // Perform the update query
                                $builder = $this->db->table('assessment_options');
                                $builder->insert($insertoptiondata);
                            }
                        }
                    }
                } else {
                    $insertrecordCount = $j - 1;
                    $data['error'] = "Row " . $j . " : don't have question ID to Update <br/>
                    only" . $insertrecordCount . " : Record updated successfully";
                    return $data;
                }
                // exit();
            }
        } else {
            $data['error'] = 'Data not found!, Your trying to import wrong Excelsheet';
            return $data;
        }
    }
    function delete_question_attachments($newdata, $qa_id)
    {
        $builder = $this->db->table('question_attachments');
        $builder->where('qa_id', $qa_id);
        $builder->update($newdata);
        // $data = $builder->get()->getResultArray();
        $data['status'] = "OK";
        return $data;
    }
    function getpagestatus($pageid)
    {
        $builder = $this->db->table('page as p');
        $builder->select('p.*');
        $builder->where('p.page_id', $pageid);
        $data = $builder->get()->getResultArray();
        return $data;
    }
}
