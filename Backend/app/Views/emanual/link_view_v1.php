<!DOCTYPE html>
<html lang="en" data-menu-color="gradient">

<head>
    <meta charset="utf-8" />
    <title>DoCheck</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="TalentQuest" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>public/Landing/images/favicon.ico">

    <!-- plugin css -->
    <link href="<?php echo base_url(); ?>public/creative/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />

    <!-- Theme Config Js -->
    <script src="<?php echo base_url(); ?>public/creative/assets/js/head.js"></script>

    <!-- Bootstrap css -->
    <link href="<?php echo base_url(); ?>public/creative/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="app-style" />

    <!-- App css -->
    <link href="<?php echo base_url(); ?>public/creative/assets/css/app.min.css" rel="stylesheet" type="text/css" />

    <!-- Icons css -->
    <link href="<?php echo base_url(); ?>public/creative/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <style>
        .menu-title-custom {
            color: black;
            margin-bottom: 5px;
            padding: 5px;
            font-size: 16px;
            list-style-type: none;
            border-radius: 5px;
            background-color: rgba(195, 199, 203, 0.27);
        }


        .menu-custom {
            color: black;
            padding: 10px;
            font-size: 16px;
            list-style-type: none;
        }

        .menu-item-custom {
            color: black;
            margin-left: 10px;
            margin-bottom: 5px;
            width: 90%;
            font-size: 14px;
            border-radius: 5px;
            background-color: rgba(195, 199, 203, 0.27);
            list-style-type: none;
            text-decoration: none;

        }



        .menu-text-custom {
            text-decoration: none;
            color: black;
            padding: 5px;
        }
        .menu-text-custom:hover {
            color: #4279ff;
        }
        .menu-text-custom-active {
            text-decoration: none;
            color: red;
            padding: 5px;
        }
    </style>
</head>

