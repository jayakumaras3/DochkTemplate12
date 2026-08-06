<?php

namespace App\Controllers\Demo;

use App\Models\Demo\Cart_model;
use App\Models\User_login\Login_model;
use App\Models\Settings\Dropdown_model;
use App\Models\SCORM\Scorm_course_model;
use App\Controllers\BaseController;

#[\AllowDynamicProperties]
class Cart extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->is_session_available();
        $this->cart_model = new Cart_model();
        $this->login_model = new Login_model();
        $this->dropdown_model = new Dropdown_model();
        $this->scorm_course_model = new Scorm_course_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url('my_training'));
            exit();
        }

        $arrayuserlevel = explode(',', $userlevel);
        if (!in_array('8', $arrayuserlevel)) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }

    public function addToCart($scourse_id)
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $data = [];

        $data['header'] = 'Demo Dashboard';
        $data['header_link'] = 'Demo/demo_dashboard';

        $data['sub_header_1'] = 'Cart';
        $data['scourse_id'] = $scourse_id;
        $user = session()->get('id_user');

        if ($scourse_id > 0) {

            $course_exists = $this->cart_model->doesCourseAlreadyAssigned($scourse_id, $user);
            if ($course_exists == 0) {
                $newdata = [
                    'course_id' => $scourse_id,
                    'status' => '1',
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),

                ];
                $result = $this->cart_model->addCourseToCart($newdata);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0021'));
                    return redirect()->to(base_url() . 'demo/demo_dashboard');
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
        }

        $data['courseInCart'] = $this->cart_model->getAddedCourses($user);

        echo view('templates/header_view', $data);
        echo view('cart/cart_view');
        echo view('templates/footer_view');
    }
    public function delcart()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $delData = [
            'status' => '0',


            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $cartAssignid = $this->request->getVar('cartAssignid');
        $result = $this->cart_model->updateCartDetails($delData, $cartAssignid);

        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }

        return redirect()->to(base_url() . 'Demo/cart/report');
    }
    public function delItemFromCart()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $delData = [
            'status' => '0',


            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        // $assignID =  $this->request->getVar('assignID');
        if (isset($_POST['cartid'])) {
            $data['cartid'] = $_POST['cartid'];
            $_SESSION['cartid'] = $data['cartid'];
        } else if (isset($_GET['cartid'])) {
            $data['cartid'] = $_GET['cartid'];
        } else if (isset($_SESSION['cartid'])) {
            $data['cartid'] = $_SESSION['cartid'];
        }

        $result = $this->cart_model->delCartItem($delData, $data['cartid']);

        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }

        return redirect()->to(base_url() . 'Demo/cart/addToCart/0');
    }
    public function delItemFromassignedCart()
    {
        // print_r("tt");
        // exit();
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $delData = [
            'status' => '0',


            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        // $cartid =  $this->request->getVar('cartAssignid');
        if (isset($_POST['cartid'])) {
            $data['cartid'] = $_POST['cartid'];
            $_SESSION['cartid'] = $data['cartid'];
        } else if (isset($_GET['cartid'])) {
            $data['cartid'] = $_GET['cartid'];
        } else if (isset($_SESSION['cartid'])) {
            $data['cartid'] = $_SESSION['cartid'];
        }
        if (isset($_POST['username'])) {
            $data['username'] = $_POST['username'];
            $_SESSION['username'] = $data['username'];
        } else if (isset($_GET['username'])) {
            $data['username'] = $_GET['username'];
        } else if (isset($_SESSION['username'])) {
            $data['username'] = $_SESSION['username'];
        }
        if (isset($_POST['course_id'])) {
            $data['course_id'] = $_POST['course_id'];
            $_SESSION['course_id'] = $data['course_id'];
        } else if (isset($_GET['course_id'])) {
            $data['course_id'] = $_GET['course_id'];
        } else if (isset($_SESSION['course_id'])) {
            $data['course_id'] = $_SESSION['course_id'];
        }

        $this->cart_model->delCartItem($delData, $data['cartid']);
        $result = $this->cart_model->unassignUserfromCourse($data['username'], $data['course_id']);
        // print_r($result);
        // exit();
        if ($result) {

            session()->setFlashdata('success', lang('Messages.Success_0005'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            session()->setFlashdata('alert-class', 'alert-danger');
        }

        return redirect()->to(base_url() . 'Demo/cart/editCart');
    }
    public function report()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $data = [];

        $data['header'] = 'Demo Report';
        $user = session()->get('id_user');
        $data['reportData'] = $this->cart_model->getReportData($user);

        echo view('templates/header_view', $data);
        echo view('cart/report_view');
        echo view('templates/footer_view');
    }

    public function editCart()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $data = [];

        $data['header'] = 'Report';
        $data['header_link'] = 'Demo/cart/report';
        $data['sub_header_1'] = 'Edit Cart';
        $cartAssignid = $this->request->getVar('cartAssignid');
        if (isset($_POST['cartAssignid'])) {
            $data['cartAssignid'] = $_POST['cartAssignid'];
            $_SESSION['cartAssignid'] = $data['cartAssignid'];
        } else if (isset($_GET['cartAssignid'])) {
            $data['cartAssignid'] = $_GET['cartAssignid'];
        } else if (isset($_SESSION['cartAssignid'])) {
            $data['cartAssignid'] = $_SESSION['cartAssignid'];
        }
        $data['getAssignedCourses'] = $this->cart_model->getAssignedCourses($data['cartAssignid']);
        $data['getUserDetails'] = $this->cart_model->getUserDetails($data['cartAssignid']);
        $data['all_C4Ucourses'] = $this->scorm_course_model->getCoursesDetails(2);
        $data['all_Democourses'] = $this->scorm_course_model->getCoursesDetails(1);
        echo view('templates/header_view', $data);
        echo view('cart/report_edit');
        echo view('templates/footer_view');
    }
    function addCoursetoassigedCart()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);
        $scourse_id = $_POST['course_id'];
        $cartAssignid = $_POST['cartAssignid'];
        $username = $_POST['username'];
        $type = $_POST['type'];
        $user = session()->get('id_user');
        if (in_array('4', $arrayuserlevel) || in_array('6', $arrayuserlevel)) {
            if ($scourse_id > 0) {

                // $course_exists = $this->cart_model->doesCourseAlreadyAssigned($scourse_id, $user);
                // if ($course_exists == 0) {
                $newdata = [
                    'course_id' => $scourse_id,
                    'status' => '2',
                    'assign_id' => $cartAssignid,
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),

                ];
                $result = $this->cart_model->addmultiCourseToCart($newdata, $username);
                if ($result) {
                    echo json_encode($result);
                }
                // }
            }
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            return redirect()->to(base_url('dashboard'));
        }
    }
    public function updateDemo()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $email = $this->request->getVar('email');
        $secret_code = $this->request->getVar('secret_code');
        $start_date = $this->request->getVar('start_date');
        $assignID = $this->request->getVar('assignID');
        $notes = $this->request->getVar('notes');

        $updateRecord = [
            'user_email' => $email,
            'secret_code' => $secret_code,
            'notes' => $notes,
            'expiry_date' => $start_date,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $this->cart_model->updateCartDetails($updateRecord, $assignID);
        session()->setFlashdata('success', lang('Messages.Success_0008'));
        return redirect()->to(base_url() . 'Demo/cart/report');
    }

    public function assignDemo()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }

        $model = new Login_model();
        $validation = \Config\Services::validation();
        $rules = [
            'email' => 'required|valid_email',
        ];
        $errors = [
            'email' => [
                'validateUser' => 'Email format not correct.'
            ]
        ];
        if (!$this->validate($rules, $errors)) {
            $data['validation'] = $this->validator;
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'Demo/cart/addToCart/0');
        } else {
            $email = $this->request->getVar('email');
            $start_date = $this->request->getVar('start_date');
            $note = $this->request->getVar('notes');
            $user = session()->get('id_user');

            $n = 10;
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ#@%!';
            $randomString = '';
            for ($i = 0; $i < $n; $i++) {
                $index = rand(0, strlen($characters) - 1);
                $randomString .= $characters[$index];
            }

            $addRecord = [
                'user_email' => $email,
                'notes' => $note,
                'expiry_date' => $start_date,
                'secret_code' => $randomString,
                'status' => '1',
                'createdby' => $user,
                'createdon' => time(),
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
            ];

            $insertID = $this->cart_model->assignCartItems($addRecord);
            $SpecialID = $insertID * 5534;
            $newuser = 'SplUsr' . $SpecialID;
            $timestamp = time();
            $designation = 'Demo User';
            $newdata = [
                'name' => 'Demo User',
                'username' => $newuser,
                'email' => '',
                'designation' => $designation,
                'password' => trim(password_hash($randomString, PASSWORD_DEFAULT)),
                'timezone' => 2,
                'lang' => 'en',
                'userid' => uniqid(),
                'timestamp' => $timestamp,
                'valid' => '1',
                'client_id' => 19,
                'createdby' => session()->get('id_user'),
                'createdon' => time(),
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            if ($model->save($newdata)) {
                $insertedid = $model->InsertID();
                $cartAssigniddata = [
                    'username' => $newuser,
                ];
                $this->cart_model->updateCartDetails($cartAssigniddata, $insertID);
                $userdata['fk_id_user'] = $insertedid;
                $userdata['fk_id_dc'] = 1;
                $userdata['fk_id_d'] = 19;
                $userdata['status'] = 1;
                $userdata['createdby'] = session()->get('id_user');
                $userdata['createdon'] = time();
                $userdata['last_updated_by'] = session()->get('id_user');
                $userdata['last_updated_on'] = time();

                $result = $this->dropdown_model->updateCategory($userdata); //add client in dropdown_users table


                $userleveldata['fk_id_user'] = $insertedid;
                $userleveldata['fk_id_dc'] = 2;
                $userleveldata['fk_id_d'] = 3;
                $userleveldata['status'] = 1;
                $userleveldata['createdby'] = session()->get('id_user');
                $userleveldata['createdon'] = time();
                $userdata['last_updated_by'] = session()->get('id_user');
                $userdata['last_updated_on'] = time();
                $result = $this->dropdown_model->updateCategory($userleveldata); //add roles in dropdown_users table


            }

            //////USER CREATED////

            $cartRecord = [
                'status' => 2,
                'assign_id' => $insertID,


                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $this->cart_model->updateCartAssignment($cartRecord, $user);

            $getAssignedCourses = $this->cart_model->getAssignedCourses($insertID);
            if ($getAssignedCourses) {
                foreach ($getAssignedCourses as $eachCourse) {
                    $courseid = $eachCourse['scourse_id'];
                    $courseAssignment = [
                        'id_user' => $insertedid,
                        'course_id' => $courseid,
                        'status' => 1,
                        'createdby' => $user,
                        'createdon' => time(),
                        'last_updated_by' => session()->get('id_user'),
                        'last_updated_on' => time(),
                    ];
                    $this->cart_model->insertRecordsCourses($courseAssignment);
                }
            }


            if ($insertID) {
                $to = $email;
                $subject = 'Demo Assigned';
                $message = 'Hi,<br><br>'
                    . 'You have been given access to view demo content from Dochek Site.<br><br>'
                    . '<i>Content provided in this site is copyright protected. Only view permission has been provided for a limited amount of time to you. You are not allowed to replicate/copy/modify any of the content without permission. Don’t share the details provided in this email to any other person without permission.<br><br>'
                    . 'If you agree, click on the below URL to access the site. You need to provide the password provided to get access.<br><br>'
                    . base_url('ang/authentication/quickaccess/' . $SpecialID) . '<br>'
                    . 'Password: ' . $randomString . '<br>'
                    . 'Expiry: ' . $start_date . '<br><br>'
                    . 'Regards,<br>'
                    . 'Dochek Admin';
                $email = \Config\Services::email();
                $email->setFrom('do-not-reply@touchstonelc.com', 'Dochek');
                $email->setTo($to);
                // $email->setCC('another@another-example.com');
                //$email->setBCC('them@their-example.com');

                $email->setSubject($subject);
                $email->setMessage($message);

                if ($email->send()) {
                    session()->setFlashdata('success', lang('Messages.Success_0022'));
                } else {
                    $data = $email->printDebugger(['headers']);
                    print_r($data);
                    exit();
                }
                session()->setFlashdata('success', lang('Messages.Success_0022'));
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0003'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }
        }
        return redirect()->to(base_url() . 'Demo/cart/report');
    }
}
