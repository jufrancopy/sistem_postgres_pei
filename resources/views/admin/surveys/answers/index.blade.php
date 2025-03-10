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

                    <canvas id="scoreChart" width="900" height="500" style="display: none;"></canvas>


                    <div class="card-body">
                        <div class="quiz text-center">
                            <h4 id="question" class="mb-4"></h4>
                            <div id="answer-buttons" class="d-grid gap-2 mb-4"></div>
                            <button id="next-btn" class="btn btn-success btn-lg px-5" style="display: none">
                                Siguiente
                            </button>
                        </div>
                        <!-- Canvas para el gráfico -->
                        <canvas id="scoreChart" width="900" height="500" style="display: none;"></canvas>
                        <!-- Contenedor para los puntajes -->
                        <div id="scores-container"></div>
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

            // Verificar si el usuario ya completó la encuesta
            checkIfUserHasResponded();
        }

        // Verifica si el usuario ya ha respondido a la encuesta
        function checkIfUserHasResponded() {
            fetch(`/surveys/${surveyId}/has-responded`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.hasResponded) {
                        // Si el usuario ya completó la encuesta, ocultar las preguntas y mostrar los resultados
                        hideQuizElements();
                        showScores();
                    } else {
                        // Si no ha completado la encuesta, cargar las preguntas
                        fetchQuestions();
                    }
                })
                .catch(error => console.error('Error al verificar respuesta:', error));
        }

        function hideQuizElements() {
            document.getElementById("question").style.display = "none";
            document.getElementById("answer-buttons").style.display = "none";
            document.getElementById("next-btn").style.display = "none";
        }

        function showEndOfSurveyNotification() {
            Swal.fire({
                title: 'Encuesta Completada',
                text: '¡Gracias por participar! A continuación se mostrarán los puntajes.',
                icon: 'success',
                confirmButtonText: 'Ver Puntajes',
            }).then(() => {
                // Ocultar elementos de preguntas y respuestas
                hideQuizElements();

                // Guardar el puntaje acumulado
                saveScore('{{ auth()->user()->id }}', surveyId, score).then(() => {
                    // Mostrar los resultados después de guardar el puntaje
                    showScores();
                });
            });
        }

        function saveScore(participantId, surveyId, score) {
            const scoreData = {
                participant_id: participantId,
                survey_id: surveyId,
                score: score
            };

            console.log("Enviando datos al servidor:", scoreData); // Depuración

            return fetch('/save-score', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(scoreData)
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Respuesta del servidor:', data); // Depuración
                    return data; // Devolver los datos para que se puedan usar en la siguiente promesa
                })
                .catch(error => console.error('Error al guardar el puntaje:', error));
        }

        function showScores() {
            // Ocultar elementos de preguntas y respuestas
            hideQuizElements();

            // Mostrar el contenedor de resultados
            const scoresContainer = document.getElementById("scores-container");
            if (scoresContainer) {
                scoresContainer.style.display = "block"; // Asegurarse de que esté visible
            }

            // Obtener los puntajes desde el servidor
            fetch(`/surveys/${surveyId}/scores`)
                .then(response => response.json())
                .then(data => {
                    console.log("Datos recibidos:", data); // Depuración

                    const currentUserId = '{{ auth()->user()->id }}';
                    let scoreTable =
                        `<h3>Puntajes de los Participantes</h3>
                <table class="table table-striped table-bordered mt-3">
                    <thead class="thead-light">
                        <tr>
                            <th>Puesto</th>
                            <th>Nombre</th>
                            <th>Total de Puntos</th>
                        </tr>
                    </thead>
                    <tbody>`;

                    data.forEach((participantScore, index) => {
                        let medalIcon = index === 0 ? '🏅' : index === 1 ? '🥈' : index === 2 ? '🥉' : '';
                        const isCurrentUser = participantScore.participant_id === currentUserId;
                        scoreTable += `<tr class="${isCurrentUser ? 'table-success user-highlight' : ''}">
                    <td>${medalIcon}${index + 1}</td>
                    <td>${participantScore.name}</td>
                    <td>${participantScore.score}</td>
                </tr>`;
                    });

                    scoreTable += `</tbody></table>`;

                    // Mostrar la tabla de puntajes
                    if (scoresContainer) {
                        scoresContainer.innerHTML = scoreTable;
                    }

                    // Mostrar el gráfico
                    showChart(data.map(participant => participant.score));
                })
                .catch(error => console.error('Error al obtener los puntajes:', error));
        }

        function showChart(data) {
            // Mostrar el canvas
            document.getElementById('scoreChart').style.display = 'block';

            // Renderizar el gráfico
            renderChart(data);
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
                    questions = shuffleArray(data); // Mezclar las preguntas
                    fetchQuestion();
                });
        }

        function shuffleArray(array) {
            for (let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]]; // Intercambiar elementos
            }
            return array;
        }

        // Cuando el usuario haya terminado la encuesta, mostrar la notificación y los puntajes
        function fetchQuestion() {
            if (currentQuestionIndex < questions.length) {
                resetState();
                showQuestion(questions[currentQuestionIndex]);
            } else {
                // Si el usuario ha respondido todas las preguntas, mostrar la notificación y los puntajes
                showEndOfSurveyNotification();
            }
        }

        // Muestra la pregunta y sus respuestas
        function showQuestion(question) {
            questionElement.innerHTML = (currentQuestionIndex + 1) + ". " + question.question;

            // Mezclar las respuestas antes de mostrarlas
            const shuffledAnswers = shuffleArray(question.answers);

            shuffledAnswers.forEach(answer => {
                const button = document.createElement("button");
                button.innerHTML = answer.answer;
                button.classList.add("btn");
                answerButtons.appendChild(button);
                button.dataset.correct = answer.is_correct ? "1" :
                    "0"; // Asegúrate de que esto esté correctamente configurado

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
            const correctButton = Array.from(buttons).find(button => button.dataset.correct === "1");

            // Deshabilitar todas las respuestas para evitar más clics
            buttons.forEach(button => {
                button.disabled = true;
            });

            // Si el usuario selecciona la respuesta correcta
            if (selectedButton.dataset.correct === "1") {
                selectedButton.classList.add("correct"); // Marcar en verde
                score++; // Incrementar el puntaje
                console.log("Respuesta correcta. Puntaje actual:", score); // Depuración
            } else {
                // Si el usuario selecciona una respuesta incorrecta
                selectedButton.classList.add("incorrect"); // Marcar la incorrecta en rojo
                correctButton.classList.add("correct"); // Marcar la respuesta correcta en verde
                console.log("Respuesta incorrecta. Puntaje actual:", score); // Depuración
            }

            nextButton.style.display = "block";
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
