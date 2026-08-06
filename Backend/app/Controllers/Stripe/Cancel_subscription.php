<?php

namespace App\Controllers\Stripe;

use App\Controllers\BaseController;
use App\Models\Support_model;

class Cancel_subscription extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->support_model = new Support_model();

    }
    function index()
    {
        $data = [];
        $data['header'] = 'Cancel Subscription';
        echo view('templates/header_view', $data);
        echo  view('stripe/cancel', $data);
        echo view('templates/footer_view');
    }

    public function createNewTicket()
    {
        $data = [];
        helper(['form']);
        if ($this->request->getPost()) {
            $newdata = [
                'description' => 'CANCEL SUBSCRIPTION: '.$this->request->getVar('description'),
                'status' => '1',
                'createdby' => session()->get('id_user'),
                'createdon' => time(),
                'last_updated_by' =>  session()->get('id_user'),
                'last_updated_on' => time(),
            ];
            $result = $this->support_model->createTicket($newdata);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0046'));
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }
            return redirect()->to(base_url() . 'Support');
        }
    }
}
