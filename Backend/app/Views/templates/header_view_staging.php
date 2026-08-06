<?php
$theme = session('theme_color');
if ($theme == 0) {
    $theme = 'light';
} else {
    $theme = 'dark';
}

$userlevel = session('userlevel');
$id_user = session('id_user');
$arrayuserlevel = array_map('intval', explode(',', $userlevel) ?? '');
$taskcount = session('totaltaskcount');
$accessmenu = session('accessmenu');
$homepage = session('home_page');
$arrayaccessmenu = array_map('intval', explode(',', $accessmenu) ?? '');
$profile_image = session('profile_image');
$profile_foldername = session('profile_foldername');
$logo = session('logo');
$logo_dark = session('logo_dark');
$client = session('client');
$marketplaceaccess = session()->get('marketplaceaccess');

?>
<!DOCTYPE html>


<html lang="en" data-menu-color="<?php echo $theme; ?>" data-bs-theme="<?php echo $theme; ?>" data-layout-mode="default"
    data-layout-width="default" data-topbar-color="<?php echo $theme; ?>" data-menu-icon="default"
    data-sidenav-size="full" class="menuitem-active" data-sidenav-user="true">

<head>
    <meta charset="utf-8" />
    <title>DoChek</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="DoChek - an essential tool for Learning Management" name="description" />
    <meta content="DoChek" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>public/Landing/images/favicon.ico">
    <!-- Plugins css -->
    <script type='text/javascript' src="<?php echo base_url(); ?>/public/assets/ckeditor/ckeditor.js"></script>

    <link href="<?php echo base_url(); ?>public/creative/assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet"
        type="text/css" />
    <link href="<?php echo base_url(); ?>public/creative/assets/libs/selectize/css/selectize.bootstrap3.css"
        rel="stylesheet" type="text/css" />
    <!-- Theme Config Js -->
    <script src="<?php echo base_url(); ?>public/creative/assets/js/head.js"></script>


    <!-- Bootstrap css -->
    <link href="<?php echo base_url(); ?>public/creative/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"
        id="app-style" />
    <!-- App css -->
    <link href="<?php echo base_url(); ?>public/creative/assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons css -->
    <link href="<?php echo base_url(); ?>public/creative/assets/css/icons.min.css" rel="stylesheet" type="text/css" />

    <link href="<?php echo base_url(); ?>public/creative/assets/libs/mohithg-switchery/switchery.min.css"
        rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>public/creative/assets/libs/multiselect/css/multi-select.css" rel="stylesheet"
        type="text/css" />
    <link href="<?php echo base_url(); ?>public/creative/assets/libs/select2/css/select2.min.css" rel="stylesheet"
        type="text/css" />
    <link href="<?php echo base_url(); ?>public/creative/assets/libs/selectize/css/selectize.bootstrap3.css"
        rel="stylesheet" type="text/css" />
    <link
        href="<?php echo base_url(); ?>public/creative/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css"
        rel="stylesheet" type="text/css" />
    <!-- third party css -->
    <link
        href="<?php echo base_url(); ?>public/creative/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link
        href="<?php echo base_url(); ?>public/creative/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link
        href="<?php echo base_url(); ?>public/creative/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link
        href="<?php echo base_url(); ?>public/creative/assets/libs/datatables.net-select-bs5/css/select.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />

    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
    <script type='text/javascript' src='<?php echo base_url(); ?>public/js/plugins/jquery/jquery.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>public/js/plugins/jquery/jquery-ui.min.js'></script>
    <script type='text/javascript'
        src='<?php echo base_url(); ?>public/js/plugins/jquery/jquery-migrate.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>public/js/plugins/jquery/globalize.js'></script>
    <script type='text/javascript'
        src='<?php echo base_url(); ?>public/js/plugins/uniform/jquery.uniform.min.js'></script>
    <link href="<?php echo base_url(); ?>public/creative/assets/libs/dropzone/min/dropzone.min.css" rel="stylesheet"
        type="text/css" />
    <link href="<?php echo base_url(); ?>public/creative/assets/libs/dropify/css/dropify.min.css" rel="stylesheet"
        type="text/css" />

    <link href="<?php echo base_url(); ?>public/creative/assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet"
        type="text/css" />

    <link href="<?php echo base_url(); ?>public/creative/assets/libs/clockpicker/bootstrap-clockpicker.min.css"
        rel="stylesheet" type="text/css" />
    <link
        href="<?php echo base_url(); ?>public/creative/assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css"
        rel="stylesheet" type="text/css" />
    <link
        href="<?php echo base_url(); ?>public/creative/assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css"
        rel="stylesheet" type="text/css" />


    <!-- plugin css -->
    <link
        href="<?php echo base_url(); ?>public/creative/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css"
        rel="stylesheet" type="text/css" />

    <style>
        .floaterwindow_green {
            position: fixed;
            top: 10px;
            width: 50%;
            left: 25%;
            justify-content: center;
            align-items: center;
            padding: 10px;
            margin-bottom: 20px;
            border: #12846d;
            background-color: rgba(26, 188, 156, 0.18);
            color: #12846d;
            border-radius: 5px;
            font-weight: bold;
            z-index: 9999;
        }

        .floaterwindow_red {
            position: fixed;
            top: 10px;
            width: 50%;
            left: 25%;
            justify-content: center;
            align-items: center;
            padding: 10px;
            margin-bottom: 20px;
            border: #a93c4c;
            background-color: rgba(241, 85, 108, 0.18);
            color: #a93c4c;
            border-radius: 5px;
            font-weight: bold;
            z-index: 9999;
        }

        .alertwindow {
            position: fixed;
            top: 30%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
        }

        #progressBar {
            width: 0%;
            height: 5px;
            background-color: #4c98af11;
            /* Green color for the bar */
            margin-top: 0px;
            /* CSS transition for smooth animation */
            transition: width 5s linear;
        }


        #loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #ffffff;
            /* White background */
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            z-index: 100;
            /* Ensure it is above all other content */
            transition: opacity 0.5s ease-out;
            /* Smooth fade-out */
        }

        /* Optional: Simple CSS Loader/Spinner */
        .loader {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Class to apply when the page is loaded */
        .loaded #loading-screen {
            opacity: 0;
            /* Fade out */
            visibility: hidden;
            /* Hide completely from user interaction */
        }

        /* Initial state for the content container */
        #content-container {
            display: none;
            /* Hide main content until loaded */
            padding: 20px;
        }

        /* Class to apply when content is ready to be shown */
        .loaded #content-container {
            display: block;
            /* Show main content */
        }
    </style>
    <style>
        .persistent-warning {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 16px;
            background-color: #fff3cd;
            border: 1px solid #ffe69c;
            color: #664d03;
            border-radius: 6px;
            margin-bottom: 16px;
        }

        .warning-icon {
            font-size: 18px;
            line-height: 1.2;
        }

        .warning-text {
            font-size: 14px;
        }
    </style>
    <style>
        #alternative-page-datatable td {
            white-space: normal !important;
            word-wrap: break-word;
            word-break: break-word;
        }

        .wrap-text {
            max-width: 500px;
            max-height: 120px;
            overflow-y: auto;
            word-break: break-word;
        }

        .menu-font-overite .menu-link .menu-text {
            font-size: 12px !important;

        }
    </style>
    <script>
        var element = document.getElementById('my-app');
        if (typeof(element) != 'undefined' && element != null) {
            var milliseconds = 5000;
            setTimeout(function() {
                document.getElementById('my-app').remove();
            }, milliseconds);
        }
    </script>
    <script>
        // Store video time in seconds
        var TimeStore = 0;

        function GetVideoTime() {
            var vid1 = document.getElementById("vidArea");
            if (vid1) {
                var currentTime = vid1.currentTime;
                TimeStore = currentTime; // Update stored time
                return formatTime(currentTime); // Format and return time
            }
            return null; // Return null if video element is not found
        }

        // Helper function to format time as MM:SS
        function formatTime(seconds) {
            var mins = Math.floor(seconds / 60);
            var secs = Math.floor(seconds % 60);
            return mins + ":" + (secs < 10 ? "0" : "") + secs;
        }

        // Function to go to the stored time
        function goToSession() {
            var timeInSeconds = TimeStore;
            var vid1 = document.getElementById("vidArea");
            if (vid1) {
                vid1.currentTime = timeInSeconds; // Seek to the stored time
                vid1.play(); // Start playing from that time
                console.log("Jumped to:", formatTime(timeInSeconds));
            }
        }

        // Function to show the current time of the video
        function showCurrentTime() {
            var formattedTime = GetVideoTime();
            if (formattedTime) {
                document.getElementById("currentTimeDisplay").innerText =
                    "Current Video Time: " + formattedTime + " (In seconds: " + TimeStore.toFixed(2) + ")";
                console.log("Current Video Time:", formattedTime);
            } else {
                document.getElementById("currentTimeDisplay").innerText =
                    "No video is currently playing.";
            }
        }
    </script>
