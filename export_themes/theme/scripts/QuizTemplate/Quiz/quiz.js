document.addEventListener("DOMContentLoaded", function () {
   const parentquizContainer = document.getElementById('parentquizContainer');
   const quizContainer = document.getElementById('quizContainer');
   let currentQuestionIndex = 0;

   var questionsData, AnswerData;
   var passingScore;
   var questionRandom = 0;
   var optionRandom = 0;
   var totalQuestions;
   var option_random = 0;
   var selectedAnswers = [];
   var interval;
   var timerElement;
   var formattedTime = "";
   var TimeUpBool=false;
   var attempt=0;

   function loadQuestions() {
      questionsData = parent.mainData; // From questions.json
      AnswerData = parent.AnsData; // From answers.json
      passingScore = parent.PassingScore; // Passing score
      parent.parent.QuizAttemptLimit = parent.QuizAttempt;
      // passingScore = parent.mainData.passingScore; // Passing score
      //loadQuestion(currentQuestionIndex); // Load the first question

     
      let newMargin = "6%"; // Example dynamic value
      document.getElementById("quizContainer").style.marginLeft = newMargin;
      document.getElementById("quizContainer").style.width = "94%";
      if (parseInt(parent.duration) == 0) {

         document.getElementById('durationte').style.display = 'none';
      }
	 
   }

   function Startpage() {
	   attempt= parseInt(parent.parent.curAttempt)+1;
      document.body.style.backgroundImage = "url('images/BG_1.png')";
      const resultsHtml = `
    <div class="Startpage">
		<p class="headerAss">${parent.startpageheader}</p>
		<div class="Startpage_sub">
		<p>${parent.startpagedescrip}</p>
		<p>${parent.TotalQuestionste}${parent.totalQuestions}</p>
		
		<p>${parent.passingScorete}${passingScore}</p>
		<p>${parent.QuizAttemptte} ${attempt}/${parent.QuizAttempt}</p>
        <p id="durationte">${parent.durationte}${parent.duration1} minutes</p>
        <p>${parent.startpagedescrip1}</p>
		
		</div>       	
       <button class="Startpagebtn" id="Startpage">${parent.startButton}</button><br>
		<span id ="inste">${parent.clicknote}</span>
    </div>
`;

      quizContainer.innerHTML = resultsHtml;
      document.getElementById('Startpage').addEventListener('click', startBut);

   }

   function loadQuestion(index) {

      let newMargin = "7%"; // Example dynamic value
      document.getElementById("quizContainer").style.marginLeft = newMargin;
      document.getElementById("quizContainer").style.width = "89%";

      const questionData = questionsData[index];

      const optionsHtml = questionData.options.map((option, idx) => {
         if (questionData.type === 'single') {
            return `
                    <div class="answer" onclick="selectOption(${idx}, '${questionData.type}')">
                        <input class="radioBut clicken" type="radio" id="answer${idx}" name="answer" value="" data-correct="${option.correct}">
                        <label class="clicken" for="answer${idx}">${option.text}</label>
                    </div>
                `;

         } else {
            return `
                    <div class="answer" onclick="selectOption(${idx}, '${questionData.type}')">
                        <input class="checkbox" type="checkbox" id="answer${idx}" name="answer" value="" data-correct="${option.correct}">
                         <label class="clicken" for="answer${idx}" onclick="event.stopPropagation();">${option.text}</label>

                    </div>
                `;
         }

      }).join('');

      const questionHtml = `
	
            <div class="questionContainer" >
				
                <div class="question">${questionData.question}<div class="redtext">${parent.Questiontext}</div></div>
                <div class="options">${optionsHtml}</div>
                <button class="btn" id="submitBtn" disabled>${parent.quizButton}</button>
                <div class="feedback" id="feedback"></div>
            </div>
			<img class="Qmark" src="images/Q.png" alt="Q">
        `;
      const parentquestionHtml = `
		 
		 <div class="parentquestion"><p id="q1">Question ${index + 1} of ${questionsData.length}</p><div id="timer">${formattedTime}</div></div>
		 	<img class="Bulb" src="images/Bulb.png" alt="Bulb"/>
		 
		 
        `;
      parentquizContainer.innerHTML = parentquestionHtml;
      quizContainer.innerHTML = questionHtml;
      timerElement = document.getElementById("timer");
      document.getElementById('submitBtn').addEventListener('click', checkAnswer);
      // Add click event listener to checkboxes
      document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
         checkbox.addEventListener('click', function () {
            // Manually toggle checkbox state
            checkbox.checked = !checkbox.checked;

            // Enable the submit button if any option is selected
            const submitButton = document.getElementById('submitBtn');
            const anyOptionSelected = document.querySelectorAll('input[name="answer"]:checked').length > 0;
            submitButton.disabled = !anyOptionSelected;
         });
      });
   }


   window.selectOption = function (index, type) {
      const selectedOption = document.getElementById(`answer${index}`);
      if (type === 'single') {
         document.querySelectorAll('input[name="answer"]').forEach(option => {
            option.checked = false;
         });
         selectedOption.checked = true;
      } else {
         selectedOption.checked = !selectedOption.checked;
      }

      const submitButton = document.getElementById('submitBtn');
      const anyOptionSelected = document.querySelectorAll('input[name="answer"]:checked').length > 0;
      submitButton.disabled = !anyOptionSelected;
   }

   function startCountdown(totalSeconds) {
      console.log("caled------");

      var mins1 = Math.floor(totalSeconds / 60);
      var secs1 = totalSeconds % 60;

      // Format time as MM:SS (e.g., 02:30)
      var formattedTime1 = (mins1 < 10 ? "0" : "") + mins1 + ":" + (secs1 < 10 ? "0" : "") + secs1;
      timerElement.textContent = formattedTime1;
      interval = setInterval(function () {
         var mins = Math.floor(totalSeconds / 60);
         var secs = totalSeconds % 60;

         // Format time as MM:SS (e.g., 02:30)
         formattedTime = (mins < 10 ? "0" : "") + mins + ":" + (secs < 10 ? "0" : "") + secs;
         timerElement.textContent = formattedTime; // Update div

         if (totalSeconds === 0) {
            clearInterval(interval);
			TimeUpBool=true;
            displayResults();
            // timerElement.textContent = "🎉 Congrats! Time's up!";
            //  alert("🎉 Congrats! Time's up! 🎉"); // Show alert message
         }

         totalSeconds--;
      }, 1000);
   }

   function checkAnswer() {
      // Get the current question data and corresponding answers
      const questionData = questionsData[currentQuestionIndex]; // From questions.json
      const answerData = AnswerData[currentQuestionIndex]; // From answers.json
      //console.log( AnswerData[currentQuestionIndex])

      // Get selected options
      const selectedOptions = document.querySelectorAll('input[name="answer"]:checked');

      if (selectedOptions.length === 0) {
         alert("Please select an answer.");
         return;
      }

      // Determine correctness
      let isCorrect = true;

      if (answerData.type === 'single') {
         // Single choice validation
         if (selectedOptions.length > 1) {
            isCorrect = false; // Invalid selection for single choice
         } else {
            const selectedIndex = Array.from(document.querySelectorAll('input[name="answer"]')).indexOf(selectedOptions[0]);
            isCorrect = answerData.options[selectedIndex]?.correct === true;
         }
      } else if (answerData.type === 'multiple') {
         // Multiple choice validation
         const selectedIndices = Array.from(selectedOptions).map(option =>
            Array.from(document.querySelectorAll('input[name="answer"]')).indexOf(option)
         );

         // Check if selected indices match the correct answers
         const correctIndices = answerData.options.map((option, index) => (option.correct ? index : -1)).filter(index => index !== -1);
         isCorrect =
            selectedIndices.length === correctIndices.length &&
            selectedIndices.every(index => correctIndices.includes(index));
      }

      // Log for debugging
      //console.log("Selected Options:", selectedOptions);
      //console.log("Correct Options from JSON:", answerData.options);
      //console.log("Is Correct:", isCorrect);

      // Record the result
      selectedAnswers.push(isCorrect);

      // Move to the next question
      moveToNextQuestion();
   }

   function moveToNextQuestion() {
      document.getElementById('submitBtn').disabled = true;
      currentQuestionIndex++;

      if (currentQuestionIndex < questionsData.length) {
         setTimeout(() => {
            loadQuestion(currentQuestionIndex);
            const feedback = document.getElementById('feedback');
            feedback.style.display = 'none';
            feedback.classList.remove('correct', 'incorrect');
         }, 500);
      } else {
         setTimeout(() => {
            console.log("submit over");
            clearInterval(interval);
			
            displayResults();
			
         }, 500);
      }
   }

   /*<p>You answered ${correctAnswers} out of ${totalQuestions} questions correctly.</p>*/
   function displayResults() {
      document.body.style.backgroundImage = "url('images/BG_1.png')";
      document.getElementById('parentquizContainer').style.display = 'none';
      let correctAnswers = selectedAnswers.filter(answer => answer).length;
      let totalQuestions = questionsData.length;
      let percentage = (correctAnswers / totalQuestions) * 100;
      //console.log("parent.Resulttitle::" + parent.resutscorecontent);
      //percentage=RoundToPrecision(percentage, 2);
      let passed = percentage >= passingScore;
		var resultsHtml;
		if(TimeUpBool)
		{
			TimeUpBool=false;
		  resultsHtml = `
			<div class="results">
				<h2>${parent.TimeUpte}</h2>
			
				<p>${parent.resutscorecontent} ${percentage.toFixed(0)}%</p>
				<p>${passed ? parent.Resutpassed : parent.Resutfailed}</p>
				${!passed ? `<button class="retrybtn" id="retryBtn">${parent.	retryButton}</button>` : ''}
			</div>
			`; 
		}
		else{
			
			 resultsHtml = `
				<div class="results">
					<h2>${parent.Resulttitle}</h2>
					<p>${parent.resutscorecontent} ${percentage.toFixed(0)}%</p>
					<p>${passed ? parent.Resutpassed : parent.Resutfailed}</p>
					${!passed ? `<button class="retrybtn" id="retryBtn">${parent.retryButton}</button>` : ''}
				</div>
			`;
		}
      quizContainer.innerHTML = resultsHtml;

      if (!passed) {
         document.getElementById('retryBtn').addEventListener('click', retryQuiz);
      }
      parent.parent.curAttempt++;
      if (passed) {
         //	parent.parent.curAttempt++;

      } else {
         console.log("parent.parent.curAttempt" + parent.parent.curAttempt)
         console.log("parent.parent.QuizAttemptLimit" + parent.parent.QuizAttemptLimit)
         if (parent.parent.curAttempt == parent.parent.QuizAttemptLimit) {

            document.getElementById('retryBtn').style.display = 'none';
         } else if (parent.parent.curAttempt < parent.parent.QuizAttemptLimit) {
            //parent.parent.curAttempt++;
            document.getElementById('retryBtn').addEventListener('click', retryQuiz);
         }


      }

      parent.parent.scoreSubmit(percentage);
      parent.parent.QuizpageVistedList();

   }

   function displayResultsScorm() {


      let correctAnswers = selectedAnswers.filter(answer => answer).length;
      let totalQuestions = questionsData.length;
      let percentage = (correctAnswers / totalQuestions) * 100;
      if (parent.parent.scoreActive) {
         percentage = parent.parent.scoreInStroedCheck
      }

      //percentage=RoundToPrecision(percentage, 2);
      let passed = percentage >= passingScore;
      parent.parent.scoreSubmit(percentage);
      const resultsHtml = `
    <div class="results">
        <h2>${parent.Resulttitle}</h2>
        <p>${parent.resutscorecontent} ${percentage.toFixed(0)}%</p>
        <p>${passed ? parent.Resutpassed : parent.Resutfailed}</p>
       
    </div>
`;
      /* ${!passed ? '<button class="retrybtn" id="retryBtn">Retry</button>' : '<button class="retrybtn" id="retryBtn">Retry</button>'}*/
      quizContainer.innerHTML = resultsHtml;
      /*document.getElementById('retryBtn').addEventListener('click', retryQuiz);
      if (!passed) {
      	document.getElementById('retryBtn').addEventListener('click', retryQuiz);
      }*/


   }

   function startBut() {
      document.body.style.backgroundImage = "url('images/BG.png')";
      loadQuestion(currentQuestionIndex);
      if (parseInt(parent.duration) == 0) {

         document.getElementById('timer').style.display = 'none';
      } else {

         startCountdown(parseInt(parent.duration));
      }
   }

   function retryQuiz() {
      currentQuestionIndex = 0;
      selectedAnswers = [];
      loadQuestion(currentQuestionIndex);
      document.getElementById('parentquizContainer').style.display = 'block';
      document.body.style.backgroundImage = "url('images/BG.png')";
      startCountdown(parseInt(parent.duration));
   }

   loadQuestions();
   parent.parent.passScoreTOarticulate();
   console.log("i am here");
   console.log("parent.parent.curAttempt" + parent.parent.curAttempt)
   console.log("parent.parent.QuizAttemptLimit" + parent.parent.QuizAttemptLimit)
   if (parent.parent.scoreActive && parent.parent.curAttempt == parent.parent.QuizAttemptLimit) {
      setTimeout(() => {
         console.log("parent.parent.curAttempt" + parent.parent.curAttempt)
         console.log("parent.parent.QuizAttemptLimit" + parent.parent.QuizAttemptLimit)
         displayResultsScorm();
      }, 100);
   }
   else
   {
	   Startpage();
   }
});