<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsuarioEstablecimiento extends Model
{
    protected $table = 'bioestadistica.usuario_establecimientos';

    protected $guarded = ['id'];

    public function establecimiento(): BelongsTo
    {
        return $this->belongsTo(Establecimiento::class);
    }
}
