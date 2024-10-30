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

    // Consulta manual en answers()
    // public function answers()
    // {
    //     return DB::table('answers_has_questions')
    //         ->where('question_id', $this->id)
    //         ->get();
    // }

    // Método para obtener respuestas como array
    public function getAnswersArray()
    {
        return $this->answers()->get()->map(function ($answer) {
            // Verifica si $answer->answers es un string antes de decodificar
            return is_string($answer->answers) ? json_decode($answer->answers, true) : $answer->answers;
        });
    }

    // En el modelo Question
    public function answersHasQuestions()
    {
        return $this->hasMany(AnswersHasQuestions::class); // Asegúrate de usar el modelo correcto
    }


    public function answers()
    {
        return $this->hasMany(AnswersHasQuestions::class, 'question_id', 'id');
    }

    // Relación para obtener respuestas
    public function getAnswers()
    {
        return $this->hasMany(AnswersHasQuestions::class, 'question_id');
    }

    // Agrega esto en el método countAnswers() para ver cuántas respuestas tiene

    public function countAnswers()
    {
        // Asegúrate de que estás usando la tabla y la relación correcta
        return DB::table('answers')->where('question_id', $this->id)->count();
    }
}
