<?php

namespace App\Controllers\User_login;

use App\Controllers\BaseController;

use App\Models\User_login\Login_model;
use App\Models\Settings\Dropdown_model;
use App\Models\User_login\Users_model;
use App\Models\Settings\Settings_model;
use CodeIgniter\I18n\Time;

#[\AllowDynamicProperties]
class Users extends BaseController
{
    private $db;

    public function __construct()
    {
        // $this->is_session_available();
        $this->login_model = new Login_model();
        $this->dropdown_model = new Dropdown_model();
        $this->users_model = new Users_model();
        $this->settings_model = new Settings_model();
    }
    // function is_session_available()
    // {
    //     $client = session()->get('client');
    //     $userlevel = session('userlevel');
    //     $arrayuserlevel = array_map('intval', explode(',', $userlevel) ?? '');
    //     if (!in_array('44', $arrayuserlevel)) {
    //         session()->setFlashdata('error', lang('Messages.Error_0004'));
    //         header('Location:' . base_url('my_training'));
    //         exit();
    //     }
    // }
    public function index() //fetch data from users table to display
    {
        if ($response =  $this->requireRole(['6', '44'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $cid = session()->get('cid');
        // print_r($cid."ttt");
        // exit();
        $data['main_header_link'] = 'User_login/client_list';
        $data['addusers'] = 'users';
        $data['register'] = 'User_login/users/register';
        $data['edit'] = 'User_login/users/editUsers';
        $data['clientid'] = isset($cid) ? $cid : $this->request->getVar('cid');
        $data['usertable'] = $this->login_model->users_view($data['clientid']);
        //print_r($data['usertable']);
        $data['clientlicense'] = $this->settings_model->clientlicensedata($data['clientid']);
        echo view('templates/header_view', $data);
        echo view('users/users_view', $data);
        echo view('templates/footer_view');
    }

    public function register()
    {
        if ($response =  $this->requireRole(['6', '44'])) {
            return $response;
        }
        $data = [];
        $data['header_link'] = 'User_login/client_list/my_client_list';
        $data['main_header_link'] = 'User_login/client_list';
        $data['addusers'] = 'users';
        $data['register'] = 'User_login/users/register';
        $data['edit'] = 'users/editUsers';
        helper(['form']);
        $model = new Login_model();
        if (isset($_POST['cid'])) {
            $data['clientid'] = $_POST['cid'];
            $_SESSION['clientid'] = $data['clientid'];
        } else if (isset($_GET['cid'])) {
            $data['clientid'] = $_GET['cid'];
        } else if (isset($_SESSION['clientid'])) {
            $data['clientid'] = $_SESSION['clientid'];
        } else {
            return redirect()->to(base_url('User_login/client_list'));
        }
        $data['userlevelData'] = $this->dropdown_model->getdropdownData(2);
        $data['timezone'] = $this->login_model->gettimezonedata();
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $password = array();
        $alpha_length = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alpha_length);
            $password[] = $alphabet[$n];
        }
        $data['temppass'] = $this->generateTempPassword(12);
        $name = $this->request->getVar('name');
        if ($this->request->getPost() && $name != '') {
            $rules = [
                'name' => 'required|min_length[1]|max_length[32]',
                'email' => 'required|min_length[6]|max_length[50]|valid_email|is_unique[users.email]',
                //'designation' => 'required',
                'userlevelItem' => 'required',
                'password' => [
                    'label' => 'Password',
                    'rules' => 'required|min_length[8]|max_length[64]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/]',
                    'errors' => [
                        'regex_match' => 'Password must include uppercase, lowercase, number, and special character.'
                    ]
                ],
                // 'app_username' => [
                //     'label' => 'App Username',
                //     'rules' => ['required', 'max_length[10]', 'is_unique[users.app_username]'],
                //     'errors' => [
                //         'required' => 'The App Username field is required.',
                //         'max_length' => 'The App Username field cannot exceed 10 characters.', // Corrected max_length
                //     ],
                // ],
                // 'app_password' => [
                //     'label' => 'App Password',
                //     'rules' => ['required', 'max_length[255]'],
                //     'errors' => [
                //         'required' => 'The App Password field is required.',
                //         'max_length' => 'The App Password field cannot exceed 255 characters.', // Corrected max_length
                //     ],
                // ],
                // 'timezone' => 'required',
                // 'lang' => 'required'
            ];
            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
            } else {

                $timestamp = time();
                //   $designation = $this->request->getVar('designation');
                //  $designation = isset($designation) ? $designation : '';
                $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                $password = array();
                $alpha_length = strlen($alphabet) - 1;
                for ($i = 0; $i < 5; $i++) {
                    $n = rand(0, $alpha_length);
                    $password[] = $alphabet[$n];
                }

                $data['temppass'] = $this->generateTempPassword(12);
                $last_name = $this->request->getVar('last_name');
                $last_name = isset($last_name) ? $last_name : '';
                $app_username = $this->request->getVar('app_username');
                $app_username = isset($app_username) ? $app_username : '';
                $newdata = [
                    'name' => $this->request->getVar('name'),
                    'last_name' => $last_name,
                    'username' => $this->request->getVar('email'),
                    'email' => $this->request->getVar('email'),
                    //  'designation' => $designation,
                    'password' => trim(password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)),
                    'app_username' => $app_username,
                    'app_password' => $this->request->getVar('app_password'),
                    'timezone' => $this->request->getVar('timezone'),
                    'client_id' => $this->request->getVar('cid'),
                    'lang' => $this->request->getVar('lang'),
                    'userid' => uniqid(),
                    'hash' => $data['temppass'],
                    'timestamp' => $timestamp,
                    'valid' => '1',
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                if ($model->save($newdata)) {
                    $insertedid = $model->InsertID();
                    if ($this->request->getVar('cid')) {
                        $userdata['fk_id_user'] = $insertedid;
                        $userdata['fk_id_dc'] = 1;
                        $userdata['fk_id_d'] = $this->request->getVar('cid');
                        $userdata['status'] = 1;
                        $userdata['createdby'] = session()->get('id_user');
                        $userdata['createdon'] = time();
                        $userdata['last_updated_by'] = session()->get('id_user');
                        $userdata['last_updated_on'] = time();

                        $result = $this->dropdown_model->updateCategory($userdata);
                    }
                    if ($this->request->getVar('userlevelItem')) {
                        $userleveldata['fk_id_user'] = $insertedid;
                        $userleveldata['fk_id_dc'] = 2;
                        $userleveldata['fk_id_d'] = $this->request->getVar('userlevelItem');
                        $userleveldata['status'] = 1;
                        $userleveldata['createdby'] = session()->get('id_user');
                        $userleveldata['createdon'] = time();
                        $userdata['last_updated_by'] = session()->get('id_user');
                        $userdata['last_updated_on'] = time();
                        $result = $this->dropdown_model->updateCategory($userleveldata);
                    }
                    if ($result) {
                        $session = session();
                        $session->setFlashdata('success', 'Successful Registration');
                        return redirect()->to(base_url() . 'User_login/Partner_users');
                    }
                }
                $session = session();
                $session->setFlashdata('error', 'Data not found, Try again');
                return redirect()->to(base_url() . 'User_login/Partner_users');
            }
        }
        echo view('templates/header_view', $data);
        echo view('users/users_add_view', $data);
        echo view('templates/footer_view');
    }
    function generateTempPassword($length = 12)
    {
        $lower   = 'abcdefghijklmnopqrstuvwxyz';
        $upper   = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $special = '!@#$%^&*()_+-=[]{}|;:<>?';

        $password = '';
        $password .= $lower[random_int(0, strlen($lower) - 1)];
        $password .= $upper[random_int(0, strlen($upper) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $special[random_int(0, strlen($special) - 1)];

        $allChars = $lower . $upper . $numbers . $special;
        for ($i = strlen($password); $i < $length; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }

        // Secure shuffle (Fisher-Yates)
        $passwordArray = str_split($password);
        for ($i = count($passwordArray) - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            $temp = $passwordArray[$i];
            $passwordArray[$i] = $passwordArray[$j];
            $passwordArray[$j] = $temp;
        }

        return implode('', $passwordArray);
    }
    public function editUsers(int $id_user, int $clientid) // update data from users table to display
    {
        if ($response =  $this->requireRole(['6', '44'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        // exit();
        // print_r($id_user);
        // exit();
        $data['header_link'] = 'User_login/users';
        $data['form_link'] = 'User_login/users/editUsers/';
        $data['form_link_1'] = 'User_login/users/passeditUsers/';
        $data['form_link_2'] = 'User_login/users/updateCategoryItem/';
        $data['form_link_3'] = 'User_login/users/deleteUserCategory/';
        $data['form_link_4'] = 'User_login/users/updateCategory/';
        $data['clientid'] = $clientid;
        $userdata = $this->users_model->usersedit_view($id_user);

        $data['table'] = $this->dropdown_model->categoryDetails();
        $data['categoryData'] = $this->dropdown_model->getCategoryData();
        $data['clientData'] = $this->dropdown_model->getclientData();
        $data['userlevelData'] = $this->dropdown_model->getdropdownData(2);
        $data['row'] = $userdata['0'];
        // print_r($data['row']);
        // exit();
        $data['clientUserlevelData'] = $this->users_model->clientUserlevel_view($id_user);
        $data['timezone'] = $this->login_model->gettimezonedata();
        $last_loginTime = $this->users_model->userActiveData($data['row']['username']);
        $data['moduleaccess'] = $this->dropdown_model->getdropdownData(17);
        $data['menu_view'] = $this->settings_model->menu_view($clientid);
        $data['user_default_dashboard'] = $this->settings_model->userdefaultdashvalue($id_user);
        $data['default_dashboard'] = $this->settings_model->clientlicensedata($clientid);

        $data['temppass'] = $this->generateTempPassword(12);
        if (!$last_loginTime) {
            $data['lastLoginTime'] = ' ';
        } else {
            $data['lastLoginTime'] = date("M-d-Y H:i", $last_loginTime[0]['dateandtime']);
        }

        if ($this->request->getPost()) {
            $rules = [
                'name' => 'required|min_length[1]|max_length[32]',
                'last_name' => 'required|min_length[1]|max_length[32]',
                'username' => 'required|max_length[50]|is_unique[users.username,id_user,' . $id_user . ']|is_unique[users.email,id_user,' . $id_user . ']',

            ];
            if (!$this->validate($rules)) {
                $data['validationEditUsers'] = $this->validator;
            } else {
                $id_user = $this->request->getVar('id_user');
                $last_name = $this->request->getVar('last_name');
                $last_name = isset($last_name) ? $last_name : '';
                $username = $this->request->getVar('username');
                $newdata = [
                    'name' => $this->request->getVar('name'),
                    'last_name' => $last_name,
                    'email' => $username,
                    'username' => $username,
                    'timezone' => $this->request->getVar('timezone'),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                    // 'designation' => $this->request->getVar('designation')
                ];
                $result = $this->users_model->updateUsersData($id_user, $newdata);
                // print_r($result );
                // exit();
                if ($result) {
                    $session = session();
                    $session->setFlashdata('success', lang('Messages.Success_0008'));
                    return redirect()->to(base_url() . 'User_login/users/editUsers/' . $id_user . '/' . $clientid);
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->route(base_url() . 'User_login/users/editUsers/' . $id_user . '/' . $clientid);
                }
            }
        }
        echo view('templates/header_view', $data);
        echo view('users/usersad_edit_view', $data);
        echo view('templates/footer_view', $data);
    }
    public function passeditUsers($id_user, $clientid)
    {
        if ($response =  $this->requireRole(['6', '44'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['header_link'] = 'User_login/users';
        $data['form_link'] = 'User_login/users/editUsers/';
        $data['form_link_1'] = 'User_login/users/passeditUsers/';
        $data['form_link_2'] = 'User_login/users/updateCategoryItem/';
        $data['form_link_3'] = 'User_login/users/deleteUserCategory/';
        $data['form_link_4'] = 'User_login/users/updateCategory/';
        $data['clientid'] = $clientid;
        $userdata = $this->users_model->usersedit_view($id_user);
        $data['table'] = $this->dropdown_model->categoryDetails();
        $data['categoryData'] = $this->dropdown_model->getCategoryData();
        $data['clientData'] = $this->dropdown_model->getclientData();
        $data['userlevelData'] = $this->dropdown_model->getdropdownData(2);
        $data['row'] = $userdata['0'];
        //print_r($data['row']);
        $data['clientUserlevelData'] = $this->users_model->clientUserlevel_view($id_user);
        //print_r($data['clientUserlevelData']);
        $data['timezone'] = $this->login_model->gettimezonedata();
        $last_loginTime = $this->users_model->userActiveData($data['row']['username']);
        if (!$last_loginTime) {
            $data['lastLoginTime'] = ' ';
        } else {
            $data['lastLoginTime'] = date("M-d-Y H:i", $last_loginTime[0]['dateandtime']);
        }
        $data['moduleaccess'] = $this->dropdown_model->getdropdownData(17);
        $data['menu_view'] = $this->settings_model->menu_view($clientid);
        $data['user_default_dashboard'] = $this->settings_model->userdefaultdashvalue($id_user);
        $data['default_dashboard'] = $this->settings_model->clientlicensedata($clientid);

        $data['temppass'] = $this->generateTempPassword(12);
        if ($this->request->getPost()) {
            // print_r("tt");
            // exit();
            $rules = [
                'password' => [
                    'label' => 'Password',
                    'rules' => 'required|min_length[8]|max_length[64]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/]',
                    'errors' => [
                        'regex_match' => 'Password must include uppercase, lowercase, number, and special character.'
                    ]
                ],
            ];
            if (!$this->validate($rules)) {
                $data['passvalidationEditUsers'] = $this->validator;
            } else {
                $id_user = $this->request->getVar('id_user');
                $newdata = [
                    'password' => trim(password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),

                ];
                $result = $this->users_model->updateUsersData($id_user, $newdata);
                if ($result) {
                    $session = session();
                    $session->setFlashdata('success', lang('Messages.Success_0008'));
                    return redirect()->to(base_url() . 'User_login/users/editUsers/' . $id_user . '/' . $clientid);
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . 'User_login/users/editUsers/' . $id_user . '/' . $clientid);
                }
            }
        }
        echo view('templates/header_view', $data);
        echo view('users/usersad_edit_view', $data);
        echo view('templates/footer_view', $data);
    }
    public function deleteUsers($id_user, $clientid)
    {
        if ($response =  $this->requireRole(['6', '44'])) {
            return $response;
        }
        $result = $this->users_model->deleteUser($id_user);
        if ($result) {
            $sessionData = session();
            $sessionData->setFlashdata('success', 'Category item : deleted Successful');
            return redirect()->to(base_url() . 'users?cid=' . $clientid);
        }
        session()->setFlashdata('error', lang('Messages.Error_0001'));
        session()->setFlashdata('alert-class', 'alert-danger');
        return redirect()->route('users')->withInput();
    }

    public function updateCategory($id_user, $clientid)
    { // add client name to user
        if ($response =  $this->requireRole(['6', '44'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['header_link'] = 'User_login/users';
        $data['form_link'] = 'User_login/users/editUsers/';
        $data['form_link_1'] = 'User_login/users/passeditUsers/';
        $data['form_link_2'] = 'User_login/users/updateCategoryItem/';
        $data['form_link_3'] = 'User_login/users/deleteUserCategory/';
        $data['form_link_4'] = 'User_login/users/updateCategory/';
        if ($this->request->getPost()) {
            $data['table'] = $this->dropdown_model->categoryDetails();
            $data['categoryData'] = $this->dropdown_model->getCategoryData();
            $data['clientData'] = $this->dropdown_model->getclientData();
            $data['userlevelData'] = $this->dropdown_model->getdropdownData(2);
            $userdata = $this->users_model->usersedit_view($id_user);
            $data['clientUserlevelData'] = $this->users_model->clientUserlevel_view($id_user);
            $data['row'] = $userdata['0'];
            $data['timezone'] = $this->login_model->gettimezonedata();
            $last_loginTime = $this->users_model->userActiveData($data['row']['username']);
            if (!$last_loginTime) {
                $data['lastLoginTime'] = ' ';
            } else {
                $data['lastLoginTime'] = date("M-d-Y H:i", $last_loginTime[0]['dateandtime']);
            }
            $data['moduleaccess'] = $this->dropdown_model->getdropdownData(17);
            $data['menu_view'] = $this->settings_model->menu_view($clientid);
            $data['user_default_dashboard'] = $this->settings_model->userdefaultdashvalue($id_user);
            $data['default_dashboard'] = $this->settings_model->clientlicensedata($clientid);
            $rulesData = [
                'clientItem' => 'required'

            ];
            if (!$this->validate($rulesData)) {
                $data['validationData'] = $this->validator;
            } else {
                $timestamp = time();
                $newdata = [
                    'fk_id_user' => $id_user,
                    'fk_id_dc' => '1',
                    'fk_id_d' => $this->request->getVar('clientItem'),
                    'createdon' => $timestamp,
                    'createdby' => session()->get('id_user'),
                    'status' => '1',
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                //print_r($newdata);
                $result = $this->dropdown_model->updateCategory($newdata);
                // print_r($result);
                // exit();
                if ($result) {
                    $this->dropdown_model->updateCilenttoUsertable($newdata['fk_id_d'], $newdata['fk_id_user']);

                    session()->setFlashdata('success', $result);
                    return redirect()->to(base_url() . 'User_login/users/editUsers/' . $id_user . '/' . $clientid);
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . 'User_login/users/editUsers/' . $id_user . '/' . $clientid);
                }
            }
        }
        echo view('templates/header_view', $data);
        echo view('users/usersad_edit_view', $data);
        echo view('templates/footer_view');
    }
    public function updateCategoryItem($clientid, $id_user = null)
    { // add userlevel name to user
        if ($response =  $this->requireRole(['6', '44'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['header_link'] = 'User_login/users';
        $data['form_link'] = 'User_login/users/editUsers/';
        $data['form_link_1'] = 'User_login/users/passeditUsers/';
        $data['form_link_2'] = 'User_login/users/updateCategoryItem/';
        $data['form_link_3'] = 'User_login/users/deleteUserCategory/';
        $data['form_link_4'] = 'User_login/users/updateCategory/';
        if ($this->request->getPost()) {
            $data['table'] = $this->dropdown_model->categoryDetails();
            $data['categoryData'] = $this->dropdown_model->getCategoryData();
            $data['clientData'] = $this->dropdown_model->getclientData();
            $data['userlevelData'] = $this->dropdown_model->getdropdownData(2);
            $userdata = $this->users_model->usersedit_view($id_user);
            $data['row'] = $userdata['0'];
            $data['clientUserlevelData'] = $this->users_model->clientUserlevel_view($id_user);
            $data['timezone'] = $this->login_model->gettimezonedata();
            $last_loginTime = $this->users_model->userActiveData($data['row']['username']);
            if (!$last_loginTime) {
                $data['lastLoginTime'] = ' ';
            } else {
                $data['lastLoginTime'] = date("M-d-Y H:i", $last_loginTime[0]['dateandtime']);
            }
            $rules = [
                'userlevelItem' => 'required'
            ];
            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
            } else {

                $timestamp = time();
                $newdata = [
                    'fk_id_user' => $id_user,
                    'fk_id_dc' => '2',
                    'fk_id_d' => $this->request->getVar('userlevelItem'),
                    'createdon' => $timestamp,
                    'createdby' => session()->get('id_user'),
                    'status' => '1',
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                //print_r($newdata);
                $result = $this->dropdown_model->updateCategoryItem($newdata);
                if ($result) {
                    session()->setFlashdata('success', $result);
                    return redirect()->to(base_url() . 'User_login/users/editUsers/' . $id_user . '/' . $clientid);
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . 'User_login/users/editUsers/' . $id_user . '/' . $clientid);
                }
            }
        }

        echo view('templates/header_view', $data);
        echo view('users/usersad_edit_view', $data);
        echo view('templates/footer_view');
    }
    public function deleteUserCategory($id_du, $id_user, $clientid)
    {
        //print_r("tt");
        if ($response =  $this->requireRole(['6', '44'])) {
            return $response;
        }
        $data = [];

        helper(['form']);
        $data['header_link'] = 'User_login/users';
        $data['form_link'] = 'User_login/users/editUsers/';
        $data['form_link_1'] = 'User_login/users/passeditUsers/';
        $data['form_link_2'] = 'User_login/users/updateCategoryItem/';
        $data['form_link_3'] = 'User_login/users/deleteUserCategory/';
        $data['form_link_4'] = 'User_login/users/updateCategory/';
        $uri = service('uri');
        // $id_user = $uri->getSegment(4);
        $sessionData = session();
        $timestamp = time();
        $newdata = [
            'id_du' => $id_du,


            'status' => '0',
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->users_model->deleteUserCategory($newdata, $id_du);
        if ($result) {
            $sessionData->setFlashdata('success', 'Category item : deleted Successful');
            return redirect()->to(base_url() . 'User_login/users/editUsers/' . $id_user . '/' . $clientid);
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'User_login/users/editUsers/' . $id_user . '/' . $clientid);
        }
    }
    function deleted_userslist()
    {
        if ($response =  $this->requireRole(['6', '44'])) {
            return $response;
        }
        $data['header_link'] = 'User_login/client_list';
        $data['header'] = 'Client List';
        $data['cid'] = $this->request->getVar('cid');
        $deleteUserData = $this->users_model->deleteUserlist($data['cid']);
        $data['deleteUserlist'] = $deleteUserData;
        echo view('templates/header_view', $data);
        echo view('users/deleted_users_view', $data);
        echo view('templates/footer_view');
    }
    function activateuser($id_user, $cid)
    {
        if ($response =  $this->requireRole(['6', '44'])) {
            return $response;
        }
        $result = $this->users_model->activateuser($id_user, $cid);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0048'));
            return redirect()->to(base_url() . 'users?cid=' . $cid);
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'users?cid=' . $cid);
        }
    }
    function client_settings()
    {
        if ($response =  $this->requireRole(['6', '44'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['clientData'] = $this->dropdown_model->clientuserlist();
        //print_r($data['clientData']);
        echo view('templates/header_view', $data);
        echo view('settings/client_setting_view', $data);
        echo view('templates/footer_view');
    }
    function usersdefaultDashbaordControl()
    {
        if ($response =  $this->requireRole(['6', '44'])) {
            return $response;
        }
        if (isset($_POST['icheck'])) {
            $data['icheck'] = $_POST['icheck'];
            $_SESSION['icheck'] = $data['icheck'];
        }
        $data['clientid'] = $_POST['client_id'];
        $data['id_user'] = $_POST['id_user'];
        $data['moduleaccess'] = $this->dropdown_model->getdropdownData(17);
        $result = $this->settings_model->giveusersdefaultdashboard($data['icheck'], $data['id_user']);
        if ($result) {
            $session = session();
            $session->setFlashdata('success', lang('Messages.Success_0008'));
            return redirect()->to(base_url() . 'User_login/users/editUsers/' . $data['id_user'] . '/' . $data['clientid']);
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'User_login/users/editUsers/' . $data['id_user'] . '/' . $data['clientid']);
        }
        echo view('templates/header_view', $data);
        echo view('users/usersad_edit_view', $data);
        echo view('templates/footer_view');
    }
}
