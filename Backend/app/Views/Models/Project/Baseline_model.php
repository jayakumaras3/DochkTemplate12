<?php

namespace App\Models\Project;

use DateTime;

use CodeIgniter\Model;

class Baseline_model extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = db_connect(); // Loading database
        // OR $this->db = \Config\Database::connect();
    }
    function getAllBaseline()
    {
        $builder = $this->db->table('project_baseline as b');
        $builder->select('b.*,u1.name as user');
        $builder->join('users as u1', 'u1.id_user = b.last_updated_by', 'left');
        $builder->where('b.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getBaselineValues($bid)
    {
        $builder = $this->db->table('project_baseline as b');
        $builder->select('b.*');
        $builder->where('b.bid', $bid);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function addbaselineValue($newdata)
    {
        $builder = $this->db->table('project_baseline');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        return true;
    }

    function updatebaselineValue($newdata, $bid)
    {
        $builder = $this->db->table('project_baseline as dlt');
        $builder->where('dlt.bid', $bid);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return true;
    }
}
