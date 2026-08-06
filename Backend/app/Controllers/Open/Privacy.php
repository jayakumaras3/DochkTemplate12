<?php

namespace App\Controllers\Open;

use App\Controllers\BaseController;


class Privacy extends BaseController
{
    private $db;

    public function __construct()
    {
       
    }
    public function index()
    {
        $data = [];
        helper(['form']);
        helper(['form']);
    
        $data['header'] = 'Privacy';
        echo view('landing/header', $data);
        echo view('privacy/privacy-policy', $data);
        echo view('landing/footer');
    }
    public function privacy()
    {
        $data = [];
        helper(['form']);
        helper(['form']);
        echo view('landing/header', $data);
        echo view('privacy/privacy-policy', $data);
        echo view('landing/footer');
    }
    public function term()
    {
        $data = [];
        helper(['form']);
        helper(['form']);
        echo view('landing/header', $data);
        echo view('privacy/terms-and-conditions', $data);
        echo view('landing/footer');
        
    }
}
