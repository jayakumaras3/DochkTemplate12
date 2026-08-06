<?php

$userlevel = session('userlevel');
$arrayuserlevel = array_map('intval', explode(',', $userlevel));
$error = session('error');
if (isset($error)):
    echo '<script>alert("' . isset($error) . '"]</script>';
endif;
$client = session('client');
$arraystakeholders = explode(',', $client);
?>
<?php $accessmenu = session()->get('accessmenu');
$arrayaccessmenu  = array_map('intval', explode(',', $accessmenu)); ?>
<!-- Start Content-->
<style>
    .thumbnail_db img {
        height: 100%;
        width: 100%;
    }

    .thumbnail_db img {
        object-fit: contain;
    }

    /* Add these styles for stars */
    .star {
        font-size: 24px;
        color: #ccc;
        cursor: pointer;
    }

    .star.selected,
    .star.hover,
    .star.half-selected {
        color: #ffcc00;
    }

    .star-rating {
        /* font-size: 24px; */
        color: gold;
        /* Adjust the color as needed */
    }

    .rating-container {
        display: inline-flex;
        /* Use inline-flex for better control */
        align-items: center;
        /* Align items vertically in the flex container */
    }

    #ratingContainer {
        /* Adjust the styles as needed */
        font-size: 18px;
        /* Example font size */
        margin-left: 0;
        /* Adjust as needed for spacing between rating and count user */
        line-height: 1;
        /* Reset the line-height to default for precise vertical alignment */
    }

    .cardtile {
        padding: 10px;
        background-color: rgba(255, 255, 255, 1);
        margin-right: 1px;
        margin-left: 1px;
        border-radius: 10px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.1), 0 6px 20px 0 rgba(0, 0, 0, 0.02);
    }

    .cardshaddow {
        border-radius: 10px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.1), 0 6px 20px 0 rgba(0, 0, 0, 0.02);
    }

    .course-card {
        border: none;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.07), 0 2px 6px rgba(0, 0, 0, 0.04);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .course-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.1), 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .course-card-thumb-form {
        margin: 0;
    }

    .course-card-thumb-btn {
        display: block;
        width: 100%;
        padding: 0;
        border: 0;
        background: none;
        cursor: pointer;
    }

    .course-thumb {
        position: relative;
        height: 150px;
        overflow: hidden;
        background-color: #eef1f6;
    }

    .course-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .course-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        padding: 0.25rem 0.65rem;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
        color: #fff;
        letter-spacing: 0.02em;
    }

    .course-badge-danger {
        background-color: #fa5c7c;
    }

    .course-badge-alpha {
        background-color: #7c5cff;
    }

    .course-badge-beta {
        background-color: #00b8d9;
    }

    .course-badge-gamma {
        background-color: #ff7043;
    }

    .course-play-btn {
        position: absolute;
        right: 10px;
        bottom: 10px;
        width: 2.25rem;
        height: 2.25rem;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        color: #343a40;
        font-size: 16px;
    }

    .course-card-body {
        padding: 0.9rem 1rem 1rem;
    }

    .course-card-title {
        font-size: 13px;
        font-weight: 600;
        color: #343a40;
        margin: 0 0 0.35rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 2.6em;
    }

    /* The card itself darkens automatically in dark mode (it's a plain Bootstrap .card),
       but this title color is hardcoded, so it needs its own dark-mode override to avoid
       rendering as near-black text on a near-black card. */
    [data-bs-theme="dark"] .course-card-title {
        color: #f3f7f9;
    }

    .course-card-duration {
        font-size: 11px;
        color: #98a6ad;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
</style>
<script>
    function displayStars(rating, containerId) {
        const container = document.getElementById(containerId);
        console.log("Rating for " + containerId + ": " + rating); // Debug log for rating
        container.innerHTML = ''; // Clear previous content

        if (rating === null || rating === 0) {
            // Display 5 outlined stars if the rating is null or zero
            for (let i = 0; i < 5; i++) {
                const outlinedStar = document.createElement('span');
                outlinedStar.innerHTML = '<i class="mdi mdi-star-outline text-warning"></i>';
                container.appendChild(outlinedStar);
            }
        } else {
            const fullStars = Math.floor(rating);
            for (let i = 0; i < fullStars; i++) {
                const star = document.createElement('span');
                star.innerHTML = '<i class="mdi mdi-star text-warning"></i>'; // Full star character
                container.appendChild(star);
            }

            const decimalPart = rating - fullStars;
            if (decimalPart > 0) {
                const halfStar = document.createElement('span');
                halfStar.innerHTML = '<i class="mdi mdi-star-half text-warning"></i>'; // Half-filled star character
                container.appendChild(halfStar);
            }

            const emptyStars = 5 - Math.ceil(rating); // Calculate empty stars
            for (let i = 0; i < emptyStars; i++) {
                const outlinedStar = document.createElement('span');
                outlinedStar.innerHTML = '<i class="mdi mdi-star-outline text-warning"></i>'; // Outlined star character
                container.appendChild(outlinedStar);
            }
        }
    }
</script>
<?php $current_url = current_url(true); // Returns CodeIgniter\HTTP\URI object 
?>
<?php $segment1 = uri_string(); // Returns string like 'my_training' 
?>
<?php $current_page = explode('/', uri_string())[0]; ?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('my_training') ?>"><?= lang('UI_Text.Dashboard') ?></a></li>
                </ol>
            </div>
            <h4 class="page-title"><?php echo lang('Buttons.Favorites'); ?></h4>
        </div>
    </div>
</div>

<?php if ($favorite_courses != '') {
    if (count($favorite_courses) > 0) { ?>

        <div id="holder" class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 g-3">

            <?php foreach ($favorite_courses as $clienteachCourseddata) {
                $course_name = $clienteachCourseddata['course_name'];

                $max_length = 39; // Maximum length of the string
                if (strlen($course_name) > $max_length) {
                    $shortened_name = substr($course_name, 0, $max_length) . ".."; // Append "..." for indication
                } else {
                    $shortened_name = $course_name;
                }

                if (!empty($clienteachCourseddata['thumbnail'])) {
                    $thumbnail = base_url('assets/assets/uploads/SCORM_course_thumbnail/' . $clienteachCourseddata['scourse_id'] . '/' . $clienteachCourseddata['thumbnail']);
                } else {
                    $thumbnail = base_url('public/aristo_assets/images/default_thumbnail.png');
                }

                // Draft modes (Development/Alpha/Beta/Gamma) get a badge on the thumbnail,
                // matching the tile design used on the main dashboard.
                $course_mode = $clienteachCourseddata['mode'];
                $modeLabels = [
                    '1' => 'UI_Text.Development',
                    '3' => 'UI_Text.Alpha',
                    '4' => 'UI_Text.Alpha_2',
                    '5' => 'UI_Text.Beta',
                    '6' => 'UI_Text.Beta_2',
                    '7' => 'UI_Text.Gamma',
                    '8' => 'UI_Text.Gamma_2',
                ];
                $modeBadgeClasses = [
                    '1' => 'course-badge-danger',
                    '3' => 'course-badge-alpha',
                    '4' => 'course-badge-alpha',
                    '5' => 'course-badge-beta',
                    '6' => 'course-badge-beta',
                    '7' => 'course-badge-gamma',
                    '8' => 'course-badge-gamma',
                ];
                $badgeLabel = isset($modeLabels[$course_mode]) ? lang($modeLabels[$course_mode]) : null;
                $badgeClass = $modeBadgeClasses[$course_mode] ?? 'course-badge-danger';
            ?>
                <div class="col">
                    <div class="card h-100 course-card">
                        <form class="course-card-thumb-form" action="<?php echo base_url('my_training/read_more') ?>" method="POST"><?= csrf_field() ?>
                            <input type="hidden" name="crid" value="<?php echo $clienteachCourseddata['scourse_id'] ?>">
                            <input type="hidden" name="detail_type" value="1">
                            <input type="hidden" name="tab" value="1">
                            <button type="submit" class="course-card-thumb-btn">
                                <div class="course-thumb">
                                    <img src="<?= $thumbnail ?>" alt="<?php echo $clienteachCourseddata['course_name'] ?>" loading="lazy" decoding="async">
                                    <?php if ($badgeLabel) { ?>
                                        <span class="course-badge <?= $badgeClass ?>"><?= esc($badgeLabel) ?></span>
                                    <?php } ?>
                                    <span class="course-play-btn"><i class="mdi mdi-play"></i></span>
                                </div>
                            </button>
                        </form>
                        <div class="card-body course-card-body">
                            <h5 class="course-card-title" title="<?php echo $course_name; ?>"><?php echo $shortened_name ?></h5>
                            <span class="course-card-duration">
                                <i class="mdi mdi-clock-outline"></i>
                                <?php echo lang('UI_Text.Duration'); ?>:
                                <?php if ($clienteachCourseddata['duration'] > 0) {
                                    $duration = $clienteachCourseddata['duration'];
                                    if ($duration > 60) {
                                        $hours = intdiv($duration, 60);
                                        echo $hours . ' ' . lang('UI_Text.Hours');
                                        $balancemin = $duration - $hours * 60;
                                        if ($balancemin > 0) {
                                            echo $balancemin . ' ' . lang('UI_Text.Minutes');
                                        }
                                    } else {
                                        echo $duration . ' ' . lang('UI_Text.Minutes');
                                    }
                                } ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php } ?>

        </div> <!-- end row-->
    <?php

    } else { ?>
        <!-- <div class="persistent-warning">
            <div class="danger-text">
                <?php echo lang('UI_Text.No_Course_Assigned'); ?>
            </div>
        </div> -->
    <?php }
} else { ?>
    <!-- <div class="persistent-warning">
        <div class="danger-text">
            <?php echo lang('UI_Text.No_Course_Assigned'); ?>
        </div>
    </div> -->
<?php } ?>

<!-- end row-->


</div>
</div>