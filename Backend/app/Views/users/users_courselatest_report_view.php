<style>
    /* Same rounded-corner + shadow + table look as SCORM/scorm_courses (courses_search_view.php). */
    .user-report-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .user-report-card table thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .user-report-card table thead th {
        border-bottom-color: #36404a;
        color: #cedeef;
    }

    .user-report-card table tbody td {
        vertical-align: middle;
    }

    .user-report-card .dataTables_length select {
        border-radius: .5rem;
        border: 1px solid #dee2e6;
        padding: .25rem 1.75rem .25rem .6rem;
    }

    .user-report-card .dataTables_filter input {
        border-radius: 2rem;
        border: 1px solid #dee2e6;
        padding: .4rem .75rem;
        min-width: 260px;
    }

    [data-bs-theme="dark"] .user-report-card .dataTables_length select,
    [data-bs-theme="dark"] .user-report-card .dataTables_filter input {
        border-color: #424e5a;
    }

    .user-report-card .pagination .page-link {
        border-radius: 0;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Reports/client_reports'); ?>"><?php echo lang('Buttons.Report'); ?></a></li>

                </ol>
            </div>
            <h4 class="page-title"><?php echo lang('UI_Text.User_Report') ?> - <?php echo $username[0]['name']; ?></h4>
        </div>
    </div>
</div>
<?php //print_r($getAllCoursesForClient); 
?>
<div class="row">
    <div class="col-6">
        <div class="card user-report-card">
            <div class="card-body">
                <h4 class="mb-1 header-title"><i class="fe-plus-circle me-1"></i> <?= lang('UI_Text.Assign_Course') ?></h4>
                <p class="text-muted font-13 mb-3"><?= lang('UI_Text.Assign_Course_Description') ?></p>
                <form id="addcoursesForm"><?= csrf_field() ?>
                    <div class="row align-items-end">
                        <!-- Course Select -->
                        <div class="col-md-8 mb-3">
                            <label for="addcourses_course_select" class="form-label fw-semibold">
                                <?php echo lang('UI_Text.Learning_Courses') ?>
                                <span class="text-danger">*</span>
                            </label>

                            <input type="hidden" name="scenario" value="0">

                            <select id="addcourses_course_select" class="form-select select2-multiple"
                                data-toggle="select2"
                                data-width="100%"
                                multiple="multiple"
                                name="course_id[]"
                                required>
                                <?php
                                foreach ($getAllCoursesForClient as $courses) {
                                    if ($courses['course_name'] != '') {
                                        $key = array_search($courses['course_id'], array_column($getlatestCoursesForUsers, 'course_id'));

                                        if (!empty($key) || $key === 0) {
                                        } else {

                                            echo '<option value="' . $courses['course_id'] . '">' .$courses['course_name'] .'</option>';
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Due Date -->
                        <!-- <div class="col-md-4 mb-3">
                            <label>Due Date</label>
                            <input class="form-control" id="due_date" name="due_date" type="date">
                        </div> -->

                        <!-- Submit Button -->
                        <div class="col-md-3 mb-3">
                            <input type="hidden" name="userid" value="<?php echo $userid ?>">
                            <button type="submit"
                                class="btn btn-outline-primary rounded-pill w-90 waves-effect btn-xs waves-light submitButton">
                                <?php echo lang('Buttons.Add_Course') ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<?php if (!empty($getlatestCoursesForUsers)) { ?>
    <div class="row">
        <div class="col-12">
            <div class="card user-report-card">
                <div class="card-body">
                    <h4 class="mb-3 header-title"><i class="fe-list me-1"></i> <?= lang('UI_Text.Assigned_Courses') ?></h4>
                    <table id="user-report-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr class="table-light">
                                <th>#</th>
                                <th><?php echo lang('UI_Text.Course_Name') ?></th>
                                <th><?php echo lang('UI_Text.Attempts') ?></th>
                                <th><?php echo lang('UI_Text.Status') ?></th>
                                <th><?php echo lang('UI_Text.Score') ?></th>
                                <th><?php echo lang('UI_Text.Total_Time') ?></th>
                                <th><?php echo lang('UI_Text.Action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                            foreach ($getlatestCoursesForUsers as $eachAllCoursesForUsers) {
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
                                    <td> <?php if (strlen($eachAllCoursesForUsers['lesson_status']) > 2) {
                                                if ($eachAllCoursesForUsers['lesson_status'] == 'completed' || $eachAllCoursesForUsers['lesson_status'] == 'passed') { ?>
                                                <span class="badge bg-soft-success text-success rounded-pill p-1 px-2"><?php echo lang('UI_Text.Completed') ?></span>
                                            <?php  } elseif ($eachAllCoursesForUsers['lesson_status'] == 'incomplete') { ?>
                                                <span class="badge bg-soft-info text-info rounded-pill p-1 px-2"><?php echo lang('UI_Text.In_Progress') ?></span>
                                            <?php } elseif ($eachAllCoursesForUsers['lesson_status'] == 'not started') { ?>
                                                <span class="badge bg-soft-warning text-warning rounded-pill p-1 px-2"><?php echo lang('UI_Text.Not_Started') ?></span>
                                            <?php  } ?>

                                        <?php } else { ?>
                                            <span class="badge bg-soft-warning text-warning rounded-pill p-1 px-2"><?php echo lang('UI_Text.Not_Started') ?></span>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo $eachAllCoursesForUsers['raw'] ?></td>
                                    <td><?php echo ($eachAllCoursesForUsers['total_time'] != '') ? $eachAllCoursesForUsers['total_time'] : '';  ?></td>

                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if ($eachAllCoursesForUsers['lesson_status'] != '') { ?>
                                                <form class="form-horizontal mb-0" action="<?php echo base_url('User_login/client_users/getscormuserdetails') ?>" method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="user_assign_id" value="<?php echo $eachAllCoursesForUsers['user_assign_id'] ?>">
                                                    <button type="submit" class="btn btn-outline-info rounded-pill btn-xs waves-effect waves-light"><?php echo lang('Buttons.View') ?></button>
                                                </form>
                                            <?php } ?>
                                            <form class="form-horizontal mb-0" action="<?php echo base_url('User_login/client_users/deleteEnrollment') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="user_assign_id" value="<?php echo $eachAllCoursesForUsers['suser_assign_id'] ?>">
                                                <input type="hidden" name="encode" value="<?php echo $encode; ?>">
                                                <button type="submit" onclick="return confirm('<?php echo lang('Alert.Aler_001') ?>')" class="btn btn-outline-danger rounded-pill btn-xs waves-effect waves-light"><?php echo lang('Buttons.Un_Enroll') ?></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php  } ?>
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
                        alert('Course added successfully!');
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
        $('#user-report-datatable').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
            columnDefs: [{
                orderable: false,
                targets: [0, 6, 7]
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
<script>
    // Use event delegation or loop through all forms
    document.querySelectorAll('form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            var button = form.querySelector('.submitButton'); // Use class instead of ID

            // Check if the button is already disabled (indicating the form is being submitted)
            if (button.disabled) {
                e.preventDefault(); // Prevent the form from being submitted again
                return false;
            }

            // Disable the submit button and change its text to 'Submitting...'
            button.disabled = true;
            button.innerHTML = 'Submitting...';
        });
    });
</script>