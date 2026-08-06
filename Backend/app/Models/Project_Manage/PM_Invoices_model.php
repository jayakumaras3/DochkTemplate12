<?php

namespace App\Models\Project_Manage;

use CodeIgniter\Model;

class PM_Invoices_model extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = db_connect(); // Loading database
    }

    public function get_invoices(int $year, int $month): array
    {
        $startDate = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01';
        $endDate   = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-31';
        $builder = $this->db->table('project_milestones');

        $builder->select('project_milestones.*, project_ucn.name as ucn_name, client.client_name as client_name,u.name as project_manager, u.id_user as user_id');
        $builder->join('project_ucn', 'project_ucn.ucn_id = project_milestones.ucn_id', 'left');
        $builder->join('projects_assignment as pa', 'pa.db_id = project_ucn.ucn_id AND pa.type_of_assignment=5 AND pa.status=1', 'inner');
        $builder->join('users as u', 'u.id_user = pa.user_id', 'left');
        $builder->join('client', 'client.id_c = project_ucn.client', 'left');
        $builder->where('project_milestones.status !=', 0);
        $builder->groupBy('project_milestones.milestone_id');
        $builder->where('project_milestones.invoicing_dt >=', $startDate);
        $builder->where('project_milestones.invoicing_dt <=', $endDate);
        $builder->orderBy('project_milestones.ucn_id', 'ASC');
        $query = $builder->get();
        return $query->getResultArray() ?? [];
    }
}
