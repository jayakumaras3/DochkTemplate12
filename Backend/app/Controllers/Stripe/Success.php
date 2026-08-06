<?php

namespace App\Controllers\Stripe;

use App\Controllers\BaseController;
use App\Models\Billing_model;
use App\Models\Users_model;

class Success extends BaseController
{
    private $db;

    public function __construct()
    {
        // $this->is_session_available();
        $this->billing_model = new Billing_model();
        $this->users_model = new Users_model();
    }
    function index()
    {
        $data = [];
        $data['header'] = 'Payment Confirmation';
        $expire_date = date('Y-m-d', strtotime('+1 year'));
        $lookup_keys = isset($_GET['lookup_keys']) ? base64_decode($_GET['lookup_keys']) : '';
        // exit();
        if (isset($_GET['session_id'])) {
            $session_id = $_GET['session_id'];
            $user_id = session()->get('id_user');
            $sessionval = count($this->billing_model->checksessionval($session_id, $user_id));
            // print_r();
            $getPricedata = $this->billing_model->getprice($lookup_keys);
            if ($sessionval == 0) {
                $newdata = [
                    'user_id' => session()->get('id_user'),
                    'amount' => $getPricedata[0]['price'],
                    'status' => 1,
                    'expire_date' => $expire_date,
                    'authorize_code' =>  $session_id,
                    'createdby' => session()->get('id_user'),
                    'createdon' => time(),
                    'last_updated_by' => session()->get('id_user'),
                    'last_updated_on' => time(),

                ];
                $this->billing_model->addSubscribe($newdata);
                $data['session_id'] =  $session_id;
                $data['sessionval'] = $sessionval;
                $logdata['id_user'] = session()->get('id_user');
                $logdata['activity_type'] = 'Credited';
                $logdata['timestamp'] = time();
                $this->users_model->savelogdata($logdata);
                echo view('templates/header_view', $data);
                echo  view('stripe/success_message', $data);
                echo view('templates/footer_view');
            } else {
                $logdata['id_user'] = session()->get('id_user');
                $logdata['activity_type'] = 'not credited';
                $logdata['timestamp'] = time();
                $this->users_model->savelogdata($logdata);
                echo view('templates/header_view', $data);
                echo  view('stripe/cancel_message', $data);
                echo view('templates/footer_view');
            }
        } else {
            return redirect()->to(base_url('billing'));
        }
    }
    function success()
    {
        $data = [];
        $startDate = time();
        $expire_date = date('Y-m-d', strtotime('+1 year', strtotime($startDate)));
        $newdata = [
            'user_id' => session()->get('id_user'),
            'amount' => 240,
            'status' => 1,
            'expire_date' => $expire_date,
            'createdby' => session()->get('id_user'),
            'createdon' => time(),
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time(),

        ];
        $result = $this->billing_model->addSubscribe($newdata);
        $data['session_id'] = $_GET['session_id'];
        // echo view('templates/header_view', $data);
        // echo  view('stripe/success',$data);
        // echo view('templates/footer_view');
    }
}
