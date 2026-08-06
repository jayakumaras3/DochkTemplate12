<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('User_login/client_users'); ?>">Users</a></li><b>&nbsp;>&nbsp;</b>
         
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="x_panel">
            <div class="block block-drop-shadow">
                <div class="content">
                    <table id="dynamic-table" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Course Name</th>
                                <th>Attempts</th>
                                <th>Status</th>
                                <th>Score</th>
                                <th>Total Time</th>
                                <th>Last Active</th>
                                <th>Details</th>
                                <th>Del</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                            foreach ($getAllCoursesForUsers as $eachAllCoursesForUsers) {
                                $j = $j + 1;
                            ?>
                                <tr>
                                    <td><?php echo $j ?></td>
                                    <td><?php echo $eachAllCoursesForUsers['course_name'] ?></td>
                                    <td><?php echo $eachAllCoursesForUsers['attempt'] ?></td>
                                    <td><?php echo Ucfirst($eachAllCoursesForUsers['lesson_status']); ?></td>
                                    <td><?php echo $eachAllCoursesForUsers['raw'] ?></td>
                                    <td><?php echo ($eachAllCoursesForUsers['total_time'] != '') ? $eachAllCoursesForUsers['total_time'] : '';  ?></td>
                                    <td><?php echo ($eachAllCoursesForUsers['last_active'] != '') ? $eachAllCoursesForUsers['last_active'] : ''; ?></td>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url('User_login/client_users/getscormuserdetails') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="sc_uid" value="<?php echo $eachAllCoursesForUsers['sc_uid'] ?>">
                                            <input type="hidden" name="id_user" value="<?php echo  $id_user ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-eye"></span></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url('User_login/client_users/deletecourseuserdetails') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="sc_uid" value="<?php echo $eachAllCoursesForUsers['sc_uid'] ?>">
                                            <input type="hidden" name="id_user" value="<?php echo $id_user ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-trash-can-outline"></span></button>
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
                url: '<?php echo base_url('SCORM/scorm_users/add_course_to_user') ?>',
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