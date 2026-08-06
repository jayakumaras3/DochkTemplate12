<?php

namespace App\Models\Marketplace;

use CodeIgniter\Model;

class M_Dashboard_model extends Model
{

    function get_courses($client_id, $pageId, $mp_language, $category)
    {
        // print_r();

        $builder = $this->db->table('marketplace_clients as mc');
        $builder->select('mc.discount, mc.billing_cycle,mc.payment_type,mc.currency,mc.cost,mpc.price, mpc.scorm_id, mpc.mp_co_id,
                  c.scourse_id, c.course_name, c.course_code, c.duration,
                  c.thumbnail, c.language, c.avg_rating');
        $builder->join('marketplace as m', 'm.mp_id =  mc.mp_id AND m.status != 0', 'left');

        $builder->join('marketplace_courses as mpc', 'mpc.mp_id =  mc.mp_id AND mpc.status != 0', 'left');
        $builder->join('scorm_courses as c', 'c.scourse_id = mpc.scorm_id AND c.status = 1', 'left');
        $builder->join('assign_meta_category as assign', 'assign.fk_scourse_id = c.scourse_id AND assign.status = 1', 'left');
        $builder->join('scorm_meta_category as cat', 'cat.sc_mcid = assign.fk_sc_mcid AND cat.status = 1', 'left');

        $builder->where('mc.status !=', 0);
        $builder->offset($pageId);

        if ($mp_language != "All") {
            $builder->where('c.language', $mp_language);
        }

        $builder->where('cat.meta_category', $category);
        $builder->where('mc.client_id', $client_id);
        $builder->where('m.type', 1);
        $builder->distinct();
        $builder->orderBy('mpc.mp_co_id', 'DESC');
        $builder->limit(9);

        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_courses_search($client_id, $search_term)
    {
        $builder = $this->db->table('marketplace_clients as mc');
        $builder->select('mc.discount, mc.billing_cycle,mc.payment_type,mc.currency,mc.cost,mpc.price, mpc.scorm_id, mpc.mp_co_id, 
                  c.scourse_id, c.course_name, c.course_code, c.duration, 
                  c.thumbnail, c.language, c.avg_rating');
        $builder->join('marketplace_courses as mpc', 'mpc.mp_id =  mc.mp_id AND mpc.status != 0', 'left');
        $builder->join('scorm_courses as c', 'c.scourse_id = mpc.scorm_id AND c.status = 1', 'left');

        $builder->where('mc.status !=', 0);
        $builder->like('c.course_name', $search_term);
        $builder->where('mc.client_id', $client_id);

        $builder->distinct();
        $builder->orderBy('mpc.mp_co_id', 'DESC');

        $data = $builder->get()->getResultArray();
        return $data;
    }

    function delete_marketplace($data, $mp_id)
    {
        $builder = $this->db->table('marketplace');
        $builder->where('mp_id', $mp_id);
        $builder->update($data);
        return true;
    }

    function get_mp_by_language($mp_language, $client_id)
    {
        $builder = $this->db->table('marketplace_clients as mc');
        $builder->select('mpc.price,  mpc.scorm_id, c.scourse_id, c.course_name, c.course_code, c.duration, c.thumbnail, c.language, c.avg_rating');
        $builder->join('marketplace_courses as mpc', 'mpc.mp_id =  mc.mp_id AND mpc.status != 0', 'left');
        $builder->join('scorm_courses as c', 'c.scourse_id = mpc.scorm_id AND c.status = 1', 'left');
        $builder->where('mc.status !=', 0);
        $builder->where('c.language', $mp_language);
        $builder->where('mc.client_id', $client_id);
        $builder->orderBy('RAND()');
        $builder->limit(9);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function get_all_marketplace($type)
    {
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);
        if (in_array('6', $arrayuserlevel)) {

            $builder = $this->db->table('marketplace as mp');
            $builder->select('mp.*,
        (SELECT COUNT(mc.client_id) FROM marketplace_clients as mc WHERE mc.mp_id = mp.mp_id AND mc.status != 0) as total_clients,
        (SELECT COUNT(mpc.mp_co_id) FROM marketplace_courses as mpc WHERE mpc.mp_id = mp.mp_id AND mpc.status != 0) as total_courses');
            $builder->where('mp.type', $type);
            $builder->where('mp.status !=', 0);
            $data = $builder->get()->getResultArray();
            return $data;
        } else {
            $client_id = session()->get('client');
            $builder = $this->db->table('marketplace as mp');

            $builder->select('mp.*,
                (SELECT COUNT(mc.client_id) 
                    FROM marketplace_clients as mc 
                    WHERE mc.mp_id = mp.mp_id AND mc.status != 0
                ) as total_clients,
                (SELECT COUNT(mpc.mp_co_id) 
                    FROM marketplace_courses as mpc 
                    WHERE mpc.mp_id = mp.mp_id AND mpc.status != 0
                ) as total_courses
            ');

            // Only marketplaces connected to CURRENT CLIENT
            $builder->join('marketplace_clients as mc2', 'mc2.mp_id = mp.mp_id');
            $builder->where('mc2.client_id', $client_id);
            $builder->where('mc2.status !=', 0);

            // existing filters
            $builder->where('mp.type', $type);
            $builder->where('mp.status !=', 0);

            $data = $builder->get()->getResultArray();
            return $data;
        }
    }

    function add_leave_data($data)
    {
        $builder = $this->db->table('marketplace');
        $builder->insert($data);
        $mp_id = $this->db->insertID();
        if ($data['type'] == 2) {

            $newdata = [
                'mp_id' => $mp_id,
                'client_id' => session()->get('client'),
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
                'status' => 1
            ];
            $builder = $this->db->table('marketplace_clients as mc');
            $builder->insert($newdata);
            $assign_users_learning_plan = [
                'mp_id' => $mp_id,
                'user_id' => session()->get('id_user'),
                'client_id' => session()->get('client'),
                'type' => $data['type'],
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
                'status' => 1
            ];
            $builder = $this->db->table('assign_users_learning_plan');
            $builder->insert($assign_users_learning_plan);
        }
        return $mp_id;
    }


    function get_client_by_id($mp_id)
    {
        $builder = $this->db->table('marketplace_clients as mc');
        $builder->select('mc.*, c.client_name,c.id_c');
        $builder->join('client as c', 'c.id_c = mc.client_id', 'left');
        $builder->where('mc.status !=', 0);
        $builder->where('mc.mp_id', $mp_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_active_clients()
    {
        $builder = $this->db->table('client');
        $builder->select('*');
        $builder->where('status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function add_client_to_marketplace_mod($data)
    {
        $builder = $this->db->table('marketplace_clients');
        $builder->insert($data);

        if (!empty($this->db->insertID())) {
            $builder = $this->db->table('dropdown_users as du');
            $builder->select('du.fk_id_user');
            $builder->join('users as u', 'u.id_user = du.fk_id_user', 'left');
            $builder->where('u.valid', 1);
            $builder->where('fk_id_d', 5);
            $builder->where('u.client_id', $data['client_id']);
            $builder->where('du.status !=', 0);
            $builder->where('du.fk_id_dc', 2);
            $userdata = $builder->get()->getResultArray();
            // echo $this->db->getLastQuery();
            // exit();

            if (!empty($userdata)) {

                foreach ($userdata as $user) {

                    $exists = $this->db->table('assign_users_learning_plan')
                        ->where([
                            'mp_id' => $data['mp_id'],
                            'user_id' => $user['fk_id_user'],
                            'client_id' => $data['client_id'],
                            'status' => 1
                        ])
                        ->countAllResults();

                    if ($exists == 0) {

                        $payload = [
                            'mp_id' => $data['mp_id'],
                            'user_id' => $user['fk_id_user'],
                            'client_id' => $data['client_id'],
                            'last_updated_by' => session()->get('id_user'),
                            'last_updated_on' => time(),
                            'status' => 1
                        ];

                        $this->db->table('assign_users_learning_plan')->insert($payload);
                    }
                }
            }
        }
        return $this->db->insertID();
    }

    function del_marketplace_client($data, $mp_cl_id)
    {
        $builder = $this->db->table('marketplace_clients');
        $builder->where('mp_cl_id', $mp_cl_id);
        $builder->update($data);
        return true;
    }

    function get_courses_by_id($mp_id)
    {
        $builder = $this->db->table('marketplace_courses as mpc');
        $builder->select('ANY_VALUE(mpc.mp_co_id) as mp_co_id, ANY_VALUE(mpc.price) as price, ANY_VALUE(mpc.scorm_id) as scorm_id, ANY_VALUE(c.course_name) as course_name, c.status,GROUP_CONCAT(DISTINCT(cat.description) ORDER BY cat.description ASC SEPARATOR ", ") as category_name,GROUP_CONCAT(DISTINCT(cat.meta_category) ORDER BY cat.description ASC SEPARATOR ", ") as skill,c.course_code, c.duration, c.language, c.avg_rating,c.thumbnail,c.scourse_id,c.description,GROUP_CONCAT(DISTINCT(sco.objective) ORDER BY sco.sco_id  ASC SEPARATOR "| ") as objective,ANY_VALUE(mpc.sort_order) as sort_order');
        $builder->join('scorm_courses as c', 'c.scourse_id = mpc.scorm_id AND c.status != 0', 'left');
        $builder->join('scorm_courses_objectives as sco', 'sco.course_id = c.scourse_id AND sco.status != 0', 'left');
        $builder->join('assign_meta_category as asn', 'asn.fk_scourse_id  = c.scourse_id AND asn.status!=0', 'left');
        $builder->join('scorm_meta_category as cat', 'cat.sc_mcid = asn.fk_sc_mcid AND cat.status!=0', 'left');
        $builder->where('mpc.status !=', 0);
        $builder->where('mpc.mp_id', $mp_id);
        $builder->orderBy('sort_order', 'Asc');
        $builder->groupBy('c.scourse_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_all_courses()
    {
        $builder = $this->db->table('scorm_courses as c');
        $builder->select('c.course_name, c.scourse_id, c.course_code');
        $builder->where('c.status', 1);
        $builder->orderBy('c.course_name', 'ASC');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function add_course_to_marketplace_mod($data)
    {
        $builder = $this->db->table('marketplace_courses');
        $builder->insert($data);
        return $this->db->insertID();
    }

    function del_marketplace_course($data, $mp_co_id)
    {
        $builder = $this->db->table('marketplace_courses');
        $builder->where('mp_co_id', $mp_co_id);
        $builder->update($data);
        return true;
    }

    function get_marketplace_by_id($mp_id)
    {
        $builder = $this->db->table('marketplace as mp');
        $builder->select('mp.*');
        $builder->where('mp.status !=', 0);
        $builder->where('mp.mp_id', $mp_id);
        $data = $builder->get()->getRowArray();
        return $data;
    }

    function update_marketplace_name($data, $mp_id)
    {
        $builder = $this->db->table('marketplace');
        $builder->where('mp_id', $mp_id);
        $builder->update($data);
        return true;
    }
    function get_marketplace_details($mp_id)
    {
        $builder = $this->db->table('marketplace as mp');
        $builder->select('mp.*');
        $builder->where('mp.mp_id', $mp_id);
        $builder->where('mp.status !=', 0);
        $data = $builder->get()->getRowArray();
        return $data;
    }
    function get_learning_plan_dashboard()
    {
        $id_user = session()->get('id_user');
        $client  = session()->get('client');

        $builder = $this->db->table('marketplace as mp');

        $builder->select("mp.*,
 (SELECT COUNT(mc.client_id) 
                    FROM marketplace_clients as mc 
                    WHERE mc.mp_id = mp.mp_id AND mc.status != 0
                ) as total_clients,
        COUNT(DISTINCT mpc.mp_co_id) as total_courses,

        COUNT(DISTINCT CASE 
            WHEN suca.course_status = 2 
            THEN suca.course_id 
        END) as completed_courses
    ");

        // Assigned plans
        $builder->join(
            'assign_users_learning_plan as aulp',
            'aulp.mp_id = mp.mp_id AND aulp.status != 0'
        );

        // Client validation
        $builder->join(
            'marketplace_clients as mc',
            'mc.mp_id = mp.mp_id AND mc.status != 0'
        );

        // Courses inside plan
        $builder->join(
            'marketplace_courses as mpc',
            'mpc.mp_id = mp.mp_id AND mpc.status != 0',
            'left'
        );

        // User course assignment (SCORM)
        $builder->join(
            'scorm_users_courses_assigned as suca',
            "suca.course_id = mpc.scorm_id 
         AND suca.id_user = {$id_user} 
         AND suca.status = 1",
            'left'
        );

        $builder->where('mc.client_id', $client);
        $builder->where('aulp.user_id', $id_user);
        $builder->where('mp.type', 2);
        $builder->where('mp.status !=', 0);

        $builder->groupBy('mp.mp_id');

        $data = $builder->get()->getResultArray();
        // print_r($data);
        // exit();

        // Calculate Progress + Status
        foreach ($data as &$plan) {

            $total      = (int) $plan['total_courses'];
            $completed  = (int) $plan['completed_courses'];

            $progress = ($total > 0)
                ? round(($completed / $total) * 100)
                : 0;

            $plan['progress_percent'] = $progress;

            if ($progress == 0) {
                $plan['plan_status'] = 'Not Started';
            } elseif ($progress == 100) {
                $plan['plan_status'] = 'Completed';
            } else {
                $plan['plan_status'] = 'In Progress';
            }
        }

        return $data;
    }
    function get_traninerlearning_plan_courses()
    {
        $builder = $this->db->table('marketplace as mp');
        $builder->select('mp.*,
        (SELECT COUNT(mc.client_id) 
                    FROM marketplace_clients as mc 
                    WHERE mc.mp_id = mp.mp_id AND mc.status != 0
                ) as total_clients,
                (SELECT COUNT(mpc.mp_co_id) 
                    FROM marketplace_courses as mpc 
                    WHERE mpc.mp_id = mp.mp_id AND mpc.status != 0
                ) as total_courses,ANY_VALUE(aulp.status) as learning_plan_status,u.name as createdby');
        $builder->join('marketplace_clients as mc', 'mc.mp_id = mp.mp_id AND mc.status != 0', 'left');
        $builder->join('assign_users_learning_plan as aulp', 'aulp.mp_id = mp.mp_id AND aulp.user_id = ' . session()->get('id_user') . ' and aulp.status != 0', 'left');
        $builder->join('marketplace_courses as mpc', 'mpc.mp_id =  mc.mp_id AND mpc.status != 0', 'left');
        $builder->join('users as u','u.id_user = mp.last_updated_by','left');
        $builder->where('mc.client_id', session()->get('client'));
        // $builder->where('aulp.user_id', session()->get('id_user'));
        $builder->where('mp.type', 2);
        // $builder->where('aulp.type', 2);
        $builder->where('mp.status !=', 0);
        $builder->groupBy('mp.mp_id');
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();

        // echo "<pre>"; print_r($data);
        // exit;
        return $data;
    }
    function get_learning_plan_courses()
    {
        $builder = $this->db->table('marketplace as mp');
        $builder->select('mp.*,
        (SELECT COUNT(mc.client_id) 
                    FROM marketplace_clients as mc 
                    WHERE mc.mp_id = mp.mp_id AND mc.status != 0
                ) as total_clients,
                (SELECT COUNT(mpc.mp_co_id) 
                    FROM marketplace_courses as mpc 
                    WHERE mpc.mp_id = mp.mp_id AND mpc.status != 0
                ) as total_courses,ANY_VALUE(aulp.status) as learning_plan_status,u.name as createdby');
        $builder->join('marketplace_clients as mc', 'mc.mp_id = mp.mp_id AND mc.status != 0', 'left');
        $builder->join('assign_users_learning_plan as aulp', 'aulp.mp_id = mp.mp_id AND aulp.status != 0', 'left');
        $builder->join('marketplace_courses as mpc', 'mpc.mp_id =  mc.mp_id AND mpc.status != 0', 'left');
         $builder->join('users as u','u.id_user = mp.last_updated_by','left');
        $builder->where('mc.client_id', session()->get('client'));
        $builder->where('aulp.user_id', session()->get('id_user'));
        $builder->where('mp.type', 2);
        $builder->where('aulp.type', 2);
        $builder->where('mp.status !=', 0);
        $builder->groupBy('mp.mp_id');
        $builder->orderBy('MAX(aulp.last_updated_on)', 'DESC', false);
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();

        // echo "<pre>"; print_r($data);
        // exit;
        return $data;
    }
    function get_certification_learning_plan_courses($certificate_id, $client, $user_id, $type)
    {
        $builder = $this->db->table('certificates_assigned_users as cert_users');
        $builder->select('ANY_VALUE(mp.mp_name) as mp_name,ANY_VALUE(mp.mp_id) as mp_id,MAX(aulp.status) as learning_plan_status, ANY_VALUE(mp.duration) as duration, ANY_VALUE(mp.thumbnail) as thumbnail, ANY_VALUE(mp.description) as description,ANY_VALUE(mp.mode) as mode,cert.name as cert_name');
        $builder->join('certificates as cert', 'cert.cert_id = cert_users.certificate_id', 'left');
        $builder->join('certificates_assigned as cert_assign', 'cert_assign.certificate_id = cert.cert_id', 'left');
        $builder->join('assign_users_learning_plan as aulp', 'aulp.mp_id = cert_assign.course_lp_mp_id and aulp.user_id=' . $user_id . ' AND aulp.status != 0', 'left');
        $builder->join('marketplace as mp', 'mp.mp_id = cert_assign.course_lp_mp_id', 'left');
        $builder->where('cert_users.status !=', 0);
        $builder->where('cert_users.certificate_id', $certificate_id);
        $builder->where('cert_users.client_id', $client);
        $builder->where('cert_users.user_id', $user_id);
        $builder->where('cert_assign.type', 4);
        $builder->where('mp.status !=', 0);
        $builder->groupby('mp.mp_id');
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();

        //  echo "<pre>";
        // print_r($data);
        // exit();
        return $data;
    }
    function get_learning_plan_each_courses($mp_id)
    {
        $id_user = session()->get('id_user');
        $builder = $this->db->table(tableName: 'marketplace_courses as mpc');
        $builder->select('ANY_VALUE(mpc.price) as price,  ANY_VAlUE(mpc.scorm_id) as scorm_id,ANY_VAlUE(m.banner) as banner,m.mp_id,c.scourse_id, c.course_name, c.course_code, c.duration, c.thumbnail, c.language, c.avg_rating,c.description,c.status,c.mode,c.type,ANY_VALUE(suca.course_status) as course_status ,max(sud.lesson_status) as lesson_status ,c.objectives,GROUP_CONCAT(DISTINCT(x.objective) ORDER BY x.sco_id  ASC SEPARATOR "| ") as objective,ANY_VALUE(mpc.sort_order) as sort_order');
        $builder->join('scorm_courses as c', 'c.scourse_id = mpc.scorm_id AND c.status = 1', 'left');
        $builder->join('scorm_users_courses_assigned as suca', 'suca.course_id = c.scourse_id and suca.id_user = ' . $id_user . ' and  suca.status=1', 'left');
        $builder->join('scorm_user_details as sud', 'sud.user_assign_id = suca.user_assign_id and sud.status=1', 'left');
        $builder->join('scorm_courses_objectives as x', 'x.course_id = c.scourse_id', 'left');
        $builder->join('marketplace as m', 'm.mp_id = mpc.mp_id', 'left');
        $builder->where('mpc.status !=', 0);
        $builder->where('mpc.mp_id', $mp_id);
        $builder->where('c.status !=', 0);
        // $builder->where('suca.id_user', $id_user);
        $builder->orderBy('sort_order', 'Asc');
        $builder->groupBy('c.scourse_id');
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // print_r($data);
        // exit();
        return $data;
    }
    function assign_certificateuser_to_lp($mp_id, $user_id, $type)
    {
        $builder = $this->db->table('assign_users_learning_plan');
        $existingAssignment = $builder->where('user_id', $user_id)
            ->where('mp_id', $mp_id)
            //->where('type', $type)
            ->where('client_id', session()->get('client'))
            ->where('status !=', 0)
            ->get()
            ->getRowArray();
        // print_r($mp_id);
        // print_r($existingAssignment);
        // exit();
        if ($existingAssignment) {
            return false;
        } else {
            $data = [
                'mp_id' => $mp_id,
                'user_id' => $user_id,
                'client_id' => session()->get('client'),
                'type' => $type,
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
                'status' => 1
            ];
            $builder = $this->db->table('assign_users_learning_plan');
            $builder->insert($data);
            return $this->db->insertID();
        }
    }


    public function updateLearningPlanStatus($mp_id, $id_user, $coursearrayid, $type)
    {
        if (!is_array($coursearrayid)) {
            $coursearrayid = explode(',', $coursearrayid);
        }

        if (empty($coursearrayid)) return false;

        $total = count($coursearrayid);
        $completed = 0;
        $inProgress = 0;

        foreach ($coursearrayid as $course_id) {
            $row = $this->db->table('scorm_users_courses_assigned')
                ->select('course_status')
                ->where('id_user', $id_user)
                ->where('course_id', $course_id)
                ->get()
                ->getRowArray();

            if ($row) {
                if ($row['course_status'] == 2) $completed++;
                elseif ($row['course_status'] == 1) $inProgress++;
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
        $this->db->table('assign_users_learning_plan')
            ->where('user_id', $id_user)
            ->where('mp_id', $mp_id)
            // optional if you know the plan
            // ->where('mp_id', $type)
            ->update(['status' => $finalStatus, 'last_updated_on' => time()]);

        return true;
    }
    function enroll_user_to_learning_plan($user_id, $data)
    {
        // Check if the user is already enrolled
        $builder = $this->db->table('assign_users_learning_plan');
        $builder->where('user_id', $user_id);
        $builder->where('mp_id', $data['mp_id']);
        $builder->where('status !=', 0);
        $existingEnrollment = $builder->get()->getRowArray();

        if ($existingEnrollment) {
            // User is already enrolled, no need to enroll again
            return false;
        }

        $builder = $this->db->table('assign_users_learning_plan');
        $builder->insert($data);
        return true;
    }
    function unenroll_user_from_learning_plan($user_id, $mp_id)
    {
        $builder = $this->db->table('assign_users_learning_plan');
        $builder->where('user_id', $user_id);
        $builder->where('mp_id', $mp_id);
        $builder->where('status !=', 0);
        $data = [
            'status' => 0,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];
        $builder->update($data);
        return true;
    }
    function get_users_by_group($group_id)
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.id_user, u.name as first_name, u.last_name, u.email');
        $builder->join('scorm_user_group_assigned as sugm', 'sugm.user_id = u.id_user AND sugm.status = 1', 'left');
        $builder->where('sugm.group_id', $group_id); // Assuming 4 is the group ID for the desired group
        $builder->where('u.valid', 1);
        $data = $builder->get()->getResultArray();
        // // print_r( $data);
        // echo $this->db->getLastQuery();

        // exit();
        return $data;
    }
    function enroll_usergroup_to_learning_plan($group_id, $data)
    {
        $group_users = $this->get_users_by_group($group_id);
        $enrollmentCount = 0;

        foreach ($group_users as $user) {
            $builder = $this->db->table('assign_users_learning_plan');
            $builder->where('user_id', $user['id_user']);
            $builder->where('mp_id', $data['mp_id']);
            $builder->where('status !=', 0);
            $existingEnrollment = $builder->get()->getRowArray();

            if (!empty($existingEnrollment)) {
                // User is already enrolled, skip to next user
                continue;
            }
            $enrollmentData = [
                'mp_id' => $data['mp_id'],
                'user_id' => $user['id_user'],
                // 'due_date' => isset($_POST['due_date']) ? $_POST['due_date'] : null,
                'client_id' => session()->get('client'),
                'type' => $data['type'],
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
                'status' => 1
            ];
            $builder = $this->db->table('assign_users_learning_plan');
            $builder->insert($enrollmentData);
            $enrollmentCount++;
        }
        return $enrollmentCount > 0;
    }
    function get_assigned_users_learning_plan($mp_id)
    {
        $client = session()->get('client');
        $builder = $this->db->table('assign_users_learning_plan as aulp');
        $builder->select('aulp.*, u.name as first_name, u.last_name, u.email');
        $builder->join('users as u', 'u.id_user = aulp.user_id AND u.valid = 1', 'left');
        $builder->where('aulp.mp_id', $mp_id);
        $builder->where('aulp.client_id', $client);
        $builder->where('aulp.status !=', 0);
        $builder->orderBy('u.name', 'ASC');

        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    function delete_user_from_learning_plan($user_id, $mp_id)
    {
        $builder = $this->db->table('assign_users_learning_plan');
        $builder->where('user_id', $user_id);
        $builder->where('mp_id', $mp_id);
        $data = [
            'status' => 0,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];
        $builder->update($data);
        return true;
    }
    function get_marketplace_client_details($mp_cl_id)
    {
        $builder = $this->db->table('marketplace_clients as mc');
        $builder->select('mc.*, c.client_name,c.id_c');
        $builder->join('client as c', 'c.id_c = mc.client_id', 'left');
        $builder->where('mc.status !=', 0);
        $builder->where('mc.mp_cl_id', $mp_cl_id);
        $data = $builder->get()->getRowArray();
        return $data;
    }
    function update_marketplace_client($data, $mp_cl_id)
    {
        $builder = $this->db->table('marketplace_clients');
        $builder->where('mp_cl_id', $mp_cl_id);
        $builder->update($data);
        return true;
    }
    function getSkills()
    {
        // $builder = $this->db->table('scorm_meta_category as s');
        // $builder->select('s.sc_mcid,s.description as skillname,count(a.fk_scourse_id) as course_count');
        // $builder->join('scorm_meta_category as s1', 's1.meta_category = s.sc_mcid AND s1.status = 1', 'left');
        // $builder->join('assign_meta_category as a', 'a.fk_sc_mcid = s1.sc_mcid and a.status =1', 'left');
        // $builder->where('s.typeofval', 5);
        // $builder->where('s.status', 1);
        // $builder->groupBy('s.sc_mcid');   // IMPORTANT
        // $builder->groupBy('s1.meta_category');
        // $data = $builder->get()->getResultArray();
        // // echo $this->db->getLastQuery();
        // // exit();
        // return $data;


        $builder = $this->db->table('scorm_meta_category s');

        $builder->select('s.sc_mcid, s.description as skillname, COUNT(DISTINCT mc.scorm_id) as course_count');
        $builder->join('scorm_meta_category s1', 's1.meta_category = s.sc_mcid AND s1.status != 0', 'left');
        $builder->join('assign_meta_category a', 'a.fk_sc_mcid = s1.sc_mcid AND a.status != 0', 'left');

        $builder->join('scorm_courses sc', 'sc.scourse_id = a.fk_scourse_id AND sc.status != 0', 'left');

        $builder->join('marketplace_courses mc', 'mc.scorm_id = sc.scourse_id AND mc.status != 0', 'left');
        $builder->join('marketplace_clients c', 'c.mp_id = mc.mp_id AND c.status != 0', 'left');

        $builder->join('marketplace m', 'm.mp_id = mc.mp_id AND m.status != 0 AND m.type = 1', 'left');

        $builder->where('s.typeofval', 5);
        $builder->where('m.type', 1);
        $builder->where('c.client_id', session()->get('client'));
        $builder->where('s1.status !=', 0);
        $builder->where('a.status !=', 0);
        $builder->where('mc.status !=', 0);

        // Group by
        $builder->groupBy('s.sc_mcid, s1.meta_category');

        // Execute
        $data = $builder->get()->getResultArray();

        // Debug: show last query
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    function getLanguage()
    {
        $builder = $this->db->table('scorm_courses as sc');
        $builder->select('sc.language,count(Distinct sc.scourse_id) as langcount');
        $builder->join('marketplace_courses mc', 'mc.scorm_id = sc.scourse_id AND mc.status != 0', 'left');
        $builder->join('marketplace_clients c', 'c.mp_id = mc.mp_id AND c.status != 0', 'left');
        $builder->join('marketplace as m', 'm.mp_id = c.mp_id and m.status !=0', 'left');
        $builder->where('c.client_id', session()->get('client'));
        $builder->where('sc.status !=', 0);
        $builder->where('m.type ', 1);
        $builder->groupBy('sc.language');
        $data = $builder->get()->getResultArray();

        // Debug: show last query
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    function update_course_order($order)
    {
        $i = 1;
        foreach ($order as $mp_co_id) {
            $builder = $this->db->table('marketplace_courses');

            $builder->where('mp_co_id', $mp_co_id);
            $builder->set('sort_order', $i);
            $builder->update();
            $i++;
        }
    }
}
