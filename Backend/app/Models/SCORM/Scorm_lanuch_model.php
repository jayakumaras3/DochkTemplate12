<?php

namespace App\Models\SCORM;

use CodeIgniter\Model;

class Scorm_lanuch_model extends Model
{
    // function addscormuserdetails($course_id, $group_id)
    // {
    //     $db = \Config\Database::connect();
    //     $builder = $this->db->table('scorm_user_details as su');
    //     $builder->select('su.*');
    //     $builder->where('student_id', session()->get('id_user'));
    //     $builder->where('course_id', $course_id);

    //     $builder->orderBy('sc_uid', 'DESC');
    //     $builder->limit(1);
    //     $data = $builder->get()->getResultArray();
    //     //
    //     // $builder = $this->db->table('xapi_scenarios_users_assign');
    //     $builder = $this->db->table('scorm_users_courses_assigned');
    //     $builder->select('*');
    //     $builder->where('id_user', session()->get('id_user'));
    //     $builder->where('course_id', $course_id);
    //     $builder->where('status', 1);
    //     $scenariodata = $builder->get()->getResultArray();
    //     $scenario_id = isset($scenariodata[0]['scenario_id']) ? $scenariodata[0]['scenario_id'] : 0;
    //     // print_r($data);
    //     // exit();
    //     if (empty($data)) {

    //         $newdata = [
    //             'student_id' => session()->get('id_user'),
    //             'student_name' => session()->get('name'),
    //             'course_id' => $course_id,
    //             'scenario_id' => $scenario_id,
    //             'lesson_status' => 'incomplete',
    //             'attempt' => 1,
    //             'status' => 1,
    //             'last_active' => time(),
    //             'createdby' => session()->get('id_user'),
    //             'createdon' => time(),
    //             'last_updated_by' =>  session()->get('id_user'),
    //             'last_updated_on' => time()

    //         ];

    //         $db = \Config\Database::connect();
    //         $builder = $this->db->table('scorm_user_details');
    //         $builder->insert($newdata);
    //         $scinsertedID = $db->insertID();

    //         $builder = $this->db->table('scorm_users_courses_assigned as sa');
    //         $builder->select('sa.*');
    //         $builder->where('id_user', session()->get('id_user'));
    //         $builder->where('course_id', $course_id);
    //         $builder->where('status', 1);
    //         $edata = $builder->get()->getResultArray();
    //         if (empty($edata)) {
    //             $scorm_users_courses_assigned = [
    //                 'id_user' => session()->get('id_user'),
    //                 'course_id' => $course_id,
    //                 'group_id' => $group_id,
    //                 'course_status' => 1,
    //                 'status' => 1,
    //                 'createdby' =>  session()->get('id_user'),
    //                 'createdon' => time(),
    //                 // 'last_updated_by' =>  session()->get('id_user'),
    //                 // 'last_updated_on' => time()
    //             ];
    //             $builder = $this->db->table('scorm_users_courses_assigned');
    //             $builder->insert($scorm_users_courses_assigned);
    //             $insertedID = $db->insertID();
    //             $builder = $this->db->table('scorm_user_details');
    //             $builder->set('user_assign_id', $insertedID);
    //             $builder->set('last_active', time());
    //             $builder->where('sc_uid', $scinsertedID);
    //             $builder->where('course_id', $course_id);
    //             $builder->where('status', 1);
    //             $builder->update();
    //         } else {
    //             $builder = $this->db->table('scorm_user_details');
    //             $builder->set('user_assign_id', $edata[0]['user_assign_id']);
    //             $builder->set('last_active', time());
    //             $builder->where('sc_uid', $scinsertedID);
    //             $builder->where('status', 1);
    //             $builder->update();
    //         }
    //     } elseif (!empty($data)) {
    //         if ($data[0]['status'] == 0) {
    //             $newdata = [
    //                 'student_id' => session()->get('id_user'),
    //                 'student_name' => session()->get('name'),
    //                 'course_id' => $course_id,
    //                 'scenario_id' => $scenario_id,
    //                 'lesson_status' => 'incomplete',
    //                 'attempt' => $data[0]['attempt'],
    //                 'last_active' => time(),
    //                 'status' => 1,
    //                 'createdby' => session()->get('id_user'),
    //                 'createdon' => time(),
    //                 'last_updated_by' =>  session()->get('id_user'),
    //                 'last_updated_on' => time()
    //             ];
    //             $db = \Config\Database::connect();
    //             $builder = $this->db->table('scorm_user_details');
    //             $builder->insert($newdata);
    //             $scinsertedID = $db->insertID();
    //             $builder = $this->db->table('scorm_users_courses_assigned as sa');
    //             $builder->select('sa.*');
    //             $builder->where('id_user', session()->get('id_user'));
    //             $builder->where('course_id', $course_id);
    //             $builder->where('status', 1);
    //             $edata = $builder->get()->getResultArray();
    //             if (empty($edata)) {
    //                 $scorm_users_courses_assigned = [
    //                     'id_user' => session()->get('id_user'),
    //                     'course_id' => $course_id,
    //                     'group_id' => $group_id,
    //                     'course_status' => 1,
    //                     'status' => 1,
    //                     'createdby' =>  session()->get('id_user'),
    //                     'createdon' => time(),
    //                     'last_updated_by' =>  session()->get('id_user'),
    //                     'last_updated_on' => time()
    //                 ];
    //                 $builder = $this->db->table('scorm_users_courses_assigned');
    //                 $builder->insert($scorm_users_courses_assigned);
    //                 $insertedID = $db->insertID();
    //                 $builder = $this->db->table('scorm_user_details');
    //                 $builder->set('user_assign_id', $insertedID);
    //                 $builder->set('last_active', time());
    //                 $builder->set('last_updated_on', time());
    //                 $builder->set('last_updated_by', session()->get('id_user'));
    //                 $builder->where('sc_uid', $scinsertedID);
    //                 $builder->where('course_id', $course_id);
    //                 $builder->where('status', 1);
    //                 $builder->update();
    //             } else {

