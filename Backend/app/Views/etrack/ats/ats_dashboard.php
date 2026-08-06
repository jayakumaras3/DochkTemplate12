<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <form action="<?php echo base_url('etrack/ATS/new_ats_request'); ?>" method="POST"><?= csrf_field() ?>
                    <input type="hidden" name="cid" value="MQ==">
                    <button type="submit" class="btn btn-outline-primary waves-effect btn-sm waves-light mb-3"><i class="mdi mdi-plus"></i> Create New Request</button>
                </form>
            </div>
            <h4 class="page-title">
                ATS Dashboard
            </h4>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table id="searchdatatable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Role</th>
                            <th>Experience</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Requested On</th>
                            <th>Requested By</th>
                            <th>Fin Status</th>
                            <th>Mng Status</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($active_ats) > 0) { ?>
                            <?php
                            $j = 0;
                            $various_status = array('', 'Active', 'Sourcing', 'Interviewing', 'Offered', 'Accepted', 'Joined', 'Edit', 'Hold', '', 'Closed', 'Rejected');
                            foreach ($active_ats as $data) {
                                $j++;
                            ?>
                                <tr>
                                    <td><?php echo $data['ats_id']; ?></td>
                                    <td><?php echo $data['role']; ?></td>
                                    <td><?php echo $data['min_experience'].' - '. $data['max_experience']; ?></td>
                                    <td><?php switch($data['type_of_position']){
                                        case 1: echo 'Permanent'; break; 
                                        case 2: echo 'Contract'; break;
                                    } ?></td>
                                    <td><?php echo $various_status[$data['status']]; ?></td>
                                    <td><?php echo date("Y-m-d", $data['requested_on']); ?></td>
                                    <td><?php echo $data['requester']; ?></td>
                                    <td><?php switch ($data['fin_approve']) {
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
                                    <td><?php switch ($data['level2_approve']) {
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

                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url('etrack/ATS/view_details'); ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="ats_id" value="<?php echo $data['ats_id']; ?>">
                                            <button class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-eye-outline"></span></button>
                                        </form>
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