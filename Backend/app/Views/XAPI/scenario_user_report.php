<style>
    /* Same rounded-corner + shadow + table look as SCORM/scorm_courses (courses_search_view.php). */
    .attempts-table-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .attempts-table-card table thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .attempts-table-card table thead th {
        border-bottom-color: #36404a;
        color: #cedeef;
    }

    .attempts-table-card table tbody td {
        vertical-align: middle;
    }

    .attempts-table-card .dataTables_length select {
        border-radius: .5rem;
        border: 1px solid #dee2e6;
        padding: .25rem 1.75rem .25rem .6rem;
    }

    .attempts-table-card .dataTables_filter input {
        border-radius: 2rem;
        border: 1px solid #dee2e6;
        padding: .4rem .75rem;
        min-width: 260px;
    }

    [data-bs-theme="dark"] .attempts-table-card .dataTables_length select,
    [data-bs-theme="dark"] .attempts-table-card .dataTables_filter input {
        border-color: #424e5a;
    }

    .attempts-table-card .pagination .page-link {
        border-radius: 0;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <?php if ($show_course_header_link) { ?>
                        <li class="breadcrumb-item"><a
                                href="<?php echo base_url($course_header_link) ?>"><?php echo $course_header ?></a></li>
                    <?php } ?>
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header_link) ?>"><?php echo $header ?></a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">Attempts (<?php echo isset($userreport) ? $userreport : '' ?>)</h4>
        </div>
    </div>
</div>
<?php if (!empty($userRecords)) {
?>
    <!-- <div class="row">

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">

                    <form class="form-horizontal" action="<?php echo base_url('XAPI/XAPI_courses/addnewattempt') ?>"
                        method="POST" align="left">
                        <input type="hidden" name="user_assign_id"
                            value="<?php echo $userRecords[0]['user_assign_id'] ?>">
                        <input type="hidden" name="course_id" value="<?php echo $userRecords[0]['course_id'] ?>">
                        <input type="hidden" name="student_id" value="<?php echo $userRecords[0]['student_id'] ?>">
                        <input type="hidden" name="attempt" value="<?php echo $userRecords[0]['attempt'] ?>">
                        <button type="submit" class="btn btn-outline-primary waves-effect btn-sm waves-light form-control" title="Add New Attempt">Add New Attempt</button>
                    </form>

                </div>
            </div>
        </div>
    </div> -->
<?php

} ?>
<div class="col-lg-12">
    <div class="card attempts-table-card">
        <div class="card-body">
            <h5 class="card-title mb-3"><?= esc($course_name) ?></h5>
            <table id="attempts-datatable" class="table dt-responsive nowrap w-100">
                <thead>
                    <tr class="table-light">
                        <th>#</th>
                        <!-- <th>Attempts</th> -->
                        <th>Status</th>
                        <th>Score</th>
                        <th>Total Time</th>
                        <th>Last Active</th>
                        <th>Detailed</th>
                        <th><?= lang('UI_Text.Action') ?></th>
                    </tr>
                </thead>
                <tbody>

                    <?php $j = 0;
                    $total = count($userRecords);
                    foreach ($userRecords as $index => $eachuserRecord) {

                        $totalTime = '00:00:00';
                        $trimmedsessionTime = '00:00:00';
                        $splitotalTime = '00:00:00';
                        $j = $j + 1;
                        if (strlen($eachuserRecord['session_time']) > 4) {
                            if ($eachuserRecord['total_time'] == '' || $eachuserRecord['total_time'] == '00:00:00.00') {
                                $splitotalTime = '00:00:00';
                            } else {
                                $splitotalTime = explode('.', $eachuserRecord['total_time'])[0];
                            }
                            if (strlen($eachuserRecord['session_time']) > 8) {
                                $splitsession_time = explode('.', $eachuserRecord['session_time']);
                                $trimmedsessionTime = substr($splitsession_time[0], 2);
                            }
                            if (strlen($eachuserRecord['session_time']) == 8) {
                                $trimmedsessionTime = explode('.', $eachuserRecord['session_time'])[0];
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
                            <td><?= $total - $index ?></td>
                            <td>
                                <?php
                                $statusx = $eachuserRecord['lesson_status'];
                                if (strlen($statusx) > 2) {
                                    if ($statusx == 'completed' || $statusx == 'passed') { ?>
                                        <span class="badge bg-soft-success text-success rounded-pill p-1 px-2"><?php echo 'Completed' ?></span>
                                    <?php } elseif ($statusx == 'incomplete') { ?>
                                        <span class="badge bg-soft-info text-info rounded-pill p-1 px-2"><?php echo 'In progress' ?></span>
                                    <?php } elseif ($statusx == 'not started') { ?>
                                        <span class="badge bg-soft-warning text-warning rounded-pill p-1 px-2"><?php echo 'Not Started' ?></span>
                                    <?php } ?>
                                <?php } else { ?>
                                    <span class="badge bg-soft-warning text-warning rounded-pill p-1 px-2"><?php echo 'Not Started'; ?></span>
                                <?php }
                                ?>
                            </td>
                            <td><?php echo $eachuserRecord['raw'] ?></td>
                            <td><?php echo isset($eachuserRecord['total_time']) ? $eachuserRecord['total_time'] : $totalTime; ?></td>
                             
                            <td><?php echo date('Y-m-d', $eachuserRecord['last_updated_on']); ?></td>

                            <td>
                                <?php if ($eachuserRecord['xapiscenariocount'] > 0) { ?>
                                    <form class="form-horizontal" action="<?php echo base_url($view_details) ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="sc_uid" value="<?php echo $eachuserRecord['sc_uid'] ?>">
                                        <input type="hidden" name="attempt" value="<?php echo $eachuserRecord['attempt'] ?>">
                                        <button type="submit"
                                            class="btn btn-sm rounded-pill widget-icon btn-primary"><?php echo $eachuserRecord['xapiscenariocount'] ?></button>
                                    </form>
                                <?php } ?>
                            </td>
                            <?php //if ($j == count($userRecords)) { 
                            ?>
                            <td>
                                <form class="form-horizontal" action="<?php echo base_url($delete_enrollment) ?>"
                                    method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="sc_uid" value="<?php echo $eachuserRecord['sc_uid'] ?>">
                                    <button type="submit"
                                        onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')"
                                        class="btn btn-outline-danger rounded-pill waves-effect btn-xs waves-light">Delete</button>
                                </form>
                            </td>
                            <?php // } else { 
                            ?>
                            <!-- <td></td> -->
                            <?php // } 
                            ?>

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
        $('#attempts-datatable').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
            columnDefs: [{
                orderable: false,
                targets: [0, 5, 6]
            }],
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