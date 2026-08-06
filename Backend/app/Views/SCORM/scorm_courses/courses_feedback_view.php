<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url($courses_link) ?>"><?= esc($courses_link_label) ?></a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('my_training/read_more') ?>">Course Detail</a></li>
                </ol>
            </div>
            <h4 class="page-title">
                <?php
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
                echo ' - ' . $course_name;
                ?></h4>

        </div>
    </div>
</div>
<div class="row">
    <?php
    $feedback_count_for_stage = function ($stage_number) use ($feedback_count) {
        // Use array_column to find the feedback count for the given stage number
        $feedback = array_filter($feedback_count, function ($item) use ($stage_number) {
            return $item['stage'] == $stage_number;
        });

        $feedback = array_values($feedback); // Reindex array after filtering
        return !empty($feedback) ? $feedback[0]['feedback_count'] : 0; // Return feedback count or 0 if no feedback for the stage
    }; ?>
    <?php //if ($stage != 3) { 
    ?>
    <div class="col-lg-2">
        <form id="userAssign" action="<?php echo base_url('SCORM/Course_builder/review_course/showfeedback') ?>" method="POST"><?= csrf_field() ?>
            <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
            <input type="hidden" name="course_name" value="<?php echo $course_name; ?>">
            <input type="hidden" name="stage" value="3">
            <button type="submit" class="btn btn-outline-danger waves-effect btn-sm waves-light mb-3">Alpha (<?php echo $feedback_count_for_stage(3); ?>)</button>
        </form>
    </div>
    <?php //}
    //if ($stage != 4) { 
    ?>
    <!-- <div class="col-lg-2">
            <form id="userAssign" action="<?php echo base_url('SCORM/Course_builder/review_course/showfeedback') ?>" method="POST"><?= csrf_field() ?>
                <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
                <input type="hidden" name="course_name" value="<?php echo $course_name; ?>">
                <input type="hidden" name="stage" value="4">
                <button type="submit" class="btn btn-outline-danger waves-effect btn-sm waves-light mb-3">Alpha 2 (<?php echo $feedback_count_for_stage(4); ?>)</button>
            </form>
        </div> -->
    <?php //}
    // if ($stage != 5) { 
    ?>
    <div class="col-lg-2">
        <form id="userAssign" action="<?php echo base_url('SCORM/Course_builder/review_course/showfeedback') ?>" method="POST"><?= csrf_field() ?>
            <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
            <input type="hidden" name="course_name" value="<?php echo $course_name; ?>">
            <input type="hidden" name="stage" value="5">
            <button type="submit" class="btn btn-outline-primary waves-effect btn-sm waves-light mb-3">Beta (<?php echo $feedback_count_for_stage(5); ?>)</button>
        </form>
    </div>
    <?php //}
    // if ($stage != 6) { 
    ?>
    <!-- <div class="col-lg-2">
            <form id="userAssign" action="<?php echo base_url('SCORM/Course_builder/review_course/showfeedback') ?>" method="POST"><?= csrf_field() ?>
                <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
                <input type="hidden" name="course_name" value="<?php echo $course_name; ?>">
                <input type="hidden" name="stage" value="6">
                <button type="submit" class="btn btn-outline-primary waves-effect btn-sm waves-light mb-3">Beta 2 (<?php echo $feedback_count_for_stage(6); ?>)</button>
            </form>
        </div> -->
    <?php //}
    //if ($stage != 7) { 
    ?>
    <div class="col-lg-2">
        <form id="userAssign" action="<?php echo base_url('SCORM/Course_builder/review_course/showfeedback') ?>" method="POST"><?= csrf_field() ?>
            <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
            <input type="hidden" name="course_name" value="<?php echo $course_name; ?>">
            <input type="hidden" name="stage" value="7">
            <button type="submit" class="btn btn-outline-warning waves-effect btn-sm waves-light mb-3">Gamma (<?php echo $feedback_count_for_stage(7); ?>)</button>
        </form>
    </div>
    <?php //}
    //if ($stage != 8) { 
    ?>
    <!-- <div class="col-lg-2">
            <form id="userAssign" action="<?php echo base_url('SCORM/Course_builder/review_course/showfeedback') ?>" method="POST"><?= csrf_field() ?>
                <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
                <input type="hidden" name="course_name" value="<?php echo $course_name; ?>">
                <input type="hidden" name="stage" value="8">
                <button type="submit" class="btn btn-outline-warning waves-effect btn-sm waves-light mb-3">Gamma 2 (<?php echo $feedback_count_for_stage(8); ?>)</button>
            </form>
        </div> -->
    <?php //}  
    ?>
    <div class="col-lg-2">
        <form id="userAssign" action="<?php echo base_url('SCORM/Course_builder/review_course/exportfeedbackreplies') ?>" method="POST"><?= csrf_field() ?>
            <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>">
            <input type="hidden" name="course_name" value="<?php echo $course_name; ?>">
            <!-- <input type="hidden" name="stage" value="3"> -->
            <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light mb-3"><i class="mdi mdi-export"></i> Feedback Export</button>
        </form>
    </div>
