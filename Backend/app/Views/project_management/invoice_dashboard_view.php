<?php helper('localization'); ?>
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
                    </ol>
                </div>
            <?php } ?>
            <h4 class="page-title">Invoices for <?= isset($selected_year) ? $selected_year : date('Y') ?> - <?= isset($selected_month) ? translated_month_name(intval($selected_month)) : translated_month_name((int) date('n')) ?></h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?= base_url('Project_Manage/Invoices') ?>">
                    <div class="row mb-1">
                        <div class="col-md-5">
                            <label for="year" class="form-label">Year</label>
                            <select name="year" id="year" class="form-select">
                                <?php for ($y = date('Y') - 5; $y <= date('Y') + 1; $y++) : ?>
                                    <option value="<?= $y ?>" <?= (isset($selected_year) && $selected_year == $y) ? 'selected' : '' ?>><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label for="month" class="form-label">Month</label>
                            <select name="month" id="month" class="form-select">
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
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>UCN</th>
                                <th>UCN Name</th>
                                <th>Client</th>
                                <th>Description</th>
                                <th>Value</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Project Manager</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($invoices)) : ?>
                                <?php $total_invoice = 0;
                                foreach ($invoices as $index => $invoice) : ?>
                                    <?php

                                    if (!in_array('2010', $arrayuserlevel) && !in_array('3048', $arrayuserlevel) && !in_array('3014', $arrayuserlevel) && !in_array('69', $arrayuserlevel)) { ?>

                                    <?php if ($invoice['user_id'] != session()->get('id_user')) {
                                            continue;
                                        }
                                    } ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <?= $invoice['ucn_id'] ?>
                                        </td>
                                        <td><?= esc($invoice['ucn_name']) ?></td>
                                        <td><?= esc($invoice['client_name']) ?></td>
                                        <td><?= esc($invoice['description']) ?></td>
                                        <td style="text-align: right;"><?php
                                                                        echo '$ ';
                                                                        echo esc(number_format($invoice['value']));
                                                                        $total_invoice += $invoice['value']; ?></td>
                                        <td><?= esc(date('Y-m-d', strtotime($invoice['invoicing_dt']))) ?></td>
                                        <td><?php
                                            switch ($invoice['status']) {
                                                case 1:
                                                    echo 'Active';
                                                    break;
                                                case 2:
                                                    echo 'Ready';
                                                    break;
                                                case 3:
                                                    echo 'Invoiced';
                                                    break;
                                                case 4:
                                                    echo 'Paid';
                                                    break;
                                                case 5:
                                                    echo 'Cancelled';
                                                    break;
                                                default:
                                                    echo 'Unknown';
                                                    break;
                                            }
                                            ?></td>
                                        <td><?= esc($invoice['project_manager']) ?></td>
                                        <td>

                                            <?= form_open('Project_Manage/PM_purchase_order/edit_purchase_order') ?>
                                            <input type="hidden" name="po_id" value="<?= $invoice['po_id'] ?>">
                                            <input type="hidden" name="return_url" value="3">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">Edit</button>
                                            <?= form_close() ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="5"><strong></strong></td>
                                    <td style="text-align: right;"><strong>$ <?= esc(number_format($total_invoice)) ?></strong></td>
                                    <td colspan="4"></td>
                                </tr>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center">No invoices found for the selected month and year.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>