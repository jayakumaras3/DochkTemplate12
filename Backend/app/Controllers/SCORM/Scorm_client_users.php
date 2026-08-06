<?php

namespace App\Controllers\SCORM;

use App\Controllers\BaseController;

use App\Models\User_login\Login_model;
use App\Models\Settings\Dropdown_model;
use App\Models\User_login\Users_model;
use CodeIgniter\I18n\Time;

#[\AllowDynamicProperties]
class Scorm_client_users extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->is_session_available();
        $this->login_model = new Login_model();
        $this->dropdown_model = new Dropdown_model();
        $this->users_model = new Users_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url('my_training'));
            exit();
        }

        $arrayuserlevel = explode(',', $userlevel);
        if (!in_array('6', $arrayuserlevel) && !in_array('73', $arrayuserlevel)) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }
    public function index() //fetch data from users table to display
    {
        if ($response =  $this->requireRole(['6', '44', '73'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $clientid = session()->get('client');
        $data['usertable'] = $this->login_model->users_view($clientid);
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_users/client_users_view', $data);
        echo view('templates/footer_view');
    }
    function clientregister()
    {
        if ($response =  $this->requireRole(['6', '44', '73'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $model = new Login_model();
        $data['clientid'] = base64_decode($this->request->getVar('cid'));
        $data['userlevelData'] = $this->dropdown_model->getdropdownData(2);
        $data['timezone'] = $this->login_model->gettimezonedata();
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_users/users_clientadd_view', $data);
        echo view('templates/footer_view');
    }
    function addclientregister()
    {
        if ($response =  $this->requireRole(['6', '44', '73'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $model = new Login_model();
        $data['clientid'] = $this->request->getVar('cid');
        $data['userlevelData'] = $this->dropdown_model->getdropdownData(2);
        $data['timezone'] = $this->login_model->gettimezonedata();
        if ($this->request->getPost()) {
            $rules = [
                'name' => 'required|min_length[1]|max_length[50]',
                'username' => 'required|min_length[3]|is_unique[users.username]',
                'email' => 'required|min_length[6]|max_length[80]|valid_email|is_unique[users.email]',
                'userlevelItem' => 'required',
                'password' => 'required|max_length[255]',
                'password_confirm' => 'matches[password]',
                'timezone' => 'required',
                'lang' => 'required'
            ];
            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
            } else {

                $timestamp = time();
                $designation = $this->request->getVar('designation');
                $designation = isset($designation) ? $designation : '';
                $newdata = [
                    'name' => $this->request->getVar('name'),
                    'username' => $this->request->getVar('username'),
                    'email' => $this->request->getVar('email'),
                    'designation' =>  $designation,
                    'password' => $this->request->getVar('password'),
                    'timezone' => $this->request->getVar('timezone'),
                    'lang' => $this->request->getVar('lang'),
                    'userid' => uniqid(),
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
                        $result = $this->dropdown_model->updateCategory($userdata); //add client in dropdown_users table
                    }
                    if ($this->request->getVar('userlevelItem')) {
                        $userleveldata['fk_id_user'] = $insertedid;
                        $userleveldata['fk_id_dc'] = 2;
                        $userleveldata['fk_id_d'] = $this->request->getVar('userlevelItem');
                        $userleveldata['status'] = 1;
                        $userleveldata['createdby'] = session()->get('id_user');
                        $userleveldata['createdon'] = time();
                        $result = $this->dropdown_model->updateCategory($userleveldata); //add roles in dropdown_users table
                    }
                    if ($result) {
                        $session = session();
                        $session->setFlashdata('success', 'Successful Registration');
                        return redirect()->to(base_url() . '/SCORM/Scorm_client_users');
                    }
                }
                $session = session();
                $session->setFlashdata('error', 'Data not found, Try again');
                return redirect()->to(base_url() . '/SCORM/Scorm_client_users');
            }
        }
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_users/users_clientadd_view', $data);
        echo view('templates/footer_view');
    }
    public function editUsers($id_user) // update data from users table to display
    {
        if ($response =  $this->requireRole(['6', '44', '73'])) {
            return $response;
        }
        $data = [];
        helper(['form']);

        $userencrptid = base64_decode($id_user);
        //$data['clientid'] = session()->get('client');
        $userdata = $this->users_model->usersedit_view($userencrptid);
        $data['table'] = $this->dropdown_model->categoryDetails();
        $data['categoryData'] = $this->dropdown_model->getCategoryData();
        $data['clientData'] = $this->dropdown_model->getclientData();
        $data['userlevelData'] = $this->dropdown_model->getdropdownData(2);
        $data['row'] = $userdata['0'];
        //print_r($data['row']);
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
                //'password' => 'required|max_length[255]',
                'email' => 'required|min_length[6]|max_length[50]|valid_email',
                //'designation' => 'required',
                'timezone' => 'required',

            ];
            if (!$this->validate($rules)) {
                $data['validationEditUsers'] = $this->validator;
            } else {
                $id_user  = $this->request->getVar('id_user');
                $newdata = [
                    'name' => $this->request->getVar('name'),
                    'email' => $this->request->getVar('email'),
                    //'password' => trim(password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)),
                    'designation' => $this->request->getVar('designation'),
                    'timezone' => $this->request->getVar('timezone'),
                    'last_updated_by' =>  session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                $result = $this->users_model->updateUsersData($userencrptid, $newdata);
                if ($result) {
                    $session = session();
                    $session->setFlashdata('success', lang('Messages.Success_0008'));
                    return redirect()->to(base_url() . 'SCORM/Scorm_client_users/editUsers/' . $id_user);
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . 'SCORM/Scorm_client_users/editUsers/' . $id_user);
                }
            }
        }
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_users/users_clientedit_view', $data);
        echo view('templates/footer_view', $data);
    }
    public function passeditUsers($id_user)
    {
        if ($response =  $this->requireRole(['6', '44', '73'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $userencrptid = base64_decode($id_user);
        //exit();
        //$data['clientid'] = session()->get('client');
        $userdata = $this->users_model->usersedit_view($userencrptid);
        $data['table'] = $this->dropdown_model->categoryDetails();
        $data['categoryData'] = $this->dropdown_model->getCategoryData();
        $data['clientData'] = $this->dropdown_model->getclientData();
        $data['userlevelData'] = $this->dropdown_model->getdropdownData(2);
        $data['row'] = $userdata['0'];
        //print_r($data['row']);
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
                    return redirect()->to(base_url() . '/SCORM/Scorm_client_users/editUsers/' . $id_user);
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . '/SCORM/Scorm_client_users/editUsers/' . $id_user);
                }
            }
        }
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_users/users_clientedit_view', $data);
        echo view('templates/footer_view', $data);
    }
    public function deleteUsers($id_user = null)
    {
        if ($response =  $this->requireRole(['6', '44', '73'])) {
            return $response;
        }
        $result = $this->users_model->deleteUser($id_user);
        if ($result) {
            $sessionData =  session();
            $sessionData->setFlashdata('success', 'Category item : deleted Successful');
            return redirect()->to(base_url() . '/SCORM/Scorm_client_users');
        }
        session()->setFlashdata('error', lang('Messages.Error_0001'));
        session()->setFlashdata('alert-class', 'alert-danger');
        return redirect()->route('SCORM/Scorm_client_users')->withInput();
    }

    public function updateCategory($id_user = null)
    { // add client name to user
        if ($response =  $this->requireRole(['6', '44', '73'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $userencrptid = base64_decode($id_user);
        if ($this->request->getPost()) {
            $data['table'] = $this->dropdown_model->categoryDetails();
            $data['categoryData'] = $this->dropdown_model->getCategoryData();
            $data['clientData'] = $this->dropdown_model->getclientData();
            $data['userlevelData'] = $this->dropdown_model->getdropdownData(2);
            $userdata = $this->users_model->usersedit_view($userencrptid);
            $data['clientUserlevelData'] = $this->users_model->clientUserlevel_view($userencrptid);
            $data['row'] = $userdata['0'];
            $data['timezone'] = $this->login_model->gettimezonedata();
            $last_loginTime = $this->users_model->userActiveData($data['row']['username']);
            if (!$last_loginTime) {
                $data['lastLoginTime'] = ' ';
            } else {
                $data['lastLoginTime'] =  date("M-d-Y H:i", $last_loginTime[0]['dateandtime']);
            }
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
                    'last_updated_by' =>  session()->get('id_user'),
                    'last_updated_on' => time(),
                    'status' => '1',
                ];
                //print_r($newdata);
                $result = $this->dropdown_model->updateCategory($newdata);
                if ($result) {
                    session()->setFlashdata('success', $result);
                    return redirect()->to(base_url() . '/SCORM/Scorm_client_users');
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . '/SCORM/Scorm_client_users');
                }
            }
        }
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_users/users_clientedit_view', $data);
        echo view('templates/footer_view');
    }
    public function updateCategoryItem($id_user = null)
    { // add userlevel name to user
        if ($response =  $this->requireRole(['6', '44', '73'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $userencrptid = base64_decode($id_user);
        if ($this->request->getPost()) {
            $data['table'] = $this->dropdown_model->categoryDetails();
            $data['categoryData'] = $this->dropdown_model->getCategoryData();
            $data['clientData'] = $this->dropdown_model->getclientData();
            $data['userlevelData'] = $this->dropdown_model->getdropdownData(2);
            $userdata = $this->users_model->usersedit_view($userencrptid);
            $data['row'] = $userdata['0'];
            $data['clientUserlevelData'] = $this->users_model->clientUserlevel_view($id_user);
            $data['timezone'] = $this->login_model->gettimezonedata();
            $last_loginTime = $this->users_model->userActiveData($data['row']['username']);
            if (!$last_loginTime) {
                $data['lastLoginTime'] = ' ';
            } else {
                $data['lastLoginTime'] =  date("M-d-Y H:i", $last_loginTime[0]['dateandtime']);
            }
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
                    'last_updated_by' =>  session()->get('id_user'),
                    'last_updated_on' => time(),
                    'status' => '1',
                ];
                //print_r($newdata);
                $result = $this->dropdown_model->updateCategoryItem($newdata);
                if ($result) {
                    session()->setFlashdata('success', $result);
                    return redirect()->to(base_url() . '/SCORM/Scorm_client_users/editUsers/' . $id_user);
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . '/SCORM/Scorm_client_users/editUsers/' . $id_user);
                }
            }
        }

        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_users/users_clientedit_view', $data);
        echo view('templates/footer_view');
    }
    public function deleteUserCategory($id_du, $id_user)
    {
        if ($response =  $this->requireRole(['6', '44', '73'])) {
            return $response;
        }
        $sessionData =  session();
        $timestamp = time();
        $newdata = [
            'id_du' => $id_du,
             
             
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => '0',
        ];
        $result = $this->users_model->deleteUserCategory($newdata, $id_du);
        if ($result) {
            $sessionData->setFlashdata('success', 'Category item : deleted Successful');
            return redirect()->to(base_url() . '/SCORM/Scorm_client_users');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . '/SCORM/Scorm_client_users');
        }
    }
    function deleted_userslist()
    {
        if ($response =  $this->requireRole(['6', '44', '73'])) {
            return $response;
        }
        $cid = $this->request->getVar('cid');
        $deleteUserData = $this->users_model->deleteUserlist($cid);
        $data['deleteUserlist'] = $deleteUserData;
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_users/deleted_users_view', $data);
        echo view('templates/footer_view');
    }
    function activateuser($id_user)
    {
        if ($response =  $this->requireRole(['6', '44', '73'])) {
            return $response;
        }
        $result = $this->users_model->activateuser($id_user);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0008'));
            return redirect()->to(base_url() . '/SCORM/Scorm_client_users');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . '/SCORM/Scorm_client_users');
        }
    }
    function client_settings()
    {
        if ($response =  $this->requireRole(['6', '44', '73'])) {
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
}
