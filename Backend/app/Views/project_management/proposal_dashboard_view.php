<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('etrack/dashboard'); ?>">
                            Dashboard
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">My Proposals</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo base_url("Project_Manage/PM_Proposals/add_proposal") ?>" method="POST"><?= csrf_field() ?>
                    <input type="hidden" name="cid" value="MQ==">
                    <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light mb-3"><i class="mdi mdi-plus"></i> Create New Proposals</button>
                </form>
                <div class="table-responsive">
                    <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Client</th>
                                <th>Sales</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Edit</th>
                                <th>Details</th>
                                <th>Export</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                            foreach ($proposal_list as $data) {
                                $j = $j + 1 ?>
                                <tr>
                                    <td><?php echo $data['proposal_id'] ?></td>
                                    <td><?php echo $data['short_name'] ?></td>
                                    <td><?php echo $data['client_name']; ?></td>
                                    <td><?php echo $data['fullname']; ?></td>
                                    <td><?php echo date("Y-m-d", $data['last_updated_on']); ?></td>
                                    <td>
                                        <?php
                                        $userlevel = session()->get('userlevel');
                                        $arrayuserlevel = explode(',', $userlevel);
                                        $status = $data['status'];
                                        if (in_array('6', $arrayuserlevel) && $data['status'] == 6) {
                                        ?>
                                            <form action="<?php echo base_url('Project_Manage/PM_Proposals/updatelockstatus') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="status" value="1" />
                                                <input type="hidden" name="proposal_id" value="<?php echo $data['proposal_id']; ?>">
                                                <button class="btn btn-outline-primary waves-effect btn-xs waves-light" title="Unlock"><span class="mdi mdi-lock-open-minus"></span></button>
                                            </form>
                                        <?php
                                        } else {
                                        ?>
                                        <?php
                                            switch ($status) {
                                                case 1:
                                                    echo 'New';
                                                    break;
                                                case 2:
                                                    echo 'Editing';
                                                    break;
                                                case 4:
                                                    echo 'Mng Reviewed';
                                                    break;
                                                case 6:
                                                    echo 'Locked';
                                                    break;
                                                case 7:
                                                    echo 'Linked';
                                                    break;
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php if ($status < 5) { ?>
                                            <form action="<?php echo base_url('Project_Manage/PM_Proposals/proposal_edit') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="proposal_id" value="<?php echo $data['proposal_id']; ?>">
                                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                    <span class="mdi mdi-square-edit-outline"></span></button>
                                            </form>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if ($status < 5) { ?>
                                            <form action="<?php echo base_url('Project_Manage/PM_Proposals/proposal_details') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="proposal_id" value="<?php echo $data['proposal_id']; ?>">
                                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                    <span class="mdi mdi-magnify"></span></button>
                                            </form>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if ($status == 6) { ?>
                                            <form action="<?php echo base_url('Project_Manage/PM_Proposals/export_proposal_pdf') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="proposal_id" value="<?php echo $data['proposal_id']; ?>">
                                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                    <span class="mdi mdi-file-pdf-box"></span></button>
                                            </form>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>