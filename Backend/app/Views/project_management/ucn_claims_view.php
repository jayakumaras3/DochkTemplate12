<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/PM_ucn/edit_ucn'); ?>">Edit UCN</a></li>
                </ol>
            </div>
            <h4 class="page-title">UCN Claims</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-centered table-striped dt-responsive nowrap w-100" id="claims_table">
                        <thead>
                            <tr>
                                <th>Claim ID</th>
                                <th>Vendor Name</th>
                                <th>Description</th>
                                <th>Claim Amount</th>
                                <th>Claim Date</th>
                                <th>Requested By</th>
                                <th>Status</th>
                                <th>Approve</th>
                                <th>Reject</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($claims)) { ?>
                                <?php foreach ($claims as $claim) { ?>
                                    <tr>
                                        <td><?php echo $claim['vd_id']; ?></td>
                                        <td><?php echo $claim['vendor_name']; ?></td>
                                        <td><?php echo $claim['description']; ?></td>
                                        <td style="text-align: right;"><?php echo '$ ' . preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $claim['claim_amount_usd']); ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($claim['requested_on'])); ?></td>
                                        <td><?php echo $claim['requested_by_name']; ?></td>
                                        <td><?php
                                            switch ($claim['status']) {
                                                case 1:
                                                    echo 'New';
                                                    break;
                                                case 2:
                                                    echo 'Submitted to PM';
                                                    break;
                                                case 3:
                                                    echo 'PM Approved';
                                                    break;
                                                case 4:
                                                    echo 'Submitted to PC';
                                                    break;
                                                case 5:
                                                    echo 'Pramod Approved';
                                                    break;
                                                case 6:
                                                    echo 'Submitted to Shrikant';
                                                    break;
                                                case 7:
                                                    echo 'Shrikant Approved';
                                                    break;
                                                case 8:
                                                    echo 'Submitted to Finance';
                                                    break;
                                                case 9:
                                                    echo 'Finance Approved';
                                                    break;
                                                case 10:
                                                    echo 'Rejected';
                                                    break;
                                                case 11:
                                                    echo 'Paid';
                                                    break;
                                            }
                                            ?></td>
                                        <td>
                                            <?php if ($claim['status'] == 2) { ?>
                                                <form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_ucn/claim_action'); ?>" method="POST">
                                                    <input type="hidden" name="claim_id" value="<?php echo $claim['vd_id']; ?>">
                                                    <input type="text" name="notes" placeholder="Enter Notes" class="form-control mb-2">
                                                    <input type="hidden" name="action" value="3">
                                                    <button type="submit" class="btn btn-outline-success waves-effect btn-xs waves-light">Approve</button>
                                                </form>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if ($claim['status'] == 2) { ?>
                                                <form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_ucn/claim_action'); ?>" method="POST">
                                                    <input type="hidden" name="claim_id" value="<?php echo $claim['vd_id']; ?>">
                                                    <input type="text" name="notes" placeholder="Enter Notes" class="form-control mb-2">
                                                    <input type="hidden" name="action" value="10">
                                                    <button type="submit" class="btn btn-outline-danger waves-effect btn-xs waves-light">Reject</button>
                                                </form>
                                            <?php } ?>
                                        </td>

                                    </tr>
                                <?php } ?>
                            <?php } else { ?>

                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>