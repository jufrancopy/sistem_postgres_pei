<?php

namespace App\Http\Controllers\Admin\Globales\Survey;

use App\Http\Controllers\Controller;
use App\Models\Admin\Globales\Answer;
use App\Models\Admin\Globales\Survey;
use App\Models\Admin\Globales\SurveyScore;
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

    public function saveScore(Request $request)
    {
        // Validar los datos de entrada
        $validated = $request->validate([
            'participant_id' => 'required|integer',
            'survey_id' => 'required|uuid',
            'score' => 'required|integer',
        ]);

        // Verificar si ya existe un puntaje para este participante y encuesta
        $existingScore = SurveyScore::where('participant_id', $validated['participant_id'])
            ->where('survey_id', $validated['survey_id'])
            ->first();

        if ($existingScore) {
            // Si ya existe, devolver una respuesta indicando que el puntaje ya fue guardado
            return response()->json(['message' => 'Puntaje ya registrado'], 400);
        }

        // Si no existe, guardar el nuevo puntaje
        $score = new SurveyScore($validated);
        $score->save();

        // Actualizar el estado "completed" en la tabla participants_has_surveys
        DB::table('participants_has_surveys')
            ->where('survey_id', $validated['survey_id'])
            ->where('participant_id', $validated['participant_id'])
            ->update(['completed' => true]);

        return response()->json(['message' => 'Puntaje guardado exitosamente']);
    }

    public function hasUserResponded($surveyId)
    {
        $userId = auth()->id(); // Obtener el ID del usuario autenticado

        $hasResponded = DB::table('participants_has_surveys')
            ->where('survey_id', $surveyId)
            ->where('participant_id', $userId)
            ->where('completed', true) // Asegúrate de que este campo exista y se utilice correctamente
            ->exists();

        return response()->json(['hasResponded' => $hasResponded]);
    }
}
