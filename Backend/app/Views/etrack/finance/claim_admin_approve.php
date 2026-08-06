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
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/Fin_admin/claim_admin'); ?>">
                            Claims
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                Claims Admin Review
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-8 col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label>
                            <h5>Vendor</h5>
                        </label><br>
                        <?php echo $claim_details[0]['vendor_short_name']; ?>
                    </div>
                    <div class="col-md-3">
                        <label>
                            <h5>Raised By</h5>
                        </label><br>
                        <?php echo $claim_details[0]['name']; ?>
                    </div>
                    <div class="col-md-3">
                        <label>
                            <h5>Raised On</h5>
                        </label><br>
                        <?php echo $claim_details[0]['requested_on']; ?>
                    </div>
                    <div class="col-md-3">
                        <label>
                            <h5>Mode of Payment</h5>
                        </label><br>
                        <?php $mode = $claim_details[0]['mode'];
                        switch ($mode) {
                            case 1:
                                echo 'Personal';
                                break;
                            case 2:
                                echo 'Srikant CC';
                                break;
                            case 3:
                                echo 'Frank CC';
                                break;
                            case 4:
                                echo 'Company';
                                break;
                        }
                        ?>
                    </div>
                </div>
                <div class="row mt-2">

                    <div class="col-md-3">
                        <label>
                            <h5>Status</h5>
                        </label><br>
                        <?php $status = $claim_details[0]['status'];
                        switch ($status) {
                            case 1:
                                echo 'New';
                                break;
                            case 2:
                                echo 'Mng Approved';
                                break;
                            case 3:
                                echo 'Shrikant Approved';
                                break;
                            case 4:
                                echo 'Paid';
                                break;
                            case 10:
                                echo 'Rejected';
                                break;
                        }
                        ?>
                    </div>
                    <div class="col-md-9">
                        <label>
                            <h5>Remarks</h5>
                        </label><br>
                        <?php echo $claim_details[0]['description']; ?>
                    </div>

                </div>
                <div class="row mt-2">
                    <div class="col-md-3">
                        <label>
                            <h5>Primary Currency</h5>
                        </label><br>
                        <?php $primary = $claim_details[0]['primary_currency'];
                        switch ($primary) {
                            case 1:
                                echo 'USD';
                                break;
                            case 2:
                                echo 'INR';
                                break;
                        }
                        ?>
                    </div>
                    <div class="col-md-3">
                        <label>
                            <h5>USD Value</h5>
                        </label><br>
                        <?php echo $claim_details[0]['claim_amount_usd']; ?>
                    </div>
                    <div class="col-md-3">
                        <label>
                            <h5>INR Value</h5>
                        </label><br>
                        <?php echo $claim_details[0]['claim_amount_inr']; ?>
                    </div>
                    <div class="col-md-3">
                        <label>
                            <h5>Exchange Rate</h5>
                        </label><br>
                        <?php echo $claim_details[0]['exchange_rate']; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/Fin_admin/approve_claim'); ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="inputEmail3" class="col-12 col-xl-12  col-form-label">Active UCN</label>
                            <div class="col-12 col-xl-12">
                                <select name="ucn" class="form-control">
                                    <?php
                                    foreach ($active_ucn as $ucn) {
                                        echo '<option value="' . $ucn['ucn_id'] . '">' . $ucn['name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="inputEmail3" class="col-12 col-xl-12  col-form-label">Percentage Allocation</label>
                            <div class="col-12 col-xl-12">
                                <input type="number" class="form-control" name="percentage" value="">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <input type="hidden" name="vd_id" value="<?php echo $claim_details[0]['vd_id']; ?>">
                            <button type="submit" class="mt-4 btn btn-outline-info btn-xs waves-effect waves-light">Link UCN</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>UCN</th>
                                    <th>Name</th>
                                    <th>%</th>
                                    <th>USD</th>
                                    <th>Del</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $j = 0;
                                foreach ($linked_ucn as $linked) {
                                    $j++;
                                    echo '<tr><td>';
                                    echo $j;
                                    echo '</td><td>';
                                    echo $linked['ucn'];
                                    echo '</td><td>';
                                    echo $linked['name'];
                                    echo '</td><td>';
                                    echo $linked['percent'];
                                    echo '</td><td>';
                                    echo $linked['value_allocation'];
                                    echo '</td><td>';
                                    echo '</td></tr>';
                                }

                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end col-->

    <div class="col-xl-4 col-md-4">
        <?php if ($status == 1) { ?>
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">First Level Approver</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <form class="form-horizontal" action="<?php echo base_url('etrack/Fin_admin/approve_claim'); ?>" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="status" value="2">
                                <input type="hidden" name="vd_id" value="<?php echo $claim_details[0]['vd_id']; ?>">
                                <button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">Approved</button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form class="form-horizontal" action="<?php echo base_url('etrack/Fin_admin/approve_claim'); ?>" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="status" value="10">
                                <input type="hidden" name="vd_id" value="<?php echo $claim_details[0]['vd_id']; ?>">
                                <button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light">Reject</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if ($status == 2) { ?>
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Shrikant Approval</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <form class="form-horizontal" action="<?php echo base_url('etrack/Fin_admin/approve_claim'); ?>" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="status" value="3">
                                <input type="hidden" name="vd_id" value="<?php echo $claim_details[0]['vd_id']; ?>">
                                <button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">Approved</button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form class="form-horizontal" action="<?php echo base_url('etrack/Fin_admin/approve_claim'); ?>" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="status" value="10">
                                <input type="hidden" name="vd_id" value="<?php echo $claim_details[0]['vd_id']; ?>">
                                <button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light">Reject</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if ($status == 3) { ?>
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Finance</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <form class="form-horizontal" action="<?php echo base_url('etrack/Fin_admin/approve_claim'); ?>" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="status" value="4">
                                <input type="hidden" name="vd_id" value="<?php echo $claim_details[0]['vd_id']; ?>">
                                <button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">PAID</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>