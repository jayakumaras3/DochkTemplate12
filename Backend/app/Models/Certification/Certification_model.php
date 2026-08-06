<?php

namespace App\Models\Certification;

use CodeIgniter\Model;

class Certification_model extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = db_connect(); // Loading database
    }
    function get_all_certifications($client)
    {
        $user_id = session()->get('id_user');

        $builder = $this->db->table('certificates_clients as cert_clients');

        $builder->select("
            cert.*,
            u.name as createdby,
            ANY_VALUE(cert_assign.user_id),
            MAX(cert_assign.status) as certification_status,
            COUNT(DISTINCT mp.mp_id) as learning_plan_count,
            COUNT(DISTINCT mp.mp_id, sc.scourse_id) as course_count,
            ANY_VALUE(pm.amount) as amount,
                cp.payment_status,COUNT(
    DISTINCT CASE
        WHEN suc.course_status = 2 THEN CONCAT(mp.mp_id, '-', sc.scourse_id)
    END
) AS completed_course_count,MAX(sud.raw) AS assessment_score
        ");

        $builder->join('certificates as cert', 'cert.cert_id = cert_clients.cert_id', 'left');
        $builder->join('users as u', 'u.id_user = cert.createdby', 'left');
        $builder->join('certificates_assigned_users as cert_assign', 'cert_assign.certificate_id = cert.cert_id and cert_assign.status !=0', 'left');
        $builder->join('certificates_assigned as ca', 'ca.certificate_id = cert.cert_id AND ca.type = 4', 'left');
        $builder->join('marketplace as mp', 'mp.mp_id = ca.course_lp_mp_id AND mp.status != 0', 'left');
        $builder->join('marketplace_courses as mpc', 'mpc.mp_id = mp.mp_id AND mpc.status != 0', 'left');
        $builder->join('scorm_courses as sc', 'sc.scourse_id = mpc.scorm_id AND sc.status = 1', 'left');
        $builder->join('certification_price_mapping as cpm', 'cpm.certificate_id = cert.cert_id AND cpm.is_active = 1', 'left');
        $builder->join('price_master as pm', 'pm.price_id = cpm.price_id', 'left');

        /* User payment */
        $builder->join(
            'certification_payments cp',
            "cp.certificate_id = cert.cert_id
     AND cp.user_id = {$user_id}
     AND cp.payment_status = 'PAID'",
            'left'
        );
        $builder->join(
            'scorm_users_courses_assigned suc',
            "suc.course_id = sc.scourse_id
     AND suc.id_user = {$user_id}
     AND suc.status != 0",
            'left'
        );
        /* Assessment course */
        $builder->join(
            'scorm_users_courses_assigned assessment',
            "assessment.course_id = cert.assessment_course_id
     AND assessment.id_user = {$user_id}
     AND assessment.status != 0",
            'left'
        );

        $builder->join(
            'scorm_user_details sud',
            'sud.user_assign_id = assessment.user_assign_id',
            'left'
        );
        $builder->where('cert_clients.status !=', 0);
        $builder->where('cert.type', 4);
        $builder->where('cert_clients.client_id', $client);

        $builder->groupBy('cert.cert_id');

        /* Unpurchased first */
        $builder->orderBy('(cp.payment_status IS NOT NULL)', 'DESC');

        /* Latest certifications inside each group */
        $builder->orderBy('cert.cert_id', 'DESC');

        $data = $builder->get()->getResultArray();
        return $data;
    }
    //     $builder = $this->db->table('certificates_clients as cert_clients');
    //     $builder->select('cert.*,u.name as createdby,MAX(cert_assign.status) as certification_status,COUNT(DISTINCT mp.mp_id) as learning_plan_count,
    // COUNT(DISTINCT sc.scourse_id) as course_count');
    //     $builder->join('certificates as cert', 'cert.cert_id = cert_clients.cert_id', 'left');
    //     $builder->join('certificates_assigned_users as cert_assign', 'cert_assign.certificate_id = cert.cert_id', 'left');
    //     $builder->join('certificates_assigned as ca', 'ca.certificate_id = cert.cert_id AND ca.type = 4', 'left');
    //     $builder->join('marketplace as mp', 'mp.mp_id = ca.course_lp_mp_id AND mp.status != 0', 'left');
    //     $builder->join('marketplace_courses as mpc', 'mpc.mp_id = mp.mp_id AND mpc.status != 0', 'left');
    //     $builder->join('scorm_courses as sc', 'sc.scourse_id = mpc.scorm_id AND sc.status = 1', 'left');

    //     $builder->join('users as u', 'u.id_user = cert.createdby', 'left');
    //     $builder->where('cert_clients.status !=', 0);
    //     $builder->where('cert_assign.client_id', $client);
    //     $builder->where('cert_assign.user_id', $user_id);
    //     $builder->where('cert.type', 4);
    //     $builder->where('cert_assign.status !=', 0);
    //     $builder->groupBy('cert.cert_id');
    //     $builder->orderBy('MAX(cert_assign.last_updated_on)', 'DESC', false);
    //     // $builder->orderby('desc');
    //     $data = $builder->get()->getResultArray();
    //     return $data;
    function get_all_certification_for_user($client, $user_id)
    {

        $user_id = session()->get('id_user');

        $builder = $this->db->table('certificates_clients as cert_clients');

        $builder->select("
            cert.*,
            u.name as createdby,
            ANY_VALUE(cert_assign.user_id),
            MAX(cert_assign.status) as certification_status,
            COUNT(DISTINCT mp.mp_id) as learning_plan_count,
            COUNT(DISTINCT mp.mp_id, sc.scourse_id) as course_count,
            ANY_VALUE(pm.amount) as amount,
            cp.payment_status,COUNT(
    DISTINCT CASE
        WHEN suc.course_status = 2 THEN CONCAT(mp.mp_id, '-', sc.scourse_id)
    END
) AS completed_course_count,MAX(sud.raw) AS assessment_score
        ");

        $builder->join('certificates as cert', 'cert.cert_id = cert_clients.cert_id', 'left');
        $builder->join('users as u', 'u.id_user = cert.createdby', 'left');
        $builder->join(
            'certificates_assigned_users as cert_assign',
            "cert_assign.certificate_id = cert.cert_id
     AND cert_assign.user_id = {$user_id}
     AND cert_assign.status != 0",
            'left'
        );
        $builder->join('certificates_assigned as ca', 'ca.certificate_id = cert.cert_id AND ca.type = 4', 'left');
        $builder->join('marketplace as mp', 'mp.mp_id = ca.course_lp_mp_id AND mp.status != 0', 'left');
        $builder->join('marketplace_courses as mpc', 'mpc.mp_id = mp.mp_id AND mpc.status != 0', 'left');
        $builder->join('scorm_courses as sc', 'sc.scourse_id = mpc.scorm_id AND sc.status = 1', 'left');
        $builder->join('certification_price_mapping as cpm', 'cpm.certificate_id = cert.cert_id AND cpm.is_active = 1', 'left');
        $builder->join('price_master as pm', 'pm.price_id = cpm.price_id', 'left');

        /* User payment */
        $builder->join(
            'certification_payments cp',
            "cp.certificate_id = cert.cert_id
     AND cp.user_id = {$user_id}
     AND cp.payment_status = 'PAID'",
            'left'
        );
        $builder->join(
            'scorm_users_courses_assigned suc',
            "suc.course_id = sc.scourse_id
     AND suc.id_user = {$user_id}
     AND suc.status != 0",
            'left'
        );
        /* Assessment course */
        $builder->join(
            'scorm_users_courses_assigned assessment',
            "assessment.course_id = cert.assessment_course_id
     AND assessment.id_user = {$user_id}
     AND assessment.status != 0",
            'left'
        );

        $builder->join(
            'scorm_user_details sud',
            'sud.user_assign_id = assessment.user_assign_id',
            'left'
        );
        $builder->where('cert_clients.status !=', 0);
        $builder->where('cert.type', 4);
        $builder->where('cert_clients.client_id', $client);
        // $builder->where('cert_assign.user_id', $user_id);

        $builder->groupBy('cert.cert_id');

        /* Unpurchased first */
        $builder->orderBy('(cp.payment_status IS NOT NULL)', 'DESC');

        /* Latest certifications inside each group */
        $builder->orderBy('cert.cert_id', 'DESC');

        $data = $builder->get()->getResultArray();

        return $data;
    }
    function get_all_certificates_for_user($client, $user_id)
    {
        $builder = $this->db->table('certificates_clients as cert_clients');
        $builder->select('cert.*,u.name as createdby,MAX(cert_assign.status) as certification_status,cert.name as cert_name');
        $builder->join('certificates as cert', 'cert.cert_id = cert_clients.cert_id', 'left');
        $builder->join('certificates_assigned_users as cert_assign', 'cert_assign.certificate_id = cert.cert_id', 'left');
        $builder->join('users as u', 'u.id_user = cert.last_updated_by', 'left');
        $builder->where('cert_clients.status !=', 0);
        $builder->where('cert_assign.client_id', $client);
        $builder->where('cert_assign.user_id', $user_id);
        $builder->where('cert_assign.status !=', 0);
        $builder->groupBy('cert.cert_id');
        $builder->orderBy('MAX(cert_assign.last_updated_on)', 'DESC', false);
        // $builder->orderby('desc');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getassignedclients($certificate_id)
    {
        $builder = $this->db->table('certificates_clients as cert_clients');
        $builder->select('cert_clients.*,c.client_name');
        $builder->join('client as c', 'c.id_c= cert_clients.client_id', 'left');
        $builder->where('cert_clients.cert_id', $certificate_id);
        $builder->where('cert_clients.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getassignedclienttoeditable($certificate_id, $client_id)
    {
        $builder = $this->db->table('certificates_clients as cert_clients');
        $builder->where('cert_clients.cert_id', $certificate_id);
        $builder->where('cert_clients.client_id', $client_id);
        $builder->where('cert_clients.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function delete_assigned_cert_to_client($data_client, $cert_client_id)
    {
        $builder = $this->db->table('certificates_clients as cert_clients');
        $builder->where('cert_client_id', $cert_client_id);
        $builder->update($data_client);
        return true;
    }
    function add_new_certificate($data)
    {
        $builder = $this->db->table('certificates');
        $builder->insert($data);
        $insertID = $this->db->insertID();
        if ($this->db->affectedRows() > 0) {
            if ($insertID) {
                $data = [
                    'certificate_id' => $insertID,
                    'user_id' => session()->get('id_user'),
                    'client_id' => session()->get('client'),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                    'status' => 1
                ];
                $builder = $this->db->table('certificates_assigned_users');
                $builder->insert(array_merge($data, ['user_id' => $data['user_id']]));
            }
            return  $insertID;
        } else {
            return false;
        }
    }

    function link_cert_to_client($data)
    {
        $builder = $this->db->table('certificates_clients');
        $builder->insert($data);
        if ($this->db->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
        // return true;
    }

    function get_certificate_details($certificate_id)
    {
        $builder = $this->db->table('certificates as c');
        $builder->select('c.*,ANY_VALUE(pm.amount) as amount,COUNT(DISTINCT mp.mp_id) as learning_plan_count, COUNT(DISTINCT sc.scourse_id) as course_count');
        $builder->join('certificates_assigned as ca', 'ca.certificate_id = c.cert_id AND ca.type = 4', 'left');
        $builder->join('marketplace as mp', 'mp.mp_id = ca.course_lp_mp_id AND mp.status != 0', 'left');
        $builder->join('marketplace_courses as mpc', 'mpc.mp_id = mp.mp_id AND mpc.status != 0', 'left');
        $builder->join('scorm_courses as sc', 'sc.scourse_id = mpc.scorm_id AND sc.status = 1', 'left');
        $builder->join('certification_price_mapping as cpm', 'cpm.certificate_id = c.cert_id AND cpm.is_active = 1', 'left');
        $builder->join('price_master as pm', 'pm.price_id = cpm.price_id', 'left');
        $builder->where('c.cert_id', $certificate_id);
        // $builder->where('status !=', 0);
        $data = $builder->get()->getResultArray();
        // print_r($data);
        // exit();
        return $data;
    }

    function update_certificate($certificate_id, $data)
    {
        $builder = $this->db->table('certificates');
        $builder->where('cert_id', $certificate_id);
        $builder->update($data);
        return true;
    }

    function get_assigned_courses($client_id)
    {
        $builder = $this->db->table('scorm_courses_assigned as scorm_assigned');
        $builder->select('courses.course_name as name, courses.scourse_id as scourse_id');
        $builder->join('scorm_courses as courses', 'courses.scourse_id = scorm_assigned.course_id', 'left');
        $builder->where('scorm_assigned.status !=', 0);
        $builder->where('scorm_assigned.client_id', $client_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_assigned_lp($client_id, $type)
    {
        $builder = $this->db->table('marketplace_clients as mp_clients');
        $builder->select('mp.mp_name as name, mp.mp_id as scourse_id');
        $builder->join('marketplace as mp', 'mp.mp_id = mp_clients.mp_id', 'left');
        $builder->where('mp_clients.status !=', 0);
        $builder->where('mp.status !=', 0);
        $builder->where('mp.type', $type);
        $builder->where('mp_clients.client_id', $client_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_assigned_details_courses($certificate_id)
    {
        $builder = $this->db->table('certificates_assigned as cert_clients');
        $builder->select('courses.course_name as name, courses.scourse_id as scourse_id, cert_clients.cert_assign_id as cert_assign_id');
        $builder->join('scorm_courses as courses', 'courses.scourse_id = cert_clients.course_lp_mp_id', 'left');
        $builder->where('cert_clients.status !=', 0);
        $builder->where('cert_clients.certificate_id', $certificate_id);
        $builder->where('cert_clients.type', 3);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_assigned_details_lp($certificate_id, $type)
    {
        $builder = $this->db->table('certificates_assigned as cert_clients');
        $builder->select('mp.mp_name as name, mp.mp_id as scourse_id, ANY_VALUE(cert_clients.cert_assign_id) as cert_assign_id');
        $builder->join('marketplace as mp', 'mp.mp_id = cert_clients.course_lp_mp_id', 'left');
        $builder->where('cert_clients.status !=', 0);
        $builder->where('cert_clients.certificate_id', $certificate_id);
        $builder->where('cert_clients.type', $type);
        $builder->groupBy('cert_clients.course_lp_mp_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    /**
     * Get certificate assignment for a specific course/learning-plan/marketplace item
     * Returns single row or null
     */
    function get_certificate_assignment($course_lp_mp_id, $client_id, $type = 3)
    {
        $builder = $this->db->table('certificates_assigned as ca');
        $builder->select('ca.*');
        $builder->where('ca.course_lp_mp_id', $course_lp_mp_id);
        $builder->where('ca.type', $type);
        $builder->where('ca.client_id', $client_id);
        $builder->where('ca.status !=', 0);
        $data = $builder->get()->getRowArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }

    function assign_certificate($data)
    {
        $builder = $this->db->table('certificates_assigned as cert_clients');
        $builder->where('cert_clients.status !=', 0);
        $builder->where('cert_clients.certificate_id', $data['certificate_id']);
        $builder->where('cert_clients.type', $data['type']);
        $builder->where('cert_clients.client_id', $data['client_id']);
        $builder->where('cert_clients.course_lp_mp_id', $data['course_lp_mp_id']);
        $lpdata = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // print_r($userdata);
        // exit();
        if (empty($lpdata)) {
            $builder = $this->db->table('certificates_assigned');
            $builder->insert($data);
            $insertID = $this->db->insertID();
            if ($this->db->affectedRows() > 0) {

                return true;
            } else {
                return false;
            }
        }
        return true;
    }

    function un_assign_certificate($cert_assign_id)
    {
        $builder = $this->db->table('certificates_assigned');
        $builder->where('cert_assign_id', $cert_assign_id);
        $builder->delete();
        return true;
    }
    function get_assigned_users($certificate_id, $type, $client_id)
    {
        $builder = $this->db->table('certificates_assigned_users as cert_assign');
        $builder->select('users.name as first_name, users.last_name, cert_assign.user_id');
        $builder->join('users', 'users.id_user = cert_assign.user_id and users.valid = 1', 'left');
        $builder->where('cert_assign.status !=', 0);
        $builder->where('cert_assign.certificate_id', $certificate_id);
        $builder->where('cert_assign.client_id', $client_id);
        // $builder->groupBy('cert_assign.certificate_id');
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }

    function enroll_user_to_certificate($user_id, $data)
    {
        $builder = $this->db->table('certificates_assigned_users');
        $builder->insert(array_merge($data, ['user_id' => $user_id]));
        return true;
    }
    function assign_users_learning_plan($data)
    {
        $builder = $this->db->table('assign_users_learning_plan');
        $builder->insert($data);
        return true;
    }
    function get_user_assigned_details_lp($course_lp_mp_id, $type, $user_id)
    {
        $builder = $this->db->table('assign_users_learning_plan as cert_clients');
        $builder->select('cert_clients.ulp_id');
        $builder->where('cert_clients.status !=', 0);
        $builder->where('cert_clients.mp_id', $course_lp_mp_id);
        $builder->where('cert_clients.type', $type);
        $builder->where('cert_clients.user_id', $user_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function delete_user_from_certificate($user_id, $certificate_id)
    {
        $builder = $this->db->table('certificates_assigned_users');
        $builder->where('user_id', $user_id);
        $builder->where('certificate_id', $certificate_id);
        // $builder->where('type', 4);
        $builder->update(['status' => 0]);
        if ($this->db->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
        return true;
    }
    public function updateCertificationStatus($certificate_id, $id_user, $learningarrayid, $type)
    {
        if (!is_array($learningarrayid)) {
            $learningarrayid = explode(',', $learningarrayid);
        }

        if (empty($learningarrayid)) return false;

        $total = count($learningarrayid);
        $completed = 0;
        $inProgress = 0;

        foreach ($learningarrayid as $mp_id) {
            $row = $this->db->table('assign_users_learning_plan')
                ->select('status')
                ->where('user_id', $id_user)
                ->where('mp_id', $mp_id)
                ->get()
                ->getRowArray();

            if ($row) {
                if ($row['status'] == 3) $completed++;
                elseif ($row['status'] == 2) $inProgress++;
            }
            // missing row → treat as not started
        }

        if ($completed == $total) {
            $finalStatus = 3; // all completed
        } elseif ($completed > 0 || $inProgress > 0) {
            $finalStatus = 2; // in progress
        } else {
            $finalStatus = 1; // not started
        }

        // Only update the **specific learning plan** (if known)
        // Otherwise, this updates all plans for the user
        $this->db->table('certificates_assigned_users')
            ->where('user_id', $id_user)
            ->where('certificate_id', $certificate_id)
            ->where('status !=', 0)
            // optional if you know the plan
            // ->where('mp_id', $type)
            ->update(['status' => $finalStatus, 'last_updated_on' => time()]);

        return true;
    }
    //     function json_all_certifications($client)
    //     {
    //         $builder = $this->db->table('certificates_clients as cert_clients');
    //         $builder->select("
    //     cert.cert_id as certificate_id,
    //     cert.name as certificate_name,
    //     cert.duration as certificate_duration,
    //     cert.description as certificate_description,

    //     mp.mp_id as lp_id,
    //     mp.mp_name as lp_name,
    //     mp.duration as lp_duration,
    //     mp.description as lp_description,

    //     courses.scourse_id,
    //     courses.course_name,
    //     courses.duration as course_duration,

    //     (
    //         SELECT COUNT(*)
    //         FROM certificates_assigned ca2
    //         WHERE ca2.certificate_id = cert.cert_id
    //         AND ca2.type = 4
    //         AND ca2.status != 0
    //     ) as certification_lp_count,

    //     (
    //         SELECT COUNT(DISTINCT sc2.scourse_id)
    //         FROM certificates_assigned ca2
    //         JOIN marketplace_courses mpc2
    //             ON mpc2.mp_id = ca2.course_lp_mp_id
    //             AND mpc2.status != 0
    //         JOIN scorm_courses sc2
    //             ON sc2.scourse_id = mpc2.scorm_id
    //             AND sc2.status = 1
    //         WHERE ca2.certificate_id = cert.cert_id
    //         AND ca2.type = 4
    //         AND ca2.status != 0
    //     ) as certification_course_count
    // ");
    //         $builder->join('certificates as cert', 'cert.cert_id = cert_clients.cert_id', 'left');
    //         $builder->join('certificates_assigned as cert_assign', 'cert_assign.certificate_id = cert.cert_id and cert_assign.status != 0 and cert_assign.type = 4', 'left');
    //         $builder->join('marketplace as mp', 'mp.mp_id = cert_assign.course_lp_mp_id AND mp.type = 2', 'left');
    //         $builder->join('marketplace_courses as mpc', 'mpc.mp_id = mp.mp_id and mpc.status != 0', 'left');
    //         $builder->join('scorm_courses as courses', 'courses.scourse_id = mpc.scorm_id ', 'left');

    //         $builder->where('cert.type', 4);
    //         // $builder->groupBy('cert.cert_id');
    //         $builder->groupBy('cert.cert_id, mp.mp_id');

    //         $builder->where('cert_clients.status !=', 0);
    //         $builder->where('cert_clients.client_id', $client);
    //         $builder->orderBy('cert.cert_id', 'ASC');
    //         // $builder->groupBy('cert.cert_id, cert_assign.course_lp_mp_id');
    //         $data = $builder->get()->getResultArray();
    //         // print_r($data);
    //         // echo $this->db->getLastQuery();
    //         // exit();
    //         return $data;
    //     }
    function summary($client)
    {
        $builder = $this->db->table('certificates_clients as cert_clients');

        $builder->select("
    cert.cert_id as certificate_id,
    cert.name as certificate_name,
    cert.description as certificate_description,
    cert.duration as certificate_duration,

    COUNT(DISTINCT cert_assign.course_lp_mp_id) as total_learning_paths,

    (
        SELECT COUNT(DISTINCT mpc2.scorm_id)
        FROM marketplace_courses mpc2
        JOIN certificates_assigned ca2
            ON ca2.course_lp_mp_id = mpc2.mp_id
        WHERE ca2.certificate_id = cert.cert_id
        AND ca2.type = 4
        AND ca2.status != 0
    ) as total_courses, ANY_VALUE(pm.amount) as amount
       
");

        $builder->join('certificates as cert', 'cert.cert_id = cert_clients.cert_id', 'left');

        $builder->join(
            'certificates_assigned as cert_assign',
            'cert_assign.certificate_id = cert.cert_id
     AND cert_assign.type = 4
     AND cert_assign.status != 0',
            'left'
        );
        $builder->join(
            'certification_price_mapping as cpm',
            'cpm.certificate_id = cert.cert_id
         AND cpm.is_active = 1',
            'left'
        );

        $builder->join(
            'price_master as pm',
            'pm.price_id = cpm.price_id',
            'left'
        );
        $builder->where('cert.type', 4);
        $builder->where('cert_clients.status !=', 0);
        $builder->where('cert_clients.client_id', $client);

        $builder->groupBy('cert.cert_id');

        $summary = $builder->get()->getResultArray();
        return $summary;
    }
    function details($client)
    {
        $builder = $this->db->table('certificates_clients as cert_clients');

        $builder->select("
    cert.cert_id as certificate_id,

    mp.mp_id as lp_id,
    mp.mp_name as lp_name,
    mp.description as lp_description,

    courses.scourse_id,
    courses.course_name
");

        $builder->join('certificates as cert', 'cert.cert_id = cert_clients.cert_id', 'left');

        $builder->join(
            'certificates_assigned as cert_assign',
            'cert_assign.certificate_id = cert.cert_id
     AND cert_assign.type = 4
     AND cert_assign.status != 0',
            'left'
        );

        $builder->join(
            'marketplace as mp',
            'mp.mp_id = cert_assign.course_lp_mp_id',
            'left'
        );

        $builder->join(
            'marketplace_courses as mpc',
            'mpc.mp_id = mp.mp_id AND mpc.status != 0',
            'left'
        );

        $builder->join(
            'scorm_courses as courses',
            'courses.scourse_id = mpc.scorm_id AND courses.status = 1',
            'left'
        );


        $builder->where('cert.type', 4);
        $builder->where('cert_clients.status !=', 0);
        $builder->where('cert_clients.client_id', $client);

        $builder->groupBy('cert.cert_id, mp.mp_id, courses.scourse_id');

        $details = $builder->get()->getResultArray();
        return $details;
    }
    public function assignCertificate($assignedData)
    {
        $this->db->table('certificates_assigned_users')
            ->insert($assignedData);
    }
    public function getCompletedCourses($userId)
    {
        return $this->db->table('scorm_users_courses_assigned')
            ->select('course_id')
            ->where('id_user', $userId)
            ->where('course_status', '2') // Use your actual completion column/value
            ->where('status !=', 0)
            ->get()
            ->getResultArray();
    }
    public function getCertificateById($certificateId)
    {
        return $this->db->table('certificates')
            ->select('cert_id, name, assessment_course_id')
            ->where('cert_id', $certificateId)
            ->where('status !=', 0)
            ->get()
            ->getRowArray();
    }
}
