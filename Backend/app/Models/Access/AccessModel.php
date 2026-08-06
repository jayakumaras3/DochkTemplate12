<?php

namespace App\Models\Access;

use CodeIgniter\Model;

class AccessModel extends Model
{
    protected $table            = 'accesses';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];


    public function accesslevels($client)
    {
        $builder = $this->db->table('user_access as d');
        $builder->select('d.*');
        $builder->where('d.status', '1');
        $builder->where('d.id_ua !=', '3');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function add_new_access($newdata)
    {
        $builder = $this->db->table('user_access');
        $builder->insert($newdata);
        return true;
    }

    function delete_user_access($newdata, $id_du)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('dropdown_users as d');
        $builder->where('d.id_du', $id_du);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function geteachtemplate($id_d)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('user_access as d');
        $builder->select('d.*');
        $builder->where('d.id_ua', $id_d);
        $builder->where('d.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function access_users($access_id, $client)
    {
        $builder = $this->db->table('dropdown_users as du');
        $builder->select('du.id_du as access_id, u.name as name, u.last_name as last_name, c.client_name as client');
        $builder->join('users as u', 'u.id_user = du.fk_id_user', 'left');
        $builder->join('client as c', 'c.id_c = u.client_id', 'left');
        $builder->where('u.client_id', $client);
        $builder->where('du.fk_id_d', $access_id);
        $builder->where('du.fk_id_dc', 2);
        $builder->where('du.status', '1');
        $builder->where('u.valid', '1');
        $builder->orderBy('u.name', 'ASC');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function update_user_access_level($newdata, $id_ua)
    {
        $builder = $this->db->table('user_access as d');
        $builder->where('d.id_ua', $id_ua);
        $builder->update($newdata);
        return true;
    }
}
