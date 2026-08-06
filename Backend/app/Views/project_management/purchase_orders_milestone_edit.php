<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">

                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/PM_purchase_order/edit_purchase_order'); ?>">Edit
                            Purchase Order</a></li>

                </ol>
            </div>
            <h4 class="page-title">Edit Milestone</h4>
        </div>
    </div>
</div>
<?php if (isset($milestone_details)) { ?>
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal"
                        action="<?php echo base_url('Project_Manage/PM_purchase_order/update_milestones') ?>" method="POST"><?= csrf_field() ?>
                        <div class="row">

                            <div class="col-lg-12">
                                <div class="mb-1">
                                    <label for="projectname" class="form-label">Billing Milestone <span
                                            class="text-danger">*</span></label>
                                    <input required type="text" class="form-control col-md-12" name="description"
                                        placeholder="Milestone Name" value="<?php echo $milestone_details[0]['description'] ?>" />
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-1">
                                    <label for="projectname" class="form-label">Currency <span
                                            class="text-danger">*</span></label>
                                    <select name="currency" class="form-control">
                                        <option value="1" SELECTED>US Dollars</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-1">
                                    <label for="projectname" class="form-label">Invoice Value <span
                                            class="text-danger">*</span></label>
                                    <input required type="number" class="form-control col-md-12" name="value"
                                        placeholder="Invoice Value" value="<?php echo $milestone_details[0]['value'] ?>" />
                                </div>
                            </div>


                            <div class="col-lg-12">
                                <div class="mb-1">
                                    <label for="projectname" class="form-label">Invoice Date <span
                                            class="text-danger">*</span></label>
                                    <input data-lpignore="true" class="form-control" id="due_date" name="invoicing_dt" type="date" value="<?php echo $milestone_details[0]['invoicing_dt'] ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-1">
                                    <label for="inputEmail3" class="col-form-label">Notes</label>
                                    <div>
                                        <textarea name="notes" class="form-control"><?php echo $milestone_details[0]['notes'] ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <input type="hidden" name="milestone_id" value="<?php echo $milestone_details[0]['milestone_id']; ?>">
                                <div class="text-sm-end  mt-sm-0">
                                    <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light">
                                        Update Billing Milestone
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } ?>