<?php $userlevel = session()->get('userlevel');
$arrayuserlevel  = explode(',', $userlevel);
$sessionclient = session()->get('client');

?>

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('Demo/demo_client') ?>">Demo Clients</a></li><b> &nbsp;> &nbsp;</b>
      
        </ol>
    </div>
</div>
<div class="row">
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
                                        <table id="datatable-fixed-header" class="table table-sm  table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Username</th>
                                                    <th>Courses</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php $j = 0;
                                                if (!empty($usertable)) : foreach ($usertable as $k) { 
                                                        // $clientidd = array_map('intval', str_split($k['clientid']));
                                                        // print_r($k);
                                                        $j =$j+1;?>
                                                        <tr>
                                                            <td><?php echo $j ?></td>
                                                            <td><?php echo $k['name'] ?></td>
                                                            <td>
                                                                <form class="form-horizontal" action="<?php echo base_url('demo_users/users_courses_assign') ?>" method="POST"><?= csrf_field() ?>
                                                                    <input type="hidden" name="id_user" value="<?php echo $k['id_user'] ?>">
                                                                    <input type="hidden" name="client_id" value="<?php echo $clientid ?>">
                                                                    <button type="submit" class="btn btn-sm widget-icon btn-info"><?php echo $k['courseassignedcount'] ?></button>
                                                                </form>
                                                            </td>
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
                </div>
            </div>
        </div>
    </div>
</div>