<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <?php
            $userlevel = session()->get('userlevel');
            $arrayuserlevel = explode(',', $userlevel);
            if (in_array('2010', $arrayuserlevel) || in_array('3048', $arrayuserlevel) || in_array('3014', $arrayuserlevel) || in_array('69', $arrayuserlevel)) { ?>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/Fin_admin/dashboard'); ?>">
                                Finance Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#con-close-modal">Create New Sales Opportunity</a>
                        </li>
                    </ol>
                </div>
            <?php } ?>
            <h4 class="page-title">
                Sales Dashboard - <?php echo isset($sales_manager) ? ($sales_manager == 1135 ? 'Vinod' : ($sales_manager == 1324 ? 'Jeff' : ($sales_manager == 1 ? 'House Account' : 'Unknown'))) : 'Unknown'; ?>
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
                        <div class="avatar-lg rounded bg-soft-primary">
                            <i class="mdi mdi-handshake-outline font-24 avatar-title text-primary"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-end">
                            <h3 class="text-dark mt-1">$
                                <?php
                                $closed_sales_value = isset($closed_sales_value) ? $closed_sales_value : 0;
                                echo number_format($closed_sales_value);
                                ?>
                            </h3>
                            <p class="text-muted mb-1 text-truncate">Closed Deals</p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->
    <?php if (isset($status) && $status == 1) { ?>
        <div class="col-md-6 col-xl-3">
            <div class="widget-rounded-circle card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg rounded bg-soft-success">
                                <i class="mdi mdi-target-account font-24 avatar-title text-success"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1">$
                                    <?php
                                    $sales_value = (isset($all_sales) ? array_sum(array_column($all_sales, 'value')) : 0);
                                    echo number_format($sales_value);
                                    ?>

                                </h3>
                                <p class="text-muted mb-1 text-truncate">Opportunities</p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div>
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->
    <?php } else {
        echo '<div class="col-md-6 col-xl-3">&nbsp;</div>';
    } ?>
    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card border-success border mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form method="post" action="<?php echo base_url('etrack/sales_admin'); ?>">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="field-2" class="form-label">Sales Manager</label>
                                    <select name="sales_manager" class="form-select" id="field-2" onchange="this.form.submit()">
                                        <option value="1135" <?php echo isset($sales_manager) && $sales_manager == 1135 ? 'selected' : ''; ?>>Vinod</option>
                                        <option value="1324" <?php echo isset($sales_manager) && $sales_manager == 1324 ? 'selected' : ''; ?>>Jeff</option>
                                        <option value="1" <?php echo isset($sales_manager) && $sales_manager == 1 ? 'selected' : ''; ?>>House Account</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="widget-rounded-circle card border-danger border mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form method="post" action="<?php echo base_url('etrack/sales_admin'); ?>">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="field-2" class="form-label">Status</label>
                                    <select name="status" class="form-select" id="field-2" onchange="this.form.submit()">
                                        <option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : ''; ?>>Active</option>
                                        <option value="11" <?php echo isset($status) && $status == 11 ? 'selected' : ''; ?>>Won</option>
                                        <option value="15" <?php echo isset($status) && $status == 15 ? 'selected' : ''; ?>>Lost</option>
                                        <option value="20" <?php echo isset($status) && $status == 20 ? 'selected' : ''; ?>>Rejected</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div> <!-- end row-->
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->
</div>
<div class="row">
    <div id="con-close-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create New Sales Opportunity</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="<?php echo base_url('etrack/sales_admin/create_new_sales'); ?>">
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="field-1" class="form-label">Client Name</label>
                                    <input type="text" name="client_name" class="form-control" id="field-1" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sales_mng" class="form-label">Sales Manager</label>
                                    <select name="sales_manager" class="form-select" id="sales_mng">
                                        <option value="1135" <?php echo isset($sales_manager) && $sales_manager == 1135 ? 'selected' : ''; ?>>Vinod</option>
                                        <option value="1324" <?php echo isset($sales_manager) && $sales_manager == 1324 ? 'selected' : ''; ?>>Jeff</option>
                                        <option value="1" <?php echo isset($sales_manager) && $sales_manager == 1 ? 'selected' : ''; ?>>House Account</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sales_value" class="form-label">Amount ($)</label>
                                    <input type="number" name="sales_value" class="form-control" id="sales_value" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="expected_date" class="form-label">Expected Date</label>
                                    <input type="date" name="expected_date" class="form-control" id="expected_date" placeholder="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="details" class="form-label">Details</label>
                                    <textarea name="details" class="form-control" id="details" placeholder=""></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="remarks" class="form-label">Remarks</label>
                                    <textarea name="remarks" class="form-control" id="remarks" placeholder=""></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-success btn-xs waves-effect waves-light">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- /.modal -->
