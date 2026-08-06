<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Certification/certification_dashboard/certification_view') ?>">Back</a></li>
                </ol>
            </div>
            <h4 class="page-title">Assigned
                <?php
                if ($type == 4) {
                    echo " - Learning Plan";
                }
                if ($type == 3) {
                    echo " - Courses";
                }
                if ($type == 2) {
                    echo " - Learning Plan";
                }
                if ($type == 1) {
                    echo " - Marketplace";
                }
                ?>
            </h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="row">
        <div class="col-lg-6">
            <?php if ($type == 3) { ?>
                <div class="card">
                    <div class="card-body">
                        <form class="form-horizontal" action="<?php echo base_url('Certification/Certification_dashboard/assign_cert_to_course') ?>" method="POST"><?= csrf_field() ?>
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label for="type" class="form-label">Assign Course</label>
                                <select class="form-select" name="course_id">
                                    <?php foreach ($get_all_courses as $all_courses) {
                                        $key = array_search($all_courses['scourse_id'], array_column($get_assigned_courses, 'scourse_id'));
                                        if (!empty($key) || $key === 0) {
                                        } else {
                                    ?>
                                            <option value="<?php echo $all_courses["scourse_id"]; ?>"><?php echo $all_courses["name"]; ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <input type="hidden" name="type" value="3">
                            <button type="submit" class="btn btn-outline-primary btn-xs waves-effect waves-light">Submit</button>
                        </form>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            <?php } ?>
            <?php if ($type < 3 || $type == 4) { ?>
                <div class="card">
                    <div class="card-body">
                        <form class="form-horizontal" action="<?php echo base_url('Certification/Certification_dashboard/assign_cert_to_course') ?>" method="POST"><?= csrf_field() ?>
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label for="type" class="form-label">Assign
                                    <?php
                                    if ($type == 4) {
                                        echo " - <a href=" . base_url("marketplace/learning_dashboard") . ">Learning Plan</a>";
                                    }
                                    if ($type == 3) {
                                        echo " - Courses";
                                    }
                                    if ($type == 2) {
                                        echo "Learning Plan";
                                    }
                                    if ($type == 1) {
                                        echo "Marketplace";
                                    }
                                    ?>
                                </label>

                                <select class="form-select" name="course_id">
                                    <?php foreach ($get_all_learning_plan as $all_lp) {
                                        $key = array_search($all_lp['scourse_id'], array_column($get_assigned_lp, 'scourse_id'));
                                        if (!empty($key) || $key === 0) {
                                        } else {
                                    ?>

                                            <option value="<?php echo $all_lp["scourse_id"]; ?>"><?php echo $all_lp["name"]; ?></option>

                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <input type="hidden" name="type" value="<?php echo $type; ?>">
                            <?php if ($type == 2 || $type == 4) { ?>
                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">Submit</button>
                            <?php }
                            if ($type == 1) { ?>
                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">Submit</button>
                            <?php } ?>
                        </form>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            <?php } ?>


        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="alternative-page-datatable" class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $j = 0;

                                if ($type == 3) {
                                    foreach ($get_assigned_courses as $courses_assigned) {
                                        $j++;
                                ?>
                                        <tr>
                                            <td><?php echo $j; ?></td>
                                            <td><?php echo $courses_assigned['name']; ?></td>
                                            <td>
                                                <form action="<?php echo base_url('Certification/Certification_dashboard/Un_Assign') ?>" method="POST"><?= csrf_field() ?>
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="cert_assign_id" value="<?php echo $courses_assigned['cert_assign_id']; ?>">
                                                    <button type="submit" onclick="return confirm('<?php echo lang('Alert.Aler_004') ?>')" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                                        <span class="mdi mdi-trash-can-outline"></span> Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                }

                                if ($type == 2 || $type == 4) {
                                    foreach ($get_assigned_lp as $lp_assigned) {
                                        $j++;
                                    ?>
                                        <tr>
                                            <td><?php echo $j; ?></td>
                                            <td><?php echo $lp_assigned['name']; ?></td>
                                            <td>
                                                <form action="<?php echo base_url('Certification/Certification_dashboard/Un_Assign') ?>" method="POST"><?= csrf_field() ?>
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="cert_assign_id" value="<?php echo $lp_assigned['cert_assign_id']; ?>">
                                                    <button type="submit" onclick="return confirm('<?php echo lang('Alert.Aler_004') ?>')" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                                        <span class="mdi mdi-trash-can-outline"></span> Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                }

                                if ($type == 1) {
                                    foreach ($get_assigned_lp as $mp_assigned) {
                                        $j++;
                                    ?>
                                        <tr>
                                            <td><?php echo $j; ?></td>
                                            <td><?php echo $mp_assigned['name']; ?></td>
                                            <td>
                                                <form action="<?php echo base_url('Certification/Certification_dashboard/Un_Assign') ?>" method="POST"><?= csrf_field() ?>
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="cert_assign_id" value="<?php echo $mp_assigned['cert_assign_id']; ?>">
                                                    <button type="submit" onclick="return confirm('<?php echo lang('Alert.Aler_004') ?>')" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                                        <span class="mdi mdi-trash-can-outline"></span> Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <!-- end col -->
    </div>
</div>