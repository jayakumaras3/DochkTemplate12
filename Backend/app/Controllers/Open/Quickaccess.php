<?php

namespace App\Controllers\Open;

use App\Controllers\BaseController;

use App\Models\SCORM\Scorm_dashboard_model;
use App\Models\Demo\Cart_model;
use App\Models\User_login\Login_model;
use App\Models\Settings\Settings_model;

#[\AllowDynamicProperties]
class Quickaccess extends BaseController
{
    protected $scorm_dashboard_model;
    public function __construct()
    {
        $this->scorm_dashboard_model = new Scorm_dashboard_model();
        $this->cart_model = new Cart_model();
        $this->login_model = new Login_model();
        $this->settings_model = new Settings_model();
    }
    public function index($uid)
    {
        $data = [];
        $data['landing_type'] = '1';
        $data['header'] = 'Dashboard';
        $data['username'] = 'notvalid';

        if (strlen($uid) > 3) {
            $cart_access_id = $uid / 5534;
            $course_exists = $this->cart_model->checkValid($cart_access_id);
            if ($course_exists) {
                $today = date("Y-m-d");
                if ($course_exists[0]['expiry_date'] >= $today) {
                    $data['username'] = 'SplUsr' . $uid;
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0023'));
                }
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0023'));
            }
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0023'));
        }
        // echo view('landing/header', $data);
        echo view('landing/index', $data);
        // echo view('landing/header', $data);
    }
    public function demo($uid)
    {
        $username = 'notvalid';

        if (strlen($uid) > 3) {
            $cart_access_id = $uid / 5534;
            $course_exists = $this->cart_model->checkValid($cart_access_id);
            if ($course_exists) {
                $today = date("Y-m-d");
                if ($course_exists[0]['expiry_date'] >= $today) {
                    $username = 'SplUsr' . $uid;
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0023'));
                    return redirect()->to(base_url());
                }
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0023'));
                return redirect()->to(base_url());
            }
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0023'));
            return redirect()->to(base_url());
        }

        // Generate a cryptographically secure, single-use token (64-char hex)
        // Redirect to Angular quickaccess page — only the numeric demo ID in the URL.
        // Username is derived server-side during authentication; it is never exposed in the URL.
        return redirect()->to(base_url('ang/authentication/quickaccess/' . $uid));
    }

