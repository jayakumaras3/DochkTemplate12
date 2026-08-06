<!DOCTYPE html>
<html>
<head>
    <title>Certificate</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            text-align: center;
            font-family: 'Times New Roman', serif;
        }

        .certificate-container {
            width: 1000px;
            height: 700px;
            margin: 20px auto;
            position: relative;
            border: 10px solid #000;
            padding: 40px;
            box-sizing: border-box;
        }

        .certificate-title {
            font-size: 40px;
            font-weight: bold;
        }

        .certificate-body {
            margin-top: 40px;
            font-size: 22px;
        }

        .student-name {
            font-size: 32px;
            font-weight: bold;
            margin: 20px 0;
        }

        .footer {
            position: absolute;
            bottom: 40px;
            width: 100%;
            text-align: center;
        }

        @media print {
            body {
                margin: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

<div class="certificate-container">

    <div class="certificate-title">
        CERTIFICATE OF COMPLETION
    </div>

    <div class="certificate-body">
        This is to certify that
    </div>

    <div class="student-name">
        <?= esc($certificate['student_name']); ?>
    </div>

    <div class="certificate-body">
        has successfully completed the course
        <br><br>
        <strong><?= esc($certificate['course_name']); ?></strong>
        <br><br>
        on <?= date('d M Y', strtotime($certificate['completed_date'])); ?>
    </div>

    <div class="footer">
        ____________________________ <br>
        Authorized Signature
    </div>

</div>

<div class="no-print" style="text-align:center; margin-top:20px;">
    <button onclick="window.print()">Print Certificate</button>
</div>

</body>
</html>
