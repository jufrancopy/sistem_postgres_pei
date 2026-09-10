<?php

namespace App\Models\Riiss;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ValidacionEspecialidad extends Model
{
    use HasFactory;

    protected $table = 'riiss_validacion_especialidades';

    protected $fillable = [
        'establecimiento_id',
        'token',
        'estado',
        'validador_nombre',
        'validador_cargo',
        'validador_documento',
        'validador_telefono',
        'validador_email',
        'validador_firma',
        'validador_firmado_at',
        'observaciones_cierre',
        'total_especialidades',
        'total_validadas',
        'total_inactivadas',
        'total_pendientes',
        'created_by_user_id',
    ];

    protected $casts = [
        'validador_firmado_at' => 'datetime',
        'total_especialidades' => 'integer',
        'total_validadas'      => 'integer',
        'total_inactivadas'    => 'integer',
        'total_pendientes'     => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->token)) {
                $model->token = Str::random(32);
            }
        });
    }

    public function establecimiento(): BelongsTo
    {
        return $this->belongsTo(Establecimiento::class, 'establecimiento_id', 'id_establecimiento');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ValidacionEspecialidadItem::class, 'validacion_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by_user_id');
    }

    /**
     * Recalcula contadores de estado.
     */
    public function recalcularContadores(): void
    {
        $this->total_especialidades = $this->items()->count();
        $this->total_validadas      = $this->items()->where('estado', 'validada')->count();
        $this->total_inactivadas    = $this->items()->where('estado', 'inactiva')->count();
        $this->total_pendientes     = $this->items()->where('estado', 'pendiente')->count();

        if ($this->validador_firma && $this->validador_firmado_at) {
            $this->estado = 'firmada';
        } elseif ($this->total_pendientes === 0 && $this->total_especialidades > 0) {
            $this->estado = 'completada';
        } elseif ($this->total_validadas > 0 || $this->total_inactivadas > 0) {
            $this->estado = 'en_progreso';
        } else {
            $this->estado = 'pendiente';
        }

        $this->save();
    }

    public function getProgresoPorcentajeAttribute(): float
    {
        if ($this->total_especialidades <= 0) return 0;
        $revisadas = $this->total_validadas + $this->total_inactivadas;
        return round(($revisadas / $this->total_especialidades) * 100, 1);
    }
}
