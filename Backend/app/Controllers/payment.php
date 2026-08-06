<?php

namespace App\Controllers;

use Razorpay\Api\Api;

class Payment extends BaseController
{
    public function test()
    {
        $keyId = 'rzp_test_xxxxxxxxx';
        $keySecret = 'xxxxxxxxxxxxxxx';

        $api = new Api($keyId, $keySecret);

        echo "Razorpay SDK Loaded Successfully";
    }
    public function createOrder()
    {
        $api = new Api(
            env('razorpay.key_id'),
            env('razorpay.key_secret')
        );

        $order = $api->order->create([
            'receipt' => 'CERT_' . time(),
            'amount' => 100 * 100, // ₹100
            'currency' => 'INR'
        ]);

        echo "<pre>";
        print_r($order);
    }
}
