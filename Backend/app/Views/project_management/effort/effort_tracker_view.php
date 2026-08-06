<style>
    /* Same rounded-corner + shadow + table look as other redesigned Project_Manage/etrack pages
       (e.g. approve_access_view.php, etrack/claims). */
    .effort-tracker-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .effort-tracker-card table thead th {
        border-bottom: 2px solid #eef2f7;
        color: #6c757d;
        font-weight: 700;
    }

    [data-bs-theme="dark"] .effort-tracker-card table thead th {
        border-bottom-color: #36404a;
        color: #cedeef;
    }

    .effort-tracker-card table tbody td {
        vertical-align: middle;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <?php if (session()->get('report_to_you') == 2) { ?>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/Effort_Tracker/Team_data'); ?>">Team Data</a></li>
                        <?php if (session()->get('id_user') == 1) { ?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/Effort_Tracker/All_data'); ?>">All Data</a></li>
                        <?php } ?>
                    </ol>
                </div>
            <?php } ?>
            <h4 class="page-title">Effort Tracker</h4>
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
        <div class="card effort-tracker-card">
            <div class="card-body">
                <h5 class="text-uppercase bg-light p-2 mt-0 mb-3">Add Effort</h5>
                <div id="add-effort-alert" class="alert d-none" role="alert"></div>
                <form method="POST" id="add-effort-form" action="<?php echo base_url('Project_Manage/Effort_Tracker/AddEffort'); ?>"><?= csrf_field() ?>
                    <div class="row mb-3">
                        <div class="col-md-12 mb-3">
                            <label for="year" class="form-label">Select Week <span class="text-danger">*</span></label>
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
                            <label for="year" class="form-label">Select Project <span class="text-danger">*</span></label>

                            <select name="project_id" id="project_id" class="form-control mb-1" required>
                                <option value="">Select Project</option>
                                <?php if (!empty($projects) && is_array($projects)) { ?>
                                    <?php foreach ($projects as $project) { ?>
                                        <option value="<?php echo $project['projectid']; ?>"><?php echo $project['projectname']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                                <option value="1">Others (Non-Project Work)</option>
                            </select>
                            <small class="form-text text-muted"><a class="text-reset" href="<?php echo base_url('Project_Manage/Effort_Tracker/RequestAccess'); ?>">Request Project Access</a></small>

                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="month" class="form-label">Hours <span class="text-danger">*</span></label>
                            <input type="number" name="effort_hours" id="effort_hours" class="form-control" min="0" step="1" max="40" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="month" class="form-label">Minutes <span class="text-danger">*</span></label>
                            <select name="effort_minutes" id="effort_minutes" class="form-control" required>
                                <option value="0">0</option>
                                <option value=".25">15 Minutes</option>
                                <option value=".5">30 Minutes</option>
                                <option value=".75">45 Minutes</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="month" class="form-label">Description</label>
                            <input type="text" name="description" id="description" class="form-control" placeholder="Description">
                            <small class="form-text text-muted">Maximum 20 words.</small>
                        </div>
                        <div class="col-md-4 align-self-end">
                            <button type="submit" class="btn btn-outline-primary rounded-pill waves-effect btn-sm waves-light">Save</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer text-muted">
                <p class="mb-0">Note:
                <ol>
                    <li><span class="text-danger">*</span> indicates required fields.</li>
                    <li>Ensure effort hours and minutes are entered accurately.</li>
                    <li>Non-project data entry is not required.</li>
                    <li>Employees must update last week’s data by Monday.</li>
                    <li>Team leads must approve the data by Tuesday.</li>
                </ol>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <!-- Table to show the effort data -->
        <div class="row ">
            <div class="col-md-12">
                <div class="card effort-tracker-card">

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
                                <form method="POST" action="<?php echo base_url('Project_Manage/Effort_Tracker'); ?>">
                                    <div class="row mb-1">
                                        <div class="col-md-6">
                                            <input type="week" id="weekDate" name="weekDate" class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-outline-info rounded-pill waves-effect btn-sm waves-light">Change Week</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <table class="table table-centered" id="products-datatable" data-selected-week="<?php echo isset($selected_week) ? $selected_week : date('Y-\WW'); ?>">
                            <thead>
                                <tr class="table-light">
                                    <th style="width: 50px;">S.No</th>
                                    <th>Project Name</th>
                                    <th style="text-align: right; width: 180px;">Effort</th>
                                    <th>Description</th>
                                    <th style="width: 100px;">Action</th>
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
                                        }

                                        $hours = floor($effort['effort']);
                                        $minutes = ($effort['effort'] - $hours) * 60;
                                        $formattedTime = $effort['effort'] > 0 ? sprintf("%d:%02d", $hours, $minutes) : "0:00";
                                        $minutesFraction = round($minutes / 60, 2);

                                        // Inline editing mirrors the Delete button's availability: only Active
                                        // entries within the current/previous work week may be changed.
                                        $canEdit = $status == 1 && isset($selected_week) && ($selected_week == date('Y-\WW') || $selected_week == date('Y-\WW', strtotime('-1 week')));
                                    ?>
                                        <tr data-pe-id="<?php echo $effort['pe_id']; ?>" data-effort="<?php echo $effort['effort']; ?>">
                                            <td><?php echo $sno++; ?></td>
                                            <td>
                                                <?php
                                                echo $effort['project_name'];
                                                ?>
                                            </td>
                                            <td style="text-align: right;">
                                                <?php if ($canEdit) { ?>
                                                    <input type="number" class="form-control form-control-sm d-inline-block effort-hours-input" style="width:60px;" min="0" max="40" step="1" value="<?php echo (int) $hours; ?>">
                                                    <select class="form-control form-control-sm d-inline-block effort-minutes-input" style="width:75px;">
                                                        <option value="0" <?php echo $minutesFraction == 0 ? 'selected' : ''; ?>>0 min</option>
                                                        <option value=".25" <?php echo $minutesFraction == .25 ? 'selected' : ''; ?>>15 min</option>
                                                        <option value=".5" <?php echo $minutesFraction == .5 ? 'selected' : ''; ?>>30 min</option>
                                                        <option value=".75" <?php echo $minutesFraction == .75 ? 'selected' : ''; ?>>45 min</option>
                                                    </select>
                                                <?php } else { ?>
                                                    <?php echo $formattedTime; ?>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php if ($canEdit) { ?>
                                                    <input type="text" class="form-control form-control-sm effort-description-input" value="<?php echo esc($effort['description'], 'attr'); ?>">
                                                <?php } else { ?>
                                                    <?php echo $effort['description']; ?>
                                                <?php } ?>
                                                <?php if (strlen($effort['pm_comment']) > 0) {
                                                    echo "<br><small class='text-muted'>PM Comment: " . esc($effort['pm_comment'], 'attr') . "</small>";
                                                } ?>
                                            </td>


                                            <?php if ($effort['status'] == 1) { ?>
                                                <?php if ($canEdit) { ?>

                                                    <td style="text-align: center; white-space: nowrap;">
                                                        <button type="button" class="btn btn-outline-success rounded-pill waves-effect btn-xs waves-light effort-save-btn" title="Save Effort"><span class="mdi mdi-content-save"></span></button>
                                                        &nbsp;&nbsp;
                                                        <form method="POST" action="<?php echo base_url('Project_Manage/Effort_Tracker/Delete_effort'); ?>" onsubmit="return confirm('Are you sure you want to delete this effort entry?');" style="display:inline-block;"><?= csrf_field() ?>
                                                            <input type="hidden" name="pe_id" value="<?php echo $effort['pe_id']; ?>">
                                                            <button type="submit" class="btn btn-outline-danger rounded-pill waves-effect btn-xs waves-light" title="Delete Effort"><span class="mdi mdi-delete"></span></button>
                                                        </form>
                                                    </td>
                                                <?php } else { ?>
                                                    <td style="text-align: center;"></td>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <td style="text-align: center;">
                                                    <?php
                                                    switch ($status) {
                                                        case 1:
                                                            echo '<span class="badge bg-soft-secondary text-secondary rounded-pill p-1 px-2">Active</span>';
                                                            break;
                                                        case 2:
                                                            echo '<span class="badge bg-soft-success text-success rounded-pill p-1 px-2">Approved</span>';
                                                            break;
                                                        case 3:
                                                            echo '<span class="badge bg-soft-danger text-danger rounded-pill p-1 px-2">TL Reject</span>';
                                                            break;
                                                        case 4:
                                                            echo '<span class="badge bg-soft-warning text-warning rounded-pill p-1 px-2">PM Reject</span>';
                                                            break;
                                                        case 10:
                                                            echo '<span class="badge bg-soft-dark text-dark rounded-pill p-1 px-2">Deleted</span>';
                                                            break;
                                                    }
                                                    ?>
                                                </td>
                                            <?php } ?>

                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                                <tr id="effort-total-row">
                                    <td colspan="2" style="text-align: right;"><strong>Total Effort:</strong></td>
                                    <td id="total-effort-cell" data-total="<?php echo $total_effort; ?>" style="text-align: right;"><strong id="total-effort-text"><?php
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

                                    <td colspan="2"></td>
                                </tr>
                            </tbody>

                        </table>
                        <div class="card-footer text-muted">
                            <p class="text-muted">Note:
                            <ol>
                                <li>Total effort exceeding 40 hours is highlighted in red.</li>
                                <li>Ensure all effort entries are accurate and up-to-date.</li>
                                <li>Effort once approved by the team lead cannot be edited or deleted.</li>
                                <li>Effort entries for the current and previous week can be edited or deleted until approved.</li>
                                <li>If you edit the effort hours or minutes, remember to click the save button to update the total.</li>
                            </ol>
                            </p>
                        </div>
                        <!-- Display message if no effort data is available -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var csrfName = '<?= csrf_token() ?>';
    var csrfHash = '<?= csrf_hash() ?>';
    var deleteEffortUrl = '<?= base_url('Project_Manage/Effort_Tracker/Delete_effort') ?>';

    function renderTotalEffort(total) {
        var hours = Math.floor(total);
        var minutes = Math.round((total - hours) * 60);
        var formatted = hours + ':' + (minutes < 10 ? '0' : '') + minutes;
        return total > 40 ? '<span class="text-danger">' + formatted + '</span>' : formatted;
    }

    function applyTotalDelta(delta) {
        var totalCell = $('#total-effort-cell');
        var newTotal = (parseFloat(totalCell.data('total')) || 0) + delta;
        totalCell.data('total', newTotal);
        $('#total-effort-text').html(renderTotalEffort(newTotal));
    }

    function buildEffortRow(data) {
        var row = $('<tr>').attr('data-pe-id', data.pe_id).attr('data-effort', data.effort).addClass('table-success');

        row.append($('<td>').text(data.sno));
        row.append($('<td>').text(data.project_name));

        var hoursInput = $('<input>').attr({
            type: 'number',
            min: 0,
            max: 40,
            step: 1
        }).addClass('form-control form-control-sm d-inline-block effort-hours-input').css('width', '60px').val(data.hours);

        var minutesSelect = $('<select>').addClass('form-control form-control-sm d-inline-block effort-minutes-input').css('width', '75px');
        [
            ['0', '0 min'],
            ['.25', '15 min'],
            ['.5', '30 min'],
            ['.75', '45 min']
        ].forEach(function(opt) {
            var optionEl = $('<option>').attr('value', opt[0]).text(opt[1]);
            if (parseFloat(opt[0]) === data.minutesFraction) {
                optionEl.prop('selected', true);
            }
            minutesSelect.append(optionEl);
        });

        row.append($('<td>').css('text-align', 'right').append(hoursInput).append(' ').append(minutesSelect));

        var descInput = $('<input>').attr('type', 'text').addClass('form-control form-control-sm effort-description-input').val(data.description);
        row.append($('<td>').append(descInput));

        var saveBtn = $('<button>').attr({
            type: 'button',
            title: 'Save Effort'
        }).addClass('btn btn-outline-success rounded-pill waves-effect btn-xs waves-light effort-save-btn').html('<span class="mdi mdi-content-save"></span>');

        var deleteForm = $('<form>').attr({
            method: 'POST',
            action: deleteEffortUrl
        }).css('display', 'inline-block').on('submit', function() {
            return confirm('Are you sure you want to delete this effort entry?');
        });
        deleteForm.append($('<input>').attr({
            type: 'hidden',
            name: csrfName
        }).val(csrfHash));
        deleteForm.append($('<input>').attr({
            type: 'hidden',
            name: 'pe_id'
        }).val(data.pe_id));
        deleteForm.append(
            $('<button>').attr({
                type: 'submit',
                title: 'Delete Effort'
            }).addClass('btn btn-outline-danger rounded-pill waves-effect btn-xs waves-light').html('<span class="mdi mdi-delete"></span>')
        );

        row.append($('<td>').css({
            'text-align': 'center',
            'white-space': 'nowrap'
        }).append(saveBtn).append('  ').append(deleteForm));

        return row;
    }

    $(document).on('input', '#description, .effort-description-input', function() {
        var input = $(this);
        var value = input.val();
        var words = value.trim().length ? value.trim().split(/\s+/) : [];
        if (words.length > 20) {
            input.val(words.slice(0, 20).join(' '));
        }
    });

    $(document).on('click', '.effort-save-btn', function() {
        var btn = $(this);
        var row = btn.closest('tr');
        var peId = row.data('pe-id');
        var hours = parseFloat(row.find('.effort-hours-input').val()) || 0;
        var minutes = parseFloat(row.find('.effort-minutes-input').val()) || 0;
        var description = row.find('.effort-description-input').val();

        btn.prop('disabled', true);

        $.ajax({
            url: "<?= base_url('Project_Manage/Effort_Tracker/Update_Effort_Ajax') ?>",
            type: 'POST',
            dataType: 'json',
            data: {
                pe_id: peId,
                effort_hours: hours,
                effort_minutes: minutes,
                description: description,
                [csrfName]: csrfHash
            },
            success: function(response) {
                btn.prop('disabled', false);

                if (response.csrfHash) {
                    csrfHash = response.csrfHash;
                    $('input[name="' + csrfName + '"]').val(csrfHash);
                }

                if (response.status === 'OK') {
                    var oldEffort = parseFloat(row.data('effort')) || 0;
                    row.data('effort', response.effort);
                    row.find('.effort-description-input').val(response.description);

                    applyTotalDelta(response.effort - oldEffort);

                    row.addClass('table-success');
                    setTimeout(function() {
                        row.removeClass('table-success');
                    }, 1500);
                } else {
                    alert(response.message || 'Something went wrong. Please contact Site Admin!');
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false);
                if (xhr.status === 403) {
                    alert('Your session security token has expired. The page will now reload.');
                    window.location.reload();
                    return;
                }
                alert('Request failed. Please try again.');
            }
        });
    });

    $('#add-effort-form').on('submit', function(event) {
        event.preventDefault();

        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        var alertBox = $('#add-effort-alert');

        var weekDate = form.find('#weekDate').val();
        var projectSelect = form.find('#project_id');
        var projectId = projectSelect.val();
        var projectName = projectSelect.find('option:selected').text();
        var hours = parseFloat(form.find('#effort_hours').val()) || 0;
        var minutes = parseFloat(form.find('#effort_minutes').val()) || 0;
        var description = form.find('#description').val();

        submitBtn.prop('disabled', true);
        alertBox.addClass('d-none').removeClass('alert-success alert-danger');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            dataType: 'json',
            data: {
                weekDate: weekDate,
                project_id: projectId,
                effort_hours: hours,
                effort_minutes: minutes,
                description: description,
                [csrfName]: csrfHash
            },
            success: function(response) {
                submitBtn.prop('disabled', false);

                if (response.csrfHash) {
                    csrfHash = response.csrfHash;
                    $('input[name="' + csrfName + '"]').val(csrfHash);
                }

                if (response.status === 'OK') {
                    alertBox.removeClass('d-none alert-danger').addClass('alert-success').text(response.message || 'Effort Data Added Successfully');

                    form.find('#project_id').val('');
                    form.find('#effort_hours').val('');
                    form.find('#effort_minutes').val('0');
                    form.find('#description').val('');

                    var table = $('#products-datatable');
                    if (String(table.data('selected-week')) === String(response.work_week)) {
                        var rowCount = table.find('tbody tr[data-pe-id]').length + 1;
                        var minutesFraction = response.effort - Math.floor(response.effort);

                        var newRow = buildEffortRow({
                            sno: rowCount,
                            pe_id: response.pe_id,
                            effort: response.effort,
                            hours: Math.floor(response.effort),
                            minutesFraction: minutesFraction,
                            description: response.description,
                            project_name: projectName
                        });

                        $('#effort-total-row').before(newRow);
                        applyTotalDelta(response.effort);

                        setTimeout(function() {
                            newRow.removeClass('table-success');
                        }, 1500);
                    }

                    setTimeout(function() {
                        alertBox.addClass('d-none');
                    }, 3000);
                } else {
                    alertBox.removeClass('d-none alert-success').addClass('alert-danger').text(response.message || 'Something went wrong. Please contact Site Admin!');
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false);
                if (xhr.status === 403) {
                    alertBox.removeClass('d-none alert-success').addClass('alert-danger').text('Your session security token has expired. The page will now reload.');
                    window.location.reload();
                    return;
                }
                alertBox.removeClass('d-none alert-success').addClass('alert-danger').text('Request failed. Please try again.');
            }
        });
    });
</script>