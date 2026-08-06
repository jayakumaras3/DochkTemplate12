<?php

$userlevel = session('userlevel');
$arrayuserlevel = array_map('intval', explode(',', $userlevel));
$error = session('error');
if (isset($error)):
    echo '<script>alert("' . isset($error) . '"]</script>';
endif;
$client = session('client');
$arraystakeholders = explode(',', $client);
?>
<?php $accessmenu = session()->get('accessmenu');
$arrayaccessmenu  = array_map('intval', explode(',', $accessmenu)); ?>
<!-- Start Content-->
<style>
    .thumbnail_db img {
        height: 100%;
        width: 100%;
    }

    .thumbnail_db img {
        object-fit: contain;
    }

    /* Add these styles for stars */
    .star {
        font-size: 24px;
        color: #ccc;
        cursor: pointer;
    }

    .star.selected,
    .star.hover,
    .star.half-selected {
        color: #ffcc00;
    }

    .star-rating {
        /* font-size: 24px; */
        color: gold;
        /* Adjust the color as needed */
    }

    .rating-container {
        display: inline-flex;
        /* Use inline-flex for better control */
        align-items: center;
        /* Align items vertically in the flex container */
    }

    #ratingContainer {
        /* Adjust the styles as needed */
        font-size: 18px;
        /* Example font size */
        margin-left: 0;
        /* Adjust as needed for spacing between rating and count user */
        line-height: 1;
        /* Reset the line-height to default for precise vertical alignment */
    }

    .cardtile {
        padding: 10px;
        background-color: rgba(255, 255, 255, 1);
        margin-right: 1px;
        margin-left: 1px;
        border-radius: 10px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.1), 0 6px 20px 0 rgba(0, 0, 0, 0.02);
    }

    .cardshaddow {
        border-radius: 10px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.1), 0 6px 20px 0 rgba(0, 0, 0, 0.02);
    }
</style>
<script>
    function displayStars(rating, containerId) {
        const container = document.getElementById(containerId);
        console.log("Rating for " + containerId + ": " + rating); // Debug log for rating
        container.innerHTML = ''; // Clear previous content

        if (rating === null || rating === 0) {
            // Display 5 outlined stars if the rating is null or zero
            for (let i = 0; i < 5; i++) {
                const outlinedStar = document.createElement('span');
                outlinedStar.innerHTML = '<i class="mdi mdi-star-outline text-warning"></i>';
                container.appendChild(outlinedStar);
            }
        } else {
            const fullStars = Math.floor(rating);
            for (let i = 0; i < fullStars; i++) {
                const star = document.createElement('span');
                star.innerHTML = '<i class="mdi mdi-star text-warning"></i>'; // Full star character
                container.appendChild(star);
            }

            const decimalPart = rating - fullStars;
            if (decimalPart > 0) {
                const halfStar = document.createElement('span');
                halfStar.innerHTML = '<i class="mdi mdi-star-half text-warning"></i>'; // Half-filled star character
                container.appendChild(halfStar);
            }

            const emptyStars = 5 - Math.ceil(rating); // Calculate empty stars
            for (let i = 0; i < emptyStars; i++) {
                const outlinedStar = document.createElement('span');
                outlinedStar.innerHTML = '<i class="mdi mdi-star-outline text-warning"></i>'; // Outlined star character
                container.appendChild(outlinedStar);
            }
        }
    }
