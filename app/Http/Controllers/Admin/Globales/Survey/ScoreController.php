<?php

namespace App\Http\Controllers\Admin\Globales\Survey;

use App\Http\Controllers\Controller;
use App\Models\Admin\Globales\Answer;
use App\Models\Admin\Globales\SurveyScore;
use Illuminate\Http\Request;

class ScoreController extends Controller
{

    public function calculateScores($surveyId)
    {
        // Obtenemos todos los participantes únicos para la encuesta dada
        $participants = Answer::where('survey_id', $surveyId)
            ->select('participant_id')
            ->distinct()
            ->pluck('participant_id');

        foreach ($participants as $participantId) {
            // Sumamos los puntos para el participante actual
            $score = Answer::where('survey_id', $surveyId)
                ->where('participant_id', $participantId)
                ->where('is_correct', true)
                ->count();

            // Guardamos o actualizamos el puntaje en survey_scores
            SurveyScore::updateOrCreate(
                [
                    'survey_id' => $surveyId,
                    'participant_id' => $participantId,
                ],
                ['score' => $score]
            );
        }
    }
}
