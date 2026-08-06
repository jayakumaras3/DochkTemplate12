<?php
$userlevel = session()->get('userlevel');
$arrayuserlevel  = array_map('intval', explode(',', $userlevel));
$taskcount = session()->get('totaltaskcount');

$accessmenu = session()->get('accessmenu');
$arrayaccessmenu  = array_map('intval', explode(',', $accessmenu));

$profile_image = session()->get('profile_image');
$profile_foldername =  session()->get('profile_foldername');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="newtheme/image/ico" href="<?php echo base_url(); ?>/public/img/favicon.ico" />
    <title><?php echo lang('UI_Text.Title') ?></title>
    <div class="container">
        <?php $this->renderSection('main_content') ?>
    </div>
    <!-- Bootstrap -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- Select2 -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <!-- bootstrap-datetimepicker -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
    <!-- Bootstrap Colorpicker -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="<?php echo base_url(); ?>/public/css/build/css/custom.min.css" rel="stylesheet">

    <link href="cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">

    <link href="<?php echo base_url(); ?>/public/css/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/public/css/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/public/css/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/public/css/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/public/css/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

    <link href="<?php echo base_url(); ?>/public/css/stylesheets.css" rel="stylesheet" type="text/css" />

    <script type='text/javascript' src="<?php echo base_url(); ?>/public/assets/ckeditor/ckeditor.js"></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/js/plugins/jquery/jquery.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/js/plugins/jquery/jquery-ui.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/js/plugins/jquery/jquery-migrate.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/js/plugins/jquery/globalize.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/js/plugins/uniform/jquery.uniform.min.js'></script>

    <script type='text/javascript' src='<?php echo base_url(); ?>/public/js/plugins/knob/jquery.knob.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/js/plugins/sparkline/jquery.sparkline.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/js/plugins/flot/jquery.flot.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/js/plugins/flot/jquery.flot.resize.js'></script>

    <script type='text/javascript' src='<?php echo base_url(); ?>/public/js/plugins.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/js/actions.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/js/charts.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/js/datatables/jquery.dataTables.min.js'></script>

    <script type='text/javascript' src='<?php echo base_url(); ?>/public/newtheme/js/plugins/tinymce/tinymce.min.js'></script>
    <script src="<?php echo base_url(); ?>/public/plugins/daterangepicker/daterangepicker.js"></script>
    <link type="text/css" href="<?php echo base_url(); ?>/public/newtheme/js/plugins/dropzone.min/dropzone.min.css" rel="stylesheet" />
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/newtheme/js/plugins/dropzone.min/dropzone.min.js'></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>/public/plugins/daterangepicker/daterangepicker.css">

    <script type='text/javascript' src='<?php echo base_url(); ?>/public/newtheme/js/plugins/uniform/jquery.uniform.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/newtheme/js/plugins/flot/jquery.flot.pie.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/newtheme/js/plugins/fancybox/jquery.fancybox.pack.js'></script>

    <!-- <script type='text/javascript' src='https://code.jquery.com/jquery-3.5.1.js' ></script> -->
    <script type='text/javascript' src='https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js'></script>
    <script type='text/javascript' src='https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js'></script>