</script>
<?php $current_url = current_url(true); // Returns CodeIgniter\HTTP\URI object 
?>
<?php $segment1 = uri_string(); // Returns string like 'my_training' 
?>
<?php $current_page = explode('/', uri_string())[0]; ?>
<div class="row">
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-body p-0">
                <ul class="nav nav-tabs nav-bordered" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="<?php echo base_url('my_training') ?>" class="nav-link px-3 py-2 <?= ($current_page == 'my_training') ? 'active' : '' ?>" aria-selected="<?= ($current_page == 'my_training') ? 'true' : 'false' ?>" role="tab" tabindex="-1">
                            <i class="mdi mdi-motion-play-outline font-18 d-md-none d-block"></i>
                            <span class="d-none d-md-block" style="color: <?= ($current_page == 'my_training') ? '#ff5533' : '' ?>;"><?php echo lang('Buttons.Dashboard'); ?></span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="<?php echo base_url('SCORM/scorm_courses') ?>" class="nav-link px-3 py-2 <?= ($current_page == 'SCORM') ? 'active' : '' ?>" aria-selected="<?= ($current_page == 'SCORM') ? 'true' : 'false' ?>" role="tab" tabindex="-1">
                            <i class="mdi mdi-youtube-tv font-18 d-md-none d-block"></i>
                            <span class="d-none d-md-block" style="color: <?= ($current_page == 'SCORM') ? '#ff5533' : '' ?>;"><?php echo lang('Buttons.My Courses'); ?></span>
                        </a>
                    </li>

                    <?php if (in_array('13', $arrayaccessmenu)) { ?>
                        <li class="nav-item" role="presentation">
                            <a href="<?php echo base_url('marketplace/learning_dashboard') ?>" class="nav-link px-3 py-2 <?= ($current_page == 'marketplace') ? 'active' : '' ?>" aria-selected="<?= ($current_page == 'marketplace') ? 'true' : 'false' ?>" role="tab" tabindex="-1">
                                <i class="mdi mdi-school-outline font-18 d-md-none d-block"></i>
                                <span class="d-none d-md-block" style="color: <?= ($current_page == 'marketplace') ? '#ff5533' : '' ?>;"><?php echo lang('Buttons.Learning Plan'); ?></span>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if (in_array('14', $arrayaccessmenu)) { ?>
                        <li class="nav-item" role="presentation">
                            <a href="<?php echo base_url('marketplace/dashboard') ?>" class="nav-link px-3 py-2 <?= ($current_page == 'marketplace') ? 'active' : '' ?>" aria-selected="<?= ($current_page == 'marketplace') ? 'true' : 'false' ?>" role="tab" tabindex="-1">
                                <i class="mdi mdi-school-outline font-18 d-md-none d-block"></i>
                                <span class="d-none d-md-block" style="color: <?= ($current_page == 'marketplace') ? '#ff5533' : '' ?>;"><?php echo lang('Buttons.Marketplace'); ?></span>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if (in_array('15', $arrayaccessmenu)) { ?>
                        <li class="nav-item" role="presentation">
                            <a href="<?php echo base_url('Certification/Certification_Portal') ?>" class="nav-link px-3 py-2 <?= ($current_page == 'Certification') ? 'active' : '' ?>" aria-selected="<?= ($current_page == 'Certification') ? 'true' : 'false' ?>" role="tab" tabindex="-1">
                                <i class="mdi mdi-certificate-outline font-18 d-md-none d-block"></i>
                                <span class="d-none d-md-block" style="color: <?= ($current_page == 'Certification') ? '#ff5533' : '' ?>;"><?php echo lang('Buttons.Certifications'); ?></span>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if (in_array('20', $arrayaccessmenu)) { ?>
                        <li class="nav-item" role="presentation">
                            <a href="<?php echo base_url('my_training/Favorites') ?>" class="nav-link px-3 py-2 <?= ($current_page == 'Favorites') ? 'active' : '' ?>" aria-selected="<?= ($current_page == 'Favorites') ? 'true' : 'false' ?>" role="tab" tabindex="-1">
                                <i class="mdi mdi-heart-outline font-18 d-md-none d-block"></i>
                                <span class="d-none d-md-block" style="color: <?= ($current_page == 'Favorites') ? '#ff5533' : '' ?>;"><?php echo lang('Buttons.Favorites'); ?></span>
                            </a>
                        </li>
                    <?php } ?>

                </ul> <!-- end nav-->
            </div>
        </div>
    </div>
</div>

<?php if ($clientCourseddata != '') {
    if (count($clientCourseddata) > 0) { ?>
        <h5 class="my-1"><?php echo lang('Buttons.My Courses'); ?></h5>
        <div id="holder" class="row row-cols-1 row-cols-md-3 g-3 ">

            <?php

            $j = 0;
            foreach ($clientCourseddata as $key => $clienteachCourseddata) {
                $course_name = $clienteachCourseddata['course_name'];

                $max_length = 39; // Maximum length of the string

                if (strlen($course_name) > $max_length) {
                    $shortened_name = substr($course_name, 0, $max_length) . ".."; // Append "..." for indication
                } else {
                    $shortened_name = $course_name;
                }

                if ($j >= 4) {
                    break; // Exit the loop after 4 courses
                }
                if ($clienteachCourseddata['type'] == 1 || $clienteachCourseddata['demo'] == 1) {
                    $demoButton = 'Video';
                }
                if ($clienteachCourseddata['type'] == 2) {
                    $demoButton = 'Preview';
                }
                if ($clienteachCourseddata['type'] == 5) {
                    $demoButton = 'Preview';
                }
                if (isset($clienteachCourseddata['thumbnail']) && $clienteachCourseddata['thumbnail'] != '') {

                    $thumbnail = base_url('assets/assets/uploads/SCORM_course_thumbnail/' . $clienteachCourseddata['scourse_id'] . '/' . $clienteachCourseddata['thumbnail']);
                } else {
                    $thumbnail = base_url('public/aristo_assets/images/default_thumbnail.png');
                }
                $playimg = base_url('assets/assets/img/play.png');
            ?>



                <div class="col-md-12 col-lg-4 col-xl-3">

                    <div class="card">
                        <div class="card-body" style="text-align:left; ">


                            <form class="form-horizontal" action="<?php echo base_url('my_training/read_more') ?>" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="crid" value="<?php echo $clienteachCourseddata['scourse_id'] ?>">
                                <input type="hidden" name="detail_type" value="1">
                                <input type="hidden" name="tab" value="1">

                                <button
                                    style="border: none; border:0; padding-top: 5px; outline: none; background: none; width: 100%; text-align:center">
                                    <div style="display: box;
  display: flex;
  box-align: center;
  align-items: center;
  box-pack: center;
  justify-content: center;">
                                        <img class="img-fluid mx-auto d-block rounded" src="<?= $thumbnail ?>"
                                            style="border: 1px solid transparent; display: block;background: none;  border-color: rgb(0, 0, 0, 0.2);  box-shadow: 4px 4px 5px rgba(0, 0, 0, 0.3);  height:150px;"
                                            alt="<?php echo $clienteachCourseddata['course_name'] ?>">
                                        <img style=" 
                                height: 40px;
                                width: 40px;
                                position: absolute;
                                opacity: 0.5;" src="<?php echo $playimg; ?>" alt="play" class="playBtn">
                                    </div>


                                </button>
                            </form>
                            <div style="padding-left: 10px;">
                                <?php //echo $clienteachCourseddata['course_status'];

                                $course_status = $clienteachCourseddata['mode'];
                                $type = $clienteachCourseddata['type'];
                                ?>
                                <?php
                                if ($course_status == '1') {
                                ?>
                                    <h5><span class="badge badge-soft-danger rounded-pill"><?php echo lang('UI_Text.Development'); ?>

                                        <?php } elseif ($course_status == 2) {
                                        //   print_r( $clienteachCourseddata['lesson_status'] ); 
                                        ?>
                                            <h5><?php if ($clienteachCourseddata['course_status'] == '2') { ?>
                                                    <span class="badge bg-soft-success text-success p-1"><?php echo lang('UI_Text.Completed'); ?></span>
                                                <?php } elseif ($clienteachCourseddata['course_status'] == '1' || $clienteachCourseddata['lesson_status'] == 'incomplete' || $clienteachCourseddata['lesson_status'] == 'failed') { ?>
                                                    <span class="badge bg-soft-info text-info p-1"><?php echo lang('UI_Text.In Progress'); ?></span>
                                                <?php } elseif ($clienteachCourseddata['course_status'] == '0') { ?>
                                                    <span class="badge bg-soft-warning text-warning p-1"><?php echo lang('UI_Text.Not Started'); ?></span>
                                                <?php } else { ?>
                                                    <span class="badge bg-soft-warning text-warning p-1"><?php echo lang('UI_Text.Not Started'); ?></span>
                                                <?php } ?>
                                            </h5>
                                        <?php } elseif ($course_status == '3') { ?>
                                            <h5><span class="badge badge-soft-danger rounded-pill"><?php echo lang('UI_Text.Alpha'); ?>
                                                <?php } elseif ($course_status == '4') { ?>
                                                    <h5><span class="badge badge-soft-danger rounded-pill"><?php echo lang('UI_Text.Alpha_2'); ?>
                                                        <?php } elseif ($course_status == '5') { ?>
                                                            <h5><span class="badge badge-soft-danger rounded-pill"><?php echo lang('UI_Text.Beta'); ?>
                                                                <?php } elseif ($course_status == '6') { ?>
                                                                    <h5><span class="badge badge-soft-danger rounded-pill"><?php echo lang('UI_Text.Beta_2'); ?>
                                                                        <?php } elseif ($course_status == '7') { ?>
                                                                            <h5><span class="badge badge-soft-danger rounded-pill"><?php echo lang('UI_Text.Gamma'); ?>
                                                                                <?php } elseif ($course_status == '8') { ?>
                                                                                    <h5><span class="badge badge-soft-danger rounded-pill"><?php echo lang('UI_Text.Gamma_2'); ?>
                                                                                            <?php } else {
                                                                                            if ($clienteachCourseddata['course_status'] == 2) { ?>
                                                                                                <h5><span class="badge badge-soft-success rounded-pill"><?php echo lang('UI_Text.Completed'); ?>
                                                                                                    <?php } elseif ($clienteachCourseddata['course_status'] == 1 ||  $clienteachCourseddata['lesson_status'] == 'incomplete') { ?>
                                                                                                        <span class="badge bg-soft-info text-info p-1"><?php echo lang('UI_Text.In Progress'); ?>
                                                                                                        <?php } elseif ($clienteachCourseddata['course_status'] == 0) { ?>
                                                                                                            <span class="badge bg-soft-warning text-warning p-1"><?php echo lang('UI_Text.Not Started'); ?>
                                                                                                            <?php } else { ?>
                                                                                                                <span class="badge bg-soft-warning text-warning p-1"><?php echo lang('UI_Text.Not Started'); ?>
                                                                                                            <?php }
                                                                                                    }
                                                                                                    echo '</span>';
                                                                                                            ?>

                                                                                                            <h5
                                                                                                                class="font-12 my-1 sp-line-1">
                                                                                                                <a class="text-dark"
                                                                                                                    title="<?php echo $course_name; ?>"><?php echo $shortened_name ?></a>
                                                                                                            </h5>


                                                                                                            <span
                                                                                                                class="font-10 text-muted">
                                                                                                                <?php echo lang('UI_Text.Duration'); ?>
                                                                                                                :
                                                                                                                <?php if ($clienteachCourseddata['duration'] > 0) { ?>
                                                                                                                    <?php
                                                                                                                    $duration = $clienteachCourseddata['duration'];
                                                                                                                    if ($duration > 60) {
                                                                                                                        $hours = intdiv($duration, 60);
                                                                                                                        echo $hours . ' ' . lang('UI_Text.Hours');
                                                                                                                        $balancemin = $duration - $hours * 60;
                                                                                                                        if ($balancemin > 0) {
                                                                                                                            echo $balancemin . ' ' . lang('UI_Text.Minutes');
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        echo $duration . ' ' . lang('UI_Text.Minutes');
                                                                                                                    }

                                                                                                                    ?>
                                                                                                                <?php } ?></span>

                            </div>
                        </div>
                    </div>
                </div>
            <?php $j = $j + 1;
            }
            ?>
            <?php if (isset($clienteachCourseddata['thumbnail']) && $clienteachCourseddata['thumbnail'] != '') {
                $thumbnail = base_url('assets/assets/img/view_all_my_courses.png');
            } else {
                $thumbnail = "";
            } ?>

        </div> <!-- end col -->

        <!-- end row-->
    <?php

    } else { ?>
        <!-- <div class="persistent-warning">
            <div class="danger-text">
                <?php echo lang('UI_Text.No_Course_Assigned'); ?>
            </div>
        </div> -->
    <?php }
} else { ?>
    <!-- <div class="persistent-warning">
        <div class="danger-text">
            <?php echo lang('UI_Text.No_Course_Assigned'); ?>
        </div>
    </div> -->
<?php } ?>

<?php if ($get_learning_plan != '') {
    if (count($get_learning_plan) > 0) { ?>
        <h5 class="my-1"><?php echo lang('Buttons.Learning Plan'); ?></h5>
        <div id="holder" class="row row-cols-1 row-cols-md-3 g-3 ">

            <?php

            $j = 0;
            foreach ($get_learning_plan as $key => $plan) {
                $mp_name = $plan['mp_name'];

                $max_length = 39; // Maximum length of the string

                if (strlen($mp_name) > $max_length) {
                    $shortened_name = substr($mp_name, 0, $max_length) . ".."; // Append "..." for indication
                } else {
                    $shortened_name = $mp_name;
                }

                if ($j >= 4) {
                    break; // Exit the loop after 4 courses
                }
                if (isset($plan['thumbnail']) && $plan['thumbnail'] != '') {

                    $thumbnail = base_url('assets/assets/uploads/learning_path/' . $plan['mp_id'] . '/' . $plan['thumbnail']);
                } else {
                    $thumbnail = base_url('public/aristo_assets/images/default_thumbnail.png');
                }
                $playimg = base_url('assets/assets/img/play.png');
            ?>

                <div class="col-md-12 col-lg-4 col-xl-3">

                    <div class="card">

                        <div class="card-body" style="text-align:left; ">
                            <form class="form-horizontal"
                                action="<?php echo base_url('marketplace/Learning_dashboard/learning_courses') ?>"
                                method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="mp_id" value="<?php echo $plan['mp_id']; ?>">
                                <input type="hidden" name="mode" value="<?php echo $plan['mode']; ?>">
                                <input type="hidden" name="mp_name" value="<?php echo $plan['mp_name']; ?>">
                                <?php //if ($plan['demo'] == 1) {
                                //echo '<input type="hidden" name="detail_type" value="3">';
                                // } else { 
                                echo ' <input type="hidden" name="type" value="3">';
                                // } 
                                ?>
                                <input type="hidden" name="tab" value="1">
                                <button
                                    style="border: none; border:0; padding-top: 5px; outline: none; background: none; width: 100%; text-align:center">
                                    <div style="display: box;
  display: flex;
  box-align: center;
  align-items: center;
  box-pack: center;
  justify-content: center;">
                                        <img class="img-fluid mx-auto d-block rounded" src="<?= $thumbnail ?>"
                                            style="border: 1px solid transparent; display: block;background: none;  border-color: rgb(0, 0, 0, 0.2);  box-shadow: 4px 4px 5px rgba(0, 0, 0, 0.3);  height:150px;"
                                            alt="<?php echo $plan['mp_name'] ?>">
                                        <img style=" 
                                height: 40px;
                                width: 40px;
                                position: absolute;
                                opacity: 0.5;" src="<?php echo $playimg; ?>" alt="play" class="playBtn">
                                    </div>


                                </button>

                            </form>
                            <div style="padding-left: 10px;">


                                <div class="d-flex justify-content-between align-items-center mb-1">

                                    <!-- Left Side : Plan Status -->
                                    <div>
                                        <h5>
                                            <?php
                                            if ($plan['learning_plan_status'] == '3')
                                                echo '<span class="badge bg-soft-success text-success p-1">' . lang('UI_Text.Completed') . '</span>';
                                            elseif ($plan['learning_plan_status'] == '2')
                                                echo '<span class="badge bg-soft-info text-info p-1">' . lang('UI_Text.In Progress') . '</span>';
                                            else {
                                                echo '<span class="badge bg-soft-warning text-warning p-1">' . lang('UI_Text.Not Started') . '</span>';
                                            }
                                            ?>
                                        </h5>
                                    </div>

                                    <!-- Right Side : Total Courses -->
                                    <!-- <div>
                                        <span class="fa-stack">
                                            <i class="fas fa-circle fa-stack-2x text-default"></i>
                                            <strong class="fa-stack-1x fa-inverse">
                                                <?= $plan['total_courses']; ?>
                                            </strong>
                                        </span>


                                    </div> -->

                                </div>

                                <h5 class="font-12 my-1 sp-line-1">
                                    <a class="text-dark"
                                        title="<?php echo $mp_name; ?>">
                                        <?php echo $shortened_name ?>
                                    </a>
                                </h5>


                                <span
                                    class="font-10 text-muted">
                                    <?php echo lang('UI_Text.Duration'); ?>
                                    :
                                    <?php if ($plan['duration'] > 0) { ?>
                                        <?php
                                        $duration = $plan['duration'];
                                        if ($duration > 60) {
                                            $hours = intdiv($duration, 60);
                                            echo $hours . ' ' . lang('UI_Text.Hours') . ' ';
                                            $balancemin = $duration - $hours * 60;
                                            if ($balancemin > 0) {
                                                echo $balancemin . ' ' . lang('UI_Text.Minutes');
                                            }
                                        } else {
                                            echo $duration . ' ' . lang('UI_Text.Minutes');
                                        }

                                        ?>
                                    <?php } ?></span>

                            </div>
                        </div>
                    </div>
                </div>
            <?php $j = $j + 1;
            }
            ?>
            <?php if (isset($plan['thumbnail']) && $plan['thumbnail'] != '') {
                $thumbnail = base_url('assets/assets/img/view_all_my_courses.png');
            } else {
                $thumbnail = "";
            } ?>

        </div> <!-- end col -->

        <!-- end row-->
    <?php

    } else { ?>
        <!-- <div class="persistent-warning">
            <div class="danger-text">
                <?php echo lang('UI_Text.No_Learning_Plan_Assigned'); ?>
            </div>
        </div> -->
    <?php }
} else { ?>
    <!-- <div class="persistent-warning">
        <div class="danger-text">
            <?php echo lang('UI_Text.No_Learning_Plan_Assigned'); ?>
        </div>
    </div> -->
<?php } ?>


<?php if ($get_certification != '') {
    if (count($get_certification) > 0) { ?>
        <h5 class="my-1"><?php echo lang('Buttons.Certifications'); ?></h5>
        <div id="holder" class="row row-cols-1 row-cols-md-3 g-3 ">

            <?php

            $j = 0;
            foreach ($get_certification as $key => $plan) {
                $mp_name = $plan['name'];

                $max_length = 39; // Maximum length of the string

                if (strlen($mp_name) > $max_length) {
                    $shortened_name = substr($mp_name, 0, $max_length) . ".."; // Append "..." for indication
                } else {
                    $shortened_name = $mp_name;
                }

                if ($j >= 4) {
                    break; // Exit the loop after 4 courses
                }
                if (isset($plan['thumbnail']) && $plan['thumbnail'] != '') {

                    $thumbnail = base_url('assets/assets/uploads/certification_path/' . $plan['cert_id'] . '/' . $plan['thumbnail']);
                } else {
                    $thumbnail = base_url('public/aristo_assets/images/default_thumbnail.png');
                }
                $playimg = base_url('assets/assets/img/play.png');
            ?>


                <div class="col-md-12 col-lg-4 col-xl-3">

                    <div class="card">

                        <div class="card-body" style="text-align:left; ">
                            <form class="form-horizontal"
                                action="<?php echo base_url('Certification/Certification_Portal/certificationDetails') ?>"
                                method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="certificate_id" value="<?php echo $plan['cert_id']; ?>">
                                <input type="hidden" name="cert_name" value="<?php echo $plan['cert_name']; ?>">
                                <input type="hidden" name="detail_type" value="1">
                                <?php //if ($plan['demo'] == 1) {
                                //echo '<input type="hidden" name="detail_type" value="3">';

                                // } else { 
                                echo ' <input type="hidden" name="type" value="4">';
                                // } 
                                ?>
                                <input type="hidden" name="tab" value="1">
                                <button
                                    style="border: none; border:0; padding-top: 5px; outline: none; background: none; width: 100%; text-align:center">
                                    <div style="display: box;
  display: flex;
  box-align: center;
  align-items: center;
  box-pack: center;
  justify-content: center;">
                                        <img class="img-fluid mx-auto d-block rounded" src="<?= $thumbnail ?>"
                                            style="border: 1px solid transparent; display: block;background: none;  border-color: rgb(0, 0, 0, 0.2);  box-shadow: 4px 4px 5px rgba(0, 0, 0, 0.3);  height:150px;"
                                            alt="<?php echo $plan['name'] ?>">
                                        <img style=" 
                                height: 40px;
                                width: 40px;
                                position: absolute;
                                opacity: 0.5;" src="<?php echo $playimg; ?>" alt="play" class="playBtn">
                                    </div>


                                </button>

                            </form>
                            <div style="padding-left: 10px;">
                                <div>
                                    <h5>
                                        <?php
                                        if ($plan['certification_status'] == '3')
                                            echo '<span class="badge bg-soft-success text-success p-1">' . lang('UI_Text.Completed') . '</span>';
                                        elseif ($plan['certification_status'] == '2')
                                            echo '<span class="badge bg-soft-info text-info p-1">' . lang('UI_Text.In Progress') . '</span>';
                                        else {
                                            echo '<span class="badge bg-soft-warning text-warning p-1">' . lang('UI_Text.Not Started') . '</span>';
                                        }
                                        ?>
                                    </h5>
                                </div>


                                <h5 class="font-12 my-1 sp-line-1">
                                    <a class="text-dark"
                                        title="<?php echo $mp_name; ?>">
                                        <?php echo $shortened_name ?>
                                    </a>
                                </h5>
                                <span
                                    class="font-10 text-muted">
                                    <?php echo lang('UI_Text.Duration'); ?>
                                    :
                                    <?php if ($plan['duration'] > 0) { ?>
                                        <?php
                                        $duration = $plan['duration'];
                                        if ($duration > 60) {
                                            $hours = intdiv($duration, 60);
                                            echo $hours . '  ' . lang('UI_Text.Hours') . ' ';
                                            $balancemin = $duration - $hours * 60;
                                            if ($balancemin > 0) {
                                                echo $balancemin . ' ' . lang('UI_Text.Minutes');
                                            }
                                        } else {
                                            echo $duration . ' ' . lang('UI_Text.Minutes');
                                        }

                                        ?>
                                    <?php } ?></span>




                            </div>
                        </div>
                    </div>
                </div>
            <?php $j = $j + 1;
            }
            ?>
            <?php if (isset($plan['thumbnail']) && $plan['thumbnail'] != '') {
                $thumbnail = base_url('assets/assets/img/view_all_my_courses.png');
            } else {
                $thumbnail = "";
            } ?>

        </div> <!-- end col -->

        <!-- end row-->
    <?php

    } else { ?>
        <!-- <div class="persistent-warning">
            <div class="danger-text">
                <?php echo lang('UI_Text.No_Learning_Plan_Assigned'); ?>
            </div>
        </div> -->
    <?php }
} else { ?>
    <!-- <div class="persistent-warning">
        <div class="danger-text">
            <?php echo lang('UI_Text.No_Learning_Plan_Assigned'); ?>
        </div>
    </div> -->
<?php } ?>
</div>

<!-- end row-->


</div>
</div>