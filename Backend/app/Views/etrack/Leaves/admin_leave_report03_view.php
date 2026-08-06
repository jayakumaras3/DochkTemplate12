<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/leaveadmin'); ?>">
                            Admin Leave Dashboard
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                Admin Leave Balance Report - <?php echo $start_date . ' - ' . $end_date; ?>
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped ">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Emp ID</th>
                            <th>Employee Name</th>
                            <th style="background:#F4F6F7 !important;" width="150">Earned</th>
                            <th style="background:#DBFAB6 !important;" width="150">Casual</th>
                            <th style="background:#DCDCDC !important;" width="150">Restricted</th>
                            <th style="background:#E2EEFF !important;" width="150">Paternity</th>
                            <th style="background:#FFE1AB !important;" width="150">Compoff</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        $k = -1;
                        foreach ($getetLeavesData as $leave) {
                            if ($leave['emp_id'] == '') {
                                continue;
                            }
                            $j++;
                        ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo $leave['emp_id']; ?></td>
                                <td><?php echo $leave['name'] . ' ' . $leave['last_name']; ?></td>
                                <td style="text-align:right"><?php $total1 = ($leave['Earned'] + $leave['Earned_N']); if($total1 > 0) echo $total1; ?></td>
                                <td style="text-align:right"><?php $total2 = ($leave['Medical'] + $leave['Medical_N']); if($total2 > 0) echo $total2; ?></td>
                                <td style="text-align:right"><?php $total3 = ($leave['Restricted'] + $leave['Restricted_N']); if($total3 > 0) echo $total3; ?></td>
                                <td style="text-align:right"><?php $total4 = ($leave['Paternity'] + $leave['Paternity_N']); if($total4 > 0) echo $total4; ?></td>
                                <td style="text-align:right"><?php $total5 = ($leave['Compoff'] + $leave['Compoff_N']); if($total5 > 0) echo $total5; ?></td>

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