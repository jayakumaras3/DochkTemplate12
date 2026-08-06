<?php

namespace App\Controllers\Marketplace;

use App\Controllers\BaseController;
use App\Models\Marketplace\M_Dashboard_model;
use App\Models\Certification\Certification_model;

#[\AllowDynamicProperties]
class Learning_plan_detail extends BaseController
{
    public function __construct()
    {
        $this->is_session_available();
        $this->M_Dashboard_model = new M_Dashboard_model();
        $this->certification_model = new Certification_model();
    }

    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url());
            exit();
        }
    }

    // Classifies a course's completion state the same way scorm_dashboard_view.php groups
    // assigned courses, so status badges/counts stay consistent across the app.
    private function courseState($courseStatus, $lessonStatus)
    {
        if ($courseStatus == 2 || $lessonStatus == 'completed' || $lessonStatus == 'passed') {
            return 'completed';
        }
        if ($courseStatus == 1 || $lessonStatus == 'incomplete' || $lessonStatus == 'failed') {
            return 'in_progress';
        }
        return 'not_started';
    }

    public function index()
    {
        if ($response = $this->requireRole(['5', '44', '3'])) {
            return $response;
        }

        if (isset($_POST['mp_id'])) {
            $mp_id = $_POST['mp_id'];
            session()->set('mp_id', $mp_id);
        } elseif (session()->get('mp_id')) {
            $mp_id = session()->get('mp_id');
        } else {
            return redirect()->to(base_url('marketplace/learning_dashboard'));
        }

        if (isset($_POST['type'])) {
            $type = $_POST['type'];
            session()->set('type', $type);
        } else {
            $type = session()->get('type') ?? 2;
        }

        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);
        $canManageLP = in_array('44', $arrayuserlevel) || in_array('5', $arrayuserlevel);
        $canAssignClients = session()->get('client') == 1;

        $learning_plan_details = $this->M_Dashboard_model->get_marketplace_details($mp_id);
        $course_details = $this->M_Dashboard_model->get_learning_plan_each_courses($mp_id);

        $user_id = session()->get('id_user');
        if (!empty($course_details)) {
            $coursearrayid = array_column($course_details, 'scorm_id');
            $this->M_Dashboard_model->updateLearningPlanStatus($mp_id, $user_id, $coursearrayid, $type);
        }
        $this->M_Dashboard_model->assign_certificateuser_to_lp($mp_id, $user_id, $type);

        $client_id = session()->get('client');
        $certificate_assign = $this->certification_model->get_certificate_assignment($mp_id, $client_id, 2);

        // Build per-course display state (status, sequential lock) plus the plan-wide rollups
        // (progress %, duration totals, the "continue learning" course) in a single pass.
        $mode = $learning_plan_details['mode'] ?? 2;
        $totalCourses = count($course_details);
        $completedCount = 0;
        $inProgressCount = 0;
        $notStartedCount = 0;
        $totalDuration = 0;
        $remainingDuration = 0;
        $continueCourse = null;
        $anyIncompleteSoFar = false;
        $courses = [];

        foreach ($course_details as $course) {
            $state = $this->courseState($course['course_status'] ?? null, $course['lesson_status'] ?? null);

            $locked = ($mode == 1 && $anyIncompleteSoFar && $state !== 'completed');

            if ($state === 'completed') {
                $completedCount++;
            } elseif ($state === 'in_progress') {
                $inProgressCount++;
            } else {
                $notStartedCount++;
            }

            if ($state !== 'completed') {
                $anyIncompleteSoFar = true;
                $remainingDuration += (int) $course['duration'];
                if ($continueCourse === null && !$locked) {
                    $continueCourse = $course;
                }
            }

            $totalDuration += (int) $course['duration'];

            $course['state'] = $state;
            $course['locked'] = $locked;
            $courses[] = $course;
        }

        $progressPercent = $totalCourses > 0 ? (int) round(($completedCount / $totalCourses) * 100) : 0;

        $data = [];
        $data['type'] = $type;
        $data['mp_id'] = $mp_id;
        $data['mp_name'] = $learning_plan_details['mp_name'] ?? '';
        if ($type == 1) {
            $data['header_title'] = lang('Buttons.Marketplace Dashboard');
            $data['header_link'] = 'marketplace/dashboard';
        } else {
            $data['header_title'] = lang('Buttons.Learning Plan Dashboard');
            $data['header_link'] = 'marketplace/learning_dashboard';
        }

        $data['learning_plan_details'] = $learning_plan_details;
        $data['courses'] = $courses;
        $data['certificate_assign'] = $certificate_assign;
        $data['canManageLP'] = $canManageLP;
        $data['canAssignClients'] = $canAssignClients;
        $data['totalCourses'] = $totalCourses;
        $data['completedCount'] = $completedCount;
        $data['inProgressCount'] = $inProgressCount;
        $data['notStartedCount'] = $notStartedCount;
        $data['totalDuration'] = $totalDuration;
        $data['remainingDuration'] = $remainingDuration;
        $data['progressPercent'] = $progressPercent;
        $data['continueCourse'] = $continueCourse;

        echo view('templates/header_view', $data);
        echo view('marketplace/new/lp_detail_view', $data);
        echo view('templates/footer_view', $data);
    }
}
