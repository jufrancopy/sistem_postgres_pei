<?php

namespace App\Models\Proyectos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Admin\Globales\Organigrama;

class ProyectoConsultaGerencia extends Model
{
    protected $table = 'proyectos_consultas_gerencias';

    protected $fillable = [
        'proyecto_id', 'organigrama_id', 'estado',
        'comentario', 'respondido_por', 'fecha_respuesta',
    ];

    protected $casts = ['fecha_respuesta' => 'datetime'];

    public function proyecto(): BelongsTo    { return $this->belongsTo(ProyectoInstitucional::class, 'proyecto_id'); }
    public function gerencia(): BelongsTo    { return $this->belongsTo(Organigrama::class, 'organigrama_id'); }
    public function respondidoPor(): BelongsTo { return $this->belongsTo(User::class, 'respondido_por'); }
}
