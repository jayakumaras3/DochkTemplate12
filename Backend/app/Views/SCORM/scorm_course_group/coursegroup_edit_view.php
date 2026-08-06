<style>
    .settings-section {
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06), 0 2px 6px rgba(0, 0, 0, 0.04);
        border: none;
    }

    .settings-section .section-title {
        display: flex;
        align-items: center;
        gap: .5rem;
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 1rem;
    }

    .settings-section .section-title i {
        color: #6658dd;
    }

    [data-bs-theme="dark"] .settings-section .section-title i {
        color: #9298f5;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('SCORM/Scorm_learn_group') ?>"><?= lang('UI_Text.Course_Groups') ?></a></li>

                </ol>
            </div>
            <h4 class="page-title"><?= lang('UI_Text.Edit_Course_Group') ?></h4>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-lg-4">
        <div class="card settings-section mb-3">
            <div class="card-body">
                <h5 class="section-title"><i class="mdi mdi-pencil-outline"></i> <?= lang('UI_Text.Edit_Course_Group') ?></h5>
                <form action="<?php echo base_url($form_link) ?>" method="post" autocomplete="off"><?= csrf_field() ?>
                    <div class="mb-2">
                        <label><?= lang('UI_Text.Group_Name') ?></label>
                        <input type="text" name="description" class="form-control" placeholder="<?= lang('UI_Text.Group_Name') ?>" value="<?php echo $row[0]['description'] ?>" required="" />
                    </div>
                    <div class="mb-2">
                        <label><?= lang('UI_Text.Visibility') ?></label>
                        <select class="form-select" name="visible">
                            <?php $visible = $row[0]['visible']; ?>
                            <option value="0" <?php ($visible == 0) ? 'SELECTED' : '' ?>><?= lang('UI_Text.Visible_to_All') ?></option>
                            <option value="1" <?php echo ($visible == 1) ? 'SELECTED' : '' ?>><?= lang('UI_Text.Visible_to_Select') ?></option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label><?= lang('UI_Text.Status') ?></label>
                        <select class="form-select" name="status">
                            <option value="1"><?= lang('UI_Text.Active') ?></option>
                            <option value="0"><?= lang('UI_Text.Delete') ?></option>
                        </select>
                    </div>
                    <div class="form-row">
                        <input type="hidden" name="sc_cgid" value="<?php echo $sc_cgid; ?>">
                        <button type="submit" class="btn btn-outline-warning rounded-pill waves-effect btn-xs waves-light"><?= lang('Buttons.Update') ?></button>
                    </div>
                    <?php if (isset($validation)) : ?>
                        <div class=col-12 col-sm-4>
                            <div class="alert alert-danger" role="alert">
                                <?= $validation->listErrors() ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- <div class="card">
            <div class="card-body">
                <h6>Group Logo</h6>
                <?php if ($row[0]['logo']  == '') {
                ?>
                <?php } else {
                ?>
                    <div class="head bg-dot30 np tac">
                        <img style="max-height:100px;" src="<?php echo base_url() . '/assets/assets/uploads/group_logo/' . $sc_cgid . '/' . $row[0]['logo'] ?>" class="img-squre img-thumbnail" />
                    </div><br />
                <?php }
                ?>
                <div class="form-row">
                    <form class="form-horizontal" enctype="multipart/form-data" action="<?php echo base_url('SCORM/scorm_learn_group/uploadgrouplogo') ?>" method="POST"><?= csrf_field() ?>
                        <div class="form-group col-md-12 mb-3">
                            <input type="file" name="file"  accept=".jpg,.jpeg"  required />
                        </div>
                        <div class="form-group col-md-12">
                            <input type="hidden" name="sc_cgid" value="<?php echo $sc_cgid; ?>">

                            <button type="submit" class="btn btn-outline-warning btn-xs waves-effect waves-light">Upload</button>
                        </div>
                        <?php if (isset($logovalidation)) : ?>
                            <div class="form-group col-md-12">
                                <div class="alert alert-danger" role="alert">
                                    <?= $logovalidation->listErrors() ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div> -->
    </div>
    <?php if ($client == 1) { ?>

        <div class="col-lg-8">
            <div class="card settings-section mb-3">
                <div class="card-body">
                    <h5 class="section-title"><i class="mdi mdi-domain"></i> <?= lang('UI_Text.Add_Client') ?></h5>
                    <form action="<?php echo base_url('SCORM/Scorm_learn_group/addcoursegroup_to_client') ?>" method="post" autocomplete="off"><?= csrf_field() ?>
                        <div class="mb-1">
                            <select name="client" class="form-control">
                                <?php
                                foreach ($clientlist  as $client) {
                                ?>
                                    <option value="<?php echo  $client['id_c']; ?>"><?php echo  $client['client_name']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-row">
                            <input type="hidden" name="sc_cgid" value="<?php echo $sc_cgid; ?>">
                            <button type="submit" class="btn btn-outline-primary rounded-pill btn-xs waves-effect waves-light"><?= lang('Buttons.Submit') ?></button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card settings-section mb-3">
                <div class="card-body">
                    <h5 class="section-title"><i class="mdi mdi-format-list-bulleted"></i> <?= lang('UI_Text.Assigned_Clients') ?></h5>
                    <table class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th width="10%">#</th>
                                <th><?= lang('Buttons.Clients') ?></th>
                                <th><?= lang('Buttons.Delete') ?></th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php $j = 0;
                            foreach ($assigned_client_list  as $client_list) {
                                $j = $j + 1; ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo  $client_list['client'];  ?></td>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url('SCORM/Scorm_learn_group/delete_group_clients') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="scgcid" value="<?php echo $client_list['scgcid'] ?>">
                                            <button type="submit" class="btn btn-outline-danger rounded-pill waves-effect btn-xs waves-light " title="<?= lang('Buttons.Delete') ?>"><span class="mdi mdi-trash-can-outline"></span></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php } ?>
</div>