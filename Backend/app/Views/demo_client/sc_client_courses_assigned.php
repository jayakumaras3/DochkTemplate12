<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('demo_client'); ?>">Demo Clients</a></li><b>&nbsp;>&nbsp;</b>
          
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-sm-8">
        <div class="x_panel">
            <div class="block block-drop-shadow">
                <div class="content">
                    <table id="dynamic-table" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Course Name</th>
                                <th>Group</th>
                                <th width="20%">Dispatch</th>
                                <th width="10%">Del</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                           foreach ($getAllCoursesForClient as $courses) {
                                $j = $j + 1;
                            ?>
                                <tr>
                                    <td><?php echo $j;  ?></td>
                                    <td><?php echo $courses['course_name']; ?></td>
                                    <td><?php echo $courses['group_name']; ?></td>
                                    <td></td>
                                    <td width="10%">
                                        <form class="form-horizontal" action="<?php echo base_url('demo_client/delete_assigned_client_course') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="sc_cr_as_id" value="<?php echo  $courses['sc_cr_as_id']; ?>">
                                            <input type="hidden" name="client_id" value="<?php echo $client_id; ?>" />
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')"><span class="mdi mdi-trash-can-outline"></span></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php }
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="col-sm-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="x_content">
                        <br />
                        <div class="block block-drop-shadow">
                            <div class="content controls">
                                <form class="form-horizontal1" id="addcoursesForm"><?= csrf_field() ?>
                                    <div class="form-row">
                                        <select class="select2" multiple="multiple" tabindex="-1" style="width:100%" name="course_id[]" required="">
                                            <?php foreach ($all_courses as $courses) {
                                                $key = array_search($courses['scourse_id'], array_column($getAllCoursesForClient, 'course_id'));
                                                if (!empty($key) || $key === 0) {
                                                    // print_r($courses['scourse_id']);
                                                } else {
                                                    echo '<option value="' . $courses['scourse_id'] . '">' . $courses['course_name'] . '</option>';
                                                }
                                            } ?>
                                        </select>
                                    </div><br>
                                    <div class="form-row">
                                        <input type="hidden" name="client_id" value="<?php echo $client_id; ?>" />
                                        <button type="submit" class="btn btn-sm btn-warning">Add Course</button>
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

        <div class="col-sm-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="x_content">
                        <br />
                        <div class="block block-drop-shadow">
                            <div class="content controls">
                                <form action="<?php echo base_url('demo_client/add_group_to_client') ?>" method="post" autocomplete="off"><?= csrf_field() ?>
                                    <div class="form-row">
                                        <select name="group_id" class="form-control">
                                            <?php
                                            foreach ($coursegroupdata  as $eachcoursegroupdata) {
                                                echo '<option value="' . $eachcoursegroupdata['sc_cgid'] . '">' . $eachcoursegroupdata['description'] . ' (' . $eachcoursegroupdata['assign_id_count'] . ')' . '</option>';
                                            }
                                            ?>
                                        </select>

                                    </div><br>
                                    <div class="form-row">
                                        <input type="hidden" name="client_id" value="<?php echo $client_id; ?>" />
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('<?php echo lang('Alert.Aler_005') ?>')">Add Group</button>
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
    </div>
</div>
<script>
    $('#addcoursesForm').on('submit', function(event) {

        event.preventDefault();

        var dataString = new FormData($('#addcoursesForm')[0]);

        if (typeof FormData !== 'undefined') {

            $.ajax({
                url: '<?php echo base_url('demo_client/add_course_to_client') ?>',
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
        $('#dynamic-table').DataTable();
    });
</script>