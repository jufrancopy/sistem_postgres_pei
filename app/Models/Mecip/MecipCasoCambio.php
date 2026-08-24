<?php

namespace App\Models\Mecip;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class MecipCasoCambio extends Model
{
    protected $table = 'mecip_caso_cambios';

    protected $fillable = [
        'mecip_caso_id',
        'user_id',
        'estado_anterior',
        'estado_nuevo',
        'observacion',
    ];

    public function caso()
    {
        return $this->belongsTo(MecipCaso::class, 'mecip_caso_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
