<?php $accessmenu = session()->get('accessmenu');
$arrayaccessmenu  = array_map('intval', explode(',', $accessmenu)); ?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <form id="myForm" action="<?php echo base_url('User_login/client_list'); ?>" method="POST"><?= csrf_field() ?>
                            <input type="hidden" name="pr_id" value="<?php echo $pr_id; ?>">
                            <button style="color:grey;display:block;border:none;background:none;">Client List</button>
                        </form>
                    </li>

                </ol>
            </div>
            <h4 class="page-title">Edit Client Management</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('User_login/client_list/saveEditClientlist'); ?>" method="POST"><?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Client Name</label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" name="client_name" placeholder="<?php echo lang('UI_Text.Name') ?>" value="<?php echo $row[0]['client_name'] ?>" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Client Full Name</label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" name="client_fullname" placeholder="<?php echo lang('UI_Text.Name') ?>" value="<?php echo $row[0]['client_fullname'] ?>" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Validity</label>
                        <div class="col-8 col-xl-9">
                            <input id="birthday" name="validity" class="date-picker form-control" placeholder="dd-mm-yyyy" type="text" type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="<?php echo isset($row[0]['validity']) ? $row[0]['validity'] : '0000-00-00' ?>">
                            <script>
                                function timeFunctionLong(input) {
                                    setTimeout(function() {
                                        input.type = 'text';
                                    }, 60000);
                                }
                            </script>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">No of license</label>
                        <div class="col-8 col-xl-9">
                            <input type="number" class="form-control" name="license" placeholder="License" value="<?= $row[0]['license'] ?>" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">URL</label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" name="url" placeholder="URL" value="<?= $row[0]['url'] ?>" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Start date</label>
                        <div class="col-8 col-xl-9">
                            <input id="start_date" name="start_date" class="date-picker form-control" placeholder="dd-mm-yyyy" type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="<?php echo isset($row[0]['start_date']) ? $row[0]['start_date'] : '' ?>">
                            <script>
                                function timeFunctionLong(input) {
                                    setTimeout(function() {
                                        input.type = 'text';
                                    }, 60000);
                                }
                            </script>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">End date</label>
                        <div class="col-8 col-xl-9">
                            <input id="end_date" name="end_date" class="date-picker form-control" placeholder="dd-mm-yyyy" type="text" onfocus="this.type='date'" onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="<?php echo isset($row[0]['end_date']) ? $row[0]['end_date'] : ''; ?>">
                            <script>
                                function timeFunctionLong(input) {
                                    setTimeout(function() {
                                        input.type = 'text';
                                    }, 60000);
                                }
                            </script>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Status</label>
                        <div class="col-8 col-xl-9">
                            <select name="status" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="justify-content-end row">
                        <div class="col-8 col-xl-9">
                            <?php if (isset($validation)) : ?>
                                <div class=col-12 col-sm-4>
                                    <div class="alert alert-danger" role="alert">
                                        <?= $validation->listErrors() ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                            <input type="hidden" name="pr_id" value="<?php echo $pr_id; ?>">
                            <button type="submit" class="btn btn-outline-warning waves-effect btn-sm waves-light">
                                Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- <div class="col-lg-3">
        <div class="card">
            <div class="card-body">
                <h4>Access Setting</h4>

                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Menu</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $menuData = array(
                            array("menu_id" => 1, "menu_name" => "My", "client_permission" => "My"),
                            array("menu_id" => 2, "menu_name" => "Holidays"),
                            array("menu_id" => 3, "menu_name" => "Projects"),
                            array("menu_id" => 4, "menu_name" => "Demos"),
                            array("menu_id" => 5, "menu_name" => "C4U Courses"),
                            array("menu_id" => 6, "menu_name" => "Aristo"),
                            array("menu_id" => 7, "menu_name" => "e-manual"),
                            array("menu_id" => 8, "menu_name" => "AR/VR/xAPI"),
                            array("menu_id" => 9, "menu_name" => "Manage"),
                            array("menu_id" => 10, "menu_name" => "Admin"),
                            array("menu_id" => 11, "menu_name" => "SCORM"),
                            array("menu_id" => 12, "menu_name" => "Aristo"),
                        );
                        $i = 0;
                        foreach ($menuData as $eachmenu) {
                            $i = $i + 1;
                            $id = $eachmenu['menu_id'];
                            $client_id = $cid;
                            // print_r($client_id);
                            $db = \Config\Database::connect();
                            $builder = $db->table('menu_clientaccess as mc');
                            $builder->select('mc.client_permission,mc.menu_id');
                            $builder->where('mc.menu_id', $id);
                            $builder->where('mc.client_id', $client_id);
                            $data = $builder->get();
                            if (count($data->getResultArray()) == 1) {
                                $result  = $data->getRowArray();
                                if (($result['client_permission'] == 1) && isset($result['menu_id'])) {
                                    $status = "checked";
                                } else {
                                    $status = '';
                                }
                            } else {
                                $status = '';
                            } ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $eachmenu['menu_name'] ?></td>
                                <td>
                                    <form class="form-horizontal" id="givepermission">
                                        <input type="hidden" name="client_id" value="<?php echo $cid ?>">
                                        <input type="hidden" name="menu_id" value="<?php echo $eachmenu['menu_id'] ?>">
                                        <label>
                                            <input type="checkbox" onchange='switchStatus(<?php echo $id; ?>,this,<?php echo $cid; ?>)' data-toggle='toggle' <?php echo $status ?> class="skip" />
                                            <span></span>
                                        </label>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> -->
    <div class="col-lg-6">
        <!-- <div class="card">
            <div class="card-body">
                <h6><?php echo $row[0]['client_name'] ?> Logo</h6>
                <?php if ($row[0]['logo']  == '') {
                ?>
                <?php } else {
                ?>
                    <div class="head bg-dot30 np tac">
                        <img style="max-height:100px;" src="<?php echo base_url() . '/assets/assets/uploads/client_logo/' . $cid . '/' . $row[0]['logo'] ?>" class="img-squre img-thumbnail" />
                    </div><br />
                <?php }
                ?>
                <div class="form-row">
                    <form class="form-horizontal" enctype="multipart/form-data" action="<?php echo base_url('User_login/client_list/uploadClientlogo') ?>" method="POST"><?= csrf_field() ?>
                        <div class="form-group col-md-12 mb-3">
                            <input type="file" name="file" required/>
                        </div>
                        <div class="form-group col-md-12">
                            <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                            <input type="hidden" name="pr_id" value="<?php echo $pr_id; ?>">

                            <button type="submit" class="btn btn-info btn-sm form-control">Upload</button>
                        </div>
                        <?php if (isset($logovalidation)) : ?>
                            <div class="form-group col-md-12">
                                <div class="alert alert-danger" role="alert">
                                    <?= $logovalidation->listErrors() ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div> -->


        <!-- <div class="card">
            <div class="card-body">
                <h6><?php echo $row[0]['client_name'] ?>Logo for dark</h6>
                <?php if ($row[0]['logo']  == '') {
                ?>
                <?php } else {
                ?>
                    <div class="head bg-dot30 np tac">
                        <img style="max-height:100px;" src="<?php echo base_url() . '/assets/assets/uploads/client_logo/' . $cid . '/' . $row[0]['logo_dark'] ?>" class="img-squre img-thumbnail" />
                    </div><br />
                <?php }
                ?>
                <div class="form-row">
                    <form class="form-horizontal" enctype="multipart/form-data" action="<?php echo base_url('User_login/client_list/uploadClientlogoDark') ?>" method="POST"><?= csrf_field() ?>
                        <div class="form-group col-md-12 mb-3">
                            <input type="file" name="file" />
                        </div>
                        <div class="form-group col-md-12">
                            <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                            <input type="hidden" name="pr_id" value="<?php echo $pr_id; ?>">

                            <button type="submit" class="btn btn-dark btn-sm form-control">Upload</button>
                        </div>
                        <?php if (isset($logovalidation)) : ?>
                            <div class="form-group col-md-12">
                                <div class="alert alert-danger" role="alert">
                                    <?= $logovalidation->listErrors() ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div> -->
        <div class="card">
            <div class="card-body">
                <form action="<?php echo base_url('User_login/client_list/deleted_userslist'); ?>" method="POST"><?= csrf_field() ?>
                    <input type="hidden" name="pr_id" value="<?php echo $pr_id; ?>">
                    <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                    <button type="submit" class="btn btn-outline-danger waves-effect btn-sm waves-light col-md-12"><span class="icon-user"></span> Inactive User List</button>
                </form>
            </div>
            <div class="card">
                <div class="card-body">
                    <h6><?php echo $row[0]['client_name'] ?> Logo</h6>
                    <?php if ($row[0]['logo']  == '') {
                    ?>
                    <?php } else {
                    ?>
                        <div class="head bg-dot30 np tac">
                            <img style="max-height:100px;" src="<?php echo base_url() . '/assets/assets/uploads/client_logo/' . $cid . '/' . $row[0]['logo'] ?>" class="img-squre img-thumbnail" />
                        </div><br />
                    <?php }
                    ?>
                    <div class="form-row">
                        <form class="form-horizontal" enctype="multipart/form-data" action="<?php echo base_url('User_login/client_list/uploadClientlogo') ?>" method="POST"><?= csrf_field() ?>
                            <div class="form-group col-md-12 mb-3">
                                <input type="file" name="file" required />
                            </div>
                            <div class="form-group col-md-12">
                                <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                                <input type="hidden" name="pr_id" value="<?php echo $pr_id; ?>">

                                <button type="submit" class="btn btn-outline-info waves-effect btn-sm waves-light">Upload</button>
                            </div>
                            <?php if (isset($logovalidation)) : ?>
                                <div class="form-group col-md-12">
                                    <div class="alert alert-danger" role="alert">
                                        <?= $logovalidation->listErrors() ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>


            <div class="card">
                <div class="card-body">
                    <h6><?php echo $row[0]['client_name'] ?> Logo for dark</h6>
                    <?php if ($row[0]['logo']  == '') {
                    ?>
                    <?php } else {
                    ?>
                        <div class="head bg-dot30 np tac">
                            <img style="max-height:100px;" src="<?php echo base_url() . '/assets/assets/uploads/client_logo/' . $cid . '/' . $row[0]['logo_dark'] ?>" class="img-squre img-thumbnail" />
                        </div><br />
                    <?php }
                    ?>
                    <div class="form-row">
                        <form class="form-horizontal" enctype="multipart/form-data" action="<?php echo base_url('User_login/client_list/uploadClientlogoDark') ?>" method="POST"><?= csrf_field() ?>
                            <div class="form-group col-md-12 mb-3">
                                <input type="file" name="file" />
                            </div>
                            <div class="form-group col-md-12">
                                <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                                <input type="hidden" name="pr_id" value="<?php echo $pr_id; ?>">

                                <button type="submit" class="btn btn-outline-dark waves-effect btn-sm waves-light">Upload</button>
                            </div>
                            <?php if (isset($logovalidation)) : ?>
                                <div class="form-group col-md-12">
                                    <div class="alert alert-danger" role="alert">
                                        <?= $logovalidation->listErrors() ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchStatus(id, status, cid) {
            var theID = id;
            var thecID = cid
            var theStatus = $(status).prop('checked');
            //console.log(theStatus);
            if (theStatus) {
                theStatus = 1;
            } else {
                theStatus = 0;
            }
            $.ajax({
                url: "<?php echo base_url('Settings/settings/clientaccesspermission') ?>",
                type: "POST",
                data: {
                    menu_id: theID,
                    client_permission: theStatus,
                    client_id: thecID
                },
                cache: false,
                success: function(data) {
                    // alert("checked");
                }
            });
        }
    </script>