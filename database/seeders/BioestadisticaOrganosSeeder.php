<?php

namespace Database\Seeders;

use App\Models\Bioestadistica\Organo;
use App\Models\Bioestadistica\OrganoTipo;
use Illuminate\Database\Seeder;

/**
 * Carga tipos y árbol fase 1 (Gerencia de Salud → abajo).
 * Fuentes: .docs-bio/inventario-organos-*.md — se irá completando/corrigiendo.
 */
class BioestadisticaOrganosSeeder extends Seeder
{
    /** @var array<string, int> */
    private array $tipoIds = [];

    public function run(): void
    {
        $this->seedTipos();

        $gerencia = $this->node('gerencia', 'Gerencia de Salud', null, [
            'fuente_pagina' => 39,
            'orden' => 1,
            'notas' => 'Raíz fase 1; luego colgará de Presidencia.',
        ]);

        $this->seedHijosDirectosGerencia($gerencia);
        $this->seedUnidadObstetricia($gerencia);
        $this->seedDireccionMedicaHc($gerencia);
        $this->seedDireccionApoyo($gerencia);
        $this->seedMedicinaPreventiva($gerencia);
        $this->seedGestionMedica($gerencia);
        $this->seedEnfermeria($gerencia);
        $this->seedHeq($gerencia);
        $this->seedHospitalesAreaCentral($gerencia);
        $this->seedHospitalesAreaInterior($gerencia);

        $this->command?->info('Organos: tipos='.OrganoTipo::count().' nodos='.Organo::count());
    }

    private function seedTipos(): void
    {
        $tipos = [
            ['codigo' => 'gerencia', 'nombre' => 'Gerencia', 'orden' => 10],
            ['codigo' => 'direccion', 'nombre' => 'Dirección', 'orden' => 20],
            ['codigo' => 'direccion_regional', 'nombre' => 'Dirección regional', 'orden' => 25],
            ['codigo' => 'coordinacion_zonal', 'nombre' => 'Coordinación zonal', 'orden' => 30],
            ['codigo' => 'oficina_coordinacion', 'nombre' => 'Oficina de coordinación', 'orden' => 40],
            ['codigo' => 'unidad', 'nombre' => 'Unidad', 'orden' => 50],
            ['codigo' => 'centro', 'nombre' => 'Centro', 'orden' => 60],
            ['codigo' => 'departamento', 'nombre' => 'Departamento', 'orden' => 70],
            ['codigo' => 'servicio', 'nombre' => 'Servicio', 'orden' => 80],
            ['codigo' => 'seccion', 'nombre' => 'Sección', 'orden' => 90],
            ['codigo' => 'supervision', 'nombre' => 'Supervisión', 'orden' => 100],
            ['codigo' => 'area', 'nombre' => 'Área', 'orden' => 110],
            ['codigo' => 'asistencia_tecnica', 'nombre' => 'Asistencia técnica', 'orden' => 120],
            ['codigo' => 'comite', 'nombre' => 'Comité', 'orden' => 130],
        ];

        foreach ($tipos as $tipo) {
            $row = OrganoTipo::withTrashed()->updateOrCreate(
                ['codigo' => $tipo['codigo']],
                [
                    'nombre' => $tipo['nombre'],
                    'orden' => $tipo['orden'],
                    'activo' => true,
                    'deleted_at' => null,
                ]
            );
            $this->tipoIds[$tipo['codigo']] = (int) $row->id;
            app(\App\Application\Bioestadistica\Sync\CatalogSyncRegistry::class)
                ->rememberOrganoTipo($tipo['codigo']);
        }
    }

    private function seedHijosDirectosGerencia(Organo $gerencia): void
    {
        $hijos = [
            ['oficina_coordinacion', 'Oficina de Coordinación', 10],
            ['unidad', 'Unidad de Control Interno', 20],
            ['unidad', 'Unidad de Regulación Farmacéutica', 30],
            ['unidad', 'Unidad de Obstetricia', 40],
            ['direccion', 'Dirección Médica del Hospital Central', 50],
            ['direccion', 'Dirección de Apoyo y Servicios', 60],
            ['direccion', 'Dirección de Hospitales del Área Central', 70],
            ['direccion', 'Dirección de Hospitales del Área Interior', 80],
            ['direccion', 'Dirección de Medicina Preventiva y Programas de Salud', 90],
            ['direccion', 'Dirección de Gestión Médica', 100],
            ['direccion', 'Dirección de Enfermería', 110],
            ['direccion', 'Dirección del Hospital de Especialidades Quirúrgicas', 120],
        ];

        foreach ($hijos as [$tipo, $nombre, $orden]) {
            $this->node($tipo, $nombre, $gerencia, ['fuente_pagina' => 39, 'orden' => $orden]);
        }
    }

    private function seedUnidadObstetricia(Organo $gerencia): void
    {
        $unidad = $this->childOf($gerencia, 'Unidad de Obstetricia');
        $interior = $this->node('supervision', 'Supervisión de Obstetricia Área Interior', $unidad, [
            'fuente_pagina' => 40, 'orden' => 40,
        ]);

        foreach ([
            ['Supervisión de Obstetricia Hospital Central', 10],
            ['Supervisión de Obstetricia Área Central', 20],
            ['Supervisión de Obstetricia Clínicas Periféricas (Materno Infantiles)', 30],
        ] as [$nombre, $orden]) {
            $this->node('supervision', $nombre, $unidad, ['fuente_pagina' => 40, 'orden' => $orden]);
        }

        foreach (['A', 'B', 'C', 'D', 'E'] as $i => $zona) {
            $this->node('supervision', "Supervisión Área Interior Zona {$zona}", $interior, [
                'fuente_pagina' => 40, 'orden' => ($i + 1) * 10,
            ]);
        }

        $this->node('area', 'Área Docencia e Investigación', $unidad, [
            'fuente_pagina' => 40, 'orden' => 90, 'es_jerarquico' => false,
        ]);
    }

