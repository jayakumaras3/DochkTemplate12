<?php

namespace App\Controllers\SCORM;

use App\Controllers\BaseController;

use App\Models\Settings\Dropdown_model;
use App\Models\SCORM\Scorm_course_model;
use App\Models\SCORM\Scorm_metacategory_model;
use App\Models\SCORM\Scorm_user_group_model;
use App\Models\SCORM\Scorm_learn_group_model;

#[\AllowDynamicProperties]
class Scorm_user_group extends BaseController
{
    private $db;

    public function __construct()
    {
        // $this->is_session_available();
        $this->dropdown_model = new Dropdown_model();
        $this->scorm_course_model = new Scorm_course_model();
        $this->scorm_metacategory_model = new Scorm_metacategory_model();
        $this->scorm_user_group_model = new Scorm_user_group_model();
        $this->scorm_learn_group_model = new Scorm_learn_group_model();
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
    public function index()
    {
        $data = [];
        helper(['form']);
        if ($response =  $this->requireRole(['44'])) {
            return $response;
        }
        $data = [
            'link1' => '',
            'link1_name' => '',
            'link2' => 'SCORM/Scorm_user_group',
            'link2_name' => 'User Group',
            'link3_name' => 'User Group'
        ];

        $data['header'] = 'Courses';
        $data['header_link'] = 'SCORM/scorm_courses';
        $data['sub_header_1'] = 'User Group';
        $data['form_link'] = 'SCORM/scorm_user_group/addcoursegroupval';
        $data['form_link_1'] = 'SCORM/scorm_user_group/add_users';
        $data['form_link_2'] = 'SCORM/scorm_user_group/deletecoursegpdetails';
        $client = session()->get('client');
        $data['coursegroupdata'] = $this->scorm_user_group_model->getCoursegroupdate(4, $client);
        echo view('templates/header_view', $data);
        // echo view('settings/settings_left_menu', $data);
        echo view('SCORM/scorm_user_group/user_group_view', $data);
        echo view('templates/footer_view');
    }
    public function addcoursegroupval()
    {
        $data = [];
        helper(['form']);
        if ($response =  $this->requireRole(['44'])) {
            return $response;
        }
        if ($this->request->getPost()) {
            $newdata = [
                'client_id' => session()->get('client'),
                'description' => $this->request->getVar('description'),
                'type' => '4',
                'status' => '1',
                'createdby' => session()->get('id_user'),
                'createdon' => time(),
                'last_updated_by' =>  session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $group_id = $this->scorm_user_group_model->addcoursegroupdetails($newdata);
            $client = session()->get('client');
            $add_data = [
                'group_id' => $group_id,
                'client_id' => $client,
                'status' => '1',
                'created_by' => session()->get('id_user'),
                'created_on' => time(),
                'last_updated_by' =>  session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $this->scorm_learn_group_model->add_client_to_gr($add_data);
            session()->setFlashdata('success', lang('Messages.Success_0011'));
            return redirect()->to(base_url() . 'SCORM/scorm_user_group');
        }
    }
    public function usergroup_edit_view()
    {
        $data = [];
        helper(['form']);
        if ($response =  $this->requireRole(['44'])) {
            return $response;
        }
        $data = [
            'link1' => '',
            'link1_name' => '',
            'link2' => 'SCORM/Scorm_user_group',
            'link2_name' => 'User Group',
            'link3_name' => 'Edit User Group'
        ];
        $data['header'] = 'Courses';
        $data['header_link'] = 'SCORM/scorm_courses';
        $data['sub_header_1'] = 'User Group';
        $data['sub_header_1_link'] = 'SCORM/scorm_user_group';
        $data['sub_header_2'] = 'Edit User Group';


        $user = session()->get('username');
        if (isset($_POST['sc_cgid'])) {
            $data['sc_cgid'] = $_POST['sc_cgid'];
            $_SESSION['sc_cgid'] =  $data['sc_cgid'];
        } else if (isset($_GET['sc_cgid'])) {
            $data['sc_cgid'] = $_GET['sc_cgid'];
        } else if (isset($_SESSION['sc_cgid'])) {
            $data['sc_cgid'] = $_SESSION['sc_cgid'];
        }
        $getcoursegroup = $this->scorm_user_group_model->getusersgroupDetails($data['sc_cgid']);
        $data['row'] = $getcoursegroup;
        $data['form_link'] = 'SCORM/scorm_user_group/editcoursegpval?sc_cgid=' . $getcoursegroup[0]['sc_cgid'];

        echo view('templates/header_view', $data);
        //echo view('settings/settings_left_menu', $data);
        echo view('SCORM/scorm_user_group/usergroup_edit_view', $data);
        echo view('templates/footer_view');
    }
    public function editcoursegpval()
    {
        $data = [];
        helper(['form']);
        if ($response =  $this->requireRole(['44'])) {
            return $response;
        }
        $user = session()->get('username');
        $sc_cgid = $this->request->getVar('sc_cgid');
        $data['sc_cgid'] = $sc_cgid;
        $getcoursegroup = $this->scorm_user_group_model->getusersgroupDetails($data['sc_cgid']);
        $data['row'] = $getcoursegroup;
        if ($this->request->getPost()) {
            $newdata = [
                'description' => $this->request->getVar('description'),
                'status' => $this->request->getVar('status'),
            ];
            $result = $this->scorm_user_group_model->editcoursegpdetails($newdata, $sc_cgid);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0008'));
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }
            return redirect()->to(base_url() . '/SCORM/scorm_user_group');
        }
    }
    public function deletecoursegpdetails()
    {
        if ($response =  $this->requireRole(['44'])) {
            return $response;
        }
        $sc_cgid = $_POST['sc_cgid'];
        $newdata = [
             
            
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => '0',
        ];
        $result = $this->scorm_user_group_model->editcoursegpdetails($newdata, $sc_cgid);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
            return redirect()->to(base_url() . '/SCORM/scorm_user_group');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . '/SCORM/scorm_user_group');
        }
    }
    public function add_users()
    {
        $data = [];
        helper(['form']);
        if ($response =  $this->requireRole(['44'])) {
            return $response;
        }
        $data = [
            'link1' => '',
            'link1_name' => '',
            'link2' => 'SCORM/Scorm_user_group',
            'link2_name' => 'User Group',
            'link3_name' => 'Add User'
        ];

        $data['header'] = 'Courses';
        $data['header_link'] = 'SCORM/scorm_courses';
        $data['sub_header_1'] = 'User Group';
        $data['sub_header_1_link'] = 'SCORM/scorm_user_group';
        $data['sub_header_2'] = 'Group Courses';

        $data['form_link'] = 'SCORM/scorm_user_group/add_user_to_group';
        $data['form_link_1'] = 'SCORM/scorm_user_group/delete_assigned_user';

        if (isset($_POST['sc_cgid'])) {
            $data['sc_cgid'] = $_POST['sc_cgid'];
            $_SESSION['sc_cgid'] =  $data['sc_cgid'];
        } else if (isset($_GET['sc_cgid'])) {
            $data['sc_cgid'] = $_GET['sc_cgid'];
        } else if (isset($_SESSION['sc_cgid'])) {
            $data['sc_cgid'] = $_SESSION['sc_cgid'];
        }

        $getusersgroup = $this->scorm_user_group_model->getusersgroupDetails($data['sc_cgid']);
        $data['row'] = $getusersgroup;
        $data['all_users'] = $this->scorm_user_group_model->getUsersDetails();
        $data['assigned_courses'] = $this->scorm_user_group_model->getUsersAssignedto($data['sc_cgid']);
        $data['type'] = 4;

        echo view('templates/header_view', $data);
        //echo view('settings/settings_left_menu', $data);
        echo view('SCORM/scorm_user_group/usergroup_add_courses', $data);
        echo view('templates/footer_view');
    }
    public function add_user_to_group()
    {
        $data = [];
        helper(['form']);
        if ($response =  $this->requireRole(['44'])) {
            return $response;
        }
        $newData = [
            'user_id' => $_POST['user_id'],
            'group_id' => $_POST['sc_cgid'],
            'status' => 1,
            'createdby' => session()->get('id_user'),
            'createdon' => time(),
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_user_group_model->add_user_to_gr($newData);
        if (isset($result['status']) && $result['status'] === 'OK') {
            session()->setFlashdata('success', lang('Messages.Success_0011'));
        }
        echo json_encode($result);
    }
    // public function add_user_to_group1()
    // {
    //     $data = [];
    //     helper(['form']);
    //     $sc_cgid = $this->request->getVar('sc_cgid');
    //     $data['sc_cgid'] = $sc_cgid;
    //    if ($this->request->getPost()) {
    //         $course_id = $this->request->getVar('course_id');
    //         $checkifexists = $this->scorm_user_group_model->check_if_course_exists_in_group($course_id, $sc_cgid);
    //         if ($checkifexists == 0) {
    //             $timestamp = time();
    //             $newdata = [
    //                 'course_id' => $course_id,
    //                 'group_id' => $sc_cgid,
    //                 'status' => 1,
    //                 'createdby' => session()->get('id_user'),
    //                 'createdon' => $timestamp,
    //             ];
    //             $result = $this->scorm_user_group_model->add_user_to_gr($newdata);

    //             if ($result) {
    //                 session()->setFlashdata('success', 'Course added to the Group.');
    //                 return redirect()->to(base_url() . '/scorm_user_group/add_users');
    //             } else {
    //                 session()->setFlashdata('error', 'Error adding course.');
    //                 session()->setFlashdata('alert-class', 'alert-danger');
    //                 return redirect()->to(base_url() . '/scorm_user_group/add_users');
    //             }
    //         } else {
    //             session()->setFlashdata('error', 'Course already assigned.');
    //             session()->setFlashdata('alert-class', 'alert-danger');
    //             return redirect()->to(base_url() . '/scorm_user_group/add_users');
    //         }
    //     }
    // }

    public function delete_assigned_user()
    {
        $data = [];
        helper(['form']);
        if ($response =  $this->requireRole(['44'])) {
            return $response;
        }
        $sc_cgid = $this->request->getVar('sc_cgid');
        $data['sc_cgid'] = $sc_cgid;
        if ($this->request->getPost()) {
            $rules = [
                'sc_cgid' => 'required',
                'assign_id' => 'required',
            ];
            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
            } else {
                $timestamp = time();
                $newdata = [
                    'status' => 0,
                    
                     
                    'last_updated_by' =>  session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                $assign_id = $this->request->getVar('assign_id');
                $result = $this->scorm_user_group_model->delete_user_assigned_mod($newdata, $assign_id);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0005'));
                } else {
                    session()->setFlashdata('error', 'Error deleting course.');
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
        }
        return redirect()->to(base_url() . 'SCORM/scorm_user_group/add_users');
    }
    public function uploadgrouplogo()
    {
        $data = [];
        if ($response =  $this->requireRole(['44'])) {
            return $response;
        }
        helper(['filesystem']);
        if (isset($_POST['sc_cgid'])) {
            $data['sc_cgid'] = $_POST['sc_cgid'];
            $_SESSION['sc_cgid'] =  $data['sc_cgid'];
        } else if (isset($_GET['sc_cgid'])) {
            $data['sc_cgid'] = $_GET['sc_cgid'];
        } else if (isset($_SESSION['sc_cgid'])) {
            $data['sc_cgid'] = $_SESSION['sc_cgid'];
        }

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
                        if (!is_dir(FCPATH . 'assets/assets/uploads/group_logo/' . $data['sc_cgid'])) {
                            mkdir('assets/assets/uploads/group_logo/' . $data['sc_cgid'], 0777, true);
                        }
                        if (file_exists(FCPATH . 'assets/assets/uploads/group_logo/' . $data['sc_cgid'] . "/" . $filename)) {
                            session()->setFlashdata('error', $filename . ' ' . lang('Messages.Success_0051'));
                            return redirect()->to(base_url() . 'SCORM/scorm_user_group/usergroup_edit_view');
                        } else {
                            // print_r($data['sc_cgid']);
                            // exit();
                            if ($file->move(FCPATH . 'assets/assets/uploads/group_logo/' . $data['sc_cgid'], $filename)) {
                                $filepath = FCPATH . 'assets/assets/uploads/group_logo/' . $data['sc_cgid'] . '/' . $filename;
                                $newdata = [
                                    'logo' => $filename,
                                ];
                                $result = $this->scorm_user_group_model->updategrouplogo($newdata, $data['sc_cgid']);
                                if ($result) {
                                    session()->setFlashdata('sc_cgid', $data['sc_cgid']);
                                    session()->setFlashdata('success', lang('Messages.Success_0009'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                    return redirect()->to(base_url() . 'SCORM/scorm_user_group/usergroup_edit_view');
                                } else {
                                    session()->setFlashdata('sc_cgid',  $data['sc_cgid']);
                                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                    return redirect()->to(base_url() . 'SCORM/scorm_user_group/usergroup_edit_view');
                                }
                            }
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                        return redirect()->to(base_url() . 'SCORM/scorm_user_group/usergroup_edit_view');
                    }
                }
            }
        }
    }
    function coursegroup_assignto_usergroup()
    {
        $data = [];
        if ($response =  $this->requireRole(['44'])) {
            return $response;
        }
        if (isset($_POST['sc_cgid'])) {
            $data['sc_cgid'] = $_POST['sc_cgid'];
            $_SESSION['sc_cgid'] =  $data['sc_cgid'];
        } else if (isset($_GET['sc_cgid'])) {
            $data['sc_cgid'] = $_GET['sc_cgid'];
        } else if (isset($_SESSION['sc_cgid'])) {
            $data['sc_cgid'] = $_SESSION['sc_cgid'];
        }
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);
        $data['client'] = session()->get('client');
        if (in_array('44', $arrayuserlevel) || in_array('5', $arrayuserlevel)) {
            $data['course_group'] = $this->scorm_user_group_model->getCourseUserGroupdata(3);
            $data['user_group'] = $this->scorm_user_group_model->getCourseUserGroupdata(4);
            $data['group_users'] = $this->scorm_learn_group_model->getGroupUsers($data['sc_cgid'], $data['client']);

            echo view('templates/header_view', $data);
            echo view('SCORM/scorm_course_group/coursegroup_assignto_usergroup', $data);
            echo view('templates/footer_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            return redirect()->to(base_url('my_training'));
        }
    }
    public function assignCoursegrouptoUsergroup()
    {
        $c_gid = $_POST['c_gid'];
        $u_gid = $_POST['u_gid'];
        if ($response =  $this->requireRole(['44'])) {
            return $response;
        }
        $result = $this->scorm_user_group_model->assigncoursegrouptousergroup($c_gid, $u_gid);
        // print_r($result);
        // exit();
        if (isset($result['success'])) {
            $session = session();
            $session->setFlashdata('success', $result['success']);
            return redirect()->to(base_url() . 'SCORM/Scorm_learn_group/coursegroup_useradd_view');
        } else if (isset($result['error'])) {
            session()->setFlashdata('error', $result['error']);
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'SCORM/Scorm_learn_group/coursegroup_useradd_view');
        }
    }
}
