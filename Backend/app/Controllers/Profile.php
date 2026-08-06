<?php

namespace App\Controllers;

use App\Models\User_login\Login_model;
use App\Models\Settings\Dropdown_model;
use App\Models\User_login\Users_model;

#[\AllowDynamicProperties]
class Profile extends BaseController
{
    private $db;

    public function __construct()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $this->login_model = new Login_model();
        $this->dropdown_model = new Dropdown_model();
        $this->users_model = new Users_model();
    }
    public function index() //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        $id_user = session()->get('id_user');
        $data['client'] = session()->get('client');
        $username = session()->get('username');
        $last_loginTime = $this->users_model->userActiveData($username);
        $data['personal_data'] = $this->users_model->get_personal_data($id_user);

        $data['userdata'] = $this->users_model->usersedit_view($id_user);

        if (!$last_loginTime) {
            $data['lastLoginTime'] = ' ';
        } else {
            $dateval = $last_loginTime['0']['dateandtime'];
            $newdate = $dateval - 34200;
            $date = date('m-d-Y', $dateval);
            $data['lastLoginTime'] = $date;
        }

        echo view('templates/header_view', $data);
        echo view('profile/profile_view', $data);
        echo view('templates/footer_view');
    }

    public function update_data()
    {
        $data = [];
        helper(['form']);

        if (!isset($_SESSION['pannumber'])) {
            session()->setFlashdata('error', 'Please contact admin.');
            return redirect()->to(base_url() . 'etrack/employee_details');
        }

        $id_user = session()->get('id_user');
        //  $data['doc_data'] = $this->users_model->get_doc_user($id_user);
        $data['personal_data'] = $this->users_model->get_personal_data($id_user);
        if (!isset($data['personal_data'])) {
            $newdata = [
                'userid' => $id_user,
                'status' => 1,
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time()
            ];
            $this->users_model->add_personal_data_user($newdata);
            $data['personal_data'] = $this->users_model->get_personal_data($id_user);
        }

        echo view('templates/header_view', $data);
        echo view('profile/profile_data_update_view', $data);
        echo view('templates/footer_view');
    }

    public function documents()
    {
        $data = [];
        helper(['form']);
        if (!isset($_SESSION['pannumber'])) {
            session()->setFlashdata('error', 'Please contact admin.');
            return redirect()->to(base_url() . 'etrack/employee_details');
        }
        $id_user = session()->get('id_user');
        $data['doc_data'] = $this->users_model->get_doc_user($id_user);
        echo view('templates/header_view', $data);
        echo view('profile/profile_documents_view', $data);
        echo view('templates/footer_view');
    }

    public function change_landing_page()
    {
        $id_user = session()->get('id_user');
        $newdata = [
            'landing_page' => $_POST['landing_page'],
            'banner_color' => $_POST['banner_color'],
            'theme_color' => $_POST['theme_color']
        ];
        $this->users_model->updateUsersData($id_user, $newdata);
        session()->setFlashdata('success', 'Data updated. Effective from next login.');
        return redirect()->to(base_url() . 'profile');
    }

    public function update_profile_data_personal()
    {
        $upd_id = $_POST['upd_id'];
        $newdata = [
            'status' => 0,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];
        $this->users_model->update_personal_data_user($newdata, $upd_id);
        $newdata = [
            'userid' => session()->get('id_user'),
            'DOB' => $_POST['DOB'],
            'personal_mail' => $_POST['personal_mail'],
            'current_addresss' => $_POST['current_addresss'],
            'permanent_address' => $_POST['permanent_address'],
            'personal_phone' => $_POST['personal_phone'],
            'home_phone' => $_POST['home_phone'],
            'emergency_phone' => $_POST['emergency_phone'],
            'emergency_contact' => $_POST['emergency_contact'],
            'emergency_relation' => $_POST['emergency_relation'],
            'PAN' => $_POST['PAN'],
            'status' => 1,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];
        $this->users_model->add_personal_data_user($newdata);

        session()->setFlashdata('success', 'Profile data Updated.');
        return redirect()->to(base_url() . 'profile/update_data');
    }

    public function changeUserdata()
    {
        $data = [];
        helper(['form']);
        $id_user = session()->get('id_user');
        $userdata = $this->users_model->usersedit_view($id_user);
        $data['userdata'] = $userdata;
        if ($this->request->getPost()) {
            $rules = [
                'password' => 'required|max_length[255]',
            ];
            if (!$this->validate($rules)) {
                $data['validationpassword'] = $this->validator;
            } else {
                $newdata = [
                    'password' => trim(password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                $result = $this->users_model->updateUsersData($id_user, $newdata);
                //if ($result) {
                $session = session();
                $session->setFlashdata('success', 'Data updated Successful');
                return redirect()->to(base_url() . '/profile');
                // } else {
                //    session()->setFlashdata('error', 'Data not saved!');
                ////    session()->setFlashdata('alert-class', 'alert-danger');
                //  }
            }
        }

        echo view('templates/header_view', $data);
        echo view('profile/profile_view', $data);
        echo view('templates/footer_view');
    }
    public function appUsernameChange()
    {
        $data = [];
        helper(['form']);
        // print_r($_POST);
        // exit();
        $id_user = session()->get('id_user');
        $userdata = $this->users_model->usersedit_view($id_user);
        $data['userdata'] = $userdata;

        if ($this->request->getPost()) {
            $rules = [
                'app_username' => [
                    'label' => 'App Username',
                    'rules' => ['required', 'max_length[10]', 'is_unique[users.app_username]'],
                    'errors' => [
                        'required' => 'The App Username field is required.',
                        'max_length' => 'The App Username field cannot exceed 10 characters.', // Corrected max_length
                    ],
                ],
            ];
            if (!$this->validate($rules)) {
                // print_r('tt');
                // exit();
                $data['validationappUser'] = $this->validator;
            } else {
                $newdata = [
                    'app_username' => $this->request->getVar('app_username'),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                // print_r($newdata);
                // exit();
                $result = $this->users_model->updateUsersData($id_user, $newdata);
                if ($result) {
                    $session = session();
                    $session->setFlashdata('success', 'App Username updated Successful');
                    return redirect()->to(base_url() . '/profile');
                } else {
                    session()->setFlashdata('error', 'Data not saved!');
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . '/profile');
                }
            }
        }

        echo view('templates/header_view', $data);
        echo view('profile/profile_view', $data);
        echo view('templates/footer_view');
    }
    public function appPasswordChange()
    {
        $data = [];
        helper(['form']);
        // print_r($_POST);
        // exit();
        $id_user = session()->get('id_user');
        $userdata = $this->users_model->usersedit_view($id_user);
        $data['userdata'] = $userdata;
        if ($this->request->getPost()) {
            $rules = [

                'app_password' => [
                    'label' => 'App Password',
                    'rules' => ['required', 'max_length[255]'],
                    'errors' => [
                        'required' => 'The App Password field is required.',
                        'max_length' => 'The App Password field cannot exceed 255 characters.', // Corrected max_length
                    ],
                ],

            ];
            if (!$this->validate($rules)) {
                // print_r('tt');
                // exit();
                $data['validationappPass'] = $this->validator;
            } else {
                $newdata = [
                    'app_password' => $this->request->getVar('app_password'),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                // print_r($newdata);
                // exit();
                $result = $this->users_model->updateUsersData($id_user, $newdata);
                if ($result) {
                    $session = session();
                    $session->setFlashdata('success', 'App Password updated Successful');
                    return redirect()->to(base_url() . '/profile');
                } else {
                    session()->setFlashdata('error', 'Data not saved!');
                    session()->setFlashdata('alert-class', 'alert-danger');
                    return redirect()->to(base_url() . '/profile');
                }
            }
        }

        echo view('templates/header_view', $data);
        echo view('profile/profile_view', $data);
        echo view('templates/footer_view');
    }
    function uploadImage()
    {
        // $user = session()->get('username');
         $user = session()->get('id_user');

        if ($this->request->getPost()) {
            $rules = [
                'file' => 'uploaded[file]|ext_in[file,jpg,jpeg,png,JPG]',
            ];

            if (!$this->validate($rules)) {
                $data['filevalidation'] = $this->validator;
            } else {
                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {

                        $imagename = $file->getName();
                        $newfilename = $file->getRandomName();
                        $randomname = explode('.', $newfilename);

                        $userPath = FCPATH . 'assets/assets/uploads/profile/' . $user;

                        if (is_dir($userPath)) {
                            $this->deleteDirectory($userPath);
                        }

                        $uploadPath = $userPath . "/" . $randomname[0];
                        if (!is_dir($uploadPath)) {
                            mkdir($uploadPath, 0777, true);
                        }

                        $file->move($uploadPath, $imagename);

                        $filepath = $uploadPath . '/' . $imagename;

                        $newdata = [
                            'username' => $user,
                            'profile_foldername' => $randomname[0],
                            'profile_image' => $imagename,
                            'typeofvalue' => 1,
                            'last_updated_by' => session()->get('id_user'),
                            'last_updated_on' => time(),
                        ];

                        $result = $this->users_model->addprofileImgData($newdata);
                        if ($result) {
                            session()->setFlashdata('success', 'Uploaded Successfully!');
                        } else {
                            session()->setFlashdata('error', 'Data not found!');
                            session()->setFlashdata('alert-class', 'alert-danger');
                        }
                    }
                }
            }
        }
        return redirect()->to(base_url() . 'profile');
    }
    private function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (is_dir($dir . DIRECTORY_SEPARATOR . $item)) {
                $this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item);
            } else {
                unlink($dir . DIRECTORY_SEPARATOR . $item);
            }
        }

        return rmdir($dir);
    }


    function delete_doc()
    {
        $et_doc_id = $_POST['et_doc_id'];
        $newdata = [
            'status' => 0,
            'uploaded_by' => session()->get('id_user'),
            'uploaded_on' => time(),
        ];

        $this->users_model->deleteDoc($et_doc_id, $newdata);

        $session = session();
        $session->setFlashdata('success', 'Deleted successfully.');
        return redirect()->to(base_url() . 'profile/documents');
    }

    function uploadDoc()
    {
        $user = session()->get('username');
        if ($this->request->getPost()) {

            $rules = [
                'file' => 'uploaded[file]|ext_in[file,jpg,jpeg,png,JPG]',
            ];
            if (!$this->validate($rules)) {
                print_r($rules);
                exit();
                $file = $this->request->getFile('file');
                $data['filevalidation'] = $this->validator;
            } else {


                if ($file = $this->request->getFile('file')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $imagename = $file->getName();
                        $newfilename = $file->getRandomName();
                        $randomname = explode('.', $newfilename);
                        if (!file_exists(FCPATH . 'assets/assets/uploads/profile/' . $user . "/" . $randomname[0] . "/" . $imagename)) {
                            if (!is_dir(FCPATH . 'assets/assets/uploads/profile/' . $user . "/" . $randomname[0])) {
                                mkdir(FCPATH . 'assets/assets/uploads/profile/' . $user . "/" . $randomname[0], 0777, true);
                            }
                        }
                        $a = base_url() . '/assets/assets/uploads';
                        if (file_exists(FCPATH . 'assets/assets/uploads/profile/' . $user . "/" . $randomname[0] . "/" . $imagename)) {
                            session()->setFlashdata('success', $imagename . ' already exists.!');
                            return redirect()->to(base_url() . '/profile');
                        } else {

                            $file->move(FCPATH . 'assets/assets/uploads/profile/' . $user . "/" . $randomname[0], $imagename);
                            $filepath = FCPATH . 'assets/assets/uploads/profile/' . $user . "/" . $randomname[0] . '/' . $imagename;

                            $newdata = [
                                'id_user' => session()->get('id_user'),
                                'doc_type' => $_POST['document_type'],
                                'passwd' => $_POST['pazzword'],
                                'doc_folder' => $randomname[0],
                                'doc_name' => $imagename,
                                'status' => 1,
                                'uploaded_by' => session()->get('id_user'),
                                'uploaded_on' => time(),
                            ];
                            $result = $this->users_model->adddocument($newdata);
                            if ($result) {
                                $session = session();
                                $session->setFlashdata('success', 'Uploaded Successfully!');
                            } else {
                                session()->setFlashdata('error', 'Data not found!');
                                session()->setFlashdata('alert-class', 'alert-danger');
                            }
                        }
                    }
                }
            }
        }
        return redirect()->to(base_url() . 'profile/documents');
    }
}
