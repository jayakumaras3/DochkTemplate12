<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">

                    <li class="breadcrumb-item"><a href="<?php echo base_url('my_training/read_more') ?>">Course
                            Detail</a></li>
                    <li class="breadcrumb-item"><a
                            href="<?php echo base_url('SCORM/course_builder/Editor') ?>">Pages</a></li>

                </ol>
            </div>
            <h4 class="page-title">Page Feedback -
                <?php
                echo $page_name;
                ?>
            </h4>
        </div>
    </div>
</div>

<?php if (!empty($feedback)): ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th class="center">#</th>
                                <th>Stage</th>
                                <th>Time</th>
                                <th>Feedback</th>
                                <th>Status</th>
                                <th>Creator</th>
                                <th>On</th>
                                <th>Reply</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                            foreach ($feedback as $feedback_details) {

                                $j = $j + 1; ?>
                                <tr>
                                    <td class="center"><?php echo $j ?></td>
                                    <td><?php
                                    $stage = $feedback_details['stage'];
                                    switch ($stage) {
                                        case 0:
                                            echo '-';
                                            break;
                                        case 3:
                                            echo 'Alp';
                                            break;
                                        case 4:
                                            echo 'Alp 2 ';
                                            break;
                                        case 5:
                                            echo 'Bet';
                                            break;
                                        case 6:
                                            echo 'Bet 2';
                                            break;
                                        case 7:
                                            echo 'Gam';
                                            break;
                                        case 8:
                                            echo 'Gam';
                                            break;
                                    }
                                    ?></td>
                                    <td><?php echo $feedback_details['videotime'] ?></td>
                                    <td><b>Comment :</b> <?php echo $feedback_details['feedback'] ?>
                                        <div><?php foreach ($replies[$feedback_details['feedbackid']] as $reply) { ?>
                                                <b> R : </b> <?php echo $reply['feedback_replies'] ?><br />
                                            <?php } ?>
                                        </div>
                                    </td>
                                    <td><?php $status = $feedback_details['status'];
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
                                    ?></td>
                                    <td><?php echo $feedback_details['fname'] ?></td>
                                    <td><?php echo date('m/d', $feedback_details['createdon']); ?></td>


                                    <td>
                                        <form class="form-horizontal"
                                            action="<?php echo base_url('SCORM/Course_builder/review_course/showfeedbackReplies') ?>"
                                            method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="feedbackid"
                                                value="<?php echo $feedback_details['feedbackid'] ?>">
                                            <input type="hidden" name="typeofpage" value="1">
                                            <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><i class="mdi mdi-information-outline"></i></button>
                                        </form>
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
    </div>
<?php endif; ?>
</div>