    private function seedDireccionMedicaHc(Organo $gerencia): void
    {
        $dir = $this->childOf($gerencia, 'Dirección Médica del Hospital Central');

        $this->node('oficina_coordinacion', 'Oficina de Coordinación', $dir, ['fuente_pagina' => 41, 'orden' => 5]);
        $this->node('asistencia_tecnica', 'Asistencia Técnica', $dir, ['fuente_pagina' => 41, 'orden' => 6]);
        $this->node('unidad', 'Unidad de Emergencias', $dir, ['fuente_pagina' => 41, 'orden' => 7]);
        $this->node('comite', 'Comité de Bioética', $dir, ['fuente_pagina' => 41, 'orden' => 8]);

        $deptos = [
            'Departamento de Pediatría' => [
                'Servicio de Pediatría',
                'Servicio Terapia Intensiva Niños',
                'Servicio de Neonatología',
                'Servicio de Cirugía Pediátrica',
                'Urgencias Pediátricas',
            ],
            'Departamento de Ginecología y Obstetricia' => [
                'Servicio de Perinatología',
                'Servicio de Obstetricia',
                'Servicio de Ginecología',
            ],
            'Departamento de Medicina Interna' => [
                'Servicio de Dermatología',
                'Servicio de Endocrinología',
                'Servicio de Clínica Médica I',
                'Servicio de Clínica Médica II',
                'Servicio de Reumatología',
                'Servicio de Nefrología',
                'Servicio de Neurología',
                'Servicio de Neumología',
                'Servicio de Psicología',
                'Servicio de Infectología',
                'Servicio de Gastroenterología y Endoscopía Digestiva',
                'Servicio de Alergología',
                'Servicio de Policlínica',
            ],
            'Departamento Servicios Quirúrgicos' => [
                'Servicio de Cirugía Vascular Periférica',
                'Servicio de Cirugía de Mínima Invasión',
                'Servicio de Quirófano Central',
                'Servicio de Cirugía Reconstructiva y Quemados',
                'Servicio de Cirugía General',
                'Servicio de Neurocirugía',
                'Servicio de Urología',
                'Servicio de Mastología',
                'Servicio de Traumatología',
                'Servicio de Anestesia y Reanimación',
                'Servicio de Oftalmología',
                'Servicio de Otorrinolaringología',
                'Servicio de Odontología',
            ],
            'Departamento de Medicina Crítica' => [
                'Servicio de Terapia Intensiva Adultos 1',
                'Servicio de Terapia Intensiva Adultos 2',
            ],
            'Departamento de Hematología' => ['Servicio de Hematología'],
            'Departamento de Oncología' => ['Servicio de Oncología'],
            'Departamento de Cardiología' => [
                'Servicio de Cardiología',
                'Servicio de Cardiología Intervencionista y Hemodinamia',
                'Servicio de Cardiocirugía',
                'Servicio de Unidad Coronaria de Adultos',
                'Servicio de Cardiología Diagnóstica No Invasiva',
            ],
        ];

        $ordenDepto = 10;
        foreach ($deptos as $deptoNombre => $servicios) {
            $depto = $this->node('departamento', $deptoNombre, $dir, [
                'fuente_pagina' => 41, 'orden' => $ordenDepto,
            ]);
            foreach ($servicios as $i => $servicio) {
                $this->node('servicio', $servicio, $depto, [
                    'fuente_pagina' => 41, 'orden' => ($i + 1) * 10,
                ]);
            }
            $ordenDepto += 10;
        }

        $nefrologia = $this->childOf(
            $this->childOf($dir, 'Departamento de Medicina Interna'),
            'Servicio de Nefrología'
        );
        foreach ([
            'Área Diálisis Peritoneal',
            'Área Hemodiálisis',
            'Área Trasplante Renal',
            'Área Nefrología Clínica',
        ] as $i => $area) {
            $this->node('area', $area, $nefrologia, [
                'fuente_pagina' => 41, 'orden' => ($i + 1) * 10, 'es_jerarquico' => false,
            ]);
        }

        $cardio = $this->childOf($dir, 'Departamento de Cardiología');
        foreach ([
            'Área Prevención y Rehabilitación Cardiovascular',
            'Área Electrofisiología y Ablación',
            'Área de Recuperación de Cirugía Cardíaca',
        ] as $i => $area) {
            $this->node('area', $area, $cardio, [
                'fuente_pagina' => 41, 'orden' => 200 + ($i * 10), 'es_jerarquico' => false,
            ]);
        }
    }

    private function seedDireccionApoyo(Organo $gerencia): void
    {
        $dir = $this->childOf($gerencia, 'Dirección de Apoyo y Servicios');
        $this->node('oficina_coordinacion', 'Oficina de Coordinación', $dir, ['fuente_pagina' => 42, 'orden' => 5]);
        $this->node('asistencia_tecnica', 'Asistencia Técnica', $dir, ['fuente_pagina' => 42, 'orden' => 6]);

        $lab = $this->node('departamento', 'Departamento Laboratorio de Análisis Clínicos', $dir, [
            'fuente_pagina' => 42, 'orden' => 10,
        ]);
        foreach ([
            'Servicio Laboratorio de Hematología',
            'Servicio Laboratorio de Urgencias',
            'Servicio Microbiología',
            'Servicio Laboratorio UTI',
            'Servicio Bioquímica Clínica',
        ] as $i => $nombre) {
            $this->node('servicio', $nombre, $lab, ['fuente_pagina' => 42, 'orden' => ($i + 1) * 10]);
        }

        $imagenes = $this->node('centro', 'Centro de Diagnóstico e Imágenes', $dir, [
            'fuente_pagina' => 42, 'orden' => 20,
        ]);
        foreach ([
            'Servicio de Terapia Endovascular',
            'Servicio de Tomografía',
            'Servicio de Anatomía Patológica',
            'Servicio de Radiología',
            'Servicio de Ecografía',
            'Servicio de Resonancia Magnética',
        ] as $i => $nombre) {
            $this->node('servicio', $nombre, $imagenes, ['fuente_pagina' => 42, 'orden' => ($i + 1) * 10]);
        }

        $this->node('unidad', 'Unidad de Nutrición', $dir, ['fuente_pagina' => 42, 'orden' => 30]);

        $farmacia = $this->node('departamento', 'Departamento de Farmacia', $dir, [
            'fuente_pagina' => 42, 'orden' => 40,
        ]);
        $this->node('seccion', 'Sección Administrativa', $farmacia, ['fuente_pagina' => 42, 'orden' => 10]);
        foreach (['Farmacia Externa', 'Farmacia de Urgencias', 'Farmacia Interna'] as $i => $area) {
            $this->node('area', $area, $farmacia, [
                'fuente_pagina' => 42, 'orden' => 20 + $i * 10, 'es_jerarquico' => false,
            ]);
        }

        $sangre = $this->node('centro', 'Centro Productor de Sangre y Terapia Celular', $dir, [
            'fuente_pagina' => 42, 'orden' => 50,
        ]);
        foreach ([
            'Área Promoción y Colecta de Sangre',
            'Área Calificación Biológica',
            'Área de Producción',
            'Área Garantía de Calidad',
            'Área Medicina Transfusional',
        ] as $i => $area) {
            $this->node('area', $area, $sangre, [
                'fuente_pagina' => 42, 'orden' => ($i + 1) * 10, 'es_jerarquico' => false,
            ]);
        }

        $adm = $this->node('departamento', 'Departamento Apoyo Administrativo', $dir, [
            'fuente_pagina' => 42, 'orden' => 60,
        ]);
        foreach ([
            'Sección Agendamiento e Informes',
            'Sección Documentación y Archivo',
            'Sección Admisión',
            'Sección del Centro de Atención Directa',
        ] as $i => $nombre) {
            $this->node('seccion', $nombre, $adm, ['fuente_pagina' => 42, 'orden' => ($i + 1) * 10]);
        }

        $log = $this->node('departamento', 'Departamento Apoyo Logístico', $dir, [
            'fuente_pagina' => 42, 'orden' => 70,
        ]);
        foreach ([
            'Sección Servicios Generales',
            'Sección Lavandería y Ropería',
            'Sección Limpieza y Recolección',
            'Sección Cocina',
        ] as $i => $nombre) {
            $this->node('seccion', $nombre, $log, ['fuente_pagina' => 42, 'orden' => ($i + 1) * 10]);
        }
    }

