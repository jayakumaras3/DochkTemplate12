<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Task/Task_manage/team_tasks') ?>">Team Tasks</a></li>
                </ol>
            </div>
            <h4 class="page-title">Active Task View (<?php echo $emplname; ?>)</h4>
        </div>
    </div>
</div>
<?php if ($is_manager == 2) { ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5>Team Tasks</h5>
                    <table class="table dt-responsive w-100">
                        <thead>
                            <tr>
                                <th>Project</th>
                                <th>Course</th>
                                <th>Description</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Assigned View</th>
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
                                            <form class="form-horizontal" action="<?php echo base_url('Task/Task_manage/assign_master_tl') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="master_id" value="<?php echo $master['mt_id']; ?>">
                                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-eye-outline"></span></button>
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
        </div> <!-- end col -->
    </div>
<?php } ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5>Employee Tasks</h5>
                <table class="table dt-responsive w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Course</th>
                            <th>Status</th>
                            <th>Description</th>
                            <th>Planned Effort</th>
                            <th>Due Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($to_do) {
                            foreach ($to_do as $to_do_list) {
                                if (isset($to_do_list['effort'])) {
                                    $effort  =  explode('.', $to_do_list['effort']);

                                    $effort_hours = $effort[0];
                                    if (isset($effort[1])) {
                                        $effort_min = $effort[1];
                                        if ($effort_min == '0') {
                                            $Min = '00';
                                        } elseif ($effort_min == '25') {
                                            $Min = '15';
                                        } elseif ($effort_min == '5') {
                                            $Min = '30';
                                        } elseif ($effort_min == '75') {
                                            $Min = '45';
                                        } else {
                                        }
                                        $actual =  $effort_hours . ':' . $Min;
                                    }
                                }
                                $actual_value = ($to_do_list['effort'] != '') ? $actual : '';
                                echo '<tr>';
                                echo '<td>' . $to_do_list['task_id'] . '</td>';
                                echo '<td>' . $to_do_list['course_name'] . '</td>';
                                echo '<td>To Do</td>';
                                echo '<td>' . $to_do_list['description'] . '</td>';
                                echo '<td>' .  $actual_value . '</td>';
                                echo '<td>' . $to_do_list['due_date'] . '</td>';
                                echo '</tr>';
                            }
                        }
                        if ($in_progress) {
                            foreach ($in_progress as $in_progress_list) {
                                if (isset($in_progress_list['effort'])) {
                                    $eeffort  =  explode('.', $in_progress_list['effort']);

                                    $effort_hours = $eeffort[0];
                                    if (isset($eeffort[1])) {
                                        $effort_min = $eeffort[1];
                                        if ($effort_min == '0') {
                                            $Min = '00';
                                        } elseif ($effort_min == '25') {
                                            $Min = '15';
                                        } elseif ($effort_min == '5') {
                                            $Min = '30';
                                        } elseif ($effort_min == '75') {
                                            $Min = '45';
                                        } else {
                                            $Min =  '00';
                                        }
                                        $actual =  $effort_hours . ':' . $Min;
                                    }
                                }
                                $actual_value = ($in_progress_list['effort'] != '') ? $actual : '';
                                echo '<tr>';
                                echo '<td>' . $in_progress_list['task_id'] . '</td>';
                                echo '<td>' . $in_progress_list['course_name'] . '</td>';
                                echo '<td>In Prog.</td>';
                                echo '<td>' . $in_progress_list['description'] . '</td>';
                                echo '<td>' . $actual_value  . '</td>';
                                echo '<td>' . $in_progress_list['due_date'] . '</td>';
                                echo '</tr>';
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
