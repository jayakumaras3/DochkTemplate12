<?php
$userlevel = session('userlevel');
$id_user = session('id_user');
$arrayuserlevel  = array_map('intval', explode(',', $userlevel) ?? '');
?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">
                India Finance Dashboard <?php echo isset($year) ? ' - ' . $year : ''; ?>
            </h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 col-xl-3">
        <div class="card ">
            <div class="card-body">
                <table class="table table-sm table-bordered  mb-0">
                    <thead>
                        <tr style="background-color: #6b5e5e42;">
                            <th>#</th>
                            <th>%</th>
                            <th style="text-align: center;">Production Employees</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($list_users)) {
                            $sno = 0;
                            $total_capacity = 0;
                            $total_usersx = count($list_users);
                            $row_1 = round($total_usersx / 3);
                            foreach ($list_users as $row) {
                                $sno++;
                                $total_capacity += $row['default_dashboard2'] * 160 / 100;
                                if ($row['gender'] == 1) {
                                    echo '<tr style="background-color: #d66be431;">';
                                } else {
                                    echo '<tr>';
                                }
                                echo '<td>' . $sno . '</td>';
                                echo '<td style="text-align: right;">' . $row['default_dashboard2'] . '%</td>';
                                echo '<td>' . $row['name'] . ' ' . $row['last_name'];
                                if ($row['engage_type'] != 1) {
                                    echo ' <span class="badge bg-warning text-dark">CO</span>';
                                }
                                if ($row['default_dashboard1'] > 0) {

                                    switch ($row['default_dashboard1']) {
                                        case 2:
                                            echo ' <span class="badge bg-info text-dark">';
                                            echo 'DOCHEK';

                                            break;
                                        case 3:
                                            echo ' <span class="badge bg-success text-dark">';
                                            echo 'LIB';
                                            break;
                                        case 4:
                                            echo ' <span class="badge bg-danger text-dark">';
                                            echo 'COM';
                                            break;
                                    }
                                    echo '</span>';
                                }
                                echo '</td>';
                                echo '</tr>';
                                if ($sno ==  $row_1 || $sno ==  $row_1 * 2) {
                                    echo '</tbody>
           
                </table>
            </div>
        </div> <!-- end card-->
    </div> <!-- end col -->';
                                    echo '<div class="col-lg-6 col-xl-3">
        <div class="card ">
            <div class="card-body">
                <table class="table table-sm table-bordered  mb-0">
                    <thead>
                        <tr style="background-color: #6b5e5e42;">
                            <th>#</th>
                            <th>%</th>
                            <th style="text-align: center;">Production Employees</th>
                        </tr>
                    </thead>
                    <tbody>';
                                }
                            }
                        }
                        ?>
                    </tbody>

                </table>
            </div>
        </div> <!-- end card-->
    </div> <!-- end col -->
    <div class="col-lg-6 col-xl-3">
        <div class="card ">
            <div class="card-body">
                <table class="table table-sm table-bordered  mb-0">
                    <thead>
                        <tr style="background-color: #82c49242;">
                            <th>#</th>
                            <th style="text-align: center;">Support Employees</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($active_users)) {
                            $sno = 0;
                            foreach ($active_users as $row) {
                                if (in_array($row['id_user'], array_column($list_users, 'id_user'))) {
                                    continue; // Skip support users in this loop
                                }
                                $sno++;
                                if ($row['gender'] == 1) {
                                    echo '<tr style="background-color: #d66be431;">';
                                } else {
                                    echo '<tr>';
                                }
                                echo '<td>' . $sno . '</td>';
                                echo '<td>' . $row['name'] . ' ' . $row['last_name'];
                                if ($row['engage_type'] != 1) {
                                    echo ' <span class="badge bg-warning text-dark">CO</span>';
                                }
                                if ($row['default_dashboard1'] > 0) {


                                    switch ($row['default_dashboard1']) {
                                        case 2:
                                            echo ' <span class="badge bg-info text-dark">';
                                            echo 'DOCHEK';

                                            break;
                                        case 3:
                                            echo ' <span class="badge bg-success text-dark">';
                                            echo 'LIB';
                                            break;
                                        case 4:
                                            echo ' <span class="badge bg-danger text-dark">';
                                            echo 'COM';
                                            break;
                                    }
                                    echo '</span>';
                                }
                                echo '</td>';
                                echo '</tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div> <!-- end card-->
    </div>
</div>
<div class="row">
    <div class="col-lg-4 col-xl-2">
        <div class="card bg-pattern">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="text-end">
                            <h3 class="text-dark my-1"><?php echo $total_users ?? '0'; ?></h3>
                            <p class="text-muted mb-0 text-truncate">Total Active Users</p>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end card-->
    </div> <!-- end col -->

    <div class="col-lg-4 col-xl-2">
        <div class="card bg-pattern">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="text-end">
                            <h3 class="text-dark my-1"><?php echo $total_male ?? '0'; ?>/<?php $female = ($total_users ?? 0) - ($total_male ?? 0);
                                                                                            echo $female;
                                                                                            $ratio = $female / ($total_users ?? 1) * 100;
                                                                                            echo '<span style="font-size:10px"> (<i>' . round($ratio) . '%</i>)</span>'; ?></h3>
                            <p class="text-muted mb-0 text-truncate">Male/Female</p>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end card-->
    </div> <!-- end col -->
    <div class="col-lg-4 col-xl-2">
        <div class="card bg-pattern">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="text-end">
                            <h3 class="text-dark my-1"><?php echo $permanent_users ?? '0'; ?>/<?php $contract = ($total_users ?? 0) - ($permanent_users ?? 0);
                                                                                                echo $contract;
                                                                                                $ratio = $contract / ($total_users ?? 1) * 100;
                                                                                                echo '<span style="font-size:10px"> (<i>' . round($ratio) . '%</i>)</span>'; ?></h3>
                            <p class="text-muted mb-0 text-truncate">Permanent/Contract</p>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end card-->
    </div> <!-- end col -->
    <div class="col-lg-4 col-xl-2">
        <div class="card bg-pattern">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="text-end">
                            <h3 class="text-dark my-1"><?php echo $tasks_users ?? '0'; ?>/<?php $support = ($total_users ?? 0) - ($tasks_users ?? 0);
                                                                                            echo $support;
                                                                                            $ratio = $support / ($total_users ?? 1) * 100;
                                                                                            echo '<span style="font-size:10px"> (<i>' . round($ratio) . '%</i>)</span>'; ?></h3>
                            <p class="text-muted mb-0 text-truncate">Production/Support</p>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end card-->
    </div> <!-- end col -->
    <div class="col-lg-4 col-xl-2">
        <div class="card bg-pattern">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="text-end">
                            <h3 class="text-dark my-1"><?php
                                                        $production_capacity = $total_capacity;
                                                        echo number_format($production_capacity, 0, '.', ',') . ' Hrs';
                                                        ?>
                            </h3>
                            <p class="text-muted mb-0 text-truncate">Production Capacity</p>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end card-->
    </div> <!-- end col -->
    <div class="col-lg-4 col-xl-2">
        <div class="card bg-pattern">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="text-end">
                            <h3 class="text-dark my-1">

                                <?php
                                $revneu_capacity = $production_capacity * 23 * .6;
                                echo '$ ' . number_format($revneu_capacity, 0, '.', ',');
                                ?></h3>
                            <p class="text-muted mb-0 text-truncate">Capacity
                                <span style="font-size:10px"> ($ 23/Hr * 60% Uti)</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end card-->
    </div> <!-- end col -->
</div>
<?php
$exchange_rate = 94;

$wip_revenue = array(
    0 => array('month' => 1, 'total_amount' => 26049),
    1 => array('month' => 2, 'total_amount' => 23997),
    2 => array('month' => 3, 'total_amount' => 55456),
    3 => array('month' => 4, 'total_amount' => 70915),
    4 => array('month' => 5, 'total_amount' => 63723),
    5 => array('month' => 6, 'total_amount' => 73149),
    6 => array('month' => 7, 'total_amount' => 0),
    7 => array('month' => 8, 'total_amount' => 0),
    8 => array('month' => 9, 'total_amount' => 0),
    9 => array('month' => 10, 'total_amount' => 0),
    10 => array('month' => 11, 'total_amount' => 0),
    11 => array('month' => 12, 'total_amount' => 0)
);
$library_revenue = array(32851, 37894, 34078, 35708, 45520, 36848, 42020, 43950, 35463, 27397, 37209, 32339);
//$bespok_revenue = array(10768, 40414, 36928, 40544, 107252, 76733, 112448, 93846, 40417, 27267, 29567, 842);
$revenue_array = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

$cost_array = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

$operation_cost = $operation_cost ?? array();
for ($i = 0; $i < 12; $i++) {
    if (array_search($i + 1, array_column($operation_cost, 'month')) !== false) {
        $cost_array[$i] += round($operation_cost[array_search($i + 1, array_column($operation_cost, 'month'))]['usd_cost']);
    }
}
$sales_data_value = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
$sales_data_array = $sales_data ?? array();
for ($i = 0; $i < 12; $i++) {
    if (array_search($i + 1, array_column($sales_data_array, 'month')) !== false) {
        $sales_data_value[$i] += round($sales_data_array[array_search($i + 1, array_column($sales_data_array, 'month'))]['total_amount']);
    }
}

$bespoke_revenue_val = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
$bespoke_revenue_data = $bespoke_revenue ?? array();
for ($i = 0; $i < 12; $i++) {
    $key = array_search($i + 1, array_column($bespoke_revenue_data, 'month'));
    if ($key !== false) {
        $bespoke_revenue_val[$i] += round($bespoke_revenue_data[$key]['total_amount']);
    }
}

$total_cost_array = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
$total_cumulative = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
$this_month = date('m');
?>
<div class="row">
    <div class="col-lg-12 col-xl-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-sm table-bordered  mb-0">
                    <thead>
                        <tr style="background-color: #3d23d1a6; color: white;">
                            <th>Category</th>
                            <th style="text-align: center;">JAN</th>
                            <th style="text-align: center;">FEB</th>
                            <th style="text-align: center;">MAR</th>
                            <th style="text-align: center;">APR</th>
                            <th style="text-align: center;">MAY</th>
                            <th style="text-align: center;">JUN</th>
                            <th style="text-align: center;">JUL</th>
                            <th style="text-align: center;">AUG</th>
                            <th style="text-align: center;">SEP</th>
                            <th style="text-align: center;">OCT</th>
                            <th style="text-align: center;">NOV</th>
                            <th style="text-align: center;">DEC</th>
                            <th style="text-align: center;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Library Revenue</td>
                            <?php
                            $total_library_revenue = 0;
                            for ($i = 0; $i < 12; $i++) {
                                if (!isset($library_revenue[$i]) || $library_revenue[$i] === null || $library_revenue[$i] == 0) {
                                    echo '<td style="text-align:right;"></td>';
                                } else {
                                    $total_library_revenue += $library_revenue[$i];
                                    $revenue_array[$i] += $library_revenue[$i];
                                    if ($i >= $this_month - 1) {
                                        echo '<td style="text-align:right; background-color:#6b5e5e2c !important;">$ ' . number_format($library_revenue[$i], 0, '.', ',') . '</td>';
                                    } else {
                                        echo '<td style="text-align:right;">$ ' . number_format($library_revenue[$i], 0, '.', ',') . '</td>';
                                    }
                                }
                            }

                            ?>
                            <td style="text-align:right;">$ <?php echo number_format($total_library_revenue, 0, '.', ','); ?></td>
                        </tr>
                        <tr>
                            <td><a href="<?php echo base_url('Project_Manage/Invoices'); ?>">Bespok Revenue</a></td>
                            <?php
                            $total_bespok_revenue = 0;
                            for ($i = 0; $i < 12; $i++) {
                                if (!isset($bespoke_revenue_val[$i]) || $bespoke_revenue_val[$i] === null || $bespoke_revenue_val[$i] == 0) {
                                    echo '<td style="text-align:right;"></td>';
                                } else {
                                    $total_bespok_revenue += $bespoke_revenue_val[$i];
                                    $revenue_array[$i] += $bespoke_revenue_val[$i];
                                    if ($i >= $this_month - 1) {
                                        echo '<td style="text-align:right; background-color:#6b5e5e2c !important;">$ ' . number_format($bespoke_revenue_val[$i], 0, '.', ',') . '</td>';
                                    } else {
                                        echo '<td style="text-align:right;">$ ' . number_format($bespoke_revenue_val[$i], 0, '.', ',') . '</td>';
                                    }
                                }
                            }
                            ?>
                            <td style="text-align:right;">$ <?php echo number_format($total_bespok_revenue, 0, '.', ','); ?></td>
                        </tr>
                        <tr style="background-color: #f6d26e42; font-weight: bold;">
                            <td>WIP</td>
                            <?php for ($i = 0; $i < 12; $i++) {
                                if ($wip_revenue[$i]['total_amount'] == 0) {
                                    echo '<td></td>';
                                } else {
                                    if ($wip_revenue[$i]['total_amount'] < $total_cumulative[$i]) {
                                        echo '<td style="text-align:right;"><strong>$ ' . number_format($wip_revenue[$i]['total_amount'], 0, '.', ',') . '</strong></td>';
                                    } else {
                                        echo '<td style="text-align:right;"><strong>$ ' . number_format($wip_revenue[$i]['total_amount'], 0, '.', ',') . '</strong></td>';
                                    }
                                }
                            } ?>
                            <td></td>
                        </tr>
                        
                        <tr style="background-color: #56c67442; font-weight: bold;">
                            <td>Total Revenue</td>
                            <?php
                            $total_revenue = 0;
                            for ($i = 0; $i < 12; $i++) {
                                if (!isset($revenue_array[$i]) || $revenue_array[$i] === null || $revenue_array[$i] == 0) {
                                    echo '<td style="text-align:right;"></td>';
                                } else {
                                    $total_revenue += $revenue_array[$i];
                                    if ($i >= $this_month - 1) {
                                        echo '<td style="text-align:right; font-weight:bold; background-color:#6b5e5e2c !important;">$ ' . number_format($revenue_array[$i], 0, '.', ',') . '</td>';
                                    } else {
                                        echo '<td style="text-align:right; font-weight:bold;">$ ' . number_format($revenue_array[$i], 0, '.', ',') . '</td>';
                                    }
                                }
                            }
                            ?>
                            <td style="text-align:right; font-weight:bold; background-color:#92f5a942;">$ <?php echo number_format($total_revenue, 0, '.', ','); ?></td>
                        </tr>

                        <tr>
                            <td>Salary Cost</td>
                            <?php $counter = 12;
                            $total_salary = 0;
                            if (!empty($salary_users)) {
                                foreach ($salary_users as $row) {
                                    if (!isset($row['total_salary']) || $row['total_salary'] === null || $row['total_salary'] == 0) {
                                        echo '<td style="text-align:right;"></td>';
                                    } else {
                                        $total_salary += round($row['total_salary'] / $exchange_rate);
                                        $total_cost_array[$row['pay_month'] - 1] += round($row['total_salary'] / $exchange_rate);
                                        if ($row['pay_month'] >= $this_month) {
                                            echo '<td style="text-align:right; background-color:#6b5e5e2c !important;">$ ' . number_format(round($row['total_salary'] / $exchange_rate), 0, '.', ',') . '</td>';
                                        } else {
                                            echo '<td style="text-align:right;">$ ' . number_format(round($row['total_salary'] / $exchange_rate), 0, '.', ',') . '</td>';
                                        }
                                    }
                                    $counter--;
                                }
                            }
                            for ($i = 0; $i < $counter; $i++) {
                                echo '<td style="text-align:right;"></td>';
                            }
                            ?>
                            <td style="text-align:right;">$ <?php echo number_format($total_salary, 0, '.', ','); ?></td>
                        </tr>

                        <tr>
                            <td>Operational Cost</td>
                            <?php
                            $total_cost = 0;
                            for ($i = 0; $i < 12; $i++) {

                                if (!isset($cost_array[$i]) || $cost_array[$i] === null || $cost_array[$i] == 0) {
                                    echo '<td style="text-align:right;"></td>';
                                } else {
                                    $total_cost += $cost_array[$i];
                                    $total_cost_array[$i] += $cost_array[$i];

                                    if ($i >= $this_month - 1) {
                                        echo '<td style="text-align:right; background-color:#6b5e5e2c !important;">$ ' . number_format($cost_array[$i], 0, '.', ',') . '</td>';
                                    } else {
                                        echo '<td style="text-align:right;">$ ' . number_format($cost_array[$i], 0, '.', ',') . '</td>';
                                    }
                                }
                            }
                            ?>
                            <td style="text-align:right;">$ <?php echo number_format($total_cost, 0, '.', ','); ?></td>
                        </tr>

                        <tr style="background-color: #c66a5642; font-weight: bold;">
                            <td>Total Cost</td>
                            <?php
                            $total_cost_all = $total_salary + $total_cost;
                            for ($i = 0; $i < 12; $i++) {
                                if (!isset($total_cost_array[$i]) || $total_cost_array[$i] === null || $total_cost_array[$i] == 0) {
                                    echo '<td style="text-align:right;"></td>';
                                } else {
                                    if ($i >= $this_month - 1) {
                                        echo '<td style="text-align:right; font-weight:bold; background-color:#6b5e5e2c !important;">$ ' . number_format($total_cost_array[$i], 0, '.', ',') . '</td>';
                                    } else {
                                        echo '<td style="text-align:right; font-weight:bold;">$ ' . number_format($total_cost_array[$i], 0, '.', ',') . '</td>';
                                    }
                                }
                            }
                            ?>
                            <td style="text-align:right;">$ <?php echo number_format($total_cost_all, 0, '.', ','); ?></td>


                        </tr>


                    </tbody>
                    </tfoot>
                    <tr>
                        <td><strong>Delta</strong></td>
                        <?php
                        $counter = 0;
                        foreach ($total_cumulative as $value) {
                            if ($counter == 12) {
                                $value = $total_library_revenue + $total_bespok_revenue - $total_salary - $total_cost;
                                if ($value < 0) {
                                    echo '<td style="text-align:right; color: red;"><strong>$ ' . number_format($value, 0, '.', ',') . '</strong></td>';
                                } else {
                                    echo '<td style="text-align:right; color: green;"><strong>$ ' . number_format($value, 0, '.', ',') . '</strong></td>';
                                }
                            } else {
                                $total_cumulative[$counter] = $revenue_array[$counter] - $total_cost_array[$counter];
                                if ($total_cumulative[$counter] < 0) {
                                    echo '<td style="text-align:right; color: red;"><strong>$ ' . number_format($total_cumulative[$counter], 0, '.', ',') . '</strong></td>';
                                } else {
                                    echo '<td style="text-align:right; color: green;"><strong>$ ' . number_format($total_cumulative[$counter], 0, '.', ',') . '</strong></td>';
                                }
                            }
                            $counter++;
                        }

                        ?>

                    </tr>
                    <tr>
                        <td><strong>Cumulative</strong></td>
                        <?php
                        $cumulative_total = 0;
                        for ($i = 0; $i < 12; $i++) {
                            $cumulative_total += $total_cumulative[$i];
                            if ($cumulative_total < 0) {
                                echo '<td style="text-align:right; color: red;"><strong>$ ' . number_format($cumulative_total, 0, '.', ',') . '</strong></td>';
                            } else {
                                echo '<td style="text-align:right; color: green;"><strong>$ ' . number_format($cumulative_total, 0, '.', ',') . '</strong></td>';
                            }
                        }
                        ?>
                        <td></td>
                    </tr>

                    <tr>
                        <td><a href="<?php echo base_url('etrack/sales_admin'); ?>">Sales Pipeline</a></td>
                        <?php
                        $total_sales = 0;
                        if (!empty($sales_data_value)) {
                            for ($i = 0; $i < 12; $i++) {
                                if (!isset($sales_data_value[$i]) || $sales_data_value[$i] === null || $sales_data_value[$i] == 0) {
                                    echo '<td style="text-align:right;"></td>';
                                } else {
                                    $total_sales += $sales_data_value[$i];

                                    echo '<td style="text-align:right;">$ ' . number_format($sales_data_value[$i], 0, '.', ',') . '</td>';
                                }
                            }
                            echo '<td style="text-align:right;">$ ' . number_format($total_sales, 0, '.', ',') . '</td>';
                        }
                        ?>
                    </tr>
                </table>
            </div>
        </div> <!-- end card-->
    </div> <!-- end col -->
</div>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .chart-container {
        position: relative;
        max-width: 1200px;
        margin: 30px auto;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div> <!-- end card-->
    </div> <!-- end col -->
</div>

<script>
    // --- BAR CHART ---
    const barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar', // Defines chart style
        data: {
            labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'], // X-axis labels
            datasets: [{
                label: 'Monthly Revenue ($)',
                data: [<?php foreach ($revenue_array as $value) {
                            echo $value . ',';
                        } ?>], // Y-axis data points
                backgroundColor: 'rgba(54, 162, 235, 0.5)', // Fill color
                borderColor: 'rgba(54, 162, 235, 1)', // Border color
                borderWidth: 1
            }, {
                label: 'WIP ($)',
                data: [<?php foreach ($wip_revenue as $value) {
                            echo $value['total_amount'] . ',';
                        } ?>], // Y-axis data points
                backgroundColor: 'rgba(99, 255, 151, 0.5)', // Fill color
                borderColor: 'rgba(99, 255, 151, 1)', // Border color
                borderWidth: 1
            }, {
                label: 'Monthly Expenses ($)',
                data: [<?php foreach ($total_cost_array as $value) {
                            echo $value . ',';
                        } ?>], // Y-axis data points
                backgroundColor: 'rgba(255, 99, 132, 0.5)', // Fill color
                borderColor: 'rgba(255, 99, 132, 1)', // Border color
                borderWidth: 1
            }, {

                label: 'Delta ($)',
                data: [<?php foreach ($total_cumulative as $value) {
                            echo $value . ',';
                        } ?>], // Y-axis data 
                backgroundColor: 'rgba(8, 109, 8, 0.5)', // Fill color
                borderColor: 'rgb(104, 228, 125)', // Border color
                borderWidth: 1

            }, {
                type: 'line',
                label: 'Cumulative ($)',
                data: [<?php $base = 0;
                        foreach ($total_cumulative as $value) {
                            $base += $value;
                            echo $base . ',';
                        } ?>], // Y-axis data 
                borderColor: 'hsl(71, 100%, 46%)', // Line color
                borderWidth: 2,
                fill: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true // Forces Y-axis to start at 0
                }
            }
        }
    });
</script>