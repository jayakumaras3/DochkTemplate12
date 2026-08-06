<?php

namespace App\Controllers;

use App\Models\User_login\Login_model;
use App\Models\Dashboard\Dashboard_model;
use App\Models\Settings\Settings_model;
use App\Models\Social\Post_model;

#[\AllowDynamicProperties]

class Landing extends BaseController
{
    private $db;

    public function __construct()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $this->login_model = new Login_model();
        $this->dashboard_model = new Dashboard_model();
        $this->settings_model = new Settings_model();
        $this->post_model = new Post_model();
    }
    public function index()
    {
        echo "<script>window.location.href = '" . base_url('ang/') . "';</script>";

        exit();
        $data = [];
        helper(['form']);
        $data['landing_type'] = '4';
        $data['username'] = 'notvalid';
        // echo view('landing/header', $data);
        echo view('landing/index');
        // echo view('landing/footer');
    }
    public function signup()
    {
        $data = $this->request->getJSON(true);

        if (empty($data)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status' => false,
                    'message' => 'Invalid request data'
                ]);
        }

        // Sanitize input
        $data['firstName'] = trim($data['firstName'] ?? '');
        $data['lastName'] = trim($data['lastName'] ?? '');
        $data['email'] = strtolower(trim($data['email'] ?? ''));
        $data['confirmEmail'] = strtolower(trim($data['confirmEmail'] ?? ''));
        $data['password'] = $data['password'] ?? '';
        $data['confirmPassword'] = $data['confirmPassword'] ?? '';

        // Validation rules
        $rules = [
            'firstName' => 'required|min_length[2]|max_length[100]',
            'lastName' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email|max_length[255]',
            'confirmEmail' => 'required|valid_email',
            'password' => 'required|min_length[8]|max_length[100]',
            'confirmPassword' => 'required'
        ];

        if (!$this->validateData($data, $rules)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON([
                    'status' => false,
                    'errors' => $this->validator->getErrors()
                ]);
        }

        // Email confirmation
        if ($data['email'] !== $data['confirmEmail']) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON([
                    'status' => false,
                    'message' => 'Email addresses do not match'
                ]);
        }

        // Password confirmation
        if ($data['password'] !== $data['confirmPassword']) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON([
                    'status' => false,
                    'message' => 'Passwords do not match'
                ]);
        }

        // Strong password validation
        if (
            !preg_match(
                '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/',
                $data['password']
            )
        ) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON([
                    'status' => false,
                    'message' => 'Password must contain at least 8 characters, one uppercase letter, one lowercase letter, one number, and one special character.'
                ]);
        }



        // Check existing email
        $existingUser = $this->login_model->check_existing_email($data['email']);
        if ($existingUser) {
            return $this->response
                ->setStatusCode(409)
                ->setJSON([
                    'status' => false,
                    'message' => 'Email is already registered'
                ]);
        }

        // Insert user
        $insertData = [
            'name' => $data['firstName'],
            'last_name' => $data['lastName'],
            'email' => $data['email'],
            'username' => $data['email'],
            'valid' => 0,
            'client_id' => 70,
            'userid' => bin2hex(random_bytes(8)),
            'password' => password_hash(
                $data['password'],
                PASSWORD_DEFAULT
            ),
            'session_version' => 1,
            'createdon' => date('Y-m-d H:i:s')
        ];

        $userId = $this->login_model->insertUserdetails($insertData);

        if (!$userId) {
            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'status' => false,
                    'message' => 'Registration failed'
                ]);
        }
        $email = \Config\Services::email();

        $activationLink = base_url("activate-account?token=" . $insertData['userid']);
        $email->setFrom('do-not-reply@touchstonelc.com', 'Dochek');
        $email->setTo($data['email']);
        $email->setSubject('Activate Your Account');

        $email->setMessage("
    <h2>Welcome!</h2>
    <p>Thank you for registering.</p>
    <p>Please click the button below to activate your account.</p>

    <p>
        <a href='{$activationLink}'
           style='padding:10px 20px;
                  background:#007bff;
                  color:#fff;
                  text-decoration:none;
                  border-radius:4px;'>
            Activate Account
        </a>
    </p>

    <p>If you did not create this account, you can ignore this email.</p>
");

        $email->send();

        return $this->response
            ->setStatusCode(201)
            ->setJSON([
                'status' => true,
                'message' => "Thank you for registering. We've sent an account activation email to your registered email address. Please verify your email to complete the registration process.",
                'user_id' => $userId
            ]);
    }
    public function activateAccount()
    {
        $token = $this->request->getVar('token');
        // print_r($token);
        // exit();

        if (empty($token)) {
            return redirect()->to(base_url() . 'ang/login?success=Invalid activation link.');
        }

        $user = $this->login_model->getUserByToken($token);

        if (!$user) {
            return redirect()->to(base_url() . 'ang/login');
        }
        $this->login_model->activateuser($user['id_user'], [
            'valid' => 1,
            'userid' => null
        ]);

        return redirect()->to(base_url() . 'ang/login?success=Your account has been activated successfully. Please log in.');
    }
    public function login_register()
    {
        helper(['form']);

        // Force JSON requests only
        $contentType = strtolower($this->request->getHeaderLine('Content-Type'));

        if (strpos($contentType, 'application/json') === false) {
            return $this->response
                ->setStatusCode(415)
                ->setJSON([
                    'success' => false,
                    'message' => 'Invalid request format.'
                ]);
        }

        $this->response->setHeader('Content-Type', 'application/json');

        // Parse JSON input
        $json = $this->request->getJSON(true);

        if (!$json) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'errors' => 'Invalid request format.'
            ]);
        }

        $username = trim($json['username'] ?? '');
        $password = trim($json['password'] ?? '');

        /*
    |--------------------------------------------------------------------------
    | Basic Validation Only
    |--------------------------------------------------------------------------
    */
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]', //regex_match[/^[A-Za-z0-9@._-]+$/]
            'password' => 'required|min_length[6]|max_length[255]'
        ];

        if (!$this->validateData($json, $rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    */
        $ip = $this->request->getIPAddress();

        $cache = \Config\Services::cache();

        // Per-IP + username protection
        $attemptKey = 'login_attempts_' . md5($ip . '_' . strtolower($username));

        $attempts = (int) ($cache->get($attemptKey) ?? 0);

        // Hard block
        if ($attempts >= 5) {

            return $this->response
                ->setStatusCode(429)
                ->setJSON([
                    'success' => false,
                    'message' => 'Too many login attempts. Please try again after 5 minutes.'
                ]);
        }

        /*
    |--------------------------------------------------------------------------
    | Fetch User
    |--------------------------------------------------------------------------
    */
        $userResult = $this->login_model->login_view($username);

        $user = $userResult[0] ?? null;

        /*
    |--------------------------------------------------------------------------
    | Unified Authentication Failure Handler
    |--------------------------------------------------------------------------
    */
        $authFail = function () use ($cache, $attemptKey, $attempts) {

            // Increment attempts
            $cache->save($attemptKey, $attempts + 1, 300);

            // Slow down brute force attacks
            sleep(min($attempts + 1, 3));

            return $this->response
                ->setStatusCode(401)
                ->setJSON([
                    'success' => false,
                    'message' => 'Invalid username or password.'
                ]);
        };

        /*
    |--------------------------------------------------------------------------
    | User Exists?
    |--------------------------------------------------------------------------
    */
        if (!$user) {
            return $authFail();
        }

        /*
    |--------------------------------------------------------------------------
    | Verify Password
    |--------------------------------------------------------------------------
    */
        // Replace password_hash_column with actual DB password field
        if (!password_verify($password, $user['password'])) {
            return $authFail();
        }

        /*
    |--------------------------------------------------------------------------
    | Account Status Checks
    |--------------------------------------------------------------------------
    */
        $currdate = date('Y-m-d');

        if (
            !empty($user['validity']) &&
            $user['validity'] !== '0000-00-00' &&
            $user['validity'] < $currdate
        ) {

            // Optional: still count as failed auth
            $cache->save($attemptKey, $attempts + 1, 300);

            return $this->response
                ->setStatusCode(403)
                ->setJSON([
                    'success' => false,
                    'message' => 'Invalid username or password.'
                ]);
        }

        /*
    |--------------------------------------------------------------------------
    | Successful Login
    |--------------------------------------------------------------------------
    */

        // Clear attempts
        $cache->delete($attemptKey);

        // Load related data
        $taskcount          = $this->dashboard_model->taskcount($username);
        $menu               = $this->settings_model->menu_view($user['client']);
        $marketplaceaccess  = $this->settings_model->marketplaceaccess($user['client']);
        $userpostCount      = $this->post_model->getUserPostCount($user['id_user']);

        /*
    |--------------------------------------------------------------------------
    | Build Session Data
    |--------------------------------------------------------------------------
    */
        $userData = [

            'id_user'                  => $user['id_user'],
            'username'                 => esc($user['username']),
            'lang'                     => $user['lang'],
            'client'                   => $user['client'],
            'report_to_you'            => $user['report_to_you'],
            'partner_code'             => $user['partner_code'],
            'logo'                     => $user['logo'],
            'logo_dark'                => $user['logo_dark'],
            'userlevel'                => $user['userlevel'],
            'region'                   => $user['region'],
            'name'                     => esc($user['name']),
            'last_name'                => esc($user['last_name']),
            'timezone'                 => $user['timezone'],
            'timezone_pname'           => $user['timezone_pname'],
            'demoaccess'               => $user['val2'],
            'totaltaskcount'           => $taskcount,
            'default_dashboard'        => $user['default_dashboard'],
            'users_default_dashboard'  => $user['users_default_dashboard'],
            'profile_image'            => $user['profile_image'],
            'profile_foldername'       => $user['profile_foldername'],
            'landing_page'             => $user['landing_page'],
            'accessmenu'               => $menu[0]['accessmenu'] ?? null,
            'banner_color'             => $user['banner_color'],
            'theme_color'              => $user['theme_color'],
            'userpostCount'            => $userpostCount[0]['status'] ?? 0,
            'marketplaceaccess'        => !empty($marketplaceaccess) ? 1 : 0,
            'session_version' => $user['session_version']
        ];

        /*
    |--------------------------------------------------------------------------
    | Session Security
    |--------------------------------------------------------------------------
    */
        session()->regenerate(true);

        if (!empty($userData['lang'])) {
            session()->set('locale', $userData['lang']);
        }

        $this->setUserSession($userData);

        session()->set([
            'isLoggedIn' => true,
            'userData'   => $userData,
            'id_user' => $userData['id_user'],
            'session_version' => (int)$userData['session_version']
        ]);

        /*
    |--------------------------------------------------------------------------
    | User Role Validation
    |--------------------------------------------------------------------------
    */
        $loginAllowedRoles = [3, 6, 44];

        $userLevels = array_map(
            'intval',
            explode(',', $userData['userlevel'])
        );

        if (empty(array_intersect($userLevels, $loginAllowedRoles))) {

            $updated = $this->login_model->updateUserlevel(
                $userData['id_user'],
                '3'
            );

            if ($updated) {

                $userData['userlevel'] = '3';

                session()->set('userData', $userData);
            } else {

                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Unable to update user role.'
                ]);
            }
        }

        /*
    |--------------------------------------------------------------------------
    | Landing Page Redirect
    |--------------------------------------------------------------------------
    */
        $redirectMap = [
            1 => 'etrack/dashboard',
            2 => 'etrack/attendance/view',
            3 => 'Task/Task_manage/my_task',
            4 => 'Task/Task_manage/team_tasks_allocate',
            5 => 'Project_Manage/PM_ucn',
            6 => 'Project_Manage/PM_projects',
            7 => 'marketplace/dashboard',
            8 => 'SCORM/Scorm_client/reviews'
        ];
        if ($user['client'] == 70) {
            $redirect = base_url('Certification/Certification_Portal');
        } else {
            $redirect = base_url(
                $redirectMap[$userData['landing_page']] ?? 'my_training'
            );
        }

        session()->set(
            'home_page',
            $userData['landing_page'] ?? 0
        );

        /*
    |--------------------------------------------------------------------------
    | Success Response
    |--------------------------------------------------------------------------
    */
        return $this->response->setJSON([
            'success'       => true,
            'redirect_url'  => $redirect,
            'user'          => [
                'id_user'  => $userData['id_user'],
                'username' => $userData['username'],
                'name'     => $userData['name']
            ]
        ]);
    }
    private function setUserSession($user)
    {
        $data = [
            'id_user' => $user['id_user'],
            'username' => $user['username'],
            'name' => $user['name'],
            'last_name' => $user['last_name'],
            'time_logged_in' => time(),
            'isLoggedIn' => true,
            'lang' => $user['lang'],
            'client' => $user['client'],
            'report_to_you' => $user['report_to_you'],
            'partner_code' => $user['partner_code'],
            'logo' => $user['logo'],
            'logo_dark' => $user['logo_dark'],
            'userlevel' => $user['userlevel'],
            'accessmenu' => $user['accessmenu'],
            'timezone' => $user['timezone'],
            'timezone_pname' => $user['timezone_pname'],
            'demoaccess' => $user['demoaccess'],
            'totaltaskcount' => $user['totaltaskcount'],
            'default_dashboard' => $user['default_dashboard'],
            'users_default_dashboard' => $user['users_default_dashboard'],
            'profile_image' => $user['profile_image'],
            'banner_color' => $user['banner_color'],
            'theme_color' => $user['theme_color'],
            'profile_foldername' => $user['profile_foldername'],
            'login_source' => 'angular',
            'userpostCount' => $user['userpostCount'],
            'marketplaceaccess' => $user['marketplaceaccess']
        ];

        $newdata = [
            'username' => $user['username'],
            'exittime' => ' ',
            'ipaddress' => $this->getClientIpAddress() ?? 'UNKNOWN',
            'dateandtime' => time(),
            'extra' => ' '
        ];

        $this->login_model->updateUserActive($newdata);

        session()->set($data);

        return $data;
    }
    function getClientIpAddress()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //Checking IP From Shared Internet
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //To Check IP is Pass From Proxy
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
    public function quickaccess()
    {
        $data = [];
        helper(['form']);
        $data['landing_type'] = '2';
        echo view('landing/header', $data);
        echo view('landing/index');
        echo view('landing/footer');
    }
    public function contact_us()
    {
        if ($this->request->getPost()) {
            $captcha = $this->request->getPost('captcha');
            $sessionCaptcha = session()->get('captcha');

            if ($captcha !== $sessionCaptcha) {
                // CAPTCHA validation failed
                session()->setFlashdata('error', 'CAPTCHA validation failed. Please try again');
                return redirect()->to(base_url('login'));
            }
            $to = 'pchandran@talentquest.com';
            $subject = $this->request->getPost('subject');
            $name = $this->request->getPost('name');
            $email = $this->request->getPost('email');
            $comments_msg = $this->request->getPost('comments');
            $comments = 'Hi,<br><br>'
                . 'Name : ' . $name . '<br>'
                . 'Sender Email : ' . $email . '<br>'
                . 'Comment : ' . $comments_msg . '<br><br>'
                . 'Regards,<br>'
                . 'Dochek Admin';
            $email = \Config\Services::email();
            $email->setFrom('do-not-reply@touchstonelc.com', 'Dochek');
            $email->setTo($to);
            // $email->setCC('another@another-example.com');
            //$email->setBCC('them@their-example.com');

            $email->setSubject($subject);
            $email->setMessage($comments);

            if ($email->send()) {
                session()->setFlashdata('success', 'Mail Sent successfully! We will contact soon');
                return redirect()->to(base_url('login'));
            } else {
                $data = $email->printDebugger(['headers']);
                print_r($data);
                exit();
            }
        } else {
            return redirect()->to(base_url('login'));
        }
    }
    // public function angualr_contact_us()
    // {
    //     $this->response->setHeader('Content-Type', 'application/json');

    //     $json = $this->request->getJSON(true);
    //     if (!$json) {
    //         return $this->response->setJSON([
    //             'success' => false,
    //             'message' => 'Invalid request format'
    //         ]);
    //     } else {

    //         // $to = 'pchandran@talentquest.com';
    //         $to = 'srividya.a@touchstonelc.com';
    //         $subject = 'Get in Touch';
    //         $name = $json['name'] ?? null;
    //         $company = $json['company'] ?? null;
    //         $email = $json['email'] ?? null;
    //         $city = $json['city'] ?? null;
    //         $message = $json['message'] ?? null;
    //         $comments = 'Hi,<br><br>'
    //             . 'Name : ' . $name . '<br>'
    //             . 'company : ' . $company . '<br>'
    //             . 'Sender Email : ' . $email . '<br>'
    //             . 'City : ' . $city . '<br>'
    //             . 'Message : ' . $message . '<br><br/>'
    //             . 'Regards,<br>'
    //             . 'Dochek Admin';
    //         $email = \Config\Services::email();
    //         $email->setFrom('do-not-reply@touchstonelc.com', 'Dochek');
    //         $email->setTo($to);
    //         // $email->setCC('another@another-example.com');
    //         //$email->setBCC('them@their-example.com');

    //         $email->setSubject($subject);
    //         $email->setMessage($comments);

    //         if ($email->send()) {
    //             session()->setFlashdata('success', 'Mail Sent successfully! We will contact soon');
    //             echo "<script>window.location.href = '" . base_url('app/Views/angular_view/') . "';</script>";

    //             exit();
    //         } else {
    //             $data = $email->printDebugger(['headers']);
    //             print_r($data);
    //             exit();
    //         }
    //     }
    // }
    public function angualr_contact_us()
    {

        $this->response->setHeader('Content-Type', 'application/json');

        $json = $this->request->getJSON(true);

        if (!$json) {

            return $this->response->setJSON([

                'success' => false,

                'message' => 'Invalid request format.'

            ]);
        }

        $name = $json['name'] ?? '';

        $company = $json['company'] ?? '';

        $emailFrom = $json['email'] ?? '';

        $city = $json['city'] ?? '';

        $messageText = $json['message'] ?? '';

        // $to = 'srividya.a@touchstonelc.com,jayakumar.k@touchstonelc.com';
        $to = 'pramod.c@TouchstoneLC.com';
        $subject = 'Get in Touch';

        $message = "Hi,<br><br>
                <strong>Name:</strong> {$name}<br>
                <strong>Company:</strong> {$company}<br>
                <strong>Email:</strong> {$emailFrom}<br>
                <strong>City:</strong> {$city}<br>
                <strong>Message:</strong> {$messageText}<br><br>

                        Regards,<br>

                        Dochek Admin

                    ";

        $email = \Config\Services::email();

        $email->setFrom('do-not-reply@touchstonelc.com', 'Dochek');

        $email->setTo($to);

        $email->setSubject($subject);

        $email->setMessage($message);

        if ($email->send()) {

            return $this->response->setJSON([

                'success' => true,

                'message' => 'Mail sent successfully! We will contact you soon.'

            ]);
        } else {

            $debugInfo = $email->printDebugger(['headers']);

            return $this->response->setJSON([

                'success' => false,

                'message' => 'Unable to send email using SMTP. Please check server configuration.',

                'debug' => $debugInfo

            ]);
        }
    }




    // public function angualr_product_enquiry()
    // {
    //     $this->response->setHeader('Content-Type', 'application/json');

    //     $json = $this->request->getJSON(true);

    //     if (!$json) {
    //         return $this->response->setJSON([
    //             'success' => false,
    //             'message' => 'Invalid request format.'
    //         ]);
    //     }

    //     // $to = 'pchandran@talentquest.com';
    //     $to = 'srividya.a@touchstonelc.com,jayakumar.k@TouchstoneLC.com';
    //     $subject = 'Product enquiry';
    //     $firstname = $json['firstName'] ?? null;
    //     $lastname = $json['lastName'] ?? null;
    //     $email = $json['email'] ?? null;
    //     $enquiry = $json['enquiry'] ?? null;
    //     $message = $json['message'] ?? null;
    //     $comments = 'Hi,<br><br>'
    //         . 'Frist Name : ' . $firstname . '<br>'
    //         . 'Last Name : ' . $lastname . '<br>'
    //         . 'Sender Email : ' . $email . '<br>'
    //         . 'Enquire related to : ' . $enquiry . '<br>'
    //         . 'Message : ' . $message . '<br><br/>'
    //         . 'Regards,<br>'
    //         . 'Dochek Admin';
    //     $email = \Config\Services::email();
    //     $email->setFrom('do-not-reply@touchstonelc.com', 'Dochek');
    //     $email->setTo($to);
    //     // $email->setCC('another@another-example.com');
    //     //$email->setBCC('them@their-example.com');

    //     $email->setSubject($subject);
    //     $email->setMessage($comments);

    //     if ($email->send()) {
    //         return $this->response->setJSON([
    //             'success' => true,
    //             'message' => 'Mail sent successfully! We will contact you soon.'
    //         ]);
    //     } else {
    //         $data = $email->printDebugger(['headers']);
    //         print_r($data);
    //         exit();
    //     }
    // }
    // public function logout()
    // {
    //     // print_r(session()->get('username')."ttt");
    //     $newdata['username'] = session()->get('username');
    //     $newdata['exittime'] = '';
    //     $ipaddress = $this->getClientIpAddress();
    //     $newdata['ipaddress'] = $ipaddress;
    //     $newdata['dateandtime'] = time();
    //     $newdata['extra'] = '';
    //     $result = $this->login_model->updateUserActive($newdata);
    //     session()->destroy();
    //     unset($_COOKIE['clientid']); // client filter unset
    //     // setcookie('clientid', null, -1, '/'); //client filter unset
    //     setcookie('clientid', '', -1, '/');
    //     return redirect()->to(base_url());
    // }
    public function angualr_product_enquiry()
    {

        $this->response->setHeader('Content-Type', 'application/json');

        $json = $this->request->getJSON(true);

        if (!$json) {

            return $this->response->setJSON([

                'success' => false,

                'message' => 'Invalid request format.'

            ]);
        }

        $name = $json['firstName'] ?? '';

        $lastName = $json['lastName'] ?? '';

        $email = $json['email'] ?? '';

        $enquiryType = $json['enquiry'] ?? '';

        $messageText = $json['comment'] ?? '';

        // $to = 'srividya.a@touchstonelc.com';
        $to = 'pramod.c@TouchstoneLC.com';
        $subject = 'Product Enquiry';

        $message = "

        Hi,<br><br>
<strong>Name:</strong> {$name} {$lastName}<br>
<strong>Email:</strong> {$email}<br>
<strong>Enquiry Type:</strong> {$enquiryType}<br>
<strong>Message:</strong> {$messageText}<br><br>

        Regards,<br>

        Dochek Admin

    ";

        $email = \Config\Services::email();

        $email->setFrom('do-not-reply@touchstonelc.com', 'Dochek');

        $email->setTo($to);

        $email->setSubject($subject);

        $email->setMessage($message);

        try {

            if ($email->send()) {

                return $this->response->setJSON([

                    'success' => true,

                    'message' => 'Product enquiry sent successfully!'

                ]);
            } else {

                $debug = $email->printDebugger(['headers']);

                return $this->response->setJSON([

                    'success' => false,

                    'message' => 'Failed to send email.',

                    'debug' => $debug

                ]);
            }
        } catch (\Exception $e) {

            return $this->response->setJSON([

                'success' => false,

                'message' => 'SMTP error: ' . $e->getMessage()

            ]);
        }
    }


    public function logout()
    {
        $source = session()->get('login_source') ?? 'ci';
        $newdata['username'] = session()->get('username');
        $newdata['exittime'] = '';
        $ipaddress = $this->getClientIpAddress();
        $newdata['ipaddress'] = $ipaddress;
        $newdata['dateandtime'] = time();
        $newdata['extra'] = '';


        $this->login_model->updateUserActive($newdata);

        session()->destroy();
        unset($_COOKIE['clientid']);
        setcookie('clientid', '', -1, '/');

        // // Output JavaScript to redirect to Angular app
        // echo "<script>window.location.href = '" . base_url('ang/') . "';</script>";

        // exit(); // Ensure script stops here
        if ($source === 'angular') {
            return redirect()->to(base_url('ang'));
        } else {
            return redirect()->to(base_url('Landing_dochek'));
        }
    }
    public function forgotpasswordVerify()
    {
        helper(['form']);

        // Get JSON input from Angular
        $json = $this->request->getJSON(true); // true = return as array
        $email = isset($json['email']) ? filter_var($json['email'], FILTER_SANITIZE_EMAIL) : null;

        if (empty($email)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Email is required'
            ]);
        }

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid email address'
            ]);
        }

        // Check email in database
        $userdata = $this->login_model->verifyEmail($email);

        if (empty($userdata)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => "Email doesn't exist"
            ]);
        }

        // Update token time, send email etc.
        if ($this->login_model->updateAt($userdata['userid'])) {
            $to = $email;
            $subject = 'Reset Password Link';
            $token = $userdata['userid'];
            $resetLink = base_url('forgot_password/reset_password/' . $token);

            $message = 'Hi ' . esc($userdata['name']) . ',<br><br>'
                . 'Your reset password request has been received.<br><br>'
                . '<a href="' . $resetLink . '" target="_blank">Click here to Reset Password</a><br><br>'
               // . '<small>Note: Reset link expires in 15 minutes.</small><br><br>'
                . 'Thanks,<br>Dochek Team';

            $emailService = \Config\Services::email();
            $emailService->setFrom('do-not-reply@touchstonelc.com', 'Dochek');
            $emailService->setTo($to);
            $emailService->setSubject($subject);
            $emailService->setMessage($message);

            if ($emailService->send()) {
                return $this->response->setJSON([
                    'status' => true,
                    'message' => 'If the email address is registered, a password reset link has been sent. Please verify within 15 minutes.'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Unable to send email. Please try again.'
                ]);
            }
        }

        return $this->response->setJSON([
            'status' => false,
            'message' => 'Unable to process request.'
        ]);
    }
}
