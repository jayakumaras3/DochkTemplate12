<?php
$skills = array();
foreach ($department_list as $data) {
    $skills[$data['value']] = $data['name'];
}
/* $skills = array(
    "52" => "Instructional Design",
    "2" => "Content Editor",
    "3" => "Graphic Design",
    "4" => "Visual Design",
    "5" => "Visualizer",
    "6" => "Post Production",
    "7" => "Articulate",
    "8" => "3D Modeling/Texturing",
    "9" => "General Programming",
    "10" => "Quality Assurance",
    "51" => "Unity3D Programming",
    "53" => "Project Manager",
    "54" => "SME"
); */
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <?php
                    if ($returnid == 2) {
                    ?>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/PM_projects'); ?>">Projects</a></li>
                    <?php
                    } else {
                    ?>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/PM_ucn/edit_ucn'); ?>">Edit UCN</a></li>
                    <?php
                    }
                    ?>
                </ol>
            </div>
            <h4 class="page-title">Project Breakup</h4>
        </div>
    </div>
</div>
<div class="row"> 
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>UCN</th>
                            <th>Project Name</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>% Effort UCN</th>
                            <th>% Completion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $project_details[0]['ucn']; ?></td>
                            <td><?php echo $project_details[0]['projectname']; ?></td>
                            <td><?php $status = $project_details[0]['status'];
                                switch ($status) {
                                    case 1:
                                        echo 'Alpha';
                                        break;
                                    case 2:
                                        echo 'Beta';
                                        break;
                                    case 5:
                                        echo 'Gamma';
                                        break;
                                    case 3:
                                        echo 'On Hold';
                                        break;
                                    case 4:
                                        echo 'Completed';
                                        break;

                                    case 0:
                                        echo 'Deleted';
                                        break;
                                }
                                ?></td>
                            <td><?php echo $project_details[0]['start_date']; ?></td>
                            <td><?php echo $project_details[0]['end_date']; ?></td>
                            <td><?php echo $project_details[0]['wip']; ?></td>
                            <td><?php echo $project_details[0]['percent']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4>Effort</h4>
                <?php

                //   print_r($manager_allocated_effort);
                //exit();
                ?>
                <form id="bulkEffortForm" action="<?= base_url('Project_Manage/PM_ucn/allocate_effort_to_manager_bulk') ?>" method="POST"><?= csrf_field() ?>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Skill</th>
                                <th>Planned UCN</th>
                                <th>Planned Proj</th>
                                <?php if ($project_details[0]['status'] != 4) { ?>
                                    <th>Leads<span class="text-danger">*</span></th>
                                    <th>Allocate<span class="text-danger">*</span></th>
                                    <!-- <th>Save</th> -->
                                <?php } ?>
                                <th>Remarks</th>
                                <th width="5%">Allocated</th>
                                <th>TL Planned</th>
                                <th>Actual</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $j = 0;
                            $totaleffort = 0;
                            $totalprojeffort = 0;
                            $allocated_master = 0;
                            $allocated_tl = 0;
                            $actualeffort = 0;
                            foreach ($skills as $skillId => $skill_data) {
                                $key1 = array_search($skillId, array_column($effort_data, 'type_resource'));
                                if ($key1 != '') {
                                    $j++;
                                    $allocated_tl_this = 0;
                                    $allocated_emp_this = 0;
                                    $allocated_mst_this = 0;
                            ?>
                                    <tr>
                                        <td><?php echo $j; ?></td>
                                        <td><?php echo  $skill_data; ?></td>
                                        <td>
                                            <?php
                                            $totaleffort = $totaleffort +  $effort_data[$key1]['effort'];
                                            echo $effort_data[$key1]['effort'];
                                            ?>
                                        </td>

                                        <td><?php $projeff =  $effort_data[$key1]['effort'] * $project_details[0]['wip'] / 100;
                                            $totalprojeffort = $totalprojeffort + $projeff;
                                            echo $projeff; ?></td>
                                        <?php if ($project_details[0]['status'] != 4) { ?>
                                            <!-- <form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_ucn/allocate_effort_to_manager') ?>" method="POST"><?= csrf_field() ?> -->
                                            <td>
                                                <select class="form-select manager-select" name="manager[]" onchange="checkEffortEntries()">
                                                    <option value="">-- Select User --</option>
                                                    <option value="<?php echo $self_user; ?>"><?php echo $self_user_name; ?> : Self</option>
                                                    <?php
                                                    foreach ($managerlist as $managers) {
                                                    ?>
                                                        <option value="<?php echo $managers['id_user']; ?>"><?php echo $managers['name']; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td width="8%"><input type="number" class="form-control effort-input" name="effort[]" min="1" value="" onchange="checkEffortEntries()"></td>
                                            <td>
                                                <textarea class="form-control" name="remarks[]" rows="1"></textarea>
                                            </td>


                                            <td style="display:none;">

                                                <input type="hidden" name="stage[]" value="<?php echo $status; ?>">
                                                <input type="hidden" name="projectids[]" value="<?php echo $project_details[0]['projectid']; ?>">
                                                <input type="hidden" name="ucns[]" value="<?php echo $project_details[0]['ucn']; ?>">
                                                <input type="hidden" name="skillIds[]" value="<?php echo $skillId; ?>">
                                                <!-- <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light">
                                                    Add
                                                </button> -->

                                            </td>
                                            <!-- </form> -->
                                        <?php } ?>

                                        <?php
                                        $key2 = array_search($skillId, array_column($manager_allocated_effort, 'skill_id'));
                                        if ($key2 != '') {
                                            $allocated_master = $allocated_master + $manager_allocated_effort[$key2]['total'];
                                            $allocated_mst_this = $manager_allocated_effort[$key2]['total'];

                                            $allocated_tl_this = $manager_allocated_effort[$key2]['tleffort'];
                                            $allocated_tl = $allocated_tl + $allocated_tl_this;

                                            $allocated_emp_this = $manager_allocated_effort[$key2]['efffort'];
                                            $actualeffort = $actualeffort + $allocated_emp_this;
                                        } ?>

                                        <td <?php if ($allocated_mst_this > $projeff) {
                                                echo ' style="color: red;"';
                                            } ?>>
                                            <?php echo $allocated_mst_this; ?>
                                        </td>

                                        <td><?php echo $allocated_tl_this; ?></td>
                                        <td><?php echo $allocated_emp_this; ?></td>
                                        <!-- <td>
                                            <form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_ucn/view_effort_details') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="projectid" value="<?php echo $project_details[0]['projectid']; ?>">
                                                <input type="hidden" name="ucn" value="<?php echo $project_details[0]['ucn']; ?>">
                                                <input type="hidden" name="skillId" value="<?php echo $skillId; ?>">
                                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-eye-outline"></span></button>
                                            </form>
                                        </td> -->
                                        <td>
                                            <button type="button" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="viewTaskDetails(<?= $project_details[0]['projectid'] ?>,<?= $project_details[0]['ucn'] ?>,<?= $skillId ?>)">
                                                <span class="mdi mdi-eye-outline"></span>
                                            </button>
                                        </td>
                                    </tr>
                            <?php }
                            } ?>

                            <tr>
                                <td colspan="2"></td>
                                <td><?php echo $totaleffort; ?></td>
                                <td><?php echo $totalprojeffort; ?></td>
                                <?php if ($project_details[0]['status'] != 4) { ?>
                                    <td colspan="3"></td>
                                <?php } ?>
                                <td><?php echo $allocated_master; ?></td>
                                <td><?php echo $allocated_tl; ?></td>
                                <td><?php echo $actualeffort; ?></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center mt-3">
                        <span data-bs-toggle="tooltip" data-bs-placement="top" >
                            <button type="submit" class="btn btn-outline-primary" id="submitButton" disabled>
                                Allocate task to Manager/Team Lead
                            </button>
                        </span>
                    </div>


                </form>
                <script>
                    function viewTaskDetails(project_id, ucn_id, skill_id) {
                        postToUrl('<?= base_url('Project_Manage/PM_ucn/view_effort_details') ?>', {
                            projectid: project_id,
                            ucn: ucn_id,
                            skillId: skill_id
                        });
                    }


                    function postToUrl(url, params) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = url;

                        for (const key in params) {
                            if (params.hasOwnProperty(key)) {
                                const hiddenField = document.createElement('input');
                                hiddenField.type = 'hidden';
                                hiddenField.name = key;
                                hiddenField.value = params[key];
                                form.appendChild(hiddenField);
                            }
                        }

                        document.body.appendChild(form);
                        form.submit();
                    }
                </script>

            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>External Cost</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <td>#</td>
                            <td>Description</td>
                            <th>Planned</th>
                            <th>Actual</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        $key1 = array_search(55, array_column($effort_data, 'type_resource'));
                        if ($key1 != '') {
                            $j++;
                        ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php
                                    echo $effort_data[$key1]['remarks'];
                                    ?></td>
                                <td>
                                    <?php
                                    echo $effort_data[$key1]['effort'];
                                    ?>
                                </td>
                            </tr>
                        <?php }  ?>
                        <?php
                        $key1 = array_search(56, array_column($effort_data, 'type_resource'));
                        if ($key1 != '') {
                            $j++;
                        ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php
                                    echo $effort_data[$key1]['remarks'];
                                    ?></td>
                                <td>
                                    <?php
                                    echo $effort_data[$key1]['effort'];
                                    ?>
                                </td>
                            </tr>
                        <?php }  ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    function checkEffortEntries() {
        const userSelects = document.querySelectorAll('.manager-select');
        const effortInputs = document.querySelectorAll('.effort-input');
        let enableButton = false;

        userSelects.forEach((select, i) => {
            const selectedValue = select.value.trim();
            const effortValue = effortInputs[i].value.trim();

            if (selectedValue !== '' && effortValue !== '') {
                enableButton = true;
            }
        });

        document.getElementById('submitButton').disabled = !enableButton;
    }
</script>