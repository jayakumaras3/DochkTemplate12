<?php

namespace App\Controllers\Stripe;

use App\Controllers\BaseController;

class Cancel extends BaseController
{

    function index()
    {
        // echo view('templates/header_view', $data);
        echo  view('stripe/Cancel');
        // echo view('templates/footer_view');
    }
}
