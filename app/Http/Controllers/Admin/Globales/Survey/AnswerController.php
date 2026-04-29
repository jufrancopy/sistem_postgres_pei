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
            'score' => 'required|integer|min:0',
        ], [
            'participant_id.required' => 'El ID del participante es obligatorio.',
            'survey_id.required' => 'El ID de la encuesta es obligatorio.',
            'survey_id.uuid' => 'El ID de la encuesta debe ser un UUID válido.',
            'score.required' => 'El puntaje es obligatorio.',
            'score.integer' => 'El puntaje debe ser un número entero.',
            'score.min' => 'El puntaje no puede ser negativo.',
        ]);

        DB::beginTransaction();

        try {
            // Verificar si ya existe un puntaje para este participante y encuesta
            if (SurveyScore::where($validated)->exists()) {
                return response()->json(['message' => 'Puntaje ya registrado'], 400);
            }

            // Guardar el nuevo puntaje
            SurveyScore::create($validated);

            // Actualizar o insertar en participants_has_surveys
            DB::table('participants_has_surveys')->updateOrInsert(
                ['survey_id' => $validated['survey_id'], 'participant_id' => $validated['participant_id']],
                ['completed' => true]
            );

            DB::commit();
            return response()->json(['message' => 'Puntaje guardado exitosamente']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al guardar el puntaje: ' . $e->getMessage()], 500);
        }
    }

    public function hasUserResponded($surveyId)
    {
        $userId = auth()->id();
        $hasResponded = DB::table('participants_has_surveys')
            ->where('survey_id', $surveyId)
            ->where('participant_id', $userId)
            ->where('completed', true)
            ->exists();

        return response()->json(['hasResponded' => $hasResponded]);
    }

    public function getScores($surveyId)
    {
        $scores = SurveyScore::where('survey_id', $surveyId)
            ->join('users', 'survey_scores.participant_id', '=', 'users.id')
            ->select('survey_scores.score', 'users.name', 'survey_scores.participant_id')
            ->orderBy('survey_scores.score', 'desc')
            ->get();

        return response()->json($scores);
    }
}
