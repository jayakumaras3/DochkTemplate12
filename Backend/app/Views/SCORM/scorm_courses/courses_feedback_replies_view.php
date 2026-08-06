<?php $userlevel = session()->get('userlevel');
$arrayuserlevel = array_map('intval', explode(',', $userlevel));
?>
<div class="row">
    <div class="col-md-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">

                    <?php if ($typeofpage == 2) { ?>
                        <li class="breadcrumb-item"><a
                                href="<?php echo base_url('SCORM/Course_builder/review_course/showfeedback') ?>">Feedback</a></li>
                    <?php } ?>
                    <?php if ($typeofpage == 1) { ?>
                       <li class="breadcrumb-item"><a
                            href="<?php echo base_url('SCORM/course_builder/Editor') ?>">Pages</a></li>

                    <?php } ?>

                </ol>
            </div>
            <h4 class="page-title">Feedback Details (ID <?php echo $feedback_details[0]['feedbackid']; ?>)</h4>
        </div>
    </div>
</div>
<?php
if ($typeofpage == 2) {
    ?>
    <div class="row mb-2">
        <div class="col-md-5">
            <?php if ($getPrevPage) { ?>
                <form class="form-horizontal"
                    action="<?php echo base_url('SCORM/Course_builder/review_course/showfeedbackReplies') ?>" method="POST"><?= csrf_field() ?>
                    <input type="hidden" name="feedbackid" value="<?php echo $getPrevPage[0]['feedbackid']; ?>">
                    <input type="hidden" name="typeofpage" value="2">
                    <button type="submit" alt="Next" class="" style="all: unset; cursor: pointer;"><i
                            class="mdi mdi-arrow-left-circle-outline font-22"></i></button>
                </form>
            <?php } ?>
        </div>
        <?php if (session()->get('client') == '1') { ?>
            <!-- <div class="col-md-2" style="text-align: center;">
                <a class="nav-link waves-effect waves-light" data-bs-toggle="offcanvas" href="#theme-settings-offcanvas">
                    <i class="mdi mdi-clipboard-edit-outline font-22"></i>
                </a>
            </div> -->
            <div class="col-md-3">
                <form class="form-horizontal" action="<?php echo base_url('Course_builder/review_course/deleteFeedback') ?>"
                    method="POST"><?= csrf_field() ?>
                    <input type="hidden" name="feedbackid" value="<?php echo $feedback_details[0]['feedbackid'] ?>">
                    <button class="btn btn-outline-danger waves-effect btn-sm waves-light mb-3"
                        onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')">Delete Feedback</button>
                </form>
            </div>
        <?php } ?>

        <div class="col-md-4" style="text-align: right;">

            <?php if ($getNextPage) { ?>
                <form class="form-horizontal"
                    action="<?php echo base_url('SCORM/Course_builder/review_course/showfeedbackReplies') ?>" method="POST"><?= csrf_field() ?>
                    <input type="hidden" name="feedbackid" value="<?php echo $getNextPage[0]['feedbackid']; ?>">
                    <input type="hidden" name="typeofpage" value="2">
                    <button type="submit" alt="Next" style="all: unset; cursor: pointer;"><i
                            class="mdi mdi-arrow-right-circle-outline font-22"></i></button>
                </form>
            <?php } ?>
        </div>
    </div>
    <?php
}

?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <?php
                echo '<div class="row mb-1"><div class="col-md-3"><h5>Page</h5>';
                echo $getPageNameByPageID[0]['page_name'];
                echo '</div><div class="col-md-3"><h5>Stage</h5>';
                $stage = $feedback_details[0]['stage'];
                switch ($stage) {
                    case 0:
                        echo '-';
                        break;
                    case 3:
                        echo 'Alpha Feedback';
                        break;
                    case 4:
                        echo 'Alpha 2 Feedback';
                        break;
                    case 5:
                        echo 'Beta Feedback';
                        break;
                    case 6:
                        echo 'Beta 2 Feedback';
                        break;
                    case 7:
                        echo 'Gamma Feedback';
                        break;
                    case 8:
                        echo 'Gamma 2 Feedback';
                        break;
                }
                echo '</div><div class="col-md-3"><h5>Status</h5>';
                $status = $feedback_details[0]['status'];
                switch ($status) {
                    case 1:
                        echo 'New';
                        break;
                    case 2:
                        echo 'Replied';
                        break;
                    case 3:
                        echo 'Fixed';
                        break;
                    case 4:
                        echo 'QA Ver';
                        break;
                    case 5:
                        echo 'ReOpen';
                        break;
                    case 6:
                        echo 'Closed';
                        break;
                }
                echo '</div><div class="col-md-3"><h5>Created By</h5>';
                echo $feedback_details[0]['fname'];
                echo '</div><div class="col-md-3"><h5>Created On</h5>';
                echo date('m/d', $feedback_details[0]['createdon']);

                echo '</div>';
                echo '<div class="col-md-3"><h5>Comment Type</h5>';
                $comment_type = $feedback_details[0]['comment_type'];
                switch ($comment_type) {
                    case 1:
                        echo "Change";
                        break;
                    case 2:
                        echo "Defect";
                        break;
                    case 3:
                        echo "Question";
                        break;
                    case 4:
                        echo "Suggestion";
                        break;
                }
                echo '</div><div class="col-md-3"><h5>Severity</h5>';

                $serverity = $feedback_details[0]['serverity'];
                switch ($serverity) {
                    case 1:
                        echo "Low";
                        break;
                    case 2:
                        echo "Medium";
                        break;
                    case 3:
                        echo "High";
                        break;
                }
                echo '</div><div class="col-md-3"><h5>Category</h5>';

                $comment_category = $feedback_details[0]['comment_category'];
                switch ($comment_category) {
                    case 1:
                        echo "Visual";
                        break;
                    case 2:
                        echo "Audio";
                        break;
                    case 3:
                        echo "Content";
                        break;
                    case 4:
                        echo "Functionaliy";
                        break;
                    case 5:
                        echo "Global";
                        break;
                }

                echo '</div>';
                echo '<div class="col-md-6"><h5>Feedback</h5>';
                echo $feedback_details[0]['feedback'];
                echo '</div>';
                echo '<div class="col-md-6">';
                if ($feedback_details[0]['uploaded_file']) {
                    echo '<img class="img-fluid img-thumbnail" src="' . base_url('assets/assets/uploads/feedback_attachment/' . $feedback_details[0]['course_id'] . '/' . $feedback_details[0]['uploaded_file']) . '">';
                }
                if ($feedback_details[0]['attchment']) {
                    echo '<img class="img-fluid img-thumbnail" src="' . base_url('assets/assets/uploads/feedback_attachment/' . $feedback_details[0]['course_id'] . '/' . $feedback_details[0]['attchment']) . '">';
                }

                echo '</div></div>';
                ?>

            </div>
        </div>
    </div>
