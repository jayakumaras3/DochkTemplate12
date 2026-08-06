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
                        <h4 class="text-success">Payment Successful</h4>
                    <?php else: ?>
                        <div class="mb-3">
                            <i class="bi bi-x-circle-fill text-danger" style="font-size:60px;"></i>
                        </div>
                        <h4 class="text-danger">Payment Failed</h4>
                    <?php endif; ?>

                    <p class="mt-3"><?= $paymentMessage ?></p>

                    <div class="mt-4">
                        <button class="btn <?= $paymentStatus === 'success' ? 'btn-success' : 'btn-danger' ?>"
                            data-bs-dismiss="modal">
                            Close
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
            <h4 class="page-title">Course Detail</h4>
        </div>
    </div>
</div>
<!-- end page title -->
<?php if (isset($clientCourseddata) && $clientCourseddata != '') {
    if (count($clientCourseddata) > 0) {
        if ($clientCourseddata[0]['type'] == 1) {
            $demoButton = '<span class="btn-label"><i class="icon-social-youtube"></i></span>Preview';
        }
        if ($clientCourseddata[0]['type'] == 2) {
            $demoButton = '<span class="btn-label"><i class="icon-social-youtube"></i></span>Preview';
        }
        if ($clientCourseddata[0]['type'] == 5) {
            $demoButton = '<span class="btn-label"><i class="icon-social-youtube"></i></span>Preview';
        } else {
            $demoButton = '<span class="btn-label"><i class="icon-social-youtube"></i></span>Preview';
        }
?>
        <div class="row">
            <?php if (in_array('4', $arrayuserlevel) || in_array('46', $arrayuserlevel) || in_array('6', $arrayuserlevel) || in_array('67', $arrayuserlevel) || in_array('5', $arrayuserlevel) || in_array('45', $arrayuserlevel) || in_array('44', $arrayuserlevel)) { ?>
                <div class="col-lg-10">
                <?php } else { ?>
                    <div class="col-lg-12">
                    <?php } ?>
                    <div class="card ribbon-box">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3">
                                    <?php if (isset($clientCourseddata[0]['thumbnail']) && $clientCourseddata[0]['thumbnail'] != '') {
                                        $thumbnail = base_url('assets/assets/uploads/SCORM_course_thumbnail/' . $clientCourseddata[0]['scourse_id'] . '/' . $clientCourseddata[0]['thumbnail']);
                                    } else {
                                        $thumbnail = base_url('public/aristo_assets/images/default_thumbnail.png');
                                    } ?>

                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <img style="border: 1px solid transparent; display: block;background: none;  border-color: rgb(0, 0, 0, 0.2);  box-shadow: 4px 4px 5px rgba(0, 0, 0, 0.3);"
                                                src="<?php echo $thumbnail ?>" alt="" class="img-fluid mx-auto d-block rounded">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php if ($getratingCourse['0']['count_user'] != 0) { ?>
                                                <div class="rating-container">
                                                    <span id="ratingContainer" class="star-rating"></span>
                                                    <span
                                                        id="ratingContainer"><?php echo ($getratingCourse['0']['count_user'] != 0) ? '(' . $getratingCourse['0']['count_user'] . ')' : ''; ?></span>
                                                </div><br />
                                            <?php }
                                            ?>
                                            Language : <span
                                                class="text-muted me-2"><?php echo $clientCourseddata[0]['language']; ?>
                                            </span><br />
                                            Duration : <span class="text-muted me-2">
                                                <?php if ($clientCourseddata[0]['duration'] > 0) { ?>
                                                    <?php $duration = $clientCourseddata[0]['duration'];
                                                    if ($duration > 60) {
                                                        $hours = intdiv($duration, 60);
                                                        echo $hours . ' Hrs. ';
                                                        $balancemin = $duration - $hours * 60;
                                                        if ($balancemin > 0) {
                                                            echo $balancemin . ' min';
                                                        }
                                                    } else {
                                                        echo $duration . ' min';
                                                    } ?>
                                                <?php } ?> </span> <br />
                                            <?php //print_r($clientCourseddata); 
                                            ?>
                                            <?php if (isset($clientCourseddata[0]['due_date']) && $clientCourseddata[0]['due_date'] != '0000-00-00') { ?>
                                                Due On : <span class="text-muted me-2">
                                                    <?php echo date('m-d-Y', strtotime($clientCourseddata[0]['due_date'])); ?></span><br />
                                            <?php } else { ?>
                                            <?php } ?>
                                            <?php if (strlen($clientCourseddata[0]['last_updated_by']) > 5) { ?>
                                                Last Accessed On : <span class="text-muted me-2">
                                                    <?php echo date('m-d-Y', strtotime($clientCourseddata[0]['last_updated_by'])); ?></span><br />
                                            <?php } else { ?>
                                            <?php } ?>
                                            <?php if (isset($clientCourseddata[0]['attempt']) && $clientCourseddata[0]['attempt']) { ?>
                                                Attempts : <span
                                                    class="text-muted me-2"><?php echo $clientCourseddata[0]['attempt'] ?></span>
                                            <?php } ?>
                                            <?php
                                            $course_status = $clientCourseddata[0]['mode'];
                                            ?>
                                        </div>
                                    </div>
                                    <div class="row mb-3">

                                        <div class="col-md-12">
                                            <?php if ($course_status == 2) {
                                                // if (isset($clientCourseddata[0]['course_status'])) { 
                                            ?>
                                                <!--     <h4><?php if ($clientCourseddata[0]['course_status'] == '2') { ?>
                                                             <div class="ribbon-two ribbon-two-success"><span>Completed</span></div>
                                                       <?php } elseif ($clientCourseddata[0]['course_status'] == '1' || $clientCourseddata[0]['lesson_status'] == 'incomplete') { ?>
                                                            <div class="ribbon-two ribbon-two-info"><span>In Progress</span></div>
                                                       <?php } elseif ($clientCourseddata[0]['course_status'] == '0') { ?>
                                                           <div class="ribbon-two ribbon-two-danger"><span>Not Started</span></div>
                                                     <?php } else { ?>
                                                           <div class="ribbon-two ribbon-two-danger"><span>Not Started</span></div>
                                                        <?php } ?>

                                                   </h4> -->
                                                <!--  <?php //} 
                                                        ?> -->
                                                <?php if (isset($clientCourseddata[0]['lesson_status'])) {
                                                    // print_r($clientCourseddata[0]['lesson_status']);
                                                    if (strlen($clientCourseddata[0]['lesson_status']) > 2) {
                                                        if ($clientCourseddata[0]['lesson_status'] == 'completed' || $clientCourseddata[0]['lesson_status'] == 'passed') { ?>
                                                            <div class="ribbon-two ribbon-two-success"><span><?php echo 'Completed'; ?></span></div>
                                                        <?php } elseif ($clientCourseddata[0]['lesson_status'] == 'incomplete') { ?>
                                                            <div class="ribbon-two ribbon-two-info"><span><?php echo 'In progress'; ?></span></div>
                                                        <?php } elseif ($clientCourseddata[0]['lesson_status'] == 'not started') { ?>
                                                            <div class="ribbon-two ribbon-two-warning"><span><?php echo 'Not Started'; ?></span></div>
                                                        <?php } elseif ($clientCourseddata[0]['lesson_status'] == 'failed') { ?>
                                                            <div class="ribbon-two ribbon-two-info"><span><?php echo 'In progress'; ?></span></div>
                                                        <?php } ?>

                                                    <?php } else { ?>
                                                        <div class="ribbon-two ribbon-two-warning"><span><?php echo 'Not Started'; ?></span></div>
                                                    <?php }
                                                } else { ?>
                                                    <div class="ribbon-two ribbon-two-warning"><span><?php echo 'Not Started'; ?></span></div>
                                                <?php } ?>
                                            <?php  } else { ?>
                                                <h4><?php
                                                    // print_r($course_status);
                                                    if ($course_status == '1') { ?>
                                                        <div class="ribbon-two ribbon-two-danger"><span>Development</span></div>
                                                    <?php } elseif ($course_status == '3') { ?>
                                                        <div class="ribbon-two ribbon-two-warning"><span>Alpha</span></div>
                                                    <?php } elseif ($course_status == '4') { ?>
                                                        <div class="ribbon-two ribbon-two-info"><span>Alpha 2</span></div>
                                                    <?php } elseif ($course_status == '5') { ?>
                                                        <div class="ribbon-two ribbon-two-warning"><span>Beta</span></div>
                                                    <?php } elseif ($course_status == '6') { ?>
                                                        <div class="ribbon-two ribbon-two-info"><span>Beta 2</span></div>
                                                    <?php } elseif ($course_status == '7') { ?>
                                                        <div class="ribbon-two ribbon-two-warning"><span>Gamma</span></div>
                                                    <?php } elseif ($course_status == '8') { ?>
                                                        <div class="ribbon-two ribbon-two-info"><span>Gamma 2</span></div>
                                                    <?php } ?>
                                                </h4>
                                            <?php } ?>

                                        </div>
                                        <div class="col-md-12">
                                            <?php //    print_r($price); 
                                            ?>
                                            <?php //print_r($getCoursesAssigned);
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
                                                                    <form method="POST" onsubmit="LaunchCourse(this)">
                                                                        <button class="btn btn-outline-success waves-effect btn-sm waves-light mb-3"
                                                                            onclick="openForm()"><i class="mdi mdi-play-circle-outline me-2"></i>
                                                                            Launch</button>
                                                                    </form>

                                                                <?php } else { ?>
                                                                    <form method="POST" action="<?php echo base_url('stripe/Checkout/payCourse'); ?>">
                                                                        <input type="hidden" name="course_price" value="<?php echo $price ?>">
                                                                        <input type="hidden" name="billing_cycle" value="<?php echo $billing_cycle; ?>">
                                                                        <input type="hidden" name="scourse_id" value="<?php echo $crid; ?>">
                                                                        <input type="hidden" name="currency" value="<?php echo $currency; ?>" ?>
                                                                        <button class="btn btn-outline-danger waves-effect btn-sm waves-light mb-3"><i class="mdi mdi-credit-card me-2"></i>
                                                                            <?php echo '$ ' . $price ?></button>
                                                                    </form>
                                                                <?php

                                                                } ?>
                                                            <?php  } elseif ($detail_type == '5' && $payment_type == '2' && $billing_cycle == '2') { ?>
                                                                <?php if (!empty($getbillingdata)) {
                                                                    //print_r("ttt"); 
                                                                ?>
                                                                    <form method="POST" onsubmit="LaunchCourse(this)">
                                                                        <button class="btn btn-outline-success waves-effect btn-sm waves-light mb-3"
                                                                            onclick="openForm()"><i class="mdi mdi-play-circle-outline me-2"></i>
                                                                            Launch</button>
                                                                    </form>
                                                                <?php } ?>
                                                            <?php } else {
                                                                // print_r("ss"); 
                                                            ?>
                                                                <form method="POST" onsubmit="LaunchCourse(this)">
                                                                    <button class="btn btn-outline-success waves-effect btn-sm waves-light mb-3"
                                                                        onclick="openForm()"><i class="mdi mdi-play-circle-outline me-2"></i>
                                                                        Launch</button>
                                                                </form>
                                                            <?php } ?>
                                                        <?php } else { ?>

                                                            <?php if ($clientCourseddata[0]['mode'] == 1) { ?>

                                                            <?php } else { ?>
                                                                <form method="POST" onsubmit="LaunchCourse(this)">
                                                                    <button class="btn btn-outline-danger waves-effect btn-sm waves-light mb-3"
                                                                        onclick="openForm()"><i class="mdi mdi-play-circle-outline me-2"></i>
                                                                        Review</button>
                                                                </form>
                                                            <?php } ?>
                                                        <?php } ?>


                                                    <?php } ?>
                                                <?php } ?>

                                            <?php } else { ?>
                                                <?php if ($detail_type == '5' && $payment_type == '2' && $price >= 0) {
                                                ?>
                                                    <form method="POST" action="<?php echo base_url('stripe/Checkout/payCourse'); ?>">
                                                        <input type="hidden" name="course_price" value="<?php echo $price ?>">
                                                        <input type="hidden" name="billing_cycle" value="<?php echo $billing_cycle; ?>">
                                                        <input type="hidden" name="scourse_id" value="<?php echo $crid; ?>">
                                                        <input type="hidden" name="currency" value="<?php echo $currency; ?>" ?>
                                                        <button class="btn btn-outline-danger waves-effect btn-sm waves-light mb-3"><i class="mdi mdi-credit-card me-2"></i>
                                                            <?php echo '$ ' . $price ?></button>
                                                    </form>
                                                <?php
                                                } else {
                                                    // print_r($price);  
                                                ?>
                                                    <form method="POST" onsubmit="LaunchCourse(this)">
                                                        <button class="btn btn-outline-success waves-effect btn-sm waves-light mb-3"
                                                            onclick="openForm()"><i class="mdi mdi-play-circle-outline me-2"></i>
                                                            Launch</button>
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


                                                <?php if ($clientCourseddata[0]['type'] === 5) { ?>
                                                    <a
                                                        onclick="OpenNewWindow('<?php echo base_url('SCORM/scorm_launch/tinCanlanch'); ?>')">
                                                        <button type="button"
                                                            class="btn btn-outline-success waves-effect btn-sm waves-light mb-3"><span
                                                                class="btn-label"><i
                                                                    class="icon-control-play"></i></span>Launch</button></a>
                                                <?php } else {
                                                    // print_r($course_status); 
                                                ?>
                                                    <?php if ($course_status === '2' || $getCoursesAssigned[0]['course_status'] === '3') {
                                                        // print_r($course_status);
                                                    ?>
                                                        <?php if ($detail_type === 5 && $payment_type === 2 && $billing_cycle === 1) {
                                                            if (!empty($getbillingdata) || $price <= 0) { ?>
                                                                <a onclick="OpenNewWindow('<?php echo base_url('SCORM/scorm_launch'); ?>')">
                                                                    <button type="button"
                                                                        class="btn btn-outline-success waves-effect btn-sm waves-light mb-3"><span
                                                                            class="btn-label"><i
                                                                                class="icon-control-play"></i></span>Launch</button></a>
                                                                <?php if (!empty($certificate_assign) && ($course_status == '2' || (isset($getCoursesAssigned[0]['course_status']) && $getCoursesAssigned[0]['course_status'] == '3'))) { ?>
                                                                    <!-- <a class="btn btn-outline-secondary btn-sm mb-3" href="<?php echo base_url('Certification/certification_dashboard/view_certificate/' . $crid . '/' . $client_id); ?>" target="_blank">
                                                                        <i class="mdi mdi-certificate-outline me-1"></i> View Certificate
                                                                    </a> -->
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <form method="POST" action="<?php echo base_url('stripe/Checkout/payCourse'); ?>">
                                                                    <input type="hidden" name="course_price" value="<?php echo $price ?>">
                                                                    <input type="hidden" name="billing_cycle" value="<?php echo $billing_cycle; ?>">
                                                                    <input type="hidden" name="scourse_id" value="<?php echo $crid; ?>">
                                                                    <input type="hidden" name="currency" value="<?php echo $currency; ?>" ?>
                                                                    <button class="btn btn-outline-danger waves-effect btn-sm waves-light mb-3"><i class="mdi mdi-credit-card me-2"></i>
                                                                        <?php echo '$ ' . $price ?></button>
                                                                </form>

                                                            <?php } ?>
                                                        <?php  } elseif ($detail_type === 5 && $payment_type === 2 && $billing_cycle == 2) { ?>
                                                            <?php if (!empty($getsubscribebillingdata)) { ?>
                                                                <a onclick="OpenNewWindow('<?php echo base_url('SCORM/scorm_launch'); ?>')">
                                                                    <button type="button"
                                                                        class="btn btn-outline-success waves-effect btn-sm waves-light mb-3"><span
                                                                            class="btn-label"><i
                                                                                class="icon-control-play"></i></span>Launch</button></a>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <a onclick="OpenNewWindow('<?php echo base_url('SCORM/scorm_launch'); ?>')">
                                                                <button type="button"
                                                                    class="btn btn-outline-success waves-effect btn-sm waves-light mb-3"><span
                                                                        class="btn-label"><i
                                                                            class="icon-control-play"></i></span>Launch</button></a>
                                                        <?php } ?>

                                                    <?php } else { ?>
                                                        <?php if ($clientCourseddata[0]['mode'] == 1) { ?>

                                                        <?php } else { ?>
                                                            <?php if (isset($pagedata[0]['page_id'])) { ?>
                                                                <a
                                                                    onclick="OpenNewWindow('<?php echo base_url('SCORM/scorm_launch/review/' . $pagedata[0]['page_id']); ?>')">
                                                                    <button type="button"
                                                                        class="btn btn-outline-danger waves-effect btn-sm waves-light mb-3"><span
                                                                            class="btn-label"><i
                                                                                class="icon-control-play"></i></span>Review</button></a>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php if ($client != 1) { ?>
                                                        <?php if ($getCoursesAssigned[0]['course_status'] != '3') { ?>
                                                            </br>
                                                            <!-- <a href="#" onclick="return confirmReview('<?php echo base_url('SCORM/course_builder/review_course/update_reviewstatus/') . $clientCourseddata[0]['scourse_id']; ?>');" class="btn btn-outline-primary waves-effect btn-sm waves-light mb-3">
                                                                    <i class="mdi mdi-water-check-outline me-1"></i> Review Completed
                                                                </a> -->
                                                        <?php } ?>
                                                    <?php } ?>

                                                <?php }
                                            } else if (strlen($clientCourseddata[0]['launch_link']) > 5) { ?>
                                                <a
                                                    onclick="OpenNewWindowmiddlepop('<?php echo $clientCourseddata[0]['scourse_id'] ?>','4')">
                                                    <button type="button"
                                                        class="btn btn-outline-success waves-effect btn-sm waves-light mb-3"><span
                                                            class="btn-label"><i
                                                                class="icon-control-play"></i></span>Launch</button></a>
                                            <?php } elseif ($clientCourseddata[0]['type'] == 8) { ?>
                                                <a onclick="OpenNewWindow('<?php echo base_url('Assessment/launch'); ?>')"><button
                                                        class="btn btn-outline-success waves-effect btn-sm waves-light mb-3"><span
                                                            class="btn-label"><i
                                                                class="icon-control-play"></i></span>Launch</button></a>
                                                <?php }

                                            if (strlen($clientCourseddata[0]['promo_video']) > 3) {
                                                $promofile = FCPATH . 'assets/assets/uploads/SCORM_course_promovideo/' . $clientCourseddata[0]['scourse_id'] . '/' . $clientCourseddata[0]['promo_video'];
                                                if (file_exists($promofile)) {
                                                ?>
                                                    <a
                                                        onclick="OpenNewWindowmiddle('<?php echo base_url('SCORM/scorm_dashboard/launchpromo_video'); ?>')">
                                                        <button type="button"
                                                            class="btn btn-outline-warning waves-effect btn-sm waves-light mb-3"><?= $demoButton ?></button></a>
                                            <?php }
                                            } ?>

                                            <?php if (in_array('8', $arrayuserlevel)) {
                                                if ($clientCourseddata[0]['demo'] == '1') { ?>
                                                    <a
                                                        href="<?php echo base_url('Demo/cart/addToCart/' . $clientCourseddata[0]['scourse_id']) ?>">
                                                        <button type="button"
                                                            class="btn btn-outline-dark waves-effect btn-sm waves-light mb-3"
                                                            title="Add to Cart"><i class="fa fa-shopping-cart"></i></button></a>
                                            <?php }
                                            } ?>
                                            <?php if ($client != 1) {
                                            ?>
                                                <?php if (isset($getCoursesAssigned[0]['course_status']) && $course_status != '1') {
                                                    if ($getCoursesAssigned[0]['course_status'] != '3' && $getCoursesAssigned[0]['role'] == '5') { ?>
                                                        <br />
                                                        <a href="#"
                                                            onclick="return confirmReview('<?php echo base_url('SCORM/course_builder/review_course/update_reviewstatus/' . $clientCourseddata[0]['scourse_id'] . '/10'); ?>');"
                                                            class="btn btn-outline-primary waves-effect btn-sm waves-light mb-3">
                                                            <i class="mdi mdi-water-check-outline me-1"></i> Review Completed
                                                        </a>

                                                    <?php
                                                    }
                                                    ?>
                                            <?php }
                                            }
                                            ?>
                                        </div><br />
                                        <?php

                                        if (empty($getratingCourseofuser)) {
                                            if (isset($clientCourseddata[0]['course_status'])) {
                                                if ($clientCourseddata[0]['course_status'] == '2') { ?>
                                                    <!-- <div><br />
                                                        <a type="button" href="#modal6"
                                                            class="btn btn-outline-primary waves-effect btn-sm waves-light mb-3"
                                                            data-bs-toggle="modal" data-bs-target="#modal6">
                                                            <span class="btn-label"><i class="mdi mdi-star"></i></span>Rate this
                                                            Course
                                                        </a>
                                                    </div> -->
                                        <?php
                                                }
                                            }
                                        } ?>
                                        <?php if (!empty($certificate_assign) && ($clientCourseddata[0]['lesson_status'] == 'completed' || $clientCourseddata[0]['lesson_status'] == 'passed')) {
                                        ?>
                                            <!-- <a class="btn btn-outline-secondary btn-sm" href="<?php echo base_url('Certification/Dashboard/view_certificate/' . $crid . '/3'); ?>" target="_blank">
                                                <i class="mdi mdi-certificate-outline"></i> View Certificate
                                            </a> -->
                                            <form action="<?= base_url('Certification/certification_dashboard/view_certificate'); ?>" method="post" target="_blank" style="display:inline;">
                                                <?= csrf_field(); ?>
                                                <input type="hidden" name="course_mp_id" value="<?= $learning_plan_details['mp_id']; ?>">
                                                <input type="hidden" name="type" value="3">
                                                <button type="submit" class="btn btn-outline-secondary btn-sm">
                                                    <i class="mdi mdi-certificate-outline"></i> View Certificate
                                                </button>
                                            </form>
                                        <?php } ?>
                                    </div>

                                </div> <!-- end col -->


                                <div class="col-lg-9">
                                    <ul class="nav nav-pills navtab-bg nav-justified">
                                        <li class="nav-item">
                                            <a href="#Details" data-bs-toggle="tab" aria-expanded="true" class="nav-link <?php if ($tab == 1)
                                                                                                                                echo "active"; ?>">
                                                Details
                                            </a>
                                        </li>
                                        <!-- <li class="nav-item">
                                                <a href="#Objectives" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                                    Preview
                                                </a>
                                            </li> -->
                                        <li class="nav-item">
                                            <a href="#Discussion1" data-bs-toggle="tab" aria-expanded="false" class="nav-link <?php if ($tab == 2)
                                                                                                                                    echo "active"; ?>">
                                                Discussion
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane <?php if ($tab == 1)
                                                                    echo " show active"; ?>" id="Details">
                                            <div class="ps-xl-3 mt-3 mt-xl-0">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <h4 class="mb-3"><?php echo $clientCourseddata[0]['course_name']; ?>
                                                        </h4>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <?php if (isset($clientCourseddata[0]['description'])) {
                                                            if (strlen($clientCourseddata[0]['description']) > 10) {
                                                                if (strlen($clientCourseddata[0]['description']) > 5) {
                                                                    echo $clientCourseddata[0]['description'];
                                                                }
                                                            }
                                                        } ?>
                                                    </div>
                                                    <?php
                                                    if (strlen($clientCourseddata[0]['objectives']) > 5) {
                                                        echo '  <div class="row"><div class="col-md-12"><div>';
                                                        $objectives = $clientCourseddata[0]['objectives'];
                                                        print_r(str_replace("<li>", '<p class="text-muted"><i class="mdi mdi-checkbox-marked-circle-outline h6 text-primary me-2"></i>', $objectives, $i));
                                                        str_replace("</li>", '</p>', $i, $k);
                                                        echo '</div></div></div>';
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($getAllObjectives) {
                                                    ?>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <!-- <div class="mb-2">In this course, you will be able to:</div> -->
                                                                <div>
                                                                    <table>
                                                                        <tbody>
                                                                            <?php
                                                                            foreach ($getAllObjectives as $objectives) {
                                                                            ?>
                                                                                <tr>
                                                                                    <td valign="top" style="padding: 5px;"><i
                                                                                            class="mdi mdi-checkbox-marked-circle-outline h6 text-primary me-2"></i>
                                                                                    </td>
                                                                                    <td valign="top" style="padding: 5px;">
                                                                                        <?= $objectives['objective'] ?>
                                                                                    </td>
                                                                                </tr>

                                                                            <?php } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div> <!-- end col -->
                                        </div>
                                        <div class="tab-pane <?php if ($tab == 2)
                                                                    echo " show active"; ?>" id="Discussion1">
                                            <div class="row">
                                                <style>
                                                    .btn-card {
                                                        display: block;
                                                        /* make it behave like a div */
                                                        width: 100%;
                                                        /* full column width */
                                                        border: none;
                                                        /* remove black border */
                                                        background: none;
                                                        /* no background */
                                                        padding: 0;
                                                        /* remove button padding */
                                                        margin: 0;
                                                        text-align: left;
                                                        cursor: pointer;
                                                    }

                                                    .btn-card:focus {
                                                        outline: none;
                                                        /* remove blue highlight */
                                                        box-shadow: none;
                                                        /* remove Bootstrap shadow */
                                                    }
                                                </style>

                                                <div class="col-md-12"
                                                    style="max-height: 350px; overflow-y: auto; overflow-x: hidden;">
                                                    <div class="card">
                                                        <div class="card-body p-0">

                                                            <div class="tab-content pt-0">
                                                                <div class="tab-pane show active p-3" id="newpost"
                                                                    role="tabpanel">
                                                                    <!-- comment box -->
                                                                    <div class="border rounded">
                                                                        <form class="comment-area-box"
                                                                            action="<?php echo base_url('social/post/add_post') ?>"
                                                                            method="POST" enctype="multipart/form-data"><?= csrf_field() ?>

                                                                            <textarea rows="4" name="new_post"
                                                                                class="form-control border-0 resize-none"
                                                                                placeholder="Write something...."></textarea>

                                                                            <div
                                                                                class="p-2 bg-light d-flex justify-content-between align-items-center">
                                                                                <div class="d-flex align-items-center gap-2">
                                                                                    <!-- Trigger for image upload -->
                                                                                    <label
                                                                                        class="btn btn-outline-warning waves-effect btn-xs waves-light "
                                                                                        for="imageUpload">
                                                                                        <i class="mdi mdi-image-outline"></i>Image
                                                                                    </label>
                                                                                    <input type="file" name="image_files[]"
                                                                                        id="imageUpload" multiple
                                                                                        accept="image/*" style="display: none;">

                                                                                    <!-- Trigger for video upload -->
                                                                                    <label
                                                                                        class="btn btn-outline-info waves-effect btn-xs waves-light "
                                                                                        for="videoUpload">
                                                                                        <i class="mdi mdi-video-outline"></i>Video
                                                                                    </label>
                                                                                    <input type="file" name="video_files[]"
                                                                                        id="videoUpload" multiple
                                                                                        accept="video/*" style="display: none;">
                                                                                </div>
                                                                                <input type="hidden" name="course_id"
                                                                                    value="<?php echo $crid; ?>">
                                                                                <input type="hidden" name="tab" value="2">
                                                                                <button type="submit"
                                                                                    class="btn btn-outline-success waves-effect btn-xs waves-light ">
                                                                                    <i
                                                                                        class="mdi mdi-send-outline me-1"></i>Post
                                                                                </button>
                                                                            </div>
                                                                        </form>
                                                                    </div> <!-- end .border-->
                                                                    <!-- end comment box -->
                                                                </div> <!-- end preview-->
                                                            </div> <!-- end tab-content-->
                                                        </div>
                                                    </div>
                                                    <?php foreach ($active_posts as $post) { ?>
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="d-flex align-items-start">
                                                                    <img class="me-2 avatar-sm rounded-circle"
                                                                        src="<?php echo (!empty($post['profile_image']) && !empty($post['profile_foldername']))
                                                                                    ? base_url('assets/assets/uploads/profile/' . $post['id_user'] . "/" . $post['profile_foldername'] . "/" . $post['profile_image'])
                                                                                    : base_url('public/aristo_assets/images/User_2_1.svg'); ?>" alt="User image"
                                                                        onerror="this.onerror=null; this.src='<?php echo base_url('public/aristo_assets/images/User_2_1.svg'); ?>';">

                                                                    <div class="w-100">
                                                                        <?php if ($post['created_by'] == $user_id): ?>
                                                                            <div class="dropdown float-end text-muted">
                                                                                <a href="#" class="dropdown-toggle text-muted font-18"
                                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                                    <i class="mdi mdi-dots-horizontal"></i>
                                                                                </a>
                                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                                    <button type="button" class="dropdown-item"
                                                                                        onclick="toggleEditPostForm(<?php echo $post['social_id']; ?>)">Edit</button>


                                                                                    <form
                                                                                        action="<?php echo base_url('social/post/delete_post') ?>"
                                                                                        method="POST"><?= csrf_field() ?>
                                                                                        <input type="hidden" name="post_id"
                                                                                            value="<?php echo $post['social_id'] ?>">
                                                                                        <input type="hidden" name="course_id"
                                                                                            value="<?php echo $crid ?>">
                                                                                        <input type="hidden" name="tab" value="2">
                                                                                        <button type="submit"
                                                                                            class="dropdown-item">Delete</button>
                                                                                    </form>

                                                                                </div>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                        <div class="edit-post-form bg-light p-3 rounded mt-2"
                                                                            id="edit-post-<?php echo $post['social_id']; ?>"
                                                                            style="display: none;">
                                                                            <form
                                                                                action="<?php echo base_url('social/post/edit_post') ?>"
                                                                                method="POST"><?= csrf_field() ?>
                                                                                <input type="hidden" name="post_id"
                                                                                    value="<?php echo $post['social_id']; ?>">
                                                                                <textarea name="post_data"
                                                                                    class="form-control mb-2"><?php echo $post['post_data']; ?></textarea>
                                                                                <input type="hidden" name="tab" value="2">
                                                                                <div class="text-end">
                                                                                    <button type="submit"
                                                                                        class="btn btn-primary btn-sm">Save</button>
                                                                                    <button type="button"
                                                                                        class="btn btn-secondary btn-sm"
                                                                                        onclick="toggleEditPostForm(<?php echo $post['social_id']; ?>)">Cancel</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                        <h5 class="m-0"><a
                                                                                class="text-reset">
                                                                                <?php echo $post['posted_by'] . ' ' . $post['posted_by_last_name']; ?></a>
                                                                        </h5>
                                                                        <p class="text-muted">
                                                                            <small><?php echo $post['time_ago']; ?></small>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="font-16 text-left fst-italic text-dark">
                                                                    <?php echo nl2br(htmlspecialchars($post['post_data'])); ?>

                                                                    <?php
                                                                    // Collect media
                                                                    $mediaItems = [];

                                                                    if (!empty($post['post_image'])) {
                                                                        $images = explode(',', $post['post_image']);
                                                                        foreach ($images as $img) {
                                                                            $img = trim($img);
                                                                            if (!empty($img)) {
                                                                                $mediaItems[] = [
                                                                                    'type' => 'image',
                                                                                    'path' => base_url('assets/assets/uploads/post_images/' . $img)
                                                                                ];
                                                                            }
                                                                        }
                                                                    }

                                                                    if (!empty($post['post_video'])) {
                                                                        $videos = explode(',', $post['post_video']);
                                                                        foreach ($videos as $vid) {
                                                                            $vid = trim($vid);
                                                                            if (!empty($vid)) {
                                                                                $mediaItems[] = [
                                                                                    'type' => 'video',
                                                                                    'path' => base_url('assets/assets/uploads/post_videos/' . $vid)
                                                                                ];
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>

                                                                    <?php if (!empty($mediaItems)) { ?>
                                                                        <div class="post-media-grid">
                                                                            <?php foreach ($mediaItems as $media) { ?>
                                                                                <div class="media-item">
                                                                                    <?php if ($media['type'] === 'image') { ?>
                                                                                        <img src="<?php echo $media['path']; ?>"
                                                                                            alt="post image" />
                                                                                    <?php } else { ?>
                                                                                        <video controls>
                                                                                            <source src="<?php echo $media['path']; ?>"
                                                                                                type="video/mp4">
                                                                                            Your browser does not support the video tag.
                                                                                        </video>
                                                                                    <?php } ?>
                                                                                </div>
                                                                            <?php } ?>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                                <div class="mt-3">
                                                                    <?php if ($post['melikes'] > 0) { ?>
                                                                        <span class="text-muted ps-0"><i
                                                                                class="mdi mdi-heart text-danger"></i>
                                                                            <?php echo $post['likes']; ?> Likes</span>
                                                                    <?php } else { ?>
                                                                        <form class="form-horizontal"
                                                                            action="<?php echo base_url('social/post/like_post') ?>"
                                                                            method="POST"><?= csrf_field() ?>
                                                                            <input type="hidden" name="post_id"
                                                                                value="<?php echo $post['social_id'] ?>">
                                                                            <input type="hidden" name="course_id"
                                                                                value="<?php echo $crid ?>">
                                                                            <input type="hidden" name="tab" value="2">
                                                                            <button type="submit"
                                                                                class="btn btn-sm btn-link text-muted ps-0"><i
                                                                                    class="mdi mdi-heart text-danger"></i>
                                                                                <?php echo $post['likes']; ?> Likes</button>
                                                                        </form>
                                                                    <?php } ?>

                                                                </div>
                                                                <!-- Replies -->
                                                                <?php if (isset($replies[$post['social_id']])): ?>
                                                                    <?php
                                                                    $totalReplies = count($replies[$post['social_id']]);
                                                                    $visibleCount = 2;
                                                                    $i = 0;
                                                                    ?>
                                                                    <div class="post-user-comment-box mt-2">
                                                                        <a href="javascript:void(0);"
                                                                            class="text-muted font-13 d-inline-block mt-2">
                                                                            <i class="mdi mdi-reply"></i> Reply
                                                                        </a>

                                                                        <?php foreach ($replies[$post['social_id']] as $reply): ?>
                                                                            <div class="d-flex align-items-start mt-3 reply-item-<?php echo $post['social_id']; ?> <?php if ($i >= $visibleCount)
                                                                                                                                                                        echo 'd-none'; ?>">
                                                                                <!-- Reply Profile Picture -->
                                                                                <img class="me-2 avatar-sm rounded-circle"
                                                                                    src="<?php echo (isset($reply['profile_image']) && $reply['profile_foldername'])
                                                                                                ? base_url('assets/assets/uploads/profile/' . $reply['id_user'] . "/" . $reply['profile_foldername'] . "/" . $reply['profile_image'])
                                                                                                : base_url('public/aristo_assets/images/User_2_1.svg'); ?>"
                                                                                    alt="User image"
                                                                                    onerror="this.onerror=null; this.src='<?php echo base_url('public/aristo_assets/images/User_2_1.svg'); ?>';">


                                                                                <div class="w-100">
                                                                                    <?php if ($reply['last_updated_by'] == $user_id): ?>
                                                                                        <div class="dropdown float-end text-muted">
                                                                                            <a href="#"
                                                                                                class="dropdown-toggle text-muted font-18"
                                                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                                                <i class="mdi mdi-dots-horizontal"></i>
                                                                                            </a>
                                                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                                                <button type="button" class="dropdown-item"
                                                                                                    onclick="toggleEditReplyForm(<?php echo $reply['social_reply_id']; ?>)">Edit</button>


                                                                                                <form
                                                                                                    action="<?php echo base_url('social/post/delete_reply') ?>"
                                                                                                    method="POST"><?= csrf_field() ?>
                                                                                                    <input type="hidden" name="social_reply_id"
                                                                                                        value="<?php echo $reply['social_reply_id'] ?>">
                                                                                                    <input type="hidden" name="tab" value="2">
                                                                                                    <button type="submit"
                                                                                                        class="dropdown-item">Delete</button>
                                                                                                </form>

                                                                                            </div>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                    <div class="edit-reply-form bg-light p-3 rounded mt-2"
                                                                                        id="edit-reply-<?php echo $reply['social_reply_id']; ?>"
                                                                                        style="display: none;">
                                                                                        <form
                                                                                            action="<?php echo base_url('social/post/edit_reply') ?>"
                                                                                            method="POST"><?= csrf_field() ?>
                                                                                            <input type="hidden" name="social_reply_id"
                                                                                                value="<?php echo $reply['social_reply_id']; ?>">
                                                                                            <textarea name="reply_content"
                                                                                                class="form-control mb-2"><?php echo $reply['reply_content']; ?></textarea>
                                                                                            <input type="hidden" name="tab" value="2">
                                                                                            <div class="text-end">
                                                                                                <button type="submit"
                                                                                                    class="btn btn-primary btn-sm">Save</button>
                                                                                                <button type="button"
                                                                                                    class="btn btn-secondary btn-sm"
                                                                                                    onclick="toggleEditReplyForm(<?php echo $reply['social_reply_id']; ?>)">Cancel</button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                    <h6 class="mt-0 mb-1">
                                                                                        <a class="text-reset">
                                                                                            <?php echo $reply['replied_by'] . ' ' . $reply['replied_by_last_name']; ?>
                                                                                        </a>
                                                                                        <small
                                                                                            class="text-muted ms-2"><?php echo $reply['time_ago']; ?></small>
                                                                                    </h6>
                                                                                    <p class="mb-2">
                                                                                        <?php echo nl2br(htmlspecialchars($reply['reply_content'])); ?>
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                            <?php $i++; ?>
                                                                        <?php endforeach; ?>

                                                                        <?php if ($totalReplies > $visibleCount): ?>
                                                                            <div
                                                                                class="mt-2 view-more-wrapper-<?php echo $post['social_id']; ?>">
                                                                                <a href="javascript:void(0);"
                                                                                    class="text-primary font-13 read-more-replies"
                                                                                    data-post-id="<?php echo $post['social_id']; ?>">
                                                                                    View more replies
                                                                                    (<?php echo $totalReplies - $visibleCount; ?>)
                                                                                </a>
                                                                            </div>
                                                                        <?php endif; ?>

                                                                        <!-- Reply Form -->
                                                                        <div class="d-flex align-items-start mt-2">
                                                                            <div class="w-100">
                                                                                <form
                                                                                    action="<?php echo base_url('social/post/reply_post') ?>"
                                                                                    method="POST" class="form-horizontal">
                                                                                    <div class="row g-2">
                                                                                        <div class="col">
                                                                                            <input type="text" name="reply_comment"
                                                                                                class="form-control border-0 form-control-sm"
                                                                                                placeholder="Write a comment">
                                                                                        </div>
                                                                                        <div class="col-auto">
                                                                                            <input type="hidden" name="post_id"
                                                                                                value="<?php echo $post['social_id'] ?>">
                                                                                            <input type="hidden" name="course_id"
                                                                                                value="<?php echo $crid; ?>">
                                                                                            <input type="hidden" name="tab" value="2">
                                                                                            <button type="submit"
                                                                                                class="btn btn-success btn-sm">
                                                                                                <i class="fe-send"></i>
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>





                                                            </div>
                                                        </div>


                                                    <?php } ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- end card-->
                            </div> <!-- end col-->
                        </div> <!-- end tab-content-->
                    </div>

                    <!-- <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Course not assigned. Please contact Admin.
                    </div> -->
                    </div>




                    <?php if (in_array('4', $arrayuserlevel) || in_array('44', $arrayuserlevel) || in_array('46', $arrayuserlevel) || in_array('6', $arrayuserlevel) || in_array('67', $arrayuserlevel) || in_array('5', $arrayuserlevel) || in_array('45', $arrayuserlevel)) { ?>
                        <div class="col-lg-2">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="mb-0 mt-0 text-uppercase bg-light p-2"> Administration</h6>

                                    <?php  //print_r($editableCoursedata);
                                    if (!empty($editableCoursedata) && $editableCoursedata[0]['editable'] == '1') {
                                        // print_r("rretert"); 
                                    ?>

                                        <?php if (in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel) || in_array('4', $arrayuserlevel)) { // Trainers 

                                        ?>
                                            <form id="userAssign" action="<?php echo base_url('SCORM/scorm_courses/course_settings_view') ?>"
                                                method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="scourse_id" value="<?php echo $crid; ?>">
                                                <input type="hidden" name="tab" value="1">
                                                <input type="hidden" name="course_name"
                                                    value="<?php echo $clientCourseddata[0]['course_name']; ?>">
                                                <button type="submit" class="btn btn-block">Course Settings</button>
                                            </form>
                                            <?php if (in_array('4', $arrayuserlevel)) { ?>
                                                <form id="userAssign" action="<?php echo base_url('SCORM/scorm_courses/add_category'); ?>"
                                                    method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="scourse_id" value="<?php echo $crid; ?>">
                                                    <input type="hidden" name="course_name"
                                                        value="<?php echo $clientCourseddata[0]['course_name']; ?>">
                                                    <button type="submit" class="btn btn-block">Categories</button>
                                                </form>
                                            <?php } ?>
                                            <?php if ($clientCourseddata[0]['type'] == 5) { // AR/VR 
                                            ?>
                                                <h5 class="mb-0 mt-0 text-uppercase bg-light p-2"> AR/VR Scenarios</h5>
                                                <form id="userAssign" action="<?php echo base_url('XAPI/XAPI_scenarios_courses') ?>" method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="course_name"
                                                        value="<?php echo $clientCourseddata[0]['course_name']; ?>">
                                                    <input type="hidden" name="scourse_id" value="<?php echo $crid; ?>">
                                                    <button type="submit" class="btn btn-block">Scenario Settings</button>
                                                </form>
                                            <?php } ?>
                                        <?php } ?>
                                        <?php
                                        // print_r($clientCourseddata[0]['type']);

                                        if ($clientCourseddata[0]['type'] == 11 || $clientCourseddata[0]['type'] == 10) {

                                            if (in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel) || in_array('46', $arrayuserlevel) || in_array('4', $arrayuserlevel) || in_array('67', $arrayuserlevel)) {
                                        ?>

                                                <?php if ($clientCourseddata[0]['type'] == 10  && $client == 1) {
                                                    if (in_array('46', $arrayuserlevel) || in_array('67', $arrayuserlevel)) { // Developer ,QA,PM
                                                ?>
                                                        <!-- <form id="userAssign" action="<?php echo base_url('SCORM/course_builder/scorm_course_pages') ?>"
                                                    method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="course_name"
                                                        value="<?php echo $clientCourseddata[0]['course_name']; ?>">
                                                    <input type="hidden" name="course_type" value="<?php echo $clientCourseddata[0]['type']; ?>">
                                                    <button type="submit" class="btn btn-block">Development</button>
                                                </form> -->

                                                        <form id="userAssign" action="<?php echo base_url('SCORM/course_builder/Editor') ?>" method="POST"><?= csrf_field() ?>
                                                            <input type="hidden" name="course_name"
                                                                value="<?php echo $clientCourseddata[0]['course_name']; ?>">
                                                            <input type="hidden" name="crid" value="<?php echo $clientCourseddata[0]['scourse_id']; ?>">
                                                            <input type="hidden" name="course_type" value="<?php echo $clientCourseddata[0]['type']; ?>">
                                                            <button type="submit" class="btn btn-block">Course Builder</button>
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
                                                            <button type="submit" class="btn btn-block">Course Builder</button>
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
                                                        <button type="submit" class="btn btn-block">SCORM Upload</button>
                                                    </form>
                                                <?php } ?>
                                                <?php if ($clientCourseddata[0]['type'] == 11 && (in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel) || in_array('46', $arrayuserlevel) || in_array('67', $arrayuserlevel) || in_array('4', $arrayuserlevel))) { // Developer ,QA,PM,trainer
                                                ?>
                                                    <!-- <form id="userAssign"
                                                    action="<?php echo base_url('SCORM/course_builder/Scorm_course_pages/page_pdf_view') ?>"
                                                    method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="scourse_id"
                                                        value="<?php echo $clientCourseddata[0]['scourse_id']; ?>">
                                                    <input type="hidden" name="course_name"
                                                        value="<?php echo $clientCourseddata[0]['course_name']; ?>">
                                                    <button type="submit" class="btn btn-block">SCORM Export</button>
                                                </form> -->
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
                                                    <button type="submit" class="btn btn-block">Input Variables</button>
                                                </form>
                                                <form id="userAssign" action="<?php echo base_url('XAPI/XAPI_courses/output_variables') ?>"
                                                    method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="scourse_id"
                                                        value="<?php echo $clientCourseddata[0]['scourse_id']; ?>">
                                                    <input type="hidden" name="course_name"
                                                        value="<?php echo $clientCourseddata[0]['course_name']; ?>">
                                                    <button type="submit" class="btn btn-block">Output Variables</button>
                                                </form>

                                        <?php }
                                        } ?>
                                    <?php } ?>
                                    <?php if (in_array('5', $arrayuserlevel) || in_array('46', $arrayuserlevel) || in_array('67', $arrayuserlevel) || in_array('4', $arrayuserlevel) || in_array('45', $arrayuserlevel) || in_array('44', $arrayuserlevel)) { // Developer ,QA,PM,ID

                                    ?>


                                        <?php if (in_array('5', $arrayuserlevel) || in_array('4', $arrayuserlevel) || in_array('44', $arrayuserlevel)) {  // Instructors or Project Manager
                                        ?>
                                            <form id="userAssign"
                                                action="<?php echo base_url('XAPI/XAPI_courses/courseusersassigned_report') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="scourse_id" value="<?php echo $crid; ?>">
                                                <button type="submit" class="btn btn-block">Assign Users</button>
                                            </form>

                                            <?php if (in_array('4', $arrayuserlevel)) {  // PM 
                                            ?>
                                                <!-- <form id="userAssign" action="<?php echo base_url('Project_Manage/PM_ucn/team') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="scourse_id" value="<?php echo $crid; ?>">
                                                <input type="hidden" name="project_id" value="<?php echo $clientCourseddata[0]['project_id']; ?>">
                                                <button type="submit" class="btn btn-block">
                                                    Client Reviewers</button>
                                            </form>
                                            <form id="userAssign" action="<?php echo base_url('Task/Task_master/task_master_pm') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="scourse_id" value="<?php echo $crid; ?>">
                                                <input type="hidden" name="project_id" value="<?php echo $clientCourseddata[0]['project_id']; ?>">
                                                <button type="submit" class="btn btn-block">
                                                    Task Master</button>
                                            </form> -->
                                            <?php
                                            } ?>

                                            <!-- <form id="userAssign" action="<?php echo base_url('Reports/User_report/course_reports') ?>"
                                                method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="scourse_id" value="<?php echo $crid; ?>">
                                                <button type="submit" class="btn btn-block">Course Report</button>
                                            </form> -->
                                        <?php } ?>
                                        <?php if (in_array('46', $arrayuserlevel) || in_array('4', $arrayuserlevel) || in_array('45', $arrayuserlevel)) { // Developer ,PM,CR

                                        ?>
                                            <form id="userAssign"
                                                action="<?php echo base_url('SCORM/Course_builder/review_course/showfeedback') ?>"
                                                method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="scourse_id"
                                                    value="<?php echo $clientCourseddata[0]['scourse_id']; ?>">
                                                <input type="hidden" name="course_name"
                                                    value="<?php echo $clientCourseddata[0]['course_name']; ?>">
                                                <input type="hidden" name="stage" value="3">
                                                <button type="submit" class="btn btn-block">Feedback Report</button>
                                            </form>

                                    <?php }
                                    } ?>

                                </div>
                            </div> <!-- end card-->
                        </div>
                    <?php } ?>

                </div>

        <?php
    }
} ?>
        <div class="modal fade" id="modal6" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel6"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content tx-14">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel6">Select your rating for this Course:</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="star-container">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span class="star" data-rating="<?= $i ?>">&#9733;</span>
                            <?php endfor; ?>
                        </div>

                        <br>
                        <div class="form-group col-md-12">
                            <textarea id="comment" class="form-control" placeholder="Write your comment..."></textarea>
                        </div>

                        <br>
                        <button id="submitRatingBtn" class="btn btn-outline-primary waves-effect btn-xs waves-light">Submit Rating</button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary tx-13" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
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




        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


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
                            course_id: <?= $clientCourseddata[0]['scourse_id'] ?>
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