    private function seedMedicinaPreventiva(Organo $gerencia): void
    {
        $dir = $this->childOf($gerencia, 'Dirección de Medicina Preventiva y Programas de Salud');
        $this->node('oficina_coordinacion', 'Oficina de Coordinación', $dir, ['fuente_pagina' => 65, 'orden' => 5]);

        foreach ([
            'Departamento de Epidemiología',
            'Departamento de Servicio Social',
            'Departamento Parque de la Salud',
            'Departamento de Programas de Salud',
        ] as $i => $nombre) {
            $this->node('departamento', $nombre, $dir, ['fuente_pagina' => 65, 'orden' => ($i + 1) * 10]);
        }

        $social = $this->childOf($dir, 'Departamento de Servicio Social');
        $this->node('seccion', 'Sección Asistencia Social', $social, ['fuente_pagina' => 65, 'orden' => 10]);
        $this->node('seccion', 'Sección Servicio Social y Bienestar del Personal', $social, [
            'fuente_pagina' => 65, 'orden' => 20,
        ]);

        $this->node('centro', 'Hogar Taller', $dir, ['fuente_pagina' => 65, 'orden' => 50]);

        $cream = $this->node('centro', 'Centro Residencial Especializado de Atención y Apoyo para Adultos Mayores (CREAM)', $dir, [
            'fuente_pagina' => 65, 'orden' => 60,
        ]);
        $this->node('servicio', 'Servicio de Asistencia Sanitaria', $cream, ['fuente_pagina' => 65, 'orden' => 10]);
        $this->node('seccion', 'Sección Administrativa', $cream, ['fuente_pagina' => 65, 'orden' => 20]);
        $this->node('seccion', 'Sección de Servicios Generales y Mantenimiento', $cream, [
            'fuente_pagina' => 65, 'orden' => 30,
        ]);
        foreach ([
            'Área de Apoyo Sanitario',
            'Área de Desarrollo Psicosocial',
            'Área de Gestión de Personas',
            'Área de Apoyo Logístico',
            'Área Parque Urbano',
        ] as $i => $area) {
            $this->node('area', $area, $cream, [
                'fuente_pagina' => 65, 'orden' => 40 + $i * 10, 'es_jerarquico' => false,
            ]);
        }
    }

    private function seedGestionMedica(Organo $gerencia): void
    {
        $dir = $this->childOf($gerencia, 'Dirección de Gestión Médica');
        $this->node('oficina_coordinacion', 'Oficina de Coordinación', $dir, ['fuente_pagina' => 66, 'orden' => 5]);

        foreach ([
            'Departamento de Administración de Convenios de Salud',
            'Departamento de Prestaciones Externas',
            'Departamento de Fiscalización de Gestión Médica',
            'Departamento de Educación Médica, Docencia e Investigación',
        ] as $i => $nombre) {
            $this->node('departamento', $nombre, $dir, ['fuente_pagina' => 66, 'orden' => ($i + 1) * 10]);
        }

        $centro = $this->node('centro', 'Centro de Regulación Médica y Gestión de Ambulancias', $dir, [
            'fuente_pagina' => 66, 'orden' => 50,
        ]);
        $this->node('servicio', 'Servicio de Coordinación Médica', $centro, ['fuente_pagina' => 66, 'orden' => 10]);
        $this->node('seccion', 'Sección Administrativa y Logística', $centro, ['fuente_pagina' => 66, 'orden' => 20]);
        foreach ([
            'Área Regulación Médica',
            'Área de Coordinación Operativa',
            'Área de Coordinación de Docencia, Estadística y Productividad',
            'Área Apoyo Administrativo',
            'Área Logística',
        ] as $i => $area) {
            $this->node('area', $area, $centro, [
                'fuente_pagina' => 66, 'orden' => 30 + $i * 10, 'es_jerarquico' => false,
            ]);
        }
    }