</div>
<div class="row">
    <?php if ($status == 3) { ?>
        <?php if (in_array('67', $arrayuserlevel) || in_array('46', $arrayuserlevel)) { ?>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <form class="form-horizontal" action="<?php echo base_url('SCORM/Course_builder/review_course/qaVerified') ?>"
                            method="POST"><?= csrf_field() ?>
                            <div class="row mb-1">
                                <input type="hidden" name="feedbackid" value="<?php echo $feedbackid; ?>">
                                <input type="hidden" name="typeofpage" value="1">
                                <button class="btn btn-outline-danger btn-xs waves-effect waves-light">QA Verified</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php }
    } ?>
    <?php if ($status == 1 || $status == 5) { ?>
        <?php if (in_array('46', $arrayuserlevel) || in_array('5', $arrayuserlevel)) { ?>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <form class="form-horizontal"
                            action="<?php echo base_url('SCORM/Course_builder/review_course/addfeedbackReplies') ?>" method="POST"><?= csrf_field() ?>
                            <div class="row mb-1">
                                <input type="text" class="form-control" name="feedbackReply" value="">
                            </div>
                            <div class="row mb-1">
                                <input type="hidden" name="course_id" value="<?php echo $feedback_details[0]['course_id'] ?>">
                                <input type="hidden" name="feedbackid" value="<?php echo $feedbackid; ?>">
                                <input type="hidden" name="typeofpage" value="1">
                                <input type="hidden" name="status" value="3">
                                <button class="btn btn-outline-warning btn-xs waves-effect waves-light">Developer
                                    Comment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <form class="form-horizontal"
                            action="<?php echo base_url('SCORM/Course_builder/review_course/addfeedbackReplies') ?>" method="POST"><?= csrf_field() ?>
                            <div class="row mb-1">
                                <input type="hidden" class="form-control" name="feedbackReply" value="Fixed.">
                                <input type="hidden" name="course_id" value="<?php echo $feedback_details[0]['course_id'] ?>">
                                <input type="hidden" name="feedbackid" value="<?php echo $feedbackid; ?>">
                                <input type="hidden" name="typeofpage" value="1">
                                <input type="hidden" name="status" value="3">
                                <button class="btn btn-outline-success btn-xs waves-effect waves-light">Fixed</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php }
    } ?>

    <?php if (in_array('45', $arrayuserlevel)) { ?>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal"
                        action="<?php echo base_url('SCORM/Course_builder/review_course/addfeedbackReplies') ?>" method="POST"><?= csrf_field() ?>
                        <div class="row mb-1">
                            <input type="text" class="form-control" name="feedbackReply" value="">
                        </div>
                        <div class="row mb-1">
                            <input type="hidden" name="course_id" value="<?php echo $feedback_details[0]['course_id'] ?>">

                            <input type="hidden" name="feedbackid" value="<?php echo $feedbackid; ?>">
                            <input type="hidden" name="typeofpage" value="<?php echo $typeofpage; ?>">
                            <input type="hidden" name="status" value="2">
                            <button class="btn btn-outline-info btn-xs waves-effect waves-light">Reply</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php }
    ?>

    <?php if (!empty($feedback_replies)): ?>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <table class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th class="center">#</th>
                                <th>Replies</th>
                                <th>Creator</th>
                                <th>On</th>
                                <th>Delete</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                            foreach ($feedback_replies as $feedback_details) {

                                $j = $j + 1; ?>
                                <tr>
                                    <td class="center"><?php echo $j ?></td>
                                    <td><?php echo $feedback_details['feedback'] ?></td>
                                    <td><?php echo $feedback_details['fname'] ?></td>
                                    <td><?php echo date('m/d', $feedback_details['createdon']); ?></td>
                                    <td>
                                        <?php if ($current_user == $feedback_details['createdby']) { ?>
                                            <form class="form-horizontal"
                                                action="<?php echo base_url('SCORM/Course_builder/review_course/delReply') ?>"
                                                method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="feedbackreplyid"
                                                    value="<?php echo $feedback_details['feedbackreplyid'] ?>">
                                                <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><i class="mdi mdi-trash-can-outline"></i></button>
                                            </form>
                                        <?php } ?>

                                    </td>
                                    <!-- <td></td> -->
                                    <?php
                            } ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>