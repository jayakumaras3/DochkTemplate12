<?php
// Filter down to requests for projects this PM actively manages, once, so we can
// both render the rows and reliably detect the "nothing to show" empty state.
$visible_requests = [];
if (!empty($access_requests) && is_array($access_requests)) {
    foreach ($access_requests as $access) {
        if (!empty($my_active_projects) && !in_array($access['project_id'], array_column($my_active_projects, 'projectid'))) {
            continue;
        }
        $visible_requests[] = $access;
    }
}
?>
<style>
    /* Same rounded-corner + shadow + table look as SCORM/scorm_courses (courses_search_view.php). */
    .access-requests-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .access-requests-card table thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .access-requests-card table thead th {
        border-bottom-color: #36404a;
        color: #cedeef;
    }

    .access-requests-card table tbody td {
        vertical-align: middle;
    }

    .access-requests-card .dataTables_length select {
        border-radius: .5rem;
        border: 1px solid #dee2e6;
        padding: .25rem 1.75rem .25rem .6rem;
    }

    .access-requests-card .dataTables_filter input {
        border-radius: 2rem;
        border: 1px solid #dee2e6;
        padding: .4rem .75rem;
        min-width: 260px;
    }

    [data-bs-theme="dark"] .access-requests-card .dataTables_length select,
    [data-bs-theme="dark"] .access-requests-card .dataTables_filter input {
        border-color: #424e5a;
    }

    .access-requests-card .pagination .page-link {
        border-radius: 0;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/Effort_Tracker'); ?>">Effort Tracker</a></li>
                </ol>
            </div>
            <h4 class="page-title">Project Access</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card access-requests-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-bold">Project Access Requests</h5>
                    <?php if (!empty($visible_requests)) { ?>
                        <button type="button" id="approve-all-btn" class="btn btn-primary rounded-pill btn-sm waves-effect waves-light">
                            <i class="mdi mdi-check-all me-1"></i>Approve All
                        </button>
                    <?php } ?>
                </div>

                <?php if (empty($visible_requests)) { ?>
                    <div class="persistent-warning">
                        <div class="danger-text">No pending access requests.</div>
                    </div>
                <?php } else { ?>
                    <table class="table dt-responsive nowrap w-100" id="access-requests-datatable">
                        <thead>
                            <tr class="table-light">
                                <th style="width: 50px;">S.No</th>
                                <th>Project Name</th>
                                <th>Requested By</th>
                                <th>Description</th>
                                <th style="width: 120px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $sno = 1;
                            foreach ($visible_requests as $access) { ?>
                                <tr data-pu-id="<?php echo $access['pu_id']; ?>">
                                    <td><?php echo $sno++; ?></td>
                                    <td><?php echo $access['projectname']; ?></td>
                                    <td><?php echo $access['requested_by'] . ' ' . $access['requested_by_last_name']; ?></td>
                                    <td><?php echo $access['description']; ?></td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <form method="post" class="mng-response-form mb-0" action="<?php echo base_url('Project_Manage/Effort_Tracker/pm_response'); ?>"><?= csrf_field() ?>
                                                <input type="hidden" name="pu_id" value="<?php echo $access['pu_id']; ?>">
                                                <input type="hidden" name="status" value="1">
                                                <input type="hidden" name="returnurl" value="1">
                                                <button type="submit" class="btn btn-outline-success rounded-pill waves-effect btn-xs waves-light"><i class="mdi mdi-check"></i></button>
                                            </form>
                                            <form method="post" class="mng-response-form mb-0" action="<?php echo base_url('Project_Manage/Effort_Tracker/pm_response'); ?>"><?= csrf_field() ?>
                                                <input type="hidden" name="pu_id" value="<?php echo $access['pu_id']; ?>">
                                                <input type="hidden" name="status" value="3">
                                                <input type="hidden" name="returnurl" value="1">
                                                <button type="submit" class="btn btn-outline-danger rounded-pill waves-effect btn-xs waves-light"><i class="mdi mdi-close"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script>
    var effortCsrfName = '<?= csrf_token() ?>';
    var effortCsrfHash = '<?= csrf_hash() ?>';

    document.addEventListener('DOMContentLoaded', function() {
        if ($.fn.DataTable && $('#access-requests-datatable').length) {
            $('#access-requests-datatable').DataTable({
                responsive: true,
                pagingType: 'simple_numbers',
                columnDefs: [{
                    orderable: false,
                    targets: [0, 4]
                }],
                language: {
                    search: '_INPUT_',
                    searchPlaceholder: '<?= esc(lang('UI_Text.Search_Requests'), 'js') ?>',
                    lengthMenu: '_MENU_',
                    info: '<?= esc(lang('UI_Text.Datatable_Info'), 'js') ?>',
                    infoEmpty: '<?= esc(lang('UI_Text.Datatable_Info_Empty'), 'js') ?>',
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>",
                        next: "<i class='mdi mdi-chevron-right'>"
                    }
                }
            });
        }
    });

    $(document).on('submit', '.mng-response-form', function(event) {
        event.preventDefault();

        var form = $(this);
        var row = form.closest('tr');
        var buttons = row.find('button[type="submit"]');

        buttons.prop('disabled', true);

        // The token embedded at page load can go stale (e.g. if it was already
        // used by another form on this page) before this form is submitted, so
        // always send the latest known hash rather than the static one.
        form.find('input[name="' + effortCsrfName + '"]').val(effortCsrfHash);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.csrfHash) {
                    effortCsrfHash = response.csrfHash;
                    $('.mng-response-form input[name="' + effortCsrfName + '"]').val(effortCsrfHash);
                }

                if (response.status === 'OK') {
                    row.fadeOut(200, function() {
                        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#access-requests-datatable')) {
                            $('#access-requests-datatable').DataTable().row(row).remove().draw(false);
                        } else {
                            row.remove();
                        }
                    });
                } else {
                    alert(response.message || 'Something went wrong. Please contact Site Admin!');
                    buttons.prop('disabled', false);
                }
            },
            error: function(xhr) {
                if (xhr.status === 403) {
                    alert('Your session security token has expired. The page will now reload.');
                    window.location.reload();
                    return;
                }
                alert('Request failed. Please try again.');
                buttons.prop('disabled', false);
            }
        });
    });

    $(document).on('click', '#approve-all-btn', function(event) {
        event.preventDefault();

        var btn = $(this);
        var rows = $('#access-requests-datatable tbody tr[data-pu-id]');
        var puIds = rows.map(function() {
            return $(this).data('pu-id');
        }).get();

        if (puIds.length === 0) {
            alert('There are no pending requests to approve.');
            return;
        }

        if (!confirm('This will approve ' + puIds.length + ' request(s). Continue?')) {
            return;
        }

        btn.prop('disabled', true);

        $.ajax({
            url: '<?php echo base_url('Project_Manage/Effort_Tracker/pm_bulk_approve'); ?>',
            type: 'POST',
            data: {
                pu_ids: puIds,
                [effortCsrfName]: effortCsrfHash
            },
            dataType: 'json',
            success: function(response) {
                if (response.csrfHash) {
                    effortCsrfHash = response.csrfHash;
                    $('.mng-response-form input[name="' + effortCsrfName + '"]').val(effortCsrfHash);
                }

                if (response.status === 'OK') {
                    alert(response.message);
                    window.location.reload();
                } else {
                    alert(response.message || 'Something went wrong. Please contact Site Admin!');
                    btn.prop('disabled', false);
                }
            },
            error: function(xhr) {
                if (xhr.status === 403) {
                    alert('Your session security token has expired. The page will now reload.');
                    window.location.reload();
                    return;
                }
                alert('Request failed. Please try again.');
                btn.prop('disabled', false);
            }
        });
    });
</script>
