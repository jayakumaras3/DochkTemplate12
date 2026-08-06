<?php helper('localization'); ?>
<style>
    .access-card-table-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .access-card-table-card table thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .access-card-table-card table thead th {
        border-bottom-color: #36404a;
        color: #cedeef;
    }

    .access-card-table-card table tbody td {
        vertical-align: middle;
    }

    .access-card-table-card .dataTables_length select {
        border-radius: .5rem;
        border: 1px solid #dee2e6;
        padding: .25rem 1.75rem .25rem .6rem;
    }

    .access-card-table-card .dataTables_filter input {
        border-radius: 2rem;
        border: 1px solid #dee2e6;
        padding: .4rem .75rem;
        min-width: 260px;
    }

    [data-bs-theme="dark"] .access-card-table-card .dataTables_length select,
    [data-bs-theme="dark"] .access-card-table-card .dataTables_filter input {
        border-color: #424e5a;
    }

    .access-card-table-card .pagination .page-link {
        border-radius: 0;
    }

    .in-office-tile {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
        background: linear-gradient(135deg, #7d76e8 0%, #6658dd 100%);
        color: #fff;
        position: relative;
    }

    .in-office-tile .card-body {
        position: relative;
        z-index: 1;
    }

    .in-office-tile .in-office-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        background: rgba(255, 255, 255, .2);
    }

    .in-office-tile h2 {
        font-weight: 800;
        margin-bottom: 0;
    }

    .in-office-tile .in-office-illustration {
        position: absolute;
        right: .5rem;
        bottom: 0;
        font-size: 4.5rem;
        opacity: .18;
    }

    .filters-card {
        border: none;
        border-radius: 18px;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .filters-card .form-select {
        border-radius: 10px;
        padding: .55rem .9rem;
    }

    .filters-card label {
        font-weight: 600;
        font-size: .875rem;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/attendance'); ?>">
                            Attendance Dashboard
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                Access Card Data <?php echo $start_date . ' - ' . $end_date; ?>
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="card access-card-table-card">
            <div class="card-body">
                <table id="access-card-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr class="table-light">
                            <th><i class="mdi mdi-calendar-blank-outline me-1"></i>Date</th>
                            <th><i class="mdi mdi-login text-success me-1"></i>In</th>
                            <th><i class="mdi mdi-logout text-danger me-1"></i>Out</th>
                            <th><i class="mdi mdi-clock-outline me-1"></i>Total</th>
                            <th><i class="mdi mdi-coffee-outline me-1"></i>Break</th>
                            <th><i class="mdi mdi-clock-check-outline me-1"></i>Actual</th>
                            <th><i class="mdi mdi-comment-outline me-1"></i>Comment</th>
                            <th><i class="mdi mdi-cog-outline me-1"></i>Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        $total_office = 0;
                        $total_half = 0;
                        $total_full = 0;
                        $total_calculated = 0;
                        $totalmin = 0;
                        foreach ($access_card as $data) {
                            $total_office++;
                            $total_calculated =  $data['attendance_type'] + $total_calculated;
                            $totalmin =  $data['actual_time_in_min'] + $totalmin;
                            if ($data['attendance_type'] == 1) {
                                $total_full++;
                            } else {
                                $total_half++;
                            }
                        ?>
                            <tr>
                                <td>
                                    <?php
                                    $newdate = date("m-d", strtotime($data['start_date']));
                                    echo $newdate; ?>
                                </td>
                                <td class="text-success fw-semibold"><?php echo $data['timein']; ?></td>
                                <td class="text-danger fw-semibold"><?php echo $data['timeout']; ?></td>
                                <td class="text-primary fw-semibold"><?php echo $data['totalhrs']; ?></td>
                                <td class="text-warning fw-semibold"><?php echo $data['breakhr']; ?></td>
                                <td class="text-info fw-semibold"><?php echo $data['actualhr']; ?></td>

                                <?php $remarks = $data['remarks'];
                                if (strlen($remarks) > 0) {
                                ?>
                                    <td><?php echo $remarks; ?></td>
                                    <td>
                                        <form class="form-horizontal mb-0" action="<?php echo base_url('etrack/attendance/delete_remarks'); ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="access_id" value="<?php echo $data['access_id']; ?>">
                                            <button class="btn btn-outline-danger rounded-pill waves-effect btn-xs waves-light"><span class="mdi mdi-trash-can-outline"></span></button>
                                        </form>
                                    </td>
                                <?php
                                } else {
                                ?>
                                    <td style="width: 240px;">
                                        <input type="text" required name="remarks" value="" class="form-control" form="addRemarksForm-<?php echo $data['access_id']; ?>">
                                    </td>
                                    <td>
                                        <form id="addRemarksForm-<?php echo $data['access_id']; ?>" class="form-horizontal mb-0" action="<?php echo base_url('etrack/attendance/add_remarks'); ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="access_id" value="<?php echo $data['access_id']; ?>">
                                            <button type="submit" class="btn btn-outline-primary rounded-pill waves-effect btn-xs waves-light"><span class="mdi mdi-pencil-outline"></span></button>
                                        </form>
                                    </td>
                                <?php
                                } ?>
                            </tr>
                        <?php
                        }
                        // In-office days for the selected range: derived from total actual minutes worked, in ~8hr day
                        // increments rounded to the nearest half day.
                        $hrs = floor($totalmin / 60);
                        $calculatedio = round(round(($hrs / 8), 1) * 2) / 2;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card in-office-tile mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="in-office-icon"><i class="mdi mdi-office-building-outline"></i></div>
                    <div>In Office</div>
                </div>
                <h2><?php echo $calculatedio; ?></h2>
                <p class="mb-0 font-13" style="opacity:.85;">Total days in selected range</p>
                <div class="in-office-illustration"><i class="mdi mdi-office-building"></i></div>
            </div>
        </div>
        <div class="card filters-card">
            <div class="card-body">
                <h5 class="fw-bold mb-3"><i class="mdi mdi-filter-variant me-1"></i>Filters</h5>
                <form class="form-horizontal" action="<?php echo base_url('etrack/attendance/access_card_data'); ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="month">Month
                                <?php $thismonth = date('n');
                                ?></label>
                            <select class="form-select" name="month" id="month">
                                <?php

                                for ($i = 1; $i < 13; $i++) {
                                    $monthName = translated_month_name($i);

                                    echo '<option value="' . $i . '"';
                                    if ($i == $thismonth) {
                                        echo 'selected';
                                    }
                                    echo '>' . $monthName . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="year">Year</label>
                            <select class="form-select" name='year' id="year">
                                <?php
                                $endyear = date('Y');
                                for ($i = $endyear; $i >= 2025; $i--) {
                                    echo "<option value='$i'>$i</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" onclick="this.disabled=true; this.form.submit();" class="btn btn-primary rounded-pill w-100">
                                <i class="mdi mdi-magnify me-1"></i>Show Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#access-card-datatable').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
            columnDefs: [{
                orderable: false,
                targets: [6, 7]
            }],
            language: {
                search: '_INPUT_',
                searchPlaceholder: '<?= esc(lang('Buttons.Search'), 'js') ?>',
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
