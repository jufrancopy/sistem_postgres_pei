<?php

namespace App\Models\Admin\Globales;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Survey extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'surveys';

    protected $fillable = [
        'name',
        'description',
        'type',
    ];

    public function analysts()
    {
        return $this->belongsToMany(User::class, 'surveys_has_analysts', 'survey_id', 'analyst_id');
    }

    // public function questions()
    // {
    //     return $this->hasMany(Question::class, 'survey_id', 'id');
    // }


    public function questions()
    {
        return $this->belongsToMany(Question::class, 'answers_has_questions', 'answers', 'question_id');
    }
}
