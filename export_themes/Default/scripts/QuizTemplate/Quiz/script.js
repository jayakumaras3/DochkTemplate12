// Function to fetch JSON data
var answers;
var questions;
var quizData

async function fetchData() {
  try {
    const response = await fetch('questions.json');
   // console.log("Raw Response:", response);

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

     quizData = await response.json();
    //console.log("Quiz Data:", quizData);

    // Extract questions and answers from the JSON
   const parsedQuestions = quizData.questions.map((q) => ({
  question: q.question,
  images: q.images,
  video: q.video,
  type: q.type,
  options: q.options.split('|').map((text, index) => ({
    text,
    correct: q.correct.split('|')[index] === '1', // Convert "1" to `true` and "0" to `false`
  
      })),
    }));

    // Simulate the structure of `questions` and `answers`
    questions = {
      QuestionRandom: quizData.QuestionRandom,
      OptionRandom: quizData.OptionRandom,
      TotalQuestions: parseInt(quizData.TotalQuestions),
      questions: parsedQuestions,
    };

    answers = {
      Answers: parsedQuestions.map((q) => ({
        options: q.options.map((option) => ({ correct: option.correct })),
      })),
    };

    // Get random questions and answers
    const quizDataResult = getRandomQuestionsAndAnswers(answers, questions);
   // console.log("Quiz Data Result:", quizDataResult);
  } catch (error) {
    console.error("Error fetching data:", error);
  }
}

var FinalResutfailed;
var QuestionCountText;
var MinutesText;
var ImageZoomText;
var QuizMode;
var PostAttemptType;

