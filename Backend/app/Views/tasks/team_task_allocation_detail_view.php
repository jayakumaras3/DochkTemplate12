
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Task/Task_manage/team_tasks_allocate') ?>">Manager Task Allocation</a></li>
                </ol>
            </div>
            <h4 class="page-title">Task Detail</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table class="table  table-sm table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Stage</th>
                            <th>Allocated Effort</th>
                            <th>Actual Effort</th>
                            <th>Status</th>
                            <th width=5%>View</th>
                            <th width=5%>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $j = 0;
                        $totaleffort = 0;
                        $totalactual = 0;
                        foreach ($allocated_tasks as $task) {

                            $j = $j + 1; ?>
                            <tr>
                                <td><?php echo $j ?></td>
                                <td><?php echo $task['name'] . ' ' . $task['last_name']; ?></td>
                                <td><?php $stage = $task['stage'];
                                    switch ($stage) {
                                        case 1:
                                            echo 'Alpha';
                                            break;
                                        case 2:
                                            echo 'Beta';
                                            break;
                                        case 5:
                                            echo 'Gamma';
                                            break;
                                        case 0:
                                            echo 'Gen';
                                            break;
                                    }
                                    ?></td>
                                <td><?php echo $task['effort'];
                                    $totaleffort =  $totaleffort + $task['effort']; ?></td>
                                <td><?php echo $task['toteff'];
                                    $totalactual =  $totalactual + $task['toteff']; ?></td>
                                <td><?php $status = $task['status'];
                                    switch ($status) {
                                        case 1:
                                            echo 'Active';
                                            break;
                                        case 2:
                                            echo 'Not Active';
                                            break;
                                    } ?></td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url('Task/Task_manage/employee_brkdown_effort') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="returnid" value="1">
                                        <input type="hidden" name="ucn_tl_id" value="<?php echo $task['ucn_tl_id']; ?>">
                                        <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-eye-outline"></span></button>
                                    </form>
                                </td>
                                <td>
                                    <?php if ($task['status'] == 1) { ?>
                                        <form class="form-horizontal" action="<?php echo base_url('Task/Task_manage/close_lt_assigned_task') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="ucn_tl_id" value="<?php echo $task['ucn_tl_id']; ?>">
                                            <?php if (!empty($task['toteff'])) { ?>
                                                <input type="hidden" name="status" value="2">
                                                <button type="submit" class="btn btn-outline-warning btn-sm" onclick="return confirm('<?php echo lang('Alert.Aler_009') ?>')" title="Close">Close</button>

                                            <?php } else { ?>
                                                <input type="hidden" name="status" value="0">
                                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('<?php echo lang('Alert.Aler_010') ?>')" title="Delete">Delete</button>
                                            <?php } ?>
                                        </form>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="3">TOTAL</td>
                            <td><?php echo $totaleffort; ?></td>
                            <td><?php echo $totalactual; ?></td>
                            <td colspan="3"></td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div><!-- /.row -->
    </div><!-- /.row -->
</div><!-- /.row -->