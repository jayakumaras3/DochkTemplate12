<div class="d-flex align-items-center gap-2">
    <?php if ($canManageCourses): ?>
        <div class="dropdown">
            <a href="#" class="dropdown-toggle arrow-none text-muted" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="mdi mdi-dots-vertical font-18"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end">
                <form class="form-horizontal" action="<?= base_url($settings_link) ?>" method="POST"><?= csrf_field() ?>
                    <input type="hidden" name="scourse_id" value="<?= esc($row['scourse_id']) ?>">
                    <input type="hidden" name="course_name" value="<?= esc($row['course_name']) ?>">
                    <button type="submit" class="dropdown-item"><i class="mdi mdi-cog-outline me-1"></i> <?= lang('Buttons.Settings') ?></button>
                </form>
                <form class="form-horizontal" action="<?= base_url($assign_user_link) ?>" method="POST"><?= csrf_field() ?>
                    <input type="hidden" name="scourse_id" value="<?= esc($row['scourse_id']) ?>">
                    <button type="submit" class="dropdown-item"><i class="mdi mdi-account-multiple-plus-outline me-1"></i> <?= lang('Buttons.Assign_Users') ?></button>
                </form>
            </div>
        </div>
    <?php endif; ?>
    <form class="form-horizontal mb-0" action="<?= base_url('my_training/read_more') ?>" method="POST"><?= csrf_field() ?>
        <input type="hidden" name="crid" value="<?= esc($row['scourse_id']) ?>">
        <input type="hidden" name="detail_type" value="2">
        <input type="hidden" name="tab" value="1">
        <button class="btn btn-outline-info waves-effect btn-xs rounded-pill waves-light"><?= lang('Buttons.View') ?></button>
    </form>
</div>
