<?php
$userlevel = session()->get('userlevel');
$arrayuserlevel  = explode(',', $userlevel);
$sessionclient = session()->get('client');
?>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li class="active">Demo Clients</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <table id="datatable-fixed-header" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Client</th>
                        <th>Users / License</th>
                        <th>Start date</th>
                        <th>End date</th>
                        <th>No of Courses</th>
                        <th>No of Users</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $new = array();
                    $j = 0;
                    foreach ($clientlist as $eachclientlist) {
                        // print_r($eachclientlist);
                        // $clientid = array_map('intval', str_split($eachclientlist['clientid']));
                        $clientid = $eachclientlist['id_c'];
                        //    if (session()->get('username') == 'admin' || in_array('4', $arrayuserlevel) || in_array('6', $arrayuserlevel)) {
                        $j++;
                    ?>
                        <tr>
                            <td><?php echo $j; ?></td>
                            <td><?php echo $eachclientlist['client_name'] ?></td>
                            <td> - / <?php echo $eachclientlist['license'] ?></td>
                            <td><?php echo ($eachclientlist['start_date'] != NULL) ? date('m-d-Y', strtotime($eachclientlist['start_date'])) : '' ?></td>
                            <td><?php echo ($eachclientlist['end_date'] != NULL) ? date('m-d-Y', strtotime($eachclientlist['end_date'])) : '' ?></td>
                            <td>
                                <form class="form-horizontal" action="<?php echo base_url('demo_client/view_client_course_assigned') ?>" method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="client_id" value="<?php echo $eachclientlist['id_c']; ?>">
                                    <button type="submit" class="btn btn-sm widget-icon btn-info"><?php echo $eachclientlist['sc_cr_as_id_count']; ?></button>
                                </form>
                            </td>
                            <td>
                                <form class="form-horizontal" action="<?php echo base_url('demo_users') ?>" method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="id_c" value="<?php echo $eachclientlist['id_c']; ?>">
                                    <button type="submit" class="btn btn-sm widget-icon btn-info"><?php echo $eachclientlist['user_count'] ?></button>
                                </form>
                            </td>
                        </tr>
                    <?php }
                    //  }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(function($) {
        $('#dynamic-table1').DataTable();
        $('#dynamic-table2').DataTable();
        $('#dynamic-table3').DataTable();
        $('#dynamic-table4').DataTable();
        $('#dynamic-table5').DataTable();
    });
</script>