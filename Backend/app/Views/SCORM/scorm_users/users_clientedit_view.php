<?php $client = session()->get('client');
$clientarray = explode(',', $client) ?>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('SCORM/scorm_client_users'); ?>">Users List</a></li><b> &nbsp;> &nbsp;</b>
       
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="x_panel">
            <div class="block block-drop-shadow">
                <div class="header">
                    <h2><?php echo lang('UI_Text.ChangeDetails') ?></h2>
                </div>
                <div class="content controls">
                    <form class="form" action="<?php echo base_url('SCORM/scorm_client_users/editUsers/' . base64_encode($row['id_user'])); ?>" method="POST"><?= csrf_field() ?>
                        <div class="form-row">
                            <div class="col-md-3"><?php echo lang('UI_Text.Name') ?></div>
                            <div class="col-md-9"><input class="form-control" type="text" name="name" maxlength="50" value="<?php echo $row['name']; ?>">
                            </div>
                        </div><br />

                        <div class="form-row">
                            <div class="col-md-3"><?php echo lang('UI_Text.Email') ?></div>
                            <div class="col-md-9">
                                <input class="form-control" type="text" name="email" value="<?php echo $row['email']; ?>" placeholder="Email">
                            </div>
                        </div><br />
                        <div class="form-row">
                            <div class="col-md-3"><?php echo lang('UI_Text.Designation') ?></div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="designation" placeholder="<?php echo lang('UI_Text.Designation') ?>" value="<?php echo $row['designation'] ?>" />
                            </div>
                        </div><br />
                        <div class="form-row">
                            <div class="col-md-3">Select Timezone</div>
                            <div class="col-md-9">

                                <select name="timezone" class="form-control">
                                    <?php if (!empty($timezone)) {
                                        foreach ($timezone as $eachtimezone) {
                                            if ($row['timezone'] == $eachtimezone['id_t']) { ?>
                                                <option selected='selected' value="<?php echo $eachtimezone['id_t'] ?>"><?php echo $eachtimezone['timezone_name'] . ' - ' . $eachtimezone['timezone_pname'] ?></option>
                                            <?php } else { ?>
                                                <option value="<?php echo $eachtimezone['id_t'] ?>"><?php echo $eachtimezone['timezone_name'] . ' - ' . $eachtimezone['timezone_pname'] ?></option>
                                    <?php }
                                        }
                                    } ?>

                                </select>
                            </div>
                        </div><br />
                        <?php if (isset($validationEditUsers)) : ?>
                            <div class=col-12 col-sm-4>
                                <div class="alert alert-danger" role="alert">
                                    <?= $validationEditUsers->listErrors() ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="form-row">
                            <div class="col-md-3"></div>
                            <div class="col-md-9">
                                <input type="hidden" name="id_user" value="<?php echo base64_encode($row['id_user']) ?>" />
                                <input type="hidden" name="clientid" value="<?php echo session()->get('client') ?>" />
                                <input type="hidden" name="adminuseredit" value="1" />
                                <button type="submit" class="btn btn-sm btn-info form-control" type="button">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="x_panel">
            <div class="block block-drop-shadow">
                <?php if (session()->get('passsuccess')) : ?>
                    <div class="alert alert-success" role="alert">
                        <?= session()->get('passsuccess') ?>
                    </div>
                <?php endif; ?>

                <div class="content controls">
                    <form class="form_1" action="<?php echo base_url('SCORM/scorm_client_users/passeditUsers/' . base64_encode($row['id_user'])); ?>" method="POST"><?= csrf_field() ?>

                        <div class="form-row">
                            <div class="col-md-3"><?php echo lang('UI_Text.Password') ?></div>
                            <div class="col-md-9"><input class="form-control" type="password" name="password" maxlength="30" value="" placeholder="Change Password">
                                    <p class="helper-text">
                                        <span class="chk">&#10003;</span> Min 8 chars
                                        &nbsp;|&nbsp;
                                        <span class="chk">&#10003;</span> Letters, numbers &amp; special chars
                                    </p>
                            </div>
                        </div><br />


                        <div class="form-row">
                            <div class="col-md-3"></div>
                            <div class="col-md-9">
                                <input type="hidden" name="id_user" value="<?php echo base64_encode($row['id_user']) ?>" />
                                <input type="hidden" name="clientid" value="<?php echo session()->get('client') ?>" />
                                <input type="hidden" name="adminuseredit" value="1" />
                                <button type="submit" class="btn btn-sm btn-info form-control" type="button" id="submitBtn" disabled>
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    Submit
                                </button>
                            </div>
                        </div>
                        <?php if (isset($validationpassEditUsers)) : ?>
                            <div class=col-12 col-sm-4>
                                <div class="alert alert-danger" role="alert">
                                    <?= $validationpassEditUsers->listErrors() ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <div class="block">
            <div class="x_panel">
                <div class="header">
                    <h2><?php echo lang('Buttons.Add') . ' ' . lang('UI_Text.Userlevel') ?></h2>
                </div>
                <div class="content">
                    <form class="form-inline" action="<?php echo base_url('SCORM/scorm_client_users/updateCategoryItem/' . base64_encode($row['id_user'])); ?>" method="POST"><?= csrf_field() ?>
                        <div class="form-row">
                            <div class="col-md-3"><?php echo lang('UI_Text.Userlevel') ?></div>
                            <div class="col-md-9">
                                <select name="userlevelItem" class="form-control">
                                    <?php if (!in_array('1', $clientarray)) { ?>
                                        <option value="3">Normal</option>
                                        <option value="44">Client Admin</option>
                                        <option value="45">Client Project Manager</option>
                                        <option value="46">Client SME</option>
                                        <?php } else {

                                        foreach ($userlevelData as $eachcategoryItem) {
                                            if ($eachcategoryItem['id_d'] == '6') {
                                            } else { ?>
                                                <option value="<?php echo $eachcategoryItem['id_d'] ?>"><?php echo $eachcategoryItem['name'] ?></option>
                                    <?php }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <?php if (isset($validation)) : ?>
                                <div class=col-12 col-sm-4>
                                    <div class="alert alert-danger" role="alert">
                                        <?= $validation->listErrors() ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="col-md-3"></div>
                            <div class="col-md-9">
                                <input type="hidden" name="clientid" value="<?php echo session()->get('client') ?>" />
                                <button type="submit" class="btn btn-info block">
                                    <?php echo lang('Buttons.Add') ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="x_panel">
            <div class="block block-drop-shadow">
                <div class="header">
                    <h2>Last Login</h2>
                </div>
                <div class="content">
                    <span><?php echo $lastLoginTime; ?></span>
                </div>
            </div>
        </div>
        <div class="x_panel">
            <div class="block block-drop-shadow">
                <div class="content">
                    <form action="<?php echo base_url('language'); ?>" method="POST"><?= csrf_field() ?>
                        <input type="hidden" name="user" value="<?php echo $row['username']; ?>" />
                        <div class="form-group">
                            <select name="lang" class="form-control">
                                <option value="en">English</option>
                                <option value="es">Spanish</option>
                            </select>
                        </div>
                        <input type="hidden" name="clientid" value="<?php echo session()->get('client'); ?>" />
                        <div class="form-group">
                            <input class="btn btn-sm btn-info form-control" type="submit" value="Change Language" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--<div class="block block-drop-shadow">
            <div class="content">
                <form action="" method="POST"><?= csrf_field() ?>
                    <input type="hidden" name="user" value="?>" />
					<div class="col-md-6">
					
					<div class="checkbox-inline">
                         <label><input type="checkbox" name="demoaccess" />Demo Access</label>
                    </div>
					</div>
					<div class="col-md-6">
					<div class="form-group">
                        <input type="hidden" name="changedemoacess" value="1" />
                        <input class="btn btn-info form-control" type="submit" value="Update" />
                    </div>
					</div>
                </form>
            </div>
        </div>-->
        <div class="block block-drop-shadow">
            <div class="x_panel">
                <div class="header">
                    <h2>View</h2>
                </div>

                <div class="content">
                    <table cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped ">
                        <thead>
                            <tr>
                                <th><?php echo lang('UI_Text.Category') ?></th>
                                <th><?php echo lang('UI_Text.Category') . ' ' . lang('UI_Text.Item') ?></th>
                                <th><?php echo lang('UI_Text.Action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($clientUserlevelData)) : foreach ($clientUserlevelData as $eachuserData) { ?>
                                    <tr>
                                        <td value="<?php echo $eachuserData['fk_id_dc'] ?>"><?php echo $eachuserData['category_name'] ?></td>
                                        <td value="<?php echo $eachuserData['fk_id_d'] ?>"><?php echo isset($eachuserData['Category_item']) ? $eachuserData['Category_item'] : $eachuserData['client_name'] ?></td>
                                        <input type="hidden" name="id_user" value="<?php echo $eachuserData['id_user'] ?>" />
                                        <td><a onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')" href="<?php echo base_url('SCORM/scorm_client_users/deleteUserCategory/' . $eachuserData['id_du'] . '/' . $eachuserData['id_user']) ?>" class="btn btn-sm widget-icon"><span class="icon-trash"></span></a></td>
                                    </tr>
                            <?php }
                            endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const password = document.getElementById('password');
    const submitBtn = document.getElementById('submitBtn');

    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,64}$/;

    function validatePassword() {
        if (regex.test(password.value)) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    }

    // run on typing
    password.addEventListener('input', validatePassword);

    // ✅ run on page load (IMPORTANT FIX)
    validatePassword();

});
</script>