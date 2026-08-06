
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Task/Task_manage/my_task') ?>">Task Allocation</a></li>
                </ol>
            </div>
            <h4 class="page-title">My Effort By Date</h4>
        </div>
    </div>
</div>
<div class="row">
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
                            <th>Remarks</th>
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

                                        // $decimalTime = $data1['effort'];
                                        // $totalMinutes = $decimalTime * 60;
                                        // $hours = floor($totalMinutes / 60);
                                        // $minutes = $totalMinutes % 60;
                                        // $formattedTime = date("H:i", mktime(0, $totalMinutes, 0));
                                        $actual = explode('.', $data1['effort']);
                                        if (!empty($actual[1])) {
                                            if ($actual[1] == 25) {
                                                echo $actual[0] . '.15';
                                            } elseif ($actual[1] == 5) {
                                                echo  $actual[0] . '.30';
                                            } elseif ($actual[1] == 75) {
                                                echo  $actual[0] . '.45';
                                            } else {
                                                echo $data1['effort'];
                                            }
                                        } else {
                                            echo $data1['effort'];
                                        }
                                        ?></td>

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
</div>