<body class="show">

    <!-- Begin page -->
    <div id="wrapper">

        <!-- ========== Menu ========== -->
        <div style="background-color: #fcfcfc !important;">

            <div class="logo-box">
                <!-- Brand Logo Light -->
                <a href="<?php echo base_url('Emanual/emanual_product/documents'); ?>">
                    <span class="mdi mdi-home-outline font-24"></span>
                </a>

            </div>

            <!-- menu-left -->
            <div class="scrollbar" style="width: 200px; color:black">

                <!-- User box -->
                <!--- Menu -->
                <ul class="menu-custom">

                    <li class="btn btn-outline-info btn-xs waves-effect waves-light d-grid mb-1">Engine System</li>

                    <?php
                    $totalPages = count($getAllPages);
                    foreach ($getAllPages as $allpages) {

                        if ($empg_id == $allpages['empg_id']) {
                            echo '<li class="menu-item-custom active">';
                    ?>
                            <a href="<?php echo base_url('Emanual/emanual_link/link_v2/' . $allpages['empg_id']) ?>">
                                <span class="menu-text-custom-active"> <?php echo $allpages['page_name']; ?> </span>
                            </a>

                        <?php
                        } else {
                            echo '<li class="menu-item-custom">';
                        ?>
                            <a href="<?php echo base_url('Emanual/emanual_link/link_v2/' . $allpages['empg_id']) ?>">
                                <span class="menu-text-custom"> <?php echo $allpages['page_name']; ?> </span>
                            </a>
                        <?php
                        }
                        ?>

                    <?php
                        echo '</li>';
                    } ?>
                    <li class="btn btn-outline-info btn-xs waves-effect waves-light d-grid mb-1">Chassis</li>
                    <li class="btn btn-outline-info btn-xs waves-effect waves-light d-grid mb-1">Electrical</li>
                    <li class="btn btn-outline-info btn-xs waves-effect waves-light d-grid mb-1">Instruments</li>
                    <li class="btn btn-outline-info btn-xs waves-effect waves-light d-grid mb-1">Wheels</li>
                </ul>
                <!--- End Menu -->
                <div class="clearfix"></div>
            </div>
        </div>
        <!-- ========== Left menu End ========== -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">

            <!-- ========== Topbar Start ========== -->
            <div class="navbar-custom">
                <div class="topbar">
                    <div class="topbar-menu d-flex align-items-center gap-1">
                        <h3><?php echo $getDocumentDetails[0]['document_name']; ?></h3>

                    </div>

                    <ul class="topbar-menu d-flex align-items-center">
                        <!-- Topbar Search Form -->
                        <li class="app-search dropdown me-3 d-none d-lg-block">
                            <form>
                                <input type="search" class="form-control rounded-pill" placeholder="Search..." id="top-search">
                                <span class="fe-search search-icon font-16"></span>
                            </form>

                        </li>

                        <!-- Fullscreen Button -->
                        <li class="d-none d-md-inline-block">
                            <a class="nav-link waves-effect waves-light" href="" data-toggle="fullscreen">
                                <i class="fe-maximize font-22"></i>
                            </a>
                        </li>

                        <!-- Search Dropdown (for Mobile/Tablet) -->
                        <li class="dropdown d-lg-none">
                            <a class="nav-link dropdown-toggle waves-effect waves-light arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <i class="ri-search-line font-22"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-animated dropdown-lg p-0">
                                <form class="p-3">
                                    <input type="search" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                                </form>
                            </div>
                        </li>

                        <!-- App Dropdown -->


                        <!-- Language flag dropdown  -->
                        <li class="dropdown d-none d-md-inline-block">
                            <a class="nav-link dropdown-toggle waves-effect waves-light arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <img src="<?php echo base_url(); ?>public/creative/assets/images/flags/india.jpg" alt="Language" class="me-0 me-sm-1" height="18">
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated">

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">
                                    <span class="align-middle">Tamil</span>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">
                                    <span class="align-middle">Kannada</span>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">
                                    <span class="align-middle">Hindi</span>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">
                                    <span class="align-middle">Gujarati</span>
                                </a>

                            </div>
                        </li>

                        <!-- Notofication dropdown -->


                        <!-- Light/Dark Mode Toggle Button -->
                        <li class="d-none d-sm-inline-block">
                            <div class="nav-link waves-effect waves-light" id="light-dark-mode">
                                <i class="ri-moon-line font-22"></i>
                            </div>
                        </li>

                        <!-- User Dropdown -->
                        <li class="dropdown">
                            <?php
                            $profile_image =  session('profile_image');
                            $profile_foldername = session('profile_foldername');
                            if (isset($profile_image)) {
                                $profile_link = base_url('assets/assets/uploads/profile/' . session('username') . "/" . $profile_foldername . "/" . $profile_image);
                            } else {
                                $profile_link = base_url('public/aristo_assets/images/User_2_1.svg');
                            } ?>
                            <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <img src="<?php echo  $profile_link; ?>" alt="image" class="rounded-circle "><span class="ms-1 d-none d-md-inline-block">
                                    <?php $name_user = session('name');
                                    echo $name_user; ?> <i class="mdi mdi-chevron-down"></i>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end profile-dropdown ">

                                <!-- item-->
                                <a href="<?php echo base_url('logout') ?>" class="dropdown-item notify-item">
                                    <i class="fe-log-out"></i>
                                    <span>Logout</span>
                                </a>

                            </div>
                        </li>

                    </ul>
                </div>
            </div>
            <!-- ========== Topbar End ========== -->

            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item active"> <?php echo   $page_number . ' / ' . $totalPages; ?>
                                        </li>
                                    </ol>
                                </div>
                                <h4 class="page-title" style="color:#1591EA !important"><?php echo $page_name; ?></h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <div class="row">
                        <?php foreach ($pagecontentdata as $order => $eachpagecontentdata) {
                            $content = (isset($eachpagecontentdata['translate_content1']) && $eachpagecontentdata['translate_content1'] != '') ? $eachpagecontentdata['translate_content1'] : $eachpagecontentdata['content1'];  ?>
                            <?php if ($eachpagecontentdata['type'] == '96') { ?>
                                <?php if ($content != '') { ?>
                                    <div class="col-lg-6 mb-2">
                                        <div class="card">
                                            <div class="card-body">
                                                <img style="max-width: 100%;height: auto;" alt="Responsive image" src="<?php echo base_url() ?>assets/assets/uploads/emanual_image/<?php echo $eachpagecontentdata['page_id'] ?>/<?php echo $content ?>" />
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } elseif ($eachpagecontentdata['type'] == '97') { ?>

                                <?php if ($content != '') { ?>
                                    <div class="col-lg-6 mb-2">
                                        <div class="card">
                                            <div class="card-body">
                                                <video width="100%" controlsList="nodownload" oncontextmenu="return false;" id="videoElement" controls>
                                                    <?php $videoUrl =  base_url('assets/assets/uploads/emanual_video/' . $empg_id . "/" . $content); ?>
                                                    <source src="<?= $videoUrl ?>" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                            <?php } elseif ($eachpagecontentdata['type'] == '88') { ?>

                                <div class="col-lg-12 mb-2">
                                    <h3 style="color:#1591EA !important"><?php echo $content ?></h3>
                                </div>

                            <?php } elseif ($eachpagecontentdata['type'] == '89') { ?>

                                <div class="col-lg-12 mb-2">
                                    <h2 style="color:#1591EA !important"><?php echo $content ?></h2>
                                </div>

                            <?php } elseif ($eachpagecontentdata['type'] == '90') { ?>

                                <div class="col-lg-12 mb-2">
                                    <div class="alert alert-danger" role="alert">
                                        <?php echo $content ?>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="col-lg-6 mb-2">
                                    <div class="card">
                                        <div class="card-body">
                                            <?php echo $content ?>
                                        </div>
                                    </div>
                                </div>

                            <?php } ?>

                        <?php } ?>
                        <!-- ... Your content goes here ... -->

                    </div> <!-- container -->
                </div> <!-- content -->

                <!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div>
                                    <script>
                                        document.write(new Date().getFullYear())
                                    </script> © DoChek
                                </div>
                            </div>

                        </div>
                    </div>
                </footer>
                <!-- end Footer -->

            </div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->

        <!-- Theme Settings -->


        <!-- Vendor js -->
        <script src="<?php echo base_url(); ?>public/creative/assets/js/vendor.min.js"></script>

        <!-- App js -->
        <script src="<?php echo base_url(); ?>public/creative/assets/js/app.min.js"></script>

        <!-- Plugins js-->
        <script src="<?php echo base_url(); ?>public/creative/assets/libs/jquery-sparkline/jquery.sparkline.min.js"></script>
        <script src="<?php echo base_url(); ?>public/creative/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
        <script src="<?php echo base_url(); ?>public/creative/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script>

        <!-- Dashboard 2 init -->
        <script src="<?php echo base_url(); ?>public/creative/assets/js/pages/dashboard-2.init.js"></script>


</body>

</html>