<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/Effort_Tracker'); ?>">Effort Tracker</a></li>
                </ol>
            </div>
            <h4 class="page-title">Effort Edit</h4>
        </div>
    </div>
</div>
<?php
// 1. Get current date information
$currentDate = new DateTime();
// 2. Calculate 2 weeks ago (Minimum week allowed)
$minDate = clone $currentDate;
$minDate->modify('-1 weeks');
$minWeekAttr = $minDate->format('Y-\WW'); // Output format: YYYY-Www
// 3. Calculate next week (Maximum week allowed)
$maxDate = clone $currentDate;
$maxDate->modify('0 week');
$maxWeekAttr = $maxDate->format('Y-\WW'); // Output format: YYYY-Www
?>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?php echo base_url('Project_Manage/Effort_Tracker/Update_Effort'); ?>"><?= csrf_field() ?>
                    <div class="row mb-3">
                        <div class="col-md-12 mb-3">
                            <label for="year" class="form-label">Select Date</label>
                            <input class="form-control"
                                type="week"
                                id="weekDate"
                                name="weekDate"
                                min="<?php echo $minWeekAttr; ?>"
                                max="<?php echo $maxWeekAttr; ?>"
                                value="<?php echo isset($effort_data) ? $effort_data[0]['work_week'] : date('Y-\WW'); ?>"
                                required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="year" class="form-label">Select Project</label>
                            <select name="project_id" id="project_id" class="form-control" required>
                                <option value="">Select Project</option>
                                <?php if (!empty($projects) && is_array($projects)) { ?>
                                    <?php foreach ($projects as $project) { ?>
                                        <option value="<?php echo $project['projectid']; ?>"
                                            <?php if (isset($effort_data) && isset($effort_data[0]) && $project['projectid'] == $effort_data[0]['projectid']) {
                                                echo 'selected';
                                            } ?>><?php echo $project['projectname']; ?></option>
                                    <?php } ?>
                                    <option value="1" <?php if (isset($effort_data) && isset($effort_data[0]) && $effort_data[0]['projectid'] == 1) echo 'selected'; ?>>Others (Non-Project Work)</option>
                                    <option value="2" <?php if (isset($effort_data) && isset($effort_data[0]) && $effort_data[0]['projectid'] == 2) echo 'selected'; ?>>Leave</option>
                                <?php } else { ?>
                                    <option value="" disabled>No projects available</option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="month" class="form-label">Hours</label>
                                <input type="number" name="effort_hours" id="effort_hours" class="form-control" value="<?php echo isset($effort_data) && isset($effort_data[0]) ? floor($effort_data[0]['effort']) : 0; ?>" min="0" step="1" max="40" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="month" class="form-label">Minutes</label>
                                <select name="effort_minutes" id="effort_minutes" class="form-control" required>

                                    <option value="0" <?php if (isset($effort_data) && isset($effort_data[0]) && $effort_data[0]['effort'] - round($effort_data[0]['effort']) == 0) echo 'selected'; ?>>0 Minutes</option>
                                    <option value=".25" <?php if (isset($effort_data) && isset($effort_data[0]) && $effort_data[0]['effort'] - round($effort_data[0]['effort']) == 0.25) echo 'selected'; ?>>15 Minutes</option>
                                    <option value=".5" <?php if (isset($effort_data) && isset($effort_data[0]) && $effort_data[0]['effort'] - round($effort_data[0]['effort']) == 0.5) echo 'selected'; ?>>30 Minutes</option>
                                    <option value=".75" <?php if (isset($effort_data) && isset($effort_data[0]) && $effort_data[0]['effort'] - round($effort_data[0]['effort']) == 0.75) echo 'selected'; ?>>45 Minutes</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="month" class="form-label">Description</label>
                            <input type="text" name="description" id="description" class="form-control" value="<?php echo isset($effort_data) && isset($effort_data[0]) ? $effort_data[0]['description'] : ''; ?>" placeholder="Description" required>
                        </div>
                        <div class="col-md-4 align-self-end">
                            <input type="hidden" name="pe_id" value="<?php echo isset($effort_data) && isset($effort_data[0]) ? $effort_data[0]['pe_id'] : ''; ?>">
                            <button type="submit" class="btn btn-outline-warning waves-effect btn-sm waves-light">Update Effort</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer text-muted">
                <p class="mb-0">Note: Please ensure that the effort hours and minutes are entered correctly. The total effort will be calculated as hours + minutes (in decimal format).</p>
            </div>
        </div>
    </div>
</div>