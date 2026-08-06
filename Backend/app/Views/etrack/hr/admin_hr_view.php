<?php
helper('localization');
$userlevel = session('userlevel');
$id_user = session('id_user');
$arrayuserlevel = array_map('intval', explode(',', $userlevel) ?? '');
?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/HR_admin/hr_dashboard'); ?>">
                            Dashboard
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                Admin HR Attendance
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <?php if (in_array('2010', $arrayuserlevel)) { ?>
        <div class="col-md-6">

            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('etrack/HR_admin/payroll_report'); ?>"
                        method="POST"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label for="inputEmail3" class="col-md-12 col-form-label">Month</label>
                                <select class="form-select" name="month">
                                    <?php
                                    for ($i = 1; $i < 13; $i++) {
                                        $monthName = translated_month_name($i);
                                        $thismonth = date('n');
                                        echo '<option value="' . $i . '"';
                                        if ($i == $thismonth) {
                                            echo 'selected';
                                        }
                                        echo '>' . $monthName . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="inputEmail3" class="col-md-12 col-form-label">Year</label>
                                <select class="form-select" name='year'>
                                    <?php
                                    $endyear = date('Y');
                                    for ($i = $endyear; $i >= 2025; $i--) {
                                        echo "<option value='$i'>$i</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4 mt-4">
                                <button type="submit" class="btn btn-outline-success btn-xs waves-effect waves-light">
                                    View Monthly Payroll Report
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- <div class="card">
                <div class="card-body">
                    <form class="form-horizontal"
                        action="<?php echo base_url('etrack/HR_admin/download_attendance_report'); ?>" method="POST"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label for="inputEmail3" class="col-md-12 col-form-label">Month</label>
                                <select class="form-select" name="month">
                                    <?php
                                    for ($i = 1; $i < 13; $i++) {
                                        $monthName = translated_month_name($i);
                                        $thismonth = date('n');
                                        echo '<option value="' . $i . '"';
                                        if ($i == $thismonth) {
                                            echo 'selected';
                                        }
                                        echo '>' . $monthName . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="inputEmail3" class="col-md-12 col-form-label">Year</label>
                                <select class="form-select" name='year'>
                                    <?php
                                    $endyear = date('Y');
                                    for ($i = $endyear; $i >= 2025; $i--) {
                                        echo "<option value='$i'>$i</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4 mt-4">
                                <button type="submit" class="btn btn-outline-success btn-xs waves-effect waves-light">
                                    DOWNLOAD Monthly Payroll Process Report
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div> -->
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal"
                        action="<?php echo base_url('etrack/attendance/attendance_montly_report'); ?>" method="post"
                        id="submitForm"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label for="inputEmail3" class="col-md-12 col-form-label">Month</label>
                                <select class="form-select" name="month">
                                    <?php
                                    for ($i = 1; $i < 13; $i++) {
                                        $monthName = translated_month_name($i);
                                        $thismonth = date('n');
                                        echo '<option value="' . $i . '"';
                                        if ($i == $thismonth) {
                                            echo 'selected';
                                        }
                                        echo '>' . $monthName . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="inputEmail3" class="col-md-12 col-form-label">Year</label>
                                <select class="form-select" name='year'>
                                    <?php
                                    $endyear = date('Y');
                                    for ($i = $endyear; $i >= 2025; $i--) {
                                        echo "<option value='$i'>$i</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4 mt-4">
                                <button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light"
                                    id="submitButton">DOWNLOAD Monthly Payroll Detailed Report</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal"
                        action="<?php echo base_url('etrack/HR_admin/apply_admin_wfh'); ?>" method="POST"><?= csrf_field() ?>
                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">User</label>
                            <div class="col-8 col-xl-9">
                                <select class="form-select " name="temp_user" required="">
                                    <?php foreach ($all_users as $users) {
                                        echo '<option value="' . $users['id_user'] . '">' . $users['name'] . ' ' . $users['last_name'] . '</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Number of Leaves</label>
                            <div class="col-8 col-xl-9">
                                <select class="form-select" name="value">
                                    <option value="1">Full Day</option>
                                    <option value=".5">Half Day</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Start date</label>
                            <div class="col-8 col-xl-9">
                                <input id="start_date" name="start_date" required class="date-picker form-control"
                                    placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'"
                                    onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)"
                                    value="">
                                <script>
                                    function timeFunctionLong(input) {
                                        setTimeout(function() {
                                            input.type = 'text';
                                        }, 60000);
                                    }
                                </script>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Remarks</label>
                            <div class="col-8 col-xl-9">
                                <input type="text" class="form-control" name="remarks" value="">
                            </div>
                        </div>
                        <div class="justify-content-end row">
                            <div class="col-8 col-xl-9">
                                <button type="submit" onclick="this.form.submit(); this.disabled=true;"
                                    class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                    Apply Work From Home
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('etrack/attendance/view'); ?>" method="POST"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-12  mb-2">
                                <label for="inputEmail3" class="col-form-label">User</label>
                                <select class="form-select " name="temp_user" required="">
                                    <?php foreach ($all_users as $users) {
                                        echo '<option value="' . $users['id_user'] . '">' . $users['name'] . ' ' . $users['last_name'] . '</option>';
                                    } ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="inputEmail3" class="col-md-12 col-form-label">Month</label>
                                <select class="form-select" name="month">
                                    <?php
                                    for ($i = 1; $i < 13; $i++) {
                                        $monthName = translated_month_name($i);
                                        $thismonth = date('n');
                                        echo '<option value="' . $i . '"';
                                        if ($i == $thismonth) {
                                            echo 'selected';
                                        }
                                        echo '>' . $monthName . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="inputEmail3" class="col-md-12 col-form-label">Year</label>
                                <select class="form-select" name='year'>
                                    <?php
                                    $endyear = date('Y');
                                    for ($i = $endyear; $i >= 2025; $i--) {
                                        echo "<option value='$i'>$i</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4 mt-4">
                                <input type="hidden" name="return_page" value="3">
                                <button type="submit" class="btn btn-outline-primary btn-xs waves-effect waves-light">
                                    View Attendance Dashboard Employee
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal"
                        action="<?php echo base_url('etrack/Attendance_admin/user_ac_statement'); ?>" method="POST"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="inputEmail3" class="col-form-label">User</label>
                                <select class="form-select " name="user_select" required="">
                                    <?php foreach ($all_users as $users) {
                                        echo '<option value="' . $users['id_user'] . '">' . $users['name'] . ' ' . $users['last_name'] . '</option>';
                                    } ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="inputEmail3" class="col-md-12 col-form-label">Start date</label>
                                <input id="start_date" required name="start_date" class="date-picker form-control"
                                    placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'"
                                    onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)"
                                    value="">
                                <script>
                                    function timeFunctionLong(input) {
                                        setTimeout(function () {
                                            input.type = 'text';
                                        }, 60000);
                                    }
                                </script>
                            </div>
                            <div class="col-md-4">
                                <label for="inputEmail3" class="col-md-12 col-form-label">End date</label>
                                <input id="start_date" required name="end_date" class="date-picker form-control"
                                    placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'"
                                    onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)"
                                    value="">
                                <script>
                                    function timeFunctionLong(input) {
                                        setTimeout(function () {
                                            input.type = 'text';
                                        }, 60000);
                                    }
                                </script>
                            </div>
                            <div class="col-md-4 mt-4">
                                <button type="submit" class="btn btn-outline-warning btn-xs waves-effect waves-light">
                                    View Access Card Data by Employee
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('etrack/attendance/wfh_statement_view/3'); ?>"
                        method="POST"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="inputEmail3" class="col-form-label">User</label>
                                <select class="form-select " name="user_select" required="">
                                    <?php foreach ($all_users as $users) {
                                        echo '<option value="' . $users['id_user'] . '">' . $users['name'] . ' ' . $users['last_name'] . '</option>';
                                    } ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="inputEmail3" class="col-md-12 col-form-label">Month</label>
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
                                <label for="inputEmail3" class="col-md-12 col-form-label">Year</label>
                                <select class="form-select" name='year'>
                                    <?php
                                    $endyear = date('Y');
                                    for ($i = $endyear; $i >= 2025; $i--) {
                                        echo "<option value='$i'>$i</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4 mt-4">
                                <button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">
                                    View Work From Home by Employee
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div> -->
    <?php } ?>
</div>
</div>
<script>
    let form = document.getElementById('submitForm');
    if (form) {
        form.addEventListener('submit', function() {
            var button = document.getElementById('submitButton');
            if (button) {
                button.disabled = true;
                button.innerHTML = 'Submitting...';

                // Re-enable after 5 seconds (or any safe value)
                setTimeout(function() {
                    button.disabled = false;
                    button.innerHTML = 'DOWNLOAD Monthly Payroll Detailed Report';
                }, 5000);
            }
        });
    }
</script>