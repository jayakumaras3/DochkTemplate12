<?php

namespace App\Controllers\Stripe;

use App\Controllers\BaseController;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\CheckoutSession;
use Stripe\Subscription;
use App\Models\Payment\Billing_model;
use App\Models\User_login\Users_model;

#[\AllowDynamicProperties]

class StripeWebhook extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->billing_model = new Billing_model;
        $this->users_model = new Users_model;
        Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));
        $this->db = \Config\Database::connect();
    }
    public function index()
    {
        $endpoint_secret = getenv('STRIPE_WEBHOOK_SECRET');

        $payload    = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\Exception $e) {
            http_response_code(400);
            exit;
        }

        // 🔒 Global idempotency
        if ($this->billing_model->eventExists($event->id)) {
            http_response_code(200);
            exit;
        }

        switch ($event->type) {

            /* =====================================================
           ONE-TIME PAYMENT (FINAL CONFIRMATION)
        ===================================================== */
            case 'payment_intent.succeeded':

                $intent = $event->data->object;

                // Update existing pending purchase
                $this->billing_model->updateByPaymentIntent(
                    $intent->id,
                    [
                        'status'          => 1,
                        'last_updated_on' => time()
                    ]
                );

                // 🔓 Grant product access
                $this->billing_model->grantUserProductByIntent($intent->id);

                break;

            /* =====================================================
           CHECKOUT SESSION COMPLETED (CREATE PENDING RECORD)
        ===================================================== */
            case 'checkout.session.completed':

                $session = $event->data->object;

                if ($session->mode !== 'payment') break;

                // Prevent duplicate inserts
                if ($this->billing_model->purchaseExists($session->payment_intent)) {
                    break;
                }

                $data = [
                    'client_id'           => $session->metadata->client,
                    'user_id'             => $session->metadata->user_id,
                    'product_id'          => $session->metadata->product_id,
                    'type'                => $session->metadata->billing_cycle,
                    'mode'                => 'payment',
                    'amount'              => $session->amount_total / 100,
                    'currency'            => $session->currency,
                    'gateway'             => 'stripe',

                    'authorize_code'      => $session->id,
                    'payment_intent_id'   => $session->payment_intent,
                    'stripe_customer_id'  => $session->customer,

                    'status'              => 0, // PENDING
                    'createdon'           => time(),
                    'last_updated_on'     => time(),
                ];

                $this->billing_model->addSubscribe($data);
                break;

            /* =====================================================
           SUBSCRIPTION CREATED
        ===================================================== */
            case 'customer.subscription.created':

                $sub = $event->data->object;

                // Avoid duplicates
                if ($this->billing_model->subscriptionExists($sub->id)) {
                    break;
                }

                $data = [
                    'client_id'           => $sub->metadata->client,
                    'user_id'             => $sub->metadata->user_id ?? null,
                    'product_id'          => $sub->items->data[0]->price->product,
                    'type'                => $sub->metadata->billing_cycle ?? 2,
                    'mode'                => 'subscription',
                    'amount'              => $sub->items->data[0]->price->unit_amount / 100,
                    'currency'            => $sub->items->data[0]->price->currency,
                    'gateway'             => 'stripe',

                    'authorize_code'      => $sub->id,
                    'stripe_customer_id'  => $sub->customer,
                    'current_period_end'  => $sub->current_period_end,

                    'status'              => ($sub->status === 'active') ? 1 : 0,
                    'createdon'           => time(),
                    'last_updated_on'     => time(),
                ];

                $this->billing_model->addSubscribe($data);
                break;

            /* =====================================================
           SUBSCRIPTION RENEWAL
        ===================================================== */
            case 'invoice.payment_succeeded':

                $invoice = $event->data->object;

                if (!$invoice->subscription) break;

                $this->billing_model->updateSubscriptionByStripeId(
                    $invoice->subscription,
                    [
                        'last_payment_at'   => time(),
                        'current_period_end' => $invoice->lines->data[0]->period->end,
                        'status'            => 1,
                        'last_updated_on'   => time()
                    ]
                );

                break;

            /* =====================================================
           PAYMENT FAILED
        ===================================================== */
            case 'payment_intent.payment_failed':

                $intent = $event->data->object;

                $this->billing_model->updateByPaymentIntent(
                    $intent->id,
                    [
                        'status'          => 2,
                        'last_updated_on' => time()
                    ]
                );

                break;
            /* =====================================================
                 REFUND CONFIRMED (ONE-TIME PAYMENT)
                 ===================================================== */
            case 'charge.refunded':
                $charge = $event->data->object;
                $intentId = $charge->payment_intent;
                $course_id = $charge->metadata->product_id;

                // Mark refunded and revoke access
                $this->billing_model->updateByPaymentIntent($intentId, [
                    'status' => 3,           // Refunded
                    'refunded_on' => time(),
                ]);
                $this->billing_model->unassigncoursefromuser(
                    $course_id
                );
                // Revoke user product access
                $this->billing_model->revokeUserProductByIntent($intentId);

                break;


            /* =====================================================
           SUBSCRIPTION CANCELED
        ===================================================== */
            case 'customer.subscription.deleted':

                $sub = $event->data->object;

                $this->billing_model->updateSubscriptionByStripeId(
                    $sub->id,
                    [
                        'status'          => 0,
                        'last_updated_on' => time()
                    ]
                );

                break;
        }

        // Store processed Stripe event
        $this->billing_model->storeEventId($event->id);

        http_response_code(200);
        echo json_encode(['received' => true]);
    }
}
