<!-- home start -->
<section class="bg-home bg-gradient" id="home">
    <div class="home-center">
        <div class="home-desc-center">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="home-title mo-mb-20">
                            <h1 class="mb-4 text-white"><b>DOCHEK</b> - an essential tool for Learning Management</h1>
                            <p class="text-white-50 home-desc mb-5">Welcome to DoChek, the ultimate solution for modern
                                businesses seeking to enhance their productivity and collaboration. Our robust platform
                                combines essential features such as Project Management, Online Review, Learning
                                Management Systems, and cutting-edge AR/VR tracking to streamline your processes and
                                drive success. </p>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card bg-pattern">

                            <div class="card-body p-4">
                                <?php if (session()->get('success')): ?>
                                    <div class="alert alert-success" role="alert" style="font-size:15px">
                                        <?= session()->get('success') ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (session()->get('error')): ?>
                                    <div class="alert alert-danger" role="alert" style="font-size:15px">
                                        <?= session()->get('error') ?>
                                    </div>
                                <?php endif; ?>


                                <?php if ($landing_type == 1) { ?>
                                    <?php if ($username != 'notvalid') { ?>
                                        <form autocomplete="off" action="<?php echo base_url('quickaccess/checkAccess'); ?>"
                                            method="POST"><?= csrf_field() ?>
                                            <div class="auth-brand">
                                                <a href="<?php echo base_url(''); ?>" class="logo logo-light text-center">
                                                    <span class="logo-lg">
                                                        <h3>Quick Access</h3>
                                                        <br>
                                                    </span>
                                                </a>
                                            </div>

                                            <div class="mb-3">
                                                <input type="hidden" class="form-control" autocomplete="off" name="username"
                                                    id="username" placeholder="Username" value="<?php echo $username; ?>">
                                                <input type="hidden" name="demoid" value="<?php echo $demoid; ?>">
                                            </div>
                                            <div class="mb-3">
                                                <input type="password" class="form-control" autocomplete="off" name="password"
                                                    id="password" placeholder="Password" value="" required>
                                            </div>
                                            <div class="mb-3">
                                                <button type="submit"
                                                    class="btn btn-default btn-primary btn-sm">Login</button>
                                            </div>
                                        </form>
                                    <?php } ?>
                                    <?php if (isset($quickvalidation)): ?>
                                        <div>
                                            <div class="alert alert-danger" role="alert">
                                                <?= $quickvalidation->listErrors() ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php } elseif ($landing_type == 2) { ?>
                                    <form autocomplete="off" class="form"
                                        action="<?php echo base_url('forgot_password'); ?>" method="POST"><?= csrf_field() ?>
                                        <div class="auth-brand">
                                            <a href="<?php echo base_url(''); ?>" class="logo logo-light text-center">
                                                <span class="logo-lg">
                                                    <h3>Forgot Password</h3>
                                                    <br>
                                                </span>
                                            </a>
                                        </div>
                                        <div class="mb-3">
                                            <input type="text" class="form-control" name="email"
                                                placeholder="Enter your email" value="<?= set_value('email') ?>">
                                        </div>
                                        <div>
                                            <?php if (isset($validation)): ?>
                                                <div>
                                                    <div class="alert alert-danger" role="alert">
                                                        <?= $validation->listErrors() ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="mb-3">
                                            <button type="submit" class="form-control btn btn-sm btn-warning">Request
                                                Password Reset</button>
                                        </div>
                                        <div class="mb-3">
                                            <a href="<?php echo base_url('/'); ?>" class="text-muted float-end"><small>Back
                                                    to Sign in</small></a>
                                        </div>

                                    </form>
                                <?php } elseif ($landing_type == 3) { ?>
                                    <?php if (isset($error)): ?>
                                        <div class="alert alert-danger" style="font-size:15px"><?= $error; ?></div>
                                    <?php else: ?>
                                        <form autocomplete="off" method="POST"><?= csrf_field() ?>
                                            <div class="auth-brand">
                                                <a href="<?php echo base_url(''); ?>" class="logo logo-light text-center">
                                                    <span class="logo-lg">
                                                        <h3>Change Password</h3>
                                                        <br>
                                                    </span>
                                                </a>
                                            </div>

                                            <div class="mb-3">
                                                <input type="password" class="form-control" name="password"
                                                    placeholder="New Password" value="">
                                            </div>
                                            <div class="mb-3">
                                                <input type="password" class="form-control" name="password_confirm"
                                                    placeholder="Confirm Password" value="">
                                            </div>
                                            <div>
                                                <?php if (isset($validation)): ?>
                                                    <div>
                                                        <div class="alert alert-danger" role="alert">
                                                            <?= $validation->listErrors() ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="mb-3">
                                                <button type="submit"
                                                    class="btn btn-sm btn-warning form-control">Submit</button>
                                            </div>
                                        </form>
                                    <?php endif ?>
                                    <div class="mb-3">
                                        <a href="<?php echo base_url('/'); ?>" class="text-muted float-end"><small>Back to
                                                Sign in</small></a>
                                    </div>
                                <?php } else { ?>
                                    <?php if (session()->get('login_source') == 'ci') { ?>
                                        <form autocomplete="off"
                                            action="<?php echo base_url('Landing_dochek/login_register'); ?>" method="POST"><?= csrf_field() ?>
                                        <?php } else { ?>
                                            <form autocomplete="off" action="<?php echo base_url('landing/login_register'); ?>"
                                                method="POST"><?= csrf_field() ?></form>
                                        <?php } ?>
                                        <?= csrf_field() ?>
                                        <div class="mb-3">
                                            <input class="form-control" id="username" autocomplete="off" name="username"
                                                required="" placeholder="Enter your email"
                                                value="<?= set_value('username') ?>">
                                        </div>
                                        <div class="mb-3">

                                            <div class="input-group input-group-merge">
                                                <input type="password" id="password" autocomplete="off" name="password"
                                                    class="form-control" placeholder="Enter your password">
                                            </div>
                                        </div>
                                        <?php if (isset($validation)): ?>
                                            <div>
                                                <div class="alert alert-danger" role="alert">
                                                    <?= $validation->listErrors() ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <div class="text-center d-grid">
                                            <button class="btn btn-primary" type="submit"> Sign In </button>
                                        </div>
                                        <div class="mb-3">
                                            </br>
                                            <a href="<?php echo base_url('forgot_password'); ?>"
                                                class="text-muted float-end"><small>Forgot your password?</small></a>
                                        </div>
                                        </form>
                                    <?php } ?>
                            </div> <!-- end card-body -->
                        </div> <!-- end col -->
                    </div>

                </div>
                <!-- end row -->
            </div>
            <!-- end container-fluid -->
        </div>
    </div>
