<?php

namespace App\Models\Admin\Globales;

use App\Admin\Globales\Organigrama;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatrimonyProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'patrimony_profiles';

    protected $fillable = [
        'dependency_id',
        'description'
    ];

    public function dependency()
    {
        return $this->belongsTo(Organigrama::class);
    }
}
