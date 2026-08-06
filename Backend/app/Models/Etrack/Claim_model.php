<?php

namespace App\Models\eTrack;

use CodeIgniter\Model;

class Claim_model extends Model
{

    function get_my_claims_by_month(int $user, int $month, int $year)
    {
        $builder = $this->db->table('et_vendor_details as vd');
        $builder->select('vd.*, u.name as expense_head_name, us.name as requested_by_name, us.last_name as requested_by_last_name');
        $builder->join('users as us', 'us.id_user = vd.requested_by', 'left');
        $builder->join('project_ucn as u', 'u.ucn_id = vd.expense_head', 'left');
        $builder->where('vd.requested_by', $user);
        $builder->where('vd.status !=', 0);
        $builder->where('MONTH(vd.requested_on)', $month);
        $builder->where('YEAR(vd.requested_on)', $year);
        $builder->orderBy('vd.vd_id', 'DESC');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_all_claims_by_month(int $month, int $year)
    {
        $builder = $this->db->table('et_vendor_details as vd');
        $builder->select('vd.*, u.name as expense_head_name, us.name as requested_by_name, us.last_name as requested_by_last_name');
        $builder->join('users as us', 'us.id_user = vd.requested_by', 'left');
        $builder->join('project_ucn as u', 'u.ucn_id = vd.expense_head', 'left');
        $builder->where('vd.status !=', 0);
        $builder->where('MONTH(vd.requested_on)', $month);
        $builder->where('YEAR(vd.requested_on)', $year);
        $builder->orderBy('vd.vd_id', 'DESC');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function get_active_ucn()
    {
        $builder = $this->db->table('project_ucn  as u');
        $builder->select('u.name, u.ucn_id');
        $builder->where('u.status !=', 0);
        $builder->where('u.status !=', 10);
        $builder->orderBy('u.name', 'ASC');
        $data = $builder->get()->getResultArray();
        return  $data;
    }

    function get_claim_by_ID($vd_id)
    {
        $builder = $this->db->table('et_vendor_details as vd');
        $builder->select('vd.*, u.name as expense_head_name');
        $builder->join('project_ucn as u', 'u.ucn_id = vd.expense_head', 'left');
        // $builder->join('et_vendor as v', 'v.vendor_id = vd.vendor_id', 'left');
        $builder->where('vd.vd_id', $vd_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_claim_history_by_ID($vd_id)
    {
        $builder = $this->db->table('et_vendor_history as vh');
        $builder->select('vh.*, u.name as name, u.last_name as last_name');
        $builder->join('users as u', 'u.id_user = vh.last_updated_by', 'left');
        $builder->where('vh.claim_id', $vd_id);
        $builder->orderBy('vh.vh_id', 'DESC');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_vendors()
    {
        $builder = $this->db->table('et_vendor as v');
        $builder->select('v.vendor_id, v.vendor_short_name');
        $builder->where('v.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function add_claim($newdata)
    {
        $builder = $this->db->table('et_vendor_details');
        $builder->insert($newdata);
        return $this->db->insertID();
    }

    function add_claim_history($history_data)
    {
        $builder = $this->db->table('et_vendor_history');
        $builder->insert($history_data);
        return true;
    }

    function update_claim($newdata, $vd_id)
    {
        $builder = $this->db->table('et_vendor_details as vd');
        $builder->where('vd.vd_id', $vd_id);
        $builder->update($newdata);
        return true;
    }
}
