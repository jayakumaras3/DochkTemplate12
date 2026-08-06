<?php

namespace App\Controllers;

use App\Models\User_login\Login_model;
use App\Models\Dashboard\Dashboard_model;
use App\Models\Settings\Settings_model;
use App\Models\Social\Post_model;

#[\AllowDynamicProperties]

class Landing_dochek extends BaseController
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
        // echo "<script>window.location.href = '" . base_url('app/Views/angular_view/') . "';</script>";

        // exit();
        // $data = [];
        helper(['form']);
        $data['landing_type'] = '4';
        $data['username'] = 'notvalid';
        $_SESSION['login_source'] = 'ci';

        // echo view('landing/header', $data);
        echo view('landing/index', $data);

        // echo view('landing/footer');
    }
    // public function login_register()
    // {

    //     $data = [];
    //     helper(['form']);
    //     $data['landing_type'] = '4';
    //     $_SESSION['login_source'] = 'ci';
    //     if ($this->request->getPost()) {
    //         //print_r("sss");
    //         ///exit();
    //         // print_r('tt');
    //         // exit();
    //         $rules = [
    //             'username' => 'required|min_length[3]',
    //             'password' => 'required|max_length[255]|validateUser[username,password]',
    //         ];
    //         $errors = [
    //             'password' => [
    //                 'validateUser' => 'Username or Password don\'t Match'
    //             ]
    //         ];

    //         if (!$this->validate($rules, $errors)) {
    //             $data['validation'] = $this->validator;
    //         } else {
    //             $username = $this->request->getVar('username');
    //             $user = $this->login_model->login_view($username);
    //             // echo "<pre>";
    //             //  print_r($user);
    //             //  exit();
    //             $taskcount = $this->dashboard_model->taskcount($username);
    //             $marketplaceaccess = $this->settings_model->marketplaceaccess($user[0]['client']);
    //             if (!empty($marketplaceaccess)) {
    //                 $user['marketplaceaccess'] = 1;
    //             } else {
    //                 $user['marketplaceaccess'] = 0;
    //             }
    //             //print_r($taskcount);
    //             $totaltaskcount = $taskcount;
    //             $currdate = date('Y-m-d');

    //             if ($user[0]['validity'] >= $currdate || $user[0]['validity'] == '0000-00-00' || $user[0]['validity'] == '') {
    //                 $user['id_user'] = $user[0]['id_user'];
    //                 $user['username'] = $user[0]['username'];
    //                 $user['lang'] = $user[0]['lang'];
    //                 $user['client'] = $user[0]['client'];
    //                 $user['report_to_you'] = $user[0]['report_to_you'];
    //                 $user['partner_code'] = $user[0]['partner_code'];
    //                 $user['logo'] = $user[0]['logo'];
    //                 $user['logo_dark'] = $user[0]['logo_dark'];
    //                 $user['userlevel'] = $user[0]['userlevel'];
    //                 $user['name'] = $user[0]['name'];
    //                 $user['last_name'] = $user[0]['last_name'];
    //                 $user['timezone'] = $user[0]['timezone'];
    //                 $user['timezone_pname'] = $user[0]['timezone_pname'];
    //                 $user['demoaccess'] = $user[0]['val2'];
    //                 $user['totaltaskcount'] = $totaltaskcount;
    //                 $user['default_dashboard'] = $user[0]['default_dashboard'];
    //                 $user['users_default_dashboard'] = $user[0]['users_default_dashboard'];
    //                 $user['profile_image'] = $user[0]['profile_image'];
    //                 $user['profile_foldername'] = $user[0]['profile_foldername'];
    //                 $user['landing_page'] = $user[0]['landing_page'];
    //                 $user['home_page'] = $user[0]['landing_page'];
    //                 $menu = $this->settings_model->menu_view($user[0]['client']);
    //                 $user['accessmenu'] = $menu[0]['accessmenu'];
    //                 $user['banner_color'] = $user[0]['banner_color'];
    //                 $user['theme_color'] = $user[0]['theme_color'];
    //                 $userpostCount = $this->post_model->getUserPostCount($user['id_user']);
    //                 $user['userpostCount'] = isset($userpostCount[0]['status']) ? $userpostCount[0]['status'] : 0;
    //                 // exit();
    //                 $usersessiondata = $this->setUserSession($user); //get user data from users table
    //                 // print_r($usersessiondata);
    //                 // exit();

    //                 $session = session();
    //                 if (!empty($user['lang'])) {
    //                     session()->set('locale', $user['lang']);
    //                 }
    //                 $session->isLoggedIn = 1;
    //                 //  $session['home_page'] = 1;
    //                 if ($usersessiondata['isLoggedIn'] != 1 && (empty($usersessiondata['client']))) {
    //                     session()->setFlashdata('error', 'Stakeholders or roles have not been assigned to you. Please contact Admin.');
    //                     return redirect()->to(base_url('Landing_dochek'));
    //                 } else {
    //                     if ($user['landing_page'] == 1) {
    //                         $session->set('home_page', 1);
    //                         return redirect()->to(base_url('etrack/dashboard'));
    //                     } elseif ($user['landing_page'] == 2) {
    //                         $session->set('home_page', 2);
    //                         return redirect()->to(base_url('etrack/attendance/view'));
    //                     } elseif ($user['landing_page'] == 3) {
    //                         $session->set('home_page', 3);
    //                         return redirect()->to(base_url('Task/Task_manage/my_task'));
    //                     } elseif ($user['landing_page'] == 4) {
    //                         $session->set('home_page', 4);
    //                         return redirect()->to(base_url('Task/Task_manage/team_tasks_allocate'));
    //                     } elseif ($user['landing_page'] == 5) {
    //                         $session->set('home_page', 5);
    //                         return redirect()->to(base_url('Project_Manage/PM_ucn'));
    //                     } elseif ($user['landing_page'] == 6) {
    //                         $session->set('home_page', 6);
    //                         return redirect()->to(base_url('Project_Manage/PM_projects'));
    //                     } elseif ($user['landing_page'] == 7) {
    //                         $session->set('home_page', 7);
    //                         return redirect()->to(base_url('marketplace/dashboard'));
    //                     } elseif ($user['landing_page'] == 8) {
    //                         $session->set('home_page', 8);
    //                         return redirect()->to(base_url('SCORM/Scorm_client/reviews'));
    //                     } else {
    //                         return redirect()->to(base_url('my_training'));
    //                     }
    //                 }
    //             } else {
    //                 session()->setFlashdata('error', value: 'Sorry! Validity has been expired. Please contact Admin.');
    //                 return redirect()->to(base_url() . 'Landing_dochek');
    //             }
    //         }
    //     }
    //     echo view('landing/header', $data);
    //     echo view('landing/index');
    //     echo view('landing/footer');
    // }
    public function login_register()
    {

        $data = [];
        helper(['form']);
        $data['landing_type'] = '4';
        $_SESSION['login_source'] = 'ci';
        if ($this->request->getPost()) {
            //print_r("sss");
            ///exit();
            // print_r('tt');
            // exit();
            $rules = [
                'username' => 'required|min_length[3]',
                'password' => 'required|max_length[255]|validateUser[username,password]',
            ];
            $errors = [
                'password' => [
                    'validateUser' => 'Username or Password don\'t Match'
                ]
            ];

            if (!$this->validate($rules, $errors)) {
                $data['validation'] = $this->validator;
            } else {
                $username = $this->request->getVar('username');
                $user = $this->login_model->login_view($username);
                // echo "<pre>";
                //  print_r($user);
                //  exit();
                $taskcount = $this->dashboard_model->taskcount($username);
                $marketplaceaccess = $this->settings_model->marketplaceaccess($user[0]['client']);
                if (!empty($marketplaceaccess)) {
                    $user['marketplaceaccess'] = 1;
                } else {
                    $user['marketplaceaccess'] = 0;
                }
                //print_r($taskcount);
                $totaltaskcount = $taskcount;
                $currdate = date('Y-m-d');

                if ($user[0]['validity'] >= $currdate || $user[0]['validity'] == '0000-00-00' || $user[0]['validity'] == '') {
                    $user['id_user'] = $user[0]['id_user'];
                    $user['username'] = $user[0]['username'];
                    $user['lang'] = $user[0]['lang'];
                    $user['client'] = $user[0]['client'];
                    $user['report_to_you'] = $user[0]['report_to_you'];
                    $user['partner_code'] = $user[0]['partner_code'];
                    $user['logo'] = $user[0]['logo'];
                    $user['logo_dark'] = $user[0]['logo_dark'];
                    $user['userlevel'] = $user[0]['userlevel'];
                    $user['name'] = $user[0]['name'];
                    $user['region'] = $user[0]['region'];
                    $user['last_name'] = $user[0]['last_name'];
                    $user['timezone'] = $user[0]['timezone'];
                    $user['timezone_pname'] = $user[0]['timezone_pname'];
                    $user['demoaccess'] = $user[0]['val2'];
                    $user['totaltaskcount'] = $totaltaskcount;
                    $user['default_dashboard'] = $user[0]['default_dashboard'];
                    $user['users_default_dashboard'] = $user[0]['users_default_dashboard'];
                    $user['profile_image'] = $user[0]['profile_image'];
                    $user['profile_foldername'] = $user[0]['profile_foldername'];
                    $user['landing_page'] = $user[0]['landing_page'];
                    $user['home_page'] = $user[0]['landing_page'];
                    $menu = $this->settings_model->menu_view($user[0]['client']);
                    $user['accessmenu'] = $menu[0]['accessmenu'];
                    $user['banner_color'] = $user[0]['banner_color'];
                    $user['theme_color'] = $user[0]['theme_color'];
                    $userpostCount = $this->post_model->getUserPostCount($user['id_user']);
                    $user['userpostCount'] = isset($userpostCount[0]['status']) ? $userpostCount[0]['status'] : 0;
                    $user['session_version'] = $user[0]['session_version'];
                    // exit();
                    $usersessiondata = $this->setUserSession($user); //get user data from users table
                    // print_r($usersessiondata);
                    // exit();

                    session()->set([
                        'isLoggedIn' => true,
                        'userData'   => $usersessiondata,
                        'id_user' => $usersessiondata['id_user'],
                        'session_version' => (int)$user['session_version']
                    ]);
                    $session = session();
                    if (!empty($user['lang'])) {
                        session()->set('locale', $user['lang']);
                    }
                    $session->isLoggedIn = 1;
                    //  $session['home_page'] = 1;
                    $loginAllowedRoles = [3, 6, 44];
                    $userLevels = array_map('intval', explode(',', $user['userlevel']));
                    if (empty(array_intersect($userLevels, $loginAllowedRoles))) {
                        $result = $this->login_model->updateUserlevel($user['id_user'], '3');
                    }
                    if ($usersessiondata['isLoggedIn'] != 1 && (empty($usersessiondata['client']))) {
                        session()->setFlashdata('error', 'Stakeholders or roles have not been assigned to you. Please contact Admin.');
                        return redirect()->to(base_url('Landing_dochek'));
                    } else {
                        if ($user['landing_page'] == 1) {
                            $session->set('home_page', 1);
                            return redirect()->to(base_url('etrack/dashboard'));
                        } elseif ($user['landing_page'] == 2) {
                            $session->set('home_page', 2);
                            return redirect()->to(base_url('etrack/attendance/view'));
                        } elseif ($user['landing_page'] == 3) {
                            $session->set('home_page', 3);
                            return redirect()->to(base_url('Task/Task_manage/my_task'));
                        } elseif ($user['landing_page'] == 4) {
                            $session->set('home_page', 4);
                            return redirect()->to(base_url('Task/Task_manage/team_tasks_allocate'));
                        } elseif ($user['landing_page'] == 5) {
                            $session->set('home_page', 5);
                            return redirect()->to(base_url('Project_Manage/PM_ucn'));
                        } elseif ($user['landing_page'] == 6) {
                            $session->set('home_page', 6);
                            return redirect()->to(base_url('Project_Manage/PM_projects'));
                        } elseif ($user['landing_page'] == 7) {
                            $session->set('home_page', 7);
                            return redirect()->to(base_url('marketplace/dashboard'));
                        } elseif ($user['landing_page'] == 8) {
                            $session->set('home_page', 8);
                            return redirect()->to(base_url('SCORM/Scorm_client/reviews'));
                        } else {
                            // print_r(session()->get('userData'));
                            // exit();

                            return redirect()->to(base_url('my_training'));
                        }
                    }
                } else {
                    session()->setFlashdata('error', value: 'Sorry! Validity has been expired. Please contact Admin.');
                    return redirect()->to(base_url() . 'Landing_dochek');
                }
            }
        }
        // echo view('landing/header', $data);
        echo view('landing/index', $data);
        // echo view('landing/footer');
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
            'region' => $user['region'],
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
            'userpostCount' => $user['userpostCount'],
            'login_source' => 'ci',
            'marketplaceaccess' => $user['marketplaceaccess']
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

    public function logout()
    {
        // print_r(session()->get('username')."ttt");
        $source = session()->get('login_source') ?? 'ci';


        $newdata['username'] = session()->get('username');
        $newdata['exittime'] = '';
        $ipaddress = $this->getClientIpAddress();
        $newdata['ipaddress'] = $ipaddress;
        $newdata['dateandtime'] = time();
        $newdata['extra'] = '';
        $result = $this->login_model->updateUserActive($newdata);
        session()->destroy();
        unset($_COOKIE['clientid']); // client filter unset
        // setcookie('clientid', null, -1, '/'); //client filter unset
        if ($source === 'angular') {
            return redirect()->to(base_url('ang/login'));
        } else {
            return redirect()->to(base_url('Landing_dochek'));
        }
    }
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


    // public function logout()
    // {
    //     $newdata['username'] = session()->get('username');
    //     $newdata['exittime'] = '';
    //     $ipaddress = $this->getClientIpAddress();
    //     $newdata['ipaddress'] = $ipaddress;
    //     $newdata['dateandtime'] = time();
    //     $newdata['extra'] = '';

    //     $this->login_model->updateUserActive($newdata);

    //     session()->destroy();
    //     unset($_COOKIE['clientid']);
    //     setcookie('clientid', '', -1, '/');

    //     // Output JavaScript to redirect to Angular app
    //     echo "<script>window.location.href = '" . base_url('app/Views/angular_view/') . "';</script>";

    //     exit(); // Ensure script stops here
    // }
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