    //                 $builder = $this->db->table('scorm_user_details');
    //                 $builder->set('user_assign_id', $edata[0]['user_assign_id']);
    //                 $builder->set('last_active', time());
    //                 $builder->set('last_updated_on', time());
    //                 $builder->set('last_updated_by', session()->get('id_user'));
    //                 $builder->where('sc_uid', $scinsertedID);
    //                 $builder->where('status', 1);
    //                 $builder->update();
    //             }
    //         } elseif ($data[0]['lesson_status'] == 'completed' || $data[0]['lesson_status'] == 'passed' || $data[0]['lesson_status'] == 'failed') {
    //             $db = \Config\Database::connect();
    //             $builder = $this->db->table('scorm_user_details as su');
    //             $builder->select('su.*');
    //             $builder->where('student_id', session()->get('id_user'));
    //             $builder->where('course_id', $course_id);
    //             $builder->orderBy('sc_uid', 'DESC');
    //             $builder->limit(1);
    //             $data = $builder->get()->getResultArray();
    //             // $last_active = $data[0]['createdby'];
    //             $attempt = $data[0]['attempt'] + 1;
    //             $newdata = [

    //                 'student_id' => session()->get('id_user'),
    //                 'student_name' => session()->get('name'),
    //                 'course_id' => $course_id,
    //                 'scenario_id' => $scenario_id,
    //                 'lesson_status' => 'incomplete',
    //                 'attempt' => $attempt,
    //                 'status' => 1,
    //                 'createdby' => session()->get('id_user'),
    //                 'createdon' => time(),
    //                 'last_updated_by' =>  session()->get('id_user'),
    //                 'last_updated_on' => time()
    //             ];
    //             $db = \Config\Database::connect();
    //             $builder = $this->db->table('scorm_user_details');
    //             $builder->insert($newdata);
    //             $scinsertedID = $db->insertID();
    //             if ($data) {
    //                 $builder = $this->db->table('scorm_users_courses_assigned as sa');
    //                 $builder->select('sa.*');
    //                 $builder->where('id_user', session()->get('id_user'));
    //                 $builder->where('course_id', $course_id);
    //                 $builder->where('status', 1);
    //                 $edata = $builder->get()->getResultArray();
    //                 if (empty($edata)) {
    //                     $scorm_users_courses_assigned = [
    //                         'id_user' => session()->get('id_user'),
    //                         'course_id' => $course_id,
    //                         'group_id' => $group_id,
    //                         'course_status' => 1,
    //                         'status' => 1,
    //                         'createdby' =>  session()->get('id_user'),
    //                         'createdon' => time(),
    //                         'last_updated_by' =>  session()->get('id_user'),
    //                         'last_updated_on' => time()
    //                     ];
    //                     $builder = $this->db->table('scorm_users_courses_assigned');
    //                     $builder->insert($scorm_users_courses_assigned);
    //                     $insertedID = $db->insertID();
    //                     $builder = $this->db->table('scorm_user_details');
    //                     $builder->set('user_assign_id', $insertedID);
    //                     $builder->set('last_active', time());
    //                     $builder->set('last_updated_on', time());
    //                     $builder->set('last_updated_by', session()->get('id_user'));
    //                     $builder->where('sc_uid', $scinsertedID);
    //                     $builder->where('course_id', $course_id);
    //                     $builder->where('status', 1);
    //                     $builder->update();
    //                 } else {

