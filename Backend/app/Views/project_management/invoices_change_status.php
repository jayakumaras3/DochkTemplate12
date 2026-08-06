<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
               
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/MileStones/invoices'); ?>">Invoices</a></li>
          
                </ol>
            </div>
            <h4 class="page-title">Invoice Change Status</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Project_Manage/MileStones/update_invoice_status') ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-control">
                                    <option value="2">Invoiced</option>
                                    <option value="4">Received</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1">
                                <label for="inputEmail3" class="col-form-label">Notes <span class="text-danger">*</span></label>
                                <div>
                                    <input class="form-control" name="description" type="hidden" />
                                    <textarea class="ckeditor" name="notes" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <input type="hidden" name="invoice_id" value="<?php echo $invoice_id; ?>">
                            <div class="text-sm-end  mt-sm-0">
                                <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light">
                                    Update Invoice Status
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