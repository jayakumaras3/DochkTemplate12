<!DOCTYPE html>
<html lang="en" data-layout="horizontal" data-topbar-color="light">

<head>
    <meta charset="utf-8" />
    <title>DoChek</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="DoChek an essential tool for Learning Management" name="description" />
    <meta content="DoChek" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>public/Landing/images/favicon.ico">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        /* Set height of the grid so .sidenav can be 100% (adjust if needed) */
        .row.content {
            min-height: 600px;
        }

        /* Set gray background color and 100% height */
        .sidenav {
            background-color: #fff;

        }

        /* On small screens, set height to 'auto' for sidenav and grid */
        @media screen and (max-width: 767px) {
            .sidenav {
                height: auto;
                padding: 15px;
            }

            .row.content {
                height: auto;
            }
        }

        .noDecoration {
            background-color: #efeaea;
            color: black;
            padding: 1em 1.5em;
            text-decoration: none;
            width: 270px;
            text-align: left;
            padding: 5px;
            margin: 5px;
            text-wrap: wrap;
        }

        #menu_details {
            position: absolute;
            display: none;
            width: 300px;
            text-align: left;
            padding: 5px;
            top: 0px;
            left: 0px;
            right: 0;
            bottom: 10px;
            background-color: white;
            border: 1px;
            z-index: 1;
        }

        .main-content {
            min-height: 600px;
        }

        .menu {
            height: 500px;
            overflow: auto;
            padding: 5px;
            background-color: white;
            text-decoration: none;
        }

        .question_bg {
            background-color: #acd8db;
            color: black;
            padding: 20px;
        }

        a:hover {
            text-decoration: none;
        }

        .question_base {
            background-color: #253790;
            color: white;
            font-size: 18px;
            margin: 20px;
            padding: 5px;
        }

        .option_container {
            margin: 10px;
            padding: 10px;
        }

        .options {
            background-color: #2b709b;
            color: white;
            padding: 5px;
            margin: 5px;
            width: 90%;
            cursor: pointer;
            font-size: 14px;
        }

        .feedback_correct {
            background-color: #62bd5e;
            color: white;
            padding: 5px;
            margin: 5px;
            width: 100%;
            font-size: 14px;
        }

        .feedback_wrong {
            background-color: #f56969;
            color: white;
            padding: 5px;
            margin: 5px;
            width: 100%;
            font-size: 14px;
        }

        .iframe-container {
            position: relative;
            overflow: hidden;
            width: 100%;
            padding-top: 56.25%;
            /* 16:9 Aspect Ratio (divide 9 by 16 = 0.5625) */
        }

        .responsive-iframe {
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            border: 0;
            right: 0;
            width: 100%;
            height: 100%;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row content">
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-1">
                        <button type="submit" class="btn btn-primary" id="menu-btn" >&#9776;</button>
                    </div>
                    <div class="col-sm-11">
                        <h4>
                            <?php echo $row['page_name']; ?>
                        </h4>
                    </div>
                </div>
                <div class="row main-content">
                    <div class="col-sm-12 text-left">
                        <div id="menu_details" style="display:none; ">
                            <!-- Sidebar Logo -->
                            <img src="<?php echo base_url('assets/assets/uploads/Aristo_Theme/images/logo_.png'); ?>" alt="logo" height="60px">
                            <!-- Tabs for Menu and Transcript (Horizontal Layout) -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#menu">Menu</a></li>
                                <li><a data-toggle="tab" href="#transcript">Transcript</a></li>
                            </ul>
                            <div class="tab-content">
                                <div id="menu" class="tab-pane fade in active menu">
                                    <?php foreach ($pagedetails as $page) {
                                        if ($page['sub_page_main'] == 0) { ?>

                                            <a href="<?php echo base_url('SCORM/Course_builder/review_course/launcher/' . $course_id . '/' . $page['page_id']); ?>" style="">
                                                <p class="noDecoration"><?php echo $page['page_name']; ?></p>
                                            </a>

                                    <?php }
                                    } ?>

                                </div>
                                <div id="transcript" class="tab-pane fade menu">
                                    <p><?php if (isset($transcript)) {
                                            foreach ($transcript as $script) { ?>
                                    <div class="transcript-item" style="font-size:12px;">
                                        <?php echo $script['audio']; ?>
                                    </div>
                            <?php }
                                        } ?></p>
                                </div>
                            </div>

                        </div>

                        <?php
                        if ($row['type'] == 3) {
                            // Path for the iframe content
                            $html_path = base_url() . "assets/assets/uploads/SCORM_course_document/" . $course_id . "/" . $coursedetails[0]['createdon'] . "/assets/html/" . $row['page_id'] . "/index.html"; ?>
                            <div class="iframe-container">
                                <iframe class="responsive-iframe" src="<?php echo $html_path; ?>">
                                    Your browser does not support iframes.
                                </iframe>
                            </div>
                        <?php } elseif ($row['type'] == 1) {
                            // Path for Articulate content
                            $articulate_path = base_url() . "assets/assets/uploads/SCORM_course_document/" . $course_id . "/" . $coursedetails[0]['createdon'] . "/assets/Articulate/" . $row['page_id'] . "/story.html"; ?>
                            <div class="iframe-container">
                                <iframe class="responsive-iframe" src="<?php echo $articulate_path; ?>">
                                    Your browser does not support iframes.
                                </iframe>
                            </div>
                        <?php } elseif ($row['type'] == 2 || $row['type'] == 9) {
                            // Path for the video
                            $video_path = base_url() . "assets/assets/uploads/SCORM_course_document/" . $course_id . "/" . $coursedetails[0]['createdon'] . "/assets/video/" . $row['video_upload'];
                            $vtt_path = base_url() . "assets/assets/uploads/SCORM_course_document/" . $course_id . "/" . $coursedetails[0]['createdon'] . "/assets/vtt/" . $row['vtt_upload'];
                        ?>
                            <video src="<?php echo $video_path; ?>" style="width: 100%; height: auto;" id="vidArea" controls onended="onendofvideo()" onvolumechange="volumechange_fun()" onpause="onPauseVid()"
                                controlsList="nodownload"
                                disablePictureInPicture
                                onchange="videochange">
                                <track id="englishTrack" kind="captions" src="<?php echo $vtt_path; ?>" srclang="en" label="English" default>
                            </video>
                        <?php } elseif ($row['type'] == 5 || $row['type'] == 6) { ?>

                            <div class="question_bg" style="min-height: 600px" ;>

                                <div class="question_base">
                                    <?php echo $question['question']; ?>
                                </div>
                                <div class="option_container">
                                    <i>Select the correct answer, and then click <strong>Submit.</strong></i>
                                    <?php if (isset($question_options)) {
                                        $count = 1;
                                        foreach ($question_options as $options) { ?>
                                            <div class="form-check ">
                                                <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios<?php echo $count; ?>" value="option<?php echo $count; ?>">
                                                <label class="form-check-label options " for="exampleRadios<?php echo $count; ?>">
                                                    <?php echo $options['values']; ?>
                                                </label>
                                            </div>
                                            <?php $count++; ?>
                                            <?php // echo $options['score']; 
                                            ?>
                                            <?php // echo $options['truefalse']; 
                                            ?>

                                    <?php }
                                    } ?>
                                    <br>
                                    <button class="btn btn-outline-primary waves-effect btn-xs waves-light">Submit</button></br></br>
                                    <div class="feedback_correct" id="correct_feedback"><?php echo $question['correct']; ?></div></br>
                                    <div class="feedback_wrong" id="incorrect_feedback"><?php echo $question['incorrect']; ?></div>
                                </div>
                                <?php // echo $question['noAttempts']; 
                                ?>
                            </div>
                        <?php } ?>


                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4 text-center">
                        <h5> <?php if ($prev_page) { ?>
                                <a style="float: left;" href="<?php echo base_url('SCORM/Course_builder/review_course/launcher/' . $course_id . '/' . $prev_page[0]['pre_page']) ?>">
                                    <button type="submit" class="btn btn-sm  btn-success">&#8249; Previous</button>
                                </a>
                            <?php } ?>
                        </h5>
                    </div>
                    <div class="col-sm-4 text-center">
                        <h5> Page <?php echo $row['page_number'] ?> of <?php echo count($pagedetails) ?></h5>
                    </div>
                    <div class="col-sm-4 text-center">
                        <h5> <?php if ($next_page) { ?>
                                <a style="float: right;" href="<?php echo base_url('SCORM/Course_builder/review_course/launcher/' . $course_id . '/' . $next_page[0]['pre_page']) ?>">
                                    <button type="submit" class="btn btn-sm btn-success">Next &#8250;</button>
                                </a>
                            <?php } ?>
                        </h5>
                    </div>
                </div>
                </br>
            </div>
            <div class="col-sm-3 sidenav">
                <h4>Feedback</h4>
                <div class="form-group">
                    <textarea class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <button class="btn btn-warning" type="button">
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script>
    $(document).ready(function() {
        $("#menu-btn").click(function() {
            $("#menu_details").toggle();
        });
    });
    // Function to toggle between "Menu" and "Transcript" tabs
    function toggleTab(evt, tabName) {
        // Declare all variables
        var i, tabcontent, tablinks;

        // Get all elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        // Get all elements with class="tablink" and remove the "active" class
        tablinks = document.getElementsByClassName("tablink");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" w3-blue", "");
        }

        // Show the current tab and add an "active" class to the button that opened it
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " w3-grey";
    }

    // Set default tab to open (Menu tab)
    document.getElementById("defaultOpen").click();
</script>