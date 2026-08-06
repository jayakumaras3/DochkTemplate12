<?php $client = session()->get('client'); ?>
<div class="feedback_form">
    <form class="feedback_inside_form" id="addNewfeedback" method="POST"><?= csrf_field() ?>
        <div class="form-group">
            <textarea name="feedback_value" id="feedback_value" class="form-control"></textarea>
        </div>
        <input type="hidden" id="course_id" name="course_id" value="<?php echo $course_id; ?>" />
        <input type="hidden" id="page_id" name="page_id" value="<?php echo $page_id; ?>" />
        <?php if ($client == 1) { ?>
            <input type="hidden" id="type" name="type" value="1" />
        <?php } else { ?>
            <input type="hidden" id="type" name="type" value="2" />
        <?php } ?>
        <input type="hidden" id="videotime" name="videotime" value="1" />
        <input type="hidden" id="tab" name="tab" value="2" />
        <button type="submit" class="btn btn-sm btn-primary">Save</button>
    </form>

</div>
<ul class="nav nav-tabs">
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
<div class="tab-content">
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
                        <button type="submit" class="btn btn-sm btn-default"
                            style="background: none; border: none; padding: 0; cursor: pointer;" title="close">
                            <i class="fa fa-close" style="color: grey; font-size: 15px;"></i>
                        </button>
                    </form>

                    <div class="dropdown" style="float:right;">

                        <button class="dropbtn"><span class="feedback-arrow"
                                style="font-size: 10px; cursor: pointer;">&#xFE19;</span></button>
                        <div class="dropdown-content">
                            <form class="delete_feedback" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="course_id" value="<?php echo $course_id; ?>" />
                                <input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
                                <input type="hidden" name="feedbackid" value="<?php echo $feedback_details['feedbackid']; ?>" />
                                <button type="submit" class="btn btn-sm btn-default"
                                    style="width: 100%; padding: 10px 5px; border: none;text-align: center; cursor: pointer;">
                                    Delete
                                </button>
                            </form>

                        </div>
                    </div>
                    <div class="feedback_inside_form" style="float:right;"> <button
                            class="btn btn-outline-warning  btn-xs rounded-pill waves-effect waves-light" data-toggle="modal"
                            data-target="#myModal2" style="background: none; border: none; padding: 0; cursor: pointer;"
                            title="Upload">
                            <i class="fa fa-paperclip"></i>
                        </button>
                    </div>
                    <?php if (strlen($feedback_details['attchment']) > 2) { ?>
                        <div class="feedback_inside_form" style="float:right;"> <a
                                href="<?php echo base_url('assets/assets/uploads/feedback_attachment/' . $feedback_details['feedbackid'] . '/' . $feedback_details['attchment']) ?>"
                                class="btn btn-outline-warning  btn-xs rounded-pill waves-effect waves-light" target="_blank"
                                style="background: none; border: none; padding: 0; cursor: pointer;color:black"
                                title="View attachmnet">
                                <i class="fa fa-eye"></i>
                            </a>
                        </div>
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
                                        <form class="form-horizontal" enctype="multipart/form-data" id="uploadmyfile"
                                            method="POST"><?= csrf_field() ?>
                                            <div class="form-group col-md-12 mb-3">
                                                <input type="file" name="file" accept=".png,.jpg,.jpeg" required />
                                            </div>
                                            <p style="font-size:10px">File type : png,jpg and jpeg </p>
                                            <div class="form-group col-md-12">
                                                <input type="hidden" id="course_id" name="course_id"
                                                    value="<?php echo $course_id; ?>" />
                                                <input type="hidden" id="page_id" name="page_id"
                                                    value="<?php echo $page_id; ?>" />
                                                <input type="hidden" name="feedbackid"
                                                    value="<?php echo $feedback_details['feedbackid']; ?>" />
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
                    </div>

                    <br>
                    <div class="feedback_details">
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
                        <?php echo $feedback_details['feedback']; ?>
                    </div><br />
                    <?php if (isset($replies[$feedback_details['feedbackid']])) { ?>
                        <?php foreach ($replies[$feedback_details['feedbackid']] as $reply) { ?>
                            <?php if (strlen($reply['profile_image']) > 3) { ?>
                                <img class="img_circle_reply"
                                    src="<?php echo (base_url() . 'assets/assets/uploads/profile/' . $reply['id_user'] . '/' . $reply['profile_foldername'] . '/' . $reply['profile_image']) ?>"
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
                                        <input type="hidden" name="feedbackreplyid" value="<?php echo $reply['feedbackreplyid']; ?>" />
                                        <button type="submit" class="btn btn-sm btn-default"
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
                            <button type="submit" class="btn btn-sm btn-default"
                                style="background: none; border: none; padding: 0; cursor: pointer;" title="close">
                                <i class="fa fa-close" style="color: grey; font-size: 15px;"></i>
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
                                    <button type="submit" class="btn btn-sm btn-default"
                                        style="width: 100%; padding: 10px 5px; border: none;text-align: center; cursor: pointer;">
                                        Delete
                                    </button>
                                </form>

                            </div>
                        </div>
                        <!-- <div class="feedback_inside_form" style="float:right;"> <button class="btn btn-outline-warning  btn-xs rounded-pill waves-effect waves-light" style="background: none; border: none; padding: 0; cursor: pointer;" data-toggle="modal" data-target="#myModal1">
                                <i class="fa fa-paperclip"></i>
                            </button>
                        </div> -->
                        <?php if (strlen($feedback_details['attchment']) > 2) { ?>
                            <div class="feedback_inside_form" style="float:right;"> <a
                                    href="<?php echo base_url('assets/assets/uploads/feedback_attachment/' . $feedback_details['feedbackid'] . '/' . $feedback_details['attchment']) ?>"
                                    class="btn btn-outline-warning  btn-xs rounded-pill waves-effect waves-light" target="_blank"
                                    style="background: none; border: none; padding: 0; cursor: pointer;color:black"
                                    title="View attachmnet">
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
                        <div class="feedback_details">
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
                            <?php echo $feedback_details['feedback']; ?>
                        </div><br />
                        <?php if (isset($replies[$feedback_details['feedbackid']])) { ?>
                            <?php foreach ($replies[$feedback_details['feedbackid']] as $reply) { ?>
                                <?php if (strlen($reply['profile_image']) > 3) { ?>
                                    <img class="img_circle_reply"
                                        src="<?php echo (base_url() . 'assets/assets/uploads/profile/' . $reply['id_user'] . '/' . $reply['profile_foldername'] . '/' . $reply['profile_image']) ?>"
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
                                            <button type="submit" class="btn btn-sm btn-default"
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
                    <?php } ?>
                    <strong><?php echo $feedback_details['fname'] ?></strong>
                    <span
                        style="font-size:10px"><?php echo '(' . date("m/d", $feedback_details['last_updated_on']) . ')' ?></span>
                    <!-- <span class="feedback-arrow" style="position: absolute; top: 0; right: 0; font-size: 18px; cursor: pointer;">&#xFE19;</span> -->
                    <form class="Closed_status" method="POST" style="display: inline-block;">
                        <input type="hidden" name="course_id" value="<?php echo $course_id; ?>" />
                        <input type="hidden" name="feedbackid" value="<?php echo $feedback_details['feedbackid']; ?>" />
                        <input type="hidden" name="status" value="1" />
                        <input type="hidden" id="tab" name="tab" value="2" />
                        <button type="submit" class="btn btn-sm btn-default"
                            style="background: none; border: none; padding: 0; cursor: pointer;" title="close">
                            <i class="fa fa-reply" style="color: grey; font-size: 15px;"></i>
                        </button>
                    </form>


                    <div class="dropdown" style="float:right;">

                        <button class="dropbtn"><span class="feedback-arrow"
                                style="font-size: 10px; cursor: pointer;">&#xFE19;</span></button>
                        <div class="dropdown-content">
                            <form class="delete_feedback" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="course_id" value="<?php echo $course_id; ?>" />
                                <input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
                                <input type="hidden" name="feedbackid" value="<?php echo $feedback_details['feedbackid']; ?>" />
                                <button type="submit" class="btn btn-sm btn-default"
                                    style="width: 100%; padding: 10px 5px; border: none;text-align: center; cursor: pointer;">
                                    Delete
                                </button>
                            </form>

                        </div>
                    </div>
                    <div class="feedback_inside_form" style="float:right;"> <button
                            class="btn btn-outline-warning  btn-xs rounded-pill waves-effect waves-light"
                            style="background: none; border: none; padding: 0; cursor: pointer;" data-toggle="modal"
                            data-target="#myModal">
                            <i class="fa fa-paperclip"></i>
                        </button>
                    </div>
                    <?php if (strlen($feedback_details['attchment']) > 2) { ?>
                        <div class="feedback_inside_form" style="float:right;"> <a
                                href="<?php echo base_url('assets/assets/uploads/feedback_attachment/' . $feedback_details['feedbackid'] . '/' . $feedback_details['attchment']) ?>"
                                class="btn btn-outline-warning  btn-xs rounded-pill waves-effect waves-light" target="_blank"
                                style="background: none; border: none; padding: 0; cursor: pointer;color:black"
                                title="View attachmnet">
                                <i class="fa fa-eye"></i>
                            </a>
                        </div>
                    <?php } ?>
                    <!-- Modal -->
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <p style="font-size:10px">File type : png,jpg and jpeg </p>
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
                    </div>

                    <br>
                    <div class="feedback_details">
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
                        <?php echo $feedback_details['feedback']; ?>
                    </div><br />
                    <?php if (isset($replies[$feedback_details['feedbackid']])) { ?>
                        <?php foreach ($replies[$feedback_details['feedbackid']] as $reply) { ?>
                            <?php if (strlen($reply['profile_image']) > 3) { ?>
                                <img class="img_circle_reply"
                                    src="<?php echo (base_url() . 'assets/assets/uploads/profile/' . $reply['id_user'] . '/' . $reply['profile_foldername'] . '/' . $reply['profile_image']) ?>"
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
                                        <input type="hidden" name="feedbackreplyid" value="<?php echo $reply['feedbackreplyid']; ?>" />
                                        <button type="submit" class="btn btn-sm btn-default"
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
                                        <input type="hidden" name="status" value="<?php echo $feedback_details['status']; ?>" />
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
<script>
    $(document).ready(function () {
        $('#addNewfeedback').on('submit', function (event) {

            event.preventDefault();

            var dataString = new FormData($('#addNewfeedback')[0]);

            var vidDuration = parent.GetVideoTime();

            dataString.append("videotime", vidDuration);
            console.log(dataString + "Vid Dur");
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
                            console.log('inside on condition');
                            location.reload();
                            // $("#result").load("SCORM/Course_builder/page_feedback_view");

                        } else {

                            alert('error', 'Something Went Wrong! Please contact Site Admin!');
                        }

                    },
                    error: function (xhr, textStatus, errorThrown) {
                        console.log('request failed');
                    }
                })
            } else {
                message("Your Browser Don't support FormData API! Use IE 10 or Above!");
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

        var dataString = new FormData($('#uploadfile1')[0]);

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