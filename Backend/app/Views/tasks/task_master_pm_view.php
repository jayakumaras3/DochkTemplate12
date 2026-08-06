<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css">
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('SCORM/scorm_courses/course_add_view') ?>">Course</a></li>

                </ol>
            </div>
            <h4 class="page-title">Task Master <?php echo isset($course_name) ? '- ' . $course_name : ''; ?></h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" id="uploadForm" action="<?php echo base_url('Task/Task_master/add_new_task') ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group mb-2">
                                <label>Description</label>
                                <input type="text" class="form-control" name="description" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-2">
                                <label>Assign To</label>

                                <select class="form-select" name="assigned_to" required>

                                    <option value="<?php echo $self_id_user; ?>">Self : <?php echo $self_user_name; ?></option>
                                    <?php
                                    $excluded_users = array(1129, 1141, 1138, 1121, 1124, 1115, 1130, 1135);
                                    foreach ($managerlist as $users) {

                                        if (in_array($users['id_user'], $excluded_users)) {
                                            continue;
                                        }
                                        echo '<option value="' . $users['id_user'] . '">';
                                        echo $users['name'] . ' ' . $users['last_name'];
                                        echo '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group mb-2">
                                        <label>Effort in hours</label>
                                        <input type="number" maxlength="4" min="0" name="effort" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" required />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mb-2">
                                        <label>Effort in minutes</label>
                                        <select name="effort_min" class="form-control" required>
                                            <option value=".0">00</option>
                                            <option value=".25">15</option>
                                            <option value=".5">30</option>
                                            <option value=".75">45</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-2">
                                <label>Start Date</label>
                                <input class="form-control" id="start_date" name="start_date" type="date" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-2">
                                <label>End Date</label>
                                <input class="form-control" id="end_date" name="end_date" type="date" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mt-3 d-grid">
                                <input type="hidden" name="project_id" value="<?php echo $projectid ?>">
                                <input type="hidden" name="course_id" value="<?php echo $scourse_id ?>">
                                <input type="hidden" name="dt_id" value="0">
                                <input type="hidden" name="type_of_task" value="5">
                                <button type="submit" class="btn btn-outline-danger waves-effect btn-sm waves-light" id="uploadButton">Assign Task to Lead</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table id="alternative-page-datatable" class="table dt-responsive w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Description</th>
                            <th>Assigned</th>
                            <th>Effort</th>
                            <th>Started</th>
                            <th>Ended</th>
                            <th>Details</th>
                            <th>Del</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        if ($course_tasks) {
                            foreach ($course_tasks as $task) {
                                if (isset($task['effort'])) {
                                    $effort  =  explode('.', $task['effort']);

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
                                    //  print_r( $effort_min);
                                    // exit();
                                }
                                $j = $j + 1; ?>
                                <tr>
                                    <td><?php echo $j; ?> </td>
                                    <td><?php echo $task['description']; ?></td>
                                    <td><?php echo $task['assigned_to']; ?></td>
                                    <td><?php echo (isset($actual)) ? $actual : ''; ?></td>
                                    <td><?php echo date('m-d', strtotime($task['start_date'])); ?></td>
                                    <td><?php echo date('m-d', strtotime($task['end_date'])); ?></td>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url('Task/Task_master/view_taskMaster_details') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="mt_id" value="<?php echo $task['mt_id']; ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-eye-outline"></span></button>
                                        </form>
                                    </td>
                                    <td>
                                        <?php $status = $task['status'];
                                        // print_r($allTaskofCourse);
                                        // print_r($task['mt_id']);
                                        $allTaskIds = array_column($allTaskofCourse, 'master_task_id');

                                        if (!in_array($task['mt_id'], $allTaskIds)) { ?>
                                            <form class="form-horizontal" action="<?php echo base_url('Task/Task_master/deleteMasterTask') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="mt_id" value="<?php echo $task['mt_id']; ?>">
                                                <input type="hidden" name="return_url" value="1">
                                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')"><span class="mdi mdi-trash-can-outline"></span></button>
                                            </form>
                                        <?php
                                        } elseif ($status == 3) {
                                            echo 'Completed';
                                        } else {
                                        }
                                        ?>
                                    </td>
                                </tr>
                        <?php }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
<script>
    $('#timepicker').timepicker({
        minuteStep: 60,
        showMeridian: false,
        defaultTime: '00:00'
    });
</script>
<script>
    document.getElementById('uploadForm').addEventListener('submit', function() {
        var button = document.getElementById('uploadButton');
        button.disabled = true;
        button.innerHTML = 'Submitting...';
    });
</script>
<script>
    // Wait for the DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        var startDateInput = document.getElementById('start_date');
        var endDateInput = document.getElementById('end_date');

        // Listen for changes on the Start Date field
        startDateInput.addEventListener('change', function() {
            // Get the selected start date value
            var startDate = startDateInput.value;

            // If start date is selected, set the minimum value of the end date
            if (startDate) {
                // Set the minimum end date to be the same as the start date
                endDateInput.setAttribute('min', startDate);
            }
        });
    });
</script>