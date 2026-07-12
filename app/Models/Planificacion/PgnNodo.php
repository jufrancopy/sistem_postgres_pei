<?php

namespace App\Models\Planificacion;

use Illuminate\Database\Eloquent\Model;

class PgnNodo extends Model
{
    protected $table = 'planificacion.pgn_nodos';

    protected $fillable = [
        'anio', 'pgn_estructura_id', 'parent_id',
        'codigo', 'nombre', 'monto_asignado_gs', 'activo',
    ];

    protected $casts = [
        'activo'            => 'boolean',
        'anio'              => 'integer',
        'monto_asignado_gs' => 'decimal:2',
    ];

    // ── Relaciones ────────────────────────────────────────────────────────────

    public function estructura()
    {
        return $this->belongsTo(PgnEstructura::class, 'pgn_estructura_id');
    }

    public function padre()
    {
        return $this->belongsTo(PgnNodo::class, 'parent_id');
    }

    public function hijos()
    {
        return $this->hasMany(PgnNodo::class, 'parent_id');
    }

    public function accionesPei()
    {
        return $this->hasMany(PeiAccionPgn::class, 'pgn_nodo_id');
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeDeAnio($query, int $anio)
    {
        return $query->where('anio', $anio);
    }

    public function scopeRaices($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeHojas($query)
    {
        // Nodos sin hijos — los que se vinculan al PEI
        return $query->whereNotIn('id', function ($sub) {
            $sub->select('parent_id')
                ->from('planificacion.pgn_nodos')
                ->whereNotNull('parent_id');
        });
    }

    public function scopeBuscar($query, string $q)
    {
        return $query->where(function ($w) use ($q) {
            $w->whereRaw('LOWER(nombre) LIKE ?', ['%' . strtolower($q) . '%'])
              ->orWhereRaw('LOWER(codigo) LIKE ?', ['%' . strtolower($q) . '%']);
        });
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    // Ruta completa: "Programa > Subprograma > Actividad"
    public function rutaCompleta(): string
    {
        $partes = collect([$this->nombre]);
        $nodo   = $this;
        while ($nodo->parent_id) {
            $nodo    = $nodo->padre()->with('estructura')->first();
            $partes->prepend($nodo->nombre);
        }
        return $partes->implode(' › ');
    }

    // Formato para Select2
    public function toSelect2(): array
    {
        return [
            'id'     => $this->id,
            'text'   => ($this->codigo ? '[' . $this->codigo . '] ' : '') . $this->nombre,
            'nivel'  => $this->estructura?->nombre,
            'monto'  => $this->monto_asignado_gs,
        ];
    }
}
