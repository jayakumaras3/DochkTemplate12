<?php $userlevel = session()->get('userlevel');
$arrayuserlevel  = array_map('intval', explode(',', $userlevel));
$client = session()->get('client');
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">

                    <li class="breadcrumb-item"><a href="<?php echo base_url('my_training/read_more'); ?>">Course Detail</a></li>
                </ol>
            </div>
            <h4 class="page-title">
                Development
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <?php
    if ($pagesDetails) {
        $arrayleng = count($pagesDetails) - 1;
        $nxt_page = $pagesDetails[$arrayleng]['page_number'] + 1;
    } else {
        $nxt_page = 1;
    }

    ?>
    <div class="col-md-3">
        <form action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/page_add_view') ?>" method="POST"><?= csrf_field() ?>
            <input type="hidden" name="nxt_pageid" value="<?php echo $nxt_page; ?>">
            <input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">

            <button type="submit" class="btn btn-outline-info waves-effect btn-sm waves-light mb-3"><i class="mdi mdi-plus"></i> Create New Page</button>
        </form>
    </div>

    <div class="col-md-3">
        <form action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/view_full_sb') ?>" method="POST"><?= csrf_field() ?>
            <input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
            <button type="submit" class="btn btn-outline-warning waves-effect btn-sm waves-light mb-3"> View Full Storyboard</button>
        </form>
    </div>
    <?php if (in_array('67', $arrayuserlevel) || in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel) || in_array('46', $arrayuserlevel)) { 
    ?>
        <div class="col-md-3">
            <form action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/add_test_feedback') ?>" method="POST"><?= csrf_field() ?>
                <input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
                <button type="submit" class="btn btn-outline-primary waves-effect btn-sm waves-light mb-3" onclick="return confirm('<?php echo lang('Alert.Aler_006') ?>')"> Add Test Feedback </button>
            </form>
        </div>
        <div class="col-md-3">
            <form action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/delete_test_feedback') ?>" method="POST"><?= csrf_field() ?>
                <input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
                <button type="submit" class="btn btn-outline-danger waves-effect btn-sm waves-light mb-3" onclick="return confirm('<?php echo lang('Alert.Aler_007') ?>')"> Delete Test Feedback </button>
            </form>
        </div>
    <?php } ?>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th width=5%>#</th>
                            <th width=5%>Return</th>
                            <th>Page ID</th>
                            <th>Page name</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Quiz</th>
                            <?php if (in_array('46', $arrayuserlevel) || in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel)) { 
                            ?>
                                <th>Storyboard</th>
                            <?php }
                            ?>
                            <?php if ($courseDetails[0]['type'] == 11) {
                                if (in_array('46', $arrayuserlevel) || in_array('67', $arrayuserlevel) || in_array('4', $arrayuserlevel) || in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel)) { // Developer ,QA,PM
                            ?>
                                    <th>Edit</th>
                            <?php }
                            }
                            ?>
                    </thead>

                    <tbody class="row_position">
                        <?php $j = 0;
                        //  $nxt_page = 1;
                        foreach ($pagesDetails as $eachpagesDetails) {
                        ?>

                            <tr id="<?php $eachpagesDetails['page_id']; ?>">
                                <td><?php echo abs($eachpagesDetails['page_number']); ?></td>
                                <td><?php $sup_Page_id = $eachpagesDetails['sub_page_main'];
                                    if ($sup_Page_id > 0) echo abs($sup_Page_id); ?></td>
                                <td><?php echo $eachpagesDetails['page_id'] ?></td>
                                <td><?php echo $eachpagesDetails['page_name'] ?></td>
                                <td>
                                    <?php
                                    $type = $eachpagesDetails['type'];
                                    switch ($type) {
                                        case 1:
                                            echo 'Articulate';
                                            break;
                                        case 2:
                                            echo 'Video';
                                            break;
                                        case 3:
                                            echo 'Html';
                                            break;
                                        case 4:
                                            echo 'Quiz';
                                            break;
                                        case 5:
                                            echo 'SCQ CYU';
                                            break;
                                        case 6:
                                            echo 'MCQ CYU';
                                            break;
                                        case 8:
                                            echo 'Video Sub Page';
                                            break;
                                        case 9:
                                            echo 'Audio Version';
                                            break;
                                    }

                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $status = $eachpagesDetails['status'];
                                    switch ($status) {
                                        case 1:
                                            echo 'Editing';
                                            break;
                                        case 6:
                                            echo 'Ready for Dev';
                                            break;
                                        case 7:
                                            echo 'Dev Completed';
                                            break;
                                        case 8:
                                            echo 'QA Approved';
                                            break;
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if ($eachpagesDetails['type'] == '4') { ?>
                                        <div class="col-md-2 col-sm-2 form-group pull-right">
                                            <form class="form-horizontal" action="<?php echo base_url('Assessment/trainings/review_quiz') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="type" value="<?php echo $eachpagesDetails['type'] ?>">
                                                <input type="hidden" name="scourse_id" value="<?php echo $eachpagesDetails['fk_course_id']; ?>">
                                                <input type="hidden" name="page_id" value="<?php echo $eachpagesDetails['page_id'] ?>">
                                                <input type="hidden" name="course_name" value="<?php //echo $pagerow['course_name']; 
                                                                                                ?>">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-outline-primary  btn-xs rounded-pill waves-effect waves-light">Review</button>
                                                </div>
                                            </form>
                                        </div>
                                    <?php } ?>
                                </td>
                                <?php if (in_array('46', $arrayuserlevel) || in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel)) { 
                                ?>
                                    <td>
                                        <?php if ($eachpagesDetails['type'] == 5 || $eachpagesDetails['type'] == 6) {
                                            $key = array_search($eachpagesDetails['page_id'], array_column($questiondata, 'page_id'));
                                            if (!empty($key) || $key === 0) { ?>
                                                <form class="form-horizontal" action="<?php echo base_url('Assessment/trainings/edit_quetion_view') ?>" method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="question_id" value="<?php echo $questiondata[$key]['question_id']; ?>">
                                                    <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
                                                    <input type="hidden" name="type" value="<?php echo $eachpagesDetails['type']; ?>">
                                                    <input type="hidden" name="page_id" value="<?php echo $eachpagesDetails['page_id'] ?>">
                                                    <input type="hidden" name="page_number" value="<?php echo $eachpagesDetails['page_number'] ?>">
                                                    <input type="hidden" name="page_name" value="<?php echo $eachpagesDetails['page_name'] ?>">
                                                    <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-script-text-outline"></span></button>
                                                </form>
                                            <?php
                                            }
                                        } elseif ($eachpagesDetails['type'] == 4) {
                                            // $key = array_search($eachpagesDetails['page_id'], array_column($questiondata, 'page_id'));
                                            // if (!empty($key) || $key === 0) { 
                                            ?>
                                            <form class="form-horizontal" action="<?php echo base_url('Assessment/trainings/question_list_view') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="question_id" value="<?php // echo $questiondata[$key]['question_id']; 
                                                                                                ?>">
                                                <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
                                                <input type="hidden" name="type" value="<?php echo $eachpagesDetails['type']; ?>">
                                                <input type="hidden" name="page_id" value="<?php echo $eachpagesDetails['page_id'] ?>">
                                                <input type="hidden" name="page_number" value="<?php echo $eachpagesDetails['page_number'] ?>">
                                                <input type="hidden" name="page_name" value="<?php echo $eachpagesDetails['page_name'] ?>">
                                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-script-text-outline"></span></button>
                                            </form>
                                        <?php
                                            // }
                                        } else { ?>
                                            <form class="form-horizontal" action="<?php echo base_url('SCORM/course_builder/scorm_course_pages/page_edit_view') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="page_id" value="<?php echo $eachpagesDetails['page_id'] ?>">
                                                <input type="hidden" name="page_number" value="<?php echo $eachpagesDetails['page_number'] ?>">
                                                <input type="hidden" name="page_name" value="<?php echo $eachpagesDetails['page_name'] ?>">
                                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-script-text-outline"></span></button>
                                            </form>
                                        <?php } ?>
                                    </td>
                                <?php } ?>
                                <?php if (in_array('46', $arrayuserlevel) || in_array('67', $arrayuserlevel) || in_array('4', $arrayuserlevel) || in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel)) { // Developer ,QA,PM
                                ?>

                                    <?php if ($courseDetails[0]['type'] == 11) {
                                        if ($eachpagesDetails['type'] != 4) { ?>
                                            <?php if ($eachpagesDetails['type'] == 5 || $eachpagesDetails['type'] == 6 || $eachpagesDetails['type'] == 4) {
                                            } else { ?>
                                                <td>
                                                    <form class="form-horizontal" action="<?php echo base_url('SCORM/course_builder/Editor') ?>" method="POST"><?= csrf_field() ?>
                                                        <input type="hidden" name="page_id" value="<?php echo $eachpagesDetails['page_id'] ?>">
                                                        <input type="hidden" name="page_number" value="<?php echo $eachpagesDetails['page_number'] ?>">
                                                        <input type="hidden" name="page_name" value="<?php echo $eachpagesDetails['page_name'] ?>">
                                                        <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-square-edit-outline"></span></button>
                                                    </form>
                                                </td>
                                    <?php }
                                        }
                                    } ?>

                                <?php } ?>
                            </tr>

                        <?php
                            $j++;
                            /*  if ($pagesDetails) {
                                $nxt_page = $eachpagesDetails['page_number'] + 1;
                            } else {
                                $nxt_page = 1;
                            } */
                        } ?>
                    </tbody>
                </table>


            </div>
        </div>
    </div>
