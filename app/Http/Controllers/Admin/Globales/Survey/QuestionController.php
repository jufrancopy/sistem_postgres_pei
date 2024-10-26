<?php

namespace App\Http\Controllers\Admin\Globales\Survey;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Globales\Question;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function store(Request $request)
    {
        // Validar entrada
        $request->validate([
            'survey_id' => 'required',
            'question' => 'required',
        ]);

        // Guardar la pregunta primero
        $surveyID = $request->survey_id;
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

        return response()->json(['success' => 'Pregunta y respuestas guardadas correctamente', 'surveyID' => $surveyID]);
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

        return response()->json(['question' => $question, 'surveyID' => $surveyID]);
    }
}
