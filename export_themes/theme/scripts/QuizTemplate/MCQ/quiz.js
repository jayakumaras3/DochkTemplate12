 document.addEventListener("DOMContentLoaded", function() {
            const quizContainer = document.getElementById('quizContainer');
            let attemptsLeft;
            let questionData;
			let SubmtBool=false;
			let TotalQuestions=0;

            function loadQuestion() {
						questionData = parent.mainData.question;
                        attemptsLeft = questionData.attempts; // Load attemptsLeft from JSON
                        const backgroundImage = questionData.image;

                        const optionsHtml = questionData.options.map((option, index) => {
							TotalQuestions=index;
                            return `
                                <div class="answer"  id="Opt${index}" onclick="selectOption('answer${index}')">
                                    <input class="checkbox" type="checkbox" id="answer${index}" name="answer" value="${option.value}" data-correct="${option.correct}" onchange="handleOptionSelection(this)">
                                    <label for="answer${index}">${option.text}</label>
                                </div>
                            `;
                        }).join('');

                        const questionHtml = `
                            <div class="questionContainer" style="background-image: url(${backgroundImage});">
                                <div class="question">${questionData.question}</div>
                                <div class="options">${optionsHtml}</div>
                                <button class="btn" id="submitBtn">Submit Answer</button>
                                <div class="feedback" id="feedback"></div>
                            </div>
                        `;

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
                }
            }

            window.selectOption = function(optionId) {
				if(!SubmtBool)
				{
					 document.getElementById('submitBtn').classList.remove('disabled');
					const selectedCheckbox = document.getElementById(optionId);
					selectedCheckbox.checked = !selectedCheckbox.checked;
					handleOptionSelection(selectedCheckbox);
				}
            };

            function checkAnswer() {
                const selectedAnswers = document.querySelectorAll('input[name="answer"]:checked');
                const feedback = document.getElementById('feedback');

                if (selectedAnswers.length === 0) {
                    alert("Please select at least one answer.");
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
					document.getElementById('submitBtn').classList.add('disabled');
					SubmtBool=true;
					optionDisabled();
					document.getElementById('submitBtn').disabled = false;
					document.getElementById('submitBtn').classList.add('disabled');	
                    parent.parent.PageCompleteNextFun();
                } else {
                    attemptsLeft--;

                    if (attemptsLeft > 0) {
                        feedback.textContent = questionData.feedback.incorrect;						
						feedback.classList.remove('correct');
                        feedback.classList.add('incorrect');
						document.getElementById('submitBtn').disabled = true;
						document.getElementById('submitBtn').classList.add('disabled');
						optionReset();
                    } else {
                        feedback.textContent = questionData.feedback.noAttempts;
						feedback.classList.remove('correct');
                        feedback.classList.add('incorrect');
                        document.getElementById('submitBtn').disabled = true;
						document.getElementById('submitBtn').classList.add('disabled');
						SubmtBool=true;
						optionDisabled();
                        parent.parent.PageCompleteNextFun();
                    }
                }

                feedback.style.display = 'block';
            }
			function optionDisabled() {
			optionReset();
			for (var i = 0; i <= TotalQuestions; i++) {
			var str = "answer" + i;			
            var stropt = "Opt" + i;			
            var optionDiv = document.getElementById(stropt);
			var option = document.getElementById(str);
			option.disabled = true;
			if (option.dataset.correct === 'true') {
				//option.nextElementSibling.classList.add('highlight'); // Highlight the correct answer
					optionDiv.classList.add('highlight');
				
						option.checked = true;
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