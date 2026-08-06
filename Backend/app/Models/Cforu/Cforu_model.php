<?php

namespace App\Models\Cforu;

use CodeIgniter\Model;

class Cforu_model extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = db_connect(); // Loading database
    }
    function getC4UCourses()
    {
        $builder = $this->db->table('scorm_courses as c');
        $builder->where('c.project_id', 29);
        $builder->where('c.mode', '2');
        $builder->where('c.status', '1');
        $builder->orderBy('scourse_id', 'RANDOM');
        $builder->limit(4);
        $data = $builder->get()->getResultArray();
        return $data;
    }
}
