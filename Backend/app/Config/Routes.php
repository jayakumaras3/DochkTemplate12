<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
if (!isset($routes) || !$routes instanceof RouteCollection) {
    $routes = service('routes');
}
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Landing');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

// ============================================
// API Routes - HIGH PRIORITY
// ============================================
$routes->group('api', function ($routes) {
    $routes->group('opentask', function ($routes) {
        $routes->get('findAll', 'Open_task::findAll');
    });

    // Password Reset API Routes
    $routes->post('forgot_password', 'PasswordReset::forgot_password');
    $routes->get('verify_token', 'PasswordReset::verify_token');
    $routes->post('reset_password', 'PasswordReset::reset_password');

    // Debug endpoint for token issues
    $routes->get('token-debug', 'TokenDebug::index');

    // Database diagnostic endpoint
    $routes->get('db-test', 'DatabaseTest::index');

    // Table structure endpoint
    $routes->get('table-structure/users', 'TableStructure::users');

    // CORS preflight requests
    $routes->options('forgot_password', 'PasswordReset::forgot_password');
    $routes->options('verify_token', 'PasswordReset::verify_token');
    $routes->options('reset_password', 'PasswordReset::reset_password');
});

// Compatibility routes for deployments where backend is mounted under /landing
$routes->group('landing/api', function ($routes) {
    $routes->post('forgot_password', 'PasswordReset::forgot_password');
    $routes->get('verify_token', 'PasswordReset::verify_token');
    $routes->post('reset_password', 'PasswordReset::reset_password');

    $routes->get('token-debug', 'TokenDebug::index');
    $routes->get('db-test', 'DatabaseTest::index');
    $routes->get('table-structure/users', 'TableStructure::users');

    $routes->options('forgot_password', 'PasswordReset::forgot_password');
    $routes->options('verify_token', 'PasswordReset::verify_token');
    $routes->options('reset_password', 'PasswordReset::reset_password');
});

$routes->group('Landing', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Landing::index');
});

// Mobile app API routes. Login goes through the existing Landing::login_register
// endpoint (already JSON, rate-limited, and excepted from the global 'auth' filter).
// These routes are session-cookie based (ci_session). Note: paths under 'api/*' are
// NOT reliably covered by the global 'auth' filter in this app (same as the existing
// api/quickaccess/* routes), so Mobile\Api guards each action with requireLogin().
$routes->group('api/mobile', function ($routes) {
    $routes->get('profile', 'Mobile\Api::profile');
    $routes->post('change-password', 'Mobile\Api::changePassword');
    $routes->post('logout', 'Mobile\Api::logout');
    $routes->get('wfh-summary', 'Mobile\Api::wfhSummary');
    $routes->post('wfh-apply', 'Mobile\Api::wfhApply');
    $routes->post('wfh-cancel', 'Mobile\Api::wfhCancel');
    $routes->options('profile', 'Mobile\Api::profile');
    $routes->options('change-password', 'Mobile\Api::changePassword');
    $routes->options('logout', 'Mobile\Api::logout');
    $routes->options('wfh-summary', 'Mobile\Api::wfhSummary');
    $routes->options('wfh-apply', 'Mobile\Api::wfhApply');
    $routes->options('wfh-cancel', 'Mobile\Api::wfhCancel');
});


$routes->post('Landing/login_register', 'Landing::login_register');
$routes->options('Landing/login_register', 'Landing::login_register');
$routes->get('Landing/authStatus', 'Landing::authStatus');
$routes->get('login', 'Landing::login_register');
$routes->get('signup', 'Landing::signup');
$routes->get('Landing_dochek', 'Landing_dochek::login_register');
$routes->get('activate-account', 'Landing::activateAccount');

