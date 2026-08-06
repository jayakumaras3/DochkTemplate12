<?php

namespace App\Controllers\Support;

use App\Controllers\BaseController;

use App\Models\Support\Support_model;
#[\AllowDynamicProperties]
class Support extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->support_model = new Support_model();
    }
    public function index()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        $data['header'] = 'My Support Tickets';
        $data['form_title'] = 'Support Ticket';
        $data['adminView'] = 2;
        $id_user = session()->get('id_user');

        $data['myTickets'] = $this->support_model->getMyTickets($id_user);

        echo view('templates/header_view', $data);
        echo view('support/support_user_view');
        echo view('templates/footer_view');
    }


    public function createNewTicket()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if ($this->request->getPost()) {
            $newdata = [
                'description' => $this->request->getVar('description'),
                'status' => '1',
                'created_by' => session()->get('id_user'),
                'created_on' => time(),
            ];
            $result = $this->support_model->createTicket($newdata);
            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0046'));
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }

            return redirect()->to(base_url() . 'Support/support');
        }
    }

    public function viewTicketDetails()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if ($this->request->getPost()) {
            $data['header'] = 'My Support Tickets';
            $data['header_link'] = 'Support/support';
            $data['sub_header_1'] = 'Ticket Details';
            $ticketID =  $this->request->getVar('ticketID');
            $id_user = session()->get('id_user');
            $data['ticketDescription'] = $this->support_model->getTicketsDetails($ticketID,$id_user);
            $data['getTicketReplies']  = $this->support_model->getTicketReplies($ticketID);
            echo view('templates/header_view', $data);
            echo view('support/support_ticket_view');
            echo view('templates/footer_view');
        } else {
            return redirect()->to(base_url() . 'Support/support');
        }
    }

    public function replyTicket()
    {
        if ($response =  $this->requireRole(['3'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if ($this->request->getPost()) {
            $ticketID =  $this->request->getVar('ticketID');
            $replytype =  $this->request->getVar('replytype');
            $newdata = [
                'status' => $replytype,
            ];
            $result = $this->support_model->updateTicketStatus($newdata, $ticketID);
            $replies = [
                'ticket_id' => $ticketID,
                'replies' => $this->request->getVar('replies'),
                'status' => '1',
                'created_by' => session()->get('id_user'),
                'created_on' => time(),
            ];
            $result = $this->support_model->replyTicketData($replies);

            if ($result) {
                session()->setFlashdata('success', lang('Messages.Success_0008'));
            } else {
                session()->setFlashdata('error', lang('Messages.Error_0001'));
                session()->setFlashdata('alert-class', 'alert-danger');
            }

            return redirect()->to(base_url() . 'Support/support');
        }
    }

    public function admin_support()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        
        $data = [];
        $data['header'] = 'Admin Support';
        $data['adminView'] = session()->get('client');
        if ($data['adminView'] == 1) {
            $data['myTickets'] = $this->support_model->getAllOpenTickets();
            echo view('templates/header_view', $data);
            echo view('support/support_admin_view');
            echo view('templates/footer_view');
        } else {
            return redirect()->to(base_url() . '/Support/support');
        }
    }

    public function AdminviewTicketDetails()
    {
        if ($response =  $this->requireRole(['6'])) {
            return $response;
        }
        $data = [];
        helper(['form']);
        if ($this->request->getPost()) {
            $data['header'] = 'Admin Support';
            $data['header_link'] = 'Support/support/admin_support';
            $data['sub_header_1'] = 'Ticket Details';
            $ticketID =  $this->request->getVar('ticketID');
            $data['ticketDescription'] = $this->support_model->getTicketsDetails($ticketID);
            $data['getTicketReplies']  = $this->support_model->getTicketReplies($ticketID);
            echo view('templates/header_view', $data);
            echo view('support/support_ticket_view');
            echo view('templates/footer_view');
        } else {
            return redirect()->to(base_url() . '/Support/support');
        }
    }
}
