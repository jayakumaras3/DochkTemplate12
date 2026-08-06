<style>
    body {
        margin: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        /* Horizontal center */
        align-items: center;
        /* Vertical center */
        background-color: #f9f9f9;
        /* Optional: for visibility */
    }

    .result_table {
        width: 100%;
        color: black;
        text-align: center;
    }

    h3 {
        margin: 0 0 10px 0;
    }
</style>
<div class="quiz_bg <?php echo ($course_lang == 'Arabic') ? 'rtl' : ''; ?>">
    <div class="quiz_area">
        <?php if ($getAssessmentSettings) {
            foreach ($getAssessmentSettings as $value) {
                $type = $value['type'];
                // print_r($type."<br/>");
                if ($type == 46) {
                    $retrydescrip = $value['value'];
                }
                if ($type == 36) {
                    $Completeddescrip = $value['value'];
                }
                if ($type == 37) {
                    $yourscoredescrip = $value['value'];
                }
                if ($type == 38) {
                    $congratstDescrip = $value['value'];
                }
                if ($type == 39) {
                    $faileddescrip = $value['value'];
                }
                if ($type == 47) {
                    $viewresultDescrip = $value['value'];
                }
                if ($type == 67) {
                    $contactdescription = $value['value'];
                }
            }
        }
        $retrydescrip = (isset($retrydescrip) &&  $retrydescrip != '') ? $retrydescrip : $assessment_sets['46'];
        $Completeddescrip = (isset($Completeddescrip) &&  $Completeddescrip != '') ? $Completeddescrip : $assessment_sets['36'];
        $yourscoredescrip = (isset($yourscoredescrip) &&  $yourscoredescrip != '') ? $yourscoredescrip : $assessment_sets['37'];
        $congratstDescrip = (isset($congratstDescrip) &&  $congratstDescrip != '') ? $congratstDescrip : $assessment_sets['38'];
        $faileddescrip = (isset($faileddescrip) &&  $faileddescrip != '') ? $faileddescrip : $assessment_sets['39'];
        $viewresultDescrip = (isset($viewresultDescrip) &&  $viewresultDescrip != '') ? $viewresultDescrip : $assessment_sets['47'];
        $contactdescription = (isset($contactdescription) &&  $contactdescription != '') ? $contactdescription : $assessment_sets['67'];
        ?>
        <?php
        if ($Result == 'Passed') {

        ?>
            <table width="100%" class="result_table" style="margin-top:15%;color:black">
                <!-- <tr>
                    <td><span style="font-size: 30px;  font-weight: bold;"><?php echo $viewresultDescrip ?></span></td>
                </tr> -->
                <!-- <tr>
                    <td><?php echo $Completeddescrip ?></td>
                </tr> -->

                <!-- <tr>
                    <td><img src="<?php echo base_url('assets/assets/img/Thumbsup.png'); ?>"></td>
                </tr> -->
                <!-- <tr>
                    <td><span style="color:#00847e; font-size: 30px;  font-weight: bold;"><?php echo $viewresultDescrip ?></span></td>
                </tr> -->

                <tr>
                    <td>
                        <h3><?php echo $Completeddescrip ?></h3>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $yourscoredescrip ?> : <span><?php echo $percentage . '%'; ?></span></td>
                </tr>
                <tr>
                    <td><?php echo $congratstDescrip ?></td>
                </tr>
                <!-- <tr>
                    <td><span style="color:#004b4b; font-size: 30px;  font-weight: bold;"><?php echo $percentage . '%'; ?></span></td>
                </tr> -->
            </table>
        <?php
        }
        if ($Result == 'Failed') {
            $quiz_question_path = base_url() . "SCORM/Course_builder/Review_course/quizQuestions/" . $course_id . "/" . $page_id . "/0";
        ?>
            <table width="100%" class="result_table" style="margin-top:15%;">
                <!-- <tr>
                    <td><span style="font-size: 30px;  font-weight: bold;"><?php echo  $viewresultDescrip ?></span></td>
                </tr> -->
                <tr>
                    <td>
                        <h3><?php echo $Completeddescrip ?></h3>
                    </td>
                </tr>

                <!-- <tr>
                    <td><img src="<?php echo base_url('assets/assets/img/Thumbsdown.png'); ?>"></td>
                </tr> -->
                <!-- <tr>
                    <td><span style="color:#ee6300; font-size: 30px;  font-weight: bold;">Sorry!</span></td>
                </tr> -->



                <tr>
                    <td><?php echo $yourscoredescrip ?> <span><?php echo $percentage . '%'; ?></span></td>
                </tr>
                <tr>
                    <td><?php echo $faileddescrip ?></td>
                </tr>
                <!-- <tr>
                    <td></td>
                </tr> -->
                <tr>
                    <td>
                        <form action="<?php echo $quiz_question_path; ?>" method="POST"><?= csrf_field() ?>
                            <button style="all: unset; cursor: pointer;">
                                <button id="start-quiz-btn" class="quiz_start_btn" onclick="start_quiz()"><?php echo $retrydescrip ?></button>
                            </button>
                        </form>
                    </td>
                </tr>
            </table>
        <?php
        }
        ?>
    </div>
</div>