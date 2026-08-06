document.addEventListener("DOMContentLoaded", function() {
    const quizContainer = document.getElementById('quizContainer');
    let attemptsLeft = 2; // Default value, will be updated from JSON data
    let questionData;
    let SubmtBool = false;
    let TotalQuestions = 0;

    function loadQuestion() {
		console.log("sdasd"+parent.mainData.quizButton);
        questionData = parent.mainData.question;
        attemptsLeft = questionData.attempts || 2; // Get attempts from JSON or default to 2
        const backgroundImage = questionData.image;

        const optionsHtml = questionData.options.map((option, index) => {
            TotalQuestions = index + 1; // Update TotalQuestions
            return `
                <div class="answer" id="Opt${index}" onclick="selectOption('answer${index}')">
                    <input class="radioBut clicken" type="radio" id="answer${index}" name="answer" value="${option.value}" data-correct="${option.correct}" onchange="handleOptionSelection(this)">
                    <label class="clicken" for="answer${index}">${option.text}</label>
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
        `;

        quizContainer.innerHTML = questionHtml;
        document.getElementById('submitBtn').addEventListener('click', checkAnswer);
    }

    window.handleOptionSelection = function(selectedRadio) {
        const selectedAnswerValue = selectedRadio.value;
        const isCorrect = selectedRadio.dataset.correct === 'true';

        const feedback = document.getElementById('feedback');
        feedback.style.display = 'none'; // Clear previous feedback

        // Update feedback and submit button state based on isCorrect
        if (isCorrect) {
            feedback.textContent = questionData.feedback.correct;
            feedback.classList.remove('incorrect');
            feedback.classList.add('correct');
          
        } else {
            feedback.textContent = questionData.feedback.incorrect;
            feedback.classList.remove('correct');
            feedback.classList.add('incorrect');
        }
    };

    window.selectOption = function(optionId) {
        if (!SubmtBool) {
			 document.getElementById('submitBtn').classList.remove('disabled');
            document.getElementById(optionId).checked = true;
			document.getElementById('submitBtn').classList.remove('disabled');
            handleOptionSelection(document.getElementById(optionId));
        }
    };

    function checkAnswer() {
        const selectedAnswer = document.querySelector('input[name="answer"]:checked');
        const feedback = document.getElementById('feedback');

        if (!selectedAnswer) {
            alert("Please select an answer.");
            return;
        }

        const isCorrect = selectedAnswer.dataset.correct === 'true';

        if (isCorrect) {
            feedback.textContent = questionData.feedback.correct;
            feedback.classList.add('correct');
            document.getElementById('submitBtn').disabled = true;
            SubmtBool = true;
            optionDisabled();
			  document.getElementById('submitBtn').disabled = false;
			 document.getElementById('submitBtn').classList.add('disabled');
            parent.parent.PageCompleteNextFun();
        } else {
            attemptsLeft--;

            if (attemptsLeft > 0) {
                feedback.textContent = questionData.feedback.incorrect;
                feedback.classList.add('incorrect');
                document.getElementById('submitBtn').disabled = true;
				  document.getElementById('submitBtn').disabled = false;
			 document.getElementById('submitBtn').classList.add('disabled');
                optionReset();
            } else {
                feedback.textContent = questionData.feedback.noAttempts;
                feedback.classList.add('incorrect');
                SubmtBool = true;
                optionDisabled();
				  document.getElementById('submitBtn').disabled = false;
			 document.getElementById('submitBtn').classList.add('disabled');
                parent.parent.PageCompleteNextFun();
            }
        }

        feedback.style.display = 'block';
    }

    function optionDisabled() {
        for (var i = 0; i < TotalQuestions; i++) {
            var str = "answer" + i;
            var stropt = "Opt" + i;
            var option = document.getElementById(str);
            var optionDiv = document.getElementById(stropt);
            option.disabled = true;
            if (option.dataset.correct === 'true') {
                //option.nextElementSibling.classList.add('highlight'); // Highlight the correct answer
				optionDiv.classList.add('highlight');
				optionReset();
				option.checked = true;
            }
        }
    }

    function optionReset() {
        for (var i = 0; i < TotalQuestions; i++) {
            var str = "answer" + i;
            document.getElementById(str).checked = false;
        }
    }

    loadQuestion();
});
