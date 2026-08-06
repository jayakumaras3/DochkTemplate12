<style>
    /* Same rounded-corner + shadow + table look as SCORM/scorm_courses (courses_search_view.php)
       and other redesigned pages this session. */
    .attempt-details-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .attempt-details-card table.dataTable thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .attempt-details-card table.dataTable thead th {
        border-bottom-color: #424e5a;
        color: #cedeef;
    }

    .attempt-details-card table.dataTable tbody td {
        vertical-align: middle;
    }

    .attempt-details-card .dataTables_length select {
        border-radius: .5rem;
        border: 1px solid #dee2e6;
        padding: .25rem 1.75rem .25rem .6rem;
    }

    .attempt-details-card .dataTables_filter input {
        border-radius: 2rem;
        border: 1px solid #dee2e6;
        padding: .4rem .75rem;
        min-width: 260px;
    }

    [data-bs-theme="dark"] .attempt-details-card .dataTables_length select,
    [data-bs-theme="dark"] .attempt-details-card .dataTables_filter input {
        border-color: #424e5a;
    }

    .attempt-details-card .pagination .page-link {
        border: none;
        margin: 0 2px;
        border-radius: 0;
        color: #6658dd;
    }

    .attempt-details-card .pagination .page-item.active .page-link {
        background-color: #6658dd;
        color: #fff;
    }

    .attempt-details-card .pagination .page-item.disabled .page-link {
        color: #ced4da;
        background: transparent;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('User_login/client_users'); ?>"><?php echo lang('UI_Text.User_Management'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('User_login/client_users/course_report/' . base64_encode($getAllCoursesForUsers['0']['id_user'])); ?>"><?php echo lang('UI_Text.User_Report'); ?> - <?php echo $username[0]['name']; ?></a></li>

                </ol>
            </div>
            <h4 class="page-title"><?php echo lang('UI_Text.Attempt_Details'); ?></h4>
        </div>
    </div>
</div>
<div class="row">

    <div class="col-lg-12">
        <div class="card attempt-details-card">
            <div class="card-body">
                <table id="attempt-details-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo lang('UI_Text.Course_Name'); ?></th>
                            <th><?php echo lang('UI_Text.Attempts'); ?></th>
                            <th><?php echo lang('UI_Text.Status'); ?></th>
                            <th><?php echo lang('UI_Text.Score'); ?></th>
                            <th><?php echo lang('UI_Text.Total_Time'); ?></th>
                            <th><?php echo lang('UI_Text.Details'); ?></th>
                            <th><?php echo lang('UI_Text.Action'); ?></th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        foreach ($getAllCoursesForUsers as $eachAllCoursesForUsers) {
                            // print_r($eachAllCoursesForUsers);
                            // exit();
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
                                <td><?php $statusval = ($eachAllCoursesForUsers['lesson_status'] != '') ? $eachAllCoursesForUsers['lesson_status'] : 'Not started';

                                      ?>
                                    <?php if ($statusval == 'completed' || $statusval == 'passed') { ?>
                                        <span class="badge bg-soft-success text-success p-1"><?php echo lang('UI_Text.Completed') ?></span>
                                    <?php  } elseif ($statusval == 'incomplete') { ?>
                                        <span class="badge bg-soft-info text-info p-1"><?php echo lang('UI_Text.In_Progress') ?></span>
                                    <?php } elseif ($statusval == 'not started') { ?>
                                        <span class="badge bg-soft-warning text-warning p-1"><?php echo lang('UI_Text.Not_Started') ?></span>
                                    <?php  } ?>


                                </td>
                                <td><?php echo $eachAllCoursesForUsers['raw'] ?></td>
                                <td><?php echo ($eachAllCoursesForUsers['total_time'] != '') ? $eachAllCoursesForUsers['total_time'] : '';  ?></td>
                                <?php if ($eachAllCoursesForUsers['type'] == 5) { ?>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url('XAPI/XAPI_users/viewManageuserDetailedReport') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="sc_uid" value="<?php echo $eachAllCoursesForUsers['sc_uid'] ?>">
                                            <input type="hidden" name="attempt" value="<?php echo $eachAllCoursesForUsers['attempt']  ?>">
                                            <input type="hidden" name="course_name" value="<?php echo $eachAllCoursesForUsers['course_name']  ?>">
                                            <input type="hidden" name="scenario_id" value="<?php echo $eachAllCoursesForUsers['scenario_id']  ?>">
                                            <input type="hidden" name="userid" value="<?php echo $eachAllCoursesForUsers['id_user']  ?>">
                                            <input type="hidden" name="username" value="<?php echo $username[0]['name'];  ?>">
                                            <button type="submit" class="btn btn-sm widget-icon btn-primary"><?php echo $eachAllCoursesForUsers['xapiscenariocount'] ?></button>
                                        </form>
                                    </td>
                                <?php } else { ?>
                                    <td></td>
                                <?php  }
                                if ($eachAllCoursesForUsers['type'] == 5) {  ?>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url($delete_enrollment) ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="sc_uid" value="<?php echo $eachAllCoursesForUsers['sc_uid'] ?>">
                                            <button type="submit" onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')" class="btn btn-outline-danger rounded-pill waves-effect btn-xs waves-light" title="<?= lang('Buttons.Delete') ?>"><span class="mdi mdi-trash-can-outline"></span></button>
                                        </form>
                                    </td>
                                <?php } else { ?>
                                    <?php if ($j == count($getAllCoursesForUsers)) { ?>


                                        <td>
                                            <form class="form-horizontal" action="<?php echo base_url('User_login/client_users/deletecourseuserdetails') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="sc_uid" value="<?php echo $eachAllCoursesForUsers['sc_uid'] ?>">
                                                <input type="hidden" name="id_user" value="<?php echo base64_encode($eachAllCoursesForUsers['student_id']) ?>">
                                                <button type="submit" onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')" class="btn btn-outline-danger rounded-pill waves-effect btn-xs waves-light"><span class="mdi mdi-trash-can-outline"></span> <?php echo lang('Buttons.Delete'); ?></button>
                                            </form>
                                        </td>
                                    <?php } else { ?>
                                        <td></td>
                                <?php  }
                                }
                                ?>
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
        $('#attempt-details-datatable').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
            language: {
                search: '_INPUT_',
                searchPlaceholder: '<?= esc(lang('UI_Text.Search_Courses'), 'js') ?>',
                lengthMenu: '_MENU_',
                info: '<?= esc(lang('UI_Text.Datatable_Info'), 'js') ?>',
                infoEmpty: '<?= esc(lang('UI_Text.Datatable_Info_Empty'), 'js') ?>',
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>",
                    next: "<i class='mdi mdi-chevron-right'>"
                }
            }
        });
    });
</script>
<script>
    function submit() {
        form.submit();

    }
</script>