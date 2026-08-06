<?php

namespace App\Models\XAPI;

use CodeIgniter\Model;

class API_model extends Model
{
    function checkUserAssignment($id_user, $course_id)
    {
        $builder = $this->db->table('scorm_users_courses_assigned as sua');
        $builder->select('sua.*');
        $builder->where('sua.id_user', $id_user);
        $builder->where('sua.course_id', $course_id);
        $builder->where('sua.status', 1);

        $data = $builder->get()->getResultArray();
        // print_r($data);
        // exit();
        return $data;
    }
    function getUserassignedScenario($course_id, $id_user)
    {
        $builder = $this->db->table('xapi_scenarios as s');
        $builder->select('ua.*');
        // $builder->join('xapi_scenarios_users_assign as ua', 'ua.scenario_id =s.xs and ua.user_id = ' . $id_user , 'left');
        $builder->join('scorm_users_courses_assigned as ua', 'ua.scenario_id =s.xs and ua.id_user = ' . $id_user, 'left');
        $builder->where('ua.status', '1');
        $builder->where('s.scourse_id', $course_id);
        // $builder->where('s.status', 2);
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // print_r($data);
        // echo "<pre>";
        // exit();
        return $data;
    }
    function checkVRUserAssignment($id_user, $course_id)
    {
        $builder = $this->db->table('scorm_users_courses_assigned as sua');
        $builder->select('sua.*');
        $builder->where('sua.id_user', $id_user);
        $builder->where('sua.course_id', $course_id);
        $builder->where('sua.status', 1);
        $data = $builder->get()->getResultArray();

        // print_r($data);
        // exit();
        return $data;
    }

    // function getScenarioSettings($scenario_id)
    // {
    //     $builder = $this->db->table('xapi_scenario_input_settings as sua');
    //     $builder->select('sua.*, xiv.variable_name');
    //     $builder->join('xapi_input_variables as xiv', 'xiv.xiv = sua.input_variable', 'left');
    //     $builder->where('sua.scenario_id', $scenario_id);
    //     $builder->where('sua.status', 1);
    //     $data = $builder->get()->getResultArray();
    //     return $data;
    // }
    function getScenarioSettings($scenario_id)
    {
        $builder = $this->db->table('xapi_scenario_input_settings as sua');
        $builder->select('sua.*, xiv.variable_name, xiv.variable_description, xiv.instructions, ivd.text as dvalue');
        $builder->join('xapi_input_variables as xiv', 'xiv.xiv = sua.input_variable', 'left');
        $builder->join('xapi_input_dropdown_values as ivd', 'ivd.xiv = sua.input_variable and ivd.value = sua.value and ivd.status!=0', 'left');
        $builder->where('sua.scenario_id', $scenario_id);
        $builder->where('sua.status', 1);
        $builder->where('xiv.status != ', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function checkattempts($user_assigned_id_val)
    {
        $builder = $this->db->table('scorm_user_details as sud');
        $builder->select('sud.attempt');
        $builder->where('sud.user_assign_id', $user_assigned_id_val);
        $builder->where('sud.status', 1);
        $builder->orderBy('sc_uid', 'DESC');
        $builder->limit(1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function createNewActivity($newdata)
    {
        $builder = $this->db->table('scorm_user_details');
        $builder->insert($newdata);
        $insertID = $this->db->insertID();
        return $insertID;
    }
    function updatecoursestatus($assign_id, $newdata)
    {
        $builder = $this->db->table('scorm_users_courses_assigned as sa');
        $builder->where('user_assign_id', $assign_id);
        $builder->where('status', 1);
        $builder->update($newdata);
    }

    function enterUserData($newdata2)
    {
        $builder = $this->db->table('xapi_activities');
        $builder->insert($newdata2);
        $insertID = $this->db->insertID();
        return $insertID;
    }

    function updateStat($updatedata, $recordID)
    {
        $builder = $this->db->table('scorm_user_details as sud');
        $builder->where('sud.sc_uid', $recordID);
        $builder->update($updatedata);
        // $data = $builder->get()->getResultArray();
        return true;
    }
    function updateSuspendDataIn($updatedata, $user_assigned_id_val, $attempt)
    {
        $builder = $this->db->table('scorm_user_details as sud');
        $builder->where('sud.user_assign_id', $user_assigned_id_val);
        $builder->where('sud.attempt', $attempt);
        $builder->update($updatedata);
        // $data = $builder->get()->getResultArray();
        return true;
    }
    function getSuspendata($user_assigned_id_val, $attempt)
    {
        $builder = $this->db->table('scorm_user_details as sud');
        $builder->select('sud.suspend_data');
        $builder->where('sud.user_assign_id', $user_assigned_id_val);
        $builder->where('sud.attempt', $attempt);
        $data = $builder->get()->getResultArray();
        return $data;
    }
}
