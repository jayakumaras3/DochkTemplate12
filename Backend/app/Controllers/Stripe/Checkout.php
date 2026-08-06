<?php

namespace App\Controllers\Stripe;

use App\Controllers\BaseController;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;
use App\Models\Payment\Billing_model;
use App\Models\User_login\Users_model;
use Stripe\Subscription;
use Stripe\Refund;
use Dompdf\Dompdf;
use Dompdf\Options;

#[\AllowDynamicProperties]

class Checkout extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->billing_model = new Billing_model;
        $this->users_model = new Users_model;
        Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));
        $this->db = \Config\Database::connect();
    }

    // ONE-TIME PAYMENT

    public function payCourse()
    {
        $sessionService = session();

        $scourse_id       = $this->request->getPost('scourse_id');
        $price            = (float) $this->request->getPost('course_price');
        $billing_cycle    = $this->request->getPost('billing_cycle') ?? 1;
        $currency_id      = $this->request->getPost('currency') ?? '1';
        $user_id          = $sessionService->get('id_user');
        $client          = $sessionService->get('client');


        $price_cents = (int) round($price * 100);

        $payment_mode = 'payment'; // currently only one-time payments

        $currency_map = [
            '1' => 'usd',
            '2' => 'eur',
            '3' => 'inr'
        ];
        $currency = $currency_map[$currency_id] ?? 'usd';

        // Set Stripe secret key
        // Stripe::setApiKey(env('stripe.secret'));

        try {
            //  Create Checkout Session
            $session = CheckoutSession::create([
                'payment_method_types' => ['card'],
                'mode' => $payment_mode,
                'line_items' => [[
                    'price_data' => [
                        'currency' => $currency,
                        'product_data' => [
                            'name' => 'Course ' . $scourse_id,
                        ],
                        'unit_amount' => $price_cents,
                    ],
                    'quantity' => 1,
                ]],
                'metadata' => [
                    'product_id'   => $scourse_id,
                    'user_id'       => $user_id,
                    'billing_cycle' => $billing_cycle,
                    'currency'      => $currency,
                    'mode' => $payment_mode,
                    'price' => $price_cents,
                    'client' => $client

                ],
                'success_url' => base_url('stripe/success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'  => base_url('stripe/cancel'),
            ]);

            return redirect()->to($session->url);
        } catch (\Exception $e) {
            // 8️⃣ Return error as JSON (or handle as you prefer)
            session()->setFlashdata('error', $e->getMessage());
            return redirect()->to(base_url('my_training/read_more'));
            // return $this->response->setJSON(['error' => $e->getMessage()]);
        }
    }

    // MONTHLY SUBSCRIPTION
    public function monthlySubscription()
    {
        //  echo getenv('STRIPE_MONTHLY_PRICE'); exit;
        $sessionService = session();
        $billing_cycle    = $this->request->getPost('billing_cycle') ?? 2;
        $currency_id      = $this->request->getPost('currency') ?? '1';
        $client          = $sessionService->get('client');
        $currency_map = [
            '1' => 'usd',
            '2' => 'eur',
            '3' => 'inr'
        ];
        $currency = $currency_map[$currency_id] ?? 'usd';

        $payment_mode = 'subscription';
        try {
            $session = CheckoutSession::create([
                'payment_method_types' => ['card'],
                'mode' => $payment_mode, // recurring
                'line_items' => [[
                    'price' => getenv('STRIPE_MONTHLY_PRICE'),
                    'quantity' => 1,
                ]],
                'metadata' => [
                    'user_id' => session()->get('user_id'),
                    'billing_cycle' => $billing_cycle,
                    'currency'      => $currency,
                    'mode' => $payment_mode,
                    'client' => $client
                ],
                'success_url' => base_url('stripe/success_subscription') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'  => base_url('stripe/cancel'),

            ]);

            return redirect()->to($session->url);
        } catch (\Exception $e) {
            session()->setFlashdata('error', $e->getMessage());
            return redirect()->to(base_url('marketplace/dashboard'));
            // return $this->response->setJSON(['error' => $e->getMessage()]);
        }
    }

    // SUCCESS & CANCEL PAGES
    public function success()
    {

        $data = [];
        $data['header'] = 'Payment Confirmation';
        $session_id = $this->request->getGet('session_id');
        $session    = CheckoutSession::retrieve($session_id);

        $metadata = $session->metadata;

        $product_id   = $metadata->product_id;
        $user_id       = $metadata->user_id;
        $billing_cycle = $metadata->billing_cycle;
        $mode = $metadata->mode;
        $client      = $metadata->client;

        if (isset($session_id)) {
            $session_id = $_GET['session_id'];
            $user_id = session()->get('id_user');
            $sessionval = count($this->billing_model->checksessionval($session_id, $user_id));
            if ($sessionval == 0) {
                $newdata = [
                    'client_id' => $client,
                    'user_id' => $user_id,
                    'product_id' => $product_id,
                    'type' => $billing_cycle,
                    'mode' => $mode,
                    'amount'              => $session->amount_total / 100,
                    'currency'            => $session->currency,
                    'gateway' => 'stripe',
                    'status' => 1,
                    'authorize_code' =>  $session_id,
                    'payment_intent_id'   => $session->payment_intent,
                    // 'stripe_customer_id'  => $session->customer,
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
                // $this->users_model->savelogdata($logdata);
                // echo view('templates/header_view', $data);
                // echo  view('stripe/OTPay_success_message', $data);
                // echo view('templates/footer_view');
                session()->setFlashdata('payment_status', 'success');
                session()->setFlashdata(
                    'payment_message',
                    'Thank you for your purchase. Your payment has been processed successfully!<br><b>Start watching the course now.</b>'
                );
                return redirect()->to(base_url('my_training/read_more'));
            } else {
                $logdata['id_user'] = session()->get('id_user');
                $logdata['activity_type'] = 'not credited';
                $logdata['timestamp'] = time();
                session()->setFlashdata('payment_status', 'error');
                session()->setFlashdata('payment_message', 'Payment failed. Please try again or contact support.');
                return redirect()->to(base_url('my_training/read_more'));
                // $this->users_model->savelogdata($logdata);
                // echo view('templates/header_view', $data);
                // echo  view('stripe/cancel_message', $data);
                // echo view('templates/footer_view');
            }
        } else {
            return redirect()->to(base_url('my_training/read_more'));
        }
    }
    // public function success()
    // {
    //     $sessionId = $this->request->getGet('session_id');

    //     if (!$sessionId) {
    //         return redirect()->to(base_url('my_training/read_more'));
    //     }

    //     // Get purchase created by webhook
    //     $purchase = $this->billing_model
    //         ->where('authorize_code', $sessionId)
    //         ->first();

    //     // If webhook not yet processed
    //     if (!$purchase) {
    //         session()->setFlashdata('payment_status', 'pending');
    //         session()->setFlashdata(
    //             'payment_message',
    //             'Your payment is being processed. Please wait a moment and refresh the page.'
    //         );
    //         return redirect()->to(base_url('my_training/read_more'));
    //     }

    //     // Payment confirmed by webhook
    //     if ($purchase['status'] == 1) {
    //         session()->setFlashdata('payment_status', 'success');
    //         session()->setFlashdata(
    //             'payment_message',
    //             'Thank you for your purchase. Your payment was successful. <br><b>Start watching the course now.</b>'
    //         );
    //         return redirect()->to(base_url('my_training/read_more'));
    //     }

    //     // Payment failed or pending
    //     session()->setFlashdata('payment_status', 'error');
    //     session()->setFlashdata(
    //         'payment_message',
    //         'Payment is still pending or failed. If money was deducted, it will be refunded automatically.'
    //     );

    //     return redirect()->to(base_url('my_training/read_more'));
    // }



    public function success_subscription()
    {

        $data = [];
        $data['header'] = 'Payment Confirmation';
        $session_id = $this->request->getGet('session_id');
        $session_id = $this->request->getGet('session_id');
        $session    = CheckoutSession::retrieve($session_id);

        $sessionId = $this->request->getGet('session_id');
        $subscriptionId = $session->subscription;
        $subscription = Subscription::retrieve($subscriptionId);

        $product_id = $subscription->items->data[0]->price->product;

        $price = $subscription->items->data[0]->price->id;

        $metadata = $session->metadata;
        $billing_cycle = $metadata->billing_cycle;
        $currency      = $metadata->currency;
        $mode = $metadata->mode;


        if (isset($session_id)) {
            $session_id = $_GET['session_id'];
            $user_id = session()->get('id_user');
            $client_id = session()->get('client');
            $sessionval = count($this->billing_model->checksessionval($session_id, $user_id));
            if ($sessionval == 0) {
                $newdata = [
                    'client_id' => $client_id,
                    'user_id' => $user_id,
                    'product_id' => $product_id,
                    'type' => $billing_cycle,
                    'mode' => $mode,
                    'amount' => $price,
                    'currency' => $currency,
                    'gateway' => 'stripe',
                    'status' => 1,
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
                // $this->users_model->savelogdata($logdata);
                echo view('templates/header_view', $data);
                echo  view('stripe/success_message', $data);
                echo view('templates/footer_view');
            } else {
                $logdata['id_user'] = session()->get('id_user');
                $logdata['activity_type'] = 'not credited';
                $logdata['timestamp'] = time();
                // $this->users_model->savelogdata($logdata);
                echo view('templates/header_view', $data);
                echo  view('stripe/cancel_message', $data);
                echo view('templates/footer_view');
            }
        } else {
            return redirect()->to(base_url('my_training/read_more'));
        }
    }

    // public function success_subscription()
    // {
    //     $data['header'] = 'Payment Confirmation';
    //     $session_id = $this->request->getGet('session_id');

    //     if (!$session_id) {
    //         return redirect()->to(base_url('my_training/read_more'));
    //     }

    //     $session = CheckoutSession::retrieve($session_id);

    //     $subscription_id = $session->subscription;
    //     $subscription = \Stripe\Subscription::retrieve($subscription_id);

    //     $data['product_id'] = $subscription->items->data[0]->price->product;
    //     $data['price_id'] = $subscription->items->data[0]->price->id;
    //     $data['amount'] = $subscription->items->data[0]->price->unit_amount ?? 0;
    //     $data['currency'] = $session->currency ?? 'USD';
    //     $data['mode'] = $session->mode ?? 'subscription';

    //     echo view('templates/header_view', $data);
    //     echo view('stripe/success_message', $data);
    //     echo view('templates/footer_view');
    // }


    public function cancel()
    {
        session()->setFlashdata('payment_status', 'error');
        session()->setFlashdata('payment_message', 'Payment failed. Please try again or contact support.');
        return redirect()->to(base_url('my_training/read_more'));
    }


    // public function cancellation($payment_intent_id)
    // {
    //     // Get course/payment data from DB
    //     $course = $this->billing_model->getInvoicedata($payment_intent_id);

    //     if (!$course) {
    //         return redirect()->back()->with('error', 'Course not found.');
    //     }

    //     // Check eligibility
    //     if ($course[0]['status'] != 1 || empty($course[0]['payment_intent_id'])) {
    //         return redirect()->back()->with('error', 'Refund not allowed.');
    //     }

    //     // 2-day rule (48 hours)
    //     if ((time() - $course[0]['createdon']) > (2 * 24 * 60 * 60)) {
    //         return redirect()->back()->with('error', 'Refund period expired.');
    //     }


    //     try {
    //         // Create refund
    //         $refund = Refund::create([
    //             'payment_intent' => $course[0]['payment_intent_id'],
    //         ]);


    //         $this->billing_model->updateByPaymentIntent(
    //             $course[0]['payment_intent_id'],
    //             [
    //                 'status'        => 2, // refunded
    //                 'refund_id'     => $refund->id,
    //                 'refunded_on'   => time(),
    //             ]
    //         );

    //         return redirect()->back()->with('success', 'Payment refunded successfully!');
    //     } catch (\Exception $e) {
    //         // return redirect()->back()->with('error', $e->getMessage());
    //         session()->setFlashdata('error', $e->getMessage());
    //         return redirect()->to(base_url('Payment/Billing/purchase_history'));
    //     }
    // }
    public function cancellation($payment_intent_id)
    {
        Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

        $course = $this->billing_model->getInvoicedata($payment_intent_id);


        if (!$course) {
            return redirect()->back()->with('error', 'Course not found.');
        }
        $getcoursestatus = $this->billing_model->getcoursestatus($payment_intent_id);

        if (!empty($getcoursestatus)) {
            return redirect()->back()
                ->with('error', 'This course has already been completed and cannot be refunded.');
        }


        if ($course[0]['status'] != 1) {
            return redirect()->back()->with('error', 'Refund not allowed.');
        }

        // 2-day rule
        if ((time() - $course[0]['createdon']) > (2 * 24 * 60 * 60)) {
            return redirect()->back()->with('error', 'Refund period expired.');
        }

        try {
            Refund::create([
                'payment_intent' => $payment_intent_id,
            ]);

            // 🔄 Pending until webhook confirms
            $this->billing_model->updateByPaymentIntent(
                $payment_intent_id,
                [
                    'status' => 2, // REFUND_PENDING
                    'last_updated_on' => time(),
                ]
            );
            $this->billing_model->unassigncoursefromuser($course[0]['scourse_id']);
            return redirect()->back()->with(
                'success',
                'Refund request submitted. You will be notified once processed.'
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function cancelSubscription()
    {
        Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

        $subscriptionId = $this->request->getPost('subscription_id');
        // OR fetch from DB by user_id

        try {
            Subscription::update($subscriptionId, [
                'cancel_at_period_end' => true,
            ]);

            // Update DB
            $newdata =  [
                'status' => 2, // cancelled (pending)
            ];
            $this->billing_model->updateSubscribe($newdata);

            session()->setFlashdata('success', lang('Messages.Success_0047'));
            return redirect()->to(base_url('marketplace/dashboard'));
        } catch (\Exception $e) {
            session()->setFlashdata('error', $e->getMessage());
            return redirect()->to(base_url('marketplace/dashboard'));
        }
    }
}
