<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Closed UCN</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <form action="<?php echo base_url('Project_Manage/PM_ucn/create_new_ucn') ?>" method="POST"><?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger waves-effect waves-light">
                                <i class="mdi mdi-plus-circle me-1"></i> Create New UCN
                            </button>
                        </form>
                    </div>
                    <div class="col-sm-8">
                        <div class="text-sm-end mt-2 mt-sm-0">
                            <form action="<?php echo base_url('Project_Manage/PM_ucn') ?>" method="POST"><?= csrf_field() ?>
                                <button type="submit" class="btn btn-light mb-2">
                                    Active UCN
                                </button>
                            </form>
                        </div>
                    </div><!-- end col-->
                </div>

                <div class="table-responsive">
                    <table class="table table-centered  table-striped" id="products-datatable">
                        <thead class="table-light">
                            <tr>
                                <th>UCN</th>
                                <th>Name</th>
                                <th>Client</th>
                                <th>Account Manager</th>
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
                                    <td><?php
                                        $status = $data['status'];
                                        switch ($status) {
                                            case 1:
                                                echo "New";
                                                break;
                                            case 6:
                                                echo "Awaiting Approval";
                                                break;
                                            case 7:
                                                echo "Waiting Mng Approval";
                                                break;
                                            case 8:
                                                echo "Waiting Fin Approval";
                                                break;
                                            case 9:
                                                echo "Approved";
                                                break;
                                            case 10:
                                                echo "Closed";
                                                break;
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