<?php

namespace App\Controllers;

use App\Models\SCORM\Scorm_dashboard_model;
use App\Models\SCORM\Scorm_client_model;
use App\Models\SCORM\Scorm_learn_group_model;
use App\Models\SCORM\CourseRating_model;
use App\Models\SCORM\Scorm_course_model;
use App\Models\Social\Post_model;
use App\Models\Payment\Billing_model;
use App\Models\Certification\Certification_model;
use App\Models\Marketplace\M_Dashboard_model;
use App\Models\Certification\CertificationPaymentModel;
use App\Models\XAPI\XAPI_scenarios_model;




#[\AllowDynamicProperties]

class My_training extends BaseController
{
    private $db;

    public function __construct()
    {

        $this->scorm_dashboard_model = new Scorm_dashboard_model();
        $this->scorm_client_model = new Scorm_client_model();
        $this->scorm_learn_group_model = new Scorm_learn_group_model();
        $this->courseRating_model = new CourseRating_model();
        $this->scorm_course_model = new Scorm_course_model();
        $this->Post_model = new Post_model();
        $this->billing_model = new Billing_model();
        $this->certification_model = new Certification_model();
        $this->M_Dashboard_model = new M_Dashboard_model();
        $this->CertificationPaymentModel = new CertificationPaymentModel();
        $this->xapi_scenarios_model = new XAPI_scenarios_model();
    }
    public function index()
    {

        if ($response =  $this->requireRole(['6', '3', '44'])) {
            return $response;
        }
        $data = [];
        // $data['header'] = ucwords(lang('Buttons.Dashboard'));
        $data['type'] = 0;
        $id_user = session('id_user');
        $client_id = session()->get('client');
        $data['client_id'] = session()->get('client');
        // $data['user_count'] = $this->scorm_dashboard_model->getClientUserCount();
        //$data['assign_course_count'] = $this->scorm_dashboard_model->coursesAssignedCount($id_user);
        // $data['demo_count'] = $this->scorm_dashboard_model->democourseCount();

        if ($this->request->getPost('search')) {
            $data['search'] = $this->request->getPost('search');
            $data['clientCourseddata'] = $this->scorm_dashboard_model->getsearchCoursesAssignedToMe($id_user, $data['search']);
        } else {
            $data['clientCourseddata'] = $this->scorm_dashboard_model->getCoursesAssignedToMeSummary($id_user);
        }
        $data['favoriteCourseddata'] = $this->scorm_dashboard_model->getFavoriteCourses($id_user);
     //   $data['get_learning_plan'] = $this->M_Dashboard_model->get_learning_plan_courses();

      //  $data['get_certification'] = $this->certification_model->get_all_certificates_for_user($client_id, $id_user);
        // print_r($data['get_certification']);
        // exit();
        // echo view('templates/header_view', $data);
        // echo view('SCORM/scorm_dashboard/scorm_dashboard_view');
        // echo view('templates/footer_view');
        return $this->response->setBody(
            view('templates/header_view', $data) .
                view('SCORM/scorm_dashboard/scorm_dashboard_view', $data) .
                view('templates/footer_view')
        );
    }

    public function Favorites()
    {
        if ($response =  $this->requireRole(['6', '3', '44', '20'])) {
            return $response;
        }
        $data = [];
        $data['header'] = lang('Buttons.Favorites');
        $id_user = session()->get('id_user');
        $data['favorite_courses'] = $this->scorm_dashboard_model->getFavoriteCourses($id_user);
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_dashboard/favorites_view', $data);
        echo view('templates/footer_view');
    }

    public function tq_library()
    {
        if ($response =  $this->requireRole(['6', '3', '44'])) {
            return $response;
        }
        $data = [];
        $data['header'] = 'TQ Library';
        $data['clientCourseddata'] = $this->scorm_dashboard_model->getC4UCourses();
        // print_r($data['clientCourseddata']);
        //  exit();
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_dashboard/tq_library_dashboard_view', $data);
        echo view('templates/footer_view');
    }
    public function user_learn_group()
    {
        if ($response =  $this->requireRole(['6', '3', '44'])) {
            return $response;
        }
        $data = [];
        $data['header'] = 'Dashboard';
        $data['type'] = 0;
        $id_user = session()->get('id_user');
        // $data['learngroup'] = $this->scorm_learn_group_model->getlearngroupdata($id_user);
        $client = session()->get('client');
        $data['client_group'] = $this->scorm_learn_group_model->get_client_group($client);
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_dashboard/user_learn_group_view');
        echo view('templates/footer_view');
    }
    public function course_group()
    {
        if ($response =  $this->requireRole(['6', '3', '44'])) {
            return $response;
        }
        $data = [];
        $data['header'] = 'Dashboard';
        $data['type'] = 0;
        $id_user = session()->get('id_user');
        // $data['learngroup'] = $this->scorm_learn_group_model->getlearngroupdata($id_user);
        $client = session()->get('client');
        $data['client_group'] = $this->scorm_learn_group_model->get_client_group_all($client);
        $data['client_group_to_me'] = $this->scorm_learn_group_model->get_client_group_to_me($client, $id_user);
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_dashboard/user_learn_group_view', $data);
        echo view('templates/footer_view');
    }

