document.addEventListener("DOMContentLoaded", function() {
    const quizContainer = document.getElementById('quizContainer');
	const parentquizContainer = document.getElementById('parentquizContainer');
    let attemptsLeft = 2; // Default value, will be updated from JSON data
    let questionData;
    let SubmtBool = false;
    let TotalQuestions = 0;
    function loadQuestion() {
		console.log(" "+parent.mainData.quizButton);
        questionData = parent.mainData.question;
        attemptsLeft = questionData.attempts || 2; // Get attempts from JSON or default to 2
        const backgroundImage = questionData.image;

        const optionsHtml = questionData.options.map((option, index) => {
            TotalQuestions = index + 1; // Update TotalQuestions
            return `<div class="answer FSize20" id="Opt${index}"
     tabindex="0" aria-checked="false" role="radio"
     onclick="selectOption('answer${index}')"
     onkeydown="handleKeydown(event, ${index})">
		
  <span id="Opttick${index}" class="tickSymbol"></span>

  <!-- Native radio hidden from screen readers; parent div (role="radio") provides the accessible name.
       Removed aria-labelledby and aria-checked from input to prevent duplicate announcements on iOS VoiceOver -->
  <input tabindex="-1" class="radioBut clicken" type="radio"
         id="answer${index}" name="answer"
         value="${option.value}" data-correct="${option.correct}"
         onchange="handleOptionSelection(this)"
         aria-hidden="true"
          >

  <label id="lb${index}" class="clicken" for="answer${index}">${option.text}</label>
</div> `;
        }).join('');

		const questionHtml = `
			<div class="questionContainer">
			<!-- Question Section with aria-labelledby pointing to a valid ID -->
			<div class="question FSize20">
			<span id="question-text" tabindex="0">${questionData.question}</span>

			<div class="redtext instext FSize20">
			<span id="instruction-text" tabindex="0">${parent.mainData.Questiontext}</span>
			</div>
			</div>
			<div class="options" >${optionsHtml}</div>
			<button aria-label="Submit quiz" role="button" class="btn ColorSet_CR FSize20" id="submitBtn" tabindex="0">
			${parent.mainData.quizButton}
			</button>
			<div class="feedback"  ><p tabindex="0" id="feedback"> </p></div>
			</div>
			<img class="Qmark" src="../../../images/Bulb.svg" alt="Question mark symbol for quiz" aria-hidden="true">
			`;



const parentquestionHtml = `
  
`;


		parentquizContainer.innerHTML = parentquestionHtml;
        quizContainer.innerHTML = questionHtml;
        document.getElementById('submitBtn').addEventListener('click', checkAnswer);
    }

 window.handleOptionSelection = function(selectedRadio) {
	if (!SubmtBool) {
		const selectedAnswerValue = selectedRadio.value;
		const isCorrect = selectedRadio.dataset.correct === 'true';

		const feedback = document.getElementById('feedback');
		feedback.style.display = 'none'; // Hide previous feedback

		// Update aria-checked dynamically for all radio buttons and the corresponding div
		const options = document.querySelectorAll('input[name="answer"]');
		options.forEach(option => {
		option.setAttribute('aria-checked', 'false'); // Set all options to false first
		// Update corresponding div aria-checked
			const div = document.getElementById(`Opt${option.id.replace('answer', '')}`);
			div.setAttribute('aria-checked', 'false');
		});

		// Mark the selected radio as checked
		selectedRadio.setAttribute('aria-checked', 'true');

		// Find corresponding div and update its aria-checked state
		const selectedDiv = document.getElementById(`Opt${selectedRadio.id.replace('answer', '')}`);
		selectedDiv.setAttribute('aria-checked', 'true');

		document.getElementById('submitBtn').classList.remove('disabled');
		document.getElementById('submitBtn').classList.remove('no-select');
	}
};


window.handleKeydown=function(event, idx) {
		if (!SubmtBool) {
			if (event.key === 'Enter' || event.key === ' ') {
				event.preventDefault(); // Prevent default behavior for Enter or Space
				selectOption1(idx); // Call the same selectOption function on Enter/Space
			}
		}
}
	window.selectOption1 = function(idx) {
    const checkbox = document.getElementById(`answer${idx}`);

    if (checkbox) {  // Check if the checkbox exists
        checkbox.checked = !checkbox.checked;  // Toggle the checked state
        handleOptionSelection(checkbox);  // Call your existing function to handle the selection
    } else {
        console.error(`Checkbox with id "answer${idx}" not found.`);
    }
};
    window.selectOption = function(optionId) {
		if (!SubmtBool) {
			document.getElementById('submitBtn').classList.remove('disabled');
			document.getElementById(optionId).checked = true;
			document.getElementById('submitBtn').classList.remove('no-select');
			handleOptionSelection(document.getElementById(optionId));
        }
    };

    function checkAnswer() {
        const selectedAnswer = document.querySelector('input[name="answer"]:checked');
        const feedback = document.getElementById('feedback');

        if (!selectedAnswer) {
            alert(parent.mainData.AlertText);
            return;
        }

        const isCorrect = selectedAnswer.dataset.correct === 'true';
		feedback.style.display = 'block';
        if (isCorrect) {
            feedback.textContent = questionData.feedback.correct;
            feedback.classList.add('correct');
			feedback.classList.add('FSize20');
			feedback.classList.add('Correct_CR');
            document.getElementById('submitBtn').disabled = true;
            SubmtBool = true;
            optionDisabled();
			document.getElementById('submitBtn').disabled = false;
			document.getElementById('submitBtn').classList.add('disabled');
			document.getElementById('submitBtn').classList.add('no-select');
			parent.parent.PageCompleteNextFun();
        } else {
            attemptsLeft--;

            if (attemptsLeft > 0) {
                feedback.textContent = questionData.feedback.incorrect;
				feedback.classList.add('FSize20');
                feedback.classList.add('incorrect');
                feedback.classList.add('Incorrect_CR');
                document.getElementById('submitBtn').disabled = true;
				document.getElementById('submitBtn').disabled = false;
				document.getElementById('submitBtn').classList.add('disabled');
				document.getElementById('submitBtn').classList.add('no-select');
                optionReset();
            } else {
                feedback.textContent = questionData.feedback.noAttempts;
				feedback.classList.add('FSize20');
                feedback.classList.add('incorrect');
                feedback.classList.add('Incorrect_CR');
                SubmtBool = true;
                optionDisabled();
				 document.getElementById('submitBtn').disabled = false;
				document.getElementById('submitBtn').classList.add('disabled');
				document.getElementById('submitBtn').classList.add('no-select');
                parent.parent.PageCompleteNextFun();
            }
        }

        
    }

    function optionDisabled() {
    for (var i = 0; i < TotalQuestions; i++) {
        var str = "answer" + i;
        var stropt = "Opt" + i;
        var Opttickclass = "Opttick" + i;
        var labelremove = "lb" + i;

        var labelremoveid = document.getElementById(labelremove);  
        var option = document.getElementById(str);       // input element (e.g., radio/checkbox)
        var optionDiv = document.getElementById(stropt); // container element (label/div)

        // Disable the input
        option.disabled = true;
			labelremoveid.classList.add('no-select');   // prevent text selection
            labelremoveid.classList.add('no-pointer');
			option.classList.add('no-select');   // prevent text selection
            option.classList.add('no-pointer');
        // Add classes to prevent pointer and selection
        if (optionDiv) {
            optionDiv.classList.add('no-select');   // prevent text selection
            optionDiv.classList.add('no-pointer');  // remove pointer cursor
             // remove pointer cursor
        }

        // Handle correct answer highlight
        if (option.dataset.correct === 'true') {
            // Add correct class to optionDiv for green background highlighting
            if (optionDiv) {
                optionDiv.classList.add('correct');
            }
            let element = document.getElementById(Opttickclass);
            if (element) {
                // Don't show the tick mark, hide it instead
                element.style.setProperty("--before-visibility", "hidden");
            }
          //  optionReset();
           // option.checked = true;
        }
    }
}

    function optionReset() {
    for (var i = 0; i < TotalQuestions; i++) {
			var radioButton = document.getElementById("answer" + i); // Get the radio input
			var div = document.getElementById("Opt" + i); // Get the corresponding div

			// Reset the radio button
			radioButton.checked = false;
			radioButton.setAttribute("aria-checked", "false");

			// Reset the div's aria-checked
			div.setAttribute("aria-checked", "false");
		}
	}

	// Define the function globally so inline HTML can access it
window.handleRadioKey = function(event, index) {
    const radios = document.querySelectorAll('[role="radio"]');
    let newIndex = index;

    if (event.key === 'ArrowRight' || event.key === 'ArrowDown') {
        newIndex = (index + 1) % radios.length;
    } else if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') {
        newIndex = (index - 1 + radios.length) % radios.length;
    } else if (event.key === ' ' || event.key === 'Enter') {
        event.preventDefault();
        radios[index].click(); // triggers selection via your existing logic
        return;
    } else {
        return;
    }

    // Remove tabindex from all and update focus target
    radios.forEach((r, i) => r.setAttribute("tabindex", i === newIndex ? "0" : "-1"));
    radios[newIndex].focus();
};


    loadQuestion();
});
