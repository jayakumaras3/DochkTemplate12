<?php $userlevel = session()->get('userlevel');
$arrayuserlevel  = explode(',',$userlevel);
$sessionclient = session()->get('client');
$arrayclient  = explode(',', $sessionclient);

?>

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li>Admin</li><b>&nbsp;>&nbsp;</b>
            <li class="active">Users List</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-2" style="margin-left: 83%;">
        <div class="block">
            <a href="<?php echo base_url() . "/scorm_client_users/clientregister?cid=" . base64_encode($arrayclient[0]) ?>"><button class="btn btn-info btn-sm form-control"><span class="icon-plus"></span> Add Users</button></a>
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
                                        <table id="datatable-fixed-header" width="100%" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th><?php echo lang('UI_Text.Username') ?></th>
                                                    <th><?php echo lang('UI_Text.Name') ?></th>
                                                    <th>Userlevel</th>
                                                    <th><?php echo lang('UI_Text.Last_Active') ?></th>
                                                    <th><?php echo lang('UI_Text.Edit') ?></th>
                                                    <th><?php echo lang('UI_Text.Delete') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php if (!empty($usertable)) : foreach ($usertable as $k) {
                                                        // $clientid = array_map('intval', str_split($k['clientid']));
                                                        if ($k['clientid'] == $arrayclient[0]) {
                                                            /* 44- client admin  4- TQ project manager */
                                                            if (in_array('44', $arrayuserlevel) || in_array('4', $arrayuserlevel) || in_array('6', $arrayuserlevel)) {
                                                ?>
                                                                <tr>
                                                                    <td><?php echo $k['username'] ?></td>
                                                                    <td><?php echo $k['name'] ?></td>
                                                                    <td><?php echo $k['userlevel'] ?></td>
                                                                    <td><?php echo isset($k['dateandtime']) ? date("m-d-Y", $k['dateandtime']) : ''; ?></td>
                                                                    <?php
                                                                    $temp_id = base64_encode($k['id_user']);
                                                                    //print_r($temp_id);
                                                                    ?>
                                                                    <td><a href="<?php echo base_url('scorm_client_users/editUsers/' . $temp_id) ?>"><button class="btn btn-sm widget-icon btn-info"><span class="icon-pencil"></span></button></a></td>
                                                                    <td><a onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')" href="<?php echo base_url('scorm_client_users/deleteUsers/' . $k['id_user']) ?>"><button class="btn btn-sm widget-icon btn-danger"><span class="icon-trash"></span></button></a></td>
                                                                </tr>
                                                <?php }
                                                        }
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