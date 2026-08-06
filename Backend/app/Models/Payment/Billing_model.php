<?php

namespace App\Models\Payment;

use CodeIgniter\Model;

class billing_model extends Model
{
    public function __construct()
    {
        $this->db = db_connect(); // Loading database

    }
    public function eventExists(string $eventId): bool
    {
        return $this->db->table('stripe_webhook_events')
            ->where('event_id', $eventId)
            ->countAllResults() > 0;
    }

    public function storeEventId(string $eventId): bool
    {
        return $this->db->table('stripe_webhook_events')->insert([
            'event_id'    => $eventId,
            'created_on' => time(),
        ]);
    }
    public function getMyBilling($userid)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('billing as bill');
        $builder->select('bill.*,u.name as fullname');
        $builder->join('users as u', 'u.id_user = bill.user_id', 'left');
        $builder->where('bill.user_id', $userid);
        $builder->where('bill.status', 1);
        $builder->orderBy('bill.expire_date', 'DESC');
        $builder->limit(5);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getDiscount($username)
    {
        $db = \Config\Database::connect();
        $email = $username;
        $parts = explode("@", $email);
        if (count($parts) == 2) {
            $username = $parts[0];
            $domain = $parts[1];
        } else {
            $username = '';
            $domain = '';
        }
        $builder = $this->db->table('discount as d');
        $builder->select('d.*');
        $builder->like('d.email', $domain);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getPartnerDiscount($partner_code)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('client as p');
        $builder->select('p.discount');
        $builder->where('p.code', $partner_code);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function addSubscribe($newdata)
    {
        $builder = $this->db->table('billing');
        $builder->insert($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function subscriptionExists(string $stripeSubscriptionId): bool
    {
        return $this->db->table('billing')
            ->where('authorize_code', $stripeSubscriptionId)
            ->where('mode', 'subscription')
            ->countAllResults() > 0;
    }
    public function updateSubscriptionByStripeId(string $stripeSubId, array $data): bool
    {
        return $this->db->table('billing')
            ->where('authorize_code', $stripeSubId)   // WHERE condition
            ->where('mode', 'subscription')           // safety
            ->update($data);
    }
    /**
     * Mark one-time payment as PAID using Stripe PaymentIntent ID
     */
    public function updateByPaymentIntent(string $paymentIntentId, array $data): bool
    {
        return $this->db->table('billing')
            ->where('payment_intent_id', $paymentIntentId) //  WHERE
            ->where('mode', 'payment')                     // safety
            ->update($data);
    }
    public function grantUserProductByIntent(string $paymentIntentId): bool
    {
        // No separate table needed
        // Optionally, you can fetch the purchase for logging or notifications
        $purchase = $this->db->table('billing')
            ->where('payment_intent_id', $paymentIntentId)
            ->where('status', 1)
            ->get()
            ->getRowArray();

        return $purchase ? true : false;
    }



    public function deleteSubscribe($newdata, $bill_id)
    {
        $builder = $this->db->table('billing');
        $builder->where('bill_id', $bill_id);
        $builder->update($newdata);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function addStripeData($stripeData)
    {
        $builder = $this->db->table('stripe_logs');
        $builder->insert($stripeData);
        $data = $builder->get()->getResultArray();
        return $data;
    }

    public function checksessionval($session_id, $user_id)
    {
        $db = \Config\Database::connect();
        $builder = $this->db->table('billing');
        $builder->select('*');
        //  $builder->where('user_id', $user_id);
        $builder->where('authorize_code', $session_id);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getprice($lookup_key)
    {
        $builder = $this->db->table('price as p');
        $builder->select('p.*');
        $builder->where('p.product_id', $lookup_key);
        $builder->where('status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function updateClientByCoupon($postdata)
    {
        $builder = $this->db->table('dropdown_users as u');
        $builder->set('u.fk_id_d', $postdata[0]['id_c']);
        $builder->where('u.fk_id_dc', 1);
        $builder->where('u.fk_id_user', session()->get('id_user'));
        $builder->where('status', 1);
        $builder->update();
        $data = $builder->get()->getResultArray();
        if (!empty($data)) {
            $builder = $this->db->table('users as u');
            $builder->set('u.client_id', $postdata[0]['id_c']);
            $builder->set('u.partner_id', $postdata[0]['partner_code']);
            $builder->set('u.partner_code', $postdata[0]['code']);
            $builder->where('u.id_user', session()->get('id_user'));
            $builder->where('valid', 1);
            $builder->update();
            $data = $builder->get()->getResultArray();
            $clientdata['client'] = $postdata[0]['id_c'];
            $clientdata['partner_code'] = $postdata[0]['code'];
            session()->set($clientdata);
            // print_r(session()->get('client'));
            // exit();
            return $data;
        }
    }
    function getbillingdata($crid, $userid)
    {
        $client = session()->get('client');
        $builder = $this->db->table('billing as bill');
        $builder->select('bill.*,u.name as fullname');
        $builder->join('users as u', 'u.id_user = bill.user_id', 'left');
        $builder->where('bill.user_id', $userid);
        $builder->where('bill.product_id', $crid);
        $builder->where('bill.authorize_code !=', '');
        $builder->where('bill.client_id', $client);
        $builder->where('bill.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    function getsubscribebillingdata($userid)
    {
        $client = session()->get('client');
        $builder = $this->db->table('billing as bill');
        $builder->select('bill.*,u.name as fullname');
        $builder->join('users as u', 'u.id_user = bill.user_id', 'left');
        $builder->where('bill.user_id', $userid);
        $builder->where('bill.product_id !=', '');
        $builder->where('bill.authorize_code !=', '');
        $builder->where('bill.mode', 'subscription');
        $builder->where('bill.client_id', $client);
        $builder->where('bill.status', 1);
        $data = $builder->get()->getResultArray();
        return $data;
    }
    public function getUserPurchasedCourses(int $userId): array
    {
        return $this->db->table('billing b')
            ->select('b.*, c.course_name')
            ->join('scorm_courses c', 'c.scourse_id = b.product_id', 'left')
            ->where('b.user_id', $userId)
            ->where('b.status !=', 0)          // only paid
            ->where('b.mode', 'payment')    // one-time purchases
            ->orderBy('b.createdon', 'DESC')
            ->get()
            ->getResultArray();
    }
    public function getclientPurchasedCourses(int $client): array
    {
        return $this->db->table('billing b')
            ->select('b.*, c.course_name,u.name as first_name')
            ->join('scorm_courses c', 'c.scourse_id = b.product_id', 'left')
            ->join('users  u', 'u.id_user = b.user_id', 'left')
            ->where('b.client_id', $client)
            ->where('b.status !=', 0)          // only paid
            ->where('b.mode', 'payment')    // one-time purchases
            ->orderBy('b.createdon', 'DESC')
            ->get()
            ->getResultArray();
    }
    public function revokeUserProductByIntent($paymentIntentId)
    {
        return $this->db->table('billing')
            ->where('payment_intent_id', $paymentIntentId)
            ->update([
                'access_revoked' => 1,          // Mark access as revoked
                'last_updated_on' => time()     // Update timestamp
            ]);
    }

    public function getInvoicedata($paymentIntentId)
    {
        return $this->db->table('billing b')
            ->select('b.*, c.course_name,c.scourse_id')
            ->join('scorm_courses c', 'c.scourse_id = b.product_id', 'left')
            ->where('b.payment_intent_id', $paymentIntentId)
            ->where('b.status !=', 0)          // only paid
            ->where('b.mode', 'payment')    // one-time purchases
            ->orderBy('b.createdon', 'DESC')
            ->get()
            ->getResultArray();
    }
    public function getcoursestatus($paymentIntentId)
    {
        $user = session()->get('id_user');
        $lesson_status = ['completed', 'Completed', 'passed'];

        $builder = $this->db->table('billing b');
        $builder->select('b.*, c.course_name');
        $builder->join('scorm_courses c', 'c.scourse_id = b.product_id', 'left');
        $builder->join('scorm_user_details s', 's.course_id = c.scourse_id', 'left');
        $builder->where('b.payment_intent_id', $paymentIntentId);
        $builder->where('s.student_id', $user);
        $builder->whereIn('s.lesson_status', $lesson_status); // ✅ CHANGE HERE
        $builder->where('s.status', 1);
        $builder->where('b.status', 1);
        $builder->where('b.mode', 'payment');
        $builder->orderBy('b.createdon', 'DESC');

        return $builder->get()->getResultArray();
    }
    function unassigncoursefromuser($course_id)
    {
        $user = session()->get('id_user');
        $builder = $this->db->table('scorm_users_courses_assigned');
        $builder->set('status', 0);
        $builder->where('course_id', $course_id);
        $builder->where('id_user', $user);
        $builder->update();
    }
}
