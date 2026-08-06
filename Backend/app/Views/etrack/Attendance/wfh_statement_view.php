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
                Work From Home Statement <?php if ($return_page != 1) {
                                                echo '(' . $user_details[0]['name'] . ' ' . $user_details[0]['last_name'] . ')';
                                            } ?> <?php echo $start_date . ' - ' . $end_date; ?>
            </h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <?php if ($return_page == 1) { ?>
                    <form class="form-horizontal" action="<?php echo base_url('etrack/attendance/wfh_statement_view/1'); ?>" method="POST"><?= csrf_field() ?>
                    <?php } elseif ($return_page == 2) { ?>
                        <form class="form-horizontal" action="<?php echo base_url('etrack/attendance/wfh_statement_view/2'); ?>" method="POST"><?= csrf_field() ?>
                        <?php } elseif ($return_page == 3) { ?>
                            <form class="form-horizontal" action="<?php echo base_url('etrack/attendance/wfh_statement_view/3'); ?>" method="POST"><?= csrf_field() ?>
                            <?php } ?>


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
                                    <input type="hidden" name="temp_user" value="<?php echo $temp_user; ?>">
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
                            <th>Num Work From Home</th>
                            <th>status</th>
                            <th>Remarks</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        foreach ($wfh_statement as $wfh) {
                            $j++;
                        ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo $wfh['start_date']; ?></td>
                                <td><?php echo $wfh['number_wfh']; ?></td>
                                <td><?php $status = $wfh['status'];
                                    if ($status == 0) {
                                        echo 'Deleted';
                                    } else {
                                        //  echo 'Active';
                                    } ?></td>

                                <?php $remarks = $wfh['remarks'];
                                if (strlen($remarks) > 0) {
                                    echo  '<td>' . $remarks . '</td>';
                                ?>

                                    <td>
                                        <?php if ($status != 0) {
                                            if ($return_page != 4) { ?>
                                                <form class="form-horizontal" action="<?php echo base_url('etrack/attendance/delete_wfh_remarks'); ?>" method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="et_wfh_id" value="<?php echo $wfh['et_wfh_id']; ?>">
                                                    <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-trash-can-outline"></span></button>
                                                </form>
                                        <?php }
                                        } ?>
                                    </td>
                                    <?php
                                } elseif ($status != 0) {
                                    if ($return_page != 4) { ?>

                                        <form class="form-horizontal" action="<?php echo base_url('etrack/attendance/add_wfh_remarks'); ?>" method="POST"><?= csrf_field() ?>
                                            <td style="width: 240px;">
                                                <input type="text" required name="remarks" value="" class="form-control">
                                            </td>
                                            <td>
                                                <input type="hidden" name="et_wfh_id" value="<?php echo $wfh['et_wfh_id']; ?>">
                                                <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-card-plus-outline"></span></button>
                                            </td>
                                        </form>
                                <?php
                                    } else {
                                        echo '<td></td><td></td>';
                                    }
                                } else {
                                    echo '<td></td><td></td>';
                                }
                                ?>
                                <td>
                                    <?php
                                    if ($return_page == 2 || $return_page == 3) {
                                        if ($status != 0) {
                                    ?>
                                            <form class="form-horizontal" action="<?php echo base_url('etrack/attendance/delete_wfh'); ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="et_wfh_id" value="<?php echo $wfh['et_wfh_id']; ?>">
                                                <button class="btn btn-outline-info btn-xs waves-effect waves-light">Delete</button>
                                            </form>
                                            <?php
                                        }
                                    } else {
                                        $date = strtotime(date("Y-m-d", strtotime("-5 day")));
                                        if ($return_page != 4) {
                                            if ($wfh['start_date'] > date('Y-m-d', $date)) {
                                                if ($status != 0) { ?>
                                                    <form class="form-horizontal" action="<?php echo base_url('etrack/attendance/delete_wfh'); ?>" method="POST"><?= csrf_field() ?>
                                                        <input type="hidden" name="et_wfh_id" value="<?php echo $wfh['et_wfh_id']; ?>">
                                                        <button class="btn btn-outline-info btn-xs waves-effect waves-light">Delete</button>
                                                    </form>
                                    <?php }
                                            }
                                        }
                                    } ?>

                                </td>
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