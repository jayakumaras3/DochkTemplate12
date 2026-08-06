<?php if (isset($pageIDValidation)) {
    echo $pageIDValidation;
    exit();
} ?>
<?php
function isArabic($text)
{
    return preg_match('/\p{Arabic}/u', $text);
}
?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.js"></script>
<?php $client = session()->get('client'); ?>


<ul class="nav nav-tabs">
    <li class="<?php if ($tab == 1)
        echo "active"; ?>"><a data-toggle="tab" href="#newfeedback" <?php if ($tab == 1)
              echo "active"; ?>>New</a></li>
    <li class="<?php if ($tab == 2)
        echo "active"; ?>"><a data-toggle="tab" href="#myfeedback" <?php if ($tab == 2)
              echo "active"; ?>>My</a></li>
    <li class="<?php if ($tab == 3)
        echo "active"; ?>"><a data-toggle="tab" href="#allfeedback" <?php if ($tab == 3)
              echo "active"; ?>>All</a></li>
    <li class="<?php if ($tab == 4)
        echo "active"; ?>"><a data-toggle="tab" href="#closedfeedback" <?php if ($tab == 4)
              echo "active"; ?>>Closed</a>
    </li>
</ul>
<style>
    .custom-row {
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }

    .custom-col {
        flex: 1;
        min-width: 150px;
    }

    .form-control {
        width: 100%;
    }