    private function seedEnfermeria(Organo $gerencia): void
    {
        $dir = $this->childOf($gerencia, 'Dirección de Enfermería');
        $this->node('oficina_coordinacion', 'Oficina de Coordinación', $dir, ['fuente_pagina' => 67, 'orden' => 5]);
        $this->node('area', 'Área de Docencia', $dir, [
            'fuente_pagina' => 67, 'orden' => 6, 'es_jerarquico' => false,
        ]);

        $hc = $this->node('departamento', 'Departamento de Enfermería Hospital Central', $dir, [
            'fuente_pagina' => 67, 'orden' => 10,
        ]);
        foreach ([
            'Supervisión Servicios Quirúrgicos',
            'Supervisión Servicios Medicina Interna',
            'Supervisión Servicios de Especialidades',
            'Supervisión Servicio de Gineco-Obstetricia',
            'Supervisión Medicina Crítica',
            'Supervisión Servicio de Apoyo y Consulta Externa',
            'Supervisión Servicio de Pediatría',
        ] as $i => $nombre) {
            $this->node('supervision', $nombre, $hc, ['fuente_pagina' => 67, 'orden' => ($i + 1) * 10]);
        }

        $central = $this->node('departamento', 'Departamento de Enfermería Área Central', $dir, [
            'fuente_pagina' => 67, 'orden' => 20,
        ]);
        $this->node('servicio', 'Servicio de Enfermería Hospital Luque', $central, ['fuente_pagina' => 67, 'orden' => 10]);
        $this->node('servicio', 'Servicio de Enfermería Hospital Geriátrico', $central, ['fuente_pagina' => 67, 'orden' => 20]);
        foreach ([
            'Supervisión de Clínicas Periféricas — Área Central',
            'Supervisión de Centros Especializados',
            'Supervisión de Unidades y Puestos Sanitarios — Dpto. Central',
        ] as $i => $nombre) {
            $this->node('supervision', $nombre, $central, ['fuente_pagina' => 67, 'orden' => 30 + $i * 10]);
        }

        $interior = $this->node('departamento', 'Departamento de Enfermería Área Interior', $dir, [
            'fuente_pagina' => 67, 'orden' => 30,
        ]);
        foreach (['A', 'B', 'C', 'D', 'E'] as $i => $zona) {
            $this->node('supervision', "Supervisión Zona {$zona}", $interior, [
                'fuente_pagina' => 67, 'orden' => ($i + 1) * 10,
            ]);
        }

        $heq = $this->node('departamento', 'Departamento de Enfermería Hospital de Especialidades Quirúrgicas', $dir, [
            'fuente_pagina' => 67, 'orden' => 40,
        ]);
        foreach ([
            'Supervisión de Servicios Quirúrgicos',
            'Supervisión de Medicina Crítica',
            'Supervisión de Apoyo y Consultas Externas',
            'Supervisión de Servicios de Especialidades',
            'Supervisión de Clínicas Periféricas — Capital',
        ] as $i => $nombre) {
            $this->node('supervision', $nombre, $heq, ['fuente_pagina' => 67, 'orden' => ($i + 1) * 10]);
        }
    }

    private function seedHeq(Organo $gerencia): void
    {
        $dir = $this->childOf($gerencia, 'Dirección del Hospital de Especialidades Quirúrgicas');
        $this->node('oficina_coordinacion', 'Oficina de Coordinación', $dir, ['fuente_pagina' => 68, 'orden' => 5]);

        $medico = $this->node('departamento', 'Departamento Médico', $dir, ['fuente_pagina' => 68, 'orden' => 10]);
        $policlinico = $this->node('departamento', 'Departamento Policlínico', $dir, ['fuente_pagina' => 68, 'orden' => 20]);
        $apoyo = $this->node('departamento', 'Departamento de Apoyo Médico', $dir, ['fuente_pagina' => 68, 'orden' => 30]);
        $adm = $this->node('departamento', 'Departamento de Administración y Logística', $dir, [
            'fuente_pagina' => 68, 'orden' => 40,
        ]);

        foreach ([
            'Servicio de Traumatología',
            'Servicio de Urología',
            'Servicio de Cirugía General',
            'Servicio de Neurocirugía',
            'Servicio de Mastología',
            'Servicio de Terapia Intensiva',
            'Servicio de Anestesia y Reanimación',
            'Servicio de Quirófano',
        ] as $i => $nombre) {
            $this->node('servicio', $nombre, $medico, ['fuente_pagina' => 68, 'orden' => ($i + 1) * 10]);
        }

        $this->node('area', 'Área Urgencias', $policlinico, [
            'fuente_pagina' => 68, 'orden' => 10, 'es_jerarquico' => false,
        ]);
        $this->node('area', 'Área Consultas Ambulatorias', $policlinico, [
            'fuente_pagina' => 68, 'orden' => 20, 'es_jerarquico' => false,
        ]);

        foreach ([
            'Servicio de Imágenes',
            'Servicio de Laboratorio',
            'Servicio de Farmacia',
            'Servicio de Nutrición y Cocina',
            'Servicio de Anatomía Patológica',
            'Servicio de Medicina Transfusional',
            'Servicio de Fisioterapia',
        ] as $i => $nombre) {
            $this->node('servicio', $nombre, $apoyo, ['fuente_pagina' => 68, 'orden' => ($i + 1) * 10]);
        }

        $farmacia = $this->childOf($apoyo, 'Servicio de Farmacia');
        $this->node('area', 'Área Farmacia Ambulatoria', $farmacia, [
            'fuente_pagina' => 68, 'orden' => 10, 'es_jerarquico' => false,
        ]);
        $this->node('area', 'Área Farmacia Internados', $farmacia, [
            'fuente_pagina' => 68, 'orden' => 20, 'es_jerarquico' => false,
        ]);
        $this->node('area', 'Área Esterilización', $apoyo, [
            'fuente_pagina' => 68, 'orden' => 100, 'es_jerarquico' => false,
        ]);
        $this->node('area', 'Área Banco de Tejidos', $apoyo, [
            'fuente_pagina' => 68, 'orden' => 110, 'es_jerarquico' => false,
        ]);

        foreach ([
            'Sección Administrativa',
            'Sección Servicios Generales',
            'Sección Gestión de Pacientes',
        ] as $i => $nombre) {
            $this->node('seccion', $nombre, $adm, ['fuente_pagina' => 68, 'orden' => ($i + 1) * 10]);
        }
    }

