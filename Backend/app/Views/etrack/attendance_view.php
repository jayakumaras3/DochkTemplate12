<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">
                e-Track Attendance
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/attendance'); ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="month">Enter Month (1-12):</label>
                            <input type="number" class="form-control" id="month" name="month" min="1" max="12" required>
                        </div>
                        <div class="col-md-4">
                            <label for="year">Enter Year:</label>
                            <input type="number" class="form-control" id="year" name="year" min="2025" max="2030" required>
                        </div>
                        <div class="col-md-4 mt-3">
                            <input type="submit" class=" btn btn-outline-info btn-xs waves-effect waves-light" value="Show Data">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <?php
        $todaywfh = $today_wfh[0]['number_wfh'];
        //   echo $todaywfh;

        $lastdaywfh = $last_wfh[0]['number_wfh'];
        ?>
        <div class="row">
            <?php
            if ($todaywfh < .5) { ?>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="text-end">
                                        <form class="form-horizontal" action="<?php echo base_url('etrack/attendance/add_wfh'); ?>" method="POST"><?= csrf_field() ?>
                                            <div class=" row">
                                                <input type="hidden" name="type" value="1">
                                                <input type="hidden" name="value" value="1">
                                                <button type="submit" class=" btn btn-outline-success btn-xs waves-effect waves-light">
                                                    Apply WFH (Today)
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card-->
                </div> <!-- end col -->
            <?php
            } ?>
            <?php
            if ($lastdaywfh < .5) { ?>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="text-end">
                                        <form class="form-horizontal" action="<?php echo base_url('etrack/attendance/add_wfh'); ?>" method="POST"><?= csrf_field() ?>
                                            <div class=" row">
                                                <input type="hidden" name="type" value="2">
                                                <input type="hidden" name="value" value="1">
                                                <button type="submit" class=" btn btn-outline-info btn-xs waves-effect waves-light">
                                                    Apply WFH (Previous Day)
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card-->
                </div> <!-- end col -->
            <?php
            } ?>
        </div>

        <div class="row">
            <?php
            if ($todaywfh < 1) { ?>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="text-end">
                                        <form class="form-horizontal" action="<?php echo base_url('etrack/attendance/add_wfh'); ?>" method="POST"><?= csrf_field() ?>
                                            <div class=" row">
                                                <input type="hidden" name="type" value="1">
                                                <input type="hidden" name="value" value=".5">
                                                <button type="submit" class=" btn btn-outline-warning btn-xs waves-effect waves-light">
                                                    Apply Half Day WFH (Today)
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card-->
                </div> <!-- end col -->
            <?php
            } ?>
            <?php
            if ($lastdaywfh < 1) { ?>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="text-end">
                                        <form class="form-horizontal" action="<?php echo base_url('etrack/attendance/add_wfh'); ?>" method="POST"><?= csrf_field() ?>
                                            <div class=" row">
                                                <input type="hidden" name="type" value="2">
                                                <input type="hidden" name="value" value=".5">
                                                <button type="submit" class=" btn btn-outline-danger btn-xs waves-effect waves-light">
                                                    Apply Half Day WFH (Previous Day)
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card-->
                </div> <!-- end col -->
            <?php
            } ?>
        </div> <!-- end col -->
    </div> <!-- end col -->

</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <style>
                    table {
                        border-collapse: collapse;
                    }

                    th {
                        text-align: center;
                    }

                    td {
                        padding: 8px;
                        height: 80px;
                        width: 80px;
                    }
                </style>

                <?php

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
                echo "<h5>Data of " . $firstdayofMonth . ' : ' . $lastday . "</h5>";
                echo '<table class="table table-bordered table-striped">';
                echo '<tr><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th style="background:#DCDCDC !important;">Sat</th><th style="background:#DCDCDC !important;">Sun</th></tr>';
                $dayCount = 26;
                $totaldaysleft = $daysInMonth - 25;

                echo "<tr>";
                for ($i = 1; $i <= 7; $i++) {
                    if ($i < $firstDay) {
                        echo "<td></td>";
                    } else {
                        if ($dayCount <= $daysInMonth) {
                            echo "<td>$dayCount</td>";
                            $dayCount++;
                        }
                    }
                }
                echo "</tr>";
                while ($dayCount <= $daysInMonth) {
                    echo "<tr>";
                    for ($i = 1; $i <= 7 && $dayCount <= $daysInMonth; $i++) {
                        echo "<td>$dayCount</td>";
                        $dayCount++;
                    }
                    echo "</tr>";
                }
                echo "</table>";

                ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <?php
                $month = $report_month;
                $year = $report_year;
                $timestamp = mktime(0, 0, 0, $month, 1, $year);
                $daysInMonth = date("t", $timestamp);
                $firstDay = date("N", $timestamp);
                $dayCount = 1;
                $firstdayofMonth = $year . '-' . $month . '-1';
                $lastday = $year . '-' . $month . '-25';
                echo "<h5>Data of " . $firstdayofMonth . ' : ' . $lastday . "</h5>";
                echo '<table class="table table-bordered table-striped">';
                echo '<tr><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th style="background:#DCDCDC !important;">Sat</th><th style="background:#DCDCDC !important;">Sun</th></tr>';
                echo "<tr>";
                for ($i = 1; $i <= 7; $i++) {
                    if ($i < $firstDay) {
                        echo "<td></td>";
                    } else {
                        echo "<td>$dayCount</td>";
                        $dayCount++;
                    }
                }
                echo "</tr>";
                while ($dayCount <= 25) {
                    echo "<tr>";
                    for ($i = 1; $i <= 7 && $dayCount <= 25; $i++) {
                        echo "<td>$dayCount</td>";
                        $dayCount++;
                    }
                    echo "</tr>";
                }
                echo "</table>";

                ?>
            </div>
        </div>
    </div>
</div>