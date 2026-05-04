<?php

namespace App\Models\Proyectos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class ProyectoHistorial extends Model
{
    protected $table = 'proyectos_historial';

    protected $fillable = [
        'proyecto_id', 'estado_anterior', 'estado_nuevo',
        'usuario_id', 'comentario', 'fecha',
    ];

    protected $casts = ['fecha' => 'datetime'];

    public function proyecto(): BelongsTo { return $this->belongsTo(ProyectoInstitucional::class, 'proyecto_id'); }
    public function usuario(): BelongsTo  { return $this->belongsTo(User::class, 'usuario_id'); }
}
