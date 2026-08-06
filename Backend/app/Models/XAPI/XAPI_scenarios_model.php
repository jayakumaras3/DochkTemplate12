<?php

namespace App\Models\XAPI;

use CodeIgniter\Model;

class xapi_scenarios_model extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = db_connect(); // Loading database
    }

    public function getAllScenarios($scourse_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('xapi_scenarios as v');
        $builder->select('v.*, u.name as createdby');
        $builder->join('users as u', 'u.id_user = v.createdby', 'left');
        $builder->where('v.status != ', 0);
        $builder->where('v.scourse_id', $scourse_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function getuserDetails($user_assign_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_user_details as v');
        $builder->select('v.*,count(xa.xapi_act_id) as xapiscenariocount,x.scenario_name');
        $builder->join('xapi_activities as xa', 'xa.sc_uid = v.sc_uid and xa.status =1', 'left');
        $builder->join('xapi_scenarios as x', 'x.xs = v.scenario_id and x.status =1', 'left');
        $builder->where('v.status != ', 0);
        $builder->where('v.user_assign_id', $user_assign_id);
        $builder->groupBy('v.sc_uid');
        // $builder->orderBy('xa.xapi_act_id', 'DESC');
        $builder->orderBy('xa.sc_uid', 'DESC');
        $data = $builder->get()->getResultArray();
        // print_r($builder);
        return $data;
    }
    public function getuser_assign_id($course_id, $user_id)
    {
        $builder = $this->db->table('scorm_users_courses_assigned as sca');
        $builder->select('sca.user_assign_id,sc.course_name,s.scenario_name,u.name as username');
        $builder->join('scorm_courses as sc', 'sc.scourse_id = sca.course_id', 'left');
        $builder->join('xapi_scenarios as s', 's.scourse_id = sc.scourse_id', 'left');
        $builder->join('users as u', 'u.id_user = sca.id_user', 'left');
        $builder->where('sca.course_id', $course_id);
        $builder->where('sca.id_user', $user_id);
        $builder->where('sca.status != ', 0);
        $builder->orderBy('sc.scourse_id', 'desc');
        $data = $builder->get()->getResultArray();
        // echo  $this->db->getLastQuery();
        // exit();
        return $data;
    }
    public function createNewScenario($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('xapi_scenarios');
        $builder->insert($newdata);
        $data = $db->insertID();
        return  $data;
    }

    public function getScenarioDetailss($xs)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('xapi_scenarios as sd');
        $builder->select('sd.*');
        $builder->where('sd.xs', $xs);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getScenarioIDs($scourse_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('xapi_scenarios as sd');
        $builder->select('sd.*');
        $builder->where('sd.scourse_id', $scourse_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getScenariosettingsvarible($scenario_id, $input_variable)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('xapi_scenario_input_settings as sd');
        $builder->select('sd.*');
        $builder->where('sd.scenario_id', $scenario_id);
        $builder->where('sd.input_variable', $input_variable);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getOutputVariable($sc_uid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_user_details as sd');
        $builder->select('xov.variable_description, ver.negative_verb as negative_verb, xov.variable_name as variable');
        $builder->where('sd.sc_uid', $sc_uid);
        $builder->where('sd.status', 1);
        $builder->join('scorm_users_courses_assigned as sud', 'sud.user_assign_id = sd.user_assign_id', 'left');
        $builder->join('xapi_output_variables as xov', 'xov.scourse_id = sud.course_id', 'left');
        $builder->join('verbs as ver', 'ver.verb = xov.verb and ver.status =1', 'left');
        $data = $builder->get()->getResultArray();
        //print_r($builder);

        //print_r($data);
        // exit();
        return $data;
    }
    public function getassessmentReport($sc_uid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_user_details as sd');
        $builder->select('aqr.*, aq.*,ao.values');
        $builder->join('scorm_users_courses_assigned as sud', 'sud.user_assign_id = sd.user_assign_id', 'left');
        $builder->join('assessment_question_report as aqr', 'aqr.scourse_id = sud.course_id', 'left');
        $builder->join('assessment_questions as aq', 'aq.q_id = aqr.question_id and aq.status =1', 'left');
        $builder->join('assessment_options as ao', 'ao.o_id  = aqr.option_selected', 'left');
        $builder->where('sd.sc_uid', $sc_uid);
        $builder->where('sd.status', 1);
        $data = $builder->get()->getResultArray();
        //  print_r($builder);

        //print_r($data);
        //   exit();
        return $data;
    }
    public function activityDetails($sc_uid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('xapi_activities as sd');
        $builder->select('sd.*, xov1.variable_description, xov1.feedback, xov1.verb, sd.createdon as createdtime, ver.negative_verb as negative_verb');
        $builder->where('sd.sc_uid', $sc_uid);
        $builder->where('sd.status != 0');
        $builder->orderBy('sd.createdon', 'ASC');
        $builder->join('scorm_user_details as sud', 'sud.sc_uid = sd.sc_uid', 'left');
        $builder->join('scorm_users_courses_assigned as suca', 'suca.user_assign_id = sud.user_assign_id', 'left');
        $builder->join('xapi_output_variables as xov', 'xov.scourse_id = suca.course_id', 'left');
        $builder->join('xapi_output_variables as xov1', 'xov1.variable_name = sd.variable and xov1.status !=0', 'left');
        $builder->join('verbs as ver', 'ver.verb = xov1.verb and ver.status =1', 'left');
        $builder->groupby('sd.xapi_act_id');
        $data = $builder->get()->getResultArray();
        // print_r($builder);
        // exit();
        return $data;
    }

    public function getScenarioSettings($xs)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('xapi_scenario_input_settings as sd');
        $builder->select('sd.*, iv.variable_description, iv.instructions, ivd.text as dvalue');
        $builder->join('xapi_input_variables as iv', 'iv.xiv = sd.input_variable', 'left');
        $builder->join('xapi_input_dropdown_values as ivd', 'ivd.xiv = sd.input_variable and ivd.value = sd.value and ivd.status!=0', 'left');
        $builder->where('sd.scenario_id', $xs);
        $builder->where('sd.status != ', 0);
        $builder->where('iv.status != ', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function getScenarioSettingValue($xsis)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('xapi_scenario_input_settings as sd');
        $builder->select('sd.*, iv.variable_description, iv.instructions,  iv.xiv,ivd.text as dvalue');
        $builder->join('xapi_input_variables as iv', 'iv.xiv = sd.input_variable', 'left');
        $builder->join('xapi_input_dropdown_values as ivd', 'ivd.xiv = sd.input_variable and ivd.value = sd.value and ivd.status!=0', 'left');
        $builder->where('sd.xsis', $xsis);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function updateScenarioDetails($newdata, $xs, $scourse_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('xapi_scenarios as x');
        $builder->where('x.xs', $xs);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        if (!empty($data)) {
            if ($newdata['status'] === '2') {
                $builder = $this->db->table('xapi_scenarios as x');
                $builder->set('x.status', '1');
                $builder->where('x.scourse_id', $scourse_id);
                $builder->where('x.xs !=', $xs);
                $builder->update();
                $data = $builder->get()->getResultArray();
            }
        }
        return  $data;
    }
    public function updateScenarioSettings($newdata, $xsis)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('xapi_scenario_input_settings as x');
        $builder->where('x.xsis', $xsis);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    public function getEnrollmentById($user_assign_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('scorm_users_courses_assigned as x');

        $builder->select('x.user_assign_id, x.id_user as fk_user_id, u.client_id');
        $builder->join('users as u', 'u.id_user = x.id_user', 'left');

        $builder->where('x.user_assign_id', $user_assign_id);
        $builder->where('x.status !=', 2); // not deleted

        return $builder->get()->getRow();
    }
    public function delEnrollment($newdata, $user_assign_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_users_courses_assigned as x');
        $builder->where('x.user_assign_id', $user_assign_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return  true;
    }

    public function deleteEnrollment($newdata, $sc_uid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_user_details as x');
        $builder->where('x.sc_uid', $sc_uid);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        if (!empty($data)) {
            $builder = $this->db->table('xapi_activities');
            $builder->where('sc_uid', $sc_uid);
            $builder->delete();
        }
        return  $data;
    }
    public function deleteAction($newdata, $xapi_act_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('xapi_activities as x');
        $builder->where('x.xapi_act_id', $xapi_act_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return  $data;
    }


    public function setScenarioSettings($newdata_settings)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('xapi_scenario_input_settings');
        $builder->insert($newdata_settings);
        // $data = $builder->get()->getResultArray();
        return  true;
    }

    public function addusertocourses($newdata)
    {
        $db = \Config\Database::connect();

        foreach ($newdata['id_user'] as $id_user) {
            $scorm_users_courses_assigned = [
                'id_user' => $id_user,
                'client_id' => session()->get('client'),
                'course_id' => $newdata['course_id'],
                'scenario_id' => $newdata['scenario_id'],
                'status' => 1,
                'createdby' => $newdata['createdby'],
                'createdon' => time(),
                // 'last_updated_by' =>  session()->get('id_user'),
                // 'last_updated_on' => time()
            ];
            $builder = $this->db->table('scorm_users_courses_assigned');
            $builder->insert($scorm_users_courses_assigned);
            // $data = $builder->get()->getResultArray();
            $data['status'] = "OK";
            // if (!empty($data)) {
            //     $data['status'] = "OK";
            // } else {
            //     $data['status'] = "Error";
            // }
        }
        return  $data;
    }
    function getAssignedCourseUsers($course_id)
    {
        $client_id = session()->get('client');
        // print_r($client_id);
        $builder = $this->db->table('scorm_users_courses_assigned as suca');
        $builder->select('u.id_user,u.name as fullname');
        $builder->join('users as u', 'u.id_user = suca.id_user and u.valid =1', 'left');
        $builder->join('dropdown_users as du', 'du.fk_id_user = u.id_user and du.fk_id_dc = 1 and du.status =1', 'left');
        $builder->where('suca.course_id', $course_id);
        $builder->where('du.fk_id_d =', $client_id);
        $builder->where('suca.status', '1');
        $data = $builder->get()->getResultArray();
        // print_r($data);
        return $data;
    }
    function getassignUserRoletoScenario($course_id, $user_id)
    {
        // $builder = $this->db->table('xapi_scenarios_users_assign as x');
        $builder = $this->db->table('scorm_users_courses_assigned as x');
        $builder->select('x.*');
        $builder->where('x.course_id', $course_id);
        $builder->where('x.id_user', $user_id);
        $builder->where('x.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function assignUserRoletoScenario($newdata)
    {
        // $builder = $this->db->table('xapi_scenarios_users_assign');
        $builder = $this->db->table('scorm_users_courses_assigned');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        // if (!empty($data)) {
        //     $data['status'] = "OK";
        // } else {
        //     $data['status'] = "Error";
        // }
        return true;
    }
    function getAssignedScenarioUsers($xs)
    {
        $client = session()->get('client');
        // print_r($xs);
        // exit();
        // $builder = $this->db->table('xapi_scenarios_users_assign as s');
        $builder = $this->db->table('scorm_users_courses_assigned as s');
        $builder->select('s.*,u.name as fullname');
        $builder->join('users as u', 'u.id_user = s.id_user and u.valid =1', 'left');
        $builder->join('dropdown_users as du', 'du.fk_id_user = u.id_user and du.fk_id_dc = 1 and du.status =1', 'left');
        $builder->where('du.fk_id_d =', $client);
        $builder->where('s.scenario_id =', $xs);
        $builder->where('s.status', '1');
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // // print_r( $data);
        // exit();
        return $data;
    }
    function deleteAssignedScenariouser($newdata, $user_assign_id)
    {
        // $builder = $this->db->table('xapi_scenarios_users_assign');
        $builder = $this->db->table('scorm_users_courses_assigned');
        // $builder->where('user_assign_id', $user_assign_id);
        $builder->where('user_assign_id', $user_assign_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function deleteAssigdOlderScenariouser($newdata, $user_id, $course_id)
    {
        // $builder = $this->db->table('xapi_scenarios_users_assign');
        $builder = $this->db->table('scorm_users_courses_assigned');
        $builder->where('id_user', $user_id);
        $builder->where('course_id', $course_id);
        $builder->where('status !=', 0);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    function getScenarioUsers($user_assign_id)
    {
        // $builder = $this->db->table('xapi_scenarios_users_assign as x');
        $builder = $this->db->table('scorm_users_courses_assigned as x');
        $builder->select('x.*,u.name as fullname');
        $builder->join('users as u', 'u.id_user = x.user_id and u.valid =1', 'left');
        // $builder->where('user_assign_id', $user_assign_id);
        $builder->join('x.user_assign_id', $user_assign_id);
        $builder->where('x.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function editAssignedScenariouser($newdata, $user_assign_id, $xs)
    {
        // $builder = $this->db->table('xapi_scenarios_users_assign');
        $builder = $this->db->table('scorm_users_courses_assigned');
        // $builder->where('user_assign_id', $user_assign_id);
        $builder->join('user_assign_id', $user_assign_id);
        $builder->where('scenario_id', $xs);
        $builder->update($newdata);
        // $data = $builder->get()->getResultArray();
        $data['status'] = "OK";
        // if (!empty($data)) {
        //     $data['status'] = "OK";
        // } else {
        //     $data['status'] = "Error";
        // }
        return $data;
    }
    function addnewattempt($newdata)
    {
        $builder = $this->db->table('scorm_user_details');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        return true;
    }
}
