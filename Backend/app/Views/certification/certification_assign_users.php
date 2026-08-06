<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Certification/certification_dashboard/certification_view') ?>"><?=  lang('UI_Text.Back'); ?></a></li>
                </ol>
            </div>
            <h4 class="page-title"><?= lang('UI_Text.Add_users_to_Certification') ?></h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">

                <!-- MAIN ROW -->
                <div class="row">

                    <!-- Assign Users -->
                    <div class="col-lg-12">
                        <form class="row align-items-end" id="addusersForm"><?= csrf_field() ?>
                            <?= csrf_field() ?>
                            <input type="hidden" name="scenario" value="0">

                            <div class="col-xl-6 col-md-8 mt-1">
                                <label><?= lang('UI_Text.Select_Users') ?></label>
                                <select class="form-select select2-multiple"
                                    data-toggle="select2"
                                    data-width="100%"
                                    multiple
                                    name="userid[]"
                                    required>
                                    <?php foreach ($getUserclientlist as $users) {
                                        $key = array_search($users['id_user'], array_column($get_assigned_users, 'user_id'));
                                        if (!empty($key) || $key === 0) {
                                            // print_r($courses['scourse_id']);
                                        } else { ?>
                                            <option value="<?= $users['id_user']; ?>">
                                                <?= $users['name'] . ' ' . $users['last_name']; ?>
                                            </option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>

                            <div class="col-xl-4 col-md-4 mt-3">
                                <input type="hidden" name="certificate_id" value="<?= $certificate_id ?>">
                                <input type="hidden" name="type" value="<?= $type ?>">
                                <button type="submit"
                                    class="btn btn-outline-primary waves-effect btn-sm waves-light btn-sm w-100"
                                    id="submitButton">
                                    <?= lang('Buttons.Add_User') ?>
                                </button>
                            </div>
                        </form>


                        <?php if (count($usergroupdata) > 0) { ?>

                            <!-- Assign Groups -->
                            <!-- <form class="row align-items-end" id="addusersgroupForm"><?= csrf_field() ?>
                                <div class="col-xl-6 col-md-8 mt-1">
                                    <label>Select User Group</label>

                                    <select name="group_id" class="form-control">
                                        <?php foreach ($usergroupdata as $group) { ?>
                                            <option value="<?= $group['sc_cgid']; ?>">
                                                <?= $group['description']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-xl-4 col-md-4 mt-3">
                                    <input type="hidden" name="certificate_id" value="<?= $certificate_id ?>">
                                    <input type="hidden" name="type" value="<?= $type ?>">
                                    <button type="submit"
                                        class="btn btn-outline-primary waves-effect btn-sm waves-light btn-sm w-100">
                                        Assign User Group
                                    </button>
                                </div>
                            </form> -->
                        <?php } ?>
                    </div>



                </div><!-- /.row -->

            </div>
        </div>
    </div>






    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?= lang('UI_Text.User') ?></th>
                            <!-- <th>Due Date</th> -->
                            <th><?= lang('UI_Text.Action') ?></th>
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
                                    <td>
                                        <form class="form-horizontal"
                                            action="<?php echo base_url('Certification/Dashboard/Un_Assign_certificate_user') ?>"
                                            method="POST"><?= csrf_field() ?>
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="certificate_id" value="<?php echo $certificate_id; ?>">
                                            <input type="hidden" name="user_id" value="<?php echo $users['user_id']; ?>">
                                            <input type="hidden" name="status" value="0">
                                            <button class="btn btn-outline-danger waves-effect btn-xs waves-light"><span class="mdi mdi-stop-circle"></span>
                                                <?= lang('Buttons.Un_Enroll') ?></button>
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
    document.getElementById('addusersForm').addEventListener('submit', function() {
        var button = document.getElementById('submitButton');
        button.disabled = true;
        button.innerHTML = 'Submitting...';
    });
</script>
<script>
    $('#addusersForm').on('submit', function(event) {

        event.preventDefault();

        var dataString = new FormData($('#addusersForm')[0]);

        if (typeof FormData !== 'undefined') {

            $.ajax({
                url: '<?php echo base_url('Certification/Dashboard/Assign_user_to_certification') ?>',
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
                        alert('User enrolled successfully!');

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
                        alert('Users enrolled successfully!');
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
</script>
<script>
    function submit() {
        form.submit();

    }
</script>