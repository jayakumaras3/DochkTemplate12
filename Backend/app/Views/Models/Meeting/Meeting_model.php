<?php

namespace App\Models\Meeting;

use CodeIgniter\Model;

class Meeting_model extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = db_connect(); // Loading database
        // OR $this->db = \Config\Database::connect();
    }
    function addmeetingagenda($newdata)
    {
        $builder = $this->db->table('meeting_agenda_header');
        $builder->insert($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function meetingagendaheader_view($projectid)
    {
        $builder = $this->db->table('meeting_agenda_header as m');
        $builder->select('m.*,p.projectname,t.timezone_pname');
        $builder->join('projects as p', 'p.projectid = m.fk_project_id and p.status=1', 'left');
        $builder->join('users as u', 'u.id_user = m.createdby and m.status=1', 'left');
        $builder->join('timezone as t', 't.id_t = u.timezone', 'left');
        $builder->where('m.fk_project_id', $projectid);
        $builder->where('m.type', 1);
        $builder->where('m.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function meetingallagendaheader_view($client)
    {
        $builder = $this->db->table('meeting_agenda_header as m');
        $builder->select('m.*,p.projectname,t.timezone_pname');
        $builder->join('projects as p', 'p.projectid = m.fk_project_id and p.status=1', 'left');
        $builder->join('users as u', 'u.id_user = m.createdby and m.status=1', 'left');
        $builder->join('timezone as t', 't.id_t = u.timezone', 'left');
        $builder->where('m.fk_project_id', $client);
        $builder->where('m.type', 2);
        $builder->where('m.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function editagenda_header($id_m)
    {
        $builder = $this->db->table('meeting_agenda_header as m');
        $builder->select('m.*');
        $builder->where('m.id_m', $id_m);
        $builder->where('m.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function updateagenda_header($newdata, $id_m)
    {
        $builder = $this->db->table('meeting_agenda_header as m');
        $builder->where('m.id_m', $id_m);
        $builder->where('m.status', 1);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function delagendaheader($newdata, $id_m)
    {
        $builder = $this->db->table('meeting_agenda_header as m');
        $builder->where('m.id_m', $id_m);
        $builder->where('m.status', 1);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function addprojectstatus($newdata)
    {
        $builder = $this->db->table('meeting_agenda');
        $builder->insert($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function meeting_agenda_view($id_m)
    {
        $builder = $this->db->table('meeting_agenda as ma');
        $builder->select('ma.*,ah.*');
        $builder->join('meeting_agenda_header as ah', 'ah.id_m = ma.fk_id_m and ah.status=1', 'left');
        $builder->where('ma.fk_id_m', $id_m);
        $builder->where('ma.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function meeting_agenda_edit($id_ma)
    {
        $builder = $this->db->table('meeting_agenda as ma');
        $builder->select('ma.*,ah.*');
        $builder->join('meeting_agenda_header as ah', 'ah.id_m = ma.fk_id_m and ah.status=1', 'left');
        $builder->where('ma.id_ma', $id_ma);
        $builder->where('ma.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function updatemeetingagenda($newdata, $id_ma)
    {
        $builder = $this->db->table('meeting_agenda as m');
        $builder->where('m.id_ma', $id_ma);
        $builder->where('m.status', 1);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function delmeetingagenda($newdata, $id_ma)
    {
        $builder = $this->db->table('meeting_agenda as m');
        $builder->where('m.id_ma', $id_ma);
        $builder->where('m.status', 1);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function meetingagendaclient_view($id_m)
    {
        $builder = $this->db->table('meeting_agenda_header as m');
        $builder->select('m.*,ma.*,p.projectname');
        $builder->join('meeting_agenda as ma', 'ma.fk_id_m = m.id_m and ma.status=1', 'left');
        $builder->join('projects as p', 'p.projectid = m.fk_project_id and p.status=1', 'left');
        $builder->where('m.id_m', $id_m);
        $builder->where('m.status', 1);
        $data = $builder->get()->getResultArray();
        // print_r($data);
        // exit();
        return $data;
    }
    function projectmeetingagenda($project_id)
    {
        $builder = $this->db->table('meeting_agenda_header as m');
        $builder->select('m.*,p.projectname');
        $builder->join('projects as p', 'p.projectid = m.fk_project_id and p.status=1', 'left');
        $builder->where('m.fk_project_id', $project_id);
        $builder->where('m.status', 1);
        $data = $builder->get()->getResultArray();
        // print_r($data);
        // exit();
        return $data;
    }
    function meetingagendastatus($project_id)
    {
        $builder = $this->db->table('meeting_agenda_header as m');
        $builder->select('m.*,ma.*,p.projectname');
        $builder->join('meeting_agenda as ma', 'ma.fk_id_m = m.id_m and ma.status=1', 'left');
        $builder->join('projects as p', 'p.projectid = m.fk_project_id and p.status=1', 'left');
        $builder->where('m.fk_project_id', $project_id);
        $builder->where('m.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function updateremarksformat($value, $column, $id)
    {
        $builder = $this->db->table('meeting_agenda as ma');
        $builder->set($column, $value);
        $builder->where('ma.id_ma', $id);
        $builder->update();
        $data = $builder->get()->getResultArray();
        //print_r($data);
        if (!empty($data)) {
            $data['status'] = "OK";
        } else {
            $data['status'] = "Error";
        }
        return $data;
    }
    function copyagenda_header($id_m)
    {
        $builder = $this->db->table('meeting_agenda_header as m');
        $builder->select('m.*,ma.*');
        $builder->join('meeting_agenda as ma', 'ma.fk_id_m = m.id_m and ma.status=1', 'inner');
        $builder->where('m.id_m', $id_m);
        $builder->where('m.status', 1);
        $data = $builder->get()->getResultArray();
        // print_r($data);
        // exit();
        if ($data) {
            $fk_project_id = $data[0]['fk_project_id'];
            $time = $data[0]['time'];
            $description = $data[0]['description'] . '_copy';
            $start_date = $data[0]['start_date'];
            $attendees = $data[0]['attendees'];
            $type = $data[0]['type'];
            $meeting_link = isset($data[0]['meeting_link']) ? $data[0]['meeting_link'] : '';
            $createdby = session()->get('id_user');
            $createdon = time();
            $db = \Config\Database::connect();
            // print_r($data);
            // exit();
            $q = "INSERT INTO meeting_agenda_header VALUES ('','$fk_project_id','$start_date','$time','$description','$attendees','$meeting_link','1', '$createdby','$createdon','0','0',$type,'0','0')";
            if ($db->query($q)) {
                $insert_id = $db->InsertID();
                //$insertdata = [];

                foreach ($data as $eachagendadata) {

                    $insertstatus = [
                        'fk_id_m' => $insert_id,
                        'project_status' => isset($eachagendadata['project_status']) ? $eachagendadata['project_status'] : '',
                        'completion_dt' => isset($eachagendadata['completion_dt']) ? $eachagendadata['completion_dt'] : '',
                        'status' => 1,
                        'createdby' => session()->get('id_user'),
                        'createdon' => time(),
                        'last_updated_by' => session()->get('id_user'),
                        'last_updated_on' => time()
                    ];
                    $db = \Config\Database::connect();
                    $builder = $this->db->table('meeting_agenda');
                    $builder->insert($insertstatus);
                    $headerdata = $builder->get()->getResultArray();
                }
            }
            return $headerdata;
        } else {
            $builder = $this->db->table('meeting_agenda_header as m');
            $builder->select('m.*');
            //$builder->join('meeting_agenda as ma', 'ma.fk_id_m = m.id_m and ma.status=1', 'inner');
            $builder->where('m.id_m', $id_m);
            $builder->where('m.status', 1);
            $data = $builder->get()->getResultArray();
            if ($data) {
                $fk_project_id = $data[0]['fk_project_id'];
                $time = $data[0]['time'];
                $description = $data[0]['description'] . '_copy';
                $start_date = $data[0]['start_date'];
                $attendees = $data[0]['attendees'];
                $type = $data[0]['type'];
                $meeting_link = isset($data[0]['meeting_link']) ? $data[0]['meeting_link'] : '';
                $createdby = session()->get('id_user');
                $createdon = time();
                $db = \Config\Database::connect();
                $q = "INSERT INTO meeting_agenda_header VALUES ('','$fk_project_id','$start_date','$time','$description','$attendees','$meeting_link','1', '$createdby','$createdon','0','0',$type)";
                if ($db->query($q)) {
                    return $q;
                }
            }
        }
    }
    function getprojectusers($projectid)
    {
        $builder = $this->db->table('projects_details as pd');
        $builder->select('u.name,u.id_user');
        $builder->join('users as u', 'u.username = pd.users and u.valid=1', 'left');
        $builder->where('pd.projectid', $projectid);
        $builder->groupBy('u.username');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getclientusers()
    {
        $client = session()->get('client');
        $TQ = '1';
        $users = [$client, $TQ];
        $builder = $this->db->table('dropdown_users as du');
        $builder->select('u.name,u.id_user');
        $builder->join('users as u', 'u.id_user = du.fk_id_user and u.valid=1', 'left');
        $builder->whereIn('du.fk_id_d', $users);
        // $builder->where('du.fk_id_d', 1);
        $builder->groupBy('u.username');
        $data = $builder->get()->getResultArray();
        return $data;
    }
}
