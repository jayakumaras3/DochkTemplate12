<?php helper('localization'); ?>
<style>
    #table_custom {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #table_custom td,
    #table_custom th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #table_custom th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: center;
        background-color: #4D4D4D;
        color: white;
    }


    .td_height {
        height: 70px;
        vertical-align: top;
    }

    .satsun {
        background-color: #F4F6F7;
        color: #ACB3BC;
    }

    .greenbox {
        background-color: rgb(214, 220, 226);
        color: #00612e;
    }

    .all_red {
        background-color: #ffc7ce;
        color: #9c0006;
    }

    .orangebox {
        background-color: #ffeb9c;
        color: #9c5700;
    }

    .all_green {
        background-color: #c6efce;
        color: #00612e;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <?php if ($return_page == 2) { ?>
                        <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/HR_admin'); ?>">
                                Admin Attendance
                            </a>
                        </li>
                    <?php } ?>
                    <?php if ($return_page == 1) { ?>
                        <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/attendance/team_attendance'); ?>">
                                Team Attendance
                            </a>
                        </li>
                    <?php } ?>

                </ol>
            </div>
            <h4 class="page-title">
                Attendance Team View - <?php echo $user_details[0]['name'] . ' ' . $user_details[0]['last_name']; ?>
            </h4>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">

                <?php

                function minutes($time)
                {
                    $time = explode(':', $time);
                    $totaltime = $time[0] * 60 + $time[1];
                    return $totaltime;
                }

                $todaynew = date('Y-m-d');
                $short_hours = 0;
                $totalPresent = 0;
                $totaldayinoffice = 0;
                $totalworkfromhome = 0;
                $totalleaves = 0;

                if ($report_month > 1) {
                    $month = $report_month - 1;
                    $year = $report_year;
                } else {
                    $month = 12;
                    $year = $report_year - 1;
                }

                $timestamp = mktime(0, 0, 0, $month, 26, $year);
                $daysInMonth = date("t", $timestamp);
                $firstDay = date("N", $timestamp);
                $firstdayofMonth = $year . '-' . $month . '-26';
                $lastday = $year . '-' . $month . '-' . $daysInMonth;
                echo '<table id="table_custom">';
                echo '<tr><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th><th>Sun</th></tr>';
                $dayCount = 26;
                $totaldaysleft = $daysInMonth - 25;
                echo '<tr class="td_height">';
                for ($i = 1; $i <= 7; $i++) {
                    if ($i < $firstDay) {
                        echo "<td></td>";
                    } else {
                        if ($dayCount <= $daysInMonth) {
                            if (strlen($month) < 2) {
                                $month = '0' . $month;
                            }
                            if (strlen($dayCount) < 2) {
                                $day = '0' . $dayCount;
                            } else {
                                $day = $dayCount;
                            }
                            $thisdate = $year . '-' . $month . '-' . $day;

                            $workfromhome = 0;
                            $leavepplied = 0;
                            $access_data = 0;
                            $accessval = 0;

                            $found_key = array_search($thisdate, array_column($workfromhome_data, 'start_date'));
                            if ($found_key > -1) {
                                $workfromhome = $workfromhome_data[$found_key]['number_wfh'];
                            }
                            $leave_key = array_search($thisdate, array_column($leave_data, 'start_dt'));
                            if ($leave_key > -1) {
                                $leavepplied = abs($leave_data[$leave_key]['number_leave']);
                            }
                            $attendance_key = array_search($thisdate, array_column($access_card, 'start_date'));
                            if ($attendance_key > -1) {
                                $access_data = $access_card[$attendance_key]['actualhr'];
                                if ($access_data > '09:00') {
                                    $accessval = 1.5;
                                } elseif ($access_data > '07:30' && $access_data < '09:00') {
                                    $accessval = 1;
                                } else {
                                    $accessval = 0.5;
                                }
                            }


                            if (in_array($thisdate, array_column($holidays, 'holiday_dt')) || date('N', strtotime($thisdate)) >= 6) {
                            } else {
                                $tataltime = $workfromhome + $leavepplied + $accessval;
                                if (strlen($access_data) > 2) {
                                    $today_hrs = minutes($access_data);
                                    $short_hours = $short_hours + $today_hrs;
                                }
                                $totalPresent = $tataltime + $totalPresent;
                                $totaldayinoffice = $totaldayinoffice + $accessval;
                                $totalworkfromhome  = $totalworkfromhome + $workfromhome;
                                $totalleaves  = $totalleaves + $leavepplied;
                            }

                            if ($thisdate > $todaynew) {
                                if (in_array($thisdate, array_column($holidays, 'holiday_dt'))) {
                                    echo '<td class="satsun">';
                                } elseif (date('N', strtotime($thisdate)) >= 6) {
                                    echo '<td class="satsun">';
                                } else {
                                    echo '<td>';
                                }
                            } else {
                                if (in_array($thisdate, array_column($holidays, 'holiday_dt'))) {
                                    echo '<td class="satsun">';
                                } elseif (date('N', strtotime($thisdate)) >= 6) {
                                    echo '<td class="satsun">';
                                } elseif ($tataltime > 1) {
                                    echo '<td class="orangebox">';
                                } elseif ($tataltime > 0.5) {
                                    echo '<td class="all_green">';
                                } elseif ($tataltime > 0) {
                                    echo '<td class="greenbox">';
                                } else {
                                    echo '<td class="all_red">';
                                }
                            }

                            echo $thisdate;
                            if ($workfromhome > 0) {
                                echo '<br>WFH : ' . $workfromhome;
                            }
                            if ($leavepplied > 0) {
                                echo '<br>Leave : ' . $leavepplied;
                            }
                            if ($accessval > 0) {
                                echo '<br>IO : ' . $access_data;
                            }
                            echo '</td>';
                            $dayCount++;
                        }
                    }
                }

                while ($dayCount <= $daysInMonth) {
                    echo '</tr><tr class="td_height">';
                    for ($i = 1; $i <= 7 && $dayCount <= $daysInMonth; $i++) {
                        if (strlen($month) < 2) {
                            $month = '0' . $month;
                        }
                        if (strlen($dayCount) < 2) {
                            $day = '0' . $dayCount;
                        } else {
                            $day = $dayCount;
                        }
                        $thisdate = $year . '-' . $month . '-' . $day;

                        $workfromhome = 0;
                        $leavepplied = 0;
                        $access_data = 0;
                        $accessval = 0;

                        $found_key = array_search($thisdate, array_column($workfromhome_data, 'start_date'));
                        if ($found_key > -1) {
                            $workfromhome = $workfromhome_data[$found_key]['number_wfh'];
                            // echo '<br>WFH - ';
                            //  echo $workfromhome_data[$found_key]['number_wfh'];
                        }
                        $leave_key = array_search($thisdate, array_column($leave_data, 'start_dt'));
                        if ($leave_key > -1) {
                            $leavepplied = abs($leave_data[$leave_key]['number_leave']);
                            //echo '<br>Leave ';
                            // echo $leave_data[$leave_key]['number_leave'];
                        }
                        $attendance_key = array_search($thisdate, array_column($access_card, 'start_date'));
                        if ($attendance_key > -1) {
                            $access_data = $access_card[$attendance_key]['actualhr'];
                            if ($access_data > '09:00') {
                                $accessval = 1.5;
                            } elseif ($access_data > '07:30' && $access_data < '09:00') {
                                $accessval = 1;
                            } else {
                                $accessval = 0.5;
                            }
                        }


                        if (in_array($thisdate, array_column($holidays, 'holiday_dt')) || date('N', strtotime($thisdate)) >= 6) {
                        } else {
                            $tataltime = $workfromhome + $leavepplied + $accessval;
                            if (strlen($access_data) > 2) {
                                $today_hrs = minutes($access_data);
                                $short_hours = $short_hours + $today_hrs;
                            }
                            $totalPresent = $tataltime + $totalPresent;
                            $totaldayinoffice = $totaldayinoffice + $accessval;
                            $totalworkfromhome  = $totalworkfromhome + $workfromhome;
                            $totalleaves  = $totalleaves + $leavepplied;
                        }

                        if ($thisdate > $todaynew) {
                            if (in_array($thisdate, array_column($holidays, 'holiday_dt'))) {
                                echo '<td class="satsun">';
                            } elseif (date('N', strtotime($thisdate)) >= 6) {
                                echo '<td class="satsun">';
                            } else {
                                echo '<td>';
                            }
                        } else {
                            if (in_array($thisdate, array_column($holidays, 'holiday_dt'))) {
                                echo '<td class="satsun">';
                            } elseif (date('N', strtotime($thisdate)) >= 6) {
                                echo '<td class="satsun">';
                            } elseif ($tataltime > 1) {
                                echo '<td class="orangebox">';
                            } elseif ($tataltime > 0.5) {
                                echo '<td class="all_green">';
                            } elseif ($tataltime > 0) {
                                echo '<td class="greenbox">';
                            } else {
                                echo '<td class="all_red">';
                            }
                        }
                        echo $thisdate;
                        if ($workfromhome > 0) {
                            echo '<br>WFH : ' . $workfromhome;
                        }
                        if ($leavepplied > 0) {
                            echo '<br>Leave : ' . $leavepplied;
                        }
                        if ($accessval > 0) {
                            echo '<br>IO : ' . $access_data;
                        }
                        echo '</td>';
                        $dayCount++;
                    }
                    // echo "</tr>";
                }

                $month = $report_month;
                $year = $report_year;
                $timestamp = mktime(0, 0, 0, $month, 1, $year);
                $daysInMonth = date("t", $timestamp);
                $firstDay = date("N", $timestamp);
                $dayCount = 1;
                $firstdayofMonth = $year . '-' . $month . '-1';
                $lastday = $year . '-' . $month . '-25';
                //   echo '<tr><td colspan="7" style="text-align:center; padding:30px;"><h5>' . $firstdayofMonth . ' : ' . $lastday . '</h5></td></tr>';
                // echo "<tr>";
                $dayofmont = date('w', strtotime($firstdayofMonth));
                if ($dayofmont == 1) {
                    echo '</tr><tr class="td_height">';
                }
                for ($i = 1; $i <= 7; $i++) {
                    if ($i < $firstDay) {
                        //  echo "<td></td>";
                    } else {
                        if (strlen($month) < 2) {
                            $month = '0' . $month;
                        }
                        if (strlen($dayCount) < 2) {
                            $day = '0' . $dayCount;
                        } else {
                            $day = $dayCount;
                        }
                        $thisdate = $year . '-' . $month . '-' . $day;

                        $workfromhome = 0;
                        $leavepplied = 0;
                        $access_data = 0;
                        $accessval = 0;

                        $found_key = array_search($thisdate, array_column($workfromhome_data, 'start_date'));
                        if ($found_key > -1) {
                            $workfromhome = $workfromhome_data[$found_key]['number_wfh'];
                            // echo '<br>WFH - ';
                            //  echo $workfromhome_data[$found_key]['number_wfh'];
                        }
                        $leave_key = array_search($thisdate, array_column($leave_data, 'start_dt'));
                        if ($leave_key > -1) {
                            $leavepplied = abs($leave_data[$leave_key]['number_leave']);
                            //echo '<br>Leave ';
                            // echo $leave_data[$leave_key]['number_leave'];
                        }
                        $attendance_key = array_search($thisdate, array_column($access_card, 'start_date'));
                        if ($attendance_key > -1) {
                            $access_data = $access_card[$attendance_key]['actualhr'];
                            if ($access_data > '09:00') {
                                $accessval = 1.5;
                            } elseif ($access_data > '07:30' && $access_data < '09:00') {
                                $accessval = 1;
                            } else {
                                $accessval = 0.5;
                            }
                        }


                        if (in_array($thisdate, array_column($holidays, 'holiday_dt')) || date('N', strtotime($thisdate)) >= 6) {
                        } else {
                            $tataltime = $workfromhome + $leavepplied + $accessval;
                            if (strlen($access_data) > 2) {
                                $today_hrs = minutes($access_data);
                                $short_hours = $short_hours + $today_hrs;
                            }
                            $totalPresent = $tataltime + $totalPresent;
                            $totaldayinoffice = $totaldayinoffice + $accessval;
                            $totalworkfromhome  = $totalworkfromhome + $workfromhome;
                            $totalleaves  = $totalleaves + $leavepplied;
                        }

                        if ($thisdate > $todaynew) {
                            if (in_array($thisdate, array_column($holidays, 'holiday_dt'))) {
                                echo '<td class="satsun">';
                            } elseif (date('N', strtotime($thisdate)) >= 6) {
                                echo '<td class="satsun">';
                            } else {
                                echo '<td>';
                            }
                        } else {
                            if (in_array($thisdate, array_column($holidays, 'holiday_dt'))) {
                                echo '<td class="satsun">';
                            } elseif (date('N', strtotime($thisdate)) >= 6) {
                                echo '<td class="satsun">';
                            } elseif ($tataltime > 1) {
                                echo '<td class="orangebox">';
                            } elseif ($tataltime > 0.5) {
                                echo '<td class="all_green">';
                            } elseif ($tataltime > 0) {
                                echo '<td class="greenbox">';
                            } else {
                                echo '<td class="all_red">';
                            }
                        }
                        echo $thisdate;
                        if ($workfromhome > 0) {
                            echo '<br>WFH : ' . $workfromhome;
                        }
                        if ($leavepplied > 0) {
                            echo '<br>Leave : ' . $leavepplied;
                        }
                        if ($accessval > 0) {
                            echo '<br>IO : ' . $access_data;
                        }
                        echo '</td>';
                        $dayCount++;
                    }
                }
                echo "</tr>";
                while ($dayCount <= 25) {
                    echo '<tr class="td_height">';
                    for ($i = 1; $i <= 7 && $dayCount <= 25; $i++) {
                        if (strlen($month) < 2) {
                            $month = '0' . $month;
                        }
                        if (strlen($dayCount) < 2) {
                            $day = '0' . $dayCount;
                        } else {
                            $day = $dayCount;
                        }
                        $thisdate = $year . '-' . $month . '-' . $day;

                        $workfromhome = 0;
                        $leavepplied = 0;
                        $access_data = 0;
                        $accessval = 0;

                        $found_key = array_search($thisdate, array_column($workfromhome_data, 'start_date'));
                        if ($found_key > -1) {
                            $workfromhome = $workfromhome_data[$found_key]['number_wfh'];
                            // echo '<br>WFH - ';
                            //  echo $workfromhome_data[$found_key]['number_wfh'];
                        }
                        $leave_key = array_search($thisdate, array_column($leave_data, 'start_dt'));
                        if ($leave_key > -1) {
                            $leavepplied = abs($leave_data[$leave_key]['number_leave']);
                            //echo '<br>Leave ';
                            // echo $leave_data[$leave_key]['number_leave'];
                        }
                        $attendance_key = array_search($thisdate, array_column($access_card, 'start_date'));
                        if ($attendance_key > -1) {
                            $access_data = $access_card[$attendance_key]['actualhr'];
                            if ($access_data > '09:00') {
                                $accessval = 1.5;
                            } elseif ($access_data > '07:30' && $access_data < '09:00') {
                                $accessval = 1;
                            } else {
                                $accessval = 0.5;
                            }
                        }


                        if (in_array($thisdate, array_column($holidays, 'holiday_dt')) || date('N', strtotime($thisdate)) >= 6) {
                        } else {
                            $tataltime = $workfromhome + $leavepplied + $accessval;
                            if (strlen($access_data) > 2) {
                                $today_hrs = minutes($access_data);
                                $short_hours = $short_hours + $today_hrs;
                            }
                            $totalPresent = $tataltime + $totalPresent;
                            $totaldayinoffice = $totaldayinoffice + $accessval;
                            $totalworkfromhome  = $totalworkfromhome + $workfromhome;
                            $totalleaves  = $totalleaves + $leavepplied;
                        }

                        if ($thisdate > $todaynew) {
                            if (in_array($thisdate, array_column($holidays, 'holiday_dt'))) {
                                echo '<td class="satsun">';
                            } elseif (date('N', strtotime($thisdate)) >= 6) {
                                echo '<td class="satsun">';
                            } else {
                                echo '<td>';
                            }
                        } else {
                            if (in_array($thisdate, array_column($holidays, 'holiday_dt'))) {
                                echo '<td class="satsun">';
                            } elseif (date('N', strtotime($thisdate)) >= 6) {
                                echo '<td class="satsun">';
                            } elseif ($tataltime > 1) {
                                echo '<td class="orangebox">';
                            } elseif ($tataltime > 0.5) {
                                echo '<td class="all_green">';
                            } elseif ($tataltime > 0) {
                                echo '<td class="greenbox">';
                            } else {
                                echo '<td class="all_red">';
                            }
                        }
                        echo $thisdate;
                        if ($workfromhome > 0) {
                            echo '<br>WFH : ' . $workfromhome;
                        }
                        if ($leavepplied > 0) {
                            echo '<br>Leave : ' . $leavepplied;
                        }
                        if ($accessval > 0) {
                            echo '<br>IO : ' . $access_data;
                        }
                        echo '</td>';
                        $dayCount++;
                    }
                    echo "</tr>";
                }
                echo "</table>";
                ?>
                <div class="row mt-2">
                    <div class="col-md-12">

                        <table id="table_custom">
                            <tr>
                                <td>
                                    <div class="satsun">
                                        <p class="my-2 text-center satsun">Holiday</p>
                                    </div>
                                </td>
                                <td>
                                    <div class="all_green">
                                        <p class="my-2 text-center">Full Day</p>
                                    </div>
                                </td>
                                <td>
                                    <div class="greenbox">
                                        <p class="my-2 text-center">Half Day</p>
                                    </div>
                                </td>
                                <td>
                                    <div class="all_red">
                                        <p class="my-2 text-center">No Data</p>
                                    </div>
                                </td>
                                <td>
                                    <div class="orangebox">
                                        <p class="my-2 text-center">More</p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('etrack/attendance/team_attenance_statement'); ?>" method="POST"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label for="month">Month</label>
                                <select class="form-select" name="month">
                                    <?php
                                    for ($i = 1; $i < 13; $i++) {
                                        $monthName = translated_month_name($i);
                                        $thismonth = date('n');
                                        echo '<option value="' . $i . '"';
                                        if ($i == $thismonth) {
                                            if (date('d') < 26) {
                                                echo 'selected';
                                            } else {
                                                if ($i == 12) {
                                                    echo '';
                                                } else {
                                                    if ($i == $thismonth + 1) {
                                                        echo 'selected';
                                                    }
                                                }
                                            }
                                        }
                                        echo '>' . $monthName . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="year">Year</label>
                                <select class="form-select" name='year'>
                                    <?php
                                    $endyear = date('Y');
                                    for ($i = $endyear; $i >= 2025; $i--) {
                                        echo "<option value='$i'>$i</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4 mt-3">
                                <input type="submit" onclick="this.form.submit(); this.disabled=true;" class=" btn btn-outline-info btn-xs waves-effect waves-light" value="Show Data">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <tr>
                        <td>Working Days</td>
                        <td><?php echo $working_days; ?></td>
                    </tr>
                    <tr>
                        <td>Work From Home (Actual/Calculated)</td>
                        <td><?php

                            echo count($workfromhome_data);
                            echo ' / ';
                            echo $totalworkfromhome; ?></td>
                    </tr>
                    <tr>
                        <td>Leaves</td>
                        <td><?php echo   $totalleaves; ?></td>
                    </tr>
                    <tr>
                        <td>Total hrs in Office</td>
                        <td><?php
                            $hrs = floor($short_hours / 60);
                            $min =  ':' . ($short_hours -   floor($short_hours / 60) * 60);
                            echo $hrs . $min;
                            ?></td>
                    </tr>
                    <tr>
                        <td>In Office <br>(Actual/Calculated/Hrs Calculated)</td>
                        <td><?php

                            $calculatedio = round(round(($hrs / 8), 1) * 2) / 2;
                            echo count($access_card);
                            echo ' / ';
                            echo $totaldayinoffice;
                            echo ' / ';
                            echo  $calculatedio;
                            ?></td>
                    </tr>

                    <tr>
                        <td>Approx LOP</td>
                        <td><?php
                            $lop = $working_days - $totalworkfromhome - $totalleaves - $calculatedio;
                            if ($lop <= 0) {
                                echo '-';
                            } else {
                                echo $lop;
                            }

                            ?></td>
                    </tr>
                </table>
                <span style="color:red; font-size:10px;"><i>Note: Final LOP is calculated by HR based on company policy. This data is for reference only.</i></span>
            </div>
        </div>

    </div>
</div>