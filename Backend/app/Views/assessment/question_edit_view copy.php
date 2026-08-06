<style>
    /* Style the table */
    table {
        width: 100%;
        border-collapse: collapse;
        /* Removes space between cells */
        font-family: Arial, sans-serif;
        /* background-color: #f9f9f9; */
        box-shadow: none;
        /* Remove the box shadow */
    }

    /* Style the table headers */
    th {
        padding: 12px 15px;
        text-align: left;
        background-color: #4CAF50;
        /* Green background */
        color: white;
        font-weight: bold;
    }

    /* Style the table rows */
    td {
        padding: 10px 15px;
        text-align: left;
        vertical-align: middle;
        background-color: #ffffff;

        /* White background for cells */
    }

    /* Add alternating row colors for better readability */
    tbody tr:nth-child(odd) {
        background-color: #f1f1f1;
        border: 1px solid;
        /* Light gray background for odd rows */
    }

    tbody tr:nth-child(even) {
        background-color: #ffffff;
        border: 1px solid;
        /* White background for even rows */
    }

    /* Hover effect for rows */
    tbody tr:hover {
        background-color: #e0e0e0;

        /* Light gray background when hovering over row */
    }

    /* Style buttons inside table */
    button.collapsible {
        background-color: #4CAF50;
        /* Green background */
        color: white;
        padding: 6px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
    }

    button.collapsible:hover {
        background-color: #45a049;
        /* Darker green on hover */
    }

    /* Remove borders around editable cells */
    td[contenteditable="true"] {
        background-color: #f7f7f7;
        /* Light background for editable cells */
        outline: none;
        /* Remove the default outline around editable cells */
    }

    /* Show collapsible content (Correct option area) */
    .contented {
        display: none;
        /* Hidden by default */
        background-color: #e9e9e9;
        /* Light background for collapsible content */
        padding: 10px;
        border-radius: 4px;
        margin-top: 5px;
    }

    button.collapsible:active+.contented {
        display: block;
        /* Show when button is clicked */
    }

    /* Add spacing for form inputs */
    .form-check-inline {
        margin-right: 10px;
    }

    .form-check-label {
        font-size: 14px;
    }

    .collapsible {

        /* color: white; */
        cursor: pointer;
        /* background-color: rgba(0, 0, 0, 0.4); */
        width: 100%;
        border: none;
        text-align: center;
        outline: none;
        font-size: 12px;
        padding: 2px;
    }

    .contented {
        /* color: white; */
        padding: 0 58px;
        display: none;
        overflow: hidden;
        /* background-color: rgba(0, 0, 0, 0.4); */

    }

    /* Add some styling to the "Add New Entry" button */
    #addRowBtn {
        /* padding: 8px 16px; */
        background-color: skyblue;
        color: white;
        border: none;
        /* border-radius: 4px; */
        cursor: pointer;
        /* margin-top: 10px; */
        /* font-size: 14px; */
    }

    #addRowBtn:hover {
        background-color: #45a049;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('SCORM/course_builder/Editor') ?>">Development</a></li>

                </ol>
            </div>
            <h4 class="page-title"><?php echo $sub_header_1; ?> - <?php echo $pagerow['page_number'] ?></h4>
        </div>
    </div>
