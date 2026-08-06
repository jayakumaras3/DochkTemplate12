<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/PM_ucn/edit_ucn'); ?>">Edit UCN</a></li>

                </ol>
            </div>
            <h4 class="page-title">Create New Purchase Order</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_purchase_order/add_purchase_order_submit') ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Name <span class="text-danger">*</span></label>
                                <input required type="text" class="form-control col-md-12" name="pricing_name" placeholder="Short Name" value="<?php echo $get_pricing_sheet_data[0]['proposal_name']; ?>" />
                            </div>
                        </div>
                        <input type="hidden" name="currency" value="1">
                        <input type="hidden" name="account_manager" value="<?php echo $get_pricing_sheet_data[0]['requested_by']; ?>">
                        <input type="hidden" name="client" value="<?php echo $get_pricing_sheet_data[0]['client']; ?>">
                        <input type="hidden" name="pricing_sheet_id" value="<?php echo $ppid; ?>">
                        <!-- <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Currency <span class="text-danger">*</span></label>
                                <select name="currency" class="form-control">
                                    <option value="1" SELECTED>US Dollars</option>
                                </select>
                            </div>
                        </div> -->
                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">PO Value <span class="text-danger">*</span></label>
                                <input required type="number" class="form-control col-md-12" name="po_value" placeholder="PO Value" value="" />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">PO Number <span class="text-danger">*</span></label>
                                <input required type="text" class="form-control col-md-12" name="po_number" placeholder="PO Number" value="" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Project Value <span class="text-danger">*</span></label>
                                <input required type="number" class="form-control col-md-12" name="project_value" placeholder="Project Value" value="" />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">PO Status <span class="text-danger">*</span></label>
                                <select name="po_status" class="form-control">
                                    <option value="Received" SELECTED>Received</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Email Confirmation">Email Confirmation</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1">
                                <label for="inputEmail3" class="col-form-label">Remarks</label>
                                <textarea class="form-control" name="remarks"></textarea>

                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="text-sm-end  mt-sm-0">
                                <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light">
                                    Create New Purchase Order
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>