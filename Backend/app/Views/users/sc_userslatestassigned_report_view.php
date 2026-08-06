<link href="<?php echo base_url(); ?>public/creative/assets/libs/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>public/creative/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>public/creative/assets/libs/selectize/css/selectize.bootstrap3.css" rel="stylesheet" type="text/css" />
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header_link) ?>">My Courses</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url("my_training/read_more") ?>">Course Detail</a></li>
                  
                </ol>
            </div>
            <h4 class="page-title">User Report : <?php echo $coursename[0]['course_name'] ?></h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">

        <div class="card">
            <div class="card-body">
                <form class="row" action="<?php echo base_url('XAPI/XAPI_courses/searchuserallcoursedetails'); ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-1">
                            <label class="form-label">Users</label>
                        </div>
                        <div class="col-lg-3">
                            <select class="form-select" name="userid" required="">
                                <?php foreach ($getUserclientlist as $users) {
                                    echo '<option value="' . $users['id_user'] . '">' . $users['name'] . ' ' . $users['last_name'] . '</option>';
                                } ?>
                            </select>
                        </div>

                        <div class="col-lg-3">
                            <input type="hidden" name="course_id" value="<?php echo $scourse_id ?>">
                            <button type="submit" class="btn btn-outline-primary btn-block btn-sm form-control">
                                View Detailed Report
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
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
                                <td><?php echo $eachAllCoursesForUsers['username'];
                                    if (count($scenarios) > 0) {
                                        $resultx =  array_search($eachAllCoursesForUsers['scenario_id_imp'], array_column($scenarios, 'xs'));
                                        if (is_numeric($resultx)) {
                                            echo ' {' . $scenarios[$resultx]['scenario_name'] . '}';
                                        }
                                    }
                                    ?></td>
                                <td><?php echo $eachAllCoursesForUsers['attempt'] ?></td>
                                <td><?php $status = ($eachAllCoursesForUsers['lesson_status'] != '') ? $eachAllCoursesForUsers['lesson_status'] : 'Not Started';
                                    echo ucfirst($status); ?></td>
                                <td><?php echo $eachAllCoursesForUsers['raw'] ?></td>
                                <td><?php echo $totalTime; ?></td>
                                <?php if ($eachAllCoursesForUsers['lesson_status'] != '') { ?>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url($form_link) ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="user_assign_id" value="<?php echo $eachAllCoursesForUsers['suser_assign_id'] ?>">
                                            <input type="hidden" name="tempusername" value="<?php echo $eachAllCoursesForUsers['username'] ?>">
                                            <input type="hidden" name="course_name" value="<?php echo $eachAllCoursesForUsers['course_name'] ?>">
                                            <input type="hidden" name="scenario_name" value="<?php echo (count($scenarios) > 0) ? $scenarios[$resultx]['scenario_name'] : '' ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><i class="mdi mdi-eye"></i></button>
                                        </form>
                                    </td>
                                <?php
                                } else { ?>
                                    <td>

                                    </td>
                                <?php } ?>
                                <td>
                                    <?php if ($eachAllCoursesForUsers['enrollstatus'] == 1) { ?>
                                        <form class="form-horizontal" action="<?php echo base_url($form_link1) ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="user_assign_id" value="<?php echo $eachAllCoursesForUsers['suser_assign_id'] ?>">
                                            <button type="submit" onclick="return confirm('Are you sure !! You will not be able to view the records again?')" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-stop-circle"></span></button>
                                        </form>
                                    <?php } else { ?>
                                        <form class="form-horizontal" action="<?php echo base_url($delete_enrollment) ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="user_assign_id" value="<?php echo $eachAllCoursesForUsers['suser_assign_id'] ?>">
                                            <button type="submit" onclick="return confirm('Are you sure !! You will not be able to view the records again?')" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-trash-can-outline"></span></button>
                                        </form>
                                    <?php } ?>
                                </td>
                                <?php echo '<td class="project-actions text-center">';
                                if ($eachAllCoursesForUsers['grace'] == 0) {
                                    echo '<form action="' . base_url() . 'User_login/client_users/give_grace"  method="POST"><?= csrf_field() ?>';
                                    echo '<input type="hidden" name="user_assign_id" value="' .  $eachAllCoursesForUsers['suser_assign_id'] . '"/>';
                                    echo '<input type="hidden" name="course_id" value="' . $eachAllCoursesForUsers['course_id'] . '"/>';
                                    echo '<button class=btn btn-soft-success btn-sm waves-effect waves-light" type="submit">';
                                    echo '<span class="fa fa-arrow-up"></span>';
                                    echo '</button>';
                                    echo '</form></td>';
                                } else {
                                    echo 'Grace Applied';
                                }
                                echo '</td>' ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div> -->
<script>
    $('#addusersForm').on('submit', function(event) {

        event.preventDefault();

        var dataString = new FormData($('#addusersForm')[0]);

        if (typeof FormData !== 'undefined') {

            $.ajax({
                url: '<?php echo base_url('SCORM/scorm_users/add_user_to_course') ?>',
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