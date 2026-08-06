<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title"><?php echo $header_title ?></h4>
        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-between">
                    <div class="col-auto">
                        <form class="d-flex flex-wrap align-items-center"
                            action="<?php echo base_url('marketplace/dashboard/search_by_coursename') ?>" method="POST"><?= csrf_field() ?>
                            <div class="row">
                                <div class="col-auto">
                                    <input type="search" class="form-control my-1 my-lg-0" name="search_term"
                                        placeholder="Search courses..." value="">
                                </div>

                            </div>
                        </form>
                    </div>
                    <?php
                    $userlevel = session()->get('userlevel');
                    $arrayuserlevel = explode(',', $userlevel);
                    if (in_array('6', $arrayuserlevel) || in_array('44', $arrayuserlevel)) {
                        ?>
                        <div class="col-auto">
                            <div class="text-lg-end my-1 my-lg-0">
                                <form action="<?php echo base_url('marketplace/admin'); ?>" method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="type" value="<?php echo $type; ?>">
                                    <button type="submit" class="btn btn-outline-danger waves-effect btn-sm waves-light">
                                        Admin
                                    </button>
                                </form>
                            </div>

                        </div>
                        <!-- end col-->
                        <?php
                    }
                    ?>

                </div> <!-- end row -->
            </div>
        </div> <!-- end card -->
    </div> <!-- end col-->
