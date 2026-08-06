<?php

$clientCourseddata = $clientCourseddata ?? [];
$favoriteCourseddata = $favoriteCourseddata ?? [];
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
$arrayaccessmenu  = array_map('intval', explode(',', $accessmenu));

// Group the assigned courses by mode/progress status once, here, so both the summary tiles
// and the carousels further down read from the same array instead of running a second query
// or looping over the course list twice.
$coursesByStatus = ['in_progress' => [], 'not_started' => [], 'completed' => [], 'alpha' => [], 'beta' => [], 'gamma' => []];
foreach ($clientCourseddata as $clienteachCourseddata) {
    $mode = $clienteachCourseddata['mode'];
    if ($mode == '3' || $mode == '4') {
        $coursesByStatus['alpha'][] = $clienteachCourseddata;
        continue;
    }
    if ($mode == '5' || $mode == '6') {
        $coursesByStatus['beta'][] = $clienteachCourseddata;
        continue;
    }
    if ($mode == '7' || $mode == '8') {
        $coursesByStatus['gamma'][] = $clienteachCourseddata;
        continue;
    }

    $status = $clienteachCourseddata['course_status'] ?? null;
    $lesson_status = $clienteachCourseddata['lesson_status'] ?? null;
    if ($status == '2') {
        $coursesByStatus['completed'][] = $clienteachCourseddata;
    } elseif ($status == '1' || $lesson_status == 'incomplete' || $lesson_status == 'failed') {
        $coursesByStatus['in_progress'][] = $clienteachCourseddata;
    } else {
        $coursesByStatus['not_started'][] = $clienteachCourseddata;
    }
}
unset($clienteachCourseddata);

// The alpha/beta/gamma buckets above each merge two mode values (e.g. Alpha=3 and
// Alpha_2=4), so without this the query's last_updated_on-based order surfaces
// whichever course was touched most recently at the top - meaning changing a
// course's stage just jumps it around instead of settling into the Alpha -> Alpha_2
// (or Beta -> Beta_2 / Gamma -> Gamma_2) sequence. Sort each bucket by mode so the
// lower stage always lists first; PHP's usort is stable (8.0+), so courses already
// sharing a mode keep their existing relative (last-updated) order.
foreach (['alpha', 'beta', 'gamma'] as $stageGroup) {
    usort($coursesByStatus[$stageGroup], function ($a, $b) {
        return ((int) $a['mode']) <=> ((int) $b['mode']);
    });
}

