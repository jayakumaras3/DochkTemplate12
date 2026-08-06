<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">

                    <li class="breadcrumb-item"><a href="<?php echo base_url('User_login/client_list/my_client_list'); ?>">Clients</a></li>

                </ol>
            </div>
            <h4 class="page-title">Edit Client</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('User_login/client_list/edit_client') ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-1">
                                <label for="clientname" class="form-label">Client <span class="text-danger">*</span></label>
                                <input required type="text" class="form-control col-md-12" name="client_name" placeholder="Client Short Name" value="<?php echo $row[0]['client_name'] ?>" required />
                            </div>
                        </div>
                        <!-- <div class="col-lg-12">
                            <div class="mb-1">
                                <label for="clientname" class="form-label">Client <span class="text-danger">*</span></label>
                                <input required type="text" class="form-control col-md-12" name="client_fullname" placeholder="Client Full Name" value="<?php echo $row[0]['client_fullname'] ?>" required />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-1">
                                <label for="inputEmail3" class="col-form-label">Description</label>
                                <div>
                                    <textarea class="ckeditor" name="description" required><?php echo $row[0]['description']; ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1">
                                <label for="inputEmail3" class="col-form-label">Location</label>
                                <div>
                                    <input class="form-control" name="location" type="text" value="<?php echo isset($row[0]['location']) ? $row[0]['location'] : '' ?>" />
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-1">
                                <label for="inputEmail3" class="col-form-label">Address</label>
                                <div>
                                    <input class="form-control" name="address" type="text" value="<?php echo isset($row[0]['address']) ? $row[0]['address'] : '' ?>" />
                                </div>
                            </div>
                        </div> -->
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="text-sm-end  mt-sm-0">
                                <input class="form-control" name="id_c" value="<?php echo $id_c; ?>" type="hidden" />
                                <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light">
                                   Update Client Name
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4>Assign project Manager to Client</h4>
                <form action="<?php echo base_url('User_login/client_list/addusers_projects_assignment'); ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1">
                                <label for="inputEmail3" class="col-form-label">Users</label>
                                <div>
                                    <select name="id_user" class="form-control">
                                        <?php foreach ($userdata  as $user) { ?>
                                            <option value="<?php echo $user['id_user']; ?>"><?php echo $user['fname'] . ' ' . $user['lname'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="text-sm-end  mt-sm-0">
                            <input class="form-control" name="id_c" value="<?php echo $id_c ?>" type="hidden" />
                            <button type="submit" class="btn btn-outline-danger waves-effect btn-sm waves-light">
                                Assign Project Manager
                            </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                            foreach ($access as $data) {
                                $j = $j + 1 ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $data['fname'] . ' ' . $data['lname']; ?></td>

                                    <td>
                                        <form action="<?php echo base_url('User_login/client_list/delete_userassignment') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="id_c" value="<?php echo $id_c; ?>">
                                            <input type="hidden" name="project_assign_id" value="<?php echo $data['project_assign_id']; ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                <span class="mdi mdi-trash-can-outline"></span></button>
                                        </form>
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
</div>