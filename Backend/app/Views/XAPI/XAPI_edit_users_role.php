<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url($course_header_link) ?>"><?php echo $course_header ?></a>
            </li><b> &nbsp;>&nbsp;</b>
            <?php session()->set('scourse_id',  $scourse_id);
            session()->set('course_name',  $course_name);
            session()->set('xs',  $xs); ?>
            <li> <a href="<?php echo base_url($header_link) ?>"><?php echo $header; ?></a>
            </li><b> &nbsp;>&nbsp;</b>
            <li class="active"><?php echo $header2; ?></li>
        </ol>
    </div>
</div>
<div class="col-sm-6">
    <div class="x_panel">
        <h6>Edit User Role</h6>
        <div class="x_title">
            <div class="x_content">
                <br />
                <div class="block block-drop-shadow">
                    <div class="content controls">
                        <form action="<?php echo base_url($edit_role) ?>" method="POST"><?= csrf_field() ?>
                            <div class="form-row">
                                <label>User Name</label>
                                <select class="form-select" name="user_id" disabled>
                                    <option value="<?php echo $getScenarioUsers[0]['user_id'] ?>"><?php echo $getScenarioUsers[0]['fullname'] ?></option>
                                </select>
                            </div><br>
                            <div class="form-row">
                                <label>Roles</label>
                                <select class="form-select" name="role">
                                    <option value="1" <?php if ($getScenarioUsers[0]['role'] == 1) echo "SELECTED"; ?>>User</option>
                                    <option value="2" <?php if ($getScenarioUsers[0]['role'] == 2) echo "SELECTED"; ?>>Instructor</option>
                                </select>
                            </div><br>
                            <div class="form-row">
                                <input type="hidden" name="user_assign_id" value="<?php echo $user_assign_id ?>" required="" />
                                <input type="hidden" name="xs" value="<?php echo $xs ?>" required="" />
                                <input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>" required="" />
                                <input type="hidden" name="course_name" value="<?php echo $course_name ?>" required="" />
                                <button type="submit" class="btn btn-sm btn-primary"><i class="icon-key"> </i>Save</button>
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
        </div>
    </div>
</div>
<script>
    $('#assignusersForm').on('submit', function(event) {

        event.preventDefault();

        var dataString = new FormData($('#assignusersForm')[0]);

        if (typeof FormData !== 'undefined') {

            $.ajax({
                url: '<?php echo base_url('XAPI/XAPI_scenarios/updateUserRole') ?>',
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
</script>