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
            hideQuizElements();

            const scoresContainer = document.getElementById("scores-container");
            if (scoresContainer) {
                scoresContainer.style.display = "block";
            }

            fetch(`/surveys/${surveyId}/scores`)
                .then(response => response.json())
                .then(data => {
                    console.log("🔍 Datos recibidos del servidor:", data);

                    // Como el backend no envía participant_id, asumimos que el primer/único registro 
                    // es el del usuario actual si solo hay un resultado
                    let userPuntaje = 0;

                    if (data.length === 1) {
                        // Si solo hay un registro, asumimos que es el del usuario actual
                        userPuntaje = data[0].score;
                        console.log("✅ Solo hay un registro, asumimos que es el usuario actual con puntaje:",
                            userPuntaje);
                    } else {
                        // Si hay múltiples registros, necesitamos otra forma de identificar al usuario
                        console.warn("⚠️ Hay múltiples registros pero no podemos identificar al usuario actual");
                        userPuntaje = 0;
                    }

                    // Actualizar la variable global con el puntaje detectado
                    userScore = userPuntaje;
                    console.log("🎯 Puntaje final asignado:", userScore);

                    // Obtener el puntaje máximo posible de la encuesta (puedes ajustar esto según tu lógica)
                    // Supongamos que el puntaje máximo está disponible en alguna variable o es un valor fijo
                    // Por ejemplo, si cada pregunta vale 1 punto y hay 10 preguntas:
                    const maxPuntaje = 10; // Ajustar este valor según corresponda

                    // Construir la tabla de puntajes
                    let scoreTable = `
                <h3>Puntajes de los Participantes</h3>
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
                        scoreTable += `<tr>
                    <td>${index + 1}</td>
                    <td>${participantScore.name}</td>
                    <td>${participantScore.score}</td>
                </tr>`;
                    });

                    scoreTable += `</tbody></table>`;

                    if (scoresContainer) {
                        scoresContainer.innerHTML = scoreTable + `
                    <div class="alert alert-info">Tu puntaje: ${userPuntaje} de ${maxPuntaje} puntos posibles</div>
                    <div class="btn-group mt-3">
                        <button id="share-whatsapp-text" class="btn btn-success">
                            <i class="fab fa-whatsapp"></i> Compartir Texto
                        </button>
                        <button id="share-whatsapp-image" class="btn btn-primary">
                            <i class="fab fa-whatsapp"></i> Compartir Imagen
                        </button>
                    </div>
                    <div id="canvas-container" class="mt-3" style="display:none;"></div>
                `;

                        // Agregar el canvas al DOM (oculto)
                        const canvasContainer = document.getElementById("canvas-container");
                        const canvas = document.createElement("canvas");
                        canvas.id = "score-canvas";
                        canvas.width = 600;
                        canvas.height = 400;
                        canvasContainer.appendChild(canvas);

                        // Eventos para los botones
                        document.getElementById("share-whatsapp-text").addEventListener("click", function() {
                            shareWithTextScore(userPuntaje, maxPuntaje);
                        });

                        document.getElementById("share-whatsapp-image").addEventListener("click", function() {
                            createAndShareScoreImage(userPuntaje, maxPuntaje, data[0].name);
                        });
                    }

                    showChart(data.map(participant => participant.score));
                })
                .catch(error => {
                    console.error('❌ Error al obtener los puntajes:', error);
                });
        }

        // Función para compartir mensaje de texto
        function shareWithTextScore(puntajeLogrado, puntajeMaximo) {
            const message =
                `¡Hola! Acabo de completar la encuesta y obtuve ${puntajeLogrado} de ${puntajeMaximo} puntos posibles. 🏆 ¿Puedes superarlo? 🚀`;
            const whatsappUrl = `https://api.whatsapp.com/send?text=${encodeURIComponent(message)}`;
            window.open(whatsappUrl, '_blank');
        }

        // Función para crear y compartir una imagen con el puntaje
        function createAndShareScoreImage(puntajeLogrado, puntajeMaximo, nombreUsuario) {
            const canvas = document.getElementById("score-canvas");
            const ctx = canvas.getContext("2d");

            // Limpiar canvas
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            // Fondo
            ctx.fillStyle = "#f5f5f5";
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // Borde
            ctx.strokeStyle = "#3498db";
            ctx.lineWidth = 10;
            ctx.strokeRect(10, 10, canvas.width - 20, canvas.height - 20);

            // Título
            ctx.fillStyle = "#2c3e50";
            ctx.font = "bold 32px Arial";
            ctx.textAlign = "center";
            ctx.fillText("Resultado de la Encuesta", canvas.width / 2, 60);

            // Nombre del usuario
            ctx.font = "22px Arial";
            ctx.fillText(nombreUsuario, canvas.width / 2, 100);

            // Dibujar círculo de puntaje
            const centerX = canvas.width / 2;
            const centerY = 200;
            const radius = 80;

            // Círculo de fondo
            ctx.beginPath();
            ctx.arc(centerX, centerY, radius, 0, Math.PI * 2);
            ctx.fillStyle = "#ecf0f1";
            ctx.fill();
            ctx.strokeStyle = "#95a5a6";
            ctx.lineWidth = 2;
            ctx.stroke();

            // Dibujar arco de progreso
            const porcentaje = puntajeLogrado / puntajeMaximo;
            ctx.beginPath();
            ctx.arc(centerX, centerY, radius, -Math.PI / 2, (-Math.PI / 2) + (Math.PI * 2 * porcentaje));
            ctx.lineTo(centerX, centerY);
            ctx.fillStyle = porcentaje >= 0.7 ? "#27ae60" : (porcentaje >= 0.4 ? "#f39c12" : "#e74c3c");
            ctx.fill();

            // Texto de puntaje
            ctx.fillStyle = "#2c3e50";
            ctx.font = "bold 36px Arial";
            ctx.textAlign = "center";
            ctx.fillText(`${puntajeLogrado}/${puntajeMaximo}`, centerX, centerY + 10);

            // Mensaje inferior
            ctx.font = "18px Arial";
            ctx.fillText("¡Gracias por participar!", centerX, 300);
            ctx.font = "16px Arial";
            ctx.fillText("Comparte tu resultado y desafía a tus amigos", centerX, 330);

            // Fecha
            const fecha = new Date().toLocaleDateString();
            ctx.font = "14px Arial";
            ctx.fillStyle = "#7f8c8d";
            ctx.fillText(fecha, centerX, 370);

            // Convertir el canvas a una imagen
            canvas.toBlob(function(blob) {
                // Crear una URL para el blob
                const imageUrl = URL.createObjectURL(blob);

                // Mostrar la imagen en la página (opcional)
                const canvasContainer = document.getElementById("canvas-container");
                canvasContainer.style.display = "block";
                canvasContainer.innerHTML = `
            <div class="card mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title">Tu tarjeta de resultados</h5>
                    <img src="${imageUrl}" alt="Resultado" class="img-fluid" style="max-width: 100%; height: auto; border: 1px solid #ddd; border-radius: 4px;">
                    <div class="mt-3">
                        <a href="${imageUrl}" download="mi-resultado.png" class="btn btn-sm btn-info">
                            <i class="fas fa-download"></i> Descargar
                        </a>
                        <button id="share-image" class="btn btn-sm btn-success">
                            <i class="fab fa-whatsapp"></i> Compartir
                        </button>
                    </div>
                </div>
            </div>
        `;

                // Evento para compartir la imagen
                document.getElementById("share-image").addEventListener("click", function() {
                    // En dispositivos móviles modernos, podemos usar la Web Share API si está disponible
                    if (navigator.share && blob) {
                        const file = new File([blob], 'mi-resultado.png', {
                            type: 'image/png'
                        });

                        navigator.share({
                            title: 'Mi resultado de la encuesta',
                            text: `¡Obtuve ${puntajeLogrado} de ${puntajeMaximo} puntos! ¿Puedes superarlo?`,
                            files: [file]
                        }).catch(error => {
                            console.error('Error al compartir:', error);
                            // Si falla, usamos el método alternativo
                            shareImageViaWhatsApp(imageUrl, puntajeLogrado, puntajeMaximo);
                        });
                    } else {
                        // Si Web Share API no está disponible, usamos el método alternativo
                        shareImageViaWhatsApp(imageUrl, puntajeLogrado, puntajeMaximo);
                    }
                });
            }, 'image/png');
        }

        // Función alternativa para compartir la imagen (solo texto con enlace)
        function shareImageViaWhatsApp(imageUrl, puntajeLogrado, puntajeMaximo) {
            // Nota: No es posible compartir una imagen directamente a WhatsApp desde JavaScript
            // sin que el usuario la descargue primero, a menos que tengas un backend que aloje la imagen
            // Esta es una alternativa con solo texto
            const message =
                `¡Hola! Acabo de completar la encuesta y obtuve ${puntajeLogrado} de ${puntajeMaximo} puntos posibles. 🏆 ¿Puedes superarlo? 🚀\n\nPuedes ver mi resultado descargando la imagen.`;
            const whatsappUrl = `https://api.whatsapp.com/send?text=${encodeURIComponent(message)}`;
            window.open(whatsappUrl, '_blank');

            // Sugerir al usuario que descargue la imagen
            alert(
                'WhatsApp no permite adjuntar imágenes automáticamente desde el navegador. Por favor, descarga la imagen y adjúntala manualmente a tu mensaje de WhatsApp.');
        }

        // Función para compartir en WhatsApp
        function shareOnWhatsApp(score) {
            // Usar el score pasado como parámetro en lugar de la variable global
            if (!score) {
                alert("⚠️ Tu puntaje aún no se ha cargado. Intenta nuevamente.");
                return;
            }

            const message =
                `¡Hola! Acabo de completar la encuesta y obtuve un puntaje de ${score} puntos. 🏆 ¿Puedes superarlo? 🚀`;
            const whatsappUrl = `https://api.whatsapp.com/send?text=${encodeURIComponent(message)}`;
            window.open(whatsappUrl, '_blank');
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