</div>
<div class="row">
    <?php
    $sub_page_main = $pagerow['sub_page_main'];
    if ($sub_page_main == 0) {
    ?>
        <div class="form-group col-md-4 mb-2">
            <?php if ($prev_page) { ?>
                <form class="form-horizontal" action="<?php echo base_url('SCORM/course_builder/scorm_course_pages/page_edit_view') ?>" method="POST"><?= csrf_field() ?>
                    <?= csrf_field() ?>
                    <input type="hidden" name="page_id" value="<?php echo $prev_page[0]['page_id'] ?>">
                    <input type="hidden" name="page_number" value="<?php echo $prev_page[0]['page_number'] ?>">
                    <input type="hidden" name="page_name" value="<?php echo $prev_page[0]['page_name'] ?>">
                    <button type="submit" alt="Next" class="" style="all: unset; cursor: pointer;"><i class="mdi mdi-arrow-left-circle-outline font-22"></i></button>
                </form>
            <?php } ?>
        </div>

        <div class="form-group col-md-4 mb-2">
            <form class="form-horizontal  float-end mt-0" action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/page_add_sub_page') ?>" method="POST"><?= csrf_field() ?>
                <input type="hidden" name="page_number" value="<?php echo $pagerow['page_number'] ?>">
                <input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
                <input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
                <button type="submit" class="btn btn-soft-warning waves-effect waves-light">Create Sub Page <i class="mdi mdi-arrow-up-bold-circle-outline"></i></button>
            </form>
        </div>
        <div class="form-group col-md-4 mb-2 ribbon ribbon-blue float-start">
            <?php if ($next_page) { ?>
                <form class="form-horizontal  float-end mt-0" action="<?php echo base_url('SCORM/course_builder/scorm_course_pages/page_edit_view') ?>" method="POST"><?= csrf_field() ?>
                    <input type="hidden" name="page_id" value="<?php echo $next_page[0]['page_id'] ?>">
                    <input type="hidden" name="page_number" value="<?php echo $next_page[0]['page_number'] ?>">
                    <input type="hidden" name="page_name" value="<?php echo $next_page[0]['page_name'] ?>">
                    <input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
                    <button type="submit" alt="Next" style="all: unset; cursor: pointer;"><i class="mdi mdi-arrow-right-circle-outline font-22"></i></button>
                </form>
                <?php } else {
                if ($sub_page_main == 0) {
                    $nxt_page = $pagerow['page_number'] + 1;
                ?>
                    <form class="form-horizontal  float-end mt-0" action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/page_add_view') ?>" method="POST"><?= csrf_field() ?>
                        <input type="hidden" name="nxt_pageid" value="<?php echo $nxt_page; ?>">
                        <input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
                        <button type="submit" class="btn btn-danger rounded-pill waves-effect waves-light">Create New Page <i class="mdi mdi-arrow-right-bold-circle-outline"></i></button>
                    </form>
            <?php
                }
            } ?>
        </div>
    <?php
    } else {
    ?>
        <form class="form-horizontal mb-2" action="<?php echo base_url('SCORM/course_builder/scorm_course_pages/page_edit_view') ?>" method="POST"><?= csrf_field() ?>
            <input type="hidden" name="page_id" value="<?php echo $pagerow['sub_page_main']; ?>">
            <input type="hidden" name="page_number" value="<?php echo $pagerow['sub_page_main']; ?>">
            <input type="hidden" name="course_id" value="<?php echo $scourse_id ?>">
            <button type="submit" class="btn btn-success rounded-pill waves-effect waves-light"><i class="mdi mdi-arrow-left-bold-circle-outline"></i> Main</button>
        </form>
    <?php
    }
    ?>

