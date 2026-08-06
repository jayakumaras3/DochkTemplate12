<?php
helper('localization');
$userlevel = session('userlevel');
$id_user = session('id_user');
$arrayuserlevel  = array_map('intval', explode(',', $userlevel) ?? '');
?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/HR_admin/payroll_report'); ?>">
                            Payroll Attendance
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                Add Grace
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-4 col-md-4">
        <!-- Portlet card -->
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/HR_admin/add_new_grace'); ?>" method="POST"><?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Number of Days</label>
                        <div class="col-8 col-xl-9">
                            <input type="text" name="numdays" class="form-control" required value="">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Date for the Month to Apply Grace</label>
                        <div class="col-4 col-xl-3">
                            <select name="month" class="form-control">
                                <?php
                                $currentmonth = date('m');
                                for ($i = 1; $i <= 12; $i++) {
                                    $formatted_number = sprintf("%02d", $i);
                                    $month = translated_month_name($i);
                                    echo '<option value="' . $formatted_number . '"';
                                    if ($currentmonth == $i) {
                                        echo 'SELECTED';
                                    }
                                    echo '>' . $month . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-4 col-xl-3">
                            <select name="year" class="form-control">
                                <?php
                                $current_year = date('Y');
                                $start_year = 2025;
                                for ($year = $start_year; $year <=  $current_year; $year++) {
                                    echo '<option value="' . $year . '"';
                                    if ($year == $current_year) {
                                        echo 'SELECTED';
                                    }
                                    echo '>' . $year . '</option>';
                                }
                                ?>
                            </select>
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
                            <input type="hidden" name="temp_user" value="<?php echo $temp_user; ?>">
                            <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                Add Grace
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div> <!-- end col-->
    <div class="col-xl-8 col-md-8">
        <!-- Portlet card -->
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Date</th>
                            <th>Number</th>
                            <th>HR Comment</th>
                            <th>Status</th>
                            <th>Biz Comment</th>
                            <th>Del</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        foreach ($get_grace as $data) {
                            $j++;
                            echo '<tr><td>';
                            echo $j;
                            echo '</td><td>';
                            echo $data['date'];
                            echo '</td><td>';
                            echo $data['numgrace'];
                            echo '</td><td>';
                            echo $data['remarks_hr'];
                            echo '</td><td>';
                            $biz_status = $data['bz_status'];
                            $hr_status = $data['hr_status'];
                            if ($biz_status == 1 && $hr_status == 1) {
                                echo 'Approved';
                            } elseif ($biz_status == 0 && $hr_status == 1) {
                                echo 'Await. Approval';
                            } else {
                                echo 'Deleted';
                            }
                            echo '</td><td>';
                            echo $data['remarks_biz'];
                            echo '</td><td>';
                            if ($hr_status == 1 &&  $biz_status == 0) {
                        ?>
                                <form class="form-horizontal" action="<?php echo base_url('etrack/HR_admin/delete_grace'); ?>" method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="grace_id" value="<?php echo $data['grace_id']; ?>">
                                    <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-trash-can-outline"></span></button>
                                </form>
                        <?php
                            }
                            echo '</td></tr>';
                        }

                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> <!-- end col-->
</div>