</style>
<div style="margin: 15px" ;>

    <div class="tab-content">
        <div id="newfeedback" class="tab-pane fade <?php if ($tab == 1)
            echo "in active"; ?>">
            <div class="feedback_form">
                <form class="feedback_inside_form" method="POST"
                    action="<?php echo base_url('SCORM/Course_builder/review_course/addNewfeedback') ?>"
                    enctype="multipart/form-data" id="addNewfeedback" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea class="ckeditor" name="feedback_value" required></textarea>
                            </div>
                            <div class="form-group custom-row">
                                <div class="custom-col">
                                    <select name="comment_type" id="comment_type" class="form-control">

                                        <option value="1" SELECTED>Change</option>
                                        <option value="2">Defect</option>
                                        <option value="3">Question</option>
                                        <option value="4">Suggestion</option>
                                    </select>
                                </div>

                                <div class="custom-col">
                                    <select name="serverity" id="serverity" class="form-control">

                                        <option value="1" SELECTED>Low</option>
                                        <option value="2">Medium</option>
                                        <option value="3">High</option>
                                    </select>
                                </div>

                                <div class="custom-col">
                                    <select name="comment_category" id="comment_category" class="form-control">

                                        <option value="1" SELECTED>Visuals</option>
                                        <option value="2">Audio</option>
                                        <option value="3">Content</option>
                                        <option value="4">Functionality</option>
                                        <option value="5">Global</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <input type="file" name="file" accept=".png,.jpg,.jpeg" />
                            </div>

                            <input type="hidden" name="img_val" id="img_val" value="" />

                            <!-- <p style="font-size:10px">File type : png,jpg and jpeg </p> -->
                            <input type="hidden" id="course_id" name="course_id" value="<?php echo $course_id; ?>" />
                            <input type="hidden" id="page_id" name="page_id" value="<?php echo $page_id; ?>" />
                            <?php if ($client == 1) { ?>
                                <input type="hidden" id="type" name="type" value="1" />
                            <?php } else { ?>
                                <input type="hidden" id="type" name="type" value="2" />
                            <?php } ?>
                            <input type="hidden" id="videotime" name="videotime" value="0" />
                            <input type="hidden" id="tab" name="tab" value="2" />
                            <span id="subminmsg" style="display: none; color: red; padding: 5px;">Saving...</span>
                            <button type="submit" style=" padding: 5px;" id="submain" class="btn btn-sm btn-primary"
                                onclick="capture();" disabled>Add New Feedback</button>
                            <!-- <button type="submit" style=" padding: 5px;" id="submain" class="btn btn-sm btn-primary" onclick="capture();">Add New Feedback</button> -->
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <div id="myfeedback" class="tab-pane fade <?php if ($tab == 2)
            echo "in active"; ?>">
            <?php $j = 0;
            $user_id = session()->get('id_user');
            foreach ($feedbacks as $feedback_details) {
                if ($user_id == $feedback_details['createdby'] && $feedback_details['status'] != 6) {
                    $j = $j + 1; ?>
                    <div class="individual_feedback_design" style="position: relative; width: 100%;">
                        <?php if (!empty($feedback_details['profile_image']) && !empty($feedback_details['profile_foldername'])) { ?>
                            <img class="img_circle"
                                src="<?php echo (base_url() . 'assets/assets/uploads/profile/' . $feedback_details['id_user'] . '/' . $feedback_details['profile_foldername'] . '/' . $feedback_details['profile_image']) ?>"
                                class="rounded-circle" alt="Profile" width="50" height="50">
                        <?php } else { ?>
                            <img class="img_circle" src="<?php echo base_url('public/aristo_assets/images/User_2_1.svg') ?>"
                                class="rounded-circle" alt="Profile" width="50" height="50">

                        <?php } ?>
                        <strong><?php echo $feedback_details['fname'] ?></strong>
                        <span
                            style="font-size:10px"><?php echo '(' . date("m/d", $feedback_details['last_updated_on']) . ')' ?></span>
                        <!-- <span class="feedback-arrow" style="position: absolute; top: 0; right: 0; font-size: 18px; cursor: pointer;">&#xFE19;</span> -->
                        <form class="Closed_status" method="POST" style="display: inline-block;">
                            <input type="hidden" name="course_id" value="<?php echo $course_id; ?>" />
                            <input type="hidden" name="feedbackid" value="<?php echo $feedback_details['feedbackid']; ?>" />
                            <input type="hidden" name="status" value="6" />
                            <input type="hidden" id="tab" name="tab" value="4" />
                            <button type="submit" class="btn btn-sm btn-success" title="close" style="font-size: 10px;">
                                <!-- <i class="fa fa-close" style="color: grey; font-size: 15px;"></i> -->Close feedback
                            </button>
                        </form>

                        <div class="dropdown" style="float:right;">

                            <button class="dropbtn"><span class="feedback-arrow"
                                    style="font-size: 10px; cursor: pointer;">&#xFE19;</span></button>
                            <div class="dropdown-content">
                                <form class="delete_feedback" method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="course_id" value="<?php echo $course_id; ?>" />
                                    <input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
                                    <input type="hidden" name="feedbackid"
                                        value="<?php echo $feedback_details['feedbackid']; ?>" />
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        style="width: 100%; padding: 10px 5px; border: none;text-align: center; cursor: pointer;">
                                        Delete
                                    </button>
                                </form>

                            </div>
                        </div>
                        <?php if (strlen($feedback_details['uploaded_file'] ?? '') > 2) {
                        } else { ?>
                            <div class="feedback_inside_form" style="float:right;"> <button
                                    class="btn btn-outline-warning  btn-xs rounded-pill waves-effect waves-light"
                                    data-toggle="modal" data-target="#myModal2" title="Upload">
                                    <i class="fa fa-paperclip"></i>
                                </button>
                            </div>
                        <?php } ?>
                        <?php if (strlen($feedback_details['uploaded_file'] ?? '') > 2) { ?>

                            <!-- <div class="feedback_inside_form" style="float:right;"> <a href="<?php echo base_url('assets/assets/uploads/feedback_attachment/' . $course_id . '/' . $feedback_details['uploaded_file']) ?>" class="btn btn-outline-warning  btn-xs rounded-pill waves-effect waves-light" target="_blank" style="background: none; border: none; padding: 0; cursor: pointer;color:black" title="View Upload file">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </div> -->
                            <div class="feedback_inside_form" style="float:right;">
                                <a href="javascript:void(0);"
                                    class="btn btn-outline-warning btn-xs rounded-pill waves-effect waves-light"
                                    style="background: none; border: none; padding: 0; cursor: pointer; color:black"
                                    title="View Upload file"
                                    onclick="openPopup('<?php echo base_url('assets/assets/uploads/feedback_attachment/' . $course_id . '/' . $feedback_details['uploaded_file']) ?>')">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </div>
                        <?php } ?>
                        <?php if (strlen($feedback_details['attchment'] ?? '') > 2) { ?>
                            <div class="feedback_inside_form" style="float:right;">
                                <a href="javascript:void(0);"
                                    class="btn btn-outline-warning btn-xs rounded-pill waves-effect waves-light"
                                    style="background: none; border: none; padding: 0; cursor: pointer; color:black"
                                    title="View Upload file"
                                    onclick="openPopup('<?php echo base_url('assets/assets/uploads/feedback_attachment/' . $course_id . '/' . $feedback_details['attchment']) ?>')">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </div>
                            <!-- <div class="feedback_inside_form" style="float:right;"> <a href="<?php echo base_url('assets/assets/uploads/feedback_attachment/' . $course_id . '/' . $feedback_details['attchment']) ?>" class="btn btn-outline-warning  btn-xs rounded-pill waves-effect waves-light" target="_blank" style="background: none; border: none; padding: 0; cursor: pointer;color:black" title="View Screenshot">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </div> -->
                        <?php } ?>

                        <!-- Modal -->
                        <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <h4 class="modal-title" id="myModalLabel2">Attachment</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-row">
                                            <p style="font-size:10px">File type : png,jpg and jpeg </p>
                                            <form class="form-horizontal" enctype="multipart/form-data" id="uploadmyfile"
                                                method="POST"><?= csrf_field() ?>
                                                <div class="form-group col-md-12 mb-3">
                                                    <input type="file" name="file" accept=".png,.jpg,.jpeg" required />
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <input type="hidden" id="course_id" name="course_id"
                                                        value="<?php echo $course_id; ?>" />
                                                    <input type="hidden" id="page_id" name="page_id"
                                                        value="<?php echo $page_id; ?>" />
                                                    <input type="hidden" name="feedbackid"
                                                        value="<?php echo $feedback_details['feedbackid']; ?>" />
                                                    <button type="submit"
                                                        class="btn btn-info btn-sm form-control">Upload</button>
                                                </div>
                                                <?php if (isset($logovalidation)): ?>
                                                    <div class="form-group col-md-12">
                                                        <div class="alert alert-danger" role="alert">
                                                            <?= $logovalidation->listErrors() ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <br>
                        <div class="feedback_details" dir="<?= isArabic($feedback_details['feedback']) ? 'rtl' : 'ltr' ?>">
                            <?php if ($row['course_type'] == '11') { ?>
                                <a href="javascript:parent.goToSession(<?= esc($feedback_details['videotime']) ?>)">
                                    <?php
                                    $init = intval($feedback_details['videotime']);
                                    if ($init > 0) {
                                        $minutes = floor(($init / 60) % 60);
                                        $seconds = $init % 60;
                                        echo "$minutes:$seconds";
                                    }
                                    ?>
                                </a>
                            <?php } ?>
                            <?= strip_tags($feedback_details['feedback']) ?>
                        </div>

                        <?php if (isset($replies[$feedback_details['feedbackid']])) { ?>
                            <?php foreach ($replies[$feedback_details['feedbackid']] as $reply) { ?>
                                <?php if (!empty($reply['profile_image']) && !empty($reply['profile_foldername'])) { ?>
                                    <img class="img_circle_reply"
                                        src="<?php echo (base_url() . 'assets/assets/uploads/profile/' . $reply['id_user'] . '/' . $reply['profile_foldername'] . '/' . $reply['profile_image']) ?>"
                                        class="rounded-circle" alt="Profile" width="50" height="50">
                                <?php } else { ?>
                                    <img class="img_circle_reply" src="<?php echo base_url('public/aristo_assets/images/User_2_1.svg') ?>"
                                        class="rounded-circle" alt="Profile" width="50" height="50">
                                <?php } ?>
                                <strong><?php echo $reply['fname1'] ?></strong>
                                <span style="font-size:10px"><?php echo '(' . date("m/d", $reply['last_updated_on']) . ')' ?></span>
                                <!-- <span class="feedback-arrow" style="position: absolute; top: 0; right: 0; font-size: 18px; cursor: pointer;">&#xFE19;</span> -->
                                <div class="dropdown" style="float:right;">
                                    <button class="dropbtn"><span class="feedback-arrow"
                                            style="font-size: 10px; cursor: pointer;">&#xFE19;</span></button>
                                    <div class="dropdown-content">

                                        <form class="delete_reply" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="course_id" value="<?php echo $course_id; ?>" />
                                            <input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
                                            <input type="hidden" name="feedbackreplyid"
                                                value="<?php echo $reply['feedbackreplyid']; ?>" />
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                style="width: 100%; padding: 10px 5px; border: none;text-align: center; cursor: pointer;">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <br>
                                <div class="feedback_details_reply">
                                    R : <?php echo $reply['feedback_replies']; ?>
                                </div><br />
                            <?php }
                        } ?>
                        <div class="reply_form">
                            <form class="replay_inside_form" id="replyfeedback" method="POST"><?= csrf_field() ?>
                                <table style="width:100%">
                                    <tr>
                                        <td style=" padding: 2px;"><textarea name="feedback" class="form-control"
                                                placeholder="Reply" rows="1" cols="30" required></textarea></td>
                                        <td style=" padding: 2px;"><input type="hidden" name="course_id"
                                                value="<?php echo $course_id; ?>" />
                                            <input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
                                            <input type="hidden" name="feedbackid"
                                                value="<?php echo $feedback_details['feedbackid']; ?>" />
                                            <input type="hidden" name="status" value="2" />
                                            <input type="hidden" id="tab" name="tab" value="2" />
                                            <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-reply"
                                                    style="color: grey; font-size: 15px;"></i></button>
                                        </td>
                                    </tr>
                                </table>

                            </form>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
        <div id="allfeedback" class="tab-pane fade <?php if ($tab == 3)
            echo "in active"; ?>">
            <div class="feedback_view" id="result">
                <?php $j = 0;
                foreach ($feedbacks as $feedback_details) {
                    if ($feedback_details['status'] != 6) {
                        $j = $j + 1; ?>
                        <div class="individual_feedback_design" style="position: relative; width: 100%;">
                            <?php if (!empty($feedback_details['profile_image']) && !empty($feedback_details['profile_foldername'])) { ?>
                                <img class="img_circle"
                                    src="<?php echo (base_url() . 'assets/assets/uploads/profile/' . $feedback_details['id_user'] . '/' . $feedback_details['profile_foldername'] . '/' . $feedback_details['profile_image']) ?>"
                                    class="rounded-circle" alt="Profile" width="50" height="50">
                            <?php } else { ?>
                                <img class="img_circle" src="<?php echo base_url('public/aristo_assets/images/User_2_1.svg') ?>"
                                    class="rounded-circle" alt="Profile" width="50" height="50">
                            <?php } ?>
                            <strong><?php echo $feedback_details['fname'] ?></strong>
                            <span
                                style="font-size:10px"><?php echo '(' . date("m/d", $feedback_details['last_updated_on']) . ')' ?></span>
                            <!-- <span class="feedback-arrow" style="position: absolute; top: 0; right: 0; font-size: 18px; cursor: pointer;">&#xFE19;</span> -->
                            <form class="Closed_status" method="POST" style="display: inline-block;">
                                <input type="hidden" name="course_id" value="<?php echo $course_id; ?>" />
                                <input type="hidden" name="feedbackid" value="<?php echo $feedback_details['feedbackid']; ?>" />
                                <input type="hidden" name="status" value="6" />
                                <input type="hidden" id="tab" name="tab" value="4" />
                                <button type="submit" class="btn btn-sm btn-success" title="close" style="font-size: 10px;">
                                    <!-- <i class="fa fa-close" style="color: grey; font-size: 15px;"></i> -->
                                    Close feedback
                                </button>
                            </form>

                            <div class="dropdown" style="float:right;">

                                <button class="dropbtn"><span class="feedback-arrow"
                                        style="font-size: 10px; cursor: pointer;">&#xFE19;</span></button>
                                <div class="dropdown-content">
                                    <form class="delete_feedback" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="course_id" value="<?php echo $course_id; ?>" />
                                        <input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
                                        <input type="hidden" name="feedbackid"
                                            value="<?php echo $feedback_details['feedbackid']; ?>" />
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            style="width: 100%; padding: 10px 5px; border: none;text-align: center; cursor: pointer;">
                                            Delete
                                        </button>
                                    </form>

                                </div>
                            </div>
                            <!-- <div class="feedback_inside_form" style="float:right;"> <button class="btn btn-outline-warning  btn-xs rounded-pill waves-effect waves-light" data-toggle="modal" data-target="#myModal1">
                                <i class="fa fa-paperclip"></i>
                            </button>
                        </div> -->
                            <?php if (strlen($feedback_details['uploaded_file'] ?? '') > 2) { ?>
                                <!-- <div class="feedback_inside_form" style="float:right;"> <a href="<?php echo base_url('assets/assets/uploads/feedback_attachment/' . $course_id . '/' . $feedback_details['uploaded_file']) ?>" class="btn btn-outline-warning  btn-xs rounded-pill waves-effect waves-light" target="_blank" style="background: none; border: none; padding: 0; cursor: pointer;color:black" title="View Upload file">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </div> -->
                                <div class="feedback_inside_form" style="float:right;">
                                    <a href="javascript:void(0);"
                                        class="btn btn-outline-warning btn-xs rounded-pill waves-effect waves-light"
                                        style="background: none; border: none; padding: 0; cursor: pointer; color:black"
                                        title="View Upload file"
                                        onclick="openPopup('<?php echo base_url('assets/assets/uploads/feedback_attachment/' . $course_id . '/' . $feedback_details['uploaded_file']) ?>')">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if (strlen($feedback_details['attchment'] ?? '') > 2) { ?>
                                <!-- <div class="feedback_inside_form" style="float:right;"> <a href="<?php echo base_url('assets/assets/uploads/feedback_attachment/' . $course_id . '/' . $feedback_details['attchment']) ?>" class="btn btn-outline-warning  btn-xs rounded-pill waves-effect waves-light" target="_blank" style="background: none; border: none; padding: 0; cursor: pointer;color:black" title="View Screenshot">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </div> -->
                                <div class="feedback_inside_form" style="float:right;">
                                    <a href="javascript:void(0);"
                                        class="btn btn-outline-warning btn-xs rounded-pill waves-effect waves-light"
                                        style="background: none; border: none; padding: 0; cursor: pointer; color:black"
                                        title="View Upload file"
                                        onclick="openPopup('<?php echo base_url('assets/assets/uploads/feedback_attachment/' . $course_id . '/' . $feedback_details['attchment']) ?>')">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </div>
                            <?php } ?>
                            <!-- Modal -->
                            <!-- <div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <h4 class="modal-title" id="myModalLabel1">Attachment</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-row">
                                            <form class="form-horizontal" enctype="multipart/form-data" id="uploadfile1" method="POST"><?= csrf_field() ?>
                                                <div class="form-group col-md-12 mb-3">
                                                    <input type="file" name="file" required />
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <input type="hidden" id="course_id" name="course_id" value="<?php echo $course_id; ?>" />
                                                    <input type="hidden" id="page_id" name="page_id" value="<?php echo $page_id; ?>" />
                                                    <input type="hidden" name="feedbackid" value="<?php echo $feedback_details['feedbackid']; ?>" />
                                                    <button type="submit" class="btn btn-info btn-sm form-control">Upload</button>
                                                </div>
                                                <?php if (isset($logovalidation)): ?>
                                                    <div class="form-group col-md-12">
                                                        <div class="alert alert-danger" role="alert">
                                                            <?= $logovalidation->listErrors() ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div> -->

                            <br>
                            <div class="feedback_details" dir="<?= isArabic($feedback_details['feedback']) ? 'rtl' : 'ltr' ?>">
                                <a href="javascript:parent.goToSession(<?php echo $feedback_details['videotime']; ?>)">
                                    <?php
                                    $init = intval($feedback_details['videotime']);
                                    if ($init > 0) {
                                        $hours = floor($init / 3600);
                                        $minutes = floor(($init / 60) % 60);
                                        $seconds = $init % 60;
                                        echo "$minutes:$seconds";
                                    }
                                    ?></a>
                                <?php echo strip_tags($feedback_details['feedback']); ?>
                            </div><br />
                            <?php if (isset($replies[$feedback_details['feedbackid']])) { ?>
                                <?php foreach ($replies[$feedback_details['feedbackid']] as $reply) { ?>
                                    <?php if (!empty($reply['profile_image']) && !empty($reply['profile_foldername'])) { ?>
                                        <img class="img_circle_reply"
                                            src="<?php echo (base_url() . 'assets/assets/uploads/profile/' . $reply['id_user'] . '/' . $reply['profile_foldername'] . '/' . $reply['profile_image']) ?>"
                                            class="rounded-circle" alt="Profile" width="50" height="50">
                                    <?php } else { ?>
                                        <img class="img_circle_reply" src="<?php echo base_url('public/aristo_assets/images/User_2_1.svg') ?>"
                                            class="rounded-circle" alt="Profile" width="50" height="50">
                                    <?php } ?>
                                    <strong><?php echo $reply['fname1'] ?></strong>
                                    <span style="font-size:10px"><?php echo '(' . date("m/d", $reply['last_updated_on']) . ')' ?></span>
                                    <!-- <span class="feedback-arrow" style="position: absolute; top: 0; right: 0; font-size: 18px; cursor: pointer;">&#xFE19;</span> -->
                                    <div class="dropdown" style="float:right;">
                                        <button class="dropbtn"><span class="feedback-arrow"
                                                style="font-size: 10px; cursor: pointer;">&#xFE19;</span></button>
                                        <div class="dropdown-content">

                                            <form class="delete_reply" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="course_id" value="<?php echo $course_id; ?>" />
                                                <input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
                                                <input type="hidden" name="feedbackreplyid"
                                                    value="<?php echo $reply['feedbackreplyid']; ?>" />
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    style="width: 100%; padding: 10px 5px; border: none;text-align: center; cursor: pointer;">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="feedback_details_reply">
                                        R : <?php echo $reply['feedback_replies']; ?>
                                    </div><br />
                                <?php }
                            } ?>
                            <div class="reply_form">
                                <form class="replay_inside_form" id="replyfeedback" method="POST"><?= csrf_field() ?>
                                    <table style="width:100%">
                                        <tr>
                                            <td style=" padding: 2px;"><textarea name="feedback" class="form-control"
                                                    placeholder="Reply" rows="1" cols="30" required></textarea></td>
                                            <td style=" padding: 2px;"><input type="hidden" name="course_id"
                                                    value="<?php echo $course_id; ?>" />
                                                <input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
                                                <input type="hidden" name="feedbackid"
                                                    value="<?php echo $feedback_details['feedbackid']; ?>" />
                                                <input type="hidden" name="status" value="2" />
                                                <input type="hidden" id="tab" name="tab" value="3" />
                                                <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-reply"
                                                        style="color: grey; font-size: 15px;"></i></button>
                                            </td>
                                        </tr>
                                    </table>

                                </form>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>

        <div id="closedfeedback" class="tab-pane fade <?php if ($tab == 4)
            echo "in active"; ?>">
            <?php $j = 0;
            $user_id = session()->get('id_user');
            foreach ($feedbacks as $feedback_details) {
                if ($feedback_details['createdby'] == $user_id && $feedback_details['status'] == 6) {
                    $j = $j + 1; ?>
                    <div class="individual_feedback_design" style="position: relative; width: 100%;">
                        <?php if (!empty($feedback_details['profile_image']) && !empty($feedback_details['profile_foldername'])) { ?>
                            <img class="img_circle"
                                src="<?php echo (base_url() . 'assets/assets/uploads/profile/' . $feedback_details['id_user'] . '/' . $feedback_details['profile_foldername'] . '/' . $feedback_details['profile_image']) ?>"
                                class="rounded-circle" alt="Profile" width="50" height="50">
                        <?php } else { ?>
                            <img class="img_circle" src="<?php echo base_url('public/aristo_assets/images/User_2_1.svg') ?>"
                                class="rounded-circle" alt="Profile" width="50" height="50">
                        <?php } ?>
                        <strong><?php echo $feedback_details['fname'] ?></strong>
                        <span
                            style="font-size:10px"><?php echo '(' . date("m/d", $feedback_details['last_updated_on']) . ')' ?></span>
                        <!-- <span class="feedback-arrow" style="position: absolute; top: 0; right: 0; font-size: 18px; cursor: pointer;">&#xFE19;</span> -->
                        <form class="Closed_status" method="POST" style="display: inline-block;">
                            <input type="hidden" name="course_id" value="<?php echo $course_id; ?>" />
                            <input type="hidden" name="feedbackid" value="<?php echo $feedback_details['feedbackid']; ?>" />
                            <input type="hidden" name="status" value="1" />
                            <input type="hidden" id="tab" name="tab" value="1" />
                            <button type="submit" class="btn btn-sm btn-success" title="Reopen" style="font-size: 10px;">
                                <!-- <i class="fa fa-reply" style="color: grey; font-size: 15px;"></i> -->
                                Re-open feedback
                            </button>
                        </form>


                        <div class="dropdown" style="float:right;">

                            <button class="dropbtn"><span class="feedback-arrow"
                                    style="font-size: 10px; cursor: pointer;">&#xFE19;</span></button>
                            <div class="dropdown-content">
                                <form class="delete_feedback" method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="course_id" value="<?php echo $course_id; ?>" />
                                    <input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
                                    <input type="hidden" name="feedbackid"
                                        value="<?php echo $feedback_details['feedbackid']; ?>" />
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        style="width: 100%; padding: 10px 5px; border: none;text-align: center; cursor: pointer;">
                                        Delete
                                    </button>
                                </form>

                            </div>
                        </div>
                        <div class="feedback_inside_form" style="float:right;"> <button
                                class="btn btn-outline-warning  btn-xs rounded-pill waves-effect waves-light"
                                data-toggle="modal" data-target="#myModal">
                                <i class="fa fa-paperclip"></i>
                            </button>
                        </div>
                        <?php if (strlen($feedback_details['uploaded_file'] ?? '') > 2) { ?>
                            <!-- <div class="feedback_inside_form" style="float:right;"> <a href="<?php echo base_url('assets/assets/uploads/feedback_attachment/' . $course_id . '/' . $feedback_details['uploaded_file']) ?>" class="btn btn-outline-warning  btn-xs rounded-pill waves-effect waves-light" target="_blank" style="background: none; border: none; padding: 0; cursor: pointer;color:black" title="View Upload file">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </div> -->
                            <div class="feedback_inside_form" style="float:right;">
                                <a href="javascript:void(0);"
                                    class="btn btn-outline-warning btn-xs rounded-pill waves-effect waves-light"
                                    style="background: none; border: none; padding: 0; cursor: pointer; color:black"
                                    title="View Upload file"
                                    onclick="openPopup('<?php echo base_url('assets/assets/uploads/feedback_attachment/' . $course_id . '/' . $feedback_details['uploaded_file']) ?>')">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </div>
                        <?php } ?>
                        <?php if (strlen($feedback_details['attchment'] ?? '') > 2) { ?>
                            <!-- <div class="feedback_inside_form" style="float:right;"> <a href="<?php echo base_url('assets/assets/uploads/feedback_attachment/' . $course_id . '/' . $feedback_details['attchment']) ?>" class="btn btn-outline-warning  btn-xs rounded-pill waves-effect waves-light" target="_blank" style="background: none; border: none; padding: 0; cursor: pointer;color:black" title="View Screenshot">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </div> -->
                            <div class="feedback_inside_form" style="float:right;">
                                <a href="javascript:void(0);"
                                    class="btn btn-outline-warning btn-xs rounded-pill waves-effect waves-light"
                                    style="background: none; border: none; padding: 0; cursor: pointer; color:black"
                                    title="View Upload file"
                                    onclick="openPopup('<?php echo base_url('assets/assets/uploads/feedback_attachment/' . $course_id . '/' . $feedback_details['attchment']) ?>')">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </div>
                        <?php } ?>
                        <!-- Modal -->
                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <h4 class="modal-title" id="myModalLabel">Attachment</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-row">
                                            <form class="form-horizontal" enctype="multipart/form-data" id="uploadfile"
                                                method="POST"><?= csrf_field() ?>
                                                <div class="form-group col-md-12 mb-3">
                                                    <input type="file" name="file" required />
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <input type="hidden" id="course_id" name="course_id"
                                                        value="<?php echo $course_id; ?>" />
                                                    <input type="hidden" id="page_id" name="page_id"
                                                        value="<?php echo $page_id; ?>" />
                                                    <input type="hidden" name="feedbackid"
                                                        value="<?php echo $feedback_details['feedbackid']; ?>" />
                                                    <button type="submit"
                                                        class="btn btn-info btn-sm form-control">Upload</button>
                                                </div>
                                                <?php if (isset($logovalidation)): ?>
                                                    <div class="form-group col-md-12">
                                                        <div class="alert alert-danger" role="alert">
                                                            <?= $logovalidation->listErrors() ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <br>
                        <div class="feedback_details" dir="<?= isArabic($feedback_details['feedback']) ? 'rtl' : 'ltr' ?>">
                            <a href="javascript:parent.goToSession(<?php echo $feedback_details['videotime']; ?>)">
                                <?php
                                $init = intval($feedback_details['videotime']);
                                if ($init > 0) {
                                    $hours = floor($init / 3600);
                                    $minutes = floor(($init / 60) % 60);
                                    $seconds = $init % 60;
                                    echo "$minutes:$seconds";
                                }
                                ?></a>
                            <?php echo strip_tags($feedback_details['feedback']); ?>
                        </div><br />
                        <?php if (isset($replies[$feedback_details['feedbackid']])) { ?>
                            <?php foreach ($replies[$feedback_details['feedbackid']] as $reply) { ?>
                                <?php if (!empty($reply['profile_image']) && !empty($reply['profile_foldername'])) { ?>
                                    <img class="img_circle_reply"
                                        src="<?php echo (base_url() . 'assets/assets/uploads/profile/' . $reply['id_user'] . '/' . $reply['profile_foldername'] . '/' . $reply['profile_image']) ?>"
                                        class="rounded-circle" alt="Profile" width="50" height="50">
                                <?php } else { ?>
                                    <img class="img_circle_reply" src="<?php echo base_url('public/aristo_assets/images/User_2_1.svg') ?>"
                                        class="rounded-circle" alt="Profile" width="50" height="50">
                                <?php } ?>
                                <strong><?php echo $reply['fname1'] ?></strong>
                                <span style="font-size:10px"><?php echo '(' . date("m/d", $reply['last_updated_on']) . ')' ?></span>
                                <!-- <span class="feedback-arrow" style="position: absolute; top: 0; right: 0; font-size: 18px; cursor: pointer;">&#xFE19;</span> -->
                                <div class="dropdown" style="float:right;">
                                    <button class="dropbtn"><span class="feedback-arrow"
                                            style="font-size: 10px; cursor: pointer;">&#xFE19;</span></button>
                                    <div class="dropdown-content">

                                        <form class="delete_reply" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="course_id" value="<?php echo $course_id; ?>" />
                                            <input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
                                            <input type="hidden" name="feedbackreplyid"
                                                value="<?php echo $reply['feedbackreplyid']; ?>" />
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                style="width: 100%; padding: 10px 5px; border: none;text-align: center; cursor: pointer;">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <br>
                                <div class="feedback_details_reply">
                                    R : <?php echo $reply['feedback_replies']; ?>
                                </div><br />
                            <?php }
                        } ?>
                        <div class="reply_form">
                            <form class="replay_inside_form" id="replyfeedback" method="POST"><?= csrf_field() ?>
                                <?php //print_r($feedback_details); 
                                        ?>
                                <table style="width:100%">
                                    <tr>
                                        <td style=" padding: 2px;"><textarea name="feedback" class="form-control"
                                                placeholder="Reply" rows="1" cols="30" required></textarea></td>
                                        <td style=" padding: 2px;"><input type="hidden" name="course_id"
                                                value="<?php echo $course_id; ?>" />
                                            <input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
                                            <input type="hidden" name="feedbackid"
                                                value="<?php echo $feedback_details['feedbackid']; ?>" />
                                            <input type="hidden" name="status" value="2" />
                                            <input type="hidden" id="tab" name="tab" value="3" />
                                            <!-- <input type="hidden" name="status" value="<?php echo $feedback_details['status']; ?>" /> -->
                                            <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-reply"
                                                    style="color: grey; font-size: 15px;"></i></button>
                                        </td>
                                    </tr>
                                </table>

                            </form>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>



</div>


<script>
    function capture() {

        document.getElementById('submain').style.display = 'none';
        document.getElementById('subminmsg').style.display = 'block';
        if (window.self === window.top) {
            console.log("Running in the PARENT page.");

        } else {
            console.log("Running inside the IFRAME (child page).");
            var textarea = document.querySelector('.ckeditor');
            var editorName = textarea.name;

            // Get the CKEditor instance by name and retrieve the value
            var editorValue = CKEDITOR.instances[editorName].getData();

            console.log(editorValue);
        }
        // var elmnt = null;
        <?php if ($row['course_type'] == '10') { ?>
            var iframe = window.parent.document.getElementById("target");

            // if (iframe) {
            elmnt = iframe.contentWindow.document.getElementById("slide-window");


            // }
        <?php } elseif ($row['course_type'] == '11') { ?>
            var elmnt = window.parent.document.getElementById("target");
            var vidDuration = parent.GetVideoTime();
            $('#videotime').val(vidDuration);
        <?php } ?>
        if (elmnt) {
            html2canvas(elmnt).then(function (canvas) {
                var anchorTag = document.createElement("a");
                $('#img_val').val(canvas.toDataURL("image/jpg"));
                document.getElementById("addNewfeedback").submit();
            });
        } else {
            document.getElementById("addNewfeedback").submit();
        }
    }


    $(document).ready(function () {
        $('#addNewfeedback').on('submit', function (event) {



            let form = document.getElementById('addNewfeedback');
            let submitButton = document.getElementById('submitButton');
            event.preventDefault();

            // Disable button and change its text
            submitButton.disabled = true;
            submitButton.innerHTML = 'Submitting...';
            // b     html2canvas(document.getElementById("target")).then(function(canvas) {
            //      var anchorTag = document.createElement("a");
            //     $('#img_val').val(canvas.toDataURL("image/jpg"));
            //document.getElementById("addNewfeedback").submit();
            //   });
            // Get the CKEditor content
            var feedbackValue = CKEDITOR.instances.feedback_value.getData().trim();

            // Log the CKEditor content to verify
            console.log('CKEditor content:', feedbackValue);

            // Check if the CKEditor content is empty
            if (feedbackValue === '' || feedbackValue === '<p>&nbsp;</p>' || feedbackValue === '<p></p>') {
                // Display confirmation alert
                var confirmSubmit = confirm("It looks like you haven't filled out the feedback. Do you still want to submit the form?");

                if (!confirmSubmit) {
                    return; // If the user cancels, stop form submission
                } else {
                    // Proceed with empty submission if user confirms
                    console.log('User confirmed empty submission.');
                }
            }

            // Append the CKEditor content to FormData
            var dataString = new FormData($('#addNewfeedback')[0]);
            dataString.append("feedback_value", feedbackValue);

            console.log(dataString + " Vid Dur");

            // Check if FormData is supported
            if (typeof FormData !== 'undefined') {
                $.ajax({
                    url: '<?php echo base_url('SCORM/Course_builder/review_course/addNewfeedback') ?>',
                    type: "POST",
                    data: dataString,
                    async: false,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        var obj = JSON.parse(data);
                        console.log(obj);
                        if (obj.status === 'OK') {
                            console.log('Feedback submitted successfully');
                            location.reload(); // Reload the page after successful submission
                        } else {
                            alert('Error', 'Something Went Wrong! Please contact Site Admin!');
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
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
    $(document).on('submit', '.replay_inside_form', function (event) {
        event.preventDefault();

        var form = $(this); // This refers to the dynamically generated form that was submitted
        var dataString = new FormData(form[0]);

        if (typeof FormData !== 'undefined') {
            $.ajax({
                url: '<?php echo base_url('SCORM/Course_builder/review_course/addreplyfeedback') ?>',
                type: "POST",
                data: dataString,
                async: false,
                processData: false,
                contentType: false,
                success: function (data) {
                    var obj = JSON.parse(data);
                    console.log(obj);

                    if (obj.status === 'OK') {
                        // You can reload or update the DOM dynamically here if needed
                        location.reload(); // Uncomment if you want to reload the page
                        // console.log('Reply submitted successfully');
                    } else {
                        alert('Error: Something went wrong. Please contact Site Admin!');
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log('Request failed');
                }
            });
        } else {
            alert("Your browser doesn't support FormData API! Use IE 10 or above.");
        }
    });
</script>
<script>
    $(document).on('submit', '.delete_feedback', function (event) {
        event.preventDefault();

        var form = $(this); // This refers to the dynamically generated form that was submitted
        var dataString = new FormData(form[0]);

        if (typeof FormData !== 'undefined') {
            $.ajax({
                url: '<?php echo base_url('SCORM/Course_builder/review_course/delete_feedback') ?>',
                type: "POST",
                data: dataString,
                async: false,
                processData: false,
                contentType: false,
                success: function (data) {
                    var obj = JSON.parse(data);
                    console.log(obj);

                    if (obj.status === 'OK') {
                        // You can reload or update the DOM dynamically here if needed
                        location.reload(); // Uncomment if you want to reload the page
                        // console.log('Reply submitted successfully');
                    } else {
                        alert('Error: Something went wrong. Please contact Site Admin!');
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log('Request failed');
                }
            });
        } else {
            alert("Your browser doesn't support FormData API! Use IE 10 or above.");
        }
    });
</script>
<script>
    $(document).on('submit', '.delete_reply', function (event) {
        event.preventDefault();

        var form = $(this); // This refers to the dynamically generated form that was submitted
        var dataString = new FormData(form[0]);

        if (typeof FormData !== 'undefined') {
            $.ajax({
                url: '<?php echo base_url('SCORM/Course_builder/review_course/delete_reply') ?>',
                type: "POST",
                data: dataString,
                async: false,
                processData: false,
                contentType: false,
                success: function (data) {
                    var obj = JSON.parse(data);
                    console.log(obj);

                    if (obj.status === 'OK') {
                        // You can reload or update the DOM dynamically here if needed
                        location.reload(); // Uncomment if you want to reload the page
                        // console.log('Reply submitted successfully');
                    } else {
                        alert('Error: Something went wrong. Please contact Site Admin!');
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log('Request failed');
                }
            });
        } else {
            alert("Your browser doesn't support FormData API! Use IE 10 or above.");
        }
    });
</script>
<script>
    $(document).on('submit', '.Closed_status', function (event) {
        event.preventDefault();

        var form = $(this); // This refers to the dynamically generated form that was submitted
        var dataString = new FormData(form[0]);

        if (typeof FormData !== 'undefined') {
            $.ajax({
                url: '<?php echo base_url('SCORM/Course_builder/review_course/closed_status') ?>',
                type: "POST",
                data: dataString,
                async: false,
                processData: false,
                contentType: false,
                success: function (data) {
                    var obj = JSON.parse(data);
                    console.log(obj);

                    if (obj.status === 'OK') {
                        // You can reload or update the DOM dynamically here if needed
                        location.reload(); // Uncomment if you want to reload the page
                        // console.log('Reply submitted successfully');
                    } else {
                        alert('Error: Something went wrong. Please contact Site Admin!');
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log('Request failed');
                }
            });
        } else {
            alert("Your browser doesn't support FormData API! Use IE 10 or above.");
        }
    });
</script>
<script>
    $('.fa').show();

    $('#uploadfile').on('submit', function (event) {
        event.preventDefault();

        var dataString = new FormData($('#uploadfile')[0]);

        if (typeof FormData !== 'undefined') {

            $.ajax({
                url: '<?php echo base_url('SCORM/Course_builder/review_course/feedback_attachment') ?>',
                type: "POST",
                data: dataString,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    // Show progress bar
                    $(".progress").show();
                },
                success: function (data) {
                    // console.log('Server Response:', data);
                    $('.my_update_panel').html(data);
                    var obj = JSON.parse(data);

                    // console.log(obj);

                    if (obj.status === 'OK') {
                        $('#loading_spinner').hide();
                        // console.log('inside on condition');
                        location.reload();
                        alert('File Uploaded Successfully');
                    } else {
                        alert('error', 'Something Went Wrong! Please contact Site Admin!');
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    // console.log('request failed');
                },
                complete: function () {
                    // Hide progress bar after completion
                    $(".progress").hide();
                },
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (evt) {
                        // Update progress bar
                        if (evt.lengthComputable) {
                            var percentComplete = (evt.loaded / evt.total) * 100;
                            $(".progress-bar").width(percentComplete + '%');
                            $(".progress-bar").html(percentComplete.toFixed(2) + '%');
                        }
                    }, false);
                    return xhr;
                }
            });

        } else {
            message("Your Browser Don't support FormData API! Use IE 10 or Above!");
        }
    });
</script>
<script>
    $('.fa').show();

    $('#uploadfile1').on('submit', function (event) {
        event.preventDefault();

        var dataString = new FormData($('#uploadfile')[0]);

        if (typeof FormData !== 'undefined') {

            $.ajax({
                url: '<?php echo base_url('SCORM/Course_builder/review_course/feedback_attachment') ?>',
                type: "POST",
                data: dataString,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    // Show progress bar
                    $(".progress").show();
                },
                success: function (data) {
                    // console.log('Server Response:', data);
                    $('.my_update_panel').html(data);
                    var obj = JSON.parse(data);

                    // console.log(obj);

                    if (obj.status === 'OK') {
                        $('#loading_spinner').hide();
                        // console.log('inside on condition');
                        location.reload();
                        alert('File Uploaded Successfully');
                    } else {
                        alert('error', 'Something Went Wrong! Please contact Site Admin!');
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    // console.log('request failed');
                },
                complete: function () {
                    // Hide progress bar after completion
                    $(".progress").hide();
                },
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (evt) {
                        // Update progress bar
                        if (evt.lengthComputable) {
                            var percentComplete = (evt.loaded / evt.total) * 100;
                            $(".progress-bar").width(percentComplete + '%');
                            $(".progress-bar").html(percentComplete.toFixed(2) + '%');
                        }
                    }, false);
                    return xhr;
                }
            });

        } else {
            message("Your Browser Don't support FormData API! Use IE 10 or Above!");
        }
    });
</script>
<script>
    $('.fa').show();

    $('#uploadmyfile').on('submit', function (event) {
        event.preventDefault();

        var dataString = new FormData($('#uploadmyfile')[0]);

        if (typeof FormData !== 'undefined') {

            $.ajax({
                url: '<?php echo base_url('SCORM/Course_builder/review_course/feedback_attachment') ?>',
                type: "POST",
                data: dataString,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    // Show progress bar
                    $(".progress").show();
                },
                success: function (data) {
                    // console.log('Server Response:', data);
                    $('.my_update_panel').html(data);
                    var obj = JSON.parse(data);

                    // console.log(obj);

                    if (obj.status === 'OK') {
                        $('#loading_spinner').hide();
                        // console.log('inside on condition');
                        location.reload();
                        alert('File Uploaded Successfully');
                    } else {
                        alert('error', 'Something Went Wrong! Please contact Site Admin!');
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    // console.log('request failed');
                },
                complete: function () {
                    // Hide progress bar after completion
                    $(".progress").hide();
                },
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (evt) {
                        // Update progress bar
                        if (evt.lengthComputable) {
                            var percentComplete = (evt.loaded / evt.total) * 100;
                            $(".progress-bar").width(percentComplete + '%');
                            $(".progress-bar").html(percentComplete.toFixed(2) + '%');
                        }
                    }, false);
                    return xhr;
                }
            });

        } else {
            message("Your Browser Don't support FormData API! Use IE 10 or Above!");
        }
    });
</script>
<script>
    function openPopup(url) {
        window.open(url, 'popupWindow', 'width=800,height=600,scrollbars=yes,resizable=yes');
    }
</script>
<script>
    window.onload = function () {
        // Initialize CKEditor
        if (CKEDITOR.instances['feedback_value']) {
            CKEDITOR.instances['feedback_value'].destroy(true);
        }
        CKEDITOR.replace('feedback_value');

        // Get reference to the button
        var submitBtn = document.getElementById('submain');

        // Listen to editor changes
        CKEDITOR.instances['feedback_value'].on('change', function () {
            var content = CKEDITOR.instances['feedback_value'].getData().trim();

            if (content.length > 0) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        });
    };
</script>