// Function to select random questions and answers
function getRandomQuestionsAndAnswers(answers, questions) {
	const questionPool = [...questions.questions]; // Clone the array
	const answerPool = [...answers.Answers]; // Clone the array

	const randomQuestions = [];
	const randomAnswers = [];
	
	
	//Set the Json data into JS values
	questionRandom = quizData.QuestionRandom;
	optionRandom = quizData.OptionRandom;
	totalQuestions = quizData.TotalQuestions;
	QuestionOFText=quizData.QuestionOFText;
	QuestionCountText=quizData.QuestionCountText;
	FinalResutfailed= quizData.FinalResutfailed
	QuizAttempt=quizData.QuizAttempt;
	Resulttitle=quizData.Resulttitle;
	resutscorecontent=quizData.Resutscorecontent;
	Resutpassed=quizData.Resutpassed;
	Resutfailed=quizData.Resutfailed;
	Questiontext=quizData.Questiontext;
	QuestionMcQtext=quizData.QuestionMcQtext;
	ImageZoomText=quizData.ImageZoomText;
	MinutesText=quizData.MinutesText;;
	QuizMode=quizData.QuizMode;
	
	PostAttemptType=quizData.PostAttemptType;
	
	
	duration = parseInt(quizData.duration) * 60;
	duration1 = parseInt(quizData.duration);
	startpagedescrip= quizData.startpagedescrip;
	resultpagedescrip= quizData.resultpagedescrip;
	startpageheader=quizData.startpageheader;
	startpagedescrip1= quizData.startpagedescrip1;
	startButton= quizData.startButton;
	quizButton= quizData.quizButton;
	//viewResult= questions.viewResult;
	clicknote= quizData.clicknote;
	TotalQuestionste=quizData.TotalQuestionste;
	passingScorete=quizData.passingScorete;
	QuizAttemptte=quizData.QuizAttemptte;
	durationte=quizData.durationte;
	parent.setPassScore(quizData.passingScore);
	retryButton=quizData.retryButton
	TimeUpte=quizData.TimeUpte;
	

	parent.parent.QuizAttemptLimit=QuizAttempt;
	
	
	
	if (questionRandom == 1) {
		//console.log("questionRandom:" + totalQuestions);
			if (questionPool.length >= totalQuestions && answerPool.length >= totalQuestions) {
			while (randomQuestions.length < totalQuestions) {
				// Pick a random index from the question pool
				const randIndex = Math.floor(Math.random() * questionPool.length);

				// Add the selected question and corresponding answer to the arrays
				randomQuestions.push(questionPool[randIndex]);
				randomAnswers.push(answerPool[randIndex]);

				// Remove the selected question and its corresponding answer from the pools
				questionPool.splice(randIndex, 1);
				answerPool.splice(randIndex, 1);
			}
			}
			
	} else {
		// If not random, ensure to print the first 20 questions
		const limit = Math.min(totalQuestions, questionPool.length); // Adjust limit if less than 20 available
		for (let i = 0; i < limit; i++) {
			
			randomQuestions.push(questionPool[i]); // Add the sequential question
			randomAnswers.push(answerPool[i]); // Add the corresponding answer
		}
	}

	let shuffledQuestionData = [];
	let shuffledAnswerData = [];

	function shuffleOptions() {
		// Using the random questions and answers
		const answerData = randomAnswers;
		const questionData = randomQuestions;

		// Initialize arrays to store shuffled question and answer data


		// Iterate through each question-answer pair
		for (let idx = 0; idx < questionData.length; idx++) {
			const question = questionData[idx];
			const answer = answerData[idx];

			// Ensure that the options are arrays
			if (question.options && Array.isArray(question.options) && answer.options && Array.isArray(answer.options)) {

				// Shuffle the question options and answer options together
				for (let i = question.options.length - 1; i > 0; i--) {
					const j = Math.floor(Math.random() * (i + 1));

					// Swap the question option (text)
					[question.options[i].text, question.options[j].text] = [question.options[j].text, question.options[i].text];

					// Swap the answer option (correct)
					[answer.options[i].correct, answer.options[j].correct] = [answer.options[j].correct, answer.options[i].correct];
				}
			}

			// Push shuffled question and answer data to the arrays
			shuffledQuestionData.push(question);
			shuffledAnswerData.push(answer);
		}

		// Output the shuffled data (for debugging)
 //console.log("Shuffled Question Data:", JSON.stringify(shuffledQuestionData, null, 2));
 // console.log("Shuffled Answer Data:", JSON.stringify(shuffledAnswerData, null, 2));


		return {
			shuffledQuestionData,
			shuffledAnswerData

		};
	}
	// options random only works  questionRandom ==0(without question random)
	if(optionRandom==1 && questionRandom==0)
	{
		//console.log("OR 1, qr 0");
		// Call the shuffle function
		shuffleOptions();
		mainData = shuffledQuestionData;
		AnsData = shuffledAnswerData;
	
	}
	else if (questionRandom==0 && optionRandom==0) { // there is no random question or options
		
		mainData = randomQuestions;
		AnsData = randomAnswers;
		
	}
	else if (questionRandom==1 && optionRandom==0) { // there is a random question & options are not random 
	//	console.log("OR 0, qr 1");
		mainData = randomQuestions;
		AnsData = randomAnswers;
		// console.log("Shuffled Question Data:", JSON.stringify(randomQuestions, null, 2));
		//console.log("Shuffled Answer Data:", JSON.stringify(randomAnswers, null, 2));
	}
	else if (questionRandom==1 && optionRandom==1) { // there is a random question & options are random 
		//console.log("OR 1, qr 1");
		shuffleOptions();
		mainData = shuffledQuestionData;
		AnsData = shuffledAnswerData;
		//console.log("Shuffled Question Data:", JSON.stringify(randomQuestions, null, 2));
	//	console.log("Shuffled Answer Data:", JSON.stringify(randomAnswers, null, 2));
	}
	

	PassingScore = quizData.passingScore;
//console.log(quizData.iframeSrc);
	if (quizData.iframeSrc) {
		// Create iframe element
		const iframe = document.createElement('iframe');
		iframe.id = 'contentFrame';
		iframe.src = quizData.iframeSrc;
		iframe.style.width = '100%';
		iframe.style.height = '100%';
		
		iframe.style.border = 'none';
		iframe.setAttribute('allowfullscreen', 'true');
		iframe.setAttribute('allow', 'fullscreen'); // For better compatibility

		// Append iframe to the container
		const container = document.getElementById('contentContainer');
		if (container) {
			container.appendChild(iframe);
		} else {
			console.warn('No container found for iframe');
		}
	} else {
		console.warn('No iframeSrc found in questions.json');
	}


	return {
		questions: randomQuestions,
		answers: randomAnswers
	};
}

// Function to display quiz in HTML


// Fetch data and display the quiz
fetchData();