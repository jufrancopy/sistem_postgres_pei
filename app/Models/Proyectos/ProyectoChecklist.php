<?php

namespace App\Models\Proyectos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class ProyectoChecklist extends Model
{
    protected $table = 'proyectos_checklist';

    protected $fillable = [
        'proyecto_id', 'item', 'completado',
        'archivo_url', 'observacion', 'completado_por', 'completado_at',
    ];

    protected $casts = [
        'completado'    => 'boolean',
        'completado_at' => 'datetime',
    ];

    public function proyecto(): BelongsTo  { return $this->belongsTo(ProyectoInstitucional::class, 'proyecto_id'); }
    public function completadoPor(): BelongsTo { return $this->belongsTo(User::class, 'completado_por'); }

    public function labelItem(): string
    {
        return ProyectoInstitucional::CHECKLIST_ITEMS[$this->item] ?? ucfirst($this->item);
    }
}
