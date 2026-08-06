<style>
    /* Same rounded-corner + shadow + table look as SCORM/scorm_courses (courses_search_view.php). */
    .ticket-reply-card,
    .ticket-details-table-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .ticket-reply-card .form-control {
        border-radius: 10px;
        padding: .55rem .9rem;
    }

    .ticket-details-table-card table thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .ticket-details-table-card table thead th {
        border-bottom-color: #36404a;
        color: #cedeef;
    }

    .ticket-details-table-card table tbody td {
        vertical-align: middle;
    }

    .ticket-details-table-card .dataTables_length select {
        border-radius: .5rem;
        border: 1px solid #dee2e6;
        padding: .25rem 1.75rem .25rem .6rem;
    }

    .ticket-details-table-card .dataTables_filter input {
        border-radius: 2rem;
        border: 1px solid #dee2e6;
        padding: .4rem .75rem;
        min-width: 260px;
    }

    [data-bs-theme="dark"] .ticket-details-table-card .dataTables_length select,
    [data-bs-theme="dark"] .ticket-details-table-card .dataTables_filter input {
        border-color: #424e5a;
    }

    .ticket-details-table-card .pagination .page-link {
        border-radius: 0;
    }

    .ticket-attachments-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .ticket-attachments-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
        background: rgba(102, 88, 221, .12);
        color: #6658dd;
    }

    [data-bs-theme="dark"] .ticket-attachments-icon {
        background: rgba(146, 152, 245, .18);
        color: #9298f5;
    }

    .ticket-attachments-card .form-control {
        border-radius: 10px;
        padding: .5rem .9rem;
    }

    .attachment-thumb {
        display: block;
        width: 96px;
        height: 96px;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #eef2f7;
    }

    [data-bs-theme="dark"] .attachment-thumb {
        border-color: #36404a;
    }

    .attachment-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .attachment-thumb-wrap {
        position: relative;
        width: 96px;
    }

    .attachment-delete-btn {
        position: absolute;
        top: -8px;
        right: -8px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fa5c7c;
        color: #fff;
        border: 2px solid #fff;
        font-size: 12px;
        padding: 0;
        line-height: 1;
    }

    .attachment-delete-btn:hover {
        background: #e2445e;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header_link); ?>"><?= lang('Buttons.Support') ?></a></li>
                </ol>
            </div>
            <h4 class="page-title"><?= lang('UI_Text.Ticket_Details') ?></h4>
        </div>
    </div>
</div>
<div class="row">
    <?php if ($ticketDescription[0]['status'] == 4) { ?>
        <div class="col-sm-6">
            <div class="card ticket-reply-card">
                <div class="card-body">
                    <form action="<?php echo base_url('Support/Support_user/replyTicket') ?>" method="post" autocomplete="off"><?= csrf_field() ?>
                        <div class="mb-3">
                            <input type="text" name="replies" class="form-control" placeholder="" value="" required="" />
                        </div>
                        <div class="form-row">
                            <input type="hidden" name="ticketID" value="<?php echo $ticketDescription[0]['id']; ?>" />
                            <input type="hidden" name="replytype" value="3" />
                            <button type="submit" class="btn btn-outline-danger rounded-pill btn-xs waves-effect waves-light"><?= lang('UI_Text.Re_Open') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="col-sm-6">
            <div class="card ticket-reply-card">
                <div class="card-body">
                    <form action="<?php echo base_url('Support/Support_user/replyTicket') ?>" method="post" autocomplete="off"><?= csrf_field() ?>
                        <div class="mb-3">
                            <input type="text" name="replies" class="form-control" placeholder="" value="" required="" />
                        </div>
                        <div class="form-row">
                            <input type="hidden" name="ticketID" value="<?php echo $ticketDescription[0]['id']; ?>" />
                            <input type="hidden" name="replytype" value="<?php echo $replytype; ?>" />
                            <button type="submit" class="btn btn-outline-warning rounded-pill btn-xs waves-effect waves-light"><?= lang('UI_Text.Reply') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="col-6">
        <div class="card ticket-attachments-card">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="ticket-attachments-icon"><i class="mdi mdi-image-multiple-outline"></i></div>
                    <div>
                        <h5 class="mb-0 fw-bold">Attachments</h5>
                        <p class="text-muted mb-0 font-13">Upload a screenshot or photo related to this ticket. JPG or PNG only, up to 1MB.</p>
                    </div>
                </div>

                <form action="<?php echo base_url('Support/Support_user/uploadTicketImage') ?>" method="POST" enctype="multipart/form-data" id="ticketImageForm" class="mb-3"><?= csrf_field() ?>
                    <input type="hidden" name="ticketID" value="<?php echo $ticketDescription[0]['id']; ?>" />
                    <input type="hidden" name="type_of_access" value="<?php echo isset($replytype) ? $replytype : ''; ?>" />
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <input type="file" name="ticket_image" id="ticketImageInput" accept=".jpg,.jpeg,.png,image/jpeg,image/png" class="form-control" style="max-width:320px;" required>
                        <button type="submit" class="btn btn-primary rounded-pill btn-sm">
                            <i class="mdi mdi-tray-arrow-up me-1"></i>Upload Image
                        </button>
                    </div>
                    <p class="text-muted font-13 mt-2 mb-0" id="ticketImageError"></p>
                </form>

                <?php if (!empty($ticketImages)) : ?>
                    <div class="d-flex flex-wrap gap-3">
                        <?php foreach ($ticketImages as $img) : ?>
                            <div class="attachment-thumb-wrap">
                                <a href="<?= esc($img['url']) ?>" target="_blank" rel="noopener noreferrer" class="attachment-thumb">
                                    <img src="<?= esc($img['url']) ?>" alt="Ticket attachment">
                                </a>
                                <form action="<?php echo base_url('Support/Support_user/deleteTicketImage') ?>" method="POST" class="confirm-before-submit mb-0" data-confirm-message="Delete this image? This cannot be undone.">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="ticketID" value="<?php echo $ticketDescription[0]['id']; ?>">
                                    <input type="hidden" name="type_of_access" value="<?php echo isset($replytype) ? $replytype : ''; ?>">
                                    <input type="hidden" name="filename" value="<?= esc($img['name']) ?>">
                                    <button type="submit" class="attachment-delete-btn" title="Delete image" aria-label="Delete image">
                                        <i class="mdi mdi-close"></i>
                                    </button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p class="text-muted font-13 mb-0">No images uploaded yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card ticket-details-table-card">
            <div class="card-body">
                <table id="ticket-details-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr class="table-light">
                            <th width="10%">#</th>
                            <th><?= lang('UI_Text.Description') ?>/<?= lang('UI_Text.Reply') ?></th>
                            <th width="10%"><?= lang('UI_Text.Created_By') ?></th>
                            <th width="10%"><?= lang('UI_Text.Created_On') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        if (!empty($ticketDescription)) {
                            foreach ($ticketDescription as $t) {
                                $j++;
                        ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td class="wrap-text"><?php echo $t['description']; ?></td>
                                    <td class="wrap-text"><?php echo $t['name']; ?> </td>
                                    <td class="wrap-text"><?php echo date('Y-m-d', $t['createdon']); ?> </td>
                                </tr>
                            <?php }
                        }

                        if (!empty($getTicketReplies)) {
                            foreach ($getTicketReplies as $k) {
                                $j++;
                            ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td class="wrap-text"><?php echo $k['replies']; ?></td>
                                    <td class="wrap-text"><?php echo $k['name']; ?> </td>
                                    <td class="wrap-text"><?php echo date('Y-m-d', $k['createdon']); ?> </td>
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
        $('#ticket-details-datatable').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
            order: [],
            columnDefs: [{
                orderable: false,
                targets: [0]
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

        var ticketImageForm = document.getElementById('ticketImageForm');
        var ticketImageInput = document.getElementById('ticketImageInput');
        var ticketImageError = document.getElementById('ticketImageError');
        var allowedTypes = ['image/jpeg', 'image/png'];
        var maxBytes = 1024 * 1024; // 1MB - mirrors the server-side max_size[] rule.

        if (ticketImageForm && ticketImageInput) {
            ticketImageForm.addEventListener('submit', function(e) {
                ticketImageError.textContent = '';
                var file = ticketImageInput.files[0];

                if (!file) {
                    return;
                }

                if (allowedTypes.indexOf(file.type) === -1) {
                    e.preventDefault();
                    ticketImageError.textContent = 'Only JPG or PNG images are allowed.';
                    return;
                }

                if (file.size > maxBytes) {
                    e.preventDefault();
                    ticketImageError.textContent = 'Image must be 1MB or smaller.';
                }
            });
        }

        document.addEventListener('submit', function(e) {
            var form = e.target.closest('.confirm-before-submit');
            if (form && !confirm(form.dataset.confirmMessage)) {
                e.preventDefault();
            }
        });
    });
</script>
