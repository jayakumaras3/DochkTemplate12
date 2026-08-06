<?php if (session()->get('error')) :
    echo '<script>alert("' . session()->get('error') . '")</script>';
endif;
$client =  session()->get('client');
$arraystakeholders  = explode(',', $client);

?>
<style>
    th,
    td {
        padding-left: 8px;
        /* padding-top: 8px; */
        /* padding-bottom: 8px; */
    }

    .extraspace {
        margin-bottom: 10px;
    }

    [data-tooltip]::before {
        position: absolute;
        content: attr(data-tooltip);
        font-size: 10px;
        opacity: 0;
        margin-top: 20px;
        width: 80px;
        background-color: #5A5A5A;
        color: #fff;
        text-align: center;
        border-radius: 3px;
        padding: 2px 0;
    }

    [data-tooltip]:hover::before {
        opacity: 1;
    }

    [data-tooltip]:not([data-tooltip-persistent])::before {
        pointer-events: none;
    }

    /* Popup container - can be anything you want */
    .popup {
        position: relative;
        display: inline-block;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* The actual popup */
    .popup .popuptext {
        visibility: hidden;
        width: 450px;
        background-color: #555;
        color: #fff;
        text-align: left;
        border-radius: 6px;
        padding: 10px;
        position: absolute;
        z-index: 1;
        top: 125%;
        left: 100%;
        margin-left: -450px;
    }

    .btn-margin-custom {
        border: 2px;
        border-color: coral;
        display: flex;
        justify-content: space-evenly;
    }

    /* Popup arrow */
    .popup .popuptext::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;

        /* border-color: #555 transparent transparent transparent; */
    }

    /* Toggle this class - hide and show the popup */
    .popup .show {
        visibility: visible;
        -webkit-animation: fadeIn 1s;
        animation: fadeIn 1s;
    }

    /* Add animation (fade in the popup) */
    @-webkit-keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .profile_details {
        display: -moz-box;
        display: -webkit-box;
        display: box;
        -moz-box-orient: horizontal;
        -webkit-box-orient: horizontal;
        box-orient: horizontal;
        width: 100%;
        min-height: 200px;
    }

    .profile_details div {

        -moz-box-flex: 1;
        -webkit-box-flex: 1;
        box-flex: 1;

    }

    /* Dashborad grid and list view style */
    [data-tooltip1]::before {
        position: absolute;
        content: attr(data-tooltip1);
        font-size: 10px;
        opacity: 0;
        margin-top: -20px;
        width: 50px;
        background-color: #5A5A5A;
        color: #fff;
        text-align: center;
        border-radius: 3px;
        padding: 2px 0;
    }

    [data-tooltip1]:hover::before {
        opacity: 1;
    }

    [data-tooltip1]:not([data-tooltip1-persistent])::before {
        pointer-events: none;
    }
