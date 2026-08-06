<?php

namespace App\Models\User_login;

use CodeIgniter\Model;

class Client_model extends Model
{
   
    function  getClientProjects($client)
    {
        $builder = $this->db->table('projects as p');
        $builder->select('p.*');
        $builder->where('p.client', $client);
        $builder->where('p.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }
}
