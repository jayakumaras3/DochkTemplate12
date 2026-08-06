<?php

namespace App\Controllers\Project_Manage;

use App\Controllers\BaseController;
use App\Models\Project_Manage\PM_Invoices_model;

#[\AllowDynamicProperties]
class Invoices extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->is_session_available();
        $this->PM_Invoices_model = new PM_Invoices_model();
    }
    private function is_session_available()
    {
        $userlevel = session()->get('userlevel');
        if (empty($userlevel)) {
            header('Location:' . base_url());
            exit();
        }

        $arrayuserlevel = explode(',', $userlevel);

        if (!in_array('4', $arrayuserlevel) && !in_array('69', $arrayuserlevel)) {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            header('Location:' . base_url('my_training'));
            exit();
        }
    }
    public function index()
    {
        $data = [];
        helper(['form']);
        $data = [
            'link1' => 'my_training',
            'link1_name' => 'Dashboard',
            'link2' => '',
            'link2_name' => '',
            'link3_name' => 'Invoices'
        ];
        if (isset($_POST['year'])) {
            $data['selected_year'] = $_POST['year'];
            $_SESSION['selected_year'] = $data['selected_year'];
        } elseif (isset($_SESSION['selected_year'])) {
            $data['selected_year'] = $_SESSION['selected_year'];
        } else {
            $data['selected_year'] = date('Y');
        }
        if (isset($_POST['month'])) {
            $data['selected_month'] = $_POST['month'];
            $_SESSION['selected_month'] = $data['selected_month'];
        } elseif (isset($_SESSION['selected_month'])) {
            $data['selected_month'] = $_SESSION['selected_month'];
        } else {
            $data['selected_month'] = date('m');
        }

        $data['invoices'] = $this->PM_Invoices_model->get_invoices($data['selected_year'], $data['selected_month']);
        $data['current_user'] = session()->get('id_user');
        echo view('templates/header_view', $data);
        echo view('project_management/invoice_dashboard_view', $data);
        echo view('templates/footer_view');
    }
}
