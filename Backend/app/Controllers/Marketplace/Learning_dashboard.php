<?php

namespace App\Controllers\Marketplace;

use App\Controllers\BaseController;
use App\Models\Marketplace\M_Dashboard_model;
use App\Models\User_login\Users_model;
use App\Models\SCORM\Scorm_user_group_model;

#[\AllowDynamicProperties]
class Learning_dashboard extends BaseController
{
    public function __construct()
    {
        $this->is_session_available();
        $this->M_Dashboard_model = new M_Dashboard_model();
        $this->users_model = new Users_model();
        $this->scorm_user_group_model = new Scorm_user_group_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url());
            exit();
        }
    }
    public function index()
    {
        if ($response =  $this->requireRole(['5', '44', '3'])) {
            return $response;
        }
        $data = [];
        $client_id = session()->get('client');
        $data['client_id'] = $client_id;
        if (isset($_POST['mp_language'])) {
            $_SESSION['mp_language'] = $_POST['mp_language'];
        } elseif (isset($_SESSION['mp_language'])) {
        } else {
            $_SESSION['mp_language'] = "All";
        }

        if (isset($_POST['cat_list'])) {
            $_SESSION['cat_list'] = $_POST['cat_list'];
        } elseif (isset($_SESSION['cat_list'])) {
        } else {
            $_SESSION['cat_list'] = 126;
        }

        if ($_SESSION['cat_list'] == 100) {
            $_SESSION['cat_list'] = 126;
        }
        if ($_SESSION['mp_language'] == "") { 
            $_SESSION['mp_language'] = "All";
        }
        $data['header_title'] = "Learning Plan Dashboard";
        $data['header_link'] = "marketplace/learning_dashboard";
        $data['admin'] = "Create New Learning Plan";
        $data['cat_list'] = $_SESSION['cat_list'];
        $data['mp_language'] = $_SESSION['mp_language'];
        $data['type'] = '2';
        $client_id = session()->get('client');
        // $data['get_courses'] = $this->M_Dashboard_model->get_courses($client_id, 0, $_SESSION['mp_language'], $data['cat_list']);
        $userlevel = session()->get('userlevel');
        $arrayuserlevel = explode(',', $userlevel);
        $client = session()->get('client');

        if ((in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel))) {

            $data['get_learning_plan'] = $this->M_Dashboard_model->get_traninerlearning_plan_courses();
        } else {
            $data['get_learning_plan'] = $this->M_Dashboard_model->get_learning_plan_courses();
        }
        echo view('templates/header_view', $data);
        echo view('marketplace/lp_left_menu', $data);
        echo view('marketplace/lp_dashboard', $data);
        echo view('templates/footer_view', $data);
    }

    public function learning_courses()
    {
        if ($response =  $this->requireRole(['5', '44', '3'])) {
            return $response;
        }
        if (isset($_POST['mp_id'])) {
            session()->set('mp_id', $_POST['mp_id']);
        } elseif (!session()->get('mp_id')) {
            return redirect()->to(base_url('marketplace/learning_dashboard'));
        }
        if (isset($_POST['type'])) {
            session()->set('type', $_POST['type']);
        }

        return redirect()->to(base_url('marketplace/learning_plan_detail'));
    }
    public function add_users_to_learning_plan_view()
    {
         if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        if (isset($_POST['mp_id'])) {
            $data['mp_id'] = $_POST['mp_id'];
            $_SESSION['mp_id'] = $_POST['mp_id'];
        } elseif (isset($_SESSION['mp_id'])) {
            $data['mp_id'] = $_SESSION['mp_id'];
        } else {
            header('Location:' . base_url('marketplace/learning_dashboard'));
            exit();
        }
        if (isset($_POST['type'])) {
            $data['type'] = $_POST['type'];
            $_SESSION['type'] = $_POST['type'];
        } elseif (isset($_SESSION['type'])) {
            $data['type'] = $_SESSION['type'];
        } else {
            $data['type'] = 1;
        }
        if ($data['type'] == 1) {
            $data['header_title'] = "Marketplace Dashboard";
            $data['header_link'] = "marketplace/dashboard";
            $data['admin'] = "Create New Marketplace";
        } else {
            $data['header_title'] = "Learning Plan Dashboard";
            $data['header_link'] = "marketplace/learning_dashboard";
            $data['admin'] = "Create New Learning Plan";
        }
        $data['admin_page'] = "marketplace/admin";
        $client_id = session()->get('client');
        $data['getUserclientlist'] = $this->users_model->getUserclientlist($client_id);
        $data['usergroupdata'] = $this->scorm_user_group_model->getCoursegroupdate(4, $client_id);
        $data['get_assigned_users'] = $this->M_Dashboard_model->get_assigned_users_learning_plan($data['mp_id']);
        $data['row'] = $this->M_Dashboard_model->get_marketplace_details($data['mp_id']);
        echo view('templates/header_view', $data);
        echo view('marketplace/lp_add_users', $data);
        echo view('templates/footer_view', data: $data);
    }
    public function add_users_to_learning_plan()
    {
         if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        if (isset($_POST['mp_id'])) {
            $data['mp_id'] = $_POST['mp_id'];
            $_SESSION['mp_id'] = $_POST['mp_id'];
        } elseif (isset($_SESSION['mp_id'])) {
            $data['mp_id'] = $_SESSION['mp_id'];
        } else {
            header('Location:' . base_url('marketplace/learning_dashboard'));
            exit();
        }
        if (isset($_POST['type'])) {
            $data['type'] = $_POST['type'];
            $_SESSION['type'] = $_POST['type'];
        } elseif (isset($_SESSION['type'])) {
            $data['type'] = $_SESSION['type'];
        } else {
            $data['type'] = 1;
        }
        $user_ids = isset($_POST['userid']) ? $_POST['userid'] : [];
        foreach ($user_ids as $user_id) {
            $data = [
                'mp_id' => $data['mp_id'],
                'user_id' => $user_id,
                // 'due_date' => isset($_POST['due_date']) ? $_POST['due_date'] : null,
                'client_id' => session()->get('client'),
                'type' => $data['type'],
                'last_updated_by' => session()->get('id_user'),
                'last_updated_on' => time(),
                'status' => 1
            ];

            $this->M_Dashboard_model->enroll_user_to_learning_plan($user_id, $data);
        }

        echo json_encode(['status' => 'OK']);
    }
    public function add_usergroup_to_learning_plan()
    {
         if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        if (isset($_POST['mp_id'])) {
            $data['mp_id'] = $_POST['mp_id'];
            $_SESSION['mp_id'] = $_POST['mp_id'];
        } elseif (isset($_SESSION['mp_id'])) {
            $data['mp_id'] = $_SESSION['mp_id'];
        } else {
            header('Location:' . base_url('marketplace/learning_dashboard'));
            exit();
        }
        if (isset($_POST['type'])) {
            $data['type'] = $_POST['type'];
            $_SESSION['type'] = $_POST['type'];
        } elseif (isset($_SESSION['type'])) {
            $data['type'] = $_SESSION['type'];
        } else {
            header('Location:' . base_url('marketplace/learning_dashboard'));
            exit();
        }
        $group_id = isset($_POST['group_id']) ? $_POST['group_id'] : [];

        $this->M_Dashboard_model->enroll_usergroup_to_learning_plan($group_id, $data);
        echo json_encode(['status' => 'OK']);
    }
    public function delete_users()
    {
         if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        if (isset($_POST['mp_id'])) {
            $data['mp_id'] = $_POST['mp_id'];
            $_SESSION['mp_id'] = $_POST['mp_id'];
        } elseif (isset($_SESSION['mp_id'])) {
            $data['mp_id'] = $_SESSION['mp_id'];
        } else {
            header('Location:' . base_url('marketplace/learning_dashboard'));
            exit();
        }
        $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : 0;

        $this->M_Dashboard_model->delete_user_from_learning_plan($user_id, $data['mp_id']);
        session()->setFlashdata('success', lang('Messages.Success_0020'));
        header('Location:' . base_url('marketplace/Learning_dashboard/add_users_to_learning_plan_view'));
        exit();
    }
}
