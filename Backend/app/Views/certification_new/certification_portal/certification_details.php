<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Certification Details — CCD-Tech Certification</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/assets/certification/certification-details.css') ?>">
</head>

<body>

  <!-- ═══════════════════════════════════════════════
       GRADIENT HEADER WITH BACK BUTTON
  ═══════════════════════════════════════════════ -->
  <!-- <header class="cert-header">
    <div class="container">
      <a href="<?php echo base_url('Certification/Certification_Portal'); ?>" class="btn-back">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="15 18 9 12 15 6" />
        </svg>
        Back to Certifications
      </a>
    </div>
  </header> -->
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title-right">
          <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?php echo base_url('Certification/Certification_Portal'); ?>">Certifications</a></li>
          </ol>
        </div>
        <h4 class="page-title"><?php echo $certification[0]['name']; ?></h4>
      </div>
    </div>
  </div>

  <!-- ═══════════════════════════════════════════════
       MAIN CONTENT AREA
  ═══════════════════════════════════════════════ -->
  <main class="main-content">
    <div class="container">
      <div class="cert-layout">

        <!-- ─────────────────────────────────────────
             LEFT SIDEBAR
        ───────────────────────────────────────── -->
        <aside class="cert-sidebar">

          <!-- Icon Card -->
          <div class="cert-icon-card">
            <div class="cert-icon-card__icon-wrap">
              <!-- Cloud icon representing CCD-Tech Certification -->
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z" />
              </svg>
            </div>
            <h2 class="cert-icon-card__title"><?php echo $certification[0]['name']; ?></h2>
          </div>

          <!-- Info Card -->
          <div class="info-card">
            <div class="info-row">
              <span class="info-row__label">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10" />
                  <polyline points="12 6 12 12 16 14" />
                </svg>
                DURATION
              </span>
              <span class="info-row__value"> <?php
                                              $duration = $certification[0]['duration'];
                                              if ($duration > 60) {
                                                $hours = intdiv($duration, 60);
                                                echo $hours . ' Hrs ';
                                                $balancemin = $duration - $hours * 60;
                                                if ($balancemin > 0) {
                                                  echo $balancemin . ' min';
                                                }
                                              } else {
                                                echo $duration . ' min';
                                              }

                                              ?></span>
            </div>
            <div class="info-row">
              <span class="info-row__label">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polygon points="12 2 2 7 12 12 22 7 12 2" />
                  <polyline points="2 17 12 22 22 17" />
                  <polyline points="2 12 12 17 22 12" />
                </svg>
                Learning Plan
              </span>
              <span class="info-row__value"><?php echo count($get_certification_learning_plan_courses); ?></span>
            </div>
            <div class="info-row">
              <span class="info-row__label">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="2" y="3" width="20" height="14" rx="2" />
                  <line x1="8" y1="21" x2="16" y2="21" />
                  <line x1="12" y1="17" x2="12" y2="21" />
                </svg>
                TOTAL COURSES
              </span>
              <span class="info-row__value"><?php echo $totalCourses; ?></span>
            </div>
            <?php if ($isPurchased) {
            } else { ?>
              <div class="info-row info-row--last">
                <span class="info-row__label">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <rect x="1" y="4" width="22" height="16" rx="2"></rect>
                    <line x1="1" y1="10" x2="23" y2="10"></line>
                  </svg>
                  PRICE
                </span>
                <span class="info-row__value info-row__value--price">₹ <?php echo  isset($get_certification_learning_plan_courses[0]['amount']) ? number_format($get_certification_learning_plan_courses[0]['amount'], 0) : ''; ?></span>
              </div>
            <?php } ?>
          </div>

          <!-- Progress Card -->
          <div class="progress-card">
            <div class="progress-card__header">
              <span class="progress-card__label">YOUR PROGRESS</span>
              <span class="progress-card__pct"> <?= $progressPercent ?>%</span>
            </div>
            <div class="progress-bar" role="progressbar" aria-valuenow="<?= $progressPercent ?>" aria-valuemin="0" aria-valuemax="100">
              <div class="progress-bar__fill" style="width: <?= $progressPercent ?>%"></div>
            </div>
            <div class="progress-stats">
              <div class="progress-stat">
                <span class="progress-stat__num"><?= $completedCourses ?> / <?= $totalCourses ?></span>
                <span class="progress-stat__lbl">COURSES COMPLETED</span>
              </div>
              <div class="progress-stat">
                <span class="progress-stat__num"> <?= $completedPlans ?> / <?= $totalPlans ?></span>
                <span class="progress-stat__lbl">PATHS COMPLETED</span>
              </div>
            </div>
            <!-- <div class="next-up">
              <span class="next-up__label">NEXT UP</span>
              <p class="next-up__title">Relationship Between Data Protection and Privacy</p>
            </div> -->
          </div>

        </aside><!-- /.cert-sidebar -->

        <!-- ─────────────────────────────────────────
             RIGHT CONTENT — CSS-ONLY TABS
             Radio inputs MUST precede nav & panels
             for the general sibling combinator (~)
        ───────────────────────────────────────── -->
        <?php if (!empty($get_certification_learning_plan_courses)) { ?>



          <section class="cert-content">

            <!-- DYNAMIC TAB CSS — one rule pair per Learning Plan.
                 Generated from the same loop as the tabs/panels below, so it
                 always matches however many plans exist (no hardcoded count). -->
            <style>
              <?php $i = 1;
              foreach ($get_certification_learning_plan_courses as $plan) { ?>
              #path-tab-<?php echo $i; ?>:checked~.paths-panels .path-panel--<?php echo $i; ?> {
                display: block;
              }

              #path-tab-<?php echo $i; ?>:checked~.paths-nav label[for="path-tab-<?php echo $i; ?>"] {
                background: var(--success);
                border-color: var(--success);
                color: var(--white);
              }
              <?php $i++;
              } ?>
            </style>

            <!-- RADIOS (ONLY ONCE) -->
            <?php $i = 1;
            foreach ($get_certification_learning_plan_courses as $plan) { ?>
              <input type="radio"
                name="learning-path"
                id="path-tab-<?php echo $i; ?>"
                class="path-radio"
                <?php echo $i === 1 ? 'checked' : ''; ?>>
            <?php $i++;
            } ?>

            <!-- NAV -->
            <nav class="paths-nav" aria-label="Learning Plan">
              <?php $i = 1;
              foreach ($get_certification_learning_plan_courses as $plan) { ?>
                <label for="path-tab-<?php echo $i; ?>" class="paths-nav__item">
                  <span class="paths-nav__badge"><?php echo $i; ?></span>
                  <?php echo $plan['mp_name']; ?>
                </label>
              <?php $i++;
              } ?>
            </nav>

            <!-- PANELS -->
            <div class="paths-panels">
              <?php $i = 1;
              foreach ($get_certification_learning_plan_courses as $plan) { ?>
                <div class="path-panel path-panel--<?php echo $i; ?>">

                  <div class="cert-banner">
                    <?php if (!empty($plan['Banner'])) {
                      $banner = base_url(
                        'assets/assets/uploads/learning_banner_path/' .
                          $plan['mp_id'] . '/' .
                          $plan['banner']
                      ); ?>
                      <img src="<?php echo $banner; ?>" alt="<?php echo $plan['mp_name']; ?> banner">
                    <?php    } else { ?>
                      <img src="<?php echo base_url('assets/assets/img/default_learning_plan_banner.jpg'); ?>" alt="Default thumbnail">
                    <?php } ?>
                    <!-- <img src="https://dochek.com/assets/assets/img/default_learning_plan_banner.jpg"> -->
                  </div>

                  <div class="path-info">
                    <h3 class="path-info__title"><?php echo $plan['mp_name']; ?></h3>
                    <p class="path-info__desc"><?php echo $plan['description'] ?? ''; ?></p>
                  </div>

                  <div class="courses-list-section">
                    <h4 class="courses-list-heading">COURSES INCLUDED</h4>

                    <div class="courses-list">
                      <?php foreach ($plan['courses'] as $course) {
                        // print_r($course); 
                      ?>
                        <article class="course-item">
                          <span class="course-item__check" aria-hidden="true">

                            <?php if ($course['completed']) { ?>

                              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12" />
                              </svg>

                            <?php } else { ?>

                              <svg xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2">
                                <polyline points="9 18 15 12 9 6" />
                              </svg>

                            <?php } ?>
                          </span>
                          <div class="course-item__info">
                            <!-- <a href="<?php echo base_url('course-details?id=' . $course['scourse_id']); ?>" class="course-item__title"><?php echo $course['course_name']; ?></a> -->
                            <form action="<?= base_url('Certification/Certification_Portal/courseDetails'); ?>" method="post" class="course-item__title-form">
                              <input type="hidden" name="crid" value="<?= $course['scourse_id']; ?>">
                              <input type="hidden" name="detail_type" value="13">
                              <input type="hidden" name="mp_id" value="<?= $plan['mp_id']; ?>">
                              <input type="hidden" name="tab" value="1">

                              <button type="submit" class="course-item__title">
                                <?= $course['course_name']; ?>
                              </button>
                            </form>
                            </form>
                            <span class="course-item__meta">
                              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                              </svg>
                              <?php echo $course['course_duration']; ?> min
                            </span>
                          </div>
                          <!-- <a href="<?php echo base_url('course-details?id=' . $course['scourse_id']); ?>" class="course-item__arrow" aria-label="Go to course">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                              <polyline points="9 18 15 12 9 6" />
                            </svg>
                          </a> -->
                          <form action="<?= base_url('Certification/Certification_Portal/courseDetails'); ?>" method="post" class="course-item__arrow-form">
                            <input type="hidden" name="crid" value="<?= $course['scourse_id']; ?>">
                            <input type="hidden" name="certificate_id" value="<?= $certificate_id; ?>">
                            <input type="hidden" name="detail_type" value="13">
                            <input type="hidden" name="mp_id" value="<?= $plan['mp_id']; ?>">
                            <input type="hidden" name="tab" value="1">

                            <button type="submit" class="course-item__arrow" aria-label="Go to course">
                              <svg xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6" />
                              </svg>
                            </button>
                          </form>
                        </article>
                      <?php } ?>
                    </div>
                  </div>

                </div>
              <?php $i++;
              } ?>
            </div>

            <!-- CTA (OUTSIDE PANELS) -->
            <footer class="paths-cta">
              <?php if ($isPurchased) {
              } else { ?>
                <div class="paths-cta__enrolled">
                  <span class="paths-cta__enrolled-label">ENROLLED FOR</span>
                  <span class="paths-cta__enrolled-price">₹ <?php echo  isset($get_certification_learning_plan_courses[0]['amount']) ? number_format($get_certification_learning_plan_courses[0]['amount'], 0) : ''; ?></span>
                </div>
              <?php } ?>


              <!-- <a href="#" class="btn btn--continue">
                Continue Learning
              </a> -->
            </footer>

          </section>
        <?php } ?>
      </div><!-- /.cert-layout -->
    </div><!-- /.container -->
  </main>

</body>

</html>