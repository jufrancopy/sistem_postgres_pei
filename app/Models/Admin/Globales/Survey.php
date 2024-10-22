<?php

namespace App\Models\Admin\Globales;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class Survey extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'surveys';

    protected $fillable = [
        'name',
        'description',
        'type'
    ];

    public function analysts()
    {
        return $this->belongsToMany(User::class, 'surveys_has_analysts', 'survey_id', 'analyst_id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'participants_has_surveys', 'survey_id', 'participant_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function markAsCompleted(User $participant)
    {
        // Encuentra la relación en la tabla pivot
        $this->participants()
            ->updateExistingPivot($participant->id, ['completed' => true]);
    }
}
