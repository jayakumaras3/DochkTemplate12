<?php

namespace App\Models\Support;

use CodeIgniter\Model;
use PhpParser\Node\Expr\FuncCall;

class Support_model extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = db_connect(); // Loading database
    }

    public function createTicket($newdata)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('support_tickets');
        $builder->insert($newdata);
        // $data = $builder->get()->getResultArray();
        return  true;
    }

    public function getMyTickets($id_user)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('support_tickets as mc');
        $builder->select('mc.*');
        $builder->where('mc.status != ', 0);
        $builder->where('mc.createdby', $id_user);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function getTicketCount($id_user, $type)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('support_tickets as mc');
        $builder->select('mc.*');
        $builder->where('mc.status', $type);
        $builder->where('mc.createdby', $id_user);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getAllNotifications()
    {
        $db = \Config\Database::connect();
        $todaydate = date("Y-m-d");
        $builder = $this->db->table('notifications as noti');
        $builder->select('noti.*');
        $builder->where('noti.status != ', 0);
        $builder->where('noti.end_date >=', $todaydate);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getAllOpenTickets()
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('support_tickets as mc');
        $builder->select('mc.*, u.name as creator');
        $builder->join('users as u', 'u.id_user = mc.createdby', 'left');
        $builder->where('mc.status <', 5);
        $builder->where('mc.status !=', 0);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getUserTicket($ticketID, $userID)
    {
        return $this->db->table('support_tickets')
            ->where('id', $ticketID)
            ->where('createdby', $userID) // 🔒 ownership check
            ->get()
            ->getRowArray();
    }
    // public function updateTicketStatus($newdata, $ticketID)
    // {
    //     $db = \Config\Database::connect();
    //     $builder = $this->db->table('support_tickets');
    //     $builder->where('id', $ticketID);
    //     $builder->update($newdata);
    //     $data = $builder->get()->getResultArray($newdata);
    //     if (!empty($data)) {
    //         $data['status'] = "OK";
    //     } else {
    //         $data['status'] = "Error";
    //     }
    //     return  $data;
    // }
    public function updateTicketStatus($newdata, $ticketID, $userID)
    {
        $builder = $this->db->table('support_tickets');

        $builder->where('id', $ticketID);
        $builder->where('createdby', $userID); // 🔒 enforce ownership

        return $builder->update($newdata);
    }
    // public function getTicketsDetails($ticketID)
    // {
    //     $db = \Config\Database::connect();
    //     $builder = $this->db->table('support_tickets as mc');
    //     $builder->select('mc.*, u.name as name');
    //     $builder->join('users as u', 'u.id_user = mc.createdby', 'left');
    //     $builder->where('mc.id', $ticketID);
    //     $data = $builder->get()->getResultArray();
    //     return $data;
    // }
    public function getTicketsDetails($ticketID, $userID)
    {
        $builder = $this->db->table('support_tickets as mc');
        $builder->select('mc.*, u.name as name');
        $builder->join('users as u', 'u.id_user = mc.createdby', 'left');

        $builder->where('mc.id', $ticketID);

        //CRITICAL: enforce ownership
        $builder->where('mc.createdby', $userID);

        return $builder->get()->getResultArray();
    }

    public function getTicketReplies($ticketID)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('support_ticket_replies as tr');
        $builder->select('tr.*, u.name as name');
        $builder->join('users as u', 'u.id_user = tr.createdby', 'left');
        $builder->where('tr.ticket_id', $ticketID);
        $builder->where('tr.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    // public function replyTicketData($replies)
    // {
    //     $db = \Config\Database::connect();
    //     $builder = $this->db->table('support_ticket_replies');
    //     $builder->insert($replies);
    //     // $data = $builder->get()->getResultArray();
    //     return true;
    // }

    public function replyTicketData($replies)
    {
        return $this->db->table('support_ticket_replies')->insert($replies);
    }
    public function add_notifications_to_db($notifications)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('notifications');
        $builder->insert($notifications);
        // $data = $builder->get()->getResultArray();
        return  true;
    }

    public function getNotificationDetails($notification_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('notifications as tr');
        $builder->select('tr.*, u.name as name');
        $builder->join('users as u', 'u.id_user = tr.last_updated_by', 'left');
        $builder->where('tr.notification_id', $notification_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function delete_notifications_in_db($notification_id, $notifications)
    {
        $builder = $this->db->table('notifications as tr');
        $builder->where('tr.notification_id', $notification_id);
        $builder->where('tr.status', 1);
        $builder->update($notifications);
        $data = $builder->get()->getResultArray();
        return $data;
    }
}
