<?php

namespace App\Controllers\Help;

use App\Controllers\BaseController;
use App\Models\Help\Help_model;
use App\Models\Etrack\Dashboard_model;

#[\AllowDynamicProperties]

class View extends BaseController
{

    protected $Help_model;
    public function __construct()
    {
        $this->Help_model = new Help_model();
        $this->Dashboard_model = new Dashboard_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url('my_training'));
            exit();
        }
    }

    function index()
    {
        $data = [];
        helper(['form']);
        $data['policies'] = $this->Help_model->get_help_documents();

        if (isset($_POST['emd_id'])) {
            $data['emd_id'] = $_POST['emd_id'];
        } else {
            $data['emd_id'] =  $data['policies'] ? $data['policies'][0]['emd_id'] : 16;
        }
        $data['policy_list'] = $this->Dashboard_model->get_tlc_policy_details($data['emd_id']);
        
        $data['policy_name'] = $this->Dashboard_model->get_tlc_policy_name($data['emd_id']);

        if (isset($_POST['empg_id'])) {
            $data['empg_id'] = $_POST['empg_id'];
            $data['policy_pages'] = $this->Dashboard_model->get_tlc_policy_pages($data['empg_id']);
        } else if (!empty($data['policy_list'])) {
            $data['policy_pages'] = $this->Dashboard_model->get_tlc_policy_pages($data['policy_list'][0]['empg_id']);
            $data['empg_id'] = $data['policy_list'][0]['empg_id'];
        } else {
            $data['policy_pages'] = [];
            $data['empg_id'] = null;
        }
        
        echo view('templates/header_view', $data);
        echo view('help/help_view', $data);
        echo view('templates/footer_view');
    }
}
