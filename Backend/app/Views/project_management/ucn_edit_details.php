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
            <h4 class="page-title">Edit Project Details</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_ucn/update_project_details') ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Project Name <span class="text-danger">*</span></label>
                                <input required type="text" class="form-control col-md-12" name="name" placeholder="Project Name" value="<?php echo $project_details[0]['projectname']; ?>" />
                            </div>
                        </div>
                        <input type="hidden" name="type_of_project" value="E-Learning">
                        <input type="hidden" name="percentage_po" value="0">

                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Status <span class="text-danger">*</span></label>
                                <?php $status = $project_details[0]['status']; ?>
                                <select name="status" class="form-control">
                                    <option value="1" <?php if ($status == 1) echo 'Selected'; ?>>Alpha</option>
                                    <option value="2" <?php if ($status == 2) echo 'Selected'; ?>>Beta</option>
                                    <option value="5" <?php if ($status == 5) echo 'Selected'; ?>>Gamma</option>
                                    <option value="3" <?php if ($status == 3) echo 'Selected'; ?>>On Hold</option>
                                    <option value="4" <?php if ($status == 4) echo 'Selected'; ?>>Completed</option>
                                    <!--   <option value="0" <?php if ($status == 0) echo 'Selected'; ?>>Delete</option> -->
                                </select>
                            </div>
                        </div>
                        <!--  <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="percentage" class="form-label">Effort % Allocation to UCN</label>
                                <input class="form-control" id="percent" name="wip" type="int" value="<?php echo $project_details[0]['wip']; ?>">
                            </div>
                        </div> -->
                        <input type="hidden" name="wip" value="1">
                        <input type="hidden" name="start_date" value="<?php echo $project_details[0]['start_date']; ?>">
                        <input type="hidden" name="end_date" value="<?php echo $project_details[0]['end_date']; ?>">
                        <input type="hidden" name="percent" value="0">
                        <input type="hidden" name="remarks" value="">
                        <!-- <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="inputEmail3" class="form-label">Start Date</label>
                                <input id="start_date" name="start_date" class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="<?php echo $project_details[0]['start_date']; ?>"
                                    onchange="updateEndDateMin()">
                                <script>
                                    function timeFunctionLong(input) {
                                        setTimeout(function() {
                                            input.type = 'text';
                                        }, 60000);
                                    }
                                </script>
                            </div>
                        </div> -->
                        <!-- <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="inputEmail3" class="form-label">End Date</label>
                                <input id="end_date" name="end_date" class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="<?php echo $project_details[0]['end_date']; ?>">
                                <script>
                                    function timeFunctionLong(input) {
                                        setTimeout(function() {
                                            input.type = 'text';
                                        }, 60000);
                                    }

                                    function updateEndDateMin(index) {
                                        var startDate = document.getElementById('start_date').value;
                                        var endDateInput = document.getElementById('end_date');
                                        endDateInput.min = startDate;
                                    }
                                </script>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="percentage" class="form-label">Percentage Completion</label>
                                <input class="form-control" id="percent" name="percent" type="int" value="<?php echo $project_details[0]['percent']; ?>">
                            </div>
                        </div>
 -->
                        <!--  <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="project_type" class="form-label">Project Type <span class="text-danger">*</span></label>
                                <select name="project_type" class="form-control">
                                    <option value="1" SELECTED>E-Learning</option>
                                    <option value="2">Video</option>
                                    <option value="3">AR/VR</option>
                                </select>
                            </div>
                        </div>
                    </div> 

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1">
                                <label for="inputEmail3" class="col-form-label">Remarks</label>
                                <textarea class="form-control" name="remarks"><?php echo $project_details[0]['description']; ?></textarea>
                            </div>
                        </div>
                    </div>-->
                        <div class="col-lg-4 mt-4">
                            <input type="hidden" name="project_type" value="1">
                            <input type="hidden" name="projectid" value="<?php echo $project_details[0]['projectid']; ?>">
                            <div class="text-sm-end  mt-sm-0">
                                <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light">
                                    Update Project Details
                                </button>
                            </div>
                        </div>

                </form>
            </div>
        </div>
    </div>
</div>

<!-- <div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo base_url('Project_Manage/PM_pricing_sheet/add_user_to_pricing_sheet') ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Select User <span class="text-danger">*</span></label>
                                <select name="assignuser" class="form-control" required>
                                     <option value="">Select User</option> 
                                    <?php
                                    foreach ($project_manager as $data) {
                                        echo '<option value="' . $data['id_user'] . '">'  . $data['fname'] . ' ' . $data['lname'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="role" value="1">


                        <div class="col-lg-6 mt-3">
                            <input type="hidden" name="ppid" value="<?php echo $projectid; ?>">
                            <input type="hidden" name="returnid" value="6">
                            <input type="hidden" name="type_of_assignment" value="1">
                            <button type="submit" class="btn btn-outline-primary waves-effect btn-sm waves-light col-md-12">
                                Assign User to Project</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                            foreach ($access as $data) {
                                $j = $j + 1 ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $data['fname'] . ' ' . $data['lname']; ?></td>

                                    <td>
                                        <?php if ($j > 1) { ?>
                                            <form action="<?php echo base_url('Project_Manage/PM_pricing_sheet/delete_userassignment') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="returnid" value="6">
                                                <input type="hidden" name="project_assign_id" value="<?php echo $data['project_assign_id']; ?>">
                                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                    <span class="mdi mdi-trash-can-outline"></span></button>
                                            </form>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div> -->