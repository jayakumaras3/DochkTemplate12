<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/leaveadmin'); ?>">
                            Admin Leaves
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                e-Track Leave Team Lead View
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table  class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Emp ID</th>
                            <th>Employee Name</th>
                            <th style="background:#F4F6F7 !important;">Earned +</th>
                            <th style="background:#F4F6F7 !important;">Earned -</th>
                            <th style="background:#DBFAB6 !important;">Casual +</th>
                            <th style="background:#DBFAB6 !important;">Casual -</th>
                            <th style="background:#DCDCDC !important;">Restriced +</th>
                            <th style="background:#DCDCDC !important;">Restriced -</th>
                            <th style="background:#E2EEFF !important;">Partenity +</th>
                            <th style="background:#E2EEFF !important;">Paternity -</th>
                            <th style="background:#FFE1AB !important;">Compoff +</th>
                            <th style="background:#FFE1AB !important;">Compoff -</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        foreach ($getetLeavesData as $leave) {
                            $j++;
                        ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo $leave['emp_id']; ?></td>
                                <td><?php echo $leave['name'] . ' ' . $leave['last_name']; ?></td>

                                <td><?php echo $leave['Earned']; ?></td>
                                <td><?php echo $leave['Earned_N']; ?></td>

                                <td><?php echo $leave['Medical']; ?></td>
                                <td><?php echo $leave['Medical_N']; ?></td>

                                <td><?php echo $leave['Restricted']; ?></td>
                                <td><?php echo $leave['Restricted_N']; ?></td>

                                <td><?php echo $leave['Paternity']; ?></td>
                                <td><?php echo $leave['Paternity_N']; ?></td>

                                <td><?php echo $leave['Compoff']; ?></td>
                                <td><?php echo $leave['Compoff_N']; ?></td>

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