    //                     $builder = $this->db->table('scorm_user_details');
    //                     $builder->set('user_assign_id', $edata[0]['user_assign_id']);
    //                     $builder->set('last_active', time());
    //                     $builder->set('last_updated_on', time());
    //                     $builder->set('last_updated_by', session()->get('id_user'));
    //                     $builder->where('sc_uid', $scinsertedID);
    //                     $builder->where('status', 1);
    //                     $builder->update();
    //                 }
    //             }
    //         }
    //     } else {
    //         if ($data['0']['lesson_status'] == 'incomplete') {
    //             $_SESSION['userUniqueID'] = $data['0']['sc_uid'];
    //         }
    //     }
    // }
    public function addscormuserdetails($course_id, $group_id)
    {
        $db = \Config\Database::connect();

        // Read session data once to avoid overwriting in concurrent calls
        $student_id = session()->get('id_user');
        $student_name = session()->get('name');

        // Start DB transaction (atomic operation)
        $db->transStart();

        // Get scenario_id from assigned courses
        $assignBuilder = $db->table('scorm_users_courses_assigned');
        $assignBuilder->select('*')
            ->where('id_user', $student_id)
            ->where('course_id', $course_id)
            ->where('status', 1);
        $assignedData = $assignBuilder->get()->getResultArray();
        $scenario_id = isset($assignedData[0]['scenario_id']) ? $assignedData[0]['scenario_id'] : 0;

        // Get the latest SCORM user detail row for this student & course
        $detailBuilder = $db->table('scorm_user_details');
        $detailBuilder->select('*')
            ->where('student_id', $student_id)
            ->where('course_id', $course_id)
            ->orderBy('sc_uid', 'DESC')
            ->limit(1);
        $latestData = $detailBuilder->get()->getResultArray();

        // Case 1: No record exists → Create new
        if (empty($latestData)) {
            $newData = [
                'student_id' => $student_id,
                'student_name' => $student_name,
                'course_id' => $course_id,
                'scenario_id' => $scenario_id,
                'lesson_status' => 'incomplete',
                'attempt' => 1,
                'status' => 1,
                'last_active' => time(),
                'createdby' => $student_id,
                'createdon' => time(),
                'last_updated_by' => $student_id,
                'last_updated_on' => time()
            ];
            $db->table('scorm_user_details')->insert($newData);
            $scInsertId = $db->insertID();

            // Assign course if not already assigned
            if (empty($assignedData)) {
                $assignData = [
                    'id_user' => $student_id,
                    'course_id' => $course_id,
                    'group_id' => $group_id,
                    'course_status' => 1,
                    'status' => 1,
                    'createdby' => $student_id,
                    'createdon' => time()
                ];
                $db->table('scorm_users_courses_assigned')->insert($assignData);
                $assignId = $db->insertID();
            } else {
                $assignId = $assignedData[0]['user_assign_id'];
            }

            // Update SCORM details with assigned course ID
            $db->table('scorm_user_details')
                ->set('user_assign_id', $assignId)
                ->set('last_active', time())
                ->where('sc_uid', $scInsertId)
                ->update();
        }

        // Case 2: Record exists but status = 0 → Reactivate
        elseif ($latestData[0]['status'] == 0) {
            $newData = [
                'student_id' => $student_id,
                'student_name' => $student_name,
                'course_id' => $course_id,
                'scenario_id' => $scenario_id,
                'lesson_status' => 'incomplete',
                'attempt' => $latestData[0]['attempt'],
                'status' => 1,
                'last_active' => time(),
                'createdby' => $student_id,
                'createdon' => time(),
                'last_updated_by' => $student_id,
                'last_updated_on' => time()
            ];
            $db->table('scorm_user_details')->insert($newData);
            $scInsertId = $db->insertID();

            if (empty($assignedData)) {
                $assignData = [
                    'id_user' => $student_id,
                    'course_id' => $course_id,
                    'group_id' => $group_id,
                    'course_status' => 1,
                    'status' => 1,
                    'createdby' => $student_id,
                    'createdon' => time(),
                    'last_updated_by' => $student_id,
                    'last_updated_on' => time()
                ];
                $db->table('scorm_users_courses_assigned')->insert($assignData);
                $assignId = $db->insertID();
            } else {
                $assignId = $assignedData[0]['user_assign_id'];
            }

            $db->table('scorm_user_details')
                ->set('user_assign_id', $assignId)
                ->set('last_active', time())
                ->set('last_updated_on', time())
                ->set('last_updated_by', $student_id)
                ->where('sc_uid', $scInsertId)
                ->update();
        }

        // Case 3: Lesson completed → Start new attempt
        elseif (in_array($latestData[0]['lesson_status'], ['completed', 'passed', 'failed'])) {
            if (in_array($latestData[0]['lesson_status'], ['completed', 'passed'])) {
                $course_status = 2;
            } else {
                $course_status = 1;
            }
            $attempt = $latestData[0]['attempt'] + 1;
            $newData = [
                'student_id' => $student_id,
                'student_name' => $student_name,
                'course_id' => $course_id,
                'scenario_id' => $scenario_id,
                'lesson_status' => 'incomplete',
                'attempt' => $attempt,
                'status' => 1,
                'createdby' => $student_id,
                'createdon' => time(),
                'last_updated_by' => $student_id,
                'last_updated_on' => time()
            ];
            $db->table('scorm_user_details')->insert($newData);
            $scInsertId = $db->insertID();

            if (empty($assignedData)) {
                $assignData = [
                    'id_user' => $student_id,
                    'course_id' => $course_id,
                    'group_id' => $group_id,
                    'course_status' => $course_status,
                    'status' => 1,
                    'createdby' => $student_id,
                    'createdon' => time(),
                    'last_updated_by' => $student_id,
                    'last_updated_on' => time()
                ];
                $db->table('scorm_users_courses_assigned')->insert($assignData);
                $assignId = $db->insertID();
            } else {
                $assignId = $assignedData[0]['user_assign_id'];
                $builder = $this->db->table('scorm_users_courses_assigned as sa');
                $builder->set('course_status', $course_status);
                $builder->where('id_user', session()->get('id_user'));
                $builder->where('user_assign_id', $assignId);
                $builder->where('status', 1);
                $builder->update();
            }

            $db->table('scorm_user_details')
                ->set('user_assign_id', $assignId)
                ->set('last_active', time())
                ->set('last_updated_on', time())
                ->set('last_updated_by', $student_id)
                ->where('sc_uid', $scInsertId)
                ->update();
        }

        // Case 4: Incomplete → Set session only
        else {
            if ($latestData[0]['lesson_status'] == 'incomplete') {
                $_SESSION['userUniqueID'] = $latestData[0]['sc_uid'];
            }
        }

        // Commit transaction
        $db->transComplete();
    }

