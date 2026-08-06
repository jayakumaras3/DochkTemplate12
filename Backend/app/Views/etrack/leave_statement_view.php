<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/leaves'); ?>">
                            Leaves
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                e-Track Leave Statement
            </h4>
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
                            <th>Number of Leaves</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Updated By</th>
                            <th>Updated On</th>
                            <th>Delete</th>
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
                                <td><?php echo $leave['et_le_id']; ?></td>
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
                                            case 8:
                                                echo 'LOP';
                                                break;
                                    }
                                    ?></td>
                                <td><?php echo $leave['number_leave']; ?></td>
                                <td><?php echo $leave['start_dt']; ?></td>
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
                                    }
                                    ?></td>
                                <td><?php echo $leave['remarks']; ?></td>
                                <td><?php echo $leave['updatedby']; ?></td>
                                <td><?php echo date('Y-m-d', $leave['last_updated_on']); ?></td>
                                <td>
                                    <?php if ($leave['number_leave'] < 0) { ?>
                                        <?php if ($lastMonth25 < $leave['start_dt']) { ?>
                                            <?php if ($status != 0) { ?>
                                                <form class="form-horizontal" action="<?php echo base_url('etrack/leaves/delete_leaves'); ?>" method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="leaveid" value="<?php echo $leave['et_le_id']; ?>">
                                                    <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-trash-can-outline"></span></button>
                                                </form>
                                            <?php } ?>
                                    <?php }
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