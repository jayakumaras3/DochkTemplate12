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
        background-color: rgb(128, 128, 120);
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
        background-color: rgb(217, 232, 241);
        /* Green background */
        color: white;
        padding: 6px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
    }

    button.collapsible:hover {
        background-color: rgb(135, 199, 229);
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
        cursor: pointer;
    }

    #addRowBtn:hover {
        background-color: #45a049;
    }
</style>
<?php $userlevel = session()->get('userlevel');
$array = array_map('intval', str_split($userlevel)); ?>
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
<div class="row">
    <div class="form-group col-md-5 mb-2">
        <?php if ($prev_page) { ?>
            <form class="form-horizontal" action="<?php echo base_url('Assessment/trainings/add_quiz_option_view') ?>"
                method="POST"><?= csrf_field() ?>
                <?= csrf_field() ?>
                <input type="hidden" name="question_id" value="<?php echo $prev_page[0]['q_id'] ?>">
                <input type="hidden" name="page_id" value="<?php echo $prev_page[0]['page_id'] ?>">
                <input type="hidden" name="type" value="<?php echo $type ?>">
                <button type="submit" alt="Next" class="" style="all: unset; cursor: pointer;"><i
                        class="mdi mdi-arrow-left-circle-outline font-22"></i></button>
            </form>
        <?php } ?>
    </div>
    <div class="form-group col-md-3 mb-2">
        <form class="form-horizontal" action="<?php echo base_url('Assessment/trainings/review_quiz') ?>" method="POST"><?= csrf_field() ?>
            <?= csrf_field() ?>
            <input type="hidden" name="scourse_id" value="<?php echo $row['scourse_id']; ?>">
            <input type="hidden" name="page_id" value="<?php echo $row['page_id'] ?>">
            <div class="form-group">
                <button type="submit"
                    class="btn btn-outline-primary  btn-xs rounded-pill waves-effect waves-light">Review Quiz</button>
            </div>
        </form>
    </div>
    <div class="form-group col-md-4 mb-2 ribbon ribbon-blue float-start">
        <?php if ($next_page) { ?>
            <form class="form-horizontal  float-end mt-0"
                action="<?php echo base_url('Assessment/trainings/add_quiz_option_view') ?>" method="POST"><?= csrf_field() ?>
                <?= csrf_field() ?>
                <input type="hidden" name="question_id" value="<?php echo $next_page[0]['q_id'] ?>">
                <input type="hidden" name="page_id" value="<?php echo $next_page[0]['page_id'] ?>">
                <input type="hidden" name="type" value="<?php echo $type ?>">
                <button type="submit" alt="Next" style="all: unset; cursor: pointer;"><i
                        class="mdi mdi-arrow-right-circle-outline font-22"></i></button>
            </form>
        <?php } else {
        } ?>
    </div>

