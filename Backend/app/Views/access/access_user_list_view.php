<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Access/accessController'); ?>">Site Access</a></li>
                </ol>
            </div>
            <h4 class="page-title">Access Edit</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo base_url('Access/accessController/editAccesslevel'); ?>" method="post" autocomplete="off"><?= csrf_field() ?>
                    <?= csrf_field() ?>
                    <div class="col-lg-12 mb-2">
                        <label>Name</label>
                        <input name="name" class="form-control" placeholder="Name" type="text" value="<?php echo $access_details[0]['name']; ?>" required="required">
                    </div>


                    <input name="id_ua" class="form-control" placeholder="ID" type="hidden" value="<?php echo $access_details[0]['id_ua']; ?>" required="required">

                    <div class="col-lg-12 mb-2">
                        <select class="form-select" name="status">
                            <option value="1">Active</option>
                            <option value="0">Delete</option>
                        </select>
                    </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-outline-primary waves-effect waves-light form-control">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <table id="searchdatatable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>User</th>
                            <th>Client</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        foreach ($userlevelData as $users) {
                            $j++;
                        ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo $users['name'] . ' ' . $users['last_name']; ?></td>
                                <td><?php echo $users['client']; ?></td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url('Access/accessController/delete_access'); ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="access_id" value="<?php echo $users['access_id'] ?>">
                                        <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-trash-can-outline"></span></button>
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