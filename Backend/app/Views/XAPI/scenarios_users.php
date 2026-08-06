<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url($course_header_link) ?>"><?php echo $course_header ?></a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header_link) ?>"><?php echo $header ?></a></li>
                </ol>
            </div>
            <h4 class="page-title"><?php echo $header2; ?></h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
            <form class="form" id="addusersForm"><?= csrf_field() ?>
                <?= csrf_field() ?>
                <div class="mb-3">
                    <select class="select2" multiple="multiple" tabindex="-1" style="width:100%" name="userid[]" required="">
                        <?php
                        foreach ($getUserclientlist as $users) {
                            $key = array_search($users['id_user'], array_column($getUserlatestCourse, 'id_user'));
                            if (!empty($key) || $key === 0) {
                            } else {
                                echo '<option value="' . $users['id_user'] . '">' . $users['name'] . '</option>';
                            }
                        } ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="hidden" name="scenario_id" value="<?php echo $xs ?>">
                    <input type="hidden" name="course_id" value="<?php echo $scourse_id ?>">
                    <button type="submit" class="btn btn-warning btn-sm form-control">
                        <i class="ace-icon fa fa-key bigger-110"></i> Add User
                    </button>
                </div>
                <div class="col-md-6">
                    <span style="color:red; font-size:11px;">* If user is not visible in the list, user may have been assigned to another Scenario. <br>Please remove from the other scenario to assign here. </span>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <p class="text-muted font-13 mb-4"></p>
                <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Attempts</th>
                            <th>Status</th>
                            <th>Score</th>
                            <th>Total Time</th>
                            <th>Details</th>
                            <th>Un Enroll</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $j = 0;
                        foreach ($getUserlatestclientCourseByScenario as $eachAllCoursesForUsers) {
                            // echo '<pre>';
                            // print_r($eachAllCoursesForUsers);
                            // exit();
                            $totalTime = '00:00:00';
                            $trimmedsessionTime = '00:00:00';
                            $splitotalTime = '00:00:00';
                            $j = $j + 1;
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
                                <td><?php echo $eachAllCoursesForUsers['username'] ?></td>
                                <td><?php echo $eachAllCoursesForUsers['attempt'] ?></td>
                                <td><?php echo ($eachAllCoursesForUsers['lesson_status'] != '') ? $eachAllCoursesForUsers['lesson_status'] : 'Not started' ?></td>
                                <td><?php echo $eachAllCoursesForUsers['raw'] ?></td>
                                <td><?php echo $totalTime; ?></td>
                                <?php if ($eachAllCoursesForUsers['lesson_status'] != '') { ?>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url($form_link) ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="user_assign_id" value="<?php echo $eachAllCoursesForUsers['suser_assign_id'] ?>">
                                            <input type="hidden" name="tempusername" value="<?php echo $eachAllCoursesForUsers['username'] ?>">
                                            <button type="submit" class="btn btn-sm widget-icon btn-info"><span class="fa fa-eye"></span></button>
                                        </form>
                                    </td>
                                <?php
                                } else { ?>
                                    <td>

                                    </td>
                                <?php } ?>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url($form_link1) ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="user_assign_id" value="<?php echo $eachAllCoursesForUsers['suser_assign_id'] ?>">
                                        <button type="submit" onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')" class="btn btn-sm widget-icon btn-warning"><span class="fa fa-ban"></span></button>
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
    $('#addusersForm').on('submit', function(event) {

        event.preventDefault();

        var dataString = new FormData($('#addusersForm')[0]);

        if (typeof FormData !== 'undefined') {

            $.ajax({
                url: '<?php echo base_url('XAPI/XAPI_scenarios/add_user_to_course_scenario') ?>',
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