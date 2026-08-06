<style>
    /* Same rounded-corner + shadow + table look as SCORM/scorm_courses (courses_search_view.php). */
    .leave-statement-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .leave-statement-card table thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .leave-statement-card table thead th {
        border-bottom-color: #36404a;
        color: #cedeef;
    }

    .leave-statement-card table tbody td {
        vertical-align: middle;
    }

    .leave-statement-card .dataTables_length select {
        border-radius: .5rem;
        border: 1px solid #dee2e6;
        padding: .25rem 1.75rem .25rem .6rem;
    }

    .leave-statement-card .dataTables_filter input {
        border-radius: 2rem;
        border: 1px solid #dee2e6;
        padding: .4rem .75rem;
        min-width: 260px;
    }

    [data-bs-theme="dark"] .leave-statement-card .dataTables_length select,
    [data-bs-theme="dark"] .leave-statement-card .dataTables_filter input {
        border-color: #424e5a;
    }

    .leave-statement-card .pagination .page-link {
        border-radius: 0;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/leaves'); ?>">
                            Leave Dashboard
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                Leave Statement
            </h4>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-4">
        <div class="card leave-statement-card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/leaves/statement'); ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-4">

                            <select class="form-select" name='year'>
                                <?php
                                $endyear = date('Y');
                                for ($i = $endyear; $i >= 2025; $i--) {
                                    echo "<option value='$i'>$i</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 mt-3 mt-md-0">
                            <button type="submit" class="btn btn-outline-info rounded-pill btn-xs waves-effect waves-light">
                                Change Year
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="card leave-statement-card">
            <div class="card-body">
                <table id="leave-statement-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr class="table-light">
                            <th class="center">#</th>
                            <th>Leave Type</th>
                            <th>Added</th>
                            <th>Applied</th>
                            <th>Date</th>
                            <!--  <th>Expiry</th> -->
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Updated By</th>
                            <th>Updated On</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        foreach ($leave_statement as $leave) {
                            $year = date('Y');        //last year
                            $last_month = date('m', strtotime("-1 month"));       //last month
                            $lastMonth25 = $year . '-' . $last_month . '-25';
                            $j++;
                        ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php $type = $leave['type'];
                                    switch ($type) {
                                        case 1:
                                            echo 'WFH';
                                            break;
                                        case 2:
                                            echo 'Earned';
                                            break;
                                        case 3:
                                            echo 'Casual';
                                            break;
                                        case 4:
                                            echo 'Restricted';
                                            break;
                                        case 5:
                                            echo 'Paternity';
                                            break;
                                        case 6:
                                            echo 'Compoff';
                                            break;
                                        case 7:
                                            echo 'Casual Leave +';
                                            break;
                                        case 8:
                                            echo 'LOP';
                                            break;
                                        case 9:
                                            echo 'OD';
                                            break;
                                    }
                                    ?></td>
                                <?php $leavenum = $leave['number_leave'];
                                if ($leavenum > 0) { ?>
                                    <td><?php echo $leavenum; ?></td>
                                    <td></td>
                                <?php } else { ?>
                                    <td></td>
                                    <td><?php echo abs($leavenum); ?></td>
                                <?php } ?>
                                <td><?php echo $leave['start_dt']; ?></td>
                                <!--  <td><?php echo $leave['expire_on']; ?></td> -->
                                <td><?php $status = $leave['status'];
                                    switch ($status) {
                                        case 1:
                                            echo '<span class="badge bg-soft-success text-success rounded-pill p-1 px-2">Active</span>';
                                            break;
                                        case 0:
                                            echo '<span class="badge bg-soft-secondary text-secondary rounded-pill p-1 px-2">Deleted</span>';
                                            break;
                                        case 3:
                                            echo '<span class="badge bg-soft-warning text-warning rounded-pill p-1 px-2">Waiting for Approval</span>';
                                            break;
                                        case 4:
                                            echo '<span class="badge bg-soft-danger text-danger rounded-pill p-1 px-2">Rejected</span>';
                                            break;
                                    }
                                    ?></td>
                                <td><?php echo $leave['remarks']; ?></td>
                                <td><?php echo $leave['updatedby']; ?></td>
                                <td><?php echo date('Y-m-d', $leave['last_updated_on']); ?></td>
                                <td>
                                    <?php if ($leave['number_leave'] < 0) { ?>
                                        <?php if ($lastMonth25 < $leave['start_dt']) { ?>
                                            <?php if ($status != 0) { ?>
                                                <form class="form-horizontal" action="<?php echo base_url('etrack/leaves/delete_leaves'); ?>" method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="leaveid" value="<?php echo $leave['et_le_id']; ?>">
                                                    <button class="btn btn-outline-primary rounded-pill waves-effect btn-xs waves-light"><span class="mdi mdi-trash-can-outline"></span></button>
                                                </form>
                                            <?php } ?>
                                    <?php }
                                    } ?>
                                    <?php if ($leave['type'] == 6) { ?>

                                        <?php if ($status != 0) { ?>
                                            <form class="form-horizontal" action="<?php echo base_url('etrack/leaves/delete_leaves'); ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="leaveid" value="<?php echo $leave['et_le_id']; ?>">
                                                <button class="btn btn-outline-primary rounded-pill waves-effect btn-xs waves-light"><span class="mdi mdi-trash-can-outline"></span></button>
                                            </form>

                                    <?php }
                                    } ?>
                                </td>

                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#leave-statement-datatable').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
            columnDefs: [{
                orderable: false,
                targets: [0, 9]
            }],
            language: {
                search: '_INPUT_',
                searchPlaceholder: '<?= esc(lang('UI_Text.Search_Leaves'), 'js') ?>',
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
