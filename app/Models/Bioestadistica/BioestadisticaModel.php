<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class BioestadisticaModel extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
}
