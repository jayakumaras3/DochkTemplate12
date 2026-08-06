<?php

namespace App\Models\Project_Manage;

use CodeIgniter\Model;

class PM_PO_model extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = db_connect(); // Loading database
    }
    public function get_purchase_order_list($user)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('purchase_order as du');
        $builder->select('du.*, c.client_name as client_name');
        $builder->join('projects_assignment as pa', 'pa.db_id = du.po_id', 'left');
        $builder->join('client as c', 'c.id_c = du.client_id', 'left');
        $builder->join('users as u', 'u.id_user = du.account_manager', 'left');
        $builder->where('pa.type_of_assignment', 4);
        $builder->where('pa.user_id', session()->get('id_user'));
        $builder->where('du.status !=', 0);
        $builder->orderBy('du.po_id', 'DESC');
        $builder->limit(20);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function add_new_purchase_order($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('purchase_order');
        $builder->insert($newdata);
        $insertID = $db->insertID();
        if ($insertID) {
            $newdata = [
                'type_of_assignment' => 4,
                'db_id' => $insertID,
                'user_id' => session()->get('id_user'),
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
    public function add_milestone($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_milestones');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        $data = true;
        return $data;
    }

    public function add_ucn_link($newdata)
    {
        $builder = $this->db->table('project_ucn_link');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        $data = true;
        return $data;
    }
    public function add_ucn($newdata, $po_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_ucn');
        $builder->insert($newdata);
        $insertID = $db->insertID();
        if ($insertID) {
            $paunewdata = [
                'type_of_assignment' => 5,
                'db_id' => $insertID,
                'user_id' => session()->get('id_user'),
                'created_on' => time(),
                'created_by' => session()->get('id_user'),
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
                'status' => '1'
            ];
            $builder = $this->db->table('projects_assignment');
            $builder->insert($paunewdata);

            $add_link = [
                'ucn' => $insertID,
                'type_of_link' => 2,
                'table_id' => $po_id,
                'status' => 1,
                'created_on' => time(),
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user')
            ];
            $builder = $this->db->table('project_ucn_link');
            $builder->insert($add_link);

            $pnewdata = [

                'ucn' => $insertID,
                'projectname' => $newdata['name'],
                'project_type' => 1,
                'wip' => 100,
                'client' => $newdata['client'],
                'start_date' => date('Y-m-d'),
                'percent' => 0,
                'createdon' => time(),
                'createdby' => session()->get('id_user'),
                'last_updated_on' => time(),
                'last_updated_by' => session()->get('id_user'),
                'status' => 1
            ];
            $builder = $this->db->table('projects');
            $builder->insert($pnewdata);
            $projectinsertID = $db->insertID();
            // exit();
            if ($projectinsertID) {
                $panewdata = [
                    'type_of_assignment' => 1,
                    'db_id' => $projectinsertID,
                    'user_id' => session()->get('id_user'),
                    'created_on' => time(),
                    'created_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'status' => '1'
                ];
                $builder = $this->db->table('projects_assignment');
                $builder->insert($panewdata);


                $scnewdata = [
                    'project_id' => $projectinsertID,
                    'type' => 10,
                    'course_name' => $newdata['name'],
                    'duration' => 10,
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    'last_updated_on' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'status' => '1'
                ];
                $builder = $this->db->table('scorm_courses');
                $builder->insert($scnewdata);

                $data['course_id'] = $db->insertID();
                if (isset($data['course_id'])) {
                    $scourse_id = $data['course_id'];
                    $scorm_courses_assigned = [
                        'client_id' => session()->get('client'),
                        'course_id' => $scourse_id,
                        'editable' => 1,
                        'status' => 1,
                        'createdby' => session()->get('id_user'),
                        'createdon' => time(),
                    ];
                    $builder = $this->db->table('scorm_courses_assigned');
                    $builder->insert($scorm_courses_assigned);
                    $userlevel = session()->get('userlevel');
                    $arrayuserlevel = array_map('intval', explode(',', $userlevel));
                    if (in_array('4', $arrayuserlevel)) {
                        $builder = $this->db->table('projects as p');
                        $builder->select('p.client');
                        $builder->where('p.projectid', $projectinsertID);
                        $clientasssigndata = $builder->get()->getResultArray();
                        if (!empty($clientasssigndata)) {
                            $client = $clientasssigndata[0]['client'];
                            $scorm_courses_assigned = [
                                'client_id' => $client,
                                'course_id' => $scourse_id,
                                'editable' => 1,
                                'status' => 1,
                                'createdby' => session()->get('id_user'),
                                'createdon' => time(),
                            ];
                            $builder = $this->db->table('scorm_courses_assigned');
                            $builder->insert($scorm_courses_assigned);
                        }
                    }


                    $scorm_users_courses_assigned = [
                        'id_user' => session()->get('id_user'),
                        'course_id' => $scourse_id,
                        'status' => 1,
                        'createdby' => session()->get('id_user'),
                        'createdon' => time(),
                    ];
                    $builder = $this->db->table('scorm_users_courses_assigned');
                    $builder->insert($scorm_users_courses_assigned);

                    $timestamp = time();
                    $hash = hash('sha256', $scourse_id . '' . $timestamp, false);
                    $db = \Config\Database::connect();
                    $builder = $this->db->table('scorm_courses as sc');
                    $builder->set('sc.hash', $hash);
                    $builder->where('sc.scourse_id', $scourse_id);
                    $builder->update();
                }
            }
            $createdname = session()->get('name');
            $to = 'pramod.c@TouchstoneLC.com,chandana.kemparaj@TouchstoneLC.com,shrikant.msp@touchstonelc.com';
            $subject = 'UCN ID: ' . $insertID . ' Created Today';
            $message = "Hi,<br><br>";
            $message .= "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>
                <tr>
                    <th>UCN ID</th>
                    <th>UCN Name</th>
                    <th>Created By</th>
                    <th>Created On</th>
                </tr>
               <tr>
                    <td>{$insertID}</td>
                    <td>{$newdata['name']}</td>
                    <td>{$createdname}</td>
                    <td>" . date('Y-m-d H:i:s') . "</td>
                 </tr>";


            $message .= "</table><br><br>";

            $message .= 'Regards,<br>';
            $message .= 'Dochek Team';

            $email = \Config\Services::email();
            $email->setFrom('do-not-reply@touchstonelc.com', 'Dochek');
            $email->setTo($to);
            $email->setSubject($subject);
            $email->setMessage($message);
            if ($email->send()) {
                // session()->setFlashdata('success', 'Mail Sent.');
            } else {
                $data = $email->printDebugger(['headers']);
                print_r($data);
                exit();
            }
        }
        return $insertID;
    }

    public function get_po_details($po_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('purchase_order as du');
        $builder->select('du.*, c.client_name as client_name');
        $builder->join('client as c', 'c.id_c = du.client_id', "left");
        $builder->where('du.po_id', $po_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function get_po_upload_details($po_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('po_upload as du');
        $builder->select('du.*');
        $builder->where('du.po_id', $po_id);
        $builder->where('du.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function get_ucn_edit_details($ucn_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_ucn as ucn');
        $builder->select('ucn.*, c.client_name as client_name, p.short_name as proposal_name, pp.proposal_name as pricing_name');
        $builder->join('client as c', 'c.id_c = ucn.client', "left");
        $builder->join('proposals as p', 'p.proposal_id = ucn.proposal_id', "left");
        $builder->join('project_pricing as pp', 'pp.ppid = ucn.pricing_id', "left");
        $builder->where('ucn.ucn_id', $ucn_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function get_milestone_details($po_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_milestones as pm');
        $builder->select('pm.*, po.po_value as po_val, ucn.name as usnname');
        $builder->join('purchase_order as po', 'po.po_id = pm.po_id', "left");
        $builder->join('project_ucn as ucn', 'ucn.ucn_id = pm.ucn_id', "left");
        $builder->where('pm.po_id', $po_id);
        $builder->where('pm.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function get_ucn_details($po_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_ucn as ucn');
        $builder->select('ucn.*');
        $builder->join('project_ucn_link as pul', 'pul.ucn = ucn.ucn_id', "left");
        $builder->where('pul.table_id', $po_id);
        $builder->where('pul.type_of_link', 2);
        $builder->where('pul.status !=', 0);
        $builder->where('ucn.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function update_purchase_order($newdata, $po_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('purchase_order as po');
        $builder->where('po.po_id', $po_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function update_po_upload($newdata)
    {
        // print_r($newdata);
        // exit();
        $db = \Config\Database::connect();
        $builder = $this->db->table('po_upload');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        return true;
    }
    public function delpo_upload($newdata, $po_uid)
    {
        // print_r($newdata);
        // exit();
        $db = \Config\Database::connect();
        $builder = $this->db->table('po_upload');
        $builder->where('po_uid', $po_uid);
        $builder->update($newdata);
        $data['status'] = 'OK';
        return $data;
    }
    public function update_ucn_data($newdata, $ucn_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_ucn as ucn');
        $builder->where('ucn.ucn_id', $ucn_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function update_milestone($newdata, $milestone_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('project_milestones as pm');
        $builder->where('pm.milestone_id', $milestone_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function updateUcndata($newdata, $ucn_id)
    {
        $builder = $this->db->table('project_ucn as ucn');
        $builder->where('ucn.ucn_id', $ucn_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        if ($newdata['status'] == '10') {
            $createdname = session()->get('name');
            // $to = 'keerthana.mk@TouchstoneLC.com';
            $to = 'pramod.c@TouchstoneLC.com,chandana.kemparaj@TouchstoneLC.com,shrikant.msp@touchstonelc.com';
            $subject = 'UCN ID: ' . $ucn_id . ' Closed Today';
            $message = "Hi,<br><br>";
            $message .= "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>
                <tr>
                    <th>UCN ID</th>
                    <th>UCN Name</th>
                    <th>Closed By</th>
                    <th>Closed On</th>
                </tr>
               <tr>
                    <td>{$ucn_id}</td>
                    <td>{$newdata['name']}</td>
                    <td>{$createdname}</td>
                    <td>" . date('Y-m-d H:i:s') . "</td>
                 </tr>";


            $message .= "</table><br><br>";

            $message .= 'Regards,<br>';
            $message .= 'Dochek Team';

            $email = \Config\Services::email();
            $email->setFrom('do-not-reply@touchstonelc.com', 'Dochek');
            $email->setTo($to);
            $email->setSubject($subject);
            $email->setMessage($message);
            if ($email->send()) {
                // session()->setFlashdata('success', 'Mail Sent.');
            } else {
                $data = $email->printDebugger(['headers']);
                print_r($data);
                exit();
            }

        }
        return $data;
    }
}
