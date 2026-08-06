<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
               
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/PM_purchase_order'); ?>">Purchase Orders</a></li>
              
                </ol>
            </div>
            <h4 class="page-title">PO Details</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_purchase_order/add_milestones') ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">UCN <span class="text-danger">*</span></label>
                                <select name="ucn_id" class="form-control">
                                    <?php
                                    foreach ($ucn_details as $data) { ?>
                                        <option value="<?php echo $data['ucn_id'] ?>"><?php echo $data['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Milestone Name <span class="text-danger">*</span></label>
                                <input required type="text" class="form-control col-md-12" name="description" placeholder="Milestone Name" value="" />
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Currency <span class="text-danger">*</span></label>
                                <select name="currency" class="form-control">
                                    <option value="1" SELECTED>US Dollars</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Invoice Value <span class="text-danger">*</span></label>
                                <input required type="number" class="form-control col-md-12" name="milestone_value" placeholder="Invoice Value" value="" />
                            </div>
                        </div>


                        <div class="col-lg-12">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Invoice Date <span class="text-danger">*</span></label>
                                <input class="form-control" id="due_date" name="invoice_date" type="date">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1">
                                <label for="inputEmail3" class="col-form-label">Notes</label>
                                <div>
                                    <textarea name="notes" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <input type="hidden" name="po_id" value="<?php echo $po_id; ?>">
                            <div class="text-sm-end  mt-sm-0">
                                <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>UCN</th>
                                <th>Description</th>
                                <th>Value</th>
                                <th>Percent</th>
                                <th>Invoice Date</th>
                                <th>Notes</th>
                                <th>Del</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $total_po_value = 0;

                            if (count($milestone_details) > 0) {
                                $approved_po_value = $milestone_details[0]['po_val'];
                                echo '<tr><td></td><td>Approved PO Value</td><td align="right">$ ' . $approved_po_value . '<td colspan=4></td>';
                                foreach ($milestone_details as $data) {
                                    $current_po_value = $data['value'];
                                    $total_po_value = $total_po_value + $current_po_value;
                                    $percentage = round($current_po_value / $approved_po_value * 100);
                            ?>
                                    <tr>
                                        <td><?php echo $data['ucn_id'].'-'.$data['usnname']; ?></td>
                                        <td><?php echo $data['description']; ?></td>
                                        <td align="right"><?php echo '$ ' . $data['value']; ?></td>
                                        <td><?php echo $percentage . ' %'; ?></td>
                                        <td><?php echo $data['invoicing_dt']; ?></td>
                                        <td><?php echo $data['notes'] ?></td>
                                        <td>
                                            <form action="<?php echo base_url('Project_Manage/PM_purchase_order/del_milestone') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="milestone_id" value="<?php echo $data['milestone_id']; ?>">
                                                <input type="hidden" name="po_id" value="<?php echo $po_id; ?>">
                                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                    <span class="mdi mdi-trash-can-outline"></span></button>
                                            </form>
                                        </td>
                                    </tr>
                            <?php
                                }

                                $totalper = round($total_po_value / $approved_po_value * 100);
                                echo '<tr><td></td><td>Total</td><td align="right">$ ' . $total_po_value . '<td>' . $totalper . ' %</td><td colspan=3></td>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</div>