    // function updatescormuserdetails($student_id, $student_name, $course_id, $column_name, $value)
    // {

    //     $last_active = date('m-d-Y h:i:s');
    //     $db = \Config\Database::connect();
    //     $builder = $this->db->table('scorm_user_details as su');
    //     $builder->select('su.*');
    //     $builder->where('student_id', session()->get('id_user'));
    //     $builder->where('course_id', $course_id);
    //     $builder->orderBy('sc_uid', 'DESC');
    //     // $builder->whereIn('lesson_status', 'incomplete');
    //     $data = $builder->get()->getResultArray();
    //     if (!empty($data)) {
    //         if ($data[0]['lesson_status'] == 'incomplete' || $data[0]['lesson_status'] == 'not started' || $data[0]['lesson_status'] == 'failed' && $data[0]['status'] == '1') {
    //             $builder = $this->db->table('scorm_user_details as su');
    //             if ($data[0]['lesson_status'] == 'not started') {
    //                 $builder->set('attempt', 1);
    //             }
    //             $builder->set($column_name, $value);
    //             $builder->set('last_active', time());
    //             $builder->set('last_updated_on', time());
    //             $builder->set('last_updated_by', session()->get('id_user'));
    //             $builder->where('sc_uid', $data['0']['sc_uid']);
    //             $builder->where('status', 1);
    //             $builder->update();
    //             $data = $builder->get()->getResultArray();
    //             $builder = $this->db->table('scorm_users_courses_assigned as sa');
    //             $builder->select('sa.*');
    //             $builder->where('id_user', session()->get('id_user'));
    //             $builder->where('course_id', $course_id);
    //             $builder->where('course_status!=', 2);
    //             $builder->where('status', 1);
    //             $edata = $builder->get()->getResultArray();
    //             if (!empty($edata)) {
    //                 $builder = $this->db->table('scorm_users_courses_assigned as sa');
    //                 $builder->set('course_status', 1);
    //                 $builder->where('id_user', session()->get('id_user'));
    //                 $builder->where('course_id', $course_id);
    //                 $builder->where('status', 1);
    //                 $builder->update();
    //             }
    //             if (!empty($data)) {
    //                 $data['status'] = "true";
    //             } else {
    //                 $data['status'] = "false";
    //             }
    //             // return $data;
    //         } elseif (($data[0]['lesson_status'] == 'completed' && $column_name == 'suspend_data') ||  ($data[0]['lesson_status'] == 'completed' && $column_name == 'session_time') || ($data[0]['lesson_status'] == 'passed' && $column_name == 'suspend_data') || ($data[0]['lesson_status'] == 'passed' && $column_name == 'session_time')) {
    //             $builder = $this->db->table('scorm_user_details as su');
    //             $builder->set($column_name, $value);
    //             $builder->set('last_active',  time());
    //             $builder->set('completion_date', time());
    //             $builder->set('last_updated_on', time());
    //             $builder->set('last_updated_by', session()->get('id_user'));
    //             $builder->where('sc_uid', $data['0']['sc_uid']);
    //             $builder->where('status', 1);
    //             $builder->update();
    //             $data = $builder->get()->getResultArray();
    //             $builder = $this->db->table('scorm_users_courses_assigned as sa');
    //             $builder->set('course_status', 2);
    //             $builder->set('last_updated_on', time());
    //             $builder->where('id_user', session()->get('id_user'));
    //             $builder->where('course_id', $course_id);
    //             $builder->where('status', 1);
    //             $builder->update();
    //             $edata = $builder->get()->getResultArray();
    //             if (!empty($data)) {
    //                 $data['status'] = "true";
    //             } else {
    //                 $data['status'] = "false";
    //             }
    //             // return $data;
    //         }
    //         return $data;
    //     } else {

