<?php foreach ($courses as $course):
    if (empty($course['scourse_id']) || trim((string) $course['course_name']) === '') {
        continue;
    }
    $thumbnail = (!empty($course['thumbnail']))
        ? base_url('assets/assets/uploads/SCORM_course_thumbnail/' . $course['scourse_id'] . '/' . $course['thumbnail'])
        : base_url('public/aristo_assets/images/default_thumbnail.png');
?>
    <div class="card h-100 course-card">
        <form class="course-card-link-form" action="<?= base_url('my_training/read_more') ?>" method="POST"><?= csrf_field() ?>
            <input type="hidden" name="crid" value="<?= $course['scourse_id'] ?>">
            <input type="hidden" name="detail_type" value="5">
            <input type="hidden" name="tab" value="1">
            <button type="submit" class="course-card-link">
                <div class="course-thumb">
                    <img src="<?= $thumbnail ?>" alt="<?= esc($course['course_name']) ?>" loading="lazy" decoding="async">
                </div>
                <div class="card-body course-card-body">
                    <h5 class="course-card-title" title="<?= esc($course['course_name']) ?>"><?= esc($course['course_name']) ?></h5>
                    <div class="d-flex flex-column gap-1">
                        <span class="course-card-duration">
                            <i class="mdi mdi-clock-outline"></i>
                            <?= lang('UI_Text.Duration') ?>: <?= (int) $course['duration'] ?> <?= lang('UI_Text.Minutes') ?>
                        </span>
                        <?php if (!empty($course['language'])): ?>
                            <span class="course-card-duration">
                                <i class="mdi mdi-web"></i>
                                <?= lang('UI_Text.Language') ?>: <?= esc($course['language']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </button>
        </form>
    </div>
<?php endforeach; ?>
