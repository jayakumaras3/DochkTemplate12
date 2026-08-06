<?php

namespace App\Controllers\User_login;

use App\Controllers\BaseController;

use App\Models\User_login\Login_model;
use App\Models\Settings\Dropdown_model;
use App\Models\User_login\Users_model;
use App\Models\Settings\Settings_model;

#[\AllowDynamicProperties]
class Partner_users extends BaseController
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
    // private function is_session_available()
    // {
    //     $userlevel = session()->get('userlevel');
    //     if (empty($userlevel)) {
    //         header('Location:' . base_url('my_training'));
    //         exit();
    //     }

    //     $arrayuserlevel = explode(',', $userlevel);
    //     if (!in_array('5', $arrayuserlevel)) {
    //         session()->setFlashdata('message', lang('Messages.Error_0004'));
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
        $data['header'] = 'User_login/Partners';
        $data['main_header_link'] = 'User_login/client_list';
        $data['header_link'] = 'User_login/client_list/partner';
        $data['addusers'] = 'User_login/Partner_users/register_view';
        $data['register'] = 'User_login/users/register';
        $data['edit'] = 'User_login/users/editUsers';
        if (isset($_POST['cid'])) {
            $data['clientid'] = $_POST['cid'];
            $_SESSION['clientid'] =  $data['clientid'];
        } else if (isset($_GET['cid'])) {
            $data['clientid'] = $_GET['cid'];
            $_SESSION['clientid'] =  $data['clientid'];
        } else if (isset($_SESSION['clientid'])) {
            $data['clientid'] = $_SESSION['clientid'];
        } else {
            return redirect()->to(base_url('User_login/client_list'));
        }
        $data['usertable'] = $this->login_model->users_view($data['clientid']);
        //print_r($data['usertable']); 
        //$data['clientlicense'] = $this->settings_model->clientlicensedata($data['clientid']);
        echo view('templates/header_view', $data);
        echo view('users/users_view', $data);
        echo view('templates/footer_view');
    }
    public function register_view()
    {
        if ($response =  $this->requireRole(['6', '44'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['header'] = 'User_login/Partners';
        $data['header_link'] = 'User_login/Client_list';
        $data['sub_header'] = 'Users';
        $data['sub_header_link'] = 'User_login/Partner_users';
        $data['register'] = 'User_login/users/register';
        if (isset($_POST['cid'])) {
            $data['clientid'] = $_POST['cid'];
            $_SESSION['clientid'] =  $data['clientid'];
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
        if ($response =  $this->requireRole(['6', '44'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['header'] = 'User_login/Partners';
        $data['header_link'] = 'User_login/Client_list';
        $data['sub_header'] = 'Users';
        $data['sub_header_link'] = 'User_login/Partner_users';
        $data['register'] = 'User_login/users/register';
        $model = new Login_model();
        if (isset($_POST['cid'])) {
            $data['clientid'] = $_POST['cid'];
            $_SESSION['clientid'] =  $data['clientid'];
        } else if (isset($_GET['cid'])) {
            $data['clientid'] = $_GET['cid'];
        } else if (isset($_SESSION['clientid'])) {
            $data['clientid'] = $_SESSION['clientid'];
        } else {
            return redirect()->to(base_url('User_login/client_list'));
        }
        $data['userlevelData'] = $this->dropdown_model->getdropdownData(2);
        $data['timezone'] = $this->login_model->gettimezonedata();

        $data['temppass'] = $this->generateTempPassword(12);
        $getpartnercode =  $this->users_model->getpartnerdata($data['clientid']);
        if (empty($getpartnercode)) {
            $client_id = 0;
            $partner_id =  0;
            $partner_code =  0;
        } else {
            $client_id = $getpartnercode[0]['id_c'];
            $partner_id =  $getpartnercode[0]['partner_code'];
            $partner_code =  $getpartnercode[0]['code'];
        }
        // print_r($this->request->getPost());
        if ($this->request->getPost()) {
            $rules = [
                'name' => 'required|min_length[1]|max_length[32]',
                // 'last_name' => 'required|max_length[32]',
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

                'timezone' => 'required',
                'lang' => 'required'
            ];
            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
            } else {

                $timestamp = time();
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
                    'client_id' =>  $client_id,
                    'partner_id' =>  $partner_id,
                    'partner_code' => $partner_code,
                    'timestamp' => $timestamp,
                    'valid' => '1',
                    'createdby' =>  session()->get('id_user'),
                    'createdon' => time(),
                    'last_updated_by' =>  session()->get('id_user'),
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
    public function editUsers($id_user, $client) // update data from users table to display
    {
        if ($response =  $this->requireRole(['6', '44'])) {
            return $response;
        }
        $data = [];
        helper(['form']);

        $data['clientid'] = $client;
        $userencrptid = base64_decode($id_user);
        $userdata = $this->users_model->usersedit_view($userencrptid);
        $data['table'] = $this->dropdown_model->categoryDetails();
        $data['categoryData'] = $this->dropdown_model->getCategoryData();
        $data['clientData'] = $this->dropdown_model->getclientData();
        $data['userlevelData'] = $this->dropdown_model->getdropdownData(2);
        $data['row'] = $userdata['0'];
        // print_r($data['row']);
        // exit();
        $data['allusername'] = $this->users_model->allusername();
        $data['header_link'] = 'User_login/Partner_users';
        $data['form_link'] = 'User_login/Partner_users/editUsers/' . base64_encode($data['row']['id_user']) . '/' . $client;
        $data['form_link_1'] = 'User_login/Partner_users/passeditUsers/' . base64_encode($data['row']['id_user']) . '/' . $client;;
        $data['form_link_2'] = 'User_login/Partner_users/updateCategoryItem/' . base64_encode($data['row']['id_user']) . '/' . $client;;
        $data['form_link_3'] = 'User_login/Partner_users/deleteUserCategory/';
        $data['form_link_4'] = 'User_login/Partner_users/updateManger/' . $client . '/' . base64_encode($data['row']['id_user']);
        //print_r($data['row']);

        $data['temppass'] = $this->generateTempPassword(12);
        $data['clientUserlevelData'] = $this->users_model->clientUserlevel_view($userencrptid);
        //print_r($data['clientUserlevelData']);
        $data['timezone'] = $this->login_model->gettimezonedata();
        $last_loginTime = $this->users_model->userActiveData($data['row']['username']);
        if (!$last_loginTime) {
            $data['lastLoginTime'] = ' ';
        } else {
            $data['lastLoginTime'] =  date("M-d-Y H:i", $last_loginTime[0]['dateandtime']);
        }

        if ($this->request->getPost()) {
            $rules = [
                'name' => 'required|min_length[1]|max_length[32]',
                // 'last_name' => 'required|max_length[32]',
            ];
            if (!$this->validate($rules)) {
                $data['validationEditUsers'] = $this->validator;
            } else {
                $id_user  = $this->request->getVar('id_user');
                $newdata = [
                    'name' => $this->request->getVar('name'),
                    'last_name' => $this->request->getVar('last_name'),
                    'email' => $this->request->getVar('email'),
                    'timezone' => $this->request->getVar('timezone'),
                    'last_updated_by' =>  session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                $result = $this->users_model->updateUsersData($userencrptid, $newdata);
                if ($result) {
                    $session = session();
                    $session->setFlashdata('success', lang('Messages.Success_0008'));
                    return redirect()->to(base_url() . 'User_login/Partner_users/editUsers/' . $id_user . '/' . $client);
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . 'User_login/Partner_users/editUsers/' . $id_user . '/' . $client);
                }
            }
        }
        echo view('templates/header_view', $data);
        echo view('users/users_clientedit_view', $data);
        echo view('templates/footer_view', $data);
    }
    public function passeditUsers($id_user, $client)
    {
        if ($response =  $this->requireRole(['6', '44'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $userencrptid = base64_decode($id_user);
        $data['moduleaccess'] = $this->dropdown_model->getdropdownData(17);
        $data['menu_view'] = $this->settings_model->menu_view($client);
        $data['user_default_dashboard'] = $this->settings_model->userdefaultdashvalue($userencrptid);
        $data['default_dashboard'] = $this->settings_model->clientlicensedata($client);
        $userdata = $this->users_model->usersedit_view($userencrptid);
        $data['table'] = $this->dropdown_model->categoryDetails();
        $data['categoryData'] = $this->dropdown_model->getCategoryData();
        $data['clientData'] = $this->dropdown_model->getclientData();
        $data['userlevelData'] = $this->dropdown_model->getdropdownData(2);
        $data['row'] = $userdata['0'];

        $data['allusername'] = $this->users_model->allusername();
        $data['header_link'] = 'User_login/Partner_users';
        $data['form_link'] = 'User_login/Partner_users/editUsers/';
        $data['form_link_1'] = 'User_login/Partner_users/passeditUsers/';
        $data['form_link_2'] = 'User_login/Partner_users/updateCategoryItem/';
        $data['form_link_3'] = 'User_login/Partner_users/deleteUserCategory/';
        $data['clientUserlevelData'] = $this->users_model->clientUserlevel_view($userencrptid);
        //print_r($data['clientUserlevelData']);
        $data['timezone'] = $this->login_model->gettimezonedata();
        $last_loginTime = $this->users_model->userActiveData($data['row']['username']);
        if (!$last_loginTime) {
            $data['lastLoginTime'] = ' ';
        } else {
            $data['lastLoginTime'] =  date("M-d-Y H:i", $last_loginTime[0]['dateandtime']);
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
                $id_user  = $this->request->getVar('id_user');
                $newdata = [
                    'password' => trim(password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)),
                ];
                $result = $this->users_model->updateUsersData($userencrptid, $newdata);
                if ($result) {
                    $session = session();
                    $session->setFlashdata('passsuccess', lang('Messages.Success_0008'));
                    return redirect()->to(base_url() . 'User_login/Partner_users/editUsers/' . $id_user . '/' . $client);
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . 'User_login/Partner_users/editUsers/' . $id_user . '/' . $client);
                }
            }
        }
        echo view('templates/header_view', $data);
        echo view('users/users_clientedit_view', $data);
        echo view('templates/footer_view', $data);
    }
    public function deleteUsers($id_user = null)
    {
        if ($response =  $this->requireRole(['6', '44'])) {
            return $response;
        }
        $result = $this->users_model->deleteUser($id_user);
        if ($result) {
            $sessionData =  session();
            $sessionData->setFlashdata('success', 'Category item : deleted Successful');
            return redirect()->to(base_url() . 'User_login/Partner_users');
        }
        session()->setFlashdata('error', lang('Messages.Error_0001'));
        session()->setFlashdata('alert-class', 'alert-danger');
        return redirect()->route('User_login/Partner_users')->withInput();
    }
    public function activiateusers($id_user = null)
    {
        if ($response =  $this->requireRole(['6', '44'])) {
            return $response;
        }
        $result = $this->users_model->Activiateuser($id_user);
        if ($result) {
            $sessionData =  session();
            $sessionData->setFlashdata('success', 'Category item : deleted Successful');
            return redirect()->to(base_url() . 'User_login/client_users');
        }
        session()->setFlashdata('error', lang('Messages.Error_0001'));
        session()->setFlashdata('alert-class', 'alert-danger');
        return redirect()->route('User_login/client_users')->withInput();
    }
    public function updateCategory($id_user = null)
    { // add client name to user
        if ($response =  $this->requireRole(['6', '44'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
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
                    'last_updated_by' =>  session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                //print_r($newdata);
                $result = $this->dropdown_model->updateCategory($newdata);
                if ($result) {
                    session()->setFlashdata('success', $result);
                    return redirect()->to(base_url() . 'User_login/Partner_users');
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . 'User_login/Partner_users');
                }
            }
        }
        echo view('templates/header_view', $data);
        echo view('users/users_clientedit_view', $data);
        echo view('templates/footer_view');
    }
    public function updateCategoryItem($client, $id_user = null)
    { // add userlevel name to user
        if ($response =  $this->requireRole(['6', '44'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $userencrptid = base64_decode($id_user);
        if ($this->request->getPost()) {
            $rules = [
                'userlevelItem' => 'required'
            ];

            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
            } else {

                $timestamp = time();
                $newdata = [
                    'fk_id_user' => $userencrptid,
                    'fk_id_dc' => '2',
                    'fk_id_d' => $this->request->getVar('userlevelItem'),
                    'createdon' => $timestamp,
                    'createdby' => session()->get('id_user'),
                    'status' => '1',
                    'last_updated_by' =>  session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                //print_r($newdata);
                $result = $this->dropdown_model->updateCategoryItem($newdata);
                if ($result) {
                    session()->setFlashdata('success', $result);
                    return redirect()->to(base_url() . 'User_login/Partner_users/editUsers/' . $id_user . '/' . $client);
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . 'User_login/Partner_users/editUsers/' . $id_user . '/' . $client);
                }
            }
        }

        echo view('templates/header_view', $data);
        echo view('users/users_clientedit_view', $data);
        echo view('templates/footer_view');
    }
    public function updateManger($client, $id_user = null)
    {
        if ($response =  $this->requireRole(['6', '44'])) {
            return $response;
        }
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
                    'last_updated_by' =>  session()->get('id_user'),
                    'last_updated_on' => time(),
                ];

                $result = $this->users_model->updateManager($newdata, $userencrptid);
                // print_r($result);
                // exit();
                if ($result) {
                    session()->setFlashdata('success', $result);
                    return redirect()->to(base_url() . 'User_login/Partner_users/editUsers/' . $id_user . '/' . $client);
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . 'User_login/Partner_users/editUsers/' . $id_user . '/' . $client);
                }
            }
        }

        echo view('templates/header_view', $data);
        echo view('users/users_clientedit_view', $data);
        echo view('templates/footer_view');
    }
    public function deleteUserCategory($id_du, $id_user, $client)
    {
        if ($response =  $this->requireRole(['6', '44'])) {
            return $response;
        }
        $sessionData =  session();
        $timestamp = time();
        $newdata = [
            'id_du' => $id_du,
             
             
            'status' => '0',
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->users_model->deleteUserCategory($newdata, $id_du);
        if ($result) {
            $sessionData->setFlashdata('success', 'Category item : deleted Successful');
            return redirect()->to(base_url() . 'User_login/Partner_users/editUsers/' . base64_encode($id_user) . '/' . $client);
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'User_login/Partner_users/editUsers/' . base64_encode($id_user) . '/' . $client);
        }
    }
    function deleted_userslist()
    {
        if ($response =  $this->requireRole(['6', '44'])) {
            return $response;
        }
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
        $result = $this->users_model->activateuser($id_user);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0048'));
            return redirect()->to(base_url() . 'User_login/Partner_users?cid=' . $cid);
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'User_login/Partner_users??cid=' . $cid);
        }
    }
}
