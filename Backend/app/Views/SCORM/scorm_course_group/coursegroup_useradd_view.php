<style>
    /* Same rounded-corner + shadow + table look as SCORM/Scorm_learn_group (course_group_view.php). */
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

    /* Square pagination buttons, matching SCORM/Scorm_learn_group. */
    .settings-section .pagination .page-link {
        border-radius: 0;
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
            <h4 class="page-title"><?= lang('UI_Text.Group_Users') ?></h4>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-4">
        <div class="card settings-section mb-3">
            <div class="card-body">
                <h5 class="section-title"><i class="mdi mdi-account-plus-outline"></i> <?= lang('UI_Text.Add_User_to_Group') ?></h5>
                <form class="form-horizontal" action="<?php echo base_url('SCORM/Scorm_learn_group/add_coursegroup_user') ?>" method="POST" id="submitForm"><?= csrf_field() ?>
                    <div class="mb-2">
                        <select class="form-select" name="user_id" required="">
                            <?php foreach ($getUserclientlist as $users) {
                                $key = array_search($users['id_user'], array_column($group_users, 'id_user'));
                                if (!empty($key) || $key === 0) {
                                } else {
                                    echo '<option value="' .  $users['id_user'] . '">' . $users['name'] . ' ' . $users['last_name'] . '</option>';
                                }
                            } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <input type="hidden" name="sc_cgid" value="<?php echo $sc_cgid; ?>">
                        <button type="submit" class="btn btn-outline-info rounded-pill waves-effect btn-xs waves-light " id="submitButton">
                            <?= lang('Buttons.Submit') ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card settings-section mb-3">
            <div class="card-body">
                <h5 class="section-title"><i class="mdi mdi-account-group-outline"></i> <?= lang('UI_Text.Assign_Course_Group_to_User_Group') ?></h5>
                <?php
                // The course group itself isn't picked here - it's whichever group the
                // user arrived from ($sc_cgid) - so name it explicitly, otherwise the
                // page only shows a "user group" dropdown with no visible course group,
                // which reads as if the form's own title/direction is wrong.
                $currentCourseGroup = null;
                foreach ($course_group as $group) {
                    if ($group['sc_cgid'] == $sc_cgid) {
                        $currentCourseGroup = $group['description'];
                        break;
                    }
                }
                ?>
                <?php if ($currentCourseGroup !== null): ?>
                    <p class="mb-2"><strong><?= lang('UI_Text.Course_Groups') ?>:</strong> <?= esc($currentCourseGroup) ?></p>
                <?php endif; ?>
                <form action="<?php echo base_url('SCORM/scorm_user_group/assignCoursegrouptoUsergroup') ?>" method="post" autocomplete="off" id="submitForm"><?= csrf_field() ?>
                    <div class="mb-2">
                        <label><?= lang('UI_Text.Select_User_Group') ?></label>
                        <select class="form-select" name="u_gid" required>

                            <?php foreach ($user_group as $group) { ?>
                                <option value="<?php echo $group['sc_cgid'] ?>"><?php echo $group['description'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <input type="hidden" name="c_gid" value="<?php echo $sc_cgid ?>" />
                        <button type="submit" class="btn btn-outline-warning rounded-pill btn-xs waves-effect waves-light" id="submitButton"><?= lang('Buttons.Submit') ?></button>
                    </div>
                    <?php if (isset($validation)) : ?>
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

    <div class="col-md-8">
        <div class="card settings-section mb-3">
            <div class="card-body">
                <h5 class="section-title"><i class="mdi mdi-format-list-bulleted"></i> <?= lang('UI_Text.Users') ?></h5>
                <table id="group-users-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr class="table-light">
                            <th>#</th>
                            <th><?= lang('UI_Text.Users') ?></th>
                            <th><?= lang('UI_Text.Delete') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        foreach ($group_users  as $users) {
                            $j = $j + 1; ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo  $users['name'] . ' ' . $users['last_name'];  ?></td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url('SCORM/Scorm_learn_group/del_courseuser') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="scgu_id" value="<?php echo $users['scgu_id'] ?>">
                                        <button type="submit" class="btn btn-outline-danger rounded-pill waves-effect btn-xs waves-light " onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')" title="<?= lang('UI_Text.Delete') ?>"><span class="mdi mdi-trash-can-outline"></span></button>
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
    $('#addcoursesForm').on('submit', function(event) {

        event.preventDefault();

        var dataString = new FormData($('#addcoursesForm')[0]);

        if (typeof FormData !== 'undefined') {

            $.ajax({
                url: '<?php echo base_url($form_link) ?>',
                type: "POST",
                data: dataString,
                async: false,
                processData: false,
                contentType: false,
                success: function(data) {

                    var obj = JSON.parse(data);

                    console.log(obj);

                    if (obj.status === 'OK') {
                        console.log('inside on condition');
                        //window.location.href = 'project_settings.php';
                        location.reload();


                    } else {

                        alert('error', 'Something Went Wrong! Please contact Site Admin!');
                    }

                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log('request failed');
                }
            })
        } else {
            message("Your Browser Don't support FormData API! Use IE 10 or Above!");
        }

    });

    $(document).ready(function() {
        $('#group-users-datatable').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
            columnDefs: [{
                orderable: false,
                searchable: false,
                targets: [0, 2]
            }],
            language: {
                search: '_INPUT_',
                searchPlaceholder: '<?= esc(lang('UI_Text.Search_Users'), 'js') ?>',
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