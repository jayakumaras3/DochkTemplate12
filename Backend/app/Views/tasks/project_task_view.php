<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Project Tasks</h4>
        </div>
    </div>
</div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="table dt-responsive w-100">
                        <thead>
                            <tr>
                                <th>Project</th>
                                <th>Course</th>
                                <th>Description</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Assign</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($assignedMasterTask) {
                                foreach ($assignedMasterTask as $master) {
                            ?>
                                    <tr>
                                        <td><?php echo $master['projectname'] ?></td>
                                        <td><?php echo $master['course_name'] ?></td>
                                        <td><?php echo $master['description'] ?></td>
                                        <td><?php echo ($master['start_date'] != '0000-00-00') ? date('m-d-Y', strtotime($master['start_date'])) : ''; ?></td>
                                        <td><?php echo ($master['end_date'] != '0000-00-00') ? date('m-d-Y', strtotime($master['end_date'])) : ''; ?></td>
                                        <td>
                                            <form class="form-horizontal" action="<?php echo base_url('Task/Task_manage/assign_master') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="master_id" value="<?php echo $master['mt_id']; ?>">
                                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-plus"></span></button>
                                            </form>
                                        </td>
                                        <td><?php
                                            $task_status = explode(',', $master['task_status']);
                                            // print_r(array_unique($task_status));
                                            if (count(array_unique($task_status)) == 1 && in_array('1', $task_status)) {
                                                echo 'Assigned';
                                            }
                                            // Check if all statuses are 3 (Completed)
                                            elseif (count(array_unique($task_status)) == 1 && in_array('3', $task_status)) {
                                                echo 'Completed';
                                            }
                                            // Check if any status is 1 or 2 (In Progress)
                                            elseif (in_array('1', $task_status) || in_array('2', $task_status)) {
                                                echo 'In Progress';
                                            }else{
                                                echo 'Not Assigned';
                                            }
                                            // Check if all statuses are 1 (Assigned)
                                            
                                            ?></td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
</div>
</div>
</div>
</div>
</div>
</div>