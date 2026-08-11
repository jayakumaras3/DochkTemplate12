<?php
/* Computed here (not further down with $theme) because the legacy <style> block below has
   to be gated before it is emitted. It nudges .form-check-input down 10px and forces
   .form-check to align-items:flex-start, both of which override the theme's
   .answer { align-items:center } and knock the radio/checkbox out of line with the tick
   and the option text. Skipped for ModernTheme knowledge-check pages only. */
$isModernThemeQ = isset($coursedetails) && isset($coursedetails[0]['theme'])
    && $coursedetails[0]['theme'] == '8'
    && isset($row['type']) && in_array((int) $row['type'], [5, 6], true);
?>
<?php if (!$isModernThemeQ): ?>
<style>
    .form-check {
        display: flex;
        align-items: flex-start;
        /* margin-bottom: 12px; */
    }

    .form-check-input {
        vertical-align: middle;
        margin-top: 0.3em;
        /* adjust as needed */
        margin-right: 5px;
        position: relative;
        top: 10px;
        /* manually nudge down */
    }

    .form-check-label {
        line-height: 1.5;
        /* word-break: break-word; */
    }
</style>
<?php endif; ?>
<?php
$userlevel = session()->get('userlevel');
$arrayuserlevel = explode(',', $userlevel);
//  print_r(); 
// exit();
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
<?php if (!empty($getAssessmentSettings)) {
    foreach ($getAssessmentSettings as $value) {
        $type = $value['type'];
        // print_r($type."<br/>");
        if ($type == 59) {
            $kyuselectscqdescrip = $value['value'];
        }
        if ($type == 60) {
            $kyuselectmcqdescrip = $value['value'];
        }
        if ($type == 61) {
            $kyusubmit = $value['value'];
        }
        if ($type == 68) {
            $kyupleaseselectanswer = $value['value'];
        }
    }
}
$kyuselectscqdescrip = (isset($kyuselectscqdescrip) && $kyuselectscqdescrip != '') ? $kyuselectscqdescrip : $assessment_scqmcq_sets['59'];
// print_r($assessment_scqmcq_sets['59']);
// exit();
$kyuselectmcqdescrip = (isset($kyuselectmcqdescrip) && $kyuselectmcqdescrip != '') ? $kyuselectmcqdescrip : $assessment_scqmcq_sets['60'];
$kyusubmit = (isset($kyusubmit) && $kyusubmit != '') ? $kyusubmit : $assessment_scqmcq_sets['61'];
$kyupleaseselectanswer = (isset($kyupleaseselectanswer) && $kyupleaseselectanswer != '') ? $kyupleaseselectanswer : $assessment_scqmcq_sets['68'];
?>
<?php if (isset($getcourseAssessmentSettings)) {
    foreach ($getcourseAssessmentSettings as $value) {
        $type = $value['type'];
        // print_r($type."<br/>");
        if ($type == 50) {
            $Menutitle = $value['value'];
        }
        if ($type == 51) {
            $NextTitle = $value['value'];
        }
        if ($type == 52) {
            $Prevtitle = $value['value'];
        }
        if ($type == 53) {
            $MenuName = $value['value'];
        }
        if ($type == 54) {
            $TranscriptName = $value['value'];
        }
        if ($type == 55) {
            $ResumeTitle = $value['value'];
        }
        if ($type == 56) {
            $ResumeHeader = $value['value'];
        }
        if ($type == 57) {
            $ResumeYES = $value['value'];
        }
        if ($type == 58) {
            $ResumeNO = $value['value'];
        }
    }
}
$Menutitle = (isset($Menutitle) && $Menutitle != '') ? $Menutitle : $assessment_export_sets['50'];
$NextTitle = (isset($NextTitle) && $NextTitle != '') ? $NextTitle : $assessment_export_sets['51'];
$Prevtitle = (isset($Prevtitle) && $Prevtitle != '') ? $Prevtitle : $assessment_export_sets['52'];
$MenuName = (isset($MenuName) && $MenuName != '') ? $MenuName : $assessment_export_sets['53'];
// print_r($Menutitle);
// exit();
$TranscriptName = (isset($TranscriptName) && $TranscriptName != '') ? $TranscriptName : $assessment_export_sets['54'];
$ResumeTitle = (isset($ResumeTitle) && $ResumeTitle != '') ? $ResumeTitle : $assessment_export_sets['55'];
$ResumeHeader = (isset($ResumeHeader) && $ResumeHeader != '') ? $ResumeHeader : $assessment_export_sets['56'];
$ResumeYES = (isset($ResumeYES) && $ResumeYES != '') ? $ResumeYES : $assessment_export_sets['57'];
$ResumeNO = (isset($ResumeNO) && $ResumeNO != '') ? $ResumeNO : $assessment_export_sets['58']; ?>

<div class="background_btn" id="bg_black" onclick="closeFeedback()">

</div>
<?php if ($typeOfLaunch == 2) {
} else {
?>
    <?php if (in_array('46', $arrayuserlevel) || in_array('4', $arrayuserlevel) || in_array('45', $arrayuserlevel) || in_array('67', $arrayuserlevel)) { // Developer ,PM,CR
    ?>
        <button class="openBtn_class btn btn-default" id="open_btn" onclick="openFeedback()"><span
                class="glyphicon glyphicon-edit"></span>&nbsp; &nbsp;FEEDBACK</button>
        <button class="closeBtn_class btn btn-danger" id="close_btn" onclick="closeFeedback()"><span
                class="glyphicon glyphicon-remove-circle"></span></button>
    <?php } ?>
<?php } ?>
<div class="row">
    <div class="feedbackwindow" id="feedbackwin">
        <div class="col-md-12 feedback-design">
            Feedback
        </div>
        <iframe id="feedback_frame" class="feedback_window" frameBorder="0" height="100%" width="100%"
            src="<?php echo base_url('SCORM/Course_builder/review_course/scorm_feedback_launcher/' . $course_id . '/' . $page_id . '/' . $typeOfLaunch) ?>"
            title="feedback"></iframe>
    </div>
</div>

<?php // if ($typeOfLaunch == 2) { 
?>
<?php /* ModernTheme's layout root is .wholeContainer (opened in header.php); .only_course
         constrains width to 74vw/1280px, which would clip it. */ ?>
