<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('SCORM/scorm_courses') ?>">My Courses</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('my_training/read_more') ?>">Course Detail</a></li>

                    <li class="breadcrumb-item"><a href="<?php echo base_url($header_link) ?>"><?php echo $header ?></a></li>
       
                </ol>
            </div>
            <h4 class="page-title"><?php echo $header2; ?></h4>
        </div>
    </div>
</div>
<div class="row">
    <!-- <div class="col-sm-6">
        <div class="x_panel">
            <h6>Update Scenario</h6>
            <div class="x_title">
                <div class="x_content">
                    <br />
                    <div class="block block-drop-shadow">
                        <div class="content controls">
                            <form action="<?php echo base_url($updateScenario) ?>" method="post" autocomplete="off"><?= csrf_field() ?>
                                <div class="form-row">
                                    <label>Scenario Name</label>
                                    <input type="text" name="scenario" class="form-control" value="<?php echo $scenario_details[0]['scenario_name']; ?>" required="" />
                                </div><br>
                                <div class="form-row">
                                    <label>Scenario Status</label>
                                    <?php $status = $scenario_details[0]['status']; ?>
                                    <select class="form-select" name="status">
                                        <option value="1" <?php if ($status == 1) echo "SELECTED"; ?>>In Development</option>
                                        <option value="2" <?php if ($status == 2) echo "SELECTED"; ?>>Live</option>
                                    </select>
                                </div><br>

                                <div class="form-row">
                                <input type="hidden" name="scourse_id" value="<?php echo $scourse_id; ?>" required="" />
                                    <input type="hidden" name="xs" value="<?php echo $scenario_details[0]['xs']; ?>" required="" />
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="icon-key"> </i>Save</button>
                                </div>
                                <?php if (isset($validation)) : ?>
                                    <div class=col-12 col-sm-4>
                                        <div class="alert alert-danger" role="alert">
                                            <?= $validation->listErrors() ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <h4>Assign Users and Role to Scenario</h4>
                <form class="form" action="<?php echo base_url('XAPI/XAPI_scenarios_courses/assignUserstoScenario') ?>" method="POST"><?= csrf_field() ?>
                    <div class="form-row">
                        <label>User Name</label>
                        <select class="form-select" name="user_id">
                            <?php if (!empty($getAssignedCourseUsers)) {
                                foreach ($getAssignedCourseUsers as $AssignedUsers) { ?>
                                    <option value="<?php echo $AssignedUsers['id_user'] ?>"><?php echo $AssignedUsers['fullname'] ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div><br>
                    <div class="form-row">
                        <label>Roles</label>
                        <select class="form-select" name="role">
                            <option value="1">User</option>
                            <option value="2">Instructor</option>
                        </select>
                    </div><br>
                    <div class="form-row">
                        <input type="hidden" name="xs" value="<?php echo $scenario_details[0]['xs']; ?>" required="" />
                        <input type="hidden" name="scenario_name" value="<?php echo $scenario_details[0]['scenario_name']; ?>" required="" />
                        <input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
                        <input type="hidden" name="course_name" value="<?php echo $course_name ?>">
                        <button type="submit" class="btn btn-sm btn-primary">Add</button>
                    </div>
                    <?php if (isset($validation)) : ?>
                        <div class=col-12 col-sm-4>
                            <div class="alert alert-danger" role="alert">
                                <?= $validation->listErrors() ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <div class="col-sm-8">
        <div class="card">
            <div class="card-body">
                <h4>Assigned Users to <?php echo $header; ?></h4><br />
                <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Role</th>
                            <!-- <th>Edit</th> -->
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $j = 0;
                        if (!empty($getAssignedScenarioUsers)) {
                            foreach ($getAssignedScenarioUsers as $AssignedScenarioUsers) {
                                // print_r($AssignedScenarioUsers);
                                $j = $j + 1; ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $AssignedScenarioUsers['fullname']; ?></td>
                                    <td><?php if ($AssignedScenarioUsers['role'] == 1) {
                                            echo 'User';
                                        } else {
                                            echo 'Instructor';
                                        }
                                        ?></td>
                                    <!-- <td>
                                            <form class="form-horizontal" action="<?php echo base_url($assignUserScenario) ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="user_assign_id" value="<?php echo $AssignedScenarioUsers['user_assign_id'] ?>">
                                                <input type="hidden" name="xs" value="<?php echo $AssignedScenarioUsers['scenario_id'] ?>">
                                                <input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
                                                <input type="hidden" name="course_name" value="<?php echo $course_name ?>">
                                                <button type="submit" class="btn btn-sm widget-icon btn-warning"><span class="fa fa-pencil"></span></button>
                                            </form>
                                        </td> -->
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url($deleteAssignedUser) ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="xs" value="<?php echo $scenario_details[0]['xs']; ?>" required="" />
                                            <input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
                                            <input type="hidden" name="course_name" value="<?php echo $course_name ?>">
                                            <input type="hidden" name="user_assign_id" value="<?php echo $AssignedScenarioUsers['user_assign_id'] ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')"><span class="mdi mdi-trash-can-outline"></span></button>
                                        </form>
                                    </td>
                                </tr>

                        <?php }
                        } ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>