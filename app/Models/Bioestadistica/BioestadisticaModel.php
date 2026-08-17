<?php

namespace App\Models\Bioestadistica;

use App\Models\Bioestadistica\Concerns\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class BioestadisticaModel extends Model
{
    use SoftDeletes;
    use Auditable;

    protected $guarded = ['id'];
}
