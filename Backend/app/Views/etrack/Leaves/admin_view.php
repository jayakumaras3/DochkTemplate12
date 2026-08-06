<?php helper('localization'); ?>
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
                Admin Leave Dashboard
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/leaveadmin/add_leaves'); ?>" method="POST"><?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">User</label>
                        <div class="col-8 col-xl-9">
                            <select class="form-select " name="user_select" required="">
                                <?php foreach ($all_users as $users) {
                                    echo '<option value="' . $users['id_user'] . '">' . $users['name'] . ' ' . $users['last_name'] . '</option>';
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Type of Leave</label>
                        <div class="col-8 col-xl-9">
                            <select name="typeofLeave" class="form-control">
                                <option value="2">Earned Leaves</option>
                                <option value="3">Casual Leaves</option>
                                <option value="4">Restriced Leaves</option>
                                <option value="5">Paternity Leaves</option>
                                <option value="6">Compoff Leaves</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Number of Leaves</label>
                        <!-- <div class="col-8 col-xl-9">
                            <select name="numofLeaves" class="form-control">
                                <option value=".5" selected>Half Day</option>
                                <option value="1">1 Day</option>
                                <option value="2">2 Days</option>
                                <option value="3">3 Days</option>
                                <option value="4">4 Days</option>
                                <option value="5">5 Days</option>
                            </select>
                        </div> -->
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" name="numofLeaves" value="">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Remarks</label>
                        <div class="col-8 col-xl-9">
                            <input type="text" class="form-control" name="remarks" value="">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Start date</label>
                        <div class="col-8 col-xl-9">
                            <input id="start_date" name="start_date" class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="">
                            <script>
                                function timeFunctionLong(input) {
                                    setTimeout(function() {
                                        input.type = 'text';
                                    }, 60000);
                                }
                            </script>
                        </div>
                    </div>
                    <div class="justify-content-end row">
                        <div class="col-8 col-xl-9">
                            <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-info btn-xs waves-effect waves-light">
                                Add Leaves
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
                <form class="form-horizontal" action="<?php echo base_url('etrack/leaveadmin/user_leave_statement'); ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-8">
                            <label for="inputEmail3" class="col-form-label">User</label>
                            <select class="form-select " name="user_select" required="">
                                <?php foreach ($all_users as $users) {
                                    echo '<option value="' . $users['id_user'] . '">' . $users['name'] . ' ' . $users['last_name'] . '</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-4 mt-4">
                            <button type="submit" class="btn btn-outline-warning btn-xs waves-effect waves-light">
                                User Statement
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/leaveadmin/export_etLeaves'); ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-md-12 col-form-label">Start date</label>
                            <input id="start_date" required name="start_date" class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="">
                            <script>
                                function timeFunctionLong(input) {
                                    setTimeout(function() {
                                        input.type = 'text';
                                    }, 60000);
                                }
                            </script>
                        </div>
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-md-12 col-form-label">End date</label>
                            <input id="start_date" required name="end_date" class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="">
                            <script>
                                function timeFunctionLong(input) {
                                    setTimeout(function() {
                                        input.type = 'text';
                                    }, 60000);
                                }
                            </script>
                        </div>
                        <div class="col-md-4 mt-4">
                            <button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                Report Overview
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/leaveadmin/leave_balance_report'); ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
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
                                Leave Balance Report
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body">

                <form class="form-inline" enctype="multipart/form-data" action="<?php echo base_url('etrack/leaveadmin/import_bulk_etLeaves'); ?>" method="post" id="submitForm"><?= csrf_field() ?>
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
                            <div class="input-group file">
                                <input type="file" name="file" id="file" accept=".xlsx" required>
                            </div>
                        </div>
                        <div class="col-md-4 mt-2">
                            <button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light" id="submitButton">
                                Import BULK Leaves
                            </button>
                        </div>
                        <div class="col-md-4 mt-2">
                        </div>
                        <div class="col-md-4 mt-2">
                            <a href="<?php echo base_url('assets/assets/uploads/Leave_template.xlsx'); ?>" class="btn btn-outline-info waves-effect btn-sm waves-light">Template</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>