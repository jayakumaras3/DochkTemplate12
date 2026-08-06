<?php

helper(['attendance', 'localization']);

$statusMeta = [
    'in_office' => ['label' => 'In Office', 'badge' => 'bg-soft-success text-success', 'dot' => 'bg-success'],
    'wfh'       => ['label' => 'WFH', 'badge' => 'bg-soft-primary text-primary', 'dot' => 'bg-primary'],
    'half_wfh'  => ['label' => 'Half WFH', 'badge' => 'bg-soft-warning text-warning', 'dot' => 'bg-warning'],
    'leave'     => ['label' => 'Leave', 'badge' => 'bg-soft-danger text-danger', 'dot' => 'bg-danger'],
    'half_leave' => ['label' => 'Half Leave', 'badge' => 'bg-soft-danger text-danger', 'dot' => 'bg-danger'],
    'absent'    => ['label' => 'Absent', 'badge' => 'bg-soft-secondary text-dark', 'dot' => 'bg-secondary'],
];

$wfhByDate = [];
foreach (($workfromhome_data ?? []) as $row) {
    if (!isset($row['start_date'])) {
        continue;
    }
    $wfhByDate[$row['start_date']] = (float) $row['number_wfh'];
}
$leaveByDate = [];
$leaveTypeByDate = [];
foreach (($leave_data ?? []) as $row) {
    if (!isset($row['start_dt'])) {
        continue;
    }
    $leaveByDate[$row['start_dt']] = abs((float) $row['number_leave']);
    $leaveTypeByDate[$row['start_dt']] = (int) ($row['leave_type'] ?? 0);
}
$accessHrByDate = [];
foreach (($access_card ?? []) as $row) {
    if (!isset($row['start_date'])) {
        continue;
    }
    $accessHrByDate[$row['start_date']] = $row['actualhr'] ?? null;
}
$officeByDate = [];
foreach ($accessHrByDate as $date => $hr) {
    $officeByDate[$date] = attendance_office_value($hr);
}
$holidaySet = array_column($holidays ?? [], 'holiday_dt');

$today = $todaydt;
$cursor = new DateTime($start_dt);
$cycleEnd = new DateTime($end_dt);

$summary = attendance_summary($start_dt, $end_dt, $today, $holidays ?? [], $workfromhome_data ?? [], $leave_data ?? [], $access_card ?? []);

$calendarDays = [];
$shortMinutes = 0;
$hoursDayCount = 0;

while ($cursor <= $cycleEnd) {
    $thisdate = $cursor->format('Y-m-d');
    $dow = (int) $cursor->format('N');
    $isWeekend = $dow >= 6;
    $isHoliday = in_array($thisdate, $holidaySet, true);

    $wfh = $wfhByDate[$thisdate] ?? 0;
    $leave = $leaveByDate[$thisdate] ?? 0;
    $leaveType = $leaveTypeByDate[$thisdate] ?? 0;
    $officeVal = $officeByDate[$thisdate] ?? 0;
    $accessHr = $accessHrByDate[$thisdate] ?? null;

    // A day can carry more than one status at once (e.g. half-day leave + half-day WFH),
    // so every applicable status is collected instead of picking a single one.
    $statuses = [];
    $note = null;

    if ($isHoliday) {
        $statuses[] = 'holiday';
    } elseif ($isWeekend) {
        $statuses[] = 'weekend';
    } else {
        if ($leave > 0) {
            $statuses[] = $leave >= 1 ? 'leave' : 'half_leave';
            $note = $leaveType === 9 ? 'OD' : null;
        }
        if ($wfh >= 1) {
            $statuses[] = 'wfh';
        } elseif ($wfh > 0) {
            $statuses[] = 'half_wfh';
        }
        if ($officeVal > 0) {
            $statuses[] = 'in_office';
        }
        if (empty($statuses) && $thisdate < $today) {
            $statuses[] = 'absent';
        }
    }

    // Kept separate from $note (which can hold the leave 'OD' marker instead) so the office
    // duration is always available to render inside the "In Office" badge itself.
    $officeHrLabel = $officeVal > 0 ? attendance_hr_label($accessHr) : null;

    if ($note === null) {
        $note = attendance_hr_label($accessHr);
    }

    $needsHighlight = !$isWeekend && !$isHoliday && $thisdate < $today && ($officeVal + $wfh + $leave) < 1;

    if (!$isWeekend && !$isHoliday && $thisdate <= $today) {
        $hrLabel = attendance_hr_label($accessHr);
        if ($hrLabel !== null) {
            $shortMinutes += attendance_minutes($accessHr);
            $hoursDayCount++;
        }
    }

    $calendarDays[$thisdate] = [
        'date' => $thisdate,
        'day' => (int) $cursor->format('j'),
        'dow' => $dow,
        'status' => $statuses[0] ?? 'none',
        'statuses' => $statuses,
        'note' => $note,
        'office_hr_label' => $officeHrLabel,
        'is_today' => $thisdate === $today,
        'needs_highlight' => $needsHighlight,
    ];

    $cursor->modify('+1 day');
}

