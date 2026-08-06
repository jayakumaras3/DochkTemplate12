<?php
// Shared "Xh Ym" duration formatter and course-state label/color/icon lookup, used by the
// All Courses grid.
$formatDuration = function ($minutes) {
    $minutes = (int) $minutes;
    if ($minutes <= 0) {
        return '0 ' . lang('UI_Text.Minutes');
    }
    if ($minutes > 60) {
        $hours = intdiv($minutes, 60);
        $rest = $minutes - $hours * 60;
        return $rest > 0
            ? $hours . ' ' . lang('UI_Text.Hours') . ' ' . $rest . ' ' . lang('UI_Text.Minutes')
            : $hours . ' ' . lang('UI_Text.Hours');
    }
    return $minutes . ' ' . lang('UI_Text.Minutes');
};

$stateMeta = [
    'completed'   => ['label' => lang('UI_Text.Completed'), 'color' => 'success', 'icon' => 'mdi-check-circle'],
    'in_progress' => ['label' => lang('UI_Text.In Progress'), 'color' => 'info', 'icon' => 'mdi-play-circle'],
    'not_started' => ['label' => lang('UI_Text.Not Started'), 'color' => 'secondary', 'icon' => 'mdi-circle-outline'],
];

$thumbFor = function ($course) {
    return (!empty($course['thumbnail']))
        ? base_url('assets/assets/uploads/SCORM_course_thumbnail/' . $course['scourse_id'] . '/' . $course['thumbnail'])
        : base_url('public/aristo_assets/images/default_thumbnail.png');
};

$banner = (!empty($learning_plan_details['banner']))
    ? base_url('assets/assets/uploads/learning_banner_path/' . $mp_id . '/' . $learning_plan_details['banner'])
    : base_url('assets/assets/img/default_learning_plan_banner.jpg');
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= base_url($header_link) ?>"><?= $header_title ?></a></li>
                </ol>
            </div>
            <h4 class="page-title"><?= lang('UI_Text.Learning_Plan_Details') ?></h4>
        </div>
    </div>
</div>

