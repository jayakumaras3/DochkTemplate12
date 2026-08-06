<style>
    /* Same rounded-corner + shadow + table look as SCORM/scorm_courses (courses_search_view.php). */
    .report-table-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .report-table-card table thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .report-table-card table thead th {
        border-bottom-color: #36404a;
        color: #cedeef;
    }

    .report-table-card table tbody td {
        vertical-align: middle;
    }

    .report-table-card .dataTables_length select {
        border-radius: .5rem;
        border: 1px solid #dee2e6;
        padding: .25rem 1.75rem .25rem .6rem;
    }

    .report-table-card .dataTables_filter input {
        border-radius: 2rem;
        border: 1px solid #dee2e6;
        padding: .4rem .75rem;
        min-width: 260px;
    }

    [data-bs-theme="dark"] .report-table-card .dataTables_length select,
    [data-bs-theme="dark"] .report-table-card .dataTables_filter input {
        border-color: #424e5a;
    }

    .report-table-card .pagination .page-link {
        border-radius: 0;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Reports/client_reports') ?>"><?php echo lang('Buttons.Report'); ?></a></li>
                </ol>
            </div>
            <?php if (!isset($type) || $type === null) { ?>
                <h4 class="page-title"><?php echo lang('UI_Text.Total_Assigned'); ?></h4>
            <?php } elseif ($type == 10) { ?>
                <h4 class="page-title"><?php echo lang('UI_Text.Courses_Assigned_Report'); ?></h4>
            <?php } elseif ($type == 2) { ?>
                <h4 class="page-title"><?php echo lang('UI_Text.Completed'); ?></h4>
            <?php } elseif ($type == 1) { ?>
                <h4 class="page-title"><?php echo lang('UI_Text.In Progress'); ?></h4>
            <?php } elseif ($type == 0) { ?>
                <h4 class="page-title"><?php echo lang('UI_Text.Not_started'); ?></h4>
            <?php } ?>

        </div>
    </div>
</div>
<?php if (!empty($data_values)) : ?>
    <div class="col-lg-12">
        <div class="card report-table-card">
            <div class="card-body">
                <table id="client-assigned-report-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr class="table-light">
                            <th class="center">#</th>
                            <th><?= lang('UI_Text.User_Name'); ?></th>
                            <th><?= lang('UI_Text.Course_Name'); ?></th>
                            <th><?= lang('UI_Text.Status'); ?></th>
                            <th><?= lang('UI_Text.Assigned_By'); ?></th>
                            <th><?= lang('UI_Text.Assigned_On'); ?></th>
                            <th><?= lang('UI_Text.Updated_On'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        foreach ($data_values as $report) {

                            $j = $j + 1; ?>
                            <tr>
                                <td class="center"><?php echo  $j ?></td>
                                <td><?php echo $report['name'] . ' ' . $report['last_name']; ?></td>
                                <td><?php echo $report['course_name']; ?></td>
                                <td><?php $status = $report['course_status'];
                                    if ($status == 0) {
                                        echo '<span class="badge bg-soft-warning text-warning rounded-pill p-1 px-2">';
                                        echo lang('UI_Text.Not_started');
                                        echo '</span>';
                                    } else if ($status == 1) {
                                        echo '<span class="badge bg-soft-info text-info rounded-pill p-1 px-2">';
                                        echo lang('UI_Text.In_Progress');
                                        echo '</span>';
                                    } else if ($status == 2) {
                                        echo '<span class="badge bg-soft-success text-success rounded-pill p-1 px-2">';
                                        echo lang('UI_Text.Completed');
                                        echo '</span>';
                                    }else if ($status == 3) {
                                        echo '<span class="badge bg-soft-warning text-warning rounded-pill p-1 px-2">';
                                        echo lang('UI_Text.Reviewed');
                                        echo '</span>';
                                    } ?></td>
                                <td><?php echo $report['createdbyadmin']; ?></td>
                                <td><?php echo date('d M Y', $report['createdon']); ?></td>
                                <td><?php if($report['last_updated_on']>0) { echo date('d M Y', $report['last_updated_on']); } ?></td>
                            </tr>
                            <?php
                        }
                            ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#client-assigned-report-datatable').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
            columnDefs: [{
                orderable: false,
                targets: [0]
            }],
            language: {
                search: '_INPUT_',
                searchPlaceholder: '<?= esc(lang('UI_Text.Search_Courses'), 'js') ?>',
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
<?php endif; ?>