</head>

<body class="show">

    <div id="loading-screen">
        <div class="loader"></div>
        <p>Loading...</p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('loading-screen').style.display = 'none';
        });
    </script>

    <?php  //print_r($arrayuserlevel); 
    ?>
    <!-- Begin page -->
    <div id="wrapper">
        <!-- Layout -->
        <?php // print_r(session('client')); exit(); 
        ?>
        <?php // print_r(session()->get('client')); 
        ?>
        <?php if (session('isLoggedIn') && session()->get('client')): ?>
            <?php


            switch ($homepage) {
                case 0:
                    $landingpage = 'my_training';
                    break;
                // case 1:
                //     $landingpage = 'etrack/dashboard';
                //     break;
                // case 2:
                //     $landingpage = 'etrack/attendance/view';
                //     break;
                // case 3:
                //     $landingpage = 'Task/Task_manage/my_task';
                //     break;
                // case 4:
                //     $landingpage = 'Task/Task_manage/team_tasks_allocate';
                //     break;
                // case 5:
                //     $landingpage = 'Project_Manage/PM_ucn';
                //     break;
                // case 6:
                //     $landingpage = 'Project_Manage/PM_projects';
                //     break;
                // case 7:
                //     $landingpage = 'marketplace/dashboard';
                //     break;
                // case 8:
                //     $landingpage = 'SCORM/Scorm_client/reviews';
                //     break;
                default:
                    $landingpage = 'my_training';
            }

            ?>

            <div class="app-menu menuitem-active">

                <!-- Brand Logo -->
                <div class="logo-box">

                    <?php if ($logo_dark != '') { ?>
                        <a href="<?php echo base_url($landingpage); ?>" class="logo-light">
                            <img src="<?php echo base_url('assets/assets/uploads/client_logo/' . trim($client) . '/' . trim($logo_dark)); ?>"
                                alt="logo" class="logo-lg" style="height:25px;">
                        </a>
                    <?php } else { ?>
                        <a href="<?php echo base_url($landingpage); ?>" class="logo-light">
                            <img src="<?php echo base_url(); ?>public/Landing/images/logo-light.png" alt="logo" class="logo-lg"
                                style="height:25px;">
                            <img src="<?php echo base_url(); ?>public/Landing/images/android-chrome-192x192.png" alt="logo"
                                class="logo-sm" style="height:25px;">
                        </a>
                    <?php } ?>
                    <?php if ($logo != '') { ?>
                        <a href="<?php echo base_url($landingpage) ?>" class="logo-dark">
                            <img src="<?php echo base_url('assets/assets/uploads/client_logo/' . trim($client) . '/' . trim($logo)); ?>"
                                alt="dark logo" class="logo-lg" style="height:25px;">
                        </a>
                    <?php } else { ?>
                        <a href="<?php echo base_url($landingpage); ?>" class="logo-dark">
                            <img class="logo-lg" src="<?php echo base_url(); ?>public/Landing/images/logo-dark.png"
                                alt="dark logo" style="height:25px;">
                            <img src="<?php echo base_url(); ?>public/Landing/images/android-chrome-192x192.png" alt="logo"
                                class="logo-sm" style="height:25px;">
                        </a>
                    <?php } ?>


                </div>

                <!-- menu-left -->
                <div class="scrollbar show h-100" data-simplebar="init">
                    <div class="simplebar-wrapper" style="margin: 0px;">
                        <div class="simplebar-height-auto-observer-wrapper">
                            <div class="simplebar-height-auto-observer"></div>
                        </div>
                        <div class="simplebar-mask">
                            <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                <div class="simplebar-content-wrapper" tabindex="0" role="region"
                                    aria-label="scrollable content" style="height: 100%; overflow: hidden scroll;">
                                    <div class="simplebar-content" style="padding: 0px;">
                                        <div class="user-box text-center">

                                            <img class="rounded-circle" style="width: 100px; height: 100px;" src="<?php echo (!empty($profile_image) && !empty($profile_foldername))
                                                                                                                        ? base_url('assets/assets/uploads/profile/' . session('id_user') . "/" . $profile_foldername . "/" . $profile_image)
                                                                                                                        : base_url('public/aristo_assets/images/User_2_1.svg'); ?>"
                                                alt="User image"
                                                onerror="this.onerror=null; this.src='<?php echo base_url('public/aristo_assets/images/User_2_1.svg'); ?>';">

                                            <div class="dropdown">
                                                <div class=" h5 mb-1 d-block"
                                                    data-bs-toggle="dropdown"><?php $name_user = session('name');
                                                                                echo $name_user; ?></div>
                                            </div>

                                        </div>

                                    </div>


                                    <!--- Menu -->
                                    <ul class="menu menu-font-overite">
                                        <li class="menu-item">
                                            <a href="<?php echo base_url() . "my_training"; ?>" class="menu-link">
                                                <span class="menu-icon"><i class="mdi mdi-motion-play-outline"></i></span>
                                                <span class="menu-text"> <?php echo lang('Buttons.Dashboard'); ?> </span>
                                            </a>
                                        </li>


                                        <li class="menu-item">
                                            <a href="<?php echo base_url() . "SCORM/scorm_courses"; ?>"
                                                class="menu-link">
                                                <span class="menu-icon"><i class="mdi mdi-youtube-tv"></i></span>
                                                <span class="menu-text"> <?php echo lang('Buttons.My Courses'); ?> </span>
                                            </a>
                                        </li>

                                        <?php if (in_array('13', $arrayaccessmenu)) { ?>

                                            <li class="menu-item">
                                                <a href="<?php echo base_url() . "marketplace/learning_dashboard"; ?>"
                                                    class="menu-link">
                                                    <span class="menu-icon"><i class="mdi mdi-school-outline"></i></span>
                                                    <span class="menu-text"> <?php echo lang('Buttons.Learning Plan'); ?></span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if (in_array('17', $arrayaccessmenu)) { ?>
                                            <?php if ($client != 19) { ?>
                                                <li class="menu-item">
                                                    <a href="<?php echo base_url() . "SCORM/Scorm_learn_group"; ?>"
                                                        class="menu-link">
                                                        <span class="menu-icon"><i
                                                                class="mdi mdi-youtube-subscription"></i></span>
                                                        <span class="menu-text"> <?php echo lang('Buttons.Course Group'); ?> </span>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        <?php } ?>
                                        <!-- <li class="menu-item">
                                                <form action="<?php echo base_url('Reports/User_report'); ?>" method="POST"><?= csrf_field() ?>
                                                    <button type="submit" class="menu-link"
                                                        style="border:none;background:none;cursor:pointer;"
                                                        title="My Report">
                                                        <span class="menu-icon"><i class="fe-bar-chart"></i></span>
                                                        <span class="menu-text">My Report</span>
                                                    </button>
                                                </form>

                                            </li> -->
                                        <?php if (in_array('45', $arrayuserlevel)) { ?>
                                            <li class="menu-item">
                                                <a href="<?php echo base_url() . "SCORM/Scorm_client/reviews"; ?>"
                                                    class="menu-link">
                                                    <span class="menu-icon"><i class="mdi mdi-dots-grid"></i></span>
                                                    <span class="menu-text"> <?php echo lang('Buttons.Client Dashboard'); ?> </span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if (in_array('14', $arrayaccessmenu)) { ?>

                                            <?php if ($marketplaceaccess == 1) { ?>
                                                <li class="menu-item">
                                                    <a href="<?php echo base_url() . "marketplace/dashboard"; ?>"
                                                        class="menu-link">
                                                        <span class="menu-icon"><i class="mdi mdi-shopping-outline"></i></span>
                                                        <span class="menu-text"> <?php echo lang('Buttons.Marketplace'); ?> </span>
                                                    </a>
                                                </li>
                                        <?php }
                                        } ?>
                                        <?php if (in_array('15', $arrayaccessmenu)) {
                                            // if ($client == 1) {
                                            // if (in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel)) { 
                                        ?>
                                            <li class="menu-item">
                                                <a href="<?php echo base_url() . "Certification/dashboard"; ?>"
                                                    class="menu-link">
                                                    <span class="menu-icon"><i
                                                            class="mdi mdi-certificate-outline"></i></span>
                                                    <span class="menu-text"><?php echo lang('Buttons.Certifications'); ?></span>
                                                </a>
                                            </li>
                                        <?php //}
                                            //}
                                        } ?>
                                        <?php if (in_array('16', $arrayaccessmenu)) { ?>

                                            <li class="menu-item">
                                                <a href="<?php echo base_url() . "Game/gamification"; ?>" class="menu-link">
                                                    <span class="menu-icon"><i
                                                            class="mdi mdi-gamepad-variant-outline"></i></span>
                                                    <span class="menu-text"> <?php echo lang('Buttons.Gamification'); ?> </span>
                                                </a>
                                            </li>
                                        <?php
                                        } ?>


                                        <?php if (in_array('44', $arrayuserlevel)) { ?>
                                            <li class="menu-item">
                                                <a href="#admin" data-bs-toggle="collapse" class="menu-link">
                                                    <span class="menu-icon"><i class="fe-settings"></i></span>
                                                    <span class="menu-text"> <?php echo lang('Buttons.Admin'); ?> </span>
                                                    <span class="menu-arrow"></span>
                                                </a>
                                                <div class="collapse" id="admin">
                                                    <ul class="sub-menu">

                                                        <li class="menu-item">
                                                            <a href="<?php echo base_url() . "User_login/client_users"; ?>"
                                                                class="menu-link">
                                                                <span class="menu-text"> <?php echo lang('Buttons.Users'); ?> </span>
                                                            </a>
                                                        </li>

                                                        <!-- <li class="menu-item">
                                                                <a href="<?php echo base_url() . "SCORM/Scorm_learn_group"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> Course Groups</span>
                                                                </a>
                                                            </li> -->

                                                        <li class="menu-item">
                                                            <a href="<?php echo base_url() . "SCORM/Scorm_user_group"; ?>"
                                                                class="menu-link">
                                                                <span class="menu-text"> <?php echo lang('Buttons.User Groups'); ?></span>
                                                            </a>
                                                        </li>
                                                        <?php if ($client == 1) { ?>
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "Support/Support/admin_support"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> <?php echo lang('Buttons.Support'); ?></span>
                                                                </a>
                                                            </li>
                                                        <?php } ?>

                                                        <?php if (in_array('6', $arrayuserlevel)) { ?>
                                                            <!--     <ul class="sub-menu">
                                                                            <li class="menu-item">
                                                                                <a href="<?php echo base_url() . "SCORM/scorm_meta_category/category"; ?>" class="menu-link">
                                                                                    <span class="menu-text">Categories</span>
                                                                                </a>
                                                                            </li>
                                                                        </ul> -->
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            </li>
                                        <?php } ?>
                                        <?php if (in_array('6', $arrayuserlevel)) { ?>
                                            <li class="menu-item">
                                                <a href="#super" data-bs-toggle="collapse" class="menu-link">
                                                    <span class="menu-icon"><i class="mdi mdi-chess-king"></i></span>
                                                    <span class="menu-text"> <?php echo lang('Buttons.Super_Admin'); ?> </span>
                                                    <span class="menu-arrow"></span>
                                                </a>
                                                <div class="collapse" id="super">
                                                    <ul class="sub-menu">

                                                        <li class="menu-item">
                                                            <a href="<?php echo base_url() . "User_login/client_list"; ?>"
                                                                class="menu-link">
                                                                <span class="menu-text"> <?php echo lang('Buttons.Clients'); ?></span>
                                                            </a>
                                                        </li>

                                                        <li class="menu-item">
                                                            <a href="<?php echo base_url() . "Support/Support_user/notificatoins"; ?>"
                                                                class="menu-link">
                                                                <span class="menu-text"> <?php echo lang('Buttons.Notifications'); ?></span>
                                                            </a>
                                                        </li>


                                                    </ul>
                                                </div>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                    <!--- End Menu -->
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="simplebar-placeholder" style="width: auto; height: 1409px;"></div>
                </div>
                <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                    <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                </div>
                <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                    <div class="simplebar-scrollbar"
                        style="height: 309px; transform: translate3d(0px, 233px, 0px); display: block;"></div>
                </div>
            </div>
    </div>
    <div class="content-page">

        <div class="navbar-custom">
            <div class="topbar" style="background-color: <?php echo session('banner_color'); ?>;">
                <div class="topbar-menu d-flex align-items-center gap-1">

                    <!-- Topbar Brand Logo -->
                    <div class="logo-box">
                        <?php if ($logo_dark != '') { ?>
                            <a href="<?php echo base_url($landingpage); ?>" class="logo-light">
                                <img src="<?php echo base_url('assets/assets/uploads/client_logo/' . trim($client) . '/' . trim($logo_dark)); ?>"
                                    alt="logo" style="height:25px;">
                            </a>
                        <?php } else { ?>
                            <a href="<?php echo base_url($landingpage); ?>" class="logo-light">
                                <img src="<?php echo base_url(); ?>public/Landing/images/logo-light.png" alt="logo"
                                    style="height:25px;">
                            </a>
                        <?php } ?>
                        <?php if ($logo != '') { ?>
                            <a href="<?php echo base_url($landingpage) ?>" class="logo-dark">
                                <img src="<?php echo base_url('assets/assets/uploads/client_logo/' . trim($client) . '/' . trim($logo)); ?>"
                                    alt="dark logo" style="height:25px;">
                            </a>
                        <?php } else { ?>
                            <a href="<?php echo base_url($landingpage); ?>" class="logo-dark">
                                <img src="<?php echo base_url(); ?>public/Landing/images/logo-dark.png" alt="dark logo"
                                    style="height:25px;">
                            </a>
                        <?php } ?>
                    </div>
                    <button class="button-toggle-menu">
                        <i class="mdi mdi-menu"></i>
                    </button>
                </div>

                <ul class="topbar-menu d-flex align-items-center">
                    <li class="app-search  me-3 d-none d-lg-block">
                        <form action="<?php echo base_url($landingpage); ?>" method="POST"><?= csrf_field() ?>
                            <div class="input-group input-group-sm">
                                <input type="search" name="search" class="form-control rounded-pill" value=""
                                    placeholder="<?php echo lang('Buttons.Search'); ?>..." id="top-search">
                                <button type="submit" style="background: none;
    color: inherit;
    border: none;
    padding: 0;
    font: inherit;
    cursor: pointer;
    outline: inherit;"><span class="fe-search search-icon font-16"></span></button>
                            </div>

                        </form>
                    </li>
                    <?php if ($client != 1) { ?>
                        <li class="d-none d-sm-inline-block">
                            <div class="nav-link waves-effect waves-light" id="light-dark-mode">
                                <i class="ri-moon-line font-16"></i>
                            </div>
                        </li>
                    <?php } ?>
                    <?php if (session('super_admin_imper')) {
                        if (session('super_admin_imper') == 'imper') {
                    ?>
                            <li class="dropdown notification-list">
                                <a href="<?php echo base_url() . "Settings/settings/clientImpersonate?cid=1"; ?>"><i
                                        class="fa fa-random"></i> &nbsp;</a>
                            </li>
                    <?php
                        }
                    }
                    ?>
                   
                    <?php if (session('super_user_imper')) {
                        if (session('super_user_imper') == 'user_imper') {
                    ?>
                            <li class="dropdown notification-list">
                                <form class="form-horizontal"
                                    action="<?php echo base_url('Settings/settings/userImpersonate') ?>" method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="id_user" value="5">
                                    <button class="btn btn-sm btn-default"><i class="fa fa-random"></i></button>
                                </form>
                                <!-- <a href="<?php echo base_url() . "/Settings/settings/userImpersonate?id_user=5" ?>"><i class="fa fa-random"></i> &nbsp;</a> -->
                            </li>
                    <?php
                        }
                    }
                    ?>


                    <?php

                    if (!empty($profile_image) && !empty($profile_foldername)) {
                        $profile_link = base_url('assets/assets/uploads/profile/' . session('id_user') . "/" . $profile_foldername . "/" . $profile_image);
                    } else {
                        $profile_link = base_url('public/aristo_assets/images/User_2_1.svg');
                    } ?>
                    <!-- User Dropdown -->
                    <li class="dropdown">
                        <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light"
                            data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                            aria-expanded="false">
                            <img src="<?php echo $profile_link; ?>" alt="image" class="rounded-circle ">
                            <span class="ms-1 d-none d-md-inline-block">
                                <?php $name_user = session('name');
                                echo $name_user; ?> <i class="mdi mdi-chevron-down"></i>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                            <a href="<?php echo base_url('User_login/profile'); ?>"
                                class="dropdown-item notify-item">
                                <i class="fe-user"></i>
                                <span><?php echo lang('Buttons.My Account'); ?></span>
                            </a>
                            <a href="<?php echo base_url('Support/Support_user'); ?>"
                                class="dropdown-item notify-item">
                                <i class="fe-mail"></i>
                                <span><?php echo lang('Buttons.My Support'); ?></span>
                            </a>


                            <a href="<?php echo base_url() . "Support/Support_user/view_notificatoins"; ?>"
                                class="dropdown-item notify-item">
                                <i class="fe-bell"></i>
                                <span><?php echo lang('Buttons.Notifications'); ?></span>
                            </a>
                            <!-- <a href="<?php echo base_url('Payment/Billing/purchase_history'); ?>"
                                        class="dropdown-item notify-item">
                                        <i class="fe-clock"></i>
                                        <span>Purchase History</span>
                                    </a> -->
                            <!--  <a href="<?php echo base_url() . "my_training/help"; ?>"
                                        class="dropdown-item notify-item">
                                        <i class="fe-help-circle"></i>
                                        <span>FAQs</span>
                                    </a> -->
                            <div class="dropdown-divider"></div>
                            <a href="<?php echo base_url('logout') ?>" class="dropdown-item notify-item">
                                <i class="fe-log-out"></i>
                                <span><?php echo lang('Buttons.Logout'); ?></span>
                            </a>
                        </div>
                    </li>


                </ul>
            </div>

        </div>

    <?php else:
            return redirect()->to(base_url('forgot_password'));
    ?>
    <?php endif; ?>

    <div class="content">
        <div class="container-fluid">

            <?php if (session('success')): ?>
                <div id="alertdiv" class="floaterwindow_green">
                    <?= session('success') ?>
                    <div id="progressBar"></div>
                </div>
            <?php endif; ?>
            <?php if (session('error')): ?>
                <div id="alertdiv" class="floaterwindow_red">
                    <?= session('error') ?>
                    <div id="progressBar"></div>
                </div>
            <?php endif; ?>