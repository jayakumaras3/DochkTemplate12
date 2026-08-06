
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/PM_ucn/view_effort_details'); ?>">Project Breakup Details</a></li>
                </ol>
            </div>
            <h4 class="page-title">Project Employee Details</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Date</th>
                            <th>Effort</th>
                            <th>Stage</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        foreach ($get_emp_data as $data) {
                            $j++; ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo $data['name'] . ' ' . $data['last_name']; ?></td>
                                <td><?php echo $data['date_value']; ?></td>
                                <td><?php echo $data['effort']; ?></td>
                                <td><?php $stage = $data['stage'];
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
                                    <td><?php echo $data['remarks']; ?></td>

                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>