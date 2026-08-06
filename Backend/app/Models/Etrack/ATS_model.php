<?php

namespace App\Models\eTrack;

use CodeIgniter\Model;

class ATS_model extends Model
{

    function get_active_ats(int $client, int $type_access, int $user): array
    {
        $builder = $this->db->table('et_ats as ats');
        $builder->select('ats.*, u.name as requester, d.name as role');
        $builder->join('users as u', 'u.id_user = ats.requested_by', 'left');
        $builder->join('dropdown as d', 'd.value = ats.resource_type AND d.fk_id_dc=8 AND d.status != 0', 'left');
        $builder->where('ats.client_id', $client);
        if ($type_access == 1) {
            $builder->where('ats.requested_by', $user);
        }
        $builder->where('ats.status >', 0);
        $builder->where('ats.status <', 10);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_ats_details($ats_id)
    {
        $builder = $this->db->table('et_ats as ats');
        $builder->select('ats.*, u.name as requester, d.name as role, u2.name as fin_app, u3.name as level2app, u4.name as hr_assi');
        $builder->join('users as u', 'u.id_user = ats.requested_by', 'left');
        $builder->join('users as u2', 'u2.id_user = ats.fin_approver', 'left');
        $builder->join('users as u3', 'u3.id_user = ats.level2_approver', 'left');
        $builder->join('users as u4', 'u4.id_user = ats.assigned_hr', 'left');
        $builder->join('dropdown as d', 'd.value = ats.resource_type AND d.fk_id_dc=8 AND d.status != 0', 'left');
        $builder->where('ats.ats_id', $ats_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_ats_history($ats_id)
    {
        $builder = $this->db->table('et_ats_history as ats_his');
        $builder->select('ats_his.*, u.name as requester');
        $builder->join('users as u', 'u.id_user = ats_his.last_updated_by', 'left');
        $builder->where('ats_his.status !=', 0);
        $builder->where('ats_his.ats_id', $ats_id);
        $builder->orderBy('ats_his.ats_his_id', 'DESC');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function get_roles()
    {
        $builder = $this->db->table('dropdown as d');
        $builder->select('d.name, d.value');
        $builder->where('d.fk_id_dc', 8);
        $builder->where('d.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function add_new_ats_request($newdata)
    {
        $builder = $this->db->table('et_ats');
        $builder->insert($newdata);
        $insert_id = $this->db->insertID();
        return $insert_id;
    }

    function add_new_ats_history($newdata)
    {
        $builder = $this->db->table('et_ats_history');
        $builder->insert($newdata);
        return true;
    }

    function update_ats($newdata, $ats_id)
    {
        $builder = $this->db->table('et_ats as ats');
        $builder->where('ats.ats_id', $ats_id);
        $builder->update($newdata);
        return true;
    }
}
