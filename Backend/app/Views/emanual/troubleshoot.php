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
</head>

<body class="show">

    <!-- Begin page -->
    <div id="wrapper">

        <!-- ========== Menu ========== -->
        <div style="background-color: #fcfcfc !important;">



            <!-- menu-left -->
            <div class="scrollbar">

                <!-- User box -->
                <!--- Menu -->

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
                        <a href="<?php echo base_url('Emanual/emanual_product/documents'); ?>">
                            <span class="mdi mdi-home-outline font-24"></span>
                        </a>
                        <a href="<?php echo base_url('Emanual/emanual_product/documents') ?>">
                            <h3> <?php echo $product_details[0]['document_name'];
                                    ?></h3>
                        </a>
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
                <div class="container-fluid">

                    <div class="mb-2"></div>
                    <?php if (count($troubleshootName) > 0) { ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <span style="display: inline-block; padding-right: 20px;">
                                            <form class="form-horizontal" action="<?php echo base_url('Emanual/emanual_link/trouble') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="et_id" value="0">
                                                <button type="submit" class="btn btn-outline-primary btn-xs rounded-pill waves-effect waves-light font-14 "><span class="mdi mdi-keyboard-return"></span></button>
                                            </form>
                                        </span>

                                        <span style="display: inline-block;">
                                            <h4><?php echo $troubleshootName[0]['question'];
                                                ?></h4>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="card ">
                                    <div class="card-body">
                                        <div class="text-center">
                                            <h3>Welcome to the Troubleshooting module. Please select the problem.</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            foreach ($major_issues as $major) {
                            ?>
                                <div class="col-md-2 text-center">
                                    <form class="form-horizontal" action="<?php echo base_url('Emanual/emanual_link/trouble') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="et_id" value="<?php echo $major['et_id']; ?>">
                                        <button type="submit" class="btn btn-outline-primary btn-xs rounded-pill waves-effect waves-light "><?php echo $major['question']; ?></button>
                                    </form>
                                </div>
                            <?php
                            }
                            ?>

                        </div>
                    <?php } ?>

                    <!-- end page title -->
                    <?php if (count($trouble_links) > 0) { ?>
                        <div class="row">
                            <p>Possible troubleshooting that can be done:</p>
                            <?php
                            foreach ($trouble_links as $data) {
                            ?>
                                <div class="col-lg-3">
                                    <div class="card bg-pattern">
                                        <div class="card-body">
                                            <div class="text-center">
                                                <h4 class="mb-1 font-20">
                                                    <?php
                                                    echo  $data['question'];
                                                    ?> </h4>
                                            </div>

                                            <p class="font-14 text-center text-muted">
                                                <?php
                                                echo $data['description'];
                                                ?> </p>

                                            <div class="text-center">
                                                <form class="form-horizontal" action="<?php echo base_url('Emanual/emanual_link/trouble') ?>" method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="et_id" value="<?php echo $data['et_id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-light">Troubleshoot More</button>
                                                </form>

                                            </div>

                                            <div class="row mt-4 text-center">
                                                <div class="col-6">
                                                    <h5 class="fw-normal text-muted">Did this solve the issue?</h5>
                                                    <form class="form-horizontal" action="<?php echo base_url('Emanual/emanual_link/trouble') ?>" method="POST"><?= csrf_field() ?>
                                                        <input type="hidden" name="et_id" value="0">
                                                        <button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light">Yes</button>
                                                    </form>
                                                </div>
                                                <div class="col-6">
                                                    <h5 class="fw-normal text-muted">Want to know the procedure?</h5>
                                                    <form class="form-horizontal" action="<?php echo base_url('Emanual/emanual_link/trouble_page') ?>" method="POST"><?= csrf_field() ?>
                                                        <input type="hidden" name="et_id" value="<?php echo $data['et_id']; ?>">
                                                        <button type="submit" class="btn btn-outline-success btn-xs waves-effect waves-light">View</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- end card -->
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    <?php } ?>
                </div>



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