<?php if ($totalCourses > 0): ?>
    <div class="row">
        <div class="<?= $canManageLP ? 'col-lg-9' : 'col-lg-12' ?>">
            <!-- Hero -->
            <div class="lpd-hero mb-3" style="background-image:url('<?= $banner ?>');">
                <div class="lpd-hero-overlay">
                    <i class="mdi mdi-shield-check-outline lpd-hero-shield"></i>
                    <h3 class="lpd-hero-title"><?= esc($mp_name) ?></h3>
                    <?php if (!empty($learning_plan_details['description'])): ?>
                        <p class="lpd-hero-desc"><?= esc(strip_tags($learning_plan_details['description'])) ?></p>
                    <?php endif; ?>

                    <div class="lpd-hero-stats">
                        <div class="lpd-hero-stat">
                            <i class="mdi mdi-book-open-variant"></i>
                            <div>
                                <div class="lpd-hero-stat-value"><?= $totalCourses ?></div>
                                <div class="lpd-hero-stat-label"><?= lang('UI_Text.Total_Courses') ?></div>
                            </div>
                        </div>
                        <div class="lpd-hero-stat">
                            <i class="mdi mdi-clock-outline"></i>
                            <div>
                                <div class="lpd-hero-stat-value"><?= $formatDuration($totalDuration) ?></div>
                                <div class="lpd-hero-stat-label"><?= lang('UI_Text.Duration') ?></div>
                            </div>
                        </div>
                        <?php if (!empty($certificate_assign)): ?>
                            <div class="lpd-hero-stat">
                                <i class="mdi mdi-certificate-outline"></i>
                                <div>
                                    <div class="lpd-hero-stat-value"><?= lang('UI_Text.Certificate') ?></div>
                                    <div class="lpd-hero-stat-label"><?= lang('UI_Text.Completed') ?></div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="lpd-progress-row">
                        <span><?= lang('UI_Text.Overall_Progress') ?></span>
                        <span class="lpd-progress-pct"><?= $progressPercent ?>%</span>
                    </div>
                    <div class="progress lpd-progress-bar-track">
                        <div class="progress-bar bg-success" style="width:<?= $progressPercent ?>%"></div>
                    </div>
                    <div class="lpd-progress-sub">
                        <span><?= sprintf(lang('UI_Text.Courses_Completed_Progress'), $completedCount, $totalCourses) ?></span>
                        <span><?= $formatDuration($remainingDuration) ?> <?= lang('UI_Text.Remaining') ?></span>
                    </div>

                </div>
            </div>

            <?php if ($continueCourse): ?>
                <!-- Continue Learning -->
                <div class="card lpd-continue-card mb-3">
                    <div class="card-body">
                        <h5 class="mb-0"><?= lang('UI_Text.Continue_Learning') ?></h5>
                        <p class="text-muted font-13 mb-3"><?= lang('UI_Text.Pick_Up_Where_You_Left_Off') ?></p>
                        <div class="row align-items-start g-3">
                            <div class="col-12 col-sm-3">
                                <img src="<?= $thumbFor($continueCourse) ?>" class="lpd-continue-thumb" alt="<?= esc($continueCourse['course_name']) ?>" loading="lazy">
                            </div>
                            <div class="col-12 col-sm-6">
                                <h6 class="mb-1"><?= esc($continueCourse['course_name']) ?></h6>
                                <?php if (!empty($continueCourse['description'])): ?>
                                    <p class="text-muted font-13 mb-2"><?= esc(mb_strimwidth(strip_tags($continueCourse['description']), 0, 400, '…')) ?></p>
                                <?php endif; ?>
                                <span class="text-muted font-13"><i class="mdi mdi-clock-outline"></i> <?= $formatDuration($continueCourse['duration']) ?></span>
                            </div>
                            <div class="col-12 col-sm-3 align-self-end">
                                <form action="<?= base_url('my_training/read_more') ?>" method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="crid" value="<?= $continueCourse['scourse_id'] ?>">
                                    <input type="hidden" name="detail_type" value="<?= $type == 4 ? 11 : 9 ?>">
                                    <input type="hidden" name="mp_id" value="<?= $mp_id ?>">
                                    <input type="hidden" name="tab" value="1">
                                    <button type="submit" class="btn btn-primary rounded-pill w-100">
                                        <?= lang('UI_Text.Continue') ?> <i class="mdi mdi-arrow-right"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($canManageLP): ?>
            <div class="col-lg-3">
                <!-- Administrator -->
                <div class="card lpd-admin-card mb-3">
                    <div class="card-body">
                        <h5 class="lpd-admin-title"><i class="mdi mdi-shield-account-outline"></i> <?= lang('UI_Text.Administration') ?></h5>
                        <form action="<?= base_url('marketplace/admin/edit_marketplace') ?>" method="POST"><?= csrf_field() ?>
                            <input type="hidden" name="mp_name" value="<?= esc($learning_plan_details['mp_name']) ?>">
                            <input type="hidden" name="mp_id" value="<?= $learning_plan_details['mp_id'] ?>">
                            <input type="hidden" name="type" value="2">
                            <button type="submit" class="lpd-admin-link"><i class="mdi mdi-cog-outline"></i><span class="label-text"><?= lang('Buttons.LP Settings') ?></span><i class="mdi mdi-chevron-right"></i></button>
                        </form>
                        <form action="<?= base_url('marketplace/Learning_dashboard/add_users_to_learning_plan_view') ?>" method="POST"><?= csrf_field() ?>
                            <input type="hidden" name="cid" value="MQ==">
                            <input type="hidden" name="mp_id" value="<?= $learning_plan_details['mp_id'] ?>">
                            <input type="hidden" name="type" value="2">
                            <button type="submit" class="lpd-admin-link"><i class="mdi mdi-account-multiple-plus-outline"></i><span class="label-text"><?= lang('Buttons.Assign_Users') ?></span><i class="mdi mdi-chevron-right"></i></button>
                        </form>
                        <form action="<?= base_url('marketplace/admin/edit_courses') ?>" method="POST"><?= csrf_field() ?>
                            <input type="hidden" name="mp_name" value="<?= esc($learning_plan_details['mp_name']) ?>">
                            <input type="hidden" name="mp_id" value="<?= $learning_plan_details['mp_id'] ?>">
                            <input type="hidden" name="type" value="2">
                            <button type="submit" class="lpd-admin-link"><i class="mdi mdi-youtube-tv"></i><span class="label-text"><?= lang('Buttons.Link_Courses') ?></span><i class="mdi mdi-chevron-right"></i></button>
                        </form>
                        <?php if ($canAssignClients): ?>
                            <form action="<?= base_url('marketplace/admin/edit_client') ?>" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="mp_name" value="<?= esc($learning_plan_details['mp_name']) ?>">
                                <input type="hidden" name="mp_id" value="<?= $learning_plan_details['mp_id'] ?>">
                                <input type="hidden" name="type" value="2">
                                <button type="submit" class="lpd-admin-link"><i class="mdi mdi-account-group-outline"></i><span class="label-text"><?= lang('Buttons.Assign_Clients') ?></span><i class="mdi mdi-chevron-right"></i></button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- All Courses -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0"><?= lang('UI_Text.All_Courses') ?> (<?= $totalCourses ?>)</h5>
                <select id="lpdStatusFilter" class="form-select form-select-sm" style="width:auto;">
                    <option value="all"><?= lang('UI_Text.All_Status') ?></option>
                    <option value="completed"><?= lang('UI_Text.Completed') ?></option>
                    <option value="in_progress"><?= lang('UI_Text.In Progress') ?></option>
                    <option value="not_started"><?= lang('UI_Text.Not Started') ?></option>
                    <option value="locked"><?= lang('UI_Text.Locked') ?></option>
                </select>
            </div>
        </div>
    </div>
    <div class="row row-cols-2 row-cols-sm-3 row-cols-lg-4 row-cols-xl-6 g-3 mb-5" id="lpdCourseGrid">
        <?php foreach ($courses as $i => $course):
            $meta = $stateMeta[$course['state']];
            $filterState = $course['locked'] ? 'locked' : $course['state'];
        ?>
            <div class="col lpd-course-col" data-state="<?= $filterState ?>">
                <div class="card lpd-course-card h-100">
                    <div class="lpd-course-thumb">
                        <img src="<?= $thumbFor($course) ?>" alt="<?= esc($course['course_name']) ?>" loading="lazy" decoding="async">
                        <?php if ($course['locked']): ?>
                            <span class="lpd-course-ribbon locked"><?= lang('UI_Text.Locked') ?></span>
                            <span class="lpd-course-status-icon locked"><i class="mdi mdi-lock-outline"></i></span>
                            <div class="lpd-course-lock-overlay"></div>
                        <?php else: ?>
                            <span class="lpd-course-ribbon <?= $meta['color'] ?>"><?= $meta['label'] ?></span>
                            <span class="lpd-course-status-icon <?= $meta['color'] ?>"><i class="mdi <?= $meta['icon'] ?>"></i></span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <h6 class="lpd-course-title"><?= $i + 1 ?>. <?= esc($course['course_name']) ?></h6>
                        <span class="text-muted font-13"><i class="mdi mdi-clock-outline"></i> <?= $formatDuration($course['duration']) ?></span>
                    </div>
                    <?php if (!$course['locked']): ?>
                        <form action="<?= base_url('my_training/read_more') ?>" method="POST" class="stretched-link-form"><?= csrf_field() ?>
                            <input type="hidden" name="crid" value="<?= $course['scourse_id'] ?>">
                            <input type="hidden" name="detail_type" value="<?= $type == 4 ? 11 : 9 ?>">
                            <input type="hidden" name="mp_id" value="<?= $mp_id ?>">
                            <input type="hidden" name="tab" value="1">
                            <button type="submit" class="lpd-course-stretched-btn" aria-label="<?= esc($course['course_name']) ?>"></button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="persistent-warning">
        <div class="danger-text">
            <?= lang('UI_Text.No_Course_Assigned') ?>
        </div>
    </div>