</div>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="x_panel">
                    <form class="form-horizontal" action="<?php echo base_url($editquestion) ?>" method="POST"><?= csrf_field() ?>
                        <?= csrf_field() ?>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-10">
                                    <label>Question</label>
                                    <!-- <input type="text" class="form-control col-md-12" name="question" placeholder="Question" value="<?php echo isset($row['question']) ? htmlspecialchars($row['question']) : '' ?>" /> -->
                                    <textarea class="form-control col-md-12" name="question" placeholder="Question"
                                        value="<?php echo isset($row['question']) ? htmlspecialchars($row['question']) : '' ?>"
                                        required><?php echo isset($row['question']) ? htmlspecialchars($row['question']) : '' ?></textarea>
                                </div>
                                <div class="col-md-2">
                                    <?php if (isset($coursevalidation)): ?>
                                        <div class=col-12 col-sm-4>
                                            <div class="alert alert-danger" role="alert">
                                                <?= $coursevalidation->listErrors() ?>
                                            </div>
                                        </div>
                                    <?php endif; ?><br />
                                    <input type="hidden" name="q_id" value="<?php echo $row['q_id']; ?>">
                                    <input type="hidden" name="typeval" value="<?php echo $typeval; ?>">
                                    <input type="hidden" name="returnUrl" value="1">
                                    <button type="submit"
                                        class="btn btn-outline-warning waves-effect btn-sm waves-light">
                                        Update
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>



        <div class="card">
            <div class="card-body">


                <!-- <h5><?php echo $row['question']; ?></h5> -->

                <table>
                    <thead>
                        <tr>
                            <th style="width: 10%">#</th>
                            <th>Options</th>
                            <th style="width: 10%">Score</th>
                            <th style="width: 10%">Correct</th>
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

                                    <td contenteditable="true"
                                        onBlur="updateDate(this,'values','<?php echo $eachoptiondata['o_id'] ?>')">
                                        <?php echo $eachoptiondata['values'] ?>
                                    </td>
                                    <td contenteditable="true"
                                        onBlur="updateDate(this,'score','<?php echo $eachoptiondata['o_id'] ?>')">
                                        <?php echo ($eachoptiondata['score'] != 0) ? $eachoptiondata['score'] : '' ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($eachoptiondata['truefalse'] == 1) {
                                        ?>
                                            <div class="class=" form-check mb-2 form-check-success">
                                                <input class="form-check-input  form-check-success" onclick="updateDate('2','truefalse','<?php echo $eachoptiondata['o_id'] ?>')" type="checkbox" value="" id="customckeck15" checked="checked">
                                            </div>
                                        <?php
                                        } else {
                                        ?>
                                            <input class="form-check-input " onclick="updateDate('1','truefalse','<?php echo $eachoptiondata['o_id'] ?>')" type="checkbox" value="" id="customckeck15">

                                        <?php }
                                        ?>
                                    </td>
                                    <td contenteditable="true"
                                        onBlur="updateDate(this,'status','<?php echo $eachoptiondata['o_id'] ?>')">
                                        <button type="button"
                                            onclick="updateDate('0','status','<?php echo $eachoptiondata['o_id'] ?>')"
                                            class="btn btn-outline-danger waves-effect btn-xs waves-light"><span class="mdi mdi-trash-can-outline"></span></button>
                                    </td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                </table>
                <br>
                <span style="color:red; font-size:12px">Enter values for options and score and click outside the table to update the values.</span>
                <br> <button type="submit" id="addRowBtn" class="btn btn-outline-info waves-effect btn-sm waves-light">+ Add New Option</button><br /><br />

            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="x_panel">
                    <form class="form-horizontal"
                        action="<?php echo base_url('Assessment/trainings/edit_attempts_question') ?>" method="POST"><?= csrf_field() ?>
                        <?= csrf_field() ?>
                        <div class="col-md-12">
                            <?php if ($type == 5 || $type == 6) { ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Correct feedback</label>
                                        <input type="text" class="form-control col-md-12" name="correct" placeholder=""
                                            value="<?php echo $row['correct'] ?>" />
                                    </div><br><br><br><br>
                                    <div class="col-md-12">
                                        <label>Incorrect feedback 1 (System Generated)</label><br>
                                        <label>Sorry! That is not the correct answer. Click Try Again.</label>
                                    </div><br><br><br>
                                    <div class="col-md-12">
                                        <label>Incorrect feedback 2</label>
                                        <input type="text" class="form-control col-md-12" name="incorrect" placeholder=""
                                            value="<?php echo $row['incorrect'] ?>" />
                                    </div>
                                    <!-- <div class="col-md-12">
                                        <label>Incorrect feedback 2</label>
                                        <input type="text" class="form-control col-md-12" name="noAttempts" placeholder="" value="<?php echo $row['noAttempts'] ?>" />
                                    </div> -->
                                    <input type="hidden" name="noAttempts" value="2">
                                    <input type="hidden" name="returnUrl" value="1">
                                </div><br />

                            <?php } ?>
                            <?php if ($type == 4) { ?>
                                <div class="row">
                                    <!-- <div class="col-md-4">
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
                                    </div> -->
                                    <!-- <div class="col-md-4">
                                        <label>Score</label>
                                        <input type="text" class="form-control col-md-12" name="score" placeholder="Score" value="<?php echo $row['score'] ?>" />
                                    </div> -->

                                    <div class="col-md-4">
                                        <select name="quiz_type" class="form-control col-md-12">
                                            <?php if (!empty($AssessmentQuestionType)) {
                                                foreach ($AssessmentQuestionType as $quiz_type) {
                                                    if ($row['quiz_type'] == $quiz_type['id_d']) { ?>
                                                        <option selected='selected' value="<?php echo $quiz_type['id_d'] ?>">
                                                            <?php echo $quiz_type['name'] ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $quiz_type['id_d'] ?>">
                                                            <?php echo $quiz_type['name'] ?></option>
                                            <?php }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <input type="hidden" name="returnUrl" value="2">
                                <?php } ?>

                                <div class="col-md-4">
                                    <label>&nbsp;</label>
                                    <?php if (isset($coursevalidation)): ?>
                                        <div class=col-12 col-sm-4>
                                            <div class="alert alert-danger" role="alert">
                                                <?= $coursevalidation->listErrors() ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <input type="hidden" name="q_id" value="<?php echo $row['q_id']; ?>">
                                    <input type="hidden" name="typeval" value="<?php echo $typeval; ?>">
                                    <input type="hidden" name="page_id" value="<?php echo $page_id; ?>">

                                    <button type="submit"
                                        class="btn btn-outline-warning waves-effect btn-sm waves-light ">
                                        Update Type
                                    </button>
                                </div>
                                </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">

                <div class="row">
                    <h4>Upload Image / Video :</h4>
                    <div class="col-md-12">
                        <ul style="color:indianred;padding:5px">
                            <li>File name should be unique; allowed file type: JPG, PNG and mp4; file size limit: 100 MB.</li>
                        </ul>
                    </div>
                    <div class="col-md-12">

                    <div class="x_panel">

                        <div class="row">
                            <div class="col-md-12">
                                <?php

                                $base = base_url();
                                // print_r($base);
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
                                if ($base == 'https://staging.dochek.com/') {
                                    $baseloc = '/var/www/html/DOCHEK/';
                                }
                                if ($base == 'http://localhost/DOCHEKDOTCOM/') {
                                    $baseloc = 'D:/wampp/www/DOCHEKDOTCOM/';
                                }
                                if ($base == 'http://localhost/DOCHEK/') {
                                    $baseloc = 'C:/wamp64/www/DOCHEK/';
                                }
                                if ($base == 'http://172.16.2.218/DOCHEK/') {
                                    $baseloc = '/var/www/DOCHEK/';
                                }

                                // $folderloc = $baseloc . 'assets/assets/uploads/assessment_image/' . $row['q_id'];
                                $folderloc = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $row['scourse_id'] . '/' . $getCourseData[0]['createdon'] . '/assets/Quiz/' . $row['page_id'] . '/assessment_image';
                                // print_r($question_attachment_image);
                                // exit();

                                $fileloc = "";
                                $imagename = isset($question_attachment_image[0]['doc_name']) ? $question_attachment_image[0]['doc_name'] : 'stest';
                                $videoname = isset($question_attachment_video[0]['doc_name']) ? $question_attachment_video[0]['doc_name'] : 'stest';
                                $imagefileloc = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $row['scourse_id'] . '/' . $getCourseData[0]['createdon'] . '/assets/Quiz/' . $row['page_id'] . '/assessment_image/' . $imagename;
                                $videofileloc = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $row['scourse_id'] . '/' . $getCourseData[0]['createdon'] . '/assets/Quiz/' . $row['page_id'] . '/assessment_video/' . $videoname;
                                // print_r($imagefileloc);
                                // print_r($videofileloc);
                                if ($imagename != 'stest') {
                                    if (!empty($question_attachment_image)) {

                                        if (is_dir($folderloc)) {
                                            // $files2 = scandir($folderloc, SCANDIR_SORT_DESCENDING);
                                            $sno = 0;
                                            echo '<table class="table  table-sm">';
                                            echo '<tr><th>#</th><th>Folder</th><th>Del</th></tr>';
                                            foreach ($question_attachment_image as $key => $value) {
                                                $fileloc = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $row['scourse_id'] . '/' . $getCourseData[0]['createdon'] . '/assets/Quiz/' . $row['page_id'] . '/assessment_image/' . $value['doc_name'];

                                                if (file_exists($fileloc)) {
                                                    $sno++;
                                                    echo '<tr><td>';
                                                    echo $sno;
                                                    echo '</td><td>';

                                ?>
                                                    <div class="head bg-dot30 np tac">
                                                        <!-- <img style="max-height:100px;" src="<?php echo base_url() ?>/assets/assets/uploads/assessment_image/<?php echo $row['q_id']; ?>/<?php echo $value['doc_name'] ?>" class="img-squre img-thumbnail" /> -->
                                                        <img style="max-height:100px;"
                                                            src="<?php echo base_url() ?>/assets/assets/uploads/SCORM_course_document/<?php echo $row['scourse_id']; ?>/<?php echo $getCourseData[0]['createdon']; ?>/assets/Quiz/<?php echo $row['page_id']; ?>/assessment_image/<?php echo $value['doc_name'] ?>"
                                                            class="img-squre img-thumbnail" />
                                                    </div><br />
                                                    <?php

                                                    // echo '</td><td>';
                                                    // $file_creation_date = filectime($folderloc . '/' . $value['doc_name']);
                                                    // echo date('Y-m-d H:i:s', $file_creation_date);
                                                    echo '</td><td>';
                                                    //if ($row['thumbnail']!=$value) {
                                                    ?>

                                                    <form class="form-horizontal" action="<?php echo base_url('Assessment/trainings/delquestion_file'); ?>" method="POST"><?= csrf_field() ?>
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="qa_id" value="<?php echo isset($question_attachment_image[0]['qa_id']) ? $question_attachment_image[0]['qa_id'] : '' ?>">
                                                        <input type="hidden" name="file_name" value="<?php echo $value['doc_name']; ?>">
                                                        <button type="submit" class="btn btn-outline-danger btn-xs"
                                                            onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')">
                                                            <span class="mdi mdi-trash-can-outline"></span>
                                                        </button>
                                                    </form>


                                    <?php
                                                    //	}

                                                    echo '</td><tr>';
                                                }
                                            }
                                            echo '</table>';
                                        } else {
                                            echo 'No Files';
                                        }
                                    } else {
                                        echo 'No Files';
                                    }
                                } elseif ($videoname != 'stest') { ?>
                                    <?php

                                    // $folderloc = $baseloc . 'assets/assets/uploads/assessment_video/' . $row['q_id'];
                                    $fileloc = "";
                                    $folderloc = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $row['scourse_id'] . '/' . $getCourseData[0]['createdon'] . '/assets/Quiz/' . $row['page_id'] . '/assessment_video';
                                    if (!empty($question_attachment_video)) {
                                        if (is_dir($folderloc)) {
                                            $files2 = scandir($folderloc, SCANDIR_SORT_DESCENDING);
                                            $sno = 0;
                                            echo '<table class="table  table-sm">';
                                            echo '<tr><th>#</th><th>Folder</th><th>Del</th></tr>';
                                            foreach ($question_attachment_video as $key => $value) {
                                                $fileloc = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $row['scourse_id'] . '/' . $getCourseData[0]['createdon'] . '/assets/Quiz/' . $row['page_id'] . '/assessment_video/' . $value['doc_name'];

                                                if (file_exists($fileloc)) {
                                                    $sno++;
                                                    echo '<tr><td>';
                                                    echo $sno;
                                                    echo '</td><td>';
                                                    echo $value['doc_name'];

                                                    // echo '</td><td>';
                                                    // $file_creation_date = filectime($folderloc . '/' . $value);
                                                    // echo date('Y-m-d H:i:s', $file_creation_date);
                                                    echo '</td><td>';
                                                    //if ($row['thumbnail']!=$value) {
                                    ?>
                                                    <form class="form-horizontal deleteAssementvideo" method="POST"><?= csrf_field() ?>
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="fileloc"
                                                            value="<?php echo $folderloc . '/' . $value['doc_name']; ?>">
                                                        <input type="hidden" name="q_id" value="<?php echo $row['q_id'] ?>">
                                                        <input type="hidden" name="qa_id"
                                                            value="<?php echo isset($question_attachment_video[0]['qa_id']) ? $question_attachment_video[0]['qa_id'] : '' ?>">
                                                        <button type="submit" class="btn btn-outline-danger waves-effect btn-xs waves-light"
                                                            onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')"><span
                                                                class="mdi mdi-trash-can-outline"></span></button>
                                                    </form>

                                    <?php
                                                    // }

                                                    echo '</td><tr>';
                                                }
                                            }

                                            echo '</table>';
                                        } else {
                                            echo 'No Files';
                                        }
                                    } else {
                                        echo 'No Files';
                                    } ?>


                                <?php } else {
                                    echo 'No Files';
                                } ?>
                            </div>
                            <?php if (file_exists($imagefileloc) || file_exists($videofileloc)) { ?>
                                <div class="col-md-12">
                                    <div class="form-row" style="border: 1px solid grey;padding :10px">
                                        <b>Note : Delete existing Image or Video files to upload new</b>
                                    </div>
                                </div>
                            <?php } else {
                            ?>
                                <div class="col-md-12">
                                    <div class="form-row">
                                        <form class="form-horizontal1" enctype="multipart/form-data"
                                            action="<?php echo base_url($form_url_1) ?>" method="POST"><?= csrf_field() ?>
                                            <div class="form-group col-md-12 mb-2">
                                                <input type="file" name="file" accept=".jpg,.png,.jpeg,.mp4" required />
                                            </div>

                                            <div class="form-group col-md-12 mb-2">
                                                <input type="hidden" name="scourse_id" value="<?php echo $row['scourse_id'] ?>">
                                                <input type="hidden" name="createdon"
                                                    value="<?php echo $getCourseData[0]['createdon'] ?>">
                                                <input type="hidden" name="page_id" value="<?php echo $row['page_id'] ?>">
                                                <input type="hidden" name="q_id" value="<?php echo $row['q_id'] ?>">
                                                <input type="hidden" name="type" value="1">
                                                <button type="submit"
                                                    class="btn btn-outline-info waves-effect btn-sm waves-light col-md-8">Upload
                                                    Image / Video</button>
                                            </div>
                                        </form>


                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- <div class="x_panel">
                    <div class="row">
                        <h6>Upload Video <span style="color:burlywood">(<b>Note:</b> Video file name should be unique; allowed file type: mp4; file size limit: 100 MB)</span></h6>
                        <div class="col-md-6">
                            <?php

                            // $folderloc = $baseloc . 'assets/assets/uploads/assessment_video/' . $row['q_id'];
                            $fileloc = "";
                            $folderloc = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $row['scourse_id'] . '/' . $getCourseData[0]['createdon'] . '/assets/Quiz/' . $row['page_id'] . '/assessment_video';
                            if (!empty($question_attachment_video)) {
                                if (is_dir($folderloc)) {
                                    $files2 = scandir($folderloc, SCANDIR_SORT_DESCENDING);
                                    $sno = 0;
                                    echo '<table class="table  table-sm">';
                                    echo '<tr><th>#</th><th>Folder</th><th>Del</th></tr>';
                                    foreach ($question_attachment_video as $key => $value) {
                                        $fileloc = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $row['scourse_id'] . '/' . $getCourseData[0]['createdon'] . '/assets/Quiz/' . $row['page_id'] . '/assessment_video/' . $value['doc_name'];

                                        if (file_exists($fileloc)) {
                                            $sno++;
                                            echo '<tr><td>';
                                            echo $sno;
                                            echo '</td><td>';
                                            echo $value['doc_name'];

                                            // echo '</td><td>';
                                            // $file_creation_date = filectime($folderloc . '/' . $value);
                                            // echo date('Y-m-d H:i:s', $file_creation_date);
                                            echo '</td><td>';
                                            //if ($row['thumbnail']!=$value) {
                            ?>
                                            <form class="form-horizontal deleteAssementvideo" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="fileloc" value="<?php echo $folderloc . '/' . $value['doc_name']; ?>">
                                                <input type="hidden" name="q_id" value="<?php echo $row['q_id'] ?>">
                                                <input type="hidden" name="qa_id" value="<?php echo isset($question_attachment_video[0]['qa_id']) ? $question_attachment_video[0]['qa_id'] : '' ?>">
                                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="return confirm('Are you sure !! Do you want to delete this file?')"><span class="mdi mdi-trash-can-outline"></span></button>
                                            </form>

                            <?php
                                            // }

                                            echo '</td><tr>';
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
                        <?php if (file_exists($imagefileloc) || file_exists($videofileloc)) { ?>
                            <div class="col-md-6">
                                <div class="form-row" style="border: 1px solid grey;padding :10px">
                                    <b>Note : Delete existing Video or Image files to upload new</b>
                                </div>
                            </div>
                        <?php } else {
                        ?>
                            <div class="col-md-6">
                                <div class="form-row">
                                    <form class="form-horizontal2" enctype="multipart/form-data" action=<?php echo base_url($form_url_3); ?> method="POST"><?= csrf_field() ?>
                                        <div class="form-group col-md-6 mb-2">
                                            <input type="file" name="file" accept=".mp4" required />
                                        </div>
                                        <div class="form-group col-md-6 mb-2">
                                            <input type="hidden" name="scourse_id" value="<?php echo $row['scourse_id'] ?>">
                                            <input type="hidden" name="createdon" value="<?php echo $getCourseData[0]['createdon'] ?>">
                                            <input type="hidden" name="page_id" value="<?php echo $row['page_id'] ?>">
                                            <input type="hidden" name="q_id" value="<?php echo $row['q_id'] ?>">
                                            <input type="hidden" name="type" value="3">
                                            <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light col-md-8">Upload Video</button>
                                        </div>
                                        <?php if (isset($videovalidation)): ?>
                                            <div class="form-group col-md-12">
                                                <div class="alert alert-danger" role="alert">
                                                    <?= $videovalidation->listErrors() ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div> -->
                    <!-- <div class="x_panel">
                    <h6>Upload PDF</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <?php

                            $folderloc = $baseloc . 'assets/assets/uploads/assessment_pdf/' . $row['q_id'];
                            // $folderloc = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $row['scourse_id'] . '/' . $getCourseData[0]['createdon'] . '/assets/Quiz/' . $row['page_id'] . '/assessment_image';

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
                                        <button type="submit" class="btn btn-warning btn-sm form-control">Upload PDF Document</button>
                                    </div>
                                    <?php if (isset($pdfvalidation)): ?>
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
                </div> -->
                </div>
                </div>



                
            </div>
        </div>
    </div>
</div>

<!-- Button to add new entry -->
<!-- <button type="button" id="addRowBtn" class="btn btn-sm btn-primary">+ Add New Option</button><br /> -->




<script>
    document.querySelectorAll('[contenteditable="true"]').forEach(cell => {
        cell.addEventListener('input', function() {
            console.log(cell.innerText); // Or save the content to your server
        });
    });

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
        console.log(value + column + id);

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
        console.log(value + column + id);
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
</script>
<script>
    // Function to add a new row to the table
    document.getElementById('addRowBtn').addEventListener('click', function() {
        var tableBody = document.getElementById('table-body');

        // Get the current row number by counting existing rows
        var rowCount = tableBody.getElementsByTagName('tr').length + 1;

        // Create a new row
        var newRow = document.createElement('tr');

        // Add cells to the new row (similar to the structure of your existing rows)
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
<script>
    $(document).ready(function() {
        // Bind submit event to each individual delete form
        $('.deleteAssementimage').on('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            var dataString = new FormData(this); // 'this' refers to the form that was submitted

            // Ensure the form is being submitted correctly
            if (typeof FormData !== 'undefined') {
                $.ajax({
                    url: '<?php echo base_url('Assessment/trainings/delquestion_file'); ?>',
                    type: "POST",
                    data: dataString,
                    async: false,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        var obj = JSON.parse(data);
                        console.log(obj);
                        if (obj.status === 'OK') {
                            console.log('File deleted successfully!');
                            // Optionally, remove the image from the DOM without reloading the page
                            $(event.target).closest('form').remove(); // Remove the form from DOM
                            location.reload();
                        } else {
                            alert('Error: Something went wrong! Please contact the Site Admin.');
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log('Request failed');
                    }
                });
            } else {
                alert("Your Browser Doesn't support FormData API! Use IE 10 or Above!");
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Bind submit event to each individual delete form
        $('.deleteAssementvideo').on('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            var dataString = new FormData(this); // 'this' refers to the form that was submitted

            // Ensure the form is being submitted correctly
            if (typeof FormData !== 'undefined') {
                $.ajax({
                    url: '<?php echo base_url('Assessment/trainings/delquestion_file'); ?>',
                    type: "POST",
                    data: dataString,
                    async: false,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        var obj = JSON.parse(data);
                        console.log(obj);
                        if (obj.status === 'OK') {
                            console.log('File deleted successfully!');
                            // Optionally, remove the image from the DOM without reloading the page
                            $(event.target).closest('form').remove(); // Remove the form from DOM
                            location.reload();
                        } else {
                            alert('Error: Something went wrong! Please contact the Site Admin.');
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log('Request failed');
                    }
                });
            } else {
                alert("Your Browser Doesn't support FormData API! Use IE 10 or Above!");
            }
        });
    });
</script>