// ============================================
// Legacy/Old Password Reset Routes (for backward compatibility)
// Redirect old endpoints to new API
// ============================================
$routes->post('landing/forgotpasswordVerify', 'PasswordReset::forgot_password');
$routes->post('forgot_password/verify', 'PasswordReset::forgot_password');
$routes->match(['get', 'post'], 'forgot_password', 'Open\Login_forgot_password::index');
$routes->match(['get', 'post'], 'forgot_password/reset_password/(:any)', 'Open\Login_forgot_password::reset_password/$1');
$routes->match(['get', 'post'], 'quickaccess', 'Open\Quickaccess::index');
$routes->match(['get', 'post'], 'quickaccess/demo/(:any)', 'Open\Quickaccess::demo/$1');
$routes->match(['get', 'post'], 'quickaccess/checkAccess', 'Open\Quickaccess::checkAccess');
$routes->post('api/quickaccess/login', 'Open\Quickaccess::apiQuickAccessLogin');
$routes->post('api/quickaccess/authenticate', 'Open\Quickaccess::apiQuickAccessAuthenticate');
$routes->match(['get', 'post'], 'usergraph', 'Open\usergraph::index');
$routes->match(['get', 'post'], 'usergraph/projectplanGraph', 'Open\usergraph::projectplanGraph');
$routes->match(['get', 'post'], 'privacy', 'Open\Privacy::index');
$routes->match(['get', 'post'], 'tour', 'Others\Tour_user::index');
$routes->match(['get', 'post'], 'privacy/privacy-policy', 'Open\Privacy::privacy');
$routes->match(['get', 'post'], 'privacy/term', 'Open\Privacy::term');
$routes->match(['get', 'post'], 'userprojectplan/getprojectplan(:any)', 'Open\Userprojectplan::getprojectplan/$1');
$routes->get('marketplace/admin/downloadCoursesJson/(:num)', 'Marketplace\Admin::downloadCoursesJson/$1');

$routes->post('Open/Login_forgot_password', 'Login_forgot_password::index');

$routes->get('logout', 'Landing::logout', ['filter' => 'auth']);
$routes->get('my_training', 'My_training::index', ['filter' => 'auth']);
$routes->get('profile', 'Profile::index', ['filter' => 'auth']);
$routes->match(['GET', 'POST'], 'changeUserdata', 'Profile::changeUserdata', ['filter' => 'auth']);
$routes->get('dashboard', 'Dashboard::index');
$routes->get('users', 'User_login/Users::index', ['filter' => 'auth']);
$routes->match(['GET', 'POST'], 'createnewpage', 'Pages::createnewpage', ['filter' => 'auth']);
$routes->match(['GET', 'POST'], 'register', 'Users::register', ['filter' => 'auth']);
$routes->match(['GET', 'POST'], 'editUsers', 'Users::editUsers', ['filter' => 'auth']);
$routes->get('deleteUsers', 'Users::deleteUsers', ['filter' => 'auth']);
$routes->get('dropdown', 'Dropdown::index', ['filter' => 'auth']);
$routes->match(['GET', 'POST'], 'categoryItem', 'Dropdown::categoryItem', ['filter' => 'auth']);
$routes->match(['GET', 'POST'], 'category', 'Dropdown::category', ['filter' => 'auth']);
$routes->match(['GET', 'POST'], 'updateCategory', 'Users::updateCategory', ['filter' => 'auth']);
$routes->match(['GET', 'POST'], 'updateCategoryItem', 'Users::updateCategoryItem', ['filter' => 'auth']);
$routes->match(['GET', 'POST'], 'deleted_userslist', 'Users::deleted_userslist', ['filter' => 'auth']);
//$routes->match(['GET', 'POST'], 'usergraph', 'Usergraph::index', ['filter' => 'noauth']);

//save_time_on_exit
$routes->post('save-time', 'SCORM\Course_builder\Review_course::save_time_on_exit');
$routes->post('update-time', 'SCORM\Course_builder\Review_course::update_time');

$routes->get('stripe/subscribe', 'Stripe\Checkout::monthlySubscription');
$routes->post('stripe/pay-course', 'Stripe\Checkout::payCourse');

$routes->get('stripe/success', 'Stripe\Checkout::success');
$routes->get('stripe/success_subscription', 'Stripe\Checkout::success_subscription');

$routes->get('stripe/cancel', 'Stripe\Checkout::cancel');
$routes->post('stripewebhook', 'Stripe\StripeWebhook::index');

$routes->get('/lang/(:any)', 'Language::setlanguage/$1');

$routes->get('certification-details', 'Certification\Certification_Portal::certificationDetails');
$routes->post('course-details', 'Certification\Certification_Portal::courseDetails');

$routes->post(
    'Certification/Certification_Portal/buyNowDetails',
    'Certification\Certification_Portal::buyNowDetails'
);
$routes->post(
    'Certification/Certification_Payment/createPaymentOrder',
    'Certification\Certification_Payment::createPaymentOrder'
);

$routes->post(
    'Certification/Certification_Payment/verifyPayment',
    'Certification\Certification_Payment::verifyPayment'
);
$routes->post(
    'Certification/Certification_Payment/paymentFailed',
    'Certification\Certification_Payment::paymentFailed'
);
$routes->post(
    'Certification/Certification_Payment/paymentCancelled',
    'Certification\Certification_Payment::paymentCancelled'
);