    private function seedHospitalesAreaCentral(Organo $gerencia): void
    {
        $dir = $this->childOf($gerencia, 'Dirección de Hospitales del Área Central');

        $this->node('oficina_coordinacion', 'Oficina de Coordinación', $dir, ['fuente_pagina' => 43, 'orden' => 5]);
        $this->node('departamento', 'Departamento Apoyo Asistencial', $dir, ['fuente_pagina' => 43, 'orden' => 10]);
        $adm = $this->node('departamento', 'Departamento Administrativo', $dir, [
            'fuente_pagina' => 43, 'orden' => 20,
        ]);
        foreach ([
            'Sección Administración de Personal',
            'Sección Servicios Generales',
            'Sección Apoyo Logístico',
        ] as $i => $nombre) {
            $this->node('seccion', $nombre, $adm, ['fuente_pagina' => 43, 'orden' => ($i + 1) * 10]);
        }

        $locales = [
            'Dirección Clínica Periférica Boquerón',
            'Dirección Clínica Periférica Nanawa',
            'Dirección Clínica Periférica Isla Poí',
            'Dirección Clínica Periférica Campo Vía de Capiatá',
            'Dirección Clínica Periférica Yrendague',
            'Dirección Unidad Sanitaria Ypacaraí',
            'Dirección Unidad Sanitaria Villeta',
            'Dirección Unidad Sanitaria San Antonio',
            'Dirección Puesto Sanitario Piquete Cué',
            'Dirección Puesto Sanitario Itauguá',
            'Dirección Puesto Sanitario Guarambaré',
            'Dirección Hospital 12 de Junio',
            'Dirección Hospital Geriátrico Dr. Gerardo Buongermini',
            'Dirección Hospital de Luque',
            'Dirección Centro Odontológico',
            'Dirección Centro de Salud Mental',
            'Dirección Centro de Medicina Física y Rehabilitación',
            'Dirección UBAS Itá',
        ];

        foreach ($locales as $i => $nombre) {
            $local = $this->node('direccion', $nombre, $dir, [
                'fuente_pagina' => 44, 'orden' => 100 + $i * 10,
            ]);

            if (str_starts_with($nombre, 'Dirección Clínica Periférica')) {
                $this->attachEstructuraClinicaPeriferica($local);
            } elseif (str_starts_with($nombre, 'Dirección Unidad Sanitaria')) {
                $this->attachEstructuraUs($local, 46);
            } elseif (str_starts_with($nombre, 'Dirección Puesto Sanitario')) {
                $this->attachEstructuraPs($local, 47);
            } elseif (in_array($nombre, ['Dirección Hospital de Luque', 'Dirección Hospital 12 de Junio'], true)) {
                $this->attachEstructuraHospitalLuqueBase($local);
            } elseif ($nombre === 'Dirección Hospital Geriátrico Dr. Gerardo Buongermini') {
                $this->attachEstructuraGeriatrico($local);
            } elseif ($nombre === 'Dirección Centro Odontológico') {
                $this->attachEstructuraCentroOdontologico($local);
            } elseif ($nombre === 'Dirección Centro de Salud Mental') {
                $this->attachEstructuraSaludMental($local);
            } elseif ($nombre === 'Dirección Centro de Medicina Física y Rehabilitación') {
                $this->attachEstructuraMedFisica($local);
            } elseif ($nombre === 'Dirección UBAS Itá') {
                $this->attachEstructuraUbas($local);
            }
        }
    }