</div>
<?php if ($sub_page_main == 0) { ?>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url($editpage) ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="form-group col-md-4 mb-2">
                            <label>Page Name</label>
                            <input type="text" class="form-control col-md-12" name="page_name" placeholder="Page Name" value="<?php echo $pagerow['page_name'] ?>" />
                        </div>
                        <div class="form-group col-md-2 mb-2">
                            <label>Page Type</label>
                            <select name="type" class="form-control">
                                <option value="5" <?php echo ($pagerow['type'] == 5) ? 'selected' : ''; ?>>SCQ</option>
                                <option value="6" <?php echo ($pagerow['type'] == 6) ? 'selected' : ''; ?>>MCQ</option>
                                <!-- <option value="4" <?php echo ($pagerow['type'] == 4) ? 'selected' : ''; ?>>Quiz</option> -->
                            </select>
                        </div>
                        <div class="form-group col-md-2 mb-2">
                            <label>Page Number</label>
                            <input type="number" class="form-control col-md-12" name="page_number" placeholder="Page Number" value="<?php echo $pagerow['page_number'] ?>" />
                        </div>
                        <div class="form-group col-md-2 mb-2">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="1" <?php echo ($pagerow['status'] == 1) ? 'selected' : ''; ?>>Editing</option>
                                <!-- <option value="2" <?php echo ($pagerow['status'] == 2) ? 'selected' : ''; ?>>CE Rev</option>
                                <option value="3" <?php echo ($pagerow['status'] == 3) ? 'selected' : ''; ?>>CE Fix</option>
                                <option value="4" <?php echo ($pagerow['status'] == 4) ? 'selected' : ''; ?>>Client Rev</option>
                                <option value="5" <?php echo ($pagerow['status'] == 5) ? 'selected' : ''; ?>>Client Fix</option> -->
                                <option value="7" <?php echo ($pagerow['status'] == 7) ? 'selected' : ''; ?>>Dev Completed</option>
                                <option value="0" <?php echo ($pagerow['status'] == 0) ? 'selected' : ''; ?>>Delete</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2 mt-3 mb-2">
                            <?php if (isset($coursevalidation)) : ?>
                                <div class=col-12 col-sm-4>
                                    <div class="alert alert-white" role="alert">
                                        <?= $coursevalidation->listErrors() ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
                            <!-- <input type="hidden" name="status" value="1"> -->
                            <button type="submit" class="btn btn-outline-warning waves-effect btn-sm waves-light ">
                                Update
                            </button>
                        </div>

                </form>
            </div>
        </div>
    </div>
<?php } ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="x_panel">
                    <form class="form-horizontal" action="<?php echo base_url($editquestion) ?>" method="POST"><?= csrf_field() ?>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-10">
                                    <label>Question</label>
                                    <input type="text" class="form-control col-md-12" name="question" placeholder="Question" value="<?php echo isset($row['question']) ? htmlspecialchars($row['question']) : '' ?>" />
                                </div>
                                <div class="col-md-2">
                                    <?php if (isset($coursevalidation)) : ?>
                                        <div class=col-12 col-sm-4>
                                            <div class="alert alert-danger" role="alert">
                                                <?= $coursevalidation->listErrors() ?>
                                            </div>
                                        </div>
                                    <?php endif; ?><br />
                                    <input type="hidden" name="q_id" value="<?php echo $row['q_id']; ?>">
                                    <input type="hidden" name="page_id" value="<?php echo $pagerow['page_id']; ?>">
                                    <input type="hidden" name="page_number" value="<?php echo $pagerow['page_number']; ?>">
                                    <input type="hidden" name="typeval" value="<?php echo $typeval; ?>">
                                    <!-- <input type="hidden" name="returnUrl" value="1"> -->
                                    <button type="submit" class="btn btn-outline-info waves-effect btn-sm waves-light">
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
$array  = array_map('intval', str_split($userlevel)); ?>
<!-- Button to add new entry -->
<!-- <button type="button" id="addRowBtn" class="btn btn-sm btn-primary">+ Add New Option</button><br /> -->
<div class="row">

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <button type="submit" id="addRowBtn" class="btn btn-outline-primary  rounded-pill waves-effect btn-sm waves-light mb-3 float-end"><span class="mdi mdi-plus-circle"></span>
                    Add New Option</button><br /><br />


                <!-- <h5><?php echo $row['question']; ?></h5> -->

                <table>
                    <thead>
                        <tr>
                            <th style="width: 10%">#</th>
                            <th>Option</th>
                            <!-- <th style="width: 10%">Score</th> -->
                            <th style="width: 10%">Answer</th>
                            <th style="width: 10%">Delete</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        <?php
                        $j = 0;
                        if ($getoptiondata != '') {
                            foreach ($getoptiondata as $eachoptiondata) {
                                $j = $j + 1;
                                if ($eachoptiondata['truefalse'] == 1) {
                                    $answer = '<span class="mdi mdi-check-bold"></span>';
                                } else {
                                    $answer = '<span class="mdi mdi-close-thick"></span>';
                                }
                        ?>
                                <tr>
                                    <td><?php echo $j; ?></td>

                                    <td contenteditable="true" onBlur="updateDate(this,'values','<?php echo $eachoptiondata['o_id'] ?>')">
                                        <?php echo $eachoptiondata['values'] ?>
                                    </td>
                                    <!--  <td contenteditable="true" onBlur="updateDate(this,'score','<?php echo $eachoptiondata['o_id'] ?>')">
                                        <?php echo ($eachoptiondata['score'] != 0) ? $eachoptiondata['score'] : '' ?>
                                    </td> -->
                                    <td>
                                        <?php
                                        if ($eachoptiondata['truefalse'] == 1) {
                                        ?>
                                            <button type="button" onclick="updateDate('2','truefalse','<?php echo $eachoptiondata['o_id'] ?>')" class="btn btn-outline-success waves-effect btn-sm waves-light">Correct</button>
                                        <?php
                                        } else {
                                        ?>
                                            <button type="button" onclick="updateDate('1','truefalse','<?php echo $eachoptiondata['o_id'] ?>')" class="btn btn-outline-danger waves-effect btn-sm waves-light">Wrong</button>
                                        <?php  }
                                        ?>
                                    </td>
                                    <td contenteditable="true" onBlur="updateDate(this,'status','<?php echo $eachoptiondata['o_id'] ?>')">
                                        <button type="button" onclick="updateDate('0','status','<?php echo $eachoptiondata['o_id'] ?>')" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-trash-can-outline"></span></button>
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
            <a href="#Settings" data-bs-toggle="tab" aria-expanded="true" class="nav-link <?php if ($tab == 1) echo "active"; ?>">
                Settings
            </a>
        </li>
        <li class="nav-item">
            <a href="#Template" data-bs-toggle="tab" aria-expanded="false" class="nav-link <?php if ($tab == 2) echo "active"; ?>">
                Template
            </a>
        </li>
    </ul>
    <? php // print_r($tab); 
    ?>
    <div class="tab-content">
        <div class="tab-pane <?php if ($tab == 1) echo "show active"; ?>" id="Settings">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="x_panel">
                            <form class="form-horizontal" action="<?php echo base_url('Assessment/trainings/edit_attempts_question') ?>" method="POST"><?= csrf_field() ?>
                                <div class="col-md-12">
                                    <?php if ($pagerow['type'] == 5 || $pagerow['type'] == 6) { ?>
                                        <div class="row">
                                            <div class="col-md-12 mb-2">
                                                <label>Correct feedback</label>
                                                <input type="text" class="form-control col-md-12" name="correct" placeholder="" value="<?php echo $row['correct'] ?>" />
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                <label>Incorrect feedback 1</label><br>
                                                <input type="text" class="form-control col-md-12" name="incorrect2" placeholder="" value="<?php echo $row['incorrect2'] ?>" />
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                <label>Incorrect feedback 2</label>
                                                <input type="text" class="form-control col-md-12" name="incorrect" placeholder="" value="<?php echo $row['incorrect'] ?>" />
                                            </div>

                                            <div class="col-md-12 mb-2">
                                                <label>Attempts</label>
                                                <select name="noAttempts" class="form-control">
                                                    <option value="2" selected>2</option>
                                                    <!-- <option value="1">1</option> -->
                                                </select>
                                            </div>

                                        </div>
                                    <?php } ?>
                                    <?php if ($type == 4) { ?>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Category</label>
                                                <select name="category" class="form-control col-md-12">
                                                    <?php if (!empty($allcategories)) {
                                                        foreach ($allcategories as $eachcategories) {
                                                            // print_r($eachcategories);
                                                            if ($row['category'] == $eachcategories['sc_mcid']) { ?>
                                                                <option selected='selected' value="<?php echo $eachcategories['sc_mcid'] ?>"><?php echo $eachcategories['description'] ?></option>
                                                            <?php } else { ?>
                                                                <option value="<?php echo $eachcategories['sc_mcid'] ?>"><?php echo $eachcategories['description'] ?></option>
                                                    <?php }
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Score</label>
                                                <input type="text" class="form-control col-md-12" name="score" placeholder="Score" value="<?php echo $row['score'] ?>" />
                                            </div>

                                            <div class="col-md-4">
                                                <label>Type</label>
                                                <select name="quiz_type" class="form-control col-md-12">
                                                    <?php if (!empty($AssessmentQuestionType)) {
                                                        foreach ($AssessmentQuestionType as $quiz_type) {
                                                            if ($row['quiz_type'] == $quiz_type['id_d']) { ?>
                                                                <option selected='selected' value="<?php echo $quiz_type['id_d'] ?>"><?php echo $quiz_type['name'] ?></option>
                                                            <?php } else { ?>
                                                                <option value="<?php echo $quiz_type['id_d'] ?>"><?php echo $quiz_type['name'] ?></option>
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
                                            <?php if (isset($coursevalidation)) : ?>
                                                <div class=col-12 col-sm-4>
                                                    <div class="alert alert-danger" role="alert">
                                                        <?= $coursevalidation->listErrors() ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <input type="hidden" name="q_id" value="<?php echo $row['q_id']; ?>">
                                            <input type="hidden" name="typeval" value="<?php echo $typeval; ?>">
                                            <input type="hidden" name="typeval" value="<?php echo $typeval; ?>">
                                            <input type="hidden" name="page_number" value="<?php echo $page_number; ?>">
                                            <input type="hidden" name="returnUrl" value="1">
                                            <input type="hidden" name="tab" value="1">
                                            <button type="submit" class="btn btn-outline-warning waves-effect btn-sm waves-light mb-3">
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
        <div class="tab-pane <?php if ($tab == 2) echo "show active"; ?>" id="Template">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        ( Note: Respective Default Template will be display if you are not filled any fields)<br />
                        <?php //$getAssessmentSets = '';

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
                                            <label><b>Default SCQ:</b> <?php echo  $assessment_scqmcq_sets[$x] ?></label><br>
                                        <?php } elseif ($x == '60') { ?>
                                            <label><b>Default MCQ:</b> <?php echo  $assessment_scqmcq_sets[$x] ?></label><br>
                                        <?php } else { ?>
                                            <label><b>Default :</b> <?php echo  $assessment_scqmcq_sets[$x] ?></label><br>
                                        <?php } ?>
                                        <div class="col-lg-10">
                                            <input class="form-control" name="valid" type="hidden" />
                                            <input class="form-control" name="value" type="input" value="<?php echo isset($item) ? $item :  $assessment_scqmcq_sets[$x]; ?>" />
                                        </div>
                                        <div class="col-lg-2">
                                            <button type="submit" class="btn btn-outline-warning btn-xs rounded-pill waves-effect waves-light mt-2">
                                                Update</button>
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
                                                <label><b>Default SCQ:</b> <?php echo  $assessment_scqmcq_sets[$x] ?></label><br>
                                            <?php } elseif ($x == '60') { ?>
                                                <label><b>Default MCQ:</b> <?php echo  $assessment_scqmcq_sets[$x] ?></label><br>
                                            <?php } else { ?>
                                                <label><b>Default :</b> <?php echo  $assessment_scqmcq_sets[$x] ?></label><br>
                                            <?php } ?>
                                            <input class="form-control" name="valid" type="hidden" />
                                            <input name="value" class="form-control" value="" required />
                                        </div>
                                        <div class="col-lg-2">
                                            <button type="submit" class="btn btn-outline-primary btn-xs rounded-pill waves-effect waves-light mt-2">
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

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="x_panel">
                    <h6>Image</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <?php

                            $base = base_url();
                            // print_r($base);
                            if ($base == 'http://localhost:8888/projects_dochek/projects_dochek') {
                                $baseloc = '/Users/pchandran/Sites/projects_dochek/projects_dochek/';
                            }
                            if ($base == 'http://localhost/projects_dochek/') {
                                $baseloc = 'D:/wampp/www/projects_dochek/';
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
                                $baseloc = '/var/www/html/';
                            }
                            if ($base == 'http://172.16.2.218/DOCHEK/') {
                                $baseloc = '/var/www/DOCHEK/';
                            }
                            // $folderloc = $baseloc . 'assets/assets/uploads/assessment_image/' . $row['q_id'];
                            $folderloc = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $row['scourse_id'] . '/' . $getCourseData[0]['createdon'] . '/assets/Quiz/' . $row['page_id'] . '/assessment_image';
                            // print_r($question_attachment_image);
                            // exit();
                            if (!empty($question_attachment_image)) {
                                if (is_dir($folderloc)) {
                                    $files2 = scandir($folderloc, SCANDIR_SORT_DESCENDING);
                                    $sno = 0;
                                    echo '<table class="table  table-sm">';
                                    echo '<tr><th>#</th><th>Folder</th><th>Created</th><th>Del</th></tr>';
                                    foreach ($files2 as $key => $value) {

                                        if (strlen($value) > 3) {
                                            $dontshow = 0;
                                            $file_parts = pathinfo($value);
                                            if ($file_parts['extension'] != 'DS_Store') {
                                                $sno++;
                                                echo '<tr><td>';
                                                echo $sno;
                                                echo '</td><td>';

                            ?>
                                                <div class="head bg-dot30 np tac">
                                                    <!-- <img style="max-height:100px;" src="<?php echo base_url() ?>/assets/assets/uploads/assessment_image/<?php echo $row['q_id']; ?>/<?php echo $value ?>" class="img-squre img-thumbnail" /> -->
                                                    <img style="max-height:100px;" src="<?php echo base_url() ?>/assets/assets/uploads/SCORM_course_document/<?php echo $row['scourse_id']; ?>/<?php echo $getCourseData[0]['createdon']; ?>/assets/Quiz/<?php echo $row['page_id']; ?>/assessment_image/<?php echo $value ?>" class="img-squre img-thumbnail" />

                                                </div><br />
                                                <?php

                                                echo '</td><td>';
                                                $file_creation_date = filectime($folderloc . '/' . $value);
                                                echo date('Y-m-d H:i:s', $file_creation_date);
                                                echo '</td><td>';
                                                //if ($row['thumbnail']!=$value) {
                                                ?>
                                                <form class="form-horizontal" action="<?php echo base_url('Assessment/trainings/delquestion_file'); ?>" method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="fileloc" value="<?php echo $folderloc . '/' . $value; ?>">
                                                    <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')"><span class="mdi mdi-trash-can-outline"></span></button>
                                                </form>


                            <?php
                                                //	}

                                                echo '</td><tr>';
                                            }
                                        }
                                    }
                                    echo '</table>';
                                } else {
                                    echo 'No Files';
                                }
                            } else {
                                echo 'No Files';
                            } ?>
                        </div>

                        <div class="col-md-6">
                            <div class="form-row">
                                <form class="form-horizontal1" enctype="multipart/form-data" action="<?php echo base_url($form_url_1) ?>" method="POST"><?= csrf_field() ?>
                                    <div class="form-group col-md-6 mb-2">
                                        <input type="file" name="file" />
                                    </div>

                                    <div class="form-group col-md-6 mb-2">
                                        <input type="hidden" name="scourse_id" value="<?php echo $row['scourse_id'] ?>">
                                        <input type="hidden" name="q_id" value="<?php echo $row['q_id'] ?>">
                                        <input type="hidden" name="type" value="1">
                                        <button type="submit" class="btn btn-outline-info waves-effect btn-sm waves-light mb-3 col-md-12">Upload Image</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="x_panel">
                    <h6>Upload PDF</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <?php

                            $folderloc = $baseloc . 'assets/assets/uploads/assessment_pdf/' . $row['q_id'];
                            if (is_dir($folderloc)) {
                                $files2 = scandir($folderloc, SCANDIR_SORT_DESCENDING);
                                $sno = 0;
                                echo '<table class="table  table-sm">';
                                echo '<tr><th>#</th><th>Folder</th><th>Created</th><th>Del</th></tr>';
                                foreach ($files2 as $key => $value) {
                                    if (strlen($value) > 3) {

                                        $dontshow = 0;
                                        $file_parts = pathinfo($value);
                                        if ($file_parts['extension'] != 'DS_Store') {
                                            $sno++;
                                            echo '<tr><td>';
                                            echo $sno;
                                            echo '</td><td>';
                                            echo $value;

                                            echo '</td><td>';
                                            $file_creation_date = filectime($folderloc . '/' . $value);
                                            echo date('Y-m-d H:i:s', $file_creation_date);
                                            echo '</td><td>';
                                            // if ($row['thumbnail'] != $value) {
                            ?>
                                            <form class="form-horizontal" action="<?php echo base_url('Assessment/trainings/delquestion_file'); ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="fileloc" value="<?php echo $folderloc . '/' . $value; ?>">
                                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')"><span class="mdi mdi-trash-can-outline"></span></button>
                                            </form>
                            <?php
                                            // }

                                            echo '</td><tr>';
                                        }
                                    }
                                }
                                echo '</table>';
                            } else {
                                echo 'No Files';
                            } ?>
                        </div>
                        <div class="col-md-6">
                            <div class="form-row">
                                <form class="form-horizontal2" enctype="multipart/form-data" action=<?php echo base_url($form_url_2); ?> method="POST"><?= csrf_field() ?>
                                    <div class="form-group col-md-6 mb-2">
                                        <input type="file" name="file" />
                                    </div>
                                    <div class="form-group col-md-6 mb-2">
                                        <input type="hidden" name="scourse_id" value="<?php echo $row['scourse_id'] ?>">
                                        <input type="hidden" name="q_id" value="<?php echo $row['q_id'] ?>">
                                        <input type="hidden" name="type" value="2">
                                        <button type="submit" class="btn btn-outline-warning waves-effect btn-sm waves-light mb-3 col-md-12">Upload PDF Document</button>
                                    </div>
                                    <?php if (isset($pdfvalidation)) : ?>
                                        <div class="form-group col-md-12">
                                            <div class="alert alert-danger" role="alert">
                                                <?= $pdfvalidation->listErrors() ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="x_panel">
                    <div class="row">
                        <h6>Upload Video</h6>
                        <div class="col-md-6">
                            <?php

                            $folderloc = $baseloc . 'assets/assets/uploads/assessment_video/' . $row['q_id'];
                            if (is_dir($folderloc)) {
                                $files2 = scandir($folderloc, SCANDIR_SORT_DESCENDING);
                                $sno = 0;
                                echo '<table class="table  table-sm">';
                                echo '<tr><th>#</th><th>Folder</th><th>Created</th><th>Del</th></tr>';
                                foreach ($files2 as $key => $value) {
                                    if (strlen($value) > 3) {

                                        $dontshow = 0;
                                        $file_parts = pathinfo($value);
                                        if ($file_parts['extension'] != 'DS_Store') {
                                            $sno++;
                                            echo '<tr><td>';
                                            echo $sno;
                                            echo '</td><td>';
                                            echo $value;

                                            echo '</td><td>';
                                            $file_creation_date = filectime($folderloc . '/' . $value);
                                            echo date('Y-m-d H:i:s', $file_creation_date);
                                            echo '</td><td>';
                                            // if ($row['thumbnail'] != $value) {
                            ?>
                                            <form class="form-horizontal" action="<?php echo base_url('Assessment/trainings/delquestion_file'); ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="fileloc" value="<?php echo $folderloc . '/' . $value; ?>">
                                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')"><span class="mdi mdi-trash-can-outline"></span></button>
                                            </form>
                            <?php
                                            // }

                                            echo '</td><tr>';
                                        }
                                    }
                                }
                                echo '</table>';
                            } else {
                                echo 'No Files';
                            } ?>
                        </div>
                        <div class="col-md-6">
                            <div class="form-row">
                                <form class="form-horizontal2" enctype="multipart/form-data" action=<?php echo base_url($form_url_3); ?> method="POST"><?= csrf_field() ?>
                                    <div class="form-group col-md-6 mb-2">
                                        <input type="file" name="file" />
                                    </div>
                                    <div class="form-group col-md-6 mb-2">
                                        <input type="hidden" name="scourse_id" value="<?php echo $row['scourse_id'] ?>">
                                        <input type="hidden" name="q_id" value="<?php echo $row['q_id'] ?>">
                                        <input type="hidden" name="type" value="3">
                                        <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light mb-3 col-md-12">Upload Video</button>
                                    </div>
                                    <?php if (isset($pdfvalidation)) : ?>
                                        <div class="form-group col-md-12">
                                            <div class="alert alert-danger" role="alert">
                                                <?= $pdfvalidation->listErrors() ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateScoreValue(newValue) {
        document.getElementById("scoreInput").value = newValue;
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const scoreInput = document.getElementById("scoreInput");
        const trueFalseRadioYes = document.querySelector("input[value='1']");
        const submitButton = document.getElementById("submitButton");

        function updateScoreValue(value) {
            scoreInput.value = value;
        }

        submitButton.addEventListener("click", function(event) {
            const enteredScore = parseFloat(scoreInput.value);
            const trueFalseChecked = trueFalseRadioYes.checked;

            if (!trueFalseChecked && enteredScore > 0) {
                alert("Please enter a non-positive value for the score.");
                event.preventDefault(); // Prevent form submission
            }
        });
    });
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
        // console.log(value + column + id);

        ///conole.log($(this).find(':selected').data('id'));
        $.ajax({
            url: '<?php echo base_url('Assessment/trainings/updatedateformat') ?>',
            type: 'post',
            data: {
                value: value,
                column: column,
                id: id
            },
            success: function(data) {
                var obj = JSON.parse(data);

                console.log(obj);

                if (obj.status === 'OK') {
                    console.log('inside on condition');
                    // if (column == 'duration' || column == 'start_day') {
                    location.reload(true);
                    // }

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
        // console.log(value + column + id);
        let scourse_id = '<?php echo $row['scourse_id'] ?>';
        let question_id = '<?php echo $row['q_id'] ?>';
        // /conole.log($(this).find(':selected').data('id'));
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

        // Create a new row
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

        // Append the new row to the table body
        tableBody.appendChild(newRow);
    });
</script>