<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">My Purchase Orders</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Description</th>
                                <th>Client</th>
                                <th>PO Value</th>
                                <th>PO Status</th>
                                <th>Status</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                            foreach ($purchase_order_list as $data) {
                                $j = $j + 1 ?>
                                <tr>
                                    <td><?php echo $data['po_id'] ?></td>
                                    <td><?php echo $data['description'] ?></td>
                                    <td><?php echo $data['client_name'] ?></td>
                                    <td align="right">$ <?php echo $data['po_value'] ?></td>
                                    <td><?php
                                        $postatus = $data['po_status'];
                                        echo $postatus;
                                        ?></td>
                                    <td><?php
                                        $status = $data['status'];
                                        switch ($status) {
                                            case 1:
                                                echo 'Editing';
                                                break;
                                            case 6:
                                                echo 'Locked';
                                                break;
                                        }
                                        ?></td>
                                    <td>
                                        <form action="<?php echo base_url('Project_Manage/PM_purchase_order/edit_purchase_order') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="po_id" value="<?php echo $data['po_id']; ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                <span class="mdi mdi-square-edit-outline"></span></button>
                                        </form>
                                    </td>

                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>