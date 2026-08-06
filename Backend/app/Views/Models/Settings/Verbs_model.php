<?php

namespace App\Models\Settings;

use CodeIgniter\Model;

class verbs_model extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = db_connect(); // Loading database
    }

    public function getAllVerbs()
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('verbs as v');
        $builder->select('v.*');
        $builder->where('v.status != ', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function createNewVerb($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('verbs');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        return  true;
    }

    public function getSpecificVerb($verbid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('verbs as v');
        $builder->select('v.*');
        $builder->where('v.verbid', $verbid);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function updateVerb($newdata, $verbid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('verbs');
        $builder->where('verbid', $verbid);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
}
