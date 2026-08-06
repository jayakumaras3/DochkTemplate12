<style>
    /* Same rounded-corner + shadow + table look as SCORM/scorm_courses (courses_search_view.php). */
    .course-group-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .course-group-card table thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .course-group-card table thead th {
        border-bottom-color: #36404a;
        color: #cedeef;
    }

    .course-group-card table tbody td {
        vertical-align: middle;
    }

    .course-group-card .dataTables_length select {
        border-radius: .5rem;
        border: 1px solid #dee2e6;
        padding: .25rem 1.75rem .25rem .6rem;
    }

    .course-group-card .dataTables_filter input {
        border-radius: 2rem;
        border: 1px solid #dee2e6;
        padding: .4rem .75rem;
        min-width: 260px;
    }

    .course-group-card .pagination .page-link {
        border: none;
        margin: 0 2px;
        border-radius: 0;
        color: #6658dd;
    }

    .course-group-card .pagination .page-item.active .page-link {
        background-color: #6658dd;
        color: #fff;
    }

    .course-group-card .pagination .page-item.disabled .page-link {
        color: #ced4da;
        background: transparent;
    }

    [data-bs-theme="dark"] .course-group-card .dataTables_length select,
    [data-bs-theme="dark"] .course-group-card .dataTables_filter input {
        border-color: #424e5a;
    }

    [data-bs-theme="dark"] .course-group-card .pagination .page-link {
        color: #9298f5 !important;
    }

    [data-bs-theme="dark"] .course-group-card .pagination .page-item.active .page-link {
        background-color: #7b82f7 !important;
        border-color: #7b82f7 !important;
        color: #fff !important;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('SCORM/Scorm_learn_group') ?>"><?= lang('UI_Text.Course_Groups') ?></a></li>
                </ol>
            </div>
            <h4 class="page-title"><?= lang('UI_Text.Group_Courses') ?></h4>
        </div>
    </div>
</div>

<div class="row">
    <?php
    $userlevel = session()->get('userlevel');
    $arrayuserlevel = array_map('intval', explode(',', $userlevel));
    $client = session()->get('client');
    if (in_array('4', $arrayuserlevel) || in_array('5', $arrayuserlevel) || in_array('44', $arrayuserlevel)) {
    ?>
        <div class="col-md-12">
            <div class="card course-group-card">
                <div class="card-body">
                    <h5 class="section-title"><i class="mdi mdi-link-variant"></i> <?= lang('UI_Text.Link_Course_to_Group') ?></h5>
                    <form class="form-horizontal" action="<?php echo base_url('my_training/pm_add_course_to_group') ?>"
                        method="POST"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-8 col-lg-8">
                                <div class="mb-2">
                                    <select class="form-select select2-multiple" data-toggle="select2" data-width="100%"
                                        multiple="multiple" name="course_id[]" required="">
                                        <?php foreach ($all_courses as $courses) {
                                            $key = array_search($courses['scourse_id'], array_column($assigned_courses, 'course_id'));
                                            if (!empty($key) || $key === 0) {
                                            } else {
                                                echo '<option value="' . $courses['scourse_id'] . '">' . $courses['course_code'] . ' ' . $courses['course_name'] . '</option>';
                                            }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4">
                                <div class="form-group col-md-12 ">
                                    <input type="hidden" name="sc_cgid" value="<?php echo $sc_cgid; ?>">
                                    <button type="submit" class="btn btn-outline-primary rounded-pill btn-xs waves-effect waves-light">
                                        Link Course
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php
    }
    ?>

</div>

<div class="row">
    <div class="col-md-12">
        <div class="card course-group-card">
            <div class="card-body">
                <h5 class="section-title"><i class="mdi mdi-book-multiple-outline"></i> <?= lang('UI_Text.Group_Courses') ?></h5>
                <table id="course-group-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr class="table-light">
                            <th>#</th>
                            <th><?= lang('UI_Text.Code') ?></th>
                            <th><?= lang('UI_Text.Course_Name') ?></th>
                            <th><?= lang('UI_Text.Duration') ?></th>
                            <th><?= lang('UI_Text.Language') ?></th>
                            <th><?= lang('UI_Text.Action') ?></th>
                            <!-- <th>Export</th>
                            <th>Download</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        if (!empty($assigned_courses)) {
                            foreach ($assigned_courses as $assigned) {
                                // print_r($assigned);
                                // exit();
                                $j = $j + 1; ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo isset($assigned['course_code']) ? $assigned['course_code'] : '' ?></td>
                                    <td><?php echo $assigned['coursename'] ?></td>
                                    <td><?php echo isset($assigned['duration']) ? $assigned['duration'] : '' ?></td>
                                    <td>
                                        <?php
                                        if (strlen($assigned['language']) > 2) { ?>
                                            <?php echo $assigned['language'] ?>
                                        <?php } ?>
                                    </td>
                                    <td width="15%">
                                        <div class="d-flex align-items-center gap-2">
                                        <form class="form-horizontal mb-0" action="<?php echo base_url('my_training/read_more') ?>"
                                            method="POST"><?= csrf_field() ?>

                                            <input type="hidden" name="crid" value="<?php echo $assigned['scourse_id'] ?>">
                                            <input type="hidden" name="detail_type" value="8">
                                            <input type="hidden" name="tab" value="1">
                                            <button type="submit" class="btn btn-outline-info rounded-pill waves-effect btn-xs waves-light"><?= lang('Buttons.View') ?></button>
                                        </form>
                                    <!-- <td>
                                    <form class="form-horizontal2" enctype="multipart/form-data" action=<?php echo base_url('Course_builder/Scorm_course_pages/exportCoursePackage'); ?> method="post" id="submitForm"><?= csrf_field() ?>
                                        <div class="form-group col-md-12 mb-2">
                                            <input type="hidden" name="scourse_id" value="<?php echo $assigned['scourse_id'] ?>">
                                            <input type="hidden" name="Identifier" value="<?php echo 'wabtec' . $assigned['scourse_id'] ?>">
                                            <input type="hidden" name="tab" value="1">
                                            <input type="hidden" name="returnUrl" value="2">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" id="submitButton"><span class="mdi mdi-export"></span></button>
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <?php $baseloc = '';
                                    $base = base_url();
                                    if ($base == 'http://localhost/Dochek_V3/Dochek_V3') {
                                        $baseloc = '/Users/pchandran/Sites/dochek_v3/Dochek_V3/';
                                    }
                                    if ($base == 'http://localhost/projects_dochek') {
                                        $baseloc = 'D:/wampp/www/projects_dochek/';
                                    }
                                    if ($base == 'https://dochek.com/') {
                                        $baseloc = '/var/www/html/';
                                    }
                                    if ($base == 'https://www.aristo-tle.com') {
                                        $baseloc = '/';
                                    }
                                    if ($base == 'https://staging.dochek.com/') {
                                        $baseloc = '/var/www/html/DOCHEK/';
                                    }
                                    if ($base == 'http://localhost/DOCHEK/') {
                                        $baseloc = '/var/www/html/';
                                    }
                                    $folderloc = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $assigned['scourse_id'] . '/';
                                    //  print_r($fileloc);
                                    if (is_dir($folderloc)) {
                                        $files2 = scandir($folderloc, SCANDIR_SORT_DESCENDING);
                                        $sno = 0;
                                        $course_name = htmlspecialchars($assigned['course_name'], ENT_QUOTES, 'UTF-8');

                                        $courses_name = preg_replace('/["\']/', '', $assigned['course_name']);
                                        // print_r($courses_name);
                                        $filename = $baseloc . 'assets/assets/uploads/SCORM_course_document/' . $assigned['scourse_id'] . '/' . $courses_name . '.zip';
                                        // print_r(rawurlencode($course_name));
                                        if (file_exists($filename)) {
                                            // print_r($filename);
                                            foreach ($files2 as $value) {
                                                if ($value == $courses_name . '.zip') { ?>
                                                    <a href="<?php echo base_url('assets/assets/uploads/SCORM_course_document/' . $assigned['scourse_id'] . '/' . htmlspecialchars($courses_name, ENT_QUOTES, 'UTF-8') . '.zip'); ?>" class="btn btn-outline-primary waves-effect btn-xs waves-light" title="Download"><span class="mdi mdi-download"></span></a>

                                    <?php }
                                            }
                                        } else {
                                            echo '';
                                        }
                                    } else {
                                        echo '';
                                    }
                                    echo ''; ?>

                                </td> -->
                                    <?php if (in_array('44', $arrayuserlevel) || in_array('5', $arrayuserlevel)) { ?>
                                        <form class="form-horizontal mb-0"
                                            action="<?php echo base_url('SCORM/Course_builder/Scorm_course_group/deleteCoursedetails') ?>"
                                            method="POST"><?= csrf_field() ?>

                                            <input type="hidden" name="scourse_id"
                                                value="<?php echo $assigned['scourse_id'] ?>">
                                            <input type="hidden" name="assign_id"
                                                value="<?php echo $assigned['assign_id'] ?>">
                                            <button type="submit" class="btn btn-outline-danger rounded-pill waves-effect btn-xs waves-light"
                                                onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')">Un Link</button>
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
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#course-group-datatable').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
            columnDefs: [{
                orderable: false,
                searchable: false,
                targets: [0, 5]
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
</div>