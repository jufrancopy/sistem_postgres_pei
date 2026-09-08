<?php

namespace App\Models\Eventos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;

class EventoTarea extends Model
{
    use SoftDeletes;

    protected $table = 'evento_tareas';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'evento_paso_id',
        'evento_id',
        'nombre',
        'descripcion',
        'responsable_id',
        'fecha_limite',
        'prioridad',
        'completada',
        'completada_el',
        'completada_por',
        'costo_estimado',
        'orden',
        'observaciones',
    ];

    protected $casts = [
        'fecha_limite'   => 'date',
        'completada'     => 'boolean',
        'completada_el'  => 'datetime',
        'costo_estimado' => 'decimal:2',
        'orden'          => 'integer',
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

    public function paso(): BelongsTo
    {
        return $this->belongsTo(EventoPaso::class, 'evento_paso_id');
    }

    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function completadaPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completada_por');
    }

    // ── Scopes ───────────────────────────────────────────────────

    public function scopePendientes($query)
    {
        return $query->where('completada', false);
    }

    public function scopeCompletadas($query)
    {
        return $query->where('completada', true);
    }

    public function scopeVencidas($query)
    {
        return $query->where('completada', false)
                     ->whereNotNull('fecha_limite')
                     ->where('fecha_limite', '<', Carbon::today());
    }

    public function scopeProximasAVencer($query, $dias = 3)
    {
        return $query->where('completada', false)
                     ->whereNotNull('fecha_limite')
                     ->whereBetween('fecha_limite', [Carbon::today(), Carbon::today()->addDays($dias)]);
    }

    // ── Accessors ────────────────────────────────────────────────

    public function getEstaVencidaAttribute(): bool
    {
        if ($this->completada || !$this->fecha_limite) return false;
        return Carbon::parse($this->fecha_limite)->endOfDay()->isPast();
    }

    public function getEsProximaAttribute(): bool
    {
        if ($this->completada || !$this->fecha_limite || $this->esta_vencida) return false;
        $diff = Carbon::today()->diffInDays(Carbon::parse($this->fecha_limite), false);
        return $diff >= 0 && $diff <= 3;
    }

    public function getPrioridadBadgeHtmlAttribute(): string
    {
        $map = [
            'urgente' => ['label' => 'Urgente', 'class' => 'badge-danger', 'icon' => 'fa-fire'],
            'alta'    => ['label' => 'Alta', 'class' => 'badge-warning text-dark', 'icon' => 'fa-exclamation'],
            'media'   => ['label' => 'Media', 'class' => 'badge-info', 'icon' => 'fa-arrow-right'],
            'baja'    => ['label' => 'Baja', 'class' => 'badge-light text-dark border', 'icon' => 'fa-arrow-down'],
        ];

        $data = $map[$this->prioridad] ?? ['label' => ucfirst($this->prioridad), 'class' => 'badge-secondary', 'icon' => 'fa-circle'];
        return '<span class="badge ' . $data['class'] . ' px-2 py-0.5" style="font-size: 0.68rem; border-radius: 4px;"><i class="fa ' . $data['icon'] . ' mr-1"></i>' . $data['label'] . '</span>';
    }
}
