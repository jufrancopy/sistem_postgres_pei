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
                        <h3>Encuesta {{ $survey->name }}</h3>
                    </div>

                    <canvas id="scoreChart" width="900" height="500"></canvas>

                    <div class="card-body">
                        <div class="quiz text-center">
                            <h4 id="question" class="mb-4">

                            </h4>
                            <div id="answer-buttons" class="d-grid gap-2 mb-4">

                            </div>
                            <button id="next-btn" class="btn btn-success btn-lg px-5" style="display: none">
                                Siguiente
                            </button>
                        </div>
                        <!-- Agrega estos botones en tu HTML -->
                        <div id="social-share" style="display:none;">
                            <a id="share-whatsapp" href="#" target="_blank"><i class="fa fa-whatsapp"
                                    aria-hidden="true"></i></a>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Elementos del DOM y variables
        const questionElement = document.getElementById("question");
        const answerButtons = document.getElementById("answer-buttons");
        const nextButton = document.getElementById("next-btn");
        const ctx = document.getElementById('scoreChart').getContext('2d');

        let currentQuestionIndex = 0;
        let score = 0;

        // ID de la encuesta y almacenamiento de preguntas
        let surveyId = '{{ $survey->id }}';
        let questions = [];
        let answered = false;

        // Función para iniciar la encuesta
        function startQuiz() {
            currentQuestionIndex = 0;
            score = 0;
            nextButton.innerHTML = "Siguiente";
            checkIfUserHasResponded();
        }

        // Verifica si el usuario ya ha respondido a la encuesta
        function checkIfUserHasResponded() {
            fetch(`/surveys/${surveyId}/has-responded`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Asegúrate de incluir CSRF si es necesario
                    }
                })
                .then(response => response.json())
                .then(data => {
                    data.hasResponded
                    if (data.hasResponded) {
                        Swal.fire({
                            title: 'Encuesta completada',
                            text: "Veamos los puntajes!",
                            icon: 'info',
                            buttons: true,
                            dangerMode: false,
                        }).then((willScores) => {
                            if (willScores) {
                                showScores(); // Llama a la función para mostrar los puntajes
                            }
                        })
                    } else {
                        // Si no ha respondido, continúa con la carga de preguntas
                        fetchQuestions();
                    }
                })
                .catch(error => {
                    console.error('Error al verificar respuesta:', error);
                });
        }

        //Funcion para Graficos
        function renderChart(data) {
            console.log(data)
            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map((_, index) => `Participante ${index + 1}`),
                    datasets: [{
                        label: 'Puntajes',
                        data: data,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Muestra el puntaje final y una tabla con puntajes de todos los participantes
        function showScores() {
            resetState();
            fetch(`/surveys/${surveyId}/scores`)
                .then(response => response.json())
                .then(data => {
                    const currentUserId = '{{ auth()->user()->id }}';

                    let scoreTable = `<h3>Puntajes de los Participantes</h3>`;
                    scoreTable += `
                        <table class="table table-striped table-bordered mt-3">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Puesto</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Total de Puntos</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                    scoreData = data.map((participantScore, index) => {
                        let medalIcon = index === 0 ? '🏅' : index === 1 ? '🥈' : index === 2 ? '🥉' : '';
                        const isCurrentUser = participantScore.id === currentUserId;
                        scoreTable += `
                            <tr class="${isCurrentUser ? 'table-success user-highlight' : ''}">
                                <td>${medalIcon}${index + 1}</td>
                                <td>${participantScore.name}</td>
                                <td>${participantScore.score}</td>
                            </tr>
                        `;
                        return participantScore.score; // Guarda los puntajes para el gráfico
                    });

                    scoreTable += `</tbody></table>`;
                    questionElement.innerHTML += scoreTable;

                    renderChart(scoreData);
                })
                .catch(error => console.error('Error al obtener los puntajes:', error));
        }

        function renderChart(data) {
            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map((_, index) => `Participante ${index + 1}`),
                    datasets: [{
                        label: 'Puntajes',
                        data: data,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Obtiene las preguntas desde el servidor
        function fetchQuestions() {
            fetch(`/surveys/${surveyId}/questions`)
                .then(response => response.json())
                .then(data => {
                    questions = data;
                    fetchQuestion();
                });
        }

        // Muestra la pregunta actual
        function fetchQuestion() {
            if (currentQuestionIndex < questions.length) {
                resetState();
                showQuestion(questions[currentQuestionIndex]);
            } else {
                showScores();
            }
        }

        // Muestra la pregunta y sus respuestas
        function showQuestion(question) {
            questionElement.innerHTML = (currentQuestionIndex + 1) + ". " + question.question;

            question.answers.forEach(answer => {
                const button = document.createElement("button");
                button.innerHTML = answer.answer;
                button.classList.add("btn");
                answerButtons.appendChild(button);
                button.dataset.correct = answer.is_correct;

                button.addEventListener("click", () => {
                    if (!answered) {
                        answered = true;
                        selectAnswer(button, question);
                    }
                });
            });
        }

        // Selecciona la respuesta y guarda si es correcta
        function selectAnswer(selectedButton, question) {
            const buttons = answerButtons.querySelectorAll("button");

            buttons.forEach(button => {
                const isCorrect = button.dataset.correct === "1";
                button.classList.add(isCorrect ? "correct" : "incorrect");
                button.disabled = true;
            });

            if (selectedButton.dataset.correct === "1") {
                score++;
            }

            nextButton.style.display = "block";

            const answerData = {
                participant_id: '{{ auth()->user()->id }}',
                survey_id: surveyId,
                question_id: question.id,
                answer: selectedButton.innerHTML,
                is_correct: selectedButton.dataset.correct === "1"
            };

            fetch('/save-answer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(answerData)
            });
        }

        // Reinicia el estado para la siguiente pregunta
        function resetState() {
            nextButton.style.display = "none";
            answered = false;
            answerButtons.innerHTML = '';
        }

        // Pasa a la siguiente pregunta
        function handleNextButton() {
            currentQuestionIndex++;
            fetchQuestion();
        }

        // Envía el puntaje al servidor
        function saveScore(participantId, surveyId, score) {
            const scoreData = {
                participant_id: participantId,
                survey_id: surveyId,
                score: score
            };

            fetch('/save-score', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(scoreData)
            });
        }

        // Evento de clic para el botón "Siguiente"
        nextButton.addEventListener("click", () => {
            if (currentQuestionIndex >= questions.length) {
                startQuiz();
            } else {
                handleNextButton();
            }
        });

        // Inicia la encuesta al cargar la página
        startQuiz();
    </script>
@stop
