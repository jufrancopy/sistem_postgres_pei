<?php

namespace App\Models\Riiss;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class RiissSesionFarmaceutica extends Model
{
    use HasFactory;

    protected $table = 'riiss_sesiones_farmaceuticas';

    protected $fillable = [
        'token',
        'codigo_acceso',
        'analista_nombre',
        'analista_cargo',
        'matricula_profesional',
        'analista_documento',
        'analista_telefono',
        'analista_email',
        'notas',
        'estado',
        'created_by_user_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->token)) {
                $model->token = Str::random(40);
            }
            if (empty($model->codigo_acceso)) {
                $model->codigo_acceso = 'VAL-FARM-' . strtoupper(Str::random(6));
            }
        });
    }

    public function creadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function validacionesEspecialidades(): HasMany
    {
        return $this->hasMany(RiissValidacionFarmaceuticaEspecialidad::class, 'sesion_farmaceutica_id');
    }

    public function dictamenesMedicamentos(): HasMany
    {
        return $this->hasMany(RiissValidacionFarmaceuticaMedicamento::class, 'sesion_farmaceutica_id');
    }

    public function getUrlAccesoAttribute(): string
    {
        return route('riiss.portal-farmaceutico.show', ['token' => $this->token]);
    }
}
