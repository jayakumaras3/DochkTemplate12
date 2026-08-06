<?php

namespace App\Controllers\Certification;

use App\Controllers\BaseController;
use App\Models\Certification\Certification_model;
use App\Models\Marketplace\M_Dashboard_model;
use App\Models\User_login\Users_model;
use App\Models\SCORM\Scorm_user_group_model;
use App\Models\Settings\Dropdown_model;
use App\Models\SCORM\Scorm_dashboard_model;
use App\Models\Certification\CertificationPaymentModel;
use App\Models\SCORM\Scorm_course_model;
use App\Models\Social\Post_model;


#[\AllowDynamicProperties]


class Certification_Portal extends BaseController
{


    public function __construct()
    {
        $this->Certification_model = new Certification_model();
        $this->M_Dashboard_model = new M_Dashboard_model();
        $this->users_model = new Users_model();
        $this->scorm_user_group_model = new Scorm_user_group_model();
        $this->dropdown_model = new Dropdown_model();
        $this->scorm_dashboard_model = new Scorm_dashboard_model();
        $this->CertificationPaymentModel = new CertificationPaymentModel();
        $this->certification_model = new Certification_model();
        $this->scorm_course_model = new Scorm_course_model();
        $this->Post_model = new Post_model();
    }

    public function index()
    {
        if ($response =  $this->requireRole(['5', '44', '3'])) {
            return $response;
        }
        $data = [];
        $client = session()->get('client');
        $data['client_id'] = $client;
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);
        // $this->Certification_model->json_all_certifications($client);
        $purchasedCertificates = $this->CertificationPaymentModel->getPurchasedCertificates(session()->get('id_user'));

        $data['purchasedCertificates'] = array_column($purchasedCertificates, 'certificate_id');