</div>
<?php
$pageidSubpageidArray = [
    "allPageIds" => ["0"],
    "allSubPageIds" => []
];

foreach ($pagesDetails as $item) {
    $pageId = (string)$item['page_id'];
    $subPageMain = (string)$item['sub_page_main'];

    // If this is a main page (sub_page_main == 0)
    if ($subPageMain == "0") {
        if (!in_array($pageId, $pageidSubpageidArray["allPageIds"])) {
            $pageidSubpageidArray["allPageIds"][] = trim($pageId);
        }

        if (!isset($pageidSubpageidArray["allSubPageIds"][$pageId])) {
            $pageidSubpageidArray["allSubPageIds"][$pageId] = ["0"];
        }
    } else {
        // This is a sub-page
        foreach ($pagesDetails as $mainPage) {
            if ((string)$mainPage["page_number"] == floor($item["page_number"])) {
                $mainPageId = (string)$mainPage["page_id"];
                if (!isset($pageidSubpageidArray["allSubPageIds"][$mainPageId])) {
                    $pageidSubpageidArray["allSubPageIds"][$mainPageId] = ["0"];
                }
                $pageidSubpageidArray["allSubPageIds"][$mainPageId][] = trim($pageId);
                break;
            }
        }
    }
}
?>
<div class="col-lg-12">
    <div class="card">
        <div class="card-body"> <?php
                                // Output the result as JSON
                                echo 'pageidSubpageidArray = ' . json_encode($pageidSubpageidArray) . ';';
                                ?>
        </div>
    </div>
</div>
</div>