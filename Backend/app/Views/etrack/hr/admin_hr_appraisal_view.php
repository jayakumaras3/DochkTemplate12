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
                Appraisal Data - <?php echo $user_data[0]['name'] . ' ' . $user_data[0]['last_name']; ?>
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('etrack/HR_admin/add_new_appraisal'); ?>" method="POST"><?= csrf_field() ?>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-12 col-xl-12 col-form-label">Effective Date</label>
                            <div class="col-12 col-xl-12">
                                <input id="start_date" name="effectivedate" required class="date-picker form-control" placeholder="yyyy-mm-dd" type="text" onfocus="this.type='date'" onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)" value="">
                                <script>
                                    function timeFunctionLong(input) {
                                        setTimeout(function() {
                                            input.type = 'text';
                                        }, 60000);
                                    }
                                </script>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-12 col-xl-12 col-form-label">Description</label>
                            <div class="col-12 col-xl-12">
                                <input type="text" name="description" class="form-control" required value="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-6 col-xl-6 col-form-label">Designation</label>
                            <div class="col-12 col-xl-12">
                                <input type="text" name="designation" class="form-control" required value="">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-12 col-xl-12 col-form-label">New CTC</label>
                            <div class="col-12 col-xl-12">
                                <input type="number" name="yearly" class="form-control" required value="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-12 col-xl-12 col-form-label">Engagement Type</label>
                            <div class="col-12 col-xl-12">
                                <select name="type_of_engagement" class="form-control">
                                    <option value="1" selected>Permanent</option>
                                    <option value="2">Contract</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-12 col-xl-12 col-form-label">Template</label>
                            <div class="col-12 col-xl-12">
                                <select name="template" class="form-control">
                                    <option value="1" >TalentQuest</option>
                                    <option value="2" selected>Touchstone</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-12 col-xl-12 col-form-label">Type of Rev</label>
                            <div class="col-12 col-xl-12">
                                <select name="type_of_appraisal" class="form-control">
                                    <option value="1" selected>Salary Revision</option>
                                    <option value="2">Designation and Salary</option>
                                    <option value="3">Only Designation</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="justify-content-end row">
                        <div class="col-12 col-xl-12">
                            <input type="hidden" name='temp_user' value="<?php echo $temp_user; ?>">
                            <button type="submit" onclick="this.form.submit(); this.disabled=true;" class="btn btn-outline-danger btn-xs waves-effect waves-light">
                                Add Appraisal
                            </button>
                        </div>
                    </div>
                </form>
            </div>
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
                            <th>Engag. Type</th>
                            <th>Rev. Type</th>
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
                                    <?php $type_of_engagement = $app['type_of_app'];
                                    if ($type_of_engagement == 1) {
                                        echo 'Salary';
                                    } elseif ($type_of_engagement == 2) {
                                        echo 'Salary and Des';
                                    } elseif ($type_of_engagement == 3) {
                                        echo 'Designation';
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
                                        echo 'Rejected';
                                    }
                                    ?></td>
                                <td>
                                    <?php if ($iagree == 0) {  ?>
                                        <form class="form-horizontal" action="<?php echo base_url('etrack/HR_admin/edit_appraisal'); ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="return_page" value="2">
                                            <input type="hidden" name="salid" value="<?php echo $app['salid']; ?>">
                                            <button class="btn btn-outline-warning waves-effect btn-xs waves-light"><span class="mdi mdi-pencil-outline"></span></button>
                                        </form>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($app['salid'] > 2048) {
                                        if ($iagree != 2) { ?>
                                            <?php if ($type_of_engagement == 1) { ?>
                                                <form class="form-horizontal" action="<?php echo base_url('etrack/employee_details/view_letter'); ?>" method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="return_page" value="2">
                                                    <input type="hidden" name="salid" value="<?php echo $app['salid']; ?>">
                                                    <input type="hidden" name="temp_user" value="<?php echo $temp_user; ?>">
                                                    <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-file-table-box-outline"></span></button>
                                                </form>
                                            <?php } ?>
                                            <?php if ($type_of_engagement == 3) { ?>
                                                <form class="form-horizontal" action="<?php echo base_url('etrack/employee_details/view_des_letter'); ?>" method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="return_page" value="2">
                                                    <input type="hidden" name="salid" value="<?php echo $app['salid']; ?>">
                                                    <input type="hidden" name="temp_user" value="<?php echo $temp_user; ?>">
                                                    <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-file-table-box-outline"></span></button>
                                                </form>
                                            <?php } ?>
                                    <?php }
                                    } ?>
                                </td>
                                <td>
                                    <?php if ($app['salid'] > 2048) {
                                        if ($iagree != 2) {
                                            if ($type_of_engagement != 3) {
                                    ?>
                                                <form class="form-horizontal" action="<?php echo base_url('etrack/employee_details/view_breakup'); ?>" method="POST"><?= csrf_field() ?>
                                                    <input type="hidden" name="return_page" value="2">
                                                    <input type="hidden" name="salid" value="<?php echo $app['salid']; ?>">
                                                    <input type="hidden" name="temp_user" value="<?php echo $temp_user; ?>">
                                                    <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-microsoft-excel"></span></button>
                                                </form>
                                    <?php }
                                        }
                                    } ?>
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