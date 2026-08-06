<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/sales_admin'); ?>">
                            Sales Dashboard
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                Sales Details
            </h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
               
                <form method="post" action="<?php echo base_url('etrack/sales_admin/update_sales_details'); ?>">
                    <input type="hidden" name="sales_id" value="<?php echo $sales_id; ?>">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>Client</th>
                            <td><input type="text" class="form-control" name="client" value="<?php echo $sales_details['client'] ?? ''; ?>"></td>
                        </tr>
                        <tr>
                            <th>Sales Manager</th>
                            <td><select name="sales_manager" class="form-select">
                                    <option value="1135" <?php echo ($sales_details['sales_manager'] ?? null) == 1135 ? 'selected' : ''; ?>>Vinod</option>
                                    <option value="1324" <?php echo ($sales_details['sales_manager'] ?? null) == 1324 ? 'selected' : ''; ?>>Jeff</option>
                                    <option value="1" <?php echo ($sales_details['sales_manager'] ?? null) == 1 ? 'selected' : ''; ?>>House Account</option>
                                </select></td>
                        </tr>
                        <tr>
                            <th>Expected Date</th>
                            <td><input type="date" class="form-control" name="expected_date" value="<?php echo $sales_details['expected_date'] ?? ''; ?>"></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td><select name="status" class="form-select">
                                    <option value="1" <?php echo ($sales_details['status'] ?? null) == 1 ? 'selected' : ''; ?>>Prospecting</option>
                                    <option value="2" <?php echo ($sales_details['status'] ?? null) == 2 ? 'selected' : ''; ?>>Demonstration</option>
                                    <option value="3" <?php echo ($sales_details['status'] ?? null) == 3 ? 'selected' : ''; ?>>Proposal Sent</option>
                                    <option value="4" <?php echo ($sales_details['status'] ?? null) == 4 ? 'selected' : ''; ?>>Negotiation</option>
                                    <option value="11" <?php echo ($sales_details['status'] ?? null) == 11 ? 'selected' : ''; ?>>Won</option>
                                    <option value="15" <?php echo ($sales_details['status'] ?? null) == 15 ? 'selected' : ''; ?>>Lost</option>
                                    <option value="0" <?php echo ($sales_details['status'] ?? null) == 0 ? 'selected' : ''; ?>>Inactive</option>
                                </select></td>
                        </tr>

                        <tr>
                            <th>Value ($)</th>
                            <td><input type="text" class="form-control" name="sales_value" value="<?php echo isset($sales_details['value']) ? $sales_details['value'] : ''; ?>"></td>
                        </tr>
                        <tr>
                            <th>Details</th>
                            <td><textarea name="details" class="form-control" id="field-7" placeholder=""><?php echo $sales_details['details'] ?? ''; ?></textarea></td>

                        </tr>
                        <tr>
                            <th>Remarks</th>
                            <td><textarea name="remarks" class="form-control" id="field-7" placeholder=""><?php echo $sales_details['remarks'] ?? ''; ?></textarea></td>

                        </tr>
                        <tr>
                            <td colspan="2"><button type="submit" class="btn btn-outline-success btn-xs waves-effect waves-light">Update Sales Details</button></td>
                        </tr>
                    </table>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Sales History</h4>
                <?php if (!empty($sales_history)) : ?>
                    <table class="table table-bordered table-striped mt-2">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>His ID</th>
                                <th>Expected Date</th>
                                <th>Value ($)</th>
                                <th>Status</th>
                                <th>Details</th>
                                <th>Remarks</th>
                                <th>Updated On</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $sno = 0;
                            foreach ($sales_history as $history) : $sno++; ?>
                                <tr>
                                    <td><?php echo $sno; ?></td>
                                    <td><?php echo $history['sales_history_id']; ?></td>
                                    <td><?php echo $history['expected_date']; ?></td>
                                    <td style="text-align: right;">$ <?php echo number_format($history['value']); ?></td>
                                    <td><?php
                                        $status = $history['sales_status'];
                                        if ($status == 1) {
                                            echo "Prospecting";
                                        } elseif ($status == 2) {
                                            echo "Demonstration";
                                        } elseif ($status == 3) {
                                            echo "Proposal Sent";
                                        } elseif ($status == 4) {
                                            echo "Negotiation";
                                        } elseif ($status == 11) {
                                            echo "Won";
                                        } elseif ($status == 15) {
                                            echo "Lost";
                                        }
                                        ?></td>
                                    <td><?php echo $history['details']; ?></td>
                                    <td><?php echo $history['remarks']; ?></td>
                                    <td><?php echo date('Y-m-d', $history['last_updated_on']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <p class="text-muted
    font-14 mb-3 mt-2">No sales history available for this opportunity.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>