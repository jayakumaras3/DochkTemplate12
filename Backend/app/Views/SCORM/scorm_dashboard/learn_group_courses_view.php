<?php $userlevel = session()->get('userlevel');
$arrayuserlevel  = array_map('intval', explode(',', $userlevel));
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('my_training'); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('my_training/user_learn_group'); ?>">Course Group</a></li>
                    
                </ol>
            </div>
            <h4 class="page-title">Course Course Group</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<?php if ($clientCourseddata != '') {
    if (count($clientCourseddata) > 0) { ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <h5><b>Course Group Name :</b> <?php echo $clientCourseddata[0]['group_name'] ?></h5>
                                <h5><?php echo ($clientCourseddata[0]['due_date'] != '0000-00-00') ? '<b>Due Date : </b>' . date('d-m-Y', strtotime($clientCourseddata[0]['due_date'])) : 'No' ?></h5>
                            </div>
                            <div class="col-lg-6">
                                <h5>
                                    <h6 class="text-uppercase">Completion <span class="float-end"></h6>
                                    <div class="progress progress-sm m-0">
                                        <div class="progress-bar bg-blue" role="progressbar" aria-valuenow="<?php echo $complete_percent ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $complete_percent ?>%">
                                            <span class="visually-hidden"><?php echo $complete_percent ?>% Complete</span>
                                        </div>
                                    </div>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $j = 0;
        foreach ($clientCourseddata as $eachclientCourseddata) {
            $j = $j + 1;
            // print_r($eachclientCourseddata);
            // exit();
            $demoButton = '<span class="btn-label"><i class="icon-social-youtube"></i></span>Preview';
            if (strlen($eachclientCourseddata['lesson_status']) > 2) {
                if ($eachclientCourseddata['lesson_status'] == 'completed' ||  $eachclientCourseddata['lesson_status'] == 'passed') {
                    $lunchbutton =  'Completed';
                } elseif ($eachclientCourseddata['lesson_status'] == 'incomplete') {
                    $lunchbutton =  'Resume';
                } elseif ($eachclientCourseddata['lesson_status'] == 'not started') {
                    $lunchbutton =  'Launch';
                } elseif ($eachclientCourseddata['lesson_status'] == 'failed') {
                    $lunchbutton =  'Resume';
                }
            } else {
                $lunchbutton =  'Launch';
            }

            // $totalTime = '00:00:00';
            // $trimmedsessionTime = '00:00:00';
            // $splitotalTime = '00:00:00';
            // if (strlen($eachclientCourseddata['session_time']) > 4) {
            //     if ($eachclientCourseddata['total_time'] == '' || $eachclientCourseddata['total_time'] == '00:00:00.00') {
            //         $splitotalTime = '00:00:00';
            //     } else {
            //         $splitotalTime = explode('.', $eachclientCourseddata['total_time'])[0];
            //     }
            //     if (strlen($eachclientCourseddata['session_time']) > 8) {
            //         $splitsession_time = explode('.', $eachclientCourseddata['session_time']);
            //         $trimmedsessionTime = substr($splitsession_time[0], 2);
            //     }
            //     if (strlen($eachclientCourseddata['session_time']) == 8) {
            //         $trimmedsessionTime = explode('.', $eachclientCourseddata['session_time'])[0];
            //     }
            //     $matches0 = explode(':', $splitotalTime); // split up the string
            //     $matches1 = explode(':', $trimmedsessionTime);
            //     $sec0 = $matches0[0] * 60 * 60 + $matches0[1] * 60 + $matches0[2];
            //     $sec1 = $sec0 + $matches1[0] * 3600 + $matches1[1] * 60 + $matches1[2]; // get total seconds
            //     $h = intval(($sec1) / 3600);
            //     $m = intval(($sec1 - $h * 3600) / 60);
            //     $s = $sec1 - $h * 3600 - $m * 60;
            //     $totalTime = str_pad($h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($m, 2, '0', STR_PAD_LEFT) . ':' . str_pad($s, 2, '0', STR_PAD_LEFT);
            //     list($hours, $minutes, $seconds) = explode(':', $totalTime);
            //     $watchedSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;
            // } else {
            //     $watchedSeconds = 0;
            // }
            // if ($eachclientCourseddata['duration'] > 0) {
            //     $duration = $eachclientCourseddata['duration'];
            //     $totalSeconds = $duration * 60;
            // } else {
            //     $totalSeconds = 0;
            // }
            // if ($totalSeconds > 0) {

            //     $com_percent = ($watchedSeconds / $totalSeconds) * 100;
            //     print_r($totalSeconds);
            //     $completion_percentage =  round($com_percent, 2);
            // } else {
            //     $completion_percentage =  0;
            // }
            // if (strlen($eachclientCourseddata['lesson_status']) > 2) {
            //     if ($eachclientCourseddata['lesson_status'] == 'completed' || $eachclientCourseddata['lesson_status'] == 'passed') {
            //         $completion_percentage =  100;
            //     } elseif ($eachclientCourseddata['lesson_status'] == 'incomplete') {
            //         $completion_percentage =  50;
            //     } elseif ($eachclientCourseddata['lesson_status'] == 'not started') {
            //         $completion_percentage =  0;
            //     }
            // } else {
            //     $completion_percentage =  0;
            // }

        ?>

            <div class="row">
                <div class="col-12">
                    <div class="card  ribbon-box">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4">

                                    <?php if (isset($eachclientCourseddata['thumbnail']) && $eachclientCourseddata['thumbnail'] != '') {
                                        $thumbnail = base_url('assets/assets/uploads/SCORM_course_thumbnail/' . $eachclientCourseddata['scourse_id'] . '/' . $eachclientCourseddata['thumbnail']);
                                    } else {
                                        $thumbnail = 'style="height:150px; background-size: 100% 100%;background-repeat: no-repeat;";';
                                    } ?>

                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <img src="<?php echo $thumbnail ?>" alt="" class="img-fluid mx-auto d-block rounded">
                                        </div>
                                    </div>
                                    <style>
                                        .button-container {
                                            display: flex;
                                            gap: 10px;
                                            /* Adjust space between buttons */
                                            align-items: center;
                                            /* Aligns items vertically in the center */
                                        }
                                    </style>

                                </div>
                                <div class="col-lg-8">
                                    <div class="ps-xl-3 mt-3 mt-xl-0">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <h4 class="mb-1"><?php echo $eachclientCourseddata['course_name']; ?> <button data-bs-toggle="modal" data-bs-target="#scrollable-modal-<?php echo  $j; ?>" class="btn btn-default" title="Description">
                                                        <i class="fe-info"></i>
                                                    </button>
                                                </h4>
                                                <!-- Full width modal content -->


                                                <div id="scrollable-modal-<?php echo  $j; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="scrollableModalTitle-<?php echo  $j; ?>" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="scrollableModalTitle-<?php echo  $j; ?>">Description</h4>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p class="text-muted mb-4">
                                                                    <?php if (isset($eachclientCourseddata['description']) && strlen($eachclientCourseddata['description']) > 10) {
                                                                        echo $eachclientCourseddata['description'];
                                                                    } ?>
                                                                </p>
                                                                <div class="row mb-3">
                                                                    <div class="col-md-12">
                                                                        <div>
                                                                            <?php
                                                                            if (isset($eachclientCourseddata['objectives']) && strlen($eachclientCourseddata['objectives']) > 5) {
                                                                                $objectives = $eachclientCourseddata['objectives'];
                                                                                // Assuming $objectives is a string containing <li> items
                                                                                $objectives = str_replace("<li>", '<p class="text-muted"><i class="mdi mdi-checkbox-marked-circle-outline h6 text-primary me-2"></i>', $objectives);
                                                                                $objectives = str_replace("</li>", '</p>', $objectives);
                                                                                echo $objectives; // Output the formatted objectives
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </div><!-- /.modal-content -->
                                                    </div><!-- /.modal-dialog -->
                                                </div><!-- /.modal -->
                                                Language : <span class="text-muted me-2">English</span><br/>
                                                Duration : <span class="text-muted me-2"> <?php if ($eachclientCourseddata['duration'] > 0) { ?>
                                                        <?php
                                                                                                $duration = $eachclientCourseddata['duration'];
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
                                           
                                            <div class="col-sm-4">
                                                <?php if (strlen($eachclientCourseddata['lesson_status']) > 2) {
                                                    if ($eachclientCourseddata['lesson_status'] == 'completed' || $eachclientCourseddata['lesson_status'] == 'passed') { ?>
                                                        <div class="ribbon ribbon-success float-end"><?php echo 'Completed' ?></div>
                                                    <?php  } elseif ($eachclientCourseddata['lesson_status'] == 'incomplete') { ?>
                                                        <div class="ribbon ribbon-info float-end"><?php echo 'In progress' ?></div>
                                                    <?php } elseif ($eachclientCourseddata['lesson_status'] == 'not started') { ?>
                                                        <<div class="ribbon ribbon-danger float-end"><?php echo 'Not Started' ?></div>
                                                    <?php } ?>

                                                <?php } else { ?>
                                                    <div class="ribbon ribbon-danger float-end"><?php echo 'Not Started'; ?></div>
                                                <?php } ?><br/>

                                                
                                            </div><!-- end col-->
                                            <span class="mb-3"></span>
                                            <div class="row mb-3">
                                                <div class="col-md-12 button-container">
                                                    <?php if (isset($eachclientCourseddata['upload']) &&  $eachclientCourseddata['upload'] != '') {


                                                    ?>
                                                        <?php if ($eachclientCourseddata['type'] == 5) { ?>
                                                            <!-- <a onclick="OpenNewWindow('<?php echo base_url('SCORM/scorm_launch/tinCanlanch'); ?>')"> <button type="button" class="btn btn-success waves-effect waves-light"><span class="btn-label"><i class="icon-control-play"></i></span><?= $lunchbutton ?></button></a> -->
                                                            <form id="courseForm<?php echo $eachclientCourseddata['scourse_id']; ?>" action="<?php echo base_url('SCORM/scorm_launch/tinCanlanch'); ?>" method="POST"><?= csrf_field() ?>
                                                                <input type="hidden" name="course_id" value="<?php echo $eachclientCourseddata['scourse_id']; ?>">
                                                                <input type="hidden" name="foldername" value="<?php echo $eachclientCourseddata['upload']; ?>">
                                                                <input type="hidden" name="type" value="<?php echo $eachclientCourseddata['type']; ?>">
                                                                <input type="hidden" name="group_id" value="<?php echo isset($eachclientCourseddata['group_id']) ? $eachclientCourseddata['group_id'] : 0; ?>">
                                                                <button type="button" class="btn btn-outline-success waves-effect btn-sm waves-light mb-3" onclick="submitFormInNewWindow(<?php echo $eachclientCourseddata['scourse_id']; ?>)">
                                                                    <span class="btn-label"><i class="icon-control-play"></i></span><?= $lunchbutton ?>
                                                                </button>
                                                            </form>
                                                        <?php } else { ?>
                                                            <!-- <a onclick="OpenNewWindow('<?php echo base_url('SCORM/scorm_launch'); ?>')"> <button type="button" class="btn btn-success waves-effect waves-light"><span class="btn-label"><i class="icon-control-play"></i></span><?= $lunchbutton ?></button></a> -->

                                                            <form id="courseForm<?php echo $eachclientCourseddata['scourse_id']; ?>" action="<?php echo base_url('SCORM/scorm_launch'); ?>" method="POST"><?= csrf_field() ?>
                                                                <input type="hidden" name="course_id" value="<?php echo $eachclientCourseddata['scourse_id']; ?>">
                                                                <input type="hidden" name="foldername" value="<?php echo $eachclientCourseddata['upload']; ?>">
                                                                <input type="hidden" name="type" value="<?php echo $eachclientCourseddata['type']; ?>">
                                                                <input type="hidden" name="group_id" value="<?php echo isset($eachclientCourseddata['group_id']) ? $eachclientCourseddata['group_id'] : 0; ?>">
                                                                <button type="button" class="btn btn-outline-success waves-effect btn-sm waves-light mb-3" onclick="submitFormInNewWindow(<?php echo $eachclientCourseddata['scourse_id']; ?>)">
                                                                    <span class="btn-label"><i class="icon-control-play"></i></span><?= $lunchbutton ?>
                                                                </button>
                                                            </form>

                                                        <?php
                                                        }
                                                    } else if (strlen($eachclientCourseddata['launch_link']) > 5) { ?>
                                                        <a onclick="OpenNewWindowmiddlepop('<?php echo  $eachclientCourseddata['scourse_id'] ?>','4')"> <button type="button" class="btn btn-success waves-effect waves-light"><span class="btn-label"><i class="icon-control-play"></i></span><?= $lunchbutton ?></button></a>
                                                    <?php } elseif ($eachclientCourseddata['type'] == 8) {  ?>
                                                        <a onclick="OpenNewWindow('<?php echo base_url('Assessment/launch'); ?>')"><button class="btn-sm btn btn-success"><span class="btn-label"><i class="icon-control-play"></i></span><?= $lunchbutton ?></button></a>
                                                    <?php }

                                                    if (strlen($eachclientCourseddata['promo_video']) > 3) {
                                                        $promo =  base_url('assets/assets/uploads/SCORM_course_promovideo/' .  $eachclientCourseddata['scourse_id'] . '/' .  $eachclientCourseddata['promo_video']);
                                                    ?>
                                                        <a onclick="OpenNewWindowmiddle('<?php echo base_url('SCORM/scorm_dashboard/launchpromo_video'); ?>')"> <button type="button" class="btn btn-outline-warning waves-effect btn-sm waves-light mb-3"><?= $demoButton ?></button></a>
                                                    <?php } ?>

                                                    <?php if ($type > 0) {
                                                        if (in_array('1', $arraystakeholders)) { // only TQ users access for Cart
                                                    ?>
                                                            <a href="<?php echo base_url('Demo/cart/addToCart/' .  $eachclientCourseddata['scourse_id']) ?>"> <button type="button" class="btn btn-success waves-effect waves-light"><i class="fa fa-shopping-cart"></i></button></a>
                                                    <?php }
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


<?php

        }
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
<script type="text/javascript">
    function submitFormInNewWindow(courseId) {
        var form = document.getElementById('courseForm' + courseId);
        var formAction = form.action;

        // Define the new window parameters
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

        // Open a new window
        var newwin = window.open('', 'Launcher', params);

        // Handle form submission
        form.target = 'Launcher'; // Set the form target to the new window
        form.submit(); // Submit the form
    }
</script>