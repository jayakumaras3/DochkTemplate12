<?php
$userlevel = session()->get('userlevel');
$arrayuserlevel = explode(',', $userlevel);
//print_r($arrayuserlevel);
?>
<!-- <div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <?php if ($detail_type == 1) { ?>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('my_training') ?>"><?php echo lang('UI_Text.Dashboard'); ?></a></li>
                    <?php } else { ?>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('Certification/certification_dashboard') ?>"><?php echo lang('UI_Text.Certifications'); ?></a></li>
                    <?php } ?>
                </ol>
            </div>
            <?php
            $completestatus = array_column($get_certification_learning_plan_courses, 'learning_plan_status');
            if (!empty($completestatus) && count(array_unique($completestatus)) === 1 && $completestatus[0] == 3) {
            ?>
                <form action="<?= base_url('Certification/Dashboard/view_certificate'); ?>" method="post" target="_blank" style="display:inline;">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="course_mp_id" value="<?= $certificate_id; ?>">
                    <input type="hidden" name="type" value="4">
                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                        <i class="mdi mdi-certificate-outline"></i> <?= lang('Buttons.View Certificate'); ?>
                    </button>
                </form>
            <?php } ?>
            <h4 class="page-title"><?php echo isset($cert_name) ? $cert_name : $get_certification_learning_plan_courses[0]['cert_name']; ?></h4>
        </div>
    </div>
</div> -->
<div class="row align-items-center py-2">

    <!-- Left (was Right): Title -->
    <div class="col-md-4">
         <h4 class="page-title">
            <?= isset($cert_name) ? $cert_name : $get_certification_learning_plan_courses[0]['cert_name']; ?>
        </h4>
    </div>

    <!-- Center: Button -->
    <div class="col-md-4 text-center">

        <?php
        $completestatus = array_column($get_certification_learning_plan_courses, 'learning_plan_status');
        if (!empty($completestatus) && count(array_unique($completestatus)) === 1 && $completestatus[0] == 3) {
        ?>
            <form action="<?= base_url('Certification/Dashboard/view_certificate'); ?>" method="post" target="_blank" style="display:inline;">
                <?= csrf_field(); ?>
                <input type="hidden" name="course_mp_id" value="<?= $certificate_id; ?>">
                <input type="hidden" name="type" value="4">

                <button type="submit" class="btn btn-outline-secondary btn-sm px-3">
                    <i class="mdi mdi-certificate-outline"></i>
                    <?= lang('Buttons.View Certificate'); ?>
                </button>
            </form>
        <?php } ?>

    </div>

    <!-- Right (was Left): Breadcrumb -->
    <div class="col-md-4 text-end">
        <ol class="breadcrumb m-0 small text-muted justify-content-end">
            <?php if ($detail_type == 1) { ?>
                <li class="breadcrumb-item">
                    <a href="<?= base_url('my_training') ?>" class="text-decoration-none">
                        <?= lang('UI_Text.Dashboard'); ?>
                    </a>
                </li>
            <?php } else { ?>
                <li class="breadcrumb-item">
                    <a href="<?= base_url('Certification/certification_dashboard') ?>" class="text-decoration-none">
                        <?= lang('UI_Text.Certifications'); ?>
                    </a>
                </li>
            <?php } ?>
        </ol>
    </div>

