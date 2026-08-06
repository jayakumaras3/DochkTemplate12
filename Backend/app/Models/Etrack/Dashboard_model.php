<?php

namespace App\Models\eTrack;

use CodeIgniter\Model;

class Dashboard_model extends Model
{

    function getWeeklyEffort_dash($user, $this_week, $last_week)
    {
        $builder = $this->db->table('project_effort as pe');
        $builder->select('
            IFNULL(SUM(CASE WHEN pe.work_week = ' . $this->db->escape($this_week) . ' THEN pe.effort ELSE 0 END), 0) as this_week_effort,
            IFNULL(SUM(CASE WHEN pe.work_week = ' . $this->db->escape($last_week) . ' THEN pe.effort ELSE 0 END), 0) as last_week_effort
        ');
        $builder->where('pe.user', $user);
        $builder->where('pe.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getPendingApprovalCount_dash($manager_id)
    {
        $builder = $this->db->table('project_effort as pe');
        $builder->select('COUNT(pe.pe_id) as pending_count');
        $builder->join('users as u', 'u.id_user = pe.user', 'left');
        $builder->where('pe.status', 1);
        $builder->where('u.manager', $manager_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getmymymanager($temp_user)
    {
        $builder = $this->db->table('users as u');
        $builder->select('m.name, m.last_name, m.id_user, m.designation, m.profile_image,m.id_user,m.profile_foldername');
        $builder->join('users as m', 'm.id_user = u.manager', 'left');
        // $builder->join('profile as p', 'p.username = m.username', 'left');
        $builder->where('m.valid', 1);
        $builder->where('u.id_user', $temp_user);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function aboutme($temp_user)
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.name, u.last_name, u.id_user, u.designation,u.profile_image,u.id_user,u.profile_foldername');
        // $builder->join('profile as p', 'p.username = u.username', 'left');
        $builder->where('u.valid', 1);
        $builder->where('u.id_user', $temp_user);
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }

    function getmyReportees($temp_user)
    {
        $builder = $this->db->table('users as m');
        $builder->select('m.name, m.last_name, m.id_user, m.designation,m.profile_image,m.id_user,m.profile_foldername');
        // $builder->join('profile as p', 'p.username = m.username', 'left');
        $builder->where('m.valid', 1);
        $builder->where('m.manager', $temp_user);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function check_policy_accepted($user_id, $emd_id)
    {
        $builder = $this->db->table('tlc_policy_agree');
        $builder->select('accepted_on');
        $builder->where('user_id', $user_id);
        $builder->where('emd_id', $emd_id);
        $builder->orderBy('accepted_on', 'DESC');
        $builder->limit(1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_tlc_policies()
    {
        $builder = $this->db->table('emanual_document as epg');
        $builder->select('epg.document_name, epg.emd_id, tpa.accepted_on as accepted_on, epg.last_updated_on as last_updated_on');
        $builder->join('tlc_policy_agree as tpa', 'tpa.emd_id = epg.emd_id and tpa.user_id = ' . session()->get('id_user'), 'left');
        $builder->where('epg.product_id', 7);
        $builder->groupBy('epg.emd_id');
        $builder->where('epg.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function accept_policy($newdata)
    {
        $builder = $this->db->table('tlc_policy_agree');
        $builder->insert($newdata);
        return true;
    }

    function get_tlc_policy_name($emd_id)
    {
        $builder = $this->db->table('emanual_document as epg');
        $builder->select('epg.document_name, epg.last_updated_on');
        $builder->where('epg.emd_id', $emd_id);
        $builder->where('epg.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_tlc_policy_details($emd_id)
    {
        $builder = $this->db->table('emanual_page as epg');
        $builder->select('epg.page_name, epg.empg_id');
        $builder->where('epg.document_id', $emd_id);
        $builder->where('epg.status', 1);
        $builder->orderBy('page_number', 'ASC');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_tlc_policy_pages($empg_id)
    {
        // print_r($empg_id);
        // exit();
        $builder = $this->db->table('emanual_content as epg');
        $builder->select('epg.content1, epg.type,epg.page_id');
        $builder->where('epg.page_id', $empg_id);
        $builder->where('epg.status !=', 0);
        $builder->orderBy('sequence', 'ASC');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    // function get_anniversary_buddies($monthday)
    // {
    //     $builder = $this->db->table('users as u');
    //     $builder->select('u.name, u.last_name, u.profile_image,u.id_user,u.profile_foldername, u.DOJ');
    //     $builder->where('u.valid >', 0);
    //     $builder->where('DATE_FORMAT(u.DOJ, "%m") = DATE_FORMAT("' . $monthday . '", "%m")');

    //     //$builder->limit(4);
    //     $builder->orderBy('Month(u.DOJ)', 'ASC');
    //     $builder->orderBy('Day(u.DOJ)', 'ASC');
    //     $data = $builder->get()->getResultArray();

    //     // echo $this->db->getLastQuery();
    //     // exit();
    //     return $data;
    // }
    function get_anniversary_buddies($monthday)
    {
        $month = date('m', strtotime($monthday));
        $builder = $this->db->table('users as u');
        $builder->select('u.name, u.last_name, u.profile_image, u.id_user, u.profile_foldername, u.DOJ');
        $builder->where('u.valid >', 0);
        $builder->where('MONTH(u.DOJ)', $month);
        $builder->orderBy('MONTH(u.DOJ)', 'ASC');
        $builder->orderBy('DAY(u.DOJ)', 'ASC');
        return $builder->get()->getResultArray();
    }


    function getbirthdaybuddies($monthday)
    {
        $builder = $this->db->table('users_personal_data as upd');
        $builder->select('u.name, u.last_name, u.profile_image,u.id_user,u.profile_foldername, upd.DOB');
        $builder->join('users as u', 'u.id_user = upd.userid', 'left');
        // $builder->join('profile as p', 'p.username = u.username', 'left');
        $builder->where('upd.status >', 0);
        $builder->where('u.valid >', 0);
        $builder->where('DATE_FORMAT(upd.DOB, "%m-%d") >= DATE_FORMAT("' . $monthday . '", "%m-%d")');
        $builder->limit(5);
        $builder->orderBy('Month(upd.DOB)', 'ASC');
        $builder->orderBy('Day(upd.DOB)', 'ASC');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getTaskByUser_dash($user, $status)
    {
        $builder = $this->db->table('tasks as t');
        $builder->select('count(t.task_id) as tasks');
        $builder->where('t.assigned_to', $user);
        $builder->where('t.status', $status);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function get_active_projects($status)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('projects as pro');
        $builder->select('count(pro.projectid) as projectid');
        $builder->join('projects_assignment as pa', 'pa.db_id = pro.projectid', 'left');
        $builder->where('pro.status >', 0);
        $builder->where('pro.status', $status);
        $builder->where('pa.type_of_assignment', 1);
        $builder->where('pa.user_id', session()->get('id_user'));
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function get_purchase_order_list()
    {
        $builder = $this->db->table('purchase_order as du');
        $builder->select('sum(du.project_value) as project_value');
        $builder->join('projects_assignment as pa', 'pa.db_id = du.po_id', 'left');
        $builder->where('pa.type_of_assignment', 4);
        $builder->where('pa.user_id', session()->get('id_user'));
        $builder->where('du.status !=', 0);
        $builder->orderBy('du.po_id', 'DESC');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function getHolidays($today, $type)
    {
        $builder = $this->db->table('holiday_list as holidays');
        $builder->select('holidays.*');
        $builder->where('holidays.status', 1);
        $builder->where('holidays.country', $type);
        $builder->where('holidays.holiday_dt >=', $today);
        $builder->orderBy('holidays.holiday_dt', 'ASC');
        $builder->limit(5);

        $data = $builder->get()->getResultArray();
        return $data;
    }


    function add_inoffice($newdata)
    {
        $builder = $this->db->table('et_present_today');
        $builder->insert($newdata);
        return true;
    }
}
