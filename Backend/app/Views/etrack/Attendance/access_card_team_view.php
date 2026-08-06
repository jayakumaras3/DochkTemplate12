<?php helper('localization'); ?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">

                <ol class="breadcrumb m-0">
                    <?php if ($return_page == 1) { ?>
                        <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/attendance'); ?>">
                                Attendance Dashboard
                            </a>
                        </li>
                    <?php } ?>
                    <?php if ($return_page == 2) { ?>
                        <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/attendance/team_attendance'); ?>">
                                Team Attendance
                            </a>
                        </li>
                    <?php } ?>
                    <?php if ($return_page == 3) { ?>
                        <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/HR_admin/payroll_report'); ?>">
                                Payroll Attendance
                            </a>
                        </li>
                    <?php } ?>
                    <?php if ($return_page == 4) { ?>
                        <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/Fin_admin/payroll_report'); ?>">
                                Finance Dashboard
                            </a>
                        </li>
                    <?php } ?>

                </ol>

            </div>
            <h4 class="page-title">
                Access Card Team Data - <?php echo $user_details[0]['name'] . ' ' . $user_details[0]['last_name']; ?>
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/attendance/team_single_user_ac'); ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label for="month">Month</label>
                            <select class="form-select" name="month">
                                <?php
                                for ($i = 1; $i < 13; $i++) {
                                    $monthName = translated_month_name($i);
                                    $thismonth = date('n');
                                    echo '<option value="' . $i . '"';
                                    if ($i == $thismonth) {
                                        if (date('d') < 26) {
                                            echo 'selected';
                                        } else {
                                            if ($i == 12) {
                                                echo '';
                                            } else {
                                                if ($i == $thismonth + 1) {
                                                    echo 'selected';
                                                }
                                            }
                                        }
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
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Day</th>
                            <th>In</th>
                            <th>Out</th>
                            <th>Total</th>
                            <th>Break</th>
                            <th>Actual</th>
                            <th>Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        foreach ($access_card as $data) {
                            $j++;
                        ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo $data['start_date']; ?></td>
                                <td><?php echo $data['timein']; ?></td>
                                <td><?php echo $data['timeout']; ?></td>
                                <td><?php echo $data['totalhrs']; ?></td>
                                <td><?php echo $data['breakhr']; ?></td>
                                <td><?php echo $data['actualhr']; ?></td>
                                <?php $remarks = $data['remarks'];
                                echo '<td>' . $remarks . '</td>';
                                ?>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>