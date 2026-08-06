<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                   
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/MileStones'); ?>">Milestones</a></li>
               
                </ol>
            </div>
            <h4 class="page-title">Edit Milestone</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>%</th>
                                <th>Description</th>
                                <th>Value</th>
                                <th>Invoice Date</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Edit</th>
                                <th>Submit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $j = 0;

                            if (count($milestone_details) > 0) {

                                foreach ($milestone_details as $data) {
                            ?>
                                    <tr>
                                        <td><?php echo $data['invoice_id']; ?></td>
                                        <td><?php echo $data['percentage'] . ' %'; ?></td>
                                        <td><?php echo $data['description']; ?></td>
                                        <td><?php echo '$ ' . $data['value']; ?></td>
                                        <td><?php echo $data['inv_dt']; ?></td>
                                        <td><?php echo $data['due_dt']; ?></td>
                                        <td> <?php $status = $data['status'];
                                                switch ($status) {
                                                    case 1:
                                                        echo 'Editing';
                                                        break;
                                                    case 2:
                                                ?>
                                                    <form action="<?php echo base_url("Project_Manage/MileStones/submit_invoice") ?>" method="POST"><?= csrf_field() ?>
                                                        <input type="hidden" name="invoice_id" value="<?php echo $data['invoice_id']; ?>">
                                                        <input type="hidden" name="status" value="1">
                                                        <button type="submit" class="btn btn-outline-danger waves-effect btn-sm waves-light">Invoiced</button>
                                                    </form>
                                                <?php
                                                        break;
                                                    case 3:
                                                        echo 'On Hold';
                                                        break;
                                                    case 4:
                                                        echo 'Received';
                                                        break;
                                                }

                                                echo '</td>';
                                                if ($status == 1) { ?>
                                        <td>
                                            <form action="<?php echo base_url('Project_Manage/MileStones/edit_invoice') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="invoice_id" value="<?php echo $data['invoice_id']; ?>">
                                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                    <span class="mdi mdi-square-edit-outline"></span></button>
                                            </form>
                                        </td>
                                        <td>
                                            <form action="<?php echo base_url('Project_Manage/MileStones/submit_invoice') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="invoice_id" value="<?php echo $data['invoice_id']; ?>">
                                                <input type="hidden" name="status" value="2">
                                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                    <span class="mdi mdi-file-upload-outline"></span></button>
                                            </form>
                                        </td>
                                    <?php
                                                } else {;
                                                    echo '<td></td><td></td>';
                                                }
                                    ?>
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
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Project_Manage/MileStones/add_invoice') ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Percentage <span class="text-danger">*</span></label>
                                <input required type="text" class="form-control col-md-12" name="percentage" placeholder="Percentage" value="" />
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Currency <span class="text-danger">*</span></label>
                                <select name="currency" class="form-control">
                                    <option value="1">US Dollars</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Value <span class="text-danger">*</span></label>
                                <input required type="text" class="form-control col-md-12" name="value" placeholder="Value" value="" />
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input class="form-control" id="due_date" name="inv_dt" type="date">
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">End Date <span class="text-danger">*</span></label>
                                <input class="form-control" id="due_date" name="due_dt" type="date">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1">
                                <label for="inputEmail3" class="col-form-label">Description <span class="text-danger">*</span></label>
                                <div>
                                    <input class="form-control" name="description" type="hidden" />
                                    <textarea class="ckeditor" name="description" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <input type="hidden" name="milestone_id" value="<?php echo $milestone_id; ?>">
                            <div class="text-sm-end  mt-sm-0">
                                <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light">
                                    Add Invoice
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
</div>