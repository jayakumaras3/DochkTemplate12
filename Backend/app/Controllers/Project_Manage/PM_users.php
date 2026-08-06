<?php

namespace App\Controllers\Project_Manage;

use App\Controllers\BaseController;

use App\Models\User_login\Login_model;
use App\Models\Settings\Dropdown_model;
use App\Models\User_login\Users_model;
use App\Models\Settings\Settings_model;

#[\AllowDynamicProperties]
class PM_users extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->is_session_available();
        $this->login_model = new Login_model();
        $this->dropdown_model = new Dropdown_model();
        $this->users_model = new Users_model();
        $this->settings_model = new Settings_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url('my_training'));
            exit();
        }

        $arrayuserlevel = explode(',', $userlevel);
        if (!in_array('4', $arrayuserlevel) && !in_array('44', $arrayuserlevel)) {
            session()->setFlashdata('message', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }
    public function index() //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'Project_Manage';
        $data['main_header_link'] = 'User_login/client_list/my_client_list';
        $data['addusers'] = 'Project_Manage/PM_users/register_view';
        $data['edit'] = 'Project_Manage/PM_users/editUsers';
        if (isset($_POST['cid'])) {
            $data['clientid'] = $_POST['cid'];
            $_SESSION['clientid'] = $data['clientid'];
        } else if (isset($_GET['cid'])) {
            $data['clientid'] = $_GET['cid'];
            $_SESSION['clientid'] = $data['clientid'];
        } else if (isset($_SESSION['clientid'])) {
            $data['clientid'] = $_SESSION['clientid'];
        } else {
            return redirect()->to(base_url('User_login/client_list/my_client_list'));
        }
        $data['usertable'] = $this->login_model->users_view($data['clientid']);
        //print_r($data['usertable']);
        $data['clientlicense'] = $this->settings_model->clientlicensedata($data['clientid']);
        echo view('templates/header_view', $data);
        echo view('users/users_view', $data);
        echo view('templates/footer_view');
    }
    public function register_view()
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'Project_Manage';
        $data['header_link'] = 'User_login/client_list/my_client_list';
        $data['sub_header'] = 'Users';
        $data['sub_header_link'] = 'Project_Manage/PM_users';
        $data['register'] = 'Project_Manage/PM_users/register';
        //'User_login/users/register';
        if (isset($_POST['cid'])) {
            $data['clientid'] = $_POST['cid'];
            $_SESSION['clientid'] = $data['clientid'];
        } else if (isset($_GET['cid'])) {
            $data['clientid'] = $_GET['cid'];
        } else if (isset($_SESSION['clientid'])) {
            $data['clientid'] = $_SESSION['clientid'];
        } else {
            return redirect()->to(base_url('User_login/client_list/my_client_list'));
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
    public function register()
    {
        $data = [];
        helper(['form']);
        $data['header'] = 'Project_Manage';
        $data['header_link'] = 'User_login/client_list/my_client_list';
        $data['sub_header'] = 'Users';
        $data['sub_header_link'] = 'Project_Manage/PM_users';
        $data['register'] = 'Project_Manage/PM_users/register';
        $model = new Login_model();
        if (isset($_POST['cid'])) {
            $data['clientid'] = $_POST['cid'];
            $_SESSION['clientid'] = $data['clientid'];
        } else if (isset($_GET['cid'])) {
            $data['clientid'] = $_GET['cid'];
        } else if (isset($_SESSION['clientid'])) {
            $data['clientid'] = $_SESSION['clientid'];
        } else {
            return redirect()->to(base_url('User_login/client_list/my_client_list'));
        }
        // print_r($this->request->getVar('cid'));
        // exit();
        $data['userlevelData'] = $this->dropdown_model->getdropdownData(2);
        $data['timezone'] = $this->login_model->gettimezonedata();
       
        $data['temppass'] = $this->generateTempPassword(12);
        $getpartnercode = $this->users_model->getpartnerdata($data['clientid']);
        if (empty($getpartnercode)) {
            $client_id = 0;
            $partner_id = 0;
            $partner_code = 0;
        } else {
            $client_id = $getpartnercode[0]['id_c'];
            $partner_id = $getpartnercode[0]['partner_code'];
            $partner_code = $getpartnercode[0]['code'];
        }
        // print_r($this->request->getPost());
        if ($this->request->getPost()) {
            $rules = [
                'name' => 'required|min_length[1]|max_length[32]',
                // 'last_name' => 'required|max_length[32]',
                'email' => 'required|min_length[6]|max_length[50]|valid_email|is_unique[users.email]|regex_match[/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/]',
                //'designation' => 'required',
                'userlevelItem' => 'required',
                'password' => 'required|min_length[8]|max_length[255]',

                'timezone' => 'required',
                'lang' => 'required'
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

                $newdata = [
                    'name' => $this->request->getVar('name'),
                    'last_name' => $this->request->getVar('last_name'),
                    'username' => $this->request->getVar('email'),
                    'email' => $this->request->getVar('email'),
                    //  'designation' => $designation,
                    'password' => trim(password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)),
                    'timezone' => $this->request->getVar('timezone'),
                    'lang' => $this->request->getVar('lang'),
                    'userid' => uniqid(),
                    'hash' => $data['temppass'],
                    'client_id' => $client_id,
                    'partner_id' => $partner_id,
                    'partner_code' => $partner_code,
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
                        $accessLevels = [3, 45]; // example access level IDs
                        foreach ($accessLevels as $level) {
                            $userleveldata = [];
                            $userleveldata['fk_id_user'] = $insertedid;
                            $userleveldata['fk_id_dc'] = 2;
                            $userleveldata['fk_id_d'] = $level;
                            $userleveldata['status'] = 1;
                            $userleveldata['createdby'] = session()->get('id_user');
                            $userleveldata['createdon'] = time();

                            $this->dropdown_model->updateCategory($userleveldata);
                        }
                    }
                    if ($result) {
                        $session = session();
                        $session->setFlashdata('success', 'Successful Registration');
                        return redirect()->to(base_url() . 'Project_Manage/PM_users');
                    }
                }
                $session = session();
                $session->setFlashdata('error', 'Data not found, Try again');
                return redirect()->to(base_url() . 'Project_Manage/PM_users');
            }
        }
        echo view('templates/header_view', $data);
        echo view('users/users_add_view', $data);
        echo view('templates/footer_view');
    }
    public function editUsers($id_user = null, $clientid = null) // update data from users table to display
    {
        $data = [];
        if (empty($id_user) && empty($clientid)) {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            return redirect()->to(base_url('Project_Manage/PM_users'));
        } else {
            helper(['form']);
            $data['header_link'] = 'Project_Manage/PM_users';
            $data['form_link'] = 'Project_Manage/PM_users/editUsers/';
            $data['form_link_1'] = 'Project_Manage/PM_users/passeditUsers/';
            $data['form_link_2'] = 'Project_Manage/PM_users/updateCategoryItem/';
            $data['form_link_3'] = 'Project_Manage/PM_users/deleteUserCategory/';
            $data['form_link_4'] = 'Project_Manage/PM_users/updateCategory/';
            $data['clientid'] = $clientid;
            $userdata = $this->users_model->usersedit_view($id_user);
            $data['table'] = $this->dropdown_model->categoryDetails();
            $data['categoryData'] = $this->dropdown_model->getCategoryData();
            $data['clientData'] = $this->dropdown_model->getclientData();
            $data['userlevelData'] = $this->dropdown_model->getdropdownData(2);
            $data['row'] = $userdata['0'];
            $data['clientUserlevelData'] = $this->users_model->clientUserlevel_view($id_user);
            $data['timezone'] = $this->login_model->gettimezonedata();
            $last_loginTime = $this->users_model->userActiveData($data['row']['username']);
            $data['moduleaccess'] = $this->dropdown_model->getdropdownData(17);
            $data['menu_view'] = $this->settings_model->menu_view($clientid);
            $data['user_default_dashboard'] = $this->settings_model->userdefaultdashvalue($id_user);
            $data['default_dashboard'] = $this->settings_model->clientlicensedata($clientid);
            $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
            $password = array();
            $alpha_length = strlen($alphabet) - 1;
            for ($i = 0; $i < 8; $i++) {
                $n = rand(0, $alpha_length);
                $password[] = $alphabet[$n];
            }
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
                'username' => 'required|max_length[50]|is_unique[users.username,id_user,' . $userencrptid . ']',
            ];
                if (!$this->validate($rules)) {
                    $data['validationEditUsers'] = $this->validator;
                } else {
                    $id_user = $this->request->getVar('id_user');
                    $last_name = $this->request->getVar('last_name');
                    $last_name = isset($last_name) ? $last_name : '';
                    $newdata = [
                        'name' => $this->request->getVar('name'),
                        'last_name' => $last_name,
                        'email' => $this->request->getVar('email'),
                        // 'username' => $this->request->getVar('email'),
                        'password' => trim(password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)),
                        'timezone' => $this->request->getVar('timezone'),
                        'last_updated_by' => session()->get('id_user'),
                        'last_updated_on' => time(),
                        // 'designation' => $this->request->getVar('designation')
                    ];
                    $result = $this->users_model->updateUsersData($id_user, $newdata);
                    if ($result) {
                        $session = session();
                        $session->setFlashdata('success', lang('Messages.Success_0008'));
                        return redirect()->to(base_url() . 'Project_Manage/PM_users/editUsers/' . $id_user . '/' . $clientid);
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0003'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                        return redirect()->route('Project_Manage/PM_users/editUsers/' . $id_user . '/' . $clientid)->withInput();
                    }
                }
            }
            echo view('templates/header_view', $data);
            echo view('users/usersad_edit_view', $data);
            echo view('templates/footer_view', $data);
        }
    }
    public function passeditUsers($id_user, $client)
    {
        $data = [];
        helper(['form']);
        // $userencrptid = base64_decode($id_user);
        $data['moduleaccess'] = $this->dropdown_model->getdropdownData(17);
        $data['menu_view'] = $this->settings_model->menu_view($client);
        $data['user_default_dashboard'] = $this->settings_model->userdefaultdashvalue($id_user);
        $data['default_dashboard'] = $this->settings_model->clientlicensedata($client);
        $userdata = $this->users_model->usersedit_view($id_user);
        $data['table'] = $this->dropdown_model->categoryDetails();
        $data['categoryData'] = $this->dropdown_model->getCategoryData();
        $data['clientData'] = $this->dropdown_model->getclientData();
        $data['userlevelData'] = $this->dropdown_model->getdropdownData(2);
        $data['row'] = $userdata['0'];

        // $data['allusername'] = $this->users_model->allusername();
        $data['header_link'] = 'Project_Manage/PM_users';
        $data['form_link'] = 'Project_Manage/PM_users/editUsers/';
        $data['form_link_1'] = 'Project_Manage/PM_users/passeditUsers/';
        $data['form_link_2'] = 'Project_Manage/PM_users/updateCategoryItem/';
        $data['form_link_3'] = 'Project_Manage/PM_users/deleteUserCategory/';
        $data['form_link_4'] = 'Project_Manage/PM_users/updateCategory/';
        $data['clientUserlevelData'] = $this->users_model->clientUserlevel_view($id_user);
        //print_r($data['clientUserlevelData']);
        $data['timezone'] = $this->login_model->gettimezonedata();
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $password = array();
        $alpha_length = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alpha_length);
            $password[] = $alphabet[$n];
        }
        $data['temppass'] = $this->generateTempPassword(12);
        $last_loginTime = $this->users_model->userActiveData($data['row']['username']);
        if (!$last_loginTime) {
            $data['lastLoginTime'] = ' ';
        } else {
            $data['lastLoginTime'] = date("M-d-Y H:i", $last_loginTime[0]['dateandtime']);
        }

        if (!$last_loginTime) {
            $data['lastLoginTime'] = ' ';
        } else {
            $data['lastLoginTime'] = date("M-d-Y H:i", $last_loginTime[0]['dateandtime']);
        }
        if ($this->request->getPost()) {
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
                $data['validationpassEditUsers'] = $this->validator;
            } else {
                $id_user = $this->request->getVar('id_user');
                $newdata = [
                    'password' => trim(password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)),
                ];
                $result = $this->users_model->updateUsersData($id_user, $newdata);
                if ($result) {
                    $session = session();
                    $session->setFlashdata('passsuccess', lang('Messages.Success_0008'));
                    return redirect()->to(base_url() . 'Project_Manage/PM_users/editUsers/' . $id_user . '/' . $client);
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . 'Project_Manage/PM_users/editUsers/' . $id_user . '/' . $client);
                }
            }
        }
        echo view('templates/header_view', $data);
        echo view('users/users_clientedit_view', $data);
        echo view('templates/footer_view', $data);
    }
    public function deleteUsers($id_user = null)
    {
        $result = $this->users_model->deleteUser($id_user);
        if ($result) {
            $sessionData = session();
            $sessionData->setFlashdata('success', 'Category item : deleted Successful');
            return redirect()->to(base_url() . 'Project_Manage/PM_users');
        }
        session()->setFlashdata('error', lang('Messages.Error_0001'));
        session()->setFlashdata('alert-class', 'alert-danger');
        return redirect()->route('Project_Manage/PM_users')->withInput();
    }
    public function activiateusers($id_user = null)
    {
        $result = $this->users_model->Activiateuser($id_user);
        if ($result) {
            $sessionData = session();
            $sessionData->setFlashdata('success', 'Category item : deleted Successful');
            return redirect()->to(base_url() . 'User_login/client_users');
        }
        session()->setFlashdata('error', lang('Messages.Error_0001'));
        session()->setFlashdata('alert-class', 'alert-danger');
        return redirect()->route('User_login/client_users')->withInput();
    }
    public function updateCategory($id_user = null)
    { // add client name to user
        $data = [];
        helper(['form']);
        $data['header_link'] = 'Project_Manage/PM_users';
        $data['form_link'] = 'Project_Manage/PM_users/editUsers/';
        $data['form_link_1'] = 'Project_Manage/PM_users/passeditUsers/';
        $data['form_link_2'] = 'Project_Manage/PM_users/updateCategoryItem/';
        $data['form_link_3'] = 'Project_Manage/PM_users/deleteUserCategory/';
        $data['form_link_4'] = 'Project_Manage/PM_users/updateCategory/';
        $userencrptid = base64_decode($id_user);
        if ($this->request->getPost()) {
            $rulesData = [
                'clientItem' => 'required'
            ];
            if (!$this->validate($rulesData)) {
                $data['validationData'] = $this->validator;
            } else {
                $timestamp = time();
                $newdata = [
                    'fk_id_user' => $userencrptid,
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
                if ($result) {
                    session()->setFlashdata('success', $result);
                    return redirect()->to(base_url() . 'Project_Manage/PM_users');
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . 'Project_Manage/PM_users');
                }
            }
        }
        echo view('templates/header_view', $data);
        echo view('users/users_clientedit_view', $data);
        echo view('templates/footer_view');
    }
    public function updateCategoryItem($client, $id_user = null)
    { // add userlevel name to user
        $data = [];
        helper(['form']);
        $data['header_link'] = 'Project_Manage/PM_users';
        $data['form_link'] = 'Project_Manage/PM_users/editUsers/';
        $data['form_link_1'] = 'Project_Manage/PM_users/passeditUsers/';
        $data['form_link_2'] = 'Project_Manage/PM_users/updateCategoryItem/';
        $data['form_link_3'] = 'Project_Manage/PM_users/deleteUserCategory/';
        $data['form_link_4'] = 'Project_Manage/PM_users/updateCategory/';
        if ($this->request->getPost()) {
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
                    return redirect()->to(base_url() . 'Project_Manage/PM_users/editUsers/' . $id_user . '/' . $client);
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . 'Project_Manage/PM_users/editUsers/' . $id_user . '/' . $client);
                }
            }
        }

        echo view('templates/header_view', $data);
        echo view('users/users_clientedit_view', $data);
        echo view('templates/footer_view');
    }
    public function updateManger($client, $id_user = null)
    {
        $data = [];
        helper(['form']);
        $userencrptid = base64_decode($id_user);
        $data['allusername'] = $this->users_model->allusername();

        if ($this->request->getPost()) {
            $rules = [
                'manager' => 'required'
            ];
            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
            } else {

                $timestamp = time();
                $newdata = [
                    'manager' => $this->request->getVar('manager'),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];

                $result = $this->users_model->updateManager($newdata, $userencrptid);
                // print_r($result);
                // exit();
                if ($result) {
                    session()->setFlashdata('success', $result);
                    return redirect()->to(base_url() . 'Project_Manage/PM_users/editUsers/' . $id_user . '/' . $client);
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . 'Project_Manage/PM_users/editUsers/' . $id_user . '/' . $client);
                }
            }
        }

        echo view('templates/header_view', $data);
        echo view('users/users_clientedit_view', $data);
        echo view('templates/footer_view');
    }
    public function deleteUserCategory($id_du, $id_user, $client)
    {
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
            return redirect()->to(base_url() . 'Project_Manage/PM_users/editUsers/' . $id_user . '/' . $client);
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'Project_Manage/PM_users/editUsers/' . $id_user . '/' . $client);
        }
    }
    function deleted_userslist()
    {
        $data['cid'] = $this->request->getVar('cid');
        $deleteUserData = $this->users_model->deleteUserlist($data['cid']);
        $data['deleteUserlist'] = $deleteUserData;
        echo view('templates/header_view', $data);
        echo view('users/deleted_users_view', $data);
        echo view('templates/footer_view');
    }
    function activateuser($id_user, $cid)
    {
        $result = $this->users_model->activateuser($id_user);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0008'));
            return redirect()->to(base_url() . 'Project_Manage/PM_users?cid=' . $cid);
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'Project_Manage/PM_users?cid=' . $cid);
        }
    }
}
