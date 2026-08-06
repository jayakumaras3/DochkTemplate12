<!-- chat area -->

<div id="loadMoreBlock">
    <?php
    // print_r(array_column($get_courses, 'billing_cycle'));
    // exit();
    if (in_array('2', array_column($get_courses, 'billing_cycle'))) { ?>
        <?php if (!empty($getsubscribebillingdata)) { ?>
            <div class="mg-t-10">
                <form action="<?= base_url('stripe/cancel-subscription'); ?>" method="post" style="display:inline;">
                    <?= csrf_field() ?>
                    <?php $price = ($get_courses[0]['cost'] > 0) ? $get_courses[0]['cost'] : 0;
                    // print_r($get_courses[0]['cost']);
                    // exit(); 
                    ?>
                    <?php if (isset($get_courses[0]['discount']) && $get_courses[0]['discount'] > 0) {
                        $price = $get_courses[0]['price'] - ($get_courses[0]['price'] * $get_courses[0]['discount'] / 100);
                    } ?>
                    <?php echo lang('Statements.State_0014'); ?>
                    <input type="hidden" name="subscription_id" value="">

                    <button type="submit"
                        style="background:none;border:none;padding:0;
                   color:#0d6efd;text-decoration:underline;cursor:pointer;">
                        <?php echo lang('Buttons.Cancel_Subscription'); ?>
                    </button>
                </form>
            </div><br />
        <?php  } else { ?>
            <div class="mg-t-10">
                <form action="<?= base_url('Payment/Billing'); ?>" method="post" style="display:inline;">
                    <?= csrf_field() ?>
                    <?php $price = ($get_courses[0]['cost'] > 0) ? $get_courses[0]['cost'] : 0;
                    // print_r($get_courses[0]['cost']);
                    // exit(); 
                    ?>
                    <?php if (isset($get_courses[0]['discount']) && $get_courses[0]['discount'] > 0) {
                        $price = $get_courses[0]['price'] - ($get_courses[0]['price'] * $get_courses[0]['discount'] / 100);
                    } ?>
                    <?php echo lang('Statements.State_0015'); ?>
                    <input type="hidden" name="price" value="<?php echo  number_format($price, 2) - .01; ?>">

                    <button type="submit"
                        style="background:none;border:none;padding:0;
                   color:#0d6efd;text-decoration:underline;cursor:pointer;">
                        <?php echo lang('Buttons.Billing_Page'); ?>
                    </button>
                </form>
            </div><br />
        <?php  } ?>
    <?php } ?>


    <div class="row">
        <?php if (!empty($get_courses)) {
            //print_r($get_courses);
            // exit();
            foreach ($get_courses as $course) {
                if ($course['scorm_id'] > 0) {
                    $price = ($course['price'] > 0) ? $course['price'] : 0; ?>
                    <?php if (isset($course['discount']) && $course['discount'] >= 0 && $course['discount'] < 100) {
                        $price = $course['price'] - ($course['price'] * $course['discount'] / 100);
                    } else {
                        $price = 0;
                    }

                    ?>
                    <div class="col-md-6 col-lg-4 col-xl-4">
                        <div class="card product-box">
                            <div class="card-body">
                                <!-- <div class="product-action">
                                    <form class="form-horizontal" action="<?php echo base_url('my_training/read_more') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="crid" value="<?php echo $course['scorm_id'] ?>">
                                        <input type="hidden" name="detail_type" value="5">
                                        <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-success btn-xs waves-effect waves-light"><i class="mdi mdi-play-circle-outline"></i></button>
                                    </form>
                                </div> -->
                                <?php
                                if (isset($course['thumbnail']) && $course['thumbnail'] != '') {
                                    $thumbnail =  base_url('assets/assets/uploads/SCORM_course_thumbnail/' . $course['scourse_id'] . '/' . $course['thumbnail']);
                                } else {
                                    $thumbnail = base_url('public/aristo_assets/images/default_thumbnail.png');
                                }
                                $playimg = base_url('assets/assets/img/play.png');
                                ?>

                                <form class="form-horizontal" action="<?php echo base_url('my_training/read_more') ?>" method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="crid" value="<?php echo $course['scourse_id'] ?>">
                                    <input type="hidden" name="detail_type" value="5">
                                    <input type="hidden" name="billing_cycle" value="<?php echo $course['billing_cycle'] ?>">
                                    <input type="hidden" name="payment_type" value="<?php echo $course['payment_type'] ?>">
                                    <input type="hidden" name="currency" value="<?php echo $course['currency']; ?>" ?>
                                    <input type="hidden" name="price" value="<?php echo  number_format($price, 2) - .01; ?>">


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
                                                alt="<?php echo lang('UI_Text.Course_Thumbnail'); ?>">
                                            <img style=" 
                                height: 40px;
                                width: 40px;
                                position: absolute;
                                opacity: 0.5;" src="<?php echo $playimg; ?>" alt="play" class="playBtn">
                                        </div>


                                    </button>
                                </form>

                                <!-- <div class="bg-light">
                                    <img src="<?= $thumbnail ?>" alt="Course Thumbnail" class="img-fluid">
                                </div> -->

                                <div class="product-info">
                                    <div class="row align-items-center">
                                        <h5 class="font-16 mt-0 sp-line-1"><span class="text-dark"><?php echo $course['course_name']; ?></span> </h5>

                                        <div class="col">
                                            <div class="text-warning mb-2 font-13">
                                                <?php $avg_rating = $course['avg_rating'];
                                                if ($avg_rating > 0) {
                                                    for ($i = 1; $i <= 5; $i++) {
                                                        if ($i <= $avg_rating) {
                                                            echo '<i class="fa fa-star"></i>';
                                                        } else {
                                                            echo '<i class="fa fa-star-o"></i>';
                                                        }
                                                    }
                                                } else {
                                                }

                                                ?>
                                            </div>
                                            <?php if ($course['language']) { ?>
                                                <h6 class="m-1"> <span class="text-muted"> <?php echo lang('UI_Text.Language'); ?> : <?php echo $course['language']; ?></span></h6>
                                            <?php } ?>
                                            <?php if ($course['duration']) { ?>
                                                <h6 class="m-1"> <span class="text-muted"> <?php echo lang('UI_Text.Duration'); ?> : <?php echo $course['duration']; ?> <?php echo lang('UI_Text.Minutes'); ?></span></h6>
                                            <?php } ?>
                                        </div>
                                        <div class="col-auto">

                                            <?php if ($price > 0 && $course['payment_type'] != 1 && $course['billing_cycle'] == 1  && $course['discount'] < 100) { ?>
                                                <div class="product-price-tag">
                                                    <form action="<?php echo base_url('my_training/read_more'); ?>" method="POST"><?= csrf_field() ?>
                                                        <input type="hidden" name="crid" value="<?php echo $course['scourse_id'] ?>">
                                                        <input type="hidden" name="detail_type" value="5">
                                                        <input type="hidden" name="billing_cycle" value="<?php echo $course['billing_cycle'] ?>">
                                                        <input type="hidden" name="payment_type" value="<?php echo $course['payment_type'] ?>">
                                                        <input type="hidden" name="currency" value="<?php echo $course['currency']; ?>" ?>
                                                        <input type="hidden" name="price" value="<?php echo  number_format($price, 2) - .01; ?>">
                                                        <Button class="form-control" style="display: block;background: none;  box-shadow: 4px 4px 5px rgba(0, 0, 0, 0.3);"> <?php echo '$ ' . number_format($price, 2) - .01; ?> </button>
                                                    </form>
                                                </div>
                                            <?php } ?>
                                            <!--  <i><span style="color:green">Free for you</i> -->
                                        </div>
                                    </div> <!-- end row -->
                                </div> <!-- end product info-->
                            </div>
                        </div> <!-- end card-->
                    </div>
            <?php  }
            }
        } else {
            ?>
            <script>
                $('#noMoreCourses').show();
                no_more_course = 2;

                $('.load-more').hide();
            </script>
        <?php
        }
        ?>
        <input type="hidden" id="rowcount" name="rowcount" value="<?php echo isset($rowId) ? $rowId : 0; ?>">
    </div>
</div>
</div>