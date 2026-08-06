<?php
$bg = !empty($certificate['background_image'])
    ? base_url($certificate['background_image'])
    : '';



if (!empty($completionDate)) {
    $ts = is_numeric($completionDate)
        ? (int)$completionDate
        : strtotime($completionDate);
    if ($ts) {
        $completionDate = date('Y-m-d', $ts);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Certificate</title>

    <!-- Elegant Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Great+Vibes&family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">

    <style>
        @page {
            size: A4;
            margin: 0;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .certificate {
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
            position: relative;
            background: url("<?= $bg ?>") no-repeat center center;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .content {
            width: 80%;
        }

        .title {
            font-family: 'Cinzel', serif;
            font-size: 38px;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 40px;
        }

        .text {
            font-family: 'Libre Baskerville', serif;
            font-size: 18px;
            margin: 12px 0;
        }

        .name {
            font-family: 'Great Vibes', cursive;
            font-size: 50px;
            margin: 20px 0 10px;
        }

        .name-line {
            width: 60%;
            margin: 0 auto 25px;
            border-bottom: 2px solid #c9a24c;
        }

        .course {
            font-family: 'Libre Baskerville', serif;
            font-size: 22px;
            font-weight: 700;
            margin-top: 10px;
        }

        .date-section {
            margin-top: 25px;
            font-family: 'Libre Baskerville', serif;
        }

        .date {
            font-size: 16px;
            border-bottom: 1px solid #c9a24c;
            display: inline-block;
            padding-bottom: 4px;
        }

        .date-label {
            font-size: 14px;
            margin-top: 5px;
        }

        @media print {
            body {
                margin: 0;
            }
        }

        .reference-id {
            position: absolute;
            bottom: 55px;
            left: 105px;
            font-size: 12px;
            font-family: 'Libre Baskerville', serif;
        }
    </style>
</head>

<body>

    <div class="certificate">
        <div class="content">
            <br>
            <div class="title">
                CERTIFICATE<br>OF COMPLETION
            </div>

            <div class="text">
                This is to certify that
            </div>

            <div class="name">
                <?= esc($user['name']) ?>
            </div>

            <div class="name-line"></div>

            <div class="text">
                has successfully completed the
            </div>

            <div class="course">
                <?= esc($course_name ?? '') ?>
            </div>

            <div class="date-section">
                <div class="date">
                    <?= esc(date('l, F d, Y', strtotime($completionDate))) ?>
                </div>
                <div class="date-label">
                    Date
                </div>
            </div>
        </div>
         <div class="reference-id">
        <?= esc($assign['certificate_id'] . $assign['cert_assign_id'] . $assign['last_updated_on']); ?>
    </div>
    </div>
   

</body>

</html>