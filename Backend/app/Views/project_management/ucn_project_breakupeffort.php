
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/PM_ucn/project_breakdown'); ?>">Project Breakup</a></li>
                </ol>
            </div>
            <h4 class="page-title">Project Breakup Details</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Manager</th>
                            <th>Stage</th>
                            <th>Allocated</th>
                            <th>Mng Allocated</th>
                            <th>Actual Effort</th>
                            <th>Remarks</th>
                            <th>Status</th>
                            <th>Details</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        $alloctotal = 0;
                        $mang_all = 0;
                        $actu_total = 0;
                        foreach ($get_project_data as $data) {
                            $j++; ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo $data['name']; ?></td>
                                <td><?php $stage = $data['stage'];
                                    switch ($stage) {
                                        case 1:
                                            echo 'Alpha';
                                            break;
                                        case 2:
                                            echo 'Beta';
                                            break;
                                        case 5:
                                            echo 'Gamma';
                                            break;
                                        case 0:
                                            echo 'Gen';
                                            break;
                                    }
                                    ?></td>
                                <td><?php echo $data['effort'];
                                    $alloctotal =  $alloctotal + $data['effort'];
                                    ?></td>
                                <td><?php echo $data['teeff'];
                                    $mang_all =  $mang_all + $data['teeff']; ?></td>
                                <td><?php echo $data['eeeff'];
                                    $actu_total =  $actu_total + $data['eeeff']; ?></td>
                                <td width=15% title="<?= htmlspecialchars($data['remarks']) ?>">
                                    <?= strlen($data['remarks']) > 30 ? htmlspecialchars(substr($data['remarks'], 0, 30)) . '...' : htmlspecialchars($data['remarks']) ?>
                                </td>
                                <td><?php $status = $data['status'];
                                    switch ($status) {
                                        case 1:
                                            echo 'Active';
                                            break;
                                        case 2:
                                            echo 'In Active';
                                            break;
                                    }
                                    ?></td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_ucn/view_mst_detail') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="ucn_mst_id" value="<?php echo $data['ucn_mst_id']; ?>">
                                        <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-eye-outline"></span></button>
                                    </form>
                                </td>
                                <td>
                                    <?php if ($status == 1) { ?>
                                        <form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_ucn/close_mst_task') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="ucn_mst_id" value="<?php echo $data['ucn_mst_id']; ?>">
                                            <?php if (!empty($data['eeeff'])) { ?>
                                                <div class="col-md-2">
                                                    <input type="hidden" name="status" value="2">
                                                    <button type="submit" class="btn btn-outline-warning btn-sm" onclick="return confirm('<?php echo lang('Alert.Aler_009') ?>')">Close</button>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-md-2">
                                                    <input type="hidden" name="status" value="0">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('<?php echo lang('Alert.Aler_010') ?>')">Delete</button>
                                                </div>
                                            <?php } ?>
                                        </form>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="3">TOTAL</td>
                            <td><?php echo  $alloctotal; ?></td>
                            <td><?php echo  $mang_all; ?></td>
                            <td><?php echo  $actu_total; ?></td>
                            <td colspan="3"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>