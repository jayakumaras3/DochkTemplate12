<!doctype html>
<html>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Quiz</title>
    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' rel='stylesheet'>
    <link href='' rel='stylesheet'>
    <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
    <script type="text/javascript">
        function disableButton() {
            //  document.getElementById("nxtBtn").style.display = "none";
            //      alert('Disabled');
        }
    </script>
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
            background-color: #D2D4DA;
            color: #000;
            padding: 10px;
            font-family: 'Montserrat', sans-serif;
            max-width: 700px;

        }

        .container>p {
            font-size: 32px
        }

        .qnumber {
            font-size: 20px;
            text-align: center;
        }

        .question {
            width: 100%;
        }

        .instruction {
            color: blue;
            font-size: 12px;
            margin-left: 10px;
        }

        .label_around {
            background-color: white;
            margin: 5px;
            padding: 2px;
        }

        .options {
            position: relative;
            padding-left: 40px;
        }

        #options label {
            display: block;
            margin: 10px;
            font-size: 14px;
            cursor: pointer
        }


        .options input {
            opacity: 0
        }

        .checkmark {
            position: absolute;
            top: -1px;
            left: 0;
            height: 25px;
            width: 25px;
            background-color: #555;
            border: 1px solid #ddd;
            border-radius: 50%
        }

        .options input:checked~.checkmark:after {
            display: block
        }

        .options .checkmark:after {
            content: "";
            width: 10px;
            height: 10px;
            display: block;
            background: white;
            position: absolute;
            top: 50%;
            left: 50%;
            border-radius: 50%;
            transform: translate(-50%, -50%) scale(0);
            transition: 300ms ease-in-out 0s
        }

        .options input[type="radio"]:checked~.checkmark {
            background: #21bf73;
            transition: 300ms ease-in-out 0s
        }

        .options input[type="radio"]:checked~.checkmark:after {
            transform: translate(-50%, -50%) scale(1)
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
        <div class="question">
            <div class="qnumber">
                <?php
                echo 'QUESTION - ' . $current_question . '/' . $total_questions;
                ?>
            </div>
            <hr class="orange_line">
            <div class="row">
                <div class="col-12 question"><b><?php echo $question[0]['question']; ?></b></div>
            </div>
            <?php
            if (!empty($checkpdf)) {
                foreach ($checkpdf as $checkpdf) :
                    $filename = $checkpdf['doc_name'];
                    echo '<div class="row"><div class="col-12 pt-3">';
                    //Code modified as on 02-13-2023 to open documents in a popup window. 
                    echo "<a href=\"javascript:window.open('";
                    echo  base_url() . '/assets/upload/assessment_image/' . $question_id . '/' . $filename;
                    echo "','mypopuptitle','width=600,height=400')\">View PDF</a>";
                    //End of new code done on 02-13-2023.

                    //Old code to open PDF documents in a seperate window. 
                    //echo '<a href="' . base_url() . '/upload/quiz_images/' . $question_id . '/' . $filename . '" target="_blank">View PDF</a>';
                    //End of old code.

                    echo '</div></div>';
                endforeach;
            }
            ?>
            <div class="row">
                <?php
                if (!empty($checkfileexists)) {
                    echo '<div class="col-6 pt-3" id="options">';
                } else {
                    echo '<div class="col-12 pt-3" id="options">';
                }

                echo '<form class="confirm"  action="' . base_url('Assessment/launch/start_assessment') . '" method="post" accept-charset="utf-8" enctype="multipart/form-data" >';
                echo' <?= csrf_field() ?>';
                if (!empty($option_array)) {
                    foreach ($option_array as $option_array) :
                        echo '<div class="label_around"><label class="options">';
                        echo $option_array['values'];
                        echo '<input type="radio" name="quiz_option" required="required" value="';
                        echo $option_array['o_id'];
                        echo '"> <span class="checkmark"></span> </label></div>';
                    endforeach;
                }
                echo '</div>';

                if (!empty($checkfileexists)) {
                    $filename = $checkfileexists[0]['doc_name'];
                    echo '<div class="col-6 pt-3">';
                    echo '<img src="' . base_url() . '/assets/assets/uploads/assessment_image/' . $question_id . '/' . $filename . '" alt="user-avatar" height="250px" class="img-fluid">';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-12 pt-3 instruction"><i>Select the correct option. Click Next to continue.</i></div>
        </div>
        <div class="d-flex align-items-right pt-3">
            <div class="ml-auto mr-sm-5">
                <?php
                echo '<input type="hidden" value="' . $current_question . '" name="q_num" />';
                echo '<input type="hidden" value="' . $question_id . '" name="q_id" />';
                echo '<input type="hidden" value="' . $scourse_id . '" name="scourse_id" />';
                if (!empty($option_array)) {

                    echo '<button id="nxtBtn" type="submit" onclick="disableButton()" class="btn btn-primary btn-sm nxt-btn">Next</button>';
                } else {
                    echo '<span style="font-size: 12px; color: red;"><i>Error displaying options. Your data will not be saved.</i></span>';
                }
                echo '</form>';
                // echo  $_SESSION['globalval']['qarray'][0];
                ?>
            </div>
        </div>

    </div>
    <script type='text/javascript' src='https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js'></script>
</body>

</html>