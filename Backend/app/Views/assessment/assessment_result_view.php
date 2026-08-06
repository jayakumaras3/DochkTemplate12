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

        .message {
            color: white;
            font-size: 12px;
        }

        .container {
            background-color: #212947;
            color: #000;
            padding: 10px;
            font-family: 'Montserrat', sans-serif;
            max-width: 700px;
            min-height: 400px;
        }

        .result_title {
            color: #fff;
            font-size: 40px;
        }

        .result {
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
            <div class="result_title"><b>RESULT</b></div>
            <hr class="orange_line">
            <div class="col-12 result">
                Max Score : <?php echo $maxscoreoption[0]['maxs']; ?>
                <br>
                Your Score : <?php echo $resultval[0]['scoreval']; ?>
                <br>
                % Scored : <?php $scored = round($resultval[0]['scoreval'] / $maxscoreoption[0]['maxs'] * 100, 2);
                            echo $scored; ?> %
                <br>
                Minimum Required % : <?php echo $quiz_pass[0]['value']; ?> %
                <br>
                Result : <?php if ($scored >= $quiz_pass[0]['value']) {
                                echo 'Passed';
                            } else {
                                echo 'Failed';
                            } ?>
                <?php if ($cat_details) {
                    $category_missed = '';
                    echo '<br>Categories Missed : ';
                    foreach ($cat_details as $row2) {
                        if ($category_missed == '') {
                            $category_missed = $row2['cat'];
                        } else {
                            $category_missed = $category_missed . ', ' . $row2->cat;
                        }
                    }
                    echo $category_missed;
                }

                if ($scored >= $quiz_pass[0]['value']) {
                    echo '<br><br><form  action="' . base_url('Assessment/launch/review_questions') . '" method="post" accept-charset="utf-8" enctype="multipart/form-data" >';
                    echo' <?= csrf_field() ?>';
                    echo '<input type="hidden" value="' . $attempt . '" name="attempt" />';
                    echo '<input type="hidden" value="' . $scourse_id . '" name="scourse_id" />';
                    echo '<button type="submit" class="btn btn-primary btn-sm  nxt-btn">Review Questions</button>';
                    echo '</form>';
                }
                ?>

            </div>
            <br><br><br>
            <div class="message">
                You have completed the assessment. <br><br><span style="font-size:16px">Please close the window to return to the Dashboard.</span>

            </div>
        </div>
    </div>
</body>

</html>