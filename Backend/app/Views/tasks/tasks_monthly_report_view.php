<?php helper('localization'); ?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">

                </ol>
            </div>
            <h4 class="page-title">
                Report
            </h4>
        </div>
    </div>
</div>
<div class="row">

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5>Domain level Report</h5>
                <span style="color:red">"Kindly select a date range to generate the report on a monthly basis, ensuring
                    the system performs
                    efficiently without slowing down."</span>
                <form class="form-horizontal" action="<?php echo base_url('task/task_manage/effort_domain_report'); ?>"
                    method="POST"><?= csrf_field() ?>
                    <br />
                    <div class="row">
                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Start Date</label>
                            <div class="col-8 col-xl-9">
                                <input id="start_date" name="start_date" class="date-picker form-control"
                                    placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'"
                                    onclick="this.type='date'" onblur="this.type='text'"
                                    onmouseout="timeFunctionLong(this)" value="" required>
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
                            <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">End Date</label>
                            <div class="col-8 col-xl-9">
                                <input id="end_date" name="end_date" class="date-picker form-control"
                                    placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'"
                                    onclick="this.type='date'" onblur="this.type='text'"
                                    onmouseout="timeFunctionLong(this)" value="" required>
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
                            <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Domain</label>
                            <div class="col-8 col-xl-9">
                                <select class="form-select" name='domain'>
                                    <option value="0">All Domain</option>
                                    <?php
                                    if (!empty($domain)) {
                                        foreach ($domain as $eachdomain) { ?>
                                            <option value="<?php echo $eachdomain['value'] . '|' . $eachdomain['name']; ?>">
                                                <?php echo $eachdomain['name']; ?>
                                            </option>

                                    <?php }
                                    }
                                    ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <button type="submit" class="btn btn-outline-primary btn-xs waves-effect waves-light">Domain
                                Level
                                Report</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5>Task Effort Monthly Report</h5><br />
                <form class="form-horizontal" action="<?php echo base_url('task/task_manage/effort_montly_report'); ?>"
                    method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Month</label>
                            <div class="col-8 col-xl-9">
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
                        </div>
                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-4 col-xl-3 col-form-label">Year</label>
                            <div class="col-8 col-xl-9">
                                <select class="form-select" name='year'>
                                    <?php
                                    $endyear = date('Y');
                                    for ($i = $endyear; $i >= 2025; $i--) {
                                        echo "<option value='$i'>$i</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">Effort
                                Report</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>