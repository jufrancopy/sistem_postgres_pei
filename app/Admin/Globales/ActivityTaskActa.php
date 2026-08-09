<?php

namespace App\Admin\Globales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\User;

class ActivityTaskActa extends Model
{
    protected $table = 'activity_task_actas';

    protected $fillable = [
        'activity_task_id',
        'uuid',
        'numero_acta',
        'institucion',
        'dependencia',
        'lugar',
        'fecha',
        'hora_desde',
        'hora_hasta',
        'convocados_texto',
        'temas_tratar',
        'objetivo',
        'desarrollo',
        'acuerdos',
        'compromisos',
        'estado',
        'hash_seguridad',
        'firma_moderador',
        'fecha_firma_moderador',
        'qr_seguridad_path',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'fecha'                 => 'date',
        'fecha_firma_moderador' => 'datetime',
        'compromisos'           => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function task()
    {
        return $this->belongsTo(ActivityTask::class, 'activity_task_id');
    }

    public function participantes()
    {
        return $this->hasMany(ActivityTaskActaParticipante::class, 'acta_id')->orderBy('id', 'asc');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getPublicUrlAttribute(): string
    {
        return route('actas.public.show', $this->uuid);
    }
}