<?php if ($theme !== 'ModernTheme'): ?>
<div class="col-sm-12 only_course" id="target">
<?php endif; ?>
    <?php //} else { 
    ?>
    <!--   <div class="col-sm-9 course_with_feedback" id="capture"> -->
    <?php // } 
    ?>
    <?php
    if ($course_return_window == 1) {
        if (abs($row['page_number']) == 1 && strlen($lesson_location) > 0) {
            if (abs($row['page_number']) != abs($lesson_location)) {
    ?>
                <div id="resumemainContainer" class="resume_container">
                    <div class="preloaderDisplayresume"></div>
                    <div id="resumeArea" class="resume_content_container ">
                        <div class="resume_content_header ColorSet_CR FSize18"><?php echo $ResumeTitle; ?></div>
                        <div class="resume_content_text"><?php echo $ResumeHeader; ?></div>
                        <div class="row h-100">
                            <div class="col-lg-6 text-center">
                                <form
                                    action="<?php echo base_url('SCORM/Course_builder/review_course/launcher/1/' . $lesson_location); ?>"
                                    method="POST"><?= csrf_field() ?>
                                    <button
                                        class="btn btn-large btn-default text-center ColorSet_CR yes-btn"><?php echo $ResumeYES; ?></button>
                                </form>
                            </div>
                            <div class="col-lg-6 text-center">
                                <form action="<?php echo base_url('SCORM/Course_builder/review_course/launcher/1/1'); ?>" method="POST"><?= csrf_field() ?>
                                    <button
                                        class="btn btn-large btn-default text-center ColorSet_CR no-btn"><?php echo $ResumeNO; ?></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
    <?php
            }
        }
    }
    ?>

    <?php
    // $isModernThemeQ is computed at the top of this file (it gates the legacy <style>
    // block there); do not redefine it here.
    // Hoisted out of the header markup below so both theme branches can use them.
    $currentpage = round($row['page_number']);
    $totalPage = count($pagedetails);
    $prev_page_number = $currentpage - 1;
    $next_page_number = $currentpage + 1;
    ?>
    <?php if ($theme === 'ModernTheme'): ?>
        <?php /* Structure below mirrors ModernTheme's own content.html contract, which
                 Color.css targets with strict child selectors:
                 #Tmenu.sideBar > (img.logo, #sideBarHeader > #toc_id|#trans_id, .tocData, .copyright)
                 and #tocData > span > li > span.toc-row-item > (.toc-item-text > .menu-title).
                 The existing per-page POST navigation is reused verbatim, only re-nested.
                 style="display:none" is also copied verbatim from the theme's own content.html
                 (<div id="Tmenu" ... style="display:none">). .sideBar's own CSS rule (Color.css)
                 is an unconditioned `display: flex`, so without this inline style the panel
                 painted open on first render -- every Preview page load/refresh/navigation
                 briefly showed the Menu before jMain.js's window 'load' handler (which fires
                 late, after every asset) forced it closed. TtoggleMenu()/toggleModernSidebar()
                 in footer.php only ever check panel.style.display === 'block', so starting
                 from "none" instead of unset does not change first-click open behaviour. */ ?>
        <div id="Tmenu" class="sideBar" style="display:none">
            <img class="logo" src="<?php echo base_url('assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/' . $theme . '/images/logo.svg'); ?>" alt="logo">
            <div id="sideBarHeader" role="tablist" aria-label="Sidebar tabs">
                <div id="toc_id" class="tocclickedclscss" role="tab" aria-selected="true"><span><?php echo $MenuName ?></span></div>
                <div id="trans_id" role="tab" aria-selected="false"><span><?php echo $TranscriptName ?></span></div>
            </div>
            <?php /* Inline style copied verbatim from the theme's own content.html:
                     <div class="tocData" ng-bind-html="sb.sideBarData"
                          style="line-height:1.5;overflow-y:auto !important;">
                     The line-height cascades into every menu row, so omitting it made the
                     rows tighter than the reference. */ ?>
            <div class="tocData" style="line-height:1.5;overflow-y:auto !important;">
                <?php
                /* Menu icons. Both the icon set and the keyword/type heuristic below are the
                   theme's own, taken from scripts/controller/sideBarController.js
                   (getTocItemIconSVG + getAutoMenuIcon). Preview cannot call that code (it
                   needs toc.json + Angular bootstrapping), so the same mapping is reproduced
                   here. The SVGs keep stroke="currentColor", so Color.css's
                   .toc-item-icon colour and the selectedToc / visitedTOC states still drive
                   them - no colour or icon is invented. */
                $tocIconSvg = [
                    'home' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7"/><path d="M9 22V12h6v10"/><path d="M21 22H3"/></svg>',
                    'book-open' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>',
                    'brain' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.5 2a3.5 3.5 0 0 0-3.5 3.5V8a2 2 0 0 0-2 2v1a2 2 0 0 0 2 2v1.5A3.5 3.5 0 0 0 9.5 18H10v4"/><path d="M14.5 2A3.5 3.5 0 0 1 18 5.5V8a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2v1.5A3.5 3.5 0 0 1 14.5 18H14v4"/><path d="M10 8h4"/><path d="M10 13h4"/></svg>',
                    'lightbulb' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="9" y1="18" x2="15" y2="18"/><line x1="10" y1="22" x2="14" y2="22"/><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5A4.61 4.61 0 0 1 8.91 14"/></svg>',
                    'clipboard-check' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/><path d="m9 12 2 2 4-4"/></svg>',
                    'accessibility' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="16" cy="4" r="1"/><path d="m18 19 1-7-6 1"/><path d="m5 8 3-3 5.5 3-2.36 3.5"/><path d="M4.24 14.5a5 5 0 0 0 6.88 6"/><path d="M13.76 17.5a5 5 0 0 0-6.88-6"/></svg>',
                    'badge-check' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>',
                    'check-circle' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>',
                    'video' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>',
                    'quiz' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/><path d="M12 11v5"/><path d="M12 8h.01"/></svg>',
                    'lesson' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>',
                ];
                /* Same tests, in the same order, as getAutoMenuIcon(). The theme reads the
                   content node's type/path from toc.json; here the equivalent is the page
                   record's own type (2/8/9 = video, 4 = quiz) that this view already uses. */
                $resolveTocIcon = function ($pageName, $pageType) {
                    $t = strtolower(trim((string) $pageName));
                    if (strpos($t, 'summary') !== false) { return 'check-circle'; }
                    if (in_array((int) $pageType, [2, 8, 9], true)) { return 'video'; }
                    if ((int) $pageType === 4 || strpos($t, 'quiz') !== false) { return 'quiz'; }
                    if (strpos($t, 'knowledge check') !== false) {
                        return (strpos($t, '2') !== false) ? 'badge-check' : 'clipboard-check';
                    }
                    if (strpos($t, 'welcome') !== false) { return 'home'; }
                    if (strpos($t, 'introduction') !== false) { return 'book-open'; }
                    if (strpos($t, 'accommodation') !== false) { return 'accessibility'; }
                    if (strpos($t, 'advantage') !== false) { return 'lightbulb'; }
                    if (strpos($t, 'workforce') !== false
                        || (strpos($t, 'neurodiversity') !== false && strpos($t, 'in the') !== false)) { return 'brain'; }
                    return 'book-open';
                };
                ?>
                <div id="menu" style="display:block;">
                    <ol id="tocData">
                        <?php foreach ($pagedetails as $page) {
                            if ($page['sub_page_main'] == 0) {
                                $isvisited = array_search(abs($page['page_number']), $scorm_suspend_data_arr);
                                $isCurrentPage = (round($page['page_number']) == round($row['page_number']));
                                $rowClass = 'toc-row-item' . ($isCurrentPage ? ' selectedToc' : (strlen($isvisited) > 0 ? ' visitedTOC' : ''));
                        ?>
                                <span>
                                    <li>
                                        <span class="<?php echo $rowClass; ?>">
                                            <form
                                                action="<?php echo base_url('SCORM/Course_builder/review_course/launcher/1/' . $page['page_number']); ?>"
                                                method="POST" style="display:contents;"><?= csrf_field() ?>
                                                <input type="hidden" name="course_id" value="<?php echo $page['fk_course_id']; ?>" />
                                                <input type="hidden" name="page_number" value="<?php echo $page['page_number']; ?>" />
                                                <input type="hidden" name="typeOfLaunch" value="<?php echo $typeOfLaunch ?>" />
                                                <?php /* gap:6px mirrors .toc-row-item's own gap in Color.css, since the
                                                         icon and text are flex items of this button. */ ?>
                                                <button type="submit" style="all:unset; display:flex; align-items:center; gap:6px; width:100%; cursor:pointer; color:inherit; font:inherit;">
                                                    <span class="toc-item-icon" aria-hidden="true"><?php
                                                        $tocIconKey = $resolveTocIcon($page['page_name'], isset($page['type']) ? $page['type'] : 0);
                                                        echo isset($tocIconSvg[$tocIconKey]) ? $tocIconSvg[$tocIconKey] : $tocIconSvg['lesson'];
                                                    ?></span>
                                                    <span class="toc-item-text"><span class="menu-title"><?php echo $page['page_name']; ?></span></span>
                                                </button>
                                            </form>
                                        </span>
                                    </li>
                                    <?php /* Completion indicator. Color.css makes #tocData > span a
                                             two-column grid (1fr + 12px) whose second cell is this
                                             <p>: .tickSymbol1 is the theme's visible blue check,
                                             .tickSymbol is the same slot kept invisible so every row
                                             stays aligned. Driven by the same $isvisited check the
                                             legacy menu used, i.e. the SCORM suspend data the
                                             controller already parsed - no new state is introduced. */ ?>
                                    <?php if (strlen($isvisited) > 0) { ?>
                                        <p class="tickSymbol1"></p>
                                    <?php } else { ?>
                                        <p class="tickSymbol" style="visibility:hidden;"></p>
                                    <?php } ?>
                                </span>
                        <?php }
                        } ?>
                    </ol>
                </div>
                <div id="transcript" style="display:none;">
                    <p><?php if (isset($transcript)) {
                            foreach ($transcript as $script) { ?>
                    <div class="transcript-item" style="font-size:12px;">
                        <?php echo $script['audio']; ?>
                    </div>
            <?php }
                        } ?></p>
                </div>
            </div>
            <div class="copyright">&copy; <?php echo date('Y'); ?> Touchstone</div>
            <?php /* Present in the theme's content.html straight after .copyright; sideBar.css
                     keeps it display:none, but emitted for structural parity. */ ?>
            <div class="line"><hr></div>
        </div>
        <div class="contentArea">
            <div id="clickableDiv" class="headingArea1">
                <img id="TmenuIcon" src="<?php echo base_url('assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/' . $theme . '/images/footer-menu/Menuopen.svg'); ?>"
                    alt="<?php echo $Menutitle ?>" role="button" tabindex="0" style="cursor:pointer;" onclick="toggleModernSidebar()">
            </div>
            <?php /* FSize19 is part of the theme's own header markup (content.html:68 -
                     `<div class="headingArea FSize19">`) and is the ONLY thing that sets the
                     title's desktop font-size: Color.css's .headingArea declares no font-size at
                     desktop widths, and .pageHeaderText (Color.css:179-182) sets only colour and
                     font-weight: 700. Without this class the span had nothing to inherit from but
                     body, landing on Bootstrap 3.3.7's `body { font-size: 14px }` - so the title
                     rendered 14px against the export's 19px. The <=1024px / <=600px media queries
                     already set .headingArea font-size themselves, so only desktop was affected. */ ?>
            <div class="headingArea FSize19">
                <span class="pageHeaderText"><?php echo $row['page_name']; ?></span>
                <div id="pagenoHeader"><?php echo $currentpage; ?> / <?php echo $totalPage; ?></div>
                <?php /* Exit/power control, same ids, asset and markup as the theme's own
                         content.html header (#exitCourseBtn > #ImagExitCourse), so Color.css
                         styles it. The theme calls its SCORM end(); Preview has no SCORM
                         session to close, so this just closes the preview window that
                         submitPostRequest() opened - no SCORM call is introduced. */ ?>
                <button id="exitCourseBtn" type="button" onclick="window.close();" aria-label="Exit Course" title="Exit Course">
                    <img id="ImagExitCourse"
                        src="<?php echo base_url('assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/' . $theme . '/images/footer-menu/' . rawurlencode('Exit button_02.png')); ?>"
                        alt="" style="height: 20px; vertical-align: middle;">
                </button>
            </div>
    <?php else: ?>
    <div class="hide_menu" id="menu-bg" style="display:none;"></div>
    <!-- <div class="next_instruction" id="next_instruction_id" style="display:none;">Click Next to Continue.</div> -->
    <div class="next_instruction" id="close_instruction_id" style="display:none;">Click Close to Continue.</div>
    <div class="row header-design">
        <div class="col-sm-12">
            <h5>
                <span>
                    <button type="submit"
                        style="padding: 10px; border: none; background: none; cursor: pointer;  margin: 0;  padding: 0;"
                        id="menu-btn" title="<?php echo $Menutitle ?>"><i class="fa fa-solid fa-bars"></i></button>
                </span>
                <span>
                    <button type="submit"
                        style="padding: 10px; border: none; background: none; cursor: pointer;  margin: 0;  padding: 0; display:none;"
                        id="menu-btn-close" title="<?php echo $Menutitle ?>"><i class="fa fa-close"></i></button>
                </span>
                <span style="padding-left: 10px;">
                    <?php echo $row['page_name']; ?>
                </span>
                <span style="float: right;">
                    <?php echo $currentpage ?> / <?php echo $totalPage; ?>
                </span>
            </h5>

        </div>
    </div>
    <div class="row main-content main_content_area">
        <div id="menu_details" style="display:none; ">
            <!-- Sidebar Logo -->
            <?php if (!empty($clientdata[0]['logo'])) {
            ?>
                <!-- <img src="<?php echo base_url('assets/assets/uploads/client_logo/' . $clientdata[0]['id_c'] . '/' . $clientdata[0]['logo']); ?>" alt="logo" height="40px" style="margin-top:23px;margin-bottom:23px;margin-left:50px"> -->
            <?php } else {
            } ?>
            <?php if ($coursedetails[0]['theme'] == '3' || $coursedetails[0]['theme'] == '6' || $coursedetails[0]['theme'] == '5') { ?>
                <img src="<?php echo base_url('assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/' . $theme . '/images/logo.png'); ?>"
                    alt="logo" height="120px">
            <?php } ?>
            <!-- Tabs for Menu and Transcript (Horizontal Layout) -->
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#menu"><?php echo $MenuName ?></a></li>
                <li><a data-toggle="tab" href="#transcript"><?php echo $TranscriptName ?></a></li>
            </ul>
            <div class="tab-content">
                <div id="menu" class="tab-pane fade in active menu">
                    <?php foreach ($pagedetails as $page) {
                        if ($page['sub_page_main'] == 0) {
                            $isvisited = array_search(abs($page['page_number']), $scorm_suspend_data_arr);
                    ?>
                            <form
                                action="<?php echo base_url('SCORM/Course_builder/review_course/launcher/1/' . $page['page_number']); ?>"
                                method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="course_id" value="<?php echo $page['fk_course_id']; ?>" />
                                <input type="hidden" name="page_number" value="<?php echo $page['page_number']; ?>" />
                                <input type="hidden" name="typeOfLaunch" value="<?php echo $typeOfLaunch ?>" />
                                <span> <button style="all: unset; cursor: pointer;">
                                        <p class="noDecoration">
                                            <?php
                                            echo $page['page_name'];
                                            ?>
                                        </p>
                                    </button>
                                </span>
                                <span class=" tick">
                                    <?php
                                    if (strlen($isvisited) > 0) {
                                        echo ' <i class="fa fa-check" aria-hidden="true"></i>';
                                    }
                                    ?>
                                </span>
                            </form>
                    <?php }
                    } ?>
                </div>
                <div id="transcript" class="tab-pane fade menu">
                    <p><?php if (isset($transcript)) {
                            foreach ($transcript as $script) { ?>
                    <div class="transcript-item" style="font-size:12px;">
                        <?php echo $script['audio']; ?>
                    </div>
            <?php }
                        } ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>
        <?php if ($theme === 'ModernTheme'): ?>
        <div class="pageContent">
        <?php endif; ?>
        <?php
        $subpage = $row['sub_page_main'];
        if ($subpage != 0) {
            $main_page = round($row['page_number']);
        ?>
            <div class="close_btn_container">
                <form action="<?php echo base_url('SCORM/Course_builder/review_course/launcher/2/' . $main_page); ?>"
                    method="POST"><?= csrf_field() ?>
                    <button style="all: unset; cursor: pointer;">
                        <div class="close_btn"></div>
                    </button>
                </form>
            </div>
        <?php } ?>
        <?php
        if ($row['type'] == 3) {
            // Path for the iframe content
            $html_path = base_url() . "assets/assets/uploads/SCORM_course_document/" . $course_id . "/" . $coursedetails[0]['createdon'] . "/assets/html/" . $row['page_id'] . "/Screen_01.html"; ?>
            <div class="iframe-container">
                <iframe class="responsive-iframe" src="<?php echo $html_path; ?>">
                    Your browser does not support iframes.
                </iframe>
            </div>
        <?php } elseif ($row['type'] == 1) {
            // Path for Articulate content
            $articulate_path = base_url() . "assets/assets/uploads/SCORM_course_document/" . $course_id . "/" . $coursedetails[0]['createdon'] . "/assets/Articulate/" . $row['page_id'] . "/story.html"; ?>
            <div class="iframe-container">
                <iframe class="responsive-iframe" src="<?php echo $articulate_path; ?>">
                    Your browser does not support iframes.
                </iframe>
            </div>
            <?php } elseif (($row['type'] == 2 || $row['type'] == 8 || $row['type'] == 9)) {
            // print_r($row['video_upload']);
            if (isset($pageVideo[0]['filename']) && strlen($pageVideo[0]['filename']) > 5) {
                $video_path = base_url() . "assets/assets/uploads/SCORM_course_document/" . $course_id . "/" . $coursedetails[0]['createdon'] . "/assets/video/" . $pageVideo[0]['filename'];
                if (isset($pageVtt[0]['filename'])) {
                    $vtt_path = base_url() . "assets/assets/uploads/SCORM_course_document/" . $course_id . "/" . $coursedetails[0]['createdon'] . "/assets/vtt/" . $pageVtt[0]['filename'];
                }

            ?>
                <?php if ($row['type'] == 8) {
                    if ($return_page == 2) { ?>
                        <div class="interactive_bg" id="interactive-btn">
                        <?php } else { ?>
                            <div class="interactive_bg" id="interactive-btn" style="display: none;">
                            <?php } ?>
                            <div class="table-center">
                                <?php
                                $subpages_Count = count($sub_page_content);
                                if ($subpages_Count < 4) {
                                    echo '<br><br><br>';
                                    echo '<table  style="margin-left: 30%; margin-top: 5%; width: 40%;">   <tr>';
                                    echo '<td style="text-align: center; padding: 15px;">';
                                } elseif ($subpages_Count > 3 && $subpages_Count < 7) {
                                    echo '<br><br><br><br>';
                                    echo '<table  style="margin-left: 10%; width: 80%;">   <tr>';
                                    echo '<td style="text-align: center; padding: 15px;" colspan="2">';
                                } else {
                                    echo '<table style=" margin-top: 8%; width: 100%;">   <tr>';
                                    echo '<td style="text-align: center; padding: 15px;" colspan="3">';
                                }
                                ?>
                                <span style="font-size:25px;"><i>Click each <strong>button</strong> to learn more. </i></span>
                                </td>
                                </tr>
                            </div>
                            </br>
                            <?php
                            $row_counter = 0;
                            if ($subpages_Count > 0) {
                                echo ' ';
                                foreach ($sub_page_content as $subPages) {
                                    if ($row_counter == 0) {
                                        echo '<tr>';
                                    }
                                    $row_counter++;
                                    $isvisited_sub = array_search(abs($subPages['page_number']), $scorm_suspend_data_arr);

                            ?>
                                    <td style="text-align: center; width:30vw; padding: 15px;">
                                        <form class="form-horizontal mb-2"
                                            action="<?php echo base_url('SCORM/Course_builder/review_course/launcher/1/' . $subPages['page_number']) ?>"
                                            method="POST"><?= csrf_field() ?>
                                            <button type="submit" class="sub_page_btn">
                                                <?php
                                                echo $subPages['page_name'];
                                                ?></button>
                                            <?php if ($isvisited_sub) {
                                                echo '<div class="tick-subpage"><i class="fa fa-check-circle" aria-hidden="true"></i>';
                                                echo "</div>";
                                            }
                                            ?>
                                        </form>
                                    </td>
                            <?php
                                    if ($subpages_Count < 4) {
                                        $row_counter = 0;
                                        echo '</tr>';
                                    } elseif ($subpages_Count > 3 && $subpages_Count < 7) {
                                        if ($row_counter > 1) {
                                            $row_counter = 0;
                                            echo '</tr>';
                                        }
                                    } else {
                                        if ($row_counter > 2) {
                                            $row_counter = 0;
                                            echo '</tr>';
                                        }
                                    }
                                }
                                echo '</tr></table>';
                            }
                            ?>
                            </div>
                        </div>
                    <?php }
                if ($return_page == 2) { ?>
                        <video class="object-fit-contain" src="<?php echo $video_path; ?>"
                            style="display:none; width: 100%; height: 86vh;" id="vidArea" controls
                            controlsList="nodownload noplaybackrate" disablePictureInPicture>
                        <?php } else { ?>
                            <video class="object-fit-contain" src="<?php echo $video_path; ?>"
                                style=" display: block; width: 100%; height: 86vh;" id="vidArea" controls
                                controlsList="nodownload noplaybackrate" autoplay="autoplay" disablePictureInPicture>
                            <?php } ?>
                            <?php if (isset($pageVtt[0]['filename'])) { ?>
                                <track id="englishTrack" kind="captions" src="<?php echo isset($vtt_path) ? $vtt_path : ''; ?>"
                                    srclang="en" label="English" default>
                            <?php } ?>
                            </video>
                        <?php

                    } else {
                        echo "Page is under development.";
                    }
                } elseif ($row['type'] == 5) { ?>

                        <?php /* The theme's own SCQ.html/MCQ.html wrap the card in #quizContainer, and that
                                 is the element SCQ|MCQ_style.css hangs the OUTER gutter on
                                 (`padding: 20px clamp(18px,3vw,34px) 16px` + its own frame border/shadow,
                                 SCQ_style.css:276-287). Without it .questionContainer ran edge-to-edge in
                                 .pageContent -- its border flush against the viewport, so the card did not
                                 read as a card at all. Reusing the theme's own element rather than restating
                                 its padding anywhere. Same wrapper already used by the Quiz flow's views. */ ?>
                        <?php if ($isModernThemeQ): ?><div id="quizContainer"><?php endif; ?>
                        <div class="question_bg<?php echo $isModernThemeQ ? ' questionContainer' : ''; ?>">
                            <?php if (!$isModernThemeQ) { echo '<br><br>'; } ?>
                            <table style="width: 100%;">
                                <tr>
                                    <td style="<?php echo $isModernThemeQ ? 'display:none;' : 'width: 10%;  vertical-align: top;  height: 70vh;'; ?>">
                                        <div class="question_bg_question">
                                            <img style="width: 80px;"
                                                src="<?php echo base_url('assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/' . $theme . '/images/Bulb.svg'); ?>">
                                        </div>
                                    </td>
                                    <td style="<?php echo $isModernThemeQ ? 'vertical-align: top;' : 'width: 80%; vertical-align: top;  height: 70vh;'; ?>">
                                        <div class="question_base">

                                            <div class="question_stem<?php echo $isModernThemeQ ? ' question FSize16' : ''; ?>">
                                                <?php echo $question['question']; ?>
                                            </div>
                                            <div class="question_instructions<?php echo $isModernThemeQ ? ' redtext instext FSize16' : ''; ?>">
                                                <i><?php echo $kyuselectscqdescrip ?></i>
                                            </div>
                                        </div>
                                        <div class="option_container<?php echo $isModernThemeQ ? ' options' : ''; ?>">
                                            <form id="radioForm"<?php echo $isModernThemeQ ? ' style="display:contents;"' : ''; ?>><?= csrf_field() ?>
                                                <?php if (isset($question_options)) {
                                                    $count = 1;
                                                    foreach ($question_options as $options) {
                                                        // print_r((strlen($options['values'])));
                                                        if (strlen($options['values']) > 1) { ?>
                                                            <div class="form-check<?php echo $isModernThemeQ ? ' answer FSize16' : ''; ?>"><?php if ($isModernThemeQ) { echo '<span class="tickSymbol"></span>'; } ?>

                                                                <?php $correct = $options['truefalse'];
                                                                if ($correct == 1) {
                                                                ?>
                                                                    <input class="form-check-input<?php echo $isModernThemeQ ? ' radioBut clicken' : ''; ?>" type="radio" name="RadioFeedback"
                                                                        id="RadioFeedback<?php echo $count; ?>" value="feedback_correct">
                                                                    <?php if ($typeOfLaunch != 2) { ?>
                                                                        <label class="form-check-label<?php echo $isModernThemeQ ? ' clicken' : ' options options_cursor'; ?>"
                                                                            id="correct<?php echo $count; ?>" value="1" onchange="toggleDiv()"
                                                                            for="RadioFeedback<?php echo $count; ?>">
                                                                        <?php } else { ?>
                                                                            <label class="form-check-label<?php echo $isModernThemeQ ? ' clicken' : ' options options_cursor'; ?>"
                                                                                id="correct<?php echo $count; ?>" value="1" onchange="toggleDiv()"
                                                                                for="RadioFeedback<?php echo $count; ?>">
                                                                            <?php } ?>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                            <input class="form-check-input<?php echo $isModernThemeQ ? ' radioBut clicken' : ''; ?>" type="radio" name="RadioFeedback"
                                                                                id="RadioFeedback<?php echo $count; ?>" value="feedback_wrong">
                                                                            <label class="form-check-label<?php echo $isModernThemeQ ? ' clicken' : ' options options_cursor'; ?>"
                                                                                id="incorrect<?php echo $count; ?>" value="0" onchange="toggleDiv()"
                                                                                for="RadioFeedback<?php echo $count; ?>">
                                                                            <?php
                                                                        }
                                                                            ?>
                                                                            <?php echo $options['values']; ?>
                                                                            </label>
                                                            </div>
                                                            <?php $count++; ?>
                                                            <?php // echo $options['score']; 
                                                            ?>
                                                <?php }
                                                    }
                                                } ?>

                                                <button id="submit-btn" class="<?php echo $isModernThemeQ ? 'btn ColorSet_CR FSize16' : 'question_sub_btn'; ?>"><?php echo $kyusubmit ?></button>
                                            </form>
                                            <button id="retry-btn" class="<?php echo $isModernThemeQ ? 'retrybtn ColorSet_CR FSize16' : 'question_sub_btn'; ?>" onclick="retry()"
                                                class="btn btn-warning" style="display: none;">Try Again</button>
                                            <div class="<?php echo $isModernThemeQ ? 'feedback correct Correct_CR FSize16' : 'feedback_correct'; ?>" id="correct_feedback"<?php echo $isModernThemeQ ? ' style="display:none;"' : ''; ?>><?php echo $question['correct']; ?>
                                            </div>
                                            <div class="<?php echo $isModernThemeQ ? 'feedback incorrect Incorrect_CR FSize16' : 'feedback_wrong'; ?>" id="incorrect1_feedback"<?php echo $isModernThemeQ ? ' style="display:none;"' : ''; ?>>
                                                <?php echo !empty($question['incorrect2']) ? $question['incorrect2'] : 'Sorry! That is not the correct answer. Click Try Again.' ?>
                                            </div>
                                            <div class="<?php echo $isModernThemeQ ? 'feedback incorrect Incorrect_CR FSize16' : 'feedback_wrong'; ?>" id="incorrect_feedback"<?php echo $isModernThemeQ ? ' style="display:none;"' : ''; ?>>
                                                <?php echo $question['incorrect']; ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <?php if ($isModernThemeQ): ?></div><?php /* closes #quizContainer */ ?><?php endif; ?>
                    <?php } elseif ($row['type'] == 6) { ?>
                        <!-- <div class="question_bg<?php echo $isModernThemeQ ? ' questionContainer' : ''; ?>">
                    <br><br><br><br>
                    <table style="width: 100%">
                        <tr>
                            <td style="width: 50%;  vertical-align: top;   height: 70vh;">
                                <div class="question_bg_question">
                                    <img style="width: 80px;"
                                        src="<?php echo base_url('assets/assets/img/question.png'); ?>">
                                </div>
                                <div class="question_base">
                                    <div class="question_stem<?php echo $isModernThemeQ ? ' question FSize16' : ''; ?>">
                                        <?php echo $question['question']; ?>
                                    </div>
                                    <div class="question_instructions<?php echo $isModernThemeQ ? ' redtext instext FSize16' : ''; ?>">
                                        <i><?php echo $kyuselectmcqdescrip ?></i>
                                    </div>
                                </div>
                            </td>
                            <td style="width: 50%;  vertical-align: top;   height: 70vh;">
                                <div class="option_container<?php echo $isModernThemeQ ? ' options' : ''; ?>">
                                    <form id="checkboxForm">
                                        <?php if (isset($question_options)) {
                                            $count = 1;
                                            $totalcorrect = 0;
                                            foreach ($question_options as $options) { ?>
                                                <div class="form-check">
                                                    <?php
                                                    $correct = $options['truefalse'];
                                                    if ($correct == 1) {
                                                        $totalcorrect++;
                                                    ?>
                                                        <input class="form-check-input<?php echo $isModernThemeQ ? ' checkbox' : ''; ?>" type="checkbox" name="checkanswer"
                                                            id="exampleCheckbox<?php echo $count; ?>" value="feedback_correct">
                                                        <?php if ($typeOfLaunch != 2) { ?>
                                                            <label class="form-check-label<?php echo $isModernThemeQ ? ' clicken' : ' options options_cursor'; ?>"
                                                                id="multi<?php echo $count; ?>" for="exampleCheckbox<?php echo $count; ?>">
                                                            <?php } else { ?>
                                                                <label class="form-check-label<?php echo $isModernThemeQ ? ' clicken' : ' options options_cursor'; ?>"
                                                                    id="multi<?php echo $count; ?>"
                                                                    for="exampleCheckbox<?php echo $count; ?>">
                                                                <?php } ?>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <input class="form-check-input<?php echo $isModernThemeQ ? ' checkbox' : ''; ?>" type="checkbox" name="checkanswer"
                                                                    id="exampleCheckbox<?php echo $count; ?>" value="feedback_wrong">
                                                                <label class="form-check-label<?php echo $isModernThemeQ ? ' clicken' : ' options options_cursor'; ?>"
                                                                    id="multi<?php echo $count; ?>"
                                                                    for="exampleCheckbox<?php echo $count; ?>">
                                                                    <?php
                                                                }
                                                                    ?>
                                                                <?php echo $options['values']; ?>
                                                            </label>
                                                </div>
                                                <?php $count++; ?>

                                            <?php } ?>
                                            <input type="hidden" id="totalcorrect" name="totalcorrect2"
                                                value="<?php echo $totalcorrect; ?>">
                                        <?php } ?>
                                        <br>
                                        <button id="submit-btn" class="<?php echo $isModernThemeQ ? 'btn ColorSet_CR FSize16' : 'question_sub_btn'; ?>"><?php echo $kyusubmit ?></button>
                                    </form>
                                    <button id="retry-btn" class="<?php echo $isModernThemeQ ? 'retrybtn ColorSet_CR FSize16' : 'question_sub_btn'; ?>" onclick="retry_multi()"
                                        style="display: none;" class="btn btn-warning">Try Again</button>
                                    <div class="<?php echo $isModernThemeQ ? 'feedback correct Correct_CR FSize16' : 'feedback_correct'; ?>" id="correct_feedback"<?php echo $isModernThemeQ ? ' style="display:none;"' : ''; ?>><?php echo $question['correct']; ?>
                                    </div>
                                    <div class="<?php echo $isModernThemeQ ? 'feedback incorrect Incorrect_CR FSize16' : 'feedback_wrong'; ?>" id="incorrect1_feedback"<?php echo $isModernThemeQ ? ' style="display:none;"' : ''; ?>>
                                        <?php echo !empty($question['incorrect2']) ? $question['incorrect2'] : 'Sorry! That is not the correct answer. Click Try Again.' ?>
                                    </div>
                                    <div class="<?php echo $isModernThemeQ ? 'feedback incorrect Incorrect_CR FSize16' : 'feedback_wrong'; ?>" id="incorrect_feedback"<?php echo $isModernThemeQ ? ' style="display:none;"' : ''; ?>>
                                        <?php echo $question['incorrect']; ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div> -->
                        <?php /* Same theme wrapper as the SCQ branch above -- see the comment there. */ ?>
                        <?php if ($isModernThemeQ): ?><div id="quizContainer"><?php endif; ?>
                        <div class="question_bg<?php echo $isModernThemeQ ? ' questionContainer' : ''; ?>">
                            <?php if (!$isModernThemeQ) { echo '<br><br>'; } ?>
                            <table style="width: 100%;">
                                <tr>
                                    <!-- Image Column (10%) -->
                                    <td style="<?php echo $isModernThemeQ ? 'display:none;' : 'width: 10%; vertical-align: top; height: 70vh;'; ?>">
                                        <div class="question_bg_question">
                                            <img style="width: 80px;"
                                                src="<?php echo base_url('assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/' . $theme . '/images/Bulb.svg'); ?>">
                                        </div>
                                    </td>

                                    <!-- Question + Options Column (80%) -->
                                    <td style="<?php echo $isModernThemeQ ? 'vertical-align: top;' : 'width: 80%; vertical-align: top; height: 70vh;'; ?>">
                                        <div class="question_base">
                                            <div class="question_stem<?php echo $isModernThemeQ ? ' question FSize16' : ''; ?>">
                                                <?php echo $question['question']; ?>
                                            </div>
                                            <div class="question_instructions<?php echo $isModernThemeQ ? ' redtext instext FSize16' : ''; ?>">
                                                <i><?php echo $kyuselectmcqdescrip; ?></i>
                                            </div>
                                        </div>

                                        <div class="option_container<?php echo $isModernThemeQ ? ' options' : ''; ?>">
                                            <form id="checkboxForm"<?php echo $isModernThemeQ ? ' style="display:contents;"' : ''; ?>><?= csrf_field() ?>
                                                <?php
                                                if (isset($question_options)) {
                                                    $count = 1;
                                                    $totalcorrect = 0;
                                                    foreach ($question_options as $options) {
                                                        if (strlen($options['values']) > 1) {
                                                            $correct = $options['truefalse'];
                                                            if ($correct == 1) {
                                                                $totalcorrect++;
                                                ?>
                                                                <div class="form-check<?php echo $isModernThemeQ ? ' answer FSize16' : ''; ?>"><?php if ($isModernThemeQ) { echo '<span class="tickSymbol"></span>'; } ?>
                                                                    <input class="form-check-input<?php echo $isModernThemeQ ? ' checkbox' : ''; ?>" type="checkbox" name="checkanswer"
                                                                        id="exampleCheckbox<?php echo $count; ?>" value="feedback_correct">
                                                                    <label class="form-check-label<?php echo $isModernThemeQ ? ' clicken' : ' options options_cursor'; ?>"
                                                                        id="multi<?php echo $count; ?>" for="exampleCheckbox<?php echo $count; ?>">
                                                                        <?php echo $options['values']; ?>
                                                                    </label>
                                                                </div>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <div class="form-check<?php echo $isModernThemeQ ? ' answer FSize16' : ''; ?>"><?php if ($isModernThemeQ) { echo '<span class="tickSymbol"></span>'; } ?>
                                                                    <input class="form-check-input<?php echo $isModernThemeQ ? ' checkbox' : ''; ?>" type="checkbox" name="checkanswer"
                                                                        id="exampleCheckbox<?php echo $count; ?>" value="feedback_wrong">
                                                                    <label class="form-check-label<?php echo $isModernThemeQ ? ' clicken' : ' options options_cursor'; ?>"
                                                                        id="multi<?php echo $count; ?>" for="exampleCheckbox<?php echo $count; ?>">
                                                                        <?php echo $options['values']; ?>
                                                                    </label>
                                                                </div>
                                                    <?php
                                                            }
                                                            $count++;
                                                        }
                                                    }
                                                    ?>
                                                    <input type="hidden" id="totalcorrect" name="totalcorrect2"
                                                        value="<?php echo $totalcorrect; ?>">
                                                <?php } ?>

                                                <?php if (!$isModernThemeQ): ?><br><?php endif; ?>
                                                <button id="submit-btn" class="<?php echo $isModernThemeQ ? 'btn ColorSet_CR FSize16' : 'question_sub_btn'; ?>"><?php echo $kyusubmit ?></button>
                                            </form>

                                            <button id="retry-btn" class="<?php echo $isModernThemeQ ? 'retrybtn ColorSet_CR FSize16' : 'question_sub_btn'; ?>" onclick="retry_multi()"
                                                style="display: none;">Try Again</button>

                                            <div class="<?php echo $isModernThemeQ ? 'feedback correct Correct_CR FSize16' : 'feedback_correct'; ?>" id="correct_feedback"<?php echo $isModernThemeQ ? ' style="display:none;"' : ''; ?>><?php echo $question['correct']; ?>
                                            </div>
                                            <div class="<?php echo $isModernThemeQ ? 'feedback incorrect Incorrect_CR FSize16' : 'feedback_wrong'; ?>" id="incorrect1_feedback"<?php echo $isModernThemeQ ? ' style="display:none;"' : ''; ?>>
                                                <?php echo !empty($question['incorrect2']) ? $question['incorrect2'] : 'Sorry! That is not the correct answer. Click Try Again.'; ?>
                                            </div>
                                            <div class="<?php echo $isModernThemeQ ? 'feedback incorrect Incorrect_CR FSize16' : 'feedback_wrong'; ?>" id="incorrect_feedback"<?php echo $isModernThemeQ ? ' style="display:none;"' : ''; ?>>
                                                <?php echo $question['incorrect']; ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <?php if ($isModernThemeQ): ?></div><?php /* closes #quizContainer */ ?><?php endif; ?>

                    <?php } elseif ($row['type'] == 4) { ?>
                        <?php
                        $quiz_question_path = base_url() . "SCORM/Course_builder/Review_course/quizStartPage/" . $course_id . "/" . $page_id;
                        ?>
                        <div class="iframe-container">
                            <iframe class="responsive-iframe" src="<?php echo $quiz_question_path; ?>">
                                Your browser does not support iframes.
                            </iframe>
                        </div>
                    <?php } elseif ($row['type'] == 10 || $row['type'] == 11 || $row['type'] == 12) {
                        $pageImageFile = $row['page_image'] ?? '';
                        $imageAlt = $row['image_alt'] ?? '';
                        $pageImageUrl = '';
                        $hasPageImage = false;
                        if ($pageImageFile !== '') {
                            $pageImageDiskPath = FCPATH . "assets/assets/uploads/SCORM_course_document/" . $course_id . "/" . $coursedetails[0]['createdon'] . "/assets/html/" . $page_id . "/" . $pageImageFile;
                            if (file_exists($pageImageDiskPath)) {
                                $hasPageImage = true;
                                $pageImageUrl = base_url() . "assets/assets/uploads/SCORM_course_document/" . $course_id . "/" . $coursedetails[0]['createdon'] . "/assets/html/" . $page_id . "/" . $pageImageFile;
                            }
                        }
                        ?>
                        <div class="text-page-container" style="padding: 30px; overflow-y: auto; height: 86vh;">
                            <?php if ($row['type'] == 11 && $hasPageImage) { ?>
                                <div class="row align-items-start">
                                    <div class="col-md-6"><img src="<?php echo $pageImageUrl; ?>" alt="<?php echo esc($imageAlt); ?>" class="img-fluid rounded"></div>
                                    <div class="col-md-6"><?php echo $row['content'] ?? ''; ?></div>
                                </div>
                            <?php } elseif ($row['type'] == 12 && $hasPageImage) { ?>
                                <div class="row align-items-start">
                                    <div class="col-md-6"><?php echo $row['content'] ?? ''; ?></div>
                                    <div class="col-md-6"><img src="<?php echo $pageImageUrl; ?>" alt="<?php echo esc($imageAlt); ?>" class="img-fluid rounded"></div>
                                </div>
                            <?php } else { ?>
                                <div><?php echo $row['content'] ?? ''; ?></div>
                            <?php } ?>
                        </div>
                    <?php } ?>
    <?php if ($theme === 'ModernTheme'): ?>
    <?php /* closes .pageContent then .contentArea; Color.css expects .footer to be a direct
             child of .wholeContainer, with each control a direct <div> child of .footer. */ ?>
    </div>
    </div>
    <div class="footer">
        <?php $subpage = $row['sub_page_main'];
        if ($subpage == 0) { ?>
            <?php /* Matches the theme's own footer: the arrow is a CSS background-image on
                     .prevClass / .nextClass (footer.css), sized by Color.css, not an <img>.
                     With an <img> here, .footer > div's filter (brightness .62, and .5 on
                     hover) darkened the icon and turned the light #f1f4f9 hover fill into a
                     grey block. The button just fills the 42px cell so the whole control
                     stays clickable; the POST navigation is unchanged. */ ?>
            <?php if ($currentpage > 1) { ?>
                <div id="prev" class="prevClass">
                    <form action="<?php echo base_url('SCORM/Course_builder/review_course/launcher/1/' . $prev_page_number); ?>"
                        method="POST" style="display:contents;"><?= csrf_field() ?>
                        <button type="submit" title="<?php echo $Prevtitle; ?>" aria-label="<?php echo $Prevtitle; ?>"
                            style="display:block; width:100%; height:100%; margin:0; padding:0; background:none; border:0; cursor: pointer;"></button>
                    </form>
                </div>
            <?php } ?>
            <?php if ($next_page_number <= $totalPage) { ?>
                <div id="next" class="nextClass">
                    <form action="<?php echo base_url('SCORM/Course_builder/review_course/launcher/1/' . $next_page_number); ?>"
                        method="POST" style="display:contents;"><?= csrf_field() ?>
                        <button type="submit" title="<?php echo $NextTitle; ?>" aria-label="<?php echo $NextTitle; ?>"
                            style="display:block; width:100%; height:100%; margin:0; padding:0; background:none; border:0; cursor: pointer;"></button>
                    </form>
                </div>
            <?php } ?>
        <?php } ?>
        <?php /* Learning Aids control. The theme renders this from Json/config.json via Angular
                 (id "resource1", class "resourceClass" - the icon itself is a CSS background-image
                 on .resourceClass in footer.css:136-138, resource.svg), and its own main.js hides
                 it when the course has no resources (`if (ResourceBool) {...} else {
                 document.getElementById("resource1").style.display = "none" }` - main.js:644-648,
                 where ResourceBool comes from Template.json's "Resource" flag). Export sets that
                 flag from exactly the model call now mirrored in the controller, so gating the
                 element's existence on the same non-empty test reproduces the theme's own
                 visibility rule without emitting a dead control. */ ?>
        <?php if (!empty($getAllFileOwner)) { ?>
            <div id="resource1" class="resourceClass" role="button" tabindex="0"
                title="<?php echo isset($LearningAidsTitle) ? esc($LearningAidsTitle) : 'Learning Aids'; ?>"
                aria-label="<?php echo isset($LearningAidsTitle) ? esc($LearningAidsTitle) : 'Learning Aids'; ?>"></div>
        <?php } ?>
    <?php else: ?>
    </div>
    <div class="row nav-btn-container">
        <?php $subpage = $row['sub_page_main'];
        if ($subpage == 0) { ?>
            <span style="float: right;">
                <div class="next_btn_container" id="next_btn_container">
                    <?php if ($next_page_number <= $totalPage) { ?>
                        <form action="<?php echo base_url('SCORM/Course_builder/review_course/launcher/1/' . $next_page_number); ?>"
                            method="POST"><?= csrf_field() ?>
                            <button style="all: unset; cursor: pointer;">
                                <img style="height:20px"
                                    src="<?php echo base_url('assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/' . $theme . '/images/footer-menu/next.svg'); ?> "
                                    title="<?php echo $NextTitle; ?>">
                            </button>
                        </form>
                    <?php } ?>
                </div>
            </span>
            <span style="float: right;">
                <div class="prev_btn_container" id="prev_btn_container">
                    <?php
                    if ($currentpage > 1) { ?>
                        <form action="<?php echo base_url('SCORM/Course_builder/review_course/launcher/1/' . $prev_page_number); ?>"
                            method="POST"><?= csrf_field() ?>
                            <button style="all: unset; cursor: pointer;">
                                <img style="height:20px"
                                    src="<?php echo base_url('assets/assets/uploads/SCORM_course_document/scorm_libraries/export_themes/' . $theme . '/images/footer-menu/previous.svg'); ?>"
                                    title="<?php echo $Prevtitle; ?>">
                            </button>
                        </form>
                    <?php } ?>
                </div>
            </span>
        <?php } ?>
    </div>