</div>
<!-- Start of table to show the sales opportunities -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped dt-responsive nowrap" id="sales_data_table" style="width:100%">
                        <thead>
                            <tr style="background-color: #0011ff39; color: #000;">
                                <th style="width: 2%;">#</th>
                                <th>Client</th>
                                <th style="min-width: 180px;">Details</th>
                                <th style="min-width: 180px;">Remarks</th>
                                <th style="width: 200px;">Amount ($)</th>
                                <th style="width: 140px;">Expected Date</th>
                                <th style="width: 200px;">Status</th>
                                <th style="width: 60px;">Updated</th>
                                <th style="width: 50px;">Details</th>
                                <th style="width: 50px;">Save</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total_sales = 0;
                            $client_total = 0;
                            if (!empty($all_sales)) :
                            ?>
                                <?php
                                $sno = 0;
                                $counter = 0;
                                foreach ($all_sales as $index => $sale) : $sno++;

                                ?>

                                    <tr data-sales-id="<?php echo $sale['sales_id']; ?>">
                                        <td><?php echo $sno; ?></td>
                                        <td><?php echo $sale['client']; ?></td>
                                        <td>
                                            <?php if ($sale['details'] != 0) { ?>
                                                <textarea class="form-control form-control-sm sales-details-input" rows="1"><?php echo esc($sale['details']); ?></textarea>
                                            <?php } else { ?>
                                                <?php echo $sale['details']; ?>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if ($sale['status'] != 0) { ?>
                                                <textarea class="form-control form-control-sm sales-remarks-input" rows="1"><?php echo esc($sale['remarks']); ?></textarea>
                                            <?php } else { ?>
                                                <?php echo $sale['remarks']; ?>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($sale['status'] != 0) {
                                                $total_sales += $sale['value']; ?>
                                                <input type="number" class="form-control form-control-sm sales-value-input" style="text-align: right;" value="<?php echo $sale['value']; ?>">
                                            <?php } else { ?>
                                                $ <?php echo number_format($sale['value']); ?>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if ($sale['status'] != 0) { ?>
                                                <input type="date" class="form-control form-control-sm sales-expected-date-input" value="<?php echo $sale['expected_date']; ?>">
                                            <?php } else { ?>
                                                <?php echo $sale['expected_date']; ?>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php $status = $sale['status'];
                                            if ($sale['status'] != 0) { ?>
                                                <select class="form-select form-select-sm sales-status-select">
                                                    <option value="1" <?php echo $status == 1 ? 'selected' : ''; ?>>Prospecting</option>
                                                    <option value="2" <?php echo $status == 2 ? 'selected' : ''; ?>>Demonstration</option>
                                                    <option value="3" <?php echo $status == 3 ? 'selected' : ''; ?>>Proposal Sent</option>
                                                    <option value="4" <?php echo $status == 4 ? 'selected' : ''; ?>>Negotiation</option>
                                                    <option value="11" <?php echo $status == 11 ? 'selected' : ''; ?>>Won</option>
                                                    <option value="15" <?php echo $status == 15 ? 'selected' : ''; ?>>Lost</option>
                                                    <option value="0" <?php echo $status == 0 ? 'selected' : ''; ?>>Deleted</option>
                                                </select>
                                            <?php } else {
                                                echo "Deleted";
                                            } ?>
                                        </td>
                                        <td class="sales-last-updated"><?php echo date('M-d', $sale['last_updated_on']); ?></td>
                                        <td>
                                            <form method="post" action="<?php echo base_url('etrack/sales_admin/view_sales_details'); ?>">
                                                <input type="hidden" name="sales_id" value="<?php echo $sale['sales_id']; ?>">
                                                <button type="submit" class="btn btn-outline-warning btn-xs waves-effect waves-light"><span class="mdi mdi-eye"></span></button>
                                            </form>
                                        </td>
                                        <td>
                                            <?php if ($sale['status'] != 0) { ?>
                                                <button type="button" class="btn btn-outline-success btn-xs waves-effect waves-light sales-save-btn"><span class="mdi mdi-content-save"></span></button>
                                            <?php } ?>
                                        </td>
                                    </tr>

                                   
                                   

                                <?php endforeach; ?>

                            <?php else : ?>

                            <?php endif; ?>
                        </tbody>
                    </table>
                </div> <!-- end table-responsive-->
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div>
</div>
<!-- End of table to show the sales opportunities -->
<script>
    var salesCsrfName = '<?= csrf_token() ?>';
    var salesCsrfHash = '<?= csrf_hash() ?>';

    $(document).on('click', '.sales-save-btn', function() {
        var btn = $(this);
        var row = btn.closest('tr');
        var salesId = row.data('sales-id');
        var status = row.find('.sales-status-select').val();
        var details = row.find('.sales-details-input').val();
        var remarks = row.find('.sales-remarks-input').val();
        var value = row.find('.sales-value-input').val();
        var expectedDate = row.find('.sales-expected-date-input').val();

        btn.prop('disabled', true);

        $.ajax({
            url: "<?= base_url('etrack/sales_admin/update_sales_row') ?>",
            type: 'POST',
            dataType: 'json',
            data: {
                sales_id: salesId,
                status: status,
                details: details,
                remarks: remarks,
                sales_value: value,
                expected_date: expectedDate,
                [salesCsrfName]: salesCsrfHash
            },
            success: function(response) {
                btn.prop('disabled', false);

                if (response.csrfHash) {
                    salesCsrfHash = response.csrfHash;
                }

                if (response.status === 'OK') {
                    row.find('.sales-last-updated').text(response.last_updated_on);
                    row.addClass('table-success');
                    setTimeout(function() {
                        row.removeClass('table-success');
                    }, 1500);
                } else {
                    alert(response.message || 'Something went wrong. Please contact Site Admin!');
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false);
                if (xhr.status === 403) {
                    alert('Your session security token has expired. The page will now reload.');
                    window.location.reload();
                    return;
                }
                alert('Request failed. Please try again.');
            }
        });
    });
</script>