</div>
<div class="row">
    <?php if (in_array('44', $arrayuserlevel) || in_array('5', $arrayuserlevel)) { ?>
        <div class="col-lg-10">
        <?php } else { ?>
            <div class="col-lg-12">
            <?php } ?>
            <?php if (!empty($get_certification_learning_plan_courses)) { ?>

                <div class="card">
                    <div class="card-body">
                        <table class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th class="center">#</th>
                                    <th><?php echo lang('UI_Text.Modules'); ?></th>
                                    <th><?php echo lang('UI_Text.Duration'); ?></th>
                                    <?php
                                    $userlevel = session()->get('userlevel');
                                    $arrayuserlevel = explode(',', $userlevel);
                                    if (in_array('44', $arrayuserlevel) || in_array('5', $arrayuserlevel)) {
                                    ?>
                                    <?php } ?>
                                    <th><?php echo lang('UI_Text.Status'); ?></th>
                                    <th><?php echo lang('UI_Text.Action'); ?></th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php $j = 0;
                                foreach ($get_certification_learning_plan_courses as $plan) {

                                    $j = $j + 1; ?>
                                    <tr>
                                        <td class="center"><?php echo $j ?></td>
                                        <td><?php echo $plan['mp_name'] ?></td>
                                        <td>
                                            <?php if ($plan['duration'] > 0) { ?>
                                                <?php
                                                $duration = $plan['duration'];
                                                if ($duration > 60) {
                                                    $hours = intdiv($duration, 60);
                                                    echo $hours . ' Hrs. ';
                                                    $balancemin = $duration - $hours * 60;
                                                    if ($balancemin > 0) {
                                                        echo $balancemin . ' min';
                                                    }
                                                } else {
                                                    echo $duration . ' min';
                                                }

                                                ?></br>
                                            <?php } ?>
                                        </td>
                                        <td><?php
                                            if ($plan['learning_plan_status'] == '3')
                                                echo '<span class="badge bg-soft-success text-success p-1">' . lang('UI_Text.Completed') . '</span>';
                                            elseif ($plan['learning_plan_status'] == '2')
                                                echo '<span class="badge bg-soft-info text-info p-1">' . lang('UI_Text.In Progress') . '</span>';
                                            else
                                                echo '<span class="badge bg-soft-warning text-warning p-1">' . lang('UI_Text.Not Started') . '</span>';
                                            ?>
                                        </td>
                                        <td>
                                            <form class="form-horizontal"
                                                action="<?php echo base_url('marketplace/Learning_dashboard/learning_courses') ?>"
                                                method="POST"><?= csrf_field() ?>
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="mp_id" value="<?php echo $plan['mp_id']; ?>">
                                                <input type="hidden" name="mode" value="<?php echo $plan['mode']; ?>">
                                                <input type="hidden" name="mp_name" value="<?php echo $plan['mp_name']; ?>">
                                                <?php //if ($clienteachCourseddata['demo'] == 1) {
                                                //echo '<input type="hidden" name="detail_type" value="3">';
                                                // } else { 
                                                echo ' <input type="hidden" name="type" value="4">';
                                                // } 
                                                ?>
                                                <input type="hidden" name="tab" value="1">

                                                <button type="submit" class="btn btn-outline-info waves-effect btn-xs waves-light "><?php echo lang('Buttons.View'); ?></button>
                                                </button>
                                            </form>
                                        </td>
                                    <?php
                                } ?>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php
            } else { ?>
                <div class="persistent-warning">
                    <div class="danger-text">
                        <?php echo lang('Statements.State_0011'); ?>
                    </div>
                </div>
            <?php } ?>
            </div>

            <?php if (in_array('44', $arrayuserlevel) || in_array('5', $arrayuserlevel)) { ?>
                <div class="col-lg-2">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-0 mt-0 text-uppercase bg-light p-2"> <?php echo lang('UI_Text.Administration'); ?></h6>
                            <?php if (isset($getassignedclienttoeditable) && $getassignedclienttoeditable[0]['can_edit'] == 1) { ?>
                                <form action="<?php echo base_url('Certification/certification_dashboard/Edit_certificate') ?>" method="POST"><?= csrf_field() ?>
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="certificate_id" value="<?php echo $certificate_id; ?>">
                                    <button type="submit" class="btn btn-block"><?php echo lang('Buttons.Settings'); ?></button>
                                    </button>
                                </form>
                                <form action="<?php echo base_url('Certification/certification_dashboard/Assign_certificate') ?>" method="POST"><?= csrf_field() ?>
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="certificate_id" value="<?php echo $certificate_id; ?>">

                                    <button type="submit" class="btn btn-block"><?php echo lang('Buttons.Assign_LP'); ?></button>
                                </form>
                            <?php } ?>
                            <form action="<?php echo base_url('Certification/certification_dashboard/Assign_user_to_certification_view') ?>" method="POST"><?= csrf_field() ?>
                                <?= csrf_field() ?>
                                <input type="hidden" name="certificate_id" value="<?php echo $certificate_id; ?>">
                                <input type="hidden" name="type" value="<?php echo $type; ?>">
                                <button type="submit" class="btn btn-block"><?php echo lang('Buttons.Assign_Users'); ?></button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php
            } ?>
        </div>