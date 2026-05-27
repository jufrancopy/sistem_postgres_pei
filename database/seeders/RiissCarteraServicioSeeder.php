<?php

namespace Database\Seeders;

use App\Models\Riiss\CarteraServicio;
use Illuminate\Database\Seeder;

class RiissCarteraServicioSeeder extends Seeder
{
    public function run(): void
    {
        // Formato: [servicio, grupo_servicio, nivel, grado, tipo_est, especialidad_1, especialidad_2,
        //           requerido, descripcion, PS, US, CP, HB, HM, HA]
        $servicios = [
            // ── PROMOCIÓN Y PREVENCIÓN ──────────────────────────────────────────
            ['Charlas preventivas','Promoción y Prevención',1,1,'Extra muro','MEDICINA PREVENTIVA',null,true,'Charlas preventivas',1,1,1,1,1,1],
            ['Educación Integral en Salud','Promoción y Prevención',1,1,'Extra muro','MEDICINA PREVENTIVA',null,true,'Educación Integral en Salud',1,1,1,1,1,1],
            ['Inmunizaciones (PAI completo)','Inmunizaciones',1,1,'Extra muro','MEDICINA PREVENTIVA',null,true,'Esquema PAI completo según edad',1,1,1,1,1,1],
            ['Salud en el embarazo - Educación','Salud en el embarazo',1,1,'Extra muro','OBSTETRICIA',null,true,'Educación, comunicación, Consejería integral',1,1,1,1,1,1],
            ['Programa Lactancia Materna','Lactancia Materna',1,1,'Extra muro','OBSTETRICIA',null,true,'Programa de Lactancia Materna',1,1,1,1,1,1],
            ['Atención al niño y la madre','Atención niño y madre',1,1,'Extra muro','PEDIATRIA',null,true,'Consejería integral crianza positiva',1,1,1,1,1,1],
            ['Prevención Violencia contra la Mujer','Violencia de género',1,1,'Extra muro','MEDICINA PREVENTIVA',null,false,'Prevención de la violencia',1,1,1,1,1,1],
            ['Salud Sexual Reproductiva - Consejería','Salud Sexual Reproductiva',1,1,'Extra muro','PLANIFICACION FAMILIAR',null,true,'Consejería en salud sexual y reproductiva',1,1,1,1,1,1],
            ['Planificación Familiar - Insumos','Salud Sexual Reproductiva',1,1,'Extra muro','PLANIFICACION FAMILIAR',null,true,'Administración de insumos',1,1,1,1,1,1],
            ['Salud Bucodental - Promoción','Salud Bucodental',1,1,'Extra muro','ODONTOLOGIA',null,true,'Charlas y consejerías',1,1,1,1,1,1],
            ['Prevención Cáncer Cérvico Uterino y Próstata','Prevención Oncológica',1,1,'Extra muro','ONCOLOGIA',null,true,'Información, educación y comunicación',1,1,1,1,1,1],
            ['Detección temprana cardiovascular y diabetes','Detección precoz',1,1,'Extra muro','CARDIOLOGIA',null,true,'Protocolos de cribado',1,1,1,1,1,1],
            ['Salud Ocupacional','Salud Ocupacional',1,1,'Extra muro','SALUD OCUPACIONAL',null,false,'Identificar factores de riesgo',0,0,0,1,1,1],
            ['Vigilancia epidemiológica','Vigilancia de la Salud',1,1,'Extra muro','EPIDEMIOLOGIA',null,true,'Notificación EDO, manejo de brotes',1,1,1,1,1,1],
            ['Prevención IAAS','Vigilancia de la Salud',1,1,'Extra muro','INFECTOLOGIA',null,true,'Vigilancia activa IAAS',0,0,0,1,1,1],
            ['Seguridad del Paciente','Vigilancia de la Salud',1,1,'Extra muro','MEDICINA PREVENTIVA',null,true,'Análisis de casos adversos',0,0,1,1,1,1],
            ['Promoción salud mental','Salud Mental',1,1,'Extra muro','PSICOLOGÍA',null,true,'Consejería integral',1,1,1,1,1,1],
            ['Prevención al Adolescente','Adolescencia',1,1,'Extra muro','PSICOLOGÍA',null,false,'Actividades de promoción y prevención',1,1,1,1,1,1],
            ['Adulto Mayor - Vida Plena','Adulto Mayor',1,1,'Extra muro','MEDICINA PREVENTIVA',null,false,'Atención Integral del Adulto Mayor',0,0,1,1,1,1],

            // ── CONSULTA MÉDICA ─────────────────────────────────────────────────
            ['Consulta Medicina Familiar','Consulta Medicina Familiar',1,1,'Consulta médica','MEDICINA FAMILIAR',null,true,'Consulta ambulatoria',0,1,1,1,1,1],
            ['Consulta Clínica Médica','Consulta Clínica Médica',1,1,'Consulta médica','CLINICA MEDICA',null,true,'Diagnóstico y tratamiento condiciones frecuentes',0,0,0,1,1,1],
            ['Consulta Ginecología y Obstetricia','Consulta Gineco-Obstetricia',1,1,'Consulta médica','GINECO-OBSTETRICIA',null,true,'Control prenatal, planificación familiar',1,1,1,1,1,1],
            ['Control prenatal','Control prenatal',1,1,'Consulta médica','GINECO-OBSTETRICIA',null,true,'Control prenatal',0,1,1,1,1,1],
            ['Control y atención del parto de bajo riesgo','Parto bajo riesgo',1,1,'Consulta médica','GINECO-OBSTETRICIA',null,true,'Parto de bajo riesgo',0,0,0,1,1,1],
            ['Toma de PAP y Colposcopia','Patología Cervical',1,1,'Consulta médica','GINECO-OBSTETRICIA',null,true,'Patología cervical PAP y Colposcopia',0,1,1,1,1,1],
            ['Consulta Odontología adultos','Consulta Odontología',1,1,'Consulta médica','ODONTOLOGÍA',null,true,'Examen dentario y periodontal adultos',0,1,1,1,1,1],
            ['Consulta Odontología pediátrica','Consulta Odontología',1,1,'Consulta médica','ODONTOLOGÍA',null,true,'Examen dentario pediátrico',0,1,1,1,1,1],
            ['Consulta Oftalmología básica','Consulta Oftalmología',1,1,'Consulta médica','OFTALMOLOGIA',null,false,'Toma de agudeza visual',0,0,1,1,1,1],
            ['Consulta Oftalmología compleja','Consulta Oftalmología',2,2,'Consulta médica','OFTALMOLOGIA',null,false,'Manejo de casos complejos',0,0,0,0,1,1],
            ['Consulta Pediatría','Consulta Pediatría',1,1,'Consulta médica','PEDIATRIA',null,true,'Consulta ambulatoria pediátrica',0,1,1,1,1,1],
            ['Consulta Pediatría compleja','Consulta Pediatría',2,2,'Consulta médica','PEDIATRIA',null,false,'Morbilidad compleja',0,0,0,0,1,1],
            ['Consulta Psicología','Consulta Psicología',1,1,'Consulta médica','PSICOLOGIA',null,false,'Consulta ambulatoria',0,1,1,1,1,1],
            ['Consulta Cirugía General','Consulta Cirugía General',1,1,'Consulta médica','CIRUGÍA GENERAL',null,true,'Diagnóstico y derivación',0,0,0,1,1,1],
            ['Consulta Diabetología','Consulta Diabetología',1,1,'Consulta médica','DIABETOLOGÍA',null,false,'RIAD diabetes',0,0,1,1,1,1],
            ['Consulta Nutrición','Consulta Nutrición',1,1,'Consulta médica','NUTRICION',null,false,'Diagnóstico y tratamiento nutricional',0,0,1,1,1,1],

            // ── SERVICIOS INTERMEDIOS ───────────────────────────────────────────
            ['Farmacia básica','Farmacia',1,1,'Intra muro','FARMACIA',null,true,'Dispensación de medicamentos esenciales',1,1,1,1,1,1],
            ['Laboratorio - Toma de muestra','Laboratorio',1,1,'Intra muro','LABORATORIO',null,true,'Toma y procesamiento básico de muestras',0,1,1,1,1,1],
            ['Laboratorio clínico completo','Laboratorio',1,1,'Intra muro','LABORATORIO',null,true,'Hematología, química sanguínea, bacteriología',0,0,0,1,1,1],
            ['Diagnóstico por imágenes - Ecografía','Imágenes',1,1,'Intra muro','RADIOLOGIA',null,false,'Ultrasonido diagnóstico',0,1,1,1,1,1],
            ['Diagnóstico por imágenes - Rayos X','Imágenes',1,1,'Intra muro','RADIOLOGIA',null,true,'Radiología convencional',0,0,0,1,1,1],
            ['Diagnóstico por imágenes - Tomografía','Imágenes',2,2,'Intra muro','RADIOLOGIA',null,false,'Tomografía computada',0,0,0,0,1,1],

            // ── INTERNACIÓN ─────────────────────────────────────────────────────
            ['Internación general','Internación',1,1,'Intra muro','CLINICA MEDICA',null,true,'Camas de internación general',0,0,0,1,1,1],
            ['Internación pediátrica','Consulta Pediatría',1,1,'Intra muro','PEDIATRIA',null,true,'Sala de pediatría',0,0,0,1,1,1],
            ['Urgencias adultos','Urgencias',1,1,'Intra muro','CLINICA MEDICA',null,true,'Atención de urgencias adultos',0,0,0,1,1,1],
            ['Urgencias pediátricas','Urgencias',1,1,'Intra muro','PEDIATRIA',null,true,'Atención de urgencias pediátricas',0,0,0,1,1,1],
            ['Quirófano cirugía mayor','Cirugía',1,1,'Intra muro','CIRUGÍA GENERAL',null,true,'Cirugía mayor electiva y de urgencia',0,0,0,1,1,1],
            ['Sala de partos','Parto bajo riesgo',1,1,'Intra muro','GINECO-OBSTETRICIA',null,true,'Atención del parto',0,0,0,1,1,1],

            // ── UTI ─────────────────────────────────────────────────────────────
            ['Terapia Intensiva Adultos (UTI)','UTI',2,2,'Intra muro','CUIDADOS INTENSIVOS',null,true,'Unidad de terapia intensiva adultos',0,0,0,0,1,1],
            ['Terapia Intensiva Pediátrica (UTIP)','UTI',2,2,'Intra muro','CUIDADOS INTENSIVOS',null,false,'Unidad de terapia intensiva pediátrica',0,0,0,0,1,1],

            // ── ALTA COMPLEJIDAD ────────────────────────────────────────────────
            ['Oncología - Quimioterapia','Oncología',3,3,'Intra muro','ONCOLOGIA',null,false,'Tratamiento oncológico ambulatorio e internado',0,0,0,0,0,1],
            ['Hemodiálisis','Nefrología',3,3,'Intra muro','NEFROLOGIA',null,false,'Servicio de hemodiálisis',0,0,0,0,0,1],
            ['Resonancia Magnética','Imágenes',3,3,'Intra muro','RADIOLOGIA',null,false,'Resonancia magnética diagnóstica',0,0,0,0,0,1],
        ];

        foreach ($servicios as $s) {
            CarteraServicio::create([
                'servicio'                  => $s[0],
                'grupo_servicio'            => $s[1],
                'nivel_atencion'            => $s[2],
                'grado_complejidad'         => $s[3],
                'tipo_establecimiento'      => $s[4],
                'especialidad_1'            => $s[5],
                'especialidad_2'            => $s[6],
                'requerido'                 => $s[7],
                'descripcion'               => $s[8],
                'aplica_puesto_sanitario'   => (bool) $s[9],
                'aplica_unidad_sanitaria'   => (bool) $s[10],
                'aplica_clinica_periferica' => (bool) $s[11],
                'aplica_hospital_baja'      => (bool) $s[12],
                'aplica_hospital_mediana'   => (bool) $s[13],
                'aplica_hospital_alta'      => (bool) $s[14],
                'tipo_prestacion'           => $this->inferirTipoPrestacion($s[4]),
            ]);
        }

        $this->command->info('Servicios de cartera insertados: ' . count($servicios));
    }

    private function inferirTipoPrestacion(string $tipoEst): string
    {
        return match($tipoEst) {
            'Extra muro'  => 'Prevención de enfermedades y Promoción de la salud',
            'Intra muro'  => 'Atención intra muro',
            default       => 'Consulta médica',
        };
    }
}
