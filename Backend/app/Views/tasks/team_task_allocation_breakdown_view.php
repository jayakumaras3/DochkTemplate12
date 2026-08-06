
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <?php if ($returnid == 1) { ?>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('Task/Task_manage/view_task_details') ?>">Task Detail</a></li>
                    <?php } ?>

                    <?php if ($returnid == 2) { ?>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('Task/Task_manage/my_task') ?>">Task Allocation</a></li>
                    <?php } ?>

                </ol>
            </div>
            <h4 class="page-title">Task Breakdown</h4>
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
                            <th>Stage</th>
                            <th>Date</th>
                            <th>Effort</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $j = 0;
                        foreach ($allocated_breakdown as $task) {
                            $j = $j + 1; ?>
                            <tr>
                                <td><?php echo $j ?></td>
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
                                <td><?php echo $task['date_value']; ?></td>
                                <td>
                                    <?php
                                    $actual = explode('.', $task['effort']);
                                    if (!empty($actual[1])) {
                                        if ($actual[1] == 25) {
                                            echo $actual[0] . '.15';
                                        } elseif ($actual[1] == 5) {
                                            echo $actual[0] . '.30';
                                        } elseif ($actual[1] == 75) {
                                            echo $actual[0] . '.45';
                                        } else {
                                            echo $task['effort'];
                                        }
                                    } else {
                                        echo $task['effort'];
                                    } ?></td>
                                <td><?php echo $task['remarks'] ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
        </div><!-- /.row -->
    </div><!-- /.row -->
</div><!-- /.row -->