<?php

namespace App\Controllers\SCORM;

use App\Controllers\BaseController;

use App\Models\Settings\Dropdown_model;
use App\Models\SCORM\Scorm_course_model;
use App\Models\SCORM\Scorm_metacategory_model;
use App\Models\SCORM\Scorm_learn_group_model;
use App\Models\User_login\Users_model;
use App\Models\SCORM\Scorm_user_group_model;


#[\AllowDynamicProperties]
class Scorm_learn_group extends BaseController
{
    private $db;

    public function __construct()
    {
        // $this->is_session_available();
        $this->dropdown_model = new Dropdown_model();
        $this->scorm_course_model = new Scorm_course_model();
        $this->users_model = new Users_model();
        $this->scorm_user_group_model = new Scorm_user_group_model();
        $this->scorm_metacategory_model = new Scorm_metacategory_model();
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
        if ($response =  $this->requireRole(['6', '44', '5', '73', '3'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data = [
            'link1' => '',
            'link1_name' => '',
            'link2' => '',
            'link2_name' => '',
            'link3_name' => 'Course Group'
        ];

        $data['header'] = 'Courses';
        $data['header_link'] = 'SCORM/scorm_courses';
        $data['sub_header_1'] = 'Course Group';
        $data['form_link'] = 'SCORM/Scorm_learn_group/addcoursegroupval';
        $data['form_link_1'] = 'SCORM/Scorm_learn_group/add_courses';
        $data['form_link_2'] = 'SCORM/Scorm_learn_group/coursegroup_edit_view';
        $data['form_link_3'] = 'SCORM/Scorm_learn_group/coursegroup_useradd_view';
        $client = session()->get('client');
        $data['coursegroupdata'] = $this->scorm_learn_group_model->getCoursegroupdate(3, $client);
        echo view('templates/header_view', $data);
        //  echo view('settings/settings_left_menu', $data);
        echo view('SCORM/scorm_course_group/course_group_view', $data);
        echo view('templates/footer_view');
    }

    public function coursegroup_useradd_view()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['sc_cgid'])) {
            $data['sc_cgid'] = $_POST['sc_cgid'];
            $_SESSION['sc_cgid'] = $data['sc_cgid'];
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


            $data['getUserclientlist'] = $this->users_model->getUserclientlist($data['client']);
            //  $data['group_users'] = $this->scorm_learn_group_model->getGroupUsers($data['sc_cgid'], $data['client']);
            $data['form_link'] = 'SCORM/Scorm_learn_group/editcoursegpval';
            $data['pr_id'] = session()->get('partner_code');
            echo view('templates/header_view', $data);
            echo view('SCORM/scorm_course_group/coursegroup_useradd_view', $data);
            echo view('templates/footer_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            return redirect()->to(base_url('my_training'));
        }
    }

    // public function add_coursegroup_user()
    // {
    //     $newdata = [
    //         'sc_cgid' => $this->request->getVar('sc_cgid'),
    //         'user_id' => $this->request->getVar('user_id'),
    //         'status' => '1',
    //         'last_updated_by' => session()->get('id_user'),
    //         'last_updated_on' => time(),
    //     ];
    //     $this->scorm_learn_group_model->addcoursegroupassignusers($newdata);
    //     session()->setFlashdata('success', 'New User added to the Group.');
    //     return redirect()->to(base_url() . 'SCORM/Scorm_learn_group/coursegroup_useradd_view');
    // }
    public function add_coursegroup_user()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }

        helper(['form']);

