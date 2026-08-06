<?php
$userlevel = session('userlevel');
$client = session('client');
$arrayuserlevel = array_map('intval', explode(',', $userlevel));
?>

<?php
$userlevel = session('userlevel');
$id_user = session('id_user');
$arrayuserlevel = array_map('intval', explode(',', $userlevel) ?? '');


?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header_link) ?>"><?php echo $header_title ?></a></li>
                </ol>
            </div>
            <h4 class="page-title"><?php echo $mp_name ?></h4>
        </div>
    </div>
</div>
<style>
    .completed-tick {
        position: absolute;
        top: 8px;
        right: 8px;
        background: #28a745;
        color: #fff;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        z-index: 20;
    }
</style>
<?php
$previousSequenceCompleted = true;

if ($course_details != '' && count($course_details) > 0) {

    $total_courses = count($course_details);
    $completed_courses = 0;

    foreach ($course_details as $course) {
        if ($course['course_status'] == 2) {
            $completed_courses++;
        }
    }

    $progress_percent = ($total_courses > 0)
        ? round(($completed_courses / $total_courses) * 100)
        : 0;



?>

    <div class="row">

        <!-- ================= LEFT SIDE (Courses + Progress) ================= -->
        <?php
        if (in_array('44', $arrayuserlevel) || in_array('5', $arrayuserlevel)) {
        ?>

            <?php if ($type != 4) { ?>
                <div class="col-lg-10">
                <?php } ?>
            <?php } else { ?>
                <div class="col-lg-12">
                <?php } ?>
                <div class="row">
                    <div class="col-lg-12 mb-1">
                        <?php if (!empty($course_details[0]['banner'])) {
                            $banner = base_url(
                                'assets/assets/uploads/learning_banner_path/' .
                                    $course_details[0]['mp_id'] . '/' .
                                    $course_details[0]['banner']
                            );
                        } else {
                            $banner = base_url('assets/assets/img/default_learning_plan_banner.jpg');
                        } ?>
                        <img src="<?= $banner ?>" class="img-fluid w-100 rounded" alt="<?php echo lang('UI_Text.Banner'); ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card mb-1">
                            <div class="card-body">
                                <!-- Learning learning_plan_details Description -->
                                <p class="mb-0"><?php echo $learning_plan_details['description'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card mb-1">
                            <div class="card-body py-2">
                                <div class="progress" style="height: 22px;">
                                    <div class="progress-bar progress-bar-striped bg-success" role="progressbar"
                                        style="width: <?php echo $progress_percent; ?>%;"
                                        aria-valuenow="<?php echo $progress_percent; ?>" aria-valuemin="0" aria-valuemax="100">
                                        <?php echo $progress_percent; ?>%
                                    </div>
                                </div>
                                <small class="text-muted">
                                    <?php echo $completed_courses; ?> / <?php echo $total_courses; ?> <?php echo lang('UI_Text.courses completed'); ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (!empty($certificate_assign) && ($progress_percent == 100)) {
                ?>
                    <div class="row">
                        <div class="col-lg-2  mb-1">
                            <!-- <a class="btn btn-outline-secondary btn-sm" href="<?php echo base_url('Certification/Dashboard/view_certificate/' . $learning_plan_details['mp_id'] . '/2'); ?>" target="_blank">
                                        <i class="mdi mdi-certificate-outline"></i> View Certificate
                                    </a><br /> -->
                            <form action="<?= base_url('Certification/Dashboard/view_certificate'); ?>" method="post" target="_blank" style="display:inline;">
                                <?= csrf_field(); ?>
                                <input type="hidden" name="course_mp_id" value="<?= $learning_plan_details['mp_id']; ?>">
                                <input type="hidden" name="type" value="2">
                                <button type="submit" class="btn btn-outline-secondary btn-sm">
                                    <i class="mdi mdi-certificate-outline"></i> <?php echo lang('Buttons.View Certificate'); ?>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php } ?>

                <!-- ================= COURSES GRID ================= -->
                <div class="row">
                    <?php
                    foreach ($course_details as $index => $clienteachCourseddata) {

                        $course_name = $clienteachCourseddata['course_name'];
                        $sequenceType = $mode;

                        if (!empty($clienteachCourseddata['thumbnail'])) {
                            $thumbnail = base_url(
                                'assets/assets/uploads/SCORM_course_thumbnail/' .
                                    $clienteachCourseddata['scourse_id'] . '/' .
                                    $clienteachCourseddata['thumbnail']
                            );
                        } else {
                            $thumbnail = base_url('public/aristo_assets/images/default_thumbnail.png');
                        }

                        $isCompleted = ($clienteachCourseddata['lesson_status'] == 'completed' ||
                            $clienteachCourseddata['lesson_status'] == 'passed');

                        $locked = false;
                        if ($sequenceType == 1 && $index > 0) {
                            for ($i = 0; $i < $index; $i++) {
                                $previousCourse = $course_details[$i];
                                $previousCompleted = ($previousCourse['lesson_status'] == 'completed' ||
                                    $previousCourse['lesson_status'] == 'passed');
                                if (!$previousCompleted) {
                                    $locked = true;
                                    break;
                                }
                            }
                        }
                    ?>

                        <div class="col-sm-12 col-md-6 col-lg-4 col-xxl-3">
                            <div class="card">
                                <div class="card-body">
                                    <form action="<?= base_url('my_training/read_more') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="crid" value="<?= $clienteachCourseddata['scourse_id']; ?>">
                                        <?php if ($type == 4) { ?>
                                            <input type="hidden" name="detail_type" value="11">
                                        <?php } else { ?>
                                            <input type="hidden" name="detail_type" value="9">
                                        <?php } ?>
                                        <input type="hidden" name="mp_id" value="<?= $mp_id; ?>">
                                        <input type="hidden" name="tab" value="1">

                                        <button
                                            <?= $locked ? 'disabled title="' . lang('UI_Text.Complete_Previous_Course') . '"' : ''; ?>
                                            style="border:none;background:none;width:100%;position:relative;">

                                            <div style="display:flex;justify-content:center;align-items:center;position:relative;">

                                                <img src="<?= $thumbnail ?>" class="img-fluid rounded"
                                                    style="height:150px;width:100%;object-fit:cover;
                                            border:1px solid rgba(0,0,0,0.2);
                                            box-shadow:4px 4px 5px rgba(0,0,0,0.2);">

                                                <?php if ($isCompleted): ?>
                                                    <div class="completed-tick">

                                                        <i class="mdi mdi-check-circle"></i>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ($locked): ?>
                                                    <div style="position:absolute;
                                                background:rgba(0,0,0,0.5);
                                                width:100%;height:150px;border-radius:5px;">
                                                    </div>
                                                <?php elseif (!$isCompleted): ?>
                                                    <img src="<?= base_url('assets/assets/img/play.png'); ?>"
                                                        style="height:40px;width:40px;position:absolute;opacity:0.7;">
                                                <?php endif; ?>

                                            </div>
                                        </button>
                                    </form>

                                    <!-- STATUS + NAME -->
                                    <div class="mt-3">

                                        <h6>
                                            <?php
                                            $status = $clienteachCourseddata['lesson_status'] ?? 'not started';

                                            if ($status == 'completed' || $status == 'passed') {
                                                echo '<span class="badge badge-soft-success rounded-pill">' . lang('UI_Text.Completed') . '</span>';
                                            } elseif ($status == 'incomplete') {
                                                echo '<span class="badge badge-soft-info rounded-pill">' . lang('UI_Text.In Progress') . '</span>';
                                            } elseif ($status == 'failed') {
                                                echo '<span class="badge badge-soft-info rounded-pill">' . lang('UI_Text.In Progress') . '</span>';
                                            } else {
                                                echo '<span class="badge badge-soft-warning rounded-pill">' . lang('UI_Text.Not Started') . '</span>';
                                            }
                                            ?>
                                        </h6>

                                        <h6 class="my-2">
                                            <a class="text-dark text-decoration-none">
                                                <?= $course_name; ?>
                                            </a>
                                        </h6>

                                        <small class="text-muted">
                                            <?php echo lang('UI_Text.Duration'); ?> : <?= $clienteachCourseddata['duration']; ?> <?php echo lang('UI_Text.Minutes'); ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                </div>

            <?php } else { ?>
                <div class="row align-items-start">

                    <!-- ================= LEFT SIDE (Courses + Progress) ================= -->
                    <?php
                    if (in_array('44', $arrayuserlevel) || in_array('5', $arrayuserlevel)) {
                    ?>
                        <div class="col-lg-10">
                            <div class="persistent-warning">
                                <div class="danger-text">
                                    <?php echo lang('UI_Text.No_Course_Assigned'); ?>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="col-lg-12">
                                <div class="persistent-warning">
                                    <div class="danger-text">
                                        <?php echo lang('UI_Text.No_Course_Assigned'); ?>
                                    </div>
                                </div>
                            <?php } ?>
                            </div>
                        <?php  } ?>
                        <!-- ================= RIGHT SIDE (Administration Panel) ================= -->
                        <?php
                        $userlevel = session()->get('userlevel');
                        $arrayuserlevel = explode(',', $userlevel);
                        if (in_array('44', $arrayuserlevel) || in_array('5', $arrayuserlevel)) {
                            // print_r($type);
                        ?>
                            <?php if ($type != 4) { ?>
                                <div class="col-lg-2">

                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="mb-0 mt-0 text-uppercase bg-light p-2"> <?php echo lang('UI_Text.Administration'); ?></h6>
                                            <form action="<?php echo base_url('marketplace/admin/edit_marketplace') ?>"
                                                method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="mp_name"
                                                    value="<?php echo $learning_plan_details['mp_name']; ?>">
                                                <input type="hidden" name="mp_id"
                                                    value="<?php echo $learning_plan_details['mp_id']; ?>">
                                                <?php
                                                echo ' <input type="hidden" name="type" value="2">';
                                                ?>
                                                <button type="submit" class="btn btn-block"><?php echo lang('Buttons.LP Settings'); ?></button>


                                            </form>

                                            <?php if (session()->get('client') == 1) { ?>

                                                <form action="<?php echo base_url('marketplace/admin/edit_client') ?>"
                                                    method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="mp_name"
                                                        value="<?php echo $learning_plan_details['mp_name']; ?>">
                                                    <input type="hidden" name="mp_id"
                                                        value="<?php echo $learning_plan_details['mp_id']; ?>">
                                                    <?php
                                                    echo ' <input type="hidden" name="type" value="2">';
                                                    ?>
                                                    <button type="submit"
                                                        class="btn btn-block">
                                                        <!-- <i class="mdi mdi-account-group-outline"></i> -->
                                                        <?php echo lang('Buttons.Assign_Clients'); ?>
                                                    </button>

                                                </form>
                                            <?php } ?>
                                            <form action="<?php echo base_url('marketplace/admin/edit_courses') ?>"
                                                method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="mp_name"
                                                    value="<?php echo $learning_plan_details['mp_name']; ?>">
                                                <input type="hidden" name="mp_id"
                                                    value="<?php echo $learning_plan_details['mp_id']; ?>">
                                                <?php
                                                echo ' <input type="hidden" name="type" value="2">';
                                                ?>
                                                <button type="submit"
                                                    class="btn btn-block">
                                                    <!-- <i class="mdi mdi-youtube-tv"></i> -->
                                                    <?php echo lang('Buttons.Link_Courses'); ?>
                                                </button>

                                            </form>
                                            <form action="<?php echo base_url('marketplace/Learning_dashboard/add_users_to_learning_plan_view'); ?>"
                                                method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="cid" value="MQ==">
                                                <input type="hidden" name="mp_id" value="<?php echo $learning_plan_details['mp_id']; ?>">
                                                <?php //if ($clienteachCourseddata['demo'] == 1) {
                                                //echo '<input type="hidden" name="detail_type" value="3">';
                                                // } else { 
                                                echo ' <input type="hidden" name="type" value="2">';
                                                // } 
                                                ?> <button type="submit"
                                                    class="btn btn-block">
                                                    <!-- <i class="mdi mdi-account-outline"></i> -->
                                                    <?php echo lang('Buttons.Assign_Users'); ?>
                                                </button>

                                            </form>


                                        </div>
                                    </div>

                                </div>
                                <div class="modal fade" id="editMarketplaceModal<?= $learning_plan_details['mp_id']; ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content">

                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h5 class="modal-title"><?php echo lang('UI_Text.Edit_Learning_Plan'); ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <!-- Modal Body -->
                                            <div class="modal-body">

                                                <form class="form-horizontal"
                                                    action="<?= base_url('marketplace/admin/update_marketplace_name') ?>"
                                                    method="post"
                                                    id="submitForm"><?= csrf_field() ?>

                                                    <div class="row mb-3">
                                                        <label class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Name'); ?></label>
                                                        <div class="col-8 col-xl-9">
                                                            <input type="text" class="form-control"
                                                                name="marketplace_name"
                                                                value="<?= $learning_plan_details['mp_name']; ?>" required>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Duration_Min'); ?></label>
                                                        <div class="col-8 col-xl-9">
                                                            <input type="number" class="form-control"
                                                                name="duration" min="0"
                                                                value="<?= $learning_plan_details['duration']; ?>" required>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <label class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Mode'); ?></label>
                                                        <div class="col-8 col-xl-9">
                                                            <select class="form-select" name="mode" required>
                                                                <option value="1" <?= ($learning_plan_details['mode'] == 1) ? 'selected' : '' ?>>
                                                                    <?php echo lang('UI_Text.Sequential'); ?>
                                                                </option>
                                                                <option value="2" <?= ($learning_plan_details['mode'] == 2) ? 'selected' : '' ?>>
                                                                    <?php echo lang('UI_Text.Non_Sequential'); ?>
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>


                                                    <div class="row mb-3">
                                                        <label class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Description'); ?></label>
                                                        <div class="col-8 col-xl-9">
                                                            <textarea class="ckeditor"
                                                                name="description"
                                                                required><?= $learning_plan_details['description']; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-4 col-xl-3 col-form-label"><?php echo lang('UI_Text.Delete_LP'); ?></label>
                                                        <div class="col-8 col-xl-9">
                                                            <select class="form-select" name="status" required>
                                                                <option value="0" <?= ($learning_plan_details['status'] == 0) ? 'selected' : '' ?>>
                                                                    <?php echo lang('UI_Text.Yes'); ?>
                                                                </option>
                                                                <option value="1" <?= ($learning_plan_details['status'] == 1) ? 'selected' : '' ?>>
                                                                    <?php echo lang('UI_Text.No'); ?>
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <!-- Hidden Fields -->
                                                    <input type="hidden" name="remarks" value="<?= $learning_plan_details['remarks']; ?>">
                                                    <input type="hidden" name="language" value="<?= $learning_plan_details['language']; ?>">
                                                    <input type="hidden" name="mp_id" value="<?= $learning_plan_details['mp_id']; ?>">
                                                    <input type="hidden" name="type" value="<?= $learning_plan_details['type'] ?? 1; ?>">

                                                    <!-- Footer Buttons -->
                                                    <div class="text-center">
                                                        <button type="submit"
                                                            class="btn btn-outline-warning btn-sm"
                                                            id="submitButton">
                                                            <?php echo lang('Buttons.Update'); ?>
                                                        </button>
                                                    </div>

                                                </form>
                                                <!-- 🔼 YOUR FORM ENDS HERE 🔼 -->

                                            </div>
                                        </div>
                                    </div>
                                </div>

                        </div>
                    <?php } ?>
                <?php }
                ?>