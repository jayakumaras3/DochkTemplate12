<?php $isModernTheme = isset($coursedetails) && isset($coursedetails[0]['theme']) && in_array((string) $coursedetails[0]['theme'], ['8', '9'], true); ?>
</div>
</div>
<?php
/* Guarded so this block simply does not render if either piece of data is absent, rather than
   erroring - footer.php is shared by several controller actions and only launcher() supplies
   these. page_video_view.php gates the footer icon on the same !empty($getAllFileOwner). */
$mtResourceCreatedOn = isset($getCourseData[0]['createdon']) ? $getCourseData[0]['createdon'] : null;
$mtShowResources = $isModernTheme && !empty($getAllFileOwner) && $mtResourceCreatedOn !== null && isset($course_id);
?>
<?php if ($mtShowResources) { ?>
    <?php /* Learning Aids panel. Placed here, after .wholeContainer closes, because that is
             exactly where the theme keeps it -- content.html:159 has #resourceArea as a
             body-level sibling of the player, not nested inside it, which is what lets its
             position:absolute / z-index:10001 overlay cover the whole player (Color.css:781-792).
             Structure and classes are the markup the theme's own main.js generateResourceList()
             builds (main.js:672-711); every class used here is already defined in the theme's
             Color.css:830-928 / content.css, both of which Preview links for ModernTheme, so no
             new CSS is introduced. Links resolve to the live upload location of the same PDFs
             Export copies into the package as assets/PDF/<folder> -- identical URL construction
             to courses_transcript_pdf.php:85. Resource data itself is untouched. */ ?>
    <div id="resourceArea">
        <div id="resourceContent">
            <div class="resource-modal-header">
                <div class="resource-modal-title" id="resourceModalTitle">
                    <svg class="resource-modal-icon-brand" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" y1="13" x2="8" y2="13" />
                        <line x1="16" y1="17" x2="8" y2="17" />
                        <polyline points="10 9 9 9 8 9" />
                    </svg>
                    <?php echo isset($LearningAidsTitle) ? esc($LearningAidsTitle) : 'Learning Aids'; ?>
                </div>
                <button class="resource-modal-close" id="resourceModalClose" aria-label="Close" tabindex="0">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>
            <div class="resource-modal-body">
                <?php foreach ($getAllFileOwner as $pdf) {
                    if (empty($pdf['folder'])) { continue; }
                    $pdfUrl = base_url('assets/assets/uploads/SCORM_course_document/' . $course_id . '/' . $mtResourceCreatedOn . '/assets/PDF/' . rawurlencode($pdf['folder']));
                ?>
                    <a class="resource-modal-item" target="_blank" href="<?php echo $pdfUrl; ?>" rel="noopener noreferrer">
                        <div class="resource-item-icon-wrap" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                            </svg>
                        </div>
                        <span class="resource-item-label"><?php echo esc($pdf['description']); ?></span>
                        <svg class="resource-item-arrow" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="5" y1="12" x2="19" y2="12" />
                            <polyline points="12 5 19 12 12 19" />
                        </svg>
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
    <script>
        /* Open/close only. The theme drives this from its Angular footer controller
           (footerBarController.js:478-481 -> #resourceArea display block) and closes via
           main.js backToMainPage() (main.js:210); Preview runs neither, so the same two
           state changes are wired directly. Nothing else is affected. */
        (function () {
            var trigger = document.getElementById('resource1');
            var overlay = document.getElementById('resourceArea');
            var closeBtn = document.getElementById('resourceModalClose');
            if (!trigger || !overlay) { return; }

            function openPanel() { overlay.style.display = 'block'; }
            function closePanel() { overlay.style.display = 'none'; }

            trigger.addEventListener('click', openPanel);
            trigger.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); openPanel(); }
            });
            if (closeBtn) { closeBtn.addEventListener('click', closePanel); }
            overlay.addEventListener('click', function (e) {
                if (e.target === overlay) { closePanel(); }
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && overlay.style.display === 'block') { closePanel(); }
            });
        })();
    </script>
<?php } ?>
</body>

