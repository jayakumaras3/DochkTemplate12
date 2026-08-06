<?php if (session()->get('error')) :
    echo '<script>alert("' . session()->get('error') . '")</script>';
endif;
$client =  session()->get('client');
$arraystakeholders  = explode(',', $client); ?>
<style>
    th,
    td {
        padding-left: 8px;
        /* padding-top: 8px; */
        /* padding-bottom: 8px; */
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
        width: 360px;
        background-color: #555;
        color: #fff;
        text-align: left;
        border-radius: 6px;
        padding: 10px;
        position: absolute;
        z-index: 1;
        top: 125%;
        left: 100%;
        margin-left: -350px;
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

    .brief {
        display: block;
        white-space: nowrap;
        width: 16em;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url('Project/dashboard') ?>">Project Dashboard</a>
            </li><b>&nbsp;>&nbsp;</b>
            <li class="active">
                <?php if (in_array('1', $arraystakeholders)) { ?>
                    <a href="<?php echo base_url('project_details?projectid=' . $projectid) ?>"><?php echo $getmyassignment['0']['projectname'] ?></a>
                <?php } else { ?>
                    <?php echo $getmyassignment['0']['projectname'] ?>
                <?php } ?>
            </li><b>&nbsp;>&nbsp;</b>
     
        </ol>

    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="x_content">
            <div class="row">
                <?php
                if ($getmyassignmentcourse != '') {
                    if (count($getmyassignmentcourse) > 0) {
                        //print_r($getmyassignment);
                        $j = 0;
                        foreach ($getmyassignmentcourse as $myeachassignment) { ?>
                            <div class="col-md-6 col-sm-6  profile_details">
                                <div class="well profile_view" style="padding-bottom: 5px;">
                                    <div class="col-sm-12">
                                        <div class="col-sm-11">
                                            <?php if (in_array('1', $arraystakeholders)) { ?>
                                                <p class="brief" title="<?= $myeachassignment['course_name'] ?>" style="font-size:110%;font-weight:bold"><i><a href="<?php echo base_url('pages?projectid=' . $myeachassignment['projectid'] . '&course_id=' . $myeachassignment['course_id']) ?>"><?= $myeachassignment['course_name'] ?></a></i></p>
                                            <?php } else { ?>
                                                <p class="brief" title="<?= $myeachassignment['course_name'] ?>" style="font-size:110%;font-weight:bold"><i><?= $myeachassignment['course_name'] ?></i></p>
                                            <?php } ?>
                                        </div>

                                        <div class="col-sm-1">
                                            <i class="fa fa-info"></i>
                                        </div>
                                        <div class="left col-md-12 col-sm-12">
                                            <!-- <h2>Nicole Pearson</h2>
                                        <p><strong>About: </strong> Web Designer / UX / Graphic Artist / Coffee Lover </p> -->
                                            <ul class="list-unstyled">
                                                <li><strong>Start Date :</strong> <?php if ($myeachassignment['pstart_dt'] == '0000-00-00' || isset($myeachassignment['pstart_dt']) == "") {
                                                                                    } else {
                                                                                        echo date('m-d-y', strtotime($myeachassignment['pstart_dt']));
                                                                                    }  ?></li>
                                                <li><strong>End Date :</strong> <?php if ($myeachassignment['pend_dt'] == '0000-00-00' || isset($myeachassignment['pend_dt']) == "") {
                                                                                } else {
                                                                                    echo date('m-d-y', strtotime($myeachassignment['pend_dt']));
                                                                                }  ?></li>
                                                <li>Status : <?php echo $myeachassignment['stage'] ?></li>
                                                <li class="project_progress">
                                                    <?php
                                                    if (isset($myeachassignment['comp'])) {
                                                        $comp = $myeachassignment['comp'];
                                                    } else {
                                                        $comp = 0;
                                                    }
                                                    ?><?php echo $comp ?>% Completed
                                                    <?php if (in_array('1', $arraystakeholders)) { ?>
                                                        <a href="<?php echo base_url('project_details/course_edit_view?projectid=' . $myeachassignment['projectid'] . '&course_id=' . $myeachassignment['course_id']); ?>">
                                                            <div class="progress progress_sm" style="width:50%;">
                                                                <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="<?php echo $comp ?>"></div>
                                                            </div>
                                                        </a>
                                                    <?php } else { ?>
                                                        <div class="progress progress_sm" style="width:50%;">
                                                            <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="<?php echo $comp ?>"></div>
                                                        </div>
                                                    <?php } ?>

                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class=" col-sm-2 emphasis" title="Events">
                                            <?php if (in_array('1', $arraystakeholders)) { ?>
                                                <a href="<?php echo base_url('Project/event?projectid=' . $myeachassignment['projectid'] . '&course_id=' . $myeachassignment['course_id']); ?>" class="btn btn-sm widget-icon btn-warning"><span class="fa fa-gears"></span></a>
                                            <?php } else { ?>

                                            <?php } ?>
                                        </div>
                                        <div class=" col-sm-2 emphasis" title="Task">
                                            <form class="form-horizontal" action="<?php echo base_url('Project/project_management') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="projectid" value="<?php echo $myeachassignment['projectid'] ?>">
                                                <input type="hidden" name="course_id" value="<?php echo $myeachassignment['course_id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-danger"><span class="icon-external-link"></span></button>
                                            </form>
                                            <!-- <a href="<?php echo base_url('Project/project_management?projectid=' . $myeachassignment['projectid'] . '&course_id=' . $myeachassignment['course_id']) ?>" class="btn btn-sm widget-icon  btn-danger"><span class="icon-external-link"></span></a> -->
                                        </div>
                                        <div class=" col-sm-2 emphasis">
                                            <a class="btn btn-sm widget-icon  btn-dark">
                                                <div class="popup" title="Note" onclick="myFunction('myPopup_<?= $j ?>')"><span class="fa fa-comment"></span>
                                                    <span class="popuptext" id="myPopup_<?= $j ?>"><?php echo $myeachassignment['notes'] ?></span>
                                                </div>
                                            </a>
                                        </div>
                                        <div class=" col-sm-2 emphasis" title="Project Plan">
                                            <form class="form-horizontal" action="<?php echo base_url('Project/project_plan') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="projectid" value="<?php echo $myeachassignment['projectid'] ?>">
                                                <input type="hidden" name="course_id" value="<?php echo $myeachassignment['course_id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-info"><span class="icon-calendar"></span></button>
                                            </form>
                                            <!-- <a href="<?php echo base_url() . '/Project/project_plan?projectid=' . $myeachassignment['projectid'] . '&course_id=' . $myeachassignment['course_id'] ?>" class="btn btn-sm widget-icon  btn-info"><span class="icon-calendar"></span></a> -->
                                        </div>
                                        <?php if ($myeachassignment['pageid'] == '') {
                                        } else {
                                        ?>
                                            <div class=" col-sm-2 emphasis" title="Feedback">

                                                <?php $btntype = '';
                                                //  print_r($myeachassignment['projectTheme']);
                                                if ($myeachassignment['coursetype'] == 21 || $myeachassignment['coursetype'] == 62) {
                                                    $btntype = 'btn-success';
                                                } else if ($myeachassignment['coursetype'] == 22) {
                                                    $btntype = 'btn-primary';
                                                }
                                                // if ($myeachassignment['reviewid'] == '') {
                                                //     $disable =  'disabled';
                                                // } else {
                                                //     $disable = '';
                                                // }
                                                if ($myeachassignment['projectTheme'] == 'GE') {
                                                    if ($myeachassignment['reviewstatus'] == 2) {
                                                        echo '<a href="javascript: void(0)" class="btn btn-sm widget-icon ' . $btntype . '" onclick="popup(0,' . $myeachassignment['course_id'] . ',1,' . $myeachassignment['coursetype'] . ')">';
                                                    } else {
                                                        echo '<a href="javascript: void(0)" class="btn btn-sm widget-icon ' . $btntype . '"  onclick="popup(' . $myeachassignment['reviewid'] . ',' . $myeachassignment['course_id'] . ',1,' . $myeachassignment['coursetype'] . ')">';
                                                    }
                                                } elseif ($myeachassignment['projectTheme'] == 'VR') {
                                                    if ($myeachassignment['reviewstatus'] == 2) {
                                                        echo '<a href="javascript: void(0)" class="btn btn-sm widget-icon ' . $btntype . '" onclick="popup(0,' . $myeachassignment['course_id'] . ',' . $myeachassignment['pageid'] . ',' . $myeachassignment['coursetype'] . ')">';
                                                    } else {
                                                        echo '<a href="javascript: void(0)" class="btn btn-sm widget-icon ' . $btntype . ' "  onclick="popupfeedback(' . $myeachassignment['course_id'] . ',' . $myeachassignment['pageid'] . ',' . $myeachassignment['coursetype'] . ')">';
                                                    }
                                                } else {
                                                    if ($myeachassignment['coursetype'] == 21 || $myeachassignment['coursetype'] == 22 || $myeachassignment['coursetype'] == 24 || $myeachassignment['coursetype'] == 62) {
                                                        if ($myeachassignment['reviewstatus'] == 2) {
                                                            echo '<a href="javascript: void(0)" class="btn btn-sm widget-icon ' . $btntype . '" onclick="popup(0,' . $myeachassignment['course_id'] . ',1,' . $myeachassignment['coursetype'] . ')">';
                                                        } else {
                                                            echo '<a href="javascript: void(0)" class="btn btn-sm widget-icon ' . $btntype . '"  onclick="popup(' . $myeachassignment['reviewid'] . ',' . $myeachassignment['course_id'] . ',1,' . $myeachassignment['coursetype'] . ')">';
                                                        }
                                                    } else {
                                                        echo '<a href="javascript: void(0)" class="btn btn-sm widget-icon  btn-info"  onclick="popup(' . $myeachassignment['reviewid'] . ',' . $myeachassignment['course_id'] . ',1,3)">';
                                                    }
                                                }
                                                echo '<span class="icon-play"></span>';
                                                echo '</a>'; ?>
                                            </div>
                                        <?php }
                                        ?>
                                    </div>
                                </div>
                            </div>
                <?php $j = $j + 1;
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="col-md-12 col-sm-12  profile_details">
            <div class="well profile_view" style="padding-bottom: 5px;">
                <div class="col-md-10">
                    <h6><strong>Important Document</strong></h6>
                </div>
                <div class="col-md-2">
                    <a title="View documents" href="<?php echo base_url('Project/dashboard_v1/docupload_view?projectid=' . $myeachassignment['projectid']); ?>"><i class="fa fa-file"></i></a>
                </div>

                <?php if (session()->get('success')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= session()->get('success') ?>
                    </div>
                <?php endif;
                if (session()->get('error')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= session()->get('error') ?>
                    </div>
                <?php endif; ?>
                <!-- <div class="x_panel"> -->

                <div class="col-md-12">

                    <div class="form-row">
                        <form class="form-horizontal" enctype="multipart/form-data" action="<?php echo base_url('Project/dashboard_v1/fileupload') ?>" method="POST"><?= csrf_field() ?>
                            <div class="form-group col-md-12">
                                Note:<br />
                                <small>1. Filename name should not contain space.</small><br />
                                <small>2. File size should be less than or equal to 1MB.</small><br />
                                <small>3. File extension supports for <b>pdf,docx,xls,ppt,pptc</b></small><br /><br />
                                <input type="text" placeholder="Description" name="description" class="form-control" />
                            </div>
                            <div class="form-group col-md-12">
                                <input type="file" name="file" />
                            </div>
                            <div class="form-group col-md-12">
                                <input type="hidden" name="projectid" value="<?php echo $projectid; ?>">
                                <button type="submit" class="btn btn-danger btn-sm form-control">Upload</button>
                            </div>
                            <!-- <div><i class="fa fa-spinner fa-spin">Loading..Please wait</i></div>-->
                            <div></div>
                            <?php if (isset($validation)) : ?>
                                <div class="form-group col-md-12">
                                    <div class="alert alert-danger" role="alert">
                                        <?= $validation->listErrors() ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    // When the user clicks on div, open the popup
    function myFunction(myPopup) {
        var popup = document.getElementById(myPopup);
        popup.classList.toggle("show");
    }
</script>