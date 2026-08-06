<?php $userlevel = session()->get('userlevel');
$arrayuserlevel  = explode(',', $userlevel);
$sessionclient = session()->get('client');

?>

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url($header_link); ?>"><?php echo $header; ?></a></li><b>&nbsp;>&nbsp;</b>

        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="block">
            <div class="x_panel">
                <div class="content tab-content bg-dot50">
                    <div class="tab-pane active" id="tab13">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="block">
                                    <div class="content">
                                        <table id="datatable-fixed-header" class="table  table-sm table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>User</th>
                                                    <th># Courses</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $j = 0;
                                                if (!empty($usertable)) : foreach ($usertable as $k) { 
                                                        $j =$j+1;?>
                                                        <tr>
                                                            <td><?php echo $j ?></td>
                                                            <td><?php echo $k['name'] ?></td>
                                                            <td>
                                                                <form class="form-horizontal" action="<?php echo base_url($form_link1) ?>" method="POST"><?= csrf_field() ?>
                                                                    <input type="hidden" name="userid" value="<?php echo $k['id_user'] ?>">
                                                                    <input type="hidden" name="client_id" value="<?php echo $clientid ?>">
                                                                    <button type="submit" class="btn btn-sm widget-icon btn-warning"><?php echo $k['courseassignedcount'] ?></button>
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