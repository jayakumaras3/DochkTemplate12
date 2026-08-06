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
                                    $po_value_array = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

                                    foreach ($wip_list as $data) {
                                        $prev_percent = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
                                        if ($data['month_0_percent'] > 99) {
                                            continue;
                                        }
                                        $ucn =  $data['ucn_id'];
                                        //Skipping content4u library UCN.
                                        if ($ucn == 896 || $ucn == 897) {
                                            continue;
                                        }
                                        //   if ($data['status'] != '10') {
                                        $userlevel = session()->get('userlevel');
                                        $arrayuserlevel = explode(',', $userlevel);

                                    ?>
                                        <?php if ($data['status'] == 10) {
                                            echo '<tr style="background-color:#e69ca869; border-color: white; ">';
                                        } else {
                                            echo '<tr>';
                                        }
                                        ?>

                                        <?php if (in_array('69', $arrayuserlevel)) { ?>
                                            <td>
                                                <form action="<?php echo base_url('Project_Manage/PM_ucn/edit_ucn') ?>" method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="ucn_id" value="<?php echo $data['ucn_id'] ?>">
                                                    <input type="hidden" name="client" value="<?php echo $data['client_name'] ?>">
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
                                            <?php if ($data['po_value'] > 0) {
                                                $po_value_array[0] = $po_value_array[0] + $data['po_value'];
                                                //  echo '$ ';
                                                echo number_format($data['po_value']);
                                            } ?>
                                        </td>
                                        <td>
                                            <?php if ($data['start_dt']) {
                                                echo date("M-y", strtotime($data['start_dt']));
                                            } else {
                                                echo date("M-y", $data['created_on']);
                                            } ?>
                                        </td>
                                        <td>
                                            <?php if ($data['status'] == 10) {
                                                if ($data['end_dt']) {
                                                    echo date("M-y", strtotime($data['end_dt']));
                                                } else {
                                                    echo date("M-y", $data['last_updated_on']);
                                                }
                                            } ?>
                                        </td>
                                        <td class="right">
                                            <?php
                                            if ($data['month_0_percent'] > 0) {
                                                echo '<span>';
                                                echo $data['month_0_percent'] . '%';
                                                echo '</span>';
                                            }
                                            if ($data['month_0_percent'] < 0) {
                                                echo '<span style="color:red;">';
                                                echo $data['month_0_percent'] . '%';
                                                echo '</span>';
                                            } else {
                                            }
                                            if ($data['month_0_percent'] > 0) {
                                                $prev_percent[0] = $data['month_0_percent'];
                                            } else {
                                                $prev_percent[0] = 0;
                                            }

                                            ?>
                                        </td>
                                        <td class="right">
                                            <?php
                                            if ($data['month_1_percent'] > 0) {
                                                echo '<span style="color:green;">';
                                                echo $data['month_1_percent'] . '%';
                                            } elseif ($data['month_1_percent'] < 0) {
                                                echo '<span style="color:red;">';
                                                echo $data['month_1_percent'] . '%';
                                            } else {
                                                echo '<span>';
                                            }

                                            echo '</span>';
                                            if ($data['month_1_percent'] > 0) {
                                                // if ($prev_percent[0] > 0) {
                                                echo '<br>';
                                                echo number_format($data['po_value'] * ($data['month_1_percent'] - $prev_percent[0]) / 100, 0);
                                                $po_value_array[1] = $po_value_array[1] + $data['po_value'] * ($data['month_1_percent'] - $prev_percent[0]) / 100;
                                                //echo ' - ' . $po_value_array[1];
                                                // }
                                                $prev_percent[1] = $data['month_1_percent'];
                                            } else {
                                                $prev_percent[1] = 0;
                                            }

                                            ?>
                                        </td>
                                        <td class="right">
                                            <?php
                                            if ($data['month_2_percent'] > 0) {
                                                echo '<span style="color:green;">';
                                                echo $data['month_2_percent'] . '%';
                                            } elseif ($data['month_2_percent'] < 0) {
                                                echo '<span style="color:red;">';
                                                echo $data['month_2_percent'] . '%';
                                            } else {
                                                echo '<span>';
                                            }
                                            echo '</span>';
                                            if ($data['month_2_percent'] > 0) {
                                                echo '<br>';
                                                echo number_format($data['po_value'] * ($data['month_2_percent'] - $prev_percent[1]) / 100, 0);

                                                //if ($prev_percent[1] > 0) {
                                                $po_value_array[2] = $po_value_array[2] + $data['po_value'] * ($data['month_2_percent'] - $prev_percent[1]) / 100;
                                                // echo ' - ' . $po_value_array[2];
                                                // }
                                                $prev_percent[2] = $data['month_2_percent'];
                                            } else {
                                                $prev_percent[2] = 0;
                                            }
                                            ?>
                                        </td>
                                        <td class="right">
                                            <?php
                                            if ($data['month_3_percent'] > 0) {
                                                echo '<span style="color:green;">';
                                                echo $data['month_3_percent'] . '%';
                                            } elseif ($data['month_3_percent'] < 0) {
                                                echo '<span style="color:red;">';
                                                echo $data['month_3_percent'] . '%';
                                            } else {
                                                echo '<span>';
                                            }
                                            echo '</span>';
                                            if ($data['month_3_percent'] > 0) {
                                                echo '<br>';
                                                echo number_format($data['po_value'] * ($data['month_3_percent'] - $prev_percent[2]) / 100, 0);
                                                // if ($prev_percent[2] > 0) {
                                                $po_value_array[3] = $po_value_array[3] + $data['po_value'] * ($data['month_3_percent'] - $prev_percent[2]) / 100;
                                                // }
                                                $prev_percent[3] = $data['month_3_percent'];
                                            } else {
                                                $prev_percent[3] = 0;
                                            }
                                            ?>
                                        </td>
                                        <td class="right">
                                            <?php
                                            if ($data['month_4_percent'] > 0) {
                                                echo '<span style="color:green;">';
                                                echo $data['month_4_percent'] . '%';
                                            } elseif ($data['month_4_percent'] < 0) {
                                                echo '<span style="color:red;">';
                                                echo $data['month_4_percent'] . '%';
                                            } else {
                                                echo '<span>';
                                            }
                                            echo '</span>';
                                            if ($data['month_4_percent'] > 0) {
                                                // if ($prev_percent[3] > 0) {
                                                echo '<br>';
                                                echo number_format($data['po_value'] * ($data['month_4_percent'] - $prev_percent[3]) / 100, 0);
                                                $po_value_array[4] = $po_value_array[4] + $data['po_value'] * ($data['month_4_percent'] - $prev_percent[3]) / 100;
                                                // }
                                                $prev_percent[4] = $data['month_4_percent'];
                                            } else {
                                                $prev_percent[4] = 0;
                                            }
                                            ?>
                                        </td>
                                        <td class="right">
                                            <?php
                                            if ($data['month_5_percent'] > 0) {
                                                echo '<span style="color:green;">';
                                                echo $data['month_5_percent'] . '%';
                                            } elseif ($data['month_5_percent'] < 0) {
                                                echo '<span style="color:red;">';
                                                echo $data['month_5_percent'] . '%';
                                            } else {
                                                echo '<span>';
                                            }
                                            echo '</span>';
                                            if ($data['month_5_percent'] > 0) {
                                                //if ($prev_percent[4] > 0) {
                                                echo '<br>';
                                                echo number_format($data['po_value'] * ($data['month_5_percent'] - $prev_percent[4]) / 100, 0);
                                                $po_value_array[5] = $po_value_array[5] + $data['po_value'] * ($data['month_5_percent'] - $prev_percent[4]) / 100;
                                                // }
                                                $prev_percent[5] = $data['month_5_percent'];
                                            } else {
                                                $prev_percent[5] = 0;
                                            }
                                            ?>
                                        </td>
                                        <td class="right">
                                            <?php
                                            if ($data['month_6_percent'] > 0) {
                                                echo '<span style="color:green;">';
                                                echo $data['month_6_percent'] . '%';
                                            } elseif ($data['month_6_percent'] < 0) {
                                                echo '<span style="color:red;">';
                                                echo $data['month_6_percent'] . '%';
                                            } else {
                                                echo '<span>';
                                            }
                                            echo '</span>';
                                            if ($data['month_6_percent'] > 0) {
                                                //  if ($prev_percent[5] > 0) {
                                                echo '<br>';
                                                echo number_format($data['po_value'] * ($data['month_6_percent'] - $prev_percent[5]) / 100, 0);
                                                $po_value_array[6] = $po_value_array[6] + $data['po_value'] * ($data['month_6_percent'] - $prev_percent[5]) / 100;
                                                //  }
                                                $prev_percent[6] = $data['month_6_percent'];
                                            } else {
                                                $prev_percent[6] = 0;
                                            }
                                            ?>
                                        </td>
                                        <td class="right">
                                            <?php
                                            if ($data['month_7_percent'] > 0) {
                                                echo '<span style="color:green;">';
                                                echo $data['month_7_percent'] . '%';
                                            } elseif ($data['month_7_percent'] < 0) {
                                                echo '<span style="color:red;">';
                                                echo $data['month_7_percent'] . '%';
                                            } else {
                                                echo '<span>';
                                            }
                                            echo '</span>';
                                            if ($data['month_7_percent'] > 0) {
                                                //  if ($prev_percent[6] > 0) {
                                                echo '<br>';
                                                echo number_format($data['po_value'] * ($data['month_7_percent'] - $prev_percent[6]) / 100, 0);
                                                $po_value_array[7] = $po_value_array[7] + $data['po_value'] * ($data['month_7_percent'] - $prev_percent[6]) / 100;
                                                //  }
                                                $prev_percent[7] = $data['month_7_percent'];
                                            } else {
                                                $prev_percent[7] = 0;
                                            }
                                            ?>
                                        </td>
                                        <td class="right">
                                            <?php
                                            if ($data['month_8_percent'] > 0) {
                                                echo '<span style="color:green;">';
                                                echo $data['month_8_percent'] . '%';
                                            } elseif ($data['month_8_percent'] < 0) {
                                                echo '<span style="color:red;">';
                                                echo $data['month_8_percent'] . '%';
                                            } else {
                                                echo '<span>';
                                            }
                                            echo '</span>';
                                            if ($data['month_8_percent'] > 0) {
                                                //  if ($prev_percent[7] > 0) {
                                                echo '<br>';
                                                echo number_format($data['po_value'] * ($data['month_8_percent'] - $prev_percent[7]) / 100, 0);
                                                $po_value_array[8] = $po_value_array[8] + $data['po_value'] * ($data['month_8_percent'] - $prev_percent[7]) / 100;
                                                //  }
                                                $prev_percent[8] = $data['month_8_percent'];
                                            } else {
                                                $prev_percent[8] = 0;
                                            }
                                            ?>
                                        </td>
                                        <td class="right">
                                            <?php
                                            if ($data['month_9_percent'] > 0) {
                                                echo '<span style="color:green;">';
                                                echo $data['month_9_percent'] . '%';
                                            } elseif ($data['month_9_percent'] < 0) {
                                                echo '<span style="color:red;">';
                                                echo $data['month_9_percent'] . '%';
                                            } else {
                                                echo '<span>';
                                            }
                                            echo '</span>';
                                            if ($data['month_9_percent'] > 0) {
                                                //  if ($prev_percent[8] > 0) {
                                                echo '<br>';
                                                echo number_format($data['po_value'] * ($data['month_9_percent'] - $prev_percent[8]) / 100, 0);
                                                $po_value_array[9] = $po_value_array[9] + $data['po_value'] * ($data['month_9_percent'] - $prev_percent[8]) / 100;
                                                //  }
                                                $prev_percent[9] = $data['month_9_percent'];
                                            } else {
                                                $prev_percent[9] = 0;
                                            }
                                            ?>
                                        </td>
                                        <td class="right">
                                            <?php
                                            if ($data['month_10_percent'] > 0) {
                                                echo '<span style="color:green;">';
                                                echo $data['month_10_percent'] . '%';
                                            } elseif ($data['month_10_percent'] < 0) {
                                                echo '<span style="color:red;">';
                                                echo $data['month_10_percent'] . '%';
                                            } else {
                                                echo '<span>';
                                            }
                                            echo '</span>';
                                            if ($data['month_10_percent'] > 0) {
                                                //  if ($prev_percent[9] > 0) {
                                                echo '<br>';
                                                echo number_format($data['po_value'] * ($data['month_10_percent'] - $prev_percent[9]) / 100, 0);
                                                $po_value_array[10] = $po_value_array[10] + $data['po_value'] * ($data['month_10_percent'] - $prev_percent[9]) / 100;
                                                //  }
                                                $prev_percent[10] = $data['month_10_percent'];
                                            } else {
                                                $prev_percent[10] = 0;
                                            }
                                            ?>
                                        </td>
                                        <td class="right">
                                            <?php
                                            if ($data['month_11_percent'] > 0) {
                                                echo '<span style="color:green;">';
                                                echo $data['month_11_percent'] . '%';
                                            } elseif ($data['month_11_percent'] < 0) {
                                                echo '<span style="color:red;">';
                                                echo $data['month_11_percent'] . '%';
                                            } else {
                                                echo '<span>';
                                            }
                                            echo '</span>';
                                            if ($data['month_11_percent'] > 0) {
                                                //  if ($prev_percent[10] > 0) {
                                                echo '<br>';
                                                echo number_format($data['po_value'] * ($data['month_11_percent'] - $prev_percent[10]) / 100, 0);
                                                $po_value_array[11] = $po_value_array[11] + $data['po_value'] * ($data['month_11_percent'] - $prev_percent[10]) / 100;
                                                //  }
                                                $prev_percent[11] = $data['month_11_percent'];
                                            } else {
                                                $prev_percent[11] = 0;
                                            }
                                            ?>
                                        </td>
                                        <td class="right">
                                            <?php
                                            if ($data['month_12_percent'] > 0) {
                                                echo '<span style="color:green;">';
                                                echo $data['month_12_percent'] . '%';
                                            } elseif ($data['month_12_percent'] < 0) {
                                                echo '<span style="color:red;">';
                                                echo $data['month_12_percent'] . '%';
                                            } else {
                                                echo '<span>';
                                            }
                                            echo '</span>';
                                            if ($data['month_12_percent'] > 0) {
                                                //  if ($prev_percent[11] > 0) {
                                                echo '<br>';
                                                echo number_format($data['po_value'] * ($data['month_12_percent'] - $prev_percent[11]) / 100, 0);
                                                $po_value_array[12] = $po_value_array[12] + $data['po_value'] * ($data['month_12_percent'] - $prev_percent[11]) / 100;
                                                //  }
                                                $prev_percent[12] = $data['month_12_percent'];
                                            } else {
                                                $prev_percent[12] = 0;
                                            }
                                            ?>
                                        </td>
                                        </tr>

                                        
                                      
                                    <?php
                                    }
                                    ?>
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
                                    <?php 
                                    //  }
                                    echo '<tr><td colspan="7"></td><td  class="right">';
                                    // echo number_format($po_value_array[0]);
                                    echo '</td><td  class="right">';
                                    echo number_format($po_value_array[1]);
                                    echo '</td><td  class="right">';
                                    echo number_format($po_value_array[2]);
                                    echo '</td><td  class="right">';
                                    echo number_format($po_value_array[3]);
                                    echo '</td><td  class="right">';
                                    echo number_format($po_value_array[4]);
                                    echo '</td><td  class="right">';
                                    echo number_format($po_value_array[5]);
                                    echo '</td><td  class="right">';
                                    echo number_format($po_value_array[6]);
                                    echo '</td><td  class="right">';
                                    echo number_format($po_value_array[7]);
                                    echo '</td><td  class="right">';
                                    echo number_format($po_value_array[8]);
                                    echo '</td><td  class="right">';
                                    echo number_format($po_value_array[9]);
                                    echo '</td><td  class="right">';
                                    echo number_format($po_value_array[10]);
                                    echo '</td><td  class="right">';
                                    echo number_format($po_value_array[11]);
                                    echo '</td><td  class="right">';
                                    echo number_format($po_value_array[12]);
                                    echo '</td></tr>';
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

        // If no value is selected, set the dropdown back to "Select Year"
        if (!yearSelect.value) {
            yearSelect.selectedIndex = 0; // This selects the first option (empty option)
        }
    });
</script>