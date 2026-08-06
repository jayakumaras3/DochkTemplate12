<?php
$userlevel = session()->get('userlevel');
$arrayuserlevel = explode(',', $userlevel);
$canManageLP = in_array('44', $arrayuserlevel) || in_array('5', $arrayuserlevel);
$canAssignClients = session()->get('client') == 1;
?>
<?php if (!empty($get_learning_plan)) { ?>
    <div class="row row-cols-2 row-cols-sm-3 row-cols-lg-4 row-cols-xl-5 row-cols-xxl-6 g-3 mb-5">
        <?php foreach ($get_learning_plan as $plan):
            $thumbnail = (!empty($plan['thumbnail']))
                ? base_url('assets/assets/uploads/learning_path/' . $plan['mp_id'] . '/' . $plan['thumbnail'])
                : base_url('public/aristo_assets/images/default_thumbnail.png');

            if ($plan['learning_plan_status'] == '3') {
                $statusClass = 'bg-soft-success text-success';
                $statusLabel = lang('UI_Text.Completed');
            } elseif ($plan['learning_plan_status'] == '2') {
                $statusClass = 'bg-soft-info text-info';
                $statusLabel = lang('UI_Text.In Progress');
            } else {
                $statusClass = 'bg-soft-warning text-warning';
                $statusLabel = lang('UI_Text.Not Started');
            }
        ?>
            <div class="col">
                <div class="lp-card" onclick="if (!event.target.closest('a, button, .dropdown-menu')) { document.getElementById('lpNavForm<?= (int) $plan['mp_id'] ?>').submit(); }">
                    <div class="lp-card-thumb">
                        <img src="<?= $thumbnail ?>" alt="<?= esc($plan['mp_name']) ?>" loading="lazy" decoding="async">
                    </div>
                    <?php if ($canManageLP) { ?>
                        <div class="lp-card-menu dropdown">
                            <a href="#" class="lp-menu-btn dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false" title="More">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <form class="form-horizontal" action="<?php echo base_url('marketplace/admin/edit_marketplace') ?>" method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="mp_name" value="<?php echo $plan['mp_name']; ?>">
                                    <input type="hidden" name="mp_id" value="<?php echo $plan['mp_id']; ?>">
                                    <input type="hidden" name="type" value="2">
                                    <button type="submit" class="dropdown-item"><i class="mdi mdi-cog-outline me-1"></i> <?php echo lang('Buttons.LP Settings'); ?></button>
                                </form>
                                <form class="form-horizontal" action="<?php echo base_url('marketplace/Learning_dashboard/add_users_to_learning_plan_view') ?>" method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="cid" value="MQ==">
                                    <input type="hidden" name="mp_id" value="<?php echo $plan['mp_id']; ?>">
                                    <input type="hidden" name="type" value="2">
                                    <button type="submit" class="dropdown-item"><i class="mdi mdi-account-multiple-plus-outline me-1"></i> <?php echo lang('Buttons.Assign_Users'); ?></button>
                                </form>
                                <form class="form-horizontal" action="<?php echo base_url('marketplace/admin/edit_courses') ?>" method="POST"><?= csrf_field() ?>
                                    <input type="hidden" name="mp_name" value="<?php echo $plan['mp_name']; ?>">
                                    <input type="hidden" name="mp_id" value="<?php echo $plan['mp_id']; ?>">
                                    <input type="hidden" name="type" value="2">
                                    <button type="submit" class="dropdown-item"><i class="mdi mdi-youtube-tv me-1"></i> <?php echo lang('Buttons.Link_Courses'); ?></button>
                                </form>

                                <?php if ($canAssignClients) { ?>
                                    <form class="form-horizontal" action="<?php echo base_url('marketplace/admin/edit_client') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="mp_name" value="<?php echo $plan['mp_name']; ?>">
                                        <input type="hidden" name="mp_id" value="<?php echo $plan['mp_id']; ?>">
                                        <input type="hidden" name="type" value="2">
                                        <button type="submit" class="dropdown-item"><i class="mdi mdi-account-group-outline me-1"></i> <?php echo lang('Buttons.Assign_Clients'); ?></button>
                                    </form>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="lp-card-body">
                        <h5 class="lp-card-title" title="<?= esc($plan['mp_name']) ?>"><?= esc($plan['mp_name']) ?></h5>
                        <div class="lp-status-row">
                            <span class="lp-status-badge <?= $statusClass ?>"><?= $statusLabel ?></span>
                        </div>
                        <hr class="lp-divider">
                        <div class="lp-stats-row">
                            <div class="lp-stats-group">
                                <div class="lp-stat-item">
                                    <span class="lp-stat-icon lp-stat-icon-round"><i class="mdi mdi-clock-outline"></i></span>
                                    <div>
                                        <div class="lp-stat-value">
                                            <?php if ($plan['duration'] > 0) {
                                                $duration = $plan['duration'];
                                                echo sprintf('%02d:%02d', intdiv($duration, 60), $duration % 60);
                                            } ?>
                                        </div>
                                        <div class="lp-stat-label"><?= lang('UI_Text.Duration') ?></div>
                                    </div>
                                </div>
                                <span class="lp-stat-divider"></span>
                                <div class="lp-stat-item">
                                    <span class="lp-stat-icon lp-stat-icon-square"><i class="mdi mdi-book-open-variant"></i></span>
                                    <div>
                                        <div class="lp-stat-value"><?= (int) $plan['total_courses'] ?></div>
                                        <div class="lp-stat-label"><?= lang('UI_Text.Courses') ?></div>
                                    </div>
                                </div>
                            </div>
                            <form class="mb-0" id="lpNavForm<?= (int) $plan['mp_id'] ?>" action="<?php echo base_url('marketplace/Learning_dashboard/learning_courses') ?>" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="mp_id" value="<?php echo $plan['mp_id']; ?>">
                                <input type="hidden" name="mode" value="<?php echo $plan['mode']; ?>">
                                <input type="hidden" name="mp_name" value="<?php echo $plan['mp_name']; ?>">
                                <input type="hidden" name="type" value="2">
                                <input type="hidden" name="tab" value="1">
                                <!--  <button type="submit" class="lp-chevron-btn" aria-label="<?php echo lang('Buttons.View'); ?>">
                                     <i class="mdi mdi-chevron-right"></i> 
                                </button> -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <style>
        .lp-card {
            position: relative;
            border: none;
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.07), 0 2px 6px rgba(0, 0, 0, 0.04);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            cursor: pointer;
        }

        .lp-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.1), 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .lp-card-thumb {
            position: relative;
            aspect-ratio: 15 / 7;
            background: linear-gradient(135deg, #eaf2ff 0%, #dbe9ff 100%);
            overflow: hidden;
        }

        .lp-card-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .lp-card-menu {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .lp-menu-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
            color: #495057;
            font-size: 13px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        .lp-menu-btn::after {
            display: none;
        }

        .lp-card-body {
            padding: 1.1rem 1.15rem 1.15rem;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .lp-card-title {
            font-size: 13px;
            font-weight: 600;
            color: #1d3153;
            margin-bottom: 0.65rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .lp-status-row {
            margin-bottom: 0.9rem;
        }

        .lp-status-badge {
            display: inline-block;
            padding: 0.32rem 0.75rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .lp-divider {
            border: 0;
            border-top: 1px solid #edf1f8;
            margin: 0 0 0.9rem;
        }

        .lp-stats-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: auto;
            gap: 0.5rem;
        }

        .lp-stats-group {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            min-width: 0;
        }

        .lp-stat-item {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            min-width: 0;
        }

        .lp-stat-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            background: #eaf2ff;
            color: #3479f6;
            font-size: 17px;
            flex-shrink: 0;
        }

        .lp-stat-icon-round {
            border-radius: 50%;
        }

        .lp-stat-icon-square {
            border-radius: 10px;
        }

        .lp-stat-value {
            font-size: 0.85rem;
            font-weight: 800;
            color: #1d3153;
            line-height: 1.2;
            white-space: nowrap;
        }

        .lp-stat-label {
            font-size: 0.68rem;
            color: #6c757d;
            white-space: nowrap;
        }

        .lp-stat-divider {
            width: 1px;
            height: 32px;
            background: #edf1f8;
            flex-shrink: 0;
        }

        .lp-chevron-btn {
            border: 0;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #eaf2ff;
            color: #3479f6;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            cursor: pointer;
            flex-shrink: 0;
            transition: background-color 0.15s ease, color 0.15s ease;
        }

        .lp-chevron-btn:hover {
            background: #3479f6;
            color: #fff;
        }

        [data-bs-theme="dark"] .lp-card {
            background: #232b36;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.3);
        }

        [data-bs-theme="dark"] .lp-card-thumb {
            background: linear-gradient(135deg, #1c2c44 0%, #232b36 100%);
        }

        [data-bs-theme="dark"] .lp-card-title,
        [data-bs-theme="dark"] .lp-stat-value {
            color: #f3f7f9;
        }

        [data-bs-theme="dark"] .lp-stat-label {
            color: #98a6ad;
        }

        [data-bs-theme="dark"] .lp-stat-icon,
        [data-bs-theme="dark"] .lp-chevron-btn {
            background: rgba(52, 121, 246, 0.18);
            color: #7fb0ff;
        }

        [data-bs-theme="dark"] .lp-chevron-btn:hover {
            background: #3479f6;
            color: #fff;
        }

        [data-bs-theme="dark"] .lp-divider,
        [data-bs-theme="dark"] .lp-stat-divider {
            border-color: #36404a;
            background: #36404a;
        }

        [data-bs-theme="dark"] .lp-menu-btn {
            background: rgba(35, 43, 54, 0.9);
            color: #cedeef;
        }
    </style>
<?php } else { ?>
    <div class="persistent-warning">
        <div class="danger-text">
            <?php echo lang('UI_Text.No_Learning_Plan_Assigned'); ?>
        </div>
    </div>
<?php } ?>