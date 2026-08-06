<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">
                Appraisals
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
                            <th>Effective Date</th>
                            <th>Description</th>
                            <th>Designation</th>
                            <th>% Hike</th>
                            <th>Yearly</th>
                            <th>Status</th>
                            <th>Appraisal Letter</th>
                            <th>Breakup</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        $start_sal = 0;
                        foreach ($appraisals as $app) {
                            $j++;
                        ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo $app['effectivedate']; ?></td>
                                <td><?php echo $app['description']; ?></td>
                                <td><?php echo $app['designation']; ?></td>
                                <td style="text-align: right;"><?php
                                                                if ($start_sal > 0) {
                                                                    $diffsal = $app['yearly'] - $start_sal;
                                                                    if ($diffsal > 0) {
                                                                        $percentagehike = ($diffsal / $start_sal) * 100;
                                                                        echo round($percentagehike) . '%';
                                                                    }
                                                                }
                                                                ?></td>
                                <td style="text-align: right;">INR <?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $app['yearly']);; ?></td>
                                <td><?php $iagree = $app['iagree'];
                                    if ($iagree == 1) {
                                        echo 'Accepted';
                                    } elseif ($iagree == 2) {
                                        echo 'Contact BU Head';
                                    }
                                    ?></td>
                                <td>
                                    <?php if ($app['salid'] > 2048) {
                                        if ($app['type_of_app'] == 1) {
                                    ?>
                                            <form class="form-horizontal" action="<?php echo base_url('etrack/employee_details/view_letter'); ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="return_page" value="1">
                                                <input type="hidden" name="salid" value="<?php echo $app['salid']; ?>">
                                                <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi  mdi-file-table-box-outline"></span></button>
                                            </form>

                                        <?php } ?>
                                         <?php if ($app['type_of_app'] == 2) { ?>
                                            <form class="form-horizontal" action="<?php echo base_url('etrack/employee_details/view_mixed_letter'); ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="return_page" value="1">
                                                <input type="hidden" name="salid" value="<?php echo $app['salid']; ?>">
                                                <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi  mdi-file-table-box-outline"></span></button>
                                            </form>
                                        <?php } ?>
                                        <?php if ($app['type_of_app'] == 3) { ?>
                                            <form class="form-horizontal" action="<?php echo base_url('etrack/employee_details/view_des_letter'); ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="return_page" value="1">
                                                <input type="hidden" name="salid" value="<?php echo $app['salid']; ?>">
                                                <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi  mdi-file-table-box-outline"></span></button>
                                            </form>
                                        <?php } ?>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($app['salid'] > 2048) { 
                                        if ($app['type_of_app'] != 3) {
                                    ?>
                                            <form class="form-horizontal" action="<?php echo base_url('etrack/employee_details/view_breakup'); ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="return_page" value="1">
                                                <input type="hidden" name="salid" value="<?php echo $app['salid']; ?>">
                                                <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-microsoft-excel"></span></button>
                                            </form>
                                    <?php }
                                    } ?>
                                </td>
                            </tr>
                        <?php
                            $start_sal =  $app['yearly'];
                        }
                        ?>
                    </tbody>
                </table>
                <p class="text-danger">If there is any discripency in data please contact HR. The data is confidential please don't share with anyone.</p>
            </div>
        </div>
    </div>
</div>