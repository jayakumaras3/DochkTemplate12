<?php $client = session()->get('client');
$clientarray = explode(',', $client); ?>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li>Admin</li><b> &nbsp;> &nbsp;</b>
            <li><a href="<?php echo base_url('SCORM/scorm_client_users') ?>">Users List</a></li><b> &nbsp;> &nbsp;</b>
          
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="x_panel">
            <h2>Add New Users</h2>
            <div class="x_title">
                <div class="x_content">
                    <br />
                    <div class="block block-drop-shadow">
                        <div class="content controls">
                            <form class="form" action=" <?php echo base_url('SCORM/scorm_client_users/addclientregister?cid=' . $clientid); ?>" method="POST"><?= csrf_field() ?>
                                <div class="form-group row" class="col-md-9 col-sm-9 ">
                                    <label><?php echo lang('UI_Text.Name') ?></label>
                                    <input type="text" class="form-control" name="name" placeholder="<?php echo lang('UI_Text.Name') ?>" value="<?= set_value('name') ?>" />
                                </div>
                                <div class="form-group row" class="col-md-9 col-sm-9 ">
                                    <label><?php echo lang('UI_Text.Username') ?></label>
                                    <input type="text" class="form-control" name="username" placeholder="<?php echo lang('UI_Text.Username') ?>" value="<?= set_value('username') ?>" />
                                </div>
                                <div class="form-group row" class="col-md-9 col-sm-9 ">
                                    <label><?php echo lang('UI_Text.Email') ?></label>
                                    <input type="text" class="form-control" name="email" value="" placeholder="Email" />
                                </div>
                                <div class="form-group row" class="col-md-9 col-sm-9 ">
                                    <label><?php echo lang('UI_Text.Designation') ?></label>
                                    <input type="text" class="form-control" name="designation" placeholder="<?php echo lang('UI_Text.Designation') ?>" value="<?= set_value('designation') ?>" />
                                </div>
                                <div class="form-group row" class="col-md-9 col-sm-9 ">
                                    <label><?php echo lang('UI_Text.Userlevel') ?></label>
                                    <select name="userlevelItem" class="form-control">
                                        <?php if (!in_array('1', $clientarray)) { ?>
                                            <option value="3">Normal</option>
                                            <option value="44">Client Admin</option>
                                            <option value="45">Client Project Manager</option>
                                            <option value="46">Client SME</option>
                                            <?php } else {
                                            foreach ($userlevelData as $eachcategoryItem) { ?>
                                                <option value="<?php echo $eachcategoryItem['id_d'] ?>"><?php echo $eachcategoryItem['name'] ?></option>
                                        <?php }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group row" class="col-md-9 col-sm-9 ">
                                    <label><?php echo lang('UI_Text.Password') ?></label>
                                    <input type="password" class="form-control" name="password" placeholder="<?php echo lang('UI_Text.Password') ?>" value="" />

                                </div>
                                <div class="form-group row" class="col-md-9 col-sm-9 ">
                                    <label>Password Confirm</label>
                                    <input type="password" class="form-control" name="password_confirm" placeholder="Password confirm" value="" />
                                </div>
                                <div class="form-group row" class="col-md-9 col-sm-9 ">
                                    <label>Select Timezone</label>
                                    <select name="timezone" class="form-control">
                                        <?php foreach ($timezone as $eachtimezone) { ?>
                                            <option value="<?php echo $eachtimezone['id_t'] ?>"><?php echo $eachtimezone['timezone_name'] . ' - ' . $eachtimezone['timezone_pname'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <!--<div class="form-group row" class="col-md-9 col-sm-9 ">
                                            <div class="col-md-4">Roles</div>
                                            <div class="col-md-8">
                                                <select name="userlevel">
                                                    <?php foreach ($userlevelData as $eachcategoryItem) { ?>
                                                        <option value="<?php echo $eachcategoryItem['id_d'] ?>"><?php echo $eachcategoryItem['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div> -->
                                <div class="form-group row" class="col-md-9 col-sm-9 ">
                                    <label>Language</label>
                                    <select name="lang" class="form-control">
                                        <option value="en">English</option>
                                        <option value="es">Spanish</option>
                                    </select>

                                </div>

                                <div class="form-group row" class="col-md-9 col-sm-9 ">
                                    <?php if (isset($validation)) : ?>
                                        <div class=col-12 col-sm-4>
                                            <div class="alert alert-danger" role="alert">
                                                <?= $validation->listErrors() ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <input type="hidden" name="subjoin" value="1">
                                    <input type="hidden" name="createdby" value=" ">
                                    <button type="submit" class="btn btn-success block">
                                        <i class="ace-icon fa fa-key bigger-110"></i> Save
                                    </button>

                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>