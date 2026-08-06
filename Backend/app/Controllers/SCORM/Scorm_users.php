<?php

namespace App\Controllers\SCORM;

use App\Controllers\BaseController;

use App\Models\User_login\Login_model;
use App\Models\Settings\Dropdown_model;
use App\Models\User_login\Users_model;
use App\Models\SCORM\Scorm_client_model;

#[\AllowDynamicProperties]
class Scorm_users extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->is_session_available();
        $this->login_model = new Login_model();
        $this->dropdown_model = new Dropdown_model();
        $this->users_model = new Users_model();
        $this->scorm_client_model = new Scorm_client_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url('my_training'));
            exit();
        }

        $arrayuserlevel = explode(',', $userlevel);
        if (!in_array('6', $arrayuserlevel) && !in_array('4', $arrayuserlevel) && !in_array('5', $arrayuserlevel) && !in_array('44', $arrayuserlevel)) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }
    public function index() //fetch data from users table to display
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['header'] = 'Clients';
        $data['header_link'] = 'SCORM/scorm_client';
        $data['sub_header_1'] = 'Users List';

        $data['form_link1'] = 'SCORM/Scorm_users/users_courses_assign';

        $data['clientid'] = $this->request->getVar('id_c');
        $data['usertable'] = $this->scorm_client_model->user_assigned_courses($data['clientid'], 2);
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_users/users_view', $data);
        echo view('templates/footer_view');
    }
    public function users_courses_assign()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $data = [];
        $data['header'] = 'Clients';
        $data['header_link'] = 'SCORM/scorm_client';
        $data['sub_header_1'] = 'Users List';
        $data['sub_header_1_link'] = 'SCORM/Scorm_users';
        $data['sub_header_2'] = 'User Report';
        $data['form_link1'] = 'SCORM/scorm_users/deleteuserscoursedetails';
        helper(['form']);
        if (isset($_POST['userid'])) {
            $data['userid'] = $_POST['userid'];
            $_SESSION['userid'] =  $data['userid'];
        } else if (isset($_GET['userid'])) {
            $data['userid'] = $_GET['userid'];
        } else if (isset($_SESSION['userid'])) {
            $data['userid'] = $_SESSION['userid'];
        }
        if (isset($_POST['client_id'])) {
            $data['client_id'] = $_POST['client_id'];
            $_SESSION['client_id'] =  $data['client_id'];
        } else if (isset($_GET['client_id'])) {
            $data['client_id'] = $_GET['client_id'];
        } else if (isset($_SESSION['client_id'])) {
            $data['client_id'] = $_SESSION['client_id'];
        }
        $data['username'] = $this->scorm_client_model->getUserName($data['userid']);
        $data['getAllCoursesForClient'] = $this->scorm_client_model->getAllCoursesForClient($data['client_id'], 2);
        $data['getAllCoursesForUsers'] = $this->scorm_client_model->getAllCoursesForUserbyType($data['userid'], 2);
        echo view('templates/header_view', $data);
        echo view('SCORM/scorm_users/users_courses_assign_view', $data);
        echo view('templates/footer_view');
    }
    public function add_user_to_course()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        helper(['form']);

        // 1️⃣ Validation rules
        $rules = [
            'course_id' => 'required|integer',
            'scenario'  => 'required|integer',
            'userid'    => 'required',
            'due_date'   => 'permit_empty|valid_date[Y-m-d]',
            'expiry_date' => 'permit_empty|valid_date[Y-m-d]',
        ];

        if (!$this->validate($rules)) {
            // Return JSON with validation errors
            return $this->response->setJSON([
                'status' => 'Error',
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }

        // 2️⃣ Permission check
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);

        if (!in_array('44', $arrayuserlevel) && !in_array('5', $arrayuserlevel)) {
            return $this->response->setJSON([
                'status' => 'Error',
                'message' => 'Unauthorized access'
            ])->setStatusCode(403);
        }

        // 3️⃣ Get POST data safely
        $courseId  = $this->request->getPost('course_id');
        $scenario  = $this->request->getPost('scenario');
        $userId    = $this->request->getPost('userid');
        $dueDate   = $this->request->getPost('due_date') ?? '0000-00-00';
        $expiryDate = $this->request->getPost('expiry_date') ?? '0000-00-00';

        $newData = [
            // 'client_id'       => session()->get('client'),
            'course_id'       => $courseId,
            'scenario_id'     => $scenario,
            'id_user'         => $userId,
            'due_date'        => $dueDate,
            'expiry_date'     => $expiryDate,
            'createdby'       => session()->get('id_user'),
            'createdon'       => time(),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];

        // 4️⃣ Call the model
        $result = $this->scorm_client_model->addusertocourses($newData);

        // 5️⃣ Return JSON for AJAX
        if (!empty($result) && isset($result['status']) && $result['status'] === 'OK') {
            return $this->response->setJSON([
                'status' => 'OK',
                'message' => 'User successfully assigned to the course!'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'Error',
                'message' => 'Some users in the group already exist. The remaining users have been assigned.'
            ]);
        }
    }


    public function add_usergroup_to_course()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        helper(['form']);

        // 1️⃣ Validation rules
        $rules = [
            'group_id'   => 'required|integer',
            'course_id'  => 'required|integer',
            'scenario'   => 'required|integer',
            'due_date'   => 'permit_empty|valid_date[Y-m-d]',
            'expiry_date' => 'permit_empty|valid_date[Y-m-d]',
        ];

        if (!$this->validate($rules)) {
            // Validation failed
            return $this->response->setJSON([
                'status' => 'Error',
                'message' => $this->validator->listErrors()
            ]);
        }

        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);

        if (!in_array('44', $arrayuserlevel) && !in_array('5', $arrayuserlevel)) {
            return $this->response->setJSON([
                'status' => 'Error',
                'message' => 'Unauthorized access'
            ])->setStatusCode(403);
        }

        $groupId    = $this->request->getPost('group_id');
        $courseId   = $this->request->getPost('course_id');
        $scenario   = $this->request->getPost('scenario');
        $dueDate    = $this->request->getPost('due_date') ?? '0000-00-00';
        $expiryDate = $this->request->getPost('expiry_date') ?? '0000-00-00';

        $usergroup = $this->scorm_client_model->getUsergroup($groupId);

        if (empty($usergroup)) {
            return $this->response->setJSON([
                'status' => 'Error',
                'message' => 'No users found in this group'
            ]);
        }

        // 4️⃣ Prepare data for the model
        $newData = [
            'client_id'       => session()->get('client'),
            'course_id'       => $courseId,
            'stage'           => 0,
            'role'            => 0,
            'due_date'        => $dueDate,
            'expiry_date'     => $expiryDate,
            'scenario_id'     => $scenario,
            'id_user'         => $usergroup, // array of user IDs
            'createdby'       => session()->get('id_user'),
            'createdon'       => time(),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];

        $result = $this->scorm_client_model->addusergrouptocourses($newData);

        if (!empty($result) && isset($result['status']) && $result['status'] === 'OK') {
            return $this->response->setJSON([
                'status' => 'OK',
                'message' => 'Users successfully assigned to the course!'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'Error',
                'message' => 'Users in the group already exist.'
            ]);
        }
    }

    // public function add_course_to_user()
    // {
    //      if ($response =  $this->requireRole(['6', '44','5', '4'])) {
    //         return $response;
    //     }
    //     $due_date = isset($_POST['due_date']) ? $_POST['due_date'] : '0000-00-00';
    //     $newData = [
    //         'course_id' => $_POST['course_id'],
    //         'due_date' => $due_date,
    //         'id_user' => $_POST['userid'],
    //         'createdby' => session()->get('id_user'),
    //         'createdon' => time(),
    //         'last_updated_by' =>  session()->get('id_user'),
    //         'last_updated_on' => time(),
    //     ];
    //     $result = $this->scorm_client_model->addcoursetousers($newData);
    //     echo json_encode($result);
    // }
    public function add_course_to_user()
    {
        if ($response = $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }

        $session = session();
        $loggedInUserId = $session->get('id_user');
        $clientId = $session->get('client');

        // Safe input
        $course_ids = $this->request->getPost('course_id');
        $targetUserId = $this->request->getPost('userid');
        $due_date = $this->request->getPost('due_date') ?? '0000-00-00';

        if (empty($course_ids) || !is_array($course_ids) || empty($targetUserId) || !is_numeric($targetUserId)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid input']);
        }

        // Fetch target user
        $user = $this->users_model->getUserById($targetUserId);

        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User not found']);
        }

        // BOLA Protection
        if ($user->client_id != $clientId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized access']);
        }

        // // 🚫 Role restriction
        // if (!$this->canAssignCourse($loggedInUserId, $targetUserId)) {
        //     return $this->response->setJSON(['status' => 'error', 'message' => 'Permission denied']);
        // }

        $newData = [
            'course_id' => $course_ids,
            'due_date' => $due_date,
            'id_user' => $targetUserId,
            'createdby' => $loggedInUserId,
            'client_id' => $clientId
        ];

        $result = $this->scorm_client_model->addcoursetousers($newData);

        echo json_encode($result);
    }
    public function deleteuserscoursedetails()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '4'])) {
            return $response;
        }
        $user_assign_id = $_POST['user_assign_id'];
        $newdata = [


            'status' => '0',
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->scorm_client_model->deleteuserscoursedetails($newdata, $user_assign_id);
        if ($result) {
            session()->setFlashdata('success', lang('Messages.Success_0005'));
            return redirect()->to(base_url() . 'SCORM/scorm_users/users_courses_assign');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0001'));
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to(base_url() . 'SCORM/scorm_users/users_courses_assign');
        }
    }
}
