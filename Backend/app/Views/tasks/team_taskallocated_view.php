<?php
$skills = array();
foreach ($department_list as $data) {
    $skills[$data['value']] = $data['name'];
}
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Task Allocation</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table class="table  table-sm table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Project</th>
                            <th>Skill</th>
                            <th>Stage</th>
                            <th>Date</th>
                            <th>Effort (Hr)</th>
                            <th>Effort (Min)</th>
                            <th>Remarks</th>
                            <th>Save</th>

                            <th width=5%>View</th>
                            <th width=5%>Close</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $j = 0;
                        foreach ($active_tasks as $task) {
                            $j = $j + 1; ?>
                            <tr>
                                <td><?php echo $j ?></td>
                                <td><?php echo $task['projectname'] ?></td>
                                <td><?php echo $skills[$task['skill_id']];
                                    ?></td>
                                <td><?php $stage = $task['stage'];
                                    switch ($stage) {
                                        case 1:
                                            echo 'Alpha';
                                            break;
                                        case 2:
                                            echo 'Beta';
                                            break;
                                        case 5:
                                            echo 'Gamma';
                                            break;
                                        case 0:
                                            echo 'Gen';
                                            break;
                                    }
                                    ?></td>
                                <form class="form-horizontal" action="<?php echo base_url('Task/Task_manage/employee_add_effort') ?>" method="POST"><?= csrf_field() ?>
                                    <td>
                                        <input id="start_date" name="start_date" class="date-picker form-control"
                                            placeholder="yyyy-mm-dd" type="text"
                                            onfocus="enableDatePicker(this)" onclick="enableDatePicker(this)"
                                            onblur="this.type='text'" onmouseout="timeFunctionLong(this)"
                                            required value="">
                                        <script>
                                            function enableDatePicker(input) {
                                                input.type = 'date';

                                                const today = new Date();
                                                let validDates = [];
                                                let checkDate = new Date(today);

                                                // Get last 3 weekdays (skip weekends)
                                                while (validDates.length < 3) {
                                                    const day = checkDate.getDay(); // 0 = Sun, 6 = Sat
                                                    if (day !== 0 && day !== 6) {
                                                        validDates.push(new Date(checkDate));
                                                    }
                                                    checkDate.setDate(checkDate.getDate() - 1);
                                                }

                                                // Sort just in case
                                                validDates.sort((a, b) => a - b);

                                                const formatDate = (date) => {
                                                    const y = date.getFullYear();
                                                    const m = String(date.getMonth() + 1).padStart(2, '0');
                                                    const d = String(date.getDate()).padStart(2, '0');
                                                    return `${y}-${m}-${d}`;
                                                };

                                                const minDate = validDates[0];
                                                const maxDate = validDates[validDates.length - 1];

                                                input.min = formatDate(minDate);
                                                input.max = formatDate(maxDate);

                                                input.addEventListener('change', function() {
                                                    const selectedDate = new Date(this.value);
                                                    selectedDate.setHours(0, 0, 0, 0); // Normalize

                                                    const isValid = validDates.some(date => {
                                                        date.setHours(0, 0, 0, 0);
                                                        return date.getTime() === selectedDate.getTime();
                                                    });

                                                    if (!isValid) {
                                                        alert("Please select a valid weekday within the last 3 working days.");
                                                        this.value = "";
                                                    }
                                                });
                                            }

                                            function timeFunctionLong(input) {
                                                setTimeout(function() {
                                                    input.type = 'text';
                                                }, 60000);
                                            }
                                        </script>

                                    </td>
                                    <td>
                                        <select name="hrs_val" class="form-control">
                                            <?php
                                            for ($x = 0; $x <= 23; $x++) {
                                                echo '<option value="' . $x . '">' . $x . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td>

                                        <select name="min_val" class="form-control">
                                            <option value=".0">00</option>
                                            <option value=".25">15</option>
                                            <option value=".5">30</option>
                                            <option value=".75">45</option>
                                        </select>

                                    </td>
                                    <td><input type="text" class="form-control" name="remarks" value=""></td>
                                    <td>
                                        <input type="hidden" name="ucn_tl_id" value="<?php echo $task['ucn_tl_id']; ?>">
                                        <input type="hidden" name="stage" value="<?php echo $task['stage']; ?>">
                                        <input type="hidden" name="project_id" value="<?php echo $task['project_id']; ?>">
                                        <input type="hidden" name="ucn_id" value="<?php echo $task['ucn_id']; ?>">
                                        <input type="hidden" name="skill_Id" value="<?php echo $task['skill_id']; ?>">
                                        <input type="hidden" name="ucn_mst_id" value="<?php echo $task['ucn_mst_id']; ?>">
                                        <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light">
                                            Save
                                        </button>
                                    </td>
                                </form>

                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url('Task/Task_manage/employee_brkdown_effort') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="returnid" value="2">
                                        <input type="hidden" name="ucn_tl_id" value="<?php echo $task['ucn_tl_id']; ?>">
                                        <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-eye-outline"></span></button>
                                    </form>
                                </td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url('Task/Task_manage/close_my_assigned_task') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="ucn_tl_id" value="<?php echo $task['ucn_tl_id']; ?>">
                                        <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_009') ?>')"><span class="mdi mdi-window-close"></span></button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
        </div><!-- /.row -->
    </div><!-- /.row -->
</div><!-- /.row -->

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4><?php echo $todaydt . ' (';
                    echo date('l', strtotime($todaydt)) . ')'; ?></h4>
                <table class="table">
                    <thead>
                        <tr>
                            <td>#</td>
                            <td>Project</td>
                            <td>Effort</td>
                            <td>Remarks</td>
                            <td>Delete</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sno = 0;
                        if ($getdata_1) {
                            foreach ($getdata_1 as $data1) {
                                $sno++;
                        ?>
                                <tr>
                                    <td><?php echo $sno; ?></td>
                                    <td><?php echo $data1['projectname']; ?></td>

                                    <td><?php
                                        $decimalTime = $data1['effort'];
                                        $totalMinutes = $decimalTime * 60;
                                        $hours = floor($totalMinutes / 60);
                                        $minutes = $totalMinutes % 60;
                                        $formattedTime = date("H:i", mktime(0, $totalMinutes, 0));
                                        echo $formattedTime; ?></td>
                                    <td><?php echo $data1['remarks']; ?></td>
                                    <td>
                                        <?php $effortDate = $data1['date_value'];
                                        $today = new DateTime();
                                        $validDates = [];
                                        $checkDate = clone $today;

                                        while (count($validDates) < 3) {
                                            $dayOfWeek = $checkDate->format('N'); // 1 (Mon) to 7 (Sun)
                                            if ($dayOfWeek < 6) {
                                                $validDates[] = $checkDate->format('Y-m-d');
                                            }
                                            $checkDate->modify('-1 day');
                                        }

                                        // Check if effort date is within allowed range
                                        if (!in_array($effortDate, $validDates)) {
                                        } else { ?>
                                            <form class="form-horizontal" action="<?php echo base_url('Task/Task_manage/delete_effort') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="ucn_emp_id" value="<?php echo $data1['ucn_emp_id']; ?>">
                                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_010') ?>')"><span class="mdi mdi-trash-can-outline"></span></button>
                                            </form>
                                        <?php } ?>
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

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4><?php echo $previous_dt . ' (';
                    echo date('l', strtotime($previous_dt)) . ')'; ?></h4>

                <table class="table">
                    <thead>
                        <tr>
                            <td>#</td>
                            <td>Project</td>
                            <td>Effort</td>
                            <td>Remarks</td>
                            <td>Delete</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sno = 0;
                        if ($getdata_2) {
                            foreach ($getdata_2 as $data1) {
                                $sno++;
                        ?>
                                <tr>
                                    <td><?php echo $sno; ?></td>
                                    <td><?php echo $data1['projectname']; ?></td>


                                    <td><?php
                                        $decimalTime = $data1['effort'];
                                        $totalMinutes = $decimalTime * 60;
                                        $hours = floor($totalMinutes / 60);
                                        $minutes = $totalMinutes % 60;
                                        $formattedTime = date("H:i", mktime(0, $totalMinutes, 0));
                                        echo $formattedTime; ?></td>
                                    <td><?php echo $data1['remarks']; ?></td>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url('Task/Task_manage/delete_effort') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="ucn_emp_id" value="<?php echo $data1['ucn_emp_id']; ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_010') ?>')"><span class="mdi mdi-trash-can-outline"></span></button>
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

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">

                <h4><?php echo $previous_dt_min . ' (';
                    echo date('l', strtotime($previous_dt_min)) . ')'; ?></h4>
                <table class="table">
                    <thead>
                        <tr>
                            <td>#</td>
                            <td>Project</td>
                            <td>Effort</td>
                            <td>Remarks</td>
                            <td>Delete</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sno = 0;
                        if ($getdata_3) {
                            foreach ($getdata_3 as $data1) {
                                $sno++;
                        ?>
                                <tr>
                                    <td><?php echo $sno; ?></td>
                                    <td><?php echo $data1['projectname']; ?></td>
                                    <td><?php
                                        $decimalTime = $data1['effort'];
                                        $totalMinutes = $decimalTime * 60;
                                        $hours = floor($totalMinutes / 60);
                                        $minutes = $totalMinutes % 60;
                                        $formattedTime = date("H:i", mktime(0, $totalMinutes, 0));
                                        echo $formattedTime;
                                        ?></td>
                                    <td><?php echo $data1['remarks']; ?></td>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url('Task/Task_manage/delete_effort') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="ucn_emp_id" value="<?php echo $data1['ucn_emp_id']; ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')"><span class="mdi mdi-trash-can-outline"></span></button>
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

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <p>Search By Date</p>
                <form class="form-horizontal" action="<?php echo base_url('Task/Task_manage/search_effort_by_date') ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <input id="start_date" name="start_date" class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="" required>
                                <script>
                                    function timeFunctionLong(input) {
                                        setTimeout(function() {
                                            input.type = 'text';
                                        }, 60000);
                                    }
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-1">
                                <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light">
                                    View Data
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>