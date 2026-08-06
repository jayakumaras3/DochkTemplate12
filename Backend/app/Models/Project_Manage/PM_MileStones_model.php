<?php

namespace App\Models\Project_Manage;

use CodeIgniter\Model;

class PM_MileStones_model extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = db_connect(); // Loading database
    }

    public function get_milestone_list()
    {
        $builder = $this->db->table('project_milestones as pm');
        $builder->select('pm.*, u.ucn_id as ucn, u.name as ucnname');
        $builder->join('project_ucn as u', 'u.ucn_id = pm.ucn_id', "left");
        $builder->where('pm.status !=', 0);
        $builder->orderBy('pm.invoicing_dt', 'DESC');
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    public function get_milestone_details($milestone_id)
    {
        $builder = $this->db->table('project_invoice as pi');
        $builder->select('pi.*');
        $builder->where('pi.status !=', 0);
        $builder->orderBy('pi.milestone_id', $milestone_id);
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    public function add_invoice($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_invoice');
        $builder->insert($newdata);
        $data = $builder->get()->getResultArray();
        return  $data;
    }

    public function get_invoice_details($invoice_id)
    {
        $builder = $this->db->table('project_invoice as pi');
        $builder->select('pi.*,c.address');
        $builder->join('client as c','c.id_c = pi.client_id','left');
        $builder->where('pi.status !=', 0);
        $builder->orderBy('pi.invoice_id', $invoice_id);
        $data = $builder->get()->getResultArray();
        return  $data;
    }

    public function update_invoice($newdata, $invoice_id)

    {
        $builder = $this->db->table('project_invoice as pd');
        $builder->where('pd.invoice_id', $invoice_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    public function get_invoice_list()
    {
        $builder = $this->db->table('project_invoice as pi');
        $builder->select('pi.*, c.client_name as client_name');
        $builder->join('client as c', 'c.id_c = pi.client_id', "left");
        $builder->join('projects_assignment as pa', 'pa.db_id = c.id_c', 'left');
        $builder->where('pa.user_id',session()->get('id_user'));
        $builder->where('pi.status >', 1);
        $builder->where('pa.status', 1);
        $data = $builder->get()->getResultArray();
        return  $data;
    }
    public function getpoclient($milestone_id)
    {
        $builder = $this->db->table('project_milestones as pm');
        $builder->select('po.client_id');
        $builder->join('purchase_order as po', 'po.po_id = pm.po_id', "left");
        $builder->where('pm.status =', 1);
        $builder->groupBy('po.client_id');
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return  $data;
    }
}
