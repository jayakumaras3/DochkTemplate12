<?php

namespace App\Models\Project_Manage;

use CodeIgniter\Model;

class PM_Proposals_model extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = db_connect(); // Loading database
    }
    public function add_new_proposal($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('proposals');
        $builder->insert($newdata);
        $insertID = $db->insertID();
        if ($insertID) {
            $newdata = [
                'type_of_assignment'  =>  3,
                'db_id' =>  $insertID,
                'user_id' =>  session()->get('id_user'),
                'created_on' => time(),
                'created_by' => session()->get('id_user'),
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
                'status' => '1'
            ];
            $builder = $this->db->table('projects_assignment');
            $builder->insert($newdata);
        }
        return $insertID;
    }

    public function add_proposal_details($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('proposal_details');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        $data = true;
        return $data;
    }

    public function get_proposal_list()
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('proposals as du');
        $builder->select('du.*, c.client_name, concat(u.name," ",u.last_name) as fullname');
        $builder->join('projects_assignment as pa', 'pa.db_id = du.proposal_id', 'left');
        $builder->join('client as c', 'c.id_c = du.client', 'left');
        $builder->join('users as u', 'u.id_user = du.account_manager', 'left');
        $builder->where('pa.type_of_assignment', 3);
        $builder->where('pa.user_id', session()->get('id_user'));
        $builder->where('du.status !=', 0);

        $builder->orderBy('du.proposal_id', 'DESC');
        $builder->limit(20);
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    public function get_proposal_data($proposal_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('proposals as du');
        $builder->select('du.*');
        $builder->where('du.proposal_id', $proposal_id);
        $data = $builder->get()->getResultArray();
        return  $data;
    }

    public function get_proposal_details($proposal_id, $type)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('proposal_details as du');
        $builder->select('du.*');
        $builder->where('du.status !=', 0);
        $builder->where('du.types', $type);
        $builder->where('du.proposal_id', $proposal_id);
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    public function get_proposalimage_details($proposal_id, $type)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('proposal_details as du');
        $builder->select('du.*');
        $builder->where('du.status !=', 0);
        $builder->where('du.types', $type);
        $builder->where('du.proposal_id', $proposal_id);
        $builder->orderBy('proposal_details_id', 'Asc');
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    public function get_pricing_data()
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_pricing as du');
        $builder->select('du.*');
        $builder->where('du.status', 6);
        $data = $builder->get()->getResultArray();
        return  $data;
    }

    public function edit_proposal($newdata, $proposal_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('proposals as pd');
        $builder->where('pd.proposal_id', $proposal_id);
        return $builder->update($newdata);
    }
    public function delete_proposal_details($newdata, $proposal_details_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('proposal_details as pd');
        $builder->where('pd.proposal_details_id', $proposal_details_id);
        return $builder->update($newdata);
    }


    public function  update_proposal_status($newdata, $proposal_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('proposals as pd');
        $builder->where('pd.proposal_id', $proposal_id);
        return $builder->update($newdata);
    }
}
