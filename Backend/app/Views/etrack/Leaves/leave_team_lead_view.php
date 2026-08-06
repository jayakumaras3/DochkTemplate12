<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/leaves'); ?>">
                            Leave Dashboard
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                Team Leaves
            </h4>
        </div>
    </div>
</div>
<?php if (count($getcompoffApproval) > 0) { ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="center">#</th>
                                <th>Requested By</th>
                                <th>Requested On</th>
                                <th>Number of Leaves</th>
                                <th>Reason</th>
                                <th>Approve</th>
                                <th>Reject</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $k = 0;
                            foreach ($getcompoffApproval as $compoff) {
                                $k++;
                            ?>
                                <tr>
                                    <td><?php echo $k; ?></td>
                                    <td><?php echo $compoff['emp_name']; ?></td>
                                    <td><?php echo date('Y-m-d', $compoff['last_updated_on']); ?></td>
                                    <td><?php echo $compoff['number_leave']; ?></td>
                                    <td><?php echo $compoff['remarks']; ?></td>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url('etrack/leaves/approve_compoff'); ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="leaveid" value="<?php echo $compoff['et_le_id']; ?>">
                                            <button onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-success btn-xs waves-effect waves-light">Approve</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url('etrack/leaves/reject_compoff'); ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="leaveid" value="<?php echo $compoff['et_le_id']; ?>">
                                            <button onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-danger btn-xs waves-effect waves-light">Reject</button>
                                        </form>
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
<?php } ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="center">#</th>

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
                            <th>Statement</th>
                            <th>Apply</th>
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
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url('etrack/leaves/team_single_user_statement'); ?>" method="POST"><?= csrf_field() ?>
                                       
                                            <input type="hidden" name="user_id" value="<?php echo $leave['id_user']; ?>">
                                            <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-primary btn-xs waves-effect waves-light">
                                                Stat.
                                            </button>
                                   
                                    </form>
                                </td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url('etrack/leaves/team_single_user_leaves'); ?>" method="POST"><?= csrf_field() ?>
                                        
                                            <input type="hidden" name="user_id" value="<?php echo $leave['id_user']; ?>">
                                            <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-danger waves-effect btn-xs waves-light">
                                                Apply
                                            </button>
                                       
                                    </form>
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