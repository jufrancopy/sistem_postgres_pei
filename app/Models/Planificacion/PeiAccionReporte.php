<?php

namespace App\Models\Planificacion;

use Illuminate\Database\Eloquent\Model;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Models\User;

class PeiAccionReporte extends Model
{
    protected $table = 'planificacion.pei_accion_reportes';

    protected $fillable = [
        'pei_profile_id', 'user_id',
        'fecha_reporte', 'periodo_label',
        'valor_numerador', 'descripcion_avance',
        'evidencia_url', 'evidencia_label',
        'semaforo', 'pct_avance',
    ];

    protected $casts = [
        'fecha_reporte'   => 'date',
        'valor_numerador' => 'float',
        'pct_avance'      => 'float',
    ];

    public function accion()
    {
        return $this->belongsTo(PeiProfile::class, 'pei_profile_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Calcula el semáforo y el % de avance dado el indicador de la acción.
     * Actualiza los campos y guarda si se pasa $save=true.
     */
    public function calcularSemaforo(Indicador $indicador, bool $save = false): void
    {
        if ($this->valor_numerador === null || !$indicador->metas) {
            $this->semaforo  = 'sin-datos';
            $this->pct_avance = null;
            if ($save) $this->save();
            return;
        }

        // Buscar la meta del año del reporte
        $anioReporte = $this->fecha_reporte ? $this->fecha_reporte->year : now()->year;
        $metaDelAnio = collect($indicador->metas)
            ->firstWhere('anio', $anioReporte);

        if (!$metaDelAnio) {
            // Si no hay meta exacta del año, tomar la más cercana hacia adelante
            $metaDelAnio = collect($indicador->metas)
                ->sortBy('anio')
                ->first(fn($m) => $m['anio'] >= $anioReporte);
        }

        if (!$metaDelAnio || !$metaDelAnio['valor']) {
            $this->semaforo  = 'sin-datos';
            $this->pct_avance = null;
            if ($save) $this->save();
            return;
        }

        // Extraer el valor numérico de la meta (puede ser "85%", "Reducir 5%", "1200", etc.)
        $metaNumero = (float) preg_replace('/[^0-9.]/', '', $metaDelAnio['valor']);

        if ($metaNumero <= 0) {
            $this->semaforo  = 'sin-datos';
            $this->pct_avance = null;
            if ($save) $this->save();
            return;
        }

        $pct = round(($this->valor_numerador / $metaNumero) * 100, 2);
        $this->pct_avance = $pct;

        // Semáforo según sentido del indicador
        if ($indicador->sentido === 'descendente') {
            // En descendente: lograr menos es mejor
            // Si el valor logrado es <= meta, vamos bien
            $ratio = $metaNumero > 0 ? ($this->valor_numerador / $metaNumero) : 1;
            if ($ratio <= 0.85)       $this->semaforo = 'verde';
            elseif ($ratio <= 1.0)    $this->semaforo = 'amarillo';
            else                      $this->semaforo = 'rojo';
        } else {
            // Ascendente: más es mejor
            if ($pct >= 85)      $this->semaforo = 'verde';
            elseif ($pct >= 50)  $this->semaforo = 'amarillo';
            else                 $this->semaforo = 'rojo';
        }

        if ($save) $this->save();
    }
}
