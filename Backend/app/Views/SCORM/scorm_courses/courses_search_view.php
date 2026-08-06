<?php
$userlevel = session()->get('userlevel');
if (empty($userlevel)) {
    header('Location:' . base_url('my_training'));
    exit();
}
$arrayuserlevel = explode(',', $userlevel);
$canCreateCourse = in_array('44', $arrayuserlevel) || in_array('5', $arrayuserlevel);
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <?php if ($canCreateCourse) { ?>
                <div class="page-title-right">
                    <button type="button"
                        class="btn btn-outline-primary btn-xs rounded-pill waves-effect waves-light"
                        data-bs-toggle="modal"
                        data-bs-target="#createCourseModal">
                        <i class="mdi mdi-plus-circle"></i> <?php echo lang('Buttons.Create_course'); ?>
                    </button>
                </div>
            <?php } ?>
            <h4 class="page-title"><?= lang('UI_Text.Courses') ?></h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card courses-table-card">
            <div class="card-body">
                <?php if ($canCreateCourse) { ?>
                    <div class="modal fade" id="createCourseModal" tabindex="-1" aria-labelledby="createCourseModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content create-course-modal">

                                <div class="modal-header border-0 pb-0">
                                    <div>
                                        <h5 class="modal-title fw-bold" id="createCourseModalLabel">
                                            <?php echo lang('Buttons.Create_course'); ?>
                                        </h5>
                                        <p class="text-muted font-13 mb-0">Fill in the details below to create a new course.</p>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body pt-2">

                                    <?php if (isset($coursevalidation)) : ?>
                                        <div class="alert alert-danger">
                                            <?= $coursevalidation->listErrors() ?>
                                        </div>
                                    <?php endif; ?>

                                    <form id="createCourseForm"
                                        action="<?php echo base_url('SCORM/scorm_courses/add_new_course_data') ?>"
                                        method="POST"><?= csrf_field() ?>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold"><?php echo lang('UI_Text.Course_Name'); ?> <span class="text-danger">*</span></label>
                                            <input required type="text" class="form-control"
                                                name="course_name"
                                                placeholder="<?php echo lang('UI_Text.Course_Name'); ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold"><?php echo lang('UI_Text.Course_Duration'); ?> (<?php echo lang('UI_Text.Minutes'); ?>)</label>
                                            <input type="number" class="form-control"
                                                name="duration" min="0"
                                                placeholder="<?php echo lang('UI_Text.Course_Duration'); ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold"><?php echo lang('UI_Text.Language'); ?></label>
                                            <select name="language" class="form-select" required>
                                                <option value="English">English</option>
                                                <option value="Spanish">Spanish</option>
                                                <option value="French">French</option>
                                                <option value="Russian">Russian</option>
                                                <option value="Portuguese">Portuguese</option>
                                                <option value="Bahasa">Bahasa</option>
                                                <option value="Arabic">Arabic</option>
                                                <option value="German">German</option>
                                                <option value="Italian">Italian</option>
                                                <option value="Japanese">Japanese</option>
                                                <option value="Korean">Korean</option>
                                                <option value="Turkish">Turkish</option>
                                                <option value="Swedish">Swedish</option>
                                                <option value="Dutch">Dutch</option>
                                                <option value="Kazakh">Kazakh</option>
                                                <option value="Simplified Chinese">Simplified Chinese</option>
                                                <option value="Traditional Chinese">Traditional Chinese</option>
                                                <option value="Vietnamese">Vietnamese</option>
                                                <option value="Bahasa Malayasian">Bahasa Malaysian</option>
                                                <option value="Bahasa Indonesian">Bahasa Indonesian</option>
                                                <option value="Khmer">Khmer</option>
                                                <option value="Czech">Czech</option>
                                                <option value="Polish">Polish</option>
                                                <option value="Macedonian">Macedonian</option>

                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold"><?php echo lang('UI_Text.Course_Type'); ?></label>
                                            <select name="type" class="form-select">
                                                <option value="11">Course Builder</option>
                                                <option value="10">SCORM</option>
                                            </select>
                                        </div>

                                        <input type="hidden" name="project_id" value="0">
                                        <input type="hidden" name="addprojects" value="1">
                                        <input type="hidden" name="typeval" value="0">
                                        <input type="hidden" name="returnUrl" value="2">
                                        <input type="hidden" name="sc_cgid" value="<?php echo isset($sc_cgid) ? $sc_cgid : '' ?>">
                                    </form>
                                </div>

                                <div class="modal-footer border-top">
                                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                                        <?php echo lang('Buttons.Cancel') ?>
                                    </button>
                                    <button type="submit" form="createCourseForm" class="btn btn-primary rounded-pill px-4">
                                        <?php echo lang('Buttons.Submit'); ?>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <style>
                        .create-course-modal {
                            border-radius: 18px;
                            border: none;
                            box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.2);
                        }

                        .create-course-modal .form-control,
                        .create-course-modal .form-select {
                            border-radius: 10px;
                            padding: 0.55rem 0.9rem;
                        }
                    </style>
                <?php } ?>

                <?php if (!empty($hasCourses)) { ?>
                    <table id="courses-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr class="table-light">
                                <th class="center">#</th>
                                <th><?php echo lang('UI_Text.Code'); ?></th>
                                <th width="30%"><?php echo lang('UI_Text.Course_Name'); ?></th>
                                <th><?php echo lang('UI_Text.Duration'); ?></th>
                                <th><?php echo lang('UI_Text.Course_Type'); ?></th>
                                <th><?php echo lang('UI_Text.Language'); ?></th>
                                <th><?php echo lang('UI_Text.Status'); ?></th>
                                <th><?php echo lang('UI_Text.Action'); ?></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                <?php } else { ?>
                    <div class="persistent-warning">
                        <div class="danger-text">
                            <?php echo lang('UI_Text.No_Course_Assigned'); ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($hasCourses)) { ?>
    <style>
        /* Same rounded-corner + shadow treatment as etrack/dashboard.php's .card rule. */
        .courses-table-card {
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
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

        .courses-table-card table.dataTable thead th {
            border-bottom: 2px solid #eef2f7;
            color: #6c757d;
            font-weight: 700;
            white-space: nowrap;
        }

        [data-bs-theme="dark"] .courses-table-card table.dataTable thead tr {
            --ct-table-border-color: #36404a;
        }

        [data-bs-theme="dark"] .courses-table-card table.dataTable thead th {
            border-bottom-color: #36404a !important;
            color: #cedeef;
        }

        .courses-table-card table.dataTable tbody td {
            vertical-align: middle;
            white-space: normal !important;
            word-wrap: break-word;
            word-break: break-word;
        }

        .courses-table-card .pagination .page-link {
            border: none;
            margin: 0 2px;
            border-radius: 0;
            color: #6658dd;
        }

        .courses-table-card .pagination .page-item.active .page-link {
            background-color: #6658dd;
            color: #fff;
        }

        .courses-table-card .pagination .page-item.disabled .page-link {
            color: #ced4da;
            background: transparent;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const CSRF_NAME = "<?= csrf_token() ?>";
            const CSRF_HASH = "<?= csrf_hash() ?>";

            $('#courses-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '<?= base_url('SCORM/scorm_courses/courses_datatable_ajax') ?>',
                    type: 'POST',
                    data: function(d) {
                        d[CSRF_NAME] = CSRF_HASH;
                    }
                },
                responsive: true,
                pagingType: 'simple_numbers',
                order: [],
                columnDefs: [{
                        orderable: false,
                        searchable: false,
                        targets: [0, 7]
                    },
                    {
                        // Column 6 (Status) is a derived badge (mode + course_status + lesson_status),
                        // not a searchable text column, but it is sortable - see
                        // Scorm_courses::courses_datatable_ajax().
                        searchable: false,
                        targets: [6]
                    }
                ],
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
<?php } ?>
