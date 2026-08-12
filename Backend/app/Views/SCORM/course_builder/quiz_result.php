<?php $isModernTheme = isset($coursedetails) && isset($coursedetails[0]['theme']) && in_array((string) $coursedetails[0]['theme'], ['8', '9'], true); ?>
<?php if (!$isModernTheme): ?>
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
<?php else: ?>
<style>
    /* Structure only: #quizContainer is a flex child of .wholeContainer (opened in
       header.php), so it needs a definite height to centre the results card. The
       legacy body flex-centering above is skipped for this theme. */
    .result_table { width: 100%; text-align: center; }
</style>
<?php endif; ?>
<?php if ($isModernTheme): ?>
    <?php /* quiz.js hides #parentquizContainer on the results screen, and Quiz_style.css
             centres the card via `#quizContainer:has(.results)` -- so quiz-start-view must
             NOT be set here (that class belongs to the start page). */ ?>
    <div id="parentquizContainer" style="display:none;"></div>
    <div id="quizContainer" class="<?php echo ($course_lang == 'Arabic') ? 'rtl' : ''; ?>">
<?php else: ?>
<div class="quiz_bg <?php echo ($course_lang == 'Arabic') ? 'rtl' : ''; ?>">
    <div class="quiz_area">
<?php endif; ?>
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
        <?php if ($isModernTheme) {
            /* Mirrors the card ModernTheme's own quiz.js builds for the results screen:
               #Startpageid.Startpage (white card) > p.headerAss (heading) >
               .Startpage_sub (body lines) > button.retrybtn.ColorSet_CR (teal action).
               Text, score and the Retry form/action are unchanged. */
            $quiz_question_path = base_url() . "SCORM/Course_builder/Review_course/quizQuestions/" . $course_id . "/" . $page_id . "/0";
        ?>
            <div class="results FSize20">
                <p id="resultsHeading" role="text" tabindex="0"><?php echo $Completeddescrip ?></p>
                <?php if ($Result == 'Passed') { ?>
                    <p tabindex="0"><?php echo $yourscoredescrip ?> : <span><?php echo $percentage . '%'; ?></span></p>
                    <p tabindex="0"><?php echo $congratstDescrip ?></p>
                <?php } elseif ($Result == 'Failed') { ?>
                    <p tabindex="0"><?php echo $yourscoredescrip ?> <span><?php echo $percentage . '%'; ?></span></p>
                    <p tabindex="0"><?php echo $faileddescrip ?></p>
                    <form action="<?php echo $quiz_question_path; ?>" method="POST"><?= csrf_field() ?>
                        <button id="start-quiz-btn" class="retrybtn ColorSet_CR FSize20" onclick="start_quiz()"><?php echo $retrydescrip ?></button>
                    </form>
                <?php } ?>
            </div>
        <?php } ?>
        <?php
        if (!$isModernTheme && $Result == 'Passed') {

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
        if (!$isModernTheme && $Result == 'Failed') {
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
<?php if ($isModernTheme): ?>
<?php /* closes .wholeContainer opened in header.php (this view renders no footer.php) */ ?>
</div>
<?php else: ?>
</div>
<?php endif; ?>