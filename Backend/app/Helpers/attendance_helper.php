<?php

function attendance_minutes($time)
{
    if (!is_string($time) || strpos($time, ':') === false) {
        return 0;
    }
    [$h, $m] = explode(':', $time);
    return ((int) $h * 60) + (int) $m;
}

function attendance_format_hm($minutes)
{
    $minutes = max(0, (int) round($minutes));
    return intdiv($minutes, 60) . 'h ' . str_pad((string) ($minutes % 60), 2, '0', STR_PAD_LEFT) . 'm';
}

function attendance_hr_label($actualhr)
{
    if (!is_string($actualhr) || strlen($actualhr) <= 2) {
        return null;
    }
    [$h, $m] = array_pad(explode(':', $actualhr), 2, '0');
    return ((int) $h) . 'h ' . str_pad((string) ((int) $m), 2, '0', STR_PAD_LEFT) . 'm';
}

function attendance_format_days($value)
{
    return rtrim(rtrim(number_format((float) $value, 1), '0'), '.');
}

/**
 * More than 7.5 office hours in a day counts as a full day, more than 3.5 counts as half.
 */
function attendance_office_value($actualhr)
{
    $minutes = attendance_minutes($actualhr);
    if ($minutes > 450) {
        return 1;
    }
    if ($minutes > 210) {
        return 0.5;
    }
    return 0;
}

/**
 * Single source of truth for the In Office / WFH / Leave day-counts shown on both
 * etrack/dashboard (Attendance Overview) and etrack/attendance/view (Attendance Cycle).
 * Only counts business days (weekends/holidays excluded) up to and including $today.
 */
function attendance_summary($start_dt, $end_dt, $today, array $holidays, array $workfromhome_data, array $leave_data, array $access_card)
{
    $holidaySet = array_column($holidays, 'holiday_dt');

    $wfhByDate = [];
    foreach ($workfromhome_data as $row) {
        if (!isset($row['start_date'])) {
            continue;
        }
        $wfhByDate[$row['start_date']] = (float) $row['number_wfh'];
    }

    $leaveByDate = [];
    foreach ($leave_data as $row) {
        if (!isset($row['start_dt'])) {
            continue;
        }
        $leaveByDate[$row['start_dt']] = abs((float) $row['number_leave']);
    }

    $officeByDate = [];
    foreach ($access_card as $row) {
        if (!isset($row['start_date'])) {
            continue;
        }
        $officeByDate[$row['start_date']] = attendance_office_value($row['actualhr'] ?? null);
    }

    $cursor = new DateTime($start_dt);
    $cycleEnd = new DateTime($end_dt);
    $periodDays = (int) $cursor->diff($cycleEnd)->days + 1;

    $totalWorkingDays = 0;
    $workingDaysElapsed = 0;
    $inOfficeDays = 0.0;
    $wfhFullDays = 0;
    $wfhHalfDays = 0.0;
    $leaveDays = 0.0;

    while ($cursor <= $cycleEnd) {
        $thisdate = $cursor->format('Y-m-d');
        $isWeekend = (int) $cursor->format('N') >= 6;
        $isHoliday = in_array($thisdate, $holidaySet, true);

        if (!$isWeekend && !$isHoliday) {
            $totalWorkingDays++;

            // Today is excluded here (both from the elapsed-days baseline and from the
            // office/WFH/leave totals that get subtracted from it) so a missing check-in on
            // a day that isn't over yet never counts as an absence.
            if ($thisdate < $today) {
                $workingDaysElapsed++;

                $wfh = $wfhByDate[$thisdate] ?? 0;
                $inOfficeDays += $officeByDate[$thisdate] ?? 0;
                if ($wfh >= 1) {
                    $wfhFullDays += 1;
                } elseif ($wfh > 0) {
                    $wfhHalfDays += $wfh;
                }
                $leaveDays += $leaveByDate[$thisdate] ?? 0;
            }
        }

        $cursor->modify('+1 day');
    }

    return [
        'period_days' => $periodDays,
        'total_working_days' => $totalWorkingDays,
        'working_days_elapsed' => $workingDaysElapsed,
        'in_office_days' => $inOfficeDays,
        'wfh_full_days' => $wfhFullDays,
        'wfh_half_days' => $wfhHalfDays,
        'wfh_days' => $wfhFullDays + $wfhHalfDays,
        'leave_days' => $leaveDays,
    ];
}
