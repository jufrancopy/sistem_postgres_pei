<?php

namespace App\Admin\Globales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kalnoy\Nestedset\NodeTrait;
use App\Admin\Planificacion\Pei\PeiProfile;

class Organigrama extends Model
{
    use NodeTrait;
    
    protected $table = 'organigramas';
    
    protected $fillable = ['dependency', 'user_id', 'establecimiento_id', 'email', 'manager', 'phone',
        'tipo_establecimiento', 'nivel_complejidad', 'tenencia', 'tiene_aop', 'region', 'locality_id'];

    protected $casts = ['tiene_aop' => 'boolean'];

    public function esEstablecimiento(): bool
    {
        return !is_null($this->establecimiento_id) || !is_null($this->tipo_establecimiento);
    }

    public function establecimiento()
    {
        return $this->belongsTo(\App\Models\Bioestadistica\Establecimiento::class, 'establecimiento_id');
    }

    public function parent()
    {
        return $this->belongsTo(Organigrama::class, 'parent_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
    
    public function scopeNombre($query, $nombre)
    {
        if (trim($nombre) !="")
        {

    $query->where(DB::raw("CONCAT(dependency, ' ', dependency_id)"), 'LIKE', "%$nombre%");    
        }
        
    }

    /**
     * Obtener el PEI asociado a este organigrama
     */
    public function pei()
    {
        return $this->hasOne(PeiProfile::class, 'dependency_id');
    }

    /**
     * Obtener el grupo asociado a este organigrama a través del PEI
     */
    public function group()
    {
        return $this->hasOneThrough(
            Group::class,
            PeiProfile::class,
            'dependency_id', // Foreign key en pei_profiles
            'id', // Local key en groups
            'id', // Local key en organigramas
            'group_id' // Foreign key en pei_profiles
        );
    }
}


