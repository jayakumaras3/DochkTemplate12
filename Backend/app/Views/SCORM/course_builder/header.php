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

    <?php if ($theme === 'ModernTheme'): ?>
    <?php /* Exact stylesheet set and order of the exported ModernTheme player, taken from a
             real exported package's entry point (course-packages/DocheckPre/index.html):
                 bootstrap -> sideBar -> content -> footer -> custom -> mobile -> toc -> Color
             Differences this corrects versus what Preview loaded before:
               - sideBar.css must be 2nd (it was 7th, so it wrongly overrode content.css)
               - custom.css must be 5th (it was 2nd)
               - certificate.css is NOT part of the exported set, so it is not loaded here
                 (that also removes the 404 it was producing)
             Color-FIXED.css is deliberately absent: the export does not load it, Color.css
             is the live override layer and must stay last. */ ?>
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/sideBar.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/content.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/footer.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/custom.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/mobile.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/toc.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/Color.css" rel="stylesheet">
    <?php else: ?>
    <?php /* Every other theme keeps the original order and set, unchanged. */ ?>
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/custom.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/Color.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/content.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/footer.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/mobile.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/toc.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/certification.css" rel="stylesheet">
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
        /* Color.css sizes .pageContent as a flex child (height:0; flex:1), but the
           content blocks below were written for the legacy fixed-header shell and
           hardcode 86vh/70vh. Neutralise those inside .pageContent only. */
        .pageContent .iframe-container, .pageContent .responsive-iframe,
        .pageContent #vidArea, .pageContent video,
        .pageContent .question_bg, .pageContent .table-center,
        .pageContent .text-page-container { height: 100% !important; }

        /* jMain.js opens the drawer with an inline `display: block`, which beats
           Color.css's unscoped `display: flex` and would render the sidebar as a plain
           block box (its .tocData flex/scroll rules then do nothing). Color.css already
           ships this exact guard for <=1024px; this extends it to wider viewports. */
        #Tmenu.sideBar[style*="display: block"] {
            display: flex !important;
            flex-direction: column !important;
        }

    </style>
    <script>
        /* Same touch-detection convention the theme already uses in its own
           QuizTemplate pages (Quiz.html / MCQ.html / SCQ.html); Color.css keys several
           tablet-landscape rules off html.is-touch-device. */
        (function () {
            var isTouch = (navigator.maxTouchPoints > 0) || ('ontouchstart' in window);
            if (isTouch && window.innerWidth <= 1366) {
                document.documentElement.classList.add('is-touch-device');
            }
        })();

        /* jMain.js's shouldMenuBeForcedClosedForAudioPage() reads the global
           AudioVersionEnable, which the theme declares in scripts/scormFunctions.js -- a
           SCORM API wrapper Preview deliberately does not load. Undeclared, that read
           throws a ReferenceError on the first line of TtoggleMenu(), so the menu button
           did nothing. Declaring it satisfies the reference without loading SCORM code. */
        if (typeof window.AudioVersionEnable === 'undefined') {
            window.AudioVersionEnable = false;
        }
    </script>
    <?php endif; ?>
    <?php
    /* Inline knowledge-check pages (page types 5 = SCQ, 6 = MCQ) are rendered inside the
       course page rather than in the quiz iframe, so they need the theme's own SCQ/MCQ
       stylesheets. Gated on the page type so these rules never touch other page types. */
    $isInlineQuestionPage = isset($row['type']) && ($row['type'] == 5 || $row['type'] == 6);
    ?>
    <?php if ($theme === 'ModernTheme' && $isInlineQuestionPage): ?>
    <?php /* Exactly the order the theme's own SCQ.html / MCQ.html use:
             SCQ|MCQ_style -> Color -> QuestionOptions. Color.css must follow the question
             stylesheet so .Correct_CR / .Incorrect_CR win over its plain .correct /
             .incorrect, which is what gives the theme's feedback bars their colours. */ ?>
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/<?php echo ($row['type'] == 5) ? 'SCQ_style.css' : 'MCQ_style.css'; ?>" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/Color.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/QuestionOptions.css" rel="stylesheet">
    <style>
        /* Structure only. The inline question markup lives inside the course page's
           .pageContent, so give the theme's question card room and drop the legacy
           full-height/background rules that were sized for the old shell. */
        .pageContent .question_bg { background-image: none; height: auto; }
        /* The theme's own knowledge-check layout has no bulb icon. */
        .pageContent .question_bg_question { display: none; }
        .pageContent .question_bg > table,
        .pageContent .question_bg > table > tbody,
        .pageContent .question_bg > table > tbody > tr,
        .pageContent .question_bg > table > tbody > tr > td { display: block; width: auto; height: auto; }
        /* QuestionOptions.css marks the selected option via [aria-checked], which only the
           theme's JS sets; map it onto the native :checked state (no JS change). */
        .pageContent .answer:has(input:checked) {
            background: #EAF4FF;
            box-shadow: inset 0 0 0 1px #2A78C8;
        }
        /* The theme's own SCQ.html / MCQ.html never load Bootstrap, so its
           `label { font-weight: bold }` reset does not exist there. Preview does load
           Bootstrap, which was overriding QuestionOptions.css's option text. */
        .pageContent .answer label { font-weight: 400; }

        /* Correct-answer indicator. The quiz JS marks the right option by adding the
           legacy `.correct_option` class to its <label>; that class carries a bright
           green background from this file's inline block, which painted a bar across the
           label instead of the theme's tick. Neutralise it inside .answer only, and
           instead reveal QuestionOptions.css's own .tickSymbol (its green SVG, its
           --before-visibility switch) in the 20px slot every row already reserves - so
           the tick is centred in the row and never overlaps the input or the text. */
        .pageContent .answer .correct_option { background-color: transparent; }
        .pageContent .answer:has(.correct_option) .tickSymbol { --before-visibility: visible; }

        /* Hover. QuestionOptions.css already supplies the theme's hover background
           (.btn:hover etc -> #00587A); the only thing missing is the text colour, because
           Bootstrap 3's `.btn:focus, .btn:hover { color: #333 }` has the same specificity
           and QuestionOptions never re-declares `color`, so the label turned dark grey.
           The theme's pages never load Bootstrap, so restore its white text only here. */
        .pageContent .btn:hover, .pageContent .btn:focus,
        .pageContent .retrybtn:hover, .pageContent .retrybtn:focus { color: #ffffff; }

        /* In the theme, Submit/Try-Again are siblings of .options; here they sit inside it
           (the form is display:contents), and .options is a flex column, so the default
           align-items:stretch was widening them to the full row. Opt them out only.
           Descendant (not child) combinator: the MCQ/SCQ submit button is a grandchild of
           .option_container (the display:contents <form> sits in between), and `>` only
           walks the real DOM tree, not the render tree, so it never matched -- MCQ's Submit
           was stretching to the full options-column width as a result. */
        .pageContent .option_container .btn,
        .pageContent .option_container .retrybtn,
        .pageContent .option_container #submit-btn,
        .pageContent .option_container #retry-btn { align-self: flex-start; }

        /* Legacy .question_base/.question_stem/.question_instructions/.options rules (this
           file's earlier unconditional <style> block, ~line 442-507) were never gated for
           ModernTheme the way .form-check already is in page_video_view.php. Each property
           below is one the theme's own CSS never re-declares for that class, so it defaults
           to 0 in the real export; these neutralise the un-gated legacy value back to that
           same implicit default rather than inventing or duplicating a theme value. */
        .pageContent .question_base { padding: 0; }
        .pageContent .question_stem { margin-left: 0; margin-right: 0; }
        .pageContent .question_instructions { margin-left: 0; margin-right: 0; }
        .pageContent .options { padding: 0; margin-right: 0; }

        /* SCQ_style/MCQ_style give .correct/.incorrect a legacy `margin-left: 2.5%` and
           `padding-left: 2.1%`. The theme cancels those because its feedback element is
           `<p id="feedback">` and `#feedback { margin: 0 }` outranks them; our feedback
           keeps its own ids (the quiz JS needs them), so cancel the offset the same way -
           otherwise the bar is indented and overflows the row on the right. */
        .pageContent .option_container > .feedback { margin-left: 0; padding-left: 16px; }
    </style>
    <?php endif; ?>
    <?php if ($theme === 'ModernTheme' && !empty($isQuizPage)): ?>
    <?php /* The theme's own Quiz.html loads these two last (Quiz_style -> Color -> QuestionOptions);
             they must also come after this file's legacy inline <style> block above, which styles
             the old .options/.quiz_* classes and would otherwise win on equal specificity.
             Gated on $isQuizPage so quiz rules never bleed into ordinary course pages. */ ?>
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/Quiz_style.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/Color.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/<?php echo $theme ?>/css/QuestionOptions.css" rel="stylesheet">
    <style>
        /* The legacy faded/disabled state was styled as `.submit-quiz-btn.faded`; the
           ModernTheme markup drops that class (it carried the old red background), so
           re-attach the same affordance to the id the quiz JS already toggles. */
        #submit-quiz-btn.faded { opacity: 0.5; cursor: not-allowed; }

        /* In the theme, Submit sits outside .options; here the form is display:contents so
           it becomes a flex item of that column, and align-items:stretch would widen it to
           the full row. Opt just this button out. */
        .mtQuizArea .options > #submit-quiz-btn { align-self: flex-start; }

        /* Same Bootstrap `.btn:hover { color: #333 }` clash as on the inline pages - keep
           the theme's hover background, restore its white label. #quizContainer covers the
           Assessment Start button and the result page's Retry button. */
        .mtQuizArea .btn:hover, .mtQuizArea .btn:focus,
        .mtQuizArea .retrybtn:hover, .mtQuizArea .retrybtn:focus,
        #quizContainer .btn:hover, #quizContainer .btn:focus,
        #quizContainer .Startpagebtn:hover, #quizContainer .Startpagebtn:focus,
        #quizContainer .retrybtn:hover, #quizContainer .retrybtn:focus { color: #ffffff; }

        /* Structure only - no theme design values are redefined here. The question view
           nests its content in a table purely for layout; neutralise that (and the
           legacy full-viewport padding) so Quiz_style.css's own .questionContainer /
           .contentWrapper / .options flex rules can take effect on the existing markup. */
        .mtQuizArea { height: auto; width: auto; padding: 0; }
        .mtQuizRoot { background: transparent; }
        <?php /* Chain runs through #quizContainer now that the card is wrapped in it, so these
                 stay direct-child selectors (they must not affect a table inside the question
                 content itself). */ ?>
        .mtQuizArea #quizContainer > table,
        .mtQuizArea #quizContainer > table > tbody,
        .mtQuizArea #quizContainer > table > tbody > tr,
        .mtQuizArea #quizContainer > table > tbody > tr > td { display: block; width: auto; }
        /* The theme's Quiz question layout has no bulb icon (see quiz.js). */
        .mtQuizArea .Quiz_question_img { display: none; }

        /* QuestionOptions.css styles the selected option via [aria-checked="true"],
           which only the theme's own JS sets. Map the same rule onto the native
           :checked state so selection is visible without touching any JavaScript. */
        .mtQuizArea .answer:has(input:checked) {
            background: #EAF4FF;
            box-shadow: inset 0 0 0 1px #2A78C8;
        }
    </style>
    <?php endif; ?>
    <?php if ($theme === 'ModernTheme'): ?>
    <?php /* MUST be the last stylesheet in <head>. Color.css sizes the sidebar responsively
             (--player-sidebar-width: 20vw, min 240px / max 380px) and redefines that variable
             inside several @media blocks, so on a wide window it clamps to 380px while the
             reference export - viewed in a ~1525px window - renders 305px. Pinned to 305px so
             Preview matches the export at any window size.
             Placed here rather than with the other ModernTheme rules because the inline-question
             and quiz blocks above re-link Color.css; sitting before them, this pin was being
             reset back to 20vw on SCQ/MCQ and quiz pages, giving those pages a different
             sidebar width from the normal course pages.
             Set on :root so Color.css's own .sideBar rule consumes it and every dependent
             value (tab widths, row widths, active bar, insets) rescales automatically - no
             individual dimension is hardcoded. Desktop only: below 1025px Color.css switches
             to its own drawer widths, which are left untouched. Export is unaffected. */ ?>
    <style>
        @media (min-width: 1025px) {
            :root { --player-sidebar-width: 305px; }
        }
    </style>
    <?php endif; ?>
    <?php if ($theme === 'ModernTheme' && ($isInlineQuestionPage || !empty($isQuizPage))): ?>
    <?php /* Full-row option selection. In the export, the theme's own quiz.js puts an inline
             onclick on the row div itself (`<div class="answer" onclick="selectOption('answerN')">`
             - SCQ/quiz.js:42-45, MCQ/quiz.js, Quiz/quiz.js:345/354), which is what makes the whole
             bordered row a click target there. Preview's rows carry no such handler, so only the
             <input> and the <label for=...> were clickable natively, leaving real dead zones: the
             row's own 10px side padding, the 20px .tickSymbol slot, both 12px flex gaps, and the
             ~8px strips above/below the label's line box inside the 36px-min-height row.

             One delegated listener here rather than three copies, because header.php is the only
             file shared by all three question flows (launcher -> page_video_view for SCQ/MCQ,
             quizQuestions -> quiz_questions for Quiz; the latter never loads footer.php).

             Clicks that land on the input or the label are left entirely alone so the browser's
             native activation stays the only thing acting on them - that is what prevents any
             double-toggle. Nothing here touches scoring, attempts, retry or SCORM: it only sets
             .checked, exactly as a real click would, then fires the same `change` event a real
             click fires so existing listeners (e.g. quiz_questions.php's submit-enable check)
             behave identically. Disabled inputs are skipped, so the post-submit lock applied by
             footer.php's disable_radio_btns() keeps rows inert.

             Deliberately NOT adding role/tabindex to the row: unlike the theme (which sets
             tabindex="-1" on its input because the row is its tab stop), Preview's inputs are
             real focusable controls with proper <label for> association, so keyboard and
             screen-reader selection already work. Adding a second tab stop would regress that. */ ?>
    <script>
        document.addEventListener('click', function (event) {
            if (!event.target || !event.target.closest) { return; }
            var row = event.target.closest('.answer');
            if (!row) { return; }

            var input = row.querySelector('input[type="radio"], input[type="checkbox"]');
            if (!input || input.disabled) { return; }

            /* Native handling already covers these two - do not interfere. */
            if (event.target === input || event.target.closest('label')) { return; }

            if (input.type === 'radio') {
                if (input.checked) { return; }
                input.checked = true;
            } else {
                input.checked = !input.checked;
            }
            input.dispatchEvent(new Event('change', { bubbles: true }));
        }, false);
    </script>
    <?php endif; ?>
</head>

<body>
    <?php if ($theme === 'ModernTheme'): ?>
    <?php /* Color.css's .wholeContainer is itself the full-viewport flex column; nesting it
             inside .container-fluid.full-view (max-width/position:absolute) would fight it. */ ?>
    <div class="wholeContainer">
    <?php else: ?>
    <div class="container-fluid full-view">
        <div class="row content">
    <?php endif; ?>