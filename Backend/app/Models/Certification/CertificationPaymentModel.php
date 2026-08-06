<?php

namespace App\Models\Certification;

use CodeIgniter\Model;

class CertificationPaymentModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'certification_payments';
    protected $primaryKey = 'cp_id';
    protected $allowedFields = [
        'certificate_id',
        'account_id',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'original_amount',
        'discount_amount',
        'final_amount',
        'payment_status',
        'created_on',
        'last_updated_on'
    ];

    public function getCertificatePrice($certificateId)
    {
        return $this->db->table('certification_price_mapping cpm')
            ->select('pm.*')
            ->join(
                'price_master pm',
                'pm.price_id = cpm.price_id'
            )
            ->where('cpm.certificate_id', $certificateId)
            ->where('cpm.is_active', 1)
            ->get()
            ->getRowArray();
    }

    public function getCertificateDiscount($certificateId)
    {
        return $this->db->table('certification_discount_mapping cdm')
            ->select('dm.*')
            ->join(
                'discount_master dm',
                'dm.discount_id = cdm.discount_id'
            )
            ->where('cdm.certificate_id', $certificateId)
            ->where('cdm.is_active', 1)
            ->get()
            ->getRowArray();
    }

    public function getCertificatePricingDetails($certificateId)
    {
        return $this->db->table('certification_price_mapping cpm')
            ->select('
            pm.price_id,
            pm.amount,
            dm.discount_id,
            dm.discount_code,
            dm.discount_type,
            dm.discount_value
        ')
            ->join(
                'price_master pm',
                'pm.price_id = cpm.price_id'
            )
            ->join(
                'certification_discount_mapping cdm',
                'cdm.certificate_id = cpm.certificate_id
             AND cdm.is_active = 1',
                'left'
            )
            ->join(
                'discount_master dm',
                'dm.discount_id = cdm.discount_id',
                'left'
            )
            ->where('cpm.certificate_id', $certificateId)
            ->where('cpm.is_active', 1)
            ->get()
            ->getRowArray();
    }
    function createPayment($paymentData)
    {
        $this->db->table('certification_payments')->insert($paymentData);
    }
    public function updatePaymentSuccess(
        $orderId,
        $paymentId,
        $signature
    ) {
        return $this->where('razorpay_order_id', $orderId)
            ->set([
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature'  => $signature,
                'payment_status'      => 'PAID',
                'last_updated_on'     => time()
            ])
            ->update();
    }

    public function updatePaymentFailed($orderId)
    {
        return $this->db->table('certification_payments')
            ->where('razorpay_order_id', $orderId)
            ->update([
                'payment_status' => 'FAILED',
                'last_updated_on' => time()
            ]);
    }
    public function updatePaymentCancelled($orderId)
    {
        return $this->db->table('certification_payments')
            ->where('razorpay_order_id', $orderId)
            ->where('payment_status', 'PENDING')
            ->update([
                'payment_status' => 'CANCELLED',
                'last_updated_on' => time()
            ]);
    }
    public function getPurchasedCertificates($userId)
    {
        return $this->db->table('certificates_assigned_users')
            ->select('certificate_id')
            ->where('user_id', $userId)
            ->where('status !=', 0)
            ->get()
            ->getResultArray();
    }
    public function validateCoupon($certificateId, $couponCode)
    {
        // print_r($certificateId);
        // print_r($couponCode);
        // exit();

        return $this->db->table('certification_discount_mapping cdm')
            ->select('dm.*')
            ->join(
                'discount_master dm',
                'dm.discount_id = cdm.discount_id'
            )
            ->where('cdm.certificate_id', $certificateId)
            ->where('dm.discount_code', $couponCode)
            ->where('dm.is_active', 1)
            ->get()
            ->getRowArray();
    }
    public function isCertificatePurchased($userId, $certificateId)
    {
        return $this->db->table('certification_payments')
            ->select('payment_id')
            ->where([
                'user_id' => $userId,
                'certificate_id' => $certificateId,
                'payment_status' => 'PAID'
            ])
            ->get()
            ->getRowArray();
    } 
    public function getPaymentHistory($userId)
    {
        return $this->db->table('certification_payments cp')
            ->select('
            cp.payment_id as id,
            cp.final_amount,
            cp.payment_status,
            cp.razorpay_payment_id as transaction_id,
            cp.createdon,
            c.name as cert_name
        ')
            ->join('certificates c', 'c.cert_id = cp.certificate_id')
            ->where('cp.user_id', $userId)
            ->where('cp.client_id', session()->get('client'))
            ->orderBy('cp.payment_id', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Admin-only: payment history across every user/client.
     * Restricted to client_id = 1 by the calling controller.
     */
    public function getAdminPaymentHistory(array $filters = [])
    {
        $builder = $this->db->table('certification_payments cp');

        $builder->select("
            cp.payment_id,
            cp.razorpay_order_id,
            cp.razorpay_payment_id,
            cp.final_amount,
            cp.payment_status,
            cp.createdon,
            cp.last_updated_on,
            u.name as user_first_name,
            u.last_name as user_last_name,
            u.email as user_email,
            cl.client_name,
            c.name as cert_name
        ");

        $builder->join('users u', 'u.id_user = cp.user_id', 'left');
        $builder->join('certificates c', 'c.cert_id = cp.certificate_id', 'left');
        $builder->join('client cl', 'cl.id_c = cp.client_id', 'left');

        if (!empty($filters['user_name'])) {
            $builder->groupStart()
                ->like('u.name', $filters['user_name'])
                ->orLike('u.last_name', $filters['user_name'])
                ->groupEnd();
        }

        if (!empty($filters['email'])) {
            $builder->like('u.email', $filters['email']);
        }

        if (!empty($filters['certificate_id'])) {
            $builder->where('cp.certificate_id', $filters['certificate_id']);
        }

        if (!empty($filters['client_id'])) {
            $builder->where('cp.client_id', $filters['client_id']);
        }

        if (!empty($filters['payment_status'])) {
            $builder->where('cp.payment_status', $filters['payment_status']);
        }

        if (!empty($filters['date_from'])) {
            $builder->where('cp.createdon >=', strtotime($filters['date_from'] . ' 00:00:00'));
        }

        if (!empty($filters['date_to'])) {
            $builder->where('cp.createdon <=', strtotime($filters['date_to'] . ' 23:59:59'));
        }

        $builder->orderBy('cp.payment_id', 'DESC');

        return $builder->get()->getResultArray();
    }

    public function getAllClientsForFilter()
    {
        return $this->db->table('client')
            ->select('id_c, client_name')
            ->where('status !=', 0)
            ->orderBy('client_name', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getAllCertificatesForFilter()
    {
        return $this->db->table('certificates')
            ->select('cert_id, name')
            ->where('type', 4)
            ->where('status !=', 0)
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();
    }
}
