<style>
    /* Same rounded-corner + shadow + table look as SCORM/scorm_courses (courses_search_view.php). */
    .support-table-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .support-table-card table thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .support-table-card table thead th {
        border-bottom-color: #36404a;
        color: #cedeef;
    }

    .support-table-card table tbody td {
        vertical-align: middle;
    }

    .support-table-card .dataTables_length select {
        border-radius: .5rem;
        border: 1px solid #dee2e6;
        padding: .25rem 1.75rem .25rem .6rem;
    }

    .support-table-card .dataTables_filter input {
        border-radius: 2rem;
        border: 1px solid #dee2e6;
        padding: .4rem .75rem;
        min-width: 260px;
    }

    [data-bs-theme="dark"] .support-table-card .dataTables_length select,
    [data-bs-theme="dark"] .support-table-card .dataTables_filter input {
        border-color: #424e5a;
    }

    .support-table-card .pagination .page-link {
        border-radius: 0;
    }
</style>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <button type="button"
                            class="btn btn-outline-primary btn-xs rounded-pill waves-effect waves-light float-end"
                            data-bs-toggle="modal"
                            data-bs-target="#createTicketModal">
                            <i class="mdi mdi-plus-circle"></i> <?php echo lang('Buttons.Create_Ticket') ?>
                        </button>
                    </li>
                </ol>
            </div>
            <h4 class="page-title"><?php echo lang('Buttons.Support'); ?></h4>
        </div>
    </div>
</div>
<div class="modal fade" id="createTicketModal" tabindex="-1" aria-labelledby="createTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content create-ticket-modal">

            <!-- Header -->
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="createTicketModalLabel"><?php echo lang('Buttons.Create_Ticket') ?></h5>
                    <p class="text-muted font-13 mb-0">Describe your issue and our support team will get back to you.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body -->
            <div class="modal-body pt-2">
                <form id="createTicketForm"
                    action="<?= base_url('Support/Support_user/createNewTicket') ?>"
                    method="post"
                    autocomplete="off"><?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold"><?php echo lang('UI_Text.Description') ?> <span class="text-danger">*</span></label>
                        <textarea name="description"
                            class="form-control"
                            rows="4"
                            required></textarea>
                    </div>
                </form>
            </div>

            <div class="modal-footer border-top">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    <?php echo lang('Buttons.Cancel') ?>
                </button>
                <button type="submit" form="createTicketForm" class="btn btn-primary rounded-pill px-4">
                    <?php echo lang('Buttons.Submit') ?>
                </button>
            </div>

        </div>
    </div>
</div>

<style>
    .create-ticket-modal {
        border-radius: 18px;
        border: none;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.2);
    }

    .create-ticket-modal .form-control {
        border-radius: 10px;
        padding: 0.55rem 0.9rem;
    }
</style>

<div class="col-lg-12">
    <div class="card support-table-card">
        <div class="card-body">

            <table id="support-ticket-datatable" class="table dt-responsive nowrap w-100">
                <thead>
                    <tr class="table-light">
                        <th>#</th>
                        <th><?php echo lang('UI_Text.Description') ?></th>
                        <th><?php echo lang('UI_Text.Status') ?></th>
                        <th><?php echo lang('UI_Text.Action') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $j = 0;
                    if (!empty($myTickets)) {

                        foreach ($myTickets as $k) {
                    ?>
                            <tr>
                                <td><?php echo $k['id']; ?></td>
                                <td title="<?php echo htmlspecialchars($k['description']); ?>">
                                    <?php
                                    echo htmlspecialchars(
                                        strlen($k['description']) > 70
                                            ? substr($k['description'], 0, 70) . '...'
                                            : $k['description']
                                    );
                                    ?>
                                </td>

                                <td><?php
                                    $status = $k['status'];
                                    // echo $status;
                                    switch ($status) {
                                        case 1:
                                            echo "<span class='badge bg-soft-primary text-primary rounded-pill p-1 px-2'>" . lang('UI_Text.New') . "</span>";
                                            break;
                                        case 2:
                                            echo "<span class='badge bg-soft-info text-info rounded-pill p-1 px-2'>" . lang('UI_Text.Replied') . "</span>";
                                            break;
                                        case 3:
                                            echo "<span class='badge bg-soft-warning text-warning rounded-pill p-1 px-2'>" . lang('UI_Text.Commented') . "</span>";
                                            break;
                                        case 4:
                                            echo "<span class='badge bg-soft-danger text-danger rounded-pill p-1 px-2'>" . lang('UI_Text.Re_Open') . "</span>";
                                            break;
                                        case 5:
                                            echo "<span class='badge bg-soft-success text-success rounded-pill p-1 px-2'>" . lang('UI_Text.Closed') . "</span>";
                                            break;
                                    } ?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <form class="form-horizontal mb-0" action="<?php echo base_url('Support/Support_user/viewTicketDetails') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="ticketID" value="<?php echo $k['id'] ?>">
                                            <input type="hidden" name="type_of_access" value="3">
                                            <button type="submit" class="btn btn-outline-info rounded-pill waves-effect btn-xs waves-light"><?php echo lang('Buttons.View') ?></button>
                                        </form>
                                        <?php if ($status != 5) { ?>
                                            <form class="form-horizontal mb-0 confirm-before-submit" data-confirm-message="<?php echo esc(lang('Alert.Aler_012'), 'attr') ?>" action="<?php echo base_url('Support/Support_user/replyTicket') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="ticketID" value="<?php echo $k['id'] ?>">
                                                <input type="hidden" name="replies" value="Ticket Closed">
                                                <input type="hidden" name="replytype" value="5">
                                                <button type="submit" class="btn btn-outline-danger rounded-pill waves-effect btn-xs waves-light"><?php echo lang('Buttons.Close') ?></button>
                                            </form>
                                        <?php } else { ?>
                                            <form class="form-horizontal mb-0 confirm-before-submit" data-confirm-message="<?php echo esc(lang('Alert.Aler_012'), 'attr') ?>" action="<?php echo base_url('Support/Support_user/viewTicketDetails') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="ticketID" value="<?php echo $k['id'] ?>">
                                                <input type="hidden" name="type_of_access" value="3">
                                                <button type="submit" class="btn btn-outline-warning rounded-pill waves-effect btn-xs waves-light"><?php echo lang('Buttons.Reopen') ?></button>
                                            </form>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#support-ticket-datatable').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
            columnDefs: [{
                orderable: false,
                targets: [0, 3]
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

        document.addEventListener('submit', function(e) {
            var form = e.target.closest('.confirm-before-submit');
            if (form && !confirm(form.dataset.confirmMessage)) {
                e.preventDefault();
            }
        });
    });
</script>
