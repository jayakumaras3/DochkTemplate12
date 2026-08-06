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
            <h4 class="page-title">Status</h4>
        </div>
    </div>
</div>


<div class="row" style="font-size: 11px !important;">
    <div class="card">
        <div class="card-body">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="tab-content">
                    <div class="tab-pane show active" id="inprogress">
                        <div class="table-responsive" style="font-size: 14px !important; color: black !important;">
                            <table class="table table-bordered w-100">
                                <thead>
                                    <tr>
                                        <th style="width: 20px !important;
  margin: auto; color: white;
  background-color: #a0bae7 !important;">#</th>
                                        <th style="width: 60px !important;
  margin: auto; color: white;
  background-color: #a0bae7 !important;">UCN</th>
                                        <th style="width: 250px !important;
  margin: auto; color: white;
  background-color: #a0bae7 !important;">Details</th>


                                        <th style="width: 80px !important;  margin: auto;color: white;
  background-color: #a0bae7 !important;">Status</th>
                                        <th style="  margin: auto;color: white;
  background-color: #a0bae7 !important;">Remarks</th>
                                        <th style="  margin: auto;color: white;
  background-color: #a0bae7 !important;">Scope</th>
                                        <th style="width: 80px !important;
  margin: auto;color: white;
  background-color: #a0bae7 !important;">Updated</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php
                                    $sno = 0;
                                    if (!isset($wip_list) || !is_array($wip_list)) {
                                        $wip_list = [];
                                    }
                                    foreach ($wip_list as $data) {
                                        $ucn = $data['ucn_id'];
                                        if ($ucn == 896 || $ucn == 897) {
                                            continue;
                                        }
                                        $sno++;
                                    ?>

                                        <?php
                                        echo '<tr>';
                                        echo '<td scope="row">' . $sno . '</td>';
                                        ?>
                                        <td>
                                            <?php
                                            $userlevel = session()->get('userlevel');
                                            $arrayuserlevel = explode(',', $userlevel);
                                            $logged_user = trim(session()->get('name'));
                                            $project_managers = array_map('trim', explode(',', $data['project_manager']));
                                            if (
                                                !in_array('69', $arrayuserlevel) &&
                                                !in_array($logged_user, $project_managers)
                                            ) {
                                                echo  $data['ucn_id'];
                                            } else {
                                            ?>
                                                <form action="<?php echo base_url('Project_Manage/PM_ucn/edit_ucn_details') ?>" method="POST">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="id_ucn" value="<?php echo $data['ucn_id']; ?>">
                                                    <input type="hidden" name="return_page" value="2">
                                                    <input type="hidden" name="client" value="<?php echo $data['client_name']; ?>">
                                                    <button type="submit"
                                                        class="btn btn-outline-dark btn-xs waves-effect waves-light">
                                                        <?php echo $data['ucn_id']; ?>
                                                    </button>
                                                </form>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo $data['name']; ?>
                                            <br>Client : <?php echo $data['client_name']; ?>
                                            <br>Project Manager : <?php echo $data['project_manager']; ?>
                                            <br>Start Date : <?php
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
                                                echo '<span class="badge bg-soft-info text-info p-1">Completed</span>';
                                            } else if ($data['status'] == 4) {
                                                echo '<span class="badge bg-soft-warning text-warning p-1">On Hold</span>';
                                            } else if ($data['status'] == 3) {
                                                echo '<span class="badge bg-soft-danger text-danger p-1">Cancelled</span>';
                                            } else if ($data['status'] == 5) {
                                                echo '<span class="badge bg-soft-primary text-primary p-1">Delayed</span>';
                                            } else if ($data['status'] == 1) {
                                                echo '<span class="badge bg-soft-success text-success p-1">Active</span>';
                                            } else {
                                                echo '';
                                            }
                                            ?>

                                        </td>

                                        <td><?php echo $data['remarks']; ?></td>

                                        <td><?php echo $data['scope']; ?></td>
                                        <td>
                                            <?php
                                            echo date("M-d", $data['last_updated_on']);
                                            ?>
                                        </td>
                                        </tr>

                                    <?php } ?>

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