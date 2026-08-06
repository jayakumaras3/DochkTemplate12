<?php

namespace App\Controllers\Stripe;

use App\Controllers\BaseController;

require_once 'vendor/autoload.php';



class Create_portal_session extends BaseController
{

  function index()
  {
    // $stripeSecretKey = 'sk_test_51O3DPXSEl2aCSnJf1xTbBygWMj33TI3T4seUohVwNHf2dlZ2ZdyeDQACACZqaITRKSMzdQK4j8Pb3iTCZcsTz2nO00vshWh56v';
    $stripeSecretKey = 'sk_test_51OP4a6GTotN7O5QoVCyOFu3B0jcdXS12P1bqe0LdeZqosOCvbyYLaYeSQf0JMDgrxZRtPG10pQGYTrNnENJXvo4A00VWid0ly5';
    \Stripe\Stripe::setApiKey($stripeSecretKey);

    header('Content-Type: application/json');


    $YOUR_DOMAIN = base_url('Billing');

    try {
      $checkout_session = \Stripe\Checkout\Session::retrieve($_POST['session_id']);
      $return_url = $YOUR_DOMAIN;
      print_r($checkout_session);
      exit();
      // Authenticate your user.
      $session = \Stripe\BillingPortal\Session::create([
        'customer' => $checkout_session->customer,
        'return_url' => $return_url,
      ]);
     
      header("HTTP/1.1 303 See Other");
      return redirect()->to($session->url);
      // header("Location: " . $session->url);
    } catch (Error $e) {
      http_response_code(500);
      echo json_encode(['error' => $e->getMessage()]);
    }
  }
}
