<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">My Milestones</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>PO</th>
                                <th>UCN</th>
                                <th>Description</th>
                                <th>Value</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        <tbody>
                            <?php $j = 0;
                            foreach ($milestone_list as $data) {
                                $j = $j + 1 ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $data['po_id']; ?></td>
                                    <td><?php echo $data['ucn'] . '-' . $data['ucnname'] ?></td>
                                    <td><?php echo $data['description'] ?></td>
                                    <td><?php echo '$ ' . $data['value']; ?></td>
                                    <td><?php echo $data['invoicing_dt']; ?></td>
                                    <td>
                                        <?php $status = $data['status'];
                                        switch ($status) {
                                            case 1:
                                                echo 'Active';
                                                break;
                                            case 2:
                                                echo 'Delayed';
                                                break;
                                            case 4:
                                                echo 'Invoiced';
                                                break;
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <form action="<?php echo base_url('Project_Manage/MileStones/action') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="milestone_id" value="<?php echo $data['milestone_id']; ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                <span class="mdi mdi-square-edit-outline"></span></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>