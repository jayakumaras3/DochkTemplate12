<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/PM_purchase_order/edit_purchase_order'); ?>">Purchase Orders</a></li>

                </ol>
            </div>
            <h4 class="page-title">UCN Linking to Project</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_purchase_order/add_ucn') ?>" method="POST" id="submitForm"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">UCN Name <span class="text-danger">*</span></label>
                                <input required type="text" class="form-control col-md-12" name="name" placeholder="UCN Name" value="<?php echo $pricing_name; ?>" />
                            </div>
                        </div>

                        <input type="hidden" name="type_of_project" value="E-Learning">
                        <input type="hidden" name="proposal_id" value="1">
                        <input type="hidden" name="pricing_id" value="1">
                        <input type="hidden" name="due_date" value="0">

                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input class="form-control" id="due_date" name="start_date" type="date">
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
                    <div class="row mt-2">
                        <div class="col-12">
                            <input type="hidden" name="account_manager" value="<?php echo $account_manager; ?>">
                            <input type="hidden" name="projectclient" value="<?php echo $projectclient; ?>">
                            <input type="hidden" name="po_id" value="<?php echo $po_id; ?>">
                            <div class="text-sm-end  mt-sm-0">
                                <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light" id="submitButton">
                                    Create New UCN and Link to Project
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo base_url('Project_Manage/PM_purchase_order/link_ucn_to_project') ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class=" mb-1">
                                <label class="form-label">Active UCN<span class="text-danger">*</span></label>
                                <select name="ucn" class="form-control">
                                    <?php foreach ($ucn_list as $ucnx) { ?>
                                        <option value="<?php echo $ucnx['ucn_id'] ?>"><?php echo $ucnx['name'] ?></option>
                                    <?php } ?>

                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12 mt-2">
                            <input type="hidden" name="po_id" value="<?php echo $po_id; ?>">
                            <button type="submit" class="btn btn-outline-primary btn-xs rounded-pill waves-effect waves-light  ">
                                Link Existing UCN
                            </button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>


</div>