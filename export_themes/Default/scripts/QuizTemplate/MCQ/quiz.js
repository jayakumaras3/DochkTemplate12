 document.addEventListener("DOMContentLoaded", function() {
            const quizContainer = document.getElementById('quizContainer');
				const parentquizContainer = document.getElementById('parentquizContainer');
            let attemptsLeft;
            let questionData;
			let SubmtBool=false;
			let TotalQuestions=0;

            function loadQuestion() {
						questionData = parent.mainData.question;
                        attemptsLeft = questionData.attempts; // Load attemptsLeft from JSON
                        const backgroundImage = questionData.image;
const optionsHtml = questionData.options.map((option, index) => {
    TotalQuestions = index;
    return `
        <div class="answer" id="Opt${index}"><span id="Opttick${index}" class="tickSymbol"></span>
            <input class="checkbox" type="checkbox" id="answer${index}" name="answer" value="${option.value}" data-correct="${option.correct}">
            <label id="lb${index}" class="clicken" for="answer${index}">${option.text}</label>
        </div>
    `;
}).join('');


                        const questionHtml = `
                            <div class="questionContainer">
                                <div class="question">${questionData.question}<div class="redtext">${parent.mainData.Questiontext}</div></div>
                                <div class="options">${optionsHtml}</div>
                                <button class="btn" id="submitBtn">${parent.mainData.quizButton}</button>
                                <div class="feedback" id="feedback"></div>
                            </div>
							<img class="Qmark" src="images/Q.png" alt="Q">
                        `;
const parentquestionHtml = `
		 
		 	<img class="Bulb" src="images/Bulb.png" alt="Bulb"/>
		 
		 
        `;
		parentquizContainer.innerHTML = parentquestionHtml;
                        quizContainer.innerHTML = questionHtml;
                        document.getElementById('submitBtn').addEventListener('click', checkAnswer);
            }

            var resetBool = false;
            window.handleOptionSelection = function(selectedCheckbox) {
                if (attemptsLeft == 1) {
                    if (!resetBool) {
                        // Reset all checkboxes to default state
                        const checkboxes = document.querySelectorAll('input[name="answer"]');
                        checkboxes.forEach(checkbox => {
                            checkbox.checked = false;
                        });

                        // Set the selected checkbox to checked
                        selectedCheckbox.checked = true;

                        // Enable submit button when an option is selected
                        const submitButton = document.getElementById('submitBtn');
                        submitButton.disabled = false;

                        resetBool = true;
                    }
					//console.log(SubmtBool)
				if(!SubmtBool)
				{
					 document.getElementById('submitBtn').classList.remove('disabled');
					 document.getElementById('submitBtn').classList.remove('no-select');
					  const feedback = document.getElementById('feedback');
					feedback.style.display = 'none';
					//const selectedCheckbox = document.getElementById(optionId);
					//selectedCheckbox.checked = !selectedCheckbox.checked;
					
				}
                }
				
            }

            window.selectOption = function(optionId) {
				
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
                    feedback.textContent = questionData.feedback.correct;
					feedback.classList.remove('incorrect');
                    feedback.classList.add('correct');
					
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
                        feedback.textContent = questionData.feedback.incorrect;						
						feedback.classList.remove('correct');
                        feedback.classList.add('incorrect');
						document.getElementById('submitBtn').disabled = true;
						document.getElementById('submitBtn').classList.add('no-select');
						document.getElementById('submitBtn').classList.add('disabled');
						optionReset();
                    } else {
                        feedback.textContent = questionData.feedback.noAttempts;
						feedback.classList.remove('correct');
                        feedback.classList.add('incorrect');
                        document.getElementById('submitBtn').disabled = true;
						document.getElementById('submitBtn').classList.add('no-select');
						document.getElementById('submitBtn').classList.add('disabled');
						SubmtBool=true;
						optionDisabled();
						
                        parent.parent.PageCompleteNextFun();
                    }
                }

                feedback.style.display = 'block';
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
          //  optionReset();
         //   option.checked = true;
        }
    }

}
			 function optionReset() {
			for (var i = 0; i <= TotalQuestions; i++) {
            var str = "answer" + i;
					document.getElementById(str).checked = false;
				}
			}
            loadQuestion();
        });
		document.addEventListener("DOMContentLoaded", function() {
			
 // Define the same click handler used for adding listeners
function answerClickHandler(event) {
    const checkbox = this.querySelector('input[type="checkbox"]');

    // Toggle checkbox only if it's not already the clicked element
    if (event.target !== checkbox) {
        checkbox.checked = !checkbox.checked;
    }

    // Handle selection logic
    handleOptionSelection(checkbox);
}

// This stores the divs so we can reference them in both add/remove
let answerDivs = [];

// Add event listeners on page load
document.addEventListener("DOMContentLoaded", function () {
    answerDivs = Array.from(document.querySelectorAll('.answer'));
    answerDivs.forEach(answerDiv => {
        answerDiv.addEventListener('click', answerClickHandler);
    });
});

// 🔻 NEW FUNCTION: Call this to disable answer click behavior
function disableAnswerClicks() {
    if (answerDivs.length === 0) {
        answerDivs = Array.from(document.querySelectorAll('.answer'));
    }
    answerDivs.forEach(answerDiv => {
        answerDiv.removeEventListener('click', answerClickHandler);
    });
}
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent the div click from triggering again
            handleOptionSelection(this);
        });
    });

    document.querySelectorAll('label.clicken').forEach(label => {
        label.addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent double toggling
        });
    });
});