    public function course_group_list()
    {
        if ($response =  $this->requireRole(['6', '3', '44'])) {
            return $response;
        }
        $data = [];
        $data['header'] = 'Dashboard';
        $data['type'] = 0;
        if (isset($_POST['sc_cgid'])) {
            $data['sc_cgid'] = $_POST['sc_cgid'];
            $_SESSION['sc_cgid'] = $data['sc_cgid'];
        } else if (isset($_SESSION['sc_cgid'])) {
            $data['sc_cgid'] = $_SESSION['sc_cgid'];
        } else {
            session()->setFlashdata('error', 'Select a Group to view details.');
            return redirect()->to(base_url() . 'my_training/course_group');
        }

        $data['all_courses'] = $this->scorm_course_model->coursenamessearch_view20();
        $data['assigned_courses'] = $this->scorm_learn_group_model->getCoursesAssignedto($data['sc_cgid']);
        // print_r($data['assigned_courses']);
        // exit();
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_dashboard/user_learn_group_detail_view');
        echo view('templates/footer_view');
    }

    public function pm_add_course_to_group()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        helper(['form']);

        // group_id is a NOT NULL column (scorm_course_group_assigned), so a missing/empty
        // sc_cgid here must not reach the model - it would otherwise surface as a raw DB
        // error ("Column 'group_id' cannot be null") instead of a normal flash message.
        if (empty($_POST['sc_cgid']) || empty($_POST['course_id'])) {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->to(base_url() . 'my_training/course_group_list');
        }

        $newData = [
            'course_id' => $_POST['course_id'],
            'group_id' => $_POST['sc_cgid'],
            'status' => 1,
            'createdby' => session()->get('id_user'),
            'createdon' => time(),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $this->scorm_learn_group_model->add_course_to_gr($newData);
        session()->setFlashdata('success', lang('Messages.Success_0001'));
        return redirect()->to(base_url() . 'my_training/course_group_list');
    }

