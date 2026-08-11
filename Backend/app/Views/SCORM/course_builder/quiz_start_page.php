<?php
// Settings parsing hoisted out of the markup so both theme branches can use the values.
if ($getAssessmentSettings) {
    foreach ($getAssessmentSettings as $value) {
        $type = $value['type'];
        if ($type == 21) {
            $duration = $value['value'];
        }
        if ($type == 22) {
            $total_questions = $value['value'];
        }

        if ($type == 23) {
            $passing = $value['value'];
        }
        if ($type == 24) {
            $attempts = $value['value'];
        }
        if ($type == 31) {
            $description = $value['value'];
        }
        if ($type == 42) {
            $assessmentdescrip = $value['value'];
        }
        if ($type == 33) {
            $totalquestionsdescrip = $value['value'];
        }
        if ($type == 34) {
            $passingdescrip = $value['value'];
        }
        if ($type == 35) {
            $attemptsdescrip = $value['value'];
        }
        if ($type == 41) {
            $durationdescrip = $value['value'];
        }
        if ($type == 43) {
            $reattemptdescrip = $value['value'];
        }
        if ($type == 44) {
            $startdescrip = $value['value'];
        }
        if ($type == 48) {
            $begindescrip = $value['value'];
        }
        if ($type == 71) {
            $minutesdescrip = $value['value'];
        }
    }
    $assessmentdescrip = (isset($assessmentdescrip) &&  $assessmentdescrip != '') ? $assessmentdescrip : $assessment_sets['42'];
    $totalquestionsdescrip = (isset($totalquestionsdescrip) &&  $totalquestionsdescrip != '') ? $totalquestionsdescrip : $assessment_sets['33'];
    $passingdescrip = (isset($passingdescrip) &&  $passingdescrip != '') ? $passingdescrip : $assessment_sets['34'];
    $attemptsdescrip = (isset($attemptsdescrip) &&  $attemptsdescrip != '') ? $attemptsdescrip : $assessment_sets['35'];
    $durationdescrip = (isset($durationdescrip) &&  $durationdescrip != '') ? $durationdescrip : $assessment_sets['41'];
    $reattemptdescrip = (isset($reattemptdescrip) &&  $reattemptdescrip != '') ? $reattemptdescrip : $assessment_sets['43'];
    $startdescrip = (isset($startdescrip) &&  $startdescrip != '') ? $startdescrip : $assessment_sets['44'];
    $begindescrip = (isset($begindescrip) &&  $begindescrip != '') ? $begindescrip : $assessment_sets['48'];
    $minutesdescrip = (isset($minutesdescrip) &&  $minutesdescrip != '') ? $minutesdescrip : $assessment_sets['71'];
}
$quiz_question_path = base_url() . "SCORM/Course_builder/Review_course/quizQuestions/" . $course_id . "/" . $page_id . "/0";
$isModernTheme = isset($coursedetails) && isset($coursedetails[0]['theme']) && $coursedetails[0]['theme'] == '8';
?>
<?php if ($isModernTheme): ?>
    <?php /* Mirrors the start-page markup ModernTheme's own scripts/QuizTemplate/Quiz/quiz.js
             builds at runtime: #quizContainer.quiz-start-view > #Startpageid.Startpage >
             (p.headerAss, .Startpage_sub, button.Startpagebtn.ColorSet_CR, p#inste).
             #parentquizContainer is the dark question bar, hidden on the start view exactly
             as quiz.js does. Wrapper/classes only - the Start form is unchanged. */ ?>
    <div id="parentquizContainer" style="display:none;"></div>
    <div id="quizContainer" class="quiz-start-view <?php echo ($course_lang == 'Arabic') ? 'rtl' : ''; ?>">
        <div id="Startpageid" class="Startpage FSize20">
            <?php if ($getAssessmentSettings) { ?>
                <p class="headerAss FSize38"><?php echo $assessmentdescrip ?></p>
                <div class="Startpage_sub FSize20">
                    <?php if (isset($description) && $description != '') { ?>
                        <p><?php echo $description; ?></p>
                    <?php } ?>
                    <?php if (isset($total_questions)) { ?>
                        <p><?php echo $totalquestionsdescrip . $total_questions; ?></p>
                    <?php } ?>
                    <?php if (isset($passing)) { ?>
                        <p><?php echo $passingdescrip . $passing . '%'; ?></p>
                    <?php } ?>
                    <?php if (isset($attempts)) { ?>
                        <p><?php echo $attemptsdescrip . $attempts; ?></p>
                    <?php } ?>
                    <?php if (isset($duration)) { ?>
                        <p><?php echo $durationdescrip . $duration . ' ' . $minutesdescrip; ?></p>
                    <?php } ?>
                    <p><?php echo $reattemptdescrip; ?></p>
                </div>
            <?php } ?>
            <form action="<?php echo $quiz_question_path; ?>" method="POST"><?= csrf_field() ?>
                <button id="start-quiz-btn" class="Startpagebtn ColorSet_CR FSize20"><?php echo isset($startdescrip) ? $startdescrip : ''; ?></button>
            </form>
            <p id="inste" class="ID_ColorSet_CR FSize20"><?php echo isset($begindescrip) ? $begindescrip : ''; ?></p>
        </div>
    </div>
    <?php /* closes .wholeContainer opened in header.php (this view renders no footer.php) */ ?>
    </div>
<?php else: ?>
<div class="quiz_bg <?php echo ($course_lang == 'Arabic') ? 'rtl' : ''; ?>">
    <div class="quiz_area">


        <?php
        if ($getAssessmentSettings) {
        ?>
            <div class="assessment_text">
                <strong><?php echo $assessmentdescrip ?></strong>
            </div>
            <div class="assessment_start_text">
            <?php echo isset($description) ? $description : '';
            echo '<br>';
            echo isset($total_questions) ? $totalquestionsdescrip . $total_questions : '';
            echo '<br>';
            echo isset($passing) ? $passingdescrip . $passing . '%' : '';
            echo '<br>';
            echo isset($attempts) ? $attemptsdescrip . $attempts : '';
            echo '<br>';
            echo isset($duration) ? $durationdescrip . $duration . ' ' . $minutesdescrip  : '';
            echo '<br><br>';
            echo $reattemptdescrip;
        }
            ?>
            </div>
            <div class="start_btn">
                <div>
                    <form action="<?php echo $quiz_question_path; ?>" method="POST"><?= csrf_field() ?>
                        <button style="all: unset; cursor: pointer;">
                            <button id="start-quiz-btn" class="quiz_start_btn"><?php echo $startdescrip; ?></button>
                        </button>
                    </form>
                </div>
            </div>
            <br>
            <span class="start_instructions"><?php echo $begindescrip ?></span>
    </div>
</div>
<?php endif; ?>
