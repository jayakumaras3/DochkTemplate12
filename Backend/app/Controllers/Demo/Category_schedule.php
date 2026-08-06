<?php

namespace App\Controllers;

use App\Models\Demo\Demos_model;

#[\AllowDynamicProperties]
class Category_schedule extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->demos_model = new Demos_model();
    }
    public function index()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $getallcat = $this->demos_model->getallcatdata();
        $data['getallcat'] = isset($getallcat['showactiveprojects_vc']) ? $getallcat['showactiveprojects_vc'] : '';
        return view('demos/category_schedule_view', $data);
    }
    public function demo_loader()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $data = [];
        return view('demos/demo_loader_view', $data);
    }
    public function popup_ws()
    {
        if ($response =  $this->requireRole(['8'])) {
            return $response;
        }
        $data = [];
        $demoid =  $this->request->getVar('demoid');
        $data['result'] = $this->demos_model->wsshowdetails($demoid);
        echo view('demos/popup_ws_view', $data);
    }
}