    //         $newdata = [
    //             'student_id' => $student_id,
    //             'student_name' => $student_name,
    //             'course_id' => $course_id,
    //             $column_name =>  $value,
    //             'last_active' => time(),
    //             'status' => 1,
    //             'createdby' => session()->get('id_user'),
    //             'createdon' => time(),
    //             'last_updated_by' =>  session()->get('id_user'),
    //             'last_updated_on' => time()
    //         ];
    //         $db = \Config\Database::connect();
    //         $builder = $this->db->table('scorm_user_details');
    //         $builder->insert($newdata);
    //         $data = $builder->get()->getResultArray();
    //         if (!empty($data)) {
    //             $data['status'] = "true";
    //         } else {
    //             $data['status'] = "false";
    //         }
    //         return $data;
    //     }
    // }
    public function updatescormuserdetails($student_id, $student_name, $course_id, $column_name, $value)
    {
        $now = time();
        $userId = session()->get('id_user');

        $builder = $this->db->table('scorm_user_details');
        $builder->where('student_id', $student_id);
        $builder->where('course_id', $course_id);
        $builder->orderBy('sc_uid', 'DESC');

        $existing = $builder->get()->getResultArray();

        // If record exists
        if (!empty($existing)) {
            $record = $existing[0]; // Latest record

            $lessonStatus = strtolower($record['lesson_status']);

            // Case 1: Not complete yet
            if (
                in_array($lessonStatus, ['incomplete', 'not started', 'failed']) &&
                $record['status'] == '1'
            ) {
                $updateData = [
                    $column_name => $value,
                    'last_active' => $now,
                    'last_updated_on' => $now,
                    'last_updated_by' => $userId
                ];

                if ($lessonStatus == 'not started') {
                    $updateData['attempt'] = 1;
                }

                $this->db->table('scorm_user_details')
                    ->where('sc_uid', $record['sc_uid'])
                    ->where('status', 1)
                    ->update($updateData);

                // Ensure course is marked in-progress if not already completed
                $this->db->table('scorm_users_courses_assigned')
                    ->where('id_user', $student_id)
                    ->where('course_id', $course_id)
                    ->where('course_status !=', 2)
                    ->where('status', 1)
                    ->update(['course_status' => 1]);

                return ['status' => "true"];
            }

            // Case 2: Already completed/passed — only update certain fields
            if (
                ($lessonStatus == 'completed' || in_array($column_name, ['suspend_data', 'session_time'])) ||
                ($lessonStatus == 'passed' || in_array($column_name, ['suspend_data', 'session_time']))
            ) {
                $updateData = [
                    $column_name => $value,
                    'last_active' => $now,
                    'completion_date' => $now,
                    'last_updated_on' => $now,
                    'last_updated_by' => $userId
                ];

                $this->db->table('scorm_user_details')
                    ->where('sc_uid', $record['sc_uid'])
                    ->where('status', 1)
                    ->update($updateData);

                // Mark course as completed
                $this->db->table('scorm_users_courses_assigned')
                    ->where('id_user', $student_id)
                    ->where('course_id', $course_id)
                    ->where('status', 1)
                    ->update([
                        'course_status' => 2,
                        'last_updated_on' => $now
                    ]);

                return ['status' => "true"];
            }

            // No update performed
            return ['status' => "false"];
        }

        // If no existing record — create new one
        $newData = [
            'student_id' => $student_id,
            'student_name' => $student_name,
            'course_id' => $course_id,
            $column_name => $value,
            'last_active' => $now,
            'status' => 1,
            'createdby' => $userId,
            'createdon' => $now,
            'last_updated_by' => $userId,
            'last_updated_on' => $now
        ];

        $inserted = $this->db->table('scorm_user_details')->insert($newData);

        return ['status' => $inserted ? "true" : "false"];
    }

