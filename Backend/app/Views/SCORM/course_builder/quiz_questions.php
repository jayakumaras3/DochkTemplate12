<style>
    .form-check {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-left: 10px;
        /* margin-bottom: 12px; */
    }

    .form-check-input {
        vertical-align: middle;
        margin-top: 0.3em;
        /* adjust as needed */
        margin-right: 8px;
        position: relative;
        top: 3px;
        /* padding: 2px; */
        /* manually nudge down */
    }

    .form-check-label {
        line-height: 2;
        word-break: break-word;
    }

    /*Image Zoom in */
    /* Image Zoom In */
    .image-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.9);
        justify-content: center;
        align-items: center;
    }

    .image-modal.active {
        display: flex;
    }

    .modal-content {
        border-radius: 4px;
        box-shadow: 0 0 12px rgba(255, 255, 255, 0.2);
    }

    .close-btn {
        position: absolute;
        top: 10px;
        right: 25px;
        color: white;
        font-size: 32px;
        font-weight: bold;
        cursor: pointer;
        z-index: 10000;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    /* Optional: Improve behavior on very small screens */
    @media (max-width: 768px) {
        .modal-content {
            max-width: 95vw;
            max-height: 75vh;
        }
    }


    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }
    
</style>
<div class="quiz_option_bg <?php echo ($course_lang == 'Arabic') ? 'rtl' : ''; ?>">
    <div class="quiz_area">
        <?php if ($getAssessmentSettings) {
            foreach ($getAssessmentSettings as $value) {
                $type = $value['type'];
                // print_r($type."<br/>");
                if ($type == 40) {
                    $selectcorrectdescrip = $value['value'];
                }
                if ($type == 45) {
                    $submitdescrip = $value['value'];
                }
                if ($type == 47) {
                    $viewresultDescrip = $value['value'];
                }
                if ($type == 46) {
                    $imagedescrip = $value['value'];
                }
                if ($type == 73) {
                    $imagedescrip = $value['value'];
                }
                if ($type == 69) {
                    $questionDescrip = $value['value'];
                }
                if ($type == 70) {
                    $ofdescrip = $value['value'];
                }
                if ($type == 71) {
                    $minutesdescrip = $value['value'];
                }
            }
        }
        $selectcorrectdescrip = (isset($selectcorrectdescrip) &&  $selectcorrectdescrip != '') ? $selectcorrectdescrip : $assessment_sets['40'];
        $selectcorrectmcqdescrip = (isset($selectcorrectmcqdescrip) &&  $selectcorrectmcqdescrip != '') ? $selectcorrectmcqdescrip : $assessment_sets['40'];
        $submitdescrip = (isset($submitdescrip) &&  $submitdescrip != '') ? $submitdescrip : $assessment_sets['45'];
        $viewresultDescrip = (isset($viewresultDescrip) &&  $viewresultDescrip != '') ? $viewresultDescrip : $assessment_sets['47'];
        $retrydescrip = (isset($retrydescrip) &&  $retrydescrip != '') ? $retrydescrip : $assessment_sets['46'];
        $imagedescrip = (isset($imagedescrip) &&  $imagedescrip != '') ? $imagedescrip : $assessment_sets['73'];

        $questionDescrip = (isset($questionDescrip) &&  $questionDescrip != '') ? $questionDescrip : $assessment_sets['69'];
        $ofdescrip = (isset($ofdescrip) &&  $ofdescrip != '') ? $ofdescrip : $assessment_sets['70'];
        $minutesdescrip = (isset($minutesdescrip) &&  $minutesdescrip != '') ? $minutesdescrip : $assessment_sets['71'];

        ?>
        <div class="question-number"><strong>
                <?php
                if ($next_sequence != 'NoQuestions') {
                    echo $questionDescrip . ' ' . $next_sequence . ' ' . $ofdescrip . ' ' . $totalQuestions;
                } else {
                    echo $questionDescrip . ' ' . $totalQuestions . ' ' . $ofdescrip . ' ' . $totalQuestions;
                }
                ?>
            </strong>
        </div>
        <table style="width: 100%;">
            <tr>
                <!-- Optional: Bulb Icon (first column) -->
                <td style="vertical-align: top; ">
                    <div class="Quiz_question_img">
                        <img src="<?php echo base_url('assets/assets/img/Bulb.png'); ?>" class="img-thumbnail" width="80" height="80" style="border:0;">
                    </div>
                </td>

                <!-- Main Content Column -->
                <td style="vertical-align: top;width:95%">
                    <!-- Question Text -->
                    <div class="quiz_stem mb-2" style="width:90%;">
                        <?php
                        if ($course_lang == 'Arabic') {
                            echo $QuizQuestions[0]['question']; // Assuming the Arabic version of the question is already loaded here
                        } else {
                            echo $QuizQuestions[0]['question'];
                        }
                        ?>
                    </div>


                    <!-- Instructions -->
                    <div class="quiz_instructions">
                        <?php
                        $quiz_type = $QuizQuestions[0]['quiz_type'];
                        echo ($quiz_type == 115) ? $selectcorrectmcqdescrip : $selectcorrectdescrip;
                        ?>
                    </div>

                    <!-- Options and Image Side by Side -->
                    <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                        <!-- Options Block -->
                        <div style="flex: 2 1 45%;">
                            <form action="<?php echo base_url('SCORM/Course_builder/Review_course/questionSubmitted/' . $course_id . '/' . $page_id . '/' . $next_sequence); ?>" method="POST"><?= csrf_field() ?>
                                <?php
                                if (isset($optionsval)) {
                                    if ($RandomizeOptions == 'Enabled') {
                                        shuffle($optionsval);
                                    }

                                    foreach ($optionsval as $options) {
                                        $values = trim($options['values']);
                                        if (!empty($values)) {
                                            $inputType = ($quiz_type == 115) ? 'checkbox' : 'radio';
                                ?>
                                            <div class="form-check mb-2">
                                                <input type="<?php echo $inputType; ?>" class="form-check-input" name="optionID[]" value="<?php echo $options['o_id']; ?>">
                                                <label class="form-check-label"><?php echo $values; ?></label>
                                            </div>
                                <?php
                                        }
                                    }
                                } else {
                                    echo 'No options available.';
                                }
                                ?>

                                <!-- Hidden Fields -->
                                <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                                <input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
                                <input type="hidden" name="sc_uid" value="<?php echo $sc_uid; ?>">
                                <input type="hidden" name="questionId" value="<?php echo $QuizQuestions[0]['q_id']; ?>">
                                <input type="hidden" name="attempt" value="<?php echo $attempt; ?>">

                                <br>
                                <button id="submit-quiz-btn" class="submit-quiz-btn btn btn-primary mt-2"
                                    <?php echo ($quiz_type != 115) ? 'disabled' : ''; ?>>
                                    <?php echo ($next_sequence != 'NoQuestions') ? $submitdescrip : $viewresultDescrip; ?>
                                </button>
                            </form>
                        </div>

                        <!-- Image Block -->
                        <?php if (!empty($question_attachment)) {
                            $file = $question_attachment[0]['doc_name'];
                            $file_path = base_url('assets/assets/uploads/SCORM_course_document/' . $course_id . '/' . $getCourseData[0]['createdon'] . '/assets/Quiz/' . $page_id . '/assessment_image/' . $file);
                            $vidofile_path = base_url('assets/assets/uploads/SCORM_course_document/' . $course_id . '/' . $getCourseData[0]['createdon'] . '/assets/Quiz/' . $page_id . '/assessment_video/' . $file);


                            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION)); // Get file extension
                        ?>

                            <div style="flex: 1 1 45%; ">
                                <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) { ?>
                                    <div style="text-align: center;">
                                        <img src="<?php echo $file_path; ?>" alt="<?php echo $file; ?>" id="zoomableImage" class="zoomable-image" style="max-width: 100%; max-height: 250px; cursor: zoom-in;">
                                        <div style="padding-top:10px; color: #e1251a;"><?php echo $imagedescrip; ?></div>
                                    </div>
                                    <div id="imageModal" class="image-modal">
                                        <span class="close-btn" onclick="closeModal()">&times;</span>
                                        <img id="modalImg" class="modal-content" />
                                    </div>
                                <?php } elseif (in_array($ext, ['mp4', 'webm', 'ogg'])) { ?>
                                    <video height="auto" controls style="max-width:90%;max-height: 200px;">
                                        <source src="<?php echo $vidofile_path; ?>" type="video/<?php echo $ext; ?>">
                                        Your browser does not support the video tag.
                                    </video>

                                <?php } else { ?>
                                    <p>Unsupported file format: <?php echo $ext; ?></p>
                                <?php } ?>
                            </div>

                        <?php } ?>

                    </div>
                </td>
            </tr>
        </table>

    </div>
