<?php

namespace App\Controllers\Others;

use App\Controllers\BaseController;
use App\Models\Others\Tournament_model;

#[\AllowDynamicProperties]
class Tournaments extends BaseController
{
    public function __construct()
    {
        $this->is_session_available();
        $this->Tournament_model = new Tournament_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url('my_training'));
            exit();
        }

        $arrayuserlevel = explode(',', $userlevel);

        if (!in_array('44', $arrayuserlevel)) {

            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }
    public function index() //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        $data['all_tournaments'] = $this->Tournament_model->get_active_tournaments();
        echo view('templates/header_view', $data);
        echo view('others/tour/dashboard', $data);
        echo view('templates/footer_view');
    }

    public function Add_Tournament()
    {
        $data = [];
        helper(['form']);
        echo view('templates/header_view', $data);
        echo view('others/tour/add_tournament_view', $data);
        echo view('templates/footer_view');
    }

    public function Insert_New_Tournament()
    {
        helper(['form', 'url']);
        $tournament_name = $this->request->getPost('tournament_name');
        $start_date = $this->request->getPost('start_date');
        $end_date = $this->request->getPost('end_date');

        $data = [
            'tournament_name' => $tournament_name,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'status' => 1,
        ];

        $inserted = $this->Tournament_model->insert_tournament($data);

        if ($inserted) {
            return redirect()->to(base_url('Others/Tournaments'))->with('success', 'Tournament added successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to add tournament. Please try again.');
        }
    }

    public function Edit_Tournament()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['tour_id'])) {
            $data['tour_id'] = $this->request->getPost('tour_id');

            $data['tournament_details'] = $this->Tournament_model->get_tournament_by_id($data['tour_id']);

            echo view('templates/header_view', $data);
            echo view('others/tour/edit_tournament_view', $data);
            echo view('templates/footer_view');
        } else {
            return redirect()->to(base_url('Others/Tournaments'))->with('error', 'No tournament selected for editing.');
        }
    }

    public function Update_Tournament()
    {
        helper(['form', 'url']);
        $tour_id = $this->request->getPost('tour_id');
        $tournament_name = $this->request->getPost('tournament_name');
        $start_date = $this->request->getPost('start_date');
        $end_date = $this->request->getPost('end_date');
        $status = $this->request->getPost('status');
        $data = [
            'tournament_name' => $tournament_name,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'status' => $status,
        ];

        $updated = $this->Tournament_model->update_tournament($tour_id, $data);
        if ($updated) {
            return redirect()->to(base_url('Others/Tournaments'))->with('success', 'Tournament updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to update tournament. Please try again.');
        }
    }

    public function Teams()
    {
        return redirect()->to(base_url('Others/Tournaments/Scoreboard'));
        $data = [];
        helper(['form']);

        if (isset($_POST['tour_id'])) {
            $data['tour_id'] = $_POST['tour_id'];
            $_SESSION['tour_id'] = $data['tour_id'];
        } elseif (isset($_SESSION['tour_id'])) {
            $data['tour_id'] = $_SESSION['tour_id'];
        }

        if (isset($data['tour_id'])) {
            $data['all_teams'] = $this->Tournament_model->get_teams($data['tour_id']);
            echo view('templates/header_view', $data);
            echo view('others/tour/view_teams', $data);
            echo view('templates/footer_view');
        } else {
            return redirect()->to(base_url('Others/Tournaments'))->with('error', 'No tournament selected for managing teams.');
        }
    }

    public function Add_New_Team()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['tour_id'])) {
            $data['tour_id'] = $_POST['tour_id'];
            $_SESSION['tour_id'] = $data['tour_id'];
        } elseif (isset($_SESSION['tour_id'])) {
            $data['tour_id'] = $_SESSION['tour_id'];
        }

        if (isset($data['tour_id'])) {
            echo view('templates/header_view', $data);
            echo view('others/tour/add_team_view', $data);
            echo view('templates/footer_view');
        } else {
            return redirect()->to(base_url('Others/Tournaments'))->with('error', 'No tournament selected for adding a team.');
        }
    }

    public function Insert_New_Team()
    {
        helper(['form', 'url']);
        $team_name = $this->request->getPost('team_name');
        $player1 = $this->request->getPost('player1');
        $player2 = $this->request->getPost('player2');
        $tour_id = $_SESSION['tour_id'];

        $data = [
            'team_name' => $team_name,
            'player1' => $player1,
            'player2' => $player2,
            'tour_id' => $tour_id,
            'status' => 1,
        ];

        $inserted = $this->Tournament_model->Insert_New_Team_data($data);

        if ($inserted) {
            return redirect()->to(base_url('Others/Tournaments/Teams'))->with('success', 'Team added successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to add team. Please try again.');
        }
    }


    public function Edit_Team()
    {
        $data = [];
        helper(['form']);

        if (isset($_POST['tt_id'])) {
            $data['tt_id'] = $_POST['tt_id'];
            $_SESSION['tt_id'] = $data['tt_id'];
        } elseif (isset($_SESSION['tt_id'])) {
            $data['tt_id'] = $_SESSION['tt_id'];
        }


        if (isset($data['tt_id'])) {

            $data['team_details'] = $this->Tournament_model->get_teams_by_id($data['tt_id']);
            $data['team_members'] = $this->Tournament_model->get_team_members_by_id($data['tt_id']);

            echo view('templates/header_view', $data);
            echo view('others/tour/edit_team_view', $data);
            echo view('templates/footer_view');
        } else {
            return redirect()->to(base_url('Others/Tournaments'))->with('error', 'No team selected for editing.');
        } // Implementation for editing a team can be added here
    }

    public function Update_Team()
    {
        helper(['form', 'url']);
        $tt_id = $this->request->getPost('tt_id');
        $team_name = $this->request->getPost('team_name');
        $player1 = $this->request->getPost('player1');
        $player2 = $this->request->getPost('player2');
        $points = $this->request->getPost('points');
        $status = $this->request->getPost('status');
        $data = [
            'team_name' => $team_name,
            'player1' => $player1,
            'player2' => $player2,
            'points' => $points,
            'status' => $status,
        ];

        $updated = $this->Tournament_model->update_team_data($tt_id, $data);
        if ($updated) {
            return redirect()->to(base_url('Others/Tournaments/Teams'))->with('success', 'Team updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to update team. Please try again.');
        }
    }

    public function Add_Member_to_Team()
    {
        helper(['form', 'url']);
        $tt_id = $this->request->getPost('tt_id');
        $username = $this->request->getPost('username');
        $user_role = $this->request->getPost('user_role');

        $data = [
            'team_id' => $tt_id,
            'username' => $username,
            'user_role' => $user_role,
            'status' => 1,
        ];

        $updated = $this->Tournament_model->Add_Member_to_Team_data($data);

        if ($updated) {
            return redirect()->to(base_url('Others/Tournaments/Edit_Team'))->with('success', 'Team updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to update team. Please try again.');
        }
    }

    public function Delete_team_member()
    {
        helper(['form', 'url']);
        $tu_id = $this->request->getPost('tu_id');
        $data = [
            'status' => 0,
        ];

        $updated = $this->Tournament_model->Delete_member_from_team($data, $tu_id);

        if ($updated) {
            return redirect()->to(base_url('Others/Tournaments/Edit_Team'))->with('success', 'Team member removed successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to update team. Please try again.');
        }
    }


    public function Matches()
    {
        return redirect()->to(base_url('Others/Tournaments/Scoreboard'));

        $data = [];
        helper(['form']);

        if (isset($_POST['tour_id'])) {
            $data['tour_id'] = $_POST['tour_id'];
            $_SESSION['tour_id'] = $data['tour_id'];
        } elseif (isset($_SESSION['tour_id'])) {
            $data['tour_id'] = $_SESSION['tour_id'];
        }

        if (isset($data['tour_id'])) {
            $data['all_matches'] = $this->Tournament_model->get_all_matches($data['tour_id']);
            echo view('templates/header_view', $data);
            echo view('others/tour/view_matches', $data);
            echo view('templates/footer_view');
        } else {
            return redirect()->to(base_url('Others/Tournaments'))->with('error', 'No tournament selected for managing teams.');
        }
    }

    public function Add_New_Match()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['tour_id'])) {
            $data['tour_id'] = $_POST['tour_id'];
            $_SESSION['tour_id'] = $data['tour_id'];
        } elseif (isset($_SESSION['tour_id'])) {
            $data['tour_id'] = $_SESSION['tour_id'];
        }

        if (isset($data['tour_id'])) {
            $data['teams'] = $this->Tournament_model->get_all_teams($data['tour_id']);

            echo view('templates/header_view', $data);
            echo view('others/tour/add_match_view', $data);
            echo view('templates/footer_view');
        } else {
            return redirect()->to(base_url('Others/Tournaments'))->with('error', 'No tournament selected for adding a match.');
        }
    }

    public function Insert_New_Match()
    {
        helper(['form', 'url']);
        $round = $this->request->getPost('round');
        $team_1 = $this->request->getPost('team_1');
        $team_2 = $this->request->getPost('team_2');
        if ($team_1 == $team_2) {
            return redirect()->back()->with('error', 'Team 1 and Team 2 cannot be the same. Please select different teams.');
        }
        $match_date = $this->request->getPost('match_date');
        $slot = $this->request->getPost('slot');
        $tour_id = $_SESSION['tour_id'];

        $data = [
            'tour_id' => $tour_id,
            'round' => $round,
            'team_1' => $team_1,
            'team_2' => $team_2,
            'slot' => $slot,
            'match_date' => $match_date,
            'status' => 1,
        ];

        $inserted = $this->Tournament_model->Insert_New_Match_data($data);

        if ($inserted) {
            return redirect()->to(base_url('Others/Tournaments/Matches'))->with('success', 'Match added successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to add match. Please try again.');
        }
    }

    public function Edit_Match()
    {
        $data = [];
        helper(['form']);

        if (isset($_POST['match_id'])) {
            $data['match_id'] = $_POST['match_id'];
            $_SESSION['match_id'] = $data['match_id'];
        } elseif (isset($_SESSION['match_id'])) {
            $data['match_id'] = $_SESSION['match_id'];
        }

        if (isset($_POST['tour_id'])) {
            $data['tour_id'] = $_POST['tour_id'];
            $_SESSION['tour_id'] = $data['tour_id'];
        } elseif (isset($_SESSION['tour_id'])) {
            $data['tour_id'] = $_SESSION['tour_id'];
        }

        if (isset($data['match_id'])) {

            $data['match_details'] = $this->Tournament_model->get_matches_by_id($data['match_id']);
            //  print_r($data['match_id']);
            //exit();
            $data['teams'] = $this->Tournament_model->get_all_teams($data['tour_id']);

            echo view('templates/header_view', $data);
            echo view('others/tour/edit_match_view', $data);
            echo view('templates/footer_view');
        } else {
            return redirect()->to(base_url('Others/Tournaments'))->with('error', 'No team selected for editing.');
        } // Implementation for editing a team can be added here
    }

    public function Update_Match_details()
    {
        helper(['form', 'url']);
        $match_id = $this->request->getPost('match_id');
        $round = $this->request->getPost('round');
        $team_1 = $this->request->getPost('team_1');
        $team_2 = $this->request->getPost('team_2');
        if ($team_1 == $team_2) {
            return redirect()->back()->with('error', 'Team 1 and Team 2 cannot be the same. Please select different teams.');
        }
        $match_date = $this->request->getPost('match_date');
        $slot = $this->request->getPost('slot');
        $data = [
            'round' => $round,
            'team_1' => $team_1,
            'team_2' => $team_2,
            'match_date' => $match_date,
            'slot' => $slot,
        ];

        $updated = $this->Tournament_model->update_match_data($match_id, $data);

        if ($updated) {
            return redirect()->to(base_url('Others/Tournaments/Matches'))->with('success', 'Match updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to update match. Please try again.');
        }
    }

    public function Update_Match_Points()
    {
        helper(['form', 'url']);
        $match_id = $this->request->getPost('match_id');
        $points = $this->request->getPost('points');
        $who_won = $this->request->getPost('who_won');
        $data = [
            'who_won' => $who_won,
        ];

        $updated = $this->Tournament_model->update_match_data($match_id, $data);
        $data = [
            'who_won' => $who_won,
        ];

        /*   $data2 = [
            'points' => $points,
        ];
        $this->Tournament_model->update_team_points($data2, $who_won); */

        if ($updated) {
            return redirect()->to(base_url('Others/Tournaments/Matches'))->with('success', 'Match status updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to update match points. Please try again.');
        }
    }

    public function Scoreboard()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['tour_id'])) {
            $data['tour_id'] = $_POST['tour_id'];
            $_SESSION['tour_id'] = $data['tour_id'];
        } elseif (isset($_SESSION['tour_id'])) {
            $data['tour_id'] = $_SESSION['tour_id'];
        }

        if (isset($data['tour_id'])) {
            $data['all_matches'] = $this->Tournament_model->get_all_matches($data['tour_id']);
            $data['all_teams'] = $this->Tournament_model->get_teams($data['tour_id']);
            $data['tournament_details'] = $this->Tournament_model->get_tournament_by_id($data['tour_id']);
            echo view('templates/header_view', $data);
            echo view('others/tour/view_scoreboard', $data);
            echo view('templates/footer_view');
        } else {
            return redirect()->to(base_url('Others/Tournaments'))->with('error', 'No tournament selected for managing teams.');
        }
    }
}
