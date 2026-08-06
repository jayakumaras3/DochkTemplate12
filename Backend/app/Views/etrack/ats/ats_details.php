<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/ATS'); ?>">
                            ATS Dashboard
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                ATS Details
            </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Type</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td>1</td>
                            <td>ATS ID</td>
                            <td><?php echo $ats_details[0]['ats_id']; ?></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Role</td>
                            <td><?php echo $ats_details[0]['role']; ?></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Experience</td>
                            <td><?php echo $ats_details[0]['min_experience'] . ' - ' . $ats_details[0]['max_experience']; ?></td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Type of Position</td>
                            <td><?php switch ($ats_details[0]['type_of_position']) {
                                    case 1:
                                        echo 'Permanent';
                                        break;
                                    case 2:
                                        echo 'Contract';
                                        break;
                                } ?></td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Requested On</td>
                            <td><?php if (strlen($ats_details[0]['requested_on']) > 2) {
                                    echo date("Y-m-d", $ats_details[0]['requested_on']);
                                } ?></td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>Assigned HR</td>
                            <td><?php echo $ats_details[0]['hr_assi']; ?></td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>Finance Approver</td>
                            <td><?php echo $ats_details[0]['fin_app']; ?></td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>Finance Approve Status</td>
                            <td><?php switch ($ats_details[0]['fin_approve']) {
                                    case 1:
                                        echo 'Approved';
                                        break;
                                    case 2:
                                        echo 'Rejected';
                                        break;
                                    default:
                                        echo 'In Process';
                                        break;
                                } ?></td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td>Finance Updated On</td>
                            <td><?php if (strlen($ats_details[0]['fin_approve_on']) > 2) {
                                    echo date("Y-m-d", $ats_details[0]['fin_approve_on']);
                                } ?>

                            </td>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td>Finance Remarks</td>
                            <td><?php echo $ats_details[0]['fin_remark']; ?></td>
                        </tr>

                        <tr>
                            <td>11</td>
                            <td>Management Approver</td>
                            <td><?php echo $ats_details[0]['level2app']; ?></td>
                        </tr>
                        <tr>
                            <td>12</td>
                            <td>Management Approve Status</td>
                            <td><?php switch ($ats_details[0]['level2_approve']) {
                                    case 1:
                                        echo 'Approved';
                                        break;
                                    case 2:
                                        echo 'Rejected';
                                        break;
                                    default:
                                        echo 'In Process';
                                        break;
                                } ?></td>
                        </tr>
                        <tr>
                            <td>13</td>
                            <td>Management Updated On</td>
                            <td><?php if (strlen($ats_details[0]['level2_approve_on']) > 2) {
                                    echo date("Y-m-d", $ats_details[0]['level2_approve_on']);
                                } ?>
                            </td>
                        </tr>
                        <tr>
                            <td>14</td>
                            <td>Management Remarks</td>
                            <td><?php echo $ats_details[0]['remark_level2']; ?></td>
                        </tr>
                        <tr>
                            <td colspan="3"><b>Requirement</b><br><?php echo $ats_details[0]['requirement_details']; ?></td>
                        </tr>
                        <tr>
                            <td colspan="3"><b>Job Description</b><br><?php echo $ats_details[0]['job_description']; ?></td>
                        </tr>
                    </tbody>
                </table>

                <?php
                if ($ats_details[0]['status'] == 7) {
                ?>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <form class="form-horizontal" action="<?php echo base_url('etrack/ATS/edit_ats'); ?>" method="POST"><?= csrf_field() ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="ats_id" value="<?php echo $ats_details[0]['ats_id']; ?>">
                                        <input type="submit" onclick="this.form.submit(); this.disabled=true;" class=" btn btn-outline-primary btn-xs waves-effect waves-light" value="Edit">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6 mb-2">
                            <form class="form-horizontal" action="<?php echo base_url('etrack/ATS/submit_for_processing'); ?>" method="POST"><?= csrf_field() ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="ats_id" value="<?php echo $ats_details[0]['ats_id']; ?>">
                                        <input type="submit" onclick="this.form.submit(); this.disabled=true;" class=" btn btn-outline-danger btn-xs waves-effect waves-light" value="Submit for Processing">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
        <?php
        if ($ats_details[0]['fin_approver'] == session()->get('id_user')) {
        ?>

            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('etrack/ATS/finance_approval'); ?>" method="POST"><?= csrf_field() ?>
                        <div class="row">
                            <h5>Level 1 Approval</h5>
                            <div class="col-md-12 mb-2">
                                <label class="form-label">Remarks</label>
                                <textarea class="form-control" name="fin_remark"></textarea>
                            </div>
                            <div class="col-md-12 mb-2">
                                <select class="form-select" name="fin_approve">
                                    <option value="1">Approve</option>
                                    <option value="2">Reject</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <input type="hidden" name="ats_id" value="<?php echo $ats_details[0]['ats_id']; ?>">
                                <input type="submit" onclick="this.form.submit(); this.disabled=true;" class=" btn btn-outline-warning btn-xs waves-effect waves-light" value="Update">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        <?php
        }
        ?>
        <?php
     ///   echo $ats_details[0]['level2_approver'];
        if ($ats_details[0]['level2_approver'] == session()->get('id_user')) {
        ?>

            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('etrack/ATS/level2_approval'); ?>" method="POST"><?= csrf_field() ?>
                        <div class="row">
                            <h5>Level 2 Approval</h5>
                            <div class="col-md-12 mb-2">
                                <label class="form-label">Remarks</label>
                                <textarea class="form-control" name="remark_level2"></textarea>
                            </div>
                            <div class="col-md-12 mb-2">
                                <select class="form-select" name="level2_approve">
                                    <option value="1">Approve</option>
                                    <option value="2">Reject</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <input type="hidden" name="ats_id" value="<?php echo $ats_details[0]['ats_id']; ?>">
                                <input type="submit" onclick="this.form.submit(); this.disabled=true;" class=" btn btn-outline-warning btn-xs waves-effect waves-light" value="Update">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        <?php
        }
        ?>


    </div>
    <div class="col-md-6">
        <?php
        if ($type_access == 2) { ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form class="form-horizontal" action="<?php echo base_url('etrack/ATS/add_fin_app'); ?>" method="POST"><?= csrf_field() ?>
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <select class="form-select" name="finance_approver">
                                            <option value="1138">Shrikant</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="hidden" name="ats_id" value="<?php echo $ats_details[0]['ats_id']; ?>">
                                        <input type="submit" onclick="this.form.submit(); this.disabled=true;" class=" btn btn-outline-info btn-xs waves-effect waves-light" value="Assign Level 1 Approver/Send eMail">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form class="form-horizontal" action="<?php echo base_url('etrack/ATS/add_level2_app'); ?>" method="POST"><?= csrf_field() ?>
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <select class="form-select" name="level2_approver">
                                            <option value="1135">Vinod</option>
                                            <option value="1">Pramod</option>
                                            <option value="1141">Frank</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="hidden" name="ats_id" value="<?php echo $ats_details[0]['ats_id']; ?>">
                                        <input type="submit" onclick="this.form.submit(); this.disabled=true;" class=" btn btn-outline-warning btn-xs waves-effect waves-light" value="Assign Level 2 Approver/Send eMail">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form class="form-horizontal" action="<?php echo base_url('etrack/ATS/assign_hr'); ?>" method="POST"><?= csrf_field() ?>
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <select class="form-select" name="assigned_hr">
                                            <option value="1115">Lakshmi</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="hidden" name="ats_id" value="<?php echo $ats_details[0]['ats_id']; ?>">
                                        <input type="submit" onclick="this.form.submit(); this.disabled=true;" class=" btn btn-outline-danger btn-xs waves-effect waves-light" value="Assign HR/Send eMail">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5>Update Comment</h5>
                        <form class="form-horizontal" action="<?php echo base_url('etrack/ATS/add_ats_history'); ?>" method="POST"><?= csrf_field() ?>
                            <div class="row">
                                <div class="col-md-12 mb-2">

                                    <textarea class="form-control" name="remarks"></textarea>
                                </div>
                                <?php
                                if ($type_access == 2) { ?>
                                    <div class="col-md-12 mb-2">
                                        <select class="form-select" name="new_status">
                                            <option value="1" <?php if ($ats_details[0]['status'] == 1) {
                                                                    echo "Selected";
                                                                }; ?>>Active</option>
                                            <option value="2" <?php if ($ats_details[0]['status'] == 2) {
                                                                    echo "Selected";
                                                                }; ?>>Sourcing</option>
                                            <option value="3" <?php if ($ats_details[0]['status'] == 3) {
                                                                    echo "Selected";
                                                                }; ?>>Interviewing</option>
                                            <option value="4" <?php if ($ats_details[0]['status'] == 4) {
                                                                    echo "Selected";
                                                                }; ?>>Offered</option>
                                            <option value="5" <?php if ($ats_details[0]['status'] == 5) {
                                                                    echo "Selected";
                                                                }; ?>>Accepted</option>
                                            <option value="6" <?php if ($ats_details[0]['status'] == 6) {
                                                                    echo "Selected";
                                                                }; ?>>Joined</option>
                                            <option value="7" <?php if ($ats_details[0]['status'] == 7) {
                                                                    echo "Selected";
                                                                }; ?>>Edit</option>
                                            <option value="8" <?php if ($ats_details[0]['status'] == 8) {
                                                                    echo "Selected";
                                                                }; ?>>Hold</option>
                                            <option value="10" <?php if ($ats_details[0]['status'] == 10) {
                                                                    echo "Selected";
                                                                }; ?>>Closed</option>

                                            <option value="11" <?php if ($ats_details[0]['status'] == 11) {
                                                                    echo "Selected";
                                                                }; ?>>Rejected</option>
                                        </select>
                                    </div>
                                <?php
                                } else {
                                ?>
                                    <input type="hidden" name="new_status" value="<?php echo $ats_details[0]['status']; ?>">
                                <?php
                                } ?>
                                <div class="col-md-12">
                                    <input type="hidden" name="current_status" value="<?php echo $ats_details[0]['status']; ?>">
                                    <input type="hidden" name="ats_id" value="<?php echo $ats_details[0]['ats_id']; ?>">
                                    <input type="submit" onclick="this.form.submit(); this.disabled=true;" class=" btn btn-outline-primary btn-xs waves-effect waves-light" value="Add Comment">
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
                            <tbody>
                                <?php if (count($ats_history) > 0) { ?>
                                    <?php
                                    $j = 0;
                                    foreach ($ats_history as $history) {
                                        $j++;
                                    ?>
                                        <tr>
                                            <td><?php echo $history['remarks']; ?><br>
                                                <span style="font-size:8px; font-style: italic;"><?php echo $history['requester']; ?> | <?php echo date("Y-m-d", $history['last_updated_on']); ?></span>
                                            </td>
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

    </div>