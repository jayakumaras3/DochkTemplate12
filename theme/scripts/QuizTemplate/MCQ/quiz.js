 document.addEventListener("DOMContentLoaded", function() {
            const quizContainer = document.getElementById('quizContainer');
				const parentquizContainer = document.getElementById('parentquizContainer');
            let attemptsLeft;
            let questionData;
			let SubmtBool=false;
			let TotalQuestions=0;

            function applyMobileScrollFix() {
                if (window.innerWidth > 900) {
                    return;
                }

                // Keep mobile quiz content scrollable even if outer template uses overflow hidden.
                document.documentElement.style.setProperty('height', '100%', 'important');
                document.documentElement.style.setProperty('overflow-y', 'auto', 'important');
                document.documentElement.style.setProperty('-webkit-overflow-scrolling', 'touch');

                document.body.style.setProperty('height', '100%', 'important');
                document.body.style.setProperty('min-height', '100%', 'important');
                document.body.style.setProperty('overflow-y', 'auto', 'important');
                document.body.style.setProperty('-webkit-overflow-scrolling', 'touch');
                document.body.style.setProperty('touch-action', 'pan-y', 'important');

                if (quizContainer) {
                    quizContainer.style.setProperty('height', 'auto', 'important');
                    quizContainer.style.setProperty('min-height', '100%', 'important');
                    quizContainer.style.setProperty('overflow-y', 'auto', 'important');
                    quizContainer.style.setProperty('-webkit-overflow-scrolling', 'touch');
                    quizContainer.style.setProperty('padding-bottom', '24px', 'important');
                }
            }

            function loadQuestion() {
                        if (parent.hideShellSpinner) parent.hideShellSpinner();
						// Shared mechanism (theme/scripts/backgroundLayer.js, loaded in
						// MCQ.html's <head>) - same function SCQ/Quiz/Custom HTML use.
						// "background" is a top-level key in question.json, sibling to
						// quizButton/AlertText, so it comes through on parent.mainData.
						if (typeof applyBackground === 'function') applyBackground(parent.mainData.background);
						questionData = parent.mainData.question;
                        attemptsLeft = questionData.attempts; // Load attemptsLeft from JSON
                        const backgroundImage = questionData.image;
const optionsHtml = questionData.options.map((option, index) => {
    TotalQuestions = index;
    return `
        <div tabindex="0" role="checkbox" class="answer FSize16" id="Opt${index}" 
             onkeydown="handleKeydown(event, ${index})"
             aria-checked="false"  
        >
            <span id="Opttick${index}" class="tickSymbol"></span>
            
            <input class="checkbox" tabindex="-1" type="checkbox" 
                   id="answer${index}" name="answer" value="${option.value}" 
                   data-correct="${option.correct}" 
                   onchange="handleOptionSelection(this)" 
                   aria-labelledby="lb${index}" 
                   aria-checked="false" 	
            >
            <label id="lb${index}" class="clicken" for="answer${index}">${option.text}</label>
        </div>
    `;
}).join('');



                        const questionHtml = `

                            <div class="questionContainer">
							
                                 <div class="question FSize16">
								<span id="question-text" tabindex="0">${questionData.question}</span>
								  <div class="redtext instext FSize16">
								<span id="instruction-text" tabindex="0">${parent.mainData.Questiontext}</span>
							</div>
								</div>
                                <div class="options">${optionsHtml}</div>
                                <button class="btn ColorSet_CR FSize16" id="submitBtn">${parent.mainData.quizButton}</button>
                                <div class="feedback"><p tabindex="-1" id="feedback" role="status" aria-live="assertive" aria-atomic="true"></p></div>
                            </div>
                        `;
		parentquizContainer.innerHTML = "";
                        quizContainer.innerHTML = questionHtml;
                        applyMobileScrollFix();
                        document.getElementById('submitBtn').addEventListener('click', checkAnswer);
                        bindAnswerRowHandlers();
            }

            function announceFeedback(message) {
                const feedback = document.getElementById('feedback');
                if (!feedback) {
                    return;
                }

                feedback.style.display = 'block';
                feedback.textContent = '';
                requestAnimationFrame(() => {
                    feedback.textContent = message;
                    feedback.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    feedback.focus({ preventScroll: true });
                });
            }

            var resetBool = false;
window.handleKeydown=function(event, idx) {
    
			if (event.key === 'Enter' || event.key === ' ') {
			event.preventDefault(); // Prevent default behavior for Enter or Space
			if(!SubmtBool)
			{
				selectOption1(idx); // Call the same selectOption function on Enter/Space
			}
    }
}
     window.handleOptionSelection = function(selectedCheckbox) {
		 	if(!SubmtBool)
				{
		if (attemptsLeft == 1) {
			// Reset all checkboxes to default state only if resetBool is false
			if (!resetBool) {
				const checkboxes = document.querySelectorAll('input[name="answer"]');
				checkboxes.forEach(checkbox => {
					checkbox.checked = false;
					// Reset aria-checked for all checkboxes
					const div = document.getElementById("Opt" + checkbox.id.replace('answer', ''));
					div.setAttribute("aria-checked", "false");
				});

				// Set the selected checkbox to checked
				selectedCheckbox.checked = true;

				// Enable the submit button
				const submitButton = document.getElementById('submitBtn');
				submitButton.disabled = false;

				resetBool = true;  // Set resetBool to true to prevent resetting multiple times
			}

			// If the submit button is not already enabled, enable it
			if (!SubmtBool) {
				const submitButton = document.getElementById('submitBtn');
				submitButton.classList.remove('disabled');
				submitButton.classList.remove('no-select');
				
				// Hide previous feedback
				const feedback = document.getElementById('feedback');
				feedback.style.display = 'none';
			}

			
		}
	
		// Update aria-checked state for the selected checkbox and corresponding div
			const div = document.getElementById("Opt" + selectedCheckbox.id.replace('answer', '')); // Get corresponding div
			//	console.log(div);
			// Synchronize aria-checked for both the input checkbox and the div container
			const ariaCheckedState = selectedCheckbox.checked ? "true" : "false";
			selectedCheckbox.setAttribute("aria-checked", ariaCheckedState);
			div.setAttribute("aria-checked", ariaCheckedState);
				}
	};


			
			
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
				console.log(optionId);
				if(!SubmtBool)
				{
					 document.getElementById('submitBtn').classList.remove('disabled');
					 
					 document.getElementById('submitBtn').classList.remove('no-select');
					const selectedCheckbox = document.getElementById(optionId);
					selectedCheckbox.checked = !selectedCheckbox.checked;
				}
            };

            function checkAnswer() {
                const selectedAnswers = document.querySelectorAll('input[name="answer"]:checked');
                const feedback = document.getElementById('feedback');

                if (selectedAnswers.length === 0) {
                   // alert("Please select at least one answer.");
					alert(parent.mainData.AlertText);
                    return;
                }

                let allCorrect = true;
                selectedAnswers.forEach(answer => {
                    if (answer.dataset.correct !== 'true') {
                        allCorrect = false;
                    }
                });
                if (allCorrect && selectedAnswers.length === questionData.options.filter(option => option.correct).length) {
                    announceFeedback(questionData.feedback.correct);
					feedback.classList.remove('incorrect');
                    feedback.classList.add('correct');
					
					feedback.classList.remove('Incorrect_CR');
						feedback.classList.add('Correct_CR');
                        feedback.classList.add('FSize16');
                    document.getElementById('submitBtn').disabled = true;
					
					 document.getElementById('submitBtn').classList.add('no-select');
					document.getElementById('submitBtn').classList.add('disabled');
					SubmtBool=true;
					optionDisabled();
					document.getElementById('submitBtn').disabled = false;
					document.getElementById('submitBtn').classList.add('no-select');
					document.getElementById('submitBtn').classList.add('disabled');	
                    parent.parent.PageCompleteNextFun();
                } else {
                    attemptsLeft--;

                    if (attemptsLeft > 0) {
						console.log("1");
                        announceFeedback(questionData.feedback.incorrect);
						feedback.classList.remove('correct');
                        feedback.classList.add('incorrect');						
                        feedback.classList.add('FSize16');
						feedback.classList.remove('Correct_CR');
						feedback.classList.add('Incorrect_CR');
						
						
						document.getElementById('submitBtn').disabled = true;
						document.getElementById('submitBtn').classList.add('no-select');
						document.getElementById('submitBtn').classList.add('disabled');
						optionReset();
                    } else {
						console.log("2");
                        announceFeedback(questionData.feedback.noAttempts);
						feedback.classList.remove('correct');
                        feedback.classList.add('incorrect');
						
						
						feedback.classList.remove('Correct_CR');
						feedback.classList.add('Incorrect_CR');
                        document.getElementById('submitBtn').disabled = true;
						document.getElementById('submitBtn').classList.add('no-select');
						document.getElementById('submitBtn').classList.add('disabled');
						SubmtBool=true;
						optionDisabled();
						
                        parent.parent.PageCompleteNextFun();
                    }
                }

                
            }
			function optionDisabled() {
    for (var i = 0; i <=TotalQuestions; i++) {
        var str = "answer" + i;
        var stropt = "Opt" + i;
        var Opttickclass = "Opttick" + i;
        var labelremove = "lb" + i;

        var labelremoveid = document.getElementById(labelremove);  
        var option = document.getElementById(str);       // input element (e.g., radio/checkbox)
        var optionDiv = document.getElementById(stropt); // container element (label/div)

        // Disable the input
        option.disabled = true;
        optionDiv.classList.add('disabled');
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
            let element = document.getElementById(Opttickclass);
            if (element) {
                element.style.setProperty("--before-visibility", "visible");
            }
        }
    }

}
			 function optionReset() {
					for (var i = 0; i <=TotalQuestions; i++) {
						var checkbox = document.getElementById("answer" + i); // Get the checkbox input
						var div = document.getElementById("Opt" + i); // Get the corresponding div

						// Reset the checkbox
						checkbox.checked = false;
						checkbox.setAttribute("aria-checked", "false");

						// Reset the div's aria-checked
						div.setAttribute("aria-checked", "false");
					}
				}
            loadQuestion();
                        applyMobileScrollFix();
                        window.addEventListener('resize', applyMobileScrollFix);
                        window.addEventListener('orientationchange', applyMobileScrollFix);
        });
