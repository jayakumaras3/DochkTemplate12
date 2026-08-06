<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('SCORM/Scorm_client/reviews') ?>">Client Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project/client_dashboard/courses') ?>">Courses</a></li>
                </ol>
            </div>
            <h4 class="page-title">Reviewers</h4>
        </div>
    </div>
</div>
<div class="row">

    <div class="card">
        <div class="card-body">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Reviewer</th>
                        <th>Assigned On</th>
                        <th>Due On</th>
                        <th>Completed On</th>
                        <th>Stage</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $j = 0;
                    $stage1 = '';
                    if ($client_access) {
                        foreach ($client_access as $data) {
                            // print_r($data);
                            // exit();
                            $j = $j + 1 ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo $data['fullname']; ?></td>
                                <td><?php echo date('m-d-Y', $data['createdon']); ?></td>

                                <td><?php echo ($data['due_date'] != '0000-00-00') ? date('m-d-Y', strtotime($data['due_date'])) : ''; ?></td>
                                <td><?php echo ($data['last_updated_on'] != '0') ? date('m-d-Y', ($data['last_updated_on'])) : ''; ?></td>
                                <td>
                                    <?php if ($data['stage'] == 1) {
                                        $stage1 = 'Development';
                                    } elseif ($data['stage'] == 2) {
                                        $stage1 = 'Live';
                                    } elseif ($data['stage'] == 3) {
                                        $stage1 = 'Alpha Review';
                                    } elseif ($data['stage'] == 4) {
                                        $stage1 = 'Alpha 2 Review';
                                    } elseif ($data['stage'] == 5) {
                                        $stage1 = 'Beta Review';
                                    } elseif ($data['stage'] == 6) {
                                        $stage1 = 'Beta 2 Review';
                                    } elseif ($data['stage'] == 7) {
                                        $stage1 = 'Gamma';
                                    } elseif ($data['stage'] == 8) {
                                        $stage1 = 'Gamma 2';
                                    } ?>
                                    <?php echo $stage1; ?>
                                </td>
                                <td><?php $status = $data['course_status'];
                                    if ($status == 1) {
                                        echo 'In progress';
                                    } elseif ($status == 2) {
                                        echo 'Completed';
                                    } elseif ($status == 3) {
                                        echo 'Review Completed';
                                    }

                                    ?>
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>