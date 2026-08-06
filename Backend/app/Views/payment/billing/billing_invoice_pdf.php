<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 12px;
        }

        .header {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f4f4f4;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>Invoice</h2>
        <p><strong>Date:</strong> <?= date('d-m-Y', $payment[0]['createdon']) ?></p>
    </div>

    <table>
        <tr>
            <th>Invoice No</th>
            <td><?= esc($invoice_no) ?></td>
        </tr>
        <tr>
            <th>Order ID</th>
            <td><?= 'OL -' . esc($payment[0]['bill_id']) ?></td>
        </tr>
        <tr>
            <th>Course Name</th>
            <td><?= esc($payment[0]['course_name']) ?></td>
        </tr>
        <tr>
            <th>Amount</th>
            <td><?= number_format($payment[0]['amount'], 2) ?> <?= strtoupper($payment[0]['currency']) ?></td>
        </tr>
        <tr>
            <th>Payment Gateway</th>
            <td><?= esc($payment[0]['gateway']) ?></td>
        </tr>
        <tr>
            <th>Transaction ID</th>
            <td><?= esc($payment[0]['payment_intent_id']) ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td><?php if ($payment[0]['status'] == 1): ?>
                    Successful
                <?php elseif ($payment[0]['status'] == 2): ?>
                    Refund Pending
                <?php elseif ($payment[0]['status'] == 3): ?>
                    Refunded
                <?php else: ?>
                    Pending
                <?php endif; ?>
            </td>
        </tr>
    </table>

</body>

</html>