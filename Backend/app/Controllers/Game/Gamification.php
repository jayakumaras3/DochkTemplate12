<?php

namespace App\Controllers\Game;

use App\Controllers\BaseController;
use App\Models\SCORM\Scorm_course_model;
use App\Models\Game\Gamification_model;
use App\Models\SCORM\Scorm_user_group_model;
use App\Models\SCORM\Scorm_client_model;

#[\AllowDynamicProperties]

class Gamification extends BaseController
{
    public function __construct()
    {
        $this->Gamification_model = new Gamification_model();
        $this->scorm_course_model = new Scorm_course_model();
        $this->scorm_client_model = new Scorm_client_model();
        $this->scorm_user_group_model = new Scorm_user_group_model();
    }
    function index()
    {
        if ($response =  $this->requireRole(['5', '44', '3'])) {
            return $response;
        }
        $data = [];
        $client = session()->get('client');
        $user = session()->get('id_user');
        $data['course_details'] = $this->Gamification_model->get_live_games($client, 5, $user);
        $data['active_games'] = $this->Gamification_model->get_active_games($client);
        $data['usersGameList'] = $this->Gamification_model->get_users_games($client);
        $data['get_user_games_report'] = $this->Gamification_model->get_user_games_report($client);
        $data['clientCourseddata'] = $this->scorm_course_model->coursenamessearch_view20();
        echo view('templates/header_view');
        echo view('gamification/game_dashboard', $data);
        echo view('templates/footer_view');
    }

    function create_new_game()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $userlevel = session('userlevel');
        $arrayuserlevel = array_map('intval', explode(',', $userlevel) ?? '');
        if (in_array('2010', $arrayuserlevel) || in_array('44', $arrayuserlevel) || in_array('5', $arrayuserlevel)) {
            $data['clientCourseddata'] = $this->scorm_course_model->coursenamessearch_view20();
            echo view('templates/header_view', $data);
            echo view('gamification/create_game', $data);
            echo view('templates/footer_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            return redirect()->to(base_url() . 'Game/gamification');
        }
    }

    function addGame()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $newdata = [
            'client_id' => session()->get('client'),
            'game_name' => $_POST['game_name'],
            'course_id' => $_POST['course_id'],
            'start_on' => $_POST['start_on'],
            'end_on' => $_POST['end_on'],
            'attempts' => $_POST['attempts'],
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => 1
        ];
        $gamification_id = $this->Gamification_model->add_new_game_request($newdata);


        session()->setFlashdata('success', lang('Messages.Success_0011'));
        return redirect()->to(base_url() . 'Game/gamification');
    }

    function view_details()
    {
        if ($response =  $this->requireRole(['5', '44', '3'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if (isset($_POST['game_id'])) {
            $data['game_id'] = $_POST['game_id'];
            $_SESSION['game_id'] = $data['game_id'];
        } else if (isset($_SESSION['game_id'])) {
            $data['game_id'] = $_SESSION['game_id'];
        } else {
            return redirect()->to(base_url() . 'Game/gamification');
        }
        $data['active_games'] = $this->Gamification_model->game_details($data['game_id']);
        $data['clientCourseddata'] = $this->scorm_course_model->coursenamessearch_view20();
        echo view('templates/header_view', $data);
        echo view('gamification/edit_game', $data);
        echo view('templates/footer_view');
    }

    function game_users()
    {
        if ($response =  $this->requireRole(['5', '44', '3'])) {
            return $response;
        }
        $data = [];
        helper(['form']);

        if (isset($_POST['game_id'])) {
            $data['game_id'] = $_POST['game_id'];
            $_SESSION['game_id'] = $data['game_id'];
        } else if (isset($_SESSION['game_id'])) {
            $data['game_id'] = $_SESSION['game_id'];
        } else {
            return redirect()->to(base_url() . 'Game/gamification');
        }

        $client = session()->get('client');
        $data['active_games'] = $this->Gamification_model->game_details($data['game_id']);
        $data['assigneduser'] = $this->Gamification_model->get_game_assigned_users($data['game_id']);
        $data['all_users'] = $this->Gamification_model->get_active_user($client);
        $data['usergroupdata'] = $this->scorm_user_group_model->getCoursegroupdate(4, $client);
        echo view('templates/header_view', $data);
        echo view('gamification/game_users', $data);
        echo view('templates/footer_view');
    }

    function assign_users()
    {
        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $due_date = isset($_POST['due_date']) ? $_POST['due_date'] : ' 0000-00-00';
        $expiry_date = isset($_POST['expiry_date']) ? $_POST['expiry_date'] : ' 0000-00-00';
        $scenario = isset($_POST['scenario']) ? $_POST['scenario'] : ' 0';
        $newData = [
            'course_id' => $_POST['course_id'],
            'id_user' => $_POST['id_user'],
            'game_id' => $_POST['game_id'],
            'due_date' => $due_date,
            'expiry_date' => $expiry_date,
            'scenario_id' => $scenario,
            'createdby' => session()->get('id_user'),
            'createdon' => time(),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $this->Gamification_model->assign_user_to_course($newData);

        session()->setFlashdata('success', lang('Messages.Success_0018'));
        return redirect()->to(base_url() . 'Game/Gamification/game_users');
    }
    function editGame()
    {

        if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        if (isset($_POST['game_id'])) {
            $data['game_id'] = $_POST['game_id'];
            $_SESSION['game_id'] = $data['game_id'];
        } else if (isset($_SESSION['game_id'])) {
            $data['game_id'] = $_SESSION['game_id'];
        } else {
            return redirect()->to(base_url() . 'Game/gamification');
        }
        $newdata = [
            'game_name' => $_POST['game_name'],
            'course_id' => $_POST['course_id'],
            'start_on' => $_POST['start_on'],
            'end_on' => $_POST['end_on'],
            'attempts' => $_POST['attempts'],
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
            'status' => $_POST['status']
        ];
        $this->Gamification_model->update_game_request($newdata, $data['game_id']);
        session()->setFlashdata('success', lang('Messages.Success_0008'));
        return redirect()->to(base_url() . 'Game/gamification');
    }
    public function add_usergroup_to_game()
    {
         if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $group_id = $_POST['user_group_id'];
        $usergroup = $this->scorm_client_model->getUsergroup($group_id);
        $newData = [
            'course_id' => $_POST['course_id'],
            'stage' => 0,
            'role' => 0,
            'game_id' => $_POST['game_id'],
            'due_date' => isset($_POST['due_date']) ? $_POST['due_date'] : '0000-00-00',
            'expiry_date' => isset($_POST['expiry_date']) ? $_POST['expiry_date'] : '0000-00-00',
            'scenario_id' => 0,
            'id_user' => $usergroup,
            'createdby' => session()->get('id_user'),
            'createdon' => time(),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $this->Gamification_model->addusergrouptocourses($newData);
        session()->setFlashdata('success', lang('Messages.Success_0018'));
        return redirect()->to(base_url() . 'Game/Gamification/game_users');
    }
    function unassign_user()
    {
         if ($response =  $this->requireRole(['5', '44'])) {
            return $response;
        }
        $game_user_id = $_POST['game_user_id'];
        $newdata = [
            'status' => 0,
        ];
        $this->Gamification_model->unassign_user($newdata, $game_user_id);
        session()->setFlashdata('success', lang('Messages.Success_0020'));
        return redirect()->to(base_url() . 'Game/Gamification/game_users');
    }
}