</div>

<?php if (!empty($feedback)) { ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="alternative-page-datatable" class="table dt-responsive  w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th width=25%>Page</th>
                                <th>Time</th>
                                <th width=30%>Feedback</th>
                                <th>Status</th>
                                <th>Creator</th>
                                <th>On</th>
                                <th>Details</th>
                                <?php $userlevel = session()->get('userlevel');
                                $arrayuserlevel  = array_map('intval', explode(',', $userlevel));
                                if (in_array('45', $arrayuserlevel)) {

                                ?> <th>Close</th>
                                <?php } ?>
                                <!-- <th>Del</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                            foreach ($feedback as $feedback_details) { ?>
                                <tr>
                                    <td class="center"><?php echo  $feedback_details['feedbackid'] ?></td>
                                    <td><?php echo abs($feedback_details['pnumber']); ?>-<?php echo $feedback_details['pagename'] ?></td>
                                    <td><?php if ($feedback_details['videotime'] != null) {
                                            // Find the position of the period
                                            $position = strpos($feedback_details['videotime'], '.');
                                            echo substr($feedback_details['videotime'], 0,  $position);
                                        } ?></td>
                                    <td><?php echo $feedback_details['feedback']
                                        ?></td>
                                    <td><?php $status = $feedback_details['status'];
                                        switch ($status) {
                                            case 1:
                                                echo '<span class="badge bg-soft-info text-info p-1">New</span>';
                                                break;
                                            case 2:
                                                echo '<span class="badge bg-soft-primary text-info p-1">Replied</span>';
                                                break;
                                            case 3:
                                                echo '<span class="badge bg-soft-primary text-info p-1">Fixed</span>';
                                                break;
                                            case 4:
                                                echo '<span class="badge bg-soft-warning text-info p-1">QA Ver</span>';
                                                break;
                                            case 5:
                                                echo '<span class="badge bg-soft-danger text-info p-1">ReOpen</span>';
                                                break;
                                            case 6:
                                                echo '<span class="badge bg-soft-success text-info p-1">Closed</span>';
                                                break;
                                        }
                                        ?></td>
                                    <td><?php echo $feedback_details['fname'] ?></td>
                                    <td><?php echo date('m/d', $feedback_details['createdon']); ?></td>


                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url('SCORM/Course_builder/review_course/showfeedbackReplies') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="feedbackid" value="<?php echo $feedback_details['feedbackid'] ?>">
                                            <input type="hidden" name="typeofpage" value="2">
                                            <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><i class="mdi mdi-information-outline"></i></button>
                                        </form>
                                    </td>

                                    <?php $userlevel = session()->get('userlevel');
                                    $arrayuserlevel  = array_map('intval', explode(',', $userlevel));
                                    if (in_array('45', $arrayuserlevel)) {
                                        if ($feedback_details['status'] != '6') {
                                    ?>
                                            <td>
                                                <form class="form-horizontal" action="<?php echo base_url('SCORM/Course_builder/review_course/closefeedback') ?>" method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="course_id" value="<?php echo $scourse_id; ?>" />
                                                    <input type="hidden" name="feedbackid" value="<?php echo $feedback_details['feedbackid']; ?>" />
                                                    <input type="hidden" name="status" value="6" />
                                                    <input type="hidden" id="tab" name="tab" value="4" />
                                                    <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" title="close" style="font-size: 10px;" onclick="return confirm('Are you sure !! Do you want to close this feedback?')">
                                                        <i class="mdi mdi-close" style="color: grey; font-size: 15px;"></i>
                                                        <!-- Close feedback -->
                                                    </button>
                                                </form>
                                            </td>
                                        <?php } else { ?>
                                            <td></td>
                                    <?php   }
                                    } ?>
                                    <!-- <td>
                                        <form class="form-horizontal" action="<?php echo base_url('SCORM/Course_builder/review_course/deleteFeedback') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="feedbackid" value="<?php echo $feedback_details['feedbackid'] ?>">
                                            <button class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="return confirm('Are you sure !! Do you want to delete this file?')"><span class="mdi mdi-trash-can-outline"></span></button>
                                        </form>
                                    </td> -->
                                    <!-- <td></td> -->
                                <?php
                            } ?>
                                </tr>
                        </tbody>
                    </table>


                </div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="persistent-warning">
        <div class="danger-text">
            No Data Found.
        </div>
    </div>
<?php } ?>
</div>