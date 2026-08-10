<?php
// print_r($coursedetails[0]['theme']);
// exit();
if (isset($coursedetails)) {
    if ($coursedetails[0]['theme'] == '1') {
        $theme = 'Default';
    } elseif ($coursedetails[0]['theme'] == '2') {
        $theme = 'ContentforU';
    } elseif ($coursedetails[0]['theme'] == '3') {
        $theme = 'Wabtec';
    } elseif ($coursedetails[0]['theme'] == '4') {
        $theme = 'Knowledge_Works';
    } elseif ($coursedetails[0]['theme'] == '5') {
        $theme = 'WabtecArabic';
    } elseif ($coursedetails[0]['theme'] == '6') {
        $theme = 'WabtecTheme';
    } elseif ($coursedetails[0]['theme'] == '7') {
        $theme = 'Vertical_ContentforU';
    } elseif ($coursedetails[0]['theme'] == '8') {
        $theme = 'ModernTheme';
    } else {
        $theme = 'Default';
    }
} else {
    $theme = 'Default';
}
?>
<!DOCTYPE html>
<html lang="en" data-layout="horizontal" data-topbar-color="light">

<head>
    <meta charset="utf-8" />
    <title>DoChek</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="DoChek - an essential tool for Learning Management" name="description" />
    <meta content="DoChek" name="author" />
    <link rel="shortcut icon" href="<?php echo base_url(); ?>public/Landing/images/favicon.ico">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/custom.css" rel="stylesheet">
    <?php if ($theme !== 'ModernTheme'): ?>
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/Color.css" rel="stylesheet">
    <?php endif; ?>
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/content.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/footer.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/mobile.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/toc.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/<?php echo ($theme === 'ModernTheme') ? 'certificate.css' : 'certification.css'; ?>" rel="stylesheet">
    <?php if ($theme === 'ModernTheme'): ?>
    <?php /* Color.css is this theme's authoritative override layer (mobile.css's own
             header comment states it expects Color.css to load after it). Loading it
             3rd let content.css's `.headingArea1 { z-index: 1002 }` override
             Color.css's 1006, so the opaque-white `.headingArea` (same z-index, later
             in DOM) painted over the menu button and hid it. Loading it last restores
             the cascade the theme was authored for. */ ?>
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/Color.css" rel="stylesheet">
    <?php endif; ?>
    <script src="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/lib/angular-1.5.8/angular.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/lib/angular-1.5.8/angular-route.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/lib/angular-1.5.8/angular-sanitize.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/lib/radialIndicator.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/lib/angular.radialIndicator.js"></script>
    <script src="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/lib/jquery-3.1.1.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/lib/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/lib/preloadjs/assets/src/common/Proxy.js"></script>
    <script src="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/lib/preloadjs/assets/src/common/Extend.js"></script>
    <script src="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/lib/preloadjs/assets/src/common/EventDispatcher.js"></script>
    <script src="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/lib/preloadjs/assets/src/SoundInstance.js"></script>
    <script src="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/lib/preloadjs/assets/src/Preloadjs.js"></script>
    <script src="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/lib/preloadjs/assets/src/Soundjs.js"></script>
    <script src="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/lib/createjs-2015.11.26.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/scripts/service/globalSettingService.js"></script>
    <script src="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/scripts/service/globalVariableService.js"></script>
    <script src="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/scripts/controller/sideBarController.js"></script>
    <script src="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/scripts/controller/footerBarController.js"></script>
    <script src="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/scripts/controller/contentController.js"></script>
    <script src="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/scripts/controller/certificateController.js"></script>
    <script src="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/scripts/controller/mainBarController.js"></script>
    <script src="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/scripts/controller/loginController.js"></script>
    <script src="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/scripts/app.js"></script>
    <script src="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/scripts/main.js"></script>
    <script src="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/scripts/jMain.js"></script>
    <script type='text/javascript' src="<?php echo base_url(); ?>/public/assets/ckeditor/ckeditor.js"></script>

    <style>
        body {
            background-color: #f5f5f5;
        }

        /* Set height of the grid so .sidenav can be 100% (adjust if needed) */
        /* .row.content { */
        /*  min-height: 600px; */
        /* } */

        /* Set gray background color and 100% height */
        .sidenav {
            background-color: #f5f5f5;
        }

        .feedback_form {
            background-color: #fff;
            margin-bottom: 10px;
            margin-top: 10px;
            border-radius: 5px;
            box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);
        }

        .feedback_inside_form {
            padding: 5px;

        }

        /*
        .feedback_view {
            background-color: #f5f5f5;
        }
*/
        .individual_feedback_design {
            background-color: #fff;
            border-radius: 5px;
            width: 100%;
            margin-bottom: 10px;
            padding: 5px;
            border: 1px;
            box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);
        }

        .individual_feedback_design:hover {
            box-shadow: 0 3px 10px rgb(0 0 0 / 0.5);
        }




        /* On small screens, set height to 'auto' for sidenav and grid */
        @media screen and (max-width: 767px) {
            .sidenav {
                height: auto;
                padding: 15px;
            }

            .row.content {
                height: auto;
            }
        }

        .noDecoration {
            width: 245px;
            background-color: #f1f1f1;
            color: black;
            padding: .3em .2em;
            text-decoration: none;
            cursor: pointer;
            text-align: left;
            text-wrap: wrap;
            margin-left: 5px;
            margin-bottom: 2px;
            font-size: 14px;
        }

        .noDecoration:hover {

            background-color: #d6d6d6;

        }

        .noDecoration_select {
            width: 245px;
            background-color: #d8d8d8;
            color: black;
            padding: .3em .2em;
            text-decoration: none;
            text-align: left;
            text-wrap: wrap;
            margin-left: 5px;
            margin-bottom: 2px;
            font-size: 14px;
        }

        .full-view {

            max-width: 1920px;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            margin: auto;
        }

        .course_with_feedback {

            margin: auto;
        }

        .only_course {
            width: 74vw;
            max-width: 1280px;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            margin: auto;
        }

        #menu_details {
            position: absolute;
            width: 300px;
            text-align: left;
            top: 0px;
            left: 0px;
            right: 0;
            bottom: 0;
            background-color: white;
            border: 1px;
            z-index: 1;
        }

        .right_menu {
            position: relative;
            float: left;
        }

        .main-content {
            background-color: #fff;
            z-index: 10;
        }

        .hide_menu {
            position: absolute;
            left: -0px;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .resume_menu {
            position: absolute;
            left: -0px;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 2000000;
        }

        .resume_contailer {
            margin-left: 30%;
            margin-top: 20%;
            color: black;
            padding: 30px;
            width: 350px;
            border-radius: 10px;
            height: 150px;
            background-color: white;
        }

        .header-design {
            background-color: #fff;
            padding-top: 5px;
            max-width: 1280px;
            border-bottom: 1px solid black;
        }

        .feedback-design {
            background-color: #3cb371;
            color: white;
            padding-top: 11px;
            padding-bottom: 10px;
        }

        .remove_Cursor {
            cursor: none;
        }

        .menu {
            height: 80vh;
            overflow: auto;
            padding: 5px;
            background-color: white;
            text-decoration: none;
        }

        .quiz_bg {

            background-color: rgb(255, 255, 255);
            /* background-image: url(<?php echo base_url('assets/assets/img/BG.png'); ?>); */
            background-repeat: no-repeat;
            background-position: 0% 0%;
            background-size: 100%;
        }

        .quiz_option_bg {
            background-color: rgb(255, 255, 255);
            /* background-image: url(<?php echo base_url('assets/assets/img/BG_a.png'); ?>); */
            background-repeat: no-repeat;
            background-position: 0% 0%;
            background-size: 100%;
        }

        .result_table td {
            text-align: center;
            vertical-align: middle;
            padding: 10px;

        }

        .quiz_area {
            height: 100vh;
            width: 100vw;
            padding: 50px;
        }

        .quiz_start_btn {
            background-color: #e1251a;
            border: none;
            color: white;
            padding: 2px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            padding-left: 20px;
            padding-right: 20px;
            border-radius: 12px;
        }

        .submit-quiz-btn {
            background-color: #e1251a;
            border: none;
            color: white;
            padding: 2px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            padding-left: 20px;
            padding-right: 20px;
            border-radius: 12px;
        }

        .question-number {
            background-color: rgb(97, 94, 94);
            width: 90vw;
            color: white;
            height: 30px;
            padding: 5px;
        }

        .Quiz_question_img {
            margin-top: 20px;
            width: 70px;
            height: 70px;
        }


        .quiz_stem {
            margin-top: 20px;
            margin-left: 10px;
            font-size: 18px;
        }

        .quiz_instructions {
            color: #e1251a;
            padding: 10px;
        }

        .quiz_option_container {
            margin: 10px;
            padding: 10px;
        }

        .start_instructions {
            background-color: #fdf1e5;
            color: #e1251a;
            padding: 5px;
        }

        .assessment_text {
            margin-top: 100px;
            font-size: 30px;

        }

        .table-center {
            display: block;
            height: 86vh;
            width: 74vw;
            max-width: 1280px;

        }



        .tick {
            color: black;
            font-size: 12px;
            width: 30px;
        }

        .tick-subpage {
            position: relative;
            margin-top: -48px;
            left: 90%;
            width: 20px;
            font-size: 25px;
        }

        .question_bg_question {}

        .question_bg {
            /*  background-color: #acd8db; */
            background-image: url(<?php echo base_url('assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/' . $theme . '/scripts/QuizTemplate/MCQ/images/BG.png'); ?>);
            background-repeat: no-repeat;
            color: black;
            padding: 20px;
            background-size: contain;
            height: 86vh;
            border-radius: 5px;
        }

        a:hover {
            text-decoration: none;
        }

        .question_base {

            padding: 10px;
            color: black;

            vertical-align: top;
        }

        .question_stem {

            margin-left: 10px;
            margin-right: 10px;
            font-size: 16px;
        }

        .question_instructions {
            margin-top: 10px;
            color: #df7e00;
            margin-left: 10px;
            margin-right: 10px;
            font-size: 14px;
        }

        .question_retry_btn {
            padding-right: 15px;
            padding-left: 15px;
            margin: 10px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            background-color: #F2672E;
            border-color: #F2672E;
            color: white;
        }

        .question_sub_btn {

            padding-right: 15px;
            padding-left: 15px;
            margin: 10px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            background-color: #F2672E;
            border-color: #F2672E;
            color: white;
        }

        .question_sub_btn:hover {
            background-color: rgb(241, 143, 104);
        }

        .option_container {
            margin-left: 25px;
            padding: 10px;
        }

        .options {
            border-radius: 2px;
            padding: 5px;
            margin: 5px;
            width: 90%;
            font-size: 14px;
            border-width: 2px;

        }

        .options_cursor {
            cursor: pointer;
        }

        .options_cursor:hover {
            color: rgb(97, 60, 184);
        }

        .correct_option {
            background-color: rgb(131, 221, 134);
        }



        .feedback_correct {
            background-color: #62bd5e;
            color: white;
            padding: 5px;
            margin: 5px;
            width: 95%;
            font-size: 14px;
            border-radius: 5px;
            display: none;
        }

        .feedback_wrong {
            background-color: #f56969;
            color: white;
            padding: 5px;
            margin: 5px;
            width: 95%;
            font-size: 14px;
            border-radius: 5px;
            display: none;
        }

        .page_number_design {
            padding-top: 5px;
        }

        .img_circle {
            border-radius: 50%;
            width: 20px;
            height: 20px;
        }

        .feedback_details {
            margin-top: 5px;
            font-size: 12px;
        }

        .img_circle_reply {
            border-radius: 50%;
            width: 20px;
            height: 20px;
            margin-left: 20px;
        }

        .feedback_details_reply {
            margin-top: 5px;
            margin-left: 20px;
            font-size: 12px;
        }

        .iframe-container {
            position: relative;
            overflow: hidden;
            height: 86vh;
            /* 16:9 Aspect Ratio (divide 9 by 16 = 0.5625) */
        }

        .main_content_area {
            height: 86vh;
            overflow: auto;
            max-width: 1280px;

        }

        .feedback_window {
            overflow: auto;
            /* iframes are inline by default */
            background: #000;
            border: none;
            /* Reset default border */
            height: 83;

        }

        .responsive-iframe {
            position: absolute;
            overflow: hidden;
            top: 0;
            left: 0;
            bottom: 0;
            border: 0;
            right: 0;
            width: 100%;
            height: 100%;
        }

        .footer-design {
            position: absolute;
            width: 100%;
            top: 45%;
            padding-bottom: 5px;

        }

        .bottom_border {
            height: 2px;
            background-color: #727272;
        }

        .nav-btn-container {
            margin-top: 4px;
            height: 45px;
            position: relative;
            padding: 2px;
            border-radius: 5px;
            max-width: 1280px;
            background-color: #fff;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.5);

        }

        .prev_btn_container {
            margin: 10px;
        }

        .next_btn_container {
            margin: 10px;
        }

        .close_btn_container {
            position: absolute;
            top: 8%;
            right: 0px;
            z-index: inherit;
        }

        .next_instruction {
            position: absolute;
            bottom: 7%;
            padding: 10px;
            right: 0px;
            z-index: 100;
            color: white;
            background-color: #000;
            max-width: 1280px;
            z-index: 100;
        }

        .close_btn {
            background-image: url(<?php echo base_url('assets/assets/img/Close_N.png'); ?>);
            background-repeat: no-repeat;
            height: 35px;
            width: 40px;
            background-repeat: no-repeat;
        }

        .return_btn_container {
            position: absolute;
            top: 3%;
            right: 0px;
            z-index: 100;
        }

        .sub_page_btn_container {
            margin: 40px;

        }

        .sub_page_btn {
            max-width: 400px;
            min-height: 60px;
            font-size: 15px;
            font-weight: bold;
            border-radius: 10px;
            width: 100%;
            color: black;
            padding-bottom: 5px;
            background-color: #edae36;
            top: 130px;
        }

        .interactive_bg {
            background-image: url(<?php echo base_url('assets/assets/img/sub_page_bg.png'); ?>);
            background-repeat: no-repeat;
            position: absolute;
            /*   height: 75vh; */
            color: white;
            /*  background-color: #183f5b; */
        }

        .dropbtn {
            background-color: white;
            color: black;
            padding: 2px;
            font-size: 15px;
            border: none;
            cursor: pointer;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown:hover .dropbtn {
            background-color: grey;
        }

        .submit-quiz-btn.faded {
            opacity: 0.5;
            cursor: not-allowed;
            background-color: #ccc;
        }

        .submit-quiz-btn:not(.faded):hover {
            background-color: #45a049;
        }

        .background_btn {
            background-color: #000;
            position: fixed;
            left: 0px;
            top: 0px;
            display: none;
            width: 100%;
            height: 100%;
            color: white;
            opacity: 0.5;
            z-index: 200;
        }

        .openBtn_class {
            margin: 10px;
            z-index: 500;
            position: fixed;
            right: 0px;
            display: block;
        }

        .closeBtn_class {
            margin: 10px;
            z-index: 500;
            position: fixed;
            right: 0px;
            display: none;
        }

        .feedbackwindow {
            display: none;
            z-index: 300;
            margin: 7px;
            position: fixed;
            right: 0px;
            /* for demo purpose  */
            width: 50vw;
            height: 90vh;
            background-color: black;
        }

        .rtl {
            direction: rtl;
            text-align: right;
        }
    </style>

    <script>
        // Store video time in seconds
        var TimeStore = 0;

        function GetVideoTime() {

            var vid1 = document.getElementById("vidArea");
            if (vid1) {
                var currentTime = vid1.currentTime;
                return currentTime;
            }
            return null; // Return null if video element is not found
        }

        // Helper function to format time as MM:SS
        function formatTime(seconds) {
            var mins = Math.floor(seconds / 60);
            var secs = Math.floor(seconds % 60);
            return mins + ":" + (secs < 10 ? "0" : "") + secs;
        }

        // Function to go to the stored time
        function goToSession(TimeStore) {
            var timeInSeconds = TimeStore;
            var vid1 = document.getElementById("vidArea");
            if (vid1) {
                vid1.currentTime = timeInSeconds; // Seek to the stored time
                vid1.stop(); // Start playing from that time
                console.log("Jumped to:", formatTime(timeInSeconds));
            }
        }

        // Function to show the current time of the video
        function showCurrentTime() {
            var formattedTime = GetVideoTime();
            if (formattedTime) {
                document.getElementById("currentTimeDisplay").innerText =
                    "Current Video Time: " + formattedTime + " (In seconds: " + TimeStore.toFixed(2) + ")";
                console.log("Current Video Time:", formattedTime);
            } else {
                document.getElementById("currentTimeDisplay").innerText =
                    "No video is currently playing.";
            }
        }
    </script>
    <?php if ($theme === 'ModernTheme'): ?>
    <style>
        .pageContent .iframe-container, .pageContent .responsive-iframe,
        .pageContent #vidArea, .pageContent video,
        .pageContent .question_bg, .pageContent .table-center,
        .pageContent .text-page-container { height: 100% !important; }

        /* jMain.js opens the drawer by setting an inline `display: block`, which beats
           Color.css's unscoped `display: flex` and would render the sidebar as a plain
           block box (its .tocData flex/scroll rules then do nothing). Color.css already
           ships this exact guard for <=1024px (see its @media block); this extends the
           same rule to wider viewports. */
        #Tmenu.sideBar[style*="display: block"] {
            display: flex !important;
            flex-direction: column !important;
        }
    </style>
    <script>
        (function () {
            var isTouch = (navigator.maxTouchPoints > 0) || ('ontouchstart' in window);
            if (isTouch && window.innerWidth <= 1366) {
                document.documentElement.classList.add('is-touch-device');
            }
        })();

        /* jMain.js's shouldMenuBeForcedClosedForAudioPage() reads the global
           AudioVersionEnable, which the theme declares in scripts/scormFunctions.js --
           a SCORM API wrapper this Preview deliberately does not load. Without the
           declaration that read throws a ReferenceError inside TtoggleMenu(), so the
           menu button did nothing. Declaring it false (no audio-version flow) satisfies
           the reference without pulling in any SCORM code. */
        if (typeof window.AudioVersionEnable === 'undefined') {
            window.AudioVersionEnable = false;
        }
    </script>
    <?php endif; ?>
</head>

<body>
    <?php if ($theme === 'ModernTheme'): ?>
    <div class="wholeContainer">
    <?php else: ?>
    <div class="container-fluid full-view">
        <div class="row content">
    <?php endif; ?>