</html>
<?php if ($isModernTheme): ?>
<script>
    function toggleModernSidebar() {
        var panel = document.getElementById('Tmenu');
        var wasOpen = panel && panel.style.display === 'block';

        // Prefer the theme's own menu controller (jMain.js), already loaded by header.php.
        try {
            if (typeof TtoggleMenu === 'function') {
                TtoggleMenu();
            }
        } catch (e) {
            // Parts of jMain.js reach into Angular scope, which Preview never
            // bootstraps. Swallow and fall through to the manual toggle below.
        }

        // Fallback: if the theme's controller did not flip the panel, do it here using
        // the theme's own CSS hooks, so no new styling is introduced.
        if (panel && (panel.style.display === 'block') === wasOpen) {
            var open = !wasOpen;
            panel.style.display = open ? 'block' : 'none';
            var whole = document.querySelector('.wholeContainer');
            if (whole) {
                whole.classList.toggle('menu-open', open);
            }
            document.body.classList.toggle('menu-open-body', open);
            document.documentElement.classList.toggle('menu-open-body', open);
            if (typeof Tmenu !== 'undefined') {
                Tmenu = open;
            }
        }

        var v = document.getElementById('vidArea');
        if (v) {
            (panel && panel.style.display === 'block') ? v.pause() : v.play();
        }

        /* Keep aria-expanded truthful, which the theme does via
           aria-expanded="{{sb.menuOpen ? 'true' : 'false'}}" (content.html:65). Read back from
           the panel rather than from a local flag so it stays correct no matter which of the two
           paths above actually flipped it. */
        var menuIcon = document.getElementById('TmenuIcon');
        if (menuIcon) {
            menuIcon.setAttribute('aria-expanded', (panel && panel.style.display === 'block') ? 'true' : 'false');
        }
    }

    /* Keyboard activation for the menu icon. It is an <img role="button">, not a natively
       activatable control, so its onclick fires on mouse only - the theme pairs its ng-click
       with ng-keydown for exactly this reason (content.html:58-59). Enter and Space are the
       two keys the button role is required to support. */
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Enter' && e.key !== ' ') { return; }
        var icon = e.target && e.target.id === 'TmenuIcon' ? e.target : null;
        if (!icon) { return; }
        /* Same guard the theme's own keyboard handler applies before acting
           (sideBarController.js:424-426): when the menu is disabled, Color.css:129-133 sets
           pointer-events:none on the icon, and keyboard must respect that too rather than
           opening a menu the mouse cannot. */
        if (window.getComputedStyle(icon).pointerEvents === 'none') { return; }
        e.preventDefault(); /* stop Space from scrolling the page */
        toggleModernSidebar();
    }, false);

    /* Color.css requires #toc_id/#trans_id to be direct children of #sideBarHeader, which
       Bootstrap's nav-tabs markup (ul > li > a) cannot satisfy, so the tab switch is wired
       manually here. .tocclickedclscss is the theme's own active-tab class. */
    document.addEventListener('DOMContentLoaded', function () {
        var tocTab = document.getElementById('toc_id');
        var transTab = document.getElementById('trans_id');
        var menuPanel = document.getElementById('menu');
        var transcriptPanel = document.getElementById('transcript');
        if (!tocTab || !transTab || !menuPanel || !transcriptPanel) {
            return;
        }
        function selectTab(showTranscript) {
            menuPanel.style.display = showTranscript ? 'none' : 'block';
            transcriptPanel.style.display = showTranscript ? 'block' : 'none';
            tocTab.setAttribute('aria-selected', showTranscript ? 'false' : 'true');
            transTab.setAttribute('aria-selected', showTranscript ? 'true' : 'false');
            tocTab.classList.toggle('tocclickedclscss', !showTranscript);
            transTab.classList.toggle('tocclickedclscss', showTranscript);
            /* Keep aria-current on the selected tab, matching the theme's own markup
               (content.html:8 puts aria-current="page" on the selected tab). */
            if (showTranscript) {
                transTab.setAttribute('aria-current', 'page');
                tocTab.removeAttribute('aria-current');
            } else {
                tocTab.setAttribute('aria-current', 'page');
                transTab.removeAttribute('aria-current');
            }
        }
        tocTab.addEventListener('click', function () { selectTab(false); });
        transTab.addEventListener('click', function () { selectTab(true); });
        /* The tab labels are now focusable spans (matching the theme, which wires
           onkeydown="keyHandler(...)" there). That theme global needs Angular, which Preview
           never bootstraps, so activate on Enter/Space here instead - same selectTab() the
           click path already uses, so no behaviour is duplicated. */
        function tabKeydown(showTranscript) {
            return function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    selectTab(showTranscript);
                }
            };
        }
        tocTab.addEventListener('keydown', tabKeydown(false));
        transTab.addEventListener('keydown', tabKeydown(true));
    });
