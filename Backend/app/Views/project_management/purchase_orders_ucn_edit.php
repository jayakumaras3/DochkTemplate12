<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/PM_purchase_order/edit_purchase_order'); ?>">Purchase Orders</a></li>
                   </ol>
            </div>
            <h4 class="page-title">Edit UCN</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_purchase_order/update_ucn') ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">UCN Name <span class="text-danger">*</span></label>
                                <input required type="text" class="form-control col-md-12" name="name" placeholder="UCN Name" value="<?php echo $ucn_edit_details[0]['name']; ?>" />
                            </div>
                        </div>
                   
                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input class="form-control" id="due_date" name="start_date" type="date" value="<?php echo $ucn_edit_details[0]['start_dt']; ?>">
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">End Date <span class="text-danger">*</span></label>
                                <input class="form-control" id="due_date" name="end_date" type="date" value="<?php echo $ucn_edit_details[0]['end_dt']; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1">
                                <label for="inputEmail3" class="col-form-label">Remarks</label>
                                <textarea class="form-control" name="remarks" required><?php echo $ucn_edit_details[0]['remarks']; ?></textarea>
                              
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">

                            <div class="text-sm-end  mt-sm-0">
                                <input type="hidden" name="percentage_po" value="100">
                                <input type="hidden" name="type_of_project" value="<?php echo $ucn_edit_details[0]['type_of_project']; ?>">
                                <input type="hidden" name="client" value="<?php echo $ucn_edit_details[0]['client']; ?>">
                                <input type="hidden" name="account_manager" value="<?php echo $ucn_edit_details[0]['account_manager']; ?>">
                                <input type="hidden" name="po_id" value="<?php echo $ucn_edit_details[0]['po_id']; ?>">
                                <input type="hidden" name="ucn_id" value="<?php echo $ucn_edit_details[0]['ucn_id']; ?>">
                                <button type="submit" class="btn btn-outline-warning waves-effect btn-sm waves-light">
                                    Update UCN
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