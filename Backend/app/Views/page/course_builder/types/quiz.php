<?php
// Quiz settings (Pass %, Duration, Max Questions, Randomize Questions/Options) come from
// $getAssessmentSettings - already fetched by Editor::page_content() - keyed here by type for
// easy lookup. Type IDs match app/Views/assessment/assessment_settings_view.php. Editing now
// happens entirely on that separate "Quiz Settings" page (linked below) - this page only
// displays the current values as read-only stat tiles, so there's no local save/Sid plumbing
// to carry here anymore.
$quizSettingsByType = [];
if (!empty($getAssessmentSettings)) {
    foreach ($getAssessmentSettings as $eachSetting) {
        $quizSettingsByType[$eachSetting['type']] = $eachSetting;
    }
}
$quizDuration       = $quizSettingsByType[21]['value'] ?? 0;
$quizMaxQuestions   = $quizSettingsByType[22]['value'] ?? 0;
$quizPassPercentage = $quizSettingsByType[23]['value'] ?? 0;
$quizRandomizeQuestions = (($quizSettingsByType[1]['value'] ?? 'Disabled') === 'Enabled');
$quizRandomizeOptions   = (($quizSettingsByType[2]['value'] ?? 'Disabled') === 'Enabled');
$quizQuestionCount = !empty($getQuestiondata) ? count($getQuestiondata) : 0;

