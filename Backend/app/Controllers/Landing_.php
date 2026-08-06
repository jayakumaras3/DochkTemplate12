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
        echo view('landing/header', $data);
        echo view('landing/index');
        echo view('landing/footer');
    }
    public function login_register()
    {
        helper(['form']);
        $data = [];

        $data['landing_type'] = '4';
        $this->response->setHeader('Content-Type', 'application/json');

        $json = $this->request->getJSON(true);
        if (!$json) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request format'
            ]);
        }

        $username = $json['username'] ?? null;
        $password = $json['password'] ?? null;

        $rules = [
            'username' => 'required|min_length[3]',
            'password' => 'required|max_length[255]|validateUser[username,password]',
        ];
        $errors = [
            'password' => [
                'validateUser' => 'Username or Password don\'t match.'
            ]
        ];

        if (!$this->validate($rules, $errors)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }

        $user = $this->login_model->login_view($username);
        $currdate = date('Y-m-d');

        if (!$user || empty($user[0])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not found.'
            ]);
        }

        if ($user[0]['validity'] < $currdate && $user[0]['validity'] != '0000-00-00' && $user[0]['validity'] != '') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Account validity expired.'
            ]);
        }

        // Session setup
        $taskcount = $this->dashboard_model->taskcount($username);
        $menu = $this->settings_model->menu_view($user[0]['client']);

        $userData = [
            'id_user' => $user[0]['id_user'],
            'username' => $user[0]['username'],
            'lang' => $this->request->getLocale(),
            'client' => $user[0]['client'],
            'report_to_you' => $user[0]['report_to_you'],
            'partner_code' => $user[0]['partner_code'],
            'logo' => $user[0]['logo'],
            'logo_dark' => $user[0]['logo_dark'],
            'userlevel' => $user[0]['userlevel'],
            'name' => $user[0]['name'],
            'timezone' => $user[0]['timezone'],
            'timezone_pname' => $user[0]['timezone_pname'],
            'demoaccess' => $user[0]['val2'],
            'totaltaskcount' => $taskcount,
            'default_dashboard' => $user[0]['default_dashboard'],
            'users_default_dashboard' => $user[0]['users_default_dashboard'],
            'profile_image' => $user[0]['profile_image'],
            'profile_foldername' => $user[0]['profile_foldername'],
            'landing_page' => $user[0]['landing_page'],
            'accessmenu' => $menu[0]['accessmenu'] ?? null,
            'banner_color' => $user[0]['banner_color'],
            'theme_color' => $user[0]['theme_color']
        ];

        $usersessiondata = $this->setUserSession($userData);
        session()->set('isLoggedIn', true);
        session()->set('userData', $userData);

        // Handle missing client or role
        if (
            $usersessiondata['isLoggedIn'] != 1 ||
            empty($usersessiondata['client']) ||
            empty($usersessiondata['userlevel'])
        ) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Stakeholders or roles have not been assigned to you. Please contact Admin.'
            ]);
        }

        // Build redirect URL for frontend to navigate
        switch ($userData['landing_page']) {
            case 1:
                $redirect = base_url('etrack/dashboard');
                break;
            case 2:
                $redirect = base_url('etrack/attendance/view');
                break;
            case 3:
                $redirect = base_url('Task/Task_manage/my_task');
                break;
            case 4:
                $redirect = base_url('Task/Task_manage/team_tasks_allocate');
                break;
            case 5:
                $redirect = base_url('Project_Manage/PM_ucn');
                break;
            case 6:
                $redirect = base_url('Project_Manage/PM_projects');
                break;
            case 7:
                $redirect = base_url('marketplace/dashboard');
                break;
            case 8:
                $redirect = base_url('SCORM/Scorm_client/reviews');
                break;
            default:
                $redirect = base_url('my_training');
        }

        // return $this->response->setJSON([
        //     'success' => true,
        //     'redirect_url' => $redirect,
        //     'user' => $userData
        // ]);
        return $this->response->setJSON([
            'success' => true,
            'redirect_url' => $redirect,
            'user' => $userData
        ]);

    }


    private function setUserSession($user)
    {
        $data = [
            'id_user' => $user['id_user'],
            'username' => $user['username'],
            'name' => $user['name'],
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
            'login_source' => 'angular'
        ];
        $newdata['username'] = $user['username'];
        $newdata['exittime'] = ' ';
        $ipaddress = $this->getClientIpAddress();
        $newdata['ipaddress'] = $ipaddress;
        $newdata['dateandtime'] = time();
        $newdata['extra'] = ' ';
        $result = $this->login_model->updateUserActive($newdata);
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
                session()->setFlashdata('success', 'Mail Sent Successfully. We will contact soon');
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
    //             session()->setFlashdata('success', 'Mail Sent Successfully. We will contact soon');
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

                'message' => 'Mail sent successfully. We will contact you soon.'

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
    //             'message' => 'Mail sent successfully. We will contact you soon.'
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

                    'message' => 'Product enquiry sent successfully.'

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
                . '<small>Note: Reset link expires in 15 minutes.</small><br><br>'
                . 'Thanks,<br>Project Admin';

            $emailService = \Config\Services::email();
            $emailService->setFrom('do-not-reply@touchstonelc.com', 'Dochek');
            $emailService->setTo($to);
            $emailService->setSubject($subject);
            $emailService->setMessage($message);

            if ($emailService->send()) {
                return $this->response->setJSON([
                    'status' => true,
                    'message' => 'Reset password link sent to your registered email. Please verify with in 15 mins.'
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