</style>
<div class="page-title">
    <div class="title_left">
        <h3><?php echo $header; ?></h3>
    </div>
    <div class="title_right">
        <?php if ($type == 1) { ?>
            <div class="col-md-1 col-sm-1 form-group pull-right top_search">
                <button class="btn btn-default" data-tooltip1="Listview"><a href="<?= base_url('Demo/demo_dashboard'); ?>"><span class="fa fa-th-list"></span></a></button>
            </div>
        <?php } elseif ($type == 2) { ?>
            <div class="col-md-1 col-sm-1 form-group pull-right top_search">
                <button class="btn btn-default" data-tooltip1="Listview"><a href="<?= base_url('SCORM/scorm_dashboard'); ?>"><span class="fa fa-th-list"></span></a></button>
            </div>
        <?php } elseif ($type == 3) { ?>
            <div class="col-md-1 col-sm-1 form-group pull-right top_search">
                <button class="btn btn-default" data-tooltip1="Listview"><a href="<?= base_url('my_training/dashboardtable'); ?>"><span class="fa fa-th-list"></span></a></button>
            </div>
        <?php } ?>
        <div class="col-md-5 col-sm-5 form-group pull-right top_search">
            <form>
                <div class="input-group">
                    <input type="text" class="form-control" name="search" value="<?= isset($search) ? $search : '' ?>" placeholder="Search for...">
                    <span class="input-group-btn">
                        <button class="btn btn-secondary" type="submit">Go!</button>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="x_content">
            <div class="row">
                <?php
                if ($clientCourseddata != '') {
                    if (count($clientCourseddata) > 0) {
                        $j = 0;
                        foreach ($clientCourseddata as $clienteachCourseddata) {
                            if ($j >= 7) {
                                break; // Exit the loop after 4 courses
                            }
                            if ($clienteachCourseddata['type'] == 1) {
                                $demoButton = 'Video';
                            }
                            if ($clienteachCourseddata['type'] == 2) {
                                $demoButton = 'Preview';
                            }
                            if ($clienteachCourseddata['type'] == 5) {
                                $demoButton = 'Preview';

                                // $id_user = session()->get('id_user');
                                // $useridlength = strlen($id_user);
                                // $getUserTimestamp = $usertimestamp;
                                // $userLength = strlen($useridlength . '' . $id_user);
                                // $length =  6 - $userLength;
                                // $timestampLastDigit = substr($getUserTimestamp, -$length);
                                // $generatedNumber = $useridlength . $id_user . $timestampLastDigit;
                            }
                            if (isset($clienteachCourseddata['thumbnail']) && $clienteachCourseddata['thumbnail'] != '') {
                                $thumbnail =  base_url('assets/assets/uploads/SCORM_course_thumbnail/' . $clienteachCourseddata['scourse_id'] . '/' . $clienteachCourseddata['thumbnail']);
                                $imgthumbnail = 'style="background-image: url(' . $thumbnail . ');height:150px; background-size: 100% 100%;background-repeat: no-repeat;";';
                            } else {
                                $imgthumbnail = 'style="height:150px; background-size: 100% 100%;background-repeat: no-repeat;";';
                            } ?>

                            <div class="col-lg-3 col-md-3 col-sm-3  profile_details">
                                <div class="col-md-12 col-sm-12 well profile_view">
                                    <div class="col-md-12 col-sm-12 " <?= $imgthumbnail ?>>
                                    </div>&nbsp;

                                    <div class="row btn-margin-custom">
                                        <div class="col-md-12 col-sm-12 btn-margin-custom">
                                            <?php if (isset($clienteachCourseddata['upload']) && $clienteachCourseddata['upload'] != '') {
                                            ?>
                                                <?php if ($clienteachCourseddata['type'] == 5) { ?>
                                                    <div class=" btn-margin-custom">
                                                        <a href="#" onclick="OpenNewWindow('<?php echo base_url('SCORM/scorm_launch/tinCanlanch?course_id=' . $clienteachCourseddata['scourse_id'] . '&foldername=' . $clienteachCourseddata['upload'] . '&type=' . $type); ?>')"><button class="btn-sm btn btn-success">Launch</button></a>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class=" btn-margin-custom">
                                                        <a href="#" onclick="OpenNewWindow('<?php echo base_url('SCORM/scorm_launch?course_id=' . $clienteachCourseddata['scourse_id'] . '&foldername=' . $clienteachCourseddata['upload'] . '&type=' . $type); ?>')"><button class="btn-sm btn btn-success">Launch</button></a>
                                                    </div>
                                                <?php }
                                            } else if (strlen($clienteachCourseddata['launch_link']) > 5) { ?>
                                                <div class=" btn-margin-custom">
                                                    <a onclick="OpenNewWindowmiddlepop('<?php echo $clienteachCourseddata['scourse_id'] ?>','4')"><button class="btn-sm btn btn-success">Launch</button></a>
                                                </div>
                                            <?php } elseif ($clienteachCourseddata['type'] == 8) {  ?>
                                                <a href="#" onclick="OpenNewWindow('<?php echo base_url('Assessment/launch?course_id=' . $clienteachCourseddata['scourse_id'] . '&foldername=' . $clienteachCourseddata['upload'] . '&type=' . $type); ?>')"><button class="btn-sm btn btn-success">Launch</button></a>
                                            <?php }
                                            if (strlen($clienteachCourseddata['promo_video']) > 3) {
                                                $promo =  base_url('assets/assets/uploads/SCORM_course_promovideo/' . $clienteachCourseddata['scourse_id'] . '/' . $clienteachCourseddata['promo_video']);
                                            ?>
                                                <div class=" btn-margin-custom">
                                                    <a href="#" onclick="OpenNewWindowmiddlepop('<?php echo $clienteachCourseddata['scourse_id'] ?>','1')"><button class="btn-sm btn btn-primary"><?= $demoButton ?></button></a>
                                                </div>
                                            <?php } ?>
                                            <?php if ($type > 0) {
                                                if (in_array('1', $arraystakeholders)) { // only TQ users access for Cart
                                            ?>
                                                    <div class=" btn-margin-custom">
                                                        <a href="<?php echo base_url('Demo/cart/addToCart/' . $clienteachCourseddata['scourse_id']) ?>"><button class="btn-sm btn btn-dark"><i class="fa fa-shopping-cart"></i></button></a>
                                                    </div>
                                            <?php }
                                            } ?>
                                            <?php if (isset($clienteachCourseddata['description'])) {
                                                if (strlen($clienteachCourseddata['description']) > 10) {
                                                    $descriptionpath = base_url('SCORM/scorm_dashboard/launchDescriptionPopup?course_id=' . $clienteachCourseddata['scourse_id']);
                                            ?>
                                                    <div class=" btn-margin-custom">
                                                        <a href="#" onclick="OpenNewWindowmiddlepop('<?php echo $clienteachCourseddata['scourse_id'] ?>','2')"><button class="btn-sm btn btn-info">Details</button></a>

                                                    </div>
                                            <?php }
                                            } ?>
                                            <?php if ($clienteachCourseddata['pdf_filename'] != '') { ?>
                                                <div class=" btn-margin-custom">
                                                    <a href="#" onclick="OpenNewWindowPdfpop('<?php echo $clienteachCourseddata['scourse_id'] ?>','3')"><button class="btn-sm btn btn-danger"><i class="fa fa-file-pdf-o"></i></button></a>

                                                </div>
                                            <?php  } ?>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="category-div right col-md-12 col-sm-12">
                                        <div class="x_title">
                                            <p class="brief" title="<?php echo $clienteachCourseddata['course_name'] ?>" style="font-size: 12px;font-weight:bold"><i><?php echo $clienteachCourseddata['course_name']; ?></i></p>
                                        </div>
                                        <p style="font-size: 11px;">
                                            <?php if (($clienteachCourseddata['type'] == 5) && strlen($app_username) > 1) { ?>
                                                <span style="color:blue; font-size:14px;"><b> App Username: </b><?php echo $app_username; ?></span>
                                                <!-- <b><a href="<?php echo base_url('API/APIaccess/' . $app_username . '/' . $clienteachCourseddata['scourse_id']) ?>" target="_blank"><u>XAPI link</u></a><b> -->
                                            <?php } ?></br>
                                            <?php if ($clienteachCourseddata['duration'] > 0) { ?>
                                                <b> Duration: </b>
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
                                                </br>
                                            <?php } ?>
                                            <?php
                                            if (strlen($clienteachCourseddata['language']) > 2) { ?>
                                                <b> Language: </b><?php echo $clienteachCourseddata['language'] ?></br>
                                            <?php } ?>
                                            <?php if (strlen($clienteachCourseddata['category']) > 2) { ?>
                                                <b> Categories: </b><?php echo $clienteachCourseddata['category'] ?><?php } ?><br />
                                                <?php
                                                if (isset($clienteachCourseddata['lesson_status']) && strlen($clienteachCourseddata['lesson_status']) > 2) { ?>
                                                    <b> Status: </b><?php echo  ucfirst($clienteachCourseddata['lesson_status']) ?>
                                                <?php } ?>


                                        </p>
                                    </div>
                                </div>
                            </div>
                <?php $j = $j + 1;
                        }
                    }
                }
                ?>
                <?php if (isset($clienteachCourseddata['thumbnail']) && $clienteachCourseddata['thumbnail'] != '') {
                    $thumbnail = base_url('assets/assets/img/view_all_my_courses.png');
                    $imgthumbnail = 'style="background-image: url(' . $thumbnail . ');height:150px; background-size: 100% 100%;background-repeat: no-repeat;";';
                } else {
                    $imgthumbnail = 'style="height:150px; background-size: 100% 100%;background-repeat: no-repeat;";';
                } ?>
                <?php if (count($clientCourseddata) > 7) { ?>
                    <div class="col-lg-3 col-md-3 col-sm-3  profile_details">
                        <div class="col-md-12 col-sm-12 well profile_view">
                            <a href="<?php echo base_url('my_training/dashboardtable'); ?>">
                                <div class="col-md-12 col-sm-12 " <?= $imgthumbnail ?>>

                                </div>&nbsp;
                            </a>
                            <div class="row btn-margin-custom">
                                <div class="col-md-12 col-sm-12 btn-margin-custom">

                                    <div class=" btn-margin-custom">
                                        <a href="<?php echo base_url('my_training/dashboardtable'); ?>"><button class="btn-sm btn btn-danger">View More</button></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
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
</script>