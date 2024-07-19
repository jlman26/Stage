
 function submitQuestions() {
    var numQuestions = parseInt(document.getElementById('questions').value);
    var title = document.getElementById('title').value;
    var description = document.getElementById('description').value;

    if (title === '' || description === '') {
        alert('Please enter a title and description.');
        return;
    }

    if (isNaN(numQuestions) || numQuestions < 1 || numQuestions > 20) {
        alert('Please enter a number between 1 and 20.');
        return;
    }

    var questionContainer = document.getElementById('questionContainer');
    questionContainer.innerHTML = ''; // Clear previous content

    for (var i = 1; i <= numQuestions; i++) {
        createQuestionForm(i);
    }

    var submitButton = document.createElement('button');
    submitButton.textContent = 'Submit';
    submitButton.className = 'btn btn-primary mt-2';
    submitButton.type = 'button';
    submitButton.onclick = function() {
        submitForm(title, description);
    };
    questionContainer.appendChild(submitButton);
}

function submitForm(title, description) {
    var allQuestionsFilled = true;
    var allQuestionsHaveAnswers = true;
    var allQuestionsHaveCorrectAnswers = true;
    var formData = new FormData();

    var questionsArray = [];

    var questionDivs = document.querySelectorAll('.question');
    questionDivs.forEach(function(questionDiv) {
        var questionInput = questionDiv.querySelector('input[type="text"]');
        var questionId = questionInput.name.replace('question', '');
        var questionText = questionInput.value.trim();
        if (questionText === '') {
            allQuestionsFilled = false;
        }

        var answerInputs = questionDiv.querySelectorAll('.answer-container input[type="text"]');
        var questionAnswers = [];
        var correctAnswer = '';

        answerInputs.forEach(function(answerInput) {
            var answerText = answerInput.value.trim();
            if (answerText !== '') {
                questionAnswers.push(answerText);
            }
        });

        if (questionAnswers.length < 1) {
            allQuestionsHaveAnswers = false;
        }

        var correctAnswerInput = questionDiv.querySelector('.correct-answer');
        if (correctAnswerInput) {
            correctAnswer = correctAnswerInput.value.trim();
            if (correctAnswer === '') {
                allQuestionsHaveCorrectAnswers = false;
            }
        }

        questionsArray.push({
            questionId: questionId,
            questionText: questionText,
            answers: questionAnswers,
            correctAnswer: correctAnswer
        });
    });

    if (!allQuestionsFilled) {
        alert('Please fill in all question fields before submitting.');
        return;
    }

    if (!allQuestionsHaveAnswers) {
        alert('Please provide at least one answer for each question before submitting.');
        return;
    }

    if (!allQuestionsHaveCorrectAnswers) {
        alert('Please specify a correct answer for each question before submitting.');
        return;
    }

    formData.append('title', title);
    formData.append('description', description);
    formData.append('questions', JSON.stringify(questionsArray));

    fetch('./quiz/submit.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) // Parse response as JSON
    .then(data => {
        if (data.error) {
            alert('Error creating quiz: ' + data.error);
        } else {
            alert('Quiz created successfully with ID: ' + data.quizId);
            // Redirect or perform any other action upon successful creation
        }
    })
    .catch(error => {
        console.error('Error creating quiz:', error);
        alert('Failed to create quiz. Please try again.');
    });
}

function createQuestionForm(questionId) {
    var questionDiv = document.createElement('div');
    questionDiv.className = 'question mb-4';
    questionDiv.innerHTML = `<h5>Question ${questionId}</h5>`;

    var questionText = document.createElement('input');
    questionText.type = 'text';
    questionText.className = 'form-control mb-2';
    questionText.placeholder = 'Enter Question Text';
    questionText.name = `question${questionId}`;
    questionDiv.appendChild(questionText);

    var answerContainer = document.createElement('div');
    answerContainer.className = 'answer-container';
    questionDiv.appendChild(answerContainer);

    var addAnswerButton = document.createElement('button');
    addAnswerButton.textContent = 'Add Answer';
    addAnswerButton.className = 'btn btn-sm btn-success mb-2';
    addAnswerButton.type = 'button';
    addAnswerButton.onclick = function() {
        var answerInput = document.createElement('input');
        answerInput.type = 'text';
        answerInput.className = 'form-control mb-2 answer';
        answerInput.placeholder = 'Enter Answer';
        answerContainer.appendChild(answerInput);
    };
    questionDiv.appendChild(addAnswerButton);

    var addCorrectAnswerButton = document.createElement('button');
    addCorrectAnswerButton.textContent = 'Add Correct Answer';
    addCorrectAnswerButton.className = 'btn btn-sm btn-warning mb-2';
    addCorrectAnswerButton.type = 'button';
    addCorrectAnswerButton.onclick = function() {
        var correctAnswerInput = document.createElement('input');
        correctAnswerInput.type = 'text';
        correctAnswerInput.className = 'form-control mb-2 correct-answer';
        correctAnswerInput.placeholder = 'Enter Correct Answer';
        answerContainer.appendChild(correctAnswerInput);
        addCorrectAnswerButton.disabled = true;
    };
    questionDiv.appendChild(addCorrectAnswerButton);

    var questionContainer = document.getElementById('questionContainer');
    questionContainer.appendChild(questionDiv);
}

