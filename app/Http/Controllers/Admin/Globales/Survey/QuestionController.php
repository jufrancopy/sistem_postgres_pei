<?php

namespace App\Http\Controllers\Admin\Globales\Survey;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Globales\Question;
use App\Models\Admin\Globales\Survey;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function store(Request $request)
    {
        $surveyID = $request->survey_id;

        if ($request->type == 'generate_ia') {
            $iaSubject = $request->ia_subject;
            $iaNumberQuestion = $request->ia_number_question;
            $iaNumberAnswersForQuestion = $request->ia_number_anwers_for_question;

            // Obtén el contenido de la respuesta de Gemini    
            $response = Gemini::geminiPro()->generateContent("Generar $iaNumberQuestion preguntas de selección múltiple sobre $iaSubject. Cada pregunta debe tener $iaNumberAnswersForQuestion respuestas. Asegúrate de que las preguntas sean presentadas sin corchetes y que sigan el formato: Pregunta: [\"Pregunta aquí\"], Respuestas en JSON: [{\"answer\":\"Respuesta 1\",\"is_correct\":1},{\"answer\":\"Respuesta 2\",\"is_correct\":0}].");

            // Accede al contenido generado
            $questionsData = $response->candidates[0]->content->parts[0]->text;

            // Log para ver el contenido generado
            \Log::info('Contenido generado:', ['questionsData' => $questionsData]);

            // Dividir las preguntas por el patrón **Pregunta X:**  
            $questionsArray = preg_split('/\*\*Pregunta \d+\:\*\*/', $questionsData);

            // Log para verificar cómo se dividen las preguntas
            \Log::info('Preguntas divididas:', ['questionsArray' => $questionsArray]);

            foreach ($questionsArray as $questionBlock) {
                // Limpiar espacios en blanco antes y después
                $questionBlock = trim($questionBlock);

                // Comprobar si el bloque está vacío
                if (empty($questionBlock)) {
                    continue; // Si está vacío, saltar esta iteración
                }

                // Separar la pregunta de las respuestas
                $parts = explode('Respuestas en JSON:', $questionBlock);

                // Asegurarse de que hay exactamente dos partes: la pregunta y las respuestas
                if (count($parts) != 2) {
                    Log::warning('Formato no esperado', ['questionBlock' => $questionBlock]);
                    continue; // Si no hay el formato esperado, saltar esta iteración
                }

                // Extraer la pregunta
                $pregunta = trim($parts[0]); // La pregunta ya está separada
                // Eliminar numeración inicial si existe
                $pregunta = preg_replace('/^\d+\.\s*/', '', $pregunta);

                // Las respuestas en formato JSON
                $respuestasJson = trim($parts[1]); // Las respuestas en formato JSON

                // Limpiar posibles caracteres extraños
                $respuestasJson = str_replace(["\n", '`'], '', $respuestasJson);
                $respuestasJson = preg_replace('/\s+/', ' ', $respuestasJson); // Eliminar espacios extra

                // Decodificar las respuestas
                $respuestasArray = json_decode($respuestasJson, true);

                // Verifica que el array de respuestas no esté vacío
                if (empty($respuestasArray)) {
                    Log::warning('Array de respuestas vacío', ['respuestasJson' => $respuestasJson]);
                    continue; // O puedes manejar el error como prefieras
                }

                // Guardar la pregunta
                $savedQuestion = Question::create([
                    'survey_id' => $request->survey_id,
                    'question' => $pregunta // Aquí se guarda la pregunta
                ]);

                // Transformar las respuestas al formato necesario
                $answers = [];
                foreach ($respuestasArray as $respuesta) {
                    $answers[] = [
                        'answer' => $respuesta['answer'],
                        'is_correct' => $respuesta['is_correct'],
                    ];
                }

                // Insertar respuestas como JSON en la tabla pivot
                DB::table('answers_has_questions')->insert([
                    'question_id' => $savedQuestion->id,
                    'answers' => json_encode($answers), // Guardar el JSON de respuestas
                ]);
            }

            $totalQuestions = Survey::find($request->survey_id)->questions->count();
            // Responder después de guardar todas las preguntas y respuestas
            return response()->json(['success' => 'Pregunta y respuestas guardadas correctamente', 'surveyID' => $request->survey_id, 'totalQuestions' => $totalQuestions]);
        } else {
            $request->validate([
                'survey_id' => 'required',
                'question' => 'required',
            ]);

            $question = Question::create([
                'survey_id' => $surveyID,
                'question' => $request->question
            ]);

            // Asegurarse de que $request->is_correct esté siempre definido como array
            $is_correct_answers = $request->is_correct ?? [];

            // Formatear las respuestas y asociarlas en la tabla pivot
            $answers = [];

            foreach ($request->answer_id as $index => $answer) {
                // Verifica si el valor está en el array de respuestas correctas
                $isCorrect = in_array($index + 1, $is_correct_answers) ? 1 : 0;

                $answers[] = [
                    'answer' => $answer,
                    'is_correct' => $isCorrect,  // Marca si la respuesta es correcta
                ];
            }

            // Insertar respuestas como JSON en la tabla pivot o donde prefieras
            DB::table('answers_has_questions')->insert([
                'question_id' => $question->id,
                'answers' => json_encode($answers),  // Guardar el JSON de respuestas
            ]);

            $totalQuestions = Survey::find($surveyID)->questions->count();

            return response()->json(['success' => 'Pregunta y respuestas guardadas correctamente', 'surveyID' => $surveyID, 'totalQuestions' => $totalQuestions]);
        }
    }

    public function show(Request $request, $surveyID)
    {
        $questions = Question::where('survey_id', $surveyID)->get();

        return view('admin.surveys.questions.index', compact('questions'));
    }

    public function destroy(Request $request, $id)
    {
        $question = Question::find($id);
        $surveyID = $question->survey_id;
        $question->delete();

        $totalQuestions = Survey::find($surveyID)->questions->count();

        return response()->json(['question' => $question, 'surveyID' => $surveyID, 'totalQuestions' => $totalQuestions]);
    }
}
