<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('my_training') ?>"><?= lang('Buttons.Dashboard') ?></a></li>
                </ol>
            </div>
            <h4 class="page-title"><?= lang('UI_Text.Client_Dashboard') ?></h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th><?= lang('UI_Text.Course_Name') ?></th>
                            <th><?= lang('UI_Text.Assigned_On') ?></th>
                            <th><?= lang('UI_Text.Due_On') ?></th>
                            <th><?= lang('UI_Text.Details') ?></th>
                            <th><?= lang('UI_Text.Mark_Completed') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        foreach ($my_reviews as $courses) {
                            $j = $j + 1;
                            ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <!-- <td><?php echo $courses['course_name']; ?></td> -->
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url('my_training/read_more') ?>"
                                        method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="crid" value="<?php echo $courses['course_id']; ?>">
                                        <input type="hidden" name="detail_type" value="7">
                                        <input type="hidden" name="tab" value="1">
                                        <button class="btn btn-link btn-sm waves-effect waves-light"
                                            style="text-decoration: none; padding: 0;">
                                            <?php echo $courses['course_name']; ?>
                                        </button>
                                    </form>
                                </td>

                                <td><?php echo date("Y-m-d", $courses['createdon']); ?></td>
                                <td><?php echo $courses['due_date']; ?></td>
                                <td width="10%">
                                    <form class="form-horizontal" action="<?php echo base_url('my_training/read_more') ?>"
                                        method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="crid" value="<?php echo $courses['course_id']; ?>">
                                        <input type="hidden" name="detail_type" value="7">
                                        <input type="hidden" name="tab" value="1">
                                        <button
                                            class="btn btn-outline-success waves-effect btn-sm waves-light mb-3"><?= lang('Buttons.Launch') ?></button>
                                    </form>
                                </td>
                                <td width="10%">

                                    <a href="#"
                                        onclick="return confirmReview('<?php echo base_url('SCORM/course_builder/review_course/update_reviewstatus/' . $courses['course_id'] . '/7') ?>');"
                                        class="btn btn-outline-danger waves-effect btn-sm waves-light mb-3">
                                        <?= lang('Buttons.Completed') ?>
                                    </a>

                                </td>
                            </tr>
                        <?php }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <?php
    // foreach ($client_active_projects as $data) {
    // $projectid = $data['projectid'];
    // $gen_random = 420 . rand(25, 50) . rand(100, 1000);
    // $temp_id = password_hash($gen_random, PASSWORD_DEFAULT);
    // $dealCrypt = crypt($projectid, '');
    // $ciphering = "AES-128-CTR";
    //  $iv_length = openssl_cipher_iv_length($ciphering);
    // $options = 0;
    
    //  $encryption_iv = '1234567891011121';
    
    // $encryption_key = "GeeksforGeeks";
    
    // $encryption = openssl_encrypt(
    //     $projectid,
    //     $ciphering,
    //     $encryption_key,
    //     $options,
    //     $encryption_iv
    // );
    // $temp_id = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $encryption . '_' . $temp_id);
    
    // $user_url = base_url('usergraph/projectplanGraph/' . $temp_id);
    // $projectplan_url = base_url('userprojectplan/getprojectplan/' . $temp_id);
    
    ?>

    <!-- <h4 class="mt-0"><a class="text-dark"><?php //echo $data['projectname'] ?></a></h4>

        <div class="badge bg-soft-success text-success mb-3">
            <?php
            // $status = $data['status'];
            // if ($status == 1) {
            //     echo 'Active';
            // }
            // if ($status == 3) {
            //     echo 'On Hold';
            // }
            ?>
        </div>
        -->
    <!-- <p class="mb-1">
            <span class="pe-2 text-nowrap mb-2 d-inline-block">
                <i class="mdi mdi-calendar-arrow-right text-muted"></i>
                <b>Started On: </b> <?php //echo $data['start_date'] ?>
            </span>
            <span class="text-nowrap mb-2 d-inline-block">
                <i class="mdi mdi-calendar-lock text-muted"></i>
                <b>Completed On: </b> <?php //echo $data['end_date'] ?>
            </span>
        </p> -->


    <!-- <p class="mb-2 fw-semibold">Project Progress: <span class="float-end"><?php //echo $data['percent'] ?></span></p>
        <div class="progress mb-1" style="height: 7px;">
            <div class="progress-bar" role="progressbar" aria-valuenow="<?php //echo $data['percent'] ?>" aria-valuemin="0"
                aria-valuemax="100" style="width: <?php //echo $data['percent'] ?>%;">
            </div>
        </div>
    </div>
    </div> 
    </div>  -->
    <?php
    //}
    ?>
</div>

</div>
<script type="text/javascript">
    function confirmReview(url) {
        // Display confirmation dialog
        var userConfirmed = confirm("Are you sure you want to finalize the review? Once you click “OK,” you will no longer be able to provide feedback.");

        // If user clicks "Yes", proceed with the redirect
        if (userConfirmed) {
            window.location.href = url;
        }

        // If user clicks "Cancel", do nothing and stay on the current page
        return false;
    }
</script>