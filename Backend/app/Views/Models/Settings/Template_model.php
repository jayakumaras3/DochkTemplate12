<?php

namespace App\Models\Settings;

use CodeIgniter\Model;

class Template_model extends Model
{
    protected $primaryKey = 't_id';
    protected $allowedFields = ['fk_template_id', 'item_type', 'item_description', 'completion', 'duration', 'status', 'createdon', 'createdby', 'deletedon', 'deletedby'];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    function addtemplatedetails($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('template_details');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        return true;
    }
    function gettemplatedetails($id_d)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('template_details as td');
        $builder->select('td.*,d.name as itemtypename');
        $builder->join('dropdown as d', 'd.id_d = td.item_type', 'left');
        $builder->where('td.fk_template_id', $id_d);
        $builder->where('td.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function geteachtempdetails($t_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('template_details as td');
        $builder->select('td.*,d.name as itemtypename');
        $builder->join('dropdown as d', 'd.id_d = td.item_type and d.fk_id_dc =1', 'left');
        $builder->where('td.t_id', $t_id);
        $builder->where('td.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function edittemplatedetails($newdata, $t_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('template_details');
        $builder->where('t_id', $t_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function deleteTemplatedetails($t_id, $newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table(' template_details as td');
        $builder->where('td.t_id', $t_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
}
