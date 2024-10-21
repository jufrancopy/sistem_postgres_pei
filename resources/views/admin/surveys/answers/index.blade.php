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

    <script>
        // Obtiene los elementos del DOM necesarios para mostrar las preguntas, respuestas y el botón "Siguiente"
        const questionElement = document.getElementById("question");
        const answerButtons = document.getElementById("answer-buttons");
        const nextButton = document.getElementById("next-btn");

        // Variables para manejar el estado actual de la encuesta
        let currentQuestionIndex = 0; // Índice de la pregunta actual
        let score = 0; // Puntaje acumulado

        // Obtiene el ID de la encuesta desde Blade
        let surveyId = '{{ $survey->id }}';
        let questions = []; // Almacena todas las preguntas y respuestas de la encuesta
        let answered = false; // Control para evitar seleccionar múltiples respuestas en una misma pregunta

        // Función para iniciar el cuestionario
        function startQuiz() {
            currentQuestionIndex = 0; // Reinicia el índice de las preguntas
            score = 0; // Reinicia el puntaje
            nextButton.innerHTML = "Siguiente"; // Restablece el texto del botón "Siguiente"
            fetchQuestions(); // Llama a la función para obtener las preguntas
        }

        // Función que obtiene las preguntas desde el servidor
        function fetchQuestions() {
            // Realiza una solicitud para obtener las preguntas de la encuesta
            fetch(`/surveys/${surveyId}/questions`)
                .then(response => response.json()) // Convierte la respuesta a formato JSON
                .then(data => {
                    questions = data; // Almacena las preguntas en la variable
                    fetchQuestion(); // Carga la primera pregunta
                });
        }

        // Función que carga la pregunta actual basada en el índice
        function fetchQuestion() {
            // Verifica si hay más preguntas disponibles
            if (currentQuestionIndex < questions.length) {
                resetState(); // Limpia el estado anterior
                showQuestion(questions[currentQuestionIndex]); // Muestra la pregunta actual
            } else {
                showScore(); // Si no hay más preguntas, muestra el puntaje final
            }
        }

        // Función que muestra la pregunta y sus respuestas
        function showQuestion(question) {
            questionElement.innerHTML = (currentQuestionIndex + 1) + ". " + question
                .question; // Muestra el texto de la pregunta

            // Itera sobre las posibles respuestas de la pregunta
            question.answers.forEach(answer => {
                const button = document.createElement("button"); // Crea un botón para cada respuesta
                button.innerHTML = answer.answer; // Establece el texto de la respuesta en el botón
                button.classList.add("btn"); // Añade la clase 'btn' al botón
                answerButtons.appendChild(button); // Añade el botón al contenedor de respuestas
                button.dataset.correct = answer.is_correct; // Almacena si la respuesta es correcta o no

                // Agrega el evento de clic al botón de respuesta
                button.addEventListener("click", () => {
                    if (!answered) { // Solo permite una respuesta por pregunta
                        answered = true; // Marca la pregunta como respondida
                        selectAnswer(button,
                            question); // Llama a la función para manejar la selección de la respuesta
                    }
                });
            });
        }

        function selectAnswer(selectedButton, question) {
            const correctAnswer = question.answers.find(answer => answer.is_correct);
            const buttons = answerButtons.querySelectorAll("button");

            buttons.forEach(button => {
                const isCorrect = button.dataset.correct === "1";
                if (isCorrect) {
                    button.classList.add("correct");
                } else if (button === selectedButton && !isCorrect) {
                    button.classList.add("incorrect");
                }
                button.disabled = true;
            });

            const isCorrect = selectedButton.dataset.correct === "1";
            if (isCorrect) {
                score++;
            }

            nextButton.style.display = "block";

            // Enviar la respuesta al backend para guardarla en la base de datos
            const answerData = {
                participant_id: '{{ auth()->user()->id }}', // Asegúrate de que el participante esté autenticado
                survey_id: surveyId,
                question_id: question.id,
                answer: selectedButton.innerHTML, // El texto de la respuesta seleccionada
                is_correct: isCorrect
            };

            fetch('/save-answer', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Incluir el token CSRF para seguridad
                    },
                    body: JSON.stringify(answerData)
                }).then(response => response.json())
                .then(data => {
                    console.log('Respuesta guardada:', data);
                }).catch(error => {
                    console.error('Error al guardar la respuesta:', error);
                });
        }


        // Función que reinicia el estado para la siguiente pregunta
        function resetState() {
            nextButton.style.display = "none"; // Oculta el botón "Siguiente"
            answered = false; // Marca la pregunta como no respondida para la siguiente ronda
            // Elimina todos los botones de respuesta de la pregunta anterior
            while (answerButtons.firstChild) {
                answerButtons.removeChild(answerButtons.firstChild);
            }
        }

        // Función que maneja el clic en el botón "Siguiente"
        function handleNextButton() {
            currentQuestionIndex++; // Incrementa el índice de la pregunta actual
            fetchQuestion(); // Carga la siguiente pregunta
        }

        // Función que muestra el puntaje final cuando se terminan todas las preguntas
        function showScore() {
            resetState();
            questionElement.innerHTML = `Tu puntaje es ${score} de ${questions.length}!`;

            // Crea URLs para compartir en redes sociales

            const whatsappShareUrl =
                `https://api.whatsapp.com/send?text=${encodeURIComponent(`¡Obtuve un puntaje de ${score} en el quiz! ${window.location.href}`)}`;


            // Asigna las URLs a los botones de compartir
            document.getElementById('share-whatsapp').href = whatsappShareUrl;

            // Muestra los botones de compartir
            document.getElementById('social-share').style.display = 'block';
        }


        // Agrega el evento de clic al botón "Siguiente"
        nextButton.addEventListener("click", () => {
            // Si ya se respondieron todas las preguntas, reinicia la encuesta
            if (currentQuestionIndex >= questions.length) {
                startQuiz(); // Reinicia la encuesta
            } else {
                handleNextButton(); // Pasa a la siguiente pregunta
            }
        });

        // Inicia el cuestionario cuando se carga la página
        startQuiz();
    </script>

@stop
