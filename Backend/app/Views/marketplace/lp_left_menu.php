<?php
$userlevel = session()->get('userlevel');
$arrayuserlevel = explode(',', $userlevel);
$canManageLP = in_array('44', $arrayuserlevel) || in_array('5', $arrayuserlevel);
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <?php if ($canManageLP) { ?>
                <div class="page-title-right">
                    <button type="button"
                        class="btn btn-outline-primary btn-xs rounded-pill waves-effect waves-light"
                        data-bs-toggle="modal"
                        data-bs-target="#createLearningPlanModal">
                        <i class="mdi mdi-plus-circle"></i> <?php echo lang('Buttons.Create_lp'); ?>
                    </button>
                </div>
            <?php } ?>
            <h4 class="page-title"><?= lang('UI_Text.Learning_Plan') ?></h4>
        </div>
    </div>
</div>
<?php if ($canManageLP) { ?>
    <div class="modal fade" id="createLearningPlanModal" tabindex="-1"
        aria-labelledby="createLearningPlanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content create-lp-modal">
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="modal-title fw-bold" id="createLearningPlanModalLabel">
                            <?php echo lang('Buttons.Create_lp'); ?>
                        </h5>
                        <p class="text-muted font-13 mb-0">Fill in the details below to create a new learning plan.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body pt-2">

                    <form id="createLearningPlanForm"
                        action="<?php echo base_url('marketplace/admin/add_new_marketplace') ?>"
                        method="post"><?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label fw-semibold"><?php echo lang('UI_Text.Name'); ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control"
                                name="marketplace_name"
                                placeholder="<?php echo lang('UI_Text.Name'); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold"><?php echo lang('UI_Text.Duration'); ?> <span class="text-danger">*</span></label>
                            <input type="number" class="form-control"
                                name="duration"
                                placeholder="<?php echo lang('UI_Text.Duration'); ?>" min="0"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold"><?php echo lang('UI_Text.Mode'); ?> <span class="text-danger">*</span></label>
                            <select class="form-select" name="mode" required>
                                <option value=""><?php echo lang('UI_Text.Select Mode'); ?></option>
                                <option value="1"><?php echo lang('UI_Text.Sequential'); ?></option>
                                <option value="2"><?php echo lang('UI_Text.Non_Sequential'); ?></option>
                            </select>
                        </div>
                        <input type="hidden" name="description" value="">
                        <input type="hidden" name="remarks" value="">
                        <input type="hidden" name="language" value="English">
                        <input type="hidden" name="type" value="<?php echo $type; ?>">
                    </form>
                </div>

                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                        <?php echo lang('Buttons.Cancel') ?>
                    </button>
                    <button type="submit" form="createLearningPlanForm" class="btn btn-primary rounded-pill px-4">
                        <?php echo lang('Buttons.Submit'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .create-lp-modal {
            border-radius: 18px;
            border: none;
            box-shadow: 0 0.5rem 1.5rem rgba(50, 58, 70, 0.2);
        }

        .create-lp-modal .form-control,
        .create-lp-modal .form-select {
            border-radius: 10px;
            padding: 0.55rem 0.9rem;
        }
    </style>
<?php } ?>
