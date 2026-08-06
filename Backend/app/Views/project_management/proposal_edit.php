<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
          
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/PM_Proposals'); ?>">Edit Proposal</a></li>
            
                </ol>
            </div>
            <h4 class="page-title">Edit Proposal</h4>
        </div>
    </div>
</div>
<?php if ($get_proposal_data[0]['status'] < 5) { ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_Proposals/updatelockstatus') ?>" method="POST"><?= csrf_field() ?>
                        <input type="hidden" name="status" value="6" />
                        <input type="hidden" name="proposal_id" value="<?php echo $get_proposal_data[0]['proposal_id']; ?>" />
                        <button type="submit" class="btn btn-outline-info waves-effect btn-sm waves-light">
                            <h4 class="text-dark my-1">Lock Proposal</h4>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_Proposals/edit_proposal_submit') ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Name <span class="text-danger">*</span></label>
                                <input required type="text" class="form-control col-md-12" name="proposal_name" placeholder="Short Name" value="<?php echo $get_proposal_data[0]['short_name']; ?>" />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-1">
                                <label for="clientname" class="form-label">Client <span class="text-danger">*</span></label>

                                <select name="client" class="form-control">
                                    <?php foreach ($getclients as $client) {
                                        if ($get_proposal_data[0]['client'] == $client['id_c']) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = '';
                                        } ?>
                                        <option value="<?php echo $client['id_c'] ?>" <?php echo $selected; ?>><?php echo $client['client_name'] ?></option>
                                    <?php } ?>
                                </select>
                                <!-- <input required type="text" class="form-control col-md-12" name="client" placeholder="Client Name" /> -->

                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class=" mb-1">
                                <label for="purchase_order_id" class="form-label">Account Manager <span class="text-danger">*</span></label>
                                <select name="account_manager" class="form-control">

                                    <?php foreach ($salesuser as $sales) {
                                        if ($get_proposal_data[0]['account_manager'] == $sales['id_user']) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = '';
                                        } ?>
                                        <option value="<?php echo $sales['id_user'] ?>" <?php echo  $selected ?>><?php echo $sales['fullname'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class=" mb-1">
                                <label for="purchase_order_id" class="form-label">Proposal Template <span class="text-danger">*</span></label>
                                <select name="template" class="form-control">
                                    <option value="<?php echo $get_proposal_data[0]['templates']; ?>">
                                        <?php
                                        $templates = $get_proposal_data[0]['templates'];
                                        switch ($templates) {
                                            case 1:
                                                echo "Standard Elearning";
                                                break;
                                            case 2:
                                                echo "AR/VR Simulation";
                                                break;
                                            case 3:
                                                echo "Video";
                                                break;
                                        }
                                        ?>

                                    </option>
                                    <option value="1">Standard Elearning</option>
                                    <option value="2">AR/VR Simulation</option>
                                    <option value="3">Video</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1">
                                <label for="inputEmail3" class="col-form-label">About Client</label>
                                <div>
                                    <input class="form-control" name="valid" type="hidden" />
                                    <textarea class="ckeditor" name="about_client" required><?php echo $get_proposal_data[0]['about_client']; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1">
                                <label for="inputEmail3" class="col-form-label">TQ Understanding of Requirement</label>
                                <div>
                                    <input class="form-control" name="valid" type="hidden" />
                                    <textarea class="ckeditor" name="requirement" required><?php echo $get_proposal_data[0]['requirement']; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1">
                                <label for="inputEmail3" class="col-form-label">TQ Proposed Solution</label>
                                <div>
                                    <input class="form-control" name="valid" type="hidden" />
                                    <textarea class="ckeditor" name="solution" required><?php echo $get_proposal_data[0]['solution']; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1">
                                <label for="inputEmail3" class="col-form-label">Assumptions</label>
                                <div>
                                    <input class="form-control" name="valid" type="hidden" />
                                    <textarea class="ckeditor" name="assumption" required><?php echo $get_proposal_data[0]['assumption']; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1">
                                <label for="inputEmail3" class="col-form-label">Status</label>
                                <div>
                                    <select name="status" class="form-control">
                                        <option value="1">New</option>
                                        <option value="6">Locked</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="text-sm-end  mt-sm-0">
                                <input type="hidden" name="proposal_id" value="<?php echo $get_proposal_data[0]['proposal_id']; ?>">
                                <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light">
                                    Update
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo base_url('Project_Manage/PM_pricing_sheet/add_user_to_pricing_sheet') ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Select User <span class="text-danger">*</span></label>
                                <select name="assignuser" class="form-control" required>
                                    <option value="" >Select User</option>
                                    <?php
                                    foreach ($project_manager as $data) {
                                        echo '<option value="' . $data['id_user'] . '">'  . $data['fname'] . ' ' . $data['lname'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Select Role <span class="text-danger">*</span></label>
                                <select name="role" class="form-control" required>
                                    <option value="">Select Role</option>
                                    <option value="1">Project Manager</option>
                                    <option value="2">Project Coordinator</option>
                                    <option value="5">Developer</option>
                                    <option value="10">ID</option>
                                    <option value="20">Sales</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <input type="hidden" name="ppid" value="<?php echo $proposal_id; ?>">
                            <input type="hidden" name="returnid" value="3">
                            <input type="hidden" name="type_of_assignment" value="3">
                            <button type="submit" class="btn btn-outline-primary waves-effect btn-sm waves-light">
                                Assign Users to UCN</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                            foreach ($access as $data) {
                                $j = $j + 1 ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $data['fname'] . ' ' . $data['lname']; ?></td>
                                    <td><?php $typeofrole = $data['type_of_role'];
                                        switch ($typeofrole) {
                                            case 1:
                                                echo 'Project Manager';
                                                break;
                                            case 2:
                                                echo 'Project Coordinator';
                                                break;
                                            case 5:
                                                echo 'Developer';
                                                break;
                                            case 10:
                                                echo 'ID';
                                                break;
                                            case 20:
                                                echo 'Sales';
                                                break;
                                        }

                                        ?></td>

                                    <td>
                                        <form action="<?php echo base_url('Project_Manage/PM_pricing_sheet/delete_userassignment') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="returnid" value="3">
                                            <input type="hidden" name="project_assign_id" value="<?php echo $data['project_assign_id']; ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                <span class="mdi mdi-trash-can-outline"></span></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php
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