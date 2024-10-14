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

        // Formatear las respuestas y asociarlas en la tabla pivot
        $answers = [];
        foreach ($request->answer_id as $index => $answer) {
            $answers[] = [
                'answer' => $answer,
                'is_correct' => in_array($index, $request->is_correct),  // Verificar si es correcta
            ];
        }

        // Insertar respuestas como JSON en la tabla pivot o donde prefieras
        DB::table('answers_has_questions')->insert([
            'question_id' => $question->id,
            'answers' => json_encode($answers),  // Guardar el JSON de respuestas
        ]);

        return response()->json(['success' => 'Pregunta y respuestas guardadas correctamente', 'surveyID'=>$surveyID ]);
    }

    public function destroy(Request $request, $id)
    {
        $question = Question::find($id);
        $surveyID = $question->survey_id;
        $question->delete();

        return response()->json(['question'=>$question, 'surveyID'=>$surveyID]);
    }
}
