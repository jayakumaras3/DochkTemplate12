<?php $userlevel = session()->get('userlevel');
$arrayuserlevel = array_map('intval', explode(',', $userlevel));
$client = session()->get('client');
?>
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

    .cardtile {
        padding: 10px;
        background-color: rgba(255, 255, 255, 0.1);
        margin-right: 1px;
        margin-left: 1px;
        border-radius: 10px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.1), 0 6px 20px 0 rgba(0, 0, 0, 0.02);
    }

    .cardshaddow {
        border-radius: 10px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.1), 0 6px 20px 0 rgba(0, 0, 0, 0.02);
    }

    /* Course detail redesign */
    .course-thumb-wrap {
        position: relative;
        border-radius: 15px;
        overflow: hidden;
        border: 1px solid #ccc;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
        /* Thumbnail upload only validates width (fixed at 480px) - height is
           unconstrained, so without a fixed aspect ratio here an unusually tall
           or short upload scales unpredictably as this column's width changes
           across breakpoints. Pinning the ratio keeps every course's thumbnail
           the same shape and properly responsive at every screen size. */
        aspect-ratio: 16 / 9;
        width: 100%;
    }

    [data-bs-theme="dark"] .course-thumb-wrap {
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.5);
    }

    .course-thumb-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .course-action-row .persistent-warning {
        flex-basis: 100%;
    }

    .course-name-divider {
        margin: .75rem .5rem;
        border-top: 1px solid var(--ct-border-color);
    }

    .course-footer-item .course-meta-label {
        display: block;
        color: var(--ct-secondary-color);
        font-size: .7rem;
    }

    .course-footer-item .course-meta-value {
        display: block;
        font-weight: 600;
        color: var(--ct-body-color);
    }

    .course-favorite-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 1px solid var(--ct-border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .course-favorite-btn i {
        font-size: 1.4rem;
    }

    .stat-tile-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .75rem;
        margin-bottom: 1rem;
    }

    .stat-tile {
        display: flex;
        align-items: center;
        gap: .75rem;
        padding: .75rem;
        border: 1px solid var(--ct-border-color);
        border-radius: 10px;
    }

    .stat-tile-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: rgba(var(--ct-primary-rgb), 0.1);
        color: var(--ct-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.1rem;
    }

    .stat-tile-label {
        display: block;
        color: var(--ct-secondary-color);
        font-size: .7rem;
    }

    .stat-tile-value {
        display: block;
        font-weight: 600;
        color: var(--ct-body-color);
    }

    .about-course-box {
        background: var(--ct-card-bg);
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06), 0 2px 6px rgba(0, 0, 0, 0.04);
    }

    [data-bs-theme="dark"] .about-course-box {
        border-color: #424e5a;
    }

    .about-course-title {
        font-weight: 600;
        margin-bottom: .5rem;
    }

    /* Description/objectives are rich-text HTML saved from the course editor, so they
       can carry their own inline color/style from whoever authored them - the `*`
       + !important here overrides that unconditionally to the current theme's body
       color, instead of leaving contrast to whatever was baked into the saved HTML. */
    .about-course-content,
    .about-course-content * {
        color: var(--ct-body-color) !important;
    }

    /* --ct-body-color is only a muted #94a0ad in dark mode - readable enough on plain
       backgrounds, but not the clearly-legible shade this app otherwise uses for text
       on dark cards. Force the same light color used everywhere else in dark mode. */
    [data-bs-theme="dark"] .about-course-content,
    [data-bs-theme="dark"] .about-course-content * {
        color: #cedeef !important;
    }

    /* Every .card on this page (main content card, administration-card, enrolled-learners-card)
       rounded and shadowed to match administration-card. Cards below with their own box-shadow
       (administration-card, enrolled-learners-card) override this via their more specific selector. */
    .card {
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06), 0 2px 6px rgba(0, 0, 0, 0.04);
        border: none;
    }

    .administration-card {
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06), 0 2px 6px rgba(0, 0, 0, 0.04);
        border: none;
    }

    .administration-card .section-title {
        display: flex;
        align-items: center;
        gap: .5rem;
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: .75rem;
    }

    .administration-card .section-title i {
        color: var(--ct-primary);
    }

    .administration-card form {
        display: block;
        margin-bottom: 0;
    }

    .administration-card form+form {
        border-top: 1px solid var(--ct-border-color);
        margin-top: .15rem;
        padding-top: .15rem;
    }

    .administration-card .quick-action-item {
        display: flex;
        align-items: center;
        width: 100%;
        gap: .65rem;
        padding: .65rem .25rem;
        border: none;
        background: none;
        text-align: left;
        color: var(--ct-body-color);
        font-weight: 600;
        font-size: .875rem;
        border-radius: 10px;
    }

    .administration-card .quick-action-item:hover {
        background-color: rgba(var(--ct-primary-rgb), 0.08);
    }

    .administration-card .quick-action-item .mdi:first-child {
        font-size: 1.1rem;
        color: var(--ct-body-color);
        flex-shrink: 0;
    }

    .administration-card .quick-action-item .label-text {
        flex-grow: 1;
    }

    .administration-card .quick-action-item .mdi-chevron-right {
        color: var(--ct-secondary-color);
        flex-shrink: 0;
    }

    .enrolled-learners-card {
        background: var(--ct-primary);
        color: #fff;
        border: none;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
    }

    [data-bs-theme="dark"] .enrolled-learners-card {
        background: #4a3fae;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.45);
    }

    .enrolled-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-bottom: .5rem;
    }

    .enrolled-label {
        font-size: .8rem;
        opacity: .85;
    }

    .enrolled-value {
        font-size: 1.5rem;
        font-weight: 700;
    }

    .course-footer-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-top: 1rem;
        padding-top: 1rem;
        margin-bottom: 1rem;
        padding-bottom: 1rem;

    }

    .course-footer-item {
        display: flex;
        align-items: center;
        gap: .5rem;
        font-size: .8rem;
    }

    .course-footer-item i {
        font-size: 1.1rem;
        color: var(--ct-primary);
    }

    .course-footer-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: rgba(var(--ct-primary-rgb), 0.15);
        color: var(--ct-primary);
        font-weight: 600;
        font-size: .75rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
<?php
$paymentStatus  = session()->getFlashdata('payment_status');
$paymentMessage = session()->getFlashdata('payment_message');
?>

