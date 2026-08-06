<style>
    /* Same rounded-corner + shadow + table look as SCORM/scorm_courses (courses_search_view.php). */
    .notification-table-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .notification-table-card table thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .notification-table-card table thead th {
        border-bottom-color: #36404a;
        color: #cedeef;
    }

    .notification-table-card table tbody td {
        vertical-align: middle;
    }

    .notification-table-card .dataTables_length select {
        border-radius: .5rem;
        border: 1px solid #dee2e6;
        padding: .25rem 1.75rem .25rem .6rem;
    }

    .notification-table-card .dataTables_filter input {
        border-radius: 2rem;
        border: 1px solid #dee2e6;
        padding: .4rem .75rem;
        min-width: 260px;
    }

    [data-bs-theme="dark"] .notification-table-card .dataTables_length select,
    [data-bs-theme="dark"] .notification-table-card .dataTables_filter input {
        border-color: #424e5a;
    }

    .notification-table-card .pagination .page-link {
        border-radius: 0;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title"><?php echo lang('UI_Text.Notifications'); ?></h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card notification-table-card">
            <div class="card-body">
                <table id="notification-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr class="table-light">
                            <th width="10%">ID</th>
                            <th><?php echo lang('UI_Text.Description'); ?></th>
                            <th><?php echo lang('UI_Text.Start_Date'); ?></th>
                            <th><?php echo lang('UI_Text.End_Date'); ?></th>
                            <th><?php echo lang('UI_Text.Details'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        if (!empty($latest_notifications)) {
                            foreach ($latest_notifications as $k) {
                                $j++;
                        ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $k['short_name']; ?></td>
                                    <td><?php echo $k['start_date']; ?></td>
                                    <td><?php echo $k['end_date']; ?></td>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url('Support/Support_user/view_detailed_notification') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="notification_id" value="<?php echo $k['notification_id'] ?>">
                                            <button type="submit" class="btn btn-outline-primary rounded-pill waves-effect btn-xs waves-light"><span class="mdi mdi-eye-outline"></span> <?php echo lang('UI_Text.view'); ?></button>
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
        $('#notification-datatable').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
            columnDefs: [{
                orderable: false,
                targets: [0, 4]
            }],
            language: {
                search: '_INPUT_',
                searchPlaceholder: '<?= esc(lang('UI_Text.Search_Notifications'), 'js') ?>',
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