    private function seedHospitalesAreaInterior(Organo $gerencia): void
    {
        $dir = $this->childOf($gerencia, 'Dirección de Hospitales del Área Interior');

        $this->node('oficina_coordinacion', 'Oficina de Coordinación', $dir, ['fuente_pagina' => 54, 'orden' => 5]);
        $this->node('departamento', 'Departamento Apoyo Asistencial', $dir, ['fuente_pagina' => 54, 'orden' => 10]);
        $adm = $this->node('departamento', 'Departamento Administrativo', $dir, [
            'fuente_pagina' => 54, 'orden' => 20,
        ]);
        foreach ([
            'Sección Administración de Personal',
            'Sección Servicios Generales',
            'Sección Administración de Contratos y Convenios',
        ] as $i => $nombre) {
            $this->node('seccion', $nombre, $adm, ['fuente_pagina' => 54, 'orden' => ($i + 1) * 10]);
        }

        $zonas = [
            ['coordinacion_zonal', 'Coordinación Zonal 1ª — Concepción / Alto Paraguay', [
                'Dirección Hospital Regional Concepción',
                'Dirección Unidad Sanitaria Vallemí',
                'Dirección Unidad Sanitaria Horqueta',
                'Dirección Unidad Sanitaria Bahía Negra',
                'Dirección Unidad Sanitaria Puerto Casado',
                'Dirección Puesto Sanitario Puerto Pinasco',
            ]],
            ['coordinacion_zonal', 'Coordinación Zonal 2ª — San Pedro', [
                'Dirección Hospital Regional San Pedro del Ycuamandyyú',
                'Dirección Unidad Sanitaria Puerto Rosario',
                'Dirección Unidad Sanitaria San Estanislao',
                'Dirección Puesto Sanitario Itacurubí del Rosario',
                'Dirección Puesto Sanitario Puerto Antequera',
                'Dirección Puesto Sanitario Capiibary',
                'Dirección Puesto Sanitario Choré',
            ]],
            ['direccion_regional', 'Dirección Regional 3ª — Cordillera', [
                'Dirección Unidad Sanitaria Caacupé',
                'Dirección Unidad Sanitaria Eusebio Ayala',
                'Dirección Puesto Sanitario San Bernardino',
                'Dirección Puesto Sanitario Caraguatay',
                'Dirección Puesto Sanitario Tobatí',
                'Dirección Puesto Sanitario Arroyos y Esteros',
            ]],
            ['direccion_regional', 'Dirección Regional 4ª — Guairá', [
                'Dirección Hospital Regional Villarrica',
                'Dirección Unidad Sanitaria Colonia Independencia',
                'Dirección Unidad Sanitaria Iturbe',
                'Dirección Unidad Sanitaria Tebicuary',
                'Dirección Puesto Sanitario Paso Yobay',
                'Dirección Puesto Sanitario José Fassardi',
            ]],
            ['direccion_regional', 'Dirección Regional 5ª — Caaguazú', [
                'Dirección Hospital Regional Coronel Oviedo',
                'Dirección Unidad Sanitaria Caaguazú',
                'Dirección Puesto Sanitario Yhú',
                'Dirección Puesto Sanitario San José de los Arroyos',
                'Dirección Puesto Sanitario Dr. Juan Manuel Frutos',
            ]],
            ['direccion_regional', 'Dirección Regional 6ª — Caazapá', [
                'Dirección Unidad Sanitaria Caazapá',
                'Dirección Puesto Sanitario San Juan Nepomuceno',
                'Dirección Puesto Sanitario Yegros',
                'Dirección Puesto Sanitario Yuty',
            ]],
            ['coordinacion_zonal', 'Coordinación Zonal 7ª — Itapúa', [
                'Dirección Hospital Regional Encarnación',
                'Dirección Hospital Regional Ayolas',
                'Dirección Unidad Sanitaria Hohenau',
                'Dirección Unidad Sanitaria Carmen del Paraná',
                'Dirección Unidad Sanitaria Fram',
                'Dirección Unidad Sanitaria Natalio',
                'Dirección Puesto Sanitario Coronel Bogado',
                'Dirección Puesto Sanitario Carlos Antonio López',
                'Dirección Puesto Sanitario Edelira Km 28',
                'Dirección Puesto Sanitario María Auxiliadora',
                'Dirección Puesto Sanitario Mayor Otaño',
            ]],
            ['coordinacion_zonal', 'Coordinación Zonal 8ª — Misiones', [
                'Dirección Unidad Sanitaria San Ignacio',
                'Dirección Unidad Sanitaria San Juan Bautista de las Misiones',
                'Dirección Puesto Sanitario Santa María de Fe',
                'Dirección Puesto Sanitario Villa Florida',
                'Dirección Puesto Sanitario Santa Rosa',
                'Dirección Puesto Sanitario Yabebyry',
                'Dirección Puesto Sanitario Santiago',
            ]],
            ['direccion_regional', 'Dirección Regional 9ª — Paraguarí', [
                'Dirección Unidad Sanitaria Paraguarí',
                'Dirección Puesto Sanitario Carapeguá',
                'Dirección Puesto Sanitario Caapucú',
                'Dirección Puesto Sanitario La Colmena',
                'Dirección Puesto Sanitario Quiindy',
                'Dirección Puesto Sanitario Ybycuí',
                'Dirección Puesto Sanitario Quyquyhó',
                'Dirección Puesto Sanitario Mbuyapey',
            ]],
            ['direccion_regional', 'Dirección Regional 10ª — Alto Paraná', [
                'Dirección Hospital Regional Ciudad del Este',
                'Dirección Unidad Sanitaria Hernandarias Dr. Ramón Genaro Agüero Sosa',
                'Dirección Unidad Sanitaria Puerto Presidente Franco',
                'Dirección Puesto Sanitario Itakyry',
                'Dirección Puesto Sanitario Santa Rita',
                'Dirección Puesto Sanitario Minga Guazú',
            ]],
            ['direccion_regional', 'Dirección Regional 12ª — Ñeembucú', [
                'Dirección Hospital Regional Pilar',
                'Dirección Puesto Sanitario Alberdi',
            ]],
            ['direccion_regional', 'Dirección Regional 13ª — Amambay', [
                'Dirección Hospital Regional Pedro Juan Caballero',
                'Dirección Unidad Sanitaria Capitán Bado',
                'Dirección Puesto Sanitario Bella Vista Norte',
            ]],
            ['direccion_regional', 'Dirección Regional 14ª — Canindeyú', [
                'Dirección Unidad Sanitaria San Isidro del Curuguaty',
                'Dirección Unidad Sanitaria Puente Kyjha',
                'Dirección Puesto Sanitario Salto del Guairá',
                'Dirección Puesto Sanitario La Paloma',
            ]],
            ['direccion_regional', 'Dirección Regional 15ª — Presidente Hayes / Boquerón', [
                'Dirección Hospital Regional Benjamín Aceval',
                'Dirección Puesto Sanitario Villa Hayes',
            ]],
        ];

        foreach ($zonas as $zi => [$tipoZona, $nombreZona, $locales]) {
            $zona = $this->node($tipoZona, $nombreZona, $dir, [
                'fuente_pagina' => 55, 'orden' => ($zi + 1) * 10,
            ]);
            foreach ($locales as $li => $nombreLocal) {
                $local = $this->node('direccion', $nombreLocal, $zona, [
                    'fuente_pagina' => 56, 'orden' => ($li + 1) * 10,
                ]);
                if ($nombreLocal === 'Dirección Hospital Regional Ciudad del Este') {
                    $this->attachEstructuraCde($local);
                } elseif (str_contains($nombreLocal, 'Hospital Regional')) {
                    $this->attachEstructuraHospitalRegionalTipo($local);
                } elseif (str_contains($nombreLocal, 'Unidad Sanitaria')) {
                    $this->attachEstructuraUs($local, 63);
                } elseif (str_contains($nombreLocal, 'Puesto Sanitario')) {
                    $this->attachEstructuraPs($local, 64);
                }
            }
        }
    }

    private function attachEstructuraClinicaPeriferica(Organo $dir): void
    {
        $medica = $this->node('area', 'Área Médica', $dir, [
            'fuente_pagina' => 45, 'orden' => 10, 'es_jerarquico' => false,
        ]);
        $diag = $this->node('area', 'Área de Diagnóstico', $dir, [
            'fuente_pagina' => 45, 'orden' => 20, 'es_jerarquico' => false,
        ]);
        $adm = $this->node('area', 'Área Apoyo Administrativo', $dir, [
            'fuente_pagina' => 45, 'orden' => 30, 'es_jerarquico' => false,
        ]);

        $this->node('servicio', 'Consulta Ambulatoria Médico Odontológica', $medica, ['fuente_pagina' => 45, 'orden' => 10]);
        $this->node('servicio', 'Urgencias y Enfermería', $medica, ['fuente_pagina' => 45, 'orden' => 20]);
        foreach (['Imágenes', 'Colposcopia y PAP', 'Laboratorio', 'Electrocardiograma (E.C.G.)'] as $i => $nombre) {
            $this->node('servicio', $nombre, $diag, ['fuente_pagina' => 45, 'orden' => ($i + 1) * 10]);
        }
        foreach ([
            'Farmacia',
            'Admisión y Documentación Clínica',
            'Nutrición',
            'Servicios Generales',
            'Administración del Personal',
        ] as $i => $nombre) {
            $this->node('seccion', $nombre, $adm, ['fuente_pagina' => 45, 'orden' => ($i + 1) * 10]);
        }
    }

    private function attachEstructuraUs(Organo $dir, int $pagina): void
    {
        $medica = $this->node('area', 'Área Médica', $dir, [
            'fuente_pagina' => $pagina, 'orden' => 10, 'es_jerarquico' => false,
        ]);
        $apoyo = $this->node('area', 'Área de Apoyo', $dir, [
            'fuente_pagina' => $pagina, 'orden' => 20, 'es_jerarquico' => false,
        ]);
        $this->node('area', 'Área Apoyo Administrativo', $dir, [
            'fuente_pagina' => $pagina, 'orden' => 30, 'es_jerarquico' => false,
        ]);
        $this->node('servicio', 'Consultorio', $medica, ['fuente_pagina' => $pagina, 'orden' => 10]);
        $this->node('servicio', 'Enfermería', $medica, ['fuente_pagina' => $pagina, 'orden' => 20]);
        $this->node('servicio', 'Farmacia', $apoyo, ['fuente_pagina' => $pagina, 'orden' => 10]);
        $this->node('servicio', 'Laboratorio', $apoyo, ['fuente_pagina' => $pagina, 'orden' => 20]);
    }

