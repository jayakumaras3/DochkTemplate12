<style>
    /* Same rounded-corner + shadow + table look as SCORM/scorm_courses (courses_search_view.php). */
    .client-list-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .client-list-card table thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .client-list-card table thead th {
        border-bottom-color: #36404a;
        color: #cedeef;
    }

    .client-list-card table tbody td {
        vertical-align: middle;
    }

    .client-list-card .dataTables_length select {
        border-radius: .5rem;
        border: 1px solid #dee2e6;
        padding: .25rem 1.75rem .25rem .6rem;
    }

    .client-list-card .dataTables_filter input {
        border-radius: 2rem;
        border: 1px solid #dee2e6;
        padding: .4rem .75rem;
        min-width: 260px;
    }

    [data-bs-theme="dark"] .client-list-card .dataTables_length select,
    [data-bs-theme="dark"] .client-list-card .dataTables_filter input {
        border-color: #424e5a;
    }

    .client-list-card .pagination .page-link {
        border-radius: 0;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <button type="button"
                    class="btn btn-outline-info btn-xs rounded-pill waves-effect waves-light"
                    data-bs-toggle="modal"
                    data-bs-target="#createClientModal">
                    <i class="mdi mdi-plus-circle"></i> Create New Client
                </button>
            </div>
            <h4 class="page-title">Clients</h4>
        </div>
    </div>
</div>

<div class="modal fade" id="createClientModal" tabindex="-1" aria-labelledby="createClientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content create-client-modal">

            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="createClientModalLabel">Create New Client</h5>
                    <p class="text-muted font-13 mb-0">Fill in the details below to create a new client.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body pt-2">
                <?php if (isset($validation)) : ?>
                    <div class="alert alert-danger">
                        <?= $validation->listErrors() ?>
                    </div>
                <?php endif; ?>

                <form id="createClientForm" action="<?php echo base_url('User_login/client_list/add_client') ?>" method="POST"><?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Client Name <span class="text-danger">*</span></label>
                        <input required type="text" class="form-control" name="client_name" placeholder="Client Name" value="<?= set_value('client_name') ?>">
                    </div>
                    <input type="hidden" name="redirect_url" value="1">
                </form>
            </div>

            <div class="modal-footer border-top">
                <button type="submit" form="createClientForm" class="btn btn-primary rounded-pill px-4">
                    <?php echo lang('Buttons.Create') ?>
                </button>
            </div>

        </div>
    </div>
</div>

<style>
    .create-client-modal {
        border-radius: 18px;
        border: none;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.2);
    }

    .create-client-modal .form-control {
        border-radius: 10px;
        padding: 0.55rem 0.9rem;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (isset($validation)) : ?>
            var createClientModal = new bootstrap.Modal(document.getElementById('createClientModal'));
            createClientModal.show();
        <?php endif; ?>
    });
</script>

<div class="row">
    <div class="col-md-12">
        <div class="card client-list-card">
            <div class="card-body">
                <table id="client-list-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr class="table-light">
                            <th>Client Name</th>
                            <th>Created</th>
                            <th>Validity</th>
                            <th>Status</th>
                            <!-- <th>Add Courses</th> -->
                            <!-- <th>Imper</th> -->
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $new = array();
                        foreach ($clientlist as $eachclientlist) {
                            if ($eachclientlist['id_c'] == 1) {
                                // continue;
                            }
                        ?>
                            <tr>
                                <td><?php echo $eachclientlist['client_name'] ?></td>
                                <td><?php echo date('m-d-Y', $eachclientlist['createdon']) ?></td>
                                <td><?php echo ($eachclientlist['validity'] != '0000-00-00') ? date('m-d-Y', strtotime($eachclientlist['validity'])) : ' ' ?></td>
                                <td>
                                    <?php if ($eachclientlist['status'] == 0) { ?>
                                        <span class="badge bg-soft-secondary text-secondary rounded-pill p-1 px-2">Inactive</span>
                                    <?php } else { ?>
                                        <span class="badge bg-soft-success text-success rounded-pill p-1 px-2">Active</span>
                                    <?php } ?>
                                </td>
                                <!-- <td>
                                    <form class="form-horizontal" action="<?php echo base_url($form_link1) ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="client_id" value="<?php echo $eachclientlist['id_c']; ?>">
                                        <button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">Add Course</button>
                                    </form>
                                </td> -->
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <form class="form-horizontal mb-0" action="<?php echo base_url('User_login/Partner_users') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="cid" value="<?php echo $eachclientlist['id_c'] ?>">
                                            <button type="submit" class="btn btn-outline-primary rounded-pill btn-xs waves-effect waves-light">Users</button>
                                        </form>
                                        <form class="form-horizontal mb-0" action="<?php echo base_url('SCORM/Scorm_settings') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="cid" value="<?php echo $eachclientlist['id_c']; ?>">
                                            <button type="submit" class="btn btn-outline-success rounded-pill waves-effect btn-xs waves-light">Features</button>
                                        </form>
                                        <form class="form-horizontal mb-0" action="<?php echo base_url('User_login/client_list/getcountofCMLtoclient') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="cid" value="<?php echo $eachclientlist['id_c']; ?>">
                                            <button type="submit" class="btn btn-outline-info rounded-pill btn-xs waves-effect waves-light"><span class="mdi mdi-info-outline"></span> Courses</button>
                                        </form>
                                        <!--
                                        <form class="form-horizontal mb-0" action="<?php echo base_url('Settings/settings/clientImpersonate') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="cid" value="<?php echo $eachclientlist['id_c'] ?>">
                                            <button type="submit" class="btn btn-sm widget-icon btn-primary"><span class="fa fa-random"></span></button>
                                        </form>
                                        -->
                                        <form class="form-horizontal mb-0" action="<?php echo base_url('User_login/client_list/editclientlist') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="cid" value="<?php echo $eachclientlist['id_c'] ?>">
                                            <input type="hidden" name="pr_id" value="<?php echo $eachclientlist['partner_code'] ?>">
                                            <button type="submit" class="btn btn-outline-warning rounded-pill waves-effect btn-xs waves-light">Edit</button>
                                        </form>
                                        <a onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')" href="<?php echo base_url('User_login/client_list/deleteClientlist/' . $eachclientlist['id_c']) ?>"><button class="btn btn-outline-danger rounded-pill waves-effect btn-xs waves-light">Delete</button></a>
                                    </div>
                                </td>
                            </tr>
                        <?php  }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#client-list-datatable').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
            columnDefs: [{
                orderable: false,
                targets: [4]
            }],
            language: {
                search: '_INPUT_',
                searchPlaceholder: '<?= esc(lang('UI_Text.Search_Clients'), 'js') ?>',
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
