<?php

namespace App\Controllers\Others;

use App\Controllers\BaseController;
use App\Models\Others\Tournament_model;

#[\AllowDynamicProperties]
class Tour_user extends BaseController
{
    public function __construct()
    {
        $this->Tournament_model = new Tournament_model();
    }

    public function index() //fetch data from users table to display
    {
        $data = [];
        helper(['form']);
        $data['all_tournaments'] = $this->Tournament_model->get_active_tournaments();
        echo view('templates/header_no_login', $data);
        echo view('others/tour/dashboard_user', $data);
        echo view('templates/footer_view');
    }


    public function scores()
    {
        $data = [];
        helper(['form']);
        if (isset($_POST['tour_id'])) {
            $data['tour_id'] = $_POST['tour_id'];
            $data['tournament_name'] = $_POST['tournament_name'];
        } else  {
            return redirect()->to(base_url('Others/Tour_user'))->with('error', 'No tournament selected for managing teams.');
        }

        if (isset($data['tour_id'])) {
            $data['all_matches'] = $this->Tournament_model->get_all_matches($data['tour_id']);
            $data['all_teams'] = $this->Tournament_model->get_teams($data['tour_id']);
            $data['tournament_details'] = $this->Tournament_model->get_tournament_by_id($data['tour_id']);
            echo view('templates/header_no_login', $data);
            echo view('others/tour/view_scoreboard_user', $data);
            echo view('templates/footer_view');
        } else {
            return redirect()->to(base_url('Others/Tour_user'))->with('error', 'No tournament selected for managing teams.');
        }
    }
}
