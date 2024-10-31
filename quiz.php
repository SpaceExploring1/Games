<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Advanced Quiz</title>
</head>
<body>
    <div class="app">
        <h1>Advanced Quiz</h1>
        <p>Try completing it before the cycle ends :)</p>
        <div class="quiz">
            <h2 id="question">Question:</h2>
            <div id="answer-button">
                <!-- Buttons will be dynamically created here -->
            </div>
            <button id="Next-btn" style="display: none;">Next Question</button>
        </div>
    </div>
    <script>
        const questions = [
            { question: "What is the largest fish to have existed?", answers: [{ text: "Megalodon", correct: false }, { text: "Whale shark", correct: false }, { text: "Leedsichthys", correct: true }] },
            { question: "What's the fastest grossing movie?", answers: [{ text: "Star Wars V", correct: false }, { text: "Avatar 2", correct: true }, { text: "Avatar", correct: false }] },
            { question: "What's the biggest building ever made?", answers: [{ text: "Burj Khalifa", correct: false }, { text: "The Bride", correct: true }, { text: "Eiffel Tower", correct: false }] },
            { question: "What's the best selling food item?", answers: [{ text: "Hamburgers", correct: false }, { text: "Steak", correct: false }, { text: "Noodles", correct: true }] },
            { question: "Fastest typing speed record?", answers: [{ text: "216 wpm", correct: true }, { text: "150 wpm", correct: false }, { text: "224 wpm", correct: false }] },
            { question: "What animal has the strongest bite?", answers: [{ text: "Great White Shark", correct: false }, { text: "Polar Bear", correct: false }, { text: "Orca", correct: true }] },
            { question: "How tall was the tallest man?", answers: [{ text: "272 cm", correct: true }, { text: "250 cm", correct: false }, { text: "244 cm", correct: false }] }
        ];

        const questionElement = document.getElementById("question");
        const answerButton = document.getElementById("answer-button");
        const nextButton = document.getElementById("Next-btn");
        let currentQuestionIndex = 0;
        let score = 0;

        nextButton.addEventListener("click", () => {
            currentQuestionIndex++;
            if (currentQuestionIndex < questions.length) {
                showQuestion();
            } else {
                endQuiz();
            }
        });

        function startQuiz() {
            currentQuestionIndex = 0;
            score = 0;
            nextButton.innerHTML = "Next Question";
            showQuestion();
        }

        function showQuestion() {
            resetState();
            let currentQuestion = questions[currentQuestionIndex];
            questionElement.innerHTML = (currentQuestionIndex + 1) + ". " + currentQuestion.question;

            currentQuestion.answers.forEach(answer => {
                const button = document.createElement("button");
                button.innerHTML = answer.text;
                button.classList.add("btn");
                button.dataset.correct = answer.correct;
                button.addEventListener("click", selectAnswer);
                answerButton.appendChild(button);
            });
        }

        function selectAnswer(e) {
            const selectedBtn = e.target;
            const isCorrect = selectedBtn.dataset.correct === "true";
            if (isCorrect) {
                selectedBtn.classList.add("correct");
                score++;
            } else {
                selectedBtn.classList.add("incorrect");
            }
            nextButton.style.display = "block";
        }

        function resetState() {
            nextButton.style.display = "none";
            while (answerButton.firstChild) {
                answerButton.removeChild(answerButton.firstChild);
            }
        }

        function endQuiz() {
            questionElement.innerHTML = `Quiz complete! Your score: ${score} out of ${questions.length}`;
            answerButton.innerHTML = ''; 
            nextButton.style.display = "none";

            const form = document.createElement("form");
            form.action = "scorelist.php"; // Redirect to scorelist.php
            form.method = "GET";

            form.appendChild(scoreInput);

            document.body.appendChild(form);

            form.submit(); // Automatically submits the form
        }

        startQuiz();
    </script>
</body>
</html>
