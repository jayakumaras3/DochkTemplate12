<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">My Clients</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Client</th>
                                <th>Status</th>
                                <th>Users</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                            $id_user = session()->get('id_user');
                            foreach (($clientlist ?? []) as $data) {
                                if ($data['id_c'] == 1) {
                                    continue;
                                }
                            ?>
                                <tr>
                                    <?php $assigned_users = explode(',', $data['assigned_users']);
                                    // exit();
                                    if (in_array($id_user, $assigned_users) || $id_user == 1) { ?>
                                        <td><?php $j = $j + 1;
                                            echo $j; ?></td>
                                        <td><?php echo $data['client_name'] ?></td>
                                        <td>
                                            <?php if ($data['id_c'] != 1) { ?>
                                                <form action="<?php echo base_url('User_login/client_list/client_status') ?>" method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="id_c" value="<?php echo $data['id_c']; ?>">
                                                    <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                        Status</button>
                                                </form>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if ($data['id_c'] != 1) { ?>
                                                <form action="<?php echo base_url('Project_Manage/PM_users') ?>" method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="cid" value="<?php echo $data['id_c']; ?>">
                                                    <button type="submit" class="btn btn-outline-warning waves-effect btn-xs waves-light">
                                                        Users</button>
                                                </form>
                                            <?php } ?>
                                        </td>

                                        <!-- <td>
                            <form action="<?php echo base_url('Project_Manage/PM_Proposals/proposal_edit') ?>" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="id_c" value="<?php echo $data['id_c']; ?>">
                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                    <span class="mdi mdi-bookshelf"></span></button>
                            </form>
                        </td> -->

                                        <td>
                                            <?php if ($data['id_c'] != 1) { ?>
                                                <form action="<?php echo base_url('User_login/client_list/client_edit_view') ?>" method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="id_c" value="<?php echo $data['id_c']; ?>">
                                                    <button type="submit" class="btn btn-outline-danger waves-effect btn-xs waves-light">
                                                        Edit</button>
                                                </form>
                                            <?php } ?>
                                        </td>
                                    <?php } else { ?>

                                    <?php } ?>
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
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('User_login/client_list/add_client') ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-1">
                                <label for="clientname" class="form-label">Client <span class="text-danger">*</span></label>
                                <input required type="text" class="form-control col-md-12" name="client_name" placeholder="Client Name" required />
                            </div>
                        </div>
                        <!--  <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="clientname" class="form-label">Client <span class="text-danger">*</span></label>
                                <input required type="text" class="form-control col-md-12" name="client_fullname" placeholder="Client Full Name" required />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-1">
                                <label for="inputEmail3" class="col-form-label">Description</label>
                                <div>
                                    <input class="form-control" name="description" type="hidden" />
                                    <textarea class="ckeditor" name="requirement" required></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1">
                                <label for="inputEmail3" class="col-form-label">Location</label>
                                <div>
                                    <input class="form-control" name="location" type="text" />
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1">
                                <label for="inputEmail3" class="col-form-label">Address</label>
                                <div>
                                    <input class="form-control" name="address" type="text" />
                                    <input class="form-control" name="redirect_url" type="text" value="" />
                            </div>
                        </div> -->


                        <div class="text-sm-end  mt-sm-0">
                            <button type="submit" class="btn btn-outline-danger waves-effect btn-sm waves-light">
                                Add New Client
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>