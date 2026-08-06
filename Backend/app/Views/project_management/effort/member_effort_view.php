<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <?php if (session()->get('id_user') == 1) { ?>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/Effort_Tracker/All_data'); ?>">All Effort</a></li>

                    <?php } ?>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/Effort_Tracker/Team_data'); ?>">Team Effort</a></li>
                </ol>
            </div>
            <h4 class="page-title">Effort Tracker | <?php echo isset($member_info) ? $member_info['name'] . ' ' . $member_info['last_name'] : ''; ?></h4>
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
        <div class="card border border-danger">
            <div class="card-body">
                <h5 class="text-uppercase bg-light p-2 mt-0 mb-3">Add Effort</h5>
                <form method="POST" action="<?php echo base_url('Project_Manage/Effort_Tracker/AddEffort_for_member'); ?>"><?= csrf_field() ?>
                    <div class="row mb-3">
                        <div class="col-md-12 mb-3">
                            <label for="year" class="form-label">Select Date</label>
                            <input class="form-control"
                                type="week"
                                id="weekDate"
                                name="weekDate"
                                min="<?php echo $minWeekAttr; ?>"
                                max="<?php echo $maxWeekAttr; ?>"
                                value="<?php echo isset($selected_week) ? $selected_week : date('Y-\WW'); ?>"
                                required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="year" class="form-label">Select Project</label>
                            <select name="project_id" id="project_id" class="form-control" required>
                                <option value="">Select Project</option>
                                <?php if (!empty($projects) && is_array($projects)) { ?>
                                    <?php foreach ($projects as $project) { ?>
                                        <option value="<?php echo $project['projectid']; ?>"><?php echo $project['projectname']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                                <option value="1">Others (Non-Project Work)</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="month" class="form-label">Hours</label>
                            <input type="number" name="effort_hours" id="effort_hours" class="form-control" min="0" step="1" max="40" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="month" class="form-label">Minutes</label>
                            <select name="effort_minutes" id="effort_minutes" class="form-control" required>
                                <option value="0">0</option>
                                <option value=".25">15 Minutes</option>
                                <option value=".5">30 Minutes</option>
                                <option value=".75">45 Minutes</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="month" class="form-label">Description</label>
                            <input type="text" name="description" id="description" class="form-control" placeholder="Description" required>
                        </div>
                        <div class="col-md-12 align-self-end">
                            <input type="hidden" name="member_id" value="<?php echo isset($member_info) ? $member_info['id_user'] : ''; ?>">
                            <button type="submit" class="btn btn-outline-danger waves-effect btn-sm waves-light">Add Effort for <?php echo isset($member_info) ? $member_info['name'] . ' ' . $member_info['last_name'] : ''; ?></button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer text-muted">
                <p class="mb-0">Note: Please ensure that the effort hours and minutes are entered correctly. The total effort will be calculated as hours + minutes (in decimal format).</p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="row ">
            <div class="col-md-12">
                <div class="card border border-danger">
                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col-md-6">
                                <h5 class="text-uppercase bg-light p-2 mt-0 mb-3">Effort-Week:
                                    <?php
                                    if (isset($selected_week)) {

                                        $weekStr = $selected_week;

                                        // Create DateTime object from week string
                                        $date = new DateTime($weekStr);

                                        // Get the start and end dates of the selected week
                                        $startDate = $date->format('M-d'); // First day of the week
                                        $date->modify('+6 days');
                                        $endDate = $date->format('M-d');   // Last day of the week

                                        // echo "Selected Week: $weekStr <br>";
                                        echo " | $startDate to $endDate";
                                    }
                                    ?></h5>
                            </div>
                            <div class="col-md-6 text-end">
                                <form method="POST" action="<?php echo base_url('Project_Manage/Effort_Tracker/view_member_effort'); ?>">
                                    <div class="row mb-1">
                                        <div class="col-md-6">
                                            <input type="week" id="weekDate" name="weekDate" class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-outline-info waves-effect btn-sm waves-light">Change Week</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <table class="table table-centered  table-bordered table-striped" id="products-datatable" data-selected-week="<?php echo isset($selected_week) ? $selected_week : date('Y-\WW'); ?>">
                            <thead class="table-light">

                                <tr>
                                    <th>S.No</th>
                                    <th>Project Name</th>
                                    <th>Effort Hours</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total_effort = 0;
                                if (!empty($effort_data)) {
                                    $sno = 1;

                                ?>
                                    <?php foreach ($effort_data as $effort) {
                                        $status = $effort['status'];
                                        if ($status != 3 && $status != 4) { // Exclude deleted records
                                            $total_effort = $total_effort + $effort['effort'];
                                        } ?>
                                        <tr>
                                            <td><?php echo $sno++; ?></td>
                                            <td>
                                                <?php
                                                if ($effort['projectid'] == 1) {
                                                    echo "Others (Non-Project Work)";
                                                } elseif ($effort['projectid'] == 2) {
                                                    echo "Leave";
                                                } else {
                                                    echo $effort['project_name'];
                                                }
                                                ?>
                                            </td>
                                            <td style="text-align: right;"><?php


                                                                            if ($effort['effort'] > 0) {
                                                                                $hours = floor($effort['effort']);
                                                                                $minutes = ($effort['effort'] - $hours) * 60;
                                                                                $formattedTime = sprintf("%d:%02d", $hours, $minutes);
                                                                                echo $formattedTime; // Outputs: 4:30
                                                                            } else {
                                                                                echo "0:00";
                                                                            }
                                                                            ?></td>


                                            <td><?php echo $effort['description']; if($effort['pm_comment']) echo " PM : (" . $effort['pm_comment'] . ")"; ?></td>


                                            <?php if ($effort['status'] == 1) { ?>
                                                <td>
                                                    <form method="POST" action="<?php echo base_url('Project_Manage/Effort_Tracker/Delete_effort'); ?>" onsubmit="return confirm('Are you sure you want to delete this effort entry?');"><?= csrf_field() ?>
                                                        <input type="hidden" name="pe_id" value="<?php echo $effort['pe_id']; ?>">
                                                        <button type="submit" class="btn btn-outline-danger waves-effect btn-xs waves-light"><span class="mdi mdi-delete"></span></button>
                                                    </form>
                                                </td>
                                            <?php } else { ?>
                                                <td style="text-align: center;">
                                                    <?php
                                                    switch ($status) {
                                                        case 1:
                                                            echo '<span class="badge bg-soft-secondary text-secondary p-1">Active</span>';
                                                            break;
                                                        case 2:
                                                            echo '<span class="badge bg-soft-success text-success p-1">Approved</span>';
                                                            break;
                                                        case 3:
                                                            echo '<span class="badge bg-soft-danger text-danger p-1">TL Reject</span>';
                                                            break;
                                                        case 4:
                                                            echo '<span class="badge bg-soft-warning text-warning p-1">PM Reject</span>';
                                                            break;
                                                        case 10:
                                                            echo '<span class="badge bg-soft-dark text-dark p-1">Deleted</span>';
                                                            break;
                                                    }
                                                    ?>
                                                </td>
                                            <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                                <tr>
                                    <td colspan="2" style="text-align: right;"><strong>Total Effort:</strong></td>
                                    <td style="text-align: right;"><strong><?php

                                                                            if ($total_effort > 0) {
                                                                                if ($total_effort > 40) {
                                                                                    echo '<span class="text-danger">'; // Start red color for total effort exceeding 40
                                                                                }
                                                                                $hours = floor($total_effort);
                                                                                $minutes = ($total_effort - $hours) * 60;
                                                                                $formattedTime = sprintf("%d:%02d", $hours, $minutes);
                                                                                echo $formattedTime;
                                                                                if ($total_effort > 40) {
                                                                                    echo '</span>'; // End red color
                                                                                }
                                                                            } else {
                                                                                echo "0:00";
                                                                            }
                                                                            ?></strong></td>
                                    <td colspan="5"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card border border-danger">
                    <div class="card-header">
                        <h4 class="header-title">Last 10 Weeks Effort Summary</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-centered  table-striped" id="weekly_totals_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Week</th>
                                    <th>Total Effort</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $wn = 1;
                                if (!empty($weekly_totals) && is_array($weekly_totals)) {
                                ?>
                                    <?php foreach ($weekly_totals as $week) { ?>
                                        <tr>
                                            <td><?php echo $wn++; ?></td>
                                            <td>
                                                <?php
                                                $weekStr = $week['work_week'];
                                                $weekDate = new DateTime($weekStr);
                                                $weekStart = $weekDate->format('M-d');
                                                $weekDate->modify('+6 days');
                                                $weekEnd = $weekDate->format('M-d');
                                                echo "$weekStr | $weekStart to $weekEnd";
                                                ?>
                                            </td>
                                            <td style="text-align: right;">
                                                <?php
                                                $weekTotal = (float) $week['total_effort'];
                                                if ($weekTotal > 0) {
                                                    $hours = floor($weekTotal);
                                                    $minutes = ($weekTotal - $hours) * 60;
                                                    echo sprintf("%d:%02d", $hours, $minutes);
                                                } else {
                                                    echo "0:00";
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="3" style="text-align: center; font-weight: bold;">No effort data found.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>