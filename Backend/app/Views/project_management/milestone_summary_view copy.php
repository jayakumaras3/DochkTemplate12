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

                                    $po_value_array = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

                                    $m_value_array = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

                                    // ACCOUNT MANAGER TOTALS
                                    $am_month_total = array_fill(1, 12, 0);

                                    $previous_am = '';

                                    $acct_mng_total = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
                                    $acct_mng = 0;

                                    foreach ($milestoneSummary_list as $data) {

                                        $po_value_array = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

                                        if ($data['month_0_percent'] > 99) {
                                            continue;
                                        }

                                        $ucn = $data['ucn_id'];
                                        if ($ucn == 896 || $ucn == 897) {
                                            continue;
                                        }

                                        $userlevel = session()->get('userlevel');
                                        $arrayuserlevel = explode(',', $userlevel);

                                        // CURRENT ACCOUNT MANAGER
                                        $current_am = trim($data['account_manager']);

                                        // PRINT PREVIOUS AM TOTAL ROW
                                        if ($previous_am != '' && $previous_am != $current_am) {
                                            $acct_mng++;
                                            echo '<tr style="background:#f2f2f2;font-weight:bold;">';

                                            echo '<td colspan="5" align="right">';
                                            // echo $previous_am . ' Total';
                                            echo '</td>';

                                            for ($i = 1; $i <= 12; $i++) {

                                                echo '<td class="right">';
                                                echo number_format((float)$am_month_total[$i]);
                                                $acct_mng_total[$acct_mng] += $am_month_total[$acct_mng];
                                                echo '</td>';
                                            }
                                            echo '<td>' . number_format((float)$acct_mng_total[$acct_mng]) . '</td>';

                                            echo '</tr>';

                                            // RESET ACCOUNT MANAGER TOTALS
                                            $am_month_total = array_fill(1, 12, 0);
                                        }

                                    ?>

                                        <?php
                                        if ($data['status'] == 10) {
                                            echo '<tr style="background-color:#e69ca869; border-color: white;">';
                                        } else {
                                            echo '<tr>';
                                        }
                                        ?>

                                        <!-- UCN -->
                                        <?php if (in_array('69', $arrayuserlevel)) { ?>

                                            <td>
                                                <form action="<?php echo base_url('Project_Manage/PM_purchase_order/edit_purchase_order') ?>" method="POST">

                                                    <?= csrf_field() ?>

                                                    <input type="hidden" name="ucn_id" value="<?php echo $data['ucn_id'] ?>">

                                                    <input type="hidden" name="client" value="<?php echo $data['client_name'] ?>">
                                                    <input type="hidden" name="return_url" value="2">
                                                    <button type="submit" class="btn btn-outline-dark btn-xs waves-effect waves-light">

                                                        <?php echo $data['ucn_id'] ?>

                                                    </button>

                                                </form>
                                            </td>

                                        <?php } else { ?>

                                            <td><?php echo $data['ucn_id']; ?></td>

                                        <?php } ?>

                                        <td><?php echo $data['name']; ?></td>

                                        <td><?php echo $data['client_name']; ?></td>

                                        <td><?php echo $data['project_manager']; ?></td>

                                        <td class="right">

                                            <?php

                                            if ($data['po_value'] > 0) {

                                                $po_value_array[0] += $data['po_value'];

                                                echo number_format($data['po_value']);
                                            }

                                            ?>

                                        </td>


                                        <td class="right">

                                            <?php

                                            if ($data['month_1_percent'] > 0) {

                                                echo '<span style="color:green;">';
                                                echo number_format($data['month_1_percent']);
                                                echo '</span>';

                                                $m_value_array[1] += $data['month_1_percent'];

                                                $am_month_total[1] += $data['month_1_percent'];
                                            } elseif ($data['month_1_percent'] < 0) {

                                                echo '<span style="color:red;">';
                                                echo number_format($data['month_1_percent']);
                                                echo '</span>';
                                            }

                                            ?>

                                        </td>

                                        <td class="right">

                                            <?php

                                            if ($data['month_2_percent'] > 0) {

                                                echo '<span style="color:green;">';
                                                echo number_format($data['month_2_percent']);
                                                echo '</span>';

                                                $m_value_array[2] += $data['month_2_percent'];

                                                $am_month_total[2] += $data['month_2_percent'];
                                            } elseif ($data['month_2_percent'] < 0) {

                                                echo '<span style="color:red;">';
                                                echo number_format($data['month_2_percent']);
                                                echo '</span>';
                                            }

                                            ?>

                                        </td>

                                        <td class="right">

                                            <?php

                                            if ($data['month_3_percent'] > 0) {

                                                echo '<span style="color:green;">';
                                                echo number_format($data['month_3_percent']);
                                                echo '</span>';

                                                $m_value_array[3] += $data['month_3_percent'];

                                                $am_month_total[3] += $data['month_3_percent'];
                                            } elseif ($data['month_3_percent'] < 0) {

                                                echo '<span style="color:red;">';
                                                echo number_format($data['month_3_percent']);
                                                echo '</span>';
                                            }

                                            ?>

                                        </td>

                                        <!-- MONTH 4 -->
                                        <td class="right">

                                            <?php

                                            if ($data['month_4_percent'] > 0) {

                                                echo '<span style="color:green;">';
                                                echo number_format($data['month_4_percent']);
                                                echo '</span>';

                                                $m_value_array[4] += $data['month_4_percent'];

                                                $am_month_total[4] += $data['month_4_percent'];
                                            } elseif ($data['month_4_percent'] < 0) {

                                                echo '<span style="color:red;">';
                                                echo number_format($data['month_4_percent']);
                                                echo '</span>';
                                            }

                                            ?>

                                        </td>

                                        <!-- MONTH 5 -->
                                        <td class="right">

                                            <?php

                                            if ($data['month_5_percent'] > 0) {

                                                echo '<span style="color:green;">';
                                                echo number_format($data['month_5_percent']);
                                                echo '</span>';

                                                $m_value_array[5] += $data['month_5_percent'];

                                                $am_month_total[5] += $data['month_5_percent'];
                                            } elseif ($data['month_5_percent'] < 0) {

                                                echo '<span style="color:red;">';
                                                echo number_format($data['month_5_percent']);
                                                echo '</span>';
                                            }

                                            ?>

                                        </td>

                                        <!-- MONTH 6 -->
                                        <td class="right">

                                            <?php

                                            if ($data['month_6_percent'] > 0) {

                                                echo '<span style="color:green;">';
                                                echo number_format($data['month_6_percent']);
                                                echo '</span>';
                                            } elseif ($data['month_6_percent'] < 0) {

                                                echo '<span style="color:red;">';
                                                echo number_format($data['month_6_percent']);
                                                echo '</span>';
                                            }

                                            $m_value_array[6] += $data['month_6_percent'];

                                            $am_month_total[6] += $data['month_6_percent'];

                                            ?>

                                        </td>

                                        <!-- MONTH 7 -->
                                        <td class="right">

                                            <?php

                                            if ($data['month_7_percent'] > 0) {

                                                echo '<span style="color:green;">';
                                                echo number_format($data['month_7_percent']);
                                                echo '</span>';

                                                $m_value_array[7] += $data['month_7_percent'];

                                                $am_month_total[7] += $data['month_7_percent'];
                                            } elseif ($data['month_7_percent'] < 0) {

                                                echo '<span style="color:red;">';
                                                echo number_format($data['month_7_percent']);
                                                echo '</span>';
                                            }

                                            ?>

                                        </td>

                                        <!-- MONTH 8 -->
                                        <td class="right">

                                            <?php

                                            if ($data['month_8_percent'] > 0) {

                                                echo '<span style="color:green;">';
                                                echo number_format($data['month_8_percent']);
                                                echo '</span>';

                                                $m_value_array[8] += $data['month_8_percent'];

                                                $am_month_total[8] += $data['month_8_percent'];
                                            } elseif ($data['month_8_percent'] < 0) {

                                                echo '<span style="color:red;">';
                                                echo number_format($data['month_8_percent']);
                                                echo '</span>';
                                            }

                                            ?>

                                        </td>

                                        <!-- MONTH 9 -->
                                        <td class="right">

                                            <?php

                                            if ($data['month_9_percent'] > 0) {

                                                echo '<span style="color:green;">';
                                                echo number_format($data['month_9_percent']);
                                                echo '</span>';

                                                $m_value_array[9] += $data['month_9_percent'];

                                                $am_month_total[9] += $data['month_9_percent'];
                                            } elseif ($data['month_9_percent'] < 0) {

                                                echo '<span style="color:red;">';
                                                echo number_format($data['month_9_percent']);
                                                echo '</span>';
                                            }

                                            ?>

                                        </td>

                                        <!-- MONTH 10 -->
                                        <td class="right">

                                            <?php

                                            if ($data['month_10_percent'] > 0) {

                                                echo '<span style="color:green;">';
                                                echo number_format($data['month_10_percent']);
                                                echo '</span>';

                                                $m_value_array[10] += $data['month_10_percent'];

                                                $am_month_total[10] += $data['month_10_percent'];
                                            } elseif ($data['month_10_percent'] < 0) {

                                                echo '<span style="color:red;">';
                                                echo number_format($data['month_10_percent']);
                                                echo '</span>';
                                            }

                                            ?>

                                        </td>

                                        <!-- MONTH 11 -->
                                        <td class="right">

                                            <?php

                                            if ($data['month_11_percent'] > 0) {

                                                echo '<span style="color:green;">';
                                                echo number_format($data['month_11_percent']);
                                                echo '</span>';

                                                $m_value_array[11] += $data['month_11_percent'];

                                                $am_month_total[11] += $data['month_11_percent'];
                                            } elseif ($data['month_11_percent'] < 0) {

                                                echo '<span style="color:red;">';
                                                echo number_format($data['month_11_percent']);
                                                echo '</span>';
                                            }

                                            ?>

                                        </td>

                                        <!-- MONTH 12 -->
                                        <td class="right">

                                            <?php

                                            if ($data['month_12_percent'] > 0) {

                                                echo '<span style="color:green;">';
                                                echo number_format($data['month_12_percent']);
                                                echo '</span>';

                                                $m_value_array[12] += $data['month_12_percent'];

                                                $am_month_total[12] += $data['month_12_percent'];
                                            } elseif ($data['month_12_percent'] < 0) {

                                                echo '<span style="color:red;">';
                                                echo number_format($data['month_12_percent']);
                                                echo '</span>';
                                            }

                                            ?>

                                        </td>

                                        <td>
                                            <?php

                                            echo $data['account_manager'];

                                            ?>
                                        </td>
                                        </tr>

                                    <?php

                                        $previous_am = $current_am;
                                    }

                                    if ($previous_am != '') {

                                        echo '<tr style="background:#f2f2f2;font-weight:bold;">';

                                        echo '<td colspan="6" align="right">';
                                        echo $previous_am . ' Total';
                                        echo '</td>';

                                        for ($i = 1; $i <= 12; $i++) {

                                            echo '<td class="right">';
                                            echo number_format($am_month_total[$i]);
                                            echo '</td>';
                                        }

                                        echo '</tr>';
                                    }

                                    echo '<tr style="background:#f2f2f2;font-weight:bold;"><td colspan="6" align="right">Grand Total</td>';

                                    for ($i = 1; $i <= 12; $i++) {

                                        echo '<td class="right">';
                                        echo number_format($m_value_array[$i]);
                                        echo '</td>';
                                    }

                                    echo '</tr>';

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