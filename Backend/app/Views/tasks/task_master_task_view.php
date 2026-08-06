<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('my_training/read_more') ?>">Course Details</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Task/Task_master/task_master_pm') ?>">Task Master</a></li>
                </ol>
            </div>
            <h4 class="page-title">Project Task View</h4>
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
                            <th>Client</th>
                            <th>Project</th>
                            <th>Course</th>
                            <th>Description</th>
                            <th>Total Effort</th>
                            <th>Start</th>
                            <th>End</th>
                            <!--  <th>Close</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $project_id = 0;
                        $course_id = 0;
                        $mt_id = 0;
                        $dt_id = 0;

                        if ($masterData) {

                            foreach ($masterData as $data) {
                                if (isset($data['effort'])) {
                                    $effort  =  explode('.', $data['effort']);

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
                                            $Min = '00';
                                        }
                                        $actual =  $effort_hours . ':' . $Min;
                                    } else {
                                        $actual =  $effort_hours . ':00';
                                    }
                                }
                                $project_id = $data['project_id'];
                                $course_id =  $data['course_id'];
                                $mt_id =  $data['mt_id'];
                                $dt_id =  $data['dt_id'];
                        ?>
                                <tr>
                                    <td><?php echo $data['client_name'] ?></td>
                                    <td><?php echo $data['projectname'] ?></td>
                                    <td><?php echo $data['course_name'] ?></td>
                                    <td><?php echo $data['description'] ?></td>
                                    <td><?php echo $actual ?></td>
                                    <td><?php echo ($data['start_date'] != '0000-00-00') ? date('m-d-Y', strtotime($data['start_date'])) : ''; ?></td>
                                    <td><?php echo ($data['end_date'] != '0000-00-00') ? date('m-d-Y', strtotime($data['end_date'])) : ''; ?></td>
                                    <!--  <td>
                                        <form class="form-horizontal" action="<?php echo base_url('Task/Task_manage/complete_master_task') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="mt_id" value="<?php echo $data['mt_id']; ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-window-close"></span></button>
                                        </form>
                                    </td> -->
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
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered dt-responsive w-100">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Assigned To</th>
                            <th>Planned</th>
                            <th>Actual</th>
                            <th>Status</th>
                            <th>Planned On</th>
                            <th>Completed On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($taskByMasterId) {
                            $effort_value = '';
                            $actual_effort_value = '';
                            foreach ($taskByMasterId as $task) {
                                if (isset($task['effort'])) {
                                    $eeffort  =  explode('.', $task['effort']);

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
                                        }
                                        $effort =  $effort_hours . ':' . $Min;
                                    } else {
                                        $effort =  $effort_hours;
                                    }
                                    $actual_effort  =  explode('.', $task['actual_effort']);

                                    $effort_hours = $actual_effort[0];
                                    if (isset($actual_effort[1])) {
                                        $effort_min = $actual_effort[1];
                                        if ($effort_min == '0') {
                                            $Min = '00';
                                        } elseif ($effort_min == '25') {
                                            $Min = '15';
                                        } elseif ($effort_min == '5') {
                                            $Min = '30';
                                        } elseif ($effort_min == '75') {
                                            $Min = '45';
                                        }
                                        $actual =  $effort_hours . ':' . $Min;
                                    } else {
                                        $actual =  $effort_hours;
                                    }
                                }

                        ?>
                                <tr>
                                    <td><?php echo $task['description'] ?></td>
                                    <td><?php echo $task['name'] ?></td>
                                    <td><?php echo ($task['effort'] != '') ? $effort : ''; ?></td>
                                    <td><?php echo ($task['actual_effort'] != '') ? $actual : ''; ?></td>
                                    <td>
                                        <?php
                                        $status = $task['status'];
                                        switch ($status) {
                                            case 1:
                                                echo 'To Do';
                                                break;
                                            case 2:
                                                echo 'In Prog';
                                                break;
                                            case 3:
                                                echo 'Completed';
                                                break;
                                        }
                                        ?>
                                    </td>
                                    <td><?php
                                        $due = $task['due_date'];
                                        $completed_on = $task['completed_on'];
                                        if (strlen($due ?? '') > 3) {
                                            echo substr($due, 5);
                                        } ?>
                                    </td>
                                    <td><?php
                                        if ($status == 1 || $status == 2) {
                                        ?>
                                            <!--  <form class="form-horizontal" action="<?php echo base_url('Task/Task_manage/change_task_status') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="task_id" value="<?php echo $task['task_id']; ?>">
                                                <input type="hidden" name="status" value="0">
                                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-trash-can-outline"></span></button>
                                            </form> -->
                                        <?php
                                        }
                                        if (strlen($completed_on ?? '') > 3) {
                                            echo date('m-d', $completed_on);
                                        } ?>
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
</div>