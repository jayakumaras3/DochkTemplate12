document.addEventListener("DOMContentLoaded", () => {
  const questionEl = document.getElementById("question");
  const optionsEl = document.getElementById("options");
  const submitBtn = document.getElementById("submit-btn");
  const feedbackEl = document.getElementById("feedback");

  let attempts;

  // Fetch quiz data from the parent window
  const data = window.parent.mainData;
  const question = data.question;
  attempts = question.attempts;

  // Display the question
  questionEl.innerHTML = question.question;

  // Display the options
  question.options.forEach((option, index) => {
    const optionEl = document.createElement("div");
    optionEl.classList.add("quiz-option");
    optionEl.innerHTML = `
      <input type="checkbox" id="option${index}" name="option" value="${option.value}">
      <label for="option${index}">${option.text}</label>
    `;
    optionsEl.appendChild(optionEl);
  });

  // Handle submit button click
  submitBtn.addEventListener("click", () => {
    const selectedOptions = document.querySelectorAll('input[name="option"]:checked');

    if (selectedOptions.length === 0) {
      feedbackEl.textContent = data.AlertText;
      feedbackEl.classList.add("incorrect");
      return;
    }

    const correctAnswers = question.options.filter(o => o.correct).map(o => o.value);
    const selectedAnswers = Array.from(selectedOptions).map(o => o.value);

    const isCorrect = correctAnswers.length === selectedAnswers.length && correctAnswers.every(a => selectedAnswers.includes(a));

    if (isCorrect) {
      feedbackEl.textContent = question.feedback.correct;
      feedbackEl.classList.remove("incorrect");
      feedbackEl.classList.add("correct");
      submitBtn.disabled = true;
    } else {
      attempts--;
      if (attempts > 0) {
        feedbackEl.textContent = question.feedback.incorrect;
      } else {
        feedbackEl.textContent = question.feedback.noAttempts;
        submitBtn.disabled = true;
      }
      feedbackEl.classList.add("incorrect");
    }
  });
});
