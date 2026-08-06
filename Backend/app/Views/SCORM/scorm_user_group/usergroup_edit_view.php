<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('SCORM/Scorm_user_group'); ?>"><?= lang('UI_Text.User_Groups') ?></a></li>
                </ol>
            </div>
            <h4 class="page-title"><?= lang('UI_Text.Edit_User_Group') ?> - <?php echo $row[0]['description'] ?></h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6><?= lang('UI_Text.User_Group_Name') ?></h6>
                <form action="<?php echo base_url($form_link) ?>" method="post" autocomplete="off"><?= csrf_field() ?>
                    <div class="mb-2">
                        <input type="text" name="description" class="form-control" placeholder="Group Name" value="<?php echo $row[0]['description'] ?>" required="" />
                    </div>
                    <div class="mb-2">
                        <select class="form-select" name="status">
                            <option value="1"><?= lang('UI_Text.Active') ?></option>
                            <option value="0"><?= lang('UI_Text.Delete') ?></option>
                        </select>
                    </div>
                    <div class="form-row">
                        <button type="submit" class="btn btn-outline-warning btn-xs waves-effect waves-light"><?= lang('Buttons.Update') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h6> Logo</h6>
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
                            <input type="file" name="file" accept=".jpg,.jpeg" required />
                        </div>
                        <div class="form-group col-md-12">
                            <input type="hidden" name="sc_cgid" value="<?php echo $sc_cgid; ?>">

                            <button type="submit" class="btn btn-outline-success waves-effect btn-xs waves-light">Upload</button>
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
        </div>
    </div> -->
</div>
</div>
</div>
</div>
</div>