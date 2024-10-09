const questions = [
    {
        question: "Cuales son las notas de la escala de Do",
        answers: [
            { text: "Do Re Mi Fa Sol La Si", correct: true },
            { text: "Do Re Mi Fa Sol La", correct: false },
            { text: "Do Re Mi Fa Sol", correct: false },
            { text: "Do Re Mi Fa", correct: false }
        ]
    },
    {
        question: "La escala Menor Antigua de Do está compuesta por",
        answers: [
            { text: "Do Re Mi Fa Sol La Si", correct: false },
            { text: "Do Re Mi Fa Sol# La", correct: false },
            { text: "Do Re Mi Fa Sol", correct: false },
            { text: "Do Re Mi Fa", correct: true }
        ]
    },
    {
        question: "La escala Mayor de Sol está compuesta por",
        answers: [
            { text: "Sol La Si Do Re Mi Fa", correct: false },
            { text: "Sol La Si Do Re Mi Fa#", correct: true },
            { text: "Sol La Si Do Re Mi Sol", correct: false },
            { text: "Sol La Si Do Re Fa", correct: false }
        ]
    },
    {
        question: "La escala Menor Natural de La está compuesta por",
        answers: [
            { text: "La Si Do# Re Mi Fa# Sol#", correct: false },
            { text: "La Si Do Re Mi Fa Sol", correct: true },
            { text: "La Si Do Re Mi Fa# Sol#", correct: false },
            { text: "La Si Do# Re Mi Fa Sol", correct: false }
        ]
    },
    {
        question: "La escala Menor Melódica de Re está compuesta por",
        answers: [
            { text: "Re Mi Fa Sol La Si Do", correct: false },
            { text: "Re Mi Fa# Sol La Si Do#", correct: true },
            { text: "Re Mi Fa# Sol La Si Do", correct: false },
            { text: "Re Mi Fa# Sol# La Si Do#", correct: false }
        ]
    }
];

const questionElement = document.getElementById("question");
const answerButtons = document.getElementById("answer-buttons");
const nextButton = document.getElementById("next-btn");

let currentQuestionIndex = 0;
let score = 0;

function startQuiz() {
    currentQuestionIndex = 0;
    score = 0;
    nextButton.innerHTML = "Siguiente";
    showQuestion();
}

function showQuestion() {
    resetState();
    let currentQuestion = questions[currentQuestionIndex];
    let questionNo = currentQuestionIndex + 1;
    questionElement.innerHTML = questionNo + ". " + currentQuestion.question;

    currentQuestion.answers.forEach(answer => {
        const button = document.createElement("button");
        button.innerHTML = answer.text;
        button.classList.add("btn");
        answerButtons.appendChild(button);
        if (answer.correct) {
            button.dataset.correct = answer.correct;
        }
        button.addEventListener("click", selectAnswer);
    });
}

function resetState() {
    nextButton.style.display = "none";
    while (answerButtons.firstChild) {
        answerButtons.removeChild(answerButtons.firstChild);
    }
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
    Array.from(answerButtons.children).forEach(button => {
        if (button.dataset.correct === "true") {
            button.classList.add("correct");
        }
        button.disabled = true;
    });
    nextButton.style.display = "block";
}

function showScore() {
    resetState();
    questionElement.innerHTML = `Tu puntaje es ${score} de ${questions.length}!`;
    nextButton.innerHTML = "Jugar de nuevo";
    nextButton.style.display = "block";
}

function handleNextButton() {
    currentQuestionIndex++;
    if (currentQuestionIndex < questions.length) {
        showQuestion();
    } else {
        showScore();
    }
}

nextButton.addEventListener("click", () => {
    if (currentQuestionIndex < questions.length) {
        handleNextButton();
    } else {
        startQuiz();
    }
})

startQuiz();