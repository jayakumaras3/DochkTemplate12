document.addEventListener("DOMContentLoaded", function() {
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
	var TimeUpBool = false;
	var attempt = 0;

	var quiztype = "";

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

	}

	function Startpage() {
		attempt = parseInt(parent.parent.curAttempt) + 1;
		//    document.body.style.backgroundImage = "url('images/BG_1.png')";
		const resultsHtml = `
    <div class="Startpage">
		<p class="headerAss">${parent.startpageheader}</p>
		<div class="Startpage_sub">
		<p>${parent.startpagedescrip}</p>
		<p>${parent.TotalQuestionste}${parent.totalQuestions}</p>
		
		<p>${parent.passingScorete}${passingScore}</p>
		<p>${parent.QuizAttemptte} ${attempt}/${parent.QuizAttempt}</p>
        <p id="durationte">${parent.durationte}${parent.duration1} ${parent.MinutesText}</p>
        <p>${parent.startpagedescrip1}</p>
		
		</div>       	
       <button class="Startpagebtn" id="Startpage">${parent.startButton}</button><br>
		<span id ="inste">${parent.clicknote}</span>
    </div>
`;

		quizContainer.innerHTML = resultsHtml;
		document.getElementById('Startpage').addEventListener('click', startBut);
		if (parseInt(parent.duration) == 0) {

			document.getElementById('durationte').style.display = 'none';
		}


	}

	function toggleZoom(image) {
		// Check if the image is already zoomed in
		if (image.classList.contains('zoom-in')) {
			image.classList.remove('zoom-in');
		} else {
			image.classList.add('zoom-in');
		}

		/*if (image.style.transform === "scale(2)") {
		    image.style.transform = "scale(1)"; // Reset to normal size
		    image.style.transition = "transform 0.25s ease"; // Add transition for smooth zoom out
		} else {
		    image.style.transform = "scale(2)"; // Zoom in the image
		    image.style.transition = "transform 0.25s ease"; // Add transition for smooth zoom in
		}*/
	}

	function loadQuestion(index) {

		let newMargin = "7%"; // Example dynamic value
		document.getElementById("quizContainer").style.marginLeft = newMargin;
		document.getElementById("quizContainer").style.width = "89%";

		const questionData = questionsData[index];

		const optionsHtml = questionData.options.map((option, idx) => {
			quiztype = questionData.type;
			if (questionData.type === 'single') {
				return `
                    <div class="answer" onclick="selectOption(${idx}, '${questionData.type}')">
                        <input class="radioBut clicken" type="radio" id="answer${idx}" name="answer" value="" data-correct="${option.correct}">
                        <label class="clicken" for="answer${idx}">${option.text}</label>
                    </div>
                `;

			} else if (questionData.type === 'multiple') {
				return `
                    <div class="answer" onclick="selectOption(${idx}, '${questionData.type}')">
                        <input class="checkbox" type="checkbox" id="answer${idx}" name="answer" value="" data-correct="${option.correct}">
                         <label class="clicken" for="answer${idx}" onclick="event.stopPropagation();">${option.text}</label>

                    </div>
                `;
			}

		}).join('');
		var questionHtml = "";
		if (questionData.type === 'single') {
			if (questionData.images == null && questionData.video == null) {
				questionHtml = `
			
					<div class="questionContainer" >
						
						<div class="question">${questionData.question}<div class="redtext">${parent.Questiontext}</div></div>
						<div class="options">${optionsHtml}</div>
						<button class="btn btn1" id="submitBtn" disabled>${parent.quizButton}</button>
						<div class="feedback" id="feedback"></div>
					</div>
					
				`;
			} else if (questionData.images != null && questionData.video == null) {
					questionHtml = `
					<div class="questionContainer">
					  <div class="question">
						${questionData.question}
						<div class="redtext">${parent.QuestionMcQtext}</div>
						<div class="redtext1">${parent.ImageZoomText}</div>
					  </div>
					  <div class="contentWrapper">
						<div class="options1">${optionsHtml}<button class="btn" id="submitBtn" disabled>${parent.quizButton}</button></div>
						<img class="ImageQuestion" src="${questionData.images}" alt="Q">
						 
					  </div>					 
					  <div class="feedback" id="feedback"></div>
					</div>

				  <!-- Responsive Image Modal -->
				  <div id="imageModal" class="modal">
					<span class="close" >&times;</span>
					<div class="modal-content-wrapper">
					  <img class="modal-img" id="modalImg">
					</div>
				  </div>
				`;
			} else if (questionData.images == null && questionData.video != null) {
				questionHtml = `
				<div class="questionContainer">
					<div class="question">${questionData.question}<div class="redtext">${parent.Questiontext}</div></div>
					 <div class="contentWrapper">
						<div class="options1">${optionsHtml}</div>
						<video id="myvideoQuiz" class="VideoQuestion" src="${questionData.video}" controls controlsList="nodownload noremoteplayback" disablePictureInPicture allowfullscreen>
</video>
					  </div>
					<button class="btn btn1" id="submitBtn" disabled>${parent.quizButton}</button>
					<div class="feedback" id="feedback"></div>
				</div>
				
			`;
			}
		} else if (questionData.type === 'multiple') {
			if (questionData.images == null && questionData.video == null) {

				questionHtml = `
			
					<div class="questionContainer" >
						
						<div class="question">${questionData.question}<div class="redtext">${parent.QuestionMcQtext}</div></div>
						<div class="options">${optionsHtml}</div>
						<button class="btn btn1" id="submitBtn" disabled>${parent.quizButton}</button>
						<div class="feedback" id="feedback"></div>
					</div>
					
				`;
			} else if (questionData.images != null && questionData.video == null) {
				questionHtml = `
			
					<div class="questionContainer" >
						
						<div class="question">${questionData.question}<div class="redtext">${parent.QuestionMcQtext}</div><div class="redtext">${parent.ImageZoomText}</div></div>
						 <div class="contentWrapper">
						<div class="options1">${optionsHtml}<button class="btn" id="submitBtn" disabled>${parent.quizButton}</button></div>
						<img class="ImageQuestion" src="${questionData.images}" alt="Q">						 
					  </div>
						<div class="feedback" id="feedback"></div>
					</div>
					
					 <!-- Responsive Image Modal -->
				  <div id="imageModal" class="modal">
					<span class="close" >&times;</span>
					<div class="modal-content-wrapper">
					  <img class="modal-img" id="modalImg">
					</div>
				  </div>
				`;
			} else if (questionData.images == null && questionData.video != null) {
				questionHtml = `
		
					<div class="questionContainer" >
						
						<div class="question">${questionData.question}<div class="redtext">${parent.QuestionMcQtext}</div></div>
						<div class="contentWrapper">
						<div class="options1">${optionsHtml} <button class="btn" id="submitBtn" disabled>${parent.quizButton}</button></div>
						<video id="myvideoQuiz" class="VideoQuestion" src="${questionData.video}" controls controlsList="nodownload noremoteplayback" disablePictureInPicture allowfullscreen>
</video>
					  </div>
						
						<div class="feedback" id="feedback"></div>
					</div>
					`;

			}

		}
		const parentquestionHtml = `
		 
		 <div class="parentquestion"><p id="q1">${parent.QuestionCountText} ${index + 1} ${parent.QuestionOFText} ${questionsData.length}</p><div id="timer">${formattedTime}</div></div>
		 	<img class="Bulb" src="images/Bulb.png" alt="Bulb"/>
		 
		 
        `;
		parentquizContainer.innerHTML = parentquestionHtml;
		quizContainer.innerHTML = questionHtml;
		timerElement = document.getElementById("timer");
		document.getElementById('submitBtn').addEventListener('click', checkAnswer);
		// Add click event listener to checkboxes
		document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
			checkbox.addEventListener('click', function() {
				// Manually toggle checkbox state
				checkbox.checked = !checkbox.checked;

				// Enable the submit button if any option is selected
				const submitButton = document.getElementById('submitBtn');
				const anyOptionSelected = document.querySelectorAll('input[name="answer"]:checked').length > 0;
				submitButton.disabled = !anyOptionSelected;
			});
		});


		document.querySelectorAll('.ImageQuestion').forEach(img => {
			img.addEventListener('click', function() {
				const modal = document.getElementById('imageModal');
		  const modalImg = document.getElementById('modalImg');
		  img.style.display = 'none';
		  modal.style.display = 'flex';
		  modalImg.src = img.src;
			});
		});
		document.querySelectorAll('.close').forEach(img => {
			img.addEventListener('click', function() {
				document.getElementById('imageModal').style.display = 'none';
				document.querySelectorAll('.ImageQuestion').forEach(img => {
					img.style.display = 'block';
				  });
				
			});
		});
		/*	function openImageModal(imageSrc) {
		  const modal = document.getElementById('imageModal');
		  const modalImg = document.getElementById('modalImg');
		  modal.style.display = 'flex';
		  modalImg.src = imageSrc;
		}*/

		


	}


	window.selectOption = function(index, type) {
		const selectedOption = document.getElementById(`answer${index}`);
		if (type === 'single') {
			document.querySelectorAll('input[name="answer"]').forEach(option => {
				option.checked = false;
			});
			selectedOption.checked = true;
		} else if (type === 'singleImage') {
			document.querySelectorAll('input[name="answer"]').forEach(option => {
				option.checked = false;
			});
			selectedOption.checked = true;
		} else if (type === 'singleVideo') {
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

		var mins1 = Math.floor(totalSeconds / 60);
		var secs1 = totalSeconds % 60;

		// Format time as MM:SS (e.g., 02:30)
		var formattedTime1 = (mins1 < 10 ? "0" : "") + mins1 + ":" + (secs1 < 10 ? "0" : "") + secs1;
		timerElement.textContent = formattedTime1;
		interval = setInterval(function() {
			var mins = Math.floor(totalSeconds / 60);
			var secs = totalSeconds % 60;

			// Format time as MM:SS (e.g., 02:30)
			formattedTime = (mins < 10 ? "0" : "") + mins + ":" + (secs < 10 ? "0" : "") + secs;
			timerElement.textContent = formattedTime; // Update div

			if (totalSeconds === 0) {
				clearInterval(interval);
				TimeUpBool = true;
				displayResults();
				// timerElement.textContent = "🎉 Congrats! Time's up!";
				//  alert("🎉 Congrats! Time's up! 🎉"); // Show alert message
			}

			totalSeconds--;
		}, 1000);
	}

	function checkAnswer() {
		// Get the current question data and corresponding answers
		const answerData = AnswerData[currentQuestionIndex]; // Get correct answers from JSON

		// Get selected options
		const selectedOptions = document.querySelectorAll('input[name="answer"]:checked');
		var currentoptID = "";
		selectedOptions.forEach(option => {
			currentoptID = option.id.toString();
		});
		let resultStr = currentoptID.substring(currentoptID.length - 1);


		if (selectedOptions.length === 0) {
			alert("Please select an answer.");
			return;
		}

		let isCorrect = false; // Default to false

		if (quiztype === 'single') {
			// Single choice validation
			if (selectedOptions.length > 1) {
				isCorrect = false; // More than one selected for single-choice
			} else {
				const selectedValue = selectedOptions[0].value; // Get selected option value
				// Find the correct option
				const correctOption = answerData.options.find(option => option.correct);

				if (answerData.options[parseInt(resultStr)].correct) {
					isCorrect = true;
				} else {
					// console.log("No correct option found.");
					isCorrect = false;
				}
			}
		} else if (quiztype === 'multiple') {
			// Multiple choice validation
			if (selectedOptions.length === 0) {
				isCorrect = false; // No options selected for multiple-choice
			} else {
				// Go through each selected option and check if it is correct
				let allCorrect = true;

				selectedOptions.forEach(option => {
					const resultStr = option.id.toString().slice(-1); // Assuming the ID contains an index
					const selectedOptionCorrect = answerData.options[parseInt(resultStr)].correct;

					if (!selectedOptionCorrect) {
						allCorrect = false; // If any selected option is wrong, set to false
					}
				});

				// Now check if there are any extra correct options that weren't selected
				answerData.options.forEach((option, index) => {
					if (option.correct && !Array.from(selectedOptions).some(option => option.id.toString().slice(-1) === index.toString())) {
						allCorrect = false; // If there is a correct option that wasn't selected, set to false
					}
				});

				isCorrect = allCorrect; // If all selected are correct and no extra correct options are missing
			}
		}

		// Log result for debugging

		// Store the result
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
				clearInterval(interval);

				displayResults();
				parent.parent.menuEnable_fun_firstpage();							 

			}, 500);
		}
	}

	/*<p>You answered ${correctAnswers} out of ${totalQuestions} questions correctly.</p>*/
	function displayResults() {

		// document.body.style.backgroundImage = "url('images/BG_1.png')";
		document.getElementById('parentquizContainer').style.display = 'none';
		let correctAnswers = selectedAnswers.filter(answer => answer).length;
		let totalQuestions = questionsData.length;
		let percentage = (correctAnswers / totalQuestions) * 100;
		parent.parent.curAttempt++;
		//percentage=RoundToPrecision(percentage, 2);
		let passed = percentage >= passingScore;
		var resultsHtml;
		if (TimeUpBool) {
			TimeUpBool = false;
			//console.log(parent.parent.curAttempt);
			//	console.log(parent.parent.QuizAttemptLimit);
			if (parent.parent.curAttempt == parent.parent.QuizAttemptLimit) {
				resultsHtml = `
			<div class="results">
				<h2>${parent.TimeUpte}</h2>
			
				<p>${parent.resutscorecontent} ${percentage.toFixed(0)}%</p>
				<p>${passed ? parent.Resutpassed : parent.FinalResutfailed}</p>
				${!passed ? `<button class="retrybtn" id="retryBtn">${parent.retryButton}</button>` : ''}
			</div>
			`;
			} else {
				resultsHtml = `
			<div class="results">
				<h2>${parent.TimeUpte}</h2>
			
				<p>${parent.resutscorecontent} ${percentage.toFixed(0)}%</p>
				<p>${passed ? parent.Resutpassed : parent.Resutfailed}</p>
				${!passed ? `<button class="retrybtn" id="retryBtn">${parent.retryButton}</button>` : ''}
			</div>
			`;
			}

		} else {
			if (parent.parent.curAttempt == parent.parent.QuizAttemptLimit) {

				resultsHtml = `
				<div class="results">
					<h2>${parent.Resulttitle}</h2>
					<p>${parent.resutscorecontent} ${percentage.toFixed(0)}%</p>
					<p>${passed ? parent.Resutpassed : parent.FinalResutfailed}</p>
					${!passed ? `<button class="retrybtn" id="retryBtn">${parent.retryButton}</button>` : ''}
				</div>
			`;
			} else {
				resultsHtml = `
				<div class="results">
					<h2>${parent.Resulttitle}</h2>
					<p>${parent.resutscorecontent} ${percentage.toFixed(0)}%</p>
					<p>${passed ? parent.Resutpassed : parent.Resutfailed}</p>
					${!passed ? `<button class="retrybtn" id="retryBtn">${parent.retryButton}</button>` : ''}
				</div>
			`;
			}

		}
		quizContainer.innerHTML = resultsHtml;

		if (!passed) {
			document.getElementById('retryBtn').addEventListener('click', retryQuiz);
		}

		if (passed) {
			//	parent.parent.curAttempt++;

		} else {
			//  console.log("parent.parent.curAttempt" + parent.parent.curAttempt)
			//  console.log("parent.parent.QuizAttemptLimit" + parent.parent.QuizAttemptLimit)
			if (parent.parent.curAttempt == parent.parent.QuizAttemptLimit) {

				document.getElementById('retryBtn').style.display = 'none';
			} else if (parent.parent.curAttempt < parent.parent.QuizAttemptLimit) {
				//parent.parent.curAttempt++;
				document.getElementById('retryBtn').addEventListener('click', retryQuiz);
			}


		}

		parent.parent.scoreSubmit(percentage);
        parent.parent.QuizpageVistedList();
		parent.parent.enablePrevbtn();

	}

	function displayResultsScorm() {
	//   document.body.style.backgroundImage = "url('images/BG_1.png')";
		document.getElementById('parentquizContainer').style.display = 'none';
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
        <p>${passed ? parent.Resutpassed : parent.FinalResutfailed}</p>
       
    </div>
`;
		/* ${!passed ? '<button class="retrybtn" id="retryBtn">Retry</button>' : '<button class="retrybtn" id="retryBtn">Retry</button>'}*/
		quizContainer.innerHTML = resultsHtml;
		/*document.getElementById('retryBtn').addEventListener('click', retryQuiz);
		if (!passed) {
			document.getElementById('retryBtn').addEventListener('click', retryQuiz);
		}*/

		parent.parent.enablePrevbtn();		
}
	function startBut() {
		// document.body.style.backgroundImage = "url('images/BG.png')";
		loadQuestion(currentQuestionIndex);
		if (parseInt(parent.duration) == 0) {

			document.getElementById('timer').style.display = 'none';
		} else {

			startCountdown(parseInt(parent.duration));
		}
		 parent.parent.disableNextPrevMenu();
	}

	function retryQuiz() {
		parent.getRandomQuestionsAndAnswers(parent.answers, parent.questions)
		// parent.fetchData();
		//  parent.shuffleOptions();
		questionsData = parent.mainData; // From questions.json
		AnswerData = parent.AnsData; // From answers.json
		passingScore = parent.PassingScore; // Passing score
		parent.parent.QuizAttemptLimit = parent.QuizAttempt;
		currentQuestionIndex = 0;
		selectedAnswers = [];
		loadQuestion(currentQuestionIndex);
		document.getElementById('parentquizContainer').style.display = 'block';
		// document.body.style.backgroundImage = "url('images/BG.png')";

		if (parseInt(parent.duration) == 0) {

			document.getElementById('timer').style.display = 'none';
		} else {

			startCountdown(parseInt(parent.duration));
		}
		 parent.parent.disableNextPrevMenu();
    }
	

	loadQuestions();
	parent.parent.passScoreTOarticulate();
	/* console.log("i am here");
	 console.log("parent.parent.curAttempt" + parent.parent.curAttempt)
	 console.log("parent.parent.QuizAttemptLimit" + parent.parent.QuizAttemptLimit)*/
	if (parent.parent.scoreActive && parent.parent.curAttempt == parent.parent.QuizAttemptLimit) {
		setTimeout(() => {
			//console.log("parent.parent.curAttempt" + parent.parent.curAttempt)
			// console.log("parent.parent.QuizAttemptLimit" + parent.parent.QuizAttemptLimit)
			displayResultsScorm();
		}, 50);
	} else {
		Startpage();
	}
});