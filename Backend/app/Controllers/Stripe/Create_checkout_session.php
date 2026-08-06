<?php

namespace App\Controllers\Stripe;

use App\Controllers\BaseController;

require_once 'vendor/autoload.php';

class Create_checkout_session extends BaseController
{

    function index()
    {
        // $lookup_key = $_POST['lookup_key'];
        if (isset($_POST['lookup_key'])) {
            $lookup_key = $_POST['lookup_key'];
            $_SESSION['lookup_key'] =  $lookup_key;
        } else if (isset($_GET['lookup_key'])) {
            $lookup_key = $_GET['lookup_key'];
        } else if (isset($_SESSION['lookup_key'])) {
            $lookup_key = $_SESSION['lookup_key'];
        }
    // $stripeSecretKey = 'sk_test_51OP4a6GTotN7O5QoVCyOFu3B0jcdXS12P1bqe0LdeZqosOCvbyYLaYeSQf0JMDgrxZRtPG10pQGYTrNnENJXvo4A00VWid0ly5';
    $stripeSecretKey = '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';
        \Stripe\Stripe::setApiKey($stripeSecretKey);

        header('Content-Type: application/json');

        $YOUR_DOMAIN = base_url('Stripe');
        $cancel_DOMAIN = base_url('Billing');
        // echo $YOUR_DOMAIN;
        // exit();
        try {
            $prices = \Stripe\Price::all([
                // retrieve lookup_key from form data POST body
                'lookup_keys' => [$lookup_key],
                'expand' => ['data.product']
            ]);

            $checkout_session = \Stripe\Checkout\Session::create([
                'line_items' => [[
                    'price' => $prices->data[0]->id,
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => $YOUR_DOMAIN . '/Success?session_id={CHECKOUT_SESSION_ID}&lookup_keys='.base64_encode($lookup_key),
                'cancel_url' => $cancel_DOMAIN,
            ]);
            // echo $checkout_session->url;
            // print_r("tt");
            // exit();
            header("HTTP/1.1 303 See Other");
            // header("Location: " . $checkout_session->url);
            return redirect()->to($checkout_session->url);
        } catch (Error $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
