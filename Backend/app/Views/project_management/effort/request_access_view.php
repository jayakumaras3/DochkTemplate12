<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/Effort_Tracker'); ?>">Effort Tracker</a></li>
                </ol>
            </div>
            <h4 class="page-title">Project Access</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="text-uppercase bg-light p-2 mt-0 mb-3">Project Access Request</h5>
                <div id="add-effort-alert" class="alert d-none" role="alert"></div>
                <form method="POST" id="add-effort-form" action="<?php echo base_url('Project_Manage/Effort_Tracker/AddRequest'); ?>"><?= csrf_field() ?>
                    <div class="row mb-3">
                        <div class="col-md-12 mb-3">
                            <label for="year" class="form-label">Select Project <span class="text-danger">*</span></label>
                            <select name="project_id" id="project_id" class="form-control mb-1" required>
                                <option value="">Select Project</option>
                                <?php if (!empty($projects) && is_array($projects)) { ?>
                                    <?php foreach ($projects as $project) {
                                        if (!empty($projects_with_access) && is_array($projects_with_access)) {
                                            $has_access = false;
                                            foreach ($projects_with_access as $access) {
                                                if ($access['projectid'] == $project['projectid'] && ($access['pusers_status'] == 1 || $access['pusers_status'] == 2)) {
                                                    $has_access = true;
                                                    break;
                                                }
                                            }
                                            if ($has_access) {
                                                continue; // Skip projects that already have access
                                            }
                                        }

                                    ?>
                                        <option value="<?php echo $project['projectid']; ?>"><?php echo $project['projectname']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="month" class="form-label">Description</label>
                            <input type="text" name="description" id="description" class="form-control" placeholder="Description">
                            <small class="form-text text-muted">Maximum 20 words.</small>
                        </div>
                        <div class="col-md-4 align-self-end">
                            <button type="submit" class="btn btn-outline-primary waves-effect btn-sm waves-light">Save</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer text-muted">
                <p class="mb-0">Note:
                <ol>
                    <li><span class="text-danger">*</span> indicates required fields.</li>
                    <li>Request access only for projects that you have worked on.</li>
                </ol>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <!-- Table to show the effort data -->
        <div class="row ">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col-md-12">
                                <h5 class="text-uppercase bg-light p-2 mt-0 mb-3">Project Access Status</h5>
                            </div>
                        </div>

                        <table class="table table-centered  table-bordered table-striped" id="products-datatable" data-selected-week="<?php echo isset($selected_week) ? $selected_week : date('Y-\WW'); ?>">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">S.No</th>
                                    <th>Project Name</th>
                                    <th style="text-align: right; width: 100px;">Status</th>
                                    <th>Description</th>
                                    <th style="width: 100px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($projects_with_access) && is_array($projects_with_access)) {
                                    $sno = 1;
                                ?>
                                    <?php foreach ($projects_with_access as $access) {
                                    ?>
                                        <tr>
                                            <td><?php echo $sno++; ?></td>
                                            <td>
                                                <?php
                                                echo $access['projectname'];
                                                ?>
                                            </td>
                                            <td style="text-align: right;">
                                                <?php
                                                switch ($access['pusers_status']) {
                                                    case 1:
                                                        echo '<span class="badge bg-soft-success text-secondary p-1">Approved</span>';
                                                        break;
                                                    case 2:
                                                        echo '<span class="badge bg-soft-secondary text-success p-1">Requested</span>';
                                                        break;
                                                    case 3:
                                                        echo '<span class="badge bg-soft-danger text-danger p-1">Rejected</span>';
                                                        break;
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php echo $access['description']; ?>
                                            </td>
                                            <?php if ($access['pusers_status'] == 2) { ?>
                                                <td style="text-align: center; white-space: nowrap;">
                                                    <form method="POST" action="<?php echo base_url('Project_Manage/Effort_Tracker/Delete_request'); ?>" onsubmit="return confirm('Are you sure you want to delete this request?');" style="display:inline-block;"><?= csrf_field() ?>
                                                        <input type="hidden" name="pu_id" value="<?php echo $access['pu_id']; ?>">
                                                        <button type="submit" class="btn btn-outline-danger waves-effect btn-xs waves-light" title="Delete Request"><span class="mdi mdi-delete"></span></button>
                                                    </form>
                                                </td>
                                            <?php } else { ?>
                                                <td style="text-align: center; white-space: nowrap;">
                                                    <span class="text-muted"></span>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                <?php }
                                } ?>
                            </tbody>
                        </table>

                        <!-- Display message if no access requests are available -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>