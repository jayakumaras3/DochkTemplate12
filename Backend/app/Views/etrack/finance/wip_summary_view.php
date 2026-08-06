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
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/Fin_admin'); ?>">
                            Finance Dashboard
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">WIP Summary</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="card">
        <div class="card-body">
            <div class="col-12 col-md-12 col-lg-12">


                <div class="tab-content">
                    <div class="tab-pane show active" id="inprogress">
                        <div class="table-responsive">
                            <table class="table table-bordered w-100">
                                <thead>
                                    <tr>
                                        <th>UCN</th>
                                        <th>Name</th>
                                        <th width="10%">Client</th>
                                        <th>PM</th>
                                        <th>Status</th>
                                        <th>PO Value</th>
                                        <th>JAN</th>
                                        <th>FEB</th>
                                        <th>MAR</th>
                                        <th>APR</th>
                                        <th>MAY</th>
                                        <th>JUN</th>
                                        <th>JUL</th>
                                        <th>AUG</th>
                                        <th>SEP</th>
                                        <th>OCT</th>
                                        <th>NOV</th>
                                        <th>DEC</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($wip_list as $data) {
                                        $ucn =  $data['ucn_id'];
                                    ?>
                                        <tr>
                                            <td><?php echo $data['ucn_id']; ?></td>
                                            <td width="20%"><?php echo $data['name']; ?></td>
                                            <td width="10%"><?php echo $data['client_name']; ?></td>
                                            <td><?php echo $data['project_manager']; ?></td>
                                            <td><?php
                                                if ($data['status'] != '10') {
                                                    echo 'Active';
                                                } else {
                                                    echo 'Closed';
                                                }
                                                ?></td>
                                            <td class="right"><?php if($data['po_value']>0){
                                                echo '$ ';
                                                echo number_format($data['po_value']);
                                                } ?></td>
                                            <td class="right"><?php echo $data['month_1_percent']; ?></td>
                                            <td class="right"><?php echo $data['month_2_percent']; ?></td>
                                            <td class="right"><?php echo $data['month_3_percent']; ?></td>
                                            <td class="right"><?php echo $data['month_4_percent']; ?></td>
                                            <td class="right"><?php echo $data['month_5_percent']; ?></td>
                                            <td class="right"><?php echo $data['month_6_percent']; ?></td>
                                            <td class="right"><?php echo $data['month_7_percent']; ?></td>
                                            <td class="right"><?php echo $data['month_8_percent']; ?></td>
                                            <td class="right"><?php echo $data['month_9_percent']; ?></td>
                                            <td class="right"><?php echo $data['month_10_percent']; ?></td>
                                            <td class="right"><?php echo $data['month_11_percent']; ?></td>
                                            <td class="right"><?php echo $data['month_12_percent']; ?></td>

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