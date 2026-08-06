<?php

$userlevel = session('userlevel');
$arrayuserlevel  = array_map('intval', explode(',', $userlevel));
?>
<div class="col-sm-3 sidenav">
    <div class="row">
        <div class="col-md-12">
            <h4>Feedback</h4>
        </div>
        <!--         <div class="col-md-6">
            <i class="fa fa-cloud-download" style="font-size:24px;color:grey;text-align:left;padding-top:5px"></i>
        </div> -->
    </div>
    <?php if (in_array('67', $arrayuserlevel) || in_array('46', $arrayuserlevel)) { ?>
        <div class="feedback_form">
            <form class="feedback_inside_form" id="addNewfeedback" method="POST"><?= csrf_field() ?>
                <div class="form-group">
                    <textarea name="feedback_value" id="feedback_value" class="form-control"></textarea>
                </div>
                <input type="hidden" id="course_id" name="course_id" value="<?php echo $course_id; ?>" />
                <input type="hidden" id="page_id" name="page_id" value="<?php echo $page_id; ?>" />
                <input type="hidden" id="type" name="type" value="2" />
                <input type="hidden" id="videotime" name="videotime" value="1" />

                <button type="submit" class="btn btn-sm btn-primary">Save</button>
            </form>
        </div>
    <?php } ?>
    <?php if (in_array('46', $arrayuserlevel) || in_array('5', $arrayuserlevel) || in_array('67', $arrayuserlevel)) { ?>
        <div class="feedback_view" id="result">
            <?php $j = 0;
            foreach ($feedbacks as $feedback_details) {
                $j = $j + 1;  ?>
                <div class="individual_feedback_design" style="position: relative; width: 100%;">
                    <img class="img_circle" src="<?php echo (base_url() . 'assets/assets/uploads/profile/' . $feedback_details['id_user'] . '/' . $feedback_details['profile_foldername'] . '/' . $feedback_details['profile_image']) ?>" class="rounded-circle" alt="Cinque Terre" width="50" height="50">
                    <strong><?php echo $feedback_details['fname'] ?></strong>
                    <span style="font-size:10px"><?php echo '(' . date("m/d", $feedback_details['last_updated_on']) . ')' ?></span>
                    <!-- <span class="feedback-arrow" style="position: absolute; top: 0; right: 0; font-size: 18px; cursor: pointer;">&#xFE19;</span> -->
                    <a> <i class="fa fa-check" style="color: grey"></i></a>

                    <div class="dropdown" style="float:right;">
                        <!-- <a> <i class="fa fa-check" style="color: grey"></i></a> -->

                        <button class="dropbtn"><span class="feedback-arrow" style="font-size: 10px; cursor: pointer;">&#xFE19;</span></button>
                        <div class="dropdown-content">
                            <form class="delete_feedback" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="course_id" value="<?php echo $course_id; ?>" />
                                <input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
                                <input type="hidden" name="feedbackid" value="<?php echo $feedback_details['feedbackid']; ?>" />
                                <button type="submit" class="btn btn-sm btn-default" style="width: 100%; padding: 10px 5px; border: none;text-align: center; cursor: pointer;">
                                    Delete
                                </button>
                            </form>

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
                            <img class="img_circle" src="<?php echo (base_url() . 'assets/assets/uploads/profile/' . $reply['id_user'] . '/' . $reply['profile_foldername'] . '/' . $reply['profile_image']) ?>" class="rounded-circle" alt="Cinque Terre" width="50" height="50">
                            <strong><?php echo $reply['fname1'] ?></strong>
                            <span style="font-size:10px"><?php echo '(' . date("m/d", $reply['last_updated_on']) . ')' ?></span>
                            <!-- <span class="feedback-arrow" style="position: absolute; top: 0; right: 0; font-size: 18px; cursor: pointer;">&#xFE19;</span> -->
                            <div class="dropdown" style="float:right;">
                                <button class="dropbtn"><span class="feedback-arrow" style="font-size: 10px; cursor: pointer;">&#xFE19;</span></button>
                                <div class="dropdown-content">

                                    <form class="delete_reply" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="course_id" value="<?php echo $course_id; ?>" />
                                        <input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
                                        <input type="hidden" name="feedbackreplyid" value="<?php echo $reply['feedbackreplyid']; ?>" />
                                        <button type="submit" class="btn btn-sm btn-default" style="width: 100%; padding: 10px 5px; border: none;text-align: center; cursor: pointer;">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <br>
                            <div class="feedback_details">
                                R : <?php echo $reply['feedback_replies']; ?>
                            </div><br />
                    <?php }
                    } ?>
                    <div class="reply_form">
                        <form class="replay_inside_form" id="replyfeedback" method="POST"><?= csrf_field() ?>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <textarea name="feedback" class="form-control" placeholder="Reply" rows="1" cols="30" required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <input type="hidden" name="course_id" value="<?php echo $course_id; ?>" />
                                    <input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
                                    <input type="hidden" name="feedbackid" value="<?php echo $feedback_details['feedbackid']; ?>" />
                                    <button type="submit" class="btn btn-sm btn-default"><i class="material-icons" style="font-size:16px">&#xe163;</i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            <?php  } ?>
        </div>
    <?php } ?>

</div>
<script>
    $(document).ready(function() {
        $('#addNewfeedback').on('submit', function(event) {

            event.preventDefault();

            var dataString = new FormData($('#addNewfeedback')[0]);

            var vidDuration = parent.GetVideoTime();
            dataString.append("videotime", vidDuration);

            if (typeof FormData !== 'undefined') {
                $.ajax({
                    url: '<?php echo base_url('SCORM/Course_builder/review_course/addNewfeedback') ?>',
                    type: "POST",
                    data: dataString,
                    async: false,
                    processData: false,
                    contentType: false,
                    success: function(data) {
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
                    error: function(xhr, textStatus, errorThrown) {
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
    $(document).on('submit', '.replay_inside_form', function(event) {
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
                success: function(data) {
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
                error: function(xhr, textStatus, errorThrown) {
                    console.log('Request failed');
                }
            });
        } else {
            alert("Your browser doesn't support FormData API! Use IE 10 or above.");
        }
    });
</script>
<script>
    $(document).on('submit', '.delete_feedback', function(event) {
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
                success: function(data) {
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
                error: function(xhr, textStatus, errorThrown) {
                    console.log('Request failed');
                }
            });
        } else {
            alert("Your browser doesn't support FormData API! Use IE 10 or above.");
        }
    });
</script>
<script>
    $(document).on('submit', '.delete_reply', function(event) {
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
                success: function(data) {
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
                error: function(xhr, textStatus, errorThrown) {
                    console.log('Request failed');
                }
            });
        } else {
            alert("Your browser doesn't support FormData API! Use IE 10 or above.");
        }
    });
</script>