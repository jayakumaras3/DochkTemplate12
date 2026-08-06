<?php
$userlevel = session()->get('userlevel');
$arrayuserlevel  = explode(',', $userlevel);
$sessionclient = session()->get('client');
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('my_training') ?>">Dashboard</a></li>
         
                </ol>
            </div>
            <h4 class="page-title"><?php echo $header; ?></h4>
        </div>
    </div>
</div>
<div class="col-lg-8">
    <div class="card">
        <div class="card-body">
            <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Client</th>
                        <th>No of Courses</th>
                        <!-- <th>No of Users</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $new = array();
                    $j = 0;
                    foreach ($clientlist as $eachclientlist) {
                        $clientid = $eachclientlist['id_c'];
                        $j++;
                    ?>
                        <tr>
                            <td><?php echo $j; ?></td>
                            <td><?php echo $eachclientlist['client_name'] ?></td>
                            <td>
                                <form class="form-horizontal" action="<?php echo base_url($form_link1) ?>" method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="client_id" value="<?php echo $eachclientlist['id_c']; ?>">
                                    <button type="submit" class="btn btn-sm widget-icon btn-info"><?php echo $eachclientlist['sc_cr_as_id_count']; ?></button>
                                </form>
                            </td>
                            <!-- <td>
                                <form class="form-horizontal" action="<?php echo base_url($form_link2) ?>" method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="id_c" value="<?php echo $eachclientlist['id_c']; ?>">
                                    <button type="submit" class="btn btn-sm widget-icon btn-warning"><?php echo $eachclientlist['user_count'] ?></button>
                                </form>
                            </td> -->
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>