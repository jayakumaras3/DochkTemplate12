<?php

function calculateCertificationAmount($pricing, $coupon = null)
{
    $originalAmount = $pricing['amount'];

    $discountAmount = 0;
    $couponAmount = 0;

    /*
    |--------------------------------------------------------------------------
    | Automatic Discount
    |--------------------------------------------------------------------------
    | Apply only if discount_code is NULL
    */
    if (
        !empty($pricing['discount_id']) &&
        empty($pricing['discount_code'])
    ) {

        if ($pricing['discount_type'] == 'PERCENTAGE') {

            $discountAmount =
                ($originalAmount * $pricing['discount_value']) / 100;
        } else {

            $discountAmount =
                $pricing['discount_value'];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Coupon Discount
    |--------------------------------------------------------------------------
    | Apply only when user entered coupon
    */
    if (!empty($coupon)) {

        if ($coupon['discount_type'] == 'PERCENTAGE') {

            $couponAmount =
                ($originalAmount * $coupon['discount_value']) / 100;
        } else {

            $couponAmount =
                $coupon['discount_value'];
        }

        // Don't apply automatic discount
        $discountAmount = 0;
    }

    $finalAmount =
        $originalAmount -
        $discountAmount -
        $couponAmount;

    return [
        'originalAmount' => $originalAmount,
        'discountAmount' => $discountAmount,
        'couponAmount'   => $couponAmount,
        'finalAmount'    => max(0, $finalAmount)
    ];
}