$courseStatusCounts = [
    'assigned'    => count($clientCourseddata),
    'in_progress' => count($coursesByStatus['in_progress']),
    'completed'   => count($coursesByStatus['completed']),
    'not_started' => count($coursesByStatus['not_started']),
    'alpha'       => count($coursesByStatus['alpha']),
    'beta'        => count($coursesByStatus['beta']),
    'gamma'       => count($coursesByStatus['gamma']),
];
?>
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

    .summary-tile.card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06), 0 2px 6px rgba(0, 0, 0, 0.04);
    }

    .summary-tile .card-body {
        padding: 0.9rem 1rem;
    }

    .summary-tile .avatar-lg {
        border-radius: 12px;
        height: 2.5rem;
        width: 2.5rem;
    }

    .summary-tile .avatar-lg i {
        font-size: 16px !important;
    }

    .summary-tile h3 {
        font-size: 1.25rem;
        margin-bottom: 0.15rem;
    }

    .summary-tile p {
        font-size: 0.7rem;
        margin-bottom: 0;
    }

    .summary-tile.tile-clickable {
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .summary-tile.tile-clickable:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.09), 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .course-section {
        scroll-margin-top: calc(var(--ct-topbar-height, 70px) + 20px);
    }


    .summary-tile.tile-success {
        background: linear-gradient(135deg, #ffffff 0%, #e9f9f0 100%);
    }

    .summary-tile.tile-warning {
        background: linear-gradient(135deg, #ffffff 0%, #fff6e0 100%);
    }

    .summary-tile.tile-danger {
        background: linear-gradient(135deg, #ffffff 0%, #fdecec 100%);
    }

    /* The tile gradients above are light regardless of theme, and Bootstrap's .text-dark
       is a fixed dark navy-gray (not theme-adaptive), so in dark mode both need explicit
       overrides here to stay legible instead of rendering as dark-on-dark. */
    [data-bs-theme="dark"] .summary-tile.tile-success {
        background: linear-gradient(135deg, #232b36 0%, #17301f 100%);
    }

    [data-bs-theme="dark"] .summary-tile.tile-warning {
        background: linear-gradient(135deg, #232b36 0%, #3a2f10 100%);
    }

    [data-bs-theme="dark"] .summary-tile.tile-danger {
        background: linear-gradient(135deg, #232b36 0%, #3a1f22 100%);
    }

    .summary-tile.tile-purple {
        background: linear-gradient(135deg, #ffffff 0%, #efeaff 100%);
    }

    .summary-tile.tile-cyan {
        background: linear-gradient(135deg, #ffffff 0%, #e6f8fc 100%);
    }

    .summary-tile.tile-orange {
        background: linear-gradient(135deg, #ffffff 0%, #ffe9e0 100%);
    }

    [data-bs-theme="dark"] .summary-tile.tile-purple {
        background: linear-gradient(135deg, #232b36 0%, #241c44 100%);
    }

    [data-bs-theme="dark"] .summary-tile.tile-cyan {
        background: linear-gradient(135deg, #232b36 0%, #123138 100%);
    }

    [data-bs-theme="dark"] .summary-tile.tile-orange {
        background: linear-gradient(135deg, #232b36 0%, #3a2416 100%);
    }

    [data-bs-theme="dark"] .summary-tile h3.text-dark {
        color: #f3f7f9 !important;
    }

    /* Bootstrap doesn't ship an "orange" soft-color variant in this theme, so the Gamma tile
       gets its own small set matching the same orange already used for the Gamma badge elsewhere. */
    .bg-soft-orange {
        background-color: rgba(255, 112, 67, 0.15) !important;
    }

    .text-orange {
        color: #ff7043 !important;
    }

    .border-orange {
        border-color: #ff7043 !important;
    }

    .course-section-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }

    .course-section-title h5 {
        font-size: 1.15rem;
        font-weight: 700;
    }

    .course-carousel {
        display: flex;
        gap: 1rem;
        overflow-x: auto;
        overflow-y: hidden;
        padding-bottom: 0.75rem;
        scroll-snap-type: x proximity;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }

    .course-carousel::-webkit-scrollbar {
        display: none;
    }

    .course-carousel-item {
        flex: 0 0 250px;
        max-width: 250px;
        scroll-snap-align: start;
    }

    .course-carousel-nav {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .course-carousel-nav-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        border: 1px solid rgba(0, 0, 0, 0.1);
        background-color: #fff;
        color: #6c757d;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
        transition: background-color 0.15s ease, color 0.15s ease;
        /* Without this, rapidly double-clicking the button (there's no real text here, just an
           icon-font glyph) makes the browser attempt a word-selection at that point, which in
           some browsers (e.g. Edge's "mini toolbar") pops up an empty selection menu. */
        -webkit-user-select: none;
        user-select: none;
    }

    .course-carousel-nav-btn:hover {
        background-color: #ff5533;
        color: #fff;
    }

    .course-carousel-nav-btn:disabled {
        opacity: 0.4;
        cursor: default;
        pointer-events: none;
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

    .course-card-link-form {
        margin: 0;
        height: 100%;
    }

    .course-card-link-btn {
        display: flex;
        flex-direction: column;
        width: 100%;
        height: 100%;
        padding: 0;
        border: 0;
        background: none;
        cursor: pointer;
        text-align: left;
        color: inherit;
    }

    .course-thumb {
        position: relative;
        height: 150px;
        overflow: hidden;
        border: 1px solid #ccc;
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

    .course-badge-success {
        background-color: #0acf97;
    }

    .course-badge-info {
        background-color: #39afd1;
    }

    .course-badge-warning {
        background-color: #ffbc00;
        color: #543a00;
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
        padding: 0.9rem 1rem 0;
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

    document.addEventListener('DOMContentLoaded', function() {
        function updateNavButtons(track) {
            const wrapper = track.closest('.course-section');
            if (!wrapper) return;
            const prevBtn = wrapper.querySelector('.course-carousel-nav-btn[data-carousel-dir="-1"]');
            const nextBtn = wrapper.querySelector('.course-carousel-nav-btn[data-carousel-dir="1"]');
            const maxScroll = track.scrollWidth - track.clientWidth;
            if (prevBtn) prevBtn.disabled = track.scrollLeft <= 0;
            if (nextBtn) nextBtn.disabled = track.scrollLeft >= maxScroll - 1;
        }

        document.querySelectorAll('.course-carousel').forEach(function(track) {
            updateNavButtons(track);
            track.addEventListener('scroll', function() {
                updateNavButtons(track);
            });
        });

        document.querySelectorAll('.course-carousel-nav-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const track = document.getElementById(btn.dataset.carouselTarget);
                if (!track) return;
                const dir = parseInt(btn.dataset.carouselDir, 10);
                const cardWidth = track.querySelector('.course-carousel-item')?.offsetWidth || 250;
                const scrollAmount = (cardWidth + 16) * 2; // two cards + gap
                track.scrollBy({
                    left: dir * scrollAmount,
                    behavior: 'smooth'
                });
            });
        });

        document.querySelectorAll('.summary-tile[data-scroll-target]').forEach(function(tile) {
            tile.addEventListener('click', function() {
                const target = document.getElementById(tile.dataset.scrollTarget);
                if (!target) return;
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });
        });
    });
</script>
<?php $current_url = current_url(true); // Returns CodeIgniter\HTTP\URI object 
?>
<?php $segment1 = uri_string(); // Returns string like 'my_training' 
?>
<?php $current_page = explode('/', uri_string())[0]; ?>
<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<h4 class="page-title"><?= lang('UI_Text.Dashboard') ?> </h4>
		</div>
	</div>
</div>
<!-- Summary tab -->
<div class="row summary-tiles-row row-cols-2 <?= in_array(45, $arrayuserlevel) ? 'row-cols-md-3 row-cols-xl-6' : 'row-cols-md-4' ?> g-2">
    <div class="col">
        <div class="widget-rounded-circle card summary-tile tile-warning tile-clickable" data-scroll-target="course-section-in_progress">
            <div class="card-body">
                <div class="row">

                    <div class="col-6">
                        <div class="avatar-lg bg-soft-warning border-warning border">
                            <i class="fe-play font-22 avatar-title text-warning"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup"><?= (int) $courseStatusCounts['in_progress'] ?></span></h3>
                            <p class="text-muted mb-1 text-truncate"><?= lang('UI_Text.In_Progress') ?></p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->

    <div class="col">
        <div class="widget-rounded-circle card summary-tile tile-danger tile-clickable" data-scroll-target="course-section-not_started">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg bg-soft-danger border-danger border">
                            <i class="fe-alert-triangle font-22 avatar-title text-danger"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup"><?= (int) $courseStatusCounts['not_started'] ?></span></h3>
                            <p class="text-muted mb-1 text-truncate"><?= lang('UI_Text.Not_Started') ?></p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->

    <div class="col">
        <div class="widget-rounded-circle card summary-tile tile-success tile-clickable" data-scroll-target="course-section-completed">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-lg bg-soft-success border-success border">
                            <i class="fe-thumbs-up font-22 avatar-title text-success"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-end">
                            <h3 class="text-dark mt-1"><span data-plugin="counterup"><?= (int) $courseStatusCounts['completed'] ?></span></h3>
                            <p class="text-muted mb-1 text-truncate"><?= lang('UI_Text.Completed') ?></p>
                        </div>
                    </div>
                </div> <!-- end row-->
            </div>
        </div> <!-- end widget-rounded-circle-->
    </div> <!-- end col-->

    <?php if (in_array(45, $arrayuserlevel)) { ?>
        <div class="col">
            <div class="widget-rounded-circle card summary-tile tile-purple tile-clickable" data-scroll-target="course-section-alpha">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg bg-soft-primary border-primary border">
                                <i class="fe-zap font-22 avatar-title text-primary"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup"><?= (int) $courseStatusCounts['alpha'] ?></span></h3>
                                <p class="text-muted mb-1 text-truncate"><?= lang('UI_Text.Alpha') ?></p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div>
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->

        <div class="col">
            <div class="widget-rounded-circle card summary-tile tile-cyan tile-clickable" data-scroll-target="course-section-beta">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg bg-soft-info border-info border">
                                <i class="fe-layers font-22 avatar-title text-info"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup"><?= (int) $courseStatusCounts['beta'] ?></span></h3>
                                <p class="text-muted mb-1 text-truncate"><?= lang('UI_Text.Beta') ?></p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div>
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->

        <div class="col">
            <div class="widget-rounded-circle card summary-tile tile-orange tile-clickable" data-scroll-target="course-section-gamma">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg bg-soft-orange border-orange border">
                                <i class="fe-award font-22 avatar-title text-orange"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup"><?= (int) $courseStatusCounts['gamma'] ?></span></h3>
                                <p class="text-muted mb-1 text-truncate"><?= lang('UI_Text.Gamma') ?></p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div>
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->
    <?php } else { ?>
        <div class="col">
            <div class="widget-rounded-circle card summary-tile tile-purple tile-clickable" data-scroll-target="course-section-favorites">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-lg bg-soft-primary border-primary border">
                                <i class="fe-heart font-22 avatar-title text-primary"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <h3 class="text-dark mt-1"><span data-plugin="counterup"><?= (int) count($favoriteCourseddata) ?></span></h3>
                                <p class="text-muted mb-1 text-truncate"><?= lang('Buttons.Favorites') ?></p>
                            </div>
                        </div>
                    </div> <!-- end row-->
                </div>
            </div> <!-- end widget-rounded-circle-->
        </div> <!-- end col-->
    <?php } ?>
</div>

<!-- Summary tab end -->
<?php
// Render a single course card; reused across every carousel row below
// (assigned-course status rows as well as the Favourites row).
$renderCourseCard = function ($clienteachCourseddata) {
    $course_name = $clienteachCourseddata['course_name'];

    $max_length = 39; // Maximum length of the string

    if (strlen($course_name) > $max_length) {
        $shortened_name = substr($course_name, 0, $max_length) . ".."; // Append "..." for indication
    } else {
        $shortened_name = $course_name;
    }

    if (isset($clienteachCourseddata['thumbnail']) && $clienteachCourseddata['thumbnail'] != '') {
        $thumbnail = base_url('assets/assets/uploads/SCORM_course_thumbnail/' . $clienteachCourseddata['scourse_id'] . '/' . $clienteachCourseddata['thumbnail']);
    } else {
        $thumbnail = base_url('public/aristo_assets/images/default_thumbnail.png');
    }

    // Draft modes (Development/Alpha/Beta/Gamma) still show a badge on the thumbnail.
    // The progress status (Completed/In Progress/Not Started) is no longer badged here
    // since the carousel section headings already convey it.
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
    <div class="course-carousel-item">
        <div class="card h-100 course-card">
            <form class="course-card-link-form" action="<?php echo base_url('my_training/read_more') ?>" method="POST"><?= csrf_field() ?>
                <input type="hidden" name="crid" value="<?php echo $clienteachCourseddata['scourse_id'] ?>">
                <input type="hidden" name="detail_type" value="1">
                <input type="hidden" name="tab" value="1">
                <button type="submit" class="course-card-link-btn">
                    <div class="course-thumb">
                        <img src="<?= $thumbnail ?>" alt="<?php echo $clienteachCourseddata['course_name'] ?>" loading="lazy" decoding="async">

                        <span class="course-play-btn"><i class="mdi mdi-play"></i></span>
                    </div>
                    <div class="card-body course-card-body">
                        <h5 class="course-card-title" title="<?php echo $course_name; ?>"><?php echo $shortened_name ?></h5>
                        <div class="d-flex flex-column gap-1">
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
                            <?php if (!empty($clienteachCourseddata['language'])) { ?>
                                <span class="course-card-duration">
                                    <i class="mdi mdi-web"></i>
                                    <?php echo lang('UI_Text.Language'); ?>: <?php echo esc($clienteachCourseddata['language']); ?>
                                </span>
                            <?php } ?>
                        </div>
                    </div>
                </button>
            </form>
        </div>
    </div>
<?php
};

// $coursesByStatus was already grouped near the top of this file (shared with the summary tiles).
$statusSections = [
    'in_progress' => lang('UI_Text.In Progress'),
    'not_started' => lang('UI_Text.Not Started'),
    'completed'   => lang('UI_Text.Completed'),
    'alpha'       => lang('UI_Text.Alpha'),
    'beta'        => lang('UI_Text.Beta'),
    'gamma'       => lang('UI_Text.Gamma'),
];

// Same icon/color pairing as the summary tiles above, so each section title matches its counterpart in the top bar.
$statusIcons = [
    'in_progress' => ['icon' => 'fe-play', 'color' => 'text-warning'],
    'not_started' => ['icon' => 'fe-alert-triangle', 'color' => 'text-danger'],
    'completed'   => ['icon' => 'fe-thumbs-up', 'color' => 'text-success'],
    'alpha'       => ['icon' => 'fe-zap', 'color' => 'text-primary'],
    'beta'        => ['icon' => 'fe-layers', 'color' => 'text-info'],
    'gamma'       => ['icon' => 'fe-award', 'color' => 'text-primary'],
];
?>

<?php if (count($clientCourseddata) > 0): ?>
    <?php foreach ($statusSections as $statusKey => $statusLabel):
        $sectionCourses = $coursesByStatus[$statusKey];
        if (empty($sectionCourses)) {
            continue;
        }
    ?>
        <div class="course-section mb-4" id="course-section-<?= $statusKey ?>">
            <div class="course-section-title">
                <h5 class="mb-0"><i class="<?= $statusIcons[$statusKey]['icon'] ?> <?= $statusIcons[$statusKey]['color'] ?> me-1"></i> <?= esc($statusLabel) ?> <span class="text-muted font-13">(<?= count($sectionCourses) ?>)</span></h5>
                <div class="course-carousel-nav">
                    <button type="button" class="course-carousel-nav-btn" data-carousel-target="course-carousel-<?= $statusKey ?>" data-carousel-dir="-1" aria-label="<?= esc(lang('UI_Text.Previous')) ?>">
                        <i class="mdi mdi-chevron-left"></i>
                    </button>
                    <button type="button" class="course-carousel-nav-btn" data-carousel-target="course-carousel-<?= $statusKey ?>" data-carousel-dir="1" aria-label="<?= esc(lang('UI_Text.Next')) ?>">
                        <i class="mdi mdi-chevron-right"></i>
                    </button>
                    <a href="<?php echo base_url('SCORM/scorm_courses') ?>" class="font-13 ms-2"><?= lang('UI_Text.View_All') ?></a>
                </div>
            </div>
            <div id="course-carousel-<?= $statusKey ?>" class="course-carousel">
                <?php foreach (array_slice($sectionCourses, 0, 12) as $clienteachCourseddata) {
                    $renderCourseCard($clienteachCourseddata);
                } ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (!empty($favoriteCourseddata)): ?>
    <div class="course-section mb-4" id="course-section-favorites">
        <div class="course-section-title">
            <h5 class="mb-0"><i class="fe-heart text-danger me-1"></i> <?= esc(lang('Buttons.Favorites')) ?> <span class="text-muted font-13">(<?= count($favoriteCourseddata) ?>)</span></h5>
            <div class="course-carousel-nav">
                <button type="button" class="course-carousel-nav-btn" data-carousel-target="course-carousel-favorites" data-carousel-dir="-1" aria-label="<?= esc(lang('UI_Text.Previous')) ?>">
                    <i class="mdi mdi-chevron-left"></i>
                </button>
                <button type="button" class="course-carousel-nav-btn" data-carousel-target="course-carousel-favorites" data-carousel-dir="1" aria-label="<?= esc(lang('UI_Text.Next')) ?>">
                    <i class="mdi mdi-chevron-right"></i>
                </button>
                <a href="<?php echo base_url('my_training/Favorites') ?>" class="font-13 ms-2"><?= lang('UI_Text.View_All') ?></a>
            </div>
        </div>
        <div id="course-carousel-favorites" class="course-carousel">
            <?php foreach (array_slice($favoriteCourseddata, 0, 12) as $clienteachCourseddata) {
                $renderCourseCard($clienteachCourseddata);
            } ?>
        </div>
    </div>
<?php endif; ?>

</div>