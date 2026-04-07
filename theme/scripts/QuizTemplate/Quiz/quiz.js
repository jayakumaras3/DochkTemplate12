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
	var QuizMode = "";
	var PostAttemptType =true;
	
	var attempt = 0;

	var quiztype = "";
	var QuestionHead="";
	var selectedOptions="";
	function loadQuestions() {
		questionsData = parent.mainData; // From questions.json
		AnswerData = parent.AnsData; // From answers.json
		passingScore = parent.PassingScore; // Passing score
		parent.parent.QuizAttemptLimit = parent.QuizAttempt;
		QuizMode = parent.QuizMode;
		PostAttemptType = parent.PostAttemptType;
		let newMargin = "6%"; // Example dynamic value
		document.getElementById("quizContainer").style.marginLeft = newMargin;
		document.getElementById("quizContainer").style.width = "94%";

	}

	function Startpage() {
		if(QuizMode =="PreTest")
		{
					if(parent.parent.getSuspendString("str4")=="" ||parent.parent.getSuspendString("str4")==null)
					{
						
					}
					else{
						//alert();
						parent.parent.PrecurAttempt = Number(parent.parent.getSuspendString("str4"));
					}
					
						if (parent.parent.PrecurAttempt == parent.parent.QuizAttemptLimit) {
							attempt = parseInt(parent.parent.PrecurAttempt);
						}
						else{
							attempt = parseInt(parent.parent.PrecurAttempt) + 1;
						}
						const resultsHtml = `
						<div id ="Startpageid" class="Startpage FSize20" >
							<p class="headerAss FSize38" id="startpage-header" tabindex="0">${parent.startpageheader}</p>
							<div class="Startpage_sub FSize20">
								<p id="startpage-description" tabindex="0">${parent.startpagedescrip}</p>
								<p id="total-questions" tabindex="0">${parent.TotalQuestionste}${parent.totalQuestions}</p>
								<p id="passing-score" tabindex="0">${parent.passingScorete}${passingScore}</p>
								<p id="quiz-attempts" tabindex="0">${parent.QuizAttemptte} ${attempt}/${parent.QuizAttempt}</p>
								<p id="duration" tabindex="0" aria-live="polite">${parent.durationte}${parent.duration1} ${parent.MinutesText}</p>
								<p id="startpage-description-1" tabindex="0">${parent.startpagedescrip1}</p>
							</div>
							<button class="Startpagebtn ColorSet_CR FSize20" id="Startpage" tabindex="0"  onclick="startQuiz()">
								${parent.startButton}
							</button><br>
							<p id="inste" class="ID_ColorSet_CR FSize20" tabindex="0">${parent.clicknote}</p>


						</div>
					`;


				quizContainer.innerHTML = resultsHtml;
				document.getElementById('Startpage').addEventListener('click', startBut);
				if (parseInt(parent.duration) == 0) {

					document.getElementById('durationte').style.display = 'none';
				}
				parent.parent.disablenextbtn();
		}
		else{
			
		attempt = parseInt(parent.parent.curAttempt) + 1;
		//    document.body.style.backgroundImage = "url('images/BG_1.png')";
		const resultsHtml = `
		  <div id ="Startpageid" class="Startpage FSize20" >
			<p class="headerAss FSize38" id="startpage-header" tabindex="0">${parent.startpageheader}</p>
			<div class="Startpage_sub FSize20">
				<p id="startpage-description" tabindex="0">${parent.startpagedescrip}</p>
				<p id="total-questions" tabindex="0">${parent.TotalQuestionste}${parent.totalQuestions}</p>
				<p id="passing-score" tabindex="0">${parent.passingScorete}${passingScore}</p>
				<p id="quiz-attempts" tabindex="0">${parent.QuizAttemptte} ${attempt}/${parent.QuizAttempt}</p>
				<p id="durationte" tabindex="0" aria-live="polite">${parent.durationte}${parent.duration1} ${parent.MinutesText}</p>
				<p id="startpage-description-1" tabindex="0">${parent.startpagedescrip1}</p>
			</div>
			<button class="Startpagebtn ColorSet_CR FSize20" id="Startpage" tabindex="0" aria-label="${parent.startButton}">
				${parent.startButton}
			</button><br>
		   <p id="inste" class="ID_ColorSet_CR FSize20" tabindex="0" >${parent.clicknote}</p>


		</div>
		`;

		quizContainer.innerHTML = resultsHtml;
		document.getElementById('Startpage').addEventListener('click', startBut);
		if (parseInt(parent.duration) == 0) {

			document.getElementById('durationte').style.display = 'none';
		}

		}
		
	}

	function toggleZoom(image) {
		// Check if the image is already zoomed in
		if (image.classList.contains('zoom-in')) {
			image.classList.remove('zoom-in');
		} else {
			image.classList.add('zoom-in');
		}
	}

	function loadQuestion(index) {

		let newMargin = "7%"; // Example dynamic value
		document.getElementById("quizContainer").style.marginLeft = newMargin;
		document.getElementById("quizContainer").style.width = "89%";

		const questionData = questionsData[index];

		const optionsHtml = questionData.options.map((option, idx) => {
			quiztype = questionData.type;
			const valueAttr = option.value || option.text; // define inside each block
			if (questionData.type === 'single') {
				return `
                   <!-- Custom radio button wrapper. Native input is hidden from screen readers to avoid duplicate announcements.
                        Removed aria-labelledby to prevent VoiceOver from reading the label twice on iOS.
                        Parent div manages the accessible name through its role="radio" and label content. -->
                   <div id="Opt${idx}" class="answer FSize20"  onkeydown="handleKeydown(event, ${idx}, '${questionData.type}')" onclick="selectOption(${idx}, '${questionData.type}')" role="radio" aria-checked="false" tabindex="0">
					<input class="radioBut clicken" type="radio" id="answer${idx}" name="answer" value="${valueAttr}" data-correct="${option.correct}" tabindex="-1" aria-hidden="true">
						<label class="clicken" for="answer${idx}" id="answer${idx}-label">${option.text}</label>
					</div>
                `;

			} else if (questionData.type === 'multiple') {
				const valueAttr = option.value || option.text; // define inside each block
				return `
				  <!-- Custom checkbox wrapper. Native input is hidden from screen readers to avoid duplicate announcements.
                       Removed aria-labelledby to prevent VoiceOver from reading the label twice on iOS.
                       Parent div manages the accessible name through its role="checkbox" and label content. -->
				  <div id="Opt${idx}" class="answer FSize20"  onkeydown="handleKeydown(event, ${idx}, '${questionData.type}')" onclick="selectOption(${idx}, '${questionData.type}')" role="checkbox" aria-checked="false" tabindex="0">
					<input class="checkbox" type="checkbox" id="answer${idx}" name="answer" value="${valueAttr}" data-correct="${option.correct}" tabindex="-1" aria-hidden="true">
					<label class="clicken" for="answer${idx}" id="answer${idx}-label" onclick="event.stopPropagation();">${option.text}</label>
				</div>

                `;
			}

		}).join('');
		var questionHtml = "";
		QuestionHead=questionData.question;
		if (questionData.type === 'single') {
			if (questionData.images == null && questionData.video == null) {
				questionHtml = `
			
					<div class="questionContainer">
					<div tabindex="0" class="question FSize20" id="question-header" >
						${questionData.question}
						<div class="redtext instext FSize20" aria-live="polite">${parent.Questiontext}</div>
					</div>
					<div class="options" aria-labelledby="question-header">
						${optionsHtml}
					</div>
					<button class="btn btn1 ColorSet_CR FSize20" id="submitBtn" tabindex="0" disabled aria-label="${parent.quizButton}">
						${parent.quizButton}
					</button>
					<div class="feedback" tabindex="0" id="feedback" aria-live="polite"></div>
				</div>
					
				`;
			} else if (questionData.images != null && questionData.video == null) {
					questionHtml = `
					<div class="questionContainer">
					  <div tabindex="0" class="question FSize20">
						${questionData.question}
						<div class="redtext instext FSize20">${parent.QuestionMcQtext}</div>
					  </div>
					  
					  <div class="contentWrapper">
						<div class="options1">${optionsHtml}<button tabindex="0"  class="btn ColorSet_CR FSize20" id="submitBtn" disabled>${parent.quizButton}</button></div>
						<div><img  tabindex="0" class="ImageQuestion zoomable" id="zoomableImage" src="${questionData.images}" alt="image"></img><br><div  tabindex="0" class="redtext1 instext">${parent.ImageZoomText}</div></div>
						 
					  </div>					 
					  <div class="feedback" id="feedback"></div>
					</div>

				  <!-- Responsive Image Modal -->
				  <div id="imageModal" aria-label="Image" class="modal">
					<span aria-label="close" tabindex="0" class="close FSize3_RM" >&times;</span>
					<div class="modal-content-wrapper">
					  <img class="modal-img" id="modalImg">
					</div>
				  </div>
				`;
			} else if (questionData.images == null && questionData.video != null) {
				questionHtml = `
				<div class="questionContainer">
					<div tabindex="0" class="question FSize20">${questionData.question}<div class="redtext instext FSize20">${parent.Questiontext}</div></div>
					 <div class="contentWrapper">
						<div class="options1">${optionsHtml}</div>
						<video id="myvideoQuiz" class="VideoQuestion" src="${questionData.video}" controls controlsList="nodownload noremoteplayback" disablePictureInPicture allowfullscreen>
</video>
					  </div>
					<button tabindex="0"  class="btn btn1 ColorSet_CR FSize20" id="submitBtn" disabled>${parent.quizButton}</button>
					<div class="feedback" id="feedback"></div>
				</div>
				
			`;
			}
		} else if (questionData.type === 'multiple') {
			if (questionData.images == null && questionData.video == null) {

				questionHtml = `
			
					<div class="questionContainer" >
						
						<div tabindex="0" class="question FSize20">${questionData.question}<div class="redtext instext FSize20">${parent.QuestionMcQtext}</div></div>
						<div class="options">${optionsHtml}</div>
						<button tabindex="0"  class="btn btn1 ColorSet_CR FSize20" id="submitBtn" disabled>${parent.quizButton}</button>
						<div class="feedback" id="feedback"></div>
					</div>
					
				`;
			} else if (questionData.images != null && questionData.video == null) {
				questionHtml = `
			
					<div class="questionContainer" >
						
						<div tabindex="0" class="question FSize20">${questionData.question}<div class="redtext instext FSize20">${parent.QuestionMcQtext}</div></div>
						
						 <div class="contentWrapper">
							<div class="options1">
							  ${optionsHtml}
							  <button tabindex="0"  class="btn ColorSet_CR FSize20" id="submitBtn" disabled>${parent.quizButton}</button>
							</div>
							<div class="imageContainer">
							  <img  tabindex="0" class="ImageQuestion zoomable" id="zoomableImage" src="${questionData.images}" alt="Image">
							  <div  tabindex="0" class="redtext1 instext FSize20">${parent.ImageZoomText}</div>
							</div>
						  </div>
					 
					
						<div class="feedback" id="feedback"></div>
					</div>
					
					 <!-- Responsive Image Modal -->
				  <div id="imageModal" class="modal">
					<span class="close FSize3_RM" >&times;</span>
					<div class="modal-content-wrapper">
					  <img class="modal-img" id="modalImg">
					</div>
				  </div>
				`;
			} else if (questionData.images == null && questionData.video != null) {
				questionHtml = `
		
					<div class="questionContainer" >
						
						<div tabindex="0" class="question FSize20">${questionData.question}<div class="redtext instext FSize20">${parent.QuestionMcQtext}</div></div>
						<div class="contentWrapper">
						<div class="options1">${optionsHtml} <button tabindex="0"  class="btn ColorSet_CR FSize20" id="submitBtn" disabled>${parent.quizButton}</button></div>
						<video id="myvideoQuiz" class="VideoQuestion" src="${questionData.video}" controls controlsList="nodownload noremoteplayback" disablePictureInPicture allowfullscreen>
</video>
					  </div>
						
						<div class="feedback" id="feedback"></div>
					</div>
					`;

			}

		}
		const parentquestionHtml = `
		 
		 <div class="parentquestion FSize18 parentquestion_CR"><p
  tabindex="0" id="q1"
  
>
  ${parent.QuestionCountText} ${index + 1} ${parent.QuestionOFText} ${questionsData.length}
</p>
<div id="timer">${formattedTime}</div></div>
		 	
		 
		 
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
		
  const zoomableImage = document.getElementById("zoomableImage");
const modal = document.getElementById("imageModal");
const modalImg = document.getElementById("modalImg");
const closeBtn = document.getElementById("closeModal");

if (zoomableImage) {
  // Make image focusable and handle keyboard events for opening the modal
  zoomableImage.setAttribute("tabindex", "0"); // Ensure it's focusable

  zoomableImage.addEventListener("keydown", (event) => {
    if (event.key === "Enter" || event.key === " " || event.key === "Spacebar") {
      openModal();
    }
  });

  zoomableImage.addEventListener("click", () => {
	 // console.log("open Image");
	  openModal();
  });
}
var imageopen="";
function openModal() {
	modal.style.display = "flex";
	modalImg.src = zoomableImage.src;
	imageopen="open";
	modalImg.onload = () => {
    const imgNaturalWidth = modalImg.naturalWidth;
    const imgNaturalHeight = modalImg.naturalHeight;

    const screenWidth = window.innerWidth * 0.9;
    const screenHeight = window.innerHeight * 0.9;

    const imgRatio = imgNaturalWidth / imgNaturalHeight;
    const screenRatio = screenWidth / screenHeight;

    if (imgRatio > screenRatio) {
      modalImg.style.width = screenWidth + "px";
      modalImg.style.height = "auto";
    } else {
      modalImg.style.height = screenHeight + "px";
      modalImg.style.width = "auto";
    }
  };

  // Set focus to the modal for keyboard navigation
  modal.focus();
}

// Close the modal when clicking outside the image (on modal background)
modal?.addEventListener("click", (event) => {
  if (event.target === closeBtn) {
    closeModal();
  }
});
closeBtn?.addEventListener("keydown", (event) => {
  if (event.key === "Enter") {
	
    event.preventDefault(); // Prevent page scroll on Space
    closeModal();
  }
});

// Close the modal with the Esc key
window.addEventListener("keydown", (event) => {
  if (event.key === "Escape" ) {
	 // alert();
	 
    closeModal();
	
  }
  
});

function closeModal() {
  modal.style.display = "none";
  zoomableImage.focus(); // Return focus to the zoomable image after closing
}

window.addEventListener("resize", () => {
  if (modal.style.display === "flex" && zoomableImage) {
    zoomableImage.click(); // Reapply scaling on resize
  }
});

// Check if the modal and modal image exist
	if (modal && modalImg) {
	  // Ensure modal is focusable for accessibility
	  modal.setAttribute("tabindex", "-1");

	  modal.addEventListener("focus", () => {
		if (modalImg) {
		  modalImg.setAttribute("tabindex", "0"); // Make the image focusable when modal is open
		}
	  });
	} else {
	  console.error('Modal or modal image is missing.');
	}

  window.addEventListener("resize", () => {
    if (modal.style.display === "flex" && zoomableImage) {
      zoomableImage.click(); // Reapply scaling
    }
  });
	
	

		document.querySelectorAll('.close').forEach(img => {
			img.addEventListener('click', function() {
				document.getElementById('imageModal').style.display = 'none';
				document.querySelectorAll('.ImageQuestion').forEach(img => {
					img.style.display = 'block';
				  });
				
			});
		});
		
		

		


	}
window.handleKeydown=function(event, idx, questionType) {
    // Check if the key pressed is Enter or Space
    if (event.key === 'Enter' || event.key === ' ') {
        event.preventDefault(); // Prevent default behavior for Enter or Space
        selectOption(idx, questionType); // Call the same selectOption function on Enter/Space
    }
}

	window.selectOption = function(index, type) {
		const selectedOption = document.getElementById(`answer${index}`);
		const div = document.getElementById(`Opt${index}`);
		if (type === 'single') {
			document.querySelectorAll('input[name="answer"]').forEach(option => {
				option.checked = false;
			});
			document.querySelectorAll('.answer').forEach(div => {
				div.setAttribute("aria-checked", "false");
			});
			
			selectedOption.checked = true;
			 div.setAttribute("aria-checked", "true");
		} else if (type === 'singleImage') {
			document.querySelectorAll('input[name="answer"]').forEach(option => {
				option.checked = false;
			});
				document.querySelectorAll('.answer').forEach(div => {
				div.setAttribute("aria-checked", "false");
			});
			selectedOption.checked = true;
			 div.setAttribute("aria-checked", "true");
		} else if (type === 'singleVideo') {
			document.querySelectorAll('input[name="answer"]').forEach(option => {
				option.checked = false;
			});
				document.querySelectorAll('.answer').forEach(div => {
				div.setAttribute("aria-checked", "false");
			});
			selectedOption.checked = true;
			 div.setAttribute("aria-checked", "true");
		} else {
			selectedOption.checked = !selectedOption.checked;
			div.setAttribute("aria-checked", selectedOption.checked.toString());
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
	console.log("QuestionHead:", QuestionHead);
	;

	const answerData = AnswerData[currentQuestionIndex];
	console.log("QuestionHead:", answerData)
	const selectedOptions = document.querySelectorAll('input[name="answer"]:checked');

	if (selectedOptions.length === 0) {
		alert("Please select an answer.");
		return;
	}

	let isCorrect = false;
	let learnerResponse = [];
	let correctResponse = [];

	if (quiztype === 'single') {
		if (selectedOptions.length === 1) {
			const selectedValue = selectedOptions[0].value;
			learnerResponse.push(selectedValue);

			const selectedIndex = parseInt(selectedOptions[0].id.replace("answer", ""));
			isCorrect = answerData.options[selectedIndex].correct;

			// Correct option
			/*const correctOption = answerData.options.find(opt => opt.correct);
			if (correctOption) correctResponse.push(correctOption.value);*/
			for (let i = 0; i < answerData.options.length; i++) {
				const opt = answerData.options[i];
				const labelId = `answer${i}-label`;
				const label = document.getElementById(labelId);
				if (opt.correct) {
					 const labelText = label.textContent || label.innerText;
					correctResponse.push(labelText);
				}
			}
		}
	} else if (quiztype === 'multiple') {
		let allCorrect = true;

		selectedOptions.forEach(option => {
			const idx = parseInt(option.id.replace("answer", ""));
			learnerResponse.push(option.value);
			if (!answerData.options[idx].correct) {
				allCorrect = false;
			}
		});

		/*answerData.options.forEach((opt, idx) => {
			if (opt.correct) {
				correctResponse.push(opt.value);
				const selected = Array.from(selectedOptions).some(sel => parseInt(sel.id.replace("answer", "")) === idx);
				if (!selected) allCorrect = false;
			}
		});*/
		for (let idx = 0; idx < answerData.options.length; idx++) {
			const opt = answerData.options[idx];
				const labelId = `answer${idx}-label`;
				const label = document.getElementById(labelId);
			if (opt.correct) {
				const labelText = label.textContent || label.innerText;
				correctResponse.push(labelText);
				const selected = Array.from(selectedOptions).some(sel => parseInt(sel.id.replace("answer", "")) === idx);
				if (!selected) allCorrect = false;
			}
		}


		isCorrect = allCorrect;
	}

	// 🔽 SCORM Reporting
	let interactionIndex = currentQuestionIndex;
	let interactionId = `Scene2_Slide${interactionIndex + 1}_${quiztype}_0_0`;
	let learnerResponseStr = learnerResponse.join(',');
	let correctResponseStr = correctResponse.join(',');
	let result = isCorrect ? "correct" : "incorrect";
	let timestamp = getSCORMTimestamp();
	let objectiveId = "M1-Know_Yourself___Understanding_Personal_Culture";
	let description = QuestionHead;



	// SCORM LMS SetValues (Uncomment in SCORM environment)
	/*
	LMSSetValue(`cmi.interactions.${interactionIndex}.id`, interactionId);
	LMSSetValue(`cmi.interactions.${interactionIndex}.type`, "choice");
	LMSSetValue(`cmi.interactions.${interactionIndex}.objectives.0.id`, objectiveId);
	LMSSetValue(`cmi.interactions.${interactionIndex}.timestamp`, timestamp);
	LMSSetValue(`cmi.interactions.${interactionIndex}.learner_response`, learnerResponseStr);
	LMSSetValue(`cmi.interactions.${interactionIndex}.result`, result);
	LMSSetValue(`cmi.interactions.${interactionIndex}.description`, description);
	LMSCommit("");
	*/
	parent.parent.setScormInteractionData(interactionIndex, interactionId, objectiveId, timestamp, learnerResponseStr, correctResponseStr, result, description)
	selectedAnswers.push(isCorrect);
	moveToNextQuestion();
}

function getSCORMTimestamp() {
	const now = new Date();
	const offset = -now.getTimezoneOffset();
	const sign = offset >= 0 ? "+" : "-";
	const pad = num => String(num).padStart(2, '0');
	const iso = now.toISOString().slice(0, 19);
	const hours = pad(Math.floor(Math.abs(offset) / 60));
	const minutes = pad(Math.abs(offset) % 60);
	return `${iso}${sign}${hours}:${minutes}`;
}
	/*function checkAnswer() {
		console.log(QuestionHead);
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
				console.log(option);
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
	}*/


	function moveToNextQuestion() {
		document.getElementById('submitBtn').disabled = true;
		currentQuestionIndex++;
			if(!PostAttemptType)
			{
				SetScoreEachQuestion();
			}
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
function SetScoreEachQuestion() {
				
				let correctAnswers = selectedAnswers.filter(answer => answer).length;
				let totalQuestions = questionsData.length;
				let percentage = (correctAnswers / totalQuestions) * 100;
				let passed = percentage >= passingScore;
				var tempdata=percentage.toFixed(0);
				if(QuizMode =="PreTest")
				{
					parent.parent.pretestSuccess=true;
				}
					parent.parent.scoreSubmit_PostQuiz(tempdata);
					parent.parent.pretestSuccess=false;
}
	/*<p>You answered ${correctAnswers} out of ${totalQuestions} questions correctly.</p>*/
	function displayResults() {
		
			
				
				document.getElementById('parentquizContainer').style.display = 'none';
				let correctAnswers = selectedAnswers.filter(answer => answer).length;
				let totalQuestions = questionsData.length;
				let percentage = (correctAnswers / totalQuestions) * 100;
				let passed = percentage >= passingScore;
				
				var resultsHtml;
				if (TimeUpBool) {
					TimeUpBool = false;
					if(QuizMode =="PreTest")
					{
						resultsHtml = `
							<div class="results FSize20">
								<p id="resultsHeading" role="text" tabindex="0">${parent.TimeUpte}</p>
							
								<p tabindex="0">${parent.resutscorecontent} ${percentage.toFixed(0)}%</p>
								<p> tabindex="0"${passed ? parent.Resutpassed : parent.FinalResutfailed}</p>
							</div>
							`;
							if(PostAttemptType)
							{
								parent.parent.PrecurAttempt++;
							}
							parent.parent.setSuspendString("str4",parent.parent.PrecurAttempt);
					
							
					}
					else
					{
						if(QuizMode =="PreTest")
						{
							resultsHtml = `
								<div class="results FSize20" >
								<p id="resultsHeading" role="text" tabindex="0">${parent.TimeUpte}</p>

								  <p tabindex="0" aria-live="polite">
									<strong>${parent.resutscorecontent}</strong> ${percentage.toFixed(0)}%
								  </p>

								  <p tabindex="0" aria-live="polite">
									${passed ? parent.Resutpassed : parent.FinalResutfailed}
								  </p>
								</div>
								`;
								
						}
						else{
							
							if (parent.parent.curAttempt == parent.parent.QuizAttemptLimit) {
								resultsHtml = `
							<div class="results FSize20" >
							<p id="resultsHeading" role="text" tabindex="0">${parent.TimeUpte}</p>
							 

							  <p tabindex="0" aria-live="polite">
								<strong tabindex="0">${parent.resutscorecontent}</strong> ${percentage.toFixed(0)}%
							  </p>

							  <p tabindex="0" aria-live="polite">
								${passed ? parent.Resutpassed : parent.FinalResutfailed}
							  </p>

							  ${!passed ? `
								<button tabindex="0"
								  class="retrybtn ColorSet_CR FSize20" 
								  id="retryBtn" 
								  type="button" 
								  aria-label="Retry the quiz. ${parent.retryButton}">
								  ${parent.retryButton}
								</button>` : ''}
							</div>
							`;
							} else {
								resultsHtml = `
							<div class="results FSize20" >
						  <p id="resultsHeading" role="text" tabindex="0">${parent.TimeUpte}</p>

						  <p tabindex="0" aria-live="polite">
							<strong tabindex="0">${parent.resutscorecontent}</strong> ${percentage.toFixed(0)}%
						  </p>

						  <p tabindex="0" aria-live="polite">
							${passed ? parent.Resutpassed : parent.Resutfailed}
						  </p>

						  ${!passed ? `
							<button tabindex="0"
							  class="retrybtn ColorSet_CR FSize20" 
							  id="retryBtn" 
							  type="button" 
							  aria-label="Retry the quiz: ${parent.retryButton}">
							  ${parent.retryButton}
							</button>` : ''}
						</div>

							`;
							}
						}
					}

				} else {
					if(QuizMode =="PreTest")
					{
						if (parent.parent.PrecurAttempt == parent.parent.QuizAttemptLimit) {
							
						}
						else{
							if(PostAttemptType)
							{
								
								parent.parent.PrecurAttempt++;
							}
						}
						parent.parent.setSuspendString("str4",parent.parent.PrecurAttempt);
						console.log(parent.parent.QuizAttemptLimit);
						if (parent.parent.PrecurAttempt == parent.parent.QuizAttemptLimit) {
						resultsHtml = `
							<div class="results FSize20">
								
								<p tabindex="0">${parent.resutscorecontent} ${percentage.toFixed(0)}%</p>
								<p tabindex="0">${passed ? parent.Resutpassed : parent.FinalResutfailed}</p>
							</div>
							`;
						}
						else {
							resultsHtml = `
							<div class="results FSize20">
							<p id="resultsHeading" role="text" tabindex="0" >${parent.Resulttitle}</p>
								
								<p tabindex="0">${parent.resutscorecontent} ${percentage.toFixed(0)}%</p>
								<p tabindex="0">${passed ? parent.Resutpassed : parent.Resutfailed}</p>
								${!passed ? `<button tabindex="0" class="retrybtn ColorSet_CR FSize20" id="retryBtn">${parent.retryButton}</button>` : ''}
							</div>
						`;
						
						}
						
					}
					else
					{
						if(QuizMode =="PostTest")
						{
							if(PostAttemptType)
							{
								
								parent.parent.curAttempt++;
							}
						}
						if (parent.parent.curAttempt == parent.parent.QuizAttemptLimit) {
						
							resultsHtml = `
							<div class="results FSize20">
								<p id="resultsHeading" role="text" tabindex="0">${parent.Resulttitle}</p>
								<p tabindex="0">${parent.resutscorecontent} ${percentage.toFixed(0)}%</p>
								<p tabindex="0">${passed ? parent.Resutpassed : parent.FinalResutfailed}</p>
								${!passed ? `<button tabindex="0" class="retrybtn FSize20" id="retryBtn">${parent.retryButton}</button>` : ''}
							</div>
						`;
						} else {
							resultsHtml = `
							<div class="results FSize20" >
								<p id="resultsHeading" role="text" tabindex="0" >${parent.Resulttitle}</p>
								<p tabindex="0">${parent.resutscorecontent} ${percentage.toFixed(0)}%</p>
								<p tabindex="0">${passed ? parent.Resutpassed : parent.Resutfailed}</p>
								${!passed ? `<button tabindex="0" class="retrybtn ColorSet_CR FSize20" id="retryBtn">${parent.retryButton}</button>` : ''}
							</div>
						`;
						
						}
					}
				}
				quizContainer.innerHTML = resultsHtml;
				
		if(QuizMode =="PreTest")
		{
			parent.parent.enablenextbtn()
			if(passed)
			{		
				
				parent.parent.pretestSuccess = true;
				var tempdata=percentage.toFixed(0);
				parent.parent.scoreSubmit(tempdata);
				parent.parent.setSuspendString("str3", "Completed");
				parent.parent.pretestSuccess = false;
				
			}
			else{
				parent.parent.setSuspendString("str3", "Attempted");
			}
			if (parent.parent.PrecurAttempt == parent.parent.QuizAttemptLimit) {
			}
			else
			{
				const retryBtn = document.getElementById('retryBtn');
					if (retryBtn) {
						retryBtn.addEventListener('click', retryQuiz);
					}
			}
				
		}
		else{
			
				
				
				

				if (!passed) {
					const retryBtn = document.getElementById('retryBtn');
					if (retryBtn) {
						retryBtn.addEventListener('click', retryQuiz);
					}
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
				var tempdata=percentage.toFixed(0);
				parent.parent.enablePrevbtn();
				parent.parent.EnabledNextPrevMenu();
				parent.parent.QuizpageVistedList();
				parent.parent.scoreSubmit(tempdata);
				
				
		}
				
				
	}

	function displayResultsScorm() {
	//   document.body.style.backgroundImage = "url('images/BG_1.png')";
	if(QuizMode =="PreTest")
	{
		document.getElementById('parentquizContainer').style.display = 'none';
			let correctAnswers = selectedAnswers.filter(answer => answer).length;
			let totalQuestions = questionsData.length;
			let percentage = (correctAnswers / totalQuestions) * 100;
			let temppercentage;
			let passed = percentage >= passingScore;
		if(parent.parent.pretestCompleteCHeck=="Completed" || parent.parent.pretestCompleteCHeck=="Attempted")
		{
			
			if(parent.parent.pretestCompleteCHeck=="Completed") {
				temppercentage = parent.parent.scorm.get("cmi.core.score.raw");
				percentage = Number(temppercentage);
			}

			//percentage=RoundToPrecision(percentage, 2);
			
			if(parent.parent.pretestCompleteCHeck=="Attempted")
			{
				const resultsHtml = `
				<div class="results FSize20">
					<p id="resultsHeading" role="text" tabindex="0" >${parent.Resulttitle}</p>
					<p tabindex="0">${passed ? parent.Resutpassed : parent.FinalResutfailed}</p>
				   
				</div>
			`;
				quizContainer.innerHTML = resultsHtml;
			}
			else
			{
				
				const resultsHtml = `
				<div class="results FSize20">
					<p id="resultsHeading" role="text" tabindex="0" >${parent.Resulttitle}</p>
					<p tabindex="0">${parent.resutscorecontent} ${percentage.toFixed(0)}%</p>
					
				   
				</div>
			`;
				quizContainer.innerHTML = resultsHtml;
			}
		}
		else if (parent.parent.PrecurAttempt == parent.parent.QuizAttemptLimit) {
			
			const resultsHtml = `
				<div class="results FSize20">>${parent.Resulttitle}</p>
					<p tabindex="0">${passed ? parent.Resutpassed : parent.FinalResutfailed}</p>
				   
				</div>
			`;
				quizContainer.innerHTML = resultsHtml;
			
		}
	}
	else
	{
		
		
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
    <div class="results FSize20">
        <p id="resultsHeading" role="text" tabindex="0" >${parent.Resulttitle}</p>
        <p tabindex="0">${parent.resutscorecontent} ${percentage.toFixed(0)}%</p>
        <p tabindex="0">${passed ? parent.Resutpassed : parent.FinalResutfailed}</p>
       
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
}
	function startBut() {
		document.getElementById('Startpageid').style.display = 'none';
		// document.body.style.backgroundImage = "url('images/BG.png')";
		loadQuestion(currentQuestionIndex);
		if (parseInt(parent.duration) == 0) {

			document.getElementById('timer').style.display = 'none';
		} else {

			startCountdown(parseInt(parent.duration));
		}
		 parent.parent.disableNextPrevMenu();
		 if(!PostAttemptType && QuizMode !="PreTest")
		{
			
			//alert();
			parent.parent.curAttempt++;
			parent.parent.QuizpageVistedList();
			if (parent.parent.PrecurAttempt == parent.parent.QuizAttemptLimit) {
					parent.parent.pretestCompleteCHeck="Attempted";
			}
		}
		else if(!PostAttemptType && QuizMode =="PreTest")
		{
						parent.parent.PrecurAttempt++;
						parent.parent.setSuspendString("str4",parent.parent.PrecurAttempt);
		}
	}

	function retryQuiz() {
		
		console.log("retryQuiz");
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
		
		if(!PostAttemptType && QuizMode !="PreTest")
		{
			
			
			parent.parent.curAttempt++;
			parent.parent.QuizpageVistedList();
		}
		else if(!PostAttemptType && QuizMode =="PreTest")
		{
						parent.parent.PrecurAttempt++;
						parent.parent.setSuspendString("str4",parent.parent.PrecurAttempt);
		}
		 parent.parent.disableNextPrevMenu();
    }
	

	loadQuestions();
	parent.parent.passScoreTOarticulate();
	parent.parent.pretestCompleteCHeck=parent.parent.getSuspendString("str3");
	/* console.log("i am here");
	 console.log("parent.parent.curAttempt" + parent.parent.curAttempt)
	 console.log("parent.parent.QuizAttemptLimit" + parent.parent.QuizAttemptLimit)*/
	 if(QuizMode =="PreTest")
	{
		parent.parent.PrecurAttempt= Number(parent.parent.getSuspendString("str4"));
		console.log(parent.parent.PrecurAttempt);
		console.log(parent.parent.QuizAttemptLimit);
		if (parent.parent.PrecurAttempt == parent.parent.QuizAttemptLimit) {
			if(parent.parent.pretestCompleteCHeck=="Completed" || parent.parent.pretestCompleteCHeck=="Attempted")
			{
				
				setTimeout(() => {
					//console.log("parent.parent.curAttempt" + parent.parent.curAttempt)
					// console.log("parent.parent.QuizAttemptLimit" + parent.parent.QuizAttemptLimit)
						displayResultsScorm();
						
					}, 50);
				
			}
			else{
				
				setTimeout(() => {
					//console.log("parent.parent.curAttempt" + parent.parent.curAttempt)
					// console.log("parent.parent.QuizAttemptLimit" + parent.parent.QuizAttemptLimit)
						displayResultsScorm();
						
					}, 50);
			}
		}
		else
		{
			Startpage();
		}
	}
	else
	{

		if (parent.parent.scoreActive && parent.parent.curAttempt == parent.parent.QuizAttemptLimit) {
			setTimeout(() => {
				//console.log("parent.parent.curAttempt" + parent.parent.curAttempt)
				// console.log("parent.parent.QuizAttemptLimit" + parent.parent.QuizAttemptLimit)
				displayResultsScorm();
			}, 50);
		} else {
			Startpage();
		}
	}
});