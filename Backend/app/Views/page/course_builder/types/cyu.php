<?php if ((in_array('46', $arrayuserlevel) || in_array('5', $arrayuserlevel))) { ?>

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

        .cyu-feedback-box {
            border: 1px solid var(--ct-border-color);
            border-radius: 12px;
            padding: 14px;
        }

        .cyu-feedback-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .cyu-feedback-icon-correct {
            background: rgba(10, 207, 151, 0.12);
            color: #0acf97;
        }

        .cyu-feedback-icon-wrong {
            background: rgba(250, 92, 124, 0.12);
            color: #fa5c7c;
        }

        .cyu-richbox-wrap {
            border: 1px solid var(--ct-border-color);
            border-radius: 8px;
            overflow: hidden;
        }

        .cyu-richbox-toolbar {
            display: flex;
            gap: 2px;
            padding: 4px 6px;
            background: var(--ct-tertiary-bg);
            border-bottom: 1px solid var(--ct-border-color);
        }

        .cyu-richbox-toolbar button {
            width: 26px;
            height: 26px;
            border: none;
            background: transparent;
            border-radius: 5px;
            color: var(--ct-secondary-color);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
        }

        .cyu-richbox-toolbar button:hover {
            background: rgba(102, 88, 221, 0.12);
            color: #6658dd;
        }

        .cyu-richbox {
            min-height: 70px;
            padding: 10px 12px;
            font-size: 13.5px;
            outline: none;
        }

        .cyu-richbox-footer {
            display: flex;
            justify-content: flex-end;
            padding: 2px 10px 6px;
        }
    </style>

    <!-- Per-language default template overrides - rarely touched, tucked into a modal so it
         doesn't compete with the main editing flow below. Opened from the button in the
         Question card header (see below). -->
    <div class="modal fade" id="cyuTemplateDefaultsModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content cyu-card">
                <div class="modal-header border-0 pb-0">
                    <div class="d-flex align-items-center gap-2">
                        <span class="cyu-icon cyu-icon-primary"><i class="mdi mdi-cog-outline"></i></span>
                        <div>
                            <h5 class="modal-title fw-bold mb-1"><?php echo lang('UI_Text.CB_Template_Defaults'); ?></h5>
                            <p class="cyu-card-subtitle mb-0"><?php echo lang('UI_Text.CB_Template_Defaults_Sub'); ?></p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="cyu-card-subtitle"><?php echo lang('UI_Text.CB_Template_Defaults_Note'); ?></p>
                    <?php
                    foreach ($assessment_scqmcq_sets as $x => $sets) {
                        if (!empty($AssessmentSettings[$x])) {

                            $item = $AssessmentSettings[$x][0]['value'];
                            $s_id = $AssessmentSettings[$x][0]['s_id'];
                    ?>
                            <form action="<?php echo base_url('Assessment/trainings/setting_data_update') ?>" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="quiz_settings_type" value="<?php echo $x ?>">
                                <input type="hidden" name="add_or_update" value="2">
                                <input type="hidden" name="s_id" value="<?php echo $s_id; ?>">
                                <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
                                <input type="hidden" name="page_id" value="<?php echo $pagerow['page_id']; ?>">
                                <input type="hidden" name="tab" value="2">
                                <input type="hidden" name="returnUrl" value="2">
                                <div class="row">
                                    <?php if ($x == '59') { ?>
                                        <label><b><?php echo lang('UI_Text.CB_Default_SCQ'); ?></b> <?php echo $assessment_scqmcq_sets[$x] ?></label><br>
                                    <?php } elseif ($x == '60') { ?>
                                        <label><b><?php echo lang('UI_Text.CB_Default_MCQ'); ?></b> <?php echo $assessment_scqmcq_sets[$x] ?></label><br>
                                    <?php } else { ?>
                                        <label><b><?php echo lang('UI_Text.CB_Default_Colon'); ?></b> <?php echo $assessment_scqmcq_sets[$x] ?></label><br>
                                    <?php } ?>
                                    <div class="col-lg-10">
                                        <input class="form-control" name="valid" type="hidden" />
                                        <input class="form-control" name="value" type="input"
                                            value="<?php echo isset($item) ? $item : $assessment_scqmcq_sets[$x]; ?>" />
                                    </div>
                                    <div class="col-lg-2">
                                        <button type="submit" class="btn btn-outline-warning btn-xs rounded-pill waves-effect waves-light mt-2"><?php echo lang('Buttons.Update'); ?></button>
                                    </div>
                                </div><br />
                            </form>
                        <?php
                        } else {
                        ?>
                            <form action="<?php echo base_url('Assessment/trainings/setting_data_update') ?>" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="quiz_settings_type" value=" <?php echo $x ?>">
                                <input type="hidden" name="add_or_update" value="1">
                                <input type="hidden" name="s_id" value="0">
                                <input type="hidden" name="quiz_settings_id" value="<?php echo isset($getAssessmentSettings[0]['s_id']) ? $getAssessmentSettings[0]['s_id'] : ''; ?>">
                                <input type="hidden" name="scourse_id" value="<?php echo isset($scourse_id) ? $scourse_id : ''; ?>">
                                <input type="hidden" name="page_id" value="<?php echo $pagerow['page_id']; ?>">
                                <input type="hidden" name="tab" value="2">
                                <input type="hidden" name="returnUrl" value="2">
                                <div class="row">
                                    <div class="col-lg-10">
                                        <?php if ($x == '59') { ?>
                                            <label><b><?php echo lang('UI_Text.CB_Default_SCQ'); ?></b> <?php echo $assessment_scqmcq_sets[$x] ?></label><br>
                                        <?php } elseif ($x == '60') { ?>
                                            <label><b><?php echo lang('UI_Text.CB_Default_MCQ'); ?></b> <?php echo $assessment_scqmcq_sets[$x] ?></label><br>
                                        <?php } else { ?>
                                            <label><b><?php echo lang('UI_Text.CB_Default_Colon'); ?></b> <?php echo $assessment_scqmcq_sets[$x] ?></label><br>
                                        <?php } ?>
                                        <input class="form-control" name="valid" type="hidden" />
                                        <input name="value" class="form-control" value="" required />
                                    </div>
                                    <div class="col-lg-2">
                                        <button type="submit" class="btn btn-outline-primary btn-xs rounded-pill waves-effect waves-light mt-2"><?php echo lang('Buttons.Add'); ?></button>
                                    </div>
                                </div><br />
                            </form>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Question -->
    <div class="card cyu-card mb-3">
        <div class="card-body">
            <form class="form-horizontal" id="cyuQuestionForm" action="<?php echo base_url($editquestion) ?>" method="POST"><?= csrf_field() ?>
                <div class="d-flex align-items-start justify-content-between gap-3 mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="cyu-icon cyu-icon-primary"><i class="mdi mdi-help-circle-outline"></i></span>
                        <label class="fw-bold mb-0"><?php echo lang('UI_Text.CB_Question'); ?></label>
                    </div>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill waves-effect waves-light text-nowrap" data-bs-toggle="modal" data-bs-target="#cyuTemplateDefaultsModal">
                        <i class="mdi mdi-cog-outline"></i> <?php echo lang('UI_Text.CB_Template_Defaults'); ?>
                    </button>
                </div>

                <textarea class="form-control cyu-question-textarea" id="cyuQuestionText" name="question" maxlength="500"
                    placeholder="<?php echo lang('UI_Text.CB_Question_Placeholder'); ?>" required
                    oninput="document.getElementById('cyuQuestionCount').textContent = this.value.length + ' / 500';"
                    onblur="cyuSaveQuestion(this)"><?php echo isset($qrow['question']) ? htmlspecialchars($qrow['question']) : '' ?></textarea>
                <span class="cyu-saving-indicator" id="cyuQuestionSaving" style="display:none;"><i class="mdi mdi-loading mdi-spin"></i> <?php echo lang('UI_Text.CB_Saving_Ellipsis'); ?></span>

                <div class="d-flex justify-content-end mt-2">
                    <span class="cyu-char-count" id="cyuQuestionCount"><?php echo isset($qrow['question']) ? mb_strlen($qrow['question']) : 0; ?> / 500</span>
                </div>

                <?php if (isset($coursevalidation)): ?>
                    <div class="alert alert-danger mt-2"><?= $coursevalidation->listErrors() ?></div>
                <?php endif; ?>

                <input type="hidden" name="q_id" value="<?php echo isset($qrow['q_id']) ? $qrow['q_id'] : ''; ?>">
                <input type="hidden" name="page_id" value="<?php echo isset($qrow['page_id']) ? $qrow['page_id'] : ''; ?>">
                <input type="hidden" name="page_number" value="<?php echo isset($qrow['page_number']) ? $qrow['page_number'] : ''; ?>">
                <input type="hidden" name="typeval" value="<?php echo $typeval; ?>">
            </form>
        </div>
    </div>

    <div class="card cyu-card mb-3">
        <div class="card-body">
            <ul class="nav nav-pills nav-fill navtab-bg mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <a href="#cyuOptionsTab" data-bs-toggle="tab" aria-expanded="true" role="tab" class="nav-link active"><?php echo lang('UI_Text.CB_Options'); ?></a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#cyuFeedbackTab" data-bs-toggle="tab" aria-expanded="false" role="tab" class="nav-link"><?php echo lang('UI_Text.CB_Feedback'); ?></a>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Options -->
                <div class="tab-pane fade show active" id="cyuOptionsTab" role="tabpanel">
                    <p class="cyu-card-subtitle mb-3"><?php echo $pagerow['type'] == '5' ? lang('UI_Text.CB_Add_Options_Mark_Answer') : lang('UI_Text.CB_Add_Options_Mark_Answers'); ?></p>

                    <div class="table-responsive">
                        <table class="table cyu-options-table align-middle">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th><?php echo lang('UI_Text.CB_Option_Text'); ?></th>
                                    <th width="15%" class="text-center"><?php echo lang('UI_Text.CB_Is_Correct'); ?></th>
                                    <th width="12%" class="text-center"><?php echo lang('UI_Text.Action'); ?></th>
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
                                                <span class="cyu-saving-indicator" style="display:none;"><i class="mdi mdi-loading mdi-spin"></i> <?php echo lang('UI_Text.CB_Saving_Ellipsis'); ?></span>
                                            </td>

                                            <td class="text-center">
                                                <?php
                                                $truefalse = $eachoptiondata['truefalse'];
                                                $optionId = $eachoptiondata['o_id'];
                                                $isCorrect = $truefalse == 1;
                                                ?>
                                                <div class="d-flex flex-column align-items-center gap-1">
                                                    <?php if ($pagerow['type'] == '5') {
                                                        $type = $pagerow['type'];
                                                        $questionId = $eachoptiondata['question_id'];
                                                    ?>
                                                        <label class="cyu-toggle" title="<?php echo $isCorrect ? lang('UI_Text.CB_Correct') : lang('UI_Text.CB_Wrong'); ?>">
                                                            <input type="checkbox" <?php echo $isCorrect ? 'checked' : ''; ?>
                                                                onchange="toggleTrueFalse(this, '<?php echo $optionId; ?>')"
                                                                data-question-id="<?php echo $questionId; ?>"
                                                                data-type="<?php echo $type; ?>"
                                                                data-current="<?php echo $truefalse; ?>">
                                                            <span class="cyu-toggle-slider"></span>
                                                        </label>
                                                    <?php } else { ?>
                                                        <label class="cyu-toggle" title="<?php echo $isCorrect ? lang('UI_Text.CB_Correct') : lang('UI_Text.CB_Wrong'); ?>">
                                                            <input type="checkbox" <?php echo $isCorrect ? 'checked' : ''; ?>
                                                                onchange="updateDate(this.checked ? '1' : '2','truefalse','<?php echo $optionId; ?>')">
                                                            <span class="cyu-toggle-slider"></span>
                                                        </label>
                                                    <?php } ?>
                                                    <span class="cyu-toggle-label <?php echo $isCorrect ? 'text-success' : 'text-danger'; ?>"><?php echo $isCorrect ? lang('UI_Text.CB_Correct') : lang('UI_Text.CB_Wrong'); ?></span>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="d-flex justify-content-center gap-1">
                                                    <?php if ($optionCount <= 1) { ?>
                                                        <button type="button" class="btn btn-outline-danger waves-effect waves-light rounded-circle cyu-action-btn" title="<?php echo lang('UI_Text.CB_Min_One_Option_Note'); ?>" aria-label="<?php echo lang('UI_Text.CB_Min_One_Option_Note'); ?>" disabled><i class="mdi mdi-trash-can-outline" aria-hidden="true"></i></button>
                                                    <?php } else { ?>
                                                        <button type="button" class="btn btn-outline-danger waves-effect waves-light rounded-circle cyu-action-btn" title="<?php echo lang('Buttons.Delete'); ?>" aria-label="<?php echo lang('Buttons.Delete'); ?>"
                                                            onclick="updateDate('0','status','<?php echo $eachoptiondata['o_id'] ?>')"><i class="mdi mdi-trash-can-outline" aria-hidden="true"></i></button>
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

                    <button type="button" id="addRowBtn" class="btn cyu-add-option-btn w-100" onclick="cyuAddOption()">
                        <i class="mdi mdi-plus-circle-outline"></i> <?php echo lang('UI_Text.CB_Add_New_Option'); ?>
                    </button>
                </div>
                <!-- /Options tab -->

                <!-- Feedback & Explanations -->
                <div class="tab-pane fade" id="cyuFeedbackTab" role="tabpanel">
                    <form action="<?php echo base_url('Assessment/trainings/edit_attempts_question') ?>" method="POST" id="cyuFeedbackForm"><?= csrf_field() ?>
                        <p class="cyu-card-subtitle mb-3">
                            <?php echo lang('UI_Text.CB_Provide_Feedback_Sub'); ?>
                            <i class="mdi mdi-information-outline" title="<?php echo lang('UI_Text.CB_Feedback_Tooltip'); ?>"></i>
                        </p>

                        <?php if ($pagerow['type'] == 5 || $pagerow['type'] == 6) { ?>
                            <div class="cyu-feedback-box mb-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="cyu-feedback-icon cyu-feedback-icon-correct"><i class="mdi mdi-check-circle-outline"></i></span>
                                    <div>
                                        <div class="fw-semibold"><?php echo lang('UI_Text.CB_Correct_Answer_Feedback'); ?></div>
                                        <p class="cyu-card-subtitle mb-0"><?php echo lang('UI_Text.CB_Correct_Answer_Feedback_Sub'); ?></p>
                                    </div>
                                </div>
                                <div class="cyu-richbox-wrap">
                                    <div class="cyu-richbox-toolbar">
                                        <button type="button" onclick="cyuExec(this,'bold')" title="<?php echo lang('UI_Text.CB_Bold'); ?>"><i class="mdi mdi-format-bold"></i></button>
                                        <button type="button" onclick="cyuExec(this,'italic')" title="<?php echo lang('UI_Text.CB_Italic'); ?>"><i class="mdi mdi-format-italic"></i></button>
                                        <button type="button" onclick="cyuExec(this,'underline')" title="<?php echo lang('UI_Text.CB_Underline'); ?>"><i class="mdi mdi-format-underline"></i></button>
                                        <button type="button" onclick="cyuExec(this,'insertUnorderedList')" title="<?php echo lang('UI_Text.CB_Bulleted_List'); ?>"><i class="mdi mdi-format-list-bulleted"></i></button>
                                        <button type="button" onclick="cyuExec(this,'insertOrderedList')" title="<?php echo lang('UI_Text.CB_Numbered_List'); ?>"><i class="mdi mdi-format-list-numbered"></i></button>
                                        <button type="button" onclick="cyuLink(this)" title="<?php echo lang('UI_Text.CB_Insert_Link'); ?>"><i class="mdi mdi-link-variant"></i></button>
                                        <button type="button" onclick="cyuExec(this,'removeFormat')" title="<?php echo lang('UI_Text.CB_Clear_Formatting'); ?>"><i class="mdi mdi-format-clear"></i></button>
                                    </div>
                                    <div class="cyu-richbox" id="cyuCorrectBox" contenteditable="true"
                                        oninput="cyuOnRichInput(this, 'cyuCorrectHidden', 'cyuCorrectCount', 300)"><?php echo isset($qrow['correct']) ? $qrow['correct'] : ''; ?></div>
                                    <div class="cyu-richbox-footer"><span class="cyu-char-count" id="cyuCorrectCount"><?php echo isset($qrow['correct']) ? mb_strlen(strip_tags($qrow['correct'])) : 0; ?> / 300</span></div>
                                </div>
                                <input type="hidden" name="correct" id="cyuCorrectHidden" value="<?php echo isset($qrow['correct']) ? htmlspecialchars($qrow['correct'], ENT_QUOTES) : '' ?>">
                            </div>

                            <div class="cyu-feedback-box mb-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="cyu-feedback-icon cyu-feedback-icon-wrong"><i class="mdi mdi-close-circle-outline"></i></span>
                                    <div>
                                        <div class="fw-semibold"><?php echo lang('UI_Text.CB_Wrong_Answer_Attempt_1'); ?></div>
                                        <p class="cyu-card-subtitle mb-0"><?php echo lang('UI_Text.CB_Wrong_Answer_Attempt_1_Sub'); ?></p>
                                    </div>
                                </div>
                                <div class="cyu-richbox-wrap">
                                    <div class="cyu-richbox-toolbar">
                                        <button type="button" onclick="cyuExec(this,'bold')" title="<?php echo lang('UI_Text.CB_Bold'); ?>"><i class="mdi mdi-format-bold"></i></button>
                                        <button type="button" onclick="cyuExec(this,'italic')" title="<?php echo lang('UI_Text.CB_Italic'); ?>"><i class="mdi mdi-format-italic"></i></button>
                                        <button type="button" onclick="cyuExec(this,'underline')" title="<?php echo lang('UI_Text.CB_Underline'); ?>"><i class="mdi mdi-format-underline"></i></button>
                                        <button type="button" onclick="cyuExec(this,'insertUnorderedList')" title="<?php echo lang('UI_Text.CB_Bulleted_List'); ?>"><i class="mdi mdi-format-list-bulleted"></i></button>
                                        <button type="button" onclick="cyuExec(this,'insertOrderedList')" title="<?php echo lang('UI_Text.CB_Numbered_List'); ?>"><i class="mdi mdi-format-list-numbered"></i></button>
                                        <button type="button" onclick="cyuLink(this)" title="<?php echo lang('UI_Text.CB_Insert_Link'); ?>"><i class="mdi mdi-link-variant"></i></button>
                                        <button type="button" onclick="cyuExec(this,'removeFormat')" title="<?php echo lang('UI_Text.CB_Clear_Formatting'); ?>"><i class="mdi mdi-format-clear"></i></button>
                                    </div>
                                    <div class="cyu-richbox" id="cyuIncorrect2Box" contenteditable="true"
                                        oninput="cyuOnRichInput(this, 'cyuIncorrect2Hidden', 'cyuIncorrect2Count', 300)"><?php echo isset($qrow['incorrect2']) ? $qrow['incorrect2'] : ''; ?></div>
                                    <div class="cyu-richbox-footer"><span class="cyu-char-count" id="cyuIncorrect2Count"><?php echo isset($qrow['incorrect2']) ? mb_strlen(strip_tags($qrow['incorrect2'])) : 0; ?> / 300</span></div>
                                </div>
                                <input type="hidden" name="incorrect2" id="cyuIncorrect2Hidden" value="<?php echo isset($qrow['incorrect2']) ? htmlspecialchars($qrow['incorrect2'], ENT_QUOTES) : '' ?>">
                            </div>

                            <div class="cyu-feedback-box mb-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="cyu-feedback-icon cyu-feedback-icon-wrong"><i class="mdi mdi-close-circle-outline"></i></span>
                                    <div>
                                        <div class="fw-semibold"><?php echo lang('UI_Text.CB_Wrong_Answer_Attempt_2'); ?></div>
                                        <p class="cyu-card-subtitle mb-0"><?php echo lang('UI_Text.CB_Wrong_Answer_Attempt_2_Sub'); ?></p>
                                    </div>
                                </div>
                                <div class="cyu-richbox-wrap">
                                    <div class="cyu-richbox-toolbar">
                                        <button type="button" onclick="cyuExec(this,'bold')" title="<?php echo lang('UI_Text.CB_Bold'); ?>"><i class="mdi mdi-format-bold"></i></button>
                                        <button type="button" onclick="cyuExec(this,'italic')" title="<?php echo lang('UI_Text.CB_Italic'); ?>"><i class="mdi mdi-format-italic"></i></button>
                                        <button type="button" onclick="cyuExec(this,'underline')" title="<?php echo lang('UI_Text.CB_Underline'); ?>"><i class="mdi mdi-format-underline"></i></button>
                                        <button type="button" onclick="cyuExec(this,'insertUnorderedList')" title="<?php echo lang('UI_Text.CB_Bulleted_List'); ?>"><i class="mdi mdi-format-list-bulleted"></i></button>
                                        <button type="button" onclick="cyuExec(this,'insertOrderedList')" title="<?php echo lang('UI_Text.CB_Numbered_List'); ?>"><i class="mdi mdi-format-list-numbered"></i></button>
                                        <button type="button" onclick="cyuLink(this)" title="<?php echo lang('UI_Text.CB_Insert_Link'); ?>"><i class="mdi mdi-link-variant"></i></button>
                                        <button type="button" onclick="cyuExec(this,'removeFormat')" title="<?php echo lang('UI_Text.CB_Clear_Formatting'); ?>"><i class="mdi mdi-format-clear"></i></button>
                                    </div>
                                    <div class="cyu-richbox" id="cyuIncorrectBox" contenteditable="true"
                                        oninput="cyuOnRichInput(this, 'cyuIncorrectHidden', 'cyuIncorrectCount', 300)"><?php echo isset($qrow['incorrect']) ? $qrow['incorrect'] : ''; ?></div>
                                    <div class="cyu-richbox-footer"><span class="cyu-char-count" id="cyuIncorrectCount"><?php echo isset($qrow['incorrect']) ? mb_strlen(strip_tags($qrow['incorrect'])) : 0; ?> / 300</span></div>
                                </div>
                                <input type="hidden" name="incorrect" id="cyuIncorrectHidden" value="<?php echo isset($qrow['incorrect']) ? htmlspecialchars($qrow['incorrect'], ENT_QUOTES) : '' ?>">
                            </div>

                            <input type="hidden" name="noAttempts" value="2">
                        <?php } ?>

                        <?php if (isset($coursevalidation)): ?>
                            <div class="alert alert-danger"><?= $coursevalidation->listErrors() ?></div>
                        <?php endif; ?>

                        <input type="hidden" name="q_id" value="<?php echo isset($qrow['q_id']) ? $qrow['q_id'] : ''; ?>">
                        <input type="hidden" name="typeval" value="<?php echo $typeval; ?>">
                        <input type="hidden" name="page_number" value="<?php echo $page_number; ?>">
                        <input type="hidden" name="returnUrl" value="1">
                        <input type="hidden" name="tab" value="1">

                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-primary rounded-pill waves-effect waves-light">
                                <i class="mdi mdi-content-save-outline"></i> <?php echo lang('Buttons.CB_Save'); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<script>
    function toggleTrueFalse(checkbox, optionId) {
        const questionId = checkbox.getAttribute('data-question-id');
        const type = checkbox.getAttribute('data-type');
        const currentStatus = checkbox.getAttribute('data-current');

        const newStatus = currentStatus === '1' ? '2' : '1';

        if (type === '5' && newStatus === '1') {
            const checkboxes = document.querySelectorAll(`input[data-question-id="${questionId}"]`);
            const alreadyCorrect = Array.from(checkboxes).some(cb =>
                cb.getAttribute('data-current') === '1'
            );

            if (alreadyCorrect) {
                alert("<?php echo lang('UI_Text.CB_Only_One_Correct_Answer'); ?>");
                // The checkbox already flipped itself before this handler ran - put it back
                // since the change is being rejected.
                checkbox.checked = false;
                return;
            }
        }

        updateDate(newStatus, 'truefalse', optionId);
    }

    function updateDate(element, column, id) {
        if (column == 'truefalse' || column == 'status') {
            var value = element;
        } else {
            var value = element.innerText;
        }

        // Only the option-text edit (column === 'values') passes the actual field as
        // `element` - that's the case this indicator is for (typing, then clicking away).
        var savingIndicator = null;
        if (column === 'values' && element && element.parentNode) {
            savingIndicator = element.parentNode.querySelector('.cyu-saving-indicator');
            if (savingIndicator) savingIndicator.style.display = 'inline-flex';
        }

        let scourse_id = '<?php echo $scourse_id ?>';
        let question_id = '<?php echo isset($qrow['q_id']) ? $qrow['q_id'] : '' ?>';
        // fetch(), not $.ajax(): jQuery's AJAX transport fails to construct its own XHR in
        // this app's environment ("Cannot read properties of undefined (reading 'open')",
        // status 0 - the request never reaches the network at all), so this silently did
        // nothing on click. fetch() is native and already relied on elsewhere on this page.
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
                // The save already happened server-side by this point regardless of what the
                // response body looks like - don't let a parse hiccup (e.g. a stray PHP notice
                // printed ahead of the JSON) silently swallow the refresh, which was leaving
                // deletes/toggles saved but invisible until a manual page reload.
                try {
                    var obj = JSON.parse(text);
                    if (obj && obj.status && obj.status !== 'OK') {
                        // The server sends back a specific, user-actionable reason for known
                        // rejections (e.g. deleting a question's last remaining option) rather
                        // than just "OK" - show that instead of a generic error.
                        alert(obj.status);
                    }
                } catch (e) {
                    console.log('Could not parse updatedateformat response as JSON:', text);
                }
                // Refresh just the page-content pane instead of the whole page
                // (header/left menu/every asset) for this one option edit.
                courseBuilderReloadMain();
            })
            .catch(function() {
                // Unlike the success path, nothing else is going to refresh/replace this row,
                // so the indicator needs to be hidden explicitly here.
                if (savingIndicator) savingIndicator.style.display = 'none';
                console.log('request failed');
            });
    }

    // Creates a real (empty) option row server-side, then refreshes this pane so it comes
    // back fully rendered - simpler and less fragile than hand-building a temporary row that
    // has to mimic the table above and gets thrown away on the next edit anyway.
    function cyuAddOption() {
        let scourse_id = '<?php echo $scourse_id ?>';
        let question_id = '<?php echo isset($qrow['q_id']) ? $qrow['q_id'] : '' ?>';
        // page_type lets the server default this option to "Correct" when it's the first
        // option on a new SCQ question - see addoptioneditableformat().
        let page_type = '<?php echo $pagerow['type'] ?>';
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
                courseBuilderReloadMain();
            })
            .catch(function() {
                console.log('request failed');
            });
    }

    // Auto-saves the Question text on blur (clicking/tabbing away) instead of requiring a
    // separate "Update Question" button - same endpoint/fields the old form submit used.
    function cyuSaveQuestion(textarea) {
        var form = document.getElementById('cyuQuestionForm');
        var savingIndicator = document.getElementById('cyuQuestionSaving');
        if (savingIndicator) savingIndicator.style.display = 'inline-flex';

        fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                credentials: 'same-origin'
            })
            .then(function(response) {
                if (!response.ok) throw new Error('Request failed with status ' + response.status);
                // Re-syncs anything elsewhere on this pane that reflects the question text
                // (e.g. the page heading) rather than just trusting the textarea's own value.
                courseBuilderReloadMain();
            })
            .catch(function() {
                if (savingIndicator) savingIndicator.style.display = 'none';
                alert('<?php echo lang('UI_Text.CB_Unable_Save_Question'); ?>');
            });
    }

    // Minimal rich-text toolbar for the feedback boxes - execCommand is deprecated but still
    // broadly supported, and pulling in a full editor library for a 7-button toolbar isn't
    // worth it here.
    function cyuExec(btn, command) {
        var box = btn.closest('.cyu-richbox-wrap').querySelector('.cyu-richbox');
        box.focus();
        document.execCommand(command, false, null);
        box.dispatchEvent(new Event('input'));
    }

    function cyuLink(btn) {
        var box = btn.closest('.cyu-richbox-wrap').querySelector('.cyu-richbox');
        var url = prompt('<?php echo lang('UI_Text.CB_Enter_A_Url'); ?>');
        if (!url) return;
        box.focus();
        document.execCommand('createLink', false, url);
        box.dispatchEvent(new Event('input'));
    }

    function cyuOnRichInput(box, hiddenId, countId, maxLen) {
        document.getElementById(hiddenId).value = box.innerHTML;
        var length = (box.textContent || '').length;
        var countEl = document.getElementById(countId);
        if (countEl) {
            countEl.textContent = length + ' / ' + maxLen;
            countEl.classList.toggle('text-danger', length > maxLen);
        }
    }
</script>
