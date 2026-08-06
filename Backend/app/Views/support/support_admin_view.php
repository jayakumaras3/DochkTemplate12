<style>
    /* Same rounded-corner + shadow + table look as SCORM/scorm_courses (courses_search_view.php). */
    .support-admin-table-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .support-admin-table-card table thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .support-admin-table-card table thead th {
        border-bottom-color: #36404a;
        color: #cedeef;
    }

    .support-admin-table-card table tbody td {
        vertical-align: middle;
    }

    .support-admin-table-card .dataTables_length select {
        border-radius: .5rem;
        border: 1px solid #dee2e6;
        padding: .25rem 1.75rem .25rem .6rem;
    }

    .support-admin-table-card .dataTables_filter input {
        border-radius: 2rem;
        border: 1px solid #dee2e6;
        padding: .4rem .75rem;
        min-width: 260px;
    }

    [data-bs-theme="dark"] .support-admin-table-card .dataTables_length select,
    [data-bs-theme="dark"] .support-admin-table-card .dataTables_filter input {
        border-color: #424e5a;
    }

    .support-admin-table-card .pagination .page-link {
        border-radius: 0;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title"><?=  lang('UI_Text.Admin_Support') ?></h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card support-admin-table-card">
            <div class="card-body">
                <table id="admin-support-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr class="table-light">
                            <th>#</th>
                            <th><?=  lang('UI_Text.Description') ?></th>
                            <th><?=  lang('UI_Text.Status') ?></th>
                            <th><?=  lang('UI_Text.Created_By') ?></th>
                            <th width="15%"><?=  lang('UI_Text.Created_On') ?></th>
                            <th><?=  lang('UI_Text.Action') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        if (!empty($myTickets)) {
                            foreach ($myTickets as $k) { ?>
                                <tr>
                                    <td><?php echo $k['id']; ?></td>
                                    <td title="<?php echo htmlspecialchars($k['description']); ?>">
                                        <?php
                                        echo htmlspecialchars(
                                            strlen($k['description']) > 50
                                                ? substr($k['description'], 0, 50) . '...'
                                                : $k['description']
                                        );
                                        ?>
                                    </td>

                                    <td><?php
                                        $status = $k['status'];
                                        switch ($status) {
                                            case 1:
                                                echo "<span class='badge bg-soft-primary text-primary rounded-pill p-1 px-2'>".lang('UI_Text.New')."</span>";
                                                break;
                                            case 2:
                                                echo "<span class='badge bg-soft-info text-info rounded-pill p-1 px-2'>".lang('UI_Text.Replied')."</span>";
                                                break;
                                            case 3:
                                                echo "<span class='badge bg-soft-warning text-warning rounded-pill p-1 px-2'>".lang('UI_Text.Commented')."</span>";
                                                break;
                                            case 4:
                                                echo "<span class='badge bg-soft-danger text-danger rounded-pill p-1 px-2'>".lang('UI_Text.Re_Open')."</span>";
                                                break;
                                            case 5:
                                                echo "<span class='badge bg-soft-success text-success rounded-pill p-1 px-2'>".lang('UI_Text.Closed')."</span>";
                                                break;
                                        } ?>
                                    </td>
                                    <td><?php echo $k['creator']; ?></td>
                                    <td><?php echo date('Y-m-d', $k['createdon']); ?></td>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url('Support/Support_user/AdminviewTicketDetails') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="ticketID" value="<?php echo $k['id'] ?>">
                                            <input type="hidden" name="type_of_access" value="2">
                                            <button type="submit" class="btn btn-outline-info rounded-pill waves-effect btn-xs waves-light"><?= lang('Buttons.View') ?></button>
                                        </form>
                                    </td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#admin-support-datatable').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
            columnDefs: [{
                orderable: false,
                targets: [0, 5]
            }],
            language: {
                search: '_INPUT_',
                searchPlaceholder: '<?= esc(lang('UI_Text.Search_Tickets'), 'js') ?>',
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