    function getscormuserdetails($student_id, $course_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_user_details as su');
        $builder->select('su.*');
        $builder->where('student_id', $student_id);
        $builder->where('course_id', $course_id);
        // $builder->where('sc_uid', session()->get('userUniqueID'));
        $builder->orderBy('sc_uid', 'DESC');
        $builder->limit(1);
        $data = $builder->get()->getResultArray();
        // print_r($builder);
        // exit();
        if (!empty($data)) {
            $data['status'] = "true";
        } else {
            $data['status'] = "false";
        }
        return $data;
    }
    function addyoutubledetails($course_id, $videoUrl)
    {
        // print_r($videoUrl);
        // exit();
        $builder = $this->db->table('scorm_user_details as su');
        $builder->select('su.*');
        $builder->where('student_id', session()->get('id_user'));
        $builder->where('course_id', $course_id);

        $builder->orderBy('sc_uid', 'DESC');
        $builder->limit(1);
        $data = $builder->get()->getResultArray();
        // print_r($data);
        // exit();
        if (empty($data)) {
            $last_active = date('m-d-Y H:i:s', time());
            $newdata = [
                'student_id' => session()->get('id_user'),
                'student_name' => session()->get('name'),
                'course_id' => $course_id,
                'lesson_status' => 'completed',
                'lesson_location' => $videoUrl,
                'attempt' => 1,
                'status' => 1,
                'last_active' => time(),
                'createdby' => session()->get('id_user'),
                'createdon' => time(),
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time()
            ];

            $builder = $this->db->table('scorm_user_details');
            $builder->insert($newdata);
            $builder = $this->db->table('scorm_users_courses_assigned as sa');
            $builder->select('sa.*');
            $builder->where('id_user', session()->get('id_user'));
            $builder->where('course_id', $course_id);
            $builder->where('status', 1);
            $data = $builder->get()->getResultArray();
            if (empty($data)) {
                $scorm_users_courses_assigned = [
                    'client_id' => session()->get('client'),
                    'id_user' => session()->get('id_user'),
                    'course_id' => $course_id,
                    'status' => 1,
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    // 'last_updated_by' =>  session()->get('id_user'),
                    // 'last_updated_on' => time()
                ];
                $builder = $this->db->table('scorm_users_courses_assigned');
                $builder->insert($scorm_users_courses_assigned);
            }
        } elseif (!empty($data)) {
            if ($data[0]['lesson_status'] == 'completed') {
                $newdata = [
                    'student_id' => session()->get('id_user'),
                    'student_name' => session()->get('name'),
                    'course_id' => $course_id,
                    'lesson_status' => 'completed',
                    'lesson_location' => $videoUrl,
                    'attempt' => $data[0]['attempt'] + 1,
                    'status' => 1,
                    'last_active' => time(),
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time()
                ];
                $builder = $this->db->table('scorm_user_details');
                $builder->insert($newdata);
                $builder = $this->db->table('scorm_users_courses_assigned as sa');
                $builder->select('sa.*');
                $builder->where('id_user', session()->get('id_user'));
                $builder->where('course_id', $course_id);
                $builder->where('status', 1);
                $data = $builder->get()->getResultArray();
                if (empty($data)) {
                    $scorm_users_courses_assigned = [
                        'client_id' => session()->get('client'),
                        'id_user' => session()->get('id_user'),
                        'course_id' => $course_id,
                        'course_status' => 2,
                        'status' => 1,
                        'createdby' => session()->get('id_user'),
                        'createdon' => time(),
                        // 'last_updated_by' =>  session()->get('id_user'),
                        // 'last_updated_on' => time()
                    ];
                    $builder = $this->db->table('scorm_users_courses_assigned');
                    $builder->insert($scorm_users_courses_assigned);
                }
            }
        }
    }
    //PC CODE Check for duplicate

