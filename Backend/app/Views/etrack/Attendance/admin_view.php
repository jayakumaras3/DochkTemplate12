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
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/dashboard'); ?>">
                            Dashboard
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                Admin Attendance
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" enctype="multipart/form-data" action="<?php echo base_url('etrack/Attendance_admin/uploadAccessCard'); ?>" method="post" id="submitForm"><?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Start date</label>
                        <div class="col-8 col-xl-9">
                            <input id="start_date" name="start_date" class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="" required>
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
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Upload</label>
                        <div class="col-8 col-xl-9">
                            <input type="file" name="file" id="file" accept=".xlsx" required>
                        </div>
                    </div>
                    <div class="justify-content-end row">
                        <div class="col-8 col-xl-9">
                            <button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light" id="submitButton">
                                Upload Access Card Data
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
                <form class="form-horizontal" action="<?php echo base_url('etrack/Attendance_admin/viewAccessCardDetails'); ?>" method="POST"><?= csrf_field() ?>
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
                            <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-success btn-xs waves-effect waves-light">
                                View Access Card Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/Attendance_admin/deleteAccessCard'); ?>" method="POST"><?= csrf_field() ?>
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
                            <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                Delete Access Card Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</div>
</div>