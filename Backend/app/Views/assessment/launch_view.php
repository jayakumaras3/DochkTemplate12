<!doctype html>
<html>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Quiz</title>
    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' rel='stylesheet'>
    <link href='' rel='stylesheet'>
    <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box
        }

        body {
            background-color: #fff
        }

        hr.orange_line {
            border: 1px solid #F2672E;
        }

        .container {
            background-color: #212947;
            color: #000;
            padding: 10px;
            font-family: 'Montserrat', sans-serif;
            max-width: 700px;
            min-height: 400px;
        }

        .welcome {
            color: #fff;
            font-size: 40px;
        }

        .message {
            color: red;
            font-size: 20px;
        }

        .welcome_2nd {
            color: #ef6730;
            margin-top: -10px;
            font-size: 40px;
        }

        .pointers {
            color: #fff;
            font-size: 14px;
        }

        .container>p {
            font-size: 32px
        }

        .qnumber {
            font-size: 20px;
            text-align: center;
        }

        .question {
            padding: 10px;
            width: 100%
        }


        .btn-primary {
            background-color: #555;
            color: #ddd;
            border: 1px solid #ddd
        }

        .btn-primary:hover {
            background-color: #21bf73;
            border: 1px solid #21bf73
        }

        .btn-success {
            padding: 5px 25px;
            background-color: #21bf73
        }

        .nxt-btn {
            width: 150px;
        }

        @media(max-width:576px) {
            .question {
                width: 100%;
                word-spacing: 2px
            }
        }
    </style>
</head>

<body oncontextmenu='return false' class='snippet-body'>

    <div class="container">
        <div class="question pt-2">
            <div class="welcome"><b>WELCOME TO THE</b></div>
            <div class="welcome_2nd"><b>ASSESSMENT</b></div>
            <br>
            <div class="pointers">Course Name : <?php echo $quiz_description[0]['course_name']; ?></div>
            <div class="pointers">Description : <?php echo trim($quiz_description[0]['description']); ?></div>
            <div class="pointers">Objectives : <?php echo $quiz_description[0]['objectives']; ?></div>
            <div class="pointers">Minimum Score Required : <?php echo $quiz_pass[0]['value']; ?> %</div>
            <div class="pointers">Max Attempts Allowed : <?php echo $quiz_attempts[0]['value']; ?></div>
            <div class="pointers">Current Attempts : <?php echo  $current_attempts[0]['attempt'] + 1; ?></div>

        </div>
        <div class="d-flex align-items-center pt-3">
            <div class="ml-auto mr-sm-5">
                <?php
                $showstart = 1;
                if ($quiz_lock[0]['value'] == 'Enabled') {
                    // print_r("tt");
                    $showstart = 0;
                    $lockmsg = 'The assessment is currently locked by Admin.';
                }
                $attempt = $current_attempts[0]['attempt'] + 1;
                if ($attempt > $quiz_attempts[0]['value']) {
                    // print_r("ss");
                    $showstart = 0;
                    $lockmsg = 'Max Attempts Reached.';
                }
                if ($showstart == 1) {
                    // print_r("yy");
                    echo '<form class="confirm"  action="' . base_url('Assessment/launch/start_assessment') . '" method="post" accept-charset="utf-8" enctype="multipart/form-data" >';
                    echo ' <?= csrf_field() ?>';
                    echo '<input type="hidden" value="0" name="q_num" />';
                    echo '<input type="hidden" value="' . $scourse_id . '" name="scourse_id" />';
                    // echo '<input type="hidden" value="' .  $quiz_attempts[0]['sc_uid'] . '" name="sc_uid" />';
                   
                    echo '<button type="submit" class="btn btn-primary btn-sm  nxt-btn">Start Assessment</button>';
                    echo '</form>';
                } else {
                    if ($current_attempts[0]['grace'] == 1) {
                        echo '<form class="confirm"  action="' . base_url('Assessment/launch/start_assessment') . '" method="post" accept-charset="utf-8" enctype="multipart/form-data" >';
                        echo ' <?= csrf_field() ?>';
                        echo '<input type="hidden" value="0" name="q_num" />';
                        echo '<input type="hidden" value="' . $scourse_id . '" name="scourse_id" />';
                        // echo '<input type="hidden" value="' .  $quiz_attempts[0]['sc_uid'] . '" name="sc_uid" />';
                        echo '<button type="submit" class="btn btn-success btn-sm  nxt-btn">Start Assessment</button>';
                        echo '</form>';
                    } else {
                        echo '<div class="message">';
                        echo $lockmsg;
                        echo '</div>';
                    }
                }

                ?>
            </div>
        </div>
    </div>
    <script type='text/javascript' src='https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js'></script>
</body>

</html>