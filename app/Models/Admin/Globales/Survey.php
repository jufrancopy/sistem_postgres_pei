<?php

namespace App\Models\Admin\Globales;

use App\Admin\Globales\Group;
use App\Admin\Globales\Organigrama;
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
        'type',
        'group_id',
        'dependency_id'
    ];
    

    public function analysts()
    {
        return $this->belongsToMany(User::class, 'surveys_has_analysts', 'survey_id', 'analyst_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function dependency()
    {
        return $this->belongsTo(Organigrama::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'participants_has_surveys', 'survey_id', 'participant_id')
            ->withPivot('completed');
    }

    public function markAsCompleted(User $participant)
    {
        // Encuentra la relación en la tabla pivot
        $this->participants()
            ->updateExistingPivot($participant->id, ['completed' => true]);
    }

    public function getTotalQuestionsAttribute()
    {
        return $this->questions()->count();
    }
}