</div>
    <?php endif; ?>

<?php if ($typeOfLaunch != 2) { ?>
    <!--            <div class="col-sm-3 feedbackwindow">
                <div class="row">
                    <div class="col-md-12 feedback-design">
                        Feedback
                    </div>
                </div>

                <iframe id="Iframe" class="feedback_window" frameBorder="0" height="100%" width="100%" src="<?php echo base_url('SCORM/Course_builder/review_course/feedback_launcher/' . $course_id . '/' . $page_id . '/' . $typeOfLaunch) ?>" title="feedback"></iframe>
            </div> -->
<?php } ?>


<script type="text/javascript">
    function callpage(pageid) {
        var baseUrl = "<?php echo base_url('SCORM/Course_builder/review_course/scorm_feedback_launcher/' . $course_id . '/') ?>";
        var newURL = baseUrl + "/" + pageid + '/1';
        document.getElementById("feedback_frame").src = newURL;
    }

    function openFeedback() {

        document.getElementById("bg_black").style.display = "block";
        document.getElementById("feedbackwin").style.display = "block";
        document.getElementById("open_btn").style.display = "none";
        document.getElementById("close_btn").style.display = "block";
    }

    function closeFeedback() {
        document.getElementById("bg_black").style.display = "none";
        document.getElementById("feedbackwin").style.display = "none";
        document.getElementById("open_btn").style.display = "block";
        document.getElementById("close_btn").style.display = "none";
    }
    //     function beginButCall() {
    //  }
    // function audioButCall() {
    //     alert("audioButCall");
    // }
