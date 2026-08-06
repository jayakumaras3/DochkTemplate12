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
        .alertwindow {
            position: fixed;
            top: 30%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
        }
    </style>
    <script>
        var element = document.getElementById('my-app');
        if (typeof (element) != 'undefined' && element != null) {
            var milliseconds = 5000;
            setTimeout(function () {
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
                                                <a href="javascript: void(0);" class="dropdown-toggle h5 mb-1 d-block"
                                                    data-bs-toggle="dropdown"><?php $name_user = session('name');
                                                    echo $name_user; ?></a>
                                                <div class="dropdown-menu user-pro-dropdown">

                                                    <a href="<?php echo base_url('User_login/profile'); ?>"
                                                        class="dropdown-item notify-item">
                                                        <i class="fe-user"></i>
                                                        <span>My Account</span>
                                                    </a>
                                                    <a href="<?php echo base_url('Support'); ?>"
                                                        class="dropdown-item notify-item">
                                                        <i class="fe-mail"></i>
                                                        <span>My Support</span>
                                                    </a>


                                                    <a href="<?php echo base_url() . "Support/Support_user/view_notificatoins"; ?>"
                                                        class="dropdown-item notify-item">
                                                        <i class="fe-bell"></i>
                                                        <span>Notifications</span>
                                                    </a>
                                                    <a href="<?php echo base_url() . "my_training/help"; ?>"
                                                        class="dropdown-item notify-item">
                                                        <i class="fe-help-circle"></i>
                                                        <span>FAQs</span>
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <a href="<?php echo base_url('logout') ?>"
                                                        class="dropdown-item notify-item">
                                                        <i class="fe-log-out"></i>
                                                        <span>Logout</span>
                                                    </a>

                                                </div>
                                            </div>

                                        </div>


                                        <!--- Menu -->
                                        <ul class="menu">
                                            <li class="menu-item">
                                                <a href="<?php echo base_url() . "my_training"; ?>" class="menu-link">
                                                    <span class="menu-icon"><i class="fe-grid"></i></span>
                                                    <span class="menu-text"> Dashboard </span>
                                                </a>
                                            </li>
                                            <?php if (in_array('45', $arrayuserlevel)) { ?>
                                                <li class="menu-item">
                                                    <a href="<?php echo base_url() . "SCORM/Scorm_client/reviews"; ?>"
                                                        class="menu-link">
                                                        <span class="menu-icon"><i class="fe-bar-chart"></i></span>
                                                        <span class="menu-text"> Client Dashboard </span>
                                                    </a>
                                                </li>
                                            <?php } ?>

                                            <li class="menu-item">
                                                <a href="<?php echo base_url() . "SCORM/scorm_courses"; ?>"
                                                    class="menu-link">
                                                    <span class="menu-icon"><i class="mdi mdi-youtube-tv"></i></span>
                                                    <span class="menu-text"> My Courses </span>
                                                </a>
                                            </li>

                                            <li class="menu-item">
                                                <a href="<?php echo base_url() . "my_training/course_group"; ?>"
                                                    class="menu-link">
                                                    <span class="menu-icon"><i
                                                            class="mdi mdi-youtube-subscription"></i></span>
                                                    <span class="menu-text"> My Course Groups </span>
                                                </a>
                                            </li>
                                            <li class="menu-item">
                                                <a href="<?php echo base_url() . "marketplace/dashboard"; ?>"
                                                    class="menu-link">
                                                    <span class="menu-icon"><i class="mdi mdi-shopping-outline"></i></span>
                                                    <span class="menu-text"> Marketplace </span>
                                                </a>
                                            </li>
                                            <li class="menu-item">
                                                <a href="<?php echo base_url() . "marketplace/learning_dashboard"; ?>"
                                                    class="menu-link">
                                                    <span class="menu-icon"><i class="mdi mdi-shopping-outline"></i></span>
                                                    <span class="menu-text"> Learning Plan</span>
                                                </a>
                                            </li>
                                            <li class="menu-item">
                                                <a href="<?php echo base_url() . "Game/gamification"; ?>" class="menu-link">
                                                    <span class="menu-icon"><i
                                                            class="mdi mdi-gamepad-variant-outline"></i></span>
                                                    <span class="menu-text"> Gamification </span>
                                                </a>
                                            </li>



                                            <?php if (in_array('44', $arrayuserlevel)) { ?>
                                                <li class="menu-item">
                                                    <a href="#admin" data-bs-toggle="collapse" class="menu-link">
                                                        <span class="menu-icon"><i class="fe-settings"></i></span>
                                                        <span class="menu-text"> Admin </span>
                                                        <span class="menu-arrow"></span>
                                                    </a>
                                                    <div class="collapse" id="admin">
                                                        <ul class="sub-menu">

                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "User_login/client_users"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> Users </span>
                                                                </a>
                                                            </li>

                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "SCORM/Scorm_learn_group"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> Course Groups</span>
                                                                </a>
                                                            </li>

                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "SCORM/Scorm_user_group"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> User Groups</span>
                                                                </a>
                                                            </li>

                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "Support/Support/admin_support"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> Support</span>
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
                                                <li class="menu-title">Team Connect</li>

                                                <li class="menu-item">
                                                    <a href="<?php echo base_url() . "etrack/dashboard"; ?>" class="menu-link">
                                                        <span class="menu-icon"><i class="fe-at-sign"></i></span>
                                                        <span class="menu-text"> e-Track Dashboard </span>
                                                    </a>
                                                </li>
                                                <li class="menu-item">
                                                    <a href="#common" data-bs-toggle="collapse" class="menu-link">
                                                        <span class="menu-icon"><i class="mdi mdi-passport"></i></span>
                                                        <span class="menu-text"> Common </span>
                                                        <span class="menu-arrow"></span>
                                                    </a>
                                                    <div class="collapse" id="common">
                                                        <ul class="sub-menu">
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "etrack/dashboard/org_chart"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text">Org Structure</span>
                                                                </a>
                                                            </li>

                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "etrack/dashboard/holiday_cal"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text">Holidays</span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "etrack/dashboard/policies"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text">Policies</span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "Etrack/claims"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> Claims</span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "Etrack/exit_clearance" ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text">Exit Clearance</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                                <?php if (in_array('7', $arrayuserlevel)) { ?>
                                                    <?php if (session()->get('report_to_you') == 2 || in_array('4', $arrayuserlevel)) { ?>
                                                        <li class="menu-item">
                                                            <a href="#tasks" data-bs-toggle="collapse" class="menu-link">
                                                                <span class="menu-icon"><i class="mdi mdi-alarm-note"></i></span>
                                                                <span class="menu-text"> Tasks </span>
                                                                <span class="menu-arrow"></span>
                                                            </a>
                                                            <div class="collapse" id="tasks">
                                                                <ul class="sub-menu">
                                                                    <li class="menu-item">
                                                                        <a href="<?php echo base_url() . "Task/Task_manage/my_task"; ?>"
                                                                            class="menu-link">
                                                                            <span class="menu-text"> My Tasks</span>
                                                                        </a>
                                                                    </li>

                                                                    <li class="menu-item">
                                                                        <a href="<?php echo base_url() . "Task/Task_manage/resource_planning"; ?>"
                                                                            class="menu-link">
                                                                            <span class="menu-text"> Resource Planning </span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="menu-item">
                                                                        <a href="<?php echo base_url() . "Task/Task_manage/team_tasks"; ?>"
                                                                            class="menu-link">
                                                                            <span class="menu-text"> Team Tasks </span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="menu-item">
                                                                        <a href="<?php echo base_url() . "Task/Task_manage/team_tasks_allocate"; ?>"
                                                                            class="menu-link">
                                                                            <span class="menu-text"> Project Tasks </span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="menu-item">
                                                                        <a href="<?php echo base_url() . "Task/Task_manage/tasks_monthly_report"; ?>"
                                                                            class="menu-link">
                                                                            <span class="menu-text"> Report </span>
                                                                        </a>
                                                                    </li>

                                                                </ul>
                                                            </div>
                                                        </li>
                                                    <?php } else { ?>
                                                        <li class="menu-item">
                                                            <a href="<?php echo base_url() . "Task/Task_manage/my_task"; ?>"
                                                                class="menu-link">
                                                                <span class="menu-icon"><i class="mdi mdi-alarm-note"></i></span>
                                                                <span class="menu-text"> My Tasks </span>
                                                            </a>
                                                        </li>
                                                    <?php } ?>
                                                <?php } ?>



                                                <li class="menu-item">
                                                    <a href="#personal_data" data-bs-toggle="collapse" class="menu-link">
                                                        <span class="menu-icon"><i class="mdi mdi-security"></i></span>
                                                        <span class="menu-text"> Personal Data </span>
                                                        <span class="menu-arrow"></span>
                                                    </a>
                                                    <div class="collapse" id="personal_data">
                                                        <ul class="sub-menu">
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "etrack/employee_details/dependents"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> Dependents</span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "User_login/profile/update_data"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> Data</span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "User_login/profile/documents"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> Documents</span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "etrack/Payroll"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> Payslips </span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "etrack/employee_details/appraisals"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> Appraisals </span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "etrack/employee_details/income_tax"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> Income Tax </span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>

                                                <?php if (in_array('4', $arrayuserlevel)) { ?>
                                                    <li class="menu-item">
                                                        <a href="#projects" data-bs-toggle="collapse" class="menu-link">
                                                            <span class="menu-icon"><i class="mdi mdi-graph-outline"></i></span>
                                                            <span class="menu-text"> Projects </span>
                                                            <span class="menu-arrow"></span>
                                                        </a>
                                                        <div class="collapse" id="projects">
                                                            <ul class="sub-menu">
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "Project_Manage/PM_ucn"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"> My UCN</span>
                                                                    </a>
                                                                </li>
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "Project_Manage/PM_projects"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"> My Projects</span>
                                                                    </a>
                                                                </li>
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "Project_Manage/PM_projects/resource_allocation"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"> Resource Allocation</span>
                                                                    </a>
                                                                </li>
                                                                <!-- <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "Project_Manage/PM_pricing_sheet"; ?>" class="menu-link">
                                                                        <span class="menu-text"> Effort Sheet </span>
                                                                    </a>
                                                                </li>
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "Project_Manage/PM_purchase_order"; ?>" class="menu-link">
                                                                        <span class="menu-text"> Purchase Orders</span>
                                                                    </a>
                                                                </li> -->
                                                                <!-- <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "Project_Manage/milestones"; ?>" class="menu-link">
                                                                        <span class="menu-text"> Milestones </span>
                                                                    </a>
                                                                </li> -->
                                                                <!-- <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "Project_Manage/Invoices"; ?>" class="menu-link">
                                                                        <span class="menu-text"> Invoices </span>
                                                                    </a>
                                                                </li> -->

                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "User_login/client_list/my_client_list"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"> Clients</span>
                                                                    </a>
                                                                </li>
                                                                <?php if (in_array('69', $arrayuserlevel)) { ?>
                                                                    <li class="menu-item">
                                                                        <a href="<?php echo base_url() . "Project_Manage/PM_wip"; ?>"
                                                                            class="menu-link">
                                                                            <span class="menu-text"> WIP Summary</span>
                                                                        </a>
                                                                    </li>
                                                                <?php } ?>
                                                                <!-- <li class="menu-item">
                                                                    <a href="#" class="menu-link">
                                                                        <span class="menu-text"> PM Reports</span>
                                                                    </a>
                                                                </li> -->
                                                            </ul>
                                                        </div>
                                                    </li>
                                                <?php } ?>
                                                <li class="menu-item">
                                                    <a href="#leavemng" data-bs-toggle="collapse" class="menu-link">
                                                        <span class="menu-icon"><i class="mdi mdi-beach"></i></span>
                                                        <span class="menu-text"> Leaves </span>
                                                        <span class="menu-arrow"></span>
                                                    </a>
                                                    <div class="collapse" id="leavemng">
                                                        <ul class="sub-menu">
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "etrack/leaves"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> Leave Dashboard</span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "etrack/leaves/statement"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> My Leave Statement</span>
                                                                </a>
                                                            </li>
                                                            <?php if (session()->get('report_to_you') == 2) { ?>
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "etrack/leaves/team_leaves"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"> Team Leaves</span>
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                </li>
                                                <li class="menu-item">
                                                    <a href="#attendancemng" data-bs-toggle="collapse" class="menu-link">
                                                        <span class="menu-icon"><i class="mdi mdi-access-point"></i></span>
                                                        <span class="menu-text"> Attendance </span>
                                                        <span class="menu-arrow"></span>
                                                    </a>
                                                    <div class="collapse" id="attendancemng">
                                                        <ul class="sub-menu">
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "etrack/attendance/view/1"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> Attendance Dashboard</span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "etrack/attendance/wfh_statement"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> WFH Statement</span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "etrack/attendance/access_card_data"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> Access Card Data</span>
                                                                </a>
                                                            </li>
                                                            <?php if (session()->get('report_to_you') == 2) { ?>
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "etrack/attendance/team_attendance"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"> Team Attendance</span>
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                            <?php if (in_array('2030', $arrayuserlevel)) { ?>
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "etrack/Attendance_admin"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"> Admin Attendance</span>
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                </li>
                                                <?php if (in_array('2010', $arrayuserlevel)) { ?>
                                                    <li class="menu-item">
                                                        <a href="#hr" data-bs-toggle="collapse" class="menu-link">
                                                            <span class="menu-icon"><i class="mdi mdi-handshake-outline"></i></span>
                                                            <span class="menu-text"> HR </span>
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
                                                                        <span class="menu-text"> HR Leaves</span>
                                                                    </a>
                                                                </li>
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "etrack/HR_admin"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"> HR Attendance</span>
                                                                    </a>
                                                                </li>
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "etrack/HR_admin/personal"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"> HR Personal Data</span>
                                                                    </a>
                                                                </li>

                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "Holiday/holidays"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"> Holiday Admin</span>
                                                                    </a>
                                                                </li>
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "Others/Tournaments"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"> Tournaments</span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </li>
                                                <?php } ?>

                                                <?php if (in_array('3014', $arrayuserlevel)) { ?>
                                                    <li class="menu-item">
                                                        <a href="#finance" data-bs-toggle="collapse" class="menu-link">
                                                            <span class="menu-icon"><i class="mdi mdi-currency-inr"></i></span>
                                                            <span class="menu-text"> Finance </span>
                                                            <span class="menu-arrow"></span>
                                                        </a>
                                                        <div class="collapse" id="finance">
                                                            <ul class="sub-menu">
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "etrack/Fin_admin"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text">Finance Dashboard</span>
                                                                    </a>
                                                                </li>
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "etrack/Fin_admin/WIP_Summary"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text">WIP Summary</span>
                                                                    </a>
                                                                </li>
                                                                <li class="menu-item">
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
                                                                    <a href="<?php echo base_url() . "etrack/Fin_admin/vendors"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text">Vendors</span>
                                                                    </a>
                                                                </li>
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "etrack/Fin_admin/claim_admin"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text">Claim Management</span>
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

                                                                <?php if (in_array('3048', $arrayuserlevel)) { ?>
                                                                    <li class="menu-item">
                                                                        <a href="<?php echo base_url() . "etrack/Fin_admin/approve_grace"; ?>"
                                                                            class="menu-link">
                                                                            <span class="menu-text"> Approve Grace</span>
                                                                        </a>
                                                                    </li>
                                                                <?php } ?>


                                                            </ul>
                                                        </div>
                                                    </li>
                                                <?php } ?>
                                                <?php if (in_array('2015', $arrayuserlevel)) { ?>
                                                    <li class="menu-item">
                                                        <a href="#atsmng" data-bs-toggle="collapse" class="menu-link">
                                                            <span class="menu-icon"><i class="fe-activity"></i></span>
                                                            <span class="menu-text"> ATS </span>
                                                            <span class="menu-arrow"></span>
                                                        </a>
                                                        <div class="collapse" id="atsmng">
                                                            <ul class="sub-menu">
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "etrack/ATS"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"> ATS Dashboard</span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </li>
                                                <?php } ?>

                                                <li class="menu-item">
                                                    <a href="#itsupport" data-bs-toggle="collapse" class="menu-link">
                                                        <span class="menu-icon"><i class="mdi mdi-hammer-wrench"></i></span>
                                                        <span class="menu-text"> IT Support </span>
                                                        <span class="menu-arrow"></span>
                                                    </a>
                                                    <div class="collapse" id="itsupport">
                                                        <ul class="sub-menu">
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "etrack/ITSupport"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> IT Dashboard</span>
                                                                </a>
                                                            </li>
                                                            <?php if (in_array('4154', $arrayuserlevel)) { ?>
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "etrack/ITSupport/support_admin"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"> IT Support Admin</span>
                                                                    </a>
                                                                </li>
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "etrack/ITSupport/assets"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"> IT Assets</span>
                                                                    </a>
                                                                </li>
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "etrack/ITSupport/softwares"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"> IT Softwares</span>
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                </li>
                                            <?php } ?>
                                            <?php if (in_array('1005', $arrayuserlevel) || in_array('1010', $arrayuserlevel) || session()->get('client') == 1 || session()->get('client') == '25') { ?>

                                                <li class="menu-item">
                                                    <a href="#others" data-bs-toggle="collapse" class="menu-link">
                                                        <span class="menu-icon"><i class="mdi mdi-auto-fix"></i></span>
                                                        <span class="menu-text"> Others </span>
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
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "Emanual/dashboard"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"> e-Manual Dashboard </span>
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                            <?php if (in_array('1010', $arrayuserlevel)) { ?>
                                                                <li class="menu-item">
                                                                    <a href="<?php echo base_url() . "Emanual/emanual_product"; ?>"
                                                                        class="menu-link">
                                                                        <span class="menu-text"> e-Manual Editor </span>
                                                                    </a>
                                                                </li>
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
                                                                        <span class="menu-text"> OJTS Dashboard</span>
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                </li>
                                            <?php } ?>
                                            <?php if (in_array('6', $arrayuserlevel)) { ?>
                                                <li class="menu-item">
                                                    <a href="#super" data-bs-toggle="collapse" class="menu-link">
                                                        <span class="menu-icon"><i class="mdi mdi-chess-king"></i></span>
                                                        <span class="menu-text"> Super </span>
                                                        <span class="menu-arrow"></span>
                                                    </a>
                                                    <div class="collapse" id="super">
                                                        <ul class="sub-menu">
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "category/dashboard"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> Categories</span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "User_login/client_list"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> Clients</span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "User_login/partners/partner_list"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> Partners</span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "Support/Support_user/notificatoins"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> Notifications</span>
                                                                </a>
                                                            </li>

                                                            <li class="menu-item">
                                                                <a href="<?php echo base_url() . "holiday/holidays/access"; ?>"
                                                                    class="menu-link">
                                                                    <span class="menu-text"> Access</span>
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
                                            placeholder="Search..." id="top-search">
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

                            <li class="dropdown notification-list">
                                <form action="<?php echo base_url('social/post'); ?>" method="post" style="display:inline;">
                                    <input type="hidden" name="course_id" value="0">
                                    <button type="submit" class="menu-link"
                                        style="border:none;background:none;cursor:pointer;" title="Discussion">
                                        <span class="menu-icon"><i class="fe-rss"></i></span>

                                    </button>
                                </form>

                            </li>
                            <span class="badge bg-pink mt-1 float-end"><?php echo session()->get('userpostCount') ?></span>

                            <li class="dropdown notification-list">
                                <form action="<?php echo base_url('Reports/User_report'); ?>" method="POST"><?= csrf_field() ?>
                                    <button type="submit" class="menu-link"
                                        style="border:none;background:none;cursor:pointer;" title="My Report">
                                        <span class="menu-icon"><i class="fe-bar-chart"></i></span>

                                    </button>
                                </form>

                            </li>
                            <?php if (session()->get('client') == 1) { ?>
                                <?php if (in_array('8', $arrayuserlevel)) { ?>
                                    <li class="dropdown notification-list">
                                        <form action="<?php echo base_url('demo/Demo_dashboard'); ?>" method="POST"><?= csrf_field() ?>
                                            <button type="submit" class="menu-link"
                                                style="border:none;background:none;cursor:pointer;" title="Demos">
                                                <span class="menu-icon"><i class="fe-camera"></i></span>

                                            </button>
                                        </form>
                                    </li>
                                <?php } ?>
                            <?php } ?>
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
                                        <span>My Account</span>
                                    </a>
                                    <a href="<?php echo base_url('Support/Support_user'); ?>"
                                        class="dropdown-item notify-item">
                                        <i class="fe-mail"></i>
                                        <span>My Support</span>
                                    </a>


                                    <a href="<?php echo base_url() . "Support/Support_user/view_notificatoins"; ?>"
                                        class="dropdown-item notify-item">
                                        <i class="fe-bell"></i>
                                        <span>Notifications</span>
                                    </a>
                                    <a href="<?php echo base_url() . "my_training/help"; ?>"
                                        class="dropdown-item notify-item">
                                        <i class="fe-help-circle"></i>
                                        <span>FAQs</span>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a href="<?php echo base_url('logout') ?>" class="dropdown-item notify-item">
                                        <i class="fe-log-out"></i>
                                        <span>Logout</span>
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
                    <div class="row mt-2">
                        <?php if (session('success')): ?>
                            <div class="card">
                                <div class="card-header bg-success py-3 text-white">
                                    <div class="card-widgets">

                                        <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
                                    </div>
                                    <h5 class="card-title mb-0 text-white"><?= session('success') ?></h5>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (session('error')): ?>
                            <div class="card">
                                <div class="card-header bg-danger py-3 text-white">
                                    <div class="card-widgets">

                                        <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
                                    </div>
                                    <h5 class="card-title mb-0 text-white"><?= session('error') ?></h5>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>