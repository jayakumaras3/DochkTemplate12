<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Task/Task_manage/team_tasks') ?>">Team Tasks</a></li>
                </ol>
            </div>
            <h4 class="page-title">Completed Task View (<?php echo $emplname; ?>)</h4>
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
                            <th>ID</th>
                            <th>Course</th>
                            <th>Description</th>
                            <th>Planned</th>
                            <th>Actual</th>
                            <th>Due Date</th>
                            <th>Completed</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($completed)) {
                            foreach ($completed as $to_do_list) {
                                if (isset($to_do_list['effort'])) {
                                    $eeffort  =  explode('.', $to_do_list['effort']);

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
                                    $actual_effort  =  explode('.', $to_do_list['actual_effort']);

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
                                $effort_value = ($to_do_list['effort'] != '') ? $effort : "";
                                $actual_effort_value = ($to_do_list['actual_effort'] != '') ? $actual : "";
                                echo '<tr>';
                                echo '<td>' . $to_do_list['task_id'] . '</td>';
                                echo '<td>' . $to_do_list['course_name'] . '</td>';
                                echo '<td>' . $to_do_list['description'] . '</td>';
                                echo '<td>' . $effort_value . '</td>';
                                echo '<td>' .   $actual_effort_value  . '</td>';
                                echo '<td>' . substr($to_do_list['due_date'], 5) . '</td>';
                                echo '<td>' . date('m-d', $to_do_list['completed_on']) . '</td>';
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