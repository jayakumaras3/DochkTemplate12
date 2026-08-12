<link href="<?php echo base_url(); ?>public/creative/assets/libs/multiselect/css/multi-select.css" rel="stylesheet"
    type="text/css" />
<link href="<?php echo base_url(); ?>public/creative/assets/libs/select2/css/select2.min.css" rel="stylesheet"
    type="text/css" />
<link href="<?php echo base_url(); ?>public/creative/assets/libs/selectize/css/selectize.bootstrap3.css"
    rel="stylesheet" type="text/css" />

<style>
    .settings-back-link {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        font-weight: 600;
        font-size: .875rem;
        color: #6658dd;
        text-decoration: none;
    }

    [data-bs-theme="dark"] .settings-back-link {
        color: #9298f5;
    }

    .settings-back-link:hover {
        text-decoration: underline;
    }

    .settings-section {
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
        border: none;
    }

    .settings-section .section-title {
        display: flex;
        align-items: center;
        gap: .5rem;
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 1rem;
    }

    .settings-section .section-title i {
        color: #6658dd;
    }

    [data-bs-theme="dark"] .settings-section .section-title i {
        color: #9298f5;
    }

    .settings-section table thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
        white-space: nowrap;
    }

    [data-bs-theme="dark"] .settings-section table thead th {
        border-bottom-color: #36404a;
        color: #cedeef;
    }

    .settings-section table tbody td {
        vertical-align: middle;
    }

    .settings-section .dataTables_length select {
        border-radius: .5rem;
        border: 1px solid #dee2e6;
        padding: .25rem 1.75rem .25rem .6rem;
    }

    .settings-section .dataTables_filter input {
        border-radius: 2rem;
        border: 1px solid #dee2e6;
        padding: .4rem .75rem;
        min-width: 260px;
    }

    [data-bs-theme="dark"] .settings-section .dataTables_length select,
    [data-bs-theme="dark"] .settings-section .dataTables_filter input {
        border-color: #424e5a;
    }

    .settings-section .pagination .page-link {
        border-radius: 0;
    }
</style>
<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="<?php echo base_url($courses_link); ?>"><?= esc($courses_link_label) ?></a></li>
					<?php if ($show_course_details_link) { ?>
						<li class="breadcrumb-item">
							<form action="<?php echo base_url('my_training/read_more'); ?>" method="POST" class="d-inline"><?= csrf_field() ?>
								<input type="hidden" name="crid" value="<?php echo esc($scourse_id); ?>">
								<input type="hidden" name="detail_type" value="2">
								<input type="hidden" name="tab" value="1">
								<button type="submit" class="btn btn-link p-0 border-0 align-baseline"><?= lang('UI_Text.Course_Details') ?></button>
							</form>
						</li>
					<?php } ?>
				</ol>
			</div>
			<h4 class="page-title"><?= esc($page_title_prefix) ?> - <?php echo esc($coursename[0]['course_name']) ?></h4>
		</div>
	</div>
</div>




