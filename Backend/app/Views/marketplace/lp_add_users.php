<style>
    /* Same rounded-corner + shadow + table look as SCORM/scorm_courses (courses_search_view.php). */
    .lp-users-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .lp-users-card table thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .lp-users-card table thead th {
        border-bottom-color: #36404a;
        color: #cedeef;
    }

    .lp-users-card table tbody td {
        vertical-align: middle;
    }

    .lp-users-card .dataTables_length select {
        border-radius: .5rem;
        border: 1px solid #dee2e6;
        padding: .25rem 1.75rem .25rem .6rem;
    }

    .lp-users-card .dataTables_filter input {
        border-radius: 2rem;
        border: 1px solid #dee2e6;
        padding: .4rem .75rem;
        min-width: 260px;
    }

    [data-bs-theme="dark"] .lp-users-card .dataTables_length select,
    [data-bs-theme="dark"] .lp-users-card .dataTables_filter input {
        border-color: #424e5a;
    }

    /* Square pagination buttons, matching SCORM/Scorm_learn_group. */
    .lp-users-card .pagination .page-link {
        border-radius: 0;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header_link) ?>"><?php echo lang('UI_Text.Learning_Plan') ?></a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('marketplace/Learning_dashboard/learning_courses') ?>"><?php echo lang('UI_Text.Learning_Plan_Details') ?></a></li>

                </ol>
            </div>
            <h4 class="page-title">Assign Users<?php echo !empty($row['mp_name']) ? ' - ' . esc($row['mp_name']) : ''; ?></h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card lp-users-card">
            <div class="card-body">

                <!-- MAIN ROW -->
                <div class="row">

                    <!-- Assign Users -->
                    <div class="col-lg-12">
                        <form class="row align-items-end" id="addusersForm"><?= csrf_field() ?>
                            <input type="hidden" name="scenario" value="0">

                            <div class="col-xl-6 col-md-8 mt-1">
                                <label><?php echo lang('UI_Text.Select_Users') ?></label>
                                <select class="form-control select2-multiple" data-toggle="select2" data-width="100%" multiple="multiple"
                                    name="userid[]"
                                    required>
                                    <?php foreach ($getUserclientlist as $users) {
                                        $key = array_search($users['id_user'], array_column($get_assigned_users, 'user_id'));
                                        if (!empty($key) || $key === 0) {
                                        } else { ?>
                                            <option value="<?= $users['id_user']; ?>">
                                                <?= $users['name'] . ' ' . $users['last_name']; ?>
                                            </option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>

                            <div class="col-xl-4 col-md-4 mt-3">
                                <input type="hidden" name="mp_id" value="<?= $mp_id ?>">
                                <input type="hidden" name="type" value="<?= $type ?>">
                                <button type="submit" class="btn btn-outline-success rounded-pill waves-effect btn-sm waves-light w-100" id="submitButton">
                                    <?php echo lang('Buttons.Assign_Users') ?>
                                </button>
                            </div>
                        </form>


                        <?php if (count($usergroupdata) > 0) { ?>

                            <!-- Assign Groups -->
                            <form class="row align-items-end" id="addusersgroupForm"><?= csrf_field() ?>
                                <div class="col-xl-6 col-md-8 mt-1">
                                    <label><?php echo lang('UI_Text.Select_User_Group') ?></label>

                                    <select name="group_id" class="form-control">
                                        <?php foreach ($usergroupdata as $group) { ?>
                                            <option value="<?= $group['sc_cgid']; ?>">
                                                <?= $group['description']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-xl-4 col-md-4 mt-3">
                                    <input type="hidden" name="mp_id" value="<?= $mp_id ?>">
                                    <input type="hidden" name="type" value="<?= $type ?>">
                                    <button type="submit" class="btn btn-outline-primary rounded-pill waves-effect btn-sm waves-light w-100" id="submitBtn">
                                        <?php echo lang('Buttons.Assign_User_Group') ?>
                                    </button>
                                </div>
                            </form>
                        <?php } ?>
                    </div>
                    <script>
                        document.getElementById('addusersgroupForm').addEventListener('submit', function() {

                            const btn = document.getElementById('submitBtn');

                            btn.disabled = true;
                            btn.innerHTML = <?php echo json_encode(lang('UI_Text.Processing')); ?>;

                        });
                    </script>


                </div><!-- /.row -->

            </div>
        </div>
    </div>






    <div class="col-lg-6">
        <div class="card lp-users-card">
            <div class="card-body">
                <table id="lp-users-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr class="table-light">
                            <th>#</th>
                            <th><?php echo lang('UI_Text.Users') ?></th>

                            <th><?php echo lang('UI_Text.Action') ?></th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if (!empty($get_assigned_users)) {
                            $j = 0;
                            foreach ($get_assigned_users as $users) {
                                $j = $j + 1; ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $users['first_name'] . ' ' . $users['last_name']; ?></td>
                                    <!-- <td><?php if ($users['type'] == 2) {
                                                    // echo 'Learning Plan';
                                                } elseif ($users['type'] == 4) {
                                                    // echo 'Certification';
                                                } else {
                                                } ?></td> -->
                                    <!-- <td><?php echo ($users['due_date'] != '0000-00-00') ? date('m-d-Y', strtotime($users['due_date'])) : ''; ?></td> -->
                                    <td>
                                        <form class="form-horizontal"
                                            action="<?php echo base_url('marketplace/Learning_dashboard/delete_users') ?>"
                                            method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="mp_id" value="<?php echo $mp_id; ?>">
                                            <input type="hidden" name="user_id" value="<?php echo $users['user_id']; ?>">
                                            <input type="hidden" name="status" value="0">
                                            <button class="btn btn-outline-danger rounded-pill waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_019'); ?>')">
                                                <?php echo lang('Buttons.Un_Enroll'); ?></button>
                                        </form>
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
        $('#lp-users-datatable').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
            columnDefs: [{
                orderable: false,
                targets: [2]
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
<script>
    document.getElementById('addusersForm').addEventListener('submit', function() {
        var button = document.getElementById('submitButton');
        button.disabled = true;
        button.innerHTML = <?php echo json_encode(lang('UI_Text.Submitting')); ?>;
    });
</script>
<script>
    $('#addusersForm').on('submit', function(event) {

        event.preventDefault();

        var dataString = new FormData($('#addusersForm')[0]);

        if (typeof FormData !== 'undefined') {

            $.ajax({
                url: '<?php echo base_url('marketplace/Learning_dashboard/add_users_to_learning_plan') ?>',
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
                        alert(<?php echo json_encode(lang('Messages.Success_0052')); ?>);

                        location.reload();


                    } else {

                        alert('error', <?php echo json_encode(lang('Messages.Error_0025')); ?>);
                    }

                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log('request failed');
                }
            })
        } else {
            message(<?php echo json_encode(lang('Messages.Error_0026')); ?>);
        }

    });


    $('#addusersgroupForm').on('submit', function(event) {

        event.preventDefault();

        var dataString = new FormData($('#addusersgroupForm')[0]);

        if (typeof FormData !== 'undefined') {

            $.ajax({
                url: '<?php echo base_url('marketplace/Learning_dashboard/add_usergroup_to_learning_plan') ?>',
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
                        alert(<?php echo json_encode(lang('Messages.Success_0053')); ?>);
                        location.reload();


                    } else {

                        alert('error', <?php echo json_encode(lang('Messages.Error_0025')); ?>);
                    }

                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log('request failed');
                }
            })
        } else {
            message(<?php echo json_encode(lang('Messages.Error_0026')); ?>);
        }

    });
</script>
<script>
    function submit() {
        form.submit();

    }
</script>