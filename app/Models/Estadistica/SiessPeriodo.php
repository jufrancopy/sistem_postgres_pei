<?php

namespace App\Models\Estadistica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SiessPeriodo extends Model
{
    protected $table = 'estadistica.siess_periodos';

    protected $fillable = ['anio', 'mes', 'tipo', 'fecha_inicio', 'fecha_fin', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function getNombreAttribute(): string
    {
        $meses = [
            1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',
            5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',
            9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre',
        ];
        if ($this->mes) {
            return ($meses[$this->mes] ?? $this->mes) . ' ' . $this->anio;
        }
        return 'Anual ' . $this->anio;
    }

    public function extractos(): HasMany
    {
        return $this->hasMany(SiessExtracto::class, 'periodo_id');
    }

    public static function periodoActual(string $tipo = 'mensual'): ?self
    {
        return self::where('tipo', $tipo)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->first();
    }
}
