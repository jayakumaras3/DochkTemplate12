<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/dashboard'); ?>">
                            Dashboard
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                Org Structure
            </h4>
        </div>
    </div>
</div>

<div class="row">
    <?php
    if (count($my_manager) > 0) {
        foreach ($my_manager as $mng) {
            if ($mng['name'] == '')
                continue;
            ?>
            <div class="account-pages mt-2 mb-2">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6 col-xl-4">
                            <div class="widget-rounded-circle card">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="avatar-lg">
                                                <form class="form-horizontal"
                                                    action="<?php echo base_url('etrack/dashboard/org_chart'); ?>"
                                                    method="POST"><?= csrf_field() ?>

                                                    <input type="hidden" name="temp_user"
                                                        value="<?php echo $mng['id_user']; ?>">
                                                    <?php if (!empty($mng['profile_image']) && !empty($mng['profile_foldername'])) { ?>
                                                        <input type="image"
                                                            src="<?php echo base_url('assets/assets/uploads/profile/' . $mng['id_user'] . "/" . $mng['profile_foldername'] . "/" . $mng['profile_image']) ?>"
                                                            class="img-fluid rounded-circle">
                                                    <?php } else { ?>

                                                        <input type="image"
                                                            src="<?php echo base_url('public/aristo_assets/images/User_2_1.svg') ?>"
                                                            class="img-fluid rounded-circle">
                                                    <?php } ?>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="col">

                                            <h5 class="mb-1 mt-2"><?php echo $mng['name'] . ' ' . $mng['last_name']; ?></h5>
                                            <p class="mb-2 text-muted"><?php echo $mng['designation']; ?></p>
                                        </div>
                                    </div> <!-- end row-->
                                </div>
                            </div> <!-- end widget-rounded-circle-->
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->
                </div>
                <!-- end container -->
            </div>

            <?php
        }
    }
    ?>
</div>

<div class="row">
    <?php
    foreach ($about_me as $me) {
        ?>
        <div class="account-pages mt-2 mb-2">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-4">
                        <div class="widget-rounded-circle card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="avatar-lg">

                                            <?php if (!empty($me['profile_image']) && !empty($me['profile_foldername'])) { ?>
                                                <img src="<?php echo base_url('assets/assets/uploads/profile/' . $me['id_user'] . "/" . $me['profile_foldername'] . "/" . $me['profile_image']) ?>"
                                                    class="img-fluid rounded-circle">
                                            <?php } else { ?>

                                                <img src="<?php echo base_url('public/aristo_assets/images/User_2_1.svg') ?>"
                                                    class="img-fluid rounded-circle">
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h5 class="mb-1 mt-2"><?php echo $me['name'] . ' ' . $me['last_name']; ?></h5>
                                        <p class="mb-2 text-muted"><?php echo $me['designation']; ?></p>
                                    </div>
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end widget-rounded-circle-->
                        <!-- end row -->

                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <?php
    }
    ?>
</div>


<div class="row">
    <?php
    foreach ($my_reportees as $rep) {
        ?>
        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="avatar-lg">

                                <form class="form-horizontal" action="<?php echo base_url('etrack/dashboard/org_chart'); ?>"
                                    method="POST"><?= csrf_field() ?>

                                    <input type="hidden" name="temp_user" value="<?php echo $rep['id_user']; ?>">
                                    <?php if (!empty($rep['profile_image']) && !empty($rep['profile_foldername'])) { ?>
                                        <input type="image"
                                            src="<?php echo base_url('assets/assets/uploads/profile/' . $rep['id_user'] . "/" . $rep['profile_foldername'] . "/" . $rep['profile_image']) ?>"
                                            class="img-fluid rounded-circle">
                                    <?php } else { ?>

                                        <input type="image"
                                            src="<?php echo base_url('public/aristo_assets/images/User_2_1.svg') ?>"
                                            class="img-fluid rounded-circle">
                                    <?php } ?>

                                </form>
                            </div>
                        </div>
                        <div class="col">
                            <h5 class="mb-1 mt-2"><?php echo $rep['name'] . ' ' . $rep['last_name']; ?></h5>
                            <p class="mb-2 text-muted"><?php echo $rep['designation']; ?></p>
                        </div>
                    </div> <!-- end row-->
                </div>
            </div> <!-- end widget-rounded-circle-->
        </div>
        <?php
    }
    ?>
</div>