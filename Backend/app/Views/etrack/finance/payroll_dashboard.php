<?php
helper('localization');
$userlevel = session('userlevel');
$id_user = session('id_user');
$arrayuserlevel  = array_map('intval', explode(',', $userlevel) ?? '');
?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">
                Payroll Dashboard
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/Fin_admin/payroll_report'); ?>" method="POST"><?= csrf_field() ?>
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
                                Monthly Payroll Process Report
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>




    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/attendance/attendance_montly_report'); ?>" method="post" id="submitForm"><?= csrf_field() ?>
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
                            <button type="submit" onSubmit="document.getElementById('submit').disabled=true;" class="btn btn-outline-info btn-xs waves-effect waves-light" id="submitButton">DOWNLOAD Monthly Payroll Detailed Report</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">

                <form class="form-inline" enctype="multipart/form-data" action="<?php echo base_url('etrack/Fin_admin/payslip_import'); ?>" method="post" id="submitForm"><?= csrf_field() ?>
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
                            <div class="input-group file">
                                <input type="file" name="file" id="file" accept=".xlsx" required>
                            </div>
                        </div>
                        <div class="col-md-4 mt-2">
                            <button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light" id="submitButton">
                                Import Payslip
                            </button>
                        </div>
                        <div class="col-md-4 mt-2">
                        </div>
                        <div class="col-md-4 mt-2">
                            <a href="<?php echo base_url('assets/assets/uploads/sample_users_detail.xlsx'); ?>" class="btn btn-outline-info waves-effect btn-sm waves-light">Template</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/Fin_admin/delete_payslip'); ?>" method="POST"><?= csrf_field() ?>
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
                            <button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                Delete Payslips
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/Fin_admin/download_payslip'); ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label for="inputEmail3" class="col-md-12 col-form-label">Employee</label>

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
                        <div class="col-md-4 mt-2">
                            <button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                Download Payslip
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>