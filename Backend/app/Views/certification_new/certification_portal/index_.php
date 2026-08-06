<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Professional Certifications</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/assets/certification/certification-list.css') ?>">
</head>



<!-- ═══════════════════════════════════════════════
       CERTIFICATION GRID
  ═══════════════════════════════════════════════ -->
<?php if (!empty($get_all_certificates)) { ?>
  <main class="main-content">
    <div class="container">
      <div class="cert-grid">
        <?php
        $j = 0;
        foreach ($get_all_certificates as $all_cert) {
          $j++;
          $type = $all_cert['type'];
          $typeval = '';
          switch ($type) {
            case 4:
              $typeval = lang('UI_Text.Certification');
              break;
            case 3:
              $typeval = lang('UI_Text.Courses');
              break;
            case 2:
              $typeval = lang('UI_Text.Learning_Plan');
              break;
            case 1:
              $typeval = lang('UI_Text.Marketplace');
              break;
          }
          $Assigntypeval = '';
          switch ($type) {
            case 4:
              $Assigntypeval = lang('UI_Text.Learning_Plan');
              break;
            case 3:
              $Assigntypeval = lang('UI_Text.Courses');
              break;
            case 2:
              $Assigntypeval = lang('UI_Text.Learning_Plan');
              break;
            case 1:
              $Assigntypeval = lang('UI_Text.Marketplace');
              break;
          }
        ?>
          <!-- ── Card 1 ── -->
          <article class="cert-card">
            <div class="cert-card__thumb">
              <img src="<?= base_url('assets/assets/certification/assets/images/'.$all_cert['cert_id'].'.jpg') ?>" alt="<?= $all_cert['name'] ?>" class="cert-card__img">
            </div>
            <div class="cert-card__body">  
              <h2 class="cert-card__title"><?php echo $all_cert['name']; ?></h2>
              <p class="cert-card__desc"><?php echo $all_cert['description']; ?></p>
              <div class="cert-card__price">₹ <?= number_format($all_cert['amount'], 0) ?></div>
              <div class="cert-card__meta">
                <div class="cert-card__meta-item">
                  <span class="meta-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="12" cy="12" r="10" />
                      <polyline points="12 6 12 12 16 14" />
                    </svg>
                  </span>
                  <span>
                    <?php
                    $duration = $all_cert['duration'];
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

                    ?>


                  </span>
                </div>
                <div class="cert-card__meta-item">
                  <span class="meta-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <rect x="2" y="3" width="20" height="14" rx="2" />
                      <line x1="8" y1="21" x2="16" y2="21" />
                      <line x1="12" y1="17" x2="12" y2="21" />
                    </svg>
                  </span>
                  <span><?= $all_cert['course_count']; ?> Courses</span>
                </div>
                <div class="cert-card__meta-item">
                  <span class="meta-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <polygon points="12 2 2 7 12 12 22 7 12 2" />
                      <polyline points="2 17 12 22 22 17" />
                      <polyline points="2 12 12 17 22 12" />
                    </svg>
                  </span>
                  <span><?= $all_cert['learning_plan_count']; ?> Learning Plan</span>
                </div>
              </div>
              <div class="cert-card__actions">
                <form action="<?= base_url('Certification/Certification_Portal/certificationDetails') ?>" method="post"> <?= csrf_field() ?>
                  <input type="hidden" name="certificate_id" value="<?= $all_cert['cert_id']; ?>">
                  <input type="hidden" name="cert_name" value="<?= $all_cert['name']; ?>">

                  <button type="submit" class="btn btn--outline">
                    View Details
                  </button>
                </form>
                <?php
                $allCompleted = (
                  $all_cert['course_count'] > 0 &&
                  $all_cert['completed_course_count'] >= $all_cert['course_count']
                );

                $passedAssessment = ($all_cert['assessment_score'] >= 80);
                ?>

                <?php if (in_array($all_cert['cert_id'], $purchasedCertificates)) : ?>

                  <?php if (!$allCompleted) : ?>

                    <!-- Courses not completed -->
                    <button
                      type="button"
                      class="btn btn--success"
                      disabled>
                      Take Assessment
                    </button>

                  <?php elseif (!$passedAssessment) : ?>

                    <!-- Courses completed, assessment not passed -->
                    <button
                      type="button"
                      class="btn btn-success"
                      onclick="OpenNewWindow('<?= base_url('Certification/Certification_Portal/assessmentLauncher/' . $all_cert['cert_id']) ?>')">
                      Take Assessment
                    </button>

                  <?php else : ?>

                    <!-- Assessment passed -->
                    <!-- <a href="<?= base_url('Certification/Certification_dashboard/download_certificate/' . $all_cert['cert_id']) ?>"
                      class="btn btn--primary">
                      Download Certificate
                    </a> -->
                    <form action="<?= base_url('Certification/Dashboard/view_certificate'); ?>"
                      method="post"
                      target="_blank"
                      style="display:inline; width:100%;">

                      <?= csrf_field(); ?>

                      <input type="hidden" name="course_mp_id" value="<?= $all_cert['cert_id'] ?>">
                      <input type="hidden" name="type" value="4">

                      <button
                        type="submit"
                        class="btn btn-primary"
                        style="width:100%;">
                        <?= lang('Buttons.View Certificate'); ?>
                      </button>

                    </form>

                  <?php endif; ?>

                <?php else : ?>

                  <!-- Not purchased -->
                  <form action="<?= base_url('Certification/Certification_Portal/buyNowDetails') ?>" method="post">
                    <?= csrf_field() ?>

                    <input type="hidden" name="certificate_id" value="<?= $all_cert['cert_id']; ?>">
                    <input type="hidden" name="cert_name" value="<?= $all_cert['name']; ?>">

                    <button type="submit" class="btn btn-primary">
                      Buy Now
                    </button>
                  </form>

                <?php endif; ?>
                <!-- <a href="#" class="btn btn--primary">Buy Now</a> -->
              </div>
            </div>
          </article>
        <?php } ?>
      </div><!-- /.cert-grid -->
    </div><!-- /.container -->
  </main>
<?php } else { ?>
  <main class="main-content">
    <div class="container">
      <div class="cert-grid">
        <p>No certifications available.</p>
      </div>
    </div>
  </main>
<?php } ?>
<script type="text/javascript">
  function LaunchCourse(form) {
    console.log("case");
    params = 'width=' + screen.width;
    params += ', height=' + screen.height;
    params += ', top=0, left=0'
    params += ', fullscreen=yes';
    params += ', directories=no';
    params += ', location=no';
    params += ', menubar=no';
    params += ', resizable=no';
    params += ', scrollbars=no';
    params += ', status=no';
    params += ', toolbar=no';
    <?php $page_number = isset($pagedata[0]['page_number']) ? $pagedata[0]['page_number'] : '1'; ?>
    <?php $_SESSION['course_detail_launch'] = 1; ?>
    MyPath = '<?php echo base_url('SCORM/course_builder/review_course/launcher/1/' . $page_number); ?>';
    newwin = window.open(MyPath, "Launcher", params);
    var win_timer = setInterval(function() {
      if (newwin.closed) {
        window.location.reload();
        clearInterval(win_timer);
      }
    }, 100);
    if (window.focus) {
      newwin.focus();
    }
    return false;
  }


  function OpenNewWindow(MyPath) {
    params = 'width=' + screen.width;
    params += ', height=' + screen.height;
    params += ', top=0, left=0'
    params += ', fullscreen=yes';
    params += ', directories=no';
    params += ', location=no';
    params += ', menubar=no';
    params += ', resizable=no';
    params += ', scrollbars=no';
    params += ', status=no';
    params += ', toolbar=no';

    newwin = window.open(MyPath, "Launcher", params);
    var win_timer = setInterval(function() {
      if (newwin.closed) {
        window.location.reload();
        clearInterval(win_timer);
      }
    }, 100);
    if (window.focus) {
      newwin.focus();
    }
    return false;
  }

  function OpenNewWindowmiddle(MyPath) {
    var screenWidth = screen.width;
    var screenHeight = screen.height;
    var windowWidth = 800;
    var windowHeight = 450;
    var left = Math.floor((windowWidth) / 2);
    var top = Math.floor((windowHeight) / 2);
    var params = 'width=' + windowWidth;
    params += ', height=' + windowHeight;
    params += ', left=' + left;
    params += ', top=' + top;
    params += ', fullscreen=no';
    params += ', directories=no';
    params += ', location=no';
    params += ', menubar=no';
    params += ', resizable=no';
    params += ', scrollbars=no';
    params += ', status=no';
    params += ', toolbar=no';

    newwin = window.open(MyPath, "Launcher", params);
    var win_timer = setInterval(function() {
      if (newwin.closed) {
        window.location.reload();
        clearInterval(win_timer);
      }
    }, 100);
    if (window.focus) {
      newwin.focus();
    }
    return false;
  }
</script>