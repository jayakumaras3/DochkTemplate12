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

        .tdtext {
            font-size: 10px;
            text-align: left;
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
            <div class="result_title"><b>DETAIL RESULT</b></div>
            <hr class="orange_line">
            <div class="col-12 result">
                <table class="table">
                    <tr>
                        <th>#</th>
                        <th>Question</th>
                        <!-- <th>Score</th> -->
                        <th>Selected</th>
                        <th>Result</th>
                        <th>Correct</th>
                    </tr>
                    <?php
                    if (!empty($fulldata)) {
                        $totalrows = count($fulldata);
                        $j = 1;
                      //  echo $totalrows;
                        for ($i = 0; $i < $totalrows; $i++) {
                            echo '<tr>';
                            echo '<td class="tdtext" > ' . $j . '</td>';
                            echo '<td class="tdtext">' . $fulldata[$i][1] . '</td>';
                          //  echo '<td class="tdtext">' . $fulldata[$i][2] . '</td>';
                            echo '<td class="tdtext">' . $fulldata[$i][3] . '</td>';
                            echo '<td class="tdtext">';
                            if($fulldata[$i][4]==2){
                                echo 'Incorrect';
                                echo '</td>';
                                echo '<td class="tdtext">' . $fulldata[$i][5] . '</td>';
                            }else{
                                echo 'Correct';
                                echo '</td>';
                                echo '<td class="tdtext"></td>';
                            }
                           
                            echo '</tr>';
                            $j++;
                        }
                    }
                    ?>
                </table>
            </div>
            <br><br><br>
            <div class="message">
                You have completed the assessment. <br>Please close the window to return to Dashboard.
            </div>
        </div>

    </div>
    <script type='text/javascript' src='https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js'></script>
</body>

</html>