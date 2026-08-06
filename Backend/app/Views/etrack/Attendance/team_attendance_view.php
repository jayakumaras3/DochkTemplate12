<?php helper('localization'); ?>
<style>
    .rightalignment {
        text-align: right;
        margin-right: 1em;
    }

    .leftalignment {
        text-align: left;
        margin-left: 1em;
    }

    /* Style the table as needed */
</style>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <?php if ($return_page == 1) { ?>

                    <?php } ?>
                    <?php if ($return_page == 2) { ?>

                    <?php } ?>
                    <?php if ($return_page == 3) { ?>
                        <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/HR_admin'); ?>">
                                Admin HR Attendance
                            </a>
                        </li>
                    <?php } ?>
                    <?php if ($return_page == 4) { ?>
                        <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/Fin_admin'); ?>">
                                Finance Dashboard
                            </a>
                        </li>
                    <?php } ?>

                </ol>
            </div>
            <h4 class="page-title">
                <?php if ($return_page == 2) { ?>
                    Team Attendance <?php echo $start_date . ' : ' . $end_date; ?> | Working Days <?php echo $working_days; ?>
                <?php } else { ?>
                    Payroll Attendance <?php echo $start_date . ' : ' . $end_date; ?> | Working Days <?php echo $working_days; ?>
                <?php } ?>

            </h4>
        </div>
    </div>
