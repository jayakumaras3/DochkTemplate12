<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('User_login/client_list/my_client_list'); ?>">My Clients</a></li>

            </div>
            <h4 class="page-title">Client Status</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table dt-responsive nowrap w-100">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Project</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Scope</th>
                                <th>Updated On</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                            if (isset($client_status) && is_array($client_status)) {
                                foreach ($client_status as $data) {
                                    $j = $j + 1;
                            ?>
                                    <tr>
                                        <td>
                                            <?= form_open('Project_Manage/PM_ucn/edit_ucn_details', ['class' => 'my-form', 'id' => 'myForm']) ?>
                                            <input type="hidden" name="id_ucn" value=" <?php echo $data['ucn_id']; ?>">
                                            <input type="hidden" name="return_page" value="3">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                <?php echo $j; ?></button>
                                            <?= form_close() ?>
                                        </td>
                                        <td><?php echo $data['name'] ?></td>
                                        <td><?php if ($data['start_dt']) {
                                                echo date('Y-m', strtotime($data['start_dt']));
                                            } ?></td>
                                        <td><?php if ($data['end_dt']) {
                                                echo date('Y-m', strtotime($data['end_dt']));
                                            } ?></td>
                                        <td>
                                            <?php
                                            if ($data['status'] == 10) {
                                                echo '<span class="badge bg-soft-info text-info p-1">Completed</span>';
                                            } else if ($data['status'] == 4) {
                                                echo '<span class="badge bg-soft-warning text-warning p-1">On Hold</span>';
                                            } else if ($data['status'] == 3) {
                                                echo '<span class="badge bg-soft-danger text-danger p-1">Cancelled</span>';
                                            } else if ($data['status'] == 5) {
                                                echo '<span class="badge bg-soft-primary text-primary p-1">Delayed</span>';
                                            } else if ($data['status'] == 1) {
                                                echo '<span class="badge bg-soft-success text-success p-1">Active</span>';
                                            } else {
                                                echo '';
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo $data['remarks'] ?></td>
                                        <td><?php echo $data['scope'] ?></td>
                                        <td><?php if ($data['last_updated_on']) {
                                                echo date('m-d', $data['last_updated_on']);
                                            } ?></td>
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

</div>