</script>
<script>
    function audioButCall() {
        <?php $pagetype = array_column($pagedetails, 'type');
        if (in_array('9', $pagetype)) { ?>
            const nextPageNumber = <?php echo $next_page_number; ?>;
            const totalPage = <?php echo $totalPage; ?>;
            const baseUrl = "<?php echo base_url('SCORM/Course_builder/review_course/launcher/1/'); ?>";

            // Check if the next page is valid
            if (nextPageNumber <= totalPage) {
                // Construct the URL for the next page
                const nextPageUrl = baseUrl + nextPageNumber;

                // Redirect the user to the next page
                window.location.href = nextPageUrl;
            } else {
                console.log("No more pages available.");
            }
        <?php } ?>
    }
</script>
<script>
    function beginButCall() {
        // PHP variables passed into JS
        <?php $pagetype = array_column($pagedetails, 'type');
        if (in_array('9', $pagetype)) { ?>
            const nextPageNumber = <?php echo $next_page_number + 1; ?>;
        <?php } else { ?>
            const nextPageNumber = <?php echo $next_page_number; ?>;
        <?php } ?>
        const totalPage = <?php echo $totalPage; ?>;
        const baseUrl = "<?php echo base_url('SCORM/Course_builder/review_course/launcher/1/'); ?>";

        // Check if the next page is valid
        if (nextPageNumber <= totalPage) {
            // Construct the URL for the next page
            const nextPageUrl = baseUrl + nextPageNumber;

            // Redirect the user to the next page
            window.location.href = nextPageUrl;
        } else {
            console.log("No more pages available.");
        }
    }
</script>
<script>
    function sendTime() {
        fetch("<?= base_url('save-time') ?>", {
            method: "POST",
            keepalive: true
        });
    }

    window.addEventListener("beforeunload", sendTime);

    document.addEventListener("visibilitychange", function() {
        if (document.visibilityState === "hidden") {
            sendTime();
        }
    });
</script>
<script>
    let lastTime = Date.now();

    setInterval(() => {
        let now = Date.now();
        let diff = Math.floor((now - lastTime) / 1000); // seconds
        lastTime = now;

        console.log("sending time...", diff);

        fetch("<?= base_url('update-time') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    time: diff // âœ… ONLY DELTA
                })
            })
            .then(res => res.text())
            .then(data => console.log("API response:", data))
            .catch(err => console.error("API error:", err));

    }, 5000);
</script>
