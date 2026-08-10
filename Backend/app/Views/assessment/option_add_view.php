<?php $userlevel = session()->get('userlevel');
$array = array_map('intval', str_split($userlevel));
$quizType = isset($row['quiz_type']) ? (string) $row['quiz_type'] : '';
?>
<style>
    .cyu-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        background: var(--ct-tertiary-bg);
        color: #6658dd;
        flex-shrink: 0;
    }

    .cyu-icon-primary {
        background: rgba(102, 88, 221, 0.12);
    }

    .cyu-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
    }

    [data-bs-theme="dark"] .cyu-card {
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.3);
    }

    .cyu-card-title {
        font-weight: 700;
        margin-bottom: 2px;
    }

    .cyu-card-subtitle {
        font-size: 12.5px;
        color: var(--ct-secondary-color);
    }

    .cyu-question-textarea {
        min-height: 90px;
        resize: vertical;
    }

    .cyu-char-count {
        font-size: 12px;
        color: var(--ct-secondary-color);
        white-space: nowrap;
    }

    .cyu-option-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: rgba(102, 88, 221, 0.12);
        color: #6658dd;
        font-weight: 600;
        font-size: 12.5px;
    }

    .cyu-option-text {
        border: 1px solid var(--ct-border-color);
        border-radius: 8px;
        padding: 8px 12px;
        min-height: 40px;
        background: var(--ct-form-control-bg);
        color: var(--ct-body-color);
    }

    .cyu-option-text:focus {
        outline: none;
        border-color: #6658dd;
        box-shadow: 0 0 0 0.15rem rgba(102, 88, 221, 0.15);
    }

    .cyu-option-score {
        border: 1px solid var(--ct-border-color);
        border-radius: 8px;
        padding: 8px 10px;
        min-height: 40px;
        background: var(--ct-form-control-bg);
        color: var(--ct-body-color);
        text-align: center;
    }

    .cyu-option-score:focus {
        outline: none;
        border-color: #6658dd;
        box-shadow: 0 0 0 0.15rem rgba(102, 88, 221, 0.15);
    }

    .cyu-saving-indicator {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11.5px;
        color: var(--ct-secondary-color);
        margin-top: 4px;
    }

    .cyu-toggle {
        position: relative;
        display: inline-block;
        width: 42px;
        height: 22px;
        margin: 0;
    }

    .cyu-toggle input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .cyu-toggle-slider {
        position: absolute;
        cursor: pointer;
        inset: 0;
        background-color: #fa5c7c;
        border-radius: 22px;
        transition: background-color .2s ease;
    }

    .cyu-toggle-slider::before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 3px;
        top: 3px;
        background-color: #fff;
        border-radius: 50%;
        transition: transform .2s ease;
    }

    .cyu-toggle input:checked+.cyu-toggle-slider {
        background-color: #0acf97;
    }

    .cyu-toggle input:checked+.cyu-toggle-slider::before {
        transform: translateX(20px);
    }

    .cyu-toggle input:focus-visible+.cyu-toggle-slider {
        box-shadow: 0 0 0 0.15rem rgba(102, 88, 221, 0.3);
    }

    .cyu-toggle-label {
        font-size: 11.5px;
        font-weight: 600;
    }

    .cyu-action-btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .cyu-add-option-btn {
        border: 1.5px dashed rgba(102, 88, 221, 0.4);
        color: #6658dd;
        background: rgba(102, 88, 221, 0.04);
        border-radius: 10px;
        padding: 10px;
        font-weight: 600;
        margin-top: 12px;
    }

    .cyu-add-option-btn:hover {
        background: rgba(102, 88, 221, 0.1);
        color: #6658dd;
    }

    .quiz-nav-btn {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background-color: rgba(var(--ct-primary-rgb), 0.1);
        color: rgb(var(--ct-primary-rgb));
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        border: none;
    }

    .quiz-nav-btn:hover {
        background-color: rgba(var(--ct-primary-rgb), 0.18);
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('my_training/read_more'); ?>">Course Detail</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header_link) ?>"><?php echo $header ?></a></li>
                </ol>
            </div>
            <h4 class="page-title"><?php echo $sub_header_1; ?></h4>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-12 d-flex align-items-center gap-2">
        <?php if ($prev_page) { ?>
            <form class="d-inline" action="<?php echo base_url('Assessment/trainings/add_quiz_option_view') ?>" method="POST"><?= csrf_field() ?>
                <input type="hidden" name="question_id" value="<?php echo $prev_page[0]['q_id'] ?>">
                <input type="hidden" name="page_id" value="<?php echo $prev_page[0]['page_id'] ?>">
                <input type="hidden" name="type" value="<?php echo $type ?>">
                <button type="submit" title="Previous Question" class="quiz-nav-btn waves-effect"><i class="mdi mdi-arrow-left"></i></button>
            </form>
        <?php } ?>
        <?php if ($next_page) { ?>
            <form class="d-inline" action="<?php echo base_url('Assessment/trainings/add_quiz_option_view') ?>" method="POST"><?= csrf_field() ?>
                <input type="hidden" name="question_id" value="<?php echo $next_page[0]['q_id'] ?>">
                <input type="hidden" name="page_id" value="<?php echo $next_page[0]['page_id'] ?>">
                <input type="hidden" name="type" value="<?php echo $type ?>">
                <button type="submit" title="Next Question" class="quiz-nav-btn waves-effect"><i class="mdi mdi-arrow-right"></i></button>
            </form>
        <?php } ?>
        <form class="d-inline ms-2" action="<?php echo base_url('Assessment/trainings/review_quiz') ?>" method="POST"><?= csrf_field() ?>
            <input type="hidden" name="scourse_id" value="<?php echo $row['scourse_id']; ?>">
            <input type="hidden" name="page_id" value="<?php echo $row['page_id'] ?>">
            <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill waves-effect waves-light">
                <i class="mdi mdi-eye-outline"></i> Review Quiz
            </button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Question -->
        <div class="card cyu-card mb-3">
            <div class="card-body">
                <form class="form-horizontal" id="quizQuestionForm" action="<?php echo base_url($editquestion) ?>" method="POST"><?= csrf_field() ?>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="cyu-icon cyu-icon-primary"><i class="mdi mdi-help-circle-outline"></i></span>
                        <label class="fw-bold mb-0">Question</label>
                    </div>

                    <textarea class="form-control cyu-question-textarea" id="quizQuestionText" name="question" maxlength="500"
                        placeholder="Type your question here&hellip;" required
                        oninput="document.getElementById('quizQuestionCount').textContent = this.value.length + ' / 500';"
                        onblur="quizSaveQuestion(this)"><?php echo isset($row['question']) ? htmlspecialchars($row['question']) : '' ?></textarea>
                    <span class="cyu-saving-indicator" id="quizQuestionSaving" style="display:none;"><i class="mdi mdi-loading mdi-spin"></i> Saving&hellip;</span>

                    <div class="d-flex justify-content-end mt-2">
                        <span class="cyu-char-count" id="quizQuestionCount"><?php echo isset($row['question']) ? mb_strlen($row['question']) : 0; ?> / 500</span>
                    </div>

                    <?php if (isset($coursevalidation)): ?>
                        <div class="alert alert-danger mt-2"><?= $coursevalidation->listErrors() ?></div>
                    <?php endif; ?>

                    <input type="hidden" name="q_id" value="<?php echo $row['q_id']; ?>">
                    <input type="hidden" name="typeval" value="<?php echo $typeval; ?>">
                </form>
            </div>
        </div>

        <div class="card cyu-card mb-3">
            <div class="card-body">
                <ul class="nav nav-pills nav-fill navtab-bg mb-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#quizOptionsTab" data-bs-toggle="tab" aria-expanded="true" role="tab" class="nav-link active">Options</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#quizSettingsTab" data-bs-toggle="tab" aria-expanded="false" role="tab" class="nav-link">Settings</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Options -->
                    <div class="tab-pane fade show active" id="quizOptionsTab" role="tabpanel">
                        <p class="cyu-card-subtitle mb-3">Add options, set a score, and mark the correct answer(s).</p>

                        <div class="table-responsive">
                            <table class="table cyu-options-table align-middle">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Option Text</th>
                                        <th width="12%" class="text-center">Score</th>
                                        <th width="15%" class="text-center">Correct</th>
                                        <th width="12%" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body">
                                    <?php
                                    $j = 0;
                                    $optionCount = !empty($getoptiondata) ? count($getoptiondata) : 0;
                                    if (!empty($getoptiondata)) {
                                        foreach ($getoptiondata as $eachoptiondata) {
                                            $j++;
                                    ?>
                                            <tr>
                                                <td><span class="cyu-option-num"><?php echo $j; ?></span></td>

                                                <td>
                                                    <div class="cyu-option-text" contenteditable="true"
                                                        onBlur="updateDate(this,'values','<?php echo $eachoptiondata['o_id'] ?>')"><?php echo $eachoptiondata['values'] ?></div>
                                                    <span class="cyu-saving-indicator" style="display:none;"><i class="mdi mdi-loading mdi-spin"></i> Saving&hellip;</span>
                                                </td>

                                                <td>
                                                    <div class="cyu-option-score" contenteditable="true"
                                                        onBlur="updateDate(this,'score','<?php echo $eachoptiondata['o_id'] ?>')"><?php echo ($eachoptiondata['score'] != 0) ? $eachoptiondata['score'] : '' ?></div>
                                                </td>

                                                <td class="text-center">
                                                    <?php
                                                    $truefalse = $eachoptiondata['truefalse'];
                                                    $optionId = $eachoptiondata['o_id'];
                                                    $isCorrect = $truefalse == 1;
                                                    ?>
                                                    <div class="d-flex flex-column align-items-center gap-1">
                                                        <?php if ($quizType === '112') { ?>
                                                            <label class="cyu-toggle" title="<?php echo $isCorrect ? 'Correct' : 'Wrong'; ?>">
                                                                <input type="checkbox" <?php echo $isCorrect ? 'checked' : ''; ?>
                                                                    onchange="toggleTrueFalse(this, '<?php echo $optionId; ?>')"
                                                                    data-question-id="<?php echo $eachoptiondata['question_id']; ?>"
                                                                    data-current="<?php echo $truefalse; ?>">
                                                                <span class="cyu-toggle-slider"></span>
                                                            </label>
                                                        <?php } else { ?>
                                                            <label class="cyu-toggle" title="<?php echo $isCorrect ? 'Correct' : 'Wrong'; ?>">
                                                                <input type="checkbox" <?php echo $isCorrect ? 'checked' : ''; ?>
                                                                    onchange="updateDate(this.checked ? '1' : '2','truefalse','<?php echo $optionId; ?>')">
                                                                <span class="cyu-toggle-slider"></span>
                                                            </label>
                                                        <?php } ?>
                                                        <span class="cyu-toggle-label <?php echo $isCorrect ? 'text-success' : 'text-danger'; ?>"><?php echo $isCorrect ? 'Correct' : 'Wrong'; ?></span>
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="d-flex justify-content-center gap-1">
                                                        <?php if ($optionCount <= 1) { ?>
                                                            <button type="button" class="btn btn-outline-danger waves-effect waves-light rounded-circle cyu-action-btn" title="A question must have at least one option" disabled><i class="mdi mdi-trash-can-outline"></i></button>
                                                        <?php } else { ?>
                                                            <button type="button" class="btn btn-outline-danger waves-effect waves-light rounded-circle cyu-action-btn" title="Delete"
                                                                onclick="updateDate('0','status','<?php echo $eachoptiondata['o_id'] ?>')"><i class="mdi mdi-trash-can-outline"></i></button>
                                                        <?php } ?>
                                                    </div>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </div>

                        <button type="button" id="addRowBtn" class="btn cyu-add-option-btn w-100" onclick="quizAddOption()">
                            <i class="mdi mdi-plus-circle-outline"></i> Add New Option
                        </button>
                    </div>
                    <!-- /Options tab -->

                    <!-- Settings -->
                    <div class="tab-pane fade" id="quizSettingsTab" role="tabpanel">
                        <p class="cyu-card-subtitle mb-3">Choose whether learners can select one or multiple correct options.</p>

                        <div class="mb-2" style="max-width:320px;">
                            <label class="fw-semibold mb-1">Quiz Type</label>
                            <select name="quiz_type" class="form-control" id="quizTypeSelect" onchange="quizSaveType(this)">
                                <?php if (!empty($AssessmentQuestionType)) {
                                    foreach ($AssessmentQuestionType as $eachQuizType) { ?>
                                        <option value="<?php echo $eachQuizType['id_d'] ?>" <?php echo ($quizType === (string) $eachQuizType['id_d']) ? 'selected' : ''; ?>>
                                            <?php echo $eachQuizType['name'] ?>
                                        </option>
                                <?php }
                                } ?>
                            </select>
                            <span class="cyu-saving-indicator" id="quizTypeSaving" style="display:none;"><i class="mdi mdi-loading mdi-spin"></i> Saving&hellip;</span>
                        </div>

                        <?php if (isset($coursevalidation)): ?>
                            <div class="alert alert-danger mt-2"><?= $coursevalidation->listErrors() ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card cyu-card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="cyu-icon cyu-icon-primary"><i class="mdi mdi-image-multiple-outline"></i></span>
                    <label class="fw-bold mb-0">Image / Video</label>
                </div>
                <p class="cyu-card-subtitle mb-3">JPG, PNG or MP4. Unique file name. Max size 100 MB.</p>

                <?php
                $base = base_url();
                if ($base == 'http://localhost:8888/projects_dochek/projects_dochek') {
                    $baseloc = '/Users/pchandran/Sites/projects_dochek/projects_dochek/';
                }
                if ($base == 'http://172.16.2.218/DOCHEK/') {
                    $baseloc = '/var/www/DOCHEK/';
                }
                if ($base == 'https://dochek.com/') {
                    $baseloc = '/var/www/html/';
                }
                if ($base == 'https://staging.dochek.com/') {
                    $baseloc = '/var/www/html/DOCHEK/';
                }
                if ($base == 'http://localhost/DOCHEKDOTCOM/') {
                    $baseloc = 'D:/wampp/www/DOCHEKDOTCOM/';
                }
                if ($base == 'http://localhost/DOCHEK/') {
                    $baseloc = 'C:/wamp64/www/DOCHEK/';
                }

                $fileloc = "";
                $imagename = isset($question_attachment_image[0]['doc_name']) ? $question_attachment_image[0]['doc_name'] : 'stest';
                $videoname = isset($question_attachment_video[0]['doc_name']) ? $question_attachment_video[0]['doc_name'] : 'stest';
                $imagefileloc = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $row['scourse_id'] . '/' . $getCourseData[0]['createdon'] . '/assets/Quiz/' . $row['page_id'] . '/assessment_image/' . $imagename;
                $videofileloc = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $row['scourse_id'] . '/' . $getCourseData[0]['createdon'] . '/assets/Quiz/' . $row['page_id'] . '/assessment_video/' . $videoname;

                if ($imagename != 'stest') {
                    $folderloc = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $row['scourse_id'] . '/' . $getCourseData[0]['createdon'] . '/assets/Quiz/' . $row['page_id'] . '/assessment_image';
                    if (!empty($question_attachment_image) && is_dir($folderloc)) {
                        foreach ($question_attachment_image as $key => $value) {
                            $fileloc = $folderloc . '/' . $value['doc_name'];
                            if (file_exists($fileloc)) {
                ?>
                                <div class="text-center mb-2">
                                    <img style="max-height:120px;" src="<?php echo base_url() ?>/assets/assets/uploads/SCORM_course_document/<?php echo $row['scourse_id']; ?>/<?php echo $getCourseData[0]['createdon']; ?>/assets/Quiz/<?php echo $row['page_id']; ?>/assessment_image/<?php echo $value['doc_name'] ?>"
                                        class="rounded border img-fluid" />
                                </div>
                                <form action="<?php echo base_url('Assessment/trainings/delquestion_file'); ?>" method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="qa_id" value="<?php echo isset($question_attachment_image[0]['qa_id']) ? $question_attachment_image[0]['qa_id'] : '' ?>">
                                    <input type="hidden" name="file_name" value="<?php echo $value['doc_name']; ?>">
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill waves-effect waves-light w-100"
                                        onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')">
                                        <span class="mdi mdi-trash-can-outline"></span> Delete Image
                                    </button>
                                </form>
                <?php
                            }
                        }
                    }
                } elseif ($videoname != 'stest') {
                    $folderloc = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $row['scourse_id'] . '/' . $getCourseData[0]['createdon'] . '/assets/Quiz/' . $row['page_id'] . '/assessment_video';
                    if (!empty($question_attachment_video) && is_dir($folderloc)) {
                        foreach ($question_attachment_video as $key => $value) {
                            $fileloc = $folderloc . '/' . $value['doc_name'];
                            if (file_exists($fileloc)) {
                ?>
                                <div class="mb-2"><i class="mdi mdi-file-video-outline"></i> <?php echo $value['doc_name']; ?></div>
                                <form action="<?php echo base_url('Assessment/trainings/delquestion_file'); ?>" method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="qa_id" value="<?php echo isset($question_attachment_video[0]['qa_id']) ? $question_attachment_video[0]['qa_id'] : '' ?>">
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill waves-effect waves-light w-100"
                                        onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')">
                                        <span class="mdi mdi-trash-can-outline"></span> Delete Video
                                    </button>
                                </form>
                <?php
                            }
                        }
                    }
                }

                if (file_exists($imagefileloc) || file_exists($videofileloc)) { ?>
                    <div class="cyu-card-subtitle mt-2">Delete the existing image or video to upload a new one.</div>
                <?php } else { ?>
                    <form enctype="multipart/form-data" action="<?php echo base_url($form_url_1) ?>" method="POST"><?= csrf_field() ?>
                        <div class="mb-2">
                            <input type="file" name="file" class="form-control" accept=".jpg,.png,.jpeg,.mp4" required />
                        </div>
                        <input type="hidden" name="scourse_id" value="<?php echo $row['scourse_id'] ?>">
                        <input type="hidden" name="createdon" value="<?php echo $getCourseData[0]['createdon'] ?>">
                        <input type="hidden" name="page_id" value="<?php echo $row['page_id'] ?>">
                        <input type="hidden" name="q_id" value="<?php echo $row['q_id'] ?>">
                        <input type="hidden" name="type" value="1">
                        <button type="submit" class="btn btn-outline-info waves-effect btn-sm waves-light w-100">
                            <i class="mdi mdi-upload"></i> Upload Image / Video
                        </button>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleTrueFalse(checkbox, optionId) {
        const questionId = checkbox.getAttribute('data-question-id');
        const currentStatus = checkbox.getAttribute('data-current');
        const newStatus = currentStatus === '1' ? '2' : '1';

        if (newStatus === '1') {
            const checkboxes = document.querySelectorAll(`input[data-question-id="${questionId}"]`);
            const alreadyCorrect = Array.from(checkboxes).some(cb =>
                cb.getAttribute('data-current') === '1'
            );

            if (alreadyCorrect) {
                alert("Only one correct answer is allowed for this single-choice question. Please unselect the current answer before selecting a new one.");
                checkbox.checked = false;
                return;
            }
        }

        updateDate(newStatus, 'truefalse', optionId);
    }

    // fetch(), not $.ajax(): jQuery's AJAX transport fails to construct its own XHR in this
    // app's environment ("Cannot read properties of undefined (reading 'open')", status 0 -
    // the request never reaches the network at all), so this silently did nothing on click.
    function updateDate(element, column, id) {
        var value = (column == 'truefalse' || column == 'status') ? element : element.innerText;

        var savingIndicator = null;
        if (column === 'values' && element && element.parentNode) {
            savingIndicator = element.parentNode.querySelector('.cyu-saving-indicator');
            if (savingIndicator) savingIndicator.style.display = 'inline-flex';
        }

        let scourse_id = '<?php echo $row['scourse_id'] ?>';
        let question_id = '<?php echo $row['q_id'] ?>';

        fetch('<?php echo base_url('Assessment/trainings/updatedateformat') ?>', {
                method: 'POST',
                body: new URLSearchParams({
                    value: value,
                    column: column,
                    id: id,
                    scourse_id: scourse_id,
                    question_id: question_id
                }),
                credentials: 'same-origin'
            })
            .then(function(response) {
                if (!response.ok) throw new Error('Request failed with status ' + response.status);
                return response.text();
            })
            .then(function(text) {
                try {
                    var obj = JSON.parse(text);
                    if (obj && obj.status && obj.status !== 'OK') {
                        alert(obj.status);
                    }
                } catch (e) {
                    console.log('Could not parse updatedateformat response as JSON:', text);
                }
                location.reload();
            })
            .catch(function() {
                if (savingIndicator) savingIndicator.style.display = 'none';
                console.log('request failed');
            });
    }

    // Creates a real (empty) option row server-side, then reloads so it comes back fully
    // rendered - simpler and less fragile than hand-building a temporary row.
    function quizAddOption() {
        let scourse_id = '<?php echo $row['scourse_id'] ?>';
        let question_id = '<?php echo $row['q_id'] ?>';
        let page_type = '<?php echo $type ?>';
        fetch('<?php echo base_url('Assessment/trainings/adddateformat') ?>', {
                method: 'POST',
                body: new URLSearchParams({
                    value: '',
                    column: 'values',
                    id: 'new',
                    scourse_id: scourse_id,
                    question_id: question_id,
                    page_type: page_type
                }),
                credentials: 'same-origin'
            })
            .then(function(response) {
                if (!response.ok) throw new Error('Request failed with status ' + response.status);
                location.reload();
            })
            .catch(function() {
                console.log('request failed');
            });
    }

    // Auto-saves the Question text on blur (clicking/tabbing away) instead of requiring a
    // separate "Update" button.
    function quizSaveQuestion(textarea) {
        var form = document.getElementById('quizQuestionForm');
        var savingIndicator = document.getElementById('quizQuestionSaving');
        if (savingIndicator) savingIndicator.style.display = 'inline-flex';

        fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                credentials: 'same-origin'
            })
            .then(function(response) {
                if (!response.ok) throw new Error('Request failed with status ' + response.status);
                location.reload();
            })
            .catch(function() {
                if (savingIndicator) savingIndicator.style.display = 'none';
                alert('Unable to save the question. Please try again.');
            });
    }

    // Auto-saves the Quiz Type selector on change - only "quiz_type" is sent, so (after the
    // matching backend fix) it never touches this question's other fields.
    function quizSaveType(select) {
        var savingIndicator = document.getElementById('quizTypeSaving');
        if (savingIndicator) savingIndicator.style.display = 'inline-flex';

        fetch('<?php echo base_url('Assessment/trainings/edit_attempts_question') ?>', {
                method: 'POST',
                body: new URLSearchParams({
                    quiz_type: select.value,
                    q_id: '<?php echo $row['q_id'] ?>',
                    typeval: '<?php echo $typeval ?>',
                    page_id: '<?php echo $row['page_id'] ?>',
                    returnUrl: '2'
                }),
                credentials: 'same-origin'
            })
            .then(function(response) {
                if (!response.ok) throw new Error('Request failed with status ' + response.status);
                location.reload();
            })
            .catch(function() {
                if (savingIndicator) savingIndicator.style.display = 'none';
                alert('Unable to save the quiz type. Please try again.');
            });
    }
</script>