    private function attachEstructuraPs(Organo $dir, int $pagina): void
    {
        foreach ([
            'Área Médica',
            'Área de Farmacia',
            'Área de Enfermería',
            'Área Apoyo Administrativo',
        ] as $i => $nombre) {
            $this->node('area', $nombre, $dir, [
                'fuente_pagina' => $pagina, 'orden' => ($i + 1) * 10, 'es_jerarquico' => false,
            ]);
        }
    }

    private function attachEstructuraHospitalLuqueBase(Organo $dir): void
    {
        $asist = $this->node('departamento', 'Departamento Médico Asistencial', $dir, [
            'fuente_pagina' => 53, 'orden' => 10,
        ]);
        $apoyo = $this->node('departamento', 'Departamento de Apoyo Médico', $dir, [
            'fuente_pagina' => 53, 'orden' => 20,
        ]);
        $adm = $this->node('departamento', 'Departamento Administrativo', $dir, [
            'fuente_pagina' => 53, 'orden' => 30,
        ]);

        foreach ([
            'Servicio de Hospitalización y Urgencia',
            'Servicio de Atención Ambulatoria Médico-Odontológico',
            'Servicio de Cirugía',
        ] as $i => $nombre) {
            $this->node('servicio', $nombre, $asist, ['fuente_pagina' => 53, 'orden' => ($i + 1) * 10]);
        }
        foreach ([
            'Servicio de Farmacia',
            'Servicio de Estudios de Diagnóstico e Imágenes',
            'Servicio de Laboratorio',
        ] as $i => $nombre) {
            $this->node('servicio', $nombre, $apoyo, ['fuente_pagina' => 53, 'orden' => ($i + 1) * 10]);
        }
        foreach ([
            'Sección Administración del Personal',
            'Sección Servicios Generales',
            'Sección Admisión Documentación e Informes',
        ] as $i => $nombre) {
            $this->node('seccion', $nombre, $adm, ['fuente_pagina' => 53, 'orden' => ($i + 1) * 10]);
        }
    }

    private function attachEstructuraGeriatrico(Organo $dir): void
    {
        $this->node('asistencia_tecnica', 'Asistencia Técnica', $dir, ['fuente_pagina' => 52, 'orden' => 5]);
        $medico = $this->node('departamento', 'Departamento Médico Geriátrico', $dir, [
            'fuente_pagina' => 52, 'orden' => 10,
        ]);
        $apoyo = $this->node('departamento', 'Departamento de Apoyo Médico', $dir, [
            'fuente_pagina' => 52, 'orden' => 20,
        ]);
        $adm = $this->node('departamento', 'Departamento de Apoyo Administrativo', $dir, [
            'fuente_pagina' => 52, 'orden' => 30,
        ]);
        foreach ([
            'Servicio de Hospitalización y Urgencias',
            'Servicio de Atención Ambulatoria',
            'Servicio de Cuidados Intensivos',
        ] as $i => $nombre) {
            $this->node('servicio', $nombre, $medico, ['fuente_pagina' => 52, 'orden' => ($i + 1) * 10]);
        }
        foreach ([
            'Servicio de Farmacia',
            'Servicio de Estudios de Diagnóstico e Imágenes',
            'Servicio de Nutrición',
        ] as $i => $nombre) {
            $this->node('servicio', $nombre, $apoyo, ['fuente_pagina' => 52, 'orden' => ($i + 1) * 10]);
        }
        foreach ([
            'Sección Administración del Personal',
            'Sección Servicios Generales',
            'Sección Admisión Documentación e Informes',
        ] as $i => $nombre) {
            $this->node('seccion', $nombre, $adm, ['fuente_pagina' => 52, 'orden' => ($i + 1) * 10]);
        }
    }

    private function attachEstructuraCentroOdontologico(Organo $dir): void
    {
        $this->node('servicio', 'Servicio Odontológico', $dir, ['fuente_pagina' => 50, 'orden' => 10]);
        $this->node('servicio', 'Servicio de Apoyo y Suministros Odontológicos', $dir, [
            'fuente_pagina' => 50, 'orden' => 20,
        ]);
        $this->node('supervision', 'Supervisión de Servicios Odontológicos', $dir, [
            'fuente_pagina' => 50, 'orden' => 30,
        ]);
        $this->node('seccion', 'Sección Apoyo Administrativo', $dir, ['fuente_pagina' => 50, 'orden' => 40]);
        foreach ([
            'Área de Farmacia',
            'Área de Admisión, Agendamiento e Informes',
            'Área de Atención Odontológica',
            'Área de Enfermería',
            'Área de Cirugía Odontológica',
            'Área de Imágenes',
            'Área de Mantenimiento de Equipos y Logística',
            'Área de Recursos Humanos',
        ] as $i => $area) {
            $this->node('area', $area, $dir, [
                'fuente_pagina' => 51, 'orden' => 50 + $i * 10, 'es_jerarquico' => false,
            ]);
        }
    }

    private function attachEstructuraSaludMental(Organo $dir): void
    {
        $this->node('servicio', 'Servicio Asistencial', $dir, ['fuente_pagina' => 49, 'orden' => 10]);
        $this->node('servicio', 'Servicio de Farmacia', $dir, ['fuente_pagina' => 49, 'orden' => 20]);
        $this->node('seccion', 'Sección de Apoyo Administrativo', $dir, ['fuente_pagina' => 49, 'orden' => 30]);
    }

    private function attachEstructuraMedFisica(Organo $dir): void
    {
        $this->node('servicio', 'Servicio de Medicina Física y Rehabilitación', $dir, [
            'fuente_pagina' => 48, 'orden' => 10,
        ]);
        $this->node('servicio', 'Servicio de Rehabilitación del Hospital Central', $dir, [
            'fuente_pagina' => 48, 'orden' => 20,
        ]);
        $this->node('supervision', 'Supervisión de Medicina Física y Rehabilitación Área Central e Interior', $dir, [
            'fuente_pagina' => 48, 'orden' => 30,
        ]);
        $this->node('seccion', 'Sección de Apoyo Administrativo', $dir, ['fuente_pagina' => 48, 'orden' => 40]);
    }

