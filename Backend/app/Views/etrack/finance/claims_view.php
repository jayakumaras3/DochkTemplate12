<?php
$userlevel = session('userlevel');
$id_user = session('id_user');
$arrayuserlevel  = array_map('intval', explode(',', $userlevel) ?? '');
$month = isset($month) ? $month : date('m');
$year = isset($year) ? $year : date('Y');
?>
<style>
    /* Same rounded-corner + shadow + table look as SCORM/scorm_courses (courses_search_view.php)
       and etrack/leaves/statement (leave_statement_view.php). */
    .claims-table-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .claims-table-card table thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .claims-table-card table thead th {
        border-bottom-color: #36404a;
        color: #cedeef;
    }

    .claims-table-card table tbody td {
        vertical-align: middle;
    }

    .claims-table-card .dataTables_length select {
        border-radius: .5rem;
        border: 1px solid #dee2e6;
        padding: .25rem 1.75rem .25rem .6rem;
    }

    .claims-table-card .dataTables_filter input {
        border-radius: 2rem;
        border: 1px solid #dee2e6;
        padding: .4rem .75rem;
        min-width: 260px;
    }

    [data-bs-theme="dark"] .claims-table-card .dataTables_length select,
    [data-bs-theme="dark"] .claims-table-card .dataTables_filter input {
        border-color: #424e5a;
    }

    .claims-table-card .pagination .page-link {
        border: none;
        margin: 0 2px;
        border-radius: 0;
        color: #6658dd;
    }

    .claims-table-card .pagination .page-item.active .page-link {
        background-color: #6658dd;
        color: #fff;
    }

    .claims-table-card .pagination .page-item.disabled .page-link {
        color: #ced4da;
        background: transparent;
    }

    .add-claim-modal {
        border-radius: 18px;
        border: none;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.2);
    }

    .add-claim-modal .form-control,
    .add-claim-modal .form-select {
        border-radius: 10px;
        padding: 0.55rem 0.9rem;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <button type="button" class="btn btn-outline-primary btn-xs rounded-pill waves-effect waves-light float-end" data-bs-toggle="modal" data-bs-target="#bs-example-modal-lg"><i class="mdi mdi-plus-circle"></i> Add New Claim </button>
            </div>
            <h4 class="page-title">Claims (<?php echo $month . '/' . $year; ?>)</h4>
        </div>
    </div>
</div>
<div class="modal fade" id="bs-example-modal-lg" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content add-claim-modal">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="myLargeModalLabel">Create New Expense</h5>
                    <p class="text-muted font-13 mb-0">Fill in the details below to submit a new claim.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-2">
                <form class="form-horizontal" action="<?php echo base_url('etrack/claims/add_new_claim'); ?>" method="POST" id="submitForm"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-12 col-xl-12  col-form-label">Vendor Name</label>
                            <div class="col-12 col-xl-12">
                                <input type="text" name="vendor_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-12 col-xl-12  col-form-label">Currency</label>
                            <div class="col-12 col-xl-12">
                                <select name="currency" class="form-select" required>
                                    <option value="1" SELECTED>USD</option>
                                    <option value="2">INR</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-12 col-xl-12  col-form-label">Amount</label>
                            <div class="col-12 col-xl-12">
                                <input type="number" name="amount" class="form-control" value="" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-12 col-xl-12  col-form-label">Payment Mode</label>
                            <div class="col-12 col-xl-12">
                                <select name="payment_mode" class="form-select" required>
                                    <option value="1">Personal CC</option>
                                    <option value="2">Shrikant CC</option>
                                    <option value="3">Frank CC</option>
                                    <option value="4">Pramod CC</option>
                                    <option value="5">Office</option>
                                    <option value="6">Cash</option>
                                    <option value="7">Online</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-12 col-xl-12  col-form-label">Claim Date</label>
                            <div class="col-12 col-xl-12">
                                <input id="start_date" name="claim_date" class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="" required>
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
                            <label for="inputEmail3" class="col-12 col-xl-12  col-form-label">Expense Head <span style="font-size: 10px; color: #f54f60;">If in doubt contact Finance.</span></label>
                            <div class="col-12 col-xl-12">
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
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="inputEmail3" class="col-12 col-xl-12  col-form-label">Description</label>
                            <div class="col-12 col-xl-12">
                                <textarea name="description" class="form-control" required></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button type="submit" form="submitForm" class="btn btn-primary rounded-pill px-4" id="submitButton">
                    Add New Expense
                </button>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card claims-table-card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Etrack/claims'); ?>" method="POST" id="exportForm"><?= csrf_field() ?>
                    <div class="mb-2 row">
                        <label for="inputEmail3" class="col-12 col-xl-1  col-form-label">Month</label>
                        <div class="col-12 col-xl-2">
                            <select name="month" class="form-select" required>
                                <option value="">Select Month</option>
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                        <label for="inputEmail3" class="col-12 col-xl-1  col-form-label">Year</label>
                        <div class="col-12 col-xl-2">
                            <select name="year" class="form-select" required>
                                <option value="">Select Year</option>
                                <?php
                                $current_year = date('Y');
                                for ($i = $current_year; $i >= 2026; $i--) {
                                    echo '<option value="' . $i . '">' . $i . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-12 col-xl-2">
                            <button type="submit" class="btn btn-outline-success btn-xs rounded-pill waves-effect waves-light" id="exportButton">
                                View Claims
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card claims-table-card">
            <div class="card-body">
                <table id="claims-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr class="table-light">
                            <th class="center">#</th>
                            <th>Vendor</th>
                            <th>Claim By</th>
                            <th>Claim Date</th>
                            <th>Mode</th>
                            <th>USD</th>
                            <th>Expense Head</th>
                            <th>Status</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        $my_claims = $my_claims ?? [];
                        foreach ($my_claims as $claimx) {
                            $j++;
                            echo '<tr><td>';
                            echo $j;
                            echo '</td><td>';
                            echo $claimx['vendor_name'];
                            echo '</td><td>';
                            echo $claimx['requested_by_name'] . ' ' . $claimx['requested_by_last_name'];
                            echo '</td><td>';
                            echo $claimx['requested_on'];
                            echo '</td><td>';
                            switch ($claimx['mode']) {
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
                            echo '</td><td style="text-align:right">$ ';
                            echo number_format($claimx['claim_amount_usd']);
                            echo '</td><td>';
                            if ($claimx['expense_head'] > 9 && $claimx['expense_head'] < 50) {
                                echo '** ' . $expense_list[$claimx['expense_head']] ?? 'N/A';
                            } elseif ($claimx['expense_head'] > 50) {
                                echo $claimx['expense_head'] . ' - ' . $claimx['expense_head_name'];
                            } else {
                                echo 'N/A';
                            }

                            echo '</td><td>';
                            switch ($claimx['status']) {
                                case 1:
                                    echo '<span class="badge bg-soft-secondary text-secondary rounded-pill p-1 px-2">New</span>';
                                    break;
                                case 2:
                                    echo '<span class="badge bg-soft-warning text-warning rounded-pill p-1 px-2">Submitted to PM</span>';
                                    break;
                                case 3:
                                    echo '<span class="badge bg-soft-info text-info rounded-pill p-1 px-2">PM Approved</span>';
                                    break;
                                case 4:
                                    echo '<span class="badge bg-soft-warning text-warning rounded-pill p-1 px-2">Submitted to PC</span>';
                                    break;
                                case 5:
                                    echo '<span class="badge bg-soft-info text-info rounded-pill p-1 px-2">Pramod Approved</span>';
                                    break;
                                case 6:
                                    echo '<span class="badge bg-soft-warning text-warning rounded-pill p-1 px-2">Submitted to Shrikant</span>';
                                    break;
                                case 7:
                                    echo '<span class="badge bg-soft-info text-info rounded-pill p-1 px-2">Shrikant Approved</span>';
                                    break;
                                case 8:
                                    echo '<span class="badge bg-soft-warning text-warning rounded-pill p-1 px-2">Submitted to Finance</span>';
                                    break;
                                case 9:
                                    echo '<span class="badge bg-soft-success text-success rounded-pill p-1 px-2">Finance Approved</span>';
                                    break;
                                case 10:
                                    echo '<span class="badge bg-soft-danger text-danger rounded-pill p-1 px-2">Rejected</span>';
                                    break;
                                case 11:
                                    echo '<span class="badge bg-soft-success text-success rounded-pill p-1 px-2">Paid</span>';
                                    break;
                            }
                            echo '</td>';

                        ?>
                            <form class="form-horizontal" action="<?php echo base_url('etrack/claims/edit_claim'); ?>" method="POST"><?= csrf_field() ?>
                                <td>
                                    <input type="hidden" name="vd_id" value="<?php echo $claimx['vd_id']; ?>">
                                    <button type="submit" class="btn btn-outline-warning waves-effect btn-xs rounded-pill waves-light"><span class="mdi mdi-pencil-outline"></span></button>
                                </td>
                            </form>

                        <?php
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> <!-- end col-->
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#claims-datatable').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
            columnDefs: [{
                orderable: false,
                targets: [8]
            }],
            language: {
                search: '_INPUT_',
                searchPlaceholder: 'Search Claims',
                lengthMenu: '_MENU_',
                info: '<?= esc(lang('UI_Text.Datatable_Info'), 'js') ?>',
                infoEmpty: '<?= esc(lang('UI_Text.Datatable_Info_Empty'), 'js') ?>',
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>",
                    next: "<i class='mdi mdi-chevron-right'>"
                }
            }
        });
    });
</script>
