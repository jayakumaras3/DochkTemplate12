<?php
$userlevel = session('userlevel');
$id_user = session('id_user');
$arrayuserlevel  = array_map('intval', explode(',', $userlevel) ?? '');
if (!in_array('4154', $arrayuserlevel)) {
    header('Location:' . base_url('my_training'));
    exit();
}
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/ITSupport/assets'); ?>">
                            IT Assets
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                User Assets
            </h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Type of Asset</th>
                            <th>Description</th>
                            <th>Identifier</th>
                            <th>Assigned On</th>
                            <th>Returned On</th>
                            <th>Expected Return</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($get_user_assets) {
                       
                            $j = 0;
                            foreach ($get_user_assets as $assets) {
                                $j++;
                        ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $assets['asset_type']; ?></td>
                                    <td><?php echo $assets['desc']; ?></td>
                                    <td><?php echo $assets['identifier']; ?></td>
                                    <td><?php echo $assets['assigned_on']; ?></td>
                                    <td><?php echo $assets['returned_on']; ?></td>
                                    <td><?php echo $assets['expected_return_on']; ?></td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Software</th>
                            <th>Assigned On</th>
                            <th>Expiry On</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($get_user_software) {
                       
                            $j = 0;
                            foreach ($get_user_software as $software) {
                                $j++;
                        ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $software['desc']; ?></td>
                                    <td><?php echo date('Y-m-d', $software['assigned_on']); ?></td>
                                    <td><?php echo $software['end_date']; ?></td>
                                    <td><?php echo $software['remarks']; ?></td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>