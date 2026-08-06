<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/PM_ucn'); ?>">My UCN</a></li>
                </ol>
            </div>
            <h4 class="page-title">Create New UCN</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_ucn/add_new_ucn') ?>" method="POST" id="submitForm"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Name <span class="text-danger">*</span></label>
                                <input required type="text" class="form-control col-md-12" name="ucn_name" placeholder="Name" value="" />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="clientname" class="form-label">Client <span class="text-danger">* <a href="<?php echo base_url('User_login/client_list/my_client_list') ?>">Click here to Add New Client</a></span></label>
                                <select name="client" class="form-control">
                                    <?php foreach ($clientlist as $client) { ?>
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
                    <hr>
                    <p>Effort Data</p>
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Instruction Design</label>
                                <input type="number" class="form-control col-md-12" name="ID_effort" value="" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Content Editor</label>
                                <input type="number" class="form-control col-md-12" name="CE_effort" value="" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Graphic Design</label>
                                <input type="number" class="form-control col-md-12" name="Graphic_effort" value="" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Visual Design</label>
                                <input type="number" class="form-control col-md-12" name="Media_effort" value="" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Visualizer</label>
                                <input type="number" class="form-control col-md-12" name="Viz_effort" value="" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Post Production</label>
                                <input type="number" class="form-control col-md-12" name="PP_effort" value="" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Articulate</label>
                                <input type="number" class="form-control col-md-12" name="AR_effort" value="" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">3D Modeling</label>
                                <input type="number" class="form-control col-md-12" name="3D_effort" value="" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">General Programming</label>
                                <input type="number" class="form-control col-md-12" name="GP_effort" value="" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Quality Assurance</label>
                                <input type="number" class="form-control col-md-12" name="QA_effort" value="" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Unity3D</label>
                                <input type="number" class="form-control col-md-12" name="Unity_effort" value="" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Project Management</label>
                                <input type="number" class="form-control col-md-12" name="PM_effort" value="" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Subject Matter Expert</label>
                                <input type="number" class="form-control col-md-12" name="SME_effort" value="" />
                            </div>
                        </div>
                    </div>
                    <hr>
                    <p>ADDITIONAL DATA</p>
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Add. Cost Desc 1</label>
                                <input type="text" class="form-control col-md-12" name="desc_1" value="" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Value 1 ($)</label>
                                <input type="number" class="form-control col-md-12" name="value_1" value="" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Add. Cost Desc 2</label>
                                <input type="text" class="form-control col-md-12" name="desc_2" value="" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Value 2 ($)</label>
                                <input type="number" class="form-control col-md-12" name="value_2" value="" />
                            </div>
                        </div>
                    </div>
                    <hr>
                    <p>Purchase Order Details</p>
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">PO Value</label>
                                <input type="number" class="form-control col-md-12" name="PO_value" value="" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">PO Number</label>
                                <input type="text" class="form-control col-md-12" name="PO_number" value="" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Proj Value</label>
                                <input type="number" class="form-control col-md-12" name="Proj_value" value="" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">PO Status</label>
                                <select name="po_status" class="form-control">
                                    <option value="Received">Received</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Email Confirmation">Email Confirmation</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">

                            <div class="text-sm-end  mt-sm-0">
                                <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light" id="submitButton">
                                    Create New UCN
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>