<?php

namespace App\Models\Riiss;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class FormularioSeccion extends Model
{
    protected $table = 'formulario_secciones';

    protected $fillable = [
        'seccion',
        'sub_seccion',
        'dimension',
        'icono',
        'descripcion',
        'codigo_seccion',
        'orden',
        'activa'
    ];

    protected $casts = [
        'activa' => 'boolean',
        'orden'  => 'integer'
    ];

    public const DIMENSIONES = [
        'cartera_servicios'   => [
            'nombre' => 'Cartera de Servicios',
            'icono'  => 'fa-stethoscope',
            'color'  => '#0284c7',
            'bg'     => '#f0f9ff'
        ],
        'infraestructura'     => [
            'nombre' => 'Infraestructura e Instalaciones',
            'icono'  => 'fa-building',
            'color'  => '#d97706',
            'bg'     => '#fffbeb'
        ],
        'talento_humano'      => [
            'nombre' => 'Talento Humano',
            'icono'  => 'fa-users',
            'color'  => '#7c3aed',
            'bg'     => '#f5f3ff'
        ],
        'medicamentos_insumos'=> [
            'nombre' => 'Medicamentos, Insumos y Equipos',
            'icono'  => 'fa-pills',
            'color'  => '#0d9488',
            'bg'     => '#f0fdf4'
        ],
        'gobernanza_procesos' => [
            'nombre' => 'Gobernanza y Documentación',
            'icono'  => 'fa-file-shield',
            'color'  => '#475569',
            'bg'     => '#f8fafc'
        ],
    ];

    public function preguntas(): HasMany
    {
        return $this->hasMany(FormularioPregunta::class, 'formulario_seccion_id')->orderBy('orden');
    }

    public function preguntasActivas(): HasMany
    {
        return $this->hasMany(FormularioPregunta::class, 'formulario_seccion_id')->where('activa', true)->orderBy('orden');
    }

    public function reglas(): HasMany
    {
        return $this->hasMany(ReglaSeccionFormulario::class, 'formulario_seccion_id');
    }

    public function getNombreCompletoAttribute(): string
    {
        return $this->sub_seccion
            ? "{$this->seccion} > {$this->sub_seccion}"
            : $this->seccion;
    }

    public function getSlugAttribute(): string
    {
        return Str::slug($this->sub_seccion ?? $this->seccion, '_');
    }

    public function getDimensionConfigAttribute(): array
    {
        return self::DIMENSIONES[$this->dimension ?? 'cartera_servicios'] ?? self::DIMENSIONES['cartera_servicios'];
    }
}
