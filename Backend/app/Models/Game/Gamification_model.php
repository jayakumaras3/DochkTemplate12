<?php

namespace App\Models\Game;

use CodeIgniter\Model;

class Gamification_model extends Model
{
    function get_active_games($client)
    {
        $builder = $this->db->table('gamification as game');
        $builder->select('game.*, sc.course_name as course_name');
        $builder->join('scorm_courses as sc', 'sc.scourse_id  = game.course_id', 'left');
        $builder->where('game.client_id', $client);
        $builder->where('game.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function game_details($game_id)
    {
        $builder = $this->db->table('gamification as game');
        $builder->select('game.*');
        $builder->where('game.game_id', $game_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_game_assigned_users($game_id)
    {
        // $builder = $this->db->table('game_users as gu');
        // $builder->select('gu.*, u.name, u.last_name, sd1.raw as raw, MAX(sd2.attempt) as attempt');
        // $builder->join('users as u', 'u.id_user  = gu.user_id', 'left');
        // // Join with assigned courses
        // $builder->join('scorm_users_courses_assigned as su', 'su.user_assign_id = gu.assign_id AND su.status = 1', 'left');

        // // Join for attempt = 1 to get raw value (LEFT JOIN — optional)
        // $builder->join('scorm_user_details as sd1', 'sd1.user_assign_id = su.user_assign_id AND sd1.status = 1 AND sd1.attempt = 1 AND sd1.lesson_status = "completed"', 'left');

        // // Join for max attempt (INNER JOIN — required)
        // $builder->join('scorm_user_details as sd2', 'sd2.user_assign_id = su.user_assign_id AND sd2.status = 1 AND sd2.lesson_status = "completed"', 'inner');

        // $builder->where('gu.game_id', $game_id);
        // $builder->where('gu.status !=', 0);
        // $builder->groupBy('gu.assign_id');
        // $builder->groupBy('gu.course_id');
        // $builder->groupBy('gu.user_id');
        // $data = $builder->get()->getResultArray();
        // return $data;
        $builder = $this->db->table('game_users as gu');

        $builder->select('
   ANY_VALUE(gu.game_user_id) as game_user_id,ANY_VALUE(gu.status) as status,ANY_VALUE(gu.course_id) as course_id,ANY_VALUE(gu.user_id) as user_id,ANY_VALUE(gu.assign_id) as assign_id,
    u.name,
    u.last_name,
    ANY_VALUE(sd1.raw) as raw,
    MAX(sd2.attempt) as attempt
');

        // Get user info
        $builder->join('users as u', 'u.id_user = gu.user_id', 'left');

        // LEFT JOIN with assigned courses (some users may not have any)
        $builder->join('scorm_users_courses_assigned as su', 'su.user_assign_id = gu.assign_id AND su.status = 1', 'left');

        // LEFT JOIN for raw of attempt = 1
        $builder->join('scorm_user_details as sd1', 'sd1.user_assign_id = su.user_assign_id AND sd1.status = 1 AND sd1.attempt = 1 AND sd1.lesson_status = "completed"', 'left');

        // LEFT JOIN for max attempt (allows null for users who didn’t play)
        $builder->join('scorm_user_details as sd2', 'sd2.user_assign_id = su.user_assign_id AND sd2.status = 1 AND sd2.lesson_status = "completed"', 'left');

        // Where conditions
        $builder->where('gu.game_id', $game_id);
        $builder->where('gu.status !=', 0);

        // Grouping for aggregate
        $builder->groupBy('gu.assign_id');
        $builder->groupBy('gu.course_id');
        $builder->groupBy('gu.user_id');

        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_active_user($client)
    {
        $builder = $this->db->table('users as u');
        $builder->select('u.name, u.last_name, u.id_user');
        $builder->where('u.client_id', $client);
        $builder->where('u.valid', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function get_live_games($client, $satus, $user)
    {
        $builder = $this->db->table('gamification as game');
        $builder->select('ANY_VALUE(game.game_id) as game_id,ANY_VALUE(game.client_id) as client_id , ANY_VALUE(game.game_name) as game_name,ANY_VALUE(game.course_id) as course_id,ANY_VALUE(game.start_on) as start_on,ANY_VALUE(game.end_on) as end_on,ANY_VALUE(game.course_id) as course_id,ANY_VALUE(game.start_on) as start_on,ANY_VALUE(game.attempts) as attempts,ANY_VALUE(sc.course_name) as course_name, ANY_VALUE(sc.thumbnail) as thumbnail,ANY_VALUE(sc.scourse_id ) as scourse_id,ANY_VALUE(sc.duration) as duration,ANY_VALUE(game.status) as status');
        $builder->join('scorm_courses as sc', 'sc.scourse_id  = game.course_id', 'left');
        $builder->join('game_users as gu', 'gu.game_id  = game.game_id', 'left');
        $builder->where('gu.user_id', $user);
        $builder->where('gu.status', 1);
        $builder->where('game.client_id', $client);
        $builder->where('game.status', 5);
        $builder->orWhere('game.status', 2);
        $builder->groupBy('game.game_id');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function get_users_games($client)
    {
        // $builder = $this->db->table('game_users as gu');
        // $builder->select('gu.*,u.name,u.last_name,sd.raw as raw,MAX(sd.attempt) as attempt');
        // $builder->join('scorm_users_courses_assigned as su', 'su.user_assign_id = gu.assign_id and su.status=1', 'left');
        // $builder->join('scorm_user_details as sd', 'sd.user_assign_id   = su.user_assign_id  and sd.status=1', 'left');
        // $builder->join('users as u', 'u.id_user   = gu.user_id  and u.valid=1', 'left');
        // $builder->where('gu.status', 1);
        // $builder->where('sd.lesson_status', 'completed');
        // $builder->where('sd.attempt', 1);
        // $builder->groupBy('gu.assign_id');
        // $builder->groupBy('gu.course_id');
        // $builder->groupBy('gu.user_id');
        // $builder->limit(10);
        // $data = $builder->get()->getResultArray();
        // // echo $this->db->getLastQuery();
        // // exit();
        // return $data;
        $builder = $this->db->table('game_users as gu');

        $builder->select('
    ANY_VALUE(gu.game_user_id) as game_user_id,ANY_VALUE(gu.status) as status,ANY_VALUE(gu.course_id) as course_id,ANY_VALUE(gu.user_id) as user_id,ANY_VALUE(gu.assign_id) as assign_id,
    u.name,
    u.last_name,
    ANY_VALUE(sd1.raw) as raw,
    MAX(sd2.attempt) as attempt
');

        // Join with assigned courses
        $builder->join('scorm_users_courses_assigned as su', 'su.user_assign_id = gu.assign_id AND su.status = 1', 'inner');

        // Join for attempt = 1 to get raw value (LEFT JOIN — optional)
        $builder->join('scorm_user_details as sd1', 'sd1.user_assign_id = su.user_assign_id AND sd1.status = 1 AND sd1.attempt = 1 AND sd1.lesson_status = "completed"', 'left');

        // Join for max attempt (INNER JOIN — required)
        $builder->join('scorm_user_details as sd2', 'sd2.user_assign_id = su.user_assign_id AND sd2.status = 1 AND sd2.lesson_status = "completed"', 'inner');

        // User details
        $builder->join('users as u', 'u.id_user = gu.user_id AND u.valid = 1', 'left');

        $builder->where('gu.status', 1);

        $builder->groupBy('gu.assign_id');
        $builder->groupBy('gu.course_id');
        $builder->groupBy('gu.user_id');
        $builder->orderBy('raw', 'DESC');
        $builder->limit(10);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function get_user_games_report($client)
    {
        // $builder = $this->db->table('game_users as gu');
        // $builder->select('gu.*,u.name,u.last_name,sd.raw as raw,MAX(sd.attempt) as attempt');
        // $builder->join('scorm_users_courses_assigned as su', 'su.user_assign_id = gu.assign_id and su.status=1', 'left');
        // $builder->join('scorm_user_details as sd', 'sd.user_assign_id   = su.user_assign_id  and sd.status=1', 'left');
        // $builder->join('users as u', 'u.id_user   = gu.user_id  and u.valid=1', 'left');
        // $builder->where('gu.status', 1);
        // $builder->where('sd.lesson_status', 'completed');
        // $builder->where('sd.attempt', 1);
        // $builder->groupBy('gu.assign_id');
        // $builder->groupBy('gu.course_id');
        // $builder->groupBy('gu.user_id');
        // $builder->limit(10);
        // $data = $builder->get()->getResultArray();
        // // echo $this->db->getLastQuery();
        // // exit();
        // return $data;
        $builder = $this->db->table('game_users as gu');

        $builder->select('
    ANY_VALUE(gu.game_user_id) as game_user_id,ANY_VALUE(gu.status) as status,ANY_VALUE(gu.course_id) as course_id,ANY_VALUE(gu.user_id) as user_id,ANY_VALUE(gu.assign_id) as assign_id,
    u.name,
    u.last_name,
     ANY_VALUE(sd1.raw) as raw,
    MAX(sd2.attempt) as attempt
');

        // Join with assigned courses
        $builder->join('scorm_users_courses_assigned as su', 'su.user_assign_id = gu.assign_id AND su.status = 1', 'inner');

        // Join for attempt = 1 to get raw value (LEFT JOIN — optional)
        $builder->join('scorm_user_details as sd1', 'sd1.user_assign_id = su.user_assign_id AND sd1.status = 1 AND sd1.attempt = 1 AND sd1.lesson_status = "completed"', 'left');

        // Join for max attempt (INNER JOIN — required)
        $builder->join('scorm_user_details as sd2', 'sd2.user_assign_id = su.user_assign_id AND sd2.status = 1 AND sd2.lesson_status = "completed"', 'inner');

        // User details
        $builder->join('users as u', 'u.id_user = gu.user_id AND u.valid = 1', 'left');

        $builder->where('gu.status', 1);

        $builder->groupBy('gu.assign_id');
        $builder->groupBy('gu.course_id');
        $builder->groupBy('gu.user_id');
        $builder->orderBy('raw', 'DESC');
        // $builder->limit(10);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function add_new_game_request($newdata)
    {
        $builder = $this->db->table('gamification');
        $builder->insert($newdata);
        $insert_id = $this->db->insertID();
        $gamenewdata = [
            'id_user' => session()->get('id_user'),
            'course_id' => $newdata['course_id'],
            'scenario_id' => 0,
            'due_date' => $newdata['start_on'],
            'expiry_date' => $newdata['end_on'],
            'status' => 1,
            'createdby' => session()->get('id_user'),
            'createdon' => time(),
            'game_id' => $insert_id
        ];
        $this->assign_user_to_course($gamenewdata);
        return $insert_id;
    }

    function update_game_request($newdata, $game_id)
    {
        $builder = $this->db->table('gamification as game');
        $builder->where('game.game_id', $game_id);
        $builder->update($newdata);
        return true;
    }

    function assign_user_to_course($newdata)
    {
        $scorm_users_courses_assigned = [
            'id_user' => $newdata['id_user'],
            'course_id' => $newdata['course_id'],
            'scenario_id' => $newdata['scenario_id'],
            'client_id'=> session()->get('client'),
            'due_date' => $newdata['due_date'],
            'expiry_date' => $newdata['expiry_date'],
            'status' => 1,
            'createdby' => $newdata['createdby'],
            'createdon' => time(),
        ];
        $builder = $this->db->table('scorm_users_courses_assigned');
        $builder->insert($scorm_users_courses_assigned);
        $insert_id = $this->db->insertID();
        if (!empty($insert_id)) {
            if (($newdata['scenario_id']) != 0) {
                $xapi_scenarios_users_assign = [
                    'role' => 1,
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time()
                ];
                $builder = $this->db->table('scorm_users_courses_assigned');
                $builder->where('course_id', $newdata['course_id']);
                $builder->where('scenario_id', $newdata['scenario_id']);
                $builder->where('id_user', $newdata['id_user']);
                $builder->where('status', '1');
                $builder->update($xapi_scenarios_users_assign);
            }

            $scorm_user_details = [
                'user_assign_id' => $insert_id,
                'course_id' => $newdata['course_id'],
                'student_id' => $newdata['id_user'],
                'scenario_id' => $newdata['scenario_id'],
                'lesson_status' => 'not started',
                'status' => 1,
                'createdby' => $newdata['createdby'],
                'createdon' => time(),
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time()
            ];
            $builder = $this->db->table('scorm_user_details');
            $builder->insert($scorm_user_details);

            $game_details = [
                'assign_id' => $insert_id,
                'course_id' => $newdata['course_id'],
                'game_id' => $newdata['game_id'],
                'user_id' => $newdata['id_user'],
                'status' => 1,
            ];
            $builder = $this->db->table('game_users');
            $builder->insert($game_details);
        }
        return $insert_id;
    }

    public function addusergrouptocourses($newdata)
    {
        foreach ($newdata['id_user'] as $id_user) {
            $builder = $this->db->table('game_users');
            $builder->select('*');
            $builder->where('user_id', $id_user);
            $builder->where('course_id', $newdata['course_id']);
            $builder->where('game_id', $newdata['game_id']);
            $builder->where('status !=', 0);
            $data = $builder->get()->getResultArray();
            if (empty($data)) {
                $scorm_users_courses_assigned = [
                    'id_user' => $id_user,
                    'course_id' => $newdata['course_id'],
                    'scenario_id' => $newdata['scenario_id'],
                    'due_date' => $newdata['due_date'],
                    'expiry_date' => $newdata['expiry_date'],
                    'stage' => $newdata['stage'],
                    'role' => $newdata['role'],
                    'status' => 1,
                    'createdby' => $newdata['createdby'],
                    'createdon' => time(),
                ];
                $builder = $this->db->table('scorm_users_courses_assigned');
                $builder->insert($scorm_users_courses_assigned);
                $insert_id = $this->db->insertID();

                if (!empty($insert_id)) {
                    if (($newdata['scenario_id']) != 0) {
                        $xapi_scenarios_users_assign = [
                            'role' => 1,
                            'last_updated_by' => session()->get('id_user'),
                            'last_updated_on' => time()
                        ];
                        // $builder = $this->db->table('xapi_scenarios_users_assign');
                        $builder = $this->db->table('scorm_users_courses_assigned');
                        $builder->where('course_id', $newdata['course_id']);
                        $builder->where('scenario_id', $newdata['scenario_id']);
                        $builder->where('id_user', $id_user);
                        $builder->where('status', '1');
                        $builder->update($xapi_scenarios_users_assign);
                    }

                    $scorm_user_details = [
                        'user_assign_id' => $insert_id,
                        'course_id' => $newdata['course_id'],
                        'student_id' => $id_user,
                        'scenario_id' => $newdata['scenario_id'],
                        'lesson_status' => 'not started',
                        'status' => 1,
                        'createdby' => $newdata['createdby'],
                        'createdon' => time(),
                        'last_updated_by' => session()->get('id_user'),
                        'last_updated_on' => time()
                    ];
                    $builder = $this->db->table('scorm_user_details');
                    $builder->insert($scorm_user_details);

                    $game_details = [
                        'assign_id' => $insert_id,
                        'course_id' => $newdata['course_id'],
                        'game_id' => $newdata['game_id'],
                        'user_id' => $id_user,
                        'status' => 1,
                    ];
                    $builder = $this->db->table('game_users');
                    $builder->insert($game_details);
                }
            }
        }
        return true;
    }

    function unassign_user($newdata, $game_user_id)
    {
        $builder = $this->db->table('game_users as game');
        $builder->where('game.game_user_id', $game_user_id);
        $builder->update($newdata);
        return true;
    }
}
