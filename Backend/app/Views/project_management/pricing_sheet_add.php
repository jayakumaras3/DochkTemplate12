<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/PM_pricing_sheet'); ?>">Effort Sheet</a></li>
                </ol>
            </div>
            <h4 class="page-title">Create New Effort Sheet</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_pricing_sheet/add_pricing_sheet_submit') ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Name <span class="text-danger">*</span></label>
                                <input required type="text" class="form-control col-md-12" name="pricing_name" placeholder="Short Name" value="" />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="clientname" class="form-label">Client <span class="text-danger">* <a href="<?php echo base_url('User_login/client_list/my_client_list') ?>">Click here to Add New Client</a></span></label>
                                <select name="client" class="form-control">
                                    <?php foreach ($getclients as $client) { ?>
                                        <option value="<?php echo $client['id_c'] ?>"><?php echo $client['client_name'] ?></option>
                                    <?php } ?>
                                </select>
                                <!-- <input required type="text" class="form-control col-md-12" name="client" placeholder="Client Name" /> -->
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class=" mb-1">
                                <label for="purchase_order_id" class="form-label">Account Manager <span class="text-danger">*</span></label>
                                <select name="account_manager" class="form-control">
                                    <!-- <option value="0">Select Account Manager</option> -->
                                    <?php foreach ($salesuser as $sales) { ?>
                                        <option value="<?php echo $sales['id_user'] ?>"><?php echo $sales['fullname'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1">
                                <label for="inputEmail3" class="col-form-label">Requirement</label>
                                <textarea class="form-control" name="description"></textarea>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="margin" value="30">
                    <input type="hidden" name="pricing_model" value="25">
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="text-sm-end  mt-sm-0">
                                <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light mb-3">
                                    <?php echo  lang('Buttons.Create') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>