    function get_course_assigned_id($userid, $course_id)
    {
        $builder = $this->db->table('scorm_users_courses_assigned as c');
        $builder->select('c.*');
        $builder->where('c.id_user', $userid);
        $builder->where('c.course_id', $course_id);
        $builder->orderBy('c.user_assign_id', 'DESC');
        $builder->limit(1);
        $builder->where('c.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function get_scorm_track_id($user_assign_id)
    {
        $builder = $this->db->table('scorm_user_details as c');
        $builder->select('c.*');
        $builder->where('c.user_assign_id', $user_assign_id);
        $builder->orderBy('c.sc_uid', 'DESC');
        $builder->limit(1);
        $builder->where('c.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function update_scorm_record($update_scorm_record, $scorm_track_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_user_details');
        $builder->where('sc_uid', $scorm_track_id);
        $builder->update($update_scorm_record);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function insert_scorm_record($scorm_record)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('scorm_user_details');
        $builder->insert($scorm_record);
        $builder = $this->db->table('scorm_users_courses_assigned');
        $builder->set('course_status', 1);
        $builder->where('user_assign_id', $scorm_record['user_assign_id']);
        $builder->where('status', 1);
        $builder->update();
        return $db->insertID();
    }

    //PC CODE Ends
    function coursedetails($course_id)
    {
        $builder = $this->db->table('scorm_courses as c');
        $builder->select('c.*');
        $builder->where('c.scourse_id', $course_id);
        $builder->where('c.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getpagedetails($course_id)
    {
        $builder = $this->db->table('page as p');
        $builder->select('p.*');
        $builder->where('p.fk_course_id', $course_id);
        $builder->where('p.sub_page_main', 0);
        $builder->where('p.status !=', 0);
        $builder->orderBy('p.page_number');
        $data = $builder->get()->getResultArray();
        return $data;
    }


    function getAllFeedbackByCourseId($course_id, $stage)
    {
        $client = session()->get('client');
        $builder = $this->db->table('feedback as f');
        $builder->select('f.*, u.name as fname, p.page_name as pagename, p.page_number as pnumber, p.type as pagetype');
        $builder->join('users as u', 'u.id_user = f.createdby', 'left');
        $builder->join('page as p', 'p.page_id = f.pageid', 'left');
        $builder->where('f.course_id', $course_id);
        $builder->where('f.stage', $stage);
        $builder->where('f.status !=', 0);
        if ($client != 1) {
            $builder->where('f.type', 2);
        }
        $builder->orderBy('p.page_number ASC');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getcountFeedbackByCourseId($course_id)
    {
        $client = session()->get('client');
        $builder = $this->db->table('feedback as f');
        $builder->select('count(f.feedbackid) as feedback_count,f.stage');
        $builder->where('f.course_id', $course_id);
        $builder->where('f.status !=', 0);
        $builder->groupBy('f.stage');
        if ($client != 1) {
            $builder->where('f.type', 2);
        }
        // $builder->orderBy('p.page_number ASC');
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getAllFeedbackByPageID($page_id)
    {
        $builder = $this->db->table('feedback as f');
        $builder->select('f.*, u.name as fname');
        $builder->join('users as u', 'u.id_user = f.createdby', 'left');
        $builder->where('f.pageid', $page_id);
        $builder->where('f.type', 1);
        $builder->where('f.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getFeedbackReplies($feedbackId)
    {
        $builder = $this->db->table('feedback_replies as fr');
        $builder->select('fr.*, u.name as fname');
        $builder->join('users as u', 'u.id_user = fr.createdby', 'left');
        $builder->where('fr.feedbackid', $feedbackId);
        $builder->where('fr.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    function getFeedbackDetails($feedbackId)
    {
        $builder = $this->db->table('feedback as f');
        $builder->select('f.*, u.name as fname, uu.name as updatedby');
        $builder->join('users as u', 'u.id_user = f.createdby', 'left');
        $builder->join('users as uu', 'uu.id_user = f.last_updated_by', 'left');
        $builder->where('f.feedbackid', $feedbackId);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getAllFeedback($page_id)
    {
        $client = session()->get('client');

        $builder = $this->db->table('feedback as f');
        $builder->select('f.*, u.name as fname, u.profile_foldername, u.profile_image, u.id_user');
        $builder->join('users as u', 'u.id_user = f.createdby', 'left');
        // $builder->join('profile as p', 'p.username = u.username', 'left');
        $builder->where('f.pageid', $page_id);
        if ($client != 1) {
            $builder->where('f.type', 2);
        }
        $builder->where('f.status !=', 0);
        $builder->orderBy('f.feedbackid DESC');
        // $builder->groupBy('f.feedbackid ');
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    function getAllQAFeedback($page_id)
    {
        $builder = $this->db->table('feedback as f');
        $builder->select('f.*, u.name as fname, u.profile_foldername, u.profile_image, u.id_user');
        $builder->join('users as u', 'u.id_user = f.createdby', 'left');
        // $builder->join('profile as p', 'p.username = u.username', 'left');
        $builder->where('f.pageid', $page_id);
        $builder->where('f.type', 2);
        $builder->where('f.status', 1);
        $builder->orderBy('f.feedbackid DESC');
        // $builder->groupBy('f.feedbackid ');
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }
    function getAllFeedback_replies($feedbackid)
    {
        $builder = $this->db->table('feedback_replies as r');
        $builder->select('r.*,r.feedbackid as replyfeedbackid,r.feedback as feedback_replies,u1.name as fname1,u1.profile_foldername, u1.profile_image, u1.id_user');
        $builder->join('users as u1', 'u1.id_user = r.createdby', 'left');
        // $builder->join('profile as p1', 'p1.username = u1.username', 'left');
        $builder->where('r.feedbackid', $feedbackid);
        $builder->where('r.status', 1);
        $builder->orderBy('r.feedbackid DESC');
        // $builder->groupBy('f.feedbackid ');
        $data = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit();
        return $data;
    }


    function getAudioscript($page_id)
    {
        $builder = $this->db->table('page_content as p');
        $builder->select('p.*');
        $builder->where('p.page_id', $page_id);
        $builder->where('p.status !=', 0);
        $builder->orderBy('p.page_sequense');
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function update_reviewstatus($newdata, $course_id)
    {
        $builder = $this->db->table('scorm_users_courses_assigned');
        $builder->where('course_id', $course_id);
        $builder->where('id_user ', session()->get('id_user'));
        $builder->where('status !=', 0);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getClientDetails($course_id)
    {
        $builder = $this->db->table('scorm_courses as sc');
        $builder->select('c.*');
        $builder->join('projects as p', 'p.projectid = sc.project_id', 'left');
        $builder->join('client as c', 'c.id_c = p.client', 'leftF');
        $builder->where('sc.scourse_id', $course_id);
        $builder->where('p.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function update_course_status($user_assign_id, $status)
    {
        $builder = $this->db->table('scorm_users_courses_assigned as sa');
        $builder->set('course_status', $status);
        $builder->set('last_updated_on', time());
        $builder->where('id_user', session()->get('id_user'));
        $builder->where('user_assign_id', $user_assign_id);
        $builder->where('status', 1);
        $builder->update();
    }
    // public function update_total_time($sc_uid, $seconds)
    // {
    //     return $this->db->query(
    //         "UPDATE scorm_user_details
    //      SET total_time = SEC_TO_TIME(
    //          TIME_TO_SEC(total_time) + ?
    //      )
    //      WHERE sc_uid = ?",
    //         [$seconds, $sc_uid]
    //     );
    // }
    public function update_total_time($sc_uid, $seconds)
    {
        return $this->db->query(
            "UPDATE scorm_user_details
         SET total_time = SEC_TO_TIME(
             TIME_TO_SEC(IFNULL(total_time, '00:00:00')) + ?
         )
         WHERE sc_uid = ?",
            [$seconds, $sc_uid]
        );
    }
}
