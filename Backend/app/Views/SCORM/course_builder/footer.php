</div>
</div>
</body>

</html>

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