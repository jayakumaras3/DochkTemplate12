<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/leaves/team_leaves'); ?>">
                            Team Leaves
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                Team Leave Statement - <?php echo $user_details[0]['name'] . ' ' . $user_details[0]['last_name']; ?>
            </h4>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/leaves/team_single_user_statement'); ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-4">

                            <select class="form-select" name='year'>
                                <?php
                                $endyear = date('Y');
                                for ($i = $endyear; $i >= 2025; $i--) {
                                    echo "<option value='$i'>$i</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 mt-3 mt-md-0">
                            <button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">
                                Change Year
                            </button>
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
                <table id="searchdatatable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Leave Type</th>
                            <th>Added</th>
                            <th>Applied</th>
                            <th>Date</th>
                            <!-- <th>Expiry</th> -->
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Updated By</th>
                            <th>Updated On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        foreach ($leave_statement as $leave) {
                            $year = date('Y');        //last year
                            $last_month = date('m', strtotime("-1 month"));       //last month
                            $lastMonth25 = $year . '-' . $last_month . '-25';
                            $j++;
                        ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php $type = $leave['type'];
                                    switch ($type) {
                                        case 1:
                                            echo 'WFH';
                                            break;
                                        case 2:
                                            echo 'Earned';
                                            break;
                                        case 3:
                                            echo 'Casual';
                                            break;
                                        case 4:
                                            echo 'Restricted';
                                            break;
                                        case 5:
                                            echo 'Paternity';
                                            break;
                                        case 6:
                                            echo 'Compoff';
                                            break;
                                        case 7:
                                            echo 'Earned';
                                            break;
                                        case 8:
                                            echo 'LOP';
                                            break;
                                        case 9:
                                            echo 'OD';
                                            break;
                                    }
                                    ?></td>
                                <?php $leavenum = $leave['number_leave'];
                                if ($leavenum > 0) { ?>
                                    <td><?php echo $leavenum; ?></td>
                                    <td></td>
                                <?php } else { ?>
                                    <td></td>
                                    <td><?php echo abs($leavenum); ?></td>
                                <?php } ?>
                                <td><?php echo $leave['start_dt']; ?></td>
                                <!--  <td><?php echo $leave['expire_on']; ?></td> -->
                                <td><?php $status = $leave['status'];
                                    switch ($status) {
                                        case 1:
                                            echo 'Active';
                                            break;
                                        case 0:
                                            echo 'Deleted';
                                            break;
                                        case 3:
                                            echo 'Waiting for Approval';
                                            break;
                                        case 4:
                                            echo 'Rejected';
                                            break;
                                    }
                                    ?></td>
                                <td><?php echo $leave['remarks']; ?></td>
                                <td><?php echo $leave['updatedby']; ?></td>
                                <td><?php echo date('Y-m-d', $leave['last_updated_on']); ?></td>


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