<style>
    /* Same rounded-corner + shadow + table look as Certification/Dashboard and other
       redesigned pages this session. */
    .assign-certificate-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .assign-certificate-card .form-select {
        border-radius: 10px;
        padding: .55rem .9rem;
    }

    .assign-certificate-card table.dataTable thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .assign-certificate-card table.dataTable thead th {
        border-bottom-color: #36404a;
        color: #cedeef;
    }

    .assign-certificate-card table.dataTable tbody td {
        vertical-align: middle;
    }

    .assign-certificate-card .dataTables_length select {
        border-radius: .5rem;
        border: 1px solid #dee2e6;
        padding: .25rem 1.75rem .25rem .6rem;
    }

    .assign-certificate-card .dataTables_filter input {
        border-radius: 2rem;
        border: 1px solid #dee2e6;
        padding: .4rem .75rem;
        min-width: 260px;
    }

    [data-bs-theme="dark"] .assign-certificate-card .dataTables_length select,
    [data-bs-theme="dark"] .assign-certificate-card .dataTables_filter input {
        border-color: #424e5a;
    }

    .assign-certificate-card .pagination .page-link {
        border: none;
        margin: 0 2px;
        border-radius: 0;
        color: #6658dd;
    }

    .assign-certificate-card .pagination .page-item.active .page-link {
        background-color: #6658dd;
        color: #fff;
    }

    .assign-certificate-card .pagination .page-item.disabled .page-link {
        color: #ced4da;
        background: transparent;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Certification/Dashboard') ?>"><?= lang('UI_Text.Certificate') ?></a></li>
                </ol>
            </div>
            <h4 class="page-title"><?= lang('Buttons.Assign') ?>
                <?php
                if ($type == 4) {
                    echo " - " . lang('UI_Text.Learning_Plan');
                }
                if ($type == 3) {
                    echo " - " . lang('UI_Text.Courses');
                }
                if ($type == 2) {
                    echo " - " . lang('UI_Text.Learning_Plan');
                }
                if ($type == 1) {
                    echo " - " . lang('UI_Text.Marketplace');
                }
                ?>
            </h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <?php if ($type == 3) { ?>
            <div class="card assign-certificate-card">
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('Certification/Dashboard/assign_cert_to_course') ?>" method="POST"><?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="type" class="form-label fw-semibold"><?= lang('Buttons.Assign') ?> <?= lang('UI_Text.Courses') ?></label>
                            <select class="form-select" name="course_id">
                                <?php foreach ($get_all_courses as $all_courses) {
                                    $key = array_search($all_courses['scourse_id'], array_column($get_assigned_courses, 'scourse_id'));
                                    if (!empty($key) || $key === 0) {
                                    } else {
                                ?>
                                        <option value="<?php echo $all_courses["scourse_id"]; ?>"><?php echo $all_courses["name"]; ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <input type="hidden" name="type" value="3">
                        <button type="submit" class="btn btn-outline-primary rounded-pill waves-effect waves-light"><?= lang('Buttons.Submit') ?></button>
                    </form>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        <?php } ?>
        <?php if ($type < 3 || $type == 4) { ?>
            <div class="card assign-certificate-card">
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('Certification/Dashboard/assign_cert_to_course') ?>" method="POST"><?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="type" class="form-label fw-semibold"><?= lang('Buttons.Assign') ?>
                                <?php
                                if ($type == 4) {
                                    echo " - <a href=" . base_url("marketplace/learning_dashboard") . ">" . lang('UI_Text.Learning_Plan') . "</a>";
                                }
                                if ($type == 3) {
                                    echo " - " . lang('UI_Text.Courses');
                                }
                                if ($type == 2) {
                                    echo lang('UI_Text.Learning_Plan');
                                }
                                if ($type == 1) {
                                    echo lang('UI_Text.Marketplace');
                                }
                                ?>
                            </label>

                            <select class="form-select" name="course_id">
                                <?php foreach ($get_all_learning_plan as $all_lp) {
                                    $key = array_search($all_lp['scourse_id'], array_column($get_assigned_lp, 'scourse_id'));
                                    if (!empty($key) || $key === 0) {
                                    } else {
                                ?>

                                        <option value="<?php echo $all_lp["scourse_id"]; ?>"><?php echo $all_lp["name"]; ?></option>

                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <input type="hidden" name="type" value="<?php echo $type; ?>">
                        <?php if ($type == 2 || $type == 4 || $type == 1) { ?>
                            <button type="submit" class="btn btn-outline-primary rounded-pill waves-effect waves-light"><?= lang('Buttons.Submit') ?></button>
                        <?php } ?>
                    </form>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        <?php } ?>
    </div>
    <div class="col-lg-6">
        <div class="card assign-certificate-card">
            <div class="card-body">
                <table id="assign-certificate-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr class="table-light">
                            <th>#</th>
                            <th><?= lang('UI_Text.Name') ?></th>
                            <th><?= lang('Buttons.Delete') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;

                        if ($type == 3) {
                            foreach ($get_assigned_courses as $courses_assigned) {
                                $j++;
                        ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $courses_assigned['name']; ?></td>
                                    <td>
                                        <form action="<?php echo base_url('Certification/Dashboard/Un_Assign') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="cert_assign_id" value="<?php echo $courses_assigned['cert_assign_id']; ?>">
                                            <button type="submit" onclick="return confirm('<?php echo lang('Alert.Aler_004') ?>')" class="btn btn-outline-danger rounded-pill waves-effect btn-xs waves-light">
                                                <?= lang('Buttons.Delete') ?></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php
                            }
                        }

                        if ($type == 2 || $type == 4) {
                            foreach ($get_assigned_lp as $lp_assigned) {
                                $j++;
                            ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $lp_assigned['name']; ?></td>
                                    <td>
                                        <form action="<?php echo base_url('Certification/Dashboard/Un_Assign') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="cert_assign_id" value="<?php echo $lp_assigned['cert_assign_id']; ?>">
                                            <button type="submit" onclick="return confirm('<?php echo lang('Alert.Aler_004') ?>')" class="btn btn-outline-danger rounded-pill waves-effect btn-xs waves-light">
                                                <?= lang('Buttons.Delete') ?></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php
                            }
                        }

                        if ($type == 1) {
                            foreach ($get_assigned_lp as $mp_assigned) {
                                $j++;
                            ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $mp_assigned['name']; ?></td>
                                    <td>
                                        <form action="<?php echo base_url('Certification/Dashboard/Un_Assign') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="cert_assign_id" value="<?php echo $mp_assigned['cert_assign_id']; ?>">
                                            <button type="submit" onclick="return confirm('<?php echo lang('Alert.Aler_004') ?>')" class="btn btn-outline-danger rounded-pill waves-effect btn-xs waves-light">
                                                <?= lang('Buttons.Delete') ?></button>
                                        </form>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- end col -->
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if ($.fn.DataTable.isDataTable('#assign-certificate-datatable')) {
            return;
        }
        $('#assign-certificate-datatable').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
            columnDefs: [{
                orderable: false,
                targets: [-1]
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
