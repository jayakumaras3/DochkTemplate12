<?php

namespace App\Controllers\User_login;

use App\Controllers\BaseController;

use CodeIgniter\HTTP\RedirectResponse;
use App\Models\Dashboard\Dashboard_model;
use App\Models\Settings\Settings_model;
use App\Models\Settings\Dropdown_model;
use App\Models\User_login\Users_model;
use App\Models\Project_Manage\PM_pricing_sheet_model;
use App\Models\Payment\Billing_model;

#[\AllowDynamicProperties]
class Client_list extends BaseController
{

    protected Dashboard_model $dashboard_model;
    protected Settings_model $settings_model;
    protected Dropdown_model $dropdown_model;
    protected Users_model $users_model;
    protected PM_pricing_sheet_model $PM_pricing_sheet_model;
    protected Billing_model $billing_model;

    public function __construct()
    {
        $this->is_session_available();

        $this->dashboard_model = new Dashboard_model();
        $this->settings_model = new Settings_model();
        $this->dropdown_model = new Dropdown_model();
        $this->users_model = new Users_model();
        $this->PM_pricing_sheet_model = new PM_pricing_sheet_model();
        $this->billing_model = new Billing_model();
    }

    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url('my_training'));
            exit();
        }

        $arrayuserlevel = explode(',', $userlevel);
        if (!in_array('6', $arrayuserlevel) && !in_array('4', $arrayuserlevel)) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }

    public function index()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        // print_r($data['pr_id']);
        // exit();
        $data['header'] = 'Client';
        $data['add'] = 'Add New Client';
        $data = [
            'link1' => '',
            'link1_name' => '',
            'link2' => '',
            'link2_name' => '',
            'link3_name' => 'Clients'
        ];
        if (isset($_POST['pr_id'])) {
            $data['pr_id'] = $_POST['pr_id'];
        } else if (isset($_GET['pr_id'])) {
            $data['pr_id'] = $_GET['pr_id'];
        } elseif (session()->getFlashdata('pr_id')) {
            $data['pr_id'] = session()->getFlashdata('pr_id');
        } else {
            $data['pr_id'] = session()->get('partner_code');
        }


        $data['form_link1'] = 'SCORM/scorm_client/view_client_course_assigned';
        $data['form_link2'] = 'SCORM/Scorm_users';
        $data['partner_id'] = $data['pr_id'];
        $data['clientlist'] = $this->settings_model->clientuserlist($data['partner_id']);
        echo view('templates/header_view');
        // echo view('settings/admin_left_menu', $data);
        echo view('client/client_list_view', $data);
        echo view('templates/footer_view');
    }
    /* project managemnt */
    public function my_client_list()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data = [
            'link1' => 'my_training',
            'link1_name' => 'Dashboard',
            'link2' => '',
            'link2_name' => '',
            'link3_name' => 'Clients'
        ];
        if (isset($_POST['pr_id'])) {
            $data['pr_id'] = $_POST['pr_id'];
        } else if (isset($_GET['pr_id'])) {
            $data['pr_id'] = $_GET['pr_id'];
        } elseif (session()->getFlashdata('pr_id')) {
            $data['pr_id'] = session()->getFlashdata('pr_id');
        } else {
            $data['pr_id'] = session()->get('partner_code');
        }
        $data['partner_id'] = $data['pr_id'];
        $data['clientlist'] = $this->settings_model->get_my_client_list(session()->get('id_user'));
        echo view('templates/header_view', $data);
        // echo view('dashboard/projects_left_menu', $data);
        echo view('project_management/client_dashboard_view', $data);
        echo view('templates/footer_view');
    }
    function client_status()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        if (isset($_POST['id_c'])) {
            $data['id_c'] = $_POST['id_c'];
            $_SESSION['id_c'] = $data['id_c'];
        } else if (isset($_SESSION['id_c'])) {
            $data['id_c'] = $_SESSION['id_c'];
        } else {
            redirect()->to(base_url() . 'User_login/client_list/my_client_list');
        }
        $data['client_status'] = $this->settings_model->get_client_status($data['id_c']);
        echo view('templates/header_view');
        echo view('project_management/client_status_view', $data);
        echo view('templates/footer_view');
    }
    function add_client_view()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        // print_r($_POST);
        // exit();
        // $data['redirect_url']   = isset($_POST['redirect_url']) ? $_POST['redirect_url'] : 2;
        $redirect_url =  $this->request->getPost('redirect_url');
        if (isset($redirect_url)) {
            $data['redirect_url'] = $this->request->getPost('redirect_url');
            $_SESSION['redirect_url'] = $data['redirect_url'];
        } else if (isset($_SESSION['redirect_url'])) {
            $data['redirect_url'] = $_SESSION['redirect_url'];
        } elseif (session()->get('redirect_url')) {
            $data['redirect_url'] = session()->get('redirect_url');
        } else {
            $data['redirect_url'] = session()->get('redirect_url');
        }


        echo view('templates/header_view', $data);
        echo view('project_management/client_add', $data);
        echo view('templates/footer_view');
    }
    function add_client()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['pr_id'])) {
            $data['pr_id'] = $_POST['pr_id'];
        } else if (isset($_GET['pr_id'])) {
            $data['pr_id'] = $_GET['pr_id'];
        } elseif (session()->getFlashdata('pr_id')) {
            $data['pr_id'] = session()->getFlashdata('pr_id');
        } else {
            $data['pr_id'] = session()->get('partner_code');
        }
        $redirect_url = isset($_POST['redirect_url']) ? $_POST['redirect_url'] : 2;
        if ($this->request->getPost()) {
            $rulesData = [
                'client_name' => 'required|max_length[32]',

            ];
            if (!$this->validate($rulesData)) {
                $data['validation'] = $this->validator;
                $data['form_link1'] = 'SCORM/scorm_client/view_client_course_assigned';
                $data['form_link2'] = 'SCORM/Scorm_users';
                $data['partner_id'] = $data['pr_id'];
                $data['clientlist'] = $this->settings_model->clientuserlist($data['partner_id']);
                echo view('templates/header_view', $data);
                echo view('client/client_list_view', $data);
                echo view('templates/footer_view');
            } else {
                $client_name = $this->request->getVar('client_name');
                /*                 $client_fullname = $this->request->getVar('client_fullname');
                $descriptionData = $this->request->getVar('description');
                $description = isset($descriptionData) ? $descriptionData : '';
                $addressData = $this->request->getVar('address');
                $address = isset($addressData) ? $addressData : '';
                $locationData = $this->request->getVar('location');
                $location = isset($locationData) ? $locationData : ''; */

                $newdata = [
                    //'id_c' => $cid,
                    'client_name' => $client_name,
                    /*                'client_fullname' => $client_fullname,
                    'description' => $description,
                    'address' => $address,
                    'location' => $location, */
                    'partner_code' => $data['pr_id'],
                    'license' => 20,
                    'status' => 1,
                    'createdon' => time(),
                    'createdby' => session()->get('id_user'),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),

                ];
                $result = $this->settings_model->addClient($newdata);
                // print_r($data['pr_id']);
                // exit();
                if ($result) {
                    session()->setFlashdata('pr_id', $data['pr_id']);
                    session()->setFlashdata('success', lang('Messages.Success_0008'));
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
                if ($redirect_url == 1) {
                    return redirect()->to(base_url() . 'User_login/client_list');
                } else {
                    return redirect()->to(base_url() . 'User_login/client_list/my_client_list');
                }
            }
        }
    }
    public function client_edit_view()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['id_c'])) {
            $data['id_c'] = $_POST['id_c'];
        } else if (isset($_GET['id_c'])) {
            $data['id_c'] = $_GET['id_c'];
        } else if (isset($_SESSION['id_c'])) {
            $data['id_c'] = $_SESSION['id_c'];
        }
        // $data['id_c'] = session()->getFlashdata('id_c');

        $pr_id = session()->getFlashdata('pr_id');
        $data['pr_id'] = isset($_POST['pr_id']) ? $_POST['pr_id'] : $pr_id;
        $data['row'] = $this->settings_model->getclientuserlist($data['id_c']);
        $data['userdata'] = $this->PM_pricing_sheet_model->get_project_manager();
        $data['access'] = $this->PM_pricing_sheet_model->get_access_info($data['id_c'], 8);
        echo view('templates/header_view');
        echo view('project_management/client_edit', $data);
        echo view('templates/footer_view');
    }

    function edit_client()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        helper(['form']);
        if (isset($_POST['id_c'])) {
            $data['id_c'] = $_POST['id_c'];
        } else if (isset($_GET['id_c'])) {
            $data['id_c'] = $_GET['id_c'];
        }

        if ($this->request->getPost()) {
            $rulesData = [
                'client_name' => 'required|max_length[32]',

            ];
            if (!$this->validate($rulesData)) {
                $data['validation'] = $this->validator;
            } else {
                $client_name = $this->request->getVar('client_name');
                $client_fullname = $this->request->getVar('client_fullname');
                $descriptionData = $this->request->getVar('description');
                $description = isset($descriptionData) ? $descriptionData : '';
                $addressData = $this->request->getVar('address');
                $address = isset($addressData) ? $addressData : '';
                $locationData = $this->request->getVar('location');
                $location = isset($locationData) ? $locationData : '';

                $newdata = [
                    //'id_c' => $cid,
                    'client_name' => $client_name,
                    'client_fullname' => $client_fullname,
                    'description' => $description,
                    'address' => $address,
                    'location' => $location,
                    'status' => 1,
                    'createdon' => time(),
                    'createdby' => session()->get('id_user'),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),

                ];
                $result = $this->settings_model->editClientlist($newdata, $data['id_c']);
                // print_r($data['pr_id']);
                // exit();
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0008'));
                    return redirect()->to(base_url() . 'User_login/client_list/my_client_list');
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . 'User_login/client_list/my_client_list');
                }
            }
        }
    }
    function addusers_projects_assignment()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();
            if (isset($_POST['id_c'])) {
                $data['id_c'] = $this->request->getVar('id_c');
                $_SESSION['id_c'] = $data['id_c'];
            } else if (isset($_SESSION['id_c'])) {
                $data['id_c'] = $_SESSION['id_c'];
            }
            $id_user = $_POST['id_user'];
            $newdata = [
                'type_of_assignment' => 8,
                'db_id' => $data['id_c'],
                'user_id' => $id_user,
                'created_on' => $timestamp,
                'created_by' => session()->get('id_user'),
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user'),
                'status' => '1'
            ];

            $result = $this->PM_pricing_sheet_model->add_user_to_pricing($newdata);

            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0011'));
                return redirect()->to(base_url() . 'User_login/client_list/client_edit_view');
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'User_login/client_list/client_edit_view');
            }
        }
    }
    public function delete_userassignment()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        if ($this->request->getPost()) {
            $timestamp = time();
            if (isset($_POST['id_c'])) {
                $_SESSION['id_c'] = $_POST['id_c'];
            }
            $project_assign_id = $this->request->getVar('project_assign_id');
            $newdata = [
                'last_updated_on' => $timestamp,
                'last_updated_by' => session()->get('id_user'),
                'status' => 0
            ];

            $result = $this->PM_pricing_sheet_model->delete_userassigned($newdata, $project_assign_id);

            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0003'));
                return redirect()->to(base_url() . 'User_login/client_list/client_edit_view');
            } else {
                session()->setFlashdata('message', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
                return redirect()->to(base_url() . 'User_login/client_list/client_edit_view');
            }
        }
    }
    /* project managemnt */
    public function editclientlist()
    {
        // print_r($_POST);
        // exit();
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];

        // $cid = session()->getFlashdata('cid');
        // $pr_id = session()->getFlashdata('pr_id');
        if (isset($_POST['cid'])) {
            $data['cid'] = $_POST['cid'];
            $_SESSION['cid'] = $data['cid'];
        } else if (isset($_SESSION['cid'])) {
            $data['cid'] = $_SESSION['cid'];
        } elseif (session()->getFlashdata('cid')) {
            $data['cid'] = session()->getFlashdata('cid');
        } else {
            $data['cid'] = session()->get('id_c');
        }
        $data['id_c'] = $data['cid'];
        if (isset($_POST['pr_id'])) {
            $data['pr_id'] = $_POST['pr_id'];
            $_SESSION['pr_id'] = $data['pr_id'];
        } else if (isset($_SESSION['pr_id'])) {
            $data['pr_id'] = $_SESSION['pr_id'];
        } elseif (session()->getFlashdata('pr_id')) {
            $data['pr_id'] = session()->getFlashdata('pr_id');
        } else {
            $data['pr_id'] = session()->get('partner_code');
        }

        $data['moduleaccess'] = $this->dropdown_model->getdropdownData(17);
        $data['dashboardaccesstype'] = $this->dropdown_model->getdropdownData(16);
        $data['row'] = $this->settings_model->getclientuserlist($data['cid']);
        echo view('templates/header_view');
        echo view('client/client_editlist_view', $data);
        echo view('templates/footer_view');
    }
    public function saveEditClientlist()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url() . '/');
        } else {
            $data = [];
            helper(['form']);
            $data['cid'] = $_POST['cid'];
            $data['pr_id'] = $_POST['pr_id'];
            // print_r($data['pr_id']);
            $data['row'] = $this->settings_model->getclientuserlist($data['cid']);
            $data['dashboardaccesstype'] = $this->dropdown_model->getdropdownData(16);
            if ($this->request->getPost()) {
                $rulesData = [
                    'client_name' => 'required|max_length[32]',
                    'status' => 'required|max_length[10]',
                ];
                if (!$this->validate($rulesData)) {
                    $data['validation'] = $this->validator;
                } else {
                    $client_name = $this->request->getVar('client_name');
                    $client_fullname = $this->request->getVar('client_fullname');
                    $validity = $this->request->getVar('validity');
                    if ($validity == '') {
                        $validity = '0000-00-00';
                    } else {
                        $validity = date('Y-m-d', strtotime($validity));
                    }
                    $license = $this->request->getVar('license');

                    $start_date = $this->request->getVar('start_date');
                    if ($start_date == NULL) {
                        $start_date = NULL;
                    } else {
                        $start_date = date('Y-m-d', strtotime($start_date));
                    }
                    $end_date = $this->request->getVar('end_date');
                    if ($end_date == NULL) {
                        $end_date = NULL;
                    } else {
                        $end_date = date('Y-m-d', strtotime($end_date));
                    }
                    $courses_count = $this->request->getVar('course_count');
                    $url = $this->request->getVar('url');
                    $status = $this->request->getVar('status');
                    $type = $this->request->getVar('type');

                    $timestamp = time();
                    $newdata = [
                        //'id_c' => $cid,
                        'client_name' => $client_name,
                        'client_fullname' => $client_fullname,
                        'validity' => $validity,
                        'license' => $license,
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                        // 'courses_count' => $courses_count,
                        'url' => $url,
                        'type' => $type,
                        'status' => $status,
                        'createdon' => $timestamp,
                        'createdby' => session()->get('id_user'),
                        'last_updated_by' => session()->get('id_user'),
                        'last_updated_on' => time(),

                    ];
                    $result = $this->settings_model->editClientlist($newdata, $data['cid']);
                    // print_r($data['pr_id']);
                    // exit();
                    if ($result) {
                        session()->setFlashdata('pr_id', $data['pr_id']);
                        session()->setFlashdata('success', lang('Messages.Success_0008'));
                        return redirect()->to(base_url() . 'User_login/client_list');
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0003'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                        return redirect()->to(base_url() . 'User_login/client_list');
                    }
                }
            }
            echo view('templates/header_view');
            echo view('client/client_editlist_view', $data);
            echo view('templates/footer_view');
        }
    }
    function deleteClientlist($id_c)
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $newdata = ['id_c' => $id_c, 'status' => '0'];
        $result = $this->settings_model->deleteClientlist($id_c, $newdata);
        if ($result) {
            $sessionData = session();
            $sessionData->setFlashdata('success', 'Deleted Successful');
            return redirect()->to(base_url() . 'User_login/client_list')->with('success', 'Deleted Successful');
        }
        session()->setFlashdata('error', lang('Messages.Error_0001'));
        session()->setFlashdata('alert-class', 'alert-danger');
        return redirect()->to(base_url() . 'User_login/client_list');
    }
    function addclient()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if ($this->request->getPost()) {
            $rules = [
                'name' => 'required',
                'category' => 'required',
            ];
            $data['table'] = $this->dropdown_model->categoryDetails();
            $data['categoryData'] = $this->dropdown_model->getCategoryData();
            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
            } else {
                $timestamp = time();
                $newdata = [
                    'partner_code' => $this->request->getVar('partner_code'),
                    'fk_id_dc' => $this->request->getVar('category'),
                    'name' => $this->request->getVar('name'),
                    'createdon' => $timestamp,
                    'createdby' => session()->get('id_user'),
                    'status' => '1',
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                // print_r($newdata);
                // exit();
                $result = $this->dropdown_model->savecategoryItem($newdata);
                // print_r($result['insertID']);
                // exit();
                if ($result) {
                    session()->setFlashdata('cid', $result['insertID']);
                    session()->setFlashdata('success', lang('Messages.Success_0011'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . 'User_login/client_list/my_client_list');
                } else {
                    session()->setFlashdata('cid', $result['insertID']);
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . 'User_login/client_list/my_client_list');
                }
            }
        }
    }
    public function uploadClientlogo()
    {

        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['filesystem']);
        if (isset($_POST['cid'])) {
            $data['cid'] = $_POST['cid'];
            $_SESSION['cid'] = $data['cid'];
        } else if (isset($_GET['cid'])) {
            $data['cid'] = $_GET['cid'];
        } else if (isset($_SESSION['cid'])) {
            $data['cid'] = $_SESSION['cid'];
        }

        $data['row'] = $this->settings_model->getclientuserlist($data['cid']);
        $data['dashboardaccesstype'] = $this->dropdown_model->getdropdownData(16);
        if ($this->request->getPost()) {
            $rules = [
                'file' => 'uploaded[file]|ext_in[file,jpg,png]'
            ];
            if (!$this->validate($rules)) {
                $data['logovalidation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();
                        if (!is_dir(FCPATH . 'assets/assets/uploads/client_logo/' . $data['cid'])) {
                            mkdir('assets/assets/uploads/client_logo/' . $data['cid'], 0777, true);
                        }
                        if (file_exists(FCPATH . 'assets/assets/uploads/client_logo/' . $data['cid'] . "/" . $filename)) {
                            session()->setFlashdata('error', $filename . ' ' . lang('Messages.Success_0051'));
                        } else {
                            if ($file->move(FCPATH . 'assets/assets/uploads/client_logo/' . $data['cid'], $filename)) {
                                $filepath = FCPATH . 'assets/assets/uploads/client_logo/' . $data['cid'] . '/' . $filename;
                                $newdata = [
                                    'logo' => $filename,
                                ];
                                $result = $this->settings_model->updateclientlogo($newdata, $data['cid']);
                                if ($result) {
                                    session()->setFlashdata('id_c', $data['cid']);
                                    session()->setFlashdata('success', lang('Messages.Success_0009'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                    return redirect()->to(base_url() . 'User_login/client_list/editclientlist');
                                } else {
                                    session()->setFlashdata('id_c', $data['cid']);
                                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                    return redirect()->to(base_url() . 'User_login/client_list/editclientlist');
                                }
                            }
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                        return redirect()->to(base_url() . 'User_login/client_list/editclientlist');
                    }
                }
            }
        }
    }
    function uploadClientlogoDark()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        helper(['filesystem']);
        if (isset($_POST['cid'])) {
            $data['cid'] = $_POST['cid'];
            $_SESSION['cid'] = $data['cid'];
        } else if (isset($_GET['cid'])) {
            $data['cid'] = $_GET['cid'];
        } else if (isset($_SESSION['cid'])) {
            $data['cid'] = $_SESSION['cid'];
        }
        $data['row'] = $this->settings_model->getclientuserlist($data['cid']);
        $data['dashboardaccesstype'] = $this->dropdown_model->getdropdownData(16);
        if ($this->request->getPost()) {
            $rules = [
                'file' => 'uploaded[file]|ext_in[file,jpg,png]'
            ];
            if (!$this->validate($rules)) {
                $data['logovalidation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $filename = $file->getName();
                        // print_r($filename);
                        // exit();
                        if (!is_dir(FCPATH . 'assets/assets/uploads/client_logo/' . $data['cid'])) {
                            mkdir('assets/assets/uploads/client_logo/' . $data['cid'], 0777, true);
                        }
                        if (file_exists(FCPATH . 'assets/assets/uploads/client_logo/' . $data['cid'] . "/" . $filename)) {
                            session()->setFlashdata('error', $filename . ' ' . lang('Messages.Success_0051'));
                        } else {
                            if ($file->move(FCPATH . 'assets/assets/uploads/client_logo/' . $data['cid'], $filename)) {
                                $filepath = FCPATH . 'assets/assets/uploads/client_logo/' . $data['cid'] . '/' . $filename;
                                $newdata = [
                                    'logo_dark' => $filename,
                                ];
                                $result = $this->settings_model->updateclientlogo($newdata, $data['cid']);
                                if ($result) {
                                    session()->setFlashdata('id_c', $data['cid']);
                                    session()->setFlashdata('success', lang('Messages.Success_0009'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                    return redirect()->to(base_url() . 'User_login/client_list/editclientlist');
                                } else {
                                    session()->setFlashdata('id_c', $data['cid']);
                                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                    return redirect()->to(base_url() . 'User_login/client_list/editclientlist');
                                }
                            }
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                        return redirect()->to(base_url() . 'User_login/client_list/editclientlist');
                    }
                }
            }
        }
    }
    function updatehashvalclient()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $getclientdata = $this->dropdown_model->getclientData();
        foreach ($getclientdata as $eachclientdata) {
            $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
            $password = array();
            $alpha_length = strlen($alphabet) - 1;
            for ($i = 0; $i < 8; $i++) {
                $n = rand(0, $alpha_length);
                $password[] = $alphabet[$n];
            }

            $data['temppass'] = $this->generateTempPassword(12);
            $data = [
                'hash' => $data['temppass']
            ];
            $result = $this->dropdown_model->updateHashtoClient($data, $eachclientdata['id_c']);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0008'));
                session()->setFlashdata('alert-class', 'alert-danger');
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }
        }
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
    function updatehashvaluser()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $getuserdata = $this->dropdown_model->getusersdata();
        foreach ($getuserdata as $eachusertdata) {
            $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
            $password = array();
            $alpha_length = strlen($alphabet) - 1;
            for ($i = 0; $i < 5; $i++) {
                $n = rand(0, $alpha_length);
                $password[] = $alphabet[$n];
            }

            $data['temppass'] = $this->generateTempPassword(12);
            $data = [
                'hash' => $data['temppass']
            ];
            $result = $this->dropdown_model->updateHashtouser($data, $eachusertdata['id_user']);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0008'));
                session()->setFlashdata('alert-class', 'alert-danger');
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }
        }
    }
    public function partner()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        $data['header'] = 'User_login/Partners';
        $data['add'] = 'Add New Partner';
        if (isset($_POST['client'])) {
            $data['client'] = $_POST['client'];
        } else if (isset($_GET['client'])) {
            $data['client'] = $_GET['client'];
        } else {
            $data['client'] = session()->get('client');
        }
        $data['current_client'] = $data['client'];
        $data['clientlist'] = $this->settings_model->partneruserlist($data['current_client']);
        echo view('templates/header_view');
        echo view('client/client_list_view', $data);
        echo view('templates/footer_view');
    }
    function deleted_userslist()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data['header_link'] = 'User_login/client_users';
        $data['header'] = 'Users';
        if (isset($_POST['cid'])) {
            $data['cid'] = $_POST['cid'];
        } else if (isset($_GET['cid'])) {
            $data['cid'] = $_GET['cid'];
        } else {
            $data['cid'] = session()->get('client');
        }
        $deleteUserData = $this->users_model->deleteUserlist($data['cid']);
        $data['deleteUserlist'] = $deleteUserData;
        echo view('templates/header_view', $data);
        echo view('users/deleted_users_view', $data);
        echo view('templates/footer_view');
    }
    public function deleteUsers($id_user, $clientid)
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
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
    function getcountofCMLtoclient()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        $data['header_link'] = 'User_login/client_users';
        $data['header'] = 'Users';
        if (isset($_POST['cid'])) {
            $data['cid'] = $_POST['cid'];
        } else if (isset($_GET['cid'])) {
            $data['cid'] = $_GET['cid'];
        } else {
            $data['cid'] = session()->get('client');
        }

        $data['getCourseCount'] = $this->settings_model->getCourseCounttoClient($data['cid']);
        $data['getmarketCount'] = $this->settings_model->getmarketlearningtoClient($data['cid'], 1);
        $data['getlearningCount'] = $this->settings_model->getmarketlearningtoClient($data['cid'], 2);
        $data['purchasedCourses'] = $this->billing_model->getclientPurchasedCourses($data['cid']);
        echo view('templates/header_view', $data);
        echo view('client/client_info_view', $data);
        echo view('templates/footer_view');
    }
    function menu_settings()
    {
        if ($response =  $this->requireRole(['6', '44', '4'])) {
            return $response;
        }
        $data = [];
        if (isset($_POST['cid'])) {
            $data['cid'] = $_POST['cid'];
        } else if (isset($_GET['cid'])) {
            $data['cid'] = $_GET['cid'];
        } else {
            $data['cid'] = session()->get('client');
        }
        //  $data['menus'] = 
        echo view('templates/header_view', $data);
        echo view('client/menu_settings_view', $data);
        echo view('templates/footer_view');
    }
}
