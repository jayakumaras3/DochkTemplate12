<style>
    /* Same rounded-corner + shadow + table look as SCORM/scorm_courses (courses_search_view.php). */
    .settings-section {
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
        border: none;
    }

    .settings-section .section-title {
        display: flex;
        align-items: center;
        gap: .5rem;
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 1rem;
    }

    .settings-section .section-title i {
        color: #6658dd;
    }

    [data-bs-theme="dark"] .settings-section .section-title i {
        color: #9298f5;
    }

    .settings-section table thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .settings-section table thead th {
        border-bottom-color: #36404a;
        color: #cedeef;
    }

    .settings-section table tbody td {
        vertical-align: middle;
    }

    .settings-section .dataTables_length select {
        border-radius: .5rem;
        border: 1px solid #dee2e6;
        padding: .25rem 1.75rem .25rem .6rem;
    }

    .settings-section .dataTables_filter input {
        border-radius: 2rem;
        border: 1px solid #dee2e6;
        padding: .4rem .75rem;
        min-width: 260px;
    }

    [data-bs-theme="dark"] .settings-section .dataTables_length select,
    [data-bs-theme="dark"] .settings-section .dataTables_filter input {
        border-color: #424e5a;
    }

    /* Square pagination buttons (courses_search_view.php rounds these; this table keeps them square). */
    .settings-section .pagination .page-link {
        border-radius: 0;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <?php $userlevel = session()->get('userlevel');
            $arrayuserlevel = explode(',', $userlevel); ?>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('my_training') ?>"><?= lang('Buttons.Dashboard') ?></a></li>
                </ol>
            </div>
            <h4 class="page-title"><?= lang('UI_Text.Course_Groups') ?></h4>
        </div>
        <!-- <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?php echo lang('Statements.State_0005'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div> -->
    </div>
</div>

<div class="row mt-3">
    <?php if (in_array('44', $arrayuserlevel) || in_array('5', $arrayuserlevel)) { ?>
        <div class="col-md-4">
            <div class="card settings-section mb-3">
                <div class="card-body">
                    <h5 class="section-title"><i class="mdi mdi-folder-plus-outline"></i> <?= lang('UI_Text.Create_New_Course_Group') ?></h5>
                    <form action="<?php echo base_url($form_link) ?>" method="post" autocomplete="off" id="submitForm"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-lg-12 mb-2">
                                <label><?= lang('UI_Text.Group_Name') ?></label>
                                <input type="text" name="description" class="form-control" value=""
                                    required="" />
                            </div>
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-outline-primary rounded-pill waves-effect btn-xs waves-light"
                                    id="submitButton"><?= lang('Buttons.Submit') ?></button>
                            </div>
                        </div>
                        <?php if (isset($validation)): ?>
                            <div class=col-12 col-sm-4>
                                <div class="alert alert-danger" role="alert">
                                    <?= $validation->listErrors() ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="col-md-8">
        <div class="card settings-section mb-3">
            <div class="card-body">
                <h5 class="section-title"><i class="mdi mdi-format-list-bulleted"></i> <?= lang('UI_Text.Course_Groups') ?></h5>

                <table id="course-group-list-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr class="table-light">
                            <th width="10%">#</th>
                            <th><?= lang('UI_Text.Group_Name') ?></th>
                            <?php if (in_array('44', $arrayuserlevel) || in_array('5', $arrayuserlevel)) { ?>
                                <th><?= lang('UI_Text.Group_Users') ?></th>
                            <?php } ?>
                            <th width="20%"><?= lang('UI_Text.Group_Courses') ?></th>
                            <?php if (in_array('44', $arrayuserlevel) || in_array('5', $arrayuserlevel)) { ?>
                                <th width="10%"><?= lang('UI_Text.Action') ?></th>
                            <?php } ?>

                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        foreach ($coursegroupdata as $eachcoursegroupdata) {
                            $j = $j + 1; ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo $eachcoursegroupdata['description']; ?></td>
                                <?php if (in_array('44', $arrayuserlevel) || in_array('5', $arrayuserlevel)) { ?>
                                    <td><?php $visible = $eachcoursegroupdata['visible'];
                                        if ($visible == 0) {
                                            echo "All";
                                        } else {
                                        ?>
                                            <form class="form-horizontal" action="<?php echo base_url($form_link_3) ?>"
                                                method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="sc_cgid"
                                                    value="<?php echo $eachcoursegroupdata['sc_cgid'] ?>">
                                                <button type="submit" class="btn btn-outline-info rounded-pill waves-effect btn-xs waves-light"><?= lang('Buttons.View') ?></button>
                                            </form>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                <?php } ?>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url('my_training/course_group_list') ?>"
                                        method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="sc_cgid"
                                            value="<?php echo $eachcoursegroupdata['sc_cgid'] ?>">
                                        <button type="submit"
                                            class="btn btn-outline-success rounded-pill waves-effect btn-xs waves-light "><?php echo $eachcoursegroupdata['assign_id_count']; ?></button>
                                    </form>
                                </td>
                                <?php if (in_array('44', $arrayuserlevel) || in_array('5', $arrayuserlevel)) { ?>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url($form_link_2) ?>"
                                            method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="sc_cgid"
                                                value="<?php echo $eachcoursegroupdata['sc_cgid'] ?>">
                                            <button type="submit" class="btn btn-outline-warning rounded-pill waves-effect btn-xs waves-light "><?= lang('UI_Text.Edit') ?></button>
                                        </form>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>


</div>

<script>
    function target_popup1(url) {

        url = url.trim()
        params = 'width=' + screen.width;
        params += ', height=' + screen.height;
        params += ', top=0, left=0'
        params += ', fullscreen=yes';
        newwin = window.open('http://' + url, 'windowname4', params);
        if (window.focus) {
            newwin.focus();
        }
        return false;
    }

    function target_popup2(filename, demoid) {

        params = 'width=' + screen.width;
        params += ', height=' + screen.height;
        params += ', top=0, left=0'
        params += ', fullscreen=yes';
        newwin = window.open('http://purpleframetech.us/demos/upload/client/' + demoid + '/' + filename, 'windowname5', params);
        if (window.focus) {
            newwin.focus();
        }
        return false;
    }
    $(document).ready(function() {

        $('#course-group-list-datatable').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
            ordering: false,
            language: {
                search: '_INPUT_',
                searchPlaceholder: '<?= esc(lang('UI_Text.Search_Group_Name'), 'js') ?>',
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