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
                    <?php if (in_array('4154', $arrayuserlevel)) { ?>
                        <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/ITSupport/support_admin'); ?>">
                                IT Support Admin
                            </a>
                        </li>
                    <?php } else { ?>
                        <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/ITSupport'); ?>">
                                IT Support
                            </a>
                        </li>
                    <?php } ?>
                </ol>
            </div>
            <h4 class="page-title">
                IT Support Detail
            </h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card d-block">
            <div class="card-body">



                <h4 class="mb-1 mt-0 font-18"><?php echo $support_ticket_details[0]['short_desc']; ?></h4>


                <label class="mt-2">Details :</label>
                <p class="text-muted  mb-2">
                    <?php echo $support_ticket_details[0]['detail_desc']; ?>
                </p>
                <div class="row">
                    <div class="col-md-6">
                        <!-- Ticket type -->
                        <label class="mt-2 mb-1">Ticket Type :</label>
                        <p>
                            IT Ticket
                        </p>
                        <!-- end Ticket Type -->
                    </div>

                    <div class="col-md-6">
                        <!-- assignee -->
                        <label class="mt-2 mb-1">Assigned To :</label>
                        <div class="d-flex align-items-start">

                            <div class="w-100">
                                <p> <?php echo $support_ticket_details[0]['name']; ?> </p>
                            </div>
                        </div>
                        <!-- end assignee -->
                    </div> <!-- end col -->
                </div> <!-- end row -->

                <div class="row">
                    <div class="col-md-6">
                        <!-- assignee -->
                        <label class="mt-2 mb-1">Created On :</label>
                        <p><?php echo date('Y-m-d', $support_ticket_details[0]['created_on']); ?></p>
                        <!-- end assignee -->
                    </div> <!-- end col -->

                    <div class="col-md-6">
                        <!-- assignee -->
                        <label class="mt-2 mb-1">Updated On :</label>
                        <p><?php echo date('Y-m-d', $support_ticket_details[0]['last_updated_on']); ?></p>
                        <!-- end assignee -->
                    </div> <!-- end col -->
                </div> <!-- end row -->

                <div class="row">
                    <div class="col-md-6">
                        <!-- Status -->
                        <label class="mt-2 form-label">Status :</label>
                        <div class="row">
                            <div class="col-auto">
                                <?php $type = $support_ticket_details[0]['status'];
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
                                ?>
                            </div>
                        </div>
                        <!-- end Status -->
                    </div> <!-- end col -->

                    <div class="col-md-6">
                        <!-- Priority -->
                        <label class="mt-2 mb-1">Priority :</label>
                        <div class="row">
                            <div class="col-auto">
                                <?php $priortiy = $support_ticket_details[0]['priority'];
                                switch ($priortiy) {
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
                                ?>
                            </div>
                        </div>
                        <!-- end Priority -->
                    </div> <!-- end col -->
                </div> <!-- end row -->
            </div> <!-- end card-body-->
        </div>

        <div class="card">
            <div class="card-body">
                <h4 class="mb-4 mt-0 font-16">Responses (<?php echo count($support_ticket_reply); ?>)</h4>
                <div class="clerfix"></div>
                <?php
                foreach ($support_ticket_reply as $replies) {
                ?>
                    <div class="d-flex align-items-start mb-2">
                        <div class="w-100">
                            <h5 class="mt-0 mb-1"><?php echo $replies['name']; ?> <small class="text-muted float-end"><?php echo date('Y-m-d', $replies['last_updated_on']); ?></small></h5>
                            <?php echo $replies['reply']; ?>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div> <!-- end card-body-->
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/ITSupport/reply_ticket'); ?>" method="post" id="submitForm"><?= csrf_field() ?>
                    <div class="row mb-1">
                        <label for="inputEmail3" class="col-12 col-xl-12 col-form-label">Status</label>
                        <div class="col-12 col-xl-12">
                            <select name="status" class="form-control">
                                <option value="2">Respond</option>
                                <?php if ($user == $support_ticket_details[0]['id_user']) { ?>
                                    <option value="4">Close</option>
                                    <option value="3">Re-Open</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <?php if (in_array('4154', $arrayuserlevel)) { ?>
                        <div class="row mb-1">
                            <label for="inputEmail3" class="col-12 col-xl-12 col-form-label">Assigned To</label>
                            <div class="col-12 col-xl-12">
                                <select name="assigned_to" class="form-control">
                                    <option value="1103" <?php if ($support_ticket_details[0]['assigned_to'] == $user) echo 'selected'; ?>>Arun</option>
                                    <option value="1" <?php if ($support_ticket_details[0]['assigned_to'] == $user) echo 'selected'; ?>>Pramod</option>
                                </select>
                            </div>
                        </div>
                    <?php } else {
                        echo '<input type="hidden" name="assigned_to" value="' . $support_ticket_details[0]['assigned_to'] . '">';
                    } ?>

                    <div class="row mb-2">
                        <label for="inputEmail3" class="col-12 col-xl-12 col-form-label">Details</label>
                        <div class="col-12 col-xl-12">
                            <textarea name="long_description" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="justify-content-end row">
                        <div class="col-8 col-xl-9">
                            <input type="hidden" name="et_sup_id" value="<?php echo $support_ticket_details[0]['et_sup_id']; ?>">
                            <button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light" id="submitButton">
                                Reply To Ticket
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>