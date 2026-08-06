                                        <?php if ((in_array('46', $arrayuserlevel) || in_array('5', $arrayuserlevel))) {
                                        ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="x_panel">
                                                                <form class="form-horizontal"
                                                                    action="<?php echo base_url($editquestion) ?>" method="POST"><?= csrf_field() ?>
                                                                    <div class="col-md-12">
                                                                        <div class="row">
                                                                            <div class="col-md-10">
                                                                                <label>Question</label>
                                                                                <input type="text" class="form-control col-md-12"
                                                                                    name="question" placeholder="Question"
                                                                                    value="<?php echo isset($qrow['question']) ? htmlspecialchars($qrow['question']) : '' ?>" />
                                                                            </div>
                                                                            <div class="col-md-2">
                                                                                <?php if (isset($coursevalidation)): ?>
                                                                                    <div class=col-12 col-sm-4>
                                                                                        <div class="alert alert-danger" role="alert">
                                                                                            <?= $coursevalidation->listErrors() ?>
                                                                                        </div>
                                                                                    </div>
                                                                                <?php endif; ?><br />
                                                                                <input type="hidden" name="q_id"
                                                                                    value="<?php echo isset($qrow['q_id']) ? $qrow['q_id'] : ''; ?>">
                                                                                <input type="hidden" name="page_id"
                                                                                    value="<?php echo isset($qrow['page_id']) ? $qrow['page_id'] : ''; ?>">
                                                                                <input type="hidden" name="page_number"
                                                                                    value="<?php echo isset($qrow['page_number']) ? $qrow['page_number'] : ''; ?>">
                                                                                <input type="hidden" name="typeval"
                                                                                    value="<?php echo $typeval; ?>">
                                                                                
                                                                                <button type="submit"
                                                                                    class="btn btn-outline-warning waves-effect btn-sm waves-light rounded-pill">
                                                                                    Update
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php $userlevel = session()->get('userlevel');
                                            $array = array_map('intval', str_split($userlevel)); ?>

<div class="row">

                                                <div class="col-md-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <button type="submit" id="addRowBtn"
                                                                class="btn btn-outline-primary  rounded-pill waves-effect btn-sm waves-light mb-3 float-end"><span class="mdi mdi-plus-circle"></span>
                                                                Add New Option</button><br /><br />

<table
                                                                style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 14px; background-color: #fff; border: 1px solid #ddd;">
                                                                <thead
                                                                    style="background-color: #378d4eff; text-align: left;color:white">
                                                                    <tr>
                                                                        <th
                                                                            style="width: 5%; padding: 10px; border-bottom: 2px solid #378d4eff;">
                                                                            #</th>
                                                                        <th
                                                                            style="padding: 10px; border-bottom: 2px solid #378d4eff;">
                                                                            Option</th>
                                                                        <th
                                                                            style="width: 10%; padding: 10px; border-bottom: 2px solid #378d4eff; text-align: center;">
                                                                            Answer</th>
                                                                        <th
                                                                            style="width: 15%; padding: 10px; border-bottom: 2px solid #378d4eff; text-align: center;">
                                                                            Delete</th>
                                                                    </tr>
                                                                </thead>

                                                                <tbody id="table-body">
                                                                    <?php
                                                                    $j = 0;
                                                                    if (!empty($getoptiondata)) {
                                                                        foreach ($getoptiondata as $eachoptiondata) {
                                                                            $j++;
                                                                            $answerIcon = $eachoptiondata['truefalse'] == 1 ?
                                                                                '<span class="mdi mdi-check-bold" style="color: green;"></span>' :
                                                                                '<span class="mdi mdi-close-thick" style="color: red;"></span>';
                                                                    ?>
                                                                            <tr style="border-bottom: 1px solid #eee; transition: background-color 0.2s;"
                                                                                onmouseover="this.style.backgroundColor='#f9f9f9';"
                                                                                onmouseout="this.style.backgroundColor='white';">

                                                                                <td style="padding: 10px; text-align: center;">
                                                                                    <?php echo $j; ?>
                                                                                </td>

                                                                                <td contenteditable="true"
                                                                                    onBlur="updateDate(this,'values','<?php echo $eachoptiondata['o_id'] ?>')"
                                                                                    style="padding: 10px; border-left: 1px solid #eee; border-right: 1px solid #eee; cursor: text;">
                                                                                    <?php echo $eachoptiondata['values'] ?>
                                                                                </td>

                                                                                <td style="padding: 10px; text-align: center;">
                                                                                    <?php
                                                                                    if ($pagerow['type'] == '5') {
                                                                                        $type = $pagerow['type'];
                                                                                        $questionId = $eachoptiondata['question_id'];
                                                                                        $truefalse = $eachoptiondata['truefalse'];
                                                                                        $optionId = $eachoptiondata['o_id'];

                                                                                        $btnColor = $truefalse == 1 ? 'btn btn-outline-success waves-effect btn-xs waves-light mb-3 rounded-pill' : 'btn btn-outline-danger waves-effect btn-xs waves-light mb-3 rounded-pill';
                                                                                        $btnText = $truefalse == 1 ? 'Correct' : 'Wrong';
                                                                                    ?>
                                                                                        <button type="button"
                                                                                            onclick="toggleTrueFalse(this, '<?php echo $optionId; ?>')"
                                                                                            data-question-id="<?php echo $questionId; ?>"
                                                                                            data-type="<?php echo $type; ?>"
                                                                                            data-current="<?php echo $truefalse; ?>"
                                                                                            class="<?php echo $btnColor; ?>">
                                                                                            <?php echo $btnText; ?>
                                                                                        </button>
                                                                                        <?php } else {
                                                                                        if ($eachoptiondata['truefalse'] == 1) { ?>
                                                                                            <button type="button"
                                                                                                onclick="updateDate('2','truefalse','<?php echo $eachoptiondata['o_id'] ?>')"
                                                                                                class="btn btn-outline-success waves-effect btn-xs waves-light mb-3 rounded-pill">
                                                                                                Correct
                                                                                            </button>
                                                                                        <?php } else { ?>
                                                                                            <button type="button"
                                                                                                onclick="updateDate('1','truefalse','<?php echo $eachoptiondata['o_id'] ?>')"
                                                                                                class="btn btn-outline-danger waves-effect btn-xs waves-light mb-3 rounded-pill">
                                                                                                Wrong
                                                                                            </button>
                                                                                    <?php }
                                                                                    } ?>
                                                                                </td>

                                                                                <td style="padding: 10px; text-align: center;">
                                                                                    <button type="button"
                                                                                        onclick="updateDate('0','status','<?php echo $eachoptiondata['o_id'] ?>')"
                                                                                        title="Delete Option" class="btn btn-outline-danger waves-effect btn-xs waves-light mb-3 rounded-pill">
                                                                                        <span class="mdi mdi-trash-can-outline"></span> Delete
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
                                                                    <?php }
                                                                    } ?>
                                                                </tbody>
                                                            </table>

</div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <ul class="nav nav-pills nav-fill navtab-bg">
                                                    <li class="nav-item">
                                                        <a href="#Settings" data-bs-toggle="tab" aria-expanded="true" class="nav-link <?php if ($tab == 1)
                                                                                                                                            echo "active"; ?>">
                                                            Settings
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#Template" data-bs-toggle="tab" aria-expanded="false" class="nav-link <?php if ($tab == 2)
                                                                                                                                            echo "active"; ?>">
                                                            Template
                                                        </a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane <?php if ($tab == 1)
                                                                                echo "show active"; ?>" id="Settings">

                                                        <div class="col-md-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="x_panel">
                                                                        <form class="form-horizontal"
                                                                            action="<?php echo base_url('Assessment/trainings/edit_attempts_question') ?>"
                                                                            method="POST"><?= csrf_field() ?>
                                                                            <div class="col-md-12">
                                                                                <?php if ($pagerow['type'] == 5 || $pagerow['type'] == 6) { ?>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12 mb-2">
                                                                                            <label>Correct feedback</label>
                                                                                            <input type="text"
                                                                                                class="form-control col-md-12"
                                                                                                name="correct" placeholder=""
                                                                                                value="<?php echo isset($qrow['correct']) ? htmlspecialchars($qrow['correct']) : '' ?>" />
                                                                                        </div>
                                                                                        <div class="col-md-12 mb-2">
                                                                                            <label>Incorrect feedback 1</label><br>
                                                                                            <input type="text"
                                                                                                class="form-control col-md-12"
                                                                                                name="incorrect2" placeholder=""
                                                                                                value="<?php echo isset($qrow['incorrect2']) ? htmlspecialchars($qrow['incorrect2']) : '' ?>" />
                                                                                        </div>
                                                                                        <div class="col-md-12 mb-2">
                                                                                            <label>Incorrect feedback 2</label>
                                                                                            <input type="text"
                                                                                                class="form-control col-md-12"
                                                                                                name="incorrect" placeholder=""
                                                                                                value="<?php echo isset($qrow['incorrect']) ? htmlspecialchars($qrow['incorrect']) : '' ?>" />
                                                                                        </div>

                                                                                        <div class="col-md-12 mb-2">
                                                                                            <label>Attempts</label>
                                                                                            <select name="noAttempts"
                                                                                                class="form-control">
                                                                                                <option value="2" selected>2</option>
                                                                                                
                                                                                            </select>
                                                                                        </div>

                                                                                    </div>
                                                                                <?php } ?>
                                                                                <?php if ($type == 4) { ?>
                                                                                    <div class="row">
                                                                                        <div class="col-md-4">
                                                                                            <label>Category</label>
                                                                                            <select name="category"
                                                                                                class="form-control col-md-12">
                                                                                                <?php if (!empty($allcategories)) {
                                                                                                    foreach ($allcategories as $eachcategories) {
                                                                                                        if ($row['category'] == $eachcategories['sc_mcid']) { ?>
                                                                                                            <option selected='selected'
                                                                                                                value="<?php echo $eachcategories['sc_mcid'] ?>">
                                                                                                                <?php echo $eachcategories['description'] ?>
                                                                                                            </option>
                                                                                                        <?php } else { ?>
                                                                                                            <option
                                                                                                                value="<?php echo $eachcategories['sc_mcid'] ?>">
                                                                                                                <?php echo $eachcategories['description'] ?>
                                                                                                            </option>
                                                                                                <?php }
                                                                                                    }
                                                                                                }
                                                                                                ?>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-4">
                                                                                            <label>Score</label>
                                                                                            <input type="text"
                                                                                                class="form-control col-md-12"
                                                                                                name="score" placeholder="Score"
                                                                                                value="<?php echo $row['score'] ?>" />
                                                                                        </div>

                                                                                        <div class="col-md-4">
                                                                                            <label>Type</label>
                                                                                            <select name="quiz_type"
                                                                                                class="form-control col-md-12">
                                                                                                <?php if (!empty($AssessmentQuestionType)) {
                                                                                                    foreach ($AssessmentQuestionType as $quiz_type) {
                                                                                                        if ($row['quiz_type'] == $quiz_type['id_d']) { ?>
                                                                                                            <option selected='selected'
                                                                                                                value="<?php echo $quiz_type['id_d'] ?>">
                                                                                                                <?php echo $quiz_type['name'] ?>
                                                                                                            </option>
                                                                                                        <?php } else { ?>
                                                                                                            <option
                                                                                                                value="<?php echo $quiz_type['id_d'] ?>">
                                                                                                                <?php echo $quiz_type['name'] ?>
                                                                                                            </option>
                                                                                                <?php }
                                                                                                    }
                                                                                                }
                                                                                                ?>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div><br />
                                                                                <?php } ?>

                                                                                <div class="row">

                                                                                    <div class="col-md-12">
                                                                                        <?php if (isset($coursevalidation)): ?>
                                                                                            <div class=col-12 col-sm-4>
                                                                                                <div class="alert alert-danger"
                                                                                                    role="alert">
                                                                                                    <?= $coursevalidation->listErrors() ?>
                                                                                                </div>
                                                                                            </div>
                                                                                        <?php endif; ?>
                                                                                        <input type="hidden" name="q_id"
                                                                                            value="<?php echo isset($qrow['q_id']) ? $qrow['q_id'] : ''; ?>">
                                                                                        <input type="hidden" name="typeval"
                                                                                            value="<?php echo $typeval; ?>">
                                                                                        <input type="hidden" name="typeval"
                                                                                            value="<?php echo $typeval; ?>">
                                                                                        <input type="hidden" name="page_number"
                                                                                            value="<?php echo $page_number; ?>">
                                                                                        <input type="hidden" name="returnUrl"
                                                                                            value="1">
                                                                                        <input type="hidden" name="tab" value="1">
                                                                                        <button type="submit"
                                                                                            class="btn btn-outline-warning waves-effect btn-sm waves-light mb-3 rounded-pill">
                                                                                            Update CYU Settings
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane <?php if ($tab == 2)
                                                                                echo "show active"; ?>" id="Template">
                                                        <div class="col-md-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    ( Note: Respective Default Template will be display if you are
                                                                    not filled any fields)<br />
                                                                    <?php
                                                                    foreach ($assessment_scqmcq_sets as $x => $sets) {
                                                                        if (!empty($AssessmentSettings[$x])) {

                                                                            $item = $AssessmentSettings[$x][0]['value'];
                                                                            $s_id = $AssessmentSettings[$x][0]['s_id'];

                                                                    ?>

                                                                            <form
                                                                                action="<?php echo base_url('Assessment/trainings/setting_data_update') ?>"
                                                                                method="POST"><?= csrf_field() ?>
                                                                                <input type="hidden" name="quiz_settings_type"
                                                                                    value="<?php echo $x ?>">
                                                                                <input type="hidden" name="add_or_update" value="2">
                                                                                <input type="hidden" name="s_id"
                                                                                    value="<?php echo $s_id; ?>">
                                                                                <input type="hidden" name="scourse_id"
                                                                                    value="<?php echo $scourse_id; ?>">
                                                                                <input type="hidden" name="page_id"
                                                                                    value="<?php echo $pagerow['page_id']; ?>">
                                                                                <input type="hidden" name="tab" value="2">
                                                                                <input type="hidden" name="returnUrl" value="2">
                                                                                <div class="row">
                                                                                    <?php if ($x == '59') { ?>
                                                                                        <label><b>Default SCQ:</b>
                                                                                            <?php echo $assessment_scqmcq_sets[$x] ?></label><br>
                                                                                    <?php } elseif ($x == '60') { ?>
                                                                                        <label><b>Default MCQ:</b>
                                                                                            <?php echo $assessment_scqmcq_sets[$x] ?></label><br>
                                                                                    <?php } else { ?>
                                                                                        <label><b>Default :</b>
                                                                                            <?php echo $assessment_scqmcq_sets[$x] ?></label><br>
                                                                                    <?php } ?>
                                                                                    <div class="col-lg-10">
                                                                                        <input class="form-control" name="valid"
                                                                                            type="hidden" />
                                                                                        <input class="form-control" name="value"
                                                                                            type="input"
                                                                                            value="<?php echo isset($item) ? $item : $assessment_scqmcq_sets[$x]; ?>" />
                                                                                    </div>
                                                                                    <div class="col-lg-2">
                                                                                        <button type="submit"
                                                                                            class="btn btn-outline-warning btn-xs rounded-pill waves-effect waves-light mt-2">
                                                                                            Update</button>
                                                                                    </div>
                                                                                </div><br />
                                                                            </form>

                                                                        <?php
                                                                        } else {
                                                                        ?>
                                                                            <form
                                                                                action="<?php echo base_url('Assessment/trainings/setting_data_update') ?>"
                                                                                method="POST"><?= csrf_field() ?>
                                                                                <input type="hidden" name="quiz_settings_type"
                                                                                    value=" <?php echo $x ?>">
                                                                                <input type="hidden" name="add_or_update" value="1">
                                                                                <input type="hidden" name="s_id" value="0">
                                                                                <input type="hidden" name="quiz_settings_id"
                                                                                    value="<?php echo isset($getAssessmentSettings[0]['s_id']) ? $getAssessmentSettings[0]['s_id'] : ''; ?>">
                                                                                <input type="hidden" name="scourse_id"
                                                                                    value="<?php echo isset($scourse_id) ? $scourse_id : ''; ?>">
                                                                                <input type="hidden" name="page_id"
                                                                                    value="<?php echo $pagerow['page_id']; ?>">
                                                                                <input type="hidden" name="tab" value="2">
                                                                                <input type="hidden" name="returnUrl" value="2">
                                                                                <div class="row">
                                                                                    <div class="col-lg-10">
                                                                                        <?php if ($x == '59') { ?>
                                                                                            <label><b>Default SCQ:</b>
                                                                                                <?php echo $assessment_scqmcq_sets[$x] ?></label><br>
                                                                                        <?php } elseif ($x == '60') { ?>
                                                                                            <label><b>Default MCQ:</b>
                                                                                                <?php echo $assessment_scqmcq_sets[$x] ?></label><br>
                                                                                        <?php } else { ?>
                                                                                            <label><b>Default :</b>
                                                                                                <?php echo $assessment_scqmcq_sets[$x] ?></label><br>
                                                                                        <?php } ?>
                                                                                        <input class="form-control" name="valid"
                                                                                            type="hidden" />
                                                                                        <input name="value" class="form-control" value=""
                                                                                            required />
                                                                                    </div>
                                                                                    <div class="col-lg-2">
                                                                                        <button type="submit"
                                                                                            class="btn btn-outline-primary btn-xs rounded-pill waves-effect waves-light mt-2">
                                                                                            Add</button>
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
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="question_bg" style="min-height: 600px" ;>
                                            <div class="question_base">
                                                <?php echo $question['question']; ?>
                                            </div>
                                            <div class="option_container">
                                                
                                                <i><?php echo $kyuselectmcqdescrip; ?></i>
                                                <form id="radioForm"><?= csrf_field() ?>
                                                    <?php if (isset($question_options)) {
                                                        $count = 1;
                                                        foreach ($question_options as $options) { ?>
                                                            <div class="form-check">

                                                                <?php $correct = $options['truefalse'];
                                                                if ($correct == 1) {
                                                                ?>
                                                                    <input class="form-check-input" type="radio" name="exampleRadios"
                                                                        id="exampleRadios<?php echo $count; ?>" value="feedback_correct">

                                                                    <label class="form-check-label options_correct" id="correct" value="1"
                                                                        onchange="toggleDiv()" for="exampleRadios<?php echo $count; ?>">

                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                        <input class="form-check-input" type="radio" name="exampleRadios"
                                                                            id="exampleRadios<?php echo $count; ?>" value="feedback_wrong">
                                                                        <label class="form-check-label options" id="incorrect" value="0"
                                                                            onchange="toggleDiv()" for="exampleRadios<?php echo $count; ?>">
                                                                        <?php
                                                                    }
                                                                        ?>

                                                                        <?php echo $options['values']; ?>
                                                                        </label>
                                                            </div>
                                                            <?php $count++; ?>
                                                    <?php }
                                                    } ?>
                                                    <br>
                                                    <button
                                                        class="btn btn-outline-primary waves-effect btn-sm waves-light rounded-pill"><?php echo $kyusubmit; ?></button></br></br>
                                                </form>
                                                <div id="correct_feedback"
                                                    style="color :white;background:#15a159;padding:5px;border-radius: 5px;">
                                                    <b>Correct Feedback :</b> <?php echo $question['correct']; ?>
                                                </div><br />
                                                <div id="incorrect_feedback"
                                                    style="color :white;background:Tomato;padding:5px;border-radius: 5px;"><b>In
                                                        correct : </b><?php echo $question['incorrect']; ?></div>
                                            </div>
                                        </div>

                <script>
                    function toggleTrueFalse(button, optionId) {
                        const questionId = button.getAttribute('data-question-id');
                        const type = button.getAttribute('data-type');
                        const currentStatus = button.getAttribute('data-current');

                        const newStatus = currentStatus === '1' ? '2' : '1';

                        if (type === '5' && newStatus === '1') {
                            const buttons = document.querySelectorAll(`button[data-question-id="${questionId}"]`);
                            const alreadyCorrect = Array.from(buttons).some(btn =>
                                btn.getAttribute('data-current') === '1'
                            );

                            if (alreadyCorrect) {
                                alert("Only one correct answer is allowed for this single-choice question. Please unselect the current answer before selecting a new one.");
                                return;
                            }
                        }

                        updateDate(newStatus, 'truefalse', optionId);
                    }
                </script>

                <script>
                    var coll = document.getElementsByClassName("collapsible");
                    var i;

                    for (i = 0; i < coll.length; i++) {
                        coll[i].addEventListener("click", function() {
                            this.classList.toggle("active");
                            var contented = this.nextElementSibling;
                            if (contented.style.display === "block") {
                                contented.style.display = "none";
                            } else {
                                contented.style.display = "block";

                            }
                        });
                    }

                    function updateDate(element, column, id) {
                        if (column == 'truefalse' || column == 'status') {
                            var value = element;
                        } else {
                            var value = element.innerText;
                        }
                        console.log(value + column + id);
                        let scourse_id = '<?php echo $scourse_id ?>';
                        let question_id = '<?php echo isset($qrow['q_id']) ? $qrow['q_id'] : '' ?>';
                        $.ajax({
                            url: '<?php echo base_url('Assessment/trainings/updatedateformat') ?>',
                            type: 'post',
                            data: {
                                value: value,
                                column: column,
                                id: id,
                                scourse_id: scourse_id,
                                question_id: question_id
                            },
                            success: function(data) {
                                var obj = JSON.parse(data);

                                console.log(obj);

                                if (obj.status === 'OK') {
                                    console.log('inside on condition');
                                    location.reload(true);

                                } else {
                                    alert(obj.status, 'Something Went Wrong! Please contact Site Admin!');
                                }
                                location.reload(true);
                            },
                            error: function(xhr, textStatus, errorThrown) {
                                console.log('request failed');
                            }

                        })

                    }

                    function addDate(element, column, id) {
                        if (column == 'truefalse') {
                            var value = element;
                        } else {
                            var value = element.innerText;
                        }
                        let scourse_id = '<?php echo $scourse_id ?>';
                        let question_id = '<?php echo isset($qrow['q_id']) ? $qrow['q_id'] : '' ?>';
                        $.ajax({
                            url: '<?php echo base_url('Assessment/trainings/adddateformat') ?>',
                            type: 'post',
                            data: {
                                value: value,
                                column: column,
                                id: id,
                                scourse_id: scourse_id,
                                question_id: question_id
                            },
                            success: function(data) {
                                var obj = JSON.parse(data);

                                console.log(obj);

                                if (obj.status === 'OK') {
                                    console.log('inside on condition');
                                    if (column == 'duration' || column == 'start_day') {
                                        location.reload(true);
                                    }

                                } else {
                                    alert(obj.status, 'Something Went Wrong! Please contact Site Admin!');
                                }
                                location.reload(true);
                            },
                            error: function(xhr, textStatus, errorThrown) {
                                console.log('request failed');
                            }

                        })

                    }
                </script>

                <script>
                    document.getElementById('addRowBtn').addEventListener('click', function() {
                        var tableBody = document.getElementById('table-body');

                        var rowCount = tableBody.getElementsByTagName('tr').length + 1;

                        var newRow = document.createElement('tr');

                        newRow.innerHTML = `
            <td>${rowCount}</td>
           
            <td contenteditable="true" onBlur="addDate(this,'values','new')"></td>
            <td contenteditable="true" onBlur="addDate(this,'score','new')"></td>
             <td>
                <button type="button" class="collapsible" title="toggle" class="nav-link" data-widget="pushmenu">&nbsp;&nbsp;</button>
                <div class="contented">
                    <label>Correct</label>&nbsp;
                     <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" name="truefalse" class="form-check-input" value="2" checked onclick="updateDate('2','truefalse','new')" id="radioNo"> No
                        </label>
                    </div>
                      <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" name="truefalse" class="form-check-input" value="1" onclick="updateDate('1','truefalse','new')"> Yes
                        </label>
                    </div>
                </div>
            </td>
        `;

                        tableBody.appendChild(newRow);
                    });
                </script>