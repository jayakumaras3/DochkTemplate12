<?php

namespace App\Controllers\Emanual;

use App\Controllers\BaseController;

use App\Models\Emanual\Emanual_product_model;

#[\AllowDynamicProperties]
class Dashboard extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->emanual_product_model = new Emanual_product_model();
    }
    public function index() //fetch data from users table to display
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        $userlevel = session()->get('userlevel');
        $arrayuserlevel  = explode(',', $userlevel);
        if (in_array('6', $arrayuserlevel)) {
            $data['productDetails'] = $this->emanual_product_model->getAllProductDetails();
            echo view('templates/header_view', $data);
            echo view('emanual/dashboard', $data);
            echo view('templates/footer_view');
        } else {
            session()->setFlashdata('error', lang('Messages.Error_0004'));
            return redirect()->to(base_url('my_training'));
        }
    }
}
