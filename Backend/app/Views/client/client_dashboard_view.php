<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Client Dashboard</h4>
        </div>
    </div>
</div>
<div class="row">
    <?php
    foreach ($client_active_projects as $data) {
        $projectid = $data['projectid'];
        $gen_random = 420 . rand(25, 50) . rand(100, 1000);
        $temp_id = password_hash($gen_random, PASSWORD_DEFAULT);
        $dealCrypt = crypt($projectid, '');
        $ciphering = "AES-128-CTR";
        // Use OpenSSl Encryption method
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;

        // Non-NULL Initialization Vector for encryption
        $encryption_iv = '1234567891011121';

        // Store the encryption key
        $encryption_key = "GeeksforGeeks";

        // Use openssl_encrypt() function to encrypt the data
        $encryption = openssl_encrypt(
            $projectid,
            $ciphering,
            $encryption_key,
            $options,
            $encryption_iv
        );
        $temp_id  =  preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $encryption . '_' . $temp_id);

        $user_url = base_url('usergraph/projectplanGraph/' . $temp_id);
        $projectplan_url = base_url('userprojectplan/getprojectplan/' . $temp_id);

    ?>
        <div class="col-md-4">
            <div class="card project-box">
                <div class="card-body">
                    <div class="dropdown float-end">
                        <a href="#" class="dropdown-toggle card-drop arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="mdi mdi-dots-horizontal m-0 text-muted h3"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <form class="form-horizontal" action="<?php echo base_url('Project/client_dashboard/courses') ?>" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="projectid" value="<?php echo $data['projectid'] ?>">
                                <button type="submit" class="dropdown-item" >Courses</button>
                            </form>
                            <a class="dropdown-item" target="_blank" href="<?php echo $projectplan_url; ?>">Project Plan</a>
                            <a class="dropdown-item" href="<?php echo base_url('Project/client_dashboard/documents') ?>">Documents</a>
                            <a class="dropdown-item" href="<?php echo base_url('Project/client_dashboard/escalation') ?>">Escalation Matrix</a>
                        </div>
                    </div> <!-- end dropdown -->
                    <!-- Title-->
                    <h4 class="mt-0"><a href="project-detail.html" class="text-dark"><?php echo $data['projectname'] ?></a></h4>
                    <p class="text-muted text-uppercase"><i class="mdi mdi-crosshairs-question"></i> <small>
                            <?php
                            $project_type = $data['project_type'];
                            if ($project_type == 1) {
                                echo 'E-Learning';
                            } elseif ($project_type == 2) {
                                echo 'Video';
                            } elseif ($project_type == 3) {
                                echo 'AR/VR';
                            } else {
                                echo 'Others';
                            }
                            ?>
                        </small></p>
                    <div class="badge bg-soft-success text-success mb-3">
                        <?php
                        $status = $data['status'];
                        if ($status == 1) {
                            echo 'Active';
                        }
                        if ($status == 3) {
                            echo 'On Hold';
                        }
                        ?>
                    </div>
                    <!-- Desc-->
                    <p class="text-muted font-13 mb-3 sp-line-2"><?php echo $data['description'] ?>
                    </p>
                    <!-- Task info-->
                    <p class="mb-1">
                        <span class="pe-2 text-nowrap mb-2 d-inline-block">
                            <i class="mdi mdi-calendar-arrow-right text-muted"></i>
                            <b>Started On: </b> <?php echo $data['start_date'] ?>
                        </span>
                        <span class="text-nowrap mb-2 d-inline-block">
                            <i class="mdi mdi-calendar-lock text-muted"></i>
                            <b>Completed On: </b> <?php echo $data['end_date'] ?>
                        </span>
                    </p>

                    <!-- Team-->
                    <div class="avatar-group mb-3" id="tooltips-container">
                        <a href="javascript: void(0);" class="avatar-group-item">
                            <img src="<?php echo base_url('/assets/assets/uploads/profile/pchandran@talentquest.com/1725006442_fc5d2c5f362a0caaf94b/pc2.jpg') ?>" class="rounded-circle avatar-sm" alt="Technical Lead" data-bs-container="#tooltips-container" data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Pramod" data-bs-original-title="Pramod">
                        </a>

                    </div>
                    <!-- Progress-->
                    <p class="mb-2 fw-semibold">Project Progress: <span class="float-end"><?php echo $data['percent'] ?></span></p>
                    <div class="progress mb-1" style="height: 7px;">
                        <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $data['percent'] ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $data['percent'] ?>%;">
                        </div><!-- /.progress-bar .progress-bar-danger -->
                    </div><!-- /.progress .no-rounded -->
                </div>
            </div> <!-- end card box-->
        </div>
    <?php
    }
    ?>
</div>
</div>