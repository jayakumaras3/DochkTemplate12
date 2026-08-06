<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">My UCN</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <form action="<?php echo base_url('Project_Manage/PM_ucn/create_new_ucn') ?>" method="POST" id="submitForm"><?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger waves-effect waves-light">
                                <i class="mdi mdi-plus-circle me-1" id="submitButton"></i> Create New UCN
                            </button>
                        </form>
                    </div>
                    <div class="col-sm-8">
                        <div class="text-sm-end mt-2 mt-sm-0">
                            <form action="<?php echo base_url('Project_Manage/PM_ucn/closed_ucn') ?>" method="POST"><?= csrf_field() ?>
                                <button type="submit" class="btn btn-light mb-2">
                                    Closed UCN
                                </button>
                            </form>
                        </div>
                    </div><!-- end col-->
                </div>

                <div class="table-responsive">
                    <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                        <thead class="table-light">
                            <tr>
                                <th>UCN</th>
                                <th>Name</th>
                                <th>Client</th>
                                <th>Account Manager</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th style="width: 85px;">Details</th>
                                <th style="width: 85px;">Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($ucn_list as $data) {
                            ?>
                                <tr>
                                    <td><?php echo $data['ucn_id'] ?></td>
                                    <td><?php echo $data['name'] ?></td>
                                    <td><?php echo $data['client_name'] ?></td>
                                    <td><?php echo $data['manager'] ?></td>
                                    <td><?php echo $data['start_dt'] ?></td>
                                    <td><?php echo $data['end_dt'] ?></td>
                                    <td><?php
                                        $status = $data['status'];
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
                                    <td>


                                        <form action="<?php echo base_url('Project_Manage/PM_ucn/edit_ucn') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="ucn_id" value="<?php echo $data['ucn_id']; ?>">
                                            <input type="hidden" name="client" value="<?php echo $data['client']; ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                <span class="mdi mdi-alpha-d-box-outline"></span></button>
                                        </form>

                                    </td>
                                    <td>


                                        <form action="<?php echo base_url('Project_Manage/PM_ucn/edit_ucn_details') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="id_ucn" value="<?php echo $data['ucn_id']; ?>">
                                            <button type="submit" class="btn btn-outline-danger waves-effect btn-xs waves-light">
                                                <span class="mdi mdi-square-edit-outline"></span></button>
                                        </form>

                                    </td>
                                    <!--  <td>
                            <form action="<?php echo base_url('Project_Manage/PM_ucn/projects') ?>" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="ucn_id" value="<?php echo $data['ucn_id']; ?>">
                                <input type="hidden" name="client" value="<?php echo $data['client']; ?>">
                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                    <span class="fe-folder"></span></button>
                            </form>
                        </td> -->


                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div>