<?php if ($paymentStatus): ?>
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-4">

                <div class="modal-body">

                    <?php if ($paymentStatus === 'success'): ?>
                        <div class="mb-3">
                            <i class="bi bi-check-circle-fill text-success" style="font-size:60px;"></i>
                        </div>
                        <h4 class="text-success"><?= lang('UI_Text.Payment Successful') ?></h4>
                    <?php else: ?>
                        <div class="mb-3">
                            <i class="bi bi-x-circle-fill text-danger" style="font-size:60px;"></i>
                        </div>
                        <h4 class="text-danger"><?= lang('UI_Text.Payment Failed') ?></h4>
                    <?php endif; ?>

                    <p class="mt-3"><?= $paymentMessage ?></p>

                    <div class="mt-4">
                        <button class="btn <?= $paymentStatus === 'success' ? 'btn-success' : 'btn-danger' ?>"
                            data-bs-dismiss="modal">
                            <?= lang('Buttons.Close'); ?>
                        </button>

                    </div>

                </div>

            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($paymentStatus): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = new bootstrap.Modal(document.getElementById('paymentModal'), {
                backdrop: 'static', // prevent closing by clicking outside
                keyboard: false // prevent ESC close (optional)
            });
            modal.show();
        });
    </script>
<?php endif; ?>


<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">

                    <li class="breadcrumb-item"><a
                            href="<?php echo base_url($header_link); ?>"><?php echo $header; ?></a>

                </ol>
            </div>
            <h4 class="page-title"><?= lang('UI_Text.Course Details') ?></h4>

        </div>
    </div>
