<?php helper('localization'); ?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">

                </ol>
            </div>
            <h4 class="page-title">
                Attendance Monthly Report
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/attendance/attendance_montly_report'); ?>" method="post" id="submitForm"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-12 mb-2">
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
                        <div class="col-md-12">
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
                        <div class="col-md-12 mt-3">
                            <button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light" id="submitButton">Attendance Report</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
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
                    button.innerHTML = 'Attendance Report';
                }, 5000);
            }
        });
    }
</script>