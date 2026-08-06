<?php $userlevel = session()->get('userlevel');
$arrayuserlevel  = array_map('intval', explode(',', $userlevel));
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

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('my_training/tq_library'); ?>">ContentforU</a></li>
                </ol>
            </div>
            <h4 class="page-title">Course View</h4>
        </div>
    </div>
</div>

<!-- end page title -->
<?php if ($clientCourseddata != '') {
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
    }
}
?>
<div class="row">
    <div class="col-lg-9">
        <div class="card ribbon-box">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <?php if (isset($clientCourseddata[0]['thumbnail']) && $clientCourseddata[0]['thumbnail'] != '') {
                            $thumbnail = base_url('assets/assets/uploads/SCORM_course_thumbnail/' . $clientCourseddata[0]['scourse_id'] . '/' . $clientCourseddata[0]['thumbnail']);
                        } else {
                            $thumbnail = base_url('public/aristo_assets/images/default_thumbnail.png');
                        } ?>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <img style="border: 1px solid transparent; display: block;background: none;  border-color: rgb(0, 0, 0, 0.2);  box-shadow: 4px 4px 5px rgba(0, 0, 0, 0.3);" src="<?php echo $thumbnail ?>" alt="" class="img-fluid mx-auto d-block rounded">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php if ($getratingCourse['0']['count_user'] != 0) { ?>
                                    <div class="rating-container">
                                        <span id="ratingContainer" class="star-rating"></span>
                                        <span id="ratingContainer"><?php echo ($getratingCourse['0']['count_user'] != 0) ? '(' . $getratingCourse['0']['count_user'] . ')' : ''; ?></span>
                                    </div><br />
                                <?php }
                                ?>
                                Language : <span class="text-muted me-2">English</span><br />
                                Duration : <span class="text-muted me-2"> <?php if ($clientCourseddata[0]['duration'] > 0) { ?>
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
                                                                                }  ?>
                                    <?php } ?> </span> <br />
                                <?php //print_r($clientCourseddata); 
                                ?>

                                <?php if (strlen($clientCourseddata[0]['last_updated_by']) > 5) {  ?>
                                    Last Accessed On : <span class="text-muted me-2"> <?php echo date('m-d-Y', strtotime($clientCourseddata[0]['last_updated_by'])); ?></span><br />
                                <?php } else { ?>
                                <?php } ?>
                                <?php if (isset($clientCourseddata[0]['attempt'])) { ?>
                                    Attempts : <span class="text-muted me-2"><?php echo $clientCourseddata[0]['attempt'] ?></span>
                                <?php } ?>
                                <?php if (isset($clientCourseddata[0]['mode'])) {
                                    $course_status = $clientCourseddata[0]['mode'];
                                } ?>
                            </div>
                        </div>
                        <div class="row mb-3">

                            <div class="col-md-12">
                                <?php if (isset($clientCourseddata[0]['course_status'])) {
                                    if ($course_status == 2) { ?>
                                        <h4><?php
                                            if ($clientCourseddata[0]['course_status'] == '2') { ?>
                                                <div class="ribbon-two ribbon-two-success"><span>Completed</span></div>
                                            <?php  } elseif ($clientCourseddata[0]['course_status'] == '1') { ?>
                                                <div class="ribbon-two ribbon-two-info"><span>In Progress</span></div>
                                            <?php } elseif ($clientCourseddata[0]['course_status'] == '0') { ?>
                                                <div class="ribbon-two ribbon-two-warning"><span>Not Started</span></div>
                                            <?php } else { ?>
                                                <div class="ribbon-two ribbon-two-warning"><span>Not Started</span></div>
                                            <?php } ?>
                                            <?php if ($clientCourseddata[0]['lesson_status'] == 'incomplete') { ?>
                                                <div class="ribbon-two ribbon-two-info"><span>In Progress</span></div>
                                            <?php } ?>
                                        </h4>
                                    <?php
                                    } else { ?>
                                        <h4><?php if ($course_status == '1') { ?>
                                                <div class="ribbon-two ribbon-two-danger"><span>Development</span></div>
                                            <?php  } elseif ($course_status == '3') { ?>
                                                <div class="ribbon-two ribbon-two-warning"><span>Alpha</span></div>
                                            <?php  } elseif ($course_status == '4') { ?>
                                                <div class="ribbon-two ribbon-two-info"><span>Alpha 2</span></div>
                                            <?php  } elseif ($course_status == '5') { ?>
                                                <div class="ribbon-two ribbon-two-warning"><span>Beta</span></div>
                                            <?php  } elseif ($course_status == '6') { ?>
                                                <div class="ribbon-two ribbon-two-info"><span>Beta 2</span></div>
                                            <?php  } elseif ($course_status == '7') { ?>
                                                <div class="ribbon-two ribbon-two-warning"><span>Gamma</span></div>
                                            <?php  } elseif ($course_status == '8') { ?>
                                                <div class="ribbon-two ribbon-two-info"><span>Gamma 2</span></div>
                                            <?php } ?>
                                        </h4>
                                <?php }
                                } ?>

                            </div>
                            <div class="col-md-12">
                                <?php //print_r($getCoursesAssigned);
                                if (!empty($getCoursesAssigned[0]['scourse_id'])) {
                                    if (isset($pagedata[0]['page_id'])) { ?>
                                        <?php if ($clientCourseddata[0]['type'] == 11) { ?>
                                            <?php if ($course_status == '2' || $getCoursesAssigned[0]['course_status'] == '3') { ?>
                                                <form method="POST" onsubmit="LaunchCourse(this)">
                                                    <button class="btn btn-outline-success waves-effect btn-sm waves-light mb-3" onclick="openForm()"><i class="mdi mdi-play-circle-outline me-2"></i> Launch</button>
                                                </form>
                                            <?php } else { ?>
                                                <form method="POST" onsubmit="LaunchCourse(this)">
                                                    <button class="btn btn-outline-danger waves-effect btn-sm waves-light mb-3" onclick="openForm()"><i class="mdi mdi-play-circle-outline me-2"></i> Review</button>
                                                </form>
                                            <?php } ?>

                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>

                                <?php if (isset($clientCourseddata[0]['upload']) &&  $clientCourseddata[0]['upload'] != '') {
                                    $session_Value = [];
                                    $session_Value = [
                                        'course_id' => $clientCourseddata[0]['scourse_id'],
                                        'foldername' => $clientCourseddata[0]['upload'],
                                        'type' => $type,
                                    ];
                                    session()->set($session_Value);
                                ?>


                                    <?php if ($clientCourseddata[0]['type'] == 5) { ?>
                                        <a onclick="OpenNewWindow('<?php echo base_url('SCORM/scorm_launch/tinCanlanch'); ?>')"> <button type="button" class="btn btn-outline-success waves-effect btn-sm waves-light mb-3"><span class="btn-label"><i class="icon-control-play"></i></span>Launch</button></a>
                                    <?php } else { ?>
                                        <a onclick="OpenNewWindow('<?php echo base_url('SCORM/scorm_launch'); ?>')"> <button type="button" class="btn btn-outline-success waves-effect btn-sm waves-light mb-3"><span class="btn-label"><i class="icon-control-play"></i></span>Launch</button></a>
                                    <?php }
                                } else if (strlen($clientCourseddata[0]['launch_link']) > 5) { ?>
                                    <a onclick="OpenNewWindowmiddlepop('<?php echo  $clientCourseddata[0]['scourse_id'] ?>','4')"> <button type="button" class="btn btn-outline-success waves-effect btn-sm waves-light mb-3"><span class="btn-label"><i class="icon-control-play"></i></span>Launch</button></a>
                                <?php } elseif ($clientCourseddata[0]['type'] == 8) {  ?>
                                    <a onclick="OpenNewWindow('<?php echo base_url('Assessment/launch'); ?>')"><button class="btn btn-outline-success waves-effect btn-sm waves-light mb-3"><span class="btn-label"><i class="icon-control-play"></i></span>Launch</button></a>
                                <?php }

                                if (strlen($clientCourseddata[0]['promo_video']) > 3) {
                                    $promo =  base_url('assets/assets/uploads/SCORM_course_promovideo/' .  $clientCourseddata[0]['scourse_id'] . '/' .  $clientCourseddata[0]['promo_video']);
                                ?>
                                    <a onclick="OpenNewWindowmiddle('<?php echo base_url('SCORM/scorm_dashboard/launchpromo_video'); ?>')"> <button type="button" class="btn btn-outline-warning waves-effect btn-sm waves-light mb-3"><?= $demoButton ?></button></a>
                                <?php } ?>


                            </div><br />
                            <?php

                            if (empty($getratingCourseofuser)) {
                                if (isset($clientCourseddata[0]['course_status'])) {
                                    if ($clientCourseddata[0]['course_status'] == '2') { ?>
                                        <!-- <div><br />
                                            <a type="button" href="#modal6" class="btn btn-outline-primary waves-effect btn-sm waves-light mb-3" data-bs-toggle="modal" data-bs-target="#modal6">
                                                <span class="btn-label"><i class="mdi mdi-star"></i></span>Rate this Course
                                            </a>
                                        </div> -->
                            <?php
                                    }
                                }
                            } ?>
                        </div>

                    </div> <!-- end col -->
                    <div class="col-lg-8">
                        <div class="ps-xl-3 mt-3 mt-xl-0">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h4 class="mb-1"><?php echo $clientCourseddata[0]['course_name']; ?></h4>

                                </div>
                                <p class="text-muted mt-2">
                                    <?php if (isset($clientCourseddata[0]['description'])) {
                                        if (strlen($clientCourseddata[0]['description']) > 10) {
                                            if (strlen($clientCourseddata[0]['description']) > 5) {
                                                echo $clientCourseddata[0]['description'];
                                            }
                                        }
                                    } ?>
                                </p>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div>
                                            <?php
                                            if (strlen($clientCourseddata[0]['objectives']) > 5) {
                                                $objectives = $clientCourseddata[0]['objectives'];
                                                print_r(str_replace("<li>", '<p class="text-muted"><i class="mdi mdi-checkbox-marked-circle-outline h6 text-primary me-2"></i>', $objectives, $i));
                                                str_replace("</li>", '</p>', $i, $k);
                                            }
                                            ?>

                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->
                </div>
            </div> <!-- end card-->
        </div> <!-- end col-->
    </div>
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-1">Category</h4>

                <div class="d-flex align-items-start mt-3">

                    <div class="w-100">
                        <a class="mt-1 font-14 text-reset" href="javascript:void(0);">
                            <span class="text-muted">
                                Marketing
                            </span>
                        </a>
                    </div>
                </div>


            </div>
        </div> <!-- end card-->
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-1">Skills</h4>

                <div class="d-flex align-items-start mt-3">

                    <div class="w-100">
                        <a class="mt-1 font-14 text-reset" href="javascript:void(0);">
                            <span class="text-muted">
                                Sales Manager
                            </span>
                        </a>
                    </div>
                </div>


            </div>
        </div> <!-- end card-->
    </div>
</div>


<div class="modal fade" id="modal6" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel6" aria-hidden="true">
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
                    <?php for ($i = 1; $i <= 5; $i++) : ?>
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
        <?php $page_number =  isset($pagedata[0]['page_number']) ? $pagedata[0]['page_number'] : '1'; ?>
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