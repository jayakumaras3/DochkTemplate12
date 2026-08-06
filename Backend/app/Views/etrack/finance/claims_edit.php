<?php

$userlevel = session('userlevel');
$id_user = session('id_user');
$arrayuserlevel  = array_map('intval', explode(',', $userlevel) ?? '');

?>
<style>
    /* Same rounded-corner + shadow treatment used across the redesigned etrack pages
       (e.g. Etrack/claims list, etrack/attendance/view). */
    .claim-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .claim-card table thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
    }

    [data-bs-theme="dark"] .claim-card table thead th {
        border-bottom-color: #36404a;
        color: #cedeef;
    }

    .claim-card table tbody td {
        vertical-align: middle;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('Etrack/claims'); ?>">
                            Claims
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                Claim Edit | Status (<?php
                                        if (isset($vendor_payment_details) && !empty($vendor_payment_details)) {
                                            switch ($vendor_payment_details[0]['status']) {
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
                                        }
                                        ?>)
            </h4>
        </div>
    </div>
</div>
<?php if (isset($vendor_payment_details) && !empty($vendor_payment_details) && $id_user == $vendor_payment_details[0]['requested_by'] && ($vendor_payment_details[0]['status'] == 1 || $vendor_payment_details[0]['status'] == 10)) { ?>

    <div class="row">
        <div class="col-xl-8 col-md-8">
            <div class="card claim-card">
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('etrack/claims/update_claim'); ?>" method="POST"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="inputEmail3" class="col-12 col-xl-12  col-form-label">Vendor Name</label>
                                <div class="col-12 col-xl-12">
                                    <input type="text" class="form-control" name="vendor_name" value="<?php echo $vendor_payment_details[0]['vendor_name']; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="inputEmail3" class="col-12 col-xl-12  col-form-label">Currency</label>
                                <div class="col-12 col-xl-12">
                                    <select name="currency" class="form-control">
                                        <option value="1" <?php
                                                            if ($vendor_payment_details[0]['primary_currency'] == 1) {
                                                                echo 'SELECTED';
                                                            }
                                                            ?>>USD</option>
                                        <option value="2" <?php
                                                            if ($vendor_payment_details[0]['primary_currency'] == 2) {
                                                                echo 'SELECTED';
                                                            }
                                                            ?>>INR</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="inputEmail3" class="col-12 col-xl-12  col-form-label">Amount</label>
                                <div class="col-12 col-xl-12">
                                    <input type="number" name="amount" class="form-control" value="<?php if ($vendor_payment_details[0]['primary_currency'] == 2) {
                                                                                                        echo $vendor_payment_details[0]['claim_amount_inr'];
                                                                                                    } else {
                                                                                                        echo $vendor_payment_details[0]['claim_amount_usd'];
                                                                                                    }
                                                                                                    ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="inputEmail3" class="col-12 col-xl-12  col-form-label">Payment Mode</label>
                                <div class="col-12 col-xl-12">
                                    <select name="payment_mode" class="form-control">
                                        <option value="1" <?php
                                                            if ($vendor_payment_details[0]['mode'] == 1) {
                                                                echo 'SELECTED';
                                                            }
                                                            ?>>Personal CC</option>
                                        <option value="2" <?php
                                                            if ($vendor_payment_details[0]['mode'] == 2) {
                                                                echo 'SELECTED';
                                                            }
                                                            ?>>Shrikant CC</option>
                                        <option value="3" <?php
                                                            if ($vendor_payment_details[0]['mode'] == 3) {
                                                                echo 'SELECTED';
                                                            }
                                                            ?>>Frank CC</option>
                                        <option value="4" <?php
                                                            if ($vendor_payment_details[0]['mode'] == 4) {
                                                                echo 'SELECTED';
                                                            }
                                                            ?>>Pramod CC</option>
                                        <option value="5" <?php
                                                            if ($vendor_payment_details[0]['mode'] == 5) {
                                                                echo 'SELECTED';
                                                            }
                                                            ?>>Office</option>
                                        <option value="6" <?php
                                                            if ($vendor_payment_details[0]['mode'] == 6) {
                                                                echo 'SELECTED';
                                                            }
                                                            ?>>Cash</option>
                                        <option value="7" <?php
                                                            if ($vendor_payment_details[0]['mode'] == 7) {
                                                                echo 'SELECTED';
                                                            }
                                                            ?>>Online</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="inputEmail3" class="col-12 col-xl-12  col-form-label">Claim Date</label>
                                <div class="col-12 col-xl-12">
                                    <input id="start_date" name="claim_date" class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="<?php echo $vendor_payment_details[0]['requested_on']; ?>">
                                    <script>
                                        function timeFunctionLong(input) {
                                            setTimeout(function() {
                                                input.type = 'text';
                                            }, 60000);
                                        }
                                    </script>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="inputEmail3" class="col-12 col-xl-12  col-form-label">Expense Head</label>
                                <div class="col-12 col-xl-12">

                                    <select name="expense_head" class="form-control">
                                        <?php $type=0; $expense_list = $expense_list ?? [];
                                        foreach ($expense_list as $key => $value) { ?>
                                            <option value="<?php echo $key ?>" <?php if ($vendor_payment_details[0]['expense_head'] == $key) {
                                                                                    echo 'selected';
                                                                                    $type = 1;
                                                                                } ?>>** <?php echo $value ?></option>
                                        <?php } ?>
                                        <?php if (!empty($active_ucn)) { ?>
                                            <?php foreach ($active_ucn as $head) { ?>
                                                <option value="<?php echo $head['ucn_id']; ?>" <?php if ($vendor_payment_details[0]['expense_head'] == $head['ucn_id']) {
                                                                                                    echo 'selected';
                                                                                                    $type = 2;
                                                                                                } ?>><?php echo $head['name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <label for="inputEmail3" class="col-12 col-xl-12  col-form-label">Description</label>
                                <div class="col-12 col-xl-12">
                                    <textarea name="description" class="form-control"><?php echo $vendor_payment_details[0]['description']; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="justify-content-end row mt-3">
                            <input type="hidden" name="exchange_rate" class="form-control" value="<?php echo $vendor_payment_details[0]['exchange_rate']; ?>">
                            <input type="hidden" name="vd_id" class="form-control" value="<?php echo $vendor_payment_details[0]['vd_id']; ?>">
                            <div class="col-12 col-xl-12">
                                <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                    Update Claim
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-4">
            <?php if ($type == 2) { ?>
            <div class="card claim-card">
                <div class="card-body">
                    <p>If claim is related to any project, the expense head should be the linked UCN. Project Manager need to approve the claim before it can be processed.</p>
                    </br>
                    <form class="form-horizontal" action="<?php echo base_url('etrack/claims/claim_status_change'); ?>" method="POST">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="vd_id" class="form-control" value="<?php echo $vendor_payment_details[0]['vd_id']; ?>">
                                <input type="hidden" name="notes" class="form-control" value="">
                                <input type="hidden" name="status" class="form-control" value="2">
                                <button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                    Submit to Project Manager for Approval
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php } else { ?>
            <div class="card claim-card">
                <div class="card-body">
                    <p>If claim is not related to any specific project, the expense head should be one of the general expense heads. Pramod Chandran need to approve the claim before it can be processed.</p>
                    <form class="form-horizontal" action="<?php echo base_url('etrack/claims/claim_status_change'); ?>" method="POST">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="vd_id" class="form-control" value="<?php echo $vendor_payment_details[0]['vd_id']; ?>">
                                <input type="hidden" name="notes" class="form-control" value="">
                                <input type="hidden" name="status" class="form-control" value="4">
                                <button type="submit" class="btn btn-outline-warning btn-xs waves-effect waves-light">
                                    Submit to Pramod Chandran for Approval
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card claim-card">
                <div class="card-body">
                    <p>If claim is not related to any specific project, and general purpose exspenses by Facility team that need approval from Shrikant.</p>
                    <form class="form-horizontal" action="<?php echo base_url('etrack/claims/claim_status_change'); ?>" method="POST">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="vd_id" class="form-control" value="<?php echo $vendor_payment_details[0]['vd_id']; ?>">
                                <input type="hidden" name="notes" class="form-control" value="">
                                <input type="hidden" name="status" class="form-control" value="6">
                                <button type="submit" class="btn btn-outline-success btn-xs waves-effect waves-light">
                                    Submit to Shrikant for Approval
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

<?php } else { ?>
       <div class="row">
            <div class="col-xl-8 col-md-8">
                <div class="card claim-card">
                    <div class="card-body">
                        <table class="table">

                            <tr class="table-light">
                                <th>Vendor</th>
                                <th>Claim Date</th>
                                <th>Mode</th>
                                <th>Amount (INR)</th>
                                <th>Amount (USD)</th>
                                <th>Expense Head</th>
                            </tr>
                            <tr>
                                <td><?php echo isset($vendor_payment_details) ? $vendor_payment_details[0]['vendor_name'] : 'N/A'; ?></td>
                                <td><?php echo isset($vendor_payment_details) ? $vendor_payment_details[0]['requested_on'] : 'N/A'; ?></td>
                                <td><?php
                                    if (isset($vendor_payment_details)) {
                                        switch ($vendor_payment_details[0]['mode']) {
                                            case 1:
                                                echo 'Personal CC';
                                                break;
                                            case 2:
                                                echo 'Srikant CC';
                                                break;
                                            case 3:
                                                echo 'Frank CC';
                                                break;
                                            case 4:
                                                echo 'Pramod CC';
                                                break;
                                            case 5:
                                                echo 'Office';
                                                break;
                                            case 6:
                                                echo 'Cash';
                                                break;
                                            case 7:
                                                echo 'Online';
                                                break;
                                        }
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?></td>
                                <td style="text-align:right">₹ <?php echo isset($vendor_payment_details) ? number_format($vendor_payment_details[0]['claim_amount_inr']) : 'N/A'; ?></td>
                                <td style="text-align:right">$ <?php echo isset($vendor_payment_details) ? number_format($vendor_payment_details[0]['claim_amount_usd']) : 'N/A'; ?></td>
                                <td><?php if ($vendor_payment_details[0]['expense_head'] > 9 && $vendor_payment_details[0]['expense_head'] < 50) {
                                        echo '** ' . ($expense_list[$vendor_payment_details[0]['expense_head']] ?? 'N/A');
                                    } elseif ($vendor_payment_details[0]['expense_head'] > 50) {
                                        echo $vendor_payment_details[0]['expense_head'] . ' - ' . $vendor_payment_details[0]['expense_head_name'];
                                    } else {
                                        echo 'N/A';
                                    } ?></td>
                            </tr>
                            <tr class="table-light">
                                <th colspan="6">Description</th>
                            </tr>
                            <tr>
                                <td colspan="6"><?php echo isset($vendor_payment_details) ? $vendor_payment_details[0]['description'] : 'N/A'; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-4">
                <?php if ($id_user == 1 && ($vendor_payment_details[0]['status'] == 3 || $vendor_payment_details[0]['status'] == 4)) { ?>

                    <div class="card claim-card">
                        <div class="card-body">
                            <form class="form-horizontal" action="<?php echo base_url('etrack/claims/claim_status_change'); ?>" method="POST">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="vd_id" class="form-control" value="<?php echo $vendor_payment_details[0]['vd_id']; ?>">
                                        <input type="text" name="notes" class="form-control mb-2" value="Pramod approved.">
                                        <input type="hidden" name="status" class="form-control" value="8">
                                        <button type="submit" class="btn btn-outline-success btn-xs waves-effect waves-light">
                                            Claim Approved by PC
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card claim-card">
                        <div class="card-body">
                            <form class="form-horizontal" action="<?php echo base_url('etrack/claims/claim_status_change'); ?>" method="POST">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="vd_id" class="form-control" value="<?php echo $vendor_payment_details[0]['vd_id']; ?>">
                                        <input type="text" name="notes" class="form-control mb-2" value="Pramod rejected.">
                                        <input type="hidden" name="status" class="form-control" value="10">
                                        <button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                            Claim Rejected by PC
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card claim-card">
                        <div class="card-body">
                            <form class="form-horizontal" action="<?php echo base_url('etrack/claims/claim_status_change'); ?>" method="POST">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="vd_id" class="form-control" value="<?php echo $vendor_payment_details[0]['vd_id']; ?>">
                                        <input type="text" name="notes" class="form-control mb-2" value="Pramod approved. Need approval from Shrikant.">
                                        <input type="hidden" name="status" class="form-control" value="6">
                                        <button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                            Claim Require approval from Shrikant
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                <?php } ?>
                <?php if ($id_user == 1138 && ($vendor_payment_details[0]['status'] == 6)) { ?>

                    <div class="card claim-card">
                        <div class="card-body">
                            <form class="form-horizontal" action="<?php echo base_url('etrack/claims/claim_status_change'); ?>" method="POST">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="vd_id" class="form-control" value="<?php echo $vendor_payment_details[0]['vd_id']; ?>">
                                        <input type="text" name="notes" class="form-control mb-2" value="Claim approved by Shrikant.">
                                        <input type="hidden" name="status" class="form-control" value="8">
                                        <button type="submit" class="btn btn-outline-success btn-xs waves-effect waves-light">
                                            Claim Approved by Shrikant
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card claim-card">
                        <div class="card-body">
                            <form class="form-horizontal" action="<?php echo base_url('etrack/claims/claim_status_change'); ?>" method="POST">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="vd_id" class="form-control" value="<?php echo $vendor_payment_details[0]['vd_id']; ?>">
                                        <input type="text" name="notes" class="form-control mb-2" value="Claim rejected by Shrikant.">
                                        <input type="hidden" name="status" class="form-control" value="10">
                                        <button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                            Claim Rejected by Shrikant
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                <?php } ?>
                <?php if ((in_array(3014, $arrayuserlevel)) && ($vendor_payment_details[0]['status'] == 8)) { ?>

                    <div class="card claim-card">
                        <div class="card-body">
                            <form class="form-horizontal" action="<?php echo base_url('etrack/claims/claim_status_change'); ?>" method="POST">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="vd_id" class="form-control" value="<?php echo $vendor_payment_details[0]['vd_id']; ?>">
                                        <input type="text" name="notes" class="form-control mb-2" value="Claim approved by Finance.">
                                        <input type="hidden" name="status" class="form-control" value="9">
                                        <button type="submit" class="btn btn-outline-success btn-xs waves-effect waves-light">
                                            Claim Approved by Finance
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card claim-card">
                        <div class="card-body">
                            <form class="form-horizontal" action="<?php echo base_url('etrack/claims/claim_status_change'); ?>" method="POST">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="vd_id" class="form-control" value="<?php echo $vendor_payment_details[0]['vd_id']; ?>">
                                        <input type="text" name="notes" class="form-control mb-2" value="Claim rejected by Finance.">
                                        <input type="hidden" name="status" class="form-control" value="10">
                                        <button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                            Claim Rejected by Finance
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php } ?>
                <?php if ((in_array(3014, $arrayuserlevel)) && ($vendor_payment_details[0]['status'] == 8 || $vendor_payment_details[0]['status'] == 9)) { ?>
                    <div class="card claim-card">
                        <div class="card-body">
                            <form class="form-horizontal" action="<?php echo base_url('etrack/claims/claim_status_change'); ?>" method="POST">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="vd_id" class="form-control" value="<?php echo $vendor_payment_details[0]['vd_id']; ?>">
                                        <input type="text" name="notes" class="form-control mb-2" value="Claim paid by Finance.">
                                        <input type="hidden" name="status" class="form-control" value="11">
                                        <button type="submit" class="btn btn-outline-success btn-xs waves-effect waves-light">
                                            Claim Paid by Finance
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    
<?php } ?>
<div class="row">
    <div class="col-8">
        <div class="card claim-card">
            <div class="card-body">
                <h4 class="header-title">Claim History</h4>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr class="table-light">
                                <th>Description</th>
                                <th>Notes</th>
                                <th>Updated By</th>
                                <th>Updated On</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($claim_history) && !empty($claim_history)) { ?>
                                <?php foreach ($claim_history as $history) { ?>
                                    <tr>
                                        <td><?php echo $history['description']; ?></td>
                                        <td><?php echo $history['notes']; ?></td>
                                        <td><?php echo $history['name'] . ' ' . $history['last_name']; ?></td>
                                        <td><?php echo date('Y-m-d', $history['last_updated_on']); ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="3" style="text-align:center;">No history found.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div> <!-- end table-responsive-->
            </div> <!-- end card-body-->
        </div>
    </div>

    <div class="col-4">
        <div class="card claim-card">
            <div class="card-body">
                <p class="text-muted font-14">
                    Upload supporting documents for this claim.<br>
                    Note: Only PDF documents are allowed and maximum file size is 5MB.
                </p>
                <form class="form-horizontal" action="<?php echo base_url('etrack/claims/upload_documents'); ?>" method="POST" enctype="multipart/form-data" id="submitForm"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="inputEmail3" class="col-12 col-xl-12  col-form-label">Upload Supporting Documents</label>
                            <div class="col-12 col-xl-12">
                                <input type="file" name="documents[]" class="form-control" multiple required>
                            </div>

                        </div>
                        <div class="col-md-12 mt-2">
                            <input type="hidden" name="vd_id" class="form-control" value="<?php echo $vendor_payment_details[0]['vd_id']; ?>">
                            <div class="col-12 col-xl-12">
                                <button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light" id="submitButton">
                                    Upload
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div> <!-- end card-body-->
        </div>

        <div class="card claim-card">
            <div class="card-body">

                <table class="table mb-0">
                    <thead>
                        <tr class="table-light">
                            <th>Documents</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $documents = session()->get('claim_documents') ?? [];

                        foreach ($documents as $key => $file):
                        ?>
                            <tr>
                                <td>
                                    <a href="<?= base_url('assets/assets/uploads/cliams/' . $file) ?>"
                                        target="_blank">
                                        <?= esc($file) ?>
                                    </a>
                                </td>
                                <td>
                                    <a href="<?= base_url('etrack/claims/delete_document/' . $key) ?>"
                                        class="btn btn-outline-danger btn-xs waves-effect waves-light"
                                        onclick="return confirm('Are you sure you want to delete this file?');">
                                        Delete <span class="mdi mdi-trash-can-outline"></span>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div> <!-- end card-body-->
        </div>
    </div>
</div>