// Makes the whole option row clickable (not just the label text/checkbox),
// matching SCQ's behaviour. Previously this was wired up via nested
// document.addEventListener("DOMContentLoaded", ...) calls that only ever
// bound to whichever .answer rows existed at that one-time event, so rows
// rebuilt by loadQuestion() (every question navigation) lost their full-row
// click handling and only the native <label for> hit area (i.e. just the
// text) kept working. Calling this directly from loadQuestion(), right
// after the row markup is injected, guarantees it re-binds every time.
function bindAnswerRowHandlers() {
    document.querySelectorAll('.answer').forEach(function(answerDiv) {
        answerDiv.addEventListener('click', function(event) {
            const checkbox = this.querySelector('input[type="checkbox"]');
            // Once the question is locked after Submit, optionDisabled() has
            // set checkbox.disabled = true. The native checkbox already ignores
            // clicks, but this row handler toggles checkbox.checked in JS, which
            // bypasses `disabled` - so without this guard the user could still
            // change answers after the result is shown by clicking the row. The
            // guard is disabled-based (not SubmtBool-based) so the incorrect
            // retry state, which resets but does NOT disable the options, keeps
            // its options clickable.
            if (checkbox.disabled) {
                return;
            }
            // Skip the manual toggle if the click originated on the checkbox
            // itself (or was forwarded there natively by clicking the
            // <label>) - it has already toggled itself at that point.
            if (event.target !== checkbox) {
                checkbox.checked = !checkbox.checked;
            }
            handleOptionSelection(checkbox);
        });
    });

    document.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
        checkbox.addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent the row click from firing again
            if (this.disabled) {
                return; // Locked after Submit - ignore any residual click.
            }
            handleOptionSelection(this);
        });
    });

    document.querySelectorAll('label.clicken').forEach(function(label) {
        label.addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent double toggling via native label forwarding
        });
    });
}
