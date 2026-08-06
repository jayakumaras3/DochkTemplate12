<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/PM_pricing_sheet'); ?>">Effort Sheet</a></li>

                </ol>
            </div>
            <h4 class="page-title">Edit Effort Sheet</h4>
        </div>
    </div>
</div>

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
$status = isset($get_pricing_sheet_data[0]['status']) ? $get_pricing_sheet_data[0]['status'] : 0;
?>
<?php
if ($check_purchase_orders) {
?>
    <div class="row">
        <div class="col-lg-12">
            <h5>Linked Purchase Orders</h5>
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <td>#</td>
                                <td>Name</td>
                                <td>PO Value</td>
                                <td>PO Number</td>
                                <td>Proj Value</td>
                                <td>Created By</td>
                                <td>View</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $j = 0;
                            foreach ($check_purchase_orders as $po) {
                                $j++;
                                echo '<tr>';
                                echo '<td>' . $j . '</td>';
                                echo '<td>' . $po['description'] . '</td>';
                                echo '<td> $ ' . $po['po_value'] . '</td>';
                                echo '<td>' . $po['po_number'] . '</td>';
                                echo '<td> $ ' . $po['project_value'] . '</td>';
                                echo '<td>' . $po['name'] . '</td>';
                                echo '<td>';
                            ?>
                                <form action="<?php echo base_url('Project_Manage/PM_purchase_order/edit_purchase_order') ?>" method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="po_id" value="<?php echo $po['po_id']; ?>">
                                    <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                        <span class="mdi mdi-eye-outline"></span></button>
                                </form>
                            <?php
                                echo '</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($status < 5) { ?>
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_pricing_sheet/add_cost') ?>" method="POST"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-1">
                                    <label for="projectname" class="form-label">Currency <span class="text-danger">*</span></label>
                                    <select name="currency" class="form-control">
                                        <option value="1" SELECTED>US Dollars</option><!-- 
                                    <option value="2">Indian Rupees</option>
                                    <option value="3">Euro</option> -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-1">
                                    <label for="cost" class="form-label">Cost <span class="text-danger">*</span></label>
                                    <input required type="number" class="form-control col-md-12" name="cost" placeholder="" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-1">
                                    <label for="projectname" class="form-label">Remarks</label>
                                    <input type="text" class="form-control col-md-12" name="remarks" placeholder="Remarks" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-1">
                            <input type="hidden" name="type" value="2" />
                            <input type="hidden" name="type_resource" value="" />
                            <input type="hidden" name="ppid" value="<?php echo $ppid; ?>" />
                            <button type="submit" class="btn btn-outline-info waves-effect btn-sm waves-light col-md-12">
                                Add Additional Cost
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_pricing_sheet/add_cost') ?>" method="POST"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-1">
                                    <label for="projectname" class="form-label">Skill Set <span class="text-danger">*</span></label>
                                    <select name="type_resource" class="form-control" required>
                                        <option value="">Select Skill</option>
                                        <?php
                                        foreach ($skills as $x => $y) {
                                            echo '<option value="' . $x . '">' . $y . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-1">
                                    <label for="clientname" class="form-label">Effort (hrs) <span class="text-danger">*</span></label>
                                    <input required type="number" class="form-control col-md-12" name="cost" placeholder="Effort (hrs)" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-1">
                                    <label for="clientname" class="form-label">Remarks</label>
                                    <input type="text" class="form-control col-md-12" name="remarks" placeholder="Remarks" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-1">
                            <input type="hidden" name="type" value="1" />
                            <input type="hidden" name="currency" value="0" />
                            <input type="hidden" name="ppid" value="<?php echo $ppid; ?>" />
                            <button type="submit" class="btn btn-outline-danger waves-effect btn-sm waves-light col-md-12">
                                Add Effort
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<div class="row">
    <?php if ($status < 5) { ?>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_pricing_sheet/update_pricing_sheet_submit') ?>" method="POST"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-1">
                                    <label for="projectname" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input required type="text" class="form-control col-md-12" name="pricing_name" placeholder="Short Name" value="<?php echo $get_pricing_sheet_data[0]['proposal_name'] ?>" />
                                </div>
                            </div>
                            <!-- <div class="col-lg-12">
                                <div class="mb-1">
                                    <label for="clientname" class="form-label">Client <span class="text-danger">*</span></label>
                                    <input required type="text" class="form-control col-md-12" name="client" placeholder="Client Name" value="<?php echo $get_pricing_sheet_data[0]['client'] ?>" />
                                </div>
                            </div> -->
                            <div class="col-lg-12">
                                <div class="mb-1">
                                    <label for="clientname" class="form-label">Client <span class="text-danger">*</span></label>

                                    <select name="client" class="form-control">
                                        <?php foreach ($getclients as $client) {
                                            if ($get_pricing_sheet_data[0]['client'] == $client['id_c']) {
                                                $selected = 'selected';
                                            } else {
                                                $selected = '';
                                            } ?>
                                            <option value="<?php echo $client['id_c'] ?>" <?php echo $selected; ?>><?php echo $client['client_name'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <!-- <input required type="text" class="form-control col-md-12" name="client" placeholder="Client Name" /> -->
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class=" mb-1">
                                    <label for="purchase_order_id" class="form-label">Account Manager <span class="text-danger">*</span></label>
                                    <select name="account_manager" class="form-control">
                                        <?php foreach ($salesuser as $sales) {
                                            if ($get_pricing_sheet_data[0]['requested_by'] == $sales['id_user']) {
                                                $selected = 'selected';
                                            } else {
                                                $selected = '';
                                            } ?>
                                            <option value="<?php echo $sales['id_user'] ?>" <?php echo  $selected ?>><?php echo $sales['fullname'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <!-- <div class="col-lg-12">
                                <div class=" mb-1">
                                    <label for="purchase_order_id" class="form-label">Currency <span class="text-danger">*</span></label>
                                    <select name="currency" class="form-control">
                                        <option SELECTED value="<?php echo $get_pricing_sheet_data[0]['currency']; ?>">
                                            <?php
                                            $currency = $get_pricing_sheet_data[0]['currency'];
                                            switch ($currency) {
                                                case 1:
                                                    echo 'US Dollars';
                                                    break;
                                                case 2:
                                                    echo 'Indian Rupees';
                                                    break;
                                                case 3:
                                                    echo 'Euro';
                                                    break;
                                            }
                                            ?>
                                        </option>
                                        <option value="1" value="<?php echo ($get_pricing_sheet_data[0]['currency'] == '1') ? 'selected' : '' ?>">US Dollars</option>
                                        <option value="2" <?php echo ($get_pricing_sheet_data[0]['currency'] == '2') ? 'selected' : '' ?>>Indian Rupees</option>
                                        <option value="3" <?php echo ($get_pricing_sheet_data[0]['currency'] == '3') ? 'selected' : '' ?>>Euro</option>
                                    </select>
                                </div>
                            </div> -->
                            <!-- <div class="col-lg-12">
                                <div class=" mb-1">
                                    <label for="purchase_order_id" class="form-label">Pricing Model <span class="text-danger">*</span></label>
                                    <select name="pricing_model" class="form-control">
                                        <?php
                                        $multi =  $get_pricing_sheet_data[0]['pricing_model'];
                                        // echo 'USD ' . $get_pricing_sheet_data[0]['pricing_model'];
                                        ?>
                                        <option value="30" <?php //echo ($get_pricing_sheet_data[0]['pricing_model'] == '30') ? 'selected' : '' ?>>USD 30</option>
                                        <option value="25" <?php //echo ($get_pricing_sheet_data[0]['pricing_model'] == '25') ? 'selected' : '' ?>>USD 25</option>
                                        <option value="20" <?php //echo ($get_pricing_sheet_data[0]['pricing_model'] == '20') ? 'selected' : '' ?>>USD 20</option>
                                        <option value="18" <?php //echo ($get_pricing_sheet_data[0]['pricing_model'] == '18') ? 'selected' : '' ?>>USD 18</option>
                                        <option value="15" <?php //echo ($get_pricing_sheet_data[0]['pricing_model'] == '15') ? 'selected' : '' ?>>USD 15</option>

                                    </select>
                                </div>
                            </div> -->
                            <!-- <div class="col-lg-12">
                                <div class=" mb-1">
                                    <label for="purchase_order_id" class="form-label">Margin <span class="text-danger">*</span></label>
                                    <select name="margin" class="form-control">
                                        <option value="30" <?php // echo ($get_pricing_sheet_data[0]['margin'] == '30') ? 'selected' : '' ?>>30 %</option>
                                        <option value="25" <?php //echo ($get_pricing_sheet_data[0]['margin'] == '25') ? 'selected' : '' ?>>25 %</option>
                                        <option value="20" <?php //echo ($get_pricing_sheet_data[0]['margin'] == '20') ? 'selected' : '' ?>>20 %</option>
                                        <option value="20" <?php //echo ($get_pricing_sheet_data[0]['margin'] == '18') ? 'selected' : '' ?>>18 %</option>
                                        <option value="20" <?php //echo ($get_pricing_sheet_data[0]['margin'] == '15') ? 'selected' : '' ?>>15 %</option>
                                    </select>
                                </div>
                            </div> -->
                            <div class="col-12">
                                <div class="mb-1">
                                    <label for="inputEmail3" class="col-form-label">Requirement</label>
                                    <textarea class="form-control" name="description"><?php echo $get_pricing_sheet_data[0]['description'] ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="text-sm-end  mt-sm-0">
                                    <input type="hidden" name="ppid" value="<?= $get_pricing_sheet_data[0]['ppid']; ?>">
                                    <input type="hidden" name="currency" value="1">
                                    <input type="hidden" name="pricing_model" value="25">
                                    <input type="hidden" name="margin" value="30">
                                    <button type="submit" class="btn btn-outline-danger waves-effect btn-sm waves-light">
                                        Update
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <table class="table mb-0">
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
                                    // print_r($get_pricing_sheet_data[0]['currency']);
                                    // exit();
                                    switch ($currency) {
                                        case 1:
                                            echo 'US Dollars';
                                            break;
                                        case 2:
                                            echo 'Indian Rupees';
                                            break;
                                        case 3:
                                            echo 'Euro';
                                            break;
                                    }
                                    ?></td>
                            </tr>
                            <tr>
                                <td>Pricing Model</td>
                                <td><?php
                                    $multi =  $get_pricing_sheet_data[0]['pricing_model'];
                                    echo 'USD ' . $get_pricing_sheet_data[0]['pricing_model'];
                                    ?></td>
                            </tr>
                            <tr>
                                <td>Margin</td>
                                <td><?php echo $get_pricing_sheet_data[0]['margin'] ?> %</td>
                            </tr>
                            <tr>
                                <td colspan="2">Requirement</td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php echo $get_pricing_sheet_data[0]['description'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="col-lg-8">
        <?php if ($get_pricing_sheet_details) { ?>
            <div class="card">
                <div class="card-body">
                    <table class="table mb-0 table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Skill</th>
                                <th>Remarks</th>
                                <th width="10%">Effort</th>
                                <th width="10%">Cost</th>
                                <th width="10%">Del</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                            $currency_sim = '';
                            $totalcost = 0;
                            $totaleffort = 0;
                            $additional = 0;
                            if ($get_pricing_sheet_details) {
                                foreach ($get_pricing_sheet_details as $data) {
                                    // if ($data['type'] == 2) {
                                    //     $additionalcost = $data['effort'];
                                    //      echo $additionalcost.'<br/>';
                                    //     // $add = $totalcost + $cost;
                                    //     $additional = $additional + $additionalcost;
                                    // }
                                    // echo  $additional;
                                    //  exit();
                                    $j = $j + 1 ?>
                                    <tr>
                                        <td><?php echo $j ?></td>
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
                                            // echo $currency_sim;
                                            if ($type == 1) {
                                                if ($data['type_resource'] > 50) {
                                                    $totalval = $effort * ($multi + 5);
                                                } else {
                                                    $totalval = $effort * $multi;
                                                }

                                                // echo $totalval;
                                                $totalcost = $totalcost + $totalval;
                                            } else if ($type == 2) {
                                                $cost = $data['effort'];
                                                echo $cost;
                                                $totalcost = $totalcost + $cost;
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php if ($status < 5) { ?>
                                                <form action="<?php echo base_url('Project_Manage/PM_pricing_sheet/delete_pricing_data') ?>" method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="bidc" value="<?php echo $data['bidc']; ?>">
                                                    <input type="hidden" name="ppid" value="<?php echo $ppid; ?>" />
                                                    <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')">
                                                        <span class="mdi mdi-trash-can-outline"></span></button>
                                                </form>
                                            <?php } ?>
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
                                <!-- <td align="right"><?php echo $currency_sim . ' ' . $totalalpha * $multi; ?></td> -->
                                <td></td>
                                <td></td>

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
                                <!-- <td align="right"><?php echo $currency_sim . ' ' . $totalbeta * $multi; ?></td> -->
                                <td></td>
                                <td></td>

                            </tr>
                            <tr>
                                <!-- <td colspan="3">Operation Cost</td> -->
                                <td colspan="3">Total</td>
                                <td align="right"><?php echo round($totaleffort); ?></td>
                                <!-- <td align="right"><?php echo $currency_sim . ' ' . $totalcost; ?></td> -->
                                <td align="right"><?php foreach ($get_pricing_sheet_details as $data) {
                                                        if ($data['type'] == 2) {
                                                            $additionalcost = $data['effort'];
                                                            $additional = $additional + $additionalcost;
                                                        }
                                                    }
                                                    echo  $additional . '<br/>';
                                                    ?>
                                </td>
                                <td></td>
                            </tr>
                            <!-- <tr>
                                <td colspan="4">Margin <?php echo $get_pricing_sheet_data[0]['margin']; ?> % </td>
                                <td align="right"><?php
                                                    echo $currency_sim . ' ' . round($get_pricing_sheet_data[0]['margin'] * $totalcost / 100);
                                                    $totalcost = $totalcost + round($get_pricing_sheet_data[0]['margin'] * $totalcost / 100); ?></td>
                                <td></td>
                            </tr> -->
                            <!-- <tr>
                                <td colspan="4">Selling Price</td>
                                <td align="right"><?php echo $currency_sim . ' ' . $totalcost; ?></td>
                                <td></td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>
            </div>
            <?php if ($status < 5) { ?>
                <div class="col-lg-12">

                    <form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_pricing_sheet/status_update_pricing_sheet') ?>" method="POST"><?= csrf_field() ?>
                        <input type="hidden" name="status" value="6" />
                        <input type="hidden" name="pricing_value" value="<?php echo $totalcost; ?>" />
                        <input type="hidden" name="ppid" value="<?php echo $ppid; ?>" />
                        <button type="submit" class="btn btn-outline-danger btn-xs rounded-pill waves-effect waves-light ">
                            Lock Effort Sheet
                        </button>
                    </form>

                </div>
            <?php } ?>
        <?php } else { ?>

            <!-- <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_pricing_sheet/add_pricing_from_template') ?>" method="POST"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-1">
                                    <label for="projectname" class="form-label">Skill Set <span class="text-danger">*</span></label>
                                    <select name="template" class="form-control">
                                        <option value="">Select Template</option>
                                        <?php
                                        foreach ($pricing_template as $x => $y) {
                                            echo '<option value="' . $x . '">' . $y . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-1">
                                    <label for="clientname" class="form-label">Duration (LH for Elearning, Minutes for Video) <span class="text-danger">*</span></label>
                                    <input required type="number" class="form-control col-md-12" name="cost" placeholder="Effort (hrs)" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-1">
                            <input type="hidden" name="type" value="1" />
                            <input type="hidden" name="currency" value="0" />
                            <input type="hidden" name="ppid" value="<?php echo $ppid; ?>" />
                            <button type="submit" class="btn btn-outline-primary waves-effect btn-sm waves-light">
                                Add Effort from Template
                            </button>
                        </div>
                    </form>
                </div>
            </div> -->

        <?php } ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo base_url('Project_Manage/PM_pricing_sheet/add_user_to_pricing_sheet') ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Select User <span class="text-danger">*</span></label>
                                <select name="assignuser" class="form-control" required>
                                    <option value="">Select User</option>
                                    <?php
                                    foreach ($project_manager as $data) {
                                        echo '<option value="' . $data['id_user'] . '">'  . $data['fname'] . ' ' . $data['lname'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6 mt-3">
                            <input type="hidden" name="ppid" value="<?php echo $ppid; ?>">
                            <input type="hidden" name="role" value="1">
                            <input type="hidden" name="returnid" value="2">
                            <input type="hidden" name="type_of_assignment" value="2">
                            <button type="submit" class="btn btn-outline-primary waves-effect btn-sm waves-light">
                                Assign Users to Effort Sheet</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                            foreach ($access as $data) {
                                $j = $j + 1 ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $data['fname'] . ' ' . $data['lname']; ?></td>
                                    <td>
                                        <form action="<?php echo base_url('Project_Manage/PM_pricing_sheet/delete_userassignment') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="returnid" value="2">
                                            <input type="hidden" name="project_assign_id" value="<?php echo $data['project_assign_id']; ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                <span class="mdi mdi-trash-can-outline"></span></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>