<?php

namespace App\Admin\Planificacion\Foda;

use App\Admin\Globales\Organigrama;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

use App\Admin\Planificacion\Task\Task;

use App\Admin\Globales\Group;

class FodaPerfil extends Model
{
    use HasUuids;

    protected $table = "planificacion.foda_perfiles";
    public $keyType = 'string';
    protected $dateFormat = 'Y-m-d H:i:sO';

    protected $fillable = ['name', 'context', 'type', 'model_id', 'dependency_id', 'group_id'];


    public function categories()
    {
        return $this->belongsToMany('App\Admin\Planificacion\Foda\FodaModelo', 'planificacion.foda_categorias_has_perfil', 'perfil_id', 'category_id');
    }

    public function groups()
    {
        return $this->belongsToMany('App\Admin\Globales\Group', 'planificacion.foda_perfiles_has_groups', 'perfil_id', 'group_id');
    }

    public function dependency()
    {
        return $this->belongsTo(Organigrama::class);
    }

    public function aspectos()
    {

        return $this->belongsToMany('App\Admin\Planificacion\Foda\FodaAspecto');
    }

    public function model()
    {
        return $this->belongsTo(FodaModelo::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function tasks()
    {
        return $this->morphMany(Task::class, 'typetaskable', 'typetask_id');
    }

    public function scopeNombre($query, $nombre)
    {
        if (trim($nombre) != "") {

            $query->where(DB::raw("CONCAT(nombre, ' ', contexto)"), 'LIKE', "%$nombre%");
        }
    }
}
