<div class="col-xl-6 col-lg-12 order-lg-2 order-xl-1">
    <div class="card">
        <div class="card-body text-secondary">
            <h5 class="card-title">Contentforu</h5>
            <p class="card-text">Dochek is offering 4 courses from the Contentforu library per month free of cost. <br>If you need access to full library of 500 plus courses please contact Admin.</p>
        </div>
    </div>

    <?php if ($clientCourseddata != '') {
        if (count($clientCourseddata) > 0) { ?>
            <div id="holder" class="row row-cols-1 row-cols-md-6 g-3 ">
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

                    if ($j >= 8) {
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

                        $thumbnail =  base_url('assets/assets/uploads/SCORM_course_thumbnail/' . $clienteachCourseddata['scourse_id'] . '/' . $clienteachCourseddata['thumbnail']);
                    } else {
                        $thumbnail = base_url('public/aristo_assets/images/default_thumbnail.png');
                    }
                    $playimg = base_url('assets/assets/img/play.png');
                ?>

                    <div class="col-md-12 col-lg-6 col-xl-6">
                        <div class="card   ">
                            <div class="card-body" style="text-align:left; ">
                                <form class="form-horizontal" action="<?php echo base_url('my_training/library') ?>" method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="crid" value="<?php echo $clienteachCourseddata['scourse_id'] ?>">
                                    <input type="hidden" name="detail_type" value="1">

                                    <button style="border: none; border:0; padding-top: 5px; outline: none; background: none; width: 100%; text-align:center">
                                        <div style="display: box;
  display: flex;
  box-align: center;
  align-items: center;
  box-pack: center;
  justify-content: center;">
                                            <img class="img-fluid mx-auto d-block rounded" src="<?= $thumbnail ?>" style="border: 1px solid transparent; display: block;background: none;  border-color: rgb(0, 0, 0, 0.2);  box-shadow: 4px 4px 5px rgba(0, 0, 0, 0.3);  height:150px;" alt="<?php echo $clienteachCourseddata['course_name'] ?>">
                                            <img style=" 
                                height: 40px;
                                width: 40px;
                                position: absolute;
                                opacity: 0.5;"
                                                src="<?php echo $playimg; ?>" alt="play" class="playBtn">
                                        </div>


                                    </button>
                                </form>
                                <div style="padding-left: 10px;">



                                    <h5 class="font-12 my-1 sp-line-1"><a class="text-dark" title="<?php echo $course_name; ?>"><?php echo $shortened_name ?></a> </h5>


                                    <span class="font-10 text-muted"> Duration : <?php if ($clienteachCourseddata['duration'] > 0) { ?>
                                            <?php
                                                                                        $duration = $clienteachCourseddata['duration'];
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

        }
    }
    ?>
    <div class="card text-white bg-success text-xs-center">

    </div>
</div>