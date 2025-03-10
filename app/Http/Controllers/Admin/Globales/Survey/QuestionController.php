<?php

namespace App\Http\Controllers\Admin\Globales\Survey;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Globales\Question;
use App\Models\Admin\Globales\Survey;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class QuestionController extends Controller
{
    public function store(Request $request)
    {
        $surveyID = $request->survey_id;
        $language = $request->language; // Verificamos el idioma seleccionado

        if ($request->type == 'generate_ia') {
            $iaSubject = $request->ia_subject;
            $iaNumberQuestion = $request->ia_number_question;
            $iaNumberAnswersForQuestion = $request->ia_number_anwers_for_question;

            // Prompt en español
            $promptEs = "Genera $iaNumberQuestion preguntas de selección múltiple sobre $iaSubject. 
                   Cada pregunta debe tener $iaNumberAnswersForQuestion respuestas. 
                   Formato esperado:
                   Pregunta: \"Texto de la pregunta\"
                   Respuestas: [{\"answer\":\"Respuesta 1\",\"is_correct\":1},{\"answer\":\"Respuesta 2\",\"is_correct\":0}]";

            // Prompt en inglés (manteniendo la estructura en español)
            $promptEn = "Generate $iaNumberQuestion multiple-choice questions about $iaSubject. 
                    Each question should have $iaNumberAnswersForQuestion answer options. 
                    Expected format:
                    Pregunta: \"Question text\"
                    Respuestas: [{\"answer\":\"Answer 1\",\"is_correct\":1},{\"answer\":\"Answer 2\",\"is_correct\":0}]";

            // Seleccionar el prompt adecuado
            $prompt = $language === 'language_en' ? $promptEn : $promptEs;

            try {
                $response = Gemini::generativeModel("gemini-1.5-flash")->generateContent($prompt);
                $questionsData = $response->candidates[0]->content->parts[0]->text;

                // 1️⃣ Limpiar y normalizar el texto recibido
                $questionsData = str_replace(["\n", "\r", '`'], '', $questionsData);
                $questionsData = trim($questionsData);

                // 2️⃣ Extraer preguntas y respuestas usando regex
                preg_match_all('/Pregunta:\s*"([^"]+)"\s*Respuestas:\s*(\[.*?\])/', $questionsData, $matches, PREG_SET_ORDER);

                // 3️⃣ Procesar cada pregunta y asegurarnos de que las respuestas sean JSON válidos
                foreach ($matches as $match) {
                    $pregunta = trim($match[1]);
                    $respuestasJson = trim($match[2]);

                    // Intentamos decodificar el JSON
                    $respuestasArray = json_decode($respuestasJson, true);

                    // 4️⃣ Si json_decode falla, reconstruimos el JSON correctamente
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        Log::warning("Error en JSON, reconstruyendo...", ['json' => $respuestasJson]);

                        // Forzamos la estructura esperada
                        preg_match_all('/{"answer":"(.*?)","is_correct":(0|1)}/', $respuestasJson, $respMatches, PREG_SET_ORDER);

                        $respuestasArray = [];
                        foreach ($respMatches as $resp) {
                            $respuestasArray[] = [
                                "answer" => trim($resp[1]),
                                "is_correct" => (int) $resp[2]
                            ];
                        }
                    }

                    // Si aún no tenemos respuestas en formato válido, descartamos
                    if (empty($respuestasArray)) {
                        Log::warning("No se pudieron reconstruir respuestas", ['question' => $pregunta]);
                        continue;
                    }

                    // 5️⃣ Guardamos la pregunta
                    $savedQuestion = Question::create([
                        'survey_id' => $surveyID,
                        'question' => $pregunta
                    ]);

                    // 6️⃣ Guardamos las respuestas en la base de datos
                    DB::table('answers_has_questions')->insert([
                        'question_id' => $savedQuestion->id,
                        'answers' => json_encode($respuestasArray, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    ]);
                }

                DB::commit();

                $totalQuestions = Survey::find($surveyID)->questions->count();
                return response()->json(['success' => 'Pregunta y respuestas guardadas correctamente', 'surveyID' => $surveyID, 'totalQuestions' => $totalQuestions]);
            } catch (\Exception $e) {
                DB::rollback();
                Log::error("Error al generar preguntas IA: " . $e->getMessage());
                return response()->json(['error' => 'Error al generar preguntas IA'], 500);
            }
        } else {
            // Guardado manual
            $request->validate([
                'survey_id' => 'required',
                'question' => 'required',
            ]);

            try {
                DB::beginTransaction();

                $question = Question::create([
                    'survey_id' => $surveyID,
                    'question' => $request->question
                ]);

                $answers = [];
                foreach ($request->answer_id as $index => $answer) {
                    $isCorrect = in_array($index + 1, $request->is_correct ?? []) ? 1 : 0;
                    $answers[] = ['answer' => $answer, 'is_correct' => $isCorrect];
                }

                DB::table('answers_has_questions')->insert([
                    'question_id' => $question->id,
                    'answers' => json_encode($answers, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                ]);

                DB::commit();

                $totalQuestions = Survey::find($surveyID)->questions->count();
                return response()->json(['success' => 'Pregunta y respuestas guardadas correctamente', 'surveyID' => $surveyID, 'totalQuestions' => $totalQuestions]);
            } catch (\Exception $e) {
                DB::rollback();
                Log::error("Error al guardar pregunta manualmente: " . $e->getMessage());
                return response()->json(['error' => 'Error al guardar la pregunta'], 500);
            }
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
