<?php
$theme = session('theme_color');
if ($theme == 0) {
    $theme = 'light';
} else {
    $theme = 'dark';
}

$userlevel = session('userlevel');
if (null !== session('region')) {
    $region = session('region');
} else {
    $region = 1;
}
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
    <link href="<?php echo base_url(); ?>public/creative/assets/libs/nestable2/jquery.nestable.min.css" rel="stylesheet" />
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
            gap: 6px;
            padding: 10px 14px;
            background-color: #f7c0c0;
            border: 1px solid #f4cbcb;
            color: #a22f22;
            border-radius: 6px;
            margin-bottom: 8px;
        }
        .persistent-success {
            display: flex;
            align-items: flex-start;
            gap: 6px;
            padding: 10px 14px;
            background-color: #e8f4eb;
            border: 1px solid #72f7ae;
            color: #010905;
            border-radius: 6px;
            margin-bottom: 8px;
        }

        .warning-icon {
            font-size: 14px;
            line-height: 1.0;
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

        /* .menu-font-overite .menu-link .menu-text {
            font-size: 14px;
            color: black;
        } */
        html[data-menu-color="light"] {
            --ct-menu-item-color: #000;
            /* your black text */
        }

        /* .dark .menu-font-overite .menu-link .menu-text {
            color: #fff;
            /* white text for dark mode */
        /*  */
    </style>
    <style>
        /* DARK MODE SELECT2 */
        [data-bs-theme="dark"] .select2-container--default .select2-selection--single,
        [data-bs-theme="dark"] .select2-container--default .select2-selection--multiple {
            background-color: #36404a !important;
            border: 1px solid #44515f !important;
            color: #fff !important;
        }

        [data-bs-theme="dark"] .select2-container--default .select2-selection__rendered {
            color: #fff !important;
        }

        [data-bs-theme="dark"] .select2-dropdown {
            background-color: #36404a !important;
            color: #fff !important;
        }

        [data-bs-theme="dark"] .select2-results__option {
            background-color: #36404a !important;
            color: #fff !important;
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
    <style>
        .password-rules.compact {
            font-size: 13px;
            line-height: 1.4;
            margin-top: 6px;
        }

        .password-rules.compact p {
            margin: 3px 0;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* circle base */
        .password-rules .icon {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 1.5px solid #999;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
        }

        /* valid state */
        .password-rules .valid .icon {
            border-color: green;
            background-color: green;
            color: white;
        }

        .password-rules .valid .icon::before {
            content: "✔";
        }

        /* invalid state */
        .password-rules .invalid .icon {
            border-color: #bababa;
            background-color: transparent;
        }

        .password-rules .invalid .icon::before {
            content: "";
        }
    </style>
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
                    <!-- <?php //if ($client == 70) {
                            // $landingpage = 'Certification/Certification_Portal';
                            // } 
                            ?> -->

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
                                        <?php if (in_array('45', $arrayuserlevel)) { ?>
                                         <!--    <li class="menu-item">
                                                <a href="<?php echo base_url() . "SCORM/Scorm_client/reviews"; ?>"
                                                    class="menu-link">
                                                    <span class="menu-icon"><i class="mdi mdi-dots-grid"></i></span>
                                                    <span class="menu-text"> <?php echo lang('Buttons.Client Dashboard'); ?> </span>
                                                </a>
                                            </li> -->
                                        <?php } ?>

                                        <li class="menu-item">
                                            <a href="<?php echo base_url() . "SCORM/scorm_courses"; ?>"
                                                class="menu-link">
                                                <span class="menu-icon"><i class="mdi mdi-youtube-tv"></i></span>
                                                <span class="menu-text"> <?php echo lang('Buttons.My Courses'); ?> </span>
                                            </a>
                                        </li>

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
                                        <?php if (in_array('13', $arrayaccessmenu)) { ?>

                                            <li class="menu-item">
                                                <a href="<?php echo base_url() . "marketplace/learning_dashboard"; ?>"
                                                    class="menu-link">
                                                    <span class="menu-icon"><i class="mdi mdi-school-outline"></i></span>
                                                    <span class="menu-text"> <?php echo lang('Buttons.Learning Plan'); ?></span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php //if ($client == 1) { ?>
                                            <?php if (in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel)) { ?>
                                                <?php if (in_array('15', $arrayaccessmenu)) {  ?>
                                                    <li class="menu-item">
                                                        <a href="<?php echo base_url() . "Certification/certification_dashboard"; ?>"
                                                            class="menu-link">
                                                            <span class="menu-icon"><i
                                                                    class="mdi mdi-certificate"></i></span>
                                                            <span class="menu-text"><?php echo lang('Buttons.Certifications'); ?> Admin</span>
                                                        </a>
                                                    </li>
                                                <?php }
                                            } else { ?>
                                                <li class="menu-item">
                                                    <a href="<?php echo base_url() . "Certification/Certification_Portal"; ?>"
                                                        class="menu-link">
                                                        <span class="menu-icon"><i
                                                                class="mdi mdi-certificate"></i></span>
                                                        <span class="menu-text"><?php echo lang('Buttons.Certifications'); ?></span>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        <?php //} ?>
                                        <?php if (in_array('18', $arrayaccessmenu)) { ?>
                                            <li class="menu-item">
                                                <a href="<?php echo base_url() . "Certification/dashboard"; ?>"
                                                    class="menu-link">
                                                    <span class="menu-icon"><i
                                                            class="mdi mdi-certificate-outline"></i></span>
                                                    <span class="menu-text"><?php echo lang('Buttons.Certificate'); ?></span>
                                                </a>
                                            </li>
                                        <?php
                                        }
                                        ?>
                                        <?php if (in_array('16', $arrayaccessmenu)) { ?>

                                            <!--  <li class="menu-item">
                                                <a href="<?php echo base_url() . "Game/gamification"; ?>" class="menu-link">
                                                    <span class="menu-icon"><i
                                                            class="mdi mdi-gamepad-variant-outline"></i></span>
                                                    <span class="menu-text"> <?php echo lang('Buttons.Gamification'); ?> </span>
                                                </a>
                                            </li> -->
                                        <?php
                                        } ?>
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


                                        <?php if (in_array('19', $arrayaccessmenu)) { ?>
                                            <?php if (in_array('44', $arrayuserlevel) || in_array('5', $arrayuserlevel)) { ?>

                                                <li class="menu-item">
                                                    <a href="<?php echo base_url() . "Reports/client_reports"; ?>" class="menu-link">
                                                        <span class="menu-icon"><i
                                                                class="mdi mdi-file-chart-outline"></i></span>
                                                        <span class="menu-text"> <?php echo lang('Buttons.Reports'); ?> </span>
                                                    </a>
                                                </li>

                                        <?php }
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

                                        <?php if ($client == 1) { ?>
                                            <li class="menu-title"> <?php echo lang('Buttons.eTrack'); ?> </li>

                                            <?php if (in_array('8', $arrayuserlevel)) { ?>
                                                <li class="menu-item">
                                                    <form action="<?php echo base_url('demo/Demo_dashboard'); ?>" method="POST"><?= csrf_field() ?>
                                                        <button type="submit" class="menu-link"
                                                            style="border:none;background:none;cursor:pointer;" title="Demos">
                                                            <span class="menu-icon"><i class="fe-camera"></i></span>
                                                            <span class="menu-text"> <?php echo lang('Buttons.Demos'); ?> </span>
                                                        </button>
                                                    </form>
                                                </li>
                                            <?php } ?>
                                            <?php if (in_array('68', $arrayuserlevel)) { ?>
                                                <li class="menu-item">
                                                    <a href="<?php echo base_url() . "etrack/Sales_admin"; ?>"
                                                        class="menu-link">
                                                        <span class="menu-icon"><i class="mdi mdi-chart-line"></i></span>
                                                        <span class="menu-text">Sales Dashboard</span>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <?php if (in_array('4154', $arrayuserlevel)) { ?>
                                                <li class="menu-item">
                                                    <a href="<?php echo base_url() . "Support/Support/admin_support"; ?>" class="menu-link">
                                                        <span class="menu-icon">
                                                            <i class="mdi mdi-mail"></i></span>
                                                        <span class="menu-text"> <?php echo lang('UI_Text.Admin_Support'); ?></span>
                                                    </a>
                                                </li>
                                            <?php }
                                            ?>
                                            <?php if ($region == 1) { ?>
                                                <li class="menu-item">
                                                    <a href="<?php echo base_url() . "etrack/dashboard"; ?>" class="menu-link">
                                                        <span class="menu-icon"><i class="fe-at-sign"></i></span>
                                                        <span class="menu-text"> Etrack Dashboard </span>
                                                    </a>
                                                </li>

                                                <?php if (in_array('69', $arrayuserlevel)) { ?>
                                                    <li class="menu-item">
                                                        <a href="<?php echo base_url() . "etrack/Fin_admin/dashboard"; ?>"
                                                            class="menu-link">
                                                            <span class="menu-icon"><i class="mdi mdi-currency-usd"></i></span>
                                                            <span class="menu-text">Finance Dashboard</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>

                                                <?php if (array_intersect(['3048', '2010', '3014', '69'], array_map('strval', $arrayuserlevel))) { ?>
                                                    <li class="menu-item">
                                                        <a href="<?php echo base_url() . "etrack/executive_dashboard"; ?>"
                                                            class="menu-link">
                                                            <span class="menu-icon"><i class="mdi mdi-view-dashboard-variant"></i></span>
                                                            <span class="menu-text">CEO Dashboard</span>
                                                        </a>
                                                    </li>
                                                <?php } ?>


                                                <?php if (in_array('7', $arrayuserlevel)) { ?>
                                                    <li class="menu-item">
                                                        <a href="<?php echo base_url() . "Project_Manage/Effort_Tracker"; ?>" class="menu-link">
                                                            <span class="menu-icon"><i class="fe-clock"></i></span>
                                                            <span class="menu-text"> Effort Tracker </span>
                                                        </a>
                                                    </li>
                                                    <li class="menu-item">
                                                        <a href="<?php echo base_url() . "Etrack/Claims/Status"; ?>" class="menu-link">
                                                            <span class="menu-icon"><i class="mdi mdi-access-point"></i></span>
                                                            <span class="menu-text"> Status </span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <li class="menu-item">
                                                    <a href="<?php echo base_url() . "Etrack/claims"; ?>" class="menu-link">
                                                        <span class="menu-icon"><i class="mdi mdi-currency-brl"></i></span>
                                                        <span class="menu-text"> Claims</span>
                                                    </a>
                                                </li>
                                                <li class="menu-item">
                                                    <a href="#common" data-bs-toggle="collapse" class="menu-link">
                                                        <span class="menu-icon"><i class="mdi mdi-passport"></i></span>
                                                        <span class="menu-text"> <?php echo lang('Buttons.HRMS'); ?> </span>
                                                        <span class="menu-arrow"></span>
                                                    </a>
                                                    <div class="collapse" id="common">
                                                        <ul class="sub-menu">
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "etrack/dashboard/org_chart"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"><?php echo lang('Buttons.Org_Structure'); ?></span>
                                                                </a>
                                                            </li>

                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "etrack/dashboard/holiday_cal"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"><?php echo lang('Buttons.Holidays'); ?></span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "etrack/dashboard/policies"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"><?php echo lang('Buttons.Policies'); ?></span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "etrack/employee_details/dependents"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> <?php echo lang('Buttons.My_Dependents'); ?></span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "User_login/profile/update_data"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> <?php echo lang('Buttons.My_Personal_Data'); ?></span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "User_login/profile/documents"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> <?php echo lang('Buttons.My_Documents'); ?></span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "etrack/Payroll"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> <?php echo lang('Buttons.My_Payslips'); ?></span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "etrack/employee_details/appraisals"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> <?php echo lang('Buttons.My_Appraisals'); ?></span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "etrack/employee_details/income_tax"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> <?php echo lang('Buttons.My_Income_Tax'); ?></span>
                                                                </a>
                                                            </li>

                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "Etrack/exit_clearance" ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"><?php echo lang('Buttons.Exit_Clearance'); ?></span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                                <li class="menu-item">
                                                    <a href="#leavemng" data-bs-toggle="collapse" class="menu-link">
                                                        <span class="menu-icon"><i class="mdi mdi-beach"></i></span>
                                                        <span class="menu-text"> <?php echo lang('Buttons.Attendance'); ?></span>
                                                        <span class="menu-arrow"></span>
                                                    </a>
                                                    <div class="collapse" id="leavemng">
                                                        <ul class="sub-menu">
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "etrack/leaves"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> <?php echo lang('Buttons.Leave_Management'); ?></span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "etrack/leaves/statement"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> <?php echo lang('Buttons.My_Leaves'); ?></span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "etrack/attendance/view/1"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> <?php echo lang('Buttons.My_Attendance'); ?></span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "etrack/attendance/wfh_statement_view"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"><?php echo lang('Buttons.Work_From_Home'); ?></span>
                                                                </a>
                                                            </li>
                                                            <?php if (session()->get('report_to_you') == 2) { ?>
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "etrack/attendance/team_attendance"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"> <?php echo lang('Buttons.Team_Attendance'); ?></span>
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "etrack/attendance/access_card_data"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> <?php echo lang('Buttons.Access_Card'); ?></span>
                                                                </a>
                                                            </li>
                                                            <?php if (in_array('2030', $arrayuserlevel)) { ?>
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "etrack/Attendance_admin"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"> <?php echo lang('Buttons.Access_Card_Data'); ?></span>
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                            <?php if (session()->get('report_to_you') == 2) { ?>
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "etrack/leaves/team_leaves"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"> <?php echo lang('Buttons.Team_Leaves'); ?></span>
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                </li>
                                                <?php if (in_array('4', $arrayuserlevel)) { ?>
                                                    <li class="menu-item">
                                                        <a href="#tasks" data-bs-toggle="collapse" class="menu-link">
                                                            <span class="menu-icon"><i class="mdi mdi-alarm-note"></i></span>
                                                            <span class="menu-text"> <?php echo lang('Buttons.Project_Management'); ?> </span>
                                                            <span class="menu-arrow"></span>
                                                        </a>
                                                        <div class="collapse" id="tasks">
                                                            <ul class="sub-menu">
                                                                <?php if (in_array('4', $arrayuserlevel)) { ?>
                                                                    <li class="menu-item">
                                                                        <a href="<?php echo base_url() . "Project_Manage/PM_ucn"; ?>"
                                                                            class="menu-link">
                                                                            <span class="menu-text"> <?php echo lang('Buttons.My_UCN'); ?></span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="menu-item">
                                                                        <a href="<?php echo base_url() . "User_login/client_list/my_client_list"; ?>"
                                                                            class="menu-link">
                                                                            <span class="menu-text"> <?php echo lang('Buttons.Clients'); ?></span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="menu-item">
                                                                        <a href="<?php echo base_url() . "Project_Manage/Invoices"; ?>" class="menu-link">
                                                                            <span class="menu-text"> Invoices </span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="menu-item">
                                                                        <a href="<?php echo base_url() . "Project_Manage/Effort_Tracker/Approve_access"; ?>" class="menu-link">
                                                                            <span class="menu-text"> Project Access Request </span>
                                                                        </a>
                                                                    </li>
                                                                <?php } ?>
                                                            </ul>
                                                        </div>
                                                    </li>
                                                <?php } ?>
                                                <?php if (in_array('2010', $arrayuserlevel)) { ?>
                                                    <li class="menu-item">
                                                        <a href="#hr" data-bs-toggle="collapse" class="menu-link">
                                                            <span class="menu-icon"><i class="mdi mdi-handshake-outline"></i></span>
                                                            <span class="menu-text"><?php echo lang('Buttons.Admin'); ?> <?php echo lang('Buttons.HRMS'); ?></span>
                                                            <span class="menu-arrow"></span>
                                                        </a>
                                                        <div class="collapse" id="hr">
                                                            <ul class="sub-menu">
                                                                <!--  <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "etrack/HR_admin/hr_dashboard"; ?>" class="menu-link">
                                                                        <span class="menu-text"> HR Dashboard</span>
                                                                    </a>
                                                                </li> -->
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "etrack/leaveadmin"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"><?php echo lang('Buttons.HR_Leaves'); ?></span>
                                                                    </a>
                                                                </li>
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "etrack/HR_admin"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"><?php echo lang('Buttons.HR_Attendance'); ?></span>
                                                                    </a>
                                                                </li>
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "etrack/HR_admin/personal"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"><?php echo lang('Buttons.HR_Personal_Data'); ?></span>
                                                                    </a>
                                                                </li>

                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "Holiday/holidays"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"><?php echo lang('Buttons.HR_Holidays'); ?></span>
                                                                    </a>
                                                                </li>
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "Emanual/emanual_product/document_view" ?>" class="menu-link">

                                                                        <span class="menu-text"><?php echo lang('Buttons.HR_Policies'); ?></span>
                                                                    </a>
                                                                </li>

                                                                <!-- <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "Others/Tournaments"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"> Tournaments</span>
                                                                    </a>
                                                                </li> -->
                                                            </ul>
                                                        </div>
                                                    </li>
                                                <?php } ?>

                                                <?php if (in_array('3014', $arrayuserlevel)) { ?>
                                                    <li class="menu-item">
                                                        <a href="#finance" data-bs-toggle="collapse" class="menu-link">
                                                            <span class="menu-icon"><i class="mdi mdi-currency-inr"></i></span>
                                                            <span class="menu-text"> <?php echo lang('Buttons.Finance'); ?></span>
                                                            <span class="menu-arrow"></span>
                                                        </a>
                                                        <div class="collapse" id="finance">
                                                            <ul class="sub-menu">

                                                                <?php if (in_array('3048', $arrayuserlevel)) { ?>
                                                                    <li class="menu-item">
                                                                        <a href="<?php echo base_url() . "etrack/Fin_admin"; ?>"
                                                                            class="menu-link">
                                                                            <span class="menu-text"><?php echo lang('Buttons.Payroll'); ?></span>
                                                                        </a>
                                                                    </li>
                                                                <?php } ?>
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "Project_Manage/Invoices"; ?>" class="menu-link">
                                                                        <span class="menu-text"> Invoices</span>
                                                                    </a>
                                                                </li>
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "Project_Manage/MileStones/milestones_summary"; ?>" class="menu-link">
                                                                        <span class="menu-text"> Milestones</span>
                                                                    </a>
                                                                </li>
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "Project_Manage/PM_wip"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"><?php echo lang('Buttons.WIP Summary'); ?></span>
                                                                    </a>
                                                                </li>

                                                                <!-- <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "etrack/Fin_admin/purchase_orders"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text">PO - Received</span>
                                                                    </a>
                                                                </li>
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "etrack/Fin_admin/purchase_orders"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text">Inv. - Issued</span>
                                                                    </a>
                                                                </li>
                                                              
                                                              
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "etrack/Fin_admin/purchase_orders"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text">Inv. - Received</span>
                                                                    </a>
                                                                </li>
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "etrack/Fin_admin/purchase_orders_issued"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text">PO - Issued</span>
                                                                    </a>
                                                                </li> 
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "etrack/Fin_admin/vendors"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text">Vendors</span>
                                                                </a>
                                                            </li>-->

                                                                <?php if (in_array('3048', $arrayuserlevel)) { ?>
                                                                    <li class="menu-item">
                                                                        <a href="<?php echo base_url() . "etrack/Fin_admin/approve_grace"; ?>"
                                                                            class="menu-link">
                                                                            <span class="menu-text"> <?php echo lang('Buttons.Grace Management'); ?></span>
                                                                        </a>
                                                                    </li>
                                                                <?php } ?>


                                                            </ul>
                                                        </div>
                                                    </li>
                                                <?php } ?>
                                                <?php if (in_array('2015', $arrayuserlevel)) { ?>
                                                    <!--                                                     <li class="menu-item">
                                                        <a href="#atsmng" data-bs-toggle="collapse" class="menu-link">
                                                            <span class="menu-icon"><i class="fe-activity"></i></span>
                                                            <span class="menu-text"> <?php echo lang('Buttons.ATS'); ?> </span>
                                                            <span class="menu-arrow"></span>
                                                        </a>
                                                        <div class="collapse" id="atsmng">
                                                            <ul class="sub-menu">
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "etrack/ATS"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"> <?php echo lang('Buttons.ATS'); ?> <?php echo lang('Buttons.Dashboard'); ?></span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </li> -->
                                                <?php } ?>
                                                <?php if (in_array('4154', $arrayuserlevel)) { ?>
                                                    <li class="menu-item">
                                                        <a href="#itsupport" data-bs-toggle="collapse" class="menu-link">
                                                            <span class="menu-icon"><i class="mdi mdi-hammer-wrench"></i></span>
                                                            <span class="menu-text"> <?php echo lang('Buttons.IT_Support'); ?> </span>
                                                            <span class="menu-arrow"></span>
                                                        </a>
                                                        <div class="collapse" id="itsupport">
                                                            <ul class="sub-menu">
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "etrack/ITSupport"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"> <?php echo lang('Buttons.IT_Support'); ?> <?php echo lang('Buttons.Dashboard'); ?></span>
                                                                    </a>
                                                                </li>

                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "etrack/ITSupport/support_admin"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"> <?php echo lang('Buttons.IT_Support'); ?> <?php echo lang('Buttons.Admin'); ?></span>
                                                                    </a>
                                                                </li>
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "etrack/ITSupport/assets"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"> <?php echo lang('Buttons.IT_Assets'); ?></span>
                                                                    </a>
                                                                </li>
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "etrack/ITSupport/softwares"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"> <?php echo lang('Buttons.IT_Softwares'); ?></span>
                                                                    </a>
                                                                </li>

                                                            </ul>
                                                        </div>
                                                    </li>
                                                <?php } ?>
                                            <?php } ?>
                                            <?php if (in_array('1005', $arrayuserlevel) || in_array('1010', $arrayuserlevel) || session()->get('client') == '1' || session()->get('client') == '25') { ?>
                                                <?php if ($id_user != 1324 && $id_user != 1135) { ?>
                                                    <li class="menu-item">
                                                        <a href="#others" data-bs-toggle="collapse" class="menu-link">
                                                            <span class="menu-icon"><i class="mdi mdi-auto-fix"></i></span>
                                                            <span class="menu-text"> <?php
                                                                                        echo lang('Buttons.Custom_Projects'); ?> </span>
                                                            <span class="menu-arrow"></span>
                                                        </a>
                                                        <div class="collapse" id="others">
                                                            <ul class="sub-menu">
                                                                <!-- <li class="menu-item">
                                                            <a href="<?php echo base_url() . "Contentforu/Dashboard"; ?>"
                                                                class="menu-link">
                                                                <span class="menu-text"> Contentforu </span>
                                                            </a>
                                                        </li> -->

                                                                <?php if (in_array('1005', $arrayuserlevel)) { ?>
                                                                    <!--  <li class="menu-item">
                                                                <a href="<?php echo base_url() . "Emanual/dashboard"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> e-Manual Dashboard </span>
                                                                </a>
                                                            </li> -->
                                                                <?php } ?>
                                                                <?php if (in_array('1010', $arrayuserlevel)) { ?>
                                                                    <!-- <li class="menu-item">
                                                                <a href="<?php echo base_url() . "Emanual/emanual_product"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> e-Manual Editor </span>
                                                                </a>
                                                            </li> -->
                                                                <?php } ?>
                                                                <?php if (session()->get('client') == 1 || session()->get('client') == '25') { ?>
                                                                    <!-- <li class="menu-item">
                                                                <a href="<?php echo base_url() . "Others/Ojts_consolidated"; ?>" class="menu-link">
                                                                    <span class="menu-text"> OJTS View </span>
                                                                </a>
                                                            </li> -->
                                                                    <li class="menu-item">
                                                                        <a href="<?php echo base_url() . "Others/Ojts_consolidated/ojts_download_pdf"; ?>"
                                                                            class="menu-link">
                                                                            <span class="menu-text"> <?php echo lang('Buttons.OJTS'); ?></span>
                                                                        </a>
                                                                        <?php if (session()->get('id_user') == 1 || session()->get('id_user') == '1184' || session()->get('id_user') == '834') { ?>
                                                                            <a href="<?php echo base_url() . "Emanual/emanual_product/helpdocument_view"; ?>"
                                                                                class="menu-link">
                                                                                <span class="menu-text">DOCHEK Help</span>
                                                                            </a>
                                                                        <?php } ?>
                                                                    </li>

                                                                <?php } ?>
                                                                <?php //if (session()->get('client') == 1) { 
                                                                ?>
                                                                <!-- <li class="menu-item">
                                                                <a href="<?php echo base_url() . "open/Question_bank/tab_questions_post"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> <?php echo lang('Buttons.Question_Bank'); ?></span>
                                                                </a>
                                                            </li> -->
                                                                <?php //} 
                                                                ?>
                                                            </ul>
                                                        </div>
                                                    </li>
                                                <?php } ?>
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

                                                                <form action="<?php echo base_url('marketplace/admin'); ?>" method="POST"><?= csrf_field() ?>
                                                                    <input type="hidden" name="type" value="1">
                                                                    <button type="submit" class="menu-link"
                                                                        style="border:none;background:none;cursor:pointer;"
                                                                        title="My Report">
                                                                        <!-- <span class="menu-icon"><i class="fe-bar-chart"></i></span> -->
                                                                        <span class="menu-text">Marketplace Admin</span>
                                                                    </button>
                                                                </form>
                                                            </li>
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "category/dashboard"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> <?php echo lang('Buttons.Categories'); ?></span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "User_login/client_list"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> <?php echo lang('Buttons.Clients'); ?></span>
                                                                </a>
                                                            </li>
                                                            <!-- <li class="menu-item">
                                                                <a href="<?php echo base_url() . "User_login/partners/partner_list"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> <?php echo lang('Buttons.Partners'); ?></span>
                                                                </a>
                                                            </li> -->
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "Support/Support_user/notificatoins"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> <?php echo lang('Buttons.Notifications'); ?></span>
                                                                </a>
                                                            </li>

                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "access/AccessController"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> <?php echo lang('Buttons.Access'); ?></span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                            <?php } ?>
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
                    <?php //if ($client != 1) { 
                    ?>
                    <li class="d-none d-sm-inline-block">
                        <div class="nav-link waves-effect waves-light" id="light-dark-mode">
                            <i class="ri-moon-line font-16"></i>
                        </div>
                    </li>
                    <?php //} 
                    ?>
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

                    <li class="dropdown d-none d-md-inline-block">
                        <a class="nav-link dropdown-toggle waves-effect waves-light arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="true">
                            <i class="mdi mdi-google-translate"></i> &nbsp; <?php echo session()->get('locale') == 'en' ? 'English' : (session()->get('locale') == 'es' ? 'Español' : (session()->get('locale') == 'kan' ? 'ಕನ್ನಡ' : (session()->get('locale') == 'hi' ? 'हिन्दी' : 'Language'))); ?>&nbsp; <i class="mdi mdi-chevron-down"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated" style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(0.8px, 72px, 0px);" data-popper-placement="bottom-end">
                            <a href="<?php echo base_url('lang/en'); ?>" class="dropdown-item">
                                <span class="align-middle">English </span>
                            </a>
                            <a href="<?php echo base_url('lang/es'); ?>" class="dropdown-item">
                                <span class="align-middle">Español</span>
                            </a>
                            <?php if ($client == 1) { ?>
                                <a href="<?php echo base_url('lang/kan'); ?>" class="dropdown-item">
                                    <span class="align-middle">ಕನ್ನಡ</span>
                                </a>

                                <a href="<?php echo base_url('lang/hi'); ?>" class="dropdown-item">
                                    <span class="align-middle">हिन्दी</span>
                                </a>
                            <?php } ?>
                        </div>
                    </li>

                    <?php if (session()->get('is_impersonating')) {
                    ?>
                        <li class="dropdown notification-list">
                            <form action="<?= base_url('Settings/settings/userImpersonate') ?>" method="POST">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id_user" value="<?= session()->get('org_id_user') ?>">
                                <button class="btn btn-sm btn-default">
                                    <i class="fa fa-random"></i>
                                </button>
                            </form>
                        </li>
                    <?php
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
                            <?php //if ($client == 1): ?>
                                <a href="<?php echo base_url('Certification/Certification_Portal/paymentHistory'); ?>"
                                    class="dropdown-item notify-item">
                                    <i class="fe-clock"></i>
                                    <span><?php echo lang('UI_Text.Payment_History'); ?></span>
                                </a>
                            <?php //endif; ?>
                            <a href="<?php echo base_url('Help/View'); ?>"
                                class="dropdown-item notify-item">
                                <i class="fe-help-circle"></i>
                                <span><?php echo lang('Buttons.Help'); ?></span>
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