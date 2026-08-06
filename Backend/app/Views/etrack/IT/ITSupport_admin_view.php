<?php
$userlevel = session('userlevel');
$id_user = session('id_user');
$arrayuserlevel  = array_map('intval', explode(',', $userlevel) ?? '');
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">
                IT Support Admin
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card border-primary border">
            <div class="card-body">
                <a href="<?php echo base_url('etrack/ITSupport/support_admin'); ?>">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-warning">
                                <i class="fe-clock font-22 avatar-title text-white"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <p class="text-muted mb-1 text-truncate">New Tickets</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </a>
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->
    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <a href="<?php echo base_url('etrack/ITSupport/in_progress_tickets'); ?>">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-danger">
                                <i class="mdi mdi-lock-open-check-outline font-22 avatar-title text-white"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <p class="text-muted mb-1 text-truncate">In Progress Tickets</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </a>
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->
    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <a href="<?php echo base_url('etrack/ITSupport/closed_tickets'); ?>">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded-circle bg-success">
                                <i class="fe-check-circle font-22 avatar-title text-white"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <p class="text-muted mb-1 text-truncate">Closed Tickets</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </a>
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->
</div>
<div class="row">
    <div class="col-md-12">
        <?php if (in_array('4154', $arrayuserlevel)) { ?>
            <div class="card">
                <div class="card-body">
                    <table id="searchdatatable" class="table table-bordered table-striped ">
                        <thead>
                            <tr>
                                <th class="center">#</th>
                                <th>Location</th>
                                <th>Short Description</th>
                                <th>User</th>
                                <th>Created</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $j = 0;
                            foreach ($get_open_tickerts as $tickets) {
                                $j++;
                            ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $tickets['location']; ?></td>
                                    <td><?php echo $tickets['short_desc']; ?></td>
                                    <td><?php echo $tickets['name']; ?></td>
                                    <td><?php echo date('m-d h:i', $tickets['created_on']); ?></td>
                                    <td><?php $type = $tickets['priority'];
                                        switch ($type) {
                                            case 1:
                                                echo 'Low';
                                                break;
                                            case 2:
                                                echo 'Medium';
                                                break;
                                            case 3:
                                                echo 'High';
                                                break;
                                        }
                                        ?></td>

                                    <td><?php $type = $tickets['status'];
                                        switch ($type) {
                                            case 1:
                                                echo 'New';
                                                break;
                                            case 2:
                                                echo 'Responded';
                                                break;
                                            case 3:
                                                echo 'Re-Open';
                                                break;
                                            case 4:
                                                echo 'Closed';
                                                break;
                                            case 5:
                                                echo 'Deleted';
                                                break;
                                        }
                                        ?></td>
                                    <td>

                                        <form class="form-horizontal" action="<?php echo base_url('etrack/ITSupport/view_ticket_details'); ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="et_sup_id" value="<?php echo $tickets['et_sup_id']; ?>">
                                            <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi mdi-eye-outline"></span></button>
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
        <?php } ?>

    </div>

</div>