</div>
<div class="row">
    <!-- start chat users-->
    <div class="col-xl-3 col-lg-4">
        <div class="card">
            <div class="card-body">

                <h6 class="font-13 text-muted text-uppercase mb-2">Category</h6>

                <!-- users -->
                <div class="row">
                    <div class="col">
                        <div data-simplebar="init" style="max-height: 275px;">
                            <div class="simplebar-wrapper" style="margin: 0px;">
                                <div class="simplebar-height-auto-observer-wrapper">
                                    <div class="simplebar-height-auto-observer"></div>
                                </div>
                                <div class="simplebar-mask">
                                    <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                        <div class="simplebar-content-wrapper" tabindex="0" role="region"
                                            aria-label="scrollable content"
                                            style="height: auto; overflow: hidden scroll;">
                                            <div class="simplebar-content" style="padding: 0px;">

                                                <!-- <a href="<?php echo base_url('marketplace/dashboard/categorylist/126') ?>"
                                                    class="text-body">
                                                    <div class="d-flex align-items-start p-1">
                                                        <div class="w-100">
                                                            <h5 class="mt-0 mb-0 font-14">
                                                                <span
                                                                    class="float-end text-muted fw-normal font-12">300</span>
                                                                <?php if ($cat_list == 126) { ?>
                                                                    <span class="text-warning">Business Skills</span>
                                                                <?php } else { ?>
                                                                    Business Skills
                                                                <?php } ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </a>
                                                <a href="<?php echo base_url('marketplace/dashboard/categorylist/127') ?>"
                                                    class="text-body">
                                                    <div class="d-flex align-items-start p-1">
                                                        <div class="w-100">
                                                            <h5 class="mt-0 mb-0 font-14">
                                                                <span
                                                                    class="float-end text-muted fw-normal font-12">24</span>
                                                                <?php if ($cat_list == 127) { ?>
                                                                    <span class="text-warning">Compliance</span>
                                                                <?php } else { ?>
                                                                    Compliance
                                                                <?php } ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </a>
                                                <a href="<?php echo base_url('marketplace/dashboard/categorylist/128') ?>"
                                                    class="text-body">
                                                    <div class="d-flex align-items-start p-1">
                                                        <div class="w-100">
                                                            <h5 class="mt-0 mb-0 font-14">
                                                                <span
                                                                    class="float-end text-muted fw-normal font-12">49</span>
                                                                <?php if ($cat_list == 128) { ?>
                                                                    <span class="text-warning">DEI (Diversity, Equity, and
                                                                        Inclusion)</span>
                                                                <?php } else { ?>
                                                                    DEI (Diversity, Equity, and Inclusion)
                                                                <?php } ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </a>
                                                <a href="<?php echo base_url('marketplace/dashboard/categorylist/129') ?>"
                                                    class="text-body">
                                                    <div class="d-flex align-items-start p-1">
                                                        <div class="w-100">
                                                            <h5 class="mt-0 mb-0 font-14">
                                                                <span
                                                                    class="float-end text-muted fw-normal font-12">56</span>
                                                                <?php if ($cat_list == 129) { ?>
                                                                    <span class="text-warning">Technology</span>

                                                                <?php } else { ?>
                                                                    Technology
                                                                <?php } ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </a>
                                                <a href="<?php echo base_url('marketplace/dashboard/categorylist/130') ?>"
                                                    class="text-body">
                                                    <div class="d-flex align-items-start p-1">
                                                        <div class="w-100">
                                                            <h5 class="mt-0 mb-0 font-14">
                                                                <span
                                                                    class="float-end text-muted fw-normal font-12">27</span>
                                                                <?php if ($cat_list == 130) { ?>
                                                                    <span class="text-warning">Safety</span>
                                                                <?php } else { ?>
                                                                    Safety
                                                                <?php } ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </a>
                                                <a href="<?php echo base_url('marketplace/dashboard/categorylist/131') ?>"
                                                    class="text-body">
                                                    <div class="d-flex align-items-start p-1">
                                                        <div class="w-100">
                                                            <h5 class="mt-0 mb-0 font-14">
                                                                <span
                                                                    class="float-end text-muted fw-normal font-12">41</span>
                                                                <?php if ($cat_list == 131) { ?>
                                                                    <span class="text-warning">Wellness</span>
                                                                <?php } else { ?>
                                                                    Wellness
                                                                <?php } ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </a> -->
                                                <?php foreach ($skills as $skill) { ?>
                                                    <a href="<?php echo base_url('marketplace/dashboard/categorylist/'.$skill['sc_mcid']) ?>"
                                                        class="text-body">
                                                        <div class="d-flex align-items-start p-1">
                                                            <div class="w-100">
                                                                <h5 class="mt-0 mb-0 font-14">
                                                                    <span
                                                                        class="float-end text-muted fw-normal font-12"><?php echo $skill['course_count'] ?></span>
                                                                    <?php if ($cat_list == $skill['sc_mcid']) { ?>
                                                                        <span class="text-warning"><?php echo $skill['skillname'] ?></span>
                                                                    <?php } else { ?>
                                                                        <?php echo $skill['skillname'] ?>
                                                                    <?php } ?>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="simplebar-placeholder" style="width: auto; height: 220px;"></div>
                            </div>

                        </div>
                        <!-- end slimscroll-->
                    </div>
                    <!-- End col -->
                </div>


                <h6 class="font-13 text-muted text-uppercase mb-2">Languages</h6>

                <!-- users -->
                <div class="row">
                    <div class="col">
                        <div data-simplebar="init" style="max-height: 375px;">
                            <div class="simplebar-wrapper" style="margin: 0px;">
                                <div class="simplebar-height-auto-observer-wrapper">
                                    <div class="simplebar-height-auto-observer"></div>
                                </div>
                                <div class="simplebar-mask">
                                    <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                        <div class="simplebar-content-wrapper" tabindex="0" role="region"
                                            aria-label="scrollable content"
                                            style="height: auto; overflow: hidden scroll;">
                                            <div class="simplebar-content" style="padding: 0px;">

                                                <a href="<?php echo base_url('marketplace/dashboard/languagelist/1') ?>"
                                                    class="text-body">
                                                    <div class="d-flex align-items-start p-1">
                                                        <div class="w-100">
                                                            <h5 class="mt-0 mb-0 font-14">
                                                                <span
                                                                    class="float-end text-muted fw-normal font-12">418</span>
                                                                <?php if ($mp_language == "English") { ?>
                                                                    <span class="text-warning">English</span>
                                                                <?php } else { ?>
                                                                    English
                                                                <?php } ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </a>

                                                <a href="<?php echo base_url('marketplace/dashboard/languagelist/2') ?>"
                                                    class="text-body">
                                                    <div class="d-flex align-items-start p-1">
                                                        <div class="w-100">
                                                            <h5 class="mt-0 mb-0 font-14">
                                                                <span
                                                                    class="float-end text-muted fw-normal font-12">55</span>

                                                                <?php if ($mp_language == "Spanish") { ?>
                                                                    <span class="text-warning">Spanish</span>
                                                                <?php } else { ?>
                                                                    Spanish
                                                                <?php } ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </a>
                                                <a href="<?php echo base_url('marketplace/dashboard/languagelist/3') ?>"
                                                    class="text-body">
                                                    <div class="d-flex align-items-start p-1">
                                                        <div class="w-100">
                                                            <h5 class="mt-0 mb-0 font-14">
                                                                <span
                                                                    class="float-end text-muted fw-normal font-12">11</span>

                                                                <?php if ($mp_language == "German") { ?>
                                                                    <span class="text-warning">German</span>
                                                                <?php } else { ?>
                                                                    German
                                                                <?php } ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </a>
                                                <a href="<?php echo base_url('marketplace/dashboard/languagelist/4') ?>"
                                                    class="text-body">
                                                    <div class="d-flex align-items-start p-1">
                                                        <div class="w-100">
                                                            <h5 class="mt-0 mb-0 font-14">
                                                                <span
                                                                    class="float-end text-muted fw-normal font-12">5</span>

                                                                <?php if ($mp_language == "Italian") { ?>
                                                                    <span class="text-warning">Italian</span>
                                                                <?php } else { ?>
                                                                    Italian
                                                                <?php } ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </a>

                                                <a href="<?php echo base_url('marketplace/dashboard/languagelist/5') ?>"
                                                    class="text-body">
                                                    <div class="d-flex align-items-start p-1">
                                                        <div class="w-100">
                                                            <h5 class="mt-0 mb-0 font-14">
                                                                <span
                                                                    class="float-end text-muted fw-normal font-12">11</span>

                                                                <?php if ($mp_language == "French") { ?>
                                                                    <span class="text-warning">French</span>
                                                                <?php } else { ?>
                                                                    French
                                                                <?php } ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="simplebar-placeholder" style="width: auto; height: 140px;"></div>
                            </div>

                        </div>
                        <!-- end slimscroll-->
                    </div>
                    <!-- End col -->
                </div>



                <!-- end users -->
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div>
    <!-- end chat users-->

    <!-- chat area -->