</div>
<script>
    // Get all checkbox inputs and the submit button
    const checkboxes = document.querySelectorAll('.form-check-input');
    const submitButton = document.getElementById('submit-quiz-btn');

    // Function to check if any checkbox is checked
    function checkCheckboxes() {
        let isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
        if (isChecked) {
            submitButton.disabled = false;
            submitButton.classList.remove('faded'); // Remove the faded class if enabled
        } else {
            submitButton.disabled = true;
            submitButton.classList.add('faded'); // Add the faded class if disabled
        }
    }

    // Add event listeners to all checkboxes to detect changes
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', checkCheckboxes);
    });

    // Initial check in case any checkbox is pre-selected
    checkCheckboxes();
</script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const zoomableImage = document.getElementById("zoomableImage");
        const modal = document.getElementById("imageModal");
        const modalImg = document.getElementById("modalImg");
        const closeBtn = document.querySelector(".close-btn");

        zoomableImage.addEventListener("click", () => {
            modal.classList.add("active");
            modalImg.src = zoomableImage.src;

            modalImg.onload = resizeImage; // Run after image loads
        });

        closeBtn.addEventListener("click", () => {
            modal.classList.remove("active");
        });

        modal.addEventListener("click", (e) => {
            if (e.target === modal) {
                modal.classList.remove("active");
            }
        });

        window.addEventListener("resize", () => {
            if (modal.classList.contains("active")) {
                resizeImage();
            }
        });

        // Your resize function
        function resizeImage() {
            const imgNaturalWidth = modalImg.naturalWidth;
            const imgNaturalHeight = modalImg.naturalHeight;

            const screenWidth = window.innerWidth * 0.9;
            const screenHeight = window.innerHeight * 0.9;

            const imgRatio = imgNaturalWidth / imgNaturalHeight;
            const screenRatio = screenWidth / screenHeight;

            if (imgRatio > screenRatio) {
                modalImg.style.width = screenWidth + "px";
                modalImg.style.height = "auto";
            } else {
                modalImg.style.height = screenHeight + "px";
                modalImg.style.width = "auto";
            }
        }
    });
</script>