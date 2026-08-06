<style>
    /* Same rounded-corner + shadow + table look as other redesigned Project_Manage/etrack pages
       (e.g. Effort_Tracker, approve_access_view.php, etrack/claims). */
    .team-effort-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .team-effort-card table thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
    }

    [data-bs-theme="dark"] .team-effort-card table thead th {
        border-bottom-color: #36404a;
        color: #cedeef;
    }

    .team-effort-card table tbody td {
        vertical-align: middle;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/Effort_Tracker'); ?>">Effort Tracker</a></li>
                </ol>
            </div>
            <h4 class="page-title">Team Effort</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="card team-effort-card">
            <div class="card-body">
                <form method="post" action="<?php echo base_url('Project_Manage/Effort_Tracker/Team_data'); ?>">
                    <div class="form-group mb-3">
                        <label for="week">Select Week:</label>
                        <input type="week" id="week" name="weekDate" class="form-control" value="<?php echo isset($selected_week) ? $selected_week : ''; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-outline-success rounded-pill waves-effect btn-xs waves-light">Change Week</button>
                </form>
            </div>
        </div>
        <div class="card team-effort-card mt-3">
            <div class="card-body">
                <h5 class="text-uppercase bg-light p-2 mt-0 mb-3">Team Data <?php
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
                <table class="table" id="my_team_table">
                    <thead>
                        <tr class="table-light">
                            <th>Employee</th>
                            <th>Effort</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($my_team) && is_array($my_team)) { ?>
                            <?php
                            $total_effort = 0;
                            foreach ($my_team as $member) { ?>
                                <tr>
                                    <td><?php
                                        if (strlen($member['name']) > 10) {
                                            echo substr($member['name'], 0, 10) . '...';
                                        } else {
                                            echo $member['name'];
                                        }
                                        echo " ";
                                        if (strlen($member['last_name']) > 5) {
                                            echo substr($member['last_name'], 0, 5) . '...';
                                        } else {
                                            echo $member['last_name'];
                                        }

                                        ?></td>
                                    <td style="text-align: right;"><?php
                                                                    if ($member['total_effort'] > 0) {
                                                                        $total_effort += $member['total_effort'];
                                                                        $decimalTime = $member['total_effort'];
                                                                        $hours = floor($decimalTime);
                                                                        $minutes = ($decimalTime - $hours) * 60;
                                                                        $formattedTime = sprintf("%d:%02d", $hours, $minutes);
                                                                        echo $formattedTime; // Outputs: 4:30
                                                                    }
                                                                    ?></td>
                                    <td>
                                        <form method="post" action="<?php echo base_url('Project_Manage/Effort_Tracker/view_member_effort'); ?>">
                                            <input type="hidden" name="member_id" value="<?php echo $member['id_user']; ?>">
                                            <button type="submit" class="btn btn-outline-primary rounded-pill waves-effect btn-xs waves-light"><span class="mdi mdi-eye"></span></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td><strong>Total Effort</strong></td>
                                <td style="text-align: right;"><strong><?php
                                                                        $hours = floor($total_effort);
                                                                        $minutes = ($total_effort - $hours) * 60;
                                                                        $formattedTime = sprintf("%d:%02d", $hours, $minutes);
                                                                        echo $formattedTime; // Outputs: 4:30
                                                                        ?></strong></td>
                                <td></td>
                            </tr>
                        <?php } else { ?>
                            <tr>
                                <td colspan="2">No team members found.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Table to show employee effort that need manager's approval -->
    <div class="col-md-9">
        <div class="card team-effort-card">
            <div class="card-body">
                <h5 class="text-uppercase bg-light p-2 mt-0 mb-3 d-flex justify-content-between align-items-center">
                    Items for Approval
                    <?php if (!empty($effort_data) && is_array($effort_data)) { ?>
                        <button type="button" id="approve_all_btn" class="btn btn-danger rounded-pill waves-effect btn-xs waves-light">Approve All</button>
                    <?php } ?>
                </h5>
                <table class="table" id="project_effort_table">
                    <thead>
                        <tr class="table-light">
                            <th>S.No</th>
                            <th>Employee</th>
                            <th>Project</th>
                            <th>Hours</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sno = 1; // Initialize serial number
                        if (!empty($effort_data) && is_array($effort_data)) {
                            $currentweek = 1; // Get the current week number
                        ?>
                            <?php foreach ($effort_data as $data) {
                                if ($currentweek == $data['work_week']) {
                                    $rowClass = ''; // No
                                } else {
                                    $rowClass = ''; // Highlight current week with green
                                    $weekRange = '';
                                    if (isset($data['work_week'])) {

                                        $weekStr = $data['work_week'];

                                        // Create DateTime object from week string
                                        $date = new DateTime($weekStr);

                                        // Get the start and end dates of the selected week
                                        $startDate = $date->format('M-d'); // First day of the week
                                        $date->modify('+6 days');
                                        $endDate = $date->format('M-d');   // Last day of the week

                                        // echo "Selected Week: $weekStr <br>";
                                        $weekRange = " | $startDate to $endDate";
                                    }
                                    echo '<tr class="table-success"><td colspan="6" style="text-align: center; font-weight: bold;">Week ' . $data['work_week'] . $weekRange . '</td></tr>';
                                }
                            ?>
                                <tr class="<?php echo $rowClass; ?>" data-pe-id="<?php echo $data['pe_id']; ?>">
                                    <td><?php echo $sno++; ?></td>
                                    <td><?php echo $data['fname']; ?></td>
                                    <td><?php echo $data['project_name']; ?></td>
                                    <td style="text-align: right;"><?php
                                                                    $decimalTime = $data['effort'];
                                                                    $hours = floor($decimalTime);
                                                                    $minutes = ($decimalTime - $hours) * 60;
                                                                    $formattedTime = sprintf("%d:%02d", $hours, $minutes);
                                                                    echo $formattedTime; // Outputs: 4:30


                                                                    ?></td>
                                    <td><?php echo $data['description']; ?></td>
                                    <td>
                                        <span class="d-flex justify-content-center">
                                            <form method="post" class="mng-response-form" action="<?php echo base_url('Project_Manage/Effort_Tracker/mng_response'); ?>"><?= csrf_field() ?>
                                                <input type="hidden" name="pe_id" value="<?php echo $data['pe_id']; ?>">
                                                <input type="hidden" name="status" value="2">
                                                <button type="submit" class="btn btn-outline-success rounded-pill waves-effect btn-xs waves-light"><i class="fa fa-check"></i></button>
                                            </form>
                                            &nbsp; &nbsp; &nbsp; &nbsp;
                                            <form method="post" class="mng-response-form" action="<?php echo base_url('Project_Manage/Effort_Tracker/mng_response'); ?>"><?= csrf_field() ?>
                                                <input type="hidden" name="pe_id" value="<?php echo $data['pe_id']; ?>">
                                                <input type="hidden" name="status" value="3">
                                                <button type="submit" class="btn btn-outline-danger rounded-pill waves-effect btn-xs waves-light"><i class="fa fa-times"></i></button>
                                            </form>
                                        </span>
                                    </td>
                                </tr>

                            <?php
                                $currentweek = $data['work_week']; // Get the current week number
                            } ?>


                        <?php } else {
                            echo '<tr><td colspan="6" style="text-align: center; font-weight: bold;">Great Job! Nothing to approve.</td></tr>';
                        } ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
    <!-- End of Table to show employee effort that need manager's approval -->
</div>
<script>
    var effortCsrfName = '<?= csrf_token() ?>';
    var effortCsrfHash = '<?= csrf_hash() ?>';

    $(document).on('submit', '.mng-response-form', function(event) {
        event.preventDefault();

        var form = $(this);
        var row = form.closest('tr');
        var buttons = row.find('button[type="submit"]');

        buttons.prop('disabled', true);

        // The token embedded at page load can go stale (e.g. if it was already
        // used by another form on this page) before this form is submitted, so
        // always send the latest known hash rather than the static one.
        form.find('input[name="' + effortCsrfName + '"]').val(effortCsrfHash);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.csrfHash) {
                    effortCsrfHash = response.csrfHash;
                    $('.mng-response-form input[name="' + effortCsrfName + '"]').val(effortCsrfHash);
                }

                if (response.status === 'OK') {
                    row.fadeOut(200, function() {
                        $(this).remove();
                    });
                } else {
                    alert(response.message || 'Something went wrong. Please contact Site Admin!');
                    buttons.prop('disabled', false);
                }
            },
            error: function(xhr) {
                if (xhr.status === 403) {
                    alert('Your session security token has expired. The page will now reload.');
                    window.location.reload();
                    return;
                }
                alert('Request failed. Please try again.');
                buttons.prop('disabled', false);
            }
        });
    });

    $(document).on('click', '#approve_all_btn', function(event) {
        var btn = $(this);
        var rows = $('#project_effort_table tbody tr[data-pe-id]');
        var peIds = rows.map(function() {
            return $(this).data('pe-id');
        }).get();

        if (peIds.length === 0) {
            return;
        }

        if (!confirm('This will automatically approve ' + peIds.length + ' record(s). Continue?')) {
            return;
        }

        btn.prop('disabled', true);

        $.ajax({
            url: '<?php echo base_url('Project_Manage/Effort_Tracker/mng_bulk_approve'); ?>',
            type: 'POST',
            data: {
                pe_ids: peIds,
                [effortCsrfName]: effortCsrfHash
            },
            dataType: 'json',
            success: function(response) {
                if (response.csrfHash) {
                    effortCsrfHash = response.csrfHash;
                    $('.mng-response-form input[name="' + effortCsrfName + '"]').val(effortCsrfHash);
                }

                if (response.status === 'OK') {
                    alert(response.message);
                    window.location.reload();
                } else {
                    alert(response.message || 'Something went wrong. Please contact Site Admin!');
                    btn.prop('disabled', false);
                }
            },
            error: function(xhr) {
                if (xhr.status === 403) {
                    alert('Your session security token has expired. The page will now reload.');
                    window.location.reload();
                    return;
                }
                alert('Request failed. Please try again.');
                btn.prop('disabled', false);
            }
        });
    });
</script>