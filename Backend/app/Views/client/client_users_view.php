<?php $userlevel = session()->get('userlevel');
$arrayuserlevel  = array_map('intval', str_split($userlevel));
$sessionclient = session()->get('client');
$arrayclient  = explode(',', $userlevel);
?>

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('User_login/client_list') ?>">Back</a></li><b>&nbsp;>&nbsp;</b>

        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-2" style="margin-left: 83%;">
        <div class="block">
            <a href="<?php echo base_url() . "User_login/users/register?cid=" . $arrayclient[0] ?>"><button class="btn btn-info btn-sm form-control"><span class="icon-plus"></span> Add Users</button></a>
        </div>
    </div>
    <div class="col-md-12">
        <div class="block">
            <div class="x_panel">
                <div class="content tab-content bg-dot50">
                    <div class="tab-pane active" id="tab13">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="block">
                                    <div class="header">
                                        <h2><?php echo lang('UI_Text.Active_Users') ?></h2>
                                    </div>
                                    <div class="content">
                                        <table cellpadding="0" cellspacing="0" width="100%" class="table table-bordered table-striped sortable">
                                            <thead>
                                                <tr>
                                                    <th><?php echo lang('UI_Text.Username') ?></th>
                                                    <th><?php echo lang('UI_Text.Name') ?></th>
                                                    <th><?php echo lang('UI_Text.Last_Active') ?></th>
                                                    <th><?php echo lang('UI_Text.Edit') ?></th>
                                                    <th><?php echo lang('UI_Text.Delete') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php if (!empty($usertable)) : foreach ($usertable as $k) {
                                                        $clientid = array_map('intval', str_split($k['clientid']));
                                                        /* 44- client admin  4- TQ project manager */
                                                        if (in_array('44', $arrayuserlevel)  && in_array($arrayclient[0], $clientid) || session()->get('username') == 'admin' || in_array('4', $arrayuserlevel)) {
                                                ?>
                                                            <tr>
                                                                <td><?php echo $k['username'] ?></td>
                                                                <td><?php echo $k['name'] ?></td>
                                                                <td><?php echo date("m-d-Y", strtotime($k['timestamp'])); ?></td>
                                                                <?php
                                                                $temp_id = base64_encode($k['id_user']);
                                                                //print_r($temp_id);
                                                                ?>
                                                                <td><a href="<?php echo base_url('User_login/client_users/editUsers/' . $temp_id) ?>"><button class="widget-icon btn-info"><span class="icon-pencil"></span></button></a></td>
                                                                <td><a onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')" href="<?php echo base_url('users/deleteUsers/' . $k['id_user']) ?>"><button class="widget-icon btn-danger"><span class="icon-trash"></span></button></a></td>
                                                            </tr>
                                                <?php }
                                                    }
                                                endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>