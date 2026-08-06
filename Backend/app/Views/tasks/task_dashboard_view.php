<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css">
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/dashboard'); ?>">
                            Dashboard
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">My Tasks</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">To Do</h4>
                <ul class="sortable-list tasklist list-unstyled" id="upcoming">
                    <?php
                    if ($to_do) {
                        foreach ($to_do as $to_do_list) {
                    ?>
                            <li id="task1">
                                <?php
                                $priority = $to_do_list['priority'];
                                if ($priority == 'High') {
                                    echo '<span class="badge bg-soft-danger text-danger float-end">High</span>';
                                }
                                if ($priority == 'Medium') {
                                    echo '<span class="badge bg-soft-warning text-warning float-end">Medium</span>';
                                }
                                if ($priority == 'Low') {
                                    echo '<span class="badge bg-soft-success text-success float-end">Low</span>';
                                }
                                ?>
                                <h5 class="mt-0">
                                    <div class="text-dark">
                                        <?php
                                        $type = $to_do_list['type'];
                                        echo $to_do_list['course_name'];
                                        ?>
                                    </div>
                                </h5>
                                <p>
                                    <?php
                                    echo $to_do_list['description'];
                                    ?>
                                </p>
                                <div class="clearfix"></div>
                                <div class="row">
                                    <div class="col">
                                    <p class="font-13 mt-2 mb-0">
                                            <b>Assigned By :</b> <?php echo $to_do_list['taskcreatedby']; ?>
                                        </p>
                                        <p class="font-13 mt-2 mb-0">
                                            <?php
                                            echo 'Due on: ' . date('m-d-Y', strtotime($to_do_list['due_date']));
                                            ?>
                                        </p>
                                        <p class="font-13 mt-2 mb-0">
                                            <?php
                                            $actual = '';

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
                                                    }
                                                    $actual =  $effort_hours . ':' . $Min;
                                                }
                                            }
                                            // if (abs($to_do_list['effort']) < 1) {
                                            //     echo 'Planned Effort: ' . $actual . ' Mins';
                                            // } else {
                                            //     echo 'Planned Effort: ' . $actual . ' Hrs';
                                            // }
                                            // ?>
                                        </p>
                                    </div>
                                    <div class="col-auto">
                                        <div class="text-end">

                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="text-end ">
                                            <form class="form-horizontal" action="<?php echo base_url('Task/Task_manage/change_task_status') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="task_id" value="<?php echo $to_do_list['task_id']; ?>">
                                                <input type="hidden" name="status" value="2">
                                                <button type="submit" class="btn btn-outline-warning btn-xs  rounded-pill waves-effect waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_013') ?>')">Start</button>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </li>

                    <?php
                        }
                    }
                    ?>

            </div>
        </div>
    </div> <!-- end col -->

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">In Progress</h4>
                <ul class="sortable-list tasklist list-unstyled" id="upcoming">
                    <?php
                    if ($in_progress) {
                        foreach ($in_progress as $in_progress_list) {
                    ?>
                            <li id="task1">
                                <?php
                                $priority = $in_progress_list['priority'];
                                $type = $in_progress_list['type'];
                                if ($priority == 'High') {
                                    echo '<span class="badge bg-soft-danger text-danger float-end">High</span>';
                                }
                                if ($priority == 'Medium') {
                                    echo '<span class="badge bg-soft-warning text-warning float-end">Medium</span>';
                                }
                                if ($priority == 'Low') {
                                    echo '<span class="badge bg-soft-success text-success float-end">Low</span>';
                                }
                                ?>
                                <h5 class="mt-0">
                                    <div href="javascript: void(0);" class="text-dark">
                                        <?php
                                        echo $in_progress_list['course_name'];
                                        ?>
                                    </div>
                                </h5>
                                <p> <?php
                                    echo $in_progress_list['description'];
                                    ?></p>
                                <div class="clearfix"></div>
                                <div class="row">
                                    <div>
                                        <p class="font-13 mt-2 mb-0">
                                            <b>Assigned By :</b> <?php echo $in_progress_list['taskcreatedby']; ?>
                                        </p>
                                        <p class="font-13 mt-2 mb-0">
                                            <?php
                                            echo  '<b>Due on : </b>' . date('m-d-Y', strtotime($in_progress_list['due_date']));
                                            ?>
                                        </p>

                                    </div>

                                    <div class="popup" data-bs-toggle="modal" data-bs-target="#win-1"><button class="btn btn-outline-success btn-xs waves-effect waves-light"><span class="mdi mdi-pencil-outline"></span>Update Efforts</button></div>
                                    <div id="win-1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
                                        <!-- <div class="modal fade bs-example-modal-lg1" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;"> -->
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="standard-modalLabel">Update Efforts</h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="col-md-6 mb-2">

                                                        <p class="font-13 mt-2 mb-0">
                                                            <?php
                                                            $actual = '';

                                                            if (isset($in_progress_list['effort'])) {
                                                                $effort  =  explode('.', $in_progress_list['effort']);

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
                                                            // if (abs($in_progress_list['effort']) < 1) {
                                                            //     echo 'Planned Effort: ' . $actual . ' Mins';
                                                            // } else {
                                                            //     echo '<b>Planned Effort: </b>' . $actual . ' Hrs';
                                                            // }
                                                            ?>
                                                        </p>
                                                        <p class="font-13 mt-2 mb-0">
                                                            <b>Assigned By :</b> <?php echo $in_progress_list['taskcreatedby']; ?>
                                                        </p><br />
                                                        <form class="form-horizontal" action="<?php echo base_url('Task/Task_manage/change_task_status') ?>" method="POST"><?= csrf_field() ?>
                                                            <!-- <input type="number" name="actual_effort" class="form-control mb-2" required> -->

                                                            <div class="row">

                                                                <div class="col-6">
                                                                    <input type="number" name="actual_effort" placeholder="Enter Actual Effort" class="form-control mb-2" min="0" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" required>
                                                                </div>
                                                                <div class="col-4">
                                                                    <select name="effort_min" class="form-control" required>
                                                                        <option value=".0">00</option>
                                                                        <option value=".25">15</option>
                                                                        <option value=".5">30</option>
                                                                        <option value=".75">45</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-10">
                                                                    <input type="text" name="remark" placeholder="Remark" class="form-control mb-2">
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="task_id" value="<?php echo $in_progress_list['task_id']; ?>">
                                                            <input type="hidden" name="status" value="3">
                                                            <div class="col-md-2">
                                                                <button type="submit" class="btn btn-outline-danger btn-xs  rounded-pill waves-effect waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_014') ?>')">Completed</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <p class="font-13 mt-2 mb-0">
                                            <?php
                                            $actual = '';

                                            if (isset($in_progress_list['effort'])) {
                                                $effort  =  explode('.', $in_progress_list['effort']);

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
                                            // if (abs($in_progress_list['effort']) < 1) {
                                            //     echo 'Planned Effort: ' . $actual . ' Mins';
                                            // } else {
                                            //     echo 'Planned Effort: ' . $actual . ' Hrs';
                                            // }
                                            ?>
                                        </p> -->

                                        <!-- <div class="col-auto">
                                            <div class="text-end">

                                                <form class="form-horizontal" action="<?php echo base_url('Task/Task_manage/change_task_status') ?>" method="POST"><?= csrf_field() ?>
                                                    <div class="row">
                                                        <div class="col-8">
                                                            <input type="number" name="actual_effort" placeholder="Enter Actual Effort" class="form-control mb-2" min="0" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" required>
                                                        </div>
                                                        <div class="col-3">
                                                            <select name="effort_min" class="form-control" required>
                                                                <option value=".0">00</option>
                                                                <option value=".25">15</option>
                                                                <option value=".5">30</option>
                                                                <option value=".75">45</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="task_id" value="<?php echo $in_progress_list['task_id']; ?>">
                                                    <input type="hidden" name="status" value="3">
                                                    <button type="submit" class="btn btn-outline-danger btn-xs  rounded-pill waves-effect waves-light" onclick="return confirm('Are you sure !! Do you want to Submit this Task completed?')">Completed</button>
                                                </form>
                                            </div>
                                        </div> -->
                                    </div>
                            </li>

                    <?php
                        }
                    }
                    ?>

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
<script>
    $('#timepicker').timepicker({
        minuteStep: 60,
        showMeridian: false,
        defaultTime: '00:00'
    });
</script>