@extends('layouts.master')
@section('title', 'Respuestas')

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title ">Preguntas y Respuestas</h4>
        </div>

        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('globales.dashboard') }}">Planificación-Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Módulo Encuestas y Preguntas</li>
            </ol>
        </nav>

        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header text-center bg-primary text-white">
                        <h3>Encuesta</h3>
                    </div>
                    <div class="card-body">
                        <div class="quiz text-center">
                            <h4 id="question" class="mb-4">
                                Aquí las preguntas
                            </h4>
                            <div id="answer-buttons" class="d-grid gap-2 mb-4">

                            </div>
                            <button id="next-btn" class="btn btn-success btn-lg px-5">
                                Siguiente
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

@section('css')
    <style>
        .app {
            background: #fff;
            width: 90%;
            max-width: 600px;
            margin: 100px auto 0;
            border-radius: 10px;
            padding: 30px;
        }

        .app h1 {
            font-weight: 25px;
            color: #001e4d;
            font-weight: 600;
            border-bottom: 1px solid #333;
            padding-bottom: 30px;
        }

        .quiz {
            padding: 20px 0;
        }

        .quiz h2 {
            font-size: 18px;
            color: #001e4d;
            ;
            max-width: 600px;
        }

        .btn {
            background: #fff;
            color: #222;
            font-weight: 500;
            width: 100%;
            border: 1px solid #222;
            padding: 10px;
            margin: 10px 0;
            text-align: left;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn:hover:not([disabled]) {
            background: #222;
            color: #fff;
        }

        .btn:disabled {
            cursor: no-drop;
        }

        #next-btn {
            background: #001e4d;
            color: #fff;
            font-weight: 500;
            width: 200px;
            border: 0;
            padding: 10px;
            margin: 20px auto 0;
            border-radius: 4px;
            cursor: pointer;
            display: block;
        }

        .correct {
            background: #9aeabc;
        }

        .incorrect {
            background: #ff9393;
        }
    </style>
@stop

@section('scripts')

    <script>
        const questionElement = document.getElementById("question");
        const answerButtons = document.getElementById("answer-buttons");
        const nextButton = document.getElementById("next-btn");

        let currentQuestionIndex = 0;
        let score = 0;

        // Obtiene el ID de la encuesta desde tu variable en Blade
        let surveyId = '{{ $survey->id }}';
        let questions = []; // Almacena las preguntas y respuestas

        function startQuiz() {
            currentQuestionIndex = 0;
            score = 0;
            nextButton.innerHTML = "Siguiente";
            fetchQuestions();
        }

        function fetchQuestions() {
            // Realiza una petición AJAX al servidor para obtener todas las preguntas y respuestas
            fetch(`/surveys/${surveyId}/questions`)
                .then(response => response.json())
                .then(data => {
                    questions = data; // Almacena las preguntas recibidas
                    fetchQuestion();
                });
        }

        function fetchQuestion() {
            // Verifica que el índice actual esté dentro del rango
            if (currentQuestionIndex < questions.length) {
                resetState();
                showQuestion(questions[currentQuestionIndex]);
            } else {
                showScore(); // Muestra el puntaje al final
            }
        }

        function showQuestion(question) {
            questionElement.innerHTML = (currentQuestionIndex + 1) + ". " + question.question;

            question.answers.forEach(answer => {
                const button = document.createElement("button");
                button.innerHTML = answer.answer; // Cambia 'text' a 'answer'
                button.classList.add("btn");
                answerButtons.appendChild(button);
                button.dataset.correct = answer.is_correct;

                button.addEventListener("click", () => {
                    submitAnswer(answer.answer, answer.is_correct, question
                    .id); // Envía la respuesta y el ID de la pregunta
                });
            });
        }

        function submitAnswer(answerText, questionId) {
            fetch(`/surveys/${surveyId}/check-answer`, {
                    method: 'POST', // Asegúrate de que sea POST
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Asegúrate de que esto esté en tu vista Blade
                    },
                    body: JSON.stringify({
                        answer: answerText, // La respuesta seleccionada
                        question_id: questionId // ID de la pregunta
                    })
                })
                .then(response => {
                    // Verifica si la respuesta es correcta
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    handleNextButton(data.isCorrect); // Maneja el siguiente paso
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
        }


        function resetState() {
            nextButton.style.display = "none";
            while (answerButtons.firstChild) {
                answerButtons.removeChild(answerButtons.firstChild);
            }
        }

        function handleNextButton(isCorrect) {
            if (isCorrect) {
                score++; // Incrementa el puntaje si la respuesta es correcta
            }
            currentQuestionIndex++;
            fetchQuestion(); // Llama a fetchQuestion para avanzar a la siguiente pregunta
        }

        function showScore() {
            resetState();
            questionElement.innerHTML = `Tu puntaje es ${score} de ${questions.length}!`;
            nextButton.innerHTML = "Jugar de nuevo";
            nextButton.style.display = "block";
        }

        nextButton.addEventListener("click", () => {
            if (currentQuestionIndex >= questions.length) {
                startQuiz(); // Reinicia la encuesta
            }
        });

        startQuiz();
    </script>



@stop
