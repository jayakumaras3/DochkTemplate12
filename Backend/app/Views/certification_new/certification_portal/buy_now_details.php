<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certification Details — CCD-Tech Certification</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/assets/certification/certification-details.css') ?>">
    <style>
        .payment-wrapper {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 15px;
        }

        .payment-card {
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .08);
        }

        .cert-header {
            background: #f8fafc;
            padding: 25px 30px;
            border-bottom: 1px solid #e5e7eb;
        }

        .cert-header h2 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            line-height: 1.4;
        }

        .cert-header p {
            margin-top: 10px;
            color: #6b7280;
            font-size: 15px;
        }

        .cert-meta {
            margin-top: 12px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            color: #6b7280;
            font-size: 14px;
        }

        .payment-body {
            padding: 30px;
        }

        .section-title {
            margin-bottom: 20px;
            font-size: 20px;
            font-weight: 600;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            font-size: 16px;
        }

        .discount {
            color: #16a34a;
            font-weight: 600;
        }

        .total-box {
            margin-top: 20px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 18px 20px;

            display: flex;
            justify-content: space-between;
            align-items: center;

            font-size: 24px;
            font-weight: 700;
            color: #2563eb;
        }

        .coupon-section {
            margin-top: 25px;
        }

        .coupon-label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .coupon-box {
            display: flex;
            gap: 10px;
        }

        .coupon-box input {
            flex: 1;
            height: 48px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 0 15px;
            font-size: 15px;
        }

        .coupon-box button {
            height: 48px;
            padding: 0 20px;
            border: none;
            border-radius: 8px;
            background: #2563eb;
            color: #fff;
            cursor: pointer;
            font-weight: 600;
        }

        .terms-section {
            margin-top: 20px;
        }

        .secure-note {
            text-align: center;
            margin-top: 20px;
            color: #6b7280;
            font-size: 13px;
        }

        .btn-pay {
            width: 100%;
            margin-top: 20px;
            height: 55px;
            border: none;
            border-radius: 10px;
            background: #16a34a;
            color: #fff;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-pay:hover {
            background: #15803d;
        }
    </style>
</head>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Certification/Certification_Portal'); ?>">Certifications</a></li>
                </ol>
            </div>
            <h4 class="page-title">Certification Payment</h4>
        </div>
    </div>
</div>

<div class="payment-wrapper">

    <div class="payment-card">

        <div class="cert-header">
            <h2><?= esc($certificate_name) ?></h2>

            <p>Certification Purchase</p>

            <div class="cert-meta">
                <span><?= $course_count ?? 0 ?> Courses</span>
                <span>•</span>
                <span><?= $learning_plan_count ?? 0 ?> Learning Plan</span>
            </div>
        </div>

        <div class="payment-body">

            <h4 class="section-title">
                Price Details
            </h4>

            <div class="price-row">
                <span>Original Price</span>
                <span>₹<?= number_format($originalAmount, 0) ?></span>
            </div>

            <?php if ($discountAmount > 0) : ?>
                <div class="price-row">
                    <span>Discount</span>
                    <span class="discount">
                        - ₹<?= number_format($discountAmount, 0) ?>
                    </span>
                </div>
            <?php endif; ?>

            <?php if ($couponAmount > 0) : ?>
                <div class="price-row">
                    <span>Coupon Discount</span>
                    <span class="discount">
                        - ₹<?= number_format($couponAmount, 0) ?>
                    </span>
                </div>
            <?php endif; ?>

            <div class="total-box">

                <span>Total Payable</span>

                <span>
                    ₹<?= number_format($finalAmount, 0) ?>
                </span>

            </div>

            <div class="coupon-section">


                <?php if ($couponAmount > 0) { ?>


                    <div class="coupon-section">

                        <label class="coupon-label">Coupon Code</label>

                        <div class="persistent-success d-flex justify-content-between align-items-center">

                            <div>
                                <strong><?= esc($couponCode) ?></strong> applied successfully.
                                <br>
                                You saved ₹<?= number_format($couponAmount, 0) ?>
                            </div>

                            <form method="post"
                                action="<?= base_url('Certification/Certification_Payment/removeCoupon') ?>">

                                <?= csrf_field() ?>

                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    Remove
                                </button>

                            </form>

                        </div>

                    </div>


                <?php } else { ?>

                    <div class="coupon-section">

                        <label class="coupon-label">
                            Coupon Code
                        </label>

                        <form method="post"
                            action="<?= base_url('Certification/Certification_Payment/applyCoupon') ?>">

                            <?= csrf_field() ?>

                            <input type="hidden"
                                name="certificate_id"
                                value="<?= $certificate_id ?>">

                            <div class="coupon-box">

                                <input
                                    type="text"
                                    name="coupon_code"
                                    placeholder="Enter Coupon Code"
                                    autocomplete="off"
                                    maxlength="20"
                                    oninput="this.value=this.value.toUpperCase().replace(/\s/g,'')"
                                    required>

                                <button type="submit">
                                    Apply
                                </button>

                            </div>

                        </form>

                    </div>

                <?php } ?>

            </div>


            <div class="secure-note">
                🔒 Secure payment powered by Razorpay
            </div>

            <form method="post"
                action="<?= base_url('Certification/Certification_Payment/createPaymentOrder') ?>">

                <?= csrf_field() ?>

                <input type="hidden"
                    name="certificate_id"
                    value="<?= $certificate_id ?>">
                <input type="hidden" name="certificate_name" value="<?= $certificate_name ?>">

                <div class="form-check mb-3">
                    <input class="form-check-input"
                        type="checkbox"
                        required>
                    <style>
                        .terms-link {
                            color: #6f42c1;
                            text-decoration: underline;
                            font-weight: 600;
                        }

                        .terms-link:hover {
                            color: #5a32a3;
                        }
                    </style>
                    <label class="form-check-label" for="termsCheck">
                        I agree to the
                        <a href="<?php echo base_url('ang/terms') ?>"
                            class="terms-link"
                            target="_blank"
                            onclick="event.stopPropagation();">
                            Terms &amp; Conditions
                        </a>
                    </label>
                </div>


                <button type="submit" class="btn-pay">
                    Proceed To Payment
                </button>

            </form>

        </div>

    </div>

</div>