// Only these two quiz_type values exist anywhere in this app today (see the "Type of Question"
// select below) - no True/False or Match-the-Following question type exists in the data model.
$quizTypeLabels = [
    112 => ['label' => lang('UI_Text.CB_Type_SCQ'), 'icon' => 'mdi-radiobox-marked', 'class' => 'quiz-type-single'],
    115 => ['label' => lang('UI_Text.CB_Type_MCQ'), 'icon' => 'mdi-checkbox-marked-outline', 'class' => 'quiz-type-multi'],
];
?>
<style>
    .quiz-stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        background: rgba(var(--ct-primary-rgb), 0.1);
        color: rgb(var(--ct-primary-rgb));
        flex-shrink: 0;
    }

    .quiz-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
    }

    [data-bs-theme="dark"] .quiz-card {
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.3);
    }

    .quiz-card-title {
        font-weight: 700;
        margin-bottom: 2px;
    }

    .quiz-card-subtitle {
        font-size: 12.5px;
        color: var(--ct-secondary-color);
    }

    .question-cell {
        max-width: 480px;
        overflow: hidden;
        text-overflow: ellipsis;
        vertical-align: middle;
    }

    .quiz-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .quiz-type-single {
        background: rgba(10, 207, 151, 0.12);
        color: #0acf97;
    }

    .quiz-type-multi {
        background: rgba(102, 88, 221, 0.12);
        color: #6658dd;
    }

    .quiz-action-btn {
        width: 30px;
        height: 30px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .quiz-add-question-modal {
        border-radius: 18px;
        border: none;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.15);
    }

    [data-bs-theme="dark"] .quiz-add-question-modal {
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.45);
    }

    .quiz-modal-icon {
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

    .quiz-modal-icon-primary {
        background: rgba(102, 88, 221, 0.12);
        font-size: 22px;
    }

    .quiz-textarea-wrap {
        position: relative;
    }

    .quiz-textarea-wrap textarea {
        min-height: 150px;
        resize: vertical;
        padding-bottom: 26px;
    }

    .quiz-char-count {
        position: absolute;
        right: 12px;
        bottom: 8px;
        font-size: 12px;
        color: var(--ct-secondary-color);
        pointer-events: none;
    }

    .quiz-modal-info {
        background: rgba(102, 88, 221, 0.08);
        border: 1px solid rgba(102, 88, 221, 0.18);
        color: #6658dd;
        border-radius: 10px;
        padding: 12px 14px;
        font-size: 13.5px;
    }

    .quiz-modal-info i {
        font-size: 18px;
    }

    .quiz-stat-tile {
        display: flex;
        flex-direction: column;
        gap: 6px;
        background: var(--ct-secondary-bg);
        border: 1px solid var(--ct-border-color-translucent);
        border-radius: 14px;
        padding: 10px 14px;
        height: 100%;
    }

    .quiz-stat-tile-label {
        font-size: 11.5px;
        color: var(--ct-secondary-color);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .quiz-stat-tile-value-row {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .quiz-stat-tile-icon {
        font-size: 18px;
        color: rgb(var(--ct-primary-rgb));
        flex-shrink: 0;
    }

    .quiz-stat-tile-value {
        font-size: 16px;
        font-weight: 700;
        color: var(--ct-body-color);
    }
</style>

<!-- Action buttons -->
<div class="d-flex flex-wrap justify-content-end gap-2 mb-3">
    <button type="button" class="btn btn-primary btn-sm rounded-pill waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#signup-modal">
        <i class="mdi mdi-plus"></i> <?php echo lang('UI_Text.CB_Add_New_Question'); ?>
    </button>

    <form action="<?php echo base_url('Assessment/trainings/importQuestionsOptions_view') ?>" method="POST"><?= csrf_field() ?>
        <input type="hidden" name="type" value="<?php echo $pagetype[0]['type'] ?>">
        <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
        <input type="hidden" name="page_id" value="<?php echo $pagerow['page_id'] ?>">
        <input type="hidden" name="course_name" value="<?php echo $pagerow['course_name']; ?>">
        <button type="submit" class="btn btn-outline-secondary btn-sm rounded-pill waves-effect waves-light">
            <i class="mdi mdi-file-import-outline"></i> <?php echo lang('UI_Text.CB_Import_Questions'); ?>
        </button>
    </form>

    <form action="<?php echo base_url('Assessment/trainings/export_questions_excel') ?>" method="POST" data-download="1"><?= csrf_field() ?>
        <input type="hidden" name="type" value="<?php echo $pagetype[0]['type'] ?>">
        <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
        <input type="hidden" name="page_id" value="<?php echo $pagerow['page_id'] ?>">
        <input type="hidden" name="course_name" value="<?php echo $pagerow['course_name']; ?>">
        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill waves-effect waves-light">
            <i class="mdi mdi-file-export-outline"></i> <?php echo lang('UI_Text.CB_Export_Questions'); ?>
        </button>
    </form>

    <form action="<?php echo base_url('Assessment/trainings/assessment_settings') ?>" method="POST"><?= csrf_field() ?>
        <input type="hidden" name="type" value="<?php echo $pagetype[0]['type'] ?>">
        <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
        <input type="hidden" name="page_id" value="<?php echo $pagerow['page_id'] ?>">
        <input type="hidden" name="course_name" value="<?php echo $pagerow['course_name']; ?>">
        <input type="hidden" name="page_name" value="<?php echo $pagerow['page_name']; ?>">
        <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill waves-effect waves-light">
            <i class="mdi mdi-cog-outline"></i> <?php echo lang('UI_Text.CB_Quiz_Settings'); ?>
        </button>
    </form>

    <form action="<?php echo base_url('Assessment/trainings/review_quiz') ?>" method="POST"><?= csrf_field() ?>
        <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
        <input type="hidden" name="page_id" value="<?php echo $pagerow['page_id'] ?>">
        <input type="hidden" name="type" value="<?php echo $pagerow['type'] ?>">
        <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill waves-effect waves-light">
            <i class="mdi mdi-eye-outline"></i> <?php echo lang('UI_Text.CB_Preview_Quiz'); ?>
        </button>
    </form>
</div>

<!-- Quiz settings, at-a-glance - edited on the full "Quiz Settings" page linked above -->
<div class="row g-3 mb-3">
    <div class="col-6 col-md-4 col-lg-2">
        <div class="quiz-stat-tile">
            <div class="quiz-stat-tile-label"><?php echo lang('UI_Text.CB_Pass_Percentage'); ?></div>
            <div class="quiz-stat-tile-value-row">
                <i class="mdi mdi-target quiz-stat-tile-icon"></i>
                <span class="quiz-stat-tile-value"><?php echo esc($quizPassPercentage); ?>%</span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="quiz-stat-tile">
            <div class="quiz-stat-tile-label"><?php echo lang('UI_Text.CB_Number_Of_Items'); ?></div>
            <div class="quiz-stat-tile-value-row">
                <i class="mdi mdi-clipboard-list-outline quiz-stat-tile-icon"></i>
                <span class="quiz-stat-tile-value"><?php echo $quizQuestionCount; ?></span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="quiz-stat-tile">
            <div class="quiz-stat-tile-label"><?php echo lang('UI_Text.CB_Duration'); ?></div>
            <div class="quiz-stat-tile-value-row">
                <i class="mdi mdi-clock-outline quiz-stat-tile-icon"></i>
                <span class="quiz-stat-tile-value"><?php echo esc($quizDuration); ?> <?php echo lang('UI_Text.CB_Min'); ?></span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="quiz-stat-tile">
            <div class="quiz-stat-tile-label"><?php echo lang('UI_Text.CB_Maximum_Questions'); ?></div>
            <div class="quiz-stat-tile-value-row">
                <i class="mdi mdi-format-list-bulleted quiz-stat-tile-icon"></i>
                <span class="quiz-stat-tile-value"><?php echo esc($quizMaxQuestions); ?></span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="quiz-stat-tile">
            <div class="quiz-stat-tile-label"><?php echo lang('UI_Text.CB_Randomize_Questions'); ?></div>
            <div class="quiz-stat-tile-value-row">
                <i class="mdi mdi-shuffle-variant quiz-stat-tile-icon"></i>
                <span class="quiz-stat-tile-value"><?php echo $quizRandomizeQuestions ? lang('UI_Text.on') : lang('UI_Text.CB_Off'); ?></span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="quiz-stat-tile">
            <div class="quiz-stat-tile-label"><?php echo lang('UI_Text.CB_Randomize_Options'); ?></div>
            <div class="quiz-stat-tile-value-row">
                <i class="mdi mdi-format-list-checks quiz-stat-tile-icon"></i>
                <span class="quiz-stat-tile-value"><?php echo $quizRandomizeOptions ? lang('UI_Text.on') : lang('UI_Text.CB_Off'); ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Question Bank -->
<div class="card quiz-card">
    <div class="card-body">
        <div class="d-flex align-items-center gap-2 mb-3">
            <span class="quiz-stat-icon"><i class="mdi mdi-help-circle-outline"></i></span>
            <div>
                <h5 class="quiz-card-title mb-0"><?php echo lang('UI_Text.CB_Question_Bank'); ?></h5>
                <p class="quiz-card-subtitle mb-0"><?php echo lang('UI_Text.CB_Question_Bank_Sub'); ?></p>
            </div>
        </div>

        <div id="signup-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content quiz-add-question-modal">
                    <div class="modal-header border-0 pb-0 align-items-start">
                        <div class="d-flex align-items-start gap-3">
                            <span class="quiz-modal-icon quiz-modal-icon-primary"><i class="mdi mdi-plus"></i></span>
                            <div>
                                <h5 class="modal-title fw-bold mb-1"><?php echo lang('UI_Text.CB_Add_New_Question'); ?></h5>
                                <p class="text-muted font-13 mb-0"><?php echo lang('UI_Text.CB_Create_Question_Choose_Type_Sub'); ?></p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="<?php echo base_url('Assessment/trainings/addQuestions') ?>" method="POST" id="submitForm"><?= csrf_field() ?>
                            <div class="d-flex align-items-start gap-3 mb-4">
                                <span class="quiz-modal-icon"><i class="mdi mdi-help-circle-outline"></i></span>
                                <div class="flex-grow-1">
                                    <label for="quizNewQuestionText" class="form-label fw-semibold mb-0"><?php echo lang('UI_Text.CB_Question'); ?></label>
                                    <p class="text-muted font-13 mb-2"><?php echo lang('UI_Text.CB_Question_Learner_Sub'); ?></p>
                                    <div class="quiz-textarea-wrap">
                                        <textarea class="form-control" id="quizNewQuestionText" name="question" maxlength="1000" placeholder="<?php echo lang('UI_Text.CB_Question_Placeholder'); ?>" required
                                            oninput="updateCharCount(this, 1000, 'quizNewQuestionCount')"></textarea>
                                        <span class="quiz-char-count" id="quizNewQuestionCount">0 / 1000</span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-start gap-3 mb-4">
                                <span class="quiz-modal-icon"><i class="mdi mdi-format-list-bulleted"></i></span>
                                <div class="flex-grow-1">
                                    <label for="quizNewQuestionType" class="form-label fw-semibold mb-0"><?php echo lang('UI_Text.CB_Type_Of_Question'); ?></label>
                                    <p class="text-muted font-13 mb-2"><?php echo lang('UI_Text.CB_Select_Question_Type_Sub'); ?></p>
                                    <select class="form-select" id="quizNewQuestionType" name="quiz_type" required>
                                        <option value="" selected disabled><?php echo lang('UI_Text.CB_Select_Question_Type'); ?></option>
                                        <option value="112"><?php echo lang('UI_Text.CB_Type_SCQ'); ?></option>
                                        <option value="115"><?php echo lang('UI_Text.CB_Type_MCQ'); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="quiz-modal-info d-flex align-items-center gap-2">
                                <i class="mdi mdi-information-outline"></i>
                                <span><?php echo lang('UI_Text.CB_Configure_Options_After_Create'); ?></span>
                            </div>

                            <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
                            <input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
                            <input type="hidden" name="type" value="<?php echo $pagetype[0]['type'] ?>">
                            <input type="hidden" name="typeval" value="8">
                            <input type="hidden" name="returnUrl" value="1">

                            <div class="modal-footer border-top mt-4 px-0 pb-0">
                                <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal"><?php echo lang('Buttons.Cancel'); ?></button>
                                <button class="btn btn-primary rounded-pill" type="submit"><i class="mdi mdi-plus-box-outline"></i> <?php echo lang('UI_Text.CB_Add_New_Question'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th><?php echo lang('UI_Text.CB_Question'); ?></th>
                        <th width="12%"><?php echo lang('UI_Text.CB_Type'); ?></th>
                        <th width="10%"><?php echo lang('UI_Text.CB_Options'); ?></th>
                        <th width="15%"><?php echo lang('UI_Text.Action'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $j = 0;
                    if (isset(($getQuestiondata)) && !empty($getQuestiondata)) {
                        foreach ($getQuestiondata as $eachQuestion) {
                            $j = $j + 1;
                            $typeInfo = $quizTypeLabels[$eachQuestion['quiz_type']] ?? null;
                    ?>
                            <tr>
                                <td width="5%"><?php echo $j; ?></td>

                                <td class="question-cell"><?php echo $eachQuestion['question']; ?></td>

                                <td>
                                    <?php if ($typeInfo) { ?>
                                        <span class="quiz-type-badge <?php echo $typeInfo['class']; ?>">
                                            <i class="mdi <?php echo $typeInfo['icon']; ?>"></i> <?php echo $typeInfo['label']; ?>
                                        </span>
                                    <?php } ?>
                                </td>

                                <td><?php echo (int) ($eachQuestion['option_count'] ?? 0); ?></td>

                                <td>
                                    <div class="d-flex gap-1">
                                        <form class="form-horizontal"
                                            action="<?php echo base_url('Assessment/trainings/add_quiz_option_view') ?>"
                                            method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="type" value="<?php echo $pagetype[0]['type']; ?>">
                                            <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
                                            <input type="hidden" name="page_id" value="<?php echo $eachQuestion['page_id']; ?>">
                                            <input type="hidden" name="question_id" value="<?php echo $eachQuestion['q_id']; ?>">
                                            <button class="btn btn-outline-warning waves-effect waves-light rounded-circle quiz-action-btn" title="<?php echo lang('Buttons.Edit'); ?>" aria-label="<?php echo lang('Buttons.Edit'); ?>"><span class="mdi mdi-square-edit-outline" aria-hidden="true"></span></button>
                                        </form>
                                        <?php if ($pagetype[0]['type'] == '4') { ?>
                                            <form class="form-horizontal"
                                                action="<?php echo base_url($copyQuestion_link) ?>"
                                                method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
                                                <input type="hidden" name="question_id" value="<?php echo $eachQuestion['q_id']; ?>">
                                                <button type="submit" class="btn btn-outline-primary waves-effect waves-light rounded-circle quiz-action-btn" title="<?php echo lang('Buttons.Copy'); ?>" aria-label="<?php echo lang('Buttons.Copy'); ?>"><span class="mdi mdi-content-copy" aria-hidden="true"></span></button>
                                            </form>
                                        <?php } ?>
                                        <form class="form-horizontal"
                                            action="<?php echo base_url($quizdelete_link) ?>"
                                            method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="question_id" value="<?php echo $eachQuestion['q_id']; ?>">
                                            <button type="submit"
                                                onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')"
                                                class="btn btn-outline-danger waves-effect waves-light rounded-circle quiz-action-btn" title="<?php echo lang('Buttons.Delete'); ?>" aria-label="<?php echo lang('Buttons.Delete'); ?>"><span class="mdi mdi-trash-can-outline" aria-hidden="true"></span></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // JS string .length counts UTF-16 code units, not characters - any pasted character
    // outside the Basic Multilingual Plane (accented/composed letters some word processors
    // paste as surrogate pairs, some non-English punctuation, emoji, etc.) counts as 2,
    // inflating the shown count past the intended limit - e.g. "1007 / 1000" for text a
    // person would count as exactly 1000 characters. Array.from() iterates by Unicode code
    // point instead, and lets the limit actually be enforced by trimming.
    function updateCharCount(el, limit, counterId) {
        var chars = Array.from(el.value);
        if (chars.length > limit) {
            var pos = el.selectionEnd;
            el.value = chars.slice(0, limit).join('');
            if (pos !== null) {
                var newPos = Math.min(pos, el.value.length);
                el.setSelectionRange(newPos, newPos);
            }
            chars = Array.from(el.value);
        }
        document.getElementById(counterId).textContent = chars.length + ' / ' + limit;
    }
</script>
