<?php
$userlevel = session()->get('userlevel');
$arrayuserlevel  = array_map('intval', explode(',', $userlevel));
if (session()->get('error')) :
    echo '<script>alert("' . session()->get('error') . '")</script>';
endif;
$client =  session()->get('client');
$arraystakeholders  = explode(',', $client);
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('my_training') ?>">Dashboard</a></li>
                </ol>
            </div>
            <h4 class="page-title">Categories</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 col-xl-3">
        <a href="<?php echo base_url('SCORM/scorm_courses') ?>">
            <div class="card bg-pattern">
                <div class="card-body">
                    <div class="row">
                        <div class="row">
                            <div class="col-3">
                                <div class="avatar-sm rounded-circle bg-soft-warning border-warning border">
                                    <i class="fe-award avatar-title font-22 text-warning"></i>
                                </div>
                            </div>
                            <div class="col-9 mt-1">
                                <h5 class="text-dark my-1">Courses</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-6 col-xl-3">
        <a href="<?php echo base_url('my_training/user_learn_group') ?>">
            <div class="card bg-pattern">
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <div class="avatar-sm rounded-circle bg-soft-primary border-primary border">
                                <i class="fe-layers avatar-title font-22 text-primary"></i>
                            </div>
                        </div>
                        <div class="col-9 mt-1">
                            <h5 class="text-dark my-1">Course Groups</h5>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-6 col-xl-3">
        <a href="<?php echo base_url('my_training/user_categogies') ?>">
            <div class="card border-info border bg-pattern">
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <div class="avatar-sm rounded-circle bg-soft-info border-info border">
                                <i class="fe-git-pull-request avatar-title font-22 text-info"></i>
                            </div>
                        </div>
                        <div class="col-9 mt-1">
                            <h5 class="text-dark my-1">Categories</h5>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-6 col-xl-3">
        <a href="<?php echo base_url('Reports/User_report') ?>">
            <div class="card bg-pattern">
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <div class="avatar-sm rounded-circle bg-soft-success border-success border">
                                <i class="fe-trending-up avatar-title font-22 text-success"></i>
                            </div>

                        </div>
                        <div class="col-9 mt-1">
                            <h5 class="text-dark my-1">Reports</h5>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row">
    <?php foreach ($categoriesofclient as $category) { ?>
        <div class="col-4">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-sm-10">
                            <div class="d-flex align-items-start">
                                <div class="w-100">
                                    <h4 class="mt-0 mb-2 font-16"><?php echo $category['description'] ?></h4>
                                    <!-- <p class="mb-1"><b>Location:</b> Seattle, Washington</p>
                                    <p class="mb-0"><b>Category:</b> Ecommerce</p> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="text-center mt-3 mt-sm-0">
                                <div class="badge font-14 bg-soft-info text-info p-1">
                                    <form class="form-horizontal" action="<?php echo base_url('my_training/view_category_courses') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="category_id" value="<?php echo $category['category_id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-default"><?php echo $category['course_count'] ?></button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div> <!-- end row -->
                </div>
            </div> <!-- end card-->
        </div>
    <?php } ?>
</div>
</div>