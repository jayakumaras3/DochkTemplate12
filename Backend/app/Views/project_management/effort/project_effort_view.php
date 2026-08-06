<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/PM_ucn/edit_ucn'); ?>">Edit UCN</a></li>
                </ol>
            </div>
            <h4 class="page-title">Project Effort</h4>
        </div>
    </div>
</div>

<div class="row">
    <!-- Table to show project effort data -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">Project Effort Data</h5>
                <table class="table table-bordered table-striped dt-responsive nowrap w-100" id="project_effort_table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Hours</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total_effort = 0;
                        if (!empty($effort_data) && is_array($effort_data)) { ?>
                            <?php foreach ($effort_data as $data) { ?>
                                <tr>
                                    <td><?php echo $data['name'] . ' ' . $data['last_name']; ?></td>
                                    <td style="text-align: right;"><?php $base_effort = $data['total_effort'];
                                                                    $hours = floor($base_effort);
                                                                    $minutes = ($base_effort - $hours) * 60;
                                                                    $formattedTime = sprintf("%d:%02d", $hours, $minutes);
                                                                    echo $formattedTime;

                                                                    $total_effort += $data['total_effort']; ?></td>
                                    <td>
                                        <form action="<?php echo base_url('Project_Manage/Effort_Tracker/View_User_Effort') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="user_id" value="<?php echo $data['user_id']; ?>">
                                            <button type="submit" class="btn btn-outline-info waves-effect btn-xs waves-light">View Details</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total Effort</th>
                            <th style="text-align: right;"><?php $hours = floor($total_effort);
                                                            $minutes = ($total_effort - $hours) * 60;
                                                            $formattedTime = sprintf("%d:%02d", $hours, $minutes);
                                                            echo $formattedTime; ?></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">

        <div class="card">
            <div class="card-header">
                <h4 class="card-title text-primary">Assigned Employees</h4>
                <p class="sub-header">If the project is on hold or completed user will not be able to enter effort. </br>
                    If user is removed from the project, their effort will NOT be deleted from the project.</p>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users_by_project) && is_array($users_by_project)) { ?>
                            <?php foreach ($users_by_project as $user) {
                            ?>
                                <tr>
                                    <td>
                                        <?php echo  $user['name'] . ' ' . $user['last_name']; ?>
                                    </td>
                                    <td>
                                        <?php if ($user['pusers_status'] == 1) { ?>
                                            <form action="<?php echo base_url('Project_Manage/Effort_Tracker/Remove_user_from_project') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="pu_id" value="<?php echo $user['pu_id']; ?>">
                                                <button type="submit" class="btn btn-outline-danger waves-effect btn-xs waves-light">Remove</button>
                                            </form>
                                        <?php } else { ?>
                                            <span class="d-flex justify-content-center">
                                                <form method="post" class="mng-response-form" action="<?php echo base_url('Project_Manage/Effort_Tracker/pm_response'); ?>"><?= csrf_field() ?>
                                                    <input type="hidden" name="pu_id" value="<?php echo $user['pu_id']; ?>">
                                                    <input type="hidden" name="returnurl" value="2">
                                                    <input type="hidden" name="status" value="1">
                                                    <button type="submit" class="btn btn-outline-success waves-effect btn-xs waves-light"><i class="fa fa-check"></i></button>
                                                </form>
                                                &nbsp; &nbsp; &nbsp; &nbsp;
                                                <form method="post" class="mng-response-form" action="<?php echo base_url('Project_Manage/Effort_Tracker/pm_response'); ?>"><?= csrf_field() ?>
                                                    <input type="hidden" name="pu_id" value="<?php echo $user['pu_id']; ?>">
                                                    <input type="hidden" name="returnurl" value="2">
                                                    <input type="hidden" name="status" value="3">
                                                    <button type="submit" class="btn btn-outline-danger waves-effect btn-xs waves-light"><i class="fa fa-times"></i></button>
                                                </form>
                                            </span>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="2">No users assigned to this project.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!--  Add User to Project Form -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="card">
                    <div class="card-body">
                        <form action="<?php echo base_url('Project_Manage/Effort_Tracker/Add_user_to_project') ?>" method="POST" id="effort_form"><?= csrf_field() ?>
                            <div class="mb-3">
                                <label for="workweek" class="form-label">Select Multiple Employees </label>
                                <select name="users[]" class="form-control select2-multiple" data-toggle="select2" data-width="100%" multiple required>

                                    <?php if (!empty($all_users) && is_array($all_users)) {

                                    ?>
                                        <?php foreach ($all_users as $user) {
                                            $is_already_assigned = in_array($user['id_user'], array_column($users_by_project ?? [], 'id_user'));
                                            if ($is_already_assigned) {
                                                continue; // Skip this user if already assigned
                                            }
                                        ?>
                                            <option value="<?php echo $user['id_user']; ?>"><?php echo $user['name'] . ' ' . $user['last_name']; ?></option>
                                    <?php }
                                    } ?>

                                </select>
                            </div>
                            <input type="hidden" name="project_id" value="<?php echo isset($projectid) ? $projectid : ''; ?>">
                            <div>
                                <button type="submit" class="btn btn-outline-warning waves-effect btn-xs waves-light">Assign User/s</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>