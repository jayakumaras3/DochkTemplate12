<?php

namespace App\Controllers\SCORM;

use App\Controllers\BaseController;

use App\Models\Settings\Settings_model;
use App\Models\Settings\Dropdown_model;

#[\AllowDynamicProperties]
class Scorm_settings extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->is_session_available();
        $this->settings_model = new Settings_model();
        $this->dropdown_model = new Dropdown_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url('my_training'));
            exit();
        }

        $arrayuserlevel = explode(',', $userlevel);
        if (!in_array('6', $arrayuserlevel) && !in_array('73', $arrayuserlevel)) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }
    public function index()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $data['cid'] = $this->request->getVar('cid');
        $data['menu_view'] = $this->settings_model->menu_view($data['cid']);
        $data['moduleaccess'] = $this->dropdown_model->getdropdownData(17);

        echo view('templates/header_view', $data);
        echo view('settings/settings_view', $data);
        echo view('templates/footer_view');
    }
    public function clientaccesspermission()
    {
        if ($response =  $this->requireRole(['6', '44', '5', '73'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $client_permission = $_POST['client_permission'];
        $client_id = $_POST['client_id'];
        $menu_id = $_POST['menu_id'];
        $newdata = [
            'menu_id' => $menu_id,
            'client_id' => $client_id,
            'client_permission' => $client_permission,
            'status' => 1,
            'last_updated_by' =>  session()->get('id_user'),
            'last_updated_on' => time(),
        ];
        $result = $this->settings_model->insertClientPermission($newdata);
        echo json_encode($result);
    }
}
