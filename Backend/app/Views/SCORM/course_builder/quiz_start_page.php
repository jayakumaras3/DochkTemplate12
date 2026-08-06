<div class="quiz_bg <?php echo ($course_lang == 'Arabic') ? 'rtl' : ''; ?>">
    <div class="quiz_area">


        <?php

        if ($getAssessmentSettings) {
            foreach ($getAssessmentSettings as $value) {
                $type = $value['type'];
                // print_r($type."<br/>");
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
                <?php
                $quiz_question_path = base_url() . "SCORM/Course_builder/Review_course/quizQuestions/" . $course_id . "/" . $page_id . "/0";
                ?>
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