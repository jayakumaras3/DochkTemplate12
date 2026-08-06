<?php

namespace App\Models\Project;

use CodeIgniter\Model;

class Pricing_model extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = db_connect(); // Loading database
    }
    function getAllPricing()
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_pricing as b');
        $builder->select('b.*,u1.name as user, u2.name as requester, c.client_name as clientname, pb.description as baselinetype');
        $builder->join('users as u1', 'u1.id_user = b.last_updated_by', 'left');
        $builder->join('users as u2', 'u2.id_user = b.requested_by', 'left');
        $builder->join('project_baseline as pb', 'pb.bid = b.type', 'left');
        $builder->join('client as c', 'c.id_c = b.client', 'left');
        $builder->where('b.status', 1);
        $builder->orderBy('b.ppid', 'DESC');
        $builder->limit(20);
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    function getcurrentPricingValue($ppid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_pricing as b');
        $builder->select('b.*,u1.name as user, u2.name as requester, c.client_name as clientname, pb.description as baselinetype');
        $builder->join('users as u1', 'u1.id_user = b.last_updated_by', 'left');
        $builder->join('users as u2', 'u2.id_user = b.requested_by', 'left');
        $builder->join('project_baseline as pb', 'pb.bid = b.type', 'left');
        $builder->join('client as c', 'c.id_c = b.client', 'left');
        $builder->where('b.ppid', $ppid);
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    function getAllSales()
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('dropdown_users as du');
        $builder->select('du.*, u1.id_user as user_id, u1.name as name');
        $builder->where('du.fk_id_d', 5);
        $builder->where('du.status', 1);
        $builder->join('users as u1', 'u1.id_user = du.fk_id_user', 'left');
        $builder->orderBy('u1.name', 'ASC');
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    function getAllclient()
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('client as c');
        $builder->select('c.*');
        $builder->where('c.status', 1);
        $builder->orderBy('c.id_c', 'ASC');
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    function baselinePricing()
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_baseline_cost as c');
        $builder->select('c.*');
        $builder->where('c.status', 1);
        $builder->orderBy('c.bidc', 'ASC');
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    function pricing_sheet_details($ppid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_pricing_details as c');
        $builder->select('c.*');
        $builder->where('c.ppid', $ppid);
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    function project_pricing_details($ppid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_baseline_cost as c');
        $builder->select('c.*');
        $builder->where('c.bidc', $ppid);
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    function addPricingDetails($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_pricing_details');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        $data = true;
        return $data;
    }
    function addpricingValue($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_pricing');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        return true;
    }

    function updatepricingValue($newdata, $ppid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_pricing');
        $builder->where('ppid', $ppid);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return  $data;
    }
}
