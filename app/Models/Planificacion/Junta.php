<?php

namespace App\Models\Planificacion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Junta extends Model
{
    use HasUuids;

    protected $table = 'planificacion.juntas';

    protected $fillable = [
        'nombre',
        'codigo',
        'programa',
        'descripcion',
        'fines',
        'atribuciones',
        'ambito_competencia',
        'presidente_nombre',
        'presidente_cargo',
        'firma_digital_url',
        'sello_institucional_url',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function intervenciones()
    {
        return $this->hasMany(JuntaIntervencion::class, 'junta_id');
    }

    public function integrantes()
    {
        return $this->belongsToMany(\App\Models\User::class, 'planificacion.junta_integrantes', 'junta_id', 'user_id')
                    ->withPivot('cargo')
                    ->withTimestamps();
    }

    public function peiProfiles()
    {
        return $this->belongsToMany(\App\Admin\Planificacion\Pei\PeiProfile::class, 'planificacion.pei_profile_juntas', 'junta_id', 'pei_profile_id')
                    ->withTimestamps();
    }

    public static function generarCodigo(string $programa): string
    {
        $prefix = strtoupper(substr($programa, 0, 3));
        $count = self::where('programa', $programa)->count() + 1;
        return 'JUNTA-' . $prefix . '-' . str_pad($count, 2, '0', STR_PAD_LEFT);
    }
}