        if (in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel)) {
            $data['get_all_certificates'] = $this->Certification_model->get_all_certifications($client);
        } else {
            $data['get_all_certificates'] = $this->Certification_model->get_all_certification_for_user($client, session()->get('id_user'));
        }
        echo view('templates/header_view', $data);
        // echo view('certification/certification_portal/left_menu', $data);
        echo view('certification/certification_portal/index', $data);
        echo view('templates/footer_view', $data);
    }
    public function certificationDetails()
    {
        if ($response =  $this->requireRole(['5', '44', '3'])) {
            return $response;
        }
        $data = [];

        if (isset($_POST['certificate_id'])) {
            $data['certificate_id'] = $_POST['certificate_id'];
            $_SESSION['certificate_id'] = $data['certificate_id'];
        } elseif (isset($_SESSION['certificate_id'])) {
            $data['certificate_id'] = $_SESSION['certificate_id'];
        } else {
            // session()->setFlashdata('error', lang('Messages.Error_0003'));
        }
        if (isset($_POST['cert_name'])) {
            $data['cert_name'] = $_POST['cert_name'];
            $_SESSION['cert_name'] = $data['cert_name'];
        } elseif (isset($_SESSION['cert_name'])) {
            $data['cert_name'] = $_SESSION['cert_name'];
        } else {
            // session()->setFlashdata('error', lang('Messages.Error_0003'));
        }
        $data['detail_type'] = $this->request->getPost('detail_type');

        if (isset($_POST['type'])) {
            $data['type'] = $_POST['type'];
            $_SESSION['type'] = $data['type'];
        } elseif (isset($_SESSION['type'])) {
            $data['type'] = $_SESSION['type'];
        } else {
            $data['type'] = 4;
        }
        $data['isPurchased'] = $this->CertificationPaymentModel->isCertificatePurchased(session()->get('id_user'), $data['certificate_id']);
        // $purchasedCertificates = $this->CertificationPaymentModel->getPurchasedCertificates(session()->get('id_user'));

        // $data['purchasedCertificates'] = array_column($purchasedCertificates, 'certificate_id');

        $client_id = session()->get('client');
        $data['client_id'] = $client_id;
        $user_id = session()->get('id_user');
        // $data['get_courses'] = $this->M_Dashboard_model->get_courses($client_id, 0, $_SESSION['mp_language'], $data['cat_list']);
        $datac = $this->M_Dashboard_model->certification_learning_plan_courses($data['certificate_id'], $client_id, $user_id, $data['type']);
        $plans = [];


        foreach ($datac as $row) {

            $mpId = $row['mp_id'];

            if (!isset($plans[$mpId])) {
                $plans[$mpId] = [
                    'mp_id' => $row['mp_id'],
                    'mp_name' => $row['mp_name'],
                    'duration' => $row['duration'],
                    'thumbnail' => $row['thumbnail'],
                    'description' => $row['description'],
                    'mode' => $row['mode'],
                    'cert_name' => $row['cert_name'],
                    'learning_plan_status' => $row['learning_plan_status'],
                    'amount' => $row['amount'],
                    'courses' => []
                ];
            }

            $courseId = $row['scourse_id'];

            if ($courseId && !isset($plans[$mpId]['courses'][$courseId])) {

                $plans[$mpId]['courses'][$courseId] = [
                    'mp_id' => $row['mp_id'],
                    'mp_name' => $row['mp_name'],
                    'scourse_id' => $row['scourse_id'],
                    'course_name' => $row['course_name'],
                    'course_code' => $row['course_code'],
                    'course_duration' => $row['course_duration'],
                    'language' => $row['language'],
                    'avg_rating' => $row['avg_rating'],
                    'course_thumbnail' => $row['course_thumbnail'],
                    'amount' => $row['amount']
                ];
            }
        }
        /*
        |--------------------------------------------------------------------------
        | Certification Progress
        |--------------------------------------------------------------------------
        */

        $completedCourses = $this->certification_model
            ->getCompletedCourses($user_id);

        $completedIds = array_flip(
            array_column($completedCourses, 'course_id')
        );

        $totalCourses = 0;
        $completedCount = 0;

        $completedPlans = 0;
        $totalPlans = count($plans);

        foreach ($plans as &$plan) {

            $allCoursesCompleted = true;

            foreach ($plan['courses'] as &$course) {

                $totalCourses++;

                $course['completed'] = isset($completedIds[$course['scourse_id']]);

                if ($course['completed']) {
                    $completedCount++;
                } else {
                    $allCoursesCompleted = false;
                }
            }

            // Mark Learning Plan as completed
            $plan['completed'] = $allCoursesCompleted;

            if ($allCoursesCompleted) {
                $completedPlans++;
            }

            // Convert associative array to indexed array
            $plan['courses'] = array_values($plan['courses']);
        }



        unset($course);
        unset($plan);

        /*
        |--------------------------------------------------------------------------
        | Send to View
        |--------------------------------------------------------------------------
        */


        $data['totalCourses'] = $totalCourses;

        $data['completedCourses'] = $completedCount;

        $data['progressPercent'] = ($totalCourses > 0)
            ? round(($completedCount / $totalCourses) * 100)
            : 0;

        $data['totalPlans'] = $totalPlans;
        $data['completedPlans'] = $completedPlans;
        $data['get_certification_learning_plan_courses'] = array_values($plans);
        $data['certification'] = $this->Certification_model->get_certificate_details($data['certificate_id']);
        // print_r($data['certification']);
        // exit();

        // $data['getassignedclienttoeditable'] = $this->Certification_model->getassignedclienttoeditable($data['certificate_id'], $client_id);

        // print_r($data['get_certification_learning_plan_courses']);
        // exit();
        if (!empty($data['get_certification_learning_plan_courses'])) {
            $learningarrayid = array_column($data['get_certification_learning_plan_courses'], 'mp_id');
            $this->Certification_model->updateCertificationStatus($data['certificate_id'], $user_id, $learningarrayid, $data['type']);
        }
        // print_r($data);
        // exit();

        // Your logic here
        echo view('templates/header_view', $data);
        echo view('certification/certification_portal/certification_details', $data);
        echo view('templates/footer_view', $data);
    }
    public function courseDetails()
    {
        if ($response =  $this->requireRole(['5', '44', '3'])) {
            return $response;
        }
        $data = [];
        if (isset($_POST['crid'])) {
            $data['crid'] = $_POST['crid'];
            $_SESSION['crid'] = $data['crid'];
        } elseif (isset($_SESSION['crid'])) {
            $data['crid'] = $_SESSION['crid'];
        } else {
        }
        if (isset($_POST['certificate_id'])) {
            $data['certificate_id'] = $_POST['certificate_id'];
            $_SESSION['certificate_id'] = $data['certificate_id'];
        } elseif (isset($_SESSION['certificate_id'])) {
            $data['certificate_id'] = $_SESSION['certificate_id'];
        } else {
        }
        $mp_id = 0;
        $cdata_type = 1;
        $data['isPurchased'] = $this->CertificationPaymentModel->isCertificatePurchased(session()->get('id_user'), $data['certificate_id']);
        $purchasedCertificates = $this->CertificationPaymentModel->getPurchasedCertificates(session()->get('id_user'));

        $data['purchasedCertificates'] = array_column($purchasedCertificates, 'certificate_id');


        $id_user = session()->get('id_user');
        $client = session()->get('client');
        $isPurchased = $this->CertificationPaymentModel->isCertificatePurchased(session()->get('id_user'), $data['certificate_id']);
        $data['getAllObjectives'] = $this->scorm_course_model->getAllObjectives($data['crid']);

        if (!$isPurchased) {
            $data['clientCourseddata'] = $this->scorm_dashboard_model->getCoursesdetailslibrary($data['crid']);
        } else {
            if (!$this->scorm_dashboard_model->isassignCourseToUser($id_user, $data['crid'])) {
                $this->scorm_dashboard_model->assigncoursetouser($id_user, $data['crid'], $mp_id, 1);
            }

            $data['clientCourseddata'] = $this->scorm_dashboard_model->getCoursesdetails($data['crid']);
        }
        $_SESSION['course_lang'] = !empty($data['clientCourseddata'][0]['language']) ? $data['clientCourseddata'][0]['language'] : 'English';
        $data['getCoursesAssigned'] = $this->scorm_dashboard_model->getCoursesAssigned($id_user, $data['crid']);
        $data['editableCoursedata'] = $this->scorm_dashboard_model->editableCoursedata($client, $data['crid']);
        $data['pagedata'] = $this->scorm_dashboard_model->getpagedetails($data['crid']);
        $data['getAllObjectives'] = $this->scorm_course_model->getAllObjectives($data['crid']);
        $data['ifFavorite'] = $this->scorm_course_model->isFavorite($data['crid'], $id_user);

        $data['my_all_post'] = $this->Post_model->getPostCount($id_user, $data['crid']);
        $active_posts = $this->Post_model->get_active_posts($client, $id_user, $data['crid']);
        $replies = [];

        foreach ($active_posts as &$post) {
            $post['time_ago'] = time_elapsed_string('@' . $post['last_updated_on']);
            $post_replies = $this->Post_model->getpost_replies($post['social_id']);

            foreach ($post_replies as &$reply) {
                $reply['time_ago'] = time_elapsed_string('@' . $reply['last_updated_on']);
            }

            $replies[$post['social_id']] = $post_replies;
        }

        $data['active_posts'] = $active_posts;
        $data['replies'] = $replies;
        $this->Post_model->updatePostCount($id_user);
        // Check if a certificate is assigned for this course (type=3)
        $data['certificate_assign'] = $this->certification_model->get_certificate_assignment($data['crid'], $client, 3);

        echo view('templates/header_view', $data);
        echo view('certification/certification_portal/course_details', $data);
        echo view('templates/footer_view');
    }

    public function buyNowDetails()
    {
        $certificateId = $this->request->getPost('certificate_id');

        if (isset($certificateId)) {

            $_SESSION['certificate_id'] = $certificateId;
        } elseif (isset($_SESSION['certificate_id'])) {

            $certificateId = $_SESSION['certificate_id'];
        } else {

            session()->setFlashdata(
                'error',
                lang('Messages.Error_0003')
            );

            return redirect()->to(
                base_url('Certification/Certification_Portal')
            );
        }

        helper('discount');

        $paymentModel = new CertificationPaymentModel();

        $getcertificationDetails =
            $this->certification_model
            ->get_certificate_details($certificateId);

        $pricing =
            $paymentModel->getCertificatePricingDetails(
                $certificateId
            );

        $couponCode = session()->get('coupon_code');

        $coupon = null;

        if (!empty($couponCode)) {

            $coupon =
                $paymentModel->validateCoupon(
                    $certificateId,
                    $couponCode
                );
        }

        $amounts =  calculateCertificationAmount($pricing, $coupon);

        $data = [

            'certificate_id' => $certificateId,

            'originalAmount' => $amounts['originalAmount'],

            'discountAmount' => $amounts['discountAmount'],

            'couponAmount' => $amounts['couponAmount'],

            'finalAmount' => $amounts['finalAmount'],

            'couponCode' => $couponCode,

            'getcertificationDetails' => $getcertificationDetails
        ];

        $data['certificate_name'] =
            $getcertificationDetails[0]['name'];

        $data['course_count'] =
            $getcertificationDetails[0]['course_count'];

        $data['learning_plan_count'] =
            $getcertificationDetails[0]['learning_plan_count'];

        echo view('templates/header_view', $data);
        echo view('certification/certification_portal/buy_now_details', $data);
        echo view('templates/footer_view');
    }
    public function paymentHistory()
    {
        if ($response = $this->requireRole(['5', '44', '3'])) {
            return $response;
        } 

        $data['paymentHistory'] = $this->CertificationPaymentModel
            ->getPaymentHistory(session()->get('id_user'));
        // print_r($data['paymentHistory']);
        // exit();

        echo view('templates/header_view', $data);
        echo view('certification/certification_portal/payment_history', $data);
        echo view('templates/footer_view');
    }
    public function assessmentLauncher($certificateId)
    {
        $certificate = $this->Certification_model->getCertificateById($certificateId);

        // echo "<pre>";
        // print_r($certificate);
        // exit;

        if (empty($certificate)) {
            return redirect()->back();
        }

        $_SESSION['crid'] = $certificate['assessment_course_id'];
        // print_r($_SESSION['crid']);
        // exit();
        $_SESSION['course_detail_launch'] = 1;
        $_SESSION['course_lang'] = 'English';

        $data['pagedata'] = $this->scorm_dashboard_model->getpagedetails($_SESSION['crid']);

        return redirect()->to(
            base_url('SCORM/course_builder/review_course/launcher/1/1')
        );
    }
}
