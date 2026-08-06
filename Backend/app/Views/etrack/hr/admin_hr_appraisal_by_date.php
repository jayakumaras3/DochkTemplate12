<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/HR_admin/personal'); ?>">
                            Admin HR Personal Data
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                Appraisal Data - <?php echo $start_dt . ' ' . $end_date; ?>
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
                            <th>Employee</th>
                            <th>Effective Date</th>
                            <th>Description</th>
                            <th>Designation</th>

                            <th>Yearly</th>
                            <th>Type</th>
                            <th>Template</th>
                            <th>Status</th>
                            <th>Edit</th>
                            <th>Appraisal Letter</th>
                            <th>Breakup</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        $start_sal = 0;
                        foreach ($all_appraisal as $app) {
                            $j++;
                        ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo $app['name'] . ' ' . $app['last_name']; ?></td>
                                <td><?php echo $app['effectivedate']; ?></td>
                                <td><?php echo $app['description']; ?></td>
                                <td><?php echo $app['designation']; ?></td>

                                <td style="text-align: right;">INR <?php echo preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $app['yearly']);; ?></td>
                                <td>
                                    <?php $type_of_engagement = $app['type_of_engagement'];
                                    if ($type_of_engagement == 1) {
                                        echo 'Permanent';
                                    } elseif ($type_of_engagement == 2) {
                                        echo 'Contract';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php $template = $app['template'];
                                    if ($template == 1) {
                                        echo 'TQ';
                                    } elseif ($template == 2) {
                                        echo 'TLC';
                                    }
                                    ?>
                                </td>
                                <td><?php $iagree = $app['iagree'];
                                    if ($iagree == 1) {
                                        echo 'Accepted';
                                    } elseif ($iagree == 2) {
                                        echo '<span style="color:red">Discussion</span>';
                                    }
                                    ?></td>
                                <td>
                                    <?php if ($iagree == 0) {  ?>
                                        <form class="form-horizontal" action="<?php echo base_url('etrack/HR_admin/edit_appraisal'); ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="return_page" value="3">
                                            <input type="hidden" name="salid" value="<?php echo $app['salid']; ?>">
                                            <button class="btn btn-outline-warning waves-effect btn-xs waves-light"><span class="mdi mdi-pencil-outline"></span></button>
                                        </form>
                                    <?php }  ?>
                                </td>
                                <td>
                                    <?php if ($app['salid'] > 2048) { ?>
                                        <form class="form-horizontal" action="<?php echo base_url('etrack/employee_details/view_letter'); ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="return_page" value="3">
                                            <input type="hidden" name="salid" value="<?php echo $app['salid']; ?>">
                                            <input type="hidden" name="temp_user" value="<?php echo $app['id_user']; ?>">
                                            <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-file-table-box-outline"></span></button>
                                        </form>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($app['salid'] > 2048) { ?>
                                        <form class="form-horizontal" action="<?php echo base_url('etrack/employee_details/view_breakup'); ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="return_page" value="3">
                                            <input type="hidden" name="salid" value="<?php echo $app['salid']; ?>">
                                            <input type="hidden" name="temp_user" value="<?php echo $app['id_user']; ?>">
                                            <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-microsoft-excel"></span></button>
                                        </form>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php
                            $start_sal =  $app['yearly'];
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>