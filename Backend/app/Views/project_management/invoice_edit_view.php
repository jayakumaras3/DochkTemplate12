<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/MileStones'); ?>">Milestones</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/MileStones/action'); ?>">Edit Milestones</a></li>
   
                </ol>
            </div>
            <h4 class="page-title">Edit Invoice</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Project_Manage/MileStones/update_invoice') ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Percentage <span class="text-danger">*</span></label>
                                <input required type="text" class="form-control col-md-12" name="percentage" placeholder="Percentage" value="<?php echo $invoice_details[0]['percentage']; ?>" />
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
                                <input required type="text" class="form-control col-md-12" name="value" placeholder="Value" value="<?php echo $invoice_details[0]['value']; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input class="form-control" id="due_date" name="inv_dt" type="date" value="<?php echo $invoice_details[0]['inv_dt']; ?>">
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">End Date <span class="text-danger">*</span></label>
                                <input class="form-control" id="due_date" name="due_dt" type="date" value="<?php echo $invoice_details[0]['due_dt']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1">
                                <label for="inputEmail3" class="col-form-label">Description <span class="text-danger">*</span></label>
                                <div>
                                    <input class="form-control" name="description" type="hidden" />
                                    <textarea class="ckeditor" name="description" required><?php echo $invoice_details[0]['description']; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <input type="hidden" name="invoice_id" value="<?php echo $invoice_id; ?>">
                            <div class="text-sm-end  mt-sm-0">
                                <button type="submit" class="btn btn-sm btn-success btn-block">
                                    Update Invoice
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