<?php
$userlevel = session('userlevel');
$id_user = session('id_user');
$arrayuserlevel  = array_map('intval', explode(',', $userlevel) ?? '');
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/dashboard'); ?>">
                            Dashboard
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                IT Support
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle bg-primary">
                            <i class="fe-tag font-22 avatar-title text-white"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo count($support_tickets); ?></span></h3>
                            <p class="text-muted mb-1 text-truncate">Total Tickets</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->

    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle bg-warning">
                            <i class="fe-clock font-22 avatar-title text-white"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo count($support_tickets_open); ?></span></h3>
                            <p class="text-muted mb-1 text-truncate">New Tickets</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->

    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle bg-success">
                            <i class="fe-check-circle font-22 avatar-title text-white"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo count($support_tickets_closed); ?></span></h3>
                            <p class="text-muted mb-1 text-truncate">Closed Tickets</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->

    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg rounded-circle bg-danger">
                            <i class="mdi mdi-lock-open-check-outline font-22 avatar-title text-white"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo count($support_tickets_reopen); ?></span></h3>
                            <p class="text-muted mb-1 text-truncate">Re-Open Tickets</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/ITSupport/add_ticket'); ?>" method="post" id="submitForm"><?= csrf_field() ?>
                    <div class="row mb-1">
                        <label for="inputEmail3" class="col-12 col-xl-12 col-form-label">Location</label>
                        <div class="col-12 col-xl-12">
                            <input type="text" name="location" class="form-control" required value="">
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label for="inputEmail3" class="col-12 col-xl-12 col-form-label">Short Description</label>
                        <div class="col-12 col-xl-12">
                            <input type="text" name="short_description" class="form-control" required value="">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="inputEmail3" class="col-12 col-xl-12 col-form-label">Details</label>
                        <div class="col-12 col-xl-12">
                            <textarea name="long_description" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="inputEmail3" class="col-12 col-xl-12 col-form-label">Priority</label>
                        <div class="col-12 col-xl-12">
                            <select name="priority" class="form-control">
                                <option value="1">Low</option>
                                <option value="2">Medium</option>
                                <option value="3">High</option>
                            </select>
                        </div>
                    </div>
                    <div class="justify-content-end row">
                        <div class="col-8 col-xl-9">
                            <button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light" id="submitButton">
                                Create New IT Ticket
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <table id="searchdatatable" class="table table-bordered table-striped ">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Location</th>
                            <th>Short Description</th>
                            <th>Status</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        foreach ($support_tickets as $tickets) {
                            $j++;
                        ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo $tickets['location']; ?></td>
                                <td><?php echo $tickets['short_desc']; ?></td>
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
    </div>

</div>