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

<div class="row">
    <div class="col-xl-3 col-lg-6 order-lg-1 order-xl-1">

        <div class="card">
            <div class="card-body">

                <div class="list-group list-group-flush mt-2 font-15">
                    <a href="javascript:void(0);" class="list-group-item list-group-item-action border-0"> <span class="mdi mdi-circle text-info me-2"></span>Business Skills</a>
                    <a href="javascript:void(0);" class="list-group-item list-group-item-action border-0"> <span class="mdi mdi-circle text-primary me-2"></span>Compliance</a>
                    <a href="javascript:void(0);" class="list-group-item list-group-item-action border-0"> <span class="mdi mdi-circle text-success me-2"></span>DEI</a>
                    <a href="javascript:void(0);" class="list-group-item list-group-item-action border-0"> <span class="mdi mdi-circle text-danger me-2"></span>Technology</a>
                    <a href="javascript:void(0);" class="list-group-item list-group-item-action border-0"> <span class="mdi mdi-circle text-warning me-2"></span>Safety</a>
                    <a href="javascript:void(0);" class="list-group-item list-group-item-action border-0"> <span class="mdi mdi-circle text-blue me-2"></span>Wellness</a>
                    <a href="javascript:void(0);" class="list-group-item list-group-item-action border-0"> <span class="mdi mdi-circle text-pink me-2"></span>Healthcare</a>
                </div>

                <h6 class="font-13 text-muted ps-3 my-3 text-uppercase">Course Bundles</h6>

                <div class="px-2">
                    <div class="d-flex align-items-start mb-2">
                        <div class="w-100 ps-2">
                            <span class="badge bg-pink mt-1 float-end">102</span>
                            <h5 class="mt-1 mb-0 font-family-primary fw-semibold"><a href="javascript: void(0);" class="text-reset">Manager Essentials</a></h5>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-2">

                        <div class="w-100 ps-2">
                            <span class="badge bg-pink mt-1 float-end">35</span>
                            <h5 class="mt-1 mb-0 font-family-primary fw-semibold"><a href="javascript: void(0);" class="text-reset">HR Essentials</a></h5>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-2">

                        <div class="w-100 ps-2">
                            <span class="badge bg-pink mt-1 float-end">22</span>
                            <h5 class="mt-1 mb-0 font-family-primary fw-semibold"><a href="javascript: void(0);" class="text-reset">Finance Essentials</a></h5>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div> <!-- end col -->

    <div class="col-xl-6 col-lg-12 order-lg-2 order-xl-1">


        <!-- Story Box-->



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
                            <div class="cardtile   ">
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

    </div>

    <div class="col-xl-3 col-lg-6 order-lg-1 order-xl-2">
        <!-- news -->
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-1">Trending</h4>

                <div class="d-flex align-items-start mt-3">

                    <div class="w-100">
                        <a class="mt-1 font-14 text-reset" href="javascript:void(0);">
                            <span class="text-muted">
                                Emotional Quotient (EQ) Strategies
                            </span>
                        </a>
                    </div>
                </div>
                <div class="d-flex align-items-start mt-3">
                    <div class="w-100">
                        <a class="mt-1 font-14 text-reset" href="javascript:void(0);">

                            <span class="text-muted">
                                Basic Digital Skills
                            </span>
                        </a>
                    </div>
                </div>

                <div class="d-flex align-items-start mt-3">
                    <div class="w-100">
                        <a class="mt-1 font-14 text-reset" href="javascript:void(0);">
                            <span class="text-muted">
                                Introduction to Emotional Quotient (EQ)
                            </span>
                        </a>
                    </div>
                </div>

                <div class="d-flex align-items-start mt-3">
                    <div class="w-100">
                        <a class="mt-1 font-14 text-reset" href="javascript:void(0);">
                            <span class="text-muted">
                                Network Cybersecurity Attacks– Management and Monitoring
                            </span>
                        </a>
                    </div>
                </div>

            </div> <!-- end card-body-->
        </div> <!-- end card-->

        <!-- People -->
        <div class="card">
            <div class="card-body pb-0">


                <h4 class="header-title mb-3">New Release</h4>

                <div class="inbox-widget">
                    <div class="inbox-item">
                        <a class="mt-1 font-14 text-reset" href="javascript:void(0);">
                            <p class="inbox-item-text">Becoming an Ally in the Workplace</p>
                        </a>
                    </div>
                </div>
                <div class="inbox-widget">
                    <div class="inbox-item">
                        <a class="mt-1 font-14 text-reset" href="javascript:void(0);">
                            <p class="inbox-item-text">Inclusion for Workers With Disabilities</p>
                        </a>
                    </div>
                </div>
                <div class="inbox-widget">
                    <div class="inbox-item">
                        <a class="mt-1 font-14 text-reset" href="javascript:void(0);">
                            <p class="inbox-item-text">Leadership Training for Women</p>
                        </a>
                    </div>
                </div>
                <div class="mt-2 mb-3 text-center">
                    <a href="">View More <i class="mdi mdi-arrow-right"></i></a>
                </div>

            </div> <!-- end card-body -->
        </div>
        <!-- end video -->
    </div> <!-- end col -->
</div> <!--end row -->

</div> <!-- container -->