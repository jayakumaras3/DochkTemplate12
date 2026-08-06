<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/Effort_Tracker'); ?>">Effort Tracker</a></li>
                </ol>
            </div>
            <h4 class="page-title">Effort Dashboard</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <h5 class="text-uppercase bg-light p-2 mt-0 mb-1">Effort <?php
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

                    <div class="col-md-6 mb-1">
                        <form method="post" action="<?php echo base_url('Project_Manage/Effort_Tracker/All_data'); ?>">
                            <div class="row">
                                <div class="col-md-6 mb-1">
                                    <input type="week" id="week" name="weekDate" class="form-control" value="<?php echo isset($selected_week) ? $selected_week : ''; ?>" required>
                                </div>
                                <div class="col-md-6 mt-1">
                                    <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">Change Week</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <table class="table table-bordered table-striped dt-responsive nowrap w-100" id="my_team_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Active</th>
                            <th>Approved</th>
                            <th>Manager</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sn = 1;
                        $toHoursMinutes = function ($decimalTime) {
                            $hours = floor($decimalTime);
                            $minutes = ($decimalTime - $hours) * 60;
                            return sprintf("%d:%02d", $hours, $minutes); // Outputs: 4:30
                        };
                        if (!empty($my_team) && is_array($my_team)) { ?>
                            <?php foreach ($my_team as $member) {
                                if ($member['manager_id'] == 1202 || $member['manager_id'] == 1135 || $member['manager_id'] == 1138) {
                                    continue;
                                }
                                if ($member['id_user'] == 1 || $member['id_user'] == 1135 || $member['id_user'] == 1138) {
                                    continue;
                                }
                            ?>
                                <tr>
                                    <td><?php echo $sn++; ?></td>
                                    <td><?php

                                        echo $member['name'];

                                        echo " ";

                                        echo $member['last_name'];


                                        ?></td>

                                    <td style="text-align: right;"><?php echo $toHoursMinutes($member['active_effort']); ?></td>
                                    <td style="text-align: right;"><?php echo $toHoursMinutes($member['approved_effort']); ?></td>
                                    <td><?php echo $member['manager_name']; ?></td>

                                    <td>
                                        <form method="post" action="<?php echo base_url('Project_Manage/Effort_Tracker/view_member_effort'); ?>">
                                            <input type="hidden" name="member_id" value="<?php echo $member['id_user']; ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-eye"></span></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="7" style="text-align: center; font-weight: bold;">No team members found.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="text-uppercase bg-light p-2 mt-0 mb-1">Total Effort by Week (Active &amp; Approved)</h5>
                <table class="table table-bordered table-striped dt-responsive nowrap w-100" id="weekly_totals_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Week</th>
                            <th>Employees</th>
                            <th>General Effort</th>
                            <th>Project Effort</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $wn = 1;
                        if (!empty($weekly_totals) && is_array($weekly_totals)) { ?>
                            <?php foreach ($weekly_totals as $week) { ?>
                                <tr>
                                    <td><?php echo $wn++; ?></td>
                                    <td><?php echo $week['work_week'];
                                    echo ' | ';


                                        if (isset($week['work_week'])) {

                                            $weekStr = $week['work_week'];

                                            // Create DateTime object from week string
                                            $date = new DateTime($weekStr);

                                            // Get the start and end dates of the selected week
                                            $startDate = $date->format('M-d'); // First day of the week
                                            $date->modify('+6 days');
                                            $endDate = $date->format('M-d');   // Last day of the week

                                            // echo "Selected Week: $weekStr <br>";
                                            echo "$startDate to $endDate";
                                        }
                                        ?></td>
                                    <td style="text-align: right;"><?php echo $week['employee_count']; ?></td>
                                    <td style="text-align: right;"><?php echo $toHoursMinutes($week['general_effort']); ?></td>
                                    <td style="text-align: right;"><?php echo $toHoursMinutes($week['project_effort']); ?></td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="5" style="text-align: center; font-weight: bold;">No effort data found.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>