</section>
<!-- home end -->

<!-- features start -->
<section class="section-sm" id="features">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="text-center mb-4 pb-1">
                    <h3 class="mb-3">Modules</h3>
                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="features-box">
                    <div class="features-img mb-4">
                        <img src="<?php echo base_url(); ?>/public/Landing/images/new_icons/Docheck_Icons_Project Management.png"
                            alt="">
                    </div>
                    <h4 class="mb-2">Project Management</h4>
                    <p class="text-muted">Seamlessly plan, execute, and monitor your projects with our intuitive project
                        management tools. Track progress, set deadlines, and allocate resources efficiently to ensure
                        your team stays on target.</p>
                </div>
            </div>
            <!-- end col -->

            <!-- end col -->

            <div class="col-lg-4 col-md-6">
                <div class="features-box">
                    <div class="features-img mb-4">
                        <img src="<?php echo base_url(); ?>/public/Landing/images/new_icons/Docheck_Icons_Course Builder.png"
                            alt="">
                    </div>
                    <h4 class="mb-2">Course Builder</h4>
                    <p class="text-muted">Say goodbye to complicated authoring tools and hello to a seamless content
                        creation experience with our online Course Builder. Start creating dynamic and interactive
                        content today with our platform.</p>
                </div>
            </div>
            <!-- end col -->

            <div class="col-lg-4 col-md-6">
                <div class="features-box">
                    <div class="features-img mb-4">
                        <img src="<?php echo base_url(); ?>/public/Landing/images/new_icons/Docheck_Icons_Online Review.png"
                            alt="">
                    </div>
                    <h4 class="mb-2">Online Review</h4>
                    <p class="text-muted">Simplify your review process with our integrated online review system.
                        Collaborate in real-time, gather feedback, and make informed decisions faster, boosting your
                        team's productivity and effectiveness.</p>
                </div>
            </div>
            <!-- end col -->
            <div class="col-lg-4 col-md-6">
                <div class="features-box">
                    <div class="features-img mb-4">
                        <img src="<?php echo base_url(); ?>/public/Landing/images/new_icons/Docheck_Icons_Storyboarding.png"
                            alt="">
                    </div>
                    <h4 class="mb-2">e-Manual</h4>
                    <p class="text-muted">E-Manual is a cutting-edge online platform designed to empower manufacturers
                        in creating, managing, and maintaining service manuals with ease. With E-Manual, troubleshooting
                        and searching for online support will be easy.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="features-box">
                    <div class="features-img mb-4">
                        <img src="<?php echo base_url(); ?>/public/Landing/images/new_icons/Docheck_Icons_LMS.png"
                            alt="">
                    </div>
                    <h4 class="mb-2">Learning Management System</h4>
                    <p class="text-muted">Empower your team with our comprehensive Learning Management System. Create
                        and manage training programs, track learner progress, and enhance skills development to foster a
                        culture of continuous improvement.</p>
                </div>
            </div>
            <!-- end col -->
            <div class="col-lg-4 col-md-6">
                <div class="features-box">
                    <div class="features-img mb-4">
                        <img src="<?php echo base_url(); ?>/public/Landing/images/new_icons/Docheck_Icons_AR-VR Tracking.png"
                            alt="">
                    </div>
                    <h4 class="mb-2">AR/VR Tracking</h4>
                    <p class="text-muted">Elevate your projects with innovative AR/VR tracking capabilities. Gain
                        real-time insights and visualize complex data, allowing your team to make data-driven decisions
                        and enhance overall project outcomes.</p>
                </div>
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div> <!-- end container-fluid -->
</section>
<!-- features end -->




<!-- contact start -->
<section class="section pb-0 bg-gradient" id="contact">
    <div class="bg-shape">
        <img src="<?php echo base_url(); ?>/public/Landing/images/bg-shape-light.png" alt=""
            class="img-fluid mx-auto d-block">
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="title text-center mb-4">
                    <h3>Have any Questions?</h3>
                    <div class="contact-content text-center mt-4">
                        <div class="contact-icon mb-2">
                            <i class="mdi mdi-email-outline text-info h2"></i>
                        </div>
                        <div class="contact-details text-white">
                            <h6>E-mail</h6>
                            <h5>admin@dochek.com</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

        <!-- end row -->


        <!-- end col -->
    </div>
    <br>
    <!-- end row -->
    </div>
    <!-- end container-fluid -->
</section>

<!-- contact end -->