$weeks = [];
$weekIndex = -1;
foreach ($calendarDays as $date => $info) {
    if ($info['dow'] === 1 || $weekIndex === -1) {
        $weekIndex++;
        $weeks[$weekIndex] = ['days' => array_fill(1, 7, null)];
    }
    $weeks[$weekIndex]['days'][$info['dow']] = $info;
}

$totalWorkingDays = $summary['total_working_days'];
$inOfficeDays = $summary['in_office_days'];
$wfhFullDays = $summary['wfh_full_days'];
$wfhHalfDays = $summary['wfh_half_days'];
$wfhDays = $summary['wfh_days'];
$leaveDays = $summary['leave_days'];
// Absent is only meaningful for working days that have already happened, not the whole cycle.
$absentDays = max(0, $summary['working_days_elapsed'] - $inOfficeDays - $wfhDays - $leaveDays);

$avgHoursPerDay = $hoursDayCount > 0 ? attendance_format_hm($shortMinutes / $hoursDayCount) : '-';

$curMonth = (int) $report_month;
$curYear = (int) $report_year;
$prevMonth = $curMonth - 1;
$prevYear = $curYear;
if ($prevMonth < 1) {
    $prevMonth = 12;
    $prevYear--;
}
$nextMonth = $curMonth + 1;
$nextYear = $curYear;
if ($nextMonth > 12) {
    $nextMonth = 1;
    $nextYear++;
}

$todayWfh = $wfhByDate[$today] ?? 0;
$todayLeave = $leaveByDate[$today] ?? 0;
$todayAccess = $officeByDate[$today] ?? 0;
$todayTotal = $todayWfh + $todayLeave + $todayAccess;
$todaydtdat = $todayTotal > 0.5 ? 1 : ($todayTotal > 0 ? 0.5 : 0);

$prevWfh = $wfhByDate[$prev_india_time] ?? 0;
$prevLeave = $leaveByDate[$prev_india_time] ?? 0;
$prevAccess = $officeByDate[$prev_india_time] ?? 0;
$prevTotal = $prevWfh + $prevLeave + $prevAccess;
$previewdaytotal = $prevTotal > 0.5 ? 1 : ($prevTotal > 0 ? 0.5 : 0);