// ============================================
// Project_Manage/Effort_Tracker - explicit verb-restricted routes.
// Auto-routing allowed any HTTP method to reach these actions (including
// state-changing ones via GET); pin each to the verb it actually needs and
// require the csrf filter on everything that writes data.
// ============================================
$routes->match(['get', 'post'], 'Project_Manage/Effort_Tracker', 'Project_Manage\Effort_Tracker::index');
$routes->post('Project_Manage/Effort_Tracker/AddEffort', 'Project_Manage\Effort_Tracker::AddEffort', ['filter' => 'csrf']);
$routes->post('Project_Manage/Effort_Tracker/AddEffort_for_member', 'Project_Manage\Effort_Tracker::AddEffort_for_member', ['filter' => 'csrf']);
$routes->post('Project_Manage/Effort_Tracker/mng_response', 'Project_Manage\Effort_Tracker::mng_response', ['filter' => 'csrf']);
$routes->post('Project_Manage/Effort_Tracker/mng_bulk_approve', 'Project_Manage\Effort_Tracker::mng_bulk_approve', ['filter' => 'csrf']);
$routes->post('Project_Manage/Effort_Tracker/pm_response', 'Project_Manage\Effort_Tracker::pm_response', ['filter' => 'csrf']);
$routes->post('Project_Manage/Effort_Tracker/pm_bulk_approve', 'Project_Manage\Effort_Tracker::pm_bulk_approve', ['filter' => 'csrf']);
$routes->post('Project_Manage/Effort_Tracker/Edit_effort', 'Project_Manage\Effort_Tracker::Edit_effort', ['filter' => 'csrf']);
$routes->post('Project_Manage/Effort_Tracker/Delete_effort', 'Project_Manage\Effort_Tracker::Delete_effort', ['filter' => 'csrf']);
$routes->post('Project_Manage/Effort_Tracker/Update_Effort', 'Project_Manage\Effort_Tracker::Update_Effort', ['filter' => 'csrf']);
$routes->post('Project_Manage/Effort_Tracker/Update_Effort_Ajax', 'Project_Manage\Effort_Tracker::Update_Effort_Ajax', ['filter' => 'csrf']);
$routes->match(['get', 'post'], 'Project_Manage/Effort_Tracker/PM_Project_Effort', 'Project_Manage\Effort_Tracker::PM_Project_Effort');
$routes->post('Project_Manage/Effort_Tracker/View_User_Effort', 'Project_Manage\Effort_Tracker::View_User_Effort', ['filter' => 'csrf']);
$routes->post('Project_Manage/Effort_Tracker/Reject_effort_by_pm', 'Project_Manage\Effort_Tracker::Reject_effort_by_pm', ['filter' => 'csrf']);
$routes->post('Project_Manage/Effort_Tracker/Add_user_to_project', 'Project_Manage\Effort_Tracker::Add_user_to_project', ['filter' => 'csrf']);
$routes->post('Project_Manage/Effort_Tracker/Remove_user_from_project', 'Project_Manage\Effort_Tracker::Remove_user_from_project', ['filter' => 'csrf']);
$routes->match(['get', 'post'], 'Project_Manage/Effort_Tracker/Team_data', 'Project_Manage\Effort_Tracker::Team_data');
$routes->match(['get', 'post'], 'Project_Manage/Effort_Tracker/All_data', 'Project_Manage\Effort_Tracker::All_data');
$routes->match(['get', 'post'], 'Project_Manage/Effort_Tracker/view_member_effort', 'Project_Manage\Effort_Tracker::view_member_effort');

// Sales Dashboard - inline AJAX row update (status/remarks/value/expected_date; state-changing, requires csrf).
$routes->post('etrack/sales_admin/update_sales_row', 'Etrack\Sales_admin::update_sales_row', ['filter' => 'csrf']);

// CEO Executive Dashboard
$routes->get('etrack/executive_dashboard', 'Etrack\Executive_dashboard::view');
$routes->get('etrack/executive_dashboard/data', 'Etrack\Executive_dashboard::data');

// "Library" is a friendly alias for the learning plan dashboard: the browser keeps showing
// /Library while CI4 serves marketplace/learning_dashboard's content underneath, unlike a
// redirect (which would change the address bar to marketplace/learning_dashboard).
$routes->match(['get', 'post'], 'Library', 'Marketplace\Learning_dashboard::index');
