<?php

namespace App\Models\Project_Manage;

use CodeIgniter\Model;

class PM_Effort_Tracker_Model extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = db_connect(); // Loading database
    }

    public function get_effort_data(string $workweek, int $user_id)
    {
        $builder = $this->db->table('project_effort as effort_tracker');
        $builder->select('effort_tracker.*, projects.projectname as project_name');
        $builder->join('projects', 'effort_tracker.projectid = projects.projectid', 'left');
        $builder->where("effort_tracker.work_week", $workweek);
        $builder->where('effort_tracker.status !=', 0); // Exclude deleted records
        $builder->where('effort_tracker.user', $user_id);
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_UCN(int $project_id)
    {
        $builder = $this->db->table('projects');
        $builder->select('ucn');
        $builder->where('projectid', $project_id);
        $builder->where('status !=', 0); // Exclude deleted records
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function get_active_projects()
    {
        $builder = $this->db->table('projects');
        $builder->select('projectid, projectname');
        $builder->where('status !=', 0); // Exclude deleted records
        $builder->where('status !=', 3); // Exclude on hold projects
        $builder->where('status !=', 4); // Exclude closed projects
        $builder->orderBy('projectname', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_projects_with_access(int $user_id)
    {
        $builder = $this->db->table('project_user as pusers');
        $builder->select('projects.projectid, projects.projectname, projects.status, projects.status as project_status, pusers.status as pusers_status, pusers.pu_id, pusers.description as description');
        $builder->join('projects', 'pusers.project_id = projects.projectid', 'left');
        $builder->where('pusers.status !=', 0);
        $builder->where('projects.status !=', 0);
        $builder->where('projects.status !=', 3); // Exclude on hold projects
        $builder->where('projects.status !=', 4); // Exclude closed projects
        if ($user_id !== null) {
            $builder->where('pusers.user_id', $user_id);
        }
        // $builder->groupBy('projects.projectid, projects.projectname'); // Group by project ID and name to avoid duplicates
        $builder->orderBy('projects.projectname', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function delete_project_access_request(int $pu_id)
    {
        $builder = $this->db->table('project_user');
        $builder->where('pu_id', $pu_id);
        return $builder->delete();
    }

    public function add_project_access_request(array $newdata)
    {
        $builder = $this->db->table('project_user');
        return $builder->insert($newdata) ? $this->db->insertID() : false;
    }

    public function get_pending_access_requests()
    {
        $builder = $this->db->table('project_user');
        $builder->select('project_user.*, u.name as requested_by, u.last_name as requested_by_last_name, projects.projectname');
        $builder->join('users as u', 'project_user.user_id = u.id_user', 'left');
        $builder->join('projects', 'project_user.project_id = projects.projectid', 'left');
        $builder->where('project_user.status', 2); // Only pending requests
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_access_request_by_id(int $pu_id)
    {
        $builder = $this->db->table('project_user');
        $builder->where('pu_id', $pu_id);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function update_project_user_status(int $pu_id, int $status)
    {
        $builder = $this->db->table('project_user');
        $builder->where('pu_id', $pu_id);
        return $builder->update(['status' => $status]);
    }

    public function get_my_active_projects(int $user_id)
    {
        $builder = $this->db->table('projects as projects');
        $builder->select('projects.projectid');
        $builder->join('projects_assignment as pa', 'pa.db_id = projects.ucn', 'left');
        if( $user_id==1){
           // $builder->where('pa.user_id', 1);
        } else {
            $builder->where('pa.user_id', $user_id);
        }
        $builder->where('pa.status !=', 0);
        $builder->where('projects.status !=', 3); // Exclude on hold projects
        $builder->where('projects.status !=', 4); // Exclude closed projects
        $builder->where('projects.status !=', 0); // Exclude deleted records
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_projects(int $user_id)
    {
        $builder = $this->db->table('project_user as pusers');
        $builder->select('projects.projectid, projects.projectname');
        $builder->join('projects', 'pusers.project_id = projects.projectid', 'left');
        $builder->where('pusers.status', 1); // Only active project-user relationships
        $builder->where('projects.status !=', 0);
        $builder->where('projects.status !=', 3); // Exclude on hold projects
        $builder->where('projects.status !=', 4); // Exclude closed projects
        if ($user_id !== null) {
            $builder->where('pusers.user_id', $user_id);
        }
        $builder->groupBy('projects.projectid, projects.projectname'); // Group by project ID and name to avoid duplicates
        $builder->orderBy('projects.projectname', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function remove_user_from_project(int $pu_id)
    {
        $builder = $this->db->table('project_user');
        $builder->where('pu_id', $pu_id);
        return $builder->delete();
    }

    public function add_new_effort(array $data)
    {
        $builder = $this->db->table('project_effort');
        return $builder->insert($data) ? $this->db->insertID() : false;
    }

    public function get_effort_data_by_id(int $pe_id)
    {
        $builder = $this->db->table('project_effort');
        $builder->select('*');
        $builder->where('pe_id', $pe_id);
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function update_effort(int $pe_id, array $data)
    {
        $builder = $this->db->table('project_effort');
        $builder->where('pe_id', $pe_id);
        return $builder->update($data);
    }

    public function get_effort_data_by_project(int $project_id)
    {
        $builder = $this->db->table('project_effort as project_effort');
        $builder->select('users.id_user as user_id, users.name as name, users.last_name as last_name, sum(project_effort.effort) as total_effort');
        $builder->where('project_effort.projectid', $project_id);
        $builder->join('users', 'project_effort.user = users.id_user', 'left');
        $builder->where('project_effort.status !=', 0); // Exclude deleted records
        $builder->where('project_effort.status !=', 3); // Exclude inactive records
        $builder->where('project_effort.status !=', 4); // Exclude records rejected by PM
        $builder->groupBy('project_effort.user'); // Group by user and work week
        $builder->orderBy('project_effort.work_week', 'DESC'); // Order by work week descending
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_user_info(int $user_id)
    {
        $builder = $this->db->table('users');
        $builder->select('name, last_name');
        $builder->where('id_user', $user_id);
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function view_user_effort_by_project(int $user_id, int $project_id)
    {
        $builder = $this->db->table('project_effort as project_effort');
        $builder->select('project_effort.*');
        $builder->where('project_effort.user', $user_id);
        $builder->where('project_effort.projectid', $project_id);
        $builder->where('project_effort.status !=', 0); // Exclude deleted records
        $builder->where('project_effort.status !=', 3); // Exclude inactive records
        $builder->where('project_effort.status !=', 4); // Exclude records rejected by PM
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_all_users()
    {
        $builder = $this->db->table('users');
        $builder->select('id_user, name, last_name');
        $builder->where('client_id', 1);
        $builder->where('region', 1);
        $builder->where('manager !=', 1202); // Exclude users without a manager
        $builder->where('valid !=', 0); // Exclude deleted users
        $builder->orderBy('name', 'ASC'); // Order by name ascending    
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function add_user_to_project(array $user_ids, int $project_id)
    {
        $builder = $this->db->table('project_user');
        $data = [];
        foreach ($user_ids as $user_id) {
            $data[] = [
                'user_id' => (int) $user_id,
                'project_id' => $project_id,
                'status' => 1, // Active status
            ];
        }
        return $builder->insertBatch($data);
    }
    public function get_users_by_project(int $project_id)
    {
        $builder = $this->db->table('project_user as pusers');
        $builder->select('pusers.pu_id, users.id_user, users.name, users.last_name, pusers.status as pusers_status');
        $builder->join('users', 'pusers.user_id = users.id_user', 'left');
        $builder->where('pusers.project_id', $project_id);
        $builder->where('pusers.status !=', 0);
        $builder->where('pusers.status !=', 3);
        $builder->orderBy('users.id_user', 'ASC'); // Order by name ascending
        // $builder->groupBy('users.id_user, users.name, users.last_name'); // Group by user ID, name, and last name to avoid duplicates
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_team_effort_data(int $user_id)
    {
        $builder = $this->db->table('project_effort as effort_tracker');
        $builder->select('effort_tracker.*, projects.projectname as project_name, team_members.name as fname, team_members.last_name as user_last_name');
        $builder->join('projects', 'effort_tracker.projectid = projects.projectid', 'left');
        $builder->join('users as team_members', 'team_members.id_user = effort_tracker.user', 'left'); // Exclude inactive records
        $builder->where('effort_tracker.status', 1); // Exclude deleted records
        $builder->orderBy('effort_tracker.work_week', 'DESC'); // Order by work week descending

        $builder->groupStart();
        $builder->where('team_members.manager', $user_id);
        $builder->groupEnd();
        $query = $builder->get();
        return $query->getResultArray();
    }


    public function get_my_team(int $user_id, string $selected_week)
    {
        $builder = $this->db->table('users');
        $builder->select('id_user, name, last_name, IFNULL(SUM(effort_tracker.effort), 0) as total_effort');
        $builder->join('project_effort as effort_tracker', 'users.id_user = effort_tracker.user AND effort_tracker.work_week = ' . $this->db->escape($selected_week), 'left');
        $builder->where('users.manager', $user_id);
        $builder->where('users.valid !=', 0); // Exclude deleted users
        $builder->orderBy('users.name', 'ASC'); // Order by name ascending    
        $builder->groupStart();
        $builder->where('effort_tracker.status', 1); // Include only approved efforts
        $builder->orWhere('effort_tracker.status', 2); // Include only approved efforts
        $builder->orWhere('effort_tracker.status IS NULL'); // Include users with no efforts
        $builder->groupEnd();
        $builder->groupBy('users.id_user'); // Group by user ID, name, and last name to avoid duplicates
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_all_data(string $selected_week)
    {
        $builder = $this->db->table('users');
        $builder->select('users.id_user, users.name, users.last_name, mng.name as manager_name, mng.last_name as manager_last_name, mng.id_user as manager_id,
            IFNULL(SUM(CASE WHEN effort_tracker.status = 1 THEN effort_tracker.effort ELSE 0 END), 0) as active_effort,
            IFNULL(SUM(CASE WHEN effort_tracker.status = 2 THEN effort_tracker.effort ELSE 0 END), 0) as approved_effort,
            IFNULL(SUM(CASE WHEN effort_tracker.status = 3 THEN effort_tracker.effort ELSE 0 END), 0) as rejected_effort');
        $builder->join('project_effort as effort_tracker', 'users.id_user = effort_tracker.user AND effort_tracker.work_week = ' . $this->db->escape($selected_week), 'left');
        $builder->join('users as mng', 'mng.id_user = users.manager', 'left');
        $builder->where('users.valid !=', 0); // Exclude deleted users
        $builder->where('users.region', 1); // Exclude users outside the region
        $builder->where('users.client_id', 1); // Include only users with client_id = 1
        $builder->orderBy('mng.name', 'ASC'); // Order by name ascending
        $builder->groupStart();
        $builder->where('effort_tracker.status !=', 0); // Exclude deleted efforts
        $builder->orWhere('effort_tracker.status IS NULL'); // Include users with no efforts
        $builder->groupEnd();
        $builder->groupBy('users.id_user'); // Group by user ID, name, and last name to avoid duplicates
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_weekly_effort_totals()
    {
        $builder = $this->db->table('project_effort as effort_tracker');
        $builder->select(
            "effort_tracker.work_week, " .
                "IFNULL(SUM(effort_tracker.effort), 0) as total_effort, " .
                "IFNULL(SUM(CASE WHEN effort_tracker.projectid = 1 THEN effort_tracker.effort ELSE 0 END), 0) as general_effort, " .
                "IFNULL(SUM(CASE WHEN effort_tracker.projectid != 1 THEN effort_tracker.effort ELSE 0 END), 0) as project_effort, " .
                "COUNT(DISTINCT effort_tracker.user) as employee_count"
        );
        $builder->join('users', 'users.id_user = effort_tracker.user', 'left');
        $builder->where('users.valid !=', 0); // Exclude deleted users
        $builder->where('users.region', 1); // Exclude users outside the region
        $builder->where('users.client_id', 1); // Include only users with client_id = 1
        $builder->whereIn('effort_tracker.status', [1, 2]); // Active and Approved efforts only
        $builder->groupBy('effort_tracker.work_week');
        $builder->orderBy('effort_tracker.work_week', 'DESC');
        $query = $builder->get();
        return $query->getResultArray();
    }


    public function get_member_effort(int $member_id, string $selected_week)
    {
        $builder = $this->db->table('project_effort as effort_tracker');
        $builder->select('effort_tracker.*, projects.projectname as project_name');
        $builder->join('projects', 'effort_tracker.projectid = projects.projectid', 'left');
        $builder->where('effort_tracker.user', $member_id);
        $builder->where('effort_tracker.work_week', $selected_week);
        $builder->where('effort_tracker.status !=', 0); // Exclude deleted records
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_member_info(int $member_id)
    {
        $builder = $this->db->table('users');
        $builder->select('id_user, name, last_name');
        $builder->where('id_user', $member_id);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function get_effort_owner(int $pe_id): ?array
    {
        $builder = $this->db->table('project_effort as effort_tracker');
        $builder->select('effort_tracker.pe_id, effort_tracker.user, users.manager');
        $builder->join('users', 'users.id_user = effort_tracker.user', 'left');
        $builder->where('effort_tracker.pe_id', $pe_id);
        $query = $builder->get();
        return $query->getRowArray() ?: null;
    }

    public function get_member_weekly_totals(int $member_id, int $weeks = 10)
    {
        $builder = $this->db->table('project_effort as effort_tracker');
        $builder->select('effort_tracker.work_week, IFNULL(SUM(effort_tracker.effort), 0) as total_effort');
        $builder->where('effort_tracker.user', $member_id);
        $builder->where('effort_tracker.status !=', 0); // Exclude deleted records
        $builder->where('effort_tracker.status !=', 4); // Exclude PM rejected records
        $builder->where('effort_tracker.status !=', 3); // Exclude TL rejected records
        $builder->where('effort_tracker.status !=', 10); // Exclude deleted
        $builder->groupBy('effort_tracker.work_week');
        $builder->orderBy('effort_tracker.work_week', 'DESC');
        $builder->limit($weeks);
        $query = $builder->get();
        return $query->getResultArray();
    }
}
