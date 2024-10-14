<?php

namespace App\Models\Admin\Globales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';

    protected $fillable = ['survey_id', 'question', 'answer'];

    // Método para obtener las respuestas desde la tabla pivot
    public function answers()
    {
        return DB::table('answers_has_questions')
            ->where('question_id', $this->id)
            ->get(); // Obtiene todas las respuestas para esta pregunta
    }
}
