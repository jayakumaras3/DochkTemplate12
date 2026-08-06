<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
          
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/PM_Proposals'); ?>">Proposals</a></li>
                
                </ol>
            </div>
            <h4 class="page-title">Proposal Details (<?php echo $get_proposal_data[0]['short_name']; ?>)</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_Proposals/add_proposal_details') ?>" method="POST"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-1">
                                    <label for="projectname" class="form-label">Locked Effort Sheet <span class="text-danger">*</span></label>
                                    <select required name="details_01" class="form-control">
                                        <?php
                                        foreach ($get_pricing_data as $x) {
                                            echo '<option value="' . $x['ppid'] . '">' . $x['proposal_name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-1">
                                    <label for="projectname" class="form-label">Remarks</label>
                                    <input type="text" class="form-control col-md-12" name="details_03" placeholder="Remarks" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-1">
                            <input type="hidden" name="types" value="1" />
                            <input type="hidden" name="details_02" value="" />
                            <input type="hidden" name="proposal_id" value="<?php echo $proposal_id; ?>" />
                            <button type="submit" class="btn btn-outline-primary waves-effect btn-sm waves-light">
                                Add Effort Sheet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_Proposals/add_proposal_details') ?>" method="POST"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-1">
                                    <label for="projectname" class="form-label">Milestones <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control col-md-12" name="details_01" placeholder="Example Alpha" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-1">
                                    <label for="projectname" class="form-label">Remarks</label>
                                    <input type="text" class="form-control col-md-12" name="details_03" placeholder="Example 30%" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-1">
                            <input type="hidden" name="types" value="2" />
                            <input type="hidden" name="details_02" value="" />
                            <input type="hidden" name="proposal_id" value="<?php echo $proposal_id; ?>" />
                            <button type="submit" class="btn btn-outline-warning waves-effect btn-sm waves-light">
                                Add Milestones
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_Proposals/add_proposal_details') ?>" method="POST"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-1">
                                    <label for="projectname" class="form-label">Tax Information <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control col-md-12" name="details_03" placeholder="GST as Applicable" value="" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-1">
                            <input type="hidden" name="types" value="3" />
                            <input type="hidden" name="details_01" value="" />
                            <input type="hidden" name="details_02" value="" />
                            <input type="hidden" name="proposal_id" value="<?php echo $proposal_id; ?>" />
                            <button type="submit" class="btn btn-outline-danger waves-effect btn-sm waves-light">
                                Tax information
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form enctype="multipart/form-data" class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_Proposals/upload_image') ?>" method="POST"><?= csrf_field() ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-1">
                                    <label>Cover Page</label>
                                    <select class="form-select" name="cover_pageid" required>
                                        <option value="1">Cover page 1</option>
                                        <option value="2">Cover page 2</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-1">
                                    <input type="file" name="file" required />
                                </div>
                            </div>
                        </div>
                        <div class="mt-1">
                            <input type="hidden" name="type" value="4" />
                            <input type="hidden" name="proposal_id" value="<?php echo $proposal_id; ?>" />
                            <button type="submit" class="btn btn-outline-info waves-effect btn-sm waves-light">
                                Upload Sample
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Detail</th>
                                    <th>Remarks</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $j = 0;
                                ?>
                                <?php
                                foreach ($get_proposal_details_ps as $ps) {
                                    $j = $j + 1 ?>
                                    <tr>
                                        <td><?php echo $j; ?></td>
                                        <td><?php echo 'Pricing'; ?></td>
                                        <td><?php echo '$'; ?></td>
                                        <td><?php echo $ps['details_03']; ?></td>
                                        <td>
                                            <form action="<?php echo base_url('Project_Manage/PM_Proposals/delete_details') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="proposal_details_id" value="<?php echo $ps['proposal_details_id']; ?>">
                                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                    <span class="mdi mdi-trash-can-outline"></span></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php } ?>

                                <?php
                                foreach ($get_proposal_details_milestone as $mile) {
                                    $j = $j + 1 ?>
                                    <tr>
                                        <td><?php echo $j; ?></td>
                                        <td><?php echo 'Milestones'; ?></td>
                                        <td><?php echo $mile['details_01']; ?></td>
                                        <td><?php echo $mile['details_03']; ?></td>
                                        <td>
                                            <form action="<?php echo base_url('Project_Manage/PM_Proposals/delete_details') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="proposal_details_id" value="<?php echo $mile['proposal_details_id']; ?>">
                                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                    <span class="mdi mdi-trash-can-outline"></span></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php } ?>

                                <?php
                                foreach ($get_proposal_details_taxes as $tax) {
                                    $j = $j + 1 ?>
                                    <tr>
                                        <td><?php echo $j; ?></td>
                                        <td><?php echo 'Taxes'; ?></td>
                                        <td> </td>
                                        <td><?php echo $tax['details_03']; ?></td>
                                        <td>
                                            <form action="<?php echo base_url('Project_Manage/PM_Proposals/delete_details') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="proposal_details_id" value="<?php echo $tax['proposal_details_id']; ?>">
                                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                    <span class="mdi mdi-trash-can-outline"></span></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php } ?>

                                 <?php
                                foreach ($get_proposal_details_image as $ps) {
                                    $details_01 = $ps['details_01'];
                                    if ($details_01 ==  1) {
                                        $cover_page = 'Cover page 1';
                                    } elseif ($details_01 ==  2) {
                                        $cover_page = 'Cover page 2';
                                    }
                                    $j = $j + 1 ?>
                                    <tr>
                                        <td><?php echo $j; ?></td>
                                        <td><?php echo $cover_page; ?></td>
                                        <td>Image</td>
                                        <td><?php echo $ps['details_03']; ?></td>
                                        <td>
                                            <form action="<?php echo base_url('Project_Manage/PM_Proposals/delete_image_details') ?>" method="POST"><?= csrf_field() ?>
                                                <input type="hidden" name="proposal_details_id" value="<?php echo $ps['proposal_details_id']; ?>">
                                                <input type="hidden" name="proposal_id" value="<?php echo $ps['proposal_id']; ?>">
                                                <input type="hidden" name="image_name" value="<?php echo $ps['details_03']; ?>">
                                                <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light">
                                                    <span class="mdi mdi-trash-can-outline"></span></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

</div>