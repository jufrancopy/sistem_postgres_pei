<?php

namespace App\Http\Controllers\Admin\Globales\Survey;

use App\Http\Controllers\Controller;
use App\Models\Admin\Globales\Answer;
use App\Models\Admin\Globales\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnswerController extends Controller
{
    public function saveAnswer(Request $request)
    {
        // Validar los datos recibidos
        $validatedData = $request->validate([
            'participant_id' => 'required|exists:users,id',
            'survey_id' => 'required|exists:surveys,id',
            'question_id' => 'required|exists:questions,id',
            'answer' => 'required|string',
            'is_correct' => 'required|boolean',
        ]);
    
        // Guardar la respuesta
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
            ->where('participant_id', $validatedData['participant_id']) // Asegurar que solo se cuenten las respuestas de este participante
            ->distinct('question_id')
            ->count('question_id');
    
        // Si todas las preguntas han sido respondidas por el participante
        if ($totalQuestions === $answeredQuestions) {
            // Actualizar el estado en la tabla pivot 'participants_has_surveys'
            DB::table('participants_has_surveys')
                ->where('survey_id', $validatedData['survey_id'])
                ->where('participant_id', $validatedData['participant_id'])
                ->update(['completed' => true]); // Marcar la encuesta como completada para el participante
        }
    
        return response()->json(['message' => 'Respuesta guardada correctamente']);
    }
    
}