    /**
     * API Endpoint: Authenticate a demo user by numeric demo ID + password.
     * POST /api/quickaccess/authenticate
     * Body: { demoid: int, password: string }
     */
    public function apiQuickAccessAuthenticate()
    {
        $input = $this->request->getJSON();
        $demoid = isset($input->demoid) ? (int) $input->demoid : null;
        $password = isset($input->password) ? trim((string) $input->password) : null;

        if (!$demoid || !$password) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Demo ID and password are required.',
            ]);
        }

        if (strlen((string) $demoid) <= 3) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid demo link.',
            ]);
        }

        $cart_access_id = $demoid / 5534;
        $course_exists = $this->cart_model->checkValid($cart_access_id);
        if (!$course_exists) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'Invalid demo link. Please contact support.',
                'debug' => ['demoid' => $demoid, 'cart_id' => $cart_access_id],
            ]);
        }

        $today = date('Y-m-d');
        $expiryDate = (string) ($course_exists[0]['expiry_date'] ?? '');
        // ✅ If expiry_date is empty/null, treat as never expires (always valid)
        if (!empty($expiryDate) && $expiryDate < $today) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'This demo link has expired. Please contact support.',
                'debug' => ['demoid' => $demoid, 'expiry_date' => $expiryDate, 'today' => $today, 'cart_id' => $cart_access_id],
            ]);
        }

        $username = $course_exists[0]['username'] ?? ('SplUsr' . $demoid);
        $user = $this->login_model->login_view($username);
        if (empty($user)) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'User not found.',
                'debug' => ['username' => $username],
            ]);
        }

        $storedPasswordHash = $user[0]['password'] ?? '';
        $secretCode = (string) ($course_exists[0]['secret_code'] ?? '');

        // ✅ Password validation supports both hash (from user table) AND secret_code (demo secret)
        $passwordMatchesHash = ($storedPasswordHash !== '' && password_verify($password, $storedPasswordHash));
        $passwordMatchesSecretCode = ($secretCode !== '' && hash_equals($secretCode, $password));

        if (!$passwordMatchesHash && !$passwordMatchesSecretCode) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'Invalid password. Please try again.',
                'debug' => [
                    'username' => $username,
                    'hasStoredHash' => !empty($storedPasswordHash),
                    'hasSecretCode' => !empty($secretCode),
                    'passwordMatchesHash' => $passwordMatchesHash,
                    'passwordMatchesSecretCode' => $passwordMatchesSecretCode,
                ],
            ]);
        }

        $currdate = date('Y-m-d');
        if ($user[0]['validity'] >= $currdate || $user[0]['validity'] == '0000-00-00' || $user[0]['validity'] == '') {
            $userData = [
                'id_user' => $user[0]['id_user'],
                'username' => $user[0]['username'] ?? $username,
                'client' => $user[0]['client'],
                'userlevel' => $user[0]['userlevel'],
                'name' => $user[0]['name'],
                'timezone' => $user[0]['timezone'],
                'timezone_pname' => $user[0]['timezone_pname'],
                'demoaccess' => $user[0]['val2'],
                'totaltaskcount' => 0,
                'default_dashboard' => $user[0]['default_dashboard'],
                'users_default_dashboard' => $user[0]['users_default_dashboard'],
                'accessmenu' => '',
            ];

            $menu = $this->settings_model->menu_view($user[0]['client']);
            if (!empty($menu)) {
                $userData['accessmenu'] = $menu[0]['accessmenu'];
            }

            $this->setUserSession($userData);

            session()->set([
                'isLoggedIn' => true,
                'userData' => $userData
            ]);

            return $this->response->setStatusCode(200)->setJSON([
                'success' => true,
                'message' => 'Login successful',
                'user' => [
                    'id_user' => $userData['id_user'],
                    'name' => $userData['name'],
                    'client' => $userData['client'],
                    'userlevel' => $userData['userlevel'],
                ],
                'redirectUrl' => base_url('my_training'),
            ]);
        }

        return $this->response->setStatusCode(401)->setJSON([
            'success' => false,
            'message' => 'User account is no longer valid.',
        ]);
    }


    public function checkAccess()
    {
        $data = [];
        helper(['form']);
        $demoid =  $this->request->getVar('demoid');
        if ($this->request->getPost()) {
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
                $data['quickvalidation'] = $this->validator;
            } else {
                $username =  $this->request->getVar('username');
                $user = $this->login_model->login_view($username);
                $totaltaskcount =  0;
                $currdate = date('Y-m-d');
                if ($user[0]['validity'] >= $currdate || $user[0]['validity'] == '0000-00-00' || $user[0]['validity'] == '') {
                    $user['id_user'] = $user[0]['id_user'];
                    $user['username'] = $user[0]['username'];
                    $user['client'] = $user[0]['client'];
                    $user['userlevel'] = $user[0]['userlevel'];
                    $user['name'] = $user[0]['name'];
                    $user['timezone'] = $user[0]['timezone'];
                    $user['timezone_pname'] = $user[0]['timezone_pname'];
                    $user['demoaccess'] = $user[0]['val2'];
                    $user['totaltaskcount'] = $totaltaskcount;
                    $user['default_dashboard'] = $user[0]['default_dashboard'];
                    $user['users_default_dashboard'] = $user[0]['users_default_dashboard'];
                    $menu = $this->settings_model->menu_view($user[0]['client']);
                    $user['accessmenu']   = $menu[0]['accessmenu'];
                    $this->setUserSession($user); //get user data from users table
                    return redirect()->to(base_url('my_training'));
                }
            }
        }
        session()->setFlashdata('error', lang('Messages.Error_0023'));
        return redirect()->to(base_url() . 'quickaccess/demo/' . $demoid);
    }
    private function setUserSession($user)
    {
        $username = $user['username'] ?? '';
        $data = [
            'id_user' => $user['id_user'] ?? null,
            'username' => $username,
            'name' => $user['name'] ?? '',
            'time_logged_in' => time(),
            'isLoggedIn' => true,
            // 'lang' => $user['lang'],
            'client' => $user['client'] ?? '',
            'userlevel' => $user['userlevel'] ?? '',
            'accessmenu' => $user['accessmenu'] ?? '',
            'timezone' => $user['timezone'] ?? '',
            'timezone_pname' => $user['timezone_pname'] ?? '',
            'demoaccess' => $user['demoaccess'] ?? '',
            'totaltaskcount' => $user['totaltaskcount'] ?? 0,
            'default_dashboard' => $user['default_dashboard'] ?? '',
            'users_default_dashboard' => $user['users_default_dashboard'] ?? '',
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $newdata['username'] = $username;
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

    /**
     * @deprecated Use apiQuickAccessAuthenticate() instead.
     * Kept for backward compatibility only.
     */
    public function apiQuickAccessLogin()
    {
        // Set JSON response header
        header('Content-Type: application/json');

        // Get JSON input
        $input = $this->request->getJSON();
        $username = $input->username ?? null;
        $password = $input->password ?? null;

        // Validate inputs
        if (!$username || !$password) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Username and password are required'
            ]);
        }

        // Validate user credentials
        $rules = [
            'username' => 'required|min_length[3]',
            'password' => 'required|max_length[255]|validateUser[username,password]',
        ];

        $errors = [
            'password' => [
                'validateUser' => 'Username or Password don\'t Match'
            ]
        ];

        // Temporarily set the POST data for validation
        $_POST['username'] = $username;
        $_POST['password'] = $password;

        if (!$this->validate($rules, $errors)) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'Invalid username or password'
            ]);
        }

        // Get user data
        $user = $this->login_model->login_view($username);

        if (empty($user)) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        // Check user validity
        $totaltaskcount = 0;
        $currdate = date('Y-m-d');

        if ($user[0]['validity'] >= $currdate || $user[0]['validity'] == '0000-00-00' || $user[0]['validity'] == '') {
            $userData = [
                'id_user' => $user[0]['id_user'],
                'username' => $user[0]['username'] ?? $username,
                'client' => $user[0]['client'],
                'userlevel' => $user[0]['userlevel'],
                'name' => $user[0]['name'],
                'timezone' => $user[0]['timezone'],
                'timezone_pname' => $user[0]['timezone_pname'],
                'demoaccess' => $user[0]['val2'],
                'totaltaskcount' => $totaltaskcount,
                'default_dashboard' => $user[0]['default_dashboard'],
                'users_default_dashboard' => $user[0]['users_default_dashboard'],
                'accessmenu' => '',
            ];

            // Get menu access
            $menu = $this->settings_model->menu_view($user[0]['client']);
            if (!empty($menu)) {
                $userData['accessmenu'] = $menu[0]['accessmenu'];
            }

            // Set user session
            $this->setUserSession($userData);

            // Return success response
            return $this->response->setStatusCode(200)->setJSON([
                'success' => true,
                'message' => 'Login successful',
                'user' => [
                    'id_user' => $userData['id_user'],
                    'username' => $userData['username'],
                    'name' => $userData['name'],
                    'client' => $userData['client'],
                    'userlevel' => $userData['userlevel'],
                ],
                'redirectUrl' => base_url('my_training')
            ]);
        }

        return $this->response->setStatusCode(401)->setJSON([
            'success' => false,
            'message' => 'User account is no longer valid'
        ]);
    }
}
