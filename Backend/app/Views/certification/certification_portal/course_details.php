<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Course Details — An Introduction to Amazon Web Services (AWS)</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/assets/certification/course-details.css') ?>">
</head>

<body>

  <!-- <div class="page-wrapper"> -->

  <!-- Back Button -->
  <!-- <a href="<?php echo base_url('Certification/Certification_Portal/certificationDetails'); ?>" class="btn-back">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="15 18 9 12 15 6" />
      </svg>
      Back
    </a> -->
  <div class="row">
    <div class="col-12">
      <div class="page-title-box">
        <div class="page-title-right">
          <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?php echo base_url('Certification/Certification_Portal/certificationDetails'); ?>">Back</a></li>
          </ol>
        </div>
        <h4 class="page-title"><?= lang('UI_Text.Course Details') ?></h4>
      </div>
    </div>
  </div>

  <!-- Main Card -->
  <div class="course-card">

    <!-- LEFT — Image + Launch -->
    <div class="course-left">
      <div class="course-image-wrap">
        <img
          src="<?php echo base_url('assets/assets/uploads/SCORM_course_thumbnail/' . $clientCourseddata[0]['scourse_id'] . '/' . (isset($clientCourseddata[0]['thumbnail']) ? $clientCourseddata[0]['thumbnail'] : 'default_thumbnail.jpg')); ?>"
          alt="An Introduction to Amazon Web Services (AWS)"
          class="course-image">
      </div>

      <div class="launch-section">
        <?php if ($isPurchased) { ?>


          <?php if ($clientCourseddata[0]['type'] == '10') { ?>

            <a onclick="OpenNewWindow('<?php echo base_url('SCORM/scorm_launch'); ?>')">
              <button type="button"
                class="btn btn-outline-success waves-effect btn-sm waves-light mb-3">
                <span class="btn-label">
                  <i class="icon-control-play"></i>
                </span>
                <?= lang('Buttons.Launch') ?>
              </button>
            </a>

          <?php } else { ?>

            <?php if (isset($pagedata[0]['page_id'])) { ?>

              <form method="POST" onsubmit="return LaunchCourse(this)">
                <?= csrf_field() ?>

                <input type="hidden" name="crid"
                  value="<?= $clientCourseddata[0]['scourse_id'] ?>">

                <input type="hidden"
                  name="certificate_id"
                  value="<?= $certificate_id ?>">

                <input type="hidden"
                  name="detail_type"
                  value="13">

                <input type="hidden"
                  name="mp_id"
                  value="0">

                <button type="submit"
                  class="btn btn-outline-success waves-effect btn-sm waves-light mb-3">

                  <i class="mdi mdi-play-circle-outline me-2"></i>
                  Launch

                </button>

              </form>

            <?php } else { ?>

              <div class="persistent-warning">
                <div class="danger-text">
                  This course cannot be launched because no content has been added yet.
                </div>
              </div>

            <?php } ?>

          <?php } ?>

        <?php } else { ?>

          <a href="<?= base_url('Certification/Certification_Portal') ?>"
            class="btn btn-outline-danger waves-effect btn-sm waves-light mb-3">
            Purchase Certification to Launch
          </a>

        <?php } ?>
      </div>

    </div><!-- /.course-left -->

    <!-- RIGHT — Details -->
    <div class="course-right">

      <h1 class="course-title"><?php echo isset($clientCourseddata[0]['course_name']) ? $clientCourseddata[0]['course_name'] : 'Course Name'; ?></h1>

      <!-- Category rows -->
      <div class="info-row">
        <span class="info-label"><?= lang('UI_Text.Course Status') ?> :</span>
        <span class="info-value"> <?php
                                  $course_status = $clientCourseddata[0]['mode'];
                                  $lesson_status = $clientCourseddata[0]['lesson_status'] ?? '';
                                  if ($course_status == 2) {

                                    if (isset($clientCourseddata[0]['course_status'])) {
                                      if ($clientCourseddata[0]['course_status'] == '2') {
                                        echo '<span class="badge bg-success ms-2">' . lang('UI_Text.Completed') . '</span>';
                                      } elseif ($clientCourseddata[0]['course_status'] == '1' || $clientCourseddata[0]['lesson_status'] == 'incomplete' || $clientCourseddata[0]['lesson_status'] == 'failed') {
                                        echo '<span class="badge bg-info ms-2">' . lang('UI_Text.In Progress') . '</span>';
                                      } else {
                                        echo '<span class="badge bg-warning ms-2">' . lang('UI_Text.Not Started') . '</span>';
                                      }
                                    }
                                  } else {

                                    $labels = [
                                      '1' => ['Development', 'danger'],
                                      '3' => ['Alpha', 'warning'],
                                      '4' => ['Alpha 2', 'info'],
                                      '5' => ['Beta', 'warning'],
                                      '6' => ['Beta 2', 'info'],
                                      '7' => ['Gamma', 'warning'],
                                      '8' => ['Gamma 2', 'info'],
                                    ];

                                    if (isset($labels[$course_status])) {
                                      echo '<span class="badge bg-' . $labels[$course_status][1] . ' ms-2">' .
                                        $labels[$course_status][0] . '</span>';
                                    }
                                  }
                                  ?></span>
      </div>
      <div class="info-row">
        <span class="info-label"><?= lang('UI_Text.Attempts') ?>:</span>
        <span class="info-value"> <?= isset($clientCourseddata[0]['attempt']) && $clientCourseddata[0]['attempt'] > 0 ? $clientCourseddata[0]['attempt'] : 'N/A'; ?>
        </span>
      </div>

      <!-- Language & Duration chips -->
      <div class="meta-pills">
        <div class="pill">
          <span class="pill-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10" />
              <line x1="2" y1="12" x2="22" y2="12" />
              <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
            </svg>
          </span>
          <span class="pill-label">Language</span>
          <span class="pill-value"><?php echo isset($clientCourseddata[0]['language']) ? $clientCourseddata[0]['language'] : 'Language'; ?></span>
        </div>
        <div class="pill">
          <span class="pill-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10" />
              <polyline points="12 6 12 12 16 14" />
            </svg>
          </span>
          <span class="pill-label">Duration</span>
          <span class="pill-value"> <?= isset($clientCourseddata[0]['duration']) ? $clientCourseddata[0]['duration'] . ' min' : 'N/A'; ?>
          </span>
        </div>
      </div>
      <?php if (!empty($clientCourseddata[0]['description']) || !empty($getAllObjectives)) { ?>

        <!-- Divider -->
        <hr class="divider">

        <!-- Description -->
        <div class="course-desc">
          <p><?= $clientCourseddata[0]['description'] ?? ''; ?></p>
          </p>
        </div>

        <!-- Learning Outcomes -->
        <p class="outcomes-heading"><?= lang('UI_Text.end_of_course') ?></p>
        <ul class="outcomes-list">
          <?php foreach ($getAllObjectives as $obj) { ?>
            <li><?= $obj['objective']; ?></li>
          <?php } ?>
        </ul>
      <?php } ?>
    </div><!-- /.course-right -->

  </div><!-- /.course-card -->



</body>

</html>
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