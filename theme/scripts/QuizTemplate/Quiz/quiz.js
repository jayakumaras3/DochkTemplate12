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

	function ensureMenuCopyrightVisible() {
		return;
	}

	function restoreFooterCopyright() {
		return;
	}

	function applyMobileScrollFix() {
		if (window.innerWidth > 900) {
			return;
		}

		// Single scrolling context (html), not a chain of nested
		// overflow:auto containers. The previous version gave html, body,
		// AND #quizContainer each their own height:100%+overflow-y:auto --
		// three independent scrollers stacked inside each other. That's
		// fragile on real iOS Safari (confirmed via WebKit device
		// emulation): a touch-driven scroll can rubber-band the outer
		// page while the actual overflowing content -- e.g. the Submit
		// button below a tall video -- sits in the INNER container and
		// needs that container's own scrollTop moved, not the page's.
		// height:auto (not a hard 100%) on html/body lets them genuinely
		// grow to fit content; only html keeps overflow-y:auto, so the
		// browser's native page scroll is the one and only way to reach
		// anything below the fold.
		document.documentElement.style.setProperty('height', 'auto', 'important');
		document.documentElement.style.setProperty('min-height', '100%', 'important');
		document.documentElement.style.setProperty('overflow-y', 'auto', 'important');
		document.documentElement.style.setProperty('-webkit-overflow-scrolling', 'touch');

		document.body.style.setProperty('height', 'auto', 'important');
		document.body.style.setProperty('min-height', '100%', 'important');
		document.body.style.setProperty('overflow-y', 'visible', 'important');
		document.body.style.setProperty('-webkit-overflow-scrolling', 'touch');
		document.body.style.setProperty('touch-action', 'pan-y', 'important');

		if (quizContainer) {
			quizContainer.style.setProperty('height', 'auto', 'important');
			quizContainer.style.setProperty('min-height', '0', 'important');
			quizContainer.style.setProperty('overflow-y', 'visible', 'important');
			quizContainer.style.setProperty('-webkit-overflow-scrolling', 'touch');
			quizContainer.style.setProperty('padding-bottom', '24px', 'important');
			// #quizContainer's stylesheet rule gives it flex:1 1 0% (flex-basis
			// 0, flex-grow 1) to fill body's flex column on desktop. On mobile
			// that is actively harmful: body's own min-height:100% gives the
			// flex algorithm a DEFINITE distributable space even though body's
			// preferred height is auto, so flex-grow:1 greedily stretches
			// #quizContainer to fill exactly that (viewport-sized) space --
			// capping it there regardless of how much taller its own content
			// (e.g. a tall video question) actually is. overflow:visible then
			// just paints the extra content past that fixed box without it
			// ever counting as real document height, which is why html/body's
			// own scrollHeight stayed pinned to the viewport size and the
			// Submit button below the video was permanently unreachable, not
			// just scrolled-past. Only flex-basis needs to change (0% -> auto,
			// so sizing starts from actual content instead of zero); grow:1
			// stays so a short question still fills the screen, and shrink
			// stays allowed (min-height:0 above already permits shrinking to
			// nothing if truly needed). flex-shrink:0 (tried first) fixed the
			// height-capping bug but traded it for a WORSE one confirmed on
			// real WebKit: with BOTH #parentquizContainer and #quizContainer
			// refusing to shrink, an over-height column doesn't just overflow
			// at the bottom -- it renders #parentquizContainer (and the top of
			// the question) at a NEGATIVE offset above the viewport, with no
			// way to scroll up far enough to recover it. flex-basis:auto with
			// shrink left enabled avoids that.
			quizContainer.style.setProperty('flex', '1 1 auto', 'important');
		}
	}
	function loadQuestions() {
		questionsData = parent.mainData; // From questions.json
		AnswerData = parent.AnsData; // From answers.json
		passingScore = parent.PassingScore; // Passing score
		parent.parent.QuizAttemptLimit = parent.QuizAttempt;
		QuizMode = parent.QuizMode;
		PostAttemptType = parent.PostAttemptType;
		quizContainer.classList.add('quiz-start-view');
		parentquizContainer.style.display = 'none';
		applyMobileScrollFix();
		ensureMenuCopyrightVisible();

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
		applyMobileScrollFix();
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

	function ensureSubmitSection() {
		const questionContainer = quizContainer.querySelector('.questionContainer');
		const submitBtn = quizContainer.querySelector('#submitBtn');

		if (!questionContainer || !submitBtn) {
			return;
		}

		let submitSection = questionContainer.querySelector('.submit-section');
		if (!submitSection) {
			submitSection = document.createElement('div');
			submitSection.className = 'submit-section';
			const optionsSection = questionContainer.querySelector('.options, .options1, .contentWrapper');
			if (optionsSection && optionsSection.parentNode === questionContainer) {
				optionsSection.insertAdjacentElement('afterend', submitSection);
			} else {
				questionContainer.appendChild(submitSection);
			}
		}

		submitSection.appendChild(submitBtn);
	}

	// Land every newly rendered question (or the results page) at the top.
	// Scroll positions survive an innerHTML swap: the persistent scrollers --
	// #quizContainer on touch layouts, the iframe window on phones (where
	// applyMobileScrollFix makes the body scroll), and the COURSE PLAYER's
	// own page two frames up (its sticky header then covers the
	// "Questions X of Y" bar) -- all keep wherever the previous question
	// left them. Called once per render from loadQuestion/displayResults;
	// no timers, no listeners.
	function resetQuizScroll() {
		if (quizContainer) {
			quizContainer.scrollTop = 0;
			quizContainer.querySelectorAll('.options, .options1').forEach(function (el) {
				el.scrollTop = 0;
			});
		}
		// Always instant, never 'behavior:smooth'. A smooth/animated scroll
		// takes real time to finish (~300-500ms), and media (particularly a
		// <video>) can still be settling its own layout during that window --
		// if the page's scrollable height changes mid-animation, the
		// animation's "resting" position is no longer top:0 relative to the
		// NEW height, silently leaving the top of the question (and the
		// dark "Questions X of Y" bar) scrolled just out of reach with no
		// way to scroll further up to recover it. An instant jump has no
		// such window: by the time anything else can reflow, scroll is
		// already pinned to 0.
		var w = window;
		for (var depth = 0; depth < 4; depth++) {
			try {
				w.scrollTo(0, 0);
			} catch (e) {
				break; // cross-origin ancestor: nothing above is ours to scroll
			}
			if (!w.parent || w.parent === w) {
				break;
			}
			w = w.parent;
		}
	}

	// Reusable media column: every branch below (single/multiple type x
	// image/video/neither) renders through this. Image and video get their
	// OWN independent panel wrapper (.quiz-image-panel / .quiz-video-panel)
	// with no shared sizing class between them, per Quiz_style.css's "Media
	// panels" section -- styling one can never affect the other. When a
	// question has neither, this renders nothing at all (not even a hidden
	// placeholder): .options/.options1 in .contentWrapper already has
	// flex:1 1 auto, so with no sibling to share the row with it naturally
	// expands to the full width -- no extra CSS needed for that case.
	function renderMediaPanel(questionData) {
		const hasImage = questionData.images != null;
		const hasVideo = questionData.video != null;

		if (!hasImage && !hasVideo) {
			return '';
		}

		const imagePanelHtml = hasImage
			? `
				<div class="quiz-image-panel">
					<img tabindex="0" class="ImageQuestion zoomable" id="zoomableImage" src="${questionData.images}" alt="image">
				</div>
				<div tabindex="0" class="quiz-image-panel-caption">${parent.ImageZoomText}</div>
			`
			: '';
		const videoPanelHtml = hasVideo
			? `
				<div class="quiz-video-panel">
					<video id="myvideoQuiz" class="VideoQuestion" src="${questionData.video}" controls controlsList="nodownload noremoteplayback" disablePictureInPicture allowfullscreen></video>
				</div>
			`
			: '';

		// Modifier class lets Quiz_style.css size the column differently per
		// media type (image: fixed ~540px display width; video: unchanged
		// 35%-of-row/400px-cap) without the two ever sharing a width rule.
		const columnModifier = hasImage ? ' quiz-media-column--image' : ' quiz-media-column--video';

		return `
			<div class="quiz-media-column${columnModifier}">
				${imagePanelHtml}${videoPanelHtml}
			</div>
		`;
	}

	function renderImageModal() {
		return `
			<div id="imageModal" aria-label="Image" class="modal">
				<span aria-label="close" tabindex="0" class="close FSize3_RM">&times;</span>
				<div class="modal-content-wrapper">
					<img class="modal-img" id="modalImg">
				</div>
			</div>
		`;
	}

	function loadQuestion(index) {

		quizContainer.classList.remove('quiz-start-view');
		quizContainer.style.marginLeft = '';
		quizContainer.style.width = '';
		parentquizContainer.style.display = '';

		const questionData = questionsData[index];

		const optionsHtml = questionData.options.map((option, idx) => {
			quiztype = questionData.type;
			const valueAttr = option.value || option.text; // define inside each block
			if (questionData.type === 'single') {
				return `
                   <div id="Opt${idx}" class="answer FSize20"  onkeydown="handleKeydown(event, ${idx}, '${questionData.type}')" onclick="selectOption(${idx}, '${questionData.type}')" role="radio" aria-checked="false" tabindex="0" aria-labelledby="answer${idx}-label">
					<input class="radioBut clicken" type="radio" id="answer${idx}" name="answer" value="${valueAttr}" data-correct="${option.correct}" tabindex="-1">
						<label class="clicken" for="answer${idx}" id="answer${idx}-label">${option.text}</label>
					</div>
                `;

			} else if (questionData.type === 'multiple') {
				const valueAttr = option.value || option.text; // define inside each block
				return `
				  <div id="Opt${idx}" class="answer FSize20"  onkeydown="handleKeydown(event, ${idx}, '${questionData.type}')" onclick="selectOption(${idx}, '${questionData.type}')" role="checkbox" aria-checked="false" tabindex="0" aria-labelledby="answer${idx}-label">
					<input class="checkbox" type="checkbox" id="answer${idx}" name="answer" value="${valueAttr}" data-correct="${option.correct}" tabindex="-1">
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
					<div tabindex="0" class="question FSize16" id="question-header" >
						${questionData.question}
						<div class="redtext instext FSize16" aria-live="polite">${parent.Questiontext}</div>
					</div>
					<div class="contentWrapper">
					<div class="options" aria-labelledby="question-header">
						${optionsHtml}
					</div>
					${renderMediaPanel(questionData)}
					</div>
					<button class="btn btn1 ColorSet_CR FSize16" id="submitBtn" tabindex="0" aria-label="${parent.quizButton}">
						${parent.quizButton}
					</button>
					<div class="feedback" tabindex="0" id="feedback" aria-live="polite"></div>
				</div>
					
				`;
			} else if (questionData.images != null && questionData.video == null) {
					questionHtml = `
					<div class="questionContainer">
					  <div tabindex="0" class="question FSize16">
						${questionData.question}
						<div class="redtext instext FSize16">${parent.QuestionMcQtext}</div>
					  </div>
					  
					  <div class="contentWrapper">
						<div class="options1">${optionsHtml}<button tabindex="0"  class="btn ColorSet_CR FSize16" id="submitBtn">${parent.quizButton}</button></div>
						${renderMediaPanel(questionData)}
						 
					  </div>					 
					  <div class="feedback" id="feedback"></div>
					</div>

				  ${renderImageModal()}
				`;
			} else if (questionData.images == null && questionData.video != null) {
				questionHtml = `
				<div class="questionContainer">
					<div tabindex="0" class="question FSize16">${questionData.question}<div class="redtext instext FSize16">${parent.Questiontext}</div></div>
					 <div class="contentWrapper">
						<div class="options1">${optionsHtml}</div>
						${renderMediaPanel(questionData)}
					  </div>
					<button tabindex="0"  class="btn btn1 ColorSet_CR FSize16" id="submitBtn">${parent.quizButton}</button>
					<div class="feedback" id="feedback"></div>
				</div>

			`;
			}
		} else if (questionData.type === 'multiple') {
			if (questionData.images == null && questionData.video == null) {

				questionHtml = `

					<div class="questionContainer" >

						<div tabindex="0" class="question FSize16">${questionData.question}<div class="redtext instext FSize16">${parent.QuestionMcQtext}</div></div>
						<div class="contentWrapper">
						<div class="options">${optionsHtml}</div>
						${renderMediaPanel(questionData)}
						</div>
						<button tabindex="0"  class="btn btn1 ColorSet_CR FSize16" id="submitBtn">${parent.quizButton}</button>
						<div class="feedback" id="feedback"></div>
					</div>
					
				`;
			} else if (questionData.images != null && questionData.video == null) {
				questionHtml = `
			
					<div class="questionContainer" >
						
						<div tabindex="0" class="question FSize16">${questionData.question}<div class="redtext instext FSize16">${parent.QuestionMcQtext}</div></div>
						
						 <div class="contentWrapper">
							<div class="options1">
							  ${optionsHtml}
							  <button tabindex="0"  class="btn ColorSet_CR FSize16" id="submitBtn">${parent.quizButton}</button>
							</div>
							${renderMediaPanel(questionData)}
						  </div>
					 
					
						<div class="feedback" id="feedback"></div>
					</div>
					
					 ${renderImageModal()}
				`;
			} else if (questionData.images == null && questionData.video != null) {
				questionHtml = `
		
					<div class="questionContainer" >
						
						<div tabindex="0" class="question FSize16">${questionData.question}<div class="redtext instext FSize16">${parent.QuestionMcQtext}</div></div>
						<div class="contentWrapper">
						<div class="options1">${optionsHtml} <button tabindex="0"  class="btn ColorSet_CR FSize16" id="submitBtn">${parent.quizButton}</button></div>
						${renderMediaPanel(questionData)}
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
		applyMobileScrollFix();
		ensureMenuCopyrightVisible();
		ensureSubmitSection();
		resetQuizScroll();
		timerElement = document.getElementById("timer");
		document.getElementById('submitBtn').addEventListener('click', checkAnswer);
		// Add click event listener to checkboxes
		document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
			checkbox.addEventListener('click', function() {
				// Manually toggle checkbox state
				checkbox.checked = !checkbox.checked;
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

  // Classify by intrinsic aspect ratio (naturalWidth/naturalHeight) so
  // Quiz_style.css can size the question image by its own shape --
  // landscape fits by width, portrait fits by height, square bounds both
  // axes -- instead of a fixed on-screen size. ratio is only meaningful
  // once the image has decoded, so branch on .complete (cached images may
  // already be loaded before this script runs and never fire another
  // 'load' event).
  var classifyImageOrientation = function () {
    var w = zoomableImage.naturalWidth;
    var h = zoomableImage.naturalHeight;
    if (!w || !h) {
      return;
    }
    var ratio = w / h;
    zoomableImage.classList.remove('landscape', 'portrait', 'square');
    if (ratio > 1) {
      zoomableImage.classList.add('landscape');
    } else if (ratio < 1) {
      zoomableImage.classList.add('portrait');
    } else {
      zoomableImage.classList.add('square');
    }
  };
  if (zoomableImage.complete) {
    classifyImageOrientation();
  } else {
    zoomableImage.addEventListener('load', classifyImageOrientation);
  }
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
		const alertText = (parent.quizData && parent.quizData.AlertText && parent.quizData.AlertText.trim()) || "Please select an answer.";
		alert(alertText);
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
								  class="retrybtn FSize20" 
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
								${!passed ? `<button tabindex="0" class="retrybtn  FSize20" id="retryBtn">${parent.retryButton}</button>` : ''}
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
								${!passed ? `<button tabindex="0" class="retrybtn  FSize20" id="retryBtn">${parent.retryButton}</button>` : ''}
							</div>
						`;
						
						}
					}
				}
				quizContainer.innerHTML = resultsHtml;
				applyMobileScrollFix();
				ensureMenuCopyrightVisible();
				resetQuizScroll();

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
		applyMobileScrollFix();
		ensureMenuCopyrightVisible();
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
		applyMobileScrollFix();
		ensureMenuCopyrightVisible();
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
	applyMobileScrollFix();
	ensureMenuCopyrightVisible();
	window.addEventListener('resize', applyMobileScrollFix);
	window.addEventListener('resize', ensureMenuCopyrightVisible);
	window.addEventListener('orientationchange', applyMobileScrollFix);
	window.addEventListener('orientationchange', ensureMenuCopyrightVisible);
	window.addEventListener('beforeunload', restoreFooterCopyright);
	window.addEventListener('pagehide', restoreFooterCopyright);
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