        $rules = [
            'sc_cgid' => 'required|integer',
            'user_id' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
            return redirect()->back()->withInput();
        }

        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);
        if (!in_array('44', $arrayuserlevel) && !in_array('5', $arrayuserlevel)) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            return redirect()->back();
        }


        $sc_cgid = $this->request->getPost('sc_cgid');
        $user_id = $this->request->getPost('user_id');

        // Example: check if user exists and not already in group
        // $existingUser = $this->scorm_learn_group_model->isUserInGroup($sc_cgid, $user_id);
        //  exit();

        // $validUser = $this->scorm_learn_group_model->getUserById($user_id); // fetch user info

        // if (!$validUser) {
        //     session()->setFlashdata('error', 'Selected user does not exist.');
        //     return redirect()->back();
        // }

        // if ($existingUser) {
        //     session()->setFlashdata('error', 'User is already in this group.');
        //     return redirect()->back();
        // }

        $newdata = [
            'sc_cgid'          => $sc_cgid,
            'user_id'          => $user_id,
            'status'           => 1,
            'last_updated_by'  => session()->get('id_user'),
            'last_updated_on'  => time(),
        ];

        $result = $this->scorm_learn_group_model->addcoursegroupassignusers($newdata);

        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0011'));
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0003'));
        }

        return redirect()->to(base_url('SCORM/Scorm_learn_group/coursegroup_useradd_view'));
    }
    public function del_courseuser()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
        $scgu_id = $this->request->getVar('scgu_id');
        $newdata = [
            'status' => '0',
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $this->scorm_learn_group_model->updateGroupUserData($newdata, $scgu_id);
        session()->setFlashdata('success', lang('Messages.Success_0005'));
        return redirect()->to(base_url() . 'SCORM/Scorm_learn_group/coursegroup_useradd_view');
    }

    public function del_coursegroup_user()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
        $sc_cgid = $this->request->getVar('scgu_id');
        if (isset($_POST['sc_cgid'])) {
            $data['sc_cgid'] = $_POST['sc_cgid'];
            $_SESSION['sc_cgid'] =  $data['sc_cgid'];
        } else if (isset($_GET['sc_cgid'])) {
            $data['sc_cgid'] = $_GET['sc_cgid'];
        } else if (isset($_SESSION['sc_cgid'])) {
            $data['sc_cgid'] = $_SESSION['sc_cgid'];
        }
        $newdata = [
            'status' => '0',
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $this->scorm_learn_group_model->updateGroupUserData($newdata, $sc_cgid);
        session()->setFlashdata('success', lang('Messages.Success_0005'));
        return redirect()->to(base_url() . 'SCORM/Scorm_user_group/coursegroup_assignto_usergroup');
    }
    public function addcoursegroupval()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
        $data = [];
        $data = [
            'link1' => '',
            'link1_name' => '',
            'link2' => '',
            'link2_name' => '',
            'link3_name' => 'Course Group'
        ];

        $data['header'] = 'Courses';
        $data['header_link'] = 'SCORM/scorm_courses';
        $data['sub_header_1'] = 'Course Group';
        $data['form_link'] = 'SCORM/Scorm_learn_group/addcoursegroupval';
        $data['form_link_1'] = 'SCORM/Scorm_learn_group/add_courses';
        $data['form_link_2'] = 'SCORM/Scorm_learn_group/coursegroup_edit_view';
        $data['form_link_3'] = 'SCORM/Scorm_learn_group/coursegroup_useradd_view';
        $client = session()->get('client');
        $data['coursegroupdata'] = $this->scorm_learn_group_model->getCoursegroupdate(3, $client);
        helper(['form']);
        if ($this->request->getPost()) {
            $rules = [
                'description' => [
                    'label' => 'Course Group Name',
                    'rules' => ['required', 'is_unique[scorm_course_group.description]'],
                    'errors' => [
                        'required' => 'The Course Group Name field is required.',
                        'is_unique' => 'Course Group Name already exists, please try another name.'
                    ],
                ],

            ];
            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
            } else {
                $newdata = [
                    'client_id' => session()->get('client'),
                    'description' => $this->request->getVar('description'),
                    'type' => '3',
                    'status' => '1',
                    'visible' => 1,
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                $group_id = $this->scorm_learn_group_model->addcoursegroupdetails($newdata);
                $client = session()->get('client');
                $add_data = [
                    'group_id' => $group_id,
                    'client_id' => $client,
                    'status' => '1',
                    'created_by' => session()->get('id_user'),
                    'created_on' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                $this->scorm_learn_group_model->add_client_to_gr($add_data);
                $newdata = [
                    'sc_cgid' => $group_id,
                    'user_id' => session()->get('id_user'),
                    'status' => '1',

                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                $this->scorm_learn_group_model->addcoursegroupassignusers($newdata);

                session()->setFlashdata('success', lang('Messages.Success_0011'));
                return redirect()->to(base_url() . '/SCORM/Scorm_learn_group');
            }
        }
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_course_group/course_group_view', $data);
        echo view('templates/footer_view');
    }
    public function coursegroup_edit_view()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data = [
            'link1' => 'my_training',
            'link1_name' => 'Dashboard',
            'link2' => 'SCORM/Scorm_learn_group',
            'link2_name' => 'Course Group',
            'link3_name' => 'Edit Course Group'
        ];
        $data['header'] = 'Courses';
        $data['header_link'] = 'SCORM/scorm_courses';
        $data['sub_header_1'] = 'Course Group';
        $data['sub_header_1_link'] = 'SCORM/Scorm_learn_group';
        $data['sub_header_2'] = 'Edit Course Group';


        $user = session()->get('username');
        if (isset($_POST['sc_cgid'])) {
            $data['sc_cgid'] = $_POST['sc_cgid'];
            $_SESSION['sc_cgid'] = $data['sc_cgid'];
        } else if (isset($_GET['sc_cgid'])) {
            $data['sc_cgid'] = $_GET['sc_cgid'];
        } else if (isset($_SESSION['sc_cgid'])) {
            $data['sc_cgid'] = $_SESSION['sc_cgid'];
        }

        $getcoursegroup = $this->scorm_learn_group_model->getcoursegroupDetails($data['sc_cgid']);
        $data['client'] = session()->get('client');
        $data['row'] = $getcoursegroup;
        $data['form_link'] = 'SCORM/Scorm_learn_group/editcoursegpval';
        $data['pr_id'] = session()->get('partner_code');

        if ($data['client'] == 1) {
            $data['clientlist'] = $this->scorm_learn_group_model->clientuserlist();
            $data['assigned_client_list'] = $this->scorm_learn_group_model->clientassignedlist($data['sc_cgid']);
        }
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_course_group/coursegroup_edit_view', $data);
        echo view('templates/footer_view');
    }


    public function editcoursegpval()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $user = session()->get('username');
        $sc_cgid = $this->request->getVar('sc_cgid');
        $data['sc_cgid'] = $sc_cgid;
        $getcoursegroup = $this->scorm_learn_group_model->getcoursegroupDetails($data['sc_cgid']);
        $data['row'] = $getcoursegroup;
        if ($this->request->getPost()) {
            $newdata = [
                'description' => $this->request->getVar('description'),
                'visible' => $this->request->getVar('visible'),
                'status' => $this->request->getVar('status'),
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $result = $this->scorm_learn_group_model->editcoursegpdetails($newdata, $sc_cgid);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0008'));
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }
            return redirect()->to(base_url() . '/SCORM/Scorm_learn_group');
        }
    }
    public function deletecoursegpdetails()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
        $sc_cgid = $_POST['sc_cgid'];
        $newdata = [
             
            
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => '0',
        ];
        $result = $this->scorm_learn_group_model->editcoursegpdetails($newdata, $sc_cgid);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
            return redirect()->to(base_url() . '/SCORM/Scorm_learn_group');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . '/SCORM/Scorm_learn_group');
        }
    }

    public function addcoursegroup_to_client()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
        helper(['form']);
        $newData = [
            'client_id' => $_POST['client'],
            'group_id' => $_POST['sc_cgid'],
            'status' => 1,
            'created_by' => session()->get('id_user'),
            'created_on' => time(),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $this->scorm_learn_group_model->add_client_to_gr($newData);
        session()->setFlashdata('success', lang('Messages.Success_0011'));
        return redirect()->to(base_url() . 'SCORM/Scorm_learn_group/coursegroup_edit_view');
    }

    public function delete_group_clients()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
        helper(['form']);
        $scgcid = $_POST['scgcid'];
        $newData = [
            'status' => 0,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $this->scorm_learn_group_model->del_client_to_gr($newData, $scgcid);
        session()->setFlashdata('success', lang('Messages.Success_0005'));
        return redirect()->to(base_url() . 'SCORM/Scorm_learn_group/coursegroup_edit_view');
    }
    public function add_courses()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data = [
            'link1' => 'my_training',
            'link1_name' => 'Dashboard',
            'link2' => 'SCORM/Scorm_learn_group',
            'link2_name' => 'Course Group',
            'link3_name' => 'Add Course'
        ];
        $data['header'] = 'Courses';
        $data['header_link'] = 'SCORM/scorm_courses';
        $data['sub_header_1'] = 'Course Group';
        $data['sub_header_1_link'] = 'SCORM/Scorm_learn_group';
        $data['sub_header_2'] = 'Group Courses';

        $data['form_link'] = 'SCORM/Scorm_learn_group/add_course_to_group';
        $data['form_link_1'] = 'SCORM/Scorm_learn_group/delete_assigned_course';

        if (isset($_POST['sc_cgid'])) {
            $data['sc_cgid'] = $_POST['sc_cgid'];
            $_SESSION['sc_cgid'] = $data['sc_cgid'];
        } else if (isset($_SESSION['sc_cgid'])) {
            $data['sc_cgid'] = $_SESSION['sc_cgid'];
        }


        $getcoursegroup = $this->scorm_learn_group_model->getcoursegroupDetails($data['sc_cgid']);
        $data['row'] = $getcoursegroup;
        $data['all_courses'] = $this->scorm_course_model->coursenamessearch_view20();
        $data['assigned_courses'] = $this->scorm_learn_group_model->getCoursesAssignedto($data['sc_cgid']);
        $data['type'] = 3;

        echo view('templates/header_view', $data);
        //  echo view('settings/settings_left_menu', $data);
        echo view('SCORM/scorm_course_group/coursegroup_add_courses', $data);
        echo view('templates/footer_view');
    }
    public function add_course_to_grauto($projectid, $group_id, $language)
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
        $result = $this->scorm_learn_group_model->add_course_to_grauto($projectid, $group_id, $language);
        echo json_encode($result);
    }
    public function add_course_to_group()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $newData = [
            'course_id' => $_POST['course_id'],
            'group_id' => $_POST['sc_cgid'],
            'status' => 1,
            'createdby' => session()->get('id_user'),
            'createdon' => time(),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_learn_group_model->add_course_to_gr($newData);
        echo json_encode($result);
    }
    // public function add_course_to_group1()
    // {
    //     $data = [];
    //     helper(['form']);
    //     $sc_cgid = $this->request->getVar('sc_cgid');
    //     $data['sc_cgid'] = $sc_cgid;
    //    if ($this->request->getPost()) {
    //         $course_id = $this->request->getVar('course_id');
    //         $checkifexists = $this->scorm_learn_group_model->check_if_course_exists_in_group($course_id, $sc_cgid);
    //         if ($checkifexists == 0) {
    //             $timestamp = time();
    //             $newdata = [
    //                 'course_id' => $course_id,
    //                 'group_id' => $sc_cgid,
    //                 'status' => 1,
    //                 'createdby' => session()->get('id_user'),
    //                 'createdon' => $timestamp,
    //             ];
    //             $result = $this->scorm_learn_group_model->add_course_to_gr($newdata);

    //             if ($result) {
    //                 session()->setFlashdata('success', 'Course added to the Group.');
    //                 return redirect()->to(base_url() . '/Scorm_learn_group/add_courses');
    //             } else {
    //                 session()->setFlashdata('error', 'Error adding course.');
    //                 session()->setFlashdata('alert-class', 'alert-danger');
    //                 return redirect()->to(base_url() . '/Scorm_learn_group/add_courses');
    //             }
    //         } else {
    //             session()->setFlashdata('error', 'Course already assigned.');
    //             session()->setFlashdata('alert-class', 'alert-danger');
    //             return redirect()->to(base_url() . '/Scorm_learn_group/add_courses');
    //         }
    //     }
    // }

    public function delete_assigned_course()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $user = session()->get('username');
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
                    
                     
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),
                ];
                $assign_id = $this->request->getVar('assign_id');
                $result = $this->scorm_learn_group_model->delete_course_assigned_mod($newdata, $assign_id);
                if ($result) {
                    session()->setFlashdata('success', lang('Messages.Success_0005'));
                } else {
                    session()->setFlashdata('error', lang('Messages.Error_0003'));
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }
        }
        return redirect()->to(base_url() . 'SCORM/Scorm_learn_group/add_courses');
    }
    public function uploadgrouplogo()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
        $data = [];
        helper(['filesystem']);
        if (isset($_POST['sc_cgid'])) {
            $data['sc_cgid'] = $_POST['sc_cgid'];
            $_SESSION['sc_cgid'] = $data['sc_cgid'];
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
                            return redirect()->to(base_url() . 'SCORM/Scorm_learn_group/coursegroup_edit_view');
                        } else {
                            // print_r($data['sc_cgid']);
                            // exit();
                            if ($file->move(FCPATH . 'assets/assets/uploads/group_logo/' . $data['sc_cgid'], $filename)) {
                                $filepath = FCPATH . 'assets/assets/uploads/group_logo/' . $data['sc_cgid'] . '/' . $filename;
                                $newdata = [
                                    'logo' => $filename,
                                ];
                                $result = $this->scorm_learn_group_model->updategrouplogo($newdata, $data['sc_cgid']);
                                if ($result) {
                                    session()->setFlashdata('sc_cgid', $data['sc_cgid']);
                                    session()->setFlashdata('success', lang('Messages.Success_0009'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                    return redirect()->to(base_url() . 'SCORM/Scorm_learn_group/coursegroup_edit_view');
                                } else {
                                    session()->setFlashdata('sc_cgid', $data['sc_cgid']);
                                    session()->setFlashdata('error', lang('Messages.Error_0001'));
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                    return redirect()->to(base_url() . 'SCORM/Scorm_learn_group/coursegroup_edit_view');
                                }
                            }
                        }
                    } else {
                        session()->setFlashdata('error', lang('Messages.Error_0001'));
                        session()->setFlashdata('alert-class', 'alert-danger');
                        return redirect()->to(base_url() . 'SCORM/Scorm_learn_group/coursegroup_edit_view');
                    }
                }
            }
        }
    }
}
