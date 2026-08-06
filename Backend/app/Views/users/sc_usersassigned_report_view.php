<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header_link) ?>"><?php echo $header_link_name ?></a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header_sub_link) ?>">Course Report</a></li>
                
                </ol>
            </div>
            <h4 class="page-title">All Attempt Course View - <?php echo $coursename[0]['course_name'] ?></h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="pull-right">
        <a href="<?php echo base_url($delete_enrollment . '?course_id=' . $getAllUsersForCourses['0']['course_id'] . '&id_user=' . $getAllUsersForCourses['0']['student_id']); ?>"><button class="btn btn-sm widget-icon btn-danger">Un Enroll</button></a>
    </div>
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
                            <!-- <th>Details</th> -->
                            <th>Del</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $j = 0;
                        foreach ($getAllUsersForCourses as $eachAllCoursesForUsers) {

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
                                <td><?php echo $eachAllCoursesForUsers['student_name'] ?></td>
                                <td><?php echo $eachAllCoursesForUsers['attempt'] ?></td>
                                <td><?php echo ($eachAllCoursesForUsers['lesson_status'] != '') ? $eachAllCoursesForUsers['lesson_status'] : 'Not started' ?></td>
                                <td><?php echo $eachAllCoursesForUsers['raw'] ?></td>
                                <td><?php echo $totalTime; ?></td>
                                <?php if ($j == count($getAllUsersForCourses)) { ?>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url($form_link1) ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="sc_uid" value="<?php echo $eachAllCoursesForUsers['sc_uid'] ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-trash-can-outline"></span></button>
                                        </form>
                                    </td>
                                <?php } else { ?>
                                    <td></td>
                                <?php  } ?>
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