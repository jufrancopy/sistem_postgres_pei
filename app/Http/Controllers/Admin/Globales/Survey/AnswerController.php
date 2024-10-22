<?php

namespace App\Http\Controllers\Admin\Globales\Survey;

use App\Http\Controllers\Controller;
use App\Models\Admin\Globales\Answer;
use App\Models\Admin\Globales\Survey;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function saveAnswer(Request $request)
    {
        $validatedData = $request->validate([
            'participant_id' => 'required|exists:users,id',
            'survey_id' => 'required|exists:surveys,id',
            'question_id' => 'required|exists:questions,id',
            'answer' => 'required|string',
            'is_correct' => 'required|boolean',
        ]);

        Answer::create([
            'participant_id' => $validatedData['participant_id'],
            'survey_id' => $validatedData['survey_id'],
            'question_id' => $validatedData['question_id'],
            'answer' => json_encode(strip_tags($validatedData['answer'])), // Asegúrate de que sea JSON válido
            'is_correct' => $validatedData['is_correct'],
        ]);



        // Verificar si todas las preguntas de la encuesta han sido respondidas
        $survey = Survey::find($validatedData['survey_id']);
        $totalQuestions = $survey->questions()->count();
        $answeredQuestions = Answer::where('survey_id', $validatedData['survey_id'])
            ->distinct('question_id')
            ->count('question_id');

        // Si todas las preguntas han sido respondidas, actualizar el estado de la encuesta
        if ($totalQuestions === $answeredQuestions) {
            $survey->status = 1; // Cambiar a completado
            $survey->save();
        }

        return response()->json(['message' => 'Respuesta guardada correctamente']);
    }
}
