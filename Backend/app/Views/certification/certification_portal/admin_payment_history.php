<?php $client = session()->get('client'); ?>
<style>
    /* Same rounded-corner + shadow + table look as SCORM/scorm_courses (courses_search_view.php). */
    .admin-payment-history-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .admin-payment-history-card table.dataTable thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .admin-payment-history-card table.dataTable thead th {
        border-bottom-color: #424e5a;
        color: #cedeef;
    }

    .admin-payment-history-card table.dataTable tbody td {
        vertical-align: middle;
    }

    .admin-payment-history-card .dataTables_length select {
        border-radius: .5rem;
        border: 1px solid #dee2e6;
        padding: .25rem 1.75rem .25rem .6rem;
    }

    .admin-payment-history-card .dataTables_filter input {
        border-radius: 2rem;
        border: 1px solid #dee2e6;
        padding: .4rem .75rem;
        min-width: 260px;
    }

    [data-bs-theme="dark"] .admin-payment-history-card .dataTables_length select,
    [data-bs-theme="dark"] .admin-payment-history-card .dataTables_filter input {
        border-color: #424e5a;
    }

    .admin-payment-history-card .pagination .page-link {
        border: none;
        margin: 0 2px;
        border-radius: 0;
        color: #6658dd;
    }

    .admin-payment-history-card .pagination .page-item.active .page-link {
        background-color: #6658dd;
        color: #fff;
    }

    .admin-payment-history-card .pagination .page-item.disabled .page-link {
        color: #ced4da;
        background: transparent;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Certification/certification_dashboard'); ?>"><?= lang('UI_Text.Certifications_Admin') ?></a></li>
                </ol>
            </div>
            <h4 class="page-title"><?= lang('UI_Text.Admin_Payment_History') ?></h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card admin-payment-history-card">
            <div class="card-body">

                <!-- <form method="get" action="<?= base_url('Certification/Certification_Portal/adminPaymentHistory') ?>" class="row g-2 align-items-end mb-3">

                    <div class="col-md-2">
                        <label class="form-label">User Name</label>
                        <input type="text" name="user_name" class="form-control" value="<?= esc($filters['user_name'] ?? '') ?>" placeholder="User name">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Email</label>
                        <input type="text" name="email" class="form-control" value="<?= esc($filters['email'] ?? '') ?>" placeholder="Email">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Certificate</label>
                        <select name="certificate_id" class="form-select">
                            <option value="">All Certificates</option>
                            <?php foreach ($certificatesList as $cert) : ?>
                                <option value="<?= esc($cert['cert_id']) ?>" <?= (($filters['certificate_id'] ?? '') == $cert['cert_id']) ? 'selected' : '' ?>>
                                    <?= esc($cert['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Client</label>
                        <select name="client_id" class="form-select">
                            <option value="">All Clients</option>
                            <?php foreach ($clientsList as $clientRow) : ?>
                                <option value="<?= esc($clientRow['id_c']) ?>" <?= (($filters['client_id'] ?? '') == $clientRow['id_c']) ? 'selected' : '' ?>>
                                    <?= esc($clientRow['client_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="payment_status" class="form-select">
                            <option value="">All Statuses</option>
                            <?php foreach (['PENDING', 'PAID', 'FAILED', 'CANCELLED', 'REFUNDED'] as $status) : ?>
                                <option value="<?= $status ?>" <?= (($filters['payment_status'] ?? '') == $status) ? 'selected' : '' ?>>
                                    <?= ucfirst(strtolower($status)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Date From</label>
                        <input type="date" name="date_from" class="form-control" value="<?= esc($filters['date_from'] ?? '') ?>">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Date To</label>
                        <input type="date" name="date_to" class="form-control" value="<?= esc($filters['date_to'] ?? '') ?>">
                    </div>

                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-filter-outline"></i> Filter
                        </button>
                        <a href="<?= base_url('Certification/Certification_Portal/adminPaymentHistory') ?>" class="btn btn-light">
                            Reset
                        </a>
                        <button type="submit" formaction="<?= base_url('Certification/Certification_Portal/adminPaymentHistoryExport') ?>" class="btn btn-success ms-auto">
                            <i class="mdi mdi-file-excel-outline"></i> Export to Excel
                        </button>
                    </div>

                </form> -->

                <div class="table-responsive">
                    <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?= lang('UI_Text.Order_Id') ?></th>
                                <th><?= lang('UI_Text.Transaction_Id') ?></th>
                                <th><?= lang('UI_Text.User_Name') ?></th>
                                <th><?= lang('UI_Text.User_Email') ?></th>
                                <th><?= lang('UI_Text.Client_Name') ?></th>
                                <th><?= lang('UI_Text.Certification_Name') ?></th>
                                <th><?= lang('UI_Text.Price') ?></th>
                                <th><?= lang('UI_Text.Status') ?></th>
                                <th><?= lang('UI_Text.Transaction_Date') ?></th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                            foreach ($paymentHistory as $payment) : $j++ ?>
                                <tr>
                                    <td><?= $j ?></td>
                                    <td><?= esc($payment['razorpay_order_id']) ?></td>
                                    <td><?= esc($payment['razorpay_payment_id']) ?></td>
                                    <td><?= esc(trim(($payment['user_first_name'] ?? '') . ' ' . ($payment['user_last_name'] ?? ''))) ?></td>
                                    <td><?= esc($payment['user_email']) ?></td>
                                    <td><?= esc($payment['client_name']) ?></td>
                                    <td><?= esc($payment['cert_name']) ?></td>
                                    <td><?= '₹' . number_format($payment['final_amount'], 2) ?></td>
                                    <td>
                                        <?php
                                        $status = strtoupper($payment['payment_status']);

                                        switch ($status) {
                                            case 'PAID':
                                                $badgeClass = 'bg-success';
                                                break;

                                            case 'PENDING':
                                                $badgeClass = 'bg-warning text-dark';
                                                break;

                                            case 'FAILED':
                                                $badgeClass = 'bg-danger';
                                                break;

                                            case 'REFUNDED':
                                                $badgeClass = 'bg-info';
                                                break;

                                            case 'CANCELLED':
                                                $badgeClass = 'bg-secondary';
                                                break;

                                            default:
                                                $badgeClass = 'bg-dark';
                                                break;
                                        }
                                        ?>
                                        <span class="badge <?= $badgeClass ?>">
                                            <?= esc($payment['payment_status']) ?>
                                        </span>
                                    </td>
                                    <td><?= !empty($payment['createdon']) ? date('d M Y', $payment['createdon']) : '-' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>