</div>
<?php if ($return_page == 2) { ?>
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('etrack/attendance/team_attendance'); ?>" method="POST"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label for="month">Month</label>
                                <select class="form-select" name="month">
                                    <?php
                                    $thismonth = date('n');
                                    for ($i = 1; $i < 13; $i++) {
                                        $monthName = translated_month_name($i);

                                        echo '<option value="' . $i . '"';
                                        if ($i == $thismonth) {
                                            echo 'selected';
                                        }
                                        echo '>' . $monthName . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="year">Year</label>
                                <select class="form-select" name='year'>
                                    <?php
                                    $endyear = date('Y');
                                    for ($i = $endyear; $i >= 2025; $i--) {
                                        echo "<option value='$i'>$i</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4 mt-3">
                                <input type="submit" onclick="this.form.submit(); this.disabled=true;" class=" btn btn-outline-info btn-xs waves-effect waves-light" value="Show Data">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<div class="row">
    <?php //echo 'Working days' . $working_days; 
    ?>

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="background:#F4F6F7 !important;" class="center">#</th>
                            <th style="background:#F4F6F7 !important;">EID</th>
                            <th style="background:#F4F6F7 !important;">Manager</th>
                            <th style="background:#F4F6F7 !important;">Employee Name</th>
                            <th style="background:#F4F6F7 !important;">WFH</th>
                            <th style="background:#DCDCDC !important;">Leaves</th>
                            <th style="background:#FF0000 !important; color:#fff;">LWP</th>
                            <th style="background:#DBFAB6 !important;">IO</th>
                            <th style="background:#F4F6F7 !important;">Grase</th>
                            <th style="background:#F4F6F7 !important;">LOP</th>
                            <th style="background:#F4F6F7 !important;">TOTAL</th>
                            <th style="background:#F4F6F7 !important; width: 20%">Remarks</th>
                            <?php if ($return_page == 3) { ?>
                                <th style="background:#F4F6F7 !important;">Grase</th>
                            <?php } ?>
                            <?php if ($return_page == 2) { ?>
                                <th style="background:#F4F6F7 !important;">WFH</th>
                            <?php } ?>
                            <th style="background:#F4F6F7 !important;">Stat.</th>
                            <th style="background:#F4F6F7 !important;">Attend.</th>
                            <th style="background:#F4F6F7 !important;">AC</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $j = 0;
                        $sno = 0;
                        $counter = 0;
                        if (count($data_value['leaveData']) > 0) {
                            // Iterate over the leaveData to ensure we are consistent across all datasets.
                            foreach ($data_value['leaveData'] as $leaveData) {
                                $totalattendance = 0;
                                $hrs = 0;
                                $min = 0;
                                $leave = 0;
                                $val = 0;
                                $wfh = 0;
                                $leaveData_lwp = 0;
                                $j++;
                                if ($data_value['wfhData'][$j - 1]['emp_id'] == 0) {
                                    continue;
                                }
                                if ($data_value['wfhData'][$j - 1]['emp_id'] == 10077) {
                                    continue;
                                }
                                if ($data_value['wfhData'][$j - 1]['emp_id'] == 10111) {
                                    continue;
                                }
                                if ($data_value['wfhData'][$j - 1]['emp_id'] == 10335) {
                                    continue;
                                }
                                if ($data_value['wfhData'][$j - 1]['emp_id'] == 40032) {
                                    continue;
                                }
                                if ($data_value['wfhData'][$j - 1]['emp_id'] == 40033) {
                                    continue;
                                }
                                if ($data_value['wfhData'][$j - 1]['emp_id'] == 40034) {
                                    continue;
                                }
                                if ($data_value['wfhData'][$j - 1]['emp_id'] == 40031) {
                                    continue;
                                }
                                if ($data_value['wfhData'][$j - 1]['emp_id'] == 40035) {
                                    continue;
                                }
                                if ($data_value['wfhData'][$j - 1]['emp_id'] == 40036) {
                                    continue;
                                }
                                $sno++;
                                ?>
                                <tr>

                                    <td><?php echo $sno; ?></td>

                                    <td><?php echo $data_value['wfhData'][$j - 1]['emp_id']; ?></td>
                                    <td><?php echo $data_value['wfhData'][$j - 1]['manager_name']; ?></td>

                                    <td><?php echo $leaveData['name'] . ' ' . $leaveData['last_name']; ?></td>


                                    <?php $wfh = isset($data_value['wfhData'][$j - 1]['wfh']) ? $data_value['wfhData'][$j - 1]['wfh'] : '0';
                                    if ($wfh > 12) {
                                        echo '<td class="rightalignment" style="background-color:#ffa4a459 !important;">';
                                    } else {
                                        echo '<td class="rightalignment">';
                                    }

                                    if ($wfh > 0) {
                                        echo $wfh;
                                    }
                                    ?></td>
                                    <!-- Leaves Data Column -->
                                    <td class="rightalignment"><?php $leave = isset($leaveData['leaves']) ? -1 * $leaveData['leaves'] : '0';

                                                                if ($leave > 0) {
                                                                    echo $leave;
                                                                }
                                                                ?></td>
                                    <td class="rightalignment"><?php $leaveData_lwp = isset($leaveData_lwp['leaves']) ? -1 * $leaveData_lwp['leaves'] : '0';

                                                                if ($leaveData_lwp > 0) {
                                                                    echo $leaveData_lwp;
                                                                }
                                                                ?></td>

                                    <!-- Access Data Column -->
                                    <!--   <td class="rightalignment"><?php //echo isset($data_value['accessData'][$j - 1]['ac_data']) ? $data_value['accessData'][$j - 1]['ac_data'] : '0'; 
                                                                        ?></td>

                              
                                <td class="rightalignment">-->
                                    <?php
                                    $grase = 0;
                                    if (isset($data_value['accessData'][$j - 1]['ac_minx'])) {
                                        $hrs = floor($data_value['accessData'][$j - 1]['ac_minx'] / 60);
                                        $min =  ':' . ($data_value['accessData'][$j - 1]['ac_minx'] -   floor($data_value['accessData'][$j - 1]['ac_minx'] / 60) * 60);
                                        //  echo $hrs . $min;
                                    } else {
                                        // echo 0;
                                    }
                                    ?>
                                    <!--  </td> -->

                                    <td class="rightalignment"><?php
                                                                if (isset($data_value['accessData'][$j - 1]['ac_minx'])) {
                                                                    $hrs = floor($data_value['accessData'][$j - 1]['ac_minx'] / 60);

                                                                    $val = round(round(($hrs / 8), 1) * 2) / 2;
                                                                    echo $val;
                                                                } else {
                                                                    // echo 0;
                                                                }
                                                                ?>
                                    </td>


                                    <td class="rightalignment">
                                        <?php
                                        if (isset($data_value['gracedata'][$j - 1]['numgrace'])) {
                                            $grase = $data_value['gracedata'][$j - 1]['numgrace'];
                                            echo $grase;
                                        } else {
                                            $grase = 0;
                                            // echo 0;
                                        }
                                        ?>
                                    </td>


                                    <?php
                                    //echo $val.'-';
                                    $totalattendance = $val + $leave + $wfh + $grase;
                                    echo '<td class="rightalignment">';
                                    $lop = $working_days - $totalattendance;
                                    if ($lop > 0) {
                                        echo $lop;
                                    }
                                    echo '</td>';
                                    if ($totalattendance < $working_days) {
                                        echo '<td class="rightalignment" style="background-color:#ffa4a459 !important; ">';
                                    } else {
                                        echo '<td class="rightalignment">';
                                    }
                                    echo $totalattendance;

                                    ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (isset($data_value['wfhData'][$j - 1]['rema'])) {
                                            echo '{' . $data_value['wfhData'][$j - 1]['rema'] . '}';
                                        }
                                        if (isset($data_value['accessData'][$j - 1]['remax'])) {
                                            echo '{' . $data_value['accessData'][$j - 1]['remax'] . '}';
                                        }
                                        ?>
                                    </td>
                                    <?php if ($return_page == 3) { ?>
                                        <!-- Action Buttons -->
                                        <td class="rightalignment">
                                            <form class="form-horizontal" action="<?php echo base_url('etrack/HR_admin/apply_grase'); ?>" method="POST"><?= csrf_field() ?>
                                                <div class="row">
                                                    <input type="hidden" name="return_page" value="<?php echo $return_page; ?>">
                                                    <input type="hidden" name="temp_user" value="<?php echo $leaveData['id_user']; ?>">
                                                    <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                        <span class="mdi  mdi-archive-arrow-up-outline"></span>
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                    <?php } ?>
                                    <?php if ($return_page == 2) { ?>
                                        <td class="rightalignment">
                                            <form class="form-horizontal" action="<?php echo base_url('etrack/attendance/apply_wfh_team'); ?>" method="POST"><?= csrf_field() ?>
                                                <div class="row">
                                                    <input type="hidden" name="return_page" value="<?php echo $return_page; ?>">
                                                    <input type="hidden" name="temp_user" value="<?php echo $leaveData['id_user']; ?>">
                                                    <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                        WFH
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                    <?php } ?>
                                    <td style="width: 70px;" >
                                        <form class="form-horizontal" action="<?php echo base_url('etrack/attendance/wfh_statement_view'); ?>" method="POST"><?= csrf_field() ?>
                                            <div class="row">
                                                <input type="hidden" name="return_page" value="<?php echo $return_page; ?>">
                                                <input type="hidden" name="temp_user" value="<?php echo $leaveData['id_user']; ?>">
                                                <button type="submit" style="width:50px; margin-left: 10px;" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-warning waves-effect btn-xs waves-light">
                                                    Stat.
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                    <td style="width: 70px;" >
                                        <form class="form-horizontal" action="<?php echo base_url('etrack/attendance/view'); ?>" method="POST"><?= csrf_field() ?>
                                            <div class="row">
                                                <input type="hidden" name="return_page" value="<?php echo $return_page; ?>">
                                                <input type="hidden" name="temp_user" value="<?php echo $leaveData['id_user']; ?>">
                                                <button type="submit" style="width:50px; margin-left: 10px;" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-info waves-effect btn-xs waves-light">
                                                    Attd.
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                    <td style="width: 60px;" >
                                     
                                        <form class="form-horizontal" action="<?php echo base_url('etrack/attendance/team_single_user_ac'); ?>" method="POST"><?= csrf_field() ?>
                                            <div class="row">
                                                <input type="hidden" name="return_page" value="<?php echo $return_page; ?>">
                                                <input type="hidden" name="temp_user" value="<?php echo $leaveData['id_user']; ?>">
                                                <button type="submit" style="width:40px; margin-left: 10px;" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-danger waves-effect btn-xs waves-light">
                                                    AC
                                                </button>
                                            </div>
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
</div>