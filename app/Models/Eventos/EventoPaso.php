<?php

namespace App\Models\Eventos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;

class EventoPaso extends Model
{
    use SoftDeletes;

    protected $table = 'evento_pasos';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'evento_id',
        'orden',
        'nombre',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'color',
        'responsable_principal_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin'    => 'date',
        'orden'        => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    // ── Relaciones ───────────────────────────────────────────────

    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    public function responsablePrincipal(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_principal_id');
    }

    public function responsables(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'evento_paso_responsables', 'evento_paso_id', 'user_id');
    }

    public function tareas(): HasMany
    {
        return $this->hasMany(EventoTarea::class, 'evento_paso_id')->orderBy('orden');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Accessors & Métricas ──────────────────────────────────────

    public function getTotalTareasAttribute(): int
    {
        return $this->tareas()->count();
    }

    public function getTareasCompletadasAttribute(): int
    {
        return $this->tareas()->where('completada', true)->count();
    }

    public function getPorcentajeAvanceAttribute(): int
    {
        $total = $this->total_tareas;
        if ($total === 0) {
            return $this->estado === 'completado' ? 100 : 0;
        }
        return (int) round(($this->tareas_completadas / $total) * 100);
    }

    public function getEstaEnAlertaAttribute(): bool
    {
        if ($this->estado === 'completado') return false;
        if (!$this->fecha_fin) return false;
        return Carbon::parse($this->fecha_fin)->endOfDay()->isPast();
    }

    public function getRangoFechasFormateadoAttribute(): string
    {
        if (!$this->fecha_inicio && !$this->fecha_fin) return 'Sin fecha';
        if ($this->fecha_inicio && $this->fecha_fin) {
            return Carbon::parse($this->fecha_inicio)->format('d/m/Y') . ' al ' . Carbon::parse($this->fecha_fin)->format('d/m/Y');
        }
        return Carbon::parse($this->fecha_inicio ?: $this->fecha_fin)->format('d/m/Y');
    }

    public function getEstadoBadgeHtmlAttribute(): string
    {
        $estado = $this->estado;
        if ($this->esta_en_alerta && $estado !== 'completado') {
            $estado = 'en_alerta';
        }

        $map = [
            'pendiente'   => ['label' => 'Pendiente', 'class' => 'badge-light text-dark border', 'icon' => 'fa-clock'],
            'en_proceso'  => ['label' => 'En Proceso', 'class' => 'badge-primary', 'icon' => 'fa-spinner fa-spin'],
            'completado'  => ['label' => 'Completado', 'class' => 'badge-success', 'icon' => 'fa-check-circle'],
            'en_alerta'   => ['label' => 'Vencido / Alerta', 'class' => 'badge-danger', 'icon' => 'fa-exclamation-triangle'],
        ];

        $data = $map[$estado] ?? ['label' => ucfirst($estado), 'class' => 'badge-secondary', 'icon' => 'fa-circle'];
        return '<span class="badge ' . $data['class'] . ' px-2 py-0.5" style="font-size: 0.72rem; border-radius: 6px;"><i class="fa ' . $data['icon'] . ' mr-1"></i>' . $data['label'] . '</span>';
    }
}
