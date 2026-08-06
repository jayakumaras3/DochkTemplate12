<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('User_login/client_users/course_report/' . base64_encode($id_user)); ?>">Report View</a></li><b>&nbsp;>&nbsp;</b>
           
        </ol>
    </div>
</div>
<style>
    table {
        width: 100%;
    }

    td {
        max-width: 0;
        overflow: visible;
        white-space: nowrap;
    }

    th.columnA {
        width: 30%;
    }

    td.columnB {
        width: 70%;

    }
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="x_panel">
            <div class="block block-drop-shadow">
                <div class="content">
                    <table class="table  table-sm table-bordered table-striped" style="width:100%">
                        <tr>
                            <th class="columnA">Element</th>
                            <th class="columnB">Value</th>
                        </tr>
                        <tr>
                            <th class="columnA">Learner name</th>
                            <td class="columnB"><?php echo $getScormuserdetails[0]['student_name'] ?></td>
                        </tr>
                        <tr>
                            <th class="columnA">Lesson location</th>
                            <td class="columnB"><?php echo $getScormuserdetails[0]['lesson_location'] ?></td>
                        </tr>
                        <tr>
                            <th class="columnA">Lesson status</th>
                            <td class="columnB"><?php echo $getScormuserdetails[0]['lesson_status'] ?></td>
                        </tr>
                        <tr>
                            <th class="columnA">Score</th>
                            <td class="columnB"><?php echo $getScormuserdetails[0]['raw'] ?></td>
                        </tr>
                        <tr>
                            <th class="columnA">Score max</th>
                            <td class="columnB"><?php echo $getScormuserdetails[0]['max'] ?></td>
                        </tr>
                        <tr>
                            <th class="columnA">Score min</th>
                            <td class="columnB"><?php echo $getScormuserdetails[0]['min'] ?></td>
                        </tr>
                        <tr>
                            <th class="columnA">Total time</th>
                            <td class="columnB"><?php echo $getScormuserdetails[0]['total_time'] ?></td>
                        </tr>
                        <tr>
                            <th class="columnA">Lesson mode</th>
                            <td class="columnB"><?php echo $getScormuserdetails[0]['lesson_mode'] ?></td>
                        </tr>
                        <tr>
                            <th class="columnA">Session time</th>
                            <td class="columnB"><?php echo $getScormuserdetails[0]['session_time'] ?></td>
                        </tr>
                        <tr>
                            <th class="columnA">Attempt</th>
                            <td class="columnB"><?php echo $getScormuserdetails[0]['attempt'] ?></td>
                        </tr>
                        <tr>
                            <th class="columnA">Last active</th>
                            <td class="columnB"><?php echo $getScormuserdetails[0]['last_active'] ?></td>
                        </tr>
                        <tr>
                            <th class="columnA">Suspend data</th>
                            <td class="columnB"><?php echo $getScormuserdetails[0]['suspend_data'] ?></td>
                        </tr>
                        <tr>
                            <th class="columnA">Completion date</th>
                            <td class="columnB"><?php echo ($getScormuserdetails[0]['completion_date'] != 0) ? date('m-d-Y', $getScormuserdetails[0]['completion_date']) : '';  ?></td>
                        </tr>
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