    private function attachEstructuraUbas(Organo $dir): void
    {
        foreach ([
            'Área Médica',
            'Área de Farmacia',
            'Área de Enfermería',
            'Área Apoyo Administrativo',
        ] as $i => $nombre) {
            $this->node('area', $nombre, $dir, [
                'fuente_pagina' => 51, 'orden' => ($i + 1) * 10, 'es_jerarquico' => false,
            ]);
        }
    }

    private function attachEstructuraCde(Organo $dir): void
    {
        $medico = $this->node('departamento', 'Departamento Médico', $dir, ['fuente_pagina' => 61, 'orden' => 10]);
        $apoyo = $this->node('departamento', 'Departamento de Apoyo Médico', $dir, [
            'fuente_pagina' => 61, 'orden' => 20,
        ]);
        $log = $this->node('departamento', 'Departamento de Logística y Administración', $dir, [
            'fuente_pagina' => 61, 'orden' => 30,
        ]);

        foreach ([
            'Área Consultorios Externos', 'Área Hospital Día', 'Área Cuidados Mínimos',
            'Área Cuidados Intermedios y Críticos', 'Área Cirugía', 'Área Gineco-Obstetricia',
            'Área Pediatría', 'Área Emergencias Pediátricas', 'Área Emergencias Adultos',
        ] as $i => $area) {
            $this->node('area', $area, $medico, [
                'fuente_pagina' => 61, 'orden' => ($i + 1) * 10, 'es_jerarquico' => false,
            ]);
        }
        foreach ([
            'Área Imágenes', 'Área Laboratorio', 'Área Farmacia',
            'Área Nutrición', 'Área Fisioterapia', 'Área Esterilización',
        ] as $i => $area) {
            $this->node('area', $area, $apoyo, [
                'fuente_pagina' => 61, 'orden' => ($i + 1) * 10, 'es_jerarquico' => false,
            ]);
        }
        foreach ([
            'Gestión de Pacientes', 'Comunicación Social', 'Área Lavandería', 'Área Limpieza',
            'Área Cocina', 'Área Infraestructura Física', 'Área Equipos Biomédicos', 'Informática',
            'Área Jardinería', 'Área Administración', 'Área Residuos Hospitalarios', 'Área Talento Humano',
            'Área Admisión y Egresos', 'Área Coordinación Extrahospitalaria', 'Área Agendamiento',
            'Área Atención al Usuario', 'Área Archivo y Documentación Clínica',
            'Área Oficina de la Información para la Gestión', 'Área Servicio Social',
        ] as $i => $area) {
            $this->node('area', $area, $log, [
                'fuente_pagina' => 61, 'orden' => ($i + 1) * 10, 'es_jerarquico' => false,
            ]);
        }
    }

    private function attachEstructuraHospitalRegionalTipo(Organo $dir): void
    {
        $this->node('asistencia_tecnica', 'Asistencia Técnica', $dir, ['fuente_pagina' => 62, 'orden' => 5]);
        $apoyo = $this->node('departamento', 'Servicios de Apoyo y Diagnóstico', $dir, [
            'fuente_pagina' => 62, 'orden' => 10,
        ]);
        $medico = $this->node('departamento', 'Servicios Médicos Odontológicos', $dir, [
            'fuente_pagina' => 62, 'orden' => 20,
        ]);
        $adm = $this->node('seccion', 'Sección Apoyo Administrativo', $dir, [
            'fuente_pagina' => 62, 'orden' => 30,
        ]);

        foreach ([
            'Consulta Ambulatoria Médico Odontológica',
            'Urgencias y Enfermería',
            'Internados',
        ] as $i => $nombre) {
            $this->node('servicio', $nombre, $medico, ['fuente_pagina' => 62, 'orden' => ($i + 1) * 10]);
        }
        foreach (['Imágenes', 'Fisioterapia', 'Laboratorio', 'Electrocardiograma (E.C.G.)', 'Farmacia'] as $i => $nombre) {
            $this->node('servicio', $nombre, $apoyo, ['fuente_pagina' => 62, 'orden' => ($i + 1) * 10]);
        }
        foreach ([
            'Admisión y Documentación Clínica',
            'Nutrición',
            'Servicios Generales',
            'Administración del Personal',
        ] as $i => $nombre) {
            $this->node('seccion', $nombre, $adm, ['fuente_pagina' => 62, 'orden' => ($i + 1) * 10]);
        }
    }

    private function childOf(Organo $parent, string $nombre): Organo
    {
        $child = Organo::query()
            ->where('parent_id', $parent->id)
            ->where('nombre', $nombre)
            ->first();

        if (! $child) {
            throw new \RuntimeException("No se encontró órgano hijo «{$nombre}» bajo «{$parent->nombre}».");
        }

        return $child;
    }

    /**
     * @param  array{fuente_pagina?: int, orden?: int, es_jerarquico?: bool, notas?: string, codigo?: string}  $attrs
     */
    private function node(string $tipoCodigo, string $nombre, ?Organo $parent, array $attrs = []): Organo
    {
        $tipoId = $this->tipoIds[$tipoCodigo]
            ?? throw new \InvalidArgumentException("Tipo de órgano desconocido: {$tipoCodigo}");

        $payload = [
            'tipo_id' => $tipoId,
            'parent_id' => $parent?->id,
            'nombre' => $nombre,
            'codigo' => $attrs['codigo'] ?? null,
            'orden' => $attrs['orden'] ?? 0,
            'es_jerarquico' => $attrs['es_jerarquico'] ?? true,
            'activo' => true,
            'fuente_pagina' => $attrs['fuente_pagina'] ?? null,
            'notas' => $attrs['notas'] ?? null,
            'deleted_at' => null,
        ];

        if ($parent) {
            $organo = Organo::withTrashed()->updateOrCreate(
                ['parent_id' => $parent->id, 'nombre' => $nombre],
                $payload
            );
        } else {
            $existing = Organo::withTrashed()->whereNull('parent_id')->where('nombre', $nombre)->first();
            if ($existing) {
                $existing->fill($payload)->save();
                $organo = $existing->fresh();
            } else {
                $organo = Organo::query()->create($payload);
            }
        }

        app(\App\Application\Bioestadistica\Sync\CatalogSyncRegistry::class)
            ->rememberOrgano((int) $organo->id);

        return $organo;
    }
}
