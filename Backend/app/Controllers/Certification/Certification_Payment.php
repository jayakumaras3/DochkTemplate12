<?php

namespace App\Controllers\Certification;

use App\Controllers\BaseController;
use Razorpay\Api\Api;
use App\Models\Certification\CertificationPaymentModel;
use App\Models\Certification\Certification_model;

class Certification_Payment extends BaseController
{
    public function __construct()
    {
        $this->CertificationPaymentModel = new CertificationPaymentModel();
        $this->Certification_model = new Certification_model();
    }

    public function createPaymentOrder()
    {
        if (isset($_POST['certificate_id'])) {

            $data['certificate_id'] = $_POST['certificate_id'];
            $_SESSION['certificate_id'] = $data['certificate_id'];
        } elseif (isset($_SESSION['certificate_id'])) {

            $data['certificate_id'] = $_SESSION['certificate_id'];
        } else {

            // session()->setFlashdata(
            //     'error',
            //     lang('Messages.Error_0003')
            // );

            return redirect()->back();
        }
        if (isset($_POST['certificate_name'])) {

            $data['certificate_name'] = $_POST['certificate_name'];
            $_SESSION['certificate_name'] = $data['certificate_name'];
        } elseif (isset($_SESSION['certificate_name'])) {

            $data['certificate_name'] = $_SESSION['certificate_name'];
        } else {

            // session()->setFlashdata(
            //     'error',
            //     lang('Messages.Error_0003')
            // );

            return redirect()->back();
        }

        $paymentModel = new CertificationPaymentModel();

        $pricing = $paymentModel->getCertificatePricingDetails(
            $data['certificate_id']
        );

        if (empty($pricing)) {

            return redirect()->back()
                ->with('error', 'Pricing not configured.');
        }

        /*
    |--------------------------------------------------------------------------
    | Coupon from Session
    |--------------------------------------------------------------------------
    */
        $couponCode = session()->get('coupon_code');

        $coupon = null;

        if (!empty($couponCode)) {

            $coupon = $paymentModel->validateCoupon(
                $data['certificate_id'],
                $couponCode
            );
        }

        helper('discount');

        $amounts = calculateCertificationAmount(
            $pricing,
            $coupon
        );

        $originalAmount = $amounts['originalAmount'];
        $discountAmount = $amounts['discountAmount'];
        $couponAmount   = $amounts['couponAmount'];
        $finalAmount    = $amounts['finalAmount'];
        /*
    |--------------------------------------------------------------------------
    | Create Razorpay Order
    |--------------------------------------------------------------------------
    */
        $api = new Api(
            env('razorpay.key_id'),
            env('razorpay.key_secret')
        );

        $order = $api->order->create([
            'receipt'  => 'CERT_' . time(),
            'amount'   => $finalAmount * 100,
            'currency' => 'INR'
        ]);

        /*
    |--------------------------------------------------------------------------
    | Save Payment Record
    |--------------------------------------------------------------------------
    */
        $paymentData = [

            'certificate_id'     => $data['certificate_id'],
            'user_id'            => session()->get('id_user'),
            'client_id'          => session()->get('client'),

            'price_id'           => $pricing['price_id'],

            'discount_id'        => $pricing['discount_id'] ?? null,

            'coupon_code'        => $couponCode,
            'coupon_amount'      => $couponAmount,

            'razorpay_order_id'  => $order['id'],

            'original_amount'    => $originalAmount,
            'discount_amount'    => $discountAmount,
            'final_amount'       => $finalAmount,

            'payment_status'     => 'PENDING',

            'createdon'          => time()
        ];

        $this->CertificationPaymentModel
            ->createPayment($paymentData);

        /*
    |--------------------------------------------------------------------------
    | Razorpay Checkout View
    |--------------------------------------------------------------------------
    */
        $viewData = [

            'key_id'         => env('razorpay.key_id'),
            'order_id'       => $order['id'],
            'amount'         => $finalAmount,
            'certificate_id' => $data['certificate_id'],
            'certificate_name' => $data['certificate_name']
        ];

        return view(
            'certification/certification_portal/razorpay_checkout',
            $viewData
        );
    }
    public function verifyPayment()
    {
        $paymentId = $this->request->getPost('razorpay_payment_id');
        $orderId = $this->request->getPost('razorpay_order_id');
        $signature = $this->request->getPost('razorpay_signature');
        $certificateId = $this->request->getPost('certificate_id');
        $certificate_name = $this->request->getPost('certificate_name');
        session()->set([
            'payment_id' => $paymentId,
            'certificate_id' => $certificateId,
            'certificate_name' => $certificate_name,
        ]);

        // Update payment status
        $this->CertificationPaymentModel->updatePaymentSuccess(
            $orderId,
            $paymentId,
            $signature
        );

        // Assign certificate to user
        $assignedData = [
            'certificate_id' => $certificateId,
            'user_id' => session()->get('id_user'),
            'client_id' => session()->get('client'),
            'status' => 1,
            'last_updated_by' => session()->get('id_user'),
            'last_updated_on' => time()
        ];

        $this->Certification_model->assignCertificate($assignedData);

        // Clear coupon after successful payment
        session()->remove('coupon_code');

        session()->setFlashdata(
            'success',
            'Payment completed successfully. You can now access the certification assessment.'
        );

        return $this->response->setJSON([
            'status' => true
        ]);
    }
    public function paymentSuccess()
    {
        $data = [
            'payment_id' => session()->get('payment_id'),
            'certificate_id' => session()->get('certificate_id'),
            'certificate_name' => session()->get('certificate_name')
        ];

        return view(
            'certification/certification_portal/payment_success',
            $data
        );
    }
    public function paymentFailed()
    {
        $orderId = $this->request->getPost('razorpay_order_id');

        $this->CertificationPaymentModel->updatePaymentFailed($orderId);
        session()->setFlashdata(
            'error',
            'Payment failed. Please try again.'
        );

        return $this->response->setJSON([
            'status' => true
        ]);
    }
    public function paymentCancelled()
    {
        $orderId = $this->request->getPost('razorpay_order_id');
        // print_r($orderId);
        // exit();

        $this->CertificationPaymentModel
            ->updatePaymentCancelled($orderId);

        session()->setFlashdata(
            'error',
            'Payment cancelled. Please try again.'
        );

        return $this->response->setJSON([
            'status' => true
        ]);
    }
    public function applyCoupon()
    {
        $certificateId = $this->request->getPost('certificate_id');
        $couponCode    = trim($this->request->getPost('coupon_code'));

        $coupon = $this->CertificationPaymentModel
            ->validateCoupon($certificateId, $couponCode);

        if (!$coupon) {

            return redirect()->back()
                ->with('error', 'Invalid coupon code');
        }

        session()->set('coupon_code', $couponCode);

        return redirect()->back()
            ->with('success', 'Coupon applied successfully');
    }
    public function removeCoupon()
    {
        session()->remove([
            'coupon_code',
            'coupon_amount',
            'coupon_discount_id'
        ]);

        return redirect()->back()->with('success', 'Coupon removed successfully.');
    }
}
