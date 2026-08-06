<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project/project_plan') ?>">Project plan</a></li>

                </ol>
            </div>
            <h4 class="page-title">Add Task Master - <?php echo $item_description; ?></h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 mb-2">
        <?php if ($prev_page) { ?>
            <form action="<?php echo base_url('Task/Task_master') ?>" method="POST"><?= csrf_field() ?>
                <input type="hidden" name="projectid" value="<?php echo $prev_page[0]['fk_course_id'] ?>">
                <input type="hidden" name="item_description" value="<?php echo $prev_page[0]['item_description'] ?>">
                <input type="hidden" name="dt_id" value="<?php echo $prev_page[0]['dt_id'] ?>">
                <button type="submit" alt="Next" class="" style="all: unset; cursor: pointer;"><i class="mdi mdi-arrow-left-circle-outline font-22"></i></button>
            </form>
        <?php } ?>
    </div>
    <div class="form-group col-md-7 mb-2">
        &nbsp; &nbsp; &nbsp; &nbsp;
    </div>
    <div class="form-group col-md-1 mb-2">
        <?php if ($next_page) { ?>
            <form action="<?php echo base_url('Task/Task_master') ?>" method="POST"><?= csrf_field() ?>
                <input type="hidden" name="projectid" value="<?php echo $next_page[0]['fk_course_id'] ?>">
                <input type="hidden" name="item_description" value="<?php echo $next_page[0]['item_description'] ?>">
                <input type="hidden" name="dt_id" value="<?php echo $next_page[0]['dt_id'] ?>">
                <button type="submit" alt="Next" style="all: unset; cursor: pointer;"><i class="mdi mdi-arrow-right-circle-outline font-22"></i></button>
            </form>
    </div>
<?php } ?>

</div>
<div class="row">
    <div class="col-3">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Task/Task_master/add_new_task') ?>" method="POST" id="submitForm"><?= csrf_field() ?>
                    <div class="col-12 ">
                        <div class="form-group mb-2">
                            <label>Description</label>
                            <textarea class="form-control" name="description" required></textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-2">
                            <label>Assign To</label>
                            <select class="form-select" name="assigned_to" required>
                                <option value=''>Select User</option>
                                <?php foreach ($usertable as $users) {
                                    echo '<option value="' . $users['id_user'];
                                    echo '">';
                                    echo $users['name'] . ' ' . $users['last_name'];
                                    echo '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group mb-2">
                                    <label>Effort in hours</label>
                                    <input class="form-control" type="number" min="0" name="effort" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" required>
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
                    <div class="col-12">
                        <div class="form-group mb-2">
                            <label>Courses</label>
                            <select class="form-select" name="course_id" required>
                                <?php
                                foreach ($getCourses as $eachgetCourses) {
                                    echo '<option value="' . $eachgetCourses['scourse_id'] . '">' . $eachgetCourses['course_name'] . '</opiton>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-2">
                            <label>Start Date</label>
                            <input class="form-control" id="start_date" name="start_date" type="date" value="" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-2">
                            <label>End Date</label>
                            <input class="form-control" id="end_date" name="end_date" type="date" value="" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-2">
                            <label>Priority</label>
                            <select class="form-select" name="priority" required>
                                <option value="High">High</option>
                                <option value="Medium">Medium</option>
                                <option value="Low">Low</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mt-2 d-grid">
                            <input type="hidden" name="project_id" value="<?php echo $projectid ?>">
                            <input type="hidden" name="dt_id" value="<?php echo $dt_id ?>">
                            <input type="hidden" name="type_of_task" value="4">
                            <button id="submitButton" class="btn btn-outline-danger waves-effect btn-sm waves-light">Assign Task</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <div class="col-9">
        <div class="card">
            <div class="card-body">
                <table id="alternative-page-datatable" class="table dt-responsive w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Description</th>
                            <th>Assigned</th>
                            <th>Effort</th>
                            <th>Course</th>
                            <th>Started</th>
                            <th>Ended</th>
                            <th>Priority</th>
                            <th>Del</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        foreach ($task_masters as $task) {
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
                                    }
                                    $actual =  $effort_hours . ':' . $Min;
                                }
                            }
                            $j = $j + 1; ?>
                            <tr>
                                <td><?php echo $j; ?> </td>
                                <td><?php echo $task['description']; ?></td>
                                <td><?php echo $task['assigned_to']; ?></td>
                                <td><?php echo ($actual) ? $actual : ''; ?></td>
                                <td><?php echo $task['course_name']; ?></td>
                                <td><?php echo date('m-d-Y', strtotime($task['start_date'])); ?></td>
                                <td><?php echo date('m-d-Y', strtotime($task['end_date'])); ?></td>
                                <td><?php if ($task['priority'] == 'High') {
                                        echo 'High';
                                    }
                                    if ($task['priority'] == 'Medium') {
                                        echo 'Medium';
                                    }
                                    if ($task['priority'] == 'Low') {
                                        echo 'Low';
                                    } ?></td>
                                <?php $allTaskIds = array_column($allTaskofCourse, 'master_task_id'); ?>
                                <td>
                                    <?php if (!in_array($task['mt_id'], $allTaskIds)) { ?>

                                        <form class="form-horizontal" action="<?php echo base_url('Task/Task_master/deleteMasterTask') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="mt_id" value="<?php echo $task['mt_id']; ?>">
                                            <input type="hidden" name="return_url" value="2">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')"><span class="mdi mdi-delete"></span></button>
                                        </form>

                                    <?php } else {
                                    } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('submitForm').addEventListener('submit', function() {
        var button = document.getElementById('submitButton');
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