</div>
<!-- end page title -->
<?php if (isset($clientCourseddata) && $clientCourseddata != '') {
    if (count($clientCourseddata) > 0) {
        if ($clientCourseddata[0]['type'] == 1) {
            $demoButton = '<span class="btn-label"><i class="icon-social-youtube"></i></span>' . lang('UI_Text.Preview');
        }
        if ($clientCourseddata[0]['type'] == 2) {
            $demoButton = '<span class="btn-label"><i class="icon-social-youtube"></i></span>' . lang('UI_Text.Preview');
        }
        if ($clientCourseddata[0]['type'] == 5) {
            $demoButton = '<span class="btn-label"><i class="icon-social-youtube"></i></span>' . lang('UI_Text.Preview');
        } else {
            $demoButton = '<span class="btn-label"><i class="icon-social-youtube"></i></span>' . lang('UI_Text.Preview');
        }
?>
        <div class="row">
            <?php   // print_r($detail_type); 
            ?>
            <?php
            if (
                in_array('4', $arrayuserlevel) || in_array('46', $arrayuserlevel) ||
                in_array('6', $arrayuserlevel) || in_array('67', $arrayuserlevel) ||
                in_array('5', $arrayuserlevel) || in_array('45', $arrayuserlevel) ||
                in_array('44', $arrayuserlevel)

            ) {
                if ($detail_type != 11) {
                    echo '<div class="col-lg-9">';
                }
            } else {
                echo '<div class="col-lg-12">';
            }

            $course_status = $clientCourseddata[0]['mode'];
            $lesson_status = $clientCourseddata[0]['lesson_status'] ?? '';

            ?>


            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex align-items-start justify-content-between">
                                <h4 class="mb-3 flex-grow-1" style="min-width: 0;">
                                    <?= $clientCourseddata[0]['course_name'] ?? ''; ?>
                                </h4>
                                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                    <?php if (!empty($getCoursesAssigned[0]['user_assign_id'])) { ?>
                                        <form action="<?= base_url('my_training/unenroll') ?>" method="POST" class="d-none d-md-inline-block mb-0">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="crid" value="<?= $crid ?>">
                                            <button type="submit" onclick="return confirm('<?= lang('Alert.Aler_004') ?>')"
                                                class="btn btn-outline-danger waves-effect btn-sm waves-light rounded-pill">
                                                <i class="mdi mdi-stop-circle"></i> <?= lang('Buttons.Un_Enroll') ?>
                                            </button>
                                        </form>
                                    <?php } ?>

                                    <?php if (count($ifFavorite) > 0) { ?>
                                        <a href="<?= base_url('my_training/remove_favorite/' . $ifFavorite[0]['fav_id']); ?>" class="course-favorite-btn d-none d-md-inline-flex">
                                            <i class="mdi mdi-cards-heart" style="color: red;"></i></a>
                                    <?php } else { ?>
                                        <a href="<?= base_url('my_training/add_favorite' . '/' . $crid); ?>" class="course-favorite-btn d-none d-md-inline-flex">
                                            <i class="mdi mdi-heart-outline text-muted"></i></a>
                                    <?php
                                    } ?>
                                </div>
                            </div>
                            <div class="course-name-divider"></div>
                        </div>
                    </div>
                    <div class="row">

                        <!-- LEFT SIDE IMAGE -->
                        <div class="col-lg-4">

                            <?php
                            if (!empty($clientCourseddata[0]['thumbnail'])) {
                                $thumbnail = base_url('assets/assets/uploads/SCORM_course_thumbnail/' .
                                    $clientCourseddata[0]['scourse_id'] . '/' .
                                    $clientCourseddata[0]['thumbnail']);
                            } else {
                                $thumbnail = base_url('public/aristo_assets/images/default_thumbnail.png');
                            }
                            ?>

                            <div class="course-thumb-wrap">
                                <img src="<?= $thumbnail ?>"
                                    class="img-fluid rounded course-thumb-img"
                                    style="border-radius:15px;">
                            </div>
                            <div class="mt-4 d-flex flex-wrap gap-2 align-items-center course-action-row">

                                <?php //print_r($getCoursesAssigned);
                                if ($clientCourseddata[0]['mode'] == 1) { ?>
                                    <div class="persistent-warning">
                                        <div class="danger-text">
                                            <?= lang('Statements.State_0013') ?>
                                        </div>
                                    </div>
                                    <?php }
                                if (!empty($getCoursesAssigned[0]['scourse_id'])) {
                                    if (isset($pagedata[0]['page_id'])) {
                                        // echo $clientCourseddata[0]['type'];

                                    ?>
                                        <?php if ($clientCourseddata[0]['type'] == 11) {

                                        ?>
                                            <?php if ($course_status == '2' || $getCoursesAssigned[0]['course_status'] == '3') {
                                                if ($detail_type == 5 && $payment_type == 2 && $billing_cycle == 1) {
                                                    if (!empty($getbillingdata) || $price <= 0) {
                                                        // print_r("ttt");
                                            ?>
                                                        <form method="POST" onsubmit="LaunchCourse(this)"><?= csrf_field() ?>
                                                            <button class="btn btn-outline-success waves-effect btn-sm waves-light rounded-pill mb-3"
                                                                onclick="openForm()"><i class="mdi mdi-play-circle-outline me-2"></i>
                                                                Launch</button>
                                                        </form>
                                                    <?php } else { ?>
                                                        <form method="POST" onsubmit="LaunchCourse(this)"><?= csrf_field() ?>
                                                            <button class="btn btn-outline-success waves-effect btn-sm waves-light rounded-pill mb-3"
                                                                onclick="openForm()"><i class="mdi mdi-play-circle-outline me-2"></i>
                                                                <?= lang('Buttons.Launch') ?></button>
                                                        </form>
                                                    <?php

                                                    } ?>
                                                <?php  } elseif ($detail_type == '5' && $payment_type == '2' && $billing_cycle == '2') { ?>
                                                    <?php if (!empty($getbillingdata)) {
                                                        //print_r("ttt"); 
                                                    ?>
                                                        <form method="POST" onsubmit="LaunchCourse(this)"><?= csrf_field() ?>
                                                            <button class="btn btn-outline-success waves-effect btn-sm waves-light rounded-pill mb-3"
                                                                onclick="openForm()"><i class="mdi mdi-play-circle-outline me-2"></i>
                                                                <?= lang('Buttons.Launch') ?></button>
                                                        </form>
                                                    <?php } ?>
                                                <?php } else {
                                                    // print_r("ss"); 
                                                ?>
                                                    <form method="POST" onsubmit="LaunchCourse(this)"><?= csrf_field() ?>
                                                        <button class="btn btn-outline-success waves-effect btn-sm waves-light rounded-pill mb-3"
                                                            onclick="openForm()"><i class="mdi mdi-play-circle-outline me-2"></i>
                                                            <?= lang('Buttons.Launch') ?></button>
                                                    </form>
                                                <?php } ?>
                                            <?php } else { ?>

                                                <?php if ($clientCourseddata[0]['mode'] == 1) {
                                                } else { ?>
                                                    <form method="POST" onsubmit="LaunchCourse(this)"><?= csrf_field() ?>
                                                        <button class="btn btn-outline-danger waves-effect btn-sm waves-light rounded-pill mb-3"
                                                            onclick="openForm()"><i class="mdi mdi-play-circle-outline me-2"></i>
                                                            <?= lang('Buttons.Review') ?></button>
                                                    </form>
                                                <?php } ?>
                                            <?php } ?>


                                        <?php } ?>
                                    <?php } else { ?>
                                        <?php if ($clientCourseddata[0]['type'] == 11) { ?>
                                            <div class="persistent-warning">
                                                <div class="danger-text">
                                                    <?= lang('Statements.State_0012') ?>
                                                </div>
                                            </div>
                                    <?php }
                                    } ?>

                                <?php } else { ?>
                                    <?php if ($detail_type == '5' && $payment_type == '2' && $price >= 0) {
                                    ?>
                                        <form method="POST" onsubmit="LaunchCourse(this)"><?= csrf_field() ?>
                                            <button class="btn btn-outline-success waves-effect btn-sm waves-light rounded-pill mb-3"
                                                onclick="openForm()"><i class="mdi mdi-play-circle-outline me-2"></i>
                                                <?= lang('Buttons.Launch') ?></button>
                                        </form>
                                    <?php
                                    } else {
                                        // print_r($price);  
                                    ?>
                                        <form method="POST" onsubmit="LaunchCourse(this)"><?= csrf_field() ?>
                                            <button class="btn btn-outline-success waves-effect btn-sm waves-light rounded-pill mb-3"
                                                onclick="openForm()"><i class="mdi mdi-play-circle-outline me-2"></i>
                                                <?= lang('Buttons.Launch') ?></button>
                                        </form>
                                <?php }
                                } ?>
                                <?php if (isset($clientCourseddata[0]['upload']) && $clientCourseddata[0]['upload'] != '') {
                                    $session_Value = [];

                                    $session_Value = [
                                        'course_id' => $clientCourseddata[0]['scourse_id'],
                                        'foldername' => $clientCourseddata[0]['upload'],
                                        'type' => $type,

                                    ];
                                    session()->set($session_Value);
                                ?>

                                    <?php if ($clientCourseddata[0]['type'] == '10') { ?>
                                        <?php if ($clientCourseddata[0]['type'] === 5) { ?>
                                            <a
                                                onclick="OpenNewWindow('<?php echo base_url('SCORM/scorm_launch/tinCanlanch'); ?>')">
                                                <button type="button"
                                                    class="btn btn-outline-success waves-effect btn-sm waves-light rounded-pill mb-3"><span
                                                        class="btn-label"><i
                                                            class="icon-control-play"></i></span><?= lang('Buttons.Launch') ?></button></a>
                                        <?php } else {
                                            // print_r($course_status); 
                                        ?>
                                            <?php if ($course_status === '2' || isset($getCoursesAssigned[0]['course_status']) && $getCoursesAssigned[0]['course_status'] === '3') {
                                                // print_r($course_status);
                                            ?>
                                                <?php if ($detail_type === 5 && $payment_type === 2 && $billing_cycle === 1) {
                                                    if (!empty($getbillingdata) || $price <= 0) { ?>
                                                        <a onclick="OpenNewWindow('<?php echo base_url('SCORM/scorm_launch'); ?>')">
                                                            <button type="button"
                                                                class="btn btn-outline-success waves-effect btn-sm waves-light rounded-pill mb-3"><span
                                                                    class="btn-label"><i
                                                                        class="icon-control-play"></i></span><?= lang('Buttons.Launch') ?></button></a>
                                                    <?php } else { ?>
                                                        <form method="POST" action="<?php echo base_url('stripe/Checkout/payCourse'); ?>"><?= csrf_field() ?>
                                                            <input type="hidden" name="course_price" value="<?php echo $price ?>">
                                                            <input type="hidden" name="billing_cycle" value="<?php echo $billing_cycle; ?>">
                                                            <input type="hidden" name="scourse_id" value="<?php echo $crid; ?>">
                                                            <input type="hidden" name="currency" value="<?php echo $currency; ?>" ?>
                                                            <button class="btn btn-outline-danger waves-effect btn-sm waves-light rounded-pill mb-3"><i class="mdi mdi-credit-card me-2"></i>
                                                                <?php echo '$ ' . $price ?></button>
                                                        </form>

                                                    <?php } ?>
                                                <?php  } elseif ($detail_type === 5 && $payment_type === 2 && $billing_cycle == 2) { ?>
                                                    <?php if (!empty($getsubscribebillingdata)) { ?>
                                                        <a onclick="OpenNewWindow('<?php echo base_url('SCORM/scorm_launch'); ?>')">
                                                            <button type="button"
                                                                class="btn btn-outline-success waves-effect btn-sm waves-light rounded-pill mb-3"><span
                                                                    class="btn-label"><i
                                                                        class="icon-control-play"></i></span><?= lang('Buttons.Launch') ?></button></a>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <a onclick="OpenNewWindow('<?php echo base_url('SCORM/scorm_launch'); ?>')">
                                                        <button type="button"
                                                            class="btn btn-outline-success waves-effect btn-sm waves-light rounded-pill mb-3"><span
                                                                class="btn-label"><i
                                                                    class="icon-control-play"></i></span>Launch</button></a>
                                                <?php } ?>

                                            <?php } else { ?>
                                                <?php if ($clientCourseddata[0]['mode'] == 1) { ?>

                                                <?php } else { ?>
                                                    <?php if (isset($pagedata[0]['page_id'])) {
                                                        if ($clientCourseddata[0]['type'] == '10') { ?>
                                                            <a
                                                                onclick="OpenNewWindow('<?php echo base_url('SCORM/scorm_launch/review/' . $pagedata[0]['page_id']); ?>')">
                                                                <button type="button"
                                                                    class="btn btn-outline-danger waves-effect btn-sm waves-light rounded-pill mb-3"><span
                                                                        class="btn-label"><i
                                                                            class="icon-control-play"></i></span><?= lang('Buttons.Review') ?></button></a>
                                                    <?php }
                                                    } ?>
                                                <?php } ?>
                                            <?php } ?>


                                        <?php }
                                    } else { ?>
                                        <?php if ($clientCourseddata[0]['type'] == '10') { ?>
                                            <div class="persistent-warning">
                                                <div class="danger-text">
                                                    This course cannot be launched because the content is not uploaded yet.
                                                    Please upload the SCORM package or contact your administrator.
                                                </div>
                                            </div>

                                    <?php }
                                    } ?>
                                <?php } ?>
                                <?php if (strlen($clientCourseddata[0]['launch_link']) > 5) { ?>
                                    <a
                                        onclick="OpenNewWindowmiddlepop('<?php echo $clientCourseddata[0]['scourse_id'] ?>','4')">
                                        <button type="button"
                                            class="btn btn-outline-success waves-effect btn-sm waves-light rounded-pill mb-3"><span
                                                class="btn-label"><i
                                                    class="icon-control-play"></i></span><?= lang('Buttons.Launch') ?></button></a>
                                <?php } elseif ($clientCourseddata[0]['type'] == 8) { ?>
                                    <a onclick="OpenNewWindow('<?php echo base_url('Assessment/launch'); ?>')"><button
                                            class="btn btn-outline-success waves-effect btn-sm waves-light rounded-pill mb-3"><span
                                                class="btn-label"><i
                                                    class="icon-control-play"></i></span><?= lang('Buttons.Launch') ?></button></a>
                                    <?php } else {
                                    // echo '<span class="badge bg-secondary">Lunch button is not there because SCORM Package is not available</span>';
                                }

                                if (strlen($clientCourseddata[0]['promo_video']) > 3) {
                                    $promofile = FCPATH . 'assets/assets/uploads/SCORM_course_promovideo/' . $clientCourseddata[0]['scourse_id'] . '/' . $clientCourseddata[0]['promo_video'];
                                    if (file_exists($promofile)) {
                                    ?>
                                        <a
                                            onclick="OpenNewWindowmiddle('<?php echo base_url('SCORM/scorm_dashboard/launchpromo_video'); ?>')">
                                            <button type="button"
                                                class="btn btn-outline-warning waves-effect btn-sm waves-light rounded-pill mb-3"><?= $demoButton ?></button></a>
                                <?php }
                                } else {
                                    // echo '<span class="badge bg-secondary">' . lang('UI_Text.Promo video is not available') . '</span>';
                                } ?>

                                <?php if (in_array('8', $arrayuserlevel)) {
                                    if ($clientCourseddata[0]['demo'] == '1') { ?>
                                        <a
                                            href="<?php echo base_url('Demo/cart/addToCart/' . $clientCourseddata[0]['scourse_id']) ?>">
                                            <button type="button"
                                                class="btn btn-outline-dark waves-effect btn-sm waves-light rounded-pill mb-3"
                                                title="Add to Cart"><i class="fa fa-shopping-cart"></i>&nbsp; Add to Cart</button></a>
                                <?php }
                                } ?>
                                <?php if ($client != 1) {
                                ?>
                                    <?php if (isset($getCoursesAssigned[0]['course_status']) && $course_status != '1') {
                                        if ($getCoursesAssigned[0]['course_status'] != '3' && $getCoursesAssigned[0]['role'] == '5') { ?>
                                            <br />
                                            <a href="#"
                                                onclick="return confirmReview('<?php echo base_url('SCORM/course_builder/review_course/update_reviewstatus/' . $clientCourseddata[0]['scourse_id'] . '/10'); ?>');"
                                                class="btn btn-outline-primary waves-effect btn-sm waves-light rounded-pill mb-3">
                                                <i class="mdi mdi-water-check-outline me-1"></i> <?= lang('Buttons.Review') ?> <?= lang('UI_Text.Completed') ?>
                                            </a>

                                        <?php
                                        }
                                        ?>
                                <?php }
                                }
                                ?>

                            </div>
                            <?php //if (session()->get('client') == '1') { 
                            ?>
                            <?php if (!empty($certificate_assign) && ($clientCourseddata[0]['lesson_status'] == 'completed' || $clientCourseddata[0]['lesson_status'] == 'passed')) {
                            ?>
                                <form action="<?= base_url('Certification/certification_dashboard/view_certificate'); ?>" method="post" target="_blank" style="display:inline;">
                                    <?= csrf_field(); ?>
                                    <input type="hidden" name="course_mp_id" value="<?= $crid; ?>">
                                    <input type="hidden" name="type" value="3">
                                    <button type="submit" class="btn btn-outline-secondary btn-sm rounded-pill">
                                        <i class="mdi mdi-certificate-outline"></i> <?= lang('Buttons.View Certificate') ?>
                                    </button>
                                </form>
                            <?php //}
                            } ?>

                        </div>


                        <!-- RIGHT SIDE CONTENT -->
                        <div class="col-lg-8">
                            <?php
                            $accessmenu = session()->get('accessmenu');
                            $arrayaccessmenu  = array_map('intval', explode(',', $accessmenu));
                            ?>


                            <!-- status -->
                            <div class="stat-tile-grid">
                                <div class="stat-tile">
                                    <div class="stat-tile-icon"><i class="mdi mdi-pulse"></i></div>
                                    <div>
                                        <span class="stat-tile-label"><?= lang('UI_Text.Course Status') ?></span>
                                        <span class="stat-tile-value">
                                            <?php
                                            if ($course_status == 2) {


                                                if ($clientCourseddata[0]['course_status'] == '2') {
                                                    echo '<span class="badge bg-success">' . lang('UI_Text.Completed') . '</span>';
                                                } elseif ($clientCourseddata[0]['course_status'] == '1' || $clientCourseddata[0]['lesson_status'] == 'incomplete' || $clientCourseddata[0]['lesson_status'] == 'failed') {
                                                    echo '<span class="badge bg-info">' . lang('UI_Text.In Progress') . '</span>';
                                                } else {
                                                    echo '<span class="badge bg-warning">' . lang('UI_Text.Not Started') . '</span>';
                                                }
                                            } else {

                                                $labels = [
                                                    '1' => ['Development', 'danger'],
                                                    '3' => ['Alpha', 'warning'],
                                                    '4' => ['Alpha 2', 'info'],
                                                    '5' => ['Beta', 'warning'],
                                                    '6' => ['Beta 2', 'info'],
                                                    '7' => ['Gamma', 'warning'],
                                                    '8' => ['Gamma 2', 'info'],
                                                ];

                                                if (isset($labels[$course_status])) {
                                                    echo '<span class="badge bg-' . $labels[$course_status][1] . '">' .
                                                        $labels[$course_status][0] . '</span>';
                                                }
                                            }
                                            ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="stat-tile">
                                    <div class="stat-tile-icon"><i class="mdi mdi-calendar-check-outline"></i></div>
                                    <div>
                                        <span class="stat-tile-label"><?= lang('UI_Text.Attempts') ?></span>
                                        <span class="stat-tile-value"><?= isset($clientCourseddata[0]['attempt']) && $clientCourseddata[0]['attempt'] > 0 ? $clientCourseddata[0]['attempt'] : 'N/A'; ?></span>
                                    </div>
                                </div>

                                <div class="stat-tile">
                                    <div class="stat-tile-icon"><i class="mdi mdi-web"></i></div>
                                    <div>
                                        <span class="stat-tile-label"><?= lang('UI_Text.Language') ?></span>
                                        <span class="stat-tile-value"><?= isset($clientCourseddata[0]['language']) ? $clientCourseddata[0]['language'] : 'N/A'; ?></span>
                                    </div>
                                </div>

                                <div class="stat-tile">
                                    <div class="stat-tile-icon"><i class="mdi mdi-clock-outline"></i></div>
                                    <div>
                                        <span class="stat-tile-label"><?= lang('UI_Text.Duration') ?></span>
                                        <span class="stat-tile-value"><?= isset($clientCourseddata[0]['duration']) ? $clientCourseddata[0]['duration'] . ' min' : 'N/A'; ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php if (!empty($clientCourseddata[0]['description']) || !empty($getAllObjectives)) { ?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mb-2 p-3 about-course-box">
                                            <h6 class="about-course-title">About this course</h6>
                                            <!-- DESCRIPTION -->
                                            <div class="about-course-content mb-2">
                                                <?= $clientCourseddata[0]['description'] ?? ''; ?>
                                            </div>


                                            <!-- OBJECTIVES -->
                                            <?php if (!empty($getAllObjectives)) { ?>
                                                <div class="about-course-content mt-3">
                                                    <p><?= lang('UI_Text.end_of_course') ?></p>
                                                    <ul class="mt-2">
                                                        <?php foreach ($getAllObjectives as $obj) { ?>
                                                            <li><?= $obj['objective']; ?></li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>


                </div>

            </div>
            <div class="course-footer-meta">
                <?php if (!empty($clientCourseddata[0]['createdby'])) { ?>
                    <div class="course-footer-item">
                        <span class="course-footer-avatar"><?= strtoupper(mb_substr($clientCourseddata[0]['createdby'], 0, 1)) ?></span>
                        <div>
                            <span class="course-meta-label"><?= lang('UI_Text.Created_By') ?></span>
                            <span class="course-meta-value"><?= $clientCourseddata[0]['createdby'] ?></span>
                        </div>
                    </div>
                <?php } ?>
                <?php if (!empty($clientCourseddata[0]['createdon'])) { ?>
                    <div class="course-footer-item">
                        <i class="mdi mdi-calendar-outline"></i>
                        <div>
                            <span class="course-meta-label"><?= lang('UI_Text.Created_On') ?></span>
                            <span class="course-meta-value"><?= date('M d, Y', $clientCourseddata[0]['createdon']) ?></span>
                        </div>
                    </div>
                <?php } ?>
                <?php if (!empty($clientCourseddata[0]['last_updated_by_name'])) { ?>
                    <div class="course-footer-item">
                        <i class="mdi mdi-account-edit-outline"></i>
                        <div>
                            <span class="course-meta-label"><?= lang('UI_Text.Last_Updated_By') ?></span>
                            <span class="course-meta-value">
                                <?= $clientCourseddata[0]['last_updated_by_name'] ?>
                            </span>
                        </div>
                    </div>
                <?php } ?>
                <?php if (!empty($clientCourseddata[0]['last_updated_on'])) { ?>
                    <div class="course-footer-item">
                        <i class="mdi mdi-refresh"></i>
                        <div>
                            <span class="course-meta-label"><?= lang('UI_Text.Last_Updated_On') ?></span>
                            <span class="course-meta-value">
                                <?= date('M d, Y', $clientCourseddata[0]['last_updated_on']) ?>
                            </span>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>





        <?php if (in_array('4', $arrayuserlevel) || in_array('44', $arrayuserlevel) || in_array('46', $arrayuserlevel) || in_array('6', $arrayuserlevel) || in_array('67', $arrayuserlevel) || in_array('5', $arrayuserlevel) || in_array('45', $arrayuserlevel)) {
        ?>
            <?php if ($detail_type != 11) { ?>
                <div class="col-lg-3">
                    <div class="card administration-card">
                        <div class="card-body">
                            <h5 class="section-title"><i class="mdi mdi-shield-account-outline"></i> <?= lang('UI_Text.Administration') ?></h5>

                            <?php if (!empty($editableCoursedata) && $editableCoursedata[0]['editable'] == '1') {  ?>

                                <?php if (in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel) || in_array('4', $arrayuserlevel)) { // Trainers 

                                ?>
                                    <form id="userAssign" action="<?php echo base_url('SCORM/scorm_courses/course_settings_view') ?>"
                                        method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="scourse_id" value="<?php echo $crid; ?>">
                                        <input type="hidden" name="tab" value="1">
                                        <input type="hidden" name="course_name"
                                            value="<?php echo $clientCourseddata[0]['course_name']; ?>">
                                        <button type="submit" class="quick-action-item"><i class="mdi mdi-cog-outline"></i><span class="label-text"><?= lang('Buttons.Course Settings') ?></span><i class="mdi mdi-chevron-right"></i></button>
                                    </form>
                                    <?php if ($client == 1) { ?>
                                        <?php if (in_array('44', $arrayuserlevel) || in_array('5', $arrayuserlevel) || in_array('46', $arrayuserlevel)) { ?>
                                            <form id="userAssign" action="<?php echo base_url('SCORM/scorm_courses/add_category'); ?>"
                                                method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="scourse_id" value="<?php echo $crid; ?>">
                                                <input type="hidden" name="course_name"
                                                    value="<?php echo $clientCourseddata[0]['course_name']; ?>">
                                                <button type="submit" class="quick-action-item"><i class="mdi mdi-shape-outline"></i><span class="label-text"><?= lang('Buttons.Categories') ?></span><i class="mdi mdi-chevron-right"></i></button>
                                            </form>
                                    <?php }
                                    } ?>
                                    <?php if ($clientCourseddata[0]['type'] == 5) { // AR/VR 
                                    ?>
                                        <h5 class="mb-0 mt-0 text-uppercase bg-light p-2"><?= lang('UI_Text.AR/VR Scenarios') ?></h5>
                                        <form id="userAssign" action="<?php echo base_url('XAPI/XAPI_scenarios_courses') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="course_name"
                                                value="<?php echo $clientCourseddata[0]['course_name']; ?>">
                                            <input type="hidden" name="scourse_id" value="<?php echo $crid; ?>">
                                            <button type="submit" class="quick-action-item"><i class="mdi mdi-gesture"></i><span class="label-text"><?= lang('Buttons.Scenario Settings') ?></span><i class="mdi mdi-chevron-right"></i></button>
                                        </form>
                                    <?php } ?>
                                <?php } ?>
                                <?php
                                if ($clientCourseddata[0]['type'] == 11 || $clientCourseddata[0]['type'] == 10) {

                                    if (in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel) || in_array('46', $arrayuserlevel) || in_array('4', $arrayuserlevel) || in_array('67', $arrayuserlevel)) {
                                ?>

                                        <?php if ($clientCourseddata[0]['type'] == 10  && $client == 1) {
                                            if (in_array('46', $arrayuserlevel) || in_array('5', $arrayuserlevel) || in_array('67', $arrayuserlevel)) { // Developer ,QA,PM
                                        ?>

                                                <form id="userAssign" action="<?php echo base_url('SCORM/course_builder/Editor') ?>" method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="course_name"
                                                        value="<?php echo $clientCourseddata[0]['course_name']; ?>">
                                                    <input type="hidden" name="crid" value="<?php echo $clientCourseddata[0]['scourse_id']; ?>">
                                                    <input type="hidden" name="course_type" value="<?php echo $clientCourseddata[0]['type']; ?>">
                                                    <button type="submit" class="quick-action-item"><i class="mdi mdi-hammer-wrench"></i><span class="label-text"><?= lang('Buttons.Course Builder') ?></span><i class="mdi mdi-chevron-right"></i></button>
                                                </form>

                                            <?php
                                            }
                                        } elseif ($clientCourseddata[0]['type'] == 11) {
                                            if (in_array('46', $arrayuserlevel) || in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel) || in_array('67', $arrayuserlevel)) {  ?>
                                                <form id="userAssign" action="<?php echo base_url('SCORM/course_builder/Editor') ?>" method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="course_name"
                                                        value="<?php echo $clientCourseddata[0]['course_name']; ?>">
                                                    <input type="hidden" name="crid" value="<?php echo $clientCourseddata[0]['scourse_id']; ?>">
                                                    <input type="hidden" name="course_type" value="<?php echo $clientCourseddata[0]['type']; ?>">
                                                    <button type="submit" class="quick-action-item"><i class="mdi mdi-hammer-wrench"></i><span class="label-text"><?= lang('Buttons.Course Builder') ?></span><i class="mdi mdi-chevron-right"></i></button>
                                                </form>

                                        <?php
                                            }
                                        } ?>
                                        <?php if ($clientCourseddata[0]['type'] == 10) { // SCORM   
                                        ?>
                                            <form id="userAssign" action="<?php echo base_url('SCORM/scorm_courses/course_edit_view') ?>"
                                                method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="course_name"
                                                    value="<?php echo $clientCourseddata[0]['course_name']; ?>">
                                                <input type="hidden" name="scourse_id" value="<?php echo $crid; ?>">
                                                <button type="submit" class="quick-action-item"><i class="mdi mdi-cloud-upload-outline"></i><span class="label-text"><?= lang('Buttons.SCORM Upload') ?></span><i class="mdi mdi-chevron-right"></i></button>
                                            </form>
                                        <?php } ?>

                                <?php }
                                } ?>

                                <?php if ($clientCourseddata[0]['type'] == 5) { // AR/VR 
                                ?>
                                    <?php if (in_array('46', $arrayuserlevel) || in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel)) {  // Developer 
                                    ?>

                                        <form id="userAssign" action="<?php echo base_url('XAPI/XAPI_courses/input_variables') ?>"
                                            method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="scourse_id"
                                                value="<?php echo $clientCourseddata[0]['scourse_id']; ?>">
                                            <input type="hidden" name="course_name"
                                                value="<?php echo $clientCourseddata[0]['course_name']; ?>">
                                            <button type="submit" class="quick-action-item"><i class="mdi mdi-import"></i><span class="label-text"><?= lang('Buttons.Input Variables') ?></span><i class="mdi mdi-chevron-right"></i></button>
                                        </form>
                                        <form id="userAssign" action="<?php echo base_url('XAPI/XAPI_courses/output_variables') ?>"
                                            method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="scourse_id"
                                                value="<?php echo $clientCourseddata[0]['scourse_id']; ?>">
                                            <input type="hidden" name="course_name"
                                                value="<?php echo $clientCourseddata[0]['course_name']; ?>">
                                            <button type="submit" class="quick-action-item"><i class="mdi mdi-export"></i><span class="label-text"><?= lang('Buttons.Output Variables') ?></span><i class="mdi mdi-chevron-right"></i></button>
                                        </form>

                                <?php }
                                } ?>
                            <?php } ?>
                            <?php if (in_array('5', $arrayuserlevel) || in_array('46', $arrayuserlevel) || in_array('67', $arrayuserlevel) || in_array('4', $arrayuserlevel) || in_array('45', $arrayuserlevel) || in_array('44', $arrayuserlevel)) { // Developer ,QA,PM,ID

                            ?> <?php if (in_array('5', $arrayuserlevel) || in_array('4', $arrayuserlevel) || in_array('44', $arrayuserlevel)) {  // Instructors or Project Manager
                                ?>
                                    <form id="userAssign"
                                        action="<?php echo base_url('XAPI/XAPI_courses/courseusersassigned_report') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="scourse_id" value="<?php echo $crid; ?>">
                                        <input type="hidden" name="return_page" value="details">
                                        <button type="submit" class="quick-action-item"><i class="mdi mdi-account-multiple-plus-outline"></i><span class="label-text"><?= lang('Buttons.Assign_Users') ?></span><i class="mdi mdi-chevron-right"></i></button>
                                    </form>
                                <?php } ?>
                                <?php if (in_array('46', $arrayuserlevel) || in_array('4', $arrayuserlevel) || in_array('45', $arrayuserlevel) || in_array('67', $arrayuserlevel)) { // Developer ,PM,CR

                                ?>
                                    <form id="userAssign"
                                        action="<?php echo base_url('SCORM/Course_builder/review_course/showfeedback') ?>"
                                        method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="scourse_id"
                                            value="<?php echo $clientCourseddata[0]['scourse_id']; ?>">
                                        <input type="hidden" name="course_name"
                                            value="<?php echo $clientCourseddata[0]['course_name']; ?>">
                                        <input type="hidden" name="stage" value="3">
                                        <button type="submit" class="quick-action-item"><i class="mdi mdi-chart-box-outline"></i><span class="label-text"><?= lang('Buttons.Feedback Report') ?></span><i class="mdi mdi-chevron-right"></i></button>
                                    </form>

                            <?php }
                            } ?>

                        </div>
                    </div> <!-- end card-->

                    <div class="card enrolled-learners-card">
                        <div class="card-body">
                            <div class="enrolled-icon"><i class="mdi mdi-account-group-outline"></i></div>
                            <div class="enrolled-label"><?= lang('UI_Text.Enrolled_Learners') ?></div>
                            <div class="enrolled-value"><?= isset($enrolledLearnersCount) ? number_format($enrolledLearnersCount) : '0' ?></div>
                        </div>
                    </div>
                </div>
        <?php }
        } ?>

        </div>

<?php
    }
} ?>

<script type="text/javascript">
    function OpenPopup(MyPath, videoId) {
        var videoIframe = document.getElementById('videoIframe' + videoId);
        videoIframe.src = MyPath;

        $('#videoModal' + videoId).modal('show');

        $('#videoModal' + videoId).on('hidden.bs.modal', function() {
            // Pause the video when the modal is closed
            videoIframe.src = '';
        });
    }
</script>
<script type="text/javascript">
    function LaunchCourse(form) {
        console.log("case");
        params = 'width=' + screen.width;
        params += ', height=' + screen.height;
        params += ', top=0, left=0'
        params += ', fullscreen=yes';
        params += ', directories=no';
        params += ', location=no';
        params += ', menubar=no';
        params += ', resizable=no';
        params += ', scrollbars=no';
        params += ', status=no';
        params += ', toolbar=no';
        <?php $page_number = isset($pagedata[0]['page_number']) ? $pagedata[0]['page_number'] : '1'; ?>
        <?php $_SESSION['course_detail_launch'] = 1; ?>
        MyPath = '<?php echo base_url('SCORM/course_builder/review_course/launcher/1/' . $page_number); ?>';
        newwin = window.open(MyPath, "Launcher", params);
        var win_timer = setInterval(function() {
            if (newwin.closed) {
                window.location.reload();
                clearInterval(win_timer);
            }
        }, 100);
        if (window.focus) {
            newwin.focus();
        }
        return false;
    }


    function OpenNewWindow(MyPath) {
        params = 'width=' + screen.width;
        params += ', height=' + screen.height;
        params += ', top=0, left=0'
        params += ', fullscreen=yes';
        params += ', directories=no';
        params += ', location=no';
        params += ', menubar=no';
        params += ', resizable=no';
        params += ', scrollbars=no';
        params += ', status=no';
        params += ', toolbar=no';

        newwin = window.open(MyPath, "Launcher", params);
        var win_timer = setInterval(function() {
            if (newwin.closed) {
                window.location.reload();
                clearInterval(win_timer);
            }
        }, 100);
        if (window.focus) {
            newwin.focus();
        }
        return false;
    }

    function OpenNewWindowmiddle(MyPath) {
        var screenWidth = screen.width;
        var screenHeight = screen.height;
        var windowWidth = 800;
        var windowHeight = 450;
        var left = Math.floor((windowWidth) / 2);
        var top = Math.floor((windowHeight) / 2);
        var params = 'width=' + windowWidth;
        params += ', height=' + windowHeight;
        params += ', left=' + left;
        params += ', top=' + top;
        params += ', fullscreen=no';
        params += ', directories=no';
        params += ', location=no';
        params += ', menubar=no';
        params += ', resizable=no';
        params += ', scrollbars=no';
        params += ', status=no';
        params += ', toolbar=no';

        newwin = window.open(MyPath, "Launcher", params);
        var win_timer = setInterval(function() {
            if (newwin.closed) {
                window.location.reload();
                clearInterval(win_timer);
            }
        }, 100);
        if (window.focus) {
            newwin.focus();
        }
        return false;
    }
</script>
<script>
    const ratingValue = <?php echo isset($getratingCourse['0']['average_rating']) ? $getratingCourse['0']['average_rating'] : 'null'; ?>;
    // console.log(ratingValue);

    function displayStars(rating) {
        const container = document.getElementById('ratingContainer');

        if (JSON.stringify(container) !== "null") {
            container.innerHTML = ''; // Clear previous content

            if (rating === null) {

            } else {
                const fullStars = Math.floor(rating);
                for (let i = 0; i < fullStars; i++) {
                    const star = document.createElement('span');
                    star.innerHTML = ' <i class="mdi mdi-star text-warning"></i> '; // Actual star character
                    container.appendChild(star);
                }

                const decimalPart = rating - fullStars;
                if (decimalPart > 0) {
                    const halfStar = document.createElement('span');
                    halfStar.innerHTML = '<i class="mdi mdi-star-outline text-warning"></i>'; // Half-filled star character
                    container.appendChild(halfStar);
                }

                const emptyStars = 5 - Math.ceil(rating); // Assuming a maximum of 5 stars
                for (let i = 0; i < emptyStars; i++) {
                    const outlinedStar = document.createElement('span');
                    outlinedStar.innerHTML = '<i class="mdi mdi-star-outline text-warning"></i>'; // Outlined star character
                    container.appendChild(outlinedStar);
                }
            }
        }
    }

    // Example usage
    displayStars(ratingValue);
</script>




<script src="<?php echo base_url(); ?>public/plugins/sweetalert2/sweetalert2.min.js"></script>


<script type="text/javascript">
    // public/js/custom-rating-script.js
    $(document).ready(function() {
        var selectedRating = 0;

        $('.star').on('mouseenter', function() {
            var rating = $(this).data('rating');
            var isHalf = $(this).hasClass('half');

            $('.star').removeClass('hover selected half-selected');

            for (var i = 1; i <= rating; i++) {
                $('.star[data-rating="' + i + '"]').addClass(isHalf ? 'half-selected' : 'hover');
            }
        });

        $('.star').on('mouseleave', function() {
            $('.star').removeClass('hover half-selected');
            for (var i = 1; i <= selectedRating; i++) {
                $('.star[data-rating="' + i + '"]').addClass('selected');
            }
        });

        $('.star').on('click', function() {
            selectedRating = $(this).data('rating');
            var isHalf = $(this).hasClass('half');

            $('.star').removeClass('selected half-selected');

            if (isHalf) {
                $('.star[data-rating="' + selectedRating + '"]').addClass('half-selected');
            } else {
                for (var i = 1; i <= selectedRating; i++) {
                    $('.star[data-rating="' + i + '"]').addClass('selected');
                }
            }
        });
        $('#submitRatingBtn').on('click', function() {
            submitRating();
        });

        function submitRating() {
            // console.log('submitRating function called');
            if (selectedRating === 0) {
                Swal.fire('Error', 'Please select a rating.', 'error');
                return;
            }

            var isHalf = $('.star[data-rating="' + selectedRating + '"]').hasClass('half-selected');
            var comment = $('#comment').val();

            $.ajax({
                url: '<?php echo base_url('course_rating/submitRating') ?>', // Change to your actual route
                type: 'POST',
                data: {
                    rating: isHalf ? selectedRating - 0.5 : selectedRating,
                    comment: comment,
                    course_id: <?= isset($clientCourseddata[0]['scourse_id']) ? $clientCourseddata[0]['scourse_id'] : $crid ?>
                },
                success: function(response) {
                    // Handle success response
                    Swal.fire('Success', 'Rating submitted successfully!', 'success').then((result) => {
                        if (result.isConfirmed) {
                            // Close Bootstrap modal
                            $('#modal6').modal('hide');
                            // Optionally, you can reload the page
                            window.location.reload(true);
                        }
                    });

                },
                error: function(error) {
                    // Handle error response
                    Swal.fire('Error', 'Failed to submit rating.', 'error');
                }

            });
        }

    });
</script>
<script>
    $(function() {
        'use strict'

        $('#modal6').on('show.bs.modal', function(event) {

            var animation = $(event.relatedTarget).data('animation');
            $(this).addClass(animation);
        })

        // hide modal with effect
        $('#modal6').on('hidden.bs.modal', function(e) {
            $(this).removeClass(function(index, className) {
                return (className.match(/(^|\s)effect-\S+/g) || []).join(' ');
            });
        });

    });
</script>
<script>
    // Get the dimensions of the pop-up window
    var width = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    var height = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

    // Set the dimensions of the video element
    var videoElement = document.getElementById('videoElement');
    //  videoElement.style.maxWidth = width + 'px';
    //   videoElement.style.maxHeight = height + 'px';
</script>
<script>
    function submitPostRequest(scourse_id, page_number) {
        // Get the form by course_id dynamically
        var form = document.getElementById('launchForm_' + page_number);

        // Open a new window
        var params = 'width=' + screen.width;
        params += ', height=' + screen.height;
        params += ', top=0, left=0';
        params += ', fullscreen=yes';
        params += ', directories=no';
        params += ', location=no';
        params += ', menubar=no';
        params += ', resizable=no';
        params += ', scrollbars=no';
        params += ', status=no';
        params += ', toolbar=no';

        var newwin = window.open('', 'Launcher', params);

        // Submit the form using POST method, targeting the new window
        form.target = "Launcher"; // Target the new window
        form.submit(); // Submit the form with POST data

        // Periodically check if the new window has been closed
        var win_timer = setInterval(function() {
            if (newwin.closed) {
                window.location.reload();
                clearInterval(win_timer);
            }
        }, 100);

        if (window.focus) {
            newwin.focus();
        }

        return false;
    }
</script>
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
<script>
    function toggleEditPostForm(postId) {
        var form = document.getElementById('edit-post-' + postId);
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }

    function toggleEditReplyForm(replyId) {
        var form = document.getElementById('edit-reply-' + replyId);
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".read-more-replies").forEach(function(btn) {
            btn.addEventListener("click", function() {
                let postId = this.getAttribute("data-post-id");
                let replies = document.querySelectorAll(".reply-item-" + postId);
                let isExpanded = this.getAttribute("data-expanded") === "true";

                if (isExpanded) {
                    // Hide all but first 2 replies
                    replies.forEach(function(reply, index) {
                        if (index >= 2) reply.classList.add("d-none");
                    });
                    this.textContent = "View more replies (" + (replies.length - 2) + ")";
                    this.setAttribute("data-expanded", "false");
                } else {
                    // Show all replies
                    replies.forEach(function(reply) {
                        reply.classList.remove("d-none");
                    });
                    this.textContent = "Hide replies";
                    this.setAttribute("data-expanded", "true");
                }
            });
        });
    });
</script>