<?php endif; ?>

<style>
    .lpd-hero {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        background-size: cover;
        background-position: center;
        min-height: 320px;
    }

    .lpd-hero-overlay {
        position: relative;
        padding: 1.75rem;
        background: linear-gradient(120deg, rgba(15, 23, 42, 0.92) 0%, rgba(15, 23, 42, 0.82) 45%, rgba(15, 23, 42, 0.55) 100%);
        color: #fff;
        min-height: 320px;
    }

    .lpd-hero-shield {
        font-size: 22px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 10px;
        padding: 8px;
        margin-bottom: 0.75rem;
        display: inline-block;
    }

    .lpd-hero-title {
        color: #fff;
        font-weight: 800;
        margin-bottom: 0.4rem;
    }

    .lpd-hero-desc {
        color: rgba(255, 255, 255, 0.75);
        max-width: 480px;
        font-size: 0.9rem;
    }

    .lpd-hero-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin: 1rem 0;
    }

    .lpd-hero-stat {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 20px;
        color: rgba(255, 255, 255, 0.85);
    }

    .lpd-hero-stat-value {
        font-weight: 700;
        font-size: 0.95rem;
        line-height: 1.1;
    }

    .lpd-hero-stat-label {
        font-size: 0.72rem;
        color: rgba(255, 255, 255, 0.6);
    }

    .lpd-progress-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        margin-bottom: 0.35rem;
    }

    .lpd-progress-pct {
        font-weight: 700;
        color: #6ee7a8;
    }

    .lpd-progress-bar-track {
        height: 10px;
        background: rgba(255, 255, 255, 0.15);
    }

    .lpd-progress-sub {
        display: flex;
        justify-content: space-between;
        font-size: 0.78rem;
        color: rgba(255, 255, 255, 0.65);
        margin-top: 0.4rem;
        margin-bottom: 1rem;
    }

    .lpd-continue-thumb {
        width: 100%;
        height: 160px;
        object-fit: cover;
        border-radius: 10px;
    }

    .lpd-admin-card {
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06), 0 2px 6px rgba(0, 0, 0, 0.04);
        border: none;
    }

    .lpd-admin-title {
        display: flex;
        align-items: center;
        gap: .5rem;
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: .75rem;
    }

    .lpd-admin-title i {
        color: #495057;
    }

    .lpd-admin-card form {
        margin-bottom: 0;
    }

    .lpd-admin-card form+form {
        border-top: 1px solid #f1f3f8;
        margin-top: .15rem;
        padding-top: .15rem;
    }

    .lpd-admin-link {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        width: 100%;
        border: 0;
        background: none;
        text-align: left;
        padding: 0.65rem 0.25rem;
        color: #313a46;
        font-weight: 600;
        font-size: 0.875rem;
        border-radius: 10px;
    }

    .lpd-admin-link .label-text {
        flex-grow: 1;
    }

    .lpd-admin-link .mdi-chevron-right {
        color: #adb5bd;
        flex-shrink: 0;
    }

    .lpd-admin-link:hover {
        background-color: rgba(52, 121, 246, 0.08);
        color: #313a46;
    }

    .lpd-course-card {
        border: none;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        position: relative;
    }

    .lpd-course-thumb {
        position: relative;
        height: 100px;
    }

    .lpd-course-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .lpd-course-ribbon {
        position: absolute;
        top: 0;
        left: 0;
        font-size: 10px;
        font-weight: 700;
        color: #fff;
        padding: 3px 8px;
        border-bottom-right-radius: 8px;
        text-transform: uppercase;
        z-index: 2;
    }

    .lpd-course-ribbon.success {
        background: #0acf97;
    }

    .lpd-course-ribbon.info {
        background: #3479f6;
    }

    .lpd-course-ribbon.secondary {
        background: #98a6ad;
    }

    .lpd-course-ribbon.locked {
        background: #495057;
    }

    .lpd-course-status-icon {
        position: absolute;
        top: 6px;
        right: 6px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        color: #fff;
        z-index: 2;
        background: rgba(0, 0, 0, 0.35);
    }

    .lpd-course-status-icon.success {
        background: #0acf97;
    }

    .lpd-course-status-icon.info {
        background: #3479f6;
    }

    .lpd-course-lock-overlay {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
    }

    .lpd-course-title {
        font-size: 0.82rem;
        font-weight: 600;
        color: #343a40;
        min-height: 2.4em;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .lpd-course-stretched-btn {
        position: absolute;
        inset: 0;
        border: 0;
        background: none;
        z-index: 1;
    }

    .lpd-course-col.hidden {
        display: none;
    }

    [data-bs-theme="dark"] .lpd-hero-overlay {
        background: linear-gradient(120deg, rgba(10, 14, 22, 0.95) 0%, rgba(10, 14, 22, 0.85) 45%, rgba(10, 14, 22, 0.6) 100%);
    }

    [data-bs-theme="dark"] .lpd-admin-title i {
        color: #cedeef;
    }

    [data-bs-theme="dark"] .lpd-admin-card form+form {
        border-top-color: #36404a;
    }

    [data-bs-theme="dark"] .lpd-admin-link {
        color: #cedeef;
    }

    [data-bs-theme="dark"] .lpd-admin-link:hover {
        background-color: rgba(255, 255, 255, 0.06);
        color: #cedeef;
    }

    [data-bs-theme="dark"] .lpd-course-title {
        color: #f3f7f9;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var filter = document.getElementById('lpdStatusFilter');
        if (filter) {
            filter.addEventListener('change', function() {
                var value = filter.value;
                document.querySelectorAll('#lpdCourseGrid .lpd-course-col').forEach(function(col) {
                    col.classList.toggle('hidden', value !== 'all' && col.dataset.state !== value);
                });
            });
        }

    });
</script>
