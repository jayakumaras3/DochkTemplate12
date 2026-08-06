<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url($header_link); ?>"><?php echo $header; ?></a></li><b>&nbsp;>&nbsp;</b>
            <li>
                <form class="form-horizontal" id="form" action="<?php echo base_url($sub_header_1_link) ?>" method="POST"><?= csrf_field() ?>
                    <input type="hidden" name="id_c" value="<?php echo $client_id ?>">
                    <a href="javascript: submit()"><?php echo $sub_header_1; ?></a>
                </form>
            </li><b>&nbsp;>&nbsp;</b>
            <li class="active"><?php echo $sub_header_2; ?> - <?php echo $username[0]['name']; ?></li>
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
                            $key = array_search($courses['course_name'], array_column($getAllCoursesForUsers, 'course_name'));
                            if (!empty($key) || $key === 0) {
                            } else {
                                echo '<option value="' . $courses['course_id'] . '">' . $courses['course_name'] . '</option>';
                            }
                        } ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                    <button type="submit" class="btn btn-primary btn-sm form-control">
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
                    <table id="dynamic-table" class="table  table-sm table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Course Name</th>
                                <th>Attempts</th>
                                <th>Status</th>
                                <th>Score</th>
                                <th>Total Time</th>
                                <th>Last Access</th>
                                <th>Del</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                            // print_r($getAllCoursesForUsers);
                            foreach ($getAllCoursesForUsers as $eachAllCoursesForUsers) {
                                $j = $j + 1;
                                $totalTime = '00:00:00';
                                $trimmedsessionTime = '00:00:00';
                                $splitotalTime = '00:00:00';

                                if (strlen($eachAllCoursesForUsers['session_time']) > 4) {
                                    if ($eachAllCoursesForUsers['total_time'] == '' || $eachAllCoursesForUsers['total_time'] == '00:00:00.00') {
                                        $splitotalTime = '00:00:00';
                                    } else {
                                        $splitotalTime = explode('.', $eachAllCoursesForUsers['total_time'])[0];
                                    }
                                    if (strlen($eachAllCoursesForUsers['session_time']) > 8) {
                                        $splitsession_time = explode('.', $eachAllCoursesForUsers['session_time']);
                                        $trimmedsessionTime = substr($splitsession_time[0], 2);
                                    }
                                    if (strlen($eachAllCoursesForUsers['session_time']) == 8) {
                                        $trimmedsessionTime = explode('.', $eachAllCoursesForUsers['session_time'])[0];
                                    }
                                    $matches0 = explode(':', $splitotalTime); // split up the string
                                    $matches1 = explode(':', $trimmedsessionTime);
                                    $sec0 = $matches0[0] * 60 * 60 + $matches0[1] * 60 + $matches0[2];
                                    $sec1 = $sec0 + $matches1[0] * 3600 + $matches1[1] * 60 + $matches1[2]; // get total seconds
                                    $h = intval(($sec1) / 3600);
                                    $m = intval(($sec1 - $h * 3600) / 60);
                                    $s = $sec1 - $h * 3600 - $m * 60;
                                    $totalTime = str_pad($h, 2, '0', STR_PAD_LEFT) . ':' . str_pad($m, 2, '0', STR_PAD_LEFT) . ':' . str_pad($s, 2, '0', STR_PAD_LEFT);
                                }


                            ?>
                                <tr>
                                    <td><?php echo $j ?></td>
                                    <td><?php echo $eachAllCoursesForUsers['course_name'] ?></td>
                                    <td><?php echo $eachAllCoursesForUsers['attempt'] ?></td>
                                    <td><?php
                                        if (strlen($eachAllCoursesForUsers['lesson_status']) > 2) {
                                            echo $eachAllCoursesForUsers['lesson_status'];
                                        } else {
                                            echo 'Not Started';
                                        }
                                        ?></td>
                                    <td><?php echo $eachAllCoursesForUsers['raw'] ?></td>
                                    <td><?php
                                        if ($totalTime == '00:00:00') {
                                        } else {
                                            echo $totalTime;
                                        }
                                        ?></td>
                                    <td><?php echo ($eachAllCoursesForUsers['last_active'] != '') ? $eachAllCoursesForUsers['last_active'] : ''; ?></td>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url($form_link1) ?>" method="POST"><?= csrf_field() ?>
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