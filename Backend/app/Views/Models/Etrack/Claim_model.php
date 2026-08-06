<?php

namespace App\Models\eTrack;

use CodeIgniter\Model;

class Claim_model extends Model
{

    function get_my_claims($user)
    {
        $builder = $this->db->table('et_vendor_details as vd');
        $builder->select('vd.*, v.vendor_short_name');
        $builder->join('et_vendor as v', 'v.vendor_id = vd.vendor_id', 'left');
        $builder->where('vd.requested_by', $user);
        $builder->where('vd.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_claim_by_ID($vd_id)
    {
        $builder = $this->db->table('et_vendor_details as vd');
        $builder->select('vd.*, v.vendor_short_name');
        $builder->join('et_vendor as v', 'v.vendor_id = vd.vendor_id', 'left');
        $builder->where('vd.vd_id', $vd_id);
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
