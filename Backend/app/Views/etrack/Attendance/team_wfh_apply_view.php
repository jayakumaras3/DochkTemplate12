<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/attendance/team_attendance'); ?>">
                            Team Attendance
                        </a>
                    </li>
                </ol> 
                
            </div>
            <h4 class="page-title">
                Apply Work From Home -  <?php echo $user_details[0]['name'].' '. $user_details[0]['last_name']; ?>
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-6 col-md-12">
        <!-- Portlet card -->
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/attendance/apply_team_wfh'); ?>" method="POST"><?= csrf_field() ?>
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
                            <input id="start_date" name="start_date" required class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="">
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
                            <input type="hidden" name="temp_user" value="<?php echo $temp_user; ?>" >
                            <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                Apply Work From Home
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div> <!-- end col-->
</div>