<?php
$userlevel = session('userlevel');
$id_user = session('id_user');
$arrayuserlevel  = array_map('intval', explode(',', $userlevel) ?? '');
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
                Admin HR Personal Data
            </h4>
        </div>
    </div>
</div>

<?php if (in_array('2010', $arrayuserlevel)) { ?>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('etrack/HR_admin/view_personal_data'); ?>" method="POST"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-12  mb-2">
                                <label for="inputEmail3" class="col-form-label">Active Users</label>
                                <select class="form-select " name="temp_user" required="">
                                    <?php foreach ($all_users as $users) {
                                        echo '<option value="' . $users['id_user'] . '">' . $users['name'] . ' ' . $users['last_name'] . '</option>';
                                    } ?>
                                </select>
                            </div>
                            <div class="col-md-4 mt-2">
                                <button type="submit" class="btn btn-outline-primary btn-xs waves-effect waves-light">
                                    View Personal Data
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('etrack/HR_admin/doc_for_approval'); ?>" method="POST"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-12 mt-2">
                                <button type="submit" class="btn btn-outline-warning btn-xs waves-effect waves-light">
                                    Documents for Approval
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('etrack/HR_admin/download_data'); ?>" method="POST"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-12 mt-2">
                                <button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                    Download All Employee Data
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('etrack/HR_admin/view_appraisal_data'); ?>" method="POST"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-12  mb-2">
                                <label for="inputEmail3" class="col-form-label">Active Users</label>
                                <select class="form-select " name="temp_user" required="">
                                    <?php foreach ($all_users as $users) {
                                        echo '<option value="' . $users['id_user'] . '">' . $users['name'] . ' ' . $users['last_name'] . '</option>';
                                    } ?>
                                </select>
                            </div>
                            <div class="col-md-4 mt-2">
                                <button type="submit" class="btn btn-outline-success btn-xs waves-effect waves-light">
                                    View Appraisal Data
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
                    <form class="form-horizontal" action="<?php echo base_url('etrack/HR_admin/view_all_appraisals'); ?>" method="POST"><?= csrf_field() ?>
                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">From Date</label>
                            <div class="col-8 col-xl-9">
                                <input id="start_date" name="start_dt" required class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="">
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
                            <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">To Date</label>
                            <div class="col-8 col-xl-9">
                                <input id="end_date" name="end_date" required class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="">
                                <script>
                                    function timeFunctionLong(input) {
                                        setTimeout(function() {
                                            input.type = 'text';
                                        }, 60000);
                                    }
                                </script>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mt-2">
                                <button type="submit" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                    View Appraisals
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('etrack/HR_admin/view_salary_slip'); ?>" method="POST"><?= csrf_field() ?>
                        <div class="row mb-2">
                            <label class="col-4 col-xl-3 col-form-label">Select User</label>
                            <div class="col-8 col-xl-9">
                                <select class="form-select " name="temp_user" required="">
                                    <?php foreach ($all_users as $users) {
                                        echo '<option value="' . $users['id_user'] . '">' . $users['name'] . ' ' . $users['last_name'] . '</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="col-4 col-xl-3 col-form-label">First Day of the Month</label>
                            <div class="col-8 col-xl-9">
                                <input id="salary_month" name="salary_month" required class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="">
                                <script>
                                    function timeFunctionLong(input) {
                                        setTimeout(function() {
                                            input.type = 'text';
                                        }, 60000);
                                    }
                                </script>
                            </div>
                        </div>
                        <div class="col-md-4 mt-2">
                            <button type="submit" class="btn btn-outline-success btn-xs waves-effect waves-light">
                                View Salary Slip
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>
<?php } ?>