</head>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="<?php echo base_url('my_training') ?>" class="site_title"><img style="padding-bottom: 10px; " src="<?php echo base_url(); ?>/public/img/small_logo.png" /><span style="font-size: 23px;"> DoChek</span></a>

                    </div>
                    <div class="clearfix"></div>
                    <!-- menu profile quick info -->
                    <!--  <div class="profile clearfix">
                        <div class="profile_pic">
                            <?php // if (!empty($profile_image)) { 
                            ?>
                                <a href="<?php //echo base_url('profile') 
                                            ?>"> <img src="<?php //echo base_url() 
                                                            ?>/assets/assets/uploads/profile/<?php //echo session()->get('username') 
                                                                                        ?>/<?php //echo $profile_foldername 
                                                                                            ?>/<?php //echo $profile_image 
                                                                                                ?>" class="img-circle profile_img" /></a>
                            <?php // } else { 
                            ?>
                                <a href="<?php //echo base_url('profile') 
                                            ?>"><img src="<?php //echo base_url() . '/assets/profile_default.png' 
                                                            ?>" class="img-circle profile_img" /></a>
                            <?php // } 
                            ?>
                        </div>
                        <div class="profile_info">
                            <span>Welcome,</span>
                            <h2><?php //echo session()->get('name') 
                                ?></h2>
                        </div>
                    </div> -->
                    <!-- /menu profile quick info -->

                    <br />
                    <?php //print_r($arrayuserlevel);
                    if (session()->get('isLoggedIn') && session()->get('client')) : ?>
                        <!-- sidebar menu -->
                        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                            <div class="menu_section">
                                <ul class="nav side-menu">
                                    <li>
                                        <a href="<?php echo base_url('my_training') ?>"><i class="fa fa-home"></i>Dashboard</a>
                                    </li>
                                    <li> <a><i class="fa fa-dashboard"></i>My<span class="fa fa-chevron-down"></span></a>
                                        <ul class="nav child_menu">
                                            <!-- <li><a href="<?php echo base_url('my_training') ?>">Dashboard</a></li> -->
                                            <li><a href="<?php echo base_url('profile') ?>">Profile</a></li>
                                            <?php if (in_array('3', $arrayaccessmenu)) {
                                                $client = session()->get('client') ?>
                                                <li><a href="<?php echo base_url('tasks') ?>">Tasks</a></li>
                                                <?php if (in_array('1', $arrayuserlevel)) { ?>
                                                    <li><a href="<?php echo base_url('general_meeting_agenda') ?>">Meeting</a></li>
                                            <?php }
                                            } ?>
                                            <li><a href="<?php echo base_url('my_training/report') ?>">Report</a></li>
                                            <li><a href="<?php echo base_url('Support/support') ?>">Support</a></li>
                                        </ul>
                                    </li>
                                    <?php if (in_array('2', $arrayaccessmenu)) { // client level menu access 
                                    ?>
                                        <li>
                                            <a href="<?php echo base_url('holidays') ?>"><i class="fa fa-calendar"></i>Holidays</a>
                                        </li>
                                    <?php } ?>
                                    <?php if (in_array('3', $arrayaccessmenu)) { //client level menu access  
                                    ?>
                                        <?php if (
                                            in_array('4', $arrayuserlevel) ||  in_array('79', $arrayuserlevel) || in_array('81', $arrayuserlevel) || in_array('83', $arrayuserlevel) || in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel)
                                        ) {  // user level access 
                                        ?>
                                            <li> <a><i class="fa fa-paper-plane"></i>Sales<span class="fa fa-chevron-down"></span></a>
                                                <ul class="nav child_menu">
                                                    <li><a href="<?php echo base_url('Demo/cart/addToCart/0') ?>">Cart</a></li>
                                                    <li><a href="<?php echo base_url('Demo/cart/report') ?>">Report</a></li>
                                                    <!-- <li><a href="#">Analytics</a></li> -->
                                                </ul>
                                            </li>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php if (in_array('3', $arrayaccessmenu)) { //client level menu access  
                                    ?>
                                        <?php if (
                                            in_array('4', $arrayuserlevel) ||
                                            in_array('7', $arrayuserlevel) ||
                                            in_array('8', $arrayuserlevel) ||
                                            in_array('43', $arrayuserlevel)
                                        ) {  // user level access 
                                        ?>
                                            <li> <a><i class="fa fa-bar-chart"></i>Projects<span class="fa fa-chevron-down"></span></a>
                                                <ul class="nav child_menu">
                                                    <li><a href="<?php echo base_url('Project/dashboard') ?>">Project Dashboard</a></li>
                                                    <li><a href="<?php echo base_url('Project/projects') ?>">Projects</a></li>
                                                    <?php if (in_array('4', $arrayuserlevel)) {  // user level access 
                                                    ?>
                                                        <li><a href="#">UCN</a></li>
                                                        <li><a href="#">Effort Sheet</a></li>
                                                        <li><a href="#">Purchase Order</a></li>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                        <?php } ?>
                                    <?php } ?>


                                    <!--                                             <li> <a><i class="fa fa-desktop"></i>Demos<span class="fa fa-chevron-down"></span></a>
                                                <ul class="nav child_menu">
                                                    <li><a href="<?php echo base_url('demos/view_category?searchval=2') ?>">Demo Dashboard</a></li>
                                                    <li><a href="<?php echo base_url('demo_master') ?>">Create New Demo</a></li>
                                                    <li><a href="<?php echo base_url('demos/demo_categories') ?>">Demo Categories</a></li>
                                                    <li><a href="<?php echo base_url('demos/featured?cat=16&tag=141') ?>">TQ Featured</a></li>
                                                    <li><a href="<?php echo base_url('demos/report') ?>">Report</a></li>
                                                </ul>
                                            </li> -->


                                    <?php if (in_array('4', $arrayaccessmenu)) { // client level menu access    
                                    ?>
                                        <?php if (in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel) || in_array('67', $arrayuserlevel) || in_array('46', $arrayuserlevel)) { // user level access 
                                        ?>
                                            <?php if (in_array('67', $arrayuserlevel) || in_array('46', $arrayuserlevel)) { ?>
                                                <li> <a><i class="fa fa-desktop"></i>Demo<span class="fa fa-chevron-down"></span></a>
                                                    <ul class="nav child_menu">
                                                        <li><a href="<?php echo base_url('Demo/demo_dashboard') ?>">Demo Dashboard</a></li>

                                                        <li><a href="<?php echo base_url('Demo/demo_client') ?>">Demo Clients</a></li>
                                                        <li><a href="<?php echo base_url('Demo/demo_courses') ?>">Demo Courses</a></li>
                                                        <li><a href="<?php echo base_url('Demo/demo_meta_category') ?>">Demo Meta Data</a></li>
                                                        <li><a href="<?php echo base_url('Demo/demo_meta_category/category') ?>">Demo Category</a></li>
                                                        <li><a href="<?php echo base_url('Demo/demo_course_group') ?>">Demo Course Group</a></li>

                                                    </ul>
                                                </li>
                                            <?php } else { ?>
                                                <li>
                                                    <a href="<?php echo base_url('Demo/demo_dashboard') ?>"><i class="fa fa-desktop"></i>Demo Dashboard</a>
                                                </li>
                                            <?php  } ?>
                                    <?php }
                                    } ?>
                                    <?php if (in_array('5', $arrayaccessmenu)) { // client level menu access    
                                    ?>
                                        <?php if (in_array('73', $arrayuserlevel) || in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel)) { ?>
                                            <?php if (in_array('73', $arrayuserlevel)) { ?>
                                                <li> <a><i class="fa fa-graduation-cap"></i>C4U<span class="fa fa-chevron-down"></span></a>
                                                    <ul class="nav child_menu">
                                                        <li><a href="<?php echo base_url('SCORM/scorm_dashboard') ?>">C4U Dashboard</a></li>
                                                        <li><a href="<?php echo base_url('SCORM/scorm_client') ?>">C4U Clients</a></li>
                                                        <li><a href="<?php echo base_url('SCORM/scorm_courses') ?>">C4U Courses</a></li>
                                                        <li><a href="<?php echo base_url('SCORM/scorm_meta_category') ?>">C4U Meta Data</a></li>
                                                        <li><a href="<?php echo base_url('SCORM/scorm_meta_category/category') ?>">C4U Category</a></li>
                                                        <li><a href="<?php echo base_url('SCORM/scorm_course_group') ?>">C4U Course Group</a></li>
                                                        <li><a href="<?php echo base_url('SCORM/scorm_course_download') ?>">C4U Download</a></li>
                                                    </ul>
                                                </li>
                                            <?php } else { ?>
                                                <li>
                                                    <a href="<?php echo base_url('SCORM/scorm_dashboard') ?>"><i class="fa fa-graduation-cap"></i>C4U Dashboard</a>
                                                </li>
                                            <?php  } ?>
                                    <?php }
                                    } ?>
                                    <?php if (in_array('6', $arrayaccessmenu)) { // client level menu access    
                                    ?>
                                        <?php if (in_array('6', $arrayuserlevel) || in_array('80', $arrayuserlevel) || in_array('81', $arrayuserlevel)) { // 81 : Aristo - Normal  
                                        ?>
                                            <li> <a><i class="fa fa-university"></i>Aristo<span class="fa fa-chevron-down"></span></a>
                                                <ul class="nav child_menu">
                                                    <li><a href="#">Aristo Dashboard</a></li>
                                                    <?php if (in_array('6', $arrayuserlevel) || in_array('80', $arrayuserlevel)) { // 80 : Aristo - Admin 
                                                    ?>
                                                        <li><a href="#">Aristo Clients</a></li>
                                                        <li><a href="#">Aristo Courses</a></li>
                                                        <li><a href="#">Aristo Meta Data</a></li>
                                                        <li><a href="#">Aristo Category</a></li>
                                                        <li><a href="#">Aristo Course Group</a></li>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                    <?php }
                                    } ?>
                                    <?php if (in_array('7', $arrayaccessmenu)) { // client level menu access    
                                    ?>
                                        <?php if (in_array('6', $arrayuserlevel) || in_array('82', $arrayuserlevel) || in_array('83', $arrayuserlevel)) {  // 83 : e-Manual - Normal  
                                        ?>
                                            <li> <a><i class="fa fa-book"></i>e-Manual<span class="fa fa-chevron-down"></span></a>
                                                <ul class="nav child_menu">
                                                    <?php if (in_array('6', $arrayuserlevel) || in_array('82', $arrayuserlevel)) {  // 82 : e-Manual - Admin  
                                                    ?>
                                                        <li><a href="#">e-Manual Clients</a></li>
                                                        <li><a href="<?php echo base_url('Emanual/emanual_product'); ?>">e-Manual Products</a></li>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                    <?php }
                                    } ?>
                                    <?php if (in_array('8', $arrayaccessmenu)) { // client level menu access    
                                    ?>
                                        <?php if (in_array('6', $arrayuserlevel) || in_array('84', $arrayuserlevel) || in_array('85', $arrayuserlevel)) { // 84 :xAPI - Admin , 85 : xAPI - Normal  
                                        ?>
                                            <li><a><i class="fa fa-exchange"></i>AR/VR/xAPI<span class="fa fa-chevron-down"></span></a>
                                                <ul class="nav child_menu">
                                                    <?php if (in_array('84', $arrayuserlevel)) { ?>
                                                        <li><a href="<?php echo base_url('XAPI/XAPI_client') ?>">xAPI Clients</a></li>
                                                        <li><a href="<?php echo base_url('XAPI/XAPI_courses') ?>">xAPI Courses</a></li>
                                                        <li><a href="<?php echo base_url('XAPI/XAPI_meta_category/category') ?>">xAPI Category</a></li>
                                                        <li><a href="<?php echo base_url('XAPI/verbs/admin_verbs') ?>">xAPI Verbs</a></li>
                                                    <?php } ?>
                                                </ul>
                                        <?php }
                                    } ?>
                                        <?php if (in_array('8', $arrayaccessmenu)) { // client level menu access    
                                        ?>
                                            <?php if (in_array('6', $arrayuserlevel) || in_array('98', $arrayuserlevel) || in_array('99', $arrayuserlevel)) { // 98 : Assessment - Admin , 99 : Assessment - Normal  
                                            ?>
                                            <li><a><i class="fa fa-gamepad"></i>Assessment<span class="fa fa-chevron-down"></span></a>
                                                <ul class="nav child_menu">
                                                    <?php if (in_array('84', $arrayuserlevel)) { ?>
                                                        <li><a href="#">Assessment</a></li>
                                                        <li><a href="#">Bank</a></li>
                                                        <li><a href="#">Templates</a></li>
                                                    <?php } ?>
                                                </ul>
                                        <?php }
                                        } ?>
                                        <?php if (in_array('44', $arrayuserlevel) || in_array('86', $arrayuserlevel) || in_array('87', $arrayuserlevel)) {
                                        ?>
                                            <?php if (in_array('9', $arrayaccessmenu)) { //  86: Manage - Admin , 87: Manage - Normal 
                                            ?>
                                            <li> <a><i class="fa fa-keyboard-o"></i>Manage<span class="fa fa-chevron-down"></span></a>
                                                <ul class="nav child_menu">
                                                    <li><a href="<?php echo base_url('User_login/client_users') ?>">Users</a></li>
                                                    <li><a href="<?php echo base_url('User_login/client_users/manageTrainings') ?>">Trainings</a></li>
                                                </ul>
                                            </li>
                                        <?php
                                            } ?>
                                    <?php } ?>
                                    <?php 
                                    // print_r($arrayuserlevel);
                                    if (in_array('6', $arrayuserlevel)) { ?>
                                        <?php if (in_array('10', $arrayaccessmenu)) { // client level menu access   
                                        ?>
                                            <li> <a><i class="fa fa-gear"></i>Admin<span class="fa fa-chevron-down"></span></a>
                                                <ul class="nav child_menu">

                                                    <li><a href="<?php echo base_url('Support/support/admin_support'); ?>">Support</a></li>
                                                    <li><a href="<?php echo base_url('User_login/client_list') ?>">Clients</a></li>
                                                    <li><a href="<?php echo base_url('dropdown') ?>">Dropdown Manager</a></li>

                                                </ul>
                                            </li>
                                        <?php
                                        } ?>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        <?php
                        $client_permission = session()->get('client_permission');
                        $array = explode(', ', $client_permission);
                        $client =  session()->get('client');
                        $arraystakeholders  = explode(',', $client);
                        ?>
                </div>
            </div>
        <?php else : ?>
        <?php endif; ?>
        <!-- top navigation -->
        <div class="top_nav">
            <div class="nav_menu">
                <div class="nav toggle">
                    <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                </div>
                <nav class="nav navbar-nav">
                    <ul class=" navbar-right">
                        <li class="nav-item dropdown open">
                            <?php if (!empty($profile_image)) { ?>
                                <a href="<?php echo base_url('profile') ?>"> <img style="width:32px;height:32px;" src="<?php echo base_url() ?>/assets/assets/uploads/profile/<?php echo session()->get('username') ?>/<?php echo $profile_foldername ?>/<?php echo $profile_image ?>" class="img-circle" /></a>
                            <?php } else { ?>
                                <a href="<?php echo base_url('profile') ?>"><img style="width:32px;" src="<?php echo base_url() . '/assets/profile_default.png' ?>" class="img-circle" /></a>
                                <?php } ?><?php echo session()->get('name') ?>
                                <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                                </a>
                                <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="<?php echo base_url('profile') ?>"> Profile</a>
                                    <?php if (in_array('3', $arrayaccessmenu)) {
                                        $client = session()->get('client') ?>
                                        <a class="dropdown-item" href="<?php echo base_url('tasks') ?>">
                                            <span class="badge bg-red pull-right"><?php echo $taskcount ?></span>
                                            <span>Task</span>
                                        </a>
                                    <?php } ?>
                                    <a class="dropdown-item" href="<?php echo base_url('logout') ?>"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                                </div>
                        </li>

                    </ul>
                </nav>
            </div>
        </div>
        <div class="right_col" role="main">
            <div class="">