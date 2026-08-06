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
                Admin Leave By Date Range - <?php echo $start_date . ' - ' . $end_date; ?>
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
                            <th>Gender</th>
                            <th style="background:#F4F6F7 !important;">Ear. +</th>
                            <th style="background:#F4F6F7 !important;">Ear. -</th>

                            <th style="background:#DBFAB6 !important;">Med. +</th>
                            <th style="background:#DBFAB6 !important;">Med. -</th>

                            <th style="background:#DCDCDC !important;">Rest. +</th>
                            <th style="background:#DCDCDC !important;">Rest. -</th>

                            <th style="background:#E2EEFF !important;">Pat. +</th>
                            <th style="background:#E2EEFF !important;">Pat. -</th>

                            <th style="background:#FFE1AB !important;">Comp. +</th>
                            <th style="background:#FFE1AB !important;">Comp. -</th>

                            <th style="background:rgb(237, 196, 196) !important;">Mensural. -</th>
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
                                <td><?php if($leave['gender']==2) echo "Male"; else echo "Female"; ?></td>

                                <td style="text-align:right; background:rgb(204, 224, 255) !important;"><?php if ($leave['Earned'] > 0) echo $leave['Earned']; ?></td>
                                <td style="text-align:right"><?php if ($leave['Earned_N'] < 0) echo $leave['Earned_N']; ?></td>


                                <td style="text-align:right;  background:rgb(204, 224, 255) !important;"><?php if ($leave['Medical'] > 0) echo $leave['Medical']; ?></td>
                                <td style="text-align:right"><?php if ($leave['Medical_N'] < 0) echo $leave['Medical_N']; ?></td>

                                <td style="text-align:right;  background:rgb(204, 224, 255) !important;"><?php if ($leave['Restricted'] > 0) echo $leave['Restricted']; ?></td>
                                <td style="text-align:right"><?php if ($leave['Restricted_N'] < 0) echo $leave['Restricted_N']; ?></td>


                                <td style="text-align:right;  background:rgb(204, 224, 255) !important;"><?php if ($leave['Paternity'] > 0) echo $leave['Paternity']; ?></td>
                                <td style="text-align:right"><?php if ($leave['Paternity_N'] < 0) echo $leave['Paternity_N']; ?></td>

                                <td style="text-align:right;  background:rgb(204, 224, 255) !important;"><?php if ($leave['Compoff'] > 0) echo $leave['Compoff']; ?></td>
                                <td style="text-align:right"><?php if ($leave['Compoff_N'] < 0) echo $leave['Compoff_N']; ?></td>

                                <td style="text-align:right"><?php if ($leave['Casual'] < 0) echo $leave['Casual']; ?></td>

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