

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">

                    <li class="breadcrumb-item"><a
                            href="<?php echo base_url('SCORM/scorm_courses/course_add_view'); ?>">Course</a></li>

                </ol>
            </div>
            <h4 class="page-title">Team</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" id="addreviewerForm">
                    <div class="row">
                        <div class="col-xl-4 col-md-4   mt-2">
                            <label for="clientname" class="form-label">Select Client Users <span
                                    class="text-danger">*</span></label>
                            <select class="form-select select2-multiple" data-toggle="select2" multiple="multiple"
                                name="assignuser[]" required="">
                                <?php if (!empty($getUserclientlist)) {
                                    foreach ($getUserclientlist as $users) { ?>
                                        <option value="<?php echo $users['id_user'] ?>"><?php echo $users['fullname']; ?>
                                        </option>
                                    <?php }
                                } ?>
                            </select>

                        </div>
                        
                        <div class="col-xl-4 col-md-4   mt-1">
                            <label for="projectname" class="form-label">Due Date <span
                                    class="text-danger">*</span></label>
                            <input class="form-control" id="due_date" name="due_date" type="date" value="">

                        </div>
                        <!-- </div> -->
                        <!-- <div class="row mt-1"> -->
                        <?php if ($stage[0]['mode'] == 1) {
                            $stage_name = 'Development';
                        } elseif ($stage[0]['mode'] == 2) {
                            $stage_name = 'Live';
                        } elseif ($stage[0]['mode'] == 3) {
                            $stage_name = 'Alpha Review';
                        } elseif ($stage[0]['mode'] == 4) {
                            $stage_name = 'Alpha 2 Review';
                        } elseif ($stage[0]['mode'] == 5) {
                            $stage_name = 'Beta Review';
                        } elseif ($stage[0]['mode'] == 6) {
                            $stage_name = 'Beta 2 Review';
                        } elseif ($stage[0]['mode'] == 7) {
                            $stage_name = 'Gamma';
                        } elseif ($stage[0]['mode'] == 8) {
                            $stage_name = 'Gamma 2';
                        } else {
                            $stage_name = 'Development';
                        }
                        ?>
                         <input type="hidden" name="returnid" value="1">
                            <input type="hidden" name="course_id" value="<?php echo $scourse_id; ?>">
                            <input type="hidden" name="coursestatus" value="1">
                            <input type="hidden" name="stage" value="<?php echo $stage['0']['mode']; ?>">
                            <input type="hidden" name="type_of_assignment" value="1">
                           

                        <div class="col-xl-4 col-md-4   mt-4">
                            <button type="submit" class="btn btn-outline-primary waves-effect btn-sm waves-light"
                                id="submitButton">
                                Assign Client Reviewer - <?php echo $stage_name ?>
                            </button>

                        </div>
                </form>

            </div>
        </div>
    </div>
    <?php if (count($coursegroupdata) > 0) { ?>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo base_url('Project_Manage/PM_ucn/assign_user_group_reviewers') ?>"
                        method="POST"><?= csrf_field() ?>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-1">
                                    <label for="clientname" class="form-label">Client User Groups <span
                                            class="text-danger">*</span></label>
                                    <select name="group_id" class="form-control" required>

                                        <?php
                                        if (isset($coursegroupdata)) {
                                            foreach ($coursegroupdata as $eachusergroupdata) {
                                                echo '<option value="' . $eachusergroupdata['sc_cgid'] . '">' . $eachusergroupdata['description'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-1">
                                    <label for="projectname" class="form-label">Due Date <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" id="due_date" name="due_date" type="date" value="">
                                </div>
                            </div>
                            <!-- </div> -->
                            <!-- <div class="row mt-1"> -->
                            <?php if ($stage[0]['mode'] == 1) {
                                $stage_name = 'Development';
                            } elseif ($stage[0]['mode'] == 2) {
                                $stage_name = 'Live';
                            } elseif ($stage[0]['mode'] == 3) {
                                $stage_name = 'Alpha Review';
                            } elseif ($stage[0]['mode'] == 4) {
                                $stage_name = 'Alpha 2 Review';
                            } elseif ($stage[0]['mode'] == 5) {
                                $stage_name = 'Beta Review';
                            } elseif ($stage[0]['mode'] == 6) {
                                $stage_name = 'Beta 2 Review';
                            } elseif ($stage[0]['mode'] == 7) {
                                $stage_name = 'Gamma';
                            } elseif ($stage[0]['mode'] == 8) {
                                $stage_name = 'Gamma 2';
                            } else {
                                $stage_name = 'Development';
                            }
                            ?>
                            <?php  //print_r($stage[0]['mode']); exit(); 
                                ?>
                            <div class="col-lg-4">
                                <div class="mb-1">
                                    <label></label>
                                    <div class="text-sm-end  mt-sm-0">
                                        <input type="hidden" name="returnid" value="1">
                                        <input type="hidden" name="course_id" value="<?php echo $scourse_id; ?>">
                                        <input type="hidden" name="coursestatus" value="1">
                                        <input type="hidden" name="stage" value="<?php echo $stage['0']['mode']; ?>">
                                        <input type="hidden" name="type_of_assignment" value="1">
                                        <button type="submit"
                                            class="btn btn-outline-danger waves-effect btn-sm waves-light col-md-12"
                                            id="submitButton">
                                            Assign Client Reviewer Groups - <?php echo $stage_name ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                    </form>

                </div>
            </div>
        </div>
    <?php } ?>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table class="table mb-0 w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Reviewer</th>
                            <th>Assigned On</th>
                            <th>Due On</th>
                            <th>Completed On</th>
                            <th>Stage</th>
                            <th>Status</th>
                            <th>Rev Task</th>
                            <th>Un Enroll</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        $stage1 = '';
                        if ($client_access) {
                            foreach ($client_access as $data) {
                               
                                $j = $j + 1 ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $data['fullname']; ?></td>
                                    <td><?php echo date('m-d-Y', $data['createdon']); ?></td>

                                    <td><?php echo ($data['due_date'] != '0000-00-00') ? date('m-d-Y', strtotime($data['due_date'])) : ''; ?>
                                    </td>
                                    <td><?php echo ($data['last_updated_on'] != '0') ? date('m-d-Y', ($data['last_updated_on'])) : ''; ?>
                                    </td>
                                    <td>
                                        <?php if ($data['stage'] == 1) {
                                            $stage1 = 'Development';
                                        } elseif ($data['stage'] == 2) {
                                            $stage1 = 'Live';
                                        } elseif ($data['stage'] == 3) {
                                            $stage1 = 'Alpha Review';
                                        } elseif ($data['stage'] == 4) {
                                            $stage1 = 'Alpha 2 Review';
                                        } elseif ($data['stage'] == 5) {
                                            $stage1 = 'Beta Review';
                                        } elseif ($data['stage'] == 6) {
                                            $stage1 = 'Beta 2 Review';
                                        } elseif ($data['stage'] == 7) {
                                            $stage1 = 'Gamma';
                                        } elseif ($data['stage'] == 8) {
                                            $stage1 = 'Gamma 2';
                                        } ?>
                                        <?php echo $stage1; ?>
                                    </td>
                                    <td><?php $status = $data['course_status'];
                                    if ($status == 1) {
                                        echo 'In progress';
                                    } elseif ($status == 2) {
                                        echo 'Completed';
                                    } elseif ($status == 3) {
                                        echo 'Review Completed';
                                    }

                                    ?>
                                    </td>
                                    <td>
                                        <?php $role = $data['role'];
                                        if ($role == 0) { ?>
                                            <form action="<?php echo base_url('Project_Manage/PM_ucn/assign_task_to_reviewer') ?>"
                                                method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="user_assign_id"
                                                    value="<?php echo $data['user_assign_id']; ?>">
                                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                    <span class="mdi mdi-window-minimize"></span></button>
                                            </form>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <form action="<?php echo base_url('Project_Manage/PM_ucn/delete_assigneduser') ?>"
                                            method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="returnid" value="1">
                                            <input type="hidden" name="user_assign_id"
                                                value="<?php echo $data['user_assign_id']; ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"
                                                onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')">
                                                <span class="mdi mdi-trash-can-outline"></span></button>
                                        </form>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    document.getElementById('addreviewerForm').addEventListener('submit', function () {
        var button = document.getElementById('submitButton');
        button.disabled = true;
        button.innerHTML = 'Submitting...';
    });
</script>
<script>
    $('#addreviewerForm').on('submit', function (event) {

        event.preventDefault();

        var dataString = new FormData($('#addreviewerForm')[0]);

        if (typeof FormData !== 'undefined') {

            $.ajax({
                url: '<?php echo base_url('Project_Manage/PM_ucn/assignreviewer') ?>',
                type: "POST",
                data: dataString,
                async: false,
                processData: false,
                contentType: false,
                success: function (data) {

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
                error: function (xhr, textStatus, errorThrown) {
                    console.log('request failed');
                }
            })
        } else {
            message("Your Browser Don't support FormData API! Use IE 10 or Above!");
        }

    });
</script>