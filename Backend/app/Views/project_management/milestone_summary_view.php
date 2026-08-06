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
            <h4 class="page-title">Milestones Summary</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6 col-md-6 col-lg-6">
        <div class="card">
            <div class="card-body">


                <form action="<?php echo base_url('Project_Manage/MileStones/milestones_summary'); ?>" method="post" autocomplete="off"><?= csrf_field() ?>
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
                                        <th style="width: 60px !important;
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
                                        <th style="
  margin: auto;

  background-color: #d4e69c !important;">Acc</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php

                                    $milestoneMap = [];

                                    foreach ($milestone_values as $m) {
                                        $milestoneMap[$m['ucn_id']] = $m;
                                    }

                                    $po_value_array = array_fill(0, 13, 0);
                                    $m_value_array  = array_fill(0, 13, 0);
                                    $am_month_total = array_fill(1, 12, 0);

                                    $previous_am = '';

                                    $userlevel = session()->get('userlevel');
                                    $arrayuserlevel = explode(',', $userlevel);

                                    foreach ($milestoneSummary_list ?? [] as $data) {

                                        $ucn = $data['ucn_id'];

                                        if (isset($data['month_0_percent']) && $data['month_0_percent'] > 99) {
                                            continue;
                                        }

                                        if ($ucn == 896 || $ucn == 897) {
                                            continue;
                                        }

                                        $m = $milestoneMap[$ucn] ?? [];
                                        $project_manager = $data['project_manager'];
                                        if (!in_array('69', $arrayuserlevel) && strpos($project_manager, session()->get('name')) === false) {
                                            continue;
                                        }

                                        $current_am = trim($data['account_manager']);

                                        // AM TOTAL ROW
                                        if ($previous_am != '' && $previous_am != $current_am) {
                                            /*  echo '<tr style="background:#f2f2f2;font-weight:bold;">';
                                            echo '<td colspan="5" align="right"></td>';

                                            for ($i = 1; $i <= 12; $i++) {
                                                echo '<td class="right">' . number_format($am_month_total[$i]) . '</td>';
                                            }

                                            echo '<td></td>';
                                            echo '</tr>';

                                            $am_month_total = array_fill(1, 12, 0); */
                                        }

                                    ?>

                                        <tr <?= ($data['status'] == 10) ? 'style="background-color:#e69ca869;"' : ''; ?>>

                                            <!-- UCN -->
                                            <td>

                                                <?= $data['ucn_id'] ?>


                                            </td>

                                            <td><?= $data['name'] ?></td>
                                            <td><?= $data['client_name'] ?></td>
                                            <td><?= $data['project_manager'] ?></td>

                                            <!-- PO VALUE -->
                                            <td class="right">
                                                <?php
                                                if (!empty($data['po_value'])) {
                                                    $po_value_array[0] += $data['po_value'];
                                                    echo number_format($data['po_value']);
                                                }
                                                ?>
                                            </td>

                                            <!-- MONTHS -->
                                            <?php for ($i = 1; $i <= 12; $i++) {

                                                $field = "month_{$i}_percent";
                                                $val = $m[$field] ?? 0;

                                            ?>
                                                <td class="right">
                                                    <?php
                                                    if ($val > 0) {
                                                        echo '<span style="color:green;">' . number_format($val) . '</span>';

                                                        $m_value_array[$i] += $val;
                                                        $am_month_total[$i] += $val;
                                                    } elseif ($val < 0) {
                                                        echo '<span style="color:red;">' . number_format($val) . '</span>';
                                                    }
                                                    ?>
                                                </td>
                                            <?php } ?>

                                            <!-- ACCOUNT MANAGER -->
                                            <td><?= $data['account_manager'] ?></td>

                                        </tr>

                                    <?php

                                        $previous_am = $current_am;
                                    }

                                    // LAST AM TOTAL ROW
                                    if ($previous_am != '') {

                                        echo '<tr style="background:#f2f2f2;font-weight:bold;">';
                                        echo '<td colspan="5" align="right">Total</td>';

                                        for ($i = 1; $i <= 12; $i++) {
                                            echo '<td class="right">' . number_format($am_month_total[$i]) . '</td>';
                                        }

                                        echo '<td></td>';
                                        echo '</tr>';
                                    }

                                    // GRAND TOTAL
                                    //  echo '<tr style="background:#f2f2f2;font-weight:bold;">';
                                    //  echo '<td colspan="5" align="right">Grand Total</td>';

                                    // for ($i = 1; $i <= 12; $i++) {
                                    //   //  echo '<td class="right">' . number_format($m_value_array[$i]) . '</td>';
                                    //  }

                                    //  echo '<td></td>';
                                    //  echo '</tr>';

                                    ?>

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
            yearSelect.selectedIndex = 0; // This selects the first option (empty option)
        }
    });
</script>