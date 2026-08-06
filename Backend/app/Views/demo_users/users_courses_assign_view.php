<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li>
                <form class="form-horizontal" id="form" action="<?php echo base_url('Demo/demo_users') ?>" method="POST"><?= csrf_field() ?>
                    <input type="hidden" name="id_c" value="<?php echo $client_id ?>">
                    <a href="javascript: submit()">Users List</a>
                </form>
            </li><b>&nbsp;>&nbsp;</b>

   
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="x_panel">
            <form class="form" id="addcoursesForm"><?= csrf_field() ?>
                <div class="col-md-4">
                    <select class="select2" multiple="multiple" tabindex="-1" style="width:100%" name="course_id[]" required="">
                        <?php foreach ($getAllCoursesForClient as $courses) {
                            // $key = array_search($courses['scourse_id'], array_column($assigned_courses,'course_id'));
                            // if (!empty($key) || $key === 0) {
                            // } else {
                            echo '<option value="' . $courses['course_id'] . '">' . $courses['course_name'] . '</option>';
                            // }
                        } ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="hidden" name="id_user" value="<?php echo $id_user ?>">
                    <button type="submit" class="btn btn-warning btn-sm form-control">
                        <i class="ace-icon fa fa-key bigger-110"></i> Add Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="x_panel">
            <div class="block block-drop-shadow">
                <div class="content">
                    <table id="dynamic-table" class="table table-sm  table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Course Name</th>
                                <th>Attempts</th>
                                <th>Status</th>
                                <th>Score</th>
                                <th>Access</th>
                                <th>Del</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                            foreach ($getUserlatestCourseUsers as $eachAllCoursesForUsers) {
                                //  print_r($eachAllCoursesForUsers);
                                $j = $j + 1; ?>
                                <tr>
                                    <td><?php echo $j ?></td>
                                    <td><?php echo $eachAllCoursesForUsers['course_name'] ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url('demo_users/deleteuserscoursedetails') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="status" value="0">
                                            <input type="hidden" name="user_assign_id" value="<?php echo $eachAllCoursesForUsers['user_assign_id'] ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')"><span class="mdi mdi-trash-can-outline"></span></button>
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
</div>
<script>
    $('#addcoursesForm').on('submit', function(event) {

        event.preventDefault();

        var dataString = new FormData($('#addcoursesForm')[0]);

        if (typeof FormData !== 'undefined') {

            $.ajax({
                url: '<?php echo base_url('demo_users/add_course_to_user') ?>',
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
<script>
    function submit() {
        form.submit();

    }
</script>