$showApplyPanel = false;
if ($return_page == 1 && ($todaydtdat < 1 || $previewdaytotal < 1)) {
    $today_Month = (int) date('m');
    if ($curMonth == $today_Month || $curMonth == $today_Month + 1 || ($curMonth == 12 && $today_Month == 1)) {
        $showApplyPanel = true;
    }
}
?>
<style>
    .card {
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.12);
    }

    .att-cal-grid {
        display: grid;
        grid-template-columns: 84px repeat(7, 1fr);
        gap: 6px;
    }

    .att-cal-head {
        text-align: center;
        font-size: 0.75rem;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        padding-bottom: 6px;
    }

    .att-cal-weeklabel {
        display: flex;
        flex-direction: column;
        justify-content: center;
        font-size: 0.75rem;
        color: #6c757d;
        padding-right: 6px;
    }

    .att-cal-cell {
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 10px;
        padding: 8px 6px;
        min-height: 78px;
        background-color: #fff;
    }

    .att-cal-cell-empty {
        background: transparent;
        border: none;
    }

    .att-cal-cell.is-off {
        background-color: #f4f6f7;
        color: #adb5bd;
    }

    .att-cal-cell.is-today {
        border: 2px solid #727cf5;
    }

    .att-cal-cell.needs-highlight {
        background-color: #fff5f5;
        border-color: #f1aeb5;
    }

    .att-cal-daynum {
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .att-nav-btn {
        width: 2rem;
        height: 2rem;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .att-cal-today-badge {
        float: right;
        font-size: 0.65rem;
    }

    .att-apply-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        border-radius: 14px;
        border: 1px solid rgba(0, 0, 0, 0.08);
        text-decoration: none;
        width: 100%;
        text-align: left;
        background: none;
    }

    .att-apply-item:hover {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }

    .att-apply-icon {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.1rem;
    }

    .att-summary-card .card-body {
        padding: 0.85rem 1.25rem;
    }

    .att-stat-icon {
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 0.95rem;
    }

    .att-section {
        flex: 1 1 0;
        min-width: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0 1rem;
        border-right: 1px solid rgba(0, 0, 0, 0.08);
    }

    .att-section:last-child {
        border-right: none;
    }

    .att-section-start {
        justify-content: flex-start;
        padding-left: 0;
    }

    @media screen and (max-width: 1199px) {
        .att-section {
            border-right: none;
            flex: 1 1 33%;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            padding: 0.5rem 0;
        }
    }

    @media screen and (max-width: 768px) {
        .att-cal-grid {
            grid-template-columns: 60px repeat(7, 1fr);
        }

        .att-cal-cell {
            min-height: 60px;
            font-size: 0.75rem;
        }
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <?php if ($return_page == 1) { ?>
                        <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/dashboard'); ?>">
                                Dashboard
                            </a>
                        </li>
                    <?php } ?>

                    <?php if ($return_page == 2) { ?>
                        <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/attendance/team_attendance'); ?>">
                                Team Attendance
                            </a>
                        </li>
                    <?php } ?>

                    <?php if ($return_page == 3) { ?>
                        <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/HR_admin/payroll_report'); ?>">
                                Payroll Attendance
                            </a>
                        </li>
                    <?php } ?>
                    <?php if ($return_page == 4) { ?>
                        <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/Fin_admin/payroll_report'); ?>">
                                Payroll Attendance
                            </a>
                        </li>
                    <?php } ?>
                </ol>
            </div>
            <h4 class="page-title">
                Attendance <?php if ($return_page != 1) {
                                            echo '(' . $user_details[0]['name'] . ' ' . $user_details[0]['last_name'] . ')';
                                        } ?>
            </h4>
        </div>
    </div>
</div>

<?php if ($start_dt < '2025-03-25') { ?>
    <div class="alert alert-warning">Access card data for this month not available. Please visit old etrack.</div>
<?php } ?>

<div class="row">
    <div class="col-12 mb-3">
        <div class="card att-summary-card">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-stretch">
                    <div class="att-section att-section-start">
                        <div>
                            <div class="text-muted font-11">Attendance Cycle</div>
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <h5 class="mb-0"><?php echo date('d M', strtotime($start_dt)) . ' – ' . date('d M Y', strtotime($end_dt)); ?></h5>
                                <span class="badge bg-soft-primary text-primary"><?php echo $totalWorkingDays; ?> Working Days</span>
                            </div>
                        </div>
                    </div>

                    <div class="att-section">
                        <div class="att-stat-icon bg-soft-success text-success"><i class="mdi mdi-office-building"></i></div>
                        <div>
                            <div class="text-success fw-semibold font-11">In Office</div>
                            <div class="fw-bold font-13"><?php echo attendance_format_days($inOfficeDays); ?> Days</div>
                        </div>
                    </div>

                    <div class="att-section">
                        <div class="att-stat-icon bg-soft-primary text-primary"><i class="mdi mdi-home-variant"></i></div>
                        <div>
                            <div class="text-primary fw-semibold font-11">Work From Home</div>
                            <div class="fw-bold font-13"><?php echo attendance_format_days($wfhDays); ?> Days</div>
                        </div>
                    </div>

                    <div class="att-section">
                        <div class="att-stat-icon bg-soft-danger text-danger"><i class="mdi mdi-calendar-remove"></i></div>
                        <div>
                            <div class="text-danger fw-semibold font-11">Leave</div>
                            <div class="fw-bold font-13"><?php echo attendance_format_days($leaveDays); ?> Days</div>
                        </div>
                    </div>

                    <div class="att-section">
                        <div class="att-stat-icon bg-soft-warning text-dark"><i class="mdi mdi-account-off"></i></div>
                        <div>
                            <div class="text-muted font-11">Absent</div>
                            <div class="fw-bold font-13"><?php echo attendance_format_days($absentDays); ?> Days</div>
                        </div>
                    </div>

                    <div class="att-section">
                        <div class="att-stat-icon bg-soft-secondary text-dark"><i class="mdi mdi-clock-outline"></i></div>
                        <div>
                            <div class="text-muted font-11">Average Hours/Day</div>
                            <div class="fw-bold font-13"><?php echo esc($avgHoursPerDay); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-8 col-lg-7 mb-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <form action="<?php echo base_url('etrack/attendance/view/' . $return_page); ?>" method="POST" class="m-0"><?= csrf_field() ?>
                            <input type="hidden" name="month" value="<?php echo $prevMonth; ?>">
                            <input type="hidden" name="year" value="<?php echo $prevYear; ?>">
                            <button type="submit" class="btn btn-sm btn-outline-secondary rounded-circle att-nav-btn" aria-label="Previous month"><i class="mdi mdi-chevron-left"></i></button>
                        </form>
                        <form action="<?php echo base_url('etrack/attendance/view/' . $return_page); ?>" method="POST" class="m-0"><?= csrf_field() ?>
                            <input type="hidden" name="month" value="<?php echo $nextMonth; ?>">
                            <input type="hidden" name="year" value="<?php echo $nextYear; ?>">
                            <button type="submit" class="btn btn-sm btn-outline-secondary rounded-circle att-nav-btn" aria-label="Next month"><i class="mdi mdi-chevron-right"></i></button>
                        </form>
                        <a href="<?php echo base_url('etrack/attendance/view/' . $return_page); ?>" class="btn btn-sm btn-outline-primary rounded-pill waves-effect waves-light">Today</a>
                    </div>
                    <form action="<?php echo base_url('etrack/attendance/view/' . $return_page); ?>" method="POST" class="d-flex align-items-center gap-2 m-0"><?= csrf_field() ?>
                        <select class="form-select form-select-sm rounded-pill" name="month" style="width:auto;">
                            <?php for ($i = 1; $i < 13; $i++) { ?>
                                <option value="<?php echo $i; ?>" <?php echo $i == $curMonth ? 'selected' : ''; ?>><?php echo translated_month_name($i); ?></option>
                            <?php } ?>
                        </select>
                        <select class="form-select form-select-sm rounded-pill" name="year" style="width:auto;">
                            <?php for ($i = (int) date('Y'); $i >= 2025; $i--) { ?>
                                <option value="<?php echo $i; ?>" <?php echo $i == $curYear ? 'selected' : ''; ?>><?php echo $i; ?></option>
                            <?php } ?>
                        </select>
                        <button type="submit" class="btn btn-sm btn-outline-info rounded-pill waves-effect waves-light">Go</button>
                    </form>
                </div>

                <div class="att-cal-grid">
                    <div></div>
                    <?php foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $dowLabel) { ?>
                        <div class="att-cal-head"><?php echo $dowLabel; ?></div>
                    <?php } ?>

                    <?php foreach ($weeks as $week) {
                        $present = array_filter($week['days'], fn($d) => $d !== null);
                        $first = reset($present);
                        $last = end($present);
                    ?>
                        <div class="att-cal-weeklabel">
                            <div class="fw-semibold text-dark"><?php echo $first ? date('M', strtotime($first['date'])) : ''; ?></div>
                            <div><?php echo $first ? date('d', strtotime($first['date'])) . ' – ' . date('d', strtotime($last['date'])) : ''; ?></div>
                        </div>
                        <?php foreach ($week['days'] as $d) {
                            if ($d === null) {
                                echo '<div class="att-cal-cell att-cal-cell-empty"></div>';
                                continue;
                            }
                            $isOff = in_array($d['status'], ['weekend', 'holiday'], true);
                            $cellClass = 'att-cal-cell' . ($isOff ? ' is-off' : '') . ($d['is_today'] ? ' is-today' : '') . (!empty($d['needs_highlight']) ? ' needs-highlight' : '');
                        ?>
                            <div class="<?php echo $cellClass; ?>">
                                <?php if ($d['is_today']) { ?>
                                    <span class="badge bg-primary att-cal-today-badge">Today</span>
                                <?php } ?>
                                <div class="att-cal-daynum"><?php echo $d['day']; ?></div>
                                <?php if ($d['status'] === 'holiday') { ?>
                                    <div class="font-11">Holiday</div>
                                <?php } elseif ($d['status'] === 'weekend') { ?>
                                    <div class="font-11">Weekend</div>
                                <?php } elseif (!empty($d['statuses'])) { ?>
                                    <div class="d-flex flex-wrap gap-1 justify-content-center">
                                        <?php foreach ($d['statuses'] as $st) {
                                            if (!isset($statusMeta[$st])) {
                                                continue;
                                            }
                                            $badgeLabel = ($st === 'in_office' && !empty($d['office_hr_label']))
                                                ? $d['office_hr_label']
                                                : $statusMeta[$st]['label'];
                                        ?>
                                            <span class="badge <?php echo $statusMeta[$st]['badge']; ?> font-11"><?php echo esc($badgeLabel); ?></span>
                                        <?php } ?>
                                    </div>
                                    <?php if (!empty($d['note']) && $d['note'] !== $d['office_hr_label']) { ?>
                                        <div class="text-muted font-11 mt-1"><?php echo esc($d['note']); ?></div>
                                    <?php } ?>
                                <?php } else { ?>
                                    <div class="text-muted font-11">-</div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>

                <div class="d-flex flex-wrap gap-3 mt-3 pt-3 border-top">
                    <?php foreach ($statusMeta as $meta) { ?>
                        <div class="d-flex align-items-center gap-1 font-13"><span class="dash-legend-dot <?php echo $meta['dot']; ?>" style="width:10px;height:10px;border-radius:50%;display:inline-block;"></span> <?php echo $meta['label']; ?></div>
                    <?php } ?>
                    <div class="d-flex align-items-center gap-1 font-13"><span class="dash-legend-dot bg-secondary" style="width:10px;height:10px;border-radius:50%;display:inline-block;"></span> Weekend/Holiday</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5 mb-3">
        <?php if ($showApplyPanel) { ?>
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Apply Attendance</h4>
                    <div class="d-flex flex-column gap-2">
                        <?php if ($todaydtdat < .5) { ?>
                            <form action="<?php echo base_url('etrack/attendance/add_wfh'); ?>" method="POST" class="m-0"><?= csrf_field() ?>
                                <input type="hidden" name="type" value="1">
                                <input type="hidden" name="value" value="1">
                                <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="att-apply-item">
                                    <div class="att-apply-icon bg-soft-primary text-primary"><i class="mdi mdi-home-variant"></i></div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold text-primary">Apply WFH for Today</div>
                                        <div class="text-muted font-12">Apply Work From Home for <?php echo date('d M Y', strtotime($todaydt)); ?></div>
                                    </div>
                                    <i class="mdi mdi-chevron-right text-muted"></i>
                                </button>
                            </form>
                        <?php } ?>
                        <?php if ($todaydtdat < 1) { ?>
                            <form action="<?php echo base_url('etrack/attendance/add_wfh'); ?>" method="POST" class="m-0"><?= csrf_field() ?>
                                <input type="hidden" name="type" value="1">
                                <input type="hidden" name="value" value=".5">
                                <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="att-apply-item">
                                    <div class="att-apply-icon bg-soft-warning text-warning"><i class="mdi mdi-laptop"></i></div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold text-warning">Apply Half Day WFH for Today</div>
                                        <div class="text-muted font-12">Apply Half Day Work From Home for <?php echo date('d M Y', strtotime($todaydt)); ?></div>
                                    </div>
                                    <i class="mdi mdi-chevron-right text-muted"></i>
                                </button>
                            </form>
                        <?php } ?>
                        <?php if ($previewdaytotal < .5) { ?>
                            <form action="<?php echo base_url('etrack/attendance/add_wfh'); ?>" method="POST" class="m-0"><?= csrf_field() ?>
                                <input type="hidden" name="type" value="2">
                                <input type="hidden" name="value" value="1">
                                <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="att-apply-item">
                                    <div class="att-apply-icon bg-soft-info text-info"><i class="mdi mdi-home-variant"></i></div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold text-info">Apply WFH for Last Working Day</div>
                                        <div class="text-muted font-12">Apply Work From Home for <?php echo date('d M Y', strtotime($prev_india_time)); ?></div>
                                    </div>
                                    <i class="mdi mdi-chevron-right text-muted"></i>
                                </button>
                            </form>
                        <?php } ?>
                        <?php if ($previewdaytotal < 1) { ?>
                            <form action="<?php echo base_url('etrack/attendance/add_wfh'); ?>" method="POST" class="m-0"><?= csrf_field() ?>
                                <input type="hidden" name="type" value="2">
                                <input type="hidden" name="value" value=".5">
                                <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="att-apply-item">
                                    <div class="att-apply-icon bg-soft-secondary text-dark"><i class="mdi mdi-laptop"></i></div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold">Apply Half Day WFH for Last Working Day</div>
                                        <div class="text-muted font-12">Apply Half Day Work From Home for <?php echo date('d M Y', strtotime($prev_india_time)); ?></div>
                                    </div>
                                    <i class="mdi mdi-chevron-right text-muted"></i>
                                </button>
                            </form>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