    public function user_categogies()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        $client = session()->get('client');
        $data['header'] = 'Dashboard';
        $data['categoriesofclient'] = $this->scorm_dashboard_model->getCategoriesofclient($client);
        // print_r($data['categoriesofclient']);
        // exit();
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_dashboard/user_category_view');
        echo view('templates/footer_view');
    }
    public function view_category_courses()
    {
        if ($response =  $this->requireRole(['6', '44', '4', '3'])) {
            return $response;
        }
        $data = [];
        $client = session()->get('client');
        $data['header'] = 'Dashboard';
        if (isset($_POST['category_id'])) {
            $data['category_id'] = $_POST['category_id'];
            $_SESSION['category_id'] = $data['category_id'];
        } else if (isset($_GET['category_id'])) {
            $data['category_id'] = $_GET['category_id'];
        } else if (isset($_SESSION['category_id'])) {
            $data['category_id'] = $_SESSION['category_id'];
        }
        $data['coursesDetails'] = $this->scorm_course_model->courseCategoryssearch_view($data['category_id']);
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_dashboard/category_courses_view');
        echo view('templates/footer_view');
    }
    public function dashboardtable()
    {
        if ($response =  $this->requireRole(['6', '44', '4', '3'])) {
            return $response;
        }
        $data = [];
        $data['header'] = 'Dashboard';
        $data['type'] = 3;
        $id_user = session()->get('id_user');
        $data['clientCourseddata'] = $this->scorm_dashboard_model->getCoursesAssignedToMe($id_user);
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_dashboard/scorm_dashboardtable_view');
        echo view('templates/footer_view');
    }
    function settings()
    {
        if ($response =  $this->requireRole(['6', '44', '4', '3'])) {
            return $response;
        }
        $data = [];
        echo view('templates/header_view', $data);
        echo view('dashboard/dashboard_setting_view', $data);
        echo view('templates/footer_view');
    }
    function admin()
    {
        if ($response =  $this->requireRole(['6', '44', '4', '3'])) {
            return $response;
        }
        $data = [];
        echo view('templates/header_view', $data);
        echo view('dashboard/dashboard_admin_view', $data);
        echo view('templates/footer_view');
    }
    function help()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4', '3'])) {
            return $response;
        }
        $data = [];
        $data['header'] = 'Dashboard';
        $data['header_link'] = 'my_training';
        $data['sub_header_1'] = 'Help';
        $id_user = session()->get('id_user');
        echo view('templates/header_view', $data);
        echo view('profile/help', $data);
        echo view('templates/footer_view');
    }
    function report()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4', '3'])) {
            return $response;
        }
        $data = [];
        $data['header'] = 'Dashboard';
        $data['header_link'] = 'my_training';
        $data['sub_header_1'] = 'My Report';
        $id_user = session()->get('id_user');
        $data['getAllCoursesForUsers'] = $this->scorm_client_model->getAllCoursesForUser($id_user);
        // echo "<pre>";
        // print_r($data['getAllCoursesForUsers']);
        // exit();
        echo view('templates/header_view', $data);
        echo view('profile/report_view', $data);
        echo view('templates/footer_view');
    }
    public function library()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4', '3'])) {
            return $response;
        }
        $data = [];
        $data['type'] = 0;
        $id_user = session()->get('id_user');
        if (isset($_POST['crid'])) {
            $data['crid'] = $_POST['crid'];
            $_SESSION['crid'] = $data['crid'];
            $data['detail_type'] = $_POST['detail_type'];
            $_SESSION['detail_type'] = $data['detail_type'];
        } else if (isset($_GET['crid'])) {
            $data['crid'] = $_GET['crid'];
        } else if (isset($_SESSION['crid'])) {
            $data['crid'] = $_SESSION['crid'];
        } else {
            return redirect()->to(base_url() . '/my_training');
        }

        if ($data['detail_type'] = 1) {
            $data['header_link'] = "my_training";
            $data['header'] = "Dashboard";
        } elseif ($data['detail_type'] = 2) {
            $data['header_link'] = "my_training/dashboardtable";
            $data['header'] = "My Courses";
        } elseif ($data['detail_type'] = 3) {
            $data['header_link'] = "demo/Demo_dashboard";
            $data['header'] = "View All My Course";
        } else {
            $data['header_link'] = "my_training";
            $data['header'] = "Dashboard";
        }
        $client = session()->get('client');
        $data['clientCourseddata'] = $this->scorm_dashboard_model->getCoursesdetailslibrary($data['crid']);
        $data['getCoursesAssigned'] = $this->scorm_dashboard_model->getCoursesAssigned($id_user, $data['crid']);
        $data['editableCoursedata'] = $this->scorm_dashboard_model->editableCoursedata($client, $data['crid']);
        $data['pagedata'] = $this->scorm_dashboard_model->getpagedetails($data['crid']);
        // Check if a certificate is assigned for this course (type=3)
        $data['certificate_assign'] = $this->certification_model->get_certificate_assignment($data['crid'], $client, 3);
        $data['getratingCourseofuser'] = $this->courseRating_model->getratingCourseofuser($id_user, $data['crid']);
        $data['getratingCourse'] = $this->courseRating_model->avrratingofCourse($data['crid']);

        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_dashboard/library_view');
        echo view('templates/footer_view');
    }
    public function assignCoursegrouptoUsergroup($c_gid, $u_gid)
    {
        if ($response =  $this->requireRole(['6', '44', '4', '3', '5'])) {
            return $response;
        }
        $this->scorm_dashboard_model->assigncoursegrouptousergroup($c_gid, $u_gid);
    }
    public function assignUserstoUsergroup($c_gid)
    {
        if ($response =  $this->requireRole(['6', '44', '4', '3', '5'])) {
            return $response;
        }
        $this->scorm_dashboard_model->assignUserstoUsergroup($c_gid);
    }
    public function assignPreAttempttoQiz($c_gid)
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $this->scorm_dashboard_model->assignPreAttempttoQiz($c_gid);
    }
    public function read_more()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4', '3'])) {
            return $response;
        }
        $data = [];
        helper(['form', 'time']);
        $data['type'] = 0;
        $id_user = session()->get('id_user');

        $data['user_id'] = $id_user;
        $defaults = [
            'crid'          => null,
            'detail_type'   => null,
            'price'         => 0,
            'billing_cycle' => 0,
            'currency'      => 'USD',
            'payment_type'  => 0,
            'tab' => 1,
            'mp_id' => 0,
            'certificate_id' => null

        ];
        foreach ($defaults as $key => $default) {
            if (isset($_POST[$key])) {
                $data[$key] = $_POST[$key];
                $_SESSION[$key] = $data[$key];
            } elseif (isset($_SESSION[$key])) {
                $data[$key] = $_SESSION[$key];
            } else {
                $data[$key] = $default;
            }
        }

        if (empty($data['crid']) || $data['crid'] == null) {
            return redirect()->to(base_url() . 'my_training');
        }
        $_SESSION['course_detail_launch'] = 1;
        // $data['detail_type'] = 0;
        if ($data['detail_type'] == 1) {
            $data['header_link'] = "my_training";
            $data['header'] = lang('Buttons.Dashboard');
            $data['cdata_type'] = 0;
        } elseif ($data['detail_type'] == 2) {
            $data['header_link'] = "SCORM/Scorm_courses";
            $data['header'] = lang('Buttons.My Courses');
            $data['cdata_type'] = 0;
        } elseif ($data['detail_type'] == 3) {
            $data['header_link'] = "demo/Demo_dashboard";
            $data['header'] = lang('Buttons.Demos');
            $data['cdata_type'] = 0;
        } elseif ($data['detail_type'] == 4) {
            $data['header_link'] = "Game/gamification";
            $data['header'] = lang('Buttons.Gamification');
            $data['cdata_type'] = 0;
        } elseif ($data['detail_type'] == 5) {
            $data['header_link'] = "marketplace/dashboard";
            $data['header'] = lang('Buttons.Marketplace');
            $data['cdata_type'] = 1;
        } elseif ($data['detail_type'] == 6) {
            $data['header_link'] = "category/dashboard/view_courses";
            $data['header'] = lang('Buttons.Category Courses');
            $data['cdata_type'] = 0;
        } elseif ($data['detail_type'] == 7) {
            $data['header_link'] = "SCORM/Scorm_client/reviews";
            $data['header'] = lang('Buttons.Client Dashboard');
            $data['cdata_type'] = 0;
        } elseif ($data['detail_type'] == 8) {
            $data['header_link'] = "my_training/course_group_list";
            $data['header'] = lang('Buttons.Course Group');
            $data['cdata_type'] = 0;
        } elseif ($data['detail_type'] == 9) {
            $data['header_link'] = "marketplace/Learning_dashboard/learning_courses";
            $data['header'] = lang('Buttons.Learning Courses');
            $data['cdata_type'] = 2;
        } elseif ($data['detail_type'] == 10) {
            $data['header_link'] = "marketplace/Learning_dashboard";
            $data['header'] = lang('Buttons.Learning Plan Dashboard');
            $data['cdata_type'] = 1;
        } elseif ($data['detail_type'] == 11) {
            $data['header_link'] = "marketplace/Learning_dashboard/learning_courses";
            $data['header'] = lang('UI_Text.Back');
            $data['cdata_type'] = 4;
        } elseif ($data['detail_type'] == 12) {

            $data['header_link'] = "/marketplace/admin/edit_courses";
            $data['header'] = lang('Buttons.Link_Courses');
            $data['cdata_type'] = 1;
        } elseif ($data['detail_type'] == 13) {

            $data['header_link'] = "Certification/Certification_Portal/certificationDetails";
            $data['header'] = lang('UI_Text.Back');
            $data['cdata_type'] = 1;
        } else {
            $data['header_link'] = "my_training";
            $data['header'] = lang('Buttons.Dashboard');
            $data['cdata_type'] = 0;
        }

        $mp_id =  $data['mp_id'];
        $client = session()->get('client');
        $user = session()->get('id_user');
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);
        /*
        |--------------------------------------------------------------------------
        | Certification Launch Validation
        |--------------------------------------------------------------------------
        | Only for Certification (detail_type = 13)
        | Other modules continue to work as before.
        */
        $data['showLaunchButton'] = true;

        if ($data['detail_type'] == 13) {

            $data['showLaunchButton'] =
                $this->CertificationPaymentModel
                ->isCertificatePurchased(
                    session()->get('id_user'),
                    $data['certificate_id']
                );
        }
        /* Certification Launch Validation*/
        if ($data['detail_type'] == 5) {

            $data['getbillingdata'] = $this->billing_model->getbillingdata($data['crid'], $user);
            $data['getsubscribebillingdata'] = $this->billing_model->getsubscribebillingdata($user);
            // print_r($data['price']);
            // exit();
            if (!empty($data['getbillingdata']) || !empty($data['getbillingdata'] || $data['price'] <= 0) || $data['payment_type'] == 1) {
                $this->scorm_dashboard_model->assigncoursetouser($user, $data['crid'], $mp_id, $data['cdata_type']);
                $data['clientCourseddata'] = $this->scorm_dashboard_model->getCoursesdetails($data['crid']);
            } else {
                // $data['clientCourseddata'] = $this->scorm_dashboard_model->getCoursesdetailslibrary($data['crid']);
                $data['clientCourseddata'] = $this->scorm_dashboard_model->getCoursesdetails($data['crid']);
            }
        } else {
          
            $this->scorm_dashboard_model->assigncoursetouser($user, $data['crid'], $mp_id, $data['cdata_type']);
            $data['clientCourseddata'] = $this->scorm_dashboard_model->getCoursesdetails($data['crid']);
            //   print_r($data['crid']);
            //     exit();
        }

        // print_r($data['clientCourseddata'][0]['language']);
        // exit();
        $_SESSION['course_lang'] = !empty($data['clientCourseddata'][0]['language']) ? $data['clientCourseddata'][0]['language'] : 'English';
        $data['getCoursesAssigned'] = $this->scorm_dashboard_model->getCoursesAssigned($id_user, $data['crid']);
        $data['editableCoursedata'] = $this->scorm_dashboard_model->editableCoursedata($client, $data['crid']);
        $data['pagedata'] = $this->scorm_dashboard_model->getpagedetails($data['crid']);
        $data['getratingCourseofuser'] = $this->courseRating_model->getratingCourseofuser($id_user, $data['crid']);
        $data['getratingCourse'] = $this->courseRating_model->avrratingofCourse($data['crid']);
        $data['getAllObjectives'] = $this->scorm_course_model->getAllObjectives($data['crid']);
        $data['ifFavorite'] = $this->scorm_course_model->isFavorite($data['crid'], $user);
        $data['enrolledLearnersCount'] = $this->scorm_course_model->getEnrolledLearnersCount($data['crid']);

        $data['my_all_post'] = $this->Post_model->getPostCount($user, $data['crid']);
        $active_posts = $this->Post_model->get_active_posts($client, $user, $data['crid']);
        $repliesByPost = $this->Post_model->getpost_replies_for_posts(array_column($active_posts, 'social_id'));
        $replies = [];

        foreach ($active_posts as &$post) {
            $post['time_ago'] = time_elapsed_string('@' . $post['last_updated_on']);
            $post_replies = $repliesByPost[$post['social_id']] ?? [];

            foreach ($post_replies as &$reply) {
                $reply['time_ago'] = time_elapsed_string('@' . $reply['last_updated_on']);
            }
            unset($reply);

            $replies[$post['social_id']] = $post_replies;
        }
        unset($post);

        $data['active_posts'] = $active_posts;
        $data['replies'] = $replies;
        $this->Post_model->updatePostCount($user);
        // Check if a certificate is assigned for this course (type=3)
        $data['certificate_assign'] = $this->certification_model->get_certificate_assignment($data['crid'], $client, 3);

        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_dashboard/read_more_view');
        echo view('templates/footer_view');
    }

    public function unenroll()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4', '3'])) {
            return $response;
        }
        $id_user = session()->get('id_user');
        $crid = $_POST['crid'] ?? null;

        if (!empty($crid)) {
            // Re-derive the assignment row from the logged-in user's own id + the posted course
            // id (rather than trusting a posted user_assign_id) so this can only ever unenroll
            // the current user from their own course.
            $assigned = $this->scorm_dashboard_model->getCoursesAssigned($id_user, $crid);
            if (!empty($assigned[0]['user_assign_id'])) {
                $this->xapi_scenarios_model->delEnrollment(['status' => 2], $assigned[0]['user_assign_id']);
            }
        }

        return redirect()->to(base_url('SCORM/scorm_courses'));
    }

    public function add_favorite($course_id)
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4', '3'])) {
            return $response;
        }
        $id_user = session()->get('id_user');
        $newData = [
            'user_id' => $id_user,
            'scourse_id' => $course_id,
        ];
        $this->scorm_course_model->addFavorite($newData);
        return redirect()->to(base_url() . 'my_training/read_more');
    }

    public function remove_favorite($fav_id)
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4', '3'])) {
            return $response;
        }
        $id_user = session()->get('id_user');
        $data['ifFavorite'] = $this->scorm_course_model->getFavbyID($fav_id);
        if (empty($data['ifFavorite']) || $data['ifFavorite'][0]['user_id'] != $id_user) {
            session()->setFlashdata('error', 'Favorite not found or access denied.');
            return redirect()->to(base_url() . 'my_training/read_more');
        } else {
            $this->scorm_course_model->deleteFavorite($fav_id, $id_user);
            return redirect()->to(base_url() . 'my_training/read_more');
        }
    }

    public function course_thumbnail_list()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4', '3'])) {
            return $response;
        }
        $data = [];
        $data['type'] = 0;

        $data['coursedata'] = $this->scorm_course_model->fullCourseDetails();

        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_dashboard/course_thumbnail_list_view');
        echo view('templates/footer_view');
    }
    public function course_thumbnaildetails()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4', '3'])) {
            return $response;
        }
        $data = [];
        $data['type'] = 0;

        $data['coursedata'] = $this->scorm_course_model->fullCourseDetails();

        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_dashboard/course_thumbnaildetails_view');
        echo view('templates/footer_view');
    }
    public function downloadAllThumbnails()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4', '3'])) {
            return $response;
        }
        $coursedata =  $this->scorm_course_model->fullCourseDetails();

        // Create temp ZIP
        $zip = new \ZipArchive();
        $zipName = tempnam(sys_get_temp_dir(), 'thumbnails_') . '.zip';

        if ($zip->open($zipName, \ZipArchive::CREATE) !== TRUE) {
            return redirect()->back()->with('error', 'Cannot create ZIP file');
        }

        foreach ($coursedata as $data) {
            if (!empty($data['thumbnail'])) {
                $filePath = FCPATH . 'assets/assets/uploads/SCORM_course_thumbnail/'
                    . $data['scourse_id'] . '/' . $data['thumbnail'];

                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $data['course_name'] . '.jpg'); // optional: rename inside ZIP
                }
            }
        }

        $zip->close();

        // Send ZIP manually and delete temp file after
        return $this->response->setHeader('Content-Type', 'application/zip')
            ->setHeader('Content-Disposition', 'attachment; filename="course_thumbnails.zip"')
            ->setHeader('Content-Length', filesize($zipName))
            ->setBody(file_get_contents($zipName))
            ->send();

        // Delete temp file manually after sending
        unlink($zipName);
    }
    public function learn_group_courses()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4', '3'])) {
            return $response;
        }
        $data = [];
        $data['type'] = 0;
        $id_user = session()->get('id_user');
        if (isset($_POST['group_id']) && isset($_POST['userid'])) {
            $data['group_id'] = $_POST['group_id'];
            $_SESSION['group_id'] = $data['group_id'];
            $data['userid'] = $_POST['userid'];
            $_SESSION['userid'] = $data['userid'];
        } else if (isset($_GET['group_id']) && isset($_GET['userid'])) {
            $data['group_id'] = $_GET['group_id'];
            $data['userid'] = $_GET['userid'];
        } else if (isset($_SESSION['group_id']) && isset($_SESSION['userid'])) {
            $data['group_id'] = $_SESSION['group_id'];
            $data['userid'] = $_SESSION['userid'];
        } else {
            return redirect()->to(base_url() . 'my_training');
        }


        $data['clientCourseddata'] = $this->scorm_learn_group_model->getlearngroupCourses($data['userid'], $data['group_id']);
        // print_r($data['clientCourseddata'] );
        // exit();
        foreach ($data['clientCourseddata'] as $course) {
            if ($course['lesson_status'] != 'completed' && $course['lesson_status'] != 'passed' && $course['lesson_status'] != 'Completed') {
                $data['complete_percent'] = "0";
            } else {
                $data['complete_percent'] = "100";
                $this->scorm_learn_group_model->updateCompletionPerncent($data['group_id'], $data['userid'], $data['complete_percent']);
            }
        }

        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_dashboard/learn_group_courses_view');
        echo view('templates/footer_view');
    }
}
