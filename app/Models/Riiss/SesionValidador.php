<?php

namespace App\Models\Riiss;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class SesionValidador extends Model
{
    use HasFactory;

    protected $table = 'riiss_sesiones_validador';

    protected $fillable = [
        'token',
        'codigo_acceso',
        'analista_nombre',
        'analista_cargo',
        'analista_documento',
        'analista_telefono',
        'analista_email',
        'departamento_filtro',
        'estado',
        'notas',
        'firma_digital',
        'firmado_at',
        'created_by_user_id',
    ];

    protected $casts = [
        'firmado_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->token)) {
                $model->token = Str::random(40);
            }
            if (empty($model->codigo_acceso)) {
                $model->codigo_acceso = 'VAL-' . strtoupper(Str::random(6));
            }
        });
    }

    public function creadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function registros(): HasMany
    {
        return $this->hasMany(ValidacionEspecialidadRegistro::class, 'sesion_validador_id');
    }

    public function getUrlAccesoAttribute(): string
    {
        return url('/riiss/portal-validador/' . $this->token);
    }
}
