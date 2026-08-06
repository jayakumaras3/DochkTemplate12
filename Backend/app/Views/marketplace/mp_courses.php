<!-- start page title -->
<?php $client = session()->get('client'); ?>
<style>
    /* Same rounded-corner + shadow + table look as SCORM/scorm_courses (courses_search_view.php). */
    .courses-table-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .courses-table-card table thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .courses-table-card table thead th {
        border-bottom-color: #36404a;
        color: #cedeef;
    }

    .courses-table-card table tbody td {
        vertical-align: middle;
        white-space: normal;
        word-wrap: break-word;
        word-break: break-word;
    }

    .courses-table-card .dataTables_length select {
        border-radius: .5rem;
        border: 1px solid #dee2e6;
        padding: .25rem 1.75rem .25rem .6rem;
    }

    .courses-table-card .dataTables_filter input {
        border-radius: 2rem;
        border: 1px solid #dee2e6;
        padding: .4rem .75rem;
        min-width: 260px;
    }

    [data-bs-theme="dark"] .courses-table-card .dataTables_length select,
    [data-bs-theme="dark"] .courses-table-card .dataTables_filter input {
        border-color: #424e5a;
    }

    /* Square pagination buttons, matching SCORM/scorm_courses and SCORM/Scorm_learn_group. */
    .courses-table-card .pagination .page-link {
        border-radius: 0;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a
                            href="<?php echo base_url($header_link) ?>"><?php echo $header_title ?></a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('marketplace/Learning_dashboard/learning_courses') ?>"><?php echo lang('UI_Text.Learning_Plan_Details'); ?></a></li>

                </ol>
            </div>
            <h4 class="page-title"><?php echo lang('Buttons.Link_Courses'); ?> - <?php echo $mp_name; ?></h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row align-items-start">

    <!-- Left: Form -->
    <div class="col-md-12">
        <div class="card courses-table-card">
            <div class="card-body">
                <form action="<?php echo base_url('marketplace/admin/add_course_to_marketplace') ?>" method="POST" id="submitForm"><?= csrf_field() ?>

                    <div class="row align-items-center g-2">

                        <!-- Courses -->
                        <div class="col-md-5">
                            <select class="form-select" name="course_id" required>
                                <option value=""><?php echo lang('UI_Text.Select_Course_Placeholder'); ?></option>
                                <?php foreach ($get_all_courses as $courses) {
                                    $key = array_search(
                                        $courses['scourse_id'],
                                        array_column($get_courses_by_id, 'scorm_id')
                                    );
                                    if ($key === false) {
                                        echo '<option value="' . $courses['scourse_id'] . '">' .
                                            $courses['course_name'] . ' (' . $courses['course_code'] . ')</option>';
                                    }
                                } ?>
                            </select>
                        </div>

                        <!-- Pricing -->
                        <?php if ($client == 1) { ?>
                            <div class="col-md-3">
                                <input type="number"
                                    class="form-control"
                                    name="price"
                                    placeholder="<?php echo lang('UI_Text.Price_USD'); ?>"
                                    min="0" step="1" required>
                            </div>
                        <?php } else { ?>
                            <input type="hidden" name="price" value="0">
                        <?php } ?>

                        <!-- Button -->
                        <div class="col-md-2">
                            <input type="hidden" name="mp_id" value="<?php echo $mp_id; ?>">
                            <button type="submit"
                               id="submitButton"
                                class="btn btn-outline-primary rounded-pill btn-sm w-100">
                                <?php echo lang('Buttons.Add_Course'); ?>
                            </button>
                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>



    <?php if ($client == 1) { ?>
        <div class="row mb-2">
            <div class="col-12 d-flex justify-content-end">
                <a href="<?= base_url('marketplace/admin/downloadCoursesJson/' . $mp_id) ?>"
                    class="btn btn-outline-warning rounded-pill btn-sm me-2">
                    <i class="mdi mdi-code-json"></i> <?php echo lang('Buttons.Download_JSON'); ?>
                </a>

                <a href="<?= base_url('marketplace/admin/export_courses_excel/' . $mp_id) ?>"
                    class="btn btn-outline-success rounded-pill btn-sm me-2">
                    <i class="mdi mdi-file-excel"></i> <?php echo lang('Buttons.Export_to_Excel'); ?>
                </a>
            </div>
        </div>
    <?php } ?>


    <div class="col-md-12">
        <div class="card courses-table-card">
            <div class="card-body">

                    <table id="mp-courses-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr class="table-light">
                                <th>#</th>
                                <th><?php echo lang('UI_Text.Code'); ?></th>
                                <th><?php echo lang('UI_Text.Courses'); ?></th>
                                <th><?php echo lang('UI_Text.Duration_Min'); ?></th>
                                <?php if ($client == 1) { ?>
                                    <th><?php echo lang('UI_Text.Language'); ?></th>
                                    <!-- <th>Category</th> -->
                                    <!-- <th>Description</th> -->
                                    <!-- <td>Objectives</td> -->
                                    <th><?php echo lang('UI_Text.Price'); ?></th>
                                <?php } ?>


                                <th><?php echo lang('Buttons.View'); ?></th>
                                <th><?php echo lang('UI_Text.Delete'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                            foreach ($get_courses_by_id as $eachClient) {
                                $j++; ?>
                                <tr>

                                    <td><?php echo $j ?></td>
                                    <td><?php echo $eachClient['course_code']; ?> </td>
                                    <td><?php echo $eachClient['course_name']; ?> </td>
                                    <td><?php echo $eachClient['duration']; ?> </td>
                                    <?php if ($client == 1) { ?>
                                        <td><?php echo $eachClient['language']; ?> </td>
                                        <!-- <td><?php echo $eachClient['category_name']; ?> </td> -->
                                        <!-- <td><?php if (isset($eachClient['description'])) {
                                                if (strlen($eachClient['description']) > 10) {
                                                    if (strlen($eachClient['description']) > 5) {
                                                        echo $eachClient['description'];
                                                    }
                                                }
                                            } ?>
                                        </td> -->
                                        <!-- <td>
                                            <?php
                                            if (strlen($eachClient['objective']) > 5) {
                                                $objectives = explode("| ", $eachClient['objective']);

                                                echo '<ul class="text-muted">'; // Open UL with class
                                                foreach ($objectives as $obj) {
                                                    echo '<li>' . htmlspecialchars($obj) . '</li>'; // Each objective in <li>
                                                }
                                                echo '</ul>'; // Close UL
                                            }
                                            ?>

                                        </td> -->
                                        <td><?php echo $eachClient['price']; ?> </td>
                                    <?php } ?>
                                   
                                    <td>
                                        <form class="form-horizontal"
                                            action="<?php echo base_url('my_training/read_more') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="crid" value="<?php echo $eachClient['scorm_id'] ?>">
                                            <?php if ($type == 2) { ?>
                                                <input type="hidden" name="detail_type" value="12">
                                            <?php } else { ?>
                                                <input type="hidden" name="detail_type" value="5">
                                            <?php } ?>
                                            <button type="submit" onclick="this.form.submit(); this.disabled=true;"
                                                class="btn btn-outline-info rounded-pill waves-effect btn-xs waves-light" title="<?php echo lang('Buttons.View'); ?>">
                                                <span class="mdi mdi-eye-outline"></span></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="<?php echo base_url('marketplace/admin/delete_marketplace_course') ?>"
                                            method="POST"
                                            onsubmit="return confirm('<?php echo lang('Alert.Aler_002'); ?>');">
                                            <input type="hidden" name="mp_co_id"
                                                value="<?php echo $eachClient['mp_co_id']; ?>"><?= csrf_field() ?>
                                            <button type="submit" class="btn btn-outline-danger rounded-pill waves-effect btn-xs waves-light" title="<?php echo lang('Buttons.Delete'); ?>">
                                                <span class="mdi mdi-trash-can-outline"></span></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#mp-courses-datatable').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
            columnDefs: [{
                orderable: false,
                targets: [0, -1, -2]
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