</script>
<?php endif; ?>

<script>
    $(document).ready(function() {

        var attempt = 0;
        $("#menu-btn").click(function() {
            $('#menu_details').animate({
                width: 'show'
            }, 200);
            $("#menu-bg").toggle();
            $("#menu-btn-close").toggle();
            $("#menu-btn").toggle();
            $("#vidArea")[0].pause();
        });

        //Single Choice Questions

        $("#radioForm").submit(function(event) {
            attempt = attempt + 1;
            event.preventDefault(); // Prevent default form submission
            var inputValue = $("input[name='RadioFeedback']:checked").val();

            console.log(inputValue);
            if (inputValue == "feedback_correct") {
                $("#correct_feedback").show();
                $("#incorrect_feedback").hide();
                $("#submit-btn").hide();

                show_correct_options();
                disable_radio_btns();
            } else {
                $("#submit-btn").hide();
                $("#correct_feedback").hide();
                if (attempt == 1) {
                    $("#retry-btn").show();
                    $("#incorrect1_feedback").show();
                }
                if (attempt == 2) {
                    show_correct_options();
                    $("#incorrect_feedback").show();
                    $("#incorrect1_feedback").hide();
                }
                disable_radio_btns();
            }
        });
        $("#menu-bg").click(function() {

            $('#menu_details').animate({
                width: 'hide'
            }, 200);
            $("#menu-bg").toggle();
            $("#menu-btn-close").toggle();
            $("#menu-btn").toggle();
            $("#vidArea")[0].play();
        });
        $("#menu-btn-close").click(function() {
            $('#menu_details').animate({
                width: 'hide'
            }, 200);
            $("#menu-bg").toggle();
            $("#menu-btn-close").toggle();
            $("#menu-btn").toggle();

        });

        //Multiple Choice Questions
        $("#checkboxForm").submit(function(event) {
            event.preventDefault(); // Prevent default form submission
            var array = [];

            attempt = attempt + 1;
            var selected_correct = 0;
            let checkboxes = document.getElementsByName('checkanswer');
            var totalcorrect = document.getElementById('totalcorrect').value;

            let result = "";
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    if (checkboxes[i].value == "feedback_correct") {
                        selected_correct++;
                    }
                    if (checkboxes[i].value == "feedback_wrong") {
                        selected_correct--;
                    }
                }
            }
            console.log(selected_correct + " - " + totalcorrect);
            if (selected_correct == totalcorrect) {
                $("#correct_feedback").show();
                $("#incorrect_feedback").hide();
                $("#submit-btn").hide();
                uncheckElements();
            } else {
                if (attempt == 1) {
                    $("#retry-btn").show();
                    $("#submit-btn").hide();
                    $("#incorrect1_feedback").show();
                    uncheckElements();
                } else {
                    $("#correct_feedback").hide();
                    $("#incorrect_feedback").show();
                    $("#submit-btn").hide();
                    uncheckElements();
                    counter = 0;
                    for (var i = 0; i < checkboxes.length; i++) {
                        counter = counter + 1;
                        console.log(counter);
                        multix = "multi" + counter;
                        if (checkboxes[i].value == "feedback_correct") {

                            document.getElementById(multix).classList.add("correct_option");
                        }
                    }
                }

            }
        });


    });

    3

    function uncheckElements() {
        counter = 0;
        var uncheck = document.getElementsByTagName('input');
        for (var i = 0; i < uncheck.length; i++) {
            if (uncheck[i].type == 'checkbox') {
                uncheck[i].checked = false;
                counter = counter + 1;
                multix = "multi" + counter;
                exampleCheckbox = "exampleCheckbox" + counter;
                document.getElementById(exampleCheckbox).disabled = true;
                document.getElementById(multix).classList.remove("options_cursor");
            }
        }
    }

    function enableCheckMarks() {
        counter = 0;
        var uncheck = document.getElementsByTagName('input');
        for (var i = 0; i < uncheck.length; i++) {
            if (uncheck[i].type == 'checkbox') {
                counter = counter + 1;
                multix = "multi" + counter;
                exampleCheckbox = "exampleCheckbox" + counter;
                document.getElementById(multix).classList.add("options_cursor");
                document.getElementById(exampleCheckbox).disabled = false;

            }
        }
    }

    function show_correct_options() {
        var counter = 0;
        <?php if (isset($question_options)) {
            foreach ($question_options as $options) {
        ?>
                counter = counter + 1;
                correct = "correct" + counter;
                if (document.getElementById(correct) !== null) {
                    document.getElementById(correct).classList.add("correct_option");
                }

        <?php }
        } ?>

    }

    function enable_radio_btns() {
        var counter = 0;
        <?php if (isset($question_options)) {
            foreach ($question_options as $options) {
        ?>
                counter = counter + 1;
                radbtn = "RadioFeedback" + counter;
                document.getElementById(radbtn).checked = false;
                document.getElementById(radbtn).disabled = false;

                correct = "correct" + counter;
                incorrect = "incorrect" + counter;
                if (document.getElementById(correct) !== null) {
                    document.getElementById(correct).classList.add("options_cursor");
                }
                if (document.getElementById(incorrect) !== null) {
                    document.getElementById(incorrect).classList.add("options_cursor");
                }

        <?php }
        } ?>

    }

    function disable_radio_btns() {
        var counter = 0;

        <?php if (isset($question_options)) {
            foreach ($question_options as $options) {
        ?>
                counter = counter + 1;
                radbtn = "RadioFeedback" + counter;

                document.getElementById(radbtn).disabled = true;
                correct = "correct" + counter;
                incorrect = "incorrect" + counter;
                if (document.getElementById(correct) !== null) {
                    document.getElementById(correct).classList.remove("options_cursor");
                }
                if (document.getElementById(incorrect) !== null) {
                    document.getElementById(incorrect).classList.remove("options_cursor");
                }

        <?php }
        } ?>
    }

    function retry() {
        document.getElementById("submit-btn").style.display = "block";
        document.getElementById("retry-btn").style.display = "none";
        document.getElementById("incorrect1_feedback").style.display = "none";
        enable_radio_btns();
    }

    function retry_multi() {
        document.getElementById("submit-btn").style.display = "block";
        document.getElementById("retry-btn").style.display = "none";
        document.getElementById("incorrect1_feedback").style.display = "none";
        enableCheckMarks();
    }

    <?php if ($row['type'] == 8) { ?>
        let vid = document.getElementById("vidArea");

        if (JSON.stringify(vid) !== "null") {
            vid.onended = function() {
                vid.style.display = 'none';
                document.getElementById("interactive-btn").style.display = "block";
            };
        }
        <?php } elseif ($row['type'] == 2) {
        $subpage =  $row['sub_page_main'];
        if ($subpage != 0) {
        ?>
            let vid = document.getElementById("vidArea");

            if (JSON.stringify(vid) !== "null") {
                vid.onended = function() {
                    document.getElementById("close_instruction_id").style.display = "block";
                };
            }
        <?php } else { ?>
            call_next_btn();
    <?php }
    } ?>

    function call_next_btn() {
        let vid = document.getElementById("vidArea");

        if (JSON.stringify(vid) !== "null") {
            vid.onended = function() {
                document.getElementById("next_instruction_id").style.display = "block";
            };
        }
    }
</script>
<script>
    function submitPostRequest(scourse_id, page_number) {
        // Get the form by course_id dynamically
        var form = document.getElementById('launchForm_' + page_number);

        // Open a new window
        var params = 'width=' + screen.width;
        params += ', height=' + screen.height;
        params += ', top=0, left=0';
        params += ', fullscreen=yes';
        params += ', directories=no';
        params += ', location=no';
        params += ', menubar=no';
        params += ', resizable=no';
        params += ', scrollbars=no';
        params += ', status=no';
        params += ', toolbar=no';

        var newwin = window.open('', 'Launcher', params);

        // Submit the form using POST method, targeting the new window
        form.target = "Launcher"; // Target the new window
        form.submit(); // Submit the form with POST data

        // Periodically check if the new window has been closed
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
<script>
    let elm = document.getElementById('submitForm');
    if (JSON.stringify(elm) !== "null") {
        document.getElementById('submitForm').addEventListener('submit', function() {
            var button = document.getElementById('submitButton');
            button.disabled = true;
            button.innerHTML = 'Submitting...';
        });
    }
</script>