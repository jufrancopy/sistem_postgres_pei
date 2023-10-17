<?php

namespace App\Admin\Planificacion\Task;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeTask extends Model
{
    use HasFactory;

    protected $table = "planificacion.typetasks";

    protected $fillable = ['typetaskable_id', 'name', 'typetaskable_type'];

    public function typetaskable()
    {
        return $this->morphTo('typetaskable', 'typetaskable_id');
    }
}
