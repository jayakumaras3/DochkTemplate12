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

$routes->group('Landing', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Landing::index');
});


$routes->get('login', 'Landing::login_register');
$routes->get('Landing_dochek', 'Landing_dochek::login_register');

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
$routes->get('pages', 'Pages::index', ['filter' => 'auth']);
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

$routes->get('stripe/subscribe', 'Stripe\Checkout::monthlySubscription');
$routes->post('stripe/pay-course', 'Stripe\Checkout::payCourse');

$routes->get('stripe/success', 'Stripe\Checkout::success');
$routes->get('stripe/success_subscription', 'Stripe\Checkout::success_subscription');

$routes->get('stripe/cancel', 'Stripe\Checkout::cancel');
$routes->post('stripewebhook', 'Stripe\StripeWebhook::index');

$routes->get('/lang/(:any)', 'Language::setlanguage/$1');
