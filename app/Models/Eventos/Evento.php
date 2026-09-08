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
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Admin\Globales\Activity;
use App\Admin\Globales\Organigrama;

class Evento extends Model
{
    use SoftDeletes;

    protected $table = 'eventos';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'nombre',
        'tipo',
        'descripcion',
        'lugar_sede',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'color',
        'pei_profile_id',
        'activity_id',
        'organigrama_id',
        'presupuesto_estimado',
        'presupuesto_ejecutado',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'fecha_inicio'          => 'date',
        'fecha_fin'             => 'date',
        'presupuesto_estimado'  => 'decimal:2',
        'presupuesto_ejecutado' => 'decimal:2',
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

    public function peiProfile(): BelongsTo
    {
        return $this->belongsTo(PeiProfile::class, 'pei_profile_id');
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }

    public function organigrama(): BelongsTo
    {
        return $this->belongsTo(Organigrama::class, 'organigrama_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function responsables(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'evento_responsables', 'evento_id', 'user_id')
                    ->withPivot('rol_evento');
    }

    public function pasos(): HasMany
    {
        return $this->hasMany(EventoPaso::class, 'evento_id')->orderBy('orden');
    }

    public function tareas(): HasMany
    {
        return $this->hasMany(EventoTarea::class, 'evento_id')->orderBy('orden');
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
            // Si no hay tareas pero hay pasos completados
            $totalPasos = $this->pasos()->count();
            if ($totalPasos === 0) return $this->estado === 'completado' ? 100 : 0;
            $pasosCompletados = $this->pasos()->where('estado', 'completado')->count();
            return (int) round(($pasosCompletados / $totalPasos) * 100);
        }
        return (int) round(($this->tareas_completadas / $total) * 100);
    }

    public function getDiasRestantesAttribute(): ?int
    {
        if (!$this->fecha_fin) return null;
        $now = Carbon::now()->startOfDay();
        $fin = Carbon::parse($this->fecha_fin)->startOfDay();
        return (int) $now->diffInDays($fin, false);
    }

    public function getEstaVencidoAttribute(): bool
    {
        if (!$this->fecha_fin || $this->estado === 'completado') return false;
        return Carbon::parse($this->fecha_fin)->endOfDay()->isPast();
    }

    public function getEstadoBadgeHtmlAttribute(): string
    {
        $map = [
            'planificado' => ['label' => 'Planificado', 'class' => 'badge-info', 'icon' => 'fa-calendar-alt'],
            'en_curso'    => ['label' => 'En Curso', 'class' => 'badge-primary', 'icon' => 'fa-spinner fa-spin'],
            'completado'  => ['label' => 'Completado', 'class' => 'badge-success', 'icon' => 'fa-check-circle'],
            'en_alerta'   => ['label' => 'En Alerta', 'class' => 'badge-danger', 'icon' => 'fa-exclamation-triangle'],
            'pospuesto'   => ['label' => 'Pospuesto', 'class' => 'badge-warning text-dark', 'icon' => 'fa-pause-circle'],
            'cancelado'   => ['label' => 'Cancelado', 'class' => 'badge-secondary', 'icon' => 'fa-times-circle'],
        ];

        $data = $map[$this->estado] ?? ['label' => ucfirst($this->estado), 'class' => 'badge-secondary', 'icon' => 'fa-circle'];
        return '<span class="badge ' . $data['class'] . ' px-2.5 py-1" style="font-size: 0.75rem; border-radius: 6px;"><i class="fa ' . $data['icon'] . ' mr-1"></i>' . $data['label'] . '</span>';
    }

    public function getRangoFechasFormateadoAttribute(): string
    {
        if (!$this->fecha_inicio && !$this->fecha_fin) return 'Sin fecha definida';
        if ($this->fecha_inicio && $this->fecha_fin) {
            return Carbon::parse($this->fecha_inicio)->format('d/m/Y') . ' al ' . Carbon::parse($this->fecha_fin)->format('d/m/Y');
        }
        return Carbon::parse($this->fecha_inicio ?: $this->fecha_fin)->format('d/m/Y');
    }
}
