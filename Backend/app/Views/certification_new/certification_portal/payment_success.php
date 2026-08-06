<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet"
        href="<?= base_url('assets/assets/certification/certification-details.css') ?>">

    <style>
        .success-container {
            max-width: 900px;
            margin: 50px auto;
        }

        .success-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, .08);
        }

        .success-icon {
            width: 100px;
            height: 100px;
            margin: auto;
            border-radius: 50%;
            background: #e8f8ee;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .success-icon svg {
            width: 50px;
            height: 50px;
            color: #16a34a;
        }

        .success-title {
            text-align: center;
            font-size: 32px;
            font-weight: 700;
            margin-top: 20px;
            color: #111827;
        }

        .success-subtitle {
            text-align: center;
            color: #6b7280;
            margin-top: 10px;
        }

        .txn-box {
            margin-top: 35px;
            background: #f8fafc;
            border-radius: 14px;
            padding: 25px;
            border: 1px solid #e5e7eb;
        }

        .txn-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 30px;
            padding: 20px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .txn-row:last-child {
            border-bottom: none;
        }

        .txn-label {
            min-width: 180px;
            color: #64748b;
            font-weight: 500;
            font-size: 15px;
        }

        .txn-value {
            flex: 1;
            text-align: right;
            font-weight: 600;
            color: #111827;
            font-size: 16px;
        }

        .action-area {
            text-align: center;
            margin-top: 35px;
        }

        .btn-start {
            display: inline-block;
            padding: 14px 30px;
            background: #2563eb;
            color: #fff;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
        }

        .btn-start:hover {
            background: #1d4ed8;
            color: #fff;
        }

        .thank-note {
            margin-top: 25px;
            text-align: center;
            color: #6b7280;
        }

        .certification-name {
            max-width: 650px;
            line-height: 1.6;
            word-break: break-word;
        }

        .payment-ref {
            background: #eff6ff;
            color: #1d4ed8;
            padding: 8px 14px;
            border-radius: 8px;
            font-family: monospace;
            font-size: 15px;
            font-weight: 600;
        }

        .success-banner {
            margin: 30px auto;
            background: #ecfdf5;
            color: #166534;
            border: 1px solid #bbf7d0;
            padding: 18px 24px;
            border-radius: 12px;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <div class="success-container">

        <div class="success-card">

            <div class="success-icon">

                <svg xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7" />

                </svg>

            </div>

            <h1 class="success-title">
                Payment Successful
            </h1>

            <p class="success-subtitle">
                Thank you for choosing DOCHEK Certification.
            </p>

            <div class="txn-box">

                <div class="txn-row">
                    <div class="txn-label">
                        Transaction ID
                    </div>

                    <div class="txn-value">
                        <span class="payment-ref">
                            <?= esc($payment_id) ?>
                        </span>
                    </div>
                </div>

                <div class="txn-row">
                    <div class="txn-label">
                        Certification
                    </div>

                    <div class="txn-value certification-name">
                        <?= esc($certificate_name) ?>
                    </div>
                </div>

                <div class="txn-row">
                    <div class="txn-label">
                        Payment Date
                    </div>

                    <div class="txn-value">
                        <?= date('d M Y h:i A') ?>
                    </div>
                </div>

                <div class="thank-note">
                    Your certification has been unlocked successfully.
                    You can now access your learning path and begin your assessment.
                </div>

                <div class="action-area">

                    <a href="<?= base_url('Certification/Certification_Portal/certificationDetails') ?>"
                        class="btn-start">

                        Start Certification

                    </a>

                </div>

            </div>

        </div>

</body>

</html>