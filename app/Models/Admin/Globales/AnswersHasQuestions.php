<?php

namespace App\Models\Admin\Globales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswersHasQuestions extends Model
{
    use HasFactory;
    protected $table = 'answers_has_questions';

    protected $fillable = [
        'question_id',
        'answers',
    ];

    protected $casts = [
        'answers' => 'array', // Esto convierte el JSON automáticamente a un array
    ];
}
