<?php

namespace App\Models\Admin\Globales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyScore extends Model
{
    use HasFactory;

    protected $table = 'survey_scores';

    protected $fillable = [
        'survey_id',
        'participant_id',
        'score',
    ];
}
