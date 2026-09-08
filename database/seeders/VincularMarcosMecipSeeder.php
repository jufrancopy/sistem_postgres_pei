<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Models\Planificacion\MarcoReferencial;
use App\Models\Planificacion\MarcoTipo;

class VincularMarcosMecipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Asegurar que el Tipo 'mecip' existe
        MarcoTipo::firstOrCreate(
            ['clave' => 'mecip'],
            ['label' => 'MECIP 2015', 'color' => '#ffc107', 'activo' => true]
        );

        // 2. Definir los 5 Marcos Referenciales del MECIP
        $marcosMecip = [
            'MECIP - Ambiente de Control'          => 'Normas y principios para el entorno organizacional de control.',
            'MECIP - Administración de Riesgos'    => 'Identificación, evaluación y mitigación de riesgos institucionales.',
            'MECIP - Actividades de Control'       => 'Políticas, procedimientos y controles operativos.',
            'MECIP - Información y Comunicación'   => 'Sistemas transparentes de reporte, datos e información oportuna.',
            'MECIP - Seguimiento y Monitoreo'      => 'Evaluación continua del desempeño y cumplimiento de objetivos.'
        ];

        $marcoIds = [];
        foreach ($marcosMecip as $nombre => $descripcion) {
            $marco = MarcoReferencial::firstOrCreate(
                ['nombre' => $nombre, 'tipo' => 'mecip'],
                ['descripcion' => $descripcion, 'activo' => true]
            );
            $marcoIds[] = $marco->id;
        }

        $this->command->info("✓ 5 Marcos Referenciales MECIP asegurados (IDs: " . implode(', ', $marcoIds) . ")");

        // 3. Obtener los perfiles PEI principales y sus Ejes / Objetivos (Nivel 1)
        $perfilesPei = PeiProfile::whereNull('parent_id')
            ->orWhere('type', 'root')
            ->orWhere('level', 'master')
            ->orWhere('id', 'ce99f883-fdd0-4723-8f75-cf689aa8f0fa')
            ->orWhere('id', '766eb883-fdd0-4723-8f75-cf689aa8f0fa')
            ->get();

        $ejesPei = PeiProfile::whereIn('level', ['axi', 'axis'])->get();

        $todosLosNodos = $perfilesPei->concat($ejesPei)->unique('id');

        $vinculadosCount = 0;
        foreach ($todosLosNodos as $nodo) {
            $nodo->marcos()->syncWithoutDetaching($marcoIds);
            $vinculadosCount++;
        }

        $this->command->info("✓ Marcos MECIP vinculados exitosamente a {$vinculadosCount} perfiles/ejes del PEI.");

        // 4. Poblar / Garantizar Riesgos MECIP 2015 en los Objetivos Estratégicos (Nivel 1)
        $this->sembrarRiesgosMecipEnObjetivos();
    }

    /**
     * Sembrar la matriz de riesgos MECIP 2015 estructurados en los Objetivos Estratégicos.
     */
    private function sembrarRiesgosMecipEnObjetivos(): void
    {
        // ── Matriz de Riesgos para PEI MEF 3 OE (ce99f883-fdd0-4723-8f75-cf689aa8f0fa) ──
        $riesgosMef = [
            1 => [ // OE 1: Salud Misional
                [
                    'aspecto'            => 'Desabastecimiento y Quiebres de Stock en Medicamentos e Insumos Médicos Estratégicos',
                    'ocurrencia'         => 4,
                    'impacto'            => 5,
                    'causa_raiz'         => 'normativa',
                    'accion_mejora'      => 'Licitaciones con contratos abiertos multianuales y sistema de trazabilidad de insumos en tiempo real.',
                    'control_preventivo' => 'Auditoría semanal de quiebres de stock en el Parque Sanitario Central y farmacias de la RIISS.',
                ],
                [
                    'aspecto'            => 'Saturación Hospitalaria y Demora Crítica en Agendamiento y Quirófanos',
                    'ocurrencia'         => 4,
                    'impacto'            => 4,
                    'causa_raiz'         => 'tecnologica',
                    'accion_mejora'      => 'Despliegue del Expediente Electrónico (SIH), agendamiento omnicanal y optimización de la capacidad de camas en la RIISS.',
                    'control_preventivo' => 'Monitoreo diario de tiempos de espera y ocupación quirúrgica mediante el SIH.',
                ],
            ],
            2 => [ // OE 2: Previsión Social Misional
                [
                    'aspecto'            => 'Erosión Actuarial y Riesgo de Descalce Financiero del Fondo Jubilatorio',
                    'ocurrencia'         => 4,
                    'impacto'            => 5,
                    'causa_raiz'         => 'normativa',
                    'accion_mejora'      => 'Proyecto de Ley de Reforma del Régimen Jubilatorio ajustando la base de cálculo salarial al promedio de los últimos 10 años.',
                    'control_preventivo' => 'Dictámenes actuariales trimestrales y fiscalización continua del Consejo de Administración.',
                ],
                [
                    'aspecto'            => 'Mora Patronal Acumulada y Subdeclaración Salarial en Empresas Contribuyentes',
                    'ocurrencia'         => 4,
                    'impacto'            => 4,
                    'causa_raiz'         => 'operativa',
                    'accion_mejora'      => 'Interconexión de bases de datos tributarias con DNIT y MTESS para fiscalización masiva automatizada.',
                    'control_preventivo' => 'Bloqueo preventivo de trámites patronales y emisión automática de Certificados de Deuda ejecutivos.',
                ],
            ],
            3 => [ // OE 3: Fortalecimiento Institucional
                [
                    'aspecto'            => 'Vulnerabilidades de Ciberseguridad e Interrupción de Servicios Tecnológicos (SIH)',
                    'ocurrencia'         => 3,
                    'impacto'            => 5,
                    'causa_raiz'         => 'tecnologica',
                    'accion_mejora'      => 'Plan de Fortalecimiento de Infraestructura Tecnológica Híbrida alineado al Marco de Ciberseguridad MITIC.',
                    'control_preventivo' => 'Centro de Operaciones de Seguridad (SOC) 24/7 y respaldos inmutables en la nube.',
                ],
                [
                    'aspecto'            => 'Deficiente Apropiación de Procesos de Control Interno y Gobernanza MECIP 2015',
                    'ocurrencia'         => 3,
                    'impacto'            => 4,
                    'causa_raiz'         => 'normativa',
                    'accion_mejora'      => 'Despliegue del portal institucional de autoevaluación MECIP y capacitación obligatoria a mandos medios.',
                    'control_preventivo' => 'Auditorías semestrales de la Norma Requisito MECIP por la Auditoría Interna Institucional.',
                ],
            ],
        ];

        $masterMef = PeiProfile::find('ce99f883-fdd0-4723-8f75-cf689aa8f0fa');
        if ($masterMef) {
            $axesMef = $masterMef->children()->where('level', 'axi')->orderBy('order_item')->get();
            foreach ($axesMef as $idx => $axi) {
                $num = $axi->order_item ?: ($idx + 1);
                if (isset($riesgosMef[$num])) {
                    $params = is_array($axi->parameters) ? $axi->parameters : (json_decode($axi->parameters ?? '', true) ?: []);
                    $params['riesgos_mecip'] = $riesgosMef[$num];
                    $axi->parameters = $params;
                    $axi->saveQuietly();
                    $this->command->info("  ✓ Riesgos MECIP sembrados en MEF OE {$num}: " . strip_tags($axi->name));
                }
            }
        }

        // ── Matriz de Riesgos para PEI Original 6 OE (766eb883-fdd0-4723-8f75-cf689aa8f0fa) ──
        $riesgosOrig = [
            1 => [
                [
                    'aspecto'            => 'Brecha de Ciberseguridad y Resguardo de Información Institucional',
                    'ocurrencia'         => 3, 'impacto' => 5, 'causa_raiz' => 'tecnologica',
                    'accion_mejora'      => 'Implementar la política de ciberseguridad del MITIC y rediseñar procesos institucionales.',
                    'control_preventivo' => 'Auditorías semestrales de ciberseguridad y plan de trabajo de Normas MECIP.',
                ],
            ],
            2 => [
                [
                    'aspecto'            => 'Mortalidad Materna y Neonatal Evitable en Establecimientos RIISS',
                    'ocurrencia'         => 3, 'impacto' => 5, 'causa_raiz' => 'estructural',
                    'accion_mejora'      => 'Mejorar la atención obstétrica y neonatal e implementar Redes Temáticas Integradas.',
                    'control_preventivo' => 'Protocolos de atención según criterios epidemiológicos y control neonatal estricto.',
                ],
            ],
            3 => [
                [
                    'aspecto'            => 'Quiebre de Trazabilidad y Desabastecimiento de Insumos Médicos',
                    'ocurrencia'         => 4, 'impacto' => 4, 'causa_raiz' => 'operativa',
                    'accion_mejora'      => 'Asegurar trazabilidad informática y expandir el Expediente Electrónico y Telemedicina.',
                    'control_preventivo' => 'Alertas de stock mínimo y auditorías de consumo en farmacias hospitalarias.',
                ],
            ],
            4 => [
                [
                    'aspecto'            => 'Demoras en la Concesión y Descentralización de Prestaciones Previsionales',
                    'ocurrencia'         => 3, 'impacto' => 4, 'causa_raiz' => 'operativa',
                    'accion_mejora'      => 'Automatizar y descentralizar el otorgamiento de prestaciones económicas vía canales digitales.',
                    'control_preventivo' => 'Tablero de control de expedientes de jubilación y monitoreo de plazos normativos.',
                ],
            ],
            5 => [
                [
                    'aspecto'            => 'Desbalance Financiero por Evasión Patronal y Gestión de Inversiones',
                    'ocurrencia'         => 4, 'impacto' => 5, 'causa_raiz' => 'normativa',
                    'accion_mejora'      => 'Aumentar recaudación por vínculos interinstitucionales y diversificar inversiones con rentabilidad.',
                    'control_preventivo' => 'Fiscalizaciones cruzadas y control trimestral de colocación de reservas técnicas.',
                ],
            ],
            6 => [
                [
                    'aspecto'            => 'Ineficiencia en Procesos PAC y Alta Rotación del Talento Humano',
                    'ocurrencia'         => 3, 'impacto' => 4, 'causa_raiz' => 'estructural',
                    'accion_mejora'      => 'Optimizar ejecución del PAC, concursos de méritos y fortalecimiento de la Carrera del Talento.',
                    'control_preventivo' => 'Cronograma de adquisiciones alineado a la DNCP y evaluaciones de desempeño.',
                ],
            ],
        ];

        $masterOrig = PeiProfile::find('766eb883-fdd0-4723-8f75-cf689aa8f0fa');
        if ($masterOrig) {
            $axesOrig = $masterOrig->children()->where('level', 'axi')->orderBy('order_item')->get();
            foreach ($axesOrig as $idx => $axi) {
                $num = $axi->order_item ?: ($idx + 1);
                if (isset($riesgosOrig[$num])) {
                    $params = is_array($axi->parameters) ? $axi->parameters : (json_decode($axi->parameters ?? '', true) ?: []);
                    $params['riesgos_mecip'] = $riesgosOrig[$num];
                    $axi->parameters = $params;
                    $axi->saveQuietly();
                    $this->command->info("  ✓ Riesgos MECIP sembrados en PEI Original Eje {$num}: " . strip_tags($axi->name));
                }
            }
        }
    }
}
