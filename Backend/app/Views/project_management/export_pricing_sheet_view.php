<?php

$skills = array();
foreach ($department_list as $data) {
    $skills[$data['value']] = $data['name'];
}
/* $skills = array(
    "52" => "Instructional Design",
    "2" => "Content Editor",
    "3" => "Graphic Design",
    "4" => "Visual Design",
    "5" => "Visualizer",
    "6" => "Post Production",
    "7" => "Articulate",
    "8" => "3D Modeling/Texturing",
    "9" => "General Programming",
    "10" => "Quality Assurance",
    "51" => "Unity3D Programming",
    "53" => "Project Manager",
    "54" => "SME"
); */


$pricing_template = array(
    "1" => "Level 1 Elearning",
    "2" => "Level 2 Elearning",
    "3" => "Level 2.5 Elearning",
    "4" => "1 Minute 2D Video",
    "5" => "1 Minute 3D Video"
);
$status = $get_pricing_sheet_data[0]['status'];
?>
<style>
    table {
        border: 1px solid black;
        border-collapse: collapse;
        width: 100%;
        font-family: Arial;
    }

    th,
    td {
        border: 1px solid black;
        padding: 5px;
        /* Optional: add some padding */
    }
</style>
<div style="font-size: 0.5em; text-align: right; margin-top: 20px;">
    <?php echo date('d-m-Y'); ?>
</div>
<table>
    <tbody>
        <tr>
            <td>Name</td>
            <td><?php echo $get_pricing_sheet_data[0]['proposal_name'] ?></td>
        </tr>
        <tr>
            <td>Client</td>
            <td><?php echo $get_pricing_sheet_data[0]['client_name'] ?></td>
        </tr>
        <tr>
            <td>Account Manager</td>
            <td><?php echo $get_pricing_sheet_data[0]['account_manager'] ?></td>
        </tr>
        <tr>
            <td>Currency</td>
            <td><?php
                $currency = $get_pricing_sheet_data[0]['currency'];
                switch ($currency) {
                    case 1:
                        echo 'US Dollars';
                        break;
                    case 2:
                        echo 'Indian Ruppees';
                        break;
                    case 3:
                        echo 'Euro';
                        break;
                }
                ?></td>
        </tr>
        <!-- <tr >
            <td>Pricing Model</td>
            <td ><?php
                    $multi =  $get_pricing_sheet_data[0]['pricing_model'];
                    echo 'USD ' . $get_pricing_sheet_data[0]['pricing_model'];
                    ?></td>
        </tr>
        <tr>
            <td>Margin</td>
            <td><?php echo $get_pricing_sheet_data[0]['margin'] ?> %</td>
        </tr> -->
        <tr>
            <td colspan="2">Requirement</td>
        </tr>
        <tr>
            <td colspan="2"><?php echo $get_pricing_sheet_data[0]['description'] ?></td>
        </tr>
    </tbody>
</table>

<br />

<div class="col-lg-8">
    <?php if ($get_pricing_sheet_details) { ?>
        <div class="card">
            <div class="card-body">
                <table class="table mb-0 table-bordered">
                    <thead>
                        <tr>
                            <th width="2%">#</th>
                            <th>Skill</th>
                            <th>Remarks</th>
                            <th width="10%">Effort</th>
                            <th width="10%">Cost</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        $currency_sim = '';
                        $totalcost = 0;
                        $totaleffort = 0;
                        if ($get_pricing_sheet_details) {
                            foreach ($get_pricing_sheet_details as $data) {
                                $j = $j + 1 ?>
                                <tr>
                                    <td width="5%"><?php echo $j ?></td>
                                    <td><?php
                                        $type = $data['type'];
                                        if ($type == 1) {

                                            echo $skills[$data['type_resource']];
                                        } else {
                                            echo "Additional";
                                        }
                                        ?></td>
                                    <td><?php echo $data['remarks'] ?></td>
                                    <td align="right"><?php
                                                        if ($type == 1) {
                                                            $effort = $data['effort'];
                                                            echo $effort;
                                                            $totaleffort = $totaleffort + $effort;
                                                        }
                                                        ?></td>

                                    <td align="right">
                                        <?php

                                        if ($type == 1) {
                                            $currency = $get_pricing_sheet_data[0]['currency'];
                                        } else if ($type == 2) {
                                            $currency = $data['currency'];
                                        }
                                        switch ($currency) {
                                            case 1:
                                                $currency_sim = '$ ';
                                                break;
                                            case 2:
                                                $currency_sim = 'INR ';
                                                break;
                                            case 3:
                                                $currency_sim = 'EURO ';
                                                break;
                                        }
                                        echo $currency_sim;
                                        if ($type == 1) {
                                            if ($data['type_resource'] > 50) {
                                                $totalval = $effort * ($multi + 5);
                                            } else {
                                                $totalval = $effort * $multi;
                                            }

                                            echo $totalval;
                                            $totalcost = $totalcost + $totalval;
                                        } else if ($type == 2) {
                                            $cost = $data['effort'];
                                            echo $cost;
                                            $totalcost = $totalcost + $cost;
                                        }
                                        ?>
                                    </td>

                                </tr>
                        <?php }
                        } ?>
                        <tr>

                            <td><?php $j = $j + 1;
                                echo $j; ?></td>
                            <td>Alpha Fixes 8%</td>
                            <td>System Generated</td>
                            <td align="right"><?php $totalalpha = round($totaleffort * .08, 1);
                                                echo round($totalalpha);
                                                $totaleffort = $totaleffort + $totalalpha;
                                                $totalcost = $totalcost + $totalalpha * $multi; ?></td>
                            <td align="right"><?php echo $currency_sim . ' ' . $totalalpha * $multi; ?></td>


                        </tr>
                        <tr>

                            <td><?php $j = $j + 1;
                                echo $j; ?></td>
                            <td>Beta Fixes 6%</td>
                            <td>System Generated</td>
                            <td align="right"><?php $totalbeta = round($totaleffort * .06, 1);
                                                echo round($totalbeta);
                                                $totaleffort = $totaleffort + $totalbeta;
                                                $totalcost = $totalcost + $totalbeta * $multi; ?></td>
                            <td align="right"><?php echo $currency_sim . ' ' . $totalbeta * $multi; ?></td>


                        </tr>
                        <tr>
                            <td colspan="3">Operation Cost</td>
                            <td align="right"><?php echo round($totaleffort); ?></td>
                            <td align="right"><?php echo $currency_sim . ' ' . $totalcost; ?></td>

                        </tr>
                        <tr>
                            <td colspan="4">Margin <?php echo $get_pricing_sheet_data[0]['margin']; ?> % </td>
                            <td align="right"><?php
                                                echo $currency_sim . ' ' . round($get_pricing_sheet_data[0]['margin'] * $totalcost / 100);
                                                $totalcost = $totalcost + round($get_pricing_sheet_data[0]['margin'] * $totalcost / 100); ?></td>

                        </tr>
                        <tr>
                            <td colspan="4">Selling Price</td>
                            <td align="right"><?php echo $currency_sim . ' ' . $totalcost; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    <?php } ?>