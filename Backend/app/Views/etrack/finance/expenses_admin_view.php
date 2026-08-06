<?php helper('localization'); ?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">
                Expense Management <?= isset($selected_year) ? $selected_year : date('Y') ?> - <?= isset($selected_month) ? translated_month_name(intval($selected_month)) : translated_month_name((int) date('n')) ?>
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?= base_url('etrack/Fin_admin/expenses') ?>">
                    <div class="row mb-1">
                        <div class="col-md-5">
                            <label for="year" class="form-label">Year</label>
                            <select name="selected_year" id="year" class="form-select">
                                <?php for ($y = date('Y') - 5; $y <= date('Y') + 1; $y++) : ?>
                                    <option value="<?= $y ?>" <?= (isset($selected_year) && $selected_year == $y) ? 'selected' : '' ?>><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label for="month" class="form-label">Month</label>
                            <select name="selected_month" id="month" class="form-select">
                                <?php for ($m = 1; $m <= 12; $m++) : ?>
                                    <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>" <?= (isset($selected_month) && $selected_month == str_pad($m, 2, '0', STR_PAD_LEFT)) ? 'selected' : '' ?>><?= translated_month_name($m) ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-2 align-self-end">
                            <button type="submit" class="btn btn-outline-warning square-pill waves-effect waves-light">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#bs-example-modal-lg">Create New Expense</button>

                <div class="modal fade" id="bs-example-modal-lg" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">Create New Expense</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="<?php echo base_url('etrack/Fin_admin/add_new_expense') ?>" method="POST"><?= csrf_field() ?>
                                    <div class="row mb-1">
                                        <div class="col-md-6">
                                            <label for="expense_head" class="form-label">Expense Head</label>
                                            <select name="expense_head" id="expense_head" class="form-select" required>
                                                <option value="">Select Expense Head</option>
                                                <?php $expense_list = $expense_list ?? [];
                                                foreach ($expense_list as $key => $value) { ?>
                                                    <option value="<?php echo $key ?>">** <?php echo $value ?></option>
                                                <?php } ?>
                                                <?php if (!empty($active_ucn)) {
                                                    foreach ($active_ucn as $ucn) { ?>
                                                        <option value="<?php echo $ucn['ucn_id'] ?>"><?php echo $ucn['name'] ?></option>
                                                <?php }
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="amount" class="form-label">Amount in USD ($)</label>
                                            <input type="number" step="0.01" class="form-control" id="value" name="value" required>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-md-6">
                                            <label for="expense_date" class="form-label">Expense Date</label>
                                            <input type="date" class="form-control" id="expense_date" name="expense_date" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="paid_by" class="form-label">Paid By</label>
                                            <select name="paid_by" id="paid_by" class="form-select" required>
                                                <option value="US Credit Card">US Credit Card</option>
                                                <option value="India Credit Card">India Credit Card</option>
                                                <option value="PC">PC</option>
                                                <option value="Arun">Arun</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-md-12">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mt-2">
                                            <button type="submit" class="btn btn-outline-primary waves-effect square-pill waves-light">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>ID</th>
                            <th>Expense Head</th>
                            <th>Paid By</th>
                            <th>Description</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        if (!empty($active_expenses)) {
                            foreach ($active_expenses as $data) {
                                $j = $j + 1 ?>
                                <tr>
                                    <td><?php echo  $j ?></td>
                                    <td><?php echo $data['expense_id'] ?></td>
                                    <td><?php $expense_head = $data['expense_head'];
                                        if ($expense_head > 800) {
                                            echo $data['expense_head_ucn'];
                                        } else {
                                            echo isset($expense_list[$expense_head]) ? $expense_list[$expense_head] : 'Unknown';
                                        } ?></td>
                                    <td><?php echo $data['paid_by'] ?></td>
                                    <td><?php echo $data['description'] ?></td>
                                    <td><?php echo date('d M Y', strtotime($data['entry_date'])) ?></td>
                                    <td align="right"><?php if ($data['value'] > 0) {
                                                            echo '$ ';
                                                            echo number_format($data['value']);
                                                        } ?></td>
                                    <td><?php if ($data['status'] == 1) {
                                            echo '<span class="badge bg-success">Submitted</span>';
                                        } elseif ($data['status'] == 2) {
                                            echo '<span class="badge bg-success">Approved</span>';
                                        } elseif ($data['status'] == 3) {
                                            echo '<span class="badge bg-danger">Rejected</span>';
                                        } elseif ($data['status'] == 4) {
                                            echo '<span class="badge bg-success">Processed</span>';
                                        } elseif ($data['status'] == 5) {
                                            echo '<span class="badge bg-success">BC Done</span>';
                                        }else {
                                            echo '<span class="badge bg-danger"> - </span>';
                                        } ?></td>
                                    <td>
                                        <form action="<?php echo base_url('etrack/Fin_admin/edit_expenses') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="expense_id" value="<?php echo $data['expense_id'] ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                <span class="mdi mdi-square-edit-outline"></span></button>
                                        </form>
                                    </td>

                                </tr>

                        <?php }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> <!-- end col-->
</div>