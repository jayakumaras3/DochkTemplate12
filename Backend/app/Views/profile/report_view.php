<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header_link); ?>"><?php echo $header; ?></a></li>
         
                </ol>
            </div>
            <h4 class="page-title"><?php echo $sub_header_1; ?></h4>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <p class="text-muted font-13 mb-4"></p>
            <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Course Name</th>
                        <th>Attempts</th>
                        <th>Status</th>
                        <th>Score</th>
                        <th>Total Time</th>
                        <th>Last Accessed</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $j = 0;

                    foreach ($getAllCoursesForUsers as $eachAllCoursesForUsers) {
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
                            <td><?php echo $eachAllCoursesForUsers['course_name']  ?></td>
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
                            <td><?php echo ($eachAllCoursesForUsers['last_active'] != '') ? date('m-d-Y h:i:s', $eachAllCoursesForUsers['last_active']) : ''; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $('#addcoursesForm').on('submit', function(event) {

        event.preventDefault();

        var dataString = new FormData($('#addcoursesForm')[0]);

        if (typeof FormData !== 'undefined') {

            $.ajax({
                url: '<?php echo base_url('scorm_users/add_course_to_user') ?>',
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
<script>
    function submit() {
        form.submit();

    }
</script>