<div class="row mt-3">
    <div class="col-lg-6">
        <div class="card settings-section mb-3">
            <div class="card-body">
                <h5 class="section-title"><i class="mdi mdi-account-plus-outline"></i> <?= lang('UI_Text.Assign_User') ?></h5>
                <?php if ($coursename[0]['type'] != '5') { ?>

                    <form class="row" id="addusersForm"><?= csrf_field() ?>
                        <?php echo '<input type="hidden" name="scenario" value="0">'; ?>
                        <div class="col-xl-8 col-md-8   mt-1">
                            <label><?= lang('UI_Text.Select_Users') ?></label>
                            <select class="form-select select2-multiple" data-toggle="select2" data-width="100%"
                                multiple="multiple" name="userid[]" required="">
                                <?php
                                foreach ($getUserclientlist as $users) {

                                    $key = array_search($users['id_user'], array_column($getUserlatestclientCourseByScenario, 'id_user'));
                                    if (!empty($key) || $key === 0) {
                                    } else {
                                        echo '<option value="' . $users['id_user'] . '">' . $users['name'] . ' ' . $users['last_name'] . '</option>';
                                    }
                                } ?>
                            </select>
                        </div>
                        <input type="hidden" name="due_date" value="">
                        <input type="hidden" name="expiry_date" value="">
                        <div class="col-xl-4 col-md-4  mt-3">
                            <input type="hidden" name="course_id" value="<?php echo $scourse_id ?>">
                            <button type="submit" class="btn btn-outline-primary rounded-pill btn-xs waves-effect waves-light"
                                id="submitButton">
                                <?php echo lang('UI_Text.Assign_User') ?>
                            </button>
                        </div>
                    </form>

                <?php } elseif ($coursename[0]['type'] == '5' && count($scenarios) > 0) { ?>
                    <div class="x_panel">
                        <form class="row " id="addusersForm"><?= csrf_field() ?>
                            <?php
                            if (count($scenarios) > 0) {
                            ?>
                                <div class="col-xl-6 col-md-6">
                                    <label for="staticEmail2">
                                        <?php echo lang('UI_Text.Scenarios') ?>
                                    </label>
                                    <select class="form-select" name="scenario" required="">
                                        <?php
                                        foreach ($scenarios as $scenarios_details) {
                                            echo '<option value="' . $scenarios_details['xs'] . '">' . $scenarios_details['scenario_name'] . '</option>';
                                        } ?>
                                    </select>
                                </div>
                            <?php
                            } else {
                                echo '<input type="hidden" name="scenario" value="0">';
                            }
                            ?>

                            <div class="col-xl-6 col-md-6">
                                <label for="staticEmail2">
                                    <?= lang('UI_Text.Select_Users') ?>
                                </label>
                                <select class="form-select select2-multiple" data-toggle="select2" data-width="100%"
                                    multiple="multiple" name="userid[]" required="">
                                    <?php
                                    foreach ($getUserclientlist as $users) {
                                        $key = array_search($users['id_user'], array_column($getUserlatestclientCourseByScenario, 'id_user'));
                                        if (!empty($key) || $key === 0) {
                                        } else {
                                            echo '<option value="' . $users['id_user'] . '">' . $users['name'] . ' ' . $users['last_name'] . '</option>';
                                        }
                                    } ?>
                                </select>
                            </div>
                            <div class="col-xl-4 col-md-4  mt-1">
                                <label><?= lang('UI_Text.Due_Date') ?></label>
                                <input class="form-control" id="due_date" name="due_date" type="date">
                            </div>
                            <div class="col-xl-4 col-md-4  mt-1">
                                <label><?= lang('UI_Text.Expiry_Date') ?></label>
                                <input class="form-control" id="expiry_date" name="expiry_date" type="date">
                            </div>
                            <div class="col-xl-4 col-md-4  mt-3">
                                <input type="hidden" name="course_id" value="<?php echo $scourse_id ?>">
                                <button type="submit" class="btn btn-outline-primary rounded-pill btn-xs waves-effect waves-light">
                                    <?= lang('UI_Text.Assign_User') ?>
                                </button>
                            </div>
                        </form>
                    </div>
                <?php } else {
                } ?>
            </div>
        </div>
    </div>
    <?php if (count($usergroupdata) > 0) { ?>
        <div class="col-lg-6">
            <div class="card settings-section mb-3">
                <div class="card-body">
                    <h5 class="section-title"><i class="mdi mdi-account-group-outline"></i> <?= lang('UI_Text.Assign_User_Group') ?></h5>
                    <?php if ($coursename[0]['type'] != '5') { ?>
                        <form class="row" id="addusersgroupForm"><?= csrf_field() ?>
                            <?php echo '<input type="hidden" name="scenario" value="0">'; ?>
                            <div class="col-xl-8 col-md-8   mt-1">
                                <label><?= lang('UI_Text.Select_User_Group') ?></label>
                                <select name="group_id" class="form-control">
                                    <?php
                                    if (isset($usergroupdata)) {
                                        foreach ($usergroupdata as $eachusergroupdata) {
                                            echo '<option value="' . $eachusergroupdata['sc_cgid'] . '">' . $eachusergroupdata['description'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>

                            </div>
                            <input type="hidden" name="due_date" value="">
                            <input type="hidden" name="expiry_date" value="">
                            <div class="col-xl-4 col-md-4  mt-3">
                                <input type="hidden" name="course_id" value="<?php echo $scourse_id ?>">
                                <button type="submit" class="btn btn-outline-primary rounded-pill btn-xs waves-effect waves-light">
                                    <?= lang('Buttons.Assign_User_Group') ?>
                                </button>
                            </div>
                        </form>

                    <?php } elseif ($coursename[0]['type'] == '5' && count($scenarios) > 0) { ?>
                        <div class="x_panel">
                            <form class="row " id="addusersForm"><?= csrf_field() ?>
                                <?php
                                if (count($scenarios) > 0) {
                                ?>
                                    <div class="col-xl-6 col-md-6   mt-1">
                                        <label for="staticEmail2">
                                            <?= lang('UI_Text.Scenarios') ?>
                                        </label>
                                        <select class="form-select" name="scenario" required="">
                                            <?php
                                            foreach ($scenarios as $scenarios_details) {
                                                echo '<option value="' . $scenarios_details['xs'] . '">' . $scenarios_details['scenario_name'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                <?php
                                } else {
                                    echo '<input type="hidden" name="scenario" value="0">';
                                }
                                ?>

                                <div class="col-xl-8 col-md-8">
                                    <label for="staticEmail2">
                                        <?= lang('UI_Text.Select_User_Group') ?>
                                    </label>
                                    <select name="group_id" class="form-control">
                                        <?php
                                        foreach ($usergroupdata as $eachusergroupdata) {
                                            echo '<option value="' . $eachusergroupdata['sc_cgid'] . '">' . $eachusergroupdata['description'] . ' (' . $eachusergroupdata['assign_id_count'] . ')' . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <input type="hidden" name="due_date" value="">
                                <input type="hidden" name="expiry_date" value="">
                                <div class="col-xl-4 col-md-4 mt-3">

                                    <input type="hidden" name="course_id" value="<?php echo $scourse_id ?>">
                                    <button type="submit" class="btn btn-outline-primary rounded-pill btn-block btn-sm form-control"
                                        id="submitButton">
                                        <?= lang('Buttons.Assign_User_Group') ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                    <?php } else {
                    } ?>
                </div>
            </div>
        </div>
    <?php
    } ?>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card settings-section mb-3">
            <div class="card-body">
                <h5 class="section-title"><i class="mdi mdi-format-list-bulleted"></i> <?= lang('UI_Text.Enrolled_Learners') ?></h5>
                <table id="enrolled-learners-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr class="table-light">
                            <th>#</th>
                            <th><?= lang('UI_Text.User') ?></th>
                            <th><?= lang('UI_Text.Attempts') ?></th>
                            <th><?= lang('UI_Text.Status') ?></th>
                            <th><?= lang('UI_Text.Score') ?></th>
                            <th><?= lang('UI_Text.Latest_Time') ?></th>
                            <th><?= lang('UI_Text.Last_Active') ?></th>
                            <th><?= lang('UI_Text.Action') ?></th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $j = 0;
                        foreach ($getUserlatestclientCourseByScenario as $eachAllCoursesForUsers) {
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
                                        $resultx = array_search($eachAllCoursesForUsers['scenario_id_imp'], array_column($scenarios, 'xs'));
                                        if (is_numeric($resultx)) {
                                            echo ' {' . $scenarios[$resultx]['scenario_name'] . '}';
                                        }
                                    }
                                    ?></td>
                                <td><?php echo $eachAllCoursesForUsers['attempt'] ?></td>
                                <td>
                                    <?php if ($eachAllCoursesForUsers['course_status'] == '2') { ?>
                                        <span class="badge bg-soft-success text-success rounded-pill p-1 px-2"><?php echo lang('UI_Text.Completed'); ?></span>
                                    <?php } elseif ($eachAllCoursesForUsers['course_status'] == '1' || $eachAllCoursesForUsers['lesson_status'] == 'incomplete' || $eachAllCoursesForUsers['lesson_status'] == 'failed') { ?>
                                        <span class="badge bg-soft-info text-info rounded-pill p-1 px-2"><?php echo lang('UI_Text.In Progress'); ?></span>
                                    <?php } elseif ($eachAllCoursesForUsers['course_status'] == '0') { ?>
                                        <span class="badge bg-soft-warning text-warning rounded-pill p-1 px-2"><?php echo lang('UI_Text.Not Started'); ?></span>
                                    <?php } else { ?>
                                        <span class="badge bg-soft-warning text-warning rounded-pill p-1 px-2"><?php echo lang('UI_Text.Not Started'); ?></span>
                                    <?php } ?>
                                </td>
                                <td><?php echo $eachAllCoursesForUsers['raw'] ?></td>
                                <td><?php echo isset($eachAllCoursesForUsers['total_time']) ? $eachAllCoursesForUsers['total_time'] : $totalTime; ?></td>
                                <td><?php echo date('Y-m-d', $eachAllCoursesForUsers['last_updated_on']); ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if ($eachAllCoursesForUsers['lesson_status'] != '') { ?>
                                            <form class="form-horizontal mb-0" action="<?php echo base_url($form_link) ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="user_assign_id"
                                                    value="<?php echo $eachAllCoursesForUsers['suser_assign_id'] ?>">
                                                <input type="hidden" name="tempusername"
                                                    value="<?php echo $eachAllCoursesForUsers['username'] ?>">
                                                <input type="hidden" name="course_name"
                                                    value="<?php echo $eachAllCoursesForUsers['course_name'] ?>">
                                                <input type="hidden" name="scenario_name"
                                                    value="<?php echo (count($scenarios) > 0) ? $scenarios[$resultx]['scenario_name'] : '' ?>">
                                                <input type="hidden" name="return_page" value="<?= esc($return_page) ?>">

                                                <button type="submit" class="btn btn-outline-info rounded-pill waves-effect btn-xs waves-light "><?= lang('Buttons.View') ?></button>
                                            </form>
                                        <?php } ?>
                                        <?php if ($eachAllCoursesForUsers['enrollstatus'] == 1) { ?>
                                            <form class="form-horizontal mb-0" action="<?php echo base_url($form_link1) ?>"
                                                method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="user_assign_id"
                                                    value="<?php echo $eachAllCoursesForUsers['suser_assign_id'] ?>">
                                                <button type="submit"
                                                    onclick="return confirm('<?php echo lang('Alert.Aler_004') ?>')"
                                                    class="btn btn-outline-danger rounded-pill waves-effect btn-xs waves-light "><?= lang('Buttons.Un_Enroll') ?></button>
                                            </form>
                                        <?php } else { ?>
                                            <form class="form-horizontal mb-0" action="<?php echo base_url($delete_enrollment) ?>"
                                                method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="user_assign_id"
                                                    value="<?php echo $eachAllCoursesForUsers['suser_assign_id'] ?>">
                                                <button type="submit"
                                                    onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')"
                                                    class="btn btn-outline-danger rounded-pill waves-effect btn-xs waves-light"><span class="mdi mdi-trash-can-outline"></span> <?= lang('Buttons.Delete') ?></button>
                                            </form>
                                        <?php } ?>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#enrolled-learners-datatable').DataTable({
            responsive: true,
            pagingType: 'simple_numbers',
            columnDefs: [{
                orderable: false,
                targets: [0, -1]
            }],
            language: {
                search: '_INPUT_',
                searchPlaceholder: '<?= esc(lang('UI_Text.Search_Users'), 'js') ?>',
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
    document.getElementById('addusersForm').addEventListener('submit', function() {
        var button = document.getElementById('submitButton');
        button.disabled = true;
        button.innerHTML = 'Submitting...';
    });
</script>
<script>
    $('#addusersForm').on('submit', function(event) {
        event.preventDefault();

        var formData = new FormData(this);

        if (typeof FormData !== 'undefined') {
            $.ajax({
                url: '<?php echo base_url('SCORM/scorm_users/add_user_to_course') ?>',
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    var obj = data;

                    // If server returns JSON as string, parse it
                    if (typeof data === 'string') {
                        obj = JSON.parse(data);
                    }

                    console.log(obj);

                    if (obj.status === 'OK') {
                        alert(obj.message);
                        location.reload();
                    } else {
                        // Show validation errors or general errors
                        if (obj.status == 'error') {
                            alert(obj.message);
                            location.reload();
                            let errorMsg = '';
                            $.each(obj.errors, function(key, value) {
                                errorMsg += value + '\n';
                            });
                            alert(errorMsg);
                        } else {
                            alert(obj.message || 'Something went wrong! Please contact Admin.');
                        }
                        location.reload();
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log('AJAX request failed:', textStatus, errorThrown);
                    alert('Server error occurred! Please try again.');
                }
            });
        } else {
            alert("Your Browser doesn't support FormData API! Use IE 10 or above.");
        }
    });


    $('#addusersgroupForm').on('submit', function(event) {
        event.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: '<?php echo base_url('SCORM/scorm_users/add_usergroup_to_course') ?>',
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                var obj = data;

                // If server returns JSON as a string, parse it:
                if (typeof data === 'string') {
                    obj = JSON.parse(data);
                }

                console.log(obj);

                if (obj.status === 'OK') {
                    alert(obj.message);
                    location.reload();
                } else {
                    alert(obj.message || 'Something Went Wrong! Please contact Site Admin!');
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                console.log('AJAX request failed:', textStatus, errorThrown);
                alert('Server error occurred! Please try again.');
            }
        });
    });
</script>
<script>
    function submit() {
        form.submit();

    }
</script>
