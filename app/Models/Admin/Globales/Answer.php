<?php

namespace App\Models\Admin\Globales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    // Permite la asignación masiva de estos campos
    protected $fillable = [
        'participant_id',
        'survey_id',
        'question_id',
        'answer',
        'is_correct'
    ];
}
