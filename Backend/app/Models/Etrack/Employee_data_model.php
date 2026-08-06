<?php

namespace App\Models\eTrack;

use CodeIgniter\Model;

class Employee_data_model extends Model
{

    function checkuseraccess($user, $pannumber)
    {
        $builder = $this->db->table('users_personal_data as upd');
        $builder->select('upd.upd_id');
        $builder->where('upd.userid', $user);
        $builder->where('upd.PAN', $pannumber);
        $builder->where('upd.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getdependents($user)
    {
        $builder = $this->db->table('et_dependents as dep');
        $builder->select('dep.*');
        $builder->where('dep.user_id', $user);
        $builder->where('dep.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_my_tickets($user)
    {
        $builder = $this->db->table('et_support_tickets as spt');
        $builder->select('spt.*');
        $builder->where('spt.id_user', $user);
        $builder->where('spt.status != ', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_all_assets()
    {
        $builder = $this->db->table('et_assets as ea');
        $builder->select('ea.*, count(ead.et_ass_det_id) AS eaccount');
        $builder->join('et_assets_details as ead', 'ead.asset_id = ea.et_asset_id AND ead.status = "1"', 'left');
        $builder->where('ea.status', 1);
        $builder->groupBy('ea.et_asset_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_asset_details_edit($et_ass_det_id)
    {
        $builder = $this->db->table('et_assets_details as ea');
        $builder->select('ea.*');
        $builder->where('ea.et_ass_det_id', $et_ass_det_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_user_assets($user)
    {
        $builder = $this->db->table('et_assets_assign_history as ah');
        $builder->select('ah.*, u.description as desc, u.fin_identifier as identifier, ea.description as asset_type');
        $builder->join('et_assets_details as u', 'u.et_ass_det_id = ah.asset_detail_id', 'left');
        $builder->join('et_assets as ea', 'ea.et_asset_id = u.asset_id', 'left');
        $builder->where('ah.assigned_to', $user);
        $builder->orderBy('returned_on', 'DESC');
        $builder->where('ah.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_user_software($user){
        $builder = $this->db->table('et_softwares_assigned as software');
        $builder->select('software.assigned_on, s.soft_description as desc, sd.end_date as end_date, software.remarks as remarks');
        $builder->join('et_softwares_details as sd', 'sd.soft_detail_id = software.soft_detail_id', 'left');
        $builder->join('et_softwares as s', 's.soft_id = sd.soft_id', 'left');
        $builder->where('software.id_user', $user);
        $builder->where('software.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_asset_history($et_ass_det_id)
    {
        $builder = $this->db->table('et_assets_assign_history as ah');
        $builder->select('ah.*, u.name as name');
        $builder->join('users as u', 'u.id_user = ah.assigned_to', 'left');
        $builder->where('ah.asset_detail_id', $et_ass_det_id);
        $builder->where('ah.status', 1);
        $builder->orderBy('ah.assigned_on', 'DESC');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_asset_desc($assetid)
    {
        $builder = $this->db->table('et_assets as ea');
        $builder->select('ea.description as assetdesc');
        $builder->where('ea.et_asset_id', $assetid);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_history_byID($et_assets_assign_id)
    {
        $builder = $this->db->table('et_assets_assign_history as eh');
        $builder->select('eh.*');
        $builder->where('eh.et_assets_assign_id', $et_assets_assign_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_asset_details($asset_id)
    {
        $builder = $this->db->table('et_assets_details as ea');
        $builder->select('ea.*, ANY_VALUE(u.name) as name');
        $builder->join('(SELECT * FROM et_assets_assign_history ORDER BY assigned_on DESC) as ah', 'ah.asset_detail_id = ea.et_ass_det_id', 'left');
        $builder->join('users as u', 'u.id_user = ah.assigned_to', 'left');
        $builder->where('ea.asset_id', $asset_id);
        $builder->where('ea.status', 1);
        $builder->groupBy('ea.et_ass_det_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_all_softwares()
    {
        $builder = $this->db->table('et_softwares as soft');
        $builder->select('soft.*');
        $builder->where('soft.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function get_softwareName($soft_id)
    {
        $builder = $this->db->table('et_softwares as soft');
        $builder->select('soft.soft_description');
        $builder->where('soft.soft_id', $soft_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function get_software_user_assigned($soft_detail_id)
    {
        $builder = $this->db->table('et_softwares_assigned as soft');
        $builder->select('soft.assigned_on, soft.sf_assign_id, soft.remarks, u.name, u.last_name');
        $builder->join('users as u', 'u.id_user = soft.id_user', 'left');
        $builder->where('soft.soft_detail_id', $soft_detail_id);
        $builder->where('soft.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_software_details_edit($soft_detail_id)
    {
        $builder = $this->db->table('et_softwares_details as soft');
        $builder->select('soft.*');
        $builder->where('soft.soft_detail_id', $soft_detail_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }



    function get_software_byID($soft_id)
    {
        $today = date('Y-m-d');
        $builder = $this->db->table('et_softwares_details as soft_details');
        $builder->select('soft_details.*, COUNT(sa.sf_assign_id) AS used');
        $builder->join('et_softwares_assigned as sa', 'sa.soft_detail_id = soft_details.soft_detail_id AND sa.status= 1', 'left');
        $builder->where('soft_details.soft_id', $soft_id);
        $builder->where('soft_details.status', 1);

        $builder->where('soft_details.end_date >', $today);
        $builder->groupBy('soft_detail_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_open_tickerts()
    {
        $builder = $this->db->table('et_support_tickets as spt');
        $builder->select('spt.*, u.name');
        $builder->join('users as u', 'u.id_user = spt.id_user', 'left');
        $builder->where('spt.status', 1);
        $builder->orWhere('spt.status', 3);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_not_closed_tickerts()
    {
        $builder = $this->db->table('et_support_tickets as spt');
        $builder->select('spt.*, u.name');
        $builder->join('users as u', 'u.id_user = spt.id_user', 'left');
        $builder->where('spt.status', 2);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_closed_tickerts($start_date, $end_date)
    {
        $builder = $this->db->table('et_support_tickets as spt');
        $builder->select('spt.*, u.name');
        $builder->join('users as u', 'u.id_user = spt.id_user', 'left');
        $builder->where('spt.last_updated_on >=', $end_date);
        $builder->where('spt.last_updated_on <=', $start_date);
        $builder->where('spt.status', 4);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_ticket_reply_details($et_sup_id)
    {
        $builder = $this->db->table('et_support_tickets_reply as spt');
        $builder->select('spt.*, u.name as name');
        $builder->join('users as u', 'u.id_user = spt.last_updated_by', 'left');
        $builder->where('spt.et_sup_id', $et_sup_id);
        $builder->where('spt.status != ', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function get_ticket_details($et_sup_id)
    {
        $builder = $this->db->table('et_support_tickets as spt');
        $builder->select('spt.*, u.name as name');
        $builder->join('users as u', 'u.id_user = spt.assigned_to', 'left');
        $builder->where('spt.et_sup_id', $et_sup_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function  get_my_tickets_num($user, $type)
    {
        $builder = $this->db->table('et_support_tickets as spt');
        $builder->select('spt.*');
        $builder->where('spt.id_user', $user);
        $builder->where('spt.status', $type);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_pan_by_userID($user)
    {
        $builder = $this->db->table('users_personal_data as upd');
        $builder->select('upd.PAN');
        $builder->where('upd.userid', $user);
        $builder->where('upd.status', 1);
        $builder->orderBy('upd.upd_id', 'DESC');
        $builder->limit(1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getappraisals($user)
    {
        $builder = $this->db->table('salary as sal');
        $builder->select('sal.*');
        // $builder->join('users as u', 'u.emp_id = sal.empid', 'left');
        $builder->where('sal.id_user', $user);
        $builder->orderBy('sal.effectivedate', 'ASC');
        $builder->where('sal.status', 1);
        $data = $builder->get()->getResultArray();
        return $data; 
    }

    function getletter($user, $salid)
    {
        $builder = $this->db->table('salary as sal');
        $builder->select('sal.*, u.name as fname, u.last_name as last_name, u.emp_id as emp_id, acc.name as accname, acc.last_name as acclast');
        $builder->join('users as u', 'u.id_user = sal.id_user', 'left');
        $builder->join('users as acc', 'acc.id_user = sal.adgreed_by', 'left');
        $builder->where('sal.salid', $salid);
        $builder->where('sal.id_user', $user);
        $builder->where('sal.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getbreakup($user, $salid)
    { 
        $builder = $this->db->table('et_appraisal_breakup as brkup');
        $builder->select('brkup.*, u.name as fname, u.last_name as last_name, u.emp_id as emp_id');
        $builder->join('users as u', 'u.id_user = brkup.id_user', 'left');
        $builder->where('brkup.salid', $salid);
        $builder->where('brkup.id_user', $user);
        $builder->where('brkup.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getappraisalsID($salid)
    {
        $builder = $this->db->table('salary as sal');
        $builder->select('sal.*');
        $builder->where('sal.salid', $salid);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getpayrolls($user)
    {
        $builder = $this->db->table('user_payslip as up');
        $builder->select('up.ID, up.pay_month, up.pay_yr');
        $builder->where('up.full_name', $user);
        $builder->where('up.status', 1);
        $builder->orderBy('pay_yr', 'DESC');
        $builder->orderBy('pay_month', 'DESC');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getpayroll_details($payslip_month, $payslip_yr, $user)
    {
        $builder = $this->db->table('user_payslip as up');
        $builder->select('up.*, u.name as fname, u.last_name as last_name, u.DOJ as DOJ');
        $builder->join('users as u', 'u.id_user = up.full_name', 'left');
        $builder->where('up.full_name', $user);
        $builder->where('up.pay_month', $payslip_month);
        $builder->where('up.pay_yr', $payslip_yr);
        $builder->where('up.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function add_asset($newdata)
    {
        $builder = $this->db->table('et_assets');
        $builder->insert($newdata);
        return true;
    }

    function assign_software_license($newdata)
    {
        $builder = $this->db->table('et_softwares_assigned');
        $builder->insert($newdata);
        return true;
    }

    function add_new_software($newdata)
    {
        $builder = $this->db->table('et_softwares');
        $builder->insert($newdata);
        return true;
    }
    function delete_softwares($newdata,$soft_id)
    {
        $builder = $this->db->table('et_softwares');
        $builder->where('soft_id', $soft_id);
        $builder->update($newdata);
        return true;
    }
    function add_new_software_details($newdata)
    {
        $builder = $this->db->table('et_softwares_details');
        $builder->insert($newdata);
        return true;
    }
    function assign_asset_user($newdata)
    {
        $builder = $this->db->table('et_assets_assign_history');
        $builder->insert($newdata);
        return true;
    }

    function add_asset_details_mo($newdata)
    {
        $builder = $this->db->table('et_assets_details');
        $builder->insert($newdata);
        return true;
    }

    function add_dependent($newdata)
    {
        $builder = $this->db->table('et_dependents');
        $builder->insert($newdata);
        return true;
    }
    function add_ticket($newdata)
    {
         $db = \Config\Database::connect();
        $builder = $this->db->table('et_support_tickets');
        $builder->insert($newdata);
        $insertID = $db->insertID();
        return $insertID;
    }

    function add_reply_ticket($newdata)
    {
        $builder = $this->db->table('et_support_tickets_reply');
        $builder->insert($newdata);
        return true;
    }

    function update_asset($newdata, $et_ass_det_id)
    {
        $builder = $this->db->table('et_assets_details as aa');
        $builder->where('aa.et_ass_det_id', $et_ass_det_id);
        $builder->update($newdata);
        return true;
    }

    function delete_software_license($newdata, $sf_assign_id)
    {
        $builder = $this->db->table('et_softwares_assigned as aa');
        $builder->where('aa.sf_assign_id', $sf_assign_id);
        $builder->update($newdata);
        return true;
    }

    function update_software($newdata, $sf_assign_id)
    {
        $builder = $this->db->table('et_softwares_details as aa');
        $builder->where('aa.soft_detail_id', $sf_assign_id);
        $builder->update($newdata);
        return true;
    }

    function update_asset_his($newdata, $et_assets_assign_id)
    {
        $builder = $this->db->table('et_assets_assign_history as aa');
        $builder->where('aa.et_assets_assign_id', $et_assets_assign_id);
        $builder->update($newdata);
        return true;
    }

    function update_dependent($newdata, $dependent_id)
    {
        $builder = $this->db->table('et_dependents as dep');
        $builder->where('dep.dep_id', $dependent_id);
        $builder->update($newdata);
        return true;
    }

    function update_ticket($newdata, $et_sup_id)
    {
        $builder = $this->db->table('et_support_tickets as st');
        $builder->where('st.et_sup_id', $et_sup_id);
        $builder->update($newdata);
        return true;
    }
    function update_appraisal($newdata, $salid)
    {
        $builder = $this->db->table('salary as sal');
        $builder->where('sal.salid', $salid);
        $builder->update($newdata);
        return true;
    }
    function updateEmpID_For_MissingData()
    {
        $builder = $this->db->table('user_payslip as up');
        $builder->select('up.ID as payslipID, up.user_id as empID');
        $builder->where('up.full_name', '');
        $data = $builder->get()->getResultArray();

        foreach ($data as $eid) {
            $builder = $this->db->table('users as us');
            $builder->select('us.id_user as userid');
            $builder->where('us.emp_id', $eid['empID']);
            $data_2 = $builder->get()->getResultArray();
            if ($data_2) {
                $assign_emp_id = [
                    'full_name' =>  $data_2[0]['userid'],
                ];
                $builder = $this->db->table('user_payslip as ups');
                $builder->where('ups.ID', $eid['payslipID']);
                $builder->update($assign_emp_id);
            } else {
                $assign_emp_id = [
                    'full_name' =>  'ENERGAGE',
                ];
                $builder = $this->db->table('user_payslip as ups');
                $builder->where('ups.ID', $eid['payslipID']);
                $builder->update($assign_emp_id);
            }
        }
        return true;
    }
}
