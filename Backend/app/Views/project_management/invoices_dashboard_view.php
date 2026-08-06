<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">My Invoices</h4>
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
                                <th>Client</th>
                                <th>Value</th>
                                <th>Invoice Dt</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Edit</th>
                                <th>PDF</th>
                            </tr>
                        </thead>
                        <tbody>

                        <tbody>
                            <?php $j = 0;
                            $invoice_list = isset($invoice_list) ? $invoice_list : [];
                            foreach ($invoice_list as $data) {
                                $j = $j + 1 ?>
                                <tr>
                                    <td>
                                        <?php echo $data['invoice_id']; ?>
                                    </td>
                                    <td><?php echo $data['client_name']; ?></td>
                                    <td><?php echo '$ ' . $data['value']; ?></td>
                                    <td><?php echo $data['inv_dt']; ?></td>
                                    <td><?php echo $data['due_dt']; ?></td>
                                    <td>
                                        <?php $status = $data['status'];
                                        switch ($status) {
                                            case 2:
                                                echo 'Invoiced';
                                                break;
                                            case 4:
                                                echo 'Received';
                                                break;
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <form action="<?php echo base_url('Project_Manage/MileStones/invoice_change_status') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="invoice_id" value="<?php echo $data['invoice_id']; ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                <span class="mdi mdi-square-edit-outline"></span></button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="<?php echo base_url('Project_Manage/MileStones/export_invoice_pdf') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="invoice_id" value="<?php echo $data['invoice_id']; ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                <span class="mdi mdi-file-pdf-box"></span></button>
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