<style>
    .right {
        text-align: right;
        margin-right: 1em;
    }

    .left {
        text-align: left;
        margin-left: 1em;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/PM_ucn'); ?>">My UCN</a></li>
                </ol>
            </div>
            <h4 class="page-title">WIP Summary</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6 col-md-6 col-lg-6">
        <div class="card">
            <div class="card-body">


                <form action="<?php echo base_url('Project_Manage/PM_wip'); ?>" method="post" autocomplete="off"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6">
                            <select id="year" name="year" class="form-control">

                                <?php
                                $currentYear = date('Y');
                                $selectedYear = isset($_POST['year']) ? $_POST['year'] : '';

                                for ($year = $currentYear; $year >= 2023; $year--) {
                                    echo "<option value='$year' " . ($year == $selectedYear ? 'selected' : '') . ">$year</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2 mt-1">
                            <button type="submit" class="btn btn-outline-info btn-xs square-pill waves-effect waves-light">Search by Year</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="col-6 col-md-6 col-lg-6">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo base_url('Project_Manage/PM_wip/Download_WIP_Report'); ?>" method="post" autocomplete="off"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-6">
                            <select id="year" name="year" class="form-control">

                                <?php
                                $currentYear = date('Y');
                                $selectedYear = isset($_POST['year']) ? $_POST['year'] : '';

                                for ($year = $currentYear; $year >= 2023; $year--) {
                                    echo "<option value='$year' " . ($year == $selectedYear ? 'selected' : '') . ">$year</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2 mt-1">
                            <button type="submit" class="btn btn-outline-danger btn-xs square-pill waves-effect waves-light">Download Report</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<div class="row" style="font-size: 11px !important;">
    <div class="card">
        <div class="card-body">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="tab-content">
                    <div class="tab-pane show active" id="inprogress">
                        <div class="table-responsive">
                            <table class="table table-bordered w-100">
                                <thead>
                                    <tr>
                                        <th style="
  margin: auto;
  background-color: #a0e7ce !important;">UCN</th>
                                        <th style="width: 200px !important;
  margin: auto;
  background-color: #a0e7ce !important;">Name</th>
                                        <th style="width: 100px !important;
  margin: auto;
  background-color: #a0e7ce !important;">Client</th>
                                        <th style="width: 100px !important;
  margin: auto;
  background-color: #a0e7ce !important;">PM</th>

                                        <th style="
  margin: auto;
  background-color: #a0e7ce !important;">PO</th>
                                        <th style="
  margin: auto;
  background-color: #a0e7ce !important;">START</th>
                                        <th style="
  margin: auto;
  background-color: #a0e7ce !important;">END</th>
                                        <th style="width: 4% !important;
  margin: auto;
  background-color: #d4e69c !important;">DEC</th>
                                        <th style="width: 4% !important;
  margin: auto;
  background-color: #9ca0e658 !important;">JAN</th>
                                        <th style="width: 4% !important;
  margin: auto;
  background-color: #9ca0e658 !important;">FEB</th>
                                        <th style="width: 4% !important;
  margin: auto;
  background-color: #9ca0e658 !important;">MAR</th>
                                        <th style="width: 4% !important;
  margin: auto;
  background-color: #9ca0e658 !important;">APR</th>
                                        <th style="width: 4% !important;
  margin: auto;
  background-color: #9ca0e658 !important;">MAY</th>
                                        <th style="width: 4% !important;
  margin: auto;
  background-color: #9ca0e658 !important;">JUN</th>
                                        <th style="width: 4% !important;
  margin: auto;
  background-color: #9ca0e658 !important;">JUL</th>
                                        <th style="width: 4% !important;
  margin: auto;
  background-color: #9ca0e658 !important;">AUG</th>
                                        <th style="width: 4% !important;
  margin: auto;
  background-color: #9ca0e658 !important;">SEP</th>
                                        <th style="width: 4% !important;
  margin: auto;
  background-color: #9ca0e658 !important;">OCT</th>
                                        <th style="width: 4% !important;
  margin: auto;
  background-color: #9ca0e658 !important;">NOV</th>
                                        <th style="width: 4% !important;
  margin: auto;
  background-color: #9ca0e658 !important;">DEC</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php

                                    $percentMap = [];

                                    foreach ($percent_data as $p) {
                                        $percentMap[$p['ucn_id']] = $p;
                                    }

                                    $po_value_array = array_fill(0, 13, 0);


                                    foreach ($wip_list as $data) {

                                        $ucn = $data['ucn_id'];

                                        // Skip unwanted UCN
                                        if ($ucn == 896 || $ucn == 897) {
                                            continue;
                                        }

                                        // Get percentage row
                                        $per = $percentMap[$ucn] ?? [];

                                        $prev_percent = array_fill(0, 13, 0);

                                    ?>

                                        <tr <?= ($data['status'] == 10) ? 'style="background-color:#e69ca869;"' : ''; ?>>
                                            <td>
                                                <?php $userlevel = session()->get('userlevel');

                                                $arrayuserlevel = explode(',', $userlevel);
                                                ?>
                                                <form action="<?= base_url('Project_Manage/PM_ucn/edit_ucn') ?>" method="POST">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="ucn_id" value="<?= $data['ucn_id'] ?>">
                                                    <input type="hidden" name="client" value="<?= $data['client_name'] ?>">
                                                    <input type="hidden" name="return_url" value="2">

                                                    <button type="submit" class="btn btn-outline-dark btn-xs">
                                                        <?= $data['ucn_id'] ?>
                                                    </button>
                                                </form>

                                            </td>
                                            <td><?= $data['name']; ?></td>

                                            <td><?= $data['client_name']; ?></td>

                                            <td><?= $data['project_manager']; ?></td>

                                            <td class="right">
                                                <?php
                                                if (!empty($data['po_value'])) {

                                                    $po_value_array[0] += $data['po_value'];

                                                    echo number_format($data['po_value']);
                                                }
                                                ?>
                                            </td>

                                            <td>
                                                <?php
                                                if ($data['start_dt']) {
                                                    echo date("M-y", strtotime($data['start_dt']));
                                                } else {
                                                    echo date("M-y", $data['created_on']);
                                                }
                                                ?>
                                            </td>

                                            <td>
                                                <?php
                                                if ($data['status'] == 10) {

                                                    if ($data['end_dt']) {
                                                        echo date("M-y", strtotime($data['end_dt']));
                                                    } else {
                                                        echo date("M-y", $data['last_updated_on']);
                                                    }
                                                }
                                                ?>
                                            </td>

                                            <?php


                                            //  MONTH LOOP


                                            for ($i = 0; $i <= 12; $i++) {

                                                $field = "month_{$i}_percent";

                                                $percent = $per[$field] ?? 0;

                                            ?>

                                                <td class="right">

                                                    <?php

                                                    if ($percent > 0) {
                                                        echo '<span style="color:green;">' . $percent . '%</span>';
                                                    } elseif ($percent < 0) {
                                                        echo '<span style="color:red;">' . $percent . '%</span>';
                                                    }

                                                    // Calculation
                                                    if ($i > 0 && $percent > 0) {

                                                        $amount = $data['po_value'] * ($percent - $prev_percent[$i - 1]) / 100;
                                                        if ($amount > 0) {
                                                            echo '<br><span style="color:green;">' . number_format($amount, 0) . '</span>';
                                                        } elseif ($amount < 0) {
                                                            echo '<br><span style="color:red;">' . number_format($amount, 0) . '</span>';
                                                        }

                                                        $po_value_array[$i] += $amount;
                                                    }

                                                    $prev_percent[$i] = $percent;

                                                    ?>

                                                </td>

                                            <?php } ?>

                                        </tr>

                                    <?php } ?>

                                    <!-- TOTAL ROW -->

                                    <tr>

                                        <th colspan="8"></th>

                                        <?php for ($i = 1; $i <= 12; $i++) { ?>

                                            <th class="right">
                                                <?= number_format($po_value_array[$i]); ?>
                                            </th>

                                        <?php } ?>

                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let yearSelect = document.getElementById("year");

        if (!yearSelect.value) {
            yearSelect.selectedIndex = 0;
        }
    });
</script>